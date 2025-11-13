<?php

namespace App\Http\Controllers\Vet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Adoption;
use App\Models\User;

class AdoptionController extends Controller
{
    /**
     * Display adoption history for all pets that have adoption history records,
     * including those uploaded by pet parents and vets.
     */
    public function index()
    {
        // Get all adoption records that have adoption history, regardless of who uploaded them
        $adoptions = Adoption::with(['user', 'pet', 'adoptionHistory.adopter'])
            ->whereHas('adoptionHistory')
            ->orderBy('updated_at', 'desc')
            ->paginate(20);
        
        return view('vet.adoptions.index', compact('adoptions'));
    }
}