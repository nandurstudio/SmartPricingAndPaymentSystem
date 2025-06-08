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
                    <?php endif; ?>                    <form action="<?= base_url('schedules/store') ?>" method="post">
                        <?= $this->include('schedules/_form') ?>
                        
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
                            <a href="<?= base_url('schedules') ?>" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
jQuery(function($) {
    const tenantSelect = document.getElementById('intTenantID');
    const serviceSelect = document.getElementById('intServiceID');
    const daySelect = document.getElementById('txtDay');
    const startTimeInput = document.getElementById('dtmStartTime');
    const endTimeInput = document.getElementById('dtmEndTime');
    const slotDurationInput = document.getElementById('intSlotDuration');
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
        if (!startTimeInput || !endTimeInput || !slotDurationInput || !slotCountSpan || !firstSlotSpan || !lastSlotSpan || !dayNameSpan) {
            console.warn('Some required elements are missing');
            return;
        }
        
        const startTime = startTimeInput.value;
        const endTime = endTimeInput.value;
        const slotDuration = parseInt(slotDurationInput.value);
        const day = daySelect ? daySelect.value : '';
        
        if (!startTime || !endTime || !slotDuration || slotDuration <= 0) {
            slotCountSpan.textContent = '0';
            firstSlotSpan.textContent = '-';
            lastSlotSpan.textContent = '-';
            return;
        }
        
        // Update day name
    if (dayNameSpan) {
            dayNameSpan.textContent = day || 'day';
        }
        
        // Calculate slots
        const start = new Date(`2000-01-01T${startTime}`);
        const end = new Date(`2000-01-01T${endTime}`);
        
        if (start >= end || !slotCountSpan || !firstSlotSpan || !lastSlotSpan) {
            if (slotCountSpan) slotCountSpan.textContent = '0';
            if (firstSlotSpan) firstSlotSpan.textContent = '-';
            if (lastSlotSpan) lastSlotSpan.textContent = '-';
            return;
        }
        
        const diffMs = end - start;
        const diffMinutes = Math.floor(diffMs / 60000);
        const slots = Math.floor(diffMinutes / slotDuration);
          if (slotCountSpan) {
            slotCountSpan.textContent = slots.toString();
        }
        
        // Show first and last slot
        const firstSlotStart = new Date(start);
        const firstSlotEnd = new Date(firstSlotStart);
        firstSlotEnd.setMinutes(firstSlotEnd.getMinutes() + slotDuration);
        
        const lastSlotStart = new Date(start);
        lastSlotStart.setMinutes(lastSlotStart.getMinutes() + (slots - 1) * slotDuration);
        const lastSlotEnd = new Date(lastSlotStart);
        lastSlotEnd.setMinutes(lastSlotEnd.getMinutes() + slotDuration);
        
        if (firstSlotSpan) {
            firstSlotSpan.textContent = `${formatTime(firstSlotStart)} - ${formatTime(firstSlotEnd)}`;
        }
        if (lastSlotSpan) {
            lastSlotSpan.textContent = `${formatTime(lastSlotStart)} - ${formatTime(lastSlotEnd)}`;
        }
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
    if (startTimeInput) startTimeInput.addEventListener('change', calculateSlots);
    if (endTimeInput) endTimeInput.addEventListener('change', calculateSlots);
    if (slotDurationInput) slotDurationInput.addEventListener('input', calculateSlots);
    if (daySelect) daySelect.addEventListener('change', calculateSlots);
    
    // Initialize calculations
    calculateSlots();
});
</script>
<?= $this->endSection() ?>
