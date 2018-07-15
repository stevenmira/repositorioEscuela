<?php

namespace Escuela\Http\Controllers;

use Illuminate\Http\Request;

use Escuela\MaestroUser;
use Escuela\DetalleAsignacion;
use Escuela\Grado;
use Escuela\Seccion;
use Escuela\Turno;
use Escuela\DetalleGrado;
use Escuela\Matricula;
use Escuela\Estudiante;
use Escuela\Asignacion;

use Escuela\Http\Requests;

use Illuminate\Support\Facades\Session;
use Carbon\Carbon; //Para la zona fecha horaria
use DB;

class LibretaNotasController extends Controller
{
    
    public function getCursos()
    {
        $usuarioactual=\Auth::user();

        $id = $usuarioactual->id_usuario;
        $maestro = MaestroUser::where('id_usuario', $id)->first();
        $mdui = $maestro->mdui;

        $query3 = Carbon::now();
        $query3 = $query3->format('Y');

        $cursos=DB::table('detalle_asignacion as ma')
        ->select('ma.id_detalleasignacion','grado.nombre as nombreGrado','turno.nombre as nombreTurno','seccion.nombre as nombreSeccion')
        ->join('detalle_grado as dg','ma.iddetallegrado','=','dg.iddetallegrado','full outer')
        ->join('grado as grado','dg.idgrado','=','grado.idgrado')
        ->join('turno as turno','dg.idturno','=','turno.idturno')
        ->join('seccion as seccion','dg.idseccion','=','seccion.idseccion')
        ->where('ma.mdui', $mdui)
        ->where('ma.coordinador',1)
        ->where('ma.aniodetalleasignacion','=',$query3)
    	->orderBy('ma.id_detalleasignacion','asc')
   		->get();

        return view('userDocente.libreta.listaCursos',["cursos"=>$cursos, "query3"=>$query3, "usuarioactual"=>$usuarioactual]);
    }


    public function listaAlumnos($id)
    {
    	$usuarioactual=\Auth::user();


    	$det = DetalleAsignacion::where('id_detalleasignacion',$id)->first();
    	$q2 = $det->iddetallegrado;

    	$query3 = Carbon::now();
        $query3 = $query3->format('Y');

    	$matriculas = DB::table('matricula as ma')
    		->join('estudiante as al','ma.nie','=','al.nie')
    		->select('ma.id_matricula','ma.fechamatricula','al.nombre','al.apellido','ma.fotografia','ma.estado', 'al.nie')
            ->groupBy('ma.id_matricula','ma.fechamatricula','al.nombre','al.apellido','ma.fotografia','ma.estado', 'al.nie')
            ->where('ma.iddetallegrado','=',$q2)
            ->whereYear('ma.fechamatricula','=',$query3)
    		->orderBy('ma.id_matricula','desc')
    		->paginate(100);

    	$dg = DetalleGrado::where('iddetallegrado',$q2)->first();
    	$grado = Grado::where('idgrado', $dg->idgrado)->first();
    	$seccion = Seccion::where('idseccion', $dg->idseccion)->first();
    	$turno = Turno::where('idturno', $dg->idturno)->first();

    	

    	if(is_null($grado) or is_null($seccion) or is_null($turno)){
    			$grado = new Grado();
    			$seccion = new Seccion();
    			$turno = new Turno();
                Session::flash('message',"No hay curso disponible");
            }

        if( is_null($matriculas)){
                Session::flash('empty',"No hay alumnos inscritos");
            }

    	return view('userDocente.libreta.listaAlumnos',["matriculas"=>$matriculas, "grado"=>$grado, "seccion"=>$seccion,    "turno"=>$turno, "id" => $id,"usuarioactual"=>$usuarioactual]);
    }

