// booking-view.js
// JS logic for booking/view.php, extracted for maintainability

document.addEventListener('DOMContentLoaded', function() {
    // Status update functionality
    const updateStatusModal = new bootstrap.Modal(document.getElementById('updateStatusModal'));
    const updateStatusButtons = document.querySelectorAll('.update-status');
    
    updateStatusButtons.forEach(button => {
        button.addEventListener('click', function() {
            const bookingId = this.getAttribute('data-id');
            const status = this.getAttribute('data-status');
            
            document.getElementById('booking_id').value = bookingId;
            document.getElementById('status').value = status;
            
            let statusText = 'pending';
            if (status === 'confirmed') statusText = 'confirm';
            else if (status === 'completed') statusText = 'mark as completed';
            else if (status === 'cancelled') statusText = 'cancel';
            
            document.getElementById('status-message').textContent = `Are you sure you want to ${statusText} this booking?`;
            
            // Show cancellation reason field only for cancel status
            if (status === 'cancelled') {
                document.getElementById('cancellation-reason-container').style.display = 'block';
            } else {
                document.getElementById('cancellation-reason-container').style.display = 'none';
            }
            
            updateStatusModal.show();
        });
    });
    
    // Payment confirmation functionality
    const confirmPaymentModal = new bootstrap.Modal(document.getElementById('confirmPaymentModal'));
    const confirmPaymentButtons = document.querySelectorAll('.confirm-payment');
    
    confirmPaymentButtons.forEach(button => {
        button.addEventListener('click', function() {
            confirmPaymentModal.show();
        });
    });
    
    // Refund functionality
    const refundModal = new bootstrap.Modal(document.getElementById('refundModal'));
    const refundButtons = document.querySelectorAll('.initiate-refund');
    refundButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Load refund form via AJAX ke /bookings/refund/{id} dan tampilkan di modal
            const bookingId = this.getAttribute('data-id');
            fetch(BASE_URL + 'bookings/refund/' + bookingId)
                .then(response => response.text())
                .then(html => {
                    document.querySelector('#refundModal .modal-content').innerHTML = html;
                    // Attach submit event to the loaded form to prevent default and submit via AJAX
                    const refundForm = document.querySelector('#refundModal form');
                    if (refundForm) {
                        refundForm.addEventListener('submit', function(e) {
                            e.preventDefault();
                            const formData = new FormData(refundForm);
                            fetch(refundForm.action, {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.text())
                            .then(result => {
                                // Cek jika result mengandung kata sukses, tampilkan alert, lalu reload
                                if (result.includes('Booking refunded successfully')) {
                                    alert('Refund berhasil diproses!');
                                } else if (result.includes('Refund only allowed for paid bookings')) {
                                    alert('Refund hanya bisa untuk booking yang sudah dibayar!');
                                } else if (result.includes('Booking not found')) {
                                    alert('Booking tidak ditemukan!');
                                }
                                location.reload();
                            });
                        });
                    }
                    refundModal.show();
                });
        });
    });
    
    // Notification template handling
    const templateSelect = document.getElementById('notification-template');
    const customMessageDiv = document.getElementById('custom-message');
    
    templateSelect.addEventListener('change', function() {
        if (this.value === 'custom') {
            customMessageDiv.style.display = 'block';
        } else {
            customMessageDiv.style.display = 'none';
        }
    });
    
    // Send notification functionality
    const notificationButtons = document.querySelectorAll('.send-notification');
    const notificationPreviewModal = new bootstrap.Modal(document.getElementById('notificationPreviewModal'));
    let currentNotificationType = '';
    let currentBookingId = '';
    
    notificationButtons.forEach(button => {
        button.addEventListener('click', function() {
            const type = this.getAttribute('data-type');
            const bookingId = this.getAttribute('data-id');
            const templateType = document.getElementById('notification-template').value;
            
            currentNotificationType = type;
            currentBookingId = bookingId;
            
            // Set recipient info
            document.getElementById('preview-recipient').textContent = type === 'email' 
                ? CUSTOMER_EMAIL
                : CUSTOMER_PHONE;
            
            // Set subject based on template
            let subject = '';
            let content = '';
            
            switch(templateType) {
                case 'reminder':
                    subject = 'Reminder: Your Upcoming Booking';
                    content = REMINDER_CONTENT;
                    break;
                case 'confirmation':
                    subject = 'Booking Confirmation';
                    content = CONFIRMATION_CONTENT;
                    break;
                case 'cancellation':
                    subject = 'Booking Cancellation';
                    content = CANCELLATION_CONTENT;
                    break;
                case 'payment':
                    subject = 'Payment Reminder';
                    content = PAYMENT_CONTENT;
                    break;
                case 'custom':
                    subject = document.getElementById('message-subject').value || 'Message from ' + TENANT_NAME;
                    content = document.getElementById('message-body').value || 'No message content.';
                    content = `<p>${content.replace(/\n/g, '</p><p>')}</p>`;
                    break;
            }
            
            document.getElementById('preview-subject').textContent = subject;
            document.getElementById('preview-content').innerHTML = content;
            
            notificationPreviewModal.show();
        });
    });
    
    document.getElementById('send-message-btn').addEventListener('click', function() {
        const templateType = document.getElementById('notification-template').value;
        const subject = document.getElementById('preview-subject').textContent;
        const content = document.getElementById('preview-content').innerHTML;
        
        // Send notification via AJAX
        fetch(BASE_URL + 'api/send-notification', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                booking_id: currentBookingId,
                type: currentNotificationType,
                subject: subject,
                message: content,
                template: templateType
            })
        })
        .then(response => response.json())
        .then(data => {
            notificationPreviewModal.hide();
            
            if (data.success) {
                alert('Notification sent successfully!');
            } else {
                alert('Failed to send notification: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while sending the notification.');
        });
    });
    
    // Helper function to escape HTML
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
});
