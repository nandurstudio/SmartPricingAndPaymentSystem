<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Competency List</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap/bootstrap-icons/font/bootstrap-icons.css'); ?>">

    <style>
        /* Spinner CSS */
        .loading {
            display: none;
            /* Sembunyikan spinner secara default */
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
            /* Pastikan spinner berada di atas elemen lain */
        }

        .loading img {
            width: 50px;
            /* Ganti dengan ukuran yang diinginkan */
            height: 50px;
            /* Ganti dengan ukuran yang diinginkan */
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1>Competency List</h1>
        <a href="<?= base_url('/'); ?>" class="btn btn-primary mb-3">Back to Dashboard</a>
        <a href="<?= base_url('competency/create'); ?>" class="btn btn-primary mb-3">Add Competency</a>

        <div class="loading">
            <img src="<?= base_url('assets/images/loading.gif'); ?>" alt="Loading..." />
        </div>

        <table id="competencyTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Competency ID</th>
                    <th>Job Title ID</th>
                    <th>Active</th>
                    <th>Inserted By</th>
                    <th>Inserted Date</th>
                    <th>Updated By</th>
                    <th>Updated Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data akan diisi oleh DataTables -->
            </tbody>
        </table>
    </div>

    <script src="<?= base_url('assets/js/jquery/jquery.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap/bootstrap.bundle.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/datatables/dataTables.js'); ?>"></script>
    <script src="<?= base_url('assets/js/datatables/dataTables.bootstrap5.js'); ?>"></script>
    <script>
        $(document).ready(function() {
            var table = $('#competencyTable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "<?= base_url('competency/getCompetencies'); ?>",
                    "type": "POST"
                },
                "columns": [{
                        "data": null,
                        "render": function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    }, // Menghitung No
                    {
                        "data": "intCompetencyID"
                    },
                    {
                        "data": "intJobTitleID"
                    },
                    {
                        "data": "bitActive",
                        "render": function(data) {
                            return data ? 'Yes' : 'No';
                        }
                    },
                    {
                        "data": "txtInsertedBy"
                    },
                    {
                        "data": "dtmInsertedDate"
                    },
                    {
                        "data": "txtUpdatedBy"
                    },
                    {
                        "data": "dtmUpdatedDate"
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return `
                        <a href="<?= base_url('competency/view/'); ?>${row.intCompetencyID}/${row.intJobTitleID}" class="btn btn-info">Details</a>
                        <a href="<?= base_url('competency/edit/'); ?>${row.intCompetencyID}/${row.intJobTitleID}" class="btn btn-warning">Edit</a>
                    `;
                        }
                    }
                ],
                "searching": true, // Aktifkan pencarian
                "lengthChange": true, // Aktifkan pengubahan jumlah entri
                "pageLength": 10 // Jumlah entri per halaman
            });
        });
    </script>
</body>

</html>