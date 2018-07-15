
<div class="modal fade modal-slide-in-right" aria-hidden="true"
role="dialog" tabindex="-1" id="modal-create-evaluacion">
	{!!Form::open(array('url'=>'evaluacion','method'=>'POST','autocomplete'=>'off'))!!}
	{{Form::token()}}

	<input type="text" name="asg" value={{$asignacion}}  hidden>
	<input type="text" name="nombreMateria" value={{$nMateria}} hidden>
	<input type="text" name="nombreTurno" value={{$nTurno}} hidden>
	<input type="text" name="nombreGrado" value={{$nGrado}} hidden>
	<input type="text" name="nombreSeccion" value={{$nSeccion}} hidden>
	

	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" 
				aria-label="Close">
                     <span aria-hidden="true">×</span>
                </button>
                <h2 class="modal-title">Nueva Evaluacion para {{$nMateria}}</h2 >
				<h3 class="modal-title">{{$nGrado}} "{{$nSeccion}}" {{$nTurno}}</h3>
				<h4>Seleccione los parametros para la nueva evaluacion <i class="fa fa-info-circle" aria-hidden="true" href="#" data-toggle="tooltip" data-placement="right" title="Defina una evaluacion y asignela a un trimestre y actividad en especifico"></i></h4>
			</div>
			<div class="modal-body">
				

	<div class="form-group col-md-4">
				<label for="">Nombre Evaluacion</label>
				{!! Form::text('nombreEvaluacion', null, ['class' => 'form-control' , 'required' => 'required', 'placeholder'=>'Nombre de Evaluacion', 'autofocus'=>'on']) !!}
			</div>


	<div class="form-group col-md-4">
		<div class="form-group">
						<label>Trimestre</label>
						
						{!!Form::select('id_trimestre',$trimestres,null,['id'=>'trimes','class'=>'form-control'])!!}
		</div>
    </div>

               

                <div class="form-group col-md-4">
						<div class="form-group">
						<label>Actividades</label>
						{!!Form::select('id_actividad',['placeholder'=>'Selecciona un trimestre'],null,['id'=>'activi','class'=>'form-control'])!!}
						</div>
				</div>

                  <div class="form-group col-md-4">
						<div class="form-group">
						<label>Porcentaje</label>
						{!! Form::number('porcentaje', null, ['class' => 'form-control' , 'placeholder'=>'Introduza el porcentaje de la nota', 'autofocus'=>'on','max'=>100]) !!}
						</div>
				</div>

			

               
                


			<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>	
				<button type="submit" class="btn btn-primary">Guardar</button>
			</div>
		</div>
	</div>
	{{Form::Close()}}

</div>

