$(document).ready(function() {
    // Handle service toggle (activate/deactivate)
    $('.toggle-service').on('click', function() {
        const button = $(this);
        const serviceId = button.data('id');
        const currentStatus = button.data('status');
        const newStatus = currentStatus == 1 ? 0 : 1;
        const actionText = currentStatus == 1 ? 'deactivate' : 'activate';

        if (confirm(`Are you sure you want to ${actionText} this service?`)) {
            $.ajax({
                url: `${baseUrl}/services/toggle/${serviceId}`,
                type: 'POST',
                data: {
                    status: newStatus
                },
                success: function(response) {
                    if (response.success) {
                        // Update button state
                        button.data('status', newStatus);
                        button.attr('title', `${newStatus == 1 ? 'Deactivate' : 'Activate'} Service`);
                        button.find('i').removeClass('bi-toggle-on bi-toggle-off')
                                      .addClass(`bi-toggle-${newStatus == 1 ? 'on' : 'off'}`);
                        
                        // Update status badge
                        const statusBadge = button.closest('tr').find('td:nth-child(4) .badge');
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
                        toastr.success(`Service successfully ${actionText}d`);
                    } else {
                        toastr.error(response.message || 'Failed to update service status');
                    }
                },
                error: function(xhr) {
                    toastr.error('An error occurred while updating service status');
                    console.error('Error:', xhr);
                }
            });
        }
    });
});
