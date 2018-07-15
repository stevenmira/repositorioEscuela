<?php

namespace Escuela\Http\Controllers;

use Illuminate\Http\Request;

use Escuela\Http\Requests;

use Escuela\HojaVida;
use Escuela\Maestro;
use Escuela\MaestroEstudios;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

use Escuela\Http\Requests\DocumentacionFormRequest;
use DB;



class MaestroEstudiosController extends Controller
{

    public function getLista($id_hoja)
    {
        $usuarioactual=\Auth::user();

        $hoja = HojaVida::findOrFail($id_hoja);
        $dui = $hoja->mdui;

        $maestro = Maestro::where('mdui', $dui)->first();

        $estudios = DB::table('estudios')
        ->select('estudios.id_estudios','estudios.id_hoja','estudios.institucion','estudios.tipo','estudios.especialidad','estudios.copia')
        ->Where('estudios.id_hoja','=',$id_hoja)
        ->orderby('estudios.tipo','asc')
        ->get();

        return view("docente.estudios.lista",["estudios"=>$estudios, "maestro"=>$maestro, "id_hoja"=>$id_hoja, "usuarioactual"=>$usuarioactual]);

    }

    public function edit($id_hoja)
    {
        $usuarioactual=\Auth::user();

        $hoja = HojaVida::findOrFail($id_hoja);
        $dui = $hoja->mdui;

        $maestro = Maestro::where('mdui', $dui)->first();

        return view("docente.estudios.edit",["hoja"=>$hoja, "maestro"=>$maestro, "usuarioactual"=>$usuarioactual]);
    }


     public function update(DocumentacionFormRequest $request, $id_hoja)
    {   
        $usuarioactual=\Auth::user();

        $estudio = new MaestroEstudios();
        $estudio->id_hoja = $id_hoja;
        $estudio->institucion = $request->get('institucion');
        $estudio->tipo = $request->get('tipo');
        $estudio->especialidad = $request->get('especialidad');

        if(Input::hasFile('copia')){
            $file = Input::file('copia');
            $file -> move(public_path().'/imagenes/maestros/estudios/', $file->getClientOriginalName());
            $estudio->copia = $file->getClientOriginalName();
            }

        $estudio->save();  

        Session::flash('create',"Registro éxitoso".' ('.$estudio->especialidad.')');


        return Redirect::to('docente/estudios/lista/'.$id_hoja);
    }

    public function show($id_estudios){
        $usuarioactual=\Auth::user();

        $estudio = MaestroEstudios::findOrFail($id_estudios);

        $hoja = HojaVida::findOrFail($estudio->id_hoja);
        $dui = $hoja->mdui;

        $maestro = Maestro::where('mdui', $dui)->first();

        return view("docente.estudios.show",["estudio"=>$estudio, "maestro"=>$maestro, "usuarioactual"=>$usuarioactual]);
    }



    public function destroy($id_estudios){

        $estudio=MaestroEstudios::findOrFail($id_estudios);
        $id_hoja = $estudio->id_hoja;
        $estudio->delete();

        Session::flash('delete',"La especialidad ".'"'.$estudio->especialidad.'"'." fué borrada exitosamente");

        return Redirect::to('docente/estudios/lista/'.$id_hoja);

    }
}





