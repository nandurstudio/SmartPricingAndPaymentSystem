// --- SPECIAL SCHEDULES (special.php) ---
function initializeSpecialSchedule() {
    const elements = {
        serviceFilter: document.getElementById('service-filter'),
        monthFilter: document.getElementById('month-filter'),
        applyFilters: document.getElementById('apply-filters'),
        editModal: document.getElementById('editSpecialDateModal'),
        deleteModal: document.getElementById('deleteSpecialModal'),
    };
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
    document.querySelectorAll('.edit-special').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            // Redirect to set ?edit_id in URL, which will trigger PHP to load data from DB
            const url = new URL(window.location.href);
            url.searchParams.set('edit_id', id);
            window.location.href = url.toString();
        });
    });
    document.querySelectorAll('.delete-special').forEach(btn => {
        btn.addEventListener('click', function() {
            const data = this.dataset;
            const modal = elements.deleteModal;
            if (!modal) return;
            modal.querySelector('#delete_special_id').value = data.id;
            modal.querySelector('#delete-special-info').textContent = `${data.serviceName} (${data.date})`;
            const modalInstance = new bootstrap.Modal(modal);
            modalInstance.show();
        });
    });
}

// --- SPECIAL FORM (special.php modal) ---
function initializeSpecialForm() {
    const elements = {
        specialServiceSelect: document.getElementById('special_service_id'),
        isClosedCheckbox: document.getElementById('is_closed'),
        operatingHoursDiv: document.getElementById('operating-hours-div'),
        closedAllDayLabel: document.getElementById('closed-all-day-label')
    };
    const urlParams = new URLSearchParams(window.location.search);
    const serviceId = urlParams.get('service_id');
    // Auto-select service if only one or if service_id param exists
    if (elements.specialServiceSelect) {
        if (elements.specialServiceSelect.options.length === 2) {
            elements.specialServiceSelect.selectedIndex = 1;
            elements.specialServiceSelect.disabled = true;
        } else if (serviceId) {
            const option = Array.from(elements.specialServiceSelect.options).find(opt => opt.value === serviceId);
            if (option) {
                option.selected = true;
            }
        }
    }
    // Interactive: Closed on this date
    function toggleOperatingHours(isClosedElement, hoursDiv, closedLabel) {
        if (!isClosedElement || !hoursDiv) return;
        const timeInputs = hoursDiv.querySelectorAll('input[type="time"]');
        const defaultValues = Array.from(timeInputs).map(input => input.value);
        function update() {
            if (isClosedElement.checked) {
                hoursDiv.classList.add('disabled');
                if (timeInputs[0]) timeInputs[0].value = '00:00';
                if (timeInputs[1]) timeInputs[1].value = '23:59';
                timeInputs.forEach(input => {
                    input.disabled = true;
                    input.required = false;
                    input.classList.add('bg-light');
                });
                if (closedLabel) closedLabel.style.display = '';
            } else {
                hoursDiv.classList.remove('disabled');
                timeInputs.forEach((input, idx) => {
                    input.value = defaultValues[idx] || '';
                    input.disabled = false;
                    input.required = true;
                    input.classList.remove('bg-light');
                });
                if (closedLabel) closedLabel.style.display = 'none';
            }
        }
        isClosedElement.addEventListener('change', update);
        update();
    }
    toggleOperatingHours(elements.isClosedCheckbox, elements.operatingHoursDiv, elements.closedAllDayLabel);
}

