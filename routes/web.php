<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\TerceroController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\CentroCostoController;
use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\TipoDocumentoController;
use App\Models\Movimiento;
use App\Models\Documento;
use Illuminate\Support\Facades\Auth;




// Redirección inicial
Route::get('/', function () {
    return redirect('/login');
});


// Dashboard
Route::get('/dashboard', function () {
    $empresaId = Auth::user()->empresa_id;

    $ingresosDelMes = Movimiento::where('empresa_id', $empresaId)
        ->whereMonth('fecha', now()->month)
        ->whereYear('fecha', now()->year)
        ->whereHas('tipoMovimiento', function ($query) {
            $query->where('naturaleza', 'ingreso');
        })
        ->sum('monto');

    $egresosDelMes = Movimiento::where('empresa_id', $empresaId)
        ->whereMonth('fecha', now()->month)
        ->whereYear('fecha', now()->year)
        ->whereHas('tipoMovimiento', function ($query) {
            $query->where('naturaleza', 'egreso');
        })
        ->sum('monto');

    $totalPendiente = Documento::where('empresa_id', $empresaId)
        ->where('estado', 'pendiente')
        ->sum('total');

    $gastosCentroCosto = Movimiento::where('empresa_id', $empresaId)
        ->whereMonth('fecha', now()->month)
        ->whereYear('fecha', now()->year)
        ->whereHas('tipoMovimiento', function ($query) {
            $query->where('naturaleza', 'egreso');
        })
        ->selectRaw('centro_costo_id, SUM(monto) as total')
        ->groupBy('centro_costo_id')
        ->with('centroCosto')
        ->orderByDesc('total')
        ->get();

    return view('dashboard', compact(
        'ingresosDelMes',
        'egresosDelMes',
        'totalPendiente',
        'gastosCentroCosto'
    ));
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
        ->middleware('permiso:empresas');

    Route::resource('usuarios', UsuarioController::class)
        ->middleware('permiso:usuarios');

    Route::get('/permisos', [PermisoController::class, 'index'])
        ->name('permisos.index')
        ->middleware('permiso:permisos,lectura');

    Route::put('/permisos/{rol}', [PermisoController::class, 'update'])
        ->name('permisos.update')
        ->middleware('permiso:permisos,editar');


    // Finanzas
    Route::resource('movimientos', MovimientoController::class)
        ->middleware('permiso:movimientos');

    Route::resource('terceros', TerceroController::class)
        ->middleware('permiso:terceros');

    Route::resource('documentos', DocumentoController::class)
        ->middleware('permiso:documentos');

    Route::resource('tipos-documentos', TipoDocumentoController::class)
        ->middleware('permiso:tipos-documentos');

    //Proyectos
    Route::resource('proyectos', ProyectoController::class)
        ->middleware('permiso:proyectos');
        
    // Pagos
    Route::post('/pagos', [PagoController::class, 'store'])
        ->name('pagos.store')
        ->middleware('permiso:pagos,crear');

    Route::resource('pagos', PagoController::class)
        ->only(['index'])
        ->middleware('permiso:pagos,lectura');

    //Centro Costos 
    Route::resource('centros-costos', CentroCostoController::class)
        ->middleware('permiso:centros-costos');

        //bitácora

    Route::get('/bitacora', [BitacoraController::class,'index'])
        ->name('bitacora.index')
        ->middleware('permiso:bitacora,lectura');

});

require __DIR__.'/auth.php';