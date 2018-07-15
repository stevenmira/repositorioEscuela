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
use Escuela\Http\Requests\Matricula2FormRequest;
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

class MatriculaController extends Controller
{
    public function __construct()	//para validar
    {
        #$this->middleware('auth');
    }

    public function index(Request $request)
    {      
       if($request)
        {
            $usuarioactual=\Auth::user();

            //Obtenemos el año presente
            $actual = Carbon::now();
            $actual = $actual->format('Y');

            //los query capturan los criterios de busqueda que son enviados desde el search.blade de estudiante
            
            $query3 = $request->get('valor');   //Se obtiene el año seleccionado

            if ($query3==null) {
                //Si está nulo dejamos el año actual por defecto
                $query3 = $actual;
            }

            $query5 = $request->get('idgrado');
            $query6 = $request->get('idseccion');
            $query7 = $request->get('idturno');


            //catalogo de años
            $anioDefecto = Anio::orderBy('anios.idanio','asc'); 

            //catalogos de grados,secciones y turnos
            $grados = Grado::where('grado.estado','=','Activo')->orderBy('grado.idgrado','asc');   

            $secciones = Seccion::where('seccion.estado','=','Activo')->orderBy('seccion.idseccion','asc');

            $turnos = Turno::where('turno.estado','=','Activo')->orderBy('turno.idturno','asc');

            //verifica si la combinacion de grado, turno y seccion existen
            $consulta2=DetalleGrado::where('idgrado', $query5)->where('idseccion', $query6)->where('idturno', $query7)->first();

            if ($consulta2==null) {
                $numbA = "";
                $nombG = "";
                $nombS = "";
                $nombT = "";
               // Session::flash('M2',"opss! no tenemos resultados con el grado ");
            }else{
                $anio = Anio::where('valor','=',$query3)->first();
                $numbA = $anio->valor;
                $grado = Grado::where('idgrado','=',$query5)->first();
                $nombG = $grado->nombre;
                $seccion = Seccion::where('idseccion','=',$query6)->first();
                $nombS = $seccion->nombre;
                $turno = Turno::where('idturno','=',$query7)->first();
                $nombT = $turno->nombre;
            }
              
            //la consulta es guardada en la variable $est recordar que join
            //genera una nueva tabla con todos las columnas especificada en el select
            

            $est = DB::table('estudiante')
            ->select('estudiante.nombre','estudiante.apellido','estudiante.nie','matricula.nie','grado.nombre as nombreGrado','seccion.nombre as nombreSeccion','turno.nombre as nombreTurno','matricula.fechamatricula', 'matricula.id_matricula', 'matricula.fotografia')
            ->join('matricula as matricula','estudiante.nie','=','matricula.nie','full outer')
            ->join('detalle_grado as detalle_grado','matricula.iddetallegrado','=','detalle_grado.iddetallegrado','full outer')
            ->join('grado as grado','detalle_grado.idgrado','=','grado.idgrado','full outer')
            ->join('seccion as seccion','detalle_grado.idseccion','=','seccion.idseccion','full outer')
            ->join('turno as turno','detalle_grado.idturno','=','turno.idturno','full outer')
            ->Where('seccion.idseccion','=',$query6)
            ->Where('grado.idgrado','=',$query5)
            ->Where('turno.idturno','=',$query7)
            ->whereYear('matricula.fechamatricula','=',$query3)
            ->where('matricula.estado','=','Activo')
            ->orderby('estudiante.apellido','asc')
            ->get();  

             
           if ($est==null) {

                /*$nGrado=Grado::where('idgrado','=',1)->first();
                $nTurno=Turno::where('idturno','=',1)->first();
                $nSeccion=Seccion::where('idseccion','=',1)->first();*/

                Session::flash('M1',"opss! no tenemos resultados con el curso");
            }
                   
             
          
            //se retorna el array de resultados a la vista en una variable "estudiantes" y ademas los catalogos de turno,seccion y grado

            return view('expediente.matricula.index',["estudiantes"=>$est, "anios"=>$anioDefecto, "grados"=>$grados,"searchYear"=>$query3, "secciones"=>$secciones, "turnos"=>$turnos,"usuarioactual"=>$usuarioactual
            ,"seccion"=>$query6,"grado"=>$query5,"turno"=>$query7, "numbA"=>$numbA, "nombG"=>$nombG, "nombS"=>$nombS, "nombT"=>$nombT, "actual"=>$actual]);

        }

    }