$(function() {
    initializeSpecialSchedule();
    initializeSpecialForm();
    // Fallback: if service select is empty and service_id in URL, fetch service name from API
    var select = document.getElementById('special_service_id');
    if (select && select.options.length <= 1) {
        var params = new URLSearchParams(window.location.search);
        var serviceId = params.get('service_id');
        if (serviceId) {
            fetch(baseUrl + 'api/service-name?id=' + encodeURIComponent(serviceId))
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        var opt = document.createElement('option');
                        opt.value = data.id;
                        opt.selected = true;
                        opt.textContent = data.name;
                        select.appendChild(opt);
                        select.value = data.id;
                    } else {
                        select.innerHTML = '<option value="" selected disabled>Service not found</option>';
                    }
                })
                .catch(() => {
                    select.innerHTML = '<option value="" selected disabled>Error loading service</option>';
                });
        }
    }
    // --- Ensure intServiceID and time fields are always posted (for disabled select/readonly time fields) ---
    var form = $('#addSpecialDateModal form');
    var select = $('#special_service_id');
    var hidden = $('#hidden_special_service_id');
    var dateInput = $('#special_date');
    if(form.length && select.length && hidden.length) {
        form.on('submit', function() {
            if(select.prop('disabled')) {
                hidden.val(select.val());
            } else {
                hidden.val('');
            }
            // Guarantee time fields are always posted
            var $start = $('#special_start_time');
            var $end = $('#special_end_time');
            if ($start.length && !$start.prop('disabled') && !$start.val()) {
                $start.val('09:00');
            }
            if ($end.length && !$end.prop('disabled') && !$end.val()) {
                $end.val('17:00');
            }
        });
    }
    // --- Accessibility: Blur focus on modal close to avoid aria-hidden warning ---
    $('#addSpecialDateModal, #editSpecialDateModal, #deleteSpecialModal').on('hide.bs.modal', function () {
        // If focus is inside this modal, blur it
        if (this.contains(document.activeElement)) {
            document.activeElement.blur();
        }
    });
    // --- Closed on this date logic (Add Special Date Modal) ---
    let prevStartTime = '';
    let prevEndTime = '';
    function toggleSpecialTimeFields(isClosed) {
        const $start = $('#special_start_time');
        const $end = $('#special_end_time');
        if (isClosed) {
            // Store previous values (not used for restore)
            prevStartTime = $start.val();
            prevEndTime = $end.val();
            $start.prop('readonly', false).prop('disabled', false).val('00:00');
            $end.prop('readonly', false).prop('disabled', false).val('23:59');
            $('#closed-all-day-label').show();
        } else {
            $start.prop('readonly', false).prop('disabled', false).val('09:00');
            $end.prop('readonly', false).prop('disabled', false).val('17:00');
            $('#closed-all-day-label').hide();
        }
    }
    $('#is_closed').on('change', function () {
        toggleSpecialTimeFields(this.checked);
    });
    $('#addSpecialDateModal').on('show.bs.modal', function () {
        $('#special_date').val('');
        $('#is_closed').prop('checked', false);
        prevStartTime = '09:00';
        prevEndTime = '17:00';
        $('#special_start_time').val('09:00').prop('readonly', false).prop('disabled', false);
        $('#special_end_time').val('17:00').prop('readonly', false).prop('disabled', false);
        $('#closed-all-day-label').hide();
    });
    // --- Closed on this date logic (Edit Special Date Modal) ---
    function toggleEditTimeFields(isClosed) {
        const $start = $('#edit_special_start_time');
        const $end = $('#edit_special_end_time');
        const $hoursDiv = $('#edit-operating-hours-div');
        const $label = $('#edit-closed-all-day-label');
        if (isClosed) {
            $start.prop('readonly', false).prop('disabled', false).val('00:00');
            $end.prop('readonly', false).prop('disabled', false).val('23:59');
            $hoursDiv.addClass('disabled');
            $label.show();
        } else {
            $start.prop('readonly', false).prop('disabled', false).val('09:00');
            $end.prop('readonly', false).prop('disabled', false).val('17:00');
            $hoursDiv.removeClass('disabled');
            $label.hide();
        }
    }
    $('#edit_is_closed').on('change', function () {
        toggleEditTimeFields(this.checked);
    });
    $('#editSpecialDateModal').on('show.bs.modal', function () {
        $('#edit_is_closed').trigger('change');
    });
    // Auto-open edit modal if ?edit_id is present and modal exists
    const urlParams = new URLSearchParams(window.location.search);
    const editId = urlParams.get('edit_id');
    if (editId && $('#editSpecialDateModal').length) {
        const modal = new bootstrap.Modal(document.getElementById('editSpecialDateModal'));
        modal.show();
    }
});
