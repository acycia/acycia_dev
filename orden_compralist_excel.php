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
header('Content-Disposition: attachment; filename="Listado_Ordenes.xls"');
 
?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);

  $logoutGoTo = "usuario.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
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
$str_numero_oc = $_GET['str_numero_oc'];
$elaborador = $_GET['elaborador'];
$vendedor = $_GET['vendedor'];
$id_c = $_GET['id_c'];
$nit_c = $_GET['nit_c'];
$estado_oc = $_GET['estado_oc'];
$pendiente = $_GET['pendiente'];
$cod_ref = $_GET['cod_ref'];
$tbpw = $_GET['tbpw'];
$fecha1 = $_GET['fecha_ini'];
$fecha2 = $_GET['fecha_fin'];
$factura = $_GET['factura'];
$nfactura = $_GET['nfactura'];
$autorizado = $_GET['autorizado'];

//Filtra todos vacios
if($str_numero_oc == '0' &&  $vendedor =='0' && $elaborador == '0' && $id_c == '0' && $nit_c == '0' && $estado_oc== '0' && $pendiente == '0' && $cod_ref == '0' && $tbpw  =='0' && $fecha1 == '' && $fecha2 == '' && $factura=='0' && $nfactura =='0' && $autorizado =='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra WHERE Tbl_orden_compra.b_borrado_oc='0' GROUP BY Tbl_orden_compra.str_numero_oc ORDER BY Tbl_orden_compra.fecha_ingreso_oc DESC, Tbl_orden_compra.str_numero_oc DESC";
}
//Filtra oc lleno
if($str_numero_oc != '0' && $vendedor =='0' && $elaborador == '0' && $id_c == '0' && $nit_c == '0' && $estado_oc == '0' && $pendiente == '0' && $cod_ref == '0' && $tbpw  =='0' && $fecha1 == '' && $fecha2 == '' && $factura=='0' && $nfactura =='0' && $autorizado =='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra WHERE Tbl_orden_compra.str_numero_oc like '%$str_numero_oc%' AND Tbl_orden_compra.b_borrado_oc='0' GROUP BY Tbl_orden_compra.str_numero_oc ORDER BY Tbl_orden_compra.fecha_ingreso_oc desc";
}
//Filtra elaborador lleno
if($str_numero_oc == '0' && $vendedor =='0' && $elaborador != '0' && $id_c == '0' && $nit_c == '0' && $estado_oc == '0' && $pendiente == '0' && $cod_ref == '0' && $tbpw  =='0' && $fecha1 == '' && $fecha2 == '' && $factura=='0' && $nfactura =='0' && $autorizado =='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra WHERE Tbl_orden_compra.str_elaboro_oc LIKE '%$elaborador%' AND Tbl_orden_compra.b_borrado_oc='0' GROUP BY Tbl_orden_compra.str_numero_oc ORDER BY Tbl_orden_compra.fecha_ingreso_oc DESC,  Tbl_orden_compra.str_numero_oc DESC";
}
//Filtra vendedor lleno
if($str_numero_oc == '0' && $elaborador == '0' && $vendedor !='0' && $id_c == '0' && $nit_c == '0' && $estado_oc == '0' && $pendiente == '0' && $cod_ref == '0' && $tbpw  =='0' && $fecha1 == '' && $fecha2 == '' && $factura=='0' && $nfactura =='0' && $autorizado =='0')
{
  $query_ordenes_compra = "SELECT * FROM tbl_orden_compra,tbl_items_ordenc WHERE tbl_items_ordenc.int_vendedor_io = '$vendedor'  AND tbl_orden_compra.id_pedido=tbl_items_ordenc.id_pedido_io GROUP BY tbl_orden_compra.str_numero_oc ORDER BY tbl_orden_compra.fecha_ingreso_oc DESC,  tbl_orden_compra.str_numero_oc DESC";
}
//Filtra vendedor, fecha lleno
if($str_numero_oc == '0' && $vendedor !='0' && $elaborador == '0' && $id_c == '0' && $nit_c == '0' && $estado_oc == '0' && $pendiente == '0' && $cod_ref == '0' && $tbpw  =='0' && $fecha1 != '' && $fecha2 != '' && $factura=='0' && $nfactura =='0' && $autorizado =='0')
{
  $query_ordenes_compra = "SELECT * FROM tbl_orden_compra,tbl_items_ordenc WHERE tbl_items_ordenc.int_vendedor_io = '$vendedor' AND tbl_orden_compra.id_pedido=tbl_items_ordenc.id_pedido_io AND tbl_orden_compra.b_borrado_oc='0' and DATE(tbl_orden_compra.fecha_ingreso_oc) BETWEEN '$fecha1' AND '$fecha2' GROUP BY tbl_orden_compra.str_numero_oc ORDER BY tbl_orden_compra.fecha_ingreso_oc desc";
}
//Filtra vendedor, factura Si
if($str_numero_oc == '0' && $elaborador == '0' && $vendedor !='0' && $id_c == '0' && $nit_c == '0' && $estado_oc == '0' && $pendiente == '0' && $cod_ref == '0' && $tbpw  =='0' && $fecha1 == '' && $fecha2 == '' && $factura=='1' && $nfactura =='0' && $autorizado =='0')
{
 
  $query_ordenes_compra = "SELECT * FROM tbl_orden_compra,tbl_items_ordenc WHERE tbl_items_ordenc.int_vendedor_io = '$vendedor' AND  tbl_orden_compra.factura_oc is not null AND tbl_orden_compra.id_pedido=tbl_items_ordenc.id_pedido_io GROUP BY tbl_orden_compra.str_numero_oc ORDER BY tbl_orden_compra.fecha_ingreso_oc DESC,  tbl_orden_compra.str_numero_oc DESC";
}
//Filtra vendedor, factura No
if($str_numero_oc == '0' && $elaborador == '0' && $vendedor !='0' && $id_c == '0' && $nit_c == '0' && $estado_oc == '0' && $pendiente == '0' && $cod_ref == '0' && $tbpw  =='0' && $fecha1 == '' && $fecha2 == '' && $factura=='2' && $nfactura =='0' && $autorizado =='0')
{
 
  $query_ordenes_compra = "SELECT * FROM tbl_orden_compra,tbl_items_ordenc WHERE tbl_items_ordenc.int_vendedor_io = '$vendedor' AND  tbl_orden_compra.factura_oc is null AND tbl_orden_compra.id_pedido=tbl_items_ordenc.id_pedido_io GROUP BY tbl_orden_compra.str_numero_oc ORDER BY tbl_orden_compra.fecha_ingreso_oc DESC,  tbl_orden_compra.str_numero_oc DESC";
 
}
//Filtra cliente lleno
if($vendedor =='0' && $elaborador == '0' && $id_c != '0' && $nit_c == '0' && $estado_oc== '0' && $pendiente == '0' && $cod_ref == '0' && $tbpw  =='0' && $fecha1 == '' && $fecha2 == '' && $factura=='0' && $nfactura =='0' && $autorizado =='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra WHERE Tbl_orden_compra.id_c_oc='$id_c' AND Tbl_orden_compra.b_borrado_oc='0' GROUP BY Tbl_orden_compra.str_numero_oc ORDER BY Tbl_orden_compra.fecha_ingreso_oc DESC, Tbl_orden_compra.str_numero_oc DESC";
}
//Filtra nit_c lleno
if($vendedor =='0' && $elaborador == '0' && $id_c == '0' && $nit_c != '0' && $estado_oc== '0' && $pendiente == '0' && $cod_ref == '0' && $tbpw  =='0' && $fecha1 == '' && $fecha2 == '' && $factura=='0' && $nfactura =='0' && $autorizado =='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra WHERE Tbl_orden_compra.str_nit_oc='$nit_c' AND Tbl_orden_compra.b_borrado_oc='0' GROUP  BY Tbl_orden_compra.str_numero_oc ORDER BY Tbl_orden_compra.fecha_ingreso_oc DESC,  Tbl_orden_compra.str_numero_oc DESC";
}
//Filtra estado_oc lleno
if($vendedor =='0' && $elaborador == '0' && $id_c == '0' && $nit_c == '0' && $estado_oc != '0' && $pendiente == '0' && $cod_ref == '0' && $tbpw  =='0' && $fecha1 == '' && $fecha2 == '' && $factura=='0' && $nfactura =='0' && $autorizado =='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra WHERE Tbl_orden_compra.b_estado_oc='$estado_oc' AND Tbl_orden_compra.b_borrado_oc='0' GROUP BY Tbl_orden_compra.str_numero_oc ORDER BY Tbl_orden_compra.fecha_ingreso_oc DESC, Tbl_orden_compra.str_numero_oc DESC";
}
//Filtra cliente y pendientes lleno
if($vendedor =='0' && $elaborador == '0' && $id_c == '0' && $nit_c == '0'&& $estado_oc == '0' && $pendiente != '0' && $cod_ref == '0' && $tbpw  =='0' && $fecha1 == '' && $fecha2 == '' && $factura=='0' && $nfactura =='0' && $autorizado =='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra,Tbl_items_ordenc WHERE Tbl_orden_compra.id_pedido=Tbl_items_ordenc.id_pedido_io AND Tbl_items_ordenc.int_cantidad_rest_io $pendiente '0.00' AND Tbl_orden_compra.b_borrado_oc='0' GROUP BY Tbl_orden_compra.str_numero_oc ORDER BY Tbl_orden_compra.fecha_ingreso_oc DESC";
}
//Filtra ref lleno
if($vendedor =='0' && $elaborador == '0' && $id_c == '0' && $nit_c == '0' && $estado_oc == '0' && $pendiente == '0' && $cod_ref != '0' && $tbpw  =='0' && $fecha1 == '' && $fecha2 == '' && $factura=='0' && $nfactura =='0' && $autorizado =='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_items_ordenc,Tbl_orden_compra WHERE Tbl_items_ordenc.int_cod_ref_io='$cod_ref' AND Tbl_items_ordenc.id_pedido_io = Tbl_orden_compra.id_pedido AND Tbl_orden_compra.b_borrado_oc='0' GROUP BY Tbl_orden_compra.str_numero_oc ORDER BY Tbl_orden_compra.fecha_ingreso_oc DESC, Tbl_orden_compra.str_numero_oc DESC, Tbl_items_ordenc.int_cod_ref_io DESC";
}
//Filtra cliente y ref lleno
if($vendedor =='0' && $elaborador == '0' && $id_c != '0' && $nit_c == '0' && $estado_oc == '0' && $pendiente == '0' && $cod_ref != '0' && $tbpw  =='0' && $fecha1 == '' && $fecha2 == '' && $factura=='0' && $nfactura =='0' && $autorizado =='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_items_ordenc,Tbl_orden_compra WHERE Tbl_orden_compra.id_c_oc='$id_c' AND Tbl_items_ordenc.int_cod_ref_io='$cod_ref' AND Tbl_items_ordenc.id_pedido_io = Tbl_orden_compra.id_pedido AND Tbl_orden_compra.b_borrado_oc='0' GROUP BY Tbl_orden_compra.str_numero_oc ORDER BY Tbl_orden_compra.fecha_ingreso_oc DESC, Tbl_orden_compra.str_numero_oc DESC, Tbl_items_ordenc.int_cod_ref_io DESC";
}
//Filtra cliente y estado lleno
if($vendedor =='0' && $elaborador == '0' && $id_c != '0' && $nit_c == '0' && $estado_oc != '0' && $pendiente == '0' && $cod_ref == '0' && $tbpw  =='0' && $fecha1 == '' && $fecha2 == '' && $factura=='0' && $nfactura =='0' && $autorizado =='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_items_ordenc WHERE Tbl_orden_compra.id_c_oc='$id_c' AND Tbl_orden_compra.b_estado_oc='$estado_oc' AND  Tbl_orden_compra.b_borrado_oc='0' GROUP BY Tbl_orden_compra.str_numero_oc ORDER BY Tbl_orden_compra.fecha_ingreso_oc DESC, Tbl_orden_compra.str_numero_oc DESC, Tbl_orden_compra.b_estado_oc DESC";
}
//Filtra cliente y pendientes lleno
if($vendedor =='0' && $elaborador == '0' && $id_c != '0' && $nit_c == '0' && $estado_oc == '0' && $pendiente != '0' && $cod_ref == '0' && $tbpw  =='0' && $fecha1 == '' && $fecha2 == '' && $factura=='0' && $nfactura =='0' && $autorizado =='0')
{
//,SUM(Tbl_items_ordenc.int_cantidad_rest_io) AS restante
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra,Tbl_items_ordenc WHERE Tbl_orden_compra.id_c_oc='$id_c' AND Tbl_items_ordenc.int_cantidad_rest_io $pendiente '0.00' AND Tbl_orden_compra.id_pedido=Tbl_items_ordenc.id_pedido_io AND Tbl_orden_compra.b_borrado_oc='0' GROUP BY Tbl_orden_compra.str_numero_oc ORDER BY Tbl_orden_compra.fecha_ingreso_oc DESC, Tbl_orden_compra.str_numero_oc DESC";
}
//Filtra nit y pendientes lleno
if($vendedor =='0' && $elaborador == '0' && $id_c == '0' && $nit_c != '0' && $estado_oc == '0' && $pendiente != '0' && $cod_ref == '0' && $tbpw  =='0' && $fecha1 == '' && $fecha2 == '' && $factura=='0' && $nfactura =='0' && $autorizado =='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra,Tbl_items_ordenc WHERE Tbl_orden_compra.str_nit_oc='$nit_c' AND Tbl_orden_compra.id_pedido=Tbl_items_ordenc.id_pedido_io AND Tbl_items_ordenc.int_cantidad_rest_io $pendiente '0.00' AND Tbl_orden_compra.b_borrado_oc='0' GROUP BY Tbl_orden_compra.str_numero_oc ORDER BY Tbl_orden_compra.fecha_ingreso_oc DESC, Tbl_orden_compra.str_numero_oc DESC";
}
//Filtra vende   y estado lleno
if($vendedor =='0' && $elaborador != '0' && $id_c == '0' && $nit_c == '0' && $estado_oc != '0' && $pendiente == '0' && $cod_ref == '0' && $tbpw  =='0' && $fecha1 == '' && $fecha2 == '' && $factura=='0' && $nfactura =='0' && $autorizado =='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra WHERE Tbl_orden_compra.str_elaboro_oc LIKE '%$vende%' AND  Tbl_orden_compra.b_estado_oc='$estado_oc' AND Tbl_orden_compra.b_borrado_oc='0' GROUP BY Tbl_orden_compra.str_numero_oc ORDER BY Tbl_orden_compra.fecha_ingreso_oc DESC, Tbl_orden_compra.str_numero_oc DESC";
}
//Filtra vende y pendientes lleno
if($vendedor =='0' && $elaborador != '0' && $id_c == '0' && $nit_c == '0' && $estado_oc == '0' && $pendiente != '0' && $cod_ref == '0' && $tbpw  =='0' && $fecha1 == '' && $fecha2 == '' && $factura=='0' && $nfactura =='0' && $autorizado =='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra,Tbl_items_ordenc WHERE Tbl_orden_compra.str_elaboro_oc LIKE '%$vende%' AND Tbl_items_ordenc.int_cantidad_rest_io $pendiente '0.00' AND Tbl_orden_compra.id_pedido=Tbl_items_ordenc.id_pedido_io AND Tbl_orden_compra.b_borrado_oc='0' GROUP BY Tbl_orden_compra.str_numero_oc ORDER BY Tbl_orden_compra.b_estado_oc DESC, Tbl_orden_compra.fecha_ingreso_oc DESC";
}
//Filtra vende y ref lleno
if($vendedor =='0' && $elaborador != '0' && $id_c == '0' && $nit_c == '0' && $estado_oc == '0' && $pendiente == '0' && $cod_ref != '0' && $tbpw  =='0' && $fecha1 == '' && $fecha2 == '' && $factura=='0' && $nfactura =='0' && $autorizado =='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra,Tbl_items_ordenc WHERE Tbl_orden_compra.str_elaboro_oc LIKE '%$vende%' AND Tbl_items_ordenc.int_cod_ref_io='$cod_ref' AND Tbl_orden_compra.id_pedido=Tbl_items_ordenc.id_pedido_io AND Tbl_orden_compra.b_borrado_oc='0' GROUP BY Tbl_orden_compra.str_numero_oc ORDER BY Tbl_orden_compra.b_estado_oc DESC, Tbl_orden_compra.fecha_ingreso_oc DESC";
}
//Filtra estado y pendientes lleno
if($vendedor =='0' && $elaborador == '0' && $id_c == '0' && $nit_c == '0' && $estado_oc != '0' && $pendiente != '0' && $cod_ref == '0' && $tbpw  =='0' && $fecha1 == '' && $fecha2 == '' && $factura=='0' && $nfactura =='0' && $autorizado =='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra,Tbl_items_ordenc WHERE Tbl_orden_compra.b_estado_oc='$estado_oc' AND Tbl_items_ordenc.int_cantidad_rest_io $pendiente '0.00' AND Tbl_orden_compra.id_pedido=Tbl_items_ordenc.id_pedido_io AND Tbl_orden_compra.b_borrado_oc='0' GROUP BY Tbl_orden_compra.str_numero_oc ORDER BY Tbl_orden_compra.fecha_ingreso_oc DESC, Tbl_orden_compra.str_numero_oc DESC";
}
//Filtra pendientes y ref lleno
if($vendedor =='0' && $elaborador == '0' && $id_c == '0' && $nit_c == '0' && $estado_oc == '0' && $pendiente != '0' && $cod_ref != '0' && $tbpw  =='0' && $fecha1 == '' && $fecha2 == '' && $factura=='0' && $nfactura =='0' && $autorizado =='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra,Tbl_items_ordenc WHERE Tbl_items_ordenc.int_cod_ref_io='$cod_ref' AND Tbl_items_ordenc.int_cantidad_rest_io $pendiente '0.00' AND Tbl_orden_compra.id_pedido=Tbl_items_ordenc.id_pedido_io AND Tbl_orden_compra.b_borrado_oc='0' GROUP BY Tbl_orden_compra.str_numero_oc ORDER BY Tbl_orden_compra.fecha_ingreso_oc DESC, Tbl_orden_compra.str_numero_oc DESC";
}
//Filtra estado y ref lleno
if($vendedor =='0' && $elaborador == '0' && $id_c == '0' && $nit_c == '0' && $estado_oc != '0' && $pendiente == '0' && $cod_ref != '0' && $tbpw  =='0' && $fecha1 == '' && $fecha2 == '' && $factura=='0' && $nfactura =='0' && $autorizado =='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_items_ordenc,Tbl_orden_compra WHERE Tbl_orden_compra.b_estado_oc='$estado_oc' AND  Tbl_items_ordenc.int_cod_ref_io='$cod_ref' AND Tbl_items_ordenc.id_pedido_io = Tbl_orden_compra.id_pedido AND Tbl_orden_compra.b_borrado_oc='0' GROUP BY Tbl_orden_compra.str_numero_oc ORDER BY Tbl_orden_compra.fecha_ingreso_oc DESC, Tbl_orden_compra.str_numero_oc DESC, Tbl_items_ordenc.int_cod_ref_io DESC";
}
//Filtra todos Y NIT  y ref VACIO
if($vendedor =='0' && $elaborador != '0' && $id_c != '0' && $nit_c == '0' && $estado_oc != '0' && $pendiente != '0' && $cod_ref == '0' && $tbpw  =='0' && $fecha1 == '' && $fecha2 == '' && $factura=='0' && $nfactura =='0' && $autorizado =='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra,Tbl_items_ordenc WHERE Tbl_orden_compra.str_elaboro_oc LIKE '%$vende%' AND Tbl_orden_compra.id_c_oc='$id_c' AND Tbl_orden_compra.b_estado_oc='$estado_oc' AND Tbl_items_ordenc.int_cantidad_rest_io $pendiente '0.00' AND Tbl_orden_compra.id_pedido=Tbl_items_ordenc.id_pedido_io AND Tbl_orden_compra.b_borrado_oc='0' GROUP BY Tbl_orden_compra.str_numero_oc ORDER BY Tbl_orden_compra.b_estado_oc DESC, Tbl_orden_compra.fecha_ingreso_oc DESC";
}
//Filtra todos Y NIT VACIO
if($vendedor =='0' && $elaborador != '0' && $id_c != '0' && $nit_c == '0' && $estado_oc != '0' && $pendiente != '0' && $cod_ref != '0' && $tbpw  =='0' && $fecha1 == '' && $fecha2 == '' && $factura=='0' && $nfactura =='0' && $autorizado =='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra,Tbl_items_ordenc WHERE Tbl_orden_compra.str_elaboro_oc LIKE '%$vende%' AND Tbl_orden_compra.id_c_oc='$id_c' AND Tbl_items_ordenc.int_cod_ref_io='$cod_ref' AND Tbl_orden_compra.b_estado_oc='$estado_oc' AND Tbl_items_ordenc.int_cantidad_rest_io $pendiente '0.00' AND Tbl_orden_compra.id_pedido=Tbl_items_ordenc.id_pedido_io AND Tbl_orden_compra.b_borrado_oc='0' GROUP BY Tbl_orden_compra.str_numero_oc ORDER BY Tbl_orden_compra.b_estado_oc DESC, Tbl_orden_compra.fecha_ingreso_oc DESC";
}
 

