@extends ('layouts.maestro')
@section ('contenido')

<div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h2>Consulta de faltas</h2> 
        <h4 style="color: #00695c;"><b>Grado: </b> {{$nGrado->nombre}} <b>Sección: </b>" {{$nSeccion->nombre}}" <b>Turno: </b> {{$nTurno->nombre}}</h4>
        </div>
    </div> 
    <div class="row">
        <div  class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        @include('faltas.lista.search')
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
            <th>Apellidos</th>
            <th>Nombres</th>
            <th>Fecha de Falta</th>
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
                    <b><p>{{$es->fecha_falta}}</p></b> 
                    </td>
                                   
                    <td>
                       
              
             
              <div class="form-group">

              <a href="" data-target="#modal-detalle-{{$es->id_falta}}" data-toggle="modal"><button class="btn btn-info">Ver</button></a>
              
              <a href="" data-target="#modal-delete-{{$es->id_falta}}-{{$id}}" data-toggle="modal"><button class="btn btn-danger">Eliminar</button></a>


   
             
          
              </div>
              </a>

                    </td>
                </tr>
                <div>
                @include('faltas.eliminar')
                </div>
                @include('faltas.detalle')
                
            @endforeach
            
          
            </tbody>
        </table>
        {{ $date }} <a href="{{URL::action('FaltaController@index2')}}" class="btn btn-danger col-md-offset-8">Regresar</a>
    </div>
</div> 

@endsection