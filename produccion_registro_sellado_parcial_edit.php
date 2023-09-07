<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
?> 
<?php 
 require_once('Connections/conexion1.php');
 require_once("db/db.php");
 require_once 'Models/Mgeneral.php'; 
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


$conexion = new ApptivaDB();//consultas

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
   	      //COSTO SELLADO
		  $id_op=$_POST['id_op_rp'];
	      $metros_imp=$_POST['metro_r2']; 
		  $bolsas_sell=$_POST['bolsa_rp']; 
 		  $KILOSREALESSELL=$_POST['int_total_kilos_rp'];
 		  $FECHA_NOVEDAD_SELL = quitarHora($_POST['fecha_ini_rp']);//quita hora 	 
          $horas_sell = $_POST['total_horas_rp'];
 	  	  //MATERIA PRIMA
	   	  mysql_select_db($database_conexion1, $conexion1);
		  $queryref = "SELECT * FROM Tbl_orden_produccion,Tbl_referencia  WHERE Tbl_orden_produccion.id_op='$id_op' AND Tbl_orden_produccion.id_ref_op=Tbl_referencia.id_ref";
		  $resultref=mysql_query($queryref); 
		  $numcostoMP=mysql_num_rows($resultref); 
		  $row_referencia = mysql_fetch_assoc($resultref);
   		 
		  $tipo_cinta = $row_referencia['tipoCinta_ref'];//TIPO DE CINTA O LINER
		  $id_ter=$row_referencia['id_termica_op'];

		  //SI LLEVA CINTA TERMICA
 	      $sqlterm="SELECT valor_unitario_insumo FROM insumo WHERE id_insumo='$id_ter'"; 
		  $resultterm=mysql_query($sqlterm); 
		  $numterm=mysql_num_rows($resultterm);  
 		  if($numterm >= '1')  
		  { 
		  $valor_term=mysql_result($resultterm,0,'valor_unitario_insumo');
		  $RealmetrosTermica = ($row_referencia['cinta_termica_op']*$bolsas_sell);//por el ancho real de la cinta termica
		  $costoTermica=($RealmetrosTermica * $valor_term);
		  }else{$costoTermica='0';}

  		  $tipo = $row_referencia['adhesivo_ref'];//HOTMELT O CINTA
  		  if($tipo=='HOT MELT')//EVALUO QUE SEA HOT PORQ SE COSTEA EN KILO
          {
		  //LINER
		  $sqlliner="SELECT `id_insumo`,`valor_unitario_insumo` FROM `insumo` WHERE `id_insumo` = '$tipo_cinta'";
		  $resultliner=mysql_query($sqlliner);
          $valorLiner = mysql_result($resultliner,0,'valor_unitario_insumo');
		  $costoliner = $metros_imp * $valorLiner;//valor liner por metro lineal
		  //PEGANTE
		  $sqlpega="SELECT `id_insumo`,`valor_unitario_insumo` FROM `insumo` WHERE `id_insumo` = '1695'";//1695 codigo del pegante aplicado es general
		  $resultpega=mysql_query($sqlpega);
          $valorpega = mysql_result($resultpega,0,'valor_unitario_insumo');//VALOR DEL KILO DE PEGA
		  $metrosakilospega=adhesivos($tipo,$metros_imp);//1.2 LOS GRAMOS EN 1 METRO LINEAL Y 1000 GRAMOS EN 1 KILO 
 		  $costopega = ($metrosakilospega * $valorpega);
  		  $costoHotmelOcinta = ($costoliner+$costopega);// El precio total de hotmelt
		  }else{
		  //CINTA SEGURIDAD
 		  $sqlcostoMP="SELECT valor_unitario_insumo AS VALORMETRO FROM insumo WHERE insumo.id_insumo = '$tipo_cinta'"; 
		  $resultcostoMP=mysql_query($sqlcostoMP); 
		  $numcostoMP=mysql_num_rows($resultcostoMP); 
		  $row_valoresMP = mysql_fetch_assoc($resultcostoMP);
		  if($numcostoMP >= '1')  
		  { 
		  $valorMPcinta = $row_valoresMP['VALORMETRO'];
		  $costoHotmelOcinta = $metros_imp * $valorMPcinta;//esto pasa a dinero
 		  } 			  
 		 }
		  //SUMA CINTA SEGURIDAD Y TERMICA, BOLSILLO, HOTMEL
 		  $pesoMbols = millarBolsillo($row_referencia['ancho_ref'],$row_referencia['largo_ref'],$row_referencia['calibreBols_ref']) ;
	      $tipoLm = $row_referencia['tipoLamina_ref'];
	      $sqlrbols="SELECT valor_unitario_insumo FROM insumo WHERE id_insumo = '$tipoLm'";
	      $resultrbols= mysql_query($sqlrbols);
	      $numrbols = mysql_num_rows($resultrbols);
	      if($numrbols >='1')
	      { 
	      $valor_bols = mysql_result($resultrbols, 0, 'valor_unitario_insumo'); 
		  $costo_bols = ($valor_bols*$pesoMbols);
 	      }else{$costo_bols="0";}	
		  $COSTOMPSELLADO=($costoTermica+$costoHotmelOcinta+$costo_bols);
    
		  $sqlgeneral="SELECT * FROM `TblDistribucionHoras` ORDER BY `fecha` DESC LIMIT 1";
		  $resultgeneral= mysql_query($sqlgeneral);
		  $numgeneral= mysql_num_rows($resultgeneral);
		  if($numgeneral >='1')
		  { 
		  $TiempomeSell =  mysql_result($resultgeneral, 0, 'sellado');
		  //IMPRESION
		  $costoUnHGga_sell = mysql_result($resultgeneral, 0, 'gga_sell');
		  $costoUnHCif_sell = mysql_result($resultgeneral, 0, 'cif_sell');
		  $costoUnHGgv_sell = mysql_result($resultgeneral, 0, 'ggv_sell');
		  $costoUnHGgf_sell = mysql_result($resultgeneral, 0, 'ggf_sell');
		  $cifyggaSell=($costoUnHGga_sell+$costoUnHCif_sell+$costoUnHGgv_sell+$costoUnHGgf_sell);
		  }else{$TiempomeSell='0';} 

	//SUELDOS DE TODOS LOS EMPLEADOS FUERA DE PROCESO 
	$sqlbasicoSell="SELECT COUNT(a.codigo_empleado) AS operarios,(a.horas_empleado) AS HORADIA, SUM(b.sueldo_empleado) AS SUELDO, SUM(b.aux_empleado) AS AUXILIO, SUM(c.total) AS APORTES
FROM empleado a 
LEFT JOIN TblProcesoEmpleado b ON a.codigo_empleado=b.codigo_empleado  
LEFT JOIN TblAportes c ON a.codigo_empleado=c.codigo_empl 
WHERE b.estado_empleado='1' AND a.tipo_empleado NOT IN(4,5,6,7,8,9,10)";//NOT IN(4,5,6,7,8,9,10) son los que estan fuera de los procesos SE AGREGO b.estado_empleado='1' AND
	$resultbasicoSell=mysql_query($sqlbasicoSell);
    $operario_sell_demas=mysql_result($resultbasicoSell,0,'operarios');
	$sueldo_basSell=mysql_result($resultbasicoSell,0,'SUELDO'); //sueldos del mes 
	$auxilio_basSell=mysql_result($resultbasicoSell,0,'AUXILIO'); //auxilios trans del mes 	  
	$aportes_basSell=mysql_result($resultbasicoSell,0,'APORTES'); //aportes del mes 
 	//$horasmes_bas=mysql_result($resultbasico,0,'HORASMES');//LO EQUIVALENTE A LAS HORAS QUE SE TRABAJAN EN UN MES REAL 186,6666667 SE ENCUENTRA EN FACTOR
	$operarios_basSell=mysql_result($resultbasicoSell,0,'operarios');//CANTIDAD DE OPERARIOS 
	$horasdia_basSell=mysql_result($resultbasicoSell,0,'HORADIA');//esto es 8 
	 	 
	//NOVEDAD DE TODOS LOS EMPLEADOS FUERA DE PROCESO 
 	$sqlnovbasicoSell="SELECT SUM(b.pago_acycia) as pago,SUM(b.horas_extras) as extras,SUM(b.recargos) as recargo,SUM(b.festivos) AS festivos 
FROM empleado a 
LEFT JOIN TblNovedades b ON a.codigo_empleado=b.codigo_empleado 
WHERE a.tipo_empleado NOT IN(4,5,6,7,8,9,10) AND b.fecha BETWEEN DATE_FORMAT('$FECHA_NOVEDAD_SELL', '%Y-%m-01') AND DATE_FORMAT('$FECHA_NOVEDAD_SELL', '%Y-%m-31')";//NOT IN(4,5,6,7,8,9,10) son los que estan fuera de los procesos
	$resultnovbasicoSell=mysql_query($sqlnovbasicoSell);	
	$pago_novbasicoSell=mysql_result($resultnovbasicoSell,0,'pago'); 
	$extras_novbasicoSell=mysql_result($resultnovbasicoSell,0,'extras');  
	$recargo_novbasicoSell=mysql_result($resultnovbasicoSell,0,'recargo');
	$festivo_novbasicoSell=mysql_result($resultnovbasicoSell,0,'festivos');
	$horasmes_sell='240';//240 mientras se define horas al mes
	//OPERO TODOS LOS SUELDOS ETC, PARA SACAR EL COSTO HORA DE LOS FUERA DE PROCESOS
  	$valorhoraxoperSellDemas = sueldoMes($sueldo_basSell,$auxilio_basSell,$aportes_basSell,$horasmes_sell,$horasdia_basSell,$recargo_novbasicoSell,$festivo_novbasicoSell); 
	$valorHoraSellDemas = ($valorhoraxoperSellDemas/$operario_sell_demas)/3;//total Horas se divide por # de operarios de fuera de los procesos dividido en 3 q son los procesos
  
  	//SUELDOS DE TODOS LOS EMPLEADOS DENTRO DE SELLADO 
	$sqlbasicoSell="SELECT COUNT(a.codigo_empleado) AS operarios,(a.horas_empleado) AS HORADIA, SUM(b.sueldo_empleado) AS SUELDO, SUM(b.aux_empleado) AS AUXILIO, SUM(c.total) AS APORTES
FROM empleado a 
LEFT JOIN TblProcesoEmpleado b ON a.codigo_empleado=b.codigo_empleado  
LEFT JOIN TblAportes c ON a.codigo_empleado=c.codigo_empl 
WHERE b.estado_empleado='1' AND a.tipo_empleado IN(7,9)";//IN(5,10) son impresion
	$resultbasicoSell=mysql_query($sqlbasicoSell);
	$operario_sell=mysql_result($resultbasicoSell,0,'operarios');
	$sueldo_basSell=mysql_result($resultbasicoSell,0,'SUELDO'); //sueldos del mes 
	$auxilio_basSell=mysql_result($resultbasicoSell,0,'AUXILIO'); //auxilios trans del mes 	  
	$aportes_basSell=mysql_result($resultbasicoSell,0,'APORTES'); //aportes del mes 
	$horasdia_basSell=mysql_result($resultbasicoSell,0,'HORADIA');//esto es 8 
	$horasmes_sell='240';//240 mientras se define horas al mes
	 //FIN	 
	 //NOVEDAD DE ESE MES DE TODOS LOS EMPLEADOS DENTRO DE IMPRESION 
  	$sqlnovbasicoSell="SELECT SUM(b.pago_acycia) as pago,SUM(b.horas_extras) as extras,SUM(b.recargos) as recargo,SUM(b.festivos) AS festivos 
