$(document).ready(function () {
    // Function to format the status column
    function formatStatus(data) {
        var status = $(data);
        var isActive = status.find('i').hasClass('text-success');
        return `<div class="status-badge ${isActive ? 'active' : 'inactive'}">
                    <i data-feather="${isActive ? 'check-circle' : 'x-circle'}" 
                       class="feather-icon"></i>
                    ${isActive ? 'Aktif' : 'Tidak Aktif'}
                </div>`;
    }

    // Initialize DataTable with server-side processing
    const roleTable = $('#roleTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: `${baseUrl}/roles/data`,
            type: 'POST',
            beforeSend: function (xhr) {
                const token = document.querySelector('meta[name="csrf-token"]')?.content;
                if (token) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', token);
                }
            },
            error: function (xhr, error, thrown) {
                console.error('DataTables error:', error);
                // Fix: Use roleTable instead of this
                roleTable.order([1, 'asc']).draw();
            }
        },
        columns: [
            {
                data: null,
                name: 'index',
                searchable: false,
                orderable: false,
                responsivePriority: 3,
                title: 'No.',  // Changed from '#' to 'No.'
                width: '40px',
                className: 'text-center',
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'txtRoleName',
                name: 'txtRoleName',
                responsivePriority: 1, // Changed to highest priority
                title: 'Role Name',
                width: '120px'
            },
            {
                data: 'txtRoleDesc',
                name: 'txtRoleDesc',
                render: function (data) {
                    return data || '-';
                },
                responsivePriority: 4,
                title: 'Description',
                width: '200px'
            },
            {
                data: 'txtRoleNote',
                name: 'txtRoleNote',
                render: function (data) {
                    return data || '-';
                },
                responsivePriority: 4,
                title: 'Notes',
                width: '200px'
            },
            {
                data: 'bitStatus',
                name: 'bitStatus',
                render: function (data) {
                    const status = parseInt(data) === 1;
                    return `<span class="status-badge">
                        <i data-feather="${status ? 'check-circle' : 'x-circle'}" 
                           class="feather-icon ${status ? 'text-success' : 'text-danger'}"></i>
                        ${status ? 'Active' : 'Inactive'}
                    </span>`;
                },
                responsivePriority: 5,
                title: 'Status',
                width: '80px'
            },
            {
                data: 'txtCreatedBy',
                name: 'txtCreatedBy',
                render: function (data) {
                    return data || 'System';
                },
                responsivePriority: 6,
                title: 'Created By',
                width: '100px'
            },
            {
                data: 'dtmCreatedDate',
                name: 'dtmCreatedDate',
                render: function (data) {
                    if (!data) return '-';
                    try {
                        // Parse date and handle different formats
                        const date = new Date(data.replace(' ', 'T'));
                        if (isNaN(date.getTime())) return '-';

                        // Format: DD-MMM-YY HH:mm
                        return date.toLocaleString('en-GB', {
                            day: '2-digit',
                            month: 'short',
                            year: '2-digit',
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: false
                        }).replace(',', '');
                    } catch (e) {
                        console.warn('Date parse error:', e);
                        return '-';
                    }
                },
                responsivePriority: 7,
                title: 'Created Date',
                width: '120px'
            },
            {
                data: 'txtLastUpdatedBy',
                name: 'txtLastUpdatedBy',
                render: function (data) {
                    return data || '-';
                },
                responsivePriority: 8,
                title: 'Updated By',
                width: '120px'
            },
            {
                data: 'dtmUpdatedDate',
                name: 'dtmUpdatedDate',
                render: function (data) {
                    if (!data) return '-';
                    try {
                        // Parse date and handle different formats
                        const date = new Date(data.replace(' ', 'T'));
                        if (isNaN(date.getTime())) return '-';

                        // Format: DD-MMM-YY HH:mm
                        return date.toLocaleString('en-GB', {
                            day: '2-digit',
                            month: 'short',
                            year: '2-digit',
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: false
                        }).replace(',', '');
                    } catch (e) {
                        console.warn('Date parse error:', e);
                        return '-';
                    }
                },
                responsivePriority: 9,
                title: 'Updated Date',
                width: '120px'
            },
            {
                data: 'intRoleID',
                name: 'actions',
                orderable: false,
                searchable: false,
                responsivePriority: 2, // Changed to second highest priority
                title: 'Actions',
                width: '100px',
                render: function (data) {
                    const cleanBaseUrl = baseUrl.replace(/\/+$/, '');
                    return `<div class="btn-group" role="group">
                        <a href="${cleanBaseUrl}/roles/view/${data}" class="btn btn-info btn-sm" title="View">
                            <i data-feather="eye"></i>
                        </a>
                        <a href="${cleanBaseUrl}/roles/edit/${data}" class="btn btn-warning btn-sm" title="Edit">
                            <i data-feather="edit"></i>
                        </a>
                    </div>`;
                }
            }
        ], order: [[1, 'asc']],
        responsive: {
            details: {
                type: 'inline',
                target: 'tr',
                renderer: function (api, rowIdx, columns) {
                    const data = $.map(columns, function (col, i) {
                        return col.hidden ?
                            '<tr data-dt-row="' + col.rowIndex + '" data-dt-column="' + col.columnIndex + '">' +
                            '<td><b>' + col.title + ':</b></td> ' +
                            '<td>' + col.data + '</td>' +
                            '</tr>' :
                            '';
                    }).join('');

                    return data ? $('<table class="dtr-details"/>').append(data) : false;
                }
            }
        },
        drawCallback: function () {
            if (typeof feather !== 'undefined') {
                // Reinitialize feather icons
                feather.replace({
                    'stroke-width': 2,
                    'width': 16,
                    'height': 16,
                    'class': 'feather-icon'
                });

                // Force redraw icons in mobile
                setTimeout(function () {
                    feather.replace({
                        'stroke-width': 2,
                        'width': 16,
                        'height': 16,
                        'class': 'feather-icon'
                    });
                }, 100);
            }
        },
        initComplete: function () {
            this.api().rows().every(function () {
                $(this.node()).on('click', function () {
                    $(this).trigger('dtr:toggle');
                });
            });
        }
    });    // Handle window resize with debounce
    let resizeTimer;
    $(window).on('resize', function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function () {
            roleTable.columns.adjust();
        }, 250);
    });

    // Add click handler to prevent multiple rapid clicks
    let sortingTimeout;
    $('#roleTable thead').on('click', 'th', function () {
        if (sortingTimeout) {
            return false;
        }

        sortingTimeout = setTimeout(() => {
            sortingTimeout = null;
        }, 500); // Debounce sorting clicks
    });
});
