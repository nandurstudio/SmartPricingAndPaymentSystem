$(document).ready(function() {
    // When a service is selected
    $('#service_id').change(function() {
        const serviceData = {
            price: $(this).find(':selected').data('price'),
            duration: $(this).find(':selected').data('duration')
        };
        
        // Update display
        $('#service-price').text(formatPrice(serviceData.price));
        $('#service-duration').text(formatDuration(serviceData.duration));
        
        // Load time slots
        loadTimeSlots();
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
