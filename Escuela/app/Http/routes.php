
<?php
use Escuela\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Escuela\MaestroUser;






//GRUPO DE URL DE USUARIO SIN LOGEARSE
Route::group(['middleware' => 'guest'], function () {
  
  Route::get('/', 'Auth\AuthController@getLogin');
	Route::get('login', 'Auth\AuthController@getLogin');
	Route::post('login', ['as' =>'login', 'uses' => 'Auth\AuthController@postLogin']); 
 
 /*DESCOMENTAR ESTE GRUPO DE RUTAS CUANDO SE QUIERA AGREAGAR EL PRIMER USUARIO EN LA BASE DE DATOS*/
    Route::get('register', 'Auth\AuthController@getRegister');
    Route::get('register', 'Auth\AuthController@tregistro'); 
    Route::post('register', ['as' => 'auth/register', 'uses' => 'Auth\AuthController@postRegister']);

});

/*Rutas para errores*/
Route::get('error', function(){ 
  abort(500);
  abort(404);
});





//GRUPO DE URLS LOGEADOS 

Route::group(['middleware' => 'auth'], function () {
	  Route::get('/', 'HomeController@index');
    Route::get('home', 'HomeController@index');
    Route::get('logout', ['as' => 'logout', 'uses' => 'Auth\AuthController@getLogout']);
    Route::resource('datos/Estudiante','EstudianteController');         //Estudiantes
    Route::resource('expediente/matricula','MatriculaController');     //Nuevo Ingreso
    Route::resource('expediente/matricula2','Matricula2Controller');  //Antiguo Ingreso

    Route::get('matricula/parientes/{id}', ['as' => 'al', 'uses' => 'ParienteController@listParientes']);
    Route::get('matricula/parientes/add/{id}', ['as' => 'al', 'uses' => 'ParienteController@addParientes']);
    Route::resource('matricula/parientes/store/{id}/', 'ParienteController@store');
    Route::resource('expediente/pariente','ParienteController');  
    
    Route::get('reporte','ReporteController@index');                  //Reportes
    Route::post('recuperandoDatos','ReporteController@store');        //Reportes

});










//grupo de rutas de usuario administrador
   
