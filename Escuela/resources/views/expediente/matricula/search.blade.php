<div class="row">
		<div class="col-md-2 col-sm-6 col-xs-6 col-md-offset-0">
			<div class="form-group">
				<label><b>NUEVO INGRESO</b></label>
				<a href="matricula/create" class="col-md-offset-0"><button class="btn btn-success"><i class="fa fa-fw -square -circle fa-plus-square"></i> Crear Matricula</button></a>
			</div>
		</div>
{!! Form::open(array('url'=>'expediente/matricula','method'=>'GET','autocomplete'=>'off','role'=>'search')) !!}
		<div class="col-md-2 col-sm-6 col-xs-6 col-md-offset-0">
			<div class="form-group">
				<label>Año</label>
				{{ Form::select('valor', $anios->pluck('valor','valor'), null, ['class'=>'form-control']) }}
			</div>
		</div>
		<div class="col-md-2 col-sm-6 col-xs-6">
			<div class="form-group">
				<label>Grado</label>
				{{ Form::select('idgrado', $grados->pluck('nombre','idgrado'), null, ['class'=>'form-control']) }}
			</div>
		</div>
		<div class="col-md-2 col-sm-6 col-xs-6">
			<div class="form-group">
				<label>Seccion</label>
				{{ Form::select('idseccion', $secciones->pluck('nombre','idseccion'), null, ['class'=>'form-control']) }}
			</div>
		</div>
		<div class="col-md-2 col-sm-6 col-xs-6">
			<div class="form-group">
				<label>Turno</label>
				{{ Form::select('idturno', $turnos->pluck('nombre','idturno'), null, ['class'=>'form-control']) }}
			</div>
		</div>
		<div class="col-md-2 col-sm-6 col-xs-6">
			<label>Acción</label>
			<div class="input-group">
				<span class="input-group-btn"><button type="submit" class="btn btn-primary">Buscar Curso</button></span>
			</div>
		</div>
{{Form::close()}}
</div>

