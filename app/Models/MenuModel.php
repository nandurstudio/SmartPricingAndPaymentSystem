<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuModel extends Model
{    protected $table = 'm_menu';
    protected $primaryKey = 'intMenuID';
    protected $allowedFields = [
        'txtMenuName',
        'txtMenuLink',
        'intParentID',
        'intSortOrder',
        'txtIcon',
        'bitActive'
    ];    // Fungsi untuk mengambil menu berdasarkan Role ID
    public function getMenusByRole($intRoleID)
    {
        return $this->db->table('m_menu')
            ->select('m_menu.*')
            ->join('m_role_menu', 'm_role_menu.intMenuID = m_menu.intMenuID')
            ->where('m_role_menu.intRoleID', $intRoleID)
            ->where('m_menu.bitActive', 1)
            ->orderBy('m_menu.intSortOrder', 'ASC')
            ->get()
            ->getResultArray();
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
