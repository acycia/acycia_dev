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
  $updateSQL = sprintf("UPDATE TblAportes SET id_aporte=%s,codigo_empl=%s, cesantias_porc=%s, interesCesantias_porc=%s, prima_porc=%s,salud_porc=%s,pension_porc=%s,vacaciones_porc=%s,cajaCompensacion_porc=%s,sena_porc=%s,arl_porc=%s,total=%s,fecha=%s  WHERE id_aporte=%s", 
                       GetSQLValueString($_POST['id_aporte'], "int"),
					   GetSQLValueString($_POST['id_aporte'], "int"),
                       GetSQLValueString($_POST['cesantias_porc'], "double"),
					   GetSQLValueString($_POST['interesCesantias_porc'], "double"),
					   GetSQLValueString($_POST['prima_porc'], "double"),
					   GetSQLValueString($_POST['salud_porc'], "double"),
					   GetSQLValueString($_POST['pension_porc'], "double"),
					   GetSQLValueString($_POST['vacaciones_porc'], "double"),
					   GetSQLValueString($_POST['cajaCompensacion_porc'], "double"),
					   GetSQLValueString($_POST['sena_porc'], "double"),
					   GetSQLValueString($_POST['arl_porc'], "double"),
					   GetSQLValueString($_POST['total'], "double"),
					   GetSQLValueString($_POST['fecha'], "date"),
					   GetSQLValueString($_POST['id_aporte'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "proceso_empleados_listado.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo ));
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


$colname_aporte = "-1";
if (isset($_GET['id_aporte'])) {
  $colname_aporte = (get_magic_quotes_gpc()) ? $_GET['id_aporte'] : addslashes($_GET['id_aporte']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_aportes = sprintf("SELECT * FROM TblAportes WHERE id_aporte=%s",$colname_aporte);
$aportes = mysql_query($query_aportes, $conexion1) or die(mysql_error());
$row_aportes = mysql_fetch_assoc($aportes);
$totalRows_aportes = mysql_num_rows($aportes);

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script> 

</head>
<body>
<div align="center">
<table align="center" id="tabla">
<tr align="center"><td id="fuente1">
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
  </ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1">
      <form action="<?php echo $editFormAction; ?>" method="POST" name="form1">
        <table align="center" id="tabla35">
          <tr>
            <td colspan="3" id="titulo2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3" id="titulo2">EDITAR APORTES</td>
            </tr>
          <tr>
            <td id="fuente1">&nbsp;</td>
            <td id="fuente1"><a href="proceso_empleados_listado.php"><img src="images/opciones.gif" alt="EMPLEADOS POR PROCESO" title="EMPLEADOS POR PROCESO" border="0" style="cursor:hand;"></a><a href="factor_prestacional_add.php"><img src="images/f.gif" alt="FACTORES" title="FACTORES" border="0" style="cursor:hand;"></a><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/>
                <input name="id_aporte" type="hidden" id="id_aporte" value="<?php echo $row_aportes['id_aporte']?>">
            </a></td>
            <td id="fuente2">&nbsp;</td>
            </tr>
              <tr>
                <td id="fuente1" nowrap>FECHA DE LOS APORTES</td>
                <td id="fuente1"><input name="fecha" type="date" id="fecha" min="2000-01-02" style="width:145px" value="<?php echo $row_aportes['fecha']?>" required></td>
                <td id="fuente3"></td>
              </tr>
              <tr>
                <td id="fuente1">CESANTIAS</td>
                <td id="fuente1"><input name="cesantias_porc" type="number" id="cesantias_porc" placeholder="%" style="width:70px" min="0" step="0.001" value="<?php echo $row_aportes['cesantias_porc']?>"></td>
                <td id="fuente1">&nbsp;</td>
              </tr>
              <tr>
                <td id="fuente1" nowrap>INTERESES DE CESANTIAS</td>
                <td id="fuente1"><input name="interesCesantias_porc" type="number" id="interesCesantias_porc" placeholder="%" style="width:70px" min="0" step="0.001" value="<?php echo $row_aportes['interesCesantias_porc']?>"></td>
                <td id="fuente1">&nbsp;</td>
              </tr>
              <tr>
                <td id="fuente1">PRIMA</td>
                <td id="fuente1"><input name="prima_porc" type="number" id="prima_porc" placeholder="%" style="width:70px" min="0" step="0.001" value="<?php echo $row_aportes['prima_porc']?>"></td>
                <td id="fuente1">&nbsp;</td>
              </tr>
              <tr>
                <td id="fuente1">SALUD</td>
                <td id="fuente1"><input name="salud_porc" type="number" id="salud_porc" placeholder="%" style="width:70px" min="0" step="0.001" value="<?php echo $row_aportes['salud_porc']?>"></td>
                <td id="fuente1">&nbsp;</td>
              </tr>
              <tr>
                <td id="fuente1">PENSION</td>
                <td id="fuente1"><input name="pension_porc" type="number" id="pension_porc" placeholder="%" style="width:70px" min="0" step="0.001" value="<?php echo $row_aportes['pension_porc']?>"></td>
                <td id="fuente1">&nbsp;</td>
              </tr>
              <tr>
                <td id="fuente1">VACACIONES</td>
                <td id="fuente1"><input name="vacaciones_porc" type="number" id="vacaciones_porc" placeholder="%" style="width:70px" min="0" step="0.001" value="<?php echo $row_aportes['vacaciones_porc']?>"></td>
                <td id="fuente1">&nbsp;</td>
              </tr>
              <tr>
                <td id="fuente1" nowrap>CAJA DE COMPENSACION</td>
                <td id="fuente1"><input name="cajaCompensacion_porc" type="number" id="cajaCompensacion_porc" placeholder="%" style="width:70px" min="0" step="0.001" value="<?php echo $row_aportes['cajaCompensacion_porc']?>"></td>
                <td id="fuente1">&nbsp;</td>
              </tr>
              <tr>
                <td id="fuente1">SENA</td>
                <td id="fuente1"><input name="sena_porc" type="number" id="sena_porc" placeholder="%" style="width:70px" min="0" step="0.001" value="<?php echo $row_aportes['sena_porc']?>"></td>
                <td id="fuente1">&nbsp;</td>
              </tr>
              <tr>
                <td id="fuente1">ARL</td>
                <td id="fuente1"><input name="arl_porc" type="number" id="arl_porc" placeholder="%" style="width:70px" min="0" step="0.001" value="<?php echo $row_aportes['arl_porc']?>"></td>
                <td id="fuente1">&nbsp;</td>
              </tr>
              <tr>
                <td id="fuente1">&nbsp;</td>
                <td id="fuente1">&nbsp;</td>
                <td id="fuente1">&nbsp;</td>
              </tr>
          <tr>
            <td colspan="3" id="fuente1"><strong>Nota: </strong> <em>la fecha de estos aportes siempre estara vigente hasta que ingrese los nuevos aportes.</em></td>
          </tr>  
          <tr>
            <td colspan="3" id="fuente1">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3" id="dato2"><input type="submit" value="ACTUALIZAR"></td>
            </tr>
        </table>
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
mysql_free_result($aportes);

?>