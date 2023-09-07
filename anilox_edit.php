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
  $updateSQL = sprintf("UPDATE anilox SET codigo_insumo=%s, descripcion_insumo=%s, clase_insumo=%s, medida_insumo=%s, tipo_insumo=%s, valor_unitario_insumo=%s, stok_insumo=%s, estado_insumo=%s WHERE id_insumo=%s",
                       GetSQLValueString($_POST['codigo_insumo'], "text"),
                       GetSQLValueString($_POST['descripcion_insumo'], "text"),
                       GetSQLValueString($_POST['clase_insumo'], "int"),
                       GetSQLValueString($_POST['medida_insumo'], "text"),
                       GetSQLValueString($_POST['tipo_insumo'], "text"),
                       GetSQLValueString($_POST['valor_unitario_insumo'], "double"),
					   GetSQLValueString($_POST['stok_insumo'], "int"),
					   GetSQLValueString($_POST['estado_insumo'], "int"),
                       GetSQLValueString($_POST['id_insumo'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "anilox.php";
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

$colname_insumo_edit = "-1";
if (isset($_GET['id_insumo'])) {
  $colname_insumo_edit = (get_magic_quotes_gpc()) ? $_GET['id_insumo'] : addslashes($_GET['id_insumo']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_insumo_edit = sprintf("SELECT * FROM anilox WHERE id_insumo = %s", $colname_insumo_edit);
$insumo_edit = mysql_query($query_insumo_edit, $conexion1) or die(mysql_error());
$row_insumo_edit = mysql_fetch_assoc($insumo_edit);
$totalRows_insumo_edit = mysql_num_rows($insumo_edit);

mysql_select_db($database_conexion1, $conexion1);
$query_tipo = "SELECT * FROM tipo ORDER BY nombre_tipo ASC";
$tipo = mysql_query($query_tipo, $conexion1) or die(mysql_error());
$row_tipo = mysql_fetch_assoc($tipo);
$totalRows_tipo = mysql_num_rows($tipo);

mysql_select_db($database_conexion1, $conexion1);
$query_clases = "SELECT * FROM clase ORDER BY nombre_clase ASC";
$clases = mysql_query($query_clases, $conexion1) or die(mysql_error());
$row_clases = mysql_fetch_assoc($clases);
$totalRows_clases = mysql_num_rows($clases);

mysql_select_db($database_conexion1, $conexion1);
$query_medidas = "SELECT * FROM medida ORDER BY nombre_medida ASC";
$medidas = mysql_query($query_medidas, $conexion1) or die(mysql_error());
$row_medidas = mysql_fetch_assoc($medidas);
$totalRows_medidas = mysql_num_rows($medidas);
   
mysql_select_db($database_conexion1, $conexion1);
$query_proveedores = "SELECT * FROM proveedor ORDER BY proveedor_p ASC";
$proveedores = mysql_query($query_proveedores, $conexion1) or die(mysql_error());
$row_proveedores = mysql_fetch_assoc($proveedores);
$totalRows_proveedores = mysql_num_rows($proveedores);

?><html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
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
  <li><a href="compras.php">GESTION COMPRAS</a></li>
  </ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1">
      <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
        <table id="tabla2">
          <tr>
            <td rowspan="7" id="dato2"><img src="images/logoacyc.jpg"></td>
            <td id="subtitulo">EDITAR ANILOX </td>
            <td id="fuente2"><a href="javascript:eliminar1('id_anilox',<?php echo $row_insumo_edit['id_insumo']; ?>,'anilox_edit.php')"><img src="images/por.gif" alt="ELIMINAR" border="0" style="cursor:hand;"></a><a href="anilox.php"><img src="images/cat.gif" alt="INSUMOS" border="0" style="cursor:hand;"></a><a href="insumos_busqueda.php"> </a><img src="images/ciclo1.gif" onClick="window.history.go()" style="cursor:hand;" > </td>
          </tr>
          <tr>
            <td id="fuente1">CODIGO DEL ANILOX</td>
            <td id="fuente1">TIPO DE ANILOX</td>
          </tr>
          <tr>
            <td id="fuente1"><input type="number" min="1" step="1" style="width:100px" name="codigo_insumo" value="<?php echo $row_insumo_edit['codigo_insumo']; ?>" required ></td>
            <td id="fuente1"><select name="tipo_insumo">
            <option value=""<?php if (!(strcmp("", $row_insumo_edit['tipo_insumo']))) {echo "selected=\"selected\"";} ?>></option>
              <?php
do {  
?>
              <option value="<?php echo $row_tipo['id_tipo']?>"<?php if (!(strcmp($row_tipo['id_tipo'], $row_insumo_edit['tipo_insumo']))) {echo "selected=\"selected\"";} ?>><?php echo $row_tipo['nombre_tipo']?></option>
              <?php
} while ($row_tipo = mysql_fetch_assoc($tipo));
  $rows = mysql_num_rows($tipo);
  if($rows > 0) {
      mysql_data_seek($tipo, 0);
	  $row_tipo = mysql_fetch_assoc($tipo);
  }
?>
            </select></td>
          </tr>
          <tr>
            <td id="fuente1">DESCRIPCION</td>
            <td id="fuente1">CLASE DE ANILOX</td>
          </tr>
          <tr>
            <td id="fuente1"><input type="text" name="descripcion_insumo" required value="<?php echo $row_insumo_edit['descripcion_insumo']; ?>" size="30" onChange="MayusculaSinEspacios(this)"></td>
            <td id="fuente1"><select name="clase_insumo">
            <option value=""<?php if (!(strcmp("", $row_insumo_edit['clase_insumo']))) {echo "selected=\"selected\"";} ?>></option>
              <?php
do {  
?>
              <option value="<?php echo $row_clases['id_clase']?>"<?php if (!(strcmp($row_clases['id_clase'], $row_insumo_edit['clase_insumo']))) {echo "selected=\"selected\"";} ?>><?php echo $row_clases['nombre_clase']?></option>
              <?php
} while ($row_clases = mysql_fetch_assoc($clases));
  $rows = mysql_num_rows($clases);
  if($rows > 0) {
      mysql_data_seek($clases, 0);
	  $row_clases = mysql_fetch_assoc($clases);
  }
?>
            </select></td>
          </tr>
          <tr>
            <td id="fuente1">UNIDAD DE MEDIDA </td>
            <td id="fuente1">VALOR UNITARIO </td>
          </tr>
          <tr>
            <td id="fuente1"><select name="medida_insumo">
            <option value=""<?php if (!(strcmp("", $row_insumo_edit['medida_insumo']))) {echo "selected=\"selected\"";} ?>></option>
              <?php
do {  
?>
              <option value="<?php echo $row_medidas['id_medida']?>"<?php if (!(strcmp($row_medidas['id_medida'], $row_insumo_edit['medida_insumo']))) {echo "selected=\"selected\"";} ?>><?php echo $row_medidas['nombre_medida']?></option>
              <?php
} while ($row_medidas = mysql_fetch_assoc($medidas));
  $rows = mysql_num_rows($medidas);
  if($rows > 0) {
      mysql_data_seek($medidas, 0);
	  $row_medidas = mysql_fetch_assoc($medidas);
  }
?>
            </select></td>
            <td id="fuente1"><input type="text" name="valor_unitario_insumo" required value="<?php echo $row_insumo_edit['valor_unitario_insumo']; ?>" size="30"></td>
          </tr>
           <tr>
            <td id="fuente1"><strong>PROVEEDOR:</strong></td>
            <td id="fuente1">ESTADO</td>
            <td id="fuente1">STOCK DEL PRODUCTO / KG</td>
          </tr>
          <tr>
            <td id="fuente1"><select name="id_p" id="id_p" style="width:220px">
              <option value="0" <?php if (!(strcmp(0, $_GET['id_p']))) {echo "selected=\"selected\"";} ?>>PROVEEDOR</option>
              <?php
do {  
?>
              <option value="<?php echo $row_proveedores['id_p']?>"<?php if (!(strcmp($row_proveedores['id_p'], $_GET['id_p']))) {echo "selected=\"selected\"";} ?>><?php echo $row_proveedores['proveedor_p']?></option>
              <?php
} while ($row_proveedores = mysql_fetch_assoc($proveedores));
  $rows = mysql_num_rows($proveedores);
  if($rows > 0) {
      mysql_data_seek($proveedores, 0);
	  $row_proveedores = mysql_fetch_assoc($proveedores);
  }
?> </select>
 <?php
/* $item=0;
   do { 
   $item ++;
  echo $item."-".$row_proveedores['proveedor_p']."<BR>";
  } while ($row_proveedores = mysql_fetch_assoc($proveedores)); */      
    ?>        
           
            </td>
            <td id="dato4"><label for="estado_insumo"></label>
              <select name="estado_insumo" id="estado_insumo">
                <option value="0"<?php if (!(strcmp("0", $row_insumo_edit['estado_insumo']))) {echo "selected=\"selected\"";} ?>>ACTIVO</option>
                <option value="1"<?php if (!(strcmp("1", $row_insumo_edit['estado_insumo']))) {echo "selected=\"selected\"";} ?>>INACTIVO</option>
              </select></td>
            <td id="dato1"><input type="number" name="stok_insumo" id="stok_insumo" min="0"step="any" required value="<?php echo $row_insumo_edit['stok_insumo']; ?>" style=" width:100px" /></td>
          </tr>          <tr>
            <td colspan="3" id="fuente2"><input type="submit" value="Actualizar Insumo"></td>
            </tr>
        </table>
        <input type="hidden" name="MM_update" value="form1">
        <input type="hidden" name="id_insumo" value="<?php echo $row_insumo_edit['id_insumo']; ?>">
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

mysql_free_result($insumo_edit);

mysql_free_result($tipo);

mysql_free_result($clases);

mysql_free_result($medidas);
?>
