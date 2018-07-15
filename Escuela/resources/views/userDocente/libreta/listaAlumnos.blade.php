@extends ('layouts.maestro')
@section ('contenido')

<div class="section">

	<div class="row">
        <div class="col-lg-12">
         <h3 style="color: #00695c;"><b>Grado: </b> {{$grado->nombre}} <b>Sección: </b>" {{$seccion->nombre}}" <b>Turno: </b> {{$turno->nombre}}</h3> 
        </div>
    </div> 

    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
        @if (Session::has('message'))
            <p class="alert alert-danger">{{ Session::get('message')}}</p>
        @endif

        @if (Session::has('empty'))
            <p class="alert alert-warning">{{ Session::get('empty')}}</p>
        @endif

        @if (Session::has('update'))
            <p class="alert alert-info">{{ Session::get('update')}}</p>
        @endif  

        @if (Session::has('create'))
            <p class="alert alert-success">{{ Session::get('create')}}</p>
        @endif

        	@include('userDocente.libreta.search')
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-striped">
        <thead>
            <th></th>
            <th>Nombre</th>
            <th>Fotografía</th>
            <th>Opciones</th>
        </thead>
            <tbody>
            @foreach ($matriculas as $ma)
                <tr>
                    <td>
                        <a href="#"><i class="fa fa-chevron-right  fa-3x fa-fw"></i></a>
                    </td>
                    <td>
                        <h4>
                            <b>{{ $ma->nombre }} </b>
                        </h4>
                        <h4>{{ $ma->apellido }}</h4>
                    </td>
                    <td>
                        <img src="{{asset('imagenes/alumnos/'.$ma->fotografia)}}" alt="{{ $ma->fotografia}}" class="img-circle" width="60">
                    </td>
                    <td>
                        <a href="{{URL::action('LibretaNotasController@show',$ma->id_matricula)}}"><button class="btn btn-warning">Ver Notas</button></a>
                        <!--Jairo-->
                        <a href="{{URL::action('LibretaNotasController@showPDF',$ma->id_matricula)}}"><button class="btn btn-danger">Imprimir</button></a>
                    </td>
                </tr>
            </tbody>
            @endforeach
        </table>
    </div>
    {{$matriculas->render()}}
</div>
@endsection