FROM empleado a 
LEFT JOIN TblNovedades b ON a.codigo_empleado=b.codigo_empleado 
WHERE a.tipo_empleado IN(7,9) AND b.fecha BETWEEN DATE_FORMAT('$FECHA_NOVEDAD_SELL', '%Y-%m-01') AND DATE_FORMAT('$FECHA_NOVEDAD_SELL', '%Y-%m-31')";//IN(5,10)novedad impresion 
	$resultnovbasicoSell=mysql_query($sqlnovbasicoSell);	
	$pago_novbasicoSell=mysql_result($resultnovbasicoSell,0,'pago'); 
	$extras_novbasicoSell=mysql_result($resultnovbasicoSell,0,'extras');  
	$recargo_novbasicoSell=mysql_result($resultnovbasicoSell,0,'recargo');
	$festivo_novbasicoSell=mysql_result($resultnovbasicoSell,0,'festivos');
	//FIN
	//OPERO TODOS LOS SUELDOS ETC, PARA SACAR EL COSTO HORA DE LOS FUERA DE PROCESOS
 	$valorhoraTodosSell = sueldoMes($sueldo_basSell,$auxilio_basSell,$aportes_basSell,$horasmes_sell,$horasdia_basSell,$recargo_novbasicoSell,$festivo_novbasicoSell);
	$kiloXHoraSell=($KILOSREALESSELL/$horas_sell);//kilo x hora para los cif y gga
	$valorHoraSell = ($valorhoraTodosSell/$operario_sell);//total Horas se divide por # de operarios de Impresion	  
  	$costokiloInsumoSell=($COSTOMPSELLADO/$KILOSREALESSELL);//$ costo de 1 kilos mp
	$manoObraSell=($horas_sell*($valorHoraSell+$valorHoraSellDemas))/$KILOSREALESSELL;//$ costo de 1 kilo mano obra en 1 hora
  	$valorkilocifyggaSell=($cifyggaSell/$kiloXHoraSell);// $kiloXHora valor por hora de cif y gga  
	 
 	 $COSTOHORAKILOSELL =($costokiloInsumoSell+$manoObraSell+$valorkilocifyggaSell);
     //FIN DE EVALUACION DEL COSTO


//UPDATE LA TABLA DE INVENTARIOS DESCONTANDO LO QUE SE GASTO
 //UPDATE AL INVENTARIO LA REFERENCIA DE BOLSA
 		$id_opInv=$_POST['id_r'];
		$idInv=$_POST['ref_inven']; 
 		$sqlre="SELECT bolsas_r,metro_r FROM TblSelladoRollo WHERE id_r = $id_opInv";
		$resultre= mysql_query($sqlre);
		$numere= mysql_num_rows($resultre);
		if($numere >0)
		{
			$bolsas_r=mysql_result($resultre,0,'bolsas_r');
			$metros_r=mysql_result($resultre,0,'metro_r');  
		}
		$sqlinv="UPDATE TblInventarioListado SET Entrada = Entrada - $bolsas_r WHERE Codigo = '$idInv'";
		$resultinv=mysql_query($sqlinv, $conexion1);
 	    //ACTUALIZO LOS NUEVOS VALORES
		$canbolsas=$_POST['bolsa_rp'];
        $sqlinv2="UPDATE TblInventarioListado SET Entrada = Entrada + $canbolsas WHERE Codigo = '$idInv'";
        $resultinv2=mysql_query($sqlinv2, $conexion1); 

  //UPDATE LA TABLA DE INVENTARIOS DESCONTANDO LO QUE SE GASTO INSUMOS
       foreach($_POST['tipoCinta_ref'] as $key=>$h)
       $cinta[]= $h;
	   for($t=0; $t<count($cinta); $t++) {
		  if(!empty($cinta[$t])){ 
$updateINV = sprintf("UPDATE TblInventarioListado SET Salida = Salida - $metros_r + %s WHERE Codigo = %s",
					   GetSQLValueString($_POST['metro_r2'], "text"),
                       GetSQLValueString($cinta[$t], "int"));
  mysql_select_db($database_conexion1, $conexion1);
  $ResultINV = mysql_query($updateINV, $conexion1) or die(mysql_error());
		  }
	}
 //FIN


		//EDITAR ROLLO
		$myObjectRollo = new omGeneral();
		//$nuevovalor = new omGeneral();
		$id_rpupdateRollo = $_POST['id_r'];
	  $parcialono = isset($_POST['rolloParcial_r']) ? "1" : "0" ;
		$nuevovalorRollo = "ref_r='".$_POST['int_cod_ref_rp']."',bolsas_r='".$_POST['bolsa_rp']."',metro_r='".$_POST['metro_r2']."',metroIni_r='".$_POST['metroIni_r']."',kilos_r='".$_POST['int_total_kilos_rp']."',reproceso_r='".$_POST['reproceso']."',rollo_r='".$_POST['rollo_rp']."',maquina_r='".$_POST['str_maquina_rp']."',numIni_r='".$_POST['n_ini_rp']."',numFin_r='".$_POST['n_fin_rp']."',cod_empleado_r='".$_POST['int_cod_empleado_rp']."',cod_auxiliar_r='".$_POST['int_cod_liquida_rp']."',turno_r='".$_POST['turno_rp']."',fechaI_r='".$_POST['fecha_ini_rp']."',fechaF_r='".$_POST['fecha_fin_rp']."',kilopendiente_r='".$_POST['kiloSistema']."',rolloParcial_r='".$parcialono."',costo_r='$COSTOHORAKILOSELL' ";
		
		
		 if(isset($_POST['id_r']) && $nuevovalorRollo){
		    $myObjectRollo->UpdateGen("id_r",$id_rpupdateRollo,$nuevovalorRollo,"tblselladorollo");
		  }//FIN 

		
