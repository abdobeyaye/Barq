<?php
require_once 'config.php';
require_once 'functions.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'login':
        $phone = $_POST['phone'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if ($phone && $password) {
            $stmt = $pdo->prepare("SELECT * FROM users1 WHERE phone = ?");
            $stmt->execute([$phone]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['full_name'] = $user['full_name'];
                setFlash('message', t('login_success', getCurrentLang()));
                redirect('index.php');
            } else {
                setFlash('error', t('invalid_credentials', getCurrentLang()), 'danger');
                redirect('index.php');
            }
        }
        break;
        
    case 'register':
        $phone = $_POST['phone'] ?? '';
        $password = $_POST['password'] ?? '';
        $full_name = $_POST['full_name'] ?? '';
        $role = $_POST['role'] ?? 'customer';
        
        if ($phone && $password && $full_name) {
            // Check if phone exists
            $stmt = $pdo->prepare("SELECT id FROM users1 WHERE phone = ?");
            $stmt->execute([$phone]);
            if ($stmt->fetch()) {
                setFlash('error', t('phone_exists', getCurrentLang()), 'danger');
                redirect('index.php');
                exit;
            }
            
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users1 (phone, password, full_name, role, points) VALUES (?, ?, ?, ?, ?)");
            $initial_points = ($role === 'driver') ? 50 : 0;
            $stmt->execute([$phone, $password_hash, $full_name, $role, $initial_points]);
            
            setFlash('message', t('register_success', getCurrentLang()));
            redirect('index.php');
        } else {
            setFlash('error', t('required_fields', getCurrentLang()), 'danger');
            redirect('index.php');
        }
        break;
        
    case 'logout':
        session_destroy();
        redirect('index.php');
        break;
        
    case 'update_profile':
        if (!isLoggedIn()) {
            redirect('index.php');
            exit;
        }
        
        $full_name = $_POST['full_name'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if ($full_name) {
            if ($password) {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users1 SET full_name = ?, password = ? WHERE id = ?");
                $stmt->execute([$full_name, $password_hash, $_SESSION['user_id']]);
            } else {
                $stmt = $pdo->prepare("UPDATE users1 SET full_name = ? WHERE id = ?");
                $stmt->execute([$full_name, $_SESSION['user_id']]);
            }
            
            $_SESSION['full_name'] = $full_name;
            setFlash('message', t('profile_updated', getCurrentLang()));
        }
        redirect('index.php?page=profile');
        break;
        
    case 'update_driver_districts':
        if (!isLoggedIn() || !hasRole('driver')) {
            redirect('index.php');
            exit;
        }
        
        $districts = $_POST['districts'] ?? [];
        
        // Delete existing districts
        $stmt = $pdo->prepare("DELETE FROM driver_districts WHERE driver_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        
        // Insert new districts
        if (!empty($districts)) {
            $stmt = $pdo->prepare("INSERT INTO driver_districts (driver_id, district_id) VALUES (?, ?)");
            foreach ($districts as $district_id) {
                $stmt->execute([$_SESSION['user_id'], $district_id]);
            }
        }
        
        setFlash('message', t('districts_updated', getCurrentLang()));
        redirect('index.php?page=settings');
        break;
        
    case 'place_order':
        if (!isLoggedIn() || !hasRole('customer')) {
            redirect('index.php');
            exit;
        }
        
        $order_details = $_POST['order_details'] ?? '';
        $customer_phone = $_POST['customer_phone'] ?? '';
        $pickup_district_id = $_POST['pickup_district_id'] ?? 0;
        $delivery_district_id = $_POST['delivery_district_id'] ?? 0;
        $detailed_address = $_POST['detailed_address'] ?? '';
        
        if ($order_details && $customer_phone && $pickup_district_id && $delivery_district_id && $detailed_address) {
            // Get delivery fee
            $stmt = $pdo->prepare("SELECT price FROM district_prices WHERE from_district_id = ? AND to_district_id = ?");
            $stmt->execute([$pickup_district_id, $delivery_district_id]);
            $price_result = $stmt->fetch();
            
            if ($price_result) {
                $delivery_fee = $price_result['price'];
                $delivery_code = generateDeliveryCode();
                
                $stmt = $pdo->prepare("INSERT INTO orders1 (customer_id, order_details, customer_phone, pickup_district_id, delivery_district_id, delivery_fee, detailed_address, delivery_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$_SESSION['user_id'], $order_details, $customer_phone, $pickup_district_id, $delivery_district_id, $delivery_fee, $detailed_address, $delivery_code]);
                
                setFlash('message', t('order_placed', getCurrentLang()));
            } else {
                setFlash('error', 'Invalid districts', 'danger');
            }
        } else {
            setFlash('error', t('required_fields', getCurrentLang()), 'danger');
        }
        redirect('index.php');
        break;
        
    case 'cancel_order':
        if (!isLoggedIn()) {
            redirect('index.php');
            exit;
        }
        
        $order_id = $_POST['order_id'] ?? 0;
        
        // Check if order belongs to user and status is pending or accepted
        $stmt = $pdo->prepare("SELECT * FROM orders1 WHERE id = ? AND customer_id = ? AND status IN ('pending', 'accepted')");
        $stmt->execute([$order_id, $_SESSION['user_id']]);
        $order = $stmt->fetch();
        
        if ($order) {
            $stmt = $pdo->prepare("UPDATE orders1 SET status = 'cancelled' WHERE id = ?");
            $stmt->execute([$order_id]);
            setFlash('message', t('order_cancelled', getCurrentLang()));
        }
        redirect('index.php');
        break;
        
    case 'accept_order':
        if (!isLoggedIn() || !hasRole('driver')) {
            redirect('index.php');
            exit;
        }
        
        $order_id = $_POST['order_id'] ?? 0;
        
        // Check if driver has enough points
        $user = getCurrentUser($pdo);
        if ($user['points'] < $points_cost_per_order) {
            setFlash('error', t('insufficient_points', getCurrentLang()), 'danger');
            redirect('index.php');
            exit;
        }
        
        // Check if order is pending
        $stmt = $pdo->prepare("SELECT * FROM orders1 WHERE id = ? AND status = 'pending'");
        $stmt->execute([$order_id]);
        $order = $stmt->fetch();
        
        if ($order) {
            // Deduct points
            $stmt = $pdo->prepare("UPDATE users1 SET points = points - ? WHERE id = ?");
            $stmt->execute([$points_cost_per_order, $_SESSION['user_id']]);
            
            // Accept order
            $stmt = $pdo->prepare("UPDATE orders1 SET driver_id = ?, status = 'accepted' WHERE id = ?");
            $stmt->execute([$_SESSION['user_id'], $order_id]);
            
            setFlash('message', t('order_accepted', getCurrentLang()));
        }
        redirect('index.php');
        break;
        
    case 'pickup_order':
        if (!isLoggedIn() || !hasRole('driver')) {
            redirect('index.php');
            exit;
        }
        
        $order_id = $_POST['order_id'] ?? 0;
        
        $stmt = $pdo->prepare("UPDATE orders1 SET status = 'picked_up' WHERE id = ? AND driver_id = ? AND status = 'accepted'");
        $stmt->execute([$order_id, $_SESSION['user_id']]);
        
        setFlash('message', t('order_picked_up', getCurrentLang()));
        redirect('index.php');
        break;
        
    case 'deliver_order':
        if (!isLoggedIn() || !hasRole('driver')) {
            redirect('index.php');
            exit;
        }
        
        $order_id = $_POST['order_id'] ?? 0;
        
        $stmt = $pdo->prepare("UPDATE orders1 SET status = 'delivered' WHERE id = ? AND driver_id = ? AND status = 'picked_up'");
        $stmt->execute([$order_id, $_SESSION['user_id']]);
        
        setFlash('message', t('order_delivered', getCurrentLang()));
        redirect('index.php');
        break;
        
    case 'admin_add_points':
        if (!isLoggedIn() || !hasRole('admin')) {
            redirect('index.php');
            exit;
        }
        
        $user_id = $_POST['user_id'] ?? 0;
        $points = $_POST['points'] ?? 0;
        
        if ($user_id && $points) {
            $stmt = $pdo->prepare("UPDATE users1 SET points = points + ? WHERE id = ?");
            $stmt->execute([$points, $user_id]);
            setFlash('message', 'Points added successfully');
        }
        redirect('index.php?page=admin_users');
        break;
        
    case 'set_lang':
        $lang = $_GET['lang'] ?? 'ar';
        setLang($lang);
        redirect($_SERVER['HTTP_REFERER'] ?? 'index.php');
        break;
        
    default:
        redirect('index.php');
        break;
}
