@extends ('layouts.admin')
@section ('contenido')
<div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">
        @if (Session::has('delete'))
            <p class="alert alert-danger">{{ Session::get('delete')}}</p>
        @endif 
        @if (Session::has('create'))
            <p class="alert alert-success">{{ Session::get('create')}}</p>
        @endif 
    </div>
</div>

<div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-condensed table-hover">
                    <thead>
                        <tr class="success">
                            <th colspan="12">
                                <h4>
                                <p class="col-md-offset-5 col-sm-offset-5 col-xs-offset-2 col-lg-offset-5">Record Laboral <a title="Agregar Record" href="{{URL::action('MaestroTrabajoController@edit', $id_hoja)}}" class="btn btn-success col-md-offset-7 col-sm-offset-5 col-xs-offset-2 col-lg-offset-5"><i class="fa fa-fw -square -circle fa-plus-square"></i> Agregar</a></p>
                                </h4>
                            </th>
                        </tr>
                        <tr class="info">
                            <th>No.</th>
                            <th>Cargo</th>
                            <th>Lugar</th>
                            <th>Tiempo</th>
                            <th>Copia</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <?php $cont = 1; ?>
                   @foreach ($trabajos as $ma)
                    <tr>
                        <td>{{ $cont }}</td>
                        <td>{{ $ma->cargo }}</td>
                        <td>{{ $ma->lugar }}</td>
                        <td>{{ $ma->tiempo }}</td>
                        <td>
                            <img src="{{asset('imagenes/maestros/trabajos/'.$ma->copia)}}" alt="{{ $ma->copia }}" height="50px" width="50px" class="img-thumbnail" onclick="javascript:this.width=400;this.height=400" ondblclick="javascript:this.width=50;this.height=50">
                        </td>
                        <td style="width: 200px;">
                            <a href="{{URL::action('MaestroTrabajoController@show',$ma->id_record)}}"><button class="btn btn-warning">Ver</button></a>
                            <a href="" data-target="#modal-delete-{{$ma->id_record}}" data-toggle="modal"><button class="btn btn-danger">Eliminar</button></a>
                        </td>
                        <?php $cont=$cont+1; ?>
                    </tr>
                    @include('docente.trabajos.modal')
                    @endforeach
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="float: left;">
            <p style="text-align:center;"><b>{{$maestro->nombre}} {{$maestro->apellido}}</b></p>
            <p style="text-align:center;">(Docente)</p>
        </div>

        <a href="{{URL::action('HojaVidaController@index')}}" class="btn btn-danger btn-lg col-md-offset-2"><i class="fa fa-chevron-left" aria-hidden="true"></i> Atrás</a>
    </div>

@endsection


