<?php

namespace Escuela\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Escuela\Http\Requests;
use Storage;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Escuela\MaestroUser;
use Escuela\Asignacion;
use Escuela\DetalleAsignacion;
use Escuela\DetalleGrado;
use Escuela\Grado;
use Escuela\Seccion;
use Escuela\Turno;
use Escuela\User;
use Escuela\Falta;
use Escuela\Matricula;
use Escuela\Http\Requests\FaltaRequest;

use Last;
use Illuminate\Support\Facades\Session;

use Carbon\Carbon; //Para la zona fecha horaria

use DB;

class FaltaController extends Controller
{
    public function __construct()   //para validar
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
            
            $det_coor= DetalleAsignacion::where('mdui',$mdui)->first();
            $coor= $det_coor->coordinador;

            
            //Obtenemos el año presente
            $query3 = Carbon::now();
            $query3 = $query3->format('Y');

            //Obtenemos la fecha actual
            $date = Carbon::now(); 
            $date = $date->format('l jS \\of F Y h:i:s A');


           $asig_gram =DB::table('detalle_asignacion')
                ->select('detalle_asignacion.id_detalleasignacion','detalle_asignacion.mdui', 'detalle_asignacion.aniodetalleasignacion', 'maestro.nombre',
                'maestro.apellido','detalle_asignacion.iddetallegrado', 'detalle_grado.iddetallegrado', 'detalle_grado.idgrado', 'detalle_grado.idseccion',
                'detalle_grado.idturno', 'turno.nombre as nombreturno', 'seccion.nombre as nombreseccion', 'grado.nombre as nombregrado')
                ->join('maestro as maestro', 'detalle_asignacion.mdui', '=', 'maestro.mdui', 'full outer')
                ->join('detalle_grado as detalle_grado', 'detalle_asignacion.iddetallegrado', '=', 'detalle_grado.iddetallegrado', 'full outer')
                ->join('turno as turno', 'detalle_grado.idturno', '=', 'turno.idturno', 'full outer')
                ->join('seccion as seccion', 'detalle_grado.idseccion', '=', 'seccion.idseccion', 'full outer')
                ->join('grado as grado', 'detalle_grado.idgrado', '=', 'grado.idgrado', 'full outer')
                ->Where('detalle_asignacion.aniodetalleasignacion', '=', $query3)
                ->Where('maestro.mdui', '=', $mdui)
                ->where('detalle_asignacion.coordinador','=', 1)
                ->Where('detalle_grado.idturno', '=', 1)    //Matutino
                ->orderBy('detalle_grado.idgrado', 'asc')
                ->get();

          
           $asig_grav =DB::table('detalle_asignacion')
                ->select('detalle_asignacion.id_detalleasignacion','detalle_asignacion.mdui', 'detalle_asignacion.aniodetalleasignacion', 'maestro.nombre',
                'maestro.apellido','detalle_asignacion.iddetallegrado', 'detalle_grado.iddetallegrado', 'detalle_grado.idgrado', 'detalle_grado.idseccion',
                'detalle_grado.idturno', 'turno.nombre as nombreturno', 'seccion.nombre as nombreseccion', 'grado.nombre as nombregrado')
                ->join('maestro as maestro', 'detalle_asignacion.mdui', '=', 'maestro.mdui', 'full outer')
                ->join('detalle_grado as detalle_grado', 'detalle_asignacion.iddetallegrado', '=', 'detalle_grado.iddetallegrado', 'full outer')
                ->join('turno as turno', 'detalle_grado.idturno', '=', 'turno.idturno', 'full outer')
                ->join('seccion as seccion', 'detalle_grado.idseccion', '=', 'seccion.idseccion', 'full outer')
                ->join('grado as grado', 'detalle_grado.idgrado', '=', 'grado.idgrado', 'full outer')
                ->Where('detalle_asignacion.aniodetalleasignacion', '=', $query3)
                ->Where('maestro.mdui', '=', $mdui)
                ->where('detalle_asignacion.coordinador','=', 1)
                ->Where('detalle_grado.idturno', '=', 2)    //Matutino
                ->orderBy('detalle_grado.idgrado', 'asc')
                ->get();
           


