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
                    <?php endif; ?>                    <form action="<?= base_url('schedules/update/' . $schedule['id']) ?>" method="post">
                        <?= csrf_field() ?>
                        
                        <div class="mb-3">
                            <label class="form-label">Service</label>
                            <input type="text" class="form-control" value="<?= esc($schedule['service_name']) ?>" readonly>
                            <input type="hidden" name="intServiceID" value="<?= $schedule['service_id'] ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Day</label>
                            <input type="text" class="form-control" value="<?= esc($schedule['day']) ?>" readonly>
                            <input type="hidden" name="txtDay" value="<?= $schedule['day'] ?>">
                        </div>
                        
                        <?= $this->include('schedules/_form', ['schedule' => $schedule]) ?>
                        
                        <div class="alert alert-info mb-4">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="fas fa-info-circle fa-2x"></i>
                                </div>
                                <div>
                                    <h5>Slot Information</h5>
                                    <p class="mb-0">Based on your settings, this will create <span id="slot-count" class="fw-bold">0</span> slots for bookings every <span id="day-name"><?= $schedule['day'] ?></span>.</p>
                                    <p class="mb-0">First slot: <span id="first-slot" class="fw-bold">-</span></p>
                                    <p class="mb-0">Last slot: <span id="last-slot" class="fw-bold">-</span></p>
                                </div>
                            </div>
                        </div>
                        
                        <?php if (!empty($bookings)) : ?>
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                <strong>Warning:</strong> This schedule has <?= count($bookings) ?> existing bookings. Changing times may affect these bookings.
                            </div>
                        <?php endif; ?>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Update Schedule</button>
                            <a href="<?= base_url('schedule') ?>" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {    const startTimeInput = document.getElementById('dtmStartTime');
    const endTimeInput = document.getElementById('dtmEndTime');
    const slotDurationInput = document.getElementById('intSlotDuration');
    const slotCountSpan = document.getElementById('slot-count');
    const firstSlotSpan = document.getElementById('first-slot');
    const lastSlotSpan = document.getElementById('last-slot');
    
    // Calculate number of slots
    function calculateSlots() {
        const startTime = startTimeInput.value;
        const endTime = endTimeInput.value;
        const slotDuration = parseInt(slotDurationInput.value);
        
        if (!startTime || !endTime || !slotDuration || slotDuration <= 0) {
            slotCountSpan.textContent = '0';
            firstSlotSpan.textContent = '-';
            lastSlotSpan.textContent = '-';
            return;
        }
        
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
    
    // Initialize calculations
    calculateSlots();
});
</script>
<?= $this->endSection() ?>
