<div class="row">
    <div class="col-md-12">
        <h2><?php echo t('manage_users', $lang); ?></h2>
        
        <div class="card mt-4">
            <div class="card-body">
                <?php
                $stmt = $pdo->query("SELECT * FROM users1 ORDER BY created_at DESC");
                $users = $stmt->fetchAll();
                ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?php echo t('full_name', $lang); ?></th>
                                <th><?php echo t('phone', $lang); ?></th>
                                <th><?php echo t('role', $lang); ?></th>
                                <th><?php echo t('points', $lang); ?></th>
                                <th><?php echo t('rating', $lang) ?? 'Rating'; ?></th>
                                <th><?php echo t('created_at', $lang); ?></th>
                                <th><?php echo t('actions', $lang) ?? 'Actions'; ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $u): ?>
                            <tr>
                                <td><?php echo $u['id']; ?></td>
                                <td><?php echo e($u['full_name']); ?></td>
                                <td><?php echo e($u['phone']); ?></td>
                                <td><?php echo t($u['role'], $lang); ?></td>
                                <td><?php echo $u['points']; ?></td>
                                <td><?php echo $u['rating']; ?> (<?php echo $u['total_ratings']; ?>)</td>
                                <td><?php echo fmtDate($u['created_at']); ?></td>
                                <td>
                                    <?php if ($u['role'] === 'driver'): ?>
                                    <button class="btn btn-sm btn-primary" onclick="showAddPointsModal(<?php echo $u['id']; ?>, '<?php echo e($u['full_name']); ?>')">
                                        <?php echo t('add_points', $lang); ?>
                                    </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Points Modal -->
<div class="modal fade" id="addPointsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="actions.php">
                <input type="hidden" name="action" value="admin_add_points">
                <input type="hidden" name="user_id" id="modal_user_id">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo t('add_points', $lang); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><?php echo t('user', $lang) ?? 'User'; ?>: <strong id="modal_user_name"></strong></p>
                    <div class="mb-3">
                        <label class="form-label"><?php echo t('points', $lang); ?></label>
                        <input type="number" name="points" class="form-control" min="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo t('cancel', $lang); ?></button>
                    <button type="submit" class="btn btn-primary"><?php echo t('save', $lang); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
