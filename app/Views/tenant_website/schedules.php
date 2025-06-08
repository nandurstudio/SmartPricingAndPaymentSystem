<?= $this->extend('layouts/tenant_website') ?>

<?= $this->section('content') ?>
<section class="schedules-section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Schedules</h2>
            <p class="text-muted">View our availability and schedules</p>
        </div>

        <div class="row">
            <div class="col-md-4">
                <!-- Calendar Widget -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">Select Date</h5>
                    </div>
                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <!-- Schedule Display -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Available Time Slots</h5>
                        <span id="selected-date" class="badge bg-primary"></span>
                    </div>
                    <div class="card-body">
                        <div id="time-slots">
                            <!-- Time slots will be loaded dynamically -->
                            <div class="text-center py-5">
                                <i class="fas fa-calendar-alt text-muted mb-3" style="font-size: 3rem;"></i>
                                <h4 class="text-muted">Select a date to view available slots</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendar = flatpickr('#calendar', {
        inline: true,
        minDate: 'today',
        dateFormat: 'Y-m-d',
        onChange: function(selectedDates, dateStr) {
            document.getElementById('selected-date').textContent = dateStr;
            loadTimeSlots(dateStr);
        }
    });

    function loadTimeSlots(date) {
        // Add your time slot loading logic here
        // This should make an AJAX call to your backend to get available slots
        const timeSlotsContainer = document.getElementById('time-slots');
        timeSlotsContainer.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading slots...</div>';
        
        // Example AJAX call
        fetch(`<?= current_url() ?>/api/slots?date=${date}`)
            .then(response => response.json())
            .then(data => {
                // Process and display the slots
                displayTimeSlots(data);
            })
            .catch(error => {
                timeSlotsContainer.innerHTML = '<div class="alert alert-danger">Error loading time slots. Please try again.</div>';
            });
    }

    function displayTimeSlots(slots) {
        // Add your slot display logic here
    }
});
</script>
<?= $this->endSection() ?>
