@echo off
echo ========================================================
echo        SMART PRICING SYSTEM - DATABASE RESET/INIT
echo ========================================================
echo.
echo This will:
echo  1. Rollback all migrations
echo  2. Run all migrations (fresh structure)
echo  3. Seed initial data
echo.
echo WARNING: ALL EXISTING DATA WILL BE PERMANENTLY DELETED!
echo.

choice /C YN /M "Are you sure you want to proceed?"
if errorlevel 2 goto :cancelled
if errorlevel 1 goto :proceed

:proceed
echo.
echo Starting database reset and initialization...
php spark db setup
REM Atau: php spark db:maintenance
goto :end

:cancelled
echo.
echo Initialization cancelled.

:end
echo.
echo ========================================================
pause
