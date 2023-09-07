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
$query_numero = "SELECT * FROM Tbl_referencia  WHERE estado_ref='1' order by id_ref desc";
$numero = mysql_query($query_numero, $conexion1) or die(mysql_error());
$row_numero = mysql_fetch_assoc($numero);
$totalRows_numero = mysql_num_rows($numero);
//LLENA COMBOS DE MATERIAS PRIMAS
mysql_select_db($database_conexion1, $conexion1);
$query_insumo = "SELECT * FROM insumo WHERE clase_insumo='4' ORDER BY descripcion_insumo ASC";
$insumo = mysql_query($query_insumo, $conexion1) or die(mysql_error());
$row_insumo = mysql_fetch_assoc($insumo);
$totalRows_insumo = mysql_num_rows($insumo);
//LLENA COMBOS DE MATERIAS PRIMAS
mysql_select_db($database_conexion1, $conexion1);
$query_proceso = "SELECT * FROM tipo_procesos ORDER BY id_tipo_proceso ASC";
$procesos = mysql_query($query_proceso, $conexion1) or die(mysql_error());
$row_proceso = mysql_fetch_assoc($procesos);
$totalRows_proceso = mysql_num_rows($procesos);
//FECHAS DE IMPRESION
$fecha = date('Y-m-d');$nuevafecha = strtotime ( '-1 year' , strtotime ( $fecha ) ) ;$nuevafecha = date ( 'Y-m-d' , $nuevafecha );	
$fecha1=$nuevafecha;
$fecha2= date("Y-m-d");
$proceso='1';

$maxRows_costos = 50;
$pageNum_costos = 0;
if (isset($_GET['pageNum_costos'])) {
  $pageNum_costos = $_GET['pageNum_costos'];
}
$startRow_costos = $pageNum_costos * $maxRows_costos;

mysql_select_db($database_conexion1, $conexion1);
$query_costos = "SELECT * FROM insumo ORDER BY codigo_insumo ASC";
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
<li><a href="costos_generales.php">COSTOS GENERALES</a></li>
</ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1">
<form action="produccion_registro_extrusion_listado_xkilos2.php" method="get" name="form1">
<table id="tabla1">
<tr>
<td colspan="2" nowrap="nowrap" id="codigo">CODIGO : R1 - F03</td>
<td colspan="3" nowrap="nowrap" id="titulo2">  MATERIAS PRIMAS  POR PROCESO Y FECHA</td>
<td colspan="2" nowrap="nowrap" id="codigo" width="25">VERSION : 2</td>
</tr>
<tr>
  <td nowrap="nowrap" id="fuente2">&nbsp;</td>
  <td colspan="4" id="fuente2">&nbsp;</td>
  <td colspan="2" id="fuente2">&nbsp;</td>
</tr>
<tr>
  <td colspan="5" nowrap="nowrap" id="titulo2">DESDE:
    <input name="fecha_ini" type="date" id="fecha_ini" required="required"  min="2000-01-02" size="10" value="<?php echo restaMes();?>"/>
    HASTA: 
    <input name="fecha_fin" type="date" id="fecha_fin" min="2000-01-02" size="10" required="required" value="<?php echo date("Y-m-d"); ?>"/></td>
  <td nowrap="nowrap" id="fuente1">    <select name="proceso" id="proceso">
    <?php
do {  
?>
    <option value="<?php echo $row_proceso['id_tipo_proceso']?>"><?php echo $row_proceso['nombre_proceso']?></option>
    <?php
} while ($row_proceso = mysql_fetch_assoc($procesos));
  $rows = mysql_num_rows($procesos);
  if($rows > 0) {
      mysql_data_seek($procesos, 0);
	  $row_proceso = mysql_fetch_assoc($procesos);
  }
?>
    </select>
    <input type="submit" name="button" id="button" value="Consultar" /></td>
  </tr>
