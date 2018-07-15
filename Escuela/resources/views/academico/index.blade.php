@extends ('layouts.maestro')
@section ('contenido')

<div class="row">
        <div class="col-lg-12">
         <h1>REGISTRAR ASPECTOS ACADEMICOS</h1>
         <p>(Puede registrar evaluacion de los aspectos de academicos)</p>   
        </div>
    </div>   

   <div class="col-md-12">
      <!-- TURNO MATUTINO  --> 
    <div class="col-md-6">
        <div class="row text-center pad-top">
        <fieldset >     
            <legend>MATUTINO</legend>

       
  @foreach($asig_gram as $gram)

            <a href="{{ url('academico/lista/estudiante', ['a1' => $gram->id_detalleasignacion]) }}" >
              <div class="boot col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <div class="div-square col-lg-4 col-md-4 col-sm-4 col-xs-4">
                       <i class="fa fa-list-alt fa-5x"></i>
                @if($gram->nombregrado !="")
                  <h4>{{$gram->nombregrado}}</h4>
                  <h4>"{{$gram->nombreseccion}}"</h4>
                @else
                   <h4>N/A</h4>
                @endif
                  <p4></p4>
                  <p4></p4>                  
                  </div>     
              </div>
              </a>

        
              @endforeach

          </fieldset>
        </div>
    </div>   


    <div class="col-md-6">
        <div class="row text-center pad-top">
        <fieldset >     
            <legend>VESPERTINO</legend>

           
  @foreach($asig_grav as $grav)

            <a href="{{ url('academico/lista/estudiante', ['a1' => $grav->id_detalleasignacion]) }}" >
              <div class="boot col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <div class="div-square col-lg-4 col-md-4 col-sm-4 col-xs-4">
                       <i class="fa fa-list-alt fa-5x"></i>
                @if($grav->nombregrado != "")
                  <h4>{{$grav->nombregrado}}</h4>
                  <h4>"{{$grav->nombreseccion}}"</h4>
                @else
                   <h4>N/A</h4>
                @endif
                  <p4></p4>
                  <p4></p4>                  
                  </div>     
              </div>
              </a>

        
              @endforeach

          </fieldset>
        </div>
    </div> 

       


</div>

<div class="col-md-12">

  {{$date}}  

</div>


@endsection