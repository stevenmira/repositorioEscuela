@extends ('layouts.maestro')
@section('contenido')
<div class="container" >
		<div class="row" >
			<div class="col-md-1">
					<a class="btn btn-primary form-control" href="{{URL::action('EvaluacionController@index')}}"><i class="fa fa-chevron-left" ></i> Atrás</a>
			</div>
		</div>
	</div>
<div class="row" >
		
	<blockquote><h1>Evaluaciones de {{$nMateria}}     <i class="fa fa-info-circle" aria-hidden="true" href="#" data-toggle="tooltip" data-placement="right" title="Aqui podra crear evaluaciones de la materia {{$nMateria}}"></i></h1></blockquote>	
		
</div>

<div class="container" >
	<div class="row" >
		<div class="col-md-5">
	<h4><strong>Grado: </strong>{{$nGrado}} <strong>Seccion: </strong> "{{$nSeccion}}" <strong>Turno:</strong> {{$nTurno}} </h4>
                
		</div>
	</div>
</div>
<div class="container" >
	<div class="row" >
		<div class="col-md-6">
			@if (Session::has('fallo'))
			<p class="alert alert-danger">{{ Session::get('fallo')}}</p>
			@endif

			@if (Session::has('exito'))
			<p class="alert alert-success">{{ Session::get('exito')}}</p>
			@endif

			@if (Session::has('no'))
			<p class="alert alert-danger">{{ Session::get('no')}}</p>
			@endif

			@if (Session::has('error1'))
			<p class="alert alert-warning">{{ Session::get('error1')}}</p>
			@endif
		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-12">
		<div class="col-md-2">
			<div class="form-group">
			<a class="btn btn-success form-control" href="" data-target="#modal-create-evaluacion" data-toggle="modal" ><i class="fa fa-fw -square -circle fa-plus-square" href=# data-toggle="tooltip" title="Haga clic aqui para crear una nueva evaluacion" ></i> Nueva Evaluacion</a>
			
				@include('userDocente.evaluaciones.modal')


			</div>
		</div>
		
	</div>
</div>

@if(Session::has('M1'))
<div class="alert alert-warning alert-dismissable">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">x</a>
 opss! La combinacion <strong>{{Session::get('M1')}}</strong> no esta definida. Puede agregarla <a href="{{URL::action('CupoController@index')}}">aqui</a>
</div>
@endif

	
	<div class="row">

	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead>
					<th>Nombre</th>
					<th>Actividad</th>
					<th>Periodo</th>
                    <th>Porcentaje</th>
					<th>Opciones</th>
				</thead>
              @foreach($evaluaciones as $evaluacion)
				  <tr>
					<td>{{$evaluacion->nombreEvaluacion}}</td>
					<td>{{$evaluacion->nombreActividad}}</td>
					<td>{{$evaluacion->nombreTrimestre}}</td>
					<td>{{$evaluacion->pEval}}</td>
					
					
					<td>
					@if($evaluacion->estado=='Activo')
					 	<a href="{{URL::action('EvaluacionController@edit',$evaluacion->id_evaluacion)}}"><button class="btn btn-info">Editar</button></a>
                         <a href="" data-target="#modal-delete-{{$evaluacion->id_evaluacion}}" data-toggle="modal"><button class="btn btn-danger">Eliminar</button></a>
						
					@endif
					</td>
				</tr>
				@include('userDocente.evaluaciones.modal1')
			  @endforeach
			</table>
		</div>

	</div>
</div>



@endsection