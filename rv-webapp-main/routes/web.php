<?php

use App\Http\Controllers\CrmController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CrmController::class, 'login'])->name('login');
Route::post('/login', [CrmController::class, 'signIn'])->name('login.store');

Route::middleware('crm.auth')->group(function (): void {
    Route::get('/dashboard', [CrmController::class, 'dashboard'])->name('dashboard');
    Route::get('/assign-leads', [CrmController::class, 'assignLeads'])->name('assign-leads.index');
    Route::post('/assign-leads/{lead}/assignment', [CrmController::class, 'updateLeadAssignment'])->name('assign-leads.assignment.update');
    Route::get('/leads/create', [CrmController::class, 'createLead'])->name('leads.create');
    Route::get('/leads/{leadId}', [CrmController::class, 'showLead'])->name('leads.show');
    Route::get('/projects', [CrmController::class, 'projects'])->name('projects.index');
    Route::get('/follow-ups', [CrmController::class, 'followUps'])->name('follow-ups.index');
    Route::get('/reports', [CrmController::class, 'reports'])->name('reports.index');
    Route::post('/reports', [CrmController::class, 'storeReport'])->name('reports.store');
    Route::post('/reports/{report}/review', [CrmController::class, 'reviewReport'])->name('reports.review');
    Route::get('/employees', [CrmController::class, 'employees'])->name('employees.index');
    Route::get('/settings', [CrmController::class, 'settings'])->name('settings.index');
    Route::post('/logout', [CrmController::class, 'logout'])->name('logout');
});
