<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-xl px-4 mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header justify-content-center text-center">
                    <h3 class="fw-light my-2">Selamat! Tenant Anda Berhasil Dibuat</h3>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="feature bg-success bg-gradient text-white rounded-3 mb-3 mx-auto">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <h4 class="text-success">Free Plan Activated</h4>
                        <p class="mb-4">Anda telah berhasil mendaftar dengan Free Plan</p>
                    </div>

                    <div class="row gx-3 mb-4">
                        <div class="col-12">
                            <h5>Fitur yang tersedia:</h5>
                            <ul class="list-unstyled">
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Manajemen layanan dasar</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Sistem booking sederhana</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>1 staff account</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Laporan basic</li>
                            </ul>
                        </div>
                    </div>

                    <div class="row gx-3 mb-4">
                        <div class="col-12">
                            <div class="alert alert-info" role="alert">
                                <i class="bi bi-info-circle me-2"></i>
                                Trial period akan berakhir dalam 14 hari. Anda dapat upgrade ke paket berbayar kapan saja.
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <a href="<?= base_url('dashboard') ?>" class="btn btn-primary">Mulai Menggunakan</a>
                        <a href="<?= base_url('subscription/plans') ?>" class="btn btn-outline-primary ms-2">Lihat Paket Lain</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
