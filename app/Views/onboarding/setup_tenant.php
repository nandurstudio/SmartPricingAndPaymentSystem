<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header">
                    <h3 class="text-center font-weight-light my-4">Setup Your Business</h3>
                </div>
                <div class="card-body">
                    <?php if (session()->has('errors')) : ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                <?php foreach (session('errors') as $error) : ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif ?>

                    <form action="<?= base_url('onboarding/create-tenant') ?>" method="post">
                        <?= csrf_field() ?>
                          <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-floating mb-3 mb-md-0">
                                    <input class="form-control" id="name" name="name" type="text" value="<?= old('name') ?>" required />
                                    <label for="name">Business Name</label>
                                </div>
                            </div><div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select" id="service_type_id" name="service_type_id" required>
                                        <option value="" selected disabled>Select Type</option>
                                        <?php foreach ($serviceTypes as $type): ?>
                                            <option value="<?= $type['intServiceTypeID'] ?>" <?= old('service_type_id') == $type['intServiceTypeID'] ? 'selected' : '' ?>>
                                                <?= esc($type['txtName']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <label for="service_type_id">Business Type</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <textarea class="form-control" id="description" name="description" style="height: 100px" required><?= old('description') ?></textarea>
                            <label for="description">Business Description</label>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-floating mb-3 mb-md-0">
                                    <input class="form-control" id="domain" name="domain" type="url" placeholder="yourbusiness.com" value="<?= old('domain') ?>" />
                                    <label for="domain">Domain (Optional)</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select" id="subscription_plan" name="subscription_plan" required>
                                        <option value="" selected disabled>Select Plan</option>
                                        <option value="free" <?= old('subscription_plan') == 'free' ? 'selected' : '' ?>>Free</option>
                                        <option value="basic" <?= old('subscription_plan') == 'basic' ? 'selected' : '' ?>>Basic</option>
                                        <option value="premium" <?= old('subscription_plan') == 'premium' ? 'selected' : '' ?>>Premium</option>
                                        <option value="enterprise" <?= old('subscription_plan') == 'enterprise' ? 'selected' : '' ?>>Enterprise</option>
                                    </select>
                                    <label for="subscription_plan">Subscription Plan</label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 mb-0">
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-block">Create Business</button>
                            </div>
                        </div>
                    </form>
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
<?= $this->endSection() ?>
