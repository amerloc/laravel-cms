<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\Admin\CreatePage;
use App\Livewire\Admin\PageEditor;
use App\Livewire\Admin\PageList;
use App\Livewire\Admin\VisualBuilder;
use App\Livewire\ShowPage;
use Illuminate\Support\Facades\Route;

// --- Admin
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/pages', PageList::class)->name('admin.pages.index');
    Route::get('/admin/pages/new', CreatePage::class)->name('admin.pages.create');
    Route::get('/admin/pages/{page}/builder', VisualBuilder::class)->name('admin.pages.builder');
    Route::get('/admin/pages/{page}/edit', PageEditor::class)->name('admin.pages.edit');
});
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Include auth routes BEFORE the catch-all route
require __DIR__.'/auth.php';

// --- Public (single route). Use prefix to avoid conflicts.
Route::get('/p/{slug?}', ShowPage::class)
    ->where('slug', '^[a-z0-9]+(?:[\/-][a-z0-9]+)*$')
    ->name('page.show');

// Redirect root to home page
Route::get('/', function () {
    return redirect('/p/home');
});
