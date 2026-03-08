<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use App\Models\Categoria;
use App\Models\CentroCosto;
use App\Models\MetodoPago;
use App\Models\TipoMovimiento;
use App\Models\Tercero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\BitacoraHelper;


class MovimientoController extends Controller
{
    
    public function index()
    {
        // Siempre filtramos por empresa para que un usuario no vea datos de otra empresa.
        $empresaId = Auth::user()->empresa_id;

        $movimientos = Movimiento::with(['tipoMovimiento','categoria','usuario','centroCosto.proyecto'])
            ->where('empresa_id',$empresaId)
            ->orderBy('fecha','desc')
            ->get();

        // Estos KPIs son para ver el pulso del día en una sola pantalla.
        $hoy = now()->format('Y-m-d');

        // Ingresos del día según la naturaleza del tipo de movimiento.
        $ingresosHoy = Movimiento::where('empresa_id',$empresaId)
            ->whereDate('fecha',$hoy)
            ->whereHas('tipoMovimiento', function($q){
                $q->where('naturaleza','ingreso');
            })
            ->sum('monto');

        // Egresos del día con la misma lógica.
        $egresosHoy = Movimiento::where('empresa_id',$empresaId)
            ->whereDate('fecha',$hoy)
            ->whereHas('tipoMovimiento', function($q){
                $q->where('naturaleza','egreso');
            })
            ->sum('monto');

        $saldoHoy = $ingresosHoy - $egresosHoy;

        return view('movimientos.index', compact(
            'movimientos',
            'ingresosHoy',
            'egresosHoy',
            'saldoHoy'
        ));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Traemos catálogos ya filtrados por empresa para poblar combos del formulario.
        $empresaId = Auth::user()->empresa_id;

        return view('movimientos.create', [
            'tipos' => TipoMovimiento::where('estado',1)->get(),
            'categorias' => Categoria::where('empresa_id',$empresaId)->get(),
            'metodos' => MetodoPago::where('empresa_id',$empresaId)->get(),
            'terceros' => Tercero::where('empresa_id',$empresaId)->get(),
            'centros' => CentroCosto::with('proyecto')->where('empresa_id',$empresaId)->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación base del formulario.
        $request->validate([
            'tipo_movimiento_id' => 'required',
            'categoria_id' => 'required',
            'centro_costo_id' => 'required|exists:centro_costos,id',
            'monto' => 'required',
            'fecha' => 'required'
        ]);

        // Normalizamos formato de monto por si viene con puntos/comas.
        $monto = $this->normalizarMonto($request->monto);

        if(!is_numeric($monto) || $monto <= 0){
            return back()->withErrors(['monto' => 'Monto inválido']);
        }

        // Movimiento es la tabla central, aquí se registra toda la operación financiera.
        Movimiento::create([

            'empresa_id' => Auth::user()->empresa_id,

            'tipo_movimiento_id' => $request->tipo_movimiento_id,

            'categoria_id' => $request->categoria_id,

            'metodo_pago_id' => $request->metodo_pago_id,

            'centro_costo_id' => $request->centro_costo_id, 

            'tercero_id' => $request->tercero_id,

            'monto' => $monto, 

            'fecha' => $request->fecha,

            'descripcion' => $request->descripcion,

            'usuario_id' => Auth::id(),

            'estado'     => 'confirmado'

        ]);

        return redirect()->route('movimientos.index')
            ->with('success','Movimiento registrado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('movimientos.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $empresaId = Auth::user()->empresa_id;

        $movimiento = Movimiento::where('empresa_id', $empresaId)
            ->findOrFail($id);

        return view('movimientos.edit', [
            'movimiento' => $movimiento,
            'tipos' => TipoMovimiento::where('estado',1)->get(),
            'categorias' => Categoria::where('empresa_id',$empresaId)->get(),
            'metodos' => MetodoPago::where('empresa_id',$empresaId)->get(),
            'terceros' => Tercero::where('empresa_id',$empresaId)->get(),
            'centros' => CentroCosto::with('proyecto')->where('empresa_id',$empresaId)->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $empresaId = Auth::user()->empresa_id;

        $movimiento = Movimiento::where('empresa_id', $empresaId)
            ->findOrFail($id);

        $request->validate([
            'tipo_movimiento_id' => 'required',
            'categoria_id' => 'required',
            'centro_costo_id' => 'required|exists:centro_costos,id',
            'monto' => 'required',
            'fecha' => 'required'
        ]);

        $monto = $this->normalizarMonto($request->monto);

        if(!is_numeric($monto) || $monto <= 0){
            return back()->withErrors(['monto' => 'Monto inválido']);
        }

        $movimiento->update([
            'tipo_movimiento_id' => $request->tipo_movimiento_id,
            'categoria_id' => $request->categoria_id,
            'metodo_pago_id' => $request->metodo_pago_id,
            'centro_costo_id' => $request->centro_costo_id,
            'tercero_id' => $request->tercero_id,
            'monto' => $monto,
            'fecha' => $request->fecha,
            'descripcion' => $request->descripcion,
        ]);

        return redirect()->route('movimientos.index')
            ->with('success', 'Movimiento actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $empresaId = Auth::user()->empresa_id;

        $movimiento = Movimiento::where('empresa_id', $empresaId)
            ->findOrFail($id);

        $movimiento->update([
            'estado' => 'anulado',
        ]);

        return redirect()->route('movimientos.index')
            ->with('editado', 'Movimiento anulado correctamente');
    }

    private function normalizarMonto($monto): string
    {
        $valor = str_replace('.', '', (string) $monto);
        return str_replace(',', '.', $valor);
    }
}
