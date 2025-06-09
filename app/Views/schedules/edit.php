<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $pageTitle ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('schedules') ?>">Schedules</a></li>
        <li class="breadcrumb-item active"><?= $pageTitle ?></li>
    </ol>
    
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-edit me-1"></i>
                    <?= $pageTitle ?>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('errors')) : ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h4>Error:</h4>
                            <ul>
                                <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>                    <form action="<?= base_url('schedules/update/' . $schedule['intScheduleID']) ?>" method="post">
                        <?= csrf_field() ?>
                        
                        <div class="mb-3">                            <label class="form-label">Service</label>
                            <input type="text" class="form-control" value="<?= esc($schedule['txtServiceName'] ?? 'Service not found') ?>" readonly>
                            <input type="hidden" name="intServiceID" value="<?= $schedule['intServiceID'] ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Day</label>                            <input type="text" class="form-control" value="<?= esc($schedule['txtDay']) ?>" readonly>
                            <input type="hidden" name="txtDay" value="<?= $schedule['txtDay'] ?>">
                        </div>
                        
                        <?= $this->include('schedules/_form', ['schedule' => $schedule]) ?>
                        
                        <div class="alert alert-info mb-4">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="fas fa-info-circle fa-2x"></i>
                                </div>
                                <div>
                                    <h5>Slot Information</h5>
                                    <p class="mb-0">Based on your settings, this will create <span id="slot-count" class="fw-bold">0</span> slots for bookings every <span id="day-name"><?= $schedule['txtDay'] ?></span>.</p>
                                    <p class="mb-0">First slot: <span id="first-slot" class="fw-bold">-</span></p>
                                    <p class="mb-0">Last slot: <span id="last-slot" class="fw-bold">-</span></p>
                                </div>
                            </div>
                        </div>
                        
                        <?php if (!empty($bookings)) : ?>
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                <strong>Warning:</strong> This schedule has <?= count($bookings) ?> existing bookings. Changing times may affect these bookings.
                            </div>                        <?php endif; ?>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?= base_url('schedules' . (isset($_GET['service_id']) ? '?service_id=' . $_GET['service_id'] : '')) ?>" class="btn btn-secondary me-md-2">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Update Schedule
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->section('js') ?>
<script>
    // Base URL for API calls
    const baseUrl = '<?= base_url() ?>';
</script>
<script src="<?= base_url('assets/js/pages/schedules/schedules.js') ?>"></script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>
