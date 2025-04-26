<!-- Head -->
<?= $this->include('layouts/head') ?>

<body class="nav-fixed">
    <?= $this->include('layouts/nav') ?>
    <div id="layoutSidenav">
        <?= $this->include('layouts/sidenav') ?>
        <div id="layoutSidenav_content">
            <main>
                <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
                    <div class="container-xl px-4">
                        <div class="page-header-content pt-4">
                            <div class="row align-items-center justify-content-between">
                                <div class="col-auto mt-4">
                                    <h1 class="page-header-title">
                                        <div class="page-header-icon"><i data-feather="activity"></i></div>
                                        <?php if (session()->get('isLoggedIn')): ?>
                                            Hi, <?= esc(session()->get('userFullName')); ?>!
                                        <?php else: ?>
                                            Please login first. <?= esc(session()->get('userFullName')); ?>
                                        <?php endif; ?>
                                    </h1>
                                    <div class="page-header-subtitle">Welcome to E-Competency</div>
                                    <div class="page-header-subtitle">Human resource isn't a thing we do, it's the thing that runs our business</div>
                                </div>
                            </div>
                            <div class="page-header-search mt-4">
                                <div class="input-group input-group-joined">
                                    <input class="form-control" type="text" placeholder="Search..." aria-label="Search" autofocus="" id="searchCompetency">
                                    <span class="input-group-text"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search">
                                            <circle cx="11" cy="11" r="8"></circle>
                                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                        </svg></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
                <!-- Main page content-->
                <div class="container-xl px-4 mt-n10">
                    <div class="row">
                        <!-- Example DataTable for Dashboard Demo-->
                        <div class="mb-4">
                            <div class="card h-100">
                                <div class="card-body h-100 p-5">
                                    <div class="row align-items-center">
                                        <!-- Make table responsive -->
                                        <div class="table-responsive">
                                            <table id="suggestionsTable" class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Photo</th>
                                                        <th>Full Name</th>
                                                        <th class="d-none d-md-table-cell">Job Title</th>
                                                        <th class="d-none d-md-table-cell">Department</th>
                                                        <th>Line</th>
                                                        <th class="d-none d-lg-table-cell">Competency</th>
                                                        <th>Indicator</th>
                                                        <th>Achieved</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="suggestionsList">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Structure -->
                        <div class="modal fade" id="indicatorsModal" tabindex="-1" aria-labelledby="indicatorsModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="indicatorsModalLabel">Achieved Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Additional information -->
                                        <div class="mb-3">
                                            <strong>Full Name:</strong> <span id="modalFullName"></span><br>
                                            <strong>Job Title:</strong> <span id="modalJobTitle"></span><br>
                                            <strong>Supervisor:</strong> <span id="modalSupervisor"></span><br>
                                            <strong>Join Date:</strong> <span id="modalJoinDate"></span><br>
                                            <strong>mUser:</strong> <span id="modalMUser"></span><br>
                                            <strong>achievementPercentage:</strong> <span id="achievementPercentage"></span><br>
                                        </div>
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Indicator</th>
                                                    <th>Achieved</th>
                                                </tr>
                                            </thead>
                                            <tbody id="indicatorsList"></tbody>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xxl-4 col-xl-12 mb-4">
                            <div class="card h-100">
                                <div class="card-body h-100 p-5">
                                    <div class="row align-items-center">
                                        <div class="col-xl-8 col-xxl-12">
                                            <div
                                                class="text-center text-xl-start text-xxl-center mb-4 mb-xl-0 mb-xxl-4">
                                                <h1 class="text-primary" id="txtFullName">Nama Lengkap</h1>
                                                <p class="text-gray-700 mb-0" id="txtJobTitle">Jabatan</p>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-xxl-12 text-center">
                                            <img class="img-fluid" id="txtPhoto" src="assets/img/illustrations/at-work.svg" style="max-width: 26rem;" alt="User Photo" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-4 col-xl-6 mb-4">
                            <div class="card card-header-actions h-100">
                                <div class="card-header">
                                    Employee Detail
                                </div>
                                <div class="card-body">
                                    <div class="card-footer">
                                        <div class="small text-muted">ID</div>
                                        <h4 class="small" id="intUserID">069</h4>
                                    </div>
                                    <div class="card-footer">
                                        <div class="small text-muted">Report to</div>
                                        <h4 class="small" id="txtSupervisor">Supervisor</h4>
                                    </div>
                                    <div class="card-footer">
                                        <div class="small text-muted">Org Group Name</div>
                                        <h4 class="small" id="txtDepartmentName">SHP Plant Production</h4>
                                    </div>
                                    <div class="card-footer">
                                        <div class="small text-muted">Job Level</div>
                                        <h4 class="small">Coming soon!</h4>
                                    </div>
                                    <div class="card-footer">
                                        <div class="small text-muted">Join Date</div>
                                        <h4 class="small" id="dtmJoinDate">19 Oktober 2016</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-4 col-xl-6 mb-4">
                            <div class="card card-header-actions h-100">
                                <div class="card-header">
                                    Competencies
                                    <div class="dropdown no-caret">
                                        <button class="btn btn-transparent-dark btn-icon dropdown-toggle"
                                            id="dropdownMenuButton" type="button" data-bs-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false"><i class="text-gray-500"
                                                data-feather="more-vertical"></i></button>
                                        <div class="dropdown-menu dropdown-menu-end animated--fade-in-up"
                                            aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" href="#!">
                                                <div class="dropdown-item-icon"><i class="text-gray-500"
                                                        data-feather="list"></i></div>
                                                Manage Tasks
                                            </a>
                                            <a class="dropdown-item" href="#!">
                                                <div class="dropdown-item-icon"><i class="text-gray-500"
                                                        data-feather="plus-circle"></i></div>
                                                Add New Task
                                            </a>
                                            <a class="dropdown-item" href="#!">
                                                <div class="dropdown-item-icon"><i class="text-gray-500"
                                                        data-feather="minus-circle"></i></div>
                                                Delete Tasks
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h4 class="small">
                                        Functional Competencies
                                        <span class="float-end fw-bold">50%</span>
                                    </h4>
                                    <div class="small text-muted">Packing Operator</div>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 50%"
                                            aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <h4 class="small">
                                        General Skills
                                        <span class="float-end fw-bold">Phase 2!</span>
                                    </h4>
                                    <div class="small text-muted">Packing Operator Line A</div>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 0%"
                                            aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="card-footer position-relative">
                                    <div class="d-flex align-items-center justify-content-between small text-body">
                                        <a class="stretched-link text-body" href="#!">Visit Task Center</a>
                                        <i class="fas fa-angle-right"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Radar Chart Row -->
                    <div class="row">
                        <div class="col-xxl-12 col-xl-12 mb-4">
                            <div class="card card-header-actions h-100">
                                <div class="card-header">
                                    Skill Matrix
                                    <div class="dropdown no-caret">
                                        <button class="btn btn-transparent-dark btn-icon dropdown-toggle" id="dropdownMenuButton" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="text-gray-500" data-feather="more-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end animated--fade-in-up" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" href="#!">
                                                <div class="dropdown-item-icon"><i class="text-gray-500" data-feather="list"></i></div>
                                                Manage Tasks
                                            </a>
                                            <a class="dropdown-item" href="#!">
                                                <div class="dropdown-item-icon"><i class="text-gray-500" data-feather="plus-circle"></i></div>
                                                Add New Task
                                            </a>
                                            <a class="dropdown-item" href="#!">
                                                <div class="dropdown-item-icon"><i class="text-gray-500" data-feather="minus-circle"></i></div>
                                                Delete Tasks
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <canvas id="myRadarChart" style="width: 100%; height: 400px;"></canvas>
                                </div>
                                <div class="card-footer position-relative">
                                    <div class="d-flex align-items-center justify-content-between small text-body">
                                        <a class="stretched-link text-body" href="#!">Visit Task Center</a>
                                        <i class="fas fa-angle-right"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Example Colored Cards for Dashboard Demo-->
                    <div class="row">
                        <div class="col-lg-6 col-xl-3 mb-4">
                            <div class="card bg-primary text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-white-75 small">Achievement 1</div>
                                            <div class="text-lg fw-bold">Coming Soon!</div>
                                        </div>
                                        <i class="feather-xl text-white-50" data-feather="calendar"></i>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between small">
                                    <a class="text-white stretched-link" href="#!">View Report</a>
                                    <div class="text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xl-3 mb-4">
                            <div class="card bg-warning text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-white-75 small">Achievement 2</div>
                                            <div class="text-lg fw-bold">Coming Soon!</div>
                                        </div>
                                        <i class="feather-xl text-white-50" data-feather="dollar-sign"></i>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between small">
                                    <a class="text-white stretched-link" href="#!">View Report</a>
                                    <div class="text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xl-3 mb-4">
                            <div class="card bg-success text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-white-75 small">Achievement 3</div>
                                            <div class="text-lg fw-bold">Coming Soon!</div>
                                        </div>
                                        <i class="feather-xl text-white-50" data-feather="check-square"></i>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between small">
                                    <a class="text-white stretched-link" href="#!">View Tasks</a>
                                    <div class="text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xl-3 mb-4">
                            <div class="card bg-danger text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-white-75 small">Achievement 4</div>
                                            <div class="text-lg fw-bold">Coming Soon!</div>
                                        </div>
                                        <i class="feather-xl text-white-50" data-feather="message-circle"></i>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between small">
                                    <a class="text-white stretched-link" href="#!">View Requests</a>
                                    <div class="text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <?= $this->include('layouts/footer') ?>
        </div>
    </div>
    <!-- Scripts -->
    <?= $this->include('layouts/scripts') ?>
    <script>
        $(document).ready(function() {
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
                                callback: function(value) {
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

            // Sembunyikan elemen dengan kelas input-group, input-group-joined, dan input-group-solid
            $('#search_hide').hide();

            var table = $('#suggestionsTable').DataTable({
                searching: false // Nonaktifkan kolom pencarian
            });

            // Event listener untuk input pencarian
            $('#searchCompetency').on('input', function() {
                var searchTerm = $(this).val();
                table.clear(); // Kosongkan data sebelumnya

                if (searchTerm.length > 0) {
                    $.ajax({
                        url: 'auto_suggest/search',
                        type: 'POST',
                        data: {
                            searchTerm: searchTerm
                        },
                        success: function(response) {
                            console.log(response); // Log response untuk verifikasi struktur
                            try {
                                var suggestions = response;
                                var groupedData = {};

                                // Group data berdasarkan unique identifiers
                                suggestions.forEach(function(item) {
                                    var key = item.txtFullName + '|' + item.txtJobTitle + '|' + item.txtDepartmentName + '|' + item.txtLine;

                                    if (!groupedData[key]) {
                                        groupedData[key] = {
                                            photo: item.txtPhoto,
                                            fullName: item.txtFullName,
                                            jobTitle: item.txtJobTitle,
                                            department: item.txtDepartmentName,
                                            line: item.txtLine,
                                            supervisor: item.supervisorName || 'Unknown', // Supervisor dari backend
                                            joinDate: item.dtmJoinDate || '', // Join date langsung dari item jika ada
                                            userID: item.intUserID,
                                            competencies: [],
                                        };
                                    }

                                    // Tambahkan kompetensi ke groupedData
                                    groupedData[key].competencies.push({
                                        competency: item.txtCompetency,
                                        indicator: item.txtIndicator,
                                        achieved: item.bitAchieved === "1" ? "Yes" : "No" // Status untuk modal
                                    });

                                    // Hitung persentase pencapaian untuk setiap group.competencies
                                    groupedData[key].achievedPercentage = (() => {
                                        var totalYes = groupedData[key].competencies.filter(comp => comp.achieved === "Yes").length;
                                        var totalCompetencies = groupedData[key].competencies.length;
                                        return totalCompetencies > 0 ? (totalYes / totalCompetencies) * 100 : 0;
                                    })();
                                });

                                // Tambahkan data yang dikelompokkan ke dalam tabel
                                for (var key in groupedData) {
                                    var group = groupedData[key];

                                    // Base URL untuk path foto yang mengarah ke folder uploads/photos
                                    var photoPath = group.photo ? '<?php echo base_url('uploads/photos/'); ?>' + group.photo : 'No photo';

                                    // Bangun baris pertama tabel dengan progress bar
                                    var firstRow = [
                                        photoPath !== 'No photo' ? '<img src="' + photoPath + '" alt="Photo" style="width: 50px; height: 50px; object-fit: cover;">' : 'No photo',
                                        group.fullName,
                                        group.jobTitle,
                                        group.department,
                                        group.line,
                                        group.competencies.length > 0 ? group.competencies[0].competency : '',
                                        '<button class="btn btn-link view-details" data-competencies=\'' + JSON.stringify(group.competencies) + '\' data-fullname="' + group.fullName + '" data-jobtitle="' + group.jobTitle + '" data-supervisor="' + group.supervisor + '" data-joindate="' + group.joinDate + '" data-photo="' + photoPath + '">View</button>',

                                        // Kolom progress bar dengan persentase pencapaian
                                        group.competencies.length > 0 ? `
                                        <h4 class="small">
                                            Functional Competencies
                                            <span class="float-end fw-bold">${group.achievedPercentage.toFixed(2)}%</span>
                                        </h4>
                                        <div class="small text-muted">${group.jobTitle}</div>
                                        <div class="progress mb-4">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: ${group.achievedPercentage.toFixed(2)}%"
                                                aria-valuenow="${group.achievedPercentage.toFixed(2)}" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                    ` : '' // Menambahkan progress bar jika data kompetensi tersedia
                                    ];

                                    // Tambahkan baris ke tabel dan tampilkan
                                    table.row.add(firstRow).draw();
                                }

                                // Event click untuk melihat detail
                                $('.view-details').on('click', function() {
                                    var competencies = $(this).data('competencies');
                                    var indicatorsList = $('#indicatorsList');
                                    indicatorsList.empty(); // Kosongkan indikator sebelumnya

                                    // Ambil data tambahan dari atribut data
                                    var fullName = $(this).data('fullname');
                                    var jobTitle = $(this).data('jobtitle');
                                    var supervisor = $(this).data('supervisor');
                                    var joinDate = $(this).data('joindate');
                                    var photoUrl = $(this).data('photo');

                                    // Isi modal dengan detail tambahan
                                    $('#modalFullName').text(fullName);
                                    $('#txtFullName').text(fullName);
                                    $('#modalJobTitle').text(jobTitle);
                                    $('#txtJobTitle').text(jobTitle);
                                    $('#txtSupervisor').text(supervisor);
                                    $('#modalSupervisor').text(supervisor);
                                    $('#modalJoinDate').text(joinDate);
                                    $('#txtPhoto').attr('src', photoUrl);

                                    // Loop untuk menambahkan indikator
                                    competencies.forEach(function(comp) {
                                        var achievedStatus = comp.achieved === "Yes" ? "Yes" : "No";
                                        var rowClass = comp.achieved === "No" ? 'table-warning' : '';

                                        indicatorsList.append(
                                            '<tr class="' + rowClass + '">' +
                                            '<td>' + comp.indicator + '</td>' +
                                            '<td>' + achievedStatus + '</td>' +
                                            '</tr>'
                                        );
                                    });

                                    // Hitung total YES dan persentase pencapaian
                                    var totalYes = competencies.filter(function(comp) {
                                        return comp.achieved === "Yes";
                                    }).length;
                                    var totalIndicators = competencies.length;
                                    var achievementPercentage = (totalYes / totalIndicators) * 100;

                                    // Tampilkan hasil di elemen yang sesuai
                                    $('#achievementPercentage').text(achievementPercentage.toFixed(2) + '%');
                                    $('#indicatorsModal').modal('show'); // Tampilkan modal
                                });

                            } catch (error) {
                                console.error("Error parsing JSON:", error);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX Error:", status, error);
                        }
                    });
                } else {
                    table.clear(); // Kosongkan tabel jika panjang pencarian kurang dari 3
                    table.draw(); // Gambar ulang tabel setelah dikosongkan
                }
            });
        });
    </script>

</body>

</html>