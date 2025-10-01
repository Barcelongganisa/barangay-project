<?php
use App\Http\Controllers\ResidentProfileController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResidentController;
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

        // Request details and download routes
        Route::get('/requests/{id}', [ResidentController::class, 'showRequestDetails'])
            ->name('resident.request.details');
        
        Route::get('/requests/{id}/download', [ResidentController::class, 'downloadDocument'])
            ->name('resident.documents.download');

        // Profile routes
        Route::get('/profile', [ResidentProfileController::class, 'edit'])->name('resident.resident-profile');
        Route::patch('/profile', [ResidentProfileController::class, 'update'])->name('resident.resident-profile.update');
        Route::delete('/profile', [ResidentProfileController::class, 'destroy'])->name('resident.resident-profile.destroy');
        // // profile
        // Route::patch('/profile/update-address', [ResidentProfileController::class, 'updateAddress'])
        // ->name('resident.resident-profile.update-address');

        // Create new request with documents
        Route::post('/requests/upload', [ResidentController::class, 'storeRequestWithDocuments'])
            ->name('resident.storeRequestWithDocuments');
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