<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Hoja de Vida</title>
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
			margin-top: 20.0mm;
            margin-left: 15.0mm;
            margin-right: 20.0mm;
            margin-bottom: 15.0mm;

		}
	</style>
</head>
<body>
	<div align="center"><h4>CENTRO ESCOLAR JARDINES  DE LA SABANA<br>HOJA DE VIDA</h4></div><br>
	<ol type="I">
		<li><b>DATOS INDENTIFICACIÓN</b></li><br><br>
		
		<table>
			<div align="justify">
			Nombre: 
			<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$maestro->nombre}}&nbsp;&nbsp;&nbsp;{{$maestro->apellido}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u> 
			Sexo M 
			@if($maestro->sexo == 'M')
				<div class="polig" align="center" style="color: black;">X</div>  
			@else
				<div class="polig" >&nbsp;&nbsp;&nbsp;</div>
			@endif 
			F 
			@if($maestro->sexo == 'F') 
				<div class="polig" align="center" style="color: black;">X</div>
			@else
				<div class="polig" >&nbsp;&nbsp;&nbsp;</div>
			@endif
			
			</div>
		</table>
			
			
			<table>
				<div align="justify">
					Dirección actual: 
					@if($maestro->direccion == '')
						<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
					@else
						<u>&nbsp;&nbsp;&nbsp;&nbsp;{{$maestro->direccion}}&nbsp;&nbsp;&nbsp;&nbsp;</u>
					@endif
					<br>
					No teléfono: 
					@if($maestro->telefono == '')
						<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u> 
					@else
						<u>&nbsp;&nbsp;&nbsp;&nbsp;{{$maestro->telefono}}&nbsp;&nbsp;&nbsp;&nbsp;</u> 
					@endif
					Fecha de nacimiento:
					@if($maestro->fechanacimiento == '')
						<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
					@else
						<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$maestro->fechanacimiento}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
					@endif
				</div>
			</table>
			Lugar de nacimiento (Municipio y Depto): <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $mun->nombre }}&nbsp;&nbsp;&nbsp;,&nbsp;&nbsp;&nbsp;{{ $depto->nombre }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u><br>
			Estado Civil: <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$estado->tipo}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u> 
			Nivel Docente: <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$nivel->nivel}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u> 
			Categoría: <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$categoria->categoria}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u> 
			Clase: <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$clase->clase}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u><br>

			NIP: <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$maestro->nip}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u> 
			DUI: <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$maestro->mdui}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u> 
			Extendido: <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$maestro->extendido}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u><br>

			NIT: <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$maestro->nit}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u> 
			AFP: <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$maestro->afp}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u> 
			INEP: <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$maestro->inpep}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u><br>

			Centro Escolar  donde está nombrado: <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$hoja->cenombrado}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u><br>

			Código de la institución:<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$hoja->codigoinstitucion}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u><br>

			Fecha de ingreso al sector público (día/mes/año): <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$hoja->fechaingresopublico}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u><br>

			Fecha de ingreso al centro donde labora (día/mes/año): <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$hoja->fechalaboral}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u><br>

			Último ascenso: <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$hoja->ultimoascenso}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u> años de servicio: <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$hoja->aniosservicio}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u><br>

			Próximo ascenso: <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$hoja->proximoascenso}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u><br>

			Cargo que desempeña: <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$hoja->cargo}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u><br>

			Principales funciones a realizar: <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$hoja->funciones}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u><br><br><br><br><br>		
		
		<li><b>ESTUDIOS REALIZADOS (después del Bachillerato)</b></li><br>
		<div style="page-break-after: always;">
			<table style="border-collapse: collapse; width: 100%;">
				<thead>
					<tr>
						<th align=center style="border: 1px solid #333;">INSTITUCIÓN</th>
						<th align=center style="border: 1px solid #333;">CLASE DE ESTUDIO</th>
						<th align=center style="border: 1px solid #333;">ESPECIALIDAD</th>
					</tr>
				</thead>
				<tbody>
					@if($estudios == NULL)
					<tr>
                        <th style="border: 1px solid #333;height: 20px;">NO SE ENCONTRO</th>
						<th></th>
						<th></th>
					</tr>
					@else
						@foreach ($estudios as $ma)
                    <tr>
                        <td align=center style="border: 1px solid #333; height: 30px;">{{ $ma->institucion}}</td>
                        <td align=center style="border: 1px solid #333;">{{ $ma->tipo }}</td>
                        <td align=center style="border: 1px solid #333;">{{ $ma->especialidad }}</td>
					</tr>
						@endforeach		
					@endif			
				</tbody>
			</table>
		</div>
		<li><b>CAPACITACIONES RECIBIDAS (durante los últimos tres años)</b></li><br>
		<div>
			<table style="border-collapse: collapse; width: 100%;">
				<thead>
					<tr>
						<th align=center style="border: 1px solid #333;">AÑO</th>
						<th align=center style="border: 1px solid #333;">NOMBRE CAPACITACION</th>
						<th align=center style="border: 1px solid #333;">HORAS</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($capacitaciones as $ca)
                    <tr>
                        <td align=center style="border: 1px solid #333; height: 30px;">{{ $ca->anio}}</td>
                        <td align=center style="border: 1px solid #333;">{{ $ca->nombre }}</td>
                        <td align=center style="border: 1px solid #333;">{{ $ca->horas }}</td>
                    </tr>
                    @endforeach
				</tbody>
			</table>
		</div><br><br>
		<li><b>INSTITUCIONES DONDE LABORÓ ANTERIORMENTE</b></li><br>
		<div>
			<table style="border-collapse: collapse; width: 100%;">
				<thead>
					<tr>
						<th align=center style="border: 1px solid #333;">CARGO</th>
						<th align=center style="border: 1px solid #333;">LUGAR DE TRABAJO</th>
						<th align=center style="border: 1px solid #333;">TIEMPO</th>
					</tr>
				</thead>
				<tbody>
				@foreach ($trabajos as $tb)
                	<tr>
                        <td align=center style="border: 1px solid #333; height: 30px;">{{ $tb->cargo}}</td>
                        <td align=center style="border: 1px solid #333;">{{ $tb->lugar }}</td>
                        <td align=center style="border: 1px solid #333;">{{ $tb->tiempo }}</td>
                    </tr>
                @endforeach
				</tbody>
			</table>
		</div>
	</ol>
</body>
</html>