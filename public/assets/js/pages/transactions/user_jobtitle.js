$(document).ready(function () {
    const alertPlaceholder = document.getElementById('liveAlertPlaceholder')

    // Open Modal on Button Click
    $('#searchDepartment').on('click', function () {
        $('#modalDepartment').modal('show');
        loadDepartmentTable();
    });

    // Populate the datatable dynamically
    function loadDepartmentTable() {
        if (!$.fn.DataTable.isDataTable('#tableDepartment')) {
            $('#tableDepartment').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/departments/getDepartments', // URL endpoint
                    type: 'GET',
                },
                pageLength: 10,
                columns: [
                    { data: 'intDepartmentID', title: 'ID', orderable: true }, // Sorting enabled for ID column
                    { data: 'txtDepartmentName', title: 'Department Name', orderable: true }, // Sorting enabled for Department Name
                    {
                        data: null,
                        title: 'Action',
                        render: function (data) {
                            return `
                        <button class="btn btn-primary btn-select" data-id="${data.intDepartmentID}" data-name="${data.txtDepartmentName}">
                            <i data-feather="check"></i>
                        </button>`;
                        },
                        orderable: false // Disable sorting for Action column
                    }
                ],
                columnDefs: [
                    { orderable: true, targets: [0, 1] }, // Enable sorting for ID (column 0) and Department Name (column 1)
                    { orderable: false, targets: [2] } // Disable sorting for Action column (column 2)
                ],
                order: [[0, 'asc']], // Default sorting by ID (column 0) in ascending order
                responsive: true, // Enable responsive feature
                drawCallback: function () {
                    feather.replace(); // Refresh Feather Icons
                }
            });
        }
    }

    // Handle selection
    $(document).on('click', '.btn-select', function () {
        const id = $(this).data('id');
        const name = $(this).data('name');
        $('#searchDepartment').val(name);
        $('#searchDepartment').data('selected-id', id); // Save the ID if needed
        $('#modalDepartment').modal('hide');
    });

    // When opening the modal
    $('#modalDepartment').on('show.bs.modal', function () {
        // Remove aria-hidden to ensure the modal is accessible to screen readers
        $(this).removeAttr('aria-hidden');
        $(this).css('display', 'block'); // Make sure modal is shown
    });

    // When closing the modal
    $('#modalDepartment').on('hidden.bs.modal', function () {
        // Set aria-hidden to true when the modal is hidden
        $(this).attr('aria-hidden', 'true');
        $(this).css('display', 'none'); // Hide modal visually
    });

    $('#btnClearDepartment').click(function () {
        $('#searchDepartment').val('');
    });

    const alert = (message, type) => {
        const wrapper = document.createElement('div')
        wrapper.innerHTML = [
            `<div class="alert alert-${type} alert-dismissible fade show" role="alert">`,
            `   <div>${message}</div>`,
            '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
            '</div>'
        ].join('')

        alertPlaceholder.append(wrapper)

        // Hilangkan alert setelah 5 detik
        setTimeout(() => {
            // Menambahkan kelas fade untuk animasi menghilang
            wrapper.querySelector('.alert').classList.remove('show')
            wrapper.querySelector('.alert').classList.add('fade')

            // Setelah 1 detik (durasi animasi fade-out), hapus elemen dari DOM
            setTimeout(() => {
                wrapper.remove()
            }, 1000); // 1000 ms = 1 detik, untuk menunggu animasi selesai
        }, 5000); // 5000 ms = 5 detik, sebelum fade-out dimulai
    }

    var table = $('#user_jobtitle').DataTable({
        dom: 'Bfrtipl', // Ini mengatur elemen kontrol (buttons akan muncul di atas tabel)
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        "processing": true,
        "serverSide": true,
        "responsive": true,
        "autoWidth": false, // Menyesuaikan lebar kolom berdasarkan konten
        "ajax": {
            "url": "/transactions/getUserJobTitles", // URL API untuk mengambil data
            "type": "POST",
            "data": function (d) {
                // Tambahkan CSRF Token jika diperlukan
                d.csrf_token = $('meta[name="csrf-token"]').attr('content');
            }
        },
        "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]], // Opsi jumlah data per halaman
        "pageLength": 10, // Default jumlah data per halaman
        "columns": [
            { "data": "intUserID" }, // ID User
            { "data": "txtFullName" }, // Nama lengkap
            {
                "data": "jobTitles",
                "render": function (data, type, row) {
                    if (!data || !data.length) return 'N/A';

                    // ID unik untuk setiap accordion berdasarkan intUserID
                    const accordionId = `accordionJobTitles${row.intUserID}`;
                    const collapseId = `collapseJobTitles${row.intUserID}`;

                    // Bangun list item untuk setiap job title dengan tombol Edit dan Details
                    const jobList = data.map(job => `
            <li>
                <strong>${job.title}</strong> - Achieved: ${job.achieved}
                <div class="mt-2">
                    <a href="/transactions/user_jobtitle/details/${job.jobTitleID}" class="btn btn-info btn-sm">Details</a>
                    <button class="btn btn-warning edit-btn btn-sm" data-id="${job.jobTitleID}">Edit</button>
                </div>
            </li>
        `).join('');

                    // Struktur accordion dengan daftar job titles di dalamnya
                    return `
            <div class="accordion accordion-flush" id="${accordionId}">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading${row.intUserID}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#${collapseId}" aria-expanded="false" aria-controls="${collapseId}">
                            Job Titles
                        </button>
                    </h2>
                    <div id="${collapseId}" class="accordion-collapse collapse" data-bs-parent="#${accordionId}">
                        <div class="accordion-body">
                            <ul>${jobList}</ul>
                        </div>
                    </div>
                </div>
            </div>
        `;
                }
            },
            {
                "data": null,
                "orderable": false,
                "render": function (data, type, row) {
                    return `
                        <a href="/transactions/user_jobtitle/details/${row.intUserID}" class="btn btn-info btn-sm">Details</a>
                        <a href="/transactions/user_jobtitle/edit/${row.intUserID}" class="btn btn-warning btn-sm">Edit</a>
                    `;
                },
                "visible": false
            }
        ], "columnDefs": [
            { "width": "10%", "targets": [0] }, // Lebar kolom ID User (10%)
            { "width": "20%", "targets": [1] }, // Lebar kolom Nama User (20%)
            { "width": "20%", "targets": [3] }, // Lebar kolom Action (20%)
            { "width": "auto", "targets": [2] } // Kolom Job Titles dan Inserted By menyesuaikan secara otomatis
        ]
    });

    let isShowAll = false; // Flag untuk menentukan apakah tombol "Show All" digunakan
    // DataTable setup
    let tableAS = $('#user_jobtitle_auto_suggest').DataTable({
        dom: '<"d-flex justify-content-between"<"length-menu"l><"button-container"B>>t<"d-flex justify-content-between"<"info-section"i><"pagination-section"p>>',
        buttons: [
            {
                extend: 'collection',
                text: '<i data-feather="printer"></i> Print Options', // Tambahkan ikon Feather
                className: "btnArrow",
                buttons: [
                    {
                        extend: "print",
                        text: '<i data-feather="printer"></i> Print',
                        exportOptions: {
                            columns: ':visible',
                            rows: ':visible'
                        }
                    },
                    {
                        extend: "pdf",
                        text: '<i data-feather="file"></i> Export to PDF',
                        exportOptions: {
                            columns: ':visible',
                            rows: ':visible'
                        }
                    },
                    {
                        extend: 'excel',
                        text: '<i data-feather="file-text"></i> Export to Excel', // Tambahkan ikon Feather
                    },
                ]
            }
        ],
        initComplete: function () {
            feather.replace(); // Inisialisasi ikon Feather
        },
        processing: true,
        serverSide: true,
        responsive: true,
        searching: false, // Menonaktifkan search box default
        deferLoading: 0, // Jangan tampilkan data awal
        language: {
            emptyTable: "Please click 'Search' or 'Show All' to load data", // Pesan ketika tidak ada data
            processing: "Please wait...", // Pesan saat sedang memuat data
        },
        ajax: {
            url: '/transactions/getUserJobTitlesAutoSuggest',
            type: 'POST',
            data: function (d) {
                if (isShowAll) {
                    // Kosongkan semua filter untuk Show All
                    d.searchName = '';
                    d.searchJobTitle = '';
                    d.searchDepartment = '';
                    d.searchLine = '';
                } else {
                    // Kirim filter sesuai input
                    d.searchName = $('#searchName').val().trim();
                    d.searchJobTitle = $('#searchJobTitle option:selected').text().trim() === '-- Select Job Title --' ? '' : $('#searchJobTitle option:selected').text().trim();
                    d.searchDepartment = $('#searchDepartment').val().trim();
                    d.searchLine = $('#searchLine option:selected').text().trim() === '-- Select Line --' ? '' : $('#searchLine option:selected').text().trim();
                }
            }
        },
        columns: [
            { data: 'intTrUserJobTitleID' }, // Indeks 0
            { data: 'txtFullName' },         // Indeks 1
            { data: 'txtJobTitle' },         // Indeks 2
            { data: 'txtDepartmentName' },   // Indeks 3
            { data: 'txtLine' },             // Indeks 4
            {                                // Indeks 5 (bitAchieved)
                data: 'bitAchieved',
                render: function (data, type, row) {
                    return data == 1 ?
                        '<i data-feather="check-circle" style="color: green;"></i>' :
                        '<i data-feather="x-circle" style="color: red;"></i>';
                }
            },
            {                                // Indeks 6 (Action)
                data: null,
                defaultContent: '<button class="btn btn-info btn-action">Details</button>'
            }
        ],
        order: [[5, 'desc']] // Pastikan indeks sesuai dengan kolom 'bitAchieved'
    });

    tableAS.on('draw', function () {
        feather.replace();
    });

    // Event handler untuk tombol Search
    $('#btnFind').on('click', function () {
        isShowAll = false; // Reset flag

        const searchName = $('#searchName').val().trim();
        const searchJobTitle = $('#searchJobTitle option:selected').text().trim() === '-- Select Job Title --' ? '' : $('#searchJobTitle option:selected').text().trim();
        const searchDepartment = $('#searchDepartment').val().trim();
        const searchLine = $('#searchLine option:selected').text().trim() === '-- Select Line --' ? '' : $('#searchLine option:selected').text().trim();

        console.log('Filter parameters:', {
            searchName,
            searchJobTitle,
            searchDepartment,
            searchLine,
        });

        // Refresh DataTable dengan filter
        tableAS.ajax.reload();
    });

    // Event handler untuk tombol Show All
    $('#btnShowAll').on('click', function () {
        isShowAll = true; // Set flag untuk Show All

        // Kosongkan semua input dan dropdown
        $('#searchDepartment').val('');
        $('#searchName').val('');
        $('#searchLine').val('');
        $('#searchJobTitle').val('');

        console.log('Filter parameters: Show All');

        // Refresh DataTable tanpa filter
        tableAS.ajax.reload();
    });

    // Menambahkan event listener untuk klik pada tombol Action
    $('#user_jobtitle_auto_suggest tbody').on('click', '.btn-action', function () {
        var rowData = tableAS.row($(this).parents('tr')).data();
        console.log('Row data:', rowData);

        // Isi elemen-elemen modal dengan data dari rowData
        $('#modalDetails #txtFullName').text(rowData.txtFullName || 'N/A');
        $('#modalDetails #txtSupervisor').text(rowData.supervisorName || 'N/A');
        $('#modalDetails #txtJobTitleToLine').text(rowData.txtJobTitle || 'N/A');
        // $('#modalDetails #txtJobTitleToLine').text(`${rowData.txtJobTitle} to ${rowData.txtLine}` || 'N/A');
        $('#modalDetails #intUserID').text(rowData.txtEmpID || 'N/A');
        $('#modalDetails #txtJobTitle').text(rowData.txtJobTitle || 'N/A');
        $('#modalDetails #txtDepartmentName').text(rowData.txtDepartmentName || 'N/A');
        $('#modalDetails #dtmJoinDate').text(rowData.dtmJoinDate || 'N/A');
        $('#modalDetails #txtPhoto').attr(
            'src',
            rowData.txtPhoto ? '/uploads/photos/' + rowData.txtPhoto : 'assets/img/illustrations/at-work.svg'
        );

        let jobTitleID = rowData.intJobTitleID;

        $.ajax({
            url: '/functionalcompetency/getCompetencies/' + jobTitleID,
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                console.log(data); // Log the fetched competencies

                // Clear existing accordion content
                let accordionDiv = $('#competenciesAccordion');
                accordionDiv.empty();

                // Dynamically build the accordion
                data.forEach((competency, index) => {
                    let accordionItem = `
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading${index}">
                            <button class="accordion-button ${index === 0 ? '' : 'collapsed'}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse${index}" aria-expanded="${index === 0}" aria-controls="collapse${index}">
                                ${competency.txtCompetency}
                            </button>
                        </h2>
                        <div id="collapse${index}" class="accordion-collapse collapse ${index === 0 ? 'show' : ''}" aria-labelledby="heading${index}" data-bs-parent="#competenciesAccordion">
                            <div class="accordion-body">
                                <p><strong>ID Competency:</strong> ${competency.intCompetencyID}</p>
                                <p><strong>Job Title ID:</strong> ${competency.intJobTitleID}</p>
                            </div>
                        </div>
                    </div>
                `;
                    accordionDiv.append(accordionItem);
                });
            },
            error: function (xhr, status, error) {
                console.error('Error fetching competencies:', error);
            }
        });

        $('#fc_clickable').on('click', function () {
            console.log('Functional Competencies clicked!');

        });

        // Logika untuk progress bar Functional Competencies
        var progressValue = rowData.bitAchieved === "1" ? 100 : 0; // 100% jika 1, 0% jika 0
        var progressBar = $('#fc_pb'); // Referensi ke progress bar
        var progressPercent = $('#fc_percent'); // Referensi ke teks persentase

        // Perbarui progress bar dan teks persentase
        progressBar.css('width', progressValue + '%').attr('aria-valuenow', progressValue);
        progressPercent.text(progressValue + '%');

        // Ubah warna progress bar berdasarkan nilai
        if (progressValue === 100) {
            progressBar.removeClass('bg-danger').addClass('bg-success'); // Hijau untuk 100%
        } else {
            progressBar.removeClass('bg-success').addClass('bg-danger'); // Merah untuk 0%
        }

        // Tampilkan modal
        $('#modalDetails').modal('show');
    });

    // Menangani event ketika accordion dibuka
    $('#user_jobtitle').on('show.bs.collapse', '.accordion-collapse', function () {
        // Tutup semua accordion yang lain ketika yang satu dibuka
        var accordionId = $(this).attr('id');
        $('.accordion-collapse').not(`#${accordionId}`).collapse('hide');
    });

    // Event listener for the edit button
    $('#user_jobtitle tbody').on('click', '.edit-btn', function () {
        var userjobtitlesId = $(this).data('id');

        // AJAX request to get the data
        $.ajax({
            url: '/transactions/user_jobtitle/edit/' + userjobtitlesId,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                // Populate modal fields with the data
                $('#editUserJobTitleModal #userJobTitleId').val(data.intTrUserJobTitleID);
                $('#editUserJobTitleModal #intUserID').val(data.intUserID);
                $('#editUserJobTitleModal #userName').val(data.userName);
                $('#editUserJobTitleModal #jobTitle').val(data.txtJobTitle);
                $('#editUserJobTitleModal #achieved').prop('checked', data.bitAchieved == '1');
                $('#editUserJobTitleModal #active').prop('checked', data.bitActive == '1');

                // Show the modal
                $('#editUserJobTitleModal').modal('show');
            },
            error: function (xhr) {
                alert('Error fetching data: ' + xhr.statusText, 'danger');
            }
        });
    });

    // Handle submit form edit
    // Event listener for the save button in the modal
    $('#saveChanges').on('click', function () {
        // Ambil data dari modal
        var dataToSend = {
            intTrUserJobTitleID: $('#editUserJobTitleModal #userJobTitleId').val(),
            intUserID: $('#editUserJobTitleModal #intUserID').val(),
            bitAchieved: $('#editUserJobTitleModal #achieved').is(':checked') ? 1 : 0,
            bitActive: $('#editUserJobTitleModal #active').is(':checked') ? 1 : 0,
        };

        // Kirim data menggunakan AJAX
        $.ajax({
            url: '/transactions/user_jobtitle/update', // Endpoint untuk update
            type: 'POST',
            data: JSON.stringify(dataToSend),
            contentType: 'application/json',
            success: function (response) {
                alert(response.message, 'success');
                table.ajax.reload(null, false); // Memuat ulang tanpa reset posisi paging

                // Tutup modal
                $('#editUserJobTitleModal').modal('hide');
            },
            error: function (xhr) {
                alert('Error saving data: ' + xhr.statusText, 'danger');
            }
        });
    });
    var ctx = $("#myRadarChart")[0].getContext("2d");
    var myRadarChart = new Chart(ctx, {
        type: 'radar',
        data: {
            labels: [
                ["Cartoning Machine Operating"],
                ["Case Packer", "Machine", "Operating"],
                ["Product", "Change Over"],
                ["Struktur", "Dasar", "Mesin", "Cartoning"],
                ["Struktur", "Dasar", "Mesin", "Case Packer"],
                ["Troubleshooting"],
                ["Basic", "Technical", "Skill for IMC"],
                ["Basic", "Production", "Process"],
                ["TPM", "Autonomous", "Maintenance"],
                ["FMEA"]
            ],
            datasets: [{
                label: 'Target',
                data: [100, 100, 100, 100, 100, 100, 100, 100, 100, 100],
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2
            },
            {
                label: 'Actual',
                data: [100, 100, 100, 100, 100, 100, 100, 90, 100, 100],
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 2
            }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Skill Matrix'
                }
            },
            maintainAspectRatio: true, // Allow chart to resize based on container
            scales: {
                r: {
                    min: 0,
                    max: 100,
                    ticks: {
                        stepSize: 10
                    },
                    angleLines: {
                        display: false
                    },
                    pointLabels: {
                        font: {
                            size: 10, // Reduce font size to prevent overlap
                        },
                        padding: 10, // Add space between chart and labels
                        callback: function (value) {
                            // Optional: truncate long labels
                            if (value.length > 15) {
                                return value.substring(0, 15) + '...'; // Shorten long labels
                            }
                            return value;
                        }
                    }
                }
            }
        }
    });
});
