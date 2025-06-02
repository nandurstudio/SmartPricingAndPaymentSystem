<div id="layoutSidenav_nav">
    <nav class="sidenav shadow-right sidenav-light">
        <div class="sidenav-menu">
            <div class="nav accordion" id="accordionSidenav">
                <?php 
                // Add debug info for troubleshooting
                if (ENVIRONMENT === 'development') {
                    echo "<!-- Debug Role ID: " . session()->get('roleID') . " -->";
                    echo "<!-- Debug Role Name: " . session()->get('roleName') . " -->";
                    echo "<!-- Debug Menu Count: " . (isset($menuGroups[0]) ? count($menuGroups[0]) : 0) . " parent menus -->";
                }
                  // Check login status first
                if (!session()->get('isLoggedIn')) {
                    echo '<div class="p-3 text-center text-muted">Please login to access the menu.</div>';
                    return;
                }
                
                // Ensure we have menu data
                $menuGroups = $menuGroups ?? [];
                $currentUrl = current_url();
                $baseUrl = base_url();
                $roleID = session()->get('roleID');
                $roleName = session()->get('roleName');
                
                // If no menus loaded yet, try to load them now as a fallback
                if (empty($menuGroups) && $roleID) {
                    $menuModel = new \App\Models\MenuModel();
                    $menuGroups = $menuModel->getMenusByRole($roleID);
                    // Debug for development
                    if (ENVIRONMENT === 'development') {
                        echo "<!-- Fallback menu load for role {$roleName} (ID: {$roleID}) -->";
                    }
                }
                
                // Find parent menus (those with null or 0 parentID)
                $parentMenus = [];
                if (isset($menuGroups[0])) {
                    $parentMenus = $menuGroups[0];
                    
                    // Check if we have any parent menus
                    if (empty($parentMenus)) {
                        echo '<div class="p-3 text-center text-muted">No menu items available for your role: ' . esc($roleName) . '</div>';
                    } else {
                        // Sort parent menus by sortOrder
                        usort($parentMenus, function($a, $b) {
                            return ($a['intSortOrder'] ?? 0) - ($b['intSortOrder'] ?? 0);
                        });
                        
                        foreach ($parentMenus as $menu):
                            $menuId = $menu['intMenuID'] ?? null;
                            if ($menuId === null) continue; // Skip invalid menus
                            
                            // Check if this menu has children
                            $hasChildren = isset($menuGroups[$menuId]) && !empty($menuGroups[$menuId]);
                            
                            // Get menu properties
                            $menuName = $menu['txtMenuName'] ?? 'Menu';
                            $menuLink = $menu['txtMenuLink'] ?? '';
                            $menuIcon = $menu['txtIcon'] ?? 'circle';
                            
                            // Check if this menu or any of its children is active
                            $isActive = false;
                            if (!empty($menuLink) && (rtrim($currentUrl, '/') == rtrim($baseUrl . $menuLink, '/'))) {
                                $isActive = true;
                            } else if ($hasChildren) {
                                foreach ($menuGroups[$menuId] as $submenu) {
                                    $submenuLink = $submenu['txtMenuLink'] ?? '';
                                    if (!empty($submenuLink) && (rtrim($currentUrl, '/') == rtrim($baseUrl . $submenuLink, '/'))) {
                                        $isActive = true;
                                        break;
                                    }
                                }
                            }
                ?>
                            <!-- Menu Item: <?= $menuName ?> -->
                            <a class="nav-link <?= $isActive ? 'active' : '' ?> <?= $hasChildren ? 'collapsed' : '' ?>" 
                               href="<?= !empty($menuLink) ? base_url($menuLink) : 'javascript:void(0);' ?>"
                               <?php if ($hasChildren): ?>
                               data-bs-toggle="collapse" 
                               data-bs-target="#collapse<?= $menuId ?>"
                               aria-expanded="<?= $isActive ? 'true' : 'false' ?>"
                               aria-controls="collapse<?= $menuId ?>"
                               <?php endif; ?>>
                                <div class="nav-link-icon">
                                    <i data-feather="<?= $menuIcon ?>"></i>
                                </div>
                                <?= $menuName ?>
                                <?php if ($hasChildren): ?>
                                    <div class="sidenav-collapse-arrow">
                                        <i class="fas fa-angle-down"></i>
                                    </div>
                                <?php endif; ?>
                            </a>
                            
                            <?php if ($hasChildren): ?>
                                <!-- Submenu for: <?= $menuName ?> -->
                                <div class="collapse <?= $isActive ? 'show' : '' ?>" 
                                     id="collapse<?= $menuId ?>" 
                                     data-bs-parent="#accordionSidenav">
                                    <nav class="sidenav-menu-nested nav">
                                        <?php 
                                        // Sort submenu by sortOrder
                                        $submenuItems = $menuGroups[$menuId];
                                        usort($submenuItems, function($a, $b) {
                                            return ($a['intSortOrder'] ?? 0) - ($b['intSortOrder'] ?? 0);
                                        });
                                        
                                        foreach ($submenuItems as $submenu): 
                                            $submenuLink = $submenu['txtMenuLink'] ?? '';
                                            if (empty($submenuLink)) continue; // Skip menu items without links
                                            
                                            $submenuName = $submenu['txtMenuName'] ?? 'Submenu';
                                            $submenuIcon = $submenu['txtIcon'] ?? '';
                                            $isSubmenuActive = rtrim($currentUrl, '/') == rtrim($baseUrl . $submenuLink, '/');
                                        ?>
                                            <a class="nav-link <?= $isSubmenuActive ? 'active' : '' ?>" 
                                               href="<?= base_url($submenuLink) ?>">
                                                <?php if (!empty($submenuIcon)): ?>
                                                    <div class="nav-link-icon">
                                                        <i data-feather="<?= $submenuIcon ?>"></i>
                                                    </div>
                                                <?php endif; ?>
                                                <?= $submenuName ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </nav>
                                </div>
                            <?php endif; ?>
                <?php 
                        endforeach;
                    } // End else (if not empty parentMenus)
                } else {
                    echo '<div class="p-3 text-center text-muted">Menu tidak tersedia.</div>';
                }
                ?>
            </div>
        </div>
        
        <!-- Sidenav Footer-->
        <div class="sidenav-footer">
            <div class="sidenav-footer-content">
                <div class="sidenav-footer-subtitle">Logged in as:</div>                <?php if (session()->get('isLoggedIn')): ?>
                    <div class="sidenav-footer-title"><?= esc(session()->get('userFullName')); ?></div>
                    <small class="text-muted">
                        <?php 
                        // Show role name if available, otherwise try to get it from database
                        $roleName = session()->get('roleName');
                        if (!$roleName && session()->get('roleID')) {
                            // Get role name from database
                            $db = \Config\Database::connect();
                            $role = $db->table('m_role')
                                ->where('intRoleID', session()->get('roleID'))
                                ->get()
                                ->getRowArray();
                            if ($role) {
                                $roleName = $role['txtRoleName'];
                                // Update session for future use
                                session()->set('roleName', $roleName);
                            }
                        }
                        echo esc($roleName ?? 'Unknown Role');
                        ?>
                    </small>
                <?php else: ?>
                    <div class="sidenav-footer-title">Please login first.</div>
                <?php endif; ?>
            </div>
        </div>
    </nav>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Feather icons if available
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});
</script>
