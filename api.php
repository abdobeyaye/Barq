<?php
require_once 'config.php';
require_once 'functions.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'calculate_fee':
        $from = $_GET['from'] ?? 0;
        $to = $_GET['to'] ?? 0;
        
        if ($from && $to) {
            $stmt = $pdo->prepare("SELECT price FROM district_prices WHERE from_district_id = ? AND to_district_id = ?");
            $stmt->execute([$from, $to]);
            $result = $stmt->fetch();
            
            if ($result) {
                echo json_encode(['success' => true, 'fee' => $result['price']]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Price not found']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Invalid parameters']);
        }
        break;
        
    case 'get_districts':
        $stmt = $pdo->query("SELECT * FROM districts WHERE active = 1 ORDER BY id");
        $districts = $stmt->fetchAll();
        echo json_encode(['success' => true, 'districts' => $districts]);
        break;
        
    case 'get_driver_districts':
        if (!isLoggedIn() || !hasRole('driver')) {
            echo json_encode(['success' => false, 'error' => 'Unauthorized']);
            exit;
        }
        
        $stmt = $pdo->prepare("SELECT district_id FROM driver_districts WHERE driver_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $districts = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo json_encode(['success' => true, 'districts' => $districts]);
        break;
        
    default:
        echo json_encode(['success' => false, 'error' => 'Invalid action']);
        break;
}
