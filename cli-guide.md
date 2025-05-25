# Menjalankan semua migrasi
php spark migrate

# Rollback migrasi terakhir
php spark migrate:rollback

# Refresh database (rollback semua dan migrate ulang)
php spark migrate:refresh

# Reset database (rollback semua migrasi)
php spark migrate:reset

# Cek status migrasi
php spark migrate:status

# Menjalankan seeder (roles, multi-tenant, dst)
php spark db:seed MroleSeeder
php spark db:seed MultiTenantSeeder
# Tambahkan seeder lain sesuai kebutuhan, misal:
# php spark db:seed ServiceTypeSeeder
# php spark db:seed TenantSeeder
# php spark db:seed UserSeeder
# php spark db:seed MasterDataSeeder
# php spark db:seed TransactionSeeder

# Menjalankan command maintenance (multi-tool)
php spark db:maintenance
# Menu interaktif: Reset ALL, Patch Table Names, Cleanup Duplicate Tables, Upgrade Structure

# (Opsional) Command custom pipeline
php spark db setup     # Setup database dengan migrasi dan seeding
php spark db refresh   # Refresh database
php spark db reset     # Reset database
php spark db seed      # Hanya jalankan seeder
