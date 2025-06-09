<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Midtrans extends BaseConfig
{
    public $serverKey = '';
    public $clientKey = '';
    public $isProduction = false;
    public $isSanitized = true;
    public $is3ds = true;

    public function __construct()
    {
        parent::__construct();

        // Load environment-specific settings
        $this->serverKey = getenv('midtrans.serverKey') ?: '';
        $this->clientKey = getenv('midtrans.clientKey') ?: '';
        $this->isProduction = getenv('midtrans.isProduction') === 'true';
    }
}