/*  $updateSQL2 = sprintf("UPDATE TblSelladoRollo SET ref_r=%s, bolsas_r=%s, metro_r=%s, metroIni_r=%s, kilos_r=%s, reproceso_r=%s, rollo_r=%s,  maquina_r=%s, numIni_r=%s, numFin_r=%s, cod_empleado_r=%s, cod_auxiliar_r=%s, turno_r=%s, fechaI_r=%s, fechaF_r=%s, kilopendiente_r=%s, rolloParcial_r=%s, costo_r=%s WHERE id_r=%s",
                       //GetSQLValueString($_POST['id_op_rp'], "int"),
                        GetSQLValueString($_POST['int_cod_ref_rp'], "text"),
                        GetSQLValueString($_POST['bolsa_rp'], "int"),
                        GetSQLValueString($_POST['metro_r2'], "int"),
                        GetSQLValueString($_POST['metroIni_r'], "int"),
                        GetSQLValueString($_POST['int_total_kilos_rp'], "double"),
                        GetSQLValueString($_POST['reproceso'], "double"),
                        GetSQLValueString($_POST['rollo_rp'], "int"),
                        GetSQLValueString($_POST['str_maquina_rp'], "int"),
                        GetSQLValueString($_POST['n_ini_rp'], "text"),
                        GetSQLValueString($_POST['n_fin_rp'], "text"),
                        GetSQLValueString($_POST['int_cod_empleado_rp'], "int"),
                        GetSQLValueString($_POST['int_cod_liquida_rp'], "int"),
                        GetSQLValueString($_POST['turno_rp'], "int"),
                        GetSQLValueString($_POST['fecha_ini_rp'], "text"),
                        GetSQLValueString($_POST['fecha_fin_rp'], "text"),
                        GetSQLValueString($_POST['kiloSistema'], "double"),
                        GetSQLValueString(isset($_POST['rolloParcial_r']) ? "true" : "", "defined","1","0"),
                        GetSQLValueString($COSTOHORAKILOSELL, "double"),
                        GetSQLValueString($_POST['id_r'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result12 = mysql_query($updateSQL2, $conexion1) or die(mysql_error()); 	*/
 
 	//SUMA VALORES ACUMULADOS EN ROLLOS
    $id_op=$_POST['id_op_rp']; 
	$rolloN=$_POST['rollo_rp'];
	$sqltotal="SELECT MIN(`fechaI_r`) AS fechaini, rollo_r, SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(`fechaF_r`, `fechaI_r`)))) TotalHoras, SUM(`bolsas_r`) AS BOLSAS, SUM(`metro_r`) AS METROS,  SUM(`kilos_r`) AS KILOS, SUM(costo_r) AS costoT FROM `TblSelladoRollo` WHERE id_op_r= '$id_op' AND rollo_r='$rolloN' GROUP BY `rollo_r` ASC";
	$resulttotal= mysql_query($sqltotal);
	$numtotal= mysql_num_rows($resulttotal);
	
	if($numtotal >='1') {
	$fechainicial=mysql_result($resulttotal,0,'fechaini');
	$total_bolsas=mysql_result($resulttotal,0,'BOLSAS');
	$total_metros=mysql_result($resulttotal,0,'METROS');
	$total_horas=mysql_result($resulttotal,0,'TotalHoras');
 	$total_kilos=mysql_result($resulttotal,0,'KILOS');
	$total_sistema=(($_POST['int_kilos_prod_rp'])-($total_kilos+$_POST['int_kilos_desp_rp']+$_POST['acumulado']));//actualizo el nuevo kilo sistema en liquidacion 
	$total_costo=mysql_result($resulttotal,0,'costoT');
 	$kiloHora=$total_kilos/horadecimalUna($total_horas);
 	 }
	  
 if ((isset($_POST["rolloParcial_r"])) && ($_POST["rolloParcial_r"] == "2")) {
    // 2 TOTALIZA ROLLOS
    
    $myObject = new omGeneral();
    //$nuevovalor = new omGeneral();
    $id_rpupdate = $_POST['id_rp'];

    $nuevovalor = "id_ref_rp='".$_POST['id_ref_rp']."',int_cod_ref_rp='".$_POST['int_cod_ref_rp']."',version_ref_rp='".$_POST['version_ref_rp']."',placa_rp='".$_POST['placa_rp']."',bolsa_rp='".$total_bolsas."',lam1_rp='".$_POST['lam1_rp']."',lam2_rp='".$_POST['lam2_rp']."',turno_rp='".$_POST['turno_rp']."',rollo_rp='".$_POST['rollo_rp']."',n_ini_rp='".$_POST['n_ini_rp']."',n_fin_rp='".$_POST['n_fin_rp']."',int_kilos_prod_rp='".$_POST['int_kilos_prod_rp']."',int_kilos_desp_rp='".$_POST['acumulado']."',int_total_kilos_rp='".$total_kilos."',porcentaje_op_rp='".$_POST['porcentaje']."',int_metro_lineal_rp='".$total_metros."',int_total_rollos_rp='".$_POST['int_total_rollos_rp']."',total_horas_rp='".$total_horas."',rodamiento_rp='".$total_horas."',horas_muertas_rp='".$_POST['horas_muertas_rp']."',horas_prep_rp='".$_POST['horas_prep_rp']."',str_maquina_rp='".$_POST['str_maquina_rp']."',str_responsable_rp='".$_POST['str_responsable_rp']."',fecha_ini_rp='".$fechainicial."',fecha_fin_rp='".$_POST['fecha_fin_rp']."',int_kilosxhora_rp='".$kiloHora."',int_metroxmin_rp='".$_POST['int_metroxmin_rp']."',int_cod_empleado_rp='".$_POST['int_cod_empleado_rp']."',int_cod_liquida_rp='".$_POST['int_cod_liquida_rp']."',kiloFaltante_rp='".$_POST['kiloSistema'] ."',costo='$total_costo'";
    

   if(isset($_POST['id_rp']) && $nuevovalor){
      $myObject->UpdateGen("id_rp",$id_rpupdate,$nuevovalor,"tbl_reg_produccion");
    }//FIN
    
/*
    $updateSQL = sprintf("UPDATE Tbl_reg_produccion SET id_ref_rp=%s, int_cod_ref_rp=%s, version_ref_rp=%s, placa_rp=%s, bolsa_rp=%s, lam1_rp=%s, lam2_rp=%s, turno_rp=%s, rollo_rp=%s, n_ini_rp=%s, n_fin_rp=%s, int_kilos_prod_rp=%s, int_kilos_desp_rp=%s, int_total_kilos_rp=%s, porcentaje_op_rp=%s, int_metro_lineal_rp=%s, int_total_rollos_rp=%s, total_horas_rp=%s, rodamiento_rp=%s, horas_muertas_rp=%s, horas_prep_rp=%s, str_maquina_rp=%s, str_responsable_rp=%s, fecha_ini_rp=%s, fecha_fin_rp=%s, int_kilosxhora_rp=%s, int_metroxmin_rp=%s, int_cod_empleado_rp=%s,int_cod_liquida_rp=%s, kiloFaltante_rp=%s, costo=%s WHERE id_rp=%s",
             GetSQLValueString($_POST['id_ref_rp'], "int"),
             GetSQLValueString($_POST['int_cod_ref_rp'], "text"),
             GetSQLValueString($_POST['version_ref_rp'], "int"),
             GetSQLValueString($_POST['placa_rp'], "text"),
             GetSQLValueString($total_bolsas, "int"),
             GetSQLValueString($_POST['lam1_rp'], "double"),
             GetSQLValueString($_POST['lam2_rp'], "double"),
             GetSQLValueString($_POST['turno_rp'], "int"), 
             GetSQLValueString($_POST['rollo_rp'], "int"),
             GetSQLValueString($_POST['n_ini_rp'], "text"),
             GetSQLValueString($_POST['n_fin_rp'], "text"),					   
             GetSQLValueString($_POST['int_kilos_prod_rp'], "double"),
             GetSQLValueString($_POST['acumulado'], "text"),
             GetSQLValueString($total_kilos, "double"),
             GetSQLValueString($_POST['porcentaje'], "int"),
             GetSQLValueString($total_metros, "int"),
             GetSQLValueString($_POST['int_total_rollos_rp'], "int"),					   
             GetSQLValueString($total_horas, "text"),
             GetSQLValueString($total_horas, "text"),
             GetSQLValueString($_POST['horas_muertas_rp'], "text"), 
             GetSQLValueString($_POST['horas_prep_rp'], "text"),              
             GetSQLValueString($_POST['str_maquina_rp'], "text"),
             GetSQLValueString($_POST['str_responsable_rp'], "text"),
             GetSQLValueString($fechainicial, "date"),
             GetSQLValueString($_POST['fecha_fin_rp'], "date"),
             GetSQLValueString($kiloHora, "double"),
             GetSQLValueString($_POST['int_metroxmin_rp'], "double"),
             GetSQLValueString($_POST['int_cod_empleado_rp'], "int"),
             GetSQLValueString($_POST['int_cod_liquida_rp'], "int"),
					   GetSQLValueString($_POST['kiloSistema'], "double"),//$total_sistema
					   GetSQLValueString($total_costo, "int"),		   
					   GetSQLValueString($_POST['id_rp'], "int"));
				   					   					   
  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());	*/
 }else{ 
	//ACTUALIZA NORMAL
	

 	$myObject = new omGeneral();
 	//$nuevovalor = new omGeneral();
 	$id_rpupdate = $_POST['id_rp'];

 	$nuevovalor = "id_ref_rp='".$_POST['id_ref_rp']."',int_cod_ref_rp='".$_POST['int_cod_ref_rp']."',version_ref_rp='".$_POST['version_ref_rp']."',placa_rp='".$_POST['placa_rp']."',bolsa_rp='".$_POST['bolsa_rp']."',lam1_rp='".$_POST['lam1_rp']."',lam2_rp='".$_POST['lam2_rp']."',turno_rp='".$_POST['turno_rp']."',rollo_rp='".$_POST['rollo_rp']."',n_ini_rp='".$_POST['n_ini_rp']."',n_fin_rp='".$_POST['n_fin_rp']."',int_kilos_prod_rp='".$_POST['int_kilos_prod_rp']."',int_kilos_desp_rp='".$_POST['int_kilos_desp_rp']."',int_total_kilos_rp='".$_POST['int_total_kilos_rp']."',porcentaje_op_rp='".$_POST['porcentaje']."',int_metro_lineal_rp='".$_POST['metro_r2']."',int_total_rollos_rp='".$_POST['int_total_rollos_rp']."',total_horas_rp='".$_POST['total_horas_rp']."',rodamiento_rp='".$_POST['rodamiento_rp']."',horas_muertas_rp='".$_POST['horas_muertas_rp']."',horas_prep_rp='".$_POST['horas_prep_rp']."',str_maquina_rp='".$_POST['str_maquina_rp']."',str_responsable_rp='".$_POST['str_responsable_rp']."',fecha_ini_rp='".$_POST['fecha_ini_rp']."',fecha_fin_rp='".$_POST['fecha_fin_rp']."',int_kilosxhora_rp='".$_POST['int_kilosxhora_rp']."',int_metroxmin_rp='".$_POST['int_metroxmin_rp']."',int_cod_empleado_rp='".$_POST['int_cod_empleado_rp']."',int_cod_liquida_rp='".$_POST['int_cod_liquida_rp']."',kiloFaltante_rp='$total_sistema',costo='$total_costo'";
 	
 	
 	 if(isset($_POST['id_rp']) && $nuevovalor){
 	    $myObject->UpdateGen("id_rp",$id_rpupdate,$nuevovalor,"tbl_reg_produccion");
 	  }//FIN 

/*   $updateSQL = sprintf("UPDATE Tbl_reg_produccion SET id_ref_rp=%s, int_cod_ref_rp=%s, version_ref_rp=%s, placa_rp=%s, bolsa_rp=%s, lam1_rp=%s, lam2_rp=%s, turno_rp=%s, rollo_rp=%s, n_ini_rp=%s, n_fin_rp=%s, int_kilos_prod_rp=%s, int_kilos_desp_rp=%s, int_total_kilos_rp=%s, porcentaje_op_rp=%s, int_metro_lineal_rp=%s, int_total_rollos_rp=%s, total_horas_rp=%s, rodamiento_rp=%s, horas_muertas_rp=%s, horas_prep_rp=%s, str_maquina_rp=%s, str_responsable_rp=%s, fecha_ini_rp=%s, fecha_fin_rp=%s, int_kilosxhora_rp=%s, int_metroxmin_rp=%s, int_cod_empleado_rp=%s,int_cod_liquida_rp=%s, kiloFaltante_rp=%s, costo=%s WHERE id_rp=%s",
                        GetSQLValueString($_POST['id_ref_rp'], "int"),
                        GetSQLValueString($_POST['int_cod_ref_rp'], "text"),
                        GetSQLValueString($_POST['version_ref_rp'], "int"),
                        GetSQLValueString($_POST['placa_rp'], "text"),
                        GetSQLValueString($_POST['bolsa_rp'], "int"),
                        GetSQLValueString($_POST['lam1_rp'], "double"),
                        GetSQLValueString($_POST['lam2_rp'], "double"),
                        GetSQLValueString($_POST['turno_rp'], "int"), 
                        GetSQLValueString($_POST['rollo_rp'], "int"),
                        GetSQLValueString($_POST['n_ini_rp'], "text"),
                        GetSQLValueString($_POST['n_fin_rp'], "text"),					   
                        GetSQLValueString($_POST['int_kilos_prod_rp'], "double"),
                        GetSQLValueString($_POST['int_kilos_desp_rp'], "text"),
                        GetSQLValueString($_POST['int_total_kilos_rp'], "double"),
                        GetSQLValueString($_POST['porcentaje'], "int"),
                        GetSQLValueString($_POST['metro_r2'], "int"),
                        GetSQLValueString($_POST['int_total_rollos_rp'], "int"),					   
                        GetSQLValueString($_POST['total_horas_rp'], "text"),
                        GetSQLValueString($_POST['rodamiento_rp'], "text"),
                        GetSQLValueString($_POST['horas_muertas_rp'], "text"), 
                        GetSQLValueString($_POST['horas_prep_rp'], "text"),              
                        GetSQLValueString($_POST['str_maquina_rp'], "text"),
                        GetSQLValueString($_POST['str_responsable_rp'], "text"),
                        GetSQLValueString($_POST['fecha_ini_rp'], "date"),
                        GetSQLValueString($_POST['fecha_fin_rp'], "date"),
                        GetSQLValueString($_POST['int_kilosxhora_rp'], "double"),
                        GetSQLValueString($_POST['int_metroxmin_rp'], "double"),
                        GetSQLValueString($_POST['int_cod_empleado_rp'], "int"),
                        GetSQLValueString($_POST['int_cod_liquida_rp'], "int"),
                        GetSQLValueString($total_sistema, "double"),
                        GetSQLValueString($total_costo, "int"),		   
                        GetSQLValueString($_POST['id_rp'], "int"));
				   					   					   
  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());*/

	

 }//else fin
 //SI 2 GUARDA EL PARCIAL ES PORQUE SE CIERRA COMPLETAMENTE EL ROLLO ENTONCES SE PASAN TODOS LOS ROLLOS DE PARCIAL --> TOTAL
 if((isset($_POST['rolloParcial_r'])) && ($_POST['rolloParcial_r'] == "2")){
  $updateSQL3 = sprintf("UPDATE TblSelladoRollo SET rolloParcial_r='0' WHERE id_op_r=%s", 
		GetSQLValueString($_POST['id_op_rp'], "int"));
  mysql_select_db($database_conexion1, $conexion1);
  $Result3 = mysql_query($updateSQL3, $conexion1) or die(mysql_error()); 
 }  
 //DESPERDICIOS Y TIEMPOS
 if (!empty ($_POST['id_rpt'])&&!empty ($_POST['valor_tiem_rt'])){
    foreach($_POST['id_rpt'] as $key=>$v)
    $a[]= $v;
    foreach($_POST['valor_tiem_rt'] as $key=>$v)
    $b[]= $v;
    $c= $_POST['id_op_rp'];	
	
	for($i=0; $i<count($a); $i++) {
		  if(!empty($a[$i])&&!empty($b[$i])){ //no salga error con campos vacios
 $insertSQLt = sprintf("INSERT INTO Tbl_reg_tiempo (id_rpt_rt,valor_tiem_rt,op_rt,int_rollo_rt,id_proceso_rt,fecha_rt) VALUES (%s, %s, %s, %s, %s, %s)",                      
                       GetSQLValueString($a[$i], "int"),
                       GetSQLValueString($b[$i], "int"),
					   GetSQLValueString($c, "int"),
					   GetSQLValueString($_POST['rollo_rp'], "int"),
					   GetSQLValueString($_POST['id_proceso_rp'], "int"),
					   GetSQLValueString($_POST['fecha_ini_rp'], "date"));
  mysql_select_db($database_conexion1, $conexion1);
  $Resultt = mysql_query($insertSQLt, $conexion1) or die(mysql_error());
		  }
	}
}
if (!empty ($_POST['id_rtp']) && !empty ($_POST['valor_prep_rtp'])){
	$h = array();
    foreach($_POST['id_rtp'] as $key=>$n)
    $h[]= $n;
    foreach($_POST['valor_prep_rtp'] as $key=>$n)
    $l[]= $n;
    $c= $_POST['id_op_rp'];	
	
	for($x=0; $x<count($h); $x++) {
		  if(!empty($h[$x])&&!empty($l[$x])){ //no salga error con campos vacios
 $insertSQLp = sprintf("INSERT INTO Tbl_reg_tiempo_preparacion (id_rpt_rtp,valor_prep_rtp,op_rtp,int_rollo_rtp,id_proceso_rtp,fecha_rtp) VALUES (%s, %s, %s, %s, %s, %s)",                      
                       GetSQLValueString($h[$x], "int"),
                       GetSQLValueString($l[$x], "int"),
					   GetSQLValueString($c, "int"),
					   GetSQLValueString($_POST['rollo_rp'], "int"),
					   GetSQLValueString($_POST['id_proceso_rp'], "int"),
					   GetSQLValueString($_POST['fecha_ini_rp'], "date"));
  mysql_select_db($database_conexion1, $conexion1);
  $Resultp = mysql_query($insertSQLp, $conexion1) or die(mysql_error());
		  }
	}
}
if (!empty($_POST['id_rpd']) && !empty($_POST['valor_desp_rd'])){
    foreach($_POST['id_rpd'] as $key=>$k)
    $f[]= $k;
    foreach($_POST['valor_desp_rd'] as $key=>$k)
    $g[]= $k;
    $c= $_POST['id_op_rp'];	

	for($s=0; $s<count($f); $s++) {
		  if(!empty($f[$s])&&!empty($g[$s])){ //no salga error con campos vacios
     

    /*$id_proceso_rp = seleccionProceso($f[$s]);
    $id_proceso_rp = $id_proceso_rp=='' ? $_POST['id_proceso_rp'] : $id_proceso_rp;*/
    $id_proceso_rp = $_POST['id_proceso_rp'];
 
 $insertSQLd = sprintf("INSERT INTO Tbl_reg_desperdicio (id_rpd_rd,valor_desp_rd,op_rd,int_rollo_rd,id_proceso_rd,fecha_rd,cod_ref_rd) VALUES (%s, %s, %s, %s, %s, %s, %s)",                      
                       GetSQLValueString($f[$s], "int"),
                       GetSQLValueString($g[$s], "double"),
					   GetSQLValueString($c, "int"),
					   GetSQLValueString($_POST['rollo_rp'], "int"),
					   GetSQLValueString($id_proceso_rp, "int"),
					   GetSQLValueString($_POST['fecha_ini_rp'], "date"),
					   GetSQLValueString($_POST['int_cod_ref_rp'], "text"));
  mysql_select_db($database_conexion1, $conexion1);
  $Resultd = mysql_query($insertSQLd, $conexion1) or die(mysql_error());
		  }
	}
}  				    
  $updateGoTo = "produccion_registro_sellado_total_vista.php?id_op=" . $_POST['id_op_rp'] . "";// "&id_r=" . $_POST['id_r'] .
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
//INSERT

