<?php

namespace Escuela\Http\Controllers;

use Illuminate\Http\Request;

use Escuela\Http\Requests;

use Escuela\Matricula;
use Escuela\TipoResponsable;
use Escuela\Responsable;
use Escuela\Turno;
use Escuela\Seccion;
use Escuela\Grado;
use Escuela\Estudiante;
use Escuela\PartidaNacimiento;
use Escuela\DetalleGrado;
use Escuela\DetallePariente;
use Escuela\Anio;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;


use Escuela\Http\Requests\MatriculaFormRequest;
use Escuela\Http\Requests\Matricula3FormRequest;
use Escuela\Http\Requests\DetalleParienteFormRequest;
use Escuela\Http\Requests\ResponsableFormRequest;

use Escuela\Http\Requests\TipoResponsableFormRequest;
use Escuela\Http\Requests\GradoFormRequest;
use Escuela\Http\Requests\DetalleGradoFormRequest;
use Escuela\Http\Requests\SeccionFormRequest;
use Escuela\Http\Requests\TurnoFormRequest;

#use Escuela\Http\Requests\EstudianteFormRequest;
#use Escuela\Http\Requests\PartidaNacimientoFormFormRequest;

use DB;


use Carbon\Carbon; //Para la zona fecha horaria
use Response;
use Illuminate\Support\Collection;  //Contienen los metodos a utilizar
use Illuminate\Database\Connection;

class Matricula2Controller extends Controller
{
    public function __construct()	//para validar
    {
        #$this->middleware('auth');
    }

    public function index(Request $request)
    {
        $usuarioactual=\Auth::user();

    	if($request)
        {
            $query = trim($request->get('searchText'));
            $query2 = $request->get('searchText');

            if (!is_numeric($query2)) {
                $query2 = 0;
            }

            $matriculas = DB::table('matricula as ma')
            ->select('ma.id_matricula','ma.fechamatricula','al.nombre','al.apellido', 'al.sexo', 'al.nie','ma.fotografia','ma.estado')
            ->join('estudiante as al','ma.nie','=','al.nie')
            ->where('al.nombre','LIKE','%'.$query.'%')
            ->orwhere('al.apellido','LIKE','%'.$query.'%')
            ->orwhere('al.sexo','LIKE','%'.$query.'%')
            ->orwhere('al.nie','=',$query2)
            ->orwhere('ma.estado','LIKE','%'.$query.'%')
            ->orderBy('ma.id_matricula','desc')
            ->paginate(6);
            return view('expediente.matricula2.index',["matriculas"=>$matriculas,"searchText"=>$query, "usuarioactual"=>$usuarioactual]);
        }

    }


    public function edit($id)
    {
        $usuarioactual=\Auth::user();

    	$matricula = Matricula::findOrFail($id);

        $tipos = TipoResponsable::all();

        $anios = Anio::all();

        $date = Carbon::now();
        $endDate = $date->addYear();
        $endYear = $endDate->year; 

        $anio =  $matricula->fechamatricula;
        $anio =  strtotime($anio);
        $anio = date('Y',$anio);

        $grados = Grado::all();

        $secciones = Seccion::all();

        $turnos = Turno::all();

        $dg = $matricula->iddetallegrado;
        $detalleg = DetalleGrado::findOrFail($dg);

        $nie = $matricula->nie;

        $estudiante = Estudiante::where('nie',$nie)->first();

        $detallepartida = PartidaNacimiento::where('nie',$nie)->first();

        //Se obtienen los datos de la madre, padre, contacto
        $detalleM = Responsable::where('nie',$nie)->where('idresponsable',1)->first();
        $detalleP = Responsable::where('nie',$nie)->where('idresponsable',2)->first();
        $detalleC = Responsable::where('nie',$nie)->where('idresponsable',3)->first();

        $matriculas = DB::table('matricula')->get();

    	return view("expediente.matricula2.edit",["matricula"=>$matricula, "detalleg"=>$detalleg, "anios"=>$anios, "anio"=>$anio, "grados"=>$grados, "secciones"=>$secciones, "turnos"=>$turnos, "matriculas"=>$matriculas, "estudiante"=>$estudiante, "endYear"=>$endYear, "detallepartida"=>$detallepartida, "detalleM"=>$detalleM, "detalleP"=>$detalleP, "detalleC"=>$detalleC, "usuarioactual"=>$usuarioactual]);
    }


