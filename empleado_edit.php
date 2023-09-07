<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
?> 
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

$conexion = new ApptivaDB();

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE empleado SET codigo_empleado=%s, nombre_empleado=%s, apellido_empleado=%s, cedula_empleado=%s,empresa_empleado=%s, horas_empleado=%s, tipo_empleado=%s WHERE id_empleado=%s",
                       GetSQLValueString($_POST['codigo_empleado'], "int"),
                       GetSQLValueString($_POST['nombre_empleado'], "text"),
                       GetSQLValueString($_POST['apellido_empleado'], "text"),
                       GetSQLValueString($_POST['cedula_empleado'], "int"),
					             GetSQLValueString($_POST['empresa_empleado'], "text"),
					             GetSQLValueString($_POST['horas_empleado'], "int"),
                       GetSQLValueString($_POST['tipo_empleado'], "int"),
                       GetSQLValueString($_POST['id_empleado'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());
  
  $updateSQLEmpleado = sprintf("UPDATE TblProcesoEmpleado SET proceso_empleado=%s WHERE codigo_empleado=%s",
                               GetSQLValueString($_POST['tipo_empleado'], "int"),
                               GetSQLValueString($_POST['codigo_empleado'], "txt"));
  mysql_select_db($database_conexion1, $conexion1);
  $ResultEmpl = mysql_query($updateSQLEmpleado, $conexion1) or die(mysql_error());


  $updateGoTo = "proceso_empleados_listado.php";
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
$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario); 

