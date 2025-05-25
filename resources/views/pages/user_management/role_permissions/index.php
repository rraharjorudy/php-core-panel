<?php start_section('styles'); ?>
<link rel="stylesheet" href="/assets/libs/sweetalert2/dist/sweetalert2.min.css">
<?php end_section(); ?>
<!-- --------------------------------------------------- -->
<!--  Role Page -->
<!-- --------------------------------------------------- -->
<div class="card bg-light-info shadow-none position-relative overflow-hidden">
    <div class="card-body px-4 py-3">
        <div class="row align-items-center">
            <div class="col-9">
                <h4 class="fw-semibold mb-8">Manage Role Module Permissions</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a class="text-muted " href="<?= base_url('') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item" aria-current="page">Role Permission</li>
                    </ol>
                </nav>
            </div>
            <div class="col-3">
                <div class="text-center mb-n5">
                    <img src="<?= base_url('assets/images/breadcrumb/ChatBc.png') ?>" alt="" class="img-fluid mb-n4">
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($success = flash('success')): ?>
    <div class="alert alert-light-success alert-dismissible bg-success text-white border-0 fade show" role="alert">
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        <strong>Success - </strong> <?= htmlspecialchars($success) ?>
    </div>
<?php elseif ($error = flash('error')): ?>
    <div class="alert alert-light-danger alert-dismissible bg-danger text-white border-0 fade show" role="alert">
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        <strong>Failed - </strong> <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="<?= base_url('role-permissions') ?>">
                    <div class="mb-3">
                        <label for="role_id">Select Role</label>
                        <select name="role_id" id="role_id" class="form-select" onchange="this.form.submit()">
                            <option value="">-- Choose Role --</option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= $role['id'] ?>" <?= $role['id'] == $role_id ? 'selected' : '' ?>>
                                    <?= ucfirst($role['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>

                <?php if ($role_id): ?>
                    <form method="POST" action="<?= base_url('role-permissions/update') ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="role_id" value="<?= $role_id ?>">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Module</th>
                                    <?php foreach ($permissions as $perm): ?>
                                        <th><?= ucfirst($perm['name']) ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($groupedModules as $group): ?>
                                    <!-- Parent Module Row -->
                                    <tr class="table-primary">
                                        <td><strong><?= ucfirst(str_replace('_', ' ', $group['parent']['name'])) ?></strong></td>
                                        <?php foreach ($permissions as $perm): ?>
                                            <td>
                                                <?php
                                                $moduleId = $group['parent']['id'];
                                                $isAvailable = isset($modulePermissionsMap[$moduleId]) && in_array($perm['id'], $modulePermissionsMap[$moduleId]);
                                                $isChecked = isset($rolePermissionsMap[$moduleId]) && in_array($perm['id'], $rolePermissionsMap[$moduleId]);
                                                ?>
                                                <?php if ($isAvailable): ?>
                                                    <input type="checkbox" class="form-check-input primary" name="permissions[<?= $moduleId ?>][]" value="<?= $perm['id'] ?>" <?= $isChecked ? 'checked' : '' ?>>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>

                                    <!-- Child Modules -->
                                    <?php foreach ($group['children'] as $child): ?>
                                        <tr>
                                            <td class="ps-4">↳ <?= ucfirst(str_replace('_', ' ', $child['name'])) ?></td>
                                            <?php foreach ($permissions as $perm): ?>
                                                <td>
                                                    <?php
                                                    $moduleId = $child['id'];
                                                    $isAvailable = isset($modulePermissionsMap[$moduleId]) && in_array($perm['id'], $modulePermissionsMap[$moduleId]);
                                                    $isChecked = isset($rolePermissionsMap[$moduleId]) && in_array($perm['id'], $rolePermissionsMap[$moduleId]);
                                                    ?>
                                                    <?php if ($isAvailable): ?>
                                                        <input type="checkbox" class="form-check-input primary" name="permissions[<?= $moduleId ?>][]" value="<?= $perm['id'] ?>" <?= $isChecked ? 'checked' : '' ?>>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </tbody>

                        </table>

                        <button class="btn btn-primary">Save Permissions</button>
                    </form>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>


<?php start_section('scripts'); ?>
<script src="<?= base_url('assets/libs/sweetalert2/dist/sweetalert2.min.js') ?>"></script>
<script>
    $(document).ready(function() {
        console.log('This JS is only for the role page');

    });
</script>
<?php end_section(); ?>