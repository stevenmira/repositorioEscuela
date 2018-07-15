<div class="modal fade modal-slide-in-right" aria-hidden="true"
role="dialog" tabindex="-1" id="modal-delete-{{$es->id_falta}}-{{$id}}">
{{Form::Open(array('action'=>array('FaltaController@destroy',$es->id_falta),'method'=>'delete'))}}
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" 
				aria-label="Close">
                     <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Eliminar Falta</h4>
			</div>
			<div class="modal-body">
				<p>Desea Eliminar la inasistencia de <b>{{ $es->apellido }},{{ $es->nombre }}</b> del dia <b>{{$es->fecha_falta}}</b></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				<button type="submit" class="btn btn-primary">Confirmar</button>
			</div>
		</div>
	</div>

{{Form::Close()}}
