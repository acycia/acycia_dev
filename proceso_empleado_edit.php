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

  $updateSQLEmpleado = sprintf("UPDATE empleado SET tipo_empleado=%s WHERE codigo_empleado=%s",
                               GetSQLValueString($_POST['proceso_empleado'], "txt"),
                               GetSQLValueString($_POST['codigo_empl'], "txt"));
  mysql_select_db($database_conexion1, $conexion1);
  $ResultEmpl = mysql_query($updateSQLEmpleado, $conexion1) or die(mysql_error());
 
$updateSQL = sprintf("UPDATE TblProcesoEmpleado SET proceso_empleado=%s, sueldo_empleado=%s, aux_empleado=%s,  dias_empleado=%s,  fechainicial_empleado=%s, fechafinal_empleado=%s, estado_empleado=%s WHERE id_pem=%s",
            GetSQLValueString($_POST['proceso_empleado'], "txt"),
            GetSQLValueString($_POST['sueldo_empleado'], "int"),
            GetSQLValueString($_POST['aux_empleado'], "int"),
            GetSQLValueString($_POST['dias_empleado'], "int"),
            GetSQLValueString($_POST['fechainicial_empleado'], "date"),
            GetSQLValueString($_POST['fechafinal_empleado'], "date"),
            GetSQLValueString($_POST['estado_empleado'], "txt"),
            GetSQLValueString($_POST['id_pem'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());
 
  $updateSQL2 = sprintf("UPDATE TblAportes SET codigo_empl=%s, cesantias_porc=%s, cesantias=%s, interesCesantias_porc=%s, interesCesantias=%s,prima_porc=%s, prima=%s, salud_porc=%s, salud=%s,pension_porc=%s, pension=%s, vacaciones_porc=%s, vacaciones=%s, cajaCompensacion_porc=%s, cajaCompensacion=%s, sena_porc=%s, sena=%s, arl_porc=%s, arl=%s, total=%s,fecha=%s WHERE id_aporte=%s",
					   GetSQLValueString($_POST['codigo_empl'], "int"),
             GetSQLValueString($_POST['cesantias_porc'], "double"),
					   GetSQLValueString($_POST['cesantias'], "double"),
					   GetSQLValueString($_POST['interesCesantias_porc'], "double"),
					   GetSQLValueString($_POST['interesCesantias'], "double"),
					   GetSQLValueString($_POST['prima_porc'], "double"),
					   GetSQLValueString($_POST['prima'], "double"),
					   GetSQLValueString($_POST['salud_porc'], "double"),
					   GetSQLValueString($_POST['salud'], "double"),
					   GetSQLValueString($_POST['pension_porc'], "double"),
					   GetSQLValueString($_POST['pension'], "double"),
					   GetSQLValueString($_POST['vacaciones_porc'], "double"),
					   GetSQLValueString($_POST['vacaciones'], "double"),
					   GetSQLValueString($_POST['cajaCompensacion_porc'], "double"),
					   GetSQLValueString($_POST['cajaCompensacion'], "double"),
					   GetSQLValueString($_POST['sena_porc'], "double"),
					   GetSQLValueString($_POST['sena'], "double"),
					   GetSQLValueString($_POST['arl_porc'], "double"),
					   GetSQLValueString($_POST['arl'], "double"),
					   GetSQLValueString($_POST['total'], "double"),
					   GetSQLValueString($_POST['fecha'], "date"),
					   GetSQLValueString($_POST['id_aporte'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result2 = mysql_query($updateSQL2, $conexion1) or die(mysql_error());
  $updateGoTo = "proceso_empleado_edit.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {	
  $updateSQL = sprintf("INSERT INTO TblAportes (codigo_empl, cesantias_porc,cesantias, interesCesantias_porc, interesCesantias,prima_porc, prima, salud_porc, salud,pension_porc, pension, vacaciones_porc, vacaciones, cajaCompensacion_porc, cajaCompensacion, sena_porc, sena, arl_porc, arl, total,fecha) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
					   GetSQLValueString($_POST['codigo_empl'], "int"),
             GetSQLValueString($_POST['cesantias_porc'], "double"),
					   GetSQLValueString($_POST['cesantias'], "double"),
					   GetSQLValueString($_POST['interesCesantias_porc'], "double"),
					   GetSQLValueString($_POST['interesCesantias'], "double"),
					   GetSQLValueString($_POST['prima_porc'], "double"),
					   GetSQLValueString($_POST['prima'], "double"),
					   GetSQLValueString($_POST['salud_porc'], "double"),
					   GetSQLValueString($_POST['salud'], "double"),
					   GetSQLValueString($_POST['pension_porc'], "double"),
					   GetSQLValueString($_POST['pension'], "double"),
					   GetSQLValueString($_POST['vacaciones_porc'], "double"),
					   GetSQLValueString($_POST['vacaciones'], "double"),
					   GetSQLValueString($_POST['cajaCompensacion_porc'], "double"),
					   GetSQLValueString($_POST['cajaCompensacion'], "double"),
					   GetSQLValueString($_POST['sena_porc'], "double"),
					   GetSQLValueString($_POST['sena'], "double"),
					   GetSQLValueString($_POST['arl_porc'], "double"),
					   GetSQLValueString($_POST['arl'], "double"),
					   GetSQLValueString($_POST['total'], "double"),
					   GetSQLValueString($_POST['fecha'], "date"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "proceso_empleados_listado.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$conexion = new ApptivaDB();

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

$colname_editar_empleado = "-1";
if (isset($_GET['id_pem'])) {
  $colname_editar_empleado = (get_magic_quotes_gpc()) ? $_GET['id_pem'] : addslashes($_GET['id_pem']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_editar_empleado = sprintf("SELECT * FROM TblProcesoEmpleado,empleado WHERE TblProcesoEmpleado.id_pem = %s AND TblProcesoEmpleado.codigo_empleado=empleado.codigo_empleado", $colname_editar_empleado);
$editar_empleado = mysql_query($query_editar_empleado, $conexion1) or die(mysql_error());
$row_editar_empleado = mysql_fetch_assoc($editar_empleado);
$totalRows_editar_empleado = mysql_num_rows($editar_empleado);
//CODIGO EMPLEADO
mysql_select_db($database_conexion1, $conexion1);
$query_codigo_empleado = "SELECT * FROM empleado ORDER BY nombre_empleado ASC";
$codigo_empleado = mysql_query($query_codigo_empleado, $conexion1) or die(mysql_error());
$row_codigo_empleado = mysql_fetch_assoc($codigo_empleado);
$totalRows_codigo_empleado = mysql_num_rows($codigo_empleado);
//TIPO EMPLEADO
mysql_select_db($database_conexion1, $conexion1);
$query_tipo_empleado = "SELECT * FROM empleado_tipo ORDER BY nombre_tipo_empleado ASC";
$tipo_empleado = mysql_query($query_tipo_empleado, $conexion1) or die(mysql_error());
$row_tipo_empleado = mysql_fetch_assoc($tipo_empleado);
$totalRows_tipo_empleado = mysql_num_rows($tipo_empleado);
//APORTES
mysql_select_db($database_conexion1, $conexion1);
$query_aportes = "SELECT * FROM TblAportes ORDER BY fecha DESC";
$aportes = mysql_query($query_aportes, $conexion1) or die(mysql_error());
$row_aportes = mysql_fetch_assoc($aportes);
$totalRows_aportes = mysql_num_rows($aportes);
//EDITAR APORTES
$colname_aportes_edit = "-1";
if (isset($_GET['id_pem'])) {
  $colname_aportes_edit = (get_magic_quotes_gpc()) ? $_GET['id_pem'] : addslashes($_GET['id_pem']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_aportes_edit = sprintf("SELECT * FROM TblProcesoEmpleado,TblAportes WHERE TblProcesoEmpleado.id_pem = %s AND TblProcesoEmpleado.codigo_empleado=TblAportes.codigo_empl", $colname_aportes_edit);
$aportes_edit = mysql_query($query_aportes_edit, $conexion1) or die(mysql_error());
$row_aportes_edit = mysql_fetch_assoc($aportes_edit);
$totalRows_aportes_edit = mysql_num_rows($aportes_edit);
?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>

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
<link rel="stylesheet" type="text/css" href="css/general.css"/>

<!-- css Bootstrap-->
<link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

</head>
<body>
<?php echo $conexion->header('vistas'); ?>
<table align="center" id="tabla">
   
  <tr>
    <td colspan="2" align="center" id="linea1">
      <form method="POST" name="form1" action="<?php echo $editFormAction; ?>" >
        <table id="tabla35">
          <tr>
            <td colspan="2">&nbsp;</td>
            </tr>
          <tr>
            <td colspan="2" id="titulo2">ACTUALIZAR  EL EMPLEADO AL PROCESO</td>
            </tr>
          <tr>
            <td colspan="2" id="fuente3">
            <a href="empleado_edit.php?id_empleado=<?php echo $row_editar_empleado['id_empleado']; ?>"><img src="images/menos.gif" alt="EDITAR EMPLEADO" title="EDITAR EMPLEADO" border="0" style="cursor:hand;"></a>
            <a href="empleado_add.php"><img src="images/mas.gif" alt="ADD EMPLEADO" title="ADD EMPLEADO" border="0" style="cursor:hand;"></a><a href="factor_prestacional_add.php"><img src="images/f.gif" alt="FACTORES" title="FACTORES" border="0" style="cursor:hand;"></a><a href="tipos_procesos.php"><img src="images/p.gif" title="TIPOS DE PROCESOS" alt="TIPOS DE PROCESOS" border="0" style="cursor:hand;"></a><a href="proceso_empleados_listado.php"><img src="images/opciones.gif" alt="EMPLEADOS" title="EMPLEADOS" border="0" style="cursor:hand;"></a><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
            </tr>
           <tr>
            <td colspan="2" id="fuente2"><div id="resultado"></div></td>
            </tr>
          <tr>
            <td id="fuente1">EMPLEADO</td>
            <td id="fuente1">
              <select name="codigo_empleado" id="codigo_empleado" style="width:150px" disabled>
                <option value=""<?php if (!(strcmp("", $row_editar_empleado['codigo_empleado']))) {echo "selected=\"selected\"";} ?>>Empleado</option>
                <?php
				do {  
				?>
                <option value="<?php echo $row_codigo_empleado['codigo_empleado']?>"<?php if (!(strcmp($row_codigo_empleado['codigo_empleado'], $row_editar_empleado['codigo_empleado']))) {echo "selected=\"selected\"";} ?>><?php echo $row_codigo_empleado['codigo_empleado']." ".$row_codigo_empleado['nombre_empleado']." ".$row_codigo_empleado['apellido_empleado']?></option>
        <?php
				} while ($row_codigo_empleado = mysql_fetch_assoc($codigo_empleado));
				  $rows = mysql_num_rows($codigo_empleado);
				  if($rows > 0) {
					  mysql_data_seek($codigo_empleado, 0);
					  $row_codigo_empleado = mysql_fetch_assoc($codigo_empleado);
				  }
				?>
                </select><div id="resultado_generador"></div></td>
          </tr>
          <tr>
            <td id="fuente1">CARGO</td>
            <td id="fuente1">
              <select name="proceso_empleado" id="proceso_empleado" style="width:150px" >

                <?php
                do {  
                  ?>
                  <option value="<?php echo $row_tipo_empleado['id_empleado_tipo']?>"<?php if (!(strcmp($row_tipo_empleado['id_empleado_tipo'], $row_editar_empleado['proceso_empleado']))) {echo "selected=\"selected\"";} ?>><?php echo $row_tipo_empleado['nombre_tipo_empleado'];?></option> 
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
            <td id="fuente1">SUELDO $</td>
            <td id="fuente1"><input name="sueldo_empleado" type="number" id="sueldo_empleado" min="0" step="1"  required value="<?php echo $row_editar_empleado['sueldo_empleado']; ?>" style="width:150px"  onChange="aportes()"></td>
          </tr>
          <tr>
            <td id="fuente1">AUXILIO TRANSP.</td>
            <td id="fuente1"><input name="aux_empleado" type="number" id="aux_empleado" min="0" step="1"  required value="<?php echo $row_editar_empleado['aux_empleado']; ?>" style="width:150px"  onChange="aportes()"></td>
          </tr> 
          <tr>
            <td id="fuente1">DIAS LABORADOS</td>
            <td id="fuente1"><input name="dias_empleado" type="number" required id="dias_empleado" style="width:150px" max="31" min="0" step="1" value="<?php  echo $row_editar_empleado['dias_empleado'];?>" onChange="aportes()" maxlength="2"></td>
          </tr>        
          <!--<tr>
            <td id="fuente1">COSTO $</td> 
            <td id="fuente1"><input name="costo_empleado" type="number" id="costo_empleado" min="0.00" step="0.01"  required value="<?php echo $row_editar_empleado['costo_empleado']; ?>" style="width:150px" onClick="costoDias()"></td>
          </tr>--> 
         
          <tr>
            <td id="fuente1">FECHA INICIAL</td>
            <td id="fuente1"><input name="fechainicial_empleado" type="date" required id="fecha1" min="2000-01-02" value="<?php echo $row_editar_empleado['fechainicial_empleado']; ?>" size="10" /></td>
          </tr>
          <tr>
            <td id="fuente1">FECHA RETIRO</td>
            <td id="fuente1"><input name="fechafinal_empleado" type="date" id="fecha2" min="2000-01-02" value="<?php echo $row_editar_empleado['fechafinal_empleado']; ?>" size="10" onBlur="inactivo()"/></td>
          </tr>
          <tr>
            <td id="fuente1">ESTADO</td>
            <td id="fuente1"><select name="estado_empleado" id="estado_empleado">
              <option value="1" <?php if (!(strcmp(1, $row_editar_empleado['estado_empleado']))) {echo "selected=\"selected\"";} ?> selected>Activo</option>
              <option value="0" <?php if (!(strcmp(0, $row_editar_empleado['estado_empleado']))) {echo "selected=\"selected\"";} ?>>Inactivo</option>
              
            </select>
              <input type="hidden" name="id_pem" value="<?php echo $row_editar_empleado['id_pem']; ?>">
              <input type="hidden" name="MM_update" value="form1"></td>
          </tr>
          <tr>
            <td colspan="2">&nbsp;</td>
            </tr>
          <tr>
            <td colspan="2" id="fuente2"><!--<?php if($row_aportes_edit['id_aporte']!=''){?><input type="submit" value="EDITAR EMPLEADO" >  <?php } ?>-->
            </td>
            </tr>
          </table>
        <table align="center" id="tabla35"> 
          <tr>         
          <tr>
            <td colspan="2" id="titulo2">APORTES <?php if($row_aportes_edit['id_aporte']!=''){?><a href="javascript:eliminar1('id_aporte',<?php echo $row_aportes_edit['id_aporte']; ?>,'proceso_empleados_listado.php')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR APORTE"
title="ELIMINAR APORTE" border="0"></a><?php }?></td>
            </tr>
          <tr>
                <td id="fuente1">CESANTIAS% <?php echo $row_aportes['cesantias_porc']?></td>
                <td id="fuente1"><input name="cesantias_porc" type="hidden" id="cesantias_porc" step="0.001" value="<?php echo $row_aportes['cesantias_porc']?>">                  <input type="number" name="cesantias" id="cesantias" min="0" step="0.001" placeholder="%" style="width:120px" value="<?php echo $row_aportes_edit['cesantias']?>" required></td>
              </tr>
              <tr>
                <td id="fuente1">INTERES CESANTIAS% <?php echo $row_aportes['interesCesantias_porc']?></td>
                <td id="fuente1"><input name="interesCesantias_porc" type="hidden"step="0.001" value="<?php echo $row_aportes['interesCesantias_porc']?>">                  <input type="number" name="interesCesantias" id="interesCesantias" min="0" step="0.001" placeholder="%" style="width:120px" value="<?php echo $row_aportes_edit['interesCesantias']?>" required></td>
              </tr>
              <tr>
                <td id="fuente1">PRIMA% <?php echo $row_aportes['prima_porc']?></td>
                <td id="fuente1"><input name="prima_porc" type="hidden" id="prima_porc" step="0.001" value="<?php echo $row_aportes['prima_porc']?>">                  <input type="number" name="prima" id="prima" min="0" step="0.001" placeholder="%" style="width:120px" value="<?php echo $row_aportes_edit['prima']?>" required></td>
              </tr>
              <tr>
                <td id="fuente1">SALUD% <?php echo $row_aportes['salud_porc']?></td>
                <td id="fuente1"><input name="salud_porc" type="hidden" id="salud_porc" step="0.001" value="<?php echo $row_aportes['salud_porc']?>">                  <input type="number" name="salud" id="salud" min="0" step="0.001" placeholder="%" style="width:120px" value="<?php echo $row_aportes_edit['salud']?>" required></td>
              </tr>
              <tr>
                <td id="fuente1">PENSION% <?php echo $row_aportes['pension_porc']?></td>
                <td id="fuente1"><input name="pension_porc" type="hidden" id="pension_porc" step="0.001" value="<?php echo $row_aportes['pension_porc']?>">                  <input type="number" name="pension" id="pension" min="0" step="0.001" placeholder="%" style="width:120px" value="<?php echo $row_aportes_edit['pension']?>" required></td>
              </tr>
              <tr>
                <td id="fuente1">VACACIONES% <?php echo $row_aportes['vacaciones_porc']?></td>
                <td id="fuente1"><input name="vacaciones_porc" type="hidden" id="vacaciones_porc" step="0.001" value="<?php echo $row_aportes['vacaciones_porc']?>">                  <input type="number" name="vacaciones" id="vacaciones" min="0" step="0.001" placeholder="%" style="width:120px" value="<?php echo $row_aportes_edit['vacaciones']?>" required></td>
              </tr>
              <tr>
                <td id="fuente1">CAJA DE COMPENSACION% <?php echo $row_aportes['cajaCompensacion_porc']?></td>
                <td id="fuente1"><input name="cajaCompensacion_porc" type="hidden" style="width:70px" step="0.001" value="<?php echo $row_aportes['cajaCompensacion_porc']?>">                  <input type="number" name="cajaCompensacion" id="cajaCompensacion" min="0" step="0.001" placeholder="%" style="width:120px" value="<?php echo $row_aportes_edit['cajaCompensacion']?>" required></td>
              </tr>
              <tr>
                <td id="fuente1">SENA% <?php echo $row_aportes['sena_porc']?></td>
                <td id="fuente1"><input name="sena_porc" type="hidden" id="sena_porc"  step="0.001" value="<?php echo $row_aportes['sena_porc']?>">                  <input type="number" name="sena" id="sena" min="0" step="0.001" placeholder="%" style="width:120px" value="<?php echo $row_aportes_edit['sena']?>" required></td>
              </tr>
              <tr>
                <td id="fuente1">ARL% <?php echo $row_aportes['arl_porc']?></td>
                <td id="fuente1"><input name="arl_porc" type="hidden" id="arl_porc" placeholder="%" style="width:70px" min="0" step="0.001" value="<?php echo $row_aportes['arl_porc']?>">                  <input type="number" name="arl" id="arl" min="0" step="0.001" placeholder="%" style="width:120px" value="<?php echo $row_aportes_edit['arl']?>" required></td>
              </tr>
              <tr>
                <td id="fuente1"><strong>TOTAL&nbsp;</strong></td>
                <td id="fuente1"><input type="number" name="total" id="total" min="0" step="0.001" placeholder="%" style="width:120px" value="<?php echo $row_aportes_edit['total']?>"></td>
              </tr>
              <tr>
            <td colspan="2">&nbsp;</td>
            </tr>
          <tr>
            <td colspan="2" id="fuente2"><?php if($row_aportes_edit['id_aporte']==''){   ?><input type="submit" value="GUARDAR APORTES"  onClick="return enviar_formularios()"> <input type="hidden" name="MM_insert" value="form2"> <?php }else{?>
              <input type="submit" value="EDITAR"  onClick="return enviar_formularios()"> <?php } ?>
              <input type="hidden" name="id_aporte" value="<?php echo $row_aportes_edit['id_aporte'];?>">
              <input name="fecha" type="hidden" min="2000-01-02" value="<?php echo $row_editar_empleado['fechainicial_empleado']; ?>"/>
              <input type="hidden" name="codigo_empl" id="codigo_empl" value="<?php echo $row_editar_empleado['codigo_empleado'];?>">
              
              </td>
            </tr>
        </table>
      </form></td>
  </tr>
</table>
<?php echo $conexion->header('footer'); ?>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($editar_empleado);

mysql_free_result($tipo_empleado);


?>