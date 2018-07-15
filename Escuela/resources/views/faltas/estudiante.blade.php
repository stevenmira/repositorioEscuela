@extends ('layouts.maestro')
@section ('contenido')

<div class="row">
        <div class="col-lg-12">
         <h3 style="color: #00695c;"><b>Grado: </b> {{$nGrado->nombre}} <b>Sección: </b>" {{$nSeccion->nombre}}" <b>Turno: </b> {{$nTurno->nombre}}</h3>
         <p>(Selecciona el Estudiante)</p>  
        </div>
    </div> 

<div class="section">

    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
          @if (Session::has('message'))
            <p class="alert alert-danger">{{ Session::get('message')}}</p>
          @endif
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-striped">
        <thead>
            <th></th>
            <th>Nie</th>
            <th>Nombre</th>
               <th>Ultima falta</th>
            <th>Opciones</th>
        </thead>
            <tbody>
            @foreach ($est as $es)
                <tr>
                    <td>
                        <a href="#"><i class="fa fa-chevron-right  fa-3x fa-fw"></i></a>
                    </td>
                    <td>
                       <h4>
                            <b>{{ $es->nie }}</b>
                        </h4>
                    </td>
                    <td>
                         <h4>
                            <b>{{ $es->apellido }}</b>
                        </h4>
                        <b><p>{{ $es->nombre }}</p></b>
                       
                    </td>
                    <td>
                          
                    </td>
                                   
                    <td>
                        <a href="" >
              
             
              <div class="form-group">

              <a class="btn btn-success form-control" href="" data-target="#modal-falta" data-toggle="modal" ><i class="fa fa-fw -square -circle fa-plus-square" href=# data-toggle="tooltip" title="Agregar inasistencia a este alumno"></i>Falta</a>
                  @include('faltas.modal')
             
          
              </div>
              </a>

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $date }} <a href="{{URL::action('FaltaController@index')}}" class="btn btn-danger col-md-offset-8">Regresar</a>
    </div>
</div> 

@endsection