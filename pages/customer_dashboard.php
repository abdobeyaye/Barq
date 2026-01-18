<div class="row">
    <div class="col-md-8 mx-auto">
        <h2><?php echo t('welcome', $lang); ?>, <?php echo e($user['full_name']); ?>!</h2>
        
        <!-- New Order Form -->
        <div class="card mt-4">
            <div class="card-header">
                <h4><?php echo t('new_order', $lang); ?></h4>
            </div>
            <div class="card-body">
                <form method="POST" action="actions.php" id="orderForm">
                    <input type="hidden" name="action" value="place_order">
                    <div class="mb-3">
                        <label class="form-label"><?php echo t('order_details', $lang); ?></label>
                        <textarea name="order_details" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo t('customer_phone', $lang); ?></label>
                        <input type="text" name="customer_phone" class="form-control" value="<?php echo e($user['phone']); ?>" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><?php echo t('pickup_district', $lang); ?></label>
                            <select name="pickup_district_id" id="pickup_district" class="form-select" required>
                                <option value=""><?php echo t('select_district', $lang); ?></option>
                                <?php foreach ($districts as $district): ?>
                                    <option value="<?php echo $district['id']; ?>">
                                        <?php echo getDistrictName($district, $lang); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><?php echo t('delivery_district', $lang); ?></label>
                            <select name="delivery_district_id" id="delivery_district" class="form-select" required>
                                <option value=""><?php echo t('select_district', $lang); ?></option>
                                <?php foreach ($districts as $district): ?>
                                    <option value="<?php echo $district['id']; ?>">
                                        <?php echo getDistrictName($district, $lang); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo t('delivery_fee', $lang); ?></label>
                        <input type="text" id="delivery_fee_display" class="form-control" readonly placeholder="0 <?php echo t('mru', $lang); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo t('detailed_address', $lang); ?></label>
                        <textarea name="detailed_address" class="form-control" rows="2" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send"></i> <?php echo t('submit', $lang); ?>
                    </button>
                </form>
            </div>
        </div>

        <!-- My Orders -->
        <div class="card mt-4">
            <div class="card-header">
                <h4><?php echo t('my_orders', $lang); ?></h4>
            </div>
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
                                <th><?php echo t('delivery_code', $lang); ?></th>
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
                                <td>
                                    <?php if ($order['delivery_code']): ?>
                                        <strong><?php echo e($order['delivery_code']); ?></strong>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (in_array($order['status'], ['pending', 'accepted'])): ?>
                                    <form method="POST" action="actions.php" style="display:inline;" onsubmit="return confirm('<?php echo t('are_you_sure', $lang); ?>');">
                                        <input type="hidden" name="action" value="cancel_order">
                                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <?php echo t('cancel_order', $lang); ?>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                    <?php if ($order['status'] === 'accepted' && $order['driver_name']): ?>
                                    <div class="mt-2 small">
                                        <strong><?php echo t('your_driver', $lang); ?>:</strong><br>
                                        <?php echo e($order['driver_name']); ?><br>
                                        <?php echo e($order['driver_phone']); ?><br>
                                        <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $order['driver_phone']); ?>" target="_blank" class="btn btn-sm btn-success">
                                            <i class="bi bi-whatsapp"></i> <?php echo t('whatsapp', $lang); ?>
                                        </a>
                                    </div>
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
