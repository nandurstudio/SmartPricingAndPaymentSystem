<nav class="topnav navbar navbar-expand shadow justify-content-between justify-content-sm-start navbar-light bg-white" id="sidenavAccordion">
    <!-- Sidenav Toggle Button-->
    <button class="btn btn-icon btn-transparent-dark order-1 order-lg-0 me-2 ms-lg-2 me-lg-0" id="sidebarToggle">
        <i class="bi bi-list"></i>
    </button>
    <!-- Navbar Brand-->
    <a class="navbar-brand pe-3 ps-4 ps-lg-2" href="/">Smart Booking System</a>
    <!-- Navbar Search Input-->
    <form class="form-inline me-auto d-none d-lg-block me-3">
        <div class="input-group input-group-joined input-group-solid" id="search_hide">
            <input class="form-control pe-0" id="searchInput" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-text"><i class="bi bi-search"></i></div>
        </div>
    </form>
    <!-- Navbar Items-->
    <ul class="navbar-nav align-items-center ms-auto">
        <!-- Documentation Dropdown-->
        <li class="nav-item dropdown no-caret d-none d-md-block me-3">
            <a class="nav-link dropdown-toggle" id="navbarDropdownDocs" href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="fw-500">Documentation</div>
                <i class="bi bi-chevron-right dropdown-arrow"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-end py-0 me-sm-n15 me-lg-0 o-hidden animated--fade-in-up" aria-labelledby="navbarDropdownDocs">
                <a class="dropdown-item py-3" href="https://docs.startbootstrap.com/sb-admin-pro" target="_blank">
                    <div class="icon-stack bg-primary-soft text-primary me-4"><i class="bi bi-book"></i></div>
                    <div>
                        <div class="small text-gray-500">Documentation</div>
                        Usage instructions and reference
                    </div>
                </a>
                <div class="dropdown-divider m-0"></div>
                <a class="dropdown-item py-3" href="https://docs.startbootstrap.com/sb-admin-pro/components" target="_blank">
                    <div class="icon-stack bg-primary-soft text-primary me-4"><i class="bi bi-code-square"></i></div>
                    <div>
                        <div class="small text-gray-500">Components</div>
                        Code snippets and reference
                    </div>
                </a>
                <div class="dropdown-divider m-0"></div>
                <a class="dropdown-item py-3" href="https://docs.startbootstrap.com/sb-admin-pro/changelog" target="_blank">
                    <div class="icon-stack bg-primary-soft text-primary me-4"><i class="bi bi-file-text"></i></div>
                    <div>
                        <div class="small text-gray-500">Changelog</div>
                        Updates and changes
                    </div>
                </a>
            </div>
        </li>
        <!-- Navbar Search Dropdown-->
        <!-- * * Note: * * Visible only below the lg breakpoint-->
        <li class="nav-item dropdown no-caret me-3 d-lg-none">
            <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="searchDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bi bi-search"></i></a>
            <!-- Dropdown - Search-->
            <div class="dropdown-menu dropdown-menu-end p-3 shadow animated--fade-in-up" aria-labelledby="searchDropdown">
                <form class="form-inline me-auto w-100">
                    <div class="input-group input-group-joined input-group-solid">
                        <input class="form-control pe-0" type="text" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2" />
                        <div class="input-group-text"><i class="bi bi-search"></i></div>
                    </div>
                </form>
            </div>
        </li>
        <!-- Alerts Dropdown-->
        <li class="nav-item dropdown no-caret d-none d-sm-block me-3 dropdown-notifications">
            <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownAlerts" href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bi bi-bell"></i></a>
            <div class="dropdown-menu dropdown-menu-end border-0 shadow animated--fade-in-up" aria-labelledby="navbarDropdownAlerts">
                <h6 class="dropdown-header dropdown-notifications-header">
                    <i class="bi bi-bell me-2"></i>
                    Alerts Center
                </h6>
                <!-- Example Alert 1-->
                <a class="dropdown-item dropdown-notifications-item" href="#!">
                    <div class="dropdown-notifications-item-icon bg-warning"><i class="bi bi-exclamation-triangle"></i></div>
                    <div class="dropdown-notifications-item-content">
                        <div class="dropdown-notifications-item-content-details">December 29, 2021</div>
                        <div class="dropdown-notifications-item-content-text">This is an alert message. It's nothing serious, but it requires your attention.</div>
                    </div>
                </a>
                <!-- Example Alert 2-->
                <a class="dropdown-item dropdown-notifications-item" href="#!">
                    <div class="dropdown-notifications-item-icon bg-info"><i class="bi bi-info-circle"></i></div>
                    <div class="dropdown-notifications-item-content">
                        <div class="dropdown-notifications-item-content-details">December 22, 2021</div>
                        <div class="dropdown-notifications-item-content-text">A new monthly report is ready. Click here to view!</div>
                    </div>
                </a>
                <!-- Example Alert 3-->
                <a class="dropdown-item dropdown-notifications-item" href="#!">
                    <div class="dropdown-notifications-item-icon bg-danger"><i class="bi bi-x-circle"></i></div>
                    <div class="dropdown-notifications-item-content">
                        <div class="dropdown-notifications-item-content-details">December 8, 2021</div>
                        <div class="dropdown-notifications-item-content-text">Critical system failure, systems shutting down.</div>
                    </div>
                </a>
                <!-- Example Alert 4-->
                <a class="dropdown-item dropdown-notifications-item" href="#!">
                    <div class="dropdown-notifications-item-icon bg-success"><i class="bi bi-check-circle"></i></div>
                    <div class="dropdown-notifications-item-content">
                        <div class="dropdown-notifications-item-content-details">December 2, 2021</div>
                        <div class="dropdown-notifications-item-content-text">New user request. Woody has requested access to the organization.</div>
                    </div>
                </a>
                <a class="dropdown-item dropdown-notifications-footer" href="#!">View All Alerts</a>
            </div>
        </li>
        <!-- Messages Dropdown-->
        <li class="nav-item dropdown no-caret d-none d-sm-block me-3 dropdown-notifications">
            <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownMessages" href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bi bi-envelope"></i></a>
            <div class="dropdown-menu dropdown-menu-end border-0 shadow animated--fade-in-up" aria-labelledby="navbarDropdownMessages">
                <h6 class="dropdown-header dropdown-notifications-header">
                    <i class="bi bi-envelope me-2"></i>
                    Message Center
                </h6>
                <!-- Example Message 1  -->
                <a class="dropdown-item dropdown-notifications-item" href="#!">
                    <img class="dropdown-notifications-item-img" src="<?php echo base_url('assets/img/illustrations/profiles/profile-2.png'); ?>" />
                    <div class="dropdown-notifications-item-content">
                        <div class="dropdown-notifications-item-content-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
                        <div class="dropdown-notifications-item-content-details">Thomas Wilcox · 58m</div>
                    </div>
                </a>
                <!-- Example Message 2-->
                <a class="dropdown-item dropdown-notifications-item" href="#!">
                    <img class="dropdown-notifications-item-img" src="<?php echo base_url('assets/img/illustrations/profiles/profile-3.png'); ?>" />
                    <div class="dropdown-notifications-item-content">
                        <div class="dropdown-notifications-item-content-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
                        <div class="dropdown-notifications-item-content-details">Emily Fowler · 2d</div>
                    </div>
                </a>
                <!-- Example Message 3-->
                <a class="dropdown-item dropdown-notifications-item" href="#!">
                    <img class="dropdown-notifications-item-img" src="<?php echo base_url('assets/img/illustrations/profiles/profile-4.png'); ?>" />
                    <div class="dropdown-notifications-item-content">
                        <div class="dropdown-notifications-item-content-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
                        <div class="dropdown-notifications-item-content-details">Marshall Rosencrantz · 3d</div>
                    </div>
                </a>
                <!-- Example Message 4-->
                <a class="dropdown-item dropdown-notifications-item" href="#!">
                    <img class="dropdown-notifications-item-img" src="<?php echo base_url('assets/img/illustrations/profiles/profile-5.png'); ?>" />
                    <div class="dropdown-notifications-item-content">
                        <div class="dropdown-notifications-item-content-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
                        <div class="dropdown-notifications-item-content-details">Colby Newton · 3d</div>
                    </div>
                </a>
                <!-- Footer Link-->
                <a class="dropdown-item dropdown-notifications-footer" href="#!">Read All Messages</a>
            </div>
        </li>
        <!-- User Dropdown-->
        <li class="nav-item dropdown no-caret dropdown-user me-3 me-lg-4">
            <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownUserImage" href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">                <?php 
                    $profilePic = get_profile_picture(session()->get('photo'));
                ?>
                <img class="img-fluid" src="<?= esc($profilePic) ?>" alt="Profile" />
            </a>
            <div class="dropdown-menu dropdown-menu-end border-0 shadow animated--fade-in-up" aria-labelledby="navbarDropdownUserImage">                <h6 class="dropdown-header d-flex align-items-center">
                    <img class="dropdown-user-img" src="<?= esc($profilePic) ?>" alt="Profile" />
                    <div class="dropdown-user-details">
                        <div class="dropdown-user-details-name"><?= esc(session()->get('userFullName')); ?></div>
                        <div class="dropdown-user-details-email"><?= esc(session()->get('userEmail')); ?></div>
                    </div>
                </h6>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="<?= base_url('settings/profile') ?>">
                    <div class="dropdown-item-icon"><i class="bi bi-gear"></i></div>
                    My Profile
                </a>
                <?php if (session()->get('intTenantID')): ?>
                <a class="dropdown-item" href="<?= base_url('tenants/view/' . session()->get('intTenantID')) ?>">
                    <div class="dropdown-item-icon"><i class="bi bi-building"></i></div>
                    My Tenant
                </a>
                <?php endif; ?>
                <a class="dropdown-item" href="<?= base_url('/logout') ?>">
                    <div class="dropdown-item-icon"><i class="bi bi-box-arrow-right"></i></div>
                    Sign Out
                </a>
            </div>
        </li>
    </ul>
</nav>