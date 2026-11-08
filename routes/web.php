<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnvironmentController;
use App\Http\Controllers\PatrimonyController;
use App\Http\Controllers\UserController;
use App\Models\Environment;
use App\Models\Patrimony;
use App\Models\User;
use Illuminate\Support\Facades\Route;

    Route::middleware(['auth'])->group(function(){
        Route::get('/', [DashboardController::class, 'index'])->name('home');

    //rotas para manipular usuários
    Route::get('/users',[UserController::class, 'index'])
                ->middleware('can:list,' . User::class)
                ->name('users.index');
                
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
                ->middleware('can:update,patrimony')
                ->name('patrimony.edit');
    Route::put('/environments/{environment}/patrimonies/{patrimony}', [PatrimonyController::class, 'update'])
                ->name('patrimony.update');             
    Route::put('/environments/{environment}/patrimonies/{patrimony}/image', [PatrimonyController::class, 'updateImage'])
                ->name('patrimony.updateImage');         
    Route::post('/environments/{environment}/patrimonies/bulk-move', [PatrimonyController::class, 'bulkMove'])
                ->name('environments.patrimonies.bulk-move');
    Route::delete('/environments/{environment}/patrimonies/{patrimony}', [PatrimonyController::class, 'destroy'])
                ->middleware('can:delete,patrimony')
                ->name('patrimonies.destroy');            
    // rotas para manipular ambientes                
    Route::get('/environments',[EnvironmentController::class, 'index'])
                ->name('environments.index');
    Route::get('/environments/create',[EnvironmentController::class, 'create'])
                ->name('environments.create')
                ->middleware('can:create,' . Environment::class);
    Route::post('/environments/create',[EnvironmentController::class, 'store'])
                ->name('environments.store');
    Route::get('/environments/{environment}/edit',[EnvironmentController::class, 'edit'])
                ->middleware('can:update,' . Environment::class)
                ->name('environments.edit');
    Route::put('/environments/{environment}/edit',[EnvironmentController::class, 'update'])
                ->middleware('can:update,' . Environment::class)
                ->name('environments.update');
});

