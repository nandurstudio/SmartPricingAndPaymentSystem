$(document).ready(function () {
    const alertPlaceholder = document.getElementById('liveAlertPlaceholder');
    const alert = (message, type) => {
        const wrapper = document.createElement('div');
        wrapper.innerHTML = [
            `<div class="alert alert-${type} alert-dismissible fade show" role="alert">`,
            `   <div>${message}</div>`,
            '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
            '</div>'
        ].join('');

        alertPlaceholder.append(wrapper);
    }

    var table = $('#userTable').DataTable({
        "processing": true,
        "serverSide": true,
        "responsive": true,
        "ajax": {
            "url": "user/getUsers", // Pastikan URL API ini menangani query string untuk sorting
            "type": "POST",
            "data": function (d) {
                // `d` berisi parameter yang dikirimkan oleh DataTable ke server
                // Anda bisa menambahkan data tambahan di sini jika diperlukan
            }
        },
        "columns": [
            { "data": "intUserID", "orderable": true },
            { "data": "txtUserName", "orderable": true, "visible": false },
            { "data": "txtFullName", "orderable": true },
            { "data": "txtEmail", "orderable": true },
            { "data": "txtRoleName", "orderable": true },
            { "data": "txtNick", "orderable": true },
            { "data": "txtEmpID", "orderable": true },
            {
                "data": "txtPhoto",
                "orderable": false,
                "render": function (data, type, row) {
                    if (data) {
                        return `
                            <div class="mb-3">
                                <img src="/uploads/photos/${data}" alt="User Photo" class="img-thumbnail" width="150">
                            </div>`;
                    } else {
                        return `
                            <div class="mb-3">
                                <img src="/uploads/photos/default.png" alt="Default Photo" class="img-thumbnail" width="150">
                            </div>`;
                    }
                }
            },
            { "data": "dtmJoinDate", "orderable": true },
            { "data": "bitActive", "orderable": true },
            {
                "orderable": false,
                "defaultContent": "",
                "render": function (data, type, row) {
                    return `
                        <a href="user/view/${row.intUserID}" class="btn btn-info">Details</a>
                        <button class="btn btn-warning edit-btn" data-id="${row.intUserID}">Edit</button>
                    `;
                }
            }
        ],
        "columnDefs": [{
            "targets": 9,
            "render": function (data, type, row) {
                return row.bitActive == 1 ? 'Active' : 'Inactive';
            }
        }]
    });

    // Event listener for the edit button
    $('#userTable tbody').on('click', '.edit-btn', function () {
        var userId = $(this).data('id'); // Ambil ID user dari tombol edit

        // Arahkan ke halaman update.php dengan membawa ID user
        window.location.href = '/user/update/' + userId;
    });
});
