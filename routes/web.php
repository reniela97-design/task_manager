<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\DashboardController;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ====================================================
// EMERGENCY FORCE LOGIN (AKONG GIDUGANG PARA SA IMO)
// ====================================================
// I-type lang ang: imong-app.onrender.com/force-admin sa browser
Route::get('/force-admin', function () {
    // Kini maghimo og temporaryong "Fake" User sa memory lang
    // Dili na kini mag-check sa Aiven Database para makasulod ka dayon
    $user = new User();
    $user->id = 1; // I-assume nato nga ikaw ang ID 1
    $user->name = 'Super Admin';
    $user->email = 'admin@test.com';
    $user->role = 'Administrator'; // Siguroha nga 'Administrator' ang spelling sa imong middleware

    Auth::login($user);

    return redirect('/dashboard')->with('status', 'Nakasulod na ka gamit ang Force Login!');
});

// ====================================================
// PUBLIC ROUTES
// ====================================================
Route::get('/', function () {
    // Gidugangan og try-catch para dili mag-error ang tibuok site kung guba ang Aiven connection
    try {
        $roles = \Illuminate\Support\Facades\Schema::hasTable('roles') 
                 ? Role::where('role_inactive', 0)->get() 
                 : [];
    } catch (\Exception $e) {
        $roles = []; // I-empty lang kung dili ka-connect sa DB para dili mag-419/500 error
    }
    return view('welcome', compact('roles'));
});

// ====================================================
// AUTHENTICATED ROUTES (Logged in Users)
// ====================================================
Route::middleware(['auth'])->group(function () {

    // DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // NOTIFICATIONS
    Route::get('/notifications/read-all', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.readAll');

    // TASK REGISTRY
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::patch('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    
    // TASK ACTIONS
    Route::post('/tasks/{task}/start', [TaskController::class, 'start'])->name('tasks.start');
    Route::post('/tasks/{task}/finish', [TaskController::class, 'finish'])->name('tasks.finish');

    // INSIGHTS & ABOUT
    Route::get('/logs', [ActivityLogController::class, 'index'])->name('logs.index');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/about', function () { return view('about'); })->name('about.index');

    // PROFILE MANAGEMENT
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ====================================================
    // RESTRICTED ROUTES (Administrator & Manager Only)
    // ====================================================
    Route::middleware(['role:Administrator,Manager'])->group(function () {
        
        // USER ACCOUNTS
        Route::resource('users', UserController::class)->except(['create', 'show', 'edit']);

        // ADMINISTRATION MODULES
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
        Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::patch('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        Route::get('/systems', [SystemController::class, 'index'])->name('systems.index');
        Route::post('/systems', [SystemController::class, 'store'])->name('systems.store');
        Route::patch('/systems/{id}', [SystemController::class, 'update'])->name('systems.update');
        Route::delete('/systems/{system}', [SystemController::class, 'destroy'])->name('systems.destroy');

        Route::get('/types', [TypeController::class, 'index'])->name('types.index');
        Route::post('/types', [TypeController::class, 'store'])->name('types.store');
        Route::patch('/types/{id}', [TypeController::class, 'update'])->name('types.update');
        Route::delete('/types/{type}', [TypeController::class, 'destroy'])->name('types.destroy');

        // OPERATIONS MODULES
        Route::resource('clients', ClientController::class)->except(['create', 'show', 'edit']);
        Route::resource('projects', ProjectController::class)->except(['create', 'edit']);
        Route::patch('/projects/{project}/start', [ProjectController::class, 'start'])->name('projects.start');
        Route::patch('/projects/{project}/complete', [ProjectController::class, 'complete'])->name('projects.complete');
    });
});

require __DIR__.'/auth.php';