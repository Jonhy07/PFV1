<?php

namespace App\Http\Controllers;

use App\Cita;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class CitaController extends Controller
{
	public function index(Request $request)
	{
		$buscar = $request->buscar;
		$criterio = $request->criterio;
		$completo = (isset($request->completo)) ? $request->completo :'false';
		$count = Cita::all()->count();
		if ($completo == 'false')
		{
			if ($buscar==''){
				$cita = Cita::with('clinico')->with('datos_clinico')->with('paciente')->with('datos_paciente')->with('tipoCita')->orderBy('fecha', 'desc')->where('estado',1)->paginate($count);
			}
			else{
				$cita = Cita::with('clinico')->with('datos_clinico')->with('paciente')->with('datos_paciente')->with('tipoCita')->where([[$criterio, 'like',$buscar],['estado',1]])->orderBy('fecha', 'desc')->paginate($count);
			}
		} else if ($completo == 'true'){
			if ($buscar==''){
				$cita = Cita::with('clinico')->with('datos_clinico')->with('paciente')->with('datos_paciente')->with('tipoCita')->orderBy('fecha', 'desc')->paginate($count);
			}
			else{
				$cita = Cita::with('clinico')->with('datos_clinico')->with('paciente')->with('datos_paciente')->with('tipoCita')->where($criterio,'like',$buscar)->orderBy('fecha', 'desc')->paginate($count);
			}
		}
		return [
			"citas"=>$cita
		];
	}

	public function store(Request $request)
	{
		try {
			$cita = new Cita();
			$cita->fecha = $request->fecha;
			$cita->descripcion = $request->descripcion;
			$cita->clinico_id = $request->clinico_id;
			$cita->paciente_id = $request->paciente_id;
			$cita->tipo_cita_id = $request->tipo_cita_id;
			$cita->save();
			return Response::json(['message' => 'Cita Creada'], 200);
			#return ['id' => $nino->id];
		} catch (Exception $e) {
			return Response::json(['message' => $e->getMessage()], 400);
		}
	}

	public function update(Request $request)
	{
		$cita = Cita::findOrFail($request->id);
		$cita->fecha = $request->fecha;
		$cita->descripcion = $request->descripcion;
		$cita->clinico_id = $request->clinico_id;
		$cita->paciente_id = $request->paciente_id;
		$cita->tipo_cita_id = $request->tipo_cita_id;
		$cita->save();
		return Response::json(['message' => 'Cita Actualizado'], 200);

	}

	public function activar(Request $request)
	{
		$cita = Cita::findOrFail($request->id);
        $cita->estado = '1';
        $cita->save();
		return Response::json(['message' => 'Cita Activado'], 200);
	}

	public function desactivar(Request $request)
	{
		$cita = Cita::findOrFail($request->id);
        $cita->estado = '0';
        $cita->save();
		return Response::json(['message' => 'Cita Desactivado'], 200);
	}
	public function reporteCitas (Request $request)
	{
		$completo= $request->completo;
		$anio = $request->anio;
        if ($completo =='true'){
			if($anio==''){
				$consulta = DB::table('citas')
				->selectRaw('YEAR(fecha) AS anio, COUNT(*) AS total')
				->groupBy('anio')
				->get();
				return response()->json($consulta);
			}
			else
			{
				$consulta = DB::table('citas')
				->selectRaw('MONTH(fecha) AS mes, COUNT(*) AS total')
				->whereYear('fecha',$anio)
				->groupBy('mes')
				->get();
				return response()->json($consulta);
			}
		} 

	}
}