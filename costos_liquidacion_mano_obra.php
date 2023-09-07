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
$currentPage = $_SERVER["PHP_SELF"];
$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

$maxRows_costos = 30;
$pageNum_costos = 0;
if (isset($_GET['pageNum_costos'])) {
  $pageNum_costos = $_GET['pageNum_costos'];
}
$startRow_costos = $pageNum_costos * $maxRows_costos;

mysql_select_db($database_conexion1, $conexion1);
$query_costos = "SELECT * FROM TblProcesoEmpleado WHERE estado_empleado='1' ORDER BY proceso_empleado ASC";
$query_limit_costos = sprintf("%s LIMIT %d, %d", $query_costos, $startRow_costos, $maxRows_costos);
$costos = mysql_query($query_limit_costos, $conexion1) or die(mysql_error());
$row_costos = mysql_fetch_assoc($costos);

if (isset($_GET['totalRows_costos'])) {
  $totalRows_costos = $_GET['totalRows_costos'];
} else {
  $all_costos = mysql_query($query_costos);
  $totalRows_costos = mysql_num_rows($all_costos);
}
$totalPages_costos = ceil($totalRows_costos/$maxRows_costos)-1;

$queryString_costos = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_costos") == false && 
        stristr($param, "totalRows_costos") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_costos = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_costos = sprintf("&totalRows_costos=%d%s", $totalRows_costos, $queryString_costos);

session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0014)about:internet -->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/listado.js"></script>
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
  <li><a href="costos_generales.php">COSTOS GENERALES</a></li>
  </ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1">
    <form action="costos_liquidacion_mano_obra.php" method="get" name="form1">
	<table id="tabla1">
	<tr>
	  <td colspan="10" id="titulo2">&nbsp;</td>
  </tr>
	<tr>
	  <td colspan="10" id="titulo2">LIQUIDACION MANO DE OBRA</td>
	  </tr>
	<!--<tr>
      <td colspan="12" id="titulo2" nowrap>DESDE 
        <input name="fecha_ini" type="date" id="fecha_ini" required  min="2000-01-02" size="10" value="<?php echo restaMes();?>"/>      HASTA
        <input name="fecha_fin" type="date" id="fecha_fin" min="2000-01-02" size="10" required value="<?php echo fecha();?>"onChange="if(form1.fecha_ini.value && form1.fecha_fin.value) { consulta_mano_obra(); }else { alert('Debe seleccionar las dos fechas')}"/></td>
	  </tr>-->
	<tr>
	  <td colspan="10" id="titulo2">&nbsp;</td>
	  </tr>
<tr>
  <td colspan="4" id="fuente1">&nbsp;</td>
  <td colspan="6" id="fuente3"><a href="proceso_empleados_listado.php"> <img src="images/mas.gif" alt="ADD EMPLEADO" title="ADD EMPLEADO" border="0" style="cursor:hand;" /></a><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="CARGAR LISTADO" title="CARGAR LISTADO" border="0" style="cursor:hand;"/></a>  
    <input type="button" value="Excel Detalle" onclick="window.location = 'costos_liquidacion_mano_obra_excel.php?tipoListado=1'" />
  </td>
  </tr>
<tr id="tr1">
  <td id="titulo4" >Nombre</td>
  <td id="titulo4" >Cargo</td>
  <td id="titulo4" >Dias Laborados</td>
  <td id="titulo4" >Total Periodo</td>
  <td id="titulo4" >Costo Hora</td>
  <td id="titulo4" >M&aacute;quinas</td>
  <td id="titulo4" >Total Operarios</td>
  <td id="titulo4" >Operarios por Máquina</td>
  <td id="titulo4" >Operarios por turno y Máquina</td>
  <td id="titulo4" >Costo Hora Proceso x Línea y turno</td> 
  </tr>
