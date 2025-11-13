<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pet;
use App\Models\LostFound;
use App\Models\Appointment;
use App\Models\PetHealthRecord;
use App\Models\Treatment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Get user statistics
        $totalUsers = User::count();
        $totalPets = Pet::count();
        $totalAdoptions = \App\Models\Adoption::count();
        $totalLostFound = LostFound::count();
        
        return view('admin.dashboard.admin', compact('totalUsers', 'totalPets', 'totalAdoptions', 'totalLostFound'));
    }
}