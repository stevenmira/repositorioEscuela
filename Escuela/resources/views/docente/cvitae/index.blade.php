@extends ('layouts.admin')
@section ('contenido')
<div class="section">

    
    
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
        @if (Session::has('unicidad'))
            <p class="alert alert-danger">{{ Session::get('unicidad')}}</p>
        @endif
        
        @if (Session::has('message'))
            <p class="alert alert-danger">{{ Session::get('message')}}</p>
        @endif

        @if (Session::has('update'))
            <p class="alert alert-info">{{ Session::get('update')}}</p>
        @endif  

        @if (Session::has('create'))
            <p class="alert alert-success">{{ Session::get('create')}}</p>
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
                <font face="Comic Sans MS,arial,verdana">Puedes realizar las búsquedas por el <b style="color: black;"> Nombre Completo</b> o <b style="color: black;">Parcial </b> del docente</font>
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
                <font face="Comic Sans MS,arial,verdana">Puedes realizar las búsquedas por el <b style="color: black;"> Apellido Completo</b> o <b style="color: black;"> Parcial </b> del docente</font>
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
              <font face="Comic Sans MS,arial,verdana">Puedes realizar las búsquedas por sexo del docente<b style="color: black;"> M </b> o <b style="color: black;"> F </b>, o bien por el <b style="color: black;">NIP</b></font>
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
              <font face="Comic Sans MS,arial,verdana">Puedes realizar las búsquedas por el estado <b style="color: black;">Activo</b> o <b style="color: black;">Inactivo</b> de la hoja de vida</font>
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
            @include('docente.cvitae.search')
        </div>
    </div>


    <div class="table-responsive">
        <table class="table table-hover table-striped">
        <thead>
            <tr class="success">
                <th colspan="12">
                    
                    <h3 style="text-align: center;"><b>Listado de Docentes</b><a title="Agregar Nuevo Docente" href="cvitae/create"><button class="btn btn-success pull-right"><i class="fa fa-fw -square -circle fa-plus-square"></i> Nuevo</button></a></h3>
                    
                </th>
            </tr>
            <tr class="info">
                <th></th>
                <th>Nombre</th>
                <th>Fotográfia</th>
                <th>Estudios</th>
                <th>Capacitac.</th>
                <th>Trabajos</th>
                <th>Opciones</th>
            </tr>
            
        </thead>
            <tbody>
            @foreach ($hojas as $ho)
                <tr>
                    <td>
                        <a href="#"><i class="fa fa-chevron-right  fa-3x fa-fw"></i></a>
                    </td>
                    <td>
                        <h4>
                            <b>{{ $ho->apellido }}</b>
                        </h4>
                        <p>NIP: {{ $ho->nip }}</p>
                    </td>
                    <td>
                        <img src="{{asset('imagenes/maestros/fotos/'.$ho->fotografia)}}" alt="{{ $ho->fotografia}}" class="img-circle" width="60">
                    </td>
                    <td>
                        <a href="{{ url('docente/estudios/lista', ['id' => $ho->id_hoja]) }}"><button class="btn btn-success"><i class="fa fa-fw -square -circle fa-plus-square"></i> </button></a>
                    </td>
                    <td>
                        <a href="{{ url('docente/capacitaciones/lista', ['id' => $ho->id_hoja]) }}"><button class="btn btn-success"><i class="fa fa-fw -square -circle fa-plus-square"></i></button></a>
                    </td>
                    <td>
                        <a href="{{ url('docente/trabajos/lista', ['id' => $ho->id_hoja]) }}"><button class="btn btn-success"><i class="fa fa-fw -square -circle fa-plus-square "></i></button></a>
                    </td>
                    <td>
                        <a href="{{URL::action('HojaVidaController@edit',$ho->id_hoja)}}"><button class="btn btn-info">Editar</button></a>
                        <a href="{{URL::action('HojaVidaController@show',$ho->id_hoja)}}"><button class="btn btn-warning">Ver</button></a>
                         <a href="" data-target="#modal-delete-{{$ho->id_hoja}}" data-toggle="modal"><button class="btn btn-danger">Eliminar</button></a>
                    </td>
                </tr>
            </tbody>
            @include('docente.cvitae.modal')
            @endforeach
        </table>
    </div>
    {{$hojas->render()}}
</div>

</section>
@endsection


