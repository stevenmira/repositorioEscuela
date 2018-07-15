<div class="modal fade modal-slide-in-right" aria-hidden="true"
role="dialog" tabindex="-1" id="modal-create2">
	{!!Form::open(array('url'=>'detalle_consulta','method'=>'POST','autocomplete'=>'off'))!!}
	{{Form::token()}}

	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" 
				aria-label="Close">
                     <span aria-hidden="true">×</span>
                </button>

                <h4 class="modal-title" style="text-align: center;"> Registro de Conducta Trimestre II</h4>
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
                                            <th>Aspectos</th>
                                            <th>Criterio</th>
                                        </tr>

                                        @foreach($aspectos as $asp)
                                        <tr class="warning">
                                            <td style="width: 400px;">{{ $asp->nombre }}</td>
                                            <td>{{ Form::select('nombre', $criterios->pluck('nombre','id_criterioconducta'), null, ['class'=>'form-control']) }}</td>
                                        </tr>
                                        @endforeach
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