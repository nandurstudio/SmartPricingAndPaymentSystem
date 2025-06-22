// Service Types page specific JavaScript
$(document).ready(function() {
    // Form validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    // Description character counter
    const txtDescription = document.getElementById('txtDescription');
    const charCounter = document.getElementById('descriptionCharCounter');
    if (txtDescription && charCounter) {
        const updateCounter = () => {
            const remaining = 255 - txtDescription.value.length;
            charCounter.textContent = `${remaining} characters remaining`;
            charCounter.classList.toggle('text-danger', remaining < 50);
        };
        txtDescription.addEventListener('input', updateCounter);
        updateCounter(); // Initial count
    }

    // Status toggle helper
    const bitActive = document.getElementById('bitActive');
    const statusLabel = document.getElementById('statusLabel');
    if (bitActive && statusLabel) {
        const updateLabel = () => {
            statusLabel.textContent = bitActive.checked ? 'Active' : 'Inactive';
            statusLabel.className = `badge bg-${bitActive.checked ? 'success' : 'danger'} ms-2`;
        };
        bitActive.addEventListener('change', updateLabel);
        updateLabel(); // Initial state
    }

    // Initialize any tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
