<?= $this->extend('layouts/tenant_website') ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div class="text-center">
        <img src="<?= base_url('assets/images/404.svg') ?>" 
             alt="404 Not Found" 
             class="mb-4"
             style="max-width: 300px;">
        <h1>404 - Page Not Found</h1>
        <p class="lead">The page you are looking for does not exist.</p>        <a href="<?= tenant_url('') ?>" class="btn btn-primary">
            <i class="fas fa-home me-1"></i> Return to Homepage
        </a>
    </div>
</div>
<?= $this->endSection() ?>
