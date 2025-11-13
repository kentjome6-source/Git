<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Adoption;
use App\Models\User;

class AdoptionController extends Controller
{
    /**
     * Display adoption history for all users.
     */
    public function index()
    {
        // Get all adoption records with related data
        $adoptions = Adoption::with(['user', 'pet', 'adoptionHistory.adopter'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.adoptions.index', compact('adoptions'));
    }
}