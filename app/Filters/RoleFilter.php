<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    /**
     * Verifies if user has required role to access the route
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Make sure we have arguments
        if (empty($arguments)) {
            return $request;
        }

        // Convert string argument to array if needed
        if (is_string($arguments)) {
            $roles = explode(',', $arguments);
        } else {
            $roles = $arguments;
        }

        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        // Get user role
        $userRole = session()->get('user')['role'] ?? null;
        
        // If no role found, try getting from roleID
        if (!$userRole) {
            $roleMap = [
                1 => 'admin',
                2 => 'customer',
                3 => 'tenant_owner',
                4 => 'tenant_staff'
            ];
            $userRole = $roleMap[session()->get('roleID')] ?? null;
        }

        // Check if user role is in the allowed roles
        if (!in_array($userRole, $roles)) {
            return redirect()->to('/dashboard')->with('error', 'Access denied');
        }

        return $request;
    }

    /**
     * We don't have anything to do here.
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return $response;
    }
}
