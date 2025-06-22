// Tenants page specific JavaScript
$(document).ready(function() {
    // Initialize DataTable with improved configuration
    const table = $('#tenantsTable').DataTable({
        order: [[1, 'asc']], // Sort by Name column by default
        pageLength: 25,
        dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search tenants...",
            lengthMenu: "_MENU_ tenants per page",
            emptyTable: "No tenants available"
        }
    });

    // Toggle view (Grid/Table)
    $('#grid-view, #table-view').on('click', function() {
        const view = $(this).data('view');
        
        // Update active state of buttons
        $('.btn[data-view]').removeClass('active');
        $(this).addClass('active');
        
        // Toggle view containers
        if (view === 'grid') {
            $('#grid-container').show();
            $('#table-container').hide();
        } else {
            $('#grid-container').hide();
            $('#table-container').show();
        }
        
        // Store preference
        localStorage.setItem('tenants_view', view);
    });

    // Restore last view preference
    const lastView = localStorage.getItem('tenants_view') || 'table';
    $(`#${lastView}-view`).trigger('click');

    // Handle status toggle with improved UX
    $('.toggle-status').on('click', function() {
        const button = $(this);
        const id = button.data('id');
        const currentStatus = button.data('status');
        const newStatus = currentStatus ? 0 : 1;
        
        Swal.fire({
            title: `${currentStatus ? 'Deactivate' : 'Activate'} Tenant?`,
            text: `Are you sure you want to ${currentStatus ? 'deactivate' : 'activate'} this tenant?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: currentStatus ? '#dc3545' : '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: `Yes, ${currentStatus ? 'deactivate' : 'activate'} it!`,
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `${baseUrl}/tenants/toggle-status/${id}`,
                    method: 'POST',
                    data: {
                        status: newStatus,
                        [csrfToken]: csrfHash
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: response.message || 'An error occurred'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Failed to update status. Please try again.'
                        });
                    }
                });
            }
        });
    });

    // Handle delete tenant
    $('.delete-tenant').on('click', function() {
        const button = $(this);
        const id = button.data('id');
        const name = button.data('name');
        
        Swal.fire({
            title: 'Delete Tenant?',
            html: `Are you sure you want to delete tenant <strong>${name}</strong>?<br>This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `${baseUrl}/tenants/delete/${id}`,
                    method: 'POST',
                    data: {
                        [csrfToken]: csrfHash
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: response.message || 'Failed to delete tenant'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Failed to delete tenant. Please try again.'
                        });
                    }
                });
            }
        });
    });

    // Initialize all tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
