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

// Test the appointment-based messaging functionality
try {
    // Get a veterinarian user
    $vet = Capsule::table('users')->where('role', 'vet')->first();
    
    if ($vet) {
        echo "Veterinarian found: " . $vet->name . " (ID: " . $vet->id . ")\n";
        
        // Check if vet has appointments with pet parents
        $appointments = Capsule::table('appointments')
            ->where('vet_id', $vet->id)
            ->where('status', 'accepted')
            ->get();
            
        echo "Accepted appointments count: " . count($appointments) . "\n";
        
        foreach ($appointments as $appointment) {
            echo "Accepted appointment with user_id: " . $appointment->user_id . "\n";
            
            // Get the pet parent
            $petParent = Capsule::table('users')->where('id', $appointment->user_id)->first();
            if ($petParent) {
                echo "  Pet Parent: " . $petParent->name . " (ID: " . $petParent->id . ")\n";
            }
        }
        
        // Check for pending appointments (should not appear in contacts)
        $pendingAppointments = Capsule::table('appointments')
            ->where('vet_id', $vet->id)
            ->where('status', 'pending')
            ->get();
            
        echo "Pending appointments count: " . count($pendingAppointments) . "\n";
        
        foreach ($pendingAppointments as $appointment) {
            echo "Pending appointment with user_id: " . $appointment->user_id . " (should NOT appear in contacts)\n";
        }
    } else {
        echo "No veterinarian found in the database.\n";
    }
    
    echo "\n--- Testing Pet Parent Contacts ---\n";
    
    // Get a pet parent user
    $user = Capsule::table('users')->where('role', 'user')->first();
    
    if ($user) {
        echo "Pet Parent found: " . $user->name . " (ID: " . $user->id . ")\n";
        
        // Check if pet parent has appointments with veterinarians
        $appointments = Capsule::table('appointments')
            ->where('user_id', $user->id)
            ->where('status', 'accepted')
            ->get();
            
        echo "Accepted appointments with veterinarians count: " . count($appointments) . "\n";
        
        foreach ($appointments as $appointment) {
            echo "Accepted appointment with vet_id: " . $appointment->vet_id . "\n";
            
            // Get the veterinarian
            $vet = Capsule::table('users')->where('id', $appointment->vet_id)->first();
            if ($vet) {
                echo "  Veterinarian: " . $vet->name . " (ID: " . $vet->id . ")\n";
            }
        }
        
        // Check for pending appointments (should not appear in contacts)
        $pendingAppointments = Capsule::table('appointments')
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->get();
            
        echo "Pending appointments with veterinarians count: " . count($pendingAppointments) . "\n";
        
        foreach ($pendingAppointments as $appointment) {
            echo "Pending appointment with vet_id: " . $appointment->vet_id . " (should NOT appear in contacts)\n";
        }
    } else {
        echo "No pet parent found in the database.\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}