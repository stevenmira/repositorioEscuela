@extends ('layouts.admin')
@section('contenido')


<div class="row">  

  <div class="col-md-6">

        
                        
                        <div class="box-header">
                          <h3 class="box-title">Editar información del Usuario</h3>
                        </div><!-- /.box-header -->

   </div>    
   </div>

<div class="row">
      <div class="form-group" class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
        <form action="{{ url('editar_usuario')}}/{{$usuario->id_usuario}}" method="post">
            {{ csrf_field() }}{{ method_field('PUT') }}
                   
          <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>"> 
          
          <input type="hidden" name="id_usuario" value="<?= $usuario->id_usuario; ?>">              


             <div class="box-body ">
                    <div class="form-group col-xs-6">
                      <label for="name">Nombre*</label>
                      <input type="text" class="form-control" id="name" name="name" value="<?= $usuario->name; ?>" placeholder="Nombres" >
                    </div>
     
               <div class="form-group col-xs-6">
                      <label for="tipoUsuario">Tipo Usuario</label>
                      <select id="tipoUsuario"  name="tipoUsuario" class="form-control" >
                      <?php  for($i=0;$i<=count($tiposusuario)-1;$i++){   ?>
                      <option value="<?= $tiposusuario[$i]->tipoUsuario ?>" ><?= $tiposusuario[$i]->nombre ?></option>
                      <?php  } ?>
                      </select>
                   
              </div>
              <div class="form-group col-xs-6">
                      <label for="email">Email*</label>
                      <input type="text" class="form-control" id="email" name="email" value="<?= $usuario->email; ?>" placeholder="email" >
              </div>

              <div class="form-group col-xs-6">
                      <label for="email">password*</label>
                      <input type="text" class="form-control" id="password" value="<?= $usuario->password; ?>" name="password" p required >
              </div>
            </div>

        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12" >
            <div class="form-group">
            <div class=" col-md-3">
              <button class="btn btn-primary " type="submit" >Actualizar Datos</button>
              </div>
              <div class=" col-md-3"> 
              <a class="btn btn-danger" href="{{URL::action('UsuariosController@listado_usuarios')}}" >Cancelar</a>
              </div>
            </div>
        </div>
      </form>
  

   </div>
</div>  <!--div row-->


@endsection