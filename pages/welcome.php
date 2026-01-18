<div class="row justify-content-center">
    <div class="col-md-8 text-center">
        <h1 class="display-4 mb-4">
            <i class="bi bi-lightning-charge-fill text-primary"></i>
            <?php echo t('app_name', $lang); ?>
        </h1>
        <p class="lead"><?php echo t('tagline', $lang); ?></p>
        <p class="mt-4">
            <?php echo t('welcome', $lang); ?>
        </p>
        <div class="mt-4">
            <a href="index.php?page=login" class="btn btn-primary btn-lg me-2">
                <?php echo t('login', $lang); ?>
            </a>
            <a href="index.php?page=register" class="btn btn-outline-primary btn-lg">
                <?php echo t('register', $lang); ?>
            </a>
        </div>
    </div>
</div>