$conexion = new ApptivaDB();//consultas

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);
  //ROLLO SELLADO
$colname_rollo_sellado_edit = "-1";
if (isset($_GET['id_r'])) {
  $colname_rollo_sellado_edit = (get_magic_quotes_gpc()) ? $_GET['id_r'] : addslashes($_GET['id_r']);
}
  
mysql_select_db($database_conexion1, $conexion1);
$query_rollo_sellado_edit =  ("SELECT * FROM tblselladorollo,tbl_reg_produccion WHERE tbl_reg_produccion.id_proceso_rp='4' AND tblselladorollo.id_r='$colname_rollo_sellado_edit' AND tblselladorollo.id_op_r = tbl_reg_produccion.id_op_rp AND  tblselladorollo.rollo_r=tbl_reg_produccion.rollo_rp" );
$rollo_sellado_edit = mysql_query($query_rollo_sellado_edit, $conexion1) or die(mysql_error());
$row_rollo_sellado_edit = mysql_fetch_assoc($rollo_sellado_edit);
$totalRows_rollo_sellado_edit = mysql_num_rows($rollo_sellado_edit);
 
//PARA METROS Y KILOS INICIALES DESDE IMPRESION O EXTRUDER
 $colname_metrosImp = "-1";
if (isset($_GET['id_r'])) {
  $colname_metrosImp = (get_magic_quotes_gpc()) ? $_GET['id_r'] : addslashes($_GET['id_r']);
}
 mysql_select_db($database_conexion1, $conexion1);
 $query_metrosImp = sprintf("SELECT TblImpresionRollo.metro_r AS METROSIMP,TblImpresionRollo.kilos_r AS KILOIMP FROM TblImpresionRollo,TblSelladoRollo WHERE TblSelladoRollo.id_r='%s' AND TblSelladoRollo.id_op_r=TblImpresionRollo.id_op_r AND TblSelladoRollo.rollo_r=TblImpresionRollo.rollo_r",$colname_metrosImp);
$metrosImp = mysql_query($query_metrosImp, $conexion1) or die(mysql_error());
$row_metrosImp = mysql_fetch_assoc($metrosImp);
$totalRows_metrosImp = mysql_num_rows($metrosImp);
//SI NO TIENE IMPRESION LA O.P SE DIRIGE A EXTRUSION
if($totalRows_metrosImp=='0'){
mysql_select_db($database_conexion1, $conexion1);
 $query_metrosImp = sprintf("SELECT TblExtruderRollo.metro_r AS METROSIMP,TblExtruderRollo.kilos_r AS KILOIMP FROM TblExtruderRollo,TblSelladoRollo WHERE TblSelladoRollo.id_r='%s' AND TblSelladoRollo.id_op_r=TblExtruderRollo.id_op_r AND TblSelladoRollo.rollo_r=TblExtruderRollo.rollo_r",$colname_metrosImp);
$metrosImp = mysql_query($query_metrosImp, $conexion1) or die(mysql_error());
$row_metrosImp = mysql_fetch_assoc($metrosImp);
$totalRows_metrosImp = mysql_num_rows($metrosImp);	
}
//VARIABLES FUNCIONALES
$id_op = $row_rollo_sellado_edit['id_op_r'];
$rolloNum = $row_rollo_sellado_edit['rollo_r'];
$fechaI = $row_rollo_sellado_edit['fechaI_r'];
$parcial = $row_rollo_sellado_edit['parcial'];

///DEFINE KILOS INICIALES DEL ROLLO PARCIAL
mysql_select_db($database_conexion1, $conexion1);
$query_inicialparcial = sprintf("SELECT kilopendiente_r FROM tblselladorollo,tbl_reg_produccion WHERE tblselladorollo.id_op_r=$id_op AND tbl_reg_produccion.id_proceso_rp='4' AND tblselladorollo.id_op_r = tbl_reg_produccion.id_op_rp AND tblselladorollo.rollo_r=tbl_reg_produccion.rollo_rp and tblselladorollo.rollo_r=$rolloNum AND tblselladorollo.fechaI_r < '$fechaI' order by tblselladorollo.fechaI_r DESC",$colname_inicialparcial);
$inicialparcial = mysql_query($query_inicialparcial, $conexion1) or die(mysql_error());
$row_inicialparcial = mysql_fetch_assoc($inicialparcial);
$totalRows_inicialparcial = mysql_num_rows($inicialparcial);

 //MAQUINAS
mysql_select_db($database_conexion1, $conexion1);
$query_maquinas = "SELECT * FROM maquina WHERE proceso_maquina='4' ORDER BY id_maquina DESC";
$maquinas= mysql_query($query_maquinas, $conexion1) or die(mysql_error());
$row_maquinas = mysql_fetch_assoc($maquinas);
$totalRows_maquinas = mysql_num_rows($maquinas);
//CODIGO EMPLEADO
/*mysql_select_db($database_conexion1, $conexion1);
$query_codigo_empleado = "SELECT * FROM empleado WHERE tipo_empleado IN(7,9) ORDER BY nombre_empleado ASC";
$codigo_empleado = mysql_query($query_codigo_empleado, $conexion1) or die(mysql_error());
$row_codigo_empleado = mysql_fetch_assoc($codigo_empleado);
$totalRows_codigo_empleado = mysql_num_rows($codigo_empleado);*/

$rew_operarios = $conexion->llenarCampos("TblSelladoRollo", "WHERE id_r='".$_GET['id_r']."' ", "ORDER BY rollo_r ASC ", " cod_empleado_r,cod_auxiliar_r ");
 
$row_codigo_empleado = $conexion->llenaSelect('empleado a INNER JOIN TblProcesoEmpleado b ','ON a.codigo_empleado=b.codigo_empleado WHERE a.tipo_empleado IN(7,9) AND b.estado_empleado=1 ','ORDER BY a.nombre_empleado ASC');
  
$row_revisor = $conexion->llenaSelect('empleado a INNER JOIN TblProcesoEmpleado b ','ON a.codigo_empleado=b.codigo_empleado WHERE a.tipo_empleado IN(7,9) AND b.estado_empleado=1 ','ORDER BY a.nombre_empleado ASC');

 //CARGA LOS TIEMPOS MUERTOS 
 $colname_tiempoMuerto= "-1";
if (isset($id_op)) {
  $colname_tiempoMuerto = (get_magic_quotes_gpc()) ? $id_op : addslashes($id_op);
}
mysql_select_db($database_conexion1, $conexion1);
$query_tiempoMuerto = sprintf("SELECT *, valor_tiem_rt AS muertos FROM Tbl_reg_tiempo WHERE op_rt='%s' AND int_rollo_rt = '$rolloNum' AND fecha_rt='$fechaI' AND id_proceso_rt='4' ORDER BY id_rpt_rt ASC",$colname_tiempoMuerto);
$tiempoMuerto = mysql_query($query_tiempoMuerto, $conexion1) or die(mysql_error());
$row_tiempoMuerto = mysql_fetch_assoc($tiempoMuerto);
$totalRows_tiempoMuerto = mysql_num_rows($tiempoMuerto);
//CARGA LOS TIEMPOS PREPARACION 
mysql_select_db($database_conexion1, $conexion1);
$query_tiempoPreparacion = sprintf("SELECT *, valor_prep_rtp AS preparacion FROM Tbl_reg_tiempo_preparacion WHERE op_rtp='%s' AND int_rollo_rtp = '$rolloNum' AND fecha_rtp='$fechaI' AND id_proceso_rtp='4' ORDER BY id_rpt_rtp ASC",$colname_tiempoMuerto);
$tiempoPreparacion  = mysql_query($query_tiempoPreparacion , $conexion1) or die(mysql_error());
$row_tiempoPreparacion  = mysql_fetch_assoc($tiempoPreparacion );
$totalRows_tiempoPreparacion  = mysql_num_rows($tiempoPreparacion );
//CARGA LOS KILOS DESPERDICIOS
mysql_select_db($database_conexion1, $conexion1);
$query_desperdicio = sprintf("SELECT *, valor_desp_rd AS desperdicio FROM Tbl_reg_desperdicio WHERE op_rd=%s AND int_rollo_rd = '$rolloNum' AND id_proceso_rd='4' AND fecha_rd='$fechaI' ORDER BY id_rpd_rd ASC",$colname_tiempoMuerto);// AND fecha_rd='$fechaI'
$desperdicio = mysql_query($query_desperdicio, $conexion1) or die(mysql_error());
$row_desperdicio = mysql_fetch_assoc($desperdicio);
$totalRows_desperdicio = mysql_num_rows($desperdicio);

//Es para Totalizar el desperdicios
mysql_select_db($database_conexion1, $conexion1);
$query_desperdicio = sprintf("SELECT *, sum(valor_desp_rd) AS desperdicio FROM Tbl_reg_desperdicio WHERE op_rd=%s AND int_rollo_rd = '$rolloNum' AND id_proceso_rd='4' AND fecha_rd='$fechaI' ORDER BY id_rpd_rd ASC",$colname_tiempoMuerto);// AND fecha_rd='$fechaI'
$total_desperdicio = mysql_query($query_desperdicio, $conexion1) or die(mysql_error());
$row_total_desperdicio = mysql_fetch_assoc($total_desperdicio);

