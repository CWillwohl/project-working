<?php

use App\Livewire\User\Profile;
use Illuminate\Support\Facades\{Route};
use App\Livewire\Projects\Index as ProjectIndex;
use App\Http\Controllers\Report\WorkedPeriodsPDF;
use App\Livewire\Auth\{Login, PasswordRecover, Register};
use App\Livewire\PunchClock\{Register as PunchClockRegister};
use App\Livewire\Reports\Projects\{EstimateEarnings, ManageReceivements};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/cadastro', Register::class)->name('register');
    Route::get('/recuperar-senha', PasswordRecover::class)->name('password-recover');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', function () {
        auth()->logout();

        return redirect()->route('login');
    })->name('logout');

    Route::get('/', function () {
        return redirect()->route('punch-clock.register');
    })->name('welcome');

    Route::get('/projetos', ProjectIndex::class)->name('projects.index');

    Route::prefix('relatorios')->group(function () {
        Route::prefix('projetos')->group(function () {
            Route::get('/estimar-ganhos', EstimateEarnings::class)->name('reports.projects.estimate-earnings');
            Route::get('/gerenciar-recebimentos', ManageReceivements::class)->name('reports.projects.manage-receivements');
        });
    });

    Route::prefix('ponto-digital')->group(function () {
        Route::get('/registrar-ponto', PunchClockRegister::class)->name('punch-clock.register');
    });

    Route::prefix('usuarios')->group(function () {
        Route::get('/perfil', Profile::class)->name('users.profile');
    });
});
