<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use Illuminate\Support\Facades\Auth;

class BitacoraController extends Controller
{

    public function index()
    {

        $empresaId = Auth::user()->empresa_id;

        $bitacoras = Bitacora::with('usuario')
            ->where('empresa_id',$empresaId)
            ->latest()
            ->limit(200)
            ->get();

        return view('bitacora.index', compact('bitacoras'));

    }

}