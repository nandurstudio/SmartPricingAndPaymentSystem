/**
 * Schedules page JavaScript
 * Handles all schedule-related functionality including form handling,
 * list management, and special schedule operations.
 */

// Initialize all schedule-related functions when document is ready
document.addEventListener('DOMContentLoaded', function() {
    // Initialize each module if its core elements exist
    if (document.querySelector('form')) {
        initializeScheduleForm();
        initializeSpecialForm();
    }
    if (document.getElementById('apply-filters')) {
        // Check which list we're on
        if (document.querySelector('.edit-special')) {
            // We're on the special schedules page
            initializeSpecialSchedule();
        } else {
            // We're on the regular schedules page
            initializeScheduleList();
        }
    }
});

/**
 * Initialize schedule form functionality
 * Handles form validation, tenant/service selection, slot calculations,
 * and time-related operations
 * @returns {void}
 */
function initializeScheduleForm() {    // Get all required form elements
    const elements = {
        tenantSelect: document.getElementById('intTenantID'),
        serviceSelect: document.getElementById('intServiceID'),
        daySelect: document.getElementById('txtDay'),
        startTimeInput: document.getElementById('dtmStartTime'),
        endTimeInput: document.getElementById('dtmEndTime'),
        slotDurationInput: document.getElementById('intSlotDuration'),
        slotCountSpan: document.getElementById('slot-count'),
        firstSlotSpan: document.getElementById('first-slot'),
        lastSlotSpan: document.getElementById('last-slot'),
        dayNameSpan: document.getElementById('day-name'),
        form: document.querySelector('form')
    };

    // Form validation
    if (elements.form && elements.startTimeInput && elements.endTimeInput) {
        elements.form.addEventListener('submit', function(e) {
            const startTime = elements.startTimeInput.value;
            const endTime = elements.endTimeInput.value;
            
            if (startTime && endTime && startTime >= endTime) {
                e.preventDefault();
                alert('End time must be later than start time');
            }
        });
    }
    
    // When tenant changes, load services for that tenant
    if (elements.tenantSelect && elements.serviceSelect) {
        elements.tenantSelect.addEventListener('change', function() {
            const tenantId = this.value;
            elements.serviceSelect.innerHTML = '<option value="" selected disabled>Loading services...</option>';
            
            fetch(`${baseUrl}/api/get-services-by-tenant/${tenantId}`)
                .then(response => response.json())
                .then(data => {
                    elements.serviceSelect.innerHTML = '<option value="" selected disabled>Select Service</option>';
                    
                    if (data.services && data.services.length > 0) {
                        data.services.forEach(service => {
                            const option = document.createElement('option');
                            option.value = service.id;
                            option.dataset.slotDuration = service.duration;
                            option.textContent = service.name;
                            elements.serviceSelect.appendChild(option);
                        });
                    } else {
                        elements.serviceSelect.innerHTML = '<option value="" selected disabled>No services available</option>';
                    }
                    
                    updateSlotDuration();
                    calculateSlots();
                })
                .catch(error => {
                    console.error('Error fetching services:', error);
                    elements.serviceSelect.innerHTML = '<option value="" selected disabled>Error loading services</option>';
                });
        });
    }
    
    // When service changes, update the slot duration
    if (elements.serviceSelect) {
        elements.serviceSelect.addEventListener('change', () => updateSlotDuration(elements));
    }
    
    // Event listeners for slot calculations
    if (elements.startTimeInput) elements.startTimeInput.addEventListener('change', () => calculateSlots(elements));
    if (elements.endTimeInput) elements.endTimeInput.addEventListener('change', () => calculateSlots(elements));
    if (elements.slotDurationInput) elements.slotDurationInput.addEventListener('input', () => calculateSlots(elements));
    if (elements.daySelect) elements.daySelect.addEventListener('change', () => calculateSlots(elements));
    
    // Initial calculations
    calculateSlots(elements);
    
    function updateSlotDuration(elements) {
        if (!elements.serviceSelect || !elements.slotDurationInput) return;
        
        const selectedOption = elements.serviceSelect.options[elements.serviceSelect.selectedIndex];
        if (selectedOption?.dataset.slotDuration) {
            elements.slotDurationInput.value = selectedOption.dataset.slotDuration;
        }
        calculateSlots(elements);
    }
    
    function calculateSlots(elements) {
        const {
            startTimeInput,
            endTimeInput,
            slotDurationInput,
            slotCountSpan,
            firstSlotSpan,
            lastSlotSpan,
            dayNameSpan,
            daySelect
        } = elements;

        // Early return if essential elements are missing
        if (!startTimeInput || !endTimeInput || !slotDurationInput) {
            return;
        }

        const startTime = startTimeInput.value;
        const endTime = endTimeInput.value;
        const slotDuration = parseInt(slotDurationInput.value);
        const day = daySelect?.value || '';
        
        // Update day name if element exists
        if (dayNameSpan) {
            dayNameSpan.textContent = day || 'day';
        }

        // Helper function to update display elements
        const updateDisplay = (slots, firstSlot, lastSlot) => {
            if (slotCountSpan) slotCountSpan.textContent = slots;
            if (firstSlotSpan) firstSlotSpan.textContent = firstSlot;
            if (lastSlotSpan) lastSlotSpan.textContent = lastSlot;
        };

        if (!startTime || !endTime || !slotDuration || slotDuration <= 0) {
            updateDisplay('0', '-', '-');
            return;
        }

        const start = new Date(`2000-01-01T${startTime}`);
        const end = new Date(`2000-01-01T${endTime}`);
        
        if (start >= end) {
            updateDisplay('0', '-', '-');
            return;
        }

        const diffMs = end - start;
        const diffMinutes = Math.floor(diffMs / 60000);
        const slots = Math.floor(diffMinutes / slotDuration);

        const firstSlotStart = new Date(start);
        const firstSlotEnd = new Date(firstSlotStart);
        firstSlotEnd.setMinutes(firstSlotEnd.getMinutes() + slotDuration);

        const lastSlotStart = new Date(start);
        lastSlotStart.setMinutes(lastSlotStart.getMinutes() + (slots - 1) * slotDuration);
        const lastSlotEnd = new Date(lastSlotStart);
        lastSlotEnd.setMinutes(lastSlotEnd.getMinutes() + slotDuration);

        updateDisplay(
            slots.toString(),
            `${formatTime(firstSlotStart)} - ${formatTime(firstSlotEnd)}`,
            `${formatTime(lastSlotStart)} - ${formatTime(lastSlotEnd)}`
        );
    }
}

