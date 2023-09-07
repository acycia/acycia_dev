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
header('Content-Disposition: attachment; filename="Despachos.xls"');
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
 

 $anual=$_GET['fecha'];
 $mes=$_GET['mensual']; 
 $dia = $_GET['dia'];
 $fecha = $anual.'-'.$mes.'-'. $dia;

 ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
 </head>
<body>
<?php
 
//Filtra remision, FECHA
mysql_select_db($database_conexion1, $conexion1);
 if($anual != '0' && $mes == '0' && $dia == '0')
{
$query_items = "SELECT * FROM Tbl_items_ordenc, Tbl_remisiones  WHERE Tbl_items_ordenc.str_numero_io = Tbl_remisiones.str_numero_oc_r AND Tbl_remisiones.b_borrado_r='0' and  YEAR(Tbl_remisiones.fecha_r) = '$anual'  and Tbl_items_ordenc.fecha_despacho_io=Tbl_remisiones.fecha_r and Tbl_items_ordenc.int_cantidad_rest_io != Tbl_items_ordenc.int_cantidad_io ORDER BY Tbl_remisiones.fecha_r DESC";
}
if($anual != '0' && $mes != '0' && $dia == '0')
{
$query_items = "SELECT * FROM Tbl_items_ordenc, Tbl_remisiones  WHERE Tbl_items_ordenc.str_numero_io = Tbl_remisiones.str_numero_oc_r AND Tbl_remisiones.b_borrado_r='0' and  YEAR(Tbl_remisiones.fecha_r) = '$anual' AND MONTH(Tbl_remisiones.fecha_r) = '$mes' and Tbl_items_ordenc.fecha_despacho_io=Tbl_remisiones.fecha_r and Tbl_items_ordenc.int_cantidad_rest_io != Tbl_items_ordenc.int_cantidad_io  ORDER BY Tbl_remisiones.fecha_r DESC";
}
if($anual != '0' && $mes != '0' && $dia != '0')
{
$query_items = "SELECT * FROM Tbl_items_ordenc, Tbl_remisiones  WHERE Tbl_remisiones.str_numero_oc_r = Tbl_items_ordenc.str_numero_io  AND Tbl_remisiones.b_borrado_r='0' AND Tbl_remisiones.fecha_r =  '$fecha' and Tbl_items_ordenc.fecha_despacho_io=Tbl_remisiones.fecha_r  and Tbl_items_ordenc.int_cantidad_rest_io != Tbl_items_ordenc.int_cantidad_io ORDER BY Tbl_remisiones.fecha_r DESC";
}/*
else{
  $query_items = "SELECT * FROM Tbl_items_ordenc, Tbl_remisiones  WHERE Tbl_items_ordenc.str_numero_io = Tbl_remisiones.str_numero_oc_r ORDER BY Tbl_remisiones.fecha_r ASC";
}*/
$items = mysql_query($query_items, $conexion1) or die(mysql_error());
$row_items = mysql_fetch_assoc($items);
$totalRows_items = mysql_num_rows($items);

?>

<table id="tabla1" border=1>
              <tr> 
                <td id="nivel2">FECHA ENTREGA REM.</td>
                <td id="nivel2">CLIENTE</td>
                <td id="nivel2">O.C</td>
                <td id="nivel2">REF. AC</td> 
                <td id="nivel2">CANT. SOLICITADA</td>
                <td id="nivel2">CANT. DESPACHADA</td>
                <td id="nivel2">CANT. PENDIENTE</td>
                <td id="nivel2">TRANSPORTADOR</td>
                <td id="nivel2">GUIA</td>
                <td id="nivel2">FACTURA</td>                
                <td id="nivel2">REMISION</td>
                <td id="nivel2">CIUDAD</td>
                <td id="nivel2">DIRECCION ENTREGA</td>
                </tr>                
              <?php do { ?>
                <tr> 
                  <td id="talla2"><?php echo $row_items['fecha_r'];//$row_items['fecha_entrega_io']; ?></td>
                  <td id="talla2"><?php $clientes=$row_items['id_pedido_io'];
                        if(!empty($clientes))
                        {
                            $sqlmp="SELECT c.nombre_c, c.direccion_c, c.ciudad_c, c.pais_c FROM Tbl_orden_compra as oc, cliente as c WHERE oc.id_c_oc= c.id_c and oc.id_pedido = '$clientes'";
                            $resultmp= mysql_query($sqlmp);
                            $nump= mysql_num_rows($resultmp);
                            if($nump >='1') 
                            { 
                               $nombre_c = mysql_result($resultmp,0,'nombre_c'); 
                               $direccion_c = mysql_result($resultmp,0,'direccion_c'); 
                               $ciudad_c = mysql_result($resultmp,0,'ciudad_c'); 
                               $pais_c = mysql_result($resultmp,0,'pais_c'); 
                            } 
                      }  echo $nombre_c; ?></td>
                  <td id="talla2"><?php echo $row_items['str_numero_oc_r']; ?></td>                  
                  <td id="talla1"><?php echo $row_items['int_cod_ref_io']; ?> </td> 
                  <td id="talla2"><?php echo $row_items['int_cantidad_io']; ?></td>


                  <td id="talla2"><?php echo $despachada=($row_items['int_cantidad_io']-$row_items['int_cantidad_rest_io']); ?></td>
                  <td  id="talla2"><?php if($row_items['int_cantidad_rest_io']==''){echo '0';}else{echo $row_items['int_cantidad_rest_io'];} ?></td>
                  <td  id="talla2"><?php echo $row_items['str_transportador_r']; ?></td>
                  <td  id="talla2"><?php echo $row_items['str_guia_r']; ?></td>
                  <td  id="talla2"><?php echo $row_items['factura_r']; ?></td>

                  <td  id="talla2"><?php echo $row_items['int_remision']; ?></td> 
                  <td  id="talla2"><?php  echo htmlentities($pais_c) . ' / ' . $cad2=htmlentities($ciudad_c); ?></td>
                  <td id="talla2"><?php echo htmlentities($row_items['str_direccion_desp_io']); ?></td> 
                </tr>
                <?php } while ($row_items = mysql_fetch_assoc($items)); ?>

            </table>

</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($ano);

mysql_free_result($mezclas);

mysql_free_result($id_pm);
?>