//CARGA LOS TIEMPOS KILOS PRODUCIDOS
mysql_select_db($database_conexion1, $conexion1);
$query_producido = sprintf("SELECT *, SUM(`valor_prod_rp`) AS producido FROM  Tbl_reg_kilo_producido WHERE op_rp=%s AND id_proceso_rkp='4' AND int_rollo_rkp = $rolloNum  ORDER BY id_rpp_rp ASC",$colname_tiempoMuerto);//AND id_rpp_rp NOT IN (1406,1407,1655,1656,1657)
$producido = mysql_query($query_producido, $conexion1) or die(mysql_error());
$row_producido = mysql_fetch_assoc($producido);
$totalRows_producido = mysql_num_rows($producido);


//CARGA LOS DINAMICOS
 mysql_select_db($database_conexion1, $conexion1);
$query_tiempo_muertos = "SELECT * FROM Tbl_reg_tipo_desperdicio WHERE Tbl_reg_tipo_desperdicio.id_proceso_rtd='4' AND Tbl_reg_tipo_desperdicio.codigo_rtp='1' AND estado_rtp='0' ORDER BY Tbl_reg_tipo_desperdicio.nombre_rtp ASC";
$tiempo_muertos = mysql_query($query_tiempo_muertos, $conexion1) or die(mysql_error());
$row_tiempo_muertos = mysql_fetch_assoc($tiempo_muertos);
$totalRows_tiempo_muertos = mysql_num_rows($tiempo_muertos);

mysql_select_db($database_conexion1, $conexion1);
$query_tiempo_preparacion = "SELECT * FROM Tbl_reg_tipo_desperdicio WHERE Tbl_reg_tipo_desperdicio.id_proceso_rtd='4' AND Tbl_reg_tipo_desperdicio.codigo_rtp='2' AND estado_rtp='0' ORDER BY Tbl_reg_tipo_desperdicio.nombre_rtp ASC";
$tiempo_preparacion = mysql_query($query_tiempo_preparacion, $conexion1) or die(mysql_error());
$row_tiempo_preparacion = mysql_fetch_assoc($tiempo_preparacion);
$totalRows_tiempo_preparacion = mysql_num_rows($tiempo_preparacion);

mysql_select_db($database_conexion1, $conexion1);
$query_desperdicios = "SELECT * FROM Tbl_reg_tipo_desperdicio WHERE Tbl_reg_tipo_desperdicio.id_proceso_rtd='4' AND Tbl_reg_tipo_desperdicio.codigo_rtp='3' AND estado_rtp='0' ORDER BY Tbl_reg_tipo_desperdicio.nombre_rtp ASC";
$desperdicios = mysql_query($query_desperdicios, $conexion1) or die(mysql_error());
$row_desperdicios = mysql_fetch_assoc($desperdicios);
$totalRows_desperdicios = mysql_num_rows($desperdicios);
 
mysql_select_db($database_conexion1, $conexion1);
$query_referencia = sprintf("SELECT * FROM Tbl_orden_produccion,Tbl_referencia  WHERE Tbl_orden_produccion.id_op='$id_op' AND Tbl_orden_produccion.id_ref_op=Tbl_referencia.id_ref", $colname_referencia);
$referencia = mysql_query($query_referencia, $conexion1) or die(mysql_error());
$row_referencia = mysql_fetch_assoc($referencia);
$totalRows_referencia = mysql_num_rows($referencia);
//ACTUALIZA LOS KILOS EN LIQUIDACION
if($row_desperdicio['desperdicio']=='')//debe ser vacio sino se repite el valor en el rollo q exista el desperdicio
{
mysql_select_db($database_conexion1, $conexion1);
$query_despacumula = sprintf("SELECT *, valor_desp_rd AS despacumula FROM Tbl_reg_desperdicio WHERE op_rd=%s AND int_rollo_rd = '$rolloNum' AND id_proceso_rd='4' ORDER BY id_rpd_rd ASC",$colname_tiempoMuerto); 
$despacumula = mysql_query($query_despacumula, $conexion1) or die(mysql_error());
$row_despacumula = mysql_fetch_assoc($despacumula);
$totalRows_despacumula = mysql_num_rows($despacumula);


}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
<link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">
<link rel="stylesheet" type="text/css" href="css/general.css"/>

<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
<script type="text/javascript" src="js/consulta.js"></script> 
<script type="text/javascript" src="js/ajax_sellado.js"> </script>
<script type="text/javascript" src="AjaxControllers/js/numeracionInicial.js"></script> 

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

<script language="javascript" type="text/javascript">
var upload_number=1;
	function tiemposM() {
	var i=0;
 	var d = document.createElement("div");
	var file0 = document.createElement("select");
 	file0.setAttribute("name", "id_rpt[]");
	file0.setAttribute("onChange", "restakilosT()" );
	file0.options[i] = new Option('T.Muertos','');
	i++;
	<?php do { ?>
    file0.options[i] = new Option('<?php echo $row_tiempo_muertos['nombre_rtp']?>','<?php echo $row_tiempo_muertos['id_rtp']?>');
	i++;
    <?php
        } while ($row_tiempo_muertos = mysql_fetch_assoc($tiempo_muertos));
         $rows = mysql_num_rows($tiempo_muertos);
             if($rows > 0) {
                 mysql_data_seek($tiempo_muertos, 0);
               $row_tiempo_muertos = mysql_fetch_assoc($tiempo_muertos);
        }?> 		
	file0.setAttribute("style", "width:150px" );
	d.appendChild(file0);
 	var file = document.createElement("input");
 	file.setAttribute("type", "number");
 	file.setAttribute("name", "valor_tiem_rt[]");
	file.setAttribute("min", "0" );
	file.setAttribute("placeholder", "Tiempo minutos" );
	file.setAttribute("style", "width:60px" );
	file.setAttribute("onChange", "restakilosT(); " ); 
	d.appendChild(file); 
	
	
 	document.getElementById("moreUploads").appendChild(d);
 	upload_number++;
}
</script>
<script language="javascript" type="text/javascript">
var upload_number=1;
	function tiemposP() {
	var i=0;
 	var e = document.createElement("div");
	var file0 = document.createElement("select");
 	file0.setAttribute("name", "id_rtp[]");
	file0.setAttribute("onChange", "restakilosT()" );
	file0.options[i] = new Option('T.Preparacion','');
	i++;
	<?php do { ?>
    file0.options[i] = new Option('<?php echo $row_tiempo_preparacion['nombre_rtp']?>','<?php echo $row_tiempo_preparacion['id_rtp']?>');
	i++;
    <?php
        } while ($row_tiempo_preparacion = mysql_fetch_assoc($tiempo_preparacion));
         $rows = mysql_num_rows($tiempo_preparacion);
             if($rows > 0) {
                 mysql_data_seek($tiempo_preparacion, 0);
               $row_tiempo_preparacion = mysql_fetch_assoc($tiempo_preparacion);
        }?> 
	file0.setAttribute("style", "width:150px" );
	e.appendChild(file0);
 	var file = document.createElement("input");
 	file.setAttribute("type", "number");
 	file.setAttribute("name", "valor_prep_rtp[]");
	file.setAttribute("min", "0" );
	file.setAttribute("placeholder", "Tiempo minutos" );
	file.setAttribute("style", "width:60px" );
	file.setAttribute("onChange", "restakilosT()" );
 	
	e.appendChild(file); 
	
 	document.getElementById("moreUploads2").appendChild(e);
 	upload_number++;
}
</script>
<script language="javascript" type="text/javascript">
var upload_number=1;
	function tiemposD() {
	var i=0;
 	var f = document.createElement("div");
	var file0 = document.createElement("select");
 	file0.setAttribute("name", "id_rpd[]");
	/*file0.setAttribute("onChange", "restakilosD();kiloComparativoSell()" );*/
 
	file0.options[i] = new Option('Desperdicio','');
	i++;
	<?php do { ?>
    file0.options[i] = new Option('<?php echo $row_desperdicios['nombre_rtp']?>','<?php echo $row_desperdicios['id_rtp']?>');
	i++;
    <?php
        } while ($row_desperdicios = mysql_fetch_assoc($desperdicios));
         $rows = mysql_num_rows($desperdicios);
             if($rows > 0) {
                 mysql_data_seek($desperdicios, 0);
               $row_desperdicios = mysql_fetch_assoc($desperdicios);
        }?>
	file0.setAttribute("style", "width:150px" );
	f.appendChild(file0);
 	var file = document.createElement("input");
 	file.setAttribute("type", "number");
 	file.setAttribute("name", "valor_desp_rd[]" );
	file.setAttribute("min", "0" );
	file.setAttribute("step", "0.01" );
	file.setAttribute("placeholder", "Kilos" );
	file.setAttribute("style", "width:60px" );
	file.setAttribute("onChange", "restakilosD();kiloComparativoSell()" );
 	
	f.appendChild(file); 
	
 	document.getElementById("moreUploads3").appendChild(f);
 	upload_number++;
}
</script>
 <script>
function parcial() {
 swal({
  title: 'Seguir Rollo Parcial o  Liquidar!',
  text: "Que desea Hacer Con el Rollo:", 
  type: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  cancelButtonText: 'Rollo Parcial !',
  confirmButtonText: 'Rollo Total !',
   confirmButtonClass: 'btn btn-success',
  cancelButtonClass: 'btn btn-danger',
  buttonsStyling: false,
  closeOnConfirm: false,
  closeOnCancel: false
 
},
function(isConfirm) {
	var txt;
  if (isConfirm === true) {
    swal(
      'Terminar Rollo!',
      'ok para Totalizar Rollo.',
      'success'
    );
	txt = 2;
   } else if (isConfirm === false) {
    swal(
      'Seguir Rollo Parcial!',
      'ok para Seguir parcial!)',
      'error'
    );
	txt = 1;
   } //else {
    // outside click, isConfirm is undefinded
  //}
  document.getElementById("rolloParcial_r").value = txt;
	 submitform();
})
}

 function submitform(){
 	var bolsa_rp=(document.form1.bolsa_rp.value);
 	var total_horas_rp=(document.form1.total_horas_rp.value);
 	var tiempoOptimo_rp=(document.form1.tiempoOptimo_rp.value);
 	var int_kilosxhora_rp=(document.form1.int_kilosxhora_rp.value);
 	var indice7 = document.getElementById("n_ini_rp").value;
	var indice8 = document.getElementById("n_fin_rp").value;

    if( bolsa_rp == '') { 
        swal('Debe llenar el Campo: Bolsas x Rollo! ');
        return false;
      }else if( total_horas_rp == '' ) { 
        swal('Debe llenar el Campo: Total Horas Trabajadas ! ');
        return false;
      }else if( tiempoOptimo_rp == '') { 
        swal('Debe llenar el Campo: Tiempo Optimo! ');
        return false;
      }else if(int_kilosxhora_rp =='') { 
        swal('Debe llenar el Campo: Kilos*Hora! ');
        return false; 
      }else if((indice7  != 0 && indice8 == '') || (indice7  == '' && indice8 == '')) {  
		
		swal('[ERROR] Ingrese las numeraciones');
		 return false;  
	  }else{
        document.form1.submit(); 
         return true;
      }
  
}
</script>
 </head>
