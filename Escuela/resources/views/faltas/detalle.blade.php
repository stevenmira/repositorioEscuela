<div class="modal fade modal-slide-in-right" aria-hidden="true"
role="dialog" tabindex="-1" id="modal-detalle-{{$es->id_falta}}">

<!--{{Form::Open(array('action'=>array('FaltaController@store',$es->id_matricula),'method'=>'post'))}}
-->
Form::Open(array('url'=>'inasistencia','method'=>'patch','autocomplete'=>'off'))!!}

<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" 
			aria-label="Close">
				 <span aria-hidden="true">×</span>
			</button>
			<h2 class="modal-title">Detalle de la Falta</h2 >
			<h4><label>Alumno:</label> {{$es->apellido}} , {{$es->nombre}}</h4>
		</div>
	 <div class="modal-body">
	 
					<input type="text" name="idmatricula" value={{$es->id_falta}} hidden>
			        
		<div class="form-group col-md-4">
			   <div class="form-group">
					<label for="">Fecha de la falta</label>
					{!! Form::date('fechafalta', $es->fecha_falta, ['class' => 'form-control', 'disabled'=>'disabled']) !!}
			   </div>
		</div>
		<div class="form-group col-md-4">
				<div class="form-group">
				  <label for="">Justificación:</label>
				   {!! Form::textarea('detallefalta', $es->detalle_falta, ['class' => 'form-control' ,'readonly'=>'readonly' ,'placeholder'=>'Escriba la causa de la falta..', 'autofocus'=>'on', 'rows'=>'4']) !!}
				</div>
		</div>
	   <div class="form-group col-md-4">
		 <div class="form-group">
			<label>Con permiso</label>
			@if ($es->permiso == 'SI')
			<button class="checkbox" disabled="disabled"><label disabled="disabled">{!! Form::checkbox('conpermiso',1,true,['disabled'=>'disabled']) !!} Sí</label></button>
		@else
			<button class="checkbox" disabled="disabled"><label disabled="disabled">{!! Form::checkbox('conpermiso',1,false, ['disabled'=>'disabled'])  !!} SI</label></button>
		@endif
		</div>
	</div>
</div>	

		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			
		</div>
		
	</div>
</div>
{{Form::Close()}}
