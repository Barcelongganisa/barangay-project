<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', fn() => view('welcome'));

// Resident routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('resident-dashboard');
    })->name('dashboard');

    // Resident-specific routes
    Route::prefix('resident')->group(function () {
        Route::get('/new-request', function () {
            return view('resident.new-request');
        })->name('resident.new-request');
        
        Route::get('/requests', function () {
            return view('resident.requests');
        })->name('resident.requests');
        
        Route::get('/documents', function () {
            return view('resident.documents');
        })->name('resident.documents');
    });
});

// Admin routes
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard');
        }
        return view('admin.dashboard');
    })->name('admin.dashboard');
    
    Route::get('/manage-requests', function () {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard');
        }
        return view('admin.manage-requests');
    })->name('admin.manage.requests');
    
    Route::get('/manage-residents', function () {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard');
        }
        return view('admin.manage-residents');
    })->name('admin.manage.residents');
    
    Route::get('/reports', function () {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard');
        }
        return view('admin.reports');
    })->name('admin.reports');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';