<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuModel extends Model
{
    protected $table = 'm_menu';
    protected $primaryKey = 'intMenuID';    protected $allowedFields = [
        'txtMenuName',
        'txtMenuLink',
        'intParentID',
        'intSortOrder',
        'txtIcon',
        'bitActive',
        'txtDesc',
        'txtGUID'
    ];
    
    // Fungsi untuk mengambil menu berdasarkan Role ID    
    public function getMenusByRole($intRoleID)
    {
        if (empty($intRoleID)) {
            log_message('error', 'Invalid role ID provided: ' . var_export($intRoleID, true));
            return [];
        }
        
        log_message('debug', 'Getting menus for role ID: ' . $intRoleID);
        
        try {
            // Get role name for better logging
            $roleName = 'Unknown';
            $roleData = $this->db->table('m_role')
                ->select('txtRoleName')
                ->where('intRoleID', $intRoleID)
                ->get();
            
            if ($roleData && ($role = $roleData->getRowArray())) {
                $roleName = $role['txtRoleName'] ?? 'Unknown';
            }
              // Get menu items for this role
            $sql = "SELECT m.* 
                    FROM m_menu m
                    JOIN m_role_menu rm ON rm.intMenuID = m.intMenuID
                    WHERE rm.intRoleID = ? 
                    AND m.bitActive = 1
                    ORDER BY COALESCE(m.intParentID, 0), m.intSortOrder ASC";
            
            log_message('debug', "Executing SQL: " . $sql . " with roleID: " . $intRoleID);
            
            $menus = $this->db->query($sql, [$intRoleID]);
    
            if (!$menus) {
                log_message('error', "Failed to get menus for role {$roleName} (ID: {$intRoleID}): " . json_encode($this->db->error()));
                return [];
            }
    
            $menus = $menus->getResultArray();
            $totalMenus = count($menus);
            
            // Check for parent menus
            $parentMenuCount = 0;
            foreach ($menus as $menu) {
                if ($menu['intParentID'] === null || $menu['intParentID'] == 0) {
                    $parentMenuCount++;
                }
            }
            
            log_message('debug', "Role {$roleName} (ID: {$intRoleID}) has {$totalMenus} total menu items, {$parentMenuCount} parent menus");
            
            // If no parent menus, warn in the logs
            if ($parentMenuCount == 0 && $totalMenus > 0) {
                log_message('warning', "Role {$roleName} (ID: {$intRoleID}) has {$totalMenus} menu items but NO PARENT MENUS");
            }
    
            // Group menus by their parent ID
            $menuGroups = [];
            foreach ($menus as $menu) {
                $parentID = $menu['intParentID'] === null ? 0 : (int)$menu['intParentID'];
                if (!isset($menuGroups[$parentID])) {
                    $menuGroups[$parentID] = [];
                }
                $menuGroups[$parentID][] = $menu;
            }
    
            // Sort each menu group by intSortOrder
            foreach ($menuGroups as $parentID => &$group) {
                usort($group, function($a, $b) {
                    return ($a['intSortOrder'] ?? 0) - ($b['intSortOrder'] ?? 0);
                });
            }
            
            return $menuGroups;
            
        } catch (\Exception $e) {
            log_message('error', "Exception while getting menus for role {$intRoleID}: " . $e->getMessage());
            return [];
        }
    }

    // Fungsi untuk mendapatkan semua menu dengan struktur hirarki
    public function getMenuHierarchy()
    {
        $menus = $this->where('bitActive', 1)
            ->orderBy('intSortOrder', 'ASC')
            ->findAll();

        return $this->buildMenuHierarchy($menus);
    }

    // Fungsi helper untuk membuat hirarki menu
    private function buildMenuHierarchy($menus, $parentID = null)
    {
        $result = [];
        foreach ($menus as $menu) {
            if ($menu['intParentID'] == $parentID) {
                $children = $this->buildMenuHierarchy($menus, $menu['intMenuID']);
                if ($children) {
                    $menu['children'] = $children;
                }
                $result[] = $menu;
            }
        }
        return $result;
    }
}
