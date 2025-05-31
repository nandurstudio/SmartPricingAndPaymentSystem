<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $pageTitle ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('schedule') ?>">Schedules</a></li>
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
                            <i class="fas fa-calendar-day me-1"></i>
                            <?= $pageTitle ?>
                        </div>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addSpecialDateModal">
                            <i class="fas fa-plus-circle"></i> Add Special Date
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
                        <button id="apply-filters" class="btn btn-primary">Apply Filters</button>
                    </div>
                    <?php endif; ?>
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Service</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Reason</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($specialDates)) : ?>
                                    <?php foreach ($specialDates as $special) : ?>
                                        <tr>
                                            <td><?= esc($special['service_name']) ?></td>
                                            <td><?= date('M d, Y (D)', strtotime($special['date'])) ?></td>
                                            <td>
                                                <?php if ($special['is_closed']) : ?>
                                                    <span class="badge bg-danger">Closed</span>
                                                <?php else : ?>
                                                    <span class="badge bg-success">Special Hours</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= $special['is_closed'] ? 'N/A' : date('h:i A', strtotime($special['start_time'])) ?></td>
                                            <td><?= $special['is_closed'] ? 'N/A' : date('h:i A', strtotime($special['end_time'])) ?></td>
                                            <td><?= esc($special['notes']) ?></td>
                                            <td>
                                                <button type="button" class="btn btn-warning btn-sm edit-special" 
                                                    data-id="<?= $special['id'] ?>" 
                                                    data-service="<?= $special['service_id'] ?>" 
                                                    data-service-name="<?= $special['service_name'] ?>" 
                                                    data-date="<?= $special['date'] ?>"
                                                    data-is-closed="<?= $special['is_closed'] ?>"
                                                    data-start="<?= $special['start_time'] ?>"
                                                    data-end="<?= $special['end_time'] ?>"
                                                    data-notes="<?= $special['notes'] ?>">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm delete-special" 
                                                    data-id="<?= $special['id'] ?>"
                                                    data-service-name="<?= $special['service_name'] ?>" 
                                                    data-date="<?= date('M d, Y', strtotime($special['date'])) ?>">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
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
                    <i class="fas fa-info-circle me-1"></i>
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
                        <i class="fas fa-exclamation-circle me-1"></i>
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
            <form action="<?= base_url('schedule/store-special') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="special_service_id" class="form-label">Service <span class="text-danger">*</span></label>
                        <select class="form-select" id="special_service_id" name="service_id" required>
                            <option value="" selected disabled>Select Service</option>
                            <?php foreach ($services as $service) : ?>
                                <option value="<?= $service['id'] ?>" <?= (isset($_GET['service_id']) && $_GET['service_id'] == $service['id']) ? 'selected' : '' ?>>
                                    <?= esc($service['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="special_date" class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="special_date" name="date" min="<?= date('Y-m-d') ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="is_closed" name="is_closed">
                            <label class="form-check-label" for="is_closed">
                                Closed on this date
                            </label>
                        </div>
                    </div>
                    
                    <div id="operating-hours-div">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="special_start_time" class="form-label">Start Time <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" id="special_start_time" name="start_time" value="09:00">
                            </div>
                            <div class="col-md-6">
                                <label for="special_end_time" class="form-label">End Time <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" id="special_end_time" name="end_time" value="17:00">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="special_notes" class="form-label">Reason/Notes</label>
                        <textarea class="form-control" id="special_notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Special Date Modal -->
<div class="modal fade" id="editSpecialDateModal" tabindex="-1" aria-labelledby="editSpecialDateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSpecialDateModalLabel">Edit Special Date</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('schedule/update-special') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" id="edit_special_id" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Service</label>
                        <input type="text" class="form-control" id="edit_service_name" readonly>
                        <input type="hidden" id="edit_service_id" name="service_id">
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_special_date" class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="edit_special_date" name="date" min="<?= date('Y-m-d') ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="edit_is_closed" name="is_closed">
                            <label class="form-check-label" for="edit_is_closed">
                                Closed on this date
                            </label>
                        </div>
                    </div>
                    
                    <div id="edit-operating-hours-div">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="edit_special_start_time" class="form-label">Start Time <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" id="edit_special_start_time" name="start_time">
                            </div>
                            <div class="col-md-6">
                                <label for="edit_special_end_time" class="form-label">End Time <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" id="edit_special_end_time" name="end_time">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_special_notes" class="form-label">Reason/Notes</label>
                        <textarea class="form-control" id="edit_special_notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
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
            <form action="<?= base_url('schedule/delete-special') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" id="delete_special_id" name="id">
                <div class="modal-body">
                    <p>Are you sure you want to delete the special date for <span id="delete-special-info" class="fw-bold"></span>?</p>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-1"></i>
                        Removing this special date will revert to using the regular weekly schedule for this date.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const applyFiltersBtn = document.getElementById('apply-filters');
    if (applyFiltersBtn) {
        applyFiltersBtn.addEventListener('click', function() {
            const serviceFilter = document.getElementById('service-filter')?.value || '';
            const monthFilter = document.getElementById('month-filter')?.value || '';
            
            let url = '<?= base_url('schedule/special') ?>?';
            if (serviceFilter) url += `service_id=${serviceFilter}&`;
            if (monthFilter) {
                const [year, month] = monthFilter.split('-');
                url += `month=${month}&year=${year}`;
            }
            
            window.location.href = url;
        });
    }
    
    // Closed checkbox functionality
    const isClosedCheckbox = document.getElementById('is_closed');
    const operatingHoursDiv = document.getElementById('operating-hours-div');
    
    isClosedCheckbox.addEventListener('change', function() {
        if (this.checked) {
            operatingHoursDiv.style.display = 'none';
        } else {
            operatingHoursDiv.style.display = 'block';
        }
    });
    
    const editIsClosedCheckbox = document.getElementById('edit_is_closed');
    const editOperatingHoursDiv = document.getElementById('edit-operating-hours-div');
    
    editIsClosedCheckbox.addEventListener('change', function() {
        if (this.checked) {
            editOperatingHoursDiv.style.display = 'none';
        } else {
            editOperatingHoursDiv.style.display = 'block';
        }
    });
    
    // Edit special date functionality
    const editSpecialModal = new bootstrap.Modal(document.getElementById('editSpecialDateModal'));
    const editButtons = document.querySelectorAll('.edit-special');
    
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const serviceId = this.getAttribute('data-service');
            const serviceName = this.getAttribute('data-service-name');
            const date = this.getAttribute('data-date');
            const isClosed = this.getAttribute('data-is-closed') === '1';
            const startTime = this.getAttribute('data-start');
            const endTime = this.getAttribute('data-end');
            const notes = this.getAttribute('data-notes');
            
            document.getElementById('edit_special_id').value = id;
            document.getElementById('edit_service_id').value = serviceId;
            document.getElementById('edit_service_name').value = serviceName;
            document.getElementById('edit_special_date').value = date;
            document.getElementById('edit_is_closed').checked = isClosed;
            document.getElementById('edit_special_start_time').value = startTime ? startTime.substring(0, 5) : '';
            document.getElementById('edit_special_end_time').value = endTime ? endTime.substring(0, 5) : '';
            document.getElementById('edit_special_notes').value = notes;
            
            if (isClosed) {
                editOperatingHoursDiv.style.display = 'none';
            } else {
                editOperatingHoursDiv.style.display = 'block';
            }
            
            editSpecialModal.show();
        });
    });
    
    // Delete special date functionality
    const deleteSpecialModal = new bootstrap.Modal(document.getElementById('deleteSpecialModal'));
    const deleteButtons = document.querySelectorAll('.delete-special');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const serviceName = this.getAttribute('data-service-name');
            const date = this.getAttribute('data-date');
            
            document.getElementById('delete_special_id').value = id;
            document.getElementById('delete-special-info').textContent = `${serviceName} on ${date}`;
            
            deleteSpecialModal.show();
        });
    });
    
    // If there's a service_id parameter in the URL, auto-select the service
    const urlParams = new URLSearchParams(window.location.search);
    const serviceId = urlParams.get('service_id');
    if (serviceId) {
        const serviceSelect = document.getElementById('service-filter');
        const specialServiceSelect = document.getElementById('special_service_id');
        
        if (serviceSelect) {
            for (let i = 0; i < serviceSelect.options.length; i++) {
                if (serviceSelect.options[i].value === serviceId) {
                    serviceSelect.options[i].selected = true;
                    break;
                }
            }
        }
        
        if (specialServiceSelect) {
            for (let i = 0; i < specialServiceSelect.options.length; i++) {
                if (specialServiceSelect.options[i].value === serviceId) {
                    specialServiceSelect.options[i].selected = true;
                    break;
                }
            }
        }
    }
});
</script>
<?= $this->endSection() ?>
