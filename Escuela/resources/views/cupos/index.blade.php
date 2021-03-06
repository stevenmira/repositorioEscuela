@extends ('layouts.admin')
@section ('contenido')
<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h3> Información de Grados 
		</h3>
		<a href="asignacion_cupos/create"><button class="btn btn-success col-md-offset-0"><i class="fa fa-fw -square -circle fa-plus-square"></i> Nueva Asignación</button></a>
		<br><br>
		@if (Session::has('message'))
			<p class="alert alert-danger">{{ Session::get('message')}}</p>
		@endif

		@if (Session::has('update'))
			<p class="alert alert-info">{{ Session::get('update')}}</p>
		@endif	

		@if (Session::has('create'))
			<p class="alert alert-success">{{ Session::get('create')}}</p>
		@endif

		@include('cupos.search')
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead>
					<th>Grado</th>
					<th>Seccion</th>
					<th>Turno</th>
					<th>Cupos</th>
					<th>Opciones</th>
				</thead>
               @foreach ($detalle as $detalle)
				<tr>
                	<td>{{$detalle->grado($detalle->idgrado)}}</td>
                    <td>{{$detalle->seccion($detalle->idseccion)}}</td>
                    <td>{{$detalle->turno($detalle->idturno)}}</td>
                    <td>{{$detalle->cupo}}</td>
					
					<td>
						<a href="{{URL::action('CupoController@edit',$detalle->iddetallegrado)}}"><button class="btn btn-info">Editar</button></a>
                         <a href="" data-target="#modal-delete-{{$detalle->iddetallegrado}}" data-toggle="modal"><button class="btn btn-danger">Eliminar</button></a>
					</td>
				</tr>
				@include('cupos.modal')
				@endforeach
			</table>
		</div>
		
	</div>
	
</div>

@endsection