    public function create()
    {
        $usuarioactual=\Auth::user();

    	$tipos = DB::table('tipo_responsable')->get();


        $grados = Grado::where('grado.estado','=','Activo')->orderBy('grado.idgrado','asc');   

        $secciones = Seccion::where('seccion.estado','=','Activo')->orderBy('seccion.idseccion','asc');

        $turnos = Turno::where('turno.estado','=','Activo')->orderBy('turno.idturno','asc');

        $anios = Anio::all();

        $date = Carbon::now();
        $endDate = $date->addYear();
        $endYear = $endDate->year; 


    	return view("expediente.matricula.create",["tipos"=>$tipos, "grados"=>$grados, "secciones"=>$secciones, "turnos"=>$turnos, "anios"=>$anios, "endYear"=>$endYear, "usuarioactual"=>$usuarioactual]);
    }



    public function store (MatriculaFormRequest $request)
    {
        $usuarioactual=\Auth::user();

        try{
                DB::beginTransaction();

        		$matricula = new Matricula;
        		//$matricula->fechamatricula=$request->get('fechamatricula');

                $fechaFormato = "01/01/".$request->get('fechamatricula');
                $time = strtotime($fechaFormato);
                $matricula->fechamatricula = date('Y-m-d',$time);

                //Obtenemos la fecha de matricula
                $matricula->fechareal = $request->get('fechareal');



                //Presenta partida
                if (Input::get('presentapartida')) {
                    $matricula->presentapartida='SI';       // El usuario marcó el checkbox 
                } else {
                    $matricula->presentapartida='NO';       // El usuario NO marcó el chechbox
                }

                //Certificado de promocion
                if (Input::get('certificadoprom')) {
                    $matricula->certificadoprom='SI';       // El usuario marcó el checkbox 
                } else {
                    $matricula->certificadoprom='NO';       // El usuario NO marcó el chechbox
                }

                //Presenta Fotografias
                if (Input::get('presentafotos')) {
                    $matricula->presentafotos='SI';       // El usuario marcó el checkbox 
                } else {
                    $matricula->presentafotos='NO';       // El usuario NO marcó el chechbox
                }    

                //Constancia de buena conducta
                if (Input::get('constanciaconducta')) {
                    $matricula->constanciaconducta='SI';       // El usuario marcó el checkbox 
                } else {
                    $matricula->constanciaconducta='NO';       // El usuario NO marcó el chechbox
                }

                //EDUCACION INICIAL
                if (Input::get('educacioninicial')) {
                    $matricula->educacioninicial='SI';       // El usuario marcó el checkbox 
                } else {
                    $matricula->educacioninicial='NO';       // El usuario NO marcó el chechbox
                }

                //REPITE GRADO
                if (Input::get('repitegrado')) {
                    $matricula->repitegrado='SI';       // El usuario marcó el checkbox 
                } else {
                    $matricula->repitegrado='NO';       // El usuario NO marcó el chechbox
                }

        		$matricula->estado='Activo';
        		$matricula->ceprevio=$request->get('cePrevio');
                $matricula->telefonoce=$request->get('telefonoce');
                $matricula->vivecon=$request->get('vivecon');

                $fechaentrega = $request->get('fechaentrega');
                if ($fechaentrega !="") {
                    $matricula->fechaentrega=$request->get('fechaentrega');
                }

        		if(Input::hasFile('fotografia')){
        		$file = Input::file('fotografia');
        		$file -> move(public_path().'/imagenes/alumnos/', $file->getClientOriginalName());
        		$matricula->fotografia = $file->getClientOriginalName();
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
                    $matricula->iddetallegrado = $detalleGrado->iddetallegrado;
                    $ban = 1;
                }

                //Validacion de combinacion Grado, Seccion, Turno

                $grado = Grado::where('idgrado','=',$idgrado)->first();
                $seccion = Seccion::where('idseccion','=',$idseccion)->first();
                $turno = Turno::where('idturno','=',$idturno)->first();

                if($ban == 0) {
                    Session::flash('message', '"'.$grado->nombre.'"'.'"'.$seccion->nombre.'"'.'"'.$turno->nombre.'"'.' Esa Asignación no exite, Por favor configure esa combinación');
                    return Redirect::to('expediente/matricula');
                }


        		//Ahora de procede al tratamiento de Estudiante.

        		$partida = new PartidaNacimiento;
        		#$partida -> nie = 
                $partida->partida = $request -> get('partida');
        		$partida->folio = $request -> get('folio');
        		$partida->libro = $request -> get('libro');
        		$partida -> save();

        		//Se hace la fk a la tabla estudiante, una vez creada la partida.

        		$estudiante = new Estudiante;
        		$estudiante->nie = $request -> get('nie');
        		$estudiante->id_partida = $partida->id_partida;
        		$estudiante->nombre = $request -> get('nombre');
        		$estudiante->apellido = $request -> get('apellido');
        		$estudiante->fechadenacimiento = $request -> get('fechadenacimiento');

                //Hizo Kinder
                $vt0 = $request->get('hizokinder');
                if ($vt0==0) {
                    $matricula->hizokinder = 'NO';
                }
                else{
                    $matricula->hizokinder = 'SI';
                }

                //Sexo del estudiante
                $vt1 = $request->get('sexo');
                if ($vt1==0) {
                    $estudiante->sexo = 'F';
                }
                else{
                    $estudiante->sexo = 'M';
                }
                
                //discapacidad  del estudiante
                $vt2 = $request->get('discapacidad');
                if ($vt2==0) {
                    $estudiante->discapacidad = 'N0';
                }
                else{
                    $estudiante->discapacidad = 'SI';
                }
                $estudiante ->domicilio = $request -> get('domicilio');
                $estudiante ->enfermedad = $request -> get('enfermedad');

                //Area geografica de residencia
                $vt3 = $request->get('zonahabitacion');
                if ($vt3==0) {
                    $estudiante->zonahabitacion = 'Urbano';
                }
                else{
                    $estudiante->zonahabitacion = 'Rural';
                }
                //Autorizacion de vacuna
                $vt4 = $request->get('autorizavacuna');
                if ($vt4==0) {
                    $estudiante->autorizavacuna = 'NO';
                }
                else{
                    $estudiante->autorizavacuna = 'SI';
                }

        		$estudiante ->estado = 'Activo';
        		$estudiante -> save();

        		//Se procede a guardar el nie
        		$partida->nie = $estudiante->nie;
        		$partida->save();

        		//Se procede a guardar datos de la Madre

        		$madre = new Responsable;
        		$madre->idresponsable=1;
        		$madre->nie=$estudiante->nie;
        		$madre->nombre =$request->get('nombre2');
        		$madre->apellido =$request->get('apellido2');
        		$madre->ocupacion =$request->get('ocupacion');
        		$madre->lugardetrabajo =$request->get('lugardetrabajo');
        		$madre->telefono =$request->get('telefono');
        		$madre->dui =$request->get('dui');
        		$madre->save();

                
                //Se procede a guardar datos de la Padre
                $padre = new Responsable;
                $padre->idresponsable=2;
                $padre->nie=$estudiante->nie;
                $padre->nombre =$request->get('nombre3');
                $padre->apellido =$request->get('apellido3');
                $padre->ocupacion =$request->get('ocupacion3');
                $padre->lugardetrabajo =$request->get('lugardetrabajo3');
                $padre->telefono =$request->get('telefono3');
                $padre->dui =$request->get('dui3');
                $padre->save();


                //Se procede a guardar datos del Contacto de Emergencia
                $contacto = new Responsable;
                $contacto->idresponsable=3;
                $contacto->nie=$estudiante->nie;
                $contacto->nombre =$request->get('nombre4');
                $contacto->apellido =$request->get('apellido4');
                $contacto->telefono =$request->get('telefono4');
                $contacto->save();
            
            
        		//Se procede a guardar la matricula
        		$matricula->nie = $estudiante->nie;
        		

                $matricula -> save();

                if (!is_null($matricula)) {
                    Session::flash('create', ''.' Matricula Guardada Correctamente');
                }

           DB::commit();

        } catch(\Exception $e)
        {
          DB::rollback();
          Session::flash('error', ''.' No se pudo guardar la matricula, algo salió mal');
        }   	

    	return Redirect::to('expediente/matricula');
    }

    

