<?php require_once('Connections/conexion1.php'); ?>
<?php
//LLAMADO A FUNCIONES
include('funciones/funciones_php.php');//SISTEMA RUW PARA LA BASE DE DATOS 
//FIN
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

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = $_SESSION['MM_Username'];
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = %s", GetSQLValueString($colname_usuario, "text"));
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

$colname_ref= "-1";
if (isset($_GET['id_op'])) {
  $colname_ref = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ref_op = sprintf("SELECT * FROM Tbl_orden_produccion,Tbl_referencia,Tbl_egp WHERE  Tbl_orden_produccion.id_op='%s' AND Tbl_orden_produccion.id_ref_op=Tbl_referencia.id_ref AND Tbl_referencia.n_egp_ref=Tbl_egp.n_egp AND Tbl_referencia.estado_ref='1'",$colname_ref);
$ref_op = mysql_query($query_ref_op, $conexion1) or die(mysql_error());
$row_ref_op = mysql_fetch_assoc($ref_op);
$totalRows_ref_op = mysql_num_rows($ref_op);

/*mysql_select_db($database_conexion1, $conexion1);
$query_proceso_empleado = "SELECT * FROM empleado a INNER JOIN TblProcesoEmpleado b ON a.codigo_empleado=b.codigo_empleado ORDER BY   a.codigo_empleado DESC";
$query_limit_proceso_empleado = sprintf("%s LIMIT %d, %d", $query_proceso_empleado, $startRow_proceso_empleado, $maxRows_proceso_empleado);
$proceso_empleado = mysql_query($query_limit_proceso_empleado, $conexion1) or die(mysql_error());
$rows_empleado = mysql_fetch_assoc($proceso_empleado);*/
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="css/vista.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/vista.js"></script>
<script type="text/javascript" src="js/formato.js"></script>
<title>SISADGE AC & CIA</title>
</head>
<body>
<div align="center">
  <table id="tablainterna">
    <tr>
      <td colspan="10" align="left"><img src="images/cabecera.jpg"></td>
    </tr>
    <tr>
      <td colspan="8" id="principal">ORDEN DE PRODUCCION (LIQUIDACION)</td>
      <td colspan="2" id="principal"><?php if($row_ref_op['b_estado_op']=='5'){echo "Finalizada";}else{ ?>
        <h4 style="color:#F00"><?php echo "En proceso";}?> </h4></td>
    </tr>
    <tr>
  <td colspan="10" id="subppal2">Identificaci&oacute;n de la orden de producci&oacute;n</td>
            </tr>
            <tr id="tr1">
              <td id="subppal2">N&uacute;mero de OP</td>
              <td colspan="2" id="subppal2">FECHA MONTAJE</td>
              <td id="subppal2">CODIGO</td>
              <td colspan="3" id="subppal2">DESCRIPCION </td>
              <td id="subppal2">Kilos Programados</td>
              <td id="subppal2">Unidadas Programadas</td>
              <td id="subppal2">% Desperdicio Programado</td>
            </tr>
            <tr>
              <td id="detalle1"><?php echo $row_ref_op['id_op']; ?></td>
              <td colspan="2"  nowrap id="detalle1"><?php echo $row_ref_op['fecha_registro_op']; ?></td>
              <td nowrap id="detalle1"><?php echo $row_ref_op['int_cod_ref_op']; ?> - <?php echo $row_ref_op['version_ref_op']; ?></td>
              <td colspan="3" nowrap id="detalle1"><?php echo $row_ref_op['str_tipo_bolsa_op']; ?> </td>
              <td nowrap id="detalle1"><?php echo $row_ref_op['int_kilos_op']; ?></td>
              <td nowrap id="detalle1"><?php echo $row_ref_op['int_cantidad_op']; ?></td>
              <td nowrap id="detalle1"><?php echo $row_ref_op['int_desperdicio_op']; ?></td>
            </tr>
            <tr>
              <td id="subppal2">TIPO Cliente</td>
              <td  nowrap id="fuente1"><?php 
	  $id_c=$row_ref_op['int_cliente_op'];
	  $sqlnclie="SELECT nombre_c,tipo_c FROM cliente WHERE id_c='$id_c'"; 
	  $resultclie=mysql_query($sqlnclie); 
	  $numclie=mysql_num_rows($resultclie); 
	  if($numclie >= '1') 
	  { $cliente=mysql_result($resultclie,0,'nombre_c');
	  $tipo=mysql_result($resultclie,0,'tipo_c'); echo $tipo;  }
	  ?></td>
              <td  nowrap id="subppal2">CLIENTE:</td>
              <td colspan="7"  nowrap id="fuente1"><?php echo $cliente;?></td>
            </tr>
            <tr>
              <td colspan="10" id="subppal2">Identificaci&oacute;n de la referencia</td>
            </tr>
            <tr>
              <td id="subppal2">Material</td>
              <td colspan="2" id="subppal2">Presentaci&oacute;n</td>
              <td colspan="2" id="subppal2">Tratamiento</td>
              <td colspan="2" id="subppal2">Ancho</td>
              <td id="subppal2">Largo</td>
              <td id="subppal2">Solapa</td>
              <td id="subppal2">Bolsillo</td>
            </tr>
            <tr>
              <td id="detalle1"><?php echo $row_ref_op['str_matrial_op']; ?></td>
              <td colspan="2" id="detalle1"><?php echo $row_ref_op['str_presentacion_op']; ?></td>
              <td colspan="2" id="detalle1"><?php echo $row_ref_op['str_tratamiento_op']; ?></td>
              <td colspan="2" id="detalle1"><?php echo $row_ref_op['ancho_ref']; ?></td>
              <td id="detalle1"><?php echo $row_ref_op['largo_ref']; ?></td>
              <td id="detalle1"><?php echo $row_ref_op['solapa_ref']; ?></td>
              <td id="detalle1"><?php echo $row_ref_op['bolsillo_guia_ref']; ?></td>
            </tr>
            <tr>
              <td id="subppal2">Traslape</td>
              <td colspan="2" id="subppal2">Area Total (CM&sup2;)</td>
              <td id="subppal2">Adhesivo</td>
              <td id="subppal2">Peso Millar</td>
              <td colspan="2" id="subppal2">Calibre Bolsa</td>
              <td id="subppal2">Calibre Bolsillo</td>
              <td id="subppal2">Peso Millar/Bols</td>
              <td id="subppal2">N&deg; Tintas</td>
            </tr>
            <tr>
              <td id="detalle1"><?php  if($row_ref_op['B_cantforma']==''){echo "0";}else{echo $row_ref_op['B_cantforma'];} ?></td>
              <td colspan="2" id="detalle2"><?php $cm_b=($row_ref_op['ancho_ref']*$row_ref_op['largo_ref']); $cm_s=($row_ref_op['ancho_ref']*$row_ref_op['solapa_ref']); echo $cm_b+$cm_s; ?></td>
              <td id="detalle2"><?php echo $row_ref_op['adhesivo_ref']; ?></td>
              <td id="detalle2"><?php echo $row_ref_op['peso_millar_ref']; ?></td>
              <td colspan="2" id="detalle2"><?php echo $row_ref_op['calibre_ref']; ?></td>
              <td id="detalle2"><?php if($row_ref_op['calibreBols_ref']==''){echo "0";}else{echo $row_ref_op['calibreBols_ref'];} ?></td>
              <td id="detalle2"><?php if($row_ref_op['peso_millar_bols']==''){echo "0";}else{echo $row_ref_op['peso_millar_bols'];} ?></td>
              <td id="detalle1"><?php echo $row_ref_op['impresion_ref']; ?></td>
            </tr>
            <tr>
              <td colspan="10" id="subppal2">Identificaci&oacute;n de los elementos del costo dentro del ciclo productivo</td>
            </tr>
            <tr>
              <td colspan="10" id="subtitulo2">Extrusion (Kilos)</td>
            </tr>
            <tr id="tr1">
              <td id="subppal2">Extruidos/kg</td>
              <td colspan="2" id="subppal2">Desperdicio Extrusi&oacute;n/kg</td>
              <td colspan="2" id="subppal2">Desperdicio Montaje/kg</td>
              <td id="subppal2">Extruidos Reales/kg</td>
              <td id="subppal2">Horas Real Extrusi&oacute;n</td>
              <td id="subppal2">Tiempo Perdido (minutos)</td>
              <td id="subppal2">Rollos </td>
              <td id="subppal2">Extruidos (mts)</td>
            </tr>
            <tr>
              <td id="detalle2"><?php 
	  $id_op=$row_ref_op['id_op'];
	  $sqlex="SELECT SUM(valor_prod_rp) AS kge FROM Tbl_reg_kilo_producido WHERE op_rp='$id_op' AND id_proceso_rkp='1'"; 
	  $resultex=mysql_query($sqlex); 
	  $numex=mysql_num_rows($resultex); 
	  if($numex >= '1') 
	  { $kilos_ex=mysql_result($resultex,0,'kge'); echo numeros_format($kilos_ex); }else {echo "0,00";}
	  ?>                <?php
	  
	  $id_op=$row_ref_op['id_op'];
	  $sqlrollo="SELECT COUNT(rollo_r) AS rollos,SUM(metro_r) AS metros,SUM(kilos_r) AS kilos FROM TblExtruderRollo WHERE id_op_r='$id_op'"; 
	  $resultrollo=mysql_query($sqlrollo); 
	  $numrollo=mysql_num_rows($resultrollo); 
	  if($numrollo >= '1') 
	  { $rollo=mysql_result($resultrollo,0,'rollos');
	    $metros=mysql_result($resultrollo,0,'metros');
		$kilos=mysql_result($resultrollo,0,'kilos');
	   }?></td>
              <td colspan="2" id="detalle2"><?php 
	  $id_op=$row_ref_op['id_op'];
	  $sqldesp="SELECT SUM(valor_desp_rd) AS kgDespe FROM Tbl_reg_desperdicio WHERE op_rd='$id_op' AND id_proceso_rd='1'"; 
	  $resultdesp=mysql_query($sqldesp); 
	  $numdesp=mysql_num_rows($resultdesp); 
	  if($numdesp >= '1') 
	  { $kilos_desp=mysql_result($resultdesp,0,'kgDespe'); echo numeros_format($kilos_desp); }else {echo "0,00";}
	  ?></td>
              <td colspan="2" id="detalle2"><?php 
	  $id_op=$row_ref_op['id_op'];
	  $sqlmont="SELECT SUM(valor_desp_rd) AS kgmont FROM Tbl_reg_desperdicio WHERE op_rd=$id_op AND id_proceso_rd='1' AND id_rpd_rd='10'"; 
	  $resultmont=mysql_query($sqlmont); 
	  $nummont=mysql_num_rows($resultmont); 
	  if($nummont >= '1') 
	  { $kgmont=mysql_result($resultmont,0,'kgmont'); echo numeros_format($kgmont); }else {echo "0,00";}
	  ?></td>
              <td id="detalle2"><?php echo numeros_format($kilos-$kilos_desp);?></td>
              <td id="detalle2"><?php 	 
	   $id_op=$row_ref_op['id_op'];
	  $sqlex="SELECT TIMEDIFF(fecha_fin_rp,fecha_ini_rp) AS horasT FROM Tbl_reg_produccion WHERE id_op_rp='$id_op' AND id_proceso_rp='1'"; 
	  $resultex=mysql_query($sqlex); 
	  $numex=mysql_num_rows($resultex); 
	  if($numex >= '1') 
	  { $tHoras_ex=mysql_result($resultex,0,'horasT');echo $tHoras_ex;
	    $horasM_exDec=horadecimal($tHoras_ex);
	  }else{echo "0";}
?></td>
              <td id="detalle2"><?php 
	  $id_op=$row_ref_op['id_op'];
	  $sqlexm="SELECT SUM(valor_tiem_rt) AS horasM FROM Tbl_reg_tiempo WHERE op_rt='$id_op' AND id_proceso_rt='1'"; 
	  $resultexm=mysql_query($sqlexm); 
	  $numexm=mysql_num_rows($resultexm); 
	  if($numexm >= '1') 
	  { $horasM_ex=mysql_result($resultexm,0,'horasM');
	  }else{echo "0";}
	 
 
	  $id_op=$row_ref_op['id_op'];
	  $sqlexp="SELECT SUM(valor_prep_rtp) AS horasP FROM Tbl_reg_tiempo_preparacion WHERE op_rtp='$id_op' AND id_proceso_rtp='1'"; 
	  $resultexp=mysql_query($sqlexp); 
	  $numexp=mysql_num_rows($resultexp); 
	  if($numexp >= '1') 
	  { $horasP_ex=mysql_result($resultexp,0,'horasP'); $totalTiempo=$horasP_ex+$horasM_ex;echo $totalTiempo;}
	  ?></td>
              <td id="detalle2"><?php echo $rollo;?></td>
              <td id="detalle2"><?php echo $metros;?></td>
            </tr>
            <tr id="tr1">
              <td id="subppal2" nowrap><strong>Bolsas Aprox.</strong></td>
              <td id="subppal2" nowrap>M. P Bolsillo</td>
              <td id="subppal2" nowrap>Mano de Obra</td>
              <td colspan="2" id="subppal2" nowrap><strong>CIF</strong></td>
              <td id="subppal2" nowrap><strong>GGA</strong></td>
              <td id="subppal2" nowrap><strong>GGV</strong></td>
              <td id="subppal2" nowrap><strong>GGF </strong></td>
              <td id="subppal2" nowrap><strong>COSTO MP</strong></td>
              <td id="subppal2" nowrap>COSTO TOTAL</td>
            </tr>
            <tr>
              <td id="detalle1"><?php $anchoporc=$row_ref_op['ancho_ref']/100; echo redondear_entero_puntos($metros/$anchoporc); ?></td>
              <td id="detalle1"><?php 
			  //pendiente aproximar el gasto del bolsillo en extruder
	  $id_op=$row_ref_op['id_op'];
	  $sqlbols="SELECT SUM(valor_prod_rp) AS kglam FROM Tbl_reg_kilo_producido WHERE id_rpp='1407' AND op_rp='$id_op' AND id_proceso_rkp='4'"; 
	  $resultbols=mysql_query($sqlbols); 
	  $numbols=mysql_num_rows($resultbols); 
	  if($numbols >= '1') 
	  { $kilos_bols=mysql_result($resultbols,0,'kglam'); echo numeros_format($kilos_bols); }else {echo "0,00";}
	  ?></td>
              <td id="detalle2"><strong>
                <?php
	  //HORAS TRABAJADAS DE LA O.P
	  $id_op=$row_ref_op['id_op'];
	  $sqlex="SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(fecha_fin_rp,fecha_ini_rp)))) AS horasT FROM Tbl_reg_produccion WHERE id_op_rp='$id_op' AND id_proceso_rp='1'"; 
	  $resultex=mysql_query($sqlex); 
	  $numex=mysql_num_rows($resultex); 
	  if($numex >= '1') 
	  { $tHoras_ex=mysql_result($resultex,0,'horasT');   	
	  }	  
	  $tiempoDesperdiciado=$totalTiempo;//TIEMPO DESPERDICIADO EN SEGUNDOS
	// echo  $tiempoDesper= enteroHoras($tiempoDesperdiciado);
	 $horasaDecimal=sumarMinutosaHora($tHoras_ex,$tiempoDesperdiciado);
	 $totalHorasManoObra=horadecimal($horasaDecimal);//TOTAL TIEMPO DE LA O.P EN EXTRUDER
		  
    //SUELDO, AUXILIOTRANSPORTE, APORTES,
    $extruder='4';	
	$sqlemp="SELECT TblProcesoEmpleado.dias_empleado AS DIAS, empleado.horasmes_reales AS HORAS,empleado.diasmes_reales AS MES, COUNT(TblProcesoEmpleado.id_pem) AS ID, SUM(TblProcesoEmpleado.sueldo_empleado) AS SUELDO,SUM(TblProcesoEmpleado.aux_empleado) AS AUXILIO, SUM(TblAportes.total) AS APORTES FROM empleado,TblProcesoEmpleado,TblAportes WHERE empleado.codigo_empleado=TblProcesoEmpleado.codigo_empleado AND empleado.codigo_empleado=TblAportes.codigo_empl AND TblProcesoEmpleado.proceso_empleado=$extruder";
	$resultemp=mysql_query($sqlemp); 
	$numemp=mysql_num_rows($resultemp);
	if ($numemp>='1') {   	
	$operarios=mysql_result($resultemp,0,'ID'); //CANTIDAD DE EMPLEADOS EN EXTRUDER 
	$horasmes_reales=mysql_result($resultemp,0,'HORAS'); //LO EQUIVALENTE A LAS HORAS QUE SE TRABAJAN EN UN MES REAL 186,6666667

	$sueldo=mysql_result($resultemp,0,'SUELDO');  
	$sueld=$sueldo;
	$aux_transporte=mysql_result($resultemp,0,'AUXILIO');
	$aux_trans=	$aux_transporte;		  
	$equivalenteadia=mysql_result($resultemp,0,'MES'); 	//esto es 280 dias  / 12 
	$diasreportados=mysql_result($resultemp,0,'DIAS'); 
	$aportestotal=mysql_result($resultemp,0,'APORTES'); 
	$aport=$aportestotal;//ESTA VARIABLE SE USA EN SIGUIENTE FUNCION operaciones
	$constantemes=30;  //DIAS DEL MES SEGUN NOMINA PARA HACER LA OPERACION REALMENTE SON 23.333
	}
	//NOVEDADES DEL MES DE LOS OPRARIOS DE EXTRUDER 
	$fechafin=$_GET['fechafin'];
	//$sivacio="LAST_DAY('$fechafin')";
	$sqlnovedad="SELECT SUM(pago_acycia) AS valoracycia, SUM(pago_eps) AS valoreps, SUM(TblNovedades.dias_incapacidad) as DIAS,SUM(TblNovedades.horas_extras) as HORAS,SUM(TblNovedades.recargos) as RECARGOS,SUM(TblNovedades.festivos) as FESTIVOS
FROM empleado,TblNovedades
WHERE  empleado.codigo_empleado=TblNovedades.codigo_empleado AND fecha BETWEEN DATE_FORMAT('$fechafin', '%Y-%m-01') AND '$fechafin'";
	$resultnovedad=mysql_query($sqlnovedad); 
	$numnovedad=mysql_num_rows($resultnovedad);
	if ($numnovedad >='1') { 
    $valoracyci=mysql_result($resultnovedad,0,'valoracycia');
	$valoracycia=$valoracyci;
	$valorep=mysql_result($resultnovedad,0,'valoreps');
	$valoreps=$valorep;
	$dias_incapacid=mysql_result($resultnovedad,0,'DIAS'); 
	$dias_incapacidad=$dias_incapacid;
	$horas=mysql_result($resultnovedad,0,'HORAS');
	$recargos=mysql_result($resultnovedad,0,'RECARGOS'); 
	$festivos=mysql_result($resultnovedad,0,'FESTIVOS');
	$pagoIncapacidad=$valoracycia;
	$total_recargos = ($horas+$recargos+$festivos); 	  
	}		
	//operaciones
	$sueldoExtruder = sueldoMes($sueld,$aux_trans,$equivalenteadia,$diasreportados,$constantemes,$total_recargos,$aport,$pagoIncapacidad);
	$costoMesNeto = $sueldoExtruder/$operarios ;
	$costo_hora = $costoMesNeto/$horasmes_reales; //COSTO HORA DE MANO DE OBRA
	$manodeObra = ($costo_hora*$totalHorasManoObra);
    echo redondear_entero_puntos($manodeObra);//TOTAL DE LA O.P		   
	   ?>
              </strong>
                <?php
	  //TOTAL DE TIEMPOS EN EXTRUDER GENERAL TODAS LAS O.P
	  $fechafin=$_GET['fechafin'];
	  $sqlTiempomes="SELECT TIMEDIFF(MAX(fecha_fin_rp),MIN(fecha_ini_rp)) AS TIEMPOMES FROM `Tbl_reg_produccion` WHERE `id_proceso_rp`='1' AND `fecha_ini_rp` BETWEEN DATE_FORMAT('$fechafin', '%Y-%m-01') AND '$fechafin' AND `fecha_fin_rp` BETWEEN DATE_FORMAT('$fechafin', '%Y-%m-01') AND '$fechafin'"; 
	  $resultTiempomes=mysql_query($sqlTiempomes); 
	  $numTiempomes=mysql_num_rows($resultTiempomes); 
	  if($numTiempomes >= '1')
	  { 
	  $Tiempome=mysql_result($resultTiempomes,0,'TIEMPOMES');//TIEMPO EN EXTRUSION DEL MES DE TODAS LAS O.P
	  $Tiempomes=horadecimal($Tiempome);
		}		
      //TOTAL DE KILOS EN EXTRUDER GENERAL  DE TODAS LAS O.P
/*      $fechafin=$_GET['fechafin'];
	  $sqlkilosmes="SELECT SUM(`valor_prod_rp`) AS KILOSMES FROM `Tbl_reg_kilo_producido` WHERE `id_proceso_rkp`='1' AND fecha_rkp BETWEEN DATE_FORMAT('$fechafin', '%Y-%m-01') AND '$fechafin'"; 
		  //  SELECT  SUM(value) as total FROM data WHERE DATE BETWEEN DATE_FORMAT(CURDATE(), '%Y-%m-01') AND CURDATE();
	  $resultkilosmes=mysql_query($sqlkilosmes); 
	  $numkilosmes=mysql_num_rows($resultkilosmes); 
	  if($numkilosmes >= '1')
	  { 
	  $kilosmes=mysql_result($resultkilosmes,0,'KILOSMES');//KILOS EXTRUIDOS DEL MES
		}	*/
		
		
	  ?></td>
              <td colspan="2" id="detalle2"><strong>
                <?php 
      //CIF GENERAL
 	  $sqlcif="SELECT Tbl_generadores_valor.valor_gv AS valor FROM Tbl_generadores, Tbl_generadores_valor WHERE Tbl_generadores.categoria_generadores='CIF' AND Tbl_generadores.id_generadores=Tbl_generadores_valor.id_generadores_gv AND Tbl_generadores_valor.estado_gv='0'"; 
     $valores = mysql_query($sqlcif, $conexion1) or die(mysql_error());
     $row_valores = mysql_fetch_assoc($valores);
	 $cont=0;
	  do{
		  $valor = $row_valores['valor'] ;
		  $porcientocif=21;
        $cifextrudermes = porcentaje($valor,$porcientocif);
	   $cont+=$cifextrudermes;//COSTO GENERAL DEL CIF EN EXTRUDER SEGUN PORCENTAJE	   
    } while ($row_valores = mysql_fetch_assoc($valores));
	 $costounidhoracif=$cont/$Tiempomes;//SACAR COSTO UNIDAD HORA CIF
	 $costoTiempoHoraOpcif = ($costounidhoracif * $horasM_exDec);//UNIDAD DE CIF Y UNIDADESDE HORAS EN EXTRUDER POR O.P
	 echo redondear_entero_puntos($costoTiempoHoraOpcif); //COSTO DE HORA EXTRUIDA CON CIF	 
	  ?>
              </strong></td>
              <td id="detalle2"><strong>
                <?php 
	  //GGA
	  	  $sqlgga="SELECT SUM(Tbl_generadores_valor.valor_gv) AS valor FROM Tbl_generadores, Tbl_generadores_valor WHERE Tbl_generadores.categoria_generadores='GGA' AND Tbl_generadores.id_generadores=Tbl_generadores_valor.id_generadores_gv AND Tbl_generadores_valor.estado_gv='0'"; 
     $valoresgga = mysql_query($sqlgga, $conexion1) or die(mysql_error());
     $row_valoresgga = mysql_fetch_assoc($valoresgga);
	 $contgga=0;
	  do{
		  $valorgga = $row_valoresgga['valor'] ;
		  $porcientogga=21;
        $ggaextrudermes = porcentaje($valorgga,$porcientogga);
	   $contgga+=$ggaextrudermes;//COSTO GENERAL DEL CIF EN EXTRUDER SEGUN PORCENTAJE	   
    } while ($row_valoresgga = mysql_fetch_assoc($valoresgga));
	 $costounidhoragga=$contgga/$Tiempomes;//SACAR COSTO UNIDAD HORA GGA
	 $costoTiempoHoraOpgga = ($costounidhoragga * $horasM_exDec);//UNIDAD DE CIF Y UNIDADESDE HORAS EN EXTRUDER POR O.P
	 echo redondear_entero_puntos($costoTiempoHoraOpgga); //COSTO DE HORA EXTRUIDA CON CIF	
	  ?>
              </strong></td>
              <td id="detalle2"><strong>
                <?php 
	  //GGV
	  	  $sqlggv="SELECT SUM(Tbl_generadores_valor.valor_gv) AS valor FROM Tbl_generadores, Tbl_generadores_valor WHERE Tbl_generadores.categoria_generadores='GGV' AND Tbl_generadores.id_generadores=Tbl_generadores_valor.id_generadores_gv AND Tbl_generadores_valor.estado_gv='0'"; 
     $valoresggv = mysql_query($sqlggv, $conexion1) or die(mysql_error());
     $row_valoresggv = mysql_fetch_assoc($valoresggv);
	 $contggv=0;
	  do{
		  $valorggv = $row_valoresggv['valor'] ;
		  $porcientoggv=21;
        $ggvextrudermes = porcentaje($valorggv,$porcientoggv);
	   $contggv+=$ggvextrudermes;//COSTO GENERAL DEL CIF EN EXTRUDER SEGUN PORCENTAJE	   
    } while ($row_valoresggv = mysql_fetch_assoc($valoresggv));
	 $costounidhoraggv=$contggv/$Tiempomes;//SACAR COSTO UNIDAD HORA ggv
	 $costoTiempoHoraOpggv = ($costounidhoraggv * $horasM_exDec);//UNIDAD DE CIF Y UNIDADESDE HORAS EN EXTRUDER POR O.P
	 echo redondear_entero_puntos($costoTiempoHoraOpggv); //COSTO DE HORA EXTRUIDA CON CIF	
	  ?>
              </strong></td>
              <td id="detalle2"><strong>
                <?php 
	  //GGF
	  	  $sqlggf="SELECT SUM(Tbl_generadores_valor.valor_gv) AS valor FROM Tbl_generadores, Tbl_generadores_valor WHERE Tbl_generadores.categoria_generadores='GGF' AND Tbl_generadores.id_generadores=Tbl_generadores_valor.id_generadores_gv AND Tbl_generadores_valor.estado_gv='0'"; 
     $valoresggf = mysql_query($sqlggf, $conexion1) or die(mysql_error());
     $row_valoresggf = mysql_fetch_assoc($valoresggf);
	 $contggf=0;
	  do{
		  $valorggf = $row_valoresggf['valor'] ;
		  $porcientoggf=21;
        $ggfextrudermes = porcentaje($valorggf,$porcientoggf);
	   $contggf+=$ggfextrudermes;//COSTO GENERAL DEL CIF EN EXTRUDER SEGUN PORCENTAJE	   
    } while ($row_valoresggf = mysql_fetch_assoc($valoresggf));
	 $costounidhoraggf=$contggf/$Tiempomes;//SACAR COSTO UNIDAD HORA ggf
	 $costoTiempoHoraOpggf = ($costounidhoraggf * $horasM_exDec);//UNIDAD DE CIF Y UNIDADESDE HORAS EN EXTRUDER POR O.P
	 echo redondear_entero_puntos($costoTiempoHoraOpggf); //COSTO DE HORA EXTRUIDA CON CIF	
	  ?>
              </strong></td>
              <td id="detalle2"><strong>
                <?php 
	   //COSTO MATERIA PRIMA
	  $id_op=$row_ref_op['id_op'];
	  $sqlcostoMP="SELECT insumo.valor_unitario_insumo, Tbl_reg_kilo_producido.id_rpp_rp, COUNT(insumo.id_insumo) AS ITEMS, SUM(insumo.valor_unitario_insumo) AS VALORKILO, SUM(Tbl_reg_kilo_producido.valor_prod_rp) AS CANTKILOS FROM  insumo,Tbl_reg_kilo_producido WHERE insumo.id_insumo = Tbl_reg_kilo_producido.id_rpp_rp AND Tbl_reg_kilo_producido.id_proceso_rkp='1' AND Tbl_reg_kilo_producido.op_rp='$id_op'"; 
	  $resultcostoMP=mysql_query($sqlcostoMP); 
	  $numcostoMP=mysql_num_rows($resultcostoMP); 
	  if($numcostoMP >= '1')
	  { $ITEMS=mysql_result($resultcostoMP,0,'ITEMS');
	    $cantKilos=mysql_result($resultcostoMP,0,'CANTKILOS');
	    $valorKilo=mysql_result($resultcostoMP,0,'VALORKILO');
		$costoMP=$cantKilos*$valorKilo/$ITEMS;
		echo redondear_entero_puntos($costoMP);
		}	
	  ?>
              </strong></td>
              <td id="detalle2"><h2><?php echo redondear_entero_puntos($manodeObra+$costoTiempoHoraOpcif+$costoTiempoHoraOpgga+$costoTiempoHoraOpggv+$costoTiempoHoraOpggf+$costoMP);  ?> </h2></td>
            </tr>
            <tr>
              <td colspan="10" id="subtitulo2">Impresion (Kilos)</td>
            </tr>
            <tr id="tr1">
              <td id="subppal2">Impresos/kg</td>
              <td colspan="2" id="subppal2">Desperdicio Impresi&oacute;n/kg</td>
              <td colspan="2" id="subppal2">Desperdicio Montaje/kg</td>
              <td id="subppal2">Impresos Reales/kg</td>
              <td id="subppal2">Horas Real Impresi&oacute;n</td>
              <td id="subppal2">Tiempo Perdido (minutos)</td>
              <td id="subppal2">Rollos </td>
              <td id="subppal2">Extruidos (mts)</td>
            </tr>
            <tr>
              <td id="detalle2"><?php 
	  $id_op=$row_ref_op['id_op'];
	  $sqlimp="SELECT SUM(valor_prod_rp) AS kge FROM Tbl_reg_kilo_producido WHERE op_rp='$id_op' AND id_proceso_rkp='2'"; 
	  $resultimp=mysql_query($sqlimp); 
	  $numimp=mysql_num_rows($resultimp); 
	  if($numimp >= '1') 
	  { $kilos_imp=mysql_result($resultimp,0,'kge'); echo numeros_format($kilos_imp); }else {echo "0,00";}
	  ?>                <?php
	  
	  $id_op=$row_ref_op['id_op'];
	  $sqlrollo="SELECT COUNT(rollo_r) AS rollos,SUM(metro_r) AS metros,SUM(kilos_r) AS kilos FROM TblImpresionRollo WHERE id_op_r='$id_op'"; 
	  $resultrollo=mysql_query($sqlrollo); 
	  $numrollo=mysql_num_rows($resultrollo); 
	  if($numrollo >= '1') 
	  { $rollo_imp=mysql_result($resultrollo,0,'rollos');
	    $metros_imp=mysql_result($resultrollo,0,'metros');
		$kilos_imp=mysql_result($resultrollo,0,'kilos');
	   }?></td>
              <td colspan="2" id="detalle2"><?php 
	  $id_op=$row_ref_op['id_op'];
	  $sqldesp="SELECT SUM(valor_desp_rd) AS kgDespe FROM Tbl_reg_desperdicio WHERE op_rd='$id_op' AND id_proceso_rd='2'"; 
	  $resultdesp=mysql_query($sqldesp); 
	  $numdesp=mysql_num_rows($resultdesp); 
	  if($numdesp >= '1') 
	  { $kilos_desp_imp=mysql_result($resultdesp,0,'kgDespe'); echo numeros_format($kilos_desp_imp); }else {echo "0,00";}
	  ?></td>
              <td colspan="2" id="detalle2"><?php 
	  $id_op=$row_ref_op['id_op'];
	  $sqlmont="SELECT SUM(valor_desp_rd) AS kgmont FROM Tbl_reg_desperdicio WHERE op_rd=$id_op AND id_proceso_rd='2' AND id_rpd_rd='10'"; 
	  $resultmont=mysql_query($sqlmont); 
	  $nummont=mysql_num_rows($resultmont); 
	  if($nummont >= '1') 
	  { $kgmont_imp=mysql_result($resultmont,0,'kgmont'); echo numeros_format($kgmont_imp); }else {echo "0,00";}
	  ?></td>
              <td id="detalle2"><?php echo numeros_format($kilos_imp-$kilos_desp_imp);?></td>
              <td id="detalle2"><?php 	 
	  $id_op=$row_ref_op['id_op'];
	  $sqlimp="SELECT TIMEDIFF(MAX(`fechaF_r`),MIN(`fechaI_r`)) AS horasT FROM TblImpresionRollo WHERE id_op_r='$id_op' "; 
	  $resultimp=mysql_query($sqlimp); 
	  $numimp=mysql_num_rows($resultimp); 
	  if($numimp >= '1') 
	  { $tHoras_imp=mysql_result($resultimp,0,'horasT');echo $tHoras_imp;
	    $horasM_impDec=horadecimal($tHoras_imp);
	  }else{echo "0";}
?></td>
              <td id="detalle2"><?php 
	  $id_op=$row_ref_op['id_op'];
	  $sqlimpm="SELECT SUM(valor_tiem_rt) AS horasM FROM Tbl_reg_tiempo WHERE op_rt='$id_op' AND id_proceso_rt='2'"; 
	  $resultimpm=mysql_query($sqlimpm); 
	  $numimpm=mysql_num_rows($resultimpm); 
	  if($numimpm >= '1') 
	  { $horasM_imp=mysql_result($resultimpm,0,'horasM');
	  }else{echo "0";}
	 
 
	  $id_op=$row_ref_op['id_op'];
	  $sqlimpp="SELECT SUM(valor_prep_rtp) AS horasP FROM Tbl_reg_tiempo_preparacion WHERE op_rtp='$id_op' AND id_proceso_rtp='2'"; 
	  $resultimpp=mysql_query($sqlimpp); 
	  $numimpp=mysql_num_rows($resultimpp); 
	  if($numimpp >= '1') 
	  { $horasP_imp=mysql_result($resultimpp,0,'horasP'); $totalTiempo_imp=$horasP_imp+$horasM_imp;echo $totalTiempo_imp;}
	  ?></td>
              <td id="detalle2"><?php echo $rollo_imp;?></td>
              <td id="detalle2"><?php echo $metros_imp;?></td>
            </tr>
            <tr id="tr1">
              <td id="subppal2"><strong>Bolsas Aprox.</strong></td>
              <td colspan="2" id="subppal2">Mano de Obra</td>
              <td colspan="2" id="subppal2"><strong>CIF </strong></td>
              <td id="subppal2" nowrap><strong>GGA</strong></td>
              <td id="subppal2" nowrap><strong>GGV</strong></td>
              <td id="subppal2" nowrap><strong>GGF</strong></td>
              <td id="subppal2" nowrap><strong>COSTO MP</strong></td>
              <td id="subppal2" nowrap>COSTO TOTAL</td>
            </tr>
            <tr>
              <td id="detalle1"><?php $anchoporc_imp=$row_ref_op['ancho_ref']/100; echo redondear_entero_puntos($metros_imp/$anchoporc_imp); ?></td>
              <td colspan="2" id="detalle2"><strong>
                <?php
	  //HORAS TRABAJADAS DE LA O.P
	  $id_op=$row_ref_op['id_op'];
	  $sqlimp="SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(fecha_fin_rp,fecha_ini_rp)))) AS horasT FROM Tbl_reg_produccion WHERE id_op_rp='$id_op' AND id_proceso_rp='1'"; 
	  $resultimp=mysql_query($sqlimp); 
	  $numimp=mysql_num_rows($resultimp); 
	  if($numimp >= '1') 
	  { $tHoras_imp=mysql_result($resultimp,0,'horasT');   	
	  }	  
	  $tiempoDesperdiciado_imp=$totalTiempo_imp;//TIEMPO DESPERDICIADO EN SEGUNDOS
	// echo  $tiempoDesper= enteroHoras($tiempoDesperdiciado);
	 $horasaDecimal_imp=sumarMinutosaHora($tHoras_imp,$tiempoDesperdiciado_imp);
	 $totalHorasManoObra_imp=horadecimal($horasaDecimal_imp);//TOTAL TIEMPO DE LA O.P EN EXTRUDER
		  
    //SUELDO, AUXILIOTRANSPORTE, APORTES,
    $extruder='5';	
	$sqlemp="SELECT TblProcesoEmpleado.dias_empleado AS DIAS, empleado.horasmes_reales AS HORAS,empleado.diasmes_reales AS MES, COUNT(TblProcesoEmpleado.id_pem) AS ID, SUM(TblProcesoEmpleado.sueldo_empleado) AS SUELDO,SUM(TblProcesoEmpleado.aux_empleado) AS AUXILIO, SUM(TblAportes.total) AS APORTES FROM empleado,TblProcesoEmpleado,TblAportes WHERE empleado.codigo_empleado=TblProcesoEmpleado.codigo_empleado AND empleado.codigo_empleado=TblAportes.codigo_empl AND TblProcesoEmpleado.proceso_empleado=$extruder";
	$resultemp=mysql_query($sqlemp); 
	$numemp=mysql_num_rows($resultemp);
	if ($numemp>='1') {   	
	$operarios=mysql_result($resultemp,0,'ID'); //CANTIDAD DE EMPLEADOS EN EXTRUDER 
	$horasmes_reales=mysql_result($resultemp,0,'HORAS'); //LO EQUIVALENTE A LAS HORAS QUE SE TRABAJAN EN UN MES REAL 186,6666667

	$sueldo=mysql_result($resultemp,0,'SUELDO');  
	$sueld=$sueldo;
	$aux_transporte=mysql_result($resultemp,0,'AUXILIO');
	$aux_trans=	$aux_transporte;		  
	$equivalenteadia=mysql_result($resultemp,0,'MES'); 	//esto es 280 dias  / 12 
	$diasreportados=mysql_result($resultemp,0,'DIAS'); 
	$aportestotal=mysql_result($resultemp,0,'APORTES'); 
	$aport=$aportestotal;//ESTA VARIABLE SE USA EN SIGUIENTE FUNCION operaciones
	$constantemes=30;  //DIAS DEL MES SEGUN NOMINA PARA HACER LA OPERACION REALMENTE SON 23.333
	}
	//NOVEDADES DEL MES DE LOS OPRARIOS DE EXTRUDER 
	$fechafin=$_GET['fechafin'];
	//$sivacio="LAST_DAY('$fechafin')";
	$sqlnovedad="SELECT SUM(pago_acycia) AS valoracycia, SUM(pago_eps) AS valoreps, SUM(TblNovedades.dias_incapacidad) as DIAS,SUM(TblNovedades.horas_extras) as HORAS,SUM(TblNovedades.recargos) as RECARGOS,SUM(TblNovedades.festivos) as FESTIVOS
FROM empleado,TblNovedades
WHERE  empleado.codigo_empleado=TblNovedades.codigo_empleado AND fecha BETWEEN DATE_FORMAT('$fechafin', '%Y-%m-01') AND '$fechafin'";
	$resultnovedad=mysql_query($sqlnovedad); 
	$numnovedad=mysql_num_rows($resultnovedad);
	if ($numnovedad >='1') { 
    $valoracyci=mysql_result($resultnovedad,0,'valoracycia');
	$valoracycia=$valoracyci;
	$valorep=mysql_result($resultnovedad,0,'valoreps');
	$valoreps=$valorep;
	$dias_incapacid=mysql_result($resultnovedad,0,'DIAS'); 
	$dias_incapacidad=$dias_incapacid;
	$horas=mysql_result($resultnovedad,0,'HORAS');
	$recargos=mysql_result($resultnovedad,0,'RECARGOS'); 
	$festivos=mysql_result($resultnovedad,0,'FESTIVOS');
	$pagoIncapacidad=$valoracycia;
	$total_recargos = ($horas+$recargos+$festivos); 	  
	}		
	//operaciones
	$sueldoImpresion = sueldoMes($sueld,$aux_trans,$equivalenteadia,$diasreportados,$constantemes,$total_recargos,$aport,$pagoIncapacidad);
	$costoMesNeto = $sueldoImpresion/$operarios ;
	$costo_hora = $costoMesNeto/$horasmes_reales; //COSTO HORA DE MANO DE OBRA
	$manodeObra_imp = ($costo_hora*$totalHorasManoObra);
    echo redondear_entero_puntos($manodeObra_imp);//TOTAL DE LA O.P		   
	   ?>
                <?php
	  //TOTAL DE TIEMPOS EN EXTRUDER GENERAL TODAS LAS O.P
	  $fechafin=$_GET['fechafin'];
	  $sqlTiempomes="SELECT TIMEDIFF(MAX(fecha_fin_rp),MIN(fecha_ini_rp)) AS TIEMPOMES FROM `Tbl_reg_produccion` WHERE `id_proceso_rp`='2' AND `fecha_ini_rp` BETWEEN DATE_FORMAT('$fechafin', '%Y-%m-01') AND '$fechafin' AND `fecha_fin_rp` BETWEEN DATE_FORMAT('$fechafin', '%Y-%m-01') AND '$fechafin'"; 
	  $resultTiempomes=mysql_query($sqlTiempomes); 
	  $numTiempomes=mysql_num_rows($resultTiempomes); 
	  if($numTiempomes >= '1')
	  { 
	  $Tiempome=mysql_result($resultTiempomes,0,'TIEMPOMES');//TIEMPO EN EXTRUSION DEL MES DE TODAS LAS O.P
	  $Tiempomes_imp=horadecimal($Tiempome);
		}		
	  ?>
              </strong></td>
              <td colspan="2" id="detalle2"><strong>
                <?php 
      //CIF GENERAL
 	  $sqlcif="SELECT Tbl_generadores_valor.valor_gv AS valor FROM Tbl_generadores, Tbl_generadores_valor WHERE Tbl_generadores.categoria_generadores='CIF' AND Tbl_generadores.id_generadores=Tbl_generadores_valor.id_generadores_gv AND Tbl_generadores_valor.estado_gv='0'"; 
     $valores = mysql_query($sqlcif, $conexion1) or die(mysql_error());
     $row_valores = mysql_fetch_assoc($valores);
	 $cont=0;
	  do{
		  $valor = $row_valores['valor'] ;
		  $porcientocif=20;
        $cifimpresionmes = porcentaje($valor,$porcientocif);
	   $cont+=$cifimpresionmes;//COSTO GENERAL DEL CIF EN EXTRUDER SEGUN PORCENTAJE	   
    } while ($row_valores = mysql_fetch_assoc($valores));
	 $costounidhoracif=$cont/$Tiempomes_imp;//SACAR COSTO UNIDAD HORA CIF
	 $costoTiempoHoraOpcif_imp = ($costounidhoracif * $horasM_impDec);//UNIDAD DE CIF Y UNIDADESDE HORAS EN EXTRUDER POR O.P
	 echo redondear_entero_puntos($costoTiempoHoraOpcif_imp); //COSTO DE HORA EXTRUIDA CON CIF	 
	  ?>
              </strong></td>
              <td id="detalle2"><strong>
                <?php 
	  //GGA
	  	  $sqlgga="SELECT SUM(Tbl_generadores_valor.valor_gv) AS valor FROM Tbl_generadores, Tbl_generadores_valor WHERE Tbl_generadores.categoria_generadores='GGA' AND Tbl_generadores.id_generadores=Tbl_generadores_valor.id_generadores_gv AND Tbl_generadores_valor.estado_gv='0'"; 
     $valoresgga = mysql_query($sqlgga, $conexion1) or die(mysql_error());
     $row_valoresgga = mysql_fetch_assoc($valoresgga);
	 $contgga=0;
	  do{
		  $valorgga = $row_valoresgga['valor'] ;
		  $porcientogga=20;
        $ggaimpresionmes = porcentaje($valorgga,$porcientogga);
	   $contgga+=$ggaimpresionmes;//COSTO GENERAL DEL CIF EN EXTRUDER SEGUN PORCENTAJE	   
    } while ($row_valoresgga = mysql_fetch_assoc($valoresgga));
	 $costounidhoragga=$contgga/$Tiempomes_imp;//SACAR COSTO UNIDAD HORA GGA
	 $costoTiempoHoraOpgga_imp = ($costounidhoragga * $horasM_impDec);//UNIDAD DE CIF Y UNIDADESDE HORAS EN EXTRUDER POR O.P
	 echo redondear_entero_puntos($costoTiempoHoraOpgga_imp); //COSTO DE HORA EXTRUIDA CON CIF	
	  ?>
              </strong></td>
              <td id="detalle2"><strong>
                <?php 
	  //GGV
	  	  $sqlggv="SELECT SUM(Tbl_generadores_valor.valor_gv) AS valor FROM Tbl_generadores, Tbl_generadores_valor WHERE Tbl_generadores.categoria_generadores='GGV' AND Tbl_generadores.id_generadores=Tbl_generadores_valor.id_generadores_gv AND Tbl_generadores_valor.estado_gv='0'"; 
     $valoresggv = mysql_query($sqlggv, $conexion1) or die(mysql_error());
     $row_valoresggv = mysql_fetch_assoc($valoresggv);
	 $contggv=0;
	  do{
		  $valorggv = $row_valoresggv['valor'] ;
		  $porcientoggv=20;
        $ggvimpresionmes = porcentaje($valorggv,$porcientoggv);
	   $contggv+=$ggvimpresionmes;//COSTO GENERAL DEL CIF EN EXTRUDER SEGUN PORCENTAJE	   
    } while ($row_valoresggv = mysql_fetch_assoc($valoresggv));
	 $costounidhoraggv=$contggv/$Tiempomes_imp;//SACAR COSTO UNIDAD HORA ggv
	 $costoTiempoHoraOpggv_imp = ($costounidhoraggv * $horasM_impDec);//UNIDAD DE CIF Y UNIDADESDE HORAS EN EXTRUDER POR O.P
	 echo redondear_entero_puntos($costoTiempoHoraOpggv_imp); //COSTO DE HORA EXTRUIDA CON CIF	
	  ?>
              </strong></td>
              <td id="detalle2"><strong>
                <?php 
	  //GGF
	  	  $sqlggf="SELECT SUM(Tbl_generadores_valor.valor_gv) AS valor FROM Tbl_generadores, Tbl_generadores_valor WHERE Tbl_generadores.categoria_generadores='GGF' AND Tbl_generadores.id_generadores=Tbl_generadores_valor.id_generadores_gv AND Tbl_generadores_valor.estado_gv='0'"; 
     $valoresggf = mysql_query($sqlggf, $conexion1) or die(mysql_error());
     $row_valoresggf = mysql_fetch_assoc($valoresggf);
	 $contggf=0;
	  do{
		  $valorggf = $row_valoresggf['valor'] ;
		  $porcientoggf=20;
        $ggfimpresionmes = porcentaje($valorggf,$porcientoggf);
	   $contggf+=$ggfimpresionmes;//COSTO GENERAL DEL CIF EN EXTRUDER SEGUN PORCENTAJE	   
    } while ($row_valoresggf = mysql_fetch_assoc($valoresggf));
	 $costounidhoraggf=$contggf/$Tiempomes_imp;//SACAR COSTO UNIDAD HORA ggf
	 $costoTiempoHoraOpggf_imp = ($costounidhoraggf * $horasM_impDec);//UNIDAD DE CIF Y UNIDADESDE HORAS EN EXTRUDER POR O.P
	 echo redondear_entero_puntos($costoTiempoHoraOpggf_imp); //COSTO DE HORA EXTRUIDA CON CIF	
	  ?>
              </strong></td>
              <td id="detalle2"><strong>
                <?php 
	   //COSTO MATERIA PRIMA
	  $id_op=$row_ref_op['id_op'];
	  $sqlcostoMP="SELECT insumo.valor_unitario_insumo, Tbl_reg_kilo_producido.id_rpp_rp, COUNT(insumo.id_insumo) AS ITEMS, SUM(insumo.valor_unitario_insumo) AS VALORKILO, SUM(Tbl_reg_kilo_producido.valor_prod_rp) AS CANTKILOS FROM  insumo,Tbl_reg_kilo_producido WHERE insumo.id_insumo = Tbl_reg_kilo_producido.id_rpp_rp AND Tbl_reg_kilo_producido.id_proceso_rkp='2' AND Tbl_reg_kilo_producido.op_rp='$id_op'"; 
	  $resultcostoMP=mysql_query($sqlcostoMP); 
	  $numcostoMP=mysql_num_rows($resultcostoMP); 
	  if($numcostoMP >= '1')
	  { $ITEMS=mysql_result($resultcostoMP,0,'ITEMS');
	    $cantKilos=mysql_result($resultcostoMP,0,'CANTKILOS');
	    $valorKilo=mysql_result($resultcostoMP,0,'VALORKILO');
		$costoMP_imp=$cantKilos*$valorKilo/$ITEMS;
		echo redondear_entero_puntos($costoMP_imp);
		}	
	  ?>
              </strong></td>
              <td id="detalle2"><h2><?php echo redondear_entero_puntos($manodeObra_imp+$costoTiempoHoraOpcif_imp+$costoTiempoHoraOpgga_imp+$costoTiempoHoraOpggv_imp+$costoTiempoHoraOpggf_imp+$costoMP_imp);  ?></h2></td>
            </tr>
            <tr>
              <td colspan="10" id="subtitulo2">Refilado (Kilos)</td>
            </tr>
            <tr id="tr1">
              <td id="subppal2">Refilados</td>
              <td colspan="2" id="subppal2">Desperdicio Refilado</td>
              <td colspan="2" id="subppal2">Desperdicio Montaje</td>
              <td id="subppal2">Refilado Reales</td>
              <td id="subppal2">Horas Refilado</td>
              <td id="subppal2">Tiempo Perdido (minutos)</td>
              <td id="subppal2">Rollos </td>
              <td id="subppal2">Extruidos (mts)</td>
            </tr>
            <tr>
              <td id="detalle2">&nbsp;</td>
              <td colspan="2" id="detalle2">&nbsp;</td>
              <td colspan="2" id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
            </tr>
            <tr id="tr1">
              <td id="subppal2"><strong>Bolsas Aprox.</strong></td>
              <td colspan="2" id="subppal2"><strong>Mano de Obra</strong></td>
              <td colspan="2" id="subppal2"><strong>CIF</strong></td>
              <td id="subppal2" nowrap><strong>GGA</strong></td>
              <td id="subppal2" nowrap><strong>GGV</strong></td>
              <td id="subppal2" nowrap><strong>GGF</strong></td>
              <td id="subppal2" nowrap><strong>COSTO MP</strong></td>
              <td id="subppal2" nowrap>COSTO TOTAL</td>
            </tr>
            <tr>
              <td id="detalle1">&nbsp;</td>
              <td colspan="2" id="detalle1">&nbsp;</td>
              <td colspan="2" id="detallegrande2">&nbsp;</td>
              <td id="detallegrande2">&nbsp;</td>
              <td id="detallegrande2">&nbsp;</td>
              <td id="detallegrande2">&nbsp;</td>
              <td id="detallegrande2">&nbsp;</td>
              <td id="detallegrande2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="10" id="subtitulo2">Sellado (Kilos)</td>
            </tr>
            <tr id="tr1">
              <td id="subppal2">Sellados</td>
              <td colspan="2" id="subppal2">Desperdicio Sellado</td>
              <td colspan="2" id="subppal2">Desperdicio Montaje</td>
              <td id="subppal2">Unidades Selladas</td>
              <td id="subppal2">Horas Sellado</td>
              <td id="subppal2">Tiempo Perdido (minutos)</td>
              <td id="subppal2">Rollos </td>
              <td id="subppal2">Extruidos (mts)</td>
            </tr>
            <tr>
              <td id="detalle2">&nbsp;</td>
              <td colspan="2" id="detalle2">&nbsp;</td>
              <td colspan="2" id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
            </tr>
            <tr id="tr1">
              <td id="subppal2"><strong>Bolsas Aprox.</strong></td>
              <td colspan="2" id="subppal2"><strong>Mano de Obra</strong></td>
              <td colspan="2" id="subppal2"><strong>CIF</strong></td>
              <td id="subppal2" nowrap><strong>GGA</strong></td>
              <td id="subppal2" nowrap><strong>GGV</strong></td>
              <td id="subppal2" nowrap><strong>GGF</strong></td>
              <td id="subppal2" nowrap><strong>COSTO MP</strong></td>
              <td id="subppal2" nowrap>COSTO TOTAL</td>
            </tr>
            <tr>
              <td id="detalle1">&nbsp;</td>
              <td colspan="2" id="detalle1">&nbsp;</td>
              <td colspan="2" id="detallegrande2">&nbsp;</td>
              <td id="detallegrande2">&nbsp;</td>
              <td id="detallegrande2">&nbsp;</td>
              <td id="detallegrande2">&nbsp;</td>
              <td id="detallegrande2">&nbsp;</td>
              <td id="detallegrande2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="10" id="subtitulo2">Empaque</td>
            </tr>
            <tr>
              <td id="subppal2">Unidades por Paquete</td>
              <td colspan="2" id="subppal2">Desperdicio Refilado</td>
              <td colspan="2" id="subppal2">Desperdicio Montaje</td>
              <td id="subppal2">Refilado Reales</td>
              <td id="subppal2">Horas Refilado</td>
              <td id="subppal2">Tiempo Perdido (minutos)</td>
              <td id="subppal2">Rollos </td>
              <td id="subppal2">Extruidos (mts)</td>
            </tr>
            <tr>
              <td id="detalle2">&nbsp;</td>
              <td colspan="2" id="detalle2">&nbsp;</td>
              <td colspan="2" id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="10" id="subppal2">Resumen Liquidaci&oacute;n OP</td>
            </tr>
            <tr>
              <td id="subppal2">Elementos del Costo</td>
              <td colspan="2" id="subppal2">Extrusi&oacute;n</td>
              <td colspan="2" id="subppal2">Impresi&oacute;n</td>
              <td colspan="2" id="subppal2">Refilado</td>
              <td id="subppal2">Sellado</td>
              <td id="subppal2">Total</td>
              <td id="subppal2">% Part</td>
            </tr>
            <tr>
              <td id="subppal2">Materia prima</td>
              <td colspan="2" id="detalle2">&nbsp;</td>
              <td colspan="2" id="detalle2">&nbsp;</td>
              <td colspan="2" id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
            </tr>
            <tr>
              <td id="subppal2">Mano de obra</td>
              <td colspan="2" id="detalle2">&nbsp;</td>
              <td colspan="2" id="detalle2">&nbsp;</td>
              <td colspan="2" id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
            </tr>
            <tr>
              <td id="subppal2">Cif</td>
              <td colspan="2" id="detalle2">&nbsp;</td>
              <td colspan="2" id="detalle2">&nbsp;</td>
              <td colspan="2" id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
            </tr>
            <tr>
              <td id="subppal2">Gastos</td>
              <td colspan="2" id="detalle2">&nbsp;</td>
              <td colspan="2" id="detalle2">&nbsp;</td>
              <td colspan="2" id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
            </tr>
            <tr>
              <td id="subppal2">Total</td>
              <td colspan="2" id="detalle2">&nbsp;</td>
              <td colspan="2" id="detalle2">&nbsp;</td>
              <td colspan="2" id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="10" id="detalle2">Resumen Liquidaci&oacute;n OP (Con Ineficiencia)</td>
            </tr>
            <tr>
              <td id="subppal2">Costo Kilo + Ineficiencia</td>
              <td colspan="2" id="detalle2">&nbsp;</td>
              <td colspan="2" id="detalle2">&nbsp;</td>
              <td colspan="2" id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
            </tr>
            <tr>
              <td id="subppal2">Costo Unidad + Ineficiencia</td>
              <td colspan="2" id="detalle2">&nbsp;</td>
              <td colspan="2" id="detalle2">&nbsp;</td>
              <td colspan="2" id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
            </tr>
            <tr>
              <td id="subppal2">Comisi&oacute;n (%)</td>
              <td colspan="2" id="detalle2">&nbsp;</td>
              <td colspan="2" id="detalle2">&nbsp;</td>
              <td colspan="2" id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
            </tr>
            <tr>
              <td id="subppal2">Flete (%)</td>
              <td colspan="2" id="detalle2">&nbsp;</td>
              <td colspan="2" id="detalle2">&nbsp;</td>
              <td colspan="2" id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
            </tr>
            <tr>
              <td id="subppal2">Gastos Exportaci&oacute;n (%)</td>
              <td colspan="2" id="detalle2">&nbsp;</td>
              <td colspan="2" id="detalle2">&nbsp;</td>
              <td colspan="2" id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
            </tr>
            <tr>
              <td id="subppal2">Costo Unidad + Comisi&oacute;n + Flete + GE</td>
              <td colspan="2" id="detalle2">&nbsp;</td>
              <td colspan="2" id="detalle2">&nbsp;</td>
              <td colspan="2" id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
            </tr>
            <tr>
              <td id="subppal2">Precio Unidad</td>
              <td colspan="2" id="detalle2">&nbsp;</td>
              <td colspan="2" id="detalle2">&nbsp;</td>
              <td colspan="2" id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
            </tr>
            <tr>
              <td id="subppal2">Margen Bruto (Antes de Impuestos)</td>
              <td colspan="2" id="detalle2">&nbsp;</td>
              <td colspan="2" id="detalle2">&nbsp;</td>
              <td colspan="2" id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>
              <td id="detalle2">&nbsp;</td>    
      
    </tr>
    <tr>
      <td colspan="10" id="subppal">&nbsp;</td>
    </tr>
  </table>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);
mysql_free_result($ref_op);

?>
