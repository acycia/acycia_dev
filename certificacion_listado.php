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

$colname_verificacion = "-1";
if (isset($_GET['id_ref'])) {
  $colname_verificacion = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_verificacion = sprintf("SELECT * FROM verificacion WHERE id_ref_verif = %s ORDER BY id_verif ASC", $colname_verificacion);
$verificacion = mysql_query($query_verificacion, $conexion1) or die(mysql_error());
$row_verificacion = mysql_fetch_assoc($verificacion);
$totalRows_verificacion = mysql_num_rows($verificacion);

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
$query_ficha_tecnica = sprintf("SELECT n_ft,id_ref_ft FROM TblFichaTecnica WHERE id_ref_ft = %s", $colname_ficha_tecnica);
$ficha_tecnica = mysql_query($query_ficha_tecnica, $conexion1) or die(mysql_error());
$row_ficha_tecnica = mysql_fetch_assoc($ficha_tecnica);
$totalRows_ficha_tecnica = mysql_num_rows($ficha_tecnica);

$colname_certificacion_ref = "-1";
if (isset($_GET['id_ref'])) {
  $colname_certificacion_ref = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_certificacion_ref = sprintf("SELECT * FROM TblCertificacion  WHERE TblCertificacion.idref='%s'",$colname_certificacion_ref);
$certificacion_ref = mysql_query($query_certificacion_ref, $conexion1) or die(mysql_error());
$row_certificacion_ref = mysql_fetch_assoc($certificacion_ref);
$totalRows_certificacion_ref = mysql_num_rows($certificacion_ref);
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
        <td height="42" id="fuente2"><a href="referencias.php"><img src="images/a.gif" alt="REF'S ACTIVAS" title="REF'S ACTIVAS" border="0" style="cursor:hand;"/></a><a href="referencias_inactivas.php"><img src="images/i.gif" alt="REF'S INACTIVAS"  title="REF'S INACTIVAS" border="0" style="cursor:hand;"/></a>
		<?php if ($row_revision['id_rev']=='') {?><a href="revision_add.php?id_ref=<?php echo $_GET['id_ref']; ?>"><img src="images/r.gif" alt="REVISION" title="ADD REVISION" border="0" style="cursor:hand;" /></a> <?php } else{ ?><a href="revision_vista.php?id_rev=<?php echo $row_revision['id_rev']; ?>"><img src="images/r.gif" alt="REVISION" title="REVISION" border="0" style="cursor:hand;" /></a> <?php }?>
		<?php if ($row_verificacion_ultimo['id_verif']=='') {?><a href="verificacion_add.php?id_ref=<?php echo $_GET['id_ref']; ?>"><img src="images/v.gif" alt="ADD VERIFICACION" title="ADD VERIFICACION" border="0" style="cursor:hand;" /></a> <?php } else{ ?><a href="verificacion_vista.php?id_verif=<?php echo $row_verificacion_ultimo['id_verif']; ?>"><img src="images/v.gif" alt="VERIFICACION" title="VERIFICACION" border="0" style="cursor:hand;" /></a> <?php }?>
		</td>
        <td id="subtitulo">I . CERTIFICACION</td>
        <td id="fuente2"><a href="certificacion_add.php?id_ref=<?php echo $_GET['id_ref']; ?>"><img src="images/mas.gif" alt="ADD CERTIFICACION" title="ADD CERTIFICACION" border="0" style="cursor:hand;"/></a><?php if($row_validacion['id_val']=='') { ?><a href="validacion_add.php?id_ref=<?php echo $_GET['id_ref']; ?>"><img src="images/v.gif" alt="ADD VALIDACION" title="ADD VALIDACION" border="0" style="cursor:hand;" /></a><?php } else{ ?><a href="validacion_vista.php?id_val=<?php echo $row_validacion['id_val']; ?>"><img src="images/v.gif" alt="VALIDACION" title="VALIDACION" border="0" style="cursor:hand;" /></a><?php } ?><?php if($row_ficha_tecnica['n_ft']=='') { ?><a href="ficha_tecnica_add.php?id_ref=<?php echo $_GET['id_ref']; ?>"><img src="images/f.gif" alt="ADD FICHA TECNICA" title="ADD FICHA TECNICA" border="0" style="cursor:hand;" /></a><?php } else { ?><a href="ficha_tecnica_vista.php?n_ft=<?php echo $row_ficha_tecnica['n_ft']; ?>"><img src="images/f.gif" alt="FICHA TECNICA" title="FICHA TECNICA" border="0" style="cursor:hand;" /></a><?php } ?>
          <?php if($row_certificacion['idcc']=='') { ?>
          <a href="certificacion_add.php?id_ref=<?php echo $_GET['id_ref']; ?>" target="new"><img src="images/c.gif" alt="ADD CERTIFICACION" title="ADD CERTIFICACION" border="0" style="cursor:hand;"></a><?php } else { ?><a href="certificacion_listado.php?id_ref=<?php echo $_GET['id_ref']; ?>" target="new"><img src="images/c.gif" alt="CERTIFICACIONES" title="CERTIFICACIONES" border="0" style="cursor:hand;">
          </a><?php } ?></td>
      </tr>
</table></td>
</tr>
  <tr>
    <td colspan="3" align="center" id="numero2"><?php if($row_ficha_tecnica['id_ref_ft'] == '') { echo "Primero debe crear una FICHA TECNICA"; }?></td>
  </tr><?php if($row_ficha_tecnica['id_ref_ft'] != '') {  ?>
  <tr>
    <td colspan="3" align="center" id="numero2"><table id="tabla1">
  <tr id="tr1">
    <td id="titulo4">N&ordm;</td>
    <td id="titulo4">FECHA</td>
    <td id="titulo4">INSTRUMENTISTA</td>
    <td id="titulo4">REF</td>
    <td id="titulo4">O.C.</td>
    <td id="titulo4">FACTURA</td>
    <td id="titulo4">O.P.</td>
    <td id="titulo4">MODIFICO</td>
    </tr>
  <?php do { ?>
    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
      <td id="dato3"><a href="certificacion_vista.php?idcc=<?php echo $row_certificacion_ref['idcc']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_certificacion_ref['idcc']; ?></a></td>
      <td nowrap id="dato2"><a href="certificacion_vista.php?idcc=<?php echo $row_certificacion_ref['idcc']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_certificacion_ref['fecha']; ?></a></td>
      <td id="dato1"><a href="certificacion_vista.php?idcc=<?php echo $row_certificacion_ref['idcc']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php $cad = htmlentities( $row_certificacion_ref['instrumentista']);echo $cad; ?></a></td>
      <td id="dato2"><a href="certificacion_vista.php?idcc=<?php echo $row_certificacion_ref['idcc']; ?>" target="_top" style="text-decoration:none; color:#000000">
	  <?php echo $row_certificacion_ref['codref'];?></a></td>
      <td id="dato2"><?php echo $row_certificacion_ref['oc']; ?></td>
      <td id="dato2"><a href="certificacion_vista.php?idcc=<?php echo $row_certificacion_ref['idcc']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_certificacion_ref['factura']; ?></a></td>
      <td nowrap id="dato2"><a href="certificacion_vista.php?idcc=<?php echo $row_certificacion_ref['idcc']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_certificacion_ref['op']; ?></a><a href="certificacion_vista.php?idcc=<?php echo $row_certificacion_ref['idcc']; ?>" target="_top" style="text-decoration:none; color:#000000"></a></td>
      <td nowrap id="dato2"><a href="certificacion_vista.php?idcc=<?php echo $row_certificacion_ref['idcc']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_certificacion_ref['modifico']; ?></a></td>
    </tr>
    <?php } while ($row_certificacion_ref = mysql_fetch_assoc($certificacion_ref)); ?>
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
