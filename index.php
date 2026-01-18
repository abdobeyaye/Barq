<?php
require_once 'config.php';
require_once 'functions.php';

$lang = getCurrentLang();
$page = $_GET['page'] ?? 'home';
$user = getCurrentUser($pdo);

// Get districts
$stmt = $pdo->query("SELECT * FROM districts WHERE active = 1 ORDER BY id");
$districts = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" dir="<?php echo $lang === 'ar' ? 'rtl' : 'ltr'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo t('app_name', $lang); ?> - <?php echo t('tagline', $lang); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-lightning-charge-fill"></i> <?php echo t('app_name', $lang); ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">
                                <i class="bi bi-house"></i> <?php echo t('dashboard', $lang); ?>
                            </a>
                        </li>
                        <?php if (hasRole('customer')): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=orders">
                                <i class="bi bi-box"></i> <?php echo t('my_orders', $lang); ?>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if (hasRole('driver')): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=settings">
                                <i class="bi bi-gear"></i> <?php echo t('settings', $lang); ?>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if (hasRole('admin')): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-shield"></i> <?php echo t('admin_panel', $lang); ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="index.php?page=admin_users"><?php echo t('users', $lang); ?></a></li>
                                <li><a class="dropdown-item" href="index.php?page=admin_orders"><?php echo t('orders', $lang); ?></a></li>
                                <li><a class="dropdown-item" href="index.php?page=admin_districts"><?php echo t('districts', $lang); ?></a></li>
                            </ul>
                        </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=profile">
                                <i class="bi bi-person"></i> <?php echo e($_SESSION['full_name']); ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="actions.php?action=logout">
                                <i class="bi bi-box-arrow-right"></i> <?php echo t('logout', $lang); ?>
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=login">
                                <?php echo t('login', $lang); ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=register">
                                <?php echo t('register', $lang); ?>
                            </a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="actions.php?action=set_lang&lang=<?php echo $lang === 'ar' ? 'fr' : 'ar'; ?>">
                            <?php echo $lang === 'ar' ? 'FR' : 'عربي'; ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php
    $flash = getFlash('message');
    $error = getFlash('error');
    if ($flash): ?>
        <div class="container mt-3">
            <div class="alert alert-<?php echo $flash['type']; ?> alert-dismissible fade show" role="alert">
                <?php echo e($flash['message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="container mt-3">
            <div class="alert alert-<?php echo $error['type']; ?> alert-dismissible fade show" role="alert">
                <?php echo e($error['message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    <?php endif; ?>

    <!-- Main Content -->
    <div class="container mt-4">
        <?php
        // Route pages
        if (!isLoggedIn() && !in_array($page, ['home', 'login', 'register'])) {
            $page = 'login';
        }

        switch ($page) {
            case 'login':
                include 'pages/login.php';
                break;
            case 'register':
                include 'pages/register.php';
                break;
            case 'profile':
                include 'pages/profile.php';
                break;
            case 'orders':
                include 'pages/orders.php';
                break;
            case 'settings':
                include 'pages/settings.php';
                break;
            case 'admin_users':
                include 'pages/admin_users.php';
                break;
            case 'admin_orders':
                include 'pages/admin_orders.php';
                break;
            case 'admin_districts':
                include 'pages/admin_districts.php';
                break;
            case 'home':
            default:
                if (isLoggedIn()) {
                    if (hasRole('customer')) {
                        include 'pages/customer_dashboard.php';
                    } elseif (hasRole('driver')) {
                        include 'pages/driver_dashboard.php';
                    } elseif (hasRole('admin')) {
                        include 'pages/admin_dashboard.php';
                    }
                } else {
                    include 'pages/welcome.php';
                }
                break;
        }
        ?>
    </div>

    <!-- Footer -->
    <footer class="mt-5 py-4 bg-light">
        <div class="container text-center">
            <p class="mb-1"><?php echo t('app_name', $lang); ?> - <?php echo t('tagline', $lang); ?></p>
            <p class="text-muted">
                <i class="bi bi-whatsapp"></i> <?php echo $whatsapp_number; ?> | 
                <i class="bi bi-envelope"></i> <?php echo $help_email; ?> | 
                <i class="bi bi-telephone"></i> <?php echo $help_phone; ?>
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/app.js"></script>
</body>
</html>
