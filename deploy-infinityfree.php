<?php
/**
 * InfinityFree Deployment Script
 * Run this script to prepare your Laravel application for InfinityFree hosting
 */

echo "🚀 InfinityFree Deployment Script\n";
echo "================================\n\n";

// Check if we're in the right directory
if (!file_exists('artisan')) {
    echo "❌ Error: This script must be run from the Laravel root directory\n";
    exit(1);
}

echo "✅ Laravel application detected\n\n";

// Step 1: Check if .env file exists
if (!file_exists('.env')) {
    echo "📝 Creating .env file from infinityfree-deployment.env...\n";
    if (file_exists('infinityfree-deployment.env')) {
        copy('infinityfree-deployment.env', '.env');
        echo "✅ .env file created successfully\n";
    } else {
        echo "❌ Error: infinityfree-deployment.env not found\n";
        exit(1);
    }
} else {
    echo "✅ .env file already exists\n";
}

echo "\n";

// Step 2: Check database connection
echo "🔍 Testing database connection...\n";
try {
    $host = 'sql211.infinityfree.com';
    $dbname = 'if0_39751761_boothcare';
    $username = 'if0_39751761';
    $password = 'boothcare123';
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Database connection successful\n";
} catch (PDOException $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
    echo "   Please check your database credentials\n";
}

echo "\n";

// Step 3: Check required directories
echo "📁 Checking required directories...\n";
$directories = [
    'storage',
    'storage/logs',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'bootstrap/cache'
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
        echo "✅ Created directory: $dir\n";
    } else {
        echo "✅ Directory exists: $dir\n";
    }
}

echo "\n";

// Step 4: Check file permissions
echo "🔐 Checking file permissions...\n";
$permissions = [
    'storage' => 0755,
    'storage/logs' => 0644,
    'storage/framework/cache' => 0644,
    'storage/framework/sessions' => 0644,
    'storage/framework/views' => 0644,
    'bootstrap/cache' => 0755
];

foreach ($permissions as $path => $permission) {
    if (is_dir($path)) {
        chmod($path, $permission);
        echo "✅ Set permissions for $path\n";
    }
}

echo "\n";

// Step 5: Generate application key if needed
echo "🔑 Checking application key...\n";
$envContent = file_get_contents('.env');
if (strpos($envContent, 'APP_KEY=base64:') === false) {
    echo "⚠️  Application key not found. You'll need to run: php artisan key:generate\n";
} else {
    echo "✅ Application key is set\n";
}

echo "\n";

// Step 6: Check for vendor directory
echo "📦 Checking dependencies...\n";
if (!is_dir('vendor')) {
    echo "⚠️  Vendor directory not found. Run: composer install\n";
} else {
    echo "✅ Dependencies are installed\n";
}

echo "\n";

// Step 7: Check for node_modules
echo "📦 Checking Node.js dependencies...\n";
if (!is_dir('node_modules')) {
    echo "⚠️  Node modules not found. Run: npm install\n";
} else {
    echo "✅ Node.js dependencies are installed\n";
}

echo "\n";

// Step 8: Create deployment checklist
echo "📋 Deployment Checklist:\n";
echo "========================\n";
echo "1. ✅ Environment configuration ready\n";
echo "2. ✅ Database connection tested\n";
echo "3. ✅ Directory structure verified\n";
echo "4. ✅ File permissions set\n";
echo "5. ⚠️  Run: composer install --optimize-autoloader --no-dev\n";
echo "6. ⚠️  Run: npm install && npm run build\n";
echo "7. ⚠️  Run: php artisan migrate --force\n";
echo "8. ⚠️  Run: php artisan config:cache\n";
echo "9. ⚠️  Run: php artisan route:cache\n";
echo "10. ⚠️  Run: php artisan view:cache\n";
echo "11. ⚠️  Run: php artisan storage:link\n";
echo "\n";

echo "📁 File Structure for InfinityFree:\n";
echo "==================================\n";
echo "public_html/ (Laravel public folder contents)\n";
echo "├── index.php\n";
echo "├── .htaccess\n";
echo "├── css/\n";
echo "├── js/\n";
echo "└── images/\n";
echo "\n";
echo "../ (Parent directory - Laravel root)\n";
echo "├── app/\n";
echo "├── bootstrap/\n";
echo "├── config/\n";
echo "├── database/\n";
echo "├── resources/\n";
echo "├── routes/\n";
echo "├── storage/\n";
echo "├── vendor/\n";
echo "└── .env\n";

echo "\n";
echo "🎉 Deployment preparation complete!\n";
echo "📖 See INFINITYFREE_DEPLOYMENT_GUIDE.md for detailed instructions\n";
?>
