/**
 * Schedules page JavaScript
 * Handles all schedule-related functionality including form handling,
 * list management, and special schedule operations.
 */

// --- SCHEDULE FORM: jQuery version for create/edit (_form.php) ---
$(function() {
    var form = $('form');
    if (form.length && $('#dtmStartTime').length && $('#dtmEndTime').length) {
        form.on('submit', function(e) {
            var start = $('#dtmStartTime').val();
            var end = $('#dtmEndTime').val();
            if (start && end && start >= end) {
                e.preventDefault();
                alert('End time must be later than start time');
            }
        });
    }
    // Tenant change: load services
    $('#intTenantID').on('change', function() {
        var tenantId = $(this).val();
        var serviceSelect = $('#intServiceID');
        serviceSelect.html('<option value="" selected disabled>Loading services...</option>');
        $.get(baseUrl + '/api/get-services-by-tenant/' + tenantId, function(data) {
            serviceSelect.html('<option value="" selected disabled>Select Service</option>');
            if (data.services && data.services.length > 0) {
                $.each(data.services, function(_, service) {
                    serviceSelect.append(
                        $('<option>', {
                            value: service.id,
                            text: service.name,
                            'data-slot-duration': service.duration
                        })
                    );
                });
            } else {
                serviceSelect.html('<option value="" selected disabled>No services available</option>');
            }
            updateSlotDuration();
            calculateSlots();
        }, 'json').fail(function() {
            serviceSelect.html('<option value="" selected disabled>Error loading services</option>');
        });
    });
    // Service change: update slot duration
    $('#intServiceID').on('change', function() {
        updateSlotDuration();
    });
    // Slot calculation triggers
    $('#dtmStartTime,#dtmEndTime,#intSlotDuration,#txtDay').on('input change', function() {
        calculateSlots();
    });
    // Availability toggle
    $('#bitIsAvailable').on('change', function() {
        $('#avail-label').text(this.checked ? 'Available' : 'Not Available');
        $(this).val(this.checked ? 1 : 0);
    });
    // Initial calculation
    calculateSlots();
    // --- helpers ---
    function updateSlotDuration() {
        var sel = $('#intServiceID option:selected');
        if (sel.data('slot-duration')) {
            $('#intSlotDuration').val(sel.data('slot-duration'));
        }
        calculateSlots();
    }
    function calculateSlots() {
        var start = $('#dtmStartTime').val();
        var end = $('#dtmEndTime').val();
        var dur = parseInt($('#intSlotDuration').val());
        var day = $('#txtDay').val() || '';
        if ($('#day-name').length) $('#day-name').text(day || 'day');
        var slotCount = 0, firstSlot = '-', lastSlot = '-';
        if (start && end && dur > 0) {
            var sh = parseInt(start.split(':')[0]), sm = parseInt(start.split(':')[1]);
            var eh = parseInt(end.split(':')[0]), em = parseInt(end.split(':')[1]);
            var startMin = sh * 60 + sm;
            var endMin = eh * 60 + em;
            if (endMin > startMin) {
                slotCount = Math.floor((endMin - startMin) / dur);
                if (slotCount > 0) {
                    firstSlot = start;
                    var lastStart = startMin + (slotCount - 1) * dur;
                    var lh = String(Math.floor(lastStart / 60)).padStart(2, '0');
                    var lm = String(lastStart % 60).padStart(2, '0');
                    lastSlot = lh + ':' + lm;
                }
            }
        }
        $('#slot-count').text(slotCount);
        $('#first-slot').text(firstSlot);
        $('#last-slot').text(lastSlot);
    }
});

// --- SCHEDULE LIST (index.php) ---
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('apply-filters')) {
        if (document.querySelector('.edit-special')) {
            initializeSpecialSchedule();
        } else {
            initializeScheduleList();
        }
    }
});

function initializeScheduleList() {
    const elements = {
        serviceFilter: document.getElementById('service-filter'),
        applyFilters: document.getElementById('apply-filters'),
        deleteModal: document.getElementById('deleteScheduleModal'),
        scheduleIdInput: document.getElementById('schedule_id'),
        scheduleInfoSpan: document.getElementById('schedule-info')
    };
    if (elements.applyFilters) {
        elements.applyFilters.addEventListener('click', function() {
            const serviceFilter = elements.serviceFilter?.value || '';
            let url = `${baseUrl}/schedules?`;
            if (serviceFilter) url += `service_id=${serviceFilter}`;
            window.location.href = url;
        });
    }
    const urlParams = new URLSearchParams(window.location.search);
    const serviceId = urlParams.get('service_id');
    if (serviceId && elements.serviceFilter) {
        const option = Array.from(elements.serviceFilter.options).find(opt => opt.value === serviceId);
        if (option) {
            option.selected = true;
        }
    }
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

// (All special schedule related code has been moved to schedules_special.js)

// --- Utility: Format time to 12-hour format with AM/PM ---
function formatTime(date) {
    let hours = date.getHours();
    const minutes = date.getMinutes().toString().padStart(2, '0');
    const ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    return `${hours}:${minutes} ${ampm}`;
}

$(function() {
    // Fix: Remove focus from any element inside modal before it is hidden to avoid aria-hidden warning
    $('#addSpecialDateModal, #editSpecialDateModal, #deleteSpecialModal').on('hide.bs.modal', function () {
        // If focus is inside this modal, blur it
        if (this.contains(document.activeElement)) {
            document.activeElement.blur();
        }
    });
});
