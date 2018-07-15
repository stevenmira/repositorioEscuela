@extends ('layouts.maestro')
@section ('contenido')

    <style type="text/css">
        .polig{
            width:10px;
            height:10px;
            font-family:arial;
            font-weight:bold;
            color:#EEEEEE;
            border: 1px solid #555;
            display: inline;
            margin: 11px;
        }
        @page{
            margin-top: 5.0mm;
            margin-left: 5.0mm;
            margin-right: 5.0mm;
            margin-bottom: 5.0mm;

        }
        th{
            text-align: center;
            font-size: 1.0em;
        }

        td{
            font-size: 1.0em;
            text-decoration: bold;
            padding: 0px;
            margin: 0px;
        }
    </style>

    <div><b>INFORME DE EVALUACIÓN<span align=center><b></b></span></b></div>
    <div>
        <h3>{{$estudiante->nombre}} {{$estudiante->apellido}}</h3>
        <p>{{$grado->nombre}} "{{$seccion->nombre}}" {{$turno->nombre}}</p>
    </div>
    <div class="table-responsive"><table style="border-collapse: collapse; width: 100%;">
        <thead>
            <tr>
                <th align="center" rowspan="9" colspan="1" style="border: 1px solid #333;"><span style="font-size: 0.7em;"><b>ASIGNATURAS</b></span></th>
                <td align="center" style="border: 1px solid #333;" colspan="5"><span style="font-size: 0.7em;"><b>I TRIMESTRE</b></span></td>
                <td align="center" style="border: 1px solid #333;" colspan="5"><span style="font-size: 0.7em;"><b>II TRIMESTRE</b></span></td>
                <td align="center" style="border: 1px solid #333;" colspan="5"><span style="font-size: 0.7em;"><b>III TRIMESTRE</b></span></td>
                <th align="center" rowspan="4" style="border: 1px solid #333;"><span style="font-size: 0.7em;"><b>PROMEDIO<br>FINAL</b></span></th>
                <th align="center" rowspan="4" style="border: 1px solid #333;"><span style="font-size: 0.7em;"><b>RESULTADO</b></span></th>
            </tr>
            <tr>
                <td align="center" style="border: 1px solid #333;" colspan="4"><span style="font-size: 0.7em;"><b>ACTIVIDADES</b></span></td>
                <td align="center" style="border: 1px solid #333;" rowspan="3"><span style="font-size: 0.7em;"><b>PRO-<br>MEDIO</b></span></td>
                <td align="center" style="border: 1px solid #333;" colspan="4"><span style="font-size: 0.7em;"><b>ACTIVIDADES</b></span></td>
                <td align="center" style="border: 1px solid #333;" rowspan="3"><span style="font-size: 0.7em;"><b>PRO-<br>MEDIO</b></span></td>
                <td align="center" style="border: 1px solid #333;" colspan="4"><span style="font-size: 0.7em;"><b>ACTIVIDADES</b></span></td>
                <td align="center" style="border: 1px solid #333;" rowspan="3"><span style="font-size: 0.7em;"><b>PRO-<br>MEDIO</b></span></td>
            </tr>
            <tr>
                <td align="center" style="border: 1px solid #333;" rowspan="2"><span style="font-size: 0.7em;"><b>1</b><br>ACTIVIDAD<br>INTEGRADORA<br><b>30%</b></span></td>
                <td align="center" style="border: 1px solid #333;" colspan="2"><span style="font-size: 0.7em;"><b>2</b></span></td>
                <td align="center" style="border: 1px solid #333;" rowspan="2"><span style="font-size: 0.7em;"><b>3</b><br>PRUEBA<br>OBJETIVA<br><b>40%</b></span></td>
                <td align="center" style="border: 1px solid #333;" rowspan="2"><span style="font-size: 0.7em;"><b>1</b><br>ACTIVIDAD<br>INTEGRADORA<br><b>30%</b></span></td>
                <td align="center" style="border: 1px solid #333;" colspan="2"><span style="font-size: 0.7em;"><b>2</b></span></td>
                <td align="center" style="border: 1px solid #333;" rowspan="2"><span style="font-size: 0.7em;"><b>3</b><br>PRUEBA<br>OBJETIVA<br><b>40%</b></span></td>
                <td align="center" style="border: 1px solid #333;" rowspan="2"><span style="font-size: 0.7em;"><b>1</b><br>ACTIVIDAD<br>INTEGRADORA<br><b>30%</b></span></td>
                <td align="center" style="border: 1px solid #333;" colspan="2"><span style="font-size: 0.7em;"><b>2</b></span></td>
                <td align="center" style="border: 1px solid #333;" rowspan="2"><span style="font-size: 0.7em;"><b>3</b><br>PRUEBA<br>OBJETIVA<br><b>40%</b></span></td>
            </tr>
            <tr>
                <td align="center" style="border: 1px solid #333;"><span style="font-size: 0.7em;">CUADERNO<br><b>20%</b></span></td>
                <td align="center" style="border: 1px solid #333;"><span style="font-size: 0.7em;">PROYECTO<br><b>10%</b></span></td>
                <td align="center" style="border: 1px solid #333;"><span style="font-size: 0.7em;">CUADERNO<br><b>20%</b></span></td>
                <td align="center" style="border: 1px solid #333;"><span style="font-size: 0.7em;">PROYECTO<br><b>10%</b></span></td>
                <td align="center" style="border: 1px solid #333;"><span style="font-size: 0.7em;">CUADERNO<br><b>20%</b></span></td>
                <td align="center" style="border: 1px solid #333;"><span style="font-size: 0.7em;">PROYECTO<br><b>10%</b></span></td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td align="left" style="border: 1px solid #333;" height=1.5mm><span>Lenguaje y Literatura</span></td>
                <td align="center" style="border: 1px solid #333;">{{$na1t1m1}}</td>
                <td align="center" style="border: 1px solid #333;">{{$na2t1m1}}</td>
                <td align="center" style="border: 1px solid #333;">{{$na3t1m1}}</td>
                <td align="center" style="border: 1px solid #333;">{{$na4t1m1}}</td>
                <td align="center" style="border: 1px solid #333;">{{$prom_t1m1}}</td>
                <td align="center" style="border: 1px solid #333;">{{$na1t2m1}}</td>
                <td align="center" style="border: 1px solid #333;">{{$na2t2m1}}</td>
                <td align="center" style="border: 1px solid #333;">{{$na3t2m1}}</td>
                <td align="center" style="border: 1px solid #333;">{{$na4t2m1}}</td>
                <td align="center" style="border: 1px solid #333;">{{$prom_t2m1}}</td>
                <td align="center" style="border: 1px solid #333;">{{$na1t3m1}}</td>
                <td align="center" style="border: 1px solid #333;">{{$na2t3m1}}</td>
                <td align="center" style="border: 1px solid #333;">{{$na3t3m1}}</td>
                <td align="center" style="border: 1px solid #333;">{{$na4t3m1}}</td>
                <td align="center" style="border: 1px solid #333;">{{$prom_t3m1}}</td>
                <td align="center" style="border: 1px solid #333;">{{$prom_final_m1}}</td>
                <td align="center" style="border: 1px solid #333;">{{$res_m1}}</td>
            </tr>
            <tr>
                <td align="left" style="border: 1px solid #333;"><span>Metemática</span></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
            </tr>
            <tr>
                <td align="left" style="border: 1px solid #333;"><span  style="font-size: 0.9em;">Ciencias Salud y Medio Ambiente</span></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
            </tr>
            <tr>
                <td align="left" style="border: 1px solid #333;"><span>Estudios Sociales</span></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
            </tr>
            <tr>
                <td align="left" style="border: 1px solid #333;"><span>Segundo Idioma (Inglés)</span></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
            </tr>
            <tr>
                <td align="left" style="border: 1px solid #333;"><span>Educación Física</span></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
            </tr>
            <tr>
                <td align="left" style="border: 1px solid #333;"><span>Moral y Civica</span></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
            </tr>
            <tr>
                <td align="left" style="border: 1px solid #333;"><span>Informática</span></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
                <td align="center" style="border: 1px solid #333;"></td>
            </tr>
        </tbody>
    </table></div><br><br>
    
        <div><b>Nota: Calificación minima para aprobar una Asignatura 5.0 (cinco), cualquier alteración anula esta libreta.</b></div>

@endsection


