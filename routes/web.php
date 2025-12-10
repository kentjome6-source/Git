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
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VetController;
use App\Http\Controllers\ViewMapController;
use App\Http\Controllers\LostFoundController;
use App\Http\Controllers\ChatMessageController;
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
    Route::get('/appointment-records/{appointment}', [VetController::class, 'show'])->name('appointment.records.show');
    
    // Vet Appointment Management Routes
    Route::get('/appointments/{appointment}', [AppointmentController::class, 'show'])->name('appointments.show');
    Route::post('/appointments/{appointment}/accept', [AppointmentController::class, 'accept'])->name('appointments.accept');
    Route::post('/appointments/{appointment}/reject', [AppointmentController::class, 'reject'])->name('appointments.reject');
    Route::put('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.status.update');
    
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
    
    // Admin Lost & Found Records
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
    
    // Admin Pet User Management
    Route::resource('admin/pet-users', PetUserController::class);
});

// Authenticated User Routes
Route::middleware(['auth'])->group(function () {
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/user/profile', [ProfileController::class, 'edit'])->name('user.profile.edit');
    Route::match(['PUT', 'PATCH'], '/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Messages Routes
    Route::get('/messages', [ChatMessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [ChatMessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/send', [ChatMessageController::class, 'sendMessage'])->name('messages.send');
    Route::post('/messages/mark-as-read', [ChatMessageController::class, 'markAsRead'])->name('messages.mark-as-read');
    Route::get('/messages/unread-count', [ChatMessageController::class, 'getUnreadCount'])->name('messages.unread-count');
    Route::get('/messages/contact-unread-count', [ChatMessageController::class, 'getContactUnreadCount'])->name('messages.contact-unread-count');
    
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
    Route::get('/appointments/history/{appointment}', [AppointmentController::class, 'showHistory'])->name('appointments.history.show');
    Route::resource('appointments', AppointmentController::class);
    
    // Adoption Routes
    Route::get('/adoptions/history', [UserAdoptionController::class, 'history'])->name('adoptions.history');
    Route::resource('adoptions', UserAdoptionController::class);
    Route::post('/adoptions/{adoption}/adopt', [UserAdoptionController::class, 'adopt'])->name('adoptions.adopt');
    Route::post('/adoptions/{adoption}/approve', [UserAdoptionController::class, 'approveAdoption'])->name('adoptions.approve');
    Route::post('/adoptions/{adoption}/reject', [UserAdoptionController::class, 'rejectAdoption'])->name('adoptions.reject');
    Route::post('/adoptions/{adoption}/complete', [UserAdoptionController::class, 'completeAdoption'])->name('adoptions.complete');
    Route::delete('/adoptions/{adoption}', [UserAdoptionController::class, 'destroy'])->name('adoptions.destroy');

    // Lost & Found Routes
    Route::get('pet/lostfound', [LostFoundController::class, 'index'])->name('pet.lostfound');
    Route::resource('lost-found', LostFoundController::class);
    Route::patch('lost-found/{lostFound}/resolve', [LostFoundController::class, 'markResolved'])->name('lost-found.resolve');
    
    // Social Media Routes
    Route::get('social-media', [PostController::class, 'index'])->name('social-media.index');
    Route::get('social-media/create', [PostController::class, 'create'])->name('social-media.create');
    Route::post('social-media', [PostController::class, 'store'])->name('social-media.store');
    Route::get('social-media/{post}', [PostController::class, 'show'])->name('social-media.show')->where('post', '[0-9]+');
    Route::get('social-media/{post}/edit', [PostController::class, 'edit'])->name('social-media.edit')->where('post', '[0-9]+');
    Route::put('social-media/{post}', [PostController::class, 'update'])->name('social-media.update')->where('post', '[0-9]+');
    Route::delete('social-media/{postId}', [PostController::class, 'destroy'])->name('social-media.destroy')->where('postId', '[0-9]+');
    Route::get('social-media/my-posts', [PostController::class, 'myPosts'])->name('social-media.my-posts');
    Route::post('social-media/{post}/comments', [CommentController::class, 'store'])->name('comments.store')->where('post', '[0-9]+');
    Route::post('social-media/{post}/toggle-like', [PostController::class, 'toggleLike'])->name('social-media.toggle-like')->where('post', '[0-9]+');
    
    // Map Routes
    Route::get('/view-map', [ViewMapController::class, 'index'])->name('view.map');
    Route::get('/view-map/{id}', [ViewMapController::class, 'show'])->name('view-map.show');
});

// Password Reset Routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');