            return view("faltas.index", ["date"=>$date,"mdui"=>$mdui,"asig_gram"=>$asig_gram,"asig_grav"=>$asig_grav,"usuarioactual"=>$usuarioactual]);
        }
    }   
    
     public function index2(Request $request)
    {      
        $usuarioactual=\Auth::user();

        if($request)
        {   
            //Se busca el docente registrado
            $id_user = $usuarioactual->id_usuario;
            $det_user = MaestroUser::where('id_usuario', $id_user)->first();
            $mdui = $det_user->mdui;
            
            $det_coor= DetalleAsignacion::where('mdui',$mdui)->first();
            $coor= $det_coor->coordinador;

            
            //Obtenemos el año presente
            $query3 = Carbon::now();
            $query3 = $query3->format('Y');

            //Obtenemos la fecha actual
            $date = Carbon::now(); 
            $date = $date->format('l jS \\of F Y h:i:s A');


           $asig_gram =DB::table('detalle_asignacion')
                ->select('detalle_asignacion.id_detalleasignacion','detalle_asignacion.mdui', 'detalle_asignacion.aniodetalleasignacion', 'maestro.nombre',
                'maestro.apellido','detalle_asignacion.iddetallegrado', 'detalle_grado.iddetallegrado', 'detalle_grado.idgrado', 'detalle_grado.idseccion',
                'detalle_grado.idturno', 'turno.nombre as nombreturno', 'seccion.nombre as nombreseccion', 'grado.nombre as nombregrado')
                ->join('maestro as maestro', 'detalle_asignacion.mdui', '=', 'maestro.mdui', 'full outer')
                ->join('detalle_grado as detalle_grado', 'detalle_asignacion.iddetallegrado', '=', 'detalle_grado.iddetallegrado', 'full outer')
                ->join('turno as turno', 'detalle_grado.idturno', '=', 'turno.idturno', 'full outer')
                ->join('seccion as seccion', 'detalle_grado.idseccion', '=', 'seccion.idseccion', 'full outer')
                ->join('grado as grado', 'detalle_grado.idgrado', '=', 'grado.idgrado', 'full outer')
                ->Where('detalle_asignacion.aniodetalleasignacion', '=', $query3)
                ->Where('maestro.mdui', '=', $mdui)
                ->where('detalle_asignacion.coordinador','=', 1)
                ->Where('detalle_grado.idturno', '=', 1)    //Matutino
                ->orderBy('detalle_grado.idgrado', 'asc')
                ->get();

          
           $asig_grav =DB::table('detalle_asignacion')
                ->select('detalle_asignacion.id_detalleasignacion','detalle_asignacion.mdui', 'detalle_asignacion.aniodetalleasignacion', 'maestro.nombre',
                'maestro.apellido','detalle_asignacion.iddetallegrado', 'detalle_grado.iddetallegrado', 'detalle_grado.idgrado', 'detalle_grado.idseccion',
                'detalle_grado.idturno', 'turno.nombre as nombreturno', 'seccion.nombre as nombreseccion', 'grado.nombre as nombregrado')
                ->join('maestro as maestro', 'detalle_asignacion.mdui', '=', 'maestro.mdui', 'full outer')
                ->join('detalle_grado as detalle_grado', 'detalle_asignacion.iddetallegrado', '=', 'detalle_grado.iddetallegrado', 'full outer')
                ->join('turno as turno', 'detalle_grado.idturno', '=', 'turno.idturno', 'full outer')
                ->join('seccion as seccion', 'detalle_grado.idseccion', '=', 'seccion.idseccion', 'full outer')
                ->join('grado as grado', 'detalle_grado.idgrado', '=', 'grado.idgrado', 'full outer')
                ->Where('detalle_asignacion.aniodetalleasignacion', '=', $query3)
                ->Where('maestro.mdui', '=', $mdui)
                ->where('detalle_asignacion.coordinador','=', 1)
                ->Where('detalle_grado.idturno', '=', 2)    //Matutino
                ->orderBy('detalle_grado.idgrado', 'asc')
                ->get();
           


            return view("faltas.consulta", ["date"=>$date,"mdui"=>$mdui,"asig_gram"=>$asig_gram,"asig_grav"=>$asig_grav,"usuarioactual"=>$usuarioactual]);
        }
    } 

    public function show(Request $request, $a1)
    {
        $usuarioactual=\Auth::user();
        if ($request) {

            $id=$a1;
           

            //Se procede a buscar la  asignacion dado el id_asignacion
           
            $detalle_asignacion = DetalleAsignacion::where('id_detalleasignacion', $id)->first();

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
                ->select('estudiante.nie','estudiante.nombre','estudiante.apellido','estudiante.nie','matricula.fechamatricula','matricula.iddetallegrado','matricula.id_matricula')
                ->join('matricula as matricula','estudiante.nie','=','matricula.nie','full outer')
                //->join('falta as falta','matricula.id_matricula','=','falta.id_matricula','full outer')
                ->join('detalle_grado as detalle_grado','matricula.iddetallegrado','=','detalle_grado.iddetallegrado','full outer')
                ->where('matricula.iddetallegrado','=',$iddetallegrado)
                ->whereYear('matricula.fechamatricula','=',$query3)
                ->where('estudiante.apellido','LIKE','%'.$query.'%')
                ->orderby('estudiante.apellido')
                ->get();
           
          // $ultimafalta= DB::table('falta')->select('falta.fecha_falta')
           //->join('matricula as matricula','falta.id_matricula','=','matricula.id_matricula','full outer')
           //->join('estudiante as estudiante','matricula.nie','=','estudiante.nie', 'full outer')
           //->where('matricula.nie','=', 'estudiante.nie')
           //->latest('falta.fecha_falta')->get();
            
         
           //$ultimafalta= DB::table('falta')->select('falta.fecha_falta')
           //->join('matricula as matricula','falta.id_matricula','=','matricula.id_matricula','full outer')
           //->where('falta.id_matricula','=','matricula.id_matricula')
           //->latest('falta.fecha_falta')->get();
           return view('faltas.lista.estudiante',["id"=>$id,"searchText"=>$query ,"date"=>$date, "nGrado"=>$nGrado, "nSeccion"=>$nSeccion, "nTurno"=>$nTurno,"est"=>$est, "usuarioactual"=>$usuarioactual]);
          
        

            if ($est==null) {

                    Session::flash('message',"No hay Estudiantes Inscritos en este Curso");
                    return view("faltas.lista.estudiante",["id"=>$id,"date"=>$date, "nGrado"=>$nGrado, "nSeccion"=>$nSeccion, "nTurno"=>$nTurno, "est"=>$est,"usuarioactual"=>$usuarioactual]);
        
                }

            //return view('faltas.lista.estudiante',["searchText"=>$query, "date"=>$date, "nGrado"=>$nGrado, "nSeccion"=>$nSeccion, "nTurno"=>$nTurno,"est"=>$est,"ultimafalta"=>$ultimafalta, "usuarioactual"=>$usuarioactual]);
        }
         
    }

    public function show2(Request $request, $a1)
    {
        
        if ($request) {
            $usuarioactual=\Auth::user();
            $id=$a1;
           

            //Se procede a buscar la  asignacion dado el id_asignacion
           
            $detalle_asignacion = DetalleAsignacion::where('id_detalleasignacion', $id)->first();

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
            $hoy= Carbon::now();
            $hoy= $hoy->format('Y-m-d');
            //$query3 = 2017;
            $query = $request->get('searchText');
            
            $est = DB::table('falta')
            ->select('estudiante.nie','estudiante.nombre','estudiante.apellido','estudiante.nie','matricula.fechamatricula','matricula.iddetallegrado','matricula.id_matricula','falta.fecha_falta'
            ,'falta.id_falta','falta.detalle_falta','falta.permiso')
            ->join('matricula as matricula','falta.id_matricula','=','matricula.id_matricula','full outer')
            ->join('estudiante as estudiante','matricula.nie','=','estudiante.nie','full outer')
            ->join('detalle_grado as detalle_grado','matricula.iddetallegrado','=','detalle_grado.iddetallegrado','full outer')
            ->where('matricula.iddetallegrado','=',$iddetallegrado)
            ->whereYear('matricula.fechamatricula','=',$query3)
            ->where('falta.fecha_falta','=',$query)
            ->latest('falta.fecha_falta')
            ->get();
            

            if ($query==null) {
               
                                
                $est = DB::table('falta')
                ->select('estudiante.nie','estudiante.nombre','estudiante.apellido','estudiante.nie','matricula.fechamatricula','matricula.iddetallegrado','matricula.id_matricula','falta.fecha_falta'
                ,'falta.id_falta','falta.detalle_falta','falta.permiso')
                ->join('matricula as matricula','falta.id_matricula','=','matricula.id_matricula','full outer')
                ->join('estudiante as estudiante','matricula.nie','=','estudiante.nie','full outer')
                ->join('detalle_grado as detalle_grado','matricula.iddetallegrado','=','detalle_grado.iddetallegrado','full outer')
                ->where('matricula.iddetallegrado','=',$iddetallegrado)
                ->whereYear('matricula.fechamatricula','=',$query3)
                ->where('falta.fecha_falta','=',$hoy)
                ->latest('falta.fecha_falta')
                ->get();

                if($est==null){
                     Session::flash('message',"No hay faltas para el dia de hoy. 
                Consulte otra fecha.");
                }
                
            }
                
               
            return view('faltas.lista.inasistencia',["id"=>$id, "searchText"=>$query, "date"=>$date, "nGrado"=>$nGrado, "nSeccion"=>$nSeccion,"nTurno"=>$nTurno,"est"=>$est, "usuarioactual"=>$usuarioactual]);
            
        }
         
    }


    public function create()
    {
        
    }

    public function store(FaltaRequest $request)
    {
      
          $faltas=Falta::all(); 
          $falta= new Falta; 
          $falta->fecha_falta= $request->get('fechafalta');
          $falta->detalle_falta=$request->get('detallefalta');
          if (Input::get('conpermiso')) {
            $falta->permiso='SI';       // El usuario marcó el checkbox 
        } else {
            $falta->permiso='NO';       // El usuario NO marcó el chechbox
        }
          $falta->id_matricula=$request->get('idmatricula');
          $falta->save();
          
          Session::flash('message',"Inasistencia agregada correctamente");
          return back();
     
    }


    public function edit($id)
    {
        $usuarioactual=\Auth::user();

        
    return view("faltas.edit", ["usuarioactual"=>$usuarioactual]);


         
    }

    public function update(Request $request)
    {
        $usuarioactual=\Auth::user();
        $id=$id_falta;
                $falta = Falta::findOrFail($id);
                $falta->fecha_falta= $request->get('fechafalta');
                $falta->detalle_falta=$request->get('detallefalta');
                if (Input::get('conpermiso')) {
                  $falta->permiso='SI';       // El usuario marcó el checkbox 
              } else {
                  $falta->permiso='NO';       // El usuario NO marcó el chechbox
              }
                $falta->id_matricula=$request->get('idmatricula');
                $falta->update();

                Session::flash('message',"Inasistencia Editada correctamente");
                return back();


    }

    public function destroy($id)
    {
        $usuarioactual=\Auth::user();
        
        Falta::destroy($id);
        Session::flash('message',"Inasistencia Eliminada correctamente");
        return back();

    }

    
}
