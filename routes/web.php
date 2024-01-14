<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminCasesController;
use App\Models\Role;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

//Route::get('/password/confirm', [CustomPasswordConfirmationController::class, 'showConfirmForm'])->name('password.confirm');
//Route::post('/password/confirm', [CustomPasswordConfirmationController::class, 'confirm']);

Route::middleware(['auth', 'verified'])->group(function () {
    // Admin routes
    Route::middleware(['role:Administrator'])->group(function () {
        Route::get('/admin', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');

        // users
        Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users');

        Route::get('/admin/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
        Route::post('/admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');

        Route::get('/admin/users/{user}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/admin/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');

        Route::delete('/admin/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');

        Route::patch('/admin/users/{user}/activate', [AdminUserController::class, 'activate'])->name('admin.users.activate');
        Route::patch('/admin/users/{user}/deactivate', [AdminUserController::class, 'deactivate'])->name('admin.users.deactivate');

        // cases
        Route::get('/admin/cases', [AdminCasesController::class, 'index'])->name('admin.cases');

        Route::get('/admin/cases/create', [AdminCasesController::class, 'create'])->name('admin.cases.create');
        Route::post('/admin/cases', [AdminCasesController::class, 'store'])->name('admin.cases.store');

        Route::get('/admin/cases/{case}/edit', [AdminCasesController::class, 'edit'])->name('admin.cases.edit');
        Route::put('/admin/cases/{case}', [AdminCasesController::class, 'update'])->name('admin.cases.update');
        Route::post('/admin/cases/{id}/updateStatus', [AdminCasesController::class, 'updateCaseStatus'])->name('admin.cases.statusUpdate');

        Route::put('/admin/cases/{id}/updateDescription', [AdminCasesController::class, 'updateDescription'])->name('admin.cases.updateDescription');
        Route::post('admin/cases/{id}/reassign', [AdminCasesController::class, 'reassignLawyer'])->name('admin.cases.reassignLawyer');

        Route::get('admin/cases/{id}/uploadDocument', [AdminCasesController::class, 'uploadDocument'])->name('admin.cases.uploadDocument');
        Route::post('/admin/cases/{id}/uploadDocument', [AdminCasesController::class, 'uploadDocument'])->name('admin.cases.uploadDocument');

        Route::get('/admin/cases/{id}/deleteDocument', [AdminCasesController::class, 'deleteDocument'])->name('admin.cases.deleteDocument');
        Route::delete('/admin/cases/{id}/deleteDocument', [AdminCasesController::class, 'deleteDocument'])->name('admin.cases.deleteDocument');

        Route::get('/admin/cases/{id}/createHearing', [AdminCasesController::class, 'createHearing'])->name('admin.cases.createHearing');
        Route::post('/admin/cases/{id}/createHearing', [AdminCasesController::class, 'createHearing'])->name('admin.cases.createHearing');

        Route::delete('/admin/hearings/delete/{id}', [AdminCasesController::class, 'deleteHearing'])->name('admin.hearings.delete');

        Route::delete('/admin/cases/{case}', [AdminCasesController::class, 'destroy'])->name('admin.cases.destroy');

        Route::get('/admin/settings', function () {
            return view('profile.edit');
        })->name('admin.profile.edit');
    });


    // Paralegal routes
    Route::middleware(['role:paralegal'])->group(function () {
        Route::get('/', function () {
            return view('paralegal.dashboard');
        })->name('paralegal.dashboard');
    });


    // Lawyer dashboard
    Route::middleware(['role:Lawyer'])->group(function () {
        Route::get('/', function () {
            return view('lawyer.dashboard');
        })->name('lawyer.dashboard');
    });

    // Client dashboard
    Route::middleware(['role:Client'])->group(function () {
        Route::get('/', function () {
            return view('client.dashboard');
        })->name('client.dashboard');
    });

    // Judge routes
    Route::middleware(['judge'])->group(function () {
        // Define judge routes here
    });
});

// Route for guest users
Route::get('/guest', function () {
    return view('guest');
});

// Route for all authenticated users (including regular and admin)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', function () {
        return view('profile.edit');
    })->name('profile');
});


