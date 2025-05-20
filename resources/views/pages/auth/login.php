<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'My App'; ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css'); ?>">
    <!--  Favicon -->
    <link rel="shortcut icon" type="image/png" href="<?= base_url('assets/images/logos/favicon.ico'); ?>" />
    <!-- Core Css -->
    <link id="themeColors" rel="stylesheet" href="<?= base_url('assets/css/style.min.css'); ?>" />

</head>

<body>

    <!-- Preloader -->
    <div class="preloader">
        <img src="<?= base_url('assets/images/logos/favicon.ico'); ?>" alt="loader" class="lds-ripple img-fluid" />
    </div>
    <!-- Preloader -->
    <div class="preloader">
        <img src="<?= base_url('assets/images/logos/favicon.ico'); ?>" alt="loader" class="lds-ripple img-fluid" />
    </div>

    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
        <div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
            <div class="d-flex align-items-center justify-content-center w-100">
                <div class="row justify-content-center w-100">
                    <div class="col-md-8 col-lg-6 col-xxl-3">
                        <?php if ($error = flash('error')): ?>
                            <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show" role="alert">
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                                <strong>Failed Sign In - </strong> <?= htmlspecialchars($error) ?>
                            </div>
                        <?php endif; ?>

                        <div class="card mb-0">
                            <div class="card-body">
                                <a href="#" class="text-nowrap logo-img text-center d-block mb-5 w-100">
                                    <img src="<?= base_url('assets/images/logos/dark-logo.svg') ?>" width="180" alt="">
                                </a>
                                <form action="<?= base_url('auth/login') ?>" method="POST">
                                    <?= csrf_field() ?>
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" name="username" class="form-control" id="username" aria-describedby="emailHelp">
                                    </div>
                                    <div class="mb-4">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" name="password" class="form-control" id="password">
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100 py-8 mb-4 rounded-2">Sign In</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--  Import Js Files -->
    <script src="<?= base_url('assets/libs/jquery/dist/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/libs/simplebar/dist/simplebar.min.js') ?>"></script>
    <script src="<?= base_url('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>
    <!--  core files -->
    <script src="<?= base_url('assets/js/app.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/app.init.js') ?>"></script>
    <script src="<?= base_url('assets/js/app-style-switcher.js') ?>"></script>
    <script src="<?= base_url('assets/js/sidebarmenu.js') ?>"></script>

    <script src="<?= base_url('assets/js/custom.js') ?>"></script>
</body>

</html>