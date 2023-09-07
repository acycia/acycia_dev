<?php require_once('Connections/conexion1.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "usuario.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
//LLAMADO A FUNCIONES
include('funciones/funciones_php.php');//distintas funciones
include('costos_totales_funcion.php');//formulas de costos 
//FIN	
/*$fecha2= date("Y-m-d");
$fecha1=restaMes($fecha2);*/

$fecha1= '2014-02-01';
$fecha2= '2014-02-28';

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);  

/*mysql_select_db($database_conexion1, $conexion1); 
$query_consumo = "SELECT * FROM Tbl_reg_produccion WHERE b_borrado_rp='0' AND DATE(fecha_ini_rp) BETWEEN '$fecha1'
AND '$fecha2' AND DATE(fecha_fin_rp) BETWEEN '$fecha1' AND '$fecha2' ORDER BY fecha_ini_rp DESC";
$consumo = mysql_query($query_consumo, $conexion1) or die(mysql_error());
$row_consumo = mysql_fetch_assoc($consumo);
$totalRows_consumo = mysql_num_rows($consumo);*/

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
<title>Untitled Document</title>
</head>

<body>
<p>IMPRESION DE GGA Y CIF</p>
<p>
  <?php 
  //$costoxPxLxT =FormulaCostos($fecha1, $fecha2);
/*       echo $row_consumo["fecha_ini_rp"];
        mysql_select_db($database_conexion1, $conexion1); 
		$query_GGA = "SELECT IDCaracGGA,ValorCaracGGA FROM TblDetalleGGAProd WHERE     
		TblDetalleGGAProd.FechaInicio BETWEEN 'fecha1' AND 'fecha2' AND TblDetalleGGAProd.FechaFin BETWEEN 'fecha1' AND 'fecha2'"; 
		$GGA = mysql_query($query_GGA, $conexion1) or die(mysql_error());
		$row_GGA = mysql_fetch_assoc($GGA);
		$totalRows_GGA = mysql_num_rows($GGA);*/
		
		mysql_select_db($database_conexion1, $conexion1); 
		$query_consumo = "SELECT IDCaracGGA,ValorCaracGGA, AreaCaracGGA FROM TblDetalleGGAProd WHERE TblDetalleGGAProd.FechaInicio 
		BETWEEN '2014-02-01' AND '2014-02-28' AND TblDetalleGGAProd.FechaFin BETWEEN '2014-02-01' AND '2014-02-28'";
		$consumo = mysql_query($query_consumo, $conexion1) or die(mysql_error());
		$row_consumo = mysql_fetch_assoc($consumo);
		echo "<table border='1'>";
		  do { 
echo "<tr>";
             if($row_consumo["IDCaracGGA"]==9 && $row_consumo["AreaCaracGGA"]<=500){
		      echo "<td>",$row_consumo["ValorCaracGGA"],"</td>";
			 }
echo "</tr>";
		  } while ($row_consumo = mysql_fetch_assoc($consumo)); 
			
echo "</table>";
//Consultamos a la base de datos para sacar las columnas de la tabla

?>
<!--<table border='1'>
<?php
//ahora consultamos a la base de datos para sacar los registros contenidos
$result2 = mysql_query("SELECT IDCaracGGA,ValorCaracGGA, AreaCaracGGA FROM TblDetalleGGAProd WHERE TblDetalleGGAProd.FechaInicio 
		BETWEEN '2014-02-01' AND '2014-02-28' AND TblDetalleGGAProd.FechaFin BETWEEN '2014-02-01' AND '2014-02-28'");
while ($row2 = mysql_fetch_array($result2, MYSQL_NUM)) {
echo "<tr>";
    for($i=0; $i<count($row2); $i++)
        echo "<td>",$row2[$i],"</td>";
echo "</tr>";
}
		    ?>
            </table>-->
</p>
</body>
</html>