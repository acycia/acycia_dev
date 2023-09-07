<?php require_once('Connections/conexion1.php'); ?>
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
$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

$colname_verificaciones = "-1";
if (isset($_GET['n_oc'])) {
  $colname_verificaciones = (get_magic_quotes_gpc()) ? $_GET['n_oc'] : addslashes($_GET['n_oc']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_verificaciones = sprintf("SELECT * FROM verificacion_insumos WHERE n_oc_vi = %s ORDER BY id_insumo_vi ASC", $colname_verificaciones);
$verificaciones = mysql_query($query_verificaciones, $conexion1) or die(mysql_error());
$row_verificaciones = mysql_fetch_assoc($verificaciones);
$totalRows_verificaciones = mysql_num_rows($verificaciones);

$colname_orden_compra = "-1";
if (isset($_GET['n_oc'])) {
  $colname_orden_compra = (get_magic_quotes_gpc()) ? $_GET['n_oc'] : addslashes($_GET['n_oc']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_orden_compra = sprintf("SELECT * FROM orden_compra WHERE n_oc = %s", $colname_orden_compra);
$orden_compra = mysql_query($query_orden_compra, $conexion1) or die(mysql_error());
$row_orden_compra = mysql_fetch_assoc($orden_compra);
$totalRows_orden_compra = mysql_num_rows($orden_compra);
?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/listado.js"></script>

<script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
<link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
</head>
<body>
<div align="center">
<table align="center" id="tabla"><tr align="center"><td>
<div> 
<b class="spiffy"> 
<b class="spiffy1"><b></b></b>
<b class="spiffy2"><b></b></b>
<b class="spiffy3"></b>
<b class="spiffy4"></b>
<b class="spiffy5"></b></b>
<div class="spiffy_content">
<table id="tabla1"><tr>
<td colspan="2" align="center"><img src="images/cabecera.jpg"></td></tr>
<tr><td id="nombreusuario"><?php echo $row_usuario['nombre_usuario']; ?></td>
  <td id="cabezamenu"><ul id="menuhorizontal">
  <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
<li><a href="menu.php">MENU PRINCIPAL</a></li>
<li><a href="compras.php">GESTION COMPRAS</a></li>
</ul></td>
</tr>  
  <tr>
    <td colspan="2" align="center"><table id="tabla1">
      <tr>
        <td colspan="7" id="subtitulo1">VERIFICACIONES X O.C. N&deg; <?php echo $row_orden_compra['n_oc']; ?> DE <a href="proveedor_vista.php?id_p=<?php echo $row_verificaciones['id_p_vi']; ?>" target="_top" style="text-decoration:none; color:#000000">
          <?php $id_p=$row_orden_compra['id_p_oc'];
		  $sqlp="SELECT * FROM proveedor WHERE id_p='$id_p'";
		  $resultp=mysql_query($sqlp);
		  $nump=mysql_num_rows($resultp);
		  if($nump >= '1')
		  { $proveedor_p=mysql_result($resultp,0,'proveedor_p'); echo $proveedor_p; } else { echo ""; } ?></a></td>
        <td id="subtitulo1"><a href="verificacion_insumo_add.php?n_oc=<?php echo $row_orden_compra['n_oc']; ?>"><img src="images/mas.gif" alt="ADD VERIFICACION" border="0" style="cursor:hand;"/></a><a href="verificaciones_criticos.php"><img src="images/cat.gif" style="cursor:hand;" alt="VERIFICACIONES (CRITICOS)" border="0"/></a><a href="orden_compra.php"><img src="images/o.gif" alt="ORDENES DE COMPRA" border="0" style="cursor:hand;"/></a><a href="insumos.php"><img src="images/i.gif" style="cursor:hand;" alt="INSUMOS" border="0"/></a></td>
        </tr><?php if($row_verificaciones['n_vi']=='') { ?>
      <tr>
        <td colspan="8" id="fuente2">&nbsp;</td>
        </tr>
      <tr>
        <td colspan="8" id="fuente2">Esta O.C. tiene insumos criticos, favor adicionar las verificaciones de recibo. </td>
        </tr><?php } else { ?>
      <tr id="tr2">
        <td id="titulo4"><img src="images/por.gif" alt="ELIMINAR"></td>
        <td id="titulo4">N&deg;</td>
        <td id="titulo4">INSUMO</td>
        <td id="titulo4">FECHA</td>
        <td id="titulo4">ENTREGA</td>
        <td id="titulo4">PEDIDO</td>
        <td id="titulo4">RECIBIDO</td>
        <td id="titulo4">FALTA</td>
        </tr>
      <?php do { ?>
        <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
          <td id="dato2"><a href="javascript:eliminar1('n_vi',<?php echo $row_verificaciones['n_vi']; ?>,'verificacion_insumo_oc.php')"><img src="images/por.gif" alt="ELIMINAR" border="0" style="cursor:hand;"/></a></td>
          <td id="dato3"><a href="verificacion_insumo_vista.php?n_vi=<?php echo $row_verificaciones['n_vi']; ?>" target="_top" style="text-decoration:none; color:#000000"><strong><?php echo $row_verificaciones['n_vi']; ?></strong></a></td>
          <td id="dato1"><a href="insumo_edit.php?id_insumo=<?php echo $row_verificaciones['id_insumo_vi']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php $id_insumo=$row_verificaciones['id_insumo_vi'];
	$sqli="SELECT * FROM insumo WHERE id_insumo='$id_insumo'"; 
	$resulti=mysql_query($sqli); 
	$numi=mysql_num_rows($resulti); 
	if($numi >= '1') 
	{ $descripcion_insumo=mysql_result($resulti,0,'descripcion_insumo'); echo $descripcion_insumo; }
	else { echo "";	} ?></a></td>
          <td id="dato2"><a href="verificacion_insumo_vista.php?n_vi=<?php echo $row_verificaciones['n_vi']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_verificaciones['fecha_vi']; ?></a></td>
          <td id="dato2"><a href="verificacion_insumo_vista.php?n_vi=<?php echo $row_verificaciones['n_vi']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_verificaciones['entrega_vi']; ?></a></td>
          <td id="dato3"><?php echo $row_verificaciones['cantidad_solicitada_vi']; ?></td>
          <td id="dato3"><?php echo $row_verificaciones['cantidad_recibida_vi']; ?></td>
          <td id="dato3"><?php echo $row_verificaciones['faltantes_vi']; ?></td>
          </tr>
        <?php } while ($row_verificaciones = mysql_fetch_assoc($verificaciones)); ?>
		<?php } ?>
    </table></td>
  </tr>
  <tr>
    <td colspan="2" align="center">&nbsp;</td>
  </tr>
</table>
</div>
<b class="spiffy"> 
<b class="spiffy5"></b>
<b class="spiffy4"></b>
<b class="spiffy3"></b>
<b class="spiffy2"><b></b></b>
<b class="spiffy1"><b></b></b></b></div> 
</td></tr></table>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($verificaciones);

mysql_free_result($orden_compra);
?>