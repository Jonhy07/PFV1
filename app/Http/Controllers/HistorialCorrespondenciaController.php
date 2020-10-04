<?php

namespace App\Http\Controllers;

use App\HistorialCorrespondencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Exception;

class HistorialCorrespondenciaController extends Controller
{
    public function index(Request $request)
    {
		// Filtro por un criterio y estado
		$buscar = $request->buscar;
		$criterio = $request->criterio;
		$completo = (isset($request->completo)) ? $request->completo : $completo = 'false';
		$count = HistorialCorrespondencia::all()->count();
		if ($completo == 'false')
		{
			if ($buscar==''){
				$historialCorrespondencia = HistorialCorrespondencia::with('correspondencia')->with('apadrinamiento')->orderBy('id', 'desc')->where('estado',1)->paginate($count);
			}
			else{
				$historialCorrespondencia = HistorialCorrespondencia::with('correspondencia')->with('apadrinamiento')->where($criterio, 'like', '%'. $buscar . '%')->where('estado',1)->orderBy('id', 'desc')->paginate($count);
			}
		} else if ($completo == 'true'){
			if ($buscar==''){
				$historialCorrespondencia = HistorialCorrespondencia::with('correspondencia')->with('apadrinamiento')->orderBy('id', 'desc')->paginate($count);
			}
			else{
				$historialCorrespondencia = HistorialCorrespondencia::with('correspondencia')->with('apadrinamiento')->where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate($count);
			}
		}
		return [
			"historialCorrespondencias"=>$historialCorrespondencia
		];
    }

    public function store(Request $request)
    {
		//if(!$request->ajax())return redirect('/');
		try {
			$historialCorrespondencia = new HistorialCorrespondencia();
			$historialCorrespondencia->correspondencia_id = $request->correspondencia_id;
			$historialCorrespondencia->apadrinamiento_id = $request->apadrinamiento_id;
			$historialCorrespondencia->save();

			return Response::json(['message' => 'Historial Creado'], 200);
			#return ['id' => $nino->id];
		} catch (Exception $e) {
			return Response::json(['message' => $e->getMessage()], 400);
		}
    }

	public function activar(Request $request)
    {
        #if(!$request->ajax())return redirect('/');
        $historialCorrespondencia = HistorialCorrespondencia::findOrFail($request->id);
        $historialCorrespondencia->estado = '1';
        $historialCorrespondencia->save();
		return Response::json(['message' => 'Historial Activado'], 200);
	}
	public function desactivar(Request $request)
    {
        #if(!$request->ajax())return redirect('/');
        $historialCorrespondencia = HistorialCorrespondencia::findOrFail($request->id);
        $historialCorrespondencia->estado = '0';
        $historialCorrespondencia->save();
		return Response::json(['message' => 'Historial Desactivado'], 200);
	}
}
