@extends ('layouts.maestro')
@section ('contenido')

     

    <div class="col-md-12">
        <div class="row text-center pad-top">
        <fieldset style="background-color:#929fba;">     
            <legend>CURSOS
            <p>(Selecciona el curso)</p>
            </legend>

       
            @if(!is_null($cursos))
	            @foreach($cursos as $asm)
	            <a href="{{ url('curso/alumnos', ['id' => $asm->id_detalleasignacion]) }}" >
	              <div class="boot col-lg-4 col-md-4 col-sm-4 col-xs-12">
	                  <div class="div-square">
	                       <i class="fa fa-edit fa-5x"></i>
	                 		 <h4>{{$asm->nombreGrado}}</h4>
	                 		 <h4>{{$asm->nombreSeccion}}</h4>
	                 		 <h4>{{$asm->nombreTurno}}</h4>
	                 		 <h4>{{$query3}}</h4>            
	                  </div>     
	              </div>
	              </a>
	            @endforeach
            @endif

            @if($cursos == null)
	              <div class="boot col-lg-4 col-md-4 col-sm-4 col-xs-12">
	                  <div class="div-square">
	                       <i class="fa fa-edit fa-5x"></i>
	                 		 <h4>No hay registros</h4>           
	                  </div>     
	              </div>
	              
            @endif
          </fieldset>
        </div>
    </div>   

@endsection


