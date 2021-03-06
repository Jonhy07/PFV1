<?php

namespace App\Http\Controllers;

use App\Nino;
use Exception;
use App\PersonaSinAcceso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Response;

class NinoController extends Controller
{

    public function index(Request $request)
    {
		#if(!$request->ajax())return redirect('/');
		// Filtro por un criterio y estado
		$buscar = $request->buscar;
		$criterio = $request->criterio;
		$completo = (isset($request->completo)) ? $request->completo : $completo = 'false';	
		$count = Nino::all()->count();
		if ($completo == 'false')
		{
			if ($buscar==''){
				$nino = Nino::with('datos')->with('escuela')->orderBy('id', 'desc')->where('estado',1)->paginate($count);
			}
			else{
				$nino = Nino::with('datos')->with('escuela')->where([[$criterio, 'like', $buscar ],['estado',1]])->orderBy('id', 'desc')->paginate($count);
			}
		} else if ($completo == 'true'){
			if ($buscar==''){
				$nino = Nino::with('datos')->with('escuela')->orderBy('id', 'desc')->paginate($count);
			}
			else{
				$nino = Nino::with('datos')->with('escuela')->where($criterio, 'like', $buscar )->orderBy('id', 'desc')->paginate($count);
			}
		}else if($completo="ninono"){
			$nino = Nino::with('datos')->with('escuela')->where('estado',0)->orderBy('id', 'desc')->paginate($count);
		}
		return [
			"ninos"=>$nino
		];
    }

    public function store(Request $request)
    {
		#if(!$request->ajax())return redirect('/');
        try {
            $persona = new PersonaSinAcceso();
			$persona->nombres = $request->nombres;
			$persona->apellidos = $request->apellidos;
			$persona->genero = $request->genero;
			$persona->fecha_nacimiento = $request->fecha_nacimiento;
			$persona->sector_id = $request->sector_id;
			$persona->direccion = $request->direccion;
			$persona->save();
			
            $nino = new Nino();
            $nino->codigo = $request->codigo;
            $nino->fecha_ingreso = $request->fecha_ingreso;
            $nino->fecha_egreso = $request->fecha_egreso;
            $nino->ruta_imagen = $request->ruta_imagen;
            $nino->grado = $request->grado;
			$nino->ocupacion = $request->ocupacion;
			$nino->actividades = $request->actividades;
            $nino->escuela_id = $request->escuela_id;
			
            $nino->persona_sin_acceso_id = $persona->id;
			$nino->save();
			
			return ['id' => $nino->id];
            #return Response::json(['message' => 'Nino Creado'], 200);
            #return ['id' => $nino->id];
        } catch (Exception $e) {
            return Response::json(['message' => $e->getMessage()], 400);
		}
    }

    public function show(Nino $nino)
    {
        return[
			'codigo'=>$nino->codigo,
			'nombres'=>$nino->datos->nombres,
		];
    }

	public function update(Request $request)
    {
		$nino = Nino::findOrFail($request->id);
		$persona = PersonaSinAcceso::findOrFail($nino->persona_sin_acceso_id);

		$persona->nombres = $request->nombres;
		$persona->apellidos = $request->apellidos;
		$persona->genero = $request->genero;
		$persona->fecha_nacimiento = $request->fecha_nacimiento;
		$persona->sector_id = $request->sector_id;
		$persona->direccion = $request->direccion;
		$nino->codigo        = $request->codigo;
		$nino->fecha_ingreso = $request->fecha_ingreso;
		$nino->fecha_egreso  = $request->fecha_egreso;
		$nino->ruta_imagen   = $request->ruta_imagen;
		$nino->grado = $request->grado;
		$nino->ocupacion = $request->ocupacion;
		$nino->actividades = $request->actividades;
		$nino->escuela_id    = $request->escuela_id;
		
		$nino->save();
		$persona->save();
		
		return Response::json(['message' => 'Nino Actualizada'], 200);
	}

	public function activar(Request $request)
    {
        #if(!$request->ajax())return redirect('/');
        $nino = Nino::findOrFail($request->id);
        $persona = PersonaSinAcceso::findOrFail($nino->id);

        $nino->estado = '1';
		# Lo de desactivar x2
		$persona->estado = '1';
        $nino->save();
        $persona->save();
    }
	public function desactivar(Request $request)
    {
        #if(!$request->ajax())return redirect('/');
        $nino = Nino::findOrFail($request->id);
        $persona = PersonaSinAcceso::findOrFail($nino->id);

		$nino->estado = '0';
		# la pesona no se debe desactiar porque puede seguir yendo a consultas?
        $persona->estado = '0';
        $nino->save();
        $persona->save();
	}
	public function apadrinar(Request $request)
    {
        $nino = Nino::findOrFail($request->id);
		$nino->fecha_ingreso = $request->fecha_ingreso;
        $nino->save();
	}
	public function imagen(Request $request){
		$imagen = $request->photos;

		$nombreEliminar = public_path('storage\public\usuarios\\') .  $request->header("imagenanterior");
		if (File::exists($nombreEliminar)) {
			File::delete($nombreEliminar);
		}
		
		$completo = time() . "." . $imagen->extension();
		$imagen_redi = Image::make($imagen)->resize(300,200);
		$imagen_redi->save(public_path('storage/public/usuarios/'. $completo));
		return Response::json($completo, 200);
	}

	public function apadrinamientos (Request $request)
	{
		$consulta = DB::table('ninos')
		->selectRaw('YEAR(fecha_ingreso) AS Anio, COUNT(*) as total')
		->where('estado',1)
        ->groupBy('Anio')
		->get();
		return response()->json($consulta);
	}

}