/**
 * Initialize schedule list functionality
 * Handles filter operations, delete confirmations, and URL parameter processing
 * @returns {void}
 */
function initializeScheduleList() {
    const elements = {
        serviceFilter: document.getElementById('service-filter'),
        applyFilters: document.getElementById('apply-filters'),
        deleteModal: document.getElementById('deleteScheduleModal'),
        scheduleIdInput: document.getElementById('schedule_id'),
        scheduleInfoSpan: document.getElementById('schedule-info')
    };

    // Filter functionality
    if (elements.applyFilters) {
        elements.applyFilters.addEventListener('click', function() {
            const serviceFilter = elements.serviceFilter?.value || '';
            let url = `${baseUrl}/schedules?`;
            if (serviceFilter) url += `service_id=${serviceFilter}`;
            window.location.href = url;
        });
    }

    // Auto-select service if service_id is in URL
    const urlParams = new URLSearchParams(window.location.search);
    const serviceId = urlParams.get('service_id');
    if (serviceId && elements.serviceFilter) {
        const option = Array.from(elements.serviceFilter.options).find(opt => opt.value === serviceId);
        if (option) {
            option.selected = true;
        }
    }

    // Delete schedule confirmation
    if (elements.deleteModal) {
        const modal = new bootstrap.Modal(elements.deleteModal);
        document.querySelectorAll('.delete-schedule').forEach(button => {
            button.addEventListener('click', function() {
                const scheduleId = this.getAttribute('data-id');
                const day = this.getAttribute('data-day');
                const serviceName = this.getAttribute('data-service');
                
                elements.scheduleIdInput.value = scheduleId;
                elements.scheduleInfoSpan.textContent = `${serviceName} on ${day}`;
                
                modal.show();
            });
        });
    }
}

/**
 * Initialize special schedule functionality
 * Handles the special date management including add, edit, and delete operations
 * @returns {void} 
 */
