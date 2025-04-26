<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuModel extends Model
{
    protected $table = 'mMenu';
    protected $primaryKey = 'intMenuID';
    protected $allowedFields = [
        'txtMenuName',
        'txtMenuLink',
        'intParentID',
        'intSortOrder',
        'txtIcon',
        'txtDesc',
        'bitActive',
        'txtInsertedBy',
        'dtmInsertedDate',
        'txtUpdatedBy',
        'dtmUpdatedDate',
        'txtGUID'
    ];

    // Fungsi untuk mengambil menu berdasarkan Role ID
    public function getMenusByRole($intRoleID)
    {
        return $this->db->table('mMenu')
            ->select('mMenu.*, trRoleMenuAccess.bitCanView')
            ->join('trRoleMenuAccess', 'trRoleMenuAccess.intMenuID = mMenu.intMenuID')
            ->where('trRoleMenuAccess.intRoleID', $intRoleID)
            ->where('trRoleMenuAccess.bitCanView', 1) // Menampilkan hanya menu yang bisa dilihat
            ->orderBy('mMenu.intSortOrder', 'ASC')
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
