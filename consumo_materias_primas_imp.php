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
//SELECT REFERENCIAS
mysql_select_db($database_conexion1, $conexion1);
$query_referencia = "SELECT * FROM Tbl_referencia WHERE estado_ref='1' order by id_ref desc";
$referencia = mysql_query($query_referencia, $conexion1) or die(mysql_error());
$row_referencia = mysql_fetch_assoc($referencia);
$totalRows_referencia = mysql_num_rows($referencia);
//LLENA COMBOS DE MATERIAS PRIMAS
mysql_select_db($database_conexion1, $conexion1);
$query_insumo = "SELECT * FROM insumo WHERE clase_insumo='8' ORDER BY descripcion_insumo ASC";
$insumo = mysql_query($query_insumo, $conexion1) or die(mysql_error());
$row_insumo = mysql_fetch_assoc($insumo);
$totalRows_insumo = mysql_num_rows($insumo);

//FECHAS DE IMPRESION
$fecha = date('Y-m-d');$nuevafecha = strtotime ( '-1 year' , strtotime ( $fecha ) ) ;$nuevafecha = date ( 'Y-m-d' , $nuevafecha );	
$fecha1=first_month_day();
$fecha2= date("Y-m-d");
$proceso='2';

$maxRows_consumo = 20;
$pageNum_consumo = 0;
if (isset($_GET['pageNum_consumo'])) {
  $pageNum_consumo = $_GET['pageNum_consumo'];
}
$startRow_consumo = $pageNum_consumo * $maxRows_consumo;

mysql_select_db($database_conexion1, $conexion1);
$query_consumo = "SELECT * FROM Tbl_orden_produccion,Tbl_reg_kilo_producido WHERE Tbl_orden_produccion.id_op=Tbl_reg_kilo_producido.op_rp AND Tbl_orden_produccion.b_estado_op > 0 AND DATE(Tbl_reg_kilo_producido.fecha_rkp) BETWEEN '$fecha1' AND '$fecha2' and Tbl_reg_kilo_producido.id_proceso_rkp='$proceso' ORDER BY Tbl_orden_produccion.id_op DESC";
$query_limit_consumo = sprintf("%s LIMIT %d, %d", $query_consumo, $startRow_consumo, $maxRows_consumo);
$consumo = mysql_query($query_limit_consumo, $conexion1) or die(mysql_error());
$row_consumo = mysql_fetch_assoc($consumo);

if (isset($_GET['totalRows_consumo'])) {
  $totalRows_consumo = $_GET['totalRows_consumo'];
} else {
  $all_consumo = mysql_query($query_consumo);
  $totalRows_consumo = mysql_num_rows($all_consumo);
}
$totalPages_consumo = ceil($totalRows_consumo/$maxRows_consumo)-1;

$queryString_consumo = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_consumo") == false && 
        stristr($param, "totalRows_consumo") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_consumo = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_consumo = sprintf("&totalRows_consumo=%d%s", $totalRows_consumo, $queryString_consumo);

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
<body><div align="center">

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
</ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1">
<form action="consumo_materias_primas2_imp.php" method="get" name="form1">
<table id="tabla1">
<tr>
<td colspan="2" nowrap="nowrap" id="codigo">CODIGO : R1 - F03</td>
<td colspan="4" nowrap="nowrap" id="titulo2"> CONSUMO DE MATERIAS PRIMASC IMPRESION</td>
<td colspan="2" nowrap="nowrap" id="codigo" width="25">VERSION : 1</td>
</tr>
<tr>
  <td colspan="4" id="fuente2">&nbsp;</td>
  <td colspan="4" id="fuente2">&nbsp;</td>
</tr>
<tr>
  <td colspan="6" nowrap="nowrap" id="titulo2">DESDE:
    <input name="fechaI" type="date" id="fechaI" required="required"  min="2000-01-02" size="10" value="<?php echo first_month_day(); ?>"/>
    HASTA: 
    <input name="fechaF" type="date" id="fechaF" min="2000-01-02" size="10" required="required" value="<?php echo date("Y-m-d"); ?>"/>
    REF:
    <select name="ref" id="ref">
    <option value="">REF</option>
      <?php
	do {  
	?>
		  <option value="<?php echo $row_referencia['id_ref']?>"><?php echo $row_referencia['cod_ref']?></option>
		  <?php
	} while ($row_referencia = mysql_fetch_assoc($referencia));
	  $rows = mysql_num_rows($referencia);
	  if($rows > 0) {
		  mysql_data_seek($referencia, 0);
		  $row_referencia = mysql_fetch_assoc($referencia);
	  }
	?>
    </select>
    MP:
    <select name="mp" id="mp" style="width:50px">
    <option value="">MP</option>
      <?php
	do {  
	?>
	<option value="<?php echo $row_insumo['id_insumo']?>"><?php echo $row_insumo['descripcion_insumo']?></option>
		  <?php
	} while ($row_insumo = mysql_fetch_assoc($insumo));
	  $rows = mysql_num_rows($insumo);
	  if($rows > 0) {
		  mysql_data_seek($insumo, 0);
		  $row_insumo = mysql_fetch_assoc($insumo);
	  }
	?>
    </select></td>
  <td nowrap="nowrap" id="fuente1"><input type="submit" name="button" id="button" value="Consultar" /></td>
  </tr>
<tr>
  <td nowrap="nowrap" id="fuente2">&nbsp;</td>
  <td colspan="4" id="fuente2">&nbsp;</td>
  <td colspan="4" id="fuente2">&nbsp;</td>
