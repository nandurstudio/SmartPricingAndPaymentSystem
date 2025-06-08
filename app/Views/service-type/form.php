<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-xl px-4 mt-4">
    <div class="row">
        <div class="col-xl-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><?= $pageTitle ?></h5>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    <?php endif ?>

                    <form action="<?= $serviceType['intServiceTypeID'] ?? '' ? 
                        base_url('service-types/update/' . $serviceType['intServiceTypeID']) : 
                        base_url('service-types/store') ?>" 
                        method="POST">
                        <?= csrf_field() ?>

                        <!-- Service Type Name -->
                        <div class="mb-3">
                            <label class="small mb-1" for="txtName">Service Type Name</label>
                            <input class="form-control" id="txtName" name="txtName" type="text"
                                value="<?= old('txtName', $serviceType['txtName']) ?>" required>
                        </div>

                        <!-- Category -->
                        <div class="mb-3">
                            <label class="small mb-1" for="txtCategory">Category</label>
                            <input class="form-control" id="txtCategory" name="txtCategory" type="text"
                                value="<?= old('txtCategory', $serviceType['txtCategory']) ?>"
                                placeholder="e.g., Sports, Entertainment, Education">
                        </div>

                        <!-- Icon -->
                        <div class="mb-3">
                            <label class="small mb-1" for="txtIcon">Icon</label>
                            <input class="form-control" id="txtIcon" name="txtIcon" type="text"
                                value="<?= old('txtIcon', $serviceType['txtIcon']) ?>"
                                placeholder="Font Awesome class or icon URL">
                            <div class="form-text">Enter Font Awesome class (e.g., fa-football) or icon URL</div>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label class="small mb-1" for="txtDescription">Description</label>
                            <textarea class="form-control" id="txtDescription" name="txtDescription" 
                                rows="3"><?= old('txtDescription', $serviceType['txtDescription']) ?></textarea>
                        </div>

                        <!-- Active Status -->
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="bitActive" name="bitActive" value="1"
                                    <?= old('bitActive', $serviceType['bitActive']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="bitActive">Active</label>
                            </div>
                        </div>

                        <!-- System Badge -->
                        <?php if (isset($serviceType['bitIsSystem']) && $serviceType['bitIsSystem']): ?>
                            <div class="mb-3">
                                <span class="badge bg-info">System Service Type</span>
                                <small class="text-muted ms-2">This is a system service type and some fields may be restricted.</small>
                            </div>
                        <?php endif; ?>

                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <a href="<?= base_url('service-types') ?>" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
