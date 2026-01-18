<div class="row">
    <div class="col-md-12">
        <h2><?php echo t('welcome', $lang); ?>, <?php echo e($user['full_name']); ?>!</h2>
        <p><?php echo t('my_points', $lang); ?>: <strong><?php echo e($user['points']); ?></strong></p>
        
        <!-- Available Orders -->
        <div class="card mt-4">
            <div class="card-header">
                <h4><?php echo t('available_orders', $lang); ?></h4>
            </div>
            <div class="card-body">
                <?php
                // Get driver's selected districts
                $stmt = $pdo->prepare("SELECT district_id FROM driver_districts WHERE driver_id = ?");
                $stmt->execute([$_SESSION['user_id']]);
                $driver_districts = $stmt->fetchAll(PDO::FETCH_COLUMN);
                
                if (!empty($driver_districts)) {
                    $placeholders = implode(',', array_fill(0, count($driver_districts), '?'));
                    $stmt = $pdo->prepare("
                        SELECT o.*, 
                               d1.name_en as pickup_en, d1.name_ar as pickup_ar,
                               d2.name_en as delivery_en, d2.name_ar as delivery_ar,
                               c.full_name as customer_name, c.phone as customer_phone
                        FROM orders1 o
                        LEFT JOIN districts d1 ON o.pickup_district_id = d1.id
                        LEFT JOIN districts d2 ON o.delivery_district_id = d2.id
                        LEFT JOIN users1 c ON o.customer_id = c.id
                        WHERE o.status = 'pending' 
                        AND o.pickup_district_id IN ($placeholders)
                        ORDER BY o.created_at DESC
                    ");
                    $stmt->execute($driver_districts);
                    $available_orders = $stmt->fetchAll();
                    
                    if (count($available_orders) > 0):
                    ?>
                    <div class="row">
                        <?php foreach ($available_orders as $order): ?>
                        <div class="col-md-6 mb-3">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo t('order', $lang) ?? 'Order'; ?> #<?php echo $order['id']; ?></h5>
                                    <p><strong><?php echo t('customer_name', $lang); ?>:</strong> <?php echo e($order['customer_name']); ?></p>
                                    <p><strong><?php echo t('customer_phone', $lang); ?>:</strong> 
                                        <?php echo e($order['customer_phone']); ?>
                                        <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $order['customer_phone']); ?>" target="_blank" class="btn btn-sm btn-success ms-2">
                                            <i class="bi bi-whatsapp"></i>
                                        </a>
                                    </p>
                                    <p><strong><?php echo t('order_details', $lang); ?>:</strong> <?php echo e($order['order_details']); ?></p>
                                    <p><strong><?php echo t('from', $lang); ?>:</strong> <?php echo $lang === 'ar' ? $order['pickup_ar'] : $order['pickup_en']; ?></p>
                                    <p><strong><?php echo t('to', $lang); ?>:</strong> <?php echo $lang === 'ar' ? $order['delivery_ar'] : $order['delivery_en']; ?></p>
                                    <p><strong><?php echo t('detailed_address', $lang); ?>:</strong> <?php echo e($order['detailed_address']); ?></p>
                                    <p><strong><?php echo t('delivery_fee', $lang); ?>:</strong> <?php echo $order['delivery_fee']; ?> <?php echo t('mru', $lang); ?></p>
                                    <form method="POST" action="actions.php">
                                        <input type="hidden" name="action" value="accept_order">
                                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <?php echo t('accept_order', $lang); ?> (<?php echo $points_cost_per_order; ?> <?php echo t('points', $lang); ?>)
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                        <p class="text-muted"><?php echo t('no_orders', $lang); ?></p>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="alert alert-warning">
                        <?php echo t('select_districts_first', $lang) ?? 'Please select your operating districts in settings first.'; ?>
                        <a href="index.php?page=settings" class="alert-link"><?php echo t('settings', $lang); ?></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- My Active Orders -->
        <div class="card mt-4">
            <div class="card-header">
                <h4><?php echo t('my_active_orders', $lang); ?></h4>
            </div>
            <div class="card-body">
                <?php
                $stmt = $pdo->prepare("
                    SELECT o.*, 
                           d1.name_en as pickup_en, d1.name_ar as pickup_ar,
                           d2.name_en as delivery_en, d2.name_ar as delivery_ar,
                           c.full_name as customer_name, c.phone as customer_phone
                    FROM orders1 o
                    LEFT JOIN districts d1 ON o.pickup_district_id = d1.id
                    LEFT JOIN districts d2 ON o.delivery_district_id = d2.id
                    LEFT JOIN users1 c ON o.customer_id = c.id
                    WHERE o.driver_id = ? AND o.status IN ('accepted', 'picked_up')
                    ORDER BY o.created_at DESC
                ");
                $stmt->execute([$_SESSION['user_id']]);
                $my_orders = $stmt->fetchAll();
                
                if (count($my_orders) > 0):
                ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?php echo t('customer_name', $lang); ?></th>
                                <th><?php echo t('customer_phone', $lang); ?></th>
                                <th><?php echo t('from', $lang); ?></th>
                                <th><?php echo t('to', $lang); ?></th>
                                <th><?php echo t('fee', $lang); ?></th>
                                <th><?php echo t('status', $lang); ?></th>
                                <th><?php echo t('delivery_code', $lang); ?></th>
                                <th><?php echo t('actions', $lang) ?? 'Actions'; ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($my_orders as $order): ?>
                            <tr>
                                <td><?php echo $order['id']; ?></td>
                                <td><?php echo e($order['customer_name']); ?></td>
                                <td>
                                    <?php echo e($order['customer_phone']); ?>
                                    <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $order['customer_phone']); ?>" target="_blank" class="btn btn-sm btn-success">
                                        <i class="bi bi-whatsapp"></i>
                                    </a>
                                </td>
                                <td><?php echo $lang === 'ar' ? $order['pickup_ar'] : $order['pickup_en']; ?></td>
                                <td><?php echo $lang === 'ar' ? $order['delivery_ar'] : $order['delivery_en']; ?></td>
                                <td><?php echo $order['delivery_fee']; ?> <?php echo t('mru', $lang); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $order['status'] === 'accepted' ? 'info' : 'primary'; ?>">
                                        <?php echo t($order['status'], $lang); ?>
                                    </span>
                                </td>
                                <td><strong><?php echo e($order['delivery_code']); ?></strong></td>
                                <td>
                                    <?php if ($order['status'] === 'accepted'): ?>
                                    <form method="POST" action="actions.php" style="display:inline;">
                                        <input type="hidden" name="action" value="pickup_order">
                                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            <?php echo t('pickup_order', $lang); ?>
                                        </button>
                                    </form>
                                    <?php elseif ($order['status'] === 'picked_up'): ?>
                                    <form method="POST" action="actions.php" style="display:inline;">
                                        <input type="hidden" name="action" value="deliver_order">
                                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <?php echo t('deliver_order', $lang); ?>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                    <p class="text-muted"><?php echo t('no_orders', $lang); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
