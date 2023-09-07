<?php require_once('Connections/conexion1.php'); ?>
<?php
// Array que vincula los IDs de los selects declarados en el HTML con el nombre de la tabla donde se encuentra su contenido
$listadoSelects=array(
"tipo_inv"=>"TblTipoproducto",
"codigo_inv"=>"insumo"
);

function validaSelect($selectDestino)
{
	// Se valida que el select enviado via GET exista
	global $listadoSelects;
	if(isset($listadoSelects[$selectDestino])) return true;
	else return false;
}

function validaOpcion($opcionSeleccionada)
{
	// Se valida que la opcion seleccionada por el usuario en el select tenga un valor numerico
	if(is_numeric($opcionSeleccionada)) return true;
	else return false;
}
//$selectDestino es el nombre del select a cargar 'estados'
$selectDestino=$_GET["select"]; $opcionSeleccionada=$_GET["opcion"];

if(validaSelect($selectDestino) && validaOpcion($opcionSeleccionada))
{
	
	//$tabla=$listadoSelects[$selectDestino];

if ($opcionSeleccionada=='1'){
    mysql_select_db($database_conexion1, $conexion1);
	$sqlconsulta="SELECT * FROM Tbl_referencia order by id_ref desc";
	$consulta = mysql_query($sqlconsulta, $conexion1) or die(mysql_error());
	// Comienzo a imprimir el select
	echo "<select name='".$selectDestino."' id='".$selectDestino."' onChange='cargaContenido(this.id)' style='width:300px'>";
	echo "<option value='0'>Sel. Referencia</option>";
	while($registro=mysql_fetch_row($consulta))
	{
    // Imprimo las opciones del select
    echo "<option value='".$registro[1]."-".$registro[2]."'>".$registro[1]."-".$registro[2]."</option>";
	}	
	}
if($opcionSeleccionada=='2'){
    mysql_select_db($database_conexion1, $conexion1);
	$sqlconsulta="SELECT id_insumo,codigo_insumo,descripcion_insumo FROM insumo ORDER BY descripcion_insumo ASC";
	$consulta = mysql_query($sqlconsulta, $conexion1) or die(mysql_error());
	// Comienzo a imprimir el select
	echo "<select name='".$selectDestino."' id='".$selectDestino."' onChange='cargaContenido(this.id)' style='width:300px'>";
	echo "<option value='0'>Sel. Materia Prima</option>";
	while($registro=mysql_fetch_row($consulta))
	{
    // Imprimo las opciones del select
	echo "<option value='".$registro[0]."'>".$registro[1]."-".$registro[2]."</option>";
	}			
	echo "</select>";
	}
if($opcionSeleccionada=='3'){
    mysql_select_db($database_conexion1, $conexion1);
	$sqlconsulta="SELECT id_op FROM Tbl_orden_produccion ORDER BY id_op DESC";
	$consulta = mysql_query($sqlconsulta, $conexion1) or die(mysql_error());
	// Comienzo a imprimir el select
	echo "<select name='".$selectDestino."' id='".$selectDestino."' onChange='cargaContenido(this.id)' style='width:300px'>";
	echo "<option value='0'>Sel. Orden de Produccion.</option>";
	while($registro=mysql_fetch_row($consulta))
	{
	// Imprimo las opciones del select
	echo "<option value='".$registro[0]."'>".$registro[0]."</option>";
	}			
	echo "</select>";
}
}
if($opcionSeleccionada=='4'){
echo "Materia Prima en Proceso pendiente"; //siguiente modulo del select
}
?>