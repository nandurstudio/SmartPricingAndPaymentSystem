// Helper: disconnect tenant-upgrade.js observer if exists
function disconnectUpgradeObserver() {
    if (window.bodyObserver && typeof window.bodyObserver.disconnect === 'function') {
        window.bodyObserver.disconnect();
    }
}

$(document).on('click', '.toggle-service, .toggle-tenant', function (e) {
    e.preventDefault();
    disconnectUpgradeObserver();

    const button = $(this);
    const id = button.data('id');
    const currentStatus = button.data('status') == 1;
    const isTenant = button.hasClass('toggle-tenant');
    const type = isTenant ? 'Tenant' : 'Service';

    Swal.fire({
        title: `${currentStatus ? 'Deactivate' : 'Activate'} ${type}?`,
        text: `Are you sure you want to ${currentStatus ? 'deactivate' : 'activate'} this ${type.toLowerCase()}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: currentStatus ? '#dc3545' : '#198754',
        cancelButtonColor: '#6c757d',
        confirmButtonText: `Yes, ${currentStatus ? 'deactivate' : 'activate'} it!`,
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const endpoint = isTenant ? 'tenants' : 'services';

            $.ajax({
                url: `${window.baseUrl}/${endpoint}/toggle/${id}`,
                type: 'POST',
                data: {
                    [window.csrfName]: window.csrfToken,
                    status: !currentStatus ? 1 : 0
                },
                beforeSend: function () {
                    button.prop('disabled', true).html('<i class="spinner-border spinner-border-sm"></i>');
                },
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message || `${type} successfully ${currentStatus ? 'deactivated' : 'activated'}`,
                            showConfirmButton: false,
                            timer: 1500,
                            timerProgressBar: true,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            willClose: () => {
                                // Ini yang bikin reload setelah fade-out selesai
                                location.reload();
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message || `Failed to update status`
                        });
                    }
                },
                error: function (xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: error || 'An error occurred while updating the status'
                    });
                },
                complete: function () {
                    button.prop('disabled', false).html(currentStatus ? 'Deactivate' : 'Activate');
                }
            });
        }
    });

    return false;
});
