<?php require_once('Connections/conexion1.php'); ?>
<?php

require_once('funciones/funciones_php.php');

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
  $updateSQL = sprintf("UPDATE evaluacion_anual SET fecha_desde_eva=%s, fecha_hasta_eva=%s, fecha_realizacion_eva=%s, responsable_eva=%s, observacion_eva=%s WHERE id_eva=%s",
                       GetSQLValueString($_POST['fecha_desde_eva'], "date"),
                       GetSQLValueString($_POST['fecha_hasta_eva'], "date"),
                       GetSQLValueString($_POST['fecha_realizacion_eva'], "date"),
                       GetSQLValueString($_POST['responsable_eva'], "text"),
                       GetSQLValueString($_POST['observacion_eva'], "text"),
                       GetSQLValueString($_POST['id_eva'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "evaluacion_proveedores_anual_vista.php?id_eva=" . $_POST ['id_eva'] . "&desde=" . $_POST['desde'] . "&hasta=" . $_POST['hasta'] . "";
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

$desde_evaluacion_proveedor = "-1";
if (isset($_GET['desde'])) {
  $desde_evaluacion_proveedor = (get_magic_quotes_gpc()) ? $_GET['desde'] : addslashes($_GET['desde']);
}
$hasta_evaluacion_proveedor = "-1";
if (isset($_GET['hasta'])) {
  $hasta_evaluacion_proveedor = (get_magic_quotes_gpc()) ? $_GET['hasta'] : addslashes($_GET['hasta']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_evaluacion_proveedor = sprintf("SELECT * FROM evaluacion_proveedor WHERE periodo_desde_ev>='%s' AND periodo_hasta_ev<='%s' ORDER BY n_ev ASC", $desde_evaluacion_proveedor,$hasta_evaluacion_proveedor);
$evaluacion_proveedor = mysql_query($query_evaluacion_proveedor, $conexion1) or die(mysql_error());
$row_evaluacion_proveedor = mysql_fetch_assoc($evaluacion_proveedor);
$totalRows_evaluacion_proveedor = mysql_num_rows($evaluacion_proveedor);

$colname_evaluacion_edit = "-1";
if (isset($_GET['id_eva'])) {
  $colname_evaluacion_edit = (get_magic_quotes_gpc()) ? $_GET['id_eva'] : addslashes($_GET['id_eva']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_evaluacion_edit = sprintf("SELECT * FROM evaluacion_anual WHERE id_eva = %s", $colname_evaluacion_edit);
$evaluacion_edit = mysql_query($query_evaluacion_edit, $conexion1) or die(mysql_error());
$row_evaluacion_edit = mysql_fetch_assoc($evaluacion_edit);
$totalRows_evaluacion_edit = mysql_num_rows($evaluacion_edit);
?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
<link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">

<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
<script type="text/javascript" src="js/listado.js"></script>
<link rel="stylesheet" type="text/css" href="css/general.css"/>
</head>
<body>
<table id="tabla3"><tr><td align="center">
<table id="tabla1"><tr>
<td colspan="2" align="center"><img src="images/cabecera.jpg"></td></tr>
<tr><td id="nombreusuario"><?php echo $row_usuario['nombre_usuario']; ?></td>
  <td id="cabezamenu"><ul id="menuhorizontal">
  <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
  <li><a href="menu.php">MENU PRINCIPAL</a></li>
  <li><a href="compras.php">GESTION COMPRAS</a></li>
  </ul></td>
</tr>  
</table>
</td></tr>
  <tr>
    <td id="linea1" align="center">
	<form name="form2" method="get" action="evaluacion_proveedores_anual_edit.php">
	<table id="tabla3">
      <tr id="tr1">
        <td id="codigo">CODIGO : A3-F05</td>
        <td id="subtitulo">EVALUACION DE DESEMPE&Ntilde;O DE PROVEEDORES (ANUAL) # <?php echo $row_evaluacion_edit['id_eva']; ?></td>
        <td id="codigo">VERSION : 2 </td>
      </tr>
      <tr>
        <td colspan="2" id="fuente2"><label>
          <input name="id_eva" type="hidden" id="id_eva" value="<?php echo $row_evaluacion_edit['id_eva']; ?>">
        </label>
          <?php /*PRIORIDAD*/ $desde=$_GET['desde']; $hasta=$_GET['hasta']; ?>PERIODO DESDE  <input name="desde" type="date" id="desde" size="10" value="<?php if($desde=='') {echo date("Y-m-d"); } else{ echo $desde; } ?>"> 
		HASTA    
		  <input name="hasta" type="date" id="hasta" size="10"  value="<?php if($hasta==''){ echo date("Y-m-d"); } else{ echo $hasta; } ?>">
		   <input name="Submit" type="submit" value="FILTRO"></td>
        <td id="dato2"><a href="evaluacion_proveedores_anual_edit.php?id_eva=<?php echo $_GET['id_eva']; ?>&desde=<?php echo $_GET['desde']; ?>&hasta=<?php echo $_GET['hasta']; ?>"><img src="images/ciclo1.gif" alt="RESTAURAR" border="0" style="cursor:hand;"/></a><a href="javascript:eliminar1('id_eva',<?php echo $row_evaluacion_edit['id_eva']; ?>,'evaluacion_proveedores_anual_edit.php')"><img src="images/por.gif" alt="ELIMINAR" border="0" style="cursor:hand;"/></a><a href="evaluacion_proveedores_anual_vista.php?id_eva=<?php echo $_GET['id_eva']; ?>&desde=<?php echo $_GET['desde']; ?>&hasta=<?php echo $_GET['hasta']; ?>"><img src="images/hoja.gif" alt="VISTA IMPRESION" border="0" /></a><a href="evaluacion_anual.php"><img src="images/cat.gif" alt="EVALUACIONES" border="0" style="cursor:hand;" /></a><a href="proveedores.php"><img src="images/p.gif" alt="PROVEEDORES" border="0" /></a></td>
      </tr><?php if($desde=='' && $hasta=='') { ?>
      <tr>
        <td colspan="2" id="acceso2">Filtrar los proveedores que tienen evaluacion en el periodo especificado.</td>
        <td id="dato2">&nbsp;</td>
      </tr><?php } ?>
    </table>
	</form>
	</td>
  </tr>
<?php if($desde!='' && $hasta!='') { 
$ev=$row_evaluacion_proveedor['id_ev'];
if($ev!='') { ?>
  <tr id="tr2">
  <td id="dato2">
  <table id="tabla3">
    <tr id="tr2">
      <td id="titulo4">N&deg;</td>
      <td id="titulo4">Proveedor</td>
      <td id="titulo4">Servicio/Producto</td>
      <td id="titulo4">Entrega</td>
      <td id="titulo4">Cantidad </td>
      <td id="titulo4">Calidad</td>
      <td id="titulo4">Servicio</td>
      <td id="titulo4">Total</td>
      <td id="titulo4">Plan de Accion </td>
      <td id="titulo4">Responsable</td>
      <td id="titulo4"> Implementación</td>
    </tr>
    <?php do { ?>
      <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
        <td id="dato3"><strong><?php echo $row_evaluacion_proveedor['n_ev']; ?></strong></td>
        <td id="dato1"><?php $id_p=$row_evaluacion_proveedor['id_p_ev'];
        $sql2="SELECT * FROM proveedor WHERE id_p='$id_p'";
        $result2=mysql_query($sql2); $num2=mysql_num_rows($result2);
        if ($num2 >='1') { $nombre_p=mysql_result($result2,0,'proveedor_p');	
      } echo $nombre_p; ?> 
    </td>
        <td nowrap id="dato3"><?php echo str_replace('.',',',$row_evaluacion_proveedor['evaluacion']); ?></td>
        <td nowrap id="dato3"><?php echo str_replace('.',',',$row_evaluacion_proveedor['porcentaje_oportunos_ev']); ?>%</td>
        <td nowrap id="dato3"><?php echo str_replace('.',',',$row_evaluacion_proveedor['porcentaje_cumple_ev'])=='' ? "N.A" : str_replace('.',',',$row_evaluacion_proveedor['porcentaje_cumple_ev']).'%'; ?></td>
        <td nowrap id="dato3"><?php echo str_replace('.',',',$row_evaluacion_proveedor['porcentaje_conforme_ev']); ?>%</td>
        <td nowrap id="dato3"><?php echo str_replace('.',',',$row_evaluacion_proveedor['porcentaje_atencion_ev']); ?>%</td>
        <td nowrap id="dato3"><?php echo str_replace('.',',',$row_evaluacion_proveedor['porcentaje_final_ev']); ?>%</td>
        <td id="dato1"><?php echo eliminar_tildes($row_evaluacion_proveedor['calificacion_texto_ev']); ?></td>
        <td id="dato1"><?php echo $row_evaluacion_proveedor['responsable_registro_ev']; ?></td>
        <td id="dato2"><?php echo $row_evaluacion_proveedor['fecha_registro_ev']; ?></td>
      </tr>
      <?php } while ($row_evaluacion_proveedor = mysql_fetch_assoc($evaluacion_proveedor)); ?>
  </table></td>
</tr>
<?php } else{ ?>
<tr><td id="numero2"><img src="images/no.gif">No existen registros en ese periodo de tiempo. Compruebe si la fecha esta correcta.</td></tr>
<?php } ?>
  <tr>
    <td height="26" align="center" id="linea1">
	<form action="<?php echo $editFormAction; ?>" method="post" name="form1" onSubmit="MM_validateForm('desde','','R','hasta','','R','fecha_realizacion_eva','','R','responsable_eva','','R');return document.MM_returnValue">
	<table id="tabla3">
          <tr>
            <td id="fuente1"><input name="fecha_desde_eva" type="hidden" value="<?php echo $_GET['desde']; ?>">
            <input name="fecha_hasta_eva" type="hidden" value="<?php echo $_GET['hasta']; ?>">
            FECHA DE REALIZACION 
            <input type="date" name="fecha_realizacion_eva" value="<?php echo $row_evaluacion_edit['fecha_realizacion_eva']; ?>" size="10"> 
            REALIZADO POR 
            <input type="text" name="responsable_eva" value="<?php echo $row_evaluacion_edit['responsable_eva']; ?>" size="30"></td>
            <td id="fuente1">&nbsp;</td>
          </tr>
          <tr>
            <td rowspan="2" id="fuente1"><strong>OBSERVACIONES</strong><br><textarea name="observacion_eva" cols="60" rows="2"><?php echo $row_evaluacion_edit['observacion_eva']; ?></textarea></td>
            <td id="fuente2">Actualizar la evaluacion si los datos son correctos</td>
          </tr>
          <tr>
            <td id="dato2"><input name="submit" class="botonGigante" type="submit" value="Actualizar Evaluacion Final"></td>
          </tr>
          
        </table>
        <input type="hidden" name="MM_update" value="form1">
        <input type="hidden" name="id_eva" value="<?php echo $row_evaluacion_edit['id_eva']; ?>">
      </form></td>
  </tr>
  <?php } ?>
</table>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($evaluacion_proveedor);

mysql_free_result($evaluacion_edit);
?>