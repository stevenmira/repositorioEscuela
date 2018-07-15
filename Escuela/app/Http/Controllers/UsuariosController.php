<?php 

namespace Escuela\Http\Controllers;

use Escuela\User;
use Storage;
use Illuminate\Http\Request;
use Escuela\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Escuela\TipoUsuario;
use Escuela\Http\Requests\UsuarioRequest;
use Illuminate\Http\JsonResponse;
use Escuela\MaestroUser;
use Escuela\Maestro;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use DB;

class UsuariosController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
   public function __construct()
	{
		$this->middleware('auth');
	}


   public function form_nuevo_usuario()
	{
        
        $usuarioactual=\Auth::user();
        $tiposusuario=TipoUsuario::all();
        $maestros=Maestro::all();
		return view('formularios.form_nuevo_usuario')->with("maestros",$maestros)->with("tiposusuario",$tiposusuario)->with("usuarioactual", $usuarioactual );  
		
	}


	public function listado_usuarios()
    {
        $usuarioactual=\Auth::user();
        $tipousuario=TipoUsuario::all();
		$usuarios= User::orderBy('created_at','desc')->paginate(10);  
	
        return view('listados.listado_usuarios',compact('usuarios'))
        ->with("usuarios", $usuarios )
        ->with("usuarioactual", $usuarioactual )->with("tiposusuario",$tipousuario);     
	}


	//presenta el formulario para nuevo usuario
	public function agregar_nuevo_usuario(Request $request)
	{		
		$usuarioactual=\Auth::user();
        $tipousuario=TipoUsuario::all();
        
        $data=$request->all();

        //crea el usuario en Tabla Users 
      	$usuario= new User;
		$usuario->name  =  $data['name'];
		$usuario->email=$data['email'];
        $usuario->tipoUsuario=$data['tipoUsuario'];
		$usuario->password=bcrypt($data['password']);
		$usuario->save();
		
		
		
		//obtiene el id del usuario creado
        $idUsuario=$usuario->id_usuario;
		
		    
		$dui=$request->get('mdui');
		$existe=MaestroUser::where('mdui','=',$dui)->first();
			
		if(is_null($existe))
		{			
            $maestro= new MaestroUser;
			$maestro->id_usuario=$idUsuario;
			$maestro->mdui=$data['mdui'];
			$maestro->save();
			
		}	
		Session::flash('create', 'Usuario Creado Correctamente');
		return redirect('listado_usuarios');			
		     
}

	public function form_editar_usuario($id)
	{
		//funcion para cargar los datos de cada usuario en la ficha
		$usuarioactual=\Auth::user();
		$usuario=User::find($id);
		$contador=count($usuario);
		$tiposusuario=TipoUsuario::all();
		
		if($contador>0){          
            return view("formularios.editar_usuario")
                   ->with("usuarioactual", $usuarioactual )
                   ->with("usuario",$usuario)
                   ->with("tiposusuario",$tiposusuario);   
		}
		else
		{            
            return view("mensajes.msj_rechazado")->with("msj","el usuario con ese id no existe o fue borrado");  
		}
	}

  

     public function editar_usuario(Request $request, $id)
    {   

		$usuarioactual=\Auth::user();
		$tipousuario=TipoUsuario::all();
		$usuarios= User::orderBy('created_at','desc')->paginate(10);  

		$usuario = User::find($id);
		$usuario->name  =   $request->name;
		$usuario->email=  $request->email;
		$usuario->tipoUsuario=  $request->tipoUsuario;
		$usuario->password=  $request->password;
		$usuario->update();
	   
		Session::flash('create',"Usuario Modificado correctamente");
		return redirect('listado_usuarios');
        
    }

    

		/*public function editar_usuario(Request $request)
	{



      
        $data=$request->all();
        $reglas = array('name' => 'required|Unique:users',
        	             'email' => 'required|Email|Unique:users',
        	             'tipoUsuario' => 'required|Numeric|min:1|max:2',
        	            );
        $mensajes= array('name.required' =>  'Ingresar Nombres es obligatorio',
        	             'name.unique' =>  'Ya existe el Usuario, favor ingresar otro nombre',
        	             'email.required' =>  'Ingresar un email es obligatorio',
        	             'email.email' =>  'el email debe tener un formato valido',
        	             'email.unique' =>  'el email debe ser unico en la base de datos',
        	             
        	             'tipoUsuario.numeric' =>  'Ingresar un tipo de usuario valido',
        	             );
        

      
        $validacion = Validator::make($data, $reglas, $mensajes);
        if ($validacion->fails())
        {
			 
			 $errores = $validacion->errors();  
	         return view("mensajes.msj_rechazado")->with("msj","Existen errores de validación")
			                                      ->with("errores",$errores); 			          
        }
        /*$usuario = user::find($id);
    	$usuario -> nombreGrado = $request -> get('nombre');
    	$grado -> estado = 'Activo';
    	$grado -> update();

    	return Redirect::to('detalle/grado');

		
		$usuario=User::find($idUsuario);
        $usuario->name  =  get('name');
		$usuario->email= get('email');
        $usuario->tipoUsuario= get('tipoUsuario');
		$usuario->password= bcrypt(get('password'));
		
		$resul= $usuario->update();

        $usuario=User::find($idUsuario);
        $usuario->name  =   $request->get('name');
		$usuario->email=  $request->get('email');
        $usuario->tipoUsuario=  $request->get('tipoUsuario');
		$usuario->password=  $request->bcrypt(get('password'));
  		$resul= $usuario->update();
		   $usuario = User::find($id);
           $usuario->name  =   $request->name;
           $usuario->email=  $request->email;
           $usuario->tipoUsuario=  $request->tipoUsuario;
           $usuario->password=  $request->password;
           $usuario->save();


		if($resul){            
            return view("mensajes.msj_correcto")->with("msj","Datos actualizados Correctamente");   
		}
		else
		{            
            return view("mensajes.msj_rechazado")->with("msj","hubo un error vuelva a intentarlo");  
		}
	}


	/*	public function subir_imagen_usuario(Request $request)
	{

	    $id=$request->input('id_usuario_foto');
		$archivo = $request->file('archivo');
        $input  = array('image' => $archivo) ;
        $reglas = array('image' => 'required|image|mimes:jpeg,jpg,bmp,png,gif|max:900');
        $validacion = Validator::make($input,  $reglas);
        if ($validacion->fails())
        {
          return view("mensajes.msj_rechazado")->with("msj","El archivo no es una imagen valida");
        }
        else
        {

	        $nombre_original=$archivo->getClientOriginalName();
			$extension=$archivo->getClientOriginalExtension();
			$nuevo_nombre="userimagen-".$id.".".$extension;
		    $r1=Storage::disk('fotografias')->put($nuevo_nombre,  \File::get($archivo) );
		    $rutadelaimagen="storage/fotografias/".$nuevo_nombre;
	    
		    if ($r1){

			    $usuario=User::find($id);
			    $usuario->imagenurl=$rutadelaimagen;
			    $r2=$usuario->save();
		        return view("mensajes.msj_correcto")->with("msj","Imagen agregada correctamente");
		    }
		    else
		    {
		    	return view("mensajes.msj_rechazado")->with("msj","no se cargo la imagen");
		    }

        }	

	}
    

	public function cambiar_password(Request $request){
        $email=$request->input("email_usuario");
        $usuariactual=\Auth::user();
        
        if($usuariactual->email != $email ){
		
		$reglas = array('email_usuario' => 'required|Email|Unique:users,email');
		$mensajes = array('email_usuario.unique' => 'El email ingresado ya esta en uso en la base de datos');
      $this->validate($request,$reglas, $mensajes);
           
         }

       

		$id=$request->input("id_usuario_password");
		$email=$request->input("email_usuario");
		$password=$request->input("password_usuario");
		$usuario=User::find($id);
	    $usuario->email=$email;
	    $usuario->password=bcrypt($password);
	    $r=$usuario->save();

	    if($r){
           return view("mensajes.msj_correcto")->with("msj","password actualizado");
	    }
	    else
	    {
	    	return view("mensajes.msj_rechazado")->with("msj","Error al actualizar el password");
	    }
	}



	public function form_cargar_datos_usuarios(){
       return view("formularios.form_cargar_datos_usuarios");
	}







   


      	public function info_datos_usuario($id)
	{
		//funcion para cargar los datos de cada usuario en la ficha
		$usuario=User::find($id);
		$contador=count($usuario);
		$tiposusuario=TipoUsuario::all();
       
		
		if($contador>0){          
            return view("standard.info_datos_usuario")
                   ->with("usuario",$usuario)
                   ->with("tiposusuario",$tiposusuario);
		}
		else
		{            
            return view("mensajes.msj_rechazado")->with("msj","el usuario con ese id no existe o fue borrado");  
		}
	}



	public function mostrar_errores(){
      
       return view("mensajes.msj_rechazado")->with("msj","Existen errores de validacion");

	}*/







}