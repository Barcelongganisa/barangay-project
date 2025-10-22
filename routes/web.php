<?php

use App\Http\Controllers\ResidentProfileController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ManageRequestsController;
use App\Http\Controllers\Admin\ManageResidentsController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\UserApprovalController;
use App\Http\Controllers\Admin\CheckController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', fn() => view('welcome'));

// Resident routes - ADD 'approved' MIDDLEWARE BACK
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [ResidentController::class, 'dashboard'])->name('dashboard');

    // Resident-specific routes
    Route::prefix('resident')->name('resident.')->group(function () {
        Route::get('/new-request', function () {
            return view('resident.new-request');
        })->name('new-request');
        
        Route::get('/requests', function () {
            return view('resident.requests');
        })->name('requests');
        
        Route::get('/documents', function () {
            return view('resident.documents');
        })->name('documents');

        // Request details and download routes
        Route::get('/requests/{id}', [ResidentController::class, 'showRequestDetails'])
            ->name('request.details');
        
        Route::get('/requests/{id}/download', [ResidentController::class, 'downloadDocument'])
            ->name('documents.download');

        // Profile routes
        Route::get('/profile', [ResidentProfileController::class, 'edit'])->name('resident-profile');
        Route::patch('/profile', [ResidentProfileController::class, 'update'])->name('resident-profile.update');
        Route::delete('/profile', [ResidentProfileController::class, 'destroy'])->name('resident-profile.destroy');

        // Profile photo update route
        Route::patch('/profile/photo', [ResidentProfileController::class, 'updatePhoto'])
            ->name('profile-photo.update');

        // Create new request with documents
        Route::post('/requests/upload', [ResidentController::class, 'storeRequestWithDocuments'])
            ->name('storeRequestWithDocuments');
    });
});

// Admin routes - No 'approved' middleware needed for admin
Route::middleware(['auth'])->prefix('admin')->group(function () {
    // Dashboard route
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // User Approval routes
    Route::get('/user-approvals', [UserApprovalController::class, 'pendingUsers'])->name('admin.user.approvals');
    Route::post('/users/{id}/approve', [UserApprovalController::class, 'approveUser'])->name('admin.users.approve');
    Route::post('/users/{id}/decline', [UserApprovalController::class, 'declineUser'])->name('admin.users.decline');
    Route::get('/users/{id}/details', [UserApprovalController::class, 'getUserDetails'])->name('admin.users.details');
    
    // Manage Requests routes
    Route::get('/manage-requests', [ManageRequestsController::class, 'index'])->name('admin.manage.requests');
    Route::get('/requests/{id}/details', [ManageRequestsController::class, 'getRequestDetails'])->name('admin.requests.details');
    Route::post('/requests/{id}/status', [ManageRequestsController::class, 'updateStatus'])->name('admin.requests.updateStatus');
    Route::get('/requests/{id}/payment-details', [ManageRequestsController::class, 'getPaymentDetails'])->name('admin.requests.paymentDetails'); 
    Route::get('/requests/{id}/download-completed', [ManageRequestsController::class, 'downloadCompleted'])
        ->name('admin.requests.download-completed');
    
    // Manage Residents routes  
    Route::get('/manage-residents', [ManageResidentsController::class, 'index'])->name('admin.manage.residents');
    Route::get('/residents/{id}/details', [ManageResidentsController::class, 'getResidentDetails'])->name('admin.residents.details');
    Route::get('/residents/{id}/history', [ManageResidentsController::class, 'getResidentHistory'])->name('admin.residents.history');
    Route::delete('/residents/{id}', [ManageResidentsController::class, 'removeResident'])->name('admin.residents.remove');
        
    // Reports routes
    Route::get('/reports', [ReportsController::class, 'index'])->name('admin.reports');
    Route::post('/reports/export', [ReportsController::class, 'export'])->name('admin.reports.export');
});

// Default Laravel Breeze profile routes - ADD 'approved' MIDDLEWARE
Route::middleware(['auth', 'approved'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/test-approved', function () {
    return 'Middleware works!';
})->middleware('approved');

Route::get('/test-smtp', function () {
    try {
        // Test basic email
        \Mail::raw('Hello! This is a test email from SMTP.', function ($message) {
            $message->to('barcelonjustinebenedict.bsit@gmail.com')
                    ->subject('SMTP Test from Laravel');
        });
        
        return "✅ SMTP is working! Check your email.";
        
    } catch (\Exception $e) {
        return "❌ SMTP Error: " . $e->getMessage();
    }
});

require __DIR__.'/auth.php';