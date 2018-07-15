@extends ('layouts.maestro')
@section ('contenido')

<div class="row">
        <div class="col-lg-12">
         <h2 style="color: #00695c;"><b>Grado: </b> {{$nGrado->nombre}} <b>Sección: </b>" {{$nSeccion->nombre}}" <b>Turno: </b> {{$nTurno->nombre}}</h2>
         <h3>Listado de alumno</h3> 
        </div>
    </div> 

<div class="section">

    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
          @if (Session::has('message'))
            <p class="alert alert-success">{{ Session::get('message')}}</p>
          @endif
        </div>
        
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-striped">
        <thead>
            <th></th>
            <th>Nie</th>
            <th>Apellido</th>
            <th>Nombre</th>
            <th>Opciones</th>
        </thead>
            <tbody>
            
            @foreach ($est as $es)
           
          
                <tr>
                    <td>
                        <a href="#"><i class="fa fa-user  fa-2x fa-fw"></i></a>
                    </td>
                    <td>
                     <b>{{ $es->nie }}</b>
                    </td>
                    <td>
                        <b>{{ $es->apellido }}</b>
                    </td>
                    <td>  
                        <b>{{ $es->nombre }}</b>
                    </td>
                    <td>
                      <div class="form-group">
                         <a href="" data-target="#modal-crear-{{$es->id_matricula}}" data-toggle="modal"><button class="btn btn-primary">Falta</button></a>
                      </div>
                    </td>
                </tr>
                @include('faltas.lista.crear')
            @endforeach
            
          
            </tbody>
        </table>
        {{ $date }} <a href="{{URL::action('FaltaController@index')}}" class="btn btn-danger col-md-offset-8">Regresar</a>
    </div>
</div> 

@endsection