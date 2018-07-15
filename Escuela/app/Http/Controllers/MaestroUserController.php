<?php

namespace Escuela\Http\Controllers;

use Illuminate\Http\Request;

use Escuela\Http\Requests;

use Escuela\MaestroUser;
use Escuela\Asignacion;
use Escuela\DetalleAsignacion;
use Escuela\DetalleNota;
use Escuela\DetalleGrado;
use Escuela\Grado;
use Escuela\Seccion;
use Escuela\Turno;
use Escuela\User;
use Escuela\Materia;
use Escuela\Estudiante;
use Escuela\Matricula;

use Escuela\Http\Requests\NotaCiclo1Trim1FormRequest;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

use Carbon\Carbon; //Para la zona fecha horaria

use DB;

class MaestroUserController extends Controller
{
    public function __construct()	//para validar
    {
    }

    public function index(Request $request)
    {      
        $usuarioactual=\Auth::user();

    	if($request)
    	{	
    		//Se busca el docente registrado
 			$id_user = $usuarioactual->id_usuario;
 			$det_user = MaestroUser::where('id_usuario', $id_user)->first();
 			$mdui = $det_user->mdui;

            //Obtenemos el año presente
            $query3 = Carbon::now();
            $query3 = $query3->format('Y');

            //Obtenemos la fecha actual
            $date = Carbon::now(); 
            $date = $date->format('l jS \\of F Y h:i:s A');

            //Consulta todas las materias del docente

            //Asignaciones en el turno matutino

            $asig_mat =DB::table('asignacion')
                ->select('asignacion.id_asignacion', 'asignacion.id_detalleasignacion', 'asignacion.id_materia', 'asignacion.mdui', 'asignacion.anioasignacion', 'maestro.nombre',
                'maestro.apellido', 'materia.nombre as nombremateria', 'detalle_asignacion.iddetallegrado', 'detalle_grado.iddetallegrado', 'detalle_grado.idgrado', 'detalle_grado.idseccion',
                'detalle_grado.idturno', 'turno.nombre as nombreturno', 'seccion.nombre as nombreseccion', 'grado.nombre as nombregrado')
                ->join('maestro as maestro', 'asignacion.mdui', '=', 'maestro.mdui', 'full outer')
                ->join('detalle_asignacion as detalle_asignacion', 'asignacion.id_detalleasignacion', '=', 'detalle_asignacion.id_detalleasignacion', 'full outer')
                ->join('materia as materia', 'asignacion.id_materia', '=', 'materia.id_materia', 'full outer')
                ->join('detalle_grado as detalle_grado', 'detalle_asignacion.iddetallegrado', '=', 'detalle_grado.iddetallegrado', 'full outer')
                ->join('turno as turno', 'detalle_grado.idturno', '=', 'turno.idturno', 'full outer')
                ->join('seccion as seccion', 'detalle_grado.idseccion', '=', 'seccion.idseccion', 'full outer')
                ->join('grado as grado', 'detalle_grado.idgrado', '=', 'grado.idgrado', 'full outer')
                ->Where('asignacion.anioasignacion', '=', $query3)
                ->Where('maestro.mdui', '=', $mdui)
                ->Where('detalle_grado.idturno', '=', 1)    //Matutino
                ->orderBy('detalle_grado.idgrado', 'asc')
                ->get();


            //Asignaciones en el turno vespertino

            $asig_ver =DB::table('asignacion')
                ->select('asignacion.id_asignacion', 'asignacion.id_detalleasignacion', 'asignacion.id_materia', 'asignacion.mdui', 'asignacion.anioasignacion', 'maestro.nombre',
                'maestro.apellido', 'materia.nombre as nombremateria', 'detalle_asignacion.iddetallegrado', 'detalle_grado.iddetallegrado', 'detalle_grado.idgrado', 'detalle_grado.idseccion',
                'detalle_grado.idturno', 'turno.nombre as nombreturno', 'seccion.nombre as nombreseccion', 'grado.nombre as nombregrado')
                ->join('maestro as maestro', 'asignacion.mdui', '=', 'maestro.mdui', 'full outer')
                ->join('detalle_asignacion as detalle_asignacion', 'asignacion.id_detalleasignacion', '=', 'detalle_asignacion.id_detalleasignacion', 'full outer')
                ->join('materia as materia', 'asignacion.id_materia', '=', 'materia.id_materia', 'full outer')
                ->join('detalle_grado as detalle_grado', 'detalle_asignacion.iddetallegrado', '=', 'detalle_grado.iddetallegrado', 'full outer')
                ->join('turno as turno', 'detalle_grado.idturno', '=', 'turno.idturno', 'full outer')
                ->join('seccion as seccion', 'detalle_grado.idseccion', '=', 'seccion.idseccion', 'full outer')
                ->join('grado as grado', 'detalle_grado.idgrado', '=', 'grado.idgrado', 'full outer')
                ->Where('asignacion.anioasignacion', '=', $query3)
                ->Where('maestro.mdui', '=', $mdui)
                ->Where('detalle_grado.idturno', '=', 2)    //Vespertino
                ->orderBy('detalle_grado.idgrado', 'asc')
                ->get();    





 			return view('userDocente.materias.index',["date"=>$date,"mdui"=>$mdui,"asig_mat"=>$asig_mat, "asig_ver"=>$asig_ver, "usuarioactual"=>$usuarioactual]);
    	}
    }

