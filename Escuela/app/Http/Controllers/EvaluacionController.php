<?php

namespace Escuela\Http\Controllers;

use Carbon\Carbon;
use DB;
use Escuela\Asignacion;
use Escuela\DetalleAsignacion;
use Escuela\DetalleEvaluacion;
use Escuela\DetalleGrado;
use Escuela\Evaluacion;
use Escuela\Grado;
use Escuela\MaestroUser;
use Escuela\Materia;
use Escuela\Seccion;
use Escuela\Turno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use Escuela\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Response;
use Illuminate\Support\Collection;  //Contienen los metodos a utilizar
use Illuminate\Database\Connection;
use Escuela\Trimestre;
use Escuela\Actividad;



class EvaluacionController extends Controller
{
    public function indice(Request $request)
    {
        $usuarioactual = \Auth::user();

        //Se busca el docente registrado
        $id_user = $usuarioactual->id_usuario;
        $det_user = MaestroUser::where('id_usuario', $id_user)->first();
        $mdui = $det_user->mdui;

        //catalogo de trimestres
        $trimestres = DB::table('trimestre')->get();
        //catalogo de actividades
        $actividades = DB::table('actividad')->get();
        //catalogo de materias
        $materias = DB::table('materia')
            ->orderby('materia.nombre', 'asc')
            ->get();
        $query3 = Carbon::now();
        $query3 = $query3->format('Y');

        //Asignaciones en el turno matutino

        $asig_mat = DB::table('asignacion')
            ->select('asignacion.id_asignacion', 'asignacion.id_detalleasignacion', 'asignacion.id_materia', 'asignacion.mdui', 'asignacion.anioasignacion', 'maestro.nombre',
                'maestro.apellido', 'materia.nombre as nombremateria', 'materia.estado', 'detalle_asignacion.iddetallegrado', 'detalle_grado.iddetallegrado', 'detalle_grado.idgrado', 'detalle_grado.idseccion',
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
            ->Where('detalle_grado.idturno', '=', 1) //Matutino
            ->orderBy('detalle_grado.idgrado', 'asc')
            ->get();

        //Asignaciones en el turno vespertino

        $asig_ver = DB::table('asignacion')
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
            ->Where('detalle_grado.idturno', '=', 2) //Vespertino
            ->orderBy('detalle_grado.idgrado', 'asc')
            ->get();

        $materias_matutino = DB::table('materia')
            ->select('detalleevaluacion.id_evaluacion', 'detalleevaluacion.id_asignacion', 'evaluacion.id_evaluacion', 'evaluacion.id_actividad', 'evaluacion.nombre as nombreEvaluacion',
                'evaluacion.porcentaje as pEval', 'actividad.id_actividad', 'actividad.id_trimestre', 'actividad.nombre as nombreActividad', 'actividad.porcentaje as pAct',
                'trimestre.id_trimestre', 'trimestre.nombre as nombreTrimestre', 'evaluacion.estado', 'materia.estado')
            ->join('asignacion as asignacion', 'asignacion.id_materia', '=', 'materia.id_materia', 'full outer')
            ->join('detalleevaluacion as detalleevaluacion', 'asignacion.id_asignacion', '=', 'detalleevaluacion.id_asignacion', 'full outer')
            ->join('evaluacion as evaluacion', 'detalleevaluacion.id_evaluacion', '=', 'evaluacion.id_evaluacion', 'full outer')
            ->join('actividad as actividad', 'evaluacion.id_actividad', '=', 'actividad.id_actividad', 'full outer')
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')
            ->get();
        return view('userDocente.evaluaciones.index1', ['usuarioactual' => $usuarioactual, "actividades" => $actividades, "trimestres" => $trimestres, "materias" => $materias, "asig_mat" => $materias_matutino, "asig_ver" => $asig_ver]);
    }

    public function index(Request $request)
    {
        $usuarioactual = \Auth::user();

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

        //Asignaciones en el turno matutino

        $asig_mat = DB::table('asignacion')
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
            ->Where('detalle_grado.idturno', '=', 1) //Matutino
            ->orderBy('detalle_grado.idgrado', 'asc')
            ->get();

        //Asignaciones en el turno vespertino

        $asig_ver = DB::table('asignacion')
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
            ->Where('detalle_grado.idturno', '=', 2) //Vespertino
            ->orderBy('detalle_grado.idgrado', 'asc')
            ->get();

        //catalogo de materias
        $materias = DB::table('materia')
            ->orderby('materia.nombre', 'asc')
            ->get();

        return view('userDocente.evaluaciones.index', ["materias" => $materias, "date" => $date, "mdui" => $mdui, "asig_mat" => $asig_mat, "asig_ver" => $asig_ver, "usuarioactual" => $usuarioactual]);

    }

    public function store(Request $request)
    {
      

        if (Self::numerosEvaluaciones($request->get('asg'), $request->get('id_actividad')) == 5) {
            Session::flash('fallo', "ERROR: NO PUEDE AGREGAR MAS DE 5 EVALUACIONES");
            return back();
        } else {

            $suma=Self::sumatoriaPorcentaje($request->get('asg'), $request->get('id_actividad'));

            $diferencia=100-$suma;
            //se verifica la sumatoria de porcentajes
            if ($request->get('porcentaje')>$diferencia) {
                Session::flash('fallo', "ERROR: LA ACTIVIDAD TIENE EL MAXIMO DE PORCENTAJE ASIGNADO");
                return back();
            }else{

                $nombreM = $request->get('nombreMateria');
                $usuarioactual = \Auth::user();
                $materia = Materia::where('nombre', $nombreM)->first();
                $det_user = MaestroUser::where('id_usuario', $usuarioactual->id_usuario)->first();
               


            // se crea el objeto de evaluacion
            $evaluacion = new Evaluacion;
            $evaluacion->id_actividad = $request->get('id_actividad');
            $evaluacion->nombre = $request->get('nombreEvaluacion');
            $evaluacion->porcentaje = $request->get('porcentaje');
            $evaluacion->estado = 'Activo';
            $evaluacion->save();

            //nuevo detalle evaluacion
            $detalleEvaluacion = new DetalleEvaluacion;
            $detalleEvaluacion->id_evaluacion = $evaluacion->id_evaluacion;
            $detalleEvaluacion->id_asignacion = $request->get('asg');
            $detalleEvaluacion->save();
            }
        }

        Session::flash('exito', "Evaluacion guardada");
        return back();

    }

    public function getLista(Request $request, $a1, $a2, $nG, $nS, $nT, $nM)
    {
        $usuarioactual = \Auth::user();
        if ($request) {
            $id = $a1;

            //Obtenemos el año presente
            $query3 = Carbon::now();
            $query3 = $query3->format('Y');

            //Obtenemos la fecha actual
            $date = Carbon::now();
            $date = $date->format('l jS \\of F Y h:i:s A');
            //$trimestres = DB::table('trimestre')->get();
            $trimestres = Trimestre::lists('nombre','id_trimestre');
            

            $detalleEvaluacion = DB::table('detalleevaluacion')
                ->select('detalleevaluacion.id_evaluacion', 'detalleevaluacion.id_asignacion', 'evaluacion.id_evaluacion', 'evaluacion.id_actividad', 'evaluacion.nombre as nombreEvaluacion',
                    'evaluacion.porcentaje as pEval', 'actividad.id_actividad', 'actividad.id_trimestre', 'actividad.nombre as nombreActividad', 'actividad.porcentaje as pAct',
                    'trimestre.id_trimestre', 'trimestre.nombre as nombreTrimestre', 'evaluacion.estado')
                ->join('evaluacion as evaluacion', 'detalleevaluacion.id_evaluacion', '=', 'evaluacion.id_evaluacion', 'full outer')
                ->join('actividad as actividad', 'evaluacion.id_actividad', '=', 'actividad.id_actividad', 'full outer')
                ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')
                ->Where('detalleevaluacion.id_asignacion', '=', $id)
                ->get();

            //catalogo de materias
            $materias = DB::table('materia')
                ->orderby('materia.nombre', 'asc')
                ->get();

            

            return view('userDocente.evaluaciones.lista', ["materias" => $materias, "asignacion" => $a1, "trimestres" => $trimestres, "nGrado" => $nG, "nSeccion" => $nS, "nTurno" => $nT, "nMateria" => $nM, "usuarioactual" => $usuarioactual, "evaluaciones" => $detalleEvaluacion]);
        }
    }

    public function edit($id)
    {
        $usuarioactual = \Auth::user();
        $evaluacion = Evaluacion::where('id_evaluacion', $id)->first();
        $trimestres = DB::table('trimestre')->get();
        $actividades = DB::table('actividad')->get();

        return view("userDocente.evaluaciones.edit", ["actividades" => $actividades, "trimestres" => $trimestres, "evaluacion" => $evaluacion, "usuarioactual" => $usuarioactual]);
    }

    public function update(Request $request, $id)
    {

        $evaluacion = Evaluacion::where('id_evaluacion', $id)->first();
        $evaluacion->nombre = $request->get('nombreEvaluacion');
        $evaluacion->porcentaje = $request->get('porcentaje');
        $evaluacion->update();

        Session::flash('exito', "Evaluacion Actualizada Correctamente");
        return redirect(Self::rutaAcceso($id));
    }

    public function destroy($id)
    {
        $usuarioactual = \Auth::user();
        $RUTA = Self::rutaAcceso($id);

        $detEval = DetalleEvaluacion::where('id_evaluacion', $id)->first();
        $detEval->delete();

        Evaluacion::destroy($id);

        Session::flash('exito', "Evaluacion ELIMINADA Correctamente");
        return redirect($RUTA);

    }

    //devuelve una asignacion especifica
    protected function BusquedaAsignacion($mdui, $idmateria)
    {
        $asignacion = Asignacion::where('mdui', $mdui)->where('id_materia', $idmateria)->first();
        return $asignacion;
    }

    //totaliza el numero de evaluaciones asignadas a una materia
    protected function numerosEvaluaciones($idAsg, $idAct)
    {
        $numeroDetalles = DB::table('detalleevaluacion')
            ->select('detalleevaluacion.id_asignacion', 'evaluacion.id_actividad')
            ->join('evaluacion as evaluacion', 'detalleevaluacion.id_evaluacion', '=', 'evaluacion.id_evaluacion', 'full outer')
            ->Where('detalleevaluacion.id_asignacion', '=', $idAsg)
            ->Where('evaluacion.id_actividad', '=', $idAct)
            ->count();

        if ($numeroDetalles == 5) {
            return true;
        } else {
            return false;
        }
    }

    //construye una ruta de redireccion estatica, reciba un id de evaluacion, retorna una cadena
    protected function rutaAcceso($id)
    {

        $detEval = DetalleEvaluacion::where('id_evaluacion', $id)->first();
        $asignacion = Asignacion::where('id_asignacion', $detEval->id_asignacion)->first();
        $detAsg = DetalleAsignacion::where('id_detalleasignacion', $asignacion->id_detalleasignacion)->first();
        $detGra = DetalleGrado::where('iddetallegrado', $detAsg->iddetallegrado)->first();
        $M = Materia::where('id_materia', $asignacion->id_materia)->first();
        $G = Grado::where('idgrado', $detGra->idgrado)->first();
        $T = Turno::where('idturno', $detGra->idturno)->first();
        $S = Seccion::where('idseccion', $detGra->idseccion)->first();
        return "userDocente/lista1/estudiante/" . $asignacion->id_asignacion . "/" . $asignacion->id_asignacion . "/" . $G->nombre . "/" . $S->nombre . "/" . $T->nombre . "/" . $M->nombre . "";
    }

    //totaliza porcentajes
    protected function sumatoriaPorcentaje($idAsg,$idAct){
        $sumatoria = DB::table('detalleevaluacion')
        ->select('evaluacion.porcentaje')
        ->join('evaluacion as evaluacion', 'detalleevaluacion.id_evaluacion', '=', 'evaluacion.id_evaluacion', 'full outer')
        ->Where('detalleevaluacion.id_asignacion', '=', $idAsg)
        ->Where('evaluacion.id_actividad', '=', $idAct)
        ->sum('evaluacion.porcentaje');


        return $sumatoria;
    }

    public function getActividades(Request $request,$id){
        if($request->ajax()){
            $actividades = Actividad::actividades($id);
            return response()->json($actividades);
        }
    }

}
