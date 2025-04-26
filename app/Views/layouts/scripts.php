<!-- Tambahkan script utama lainnya -->
<script src="<?= base_url('assets/js/jquery/jquery.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/chartjs/Chart.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/bootstrap/bootstrap.bundle.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/feather-icons/feather.min.js'); ?>"></script>

<!-- DataTables and DataTables Bootstrap 5 integration -->
<script src="<?= base_url('assets/js/datatables/dataTables.js'); ?>"></script>
<script src="<?= base_url('assets/js/datatables/dataTables.bootstrap5.js'); ?>"></script>
<script src="<?= base_url('assets/js/datatables/dataTables.responsive.js'); ?>"></script>
<script src="<?= base_url('assets/js/datatables/responsive.bootstrap5.js'); ?>"></script>

<!-- DataTables Buttons and its plugins -->
<script src="<?= base_url('assets/js/datatables/dataTables.buttons.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/datatables/buttons.bootstrap5.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/jszip.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/datatables/buttons.html5.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/datatables/buttons.print.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/datatables/buttons.colVis.min.js'); ?>"></script>

<!-- PDFMake for PDF export -->
<script src="<?= base_url('assets/js/pdfmake/pdfmake.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/pdfmake/vfs_fonts.js'); ?>"></script>

<!-- Other custom scripts -->
<script src="<?= base_url('assets/js/litepicker/bundle.js'); ?>"></script>
<script src="<?= base_url('assets/js/scripts.js'); ?>"></script>

<!-- Tambahan JS lainnya -->
<script>
    feather.replace(); // Inisialisasi feather icons
</script>

<!-- Menambahkan scripts yang di-push dari controller -->
<?php if (!empty($scripts)) : ?>
    <!-- Pastikan script competencies.js ada di sini -->
    <script src="<?= base_url($scripts); ?>"></script>
<?php else: ?>
    <script>
        console.warn('No additional scripts were provided.');
    </script>
<?php endif; ?>