//Filtra TB Y PW lleno
if($str_numero_oc == '0' && $vendedor =='0' && $elaborador == '0' && $id_c == '0' && $nit_c == '0' && $estado_oc == '0' && $pendiente == '0' && $cod_ref == '0' && $tbpw  !='0' && $fecha1 == '' && $fecha2 == '' && $factura=='0' && $nfactura =='0' && $autorizado =='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra WHERE Tbl_orden_compra.str_numero_oc like '%$tbpw%' GROUP BY Tbl_orden_compra.str_numero_oc ORDER BY Tbl_orden_compra.fecha_ingreso_oc desc";
}
//Filtra vendedor, Fecha 
if($str_numero_oc == '0' && $vendedor =='0' && $elaborador != '0' && $id_c == '0' && $nit_c == '0' && $estado_oc == '0' && $pendiente == '0' && $cod_ref == '0' && $tbpw  =='0' && $fecha1 != '' && $fecha2 != '' && $factura=='0' && $nfactura =='0' && $autorizado =='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra WHERE Tbl_orden_compra.str_elaboro_oc LIKE '%$vende%' AND Tbl_orden_compra.b_borrado_oc='0' and DATE(Tbl_orden_compra.fecha_ingreso_oc) BETWEEN '$fecha1' AND '$fecha2' GROUP BY Tbl_orden_compra.str_numero_oc ORDER BY Tbl_orden_compra.fecha_ingreso_oc desc";
}
//Filtra vendedor Fecha, TB Y PW lleno
if($str_numero_oc == '0' && $vendedor =='0' && $elaborador != '0' && $id_c == '0' && $nit_c == '0' && $estado_oc == '0' && $pendiente == '0' && $cod_ref == '0' && $tbpw  !='0' && $fecha1 != '' && $fecha2 != '' && $factura=='0' && $nfactura =='0' && $autorizado =='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra WHERE Tbl_orden_compra.str_elaboro_oc LIKE '%$vende%' AND Tbl_orden_compra.str_numero_oc like '%$tbpw%' and  Tbl_orden_compra.b_borrado_oc='0' and DATE(Tbl_orden_compra.fecha_ingreso_oc) BETWEEN '$fecha1' AND '$fecha2' GROUP BY Tbl_orden_compra.str_numero_oc ORDER BY Tbl_orden_compra.fecha_ingreso_oc desc";
}
//Filtra Fecha, TB Y PW lleno
if($str_numero_oc == '0' && $vendedor =='0' && $elaborador == '0' && $id_c == '0' && $nit_c == '0' && $estado_oc == '0' && $pendiente == '0' && $cod_ref == '0' && $tbpw  !='0' && $fecha1 != '' && $fecha2 != '' && $factura=='0' && $nfactura =='0' && $autorizado =='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra WHERE Tbl_orden_compra.str_numero_oc like '%$tbpw%' and  Tbl_orden_compra.b_borrado_oc='0' and DATE(Tbl_orden_compra.fecha_ingreso_oc) BETWEEN '$fecha1' AND '$fecha2' GROUP BY Tbl_orden_compra.str_numero_oc ORDER BY Tbl_orden_compra.fecha_ingreso_oc desc";
}
//Filtra fecha lleno
if($str_numero_oc == '0' && $vendedor =='0' && $elaborador == '0' && $id_c == '0' && $nit_c == '0' && $estado_oc == '0' && $pendiente == '0' && $cod_ref == '0' && $tbpw  =='0' && $fecha1 != '' && $fecha2 != '' && $factura=='0' && $nfactura =='0' && $autorizado =='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra WHERE Tbl_orden_compra.b_borrado_oc='0' and DATE(Tbl_orden_compra.fecha_ingreso_oc) BETWEEN '$fecha1' AND '$fecha2' GROUP BY Tbl_orden_compra.str_numero_oc ORDER BY Tbl_orden_compra.fecha_ingreso_oc desc";
}



 

