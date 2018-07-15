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
          @if (Session::has('update'))
            <p class="alert alert-info">{{ Session::get('update')}}</p>
        @endif  

        @if (Session::has('create'))
            <p class="alert alert-success">{{ Session::get('create')}}</p>
        @endif
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-striped">
        <thead>
            <th></th>
            <th>Apellido</th>
            <th>Nombre</th>
               <th>Trimestre I</th>
               <th>Trimestre II</th>
               <th>Trimestre III</th>
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
                            <b>{{ $es->apellido }}</b>
                        </h4>
                    </td>
                    <td>
                         <h4>
                            <b>{{ $es->nombre }}</b>
                        </h4>
                    </td>

                    <td>
                     <a href="" >
              <div class="form-group">    
              <a class="btn btn-success " href="" data-target="#modal-create" data-toggle="modal" ><i class="fa fa-fw -square -circle fa-plus-square" href=# data-toggle="tooltip" title="agregar conducta del alumno"></i></a>
                  @include('aspectos/trimestre1/modal')
              </div>
              </a>       
                    </td>
                    <td>
                  
              <div class="form-group">
              <a  class="btn btn-success " href="" data-target="#modal-create2" data-toggle="modal" ><i class="fa fa-fw -square -circle fa-plus-square" href=# data-toggle="tooltip" title="agregar conducta del alumno"></i></a>
                  @include('aspectos/trimestre2/modal')
              </div>
               </a>
              </td>               
              <td>
              <a href="" >
              <div class="form-group" >
              
              <a class="btn btn-success " href="" data-target="#modal-create3" data-toggle="modal" ><i class="fa fa-fw -square -circle fa-plus-square" href=# data-toggle="tooltip" title="agregar conducta del alumno"></i></a>
                  @include('aspectos.trimestre3.modal')
              </div>
              </a>
              </td>
              <td>
                <a href="#" data-toggle="modal"><span class="btn btn-warning">VER</span></a>
                 <a href="" data-target="aspectos-eliminar-delete-{{$es->nie}}" data-toggle="modal"><button class="btn btn-danger">Eliminar</button></a>
                  
                  </td>
                 
                </tr>
            @endforeach

           
            </tbody>
        </table>
        {{ $date }} <a href="{{URL::action('ConductaController@index')}}" class="btn btn-danger col-md-offset-8">Regresar
        </a>
        
    </div>
</div> 

@endsection