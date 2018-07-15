<?php

namespace Escuela\Http\Controllers;

use Illuminate\Http\Request;

use Escuela\Http\Requests;

use Escuela\HojaVida;
use Escuela\Maestro;
use Escuela\MaestroTrabajo;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

use Escuela\Http\Requests\MaestroTrabajoFormRequest;
use DB;

class MaestroTrabajoController extends Controller
{

    public function getLista($id_hoja)
    {
        $usuarioactual=\Auth::user();

        $hoja = HojaVida::findOrFail($id_hoja);
        $dui = $hoja->mdui;

        $maestro = Maestro::where('mdui', $dui)->first();

        $trabajos = DB::table('recordlaboral')
        ->select('recordlaboral.id_record','recordlaboral.id_hoja','recordlaboral.cargo','recordlaboral.lugar','recordlaboral.tiempo','recordlaboral.copia')
        ->Where('recordlaboral.id_hoja','=',$id_hoja)
        ->orderby('recordlaboral.cargo','asc')
        ->get();

        return view("docente.trabajos.lista",["trabajos"=>$trabajos, "maestro"=>$maestro, "id_hoja"=>$id_hoja, "usuarioactual"=>$usuarioactual]);

    }


    public function edit($id_hoja)
    {
        $usuarioactual=\Auth::user();

        $hoja = HojaVida::findOrFail($id_hoja);
        $dui = $hoja->mdui;

        $maestro = Maestro::where('mdui', $dui)->first();

        return view("docente.trabajos.edit",["hoja"=>$hoja, "maestro"=>$maestro, "usuarioactual"=>$usuarioactual]);
    }

    public function update(MaestroTrabajoFormRequest $request, $id_hoja)
    {   
        $usuarioactual=\Auth::user();

        $trabajo = new MaestroTrabajo();
        $trabajo->id_hoja = $id_hoja;
        $trabajo->cargo = $request->get('cargo');
        $trabajo->lugar = $request->get('lugar');
        $trabajo->tiempo = $request->get('tiempo');

        if(Input::hasFile('copia')){
            $file = Input::file('copia');
            $file -> move(public_path().'/imagenes/maestros/trabajos/', $file->getClientOriginalName());
            $trabajo->copia = $file->getClientOriginalName();
            }

        $trabajo->save();  

        Session::flash('create',"Registro éxitoso".' ('.$trabajo->cargo.')');


        return Redirect::to('docente/trabajos/lista/'.$id_hoja);

    }

    public function show($id_record){
        $usuarioactual=\Auth::user();

        $trabajo = MaestroTrabajo::findOrFail($id_record);

        $hoja = HojaVida::findOrFail($trabajo->id_hoja);
        $dui = $hoja->mdui;

        $maestro = Maestro::where('mdui', $dui)->first();

        return view("docente.trabajos.show",["trabajo"=>$trabajo, "maestro"=>$maestro, "usuarioactual"=>$usuarioactual]);
    }



    public function destroy($id_record){

        $trabajo=MaestroTrabajo::findOrFail($id_record);
        $id_hoja = $trabajo->id_hoja;
        $trabajo->delete();

        Session::flash('delete',"El cargo  ".'"'.$trabajo->cargo.'"'." fué borrado exitosamente");

        return Redirect::to('docente/trabajos/lista/'.$id_hoja);

    }
}
