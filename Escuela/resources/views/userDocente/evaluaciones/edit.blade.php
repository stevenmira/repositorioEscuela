@extends ('layouts.maestro')
@section ('contenido')
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<h2>Editar Evaluacion <i class="fa fa-info-circle" aria-hidden="true" href="#" data-toggle="tooltip" data-placement="right" title="Edite la informacion relacionada a una evaluacion, unicamente podra cambiar el NOMBRE y el PORCENTAJE"></i></h2>
			@if (count($errors)>0)
			<div class="alert alert-danger">
				<ul>
				@foreach ($errors->all() as $error)
					<li>{{$error}}</li>
				@endforeach
				</ul>
			</div>
			@endif

			{!!Form::model($evaluacion,['method'=>'PATCH','route'=>['userDocente.evaluaciones.update',$evaluacion->id_evaluacion]])!!}
            {{Form::token()}}
            
					<div class="form-group col-md-4">
			


			<div class="form-group">
            	<label for="nombre">Nombre Evaluacion</label>
            	<input type="text" name="nombreEvaluacion" class="form-control" value="{{$evaluacion->nombre}}" placeholder="Nombre de evaluacion">
            </div>
			</div>



			<div class="form-group col-md-4">
						<div class="form-group">
						<label>Porcentaje</label>
						{!! Form::number('porcentaje', null, ['class' => 'form-control' , 'placeholder'=>'Introduza el porcentaje de la nota', 'autofocus'=>'on','max'=>50]) !!}
						</div>
				</div>

				
        	
			<div class="form-group col-md-12">
						<div class="form-group">
						<input name="_token" value="{{csrf_token()}}" type="hidden"></input>
				<a href="{{URL::previous()}}" class="btn btn-danger">Cancelar</a>
				<button type="submit" class="btn btn-primary">Guardar</button>
				
				</div>
				</div>

			{!!Form::close()!!}		
            
		</div>
	</div>
@endsection