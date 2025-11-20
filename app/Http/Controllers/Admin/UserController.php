<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Appointment;
use App\Models\LostFound;
use App\Models\Pet;
use App\Models\Adoption;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of users with filters
     */
    public function index(Request $request)
    {
        $query = User::legitimate()->with('pets');
        
        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Role filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        // Date filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $users = $query->withCount(['pets'])
                      ->orderBy('created_at', 'desc')
                      ->paginate(15);
        
        // Get stats for the dashboard
        $stats = [
            'total_users' => User::legitimate()->count(),
            'admin_count' => User::legitimate()->where('role', 'admin')->count(),
            'vet_count' => User::legitimate()->where('role', 'vet')->count(),
            'user_count' => User::legitimate()->where('role', 'user')->count(),
            'new_this_month' => User::legitimate()->whereMonth('created_at', now()->month)
                                   ->whereYear('created_at', now()->year)
                                   ->count(),
        ];
        
        return view('admin.users.index', compact('users', 'stats'));
    }
    
    /**
     * Show form to create a new user
     */
    public function create()
    {
        return view('admin.users.create');
    }
    
    /**
     * Store a new user
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,admin',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);
        
        // Additional validation: Prevent creating too many admin accounts
        if ($request->role === 'admin') {
            $adminCount = User::legitimate()->where('role', 'admin')->count();
            if ($adminCount >= 3) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Maximum number of admin accounts (3) reached. Please contact system administrator.');
            }
        }
        
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
            'is_active' => true, // New users are active by default
        ]);
        
        return redirect()->route('admin.users.index')
                        ->with('success', 'User created successfully!');
    }
    
    /**
     * Show user details
     */
    public function show(User $user)
    {
        // Ensure we're only showing legitimate users
        if (!$user->legitimate()->exists()) {
            return redirect()->route('admin.users.index')
                           ->with('error', 'User not found.');
        }
        
        // Load pets without health records to avoid the error
        $user->load(['pets']);
        
        // Initialize default stats structure
        $stats = [
            'pets_count' => 0,
            'adoptions_count' => 0,
            'appointments_count' => 0,
            'lost_found_count' => 0,
            'adoption_listings_count' => 0,
            'adopted_pets_count' => 0,
            'recent_activity' => [
                'pets' => [],
                'adoptions' => [],
                'appointments' => [],
                'lost_found' => [],
            ]
        ];
        
        // Get user statistics based on role
        if ($user->role === 'vet') {
            // For veterinarians, show adoption stats and appointments where they are the assigned vet
            $stats['adoptions_count'] = Adoption::where('user_id', $user->id)
                                         ->where('uploader_type', 'vet')
                                         ->count();
            $stats['appointments_count'] = Appointment::where('vet_id', $user->id)->count();
            $stats['recent_activity'] = [
                'adoptions' => Adoption::where('user_id', $user->id)
                                    ->where('uploader_type', 'vet')
                                    ->latest()
                                    ->take(5)
                                    ->get(),
                'appointments' => Appointment::where('vet_id', $user->id)
                                              ->latest()
                                              ->take(5)
                                              ->get(),
                'lost_found' => [], // Vets don't create lost/found listings
            ];
        } else {
            // For regular users, show pet registration stats and appointments they created
            $stats['pets_count'] = $user->pets()->count();
            $stats['appointments_count'] = Appointment::where('user_id', $user->id)->count();
            $stats['lost_found_count'] = LostFound::where('user_id', $user->id)->count();
            $stats['adoption_listings_count'] = Adoption::where('user_id', $user->id)
                                                ->where('uploader_type', 'user')
                                                ->count();
            
            // Safely count adopted pets
            try {
                $stats['adopted_pets_count'] = Pet::where('adopter_id', $user->id)->count();
            } catch (\Exception $e) {
                // If the column doesn't exist, set count to 0
                $stats['adopted_pets_count'] = 0;
            }
            
            $stats['recent_activity'] = [
                'pets' => $user->pets()->latest()->take(3)->get(),
                'adoptions' => Adoption::where('user_id', $user->id)
                                    ->where('uploader_type', 'user')
                                    ->latest()
                                    ->take(5)
                                    ->get(),
                'appointments' => Appointment::where('user_id', $user->id)
                                              ->latest()
                                              ->take(5)
                                              ->get(),
                'lost_found' => LostFound::where('user_id', $user->id)
                                          ->latest()
                                          ->take(5)
                                          ->get(),
            ];
        }
        
        return view('admin.users.show', compact('user', 'stats'));
    }
    
    /**
     * Delete user
     */
    public function destroy(User $user)
    {
        // Prevent deletion of the current admin user
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                           ->with('error', 'You cannot delete your own account!');
        }
        
        // Ensure we're only deleting legitimate users
        if (!$user->legitimate()->exists()) {
            return redirect()->route('admin.users.index')
                           ->with('error', 'User not found.');
        }
        
        // Delete user's pets first to maintain referential integrity
        $user->pets()->delete();
        
        // Delete the user
        $user->delete();
        
        return redirect()->route('admin.users.index')
                        ->with('success', 'User and associated pets deleted successfully!');
    }
    
    /**
     * Verify a veterinarian
     */
    public function verifyVet($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->role !== 'vet') {
            return redirect()->back()->with('error', 'User is not a veterinarian.');
        }
        
        // Check if already verified
        if ($user->is_verified_vet) {
            return redirect()->back()->with('info', 'Veterinarian is already verified.');
        }
        
        $user->is_verified_vet = true;
        $user->save();
        
        return redirect()->back()->with('success', 'Veterinarian verified successfully! They can now access the system.');
    }
    
    /**
     * Reject a veterinarian
     */
    public function rejectVet($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->role !== 'vet') {
            return redirect()->back()->with('error', 'User is not a veterinarian.');
        }
        
        // Check if already rejected
        if (!$user->is_verified_vet) {
            return redirect()->back()->with('info', 'Veterinarian verification is already pending.');
        }
        
        $user->is_verified_vet = false;
        $user->save();
        
        return redirect()->back()->with('success', 'Veterinarian verification rejected! They will no longer have access to veterinarian features.');
    }
    
    
    
    /**
     * Perform bulk actions on users
     */
    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $userIds = $request->input('user_ids', []);
        
        // Remove current admin ID from the list to prevent self-deletion
        $currentAdminId = auth()->id();
        $userIds = array_filter($userIds, function($id) use ($currentAdminId) {
            return $id != $currentAdminId;
        });
        
        if (empty($userIds)) {
            return redirect()->route('admin.users.index')
                           ->with('error', 'No valid users selected for action.');
        }
        
        switch ($action) {
            case 'delete':
                // Delete users and their pets
                User::whereIn('id', $userIds)->delete();
                return redirect()->route('admin.users.index')
                               ->with('success', 'Selected users deleted successfully!');
                
            case 'activate':
                User::whereIn('id', $userIds)->update(['is_active' => true]);
                return redirect()->route('admin.users.index')
                               ->with('success', 'Selected users activated successfully!');
                
            case 'deactivate':
                User::whereIn('id', $userIds)->update(['is_active' => false]);
                return redirect()->route('admin.users.index')
                               ->with('success', 'Selected users deactivated successfully!');
                
            default:
                return redirect()->route('admin.users.index')
                               ->with('error', 'Invalid action selected.');
        }
    }
}