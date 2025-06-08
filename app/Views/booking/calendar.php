<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Booking Calendar</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('booking') ?>">Bookings</a></li>
        <li class="breadcrumb-item active">Calendar</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-calendar me-1"></i>
                    Booking Calendar
                </div>
                <div class="d-flex gap-2">
                    <?php if (isset($tenants) && count($tenants) > 1): ?>
                    <select class="form-select form-select-sm" id="tenant-filter">
                        <option value="">All Tenants</option>
                        <?php foreach ($tenants as $tenant): ?>
                            <option value="<?= $tenant['id'] ?>" <?= (isset($_GET['tenant_id']) && $_GET['tenant_id'] == $tenant['id']) ? 'selected' : '' ?>>
                                <?= esc($tenant['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php endif; ?>

                    <?php if (!empty($services)): ?>
                    <select class="form-select form-select-sm" id="service-filter">
                        <option value="">All Services</option>
                        <?php foreach ($services as $service): ?>
                            <option value="<?= $service['id'] ?>" <?= (isset($_GET['service_id']) && $_GET['service_id'] == $service['id']) ? 'selected' : '' ?>>
                                <?= esc($service['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php endif; ?>

                    <a href="<?= base_url('booking/create') ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus-circle"></i> New Booking
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="calendar"></div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: <?= json_encode($events ?? []) ?>,
        eventClick: function(info) {
            window.location.href = "<?= base_url('booking/view/') ?>/" + info.event.id;
        },
        dateClick: function(info) {
            let url = "<?= base_url('booking/create') ?>?date=" + info.dateStr;
            if (document.getElementById('service-filter')?.value) {
                url += "&service_id=" + document.getElementById('service-filter').value;
            }
            window.location.href = url;
        }
    });
    calendar.render();

    // Handle filter changes
    ['tenant-filter', 'service-filter'].forEach(function(filterId) {
        const filter = document.getElementById(filterId);
        if (filter) {
            filter.addEventListener('change', function() {
                let url = new URL(window.location.href);
                if (this.value) {
                    url.searchParams.set(this.id.replace('-filter', '_id'), this.value);
                } else {
                    url.searchParams.delete(this.id.replace('-filter', '_id'));
                }
                window.location.href = url.toString();
            });
        }
    });
});
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>
