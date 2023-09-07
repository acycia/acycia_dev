<?php require_once('Connections/conexion1.php'); ?>
<?php

//conectar();
	mysql_select_db($database_conexion1, $conexion1);
	$sqlconsulta=("SELECT valor FROM ingreso_db");
	$consulta = mysql_query($sqlconsulta, $conexion1) or die(mysql_error());
	$row_consulta = mysql_fetch_assoc($consulta);
//desconectar();

// Capturo los valores de la DB para mostrarlos apenas se carga la pagina
$campo1=mysql_fetch_row($consulta);
/*$campo2=mysql_fetch_row($consulta);*/
mysql_select_db($database_conexion1, $conexion1);
$query_codigo_empleado = "SELECT * FROM empleado ORDER BY  empleado.codigo_empleado ASC";
$codigo_empleado = mysql_query($query_codigo_empleado, $conexion1) or die(mysql_error());
$row_codigo_empleado = mysql_fetch_assoc($codigo_empleado);
$totalRows_codigo_empleado = mysql_num_rows($codigo_empleado);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="es">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>AJAX, Ejemplos: Ingreso a base de datos, codigo fuente - ejemplo</title>
<link rel="stylesheet" type="text/css" href="ingreso_sin_recargar.css">
<script type="text/javascript" src="ingreso_sin_recargar.js"></script>
</head>

<body>
			<?php do { ?>
			<div id="demo" style="width:600px;">
				<div id="demoArr" onclick="creaInput(this.id, 'campo1')"><?=$row_codigo_empleado['nombre_empleado'];?></div>
				<!--<div id="demoAba" onclick="creaInput(this.id, 'campo2')"><?=$campo2[0];?></div>-->
				<div class="mensaje" id="error"></div>
			</div>
			<?php } while ($row_codigo_empleado = mysql_fetch_assoc($codigo_empleado)); ?>
</body>
</html>