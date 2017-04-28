<?php
namespace App\Http\Controllers;

use App\Prueba;
use App\Http\Requests;
use Illuminate\Http\Request;


class PruebaController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */



    public function index(Request $request)
    {

        //$stringCriterio = $request->input('contrato');
        $stringImei = $request->input('IMEI');
        //return  $name;

        $stringCriterio='P0441001A%';
        //$stringImei='170';
        //$fabicantes = Fabricante::find($id);
        $fabicantes = \DB::connection('telenord')->select('call spandroidbcontratos(?,?)',array($stringCriterio,$stringImei));

        if(!$fabicantes)
        {
            return response()->json(['mensaje' => 'No se encuentra este fabricante','codigo' => 404],404);
        }

        return response()->json(['datos' => $fabicantes ],200);
    }

    public function show(Request $request)
    {
        $stringCriterio = $request->input('contrato');
        $stringImei = $request->input('IMEI');
        //return  $name;

        $stringCriterio='P04410%';
        //$stringImei='170';
        //$fabicantes = Fabricante::find($id);
        $fabicantes = \DB::connection('telenord')->select('call spandroidbcontratos(?,?)',array($stringCriterio,$stringImei));

        if(!$fabicantes)
        {
            return response()->json(['mensaje' => 'No se encuentra este fabricante','codigo' => 404],404);
        }

        return response()->json(['datos' => $fabicantes ],200);
    }

}