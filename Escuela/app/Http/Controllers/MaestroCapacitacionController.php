<?php

namespace Escuela\Http\Controllers;

use Illuminate\Http\Request;

use Escuela\Http\Requests;

use Escuela\HojaVida;
use Escuela\Maestro;
use Escuela\MaestroCapacitacion;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

use Escuela\Http\Requests\MaestroCapacitacionFormRequest;
use DB;

class MaestroCapacitacionController extends Controller
{

    public function getLista($id_hoja)
    {
        $usuarioactual=\Auth::user();

        $hoja = HojaVida::findOrFail($id_hoja);
        $dui = $hoja->mdui;

        $maestro = Maestro::where('mdui', $dui)->first();

        $capacitaciones = DB::table('capacitacion')
        ->select('capacitacion.id_capacitacion','capacitacion.id_hoja','capacitacion.anio','capacitacion.horas','capacitacion.nombre','capacitacion.copia')
        ->Where('capacitacion.id_hoja','=',$id_hoja)
        ->orderby('capacitacion.anio','asc')
        ->get();

        return view("docente.capacitaciones.lista",["capacitaciones"=>$capacitaciones, "maestro"=>$maestro, "id_hoja"=>$id_hoja, "usuarioactual"=>$usuarioactual]);

    }



    public function edit($id_hoja)
    {
        $usuarioactual=\Auth::user();

        $hoja = HojaVida::findOrFail($id_hoja);
        $dui = $hoja->mdui;

        $maestro = Maestro::where('mdui', $dui)->first();

        return view("docente.capacitaciones.edit",["hoja"=>$hoja, "maestro"=>$maestro, "usuarioactual"=>$usuarioactual]);
    }

    public function update(MaestroCapacitacionFormRequest $request, $id_hoja)
    {   
        $usuarioactual=\Auth::user();

        $capacitacion = new MaestroCapacitacion();
        $capacitacion->id_hoja = $id_hoja;
        $capacitacion->nombre = $request->get('nombre');
        $capacitacion->anio = $request->get('anio');
        $capacitacion->horas = $request->get('horas');

        if(Input::hasFile('copia')){
            $file = Input::file('copia');
            $file -> move(public_path().'/imagenes/maestros/capacitaciones/', $file->getClientOriginalName());
            $capacitacion->copia = $file->getClientOriginalName();
            }

        $capacitacion->save();

        Session::flash('create',"Registro éxitoso".' ('.$capacitacion->nombre.')');


        return Redirect::to('docente/capacitaciones/lista/'.$id_hoja);
    }

    public function show($id_capacitacion){
        $usuarioactual=\Auth::user();

        $capacitacion = MaestroCapacitacion::findOrFail($id_capacitacion);

        $hoja = HojaVida::findOrFail($capacitacion->id_hoja);
        $dui = $hoja->mdui;

        $maestro = Maestro::where('mdui', $dui)->first();

        return view("docente.capacitaciones.show",["capacitacion"=>$capacitacion, "maestro"=>$maestro, "usuarioactual"=>$usuarioactual]);
    }

    public function destroy($id_capacitacion){

        $capacitacion=MaestroCapacitacion::findOrFail($id_capacitacion);
        $id_hoja = $capacitacion->id_hoja;
        $capacitacion->delete();

        Session::flash('delete',"La capacitación ".'"'.$capacitacion->nombre.'"'." fué borrada exitosamente");

        return Redirect::to('docente/capacitaciones/lista/'.$id_hoja);

    }
}