    public function update(Matricula3FormRequest $request, $id)
    {	
        $usuarioactual=\Auth::user();

    	try{
            DB::beginTransaction();

            $matricula = Matricula::findOrFail($id);
    		$matricula2 = new Matricula;

    	   //TODO LO DEL METODO STORE
          // $matricula2->fechamatricula=$request->get('fechamatricula');

            $fechaFormato = "01/01/".$request->get('fechamatricula');
            $time = strtotime($fechaFormato);
            $matricula2->fechamatricula = date('Y-m-d',$time);

            //Obtenemos la fecha de matricula
            $matricula2->fechareal = $request->get('fechareal');

            //Presenta partida
            if (Input::get('presentapartida')) {
                $matricula2->presentapartida='SI';       // El usuario marcó el checkbox 
            } else {
                $matricula2->presentapartida='NO';       // El usuario NO marcó el chechbox
            }

            //Certificado de promocion
            if (Input::get('certificadoprom')) {
                $matricula2->certificadoprom='SI';       // El usuario marcó el checkbox 
            } else {
                $matricula2->certificadoprom='NO';       // El usuario NO marcó el chechbox
            }

            //Presenta Fotografias
            if (Input::get('presentafotos')) {
                $matricula2->presentafotos='SI';       // El usuario marcó el checkbox 
            } else {
                $matricula2->presentafotos='NO';       // El usuario NO marcó el chechbox
            }    

            //Constancia de buena conducta
            if (Input::get('constanciaconducta')) {
                $matricula2->constanciaconducta='SI';       // El usuario marcó el checkbox 
            } else {
                $matricula2->constanciaconducta='NO';       // El usuario NO marcó el chechbox
            }

            //EDUCACION INICIAL
            if (Input::get('educacioninicial')) {
                $matricula2->educacioninicial='SI';       // El usuario marcó el checkbox 
            } else {
                $matricula2->educacioninicial='NO';       // El usuario NO marcó el chechbox
            }

            //REPITE GRADO
            if (Input::get('repitegrado')) {
                $matricula2->repitegrado='SI';       // El usuario marcó el checkbox 
            } else {
                $matricula2->repitegrado='NO';       // El usuario NO marcó el chechbox
            }

            $matricula2->estado='Activo';
            $matricula2->ceprevio=$request->get('cePrevio');
            $matricula2->telefonoce=$request->get('telefonoce');
            $matricula2->vivecon=$request->get('vivecon');

            $fechaentrega = $request->get('fechaentrega');
            if ($fechaentrega !="") {
                $matricula2->fechaentrega=$request->get('fechaentrega');
            }


            //Se valida que no sea null el campo fotografía

            if(Input::hasFile('fotografia')){
                $file = Input::file('fotografia');
                $file -> move(public_path().'/imagenes/alumnos/', $file->getClientOriginalName());
                $matricula2->fotografia = $file->getClientOriginalName();   
            }else{
                $matricula2->fotografia = $matricula->fotografia;
            }


            

           //Ahora se procede al tratamiento de DetalleGrado

            $idgrado = $request->get('idgrado');
            $idseccion = $request->get('idseccion');
            $idturno = $request->get('idturno');

            $detalleGrado = DetalleGrado::where('idgrado','=',$idgrado)->where('idseccion','=',$idseccion)
            ->where('idturno','=',$idturno)->first();

            $ban = 0;


            //Se hace la fk a la tabla matricula.

            if(!is_null($detalleGrado)){
                $matricula2->iddetallegrado = $detalleGrado->iddetallegrado;
                $ban = 1;
            }

            //Validacion de combinacion Grado, Seccion, Turno

            $grado = Grado::where('idgrado','=',$idgrado)->first();
            $seccion = Seccion::where('idseccion','=',$idseccion)->first();
            $turno = Turno::where('idturno','=',$idturno)->first();

            if($ban == 0) {
                Session::flash('message', '"'.$grado->nombre.'"'.'"'.$seccion->nombre.'"'.'"'.$turno->nombre.'"'.' Esa Asignación no exite, Por favor configure esa combinación');
                return Redirect::to('expediente/matricula');
            }else{
                Session::flash('create', ''.' Matricula Guardada Correctamente');
                 }

            //Ahora de procede al tratamiento de Estudiante.
            $nie = $matricula->nie;


            //validación de una matricula por año
            $fechaMatricula = "01/01/".$request->get('fechamatricula');
            $fechaMatricula = date("Y", strtotime($fechaMatricula));  // debe estar en formato TIMESTAMP
            $consultaE = Matricula::where('nie','=',$nie)->whereYear('fechamatricula','=',$fechaMatricula)->first();

            if(!is_null($consultaE)){
                $detalleG = DetalleGrado::where('iddetallegrado','=',$consultaE->iddetallegrado)->first();
                $grado = Grado::where('idgrado','=',$detalleG->idgrado)->first();
                $seccion = Seccion::where('idseccion','=',$detalleG->idseccion)->first();
                $turno = Turno::where('idturno','=',$detalleG->idturno)->first();

                Session::flash('found', ''.' El estudiante ya se encuentra matriculado en el presente año '.$fechaMatricula.'. En el curso "'.$grado->nombre.'" '.' "'.$seccion->nombre.'" '.' "'.$turno->nombre.'"');
                return Redirect::to('expediente/matricula2');
            }


            //Se hace la fk a la tabla estudiante, una vez creada la partida.

            $estudiante = Estudiante::where('nie',$nie)->first();        //Obtengo el estudiante
            
            
            //Hizo Kinder
            $vt0 = $request->get('hizokinder');
            if ($vt0=='0') {
                $matricula2->hizokinder = 'NO';
            }
            else{
                $matricula2->hizokinder = 'SI';
            }

            
            //discapacidad  del estudiante
            $vt2 = $request->get('discapacidad');
            if ($vt2=='0') {
                $estudiante->discapacidad = 'NO';
            }
            else{
                $estudiante->discapacidad = 'SI';
            }
            $estudiante ->domicilio = $request -> get('domicilio');
            $estudiante ->enfermedad = $request -> get('enfermedad');

            //Area geografica de residencia
            $vt3 = $request->get('zonahabitacion');
            if ($vt3=='0') {
                $estudiante->zonahabitacion = 'Rural';
            }
            else{
                $estudiante->zonahabitacion = 'Urbano';
            }
            //Autorizacion de vacuna
            $vt4 = $request->get('autorizavacuna');
            if ($vt4=='0') {
                $estudiante->autorizavacuna = 'NO';
            }
            else{
                $estudiante->autorizavacuna = 'SI';
            }


            $estudiante -> update();


            //Se procede a guardar datos de la Madre

            $madre = Responsable::where('nie',$nie)->where('idresponsable',1)->first();
            ######$madre->idresponsable=1;
            $madre->nie=$estudiante->nie;
            $madre->nombre =$request->get('nombre2');
            $madre->apellido =$request->get('apellido2');
            $madre->ocupacion =$request->get('ocupacion');
            $madre->lugardetrabajo =$request->get('lugardetrabajo');
            $madre->telefono =$request->get('telefono');
            $madre->dui =$request->get('dui');
            $madre->update();

            
            //Se procede a guardar datos de la Padre
            $padre = Responsable::where('nie',$nie)->where('idresponsable',2)->first();
            ####$padre->idresponsable=2;
            $padre->nie=$estudiante->nie;
            $padre->nombre =$request->get('nombre3');
            $padre->apellido =$request->get('apellido3');
            $padre->ocupacion =$request->get('ocupacion3');
            $padre->lugardetrabajo =$request->get('lugardetrabajo3');
            $padre->telefono =$request->get('telefono3');
            $padre->dui =$request->get('dui3');
            $padre->update();


            //Se procede a guardar datos del Contacto de Emergencia
            $contacto = Responsable::where('nie',$nie)->where('idresponsable',3)->first();
            ###$contacto->idresponsable=3;
            $contacto->nie=$estudiante->nie;
            $contacto->nombre =$request->get('nombre4');
            $contacto->apellido =$request->get('apellido4');
            $contacto->telefono =$request->get('telefono4');
            $contacto->update();
        
        
            //Se procede a guardar la matricula
            $matricula2->nie = $estudiante->nie;
             
    	    $matricula2->save();

            Session::flash('create', ''.' Matricula Guardada Correctamente');

            DB::commit();

        }catch(\Exception $e)
        {
            DB::rollback();
        }

    	return Redirect::to('expediente/matricula');
    }


