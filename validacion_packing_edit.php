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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE Tbl_validacion_packing SET id_val_p=%s,id_rev_val_p=%s,id_ref_val_p=%s,version_ref_val_p=%s,fecha_val_p=%s,responsable_val_p=%s,ancho_val_p=%s,
observ_ancho_val_p=%s,largo_val_p=%s,observ_largo_val_p=%s,calibre_val_p=%s,observ_calibre_val_p=%s,b_presentacion_val_p=%s,observ_presentacion_val_p=%s,
revi_ortog_val_p=%s,observ_revi_ortog_val_p=%s,rev_textos_val_p=%s,observ_rev_textos_val_p=%s,color_material_val_p=%s,observ_color_material_val_p=%s,color_ext_val_p=%s,observ_color_ext_val_p=%s,color_int_val_p=%s,observ_int_val_p=%s,b_boca_entr_val_p=%s,observ_boca_entr_val_p=%s,b_entrada_val_p=%s,observ_entrada_val_p=%s,b_lamina1_val_p=%s,
observ_lamina1_val_p=%s,b_lamina2_val_p=%s,observ_lamina2_val_p=%s,b_rodillo_val_p=%s,observ_rodillo_val_p=%s,1color_val_p=%s,observ_1color_val_p=%s,2color_val_p=%s,observ_2color_val_p=%s,3color_val_p=%s,observ_3color_val_p=%s,4color_val_p=%s,observ_4color_val_p=%s,5color_val_p=%s,observ_5color_val_p=%s,6color_val_p=%s,observ_6color_val_p=%s,7color_val_p=%s,observ_7color_val_p=%s,8color_val_p=%s,observ_8color_val_p=%s,marca_foto_val_p=%s,observ_marca_foto_val_p=%s,ref_val_p=%s,observ_ref_val_p=%s,num_paginaw_val_p=%s,observ_num_paginaw_val_p=%s,str_obs_general_p=%s,userfile_p=%s,estado_arte_val_p=%s,fecha_aprob_arte_val_p=%s,fecha_edit_val_p=%s,responsable_edit_val_p=%s WHERE id_val_p=%s",
                       GetSQLValueString($_POST['id_val_p'], "int"),
					   GetSQLValueString($_POST['id_rev_val_p'], "int"),
                       GetSQLValueString($_POST['id_ref_val_p'], "int"),
					   GetSQLValueString($_POST['version_ref_val_p'], "text"),
                       GetSQLValueString($_POST['fecha_val_p'], "date"),
					   GetSQLValueString($_POST['responsable_val_p'], "text"),
                       GetSQLValueString(isset($_POST['ancho_val_p']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_ancho_val_p'], "text"),
                       GetSQLValueString(isset($_POST['largo_val_p']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_largo_val_p'], "text"),
                       GetSQLValueString(isset($_POST['calibre_val_p']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_calibre_val_p'], "text"),
                       GetSQLValueString(isset($_POST['b_presentacion_val_p']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_presentacion_val_p'], "text"),
                       GetSQLValueString(isset($_POST['revi_ortog_val_p']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_revi_ortog_val_p'], "text"),
                       GetSQLValueString(isset($_POST['rev_textos_val_p']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_rev_textos_val_p'], "text"),
                       GetSQLValueString(isset($_POST['color_material_val_p']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_color_material_val_p'], "text"),	
					   GetSQLValueString(isset($_POST['color_ext_val_p']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_color_ext_val_p'], "text"),
					   GetSQLValueString(isset($_POST['color_int_val_p']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_int_val_p'], "text"),			   
					   GetSQLValueString(isset($_POST['b_boca_entr_val_p']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_boca_entr_val_p'], "text"),	
					   GetSQLValueString(isset($_POST['b_entrada_val_p']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_entrada_val_p'], "text"),	
					   GetSQLValueString(isset($_POST['b_lamina1_val_p']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_lamina1_val_p'], "text"),	
					   GetSQLValueString(isset($_POST['b_lamina2_val_p']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_lamina2_val_p'], "text"),
					   GetSQLValueString(isset($_POST['b_rodillo_val_p']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_rodillo_val_p'], "text"),					   
                       GetSQLValueString(isset($_POST['1color_val_p']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_1color_val_p'], "text"),
                       GetSQLValueString(isset($_POST['2color_val_p']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_2color_val_p'], "text"),
                       GetSQLValueString(isset($_POST['3color_val_p']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_3color_val_p'], "text"),
                       GetSQLValueString(isset($_POST['4color_val_p']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_4color_val_p'], "text"),
                       GetSQLValueString(isset($_POST['5color_val_p']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_5color_val_p'], "text"),
                       GetSQLValueString(isset($_POST['6color_val_p']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_6color_val_p'], "text"),
                       GetSQLValueString(isset($_POST['7color_val_p']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_7color_val_p'], "text"),
                       GetSQLValueString(isset($_POST['8color_val_p']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_8color_val_p'], "text"),
					   GetSQLValueString(isset($_POST['marca_foto_val_p']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_marca_foto_val_p'], "text"),
					   GetSQLValueString(isset($_POST['ref_val_p']) ? "true" : "", "defined","1","0"),
					   GetSQLValueString($_POST['observ_ref_val_p'], "text"),
					   GetSQLValueString(isset($_POST['num_paginaw_val_p']) ? "true" : "", "defined","1","0"),
					   GetSQLValueString($_POST['observ_num_paginaw_val_p'], "text"),  					   					   
                       GetSQLValueString($_POST['str_obs_general_p'], "text"),
                       GetSQLValueString($_POST['userfile_p'], "text"),
                       GetSQLValueString($_POST['estado_arte_val_p'], "int"),
                       GetSQLValueString($_POST['fecha_aprob_arte_val_p'], "date"),
                       GetSQLValueString($_POST['fecha_edit_val_p'], "date"),
                       GetSQLValueString($_POST['responsable_edit_val_p'], "text"),
					   GetSQLValueString($_POST['id_val_p'], "int"));
					   			   

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());
  $updateGoTo = "validacion_packing_vista.php?id_val_p=" . $_POST['id_val_p'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

$colname_validacion = "-1";
if (isset($_GET['id_val_p'])) {
  $colname_validacion = (get_magic_quotes_gpc()) ? $_GET['id_val_p'] : addslashes($_GET['id_val_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_validacion = sprintf("SELECT * FROM Tbl_validacion_packing WHERE id_val_p = %s", $colname_validacion);
$validacion = mysql_query($query_validacion, $conexion1) or die(mysql_error());
$row_validacion = mysql_fetch_assoc($validacion);
$totalRows_validacion = mysql_num_rows($validacion);

$colname_referencia_revision = "-1";
if (isset($_GET['id_val_p'])) {
  $colname_referencia_revision = (get_magic_quotes_gpc()) ? $_GET['id_val_p'] : addslashes($_GET['id_val_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_referencia_revision = sprintf("SELECT * FROM Tbl_validacion_packing, Tbl_referencia WHERE Tbl_validacion_packing.id_val_p = %s AND Tbl_validacion_packing.id_ref_val_p = Tbl_referencia.id_ref", $colname_referencia_revision);
$referencia_revision = mysql_query($query_referencia_revision, $conexion1) or die(mysql_error());
$row_referencia_revision = mysql_fetch_assoc($referencia_revision);
$totalRows_referencia_revision= mysql_num_rows($referencia_revision);

$colname_verificacion = "-1";
if (isset($_GET['id_val_p'])) {
  $colname_verificacion = (get_magic_quotes_gpc()) ? $_GET['id_val_p'] : addslashes($_GET['id_val_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_verificacion = sprintf("SELECT * FROM Tbl_validacion_packing, Tbl_verificacion_packing WHERE Tbl_validacion_packing.id_val_p = '%s' AND Tbl_validacion_packing.id_rev_val_p = Tbl_verificacion_packing.id_verif_p", $colname_verificacion);
$verificacion = mysql_query($query_verificacion, $conexion1) or die(mysql_error());
$row_verificacion = mysql_fetch_assoc($verificacion);
$totalRows_verificacion = mysql_num_rows($verificacion);

$colname_revision = "-1";
if (isset($_GET['id_val_p'])) {
  $colname_revision = (get_magic_quotes_gpc()) ? $_GET['id_val_p'] : addslashes($_GET['id_val_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_revision = sprintf("SELECT * FROM Tbl_validacion_packing, Tbl_revision_packing WHERE Tbl_validacion_packing.id_val_p = '%s' AND Tbl_validacion_packing.id_rev_val_p = Tbl_revision_packing.id_rev_p ", $colname_revision);
$revision = mysql_query($query_revision, $conexion1) or die(mysql_error());
$row_revision = mysql_fetch_assoc($revision);
$totalRows_revision = mysql_num_rows($revision);

$colname_ref_egp = "-1";
if (isset($_GET['id_val_p'])) {
  $colname_ref_egp = (get_magic_quotes_gpc()) ? $_GET['id_val_p'] : addslashes($_GET['id_val_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ref_egp = sprintf("SELECT * FROM Tbl_validacion_packing, Tbl_referencia, Tbl_egp WHERE Tbl_validacion_packing.id_val_p=%s AND Tbl_validacion_packing.id_ref_val_p=Tbl_referencia.id_ref AND  Tbl_referencia.n_egp_ref=Tbl_egp.n_egp", $colname_ref_egp);
$ref_egp = mysql_query($query_ref_egp, $conexion1) or die(mysql_error());
$row_ref_egp = mysql_fetch_assoc($ref_egp);
$totalRows_ref_egp = mysql_num_rows($ref_egp);

$colname_ficha_tecnica = "-1";
if (isset($_GET['id_val_p'])) {
  $colname_ficha_tecnica = (get_magic_quotes_gpc()) ? $_GET['id_val_p'] : addslashes($_GET['id_val_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ficha_tecnica = sprintf("SELECT * FROM Tbl_validacion_packing, TblFichaTecnica WHERE Tbl_validacion_packing.id_val_p = %s AND Tbl_validacion_packing.id_ref_val_p = TblFichaTecnica.id_ref_ft", $colname_ficha_tecnica);
$ficha_tecnica = mysql_query($query_ficha_tecnica, $conexion1) or die(mysql_error());
$row_ficha_tecnica = mysql_fetch_assoc($ficha_tecnica);
$totalRows_ficha_tecnica = mysql_num_rows($ficha_tecnica);

$colname_certificacion_ref = "-1";
if (isset($_GET['id_val_p'])) {
  $colname_certificacion_ref = (get_magic_quotes_gpc()) ? $_GET['id_val_p'] : addslashes($_GET['id_val_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_certificacion_ref = sprintf("SELECT * FROM Tbl_validacion_packing,TblCertificacion WHERE Tbl_validacion_packing.id_val_p = %s AND Tbl_validacion_packing.id_ref_val_p = TblCertificacion.idref",$colname_certificacion_ref);
$certificacion_ref = mysql_query($query_certificacion_ref, $conexion1) or die(mysql_error());
$row_certificacion_ref = mysql_fetch_assoc($certificacion_ref);
$totalRows_certificacion_ref = mysql_num_rows($certificacion_ref);    


?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
<link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">

<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
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
<tr><td colspan="2" align="center">
<form method="post" name="form1" action="<?php echo $editFormAction; ?>" enctype="multipart/form-data">
      <table id="tabla2">
        <tr id="tr1">
          <td id="codigo" width="25%">CODIGO: R2-F01</td>
          <td colspan="2" nowrap="nowrap" id="titulo2">PLAN DE DISE&Ntilde;O &amp; DESARROLLO</td>
          <td id="codigo" width="25%">VERSION: 4</td>
        </tr>
        <tr>
          <td rowspan="6" id="dato2"><img src="images/logoacyc.jpg"/></td>
          <td colspan="2" id="subtitulo">ADD - II. VALIDACION 
            PACKING  <?php echo $row_validacion['id_val_p']; ?>
            </td>
          <td id="dato2"><a href="javascript:eliminar1('id_val_p',<?php echo $row_validacion['id_val_p']; ?>,'verificacion_packing_edit.php')"><img src="images/por.gif" alt="ELIMINAR" title="ELIMINAR" border="0" style="cursor:hand;" ></a><a href="referencias_p.php"><img src="images/a.gif" alt="REF'S ACTIVAS" title="REF'S ACTIVAS" border="0" style="cursor:hand;"/></a><a href="referencias_inactivas.php"><img src="images/i.gif" alt="REF'S INACTIVAS"  title="REF'S INACTIVAS" border="0" style="cursor:hand;"/></a><a href="revision_packing_vista.php?id_rev_p=<?php echo $row_validacion['id_rev_p']; ?>"><img src="images/r.gif" alt="REVISIONES" title="REVISIONES" border="0" style="cursor:hand;" /></a><a href="verificacion_referencia_packing.php?id_ref=<?php echo $row_referencia_revision['id_ref']; ?>"><img src="images/v.gif" alt="VERIFICACION X REF" title="VERIFICACION X REF" border="0" style="cursor:hand;" /></a><a href="verificacion_packing.php"><img src="images/identico.gif" alt="LISTADO DE VERIFICACIONES" title="LISTADO DE VERIFICACIONES" border="0" style="cursor:hand;" /></a><?php if($row_validacion['id_val_p']=='') { ?><a href="validacion_packing_add.php?id_ref=<?php echo $row_referencia_revision['id_ref']; ?>"><img src="images/v.gif" alt="ADD VALIDACION" title="ADD VALIDACION" border="0" style="cursor:hand;" /></a><?php } else{ ?><a href="validacion_packing_vista.php?id_val_p=<?php echo $row_validacion['id_val_p']; ?>"><img src="images/v.gif" alt="VALIDACION" title="VALIDACION" border="0" style="cursor:hand;" /></a><?php } ?><?php if($row_ficha_tecnica['n_ft']=='') { ?><a href="ficha_tecnica_packing_add.php?id_ref=<?php echo $row_referencia_revision['id_ref']; ?>"><img src="images/f.gif" alt="ADD FICHA TECNICA"  title="ADD FICHA TECNICA" border="0" style="cursor:hand;" /></a><?php } else { ?><a href="ficha_tecnica_packing_vista.php?n_ft=<?php echo $row_ficha_tecnica['n_ft']; ?>"><img src="images/f.gif" alt="FICHA TECNICA" title="FICHA TECNICA" border="0" style="cursor:hand;" /></a><?php } ?>
		  <?php if($row_certificacion['idcc']=='') { ?>
        <a href="certificacion_add.php?id_ref=<?php echo $row_referencia_revision['id_ref']; ?>" target="new"><img src="images/c.gif" alt="ADD CERTIFICACION" title="ADD CERTIFICACION" border="0" style="cursor:hand;"></a><?php } else { ?><a href="certificacion_listado.php?id_ref=<?php echo $row_referencia_revision['id_ref']; ?>" target="new"><img src="images/c.gif" alt="CERTIFICACIONES" title="CERTIFICACIONES" border="0" style="cursor:hand;">
        </a><?php } ?></td>
        </tr>
        <tr id="tr1">
          <td id="fuente2">FECHA DE REGISTRO </td>
          <td colspan="2" id="fuente2">RESPONSABLE</td>
          </tr>
        <tr>
          <td id="dato2"><input name="fecha_val_p" type="text" value="<?php echo $row_validacion['fecha_val_p']; ?>" size="10" /></td>
          <td colspan="2" id="dato2"><input name="responsable_val_p" type="text" value="<?php echo $row_validacion['responsable_val_p']; ?>" size="30" readonly /></td>
          </tr>
        <tr id="tr1">
          <td id="fuente2">REFERENCIA</td>
          <td id="fuente2">&nbsp;</td>
          <td id="fuente2">VERSION MODIF.</td>
        </tr>
        <tr>
          <td id="dato2">REF :
            <input name="id_ref_val_p" type="hidden" value="<?php echo $row_referencia_revision['id_ref']; ?>" />
            <a href="referencia_packing_vista.php?id_ref=<?php echo $row_referencia_revision['id_ref']; ?>&cod_ref=<?php echo $row_referencia_revision['cod_ref']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_top" style="text-decoration:none;" ><strong><?php echo $row_referencia_revision['cod_ref']; ?> - <?php echo $row_validacion['version_ref_val_p']; ?></strong></a><?php if ($row_referencia_revision['version_ref'] != $row_validacion['version_ref_val_p']) echo "<span class='rojo_normal'>"." Cambiar a vers: ".$row_referencia_revision['version_ref']."</spam>"?></td>
          <td id="dato2">&nbsp;</td>
          <td id="dato2"><strong>
            <input name="version_ref_val_p" type="text" value="<?php echo $row_referencia_revision['version_ref']; ?>" size="2" />
          </strong></td>
        </tr>
        <tr>
          <td id="dato2">REVISION
            <input name="id_rev_val_p" type="hidden" value="<?php echo $row_revision['id_rev_p']; ?>" />
: <a href="revision_packing_vista.php?id_rev_p=<?php echo $row_revision['id_rev_p']; ?>" target="_top" style="text-decoration:none;" ><strong><?php echo $row_revision['id_rev_p']; ?></strong></a></td>
          <td id="dato2">VERIFICACION : <a href="verificacion_packing_vista.php?id_verif_p=<?php echo $row_verificacion['id_verif_p']; ?>" target="_top" style="text-decoration:none;" ><strong><?php echo $row_verificacion['id_verif_p']; ?></strong></a> <a href="verificacion_referencia_packing.php?id_ref=<?php echo $row_referencia_revision['id_ref']; ?>"><img src="images/v.gif" alt="VERIFICACIONES X REF" border="0" style="cursor:hand;" ></a></td>
          <td id="dato2">COTIZACION N&ordm; <?php echo $row_referencia_revision['n_cotiz_ref']; ?></td>
        </tr>
        <tr id="tr1">
          <td colspan="4" id="titulo4">LISTADO DE VERIFICACION DE PARAMETROS GENERALES (Cumple Si / No)</td>
          </tr>
        <tr>
          <td colspan="4" align="center"><table id="tabla1">
            <tr id="tr1">
              <td id="fuente2">DATO</td>
              <td id="fuente2">CUMPLE</td>
              <td id="fuente2">OBSERVACION</td>
            </tr>
            <tr>
              <td id="detalle1">Ancho: <?php echo $row_referencia_revision['ancho_ref']; ?></td>
              <td id="detalle1"><input type="checkbox" name="ancho_val_p" value="1"<?php if (!(strcmp($row_validacion['ancho_val_p'],1))) {echo "checked=\"checked\"";} ?> />
                Ancho</td>
              <td id="detalle2"><input type="text" name="observ_ancho_val_p" value="<?php echo $row_validacion['observ_ancho_val_p'];?>" size="50" /></td>
            </tr>
            <tr>
              <td id="detalle1">Largo: <?php echo $row_validacion['largo_ref']; ?></td>
              <td id="detalle1"><input type="checkbox" name="largo_val_p" value="1"<?php if (!(strcmp($row_validacion['largo_val_p'],1))) {echo "checked=\"checked\"";} ?>>
                Largo</td>
              <td id="detalle2"><input type="text" name="observ_largo_val_p" value="<?php echo $row_validacion['observ_largo_val_p'];?>" size="50" /></td>
            </tr>
            <tr>
              <td id="detalle1">Calibre: <?php echo $row_validacion['calibre_ref']; ?></td>
              <td id="detalle1"><input type="checkbox" name="calibre_val_p" value="1"<?php if (!(strcmp($row_validacion['calibre_val_p'],1))) {echo "checked=\"checked\"";} ?>>
                Calibre</td>
              <td id="detalle2"><input type="text" name="observ_calibre_val_p" value="<?php echo $row_validacion['observ_calibre_val_p'];?>" size="50" /></td>
            </tr>            
            <tr>
              <td id="detalle1">Presentacion: <?php echo $row_validacion['Str_presentacion']; ?></td>
              <td id="detalle1"><input type="checkbox" name="b_presentacion_val_p" value="1"<?php if (!(strcmp($row_validacion['b_presentacion_val_p'],1))) {echo "checked=\"checked\"";} ?>>
                Presentacion</td>
              <td id="detalle2"><input type="text" name="observ_presentacion_val_p" value="<?php echo $row_validacion['observ_presentacion_val_p'];?>" size="50" /></td>
            </tr>
            <tr>
              <td id="detalle1">Revisi&oacute;n Ortografica</td>
              <td id="detalle1"><input type="checkbox" name="revi_ortog_val_p" value="1"<?php if (!(strcmp($row_validacion['revi_ortog_val_p'],1))) {echo "checked=\"checked\"";} ?>>
Revisi&oacute;n Ortografica</td>
              <td id="detalle2"><input type="text" name="observ_revi_ortog_val_p" value="<?php echo $row_validacion['observ_revi_ortog_val_p'];?>" size="50" /></td>
            </tr>
            <tr>
              <td id="detalle1">Revisi&oacute;n Textos</td>
              <td id="detalle1"><input type="checkbox" name="rev_textos_val_p" value="1"<?php if (!(strcmp($row_validacion['rev_textos_val_p'],1))) {echo "checked=\"checked\"";} ?>>
Revisi&oacute;n Textos</td>
              <td id="detalle2"><input type="text" name="observ_rev_textos_val_p" value="<?php echo $row_validacion['observ_rev_textos_val_p'];?>" size="50" /></td>
            </tr>
<tr>
              <td id="detalle1">Pigmento Exterior: <?php echo $row_ref_egp['pigm_ext_egp']; ?></td>
              <td id="detalle1"><input type="checkbox" name="color_ext_val_p" value="1"<?php if (!(strcmp($row_validacion['color_ext_val_p'],1))) {echo "checked=\"checked\"";} ?>>Color Extrusi&oacute;n Exterior </td>
              <td id="detalle2"><input type="text" name="observ_color_ext_val_p" value="<?php echo $row_validacion['observ_color_ext_val_p'];?>" size="50" /></td>
            </tr>
            <tr>
              <td id="detalle1">Pigmento Interior: <?php echo $row_ref_egp['pigm_int_epg']; ?></td>
              <td id="detalle1"><input type="checkbox" name="color_int_val_p" value="1"<?php if (!(strcmp($row_validacion['color_int_val_p'],1))) {echo "checked=\"checked\"";} ?>>Color Extrusi&oacute;n Interior</td>
              <td id="detalle2"><input type="text" name="observ_int_val_p" value="<?php echo $row_validacion['observ_int_val_p'];?>" size="50" /></td>
            </tr>            
            <tr>
              <td id="detalle1">Color Material Adhesivos: </td>
              <td id="detalle1"><input name="color_material_val_p" type="checkbox" id="color_material_val_p" value="1"<?php if (!(strcmp($row_validacion['color_material_val_p'],1))) {echo "checked=\"checked\"";} ?>>
                Color Material Adhesivo</td>
              <td id="detalle2"><input type="text" name="observ_color_material_val_p" value="<?php echo $row_validacion['observ_color_material_val_p'];?>" size="50" /></td>
            </tr>
            <tr>
              <td id="detalle1">Boca de Entrada: <?php echo $row_validacion['Str_boca_entr_p']; ?></td>
              <td id="detalle1"><input type="checkbox" name="b_boca_entr_val_p" value="1"<?php if (!(strcmp($row_validacion['b_boca_entr_val_p'],1))) {echo "checked=\"checked\"";} ?>>
                Boca de Entrada</td>
              <td id="detalle2"><input type="text" name="observ_boca_entr_val_p" value="<?php echo $row_validacion['observ_boca_entr_val_p'];?>" size="50" /></td>
            </tr>
            <tr>
              <td id="detalle1">Ubicacion de la Entrada: <?php echo $row_validacion['b_entrada_val_p']; ?></td>
              <td id="detalle1"><input type="checkbox" name="b_entrada_val_p" value="1"<?php if (!(strcmp($row_validacion['b_entrada_val_p'],1))) {echo "checked=\"checked\"";} ?>>
                Ubicacion de la Entrada</td>
              <td id="detalle2"><input type="text" name="observ_entrada_val_p" value="<?php echo $row_validacion['observ_entrada_val_p'];?>" size="50" /></td>
            </tr>
            <tr>
              <td id="detalle1">Lamina 1: 
                <?php if($row_validacion['Str_lamina1_p']=='0'){echo "NO";}else{ echo"SI";} ?></td>
              <td id="detalle1"><input type="checkbox" name="b_lamina1_val_p" value="1"<?php if (!(strcmp($row_validacion['b_lamina1_val_p'],1))) {echo "checked=\"checked\"";} ?>>
                Lamina 1</td>
              <td id="detalle2"><input type="text" name="observ_lamina1_val_p" value="<?php echo $row_validacion['observ_lamina1_val_p'];?>" size="50" /></td>
            </tr>
            <tr>
              <td id="detalle1">Lamina 2: 
                <?php if($row_validacion['Str_lamina2_p']=='0'){echo "NO";}else{ echo"SI";} ?></td>
              <td id="detalle1"><input type="checkbox" name="b_lamina2_val_p" value="1"<?php if (!(strcmp($row_validacion['b_lamina2_val_p'],1))) {echo "checked=\"checked\"";} ?>>
                Lamina 2</td>
              <td id="detalle2"><input type="text" name="observ_lamina2_val_p" value="<?php echo $row_validacion['observ_lamina2_val_p'];?>" size="50" /></td>
            </tr>
            <tr>
              <td id="detalle1">Rodillo: <?php echo $row_revision['int_rodillo_p']; ?></td>
              <td id="detalle1"><input type="checkbox" name="b_rodillo_val_p" value="1"<?php if (!(strcmp($row_validacion['b_rodillo_val_p'],1))) {echo "checked=\"checked\"";} ?>>
                Rodillo</td>
              <td id="detalle2"><input type="text" name="observ_rodillo_val_p" value="<?php echo $row_validacion['observ_rodillo_val_p'];?>" size="50" /></td>
            </tr>
            </table></td>
          </tr>
        <tr id="tr1">
          <td colspan="4" id="titulo4">VERIFICACION DE COLORES DE IMPRESION (Cumple Si / No)</td>
        </tr>
        <tr>
          <td colspan="4" align="center"><table id="tabla1">
            <tr id="tr1">
              <td id="fuente2">COLOR</td>
              <td id="fuente2">PANTONE</td>
              <td id="fuente2">CUMPLE</td>
              <td id="fuente2">OBSERVACION</td>
            </tr>
            <tr>
              <td id="detalle1"><strong>1 </strong>: <?php echo $row_ref_egp['color1_egp']; ?></td>
              <td id="detalle1">- <?php echo $row_ref_egp['pantone1_egp']; ?></td>
              <td id="detalle2"><input name="1color_val_p" type="checkbox" id="1color_val_p" value="1"<?php if (!(strcmp($row_validacion['1color_val_p'],1))) {echo "checked=\"checked\"";} ?>/></td>
              <td id="detalle2"><input type="text" name="observ_1color_val_p" value="<?php echo $row_validacion['observ_1color_val_p'];?>" size="60" /></td>
            </tr>
            <tr>
              <td id="detalle1"><strong>2 </strong>: <?php echo $row_ref_egp['color2_egp']; ?></td>
              <td id="detalle1">- <?php echo $row_ref_egp['pantone2_egp']; ?></td>
              <td id="detalle2"><input name="2color_val_p" type="checkbox" id="2color_val_p" value="1"<?php if (!(strcmp($row_validacion['2color_val_p'],1))) {echo "checked=\"checked\"";} ?>/></td>
              <td id="detalle2"><input type="text" name="observ_2color_val_p" value="<?php echo $row_validacion['observ_2color_val_p'];?>" size="60" /></td>
            </tr>
            <tr>
              <td id="detalle1"><strong>3 </strong>: <?php echo $row_ref_egp['color3_egp']; ?></td>
              <td id="detalle1">- <?php echo $row_ref_egp['pantone3_egp']; ?></td>
              <td id="detalle2"><input name="3color_val_p" type="checkbox" id="3color_val_p" value="1"<?php if (!(strcmp($row_validacion['3color_val_p'],1))) {echo "checked=\"checked\"";} ?>></td>
              <td id="detalle2"><input type="text" name="observ_3color_val_p" value="<?php echo $row_validacion['observ_3color_val_p'];?>" size="60" /></td>
            </tr>
            <tr>
              <td id="detalle1"><strong>4 </strong>: <?php echo $row_ref_egp['color4_egp']; ?></td>
              <td id="detalle1">- <?php echo $row_ref_egp['pantone4_egp']; ?></td>
              <td id="detalle2"><input name="4color_val_p" type="checkbox" id="4color_val_p" value="1"<?php if (!(strcmp($row_validacion['4color_val_p'],1))) {echo "checked=\"checked\"";} ?>></td>
              <td id="detalle2"><input type="text" name="observ_4color_val_p" value="<?php echo $row_validacion['observ_4color_val_p'];?>" size="60" /></td>
            </tr>
            <tr>
              <td id="detalle1"><strong>5 </strong>: <?php echo $row_ref_egp['color5_egp']; ?></td>
              <td id="detalle1">- <?php echo $row_ref_egp['pantone5_egp']; ?></td>
              <td id="detalle2"><input name="5color_val_p" type="checkbox" id="5color_val_p" value="1"<?php if (!(strcmp($row_validacion['5color_val_p'],1))) {echo "checked=\"checked\"";} ?>></td>
              <td id="detalle2"><input type="text" name="observ_5color_val_p" value="<?php echo $row_validacion['observ_5color_val_p'];?>" size="60" /></td>
            </tr>
            <tr>
              <td id="detalle1"><strong>6 </strong>: <?php echo $row_ref_egp['color6_egp']; ?></td>
              <td id="detalle1">- <?php echo $row_ref_egp['pantone6_egp']; ?></td>
              <td id="detalle2"><input name="6color_val_p" type="checkbox" id="6color_val_p" value="1"<?php if (!(strcmp($row_validacion['6color_val_p'],1))) {echo "checked=\"checked\"";} ?>></td>
              <td id="detalle2"><input type="text" name="observ_6color_val_p" value="<?php echo $row_validacion['observ_6color_val_p'];?>" size="60" /></td>
            </tr>
            <tr>
              <td id="detalle1"><strong>7 </strong>: <?php echo $row_ref_egp['color7_egp']; ?></td>
              <td id="detalle1">- <?php echo $row_ref_egp['pantone7_egp']; ?></td>
              <td id="detalle2"><input name="7color_val_p" type="checkbox" id="7color_val_p" value="1"<?php if (!(strcmp($row_validacion['7color_val_p'],1))) {echo "checked=\"checked\"";} ?>></td>
              <td id="detalle2"><input type="text" name="observ_7color_val_p" value="<?php echo $row_validacion['observ_7color_val_p'];?>" size="60" /></td>
            </tr>
            <tr>
              <td id="detalle1"><strong>8 </strong>: <?php echo $row_ref_egp['color8_egp']; ?></td>
              <td id="detalle1">- <?php echo $row_ref_egp['pantone8_egp']; ?></td>
              <td id="detalle2"><input name="8color_val_p" type="checkbox" id="8color_val_p" value="1"<?php if (!(strcmp($row_validacion['8color_val_p'],1))) {echo "checked=\"checked\"";} ?>></td>
              <td id="detalle2"><input type="text" name="observ_8color_val_p" value="<?php echo $row_validacion['observ_8color_val_p'];?>" size="60" /></td>
            </tr>
                                    
            <tr>
              <td colspan="2" id="detalle1">MARCA DE FOTOCELDA</td>
              <td id="detalle2"><input type="checkbox" name="marca_foto_val_p" value="1"<?php if (!(strcmp($row_validacion['marca_foto_val_p'],1))) {echo "checked=\"checked\"";} ?>></td>
              <td id="detalle2"><input type="text" name="observ_marca_foto_val_p" value="<?php echo $row_validacion['observ_marca_foto_val_p'];?>" size="60" /></td>
            </tr>
            <tr>
              <td colspan="2" id="detalle1">REFERENCIA</td>
              <td id="detalle2"><input <?php if (!(strcmp($row_validacion['ref_val_p'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="ref_val_p" value="1"></td>
              <td id="detalle2"><input type="text" name="observ_ref_val_p" value="<?php echo $row_validacion['observ_ref_val_p'];?>" size="60" /></td>
            </tr>
<tr>
              <td colspan="2" id="detalle1">PAGINA WEB: <?php if($row_validacion['num_paginaw_val_p']=='0'){echo "NO";}else{echo"SI";} ?>
              <td id="detalle2"><input type="checkbox" name="num_paginaw_val_p" value="1"<?php if (!(strcmp($row_validacion['num_paginaw_val_p'],1))) {echo "checked=\"checked\"";} ?>></td>
              <td id="detalle2"><input type="text" name="observ_num_paginaw_val_p" value="<?php echo $row_validacion['observ_num_paginaw_val_p'];?>" size="60" /></td>
            </tr>            
          </table></td>
        </tr>
<tr id="tr1">
          <td colspan="4" id="titulo4">OBSERVACIONES GENERALES </td>
        </tr>
        <tr>
          <td colspan="4" id="dato2"><textarea name="str_obs_general_p" cols="80" rows="2"><?php echo $row_validacion['str_obs_general_p'];?></textarea></td>
        </tr>        
<tr id="tr1">
  <td colspan="4" id="titulo4">ARTE</td>
</tr>
          <tr id="tr1">
            
          </tr>
          <tr id="tr1">
            <td colspan="4" id="titulo4">ULTIMA ACTUALIZACION</td>
            </tr>
          <tr>
            <td colspan="2" id="dato2">- 
              <input name="fecha_edit_val_p" type="hidden" value="<?php echo date("Y-m-d"); ?>" />
              <?php echo $row_validacion['fecha_edit_val_p']; ?> - </td>
            <td colspan="2" id="dato2">-  
              <input name="responsable_edit_val_p" type="hidden" value="<?php echo $row_usuario['nombre_usuario']; ?>" />
              <?php echo $row_validacion['responsable_edit_val_p']; ?> - </td>
            </tr>
        <tr id="tr1">
          <td colspan="4" id="dato2"><input name="userfile_p" type="hidden" value="<?php echo $row_validacion['userfile_p']; ?>" />
            <input type="submit" value="EDITAR VERIFICACION">
            </td>
        </tr>
      </table>
      <input name="id_val_p" type="hidden" value="<?php echo $row_validacion['id_val_p']; ?>" />
      <input type="hidden" name="MM_update" value="form1">
    </form></td></tr>
</table>
</div>
<b class="spiffy"> 
<b class="spiffy5"></b>
<b class="spiffy4"></b>
<b class="spiffy3"></b>
<b class="spiffy2"><b></b></b>
<b class="spiffy1"><b></b></b></b></div> 
 </td>
  </tr>  
</table>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($referencia_revision);

//mysql_free_result($ultimo);

mysql_free_result($ref_egp);

mysql_free_result($validacion);

mysql_free_result($verificacion);

//mysql_free_result($ficha_tecnica);
?>
