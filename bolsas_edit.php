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
  $updateSQL = sprintf("UPDATE material_terminado_bolsas SET codigo_bolsa=%s, nombre_bolsa=%s, descripcion_bolsa=%s, id_medida_bolsa=%s, observacion_bolsa=%s, id_ref_bolsa=%s WHERE id_bolsa=%s",
                       GetSQLValueString($_POST['codigo_bolsa'], "text"),
                       GetSQLValueString($_POST['nombre_bolsa'], "text"),
                       GetSQLValueString($_POST['descripcion_bolsa'], "text"),
                       GetSQLValueString($_POST['id_medida_bolsa'], "int"),
                       GetSQLValueString($_POST['observacion_bolsa'], "text"),
                       GetSQLValueString($_POST['id_ref_bolsa'], "int"),
                       GetSQLValueString($_POST['id_bolsa'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "bolsas_vista.php?id_bolsa=" . $_POST['id_bolsa'] . "";
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

$colname_bolsas = "-1";
if (isset($_GET['id_bolsa'])) {
  $colname_bolsas = (get_magic_quotes_gpc()) ? $_GET['id_bolsa'] : addslashes($_GET['id_bolsa']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_bolsas = sprintf("SELECT * FROM material_terminado_bolsas WHERE id_bolsa = %s", $colname_bolsas);
$bolsas = mysql_query($query_bolsas, $conexion1) or die(mysql_error());
$row_bolsas = mysql_fetch_assoc($bolsas);
$totalRows_bolsas = mysql_num_rows($bolsas);

mysql_select_db($database_conexion1, $conexion1);
$query_referencias = "SELECT * FROM Tbl_referencia order by id_ref desc";
$referencias = mysql_query($query_referencias, $conexion1) or die(mysql_error());
$row_referencias = mysql_fetch_assoc($referencias);
$totalRows_referencias = mysql_num_rows($referencias);

mysql_select_db($database_conexion1, $conexion1);
$query_medidas = "SELECT * FROM medida ORDER BY nombre_medida ASC";
$medidas = mysql_query($query_medidas, $conexion1) or die(mysql_error());
$row_medidas = mysql_fetch_assoc($medidas);
$totalRows_medidas = mysql_num_rows($medidas);
?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
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
  <li><a href="compras.php">GESTION COMPRAS</a></li>
  </ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1">
      <form action="<?php echo $editFormAction; ?>" method="post" name="form1" onSubmit="MM_validateForm('codigo_bolsa','','R','nombre_bolsa','','R','id_ref_bolsa','','R','id_medida_bolsa','','R','descripcion_bolsa','','R');return document.MM_returnValue">
        <table id="tabla2">
          <tr>
            <td rowspan="6" id="dato2"><img src="images/logoacyc.jpg" /></td>
            <td colspan="2" id="titulo2">EDITAR PRODUCTO TERMINADO (BOLSAS)</td>
            </tr>
          <tr>
            <td id="fuente2">Consecutivo N&deg; <?php echo $row_bolsas['id_bolsa']; ?></td>
            <td id="dato2"><img src="images/ciclo1.gif" alt="RESTAURAR" border="0" style="cursor:hand;" onClick="window.history.go()"/><a href="bolsas_vista.php?id_bolsa=<?php echo $row_bolsas['id_bolsa']; ?>"><img src="images/hoja.gif" alt="VISTA IMPRESION" border="0" style="cursor:hand;"></a><a href="javascript:eliminar1('id_bolsa',<?php echo $row_bolsas['id_bolsa']; ?>,'bolsas_edit.php')"><img src="images/por.gif" alt="ELIMINAR" border="0" style="cursor:hand;"></a><a href="bolsas.php"><img src="images/b.gif" alt="BOLSAS" border="0" /></a><a href="bolsas_busqueda.php"><img src="images/embudo.gif" style="cursor:hand;" alt="FILTRO" border="0" /></a><a href="bolsas_oc.php"><img src="images/o.gif" style="cursor:hand;" alt="O.C. (BOLSAS)" border="0" /></a></td>
          </tr>
          <tr>
            <td id="fuente1">CODIGO DE LA BOLSA </td>
            <td id="fuente1">REFERENCIA</td>
          </tr>
          <tr>
            <td id="dato1"><input type="text" name="codigo_bolsa" value="<?php echo $row_bolsas['codigo_bolsa']; ?>" size="40"></td>
            <td id="dato1"><select name="id_ref_bolsa" id="id_ref_bolsa">
              <option value="" <?php if (!(strcmp("", $row_bolsas['id_ref_bolsa']))) {echo "selected=\"selected\"";} ?>>SELECCIONE</option>
              <?php
do {  
?>
              <option value="<?php echo $row_referencias['id_ref']?>"<?php if (!(strcmp($row_referencias['id_ref'], $row_bolsas['id_ref_bolsa']))) {echo "selected=\"selected\"";} ?>><?php echo $row_referencias['cod_ref']?></option>
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
            <td id="fuente1">NOMBRE DE LA BOLSA </td>
            <td id="fuente1">MEDIDA</td>
          </tr>
          <tr>
            <td><input type="text" name="nombre_bolsa" value="<?php echo $row_bolsas['nombre_bolsa']; ?>" size="40"></td>
            <td><select name="id_medida_bolsa" id="id_medida_bolsa">
              <option value=""  <?php if (!(strcmp("", $row_bolsas['id_medida_bolsa']))) {echo "selected=\"selected\"";} ?>>SELECCIONE</option>
              <?php
do {  
?><option value="<?php echo $row_medidas['id_medida']?>"<?php if (!(strcmp($row_medidas['id_medida'], $row_bolsas['id_medida_bolsa']))) {echo "selected=\"selected\"";} ?>><?php echo $row_medidas['nombre_medida']?></option>
                <?php
} while ($row_medidas = mysql_fetch_assoc($medidas));
  $rows = mysql_num_rows($medidas);
  if($rows > 0) {
      mysql_data_seek($medidas, 0);
	  $row_medidas = mysql_fetch_assoc($medidas);
  }
?>
            </select></td>
          </tr>
          <tr>
            <td colspan="3" id="fuente1">DESCRIPCION DE LA BOLSA </td>
            </tr>
          <tr>
            <td colspan="3" id="dato1"><textarea name="descripcion_bolsa" cols="70" rows="2"><?php echo $row_bolsas['descripcion_bolsa']; ?></textarea></td>
            </tr>
          <tr>
            <td colspan="3" id="fuente1">OBSERVACIONES</td>
            </tr>
          <tr>
            <td colspan="3"><textarea name="observacion_bolsa" cols="70" rows="2"><?php echo $row_bolsas['observacion_bolsa']; ?></textarea></td>
            </tr>
          <tr>
            <td colspan="3" id="dato2"><input type="submit" value="Actualizar BOLSA"></td>
            </tr>
        </table>
        <input type="hidden" name="MM_update" value="form1">
        <input type="hidden" name="id_bolsa" value="<?php echo $row_bolsas['id_bolsa']; ?>">
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

mysql_free_result($bolsas);

mysql_free_result($referencias);

mysql_free_result($medidas);
?>