<tr>
  <td nowrap="nowrap" id="fuente2">&nbsp;</td>
  <td colspan="4" id="fuente2">&nbsp;</td>
  <td colspan="2" id="fuente2">&nbsp;</td>
</tr>
<tr>
    <td colspan="3" id="dato1"><strong>Nota: </strong>El saldo en rojo es porque hay un minimo stock de lo que hay en inventario</td>
    <td colspan="3" id="dato3"><a href="produccion_registro_extrusion_listado.php"><img src="images/e.gif" alt="LISTADO EXTRUSION"title="LISTADO EXTRUSION" border="0" style="cursor:hand;" /></a><a href="produccion_registro_impresion_listado.php"><img src="images/i.gif" alt="LISTADO IMPRESION"title="LISTADO IMPRESION" border="0" style="cursor:hand;" /></a><a href="consumo_materias_primas.php"><img src="images/mp.gif" alt="LISTADO DE MATERIAS PRIMAS"title="LISTADO DE MATERIAS PRIMAS" border="0" style="cursor:hand;" /></a><a href="costos_listado_ref_xproceso_tiempos.php"><img src="images/rt.gif" alt="LISTADO REF TIEMPOS X PROCESO"title="LISTADO REF TIEMPOS X PROCESO" border="0" style="cursor:hand;" /></a><img src="images/impresor.gif" onClick="window.print();" style="cursor:hand;" alt="IMPRIMIR" title="IMPRIMIR" border="0" /><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR" title="RESTAURAR" border="0" style="cursor:hand;"/></a>
      <input type="button" value="Excel Detalle" onclick="window.location = 'produccion_registro_listado_excel.php?tipoListado=1&amp;fecha_ini=<?php echo $fecha1 ?>&amp;fecha_fin=<?php echo $fecha2 ?>&amp;proceso=<?php echo $proceso ?>'" / /></td>
    </tr>
  </table>
  </form>
  <table id="tabla1">    
    <tr id="tr1">
      <td rowspan="2" nowrap="nowrap" id="titulo4">CODIGO</td>
    <td rowspan="2" nowrap="nowrap" id="titulo4">DESCRIPCION</td>
    <td colspan="2" nowrap="nowrap" id="titulo4">INVENTARIO INICIAL</td> 
    <td colspan="2" nowrap="nowrap" id="titulo4">ENTRADAS</td>
    <td colspan="2" nowrap="nowrap" id="titulo4">SALIDAS</td>
    <td colspan="2" nowrap="nowrap" id="titulo4">INVENTARIO FINAL</td>
    </tr>
  <tr id="tr1">
    <td nowrap="nowrap" id="titulo4">CANTIDAD</td>
    <td nowrap="nowrap" id="titulo4">COSTO TOTAL</td>
    <td nowrap="nowrap" id="titulo4">CANTIDAD</td>
    <td nowrap="nowrap" id="titulo4">COSTO TOTAL</td>
    <td nowrap="nowrap" id="titulo4">CANTIDAD</td>
    <td nowrap="nowrap" id="titulo4">COSTO TOTAL</td>
    <td nowrap="nowrap" id="titulo4">CANTIDAD</td>
    <td nowrap="nowrap" id="titulo4">COSTO TOTAL</td>
  </tr>
  <?php do { ?>
  <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
    <td id="dato1" nowrap="nowrap"><?php echo $row_costos['codigo_insumo'];  
	  ?></td>
      <td id="dato1" nowrap="nowrap">
	  <?php 
	  $stok=$row_costos['stok_insumo'];
	  $nombre=$row_costos['descripcion_insumo'];
	  echo $nombre;
	  ?></td>
      <td id="dato1">
      <?php  
	  $id_rpp=$row_costos['id_insumo'];
	  $sqlSalIn="SELECT SaldoFinCant,MateriaPrima FROM TblCostosMP WHERE MateriaPrima='$id_rpp' ORDER BY `FechaFinal` DESC LIMIT 1"; 
	  $resultSalIn=mysql_query($sqlSalIn); 
	  $numSalIn=mysql_num_rows($resultSalIn); 
	  if($numSalIn >= '1') 
	  { $saldo_inicial=mysql_result($resultSalIn,0,'SaldoFinCant');}else {echo "0,00";}
	  ?></td> 
      <td id="dato1">
      <?php 
	  $TotalcostoSaldo=$row_costos['valor_unitario_insumo'];
	  $TotalcostoSaldo=$costoSaldo*$saldo_inicial;
	  echo numeros_format($TotalcostoSaldo);
	  ?> </td>     
      <td id="dato1">
      <?php 
	  $id_rpp_rp=$row_costos['id_insumo'];
	  $sqlinv="SELECT orden_compra.n_oc,orden_compra.fecha_entrega_oc,orden_compra_detalle.id_insumo_det, SUM(orden_compra_detalle.saldo_det) AS entrada 
	  FROM orden_compra,orden_compra_detalle WHERE orden_compra.n_oc=orden_compra_detalle.n_oc_det AND orden_compra.fecha_entrega_oc BETWEEN '$fecha1' AND '$fecha2' AND orden_compra_detalle.id_insumo_det=$id_rpp_rp"; 
	  $resultinv=mysql_query($sqlinv); 
	  $numinv=mysql_num_rows($resultinv); 
	  if($numinv >= '1') 
	  { $entrada=mysql_result($resultinv,0,'entrada'); echo numeros_format($entrada);}else {echo "0,00";}
	  ?></td>
      <td id="dato1">
      <?php 
	  $costoEntrada=$row_costos['valor_unitario_insumo'];
	  $TotalcostoEntrada=$costoEntrada*$entrada;
	  echo numeros_format($TotalcostoEntrada);
	  ?>
      </td>
      <td id="dato1"><?php 
	  $id_rpp_rp=$row_costos['id_insumo'];
	  $sqlcons="SELECT id_rpp_rp, fecha_rkp, op_rp, SUM(valor_prod_rp) AS kprod, SUM(valDespImp_rp) AS kdesp FROM Tbl_reg_kilo_producido WHERE id_rpp_rp='$id_rpp_rp'"; 
	  $resultcons=mysql_query($sqlcons); 
	  $numcons=mysql_num_rows($resultcons); 
	  if($numcons >= '1') 
	  {$consumo=mysql_result($resultcons,0,'kprod'); echo numeros_format($consumo);}else {echo "0,00";}
	  ?></td>
      <td id="dato1">
      <?php 
	  $costoConsumo=$row_costos['valor_unitario_insumo']; $TotalcostoConsumo=$costoConsumo*$consumo; echo numeros_format($TotalcostoConsumo);
	  ?>
      </td>
      <td id="dato1"><?php
	  $saldo_final=(($entrada+$saldo_inicial)-$consumo);
	  if($saldo_final<$stok){
      echo "<span class='rojo_normal'>". numeros_format($saldo_final) ."</spam>";}else{echo numeros_format($saldo_final);}
	  ?></td>
      <td id="dato1">
      <?php 
	  $costoSaldoF=$row_costos['valor_unitario_insumo'];
      $TotalcostoSaldoF=$costoSaldoF*$consumo; echo numeros_format($TotalcostoSaldoF);
	  ?>
      </td>      
    </tr>
    <?php } while ($row_costos = mysql_fetch_assoc($costos)); ?>
    <tr>
      <td nowrap="nowrap" id="fuente1">&nbsp;</td>
    <td nowrap="nowrap" id="fuente1">&nbsp;</td>
    <td nowrap="nowrap" id="fuente1"> </td>
    <td nowrap="nowrap" id="fuente1"><strong><?php //echo numeros_format($granTotal);?></strong></td>
    <td nowrap="nowrap" id="fuente1">&nbsp;</td>
  </tr>
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

mysql_free_result($numero);

mysql_free_result($insumo);

mysql_free_result($procesos);

mysql_free_result($costos);

?>