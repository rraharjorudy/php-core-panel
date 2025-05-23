<div class="card bg-light-info shadow-none position-relative overflow-hidden">
    <div class="card-body px-4 py-3">
        <div class="row align-items-center">
            <div class="col-9">
                <h4 class="fw-semibold mb-8">Role</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a class="text-muted " href="<?= base_url('') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a class="text-muted " href="<?= base_url('roles') ?>">Role</a></li>
                        <li class="breadcrumb-item" aria-current="page">Create</li>
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

<div class="row">
    <div class="col-12">
        <div class="card">
            <form class="needs-validation" novalidate action="<?= base_url('roles/store') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="card-body">
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
                    <div class="row m-auto">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="name" class="control-label col-form-label">Role Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Role Name" required />
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="description" class="control-label col-form-label">Description</label>
                                <textarea class="form-control p-7" id="description" name="description" cols="20" rows="1" placeholder="Description"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-3 border-top">
                    <div class="action-form">
                        <div class="text-end">
                            <button type="submit" class="btn btn-info rounded-pill px-4 waves-effect waves-light">
                                Save
                            </button>
                            <a href="<?= base_url('roles') ?>" class="btn btn-dark rounded-pill px-4 waves-effect waves-light">
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>

<?php start_section('scripts'); ?>
<script>
    $(document).ready(function() {
        console.log('This JS is only for the role create page');
        (function() {
            "use strict";
            window.addEventListener(
                "load",
                function() {
                    // Fetch all the forms we want to apply custom Bootstrap validation styles to
                    var forms = document.getElementsByClassName("needs-validation");
                    // Loop over them and prevent submission
                    var validation = Array.prototype.filter.call(
                        forms,
                        function(form) {
                            form.addEventListener(
                                "submit",
                                function(event) {
                                    if (form.checkValidity() === false) {
                                        event.preventDefault();
                                        event.stopPropagation();
                                    }
                                    form.classList.add("was-validated");
                                },
                                false
                            );
                        }
                    );
                },
                false
            );
        })();
    });
</script>
<?php end_section(); ?>