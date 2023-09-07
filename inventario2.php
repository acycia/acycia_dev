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
$conexion = new ApptivaDB();

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
$currentPage = $_SERVER["PHP_SELF"];

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
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

//FECHAS DE IMPRESION
$maxRows_costos = 50;
$pageNum_costos = 0;
if (isset($_GET['pageNum_costos'])) {
  $pageNum_costos = $_GET['pageNum_costos'];
}
$startRow_costos = $pageNum_costos * $maxRows_costos;

mysql_select_db($database_conexion1, $conexion1);
if($_GET['fecha_ini']==''){$fecha1=first_month_day();}else{$fecha1=$_GET['fecha_ini'];}
//$fecha1 = $fecha1;
if($_GET['fecha_fin']==''){$fecha2=last_month_day();}else{$fecha2=$_GET['fecha_fin'];}
//$fecha2 = $fecha2;
$tipo = $_GET['tipo'];
//Filtra producto terminado
if($fecha1 != '' && $fecha2 != '')
{
$query_costos = "SELECT * FROM TblInventarioListado WHERE Tipo='$tipo' ORDER BY CONVERT(Codigo, SIGNED INTEGER) DESC limit 10";	
}
$costos = mysql_query($query_costos, $conexion1) or die(mysql_error());
$row_costos = mysql_fetch_assoc($costos);
$totalRows_costos = mysql_num_rows($costos);

 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0014)about:internet -->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>SISADGE AC &amp; CIA</title>
<script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
<link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">

<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/listado.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
<script type="text/javascript" src="js/formato.js"></script> 

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
<body ><!-- onload = "JavaScript: AutoRefresh (90000);" -->
<?php echo $conexion->header('listas'); ?>
<form action="<?php echo $editFormAction; ?>" method="GET" name="form1">

<table class="table table-bordered table-sm">
<thead>
<tr>
<td colspan="3"nowrap="nowrap" id="codigo" >CODIGO : R1 - F03</td>
<td colspan="3" nowrap="nowrap" id="titulo2">INVENTARIO</td>
<td colspan="3" nowrap="nowrap" id="codigo" width="25">VERSION : 1</td>
</tr>
<tr>
  <td nowrap="nowrap" colspan="3"  id="fuente1">&nbsp;</td>
  <td colspan="3" id="fuente1">&nbsp;</td>
  <td colspan="3" id="fuente1">&nbsp;</td>
</tr>
<tr>
  <td colspan="3" nowrap="nowrap" id="titulo2">DESDE:
    <input name="fecha_ini" type="date" id="fecha_ini" required="required"  min="2000-01-02" size="10" value="<?php echo $_GET['fecha_ini']; ?>"/>
    HASTA:
    <input name="fecha_fin" type="date" id="fecha_fin" min="2000-01-02" size="10" required="required" value="<?php echo $_GET['fecha_fin']; ?>"/></td>
  <td nowrap="nowrap" colspan="3"  id="fuente1"><select name="tipo" id="tipo">
    <option value="1"<?php if (!(strcmp("1", $tipo))) {echo "selected=\"selected\"";} ?>>PRODUCTO TERMINADO</option>
    <option value="2"<?php if (!(strcmp("2", $tipo))) {echo "selected=\"selected\"";} ?>>MATERIAS PRIMAS</option>
    <option value="3"<?php if (!(strcmp("3", $tipo))) {echo "selected=\"selected\"";} ?>>PRODUCTO EN PROCESO</option>
    <option value="4"<?php if (!(strcmp("4", $tipo))) {echo "selected=\"selected\"";} ?>>MATERIA PRIMA EN PROCESO</option>
  </select></td>
  <td nowrap="nowrap" id="fuente1">&nbsp;</td>
  <td nowrap="nowrap" colspan="2" id="fuente3"><input type="submit" name="button" id="button" value="Consultar" /></td>
  </tr>
<tr>
  <td colspan="3" nowrap="nowrap" id="fuente2">&nbsp;</td>
  <td colspan="3" id="fuente2">&nbsp;</td>
  <td colspan="3" id="fuente2">&nbsp;</td>
