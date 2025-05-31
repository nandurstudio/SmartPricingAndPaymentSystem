<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $pageTitle ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('booking') ?>">Bookings</a></li>
        <li class="breadcrumb-item active"><?= $pageTitle ?></li>
    </ol>
    
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-calendar-plus me-1"></i>
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

                    <form id="bookingForm" action="<?= base_url('booking/store') ?>" method="post">
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
                        <input type="hidden" name="tenant_id" value="<?= isset($tenants[0]) ? $tenants[0]['id'] : '' ?>">
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
                                                data-price="<?= $service['price'] ?>" 
                                                data-duration="<?= $service['duration'] ?>"
                                                <?= (old('service_id') == $service['id'] || (isset($_GET['service_id']) && $_GET['service_id'] == $service['id'])) ? 'selected' : '' ?>>
                                            <?= esc($service['name']) ?> - Rp <?= number_format($service['price'], 2) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="booking_date" class="form-label">Booking Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="booking_date" name="booking_date" value="<?= old('booking_date') ?? date('Y-m-d') ?>" min="<?= date('Y-m-d') ?>" required>
                            </div>
                            <div class="col-md-6" id="time-slot-wrapper">
                                <label for="time_slot" class="form-label">Time Slot <span class="text-danger">*</span></label>
                                <select class="form-select" id="time_slot" name="time_slot" required>
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
                                        <?php foreach ($customers as $customer) : ?>
                                            <option value="<?= $customer['intUserID'] ?>" <?= old('customer_id') == $customer['intUserID'] ? 'selected' : '' ?>>
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
                            <button type="submit" class="btn btn-primary">Create Booking</button>
                            <a href="<?= base_url('booking') ?>" class="btn btn-secondary">Cancel</a>
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
    const dateInput = document.getElementById('booking_date');
    const timeSlotSelect = document.getElementById('time_slot');
    const existingCustomerRadio = document.getElementById('existing_customer');
    const newCustomerRadio = document.getElementById('new_customer');
    const existingForm = document.getElementById('existing-customer-form');
    const newForm = document.getElementById('new-customer-form');
    const payLaterRadio = document.getElementById('pay_later');
    const payNowRadio = document.getElementById('pay_now');
    const paymentMethods = document.getElementById('payment-methods');
    
    // Price and duration display
    function updateServiceInfo() {
        const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
        if (selectedOption && selectedOption.value) {
            document.getElementById('service-price').textContent = 'Rp ' + 
                parseFloat(selectedOption.dataset.price).toLocaleString('id-ID', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            document.getElementById('service-duration').textContent = 
                selectedOption.dataset.duration + ' minutes';
        } else {
            document.getElementById('service-price').textContent = '-';
            document.getElementById('service-duration').textContent = '-';
        }
    }
    
    // Update booking date and time display
    function updateDateTimeInfo() {
        const date = dateInput.value;
        const timeSlot = timeSlotSelect.value;
        
        if (date) {
            const formattedDate = new Date(date).toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            document.getElementById('selected-date').textContent = formattedDate;
        } else {
            document.getElementById('selected-date').textContent = '-';
        }
        
        if (timeSlot) {
            document.getElementById('selected-time').textContent = timeSlot;
        } else {
            document.getElementById('selected-time').textContent = '-';
        }
    }
    
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
                            option.dataset.price = service.price;
                            option.dataset.duration = service.duration;
                            option.textContent = `${service.name} - Rp ${parseFloat(service.price).toLocaleString('id-ID', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            })}`;
                            serviceSelect.appendChild(option);
                        });
                    } else {
                        serviceSelect.innerHTML = '<option value="" selected disabled>No services available</option>';
                    }
                    
                    updateServiceInfo();
                })
                .catch(error => {
                    console.error('Error fetching services:', error);
                    serviceSelect.innerHTML = '<option value="" selected disabled>Error loading services</option>';
                });
        });
    }
    
    // When service or date changes, load available time slots
    function loadTimeSlots() {
        const serviceId = serviceSelect.value;
        const date = dateInput.value;
        
        if (!serviceId || !date) {
            timeSlotSelect.innerHTML = '<option value="" selected disabled>Select date and service first</option>';
            return;
        }
        
        timeSlotSelect.innerHTML = '<option value="" selected disabled>Loading time slots...</option>';
        
        fetch(`<?= base_url('api/get-available-slots') ?>/${serviceId}?date=${date}`)
            .then(response => response.json())
            .then(data => {
                timeSlotSelect.innerHTML = '';
                
                if (data.error) {
                    timeSlotSelect.innerHTML = `<option value="" selected disabled>${data.error}</option>`;
                    return;
                }
                
                if (data.slots && data.slots.length > 0) {
                    const availableSlots = data.slots.filter(slot => slot.available);
                    
                    if (availableSlots.length > 0) {
                        availableSlots.forEach(slot => {
                            const option = document.createElement('option');
                            option.value = slot.time;
                            option.textContent = `${slot.time} - ${slot.end_time}`;
                            option.dataset.endTime = slot.end_time;
                            timeSlotSelect.appendChild(option);
                        });
                    } else {
                        timeSlotSelect.innerHTML = '<option value="" selected disabled>No available slots for this date</option>';
                    }
                } else {
                    timeSlotSelect.innerHTML = '<option value="" selected disabled>No schedule available for this date</option>';
                }
                
                updateDateTimeInfo();
            })
            .catch(error => {
                console.error('Error fetching time slots:', error);
                timeSlotSelect.innerHTML = '<option value="" selected disabled>Error loading time slots</option>';
            });
    }
    
    serviceSelect.addEventListener('change', function() {
        updateServiceInfo();
        loadTimeSlots();
    });
    
    dateInput.addEventListener('change', function() {
        loadTimeSlots();
        updateDateTimeInfo();
    });
    
    timeSlotSelect.addEventListener('change', function() {
        updateDateTimeInfo();
    });
    
    // Customer form toggle
    if (existingCustomerRadio && newCustomerRadio) {
        existingCustomerRadio.addEventListener('change', function() {
            if (this.checked) {
                existingForm.style.display = 'block';
                newForm.style.display = 'none';
            }
        });
        
        newCustomerRadio.addEventListener('change', function() {
            if (this.checked) {
                existingForm.style.display = 'none';
                newForm.style.display = 'block';
            }
        });
    }
    
    // Payment method toggle
    if (payLaterRadio && payNowRadio) {
        payLaterRadio.addEventListener('change', function() {
            if (this.checked) {
                paymentMethods.style.display = 'none';
            }
        });
        
        payNowRadio.addEventListener('change', function() {
            if (this.checked) {
                paymentMethods.style.display = 'block';
            }
        });
    }
    
    // Initialize the form
    updateServiceInfo();
    updateDateTimeInfo();
    
    // If service and tenant are already selected (from URL params)
    if (serviceSelect.value) {
        loadTimeSlots();
    }
});
</script>

<style>
.payment-option .form-check-input {
    position: absolute;
    clip: rect(0,0,0,0);
}

.payment-option .form-check-label {
    cursor: pointer;
    transition: all 0.2s;
}

.payment-option .form-check-input:checked + .form-check-label {
    border-color: #0d6efd !important;
    box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.25);
}
</style>
<?= $this->endSection() ?>
