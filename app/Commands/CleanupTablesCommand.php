<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CleanupTablesCommand extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:maintenance';
    protected $description = 'Database Maintenance: Reset, Patch, Cleanup, Upgrade (all-in-one)';

    public function run(array $params)
    {
        CLI::write("\n=== DATABASE MAINTENANCE MENU ===", 'yellow');
        CLI::write("1. Reset ALL (Drop, Recreate, Seed)");
        CLI::write("2. Patch Table Names (Standardize)");
        CLI::write("3. Cleanup Duplicate Tables");
        CLI::write("4. Upgrade Structure (Views/References)");
        CLI::write("0. Exit");
        $choice = CLI::prompt('Pilih menu (1/2/3/4/0)', [0,1,2,3,4]);
        switch ($choice) {
            case 1:
                $this->resetAll();
                break;
            case 2:
                $this->patchTableNames();
                break;
            case 3:
                $this->cleanupDuplicates();
                break;
            case 4:
                $this->upgradeStructure();
                break;
            default:
                CLI::write('Batal/keluar.', 'yellow');
        }
    }

    // 1. Reset All: Drop all tables, migrate, seed
    private function resetAll()
    {
        CLI::write("\n[RESET ALL] Drop all tables, migrate, dan seed data awal...", 'red');
        if (CLI::prompt('Yakin ingin RESET ALL? Semua data akan HILANG! (y/n)', ['y','n']) !== 'y') {
            CLI::write('Batal.', 'yellow');
            return;
        }
        \Config\Services::migrations()->regress(0);
        \Config\Services::migrations()->latest();
        $seeder = \Config\Database::seeder();
        $seeder->call('MroleSeeder');
        $seeder->call('ServiceTypeSeeder');
        $seeder->call('MultiTenantSeeder');
        $seeder->call('UserSeeder');
        $seeder->call('MasterDataSeeder');
        $seeder->call('TransactionSeeder');
        CLI::write('RESET ALL selesai!', 'green');
    }

    // 2. Patch Table Names: Standarisasi nama tabel lama ke baru
    private function patchTableNames()
    {
        CLI::write("\n[PATCH] Standarisasi nama tabel...", 'yellow');
        $tableMappings = [
            'mrole' => 'm_role',
            'tusers' => 'm_user',
            'tcategories' => 'm_category',
            'tproducts' => 'm_product',
            'torders' => 'm_order',
            'service_types' => 'm_service_types',
            'tenants' => 'm_tenants',
        ];
        $db = \Config\Database::connect();
        $forge = \Config\Database::forge();
        $db->query('SET FOREIGN_KEY_CHECKS = 0;');
        $existingTables = $db->listTables();
        foreach ($tableMappings as $old => $new) {
            if (in_array($old, $existingTables) && !in_array($new, $existingTables)) {
                CLI::write("Renaming $old -> $new", 'green');
                $forge->renameTable($old, $new);
            }
        }
        $db->query('SET FOREIGN_KEY_CHECKS = 1;');
        CLI::write('Patch selesai!', 'green');
    }

    // 3. Cleanup Duplicate Tables: Migrasi data jika perlu, lalu drop
    private function cleanupDuplicates()
    {
        CLI::write("\n[CLEANUP] Cek dan hapus tabel duplikat...", 'yellow');
        $tablesToCheck = [
            'service_types' => 'm_service_types',
            'tenants' => 'm_tenants',
            'torders' => 'm_order',
        ];
        $db = \Config\Database::connect();
        $forge = \Config\Database::forge();
        foreach ($tablesToCheck as $oldTable => $newTable) {
            if ($db->tableExists($oldTable)) {
                CLI::write("Found duplicate table: {$oldTable}.", 'yellow');
                if ($db->tableExists($newTable)) {
                    $oldCount = $db->table($oldTable)->countAllResults();
                    $newCount = $db->table($newTable)->countAllResults();
                    if ($oldCount > 0 && $newCount === 0) {
                        CLI::write("Migrating {$oldCount} records from {$oldTable} to {$newTable}...", 'green');
                        try {
                            $db->query("INSERT INTO {$newTable} SELECT * FROM {$oldTable}");
                            CLI::write("Data migration successful!", 'green');
                        } catch (\Exception $e) {
                            CLI::error("Failed to migrate data: " . $e->getMessage());
                        }
                    }
                }
                try {
                    CLI::write("Dropping {$oldTable}...", 'yellow');
                    $forge->dropTable($oldTable, true);
                    CLI::write("{$oldTable} dropped successfully!", 'green');
                } catch (\Exception $e) {
                    CLI::error("Error dropping {$oldTable}: " . $e->getMessage());
                }
            }
        }
        CLI::write('Cleanup selesai!', 'green');
    }

    // 4. Upgrade Structure: Buat view untuk backward compatibility
    private function upgradeStructure()
    {
        CLI::write("\n[UPGRADE] Membuat view untuk kompatibilitas lama...", 'yellow');
        $db = \Config\Database::connect();
        $views = [
            'tenants' => 'm_tenants',
            'service_types' => 'm_service_types',
        ];
        foreach ($views as $view => $table) {
            if ($db->tableExists($table)) {
                try {
                    $db->query("CREATE OR REPLACE VIEW {$view} AS SELECT * FROM {$table}");
                    CLI::write("View {$view} created for {$table}", 'green');
                } catch (\Exception $e) {
                    CLI::error("Error creating view {$view}: " . $e->getMessage());
                }
            }
        }
        CLI::write('Upgrade selesai!', 'green');
    }
}
