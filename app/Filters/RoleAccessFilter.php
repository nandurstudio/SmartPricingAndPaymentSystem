<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleAccessFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $roleID = session()->get('roleID');
        $menuName = $request->getUri()->getPath(); // Ambil nama menu dari URL

        $roleAccessModel = new \App\Models\RoleAccessModel();
        $access = $roleAccessModel->where('intRoleID', $roleID)
            ->where('txtMenuName', $menuName)
            ->first();

        if (!$access || !$access['bitCanView']) {
            return redirect()->to('/unauthorized'); // Redirect ke halaman unauthorized jika tidak memiliki akses
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
