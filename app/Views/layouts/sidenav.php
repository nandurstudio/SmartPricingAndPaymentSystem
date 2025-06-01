<div id="layoutSidenav_nav">
    <nav class="sidenav shadow-right sidenav-light">
        <div class="sidenav-menu">
            <div class="nav accordion" id="accordionSidenav">
                <?php 
                $currentUrl = current_url();
                $baseUrl = base_url();
                
                // Group menus by parent
                $menuGroups = [];                if (!empty($menus)) {
                    foreach ($menus as $menu) {
                        $parentId = $menu['intParentID'] ?? 0;
                        $menuGroups[$parentId][] = $menu;
                    }
                } else {
                    $menuGroups = [];
                }
                
                // Function to check if menu or its children are active
                function isMenuActive($menu, $menus, $currentUrl, $baseUrl) {
                    if (!empty($menu['txtMenuLink']) && $currentUrl == $baseUrl . $menu['txtMenuLink']) {
                        return true;
                    }
                    
                    // Check children if this is a parent menu
                    foreach ($menus as $childMenu) {
                        if ($childMenu['intParentID'] == $menu['intMenuID'] 
                            && $currentUrl == $baseUrl . $childMenu['txtMenuLink']) {
                            return true;
                        }
                    }
                    return false;
                }
                
                // Render main level menus
                foreach ($menuGroups[0] ?? [] as $menu): 
                    $isActive = isMenuActive($menu, $menus, $currentUrl, $baseUrl);
                    $hasChildren = isset($menuGroups[$menu['intMenuID']]);
                ?>
                    
                    <!-- Parent Menu Item -->
                    <a class="nav-link <?= $isActive ? 'active' : '' ?> <?= $hasChildren ? 'collapsed' : '' ?>" 
                       href="<?= $menu['txtMenuLink'] ? base_url($menu['txtMenuLink']) : 'javascript:void(0);' ?>"
                       <?php if ($hasChildren): ?>
                       data-bs-toggle="collapse" 
                       data-bs-target="#collapse<?= $menu['intMenuID'] ?>"
                       aria-expanded="<?= $isActive ? 'true' : 'false' ?>"
                       <?php endif; ?>>
                        <div class="nav-link-icon">
                            <i data-feather="<?= $menu['txtIcon'] ?>"></i>
                        </div>
                        <?= $menu['txtMenuName'] ?>
                        <?php if ($hasChildren): ?>
                            <div class="sidenav-collapse-arrow">
                                <i data-feather="chevron-down"></i>
                            </div>
                        <?php endif; ?>
                    </a>

                    <!-- Submenu Items -->
                    <?php if ($hasChildren): ?>
                        <div class="collapse <?= $isActive ? 'show' : '' ?>" 
                             id="collapse<?= $menu['intMenuID'] ?>" 
                             data-bs-parent="#accordionSidenav">
                            <nav class="sidenav-menu-nested nav">
                                <?php foreach ($menuGroups[$menu['intMenuID']] as $submenu): 
                                    $isSubmenuActive = $currentUrl == $baseUrl . $submenu['txtMenuLink'];
                                ?>
                                    <a class="nav-link <?= $isSubmenuActive ? 'active' : '' ?>" 
                                       href="<?= base_url($submenu['txtMenuLink']) ?>">
                                        <div class="nav-link-icon">
                                            <i data-feather="<?= $submenu['txtIcon'] ?>"></i>
                                        </div>
                                        <?= $submenu['txtMenuName'] ?>
                                    </a>
                                <?php endforeach; ?>
                            </nav>
                        </div>
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
                    <small class="text-muted"><?= esc(session()->get('roleName')); ?></small>
                <?php else: ?>
                    <div class="sidenav-footer-title">Please login first.</div>
                <?php endif; ?>
            </div>
        </div>
    </nav>
</div>

<script>
// Initialize Feather icons
document.addEventListener('DOMContentLoaded', function() {
    feather.replace();
});
</script>
