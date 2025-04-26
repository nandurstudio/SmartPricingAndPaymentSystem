<div id="layoutSidenav_nav">
    <nav class="sidenav shadow-right sidenav-light">
        <div class="sidenav-menu">
            <div class="nav accordion" id="accordionSidenav">
                <?php foreach ($menus as $menu): ?>
                    <?php
                        // Memeriksa apakah menu ini aktif
                        $isActive = ($menu['txtMenuLink'] && current_url() == base_url($menu['txtMenuLink'])) ? 'active' : '';
                        $hasActiveSubmenu = false; // Menandakan apakah ada submenu yang aktif

                        // Jika menu ini adalah parent (tidak memiliki link), cek submenu-nya
                        if (!$menu['txtMenuLink']) {
                            foreach ($menus as $submenu) {
                                if ($submenu['intParentID'] == $menu['intMenuID'] && current_url() == base_url($submenu['txtMenuLink'])) {
                                    $hasActiveSubmenu = true;
                                    break;
                                }
                            }
                        }
                    ?>
                    <?php if ($menu['intParentID'] == 0): ?>
                        <?php
                            // Menambahkan 'active' pada parent menu jika ada submenu yang aktif
                            $parentActiveClass = $isActive || $hasActiveSubmenu ? 'active' : '';
                        ?>
                        <?php if ($menu['isHeading'] == 1): ?>
                            <!-- Menu Heading -->
                            <div class="sidenav-menu-heading"><?= $menu['txtMenuName'] ?></div>
                        <?php else: ?>
                            <!-- Parent Menu -->
                            <a class="nav-link <?= $parentActiveClass; ?> <?= $menu['txtMenuLink'] ? '' : 'collapsed' ?>" 
                               href="<?= $menu['txtMenuLink'] ?: 'javascript:void(0);' ?>"
                               <?= $menu['txtMenuLink'] ? '' : 'data-bs-toggle="collapse" data-bs-target="#collapse' . $menu['intMenuID'] . '"' ?>
                               aria-expanded="<?= $hasActiveSubmenu ? 'true' : 'false'; ?>" 
                               aria-controls="collapse<?= $menu['intMenuID'] ?>">
                                <div class="nav-link-icon"><i data-feather="<?= $menu['txtIcon'] ?>"></i></div>
                                <?= $menu['txtMenuName'] ?>
                                <?php if (!$menu['txtMenuLink']): ?>
                                    <div class="sidenav-collapse-arrow"><i data-feather="chevron-down"></i></div>
                                <?php endif; ?>
                            </a>

                            <!-- Submenu jika Parent Menu tidak memiliki link -->
                            <?php if (!$menu['txtMenuLink']): ?>
                                <div class="collapse <?= $hasActiveSubmenu ? 'show' : ''; ?>" id="collapse<?= $menu['intMenuID'] ?>" data-bs-parent="#accordionSidenav">
                                    <nav class="sidenav-menu-nested nav">
                                        <?php foreach ($menus as $submenu): ?>
                                            <?php if ($submenu['intParentID'] == $menu['intMenuID']): ?>
                                                <a class="nav-link <?= ($submenu['txtMenuLink'] && current_url() == base_url($submenu['txtMenuLink'])) ? 'active' : ''; ?>" href="<?= $submenu['txtMenuLink'] ?>"><?= $submenu['txtMenuName'] ?></a>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </nav>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Sidenav Footer-->
        <div class="sidenav-footer">
            <div class="sidenav-footer-content">
                <div class="sidenav-footer-subtitle">Logged in as:</div>
                <?php if (session()->get('isLoggedIn')): ?>
                    <div class="sidenav-footer-title"><?= esc(session()->get('userFullName')); ?></div>
                <?php else: ?>
                    <div class="sidenav-footer-title">Please login first.</div>
                <?php endif; ?>
            </div>
        </div>
    </nav>
</div>
