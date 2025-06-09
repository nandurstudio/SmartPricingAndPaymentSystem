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
    
    // Function to load time slots
    function loadTimeSlots() {
        const serviceId = serviceSelect.value;
        const date = dateInput.value;
        
        if (!serviceId || !date) {
            timeSlotSelect.innerHTML = '<option value="" selected disabled>Select date and service first</option>';
            return;
        }
          timeSlotSelect.innerHTML = '<option value="" selected disabled>Loading time slots...</option>';
        
        // Construct URL without duplicating path segments
        const baseUrl = new URL(document.baseURI);
        const apiPath = `/createapi/slots/available/${serviceId}?date=${date}`;
        fetch(`${baseUrl.origin}${apiPath}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                timeSlotSelect.innerHTML = '';
                
                if (data.error) {
                    timeSlotSelect.innerHTML = `<option value="" selected disabled>${data.error}</option>`;
                    return;
                }
                
                if (data.slots && data.slots.length > 0) {
                    const availableSlots = data.slots.filter(slot => slot.available);
                    
                    if (availableSlots.length > 0) {
                        // Add a default option
                        timeSlotSelect.innerHTML = '<option value="" selected disabled>Select a time slot</option>';
                        
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
    
    // When tenant changes, load services for that tenant
    if (tenantSelect) {
        tenantSelect.addEventListener('change', function() {
            const tenantId = this.value;
            serviceSelect.innerHTML = '<option value="" selected disabled>Loading services...</option>';
            
            fetch(`${document.baseURI}api/get-services-by-tenant/${tenantId}`)
                .then(response => response.json())
                .then(data => {
                    serviceSelect.innerHTML = '<option value="" selected disabled>Select Service</option>';
                    
                    if (data.services && data.services.length > 0) {                        data.services.forEach(service => {
                            const option = document.createElement('option');
                            option.value = service.intServiceID;
                            option.dataset.price = service.decPrice;
                            option.dataset.duration = service.intDuration;
                            option.textContent = `${service.txtName} - Rp ${parseFloat(service.decPrice).toLocaleString('id-ID', {
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
