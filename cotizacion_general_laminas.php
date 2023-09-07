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
include('rud_cotizaciones/rud_cotizacion_lamina.php');//SISTEMA RUW PRA LA BASE DE DATOS 
$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

/*mysql_select_db($database_conexion1, $conexion1);
$query_egp = "SELECT * FROM egp ORDER BY n_egp DESC";
$egp = mysql_query($query_egp, $conexion1) or die(mysql_error());
$row_egp = mysql_fetch_assoc($egp);
$totalRows_egp = mysql_num_rows($egp);*/

mysql_select_db($database_conexion1, $conexion1);
$query_cotizacion = "SELECT * FROM Tbl_cliente_referencia ORDER BY N_cotizacion DESC";
$cotizacion = mysql_query($query_cotizacion, $conexion1) or die(mysql_error());
$row_cotizacion = mysql_fetch_assoc($cotizacion);
$totalRows_cotizacion = mysql_num_rows($cotizacion);

mysql_select_db($database_conexion1, $conexion1);
$query_cliente = "SELECT * FROM cliente ORDER BY nombre_c ASC";
$cliente = mysql_query($query_cliente, $conexion1) or die(mysql_error());
$row_cliente = mysql_fetch_assoc($cliente);
$totalRows_cliente = mysql_num_rows($cliente);

mysql_select_db($database_conexion1, $conexion1);
$query_vendedores = "SELECT * FROM vendedor ORDER BY nombre_vendedor ASC";
$vendedores = mysql_query($query_vendedores, $conexion1) or die(mysql_error());
$row_vendedores = mysql_fetch_assoc($vendedores);
$totalRows_vendedores = mysql_num_rows($vendedores);

mysql_select_db($database_conexion1, $conexion1);
$query_ref= "SELECT * FROM Tbl_cliente_referencia ORDER BY N_referencia DESC";
$ref = mysql_query($query_ref, $conexion1) or die(mysql_error());
$row_ref = mysql_fetch_assoc($ref);
$totalRows_ref = mysql_num_rows($ref);

$colname_archivos = "-1";
if (isset($_GET['n_egl'])) {
  $colname_archivos = $_GET['n_egl'];
}
mysql_select_db($database_conexion1, $conexion1);
$query_archivos = sprintf("SELECT * FROM egl_archivos WHERE n_egl = %s",$colname_archivos);
$archivos = mysql_query($query_archivos, $conexion1) or die(mysql_error());
$row_archivos = mysql_fetch_assoc($archivos);
$totalRows_archivos = mysql_num_rows($archivos);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/adjuntos.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
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
  <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" onsubmit="MM_validateForm('Str_nit','','R','N_ancho_l','','RisNum','N_repeticion_l','','RisNum','N_calibre_l','','RisNum','N_diametro_max_l','','RisNum','N_peso_max','','RisNum','N_cantidad_metros_r_l','','RisNum','N_precio_k','','RisNum','N_cantidad_l','','RisNum');return document.MM_returnValue"><table id="tabla2">
      <tr id="tr1">
        <td width="114" nowrap="nowrap" id="codigo">CODIGO : R1-F08 </td>
        <td colspan="2" nowrap="nowrap" id="titulo2">Cotizacion Laminas</td>
        <td colspan="2" nowrap="nowrap" id="codigo">VERSION: 2 </td>
      </tr>
      <tr>
        <td rowspan="9" id="fuente2"><img src="images/logoacyc.jpg"></td>
        <td colspan="2" id="numero2"><strong>NIT N&deg; 
        <input type="text" name="Str_nit" id="Str_nit"readonly="readonly"/>
        </strong></td>
        <td colspan="2" id="fuente2"><a href="egp_bolsa.php"><img src="images/a.gif" style="cursor:hand;" alt="EGP'S ACTIVAS" border="0" /></a><a href="egp_bolsa_obsoletos.php"><img src="images/i.gif" style="cursor:hand;" alt="EGP'S INACTIVAS" border="0" /></a><a href="egp_bolsa.php"><img src="images/opciones.gif" style="cursor:hand;" alt="MENU EGP'S" border="0" /></a><img src="images/ciclo1.gif" style="cursor:hand;" alt="RESTAURAR" border="0" onclick="window.history.go()" /></td>
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
        <td colspan="2" id="fuente1"><input name="fecha_l" type="text" id="fecha_l" value="<?php echo date("Y-m-d"); ?>" size="10" /></td>
        <td colspan="2" id="fuente1"><input name="hora_l" type="text" id="hora_l" value="<?php echo date("g:i a") ?>" size="10" readonly="true" /></td>
      </tr>
      <tr>
        <td colspan="2" id="fuente1">Ingresado por</td>
        <td colspan="2" id="fuente1">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" id="fuente1"><input name="Str_usuario" type="text" id="Str_usuario" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="20" readonly="true" /></td>
        <td colspan="2" id="dato4">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="4" id="fuente2">Nombre del Cliente</td>
      </tr>
      <tr>
        <td colspan="4" id="fuente1"><select name="clientes" id="clientes"onchange="Javascript:document.form1.Str_nit.value=this.value">
          <option value="">*</option>
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
      <td id="cabezamenu"><ul id="menuhorizontal">
