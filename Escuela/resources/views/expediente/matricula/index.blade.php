@extends ('layouts.admin')
@section ('contenido')
<div class="row">
	<div class="col-md-6 col-sm-6 col-xs-12">
		@if (Session::has('error'))
			<p class="alert alert-danger">{{ Session::get('error')}}</p>
		@endif
		
		@if (Session::has('unicidad'))
			<p class="alert alert-danger">{{ Session::get('unicidad')}}</p>
		@endif

		@if (Session::has('delete'))
			<p class="alert alert-danger">{{ Session::get('delete')}}</p>
		@endif

		@if (Session::has('message'))
			<p class="alert alert-danger">{{ Session::get('message')}}</p>
		@endif

		@if (Session::has('deletePair'))
			<p class="alert alert-danger">{{ Session::get('deletePair')}}</p>
		@endif


		@if (Session::has('pair'))
			<p class="alert alert-success">{{ Session::get('pair')}}</p>
		@endif

		@if (Session::has('create'))
			<p class="alert alert-success">{{ Session::get('create')}}</p>
		@endif	
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		@include('expediente.matricula.search')	
	</div>
</div>
<p>*Puedes realizar las búsquedas por <b>año, grado, sección y turno</b>. </p>
<p>**Sólo se muestran las matriculas en estado <b>Activo</b>. Para cambiar el estado de la matricula  <a href="{{URL::action('Matricula2Controller@index')}}">haga clic aquí.</a></p>

@if($estudiantes == null)

	@if(Session::has('M1'))
	
	{{Session::get('M1')}}
	<h1><i class="fa fa-exclamation-circle"></i> No hay resultados </h1>
	@endif

@else
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-condensed table-hover">
					<thead>
						<tr class="warning">
			                <th colspan="12">
			                    <h3 style="text-align: center;"><b>Matriculas de Estudiantes</b></h3>
			                    <p style="text-align: center;">( {{$nombG}} {{$nombS}} {{$nombT}} )</p>
			                    <p style="text-align: center;">{{$numbA}}</p>
			                </th>
			            </tr>
						<th>Fecha Matricula</th>
						<th>NIE</th>
						<th>Nombre</th>
						<th>Apellido</th>
						<th style="text-align: center;">Foto</th>
						<th style="text-align: center;">Familiares</th>
						<th style="text-align: center;">Nueva</th>
						<th style="text-align: center;">Opciones</th>

					</thead>
	               @foreach ($estudiantes as $ma)
					<tr>
						<td>{{ $ma->fechamatricula }}</td>
						<td>{{ $ma->nie }}</td>
						<td>{{ $ma->nombre }}</td>
						<td>{{$ma->apellido}}</td>
						<td>
							<img src="{{asset('imagenes/alumnos/'.$ma->fotografia)}}" alt="{{ $ma->fotografia}}" height="50px" width="50px" class="img-thumbnail" onclick="javascript:this.width=150;this.height=150" ondblclick="javascript:this.width=50;this.height=50">
						</td>
						<td>
							<a title="Agregar parientes"  href="{{ url('matricula/parientes', ['id' => $ma->id_matricula]) }}"><button class="btn btn-primary center-block"><i class="fa fa-users" aria-hidden="true"></i></button></a>
						</td>
						<td>
							<a title="Nueva Matricula" href="{{URL::action('Matricula2Controller@edit',$ma->id_matricula)}}"><button class="btn btn-success center-block"><i class="fa fa-plus"></i></button></a>
						</td>
						<td>
							<a href="{{URL::action('MatriculaController@edit',$ma->id_matricula)}}"><button class="btn btn-info">Editar</button></a>
							<a href="{{URL::action('MatriculaController@show',$ma->id_matricula)}}"><button class="btn btn-warning">Ver</button></a>
	                         <a href="" data-target="#modal-delete-{{$ma->id_matricula}}" data-toggle="modal"><button class="btn btn-danger">Eliminar</button></a>
						</td>
					</tr>
					@include('expediente.matricula.modal')
					@endforeach
				</table>
			</div>
		</div>
	</div>
@endif
@endsection