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
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
if (!empty ($_POST['cif_250'])&&!empty ($_POST['cif_500'])&&!empty ($_POST['cif_1000'])&&!empty ($_POST['cif_4000'])){
    foreach($_POST['cif_250'] as $key=>$k)
    $a[]= $k;
    foreach($_POST['cif_500'] as $key=>$k)
    $b[]= $k;
    foreach($_POST['cif_1000'] as $key=>$k)
    $c[]= $k;
    foreach($_POST['cif_4000'] as $key=>$k)
    $d[]= $k;		
    $f1= $_POST['fecha_ini'];
	$f2= $_POST['fecha_fin'];	
	
	for($x=0; $x<count($d); $x++) {
		 // if(!empty($a[$x])&&!empty($b[$x])&&!empty($c[$x])&&!empty($d[$x])){ //no salga error con campos vacios
  $insertSQL = sprintf("INSERT INTO Tbl_costos_cif ( fecha_ini, fecha_fin, costo_produccion, ajuste, costo_real, costo_real_ajustado, cif_250, cif_250_500, cif_501_1000, cif_1001_4000) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($f1, "date"),
                       GetSQLValueString($f2, "date"),
                       GetSQLValueString($a[$x], "double"),
                       GetSQLValueString($b[$x], "double"),
                       GetSQLValueString($c[$x], "double"),
                       GetSQLValueString($d[$x], "double"),
                       GetSQLValueString($a[$x], "double"),
                       GetSQLValueString($b[$x], "double"),
                       GetSQLValueString($c[$x], "double"),
                       GetSQLValueString($d[$x], "double"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertGoTo = "costos_cif_vista.php?fecha_ini=" . $_POST['fecha_ini'] . "&fecha_fin=" . $_POST['fecha_fin'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
                 }
		//  }
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


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
</head>
<body>
<div align="center">
<table align="center" id="tabla"><tr><td align="center">
<div> 
<b class="spiffy"> 
<b class="spiffy1"><b></b></b>
<b class="spiffy2"><b></b></b>
<b class="spiffy3"></b>
<b class="spiffy4"></b>
<b class="spiffy5"></b></b>
<div class="spiffy_content">
<table id="tabla1"><tr>
<td colspan="2" align="center">
<img src="images/cabecera.jpg"></td>
</tr>
<tr>
<td id="nombreusuario"><?php echo $row_usuario['nombre_usuario']; ?></td>
<td id="cabezamenu"><ul id="menuhorizontal">
           <li><a href="<?php echo $logoutAction ?>" target="_top">CERRAR SESION</a></li>
           <li><a href="administrador.php">ADMINISTRADOR</a></li>
		   <li><a href="datos_generales.php">DATOS GENERALES</a></li>
</ul></td>
</tr>
  <tr>
    <td colspan="2" align="center" id="linea1">
      <table border="0" id="tabla1">      
        <tr>
          <td colspan="2" id="dato1">
            <form method="POST" name="form1" action="<?php echo $editFormAction; ?>">
              <fieldset><legend>COSTO CIF UNIDAD PROMEDIO PRODUCIDA</legend>
                <table id="tabla2">
                  <tr>
                    <td id="fuente3" colspan="4">&nbsp;</td>
                    </tr>
                  <tr>
                    <td colspan="4">&nbsp;</td>
                  </tr>
                  <tr id="tr1">
                    <td id="fuente1">Und Producidas Enero - Julio</td>
                    <td id="fuente1">Und Producidas (MES)</td>
                    <td id="fuente1">Costo CIF promedio Und Producida</td>
                    </tr>
                  <tr>
                    <td id="fuente1">&nbsp;</td>
                    <td id="fuente1">&nbsp;</td>
                    <td id="fuente1">&nbsp;</td>
                    </tr>
                  <tr>
                    <td id="fuente1"><input type="number" name="cif_p1" id="cif_p1" min="0" step="1" style=" width:80px"/></td>
                    <td id="fuente1"><input type="number" name="cif_p2" id="cif_p2" min="0" step="1" style=" width:80px"/></td>
                    <td id="fuente1">$ 
                      <input type="number" name="cif_p3" id="cif_p3" min="0" step="0.01" style=" width:80px"/></td>
                    </tr>
                  <tr>
                    <td id="fuente5">&nbsp;</td>
                    <td id="fuente5">&nbsp;</td>
                    <td id="fuente5">&nbsp;</td>
                    </tr>
                  <tr>
                    <td id="fuente4">&nbsp;</td>
                    <td id="fuente4">&nbsp;</td>
                    <td id="fuente4">&nbsp;</td>
                    </tr>
                  <tr>
                    <td id="fuente4">&nbsp;</td>
                    <td id="fuente4">&nbsp;</td>
                    <td id="fuente4">&nbsp;</td>
                    </tr>
                  <tr>
                    <td id="fuente4">&nbsp;</td>
                    <td id="fuente4">&nbsp;</td>
                    <td id="fuente4">&nbsp;</td>
                    </tr>        
                  </table>                
                </fieldset>
              </form></td>
          </tr>
        
  <!--//TABLA NUMERO 2-->
  <tr>
    <td id="dato7">&nbsp;</td>
    <td id="dato7">&nbsp;</td>
    </tr>
  <tr>
    <td id="dato7">&nbsp;</td>
    <td id="dato7">&nbsp;</td>
    </tr>
        
        <tr>
          <td colspan="2" id="dato1">
            <form method="POST" name="form2" action="<?php echo $editFormAction; ?>">
              <fieldset>
                <legend>COSTO CIF POR CUARTILES</legend>
  <table id="tabla2">
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>Fecha inicial:
        <input name="fecha_ini" type="date" required="required" id="fecha_ini" min="2000-01-02" size="10" value="<?php echo date("Y-m-d"); ?>"/></td>
      <td>fecha final:
        <input name="fecha_fin" type="date" id="fecha_fin" min="2000-01-02" size="10" required="required"/></td>
      </tr>
      <tr>
      <td colspan="5">&nbsp;</td>
      </tr>
    <tr id="tr1">
      <td id="fuente1">&nbsp;</td>
      <td id="fuente1">CIF Bolsa de M&aacute;ximo 250 cm&sup2;</td>
      <td id="fuente1">CIF Bolsa entre 250 y 500 cm&sup2;</td>
      <td id="fuente1">CIF Bolsa entre 501  y  1000 cm&sup2;</td>
      <td id="fuente1">CIF Bolsa entre 1001 y 4000 cm&sup2;</td>
      </tr>
    <tr>
      <td id="dato1">Costo cif segun produccion promedio</td>
      <td id="dato1">$
        <input type="number" name="cif_250[]" id="cif_250[]" min="0" step="0.01" style=" width:80px" value="0.00" required="required"/></td>
      <td id="dato1">$
        <input type="number" name="cif_500[]" id="cif_500[]" min="0" step="0.01" style=" width:80px" value="0.00" required="required"/></td>
      <td id="dato1">$
        <input type="number" name="cif_1000[]" id="cif_1000[]" min="0" step="0.01" style=" width:80px" value="0.00" required="required"/></td>
      <td id="dato1">$
        <input type="number" name="cif_4000[]" id="cif_4000[]" min="0" step="0.01" style=" width:80px" value="0.00" required="required"/></td>
      </tr>
    <tr>
      <td id="fuente1">Ajustes</td>
      <td id="fuente1">$
        <input type="number" name="cif_250[]" id="cif_250[]" min="0" step="0.01" style=" width:80px" value="0.00" required="required"/></td>
      <td id="fuente1">$
        <input type="number" name="cif_500[]" id="cif_500[]" min="0" step="0.01" style=" width:80px" value="0.00" required="required"/></td>
      <td id="fuente1">$
        <input type="number" name="cif_1000[]" id="cif_1000[]" min="0" step="0.01" style=" width:80px" value="0.00" required="required"/></td>
      <td id="fuente1">$
        <input type="number" name="cif_4000[]" id="cif_4000[]" min="0" step="0.01" style=" width:80px" value="0.00" required="required"/></td>
      </tr>
    <tr>
      <td id="dato1">Costo cif segun promedio real</td>
      <td id="dato1">$
        <input type="number" name="cif_250[]" id="cif_250[]" min="0" step="0.01" style=" width:80px" value="0.00" required="required"/></td>
      <td id="dato1">$
        <input type="number" name="cif_500[]" id="cif_500[]" min="0" step="0.01" style=" width:80px" value="0.00" required="required"/></td>
      <td id="dato1">$
        <input type="number" name="cif_1000[]" id="cif_1000[]" min="0" step="0.01" style=" width:80px" value="0.00" required="required"/></td>
      <td id="dato1">$
        <input type="number" name="cif_4000[]" id="cif_4000[]" min="0" step="0.01" style=" width:80px" value="0.00" required="required"/></td>
      </tr>
    <tr>
      <td id="fuente1">Costo cif segun produccion y ajuste</td>
      <td id="fuente1">$
        <input type="number" name="cif_250[]" id="cif_250[]" min="0" step="0.01" style=" width:80px" value="0.00" required="required"/></td>
      <td id="fuente1">$
        <input type="number" name="cif_500[]" id="cif_500[]" min="0" step="0.01" style=" width:80px" value="0.00" required="required"/></td>
      <td id="fuente1">$
        <input type="number" name="cif_1000[]" id="cif_1000[]" min="0" step="0.01" style=" width:80px" value="0.00" required="required"/></td>
      <td id="fuente1">$
        <input type="number" name="cif_4000[]" id="cif_4000[]" min="0" step="0.01" style=" width:80px" value="0.00" required="required"/></td>
      </tr>
    <tr>
      <td id="fuente1">&nbsp;</td>
      <td id="fuente1">&nbsp;</td>
      <td id="fuente1">&nbsp;</td>
      <td id="fuente1">&nbsp;</td>
      <td id="fuente1">&nbsp;</td>
      </tr>
    <tr>
      <td colspan="5" id="fuente2"><input type="hidden" name="MM_insert" value="form2" />        <input type="submit" name="button" id="button" value="Guardar" /></td>
      </tr>                
    </table>     
                </fieldset>
              </form>
            </td>     
          </tr>
        </table>
      </td>
  </tr>
</table
  ></div>
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
?>
