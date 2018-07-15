@extends ('layouts.maestro')
@section ('contenido')

<div class="row">
        <div class="col-lg-12">
         <h3 style="color: #00695c;"><b>Estudiante: </b>  </h3>
         
        </div>
    </div> 

<div class="section">

    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
          @if (Session::has('message'))
            <p class="alert alert-danger">{{ Session::get('message')}}</p>
          @endif
        </div>
    </div>



<div class="col-md-12">
    <div class="form-group col-md-3">
        <div class="form-group">
          <label>Agregar  Inasistencia</label>
          
        </div>
      </div>
      </div>





    
</div> 

@endsection


