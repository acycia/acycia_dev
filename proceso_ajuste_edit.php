<?php require_once('Connections/conexion1.php'); ?>
<?php require_once('../Connections/Conexion1.php'); ?>
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
//LLAMADO A FUNCIONES
include('funciones/funciones_php.php');//SISTEMA RUW PARA LA BASE DE DATOS 
//FIN
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
  $updateSQL = sprintf("UPDATE TblProcesoAjuste SET id_proceso_pa=%s, valor_pa=%s, fechaInicial_pa=%s, fechaFinal_pa=%s WHERE id_pa=%s",
                       GetSQLValueString($_POST['id_proceso_pa'], "int"),
                       GetSQLValueString($_POST['valor_pa'], "double"),
                       GetSQLValueString($_POST['fechaInicial_pa'], "date"),
                       GetSQLValueString($_POST['fechaFinal_pa'], "date"),
                       GetSQLValueString($_POST['id_pa'], "int"));

  mysql_select_db($database_Conexion1, $Conexion1);
  $Result1 = mysql_query($updateSQL, $Conexion1) or die(mysql_error());

  $updateGoTo = "proceso_ajuste_listado.php";
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

mysql_select_db($database_conexion1, $conexion1);
$query_lista_procesos = "SELECT * FROM tipo_procesos ORDER BY nombre_proceso ASC";
$lista_procesos = mysql_query($query_lista_procesos, $conexion1) or die(mysql_error());
$row_lista_procesos = mysql_fetch_assoc($lista_procesos);
$totalRows_lista_procesos = mysql_num_rows($lista_procesos);

$colname_editar_ajuste = "-1";
if (isset($_GET['id_pa'])) {
  $colname_editar_ajuste = (get_magic_quotes_gpc()) ? $_GET['id_pa'] : addslashes($_GET['id_pa']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_editar_ajuste = sprintf("SELECT * FROM TblProcesoAjuste WHERE id_pa = %s", $colname_editar_ajuste);
$editar_ajuste = mysql_query($query_editar_ajuste, $conexion1) or die(mysql_error());
$row_editar_ajuste = mysql_fetch_assoc($editar_ajuste);
$totalRows_editar_ajuste = mysql_num_rows($editar_ajuste);

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
<script language="javascript" type="text/javascript">
//VALIDA AJUSTE RELACIONADO CON PROCESO
function validar() {
    DatosGestiones3('3','proceso_ajuste',document.form1.id_proceso_pa.value,'&fechaInicial_ajuste',document.form1.fechaInicial_pa.value,'&fechaFinal_ajuste',document.form1.fechaFinal_pa.value);
}
</script>
</head>
<body>
<div align="center">
<table align="center" id="tabla">
<tr align="center"><td>
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
  <li><a href="costos_generales.php">COSTOS GENERALES</a></li>
  </ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1">
      <form action="<?php echo $editFormAction; ?>" method="POST" name="form1" onSubmit="return envioFormAjusteProceso();">
        <table align="center" id="tabla35">
          <tr>
            <td colspan="2" id="titulo2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2" id="titulo2">ADICIONE EL AJUSTE AL PROCESO
            </td>
            </tr>
          <tr>
            <td id="fuente3" colspan="2" ><a href="proceso_ajuste_listado.php"><img src="images/opciones.gif" title="LISTADO" alt="LISTADO" border="0" style="cursor:hand;"></a><a href="tipos_procesos.php"><img src="images/p.gif" title="TIPOS DE PROCESOS" alt="TIPOS DE PROCESOS" border="0" style="cursor:hand;"></a></td>
            </tr>
            <tr>
            <td colspan="2" id="fuente2"><div id="resultado_generador"></div></td>
            </tr>
          <tr>
            <td id="fuente1">PROCESO</td>
            <td id="fuente1"><select name="id_proceso_pa" id="input2" style="width:150px">
              <option value=""<?php if (!(strcmp("", $row_editar_ajuste['id_proceso_pa']))) {echo "selected=\"selected\"";} ?>>Proceso</option>
              <?php
			do {  
			?>
              <option value="<?php echo $row_lista_procesos['id_tipo_proceso']?>"<?php if (!(strcmp($row_lista_procesos['id_tipo_proceso'], $row_editar_ajuste['id_proceso_pa']))) {echo "selected=\"selected\"";} ?>><?php echo $row_lista_procesos['nombre_proceso']?></option>
              <?php
			} while ($row_lista_procesos = mysql_fetch_assoc($lista_procesos));
			  $rows = mysql_num_rows($lista_procesos);
			  if($rows > 0) {
				  mysql_data_seek($lista_procesos, 0);
				  $row_lista_procesos = mysql_fetch_assoc($lista_procesos);
			  }
			?>
              </select><div id="resultado_generador"></div></td>
          </tr>
          <tr>
            <td id="fuente1">AJUSTE</td>
            <td id="fuente1"><input name="valor_pa" type="number" id="valor_pa" min="0" step="1"  required value="<?php echo $row_editar_ajuste['valor_pa']?>" style="width:150px" onClick="envioFormAjusteProceso();"></td>
          </tr>
          <tr>
            <td id="fuente1">FECHA INICIAL</td>
            <td id="fuente1"><input name="fechaInicial_pa" type="date" required id="fecha1" min="2000-01-02" value="<?php echo $row_editar_ajuste['fechaInicial_pa']?>" size="10" /></td>
          </tr>
          <tr>
            <td id="fuente1">FECHA FINAL</td>
            <td id="fuente1"><input name="fechaFinal_pa" type="date" required id="fecha2" min="2000-01-02" size="10" value="<?php echo $row_editar_ajuste['fechaFinal_pa']?>" onBlur="validar()"/></td>
          </tr>
          <tr>
            <td colspan="2">&nbsp;</td>
            </tr>
          <tr>
            <td colspan="2" id="dato2"><input type="submit" value="ADICIONAR PROCESO"></td>
            </tr>
        </table>
        <input type="hidden" name="id_pa" value="<?php echo $row_editar_ajuste['id_pa']; ?>">
        <input type="hidden" name="MM_update" value="form1">
      </form></td>
  </tr>
</table></div>
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

mysql_free_result($lista_procesos);

mysql_free_result($editar_ajuste);

?>