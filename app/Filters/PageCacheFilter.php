<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class PageCacheFilter implements FilterInterface
{
    protected $cacheTime = 300; // 5 minutes default

    public function before(RequestInterface $request, $arguments = null)
    {
        // Only cache GET requests
        if ($request->getMethod() !== 'get') {
            return $request;
        }

        // Don't cache for logged in users
        if (session()->get('isLoggedIn')) {
            return $request;
        }

        $key = $this->generateCacheKey($request);
        $cache = Services::cache();

        if ($cachedResponse = $cache->get($key)) {
            $response = Services::response();
            $response->setBody($cachedResponse['body']);
            
            // Set cached headers
            if (isset($cachedResponse['headers']) && is_array($cachedResponse['headers'])) {
                foreach ($cachedResponse['headers'] as $header) {
                    if (isset($header['name']) && isset($header['value'])) {
                        $response->setHeader($header['name'], $header['value']);
                    }
                }
            }
            
            return $response;
        }

        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Only cache successful GET requests
        if ($request->getMethod() !== 'get' || $response->getStatusCode() !== 200) {
            return $response;
        }

        // Don't cache for logged in users
        if (session()->get('isLoggedIn')) {
            return $response;
        }

        // Don't cache if response has no-cache header
        $cacheControl = $response->getHeaderLine('Cache-Control');
        if (!empty($cacheControl) && strpos($cacheControl, 'no-cache') !== false) {
            return $response;
        }

        $key = $this->generateCacheKey($request);
        $cache = Services::cache();

        // Format headers for caching
        $headers = [];
        foreach ($response->headers() as $name => $header) {
            if ($header instanceof \CodeIgniter\HTTP\Header) {
                $headers[] = [
                    'name' => $name,
                    'value' => $header->getValue()
                ];
            }
        }

        $cacheData = [
            'body' => $response->getBody(),
            'headers' => $headers
        ];

        $cache->save($key, $cacheData, $this->cacheTime);

        return $response;
    }

    protected function generateCacheKey(RequestInterface $request): string
    {
        $uri = $request->getUri();
        $queryString = $uri->getQuery();
        $path = $uri->getPath();

        return 'page_cache_' . md5($path . '?' . $queryString);
    }
}
