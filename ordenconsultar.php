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
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
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
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE orden_produccion SET cerrada=%s WHERE id=%s",
                       GetSQLValueString($_POST['cerrar'], "int"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE orden_produccion SET cerrada=%s WHERE id=%s",
                       GetSQLValueString(1, "int"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());
$ruta="ordenconsultar.php?variables=".$_POST['id'];
  $updateGoTo = $ruta;
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = $_SESSION['MM_Username'];
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = %s", GetSQLValueString($colname_usuario, "text"));
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

mysql_select_db($database_conexion1, $conexion1);
$query_cerrar = "SELECT id, cerrada FROM orden_produccion WHERE id = id";
$cerrar = mysql_query($query_cerrar, $conexion1) or die(mysql_error());
$row_cerrar = mysql_fetch_assoc($cerrar);
$totalRows_cerrar = mysql_num_rows($cerrar);

mysql_select_db($database_conexion1, $conexion1);
$query_actuales = "SELECT     orden_produccion.id, 
	   orden_produccion.fecha_pedido, 
	   orden_produccion.fecha_entrega, 
	   orden_produccion.numero_orden_compra, 
	   orden_produccion.referencia_cliente, 
	   referencia.cod_ref, 
	   referencia.version_ref, 
	   cliente.nombre_c, 
	   cliente.id_c, 
	   usuario.nombre_usuario AS vendedor, 
	   orden_produccion.cantidad, 
	   orden_produccion.precio_venta, 
	   orden_produccion.planchas_impresion, 
	   orden_produccion.referencia_nueva, 
	   orden_produccion.orden_produccion, 
	   orden_produccion.f_coextruccion     , 
	   orden_produccion.f_impresion     , 
	   orden_produccion.f_sellada     , 
	   orden_produccion.f_despacho     , 
	   orden_produccion.comision     , 
	   orden_produccion.notas     , 
	   orden_produccion.direccion_despacho 
	   FROM     
	   acycia_intranet.orden_produccion     
	   INNER JOIN acycia_intranet.cliente          ON (orden_produccion.cliente = cliente.id_c)     
	   INNER JOIN acycia_intranet.usuario          ON (orden_produccion.vendedor = usuario.id_usuario) 
	   INNER JOIN acycia_intranet.referencia          ON (orden_produccion.referencia_interna = referencia.id_ref) WHERE orden_produccion.cerrada = 0 ";
$actuales = mysql_query($query_actuales, $conexion1) or die(mysql_error());
$row_actuales = mysql_fetch_assoc($actuales);
$totalRows_actuales = mysql_num_rows($actuales);

mysql_select_db($database_conexion1, $conexion1);
$query_actuales2 = "SELECT     orden_produccion.id, 
	   orden_produccion.fecha_pedido, 
	   orden_produccion.fecha_entrega, 
	   orden_produccion.numero_orden_compra, 
	   orden_produccion.referencia_cliente, 
	   referencia.cod_ref, 
	   referencia.version_ref, 
	   cliente.nombre_c, 
	   cliente.id_c, 
	   usuario.nombre_usuario AS vendedor, 
	   orden_produccion.cantidad, 
	   orden_produccion.precio_venta, 
	   orden_produccion.planchas_impresion, 
	   orden_produccion.referencia_nueva, 
	   orden_produccion.orden_produccion, 
	   orden_produccion.f_coextruccion     , 
	   orden_produccion.f_impresion     , 
	   orden_produccion.f_sellada     , 
	   orden_produccion.f_despacho     , 
	   orden_produccion.comision     , 
	   orden_produccion.notas     , 
	   orden_produccion.direccion_despacho 
	   FROM     
	   acycia_intranet.orden_produccion     
	   INNER JOIN acycia_intranet.cliente          ON (orden_produccion.cliente = cliente.id_c)     
	   INNER JOIN acycia_intranet.usuario          ON (orden_produccion.vendedor = usuario.id_usuario) 
	   INNER JOIN acycia_intranet.referencia          ON (orden_produccion.referencia_interna = referencia.id_ref) WHERE orden_produccion.cerrada = 1";
$actuales2 = mysql_query($query_actuales2, $conexion1) or die(mysql_error());
$row_actuales2 = mysql_fetch_assoc($actuales2);
$totalRows_actuales2 = mysql_num_rows($actuales2);


?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link rel="StyleSheet" href="css/general.css" type="text/css">
<script src="tabla/js/jquery.js" type="text/javascript"></script>
<script src="tabla/js/jquery.tablesorter.js" type="text/javascript"></script>
<script src="SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<script language="javascript" type="application/javascript"> 
$(document).ready(function() 
    { 
        $("#miTabla").tablesorter(); 
    } 
); 
</script>
<style type="text/css">
.cabeceratabla{
	background: #999;
	padding-top:5px;
	padding-bottom:5px;
	font: bold 13px Verdana, Arial, Helvetica, sans-serif;
	color: #15428b;
	text-align:center;
	border:1px solid #99bbe8;
	width:95%;
	margin-right: auto;
	margin-left: auto;
}
table#miTabla, table#miTabla2 {
	table-layout: fixed;
	background-color: white;
	font: 11px Verdana, Arial, Helvetica, sans-serif;
	color: #777;
	width:95%;
}
table#miTabla tbody td, table#miTabla2 tbody td  {
	padding: 2px;
	padding-top:4px;
	padding-bottom:4px;
	text-align: left;
	border-bottom: 1px solid #e2e2e2;
	text-overflow: ellipsis;
	overflow: hidden;
	white-space: nowrap;
}
	