</tr>
<tr>
    <td colspan="3" id="dato1"><p><strong>Nota: </strong></p>
      <p>*El saldo en rojo en la columna (INVENTARIO FINAL) es porque hay un stok minimo..</p>
      <p>*Valor Unidad proviene del valor con el que se vendio en la o.c</p>
      <p>*En Materias Primas las entradas provienen de los ingresos de almacen de mp y producto terminado desde sellado. y en salidas en materias primas son del consumo por proceso y de producto terminado sus Despachos, todo por fecha</p>
      <p>*La Medida de venta Unidad o millar debe ser igual en la cotiz como en la o.c</p></td>
    <td colspan="3" id="dato3"><a href="inventario_add.php"><img src="images/mas.gif" alt="ADD COSTO REFERENCIA" title="ADD COSTO REFERENCIA" border="0" style="cursor:hand;" /></a><a href="produccion_registro_extrusion_listado.php"><img src="images/e.gif" alt="LISTADO EXTRUSION"title="LISTADO EXTRUSION" border="0" style="cursor:hand;" /></a><a href="produccion_registro_impresion_listado.php"><img src="images/i.gif" alt="LISTADO IMPRESION"title="LISTADO IMPRESION" border="0" style="cursor:hand;" /></a><a href="consumo_materias_primas.php"><img src="images/mp.gif" alt="LISTADO DE MATERIAS PRIMAS"title="LISTADO DE MATERIAS PRIMAS" border="0" style="cursor:hand;" /></a><img src="images/impresor.gif" onClick="window.print();" style="cursor:hand;" alt="IMPRIMIR" title="IMPRIMIR" border="0" /><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR" title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
    <td colspan="3" id="dato3"> 
     <input type="button" value="Carga Masiva" onclick="window.location = 'inventario_importar.php?tipo=<?php echo $tipo ?>'" />
     <input type="button" value="Descarga Excel" onclick="window.location = 'inventario_excel.php?tipo=<?php echo $tipo ?>'" />
     </td>
    </tr>

  </thead>
