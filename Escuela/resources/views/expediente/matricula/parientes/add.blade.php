@extends ('layouts.admin')
@section('contenido')

<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
		@if (count($errors)>0)
			<div class="alert alert-danger">
				<ul>
				<p>Correcciones:</p>
				<?php $cont = 1; ?>
				@foreach ($errors->all() as $error)
					<li>{{$cont}}. {{$error}}</li>
					<?php $cont=$cont+1; ?>
				@endforeach
				</ul>
			</div>
			@endif
	</div>
</div>


{!!Form::open(array('url'=>'/matricula/parientes/store/'.$id,'method'=>'POST','autocomplete'=>'off'))!!}
            {{Form::token()}}

<fieldset class="well the-fieldset">
	<div class="col-md-12 col-md-offset-0">
			<legend class="the-legend"><h3>Tiene familiares estudiando dentro de la institución</h3></legend>
			<div class="panel panel-primary">
				<div class="panel-body">
					<div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
						<div class="form-group">
							<label for="matricula">Estudiante</label>
							<select name="pid_matricula" class="form-control selectpicker" id="pid_matricula" data-Live-search="true">
								@foreach($estudiantes as $estudiante)
								<option value="{{ $estudiante->id }}">{{$estudiante->nombre}} {{$estudiante->apellido}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
						<div class="form-group">
							<label for="parentesco">Parentesco</label>
							<input type="text" name="pparentesco" id="pparentesco" class="form-control" placeholder="Parentesco">
						</div>
					</div>
					<div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
						<div class="form-group">
							<br>
							<button type="button" id="bt_add" class="btn btn-primary" >Agregar</button>
						</div>
					</div>
					<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
						<table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
							<thead style="background-color: #A9D0F5">
								<th>Opciones</th>
								<th>Estudiante</th>
								<th>Parentesco</th>
							</thead>
							<tfoot>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
							</tfoot>
							<tbody>
								
							</tbody>
						</table>
					</div>			
				</div>
			</div>
	</div>
</fieldset>

		<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12" id="guardar">
			<div class="form-group">
            	<a href="{{URL::action('MatriculaController@index')}}" class="btn btn-danger btn-lg col-md-offset-1">Cancelar</a>
            	<button class="btn btn-primary btn-lg col-md-offset-8" type="submit">Guardar</button>
            </div>
		</div>
		
{!!Form::close()!!}

@push('scripts')


<script>

	$(document).ready(function(){
		$('#bt_add').click(function(){
			agregar();
		});
	});

	var cont = 0;
	//$('#guardar').hide(); //Botón guardar inicialmente oculto

	function agregar(){
		id_matricula =$('#pid_matricula').val();
		matricula=$("#pid_matricula option:selected").text();
		parentesco =$('#pparentesco').val();

		if(id_matricula!=" " && parentesco!=""){

			var fila='<tr class="selected" id="fila'+cont+'"><td><button type="button" class="btn btn-warning" onclick="eliminar('+cont+');">X</button></td><td><input type="hidden" name="id_matricula[]" value="'+id_matricula+'">'+matricula+'</td><td><input type="text" name="parentesco[]" value="'+parentesco+'"></td></tr>';
			cont++;
			limpiar();
			$('#detalles').append(fila);
		}
		else{
			alert("Por favor ingrese el parentesco del estudiante");
		}
	}

	
	function limpiar(){
		$("#pparentesco").val("");
	}

	function eliminar(index){
		$("#fila"+index).remove();
		evaluar();
	}

</script>
@endpush

@endsection