    public function getLista(Request $request, $a1, $a2)
    {
        $usuarioactual=\Auth::user();
        if ($request) {

            $id=$a1;
            $id2=$a2;

            //Se procede a buscar la  asignacion dado el id_asignacion
            $asig = Asignacion::where('id_asignacion', $id)->first();

            //Se busca el detalle_asignacion
            $id_detalleasignacion = $asig->id_detalleasignacion;
            $detalle_asignacion = DetalleAsignacion::where('id_detalleasignacion', $id_detalleasignacion)->first();

            //Se busca el detalle_grado
            $iddetallegrado = $detalle_asignacion->iddetallegrado;
            $detalle_grado = DetalleGrado::where('iddetallegrado', $iddetallegrado)->first();

            //Se busca el grado seccion y turno
            $idgrado = $detalle_grado->idgrado;
            $idseccion = $detalle_grado->idseccion;
            $idturno = $detalle_grado->idturno;

            //Obtenemos los objetos
            $nGrado=Grado::where('idgrado','=',$idgrado)->first();
            $nSeccion=Seccion::where('idseccion','=',$idseccion)->first();
            $nTurno=Turno::where('idturno','=',$idturno)->first();       

             
            //Obtenemos el año presente
            $query3 = Carbon::now();
            $query3 = $query3->format('Y');

            //Obtenemos la fecha actual
            $date = Carbon::now(); 
            $date = $date->format('l jS \\of F Y h:i:s A');

            //$query3 = 2017;
            $query = trim($request->get('searchText'));
            $est = DB::table('estudiante')
                ->select('estudiante.nombre','estudiante.apellido','estudiante.nie','matricula.fechamatricula','matricula.id_matricula')
                ->join('matricula as matricula','estudiante.nie','=','matricula.nie','full outer')
                ->join('detalle_grado as detalle_grado','matricula.iddetallegrado','=','detalle_grado.iddetallegrado','full outer')
                ->where('matricula.iddetallegrado','=',$iddetallegrado)
                ->whereYear('matricula.fechamatricula','=',$query3)
                ->where('estudiante.apellido','LIKE','%'.$query.'%')
                ->orderby('estudiante.apellido','asc')
                ->get();

            if ($est==null) {

                    Session::flash('message',"No hay Estudiantes Inscritos en este Curso");
                    return view("userDocente.lista.estudiantes",["date"=>$date, "nGrado"=>$nGrado, "nSeccion"=>$nSeccion, "nTurno"=>$nTurno, "est"=>$est, "usuarioactual"=>$usuarioactual]);
        
                }

            return view('userDocente.lista.estudiantes',["searchText"=>$query, "date"=>$date, "nGrado"=>$nGrado, "nSeccion"=>$nSeccion, "nTurno"=>$nTurno,"id"=>$id, "est"=>$est, "usuarioactual"=>$usuarioactual]);
        }
         
    }


    public function show($id)
    {
        $usuarioactual=\Auth::user();
    }

    public function create()
    {
        
    }