</table>
  </form>
 <form action="inventario2.php" method="POST"  enctype="multipart/form-data" name="form2"> 
    <!-- <table id="tabla1">  

  </table>--> 
  <div style="height:400px; overflow:scroll;">
   <table id="tabla1">  
    <?php if($tipo=='1') {?> 
 <!-- SI ES PRODUCTO TERMINADO style="position: fixed;"-->
  <tr id="tr1"> 
    <td class="listado4" style="width:9 px">CODIGO</td>
    <td class="listado4" style="width:17 px">DESCRIPCION</td>
    <td class="listado4" style="width:15 px">UNIDAD</td>
    <td class="listado4" style="width:5 px">INV. INICIAL</td>
    <td class="listado4" style="width:8 px">ENTRADAS</td>
    <td class="listado4" style="width:8 px">REMISION (Sistema)</td>
    <td class="listado4" style="width:8 px"><p>SALIDAS</p>
      <p>(Manual)</p></td>
    <td class="listado4" style="width:8 px">INVENTARIO FINAL</td>
    <td class="listado4" style="width:8 px">STOK</td>
    <td class="listado4" style="width:8 px">VALOR O.C</td>
    <td class="listado4" style="width:8 px">VALOR INV.</td>
    <td class="listado4" style="width:8 px">VALOR TOTAL</td>
    <td class="listado4" style="width:10 px">TIPO</td>
    <td class="listado4" style="width:10 px">Modifico</td>
    <td class="listado4" style="width:10 px">Fecha Mod</td>
  </tr>
  <?php do { ?>
  <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF"> 
  <td id="dato1" nowrap="nowrap"><?php 
   echo $row_costos['Codigo'];
   $Cod_ref=$row_costos['Cod_ref'];
  $query_descri = "SELECT tipo_bolsa_ref FROM Tbl_referencia WHERE cod_ref='$Cod_ref'";
		$resultdescri=mysql_query($query_descri); 
		$numdescri=mysql_num_rows($resultdescri); 
		if($numdescri >= '1') 
		{
		$descripcion=mysql_result($resultdescri,0,'tipo_bolsa_ref'); }else{ $descripcion="no encontrado";}
		?></td>
      <td id="dato1" nowrap="nowrap"><?php  echo $descripcion; ?></td>
      <td id="dato1" nowrap="nowrap">
      <?php
	  $Cod_ref=$row_costos['Cod_ref'];
	  $sqlcosto = "SELECT str_unidad_io,int_precio_io FROM Tbl_items_ordenc WHERE int_cod_ref_io = $Cod_ref ORDER BY id_items DESC LIMIT 1";
	  $resultcosto=mysql_query($sqlcosto); 
	  $numcosto=mysql_num_rows($resultcosto); 
	  if ($numcosto > 0)
	  { 
		$valor_ins=mysql_result($resultcosto,0,'int_precio_io');
		echo $medRefConv = mysql_result($resultcosto,0,'str_unidad_io');
		if($medRefConv=='MILLAR')
		{
			$medidatipo="MILLAR";
			($valor_insumo=$valor_ins/1000);}
		else
		{
			$medidatipo="UNIDAD";
			$valor_insumo=$valor_ins;}
		  
	  }  
	  ?>
      </td>
      <td id="dato1">
      <?php  
	  //INVENTARIO INICIAL  
	  $saldo_inicial= $row_costos['SaldoInicial'];
	  if($saldo_inicial==''){echo "0";} else { ?><a href="javascript:verFoto('manteni.php?idInv=<?php echo $row_costos['idInv'] ?>&tipo=1','810','250')" style="text-decoration:none;"><?php echo $saldo_inicial ?></a><?php }?>
      </td>
      <td id="dato1">       
	  <?php
	  //ENTRADAS
	  $Cod_ref=$row_costos['Cod_ref'];
	  $sqlinv="SELECT SUM(bolsas_r) AS entrada FROM TblSelladoRollo WHERE ref_r='$Cod_ref' AND reproceso_r = '0' AND DATE(fechaI_r) BETWEEN '$fecha1' AND '$fecha2'";
	  $resultinv=mysql_query($sqlinv); 	
	  $numinv=mysql_num_rows($resultinv); 
	  if($numinv >= '1') 
	  {
		  echo $entrada=mysql_result($resultinv,0,'entrada');
	  }	  
 	  //echo $entrada=$row_costos['Entrada'];
	  ?></td>
      <td id="dato1"><?php
	  
	  //SALIDA REMISION SEGUN REMISION
	  $Cod_ref=$row_costos['Cod_ref'];
	  $sqlRem="SELECT SUM(int_cant_rd) AS salidas FROM Tbl_remisiones,Tbl_remision_detalle
	  WHERE Tbl_remisiones.int_remision=Tbl_remision_detalle.int_remision_r_rd  AND estado_rd='0' 
	  AND Tbl_remision_detalle.fecha_rd BETWEEN '$fecha1' AND '$fecha2' AND Tbl_remision_detalle.int_ref_io_rd='$Cod_ref'"; //int_ref_io_rd es el cod_ref
	  $resultRem=mysql_query($sqlRem); 
	  $numRem=mysql_num_rows($resultRem); 
	  if($numRem >= '1') 
	  { $salidaRem=mysql_result($resultRem,0,'salidas');}else { $salidaRem="0";} 
 	  ?> 
      <!-- inventario_edit.php-->
      <a href="javascript:verFoto('manteni.php?idInv=<?php echo $row_costos['idInv'] ?>&tipo=1','810','250')" style="text-decoration:none;"><?php echo $salidaRem; ?></a></td>
      <td id="dato1">
      <?php
	  //SALIDAS 
 	   echo $salidaExc=$row_costos['Salida'];
 	   ?>
      </td>
      <td id="dato1"><?php
	  //INVENTARIO FINAL
	  $saldo_final=(($saldo_inicial+$entrada)-$salidaRem);
	  if($saldo_final<$stok){
      echo "<span class='rojo_normal'>". numeros_format($saldo_final) ."</spam>";}else{echo numeros_format($saldo_final);}
	  ?> </td>
      <td id="dato1"></td>
      <td id="dato1">
 	  <!--VALOR UNIDAD-->
       <a href="javascript:verFoto('manteni.php?idInv=<?php echo $row_costos['idInv'] ?>&tipo=1','810','250')" style="text-decoration:none;"><?php echo $valor_insumo; ?></a></td>
      <td id="dato1">
	  <!--//COSTO INV-->
	  <a href="javascript:verFoto('manteni.php?idInv=<?php echo $row_costos['idInv'] ?>&tipo=2','810','250')" style="text-decoration:none;"><?php if($medidatipo=='MILLAR')
		{ 
			 
			echo $CostoUnd = ($row_costos['CostoUnd']/1000);
		}else{echo $CostoUnd= $row_costos['CostoUnd'];
		}?></a></td>     
      <td id="dato1"><?php
	  //COSTO FINAL 
	  $TotalCosto=($saldo_final*$valor_insumo); echo numeros_format($TotalCosto);
	  ?></td>
      <td nowrap="nowrap" id="dato1"> 
      <?php if (!(strcmp("1", $tipo))) {echo "PRODUCTO TERMINADO";} ?>
      <?php if (!(strcmp("2", $tipo))) {echo "MATERIAS PRIMAS";} ?>
      <?php if (!(strcmp("3", $tipo))) {echo "PRODUCTO EN PROCESO";} ?>
      <?php if (!(strcmp("4", $tipo))) {echo "MATERIA PRIMA EN PROCESO";} ?>
      </td>
      <td nowrap="nowrap" id="dato1"><?php echo $row_costos['Modifico'];?></td>
      <td nowrap="nowrap" id="dato1"><?php echo $row_costos['FechaModif'];?></td> 
      </tr>
    <?php } while ($row_costos = mysql_fetch_assoc($costos)); ?>
    <?php }?>     
    <!--MATERIAS PRIMAS-->
  <?php if($tipo=='2') {?>
    <tr id="tr1">
      <td class="listado4" style="width:9 px">ID</td> 
    <td class="listado4" style="width:9 px">CODIGO</td>
    <td class="listado4" style="width:17 px">DESCRIPCION</td>
    <td class="listado4" style="width:15 px">UNIDAD</td>
    <td class="listado4" style="width:5 px">INV. INICIAL</td>
    <td class="listado4" style="width:8 px">ENTRADAS</td>
    <td class="listado4" style="width:8 px">REMISION (Sistema)</td>
    <td class="listado4" style="width:8 px"><p>SALIDAS</p>
      <p>(Manual)</p></td>
    <td class="listado4" style="width:8 px">INVENTARIO FINAL</td>
    <td class="listado4" style="width:8 px">STOK</td>
    <td class="listado4" style="width:8 px">VALOR INSUMO</td>
    <td class="listado4" style="width:8 px">VALOR INV.</td>
    <td class="listado4" style="width:8 px">VALOR TOTAL</td> 
    <td class="listado4" style="width:10 px">TIPO</td>
    <td class="listado4" style="width:10 px">Modifico</td>
    <td class="listado4" style="width:10 px">Fecha Mod</td>
    </tr>
  <?php do { ?>
  <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
    <td id="dato1" nowrap="nowrap"><?php 
	    $id_insumo=$row_costos['Codigo'];  
	    $codigo=$row_costos['Codigo'];
		$query_descri = "SELECT id_insumo,codigo_insumo,descripcion_insumo,valor_unitario_insumo,stok_insumo,medida_insumo FROM insumo WHERE id_insumo=$codigo";
		$resultdescri=mysql_query($query_descri); 
		$numdescri=mysql_num_rows($resultdescri); 
		if($numdescri >= '1') 
		{
		$id_insumo=mysql_result($resultdescri,0,'id_insumo');
	    $medida_insumo=mysql_result($resultdescri,0,'medida_insumo');
		$codigo_insumo=mysql_result($resultdescri,0,'codigo_insumo');  
		$stok=mysql_result($resultdescri,0,'stok_insumo'); 
		$descripcion=mysql_result($resultdescri,0,'descripcion_insumo');
		$valor_insumo2=mysql_result($resultdescri,0,'valor_unitario_insumo');
		echo $id_insumo;
		}?></td>  
    <td id="dato1" nowrap="nowrap"> 
       <?php echo $codigo_insumo;?></td>
      <td id="dato1" nowrap="nowrap">
      <?php echo $descripcion; ?> 
      <td id="dato1">
      <?php 
	    $medida_insumo=$medida_insumo;
		$query_medidas = "SELECT nombre_medida FROM medida WHERE id_medida='$medida_insumo'";
		$resultmedidas=mysql_query($query_medidas); 
		$numMedida=mysql_num_rows($resultmedidas); 
		if($numMedida >= '1') 
		{
		$medidasInsumo=mysql_result($resultmedidas,0,'nombre_medida');
		echo $medidasInsumo;}?> 
         </td>
      <td id="dato1" ><?php
	   //INVENTARIO INICIAL  
	   $saldo_inicial= $row_costos['SaldoInicial'];
	   if($saldo_inicial==''){echo "0";} else { ?><a href="javascript:verFoto('manteni.php?idInv=<?php echo $row_costos['idInv'] ?>&tipo=1','810','250')" style="text-decoration:none;"><?php echo $saldo_inicial ?></a><?php }?></td>
      <td id="dato1">
       <?php
	   //ENTRADAS
	  $id_rpp_rp=$id_insumo;
	  $sqlinv="SELECT SUM(TblIngresos.ingreso_ing) AS entrada FROM TblIngresos WHERE id_insumo_ing=$id_rpp_rp AND fecha_ing BETWEEN '$fecha1' AND '$fecha2'"; 
	  $resultinv=mysql_query($sqlinv); 
	  $numinv=mysql_num_rows($resultinv); 
	  if($numinv >= '1') 
	  { $entrada=mysql_result($resultinv,0,'entrada');}
	 // $entrada=$row_costos['Entrada'];
	  ?>
      <a href="javascript:verFoto('manteni.php?idInv=<?php echo $row_costos['idInv'] ?>&tipo=1','810','250')" style="text-decoration:none;"><?php  echo numeros_format($entrada) ?></a>	 
	  </td>
      <td id="dato1">
	   <?php 
	   //PRODUCCION SALIDA TOTALIZADAS DE KILOS
	  $id_rpp_rp=$id_insumo;
	  $sqlsal="SELECT SUM(valor_prod_rp) AS consumo FROM Tbl_reg_kilo_producido WHERE id_rpp_rp='$id_rpp_rp' AND DATE(fecha_rkp) BETWEEN '$fecha1' AND '$fecha2'"; 
	  $resultsal=mysql_query($sqlsal); 
	  $numsal=mysql_num_rows($resultsal); 
	  if($numsal >= '1') 
	  { $salidasP=mysql_result($resultsal,0,'consumo'); echo numeros_format($salidasP);}else {$salidasP="0";}
	   echo $salidasP;  		  
	  ?>
       </td>
       <td id="dato1"><?php 
		//SALIDAS SEGUN POR DESCUENTOS UNO A UNO
		echo $salidas=$row_costos['Salida']; ?></td>
      <td id="dato1"><?php
	  //INVENTARIO FINAL
	  $saldo_final=(($saldo_inicial+$entrada)-$salidas);
	  if($saldo_final < $stok){
      echo "<span class='rojo_normal'>". numeros_format($saldo_final) ."</spam>";}else{echo numeros_format($saldo_final);}
	  ?></td>
      <td id="dato1"><?php 
		//STOK
		echo $stok; ?></td>
      <td id="dato1"> 
 	  <!--//VALOR O.C-->
      <?php echo numeros_format($valor_insumo2);?>
		 </td>
      <td id="dato1"><!--//COSTO INV-->
	  <a href="javascript:verFoto('manteni.php?idInv=<?php echo $row_costos['idInv'] ?>&tipo=2','810','250')" style="text-decoration:none;"><?php echo $CostoUnd= $row_costos['CostoUnd'];?></a></td>       
      <td id="dato1"> 
      <?php 
	  $TotalCosto=($saldo_final*$valor_insumo2); echo numeros_format($TotalCosto);
	  ?>       
       
       <td nowrap="nowrap" id="dato1">
 		<?php if (!(strcmp("1", $tipo))) {echo "PRODUCTO TERMINADO";} ?>
        <?php if (!(strcmp("2", $tipo))) {echo "MATERIAS PRIMAS";} ?>
        <?php if (!(strcmp("3", $tipo))) {echo "PRODUCTO EN PROCESO";} ?>
        <?php if (!(strcmp("4", $tipo))) {echo "MATERIA PRIMA EN PROCESO";} ?>
       </td>
       <td nowrap="nowrap" id="dato1"><?php echo $row_costos['Modifico'];?></td>
       <td nowrap="nowrap" id="dato1"><?php echo $row_costos['FechaModif'];?></td>         
    </tr>
    <?php } while ($row_costos = mysql_fetch_assoc($costos)); ?>
    <?php }?>
 </table>
</div>
<table id="tabla1">
  <tr>
    <td colspan="11" id="dato3"> 
        <input name="button2" id="button2" type="button" value="Guardar Inventario" onclick="return enviaForm('inserts.php?insert=<?php echo '0'; ?>');"/><!--?fecha_ini=<?php echo $fecha1 ?>&fecha_fin=<?php echo $fecha2 ?>&tipo=<?php echo $tipo ?>--></td>
    </tr>
</table>
  </form>
 
<?php echo $conexion->header('footer'); ?>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($costos);

?>