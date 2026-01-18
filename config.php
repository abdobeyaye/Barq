<?php
session_start();

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'barq');
define('DB_USER', 'root');
define('DB_PASS', '');

// Application settings
$whatsapp_number = "22241312931";
$help_email = "help@barqmr.com";
$help_phone = "+222 41 31 29 31";
$points_cost_per_order = 20;

// Database connection
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Initialize database schema
function initializeDatabase($pdo) {
    // Create users1 table
    $pdo->exec("CREATE TABLE IF NOT EXISTS users1 (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(100) UNIQUE,
        phone VARCHAR(20) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin', 'driver', 'customer') DEFAULT 'customer',
        points INT DEFAULT 0,
        full_name VARCHAR(255),
        rating DECIMAL(3,2) DEFAULT 0.00,
        total_ratings INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // Create districts table
    $pdo->exec("CREATE TABLE IF NOT EXISTS districts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name_en VARCHAR(100) NOT NULL,
        name_ar VARCHAR(100) NOT NULL,
        active TINYINT(1) DEFAULT 1
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // Create district_prices table
    $pdo->exec("CREATE TABLE IF NOT EXISTS district_prices (
        id INT AUTO_INCREMENT PRIMARY KEY,
        from_district_id INT NOT NULL,
        to_district_id INT NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        FOREIGN KEY (from_district_id) REFERENCES districts(id),
        FOREIGN KEY (to_district_id) REFERENCES districts(id),
        UNIQUE KEY unique_route (from_district_id, to_district_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // Create driver_districts table
    $pdo->exec("CREATE TABLE IF NOT EXISTS driver_districts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        driver_id INT NOT NULL,
        district_id INT NOT NULL,
        FOREIGN KEY (driver_id) REFERENCES users1(id) ON DELETE CASCADE,
        FOREIGN KEY (district_id) REFERENCES districts(id) ON DELETE CASCADE,
        UNIQUE KEY unique_driver_district (driver_id, district_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // Create orders1 table
    $pdo->exec("CREATE TABLE IF NOT EXISTS orders1 (
        id INT AUTO_INCREMENT PRIMARY KEY,
        customer_id INT NOT NULL,
        driver_id INT NULL,
        order_details TEXT NOT NULL,
        customer_phone VARCHAR(20) NOT NULL,
        pickup_district_id INT NOT NULL,
        delivery_district_id INT NOT NULL,
        delivery_fee DECIMAL(10,2) NOT NULL,
        detailed_address TEXT NOT NULL,
        status ENUM('pending', 'accepted', 'picked_up', 'delivered', 'cancelled') DEFAULT 'pending',
        delivery_code VARCHAR(4),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (customer_id) REFERENCES users1(id),
        FOREIGN KEY (driver_id) REFERENCES users1(id),
        FOREIGN KEY (pickup_district_id) REFERENCES districts(id),
        FOREIGN KEY (delivery_district_id) REFERENCES districts(id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // Create ratings table
    $pdo->exec("CREATE TABLE IF NOT EXISTS ratings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT NOT NULL,
        driver_id INT NOT NULL,
        customer_id INT NOT NULL,
        rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
        comment TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (order_id) REFERENCES orders1(id),
        FOREIGN KEY (driver_id) REFERENCES users1(id),
        FOREIGN KEY (customer_id) REFERENCES users1(id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // Create serial_counters table
    $pdo->exec("CREATE TABLE IF NOT EXISTS serial_counters (
        id INT AUTO_INCREMENT PRIMARY KEY,
        counter_name VARCHAR(50) UNIQUE NOT NULL,
        counter_value INT DEFAULT 0
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // Insert districts if not exists
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM districts");
    if ($stmt->fetch()['count'] == 0) {
        $districts = [
            ['Tevragh Zeina', 'تفرغ زينة'],
            ['Ksar', 'لكصر'],
            ['Sebkha', 'سبخة'],
            ['Teyarett', 'تيارت'],
            ['Dar Naïm', 'دار النعيم'],
            ['Toujounine', 'توجنين'],
            ['Arafat', 'عرفات'],
            ['El Mina', 'الميناء'],
            ['Riyad', 'الرياض'],
            ['Tarhil', 'الترحيل']
        ];

        $stmt = $pdo->prepare("INSERT INTO districts (name_en, name_ar) VALUES (?, ?)");
        foreach ($districts as $district) {
            $stmt->execute($district);
        }
    }

    // Insert district prices if not exists
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM district_prices");
    if ($stmt->fetch()['count'] == 0) {
        $prices = [
            // Tevragh Zeina (1)
            [1,1,100],[1,2,100],[1,3,100],[1,4,150],[1,5,200],[1,6,200],[1,7,150],[1,8,150],[1,9,200],[1,10,200],
            // Ksar (2)
            [2,1,100],[2,2,100],[2,3,100],[2,4,100],[2,5,100],[2,6,150],[2,7,150],[2,8,150],[2,9,200],[2,10,200],
            // Sebkha (3)
            [3,1,100],[3,2,100],[3,3,100],[3,4,200],[3,5,200],[3,6,200],[3,7,150],[3,8,100],[3,9,150],[3,10,200],
            // Teyarett (4)
            [4,1,150],[4,2,100],[4,3,200],[4,4,100],[4,5,100],[4,6,150],[4,7,200],[4,8,200],[4,9,200],[4,10,200],
            // Dar Naïm (5)
            [5,1,200],[5,2,100],[5,3,200],[5,4,100],[5,5,100],[5,6,100],[5,7,150],[5,8,200],[5,9,200],[5,10,200],
            // Toujounine (6)
            [6,1,200],[6,2,150],[6,3,200],[6,4,150],[6,5,100],[6,6,100],[6,7,100],[6,8,200],[6,9,150],[6,10,200],
            // Arafat (7)
            [7,1,150],[7,2,150],[7,3,150],[7,4,200],[7,5,150],[7,6,100],[7,7,100],[7,8,100],[7,9,100],[7,10,200],
            // El Mina (8)
            [8,1,150],[8,2,150],[8,3,100],[8,4,200],[8,5,200],[8,6,200],[8,7,100],[8,8,100],[8,9,100],[8,10,200],
            // Riyad (9)
            [9,1,200],[9,2,200],[9,3,150],[9,4,200],[9,5,200],[9,6,150],[9,7,100],[9,8,100],[9,9,100],[9,10,200],
            // Tarhil (10)
            [10,1,200],[10,2,200],[10,3,200],[10,4,200],[10,5,200],[10,6,200],[10,7,200],[10,8,200],[10,9,200],[10,10,100]
        ];

        $stmt = $pdo->prepare("INSERT INTO district_prices (from_district_id, to_district_id, price) VALUES (?, ?, ?)");
        foreach ($prices as $price) {
            $stmt->execute($price);
        }
    }

    // Insert default users if not exists
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users1");
    if ($stmt->fetch()['count'] == 0) {
        $password_hash = password_hash('123', PASSWORD_DEFAULT);
        
        // Admin user
        $stmt = $pdo->prepare("INSERT INTO users1 (phone, password, role, full_name, points) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute(['20000001', $password_hash, 'admin', 'Admin User', 0]);
        
        // Driver user
        $stmt->execute(['30000002', $password_hash, 'driver', 'Driver User', 50]);
        
        // Customer user
        $stmt->execute(['40000003', $password_hash, 'customer', 'Customer User', 0]);
    }
}

// Initialize database on first run
initializeDatabase($pdo);
