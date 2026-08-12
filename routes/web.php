<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    Route::get('/', function () {
        return view('home');
    })->name('home');

    Route::get('/users',[UserController::class, 'index'])->name('users.index');

    Route::get('/users/create',[UserController::class, 'create'])
                ->middleware('can:store, App\Models\User')
                ->name('users.create');
    Route::post('/users/create',[UserController::class, 'store'])->name('users.store');
    
    Route::get('/users/{user}', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::put('/users/{user}/roles', [UserController::class, 'updateRoles'])->name('users.updateRoles');

    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

