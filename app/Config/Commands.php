<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Commands extends BaseConfig
{
    /**
     * --------------------------------------------------------------------------
     * Available Commands
     * --------------------------------------------------------------------------
     *
     * @var array
     */
    public $commands = [
        'db:maintenance' => \App\Commands\CleanupTablesCommand::class,
        'db:update-menu' => \App\Commands\UpdateMenuCommand::class,
    ];
}
