<?php

use App\Http\Controllers\Admin\AdoptionController;
use App\Http\Controllers\Admin\LostFoundController as AdminLostFoundController;
use App\Http\Controllers\Admin\MapController;
use App\Http\Controllers\Admin\PetController as AdminPetController;
use App\Http\Controllers\Admin\PetUserController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AdoptionController as UserAdoptionController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VetController;
use App\Http\Controllers\ViewMapController;
use App\Http\Controllers\LostFoundController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/register/vet', [AuthController::class, 'showVetRegister'])->name('register.vet');
Route::post('/register/vet', [AuthController::class, 'vetRegister']);

// Logout Route
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// Vet Routes
Route::prefix('vet')->name('vet.')->middleware(['can:isVet', 'vet.verified'])->group(function () {
    Route::get('records', [VetController::class, 'records'])->name('records');
    Route::get('records/{id}', [VetController::class, 'show'])->name('records.show');
    Route::get('records/{id}/view', [VetController::class, 'viewRecord'])->name('records.view');
    Route::get('/appointments', [VetController::class, 'appointments'])->name('appointments');
    Route::get('/appointment-records', [VetController::class, 'appointmentRecords'])->name('appointment.records');
    
    // Vet Appointment Management Routes
    Route::get('/appointments/{appointment}', [AppointmentController::class, 'show'])->name('appointments.show');
    Route::post('/appointments/{appointment}/accept', [AppointmentController::class, 'accept'])->name('appointments.accept');
    Route::post('/appointments/{appointment}/reject', [AppointmentController::class, 'reject'])->name('appointments.reject');
    Route::put('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.status.update');
    
    // Chat Routes for Vets
    Route::get('messages', [ChatController::class, 'vetIndex'])->name('messages.index');
    Route::post('/messages/send', [ChatController::class, 'send'])->name('messages.send');
    Route::get('/messages/fetch', [ChatController::class, 'fetchMessages'])->name('messages.fetch');
    Route::get('/messages/unread-count', [ChatController::class, 'getUnreadCount'])->name('messages.unread-count');
    Route::post('/messages/mark-as-read', [ChatController::class, 'markAsRead'])->name('messages.mark-as-read');
    Route::get('/messages/contact-unread-count', [ChatController::class, 'getContactUnreadCount'])->name('messages.contact-unread-count');
    
    // Vet Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');    
});

// Admin Routes
Route::middleware(['auth', 'can:isAdmin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Admin Profile Routes
    Route::get('/admin/profile', [ProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::patch('/admin/profile', [ProfileController::class, 'update'])->name('admin.profile.update');
    Route::delete('/admin/profile', [ProfileController::class, 'destroy'])->name('admin.profile.destroy');
    
    // Admin Pet Management
    Route::resource('admin/pets', AdminPetController::class)->names([
        'index' => 'admin.pets.index',
        'create' => 'admin.pets.create',
        'store' => 'admin.pets.store',
        'show' => 'admin.pets.show',
        'edit' => 'admin.pets.edit',
        'update' => 'admin.pets.update',
        'destroy' => 'admin.pets.destroy',
    ]);
    
    // Admin User Management
    Route::resource('admin/users', UserController::class)->names([
        'index' => 'admin.users.index',
        'create' => 'admin.users.create',
        'store' => 'admin.users.store',
        'show' => 'admin.users.show',
        'edit' => 'admin.users.edit',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.destroy',
    ]);
    Route::post('admin/users/bulk-action', [UserController::class, 'bulkAction'])->name('admin.users.bulk-action');
    Route::post('admin/users/{user}/verify-vet', [UserController::class, 'verifyVet'])->name('admin.users.verify-vet');
    Route::post('admin/users/{user}/reject-vet', [UserController::class, 'rejectVet'])->name('admin.users.reject-vet');
    
    // Admin Adoption Management
    Route::resource('admin/adoptions', AdoptionController::class)->names([
        'index' => 'admin.adoptions.index',
        'create' => 'admin.adoptions.create',
        'store' => 'admin.adoptions.store',
        'show' => 'admin.adoptions.show',
        'edit' => 'admin.adoptions.edit',
        'update' => 'admin.adoptions.update',
        'destroy' => 'admin.adoptions.destroy',
    ]);
    
    // Admin Lost & Found Management
    Route::resource('admin/lost-found', AdminLostFoundController::class)->names([
        'index' => 'admin.lost-found.index',
        'create' => 'admin.lost-found.create',
        'store' => 'admin.lost-found.store',
        'show' => 'admin.lost-found.show',
        'edit' => 'admin.lost-found.edit',
        'update' => 'admin.lost-found.update',
        'destroy' => 'admin.lost-found.destroy',
    ]);
    
    // Admin Map Management
    Route::resource('admin/map', MapController::class)->names([
        'index' => 'admin.map.index',
        'create' => 'admin.map.create',
        'store' => 'admin.map.store',
        'show' => 'admin.map.show',
        'edit' => 'admin.map.edit',
        'update' => 'admin.map.update',
        'destroy' => 'admin.map.destroy',
    ]);
    Route::post('admin/map/{vetshop}/toggle-status', [MapController::class, 'toggleStatus'])->name('admin.map.toggleStatus');
    
    // Admin Pet User Management
    Route::resource('admin/pet-users', PetUserController::class);
});

// Authenticated User Routes
Route::middleware(['auth'])->group(function () {
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/user/profile', [ProfileController::class, 'edit'])->name('user.profile.edit');
    Route::patch('x/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Pet Routes
    Route::resource('pets', PetController::class)->names([
        'index' => 'pet.multipet.index',
        'create' => 'pet.multipet.create',
        'store' => 'pet.multipet.store',
        'show' => 'pet.multipet.show',
        'edit' => 'pet.multipet.edit',
        'update' => 'pet.multipet.update',
        'destroy' => 'pet.multipet.destroy',
    ]);
    
    // Appointment Routes
    Route::get('/appointments/history', [AppointmentController::class, 'history'])->name('appointments.history');
    Route::resource('appointments', AppointmentController::class);
    
    // Adoption Routes
    Route::resource('adoptions', UserAdoptionController::class);
    Route::get('/adoptions/history', [UserAdoptionController::class, 'history'])->name('adoptions.history');
    
    // Lost & Found Routes
    Route::get('pet/lostfound', [LostFoundController::class, 'index'])->name('pet.lostfound');
    Route::resource('lost-found', LostFoundController::class);
    
    // Social Media Routes
    Route::resource('social-media', PostController::class)->names([
        'index' => 'social-media.index',
        'create' => 'social-media.create',
        'store' => 'social-media.store',
        'show' => 'social-media.show',
        'edit' => 'social-media.edit',
        'update' => 'social-media.update',
        'destroy' => 'social-media.destroy',
    ]);
    Route::post('social-media/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    
    // Chat Routes
    Route::get('/messages', [ChatController::class, 'index'])->name('messages.index');
    // User Messages Routes
    Route::get('/user/messages', [ChatController::class, 'index'])->name('user.messages.index');
    Route::post('/user/messages/send', [ChatController::class, 'send'])->name('user.messages.send');
    Route::get('/user/messages/unread-count', [ChatController::class, 'getUnreadCount'])->name('user.messages.unread-count');
    Route::post('/user/messages/mark-as-read', [ChatController::class, 'markAsRead'])->name('user.messages.mark-as-read');
    
    // Map Routes
    Route::get('/view-map', [ViewMapController::class, 'index'])->name('view.map');
    Route::get('/view-map/{id}', [ViewMapController::class, 'show'])->name('view-map.show');
});

// Password Reset Routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');