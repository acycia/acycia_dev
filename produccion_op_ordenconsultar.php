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
  $updateSQL = sprintf("UPDATE Tbl_orden_produccion SET b_estado_op=%s WHERE id_op=%s",
                       GetSQLValueString($_POST['cerrar'], "int"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());
  $ruta="produccion_op_ordenconsultar.php?variables=".$_POST['id'];
    $updateGoTo = $ruta;
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  
  header(sprintf("Location: %s", $updateGoTo));
  }
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE Tbl_orden_produccion SET b_estado_op=%s WHERE id_op=%s",
                       GetSQLValueString($_POST['continuar'], "int"),
                       GetSQLValueString($_POST['id_a'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());
$ruta="produccion_op_ordenconsultar.php?variables=".$_POST['id_a'];

  $updateGoTo = $ruta;
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  
  header(sprintf("Location: %s", $updateGoTo));
 }
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
$query_cerrar = "SELECT id_op, b_estado_op FROM Tbl_orden_produccion WHERE id_op = id_op";
$cerrar = mysql_query($query_cerrar, $conexion1) or die(mysql_error());
$row_cerrar = mysql_fetch_assoc($cerrar);
$totalRows_cerrar = mysql_num_rows($cerrar);

mysql_select_db($database_conexion1, $conexion1);
$query_actuales = "SELECT  
	   Tbl_orden_produccion.fecha_registro_op, 
	   Tbl_orden_produccion.fecha_entrega_op, 
	   Tbl_orden_produccion.str_numero_oc_op, 
	   Tbl_orden_produccion.str_tipo_bolsa_op, 
	   Tbl_referencia.cod_ref, 
	   Tbl_referencia.version_ref, 
	   cliente.nombre_c, 
	   cliente.id_c, 
	   Tbl_orden_produccion.int_cantidad_op,  
	   Tbl_orden_produccion.int_cod_ref_op, 
	   Tbl_orden_produccion.id_op, 
	   Tbl_orden_produccion.str_matrial_op, 
	   Tbl_orden_produccion.observ_sellado_op,
	   Tbl_orden_produccion.f_coextruccion,
	   Tbl_orden_produccion.f_impresion,
	   Tbl_orden_produccion.f_sellada,
	   Tbl_orden_produccion.f_despacho
	   FROM     
	   Tbl_orden_produccion     
	   INNER JOIN cliente ON (Tbl_orden_produccion.int_cliente_op = cliente.id_c)  
	   INNER JOIN Tbl_referencia ON (Tbl_orden_produccion.id_ref_op = Tbl_referencia.id_ref) WHERE Tbl_orden_produccion.b_estado_op < 5 ORDER BY Tbl_orden_produccion.id_op DESC";
$actuales = mysql_query($query_actuales, $conexion1) or die(mysql_error());
$row_actuales = mysql_fetch_assoc($actuales);
$totalRows_actuales = mysql_num_rows($actuales);

