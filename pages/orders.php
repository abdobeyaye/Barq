<div class="row">
    <div class="col-md-12">
        <h2><?php echo t('my_orders', $lang); ?></h2>
        
        <div class="card mt-4">
            <div class="card-body">
                <?php
                $stmt = $pdo->prepare("
                    SELECT o.*, 
                           d1.name_en as pickup_en, d1.name_ar as pickup_ar,
                           d2.name_en as delivery_en, d2.name_ar as delivery_ar,
                           dr.full_name as driver_name, dr.phone as driver_phone, dr.rating as driver_rating
                    FROM orders1 o
                    LEFT JOIN districts d1 ON o.pickup_district_id = d1.id
                    LEFT JOIN districts d2 ON o.delivery_district_id = d2.id
                    LEFT JOIN users1 dr ON o.driver_id = dr.id
                    WHERE o.customer_id = ?
                    ORDER BY o.created_at DESC
                ");
                $stmt->execute([$_SESSION['user_id']]);
                $orders = $stmt->fetchAll();
                
                if (count($orders) > 0):
                ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?php echo t('order_details', $lang); ?></th>
                                <th><?php echo t('from', $lang); ?></th>
                                <th><?php echo t('to', $lang); ?></th>
                                <th><?php echo t('fee', $lang); ?></th>
                                <th><?php echo t('status', $lang); ?></th>
                                <th><?php echo t('created_at', $lang); ?></th>
                                <th><?php echo t('actions', $lang) ?? 'Actions'; ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?php echo $order['id']; ?></td>
                                <td><?php echo e($order['order_details']); ?></td>
                                <td><?php echo $lang === 'ar' ? $order['pickup_ar'] : $order['pickup_en']; ?></td>
                                <td><?php echo $lang === 'ar' ? $order['delivery_ar'] : $order['delivery_en']; ?></td>
                                <td><?php echo $order['delivery_fee']; ?> <?php echo t('mru', $lang); ?></td>
                                <td>
                                    <span class="badge bg-<?php 
                                        echo $order['status'] === 'pending' ? 'warning' : 
                                            ($order['status'] === 'accepted' ? 'info' : 
                                            ($order['status'] === 'picked_up' ? 'primary' : 
                                            ($order['status'] === 'delivered' ? 'success' : 'danger'))); 
                                    ?>">
                                        <?php echo t($order['status'], $lang); ?>
                                    </span>
                                </td>
                                <td><?php echo fmtDate($order['created_at']); ?></td>
                                <td>
                                    <?php if (in_array($order['status'], ['pending', 'accepted'])): ?>
                                    <form method="POST" action="actions.php" style="display:inline;">
                                        <input type="hidden" name="action" value="cancel_order">
                                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <?php echo t('cancel_order', $lang); ?>
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
