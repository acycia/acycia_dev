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
include('rud_cotizaciones/rud_cotizacion_bolsa.php');//SISTEMA RUW PARA LA BASE DE DATOS 
$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);
//PARA IMPRIMIR NUMERO DE COTIZACION
mysql_select_db($database_conexion1, $conexion1);
$query_cotizacion = "SELECT * FROM Tbl_cotizaciones ORDER BY N_cotizacion DESC";
$cotizacion = mysql_query($query_cotizacion, $conexion1) or die(mysql_error());
$row_cotizacion = mysql_fetch_assoc($cotizacion);
$totalRows_cotizacion = mysql_num_rows($cotizacion);
//TRAE EL NIT DEL CLIENTE
mysql_select_db($database_conexion1, $conexion1);
$query_cliente = "SELECT * FROM cliente ORDER BY nombre_c ASC";
$cliente = mysql_query($query_cliente, $conexion1) or die(mysql_error());
$row_cliente = mysql_fetch_assoc($cliente);
$totalRows_cliente = mysql_num_rows($cliente);
//IMRPIME EL NOMBRE DEL VENDEDOR
mysql_select_db($database_conexion1, $conexion1);
$query_vendedores = "SELECT * FROM vendedor ORDER BY nombre_vendedor ASC";
$vendedores = mysql_query($query_vendedores, $conexion1) or die(mysql_error());
$row_vendedores = mysql_fetch_assoc($vendedores);
$totalRows_vendedores = mysql_num_rows($vendedores);
//SELECT PARA REALIZAR UPDATE EN LA BASE DE DATOS
$colname_ver_bolsa2 = "-1";
if (isset($_GET['id_ref'])) 
{
  $colname_ver_bolsa2 = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_bolsa = sprintf("SELECT * FROM Tbl_referencia,Tbl_cotiza_bolsa WHERE Tbl_referencia.id_ref='%s' and Tbl_referencia.n_cotiz_ref=Tbl_cotiza_bolsa.N_cotizacion and Tbl_referencia.cod_ref=Tbl_cotiza_bolsa.N_referencia_c",$colname_ver_bolsa2);
$bolsa = mysql_query($query_bolsa, $conexion1) or die(mysql_error());
$row_bolsa = mysql_fetch_assoc($bolsa);
$totalRows_bolsa = mysql_num_rows($bolsa);
//OBSERVACIONES
$colname_obs = "-1";
if (isset($_GET['id_ref'])) 
{
  $colname_obs= (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_obs = sprintf("SELECT * FROM Tbl_referencia,Tbl_cotiza_bolsa_obs WHERE Tbl_referencia.id_ref='%s' and Tbl_referencia.n_cotiz_ref=Tbl_cotiza_bolsa_obs.N_cotizacion ",$colname_obs);
$obs = mysql_query($query_obs, $conexion1) or die(mysql_error());
$row_obs = mysql_fetch_assoc($obs);
$totalRows_obs = mysql_num_rows($obs);
//EVALUAR LAS REFERENCIAS GENERICAS
mysql_select_db($database_conexion1, $conexion1);
$query_referencias = "SELECT * FROM  Tbl_cotiza_bolsa,Tbl_cotizaciones  WHERE Tbl_cotizaciones.B_generica='1' and Tbl_cotizaciones.N_cotizacion=Tbl_cotiza_bolsa.N_cotizacion ORDER BY Tbl_cotizaciones.N_cotizacion DESC ";
$referencias = mysql_query($query_referencias, $conexion1) or die(mysql_error());
$row_referencias = mysql_fetch_assoc($referencias);
$totalRows_referencias = mysql_num_rows($referencias);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/delete.js"></script>
<script type="text/javascript" src="js/adjuntos.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
<script type="text/javascript">
function MM_popupMsg(msg) { //v1.0
  alert(msg);
}
</script>
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
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" enctype="multipart/form-data" onsubmit="MM_validateForm('Str_nit','','R','N_ancho','','RisNum','N_alto','','RisNum','N_solapa','','RisNum','B_fuelle','','RisNum','N_calibre','','RisNum','N_precio','','RisNum','N_cant_impresion','','RisNum','N_comision','','RisNum','vendedor','','RisNum');return document.MM_returnValue"><table id="tabla2">
      <tr id="tr1">
        <td width="113" nowrap="nowrap" id="codigo">CODIGO : R1-F08 </td>
        <td colspan="2" nowrap="nowrap" id="titulo2">COTIZACION BOLSA</td>
        <td colspan="2" nowrap="nowrap" id="codigo">VERSION: 2 </td>
      </tr>
      <tr>
        <td rowspan="9" id="fuente2"><img src="images/logoacyc.jpg"></td>
        <td colspan="2" id="numero1"><strong>NIT N&deg;
          <input type="text" name="Str_nit" id="Str_nit" readonly="readonly"/>
        </strong></td>
        <td colspan="2" id="fuente2"><?php $tipo=$row_usuario['tipo_usuario']; if($tipo=='1' || $tipo=='11') { ?><a href="javascript:eliminar_b('delete_bolsa',<?php echo $row_bolsa['N_cotizacion'];?>,'&delete_bolsa_ref',<?php echo $row_bolsa['N_referencia_c']; ?>,'cotizacion_general_bolsas_edit.php')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR COTIZACION"
title="ELIMINAR COTIZACION" border="0"><?php } ?></a><a href="egp_bolsa.php"><img src="images/a.gif" style="cursor:hand;" alt="EGP'S ACTIVAS" border="0" /></a><a href="egp_bolsa_obsoletos.php"><img src="images/i.gif" style="cursor:hand;" alt="EGP'S INACTIVAS" border="0" /></a><a href="egp_bolsa.php"><img src="images/opciones.gif" style="cursor:hand;" alt="MENU EGP'S" border="0" /></a><img src="images/ciclo1.gif" style="cursor:hand;" alt="RESTAURAR" border="0" onclick="window.history.go()" />
        </td>
      </tr>    
      <tr>
        <td colspan="2" id="titulo2">COTIZACION N&deg;</td>
        <td colspan="2" id="numero1"><strong>
          <input name="N_cotizacion" type="hidden" value="<?php $num=$row_cotizacion['N_cotizacion']+1; echo $num; ?>" />
          <?php echo $num; ?></strong></td>
      </tr>
      <tr>
        <td colspan="2" id="fuente1">Fecha  Ingreso</td>
        <td colspan="2" id="fuente1">Hora Ingreso</td>
      </tr>
      <tr>
        <td colspan="2" id="fuente1"><input name="fecha_b" type="text" id="fecha_b" value="<?php echo date("Y-m-d");  ?>" size="10" /></td>
        <td colspan="2" id="fuente1"><input name="hora_b" type="text" id="hora_b" value="<?php echo date("g:i a") ?>" size="10" readonly="true" /></td>
      </tr>
      <tr>
        <td colspan="2" id="fuente1">Estado de la Cotizaci&oacute;n</td>
        <td colspan="2" id="fuente1">Referencia</td>
      </tr>
      <tr>
        <td colspan="2" id="fuente1"><select name="B_estado" id="B_estado"><option value="1"<?php if (!(strcmp("1", $row_bolsa['B_estado']))) {echo "selected=\"selected\"";} ?>>Activo</option>
          <option value="0"<?php if (!(strcmp("0",$row_bolsa['B_estado']))) {echo "selected=\"selected\"";} ?>>Inactivo</option>
        </select></td>
        <td colspan="2" id="dato1"><select name="ref" id="ref" onblur="if(form1.ref.value) { consultagenerica3(); } else{ alert('Debe Seleccionar una REFERENCIA'); }">
          <option value="" <?php if (!(strcmp("", $_GET['id_ref']))) {echo "selected=\"selected\"";} ?>>Select</option>
          <?php
do {  
?>
          <option value="<?php echo $row_referencias['id_ref']?>"<?php if (!(strcmp($row_referencias['id_ref'], $_GET['id_ref']))) {echo "selected=\"selected\"";} ?>><?php echo $row_referencias['cod_ref']?></option>
          <?php
} while ($row_referencias = mysql_fetch_assoc($referencias));
  $rows = mysql_num_rows($referencias);
  if($rows > 0) {
      mysql_data_seek($referencias, 0);
	  $row_referencias = mysql_fetch_assoc($referencias);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td colspan="4" id="fuente2">Nombre del Cliente</td>
      </tr>
      <tr>
        <td colspan="4" id="fuente1"><select name="clientes" id="clientes"onchange="Javascript:document.form1.Str_nit.value=this.value">
          <option value=""></option>
          <?php
          do {  
		  ?>
          <option value="<?php echo $row_cliente['nit_c']?>"><?php echo $row_cliente['nombre_c']?></option>
          <?php
		  } while ($row_cliente = mysql_fetch_assoc($cliente));
 			 $rows = mysql_num_rows($cliente);
 			 if($rows > 0) {
     	     mysql_data_seek($cliente, 0);
	       $row_cliente = mysql_fetch_assoc($cliente);
  	      }
		  ?>
        </select></td>
        </tr>
      <tr>
        <td  id="cabezamenu"><ul id="menuhorizontal">
<li><a href="cotizacion_general_menu.php">Menu Cotizaciones</a></li>
</ul></td>
        <td id="cabezamenu"><ul id="menuhorizontal">
          <li><a href="perfil_cliente_add.php" target="_self">Crear Cliente</a></li>
</ul></td>
        <td colspan="2" id="fuente1">&nbsp;</td>
        </tr>
      <tr id="tr1">
        <td colspan="5" id="titulo2">BOLSAS PLASTICAS (Seguridad/Courrier/Normal)</td>
      </tr>
      <tr>
        <td colspan="5" id="fuente1">&nbsp;</td>     
      <tr id="tr1">
        <td colspan="5" id="titulo1">CARACTERISTICAS PRINCIPALES</td>

      
      <tr>
        <td id="fuente1">Ancho (cms)</td>
        <td colspan="2" id="fuente1">Alto (cms)</td>
        <td id="fuente1">Solapa (cms)</td>
        <td id="fuente1">Fuelle (cms)</td>
      </tr>
      <tr>
        <td id="dato1"><input name="N_ancho" type="text" id="N_ancho" value="<?php echo $row_bolsa['N_ancho']?>" size="3" /></td>
        <td colspan="2" id="dato1"><input name="N_alto" type="text" id="N_alto" value="<?php echo $row_bolsa['N_alto']?>" size="3" /></td>
        <td id="dato1">
          <input name="N_solapa" type="text" id="N_solapa" value="<?php echo $row_bolsa['N_solapa']?>" size="3" />
        </td>
        <td id="dato1"><input name="B_fuelle" type="text" id="B_fuelle" value="<?php echo $row_bolsa['B_fuelle']?>" size="3" /></td>
      </tr>
      <tr>
        <td id="fuente1">Calibre (micras)</td>
        <td colspan="2" id="fuente1">&nbsp;</td>
        <td id="fuente1"> Bolsillo canguro</td>
        <td id="fuente1">Tama&ntilde;o</td>
      </tr>
      <tr>
        <td id="dato1"><input name="N_calibre" type="text" id="N_calibre" value="<?php echo $row_bolsa['N_calibre']?>" size="3" /></td>
        <td colspan="2" id="dato1">&nbsp;</td>
        <td id="dato1"><select name="B_bolsillo" id="B_bolsillo"onBlur="mostrarBolsillo(this)">
          <option value="*"<?php if (!(strcmp("*", $row_bolsa['B_bolsillo']))) {echo "selected=\"selected\"";} ?>>*</option>
          <option value="1"<?php if (!(strcmp("1", $row_bolsa['B_bolsillo']))) {echo "selected=\"selected\"";} ?>>SI</option>
          <option value="0"<?php if (!(strcmp("0",$row_bolsa['B_bolsillo']))) {echo "selected=\"selected\"";} ?>>NO</option>
          </select></td>
        <td id="dato1"><input name="N_tamano_bolsillo" type="text" value="<?php echo $row_bolsa['N_tamano_bolsillo']?>" id="N_tamano_bolsillo" size="3"/></td>
      </tr>
      <tr>
        <td colspan="5" id="titulo3">&nbsp;</td>
      </tr>
      <tr id="tr1">
        <td colspan="5" id="titulo1">MATERIAL COEXTRUSION</td>
        </tr>
      <tr>
        <td id="fuente1">Material</td>
        <td colspan="2" id="fuente7">&nbsp;</td>
        <td colspan="2" id="fuente1">Color:</td>
      </tr>
      <tr>
        <td id="fuente1"><select name="Str_tipo_coextrusion" id="Str_tipo_coextrusion"onBlur="mostrarCapa(this)">
          <option>*</option>
          <option value="PIGMENTADO"<?php if (!(strcmp("PIGMENTADO", $row_bolsa['Str_tipo_coextrusion']))) {echo "selected=\"selected\"";} ?>>PIGMENTADO</option>
          <option value="NATURAL"<?php if (!(strcmp("NATURAL", $row_bolsa['Str_tipo_coextrusion']))) {echo "selected=\"selected\"";} ?>>NATURAL</option>
        </select></td>
        <td colspan="2" id="fuente3">Capa Externa:</td>
        <td colspan="2" id="fuente1"><select name="Str_capa_ext_coext" id="Str_capa_ext_coext">
          <option>*</option>
            <option value="BLANCO"<?php if (!(strcmp("BLANCO", $row_bolsa['Str_capa_ext_coext']))) {echo "selected=\"selected\"";} ?>>BLANCO</option>
            <option value="NEGRO"<?php if (!(strcmp("NEGRO", $row_bolsa['Str_capa_ext_coext']))) {echo "selected=\"selected\"";} ?>>NEGRO</option>
        </select>
        </td>
        </tr>
      <tr>
        <td id="fuente1">&nbsp;</td>
        <td colspan="2" id="fuente3">Capa Interna:</td>
        <td colspan="2" id="fuente1"><select name="Str_capa_inter_coext" id="Str_capa_inter_coext">
          <option>*</option>
          <option value="BLANCO"<?php if (!(strcmp("BLANCO", $row_bolsa['Str_capa_inter_coext']))) {echo "selected=\"selected\"";} ?>>BLANCO</option>
          <option value="NEGRO"<?php if (!(strcmp("NEGRO", $row_bolsa['Str_capa_inter_coext']))) {echo "selected=\"selected\"";} ?>>NEGRO</option>
        </select></td>
        </tr>
      <tr>
        <td id="fuente1">&nbsp;</td>
        <td colspan="2" id="fuente1">&nbsp;</td>
        <td colspan="2" id="fuente1">&nbsp;</td>
      </tr>
      <tr id="tr1">
        <td colspan="5" id="titulo1">IMPRESION</td>
      </tr>
      <tr>
        <td colspan="3" id="fuente1">Impresion:
          <select name="B_impresion" id="B_impresion"onBlur="mostrarColor(this)">
            <option value=""<?php if (!(strcmp("*", $row_bolsa['B_impresion']))) {echo "selected=\"selected\"";} ?>>*</option>
            <option value="1"<?php if (!(strcmp("1", $row_bolsa['B_impresion']))) {echo "selected=\"selected\"";} ?>>SI</option>
            <option value="0"<?php if (!(strcmp("0",$row_bolsa['B_impresion']))) {echo "selected=\"selected\"";} ?>>NO</option>
          </select>
          <select name="N_colores_impresion" id="N_colores_impresion">
            <option>Colores</option>
            <option value="1"<?php if (!(strcmp("1", $row_bolsa['N_colores_impresion']))) {echo "selected=\"selected\"";} ?>>1</option>
            <option value="2"<?php if (!(strcmp("2", $row_bolsa['N_colores_impresion']))) {echo "selected=\"selected\"";} ?>>2</option>
            <option value="3"<?php if (!(strcmp("3", $row_bolsa['N_colores_impresion']))) {echo "selected=\"selected\"";} ?>>3</option>
            <option value="4"<?php if (!(strcmp("4", $row_bolsa['N_colores_impresion']))) {echo "selected=\"selected\"";} ?>>4</option>
            <option value="5"<?php if (!(strcmp("5", $row_bolsa['N_colores_impresion']))) {echo "selected=\"selected\"";} ?>>5</option>
            <option value="6"<?php if (!(strcmp("6", $row_bolsa['N_colores_impresion']))) {echo "selected=\"selected\"";} ?>>6</option>
            <option value="7"<?php if (!(strcmp("7", $row_bolsa['N_colores_impresion']))) {echo "selected=\"selected\"";} ?>>7</option>
            <option value="8"<?php if (!(strcmp("8", $row_bolsa['N_colores_impresion']))) {echo "selected=\"selected\"";} ?>>8</option>
          </select></td>
        <td width="133" id="fuente1">Se Facturan Artes y Planchas ?</td>
        <td width="219" id="fuente1"><select name="B_cyreles" id="B_cyreles">
          <option value=""<?php if (!(strcmp("*", $row_bolsa['B_cyreles']))) {echo "selected=\"selected\"";} ?>>*</option>
          <option value="1"<?php if (!(strcmp("1", $row_bolsa['B_cyreles']))) {echo "selected=\"selected\"";} ?>>SI</option>
          <option value="0"<?php if (!(strcmp("0", $row_bolsa['B_cyreles']))) {echo "selected=\"selected\"";} ?>>NO</option>
        </select></td>
      </tr>
      <tr>
        <td id="fuente1">&nbsp;</td>
        <td colspan="2" id="fuente1">&nbsp;</td>
        <td colspan="2" id="fuente1">&nbsp;</td>
      </tr>
      <tr id="tr1">
        <td colspan="5" id="titulo1">SELLADO</td>
      </tr>
      <tr >
        <td colspan="5" id="titulo1">Tipo de Cierre Principal</td>
      </tr>
      <tr>
        <td id="fuente1">Cinta Seguridad</td>
        <td colspan="2" id="fuente1">
          <select name="B_sellado_seguridad" id="B_sellado_seguridad">
          <option value="0"<?php if (!(strcmp("0",$row_bolsa['B_sellado_seguridad']))) {echo "selected=\"selected\"";} ?>>NO</option>          
          <option value="1"<?php if (!(strcmp("1", $row_bolsa['B_sellado_seguridad']))) {echo "selected=\"selected\"";} ?>>SI</option>
          </select>
        </td>
        <td id="fuente1">Cinta Permanente</td>
        <td id="fuente1"><select name="B_sellado_permanente" id="B_sellado_permanente">
          <option value="0"<?php if (!(strcmp("0",$row_bolsa['B_sellado_permanente']))) {echo "selected=\"selected\"";} ?>>NO</option>
          <option value="1"<?php if (!(strcmp("1", $row_bolsa['B_sellado_permanente']))) {echo "selected=\"selected\"";} ?>>SI</option>
        </select></td>
      </tr>
      <tr>
        <td id="fuente1">Cinta Resellable</td>
        <td colspan="2" id="fuente1">
          <select name="B_sellado_resellable" id="B_sellado_resellable">
          <option value="0"<?php if (!(strcmp("0",$row_bolsa['B_sellado_resellable']))) {echo "selected=\"selected\"";} ?>>NO</option>
          <option value="1"<?php if (!(strcmp("1", $row_bolsa['B_sellado_resellable']))) {echo "selected=\"selected\"";} ?>>SI</option>
          </select>
        </td>
        <td id="fuente1">Hot Melt</td>
        <td id="fuente1">
          <select name="B_sellado_hotm" id="B_sellado_hotm">
          <option value="0"<?php if (!(strcmp("0",$row_bolsa['B_sellado_hotm']))) {echo "selected=\"selected\"";} ?>>NO</option>
          <option value="1"<?php if (!(strcmp("1", $row_bolsa['B_sellado_hotm']))) {echo "selected=\"selected\"";} ?>>SI</option>
          </select>
        </td>
      </tr>
      <tr>
        <td colspan="5" id="titulo1">Tipo de Sello</td>
      </tr>
      <tr>
        <td id="fuente1">Lateral</td>
        <td colspan="2" id="fuente1"><select name="Str_sellado_lateral" id="Str_sellado_lateral">
          <option></option>
          <option value="NO"<?php if (!(strcmp("NO", $row_bolsa['Str_sellado_lateral']))) {echo "selected=\"selected\"";} ?>>N/A</option>
          <option value="HILO"<?php if (!(strcmp("HILO", $row_bolsa['Str_sellado_lateral']))) {echo "selected=\"selected\"";} ?>>HILO</option>
          <option value="PLANO"<?php if (!(strcmp("PLANO", $row_bolsa['Str_sellado_lateral']))) {echo "selected=\"selected\"";} ?>>PLANO</option>
          <option value="HILO/PLANO"<?php if (!(strcmp("HILO/PLANO", $row_bolsa['Str_sellado_lateral']))) {echo "selected=\"selected\"";} ?>>HILO/PLANO</option>
          </select></td>
        <td id="fuente1">Fondo</td>
        <td id="fuente1"><select name="B_fondo" id="B_fondo">
          <option value="0"<?php if (!(strcmp("0",$row_bolsa['B_fondo']))) {echo "selected=\"selected\"";} ?>>NO</option>
          <option value="1"<?php if (!(strcmp("1", $row_bolsa['B_fondo']))) {echo "selected=\"selected\"";} ?>>SI</option>
          
        </select></td>
      </tr>
      <tr>
        <td id="fuente1">Numeracion</td>
        <td colspan="2" id="fuente1">Troquel/Precorte</td>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
      </tr>
      <tr>
        <td id="fuente1"><select name="B_numeracion" id="B_numeracion">
          <option value="0"<?php if (!(strcmp("0",$row_bolsa['B_numeracion']))) {echo "selected=\"selected\"";} ?>>NO</option>
          <option value="1"<?php if (!(strcmp("1", $row_bolsa['B_numeracion']))) {echo "selected=\"selected\"";} ?>>SI</option>
        </select></td>
        <td colspan="2" id="fuente1"><select name="B_troquel" id="B_troquel">
          <option value="0"<?php if (!(strcmp("0",$row_bolsa['B_troquel']))) {echo "selected=\"selected\"";} ?>>NO</option>
          <option value="1"<?php if (!(strcmp("1", $row_bolsa['B_troquel']))) {echo "selected=\"selected\"";} ?>>SI</option>
          
        </select></td>
        <td id="fuente1">Codigo de Barras</td>
        <td id="fuente1"><select name="B_codigo_b" id="B_codigo_b">
          <option value="0"<?php if (!(strcmp("0",$row_bolsa['B_codigo_b']))) {echo "selected=\"selected\"";} ?>>NO</option>
          <option value="1"<?php if (!(strcmp("1", $row_bolsa['B_codigo_b']))) {echo "selected=\"selected\"";} ?>>SI</option>
        </select></td>
      </tr>
      <tr>
        <td colspan="5" id="fuente1">&nbsp;</td>
      </tr>
      <tr id="tr1">
        <td colspan="5" id="titulo1">PRECIO Y CONDICIONES COMERCIALES</td>
      </tr>
      <tr>
        <td colspan="2" id="fuente1">Moneda / Precio </td>
        <td id="fuente1">Unidad</td>
        <td id="fuente1">Plazo de pago</td>
        <td id="fuente1"> Cantidad Solicitada</td>
      </tr>
      <tr>
        <td colspan="2" id="fuente5"><select name="Str_moneda" id="Str_moneda">
          <option value="COL$"<?php if (!(strcmp("COL$", $row_bolsa['Str_moneda']))) {echo "selected=\"selected\"";} ?>>COL$</option>
          <option value="USD$"<?php if (!(strcmp("USD$", $row_bolsa['Str_moneda']))) {echo "selected=\"selected\"";} ?>>USD$</option>
          <option value="EUR&euro;"<?php if (!(strcmp("EUR&euro;", $row_bolsa['Str_moneda']))) {echo "selected=\"selected\"";} ?>>EUR&euro;</option>
          </select>          <input name="N_precio"  id="N_precio" type="number" style="width: 30 px" min="0" step="0.01" size="15" value="<?php echo $row_bolsa['N_precio']?>"/></td>
        <td id="dato1"><select name="Str_unidad_vta" id="Str_unidad_vta">
          <option>*</option>
          <option value="PRECIO UNITARIO"<?php if (!(strcmp("PRECIO UNITARIO", $row_bolsa['Str_unidad_vta']))) {echo "selected=\"selected\"";} ?>>PRECIO UNITARIO</option>
          <option value="PRECIO MILLAR"<?php if (!(strcmp("PRECIO MILLAR", $row_bolsa['Str_unidad_vta']))) {echo "selected=\"selected\"";} ?>>PRECIO MILLAR</option>
          <option value="PRECIO PAQUETE"<?php if (!(strcmp("PRECIO PAQUETE", $row_bolsa['Str_unidad_vta']))) {echo "selected=\"selected\"";} ?>>PRECIO PAQUETE</option>
          <option value="KILO"<?php if (!(strcmp("KILO", $row_bolsa['Str_unidad_vta']))) {echo "selected=\"selected\"";} ?>>PRECIO PAQUETE</option>
        </select></td>
        <td id="fuente5"><select name="Str_plazo" id="Str_plazo">
          <option>*</option>
          <option value="ANTICIPADO"<?php if (!(strcmp("ANTICIPADO", $row_bolsa['Str_plazo']))) {echo "selected=\"selected\"";} ?>>Anticipado</option>
          <option value="PAGO DE CONTADO"<?php if (!(strcmp("PAGO DE CONTADO", $row_bolsa['Str_plazo']))) {echo "selected=\"selected\"";} ?>>Pago de Contado</option>
          <option value="PAGO A 15 DIAS"<?php if (!(strcmp("PAGO A 15 DIAS", $row_bolsa['Str_plazo']))) {echo "selected=\"selected\"";} ?>>Pago a 15 Dias</option>
          <option value="PAGO A 30 DIAS"<?php if (!(strcmp("PAGO A 30 DIAS", $row_bolsa['Str_plazo']))) {echo "selected=\"selected\"";} ?>>Pago a 30 Dias</option>
          <option value="PAGO A 45 DIAS"<?php if (!(strcmp("PAGO A 45 DIAS", $row_bolsa['Str_plazo']))) {echo "selected=\"selected\"";} ?>>Pago a 45 Dias</option>
          <option value="PAGO A 60 DIAS"<?php if (!(strcmp("PAGO A 60 DIAS", $row_bolsa['Str_plazo']))) {echo "selected=\"selected\"";} ?>>Pago a 60 Dias</option>
          <option value="PAGO A 90 DIAS"<?php if (!(strcmp("PAGO A 90 DIAS", $row_bolsa['Str_plazo']))) {echo "selected=\"selected\"";} ?>>Pago a 90 Dias</option>
        </select></td>
        <td id="fuente1">
          <input name="N_cant_impresion" type="text" id="N_cant_impresion" value="<?php echo $row_bolsa['N_cant_impresion'];?>" size="10" onKeyUp="puntos(this,this.value.charAt(this.value.length-1))"/>
       </td>
      </tr>
      <tr>
        <td id="fuente1">Incoterms:</td>
        <td id="fuente1">&nbsp;</td>
        <td colspan="2" id="fuente1">Vendedor</td>
        <td id="fuente1">Comision</td>
      </tr>
      <tr>
        <td colspan="2" id="fuente1">
          <select name="Str_incoterms" id="Str_incoterms">
            <option >*</option>
            <option value="EXW"<?php if (!(strcmp("EXW", $row_bolsa['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>EXW</option>
            <option value="FCA"<?php if (!(strcmp("FCA", $row_bolsa['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>FCA</option>
            <option value="FAS"<?php if (!(strcmp("FAS", $row_bolsa['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>FAS</option>
            <option value="FOB"<?php if (!(strcmp("FOB", $row_bolsa['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>FOB</option>
            <option value="CFR"<?php if (!(strcmp("CFR", $row_bolsa['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>CFR</option>
            <option value="CIF"<?php if (!(strcmp("CIF", $row_bolsa['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>CIF</option>
            <option value="CPT"<?php if (!(strcmp("CPT", $row_bolsa['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>CPT</option>
            <option value="CIP"<?php if (!(strcmp("CIP", $row_bolsa['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>CIP</option>
            <option value="DAF"<?php if (!(strcmp("DAF", $row_bolsa['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>DAF</option>
            <option value="DES"<?php if (!(strcmp("DES", $row_bolsa['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>DES</option>
            <option value="DEQ"<?php if (!(strcmp("DEQ", $row_bolsa['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>DEQ</option>
            <option value="DDU"<?php if (!(strcmp("DDU", $row_bolsa['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>DDU</option>
            <option value="DDP"<?php if (!(strcmp("DDP", $row_bolsa['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>DDP</option>
            </select> <a href="javascript:verFoto('archivosc/CuadroIncoterms.pdf','610','490')" >Ver Cuadro</a>
</td>
        <td colspan="2" id="fuente1"><select name="vendedor" id="vendedor">
          <option value="" <?php if (!(strcmp("", $row_bolsa['Str_usuario']))) {echo "selected=\"selected\"";} ?>>Seleccione</option>
          <?php
do {  
?>
          <option value="<?php echo $row_vendedores['id_vendedor']?>"<?php if (!(strcmp($row_vendedores['id_vendedor'], $row_bolsa['Str_usuario']))) {echo "selected=\"selected\"";} ?>><?php echo $row_vendedores['nombre_vendedor']?></option>
          <?php
} while ($row_vendedores = mysql_fetch_assoc($vendedores));
  $rows = mysql_num_rows($vendedores);
  if($rows > 0) {
      mysql_data_seek($vendedores, 0);
	  $row_vendedores = mysql_fetch_assoc($vendedores);
  }
?>
        </select></td>
        <td id="fuente1"><input name="N_comision" type="text" id="N_comision" size="2" maxlength="1" onkeyup="return ValNumero(this)" value="<?php echo $row_bolsa['N_comision']?>"/>
          <strong>%</strong></td>
        </tr>
      <tr>
        <td colspan="5" id="fuente1">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="5" id="fuente1">Uso de la Bolsa Observaciones:</td>
      </tr>
      <tr>
        <td colspan="5" id="dato1"><textarea name="nota_b" cols="78" rows="2" id="nota_b"onKeyUp="conMayusculas(this)"><?php echo $_GET['texto'] ?></textarea></td>
      </tr>
      <tr>
        <td colspan="5" id="dato1">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="5" id="fuente2"><input type="hidden" name="Str_tipo" id="Str_tipo" value="BOLSA" />
          <input name="responsable_modificacion" type="hidden" value="" />
          <input name="fecha_modificacion" type="hidden" value="<?php echo date("Y-m-d");?>" />
          <input name="N_referencia" type="hidden" value="<?php echo $row_bolsa['cod_ref'];?>" />
          <input name="hora_modificacion" type="hidden" value="" />
          <input name="tipo_usuario" type="hidden" value="<?php echo $row_usuario['tipo_usuario']; ?>" />
          <input name="valor" type="hidden" value="1" />
        <input name="submit" type="submit"value="COPIAR COTIZACION BOLSA" /></td>
      </tr>
    </table>
    <input type="hidden" name="MM_insert" value="form1">
  </form></td>
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
mysql_free_result($cliente);
mysql_free_result($vendedores);
mysql_free_result($cotizacion);
mysql_free_result($obs);
?>