<body onunload="restakilosT()"><!--onunload="restakilosT();" -->
<?php echo $conexion->header('vistas'); ?>
<table id="tabla1">
	<tr>
    <td colspan="2" align="center">
  <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="form1" onSubmit="return(kiloComparativoSell());">
 <table id="tabla2">
  <tr id="tr1">
    <td colspan="4" id="titulo2">REGISTRO DEL ROLLO EN SELLADO
    <?php 
			$id_op=$row_rollo_sellado_edit['id_op_r'];
			  
            $totalesRollos = $conexion->llenarCampos("tblimpresionrollo", "WHERE id_op_r='".$id_op."' ", " ", " COUNT(DISTINCT rollo_r) AS max_rolloI,SUM(metro_r) as metro_r, SUM(kilos_r) as kilos_r ");

            if($totalesRollos['max_rolloI']==0){
             $totalesRollos = $conexion->llenarCampos("tblextruderrollo", "WHERE id_op_r='".$id_op."' ", " ", " COUNT(DISTINCT rollo_r) AS max_rolloI,SUM(metro_r) as metro_r, SUM(kilos_r) as kilos_r ");
            } 
            $max_rolloI = $totalesRollos['max_rolloI'];
            $metrosI = $totalesRollos['metro_r'];
			?>
      <input type="hidden" name="id_r" id="idrollo" value="<?php echo $row_rollo_sellado_edit['id_r']; ?>" /></td>
  </tr>
  <tr>
    <td rowspan="6" id="dato2"><img src="images/logoacyc.jpg"/></td>
    <td id="dato2"><?php echo $row_rollo_sellado_edit['rollo_r'];if ($max_rolloI!='') {echo " de ".$max_rolloI;} ?></td>
    <td id="dato3"> <!--id_rliqs--><a href="javascript:eliminar1('id_rolloparcial',<?php echo $_GET['id_r'];?> ,'produccion_registro_sellado_listado_add.php')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR ROLLO LIQUIDADO"
title="ELIMINAR ROLLO LIQUIDADO" border="0" /></a><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a><a href="produccion_registro_sellado_total_vista.php?id_op=<?php echo $row_rollo_sellado_edit['id_op_r']; ?>"><img src="images/hoja.gif" alt="VISTA SELLADA" title="VISTA SELLADA" border="0" /></a><a href="produccion_sellado_listado_rollos.php?id_op_r=<?php echo $row_rollo_sellado_edit['id_op_r']; ?>"><img src="images/opciones.gif" alt="LISTADO SELLADAS"title="LISTADO SELLADAS" border="0" style="cursor:hand;" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" border="0"/></a></td>
    <td id="dato3">&nbsp;</td>
  </tr>
  <tr id="tr1">
    <td colspan="2"  nowrap="nowrap" id="dato2">ORDEN DE PRODUCCION</td>
    <td id="dato3"> Ingresado por
      <input name="str_responsable_rp" type="text" id="str_responsable_rp" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="15" readonly="readonly"/></td>
  </tr>
  <tr id="tr3">
    <td colspan="2" nowrap="nowrap" id="numero2"><?php echo $row_rollo_sellado_edit['id_op_r'];?></td>
    <td nowrap="nowrap" id="fuente2"><input name="id_rp" type="hidden" id="id_rp" value="<?php echo $row_rollo_sellado_edit['id_rp'];?>" /></td> 
  </tr>
  <tr id="tr1">
    <td colspan="2" nowrap="nowrap" id="dato2">REFERENCIA</td>
    <td id="dato2">VERSION</td>
  </tr>
  <tr>
    <td colspan="2" nowrap="nowrap" id="numero2"><?php echo $row_rollo_sellado_edit['int_cod_ref_rp'];?></td>
    <td nowrap="nowrap" id="numero2"><?php echo $row_rollo_sellado_edit['version_ref_rp'];?></td>
  </tr>
  <tr>
   <td colspan="2" id="dato2"><input type="hidden" name="ancho" id="ancho" style="width:80px" value="<?php echo $row_referencia['ancho_ref'];?>"/>
      <input type="hidden" name="calibre" id="calibre"  style="width:80px" value="<?php echo $row_referencia['calibre_ref'];?>" /></td>
    <td id="dato2">&nbsp;</td>
  </tr> 
  <tr id="tr1">
    <td colspan="6" id="titulo4">DETALLE CONSUMO</td>
  </tr>
  <tr>
    <td colspan="6" id="fuente2">&nbsp; 
      </td>
  </tr>
 <tr>
 	<style type="text/css">

    </style>
   <td id="fuente1" nowrap="nowrap" > 
   	<div class="tooltip">Peso Inicial  <span class="notastexto" > ver... </span>
   	  <span class="tooltiptext">Es la suma de: <br>PesoInicial + kilopend + Desperd + Reproceso</span>
   	</div>
     <p>
     	<?php  
     	if( $parcial==1 && $row_inicialparcial['kilopendiente_r']=='') {
            //$row_rollo_sellado_edit['kilos_r']+$row_rollo_sellado_edit['kilopendiente_r']+$row_total_desperdicio['desperdicio']+$row_rollo_sellado_edit['reproceso_r']
            //$row_rollo_sellado_edit['kilopendiente_r']+$row_rollo_sellado_edit['reproceso_r']; 
     	    $valorInicial = $row_metrosImp['KILOIMP']; //Si $row_inicialparcial['kilopendiente_r'] es vacioes porq es el primer parcial y se debe traer el valor de Impresion
     		 
     	}else{
     	    $valorInicial = $row_inicialparcial['kilopendiente_r'];
     	}
     	  
     	?>
      <input name="kiloInicial" type="text" id="kiloInicial" min="1.00" step="0.01" style="width:60px" value="<?php echo $valorInicial;?>" required="required"/>

      <input type="hidden" name="placa_rp" id="placa_rp" style="width:80px" required="required" readonly="readonly" value="<?php echo $row_rollo_sellado_edit['id_op_r']."-".$row_rollo_sellado_edit['rollo_r'];?>"/>
      
      <input name="int_kilos_prod_rp" type="hidden" id="int_kilos_prod_rp" min="1.00" step="0.01" style="width:60px" value="<?php echo $row_metrosImp['KILOIMP'];?>" required="required" readonly="readonly"/> <!-- kilos de impresion -->

     </p>
     	 
   </td> 
   <td id="fuente1">
   	<div class="tooltip">Consumo Kg <span class="notastexto"> ver... </span>
   	  <span class="tooltiptext">Este valor es: Es el equivalente en kilos segun la cantidad de Bolsas </span>
   	</div>
     <p>
       <input type="number" name="int_total_kilos_rp" id="int_total_kilos_rp" min="0.10" step="any" style="width:60px" required="required" value="<?php echo $row_rollo_sellado_edit['kilos_r'];?>" readonly="readonly"/>
     </p></td> 
   <td id="fuente1"><p>Bolsas x Rollo
     </p>
     <p>
       <input type="number" name="bolsa_rp" min="1" id="bolsa_rp" style="width:80px" required="required" onKeydown="restakilosT();" onChange="kiloComparativoSell();restakilosT();kiloDisponible();" value="<?php echo $row_rollo_sellado_edit['bolsas_r'];?>"/>
     </p></td>
   <td id="fuente1"><p>Reproceso</p>
     <p>
       <input name="reproceso" id="reproceso" type="number" min="0.00" step="any" style="width:80px"  required="required" value="<?php echo $row_rollo_sellado_edit['reproceso_r']; ?>"  onchange="kiloComparativoSell();"/>
     </p></td>
 </tr>
 <tr>
   <td id="fuente1"><p>Metro Inicial
     </p>
     <p>
       <input name="metro_r" type="hidden" id="metro_r" min="1" style="width:60px" value="<?php echo $row_metrosImp['METROSIMP'];?>" readonly="readonly"/>
       <input name="metroInicial" type="number" id="metroInicial" min="1" style="width:60px" value="<?php echo $row_rollo_sellado_edit['metro_r']+$row_rollo_sellado_edit['metroIni_r'];?>" readonly="readonly"/>
     </p></td>
    <td id="fuente1"><p>Metro Final</p>
      <p>
        <input name="metro_r2" type="number" id="metro_r2" min="1" style="width:60px" value="<?php echo $row_rollo_sellado_edit['metro_r'];?>" required="required" readonly="readonly"/>
      </p></td>
    <td id="fuente1">&nbsp;</td>
   <td id="fuente1"><input type="number" name="turno_rp" id="turno_rp" min="1" max="7" step="1" required="required" style="width:80px" value="<?php echo $row_rollo_sellado_edit['turno_r'];?>"/>
     Turno</td>
 </tr>
  <tr>
      <td id="fuente2">&nbsp;</td>
      <td colspan="2" id="fuente1">&nbsp;</td>
      <td id="fuente2">&nbsp;</td>
      </tr>
  <tr id="tr1">
    <td id="fuente1">Maquina<strong>
   <!--   <input name="standby" id="standby" type="hidden" value="<?php echo $row_rollo_sellado_edit['standby'];?>" size="5"/>
      <input name="valor_tiem_rt" id="valor_tiem_rt" type="hidden" size="5" value="0"/>
      <input name="valor_prep_rt" id="valor_prep_rt" type="hidden" size="5" value="0"/>-->
    </strong></td>
    <td id="fuente1">
    	<select name="str_maquina_rp" id="maquina" style="width:155px" >
    <option value=""<?php if (!(strcmp("", $row_rollo_sellado_edit['maquina_r']))) {echo "selected=\"selected\"";} ?>>Maquina</option>
      <?php
do {  
?>
      <option value="<?php echo $row_maquinas['id_maquina']?>"<?php if (!(strcmp($row_maquinas['id_maquina'], $row_rollo_sellado_edit['maquina_r']))) {echo "selected=\"selected\"";} ?>><?php echo $row_maquinas['nombre_maquina']?></option>
      <?php
} while ($row_maquinas = mysql_fetch_assoc($maquinas));
  $rows = mysql_num_rows($maquinas);
  if($rows > 0) {
      mysql_data_seek($maquinas, 0);
	  $row_maquinas = mysql_fetch_assoc($maquinas);
  }
?>
    </select></td>
    <td id="fuente1"> 
    	     <select class="form-control" name="int_cod_empleado_rp" id="operario" style="width:145px">
    	       <option value=""<?php if (!(strcmp("", $row_rollo_sellado_edit['cod_empleado_r']))) {echo "selected=\"selected\"";} ?>>Operario</option>
    	       <?php  foreach($row_codigo_empleado as $row_codigo_empleado ) { ?>
    	         <option value="<?php echo $row_codigo_empleado['codigo_empleado']?>"<?php if (!(strcmp($row_codigo_empleado['codigo_empleado'], $row_rollo_sellado_edit['cod_empleado_r']))) {echo "selected=\"selected\"";} ?>><?php echo $row_codigo_empleado['codigo_empleado']." - ".$row_codigo_empleado['nombre_empleado']." ".$row_codigo_empleado['apellido_empleado'];?></option>
    	       <?php } ?>
    	     </select>
    	</td>
    	   <td id="fuente1">

    	     <select class="form-control" name="int_cod_liquida_rp" id="auxiliar" style="width:145px">
    	       <option value=""<?php if (!(strcmp("", $row_rollo_sellado_edit['cod_auxiliar_r']))) {echo "selected=\"selected\"";} ?>>Revisor</option>
    	       <?php  foreach($row_revisor as $row_revisor ) { ?>
    	         <option value="<?php echo $row_revisor['codigo_empleado']?>"<?php if (!(strcmp($row_revisor['codigo_empleado'], $row_rollo_sellado_edit['cod_auxiliar_r']))) {echo "selected=\"selected\"";} ?>><?php echo $row_revisor['codigo_empleado']." - ".$row_revisor['nombre_empleado']." ".$row_revisor['apellido_empleado'];?></option>
    	       <?php } ?>
    	     </select>
        </td>
    </tr>
  <tr>
    <td colspan="2" id="dato1"></td> 
    <td colspan="3" id="dato1"></td>
    <td colspan="2" id="dato1"></td>
  </tr>
  <tr>
    <td id="fuente1">Fecha Inicial</td>
    <td colspan="2" id="fuente1">
    	<input name="fecha_ini_rp" id="fecha_ini_rp" onkeydown="restakilosT();" onchange="restakilosT();alerta_sell();" type="datetime-local" min="2000-01-02" value="<?php echo muestradatelocal($row_rollo_sellado_edit['fechaI_r']);?>" size="15" required onclick="restakilosT();" /><div id="resultado_generador"></div>
    </td>
    <td id="fuente1">
    	<input type="number" name="rollo_rp" id="rollo_rp" min="0"step="any" required="required" readonly="readonly" placeholder="Rollos" style="width:46px" value="<?php echo $row_rollo_sellado_edit['rollo_r'];?>"/>
