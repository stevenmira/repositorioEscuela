@extends ('layouts.admin')
@section ('contenido')
<div class="row">
	<div class="col-md-6 col-sm-6 col-xs-12">
		@if (Session::has('found'))
			<p class="alert alert-danger">{{ Session::get('found')}}</p>
		@endif
	</div>
</div>

<section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner" style="text-align: center;">
                <font face="Comic Sans MS,arial,verdana">Puedes realizar las búsquedas por el <b style="color: black;"> Nombre Completo</b> o <b style="color: black;">Parcial </b> del estudiante</font>
            </div>
            <div class="icon">
            </div>
            <a href="#" class="small-box-footer">Criterios de Búsqueda</a>
          </div>
        </div>


        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner" style="text-align: center;">
                <font face="Comic Sans MS,arial,verdana">Puedes realizar las búsquedas por el <b style="color: black;"> Apellido Completo</b> o <b style="color: black;"> Parcial </b> del estudiante</font>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="#" class="small-box-footer">Criterios de Búsqueda</a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner" style="text-align: center;">
              <font face="Comic Sans MS,arial,verdana">Puedes realizar las búsquedas por sexo <b style="color: black;"> M </b> o <b style="color: black;"> F </b>, o bien por <b style="color: black;"> NIE </b> del estudiante</font>
            </div>
            <div class="icon">
            </div>
            <a href="#" class="small-box-footer">Criterios de Búsqueda</a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner" style="text-align: center;">
              <font face="Comic Sans MS,arial,verdana">Puedes realizar las búsquedas por el estado <b style="color: black;">Activo</b> o <b style="color: black;">Inactivo</b> de la matricula</font>
            </div>
            <div class="icon">
            </div>
            <a href="#" class="small-box-footer">Criterios de Búsqueda</a>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->

	<div class="row">
		<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
			@include('expediente.matricula2.search')
		</div>
	</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead>
					<tr class="warning">
		                <th colspan="12">
		                    <h3 style="text-align: center;"><b>Matriculas de Estudiantes</b></h3>
		                    <p style="text-align: center;"> ( General )</p>
		                </th>
		            </tr>
					<th>Fecha Matricula</th>
					<th>NIE</th>
					<th>Nombre</th>
					<th>Apellido</th>
					<th>Foto</th>
					<th>Opciones</th>
				</thead>
               @foreach ($matriculas as $ma)
				<tr>
					<td>{{ $ma->fechamatricula }}</td>
					<td>{{ $ma->nie }}</td>
					<td>{{ $ma->nombre }}</td>
					<td>{{$ma->apellido}}</td>
					<td>
						<img src="{{asset('imagenes/alumnos/'.$ma->fotografia)}}" alt="{{ $ma->fotografia}}" height="50px" width="50px" class="img-thumbnail" onclick="javascript:this.width=150;this.height=150" ondblclick="javascript:this.width=50;this.height=50">
					</td>
					<td>
						<a href="{{URL::action('Matricula2Controller@edit',$ma->id_matricula)}}"><button class="btn btn-success">Nueva</button></a>
						<a href="{{URL::action('MatriculaController@show',$ma->id_matricula)}}"><button class="btn btn-warning">Ver</button></a>
						<a href="{{URL::action('MatriculaController@edit',$ma->id_matricula)}}"><button class="btn btn-info">Editar</button></a>

						@if($ma->estado == 'Activo')
                         <a href="" data-target="#modal-delete-{{$ma->id_matricula}}" data-toggle="modal"><button class="btn btn-danger">Eliminar</button></a>
                        @else
                        <a href="" data-target="#modal-delete-{{$ma->id_matricula}}" data-toggle="modal"><button class="btn btn-primary">Activar</button></a>
                        @endif
					</td>
				</tr>
				@include('expediente.matricula2.modal')
				@endforeach
			</table>
		</div>
		{{$matriculas->render()}}
	</div>
</div>
</section>
@endsection