table#miTabla th, table#miTabla2 th  {
	padding: 2px;
	padding-top:4px;
	padding-bottom:4px;
	border-bottom: 1px solid #d0d0d0;
	border-left: 1px solid #d0d0d0;
	text-align: left;
	overflow: hidden;
	color:#222;
	background-image: url(Imagenes/bgthgrid.gif);
	font: 11px Verdana, Arial, Helvetica, sans-serif;
	cursor:pointer;
}
</style>
<link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
</script>
</head>
<body oncontextmenu="return false">
<table id="tabla_formato"><tr><td>
    <div id="cabecera_formato"><div class="menu_formato"><ul>  
       <li><?php echo $row_usuario['nombre_usuario']; ?></li>
       <li><a href="ordeningresar.php" target="_top">ADD ORDEN</a></li>       
       <li><a href="Ordenpedido.php" target="_top">ORDENES</a></li>
       <li><a href="menu.php" target="_top">MENU</a></li>
       <li><a href="<?php echo $logoutAction ?>" target="_top">SALIR</a></li>  
      </ul></div></div>
   </td></tr></table>
  <div id="TabbedPanels1" class="TabbedPanels">
    <ul class="TabbedPanelsTabGroup">
      <li class="TabbedPanelsTab" tabindex="0">Ordenes Actuales</li>
      <li class="TabbedPanelsTab" tabindex="0">Ordenes Finalizadas</li>
    </ul>
    <div class="TabbedPanelsContentGroup">
      <div class="TabbedPanelsContent">
        <table id="miTabla" >
          <thead>
            <tr>
              <th>ID</th>
              <th>OP</th>
              <th>Pedido</th>
              <th>Entrega</th>
              <th>OC</th>
              <th>REF</th>
              <th>Cliente</th>
              <th>cantidad</th>
              <th>Coextruccion</th>
              <th>Impresion</th>
              <th>Sellada</th>
              <th>Despacho</th>
              <th>notas</th>
              <th>editar</th>
              <th>borrar</th>
             <th>Finalizar</th>
            </tr>
          </thead>
          <tbody>
            <!--Loop start, you could use a repeat region here-->
            <?php do { ?>
            <tr>
              <td><a href="#" onClick="MM_openBrWindow('Ordenpedido_detalle.php?id=<?php echo $row_actuales['id']; ?>','','scrollbars=yes,width=900,height=400')"><?php echo $row_actuales['id']; ?></a></td>
              <td><a href="#" onClick="MM_openBrWindow('ordeningresar_ordenproduccion.php?id=<?php echo $row_actuales['id']; ?>','','scrollbars=yes,width=400,height=200')">
                <?php if( $row_actuales['orden_produccion']==""){ ?>
                registar
                <?php }else{ ?>
                <a href="#" onClick="MM_openBrWindow('Ordenpedido_detalle.php?id=<?php echo $row_actuales['id']; ?>','','scrollbars=yes,width=900,height=600')"><? echo $row_actuales['orden_produccion'];}; ?></a></td>
              <td><?php echo $row_actuales['fecha_pedido']; ?></td>
              <td><?php echo $row_actuales['fecha_entrega']; ?></td>
              <td><?php echo $row_actuales['numero_orden_compra']; ?></td>
              <td><?php echo $row_actuales['cod_ref']; ?>-<?php echo $row_actuales['version_ref']; ?></td>
              <td><a href="perfil_cliente_vista.php?id_c= <?php echo $row_actuales['id_c']; ?>&tipo_usuario=1" target="_blank"><?php echo $row_actuales['nombre_c']; ?></a></td>
              <td><?php echo number_format($row_actuales['cantidad']) ; ?></td>
              <td><?php if( $row_actuales['f_coextruccion'] ==""){?>
                <a href="#" onClick="MM_openBrWindow('ordeningresar_fecha.php?tipo=1&id=<?php echo $row_actuales['id']; ?>','','width=400,height=200')">Registrar</a>
              <?php }else{echo $row_actuales['f_coextruccion'];} ?></td>
              <td><?php if( $row_actuales['f_impresion']==""){?>
                <a href="#" onClick="MM_openBrWindow('ordeningresar_fecha.php?tipo=2&id=<?php echo $row_actuales['id']; ?>','','width=400,height=200')">Registrar</a>
                <?php }else{echo $row_actuales['f_impresion'];} ?></td>
              <td><?php if( $row_actuales['f_sellada']==""){?>
                <a href="#" onClick="MM_openBrWindow('ordeningresar_fecha.php?tipo=3&id=<?php echo $row_actuales['id']; ?>','','width=400,height=200')">Registrar</a>
              <?php }else{echo $row_actuales['f_sellada'];}?></td>
              <td><?php if( $row_actuales['f_despacho']==""){?>
                <a href="#" onClick="MM_openBrWindow('ordeningresar_fecha.php?tipo=4&id=<?php echo $row_actuales['id']; ?>','','width=400,height=200')">Registrar</a>
                <?php }else{echo $row_actuales['f_despacho'];} ?></td>
              <td><?php echo $row_actuales['notas']; ?></td>
              <td><a href="orden_editar.php?id=<?php echo $row_actuales['id']; ?>">Editar</a></td>
              <td><a href="orden_borrar.php?id=<?php echo $row_actuales['id']; ?>">borrar</a></td>
              <td><form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <input name="cerrar" type="hidden" id="cerrar" value="1">
  <input name="id" type="hidden" id="id" value="<?php echo $row_actuales['id']; ?>">
<input type="submit" name="button" id="button" value="Finalizar">
<input type="hidden" name="MM_update" value="form1">
</form></td>
            </tr>
            <?php } while ($row_actuales = mysql_fetch_assoc($actuales)); ?>
            <!--Loop end-->
          </tbody>
        </table>
      </div>
      <div class="TabbedPanelsContent"> 
      <table id="miTabla2" >
          <thead>
            <tr>
              <th>ID</th>
              <th>OP</th>
              <th>Pedido</th>
              <th>Entrega</th>
              <th>OC</th>
              <th>REF</th>
              <th>Cliente</th>
              <th>cantidad</th>
              <th>Coextruccion</th>
              <th>Impresion</th>
              <th>Sellada</th>
              <th>Despacho</th>
              <th>notas</th>
                            
            </tr>
          </thead>
          <tbody>
            <!--Loop start, you could use a repeat region here-->
            <?php do { ?>
            <tr>
              <td><a href="#" onClick="MM_openBrWindow('Ordenpedido_detalle.php?id=<?php echo $row_actuales2['id']; ?>','','scrollbars=yes,width=900,height=400')"><?php echo $row_actuales2['id']; ?></a></td>
              <td><a href="#" onClick="MM_openBrWindow('ordeningresar_ordenproduccion.php?id=<?php echo $row_actuales2['id']; ?>','','scrollbars=yes,width=400,height=200')">
                <?php if( $row_actuales2['orden_produccion']==""){ ?>
                registar
                <?php }else{ ?>
                <a href="#" onClick="MM_openBrWindow('Ordenpedido_detalle.php?id=<?php echo $row_actuales2['id']; ?>','','scrollbars=yes,width=900,height=600')"><? echo $row_actuales2['orden_produccion'];}; ?></a></td>
              <td><?php echo $row_actuales2['fecha_pedido']; ?></td>
              <td><?php echo $row_actuales2['fecha_entrega']; ?></td>
              <td><?php echo $row_actuales2['numero_orden_compra']; ?></td>
              <td><?php echo $row_actuales2['cod_ref']; ?>-<?php echo $row_actuales2['version_ref']; ?></td>
              <td><a href="perfil_cliente_vista.php?id_c= <?php echo $row_actuales2['id_c']; ?>&tipo_usuario=1" target="_blank"><?php echo $row_actuales2['nombre_c']; ?></a></td>
              <td><?php echo number_format($row_actuales2['cantidad']) ; ?></td>
              <td><?php if( $row_actuales2['f_coextruccion'] ==""){?>
                <a href="#" onClick="MM_openBrWindow('ordeningresar_fecha.php?tipo=1&id=<?php echo $row_actuales2['id']; ?>','','width=400,height=200')">Registrar</a>
              <?php }else{echo $row_actuales2['f_coextruccion'];} ?></td>
              <td><?php if( $row_actuales2['f_impresion']==""){?>
                <a href="#" onClick="MM_openBrWindow('ordeningresar_fecha.php?tipo=2&id=<?php echo $row_actuales2['id']; ?>','','width=400,height=200')">Registrar</a>
                <?php }else{echo $row_actuales2['f_impresion'];} ?></td>
              <td><?php if( $row_actuales2['f_sellada']==""){?>
                <a href="#" onClick="MM_openBrWindow('ordeningresar_fecha.php?tipo=3&id=<?php echo $row_actuales2['id']; ?>','','width=400,height=200')">Registrar</a>
              <?php }else{echo $row_actuales2['f_sellada'];}?></td>
              <td><?php if( $row_actuales2['f_despacho']==""){?>
                <a href="#" onClick="MM_openBrWindow('ordeningresar_fecha.php?tipo=4&id=<?php echo $row_actuales2['id']; ?>','','width=400,height=200')">Registrar</a>
                <?php }else{echo $row_actuales2['f_despacho'];} ?></td>
              <td><?php echo $row_actuales2['notas']; ?></td>
                        
            </tr>
            <?php } while ($row_actuales2 = mysql_fetch_assoc($actuales2)); ?>
            <!--Loop end-->
          </tbody>
        </table></div>
    </div>
  </div>
  <p>&nbsp;</p>

<script type="text/javascript">
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
</script>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($cerrar);

mysql_free_result($actuales);

?>
