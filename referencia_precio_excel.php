<?php require_once('Connections/conexion1.php'); ?>
<?php
 header('Pragma: public'); 
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past    
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); 
header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1 
header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1 
header('Pragma: no-cache'); 
header('Expires: 0'); 
header('Content-Transfer-Encoding: none'); 
header('Content-Type: application/vnd.ms-excel'); // This should work for IE & Opera 
header('Content-type: application/x-msexcel'); // This should work for the rest 
header('Content-Disposition: attachment; filename="Precios-Referencia.xls"'); 
?>
<?php
//LLAMADO A FUNCIONES
include('funciones/funciones_php.php');//SISTEMA RUW PARA LA BASE DE DATOS 
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
$currentPage = $_SERVER["PHP_SELF"];

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

mysql_select_db($database_conexion1, $conexion1);
/*if($_GET['orden']==''){
  $orden="N_cotizacion";
  }else{
$orden=$_GET['orden'];
  }*/

$cod_ref = $_GET['cod_ref'];
$bolsa = $_GET['bolsa'];

 
$id_c = $_GET['id_c'];


//Filtra todos vacios
if($cod_ref!=0){
  $BD = PrecioRef($cod_ref); //la funcion define que tipo de ref es: bolsa, lamina, packing
}else{
  $BD = "Tbl_cotiza_bolsa";
}
 
//Filtra todos vacios
if($cod_ref == '0' && $id_c == '0' )
{
 
   $query_cotizacion = "SELECT * FROM Tbl_referencia r,$BD cb WHERE r.cod_ref = cb.N_referencia_c order by r.cod_ref desc";
}
if($cod_ref != '0' && $id_c == '0' )
{
 
   $query_cotizacion = "SELECT * FROM Tbl_referencia r,$BD cb WHERE r.cod_ref = cb.N_referencia_c AND r.cod_ref='$cod_ref' order by r.cod_ref desc";
}
if($cod_ref == '0' && $id_c != '0' )
{
 
   $query_cotizacion = "SELECT * FROM Tbl_referencia r,$BD cb WHERE r.cod_ref = cb.N_referencia_c  AND cb.Str_nit='$id_c' order by r.cod_ref desc";
}
if($cod_ref != '0' && $id_c != '0' )
{
 
   $query_cotizacion = "SELECT * FROM Tbl_referencia r,$BD cb WHERE r.cod_ref = cb.N_referencia_c  AND cb.Str_nit='$id_c' AND r.cod_ref='$cod_ref' order by r.cod_ref desc";
}
$cotizacion = mysql_query($query_cotizacion, $conexion1) or die(mysql_error());
$row_cotizacion = mysql_fetch_assoc($cotizacion);
$totalRows_cotizacion = mysql_num_rows($cotizacion); 
 ?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
</head>
<body>
<table id="Exportar_a_Excel" border="1">   
  <tr>
    <td colspan="12" nowrap="nowrap" align="center">LISTADO DE REFERENCIAS</td>
  </tr>
  <tr id="tr1">
    <td nowrap="nowrap" id="titulo4">N° REF</td>
    <td nowrap="nowrap" id="titulo4">COTIZ</td>
    <td nowrap="nowrap" id="titulo4">Cliente </td>
    <td nowrap="nowrap" id="titulo4">TIPO</td>
    <td colspan="2" nowrap="nowrap" id="titulo4">Material</td>
    <td nowrap="nowrap" id="titulo4">Adhesivo</td>
    <td nowrap="nowrap" id="titulo4">Ancho</td>
    <td nowrap="nowrap" id="titulo4">Largo</td>
    <td nowrap="nowrap" id="titulo4">Solapa</td>
    <td nowrap="nowrap" id="titulo4">Bolsillo</td>
    <td nowrap="nowrap" id="titulo4">Calibre</td>
    <td nowrap="nowrap" id="titulo4">Peso</td>
    <td nowrap="nowrap" id="titulo4">Precio sin impuesto $</td>
    <td nowrap="nowrap" id="titulo4">Precio Con impuesto $</td>
    <td nowrap="nowrap" id="titulo4">Fecha Creacion </td>
    <td nowrap="nowrap" id="titulo4">ESTADO</td>
  </tr>
  <?php do { ?>
  <tr>
      <td id="dato2"><?php echo $row_cotizacion['cod_ref']; ?></td>
      <td id="dato2"><?php echo $row_cotizacion['n_cotiz_ref']; ?></td>
      <td id="talla1"> <?php 
        $nit_c=$row_cotizacion['Str_nit'];
        $sqln="SELECT nombre_c FROM cliente WHERE nit_c='$nit_c'"; 
        $resultn=mysql_query($sqln); 
        $numn=mysql_num_rows($resultn); 
        if($numn >= '1') 
        { $nit_cliente_c=mysql_result($resultn,0,'nombre_c'); echo $nit_cliente_c; } ?> 
    </td> 
       <td id="dato2"><?php echo $row_cotizacion['tipo_bolsa_ref']; ?></td>
      <td colspan="2" id="dato2"><?php echo $row_cotizacion['material_ref']; ?></td>
      <td id="dato2"><?php echo $row_cotizacion['adhesivo_ref']; ?></td>
      <td id="dato2"><?php echo $row_cotizacion['ancho_ref']; ?></td>
      <td id="dato2"><?php echo $row_cotizacion['largo_ref']; ?></td>
      <td id="dato2"><?php echo $row_cotizacion['solapa_ref']; ?></td>
      <td id="dato2"><?php echo $row_cotizacion['bolsillo_guia_ref']; ?></td>
      <td id="dato2"><?php echo $row_cotizacion['calibre_ref']; ?></td>
      <td id="dato1"><?php echo $row_cotizacion['peso_millar_ref']; ?></td>
      <td id="dato2"><?php echo $row_cotizacion['N_precio'],$row_cotizacion['N_precio_vnta'];; ?></td>
      <td id="dato2"><?php echo $row_cotizacion['N_precio_old'];?></td>
      <td id="dato1"><?php echo $row_cotizacion['fecha_creacion']; ?></td>
      <td id="dato1"><?php 
    if (!(strcmp("0", $row_cotizacion['B_estado']))) {echo "Pendiente";}
    if (!(strcmp("1", $row_cotizacion['B_estado']))) {echo "Aceptada";}
    if (!(strcmp("2", $row_cotizacion['B_estado']))) {echo "Rechazada";}
    if (!(strcmp("3", $row_cotizacion['B_estado']))) {echo "Obsoleta";} ?></td>
    </tr>
    <?php } while ($row_cotizacion = mysql_fetch_assoc($cotizacion));?>
</table>
</body>
</html>
<?php
mysql_free_result($usuario);
mysql_free_result($cotizacion);

?>