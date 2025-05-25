<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class PerformanceFilter implements FilterInterface
{
    protected $startTime;

    public function before(RequestInterface $request, $arguments = null)
    {
        $this->startTime = microtime(true);
        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $endTime = microtime(true);
        $executionTime = ($endTime - $this->startTime) * 1000; // Convert to milliseconds

        // Add execution time header
        $response->setHeader('X-Execution-Time', number_format($executionTime, 2) . 'ms');

        // Add memory usage header
        $response->setHeader('X-Memory-Usage', round(memory_get_peak_usage() / 1048576, 2) . 'MB');

        return $response;
    }
}
