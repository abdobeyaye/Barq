<?php
/**
 * Installation and System Check
 * Visit this file once to verify the system is properly configured
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

$checks = [];

// Check PHP version
$checks['PHP Version'] = [
    'required' => '7.4.0',
    'current' => PHP_VERSION,
    'status' => version_compare(PHP_VERSION, '7.4.0', '>=')
];

// Check PDO MySQL extension
$checks['PDO Extension'] = [
    'required' => 'Required',
    'current' => extension_loaded('pdo') ? 'Installed' : 'Not Installed',
    'status' => extension_loaded('pdo')
];

$checks['PDO MySQL Driver'] = [
    'required' => 'Required',
    'current' => extension_loaded('pdo_mysql') ? 'Installed' : 'Not Installed',
    'status' => extension_loaded('pdo_mysql')
];

// Check if config.php exists
$checks['config.php'] = [
    'required' => 'Required',
    'current' => file_exists('config.php') ? 'Found' : 'Not Found',
    'status' => file_exists('config.php')
];

// Try to connect to database
$db_status = false;
$db_message = '';
try {
    require_once 'config.php';
    $db_status = true;
    $db_message = 'Connected successfully';
    
    // Check if tables exist
    $tables = ['users1', 'districts', 'district_prices', 'driver_districts', 'orders1', 'ratings', 'serial_counters'];
    $table_status = [];
    foreach ($tables as $table) {
        try {
            $stmt = $pdo->query("SELECT 1 FROM $table LIMIT 1");
            $table_status[$table] = true;
        } catch (PDOException $e) {
            $table_status[$table] = false;
        }
    }
    
} catch (Exception $e) {
    $db_message = 'Connection failed: ' . $e->getMessage();
}

$checks['Database Connection'] = [
    'required' => 'Required',
    'current' => $db_message,
    'status' => $db_status
];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barq System Check</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Barq System Installation Check</h1>
        
        <div class="card">
            <div class="card-header">
                <h3>System Requirements</h3>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Component</th>
                            <th>Required</th>
                            <th>Current</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($checks as $name => $check): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($name); ?></td>
                            <td><?php echo htmlspecialchars($check['required']); ?></td>
                            <td><?php echo htmlspecialchars($check['current']); ?></td>
                            <td>
                                <?php if ($check['status']): ?>
                                    <span class="badge bg-success">✓ OK</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">✗ Failed</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <?php if ($db_status && isset($table_status)): ?>
        <div class="card mt-4">
            <div class="card-header">
                <h3>Database Tables</h3>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Table Name</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($table_status as $table => $status): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($table); ?></td>
                            <td>
                                <?php if ($status): ?>
                                    <span class="badge bg-success">✓ Exists</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">✗ Missing</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="alert alert-info mt-4">
            <h4>Default Users</h4>
            <ul>
                <li><strong>Admin:</strong> Phone: 20000001, Password: 123</li>
                <li><strong>Driver:</strong> Phone: 30000002, Password: 123 (50 points)</li>
                <li><strong>Customer:</strong> Phone: 40000003, Password: 123</li>
            </ul>
        </div>
        <?php endif; ?>
        
        <?php
        $all_ok = true;
        foreach ($checks as $check) {
            if (!$check['status']) {
                $all_ok = false;
                break;
            }
        }
        ?>
        
        <?php if ($all_ok): ?>
        <div class="alert alert-success mt-4">
            <h4>✓ All checks passed!</h4>
            <p>Your system is properly configured. You can now use the application.</p>
            <a href="index.php" class="btn btn-primary">Go to Application</a>
        </div>
        <?php else: ?>
        <div class="alert alert-danger mt-4">
            <h4>✗ Some checks failed</h4>
            <p>Please fix the issues above before using the application.</p>
        </div>
        <?php endif; ?>
        
        <div class="card mt-4">
            <div class="card-header">
                <h3>Configuration</h3>
            </div>
            <div class="card-body">
                <?php if (isset($whatsapp_number)): ?>
                <p><strong>WhatsApp:</strong> <?php echo htmlspecialchars($whatsapp_number); ?></p>
                <p><strong>Help Email:</strong> <?php echo htmlspecialchars($help_email); ?></p>
                <p><strong>Help Phone:</strong> <?php echo htmlspecialchars($help_phone); ?></p>
                <p><strong>Points Cost per Order:</strong> <?php echo htmlspecialchars($points_cost_per_order); ?></p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="alert alert-warning mt-4">
            <strong>Security Note:</strong> Delete this file (install.php) after installation for security reasons.
        </div>
    </div>
</body>
</html>
