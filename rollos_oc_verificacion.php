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

$colname_orden_compra_rollos = "-1";
if (isset($_GET['n_ocr'])) {
  $colname_orden_compra_rollos = (get_magic_quotes_gpc()) ? $_GET['n_ocr'] : addslashes($_GET['n_ocr']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_orden_compra_rollos = sprintf("SELECT * FROM orden_compra_rollos WHERE n_ocr = %s", $colname_orden_compra_rollos);
$orden_compra_rollos = mysql_query($query_orden_compra_rollos, $conexion1) or die(mysql_error());
$row_orden_compra_rollos = mysql_fetch_assoc($orden_compra_rollos);
$totalRows_orden_compra_rollos = mysql_num_rows($orden_compra_rollos);

$colname_verificaciones_rollos = "-1";
if (isset($_GET['n_ocr'])) {
  $colname_verificaciones_rollos = (get_magic_quotes_gpc()) ? $_GET['n_ocr'] : addslashes($_GET['n_ocr']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_verificaciones_rollos = sprintf("SELECT * FROM verificacion_rollos WHERE n_ocr_vr = %s ORDER BY n_vr DESC", $colname_verificaciones_rollos);
$verificaciones_rollos = mysql_query($query_verificaciones_rollos, $conexion1) or die(mysql_error());
$row_verificaciones_rollos = mysql_fetch_assoc($verificaciones_rollos);
$totalRows_verificaciones_rollos = mysql_num_rows($verificaciones_rollos);
?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
<script type="text/javascript" src="js/listado.js"></script>
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
  </ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1"><table id="tabla1">
      <tr>
        <td colspan="5" id="titulo1">VERIFICACION ( ROLLOS ) X O.C. N&deg; <a href="rollos_oc_vista.php?n_ocr=<?php echo $row_orden_compra_rollos['n_ocr']; ?>" target="_top" style="text-decoration:none; color:#FF0000"><?php echo $row_orden_compra_rollos['n_ocr']; ?></a> DE <?php $id_p=$row_orden_compra_rollos['id_p_ocr'];
		if($id_p!='') { 
		$sqlp="SELECT * FROM proveedor WHERE id_p='$id_p'";
		$resultp= mysql_query($sqlp);
		$nump= mysql_num_rows($resultp);
		if($nump >='1') { $proveedor_p = mysql_result($resultp,0,'proveedor_p'); 
		echo $proveedor_p; } } ?></td>
        <td id="dato3"><?php if($row_verificaciones_rollos['entrega_vr']=='' || $row_verificaciones_rollos['entrega_vr']=='0') { ?><a href="rollos_verificacion_add.php?n_ocr=<?php echo $row_orden_compra_rollos['n_ocr']; ?>" target="_top"><img src="images/mas.gif" alt="ADD VERIFICACION" border="0" style="cursor:hand;"/></a><?php } ?><a href="rollos_oc_verificacion.php?n_ocr=<?php $ocr=$row_orden_compra_rollos['n_ocr']-1; echo $ocr; ?>" target="_top"><img src="images/atras.gif" alt="ANTERIOR O.C." border="0" style="cursor:hand;"/></a><a href="rollos_oc_verificacion.php?n_ocr=<?php $ocr=$row_orden_compra_rollos['n_ocr']+1; echo $ocr; ?>" target="_top"><img src="images/adelante.gif" alt="NEXT O.C." border="0" style="cursor:hand;"/></a><a href="rollos_oc.php" target="_top"><img src="images/o.gif" alt="O.C. (ROLLOS)" border="0" style="cursor:hand;"/></a><a href="rollos_verificacion.php" target="_top"><img src="images/v.gif" alt="VERIFICACIONES (ROLLOS)" border="0" style="cursor:hand;"/></a><a href="rollos.php" target="_top"><img src="images/r.gif" alt="ROLLOS" border="0" style="cursor:hand;"/></a></td>
      </tr>
      <tr id="tr1">
        <td id="titulo4">N&deg;</td>
        <td id="titulo4">MATERIAL SOLICITADO</td>
        <td id="titulo4">FECHA RECIBO</td>
        <td id="titulo4">RECIBIDO POR</td>
        <td id="titulo4">ENTREGA</td>
        <td id="titulo4">FACTURA / REMISION</td>
      </tr>
      <?php do { ?>
        <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
            <td id="dato2"><a href="rollos_verificacion_vista.php?n_vr=<?php echo $row_verificaciones_rollos['n_vr']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_verificaciones_rollos['n_vr']; ?></a></td>
          <td id="dato1"><a href="rollos_verificacion_vista.php?n_vr=<?php echo $row_verificaciones_rollos['n_vr']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php $id_rollo=$row_verificaciones_rollos['id_rollo_vr'];
		  if($id_rollo!='') {
		  $sqlrollo="SELECT * FROM materia_prima_rollos WHERE id_rollo='$id_rollo'";
		  $resultrollo= mysql_query($sqlrollo);
		  $numrollo= mysql_num_rows($resultrollo);
		  if($numrollo >='1') { $nombre_rollo = mysql_result($resultrollo,0,'nombre_rollo');
		  echo $nombre_rollo; } } ?></a></td>
          <td id="dato2"><a href="rollos_verificacion_vista.php?n_vr=<?php echo $row_verificaciones_rollos['n_vr']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_verificaciones_rollos['fecha_recibo_vr']; ?></a></td>
          <td id="dato1"><a href="rollos_verificacion_vista.php?n_vr=<?php echo $row_verificaciones_rollos['n_vr']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_verificaciones_rollos['responsable_recibo_vr']; ?></a></td>
          <td id="dato2"><a href="rollos_verificacion_vista.php?n_vr=<?php echo $row_verificaciones_rollos['n_vr']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php $entrega=$row_verificaciones_rollos['entrega_vr']; if($entrega == '0') { echo "Parcial"; } if($entrega == '1') { echo "Total"; } ?></a></td>
          <td id="dato2"><a href="rollos_verificacion_vista.php?n_vr=<?php echo $row_verificaciones_rollos['n_vr']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_verificaciones_rollos['factura_vr']; ?> / <?php echo $row_verificaciones_rollos['remision_vr']; ?></a></td>
        </tr>
        <?php } while ($row_verificaciones_rollos = mysql_fetch_assoc($verificaciones_rollos)); ?>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
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

mysql_free_result($orden_compra_rollos);

mysql_free_result($verificaciones_rollos);
?>