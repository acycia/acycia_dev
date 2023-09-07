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
  $insertSQL = sprintf("INSERT INTO Tbl_validacion_lamina (id_val_l,id_rev_val_l,id_ref_val_l,version_ref_val_l,fecha_val_l,responsable_val_l,ancho_val_l,
observ_ancho_val_l,largo_val_l,
observ_largo_val_l,calibre_val_l,observ_calibre_val_l,revi_ortog_val_l,observ_revi_ortog_val_l,rev_textos_val_l,observ_rev_textos_val_l,rev_extru_val_l,observ_rev_extru_val_l,color_ext_val_l,observ_color_ext_val_l,
color_int_val_l,observ_int_val_l,num_paginaw_val_l,observ_num_paginaw_val_l,rev_enbob_val_l,observ_rev_enbob_val_l,1color_val_l,observ_1color_val_l,2color_val_l,observ_2color_val_l,3color_val_l,observ_3color_val_l,4color_val_l,observ_4color_val_l,5color_val_l,observ_5color_val_l,6color_val_l,observ_6color_val_l,7color_val_l,observ_7color_val_l,8color_val_l,observ_8color_val_l,marca_foto_val_l,observ_marca_foto_val_l,ref_val_l,observ_ref_val_l,b_preimp_val_l,observ_b_preimp_val_l,observacion_val_l,userfile_l,estado_arte_val_l,fecha_aprob_arte_val_l,fecha_edit_val_l,
responsable_edit_val_l) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['id_val_l'], "int"),
					   GetSQLValueString($_POST['id_rev_val_l'], "int"),
                       GetSQLValueString($_POST['id_ref_val_l'], "int"),
					   GetSQLValueString($_POST['version_ref_val_l'], "text"),
                       GetSQLValueString($_POST['fecha_val_l'], "date"),
					   GetSQLValueString($_POST['responsable_val_l'], "text"),
                       GetSQLValueString(isset($_POST['ancho_val_l']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_ancho_val_l'], "text"),
                       GetSQLValueString(isset($_POST['largo_val_l']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_largo_val_l'], "text"),
                       GetSQLValueString(isset($_POST['calibre_val_l']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_calibre_val_l'], "text"),
                       GetSQLValueString(isset($_POST['revi_ortog_val_l']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_revi_ortog_val_l'], "text"),
                       GetSQLValueString(isset($_POST['rev_textos_val_l']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_rev_textos_val_l'], "text"),
                       GetSQLValueString(isset($_POST['rev_extru_val_l']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_rev_extru_val_l'], "text"),
                       GetSQLValueString(isset($_POST['color_ext_val_l']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_color_ext_val_l'], "text"),					   
					   GetSQLValueString(isset($_POST['color_int_val_l']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_int_val_l'], "text"),
					   GetSQLValueString(isset($_POST['num_paginaw_val_l']) ? "true" : "", "defined","1","0"),
					   GetSQLValueString($_POST['observ_num_paginaw_val_l'], "text"),
					   GetSQLValueString(isset($_POST['rev_enbob_val_l']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_rev_enbob_val_l'], "text"),					   
                       GetSQLValueString(isset($_POST['1color_val_l']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_1color_val_l'], "text"),
                       GetSQLValueString(isset($_POST['2color_val_l']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_2color_val_l'], "text"),
                       GetSQLValueString(isset($_POST['3color_val_l']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_3color_val_l'], "text"),
                       GetSQLValueString(isset($_POST['4color_val_l']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_4color_val_l'], "text"),
                       GetSQLValueString(isset($_POST['5color_val_l']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_5color_val_l'], "text"),
                       GetSQLValueString(isset($_POST['6color_val_l']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_6color_val_l'], "text"),
                       GetSQLValueString(isset($_POST['7color_val_l']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_7color_val_l'], "text"),
                       GetSQLValueString(isset($_POST['8color_val_l']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_8color_val_l'], "text"),
					   GetSQLValueString(isset($_POST['marca_foto_val_l']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_marca_foto_val_l'], "text"),
					   GetSQLValueString(isset($_POST['ref_val_l']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_ref_val_l'], "text"),					   
					   GetSQLValueString(isset($_POST['b_preimp_val_l']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_b_preimp_val_l'], "text"),
                       GetSQLValueString($_POST['observacion_val_l'], "text"),
                       GetSQLValueString($_POST['userfile_l'], "text"),
                       GetSQLValueString($_POST['estado_arte_val_l'], "int"),
                       GetSQLValueString($_POST['fecha_aprob_arte_val_l'], "date"),
                       GetSQLValueString($_POST['fecha_edit_val_l'], "date"),
                       GetSQLValueString($_POST['responsable_edit_val_l'], "text"));
					   				   
  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());
  $insertGoTo = "validacion_lamina_vista.php?id_val_l=" . $_POST['id_val_l'] . "";
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

mysql_select_db($database_conexion1, $conexion1);
$query_ultimo = "SELECT * FROM Tbl_validacion_lamina ORDER BY id_val_l DESC";
$ultimo = mysql_query($query_ultimo, $conexion1) or die(mysql_error());
$row_ultimo = mysql_fetch_assoc($ultimo);
$totalRows_ultimo = mysql_num_rows($ultimo);

$colname_referencia = "-1";
if (isset($_GET['id_ref'])) {
  $colname_referencia = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_referencia_revision = sprintf("SELECT * FROM Tbl_referencia WHERE id_ref = %s", $colname_referencia);
$referencia = mysql_query($query_referencia_revision, $conexion1) or die(mysql_error());
$row_referencia = mysql_fetch_assoc($referencia);
$totalRows_referencia= mysql_num_rows($referencia);

$colname_revision = "-1";
if (isset($_GET['id_ref'])) {
  $colname_revision = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_revision = sprintf("SELECT * FROM Tbl_revision_lamina WHERE id_ref_rev_l = %s", $colname_revision);
$revision = mysql_query($query_revision, $conexion1) or die(mysql_error());
$row_revision = mysql_fetch_assoc($revision);
$totalRows_revision = mysql_num_rows($revision);

$colname_verificacion = "-1";
if (isset($_GET['id_ref'])) {
  $colname_verificacion = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_verificacion = sprintf("SELECT * FROM Tbl_verificacion_lamina WHERE id_ref_verif_l = %s ", $colname_verificacion);
$verificacion = mysql_query($query_verificacion, $conexion1) or die(mysql_error());
$row_verificacion = mysql_fetch_assoc($verificacion);
$totalRows_verificacion = mysql_num_rows($verificacion);

$colname_validacion = "-1";
if (isset($_GET['id_ref'])) {
  $colname_validacion = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_validacion = sprintf("SELECT * FROM Tbl_validacion_lamina WHERE id_ref_val_l = %s", $colname_validacion);
$validacion = mysql_query($query_validacion, $conexion1) or die(mysql_error());
$row_validacion = mysql_fetch_assoc($validacion);
$totalRows_validacion = mysql_num_rows($validacion);

$colname_ref_egp = "-1";
if (isset($_GET['id_ref'])) {
  $colname_ref_egp = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ref_egp = sprintf("SELECT * FROM Tbl_referencia, Tbl_egp WHERE Tbl_referencia.id_ref = %s AND Tbl_referencia.n_egp_ref=Tbl_egp.n_egp", $colname_ref_egp);
$ref_egp = mysql_query($query_ref_egp, $conexion1) or die(mysql_error());
$row_ref_egp = mysql_fetch_assoc($ref_egp);
$totalRows_ref_egp = mysql_num_rows($ref_egp);

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
          <td colspan="2" id="subtitulo">ADD - II. VALIDACION LAMINA
            <input name="id_val_l" type="hidden" value="<?php $num=$row_ultimo['id_val_l']+1; echo $num; ?>" />
            <?php echo $num; ?></td>
          <td id="dato2"><a href="referencias.php"><img src="images/a.gif" alt="REF'S ACTIVAS" title="REF'S ACTIVAS" border="0" style="cursor:hand;"/></a><a href="referencias_inactivas.php"><img src="images/i.gif" alt="REF'S INACTIVAS"  title="REF'S INACTIVAS" border="0" style="cursor:hand;"/></a><a href="revision_lamina_vista.php?id_rev_l=<?php echo $row_revision['id_rev_l']; ?>"><img src="images/r.gif" alt="REVISIONES" title="REVISIONES" border="0" style="cursor:hand;" /></a><a href="verificacion_referencia_lamina.php?id_ref=<?php echo $row_referencia['id_ref']; ?>"><img src="images/v.gif" alt="VERIFICACION X REF" title="VERIFICACION X REF" border="0" style="cursor:hand;" /></a><a href="verificacion_lamina.php"><img src="images/identico.gif" alt="LISTADO DE VERIFICACIONES" title="LISTADO DE VERIFICACIONES" border="0" style="cursor:hand;" /></a><?php if($row_validacion['id_val_l']=='') { ?><a href="validacion_lamina_add.php?id_ref=<?php echo $_GET['id_ref']; ?>"><img src="images/v.gif" alt="ADD VALIDACION" title="ADD VALIDACION" border="0" style="cursor:hand;" /></a><?php } else{ ?><a href="validacion_lamina_vista.php?id_val_l=<?php echo $row_validacion['id_val_l']; ?>"><img src="images/v.gif" alt="VALIDACION" title="VALIDACION" border="0" style="cursor:hand;" /></a><?php } ?><?php if($row_ficha_tecnica['n_ft']=='') { ?><a href="ficha_tecnica_add.php?id_ref=<?php echo $_GET['id_ref']; ?>"><img src="images/f.gif" alt="ADD FICHA TECNICA"  title="ADD FICHA TECNICA" border="0" style="cursor:hand;" /></a><?php } else { ?><a href="ficha_tecnica_vista.php?n_ft=<?php echo $row_ficha_tecnica['n_ft']; ?>"><img src="images/f.gif" alt="FICHA TECNICA" title="FICHA TECNICA" border="0" style="cursor:hand;" /></a><?php } ?>
                  <?php if($row_certificacion['idcc']=='') { ?>
        <a href="certificacion_add.php?id_ref=<?php echo $_GET['id_ref']; ?>" target="new"><img src="images/c.gif" alt="ADD CERTIFICACION" title="ADD CERTIFICACION" border="0" style="cursor:hand;"></a><?php } else { ?><a href="certificacion_listado.php?id_ref=<?php echo $_GET['id_ref']; ?>" target="new"><img src="images/c.gif" alt="CERTIFICACIONES" title="CERTIFICACIONES" border="0" style="cursor:hand;">
        </a><?php } ?></td>
        </tr>
        <tr id="tr1">
          <td id="fuente2">FECHA DE REGISTRO </td>
          <td colspan="2" id="fuente2">RESPONSABLE</td>
          </tr>
        <tr>
          <td id="dato2"><input name="fecha_val_l" type="text" value="<?php echo date("Y-m-d"); ?>" size="10" /></td>
          <td colspan="2" id="dato2"><input name="responsable_val_l" type="text" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="30" readonly /></td>
          </tr>
        <tr id="tr1">
          <td id="fuente2">REFERENCIA</td>
          <td id="fuente2">MODIFICACION</td>
          <td id="fuente2">VERSION MODIF.</td>
        </tr>
        <tr>
          <td id="dato1">REF :
            <input name="id_ref_val_l" type="hidden" value="<?php echo $row_referencia['id_ref']; ?>" />
            <a href="referencia_lamina_vista.php?id_ref=<?php echo $row_referencia['id_ref']; ?>&cod_ref= <?php echo $row_referencia['cod_ref']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_top" style="text-decoration:none;" ><strong><?php echo $row_referencia['cod_ref']; ?> - <?php echo $row_referencia['version_ref']; ?></strong></a><?php if ($row_referencia['version_ref'] != $row_validacion['version_ref_val_l']) echo "<span class='rojo_normal'>"." Cambiar a vers: ".$row_referencia['version_ref']."</spam>"?></td>
          <td id="dato2">- - </td>
          <td id="dato2"><strong>
            <input name="version_ref_val_l" type="text" value="<?php echo $row_verificacion['version_ref_verif_l']; ?>" size="2" />
          </strong></td>
        </tr>
        <tr>
          <td id="dato1">REVISION
            <input name="id_rev_val_l" type="hidden" value="<?php echo $row_revision['id_rev_l']; ?>" />
: <a href="revision_lamina_vista.php?id_rev_l=<?php echo $row_revision['id_rev_l']; ?>" target="_top" style="text-decoration:none;" ><strong><?php echo $row_revision['id_rev_l']; ?></strong></a></td>
          <td id="dato2"><p>VERIFICACION : <a href="verificacion_lamina_vista.php?id_verif_l=<?php echo $row_verificacion['id_verif_l']; ?>" target="_top" style="text-decoration:none;" ><?php echo $row_verificacion['id_verif_l']; ?></a> <a href="verificacion_referencia_lamina.php?id_ref=<?php echo $row_referencia['id_ref']; ?>"><img src="images/v.gif" alt="VERIFICACIONES X REF" border="0" style="cursor:hand;" ></a></p></td>
          <td id="dato2">COTIZACION N&ordm; <?php echo $row_referencia['n_cotiz_ref']; ?></td>
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
              <td id="detalle1">Ancho: <?php echo $row_referencia['ancho_ref']; ?></td>
              <td id="detalle1"><input type="checkbox" name="ancho_val_l" value="1" />
                Ancho</td>
              <td id="detalle2"><input type="text" name="observ_ancho_val_l" value="<?php if($row_verificacion['observ_ancho_verif_l']!=''){echo "Verificacion dice: ";echo $row_verificacion['observ_ancho_verif_l'];} ?><?php echo " ".$row_revision['observ_ancho_rev_l']; ?>" size="50" /></td>
            </tr>
            <tr>
              <td id="detalle1">Largo: <?php echo $row_referencia['largo_ref']; ?></td>
              <td id="detalle1"><input type="checkbox" name="largo_val_l" value="1">
                Largo</td>
              <td id="detalle2"><input type="text" name="observ_largo_val_l" value="<?php if($row_verificacion['observ_largo_verif_l']!=''){echo "Verificacion dice: ";echo $row_verificacion['observ_largo_verif_l'];} ?><?php echo " ".$row_revision['observ_largo_rev_l']; ?>" size="50" /></td>
            </tr>
            <tr>
              <td id="detalle1">Calibre: <?php echo $row_referencia['calibre_ref']; ?></td>
              <td id="detalle1"><input type="checkbox" name="calibre_val_l" value="1">
                Calibre</td>
              <td id="detalle2"><input type="text" name="observ_calibre_val_l" value="<?php if($row_verificacion['observ_calibre_verif_l']!=''){echo "Verificacion dice: ";echo $row_verificacion['observ_calibre_verif_l'];} ?><?php echo " ".$row_revision['observ_calibre_rev_l']; ?>" size="50" /></td>
            </tr>            
            <tr>
              <td id="detalle1">Revisi&oacute;n Ortografica</td>
              <td id="detalle1"><input type="checkbox" name="revi_ortog_val_l" value="1">
                Revisi&oacute;n Ortografica</td>
              <td id="detalle2"><input type="text" name="observ_revi_ortog_val_l" value="<?php if($row_verificacion['observ_revi_ortog_verif_l']!=''){echo "Verificacion dice: ";echo $row_verificacion['observ_revi_ortog_verif_l'];} ?><?php echo " ".$row_revision['observ_revi_ortog_rev_l']; ?>" size="50" /></td>
            </tr>
            <tr>
              <td id="detalle1">Revisi&oacute;n Textos</td>
              <td id="detalle1"><input type="checkbox" name="rev_textos_val_l" value="1">
Revisi&oacute;n Textos</td>
              <td id="detalle2"><input type="text" name="observ_rev_textos_val_l" value="<?php if($row_verificacion['observ_rev_textos_verif_l']!=''){echo "Verificacion dice: ";echo $row_verificacion['observ_rev_textos_verif_l'];} ?><?php echo " ".$row_revision['observ_rev_textos_rev_l']; ?>" size="50" /></td>
            </tr>
            <tr>
              <td id="detalle1">Tipo Extrusi&oacute;n: <?php echo $row_ref_egp['tipo_ext_egp']; ?></td>
              <td id="detalle1"><input name="rev_extru_val_l" type="checkbox" id="rev_extru_val_l" value="1">
                Tipo Extrusion </td>
              <td id="detalle2"><input type="text" name="observ_rev_extru_val_l" value="<?php if($row_verificacion['observ_rev_extru_verif_l']!=''){echo "Verificacion dice: ";echo $row_verificacion['observ_rev_extru_verif_l'];} ?><?php echo " ".$row_revision['observ_rev_extru_rev_l']; ?>" size="50" /></td>
            </tr>
            <tr>
              <td id="detalle1">Pigmento Exterior: <?php echo $row_ref_egp['pigm_ext_egp']; ?></td>
              <td id="detalle1"><input type="checkbox" name="color_ext_val_l" value="1">Color Extrusi&oacute;n Exterior </td>
              <td id="detalle2"><input type="text" name="observ_color_ext_val_l" value="<?php if($row_verificacion['observ_color_ext_verif_l']!=''){echo "Verificacion dice: ";echo $row_verificacion['observ_color_ext_verif_l'];} ?><?php echo " ".$row_revision['observ_color_ext_rev_l']; ?>" size="50" /></td>
            </tr>
            <tr>
              <td id="detalle1">Pigmento Interior: <?php echo $row_ref_egp['pigm_int_epg']; ?></td>
              <td id="detalle1"><input type="checkbox" name="color_int_val_l" value="1">Color Extrusi&oacute;n Interior</td>
              <td id="detalle2"><input type="text" name="observ_int_val_l" value="<?php if($row_verificacion['observ_int_verif_l']!=''){echo "Verificacion dice: ";echo $row_verificacion['observ_int_verif_l'];} ?><?php echo " ".$row_revision['observ_int_rev_l']; ?>" size="50" /></td>
            </tr>
            
            <tr>
              <td id="detalle1">Embobinado:
                <?php switch($row_referencia['N_embobinado_l']) {
	  case 0: echo "VACIO"; break;
	  case 1: ?>
                <img src="images/embobinado1.gif">
                <?php break;
	  case 2: ?>
                <img src="images/embobinado2.gif">
                <?php break;
	  case 3: ?>
                <img src="images/embobinado3.gif">
                <?php break;
	  case 4: ?>
                <img src="images/embobinado4.gif">
                <?php break;
	  case 5: ?>
                <img src="images/embobinado5.gif">
                <?php break;
	  case 6: ?>
                <img src="images/embobinado6.gif">
                <?php break;
	  case 7: ?>
                <img src="images/embobinado7.gif">
                <?php break;
	  case 8: ?>
                <img src="images/embobinado8.gif">
                <?php break;
	  case 9: ?>
                <img src="images/embobinado9.gif">
                <?php break;
	  case 10: ?>
                <img src="images/embobinado10.gif">
                <?php break;
	  case 11: ?>
                <img src="images/embobinado11.gif">
                <?php break;
	  case 12: ?>
                <img src="images/embobinado12.gif">
                <?php break;
	  case 13: ?>
                <img src="images/embobinado13.gif">
                <?php break;
	  case 14: ?>
                <img src="images/embobinado14.gif">
                <?php break;
	  case 15: ?>
                <img src="images/embobinado15.gif">
                <?php break;
	  case 16: ?>
                <img src="images/embobinado16.gif">
                <?php break;
	  } ?></td>
              <td id="detalle1"><input type="checkbox" name="rev_enbob_val_l" value="1">
                Embobinado</td>
              <td id="detalle2"><input type="text" name="observ_rev_enbob_val_l" value="<?php if($row_verificacion['observ_rev_enbob_verif_l']!=''){echo "Verificacion dice: ";echo $row_verificacion['observ_rev_enbob_verif_l'];} ?>" size="50" /></td>
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
              <td id="detalle2"><input name="1color_val_l" type="checkbox" id="1color_val_l" value="1" /></td>
              <td id="detalle2"><input type="text" name="observ_1color_val_l" value="<?php if($row_verificacion['observ_1color_verif_l']!=''){;echo $row_verificacion['observ_1color_verif_l'];} ?>" size="60" /></td>
            </tr>
            <tr>
              <td id="detalle1"><strong>2 </strong>: <?php echo $row_ref_egp['color2_egp']; ?></td>
              <td id="detalle1">- <?php echo $row_ref_egp['pantone2_egp']; ?></td>
              <td id="detalle2"><input name="2color_val_l" type="checkbox" id="2color_val_l" value="1" /></td>
              <td id="detalle2"><input type="text" name="observ_2color_val_l" value="<?php if($row_verificacion['observ_2color_verif_l']!=''){echo $row_verificacion['observ_2color_verif_l'];}?>" size="60" /></td>
            </tr>
            <tr>
              <td id="detalle1"><strong>3 </strong>: <?php echo $row_ref_egp['color3_egp']; ?></td>
              <td id="detalle1">- <?php echo $row_ref_egp['pantone3_egp']; ?></td>
              <td id="detalle2"><input name="3color_val_l" type="checkbox" id="3color_val_l" value="1"></td>
              <td id="detalle2"><input type="text" name="observ_3color_val_l" value="<?php if($row_verificacion['observ_3color_verif_l']!=''){echo $row_verificacion['observ_3color_verif_l'];}?>" size="60" /></td>
            </tr>
            <tr>
              <td id="detalle1"><strong>4 </strong>: <?php echo $row_ref_egp['color4_egp']; ?></td>
              <td id="detalle1">- <?php echo $row_ref_egp['pantone4_egp']; ?></td>
              <td id="detalle2"><input name="4color_val_l" type="checkbox" id="4color_val_l" value="1"></td>
              <td id="detalle2"><input type="text" name="observ_4color_val_l" value="<?php if($row_verificacion['observ_4color_verif_l']!=''){echo $row_verificacion['observ_4color_verif_l'];} ?>" size="60" /></td>
            </tr>
            <tr>
              <td id="detalle1"><strong>5 </strong>: <?php echo $row_ref_egp['color5_egp']; ?></td>
              <td id="detalle1">- <?php echo $row_ref_egp['pantone5_egp']; ?></td>
              <td id="detalle2"><input name="5color_val_l" type="checkbox" id="5color_val_l" value="1"></td>
              <td id="detalle2"><input type="text" name="observ_5color_val_l" value="<?php if($row_verificacion['observ_5color_verif_l']!=''){echo $row_verificacion['observ_5color_verif_l'];}?>" size="60" /></td>
            </tr>
            <tr>
              <td id="detalle1"><strong>6 </strong>: <?php echo $row_ref_egp['color6_egp']; ?></td>
              <td id="detalle1">- <?php echo $row_ref_egp['pantone6_egp']; ?></td>
              <td id="detalle2"><input name="6color_val_l" type="checkbox" id="6color_val_l" value="1"></td>
              <td id="detalle2"><input type="text" name="observ_6color_val_l" value="<?php if($row_verificacion['observ_6color_verif_l']!=''){echo $row_verificacion['observ_6color_verif_l'];}?>" size="60" /></td>
            </tr>
            <tr>
              <td id="detalle1"><strong>7 </strong>: <?php echo $row_ref_egp['color7_egp']; ?></td>
              <td id="detalle1">- <?php echo $row_ref_egp['pantone7_egp']; ?></td>
              <td id="detalle2"><input name="7color_val_l" type="checkbox" id="7color_val_l" value="1"></td>
              <td id="detalle2"><input type="text" name="observ_7color_val_l" value="<?php if($row_verificacion['observ_7color_verif_l']!=''){echo $row_verificacion['observ_7color_verif_l'];}?>" size="60" /></td>
            </tr>
            <tr>
              <td id="detalle1"><strong>8 </strong>: <?php echo $row_ref_egp['color8_egp']; ?></td>
              <td id="detalle1">- <?php echo $row_ref_egp['pantone8_egp']; ?></td>
              <td id="detalle2"><input name="8color_val_l" type="checkbox" id="8color_val_l" value="1"></td>
              <td id="detalle2"><input type="text" name="observ_8color_val_l" value="<?php if ($row_verificacion['observ_8color_verif_l']!=''); {echo $row_verificacion['observ_8color_verif_l'];}?>" size="60" /></td>
            </tr>
                                    
            <tr>
              <td colspan="2" id="detalle1">MARCA DE FOTOCELDA</td>
              <td id="detalle2"><input type="checkbox" name="marca_foto_val_l" value="1"></td>
              <td id="detalle2"><input type="text" name="observ_marca_foto_val_l" value="<?php if ($row_verificacion['observ_marca_foto_verif_l']!=''); {echo $row_verificacion['observ_marca_foto_verif_l'];}?>" size="60" /></td>
            </tr>
            <tr>
              <td colspan="2" id="detalle1">REFERENCIA</td>
              <td id="detalle2"><input type="checkbox" name="ref_val_l" value="1"></td>
              <td id="detalle2"><input type="text" name="observ_ref_val_l" value="<?php if ($row_verificacion['observ_ref_verif_l']!=''); {echo $row_verificacion['observ_ref_verif_l'];}?>" size="60" /></td>
            </tr>
<tr>
              <td colspan="2"id="detalle1">PAGINA WEB</td>
              <td id="detalle2"><input type="checkbox" name="num_paginaw_val_l" value="1"></td>
              <td id="detalle2"><input type="text" name="observ_num_paginaw_val_l" value="<?php if($row_verificacion['observ_num_paginaw_verif_l']!=''){echo "Verificacion dice: ";echo $row_verificacion['observ_num_paginaw_verif_l'];} ?><?php echo " ".$row_revision['observ_num_paginaw_rev_l']; ?>" size="60" /></td>
            </tr>            
          </table></td>
        </tr>
        <tr id="tr1">
          <td colspan="4" id="titulo4">VERIFICACION DE CODIGO DE BARRAS (Cumple Si / No)</td>
        </tr>
        <tr>
          <td colspan="4" align="center"><table id="tabla1">
            <tr id="tr1">
              <td id="fuente2">PREIMPRESO</td>
              <td id="fuente2">NUMERO</td>
              <td id="fuente2">CUMPLE</td>
              <td id="fuente2">OBSERVACION</td>
            </tr>
            <tr>
              <td id="detalle2"><?php if ($row_referencia['b_preimp_l']=='0'){echo "NO";}else {echo"SI";} ?></td>
              <td id="detalle2"><?php echo $row_revision['int_numero_l']; ?></td>
              <td id="detalle2"><input type="checkbox" name="b_preimp_val_l" value="1" /></td>
              <td id="detalle2"><input type="text" name="observ_b_preimp_val_l" value="<?php if($row_verificacion['observ_b_preimp_verif_l']!=''){echo $row_verificacion['observ_b_preimp_verif_l'];} ?>" size="60" /></td>
            </tr>
          </table></td>
        </tr>
        <tr id="tr1">
          <td colspan="4" id="titulo4">OBSERVACIONES GENERALES </td>
        </tr>
        <tr>
          <td colspan="4" id="dato2"><textarea name="observacion_val_l" cols="80" rows="2"><?php if($row_verificacion['observacion_verif_l']!=''){echo $row_verificacion['observacion_verif_l'];} ?><?php echo " Obs Ref: ".$row_ref_egp['observacion5_egp']; ?><?php echo "Obs Rev: ".$row_revision['str_obs_general_l']; ?></textarea>
            <input name="userfile_l" type="hidden" value="<?php echo $row_verificacion['userfile_l']; ?>" />
            <input name="estado_arte_val_l" type="hidden" id="estado_arte_val_l" value="0" />
            <input name="fecha_aprob_arte_val_l" type="hidden" value="0000-00-00" /> 
            <input name="fecha_edit_val_l" type="hidden" value="" />
            <input name="responsable_edit_val_l" type="hidden" value="" /></td>
        </tr>


        <tr>
          <td colspan="4" id="dato2">&nbsp;</td>
        </tr>
        <tr id="tr1">
          <td colspan="4" id="dato2"><input type="submit" value="ADD VALIDACION"></td>
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

mysql_free_result($referencia);

mysql_free_result($ultimo);

mysql_free_result($ref_egp);

mysql_free_result($verificacion);

//mysql_free_result($validacion);

//mysql_free_result($ficha_tecnica);
?>
