<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-xl px-4 mt-4">
    <div class="card">
        <div class="card-header">            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><?= $pageTitle ?? 'Create Role' ?></h5>
                <div>
                    <button type="submit" form="roleForm" class="btn btn-primary btn-sm">
                        <i data-feather="plus"></i> Create Role
                    </button>
                    <a href="<?= base_url('roles') ?>" class="btn btn-secondary btn-sm ms-2">
                        <i data-feather="arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <?= $this->include('layouts/messages') ?>            <form action="<?= base_url('roles/store') ?>" method="post" id="roleForm">
                <?= $this->include('role/_form') ?>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>