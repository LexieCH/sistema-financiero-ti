<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
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
        $inicioMes = now()->startOfMonth();
        $finMes = now()->endOfMonth();

        $movimientos = Movimiento::with(['tipoMovimiento','usuario','centroCosto.proyecto','documento'])
            ->where('empresa_id',$empresaId)
            ->orderBy('fecha','desc')
            ->get();

        // KPIs del mes actual con suma condicional para evitar desajustes por tipos incompletos.
        $totalesMes = Movimiento::query()
            ->join('tipo_movimientos as tm', 'tm.id', '=', 'movimientos.tipo_movimiento_id')
            ->where('movimientos.empresa_id', $empresaId)
            ->whereBetween('movimientos.fecha', [$inicioMes, $finMes])
            ->where('movimientos.estado', '!=', 'anulado')
            ->selectRaw("COALESCE(SUM(CASE WHEN LOWER(COALESCE(tm.naturaleza, '')) = 'ingreso' OR LOWER(COALESCE(tm.nombre, '')) = 'ingreso' THEN movimientos.monto ELSE 0 END), 0) as ingresos_mes")
            ->selectRaw("COALESCE(SUM(CASE WHEN LOWER(COALESCE(tm.naturaleza, '')) = 'egreso' OR LOWER(COALESCE(tm.nombre, '')) = 'egreso' THEN movimientos.monto ELSE 0 END), 0) as egresos_mes")
            ->first();

        $ingresosHoy = (float) ($totalesMes->ingresos_mes ?? 0);
        $egresosHoy = (float) ($totalesMes->egresos_mes ?? 0);

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
