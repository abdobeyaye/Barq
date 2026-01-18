<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h4><?php echo t('settings', $lang); ?></h4>
            </div>
            <div class="card-body">
                <h5><?php echo t('select_districts', $lang); ?></h5>
                <p class="text-muted"><?php echo t('select_districts_desc', $lang) ?? 'Select the districts where you want to accept orders from'; ?></p>
                
                <?php
                // Get driver's current districts
                $stmt = $pdo->prepare("SELECT district_id FROM driver_districts WHERE driver_id = ?");
                $stmt->execute([$_SESSION['user_id']]);
                $selected_districts = $stmt->fetchAll(PDO::FETCH_COLUMN);
                ?>
                
                <form method="POST" action="actions.php">
                    <input type="hidden" name="action" value="update_driver_districts">
                    <?php foreach ($districts as $district): ?>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="districts[]" value="<?php echo $district['id']; ?>" 
                               id="district_<?php echo $district['id']; ?>"
                               <?php echo in_array($district['id'], $selected_districts) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="district_<?php echo $district['id']; ?>">
                            <?php echo getDistrictName($district, $lang); ?>
                        </label>
                    </div>
                    <?php endforeach; ?>
                    <button type="submit" class="btn btn-primary mt-3">
                        <?php echo t('save', $lang); ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
