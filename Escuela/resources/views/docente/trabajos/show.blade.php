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
	                    <h2><a href="#">Record Laboral</a></h2>
	                    
					        <div class="row">
					            <div class="col-sm-12 col-md-12">
					            	<aside class="col-md-3">
					                	<b>Docente</b><h5>{{$maestro->nombre}} {{$maestro->apellido}}</h5>
					                </aside>
					            	<aside class="col-md-3">
					                	<b>Cargo</b><h5>{{$trabajo->cargo}}</h5>
					                </aside>
					                <aside class="col-md-3">
					                	<b>Lugar</b><h5>{{$trabajo->lugar}}</h5>
					                </aside>
					                <aside class="col-md-3">
					                	<b>Tiempo</b><h5>{{$trabajo->tiempo}}</h5>
					                </aside>  
					            </div>
					            <div class="col-sm-12 col-md-12 col-xs-12 col-lg-12">
					            	 <aside class="col-sm-12 col-md-12 col-xs-12 col-lg-12">
					                	<b>Copia</b>
					                	<h5>
					                		<figure>
				       							@if(($trabajo->copia)!="")
				       								<img src="{{asset('imagenes/maestros/trabajos/'.$trabajo->copia)}}">
				       							@else
				       								<img src="{{asset('imagenes/maestros/trabajos/no_found.png')}}">
				       							@endif
					                		</figure>
					                		{{$trabajo->copia}}
					                	</h5>
					                </aside>
					            </div>
					        </div>
	                </div>
	            </div>

	        </article>


   		 </div>
   		 <a href="{{ url('docente/trabajos/lista', ['id' => $trabajo->id_hoja]) }}" class="btn btn-danger btn-lg col-md-offset-10"><i class="fa fa-chevron-left" aria-hidden="true"></i> Atrás</a>
    
	</div>

</fieldset>
@endsection