<li><a href="cotizacion_general_menu.php">Menu Cotizaciones</a></li>
</ul>
</td>
      <td id="cabezamenu"><ul id="menuhorizontal">
<li><a href="perfil_cliente_add.php" target="_self">Crear Cliente</a></li>
</ul></td>
        </tr>      
      <tr id="tr1">
        <td colspan="5" id="titulo2">LAMINAS</td>
      </tr>
      <tr id="tr1">
        <td colspan="5" id="titulo1">CARACTERISTICAS PRINCIPALES</td>
        </tr>
      <tr>
        <td id="fuente1">&nbsp;</td>
        <td colspan="4" id="fuente2">&nbsp;</td>
        </tr>
      <tr>
        <td id="fuente1">Ancho (cms)</td>
        <td colspan="2" id="fuente1">Repeticion (cms)</td>
        <td colspan="2" id="fuente1">Calibre  &nbsp;(mills) </td>
      </tr>
      <tr>
        <td id="dato1"><input name="N_ancho_l" type="text" id="N_ancho_l" value="" size="3" /></td>
        <td colspan="2" id="dato1"><input name="N_repeticion_l" type="text" id="N_repeticion_l" value="" size="3" /></td>
        <td colspan="2" id="dato1"><input name="N_calibre_l" type="text" id="N_calibre_l" value="" size="3" /></td>
      </tr>
      <tr>
        <td id="fuente1">&nbsp;</td>
        <td colspan="2" id="fuente1">&nbsp;</td>
        <td colspan="2" id="fuente1">&nbsp;</td>
      </tr>
      <tr id="tr1">
        <td colspan="5" id="titulo1">MATERIAL COEXTRUSION</td>
      </tr>      <tr>
        <td id="fuente1">&nbsp;</td>
        <td colspan="2" id="fuente7">&nbsp;</td>
        <td colspan="2" id="fuente1">Color:</td>
        </tr>
      <tr>
        <td id="fuente1">Material</td>
        <td colspan="2" id="fuente3">Capa Externa:</td>
        <td colspan="2" id="fuente1">
          <select name="Str_capa_ext_coext" id="Str_capa_ext_coext">
            <option>*</option>
            <option value="BLANCO">BLANCO</option>
            <option value="NEGRO">NEGRO</option>
            </select>
        </td>
        </tr>
      <tr>
        <td id="fuente1"><select name="Str_tipo_coextrusion" id="Str_tipo_coextrusion"onclick="mostrarCapa(this)">
          <option>*</option>
          <option value="PIGMENTADO">PIGMENTADO</option>
          <option value="NATURAL">NATURAL</option>
        </select></td>
        <td colspan="2" id="fuente3">Capa Interna:</td>
        <td colspan="2" id="fuente1"><select name="Str_capa_inter_coext" id="Str_capa_inter_coext">
          <option>*</option>
          <option value="BLANCO">BLANCO</option>
          <option value="NEGRO">NEGRO</option>
        </select></td>
      </tr>
      <tr>
        <td colspan="5" id="fuente1">&nbsp;</td>
      </tr>
      <tr id="tr1">
        <td colspan="5" id="titulo1">IMPRESION</td>
      </tr>
      <tr>
        <td colspan="3" id="fuente1">Impresion:          
          <select name="B_impresion" id="B_impresion" tabindex="1\" onclick="mostrarColor(this)"tag>
            <option value="2">*</option>
            <option value="1">SI</option>
            <option value="0">NO</option>
          </select>
          <select name="N_colores_impresion" id="N_colores_impresion"tag tabindex="1\">
            <option>Colores</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
          </select></td>
        <td colspan="2" id="fuente1"> Se Facturan Artes y Planchas ?          
          <select name="B_cyreles" id="B_cyreles"onclick="mostrarColor(this)">
            <option value="2">*</option>
            <option value="1">SI</option>
            <option value="0">NO</option>
          </select></td>
      </tr>
      <tr>
        <td colspan="5" id="fuente1">&nbsp;</td>
      </tr>
      <tr id="tr1">
        <td colspan="5" id="titulo1">PRESENTACION</td>
      <tr>
          <td colspan="2" id="fuente1">Diametro Maximo x Rollo (cms)</td>
          <td width="141" id="fuente1">Peso Maximo x Rollo(kgr)</td>
          <td width="133" id="fuente1">Cant. Metros x Rollo</td>
          <td width="203" id="fuente1">Tipo de Embobinado</td>
        </tr>
        <tr>
          <td colspan="2" id="fuente1"><input name="N_diametro_max_l" type="text" id="N_diametro_max_l" size="15" /></td>
          <td id="dato1"><input name="N_peso_max" type="text" id="N_peso_max" size="15" /></td>
          <td id="fuente1"><input name="N_cantidad_metros_r_l" type="text" id="N_cantidad_metros_r_l" size="15" /></td>
          <td id="fuente1">
          <?php $_GET['embobinado_egl']?>
          <select name="N_embobinado" id="N_embobinado">
            <option value="<?php echo $_GET['embobinado_egl']?>">*</option>
          </select>
          <a href="javascript:verFoto('embobinado_lamina.php','610','490')" >Ver Cuadro</a></td>
        </tr>
        <tr>
          <td colspan="4" id="fuente1"></td>
