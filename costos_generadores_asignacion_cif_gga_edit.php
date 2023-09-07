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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE Tbl_generadores_valor SET maquina_gv=%s, valor_gv=%s WHERE id_gv=%s", 
                       GetSQLValueString($_POST['maquina_gv'], "int"),                  
                       GetSQLValueString($_POST['valor_gv'], "double"),
                       GetSQLValueString($_POST['id_gv'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "costos_generadores_asignacion_cif_gga_edit.php?editar=0";
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

$colname_generadores_inicio = "-1";
if (isset($_GET['fecha_ini_gv'])) {
  $colname_generadores_inicio = (get_magic_quotes_gpc()) ? $_GET['fecha_ini_gv'] : addslashes($_GET['fecha_ini_gv']);
}
$colname_generadores_fin = "-1";
if (isset($_GET['fecha_fin_gv'])) {
  $colname_generadores_fin = (get_magic_quotes_gpc()) ? $_GET['fecha_fin_gv'] : addslashes($_GET['fecha_fin_gv']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_generadores_edit = sprintf("SELECT * FROM Tbl_generadores_valor WHERE Tbl_generadores_valor.fecha_ini_gv = '%s' AND fecha_fin_gv='%s' ORDER BY id_generadores_gv ASC", $colname_generadores_inicio, $colname_generadores_fin);
$generadores_edit = mysql_query($query_generadores_edit, $conexion1) or die(mysql_error());
$row_generadores_edit = mysql_fetch_assoc($generadores_edit);
$totalRows_generadores_edit = mysql_num_rows($generadores_edit);
//CARGA EL ID DEL QUE VA A EDITAR
$colname_valor_edit = "-1";
if (isset($_GET['id_gv'])) {
  $colname_valor_edit = (get_magic_quotes_gpc()) ? $_GET['id_gv'] : addslashes($_GET['id_gv']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_valor_edit = sprintf("SELECT * FROM Tbl_generadores_valor WHERE id_gv = %s", $colname_valor_edit);
$valor_edit = mysql_query($query_valor_edit, $conexion1) or die(mysql_error());
$row_valor_edit = mysql_fetch_assoc($valor_edit);
$totalRows_valor_edit = mysql_num_rows($valor_edit);
//MAQUINAS
mysql_select_db($database_conexion1, $conexion1);
$query_maquinas = "SELECT * FROM maquina ORDER BY id_maquina DESC";
$maquinas= mysql_query($query_maquinas, $conexion1) or die(mysql_error());
$row_maquinas = mysql_fetch_assoc($maquinas);
$totalRows_maquinas = mysql_num_rows($maquinas);
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
    <td colspan="3" id="fuente1"><?php $id=$_GET['id']; 
  if($id >= '1') { ?> <div id="acceso1"> <?php echo "ELIMINACION COMPLETA"; ?> </div> <?php }
  if($id == '0') { ?><div id="numero1"> <?php echo "ERROR AL ELIMINAR"; ?> </div> <?php }?></td>
    </tr>
  <tr>
    <td id="fuente1">&nbsp;</td>
    <td id="fuente1">&nbsp;</td>
    <td id="fuente3">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" id="dato2"><form method="POST" name="form1" action="<?php echo $editFormAction; ?>">
      <table>
        <tr>
          <td colspan="2" id="fuente1">FECHA INICIO:</td>
          <td colspan="2" id="fuente1">FECHA FIN:</td>
          <td colspan="2" id="fuente1">&nbsp;</td>
          </tr>
        <tr>
          <td colspan="2" id="numero1"><?php echo $_GET['fecha_ini_gv']; ?></td>
          <td colspan="4" id="numero1"><?php echo $_GET['fecha_fin_gv']; ?></td>
          </tr>
        <tr>
          <td id="fuente2">ITEMS</td>
          <td id="fuente2"><a href="costos_generadores_cif_gga.php" target="_blank" title="ADD GENERADORES CIF Y GGA">NOMBRE GENERADOR</a></td>
          <td id="fuente2"><a href="maquinas.php" target="_blank" title="ADD MAQUINAS">MAQUINA</a></td>
          <td id="fuente2">VALOR NETO</td>
          <td id="fuente2">CATEG.</td>
          </tr>
        <?php do { ?>
          <tr id="tr3">
            <td id="detalle1"><a href="costos_generadores_asignacion_cif_gga_edit.php?fecha_ini_gv=<?php echo $_GET['fecha_ini_gv']; ?>&amp;fecha_fin_gv=<?php echo $_GET['fecha_fin_gv']; ?>&amp;id_gv=<?php echo $row_generadores_edit['id_generadores_gv'];?>" target="_top" style="text-decoration:none; color:#000000"><?php $item=$item+1;echo $item; ?></a></td>
            <td id="detalle1"><a href="costos_generadores_asignacion_cif_gga_edit.php?fecha_ini_gv=<?php echo $_GET['fecha_ini_gv']; ?>&amp;fecha_fin_gv=<?php echo $_GET['fecha_fin_gv']; ?>&amp;id_gv=<?php echo $row_generadores_edit['id_generadores_gv'];?>" target="_top" style="text-decoration:none; color:#000000">
                  <?php  
				  $id_g=$row_generadores_edit['id_generadores_gv'];
				  $sqlgen="SELECT * FROM Tbl_generadores WHERE id_generadores='$id_g'";
				  $resultgen= mysql_query($sqlgen);
				  $numgen= mysql_num_rows($resultgen);
				  if($numgen >='1')
				  { 
				  $nombre = mysql_result($resultgen, 0, 'nombre_generadores');echo $nombre; }else{echo "N.A";
				  }?> </a></td>
            <td id="detalle2"><a href="costos_generadores_asignacion_cif_gga_edit.php?fecha_ini_gv=<?php echo $_GET['fecha_ini_gv']; ?>&amp;fecha_fin_gv=<?php echo $_GET['fecha_fin_gv']; ?>&amp;id_gv=<?php echo $row_generadores_edit['id_generadores_gv'];?>" target="_top" style="text-decoration:none; color:#000000">
			      <?php  
				  $id_m=$row_generadores_edit['maquina_gv'];
				  $sqlm="SELECT * FROM maquina WHERE id_maquina='$id_m'";
				  $resultm= mysql_query($sqlm);
				  $numm= mysql_num_rows($resultm);
				  if($numm >='1')
				  { 
				  $nombre = mysql_result($resultm, 0, 'nombre_maquina');echo $nombre; }else{echo "N.A";
				  }?></a></td>
            <td id="detalle2"><a href="costos_generadores_asignacion_cif_gga_edit.php?fecha_ini_gv=<?php echo $_GET['fecha_ini_gv']; ?>&amp;fecha_fin_gv=<?php echo $_GET['fecha_fin_gv']; ?>&amp;id_gv=<?php echo $row_generadores_edit['id_generadores_gv'];?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_generadores_edit['valor_gv']; ?></a></td>
            <td id="detalle2"><a href="costos_generadores_asignacion_cif_gga_edit.php?fecha_ini_gv=<?php echo $_GET['fecha_ini_gv']; ?>&amp;fecha_fin_gv=<?php echo $_GET['fecha_fin_gv']; ?>&amp;id_gv=<?php echo $row_generadores_edit['id_generadores_gv'];?>" target="_top" style="text-decoration:none; color:#000000">
                  <?php  
				  $id_g=$row_generadores_edit['id_generadores_gv'];
				  $sqlgen="SELECT * FROM Tbl_generadores WHERE id_generadores='$id_g'";
				  $resultgen= mysql_query($sqlgen);
				  $numgen= mysql_num_rows($resultgen);
				  if($numgen >='1')
				  { 
				  $nombre_cat = mysql_result($resultgen, 0, 'categoria_generadores');echo $nombre_cat; }else{echo "N.A";
				  }?> </a></td>
            </tr>
          <?php } while ($row_generadores_edit = mysql_fetch_assoc($generadores_edit)); ?>
        <tr>
          <td id="dato2">&nbsp;</td>
          <td id="dato2">&nbsp;</td>
          <td id="dato2">&nbsp;</td>
          <td id="dato2">&nbsp;</td>
          </tr>
        <tr>
          <td colspan="4" id="dato2"></td>
          </tr>
        </table>
    </form></td>
    <td id="dato2" valign="top"><?php $editar=$_GET['editar']; $id_gv= $_GET['id_gv']; if($id_gv!='' && $id_gv!='0' && $editar!='0') { ?>
      <form method="POST" name="form2" action="<?php echo $editFormAction; ?>">
        <table align="center" >
          <tr>
            <td id="fuente1">&nbsp;</td>
            <td id="fuente1">&nbsp;</td>
            <td id="fuente1">&nbsp;</td>
            </tr>
          <tr>
            <td id="fuente1">&nbsp;</td>
            <td id="fuente1">&nbsp;</td>
            <td id="fuente1">&nbsp;</td>
          </tr>
          <tr>
            <td id="fuente1">&nbsp;</td>
            <td id="fuente1">&nbsp;</td>
            <td id="fuente1">&nbsp;</td>
            </tr>
          <tr>
            <td id="fuente2">N&deg;</td>
            <td id="fuente2">MAQUINA</td>
            <td id="fuente2">VALOR NETO</td>
            </tr>
          <tr>
            <td id="fuente1"><input name="id_gv" type="text" id="id_gv" value="<?php echo $row_valor_edit['id_gv']; ?>" size="2" readonly="readonly"/></td>
            <td id="fuente1"><select name="maquina_gv" id="maquina_gv" style="width:80px">
              <option value=""<?php if (!(strcmp("",$row_valor_edit['maquina_gv']))) {echo "selected=\"selected\"";} ?>>N.A</option>
              <?php
				   do {  
				   ?>
              <option value="<?php echo $row_maquinas['id_maquina']?>"<?php if (!(strcmp($row_maquinas['id_maquina'], $row_valor_edit['maquina_gv']))) {echo "selected=\"selected\"";} ?>><?php echo $row_maquinas['nombre_maquina']?></option>
              <?php
				   } while ($row_maquinas = mysql_fetch_assoc($maquinas));
				   $rows = mysql_num_rows($maquinas);
					 if($rows > 0) {
					  mysql_data_seek($maquinas, 0);
					  $row_maquinas = mysql_fetch_assoc($maquinas);
					  }
				    ?>
            </select></td>
            <td id="fuente2"><input name="valor_gv" type="number" min="0" style="width:80px" step="0.01" value="<?php if($row_valor_edit['valor_gv']=="0.00"){echo " ";}else{echo $row_valor_edit['valor_gv'];} ?>"/></td>
            </tr>
          <tr>
            <td colspan="3" id="dato2"><input name="submit" type="submit" value="ACTUALIZAR VALOR" /></td>
            </tr>
          </table>
        <input type="hidden" name="MM_update" value="form2">
        <input type="hidden" name="id_gv" value="<?php echo $row_valor_edit['id_gv']; ?>">
        </form><?php } ?></td>
  </tr>
</table>
	</td>
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

mysql_free_result($generadores);

mysql_free_result($generadores_edit);

mysql_free_result($ultimo);


?>
