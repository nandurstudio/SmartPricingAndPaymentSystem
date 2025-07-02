<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="card mt-5">
        <div class="card-body p-4">
            <h1 class="h3 mb-4">Terms of Service</h1>
            
            <div class="terms-content">
                <h4>1. Acceptance of Terms</h4>
                <p>By accessing and using Smart Pricing and Payment System, you accept and agree to be bound by the terms and provision of this agreement.</p>

                <h4>2. Description of Service</h4>
                <p>Smart Pricing and Payment System provides a platform for managing pricing strategies and payment processing for businesses.</p>

                <h4>3. Registration & Account Security</h4>
                <p>Users are responsible for maintaining the confidentiality of their account information and password.</p>

                <h4>4. User Conduct</h4>
                <p>Users agree to use the service in compliance with all applicable laws and regulations.</p>

                <h4>5. Subscription & Payments</h4>
                <p>Users agree to pay all fees or charges to their account based on the pricing and payment terms presented.</p>

                <h4>6. Data Privacy</h4>
                <p>We collect and process personal data in accordance with our Privacy Policy.</p>

                <h4>7. Intellectual Property</h4>
                <p>The service and its original content are protected by copyright, trademark, and other laws.</p>

                <h4>8. Termination</h4>
                <p>We may terminate or suspend access to our service immediately, without prior notice, for conduct that we believe violates these Terms.</p>

                <h4>9. Limitation of Liability</h4>
                <p>The company shall not be liable for any indirect, incidental, special, consequential or punitive damages.</p>

                <h4>10. Changes to Terms</h4>
                <p>We reserve the right to modify or replace these terms at any time. Users will be notified of any changes.</p>
            </div>

            <div class="mt-4">
                <a href="<?= base_url('/register') ?>" class="btn btn-primary">Back to Registration</a>
                <a href="<?= base_url('/privacy-policy') ?>" class="btn btn-outline-primary ms-2">View Privacy Policy</a>
            </div>
        </div>
    </div>
</div>

<style>
.terms-content {
    line-height: 1.6;
}
.terms-content h4 {
    color: #2c3e50;
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
}
.terms-content p {
    color: #34495e;
    margin-bottom: 1rem;
}
</style>
<?= $this->endSection() ?>
