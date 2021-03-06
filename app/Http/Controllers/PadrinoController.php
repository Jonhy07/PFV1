<?php

namespace App\Http\Controllers;

use App\Encargado;
use App\Padrino;
use App\PersonaSinAcceso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Exception;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class PadrinoController extends Controller
{

    public function index(Request $request)
    {
		// Filtro por un criterio y estado
		$buscar = $request->buscar;
		$criterio = $request->criterio;
		$completo = (isset($request->completo)) ? $request->completo : $completo = 'false';
		if ($completo == 'false')
		{
			$count = Padrino::where('estado',1)->count();
			if ($buscar==''){
				$padrino = Padrino::with('datos')->with('apadrinamiento')->orderBy('id', 'desc')->where('estado',1)->paginate($count);
			}
			else{
				$padrino = Padrino::with('datos')->with('apadrinamiento')->where([[$criterio, 'like', $buscar],['estado',1]])->orderBy('id', 'desc')->paginate($count);
			}
		} else if ($completo == 'true'){
			$count = Padrino::all()->count();
			if ($buscar==''){
				$padrino = Padrino::with('datos')->with('apadrinamiento')->orderBy('id', 'desc')->paginate($count);
			}
			else{
				$padrino = Padrino::with('datos')->with('apadrinamiento')->where($criterio, 'like', $buscar)->orderBy('id', 'desc')->paginate($count);
			}
		}
		return [
			"padrinos"=>$padrino
		];
    }

    public function store(Request $request)
    {
		//if(!$request->ajax())return redirect('/');
		try {
			$persona = new PersonaSinAcceso();
			$persona->nombres = $request->nombres;
			$persona->apellidos = $request->apellidos;
			$persona->CUI = $request->CUI;
			$persona->numero_telefono = $request->numero_telefono;
			$persona->genero = $request->genero;
			$persona->fecha_nacimiento = $request->fecha_nacimiento;
			$persona->sector_id = $request->sector_id;
			$persona->direccion = $request->direccion;
			$persona->save();

			$encargado = new Padrino();
			$encargado->ruta_imagen = $request->ruta_imagen;
			$encargado->correo = $request->correo;
			$encargado->persona_sin_acceso_id = $persona->id;
			$encargado->save();

			return Response::json(['message' => 'Encargado Creado'], 200);
			#return ['id' => $nino->id];
		} catch (Exception $e) {
			return Response::json(['message' => $e->getMessage()], 400);
		}
    }

    public function show(Padrino $padrino)
    {
        //
    }

    public function update(Request $request)
    {
		//if(!$request->ajax())return redirect('/');
		$padrino = Padrino::findOrFail($request->id);
		$persona = PersonaSinAcceso::findOrFail($padrino->persona_sin_acceso_id);
		$persona->nombres = $request->nombres;
		$persona->apellidos = $request->apellidos;
		$persona->genero = $request->genero;
		$persona->fecha_nacimiento = $request->fecha_nacimiento;
		$persona->CUI = $request->CUI;
		$persona->numero_telefono = $request->numero_telefono;
		$persona->direccion = $request->direccion;
		
		$padrino->ruta_imagen = $request->ruta_imagen;
		$padrino->correo = $request->correo;
		$persona->save();
		$padrino->save();

		return Response::json(['message' => 'Padrino Actualizado'], 200);
    }

	public function activar(Request $request)
    {
        #if(!$request->ajax())return redirect('/');
        $padrino = Padrino::findOrFail($request->id);
        $persona = PersonaSinAcceso::findOrFail($padrino->id);

        $padrino->estado = '1';
        $persona->estado = '1';
        $padrino->save();
        $persona->save();
    }
	public function desactivar(Request $request)
    {
        #if(!$request->ajax())return redirect('/');
        $padrino = Padrino::findOrFail($request->id);
        $persona = PersonaSinAcceso::findOrFail($padrino->id);

        $padrino->estado = '0';
        $persona->estado = '0';
        $padrino->save();
        $persona->save();
	}

	public function imagen(Request $request){
		$imagen = $request->photos;
		$nombreEliminar = public_path('storage\public\padrinos\\') .  $request->header("imagenanterior");
		if (File::exists($nombreEliminar)) {
			File::delete($nombreEliminar);
		}
		$completo = time() . "." . $imagen->extension();
		$imagen_redi = Image::make($imagen)->resize(300,200);
		$imagen_redi->save(public_path('storage/public/'. $completo));
		return Response::json($completo, 200);
	}
	
}
