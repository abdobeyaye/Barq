<div class="row">
    <div class="col-md-12">
        <h2><?php echo t('manage_orders', $lang); ?></h2>
        
        <div class="card mt-4">
            <div class="card-body">
                <?php
                $stmt = $pdo->query("
                    SELECT o.*, 
                           d1.name_en as pickup_en, d1.name_ar as pickup_ar,
                           d2.name_en as delivery_en, d2.name_ar as delivery_ar,
                           c.full_name as customer_name,
                           dr.full_name as driver_name
                    FROM orders1 o
                    LEFT JOIN districts d1 ON o.pickup_district_id = d1.id
                    LEFT JOIN districts d2 ON o.delivery_district_id = d2.id
                    LEFT JOIN users1 c ON o.customer_id = c.id
                    LEFT JOIN users1 dr ON o.driver_id = dr.id
                    ORDER BY o.created_at DESC
                ");
                $orders = $stmt->fetchAll();
                ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?php echo t('customer_name', $lang); ?></th>
                                <th><?php echo t('driver_name', $lang); ?></th>
                                <th><?php echo t('from', $lang); ?></th>
                                <th><?php echo t('to', $lang); ?></th>
                                <th><?php echo t('fee', $lang); ?></th>
                                <th><?php echo t('status', $lang); ?></th>
                                <th><?php echo t('created_at', $lang); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?php echo $order['id']; ?></td>
                                <td><?php echo e($order['customer_name']); ?></td>
                                <td><?php echo $order['driver_name'] ? e($order['driver_name']) : '-'; ?></td>
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
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
