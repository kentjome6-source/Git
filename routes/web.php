<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PetHealthController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\LostFoundController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\AdoptionController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Admin\LostFoundController as AdminLostFoundController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\MapController as AdminMapController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\VetController;
use App\Http\Controllers\ViewMapController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('welcome'));

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// Google OAuth
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Registration (Pet User only)
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Veterinarian Registration
Route::get('/vet/register', [AuthController::class, 'showVetRegister'])->name('vet.register');
Route::post('/vet/register', [AuthController::class, 'vetRegister'])->name('vet.register.post');


// Dashboards
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard')->middleware(['auth', 'admin']);

// Profile and Pet Routes
Route::middleware('auth')->group(function () {
    // Generic profile route (kept for backward compatibility)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Role-specific profile routes
    Route::get('/user/profile', [ProfileController::class, 'edit'])->name('user.profile.edit');
    Route::post('/user/profile', [ProfileController::class, 'update'])->name('user.profile.update');
    
    Route::get('/admin/profile', [ProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::post('/admin/profile', [ProfileController::class, 'update'])->name('admin.profile.update');
    
    Route::get('/vet/profile', [ProfileController::class, 'edit'])->name('vet.profile.edit');
    Route::post('/vet/profile', [ProfileController::class, 'update'])->name('vet.profile.update');

    // Adoption Routes
    Route::get('/adoptions', [AdoptionController::class, 'index'])->name('adoptions.index');
    Route::get('/adoptions/history', [AdoptionController::class, 'history'])->name('adoptions.history');
    Route::get('/adoptions/create', [AdoptionController::class, 'create'])->name('adoptions.create');
    Route::post('/adoptions', [AdoptionController::class, 'store'])->name('adoptions.store');
    Route::get('/adoptions/{adoption}', [AdoptionController::class, 'show'])->name('adoptions.show');
    Route::post('/adoptions/{adoption}/adopt', [AdoptionController::class, 'adopt'])->name('adoptions.adopt');
    Route::post('/adoptions/{adoption}/approve', [AdoptionController::class, 'approveAdoption'])->name('adoptions.approve');
    Route::post('/adoptions/{adoption}/reject', [AdoptionController::class, 'rejectAdoption'])->name('adoptions.reject');
    Route::post('/adoptions/{adoption}/complete', [AdoptionController::class, 'completeAdoption'])->name('adoptions.complete');
    Route::delete('/adoptions/{adoption}', [AdoptionController::class, 'destroy'])->name('adoptions.destroy');

    // Pet Health Routes (converted from RESTful resource)
    Route::get('/pet-health', [PetHealthController::class, 'index'])->name('pet.health');
    Route::get('/pet-health/create', [PetHealthController::class, 'create'])->name('pet.health.create');
    Route::post('/pet-health', [PetHealthController::class, 'store'])->name('pet.health.store');
    Route::get('/pet-health/{petHealth}', [PetHealthController::class, 'show'])->name('pet.health.show');
    Route::get('/pet-health/{petHealth}/edit', [PetHealthController::class, 'edit'])->name('pet.health.edit');
    Route::put('/pet-health/{petHealth}', [PetHealthController::class, 'update'])->name('pet.health.update');
    Route::delete('/pet-health/{petHealth}', [PetHealthController::class, 'destroy'])->name('pet.health.destroy');

    Route::get('/view-map', [ViewMapController::class, 'index'])->name('view.map');
    Route::get('/view-map/shelter/{shelter}', [ViewMapController::class, 'showShelter'])->name('view.map.shelter.show');
    
    // Multi-Pet Dashboard Routes
    Route::get('/multi-pet', [PetController::class, 'index'])->name('pet.multipet.index');
    Route::get('/multi-pet/create', [PetController::class, 'create'])->name('pet.multipet.create');
    Route::post('/multi-pet', [PetController::class, 'store'])->name('pet.multipet.store');
    Route::get('/multi-pet/{pet}', [PetController::class, 'show'])->name('pet.multipet.show');
    Route::get('/multi-pet/{pet}/edit', [PetController::class, 'edit'])->name('pet.multipet.edit');
    Route::put('/multi-pet/{pet}', [PetController::class, 'update'])->name('pet.multipet.update');
    Route::delete('/multi-pet/{pet}', [PetController::class, 'destroy'])->name('pet.multipet.destroy');
    
    // Appointment Routes (converted from RESTful resource)
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('/appointments/{appointment}', [AppointmentController::class, 'show'])->name('appointments.show');
    Route::get('/appointments/{appointment}/edit', [AppointmentController::class, 'edit'])->name('appointments.edit');
    Route::put('/appointments/{appointment}', [AppointmentController::class, 'update'])->name('appointments.update');
    Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');
    Route::get('/appointments/vet/dashboard', [AppointmentController::class, 'vetIndex'])->name('appointments.vet.index');
    Route::post('/appointments/{appointment}/accept', [AppointmentController::class, 'accept'])->name('appointments.accept');
    Route::post('/appointments/{appointment}/reject', [AppointmentController::class, 'reject'])->name('appointments.reject');
    Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.update-status');
    
    // Chat Routes for Users
    Route::get('/messages', [ChatController::class, 'index'])->name('user.messages.index');
    Route::post('/messages/send', [ChatController::class, 'send'])->name('user.messages.send');
    Route::get('/messages/fetch', [ChatController::class, 'fetchMessages'])->name('user.messages.fetch');
    Route::get('/messages/unread-count', [ChatController::class, 'getUnreadCount'])->name('user.messages.unread-count');
    Route::post('/messages/mark-as-read', [ChatController::class, 'markAsRead'])->name('user.messages.mark-as-read');
    Route::get('/messages/contact-unread-count', [ChatController::class, 'getContactUnreadCount'])->name('user.messages.contact-unread-count');

    // Lost & Found Routes
    Route::get('/lost-found', [LostFoundController::class, 'index'])->name('pet.lostfound');
    Route::get('/lost-found/map', [LostFoundController::class, 'map'])->name('lost-found.map');
    Route::get('/lost-found/create', [LostFoundController::class, 'create'])->name('lost-found.create');
    Route::post('/lost-found', [LostFoundController::class, 'store'])->name('lost-found.store');
    Route::get('/lost-found/{lostFound}', [LostFoundController::class, 'show'])->name('lost-found.show');
    Route::get('/lost-found/{lostFound}/edit', [LostFoundController::class, 'edit'])->name('lost-found.edit');
    Route::put('/lost-found/{lostFound}', [LostFoundController::class, 'update'])->name('lost-found.update');
    Route::delete('/lost-found/{lostFound}', [LostFoundController::class, 'destroy'])->name('lost-found.destroy');
    Route::patch('/lost-found/{lostFound}/resolve', [LostFoundController::class, 'markResolved'])->name('lost-found.resolve');
    Route::get('/my-listings', [LostFoundController::class, 'myListings'])->name('lost-found.my-listings');

    // Furparent Social Media Routes
    Route::get('/social-media', [PostController::class, 'index'])->name('social-media.index');
    Route::get('/social-media/create', [PostController::class, 'create'])->name('social-media.create');
    Route::post('/social-media', [PostController::class, 'store'])->name('social-media.store');
    Route::get('/social-media/my-posts', [PostController::class, 'myPosts'])->name('social-media.my-posts');
    Route::delete('/social-media/{post}', [PostController::class, 'destroy'])->name('social-media.destroy');
    Route::post('/social-media/{post}/toggle-like', [PostController::class, 'toggleLike'])->name('social-media.toggle-like');
    Route::get('/social-media/{post}', [PostController::class, 'show'])->name('social-media.show');

    // Comments Routes
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Vet Routes
    Route::prefix('vet')->name('vet.')->middleware(['can:isVet', 'vet.verified'])->group(function () {
        Route::get('adoptions', [App\Http\Controllers\Vet\AdoptionController::class, 'index'])->name('adoptions.index');
        Route::get('adoptions/management', [App\Http\Controllers\Vet\AdoptionManagementController::class, 'index'])->name('adoptions.management.index');
        Route::get('adoptions/management/create', [App\Http\Controllers\Vet\AdoptionManagementController::class, 'create'])->name('adoptions.management.create');
        Route::post('adoptions/management', [App\Http\Controllers\Vet\AdoptionManagementController::class, 'store'])->name('adoptions.management.store');
        Route::get('adoptions/management/{adoption}', [App\Http\Controllers\Vet\AdoptionManagementController::class, 'show'])->name('adoptions.management.show');
        Route::delete('adoptions/management/{adoption}', [App\Http\Controllers\Vet\AdoptionManagementController::class, 'destroy'])->name('adoptions.management.destroy');
        
        // Add adoption request routes for vets
        Route::post('adoptions/management/{adoption}/adopt', [App\Http\Controllers\Vet\AdoptionManagementController::class, 'adopt'])->name('adoptions.management.adopt');
        Route::post('adoptions/management/{adoption}/approve', [App\Http\Controllers\Vet\AdoptionManagementController::class, 'approveAdoption'])->name('adoptions.management.approve');
        Route::post('adoptions/management/{adoption}/reject', [App\Http\Controllers\Vet\AdoptionManagementController::class, 'rejectAdoption'])->name('adoptions.management.reject');
        Route::post('adoptions/management/{adoption}/complete', [App\Http\Controllers\Vet\AdoptionManagementController::class, 'completeAdoption'])->name('adoptions.management.complete');
        
        Route::get('records', [VetController::class, 'records'])->name('records');
        Route::get('records/{id}', [VetController::class, 'show'])->name('records.show');
        Route::get('records/{id}/view', [VetController::class, 'viewRecord'])->name('records.view');
        Route::get('records/{id}/treatment/create', [VetController::class, 'createTreatment'])->name('records.treatment.create');
        Route::post('records/{id}/treatments', [VetController::class, 'addTreatment'])->name('records.treatments.add');
        Route::get('treatments/{treatment}/edit', [VetController::class, 'editTreatment'])->name('records.treatments.edit');
        Route::put('treatments/{treatment}', [VetController::class, 'updateTreatment'])->name('records.treatments.update');
        Route::get('/appointments', [VetController::class, 'appointments'])->name('appointments');
        
        // Chat Routes for Vets
        Route::get('messages', [ChatController::class, 'vetIndex'])->name('messages.index');
        Route::post('/messages/send', [ChatController::class, 'send'])->name('messages.send');
        Route::get('/messages/fetch', [ChatController::class, 'fetchMessages'])->name('messages.fetch');
        Route::get('/messages/unread-count', [ChatController::class, 'getUnreadCount'])->name('messages.unread-count');
        Route::post('/messages/mark-as-read', [ChatController::class, 'markAsRead'])->name('messages.mark-as-read');
        Route::get('/messages/contact-unread-count', [ChatController::class, 'getContactUnreadCount'])->name('messages.contact-unread-count');
    });
});

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    // User Management (converted from RESTful resource)
    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('admin.users.show');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
    Route::post('/users/bulk-action', [AdminUserController::class, 'bulkAction'])->name('admin.users.bulk-action');
    
    // Veterinarian Verification Routes
    Route::post('/users/{id}/verify-vet', [AdminUserController::class, 'verifyVet'])->name('admin.users.verify-vet');
    Route::post('/users/{id}/reject-vet', [AdminUserController::class, 'rejectVet'])->name('admin.users.reject-vet');
    
    // Adoption Management
    Route::get('/adoptions', [App\Http\Controllers\Admin\AdoptionController::class, 'index'])->name('admin.adoptions.index');
    
    // Lost & Found Management
    Route::get('/lost-found', [AdminLostFoundController::class, 'index'])->name('admin.lost-found.index');
    Route::get('/lost-found/{lostFound}', [AdminLostFoundController::class, 'show'])->name('admin.lost-found.show');
    
    // Map Management
    Route::get('/map', [AdminMapController::class, 'index'])->name('admin.map.index');
    Route::get('/map/location/{shelter}', [AdminMapController::class, 'show'])->name('admin.map.show');
    Route::delete('/map/location/{shelter}', [AdminMapController::class, 'destroy'])->name('admin.map.destroy');
    Route::patch('/map/location/{shelter}/toggle-status', [AdminMapController::class, 'toggleStatus'])->name('admin.map.toggle-status');
    
    // Pet Management (converted from RESTful resource)
    Route::get('/pets', [App\Http\Controllers\Admin\PetController::class, 'index'])->name('admin.pets.index');
    Route::get('/pets/create', [App\Http\Controllers\Admin\PetController::class, 'create'])->name('admin.pets.create');
    Route::post('/pets', [App\Http\Controllers\Admin\PetController::class, 'store'])->name('admin.pets.store');
    Route::get('/pets/{pet}/edit', [App\Http\Controllers\Admin\PetController::class, 'edit'])->name('admin.pets.edit');
    Route::put('/pets/{pet}', [App\Http\Controllers\Admin\PetController::class, 'update'])->name('admin.pets.update');
    Route::delete('/pets/{pet}', [App\Http\Controllers\Admin\PetController::class, 'destroy'])->name('admin.pets.destroy');
    
});
