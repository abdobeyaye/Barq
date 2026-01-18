<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h4><?php echo t('profile', $lang); ?></h4>
            </div>
            <div class="card-body">
                <form method="POST" action="actions.php">
                    <input type="hidden" name="action" value="update_profile">
                    <div class="mb-3">
                        <label class="form-label"><?php echo t('full_name', $lang); ?></label>
                        <input type="text" name="full_name" class="form-control" value="<?php echo e($user['full_name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo t('phone', $lang); ?></label>
                        <input type="text" class="form-control" value="<?php echo e($user['phone']); ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo t('role', $lang); ?></label>
                        <input type="text" class="form-control" value="<?php echo t($user['role'], $lang); ?>" disabled>
                    </div>
                    <?php if (hasRole('driver')): ?>
                    <div class="mb-3">
                        <label class="form-label"><?php echo t('my_points', $lang); ?></label>
                        <input type="text" class="form-control" value="<?php echo e($user['points']); ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo t('driver_rating', $lang); ?></label>
                        <input type="text" class="form-control" value="<?php echo e($user['rating']); ?> (<?php echo e($user['total_ratings']); ?> ratings)" disabled>
                    </div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <label class="form-label"><?php echo t('password', $lang); ?> (<?php echo t('leave_empty', $lang) ?? 'Leave empty to keep current'; ?>)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <?php echo t('update_profile', $lang); ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
