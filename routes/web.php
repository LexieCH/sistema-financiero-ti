<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\TerceroController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\PagoController;


// Redirección inicial
Route::get('/', function () {
    return redirect('/login');
});


// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');


// Rutas protegidas (requieren login)
Route::middleware('auth')->group(function () {

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');


    // Administración
    Route::resource('empresas', EmpresaController::class)
        ->middleware('rol:Admin');

    Route::resource('usuarios', UsuarioController::class)
        ->middleware('rol:Admin');


    // Finanzas
    Route::resource('movimientos', MovimientoController::class)
        ->middleware('rol:Admin,Contador');

    Route::resource('terceros', TerceroController::class)
        ->middleware('rol:Admin,Contador');

    Route::resource('documentos', DocumentoController::class)
        ->middleware('rol:Admin,Contador');


    // Acción especial documento
    Route::patch('/documentos/{documento}/pagado', [DocumentoController::class, 'marcarPagado'])
        ->name('documentos.pagado')
        ->middleware('rol:Admin,Contador');


    // Pagos
    Route::post('/pagos', [PagoController::class, 'store'])
        ->name('pagos.store')
        ->middleware('rol:Admin,Contador');

    Route::resource('pagos', PagoController::class)
        ->only(['index'])
        ->middleware('rol:Admin,Contador');

});

require __DIR__.'/auth.php';