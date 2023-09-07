 <?php require_once('Connections/conexion1.php'); ?>
<?php
require_once("db/db.php");
require_once 'Models/Referencias.php';
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


$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

//EL ID_REF ES ENVIADO DESDE VISTA DE REFERENCIA
$colname_referencia_editar = "-1";
if (isset($_GET['id'])) {
  $colname_referencia_editar = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_referencia_editar = sprintf("SELECT * FROM tbl_referencia_historico,tbl_egp WHERE tbl_referencia_historico.id= '%s' AND tbl_referencia_historico.cod_ref=tbl_egp.n_egp", $colname_referencia_editar);
$referencia_editar = mysql_query($query_referencia_editar, $conexion1) or die(mysql_error());
$row_referencia_editar = mysql_fetch_assoc($referencia_editar);
$totalRows_referencia_editar = mysql_num_rows($referencia_editar);
//ARTE
$colname_ref_verif = "-1";
if (isset($_GET['id'])) {
  $colname_ref_verif = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ref_verif = sprintf("SELECT userfile,estado_arte_verif FROM verificacion WHERE id_ref_verif = '%s' AND estado_arte_verif = '2'", $colname_ref_verif);
$ref_verif = mysql_query($query_ref_verif, $conexion1) or die(mysql_error());
$row_ref_verif = mysql_fetch_assoc($ref_verif);
$totalRows_ref_verif = mysql_num_rows($ref_verif);
//REF CLIENTE
$colname_refcliente = "-1";
if (isset($_GET['id'])) {
  $colname_refcliente = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_refcliente = sprintf("SELECT tbl_referencia_historico.cod_ref,Tbl_refcliente.id_refcliente,Tbl_refcliente.int_ref_ac_rc,Tbl_refcliente.str_ref_cl_rc,Tbl_refcliente.str_descripcion_rc FROM tbl_referencia_historico,Tbl_refcliente WHERE tbl_referencia_historico.id = '%s' and tbl_referencia_historico.cod_ref=Tbl_refcliente.int_ref_ac_rc", $colname_refcliente);
$refcliente = mysql_query($query_refcliente, $conexion1) or die(mysql_error());
$row_refcliente = mysql_fetch_assoc($refcliente);
$totalRows_refcliente = mysql_num_rows($refcliente);

//INSUMOS CAJAS MEDIDA
mysql_select_db($database_conexion1, $conexion1);
$query_insumo = "SELECT id_insumo,codigo_insumo,descripcion_insumo FROM insumo WHERE clase_insumo IN ('2') ORDER BY  descripcion_insumo ASC";
$insumo = mysql_query($query_insumo, $conexion1) or die(mysql_error());
$row_insumo = mysql_fetch_assoc($insumo);
$totalRows_insumo = mysql_num_rows($insumo);
//INSUMOS TIPO LAMINAS
mysql_select_db($database_conexion1, $conexion1);
$query_insumo2 = "SELECT id_insumo,codigo_insumo,descripcion_insumo FROM insumo WHERE clase_insumo IN ('31') ORDER BY descripcion_insumo ASC";
$insumo2 = mysql_query($query_insumo2, $conexion1) or die(mysql_error());
$row_insumo2 = mysql_fetch_assoc($insumo2);
$totalRows_insumo2 = mysql_num_rows($insumo2);
//INSUMOS TIPO ADHESIVO
mysql_select_db($database_conexion1, $conexion1);
$query_insumo3 = "SELECT id_insumo,descripcion_insumo FROM insumo WHERE clase_insumo IN ('30','33','32') AND estado_insumo='0' ORDER BY descripcion_insumo ASC";
$insumo3 = mysql_query($query_insumo3, $conexion1) or die(mysql_error());
$row_insumo3 = mysql_fetch_assoc($insumo3);
$totalRows_insumo3 = mysql_num_rows($insumo3);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
<title>SISADGE AC &amp; CIA</title>

<link rel="stylesheet" type="text/css" href="css/general.css"/>


<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script src="js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
</head>
<body onload="javascript: mostrarBols(this);traslape();calcular_pesom()">
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
    <!--SELLADO-->
    <?php $idref_e=$row_referencia_editar['id_ref'];
    $sqlpm="SELECT * FROM Tbl_produccion_mezclas WHERE id_ref_pm='$idref_e' and id_proceso='1'";
    $resultpm= mysql_query($sqlpm);
    $row_pm = mysql_fetch_assoc($resultpm);
    $numpm= mysql_num_rows($resultpm);
    if($numpm >='1')
    { ?>     
  <li><a href="produccion_caract_extrusion_mezcla_vista.php?id_c=<?php echo $row_pm['id_c_cv']; ?>&id_pm=<?php echo $row_pm['id_pm']; ?>">EXTRUSION</a></li><?php } else{ ?>
    <li><a href="produccion_mezclas_add.php?id_ref=<?php echo $row_referencia_editar['id_ref']; ?>&cod_ref=<?php echo $row_referencia_editar['cod_ref']; ?>">EXTRUSION</a></li>
    <?php } ?>
    <!--//IMPRESION-->
    <?php $idref_i=$row_referencia_editar['id_ref'];
    $sqlci="SELECT DISTINCT id_ref_pmi,id_proceso FROM Tbl_produccion_mezclas_impresion WHERE id_ref_pmi='$idref_i' and id_proceso='2'";
    $resultci= mysql_query($sqlci);
    $row_ci = mysql_fetch_assoc($resultci);
    $numci= mysql_num_rows($resultci);
    if($numci >='1')
    { ?> 
      <li><a href="produccion_caract_impresion_vista.php?id_ref=<?php echo $row_referencia_editar['id_ref']; ?>">IMPRESION</a></li><?php } else{ ?>
      <li><a href="produccion_caract_impresion_add.php?id_ref=<?php echo $row_referencia_editar['id_ref']; ?>&cod_ref=<?php echo $row_referencia_editar['cod_ref']; ?>">IMPRESION</a></li>
    <?php } ?>      
    <li><a href="produccion_refilado_add.php">REFILADO</a></li>  
    <li><a href="produccion_sellado_add.php">SELLADO</a></li>   
  </ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center">
  <form action="<?php echo $editFormAction; ?>" method="post" onsubmit="return validacion_select_bolsillo();" enctype="multipart/form-data" name="form1" id="form1">
    <table id="tabla2">
      <tr id="tr1">
        <td colspan="8" id="titulo2">REFERENCIA ( BOLSA PLASTICA ) </td>
        </tr>
      <tr>
        <td rowspan="7" id="dato2"><img src="images/logoacyc.jpg"/></td>
        <td colspan="7" id="dato3"><a href="referencia_bolsa_vista.php?id_ref=<?php echo $row_referencia_editar['id_ref']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>"><img src="images/hoja.gif" alt="VISTA IMPRESION" title="VISTA IMPRESION" border="0" /></a><a href="referencia_cliente.php?id_ref=<?php echo $row_referencia_editar['id_ref']; ?>"><img src="images/cliente.gif" alt="CLIENTES" title="CLIENTES" border="0"></a><a href="referencia_copia.php"><img src="images/opciones.gif" style="cursor:hand;" alt="LISTADO COTIZACIONES"title="LISTADO COTIZACIONES" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" title="MENU PRINCIPAL" border="0"/></a><a href="referencias.php"><img src="images/a.gif" alt="REF'S ACTIVAS" title="REF'S ACTIVAS" border="0" style="cursor:hand;"/></a><a href="referencias_inactivas.php"><img src="images/i.gif" alt="REF'S INACTIVAS" title="REF'S INACTIVAS" border="0" style="cursor:hand;"/></a>
    <?php $ref=$row_referencia_eitar['id_ref'];
    $sqlrevision="SELECT * FROM revision WHERE id_ref_rev='$ref'";
    $resultrevision= mysql_query($sqlrevision);
    $row_revision = mysql_fetch_assoc($resultrevision);
    $numrev= mysql_num_rows($resultrevision);
    if($numrev >='1')
    { ?><a href="revision_vista.php?id_rev=<?php echo $row_revision['id_rev']; ?>" target="_top" ><img src="images/r.gif" alt="REVISION" border="0" title="REVISION" style="cursor:hand;"></a><?php } else { ?><a href="revision_add.php?id_ref=<?php echo $ref; ?>" target="_top"><img src="images/r.gif" alt="ADD REVISION" title="ADD REVISION" border="0" style="cursor:hand;" /></a><?php } ?><a href="verificacion_referencia.php?id_ref=<?php echo $row_referencia_editar['id_ref']; ?>"><img src="images/v.gif" alt="VERIFICACION" title="VERIFICACION" border="0" style="cursor:hand;" /></a><?php $ref=$row_referencia_editar['id_ref'];
    $sqlval="SELECT * FROM validacion WHERE id_ref_val='$ref'";
    $resultval= mysql_query($sqlval);
    $row_val = mysql_fetch_assoc($resultval);
    $numval= mysql_num_rows($resultval);
    if($numval >='1')
    { ?><a href="validacion_vista.php?id_val=<?php echo $row_val['id_val']; ?>"><img src="images/v.gif" alt="VALIDACION" title="VALIDACION" border="0" style="cursor:hand;" /></a><?php } else { ?><a href="validacion_add.php?id_ref=<?php echo $ref; ?>" target="_top"><img src="images/v.gif" alt="ADD VALIDACION" title="ADD VALIDACION" border="0" style="cursor:hand;" /></a><?php } $ref=$row_referencia_editar['id_ref'];
    $sqlft="SELECT * FROM ficha_tecnica WHERE id_ref_ft='$ref'";
    $resultft= mysql_query($sqlft);
    $row_ft = mysql_fetch_assoc($resultft);
    $numft= mysql_num_rows($resultft);
    if($numft >='1')
    { ?><a href="ficha_tecnica_vista.php?n_ft=<?php echo $row_ft['n_ft']; ?>"><img src="images/f.gif" alt="FICHA TECNICA" title="FICHA TECNICA" border="0" style="cursor:hand;" /></a><?php } else { ?><a href="ficha_tecnica_add.php?id_ref=<?php echo $ref; ?>" target="_top"><img src="images/f.gif" alt="ADD FICHA TECNICA" title="ADD FICHA TECNICA" border="0" style="cursor:hand;" /></a><?php } ?>
      <a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
        </tr>
      <tr id="tr1">
        <td nowrap="nowrap" id="dato2">Fecha 
          <input name="fecha_registro1_ref" type="text" value="<?php echo $row_referencia_editar['fecha_registro1_ref']; ?>" size="10" readonly="readonly" /></td>
        <td colspan="6" id="dato3">
          <input type="hidden" name="codigo_usuario" value="<?php echo $row_usuario['codigo_usuario'] ?>" />
          Ingresado por
<input name="registro1_ref" type="text" value="<?php echo $row_referencia_editar['registro1_ref']; ?>" size="27" readonly="readonly" /></td>
        </tr>
      <tr id="tr3">
        <td nowrap="nowrap" id="fuente2"><strong>REFERENCIA - VERSION</strong></td>
        <td colspan="3" nowrap="nowrap" id="fuente2">Estado</td>
        <td colspan="3" id="fuente2">Arte</td>
      </tr>
      <tr id="tr3">
        <td nowrap="nowrap" id="fuente2"><input name="cod_ref" type="text" value="<?php echo $row_referencia_editar['cod_ref']; ?>" size="5" readonly="readonly"/> 
          - 
            <input name="version_ref" type="text" value="<?php echo $row_referencia_editar['version_ref']; ?>" size="2" /> 
            </td>
        <td colspan="3" id="fuente2"><select name="estado_ref" id="estado_ref">
          <option value="0" <?php if (!(strcmp(0, $row_referencia_editar['estado_ref']))) {echo "selected=\"selected\"";} ?>>Inactiva</option>
          <option value="1" <?php if (!(strcmp(1, $row_referencia_editar['estado_ref']))) {echo "selected=\"selected\"";} ?>>Activa</option>
        </select></td>
        <td colspan="3" id="dato2"><a href="javascript:verFoto('archivo/<?php echo $row_ref_verif['userfile'];?>','610','490')"> <?php echo $row_ref_verif['userfile']; ?> </a> </td>
      </tr>
      <tr>
        <td colspan="7" nowrap="nowrap" id="fuente1"><?php  if ($row_refcliente['id_refcliente']!="") {?>
        <a href="javascript:verFoto('ref_ac_ref_cliente_edit.php?id_refcliente=<?php echo $row_refcliente['id_refcliente'];?>','840','370')"><?php echo "Ver nombre de la Ref Aquí"; ?></a><?php }else{?>
            <a href="javascript:verFoto('ref_ac_ref_cliente_add.php','840','390')"><?php echo "Agregue Nombre a la Ref Aquí"; ?></a><?php }?></td>
        </tr>
      <tr id="tr1">
        <td nowrap="nowrap" id="fuente2">Cotizaci&oacute;n N&ordm;</td>
        <td colspan="3" nowrap="nowrap" id="fuente2">Referencia Generica</td>
        <td colspan="3" id="fuente2">Fecha Arte</td>
      </tr>
      <tr>
        <td id="dato2"><input name="n_cotiz_ref" type="text" value="<?php echo $row_referencia_editar['n_cotiz_ref']; ?>" size="5" readonly="readonly" /></td>
        <td colspan="3" id="dato2"><select name="B_generica" id="B_generica"onblur="if(form1.B_generica.value) { generica(); } else{ alert('Debe Seleccionar GENERICA'); }">
          <option value=""<?php if (!(strcmp('', $row_referencia_editar['B_generica']))) {echo "selected=\"selected\"";} ?>></option></option>
          <option value="1" <?php if (!(strcmp(1, $row_referencia_editar['B_generica']))) {echo "selected=\"selected\"";} ?>>SI</option>
          <option value="0" <?php if (!(strcmp(0, $row_referencia_editar['B_generica']))) {echo "selected=\"selected\"";} ?>>NO</option>
        </select></td>
        <td colspan="3" id="dato2"><?php echo $row_referencia_editar['fecha_aprob_arte_verif']; ?></td>
      </tr>
      <tr id="tr1">
        <td colspan="8" id="titulo4">DATOS GENERALES DE LA REFERENCIA </td>
        </tr>
      <tr id="tr1">
        <td id="fuente1">ANCHO (cms)</td>
        <td id="fuente1">LARGO (cms)</td>
        <td colspan="3" id="fuente1">SOLAPA  (cms)</td>
        <td colspan="3" id="fuente1">BOLSILLO PORTAGUIA </td>
      </tr>
      <tr>
        <td id="dato1"><input name="ancho_ref" id="ancho_ref" type="number" style="width:90px" min="0.00" step="0.01" required="required" value="<?php echo $row_referencia_editar['ancho_ref']; ?>"/></td>
        <td id="dato1"><input name="largo_ref" id="largo_ref" type="number" style="width:90px" min="0.00" step="0.01" required="required" value="<?php echo $row_referencia_editar['largo_ref']; ?>"/></td>
        <td colspan="2" id="dato1">
        <p><input type="radio" name="valora" id="ocultar" <?php if (!(strcmp($row_referencia_editar['b_solapa_caract_ref'],0))) {echo "checked=\"checked\"";} ?> value="0" onClick="return validarRadio(),calcular_pesom();"/>N/A<br/>
        <input type="radio" name="valora" id="mostrar" <?php if (!(strcmp($row_referencia_editar['b_solapa_caract_ref'],2))) {echo "checked=\"checked\"";} ?> value="2" onClick="return validarRadio(),calcular_pesom();"/>Sencilla<br/>
        <input type="radio" name="valora" id="mostrar" <?php if (!(strcmp($row_referencia_editar['b_solapa_caract_ref'],1))) {echo "checked=\"checked\"";} ?> value="1" onClick="return validarRadio(),calcular_pesom();"/>Doble<br /></p></td>
        <td id="dato1">Solapa valor
          <input name="solapa_ref" id="solapa_ref" type="number" style="width:50px" min="0.00" step="0.01" required="required" value="<?php echo $row_referencia_editar['solapa_ref']; ?>" onblur="calcular_pesom()"/></td>
        <td colspan="3" id="dato1"><input name="bolsillo_guia_ref" id="bolsillo_guia_ref" type="number" style="width:50px" min="0.00" step="0.01" required="required" value="<?php echo $row_referencia_editar['bolsillo_guia_ref']; ?>" onChange="mostrarBols(this)"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">CALIBRE (mills)</td>
        <td id="fuente1"> FUELLE  (cms)</td>
        <td colspan="2" id="fuente1">PESO MILLAR</td>
        <td id="fuente1">ADHESIVO</td>
        <td colspan="3" id="fuente1">Tipo</td>
      </tr>
      <tr>
        <td id="dato1"><input name="calibre_ref" id="calibre_ref" type="number" style="width:90px" min="0.00" step="0.01" required="required" onChange="calcular_pesom()" value="<?php echo $row_referencia_editar['calibre_ref']; ?>"/></td>
        <td id="dato1"><input name="B_fuelle" id="B_fuelle" type="number" style="width:90px" min="0.00" step="0.01" required="required" value="<?php echo $row_referencia_editar['N_fuelle']?>" /></td>
        <td colspan="2" id="dato1"><input name="peso_millar_ref" type="text" id="peso_millar_ref" onChange="calcular_pesom();" value="<?php echo $row_referencia_editar['peso_millar_ref']; ?>" size="10" readonly="readonly"/></td>
        <td id="dato1"><select name="adhesivo_ref" id="adhesivo" style="width:100px">
          <option value="N.A" <?php if (!(strcmp("N.A", $row_referencia_editar['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="HOT MELT" <?php if (!(strcmp("HOT MELT", $row_referencia_editar['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>HOT MELT</option>
          <option value="CINTA PERMANENTE" <?php if (!(strcmp("CINTA PERMANENTE", $row_referencia_editar['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>CINTA PERMANENTE</option>
          <option value="CINTA RESELLABLE" <?php if (!(strcmp("CINTA RESELLABLE", $row_referencia_editar['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>CINTA RESELLABLE</option>
          <option value="CINTA DE SEGURIDAD" <?php if (!(strcmp("CINTA DE SEGURIDAD", $row_referencia_editar['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>CINTA DE SEGURIDAD</option>
        </select></td>
        <td colspan="3" id="dato1"><select name="tipoCinta_ref" id="tipocinta" style="width:100px">
         <option value="" <?php if (!(strcmp("", $row_referencia_editar['tipoCinta_ref']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <?php
          do {  
           ?>
           <option value="<?php echo $row_insumo3['id_insumo']?>"<?php if (!(strcmp($row_insumo3['id_insumo'], $row_referencia_editar['tipoCinta_ref']))) {echo "selected=\"selected\"";} ?>><?php echo $row_insumo3['descripcion_insumo']?></option>
           <?php
         } while ($row_insumo3 = mysql_fetch_assoc($insumo3));
         $rows = mysql_num_rows($insumo3);
         if($rows > 0) {
           mysql_data_seek($insumo3, 0);
           $row_insumo3 = mysql_fetch_assoc($insumo3);
         }
         ?>
        </select></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">TIPO DE BOLSA</td>
        <td id="fuente1">TIPO DE SELLO</td>
        <td id="fuente1">TROQUEL</td>
        <td id="fuente1">PRECORTE</td>
        <td id="fuente1">pre./cuerpo</td>
        <td id="fuente1">pre.e/solapa</td>
        <td id="fuente1">FONDO</td>
      </tr>
      <tr>
        <td id="fuente1"><select name="tipo_bolsa_ref" id="tipo_bolsa_ref" style="width:100px" onChange="calcular_pesom();"> 
          <option value="SEGURIDAD" <?php if (!(strcmp("SEGURIDAD", $row_referencia_editar['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>SEGURIDAD</option>
          <option value="CURRIER" <?php if (!(strcmp("CURRIER", $row_referencia_editar['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>CURRIER</option>
          <option value="BOLSA PLASTICA" <?php if (!(strcmp("BOLSA PLASTICA", $row_referencia_editar['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>BOLSA PLASTICA</option>
          <option value="BOLSA MONEDA" <?php if (!(strcmp("BOLSA MONEDA", $row_referencia_editar['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>BOLSA MONEDA</option>
          <option value="COMPOSTABLE" <?php if (!(strcmp("COMPOSTABLE", $row_referencia_editar['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>COMPOSTABLE</option>
        </select></td>
        <td id="fuente1"><select name="tipo_sello_egp" id="tipo_sello_egp" style="width:100px">
          <option></option>
          <option value="HILO"<?php if (!(strcmp("HILO", $row_referencia_editar['tipo_sello_egp']))) {echo "selected=\"selected\"";} ?>>HILO</option>
          <option value="PLANO"<?php if (!(strcmp("PLANO", $row_referencia_editar['tipo_sello_egp']))) {echo "selected=\"selected\"";} ?>>PLANO</option>
          <option value="HILO/PLANO"<?php if (!(strcmp("HILO/PLANO", $row_referencia_editar['tipo_sello_egp']))) {echo "selected=\"selected\"";} ?>>HILO/PLANO</option>
        </select></td>
        <td id="fuente1"><select name="B_troquel" id="B_troquel" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_referencia_editar['B_troquel']))) {echo "selected=\"selected\"";} ?>>N.A</option>
          <option value="1"<?php if (!(strcmp("1", $row_referencia_editar['B_troquel']))) {echo "selected=\"selected\"";} ?>>SI</option>
          <option value="0"<?php if (!(strcmp("0",$row_referencia_editar['B_troquel']))) {echo "selected=\"selected\"";} ?>>NO</option>
        </select></td>
        <td id="fuente1"><select name="B_precorte" id="B_precorte" style="width:50px">
          <option value="1"<?php if (!(strcmp("1", $row_referencia_editar['B_precorte']))) {echo "selected=\"selected\"";} ?>>SI</option>
          <option value="0"<?php if (!(strcmp("0",$row_referencia_editar['B_precorte']))) {echo "selected=\"selected\"";} ?>>NO</option>
        </select></td>
        <td id="fuente1"><input name="precorte_cuerpo" id="precorte_cuerpo" type="number" style="width:50px" min="0" max="7" step="1" required="required" value="<?php echo $row_referencia_editar['precorte_cuerpo']; ?>"/></td>
        <td id="fuente1"><input name="precorte_solapa" id="precorte_solapa" type="number" style="width:50px" min="0" max="7" step="1" required="required" value="<?php echo $row_referencia_editar['precorte_solapa']; ?>"/></td>
        <td id="fuente1"><select name="B_fondo" id="B_fondo" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_referencia_editar['B_fondo']))) {echo "selected=\"selected\"";} ?>>N.A</option>
          <option value="1"<?php if (!(strcmp("1", $row_referencia_editar['B_fondo']))) {echo "selected=\"selected\"";} ?>>SI</option>
          <option value="0"<?php if (!(strcmp("0",$row_referencia_editar['B_fondo']))) {echo "selected=\"selected\"";} ?>>NO</option>
        </select></td>
      </tr>
      <tr id="tr1">
        <td rowspan="2" id="fuente1">PRESENTACION</td>
        <td rowspan="2" id="fuente1">TRATAMIENTO</td>
        <td colspan="5" id="fuente2">Bolsillo Portaguia</td>
        </tr>
      <tr>
        <td id="fuente1">(Ubicacion)</td>
        <td id="fuente1">Forma:</td>
        <td id="fuente1">Cant/Traslape</td>
        <td id="fuente1">Tipo /Lamina</td>
        <td id="fuente1">Lamina1</td>
      </tr>
      <tr>
        <td id="fuente1"><select name="Str_presentacion" id="opciones2" style="width:100px">
          <option value="LAMINA" <?php if (!(strcmp('LAMINA', $row_referencia_editar['Str_presentacion']))) {echo "selected=\"selected\"";} ?>>LAMINA</option>
          <option value="TUBULAR" <?php if (!(strcmp('TUBULAR', $row_referencia_editar['Str_presentacion']))) {echo "selected=\"selected\"";} ?>>TUBULAR</option>
       <option value="SEMITUBULAR" <?php if (!(strcmp('SEMITUBULAR', $row_referencia_editar['Str_presentacion']))) {echo "selected=\"selected\"";} ?>>SEMITUBULAR</option>
        </select></td>
        <td id="fuente1"><select name="Str_tratamiento" id="Str_tratamiento" style="width:100px">
          <option value="N.A"<?php if (!(strcmp('N.A', $row_referencia_editar['Str_tratamiento']))) {echo "selected=\"selected\"";} ?>>N.A</option>
          <option value="UNA CARA" <?php if (!(strcmp('UNA CARA', $row_referencia_editar['Str_tratamiento']))) {echo "selected=\"selected\"";} ?>>UNA CARA</option>
          <option value="DOBLE CARA" <?php if (!(strcmp('DOBLE CARA', $row_referencia_editar['Str_tratamiento']))) {echo "selected=\"selected\"";} ?>>DOBLE CARA</option>
        </select></td>
        <td id="fuente1"><select name="str_bols_ub_ref" id="str_bols_ub_ref" style="width:50px">
          <option value="">N.A.</option>
          <option value="ANVERSO"<?php if (!(strcmp('ANVERSO', $row_referencia_editar['str_bols_ub_ref']))) {echo "selected=\"selected\"";} ?>>Anverso</option>
          <option value="REVERSO"<?php if (!(strcmp('REVERSO', $row_referencia_editar['str_bols_ub_ref']))) {echo "selected=\"selected\"";} ?>>Reverso</option>
        </select></td>
        <td id="fuente1"><select name="str_bols_fo_ref" id="str_bols_fo_ref" style="width:50px"  onChange="traslape(this)">
          <option value="">N.A.</option>
          <option value="TRANSLAPE"<?php if (!(strcmp('TRANSLAPE', $row_referencia_editar['str_bols_fo_ref']))) {echo "selected=\"selected\"";} ?>>Translape</option>
          <option value="RESELLABLE"<?php if (!(strcmp('RESELLABLE', $row_referencia_editar['str_bols_fo_ref']))) {echo "selected=\"selected\"";} ?>>Resellable</option>
        </select></td>
        <td id="fuente1"><input name="B_cantforma" id="B_cantforma" disabled type="number" style="width:50px" min="0.00" step="0.01" required="required" value="<?php echo $row_referencia_editar['B_cantforma']; ?>"/>
          <input name="auxil" type="hidden" id="auxil" value="<?php echo $row_referencia_editar['B_cantforma']; ?>" /></td>
        <td id="dato1">
          <select name="tipoLamina_ref" id="tipolam" style="width:100px" onChange="medida_bolsillo(this)"><!--onblur="validacion_todos_select(this)"-->
          <option value="NA">NA</option>
          <option value="0"<?php if (!(strcmp("", $row_referencia_editar['tipoLamina_ref']))) {echo "selected=\"selected\"";} ?>>Tipo Lamina</option>
          <?php
          do {  
            ?>
            <option value="<?php echo $row_insumo2['id_insumo']?>"<?php if (!(strcmp($row_insumo2['id_insumo'], $row_referencia_editar['tipoLamina_ref']))) {echo "selected=\"selected\"";} ?>><?php echo $row_insumo2['descripcion_insumo']?></option>
            <?php
          } while ($row_insumo2 = mysql_fetch_assoc($insumo2));
          $rows = mysql_num_rows($insumo2);
          if($rows > 0) {
            mysql_data_seek($insumo2, 0);
            $row_insumo2 = mysql_fetch_assoc($insumo2);
          }
          ?>
        </select></td>
        <td id="dato1"><input name="bol_lamina_1_ref" id="valorlam" style="width:50px" min="0"step="0.01" type="number"  required="required" onchange="calcular_pesomBols()" value="<?php if($row_referencia_editar['bol_lamina_1_ref']==''){echo '0.00';}else{echo $row_referencia_editar['bol_lamina_1_ref'];}?>" /></td>
        </tr>
      <tr>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">Calibre/Bols</td>
        <td id="fuente1"><input name="calibreBols_ref" id="calibreBols_ref" type="number" style="width:50px" min="0.00" step="0.1" onchange="calcular_pesomBols()" value="<?php echo $row_referencia_editar['calibreBols_ref']; ?>"/></td>
        <td id="fuente1">Peso Millar Bols.</td>
        <td id="fuente1"><input name="peso_millar_bols" readonly="readonly" id="peso_millar_bols" type="number" style="width:50px" min="0.00" step="0.01" required="required" onclick="calcular_pesomBols()" value="<?php echo $row_referencia_editar['peso_millar_bols'] ?>"/></td>
        <td id="fuente1">Lamina 2
          <input name="bol_lamina_2_ref" id="bol_lamina_2_ref" style="width:50px" min="0"step="0.01" type="number" required="required" onchange="calcular_pesomBols()" size="5" value="<?php if($row_referencia_editar['bol_lamina_2_ref']==''){echo '0.00';}else{echo $row_referencia_editar['bol_lamina_2_ref'];} ?>" /></td>
        </tr>
        <tr id="tr1"> 
        <td rowspan="2" id="talla1">MARGENES</td>
        <td id="fuente1">Izquierda mm</td>
        <td id="fuente1"><input name="margen_izq_imp_egp" id="margen_izq_imp_egp" style="width:50px" type="number" min="0" size="5" step="1" value="<?php echo $row_referencia_editar['margen_izq_imp_egp'];?>"/></td>
        <td id="fuente1">Rep. en Ancho</td>
        <td id="fuente1"><input name="margen_anc_imp_egp" id="margen_anc_imp_egp" style="width:50px" type="number" min="0" size="5" step="1" value="<?php echo $row_referencia_editar['margen_anc_imp_egp']?>"/></td>
        <td id="fuente2">de</td>
        <td id="fuente1"><input name="margen_anc_mm_imp_egp" id="margen_anc_mm_imp_egp" style="width:50px" type="number" min="0" size="5" step="1" value="<?php echo $row_referencia_editar['margen_anc_mm_imp_egp']?>"/>mm</td>
      </tr>
      <tr>
        <td id="fuente1">Derecha mm</td>
        <td id="fuente1"><input name="margen_der_imp_egp" id="margen_der_imp_egp" style="width:50px" type="number" min="0" size="5" step="1" value="<?php echo $row_referencia_editar['margen_der_imp_egp']?>"/></td>
        <td id="fuente1">Rep. Perimetro</td>
        <td id="fuente1"><input name="margen_peri_imp_egp" id="margen_peri_imp_egp" style="width:50px" type="number" min="0" size="5" step="1" value="<?php echo $row_referencia_editar['margen_peri_imp_egp']?>"/></td>
        <td id="fuente2">de</td>
        <td id="fuente1"><input name="margen_per_mm_imp_egp" id="margen_per_mm_imp_egp" style="width:50px" type="number" min="0" size="5" step="1" value="<?php echo $row_referencia_editar['margen_per_mm_imp_egp']?>"/>mm</td>
      </tr>
      <tr  id="tr1">
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1"><strong>Z</strong></td>
        <td id="fuente1"><input name="margen_z_imp_egp" id="margen_z_imp_egp" style="width:50px" type="number" min="0" step="0.01" value="<?php echo $row_referencia_editar['margen_z_imp_egp']?>"/></td>
        <td colspan="5" id="fuente1">&nbsp;</td>
        </tr>        
        
      <tr>
        <td height="44" colspan="8" id="dato1">Ultima Actualizaci&oacute;n : 
          <input name="registro2_ref" type="hidden" value="<?php echo $row_usuario['nombre_usuario']; ?>" />
          <?php echo $row_referencia_editar['registro2_ref']; ?>
          <input name="fecha_registro2_ref" type="hidden" value="<?php echo date("Y-m-d"); ?>" />
          <?php echo $row_referencia_editar['fecha_registro2_ref']; ?>
          <input name="tipo_usuario" type="hidden" id="tipo_usuario" value="<?php echo $row_usuario['tipo_usuario']; ?>" /></td>
      </tr>
    </table>
      
        
        <table id="tabla2">
      <tr id="tr1">
        <td colspan="3" id="titulo4">DATOS ESPECIFICOS DE LA REFERENCIA</td>
        </tr>
      <tr id="tr1">
        <td id="fuente1">MATERIAL</td>
        <td id="fuente1">PIGMENTO EXTERIOR</td>
        <td id="fuente1">PIGMENTO INTERIOR </td>
      </tr>
      <tr>
        <td id="dato1">
        <select name="material_ref" id="material_ref">
      <option value="TRANSPARENTE"<?php if (!(strcmp("TRANSPARENTE", $row_referencia_editar['material_ref']))) {echo "selected=\"selected\"";} ?>>TRANSPARENTE</option>
        <option value="PIGMENTADO B/N"<?php if (!(strcmp("PIGMENTADO B/N", $row_referencia_editar['material_ref']))) {echo "selected=\"selected\"";} ?>>PIGMENTADO B/N</option>
        <option value="PIGMENTADO B/B"<?php if (!(strcmp("PIGMENTADO B/B", $row_referencia_editar['material_ref']))) {echo "selected=\"selected\"";} ?>>PIGMENTADO B/B</option>
        
      </select> 
          <!--<select name="tipo_ext_egp" id="tipo_ext_egp">
        <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['tipo_ext_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
        
          <option value="P.E.B.D-PIGMENTADO" <?php if (!(strcmp("P.E.B.D-PIGMENTADO", $row_referencia_editar['tipo_ext_egp']))) {echo "selected=\"selected\"";} ?>>P.E.B.D - PIGMENTADO</option>        
          <option value="P.E.B.D-TRANSPARENTE" <?php if (!(strcmp("P.E.B.D-TRANSPARENTE", $row_referencia_editar['tipo_ext_egp']))) {echo "selected=\"selected\"";} ?>>P.E.B.D - TRANSPARENTE</option>
          <option value="COEXTRUSION" <?php if (!(strcmp("COEXTRUSION", $row_referencia_editar['tipo_ext_egp']))) {echo "selected=\"selected\"";} ?>>COEXTRUSION</option></select>-->
          
          <!--<input type="hidden" name="material_ref" id="material_ref" value="<?php echo $row_referencia_editar['material_ref'] ?>" />--></td>
        <td id="dato1"><input type="text" name="pigm_ext_egp" value="<?php echo $row_referencia_editar['pigm_ext_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="pigm_int_epg" value="<?php echo $row_referencia_editar['pigm_int_epg']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Numero de Colores</td>
        <td id="fuente1"><input <?php if (!(strcmp($row_referencia_editar['num_pos_ref'],1))) {echo "checked=\"checked\"";} ?> name="num_pos_ref" type="checkbox" value="1" />
          Numeracion            </td>
        <td id="fuente1">Mezclas:</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="impresion_ref" size="5" id="impresion_ref" value="<?php echo $row_referencia_editar['impresion_ref'] ?>" />          <?php //echo  "Lleva ".$row_ver_ref['impresion_ref']." Colores"?></td>
        <td id="dato1"><input <?php if (!(strcmp($row_referencia_editar['cod_form_ref'],1))) {echo "checked=\"checked\"";} ?> name="cod_form_ref" type="checkbox" value="1" />
        Codigo de Barras </td>
        <td id="dato1"><em>
       <?php 
    $id_ref=$row_referencia_editar['id_ref'];
    $sqlop="SELECT id_ref_cp FROM Tbl_caract_proceso WHERE id_ref_cp='$id_ref' AND id_proceso='2' ORDER BY id_ref_cp DESC LIMIT 1"; 
    $resultop=mysql_query($sqlop); 
    $numop=mysql_num_rows($resultop);
    if($numop >= '1')
    { ?><a href="produccion_caract_impresion_vista.php?id_ref=<?php echo $row_referencia_editar['id_ref'];?>" title="Actualizar Mezcla" target="_blank">Mezclas-colores</a><?php 
    }else { ?>
      <a href="produccion_caract_impresion_add.php?id_ref=<?php echo $row_referencia_editar['id_ref'];?>&cod_ref=<?php echo $row_referencia_editar['cod_ref'];?>" title="Actualizar Mezcla" target="_blank">Falta-Mezcla</a>
       <?php } ?>
        </em></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Color 1 </td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color1_egp" value="<?php echo $row_referencia_editar['color1_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="pantone1_egp" value="<?php echo $row_referencia_editar['pantone1_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="ubicacion1_egp" value="<?php echo $row_referencia_editar['ubicacion1_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Color 2 </td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color2_egp" value="<?php echo $row_referencia_editar['color2_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="pantone2_egp" value="<?php echo $row_referencia_editar['pantone2_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="ubicacion2_egp" value="<?php echo $row_referencia_editar['ubicacion2_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Color 3 </td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color3_egp" value="<?php echo $row_referencia_editar['color3_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="pantone3_egp" value="<?php echo $row_referencia_editar['pantone3_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="ubicacion3_egp" value="<?php echo $row_referencia_editar['ubicacion3_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Color 4</td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color4_egp" value="<?php echo $row_referencia_editar['color4_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="pantone4_egp" value="<?php echo $row_referencia_editar['pantone4_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="ubicacion4_egp" value="<?php echo $row_referencia_editar['ubicacion4_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Color 5 </td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color5_egp" value="<?php echo $row_referencia_editar['color5_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="pantone5_egp" value="<?php echo $row_referencia_editar['pantone5_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="ubicacion5_egp" value="<?php echo $row_referencia_editar['ubicacion5_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Color 6 </td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color6_egp" value="<?php echo $row_referencia_editar['color6_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="pantone6_egp" value="<?php echo $row_referencia_editar['pantone6_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="ubicacion6_egp" value="<?php echo $row_referencia_editar['ubicacion6_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Color 7</td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color7_egp" value="<?php echo $row_referencia_editar['color7_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="pantone7_egp" value="<?php echo $row_referencia_editar['pantone7_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="ubicacion7_egp" value="<?php echo $row_referencia_editar['ubicacion7_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>  
      <tr id="tr1">
        <td id="fuente1">Color 8</td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color8_egp" value="<?php echo $row_referencia_editar['color8_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="pantone8_egp" value="<?php echo $row_referencia_editar['pantone8_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="ubicacion8_egp" value="<?php echo $row_referencia_editar['ubicacion8_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>           
      <tr id="tr1">
        <td id="detalle2">POSICION</td>
        <td id="detalle2">TIPO DE NUMERACION </td>
        <td id="detalle2">FORMATO &amp; CODIGO DE BARAS </td>
      </tr>
      <tr>
        <td id="detalle1">Solapa TR </td>
        <td id="detalle2"><select name="tipo_solapatr_egp" id="tipo_solapatr_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['tipo_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="Normal" <?php if (!(strcmp("Normal", $row_referencia_editar['tipo_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
  <option value="CCTV" <?php if (!(strcmp("CCTV", $row_referencia_editar['tipo_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>CCTV</option>
        </select></td>
        <td id="detalle2"><select name="cb_solapatr_egp" id="cb_solapatr_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['cb_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="EAN128" <?php if (!(strcmp("EAN128", $row_referencia_editar['cb_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>EAN128</option>
          <option value="EAN13" <?php if (!(strcmp("EAN13", $row_referencia_editar['cb_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>EAN13</option>
          <option value="CODE128" <?php if (!(strcmp("CODE128", $row_referencia_editar['cb_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>CODE128</option>
          <option value="CODE93" <?php if (!(strcmp("CODE93", $row_referencia_editar['cb_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>CODE93</option>
          <option value="CODE39" <?php if (!(strcmp("CODE39", $row_referencia_editar['cb_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>CODE39</option>
        </select>
        </td>
      </tr>
      <tr>
        <td id="detalle1">Cinta</td>
        <td id="detalle2"><select name="tipo_cinta_egp" id="tipo_cinta_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['tipo_cinta_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="Normal" <?php if (!(strcmp("Normal", $row_referencia_editar['tipo_cinta_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
        </select></td>
        <td id="detalle2"><select name="cb_cinta_egp" id="cb_cinta_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['cb_cinta_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="EAN128" <?php if (!(strcmp("EAN128", $row_referencia_editar['cb_cinta_egp']))) {echo "selected=\"selected\"";} ?>>EAN128</option>
          <option value="EAN13" <?php if (!(strcmp("EAN13", $row_referencia_editar['cb_cinta_egp']))) {echo "selected=\"selected\"";} ?>>EAN13</option>
          <option value="CODE128" <?php if (!(strcmp("CODE128", $row_referencia_editar['cb_cinta_egp']))) {echo "selected=\"selected\"";} ?>>CODE128</option>
          <option value="CODE93" <?php if (!(strcmp("CODE93", $row_referencia_editar['cb_cinta_egp']))) {echo "selected=\"selected\"";} ?>>CODE93</option>
          <option value="CODE39" <?php if (!(strcmp("CODE39", $row_referencia_editar['cb_cinta_egp']))) {echo "selected=\"selected\"";} ?>>CODE39</option>          
        </select></td>
      </tr>
      <tr>
        <td id="detalle1">Superior</td>
        <td id="detalle2"><select name="tipo_superior_egp" id="tipo_superior_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['tipo_superior_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="Normal" <?php if (!(strcmp("Normal", $row_referencia_editar['tipo_superior_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
          <option value="CCTV" <?php if (!(strcmp("CCTV", $row_referencia_editar['tipo_superior_egp']))) {echo "selected=\"selected\"";} ?>>CCTV</option>
        </select></td>
        <td id="detalle2"><select name="cb_superior_egp" id="cb_superior_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['cb_superior_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="EAN128" <?php if (!(strcmp("EAN128", $row_referencia_editar['cb_superior_egp']))) {echo "selected=\"selected\"";} ?>>EAN128</option>
          <option value="EAN13" <?php if (!(strcmp("EAN13", $row_referencia_editar['cb_superior_egp']))) {echo "selected=\"selected\"";} ?>>EAN13</option>
          <option value="CODE128" <?php if (!(strcmp("CODE128", $row_referencia_editar['cb_superior_egp']))) {echo "selected=\"selected\"";} ?>>CODE128</option>
          <option value="CODE93" <?php if (!(strcmp("CODE93", $row_referencia_editar['cb_superior_egp']))) {echo "selected=\"selected\"";} ?>>CODE93</option>
          <option value="CODE39" <?php if (!(strcmp("CODE39", $row_referencia_editar['cb_superior_egp']))) {echo "selected=\"selected\"";} ?>>CODE39</option>         
        </select></td>
      </tr>
      <tr>
        <td id="detalle1">Principal</td>
        <td id="detalle2"><select name="tipo_principal_egp" id="tipo_principal_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['tipo_principal_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="Normal" <?php if (!(strcmp("Normal", $row_referencia_editar['tipo_principal_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
          <option value="CCTV" <?php if (!(strcmp("CCTV", $row_referencia_editar['tipo_principal_egp']))) {echo "selected=\"selected\"";} ?>>CCTV</option>
        </select></td>
        <td id="detalle2"><select name="cb_principal_egp" id="cb_principal_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['cb_principal_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="EAN128" <?php if (!(strcmp("EAN128", $row_referencia_editar['cb_principal_egp']))) {echo "selected=\"selected\"";} ?>>EAN128</option>
          <option value="EAN13" <?php if (!(strcmp("EAN13", $row_referencia_editar['cb_principal_egp']))) {echo "selected=\"selected\"";} ?>>EAN13</option>
          <option value="CODE128" <?php if (!(strcmp("CODE128", $row_referencia_editar['cb_principal_egp']))) {echo "selected=\"selected\"";} ?>>CODE128</option>
          <option value="CODE93" <?php if (!(strcmp("CODE93", $row_referencia_editar['cb_principal_egp']))) {echo "selected=\"selected\"";} ?>>CODE93</option>
          <option value="CODE39" <?php if (!(strcmp("CODE39", $row_referencia_editar['cb_principal_egp']))) {echo "selected=\"selected\"";} ?>>CODE39</option>         
        </select></td>
      </tr>
      <tr>
        <td id="detalle1">Inferior</td>
        <td id="detalle2"><select name="tipo_inferior_egp" id="tipo_inferior_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['tipo_inferior_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="Normal" <?php if (!(strcmp("Normal", $row_referencia_editar['tipo_inferior_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
          <option value="CCTV" <?php if (!(strcmp("CCTV", $row_referencia_editar['tipo_inferior_egp']))) {echo "selected=\"selected\"";} ?>>CCTV</option>
        </select></td>
        <td id="detalle2"><select name="cb_inferior_egp" id="cb_inferior_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['cb_inferior_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="EAN128" <?php if (!(strcmp("EAN128", $row_referencia_editar['cb_inferior_egp']))) {echo "selected=\"selected\"";} ?>>EAN128</option>
          <option value="EAN13" <?php if (!(strcmp("EAN13", $row_referencia_editar['cb_inferior_egp']))) {echo "selected=\"selected\"";} ?>>EAN13</option>
          <option value="CODE128" <?php if (!(strcmp("CODE128", $row_referencia_editar['cb_inferior_egp']))) {echo "selected=\"selected\"";} ?>>CODE128</option>
          <option value="CODE93" <?php if (!(strcmp("CODE93", $row_referencia_editar['cb_inferior_egp']))) {echo "selected=\"selected\"";} ?>>CODE93</option>
          <option value="CODE39" <?php if (!(strcmp("CODE39", $row_referencia_editar['cb_inferior_egp']))) {echo "selected=\"selected\"";} ?>>CODE39</option>         
          
        </select></td>
      </tr>
      <tr>
        <td id="detalle1">Liner</td>
        <td id="detalle2"><select name="tipo_liner_egp" id="tipo_liner_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['tipo_liner_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="Normal" <?php if (!(strcmp("Normal", $row_referencia_editar['tipo_liner_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
          <option value="CCTV" <?php if (!(strcmp("CCTV", $row_referencia_editar['tipo_liner_egp']))) {echo "selected=\"selected\"";} ?>>CCTV</option>
        </select></td>
        <td id="detalle2"><select name="cb_liner_egp" id="cb_liner_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['cb_liner_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="EAN128" <?php if (!(strcmp("EAN128", $row_referencia_editar['cb_liner_egp']))) {echo "selected=\"selected\"";} ?>>EAN128</option>
          <option value="EAN13" <?php if (!(strcmp("EAN13", $row_referencia_editar['cb_liner_egp']))) {echo "selected=\"selected\"";} ?>>EAN13</option>
          <option value="CODE128" <?php if (!(strcmp("CODE128", $row_referencia_editar['cb_liner_egp']))) {echo "selected=\"selected\"";} ?>>CODE128</option>
          <option value="CODE93" <?php if (!(strcmp("CODE93", $row_referencia_editar['cb_liner_egp']))) {echo "selected=\"selected\"";} ?>>CODE93</option>
          <option value="CODE39" <?php if (!(strcmp("CODE39", $row_referencia_editar['cb_liner_egp']))) {echo "selected=\"selected\"";} ?>>CODE39</option>         
          
        </select></td>
      </tr>
      <tr>
        <td id="detalle1">Bolsillo</td>
        <td id="detalle2"><select name="tipo_bols_egp" id="tipo_bols_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['tipo_bols_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="Normal" <?php if (!(strcmp("Normal", $row_referencia_editar['tipo_bols_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
          <option value="CCTV" <?php if (!(strcmp("CCTV", $row_referencia_editar['tipo_bols_egp']))) {echo "selected=\"selected\"";} ?>>CCTV</option>
        </select></td>
        <td id="detalle2"><select name="cb_bols_egp" id="cb_bols_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['cb_bols_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="EAN128" <?php if (!(strcmp("EAN128", $row_referencia_editar['cb_bols_egp']))) {echo "selected=\"selected\"";} ?>>EAN128</option>
          <option value="EAN13" <?php if (!(strcmp("EAN13", $row_referencia_editar['cb_bols_egp']))) {echo "selected=\"selected\"";} ?>>EAN13</option>
          <option value="CODE128" <?php if (!(strcmp("CODE128", $row_referencia_editar['cb_bols_egp']))) {echo "selected=\"selected\"";} ?>>CODE128</option>
          <option value="CODE93" <?php if (!(strcmp("CODE93", $row_referencia_editar['cb_bols_egp']))) {echo "selected=\"selected\"";} ?>>CODE93</option>
          <option value="CODE39" <?php if (!(strcmp("CODE39", $row_referencia_editar['cb_bols_egp']))) {echo "selected=\"selected\"";} ?>>CODE39</option>
        </select></td>
      </tr>
      <tr>
        <td id="detalle1">        
        <input type="text" list="misdatos" name="tipo_nom_egp" id="tipo_nom_egp" value="<?php echo $row_referencia_editar['tipo_nom_egp']; ?>" onBlur="primeraletra(this)">
        <datalist id="misdatos">
         <option  label="Solapa TR" value="Solapa TR">
         <option  label="Cinta" value="Cinta">
         <option  label="Superior" value="Superior">
         <option  label="Principal" value="Principal">
         <option  label="Inferior" value="Inferior">
         <option  label="Liner" value="Liner">
         <option  label="Bolsillo" value="Bolsillo">
        </datalist></td>
        <td id="detalle2"><select name="tipo_otro_egp" id="tipo_otro_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['tipo_otro_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="Normal" <?php if (!(strcmp("Normal", $row_referencia_editar['tipo_otro_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
          <option value="CCTV" <?php if (!(strcmp("CCTV", $row_referencia_editar['tipo_otro_egp']))) {echo "selected=\"selected\"";} ?>>CCTV</option>
        </select></td>
        <td id="detalle2"><select name="cb_otro_egp" id="cb_otro_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['cb_otro_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="EAN128" <?php if (!(strcmp("EAN128", $row_referencia_editar['cb_otro_egp']))) {echo "selected=\"selected\"";} ?>>EAN128</option>
          <option value="EAN13" <?php if (!(strcmp("EAN13", $row_referencia_editar['cb_otro_egp']))) {echo "selected=\"selected\"";} ?>>EAN13</option>
          <option value="CODE128" <?php if (!(strcmp("CODE128", $row_referencia_editar['cb_otro_egp']))) {echo "selected=\"selected\"";} ?>>CODE128</option>
          <option value="CODE93" <?php if (!(strcmp("CODE93", $row_referencia_editar['cb_otro_egp']))) {echo "selected=\"selected\"";} ?>>CODE93</option>
          <option value="CODE39" <?php if (!(strcmp("CODE39", $row_referencia_editar['cb_otro_egp']))) {echo "selected=\"selected\"";} ?>>CODE39</option>
        </select></td>
      </tr>      
      <tr>
        <td id="detalle3">Numeracion Comienza en </td>
        <td id="detalle2"><input type="text" name="comienza_egp" required="required" value="<?php echo $row_referencia_editar['comienza_egp']; ?>" size="20" onKeyUp="return ValNumero(this)"/></td>
        <td id="detalle1"><input <?php if (!(strcmp($row_referencia_editar['fecha_cad_egp'],1))) {echo "checked=\"checked\"";} ?> name="fecha_cad_egp" type="checkbox" value="0" />
Incluir Fecha de Caducidad </td>
      </tr>
      <tr id="tr1">
        <td colspan="2" nowrap="nowrap" id="dato1">Cyreles ?: <?php if ($row_referencia_editar['B_cyreles']==1){ echo "SI ";}else {echo "NO";}?>
          Se Facturan Artes y Planchas</td>
        <td nowrap="nowrap" id="dato4">&nbsp;</td>
      </tr>
      <tr>
<td nowrap="nowrap" id="detalle1"><input <?php if (!(strcmp($row_referencia_editar['arte_sum_egp'],1))) {echo "checked=\"checked\"";} ?> name="arte_sum_egp" type="checkbox" value="1" />
          Arte Suministrado por el Cliente</td>
        <td nowrap="nowrap" id="detalle1"><input <?php if (!(strcmp($row_referencia_editar['ent_logo_egp'],1))) {echo "checked=\"checked\"";} ?> name="ent_logo_egp" type="checkbox" value="1" />
          Entrega Logos de la Entidad</td>
        <td nowrap="nowrap" id="detalle1"><input <?php if (!(strcmp($row_referencia_editar['orient_arte_egp'],1))) {echo "checked=\"checked\"";} ?> name="orient_arte_egp" type="checkbox" value="1" />
          Solicita Orientaci&oacute;n en el Arte</td>
      </tr>
      <tr>
        <td colspan="3" nowrap="nowrap" id="detalle4">&nbsp;</td>
        </tr>
      <tr id="tr1">
        <td colspan="2" id="detalle2">Adjuntar Artes, Logos o Archivos suministrado, solo archivos pdf </td>
        <td id="detalle2">Dise&ntilde;ador</td>
      </tr>
      <tr>
        <td colspan="2" rowspan="3" id="detalle2"><table border="0">
            <tr>
              <td id="dato1"><input name="arte1" type="hidden" value="<?php echo $row_referencia_editar['archivo1'];?>" /><a href="javascript:verFoto('egpbolsa/<?php echo $row_referencia_editar['archivo1'];?>','610','490')"><?php if ($row_referencia_editar['archivo1']!="")echo "Arte1";?></a></td><td id="dato2"><input type="file" name="archivo1"size="20" /></td>              
            </tr>
            <tr>
              <td id="dato1"><input name="arte2" type="hidden" value="<?php echo $row_referencia_editar['archivo2'];?>" />
              <a href="javascript:verFoto('egpbolsa/<?php echo $row_referencia_editar['archivo2'];?>','610','490')"><?php if ($row_referencia_editar['archivo2']!="")echo "Arte2";?></a></td>
              <td id="dato2"><input type="file" name="archivo2"size="20"/></td>              
            </tr>
            <tr>
              <td id="dato1"><input name="arte3" type="hidden" value="<?php echo $row_referencia_editar['archivo3'];?>" />
                <a href="javascript:verFoto('egpbolsa/<?php echo $row_referencia_editar['archivo3'];?>','610','490')"><?php if ($row_referencia_editar['archivo3']!="")echo "Arte3";?></a></td>
              <td id="dato2"><input type="file" name="archivo3" size="20"/></td>              
            </tr>
        </table></td>
        <td id="detalle2"><input type="text" name="disenador_egp" value="<?php echo $row_referencia_editar['disenador_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>
      <tr id="tr1">
        <td id="detalle2">Telefono </td>
      </tr>
      <tr>
        <td id="detalle2"><input type="text" name="telef_disenador_egp" value="<?php echo $row_referencia_editar['telef_disenador_egp']; ?>" size="20" onKeyUp="return ValNumero(this)"/></td>
      </tr>
      <tr>
        <td id="dato1">&nbsp;</td>
        <td id="dato1">&nbsp;</td>
        <td id="dato1">&nbsp;</td>
      </tr>       
      <tr id="tr1">
        <td id="dato1">Unidades por Paquete </td>
        <td id="dato1">Unidades por Caja </td>
        <td id="dato1">Medida de la Caja</td>
      </tr>
      <tr>
        <td id="dato1"><input type="number" name="unids_paq_egp" value="<?php echo $row_referencia_editar['unids_paq_egp']; ?>" required="required" size="20" /></td>
        <td id="dato1"><input type="number" name="unids_caja_egp" value="<?php echo $row_referencia_editar['unids_caja_egp']; ?>" required="required" size="20" /></td>
        <td id="dato1"><!--<input type="text" list="misdatos2" name="marca_cajas_egp" id="marca_cajas_egp" value="<?php echo $row_referencia_editar['marca_cajas_egp']; ?>" onBlur="primeraletra(this)">
        <datalist id="misdatos2">
         <option  label="Pendiente" value="Pendiente">
         <option  label="Prenderia" value="Prenderia 24x21x21">
         <option  label="Av. Grande" value="Av Grande 46x30x21">
         <option  label="Av. pequeña" value="Av pequeña 38x25x19">
         <option  label="Standar" value="Standar 54x45x21">
         <option  label="Sobre" value="Sobre 39x28x1">
         <option  label="Bulto" value="Bulto 54x34x24">
        </datalist>-->
      <select name="marca_cajas_egp" id="opciones" style="width:150px"><!--onblur="validacion_todos_select(this)"-->
      <option value="NA"<?php if (!(strcmp(0, $row_referencia_editar['marca_cajas_egp']))) {echo "selected=\"selected\"";} ?>>NA</option>
        <?php
do {  
?>
        <option value="<?php echo $row_insumo['id_insumo']?>"<?php if (!(strcmp($row_insumo['id_insumo'], $row_referencia_editar['marca_cajas_egp']))) {echo "selected=\"selected\"";} ?>><?php echo $row_insumo['descripcion_insumo']?></option>
        <?php
} while ($row_insumo = mysql_fetch_assoc($insumo));
  $rows = mysql_num_rows($insumo);
  if($rows > 0) {
      mysql_data_seek($insumo, 0);
    $row_insumo = mysql_fetch_assoc($insumo);
  }
?>
      </select></td>
      </tr>
      <tr>
        <td id="dato1">&nbsp;</td>
        <td id="dato1">&nbsp;</td>
        <td id="dato1">&nbsp;</td>
      </tr>      
      <tr id="tr1">
        <td colspan="3" id="fuente1">Observaciones</td>
      </tr>
      <tr>
        <td colspan="3" id="dato1"><textarea name="observacion5_egp" cols="75" rows="2" id="observacion5_egp"onKeyUp="conMayusculas(this)"><?php echo $row_referencia_editar['observacion5_egp']; ?></textarea></td>
      <tr>
        <td colspan="3" id="fuente1">&nbsp;</td>
      </tr>
         <tr id="tr1"> 
            <td colspan="3" id="fuente1">
               <br> 
              <a class="botonGMini" target="_blank"  href="view_index.php?c=creferencias&a=Crud&id=<?php echo $row_referencia_editar['id_ref']; ?>&columna=id_ref&tabla=tbl_referencia_historico">VER HISTORICO DE MODIFICACIONES</a>
              <P><br><br><br> </P> 
        </td>
      </tr>
      <tr id="tr1">
        <td colspan="2" id="fuente1">Ultima Modificaci&oacute;n : 
          <input name="responsable_modificacion" type="hidden" value="<?php echo $row_usuario['nombre_usuario']; ?>" />
          <?php echo $row_referencia_editar['responsable_modificacion']; ?>
          <input name="fecha_modificacion" type="hidden" value="<?php echo date("Y-m-d"); ?>" />
          <?php echo $row_referencia_editar['fecha_modificacion']; ?>
          <input name="hora_modificacion" type="hidden" value="<?php echo date("g:i a"); ?>" />
          <?php echo $row_referencia_editar['hora_modificacion']; ?>
          <input name="tipo_usuario" type="hidden" id="tipo_usuario" value="<?php echo $row_usuario['tipo_usuario']; ?>" />
          <input name="codigo_usuario" type="hidden" id="codigo_usuario" value="<?php echo $row_usuario['codigo_usuario']; ?>" /></td>
        <td id="dato2"> </td>
      </tr>

    </table>
        <input type="hidden" name="vendedor" id="vendedor" value="<?php echo $row_referencia_editar['vendedor']?>"/>
        <input type="hidden" name="MM_update" value="form1">
    <!--<input type="hidden" name="Str_nit" value="<?php //echo $row_ver_ref['Str_nit']; ?>">--> 
    <input type="hidden" name="id_ref" value="<?php echo $row_referencia_editar['id_ref']; ?>">     
  </form></td></tr></table>
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

mysql_free_result($ref_verif);

mysql_free_result($refcliente);

mysql_free_result($referencia_editar);

?>