//Filtra factura oc con y sin
if($str_numero_oc == '0' && $vendedor =='0' && $elaborador == '0' && $id_c == '0' && $nit_c == '0' && $estado_oc == '0' && $pendiente == '0' && $cod_ref == '0' && $tbpw  =='0' && $fecha1 == '' && $fecha2 == '' && $factura!='0' && $nfactura =='0' && $autorizado =='0')
{
  if($factura=='1'){
    $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra WHERE b_borrado_oc='0' and factura_oc <> '' ORDER BY fecha_ingreso_oc desc";
     }else if($factura=='2'){
    $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra WHERE b_borrado_oc='0' and factura_oc is null ORDER BY fecha_ingreso_oc desc";
  }
}
//Filtra # factura 
if($str_numero_oc == '0' && $vendedor =='0' && $elaborador == '0' && $id_c == '0' && $nit_c == '0' && $estado_oc == '0' && $pendiente == '0' && $cod_ref == '0' && $tbpw  =='0' && $fecha1 == '' && $fecha2 == '' && $factura=='0' && $nfactura !='0' && $autorizado =='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra WHERE b_borrado_oc='0' and factura_oc='$nfactura' ORDER BY fecha_ingreso_oc desc";
}
//Filtra autorizado
if($str_numero_oc == '0' && $vendedor =='0' && $elaborador == '0' && $id_c == '0' && $nit_c == '0' && $estado_oc == '0' && $pendiente == '0' && $cod_ref == '0' && $tbpw  =='0' && $fecha1 == '' && $fecha2 == '' && $factura=='0' && $nfactura =='0' && $autorizado !='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra WHERE b_borrado_oc='0' and autorizado='$autorizado' ORDER BY fecha_ingreso_oc desc";
}

