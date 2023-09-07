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
  $updateSQL = sprintf("UPDATE proveedor_mejora SET id_p_pm=%s, plan_mejora_pm=%s, responsable_pm=%s, fecha_pm=%s, cumplimiento_pm=%s WHERE id_pm=%s",
                       GetSQLValueString($_POST['id_p_pm'], "int"),
                       GetSQLValueString($_POST['plan_mejora_pm'], "text"),
                       GetSQLValueString($_POST['responsable_pm'], "text"),
                       GetSQLValueString($_POST['fecha_pm'], "date"),
                       GetSQLValueString($_POST['cumplimiento_pm'], "text"),
                       GetSQLValueString($_POST['id_pm'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "proveedor_mejoras.php?id_p=" . $_POST['id_p_pm'] . "";
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

$colname_plan_mejora = "-1";
if (isset($_GET['id_pm'])) {
  $colname_plan_mejora = (get_magic_quotes_gpc()) ? $_GET['id_pm'] : addslashes($_GET['id_pm']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_plan_mejora = sprintf("SELECT * FROM proveedor_mejora WHERE id_pm = %s", $colname_plan_mejora);
$plan_mejora = mysql_query($query_plan_mejora, $conexion1) or die(mysql_error());
$row_plan_mejora = mysql_fetch_assoc($plan_mejora);
$totalRows_plan_mejora = mysql_num_rows($plan_mejora);

$colname_proveedor = "-1";
if (isset($_GET['id_pm'])) {
  $colname_proveedor = (get_magic_quotes_gpc()) ? $_GET['id_pm'] : addslashes($_GET['id_pm']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_proveedor = sprintf("SELECT * FROM proveedor_mejora, proveedor WHERE proveedor_mejora.id_pm = '%s' AND proveedor_mejora.id_p_pm = proveedor.id_p", $colname_proveedor);
$proveedor = mysql_query($query_proveedor, $conexion1) or die(mysql_error());
$row_proveedor = mysql_fetch_assoc($proveedor);
$totalRows_proveedor = mysql_num_rows($proveedor);
?><html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
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
	<li><a href="compras.php">GESTION COMPRAS</a></li>	
	</ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center">
      <form action="<?php echo $editFormAction; ?>" method="post" name="form1" onSubmit="MM_validateForm('responsable_pm','','R','fecha_pm','','R','plan_mejora_pm','','R');return document.MM_returnValue">
        <table id="tabla2">
          <tr id="tr2">
            <td colspan="2" id="titulo2">PLAN DE MEJORA </td>
            </tr>
          <tr>
            <td colspan="2" id="fuente1"><strong>PROVEEDOR</strong> : <input name="id_p_pm" type="hidden" value="<?php echo $row_plan_mejora['id_p_pm']; ?>">
              <?php echo $row_proveedor['proveedor_p']; ?></td>
            </tr>
          <tr id="tr1">
            <td colspan="2" id="fuente1">PLAN DE MEJORA </td>
            </tr>
          <tr>
            <td colspan="2" id="dato1"><textarea name="plan_mejora_pm" cols="80" rows="5"><?php echo $row_plan_mejora['plan_mejora_pm']; ?></textarea></td>
            </tr>
          <tr id="tr1">
            <td id="fuente1">RESPONSABLE</td>
            <td id="fuente1">FECHA</td>
          </tr>
          <tr>
            <td id="dato1"><input type="text" name="responsable_pm" value="<?php echo $row_plan_mejora['responsable_pm']; ?>" size="30"></td>
            <td id="dato1"><input type="text" name="fecha_pm" value="<?php echo $row_plan_mejora['fecha_pm']; ?>" size="10"></td>
          </tr>
          <tr id="tr1">
            <td id="fuente1">CUMPLIMIENTO</td>
            <td><a href="javascript:eliminar1('id_pm',<?php echo $row_plan_mejora['id_pm']; ?>,'proveedor_mejora_edit.php')"><img src="images/por.gif" alt="ELIMINAR" border="0" style="cursor:hand;"></a><a href="proveedor_mejoras.php?id_p=<?php echo $row_proveedor['id_p']; ?>"><img src="images/m.gif" alt="PLAN DE MEJORAS" border="0" style="cursor:hand;"></a><a href="proveedor_mejora_add.php?id_p=<?php echo $row_proveedor['id_p']; ?>"><img src="images/mas.gif" alt="ADD PLAN MEJORAS" border="0" style="cursor:hand;"></a><a href="proveedor_busqueda.php"><img src="images/embudo.gif" alt="FILTRO" border="0" style="cursor:hand;"/></a></td>
          </tr>
          <tr>
            <td><select name="cumplimiento_pm" id="cumplimiento_pm">
              <option value="N.A." <?php if (!(strcmp("N.A.", $row_plan_mejora['cumplimiento_pm']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
              <option value="Si" <?php if (!(strcmp("Si", $row_plan_mejora['cumplimiento_pm']))) {echo "selected=\"selected\"";} ?>>Si</option>
              <option value="No" <?php if (!(strcmp("No", $row_plan_mejora['cumplimiento_pm']))) {echo "selected=\"selected\"";} ?>>No</option>
              <option value="Algunas veces" <?php if (!(strcmp("Algunas veces", $row_plan_mejora['cumplimiento_pm']))) {echo "selected=\"selected\"";} ?>>Algunas veces</option>
            </select></td>
            <td><input type="submit" value="Actualizar MEJORA"></td>
          </tr>
        </table>
        <input type="hidden" name="MM_update" value="form1">
        <input type="hidden" name="id_pm" value="<?php echo $row_plan_mejora['id_pm']; ?>">
      </form></td>
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

mysql_free_result($plan_mejora);

mysql_free_result($proveedor);
?>
