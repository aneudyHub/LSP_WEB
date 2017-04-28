<?php namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

use App\Fabricante;

class FabricanteController extends Controller {

	public function __construct()
	{
		//$this->middleware('auth.basic.once',['only' => ['store','update','destroy']]);
		$this->middleware('oauth',['only' => ['index','store','update','destroy']]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//return response()->json(['datos' => Fabricante::all()],200);

		$fabricante = Cache::remember('fabricantes',15/60,function (){

			return Fabricante::simplePaginate(4);

		});
		return response()->json(['siguiente' => $fabricante->nextPageUrl(),'anterior' => $fabricante->previousPageUrl(),'datos' => $fabricante->items()],200);

		//return response()->json(['datos' => \DB::select('call get_data()')],200);

		//return \DB::select('call get_data()');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		if(!$request->input('nombre') || !$request->input('telefono'))
		{
			return response()->json(['mensaje' => 'No se pudieron procesar los valores','codigo' => 422],422);
		}

		Fabricante::create($request->all());
		return response()->json(['mensaje' =>'Fabricante insertado'],201);

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//$fabicantes = Fabricante::find($id);
		$fabicantes = \DB::select('call get_data_by_id(?)',array($id));

		if(!$fabicantes)
		{
			return response()->json(['mensaje' => 'No se encuentra este fabricante','codigo' => 404],404);
		}

		return response()->json(['datos' => $fabicantes ],200);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request,$id)
	{
		$metodo = $request->method();
		$fabricante = Fabricante::find($id);
		if(!$fabricante)
		{
			return response()->json(['mensaje' => 'No se encuentra este fabricante','codigo' => 404],404);
		}
		if($metodo === 'PATCH')
		{
			$bandera = false;

			$nombre = $request->input('nombre');

			if($nombre != null && $nombre != '')
			{
				$fabricante->nombre = $nombre;
				$bandera = true;
			}

			$telefono = $request->input('telefono');

			if($telefono != null && $telefono!= '')
			{
				$fabricante->telefono = $telefono;
				$bandera = true;
			}

			if($bandera)
			{
				\DB::statement('CALL update_data_by_id(?,?,?)',array($id,$nombre,$telefono));
				//$fabricantes = \DB::statement('update_data_by_id(?,?,?)',array($id,$nombre,$telefono));
				//$fabricante->save();
				return response()->json(['mensaje' =>'Fabricante editado'],200);
			}

			return response()->json(['mensaje' =>'No se modifico ningun Fabricante'],304);
		}

		$nombre = $request->input('nombre');
		$telefono = $request->input('telefono');
		if(!$nombre || !$telefono)
		{
			return response()->json(['mensaje' => 'No se pudieron procesar los datos','codigo' => 422],422);
		}
		$fabricante->nombre = $nombre;
		$fabricante->telefono = $telefono;

		\DB::statement('CALL update_data_by_id(?,?,?)',array($id,$nombre,$telefono));
		//$fabricante->save();
		return response()->json(['mensaje' =>'Fabricante editado'],200);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$fabricante = Fabricante::find($id);
		if(!$fabricante)
		{
			return response()->json(['mensaje' => 'No se ecuentra este fabricante','codigo' => 404],404);
		}

		$vehiculos = $fabricante-> vehiculos;
		if(sizeof($vehiculos) > 0)
		{
			return response()->json(['mensaje' => 'Este fabricante pose vehiculo asociado no pude ser eleminado. Eleminar primero sus vehiculos','codigo' => 409],409);
		}

		$fabricante->delete();
		return response()->json(['mensaje' =>'Fabricante eliminado'],200);

	}

}
