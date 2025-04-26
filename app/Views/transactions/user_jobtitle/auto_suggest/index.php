<?= $this->extend('layouts/starter/main') ?>
<?= $this->section('content') ?>
<div id="liveAlertPlaceholder"></div>
<div class="row">
    <div class="col-sm-5">
        <!-- Department -->
        <div class="input-group form-floating mb-2">
            <input type="text" id="searchDepartment" class="form-control" placeholder="Org Group Name" readonly aria-label="Search by Org Group Name">
            <label for="searchDepartment">Search by Org Group Name</label>
            <button class="btn btn-outline-danger" type="button" id="btnClearDepartment">
                <i data-feather="x"></i>
            </button>
        </div>

        <!-- Line -->
        <div class="form-floating mb-2">
            <select class="form-select" id="searchLine" aria-label="Search by Line">
                <option value="">-- Select Line --</option>
                <?php foreach ($lines as $line): ?>
                    <option value="<?= $line['intLineID'] ?>"><?= $line['txtLine'] ?></option>
                <?php endforeach; ?>
            </select>
            <label for="searchLine">Search by Line</label>
        </div>
    </div>
    <div class="col-sm-5">
        <!-- Job Title -->
        <div class="form-floating mb-2">
            <select class="form-select" id="searchJobTitle" aria-label="Search by Job Title">
                <option value="">-- Select Job Title --</option>
                <?php foreach ($jobTitles as $jobTitle): ?>
                    <option value="<?= $jobTitle['intJobTitleID'] ?>"><?= $jobTitle['txtJobTitle'] ?></option>
                <?php endforeach; ?>
            </select>
            <label for="searchJobTitle">Search by Job Title</label>
        </div>

        <!-- Employee Name -->
        <div class="form-floating mb-2">
            <input type="text" id="searchName" class="form-control" placeholder="Search by Employee Name">
            <label for="searchName">Search by Employee Name</label>
        </div>
    </div>
    <div class="col-sm-2">
        <!-- Buttons -->
        <!-- Find -->
        <button id="btnFind" class="btn btn-primary w-100 w-sm-auto mb-3 btn-lg">
            <i data-feather="search"></i> Find
        </button>

        <!-- Show All -->
        <button id="btnShowAll" class="btn btn-secondary w-100 w-sm-auto mb-2 btn-lg">Show All</button>
    </div>
</div>

<table id="user_jobtitle_auto_suggest" class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Employee Name</th>
            <th>Job Titles</th>
            <th>Org Group Name</th>
            <th>Line</th>
            <th>Achieved</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<!-- Modal Master Department -->
<div class="modal fade" id="modalDepartment" tabindex="-1" aria-labelledby="modalDepartmentLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDepartmentLabel">Select Department</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table id="tableDepartment" class="table table-striped">
                    <thead></thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Details -->
<div class="modal fade" id="modalDetails" tabindex="-1" aria-labelledby="modalDetailsLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetailsLabel">Dashboard</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex flex-column">
                <div class="flex-grow-1">
                    <!-- Main page content-->
                    <div class="container-xl px-4">
                        <div class="row">
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
                                            <div class="col-xl-8 col-xxl-12 text-center text-xl-start mb-4 mb-xl-0 mb-xxl-4">
                                                <h1 class="text-primary" id="txtFullName">Nama Lengkap</h1>
                                                <p class="text-gray-700 mb-0" id="txtJobTitle">Jabatan</p>
                                            </div>
                                            <div class="col-xl-4 col-xxl-12 text-center">
                                                <img class="img-fluid rounded-circle" id="txtPhoto" src="<?php echo base_url('assets/img/illustrations/at-work.svg'); ?>" style="max-width: 100%; height: auto;" alt="User Photo" />
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
                                            <a href="javascript:void(0);" id="fc_clickable" class="text-decoration-none">
                                                Functional Competencies
                                            </a>
                                            <span class="float-end fw-bold" id="fc_percent">50%</span>
                                        </h4>
                                        <div class="small text-muted" id="txtJobTitleToLine">Packing Operator</div>
                                        <div class="progress mb-4">
                                            <div id="fc_pb" class="progress-bar bg-danger" role="progressbar" style="width: 50%"
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

                                        <!-- Indiactors -->
                                        <div class="mb-3">
                                            <div class="form-group">
                                                <label for="indicators">Indicators</label>
                                                <div id="indicators">
                                                    <!-- Checkbox for each indicator will be populated here -->
                                                </div>
                                            </div>
                                        </div>
                                        <div id="competenciesAccordion" class="accordion mt-3"></div>

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
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit user_jobtitle -->
<div class="modal fade" id="editUserJobTitleModal" tabindex="-1" aria-labelledby="editUserJobTitleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserJobTitleModalLabel">Edit User Job Title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editUserJobTitleForm">
                    <div class="mb-3">
                        <label for="userJobTitleId" class="form-label">Job Title ID</label>
                        <input type="number" class="form-control" id="userJobTitleId" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="intUserID" class="form-label">User ID</label>
                        <input type="number" class="form-control" id="intUserID" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="userName" class="form-label">User Name</label>
                        <input type="text" class="form-control" id="userName" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="jobTitle" class="form-label">Job Title</label>
                        <input type="text" class="form-control" id="jobTitle" readonly>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="achieved">
                        <label class="form-check-label" for="achieved">Achieved</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="active">
                        <label class="form-check-label" for="active">Active</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveChanges">Save Changes</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>