    //Recibe el id de la asignacion y de la matricula
    public function store (Request $request, $id, $ma, $trim)
    {
        $usuarioactual=\Auth::user();

        //Servira para identificar las actividades a las que hace referencia
        $acts = DB::table('actividad')
            ->where('id_trimestre','=', $trim)
            ->orderby('actividad.id_actividad','asc')
            ->get();


        ///////////// ACTIVIDAD: INTEGRADORA ////////////////
        
        $actividades1 = DB::table('detalleevaluacion as det')
            ->select('det.id_detalleevaluacion','evaluacion.nombre as nombreEvaluacion', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion','det.id_evaluacion','=','evaluacion.id_evaluacion','full outer')
            ->join('actividad as actividad','evaluacion.id_actividad','=','actividad.id_actividad', 'full outer')
            ->join('trimestre as trimestre','actividad.id_trimestre','=','trimestre.id_trimestre', 'full outer')
            ->Where('det.id_asignacion','=',$id)
            ->Where('actividad.id_actividad','=',$acts[0]->id_actividad)
            ->Where('trimestre.id_trimestre','=',$trim)
            ->orderby('evaluacion.id_evaluacion', 'asc')
            ->get();

        $notas = Input::get('items1');

        $var0 = 0;

        //if ($notas!="") {   //Compueba que no este vacio el array de inputs

            foreach($notas as $quan) 
            { 
                if ($quan != "") 
                {

                    $consulta = DetalleNota::where('id_detalleevaluacion', $actividades1[$var0]->id_detalleevaluacion)->where('id_matricula',$ma)->first();

                    if (is_null($consulta)) {
                        $detalle_nota = new DetalleNota();
                        $detalle_nota->id_detalleevaluacion = $actividades1[$var0]->id_detalleevaluacion;
                        $detalle_nota->id_matricula = $ma;
                        $detalle_nota->nota = $quan;
                        $detalle_nota->save();
                    }

                    if (!is_null($consulta)) {
                        $consulta->nota = $quan;
                        $consulta->update();
                    }
                    
                }
                
                $var0 = $var0 + 1;
            }
        //}


        ///////////// ACTIVIDAD: CUADERNO ////////////////

        $cuadernos1 = DB::table('detalleevaluacion as det')
            ->select('det.id_detalleevaluacion','evaluacion.nombre as nombreEvaluacion', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion','det.id_evaluacion','=','evaluacion.id_evaluacion','full outer')
            ->join('actividad as actividad','evaluacion.id_actividad','=','actividad.id_actividad', 'full outer')
            ->join('trimestre as trimestre','actividad.id_trimestre','=','trimestre.id_trimestre', 'full outer')
            ->Where('det.id_asignacion','=',$id)
            ->Where('actividad.id_actividad','=',$acts[1]->id_actividad)
            ->Where('trimestre.id_trimestre','=',$trim)
            ->orderby('evaluacion.id_evaluacion', 'asc')
            ->get();

        $notas2 = Input::get('items2');

        $var2 = 0;
        
        foreach($notas2 as $quan) 
        { 
            if ($quan != "") 
            {

                $consulta = DetalleNota::where('id_detalleevaluacion', $cuadernos1[$var2]->id_detalleevaluacion)->where('id_matricula',$ma)->first();

                if (is_null($consulta)) {
                    $detalle_nota = new DetalleNota();
                    $detalle_nota->id_detalleevaluacion = $cuadernos1[$var2]->id_detalleevaluacion;
                    $detalle_nota->id_matricula = $ma;
                    $detalle_nota->nota = $quan;
                    $detalle_nota->save();
                }

                if (!is_null($consulta)) {
                    $consulta->nota = $quan;
                    $consulta->update();
                }
                
            }
            
            $var2 = $var2 + 1;
        }




        ///////////// ACTIVIDAD: PROYECTO ////////////////

        $proyectos1 = DB::table('detalleevaluacion as det')
            ->select('det.id_detalleevaluacion','evaluacion.nombre as nombreEvaluacion', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion','det.id_evaluacion','=','evaluacion.id_evaluacion','full outer')
            ->join('actividad as actividad','evaluacion.id_actividad','=','actividad.id_actividad', 'full outer')
            ->join('trimestre as trimestre','actividad.id_trimestre','=','trimestre.id_trimestre', 'full outer')
            ->Where('det.id_asignacion','=',$id)
            ->Where('actividad.id_actividad','=',$acts[2]->id_actividad)
            ->Where('trimestre.id_trimestre','=',$trim)
            ->orderby('evaluacion.id_evaluacion', 'asc')
            ->get();

        $notas3 = Input::get('items3');

        $var3 = 0;
        
        foreach($notas3 as $quan) 
        { 
            if ($quan != "") 
            {

                $consulta = DetalleNota::where('id_detalleevaluacion', $proyectos1[$var3]->id_detalleevaluacion)->where('id_matricula',$ma)->first();

                if (is_null($consulta)) {
                    $detalle_nota = new DetalleNota();
                    $detalle_nota->id_detalleevaluacion = $proyectos1[$var3]->id_detalleevaluacion;
                    $detalle_nota->id_matricula = $ma;
                    $detalle_nota->nota = $quan;
                    $detalle_nota->save();
                }

                if (!is_null($consulta)) {
                    $consulta->nota = $quan;
                    $consulta->update();
                }
                
            }
            
            $var3 = $var3 + 1;
        }



        ///////////// ACTIVIDAD: PRUEBA ////////////////

        $pruebas1 = DB::table('detalleevaluacion as det')
            ->select('det.id_detalleevaluacion','evaluacion.nombre as nombreEvaluacion', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion','det.id_evaluacion','=','evaluacion.id_evaluacion','full outer')
            ->join('actividad as actividad','evaluacion.id_actividad','=','actividad.id_actividad', 'full outer')
            ->join('trimestre as trimestre','actividad.id_trimestre','=','trimestre.id_trimestre', 'full outer')
            ->Where('det.id_asignacion','=',$id)
            ->Where('actividad.id_actividad','=',$acts[3]->id_actividad)
            ->Where('trimestre.id_trimestre','=',$trim)
            ->orderby('evaluacion.id_evaluacion', 'asc')
            ->get();

        $notas4 = Input::get('items4');

        $var4 = 0;
        
        foreach($notas4 as $quan) 
        { 
            if ($quan != "") 
            {

                $consulta = DetalleNota::where('id_detalleevaluacion', $pruebas1[$var4]->id_detalleevaluacion)->where('id_matricula',$ma)->first();

                if (is_null($consulta)) {
                    $detalle_nota = new DetalleNota();
                    $detalle_nota->id_detalleevaluacion = $pruebas1[$var4]->id_detalleevaluacion;
                    $detalle_nota->id_matricula = $ma;
                    $detalle_nota->nota = $quan;
                    $detalle_nota->save();
                }

                if (!is_null($consulta)) {
                    $consulta->nota = $quan;
                    $consulta->update();
                }
                
            }
            
            $var4 = $var4 + 1;
        }

        return Redirect::back()->with('message', 'Notas registradas correctamente');
    }


    public function edit($g,$s,$t, $id, $ma, $trim)
    {
        $usuarioactual=\Auth::user();

        //if ($g == 1 or $g == 2 or $g == 3) {

            //Representa las evaluaciones correspondientes a las ACTIVIDADES del trimestre I
            $asignacion = Asignacion::find($id);
            $materia = Materia::find($asignacion->id_materia);

            //Servira para identificar las actividades a las que hace referencia
            $acts = DB::table('actividad')
            ->where('id_trimestre','=', $trim)
            ->orderby('actividad.id_actividad','asc')
            ->get();



            /*$dets = DB::table('detalle_asignacion as det')
            ->select('asignacion as asig')
            ->join('asignacion as asignacion','det.id_iddetalleasignacion', '=', 'asignacion.id_asignacion','full outer')
            #$materias = Materias::all();*/

            //Representa las evaluaciones correspondientes a ACT. INTEGRADORA del trimestre recibido
            
            $actividades1 = DB::table('detalleevaluacion as det')
            ->select('det.id_detalleevaluacion','det.id_asignacion','evaluacion.nombre as nombreEvaluacion',
            'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion','det.id_evaluacion','=','evaluacion.id_evaluacion','full outer')
            ->join('actividad as actividad','evaluacion.id_actividad','=','actividad.id_actividad', 'full outer')
            ->join('trimestre as trimestre','actividad.id_trimestre','=','trimestre.id_trimestre', 'full outer')
            ->Where('det.id_asignacion','=',$id)
            ->Where('actividad.id_actividad','=',$acts[0]->id_actividad)
            ->Where('trimestre.id_trimestre','=',$trim)
            ->orderby('evaluacion.id_evaluacion', 'asc')
            ->get();

            $consulta1 = DB::table('detalleevaluacion as det')
            ->select('det.id_detalleevaluacion','det.id_asignacion','evaluacion.nombre as nombreEvaluacion',
            'evaluacion.porcentaje as pEval', 'detalle_nota.nota as nota')
            ->join('evaluacion as evaluacion','det.id_evaluacion','=','evaluacion.id_evaluacion','full outer')
            ->join('actividad as actividad','evaluacion.id_actividad','=','actividad.id_actividad', 'full outer')
            ->join('trimestre as trimestre','actividad.id_trimestre','=','trimestre.id_trimestre', 'full outer')
            ->join('detalle_nota as detalle_nota','det.id_detalleevaluacion','=','detalle_nota.id_detalleevaluacion','full outer')
            ->Where('det.id_asignacion','=',$id)
            ->Where('actividad.id_actividad','=',$acts[0]->id_actividad)
            ->Where('trimestre.id_trimestre','=',$trim)
            ->Where('detalle_nota.id_matricula','=',$ma)
            ->orderby('evaluacion.id_evaluacion', 'asc')
            ->get();

            $array1 = []; 
            $cont =count($actividades1);
            $i=0;
            try{
                while($i < $cont ) {
                        if( $consulta1 != ""){
                            $array1[$i] = $consulta1[$i]->nota;
                        }
                        $i = $i +1;
                    }
                }
            catch(\Exception $e)
            {
                while($i < $cont ) {
                        $array1[$i] = 0;
                        $i = $i +1;
                    }
            }


            //Representa las evaluaciones correspondientes a CUADERNO del trimestre recibido
            
            $cuadernos1 = DB::table('detalleevaluacion as det')
            ->select('det.id_detalleevaluacion','det.id_asignacion','evaluacion.nombre as nombreEvaluacion',
            'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion','det.id_evaluacion','=','evaluacion.id_evaluacion','full outer')
            ->join('actividad as actividad','evaluacion.id_actividad','=','actividad.id_actividad', 'full outer')
            ->join('trimestre as trimestre','actividad.id_trimestre','=','trimestre.id_trimestre', 'full outer')
            ->Where('det.id_asignacion','=',$id)
            ->Where('actividad.id_actividad','=',$acts[1]->id_actividad)
            ->Where('trimestre.id_trimestre','=',$trim)
            ->orderby('evaluacion.id_evaluacion', 'asc')
            ->get();

            $consulta2 = DB::table('detalleevaluacion as det')
            ->select('det.id_detalleevaluacion','det.id_asignacion','evaluacion.nombre as nombreEvaluacion',
            'evaluacion.porcentaje as pEval', 'detalle_nota.nota as nota')
            ->join('evaluacion as evaluacion','det.id_evaluacion','=','evaluacion.id_evaluacion','full outer')
            ->join('actividad as actividad','evaluacion.id_actividad','=','actividad.id_actividad', 'full outer')
            ->join('trimestre as trimestre','actividad.id_trimestre','=','trimestre.id_trimestre', 'full outer')
            ->join('detalle_nota as detalle_nota','det.id_detalleevaluacion','=','detalle_nota.id_detalleevaluacion','full outer')
            ->Where('det.id_asignacion','=',$id)
            ->Where('actividad.id_actividad','=',$acts[1]->id_actividad)
            ->Where('trimestre.id_trimestre','=',$trim)
            ->Where('detalle_nota.id_matricula','=',$ma)
            ->orderby('evaluacion.id_evaluacion', 'asc')
            ->get();

            $array2 = []; 
            $cont =count($cuadernos1);
            $i=0;
            try{
                while($i < $cont ) {
                        if( $consulta2 != ""){
                            $array2[$i] = $consulta2[$i]->nota;
                        }
                        $i = $i +1;
                    }
                }
            catch(\Exception $e)
            {
                while($i < $cont ) {
                        $array2[$i] = 0;
                        $i = $i +1;
                    }
                }


            //Representa las evaluaciones correspondientes a PROYECTO del trimestre recibido
            
            $proyectos1 = DB::table('detalleevaluacion as det')
            ->select('det.id_detalleevaluacion','det.id_asignacion','evaluacion.nombre as nombreEvaluacion',
            'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion','det.id_evaluacion','=','evaluacion.id_evaluacion','full outer')
            ->join('actividad as actividad','evaluacion.id_actividad','=','actividad.id_actividad', 'full outer')
            ->join('trimestre as trimestre','actividad.id_trimestre','=','trimestre.id_trimestre', 'full outer')
            ->Where('det.id_asignacion','=',$id)
            ->Where('actividad.id_actividad','=',$acts[2]->id_actividad)
            ->Where('trimestre.id_trimestre','=',$trim)
            ->orderby('evaluacion.id_evaluacion', 'asc')
            ->get();


            $consulta3 = DB::table('detalleevaluacion as det')
            ->select('det.id_detalleevaluacion','det.id_asignacion','evaluacion.nombre as nombreEvaluacion',
            'evaluacion.porcentaje as pEval', 'detalle_nota.nota as nota')
            ->join('evaluacion as evaluacion','det.id_evaluacion','=','evaluacion.id_evaluacion','full outer')
            ->join('actividad as actividad','evaluacion.id_actividad','=','actividad.id_actividad', 'full outer')
            ->join('trimestre as trimestre','actividad.id_trimestre','=','trimestre.id_trimestre', 'full outer')
            ->join('detalle_nota as detalle_nota','det.id_detalleevaluacion','=','detalle_nota.id_detalleevaluacion','full outer')
            ->Where('det.id_asignacion','=',$id)
            ->Where('actividad.id_actividad','=',$acts[2]->id_actividad)
            ->Where('trimestre.id_trimestre','=',$trim)
            ->Where('detalle_nota.id_matricula','=',$ma)
            ->orderby('evaluacion.id_evaluacion', 'asc')
            ->get();

            $array3 = []; 
            $cont =count($proyectos1);
            $i=0;
            try{
                while($i < $cont ) {
                        if( $consulta3 != ""){
                            $array3[$i] = $consulta3[$i]->nota;
                        }
                        $i = $i +1;
                    }
                }
            catch(\Exception $e)
            {
                while($i < $cont ) {
                        $array3[$i] = 0;
                        $i = $i +1;
                    }
            }

            //Representa las evaluaciones correspondientes a PRUEBA del trimestre recibido
            
            $pruebas1 = DB::table('detalleevaluacion as det')
            ->select('det.id_detalleevaluacion','det.id_asignacion','evaluacion.nombre as nombreEvaluacion',
            'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion','det.id_evaluacion','=','evaluacion.id_evaluacion','full outer')
            ->join('actividad as actividad','evaluacion.id_actividad','=','actividad.id_actividad', 'full outer')
            ->join('trimestre as trimestre','actividad.id_trimestre','=','trimestre.id_trimestre', 'full outer')
            ->Where('det.id_asignacion','=',$id)
            ->Where('actividad.id_actividad','=',$acts[3]->id_actividad)
            ->Where('trimestre.id_trimestre','=',$trim)
            ->orderby('evaluacion.id_evaluacion', 'asc')
            ->get();


            $consulta4 = DB::table('detalleevaluacion as det')
            ->select('det.id_detalleevaluacion','det.id_asignacion','evaluacion.nombre as nombreEvaluacion',
            'evaluacion.porcentaje as pEval', 'detalle_nota.nota as nota')
            ->join('evaluacion as evaluacion','det.id_evaluacion','=','evaluacion.id_evaluacion','full outer')
            ->join('actividad as actividad','evaluacion.id_actividad','=','actividad.id_actividad', 'full outer')
            ->join('trimestre as trimestre','actividad.id_trimestre','=','trimestre.id_trimestre', 'full outer')
            ->join('detalle_nota as detalle_nota','det.id_detalleevaluacion','=','detalle_nota.id_detalleevaluacion','full outer')
            ->Where('det.id_asignacion','=',$id)
            ->Where('actividad.id_actividad','=',$acts[3]->id_actividad)
            ->Where('trimestre.id_trimestre','=',$trim)
            ->Where('detalle_nota.id_matricula','=',$ma)
            ->orderby('evaluacion.id_evaluacion', 'asc')
            ->get();

            $array4 = []; 
            $cont =count($pruebas1);
            $i=0;
            try{
                while($i < $cont ) {
                        if( $consulta4 != ""){
                            $array4[$i] = $consulta4[$i]->nota;
                        }
                        $i = $i +1;
                    }
                }
            catch(\Exception $e)
            {
                while($i < $cont ) {
                        $array4[$i] = 0;
                        $i = $i +1;
                    }
            }

            $num1 = count($actividades1);
            $num2 = count($cuadernos1);
            $num3 = count($proyectos1);
            $num4 = count($pruebas1);



            return view('userDocente.notas.edit', ["id"=>$id, "materia"=>$materia, "actividades1"=>$actividades1,"cuadernos1"=>$cuadernos1, "proyectos1"=>$proyectos1, "pruebas1"=>$pruebas1, "num1"=>$num1,"num2"=>$num2, "num3"=>$num3, "num4"=>$num4, "ma" => $ma, "trim" =>$trim , "acts" =>$acts , "array1"=>$array1, "array2"=>$array2, "array3"=>$array3, "array4"=>$array4, "usuarioactual"=>$usuarioactual]);

        /*}elseif ($g == 4 or $g == 5 or $g == 6 or $g == 7 or $g == 8 or $g == 9) {

             return view("userDocente.notas.show",["usuarioactual"=>$usuarioactual]);

        } */     
    }


    public function update(Request $request, $id)
    {
        $usuarioactual=\Auth::user();
        return view("userDocente.notas.show",["usuarioactual"=>$usuarioactual]);
    }

    
    public function detalle($id, $ma)
    {
        $usuarioactual=\Auth::user();

        $asignacion = Asignacion::find($id);
        $matricula = Matricula::where('id_matricula', $ma)->first();
        $estudiante = Estudiante::where('nie', $matricula->nie)->first();

        //Servira para identificar las actividades a las que hace referencia
        $acts = DB::table('actividad')
        ->orderby('actividad.id_actividad','asc')
        ->get();

        $trim = DB::table('trimestre')
        ->orderby('trimestre.id_trimestre','asc')
        ->get();

        ////////////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////             PRIMER TRIMESTRE             ////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////

        //***************************************************************************************************//
        //*****************************             INTEGRADORA                 *****************************//
        //***************************************************************************************************//

        $e_a1t1 = DB::table('detalleevaluacion as det')
            ->select('det.id_detalleevaluacion','evaluacion.nombre as nombreEvaluacion', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion','det.id_evaluacion','=','evaluacion.id_evaluacion','full outer')
            ->join('actividad as actividad','evaluacion.id_actividad','=','actividad.id_actividad', 'full outer')
            ->join('trimestre as trimestre','actividad.id_trimestre','=','trimestre.id_trimestre', 'full outer')
            ->Where('det.id_asignacion','=',$id)
            ->Where('actividad.id_actividad','=',$acts[0]->id_actividad)
            ->Where('trimestre.id_trimestre','=',$trim[0]->id_trimestre)
            ->orderby('evaluacion.id_evaluacion', 'asc')
            ->get();
        
        
        $ne_a1t1 = DB::table('detalleevaluacion as dn')
        ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval', 'evaluacion.nombre as nombreEvaluacion')
        ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
        ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
        ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
        ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
        ->Where('dn.id_asignacion','=',$id)                      // asignacion
        ->Where('actividad.id_actividad','=', $acts[0]->id_actividad)               // actividad
        ->Where('trimestre.id_trimestre','=', $trim[0]->id_trimestre)               // trimestre
        ->Where('detalle_nota.id_matricula','=',$ma)                                // matricula
        ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
        ->get();

        $array1 = [];
        $num1 = count($e_a1t1);
        $i=0;
        $na1t1 = 0.0;

        
        try{
            while($i < $num1 ) {
                $array1[$i] = $ne_a1t1[$i]->nota;
                $na1t1 = $na1t1 + $ne_a1t1[$i]->nota*$ne_a1t1[$i]->pEval;    
                $i = $i +1;
                }

            $na1t1 = $na1t1/$acts[0]->porcentaje;     
            $na1t1 = round($na1t1, 2);

            }catch(\Exception $e){

            $array1 = [0,0,0,0,0];
            $na1t1 = 0.0;
        }
        

        //***************************************************************************************************//
        //*****************************             CUADERNO                  *******************************//
        //***************************************************************************************************//

        $e_a2t1 = DB::table('detalleevaluacion as det')
            ->select('det.id_detalleevaluacion','evaluacion.nombre as nombreEvaluacion', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion','det.id_evaluacion','=','evaluacion.id_evaluacion','full outer')
            ->join('actividad as actividad','evaluacion.id_actividad','=','actividad.id_actividad', 'full outer')
            ->join('trimestre as trimestre','actividad.id_trimestre','=','trimestre.id_trimestre', 'full outer')
            ->Where('det.id_asignacion','=',$id)
            ->Where('actividad.id_actividad','=',$acts[1]->id_actividad)
            ->Where('trimestre.id_trimestre','=',$trim[0]->id_trimestre)
            ->orderby('evaluacion.id_evaluacion', 'asc')
            ->get();
        
        
        $ne_a2t1 = DB::table('detalleevaluacion as dn')
        ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval', 'evaluacion.nombre as nombreEvaluacion')
        ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
        ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
        ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
        ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
        ->Where('dn.id_asignacion','=',$id)                      // asignacion
        ->Where('actividad.id_actividad','=', $acts[1]->id_actividad)               // actividad
        ->Where('trimestre.id_trimestre','=', $trim[0]->id_trimestre)               // trimestre
        ->Where('detalle_nota.id_matricula','=',$ma)                                // matricula
        ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
        ->get();

        $array2 = [];
        $num2 = count($e_a2t1);
        $i=0;
        $na2t1 = 0.0;

        
        try{
            while($i < $num2 ) {
                $array2[$i] = $ne_a2t1[$i]->nota;
                $na2t1 = $na2t1 + $ne_a2t1[$i]->nota*$ne_a2t1[$i]->pEval;
                $i = $i +1;
                }

            $na2t1 = $na2t1/$acts[1]->porcentaje;     
            $na2t1 = round($na2t1, 2);

            }catch(\Exception $e){
                $array2 = [0,0,0,0,0];
                $na2t1 = 0.0;
        }
        


        //***************************************************************************************************//
        //*****************************             PROYECTO                  *******************************//
        //***************************************************************************************************//

        $e_a3t1 = DB::table('detalleevaluacion as det')
            ->select('det.id_detalleevaluacion','evaluacion.nombre as nombreEvaluacion', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion','det.id_evaluacion','=','evaluacion.id_evaluacion','full outer')
            ->join('actividad as actividad','evaluacion.id_actividad','=','actividad.id_actividad', 'full outer')
            ->join('trimestre as trimestre','actividad.id_trimestre','=','trimestre.id_trimestre', 'full outer')
            ->Where('det.id_asignacion','=',$id)
            ->Where('actividad.id_actividad','=',$acts[2]->id_actividad)
            ->Where('trimestre.id_trimestre','=',$trim[0]->id_trimestre)
            ->orderby('evaluacion.id_evaluacion', 'asc')
            ->get();
        
        
        $ne_a3t1 = DB::table('detalleevaluacion as dn')
        ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval', 'evaluacion.nombre as nombreEvaluacion')
        ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
        ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
        ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
        ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
        ->Where('dn.id_asignacion','=',$id)                      // asignacion
        ->Where('actividad.id_actividad','=', $acts[2]->id_actividad)               // actividad
        ->Where('trimestre.id_trimestre','=', $trim[0]->id_trimestre)               // trimestre
        ->Where('detalle_nota.id_matricula','=',$ma)                                // matricula
        ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
        ->get();

        $array3 = [];
        $num3 = count($e_a3t1);
        $i=0;
        $na3t1 = 0.0;

        
        try{
            while($i < $num3 ) {
                $array3[$i] = $ne_a3t1[$i]->nota;
                $na3t1 = $na3t1 + $ne_a3t1[$i]->nota*$ne_a3t1[$i]->pEval;
                $i = $i +1;
                }

            $na3t1 = $na3t1/$acts[2]->porcentaje;     
            $na3t1 = round($na3t1, 2);

            }catch(\Exception $e){
                $array3 = [0,0,0,0,0];
                $na3t1 = 0.0;
        }



        //***************************************************************************************************//
        //*****************************             PRUEBA                    *******************************//
        //***************************************************************************************************//

        $e_a4t1 = DB::table('detalleevaluacion as det')
            ->select('det.id_detalleevaluacion','evaluacion.nombre as nombreEvaluacion', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion','det.id_evaluacion','=','evaluacion.id_evaluacion','full outer')
            ->join('actividad as actividad','evaluacion.id_actividad','=','actividad.id_actividad', 'full outer')
            ->join('trimestre as trimestre','actividad.id_trimestre','=','trimestre.id_trimestre', 'full outer')
            ->Where('det.id_asignacion','=',$id)
            ->Where('actividad.id_actividad','=',$acts[3]->id_actividad)
            ->Where('trimestre.id_trimestre','=',$trim[0]->id_trimestre)
            ->orderby('evaluacion.id_evaluacion', 'asc')
            ->get();
        
        
        $ne_a4t1 = DB::table('detalleevaluacion as dn')
        ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval', 'evaluacion.nombre as nombreEvaluacion')
        ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
        ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
        ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
        ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
        ->Where('dn.id_asignacion','=',$id)                      // asignacion
        ->Where('actividad.id_actividad','=', $acts[3]->id_actividad)               // actividad
        ->Where('trimestre.id_trimestre','=', $trim[0]->id_trimestre)               // trimestre
        ->Where('detalle_nota.id_matricula','=',$ma)                                // matricula
        ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
        ->get();

        $array4 = [];
        $num4 = count($e_a4t1);
        $i=0;
        $na4t1 = 0.0;


        
        try{
            while($i < $num4 ) {
                $array4[$i] = $ne_a4t1[$i]->nota;
                $na4t1 = $na4t1 + $ne_a4t1[$i]->nota*$ne_a4t1[$i]->pEval;
                $i = $i +1;
                }

            $na4t1 = $na4t1/$acts[3]->porcentaje;     
            $na4t1 = round($na4t1, 2);

            }catch(\Exception $e){
                $array4 = [0,0,0,0,0];
                $na4t1 = 0;
        }

        //=========================================================================================================//
        //============================================= PROMEDIO_TRIMESTRAL ========================================//
        //=======================================================================================================//

        $prom_trim = ($na1t1 + $na2t1 + $na3t1 + $na4t1)/4;
        $prom_trim = round($prom_trim, 2);
        


        return view("userDocente.libreta.detalle",["id"=>$id,"estudiante"=>$estudiante ,
             "e_a1t1" => $e_a1t1, "num1" => $num1, "array1"=>$array1, "na1t1"=>$na1t1,
             "e_a2t1" => $e_a2t1, "num2" => $num2, "array2"=>$array2, "na2t1"=>$na2t1,
             "e_a3t1" => $e_a3t1, "num3" => $num3, "array3"=>$array3, "na3t1"=>$na3t1,
             "e_a4t1" => $e_a4t1, "num4" => $num4, "array4"=>$array4, "na4t1"=>$na4t1,
             "prom_trim"=>$prom_trim,
            "usuarioactual"=>$usuarioactual]);
    }    

    
}
