<?php require_once('Connections/conexion1.php'); ?>
<?php
mysql_select_db($database_conexion1, $conexion1);

//FUNCION PARA LIMPIAR VARIABLES PARA ESCAPAR DE ALGUNOS DATOS PARA PASARLO A MYSQL
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
//------------------------------------------------------------------
//--------------------------INVENTARIOS-----------------------------
  $update=$_REQUEST[update];//PUEDE SER POST O GET
  //variables Globales
  $fecha1=($_REQUEST[Fecha1]);
  $fecha2=($_REQUEST[Fecha2]);
  $tipo =($_REQUEST[tipo_inv]);
  $mes=$_REQUEST[Mes];

 switch($update) {
 	case 0:  //GUARDAR
	$valor1=($_POST[Cod_ref]);
    foreach($valor1 as $v)
    $a[]= $v;
	$valor2=($_POST[Codigo]);
    foreach($valor2 as $v)
    $b[]= $v;
	$valor3=($_POST[Descripcion]);
    foreach($valor3 as $v)
    $c[]= $v;	
	$valor4=($_POST[Medida]);
    foreach($valor4 as $v)
    $d[]= $v;
	$valor5=($_POST[SaldoInicial]);
    foreach($valor5 as $v)
    $e[]= $v;
	$valor6=($_POST[Entrada]);
    foreach($valor6 as $v)
    $f[]= $v;
	$valor7=($_POST[Salida]);
    foreach($valor7 as $v)
    $g[]= $v;
	$valor8=($_POST[SaldoFinal]);
    foreach($valor8 as $v)
    $h[]= $v;
	$valor9=($_POST[CostoUnd]);
    foreach($valor9 as $v)
    $i[]= $v;
	$valor10=($_POST[CostoTotal]);
    foreach($valor10 as $v)
    $j[]= $v;
    $valor11=($_REQUEST[idAjuste]);
    foreach($valor11 as $key=>$v)
    $i[]= $v;	
print "<pre>"; 
print_r($_REQUEST); 
print "</pre>";

	for($x=0; $x<count($b); $x++) 
    {
  $insertSQL = sprintf("INSERT INTO TblInventarioHistory (Mes, Fecha, Cod_ref, Codigo, Descripcion, Medida, SaldoInicial, Entrada, Salida, SaldoFinal, CostoUnd,  CostoTotal, Acep, Tipo, Responsable)VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
					   GetSQLValueString($_REQUEST['Mes'], "text"),
					   GetSQLValueString($_REQUEST['Fecha'], "text"),
					   GetSQLValueString($a[$x], "text"),
                       GetSQLValueString($b[$x], "text"),
                       GetSQLValueString($c[$x], "text"),
					   GetSQLValueString($d[$x], "text"),
                       GetSQLValueString($e[$x], "int"),
					   GetSQLValueString($f[$x], "int"),
					   GetSQLValueString($g[$x], "int"),
                       GetSQLValueString($h[$x], "int"),
                       GetSQLValueString($i[$x], "double"),
					   GetSQLValueString($j[$x], "double"),
					   GetSQLValueString($_REQUEST['Acep'], "int"),
					   GetSQLValueString($_REQUEST['tipo_inv'], "int"),					   					   
                       GetSQLValueString($_REQUEST['Responsable'], "text"));
					   
  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());
/* if($_REQUEST[idAjuste]!='') {
    $updateSQL = sprintf("UPDATE TblInventarioListado SET SaldoInicial=%s WHERE idInv='%s'",//con el ID de ajuste del mes anterior
                       GetSQLValueString($h[$x], "double"),
					   GetSQLValueString($i[$x], "int"));
    mysql_select_db($database_conexion1, $conexion1);
    $Result2 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

 }
$id=1; header("location:inventario2.php?id=$id&fecha_ini=$fecha1&fecha_fin=$fecha2&tipo=$tipo");
}*///fin for
//INSERTO CON EL ID, EL SALDO FINAL DEL INVENTARIO LISTADO			

	  break;

	  } 
}
?>