<div class="row">
    <div class="col-md-12">
        <h2><?php echo t('manage_districts', $lang); ?></h2>
        
        <div class="card mt-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?php echo t('name_en', $lang) ?? 'Name (English)'; ?></th>
                                <th><?php echo t('name_ar', $lang) ?? 'Name (Arabic)'; ?></th>
                                <th><?php echo t('status', $lang); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($districts as $district): ?>
                            <tr>
                                <td><?php echo $district['id']; ?></td>
                                <td><?php echo e($district['name_en']); ?></td>
                                <td><?php echo e($district['name_ar']); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $district['active'] ? 'success' : 'secondary'; ?>">
                                        <?php echo $district['active'] ? 'Active' : 'Inactive'; ?>
                                    </span>
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