</tr>
<tr>
    <td colspan="3" id="dato1">&nbsp;</td>
    <td colspan="5" id="dato3"><a href="produccion_registro_extrusion_listado.php"><img src="images/e.gif" alt="LISTADO EXTRUSION"title="LISTADO EXTRUSION" border="0" style="cursor:hand;" /></a><a href="produccion_registro_impresion_listado.php"><img src="images/i.gif" alt="LISTADO IMPRESION"title="LISTADO IMPRESION" border="0" style="cursor:hand;" /></a><a href="consumo_tiempos_imp.php"><img src="images/rt.gif" alt="LISTADO DE TIEMPOS"title="LISTADO DE TIEMPOS" border="0" style="cursor:hand;" /></a><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR" title="RESTAURAR" border="0" style="cursor:hand;"/></a>
      <input type="button" value="Descarga Excel" onclick="window.location = 'consumo_materias_primas_excel_imp.php?fechaI=<?php echo $fecha1 ?>&amp;fechaF=<?php echo $fecha2 ?>&ref=<?php echo $_GET['ref']?>&mp=<?php echo $_GET['mp'] ?>'" / /></td>
    </tr>
  </table>
  </form>
  <table id="tabla1">    
    <tr id="tr1">
      <td nowrap="nowrap" id="titulo4">ORDEN P.</td>
      <td nowrap="nowrap" id="titulo4">REF.</td>
      <td nowrap="nowrap" id="titulo4">MATERIA PRIMA</td>
      <td nowrap="nowrap" id="titulo4">CONSUMO/kg</td>
      <!--<td nowrap="nowrap" id="titulo4">DESPERDICIO/kg</td>-->
      <td nowrap="nowrap" id="titulo4">FECHA</td>
      </tr>
    <?php do { ?>
    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
      <td id="dato1" nowrap="nowrap"><?php echo $row_consumo['id_op'] ?></td>
      <td id="dato1" nowrap="nowrap"><?php echo $row_consumo['int_cod_ref_op'];?></td>
      <td nowrap="nowrap" id="dato1"><?php 
		 $id_rpp=$row_consumo['id_rpp_rp'];
		 $sqlin="SELECT descripcion_insumo FROM insumo WHERE id_insumo='$id_rpp'"; 
		 $resultin=mysql_query($sqlin); 
		 $numin=mysql_num_rows($resultin); 
		 if($numin >= '1') 
		 {
		 echo $nombre=mysql_result($resultin,0,'descripcion_insumo');}else {echo "N.A";}
	  ?></td>
      <td nowrap="nowrap" id="dato1"><?php  
	  $id_rpp=$row_consumo['id_rkp'];//ID
	  $sqlex="SELECT (valor_prod_rp) AS producido  FROM  Tbl_reg_kilo_producido WHERE id_rkp=$id_rpp AND id_proceso_rkp='$proceso'"; 
	  $resultex=mysql_query($sqlex); 
	  $numex=mysql_num_rows($resultex); 
	  if($numex >= '1') 
	  { $kilos_ex=mysql_result($resultex,0,'producido');
	   echo numeros_format($kilos_ex); }else {echo "0";}
	  ?></td>
<!--      <td nowrap="nowrap" id="dato1"><?php 
	    $op=$row_consumo['id_op'];
	    $sqlexd="SELECT SUM(valor_desp_rd) AS kgDespe FROM Tbl_reg_desperdicio WHERE op_rd='$op' AND id_proceso_rd='$proceso'"; 
	    $resultexd=mysql_query($sqlexd); 
	    $numexd=mysql_num_rows($resultexd); 
	    if($numexd >= '1') 
	    { $kilos_exd=mysql_result($resultexd,0,'kgDespe'); echo numeros_format($kilos_exd); }else {echo "0";}
	  ?></td> -->
       <td id="dato1" nowrap="nowrap"><?php echo quitarHora($row_consumo['fecha_rkp']); ?></td>
      </tr>
    <?php } while ($row_consumo = mysql_fetch_assoc($consumo)); ?>   
</table>
<table id="tabla1">
  <tr>
    <td id="dato1" width="25%"><?php if ($pageNum_consumo > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_consumo=%d%s", $currentPage, 0, $queryString_consumo); ?>">Primero</a>
          <?php } // Show if not first page ?>
    </td>
    <td id="dato1" width="25%"><?php if ($pageNum_consumo > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_consumo=%d%s", $currentPage, max(0, $pageNum_consumo - 1), $queryString_consumo); ?>">Anterior</a>
          <?php } // Show if not first page ?>
    </td>
    <td id="dato1" width="25%"><?php if ($pageNum_consumo < $totalPages_consumo) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_consumo=%d%s", $currentPage, min($totalPages_consumo, $pageNum_consumo + 1), $queryString_consumo); ?>">Siguiente</a>
          <?php } // Show if not last page ?>
    </td>
    <td id="dato1" width="25%"><?php if ($pageNum_consumo < $totalPages_consumo) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_consumo=%d%s", $currentPage, $totalPages_consumo, $queryString_consumo); ?>">&Uacute;ltimo</a>
          <?php } // Show if not last page ?>
    </td>
  </tr>
</table>
</td>
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

mysql_free_result($referencia);

mysql_free_result($insumo);

mysql_free_result($procesos);

mysql_free_result($consumo);

?>