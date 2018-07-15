@extends('layouts.admin')
@section('contenido')

<fieldset class="well the-fieldset" style="border:6px groove #ccc; background:#F8ECE0;">

	<div class="row">
    
        <div class="timeline-centered">


	        <article class="timeline-entry">

	            <div class="timeline-entry-inner">

	                <div class="timeline-icon bg-secondary">
	                    <i class="entypo-suitcase"></i>
	                </div>

	                <div class="timeline-label">
	                    <h2><a href="#">Capacitación Realizada</a></h2>
	                    
					        <div class="row">
					            <div class="col-sm-12 col-md-12">
					            	<aside class="col-md-3">
					                	<b>Docente</b><h5>{{$maestro->nombre}} {{$maestro->apellido}}</h5>
					                </aside>
					            	<aside class="col-md-3">
					                	<b>Año</b><h5>{{$capacitacion->anio}}</h5>
					                </aside>
					                <aside class="col-md-3">
					                	<b>Nombre</b><h5>{{$capacitacion->nombre}}</h5>
					                </aside>
					                <aside class="col-md-3">
					                	<b>Horas</b><h5>{{$capacitacion->horas}}</h5>
					                </aside>  
					            </div>
					            <div class="col-sm-12 col-md-12 col-xs-12 col-lg-12">
					            	 <aside class="col-sm-12 col-md-12 col-xs-12 col-lg-12">
					                	<b>Copia</b>
					                	<h5>
					                		<figure>
				       							@if(($capacitacion->copia)!="")
				       								<img src="{{asset('imagenes/maestros/capacitaciones/'.$capacitacion->copia)}}">
				       							@else
				       								<img src="{{asset('imagenes/maestros/capacitaciones/no_found.png')}}">
				       							@endif
					                		</figure>
					                		{{$capacitacion->copia}}
					                	</h5>
					                </aside>
					            </div>
					        </div>
	                </div>
	            </div>
	        </article>

   		 </div>
   		 <a href="{{ url('docente/capacitaciones/lista', ['id' => $capacitacion->id_hoja]) }}" class="btn btn-danger btn-lg  col-md-offset-10"><i class="fa fa-chevron-left" aria-hidden="true"></i> Atrás</a>
    
	</div>

</fieldset>




@push('scripts')

@endpush
@endsection

