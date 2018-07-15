<?php

namespace Escuela\Http\Controllers;

use Illuminate\Http\Request;

use Escuela\Http\Requests;

use Escuela\Matricula;
use Escuela\DetallePariente;
use Escuela\Estudiante;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

use DB;

class ParienteController extends Controller
{
    
    public function listParientes($id){
        $usuarioactual=\Auth::user();

        $matricula = Matricula::findOrFail($id);
        #$parientes = DetallePariente::all();

        $parientes = DB::table('detalle_pariente as pariente')
        ->select('estudiante.nombre','estudiante.apellido','estudiante.nie','pariente.id_detalle','pariente.parentesco','matricula.fotografia', 'matricula.id_matricula')
        ->join('estudiante as estudiante','pariente.nie','=','estudiante.nie','full outer')
        ->join('matricula as matricula','pariente.nie','=','matricula.nie','full outer')
        ->where('pariente.id_matricula','=',$id)
        ->orderby('estudiante.apellido','asc')
        ->get();

        return view("expediente.matricula.parientes.list", ["parientes"=>$parientes, "id"=>$id, "usuarioactual"=>$usuarioactual]);
    }

    public function addParientes($id){
        $usuarioactual=\Auth::user();

        $estudiantes = DB::table('estudiante')->orderby('estudiante.apellido','asc')->get(); 

        return view("expediente.matricula.parientes.add", ["estudiantes"=>$estudiantes, "id"=>$id, "usuarioactual"=>$usuarioactual]);
    }


    public function store(Request $request, $id){
        $usuarioactual=\Auth::user();

        $id_matricula = $request->get('id_matricula');	//Son Ids de estudiantes
        $parentesco = $request->get('parentesco');


        $cont = 0;

        while ( $cont < count($id_matricula)) {
            $detalle = new DetallePariente();
            $detalle->id_matricula = $id;
            $estudiante = Estudiante::findOrFail($id_matricula[$cont]);
            $detalle->nie = $estudiante->nie;
            $detalle->parentesco = $parentesco[$cont];
            $detalle->save();  
            $cont =$cont+1;
        }

        Session::flash('pair',"Parientes agregado correctamente");

        return Redirect::to('expediente/matricula');
    }


    public function destroy($id)
    {
        $usuarioactual=\Auth::user();

    	$pariente=DetallePariente::findOrFail($id);
    	$pariente->delete();

        Session::flash('deletePair', "El parentesco fué borrado exitosamente");

    	return Redirect::to('expediente/matricula');
    }
}
