<?php require_once('Connections/conexion1.php'); ?>


<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
?>

<?php 
	$valor=($_POST['FechaInicial']);
    foreach($valor as $key=>$v)
    $a[]= $v;
	$valor1=($_POST['FechaFinal']);
    foreach($valor1 as $key=>$v)
    $b[]= $v;
	$valor2=($_POST['Proceso']);
    foreach($valor2 as $key=>$v)
    $c[]= $v;
	$valor3=($_POST['MateriaPrima']);
    foreach($valor4 as $key=>$v)
    $d[]= $v;
	$valor4=($_POST['SaldoIniCant']);
    foreach($valor4 as $key=>$v)
    $e[]= $v;
	$valor5=($_POST['SaldoIniCosto']);
    foreach($valor5 as $key=>$v)
    $f[]= $v;
	$valor6=($_POST['EntradaCant']);
    foreach($valor6 as $key=>$v)
    $g[]= $v;
	$valor7=($_POST['EntradaCosto']);
    foreach($valor7 as $key=>$v)
    $h[]= $v;
	$valor8=($_POST['SalidaCant']);
    foreach($valor8 as $key=>$v)
    $i[]= $v;
	$valor9=($_POST['SalidaCosto']);
    foreach($valor9 as $key=>$v)
    $j[]= $v;
	$valor10=($_POST['SaldoFinCant']);
    foreach($valor10 as $key=>$v)
    $k[]= $v;
	$valor11=($_POST['SaldoFinCosto']);
    foreach($valor11 as $key=>$v)
    $l[]= $v;
	$valor12=($_POST['Responsable']);
    foreach($valor12 as $key=>$v)
    $m[]= $v;
	for($x=0; $x<count($e); $x++) 
    {
		if($e[$x]!=''){		
  $insertSQL = sprintf("INSERT INTO TblCostosMP (FechaInicial, FechaFinal, Proceso, MateriaPrima, SaldoIniCant, SaldoIniCosto, EntradaCant, EntradaCosto, SalidaCant, SalidaCosto, SaldoFinCant, SaldoFinCosto, Responsable) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($a[$x], "date"),
                       GetSQLValueString($b[$x], "date"),
                       GetSQLValueString($c[$x], "int"),
                       GetSQLValueString($d[$x], "int"),
                       GetSQLValueString($e[$x], "int"),
                       GetSQLValueString($f[$x], "double"),
                       GetSQLValueString($g[$x], "int"),
                       GetSQLValueString($h[$x], "double"),
                       GetSQLValueString($i[$x], "int"),
                       GetSQLValueString($j[$x], "double"),
                       GetSQLValueString($k[$x], "int"),
                       GetSQLValueString($l[$x], "double"),
                       GetSQLValueString($m[$x], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertGoTo = "Produccion_listado_costosMP.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));

	}//fin if
}//fin for

?>