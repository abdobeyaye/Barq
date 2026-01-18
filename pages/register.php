<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4><?php echo t('register', $lang); ?></h4>
            </div>
            <div class="card-body">
                <form method="POST" action="actions.php">
                    <input type="hidden" name="action" value="register">
                    <div class="mb-3">
                        <label class="form-label"><?php echo t('full_name', $lang); ?></label>
                        <input type="text" name="full_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo t('phone', $lang); ?></label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo t('password', $lang); ?></label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo t('role', $lang); ?></label>
                        <select name="role" class="form-select">
                            <option value="customer"><?php echo t('customer', $lang); ?></option>
                            <option value="driver"><?php echo t('driver', $lang); ?></option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <?php echo t('register', $lang); ?>
                    </button>
                </form>
                <div class="mt-3 text-center">
                    <a href="index.php?page=login"><?php echo t('login', $lang); ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