<?php do { ?>
  <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
    <td id="dato1" nowrap><a href="proceso_empleado_edit.php?id_pem=<?php echo $rows_empleado['id_pem']; ?>" target="_top" style="text-decoration:none; color:#000000">
      <?php $codigo_empleado=$row_costos['codigo_empleado']; 	
	$sqlemp="SELECT nombre_empleado, apellido_empleado FROM empleado WHERE codigo_empleado='$codigo_empleado'";
	$resultemp=mysql_query($sqlemp); $numemp=mysql_num_rows($resultemp);
	if ($numemp>='1') { 
	$nombre_empleado=mysql_result($resultemp,0,'nombre_empleado');$apellido_empleado=mysql_result($resultemp,0,'apellido_empleado');  
	echo $nombre_empleado." ".$apellido_empleado; }?>
    </a><a href="proceso_empleado_edit.php?id_pem=<?php echo $row_costos['id_pem']; ?>" target="_top" style="text-decoration:none; color:#000000"></a></td>   
    <td id="dato2"><a href="proceso_empleado_edit.php?id_pem=<?php echo $row_costos['id_pem']; ?>" target="_top" style="text-decoration:none; color:#000000">
    <?php $proceso_empleado=$row_costos['proceso_empleado']; 	
	$sql2="SELECT * FROM tipo_procesos WHERE id_tipo_proceso=$proceso_empleado";
	$result2=mysql_query($sql2); $num2=mysql_num_rows($result2);
	if ($num2>='1') { $proceso_empleado=mysql_result($result2,0,'nombre_proceso'); 
	echo $proceso_empleado; }?>
    </a></td>
    <td id="dato2"><a href="proceso_empleado_edit.php?id_pem=<?php echo $row_costos['id_pem']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_costos['dias_empleado'];?></a></td>
    <td id="dato1"><a href="proceso_empleado_edit.php?id_pem=<?php echo $row_costos['id_pem']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php $costoEmp= $row_costos['costo_empleado'];echo numeros_format($costoEmp) ?></a></td>
    <td id="dato1"><a href="proceso_empleado_edit.php?id_pem=<?php echo $row_costos['id_pem']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php ($costoHora=$row_costos['costo_empleado']*8);echo numeros_format($costoHora); ?></a></td>
    <td id="dato2" ><a href="proceso_empleado_edit.php?id_pem=<?php echo $row_costos['id_pem']; ?>" target="_top" style="text-decoration:none; color:#000000">
      <?php $maquinas=$row_costos['proceso_empleado']; 
	$sqlmaq="SELECT COUNT(proceso_maquina) AS maq FROM maquina WHERE proceso_maquina=$maquinas";
	$resultmaq=mysql_query($sqlmaq); 
	$nummaq=mysql_num_rows($resultmaq);
	if ($nummaq>='1') { 
	$Tmaquinas=mysql_result($resultmaq,0,'maq');
	echo $Tmaquinas;
	}
	?></a></td>
    <td id="dato2" ><a href="proceso_empleado_edit.php?id_pem=<?php echo $row_costos['id_pem']; ?>" target="_top" style="text-decoration:none; color:#000000">
    <?php $empleados=$row_costos['proceso_empleado']; 	
	$sqlemp="SELECT COUNT(proceso_empleado) AS empleados,estado_empleado FROM TblProcesoEmpleado WHERE proceso_empleado=$empleados AND estado_empleado='1'";
	$resultemp=mysql_query($sqlemp); $numemp=mysql_num_rows($resultemp);
	if ($numemp>='1') { 
	$cant_empleado=mysql_result($resultemp,0,'empleados');//cantidad de empleados por proceso 
	echo $cant_empleado; }
	?> </a></td>
    <td id="dato2"><a href="proceso_empleado_edit.php?id_pem=<?php echo $row_costos['id_pem']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php $operarioxMaqu=$cant_empleado/$Tmaquinas;echo $operarioxMaqu;//tomados de las dos anteriores columnas?></a></td> 
    <td id="dato2"><a href="proceso_ajuste_edit.php?id_pa=<?php echo $id_pa; ?>" target="_top" style="text-decoration:none; color:#000000"><?php $operarioxTurnoM=($operarioxMaqu/$valor_pa);echo numeros_format($operarioxTurnoM); ?></a></td> 
    <td nowrap="nowrap" id="dato2"><a href="proceso_empleado_edit.php?id_pem=<?php echo $row_costos['id_pem']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php $costoxPxLxT=($costoHoraM*$operarioxTurnoM);echo "$  ";echo numeros_format($costoxPxLxT); ?></a></td> 
    </tr>
  <?php } while ($row_costos = mysql_fetch_assoc($costos)); ?>
</table>
<table id="tabla1">
  <tr>
    <td id="dato1" width="25%"><?php if ($pageNum_costos > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_costos=%d%s", $currentPage, 0, $queryString_costos); ?>">Primero</a>
          <?php } // Show if not first page ?>
    </td>
    <td id="dato1" width="25%"><?php if ($pageNum_costos > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_costos=%d%s", $currentPage, max(0, $pageNum_costos - 1), $queryString_costos); ?>">Anterior</a>
          <?php } // Show if not first page ?>
    </td>
    <td id="dato1" width="25%"><?php if ($pageNum_costos < $totalPages_costos) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_costos=%d%s", $currentPage, min($totalPages_costos, $pageNum_costos + 1), $queryString_costos); ?>">Siguiente</a>
          <?php } // Show if not last page ?>
    </td>
    <td id="dato1" width="25%"><?php if ($pageNum_costos < $totalPages_costos) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_costos=%d%s", $currentPage, $totalPages_costos, $queryString_costos); ?>">&Uacute;ltimo</a>
          <?php } // Show if not last page ?>
    </td>
  </tr>
</table>
</form>
  </td>
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

mysql_free_result($costos);
?>