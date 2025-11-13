<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LostFound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LostFoundController extends Controller
{
    public function index()
    {
        $lostFoundItems = LostFound::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.lost-found.index', compact('lostFoundItems'));
    }

    public function show(LostFound $lostFound)
    {
        return view('admin.lost-found.show', compact('lostFound'));
    }
}