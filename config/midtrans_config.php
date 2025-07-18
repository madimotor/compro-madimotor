<?php
// Load environment variables from .env file
function loadEnv($path) {
    if (!file_exists($path)) {
        return false;
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        
        if (!array_key_exists($name, $_ENV)) {
            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
    
    return true;
}

// Load .env file
loadEnv(__DIR__ . '/../.env');

require_once __DIR__ . '/../vendor/autoload.php';

// Midtrans Configuration
\Midtrans\Config::$serverKey = getenv('MIDTRANS_SERVER_KEY');
\Midtrans\Config::$isProduction = false; // Set to true for production
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

// Client key for frontend
define('MIDTRANS_CLIENT_KEY', getenv('MIDTRANS_CLIENT_KEY'));
define('MIDTRANS_SERVER_KEY', getenv('MIDTRANS_SERVER_KEY'));
?>
