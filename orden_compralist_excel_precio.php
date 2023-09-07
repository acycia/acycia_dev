<?php require_once('Connections/conexion1.php'); ?>
<?php
/*header('Pragma: public'); 
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past    
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); 
header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1 
header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1 
header('Pragma: no-cache'); 
header('Expires: 0'); 
header('Content-Transfer-Encoding: none'); 
header('Content-Type: application/vnd.ms-excel'); // This should work for IE & Opera 
header('Content-type: application/x-msexcel'); // This should work for the rest 
header('Content-Disposition: attachment; filename="Listado_Ref_Precio.xls"');
 */
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
 
$autorizado = $_GET['autorizado'];

//Filtra todos vacios
/*if($autorizado == 1)
{
  $query_ordenes_compra = "SELECT c.nombre_c as Cliente, p.str_unidad_io as Unidad, MAX(p.int_precio_io) AS max_list_price, p.int_cod_ref_io as Ref, p.str_moneda_io as Moneda 
FROM tbl_items_ordenc p 
INNER JOIN tbl_orden_compra o ON p.id_pedido_io = o.id_pedido 
INNER JOIN cliente c on c.nit_c = o.str_nit_oc GROUP BY p.int_cod_ref_io 
ORDER BY CAST(p.int_cod_ref_io AS INT) DESC";
}*/ 
if($autorizado == 1)
{
  $query_ordenes_compra = "SELECT p.int_cod_ref_io as Ref,c.nombre_c as Cliente, p.str_unidad_io as Unidad,p.str_moneda_io as Moneda,  ancho_ref as Ancho_Bolsa, largo_ref as Alto_Bolsa,N_fuelle as Fuelle, solapa_ref as solapa_Bolsa, calibre_ref as Calibre_Bolsa, peso_millar_ref as Peso_millar_Bolsa,  bolsillo_guia_ref as Bolsillo, bol_lamina_1_ref as Lamina_Bolsillo_1, bol_lamina_2_ref as Lamina_Bolsillo_2, calibreBols_ref as Calibre_Bolsillo, IFNULL(r.peso_millar_bols,0) as Peso_Millar_Bolsillo, MAX(p.int_precio_io) AS Precio_Maximo,  IFNULL(((IFNULL(r.peso_millar_bols,0)  +IFNULL(r.peso_millar_ref,0) )*2.12) ,0) as Tasa_2_12
FROM  tbl_referencia r
INNER JOIN tbl_items_ordenc p ON r.cod_ref = p.int_cod_ref_io
INNER JOIN tbl_orden_compra o ON p.id_pedido_io = o.id_pedido 
INNER JOIN cliente c on c.nit_c = o.str_nit_oc GROUP BY p.int_cod_ref_io 
ORDER BY CAST(p.int_cod_ref_io AS INT) DESC";
} 


$ordenes_compra = mysql_query($query_ordenes_compra, $conexion1) or die(mysql_error());
$row_ordenes_compra = mysql_fetch_assoc($ordenes_compra);

 
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
<td nowrap id="titulo4">Ref</td>
<td nowrap id="titulo4">Cliente</td>
<td nowrap id="titulo4">Unidad</td>
<td nowrap id="titulo4">Moneda</td>
<td nowrap id="titulo4">Ancho_Bolsa</td>
<td nowrap id="titulo4">Alto_Bolsa</td>
<td nowrap id="titulo4">Fuelle</td>
<td nowrap id="titulo4">solapa_Bolsa</td>
<td nowrap id="titulo4">Calibre_Bolsa</td>
<td nowrap id="titulo4">Peso_millar_Bolsa</td>
<td nowrap id="titulo4">Bolsillo</td>
<td nowrap id="titulo4">Lamina_Bolsillo_1</td>
<td nowrap id="titulo4">Lamina_Bolsillo_2</td>
<td nowrap id="titulo4">Calibre_Bolsillo</td>
<td nowrap id="titulo4">Peso_Millar_Bolsillo</td>
<td nowrap id="titulo4">Precio_Maximo</td>
<td nowrap id="titulo4">Tasa_2_12</td>
  
    </tr>               
    <?php do { ?>
    <tr> 
     <td style="text-align: left;" id="dato1"> <?php echo $row_ordenes_compra['Ref']; ?> </td> 
     <td style="text-align: left;" id="dato1"> <?php echo $row_ordenes_compra['Cliente']; ?> </td>
     <td style="text-align: left;" id="dato1"> <?php echo $row_ordenes_compra['Unidad']; ?> </td> 
     <td style="text-align: left;" id="dato1"> <?php echo $row_ordenes_compra['Moneda']; ?> </td> 
     <td style="text-align: left;" id="dato1"> <?php echo $row_ordenes_compra['Ancho_Bolsa']; ?> </td> 
     <td style="text-align: left;" id="dato1"> <?php echo $row_ordenes_compra['Alto_Bolsa']; ?> </td>
     <td style="text-align: left;" id="dato1"> <?php echo $row_ordenes_compra['Fuelle']; ?> </td>
     <td style="text-align: left;" id="dato1"> <?php echo $row_ordenes_compra['solapa_Bolsa']; ?> </td>  
     <td style="text-align: left;" id="dato1"> <?php echo $row_ordenes_compra['Calibre_Bolsa']; ?> </td> 
     <td style="text-align: left;" id="dato1"> <?php echo $row_ordenes_compra['Peso_millar_Bolsa']; ?> </td>  
     <td style="text-align: left;" id="dato1"> <?php echo $row_ordenes_compra['Bolsillo']; ?> </td> 
     <td style="text-align: left;" id="dato1"> <?php echo $row_ordenes_compra['Lamina_Bolsillo_1']; ?> </td> 
     <td style="text-align: left;" id="dato1"> <?php echo $row_ordenes_compra['Lamina_Bolsillo_2']; ?> </td> 
     <td style="text-align: left;" id="dato1"> <?php echo $row_ordenes_compra['Calibre_Bolsillo']; ?> </td> 
     <td style="text-align: left;" id="dato1"> <?php echo $row_ordenes_compra['Peso_Millar_Bolsillo']; ?> </td> 
     <td style="text-align: left;" id="dato1"> <?php echo $row_ordenes_compra['Precio_Maximo']; ?> </td>  
     <td style="text-align: left;" id="dato1"> <?php echo $row_ordenes_compra['Tasa_2_12']; ?> </td>  
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