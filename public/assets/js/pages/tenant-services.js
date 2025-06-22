$(document).ready(function() {
    // Handle service toggle (activate/deactivate)
    $('.toggle-service').on('click', function() {
        const button = $(this);
        const serviceId = button.data('id');
        const currentStatus = button.data('status');
        const newStatus = currentStatus == 1 ? 0 : 1;
        const actionText = currentStatus == 1 ? 'deactivate' : 'activate';

        Swal.fire({
            title: 'Confirm Action',
            text: `Are you sure you want to ${actionText} this service?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, proceed!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                button.prop('disabled', true);
                
                // Prepare data with CSRF token
                const data = {
                    [csrfName]: csrfToken,
                    status: newStatus
                };

                $.ajax({
                    url: `${baseUrl}/services/toggle/${serviceId}`,
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        if (response.success) {
                            // Update button state
                            button.data('status', newStatus);
                            button.attr('title', `${newStatus == 1 ? 'Deactivate' : 'Activate'} Service`);
                            button.find('i').removeClass('bi-toggle-on bi-toggle-off')
                                        .addClass(`bi-toggle-${newStatus == 1 ? 'on' : 'off'}`);
                            
                            // Update status badge
                            const statusBadge = button.closest('tr').find('.service-status .badge');
                            statusBadge.removeClass('bg-success bg-danger')
                                    .addClass(newStatus == 1 ? 'bg-success' : 'bg-danger');
                            
                            const statusIcon = statusBadge.find('i');
                            statusIcon.removeClass('bi-check-circle bi-x-circle')
                                    .addClass(newStatus == 1 ? 'bi-check-circle' : 'bi-x-circle');
                            
                            statusBadge.html(
                                `<i class="bi bi-${newStatus == 1 ? 'check-circle' : 'x-circle'} me-1"></i>` +
                                `${newStatus == 1 ? 'Active' : 'Inactive'}`
                            );

                            // Show success message
                            Swal.fire({
                                title: 'Success!',
                                text: `Service successfully ${actionText}d`,
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                response.message || 'Failed to update service status',
                                'error'
                            );
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr);
                        Swal.fire(
                            'Error!',
                            'An error occurred while updating service status',
                            'error'
                        );
                    },
                    complete: function() {
                        // Re-enable button
                        button.prop('disabled', false);
                    }
                });
            }
        });
    });
});