mysql_select_db($database_conexion1, $conexion1);
$query_actuales2 = "SELECT  
	   Tbl_orden_produccion.fecha_registro_op, 
	   Tbl_orden_produccion.fecha_entrega_op, 
	   Tbl_orden_produccion.str_numero_oc_op, 
	   Tbl_orden_produccion.str_tipo_bolsa_op, 
	   Tbl_referencia.cod_ref, 
	   Tbl_referencia.version_ref, 
	   cliente.nombre_c, 
	   cliente.id_c, 
	   Tbl_orden_produccion.int_cantidad_op,  
	   Tbl_orden_produccion.int_cod_ref_op, 
	   Tbl_orden_produccion.id_op, 
	   Tbl_orden_produccion.str_matrial_op, 
	   Tbl_orden_produccion.observ_sellado_op,
	   Tbl_orden_produccion.f_coextruccion,
	   Tbl_orden_produccion.f_impresion,
	   Tbl_orden_produccion.f_sellada,
	   Tbl_orden_produccion.f_despacho	   
	   FROM     
	   Tbl_orden_produccion     
	   INNER JOIN cliente ON (Tbl_orden_produccion.int_cliente_op = cliente.id_c)  
	   INNER JOIN Tbl_referencia ON (Tbl_orden_produccion.id_ref_op = Tbl_referencia.id_ref) WHERE Tbl_orden_produccion.b_estado_op = 5 ORDER BY Tbl_orden_produccion.id_op DESC";
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
<script type="text/javascript" src="js/consulta.js"></script>
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
              <th>OP</th>
              <th>Pedido</th>
              <th>Entrega</th>
              <th>OC</th>
              <th>REF</th>
              <th>Cliente</th>
              <th>Cantidad</th>
              <th>Coextruccion</th>
              <th>Impresion</th>
              <th>Sellada</th>
              <th>Despacho</th>
              <th>Notas</th>
              <th>Editar</th>
              <th>Borrar</th>
             <th>Finalizar</th>
            </tr>
          </thead>
          <tbody>
            <!--Loop start, you could use a repeat region here-->
            <?php do { ?>
            <tr>
              <td><a href="#" onClick="MM_openBrWindow('ordeningresar_ordenproduccion.php?id=<?php echo $row_actuales['id_op']; ?>','','scrollbars=yes,width=400,height=200')">
                <?php if( $row_actuales['id_op']==""){ ?>
                registrar</a>
                <?php }else{ ?>
                <a href="#" onClick="MM_openBrWindow('produccion_Ordenpedido_detalle.php?id_op=<?php echo $row_actuales['id_op']; ?>','','scrollbars=yes,width=900,height=600')"><?php echo $row_actuales['id_op'];}; ?></a></td>
              <td><?php echo $row_actuales['fecha_registro_op']; ?></td>
              <td><?php echo $row_actuales['fecha_entrega_op']; ?></td>
              <td><?php echo $row_actuales['str_numero_oc_op']; ?></td>
              <td><?php echo $row_actuales['cod_ref']; ?>-<?php echo $row_actuales['version_ref']; ?></td>
              <td><a href="perfil_cliente_vista.php?id_c= <?php echo $row_actuales['id_c']; ?>&tipo_usuario=1" target="_blank"><?php echo $row_actuales['nombre_c']; ?></a></td>
              <td><?php echo number_format($row_actuales['int_cantidad_op']) ; ?></td>
              <td><?php if( $row_actuales['f_coextruccion'] ==""){?>
                <a href="#" onClick="MM_openBrWindow('ordeningresar_fecha.php?tipo=1&id=<?php echo $row_actuales['id_op']; ?>','','width=400,height=200')">Registrar</a>
              <?php }else{echo $row_actuales['f_coextruccion'];} ?></td>
              <td><?php if( $row_actuales['f_impresion']==""){?>
                <a href="#" onClick="MM_openBrWindow('ordeningresar_fecha.php?tipo=2&id=<?php echo $row_actuales['id_op']; ?>','','width=400,height=200')">Registrar</a>
                <?php }else{echo $row_actuales['f_impresion'];} ?></td>
              <td><?php if( $row_actuales['f_sellada']==""){?>
                <a href="#" onClick="MM_openBrWindow('ordeningresar_fecha.php?tipo=3&id=<?php echo $row_actuales['id_op']; ?>','','width=400,height=200')">Registrar</a>
              <?php }else{echo $row_actuales['f_sellada'];}?></td>
              <td><?php if( $row_actuales['f_despacho']==""){?>
                <a href="#" onClick="MM_openBrWindow('ordeningresar_fecha.php?tipo=4&id=<?php echo $row_actuales['id_op']; ?>','','width=400,height=200')">Registrar</a>
                <?php }else{echo $row_actuales['f_despacho'];} ?></td>
              <td><?php echo $row_actuales['notas']; ?></td>
              <td><a href="orden_editar.php?id=<?php echo $row_actuales['id_op']; ?>">Editar</a></td>
              <td><a href="orden_borrar.php?id=<?php echo $row_actuales['id_op']; ?>">borrar</a></td>
              <td><form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <input name="cerrar" type="hidden" id="cerrar" value="5">
  <input name="id" type="hidden" id="id" value="<?php echo $row_actuales['id_op']; ?>">
<input type="submit" name="button" id="button" value="Finalizar" onClick="alerta2(this.id)">
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
              <th>Finalizar</th> 
              <th>Reactivar</th>            
            </tr>
          </thead>
          <tbody>
            <!--Loop start, you could use a repeat region here-->
            <?php do { ?>
            <tr>
              <td><a href="#" onClick="MM_openBrWindow('ordeningresar_ordenproduccion.php?id=<?php echo $row_actuales2['id_op']; ?>','','scrollbars=yes,width=400,height=200')">
                <?php if( $row_actuales2['id_op']==""){ ?>
                registar</a>
                <?php }else{ ?>
                <a href="#" onClick="MM_openBrWindow('produccion_Ordenpedido_detalle.php?id=<?php echo $row_actuales2['id_op']; ?>','','scrollbars=yes,width=900,height=600')"><?php echo $row_actuales2['id_op'];}?></a></td>
              <td><?php echo $row_actuales2['fecha_registro_op']; ?></td>
              <td><?php echo $row_actuales2['fecha_entrega_op']; ?></td>
              <td><?php echo $row_actuales2['str_numero_oc_op']; ?></td>
              <td><?php echo $row_actuales2['cod_ref']; ?>-<?php echo $row_actuales2['version_ref']; ?></td>
              <td><a href="perfil_cliente_vista.php?id_c=<?php echo $row_actuales2['id_c']; ?>&tipo_usuario=1" target="_blank"><?php echo $row_actuales2['nombre_c']; ?></a></td>
              <td><?php echo number_format($row_actuales2['int_cantidad_op']) ; ?></td>
              <td><?php if( $row_actuales2['f_coextruccion'] ==""){?>
                <a href="#" onClick="MM_openBrWindow('ordeningresar_fecha.php?tipo=1&id=<?php echo $row_actuales2['id_op']; ?>','','width=400,height=200')">Registrar</a>
              <?php }else{echo $row_actuales2['f_coextruccion'];} ?></td>
              <td><?php if( $row_actuales2['f_impresion']==""){?>
                <a href="#" onClick="MM_openBrWindow('ordeningresar_fecha.php?tipo=2&id=<?php echo $row_actuales2['id_op']; ?>','','width=400,height=200')">Registrar</a>
                <?php }else{echo $row_actuales2['f_impresion'];} ?></td>
              <td><?php if( $row_actuales2['f_sellada']==""){?>
                <a href="#" onClick="MM_openBrWindow('ordeningresar_fecha.php?tipo=3&id=<?php echo $row_actuales2['id_op']; ?>','','width=400,height=200')">Registrar</a>
              <?php }else{echo $row_actuales2['f_sellada'];}?></td>
              <td><?php if( $row_actuales2['f_despacho']==""){?>
                <a href="#" onClick="MM_openBrWindow('ordeningresar_fecha.php?tipo=4&id=<?php echo $row_actuales2['id_op']; ?>','','width=400,height=200')">Registrar</a>
                <?php }else{echo $row_actuales2['f_despacho'];} ?></td>
              <td><?php echo $row_actuales2['notas']; ?></td>
              <td>Finalizada</td>
              <td><form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
              <input name="id_a" type="hidden" id="id_a" value="<?php echo $row_actuales2['id_op']; ?>"> 
              <input name="continuar" type="hidden" id="continuar" value="4">
              <input type="submit" name="continua" id="continua" onClick="alerta1(this.id_a)" value="Reactivar">
<input type="hidden" name="MM_update" value="form2">
</form></td>           
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

mysql_free_result($actuales2);
?> 