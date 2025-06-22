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
            confirmButtonText: 'Yes, proceed!',
            allowOutsideClick: false
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

                            // Show success message with auto-close
                            Swal.fire({
                                title: 'Success!',
                                text: `Service successfully ${actionText}d`,
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false,
                                allowOutsideClick: false,
                                willClose: () => {
                                    // Cleanup any lingering modal elements
                                    const modalBackdrops = document.getElementsByClassName('swal2-backdrop-show');
                                    const modalContainers = document.getElementsByClassName('swal2-container');
                                    
                                    for (let el of modalBackdrops) {
                                        el.remove();
                                    }
                                    for (let el of modalContainers) {
                                        el.remove();
                                    }

                                    // Reset body styles
                                    document.body.classList.remove('swal2-shown', 'swal2-height-auto');
                                    document.body.style.removeProperty('padding-right');
                                    document.body.style.removeProperty('overflow');
                                }
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: response.message || 'Failed to update service status',
                                icon: 'error',
                                allowOutsideClick: false
                            });
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr);
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred while updating service status',
                            icon: 'error',
                            allowOutsideClick: false
                        });
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