//Filtra factura oc con y sin, # factura 
if($str_numero_oc == '0' && $vendedor =='0' && $elaborador == '0' && $id_c == '0' && $nit_c == '0' && $estado_oc == '0' && $pendiente == '0' && $cod_ref == '0' && $tbpw  =='0' && $fecha1 == '' && $fecha2 == '' && $factura!='0' && $nfactura !='0' && $autorizado =='0')
{
  if($factura=='1'){
    $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra WHERE b_borrado_oc='0' and factura_oc <> '' and factura_oc='$nfactura' ORDER BY fecha_ingreso_oc desc";
     }else if($factura=='2'){
    $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra WHERE b_borrado_oc='0' and factura_oc is null and factura_oc='$nfactura' ORDER BY fecha_ingreso_oc desc";
  }
}
//Filtra # factura,autorizado
if($str_numero_oc == '0' && $vendedor =='0' && $elaborador == '0' && $id_c == '0' && $nit_c == '0' && $estado_oc == '0' && $pendiente == '0' && $cod_ref == '0' && $tbpw  =='0' && $fecha1 == '' && $fecha2 == '' && $factura=='0' && $nfactura !='0' && $autorizado !='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra WHERE b_borrado_oc='0' and factura_oc='$nfactura' and autorizado='$autorizado' ORDER BY fecha_ingreso_oc desc";
}
//Filtra factura oc con y sin,  autorizado
if($str_numero_oc == '0' && $vendedor =='0' && $elaborador == '0' && $id_c == '0' && $nit_c == '0' && $estado_oc == '0' && $pendiente == '0' && $cod_ref == '0' && $tbpw  =='0' && $fecha1 == '' && $fecha2 == '' && $factura!='0' && $nfactura =='0' && $autorizado !='0')
{
  if($factura=='1'){
    $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra WHERE b_borrado_oc='0' and factura_oc <> '' and factura_oc='$nfactura' and autorizado='$autorizado' ORDER BY fecha_ingreso_oc desc";
     }else if($factura=='2'){
    $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra WHERE b_borrado_oc='0' and factura_oc is null and autorizado='$autorizado' ORDER BY fecha_ingreso_oc desc";
  }
}
//Filtra factura oc con y sin, # factura, autorizado
if($str_numero_oc == '0' && $vendedor =='0' && $elaborador == '0' && $id_c == '0' && $nit_c == '0' && $estado_oc == '0' && $pendiente == '0' && $cod_ref == '0' && $tbpw  =='0' && $fecha1 == '' && $fecha2 == '' && $factura!='0' && $nfactura !='0' && $autorizado !='0')
{
  if($factura=='1'){
    $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra WHERE b_borrado_oc='0' and factura_oc <> '' and factura_oc='$nfactura' and autorizado='$autorizado' ORDER BY fecha_ingreso_oc desc";
     }else if($factura=='2'){
    $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra WHERE b_borrado_oc='0' and factura_oc is null and factura_oc='$nfactura' and autorizado='$autorizado' ORDER BY fecha_ingreso_oc desc";
  }
}
//Filtra factura oc con y sin, fecha
if($str_numero_oc == '0' && $vendedor =='0' && $elaborador == '0' && $id_c == '0' && $nit_c == '0' && $estado_oc == '0' && $pendiente == '0' && $cod_ref == '0' && $tbpw  =='0' && $fecha1 != '' && $fecha2 != '' && $factura!='0' && $nfactura =='0' && $autorizado =='0')
{
  if($factura=='1'){
    $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra WHERE b_borrado_oc='0' and DATE(fecha_ingreso_oc) BETWEEN '$fecha1' AND '$fecha2' and factura_oc <> '' GROUP BY str_numero_oc ORDER BY fecha_ingreso_oc desc";
     }else if($factura=='2'){
    $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra WHERE b_borrado_oc='0' and DATE(fecha_ingreso_oc) BETWEEN '$fecha1' AND '$fecha2' and factura_oc is null GROUP BY str_numero_oc ORDER BY fecha_ingreso_oc desc";
  }
}
//Filtra autorizado, fecha
if($str_numero_oc == '0' && $vendedor =='0' && $elaborador == '0' && $id_c == '0' && $nit_c == '0' && $estado_oc == '0' && $pendiente == '0' && $cod_ref == '0' && $tbpw  =='0' && $fecha1 != '' && $fecha2 != '' && $factura=='0' && $nfactura =='0' && $autorizado !='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra WHERE b_borrado_oc='0' and autorizado='$autorizado' and DATE(fecha_ingreso_oc) BETWEEN '$fecha1' AND '$fecha2' GROUP BY str_numero_oc ORDER BY fecha_ingreso_oc desc";
}
//Filtra factura oc con y sin, autorizado, fecha
if($str_numero_oc == '0' && $vendedor =='0' && $elaborador == '0' && $id_c == '0' && $nit_c == '0' && $estado_oc == '0' && $pendiente == '0' && $cod_ref == '0' && $tbpw  =='0' && $fecha1 != '' && $fecha2 != '' && $factura!='0' && $nfactura =='0' && $autorizado !='0')
{
  if($factura=='1'){
    $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra WHERE b_borrado_oc='0' and autorizado='$autorizado' and DATE(fecha_ingreso_oc) BETWEEN '$fecha1' AND '$fecha2' and factura_oc <> '' GROUP BY str_numero_oc ORDER BY fecha_ingreso_oc desc";
     }else if($factura=='2'){
    $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra WHERE b_borrado_oc='0' and autorizado='$autorizado' and DATE(fecha_ingreso_oc) BETWEEN '$fecha1' AND '$fecha2' and factura_oc is null GROUP BY str_numero_oc ORDER BY fecha_ingreso_oc desc";
  }
}

