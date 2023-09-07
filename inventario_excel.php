<?php require_once('Connections/conexion1.php'); ?>
<?php
header('Pragma: public'); 
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past    
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); 
header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1 
header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1 
header('Pragma: no-cache'); 
header('Expires: 0'); 
header('Content-Transfer-Encoding: none'); 
header('Content-Type: application/vnd.ms-excel'); // This should work for IE & Opera 
header('Content-type: application/x-msexcel'); // This should work for the rest 
header('Content-Disposition: attachment; filename="Inventario.xls"');
?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
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
//VARIABLES DE CONTROL
	$fecha1 = $_GET['fecha_ini'];
	$fecha2 = $_GET['fecha_fin'];
	$tipo = $_GET['tipo']; 
 	//Filtra materias Primas
	mysql_select_db($database_conexion1, $conexion1);
 	$query_costos = "SELECT * FROM TblInventarioListado WHERE Tipo='$tipo' ORDER BY Codigo DESC";	
 	$costos = mysql_query($query_costos, $conexion1) or die(mysql_error());
	$row_costos = mysql_fetch_assoc($costos);
	$totalRows_costos = mysql_num_rows($costos);
  ?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
</head>
<body>
   <table id="Exportar_a_Excel" border="1">  
<tr id="tr1">
  <td nowrap="nowrap" id="titulo4">ID</td> 
    <td nowrap="nowrap" id="titulo4">CODIGO  </td>
    <td nowrap="nowrap" id="titulo4">DESCRIPCION</td>
    <td nowrap="nowrap" id="titulo4">UNIDAD</td>
    <td nowrap="nowrap" id="titulo4">INV. INICIAL</td>
    <td nowrap="nowrap" id="titulo4">ENTRADAS</td>
    <td nowrap="nowrap" id="titulo4">REMISION</td>
    <td nowrap="nowrap" id="titulo4">SALIDAS</td>
    <td nowrap="nowrap" id="titulo4">INVENTARIO FINAL</td>
    <td nowrap="nowrap" id="titulo4">STOK</td>
    <td nowrap="nowrap" id="titulo4">VALOR UNIDAD</td>
    <td nowrap="nowrap" id="titulo4">VALOR INV.</td>
    <td nowrap="nowrap" id="titulo4">COSTO TOTAL</td>
    <td nowrap="nowrap" id="titulo4">TIPO</td>
    <td nowrap="nowrap" id="titulo4">MODIFICO </td>
    <td nowrap="nowrap" id="titulo4">FECHA MOD</td>
  </tr>
  <!-- SI ES PRODUCTO TERMINADO-->
  <?php if($tipo=='1') {?>
  <?php do { ?>
  <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
    <td id="dato1" nowrap="nowrap">&nbsp;</td>
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
      <td id="dato1" nowrap="nowrap"><?php
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
	  ?></td>
      <td id="dato1">
      <?php  
	  //INVENTARIO INICIAL  
	  $saldo_inicial= $row_costos['SaldoInicial'];
	  if($saldo_inicial==''){echo "0";} else { echo $saldo_inicial; }?>
      </td>
      <td id="dato1">       
	  <?php
	  //ENTRADAS
	  $Cod_ref=$row_costos['Cod_ref'];
	  $sqlinv="SELECT SUM(bolsas_r) AS entrada FROM TblSelladoRollo WHERE ref_r=$Cod_ref AND reproceso_r = '0' AND DATE(fechaI_r) BETWEEN '$fecha1' AND '$fecha2'";
	  $resultinv=mysql_query($sqlinv); 	
	  $numinv=mysql_num_rows($resultinv); 
	  if($numinv >= '1') 
	  { 
	  echo $entrada=mysql_result($resultinv,0,'entrada');
	  }
 	 // echo $entrada=$row_costos['Entrada'];
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
	  { $salidaRem=mysql_result($resultRem,0,'salidas');  redondear_entero_puntos($salidaRem);}else { $salidaRem="0";}
 	   echo $salidaRem;?> </td>
      <td id="dato1">
      <?php
	  //SALIDAS 
      $salidaExc=$row_costos['Salida'];
 	  ?> </td>
      <td id="dato1"><?php
	  //INVENTARIO FINAL
	  $saldo_final=(($saldo_inicial+$entrada)-$salidaRem);
	  if($saldo_final<$stok){
      echo "<span class='rojo_normal'>". numeros_format($saldo_final) ."</spam>";}else{echo numeros_format($saldo_final);}
	  ?> </td>
      <td id="dato1"></td>
      <td id="dato1">
      <?php
	  //VALOR UNIDAD
       echo $valor_insumo; ?> </td>
      <td id="dato1"><a href="javascript:verFoto('inventario_edit.php?idInv=<?php echo $row_costos['idInv'] ?>&tipo=2','810','250')" style="text-decoration:none;">
        <?php if($medidatipo=='MILLAR')
		{ 
			 
			echo $CostoUnd = ($row_costos['CostoUnd']/1000);
		}else{echo $CostoUnd= $row_costos['CostoUnd'];
		}?>
      </a></td>     
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
  <?php do { 
  ?>
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
    <td id="dato1" nowrap="nowrap"><?php echo $codigo_insumo;?></td>
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
		$medidasInsumo=mysql_result($resultmedidas,0,'nombre_medida');echo $medidasInsumo;}?> 
         </td>
      <td id="dato1" ><?php
	  //INVENTARIO INICIAL  
	  $saldo_inicial= $row_costos['SaldoInicial'];
	   if($saldo_inicial==''){echo "0";} else { echo $saldo_inicial; }?></td>
      <td id="dato1">
       <?php
	   //ENTRADAS
	  $id_rpp_rp=$id_insumo;
	  $sqlinv="SELECT SUM(TblIngresos.ingreso_ing) AS entrada FROM TblIngresos WHERE id_insumo_ing=$id_rpp_rp AND fecha_ing BETWEEN '$fecha1' AND '$fecha2'"; 
	  $resultinv=mysql_query($sqlinv); 
	  $numinv=mysql_num_rows($resultinv); 
	  if($numinv >= '1') 
	  { $entrada=mysql_result($resultinv,0,'entrada');}
	   echo numeros_format($entrada) ?> 
	  </td>
      <td id="dato1">
	   <?php 
	   //PRODUCCION SALIDA TOTALIZADAS DE KILOS
	  $id_rpp_rp=$id_insumo;
	  $sqlsal="SELECT SUM(valor_prod_rp) AS consumo FROM Tbl_reg_kilo_producido WHERE id_rpp_rp='$id_rpp_rp' AND DATE(fecha_rkp) BETWEEN '$fecha1' AND '$fecha2'"; 
	  $resultsal=mysql_query($sqlsal); 
	  $numsal=mysql_num_rows($resultsal); 
	  if($numsal >= '1') 
	  { $salidasP=mysql_result($resultsal,0,'consumo'); echo numeros_format($salidasP);}else {$salidasP= "0";} 	
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
 	  
      <?php 
	  //VALOR UNIDAD
	  echo numeros_format($valor_insumo2);?>
		 </td>
      <td id="dato1"><a href="javascript:verFoto('inventario_edit.php?idInv=<?php echo $row_costos['idInv'] ?>&tipo=2','810','250')" style="text-decoration:none;"><?php echo $CostoUnd= $row_costos['CostoUnd'];?></a>       
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
</body>
</html>
<?php
mysql_free_result($usuario);
mysql_free_result($costos);

?>