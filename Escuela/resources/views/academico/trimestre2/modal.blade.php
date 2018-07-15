<div class="modal fade modal-slide-in-right" aria-hidden="true"
role="dialog" tabindex="-1" id="modal-create2">
	{!!Form::open(array('url'=>'academicos','method'=>'POST','autocomplete'=>'off'))!!}
	{{Form::token()}}

	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" 
				aria-label="Close">
                     <span aria-hidden="true">×</span>
                </button>

                <h4 class="modal-title" style="text-align: center;"> Registro de Aspectos Academicos </h4>
                <h4 class="modal-title" style="text-align: center;"> Trimestre II</h4>
                <h4 class="modal-title"> </h4 >
				 <h3 style="color: #00695c;"><b> Alumno(a):{{ $es->nombre }}  {{ $es->apellido }}</b></h4>
			</div>

	     <div class="modal-body">
	        <div class="form-group "> 
		           <div class="form-group col-md-20">

		           		<h5><i class="fa fa-info-circle" aria-hidden="true" href="#" data-toggle="tooltip" data-placement="right" title="Defina una conducta"></i> Seleccione los criterio ... </h5>

                         <div class="row">
                            <aside class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-striped  table-condensed">
                                        <tr class="info">
                                            <th>Aspectos Academicos</th>
                                            <th>¿Cumple?</th>
                                        </tr>
                                        <tr>
                                        	<td>1. Cumple con sus tareas</td>
                                        	<td><input type="checkbox" name=""></td>
                                        </tr>
                                       
                                        <tr>
                                        	<td>2. Respeta a sus maestros y compañeros</td>
                                        	<td><input type="checkbox" name=""></td>
                                        </tr>

                                        <tr>
                                        	<td>3. Práctica habitos higiénicos</td>
                                        	<td><input type="checkbox" name=""></td>
                                        </tr>

                                        <tr>
                                        	<td>4. Es responsable y solidario</td>
                                        	<td><input type="checkbox" name=""></td>
                                        </tr>

                                        <tr>
                                        	<td>5. Es dinámico y participativo</td>
                                        	<td><input type="checkbox" name=""></td>
                                        </tr>

                                        <tr>
                                        	<td>6. Salió de clases sin permiso</td>
                                        	<td><input type="checkbox" name=""></td>
                                        </tr>

                                        <tr>
                                        	<td>7. No Coopera con la limpieza</td>
                                        	<td><input type="checkbox" name=""></td>
                                        </tr>

                                        <tr>
                                        	<td>8. No cumple con las tareas</td>
                                        	<td><input type="checkbox" name=""></td>
                                        </tr>

                                        <tr>
                                        	<td>9. Llego tarde a la institución o clase</td>
                                        	<td><input type="checkbox" name=""></td>
                                        </tr>

                                        <tr>
                                        	<td>10. No atendio el toque de timbre</td>
                                        	<td><input type="checkbox" name=""></td>
                                        </tr>
                                    </table>
                                </div>
                            </aside>
                        </div>
                 </div>
            </div>
        </div>
           	
			
           	
			<div class="modal-footer">
			
            
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-primary">Guardar</button>
			</div>
		</div>
	</div>
	{{Form::Close()}}

</div>