<div>
    <h1>Welcome to the Dashboard</h1>
    <p>Hello, <?= $username ?? 'Guest'; ?>!</p>
</div>

<?php start_section('scripts'); ?>
<!-- current page js files -->
<!-- <script src="</?= base_url('assets/libs/apexcharts/dist/apexcharts.min.js') ?>"></script> -->
<script src="<?= base_url('assets/js/dashboard5.js') ?>"></script>
<script>
    $(document).ready(function() {

    });
</script>
<?php end_section(); ?>