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
  $updateSQL = sprintf("UPDATE ficha_tecnica SET id_ref_ft=%s, n_egp_ft=%s, id_rev_ft=%s, cod_ft=%s, fecha_ft=%s, adicionado_ft=%s, peso_g_ft=%s, calibre_micras_ft=%s, tolerancia_md_ft=%s, tolerancia_td_ft=%s, tolerancia_te_ft=%s, tolerancia_fs_ft=%s, tolerancia_ancho_ft=%s, tolerancia_calibre_ft=%s, tolerancia_tc_ft=%s, metodo_arte=%s, pinon_ft=%s, cant_rod_ft=%s, cara_ft=%s, adhesivo_ref_ft=%s, paq_caja_ft=%s, peso_bruto_ft=%s, dim_caja_ft=%s, control_ft=%s, lista_emp_ft=%s, inserto_ft=%s, dist_ciud_ft=%s, resistencia_maxima_ft=%s, estado_ft=%s, fecha_modif_ft=%s, addcambio_ft=%s WHERE n_ft=%s",
                       GetSQLValueString($_POST['id_ref_ft'], "int"),
                       GetSQLValueString($_POST['n_egp_ft'], "int"),
                       GetSQLValueString($_POST['id_rev_ft'], "int"),
                       GetSQLValueString($_POST['cod_ft'], "text"),
                       GetSQLValueString($_POST['fecha_ft'], "date"),
                       GetSQLValueString($_POST['adicionado_ft'], "text"),
                       GetSQLValueString($_POST['peso_g_ft'], "double"),
                       GetSQLValueString($_POST['calibre_micras_ft'], "double"),
                       GetSQLValueString($_POST['tolerancia_md_ft'], "text"),
                       GetSQLValueString($_POST['tolerancia_td_ft'], "text"),
                       GetSQLValueString($_POST['tolerancia_te_ft'], "text"),
                       GetSQLValueString($_POST['tolerancia_fs_ft'], "text"),
                       GetSQLValueString($_POST['tolerancia_ancho_ft'], "text"),
                       GetSQLValueString($_POST['tolerancia_calibre_ft'], "text"),
                       GetSQLValueString($_POST['tolerancia_tc_ft'], "text"),
                       GetSQLValueString($_POST['metodo_arte'], "text"),
                       GetSQLValueString($_POST['pinon_ft'], "int"),
                       GetSQLValueString($_POST['cant_rod_ft'], "int"),
                       GetSQLValueString($_POST['cara_ft'], "text"),
                       GetSQLValueString($_POST['adhesivo_ref_ft'], "text"),
                       GetSQLValueString($_POST['paq_caja_ft'], "int"),
                       GetSQLValueString($_POST['peso_bruto_ft'], "double"),
                       GetSQLValueString($_POST['dim_caja_ft'], "text"),
                       GetSQLValueString(isset($_POST['control_ft']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['lista_emp_ft']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['inserto_ft']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['dist_ciud_ft']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['resistencia_maxima_ft'], "double"),
                       GetSQLValueString($_POST['estado_ft'], "text"),
                       GetSQLValueString($_POST['fecha_modif_ft'], "date"),
                       GetSQLValueString($_POST['addcambio_ft'], "text"),
                       GetSQLValueString($_POST['n_ft'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "ficha_tecnica_vista.php?n_ft=" . $_POST['n_ft'] . "";
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

$colname_referencia = "-1";
if (isset($_GET['n_ft'])) {
  $colname_referencia = (get_magic_quotes_gpc()) ? $_GET['n_ft'] : addslashes($_GET['n_ft']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_referencia = sprintf("SELECT * FROM ficha_tecnica, Tbl_referencia WHERE ficha_tecnica.n_ft = '%s' AND ficha_tecnica.id_ref_ft = Tbl_referencia.id_ref", $colname_referencia);
$referencia = mysql_query($query_referencia, $conexion1) or die(mysql_error());
$row_referencia = mysql_fetch_assoc($referencia);
$totalRows_referencia = mysql_num_rows($referencia);

$colname_revision = "-1";
if (isset($_GET['n_ft'])) {
  $colname_revision = (get_magic_quotes_gpc()) ? $_GET['n_ft'] : addslashes($_GET['n_ft']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_revision = sprintf("SELECT * FROM ficha_tecnica, revision WHERE ficha_tecnica.n_ft = '%s' AND ficha_tecnica.id_ref_ft = revision.id_ref_rev", $colname_revision);
$revision = mysql_query($query_revision, $conexion1) or die(mysql_error());
$row_revision = mysql_fetch_assoc($revision);
$totalRows_revision = mysql_num_rows($revision);

$colname_verificacion = "-1";
if (isset($_GET['n_ft'])) {
  $colname_verificacion = (get_magic_quotes_gpc()) ? $_GET['n_ft'] : addslashes($_GET['n_ft']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_verificacion = sprintf("SELECT * FROM ficha_tecnica, verificacion WHERE ficha_tecnica.n_ft = %s AND ficha_tecnica.id_ref_ft = verificacion.id_ref_verif AND verificacion.estado_arte_verif = '2'", $colname_verificacion);
$verificacion = mysql_query($query_verificacion, $conexion1) or die(mysql_error());
$row_verificacion = mysql_fetch_assoc($verificacion);
$totalRows_verificacion = mysql_num_rows($verificacion);

$colname_validacion = "-1";
if (isset($_GET['n_ft'])) {
  $colname_validacion = (get_magic_quotes_gpc()) ? $_GET['n_ft'] : addslashes($_GET['n_ft']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_validacion = sprintf("SELECT * FROM ficha_tecnica, validacion WHERE  ficha_tecnica.n_ft = '%s' AND ficha_tecnica.id_ref_ft = validacion.id_ref_val", $colname_validacion);
$validacion = mysql_query($query_validacion, $conexion1) or die(mysql_error());
$row_validacion = mysql_fetch_assoc($validacion);
$totalRows_validacion = mysql_num_rows($validacion);

$colname_egp = "-1";
if (isset($_GET['n_ft'])) {
  $colname_egp = (get_magic_quotes_gpc()) ? $_GET['n_ft'] : addslashes($_GET['n_ft']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_egp = sprintf("SELECT * FROM ficha_tecnica, Tbl_egp WHERE ficha_tecnica.n_ft = '%s' AND ficha_tecnica.n_egp_ft = Tbl_egp.n_egp", $colname_egp);
$egp = mysql_query($query_egp, $conexion1) or die(mysql_error());
$row_egp = mysql_fetch_assoc($egp);
$totalRows_egp = mysql_num_rows($egp);

mysql_select_db($database_conexion1, $conexion1);
$query_adhesivo = "SELECT * FROM insumo WHERE clase_insumo = '1'";
$adhesivo = mysql_query($query_adhesivo, $conexion1) or die(mysql_error());
$row_adhesivo = mysql_fetch_assoc($adhesivo);
$totalRows_adhesivo = mysql_num_rows($adhesivo);

mysql_select_db($database_conexion1, $conexion1);
$query_empaques = "SELECT * FROM insumo WHERE clase_insumo = '5' AND dimension_insumo <> ' '";
$empaques = mysql_query($query_empaques, $conexion1) or die(mysql_error());
$row_empaques = mysql_fetch_assoc($empaques);
$totalRows_empaques = mysql_num_rows($empaques);

$colname_ficha_tecnica = "-1";
if (isset($_GET['n_ft'])) {
  $colname_ficha_tecnica = (get_magic_quotes_gpc()) ? $_GET['n_ft'] : addslashes($_GET['n_ft']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ficha_tecnica = sprintf("SELECT * FROM ficha_tecnica WHERE n_ft = %s", $colname_ficha_tecnica);
$ficha_tecnica = mysql_query($query_ficha_tecnica, $conexion1) or die(mysql_error());
$row_ficha_tecnica = mysql_fetch_assoc($ficha_tecnica);
$totalRows_ficha_tecnica = mysql_num_rows($ficha_tecnica);
?><html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
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
<table id="tabla1">
  <tr>
<td colspan="2" align="center"><img src="images/cabecera.jpg"></td></tr>
<tr><td id="nombreusuario"><?php echo $row_usuario['nombre_usuario']; ?></td>
  <td id="cabezamenu"><ul id="menuhorizontal">
	<li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
	<li><a href="menu.php">MENU PRINCIPAL</a></li>
	<li><a href="referencia_copia.php">LISTADO REFERENCIA</a></li>	
	</ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1"><form method="post" name="form1" action="<?php echo $editFormAction; ?>" onSubmit="MM_validateForm('cod_ft','','R','n_egp_ft','','R','id_rev_ft','','R','fecha_ft','','R','adicionado_ft','','R','peso_g_ft','','R','calibre_micras_ft','','R','tolerancia_md_ft','','R','tolerancia_td_ft','','R','tolerancia_te_ft','','R','tolerancia_fs_ft','','R','tolerancia_ancho_ft','','R','tolerancia_calibre_ft','','R','tolerancia_tc_ft','','R','metodo_arte','','R','pinon_ft','','R','cant_rod_ft','','R','paq_caja_ft','','R','peso_bruto_ft','','R','resistencia_maxima_ft','','R');return document.MM_returnValue">
        <table id="tabla2">
          <tr id="tr1">
            <td id="codigo">CODIGO: R2-T01</td>
            <td colspan="2" id="titulo2">EDITAR FICHA TECNICA</td>
            <td id="codigo">VERSION: 2 </td>
          </tr>
          <tr>
            <td rowspan="6" id="dato2"><img src="images/logoacyc.jpg" /></td>
            <td colspan="2" id="subtitulo"><input name="id_ref_ft" type="hidden" value="<?php echo $row_ficha_tecnica['id_ref_ft']; ?>">
              <input type="text" name="cod_ft" value="<?php echo $row_ficha_tecnica['cod_ft']; ?>" size="5"></td>
            <td id="dato2"><a href="ficha_tecnica_vista.php?n_ft=<?php echo $row_ficha_tecnica['n_ft']; ?>"><img src="images/hoja.gif" alt="VISTA IMPRESION" title="VISTA IMPRESION" border="0" style="cursor:hand;"></a><a href="javascript:eliminar('n_ft',<?php echo $row_ficha_tecnica['n_ft']; ?>,'ficha_tecnica_edit.php')"><img src="images/por.gif" border="0" style="cursor:hand;" alt="ELIMINAR" title="ELIMINAR" /></a><a href="ficha_tecnica.php"><img src="images/f.gif" alt="FICHAS TECNICAS" title="FICHAS TECNICAS" border="0" style="cursor:hand;"/></a><a href="referencias.php"><img src="images/a.gif" alt="REF'S ACTIVAS" title="REF'S ACTIVAS" border="0" style="cursor:hand;"/></a><a href="referencias_inactivas.php"><img src="images/i.gif" alt="REF'S INACTIVAS" title="REF'S INACTIVAS" border="0" style="cursor:hand;"/></a><img src="images/ciclo1.gif" style="cursor:hand;" alt="RESTAURAR" title="RESTAURAR" onClick="window.history.go()"></td>
          </tr>
          <tr id="tr1">
            <td id="fuente1">REFERENCIA</td>
            <td id="dato1">EGP N&deg; 
              <input name="n_egp_ft" type="text" value="<?php //$n_egp = $row_ficha_tecnica['n_egp_ft']; if($n_egp == '' || $n_egp == '0') {echo $row_referencia['n_egp_ref']; } else{ echo $n_egp; } ?>" size="3"></td>
            <td id="dato1">REVISION N°
              <input name="id_rev_ft" type="text" value="<?php $rev = $row_ficha_tecnica['id_rev_ft']; if($rev == '' || $rev == '0') { echo $row_revision['id_rev']; } else { echo $rev; } ?>" size="3"></td>
          </tr>
          <tr>
            <td id="dato1"><strong><a href="referencia_bolsa_vista.php?id_ref=<?php echo $row_referencia['id_ref']; ?>&cod_ref= <?php echo $row_referencia['cod_ref']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_top" style="text-decoration:none;" ><?php echo $row_referencia['cod_ref']; ?> - <?php echo $row_referencia['version_ref']; ?></a></strong></td>
            <td id="dato1">VERIFICACION N&deg;  <a href="verificacion_vista.php?id_verif=<?php echo $row_verificacion['id_verif']; ?>"><?php echo $row_verificacion['id_verif']; ?></a></td>
            <td id="dato1">VALIDACION N&deg; <a href="validacion_vista.php?id_val=<?php echo $row_validacion['id_val']; ?>"><?php echo $row_validacion['id_val']; ?></a></td>
          </tr>
          <tr id="tr1">
            <td nowrap id="fuente1">FECHA ELABORACION</td>
            <td colspan="2" id="fuente1">ELABORADA POR </td>
            </tr>
          <tr>
            <td id="dato1"><input type="text" name="fecha_ft" value="<?php echo $row_ficha_tecnica['fecha_ft']; ?>" size="10"></td>
            <td colspan="2" id="dato1"><input type="text" name="adicionado_ft" value="<?php echo $row_ficha_tecnica['adicionado_ft']; ?>" size="30"></td>
            </tr>
          <tr>
            <td colspan="3" id="dato1">&nbsp;</td>
            </tr>
          <tr id="tr1">
            <td colspan="4" id="titulo1">CARACTERISTICAS GENERALES DE LA BOLSA TERMINADA</td>
            </tr>
          <tr id="tr1">
            <td id="fuente1">ANCHO</td>
            <td id="fuente1">LARGO</td>
            <td id="fuente1">SOLAPA</td>
            <td id="fuente1">CALIBRE</td>
          </tr>
          <tr>
            <td id="dato1"><?php echo $row_referencia['ancho_ref']; ?></td>
            <td id="dato1"><?php echo $row_referencia['largo_ref']; ?></td>
            <td id="dato1"><?php echo $row_referencia['solapa_ref']; ?></td>
            <td id="dato1"><?php echo $row_referencia['calibre_ref']; ?></td>
          </tr>
          <tr id="tr1">
            <td id="fuente1">BOLSILLO PORTAGUIA </td>
            <td id="fuente1">PESO MILLAR </td>
            <td id="fuente1">PESO mt. LINEAL (g) </td>
            <td id="fuente1">CALIBRE (micras) </td>
          </tr>
          <tr>
            <td id="dato1"><?php echo $row_referencia['bolsillo_guia_ref']; ?></td>
            <td id="dato1"><?php echo $row_referencia['peso_millar_ref']; ?></td>
            <td><?php if($_GET['n_ft'] != ""){ $peso_g1=(($row_referencia['peso_millar_ref'])*100)/($row_referencia['ancho_ref']); $peso_g2=round($peso_g1*100)/100; }?><input type="text" name="peso_g_ft" value="<?php echo $peso_g2; ?>" size="10"></td>
            <td><?php if( $_GET['n_ft'] != "" ) { $micras1=($row_referencia['calibre_ref'])*25.4; $micras2=round($micras1*100)/100; }?>
              <input type="text" name="calibre_micras_ft" value="<?php echo $micras2; ?>" size="10"></td>
          </tr>
          <tr>
            <td colspan="4" id="dato1"><strong>IMPORTANTE:</strong> La tolerancia en las medidas pueden variar en 1 cm en altura, 5 mm en ancho y un 10% en calibre. La altura util de la bolsa no esta determinada en la altura total, para averiguar este dato debe de restarse la solapa. </td>
            </tr>
          <tr id="tr1">
            <td colspan="4" id="titulo1">EXTRUSION</td>
            </tr>
          <tr>
            <td colspan="4" id="fuente2"><strong>RESISTENCIA (VALORES CRITICOS)</strong></td>
            </tr>
          <tr id="tr1">
            <td id="detalle2">ANALISIS</td>
            <td id="detalle2">METODO</td>
            <td id="detalle2">VALOR MINIMO </td>
            <td id="detalle2">TOLERANCIA</td>
          </tr>
          <tr>
            <td id="detalle1">Resistencia al Razgado MD </td>
            <td id="detalle1">ASTDM-D1922</td>
            <td id="detalle1">&gt; 3 gr / mic </td>
            <td id="detalle2"><input type="text" name="tolerancia_md_ft" value="<?php $md=$row_ficha_tecnica['tolerancia_md_ft']; if($md == '') { echo "228,6"; } else { echo $md; }?>" size="10"></td>
          </tr>
          <tr>
            <td id="detalle1">Resistencia al Razgado TD </td>
            <td id="detalle1">ASTDM-D1922</td>
            <td id="detalle1">&gt; 6 gr / mic </td>
            <td id="detalle2"><input type="text" name="tolerancia_td_ft" value="<?php $td=$row_ficha_tecnica['tolerancia_td_ft']; if($td == '') { echo "457,2"; } else { echo $td; } ?>" size="10"></td>
          </tr>
          <tr>
            <td id="detalle1">Deslizamiento Estatico </td>
            <td id="detalle1">Min 18 </td>
            <td id="detalle1">18 grados </td>
            <td id="detalle2"><input type="text" name="tolerancia_te_ft" value="<?php $te = $row_ficha_tecnica['tolerancia_te_ft']; if($te == '') { echo "Min 18"; } else { echo $te; }?>" size="10"></td>
          </tr>
          <tr>
            <td id="detalle1">Fuerza de Sello </td>
            <td id="detalle1">G / Pulg </td>
            <td id="detalle1">&gt;= 30 gr / mic </td>
            <td id="detalle2"><input type="text" name="tolerancia_fs_ft" value="<?php $fs = $row_ficha_tecnica['tolerancia_fs_ft']; if($fs == '') { echo ">= 30 gr / mic"; } else { echo $fs; } ?>" size="10"></td>
          </tr>
          <tr>
            <td id="detalle1">Ancho</td>
            <td id="detalle1">Flexometro</td>
            <td id="detalle1">&gt;= 45 </td>
            <td id="detalle2"><input type="text" name="tolerancia_ancho_ft" value="<?php $ancho = $row_ficha_tecnica['tolerancia_ancho_ft']; if($ancho == '') { echo "+/- 0,5 cm"; } else { echo $ancho; } ?>" size="10"></td>
          </tr>
          <tr>
            <td id="detalle1">Calibre</td>
            <td id="detalle1">Comparador de Caratula </td>
            <td id="detalle1">&gt;= 1 </td>
            <td id="detalle2"><input type="text" name="tolerancia_calibre_ft" value="<?php $calibre = $row_ficha_tecnica['tolerancia_calibre_ft']; if($calibre == '') { echo "+/- 10%"; } else { echo $calibre; } ?>" size="10"></td>
          </tr>
          <tr>
            <td id="detalle1">Tratamiento Corona </td>
            <td id="detalle1">Lapiz Tratador </td>
            <td id="detalle1">38 Dinas </td>
            <td id="detalle2"><input type="text" name="tolerancia_tc_ft" value="<?php $tc = $row_ficha_tecnica['tolerancia_tc_ft']; if($tc == '') { echo "38 - 40 Dinas"; } else { echo $tc; }?>" size="10"></td>
          </tr>
          <tr>
            <td colspan="4" id="dato1"><strong>NOTAS</strong>: Los colores de extrusi&oacute;n pueden variar ligeramente de los anotados. se indican solamente como valor de referencia. </td>
            </tr>
          <tr id="tr1">
            <td id="fuente1">TIPO EXTRUSION </td>
            <td id="fuente1">PIGMENTO INTERIOR </td>
            <td id="fuente1">PIGMENTO EXTERIOR </td>
            <td id="fuente1">PRESENTACION</td>
          </tr>
          <tr>
            <td id="dato1"><?php echo $row_egp['tipo_ext_egp']; ?></td>
            <td id="dato1"><?php echo $row_egp['pigm_int_epg']; ?></td>
            <td id="dato1"><?php echo $row_egp['pigm_ext_egp']; ?></td>
            <td id="dato1"><?php echo $row_revision['presentacion_rev']; ?></td>
          </tr>
          <tr id="tr1">
            <td colspan="4" id="titulo1">IMPRESION</td>
            </tr>
          <tr id="tr1">
            <td id="fuente1">ARTE</td>
            <td id="fuente1">MODIFICACION ARTE</td>
            <td id="fuente1">METODO INSPECCION</td>
            <td id="fuente1">TIPO DE BOLSA</td>
          </tr>
          <tr>
            <td id="dato1">[ <?php $archivo=$row_verificacion['userfile']; ?><a href="javascript:verFoto('archivo/<?php echo $archivo;?>','610','490')"> <?php echo $archivo;?></a> ]</td>
            <td id="dato1"><?php echo $row_verificacion['fecha_aprob_arte_verif']; ?></td>
            <td id="dato1"><input type="text" name="metodo_arte" value="<?php echo $row_ficha_tecnica['metodo_arte']; ?>" size="10"></td>
            <td id="dato1"><?php echo $row_referencia['tipo_bolsa_ref']; ?></td>
          </tr>
          <tr id="tr1">
            <td id="fuente1">RODILLO</td>
            <td id="fuente1">PI&Ntilde;ON</td>
            <td id="fuente1">CANTIDAD RODILLOS </td>
            <td id="fuente1">CARAS</td>
          </tr>
          <tr>
            <td id="dato1"><?php echo $row_revision['num_rodillos_rev']; ?></td>
            <td id="dato1"><?php if($row_revision['num_rodillos_rev'] != "") { $pinon=($row_revision['num_rodillos_rev'])*2; $pinon2 =round($pinon*100)/100; } ?><input type="text" name="pinon_ft" value="<?php if($row_ficha_tecnica['pinon_ft'] == '') { echo $pinon2; } else{ echo $row_ficha_tecnica['pinon_ft']; } ?>" size="10"></td>
			
			
            <td id="dato1"><input type="text" name="cant_rod_ft" value="<?php echo $row_ficha_tecnica['cant_rod_ft']; ?>" size="10"></td>
            <td id="dato1"><select name="cara_ft" id="cara_ft">
              <option value="N.A." <?php if (!(strcmp("N.A.", $row_ficha_tecnica['cara_ft']))) {echo "selected=\"selected\"";} ?>>N.A</option>
              <option value="1 cara" <?php if (!(strcmp("1 cara", $row_ficha_tecnica['cara_ft']))) {echo "selected=\"selected\"";} ?>>1 cara</option>
              <option value="2 caras" <?php if (!(strcmp("2 caras", $row_ficha_tecnica['cara_ft']))) {echo "selected=\"selected\"";} ?>>2 caras</option>
            </select></td>
          </tr>
		  <tr id="tr1">
            <td id="detalle2">COLORES</td>
            <td id="detalle2">PANTONE</td>
            <td id="detalle2">COLORES</td>
            <td id="detalle2">PANTONE</td>
          </tr>
          <tr>
            <td id="detalle1">1. <?php echo $row_egp['color1_egp']; ?></td>
            <td id="detalle1">- <?php echo $row_egp['pantone1_egp']; ?></td>
            <td id="detalle1">4. <?php echo $row_egp['color4_egp']; ?></td>
            <td id="detalle1">- <?php echo $row_egp['pantone4_egp']; ?></td>
          </tr>
          <tr>
            <td id="detalle1">2. <?php echo $row_egp['color2_egp']; ?></td>
            <td id="detalle1">- <?php echo $row_egp['pantone2_egp']; ?></td>
            <td id="detalle1">5. <?php echo $row_egp['color5_egp']; ?></td>
            <td id="detalle1">- <?php echo $row_egp['pantone5_egp']; ?></td>
          </tr>
          <tr>
            <td id="detalle1">3. <?php echo $row_egp['color3_egp']; ?></td>
            <td id="detalle1">- <?php echo $row_egp['pantone3_egp']; ?></td>
            <td id="detalle1">6. <?php echo $row_egp['color6_egp']; ?></td>
            <td id="detalle1">- <?php echo $row_egp['pantone6_egp']; ?></td>
          </tr>
          <tr id="tr1">
            <td colspan="4" id="titulo1">SELLADO</td>
            </tr>
          <tr id="tr1">
            <td id="detalle2">POSICION</td>
            <td id="detalle2">TIPO DE NUMERACION </td>
            <td id="detalle2">FORMATO CB </td>
            <td id="fuente1">TIPO DE ADHESIVO </td>
          </tr>
          <tr>
            <td id="detalle1">SOLAPA TR </td>
            <td id="detalle1">- <?php echo $row_egp['tipo_solapatr_egp']; ?></td>
            <td id="detalle1">- <?php echo $row_egp['cb_solapatr_egp']; ?></td>
            <td id="dato1"><?php echo $row_referencia['adhesivo_ref']; ?></td>
          </tr>
          <tr>
            <td id="detalle1">CINTA</td>
            <td id="detalle1">- <?php echo $row_egp['tipo_cinta_egp']; ?></td>
            <td id="detalle1">- <?php echo $row_egp['cb_cinta_egp']; ?></td>
            <td><select name="adhesivo_ref_ft" id="adhesivo_ref_ft">
              <option value="N.A." <?php if (!(strcmp("N.A.", $row_ficha_tecnica['adhesivo_ref_ft']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
              <?php
do {  
?><option value="<?php echo $row_adhesivo['codigo_insumo']?>"<?php if (!(strcmp($row_adhesivo['codigo_insumo'], $row_ficha_tecnica['adhesivo_ref_ft']))) {echo "selected=\"selected\"";} ?>><?php echo $row_adhesivo['codigo_insumo']?></option>
                <?php
} while ($row_adhesivo = mysql_fetch_assoc($adhesivo));
  $rows = mysql_num_rows($adhesivo);
  if($rows > 0) {
      mysql_data_seek($adhesivo, 0);
	  $row_adhesivo = mysql_fetch_assoc($adhesivo);
  }
?>
            </select></td>
          </tr>
          <tr>
            <td id="detalle1">PRINCIPAL</td>
            <td id="detalle1">- <?php echo $row_egp['tipo_principal_egp']; ?></td>
            <td id="detalle1">- <?php echo $row_egp['cb_principal_egp']; ?></td>
            <td id="fuente1">TIPO DE SELLO </td>
          </tr>
          <tr>
            <td id="detalle1">INFERIOR</td>
            <td id="detalle1">- <?php echo $row_egp['tipo_inferior_egp']; ?></td>
            <td id="detalle1">- <?php echo $row_egp['cb_inferior_egp']; ?></td>
            <td id="dato1"><?php echo $row_egp['tipo_sello_egp']; ?></td>
          </tr>
          <tr id="tr1">
            <td colspan="4" id="titulo1">EMPAQUE</td>
            </tr>
          <tr id="tr1">
            <td id="fuente1"><input type="checkbox" name="control_ft" value="1"  <?php if (!(strcmp($row_ficha_tecnica['control_ft'],1))) {echo "@@checked@@";} ?>>
              Control Numeraci&oacute;n </td>
            <td id="fuente1"><input type="checkbox" name="lista_emp_ft" value="1"  <?php if (!(strcmp($row_ficha_tecnica['lista_emp_ft'],1))) {echo "@@checked@@";} ?>>
List / Emp</td>
            <td id="fuente1">Dimension de la Caja</td>
            <td id="fuente1">Peso de la Caja </td>
          </tr>
          <tr>
            <td id="fuente1"><input type="checkbox" name="inserto_ft" value="1"  <?php if (!(strcmp($row_ficha_tecnica['inserto_ft'],1))) {echo "@@checked@@";} ?>>
              Insertos Especiales</td>
            <td id="fuente1"><input type="checkbox" name="dist_ciud_ft" value="1"  <?php if (!(strcmp($row_ficha_tecnica['dist_ciud_ft'],1))) {echo "@@checked@@";} ?>>
              Dist. Ciudades</td>
            <td id="fuente1"><select name="dim_caja_ft" id="dim_caja_ft" onBlur="DatosConsulta('id_insumo',form1.dim_caja_ft.value);" >
              <option value="" <?php if (!(strcmp("", $row_ficha_tecnica['dim_caja_ft']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
              <?php
do {  
?><option value="<?php echo $row_empaques['id_insumo']?>"<?php if (!(strcmp($row_empaques['id_insumo'], $row_ficha_tecnica['dim_caja_ft']))) {echo "selected=\"selected\"";} ?>><?php echo $row_empaques['dimension_insumo']?></option>
                <?php
} while ($row_empaques = mysql_fetch_assoc($empaques));
  $rows = mysql_num_rows($empaques);
  if($rows > 0) {
      mysql_data_seek($empaques, 0);
	  $row_empaques = mysql_fetch_assoc($empaques);
  }
?>
            </select></td>
            <td id="fuente1"><div id="resultado"></div></td>
          </tr>
          <tr id="tr1">
            <td id="fuente1">Unids x Paquete</td>
            <td id="fuente1">Unids x Caja</td>
            <td id="fuente1">Paquetes x Caja</td>
            <td id="fuente1">Peso Bruto (Kg)</td>
          </tr>
          <tr>
            <td id="dato1"><?php echo $row_egp['unids_paq_egp']; ?></td>
            <td id="dato1"><?php echo $row_egp['unids_caja_egp']; ?></td>
            <td><?php if (($row_egp['unids_caja_egp'] != "" && $row_egp['unids_paq_egp'] != "") && ($row_egp['unids_caja_egp'] != 0 && $row_egp['unids_paq_egp'] != 0)) { $paquete=($row_egp['unids_caja_egp'])/($row_egp['unids_paq_egp']); $paquete2 =(round($paquete*100)/100); } ?>
              <input type="text" name="paq_caja_ft" value="<?php echo $paquete2; ?>" size="10"></td>
            <td><input name="peso_millar" type="hidden" value="<?php echo $row_referencia['peso_millar_ref']; ?>">
			<input name="unids_caja" type="hidden" value="<?php echo $row_egp['unids_caja_egp']; ?>">
			<input type="text" name="peso_bruto_ft" value="<?php echo $row_ficha_tecnica['peso_bruto_ft']; ?>" size="10" onBlur="calcularft(form1.peso_millar.value,form1.unids_caja.value)"></td>
          </tr>
          <tr id="tr1">
            <td colspan="4" id="titulo1">CONDICIONES DE USO Y ALMACENAMIENTO</td>
          </tr>
          <tr>
            <td colspan="4" id="dato1"><strong>Vida Util:</strong> 12 a 18 meses maximo despues de fecha de producci&oacute;n.</td>
          </tr>
          
         <td colspan="2" id="dato1"><strong>Temp. Almacenamiento:</strong> 18 a 35 &deg;C - 55% +- 5%RH </td>
            <td colspan="2" id="dato1"><strong>Resistencia Maxima</strong> (Kg) <input type="text" name="resistencia_maxima_ft" value="<?php $rs = $row_ficha_tecnica['resistencia_maxima_ft']; if($rs == '') { echo "8.51"; } else { echo $rs; } ?>" size="10"></td>
          </tr>
		  <tr id="tr1">
            <td colspan="2" id="detalle2">Condiciones de Almacenamiento </td>
            <td colspan="2" id="detalle2">Metodos de Inspecci&oacute;n </td>
            </tr>
          <tr>
            <td colspan="2" id="detalle1">1. Se deben de guardar en cajas o en paquetes protegiendo del polvo y humedad.<br>
2. No exponer a los rayos directos del sol ni al agua.<br>
3. Evitar el contacto con solventes o vapores que afecten el adhesivo.<br>
4. Siempre dar rotaci&oacute;n a los lotes antiguos para evitar caducidad.<br>
5. Evitar en el transporte el roce entre paquetes de bolsas.<br>
6. Conservar el control de numeraci&oacute;n por paquete para la trazabilidad.</td>
            <td colspan="2" id="detalle1">1. Analisis de laboratorio a la cinta de seguridad.<br>
2. Pruebas mecanicas de laboratorio material extruido.<br>
3. Pruebas de manipulacion y resistencia producto final.</td>
            </tr>
          <tr>
            <td colspan="4" id="dato1"><strong>Notas:</strong> 1. El valor de Resistencia Maxima se toma de acuerdo a los valores obtenidos en el laboratorio. Se recomienda realizar pruebas y ensayos antes de determinar el peso a empacar. </td>
            </tr>
          <tr id="tr1">
            <td id="fuente1">ESTADO FT</td>
            <td id="fuente1">FECHA MODIF. </td>
            <td colspan="2" id="fuente1">MODIFICADO POR </td>
            </tr>
          <tr>
            <td id="dato1"><select name="estado_ft">
              <option value="Activa" <?php if (!(strcmp("Activa", $row_ficha_tecnica['estado_ft']))) {echo "selected=\"selected\"";} ?>>Activa</option>
              <option value="Inactiva" <?php if (!(strcmp("Inactiva", $row_ficha_tecnica['estado_ft']))) {echo "selected=\"selected\"";} ?>>Inactiva</option>
            </select></td>
            <td id="dato1"><input name="fecha_modif_ft" type="hidden" value="<?php echo date("Y-m-d"); ?>">
              <?php echo $row_ficha_tecnica['fecha_modif_ft']; ?></td>
            <td colspan="2" id="dato1"><input name="addcambio_ft" type="hidden" value="<?php echo $row_usuario['nombre_usuario']; ?>">
              <?php echo $row_ficha_tecnica['addcambio_ft']; ?></td>
            </tr>
          <tr id="tr1">
            <td colspan="4" id="dato2"><input type="submit" value="Actualizar FT"></td>
            </tr>
        </table>
        <input type="hidden" name="MM_update" value="form1">
        <input type="hidden" name="n_ft" value="<?php echo $row_ficha_tecnica['n_ft']; ?>">
      </form></td>
  </tr></table>
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

mysql_free_result($referencia);

mysql_free_result($revision);

mysql_free_result($verificacion);

mysql_free_result($validacion);

mysql_free_result($egp);

mysql_free_result($adhesivo);

mysql_free_result($empaques);

mysql_free_result($ficha_tecnica);
?>