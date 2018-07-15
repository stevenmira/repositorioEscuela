@extends ('layouts.admin')
@section ('contenido')

<div class="row">
	<div class="col-md-2 col-sm-6 col-xs-6 col-md-offset-0">
			<div class="form-group">
				<label>Agrega parentescos</label>
				<a href="{{ url('matricula/parientes/add', ['id' => $id]) }}" class="col-md-offset-0"><button class="btn btn-success"><i class="fa fa-fw -square -circle fa-plus-square"></i> Agregar Familiares</button></a>
			</div>
		</div>
</div>


<h3 style="text-align: center;">Familiares dentro de la Institución</h3>

@if($parientes == null)
<h3><i class="fa fa-exclamation-circle"></i> No se encontraron familiares dentro de la institución </h1>
@endif

<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-condensed table-hover">
					<thead>
						<th>NIE</th>
						<th>Nombre</th>
						<th>Apellido</th>
						<th>Parentesco</th>
						<th>Fotografía</th>
						<th>Opciones</th>

					</thead>
	               @foreach ($parientes as $ma)
					<tr>
						
						<td>{{ $ma->nie }}</td>
						<td>{{ $ma->nombre }}</td>
						<td>{{ $ma->apellido }}</td>
						<td>{{ $ma->parentesco }}</td>
						<td>
							<img src="{{asset('imagenes/alumnos/'.$ma->fotografia)}}" alt="{{ $ma->fotografia}}" height="50px" width="50px" class="img-thumbnail" onclick="javascript:this.width=150;this.height=150" ondblclick="javascript:this.width=50;this.height=50">
						</td>
						<td>
							<a title="Ver Matricula" href="{{URL::action('MatriculaController@show',$ma->id_matricula)}}"><button class="btn btn-warning">Ver</button></a>

	                         <a title="Eliminar Parentesco" href="" data-target="#modal-delete-{{$ma->id_detalle}}" data-toggle="modal"><button class="btn btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i>  Parentesco</button></a>
						</td>
						
						
					</tr>
					@include('expediente.matricula.parientes.modal')
					@endforeach
				</table>
			</div>
			<a href="{{URL::action('MatriculaController@index')}}" class="btn btn-danger btn-lg col-md-offset-1"><i class="fa fa-chevron-left" aria-hidden="true"></i> Atrás</a>
		</div>
	</div>
@endsection