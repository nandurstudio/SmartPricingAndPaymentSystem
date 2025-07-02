<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="card mt-5">
        <div class="card-body p-4">
            <h1 class="h3 mb-4">Privacy Policy</h1>
            
            <div class="privacy-content">
                <h4>1. Information We Collect</h4>
                <p>We collect information that you provide directly to us, including:</p>
                <ul>
                    <li>Name and contact information</li>
                    <li>Business information</li>
                    <li>Payment information</li>
                    <li>Usage data and analytics</li>
                </ul>

                <h4>2. How We Use Your Information</h4>
                <p>We use the information we collect to:</p>
                <ul>
                    <li>Provide and maintain our service</li>
                    <li>Process your payments</li>
                    <li>Send you important updates</li>
                    <li>Improve our services</li>
                    <li>Comply with legal obligations</li>
                </ul>

                <h4>3. Information Sharing</h4>
                <p>We do not sell your personal information. We may share your information with:</p>
                <ul>
                    <li>Service providers and business partners</li>
                    <li>Legal authorities when required by law</li>
                    <li>Other parties with your consent</li>
                </ul>

                <h4>4. Data Security</h4>
                <p>We implement appropriate security measures to protect your personal information. However, no method of transmission over the Internet is 100% secure.</p>

                <h4>5. Your Rights</h4>
                <p>You have the right to:</p>
                <ul>
                    <li>Access your personal information</li>
                    <li>Correct inaccurate data</li>
                    <li>Request deletion of your data</li>
                    <li>Opt-out of marketing communications</li>
                </ul>

                <h4>6. Cookies</h4>
                <p>We use cookies and similar tracking technologies to track activity on our service and hold certain information.</p>

                <h4>7. Changes to This Policy</h4>
                <p>We may update our Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page.</p>

                <h4>8. Contact Us</h4>
                <p>If you have any questions about this Privacy Policy, please contact us at:</p>
                <p>Email: privacy@smartpaymentplus.com</p>
            </div>

            <div class="mt-4">
                <a href="<?= base_url('/register') ?>" class="btn btn-primary">Back to Registration</a>
                <a href="<?= base_url('/terms') ?>" class="btn btn-outline-primary ms-2">View Terms of Service</a>
            </div>
        </div>
    </div>
</div>

<style>
.privacy-content {
    line-height: 1.6;
}
.privacy-content h4 {
    color: #2c3e50;
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
}
.privacy-content p {
    color: #34495e;
    margin-bottom: 1rem;
}
.privacy-content ul {
    color: #34495e;
    margin-bottom: 1rem;
    padding-left: 1.5rem;
}
.privacy-content ul li {
    margin-bottom: 0.5rem;
}
</style>
<?= $this->endSection() ?>
