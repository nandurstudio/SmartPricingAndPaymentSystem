$(document).ready(function () {
    const alertPlaceholder = document.getElementById('liveAlertPlaceholder')
    const alert = (message, type) => {
        const wrapper = document.createElement('div')
        wrapper.innerHTML = [
            `<div class="alert alert-${type} alert-dismissible fade show" role="alert">`,
            `   <div>${message}</div>`,
            '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
            '</div>'
        ].join('')

        alertPlaceholder.append(wrapper)
    }
    var table = $('#competencyTable').DataTable({
        "processing": true,
        "serverSide": true,
        "responsive": true,
        "ajax": {
            "url": "competencies/getCompetencies", // Pastikan URL API ini menangani query string untuk sorting
            "type": "POST",
            "data": function (d) {
                // `d` berisi parameter yang dikirimkan oleh DataTable ke server
                // Anda bisa menambahkan data tambahan di sini jika diperlukan
            }
        },
        "columns": [
            { "data": "intCompetencyID", "orderable": true },
            { "data": "txtCompetency", "orderable": true },
            { "data": "bitActive", "orderable": true },
            { "data": "txtInsertedBy", "orderable": true },
            { "data": "dtmInsertedDate", "orderable": true },
            { "data": "txtUpdatedBy", "orderable": true },
            { "data": "dtmUpdatedDate", "orderable": true },
            {
                "orderable": false,
                "defaultContent": "",
                "render": function (data, type, row) {
                    return `
            <a href="competencies/view/${row.intCompetencyID}" class="btn btn-info">Details</a>
            <button class="btn btn-warning edit-btn" data-id="${row.intCompetencyID}">Edit</button>
        `;
                }
            }
        ],
        "columnDefs": [{
            "targets": 2,
            "render": function (data, type, row) {
                return row.bitActive == 1 ? 'Yes' : 'No';
            }
        }
        ]
    });

    // Event listener for the edit button
    $('#competencyTable tbody').on('click', '.edit-btn', function () {
        var competencyId = $(this).data('id');

        // AJAX request to get competency data
        $.ajax({
            url: 'competencies/edit/' + competencyId, // Memperbaiki URL di sini
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                // Populasi modal dengan data
                $('#competencyId').val(data.intCompetencyID);
                $('#txtCompetency').val(data.txtCompetency);
                $('#txtDefinition').val(data.txtDefinition);
                $('#bitActive').prop('checked', data.bitActive == 1); // Cek jika aktif

                // Show the modal
                $('#editCompetencyModal').modal('show');
            },
            error: function (xhr) {
                alert('Error fetching competency data: ' + xhr.statusText, 'danger');
            }
        });
    });

    // Handle submit form edit
    $('#editCompetencyForm').on('submit', function (e) {
        e.preventDefault();

        // Set value bitActive sesuai dengan checkbox
        var isActive = $('#bitActive').is(':checked') ? 1 : 0;
        $('#bitActive').val(isActive); // Set nilai 0 atau 1

        // AJAX request untuk update kompetensi
        $.ajax({
            url: 'competencies/update/' + $('#competencyId').val(), // Memperbaiki URL di sini
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (response) {
                $('#editCompetencyModal').modal('hide'); // Menutup modal
                alert(response.message, 'success');
                table.ajax.reload(); // Reload DataTable untuk memperbarui data
            },
            error: function (xhr) {
                alert('Error updating competency: ' + xhr.statusText, 'danger');
            }
        });
    });
});
