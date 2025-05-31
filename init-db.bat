@echo off
REM Backup existing .env file if it exists
if exist .env (
    copy .env .env.backup
)

REM Create or update .env file with database configuration
echo DB_HOSTNAME=localhost > .env
echo DB_USERNAME=root >> .env
echo DB_PASSWORD= >> .env
echo DB_DATABASE=db_smartpricingandpaymentsystem >> .env
echo DB_DRIVER=MySQLi >> .env
echo DB_PORT=3306 >> .env

REM Drop and recreate database
mysql -u root -e "DROP DATABASE IF EXISTS db_smartpricingandpaymentsystem; CREATE DATABASE db_smartpricingandpaymentsystem;"

REM Run migrations and seeders
php spark migrate
php spark db:seed InitialSetupSeeder

echo Database initialization complete!
