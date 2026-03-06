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



class MovimientoController extends Controller
{
    
    public function index()
    {
        $empresaId = Auth::user()->empresa_id;

        $movimientos = Movimiento::with(['tipoMovimiento','categoria','usuario'])
            ->where('empresa_id',$empresaId)
            ->orderBy('fecha','desc')
            ->get();

        // fecha hoy
        $hoy = now()->format('Y-m-d');

        // ingresos hoy
        $ingresosHoy = Movimiento::where('empresa_id',$empresaId)
            ->whereDate('fecha',$hoy)
            ->whereHas('tipoMovimiento', function($q){
                $q->where('naturaleza','ingreso');
            })
            ->sum('monto');

        // egresos hoy
        $egresosHoy = Movimiento::where('empresa_id',$empresaId)
            ->whereDate('fecha',$hoy)
            ->whereHas('tipoMovimiento', function($q){
                $q->where('naturaleza','egreso');
            })
            ->sum('monto');

        $saldoHoy = $ingresosHoy - $egresosHoy;

        $gastosCentroCosto = Movimiento::where('empresa_id',$empresaId)
            ->whereHas('tipoMovimiento', function($q){
                $q->where('naturaleza','egreso');
            })
            ->selectRaw('centro_costo_id, SUM(monto) as total')
            ->groupBy('centro_costo_id')
            ->with('centroCosto')
            ->get();

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
        $empresaId = Auth::user()->empresa_id;

        return view('movimientos.create', [
            'tipos' => TipoMovimiento::where('estado',1)->get(),
            'categorias' => Categoria::where('empresa_id',$empresaId)->get(),
            'metodos' => MetodoPago::where('empresa_id',$empresaId)->get(),
            'terceros' => Tercero::where('empresa_id',$empresaId)->get(),
            'centros' => CentroCosto::where('empresa_id',$empresaId)->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tipo_movimiento_id' => 'required',
            'categoria_id' => 'required',
            'centro_costo_id' => 'nullable|exists:centro_costos,id',
            'monto' => 'required',
            'fecha' => 'required'
        ]);

        $monto = str_replace('.', '', $request->monto);
        $monto = str_replace(',', '.', $monto);

        if(!is_numeric($monto) || $monto <= 0){
            return back()->withErrors(['monto' => 'Monto inválido']);
        }

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

            'usuario_id' => auth()->id()

        ]);

        return redirect()->route('movimientos.index')
            ->with('success','Movimiento registrado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