<?php switch($row_vista_egl['embobinado_egl']) {
	  case 0: echo "VACIO"; break;
	  case 1: ?><img src="images/embobinado1.gif"><?php break;
	  case 2: ?><img src="images/embobinado2.gif"><?php break;
	  case 3: ?><img src="images/embobinado3.gif"><?php break;
	  case 4: ?><img src="images/embobinado4.gif"><?php break;
	  case 5: ?><img src="images/embobinado5.gif"><?php break;
	  case 6: ?><img src="images/embobinado6.gif"><?php break;
	  case 7: ?><img src="images/embobinado7.gif"><?php break;
	  case 8: ?><img src="images/embobinado8.gif"><?php break;
	  case 9: ?><img src="images/embobinado9.gif"><?php break;
	  case 10: ?><img src="images/embobinado10.gif"><?php break;
	  case 11: ?><img src="images/embobinado11.gif"><?php break;
	  case 12: ?><img src="images/embobinado12.gif"><?php break;
	  case 13: ?><img src="images/embobinado13.gif"><?php break;
	  case 14: ?><img src="images/embobinado14.gif"><?php break;
	  case 15: ?><img src="images/embobinado15.gif"><?php break;
	  case 16: ?><img src="images/embobinado16.gif"><?php break;
	  } ?>          <td width="10" id="fuente1">
          
          </td>
        </tr>
        <tr id="tr1">
          <td colspan="5" id="titulo1">PRECIO Y CONDICIONES COMERCIALES</td>
        <tr>
          <td colspan="2" id="fuente1"> Moneda / Precio x Kilo</td>
          <td colspan="2" id="fuente1">Plazo de pago</td>
          <td id="fuente1">Cantidad Solicitada (Kilos)</td>
        </tr>
        <tr>
          <td colspan="2" id="fuente1"><select name="Str_moneda_l" id="B_moneda_l">
            <option value="PESOS">$</option>
            <option value="DOLAR">USD</option>
            <option value="EUROS">&euro;</option>
          </select>
            <input name="N_precio_k" type="text" id="N_precio_k" size="15" /></td>
          <td colspan="2" id="dato1"><select name="Str_plazo" id="Str_plazo">
            <option>*</option>
            <option value="ANTICIPADO">Anticipado</option>
            <option value="PAGO DE CONTADO">Pago de Contado</option>
            <option value="PAGO A 15 DIAS">Pago a 15 Dias</option>
            <option value="PAGO A 30 DIAS">Pago a 30 Dias</option>
            <option value="PAGO A 45 DIAS">Pago a 45 Dias</option>
            <option value="PAGO A 60 DIAS">Pago a 60 Dias</option>
            <option value="PAGO A 90 DIAS">Pago a 90 Dias</option>           
          </select></td>
          <td id="fuente1"><input name="N_cantidad_l" type="text" id="N_cantidad_l" size="15" /></td>
        </tr>
        <tr>
          <td colspan="5" id="fuente1">Incoterms: </td>
        </tr>
        <tr>
          <td colspan="5" id="fuente1"><select name="Str_incoterms_l" id="incoterms2">
            <option value="">*</option>
            <option value="EXW">EXW</option>
            <option value="FCA">FCA</option>
            <option value="FAS">FAS</option>
            <option value="FOB">FOB</option>
            <option value="CFR">CFR</option>
            <option value="CIF">CIF</option>
            <option value="CPT">CPT</option>
            <option value="CIP">CIP</option>
            <option value="DAF">DAF</option>
            <option value="DES">DES</option>
            <option value="DEQ">DEQ</option>
            <option value="DDU">DDU</option>
            <option value="DDP">DDP</option>
          </select>
            <a href="javascript:verFoto('archivosc/CuadroIncoterms.pdf','610','490')" >Ver Cuadro</a></td>
        <tr>
          <td colspan="5" id="fuente1"></td>
      <tr>
        <td colspan="5" id="fuente1"> Observaciones:</td>
      </tr>
      <tr>
        <td colspan="5" id="dato1"><textarea name="nota_l" cols="78" rows="2" id="nota_l"onKeyUp="conMayusculas(this)" onClick="this.value = ''">*</textarea></td>
      </tr>
      <tr>
        <td colspan="5" id="dato1">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="5" id="fuente2"><input name="responsable_modificacion" type="hidden" value="" />
          <input name="fecha_modificacion" type="hidden" value="" />
          <input name="hora_modificacion" type="hidden" value="" />
          <input name="N_referencia" type="hidden" value="<?php echo $row_ref['N_referencia']+1;?>" />
          <input name="tipo_usuario" type="hidden" value="<?php echo $row_usuario['tipo_usuario']; ?>" />
          <input name="valor" type="hidden" value="1" />
        <input name="submit" type="submit" value="COTIZAR" /></td>
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
mysql_free_result($ref);
?>
