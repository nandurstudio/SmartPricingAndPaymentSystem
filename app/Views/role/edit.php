<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-xl px-4 mt-4">
    <div class="card">        <div class="card-header">            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><?= $pageTitle ?? 'Edit Role' ?></h5>
            </div>
        </div>
        <div class="card-body">
            <?= $this->include('layouts/messages') ?>            <form action="<?= base_url('roles/update/' . $role['intRoleID']) ?>" method="post" id="roleForm">
                <?= $this->include('role/_form') ?>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>