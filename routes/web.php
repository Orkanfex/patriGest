<?php

use App\Http\Controllers\PatrimonyController;
use App\Http\Controllers\UserController;
use App\Models\Patrimony;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    Route::get('/', function () {
        return view('home');
    })->name('home');

    //rotas para manipular usuários
    Route::get('/users',[UserController::class, 'index'])->name('users.index');
    Route::get('/users/create',[UserController::class, 'create'])
                ->middleware('can:create,' . User::class)
                ->name('users.create');
    Route::post('/users/create',[UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::put('/users/{user}/roles', [UserController::class, 'updateRoles'])->name('users.updateRoles');
    Route::put('/users/{user}/avatar', [UserController::class, 'updateAvatar'])
                ->name('users.updateAvatar');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    //rotas para manipular patrimonios
    Route::get('/environments/{environment}/patrimonies', [PatrimonyController::class, 'index'])
                ->name('patrimonies.index');
    Route::get('/environments/{environment}/patrimonies/create', [PatrimonyController::class, 'create'])
                ->middleware('can:create,' . Patrimony::class)
                ->name('patrimony.create');
    Route::post('/environments/{environment}/patrimonies/create', [PatrimonyController::class, 'store'])
                ->name('patrimony.store');
    Route::get('/environments/{environment}/patrimonies/{patrimony}', [PatrimonyController::class, 'edit'])
                ->middleware('can:update,' . Patrimony::class)
                ->name('patrimony.edit');
    Route::put('/environments/{environment}/patrimonies/{patrimony}', [PatrimonyController::class, 'update'])
                ->name('patrimony.update');             
    Route::put('/environments/{environment}/patrimonies/{patrimony}/image', [PatrimonyController::class, 'updateImage'])
                ->name('patrimony.updateImage');             
    Route::delete('/environments/{environment}/patrimonies/{patrimony}', [PatrimonyController::class, 'destroy'])
                ->name('patrimonies.destroy');            
                
});

