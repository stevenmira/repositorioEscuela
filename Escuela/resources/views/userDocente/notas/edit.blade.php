@extends ('layouts.maestro')
@section ('contenido')
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        @if (count($errors)>0)
            <div class="alert alert-danger">
                <ul>
                <p>Correcciones:</p>
                <?php $cont = 1; ?>
                @foreach ($errors->all() as $error)
                    <li>{{$cont}}. {{$error}}</li>
                    <?php $cont=$cont+1; ?>
                @endforeach
                </ul>
            </div>
            @endif
    </div>
</div>

@if(Session::has('message'))
    <div class="alert alert-success alert-dismissible" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      {{Session::get('message')}}
    </div>
@endif

{!!Form::open(array('url'=>'agregar/notas/'.$id.'/'.$ma.'/'.$trim,'method'=>'POST','autocomplete'=>'off'))!!}
{{Form::token()}}

<div class="col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="panel with-nav-tabs panel-default">
                <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab1default" data-toggle="tab">{{$materia->nombre}}</a></li>
                        </ul>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="tab1default"> <!-- Inicio del tab1 -->
                            <div class="container">
                                <div class="col-lg-12">
                                    <h3 style="color: #00695c; font-family: all;">Actividad Integradora</h3> 
                                </div>
                            </div> 
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                @foreach($actividades1 as $acts)
                                                    <th class="text-center info">{{$acts->nombreEvaluacion}} ({{$acts->pEval}}%)</th>
                                                @endforeach
                                                <th class="text-center info">Prom.</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <?php for ($w = 0; $w < $num1; $w++) { ?>
                                                <td class="text-center warning">
                                                    {!! Form::number('items1[]', $array1[$w], [ 'min'=>0, 'max'=>10, 'required' => 'required', 'class' => 'form-control' , 'placeholder'=>'...']) !!}
                                                </td>
                                                <?php } ?>
                                                <td class="text-center danger" style="width: 100px;">
                                                {!! Form::number('p1', null, ['class' => 'form-control' , 'disabled'=>'true']) !!}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="container">
                                <div class="col-lg-12">
                                    <h3 style="color: #00695c; font-family: all;">Cuaderno</h3> 
                                </div>
                            </div> 
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                @foreach($cuadernos1 as $cuads)
                                                    <th class="text-center info">{{$cuads->nombreEvaluacion}} ({{$cuads->pEval}}%)</th>
                                                @endforeach
                                                <th class="text-center info">Prom.</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <?php for ($i = 0; $i < $num2; $i++) { ?>
                                                <td class="text-center warning">
                                                    {!! Form::number('items2[]', $array2[$i], [ 'min'=>0, 'max'=>10, 'required' => 'required', 'class' => 'form-control' , 'placeholder'=>'...']) !!}
                                                </td>
                                                <?php } ?>
                                                <td class="text-center danger" style="width: 100px;">
                                                {!! Form::number('p2', null, ['class' => 'form-control' , 'disabled'=>'true']) !!}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="container">
                                    <div class="col-md-4">
                                        <h3 style="color: #00695c; font-family: all;">Proyeto</h3> 
                                    </div>
                                </div> 
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                @foreach($proyectos1 as $proys)
                                                    <th class="text-center info">{{$proys->nombreEvaluacion}} ({{$proys->pEval}}%)</th>
                                                @endforeach
                                                <th class="text-cente info">Prom.</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <?php for ($i = 0; $i < $num3; $i++) { ?>
                                                <td class="text-center warning">
                                                    {!! Form::number('items3[]', $array3[$i], [ 'min'=>0, 'max'=>10, 'required' => 'required', 'class' => 'form-control' , 'placeholder'=>'...']) !!}
                                                </td>
                                                <?php } ?>
                                                <td class="text-center danger" style="width: 100px;">
                                                {!! Form::number('p3', null, ['class' => 'form-control' , 'disabled'=>'true']) !!}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="container">
                                    <div class="col-md-4">
                                    <h3 style="color: #00695c; font-family: all;">Prueba Obj.</h3> 
                                    </div>
                                </div> 
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                @foreach($pruebas1 as $prs)
                                                    <th class="text-center info">{{$prs->nombreEvaluacion}} ({{$prs->pEval}}%)</th>
                                                @endforeach
                                                <th class="text-center info">Prom.</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <?php for ($i = 0; $i < $num4; $i++) { ?>
                                                <td class="text-center warning">
                                                    {!! Form::number('items4[]', $array4[$i], [ 'min'=>0, 'max'=>10, 'required' => 'required', 'class' => 'form-control' , 'placeholder'=>'...']) !!}
                                                </td>
                                                <?php } ?>
                                                <td class="text-center danger" style="width: 100px;">
                                                {!! Form::number('p4', null, ['class' => 'form-control' , 'disabled'=>'true']) !!}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <a href="{{URL::action('MaestroUserController@index')}}" class="btn btn-danger pull-left col-md-offset-2">Cancelar</a>
                            <div class="col-md-2 col-md-offset-2">
                                <div class="input-group">
                                    <input type="number" disabled class="form-control" name="searchText" value="0.0">
                                    <span class="input-group-btn">
                                        <button id='calcular' class="btn btn-info">Calcular</button>
                                    </span>
                                </div>
                            </div>
                            <input type="submit" class="btn btn-primary col-md-offset-2" value="Guardar">
                        </div> <!-- Fin del tab1 -->

                        <div class="tab-pane fade" id="tab2default">Default 2</div>
                        <div class="tab-pane fade" id="tab3default">Default 3</div>
                        <div class="tab-pane fade" id="tab4default">Default 4</div>
                        <div class="tab-pane fade" id="tab5default">Default 5</div>
                        <div class="tab-pane fade" id="tab6default">Default 6</div>
                        <div class="tab-pane fade" id="tab7default">Default 7</div>
                        <div class="tab-pane fade" id="tab8default">Default 8</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{!!Form::close()!!}

@endsection