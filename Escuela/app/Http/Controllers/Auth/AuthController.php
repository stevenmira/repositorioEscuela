<?php

namespace Escuela\Http\Controllers\Auth;
use Escuela\User;
use Validator;
use Escuela\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Escuela\MaestroUser;
use Escuela\Maestro;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Escuela\TipoUsuario;
use Escuela\Anio;
use Carbon\Carbon;
use DB;


class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;


    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
        $this->middleware('guest', ['except' => 'getLogout']);
      
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
   


//login

       protected function getLogin()
    {
        return view("login");
    }


       

        public function postLogin(Request $request)
   {
    $this->validate($request, [
        'name' => 'required',
        'password' => 'required',
    ]);



    $credentials = $request->only('name', 'password');



    if ($this->auth->attempt($credentials, $request->has('remember')))
    {
    
       $usuarioactual=\Auth::user();

       //if($usuarioactual->tipoUsuario==1){// SI EL USUARIO ES ADMIN MUESTRA EL VIEW DE ADMIN

        //calculo del año actual
	   $query3 = Carbon::now();
       $query1 = $query3->format('Y');
	  
	   $anioBD = DB::table('anios')
	   ->select('anios.valor')
	   ->orderBy('anios.valor','desc')
	   ->first();

	   if(intval($query1) > $anioBD->valor||$anioBD==null){
		   $anio = new Anio;
		   $anio->valor=$query1;
		   $anio->save();

	   }
      
       return view('/inicio/index')->with("usuarioactual",  $usuarioactual);
       //}
    //else
        //return view('layouts/standar')->with("usuarioactual", $usuarioactual);// SI EL USUARIO ES DIFERENTE A 1 MUSTRA POR LE MOMENTO USUARIO STANDAR
       //CAMBIAR RUTA A LA FUNCIONAL
    }

    Session::flash('message','Usuario o Contraseña Incorrectos');
    return view("login");

    }


//login

 //registro   


        protected function getRegister()
    {
       $usuarioactual=\Auth::user();
       return view("registro")->with("usuarioactual",  $usuarioactual);
    }


        

protected function postRegister(Request $request)
{
    

    $data = $request;


    $usuario= new User;
    $usuario->name  =  $data['name'];
    $usuario->email=$data['email'];
    $usuario->tipoUsuario=$data['tipoUsuario'];
    $usuario->password=bcrypt($data['password']);
    $usuario->save();
    
    
    Session::flash('message',"Usuario agregado correctamente");
    return back();
               
    
   

   

}
public function tregistro()
    {
        

        $tiposusuario=TipoUsuario::all();
        $maestros=Maestro::all();
        return view('registro')->with("tiposusuario",$tiposusuario)->with("maestros",$maestros);
        
    }

//registro

protected function getLogout()
    {
        $this->auth->logout();

        Session::flush();

        return redirect('login');
    }






}
