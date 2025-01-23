<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\PictureController;


// no to make them regester one more time 
Route::get('/', function () {
    return view('welcome');
});

// Route for the dashboard that uses the 'albums.index' method.
Route::get('/dashboard', [AlbumController::class, 'index'])
    ->middleware(['auth', 'verified']) // Make sure only authenticated and verified users can access it.
    ->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::resource('albums', AlbumController::class)->middleware(['auth', 'verified']);
Route::resource('pictures', PictureController::class)->middleware(['auth', 'verified']);
Route::delete('/albums/{album}/photos/{photo}', [PictureController::class, 'delete'])
    ->middleware(['auth', 'verified'])
    ->name('albums.photos.delete');
    // Route to delete all pictures from an album
Route::delete('/albums/{album}/delete-pictures', [AlbumController::class, 'deletePictures'])
->middleware(['auth', 'verified'])
->name('albums.delete-pictures');

// Route to move pictures to another album
Route::post('/albums/{album}/move-pictures', [AlbumController::class, 'movePictures'])
->middleware(['auth', 'verified'])
->name('albums.move-pictures');



// Route::get('albums'[])
require __DIR__.'/auth.php';
