<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/receipt.css') ?>">
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <h1 class="mt-4"><i class="bi bi-receipt me-1"></i><?= $pageTitle ?></h1>
        <div>
            <button onclick="window.print()" class="btn btn-outline-primary me-2"><i class="bi bi-printer me-1"></i>Print</button>
            <button id="export-pdf" class="btn btn-outline-success"><i class="bi bi-file-earmark-pdf me-1"></i>Export PDF</button>
        </div>
    </div>
    <div class="receipt-card receipt-card-custom mx-auto" id="receipt-pdf-area">
        <div class="receipt-header receipt-header-custom d-flex flex-column flex-md-row justify-content-between align-items-md-center align-items-start pb-3 mb-4 flex-wrap">
            <div class="d-flex align-items-center flex-shrink-0 receipt-header-logo">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:56px;height:56px;font-size:2rem;"><i class="bi bi-receipt"></i></div>
                <div class="ms-3 receipt-header-title">
                    <div class="receipt-title receipt-title-custom mb-1 d-flex align-items-center flex-wrap">
                        Receipt
                        <?php if ($booking['payment_status'] === 'paid'): ?>
                            <span class="badge bg-success ms-2 receipt-badge-paid"><i class="bi bi-check-circle me-1"></i>PAID / LUNAS</span>
                        <?php endif; ?>
                    </div>
                    <div class="receipt-meta receipt-meta-nowrap"><i class="bi bi-hash me-1"></i><?= esc($booking['booking_code']) ?> | <i class="bi bi-calendar-event me-1"></i><?= date('M d, Y', strtotime($booking['payment_date'])) ?></div>
                    <div class="text-muted receipt-guid"><i class="bi bi-shield-check me-1"></i>No. Validasi: <span class="fw-semibold"><?= esc($booking['guid'] ?? '-') ?></span></div>
                </div>
            </div>
            <div class="text-md-end text-start flex-shrink-0 receipt-tenant">
                <div class="fw-bold mb-1"><i class="bi bi-building me-1"></i><?= esc($booking['tenant_name'] ?? '-') ?: '-' ?></div>
            </div>
        </div>
        <div class="mb-4">
            <div class="receipt-section-title"><i class="bi bi-person me-1"></i>Customer Details</div>
            <table class="table table-borderless receipt-table mb-0">
                <tr><th class="receipt-label">Name</th><td class="receipt-td"><span class="receipt-colon">:</span> <?= esc($booking['customer_name'] ?? '-') ?: '-' ?></td></tr>
                <?php if (!empty($booking['customer_email'])) : ?>
                <tr><th class="receipt-label">Email</th><td class="receipt-td"><span class="receipt-colon">:</span> <?= esc($booking['customer_email']) ?: '-' ?></td></tr>
                <?php endif; ?>
            </table>
        </div>
        <div class="mb-4">
            <div class="receipt-section-title"><i class="bi bi-calendar-check me-1"></i>Booking Details</div>
            <table class="table table-borderless receipt-table mb-0">
                <tr><th class="receipt-label">Service</th><td class="receipt-td"><span class="receipt-colon">:</span> <?= esc($booking['service_name'] ?? '-') ?: '-' ?></td></tr>
                <tr><th class="receipt-label">Date</th><td class="receipt-td"><span class="receipt-colon">:</span> <?= !empty($booking['booking_date']) ? date('M d, Y', strtotime($booking['booking_date'])) : '-' ?></td></tr>
                <tr><th class="receipt-label">Time</th><td class="receipt-td"><span class="receipt-colon">:</span> <?= (!empty($booking['start_time']) ? esc($booking['start_time']) : '-') ?> - <?= (!empty($booking['end_time']) ? esc($booking['end_time']) : '-') ?></td></tr>
                <tr><th class="receipt-label">Status</th><td class="receipt-td"><span class="receipt-colon">:</span> <?= !empty($booking['status']) ? ucfirst($booking['status']) : '-' ?></td></tr>
            </table>
        </div>
        <div class="mb-4">
            <div class="receipt-section-title"><i class="bi bi-credit-card me-1"></i>Payment Details</div>
            <table class="table table-borderless receipt-table mb-0">
                <tr><th class="receipt-label">Amount</th><td class="receipt-td"><span class="receipt-colon">:</span> <strong>Rp <?= isset($booking['price']) ? number_format($booking['price'], 2) : '-' ?></strong></td></tr>
                <tr><th class="receipt-label">Reference</th><td class="receipt-td"><span class="receipt-colon">:</span> <?= esc($booking['payment_reference'] ?? '-') ?: '-' ?></td></tr>
                <tr><th class="receipt-label">Payment Date</th><td class="receipt-td"><span class="receipt-colon">:</span> <?= !empty($booking['payment_date']) ? date('M d, Y H:i', strtotime($booking['payment_date'])) : '-' ?></td></tr>
            </table>
        </div>
        <div class="text-center mt-5 receipt-footer-note">
            <i class="bi bi-emoji-smile me-1"></i>Thank you for your booking.<br>
            <span style="font-size:0.9rem;">This is a system generated receipt.</span>
            <div class="mt-4">
                <div class="mb-1 receipt-footer-guid">No. Validasi: <span class="fw-semibold"><?= esc($booking['guid'] ?? '-') ?></span></div>
                <div id="receipt-qr" class="d-flex justify-content-center"></div>
            </div>
        </div>
    </div>
</div>
<script>
window.receiptBookingCode = "<?= esc($booking['booking_code']) ?>";
window.receiptQRValue = "<?= base_url('public-invoice/' . ($booking['guid'] ?? $booking['id'])) ?>";
</script>
<script src="<?= base_url('assets/js/html2pdf.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/js/qrious.min.js') ?>"></script>
<script src="<?= base_url('assets/js/receipt.js') ?>"></script>
<?= $this->endSection() ?>
