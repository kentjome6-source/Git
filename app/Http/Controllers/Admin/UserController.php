<?php

namespace App\Http\Controllers\Admin;

use App\Models\Pet;
use App\Models\User;
use App\Models\Vetshop;
use App\Models\Adoption;
use App\Models\LostFound;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserController extends Controller
{
    public function createVet()
    {
        return view('admin.users.create-vet');
    }

    public function storeVet(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'license_number' => 'required|string|max:255',
            'specialization' => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'vet',
            'is_verified_vet' => true,
        ]);

        if (Schema::hasTable('vet_profiles')) {
            $user->vetProfile()->create([
                'license_number' => $request->license_number,
                'specialization' => $request->specialization,
                'is_verified' => true,
            ]);
        } else {
            $user->update([
                'license_number' => $request->license_number,
                'specialization' => $request->specialization,
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Veterinarian added successfully!');
    }
    
    public function index(Request $request)
    {
        // Start query excluding admin users
        $query = User::where('role', '!=', 'admin')
                    ->withCount('pets');
        
        // Apply filters
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Calculate stats (excluding admins)
        $stats = [
            'total_users' => User::where('role', '!=', 'admin')->count(),
            'vet_count' => User::where('role', 'vet')->count(),
            'user_count' => User::where('role', 'user')->count(),
            'new_this_month' => User::where('role', '!=', 'admin')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];
        
        $users = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * Show form to create a new user
     */
    public function create()
    {
        $vetshops = Vetshop::active()->get();
        return view('admin.users.create', compact('vetshops'));
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
            'role' => 'required|in:user,admin,vet',
            'vet_shop_id' => 'nullable|exists:vet_shop,id',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);
        
        // Additional validation: Prevent creating too many admin accounts
        if ($request->role === 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount >= 3) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Maximum number of admin accounts (3) reached. Please contact system administrator.');
            }
        }
        
        // For vets, require vet shop if not admin creating
        if ($request->role === 'vet' && !$request->vet_shop_id && !$this->isAdminUser()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Please select a veterinary clinic/shop for the veterinarian.');
        }
        
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'vet_shop_id' => $request->vet_shop_id,
            'phone' => $request->phone,
            'address' => $request->address,
            'is_active' => true,
            'is_verified_vet' => $request->role === 'vet',
        ]);
        
        return redirect()->route('admin.users.index')
                        ->with('success', 'User created successfully!');
    }
    
    /**
     * Show user details
     */
    public function show(User $user)
    {
        // Load the vetShop relationship
        $user->load(['pets', 'vetShop']);
        
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
     * Edit user
     */
    public function edit(User $user)
    {
        $vetshops = Vetshop::active()->get();
        return view('admin.users.edit', compact('user', 'vetshops'));
    }
    
    /**
     * Update user
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:user,admin,vet',
            'vet_shop_id' => 'nullable|exists:vet_shop,id',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);
        
        // Additional validation: Prevent creating too many admin accounts
        if ($request->role === 'admin' && $user->role !== 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount >= 3) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Maximum number of admin accounts (3) reached. Please contact system administrator.');
            }
        }
        
        // For vets, require vet shop if not admin creating
        if ($request->role === 'vet' && !$request->vet_shop_id && !$this->isAdminUser()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Please select a veterinary clinic/shop for the veterinarian.');
        }
        
        // If changing role from vet to something else, remove vet shop
        if ($user->role === 'vet' && $request->role !== 'vet') {
            $request->merge(['vet_shop_id' => null, 'is_verified_vet' => false]);
        }
        
        // If changing to vet, set as verified if admin is doing it
        if ($request->role === 'vet' && $user->role !== 'vet') {
            $request->merge(['is_verified_vet' => $this->isAdminUser()]);
        }
        
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'vet_shop_id' => $request->vet_shop_id,
            'phone' => $request->phone,
            'address' => $request->address,
            'is_active' => $request->is_active ?? $user->is_active,
            'is_verified_vet' => $request->role === 'vet' ? ($request->is_verified_vet ?? $user->is_verified_vet) : false,
        ]);
        
        return redirect()->route('admin.users.show', $user)
                        ->with('success', 'User updated successfully!');
    }
    
    /**
     * Verify a veterinarian
     */
    public function verifyVet(User $user)
    {
        if ($user->role !== 'vet') {
            return redirect()->back()->with('error', 'User is not a veterinarian.');
        }
        
        // Check if already verified
        if ($user->is_verified_vet) {
            return redirect()->back()->with('info', 'Veterinarian is already verified.');
        }
        
        $user->update(['is_verified_vet' => true]);
        
        return redirect()->back()->with('success', 'Veterinarian verified successfully! They can now access the system.');
    }
    
    /**
     * Reject a veterinarian
     */
    public function rejectVet(User $user)
    {
        if ($user->role !== 'vet') {
            return redirect()->back()->with('error', 'User is not a veterinarian.');
        }
        
        $user->update(['is_verified_vet' => false]);
        
        return redirect()->back()->with('success', 'Veterinarian verification revoked!');
    }
    
    /**
     * Assign vet to shop
     */
    public function assignToShop(Request $request, User $user)
    {
        $request->validate([
            'vet_shop_id' => 'required|exists:vet_shop,id'
        ]);
        
        if ($user->role !== 'vet') {
            return redirect()->back()->with('error', 'Only veterinarians can be assigned to clinics.');
        }
        
        $user->update(['vet_shop_id' => $request->vet_shop_id]);
        
        return redirect()->back()->with('success', 'Veterinarian assigned to clinic successfully!');
    }
    
    /**
     * Remove vet from shop
     */
    public function removeFromShop(User $user)
    {
        if ($user->role !== 'vet') {
            return redirect()->back()->with('error', 'Only veterinarians can be removed from clinics.');
        }
        
        $user->update(['vet_shop_id' => null]);
        
        return redirect()->back()->with('success', 'Veterinarian removed from clinic successfully!');
    }
    
    /**
     * Delete user
     */
    public function destroy(User $user)
    {
        $user->delete();
        
        return redirect()->route('admin.users.index')
                        ->with('success', 'User deleted successfully!');
    }
    
    /**
     * Check if current authenticated user is admin
     */
    private function isAdminUser()
    {
        $user = Auth::user();
        return $user && $user->role === 'admin';
    }
}