$ordenes_compra = mysql_query($query_ordenes_compra, $conexion1) or die(mysql_error());
$row_ordenes_compra = mysql_fetch_assoc($ordenes_compra);

mysql_select_db($database_conexion1, $conexion1);
$query_lista = "SELECT str_numero_oc FROM Tbl_orden_compra WHERE b_borrado_oc='0' ORDER BY fecha_ingreso_oc DESC";
$lista = mysql_query($query_lista, $conexion1) or die(mysql_error());
$row_lista = mysql_fetch_assoc($lista);
$totalRows_lista = mysql_num_rows($lista);

mysql_select_db($database_conexion1, $conexion1);
$query_proveedores = "SELECT * FROM cliente ORDER BY nombre_c ASC";
$proveedores = mysql_query($query_proveedores, $conexion1) or die(mysql_error());
$row_proveedores = mysql_fetch_assoc($proveedores);
$totalRows_proveedores = mysql_num_rows($proveedores);

mysql_select_db($database_conexion1, $conexion1);
$query_ano = "SELECT * FROM cliente ORDER BY nit_c DESC";
$ano = mysql_query($query_ano, $conexion1) or die(mysql_error());
$row_ano = mysql_fetch_assoc($ano);
$totalRows_ano = mysql_num_rows($ano);

