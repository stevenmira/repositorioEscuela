<center><b><font size=5>Centro Escolar</font></b></center>
<center><b><font size=5>"Jardines de la Sabana"</font></b></center>
<center><b><font size=1>Final Calle La Sabana y Av. "D"</font></b></center>
<center><b><font size=1>Ciudad Merliot, Santa Tecla</font></b></center>
<center><img src="images/logo.png"></center>
<center><div>
	<b><font size=4 >Grado: </font></b>
	<font size=4 >   {{ $data[0]->grado }}   </font>
	<b><font size=4 >Seccion: </font></b>
    <font size=4 >   {{ $data[0]->seccion }}   </font>
    <b><font size=4 >Turno: </font></b>
    <font size=4 >   {{ $data[0]->turno }}   </font>
</div></center>
<h1></h1>
<center><div>
	<font size=4>Listado de alumnos</font>
</div></center>
<center><div>
	<font size=4>Matricula año: {{$ys}}</font>
</div></center>

<h1></h1>

<table border="1px" width=100% style="border-collapse: collapse">
	<thead>
		<tr>
			<th>N°</th>
			<th>Apellido</th>
			<th>Nombre</th>			
		</tr>
	</thead>
	<tbody>
		@foreach($data as $indice=>$result)
			<tr>
				<td>{{ $indice+1 }}</td>
				<td>{{ $result->apellidoEstudiante }}</td>
				<td>{{ $result->nombreEstudiante }}</td>
			</tr>
		@endforeach

	</tbody>
</table>