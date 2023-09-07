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
  $updateSQL = sprintf("UPDATE cotizacion SET id_c_cotiz=%s, responsable_cotiz=%s, fecha_cotiz=%s, hora_cotiz=%s, observacion_cotiz=%s, responsable_modif=%s, fecha_modif=%s, hora_modif=%s WHERE n_cotiz=%s",
                       GetSQLValueString($_POST['id_c_cotiz'], "int"),
                       GetSQLValueString($_POST['responsable_cotiz'], "text"),
                       GetSQLValueString($_POST['fecha_cotiz'], "date"),
                       GetSQLValueString($_POST['hora_cotiz'], "text"),
                       GetSQLValueString($_POST['observacion_cotiz'], "text"),
                       GetSQLValueString($_POST['responsable_modif'], "text"),
                       GetSQLValueString($_POST['fecha_modif'], "date"),
                       GetSQLValueString($_POST['hora_modif'], "text"),
                       GetSQLValueString($_POST['n_cotiz'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "cotizacion_bolsa_vista.php?n_cotiz=" . $_POST['n_cotiz'] . "&tipo=" . $_POST['tipo'] . "";
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

$colname_cotizacion = "-1";
if (isset($_GET['n_cotiz'])) {
  $colname_cotizacion = (get_magic_quotes_gpc()) ? $_GET['n_cotiz'] : addslashes($_GET['n_cotiz']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_cotizacion = sprintf("SELECT * FROM cotizacion WHERE n_cotiz = '%s'", $colname_cotizacion);
$cotizacion = mysql_query($query_cotizacion, $conexion1) or die(mysql_error());
$row_cotizacion = mysql_fetch_assoc($cotizacion);
$totalRows_cotizacion = mysql_num_rows($cotizacion);

$colname_cliente = "-1";
if (isset($_GET['id_c_cotiz'])) {
  $colname_cliente = (get_magic_quotes_gpc()) ? $_GET['id_c_cotiz'] : addslashes($_GET['id_c_cotiz']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_cliente = sprintf("SELECT * FROM cliente WHERE id_c = %s", $colname_cliente);
$cliente = mysql_query($query_cliente, $conexion1) or die(mysql_error());
$row_cliente = mysql_fetch_assoc($cliente);
$totalRows_cliente = mysql_num_rows($cliente);

mysql_select_db($database_conexion1, $conexion1);
$query_clientes = "SELECT * FROM cliente ORDER BY nombre_c ASC";
$clientes = mysql_query($query_clientes, $conexion1) or die(mysql_error());
$row_clientes = mysql_fetch_assoc($clientes);
$totalRows_clientes = mysql_num_rows($clientes);

$colname_nueva = "-1";
if (isset($_GET['n_cotiz'])) {
  $colname_nueva = (get_magic_quotes_gpc()) ? $_GET['n_cotiz'] : addslashes($_GET['n_cotiz']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_nueva = sprintf("SELECT * FROM cotizacion_nueva WHERE n_cotiz_cn = %s ORDER BY n_cn ASC", $colname_nueva);
$nueva = mysql_query($query_nueva, $conexion1) or die(mysql_error());
$row_nueva = mysql_fetch_assoc($nueva);
$totalRows_nueva = mysql_num_rows($nueva);

$colname_existente = "-1";
if (isset($_GET['n_cotiz'])) {
  $colname_existente = (get_magic_quotes_gpc()) ? $_GET['n_cotiz'] : addslashes($_GET['n_cotiz']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_existente = sprintf("SELECT * FROM cotizacion_existente, referencia WHERE cotizacion_existente.n_cotiz_ce = '%s' AND cotizacion_existente.id_ref_ce = referencia.id_ref", $colname_existente);
$existente = mysql_query($query_existente, $conexion1) or die(mysql_error());
$row_existente = mysql_fetch_assoc($existente);
$totalRows_existente = mysql_num_rows($existente);
?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
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
<li><a href="comercial.php">GESTION COMERCIAL</a></li>
</ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center">
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" onSubmit="MM_validateForm('id_c_cotiz','','R','fecha_cotiz','','R','responsable_cotiz','','R','hora_cotiz','','R');return document.MM_returnValue">
  <table id="tabla2" align="center">
    <tr id="tr1">
      <td id="codigo" width="25%">CODIGO : R1 - F01</td>
      <td id="titulo2" width="50%">COTIZACION ( BOLSA SEGURIDAD) </td>
      <td id="codigo" width="25%">VERSION : 0</td>
    </tr>
    <tr>
      <td rowspan="6" id="dato2"><img src="images/logoacyc.jpg"></td>
      <td id="dato2"><a href="cotizacion_bolsa_vista.php?n_cotiz=<?php echo $row_cotizacion['n_cotiz']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>"><img src="images/hoja.gif" alt="VISTA IMPRESION" border="0" style="cursor:hand;"/></a><a href="javascript:eliminar1('n_cotiz',<?php echo $row_cotizacion['n_cotiz']; ?>,'cotizacion_bolsa_edit.php')"><img src="images/por.gif" alt="ELIMINAR COTIZACION" border="0" style="cursor:hand;"/></a><a href="cotizacion_bolsa.php"><img src="images/cat.gif" alt="COTIZACIONES" border="0" style="cursor:hand;"/></a><a href="cotizacion_menu.php"><img src="images/opciones.gif" alt="MENU COTIZACION" border="0" style="cursor:hand;"/></a></td>
      <td id="fuente1">Fecha Registro:</td>
    </tr>
    <tr>
      <td id="numero2">N&deg; <strong><?php echo $row_cotizacion['n_cotiz']; ?></strong></td>
      <td id="dato1"><input name="fecha_cotiz" type="text" value="<?php echo $row_cotizacion['fecha_cotiz']; ?>" size="10"></td>    </tr>
    <tr>
      <td id="fuente1">Responsable del Registro:
          <input type="hidden" name="tipo" value="<?php echo $row_usuario['tipo_usuario']; ?>"></td>
      <td id="fuente1">Hora Registro: </td>
    </tr>
    <tr>
      <td id="dato1"><input name="responsable_cotiz" type="text" value="<?php echo $row_cotizacion['responsable_cotiz']; ?>" size="30"></td>
      <td id="dato1"><input name="hora_cotiz" type="text" value="<?php echo $row_cotizacion['hora_cotiz']; ?>" size="10"></td>
    </tr>
    <tr>
      <td colspan="2" id="fuente1">Cliente a Cotizar</td>
      </tr>    
    <tr>
      <td colspan="2" nowrap id="dato1"><select  name="id_c_cotiz" id="id_c_cotiz" onBlur="consultacliente()">
        <?php
do {  
?>
        <option value="<?php echo $row_clientes['id_c']?>"<?php if (!(strcmp($row_clientes['id_c'], $_GET['id_c_cotiz']))) {echo "selected=\"selected\"";} ?>><?php echo $row_clientes['nombre_c']?></option>
        <?php
} while ($row_clientes = mysql_fetch_assoc($clientes));
  $rows = mysql_num_rows($clientes);
  if($rows > 0) {
      mysql_data_seek($clientes, 0);
	  $row_clientes = mysql_fetch_assoc($clientes);
  }
?>
      </select></td>
      </tr>
    <tr>
      <td colspan="2" id="detalle1">NIT : <?php echo $row_cliente['nit_c']; ?></td>
      <td id="detalle1">Telefono : <?php echo $row_cliente['telefono_c']; ?></td>
    </tr>
    <tr>
      <td colspan="2" id="detalle1">Contacto Comercial : <?php echo $row_cliente['contacto_c']; ?></td>
      <td id="detalle1">Fax : <?php echo $row_cliente['fax_c']; ?></td>
    </tr>
    <tr>
      <td colspan="2" nowrap id="detalle1">Cargo : <?php echo $row_cliente['cargo_contacto_c']; ?></td>
      <td id="detalle1">Pa&iacute;s : <?php echo $row_cliente['pais_c']; ?></td>
    </tr>
    <tr>
      <td colspan="2" id="detalle1">Email : <?php echo $row_cliente['email_comercial_c']; ?></td>
      <td id="detalle1">Ciudad : <?php echo $row_cliente['ciudad_c']; ?></td>
    </tr>
    <tr>
      <td colspan="3" id="detalle1">Direcci&oacute;n : <?php echo $row_cliente['direccion_c']; ?></td>
      </tr>
    <tr>
      <td colspan="3" id="fuente1"> Observaciones de la Cotizaci&oacute;n : </td>
      </tr>
    <tr>
      <td colspan="3" id="dato1"><textarea name="observacion_cotiz" cols="80" rows="2"><?php echo $row_cotizacion['observacion_cotiz']; ?></textarea></td>
      </tr>
    
    <tr>
      <td colspan="2" id="dato1">Ultima Actualizaci&oacute;n : <?php echo $row_cotizacion['responsable_modif']; ?>
        <input name="responsable_modif" type="hidden" value="<?php echo $row_usuario['nombre_usuario']; ?>"> 
        - <?php echo $row_cotizacion['fecha_modif']; ?>
        <input name="fecha_modif" type="hidden" value="<?php echo date("Y-m-d"); ?>">
        <?php echo $row_cotizacion['hora_modif']; ?>
<input name="hora_modif" type="hidden" value="<?php echo date("g:i a") ?>"></td>
      <td id="dato2"><input name="submit" type="submit" value="Finalizar Cotizacion"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1">
  <input type="hidden" name="n_cotiz" value="<?php echo $row_cotizacion['n_cotiz']; ?>">
</form>
</td>
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
<div align="center">
<table id="tabla">
  <tr>
    <td colspan="12" id="fuente2"><strong><a href="cotizacion_bolsa_nueva.php?n_cotiz=<?php echo $row_cotizacion['n_cotiz']; ?>">REFERENCIA NUEVA </a></strong></td>
    </tr>
	<?php $n_cn=$row_nueva['n_cn']; if($n_cn!='') {?>
  <tr>
    <td colspan="12" align="center">	
	<form name="form2" method="post" action="cotizacion_estado.php">
	<table id="tabla">
      <tr id="tr2">
        <td id="titulo4">REFERENCIA</td>
        <td nowrap id="titulo4">EGP</td>
        <td id="titulo4">Tipo</td>
        <td id="titulo4">Material</td>
        <td id="titulo4">Adhesivo</td>
        <td id="titulo4">Ancho</td>
        <td id="titulo4">Largo</td>
        <td id="titulo4">Solapa</td>
        <td id="titulo4">Bolsillo</td>
        <td id="titulo4">Calibre</td>
        <td id="titulo4">Peso</td>
        <td id="titulo4">Aceptada</td>
      </tr>
	  <?php do { ?>
	   <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#ECF5FF');" bgcolor="#ECF5FF" bordercolor="#ACCFE8">
        <td id="detalle1"><a href="cotizacion_bolsa_nueva_edit.php?n_cn= <?php echo $row_nueva['n_cn']; ?>&n_cotiz=<?php echo $row_nueva['n_cotiz_cn']; ?>&n_egp=<?php echo $row_nueva['n_egp_cn']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_nueva['cod_ref_cn']; ?></a></td>
        <td id="detalle3"><a href="cotizacion_bolsa_nueva_edit.php?n_cn= <?php echo $row_nueva['n_cn']; ?>&n_cotiz=<?php echo $row_nueva['n_cotiz_cn']; ?>&n_egp=<?php echo $row_nueva['n_egp_cn']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_nueva['n_egp_cn']; ?></a></td>
        <td id="detalle1"><a href="cotizacion_bolsa_nueva_edit.php?n_cn= <?php echo $row_nueva['n_cn']; ?>&n_cotiz=<?php echo $row_nueva['n_cotiz_cn']; ?>&n_egp=<?php echo $row_nueva['n_egp_cn']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_nueva['tipo_bolsa_cn']; ?></a></td>
        <td id="detalle1"><a href="cotizacion_bolsa_nueva_edit.php?n_cn= <?php echo $row_nueva['n_cn']; ?>&n_cotiz=<?php echo $row_nueva['n_cotiz_cn']; ?>&n_egp=<?php echo $row_nueva['n_egp_cn']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_nueva['material_cn']; ?></a></td>
        <td id="detalle1"><a href="cotizacion_bolsa_nueva_edit.php?n_cn= <?php echo $row_nueva['n_cn']; ?>&n_cotiz=<?php echo $row_nueva['n_cotiz_cn']; ?>&n_egp=<?php echo $row_nueva['n_egp_cn']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_nueva['adhesivo_cn']; ?></a></td>
        <td id="detalle3"><a href="cotizacion_bolsa_nueva_edit.php?n_cn= <?php echo $row_nueva['n_cn']; ?>&n_cotiz=<?php echo $row_nueva['n_cotiz_cn']; ?>&n_egp=<?php echo $row_nueva['n_egp_cn']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_nueva['ancho_cn']; ?></a></td>
        <td id="detalle3"><a href="cotizacion_bolsa_nueva_edit.php?n_cn= <?php echo $row_nueva['n_cn']; ?>&n_cotiz=<?php echo $row_nueva['n_cotiz_cn']; ?>&n_egp=<?php echo $row_nueva['n_egp_cn']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_nueva['largo_cn']; ?></a></td>
        <td id="detalle3"><a href="cotizacion_bolsa_nueva_edit.php?n_cn= <?php echo $row_nueva['n_cn']; ?>&n_cotiz=<?php echo $row_nueva['n_cotiz_cn']; ?>&n_egp=<?php echo $row_nueva['n_egp_cn']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_nueva['solapa_cn']; ?></a></td>
        <td id="detalle3"><a href="cotizacion_bolsa_nueva_edit.php?n_cn= <?php echo $row_nueva['n_cn']; ?>&n_cotiz=<?php echo $row_nueva['n_cotiz_cn']; ?>&n_egp=<?php echo $row_nueva['n_egp_cn']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_nueva['bolsillo_guia_cn']; ?></a></td>
        <td id="detalle3"><a href="cotizacion_bolsa_nueva_edit.php?n_cn= <?php echo $row_nueva['n_cn']; ?>&n_cotiz=<?php echo $row_nueva['n_cotiz_cn']; ?>&n_egp=<?php echo $row_nueva['n_egp_cn']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_nueva['calibre_cn']; ?></a></td>
        <td id="detalle3"><a href="cotizacion_bolsa_nueva_edit.php?n_cn= <?php echo $row_nueva['n_cn']; ?>&n_cotiz=<?php echo $row_nueva['n_cotiz_cn']; ?>&n_egp=<?php echo $row_nueva['n_egp_cn']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_nueva['peso_millar_cn']; ?></a></td>
        <td id="detalle2"><input <?php if (!(strcmp($row_nueva['estado_cn'],1))) {echo "checked=\"checked\"";} ?> name="reg[<?php echo $row_nueva['n_cn']; ?>]" type="checkbox" value="reg[<?php echo $row_nueva['n_cn']; ?>]"></td>
      </tr>
	  <?php } while ($row_nueva = mysql_fetch_assoc($nueva)); ?>
      <tr>
        <td colspan="12" id="dato3"><input name="n_cotiz" type="hidden" id="n_cotiz" value="<?php echo $row_cotizacion['n_cotiz']; ?>">
            <input name="id_c_cotiz" type="hidden" id="id_c_cotiz" value="<?php echo $row_cotizacion['id_c_cotiz']; ?>">
            <input type="submit" name="Submit" value="Aceptar"></td>
      </tr>
    </table>
	</form>	</td>
  </tr>
  
  <?php } ?>
  <tr>
    <td colspan="12" id="fuente2"><strong><a href="cotizacion_bolsa_existente.php?n_cotiz=<?php echo $row_cotizacion['n_cotiz']; ?>&id_c=<?php echo $row_cliente['id_c']; ?>">REFERENCIA EXISTENTE</a></strong></td>
  </tr>
  <?php $ce=$row_existente['n_ce']; if($ce!='') {?>
  <tr id="tr2">
    <td id="titulo4">REFERENCIA</td>
    <td id="titulo4">EGP</td>
    <td id="titulo4">Tipo</td>
    <td id="titulo4">Material</td>
    <td id="titulo4">Adhesivo</td>
    <td id="titulo4">Ancho</td>
    <td id="titulo4">Largo</td>
    <td id="titulo4">Solapa</td>
    <td id="titulo4">Bolsillo</td>
    <td id="titulo4">Calibre</td>
    <td id="titulo4">Peso</td>
    <td id="titulo4">Estado</td>
  </tr>
  <?php do { ?>
    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#ECF5FF');" bgcolor="#ECF5FF" bordercolor="#ACCFE8">
      <td id="detalle1"><a href="cotizacion_bolsa_existente_edit.php?n_cotiz=<?php echo $row_existente['n_cotiz_ce']; ?>&id_ref=<?php echo $row_existente['id_ref_ce']; ?>&n_ce= <?php echo $row_existente['n_ce']; ?>&id_c=<?php echo $row_cliente['id_c']; ?>&id_ref1=<?php echo $row_existente['id_ref_ce']; ?>&n_ce= <?php echo $row_existente['n_ce']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_existente['cod_ref']; ?></a></td>
      <td id="detalle3"><a href="cotizacion_bolsa_existente_edit.php?n_cotiz=<?php echo $row_existente['n_cotiz_ce']; ?>&id_ref=<?php echo $row_existente['id_ref_ce']; ?>&n_ce= <?php echo $row_existente['n_ce']; ?>&id_c=<?php echo $row_cliente['id_c']; ?>&id_ref1=<?php echo $row_existente['id_ref_ce']; ?>&n_ce= <?php echo $row_existente['n_ce']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_existente['n_egp_ref']; ?></a></td>
      <td id="detalle1"><a href="cotizacion_bolsa_existente_edit.php?n_cotiz=<?php echo $row_existente['n_cotiz_ce']; ?>&id_ref=<?php echo $row_existente['id_ref_ce']; ?>&n_ce= <?php echo $row_existente['n_ce']; ?>&id_c=<?php echo $row_cliente['id_c']; ?>&id_ref1=<?php echo $row_existente['id_ref_ce']; ?>&n_ce= <?php echo $row_existente['n_ce']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_existente['tipo_bolsa_ref']; ?></a></td>
      <td id="detalle1"><a href="cotizacion_bolsa_existente_edit.php?n_cotiz=<?php echo $row_existente['n_cotiz_ce']; ?>&id_ref=<?php echo $row_existente['id_ref_ce']; ?>&n_ce= <?php echo $row_existente['n_ce']; ?>&id_c=<?php echo $row_cliente['id_c']; ?>&id_ref1=<?php echo $row_existente['id_ref_ce']; ?>&n_ce= <?php echo $row_existente['n_ce']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_existente['material_ref']; ?></a></td>
      <td id="detalle1"><a href="cotizacion_bolsa_existente_edit.php?n_cotiz=<?php echo $row_existente['n_cotiz_ce']; ?>&id_ref=<?php echo $row_existente['id_ref_ce']; ?>&n_ce= <?php echo $row_existente['n_ce']; ?>&id_c=<?php echo $row_cliente['id_c']; ?>&id_ref1=<?php echo $row_existente['id_ref_ce']; ?>&n_ce= <?php echo $row_existente['n_ce']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_existente['adhesivo_ref']; ?></a></td>
      <td id="detalle3"><a href="cotizacion_bolsa_existente_edit.php?n_cotiz=<?php echo $row_existente['n_cotiz_ce']; ?>&id_ref=<?php echo $row_existente['id_ref_ce']; ?>&n_ce= <?php echo $row_existente['n_ce']; ?>&id_c=<?php echo $row_cliente['id_c']; ?>&id_ref1=<?php echo $row_existente['id_ref_ce']; ?>&n_ce= <?php echo $row_existente['n_ce']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_existente['ancho_ref']; ?></a></td>
      <td id="detalle3"><a href="cotizacion_bolsa_existente_edit.php?n_cotiz=<?php echo $row_existente['n_cotiz_ce']; ?>&id_ref=<?php echo $row_existente['id_ref_ce']; ?>&n_ce= <?php echo $row_existente['n_ce']; ?>&id_c=<?php echo $row_cliente['id_c']; ?>&id_ref1=<?php echo $row_existente['id_ref_ce']; ?>&n_ce= <?php echo $row_existente['n_ce']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_existente['largo_ref']; ?></a></td>
      <td id="detalle3"><a href="cotizacion_bolsa_existente_edit.php?n_cotiz=<?php echo $row_existente['n_cotiz_ce']; ?>&id_ref=<?php echo $row_existente['id_ref_ce']; ?>&n_ce= <?php echo $row_existente['n_ce']; ?>&id_c=<?php echo $row_cliente['id_c']; ?>&id_ref1=<?php echo $row_existente['id_ref_ce']; ?>&n_ce= <?php echo $row_existente['n_ce']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_existente['solapa_ref']; ?></a></td>
      <td id="detalle3"><a href="cotizacion_bolsa_existente_edit.php?n_cotiz=<?php echo $row_existente['n_cotiz_ce']; ?>&id_ref=<?php echo $row_existente['id_ref_ce']; ?>&n_ce= <?php echo $row_existente['n_ce']; ?>&id_c=<?php echo $row_cliente['id_c']; ?>&id_ref1=<?php echo $row_existente['id_ref_ce']; ?>&n_ce= <?php echo $row_existente['n_ce']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_existente['bolsillo_guia_ref']; ?></a></td>
      <td id="detalle3"><a href="cotizacion_bolsa_existente_edit.php?n_cotiz=<?php echo $row_existente['n_cotiz_ce']; ?>&id_ref=<?php echo $row_existente['id_ref_ce']; ?>&n_ce= <?php echo $row_existente['n_ce']; ?>&id_c=<?php echo $row_cliente['id_c']; ?>&id_ref1=<?php echo $row_existente['id_ref_ce']; ?>&n_ce= <?php echo $row_existente['n_ce']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_existente['calibre_ref']; ?></a></td>
      <td id="detalle3"><a href="cotizacion_bolsa_existente_edit.php?n_cotiz=<?php echo $row_existente['n_cotiz_ce']; ?>&id_ref=<?php echo $row_existente['id_ref_ce']; ?>&n_ce= <?php echo $row_existente['n_ce']; ?>&id_c=<?php echo $row_cliente['id_c']; ?>&id_ref1=<?php echo $row_existente['id_ref_ce']; ?>&n_ce= <?php echo $row_existente['n_ce']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_existente['peso_millar_ref']; ?></a></td>
      <td id="detalle2"><a href="cotizacion_bolsa_existente_edit.php?n_cotiz=<?php echo $row_existente['n_cotiz_ce']; ?>&id_ref=<?php echo $row_existente['id_ref_ce']; ?>&n_ce= <?php echo $row_existente['n_ce']; ?>&id_c=<?php echo $row_cliente['id_c']; ?>&id_ref1=<?php echo $row_existente['id_ref_ce']; ?>&n_ce= <?php echo $row_existente['n_ce']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php $estado=$row_existente['estado_ref']; if($estado=='0') { echo "INACTIVA";} if($estado=='1') { echo "ACTIVA";} ?></a></td>
    </tr>
    <?php } while ($row_existente = mysql_fetch_assoc($existente)); ?>
  <tr>
    <td height="26" colspan="12" id="detalle">&nbsp;</td>
    </tr>
	<?php } ?>	
</table>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($cotizacion);

mysql_free_result($cliente);

mysql_free_result($clientes);

mysql_free_result($nueva);

mysql_free_result($existente);
?>