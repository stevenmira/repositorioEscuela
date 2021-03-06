@extends ('layouts.admin')
@section ('contenido')
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<h3>Editar Sección: {{ $seccion->nombre}}</h3>
			@if (count($errors)>0)
			<div class="alert alert-danger">
				<ul>
				@foreach ($errors->all() as $error)
					<li>{{$error}}</li>
				@endforeach
				</ul>
			</div>
			@endif

			{!!Form::model($seccion,['method'=>'PATCH','route'=>['detalle.seccion.update',$seccion->idseccion]])!!}
            {{Form::token()}}
            <div class="form-group">
            	<label for="nombre">Nombre</label>
            	<input type="text" name="nombre" class="form-control" value="{{$seccion->nombre}}" placeholder="Nombre...">
            </div>
            
            <div class="form-group">
            	<a href="{{URL::action('SeccionController@index')}}" class="btn btn-danger">Cancelar</a>
            	<button class="btn btn-primary" type="submit">Guardar</button>
            </div>

			{!!Form::close()!!}		
            
		</div>
	</div>
@endsection