<div class="row">
    <div class="col-md-12">
        <h2><?php echo t('admin_panel', $lang); ?></h2>
        
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h1 class="display-4">
                            <?php
                            $stmt = $pdo->query("SELECT COUNT(*) as count FROM users1");
                            echo $stmt->fetch()['count'];
                            ?>
                        </h1>
                        <p><?php echo t('users', $lang); ?></p>
                        <a href="index.php?page=admin_users" class="btn btn-primary"><?php echo t('manage_users', $lang); ?></a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h1 class="display-4">
                            <?php
                            $stmt = $pdo->query("SELECT COUNT(*) as count FROM orders1");
                            echo $stmt->fetch()['count'];
                            ?>
                        </h1>
                        <p><?php echo t('orders', $lang); ?></p>
                        <a href="index.php?page=admin_orders" class="btn btn-primary"><?php echo t('manage_orders', $lang); ?></a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h1 class="display-4">
                            <?php
                            $stmt = $pdo->query("SELECT COUNT(*) as count FROM districts");
                            echo $stmt->fetch()['count'];
                            ?>
                        </h1>
                        <p><?php echo t('districts', $lang); ?></p>
                        <a href="index.php?page=admin_districts" class="btn btn-primary"><?php echo t('manage_districts', $lang); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