de
   
  <input name="int_total_rollos_rp" type="number" id="int_total_rollos_rp" placeholder="Rollos" style="width:46px" min="0"step="any" value="<?php echo $row_rollo_sellado_edit['int_total_rollos_rp'];?>" readonly="readonly"/>Total Rollos
</td>
  </tr>
  <tr id="tr1">
    <td id="fuente1">Fecha Final</td>
    <td colspan="2" id="fuente1"><input name="fecha_fin_rp" id="fecha_fin_rp" type="datetime-local" min="2000-01-02" size="15" required onKeydown="restakilosT();" onChange="restakilosT()" value="<?php echo muestradatelocal($row_rollo_sellado_edit['fechaF_r']);//muestradatelocal?>"/></td>
    <td id="fuente1"><p>Metro Restante</p>
      <p>
        <input name="metroIni_r" id="metroIni_r" type="number" required="required" style="width:60px" step="any" value="<?php echo $row_rollo_sellado_edit['metroIni_r'];?>" readonly="readonly"/>
      </p>
      </td>
  </tr>
 <tr>
    <td id="fuente1">Total Horas Trabajadas</td>
    <td colspan="3" id="fuente1">
      <input name="total_horas_rp" id="total_horas_rp" type="text" required="required" readonly="readonly" placeholder="total horas" value="" size="15" onclick="restakilosT();" /><!--kilosxHora2()-->
      Tiempo Optimo
      <input name="rodamiento_rp" id="tiempoOptimo_rp" type="text" size="5" onclick="restakilosT();" required="required" placeholder="rodamientos"  value="" readonly="readonly"/><!--kilosxHora2()--></td>
   </tr> 
  <tr id="tr1">
    <td id="fuente1">Numeracion Inicial</td>
    <td colspan="2" id="fuente1">
    	<input type="hidden" name="numInicioControl" id="numInicioControl" value="<?php $numIni = $row_rollo_sellado_edit['numIni_r']=='' ? $row_rollo_sellado_edit['n_fin_rp'] : $row_rollo_sellado_edit['numIni_r']; echo $numIni;?>"/>
    	<input type="text" name="n_ini_rp" id="n_ini_rp" size="15" required="required" onBlur="conMayusculas(this);" value="<?php echo $row_rollo_sellado_edit['numIni_r'];?>" />
      <input id="porcentaje" name="porcentaje" type="hidden" value="<?php echo $row_rollo_sellado_edit['porcentaje_op_rp']; ?>" min="0" max="100" step="1" style="width:40px" required="required" readonly="readonly"/></td>
    <td id="fuente1"> Numeracion Final <input type="text" name="n_fin_rp" id="n_fin_rp" style="width:115px" required="required" onblur="conMayusculas(this); " value="<?php echo $row_rollo_sellado_edit['numFin_r'];?>"/></td>
  </tr>
  <tr>
    <td id="fuente1">Desperdicio Operario</td>
    <td colspan="2" id="fuente1"> 
      <input name="acumulado" id="acumulado" type="hidden" value="<?php if($row_despacumula['despacumula']==''){echo $acuml="0";}else{echo $acuml=$row_despacumula['despacumula'];} ?>" />

      <input type="text" name="int_kilos_desp_rp" id="int_kilos_desp_rp" min="0" step="any" required="required" size="7" placeholder="Desp.oper" value="<?php if( $row_total_desperdicio['desperdicio']==''){echo "0";}else{echo $row_total_desperdicio['desperdicio'];}?>" readonly="readonly" />

      <?php //if ($row_usuario['tipo_usuario']<>'5') {?> 
      <input type="number" name="kiloSistema" id="kiloSistema" style="width:55px" required="required" min="0.00" step="any" placeholder="Sistema" value="<?php echo $row_rollo_sellado_edit['kilopendiente_r'];?>" readonly="readonly"/>
      <span class="tooltip"> <span class="notastexto" > ver</span>
   	     <span class="tooltiptext">Este valor es: Peso Inicial - peso Final - Desperdicio Operario </span>
   	  </span>
      <?php //} ?>
    </td>
    <td id="fuente1">Kilos*Hora 
    	<input name="int_kilosxhora_rp" type="number" required="required" id="int_kilosxhora_rp" min="0.10" step="any" value="" style="width:115px" onblur="restakilosT();"/>
      <div class="tooltip"> <span class="notastexto" > ver</span>
   	     <span class="tooltiptext">Este valor es: Peso Final / Tiempo Optimo </span>
   	  </div>
  </td>
  </tr>
  <tr>
    <td id="fuente1">&nbsp; </td>
    <td id="fuente1"><input type="hidden" name="rolloParcial_r" id="rolloParcial_r" value="<?php echo $row_rollo_sellado_edit['rolloParcial_r']; ?>" class="largerCheckbox"/></td>
    <td id="fuente1">&nbsp;      </td>
    <td id="dato4">&nbsp;</td>
  </tr>
    <tr>
    <td id="dato4">&nbsp;</td>
    <td id="dato4">&nbsp;</td>
    <td id="dato4">&nbsp;</td>
    <td id="dato3"><!-- <input type="button" name="ENVIAR" id="ENVIAR" value="EDITAR PARCIAL" onclick="parcial();" /> -->
    	<input type="button" class="botonGeneral" name="ENVIAR" value="EDITAR PARCIAL" onclick="parcial();validaTodoSell()" /></td>
  </tr>
 <tr>
 <td colspan="7">
 <table style="width:100%; " >       
  <tr id="tr1">
            <td id="dato1" colspan="2">Desperdicios</td>
            <td id="dato1" colspan="2">Tiempos Muertos</td>
            <td id="dato1" colspan="2">Tiempos Preparacion</td>
    </tr>
   <tr>
           
          <td id="dato1"colspan="2"><input type="button" class="botonFinalizar" name="button3" id="button3" value="Crear otra fila" onClick="desperdicio()" style="width:125px"/></td>
          <td id="dato1" colspan="2"><input type="button" class="botonFinalizar" name="button" id="button" value="Crear otra fila" onClick="tiempoM();" style="width:125px"/></td>
          <td  id="dato1" colspan="2"><input type="button" class="botonFinalizar" name="button2" id="button2" value="Crear otra fila" onClick="tiempoP()" style="width:125px"/></td> 
  </tr>
  <tr>
      
            <td id="dato1"colspan="2"><div id="moreUploads3"></div></td>
            <td id="dato1"colspan="2"><div id="moreUploads" ></div></td>
            <td id="dato1"colspan="2"><div id="moreUploads2"></div></td>
            
    </tr> 
    <tr>
     <td colspan="2" id="dato1"></td>
    <td colspan="2" id="dato1"></td> 
    <td colspan="2" id="dato1"></td> 
  </tr>
    </table>
    </td> 
