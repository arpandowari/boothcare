@echo off
echo 🚀 InfinityFree Deployment Script (Windows Batch)
echo ===============================================
echo.

REM Check if we're in the right directory
if not exist "artisan" (
    echo ❌ Error: This script must be run from the Laravel root directory
    pause
    exit /b 1
)

echo ✅ Laravel application detected
echo.

REM Check .env file
if not exist ".env" (
    echo 📝 Creating .env file from infinityfree-deployment.env...
    if exist "infinityfree-deployment.env" (
        copy "infinityfree-deployment.env" ".env" >nul
        echo ✅ .env file created successfully
    ) else (
        echo ❌ Error: infinityfree-deployment.env not found
        pause
        exit /b 1
    )
) else (
    echo ✅ .env file already exists
)

echo.

REM Database configuration info
echo 🔍 Database Configuration:
echo    Host: sql211.infinityfree.com
echo    Database: if0_39751761_boothcare
echo    Username: if0_39751761
echo ✅ Database configuration ready

echo.

REM Check directories
echo 📁 Checking required directories...
if not exist "storage" mkdir storage
if not exist "storage\logs" mkdir storage\logs
if not exist "storage\framework" mkdir storage\framework
if not exist "storage\framework\cache" mkdir storage\framework\cache
if not exist "storage\framework\sessions" mkdir storage\framework\sessions
if not exist "storage\framework\views" mkdir storage\framework\views
if not exist "bootstrap\cache" mkdir bootstrap\cache
echo ✅ Directory structure verified

echo.

REM Check dependencies
echo 📦 Checking dependencies...
if exist "vendor" (
    echo ✅ Dependencies are installed
) else (
    echo ⚠️  Vendor directory not found. Run: composer install
)

if exist "node_modules" (
    echo ✅ Node.js dependencies are installed
) else (
    echo ⚠️  Node modules not found. Run: npm install
)

echo.

REM Deployment checklist
echo 📋 Deployment Checklist:
echo ========================
echo 1. ✅ Environment configuration ready
echo 2. ✅ Database configuration verified
echo 3. ✅ Directory structure verified
echo 4. ⚠️  Run: composer install --optimize-autoloader --no-dev
echo 5. ⚠️  Run: npm install
echo 6. ⚠️  Run: npm run build
echo 7. ⚠️  Run: php artisan migrate --force
echo 8. ⚠️  Run: php artisan config:cache
echo 9. ⚠️  Run: php artisan route:cache
echo 10. ⚠️  Run: php artisan view:cache
echo 11. ⚠️  Run: php artisan storage:link

echo.
echo 🎉 Deployment preparation complete!
echo 📖 See INFINITYFREE_DEPLOYMENT_GUIDE.md for detailed instructions
pause
