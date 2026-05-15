<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\KhassaideController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\CotisationController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\InscriptionController as AdminInscriptionController;
use Illuminate\Support\Facades\Route;

// Page d'attente après inscription
Route::get('/inscription/attente', fn() => inertia('Auth/Attente'))->name('inscription.attente');

// Routes publiques
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/evenements', [EventController::class, 'index'])->name('events.index');
Route::get('/evenements/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('/khassaides', [KhassaideController::class, 'index'])->name('khassaides.index');
Route::get('/khassaides/{khassaide}', [KhassaideController::class, 'show'])->name('khassaides.show');
Route::get('/galerie', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/cotisations', [CotisationController::class, 'index'])->name('cotisations.index');

// Routes membres (authentifiés)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profil', [MemberController::class, 'profil'])->name('member.profil');
    Route::get('/membres', [MemberController::class, 'index'])->name('members.index');
    Route::get('/membres/{member}', [MemberController::class, 'show'])->name('members.show');
    Route::get('/ma-carte', [MemberController::class, 'maCarteShow'])->name('member.card');
    Route::post('/profil/photo', [MemberController::class, 'updatePhoto'])->name('member.photo.update');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Carte membre admin (générer la carte de n'importe quel membre)
Route::middleware(['auth', 'admin'])->get('/admin/membres/{member}/carte', [MemberController::class, 'carteAdmin'])->name('admin.membres.card');

// Routes admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::get('inscriptions', [AdminInscriptionController::class, 'index'])->name('inscriptions.index');
    Route::patch('inscriptions/{user}/approuver', [AdminInscriptionController::class, 'approuver'])->name('inscriptions.approuver');
    Route::delete('inscriptions/{user}/rejeter', [AdminInscriptionController::class, 'rejeter'])->name('inscriptions.rejeter');

    Route::resource('membres', Admin\MemberController::class);
    Route::resource('evenements', Admin\EventController::class);
    Route::resource('khassaides', Admin\KhassaideController::class);
    Route::resource('galerie', Admin\GalleryController::class);
    Route::resource('collections', Admin\CollectionController::class);
    Route::resource('cotisations', Admin\CotisationController::class);
});

require __DIR__.'/auth.php';
