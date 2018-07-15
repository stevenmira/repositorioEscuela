
@extends ('layouts.admin')
@section('contenido')

<div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
        <h3> Usuarios Registrados en el Sistema 
        </h3>
        <a href="form_nuevo_usuario"><button class="btn btn-success col-md-offset-0"><i class="fa fa-fw -square -circle fa-plus-square"></i> Nuevo Usuario</button></a>
        <br><br>
        @if (Session::has('message'))
            <p class="alert alert-danger">{{ Session::get('message')}}</p>
        @endif

        @if (Session::has('update'))
            <p class="alert alert-info">{{ Session::get('update')}}</p>
        @endif  

        @if (Session::has('create'))
            <p class="alert alert-success">{{ Session::get('create')}}</p>
        @endif

        @include('cupos.search')
    </div>
</div>


<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                     <th>Nombre</th>
                     <th>Email</th>
                     <th>Tipo</th>
                     <th>Fecha de Registro</th>
                     <th>Opciones</th>
                </thead>
               <?php 
                 foreach($usuarios as $usuario){  
               ?>
                <tr>
                    <td class="mailbox-messages mailbox-name" ><a   style="display:block"><i class="fa fa-user"></i>&nbsp;&nbsp;<?= $usuario->name ?></a></td>
                    <td><?= $usuario->email;  ?></td>
                    <td><span class="label label-primary "><?= $usuario->tipo($usuario->tipoUsuario);   ?></span></td>
                    <td><?= $usuario->created_at->format("Y-m-d"); ?></td>
                    <td><div class="form-group"><div class="col-lg-6 col-md-5 col-xs-2"><a  href="form_editar_usuario/{{$usuario->id_usuario}}" class="btn btn-info"> Editar</a></div>
                    <div class="col-lg-2 col-md-2 col-xs-2"> <form action="{{ url('eliminar')}}/{{$usuario->id_usuario}}" method="post">
                         {{ csrf_field() }}{{ method_field('DELETE') }}
                         <button type="submit" action="" class="btn btn-danger"> Eliminar</button></form></td></div></div>
                </tr>
                <?php        
                }
                ?>
            </table>
        </div>
     {{$usuarios->render()}}
    </div>
</div>


@endsection

