<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header">
                    <h3 class="text-center font-weight-light my-4">Complete Your Subscription</h3>
                </div>
                <div class="card-body">
                    <?php if (session()->has('error')) : ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= session('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif ?>

                    <div class="text-center mb-4">
                        <h4><?= ucfirst($plan) ?> Plan</h4>
                        <h2 class="text-primary">Rp <?= number_format($amount, 0, ',', '.') ?></h2>
                    </div>

                    <div class="text-center">
                        <button id="pay-button" class="btn btn-primary btn-lg">Pay Now</button>
                    </div>
                </div>
                <div class="card-footer text-center py-3">
                    <div class="small">
                        Need help? <a href="#" onclick="alert('Contact support@yourdomain.com')">Contact Support</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Midtrans JS -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?= getenv('MIDTRANS_CLIENT_KEY') ?>"></script>

<script>
    document.getElementById('pay-button').onclick = function() {
        snap.pay('<?= $snapToken ?>', {
            onSuccess: function(result) {
                window.location.href = '<?= base_url('onboarding/payment-success/' . $tenantId) ?>?transaction_id=' + result.transaction_id;
            },
            onPending: function(result) {
                window.location.href = '<?= base_url('onboarding/payment-pending/' . $tenantId) ?>?transaction_id=' + result.transaction_id;
            },
            onError: function(result) {
                window.location.href = '<?= base_url('onboarding/payment-failed/' . $tenantId) ?>?message=' + result.status_message;
            },
            onClose: function() {
                alert('You closed the payment window. Please complete the payment to activate your subscription.');
            }
        });
    };
</script>
<?= $this->endSection() ?>
