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
    'password' => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

$capsule->setEventDispatcher(new Dispatcher($container));
$capsule->setAsGlobal();
$capsule->bootEloquent();

// Test the contact restrictions
try {
    // Get a veterinarian user
    $vet = Capsule::table('users')->where('role', 'vet')->first();
    
    if ($vet) {
        echo "Veterinarian found: " . $vet->name . " (ID: " . $vet->id . ")\n";
        
        // Check if vet has appointments with pet parents
        $appointments = Capsule::table('appointments')->where('vet_id', $vet->id)->get();
        echo "Appointments count: " . count($appointments) . "\n";
        
        foreach ($appointments as $appointment) {
            echo "Appointment with user_id: " . $appointment->user_id . "\n";
            
            // Get the pet parent
            $petParent = Capsule::table('users')->where('id', $appointment->user_id)->first();
            if ($petParent) {
                echo "Pet Parent: " . $petParent->name . " (ID: " . $petParent->id . ")\n";
            }
        }
    } else {
        echo "No veterinarian found in the database.\n";
    }
    
    echo "\n---\n\n";
    
    // Get a pet parent user
    $user = Capsule::table('users')->where('role', 'user')->first();
    
    if ($user) {
        echo "Pet Parent found: " . $user->name . " (ID: " . $user->id . ")\n";
        
        // Check if pet parent has appointments with veterinarians
        $appointments = Capsule::table('appointments')->where('user_id', $user->id)->get();
        echo "Appointments count: " . count($appointments) . "\n";
        
        foreach ($appointments as $appointment) {
            echo "Appointment with vet_id: " . $appointment->vet_id . "\n";
            
            // Get the veterinarian
            $vet = Capsule::table('users')->where('id', $appointment->vet_id)->first();
            if ($vet) {
                echo "Veterinarian: " . $vet->name . " (ID: " . $vet->id . ")\n";
            }
        }
    } else {
        echo "No pet parent found in the database.\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}