    public function show($id)		//Para mostrar
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
        ->select('estudiante.nombre','estudiante.apellido','estudiante.nie','pariente.id_detalle','pariente.parentesco','matricula.fotografia', 'matricula.id_matricula')
        ->join('estudiante as estudiante','pariente.nie','=','estudiante.nie','full outer')
        ->join('matricula as matricula','pariente.nie','=','matricula.nie','full outer')
        ->where('pariente.id_matricula','=',$id)
        ->orderby('estudiante.apellido','asc')
        ->get();

        

    	return view("expediente.matricula.show",["matricula"=>$matricula,"grado"=>$grado, "seccion"=>$seccion, "turno"=>$turno, "estudiante"=>$estudiante, "detallepartida"=>$detallepartida, "detalleM"=>$detalleM, "detalleP"=>$detalleP, "detalleC"=>$detalleC, "parientess"=>$parientess, "usuarioactual"=>$usuarioactual]);
    }

    public function edit($id)
    {
        $usuarioactual=\Auth::user();

    	$matricula = Matricula::findOrFail($id);

    	#$tipos = DB::table('tipo_responsable')->get();
        $tipos = TipoResponsable::all();

        $anios = Anio::all();
        
        $anio =  $matricula->fechamatricula;
        $anio =  strtotime($anio);
        $anio = date('Y',$anio);

        $grados = Grado::all();
    	#$grados = DB::table('grado')->get();

        $secciones = Seccion::all();
    	#$secciones = DB::table('seccion')->get();

        $turnos = Turno::all();
    	#$turnos = DB::table('turno')->get();

        $dg = $matricula->iddetallegrado;
        $detalleg = DetalleGrado::findOrFail($dg);

        $nie = $matricula->nie;

        $estudiante = Estudiante::where('nie',$nie)->first();
        #$estudiante = DB::table('estudiante')->where('nie','=',$nie);
        $detallepartida = PartidaNacimiento::where('nie',$nie)->first();
        #$detallepartida = Partida::findOrFail($nie);

        //Se obtienen los datos de la madre, padre, contacto
        $detalleM = Responsable::where('nie',$nie)->where('idresponsable',1)->first();
        $detalleP = Responsable::where('nie',$nie)->where('idresponsable',2)->first();
        $detalleC = Responsable::where('nie',$nie)->where('idresponsable',3)->first();
        #$detalleM = Responsable::where('idresponsable',1)->first();

        $matriculas = DB::table('matricula')->get();
        #$partida 

    	return view("expediente.matricula.edit",["matricula"=>$matricula, "detalleg"=>$detalleg, "anios"=>$anios, "anio"=>$anio, "grados"=>$grados, "secciones"=>$secciones, "turnos"=>$turnos, "matriculas"=>$matriculas, "estudiante"=>$estudiante, "detallepartida"=>$detallepartida, "detalleM"=>$detalleM, "detalleP"=>$detalleP, "detalleC"=>$detalleC, "usuarioactual"=>$usuarioactual]);
    }


    public function update(Matricula2FormRequest $request, $id)
    {	
        $usuarioactual=\Auth::user();

        try{
            DB::beginTransaction();

    	    $matricula = Matricula::findOrFail($id);

    	   //TODO LO DEL METODO STORE
          //$matricula->fechamatricula=$request->get('fechamatricula');

            $fechaFormato = "01/01/".$request->get('fechamatricula');
            $time = strtotime($fechaFormato);
            $matricula->fechamatricula = date('Y-m-d',$time);

            //Obtenemos la fecha de matricula
            $matricula->fechareal = $request->get('fechareal');


            //Presenta partida
            if (Input::get('presentapartida')) {
                $matricula->presentapartida='SI';       // El usuario marcó el checkbox 
            } else {
                $matricula->presentapartida='NO';       // El usuario NO marcó el chechbox
            }

            //Certificado de promocion
            if (Input::get('certificadoprom')) {
                $matricula->certificadoprom='SI';       // El usuario marcó el checkbox 
            } else {
                $matricula->certificadoprom='NO';       // El usuario NO marcó el chechbox
            }

            //Presenta Fotografias
            if (Input::get('presentafotos')) {
                $matricula->presentafotos='SI';       // El usuario marcó el checkbox 
            } else {
                $matricula->presentafotos='NO';       // El usuario NO marcó el chechbox
            }    

            //Constancia de buena conducta
            if (Input::get('constanciaconducta')) {
                $matricula->constanciaconducta='SI';       // El usuario marcó el checkbox 
            } else {
                $matricula->constanciaconducta='NO';       // El usuario NO marcó el chechbox
            }

            //EDUCACION INICIAL
            if (Input::get('educacioninicial')) {
                $matricula->educacioninicial='SI';       // El usuario marcó el checkbox 
            } else {
                $matricula->educacioninicial='NO';       // El usuario NO marcó el chechbox
            }

            //REPITE GRADO
            if (Input::get('repitegrado')) {
                $matricula->repitegrado='SI';       // El usuario marcó el checkbox 
            } else {
                $matricula->repitegrado='NO';       // El usuario NO marcó el chechbox
            }

            $matricula->estado='Activo';
            $matricula->ceprevio=$request->get('cePrevio');
            $matricula->telefonoce=$request->get('telefonoce');
            $matricula->vivecon=$request->get('vivecon');

            $fechaentrega = $request->get('fechaentrega');
            if ($fechaentrega !="") {
                $matricula->fechaentrega=$request->get('fechaentrega');
            }

            if(Input::hasFile('fotografia')){
            $file = Input::file('fotografia');
            $file -> move(public_path().'/imagenes/alumnos/', $file->getClientOriginalName());
            $matricula->fotografia = $file->getClientOriginalName();
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
                $matricula->iddetallegrado = $detalleGrado->iddetallegrado;
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
                Session::flash('update', ''.' Actualización Exitosa');
            }

            //Ahora de procede al tratamiento de Estudiante.
            $nie = $matricula->nie;
            $partida = PartidaNacimiento::where('nie',$nie)->first();
            $partida->partida = $request -> get('partida');
            $partida->folio = $request -> get('folio');
            $partida->libro = $request -> get('libro');
            $partida -> update();

            //Se hace la fk a la tabla estudiante, una vez creada la partida.

            $estudiante = Estudiante::where('nie',$nie)->first();        //Obtengo el estudiante
            $estudiante->nie = $request -> get('nie');                   //***
            $estudiante->id_partida = $partida->id_partida;
            $estudiante->nombre = $request -> get('nombre');
            $estudiante->apellido = $request -> get('apellido');
            $estudiante->fechadenacimiento = $request -> get('fechadenacimiento');



            //Hizo Kinder
            $vt0 = $request->get('hizokinder');
            if ($vt0=='0') {
                $matricula->hizokinder = 'NO';
            }
            else{
                $matricula->hizokinder = 'SI';
            }

            //Sexo del estudiante
            $vt1 = $request->get('sexo');
            if ($vt1=='0') {
                $estudiante->sexo = 'M';
            }
            else{
                $estudiante->sexo = 'F';
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
            ######$estudiante ->estado = 'Activo';
            $estudiante -> update();


            //Se procede a guardar el nie
            //$partida->nie = $estudiante->nie;
            //$partida->update();

            //Se procede a guardar datos de la Madre  (con el NIE actualizado en la bd se busca)

            $madre = Responsable::where('nie',$estudiante->nie)->where('idresponsable',1)->first();
            ######$madre->idresponsable=1;
            $madre->nie=$estudiante->nie;
            $madre->nombre =$request->get('nombre2');
            $madre->apellido =$request->get('apellido2');
            $madre->ocupacion =$request->get('ocupacion');
            $madre->lugardetrabajo =$request->get('lugardetrabajo');
            $madre->telefono =$request->get('telefono');
            $madre->dui =$request->get('dui');
            $madre->update();

            
            //Se procede a guardar datos de la Padre    (con el NIE actualizado en la bd se busca)
            $padre = Responsable::where('nie',$estudiante->nie)->where('idresponsable',2)->first();
            ####$padre->idresponsable=2;
            $padre->nie=$estudiante->nie;
            $padre->nombre =$request->get('nombre3');
            $padre->apellido =$request->get('apellido3');
            $padre->ocupacion =$request->get('ocupacion3');
            $padre->lugardetrabajo =$request->get('lugardetrabajo3');
            $padre->telefono =$request->get('telefono3');
            $padre->dui =$request->get('dui3');
            $padre->update();


            //Se procede a guardar datos del Contacto de Emergencia (con el NIE actualizado en la bd se busca)
            $contacto = Responsable::where('nie',$estudiante->nie)->where('idresponsable',3)->first();
            ###$contacto->idresponsable=3;
            $contacto->nie=$estudiante->nie;
            $contacto->nombre =$request->get('nombre4');
            $contacto->apellido =$request->get('apellido4');
            $contacto->telefono =$request->get('telefono4');
            $contacto->update();
        
        
            //Se procede a guardar la matricula
            $matricula->nie = $estudiante->nie;

    	    $matricula->update();
        
            DB::commit();

        }catch(\Exception $e)
        {
            DB::rollback();
            Session::flash('unicidad', ''.' No se pudo actualizar, el NIE ingresado ya existe');
            return Redirect::to('expediente/matricula');
        }

    	return $this->show($id);
    }


    public function destroy($id)
    {
        $usuarioactual=\Auth::user();

    	$matricula=Matricula::findOrFail($id);
    	$matricula->estado = 'Inactivo';
    	$matricula->update();

        $estudiante = Estudiante::where('nie',$matricula->nie)->first();

        Session::flash('delete',"El estudiante ".$estudiante->nombre.' '.$estudiante->apellido. " fué dado de baja exitosamente");

    	return Redirect::to('expediente/matricula');
    }

}