$colname_editar_empleado = "-1";
if (isset($_GET['id_empleado'])) {
  $colname_editar_empleado = (get_magic_quotes_gpc()) ? $_GET['id_empleado'] : addslashes($_GET['id_empleado']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_editar_empleado = sprintf("SELECT * FROM empleado WHERE id_empleado = %s", $colname_editar_empleado);
$editar_empleado = mysql_query($query_editar_empleado, $conexion1) or die(mysql_error());
$row_editar_empleado = mysql_fetch_assoc($editar_empleado);
$totalRows_editar_empleado = mysql_num_rows($editar_empleado);

mysql_select_db($database_conexion1, $conexion1);
$query_tipo_empleado = "SELECT * FROM empleado_tipo ORDER BY nombre_tipo_empleado ASC";
$tipo_empleado = mysql_query($query_tipo_empleado, $conexion1) or die(mysql_error());
$row_tipo_empleado = mysql_fetch_assoc($tipo_empleado);
$totalRows_tipo_empleado = mysql_num_rows($tipo_empleado);

mysql_select_db($database_conexion1, $conexion1);
$query_factor = "SELECT * FROM TblFactorP ORDER BY fecha_fp DESC";
$factor = mysql_query($query_factor, $conexion1) or die(mysql_error());
$row_factor = mysql_fetch_assoc($factor);
$totalRows_factor = mysql_num_rows($factor);
?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>

<!-- desde aqui para listados nuevos -->
<link rel="stylesheet" type="text/css" href="css/desplegable.css" />
<link rel="stylesheet" type="text/css" href="css/general.css"/>

<!-- sweetalert -->
<script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
<link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">
<!-- jquery -->
<script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>
<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script> 
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>

<!-- select2 -->
<link href="select2/css/select2.min.css" rel="stylesheet"/>
<script src="select2/js/select2.min.js"></script>

<!-- css Bootstrap-->
<link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"> 

</head>
<body>
<?php echo $conexion->header('listas'); ?>
 <ul id="menuhorizontal">
                    <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                    <li><a href="menu.php">MENU PRINCIPAL</a></li>
                    <li><a href="produccion.php">PRODUCCION</a></li>
                  </ul> 

                  <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
                    <table class="table table-bordered table-sm">
                      <tr>
                        <td colspan="3">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="3" id="titulo2">ACTUALIZAR EMPLEADO</td>
                      </tr>
                      <tr>
                        <td id="fuente1">&nbsp;</td>
                        <td id="fuente1"><a href="javascript:eliminar1('id_empleado',<?php echo $row_editar_empleado['id_empleado']; ?>,'empleado_edit.php')"><img src="images/por.gif" border="0" style="cursor:hand;" alt="ELIMINAR" /></a><a href="empleado_add.php"><img src="images/mas.gif" alt="ADD EMPLEADO" border="0" style="cursor:hand;"></a><a href="empleados.php"><img src="images/opciones.gif" alt="EMPLEADOS" border="0" style="cursor:hand;"></a><a href="aportes.php"><img src="images/a.gif" alt="APORTES" title="APORTES" border="0" style="cursor:hand;"></a><a href="factor_prestacional_add.php"><img src="images/f.gif" alt="FACTORES" title="FACTORES" border="0" style="cursor:hand;"></a><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
                        <td id="fuente2">&nbsp;</td>
                      </tr>
<tr>
                        <td id="fuente1">Dias: <?php echo $row_factor['dias_fp']; ?><input type="hidden" name="dias_fp" id="dias_fp" size="5" value="<?php echo $row_factor['dias_fp']; ?>"></td>
                        <td id="fuente1">Dominicales y Festivos: <?php echo $row_factor['domin_fest_fp']; ?>
                        <input type="hidden" name="domin_fest_fp" id="domin_fest_fp" size="5" value="<?php echo $row_factor['domin_fest_fp']; ?>"></td>
                        <td id="fuente1">Vacaci&oacute;nes: <?php echo $row_factor['vacacion_fp']; ?>
                        <input type="hidden" name="vacacion_fp" id="vacacion_fp" size="5" value="<?php echo $row_factor['vacacion_fp']; ?>"></td>
                      </tr>                      
                      <tr>
                        <td id="fuente1">CODIGO</td>
                        <td colspan="2" id="fuente1"><input type="text" name="codigo_empleado" value="<?php echo $row_editar_empleado['codigo_empleado']; ?>" size="10"> </td>
                      </tr>
                      <tr>
                        <td id="fuente1">NOMBRES</td>
                        <td colspan="2" id="fuente1"><input type="text" name="nombre_empleado" value="<?php echo $row_editar_empleado['nombre_empleado']; ?>" size="30" onKeyUp="mayusculaPrimeras(this)" ></td>
                      </tr>
                      <tr>
                        <td id="fuente1">APELLIDOS</td>
                        <td colspan="2" id="fuente1"><input type="text" name="apellido_empleado" value="<?php echo $row_editar_empleado['apellido_empleado']; ?>" onKeyUp="mayusculaPrimeras(this)"  size="30"></td>
                      </tr>
                      <tr>
                        <td id="fuente1">CEDULA</td>
                        <td colspan="2" id="fuente1"><input type="number" name="cedula_empleado" min="0" value="<?php echo $row_editar_empleado['cedula_empleado']; ?>" size="30"></td>
                      </tr>
                      <tr>
                        <td id="fuente1">EMPRESA</td>
                        <td colspan="2" id="fuente1"><input name="empresa_empleado" type="text" id="empresa_empleado" value="<?php echo $row_editar_empleado['empresa_empleado']; ?>" size="30" onKeyUp="mayusculaPrimeras(this)"  required></td>
                      </tr>
                      <tr>
                        <td id="fuente1">HORAS LABORABLES</td>
                        <td colspan="2" id="fuente1"><input name="horas_empleado" type="number" id="horas_empleado" step="1" max="24" value="8" size="30" required></td>
                      </tr>
                      <tr>
                        <td id="fuente1">CARGO DE EMPLEADO </td>
                        <td colspan="2" id="fuente1"><select name="tipo_empleado">
                            <?php
                            do {  
                              ?>
                              <option value="<?php echo $row_tipo_empleado['id_empleado_tipo']?>"<?php if (!(strcmp($row_tipo_empleado['id_empleado_tipo'], $row_editar_empleado['tipo_empleado']))) {echo "selected=\"selected\"";} ?>><?php echo $row_tipo_empleado['nombre_tipo_empleado']?></option>
                              <?php
                            } while ($row_tipo_empleado = mysql_fetch_assoc($tipo_empleado));
                            $rows = mysql_num_rows($tipo_empleado);
                            if($rows > 0) {
                              mysql_data_seek($tipo_empleado, 0);
                              $row_tipo_empleado = mysql_fetch_assoc($tipo_empleado);
                            }
                            ?>
                          </select></td>
                      </tr>
                      <tr>
                        <td colspan="3">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="3" id="fuente2"><input type="submit" value="ACTUALIZAR EMPLEADO"></td>
                      </tr>
                    </table>
                    <input type="hidden" name="id_empleado" value="<?php echo $row_editar_empleado['id_empleado']; ?>">
                    <input type="hidden" name="MM_update" value="form1">
                  </form> 
<?php echo $conexion->header('footer'); ?>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($editar_empleado);

mysql_free_result($tipo_empleado);
?>