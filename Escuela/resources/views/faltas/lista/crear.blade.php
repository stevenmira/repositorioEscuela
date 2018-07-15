<div class="modal fade modal-slide-in-right" aria-hidden="true"
role="dialog" tabindex="-1" id="modal-crear-{{$es->id_matricula}}">

<!--{{Form::Open(array('action'=>array('FaltaController@store',$es->id_matricula),'method'=>'post'))}}
-->
{!!Form::Open(array('url'=>'inasistencia','method'=>'post','autocomplete'=>'off'))!!}
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" 
			aria-label="Close">
				 <span aria-hidden="true">×</span>
			</button>
			<h2 class="modal-title">Nueva Falta</h2 >
			<h4><label>Alumno:</label> {{$es->apellido}},{{$es->nombre}} <i class="fa fa-info-circle" aria-hidden="true" href="#" data-toggle="tooltip" data-placement="right" title="Si el alumno no presento permiso dejar vacia la opcion"></i></h4>
		</div>
	 <div class="modal-body">
	
					<input type="text" name="idmatricula" value={{$es->id_matricula}} hidden>
			 
		<div class="form-group col-md-4">
			   <div class="form-group">
					<label for="">Fecha de la falta</label>
					{!! Form::date('fechafalta', \Carbon\Carbon::now(), ['class' => 'form-control']) !!}
			   </div>
		</div>
		<div class="form-group col-md-4">
				<div class="form-group">
				  <label for="">Justificación:</label>
				   {!! Form::textarea('detallefalta', null, ['class' => 'form-control' ,'placeholder'=>'Escriba la causa de la falta..', 'autofocus'=>'on', 'rows'=>'4']) !!}
				</div>
		</div>
	   <div class="form-group col-md-4">
		 <div class="form-group">
			<label>Con permiso</label></br>
			<button class="checkbox " disabled="disabled" ><label>{!! Form::checkbox('conpermiso') !!} Sí</label></button>
		  </div>
	</div>
</div>	
	
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			<button type="submit" class="btn btn-primary">Guardar</button>
		</div>
		
	</div>
</div>
{{Form::Close()}}