    public function destroy($id)
    {
        $usuarioactual=\Auth::user();
        
    	$matricula2=Matricula::findOrFail($id);

        if ($matricula2->estado == 'Activo') {
            $matricula2->estado = 'Inactivo';
        }else{
            $matricula2->estado = 'Activo';
        }
    	
    	$matricula2->update();
    	return Redirect::to('expediente/matricula2');
    }

     public function show($id)      //Para mostrar
    {
        $usuarioactual=\Auth::user();

        $matricula = Matricula::findOrFail($id);

        //Encontramos el  detalle de la matricula
        $dg = $matricula->iddetallegrado;
        $detalleg = DetalleGrado::findOrFail($dg);

        $idgrado= $detalleg->idgrado;
        $idseccion=$detalleg->idseccion;
        $idturno=$detalleg->idturno;

        $grado = Grado::findOrFail($idgrado);
        $seccion = Seccion::findOrFail($idseccion);
        $turno = Turno::findOrFail($idturno);


        $nie = $matricula->nie;

        $estudiante = Estudiante::where('nie',$nie)->first();
        $detallepartida = PartidaNacimiento::where('nie',$nie)->first();

        //Se obtienen los datos de la madre, padre, contacto
        $detalleM = Responsable::where('nie',$nie)->where('idresponsable',1)->first();
        $detalleP = Responsable::where('nie',$nie)->where('idresponsable',2)->first();
        $detalleC = Responsable::where('nie',$nie)->where('idresponsable',3)->first();

        $parientes = DB::table('detalle_pariente as dp')
            ->where('dp.nie',$nie)
            ->orderBy('dp.id_detalle','desc')
            ->get();

        $count = DetallePariente::where('nie', $nie)->count();


        $parientess = DB::table('detalle_pariente as pariente')
        ->select('estudiante.nombre','estudiante.apellido','estudiante.nie','pariente.id_detalle','pariente.parentesco','matricula.fotografia', 'matricula.id_matricula','grado.nombre as grado','seccion.nombre as seccion','turno.nombre as turno')
        ->join('estudiante as estudiante','pariente.nie','=','estudiante.nie','full outer')
        ->join('matricula as matricula','pariente.nie','=','matricula.nie','full outer')
        ->join('detalle_grado as detalle_grado','matricula.iddetallegrado','=','detalle_grado.iddetallegrado','full outer')
        ->join('grado as grado','detalle_grado.idgrado','=','grado.idgrado','full outer')
        ->join('seccion as seccion','detalle_grado.idseccion','=','seccion.idseccion','full outer')
        ->join('turno as turno','detalle_grado.idturno','=','turno.idturno','full outer')
        ->where('pariente.id_matricula','=',$id)
        ->orderby('estudiante.apellido','asc')
        ->get();

        setlocale(LC_ALL,"es_ES");

        $fechaa = $matricula->fechamatricula; 
        $fecha_explode = explode("-",$fechaa);
        $anio = $fecha_explode[0];
        $mes = $fecha_explode[1];
        $dia = $fecha_explode[2];

        $hoy = date("d-MM-Y");

        $hoi = explode("-", $hoy);

        $aniohoy = $hoi[2];
        setlocale(LC_TIME, "spanish");
        $meshoy = ucfirst(strftime("%B"));
        $diahoy = $hoi[0];

        $edad=$this -> CalculaEdad($estudiante->fechadenacimiento);



        $vistaurl="matriculalguien";
        return $this ->crearPDF($vistaurl,$matricula,$grado,$seccion,$turno,$estudiante,$detallepartida,$detalleM, $detalleP, $detalleC, $parientess, $usuarioactual,$anio,$aniohoy,$meshoy,$diahoy,$edad);
    }

    public function crearPDF($vistaurl,$matricula,$grado,$seccion,$turno,$estudiante,$detallepartida,$detalleM, $detalleP, $detalleC, $parientess, $usuarioactual,$anio,$aniohoy,$meshoy,$diahoy,$edad)
    {
        $view=\View::make($vistaurl, compact('matricula','grado','seccion','turno','estudiante','detallepartida','detalleM', 'detalleP', 'detalleC', 'parientess', 'usuarioactual','anio','aniohoy','meshoy','diahoy','edad'))->render();

        $pdf =\App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        return $pdf->stream($estudiante->apellido.$estudiante->nombre.$turno->nombre.$grado->nombre.$seccion->nombre.$estudiante->nie);

    } 

    public function CalculaEdad( $fecha ) {
        list($Y,$m,$d) = explode("-",$fecha);
        return( date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y );
    }

}