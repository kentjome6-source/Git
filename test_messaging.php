<?php
require_once 'vendor/autoload.php';

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Database\Capsule\Manager as Capsule;

// Create a service container
$container = new Container();

// Create a database capsule
$capsule = new Capsule($container);
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'pawportal',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

$capsule->setEventDispatcher(new Dispatcher($container));
$capsule->setAsGlobal();
$capsule->bootEloquent();

// Test the messaging functionality
try {
    // Get a user
    $user = Capsule::table('users')->where('id', 3)->first();
    
    if ($user) {
        echo "User found: " . $user->name . " (Role: " . $user->role . ")\n";
        
        // Check if user has appointments
        $appointments = Capsule::table('appointments')->where('user_id', $user->id)->get();
        echo "Appointments count: " . count($appointments) . "\n";
        
        foreach ($appointments as $appointment) {
            echo "Appointment with vet_id: " . $appointment->vet_id . "\n";
            
            // Get the veterinarian
            $vet = Capsule::table('users')->where('id', $appointment->vet_id)->first();
            if ($vet) {
                echo "Veterinarian: " . $vet->name . " (Role: " . $vet->role . ")\n";
            }
        }
    } else {
        echo "User not found\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}