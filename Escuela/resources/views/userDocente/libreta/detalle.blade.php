@extends ('layouts.maestro')
@section ('contenido')
<div class="col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="panel with-nav-tabs panel-default">
                <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab1default" data-toggle="tab">TRIMESTRE I</a></li>
                            <li class=""><a href="#tab2default" data-toggle="tab">TRIMESTRE II</a></li>
                            <li class=""><a href="#tab3default" data-toggle="tab">TRIMESTRE III</a></li>
                        </ul>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="tab1default"> <!-- Inicio del tab1 -->
                         <h4 style="text-align: center;">{{$estudiante->nombre}} {{$estudiante->apellido}} </h4> 
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center info">Actividad</th>
                                                @foreach($e_a1t1 as $acts)
                                                    <th class="text-center info">{{$acts->nombreEvaluacion}} ({{$acts->pEval}}%)</th>
                                                @endforeach
                                                <th class="text-center info">Prom.</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center warning" style="width: 150px;"><p>Integradora</p></td>
                                                <?php for ($i = 0; $i < $num1; $i++) { ?>
                                                <td class="text-center warning">
                                                    <p> {{$array1[$i]}} </p>
                                                </td>
                                                <?php } ?>
                                                <td class="text-center danger" style="width: 100px;">{{$na1t1}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center info">Actividad</th>
                                                @foreach($e_a2t1 as $acts)
                                                    <th class="text-center info">{{$acts->nombreEvaluacion}} ({{$acts->pEval}}%)</th>
                                                @endforeach
                                                <th class="text-center info">Prom.</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center warning" style="width: 150px;"><p>Cuaderno</p></td>
                                                <?php for ($i = 0; $i < $num2; $i++) { ?>
                                                <td class="text-center warning">
                                                    <p> {{$array2[$i]}} </p>
                                                </td>
                                                <?php } ?>
                                                <td class="text-center danger" style="width: 100px;">{{$na2t1}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center info">Actividad</th>
                                                @foreach($e_a3t1 as $acts)
                                                    <th class="text-center info">{{$acts->nombreEvaluacion}} ({{$acts->pEval}}%)</th>
                                                @endforeach
                                                <th class="text-center info">Prom.</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center warning" style="width: 150px;"><p>Proyecto</p></td>
                                                <?php for ($i = 0; $i < $num3; $i++) { ?>
                                                <td class="text-center warning">
                                                    <p> {{$array3[$i]}} </p>
                                                </td>
                                                <?php } ?>
                                                <td class="text-center danger" style="width: 100px;">{{$na3t1}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center info">Actividad</th>
                                                @foreach($e_a4t1 as $acts)
                                                    <th class="text-center info">{{$acts->nombreEvaluacion}} ({{$acts->pEval}}%)</th>
                                                @endforeach
                                                <th class="text-center info">Prom.</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center warning" style="width: 150px;"><p>Prueba Objetiva</p></td>
                                                <?php for ($i = 0; $i < $num4; $i++) { ?>
                                                <td class="text-center warning">
                                                    <p> {{$array4[$i]}} </p>
                                                </td>
                                                <?php } ?>
                                                <td class="text-center danger" style="width: 100px;">{{$na4t1}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-md-3 col-md-offset-5">
                                <div class="input-group">

                                        <span class="input-group-btn">
                                            <button id='calcular' class="btn btn-primary"> <i class="fa fa-chevron-left" aria-hidden="true"></i> Atras</button>
                                        </span>
                                        <input type="number" disabled class="form-control" name="searchText" value="{{$prom_trim}}">
                                </div>
                            </div>
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

@endsection