function initializeSpecialSchedule() {
    const elements = {
        serviceFilter: document.getElementById('service-filter'),
        monthFilter: document.getElementById('month-filter'),
        applyFilters: document.getElementById('apply-filters'),
        editModal: document.getElementById('editSpecialDateModal'),
        deleteModal: document.getElementById('deleteSpecialModal'),
    };

    // Filter functionality
    if (elements.applyFilters) {
        elements.applyFilters.addEventListener('click', function() {
            const serviceId = elements.serviceFilter?.value || '';
            const month = elements.monthFilter?.value || '';
            
            let url = `${baseUrl}/schedules/special?`;
            if (serviceId) url += `service_id=${serviceId}`;
            if (month) url += `${serviceId ? '&' : ''}month=${month}`;
            
            window.location.href = url;
        });
    }
    
    // Edit special date
    document.querySelectorAll('.edit-special').forEach(btn => {
        btn.addEventListener('click', function() {
            const data = this.dataset;
            const modal = elements.editModal;
            if (!modal) return;

            // Populate modal fields
            modal.querySelector('#edit_id').value = data.id;
            modal.querySelector('#edit_service').value = data.service;
            modal.querySelector('#edit_service_name').value = data.serviceName;
            modal.querySelector('#edit_date').value = data.date;
            modal.querySelector('#edit_is_closed').value = data.isClosed;

            if (data.isClosed === '0') {
                modal.querySelector('#edit_start_time').value = data.start;
                modal.querySelector('#edit_end_time').value = data.end;
                modal.querySelector('#edit_times_container').style.display = 'block';
            } else {
                modal.querySelector('#edit_times_container').style.display = 'none';
            }
            
            modal.querySelector('#edit_notes').value = data.notes;

            // Show modal
            const modalInstance = new bootstrap.Modal(modal);
            modalInstance.show();
        });
    });
    
    // Delete confirmation
    document.querySelectorAll('.delete-special').forEach(btn => {
        btn.addEventListener('click', function() {
            const data = this.dataset;
            const modal = elements.deleteModal;
            if (!modal) return;

            modal.querySelector('#delete_id').value = data.id;
            modal.querySelector('#delete_date_text').textContent = data.date;
            modal.querySelector('#delete_service_text').textContent = data.serviceName;

            const modalInstance = new bootstrap.Modal(modal);
            modalInstance.show();
        });
    });

    // Toggle times container based on is_closed value
    document.querySelectorAll('[name="is_closed"]').forEach(input => {
        input.addEventListener('change', function() {
            const timesContainer = this.closest('form').querySelector('.times-container');
            if (!timesContainer) return;
            
            timesContainer.style.display = this.value === '0' ? 'block' : 'none';
            
            const timeInputs = timesContainer.querySelectorAll('input');
            timeInputs.forEach(input => {
                input.required = this.value === '0';
            });
        });
    });
}

/**
 * Initialize special date form functionality
 * Handles the special date form including auto-selecting service and toggle operating hours
 * @returns {void}
 */
function initializeSpecialForm() {
    const elements = {
        serviceFilter: document.getElementById('service-filter'),
        specialServiceSelect: document.getElementById('special_service_id'),
        isClosedCheckbox: document.getElementById('is_closed'),
        editIsClosedCheckbox: document.getElementById('edit_is_closed'),
        operatingHoursDiv: document.getElementById('operating-hours-div'),
        editOperatingHoursDiv: document.getElementById('edit-operating-hours-div')
    };

    // Auto-select service if service_id is in URL
    const urlParams = new URLSearchParams(window.location.search);
    const serviceId = urlParams.get('service_id');
    if (serviceId) {
        [elements.serviceFilter, elements.specialServiceSelect].forEach(select => {
            if (select) {
                const option = Array.from(select.options).find(opt => opt.value === serviceId);
                if (option) {
                    option.selected = true;
                }
            }
        });
    }

    // Handle closed checkbox functionality
    const toggleOperatingHours = (isClosedElement, hoursDiv) => {
        if (!isClosedElement || !hoursDiv) return;
        
        isClosedElement.addEventListener('change', function() {
            hoursDiv.style.display = this.checked ? 'none' : 'block';
            const timeInputs = hoursDiv.querySelectorAll('input[type="time"]');
            timeInputs.forEach(input => {
                input.required = !this.checked;
            });
        });
    };

    toggleOperatingHours(elements.isClosedCheckbox, elements.operatingHoursDiv);
    toggleOperatingHours(elements.editIsClosedCheckbox, elements.editOperatingHoursDiv);
}

/**
 * Format time to 12-hour format with AM/PM
 * @param {Date} date - The date object to format
 * @returns {string} The formatted time string in HH:MM AM/PM format
 */
function formatTime(date) {
    let hours = date.getHours();
    const minutes = date.getMinutes().toString().padStart(2, '0');
    const ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    return `${hours}:${minutes} ${ampm}`;
}
