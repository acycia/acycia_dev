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
header('Content-Disposition: attachment; filename="comisiones.xls"');
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
?>
<?php
$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

//Filtro de datos segun la seleccion.
mysql_select_db($database_conexion1, $conexion1);
 
$vendedor=$_GET['vendedor'];
$cod_ref=$_GET['cod_ref']; 
 
//Todos vacios
if($vendedor =='0' && $cod_ref=='0'){
$query_cotizacion = "SELECT * FROM Tbl_items_ordenc WHERE b_estado_io >= '4' ORDER BY fecha_despacho_io DESC";
}
if($vendedor !='0' && $cod_ref=='0'){ 
$query_cotizacion = "SELECT * FROM Tbl_items_ordenc WHERE b_estado_io >= '4' AND int_vendedor_io =$vendedor ORDER BY fecha_despacho_io DESC";
}
if($vendedor =='0' && $cod_ref!='0'){ 
$query_cotizacion = "SELECT * FROM Tbl_items_ordenc WHERE b_estado_io >= '4' AND int_cod_ref_io =$cod_ref ORDER BY fecha_despacho_io DESC";
}
if($vendedor !='0' && $cod_ref!='0'){ 
$query_cotizacion = "SELECT * FROM Tbl_items_ordenc WHERE b_estado_io >= '4' AND int_vendedor_io = $vendedor AND int_cod_ref_io =$cod_ref ORDER BY fecha_despacho_io DESC";
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
  <table width="776" border="0" align="left" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <tr>
        <td width="104" bordercolor="#FFFFFF" bgcolor="#ECF5FF"> Vendedor </td>
        <td width="128" bordercolor="#FFFFFF" bgcolor="#ECF5FF">  Ref </td>
        <td width="250" bordercolor="#FFFFFF" bgcolor="#ECF5FF"> Orden C </td>
        <td width="100" bordercolor="#FFFFFF" bgcolor="#ECF5FF"> Cantidad </td>
        <td width="51" bordercolor="#FFFFFF" bgcolor="#ECF5FF"> Precio </td>
        <td width="70" bordercolor="#FFFFFF" bgcolor="#ECF5FF"> Comision  </td> 
        <td width="80" bordercolor="#FFFFFF" bgcolor="#ECF5FF" > Fecha Despacho </td>
      </tr>
	  <?php 
	  $i=0;  ?>
      <?php do {  ?>
 
       <tr <?php if ($i%2==0) {?> style="background:#FFFFFF" <?php }else {?>style="background:#ECF5FF" <?php } 
	  $i++; ?>>
          <td width="104"> <?php 
		  $nombre_com = $row_cotizacion['int_vendedor_io'];
		  $sqldato="SELECT nombre_vendedor FROM vendedor WHERE id_vendedor='$nombre_com'";
$resultdato=mysql_query($sqldato);
echo $nombre_vendedor=mysql_result($resultdato,0,'nombre_vendedor');
		   
 ?> </td>
          <td width="128"> <?php echo $row_cotizacion['int_cod_ref_io']; ?> </td>
          <td width="250"> <?php echo $row_cotizacion['str_numero_io']; ?> </td>
          <td width="100"> <?php echo $row_cotizacion['int_cantidad_io']; ?> </td>
          <td nowrap="nowrap"> <?php echo $row_cotizacion['int_precio_io']; ?> </td>
          <td width="51"> <?php echo $row_cotizacion['int_comision_io']; ?> </td>
           
          <td width="54"> <?php echo $row_cotizacion['fecha_despacho_io']; ?> </td>
        </tr>
        <?php } while ($row_cotizacion = mysql_fetch_assoc($cotizacion)); ?>
    </table>  
</body>
</html>
<?php
mysql_free_result($usuario);
mysql_free_result($costos);

?>