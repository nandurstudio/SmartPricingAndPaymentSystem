<!-- jQuery First -->
<script src="<?= base_url('assets/js/jquery/jquery.min.js'); ?>"></script>

<!-- Ensure jQuery is loaded before using it -->
<script>
if (typeof jQuery === 'undefined') {
    console.error('jQuery is not loaded!');
} else {
    window.$ = window.jQuery = jQuery;
}
</script>

<!-- Font Awesome -->
<script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>

<!-- Bootstrap Bundle -->
<script src="<?= base_url('assets/js/bootstrap/bootstrap.bundle.min.js'); ?>"></script>

<!-- DataTables Core - Must load before extensions -->
<script src="<?= base_url('assets/js/datatables/dataTables.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/datatables/dataTables.bootstrap5.min.js'); ?>"></script>

<!-- DataTables Extensions - Load after core -->
<script src="<?= base_url('assets/js/datatables/dataTables.responsive.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/datatables/responsive.bootstrap5.js'); ?>"></script>
<script src="<?= base_url('assets/js/datatables/dataTables.buttons.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/datatables/buttons.bootstrap5.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/datatables/buttons.html5.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/datatables/buttons.print.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/datatables/buttons.colVis.min.js'); ?>"></script>

<!-- DataTables Dependencies -->
<script src="<?= base_url('assets/js/jszip.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/pdfmake/pdfmake.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/pdfmake/vfs_fonts.js'); ?>"></script>

<!-- Other Utilities -->
<script src="<?= base_url('assets/js/feather-icons/feather.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/chartjs/Chart.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/litepicker/bundle.js'); ?>"></script>

<!-- Custom Scripts -->
<script src="<?= base_url('assets/js/scripts.js'); ?>"></script>

<!-- Initialize Feather Icons -->
<script>
    feather.replace();
</script>

<!-- Additional Scripts from Controller -->
<?php if (!empty($scripts)) : ?>
    <script src="<?= base_url($scripts); ?>"></script>
<?php endif; ?>

<!-- Render section for page specific scripts -->
<?= $this->renderSection('js') ?>