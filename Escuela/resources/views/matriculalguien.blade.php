<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Ficha Matricula {{$estudiante->apellido}} {{$estudiante->nombre}}</title>
	<style>
		body {
			font-family: Arial;
		}
	</style>
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
		table, th, td {
    		border: 1px solid black;
		}
</style>
	</style>
<link rel="stylesheet" type="text/css" href="css/app.css">
</head>
<body>
	<h4 align=center>CENTRO ESCOLAR JARIDINES DE LA SABANA</h4>
	<div align=center>Final Calle La Sabana y Avenida "D" Ciudad Merliot, Santa Tecla; telefono 2278-5614</div>
	<div align=center>FICHA INSCRIPCIÓN DE MATRICULA, AÑO {{$anio}}</div>
	<div align=center>Nombre alumno(a): 
		<u>&nbsp;&nbsp;&nbsp;&nbsp;{{$estudiante->nombre}}&nbsp;&nbsp;{{$estudiante->apellido}}&nbsp;&nbsp;&nbsp;&nbsp;</u>
	</div>
	<div align=center>GRADO:<u>&nbsp;&nbsp;{{$grado->nombre}}&nbsp;&nbsp;</u>    SECCION:<u>&nbsp;&nbsp;{{$seccion->nombre}}&nbsp;&nbsp;</u> TURNO:<u>&nbsp;&nbsp;{{$turno->nombre}}&nbsp;&nbsp;</u></div>
	<div align=center>DOCUMENTACIÓN QUE SE DEBE PRESENTAR AL MOMENTO DE MATRICULAR A SU HIJO(A)</h5>
	<table>
		<thead>
			<tr>
				<th align=center>DOCUMENTACIÓN PERSONAL DEL(A) ALUMNO(A)</th>
				<th align=center>SI</th>
				<th align=center>NO</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th> a) Partida de nacimiento (original y copia)</th>
				<th align="center">
					@if($matricula->presentapartida == 'SI')
					X
					@endif
				</th>
				<th align="center">
					@if($matricula->presentapartida == 'NO')
					X
					@endif
				</th>
			</tr>
			<tr>
				<th> b) Certificado de promoción del ultimo año anterior (original y copia)</th>
				<th align="center">
					@if($matricula->certificadoprom == 'SI')
					X
					@endif
				</th>
				<th align="center">
					@if($matricula->certificadoprom == 'NO')
					X
					@endif
				</th>
			</tr>
			<tr>
				<th> c) Tres fotografias tamaño cédula</th>
				<th align="center">
					@if($matricula->presentafotos == 'SI')
					X
					@endif
				</th>
				<th align="center">
					@if($matricula->presentafotos == 'NO')
					X
					@endif
				</th>
			</tr>
			<tr>
				<th> d) Constancia de <b>Buena conducta</b> (si procede de otra institución)</th>
				<th align="center">
					@if($matricula->constanciaconducta == 'SI')
					X
					@endif
				</th>
				<th align="center">
					@if($matricula->constanciaconducta == 'NO')
					X
					@endif
				</th>
			</tr>
			<tr>
				<th> e) Ha recibido educación inicial o Parvularia (solo para Primer Grado)</th>
				<th align="center">
					@if($matricula->educacioninicial == 'SI')
					X
					@endif
				</th>
				<th align="center">
					@if($matricula->educacioninicial == 'NO')
					X
					@endif
				</th>
			</tr>			
		</tbody>
	</table>
	<div align=center>DATOS DEL(A) ALUMNO(A)</div>
	<div align="justify">Dirección particular del(a) alumno(a) <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$estudiante->domicilio}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></div>
	<div align="justify">vive con:
		@if($matricula->vivecon == '')
			<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
		@else
			<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$matricula->vivecon}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
		@endif
	</div><br>
	
	<table>
		<tbody>
			<tr>
				<th>Nombre de la madre: 
					@if($detalleM->nombre == '')
						<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
					@else
						<u>&nbsp;&nbsp;&nbsp;&nbsp;{{$detalleM->nombre}}&nbsp;&nbsp;</u>
					@endif
					@if($detalleM->apellido == '')
						<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
					@else
						<u>&nbsp;&nbsp;{{$detalleM->apellido}}&nbsp;&nbsp;&nbsp;&nbsp;</u>
					@endif
				</th>
				<th>DUI No.
					@if($detalleM->dui == '')
						<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
					@else
						<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$detalleM->dui}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
					@endif

					<br>Ocupación. 
					@if($detalleM->ocupacion == '')
						<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></th>
					@else
						<u>&nbsp;&nbsp;&nbsp;&nbsp;{{$detalleM->ocupacion}}&nbsp;&nbsp;&nbsp;&nbsp;</u></th>
					@endif
			</tr>
			<tr>
				<th align="justify">Lugar de trabajo:
					@if($detalleM->lugardetrabajo == '')
						<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
					@else
						<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$detalleM->lugardetrabajo}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
					@endif
				</th>
				<th>Teléfono. 
					@if($detalleM->telefono == '')
						<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
					@else
						<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$detalleM->telefono}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
					@endif
				</th>
			</tr>
			<tr>
				<th>Nombre del padre: 
					@if($detalleP->nombre == '')
						<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
					@else
						<u>&nbsp;&nbsp;&nbsp;&nbsp;{{$detalleP->nombre}}&nbsp;&nbsp;</u>
					@endif
					@if($detalleP->apellido == '')
						<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
					@else
						<u>&nbsp;&nbsp;{{$detalleP->apellido}}&nbsp;&nbsp;&nbsp;&nbsp;</u>
					@endif
				</th>
				<th>DUI No. 
					@if($detalleP->dui == '')
						<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
					@else
						<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$detalleP->dui}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
					@endif

					<br>Ocupación. 
					@if($detalleP->ocupacion == '')
						<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></th>
					@else
						<u>&nbsp;&nbsp;&nbsp;&nbsp;{{$detalleP->ocupacion}}&nbsp;&nbsp;&nbsp;&nbsp;</u></th>
					@endif
				</th>
			</tr>
			<tr>
				<th>Lugar de trabajo: 
					@if($detalleP->lugardetrabajo == '')
						<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
					@else
						<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$detalleP->lugardetrabajo}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
					@endif
				</th>
				<th>Teléfono. 
					@if($detalleP->telefono == '')
						<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
					@else
						<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$detalleP->telefono}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
					@endif
				</th>
			</tr>
		</tbody>
	</table>
	<ul>
		<table>
		<div align="justify">
			<li>Persona a quien se debe de avisar en caso de emergencia: 
				@if($detalleC->nombre == '')
					<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
				@else
					<u>&nbsp;&nbsp;&nbsp;&nbsp;{{$detalleC->nombre}}&nbsp;&nbsp;{{$detalleC->apellido}}&nbsp;&nbsp;&nbsp;&nbsp;</u>
				@endif
				<b> favor dejar <u>teléfono de linea fija</u>:</b> 
				@if($detalleC->telefono == '')
					<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></li>
				@else
					<u>&nbsp;&nbsp;&nbsp;&nbsp;{{$detalleC->telefono}}&nbsp;&nbsp;&nbsp;&nbsp;</u></li>
				@endif
		</div>
		</table>
		<table>
		<div align="justify">
			<li>Centro Educativo de donde procede: 
				@if($matricula->ceprevio == '') 
					<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
				@else
					<u>&nbsp;&nbsp;&nbsp;&nbsp;{{$matricula->ceprevio}}&nbsp;&nbsp;&nbsp;&nbsp;</u>
				@endif
				teléfono. 
				@if($matricula->telefonoce == '') 
					<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
				@else
					<u>&nbsp;&nbsp;&nbsp;&nbsp;{{$matricula->telefonoce}}&nbsp;&nbsp;&nbsp;&nbsp;</u>
				@endif
			</li>
		</div>
		</table>	
		<div align="justify"s>
			<li>Anotar si padece de alguna enfermedad (para ayudarlo en caso de emergencia) 
				@if($estudiante->enfermedad == '')
					<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
				@else
					<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$estudiante->enfermedad}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
				@endif
			</li>
		</div>
		<div align="justify">
			<li>Autoriza usted a que su hijo(a) sea vacunado(a) en la institución, cuando son campañas del Ministerio de Salud Pública (MSP). SI 
			@if($estudiante->autorizavacuna == 'SI')
				<div class="polig" align="center" style="color: black;">X</div>  
			@else
				<div class="polig" >&nbsp;&nbsp;&nbsp;</div>
			@endif 
			NO 
			@if($estudiante->autorizavacuna == 'NO') 
				<div class="polig" align="center" style="color: black;">X</div>
			@else
				<div class="polig" >&nbsp;&nbsp;&nbsp;</div>
			@endif
			</li>
		</div>
	</ul>

	<div align="justify"><b>ACLARAMOS: </b>Que la seguridad de los niños que se van en microbús <b>es <u>responsabilidad del padre y del encargado del transporte</u>; y que los padres deben recoger a sus hijos e hijas puntualmente a la hora respectiva salida.</b></div>
	<div align="justify"><b>NOTA: </b><u>Prohibido el uso de celulares y accesorios portátiles</u></div>
	<div>HERMANOS(AS) ESTUDIANDO EN LA INSTITUCIÓN</div>
	<table>
		<thead>
			<tr>
				<th align=center width=150mm>Nombre de los(as) hermanos(as)</th>
				<th align=center>Grado</th>
				<th align=center>Sección</th>
				<th align=center>Turno</th>
			</tr>
		</thead>
		<tbody>
			@if($parientess == NULL)
				<tr>
					<th style="height: 20px">NO SE ENCONTRO</th>
					<th></th>
					<th></th>
					<th></th>
				</tr>
			@else
				@foreach ($parientess as $ma)
				<tr align="left">
					<td style="height: 20px">{{ $ma->nombre }} {{ $ma->apellido }}</td>
					<td>{{ $ma->grado }}</td>
					<td>{{ $ma->seccion }}</td>
					<td>{{ $ma->turno }}</td>
				</tr>
				@endforeach
			@endif
		</tbody>
	</table><br>
	<div style="width: 50%; float: left;" align="center"><b>______________________________________</b></div>
	<div style="width: 50%; float: right;" align="center"><b>______________________________________</b></div><br>
	<div style="width: 50%; float: left;" align="center"><b>Firma del padre/madre o encargado</b></div>
	<div style="width: 50%; float: right;" align="center"><b>Firma del maestro(a) de grado</b></div><br><br>
	<table>
		<thead>
			<tr>
				<th align=center>NIE</th>
				<th align=center>Sexo</th>
				<th align=center>Repite<br>grado</th>
				<th align=center>Hizo<br>Kinder</th>
				<th align=center>Discapacidad</th>
				<th align=center>Rural/<br>urbano</th>
				<th align=center>Edad</th>
				<th align=center>Fecha de<br>nacimiento</th>
				<th align=center>Ptda.<br>Nac.</th>
				<th align=center>Folio</th>
				<th align=center>Libro</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td style="height: 10px" align="center">					
					@if($estudiante->nie == '')
						<span style="font-size: 0.9em;">No encontrado</span>
					@else
						<span style="font-size: 0.9em;">{{$estudiante->nie}}</span>
					@endif
				</td>
				<td align="center">
					@if($estudiante->sexo == '')
						<span style="font-size: 0.9em;">No encontrado</span>
					@else
						<span style="font-size: 0.9em;">{{$estudiante->sexo}}</span>
					@endif
				</td>
				<td align="center">
					@if($matricula->repitegrado == '')
						<span style="font-size: 0.9em;">No encontrado</span>
					@else
						<span style="font-size: 0.9em;">{{$matricula->repitegrado}}</span>
					@endif
				</td>
				<td align="center">
					@if($matricula->hizokinder == '')
						<span style="font-size: 0.9em;">No encontrado</span>
					@else
						<span style="font-size: 0.9em;">{{$matricula->hizokinder}}</span>
					@endif
				</td>
				<td align="center">
					@if($estudiante->discapacidad == '')
						<span style="font-size: 0.9em;">No encontrado</span>
					@else
						<span style="font-size: 0.9em;">{{$estudiante->discapacidad}}</span>
					@endif
				</td>
				<td align="center">
					@if($estudiante->zonahabitacion == '')
						<span style="font-size: 0.9em;">No encontrado</span>
					@else
						<span style="font-size: 0.9em;">{{$estudiante->zonahabitacion}}</span>
					@endif
				</td>
				<td align="center">{{$edad}}</th>
				<td align="center">
					@if($estudiante->fechadenacimiento == '')
						<span style="font-size: 0.9em;">No encontrado</span>
					@else
						<span style="font-size: 0.9em;">{{$estudiante->fechadenacimiento}}</span>
					@endif
				</td>
				<td align="center">
					@if($detallepartida->partida == '')
						<span style="font-size: 0.9em;">No encontrado</span>
					@else
						<span style="font-size: 0.9em;">{{$detallepartida->partida}}</span>
					@endif
				</td>
				<td align="center">
					@if($detallepartida->folio == '')
						<span style="font-size: 0.9em;">No encontrado</span>
					@else
						<span style="font-size: 0.9em;">{{$detallepartida->folio}}</span>
					@endif
				</td>
				<td align="center">
					@if($detallepartida->libro == '')
						<span style="font-size: 0.9em;">No encontrado</span>
					@else
						<span style="font-size: 0.9em;">{{$detallepartida->libro}}</span>
					@endif
				</td>
			</tr>
		</tbody>
	</table><br>
	<div>Fecha de entrega de documentos al padre de familia cuando el alumno se retira de la institución 
		@if($matricula->fechaentrega == '')
			<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></div>
		@else
			<u>&nbsp;&nbsp;{{$matricula->fechaentrega}}&nbsp;&nbsp;</u></div>
		@endif
	<div align="right">Ciudad Merliot, {{$diahoy}} de {{$meshoy}} de {{$aniohoy}}</div>	
</body>
</html>