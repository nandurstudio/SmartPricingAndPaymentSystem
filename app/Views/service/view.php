<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $pageTitle ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('service') ?>">Services</a></li>
        <li class="breadcrumb-item active"><?= $pageTitle ?></li>
    </ol>
    
    <div class="row">
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-info-circle me-1"></i>
                            Service Information
                        </div>
                        <div>
                            <a href="<?= base_url('service/edit/' . $service['id']) ?>" class="btn btn-warning btn-sm me-1">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="<?= base_url('schedule?service_id=' . $service['id']) ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-calendar"></i> Schedules
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="card-title"><?= esc($service['name']) ?></h5>
                            <p class="mb-0">
                                <span class="fw-bold">Service Type:</span> <?= esc($service['type_name'] ?? 'N/A') ?>
                            </p>
                            <p class="mb-0">
                                <span class="fw-bold">Tenant:</span> 
                                <a href="<?= base_url('tenant/view/' . $service['tenant_id']) ?>"><?= esc($service['tenant_name'] ?? 'N/A') ?></a>
                            </p>
                            <p class="mb-0">
                                <span class="fw-bold">Price:</span> Rp <?= number_format($service['price'], 2) ?>
                            </p>
                            <p class="mb-0">
                                <span class="fw-bold">Duration:</span> <?= $service['duration'] ?> minutes
                            </p>
                            <p class="mb-0">
                                <span class="fw-bold">Capacity:</span> <?= $service['capacity'] ?? '1' ?> person(s)
                            </p>
                            <p class="mb-0">
                                <span class="fw-bold">Status:</span>
                                <?php if ($service['is_active'] == 1) : ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else : ?>
                                    <span class="badge bg-danger">Inactive</span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <?php if (!empty($service['image'])) : ?>
                                <img src="<?= base_url('uploads/services/' . $service['image']) ?>" alt="<?= esc($service['name']) ?>" class="img-fluid rounded">
                            <?php else : ?>
                                <div class="border rounded d-flex align-items-center justify-content-center text-muted" style="height: 150px;">
                                    <div>No Image</div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <hr>
                    <h6 class="fw-bold">Description</h6>
                    <p><?= nl2br(esc($service['description'])) ?></p>
                    
                    <?php if (!empty($attributes)) : ?>
                        <hr>
                        <h6 class="fw-bold">Additional Information</h6>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($attributes as $key => $value) : ?>
                                <li class="list-group-item px-0">
                                    <strong><?= esc($key) ?>:</strong> <?= esc($value) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-calendar-week me-1"></i>
                            Weekly Schedule
                        </div>
                        <a href="<?= base_url('schedule/create?service_id=' . $service['id']) ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus-circle"></i> Add Schedule
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (!empty($schedules)) : ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Day</th>
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                        <th>Slot Duration</th>
                                        <th>Availability</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($schedules as $schedule) : ?>
                                        <tr>
                                            <td><?= esc($schedule['day']) ?></td>
                                            <td><?= date('h:i A', strtotime($schedule['start_time'])) ?></td>
                                            <td><?= date('h:i A', strtotime($schedule['end_time'])) ?></td>
                                            <td><?= $schedule['slot_duration'] ?> minutes</td>
                                            <td>
                                                <?php if ($schedule['is_available'] == 1) : ?>
                                                    <span class="badge bg-success">Available</span>
                                                <?php else : ?>
                                                    <span class="badge bg-danger">Not Available</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="<?= base_url('schedule/edit/' . $schedule['id']) ?>" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else : ?>
                        <div class="alert alert-info">
                            No regular schedule has been set for this service. 
                            <a href="<?= base_url('schedule/create?service_id=' . $service['id']) ?>">Create a schedule</a> to allow bookings.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-calendar-alt me-1"></i>
                    Recent Bookings
                </div>
                <div class="card-body">
                    <?php if (!empty($bookings)) : ?>
                        <div class="list-group">
                            <?php foreach ($bookings as $booking) : ?>
                                <a href="<?= base_url('booking/view/' . $booking['id']) ?>" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1"><?= esc($booking['customer_name'] ?? 'Guest') ?></h6>
                                        <small>
                                            <?php if ($booking['status'] == 'confirmed') : ?>
                                                <span class="badge bg-success">Confirmed</span>
                                            <?php elseif ($booking['status'] == 'pending') : ?>
                                                <span class="badge bg-warning">Pending</span>
                                            <?php elseif ($booking['status'] == 'cancelled') : ?>
                                                <span class="badge bg-danger">Cancelled</span>
                                            <?php elseif ($booking['status'] == 'completed') : ?>
                                                <span class="badge bg-info">Completed</span>
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                    <small><?= date('M d, Y', strtotime($booking['booking_date'])) ?> at <?= date('h:i A', strtotime($booking['start_time'])) ?></small>
                                </a>
                            <?php endforeach; ?>
                        </div>
                        <div class="mt-3">
                            <a href="<?= base_url('booking?service_id=' . $service['id']) ?>" class="btn btn-sm btn-outline-primary">View All Bookings</a>
                        </div>
                    <?php else : ?>
                        <div class="alert alert-info">
                            No bookings found for this service.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-tools me-1"></i>
                    Quick Actions
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="<?= base_url('schedule/special?service_id=' . $service['id']) ?>" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Special Schedule Days</h6>
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            <small class="text-muted">Add holidays or special operating hours</small>
                        </a>
                        <a href="<?= base_url('booking/create?service_id=' . $service['id']) ?>" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Create Booking</h6>
                                <i class="fas fa-calendar-plus"></i>
                            </div>
                            <small class="text-muted">Add a new booking for this service</small>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action" data-bs-toggle="modal" data-bs-target="#availabilityModal">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Check Availability</h6>
                                <i class="fas fa-clock"></i>
                            </div>
                            <small class="text-muted">See available slots for a specific date</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Availability Check Modal -->
