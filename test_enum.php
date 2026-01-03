<?php
require_once 'vendor/autoload.php';

// Create a simple script to test the enum values
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Load the Laravel application
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Check the enum values
try {
    $result = DB::select("SHOW COLUMNS FROM adoption_requests WHERE Field = 'status'");
    echo "Status column type: " . $result[0]->Type . "\n";
    echo "Status column default: " . $result[0]->Default . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}