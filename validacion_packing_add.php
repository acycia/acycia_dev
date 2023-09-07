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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO Tbl_validacion_packing (id_val_p,id_rev_val_p,id_ref_val_p,version_ref_val_p,fecha_val_p,responsable_val_p,ancho_val_p,
observ_ancho_val_p,largo_val_p,observ_largo_val_p,calibre_val_p,observ_calibre_val_p,b_presentacion_val_p,observ_presentacion_val_p,
revi_ortog_val_p,observ_revi_ortog_val_p,rev_textos_val_p,observ_rev_textos_val_p,color_material_val_p,observ_color_material_val_p,color_ext_val_p,observ_color_ext_val_p,color_int_val_p,observ_int_val_p,b_boca_entr_val_p,observ_boca_entr_val_p,b_entrada_val_p,observ_entrada_val_p,b_lamina1_val_p,
observ_lamina1_val_p,b_lamina2_val_p,observ_lamina2_val_p,b_rodillo_val_p,observ_rodillo_val_p,1color_val_p,observ_1color_val_p,2color_val_p,observ_2color_val_p,3color_val_p,observ_3color_val_p,4color_val_p,observ_4color_val_p,5color_val_p,observ_5color_val_p,6color_val_p,observ_6color_val_p,7color_val_p,observ_7color_val_p,8color_val_p,observ_8color_val_p,marca_foto_val_p,observ_marca_foto_val_p,ref_val_p,observ_ref_val_p,num_paginaw_val_p,observ_num_paginaw_val_p,str_obs_general_p,userfile_p,estado_arte_val_p,fecha_aprob_arte_val_p,fecha_edit_val_p,responsable_edit_val_p) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
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
                       GetSQLValueString($_POST['responsable_edit_val_p'], "text"));
					   
  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());
  $insertGoTo = "validacion_packing_vista.php?id_val_p=" . $_POST['id_val_p'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
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

$colname_referencia_revision = "-1";
if (isset($_GET['id_ref'])) {
  $colname_referencia_revision = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_referencia_revision = sprintf("SELECT * FROM Tbl_referencia, Tbl_revision_packing WHERE Tbl_referencia.id_ref = %s AND Tbl_referencia.id_ref = Tbl_revision_packing.id_ref_rev_p", $colname_referencia_revision);
$referencia_revision = mysql_query($query_referencia_revision, $conexion1) or die(mysql_error());
$row_referencia_revision = mysql_fetch_assoc($referencia_revision);
$totalRows_referencia_revision = mysql_num_rows($referencia_revision);

mysql_select_db($database_conexion1, $conexion1);
$query_ultimo = "SELECT * FROM Tbl_validacion_packing ORDER BY id_val_p DESC";
$ultimo = mysql_query($query_ultimo, $conexion1) or die(mysql_error());
$row_ultimo = mysql_fetch_assoc($ultimo);
$totalRows_ultimo = mysql_num_rows($ultimo);

$colname_ref_egp = "-1";
if (isset($_GET['id_ref'])) {
  $colname_ref_egp = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ref_egp = sprintf("SELECT * FROM Tbl_referencia, Tbl_egp WHERE Tbl_referencia.id_ref = %s AND Tbl_referencia.n_egp_ref=Tbl_egp.n_egp", $colname_ref_egp);
$ref_egp = mysql_query($query_ref_egp, $conexion1) or die(mysql_error());
$row_ref_egp = mysql_fetch_assoc($ref_egp);
$totalRows_ref_egp = mysql_num_rows($ref_egp);

$colname_validacion = "-1";
if (isset($_GET['id_ref'])) {
  $colname_validacion = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_validacion = sprintf("SELECT * FROM Tbl_validacion_packing WHERE id_ref_val_p = %s", $colname_validacion);
$validacion = mysql_query($query_validacion, $conexion1) or die(mysql_error());
$row_validacion = mysql_fetch_assoc($validacion);
$totalRows_validacion = mysql_num_rows($validacion);

$colname_verificacion_edit = "-1";
if (isset($_GET['id_ref'])) {
  $colname_verificacion_edit = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_verificacion_edit = sprintf("SELECT * FROM Tbl_verificacion_packing WHERE id_ref_verif_p = %s", $colname_verificacion_edit);
$verificacion_edit = mysql_query($query_verificacion_edit, $conexion1) or die(mysql_error());
$row_verificacion_edit = mysql_fetch_assoc($verificacion_edit);
$totalRows_verificacion_edit = mysql_num_rows($verificacion_edit);

$colname_ficha_tecnica = "-1";
if (isset($_GET['id_ref'])) {
  $colname_ficha_tecnica = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ficha_tecnica = sprintf("SELECT * FROM TblFichaTecnica WHERE id_ref_ft = %s", $colname_ficha_tecnica);
$ficha_tecnica = mysql_query($query_ficha_tecnica, $conexion1) or die(mysql_error());
$row_ficha_tecnica = mysql_fetch_assoc($ficha_tecnica);
$totalRows_ficha_tecnica = mysql_num_rows($ficha_tecnica);

$colname_certificacion_ref = "-1";
if (isset($_GET['id_ref'])) {
  $colname_certificacion_ref = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_certificacion_ref = sprintf("SELECT * FROM TblCertificacion WHERE TblCertificacion.idref = %s",$colname_certificacion_ref);
$certificacion_ref = mysql_query($query_certificacion_ref, $conexion1) or die(mysql_error());
$row_certificacion_ref = mysql_fetch_assoc($certificacion_ref);
$totalRows_certificacion_ref = mysql_num_rows($certificacion_ref); 
?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
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
            PACKING LIST
            <input name="id_val_p" type="hidden" value="<?php $num=$row_ultimo['id_val_p']+1; echo $num; ?>" />
            <?php echo $num; ?></td>
          <td id="dato2"><a href="referencias.php"><img src="images/a.gif" alt="REF'S ACTIVAS" title="REF'S ACTIVAS" border="0" style="cursor:hand;"/></a><a href="referencias_inactivas.php"><img src="images/i.gif" alt="REF'S INACTIVAS"  title="REF'S INACTIVAS" border="0" style="cursor:hand;"/></a><a href="revision_packing_vista.php?id_rev_p=<?php echo $row_referencia_revision['id_rev_p']; ?>"><img src="images/r.gif" alt="REVISIONES" title="REVISIONES" border="0" style="cursor:hand;" /></a><a href="verificacion_referencia_packing.php?id_ref=<?php echo $row_referencia_revision['id_ref']; ?>"><img src="images/v.gif" alt="VERIFICACION X REF" title="VERIFICACION X REF" border="0" style="cursor:hand;" /></a><a href="verificacion.php"><img src="images/identico.gif" alt="LISTADO DE VERIFICACIONES" title="LISTADO DE VERIFICACIONES" border="0" style="cursor:hand;" /></a><?php if($row_validacion['id_val_p']=='') { ?><a href="validacion_packing_add.php?id_ref=<?php echo $_GET['id_ref']; ?>"><img src="images/v.gif" alt="ADD VALIDACION" title="ADD VALIDACION" border="0" style="cursor:hand;" /></a><?php } else{ ?><a href="validacion_packing_vista.php?id_val_p=<?php echo $row_validacion['id_val_p']; ?>"><img src="images/v.gif" alt="VALIDACION" title="VALIDACION" border="0" style="cursor:hand;" /></a><?php } ?><?php if($row_ficha_tecnica['n_ft']=='') { ?><a href="ficha_tecnica_add.php?id_ref=<?php echo $_GET['id_ref']; ?>"><img src="images/f.gif" alt="ADD FICHA TECNICA"  title="ADD FICHA TECNICA" border="0" style="cursor:hand;" /></a><?php } else { ?><a href="ficha_tecnica_vista.php?n_ft=<?php echo $row_ficha_tecnica['n_ft']; ?>"><img src="images/f.gif" alt="FICHA TECNICA" title="FICHA TECNICA" border="0" style="cursor:hand;" /></a><?php } ?>        <?php if($row_certificacion['idcc']=='') { ?>
        <a href="certificacion_add.php?id_ref=<?php echo $_GET['id_ref']; ?>" target="new"><img src="images/c.gif" alt="ADD CERTIFICACION" title="ADD CERTIFICACION" border="0" style="cursor:hand;"></a><?php } else { ?><a href="certificacion_listado.php?id_ref=<?php echo $_GET['id_ref']; ?>" target="new"><img src="images/c.gif" alt="CERTIFICACIONES" title="CERTIFICACIONES" border="0" style="cursor:hand;">
        </a><?php } ?></td>
        </tr>
        <tr id="tr1">
          <td id="fuente2">FECHA DE REGISTRO </td>
          <td colspan="2" id="fuente2">RESPONSABLE</td>
          </tr>
        <tr>
          <td id="dato2"><input name="fecha_val_p" type="text" value="<?php echo date("Y-m-d"); ?>" size="10" /></td>
          <td colspan="2" id="dato2"><input name="responsable_val_p" type="text" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="30" readonly /></td>
          </tr>
        <tr id="tr1">
          <td id="fuente2">REFERENCIA</td>
          <td id="fuente2">&nbsp;</td>
          <td id="fuente2">VERSION MODIF.</td>
        </tr>
        <tr>
          <td id="dato1">REF :
            <input name="id_ref_val_p" type="hidden" value="<?php echo $row_referencia_revision['id_ref']; ?>" />
            <a href="referencia_packing_vista.php?id_ref=<?php echo $row_referencia_revision['id_ref']; ?>&cod_ref=<?php echo $row_referencia_revision['cod_ref']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_top" style="text-decoration:none;" ><strong><?php echo $row_referencia_revision['cod_ref']; ?> - <?php echo $row_validacion['version_ref_val_p']; ?></strong></a><?php if ($row_referencia_revision['version_ref'] != $row_validacion['version_ref_val_p']) echo "<span class='rojo_normal'>"." Cambiar a vers: ".$row_referencia_revision['version_ref']."</spam>"?></td>
          <td id="dato2">&nbsp;</td>
          <td id="dato2"><strong>
            <input name="version_ref_val_p" type="text" value="<?php echo $row_referencia_revision['version_ref']; ?>" size="2" />
          </strong></td>
        </tr>
        <tr>
          <td id="dato1">REVISION
            <input name="id_rev_val_p" type="hidden" value="<?php echo $row_referencia_revision['id_rev_p']; ?>" />
: <a href="revision_packing_vista.php?id_rev_p=<?php echo $row_referencia_revision['id_rev_p']; ?>" target="_top" style="text-decoration:none;" ><strong><?php echo $row_referencia_revision['id_rev_p']; ?></strong></a></td>
          <td id="dato2">VERIFICACION : <a href="verificacion_packing_vista.php?id_verif_p=<?php echo $row_verificacion_edit['id_verif_p']; ?>" target="_top" style="text-decoration:none;" ><strong><?php echo $row_verificacion_edit['id_verif_p']; ?></strong></a> <a href="verificacion_referencia_packing.php?id_ref=<?php echo $row_referencia_revision['id_ref']; ?>"><img src="images/v.gif" alt="VERIFICACIONES X REF" border="0" style="cursor:hand;" ></a></td>
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
              <td id="detalle1"><input type="checkbox" name="ancho_val_p" value="1" />
                Ancho</td>
              <td id="detalle2"><input type="text" name="observ_ancho_val_p" value="<?php if($row_verificacion_edit['observ_ancho_verif_p']!=''){echo $row_verificacion_edit['observ_ancho_verif_p'];} ?>" size="50" /></td>
            </tr>
            <tr>
              <td id="detalle1">Largo: <?php echo $row_referencia_revision['largo_ref']; ?></td>
              <td id="detalle1"><input type="checkbox" name="largo_val_p" value="1">
                Largo</td>
              <td id="detalle2"><input type="text" name="observ_largo_val_p" value="<?php if($row_verificacion_edit['observ_largo_verif_p']!=''){echo $row_verificacion_edit['observ_largo_verif_p'];} ?>" size="50" /></td>
            </tr>
            <tr>
              <td id="detalle1">Calibre: <?php echo $row_referencia_revision['calibre_ref']; ?></td>
              <td id="detalle1"><input type="checkbox" name="calibre_val_p" value="1">
                Calibre</td>
              <td id="detalle2"><input type="text" name="observ_calibre_val_p" value="<?php if($row_verificacion_edit['observ_calibre_verif_p']!=''){echo $row_verificacion_edit['observ_calibre_verif_p'];} ?>" size="50" /></td>
            </tr>            
            <tr>
              <td id="detalle1">Presentacion: <?php echo $row_referencia_revision['Str_presentacion']; ?></td>
              <td id="detalle1"><input type="checkbox" name="b_presentacion_val_p" value="1">
                Presentacion</td>
              <td id="detalle2"><input type="text" name="observ_presentacion_val_p" value="<?php if($row_verificacion_edit['observ_presentacion_verif_p']!=''){echo $row_verificacion_edit['observ_presentacion_verif_p'];} ?>" size="50" /></td>
            </tr>
            <tr>
              <td id="detalle1">Revisi&oacute;n Ortografica</td>
              <td id="detalle1"><input type="checkbox" name="revi_ortog_val_p" value="1">
Revisi&oacute;n Ortografica</td>
              <td id="detalle2"><input type="text" name="observ_revi_ortog_val_p" value="<?php if($row_verificacion_edit['observ_revi_ortog_verif_p']!=''){echo $row_verificacion_edit['observ_revi_ortog_verif_p'];} ?>" size="50" /></td>
            </tr>
            <tr>
              <td id="detalle1">Revisi&oacute;n Textos</td>
              <td id="detalle1"><input type="checkbox" name="rev_textos_val_p" value="1">
Revisi&oacute;n Textos</td>
              <td id="detalle2"><input type="text" name="observ_rev_textos_val_p" value="<?php if($row_verificacion_edit['observ_rev_textos_verif_p']!=''){echo $row_verificacion_edit['observ_rev_textos_verif_p'];} ?>" size="50" /></td>
            </tr>
<tr>
              <td id="detalle1">Pigmento Exterior: <?php echo $row_referencia_revision['Str_lamina1_p']; ?></td>
              <td id="detalle1"><input type="checkbox" name="color_ext_val_p" value="1">Color Extrusi&oacute;n Exterior </td>
              <td id="detalle2"><input type="text" name="observ_color_ext_val_p" value="<?php if($row_verificacion_edit['observ_color_ext_verif_p']!=''){echo $row_verificacion_edit['observ_color_ext_verif_p'];} ?>" size="50" /></td>
            </tr>
            <tr>
              <td id="detalle1">Pigmento Interior: <?php echo $row_referencia_revision['Str_lamina2_p']; ?></td>
              <td id="detalle1"><input type="checkbox" name="color_int_val_p" value="1">Color Extrusi&oacute;n Interior</td>
              <td id="detalle2"><input type="text" name="observ_int_val_p" value="<?php if($row_verificacion_edit['observ_int_verif_p']!=''){echo  $row_verificacion_edit['observ_int_verif_p'];} ?>" size="50" /></td>
            </tr>            
            <tr>
              <td id="detalle1">Color Material Adhesivos</td>
              <td id="detalle1"><input name="color_material_val_p" type="checkbox" id="color_material_val_p" value="1">
                Color Material Adhesivos</td>
              <td id="detalle2"><input type="text" name="observ_color_material_val_p" value="<?php if($row_verificacion_edit['observ_color_material_verif_p']!=''){echo $row_verificacion_edit['observ_color_material_verif_p'];} ?>" size="50" /></td>
            </tr>
            <tr>
              <td id="detalle1">Boca de Entrada: <?php echo $row_referencia_revision['Str_boca_entr_p']; ?></td>
              <td id="detalle1"><input type="checkbox" name="b_boca_entr_val_p" value="1">
                Boca de Entrada</td>
              <td id="detalle2"><input type="text" name="observ_boca_entr_val_p" value="<?php if($row_verificacion_edit['observ_boca_entr_verif_p']!=''){echo $row_verificacion_edit['observ_boca_entr_verif_p'];} ?>" size="50" /></td>
            </tr>
            <tr>
              <td id="detalle1">Ubicacion de la Entrada: <?php echo $row_referencia_revision['Str_entrada_p']; ?></td>
              <td id="detalle1"><input type="checkbox" name="b_entrada_val_p" value="1">
                Ubicacion de la Entrada</td>
              <td id="detalle2"><input type="text" name="observ_entrada_val_p" value="<?php if($row_verificacion_edit['observ_entrada_verif_p']!=''){echo $row_verificacion_edit['observ_entrada_verif_p'];} ?>" size="50" /></td>
            </tr>
            <tr>
              <td id="detalle1">Lamina 1: <?php echo $row_referencia_revision['Str_lamina1_p']; ?></td>
              <td id="detalle1"><input type="checkbox" name="b_lamina1_val_p" value="1">
                Color Extrusi&oacute;n Interior</td>
              <td id="detalle2"><input type="text" name="observ_lamina1_val_p" value="<?php if($row_verificacion_edit['observ_lamina1_verif_p']!=''){echo $row_verificacion_edit['observ_lamina1_verif_p'];} ?>" size="50" /></td>
            </tr>
            <tr>
              <td id="detalle1">Lamina2: <?php echo $row_referencia_revision['Str_lamina2_p']; ?></td>
              <td id="detalle1"><input type="checkbox" name="b_lamina2_val_p" value="1">
Color Extrusi&oacute;n Interior</td>
              <td id="detalle2"><input type="text" name="observ_lamina2_val_p" value="<?php if($row_verificacion_edit['observ_lamina2_verif_p']!=''){echo $row_verificacion_edit['observ_lamina2_verif_p'];} ?>" size="50" /></td>
            </tr>
            <tr>
              <td id="detalle1">Rodillo: <?php echo $row_referencia_revision['int_rodillo_p']; ?></td>
              <td id="detalle1"><input type="checkbox" name="b_rodillo_val_p" value="1">
                Rodillo</td>
              <td id="detalle2"><input type="text" name="observ_rodillo_val_p" value="<?php if($row_verificacion_edit['observ_rodillo_verif_p']!=''){echo $row_verificacion_edit['observ_rodillo_verif_p'];} ?>" size="50" /></td>
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
              <td id="detalle2"><input name="1color_val_p" type="checkbox" id="1color_val_p" value="1" /></td>
              <td id="detalle2"><input type="text" name="observ_1color_val_p" value="<?php if($row_verificacion_edit['observ_1color_verif_p']!=''){echo $row_verificacion_edit['observ_1color_verif_p'];} ?>" size="60" /></td>
            </tr>
            <tr>
              <td id="detalle1"><strong>2 </strong>: <?php echo $row_ref_egp['color2_egp']; ?></td>
              <td id="detalle1">- <?php echo $row_ref_egp['pantone2_egp']; ?></td>
              <td id="detalle2"><input name="2color_val_p" type="checkbox" id="2color_val_p" value="1" /></td>
              <td id="detalle2"><input type="text" name="observ_2color_val_p" value="<?php if($row_verificacion_edit['observ_2color_verif_p']!=''){echo $row_verificacion_edit['observ_2color_verif_p'];} ?>" size="60" /></td>
            </tr>
            <tr>
              <td id="detalle1"><strong>3 </strong>: <?php echo $row_ref_egp['color3_egp']; ?></td>
              <td id="detalle1">- <?php echo $row_ref_egp['pantone3_egp']; ?></td>
              <td id="detalle2"><input name="3color_val_p" type="checkbox" id="3color_val_p" value="1"></td>
              <td id="detalle2"><input type="text" name="observ_3color_val_p" value="<?php if($row_verificacion_edit['observ_3color_verif_p']!=''){echo $row_verificacion_edit['observ_3color_verif_p'];} ?>" size="60" /></td>
            </tr>
            <tr>
              <td id="detalle1"><strong>4 </strong>: <?php echo $row_ref_egp['color4_egp']; ?></td>
              <td id="detalle1">- <?php echo $row_ref_egp['pantone4_egp']; ?></td>
              <td id="detalle2"><input name="4color_val_p" type="checkbox" id="4color_val_p" value="1"></td>
              <td id="detalle2"><input type="text" name="observ_4color_val_p" value="<?php if($row_verificacion_edit['observ_4color_verif_p']!=''){echo $row_verificacion_edit['observ_4color_verif_p'];} ?>" size="60" /></td>
            </tr>
            <tr>
              <td id="detalle1"><strong>5 </strong>: <?php echo $row_ref_egp['color5_egp']; ?></td>
              <td id="detalle1">- <?php echo $row_ref_egp['pantone5_egp']; ?></td>
              <td id="detalle2"><input name="5color_val_p" type="checkbox" id="5color_val_p" value="1"></td>
              <td id="detalle2"><input type="text" name="observ_5color_val_p" value="<?php if($row_verificacion_edit['observ_5color_verif_p']!=''){echo $row_verificacion_edit['observ_5color_verif_p'];} ?>" size="60" /></td>
            </tr>
            <tr>
              <td id="detalle1"><strong>6 </strong>: <?php echo $row_ref_egp['color6_egp']; ?></td>
              <td id="detalle1">- <?php echo $row_ref_egp['pantone6_egp']; ?></td>
              <td id="detalle2"><input name="6color_val_p" type="checkbox" id="6color_val_p" value="1"></td>
              <td id="detalle2"><input type="text" name="observ_6color_val_p" value="<?php if($row_verificacion_edit['observ_6color_verif_p']!=''){echo $row_verificacion_edit['observ_6color_verif_p'];} ?>" size="60" /></td>
            </tr>
            <tr>
              <td id="detalle1"><strong>7 </strong>: <?php echo $row_ref_egp['color7_egp']; ?></td>
              <td id="detalle1">- <?php echo $row_ref_egp['pantone7_egp']; ?></td>
              <td id="detalle2"><input name="7color_val_p" type="checkbox" id="7color_val_p" value="1"></td>
              <td id="detalle2"><input type="text" name="observ_7color_val_p" value="<?php if($row_verificacion_edit['observ_7color_verif_p']!=''){echo $row_verificacion_edit['observ_7color_verif_p'];} ?>" size="60" /></td>
            </tr>
            <tr>
              <td id="detalle1"><strong>8 </strong>: <?php echo $row_ref_egp['color8_egp']; ?></td>
              <td id="detalle1">- <?php echo $row_ref_egp['pantone8_egp']; ?></td>
              <td id="detalle2"><input name="8color_val_p" type="checkbox" id="8color_val_p" value="1"></td>
              <td id="detalle2"><input type="text" name="observ_8color_val_p" value="<?php if($row_verificacion_edit['observ_8color_verif_p']!=''){echo $row_verificacion_edit['observ_8color_verif_p'];} ?>" size="60" /></td>
            </tr>
                                    
            <tr>
              <td colspan="2" id="detalle1">MARCA DE FOTOCELDA</td>
              <td id="detalle2"><input type="checkbox" name="marca_foto_val_p" value="1"></td>
              <td id="detalle2"><input type="text" name="observ_marca_foto_val_p" value="<?php if($row_verificacion_edit['observ_marca_foto_verif_p']!=''){echo $row_verificacion_edit['observ_marca_foto_verif_p'];} ?>" size="60" /></td>
            </tr>
            <tr>
              <td colspan="2" id="detalle1">REFERENCIA </td>
              <td id="detalle2"><input type="checkbox" name="ref_val_p" value="1"></td>
              <td id="detalle2"><input type="text" name="observ_ref_val_p" value="<?php if($row_verificacion_edit['observ_ref_verif_p']!=''){echo $row_verificacion_edit['observ_ref_verif_p'];} ?>" size="60" /></td>
            </tr>
              <tr>
              <td colspan="2"id="detalle1">PAGINA WEB</td>
              <td id="detalle2"><input type="checkbox" name="num_paginaw_val_p" value="1"></td>
              <td id="detalle2"><input type="text" name="observ_num_paginaw_val_p" value="<?php if($row_verificacion_edit['observ_num_paginaw_verif_p']!=''){echo $row_verificacion_edit['observ_num_paginaw_verif_p'];} ?>" size="60" /></td>
            </tr>                       
          </table></td>
        </tr>

        <tr id="tr1">
          <td colspan="4" id="titulo4">OBSERVACIONES GENERALES </td>
        </tr>
        <tr>
          <td colspan="4" id="dato2"><textarea name="str_obs_general_p" cols="80" rows="2"><?php if($row_referencia_revision['str_obs_general_p']!=''){echo "Revision dice: "; echo $row_referencia_revision['str_obs_general_p'];} ?><?php echo "Obs Ref: ".$row_ref_egp['observacion5_egp']; ?></textarea>
            <input name="userfile_p" type="hidden" value="<?php echo $row_verificacion_edit['userfile_p']; ?>" />
            <input name="estado_arte_val_p" type="hidden" id="estado_arte_val_p" value="0" />
            <input name="fecha_aprob_arte_val_p" type="hidden" value="0000-00-00" /> 
            <input name="fecha_edit_val_p" type="hidden" value="" />
            <input name="responsable_edit_val_p" type="hidden" value="" /></td>
        </tr>


        <tr>
          <td colspan="4" id="dato2">&nbsp;</td>
        </tr>
        <tr id="tr1">
          <td colspan="4" id="dato2"><input type="submit" value="ADD VERIFICACION"></td>
        </tr>
      </table>
      <input type="hidden" name="MM_insert" value="form1">
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

mysql_free_result($ultimo);

mysql_free_result($ref_egp);

mysql_free_result($validacion);

mysql_free_result($verificacion_edit);

//mysql_free_result($ficha_tecnica);
?>
