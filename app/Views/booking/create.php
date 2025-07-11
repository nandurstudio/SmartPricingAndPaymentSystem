<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Add CSS link at the top -->
<link rel="stylesheet" href="<?= base_url('assets/css/booking/booking.css') ?>">

<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $pageTitle ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('bookings') ?>">Bookings</a></li>
        <li class="breadcrumb-item active"><?= $pageTitle ?></li>
    </ol>
    
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-calendar-plus me-1"></i>
                    <?= $pageTitle ?>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('errors')) : ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h5>Error:</h5>
                            <ul>
                                <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (session()->getFlashdata('warning')) : ?>
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <?= session()->getFlashdata('warning') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>                    
                    
                    <form id="bookingForm" action="<?= base_url('bookings/store') ?>" method="post">
                        <?= csrf_field() ?>

                        <?php 
                        $isOnTenantSubdomain = strpos($_SERVER['HTTP_HOST'] ?? '', '.') !== false;
                        $roleId = session()->get('roleID');
                        
                        // Show tenant dropdown only if:
                        // 1. We're not on a tenant subdomain
                        // 2. There are multiple tenants available
                        // 3. User is an admin
                        if (isset($tenants) && count($tenants) > 1 && !$isOnTenantSubdomain && $roleId == 1):
                        ?>
                        <div class="mb-3">
                            <label for="tenant_id" class="form-label">Tenant <span class="text-danger">*</span></label>
                            <select class="form-select" id="tenant_id" name="tenant_id" required>
                                <option value="" selected disabled>Select Tenant</option>
                                <?php foreach ($tenants as $tenant) : ?>
                                    <option value="<?= $tenant['intTenantID'] ?>" 
                                            <?= (old('tenant_id') == $tenant['intTenantID'] || 
                                                (isset($_GET['tenant_id']) && $_GET['tenant_id'] == $tenant['intTenantID'])) ? 'selected' : '' ?>>
                                        <?= esc($tenant['txtTenantName']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>                        <?php else: 
                            $tenantId = '';
                            if (isset($tenants) && !empty($tenants)) {
                                if (isset($tenants[0]['intTenantID'])) {
                                    $tenantId = $tenants[0]['intTenantID'];
                                } elseif (isset($tenants[0]->intTenantID)) {
                                    $tenantId = $tenants[0]->intTenantID;
                                }
                            }
                        ?>
                        <input type="hidden" name="tenant_id" value="<?= $tenantId ?>">
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label for="service_id" class="form-label">Service <span class="text-danger">*</span></label>
                            <select class="form-select" id="service_id" name="service_id" required>
                                <option value="" selected disabled>Select a service</option>
                                <?php if (isset($services) && is_array($services)) : ?>
                                    <?php foreach ($services as $service) : ?>
                                        <option 
                                            value="<?= $service['intServiceID'] ?>"
                                            data-price="<?= $service['decPrice'] ?>"
                                            data-duration="<?= $service['intDuration'] ?>"
                                            <?= (old('service_id') == $service['intServiceID'] || 
                                                (isset($_GET['service_id']) && $_GET['service_id'] == $service['intServiceID'])) ? 'selected' : '' ?>>
                                            <?= esc($service['txtName']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <div class="form-text">
                                Please select a service to see available time slots and pricing.
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="booking_date" class="form-label">Booking Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="booking_date" name="booking_date" value="<?= old('booking_date') ?? date('Y-m-d') ?>" min="<?= date('Y-m-d') ?>" required>
                            </div>                            <div class="col-md-6" id="time-slot-wrapper">
                                <label for="start_time" class="form-label">Time Slot <span class="text-danger">*</span></label>
                                <select class="form-select" id="time_slot" name="start_time" required>
                                    <option value="" selected disabled>Select date and service first</option>
                                </select>
                                <div class="form-text">Please select a date and service to see available time slots.</div>
                            </div>
                        </div>
                        
                        <div class="card bg-light mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Price:</strong> <span id="service-price">-</span></p>
                                        <p class="mb-1"><strong>Duration:</strong> <span id="service-duration">-</span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Date:</strong> <span id="selected-date">-</span></p>
                                        <p class="mb-1"><strong>Time:</strong> <span id="selected-time">-</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <h5 class="mt-4 mb-3">Customer Information</h5>
                        
                        <?php if (session()->get('roleID') > 1) : ?>
                            <!-- For customers booking for themselves -->
                            <input type="hidden" name="customer_id" value="<?= session()->get('userID') ?>">
                        <?php else : ?>
                            <!-- For admin/staff creating bookings -->
                            <div class="mb-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="customer_type" id="existing_customer" value="existing" checked>
                                    <label class="form-check-label" for="existing_customer">Existing Customer</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="customer_type" id="new_customer" value="new">
                                    <label class="form-check-label" for="new_customer">New Customer</label>
                                </div>
                            </div>
                            
                            <div id="existing-customer-form">
                                <div class="mb-3">
                                    <label for="customer_id" class="form-label">Select Customer <span class="text-danger">*</span></label>
                                    <select class="form-select" id="customer_id" name="customer_id">
                                        <option value="" selected disabled>Select a customer</option>
                                        <?php foreach ($customers as $customer) : ?>                                            <option value="<?= $customer['intCustomerID'] ?>" <?= old('customer_id') == $customer['intCustomerID'] ? 'selected' : '' ?>>
                                                <?= esc($customer['txtFullName']) ?> (<?= esc($customer['txtEmail']) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div id="new-customer-form" style="display: none;">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="customer_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="customer_name" name="customer_name" value="<?= old('customer_name') ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="customer_email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="customer_email" name="customer_email" value="<?= old('customer_email') ?>">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="customer_phone" class="form-label">Phone <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="customer_phone" name="customer_phone" value="<?= old('customer_phone') ?>">
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Special Requests or Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"><?= old('notes') ?></textarea>
                        </div>
                        
                        <h5 class="mt-4 mb-3">Payment Information</h5>
                        <div class="mb-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="payment_method" id="pay_later" value="pay_later" checked>
                                <label class="form-check-label" for="pay_later">Pay Later</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="payment_method" id="pay_now" value="pay_now">
                                <label class="form-check-label" for="pay_now">Pay Now</label>
                            </div>
                        </div>
                        
                        <div class="mb-3" id="payment-methods" style="display: none;">
                            <label class="form-label">Select Payment Method</label>
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <div class="form-check payment-option">
                                        <input class="form-check-input" type="radio" name="payment_type" id="payment_bank" value="bank_transfer">
                                        <label class="form-check-label d-block border rounded p-3 text-center" for="payment_bank">
                                            <i class="fas fa-university fa-2x mb-2"></i><br>
                                            Bank Transfer
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <div class="form-check payment-option">
                                        <input class="form-check-input" type="radio" name="payment_type" id="payment_cc" value="credit_card">
                                        <label class="form-check-label d-block border rounded p-3 text-center" for="payment_cc">
                                            <i class="fas fa-credit-card fa-2x mb-2"></i><br>
                                            Credit Card
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <div class="form-check payment-option">
                                        <input class="form-check-input" type="radio" name="payment_type" id="payment_wallet" value="e_wallet">
                                        <label class="form-check-label d-block border rounded p-3 text-center" for="payment_wallet">
                                            <i class="fas fa-wallet fa-2x mb-2"></i><br>
                                            E-Wallet
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>Save Booking
                            </button>
                            <a href="<?= base_url('bookings') ?>" class="btn btn-secondary me-2">
                                <i class="bi bi-x-circle me-1"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add script tag at the bottom -->
<script src="<?= base_url('assets/js/booking/booking.js') ?>"></script>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    let serviceData = {};

    // Function to load time slots
    function loadTimeSlots() {
        const serviceId = $('#service_id').val();
        const date = $('#booking_date').val();
        const timeSlotSelect = $('#time_slot');
        
        if (!serviceId || !date) {
            timeSlotSelect.html('<option value="" selected disabled>Select date and service first</option>');
            return;
        }

        // Show loading
        timeSlotSelect.html('<option value="" selected disabled>Loading time slots...</option>');
        
        // Make API call to get available slots
        $.get(`${baseUrl}/api/slots/available/${serviceId}`, { date: date })
            .done(function(response) {
                if (response.status === 'success' && response.data) {
                    let slots = response.data;
                    if (slots.length > 0) {
                        timeSlotSelect.html('<option value="" selected disabled>Select a time slot</option>');
                        slots.forEach(slot => {
                            timeSlotSelect.append(`<option value="${slot.time}">${slot.formatted_time}</option>`);
                        });
                    } else {
                        timeSlotSelect.html('<option value="" selected disabled>No slots available</option>');
                    }
                }
            })
            .fail(function(xhr) {
                console.error('Error loading time slots:', xhr);
                timeSlotSelect.html('<option value="" selected disabled>Error loading time slots</option>');
            });
    }

    // Load service details when service is selected
    $('#service_id').change(function() {
        const serviceId = $(this).val();
        if (serviceId) {
            // Get service details from the selected option's data attributes
            const selectedOption = $(this).find(':selected');
            serviceData = {
                price: selectedOption.data('price'),
                duration: selectedOption.data('duration'),
                name: selectedOption.text()
            };
            
            // Update display
            $('#service-price').text(formatPrice(serviceData.price));
            $('#service-duration').text(formatDuration(serviceData.duration));
            
            // Load time slots
            loadTimeSlots();
        }
    });

    // Reload time slots when date changes
    $('#booking_date').change(loadTimeSlots);

    // Update selected time display when time slot changes
    $('#time_slot').change(function() {
        const selectedTime = $(this).find(':selected').text();
        $('#selected-time').text(selectedTime !== 'Select a time slot' ? selectedTime : '-');
    });

    // Update selected date display when date changes
    $('#booking_date').change(function() {
        const selectedDate = $(this).val();
        $('#selected-date').text(selectedDate ? formatDate(selectedDate) : '-');
    });

    // Helper function to format price
    function formatPrice(price) {
        return new Intl.NumberFormat('id-ID', { 
            style: 'currency', 
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(price);
    }

    // Helper function to format duration
    function formatDuration(minutes) {
        if (minutes >= 60) {
            const hours = Math.floor(minutes / 60);
            const mins = minutes % 60;
            return `${hours} hour${hours > 1 ? 's' : ''} ${mins > 0 ? `${mins} min${mins > 1 ? 's' : ''}` : ''}`;
        }
        return `${minutes} min${minutes > 1 ? 's' : ''}`;
    }

    // Helper function to format date
    function formatDate(dateStr) {
        return new Date(dateStr).toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }
});
</script>
<?= $this->endSection() ?>
