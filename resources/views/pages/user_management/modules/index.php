<?php start_section('styles'); ?>
<link rel="stylesheet" href="/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="/assets/libs/sweetalert2/dist/sweetalert2.min.css">
<?php end_section(); ?>
<!-- --------------------------------------------------- -->
<!--  Role Page -->
<!-- --------------------------------------------------- -->
<div class="card bg-light-info shadow-none position-relative overflow-hidden">
    <div class="card-body px-4 py-3">
        <div class="row align-items-center">
            <div class="col-9">
                <h4 class="fw-semibold mb-8">Module</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a class="text-muted " href="<?= base_url('') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item" aria-current="page">Module</li>
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

<section class="datatables">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <a href="<?= base_url('modules/create'); ?>" class="btn btn-primary mb-4">
                        <i class="ti ti-plus fs-4"></i>&nbsp; Add new
                    </a>
                    <div class="table-responsive rounded-2 mb-4">
                        <table id="zero_config" class="table border text-nowrap customize-table mb-0 align-middle">
                            <thead class="text-dark fs-4">
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th class=""></th>
                                </tr>

                            </thead>
                            <tbody>
                                <?php foreach ($modules as $module) : ?>
                                    <tr>
                                        <td><?= $module['name'] ?></td>
                                        <td><?= $module['description'] ?></td>
                                        <td>
                                            <div class="dropdown dropstart">
                                                <a href="#" class="text-muted" id="dropdownMenuButton" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <i class="ti ti-dots fs-5"></i>
                                                </a>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center gap-3" href="<?= base_url('modules/edit/' . $module['id']) ?>"><i
                                                                class="fs-4 ti ti-edit"></i>Edit</a>
                                                    </li>
                                                    <li>

                                                        <a class="dropdown-item d-flex align-items-center gap-3" href="#" onclick="confirmDelete(<?= $module['id'] ?>, '<?= addslashes($module['name']) ?>')">
                                                            <i class="fs-4 ti ti-trash"></i>Delete
                                                        </a>

                                                        <form id="delete-form-module-<?= $module['id'] ?>" action="<?= base_url('modules/delete') ?>" method="POST" style="display: none;">
                                                            <?= csrf_field() ?>
                                                            <input type="hidden" name="id" value="<?= $module['id'] ?>">
                                                        </form>

                                                    </li>
                                                </ul>
                                            </div>
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
</section>

<?php start_section('scripts'); ?>
<script src="<?= base_url('assets/libs/datatables.net/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/libs/sweetalert2/dist/sweetalert2.min.js') ?>"></script>
<script>
    $(document).ready(function() {
        console.log('This JS is only for the module page');
        $("#zero_config").DataTable({
            columnDefs: [{
                    width: "80px",
                    targets: -1
                } // -1 targets the last column (Actions)
            ]
        });
    });

    function confirmDelete(moduleId, moduleName) {
        console.log('Delete module with ID:', roleId);
        Swal.fire({
            title: 'Are you sure?',
            text: `Module "${moduleName}" will be permanently deleted!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Perform the delete action here
                document.getElementById(`delete-form-module-${roleId}`).submit();
            }
        });
    }
</script>
<?php end_section(); ?>