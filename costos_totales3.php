<?php require_once('Connections/conexion1.php'); ?>
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
include('funciones/funciones_php.php');//distintas funciones
//include('costos_totales_funcion.php');//formulas de costos 
//FIN	
/*$fecha2= date("Y-m-d");
$fecha1=restaMes($fecha2);*/

$fecha1= $_POST['fecha_ini'];
$fecha2= $_POST['fecha_fin'];

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);  

mysql_select_db($database_conexion1, $conexion1); 
$query_consumo = "SELECT * FROM Tbl_orden_produccion WHERE DATE(fecha_registro_op) BETWEEN '$fecha1'
AND '$fecha2'ORDER BY fecha_registro_op DESC";
$consumo = mysql_query($query_consumo, $conexion1) or die(mysql_error());
$row_consumo = mysql_fetch_assoc($consumo);
$totalRows_consumo = mysql_num_rows($consumo);

?><html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link rel="StyleSheet" href="css/formato.css" type="text/css">
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/listado.js"></script>
</head>
<!--<body style="overflow-x:hidden"> <---Borra barra horizontal 
<body style="overflow-y:hidden"> <----Borra barra vertical 
<body scroll=no> <- Borra barras-->
<body style="overflow-y:hidden">
<!--<div style="overflow: hidden; width:auto; height: 90px">-->
<form action="costos_totales3.php" method="POST" name="form1">
<table id="tabla3">
  <tr id="tr1">
<td id="titulo2" colspan="24">FECHA INICIAL:
<input name="fecha_ini" type="date" id="fecha_ini" required  min="2000-01-02" size="10" value="<?php echo $_POST['fecha_ini'];?>"/>
FECHA FINAL:
<input name="fecha_fin" type="date" id="fecha_fin" min="2000-01-02" size="10" required value="<?php echo $_POST['fecha_fin'];?>"/>
<input type="submit" name="submit" id="submit" value="Consultar" />
<a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR" title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
</tr>
<tr id="tr1">
    <td id="titulo2">&nbsp;</td>
    <td id="titulo2">&nbsp;</td>
    <td id="titulo2">&nbsp;</td>
    <td id="titulo2">&nbsp;</td>
    <td id="titulo2">&nbsp;</td>
    <td id="titulo2">&nbsp;</td>
    <td colspan="6" id="titulo2">COSTOS</td>
    <td colspan="7" id="titulo2">GASTOS</td>
    <td rowspan="3" id="titulo2">TOTAL</td>
    </tr>
  <tr>
<!--     <td class="Estilo1">REF.</td>
    <td class="Estilo1">O.P</td>
	<td class="Estilo1">FECHA</td>
	<td class="Estilo1">CLIENTE</td>
    <td class="Estilo1">TIPO</td>
	<td class="Estilo1">AREA</td>
    <td class="Estilo1">PRODU. REAL</td>
    <td class="Estilo1">NIVEL. PROC.</td>
    <td class="Estilo1">CIF Y GGA</td>
    <td class="Estilo1">M.P</td>
    <td class="Estilo1">MANO DE OBRA</td>
    <td class="Estilo1">COSTO BRUTO</td>
    <td class="Estilo1">COMI. VENTA</td>
    <td class="Estilo1">FLETES</td>
    <td class="Estilo1">COSTO. EXPORT.</td>
    <td class="Estilo1">COSTO. NETO</td>
    <td class="Estilo1">P.V</td>
    <td class="Estilo1">UTILIDA.</td>
    <td class="Estilo1">UTILIDA. %</td>
    <td class="Estilo1">VERIF. GGA</td>
    <td class="Estilo1">VERIF. MP</td>
    <td class="Estilo1">VERIF. MOD</td>
    <td class="Estilo1">VERIF. FLETES</td>
    <td class="Estilo1">KGS DESP.</td> --> 
     
    <td rowspan="2" class="izquierda1">CODIGOOOOO</td>
    <td rowspan="2" class="izquierda1">ORDEN .PRODUCC.</td>
	<td rowspan="2" class="izquierda1">FECHAAAAAAA</td>
	<td rowspan="2" class="izquierda3">CLIENTEEEEEEEEEEEEEEEEEEEEEE</td>
    <td rowspan="2" class="izquierda1">DESCRIPCIONN</td>