Route::group(['middleware' => 'usuarioAdmin'], function () {
      
      Route::get('form_nuevo_usuario', ['as' => 'form_nuevo_usuario', 'uses' => 'UsuariosController@form_nuevo_usuario']);
      Route::post('agregar_nuevo_usuario', 'UsuariosController@agregar_nuevo_usuario','UsuariosController@agregar_detalle');
      Route::get('listado_usuarios/{page?}', ['as' => 'listado_usuarios/{page?}', 'uses' => 'UsuariosController@listado_usuarios']);
      Route::get('form_editar_usuario/{id}', 'UsuariosController@form_editar_usuario');
      Route::put('editar_usuario/{id}',  'UsuariosController@editar_usuario');
      /*Route::post('editar_usuario', 'UsuariosController@editar_usuario');*/




     //COLOCAR SUS RUTAS ACA PARA USUARIO ADMIN

Route::resource('datos/tipoResponsable','TipoResponsableController');
Route::resource('datos/Responsable','ResponsableController');
#Route::resource('datos/Estudiante','EstudianteController');
Route::resource('detalle/grado','GradoController');
Route::resource('detalle/seccion','SeccionController');
Route::resource('detalle/turno','TurnoController');

//rutas materia
Route::resource('detalle/materia','MateriaController');
Route::resource('detalle/actividad','ActividadController'); //Ruta de gestion de actividades 


//Rutas de Gestion de docentes
Route::resource('docente/cvitae','HojaVidaController');
Route::get('municipios/{id}','HojaVidaController@getMunicipios');
Route::resource('docente/estudios','MaestroEstudiosController');
Route::get('docente/estudios/lista/{id}', ['id' => 'list', 'uses' => 'MaestroEstudiosController@getLista']);

Route::resource('docente/capacitaciones','MaestroCapacitacionController');
Route::get('docente/capacitaciones/lista/{id}', ['id' => 'list', 'uses' => 'MaestroCapacitacionController@getLista']);

Route::resource('docente/trabajos','MaestroTrabajoController');
Route::get('docente/trabajos/lista/{id}', ['id' => 'list', 'uses' => 'MaestroTrabajoController@getLista']);


Route::resource('asignacion', 'AsignacionController');

Route::resource('asignacion_cupos', 'CupoController');
Route::resource('asignacion_Usuarios', 'AsignacionUserController');

Route::resource('asignacion', 'AsignacionController');




Route::resource('asignacion/materia', 'AsignacionMateriaController');

Route::get('asignacion/{valor}','AsignacionController@show');

Route::resource('imprimir','ImprimirController');

Route::get('matripdf', function() {
  $pdf = PDF::loadView('matricul');
  return $pdf->stream('MatriculaInscrpcion.pdf');
});

Route::get('hojavidapdf', function(){
  $pdf = PDF::loadView('hojavid');
  return $pdf->stream('HojaVida.pdf');
});

Route::get('libretapdf', function(){
  $pdf = PDF::loadView('libreta')->setPaper('Letter', 'landscape')->setWarnings(false)->save('LibretaNotas.pdf');
  return $pdf->stream('LibretaNotas.pdf');
});








      //CREACION DE BOTON ELIMINAR MEDIANTE RUTA 
      Route::delete('eliminar/{id}',  function ($id){
        $detuser=MaestroUser::where('id_usuario','=',$id);
        $detuser->delete();
       
        User::find($id)->delete();
            Session::flash('message',"Usuario Eliminado Correctamente");
           return redirect('listado_usuarios');
      });

      //BOTON EDITAR MEDIANTE RUTA 
     /* Route::put('editar_usuario/{id}',  function (Request $request, $id){
           $usuario = User::find($id);
           $usuario->name  =   $request->name;
           $usuario->email=  $request->email;
           $usuario->tipoUsuario=  $request->tipoUsuario;
           $usuario->password=  $request->password;
           $usuario->save();

           return redirect('listado_usuarios/{page?}');
      });*/
      
});



//grupo de rutas para usuario standar

Route::group(['middleware' => 'usuarioStandard'], function () { 

  //Asistencias
  Route::resource('inasistencia','FaltaController');
  Route::get('faltas/consulta','FaltaController@index2');
  Route::get('faltas/lista/estudiante/{a1}', ['as' => 'list', 'uses' => 'FaltaController@show']);
  Route::get('faltas/lista/inasistencia/{a1}',['as' => 'list', 'uses' => 'FaltaController@show2']);
  Route::patch('inasistencia/editar/{id}',['as'=>'editar','uses'=> 'FaltaController@update']);
 



    /////Jairo
    Route::get('curso/alumno/libreta/{id}','LibretaNotasController@showPDF');


  //Evaluaciones
  Route::resource('evaluacion', 'EvaluacionController');
  Route::get('eval','EvaluacionController@indice');
  Route::patch('/evaluacion-editar/{id}',[
    'as' => 'userDocente.evaluaciones.update',
    'uses' => 'EvaluacionController@update'
]);
Route::get('actividades/{id}','EvaluacionController@getActividades');
 

  //{id_asignacion}{id_asignacion}{nombreGrado}{nombreSeccion}{nombreTurno}{nombreMateria}
  Route::get('userDocente/lista1/estudiante/{a1}/{a2}/{nG}/{nS}/{nT}/{nM}', ['as' => 'lista1', 'uses' => 'EvaluacionController@getLista']);
  Route::get('evaluaciones/{id}','EvaluacionController@getEvaluaciones');
 // Route::get('userDocente/trim/notas1/{g}/{s}/{t}', ['as' => 'notas', 'uses' => 'EvaluacionController@edit']);



 
});








