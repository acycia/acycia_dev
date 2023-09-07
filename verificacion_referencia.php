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

$colname_verificacion_ref = "-1";
if (isset($_GET['id_ref'])) {
  $colname_verificacion_ref = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_verificacion_ref = sprintf("SELECT * FROM verificacion WHERE id_ref_verif = %s ORDER BY id_verif ASC", $colname_verificacion_ref);
$verificacion_ref = mysql_query($query_verificacion_ref, $conexion1) or die(mysql_error());
$row_verificacion_ref = mysql_fetch_assoc($verificacion_ref);
$totalRows_verificacion_ref = mysql_num_rows($verificacion_ref);

$colname_revision = "-1";
if (isset($_GET['id_ref'])) {
  $colname_revision = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_revision = sprintf("SELECT * FROM revision WHERE id_ref_rev = %s", $colname_revision);
$revision = mysql_query($query_revision, $conexion1) or die(mysql_error());
$row_revision = mysql_fetch_assoc($revision);
$totalRows_revision = mysql_num_rows($revision);

$colname_verificacion_ultimo = "-1";
if (isset($_GET['id_ref'])) {
  $colname_verificacion_ultimo = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_verificacion_ultimo = sprintf("SELECT * FROM verificacion WHERE id_ref_verif = %s ORDER BY id_verif DESC", $colname_verificacion_ultimo);
$verificacion_ultimo = mysql_query($query_verificacion_ultimo, $conexion1) or die(mysql_error());
$row_verificacion_ultimo = mysql_fetch_assoc($verificacion_ultimo);
$totalRows_verificacion_ultimo = mysql_num_rows($verificacion_ultimo);

$colname_validacion = "-1";
if (isset($_GET['id_ref'])) {
  $colname_validacion = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_validacion = sprintf("SELECT * FROM validacion WHERE id_ref_val = %s", $colname_validacion);
$validacion = mysql_query($query_validacion, $conexion1) or die(mysql_error());
$row_validacion = mysql_fetch_assoc($validacion);
$totalRows_validacion = mysql_num_rows($validacion);

$colname_ficha_tecnica = "-1";
if (isset($_GET['id_ref'])) {
  $colname_ficha_tecnica = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ficha_tecnica = sprintf("SELECT * FROM TblFichaTecnica WHERE id_ref_ft = %s", $colname_ficha_tecnica);
$ficha_tecnica = mysql_query($query_ficha_tecnica, $conexion1) or die(mysql_error());
$row_ficha_tecnica = mysql_fetch_assoc($ficha_tecnica);
$totalRows_ficha_tecnica = mysql_num_rows($ficha_tecnica);
?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/listado.js"></script>
</head>
<body>
<div align="center">
<table id="tabla"><tr align="center"><td align="center">
<div> 
<b class="spiffy"> 
<b class="spiffy1"><b></b></b>
<b class="spiffy2"><b></b></b>
<b class="spiffy3"></b>
<b class="spiffy4"></b>
<b class="spiffy5"></b></b>
<div class="spiffy_content">
<table id="tabla1">
<tr>
<td colspan="2" align="center"><img src="images/cabecera.jpg"></td></tr>
<tr>
<td id="nombreusuario"><?php echo $row_usuario['nombre_usuario']; ?></td>
<td id="cabezamenu"><ul id="menuhorizontal">
<li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
<li><a href="menu.php">MENU PRINCIPAL</a></li>
<li><a href="referencia_copia.php">LISTADO REFERENCIA</a></li>
</ul>
</td></tr>
<tr><td colspan="2" align="center" id="linea1">
<table id="tabla1">
      <tr>
        <td id="codigo">CODIGO: R2-F01</td>
        <td id="titulo2">PLAN DE DISE&Ntilde;O Y DESARROLLO</td>
        <td id="codigo">VERSION: 3</td>
        </tr>
      <tr>
        <td id="fuente2"><a href="referencias.php"><img src="images/a.gif" alt="REF'S ACTIVAS" border="0" style="cursor:hand;"/></a><a href="referencias_inactivas.php"><img src="images/i.gif" alt="REF'S INACTIVAS" border="0" style="cursor:hand;"/></a>
		<?php $ref=$_GET['id_ref']; $sqlrev="SELECT * FROM revision WHERE id_ref_rev='$ref'";
	  $resultrev= mysql_query($sqlrev);
	  $row_rev = mysql_fetch_assoc($resultrev);
	  $numrev= mysql_num_rows($resultrev);
	  if($numrev >='1') { $id_rev = mysql_result($resultrev, 0, 'id_rev'); ?> <a href="revision_vista.php?id_rev=<?php echo $id_rev; ?>"><img src="images/r.gif" alt="REVISION" border="0" style="cursor:hand;" /></a> <?php } else{ ?> <a href="revision_add.php?id_ref=<?php echo $_GET['id_ref']; ?>"><img src="images/r.gif" alt="REVISION" border="0" style="cursor:hand;" /></a> <?php }?>
	  <?php
	  $id_rev=$row_revision['id_rev'];
	  $id_verif=$row_verificacion_ultimo['id_verif'];
	  $ultimo=$row_verificacion_ultimo['estado_arte_verif'];
	  if($id_verif=='' && $id_rev!='')//agregue && $id_rev!='' para desaparecer el + del menu
	  { ?> <a href="verificacion_add.php?id_ref=<?php echo $row_revision['id_ref_rev']; ?>">
	   <img src="images/mas.gif" alt="ADD VERIFICACION" border="0" style="cursor:hand;"/>
	   </a> 
	   <?php }
	  $sqlcm="SELECT * FROM control_modificaciones WHERE id_verif_cm='$id_verif'";
	  $resultcm= mysql_query($sqlcm);
	  $row_cm = mysql_fetch_assoc($resultcm);
	  $numcm= mysql_num_rows($resultcm);	  
	  if($id_rev !='' && $ultimo != '0' && $ultimo != '2' && $numcm >= '1') { ?>
	   <a href="verificacion_add.php?id_ref=<?php echo $row_revision['id_ref_rev']; ?>">
	   <img src="images/mas.gif" alt="ADD VERIFICACION" border="0" style="cursor:hand;"/>
	   </a> <?php } ?> </td>
        <td id="subtitulo">II . VERIFICACION</td>
        <td id="fuente2"><a href="verificacion.php"><img src="images/identico.gif" alt="LISTADO DE VERIFICACIONES" border="0" style="cursor:hand;" /></a><?php if($row_validacion['id_val']=='') { ?><a href="validacion_add.php?id_ref=<?php echo $_GET['id_ref']; ?>"><img src="images/v.gif" alt="ADD VALIDACION" border="0" style="cursor:hand;" /></a><?php } else{ ?><a href="validacion_vista.php?id_val=<?php echo $row_validacion['id_val']; ?>"><img src="images/v.gif" alt="VALIDACION" border="0" style="cursor:hand;" /></a><?php } ?><?php if($row_ficha_tecnica['n_ft']=='') { ?><a href="ficha_tecnica_add.php?id_ref=<?php echo $_GET['id_ref']; ?>"><img src="images/f.gif" alt="ADD FICHA TECNICA" border="0" style="cursor:hand;" /></a><?php } else { ?><a href="ficha_tecnica_vista.php?n_ft=<?php echo $row_ficha_tecnica['n_ft']; ?>"><img src="images/f.gif" alt="FICHA TECNICA" border="0" style="cursor:hand;" /></a><?php } ?></td>
      </tr>
</table></td>
</tr>
  <tr>
    <td colspan="3" align="center" id="numero2"><?php if($row_revision['id_rev'] == '') { echo "Primero debe crear una REVISION"; }?></td>
  </tr><?php if($row_revision['id_rev'] != '') {  ?>
  <tr>
    <td colspan="3" align="center" id="numero2"><table id="tabla1">
  <tr id="tr1">
    <td id="titulo4">N&ordm;</td>
    <td id="titulo4">FECHA</td>
    <td id="titulo4">RESPONSABLE</td>
    <td id="titulo4">REF</td>
    <td id="titulo4">ARTE</td>
    <td id="titulo4">ESTADO</td>
    <td id="titulo4">FECHA ARTE</td>
    <td id="titulo4">CIREL</td>
    <td id="titulo4">VERIF.</td>
    <td id="titulo4"><a href="control_modificaciones.php"><img src="images/m.gif" alt="CONTROL DE MODIFICACIONES" title="CONTROL DE MODIFICACIONES" border="0" /></a></td>
    </tr>
  <?php do { ?>
    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
      <td id="dato3"><a href="verificacion_vista.php?id_verif= <?php echo $row_verificacion_ref['id_verif']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_verificacion_ref['id_verif']; ?></a></td>
      <td nowrap id="dato2"><a href="verificacion_vista.php?id_verif= <?php echo $row_verificacion_ref['id_verif']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_verificacion_ref['fecha_verif']; ?></a></td>
      <td id="dato1"><a href="verificacion_vista.php?id_verif= <?php echo $row_verificacion_ref['id_verif']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php $cad = htmlentities( $row_verificacion_ref['responsable_verif']);echo $cad; ?></a></td>
      <td id="dato2"><a href="verificacion_vista.php?id_verif= <?php echo $row_verificacion_ref['id_verif']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php $ref=$row_verificacion_ref['id_ref_verif'];
	  $sqlref="SELECT * FROM Tbl_referencia WHERE id_ref='$ref'";
	  $resultref= mysql_query($sqlref);
	  $row_ref = mysql_fetch_assoc($resultref);
	  $numref= mysql_num_rows($resultref);
	  if($numref >='1') { $referencia = mysql_result($resultref, 0, 'cod_ref');
	  $id_verif=$row_verificacion_ref['id_verif'];
	  $sqlverif="SELECT * FROM verificacion WHERE id_verif='$id_verif'";
	  $resultverif= mysql_query($sqlverif);
	  $row_verif = mysql_fetch_assoc($resultverif);
	  $numverif= mysql_num_rows($resultverif);
	  if($numverif >='1') {
	  $version = mysql_result($resultverif, 0, 'version_ref_verif');
	  echo $referencia." - ".$version;
	  } } else{ echo "- -"; }?></a></td>
      <td id="dato2"><?php if($row_verificacion_ref['id_verif']!='') { $muestra = $row_verificacion_ref['userfile']; ?> <?php if($muestra!='') {?><a href="javascript:verFoto('archivo/<?php echo $muestra;?>','610','490')"><img src="images/arte.gif" alt="ARTE" title="ARTE" border="0" style="cursor:hand;"  /></a><?php } ?><?php } ?></td>
      <td id="dato2"><a href="verificacion_vista.php?id_verif= <?php echo $row_verificacion_ref['id_verif']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php if($row_verificacion_ref['estado_arte_verif']=='0') { echo "Pendiente"; } if($row_verificacion_ref['estado_arte_verif']=='1') { echo "Rechazado"; } if($row_verificacion_ref['estado_arte_verif']=='2') { echo "Aceptado"; } if($row_verificacion_ref['estado_arte_verif']=='3') { echo "Anulado"; } ?></a></td>
      <td nowrap id="dato2"><a href="verificacion_vista.php?id_verif= <?php echo $row_verificacion_ref['id_verif']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_verificacion_ref['fecha_aprob_arte_verif']; ?></a></td>
      <td nowrap id="dato2"><?php if($row_verificacion_ref['fecha_entrega_cirel'] == '0000-00-00') { echo ""; } else { echo $row_verificacion_ref['fecha_entrega_cirel']; } ?>
      </td>
      <td id="dato2"><?php if($row_validacion['id_val'] == '') { ?> <a href="validacion_add.php?id_ref=<?php echo $row_verificacion_ref['id_ref_verif']; ?>"><img src="images/v.gif" alt="ADD VALIDACION" title="ADD VALIDACION" border="0" style="cursor:hand;" /></a><?php } else{ ?> <a href="validacion_vista.php?id_val=<?php echo $row_validacion['id_val']; ?>"><img src="images/v.gif" alt="VALIDACION" title="VALIDACION" border="0" style="cursor:hand;" /></a><?php } ?>
      </td>
      <td id="dato2">
	  <?php $estado=$row_verificacion_ref['estado_arte_verif'];
	  if($estado == '' || $estado == '0' || $estado == '2')
	  { echo "- -"; }
	  if($estado == '1' || $estado == '3')
	  { 
	  $cm=$row_verificacion_ref['id_verif'];
	  $sqlcm="SELECT * FROM control_modificaciones WHERE id_verif_cm='$cm'";
	  $resultcm= mysql_query($sqlcm);
	  $row_cm = mysql_fetch_assoc($resultcm);
	  $numcm= mysql_num_rows($resultcm);
	  if($numcm >='1')
	  { 
	  $cm = mysql_result($resultcm, 0, 'id_cm'); ?>
	  <a href="control_modif_edit.php?id_cm=<?php echo $cm; ?>">
	  <img src="images/m.gif" alt="EDIT MODIFICACION" title="EDIT MODIFICACION" border="0" style="cursor:hand;"/>	  </a>
	  <?php 
	  }
	  if($numcm < '1')
	  {
	  ?>
	  <a href="control_modif_add.php?id_verif=<?php echo $row_verificacion_ref['id_verif']; ?>">
	  <img src="images/falta.gif" alt="ADD MODIFICACION" title="ADD MODIFICACION" border="0" style="cursor:hand;"  />	  </a>
	  <?php
	  } 
	  }
	  ?>	  </td>
    </tr>
    <?php } while ($row_verificacion_ref = mysql_fetch_assoc($verificacion_ref)); ?>
</table></td>
</tr><?php } ?>
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

mysql_free_result($verificacion_ref);

mysql_free_result($revision);

mysql_free_result($verificacion_ultimo);

mysql_free_result($validacion);

mysql_free_result($ficha_tecnica);
?>
