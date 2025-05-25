<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class ForceHTTPSFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // During development, allow both HTTP and HTTPS
        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return $response;
    }
}