mysql_select_db($database_conexion1, $conexion1);
$query_numero = "SELECT * FROM Tbl_referencia  WHERE estado_ref='1' ORDER BY CONVERT(cod_ref, SIGNED INTEGER)  DESC";
$numero = mysql_query($query_numero, $conexion1) or die(mysql_error());
$row_numero = mysql_fetch_assoc($numero);
$totalRows_numero = mysql_num_rows($numero);

//IMRPIME EL NOMBRE DEL VENDEDOR
mysql_select_db($database_conexion1, $conexion1);
$query_vendedores = "SELECT * FROM vendedor ORDER BY nombre_vendedor ASC";
$vendedores = mysql_query($query_vendedores, $conexion1) or die(mysql_error());
$row_vendedores = mysql_fetch_assoc($vendedores);
$totalRows_vendedores = mysql_num_rows($vendedores);
?>
<html>
<head>
  <title>SISADGE AC &amp; CIA</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

  <script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
  <link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">
  <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
  <script type="text/javascript" src="AjaxControllers/js/updates.js"></script>
  <script type="text/javascript" src="AjaxControllers/envioListados.js"></script>

  <link href="css/formato.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="js/listado.js"></script>
</head>
<body ><div align="center">
  <table id="tabla1" border=1>
    <tr id="tr1">

      <td nowrap id="titulo4">N&deg; ORDEN</td>
      <td nowrap id="titulo4">FECHA INGRESO</td>
      <td nowrap id="titulo4">CLIENTE</td>
      <!-- <td nowrap id="titulo4">VENDEDOR</td> -->
      <td nowrap id="titulo4">CANTIDAD</td>
      <td nowrap id="titulo4">VALOR TOTAL</td>
      <td nowrap id="titulo4">NIT / CC</td>
      <td nowrap id="titulo4">FACTURA</td>
      <td nowrap id="titulo4">QUIEN DESPACHO</td>
    </tr>               
    <?php do { ?>
    <tr>
      <td style="text-align: left;" ><strong><?php echo $row_ordenes_compra['str_numero_oc']; ?></strong></a></td>
      <td style="text-align: left;"><?php echo $row_ordenes_compra['fecha_ingreso_oc']; ?></td>
      <td style="text-align: left;" id="dato1">
        <?php 
        $id_c_oc=$row_ordenes_compra['id_c_oc'];
        $sqln="SELECT nombre_c FROM cliente WHERE id_c='$id_c_oc'"; 
        $resultn=mysql_query($sqln); 
        $numn=mysql_num_rows($resultn); 
        if($numn >= '1') 
        { 
         $nit_cliente_c=mysql_result($resultn,0,'nombre_c'); echo utf8_encode($nit_cliente_c); 
       }
       ?>
     </td>
     <!-- <td style="text-align: left;"><?php echo utf8_encode($row_ordenes_compra['str_elaboro_oc']); ?></td> -->

     <td style="text-align: left;">
       <?php 
       $id_pedido=$row_ordenes_compra['id_pedido'];
       $sqlCAN="SELECT SUM(int_cantidad_io) AS int_cantidad_io, SUM(int_total_item_io) AS int_total_item_io  FROM Tbl_items_ordenc WHERE id_pedido_io='$id_pedido'"; 
       $resultCAN=mysql_query($sqlCAN); 
       $numCAN=mysql_num_rows($resultCAN); 
       if($numCAN >= '1') 
       { 
         $int_cantidad_io=mysql_result($resultCAN,0,'int_cantidad_io'); echo round($int_cantidad_io);
         $int_total_item_io=mysql_result($resultCAN,0,'int_total_item_io');
       }
       ?>

     </td>

     <td style="text-align: left;">
      <?php echo number_format($int_total_item_io, 2, ',', ' '); ?> 
    </td>
    <td style="text-align: left;" id="dato2"> 
      <?php   
      echo $row_ordenes_compra['str_nit_oc'];
      ?> 
    </td>

    <td style="text-align: left;" id="dato2">
      <?php 
      $fact = $row_ordenes_compra['str_numero_oc'];
      $sqlf="SELECT re.str_elaboro_r, re.factura_r  FROM Tbl_orden_compra as oc RIGHT JOIN Tbl_remisiones as re on oc.str_numero_oc = re.str_numero_oc_r WHERE oc.str_numero_oc = '$fact' and re.factura_r <> 0";  
      $resultf=mysql_query($sqlf);
      $numf=mysql_num_rows($resultf); 
      if($numf >= 1) 
      { 
        $factura=mysql_result($resultf,0,'factura_r'); echo $factura;
        $despacho=mysql_result($resultf,0,'str_elaboro_r');  
      }
      ?> 
    </td>
    <td style="text-align: left;" id="dato2"> <?php echo utf8_encode($despacho); ?> </td>       
  </tr>
  <?php } while ($row_ordenes_compra = mysql_fetch_assoc($ordenes_compra)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($usuario);mysql_close($conexion1);

mysql_free_result($ordenes_compra);

mysql_free_result($lista);

mysql_free_result($proveedores);

mysql_free_result($ano);
?>