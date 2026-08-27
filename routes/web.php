<?php

use App\Http\Controllers\PatrimonyController;
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
    Route::put('/users/{user}/avatar', [UserController::class, 'updateAvatar'])
                ->name('users.updateAvatar');

    Route::get('/environments/{environment}/patrimonies', [PatrimonyController::class, 'index'])
                ->name('patrimonies.index');
    Route::get('/environments/{environment}/patrimony/create', [PatrimonyController::class, 'create'])
                ->middleware('can:store, App\Models\User')
                ->name('patrimony.create');
    Route::post('/environments/{environment}/patrimony/create', [PatrimonyController::class, 'store'])
                ->name('patrimony.store');
    Route::delete('/environments/{environment}/patrimonies/{patrimony}', [PatrimonyController::class, 'destroy'])
                ->name('patrimonies.destroy');            
                
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