<!--	<td class="menor1">AREA</td>
--> <td rowspan="2" class="menor1">MODDDDDD.</td>
    <td colspan="2" class="izquierda1">CIF</td>
    <td class="menor1">TOTAL</td>
    <td colspan="2" class="menor1">ADMINISTRACION</td>
    <td colspan="2" class="menor1">VENTAS</td>
    <td colspan="2" class="menor1">FINANCIEROS</td>
    <td class="izquierda1">TOTAL</td>        
  </tr>
  <tr>
    <td class="izquierda1">FIJOOOOOO</td>
    <td class="menor1">VARIABLE</td>
    <td class="menor1">COSTOSSSS</td>
    <td class="menor1">FIJOOOOO</td>
    <td class="menor1">VARIABLEEE</td>
    <td class="menor1">FIJOOOOO </td>
    <td class="menor1">VARIABLEEE</td>
    <td class="menor1">FIJOOOOO</td>
    <td class="menor1">VARIABLEEE</td>
    <td class="izquierda1">GASTOS</td>
  </tr>
</table>
</form>
</div>
<div style="overflow:auto; width:auto; height:600px; align:center;">
<table id="tabla3">
  <?php do { ?>
    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF" bordercolor="#ACCFE8">   
      <td class="listado1"><?php echo $row_consumo['int_cod_ref_op'];?></td>
      <td class="listado1"><?php echo $row_consumo['id_op'];?></td>
      <!--<td nowrap class="listado1"><?php echo $row_consumo['fecha_registro_op'];?></td>
      <td class="listado3"> 
        <?php 
		$nit=$row_consumo['str_nit_op']; 	
		$sqlchm="SELECT nombre_c FROM cliente WHERE nit_c='$nit'";
		$resultchm=mysql_query($sqlchm); $numchm=mysql_num_rows($resultchm);
		if ($numchm>='1') { 
		$cliente=mysql_result($resultchm,0,'nombre_c'); 	
		echo $cliente; }
		?></td>
      <td class="listado1">
	    <?php 
		$rp=$row_consumo['id_ref_op']; 	
		$sqlref="SELECT tipo_bolsa_ref FROM Tbl_referencia WHERE id_ref=$rp";
		$resultref=mysql_query($sqlref); $numref=mysql_num_rows($resultref);
		if ($numref>='1') { 
		$tipoBolsa=mysql_result($resultref,0,'tipo_bolsa_ref'); 	
		echo $tipoBolsa; }
		?></td>
      <td class="listado1">
        <?php 
		//BOLSAR FABRICADAS TERMINADAS
        $op=$row_consumo['id_op']; 
		$sqlimpk="SELECT SUM(bolsa_rp) AS bolsasT FROM Tbl_reg_produccion WHERE id_op_rp='$op' AND id_proceso_rp='3'";
        $resultimpk=mysql_query($sqlimpk); 
        $numimpk=mysql_num_rows($resultimpk); 
        if($numimpk >= '1') 
        { $tbolsas=mysql_result($resultimpk,0,'bolsasT');
        echo $tbolsas;
        }else{echo "0";}	
        ?></td>
      <td class="listado1"><?php 
		//kilos utilizados
        $op_rp=$row_consumo['id_op'];
        $sqlexk="SELECT SUM(valor_prod_rp) AS kilosT FROM Tbl_reg_kilo_producido WHERE op_rp='$op_rp' AND id_proceso_rkp='1'"; 
        $resultexk=mysql_query($sqlexk); 
        $numexk=mysql_num_rows($resultexk); 
        if($numexk >= '1') 
        { $tkilos_ex=mysql_result($resultexk,0,'kilosT'); //{echo "00:00:00";
        echo $tkilos_ex;
        }else{echo "0,00";}	
        ?></td>
      <td class="listado1">
		<?php 
        $op_rp=$row_consumo['id_op'];
        $fecha=$row_consumo['fecha_registro_op'];
        $sqlex="SELECT TIMEDIFF(fecha_fin_rp,fecha_ini_rp) AS horasT FROM Tbl_reg_produccion WHERE b_borrado_rp='0' AND id_op_rp='$op_rp' AND DATE(fecha_ini_rp)='$fecha'"; 
        $resultex=mysql_query($sqlex); 
        $numex=mysql_num_rows($resultex); 
        if($numex >= '1') 
        { $tHoras_ex=mysql_result($resultex,0,'horasT'); //echo $BolsaxHora=$tHoras_ex; }if ($BolsaxHora==NULL) {echo "00:00:00";
        }	
        ?>
        <?php 
        $op_rp=$row_consumo['id_op_rp'];
        $fecha=$row_consumo['fecha_registro_op'];
        $sqlexm="SELECT SUM(valor_tiem_rt) AS horasM FROM Tbl_reg_tiempo WHERE op_rt='$op_rp' AND fecha_rt='$fecha'"; 
        $resultexm=mysql_query($sqlexm); 
        $numexm=mysql_num_rows($resultexm); 
        if($numexm >= '1') 
        { $horasM=mysql_result($resultexm,0,'horasM');}
          
        $sqlexp="SELECT SUM(valor_prep_rtp) AS horasP FROM Tbl_reg_tiempo_preparacion WHERE op_rtp='$op_rp' AND fecha_rtp='$fecha'"; 
        $resultexp=mysql_query($sqlexp); 
        $numexp=mysql_num_rows($resultexp); 
        if($numexp >= '1') 
        { $horasP=mysql_result($resultexp,0,'horasP');}  
        ?>    
        <?php //costo M.O.D Neto
        $TiempoDesperdicio=($horasM+$horasP);
        $Total_bolsaSellado=$bolsaSelladas;
        //Tiempo en minutos
        $TiempoMinutos= hoursToSecods($tHoras_ex);//total tiempo y pasado a minutos 
        $sumaTiempos=($TiempoMinutos+$TiempoDesperdicio);
        $equiHoras=($sumaTiempos/60);//Equivalente de minutos en horas
        //echo redondear_entero($equiHoras);
        $BolsaxHora=redondear_decimal($Total_bolsaSellado/$equiHoras);//bolsas por hora
        $costoxPxLxT =FormulaCostos($fecha1, $fecha2);
    
        $MO = numeros_format($costoxPxLxT/$BolsaxHora);
        echo $MO;
        ?>
        </td>
          <td class="listado1"><?php
            $codref_oc=$row_consumo['int_cod_ref_op'];
            $sqloc = "SELECT int_precio_io,int_comision_io FROM Tbl_items_ordenc WHERE int_cod_ref_io = '$codref_oc' ORDER BY fecha_despacho_io DESC LIMIT 1";
            $resultoc = mysql_query($sqloc);
            $numoc = mysql_num_rows($resultoc);	  
            if($numoc >= '1') 
            { $PV=mysql_result($resultoc,0,'int_precio_io');
              $COMISION=mysql_result($resultoc,0,'int_comision_io');
             // if ($COMISION==''){$COMISION='0';}else{echo $COMISION;}
            }
            ?>
          <?php $costBruto=($MO+$MateriaP+$cifyGga); echo redondear_decimal($costBruto);?></td>
          <td class="listado1"><?php $comiVnta=($PV*$COMISION); echo $comiVnta;?></td>
          <td class="listado1"><?php 
            $fle=$row_consumo['id_ref_op'];
            $sqlfle="SELECT peso_millar_ref FROM Tbl_referencia WHERE id_ref=$fle"; 
            $resultfle=mysql_query($sqlfle); 
            $numfle=mysql_num_rows($resultfle); 
            if($numfle >= '1') 
            { $pesoM=mysql_result($resultfle,0,'peso_millar_ref'); 
             $estandar=23000; $millar=1000;//23 es el peso estandar por caja y mil el millar
             $FLETES=$estandar/($pesoM*$millar);
            echo redondear_decimal($FLETES);
            }
          ?>
          </td>
          <td class="listado1">
            <?php
            $idref_ce=$row_consumo['id_ref_op'];
            $sqlexp = "SELECT SUM(cantidad_det) AS cant, SUM(valor_total_det) AS valor_T FROM TblCostoExportacionDetalle WHERE TblCostoExportacionDetalle.id_ref_det = '$idref_ce'";
            $resultexp = mysql_query($sqlexp);
            $numexp = mysql_num_rows($resultexp);	  
            if($numexp >= '1') 
            { $cantidad_exp=mysql_result($resultexp,0,'cant');
              $valor_exp=mysql_result($resultexp,0,'valor_T');
              $CostoExport=($valor_exp/$cantidad_exp);
              echo redondear_decimal($CostoExport);
            }
            ?>
          </td>
          <td class="listado1"><?php $COSTNETO =($costBruto+$comiVnta+$FLETES);echo redondear_decimal($COSTNETO);?></td>
          <td class="listado1"><?php echo $PV;?></td>
          <td class="listado1"><?php $UTIL=($PV-$COSTNETO);echo redondear_decimal($UTIL);?></td>
          <td class="listado1"><?php $UTILPORC=($UTIL/$PV);echo redondear_entero($UTILPORC);?></td>
          <td class="listado1"><?php $VERIFGGA=($bolsaSelladas*$cifyGga);echo numeros_format($VERIFGGA);?></td>
          <td class="listado1"><?php $VERIFMP=($bolsaSelladas*$MateriaP);echo numeros_format($VERIFMP);?></td>
          <td class="listado1"><?php $VERIFMO=($bolsaSelladas*$MO);echo numeros_format($VERIFMO);?></td>
          <td class="listado1"><?php $VERIFFLE=($bolsaSelladas*$FLETES);echo numeros_format($VERIFFLE);?></td>
          <td class="listado1"><?php echo $row_consumo['int_kilos_desp_rp'];?></td>-->
    </tr>
    <?php } while ($row_consumo = mysql_fetch_assoc($consumo)); ?>
</table></div>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($consumo);
?>
