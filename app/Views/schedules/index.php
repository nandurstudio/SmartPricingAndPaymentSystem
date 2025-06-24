<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $pageTitle ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Dashboard</a></li>
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
    
    <div class="row mb-3">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-calendar-week me-1"></i>
                            Regular Weekly Schedule
                        </div>
                        <a href="<?= base_url('schedules/create' . (isset($_GET['service_id']) ? '?service_id=' . $_GET['service_id'] : '')) ?>" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle me-1"></i> Add Schedule
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (isset($services) && count($services) > 0) : ?>
                    <div class="mb-3">
                        <label for="service-filter" class="form-label">Filter by Service:</label>
                        <select class="form-select" id="service-filter">
                            <option value="">All Services</option>
                            <?php foreach ($services as $service) : ?>
                                <option value="<?= $service['intServiceID'] ?>" 
                                    <?= (isset($selectedServiceId) && $selectedServiceId == $service['intServiceID']) ? 'selected' : '' ?>>
                                    <?= esc($service['txtName']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <button id="apply-filters" class="btn btn-primary"><i class="bi bi-funnel me-1"></i>Apply Filters</button>
                    </div>
                    <?php else : ?>
                        <?php if (empty($schedules)) : ?>
                            <div class="alert alert-info">
                                No services found. Please create services before setting up schedules.
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Actions</th>
                                    <th>Service</th>
                                    <th>Day</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Slot Duration</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($schedules)) : ?>
                                    <?php foreach ($schedules as $schedule) : ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="<?= base_url('schedules/edit/' . $schedule['intScheduleID'] . (isset($_GET['service_id']) ? '?service_id=' . $_GET['service_id'] : '')) ?>" class="btn btn-warning btn-sm edit-schedule" title="Edit">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-danger btn-sm delete-schedule" 
                                                        data-id="<?= $schedule['intScheduleID'] ?>" 
                                                        data-day="<?= $schedule['txtDay'] ?>" 
                                                        data-service="<?= $schedule['txtServiceName'] ?>" title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td><?= esc($schedule['txtServiceName']) ?></td>
                                            <td><?= esc($schedule['txtDay']) ?></td>
                                            <td><?= date('H:i', strtotime($schedule['dtmStartTime'])) ?></td>
                                            <td><?= date('H:i', strtotime($schedule['dtmEndTime'])) ?></td>
                                            <td><?= $schedule['intSlotDuration'] ?> minutes</td>
                                            <td>
                                                <?php if ($schedule['bitIsAvailable'] == 1) : ?>
                                                    <span class="badge bg-success">Available</span>
                                                <?php else : ?>
                                                    <span class="badge bg-danger">Not Available</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No schedules found.</td>
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
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-info-circle me-1"></i>
                            Schedule Guide
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <h5>Setting Up Schedules</h5>
                    <p>Regular schedules define the operating hours for your services on specific days of the week.</p>
                    
                    <h6>Important Tips:</h6>
                    <ul>
                        <li>Create a schedule for each day your service is available</li>
                        <li>Set slot durations based on your service time requirements</li>
                        <li>Manage special dates (holidays, etc.) in the Special Schedules section</li>
                        <li>Make sure to add schedules for all your active services</li>
                    </ul>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-exclamation-circle me-1"></i>
                        Services need schedules to be bookable. If a service has no schedule for a particular day, customers cannot book it on that day.
                    </div>
                    
                    <a href="<?= base_url('schedules/special' . (isset($_GET['service_id']) ? '?service_id=' . $_GET['service_id'] : '')) ?>" class="btn btn-outline-primary mt-3">
                        <i class="bi bi-calendar-day me-1"></i> Manage Special Dates
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Schedule Confirmation Modal -->
<div class="modal fade" id="deleteScheduleModal" tabindex="-1" aria-labelledby="deleteScheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteScheduleModalLabel">Delete Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('schedules/delete') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" id="schedule_id" name="id">
                <input type="hidden" id="repeat_delete" name="repeat_delete" value="0">
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        Warning: Deleting this schedule will make the service unavailable for booking on this day.
                    </div>
                    <p>Are you sure you want to delete the schedule for <span id="schedule-info" class="fw-bold"></span>?</p>
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" id="repeatDelete" name="repeatDelete" value="1">
                        <label class="form-check-label" for="repeatDelete">
                            Delete all repeated schedules for this service and day
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle me-1"></i>Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Delete Schedule</button>
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
<script src="<?= base_url('assets/js/pages/schedules/schedules.js') ?>"></script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>
