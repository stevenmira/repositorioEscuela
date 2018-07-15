<?php

namespace Escuela\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Escuela\Http\Requests;
use Storage;
use Escuela\MaestroUser;

use Escuela\Asignacion;
use Escuela\DetalleAsignacion;
use Escuela\DetalleGrado;
use Escuela\Grado;
use Escuela\Seccion;
use Escuela\Turno;
use Escuela\User;

use Escuela\Http\Requests\DetalleAcademicoFormRequest;
use Illuminate\Support\Facades\Session;

use Carbon\Carbon; //Para la zona fecha horaria

use DB;



class AcademicosController extends Controller
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
           


            return view("academico.index", ["date"=>$date,"mdui"=>$mdui,"asig_gram"=>$asig_gram,"asig_grav"=>$asig_grav,"usuarioactual"=>$usuarioactual]);
            
        }
    }

    public function getLista(Request $request, $a1)
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
                ->select('estudiante.nie','estudiante.nombre','estudiante.apellido','estudiante.nie','matricula.fechamatricula')
                ->join('matricula as matricula','estudiante.nie','=','matricula.nie','full outer')
                ->join('detalle_grado as detalle_grado','matricula.iddetallegrado','=','detalle_grado.iddetallegrado','full outer')
                ->where('matricula.iddetallegrado','=',$iddetallegrado)
                ->whereYear('matricula.fechamatricula','=',$query3)
                ->where('estudiante.apellido','LIKE','%'.$query.'%')
                ->orderby('estudiante.apellido','asc')
                ->get();

           

            return view('academico.lista.estudiante',["searchText"=>$query, "date"=>$date, "nGrado"=>$nGrado, "nSeccion"=>$nSeccion, "nTurno"=>$nTurno,"est"=>$est, "usuarioactual"=>$usuarioactual]);
        }
         
    }

    public function show($id)
    {
        
    }

    public function create()
    {
    }

    public function store(DetalleAcademicoFormRequest $request)
    {
    
        
    }


    public function edit($id)
    {
      
         
    }

    public function update()
    {
         
    }
public function destroy($nie)
    {
        
    }
}