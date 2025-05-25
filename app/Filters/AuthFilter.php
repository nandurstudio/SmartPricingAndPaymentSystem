<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // If user is not logged in, redirect to login page
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login')->with('error', 'Please login first');
        }

        // Check if user is active
        if (!session()->get('bitActive')) {
            session()->destroy();
            return redirect()->to('/auth/login')->with('error', 'Your account is not active');
        }

        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing after the controller is executed
        return $response;
    }
}
