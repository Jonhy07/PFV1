<?php

namespace App\Http\Controllers;

use App\AbonoVivienda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Exception;

class AbonoViviendaController extends Controller
{
	public function index(Request $request)
	{
		// Filtro por un criterio y estado
		$buscar = $request->buscar;
		$criterio = $request->criterio;
		$completo = (isset($request->completo)) ? $request->completo : $completo = 'false';
		
		if ($completo == 'false')
		{
			$count = AbonoVivienda::where('estado',1)->count();
			if ($buscar==''){
				$abonoVivienda = AbonoVivienda::with('user')->orderBy('id', 'desc')->where('estado',1)->paginate($count);
			}
			else{
				$abonoVivienda = AbonoVivienda::with('user')->where([[$criterio, 'like', $buscar ],['estado',1]])->orderBy('id', 'desc')->paginate($count);
			}
		} else if ($completo == 'true'){
			$count = AbonoVivienda::all()->count();
			if ($buscar==''){
				$abonoVivienda = AbonoVivienda::with('user')->orderBy('id', 'desc')->paginate($count);
			}
			else{
				$abonoVivienda = AbonoVivienda::with('user')->where($criterio, 'like', $buscar)->orderBy('id', 'desc')->paginate($count);
			}
		}
		return [
			"abonoViviendas"=>$abonoVivienda
		];
	}

	public function store(Request $request)
	{
		//if(!$request->ajax())return redirect('/');
		try {
			$abonoVivienda = new AbonoVivienda();
			$abonoVivienda->cantidad_abono = $request->cantidad_abono;
			$abonoVivienda->cantidad_restante = $request->cantidad_restante;
			$abonoVivienda->descripcion = $request->descripcion;
			$abonoVivienda->fecha_pago = $request->fecha_pago;
			$abonoVivienda->user_id = $request->user_id;
			$abonoVivienda->save();
			return Response::json(['message' => 'Abono Vivienda Creada'], 200);
			#return ['id' => $nino->id];
		} catch (Exception $e) {
			return Response::json(['message' => $e->getMessage()], 400);
		}

	}

	public function update(Request $request)
	{
		//if(!$request->ajax())return redirect('/');
		$abonoVivienda = AbonoVivienda::findOrFail($request->id);
		$abonoVivienda->cantidad_abono = $request->cantidad_abono;
		$abonoVivienda->cantidad_restante = $request->cantidad_restante;
		$abonoVivienda->descripcion = $request->descripcion;
		$abonoVivienda->fecha_pago = $request->fecha_pago;
		$abonoVivienda->user_id = $request->user_id;
		$abonoVivienda->save();
		return Response::json(['message' => 'Abono Vivienda Actualizado'], 200);

	}

	public function activar(Request $request)
	{
        #if(!$request->ajax())return redirect('/');
        $abonoVivienda = AbonoVivienda::findOrFail($request->id);
        $abonoVivienda->estado = '1';
        $abonoVivienda->save();
		return Response::json(['message' => 'Abono Vivienda Activada'], 200);
	}
	
	public function desactivar(Request $request)
	{
		#if(!$request->ajax())return redirect('/');
		$abonoVivienda = AbonoVivienda::findOrFail($request->id);
		$abonoVivienda->estado = '0';
		$abonoVivienda->save();
		return Response::json(['message' => 'Abono Vivienda Desactivada'], 200);

	}
}