    public function show($id)
    {
    	$usuarioactual=\Auth::user();

        //Parametros a tener en cuenta
            // ****** mdui
            // ****** trimestre
            // ****** actividad
            // ****** materia
            // ****** alumno
            // ****** grado, seccion y turno (detalle_grado)
            // ****** asignacion

    	$matricula = Matricula::where('id_matricula',$id)->first();
    	$estudiante = Estudiante::where('nie', $matricula->nie)->first();
    	$dg = DetalleGrado::where('iddetallegrado', $matricula->iddetallegrado)->first();
    	$grado = Grado::where('idgrado', $dg->idgrado)->first();
    	$seccion = Seccion::where('idseccion', $dg->idseccion)->first();
    	$turno = Turno::where('idturno', $dg->idturno)->first();

        //Se busca el docente que inició session
        $id_user = $usuarioactual->id_usuario;
        $det_user = MaestroUser::where('id_usuario', $id_user)->first();
        $dui = $det_user->mdui;

        //Se consulta el año actual
        $fecha = Carbon::now();
        $anio = $fecha->format('Y');

        //Se consulta el detalle_grado asignado al docente
        $query0 = DetalleAsignacion::where('iddetallegrado', $dg->iddetallegrado)->where('mdui', $dui)
        ->where('aniodetalleasignacion',$anio)->first();

        //Se consulta las actividades del TRIMESTRE I
        $acts = DB::table('actividad')
            ->orderby('actividad.id_actividad','asc')
            ->get();

        //Catalogo de trimestre
        $trim = DB::table('trimestre')
            ->orderby('trimestre.id_trimestre','asc')
            ->get();

        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////      MATERIA LENGUAJE Y LITERATURA        ////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////             PRIMER TRIMESTRE             ////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////

        //====================================== ACTIVIDAD INTEGRADORA ===================================//  

        //Se consulta la asignacion del docente (LENGUAJE Y LITERATURA)
        
            $query1 = Asignacion::where('id_detalleasignacion','=', $query0->id_detalleasignacion)->where('mdui', $dui)
                ->where('id_materia',1)->where('anioasignacion',$anio)->first();  
        try{   
            $integradora = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[0]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[0]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na1t1m1 = 0.0;
                foreach ($integradora as $in) {
                    $na1t1m1 = $na1t1m1 + $in->nota*$in->pEval;
                }
            $na1t1m1 = $na1t1m1/$acts[0]->porcentaje;     
            $na1t1m1 = round($na1t1m1, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na1t1m1 = 0.0;
        }


        //====================================== ACTIVIDAD CUADERNO ===================================// 
        try{
            $cuaderno = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')               
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')            
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')           
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')        
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[1]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[0]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 2 trimestre 1 materia 1
            $na2t1m1 = 0.0;
                foreach ($cuaderno as $in) {
                    $na2t1m1 = $na2t1m1 + $in->nota*$in->pEval;
                }
            $na2t1m1 = $na2t1m1/$acts[1]->porcentaje; 
            $na2t1m1 = round($na2t1m1, 2);      //Se redondea  a 2 decimales
        }catch(\Exception $e){
            $na2t1m1 = 0.0;
        }


        //====================================== ACTIVIDAD PROYECTO ===================================//   
        try{
            $proyecto = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')               
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')            
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')           
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')        
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[2]->id_actividad)               // actividad *
            ->Where('trimestre.id_trimestre','=', $trim[0]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 3 trimestre 1 materia 1
            $na3t1m1 = 0.0;
                foreach ($proyecto as $in) {
                    $na3t1m1 = $na3t1m1 + $in->nota*$in->pEval;
                }
            $na3t1m1 = $na3t1m1/$acts[2]->porcentaje; 
            $na3t1m1 = round($na3t1m1, 2);      //Se redondea  a 2 decimales
        }catch(\Exception $e){
            $na3t1m1 = 0.0;
        }


        //====================================== ACTIVIDAD PRUEBA ===================================//
        try{
            $prueba = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')               
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')            
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')           
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')        
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[3]->id_actividad)               // actividad *
            ->Where('trimestre.id_trimestre','=', $trim[0]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 3 trimestre 1 materia 1
            $na4t1m1 = 0.0;
                foreach ($prueba as $in) {
                    $na4t1m1 = $na4t1m1 + $in->nota*$in->pEval;
                }
            $na4t1m1 = $na4t1m1/$acts[3]->porcentaje; 
            $na4t1m1 = round($na4t1m1, 2);      //Se redondea  a 2 decimales
        }catch(\Exception $e){
            $na4t1m1 = 0.0;
        }

        //=========================================================================================================//
        //============================================= PROMEDIO_T1M1 ============================================//
        //=======================================================================================================//

        $prom_t1m1 = ($na1t1m1 + $na2t1m1 + $na3t1m1 +$na4t1m1)/4;
        $prom_t1m1 = round($prom_t1m1,2);



        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////      MATERIA LENGUAJE Y LITERATURA        ////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////             SEGUNDO TRIMESTRE             ///////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////

        //====================================== ACTIVIDAD INTEGRADORA ===================================//  
  
        try{   
            $integradora = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[4]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[1]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na1t2m1 = 0.0;
                foreach ($integradora as $in) {
                    $na1t2m1 = $na1t2m1 + $in->nota*$in->pEval;
                }
            $na1t2m1 = $na1t2m1/$acts[4]->porcentaje;     
            $na1t2m1 = round($na1t2m1, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na1t2m1 = 0.0;
        }


        //====================================== ACTIVIDAD CUADERNO ===================================// 
        try{   
            $cuaderno = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[5]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[1]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na2t2m1 = 0.0;
                foreach ($cuaderno as $in) {
                    $na2t2m1 = $na2t2m1 + $in->nota*$in->pEval;
                }
            $na2t2m1 = $na2t2m1/$acts[5]->porcentaje;     
            $na2t2m1 = round($na2t2m1, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na2t2m1 = 0.0;
        }

        //====================================== ACTIVIDAD PROYECTO ===================================// 
        try{   
            $proyecto = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[6]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[1]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na3t2m1 = 0.0;
                foreach ($proyecto as $in) {
                    $na3t2m1 = $na3t2m1 + $in->nota*$in->pEval;
                }
            $na3t2m1 = $na3t2m1/$acts[6]->porcentaje;     
            $na3t2m1 = round($na3t2m1, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na3t2m1 = 0.0;
        }

        //====================================== ACTIVIDAD PRUEBA ===================================// 
        try{   
            $prueba = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[7]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[1]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na4t2m1 = 0.0;
                foreach ($prueba as $in) {
                    $na4t2m1 = $na4t2m1 + $in->nota*$in->pEval;
                }
            $na4t2m1 = $na4t2m1/$acts[7]->porcentaje;     
            $na4t2m1 = round($na4t2m1, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na4t2m1 = 0.0;
        }

        //=========================================================================================================//
        //============================================= PROMEDIO_T2M1 ============================================//
        //=======================================================================================================//

        $prom_t2m1 = ($na1t2m1 + $na2t2m1 + $na3t2m1 +$na4t2m1)/4;
        $prom_t2m1 = round($prom_t2m1,2);



        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////      MATERIA LENGUAJE Y LITERATURA        ////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////             TERCER TRIMESTRE              ///////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////

        //====================================== ACTIVIDAD INTEGRADORA ===================================//  
  
        try{   
            $integradora = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[8]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[2]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na1t3m1 = 0.0;
                foreach ($integradora as $in) {
                    $na1t3m1 = $na1t3m1 + $in->nota*$in->pEval;
                }
            $na1t3m1 = $na1t3m1/$acts[8]->porcentaje;     
            $na1t3m1 = round($na1t3m1, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na1t3m1 = 0.0;
        }

        //====================================== ACTIVIDAD CUADERNO ===================================//  
  
        try{   
            $cuaderno = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[9]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[2]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na2t3m1 = 0.0;
                foreach ($cuaderno as $in) {
                    $na2t3m1 = $na2t3m1 + $in->nota*$in->pEval;
                }
            $na2t3m1 = $na2t3m1/$acts[9]->porcentaje;     
            $na2t3m1 = round($na2t3m1, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na2t3m1 = 0.0;
        }


        //====================================== ACTIVIDAD PROYECTO ===================================//  
  
        try{   
            $proyecto = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[10]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[2]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na3t3m1 = 0.0;
                foreach ($proyecto as $in) {
                    $na3t3m1 = $na3t3m1 + $in->nota*$in->pEval;
                }
            $na3t3m1 = $na3t3m1/$acts[10]->porcentaje;     
            $na3t3m1 = round($na3t3m1, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na3t3m1 = 0.0;
        }

        //====================================== ACTIVIDAD PRUEBA ===================================//  
  
        try{   
            $prueba = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[11]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[2]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na4t3m1 = 0.0;
                foreach ($prueba as $in) {
                    $na4t3m1 = $na4t3m1 + $in->nota*$in->pEval;
                }
            $na4t3m1 = $na4t3m1/$acts[11]->porcentaje;     
            $na4t3m1 = round($na4t3m1, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na4t3m1 = 0.0;
        }

        //=========================================================================================================//
        //============================================= PROMEDIO_T3M1 ============================================//
        //=======================================================================================================//

        $prom_t3m1 = ($na1t3m1 + $na2t3m1 + $na3t3m1 +$na4t3m1)/4;
        $prom_t3m1 = round($prom_t3m1, 2);

        //=========================================================================================================//
        //============================================= PROMEDIO_FINAL_M1 ========================================//
        //=======================================================================================================//

        $prom_final_m1 = ($prom_t1m1 + $prom_t2m1 + $prom_t3m1)/3;
        $prom_final_m1 = round($prom_final_m1, 2);

        if ($prom_final_m1 >= 5.0) {
            $res_m1 = "APROBADO";
            }else{
                $res_m1 = "REPROBADO";
            }


    	return view("userDocente.libreta.show", [ "estudiante"=>$estudiante, "grado"=>$grado, "seccion"=>$seccion, "turno"=>$turno,      "na1t1m1"=>$na1t1m1, "na2t1m1"=>$na2t1m1, "na3t1m1"=>$na3t1m1, "na4t1m1"=>$na4t1m1, "prom_t1m1"=>$prom_t1m1,
             "na1t2m1"=>$na1t2m1, "na2t2m1"=>$na2t2m1, "na3t2m1"=>$na3t2m1, "na4t2m1"=>$na4t2m1, "prom_t2m1"=>$prom_t2m1,
             "na1t3m1"=>$na1t3m1, "na2t3m1"=>$na2t3m1, "na3t3m1"=>$na3t3m1, "na4t3m1"=>$na4t3m1, "prom_t3m1"=>$prom_t3m1,
             "prom_final_m1"=> $prom_final_m1, "res_m1"=>$res_m1,


            "usuarioactual"=>$usuarioactual]);
    }

////Jairo
    public function showPDF($id)
    {
        $usuarioactual=\Auth::user();

        //Parametros a tener en cuenta
            // ****** mdui
            // ****** trimestre
            // ****** actividad
            // ****** materia
            // ****** alumno
            // ****** grado, seccion y turno (detalle_grado)
            // ****** asignacion

        $matricula = Matricula::where('id_matricula',$id)->first();
        $estudiante = Estudiante::where('nie', $matricula->nie)->first();
        $dg = DetalleGrado::where('iddetallegrado', $matricula->iddetallegrado)->first();
        $grado = Grado::where('idgrado', $dg->idgrado)->first();
        $seccion = Seccion::where('idseccion', $dg->idseccion)->first();
        $turno = Turno::where('idturno', $dg->idturno)->first();

        //Se busca el docente que inició session
        $id_user = $usuarioactual->id_usuario;
        $det_user = MaestroUser::where('id_usuario', $id_user)->first();
        $dui = $det_user->mdui;

        //Se consulta el año actual
        $fecha = Carbon::now();
        $anio = $fecha->format('Y');

        //Se consulta el detalle_grado asignado al docente
        $query0 = DetalleAsignacion::where('iddetallegrado', $dg->iddetallegrado)->where('mdui', $dui)
        ->where('aniodetalleasignacion',$anio)->first();

        //Se consulta las actividades del TRIMESTRE I
        $acts = DB::table('actividad')
            ->orderby('actividad.id_actividad','asc')
            ->get();

        //Catalogo de trimestre
        $trim = DB::table('trimestre')
            ->orderby('trimestre.id_trimestre','asc')
            ->get();

        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////      MATERIA LENGUAJE Y LITERATURA        ////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////             PRIMER TRIMESTRE             ////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////

        //====================================== ACTIVIDAD INTEGRADORA ===================================//  

        //Se consulta la asignacion del docente (LENGUAJE Y LITERATURA)
        
            $query1 = Asignacion::where('id_detalleasignacion','=', $query0->id_detalleasignacion)->where('mdui', $dui)
                ->where('id_materia',1)->where('anioasignacion',$anio)->first();  
        try{   
            $integradora = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[0]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[0]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na1t1m1 = 0.0;
                foreach ($integradora as $in) {
                    $na1t1m1 = $na1t1m1 + $in->nota*$in->pEval;
                }
            $na1t1m1 = $na1t1m1/$acts[0]->porcentaje;     
            $na1t1m1 = round($na1t1m1, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na1t1m1 = 0.0;
        }


        //====================================== ACTIVIDAD CUADERNO ===================================// 
        try{
            $cuaderno = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')               
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')            
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')           
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')        
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[1]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[0]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 2 trimestre 1 materia 1
            $na2t1m1 = 0.0;
                foreach ($cuaderno as $in) {
                    $na2t1m1 = $na2t1m1 + $in->nota*$in->pEval;
                }
            $na2t1m1 = $na2t1m1/$acts[1]->porcentaje; 
            $na2t1m1 = round($na2t1m1, 2);      //Se redondea  a 2 decimales
        }catch(\Exception $e){
            $na2t1m1 = 0.0;
        }


        //====================================== ACTIVIDAD PROYECTO ===================================//   
        try{
            $proyecto = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')               
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')            
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')           
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')        
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[2]->id_actividad)               // actividad *
            ->Where('trimestre.id_trimestre','=', $trim[0]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 3 trimestre 1 materia 1
            $na3t1m1 = 0.0;
                foreach ($proyecto as $in) {
                    $na3t1m1 = $na3t1m1 + $in->nota*$in->pEval;
                }
            $na3t1m1 = $na3t1m1/$acts[2]->porcentaje; 
            $na3t1m1 = round($na3t1m1, 2);      //Se redondea  a 2 decimales
        }catch(\Exception $e){
            $na3t1m1 = 0.0;
        }


        //====================================== ACTIVIDAD PRUEBA ===================================//
        try{
            $prueba = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')               
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')            
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')           
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')        
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[3]->id_actividad)               // actividad *
            ->Where('trimestre.id_trimestre','=', $trim[0]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 3 trimestre 1 materia 1
            $na4t1m1 = 0.0;
                foreach ($prueba as $in) {
                    $na4t1m1 = $na4t1m1 + $in->nota*$in->pEval;
                }
            $na4t1m1 = $na4t1m1/$acts[3]->porcentaje; 
            $na4t1m1 = round($na4t1m1, 2);      //Se redondea  a 2 decimales
        }catch(\Exception $e){
            $na4t1m1 = 0.0;
        }

        //=========================================================================================================//
        //============================================= PROMEDIO_T1M1 ============================================//
        //=======================================================================================================//

        $prom_t1m1 = ($na1t1m1 + $na2t1m1 + $na3t1m1 +$na4t1m1)/4;
        $prom_t1m1 = round($prom_t1m1,2);



        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////      MATERIA LENGUAJE Y LITERATURA        ////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////             SEGUNDO TRIMESTRE             ///////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////

        //====================================== ACTIVIDAD INTEGRADORA ===================================//  
  
        try{   
            $integradora = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[4]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[1]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na1t2m1 = 0.0;
                foreach ($integradora as $in) {
                    $na1t2m1 = $na1t2m1 + $in->nota*$in->pEval;
                }
            $na1t2m1 = $na1t2m1/$acts[4]->porcentaje;     
            $na1t2m1 = round($na1t2m1, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na1t2m1 = 0.0;
        }


        //====================================== ACTIVIDAD CUADERNO ===================================// 
        try{   
            $cuaderno = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[5]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[1]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na2t2m1 = 0.0;
                foreach ($cuaderno as $in) {
                    $na2t2m1 = $na2t2m1 + $in->nota*$in->pEval;
                }
            $na2t2m1 = $na2t2m1/$acts[5]->porcentaje;     
            $na2t2m1 = round($na2t2m1, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na2t2m1 = 0.0;
        }

        //====================================== ACTIVIDAD PROYECTO ===================================// 
        try{   
            $proyecto = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[6]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[1]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na3t2m1 = 0.0;
                foreach ($proyecto as $in) {
                    $na3t2m1 = $na3t2m1 + $in->nota*$in->pEval;
                }
            $na3t2m1 = $na3t2m1/$acts[6]->porcentaje;     
            $na3t2m1 = round($na3t2m1, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na3t2m1 = 0.0;
        }

        //====================================== ACTIVIDAD PRUEBA ===================================// 
        try{   
            $prueba = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[7]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[1]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na4t2m1 = 0.0;
                foreach ($prueba as $in) {
                    $na4t2m1 = $na4t2m1 + $in->nota*$in->pEval;
                }
            $na4t2m1 = $na4t2m1/$acts[7]->porcentaje;     
            $na4t2m1 = round($na4t2m1, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na4t2m1 = 0.0;
        }

        //=========================================================================================================//
        //============================================= PROMEDIO_T2M1 ============================================//
        //=======================================================================================================//

        $prom_t2m1 = ($na1t2m1 + $na2t2m1 + $na3t2m1 +$na4t2m1)/4;
        $prom_t2m1 = round($prom_t2m1,2);



        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////      MATERIA LENGUAJE Y LITERATURA        ////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////             TERCER TRIMESTRE              ///////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////

        //====================================== ACTIVIDAD INTEGRADORA ===================================//  
  
        try{   
            $integradora = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[8]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[2]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na1t3m1 = 0.0;
                foreach ($integradora as $in) {
                    $na1t3m1 = $na1t3m1 + $in->nota*$in->pEval;
                }
            $na1t3m1 = $na1t3m1/$acts[8]->porcentaje;     
            $na1t3m1 = round($na1t3m1, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na1t3m1 = 0.0;
        }

        //====================================== ACTIVIDAD CUADERNO ===================================//  
  
        try{   
            $cuaderno = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[9]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[2]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na2t3m1 = 0.0;
                foreach ($cuaderno as $in) {
                    $na2t3m1 = $na2t3m1 + $in->nota*$in->pEval;
                }
            $na2t3m1 = $na2t3m1/$acts[9]->porcentaje;     
            $na2t3m1 = round($na2t3m1, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na2t3m1 = 0.0;
        }


        //====================================== ACTIVIDAD PROYECTO ===================================//  
  
        try{   
            $proyecto = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[10]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[2]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na3t3m1 = 0.0;
                foreach ($proyecto as $in) {
                    $na3t3m1 = $na3t3m1 + $in->nota*$in->pEval;
                }
            $na3t3m1 = $na3t3m1/$acts[10]->porcentaje;     
            $na3t3m1 = round($na3t3m1, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na3t3m1 = 0.0;
        }

        //====================================== ACTIVIDAD PRUEBA ===================================//  
  
        try{   
            $prueba = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[11]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[2]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na4t3m1 = 0.0;
                foreach ($prueba as $in) {
                    $na4t3m1 = $na4t3m1 + $in->nota*$in->pEval;
                }
            $na4t3m1 = $na4t3m1/$acts[11]->porcentaje;     
            $na4t3m1 = round($na4t3m1, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na4t3m1 = 0.0;
        }

        //=========================================================================================================//
        //============================================= PROMEDIO_T3M1 ============================================//
        //=======================================================================================================//

        $prom_t3m1 = ($na1t3m1 + $na2t3m1 + $na3t3m1 +$na4t3m1)/4;
        $prom_t3m1 = round($prom_t3m1, 2);

        //=========================================================================================================//
        //============================================= PROMEDIO_FINAL_M1 ========================================//
        //=======================================================================================================//

        $prom_final_m1 = ($prom_t1m1 + $prom_t2m1 + $prom_t3m1)/3;
        $prom_final_m1 = round($prom_final_m1, 2);

        if ($prom_final_m1 >= 5.0) {
            $res_m1 = "APROBADO";
            }else{
                $res_m1 = "REPROBADO";
            }


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////      MATERIA MATEMATICA      ////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////             PRIMER TRIMESTRE             ////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////

        //====================================== ACTIVIDAD INTEGRADORA ===================================//  

        //Se consulta la asignacion del docente (MATEMATICA)
        
            $query1 = Asignacion::where('id_detalleasignacion','=', $query0->id_detalleasignacion)->where('mdui', $dui)
                ->where('id_materia',2)->where('anioasignacion',$anio)->first();  
        try{   
            $integradora = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[0]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[0]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na1t1m2 = 0.0;
                foreach ($integradora as $in) {
                    $na1t1m2 = $na1t1m2 + $in->nota*$in->pEval;
                }
            $na1t1m2 = $na1t1m2/$acts[0]->porcentaje;     
            $na1t1m2 = round($na1t1m2, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na1t1m2 = 0.0;
        }


        //====================================== ACTIVIDAD CUADERNO ===================================// 
        try{
            $cuaderno = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')               
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')            
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')           
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')        
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[1]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[0]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 2 trimestre 1 materia 1
            $na2t1m2 = 0.0;
                foreach ($cuaderno as $in) {
                    $na2t1m2 = $na2t1m2 + $in->nota*$in->pEval;
                }
            $na2t1m2 = $na2t1m2/$acts[1]->porcentaje; 
            $na2t1m2 = round($na2t1m2, 2);      //Se redondea  a 2 decimales
        }catch(\Exception $e){
            $na2t1m2 = 0.0;
        }


        //====================================== ACTIVIDAD PROYECTO ===================================//   
        try{
            $proyecto = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')               
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')            
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')           
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')        
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[2]->id_actividad)               // actividad *
            ->Where('trimestre.id_trimestre','=', $trim[0]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 3 trimestre 1 materia 1
            $na3t1m2 = 0.0;
                foreach ($proyecto as $in) {
                    $na3t1m2 = $na3t1m2 + $in->nota*$in->pEval;
                }
            $na3t1m2 = $na3t1m2/$acts[2]->porcentaje; 
            $na3t1m2 = round($na3t1m2, 2);      //Se redondea  a 2 decimales
        }catch(\Exception $e){
            $na3t1m2 = 0.0;
        }


        //====================================== ACTIVIDAD PRUEBA ===================================//
        try{
            $prueba = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')               
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')            
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')           
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')        
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[3]->id_actividad)               // actividad *
            ->Where('trimestre.id_trimestre','=', $trim[0]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 3 trimestre 1 materia 1
            $na4t1m2 = 0.0;
                foreach ($prueba as $in) {
                    $na4t1m2 = $na4t1m2 + $in->nota*$in->pEval;
                }
            $na4t1m2 = $na4t1m2/$acts[3]->porcentaje; 
            $na4t1m2 = round($na4t1m2, 2);      //Se redondea  a 2 decimales
        }catch(\Exception $e){
            $na4t1m2 = 0.0;
        }

        //=========================================================================================================//
        //============================================= PROMEDIO_T1M1 ============================================//
        //=======================================================================================================//

        $prom_t1m2 = ($na1t1m2*0.3 + $na2t1m2*0.2 + $na3t1m2*0.1 +$na4t1m2*0.4);
        $prom_t1m2 = round($prom_t1m2,2);



        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////      MATERIA MATEMATICA       ///////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////             SEGUNDO TRIMESTRE             ///////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////

        //====================================== ACTIVIDAD INTEGRADORA ===================================//  
  
        try{   
            $integradora = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[4]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[1]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na1t2m2 = 0.0;
                foreach ($integradora as $in) {
                    $na1t2m2 = $na1t2m2 + $in->nota*$in->pEval;
                }
            $na1t2m2 = $na1t2m2/$acts[4]->porcentaje;     
            $na1t2m2 = round($na1t2m2, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na1t2m2 = 0.0;
        }


        //====================================== ACTIVIDAD CUADERNO ===================================// 
        try{   
            $cuaderno = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[5]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[1]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na2t2m2 = 0.0;
                foreach ($cuaderno as $in) {
                    $na2t2m2 = $na2t2m2 + $in->nota*$in->pEval;
                }
            $na2t2m2 = $na2t2m2/$acts[5]->porcentaje;     
            $na2t2m2 = round($na2t2m2, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na2t2m2 = 0.0;
        }

        //====================================== ACTIVIDAD PROYECTO ===================================// 
        try{   
            $proyecto = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[6]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[1]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na3t2m2 = 0.0;
                foreach ($proyecto as $in) {
                    $na3t2m2 = $na3t2m2 + $in->nota*$in->pEval;
                }
            $na3t2m2 = $na3t2m2/$acts[6]->porcentaje;     
            $na3t2m2 = round($na3t2m2, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na3t2m2 = 0.0;
        }

        //====================================== ACTIVIDAD PRUEBA ===================================// 
        try{   
            $prueba = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[7]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[1]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na4t2m2 = 0.0;
                foreach ($prueba as $in) {
                    $na4t2m2 = $na4t2m2 + $in->nota*$in->pEval;
                }
            $na4t2m2 = $na4t2m2/$acts[7]->porcentaje;     
            $na4t2m2 = round($na4t2m2, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na4t2m2 = 0.0;
        }

        //=========================================================================================================//
        //============================================= PROMEDIO_T2M1 ============================================//
        //=======================================================================================================//

        $prom_t2m2 = ($na1t2m2*0.3 + $na2t2m2*0.2 + $na3t2m2*0.1 +$na4t2m2*0.4);
        $prom_t2m2 = round($prom_t2m2,2);



        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////      MATERIA MATEMATICA        ////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////             TERCER TRIMESTRE              ///////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////

        //====================================== ACTIVIDAD INTEGRADORA ===================================//  
  
        try{   
            $integradora = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[8]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[2]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na1t3m2 = 0.0;
                foreach ($integradora as $in) {
                    $na1t3m2 = $na1t3m2 + $in->nota*$in->pEval;
                }
            $na1t3m2 = $na1t3m2/$acts[8]->porcentaje;     
            $na1t3m2 = round($na1t3m2, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na1t3m2 = 0.0;
        }

        //====================================== ACTIVIDAD CUADERNO ===================================//  
  
        try{   
            $cuaderno = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[9]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[2]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na2t3m2 = 0.0;
                foreach ($cuaderno as $in) {
                    $na2t3m2 = $na2t3m2 + $in->nota*$in->pEval;
                }
            $na2t3m2 = $na2t3m2/$acts[9]->porcentaje;     
            $na2t3m2 = round($na2t3m2, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na2t3m2 = 0.0;
        }


        //====================================== ACTIVIDAD PROYECTO ===================================//  
  
        try{   
            $proyecto = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[10]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[2]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na3t3m2 = 0.0;
                foreach ($proyecto as $in) {
                    $na3t3m2 = $na3t3m2 + $in->nota*$in->pEval;
                }
            $na3t3m2 = $na3t3m2/$acts[10]->porcentaje;     
            $na3t3m2 = round($na3t3m2, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na3t3m2 = 0.0;
        }

        //====================================== ACTIVIDAD PRUEBA ===================================//  
  
        try{   
            $prueba = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[11]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[2]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na4t3m2 = 0.0;
                foreach ($prueba as $in) {
                    $na4t3m2 = $na4t3m2 + $in->nota*$in->pEval;
                }
            $na4t3m2 = $na4t3m2/$acts[11]->porcentaje;     
            $na4t3m2 = round($na4t3m2, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na4t3m2 = 0.0;
        }

        //=========================================================================================================//
        //============================================= PROMEDIO_T3M2 ============================================//
        //=======================================================================================================//

        $prom_t3m2 = ($na1t3m2*0.3 + $na2t3m2*0.2 + $na3t3m2*0.1 +$na4t3m2*0.4);
        $prom_t3m2 = round($prom_t3m2, 2);

        //=========================================================================================================//
        //============================================= PROMEDIO_FINAL_M2 ========================================//
        //=======================================================================================================//

        $prom_final_m2 = ($prom_t1m2 + $prom_t2m2 + $prom_t3m2)/3;
        $prom_final_m2 = round($prom_final_m2, 2);

        if ($prom_final_m2 >= 5.0) {
            $res_m2 = "APROBADO";
            }else{
                $res_m2 = "REPROBADO";
            }


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////      MATERIA CIENCIAS      ////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////             PRIMER TRIMESTRE             ////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////

        //====================================== ACTIVIDAD INTEGRADORA ===================================//  

        //Se consulta la asignacion del docente (MATEMATICA)
        
            $query1 = Asignacion::where('id_detalleasignacion','=', $query0->id_detalleasignacion)->where('mdui', $dui)
                ->where('id_materia',3)->where('anioasignacion',$anio)->first();  
        try{   
            $integradora = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[0]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[0]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na1t1m3 = 0.0;
                foreach ($integradora as $in) {
                    $na1t1m3 = $na1t1m3 + $in->nota*$in->pEval;
                }
            $na1t1m3 = $na1t1m3/$acts[0]->porcentaje;     
            $na1t1m3 = round($na1t1m3, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na1t1m3 = 0.0;
        }


        //====================================== ACTIVIDAD CUADERNO ===================================// 
        try{
            $cuaderno = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')               
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')            
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')           
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')        
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[1]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[0]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 2 trimestre 1 materia 1
            $na2t1m3 = 0.0;
                foreach ($cuaderno as $in) {
                    $na2t1m3 = $na2t1m3 + $in->nota*$in->pEval;
                }
            $na2t1m3 = $na2t1m3/$acts[1]->porcentaje; 
            $na2t1m3 = round($na2t1m3, 2);      //Se redondea  a 2 decimales
        }catch(\Exception $e){
            $na2t1m3 = 0.0;
        }


        //====================================== ACTIVIDAD PROYECTO ===================================//   
        try{
            $proyecto = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')               
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')            
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')           
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')        
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[2]->id_actividad)               // actividad *
            ->Where('trimestre.id_trimestre','=', $trim[0]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 3 trimestre 1 materia 1
            $na3t1m3 = 0.0;
                foreach ($proyecto as $in) {
                    $na3t1m3 = $na3t1m3 + $in->nota*$in->pEval;
                }
            $na3t1m3 = $na3t1m3/$acts[2]->porcentaje; 
            $na3t1m3 = round($na3t1m3, 2);      //Se redondea  a 2 decimales
        }catch(\Exception $e){
            $na3t1m3 = 0.0;
        }


        //====================================== ACTIVIDAD PRUEBA ===================================//
        try{
            $prueba = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')               
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')            
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')           
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')        
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[3]->id_actividad)               // actividad *
            ->Where('trimestre.id_trimestre','=', $trim[0]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 3 trimestre 1 materia 1
            $na4t1m3 = 0.0;
                foreach ($prueba as $in) {
                    $na4t1m3 = $na4t1m3 + $in->nota*$in->pEval;
                }
            $na4t1m3 = $na4t1m3/$acts[3]->porcentaje; 
            $na4t1m3 = round($na4t1m3, 2);      //Se redondea  a 2 decimales
        }catch(\Exception $e){
            $na4t1m3 = 0.0;
        }

        //=========================================================================================================//
        //============================================= PROMEDIO_T1M1 ============================================//
        //=======================================================================================================//

        $prom_t1m3 = ($na1t1m3*0.3 + $na2t1m3*0.2 + $na3t1m3*0.1 +$na4t1m3*0.4);
        $prom_t1m3 = round($prom_t1m3,2);



        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////      MATERIA CIENCIAS      ///////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////             SEGUNDO TRIMESTRE             ///////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////

        //====================================== ACTIVIDAD INTEGRADORA ===================================//  
  
        try{   
            $integradora = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[4]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[1]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na1t2m3 = 0.0;
                foreach ($integradora as $in) {
                    $na1t2m3 = $na1t2m3 + $in->nota*$in->pEval;
                }
            $na1t2m3 = $na1t2m3/$acts[4]->porcentaje;     
            $na1t2m3 = round($na1t2m3, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na1t2m3 = 0.0;
        }


        //====================================== ACTIVIDAD CUADERNO ===================================// 
        try{   
            $cuaderno = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[5]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[1]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na2t2m3 = 0.0;
                foreach ($cuaderno as $in) {
                    $na2t2m3 = $na2t2m3 + $in->nota*$in->pEval;
                }
            $na2t2m3 = $na2t2m3/$acts[5]->porcentaje;     
            $na2t2m3 = round($na2t2m3, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na2t2m3 = 0.0;
        }

        //====================================== ACTIVIDAD PROYECTO ===================================// 
        try{   
            $proyecto = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[6]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[1]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na3t2m3 = 0.0;
                foreach ($proyecto as $in) {
                    $na3t2m3 = $na3t2m3 + $in->nota*$in->pEval;
                }
            $na3t2m3 = $na3t2m3/$acts[6]->porcentaje;     
            $na3t2m3 = round($na3t2m3, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na3t2m3 = 0.0;
        }

        //====================================== ACTIVIDAD PRUEBA ===================================// 
        try{   
            $prueba = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[7]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[1]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na4t2m3 = 0.0;
                foreach ($prueba as $in) {
                    $na4t2m3 = $na4t2m3 + $in->nota*$in->pEval;
                }
            $na4t2m3 = $na4t2m3/$acts[7]->porcentaje;     
            $na4t2m3 = round($na4t2m3, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na4t2m3 = 0.0;
        }

        //=========================================================================================================//
        //============================================= PROMEDIO_T2M1 ============================================//
        //=======================================================================================================//

        $prom_t2m3 = ($na1t2m3*0.3 + $na2t2m3*0.2 + $na3t2m3*0.1 +$na4t2m3*0.4);
        $prom_t2m3 = round($prom_t2m3,2);



        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////      MATERIA MATEMATICA        ////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////             TERCER TRIMESTRE              ///////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////

        //====================================== ACTIVIDAD INTEGRADORA ===================================//  
  
        try{   
            $integradora = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[8]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[2]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na1t3m3 = 0.0;
                foreach ($integradora as $in) {
                    $na1t3m3 = $na1t3m3 + $in->nota*$in->pEval;
                }
            $na1t3m3 = $na1t3m3/$acts[8]->porcentaje;     
            $na1t3m3 = round($na1t3m3, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na1t3m3 = 0.0;
        }

        //====================================== ACTIVIDAD CUADERNO ===================================//  
  
        try{   
            $cuaderno = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[9]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[2]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na2t3m3 = 0.0;
                foreach ($cuaderno as $in) {
                    $na2t3m3 = $na2t3m3 + $in->nota*$in->pEval;
                }
            $na2t3m3 = $na2t3m3/$acts[9]->porcentaje;     
            $na2t3m3 = round($na2t3m3, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na2t3m3 = 0.0;
        }


        //====================================== ACTIVIDAD PROYECTO ===================================//  
  
        try{   
            $proyecto = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[10]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[2]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na3t3m3 = 0.0;
                foreach ($proyecto as $in) {
                    $na3t3m3 = $na3t3m3 + $in->nota*$in->pEval;
                }
            $na3t3m3 = $na3t3m3/$acts[10]->porcentaje;     
            $na3t3m3 = round($na3t3m3, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na3t3m3 = 0.0;
        }

        //====================================== ACTIVIDAD PRUEBA ===================================//  
  
        try{   
            $prueba = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[11]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[2]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na4t3m3 = 0.0;
                foreach ($prueba as $in) {
                    $na4t3m3 = $na4t3m3 + $in->nota*$in->pEval;
                }
            $na4t3m3 = $na4t3m3/$acts[11]->porcentaje;     
            $na4t3m3 = round($na4t3m3, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na4t3m3 = 0.0;
        }

        //=========================================================================================================//
        //============================================= PROMEDIO_T3M2 ============================================//
        //=======================================================================================================//

        $prom_t3m3 = ($na1t3m3*0.3 + $na2t3m3*0.2 + $na3t3m3*0.1 +$na4t3m3*0.4);
        $prom_t3m3 = round($prom_t3m3, 2);

        //=========================================================================================================//
        //============================================= PROMEDIO_FINAL_M2 ========================================//
        //=======================================================================================================//

        $prom_final_m3 = ($prom_t1m3 + $prom_t2m3 + $prom_t3m3)/3;
        $prom_final_m3 = round($prom_final_m3, 2);

        if ($prom_final_m3 >= 5.0) {
            $res_m3 = "APROBADO";
            }else{
                $res_m3 = "REPROBADO";
            }


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////      MATERIA SOCIALES     ////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////             PRIMER TRIMESTRE             ////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////

        //====================================== ACTIVIDAD INTEGRADORA ===================================//  

        //Se consulta la asignacion del docente (MATEMATICA)
        
            $query1 = Asignacion::where('id_detalleasignacion','=', $query0->id_detalleasignacion)->where('mdui', $dui)
                ->where('id_materia',4)->where('anioasignacion',$anio)->first();  
        try{   
            $integradora = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[0]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[0]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na1t1m4 = 0.0;
                foreach ($integradora as $in) {
                    $na1t1m4 = $na1t1m4 + $in->nota*$in->pEval;
                }
            $na1t1m4 = $na1t1m4/$acts[0]->porcentaje;     
            $na1t1m4 = round($na1t1m4, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na1t1m4 = 0.0;
        }


        //====================================== ACTIVIDAD CUADERNO ===================================// 
        try{
            $cuaderno = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')               
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')            
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')           
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')        
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[1]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[0]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 2 trimestre 1 materia 1
            $na2t1m4 = 0.0;
                foreach ($cuaderno as $in) {
                    $na2t1m4 = $na2t1m4 + $in->nota*$in->pEval;
                }
            $na2t1m4 = $na2t1m4/$acts[1]->porcentaje; 
            $na2t1m4 = round($na2t1m4, 2);      //Se redondea  a 2 decimales
        }catch(\Exception $e){
            $na2t1m4 = 0.0;
        }


        //====================================== ACTIVIDAD PROYECTO ===================================//   
        try{
            $proyecto = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')               
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')            
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')           
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')        
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[2]->id_actividad)               // actividad *
            ->Where('trimestre.id_trimestre','=', $trim[0]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 3 trimestre 1 materia 1
            $na3t1m4 = 0.0;
                foreach ($proyecto as $in) {
                    $na3t1m4 = $na3t1m4 + $in->nota*$in->pEval;
                }
            $na3t1m4 = $na3t1m4/$acts[2]->porcentaje; 
            $na3t1m4 = round($na3t1m4, 2);      //Se redondea  a 2 decimales
        }catch(\Exception $e){
            $na3t1m4 = 0.0;
        }


        //====================================== ACTIVIDAD PRUEBA ===================================//
        try{
            $prueba = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')               
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')            
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')           
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')        
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[3]->id_actividad)               // actividad *
            ->Where('trimestre.id_trimestre','=', $trim[0]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 3 trimestre 1 materia 1
            $na4t1m4 = 0.0;
                foreach ($prueba as $in) {
                    $na4t1m4 = $na4t1m4 + $in->nota*$in->pEval;
                }
            $na4t1m4 = $na4t1m4/$acts[3]->porcentaje; 
            $na4t1m4 = round($na4t1m4, 2);      //Se redondea  a 2 decimales
        }catch(\Exception $e){
            $na4t1m4 = 0.0;
        }

        //=========================================================================================================//
        //============================================= PROMEDIO_T1M1 ============================================//
        //=======================================================================================================//

        $prom_t1m4 = ($na1t1m4*0.3 + $na2t1m4*0.2 + $na3t1m4*0.1 +$na4t1m4*0.4);
        $prom_t1m4 = round($prom_t1m4,2);



        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////      MATERIA CIENCIAS      ///////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////             SEGUNDO TRIMESTRE             ///////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////

        //====================================== ACTIVIDAD INTEGRADORA ===================================//  
  
        try{   
            $integradora = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[4]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[1]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na1t2m4 = 0.0;
                foreach ($integradora as $in) {
                    $na1t2m4 = $na1t2m4 + $in->nota*$in->pEval;
                }
            $na1t2m4 = $na1t2m4/$acts[4]->porcentaje;     
            $na1t2m4 = round($na1t2m4, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na1t2m4 = 0.0;
        }


        //====================================== ACTIVIDAD CUADERNO ===================================// 
        try{   
            $cuaderno = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[5]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[1]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na2t2m4 = 0.0;
                foreach ($cuaderno as $in) {
                    $na2t2m4 = $na2t2m4 + $in->nota*$in->pEval;
                }
            $na2t2m4 = $na2t2m4/$acts[5]->porcentaje;     
            $na2t2m4 = round($na2t2m4, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na2t2m4 = 0.0;
        }

        //====================================== ACTIVIDAD PROYECTO ===================================// 
        try{   
            $proyecto = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[6]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[1]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na3t2m4 = 0.0;
                foreach ($proyecto as $in) {
                    $na3t2m4 = $na3t2m4 + $in->nota*$in->pEval;
                }
            $na3t2m4 = $na3t2m4/$acts[6]->porcentaje;     
            $na3t2m4 = round($na3t2m4, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na3t2m4 = 0.0;
        }

        //====================================== ACTIVIDAD PRUEBA ===================================// 
        try{   
            $prueba = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[7]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[1]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na4t2m4 = 0.0;
                foreach ($prueba as $in) {
                    $na4t2m4 = $na4t2m4 + $in->nota*$in->pEval;
                }
            $na4t2m4 = $na4t2m4/$acts[7]->porcentaje;     
            $na4t2m4 = round($na4t2m4, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na4t2m4 = 0.0;
        }

        //=========================================================================================================//
        //============================================= PROMEDIO_T2M1 ============================================//
        //=======================================================================================================//

        $prom_t2m4 = ($na1t2m4*0.3 + $na2t2m4*0.2 + $na3t2m4*0.1 +$na4t2m4*0.4);
        $prom_t2m4 = round($prom_t2m4,2);



        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////      MATERIA MATEMATICA        ////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////             TERCER TRIMESTRE              ///////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////

        //====================================== ACTIVIDAD INTEGRADORA ===================================//  
  
        try{   
            $integradora = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[8]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[2]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na1t3m4 = 0.0;
                foreach ($integradora as $in) {
                    $na1t3m4 = $na1t3m4 + $in->nota*$in->pEval;
                }
            $na1t3m4 = $na1t3m4/$acts[8]->porcentaje;     
            $na1t3m4 = round($na1t3m4, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na1t3m4 = 0.0;
        }

        //====================================== ACTIVIDAD CUADERNO ===================================//  
  
        try{   
            $cuaderno = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[9]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[2]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na2t3m4 = 0.0;
                foreach ($cuaderno as $in) {
                    $na2t3m4 = $na2t3m4 + $in->nota*$in->pEval;
                }
            $na2t3m4 = $na2t3m4/$acts[9]->porcentaje;     
            $na2t3m4 = round($na2t3m4, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na2t3m4 = 0.0;
        }


        //====================================== ACTIVIDAD PROYECTO ===================================//  
  
        try{   
            $proyecto = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[10]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[2]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na3t3m4 = 0.0;
                foreach ($proyecto as $in) {
                    $na3t3m4 = $na3t3m4 + $in->nota*$in->pEval;
                }
            $na3t3m4 = $na3t3m4/$acts[10]->porcentaje;     
            $na3t3m4 = round($na3t3m4, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na3t3m4 = 0.0;
        }

        //====================================== ACTIVIDAD PRUEBA ===================================//  
  
        try{   
            $prueba = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[11]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[2]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na4t3m4 = 0.0;
                foreach ($prueba as $in) {
                    $na4t3m4 = $na4t3m4 + $in->nota*$in->pEval;
                }
            $na4t3m4 = $na4t3m4/$acts[11]->porcentaje;     
            $na4t3m4 = round($na4t3m4, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na4t3m4 = 0.0;
        }

        //=========================================================================================================//
        //============================================= PROMEDIO_T3M2 ============================================//
        //=======================================================================================================//

        $prom_t3m4 = ($na1t3m4*0.3 + $na2t3m4*0.2 + $na3t3m4*0.1 +$na4t3m4*0.4);
        $prom_t3m4 = round($prom_t3m4, 2);

        //=========================================================================================================//
        //============================================= PROMEDIO_FINAL_M2 ========================================//
        //=======================================================================================================//

        $prom_final_m4 = ($prom_t1m4 + $prom_t2m4 + $prom_t3m4)/3;
        $prom_final_m4 = round($prom_final_m4, 2);

        if ($prom_final_m4 >= 5.0) {
            $res_m4 = "APROBADO";
            }else{
                $res_m4 = "REPROBADO";
            }


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////      MATERIA INGLES     ////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////             PRIMER TRIMESTRE             ////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////

        //====================================== ACTIVIDAD INTEGRADORA ===================================//  

        //Se consulta la asignacion del docente (MATEMATICA)
        
            $query1 = Asignacion::where('id_detalleasignacion','=', $query0->id_detalleasignacion)->where('mdui', $dui)
                ->where('id_materia',5)->where('anioasignacion',$anio)->first();  
        try{   
            $integradora = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[0]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[0]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na1t1m5 = 0.0;
                foreach ($integradora as $in) {
                    $na1t1m5 = $na1t1m5 + $in->nota*$in->pEval;
                }
            $na1t1m5 = $na1t1m5/$acts[0]->porcentaje;     
            $na1t1m5 = round($na1t1m5, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na1t1m5 = 0.0;
        }


        //====================================== ACTIVIDAD CUADERNO ===================================// 
        try{
            $cuaderno = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')               
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')            
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')           
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')        
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[1]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[0]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 2 trimestre 1 materia 1
            $na2t1m5 = 0.0;
                foreach ($cuaderno as $in) {
                    $na2t1m5 = $na2t1m5 + $in->nota*$in->pEval;
                }
            $na2t1m5 = $na2t1m5/$acts[1]->porcentaje; 
            $na2t1m5 = round($na2t1m5, 2);      //Se redondea  a 2 decimales
        }catch(\Exception $e){
            $na2t1m5 = 0.0;
        }


        //====================================== ACTIVIDAD PROYECTO ===================================//   
        try{
            $proyecto = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')               
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')            
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')           
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')        
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[2]->id_actividad)               // actividad *
            ->Where('trimestre.id_trimestre','=', $trim[0]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 3 trimestre 1 materia 1
            $na3t1m5 = 0.0;
                foreach ($proyecto as $in) {
                    $na3t1m5 = $na3t1m5 + $in->nota*$in->pEval;
                }
            $na3t1m5 = $na3t1m5/$acts[2]->porcentaje; 
            $na3t1m5 = round($na3t1m5, 2);      //Se redondea  a 2 decimales
        }catch(\Exception $e){
            $na3t1m5 = 0.0;
        }


        //====================================== ACTIVIDAD PRUEBA ===================================//
        try{
            $prueba = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')               
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')            
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')           
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')        
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[3]->id_actividad)               // actividad *
            ->Where('trimestre.id_trimestre','=', $trim[0]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 3 trimestre 1 materia 1
            $na4t1m5 = 0.0;
                foreach ($prueba as $in) {
                    $na4t1m5 = $na4t1m5 + $in->nota*$in->pEval;
                }
            $na4t1m5 = $na4t1m5/$acts[3]->porcentaje; 
            $na4t1m5 = round($na4t1m5, 2);      //Se redondea  a 2 decimales
        }catch(\Exception $e){
            $na4t1m5 = 0.0;
        }

        //=========================================================================================================//
        //============================================= PROMEDIO_T1M1 ============================================//
        //=======================================================================================================//

        $prom_t1m5 = ($na1t1m5*0.3 + $na2t1m5*0.2 + $na3t1m5*0.1 +$na4t1m5*0.4);
        $prom_t1m5 = round($prom_t1m5,2);



        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////      MATERIA CIENCIAS      ///////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////             SEGUNDO TRIMESTRE             ///////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////

        //====================================== ACTIVIDAD INTEGRADORA ===================================//  
  
        try{   
            $integradora = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[4]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[1]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na1t2m5 = 0.0;
                foreach ($integradora as $in) {
                    $na1t2m5 = $na1t2m5 + $in->nota*$in->pEval;
                }
            $na1t2m5 = $na1t2m5/$acts[4]->porcentaje;     
            $na1t2m5 = round($na1t2m5, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na1t2m5 = 0.0;
        }


        //====================================== ACTIVIDAD CUADERNO ===================================// 
        try{   
            $cuaderno = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[5]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[1]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na2t2m5 = 0.0;
                foreach ($cuaderno as $in) {
                    $na2t2m5 = $na2t2m5 + $in->nota*$in->pEval;
                }
            $na2t2m5 = $na2t2m5/$acts[5]->porcentaje;     
            $na2t2m5 = round($na2t2m5, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na2t2m5 = 0.0;
        }

        //====================================== ACTIVIDAD PROYECTO ===================================// 
        try{   
            $proyecto = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[6]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[1]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na3t2m5 = 0.0;
                foreach ($proyecto as $in) {
                    $na3t2m5 = $na3t2m5 + $in->nota*$in->pEval;
                }
            $na3t2m5 = $na3t2m5/$acts[6]->porcentaje;     
            $na3t2m5 = round($na3t2m5, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na3t2m5 = 0.0;
        }

        //====================================== ACTIVIDAD PRUEBA ===================================// 
        try{   
            $prueba = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[7]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[1]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na4t2m5 = 0.0;
                foreach ($prueba as $in) {
                    $na4t2m5 = $na4t2m5 + $in->nota*$in->pEval;
                }
            $na4t2m5 = $na4t2m5/$acts[7]->porcentaje;     
            $na4t2m5 = round($na4t2m5, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na4t2m5 = 0.0;
        }

        //=========================================================================================================//
        //============================================= PROMEDIO_T2M1 ============================================//
        //=======================================================================================================//

        $prom_t2m5 = ($na1t2m5*0.3 + $na2t2m5*0.2 + $na3t2m5*0.1 +$na4t2m5*0.4);
        $prom_t2m5 = round($prom_t2m5,2);



        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////      MATERIA MATEMATICA        ////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////             TERCER TRIMESTRE              ///////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////

        //====================================== ACTIVIDAD INTEGRADORA ===================================//  
  
        try{   
            $integradora = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[8]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[2]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na1t3m5 = 0.0;
                foreach ($integradora as $in) {
                    $na1t3m5 = $na1t3m5 + $in->nota*$in->pEval;
                }
            $na1t3m5 = $na1t3m5/$acts[8]->porcentaje;     
            $na1t3m5 = round($na1t3m5, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na1t3m5 = 0.0;
        }

        //====================================== ACTIVIDAD CUADERNO ===================================//  
  
        try{   
            $cuaderno = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[9]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[2]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na2t3m5 = 0.0;
                foreach ($cuaderno as $in) {
                    $na2t3m5 = $na2t3m5 + $in->nota*$in->pEval;
                }
            $na2t3m5 = $na2t3m5/$acts[9]->porcentaje;     
            $na2t3m5 = round($na2t3m5, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na2t3m5 = 0.0;
        }


        //====================================== ACTIVIDAD PROYECTO ===================================//  
  
        try{   
            $proyecto = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[10]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[2]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na3t3m5 = 0.0;
                foreach ($proyecto as $in) {
                    $na3t3m5 = $na3t3m5 + $in->nota*$in->pEval;
                }
            $na3t3m5 = $na3t3m5/$acts[10]->porcentaje;     
            $na3t3m5 = round($na3t3m5, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na3t3m5 = 0.0;
        }

        //====================================== ACTIVIDAD PRUEBA ===================================//  
  
        try{   
            $prueba = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[11]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[2]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na4t3m5 = 0.0;
                foreach ($prueba as $in) {
                    $na4t3m5 = $na4t3m5 + $in->nota*$in->pEval;
                }
            $na4t3m5 = $na4t3m5/$acts[11]->porcentaje;     
            $na4t3m5 = round($na4t3m5, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na4t3m5 = 0.0;
        }

        //=========================================================================================================//
        //============================================= PROMEDIO_T3M2 ============================================//
        //=======================================================================================================//

        $prom_t3m5 = ($na1t3m5*0.3 + $na2t3m5*0.2 + $na3t3m5*0.1 +$na4t3m5*0.4);
        $prom_t3m5 = round($prom_t3m5, 2);

        //=========================================================================================================//
        //============================================= PROMEDIO_FINAL_M2 ========================================//
        //=======================================================================================================//

        $prom_final_m5 = ($prom_t1m5 + $prom_t2m5 + $prom_t3m5)/3;
        $prom_final_m5 = round($prom_final_m5, 2);

        if ($prom_final_m5 >= 5.0) {
            $res_m5 = "APROBADO";
            }else{
                $res_m5 = "REPROBADO";
            }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////      MATERIA EDUCACION FISICA    ////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////             PRIMER TRIMESTRE             ////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////

        //====================================== ACTIVIDAD INTEGRADORA ===================================//  

        //Se consulta la asignacion del docente (MATEMATICA)
        
            $query1 = Asignacion::where('id_detalleasignacion','=', $query0->id_detalleasignacion)->where('mdui', $dui)
                ->where('id_materia',6)->where('anioasignacion',$anio)->first();  
        try{   
            $integradora = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[0]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[0]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na1t1m6 = 0.0;
                foreach ($integradora as $in) {
                    $na1t1m6 = $na1t1m6 + $in->nota*$in->pEval;
                }
            $na1t1m6 = $na1t1m6/$acts[0]->porcentaje;     
            $na1t1m6 = round($na1t1m6, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na1t1m6 = 0.0;
        }


        //====================================== ACTIVIDAD CUADERNO ===================================// 
        try{
            $cuaderno = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')               
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')            
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')           
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')        
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[1]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[0]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 2 trimestre 1 materia 1
            $na2t1m6 = 0.0;
                foreach ($cuaderno as $in) {
                    $na2t1m6 = $na2t1m6 + $in->nota*$in->pEval;
                }
            $na2t1m6 = $na2t1m6/$acts[1]->porcentaje; 
            $na2t1m6 = round($na2t1m6, 2);      //Se redondea  a 2 decimales
        }catch(\Exception $e){
            $na2t1m6 = 0.0;
        }


        //====================================== ACTIVIDAD PROYECTO ===================================//   
        try{
            $proyecto = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')               
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')            
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')           
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')        
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[2]->id_actividad)               // actividad *
            ->Where('trimestre.id_trimestre','=', $trim[0]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 3 trimestre 1 materia 1
            $na3t1m6 = 0.0;
                foreach ($proyecto as $in) {
                    $na3t1m6 = $na3t1m6 + $in->nota*$in->pEval;
                }
            $na3t1m6 = $na3t1m6/$acts[2]->porcentaje; 
            $na3t1m6 = round($na3t1m6, 2);      //Se redondea  a 2 decimales
        }catch(\Exception $e){
            $na3t1m6 = 0.0;
        }


        //====================================== ACTIVIDAD PRUEBA ===================================//
        try{
            $prueba = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')               
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')            
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')           
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')        
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[3]->id_actividad)               // actividad *
            ->Where('trimestre.id_trimestre','=', $trim[0]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 3 trimestre 1 materia 1
            $na4t1m6 = 0.0;
                foreach ($prueba as $in) {
                    $na4t1m6 = $na4t1m6 + $in->nota*$in->pEval;
                }
            $na4t1m6 = $na4t1m6/$acts[3]->porcentaje; 
            $na4t1m6 = round($na4t1m6, 2);      //Se redondea  a 2 decimales
        }catch(\Exception $e){
            $na4t1m6 = 0.0;
        }

        //=========================================================================================================//
        //============================================= PROMEDIO_T1M1 ============================================//
        //=======================================================================================================//

        $prom_t1m6 = ($na1t1m6*0.3 + $na2t1m6*0.2 + $na3t1m6*0.1 +$na4t1m6*0.4);
        $prom_t1m6 = round($prom_t1m6,2);



        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////      MATERIA CIENCIAS      ///////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////             SEGUNDO TRIMESTRE             ///////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////

        //====================================== ACTIVIDAD INTEGRADORA ===================================//  
  
        try{   
            $integradora = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[4]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[1]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na1t2m6 = 0.0;
                foreach ($integradora as $in) {
                    $na1t2m6 = $na1t2m6 + $in->nota*$in->pEval;
                }
            $na1t2m6 = $na1t2m6/$acts[4]->porcentaje;     
            $na1t2m6 = round($na1t2m6, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na1t2m6 = 0.0;
        }


        //====================================== ACTIVIDAD CUADERNO ===================================// 
        try{   
            $cuaderno = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[5]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[1]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na2t2m6 = 0.0;
                foreach ($cuaderno as $in) {
                    $na2t2m6 = $na2t2m6 + $in->nota*$in->pEval;
                }
            $na2t2m6 = $na2t2m6/$acts[5]->porcentaje;     
            $na2t2m6 = round($na2t2m6, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na2t2m6 = 0.0;
        }

        //====================================== ACTIVIDAD PROYECTO ===================================// 
        try{   
            $proyecto = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[6]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[1]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na3t2m6 = 0.0;
                foreach ($proyecto as $in) {
                    $na3t2m6 = $na3t2m6 + $in->nota*$in->pEval;
                }
            $na3t2m6 = $na3t2m6/$acts[6]->porcentaje;     
            $na3t2m6 = round($na3t2m6, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na3t2m6 = 0.0;
        }

        //====================================== ACTIVIDAD PRUEBA ===================================// 
        try{   
            $prueba = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[7]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[1]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na4t2m6 = 0.0;
                foreach ($prueba as $in) {
                    $na4t2m6 = $na4t2m6 + $in->nota*$in->pEval;
                }
            $na4t2m6 = $na4t2m6/$acts[7]->porcentaje;     
            $na4t2m6 = round($na4t2m6, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na4t2m6 = 0.0;
        }

        //=========================================================================================================//
        //============================================= PROMEDIO_T2M1 ============================================//
        //=======================================================================================================//

        $prom_t2m6 = ($na1t2m6*0.3 + $na2t2m6*0.2 + $na3t2m6*0.1 +$na4t2m6*0.4);
        $prom_t2m6 = round($prom_t2m6,2);



        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////      MATERIA MATEMATICA        ////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////             TERCER TRIMESTRE              ///////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////

        //====================================== ACTIVIDAD INTEGRADORA ===================================//  
  
        try{   
            $integradora = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[8]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[2]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na1t3m6 = 0.0;
                foreach ($integradora as $in) {
                    $na1t3m6 = $na1t3m6 + $in->nota*$in->pEval;
                }
            $na1t3m6 = $na1t3m6/$acts[8]->porcentaje;     
            $na1t3m6 = round($na1t3m6, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na1t3m6 = 0.0;
        }

        //====================================== ACTIVIDAD CUADERNO ===================================//  
  
        try{   
            $cuaderno = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[9]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[2]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na2t3m6 = 0.0;
                foreach ($cuaderno as $in) {
                    $na2t3m6 = $na2t3m6 + $in->nota*$in->pEval;
                }
            $na2t3m6 = $na2t3m6/$acts[9]->porcentaje;     
            $na2t3m6 = round($na2t3m6, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na2t3m6 = 0.0;
        }


        //====================================== ACTIVIDAD PROYECTO ===================================//  
  
        try{   
            $proyecto = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[10]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[2]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na3t3m6 = 0.0;
                foreach ($proyecto as $in) {
                    $na3t3m6 = $na3t3m6 + $in->nota*$in->pEval;
                }
            $na3t3m6 = $na3t3m6/$acts[10]->porcentaje;     
            $na3t3m6 = round($na3t3m6, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na3t3m6 = 0.0;
        }

        //====================================== ACTIVIDAD PRUEBA ===================================//  
  
        try{   
            $prueba = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[11]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[2]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na4t3m6 = 0.0;
                foreach ($prueba as $in) {
                    $na4t3m6 = $na4t3m6 + $in->nota*$in->pEval;
                }
            $na4t3m6 = $na4t3m6/$acts[11]->porcentaje;     
            $na4t3m6 = round($na4t3m6, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na4t3m6 = 0.0;
        }

        //=========================================================================================================//
        //============================================= PROMEDIO_T3M2 ============================================//
        //=======================================================================================================//

        $prom_t3m6 = ($na1t3m6*0.3 + $na2t3m6*0.2 + $na3t3m6*0.1 +$na4t3m6*0.4);
        $prom_t3m6 = round($prom_t3m6, 2);

        //=========================================================================================================//
        //============================================= PROMEDIO_FINAL_M2 ========================================//
        //=======================================================================================================//

        $prom_final_m6 = ($prom_t1m6 + $prom_t2m6 + $prom_t3m6)/3;
        $prom_final_m6 = round($prom_final_m6, 2);

        if ($prom_final_m6 >= 5.0) {
            $res_m6 = "APROBADO";
            }else{
                $res_m6 = "REPROBADO";
            }


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////      MATERIA MORAL Y CIVICA   ////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////             PRIMER TRIMESTRE             ////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////

        //====================================== ACTIVIDAD INTEGRADORA ===================================//  

        //Se consulta la asignacion del docente (MATEMATICA)
        
            $query1 = Asignacion::where('id_detalleasignacion','=', $query0->id_detalleasignacion)->where('mdui', $dui)
                ->where('id_materia',7)->where('anioasignacion',$anio)->first();  
        try{   
            $integradora = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[0]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[0]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na1t1m7 = 0.0;
                foreach ($integradora as $in) {
                    $na1t1m7 = $na1t1m7 + $in->nota*$in->pEval;
                }
            $na1t1m7 = $na1t1m7/$acts[0]->porcentaje;     
            $na1t1m7 = round($na1t1m7, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na1t1m7 = 0.0;
        }


        //====================================== ACTIVIDAD CUADERNO ===================================// 
        try{
            $cuaderno = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')               
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')            
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')           
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')        
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[1]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[0]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 2 trimestre 1 materia 1
            $na2t1m7 = 0.0;
                foreach ($cuaderno as $in) {
                    $na2t1m7 = $na2t1m7 + $in->nota*$in->pEval;
                }
            $na2t1m7 = $na2t1m7/$acts[1]->porcentaje; 
            $na2t1m7 = round($na2t1m7, 2);      //Se redondea  a 2 decimales
        }catch(\Exception $e){
            $na2t1m7 = 0.0;
        }


        //====================================== ACTIVIDAD PROYECTO ===================================//   
        try{
            $proyecto = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')               
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')            
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')           
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')        
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[2]->id_actividad)               // actividad *
            ->Where('trimestre.id_trimestre','=', $trim[0]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 3 trimestre 1 materia 1
            $na3t1m7 = 0.0;
                foreach ($proyecto as $in) {
                    $na3t1m7 = $na3t1m7 + $in->nota*$in->pEval;
                }
            $na3t1m7 = $na3t1m7/$acts[2]->porcentaje; 
            $na3t1m7 = round($na3t1m7, 2);      //Se redondea  a 2 decimales
        }catch(\Exception $e){
            $na3t1m7 = 0.0;
        }


        //====================================== ACTIVIDAD PRUEBA ===================================//
        try{
            $prueba = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')               
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')            
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')           
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')        
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[3]->id_actividad)               // actividad *
            ->Where('trimestre.id_trimestre','=', $trim[0]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 3 trimestre 1 materia 1
            $na4t1m7 = 0.0;
                foreach ($prueba as $in) {
                    $na4t1m7 = $na4t1m7 + $in->nota*$in->pEval;
                }
            $na4t1m7 = $na4t1m7/$acts[3]->porcentaje; 
            $na4t1m7 = round($na4t1m7, 2);      //Se redondea  a 2 decimales
        }catch(\Exception $e){
            $na4t1m7 = 0.0;
        }

        //=========================================================================================================//
        //============================================= PROMEDIO_T1M1 ============================================//
        //=======================================================================================================//

        $prom_t1m7 = ($na1t1m7*0.3 + $na2t1m7*0.2 + $na3t1m7*0.1 +$na4t1m7*0.4);
        $prom_t1m7 = round($prom_t1m7,2);



        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////      MATERIA CIENCIAS      ///////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////             SEGUNDO TRIMESTRE             ///////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////

        //====================================== ACTIVIDAD INTEGRADORA ===================================//  
  
        try{   
            $integradora = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[4]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[1]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na1t2m7 = 0.0;
                foreach ($integradora as $in) {
                    $na1t2m7 = $na1t2m7 + $in->nota*$in->pEval;
                }
            $na1t2m7 = $na1t2m7/$acts[4]->porcentaje;     
            $na1t2m7 = round($na1t2m7, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na1t2m7 = 0.0;
        }


        //====================================== ACTIVIDAD CUADERNO ===================================// 
        try{   
            $cuaderno = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[5]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[1]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na2t2m7 = 0.0;
                foreach ($cuaderno as $in) {
                    $na2t2m7 = $na2t2m7 + $in->nota*$in->pEval;
                }
            $na2t2m7 = $na2t2m7/$acts[5]->porcentaje;     
            $na2t2m7 = round($na2t2m7, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na2t2m7 = 0.0;
        }

        //====================================== ACTIVIDAD PROYECTO ===================================// 
        try{   
            $proyecto = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[6]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[1]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na3t2m7 = 0.0;
                foreach ($proyecto as $in) {
                    $na3t2m7 = $na3t2m7 + $in->nota*$in->pEval;
                }
            $na3t2m7 = $na3t2m7/$acts[6]->porcentaje;     
            $na3t2m7 = round($na3t2m7, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na3t2m7 = 0.0;
        }

        //====================================== ACTIVIDAD PRUEBA ===================================// 
        try{   
            $prueba = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[7]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[1]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na4t2m7 = 0.0;
                foreach ($prueba as $in) {
                    $na4t2m7 = $na4t2m7 + $in->nota*$in->pEval;
                }
            $na4t2m7 = $na4t2m7/$acts[7]->porcentaje;     
            $na4t2m7 = round($na4t2m7, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na4t2m7 = 0.0;
        }

        //=========================================================================================================//
        //============================================= PROMEDIO_T2M1 ============================================//
        //=======================================================================================================//

        $prom_t2m7 = ($na1t2m7*0.3 + $na2t2m7*0.2 + $na3t2m7*0.1 +$na4t2m7*0.4);
        $prom_t2m7 = round($prom_t2m7,2);



        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////      MATERIA MATEMATICA        ////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////             TERCER TRIMESTRE              ///////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////

        //====================================== ACTIVIDAD INTEGRADORA ===================================//  
  
        try{   
            $integradora = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[8]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[2]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na1t3m7 = 0.0;
                foreach ($integradora as $in) {
                    $na1t3m7 = $na1t3m7 + $in->nota*$in->pEval;
                }
            $na1t3m7 = $na1t3m7/$acts[8]->porcentaje;     
            $na1t3m7 = round($na1t3m7, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na1t3m7 = 0.0;
        }

        //====================================== ACTIVIDAD CUADERNO ===================================//  
  
        try{   
            $cuaderno = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[9]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[2]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na2t3m7 = 0.0;
                foreach ($cuaderno as $in) {
                    $na2t3m7 = $na2t3m7 + $in->nota*$in->pEval;
                }
            $na2t3m7 = $na2t3m7/$acts[9]->porcentaje;     
            $na2t3m7 = round($na2t3m7, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na2t3m7 = 0.0;
        }


        //====================================== ACTIVIDAD PROYECTO ===================================//  
  
        try{   
            $proyecto = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[10]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[2]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na3t3m7 = 0.0;
                foreach ($proyecto as $in) {
                    $na3t3m7 = $na3t3m7 + $in->nota*$in->pEval;
                }
            $na3t3m7 = $na3t3m7/$acts[10]->porcentaje;     
            $na3t3m7 = round($na3t3m7, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na3t3m7 = 0.0;
        }

        //====================================== ACTIVIDAD PRUEBA ===================================//  
  
        try{   
            $prueba = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[11]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[2]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na4t3m7 = 0.0;
                foreach ($prueba as $in) {
                    $na4t3m7 = $na4t3m7 + $in->nota*$in->pEval;
                }
            $na4t3m7 = $na4t3m7/$acts[11]->porcentaje;     
            $na4t3m7 = round($na4t3m7, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na4t3m7 = 0.0;
        }

        //=========================================================================================================//
        //============================================= PROMEDIO_T3M2 ============================================//
        //=======================================================================================================//

        $prom_t3m7 = ($na1t3m7*0.3 + $na2t3m7*0.2 + $na3t3m7*0.1 +$na4t3m7*0.4);
        $prom_t3m7 = round($prom_t3m7, 2);

        //=========================================================================================================//
        //============================================= PROMEDIO_FINAL_M2 ========================================//
        //=======================================================================================================//

        $prom_final_m7 = ($prom_t1m7 + $prom_t2m7 + $prom_t3m7)/3;
        $prom_final_m7 = round($prom_final_m7, 2);

        if ($prom_final_m7 >= 5.0) {
            $res_m7 = "APROBADO";
            }else{
                $res_m7 = "REPROBADO";
            }


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////      MATERIA INFORMATICA   ////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////             PRIMER TRIMESTRE             ////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////

        //====================================== ACTIVIDAD INTEGRADORA ===================================//  

        //Se consulta la asignacion del docente (MATEMATICA)
        
            $query1 = Asignacion::where('id_detalleasignacion','=', $query0->id_detalleasignacion)->where('mdui', $dui)
                ->where('id_materia',8)->where('anioasignacion',$anio)->first();  
        try{   
            $integradora = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[0]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[0]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na1t1m8 = 0.0;
                foreach ($integradora as $in) {
                    $na1t1m8 = $na1t1m8 + $in->nota*$in->pEval;
                }
            $na1t1m8 = $na1t1m8/$acts[0]->porcentaje;     
            $na1t1m8 = round($na1t1m8, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na1t1m8 = 0.0;
        }


        //====================================== ACTIVIDAD CUADERNO ===================================// 
        try{
            $cuaderno = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')               
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')            
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')           
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')        
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[1]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[0]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 2 trimestre 1 materia 1
            $na2t1m8 = 0.0;
                foreach ($cuaderno as $in) {
                    $na2t1m8 = $na2t1m8 + $in->nota*$in->pEval;
                }
            $na2t1m8 = $na2t1m8/$acts[1]->porcentaje; 
            $na2t1m8 = round($na2t1m8, 2);      //Se redondea  a 2 decimales
        }catch(\Exception $e){
            $na2t1m8 = 0.0;
        }


        //====================================== ACTIVIDAD PROYECTO ===================================//   
        try{
            $proyecto = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')               
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')            
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')           
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')        
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[2]->id_actividad)               // actividad *
            ->Where('trimestre.id_trimestre','=', $trim[0]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 3 trimestre 1 materia 1
            $na3t1m8 = 0.0;
                foreach ($proyecto as $in) {
                    $na3t1m8 = $na3t1m8 + $in->nota*$in->pEval;
                }
            $na3t1m8 = $na3t1m8/$acts[2]->porcentaje; 
            $na3t1m8 = round($na3t1m8, 2);      //Se redondea  a 2 decimales
        }catch(\Exception $e){
            $na3t1m8 = 0.0;
        }


        //====================================== ACTIVIDAD PRUEBA ===================================//
        try{
            $prueba = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')               
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')            
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')           
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')        
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[3]->id_actividad)               // actividad *
            ->Where('trimestre.id_trimestre','=', $trim[0]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 3 trimestre 1 materia 1
            $na4t1m8 = 0.0;
                foreach ($prueba as $in) {
                    $na4t1m8 = $na4t1m8 + $in->nota*$in->pEval;
                }
            $na4t1m8 = $na4t1m8/$acts[3]->porcentaje; 
            $na4t1m8 = round($na4t1m8, 2);      //Se redondea  a 2 decimales
        }catch(\Exception $e){
            $na4t1m8 = 0.0;
        }

        //=========================================================================================================//
        //============================================= PROMEDIO_T1M1 ============================================//
        //=======================================================================================================//

        $prom_t1m8 = ($na1t1m8*0.3 + $na2t1m8*0.2 + $na3t1m8*0.1 +$na4t1m8*0.4);
        $prom_t1m8 = round($prom_t1m8,2);



        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////      MATERIA CIENCIAS      ///////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////             SEGUNDO TRIMESTRE             ///////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////

        //====================================== ACTIVIDAD INTEGRADORA ===================================//  
  
        try{   
            $integradora = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[4]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[1]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na1t2m8 = 0.0;
                foreach ($integradora as $in) {
                    $na1t2m8 = $na1t2m8 + $in->nota*$in->pEval;
                }
            $na1t2m8 = $na1t2m8/$acts[4]->porcentaje;     
            $na1t2m8 = round($na1t2m8, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na1t2m8 = 0.0;
        }


        //====================================== ACTIVIDAD CUADERNO ===================================// 
        try{   
            $cuaderno = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[5]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[1]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na2t2m8 = 0.0;
                foreach ($cuaderno as $in) {
                    $na2t2m8 = $na2t2m8 + $in->nota*$in->pEval;
                }
            $na2t2m8 = $na2t2m8/$acts[5]->porcentaje;     
            $na2t2m8 = round($na2t2m8, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na2t2m8 = 0.0;
        }

        //====================================== ACTIVIDAD PROYECTO ===================================// 
        try{   
            $proyecto = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[6]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[1]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na3t2m8 = 0.0;
                foreach ($proyecto as $in) {
                    $na3t2m8 = $na3t2m8 + $in->nota*$in->pEval;
                }
            $na3t2m8 = $na3t2m8/$acts[6]->porcentaje;     
            $na3t2m8 = round($na3t2m8, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na3t2m8 = 0.0;
        }

        //====================================== ACTIVIDAD PRUEBA ===================================// 
        try{   
            $prueba = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[7]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[1]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na4t2m8 = 0.0;
                foreach ($prueba as $in) {
                    $na4t2m8 = $na4t2m8 + $in->nota*$in->pEval;
                }
            $na4t2m8 = $na4t2m8/$acts[7]->porcentaje;     
            $na4t2m8 = round($na4t2m8, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na4t2m8 = 0.0;
        }

        //=========================================================================================================//
        //============================================= PROMEDIO_T2M1 ============================================//
        //=======================================================================================================//

        $prom_t2m8 = ($na1t2m8*0.3 + $na2t2m8*0.2 + $na3t2m8*0.1 +$na4t2m8*0.4);
        $prom_t2m8 = round($prom_t2m8,2);



        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////      MATERIA MATEMATICA        ////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////             TERCER TRIMESTRE              ///////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////

        //====================================== ACTIVIDAD INTEGRADORA ===================================//  
  
        try{   
            $integradora = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[8]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[2]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na1t3m8 = 0.0;
                foreach ($integradora as $in) {
                    $na1t3m8 = $na1t3m8 + $in->nota*$in->pEval;
                }
            $na1t3m8 = $na1t3m8/$acts[8]->porcentaje;     
            $na1t3m8 = round($na1t3m8, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na1t3m8 = 0.0;
        }

        //====================================== ACTIVIDAD CUADERNO ===================================//  
  
        try{   
            $cuaderno = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[9]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[2]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na2t3m8 = 0.0;
                foreach ($cuaderno as $in) {
                    $na2t3m8 = $na2t3m8 + $in->nota*$in->pEval;
                }
            $na2t3m8 = $na2t3m8/$acts[9]->porcentaje;     
            $na2t3m8 = round($na2t3m8, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na2t3m8 = 0.0;
        }


        //====================================== ACTIVIDAD PROYECTO ===================================//  
  
        try{   
            $proyecto = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[10]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[2]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na3t3m8 = 0.0;
                foreach ($proyecto as $in) {
                    $na3t3m8 = $na3t3m8 + $in->nota*$in->pEval;
                }
            $na3t3m8 = $na3t3m8/$acts[10]->porcentaje;     
            $na3t3m8 = round($na3t3m8, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na3t3m8 = 0.0;
        }

        //====================================== ACTIVIDAD PRUEBA ===================================//  
  
        try{   
            $prueba = DB::table('detalleevaluacion as dn')
            ->select('detalle_nota.nota', 'evaluacion.porcentaje as pEval')
            ->join('evaluacion as evaluacion', 'evaluacion.id_evaluacion','=', 'dn.id_evaluacion', 'full outer')        
            ->join('actividad as actividad', 'actividad.id_actividad','=','evaluacion.id_actividad', 'full outer')      
            ->join('trimestre as trimestre', 'actividad.id_trimestre', '=', 'trimestre.id_trimestre', 'full outer')      
            ->join('detalle_nota as detalle_nota', 'detalle_nota.id_detalleevaluacion','=', 'dn.id_detalleevaluacion')   
            ->Where('dn.id_asignacion','=',$query1->id_asignacion)                      // asignacion
            ->Where('actividad.id_actividad','=', $acts[11]->id_actividad)               // actividad
            ->Where('trimestre.id_trimestre','=', $trim[2]->id_trimestre)               // trimestre
            ->Where('detalle_nota.id_matricula','=',$id)                                // matricula
            ->orderby('evaluacion.id_evaluacion', 'asc')                                // ascendente
            ->get();

            //se calcula la nota actividad 1 trimestre 1 materia 1
            $na4t3m8 = 0.0;
                foreach ($prueba as $in) {
                    $na4t3m8 = $na4t3m8 + $in->nota*$in->pEval;
                }
            $na4t3m8 = $na4t3m8/$acts[11]->porcentaje;     
            $na4t3m8 = round($na4t3m8, 2);      //Se redondea  a 2 decimales

            }catch(\Exception $e){
            $na4t3m8 = 0.0;
        }

        //=========================================================================================================//
        //============================================= PROMEDIO_T3M2 ============================================//
        //=======================================================================================================//

        $prom_t3m8 = ($na1t3m8*0.3 + $na2t3m8*0.2 + $na3t3m8*0.1 +$na4t3m8*0.4);
        $prom_t3m8 = round($prom_t3m8, 2);

        //=========================================================================================================//
        //============================================= PROMEDIO_FINAL_M2 ========================================//
        //=======================================================================================================//

        $prom_final_m8 = ($prom_t1m8 + $prom_t2m8 + $prom_t3m8)/3;
        $prom_final_m8 = round($prom_final_m8, 2);

        if ($prom_final_m8 >= 5.0) {
            $res_m8 = "APROBADO";
            }else{
                $res_m8 = "REPROBADO";
            }




        $vistaurl="libretaalguien";
        return $this ->crearPDF($estudiante,$grado,$seccion,$turno,$na1t1m1,$na2t1m1,$na3t1m1,$na4t1m1,$prom_t1m1,$na1t2m1,$na2t2m1,$na3t2m1,$na4t2m1,$prom_t2m1,$na1t3m1,$na2t3m1,$na3t3m1,$na4t3m1,$prom_t3m1,$prom_final_m1,$res_m1,$na1t1m2,$na2t1m2,$na3t1m2,$na4t1m2,$prom_t1m2,$na1t2m2,$na2t2m2,$na3t2m2,$na4t2m2,$prom_t2m2,$na1t3m2,$na2t3m2,$na3t3m2,$na4t3m2,$prom_t3m2,$prom_final_m2,$res_m2,$na1t1m3,$na2t1m3,$na3t1m3,$na4t1m3,$prom_t1m3,$na1t2m3,$na2t2m3,$na3t2m3,$na4t2m3,$prom_t2m3,$na1t3m3,$na2t3m3,$na3t3m3,$na4t3m3,$prom_t3m3,$prom_final_m3,$res_m3,$na1t1m4,$na2t1m4,$na3t1m4,$na4t1m4,$prom_t1m4,$na1t2m4,$na2t2m4,$na3t2m4,$na4t2m4,$prom_t2m4,$na1t3m4,$na2t3m4,$na3t3m4,$na4t3m4,$prom_t3m4,$prom_final_m4,$res_m4,$na1t1m5,$na2t1m5,$na3t1m5,$na4t1m5,$prom_t1m5,$na1t2m5,$na2t2m5,$na3t2m5,$na4t2m5,$prom_t2m5,$na1t3m5,$na2t3m5,$na3t3m5,$na4t3m5,$prom_t3m5,$prom_final_m5,$res_m5,$na1t1m6,$na2t1m6,$na3t1m6,$na4t1m6,$prom_t1m6,$na1t2m6,$na2t2m6,$na3t2m6,$na4t2m6,$prom_t2m6,$na1t3m6,$na2t3m6,$na3t3m6,$na4t3m6,$prom_t3m6,$prom_final_m6,$res_m6,$na1t1m7,$na2t1m7,$na3t1m7,$na4t1m7,$prom_t1m7,$na1t2m7,$na2t2m7,$na3t2m7,$na4t2m7,$prom_t2m7,$na1t3m7,$na2t3m7,$na3t3m7,$na4t3m7,$prom_t3m7,$prom_final_m7,$res_m7,$na1t1m8,$na2t1m8,$na3t1m8,$na4t1m8,$prom_t1m8,$na1t2m8,$na2t2m8,$na3t2m8,$na4t2m8,$prom_t2m8,$na1t3m8,$na2t3m8,$na3t3m8,$na4t3m8,$prom_t3m8,$prom_final_m8,$res_m8,$usuarioactual,$vistaurl);
    }


    public function crearPDF($estudiant,$grad,$seccio,$turn,$na1t1m,$na2t1m,$na3t1m,$na4t1m,$prom_t1m,$na1t2m,$na2t2m,$na3t2m,$na4t2m,$prom_t2m,$na1t3m,$na2t3m,$na3t3m,$na4t3m,$prom_t3m,$prom_final_m,$res_m,$na1t1m2,$na2t1m2,$na3t1m2,$na4t1m2,$prom_t1m2,$na1t2m2,$na2t2m2,$na3t2m2,$na4t2m2,$prom_t2m2,$na1t3m2,$na2t3m2,$na3t3m2,$na4t3m2,$prom_t3m2,$prom_final_m2,$res_m2,$na1t1m3,$na2t1m3,$na3t1m3,$na4t1m3,$prom_t1m3,$na1t2m3,$na2t2m3,$na3t2m3,$na4t2m3,$prom_t2m3,$na1t3m3,$na2t3m3,$na3t3m3,$na4t3m3,$prom_t3m3,$prom_final_m3,$res_m3,$na1t1m4,$na2t1m4,$na3t1m4,$na4t1m4,$prom_t1m4,$na1t2m4,$na2t2m4,$na3t2m4,$na4t2m4,$prom_t2m4,$na1t3m4,$na2t3m4,$na3t3m4,$na4t3m4,$prom_t3m4,$prom_final_m4,$res_m4,$na1t1m5,$na2t1m5,$na3t1m5,$na4t1m5,$prom_t1m5,$na1t2m5,$na2t2m5,$na3t2m5,$na4t2m5,$prom_t2m5,$na1t3m5,$na2t3m5,$na3t3m5,$na4t3m5,$prom_t3m5,$prom_final_m5,$res_m5,$na1t1m6,$na2t1m6,$na3t1m6,$na4t1m6,$prom_t1m6,$na1t2m6,$na2t2m6,$na3t2m6,$na4t2m6,$prom_t2m6,$na1t3m6,$na2t3m6,$na3t3m6,$na4t3m6,$prom_t3m6,$prom_final_m6,$res_m6,$na1t1m7,$na2t1m7,$na3t1m7,$na4t1m7,$prom_t1m7,$na1t2m7,$na2t2m7,$na3t2m7,$na4t2m7,$prom_t2m7,$na1t3m7,$na2t3m7,$na3t3m7,$na4t3m7,$prom_t3m7,$prom_final_m7,$res_m7,$na1t1m8,$na2t1m8,$na3t1m8,$na4t1m8,$prom_t1m8,$na1t2m8,$na2t2m8,$na3t2m8,$na4t2m8,$prom_t2m8,$na1t3m8,$na2t3m8,$na3t3m8,$na4t3m8,$prom_t3m8,$prom_final_m8,$res_m8,$usuarioactua,$vistaurl)
    {

        $estudiante=$estudiant;
        $grado=$grad;
        $seccion=$seccio;
        $turno=$turn;
        $na1t1m1=$na1t1m;
        $na2t1m1=$na2t1m;
        $na3t1m1=$na3t1m;
        $na4t1m1=$na4t1m;
        $prom_t1m1=$prom_t1m;
        $na1t2m1=$na1t2m;
        $na2t2m1=$na2t2m;
        $na3t2m1=$na3t2m;
        $na4t2m1=$na4t2m;
        $prom_t2m1=$prom_t2m;
        $na1t3m1=$na1t3m;
        $na2t3m1=$na2t3m;
        $na3t3m1=$na3t3m;
        $na4t3m1=$na4t3m;
        $prom_t3m1=$prom_t3m;
        $prom_final_m1=$prom_final_m;
        $res_m1=$res_m;
        $usuarioactual=$usuarioactua;

        $view=\View::make($vistaurl, compact('estudiante'),compact('grado','seccion','turno','na1t1m1','na2t1m1','na3t1m1','na4t1m1','prom_t1m1','na1t2m1','na2t2m1','na3t2m1','na4t2m1','prom_t2m1','na1t3m1','na2t3m1','na3t3m1','na4t3m1','prom_t3m1','prom_final_m1','res_m1','na1t1m2','na2t1m2','na3t1m2','na4t1m2','prom_t1m2','na1t2m2','na2t2m2','na3t2m2','na4t2m2','prom_t2m2','na1t3m2','na2t3m2','na3t3m2','na4t3m2','prom_t3m2','prom_final_m2','res_m2','na1t1m3','na2t1m3','na3t1m3','na4t1m3','prom_t1m3','na1t2m3','na2t2m3','na3t2m3','na4t2m3','prom_t2m3','na1t3m3','na2t3m3','na3t3m3','na4t3m3','prom_t3m3','prom_final_m3','res_m3','na1t1m4','na2t1m4','na3t1m4','na4t1m4','prom_t1m4','na1t2m4','na2t2m4','na3t2m4','na4t2m4','prom_t2m4','na1t3m4','na2t3m4','na3t3m4','na4t3m4','prom_t3m4','prom_final_m4','res_m4','na1t1m5','na2t1m5','na3t1m5','na4t1m5','prom_t1m5','na1t2m5','na2t2m5','na3t2m5','na4t2m5','prom_t2m5','na1t3m5','na2t3m5','na3t3m5','na4t3m5','prom_t3m5','prom_final_m5','res_m5','na1t1m6','na2t1m6','na3t1m6','na4t1m6','prom_t1m6','na1t2m6','na2t2m6','na3t2m6','na4t2m6','prom_t2m6','na1t3m6','na2t3m6','na3t3m6','na4t3m6','prom_t3m6','prom_final_m6','res_m6','na1t1m7','na2t1m7','na3t1m7','na4t1m7','prom_t1m7','na1t2m7','na2t2m7','na3t2m7','na4t2m7','prom_t2m7','na1t3m7','na2t3m7','na3t3m7','na4t3m7','prom_t3m7','prom_final_m7','res_m7','na1t1m8','na2t1m8','na3t1m8','na4t1m8','prom_t1m8','na1t2m8','na2t2m8','na3t2m8','na4t2m8','prom_t2m8','na1t3m8','na2t3m8','na3t3m8','na4t3m8','prom_t3m8','prom_final_m8','res_m8','usuarioactual'))->render();
        $pdf =\App::make('dompdf.wrapper');
        $pdf ->loadHTML($view)->setPaper('Letter', 'landscape')->setWarnings(false)->save('libretaPDF.pdf');
       //$pdf->loadHTML($view);
        return $pdf->stream('libretaPDF');

    }


}
