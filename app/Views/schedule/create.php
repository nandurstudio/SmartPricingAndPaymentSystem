<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $pageTitle ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('schedule') ?>">Schedules</a></li>
        <li class="breadcrumb-item active"><?= $pageTitle ?></li>
    </ol>
    
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-plus-circle me-1"></i>
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
                    <?php endif; ?>

                    <form action="<?= base_url('schedule/store') ?>" method="post">
                        <?= csrf_field() ?>
                        
                        <?php if (isset($tenants) && count($tenants) > 1) : ?>
                        <div class="mb-3">
                            <label for="tenant_id" class="form-label">Tenant <span class="text-danger">*</span></label>
                            <select class="form-select" id="tenant_id" name="tenant_id" required>
                                <option value="" selected disabled>Select Tenant</option>
                                <?php foreach ($tenants as $tenant) : ?>
                                    <option value="<?= $tenant['id'] ?>" <?= (old('tenant_id') == $tenant['id'] || (isset($_GET['tenant_id']) && $_GET['tenant_id'] == $tenant['id'])) ? 'selected' : '' ?>>
                                        <?= esc($tenant['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php else: ?>
                        <input type="hidden" name="tenant_id" value="<?= $tenant_id ?? '' ?>">
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label for="service_id" class="form-label">Service <span class="text-danger">*</span></label>
                            <select class="form-select" id="service_id" name="service_id" required>
                                <?php if (empty($services)) : ?>
                                    <option value="" selected disabled>Please select a tenant first</option>
                                <?php else : ?>
                                    <option value="" selected disabled>Select Service</option>
                                    <?php foreach ($services as $service) : ?>
                                        <option value="<?= $service['id'] ?>" 
                                                data-duration="<?= $service['duration'] ?>"
                                                <?= (old('service_id') == $service['id'] || (isset($_GET['service_id']) && $_GET['service_id'] == $service['id'])) ? 'selected' : '' ?>>
                                            <?= esc($service['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="day" class="form-label">Day <span class="text-danger">*</span></label>
                                <select class="form-select" id="day" name="day" required>
                                    <option value="" selected disabled>Select Day</option>
                                    <option value="Monday" <?= old('day') == 'Monday' ? 'selected' : '' ?>>Monday</option>
                                    <option value="Tuesday" <?= old('day') == 'Tuesday' ? 'selected' : '' ?>>Tuesday</option>
                                    <option value="Wednesday" <?= old('day') == 'Wednesday' ? 'selected' : '' ?>>Wednesday</option>
                                    <option value="Thursday" <?= old('day') == 'Thursday' ? 'selected' : '' ?>>Thursday</option>
                                    <option value="Friday" <?= old('day') == 'Friday' ? 'selected' : '' ?>>Friday</option>
                                    <option value="Saturday" <?= old('day') == 'Saturday' ? 'selected' : '' ?>>Saturday</option>
                                    <option value="Sunday" <?= old('day') == 'Sunday' ? 'selected' : '' ?>>Sunday</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="is_available" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select" id="is_available" name="is_available" required>
                                    <option value="1" <?= old('is_available') !== '0' ? 'selected' : '' ?>>Available</option>
                                    <option value="0" <?= old('is_available') === '0' ? 'selected' : '' ?>>Not Available</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="start_time" class="form-label">Start Time <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" id="start_time" name="start_time" value="<?= old('start_time') ?? '09:00' ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="end_time" class="form-label">End Time <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" id="end_time" name="end_time" value="<?= old('end_time') ?? '17:00' ?>" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="slot_duration" class="form-label">Slot Duration (minutes) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="slot_duration" name="slot_duration" value="<?= old('slot_duration') ?? '60' ?>" min="5" required>
                            <div class="form-text" id="duration-help">Set the duration for each booking slot. Should match or be a multiple of your service duration.</div>
                        </div>
                        
                        <div class="alert alert-info mb-4">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="fas fa-info-circle fa-2x"></i>
                                </div>
                                <div>
                                    <h5>Slot Information</h5>
                                    <p class="mb-0">Based on your settings, this will create <span id="slot-count" class="fw-bold">0</span> slots for bookings every <span id="day-name">day</span>.</p>
                                    <p class="mb-0">First slot: <span id="first-slot" class="fw-bold">-</span></p>
                                    <p class="mb-0">Last slot: <span id="last-slot" class="fw-bold">-</span></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="repeatWeekly" name="repeat_weekly" value="1">
                                <label class="form-check-label" for="repeatWeekly">
                                    Create schedules for this service on this day every week
                                </label>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Create Schedule</button>
                            <a href="<?= base_url('schedule') ?>" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tenantSelect = document.getElementById('tenant_id');
    const serviceSelect = document.getElementById('service_id');
    const daySelect = document.getElementById('day');
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');
    const slotDurationInput = document.getElementById('slot_duration');
    const slotCountSpan = document.getElementById('slot-count');
    const firstSlotSpan = document.getElementById('first-slot');
    const lastSlotSpan = document.getElementById('last-slot');
    const dayNameSpan = document.getElementById('day-name');
    
    // When tenant changes, load services for that tenant
    if (tenantSelect) {
        tenantSelect.addEventListener('change', function() {
            const tenantId = this.value;
            serviceSelect.innerHTML = '<option value="" selected disabled>Loading services...</option>';
            
            fetch(`<?= base_url('api/get-services-by-tenant') ?>/${tenantId}`)
                .then(response => response.json())
                .then(data => {
                    serviceSelect.innerHTML = '<option value="" selected disabled>Select Service</option>';
                    
                    if (data.services && data.services.length > 0) {
                        data.services.forEach(service => {
                            const option = document.createElement('option');
                            option.value = service.id;
                            option.dataset.duration = service.duration;
                            option.textContent = service.name;
                            serviceSelect.appendChild(option);
                        });
                    } else {
                        serviceSelect.innerHTML = '<option value="" selected disabled>No services available</option>';
                    }
                    
                    updateSlotDuration();
                    calculateSlots();
                })
                .catch(error => {
                    console.error('Error fetching services:', error);
                    serviceSelect.innerHTML = '<option value="" selected disabled>Error loading services</option>';
                });
        });
    }
    
    // When service changes, update the slot duration
    serviceSelect.addEventListener('change', updateSlotDuration);
    
    // Update slot duration based on selected service
    function updateSlotDuration() {
        const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
        if (selectedOption && selectedOption.dataset.duration) {
            slotDurationInput.value = selectedOption.dataset.duration;
        }
        calculateSlots();
    }
    
    // Calculate number of slots
    function calculateSlots() {
        const startTime = startTimeInput.value;
        const endTime = endTimeInput.value;
        const slotDuration = parseInt(slotDurationInput.value);
        const day = daySelect.value;
        
        if (!startTime || !endTime || !slotDuration || slotDuration <= 0) {
            slotCountSpan.textContent = '0';
            firstSlotSpan.textContent = '-';
            lastSlotSpan.textContent = '-';
            return;
        }
        
        // Update day name
        dayNameSpan.textContent = day || 'day';
        
        // Calculate slots
        const start = new Date(`2000-01-01T${startTime}`);
        const end = new Date(`2000-01-01T${endTime}`);
        
        if (start >= end) {
            slotCountSpan.textContent = '0';
            firstSlotSpan.textContent = '-';
            lastSlotSpan.textContent = '-';
            return;
        }
        
        const diffMs = end - start;
        const diffMinutes = Math.floor(diffMs / 60000);
        const slots = Math.floor(diffMinutes / slotDuration);
        
        slotCountSpan.textContent = slots.toString();
        
        // Show first and last slot
        const firstSlotStart = new Date(start);
        const firstSlotEnd = new Date(firstSlotStart);
        firstSlotEnd.setMinutes(firstSlotEnd.getMinutes() + slotDuration);
        
        const lastSlotStart = new Date(start);
        lastSlotStart.setMinutes(lastSlotStart.getMinutes() + (slots - 1) * slotDuration);
        const lastSlotEnd = new Date(lastSlotStart);
        lastSlotEnd.setMinutes(lastSlotEnd.getMinutes() + slotDuration);
        
        firstSlotSpan.textContent = `${formatTime(firstSlotStart)} - ${formatTime(firstSlotEnd)}`;
        lastSlotSpan.textContent = `${formatTime(lastSlotStart)} - ${formatTime(lastSlotEnd)}`;
    }
    
    function formatTime(date) {
        let hours = date.getHours();
        const minutes = date.getMinutes().toString().padStart(2, '0');
        const ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'
        return `${hours}:${minutes} ${ampm}`;
    }
    
    // Update calculations when any input changes
    startTimeInput.addEventListener('change', calculateSlots);
    endTimeInput.addEventListener('change', calculateSlots);
    slotDurationInput.addEventListener('input', calculateSlots);
    daySelect.addEventListener('change', calculateSlots);
    
    // Initialize calculations
    calculateSlots();
});
</script>
<?= $this->endSection() ?>