<div class="modal fade" id="availabilityModal" tabindex="-1" aria-labelledby="availabilityModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="availabilityModalLabel">Check Availability</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="check-date" class="form-label">Select Date</label>
                    <input type="date" class="form-control" id="check-date" min="<?= date('Y-m-d') ?>">
                </div>
                <div id="availability-results" class="mt-3" style="display: none;">
                    <h6 class="border-bottom pb-2">Available Time Slots</h6>
                    <div id="time-slots" class="mt-3 d-flex flex-wrap gap-2"></div>
                </div>
                <div id="availability-loading" class="text-center mt-3" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p>Checking availability...</p>
                </div>
                <div id="availability-error" class="alert alert-danger mt-3" style="display: none;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="check-availability-btn">Check Availability</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkDateInput = document.getElementById('check-date');
    const checkButton = document.getElementById('check-availability-btn');
    const resultsDiv = document.getElementById('availability-results');
    const timeSlotsDiv = document.getElementById('time-slots');
    const loadingDiv = document.getElementById('availability-loading');
    const errorDiv = document.getElementById('availability-error');
    
    // Set min date to today
    checkDateInput.min = new Date().toISOString().split('T')[0];
    checkDateInput.value = new Date().toISOString().split('T')[0];
    
    checkButton.addEventListener('click', function() {
        const selectedDate = checkDateInput.value;
        if (!selectedDate) {
            errorDiv.textContent = 'Please select a date.';
            errorDiv.style.display = 'block';
            resultsDiv.style.display = 'none';
            return;
        }
        
        // Show loading, hide results and errors
        loadingDiv.style.display = 'block';
        resultsDiv.style.display = 'none';
        errorDiv.style.display = 'none';
        
        // API call to check availability
        fetch(`<?= base_url('api/check-availability/' . $service['id']) ?>?date=${selectedDate}`)
            .then(response => response.json())
            .then(data => {
                loadingDiv.style.display = 'none';
                
                if (data.error) {
                    errorDiv.textContent = data.error;
                    errorDiv.style.display = 'block';
                    return;
                }
                
                // Display results
                timeSlotsDiv.innerHTML = '';
                
                if (data.slots && data.slots.length > 0) {
                    data.slots.forEach(slot => {
                        const slotButton = document.createElement('button');
                        slotButton.className = 'btn btn-outline-primary btn-sm';
                        slotButton.textContent = slot.time;
                        slotButton.title = `${slot.time} - ${slot.end_time}`;
                        
                        if (!slot.available) {
                            slotButton.className = 'btn btn-outline-secondary btn-sm';
                            slotButton.disabled = true;
                        }
                        
                        timeSlotsDiv.appendChild(slotButton);
                    });
                } else {
                    timeSlotsDiv.innerHTML = '<div class="alert alert-warning">No available slots for the selected date.</div>';
                }
                
                resultsDiv.style.display = 'block';
            })
            .catch(error => {
                loadingDiv.style.display = 'none';
                errorDiv.textContent = 'Error checking availability. Please try again.';
                errorDiv.style.display = 'block';
                console.error('Error:', error);
            });
    });
});
</script>
<?= $this->endSection() ?>
