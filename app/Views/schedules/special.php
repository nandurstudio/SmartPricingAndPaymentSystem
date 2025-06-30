<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $pageTitle ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('schedules') ?>">Schedules</a></li>
        <li class="breadcrumb-item active"><?= $pageTitle ?></li>
    </ol>
    
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-calendar-day me-1"></i>
                            <?= $pageTitle ?>
                        </div>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addSpecialDateModal">
                            <i class="bi bi-plus-circle me-1"></i> Add Special Date
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (isset($services) && count($services) > 0) : ?>
                    <div class="mb-3">
                        <label for="service-filter" class="form-label">Filter by Service:</label>
                        <select class="form-select" id="service-filter">
                            <option value="">All Services</option>
                            <?php foreach ($services as $service) : ?>
                                <option value="<?= $service['id'] ?>" <?= (isset($_GET['service_id']) && $_GET['service_id'] == $service['id']) ? 'selected' : '' ?>>
                                    <?= esc($service['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="month-filter" class="form-label">Month:</label>
                        <input type="month" class="form-control" id="month-filter" value="<?= $current_month ?? date('Y-m') ?>">
                    </div>
                    
                    <div class="mb-3">
                        <button id="apply-filters" class="btn btn-primary"><i class="bi bi-funnel me-1"></i>Apply Filters</button>
                    </div>
                    <?php endif; ?>
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Actions</th>
                                    <th>Service</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Reason</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($specialDates)) : ?>
                                    <?php foreach ($specialDates as $special) : ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <button type="button" class="btn btn-warning btn-sm edit-special" 
                                                        data-id="<?= $special['id'] ?>" 
                                                        data-service="<?= $special['service_id'] ?>" 
                                                        data-service-name="<?= $special['service_name'] ?>" 
                                                        data-date="<?= $special['date'] ?>"
                                                        data-is-closed="<?= $special['is_closed'] ?>"
                                                        data-start="<?= $special['start_time'] ?>"
                                                        data-end="<?= $special['end_time'] ?>"
                                                        data-notes="<?= $special['notes'] ?>"
                                                        title="Edit" aria-label="Edit">
                                                        <i class="bi bi-pencil-square me-1"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-sm delete-special" 
                                                        data-id="<?= $special['id'] ?>"
                                                        data-service-name="<?= $special['service_name'] ?>" 
                                                        data-date="<?= date('M d, Y', strtotime($special['date'])) ?>"
                                                        title="Delete" aria-label="Delete">
                                                        <i class="bi bi-trash me-1"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td><?= esc($special['service_name']) ?></td>
                                            <td><?= date('M d, Y (D)', strtotime($special['date'])) ?></td>
                                            <td>
                                                <?php if ($special['is_closed']) : ?>
                                                    <span class="badge bg-danger">Closed</span>
                                                <?php else : ?>
                                                    <span class="badge bg-success">Special Hours</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= $special['start_time'] ? date('H:i', strtotime($special['start_time'])) : '-' ?></td>
                                            <td><?= $special['end_time'] ? date('H:i', strtotime($special['end_time'])) : '-' ?></td>
                                            <td><?= esc($special['notes']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No special dates found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-info-circle me-1"></i>
                    Special Dates Guide
                </div>
                <div class="card-body">
                    <h5>Managing Special Dates</h5>
                    <p>Special dates allow you to override your regular weekly schedule for specific dates such as:</p>
                    
                    <ul>
                        <li><strong>Holidays</strong> - Mark your service as closed on holidays</li>
                        <li><strong>Special Events</strong> - Set different operating hours for special events</li>
                        <li><strong>Seasonal Changes</strong> - Adjust your hours during seasonal periods</li>
                        <li><strong>Maintenance</strong> - Close your service for maintenance</li>
                    </ul>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-exclamation-circle me-1"></i>
                        Special dates take precedence over regular weekly schedules. When you set a special date, it will override the normal schedule for that day.
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Special Date Modal -->
<div class="modal fade" id="addSpecialDateModal" tabindex="-1" aria-labelledby="addSpecialDateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSpecialDateModalLabel">Add Special Date</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('schedules/special/store') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <?php 
                    $isEdit = false;
                    $special = null;
                    $services = $services ?? [];
                    $serviceNotFound = $serviceNotFound ?? false;
                    include(APPPATH . 'Views/schedules/_form_special.php'); 
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle me-1"></i>Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Special Date Modal -->
<?php
$editSpecial = null;
if (isset($_GET['edit_id']) && is_numeric($_GET['edit_id'])) {
    $editId = (int)$_GET['edit_id'];
    $specialModel = new \App\Models\SpecialScheduleModel();
    $editSpecial = $specialModel->find($editId);
    if ($editSpecial) {
        // Map DB fields to $special array expected by _form_special.php
        $serviceModel = new \App\Models\ServiceModel();
        $service = $serviceModel->find($editSpecial['intServiceID']);
        $special = [
            'id' => $editSpecial['intSpecialScheduleID'],
            'service_id' => $editSpecial['intServiceID'],
            'service_name' => $service ? $service['txtName'] : '',
            'date' => $editSpecial['dtmSpecialDate'],
            'is_closed' => (bool)$editSpecial['bitIsClosed'],
            'start_time' => $editSpecial['dtmStartTime'],
            'end_time' => $editSpecial['dtmEndTime'],
            'intSlotDuration' => $editSpecial['intSlotDuration'] ?? 60,
            'notes' => $editSpecial['txtNote']
        ];
    } else {
        $special = [];
    }
} else {
    $special = [];
}
?>
<div class="modal fade" id="editSpecialDateModal" tabindex="-1" aria-labelledby="editSpecialDateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSpecialDateModalLabel">Edit Special Date</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('schedules/update-special') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" id="edit_special_id" name="id" value="<?= esc($special['id'] ?? '') ?>">
                <div class="modal-body">
                    <?php 
                    $isEdit = true;
                    include(APPPATH . 'Views/schedules/_form_special.php'); 
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle me-1"></i>Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Special Date Confirmation Modal -->
<div class="modal fade" id="deleteSpecialModal" tabindex="-1" aria-labelledby="deleteSpecialModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteSpecialModalLabel">Delete Special Date</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('schedules/delete-special') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" id="delete_special_id" name="id">
                <div class="modal-body">
                    <p>Are you sure you want to delete the special date for <span id="delete-special-info" class="fw-bold"></span>?</p>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-1"></i>
                        Removing this special date will revert to using the regular weekly schedule for this date.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle me-1"></i>Cancel</button>
                    <button type="submit" class="btn btn-danger"><i class="bi bi-trash me-1"></i>Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->section('js') ?>
<script>
    // Base URL for API calls
    const baseUrl = '<?= base_url() ?>';
</script>
<script src="<?= base_url('assets/js/pages/schedules/schedules_special.js') ?>"></script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>