</tr>

  <tr id="tr1">
    <td colspan="4" id="titulo4">CONSUMOS</td>
  </tr>
  <!--<tr>
    <td colspan="13" id="fuente2"><a href="javascript:verFoto('produccion_regist_sellado_kilos_prod.php?id_op=<?php echo $row_rollo_sellado_edit['id_op_r'] ?>&rollo=<?php echo $row_rollo_sellado_edit['rollo_rp']?>&fecha=<?php echo $row_rollo_sellado_edit['fecha_ini_rp']?>','820','470')">
      <input type="button" name="Tiempos Desperdicio" id="check_sh1" value="Detalle consumo"/>
      </a><a href="javascript:verFoto('produccion_registro_sellado_detalle_add.php?id_op=<?php echo $row_rollo_sellado_edit['id_op_r'] ?>&rollo=<?php echo $row_rollo_sellado_edit['rollo_rp']?>&fecha=<?php echo $row_rollo_sellado_edit['fecha_ini_rp']?>','820','270')">
        <input type="button" name="Tiempos Desperdicio" id="check_sh1" value="Tiempos Desperdicio"/>
        </a>
      <input type="button" name="check_sh" id="check_sh2" value="Mostrar" onclick="mostrardiv1()"/>
      <input type="button" value="Ocultar" onclick="ocultardiv1()" /></td>
  </tr>-->
      <tr>
        <td colspan="4" id="fuente2">&nbsp;</td>
        </tr>
          <?php if($row_standBy['id_rpt_rt']!='') {?>
          <tr>
          
            <td nowrap id="detalle2" colspan="2"><strong>Fin de Semana - Tipo</strong></td>
            <td nowrap id="detalle2"><strong>Fin de Semana - Minutos</strong></td>
            <td nowrap id="detalle2"><strong>ELIMINA </strong></td>
          </tr>
          <?php  for($s=0;$s<=$totalRows_standBy-1;$s++) { ?>
          <tr>
          <td id="fuente1">&nbsp;</td>
            <td id="fuente1">
          <?php  
    	  $id_stand=mysql_result($standBy,$s,id_rpt_rt); 
    	  $sqlstand="SELECT * FROM Tbl_reg_tipo_desperdicio WHERE id_rtp='$id_stand'";
    	  $resultstand= mysql_query($sqlstand);
    	  $numstand = mysql_num_rows($resultstand);
    	  if($numstand >='1')
    	  {echo $Nombrestandby = mysql_result($resultstand, 0, 'nombre_rtp'); }?></td>
            <td id="fuente1"><?php $varST=mysql_result($standBy,$s,standby);echo $varST; $totalST+=$varST; ?></td>
            <td id="fuente1"><!--ELIMINAR ESTA FUNCION eliminar_rts-->
            <a href="javascript:eliminar_rts('id_rtsp',<?php $delrt=mysql_result($standBy,$k,id_rt); echo $delrt;?>,'id_rsp',<?php echo $row_rollo_sellado_edit['id_r']; ?>,'produccion_registro_sellado_edit.php')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR " title="ELIMINAR" border="0" /></a>
        </td>
      </tr>
      <?php } ?> 
      <tr>
 
      	<td id="fuente1"colspan="2">TOTAL</td>
      	<td id="fuente1">
      		<strong>
      			<?php if($totalST!=''){echo $totalST;}else{echo "0";}  ?>
      			= <?php echo redondear_decimal($totalST/60); ?> Horas</strong></td>
      			<td id="fuente1">&nbsp;</td>
      		</tr>   
      	<?php } ?>  



      	<?php if($row_tiempoMuerto['id_rpt_rt']!='') {?>
      		<tr>
      			 
      			<td nowrap id="detalle2"colspan="2"><strong>Tiempos Muertos - Tipo</strong></td>
      			<td nowrap id="detalle2"><strong>Tiempos Muertos - Minutos</strong></td>
      			<td nowrap id="detalle2"><strong>ELIMINA</strong></td>
      		</tr>
      		<?php  for ($k=0;$k<=$totalRows_tiempoMuerto-1;$k++) { ?>
      	<tr>
      		 
      		<td id="fuente1"colspan="2">
      			<?php $id1=mysql_result($tiempoMuerto,$k,id_rpt_rt); 
      			$id_tm=$id1;
      			$sqltm="SELECT * FROM Tbl_reg_tipo_desperdicio WHERE id_rtp='$id_tm'";
      			$resulttm= mysql_query($sqltm);
      			$numtm= mysql_num_rows($resulttm);
      			if($numtm >='1')
      			{ 
      				$nombre1 = mysql_result($resulttm, 0, 'nombre_rtp');echo $nombre1; }?>
      				<strong>
      					<!--<input name="id_rpt[]" type="hidden" id="id_rpt[]" value="<?php echo $id1; ?>" size="6"/>-->
      				</strong></td>
      				<td id="fuente1"><?php $var1=mysql_result($tiempoMuerto,$k,muertos);echo $var1; $totalTM+=$var1; ?>
      				<!--<input name="standby[]" id="standby[]" type="hidden" value="<?php  echo $nombre1; ?> "/>--></td>
      				<td id="fuente1"><a href="javascript:eliminar_varias('id_rtsp',<?php $delrt=mysql_result($tiempoMuerto,$k,id_rt); echo $delrt; ?>,'id_rsp',<?php echo $row_rollo_sellado_edit['id_r']; ?>,'produccion_registro_sellado_edit.php')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR " title="ELIMINAR" border="0" /></a></td>
      	 </tr>
     <?php } ?>
      <tr>
  
      	<td id="fuente1"colspan="2">TOTAL</td>
      	<td id="fuente1">
      		<strong>
      			<?php if($totalTM!=''){echo $totalTM;}else{echo "0";}  ?>
      			<input name="valor_tiem_rt[]" type="hidden" id="valor_tiem_rt" value="<?php echo $totalTM; ?>" size="6" />
      		</strong></td>
      		<td id="fuente1">&nbsp;</td>
      	</tr>
      <?php } ?>




      <?php if($row_tiempoPreparacion['id_rpt_rtp']!='') {?>
      	<tr>
      	 
      		<td nowrap id="detalle2"colspan="2"><strong>Tiempos Preparacion - Tipo</strong></td>
      		<td nowrap id="detalle2"><strong>Tiempos Preparacion - Minutos</strong></td>
      		<td nowrap id="detalle2"><strong>ELIMINA</strong></td>
      	</tr>
      	<?php  for ($x=0;$x<=$totalRows_tiempoPreparacion-1;$x++) { ?>
      		<tr>
      			 
      			<td id="fuente1"colspan="2"><?php $id2=mysql_result($tiempoPreparacion,$x,id_rpt_rtp); 
      			$id_rtp=$id2;
      			$sqlrtp="SELECT * FROM Tbl_reg_tipo_desperdicio WHERE id_rtp='$id_rtp'";
      			$resultrtp= mysql_query($sqlrtp);
      			$numrtp= mysql_num_rows($resultrtp);
      			if($numrtp >='1')
      			{ 
      				$nombre2 = mysql_result($resultrtp, 0, 'nombre_rtp');echo $nombre2; }?>
      				<!--<input name="id_rtp[]" type="hidden" id="id_rtp[]" value="<?php echo $id2; ?>" size="6"/>--></td>
      				<td id="fuente1"><?php $var2=mysql_result($tiempoPreparacion,$x,preparacion); echo $var2;$totalTP+=$var2; ?></td>
      				<td id="fuente1"><a href="javascript:eliminar_varias('id_rpsp',<?php $delrp=mysql_result($tiempoPreparacion,$x,id_rt); echo $delrp; ?>,'id_rsp',<?php echo $row_rollo_sellado_edit['id_r']; ?>,'produccion_registro_sellado_edit.php')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR " title="ELIMINAR" border="0" /></a>
      				</td>
      			</tr>
        <?php } ?>
      <tr>
      
      	<td id="fuente1"colspan="2">TOTAL</td>
      	<td id="fuente1">
      		<strong> 
      			<?php echo $totalTP != '' ? $totalTP : "0"; ?>
      			<input name="valor_prep_rtp[]" type="hidden" id="valor_prep_rtp" value="<?php echo $totalTP; ?>" size="6" />
      		</strong></td>
      		<td id="fuente1">&nbsp;</td>
      	</tr>
      <?php } ?>




      <?php if($row_desperdicio['id_rpd_rd']!='') {?>
      	<tr>
      	 
      		<td nowrap id="detalle2"colspan="2"><strong>Desperdicios - Tipo</strong></td>
      		<td nowrap id="detalle2"><strong>Desperdicios - Kilos</strong></td>
      		<td nowrap id="detalle2"><strong>ELIMINA</strong></td>
      	</tr>
      	<?php  for ($i=0;$i<=$totalRows_desperdicio-1;$i++) { ?>
      		<tr>
      			 
      			<td id="fuente1"colspan="2"><?php $id3=mysql_result($desperdicio,$i,id_rpd_rd); 
      			$id_rpd=$id3;
      			$sqlrtd="SELECT * FROM Tbl_reg_tipo_desperdicio WHERE id_rtp='$id_rpd'";
      			$resultrtd= mysql_query($sqlrtd);
      			$numrtd= mysql_num_rows($resultrtd);
      			if($numrtd >='1')
      			{ 
      				$nombre3 = mysql_result($resultrtd, 0, 'nombre_rtp'); echo $nombre3; }?><!--<input name="id_rpd[]" type="hidden" id="id_rpd[]" value="<?php echo $id3; ?>" size="6"/>--></td>
      				<td id="fuente1"><?php $var3=mysql_result($desperdicio,$i,desperdicio); echo $var3; $totalTD+=$var3;?></td>
      				<td id="fuente1"><a href="javascript:eliminar_varias('id_rdsp',<?php $delrd=mysql_result($desperdicio,$i,id_rd); echo $delrd; ?>,'id_rsp',<?php echo $row_rollo_sellado_edit['id_r']; ?>,'produccion_registro_sellado_edit.php')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR " title="ELIMINAR" border="0" /></a></td>
      			</tr>
        <?php } ?>
      <tr>
   
      	<td id="fuente1"colspan="2">TOTAL</td>
      	<td id="fuente1">
      		<strong>
      			<?php echo $totalTD != '' ? $totalTD : "0"; ?>

      		</strong>  
      		<input name="valor_desp_rd[]" type="hidden" id="valor_desp_rd[]" value="<?php echo $totalTD; ?>" size="6"/> 
      	 
      	</td>
      	<td id="fuente1">&nbsp;</td>
      </tr>
  <?php } ?>





  <?php if($row_producido['id_rpp_rp']!='') {?>
  	<tr>
  		 
  		<td nowrap id="detalle2"colspan="2"><strong>Insumos  - Tipo</strong></td>
  		<td nowrap id="detalle2"><strong>Mts - Kilos</strong></td>
  		<td nowrap id="detalle2"><strong>ELIMINA</strong></td>
  	</tr>
  	<?php  for ($y=0;$y<=$totalRows_producido-1;$y++) { ?>
  		<tr>
  		 
  			<td id="fuente1"colspan="2"><?php $id4=mysql_result($producido,$y,id_rpp_rp); 
  			$id_rpp=$id4;
  			$sqlri="SELECT descripcion_insumo FROM insumo WHERE id_insumo='$id_rpp' AND clase_insumo IN(1,2,5)";
  			$resultri= mysql_query($sqlri);
  			$numri= mysql_num_rows($resultri);
  			if($numri >='1')
  			{ 
  				$nombre4 = mysql_result($resultri, 0, 'descripcion_insumo'); echo $nombre4; }?></td> 
  				<td id="fuente1"><?php $var4=mysql_result($producido,$y,producido); echo $var4; $totalMM+=$var4;?></td>
  				<td id="fuente1"><a href="javascript:eliminar_rts('id_ipsp',<?php $delip=mysql_result($producido,$y,id_rkp); echo $delip; ?>,'id_rsp',<?php echo $row_rollo_sellado_edit['id_r']; ?>,'produccion_registro_sellado_edit.php')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR " title="ELIMINAR" border="0" /></a></td>
  			</tr>
  		<?php } ?>
  		<tr>
  		 
  			<td id="fuente1"colspan="2">TOTAL</td>
  			<td id="fuente1">
  				<strong>
  					<?php if($totalMM!=''){echo $totalMM;}else{echo "0";}  ?>
  					<!--<input name="valor_prod_rp" type="hidden" id="valor_prod_rp" value="<?php echo $totalMM; ?>" size="6" onblur="getSumP();"/>-->
  				</strong>           </td>
  				<td id="fuente1">&nbsp;</td>
  			</tr>
  		<?php } ?>  



  <tr>
    <td colspan="4" id="fuente1"><input type="hidden" name="tipoCinta_ref[]" id="tipoCinta_ref[]"  value="<?php echo $row_referencia['tipoCinta_ref'];?>"/>
      <input type="hidden" name="tipoCinta_ref[]" id="tipoCinta_ref[]"  value="<?php echo $row_referencia['id_termica_op'];?>"/>
      <input type="hidden" name="ref_inven" id="ref_inven" value="<?php echo $row_referencia['cod_ref']."-".$row_referencia['version_ref'];?>"/></td>
  </tr>
  <tr>
    <td colspan="4"><!--tabla de caracteristicas y temperaturas--></td>
  </tr>
  <tr id="tr1">
    <td colspan="4" id="dato2"><strong>
      
      
      <input type="hidden" name="horas_muertas_rp" id="horasmuertas"  size="12"  value="<?php echo $totalTM;//$row_rollo_sellado_edit['horas_muertas_rp']; ?>" />
      <input type="hidden" name="horas_prep_rp" id="horasprep"  size="12" value="<?php echo $totalTP;//$row_rollo_sellado_edit['horas_prep_rp']; ?>" /> 
      <input name="id_op_rp" type="hidden" id="id_op_rp" value="<?php echo $row_rollo_sellado_edit['id_op_rp']; ?>" />
      <input name="id_ref_rp" type="hidden" id="id_ref_rp" value="<?php echo $row_referencia['id_ref']; ?>" />
      <input name="int_cod_ref_rp" type="hidden" id="int_cod_ref_rp" value="<?php echo $row_rollo_sellado_edit['int_cod_ref_rp']; ?>" />
      <input name="version_ref_rp" type="hidden" id="version_ref_rp" value="<?php echo $row_rollo_sellado_edit['version_ref_rp']; ?>" />
      <input name="id_proceso_rp" type="hidden" id="id_proceso_rp" value="4" />
      <input name="int_metroxmin_rp" id="metroxmin" type="hidden" size="5" value="<?php echo $row_rollo_sellado_edit['int_metroxmin_rp'];?>"/>
      <input type="hidden" name="MM_update" value="form1" />
      </strong></td>
  </tr>
</table>
 
  </form>
  <?php echo $conexion->header('footer'); ?>
</body>
</html>

<script type="text/javascript">
function alerta_sell(){
	  DatosGestiones3('14','id_r',document.form1.id_r.value,'&fechaI',document.form1.fecha_ini_rp.value);
 }
 
  restakilosT();
  kiloComparativoSell();
  kiloDisponible();
 
 function enviodeFormulario(){ 
      var resul =validaTodoSell();
       enviodeForms(resul);
     
 }

</script>


<?php
mysql_free_result($usuario); 
mysql_free_result($maquinas);
mysql_free_result($tiempoMuerto);
mysql_free_result($tiempoPreparacion);
mysql_free_result($desperdicio);
mysql_free_result($producido); 
?>
