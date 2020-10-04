<?php

namespace App\Http\Controllers;

use App\EstudioSocioeconomico;
use Illuminate\Http\Request;

class EstudioSocioeconomicoController extends Controller
{

    public function index()
    {
        $buscar = $request->buscar;
		$completo = (isset($request->completo)) ? $request->completo : 'false';

		if ($completo == 'false')
		{
			$count = EstudioSocioeconomico::where('estado',1)->count();
			if ($buscar==''){
				$estudioSocioeconomico = EstudioSocioeconomico::orderBy('id', 'desc')->where('estado',1)->paginate($count);
			}
			else{
				$estudioSocioeconomico = EstudioSocioeconomico::where('estado',1)->where('nombre', 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate($count);
			}
		} else if ($completo == 'true'){
			$count = EstudioSocioeconomico::all()->count();
			if ($buscar==''){
				$estudioSocioeconomico = EstudioSocioeconomico::orderBy('id', 'desc')->paginate($count);
			}
			else{
				$estudioSocioeconomico = EstudioSocioeconomico::where('nombre', 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate($count);
			}
		}
		else if($completo == 'select')
		{
			$count = EstudioSocioeconomico::where('estado', 1)->count();
			$estudioSocioeconomico = EstudioSocioeconomico::orderBy('id', 'desc')->where('estado',1)->paginate($count);
		}
        return [
            'estudioSocioeconomicos' => $estudioSocioeconomico
		];
    }

    public function store(Request $request)
    {
        try {
			$estudioSocioeconomico = new EstudioSocioeconomico();
            $estudioSocioeconomico->fecha_boleta = $request->fecha_boleta;
            $estudioSocioeconomico->fecha_ingreso = $request->fecha_ingreso;
            $estudioSocioeconomico->total_ingresos = $request->total_ingresos;
            $estudioSocioeconomico->alimentacion = $request->alimentacion;
            $estudioSocioeconomico->situacion_vivienda = $request->situacion_vivienda;
            $estudioSocioeconomico->costo_vivienda = $request->costo_vivienda;
            $estudioSocioeconomico->luz = $request->luz;
            $estudioSocioeconomico->agua = $request->agua;
            $estudioSocioeconomico->drenaje = $request->drenaje;
            $estudioSocioeconomico->cantidad_cuartos = $request->cantidad_cuartos;
            $estudioSocioeconomico->bano = $request->bano;
            $estudioSocioeconomico->paredes = $request->paredes;
            $estudioSocioeconomico->techo = $request->techo;
            $estudioSocioeconomico->piso = $request->piso;
            $estudioSocioeconomico->evaluacion_diagnostico = $request->evaluacion_diagnostico;
			$estudioSocioeconomico->save();
			return Response::json(['message' => 'Estudio Socioeconómico Creada'], 200);
		} catch (Exception $e) {
            return Response::json(['message' => $e->getMessage()], 400);
		}
    }

    public function update(Request $request, EstudioSocioeconomico $estudioSocioeconomico)
    {
        try {
            $estudioSocioeconomico = EstudioSocioeconomico::findOrFail($request->id);
            $estudioSocioeconomico->fecha_boleta = $request->fecha_boleta;
            $estudioSocioeconomico->fecha_ingreso = $request->fecha_ingreso;
            $estudioSocioeconomico->total_ingresos = $request->total_ingresos;
            $estudioSocioeconomico->alimentacion = $request->alimentacion;
            $estudioSocioeconomico->situacion_vivienda = $request->situacion_vivienda;
            $estudioSocioeconomico->costo_vivienda = $request->costo_vivienda;
            $estudioSocioeconomico->luz = $request->luz;
            $estudioSocioeconomico->agua = $request->agua;
            $estudioSocioeconomico->drenaje = $request->drenaje;
            $estudioSocioeconomico->cantidad_cuartos = $request->cantidad_cuartos;
            $estudioSocioeconomico->bano = $request->bano;
            $estudioSocioeconomico->paredes = $request->paredes;
            $estudioSocioeconomico->techo = $request->techo;
            $estudioSocioeconomico->piso = $request->piso;
            $estudioSocioeconomico->evaluacion_diagnostico = $request->evaluacion_diagnostico;
            $estudioSocioeconomico->save();
            return Response::json(['message' => 'Estudio Socioecoómico Actualizada'], 200);
            } catch (Exception $e) {
                return Response::json(['message' => $e->getMessage()], 400);
            }
    }

    public function activar(Request $request)
	{
        #if(!$request->ajax())return redirect('/');
        $estudioSocioeconomico = EstudioSocioeconomico::findOrFail($request->id);
        $estudioSocioeconomico->estado = '1';
        $estudioSocioeconomico->save();
		return Response::json(['message' => 'Estudio Socioeconómico Activada'], 200);
	}
	
	public function desactivar(Request $request)
	{
		#if(!$request->ajax())return redirect('/');
		$estudioSocioeconomico = EstudioSocioeconomico::findOrFail($request->id);
		$estudioSocioeconomico->estado = '0';
		$estudioSocioeconomico->save();
		return Response::json(['message' => 'Estudio Socioeconómico Desactivada'], 200);
	}

}