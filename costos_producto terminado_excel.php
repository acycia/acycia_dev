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
header('Content-Disposition: attachment; filename="ConsumoProduccion.xls"');
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

//IMPRIME FECHAS SELECCIONADAS
 
$fecha1 = $_GET['fecha1'];
$fecha2 = $_GET['fecha2']; 
 

mysql_select_db($database_conexion1, $conexion1); 
$query_consumo = "SELECT * FROM Tbl_reg_produccion,Tbl_orden_produccion WHERE Tbl_reg_produccion.id_op_rp=Tbl_orden_produccion.id_op AND Tbl_orden_produccion.b_estado_op <> '0' AND Tbl_orden_produccion.b_borrado_op='0' 
AND DATE(Tbl_reg_produccion.fecha_ini_rp) BETWEEN '$fecha1' AND '$fecha2' AND DATE(Tbl_reg_produccion.fecha_fin_rp) BETWEEN '$fecha1' AND '$fecha2' GROUP BY Tbl_reg_produccion.id_op_rp DESC";
$consumo = mysql_query($query_consumo, $conexion1) or die(mysql_error());
$row_consumo = mysql_fetch_assoc($consumo);
$totalRows_consumo = mysql_num_rows($consumo);
 ?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
</head>
<body>
<table id="Exportar_a_Excel" border="1">   
    <tr id="tr1">
      <td colspan="7" id="titulo5">ORDEN DE PRODUCCION</td>
      <td colspan="5" id="titulo5">EXTRUSION</td>
      <td colspan="5" id="titulo5">IMPRESION</td>
      <td colspan="5" id="titulo5">REFILADO</td>
      <td colspan="6" id="titulo5">SELLADO</td>
    </tr>
    <tr id="tr1">
    <td class="estilo7">O.P.</td>
    <td class="estilo7">FECHA</td>
	<td class="estilo7">REF.</td>
	<td class="estilo7">CLIENTE</td>
    <td class="estilo7">PRODUCTO</td>
	<td class="estilo7">KILOS PROGRAMADOS</td>
    <td class="estilo7">BOLSAS PROGRAMADAS</td>
    <td class="estilo7">KILOS EXTRUIDOS</td> 
    <td class="estilo7">KILOS DESPERDICIO EXTRUSION</td>   
    <td class="estilo7">KILOS DESPERDICIO MONTAJE</td>
    <td class="estilo7">HORAS DE EXTRUSION</td>
    <td class="estilo7">TIEMPOS PERDIDOS</td> 
    <td class="estilo7">KILOS IMPRESOS</td> 
    <td class="estilo7">KILOS DESPERDICIO IMPRESOS</td>   
    <td class="estilo7">KILOS DESPERDICIO MONTAJE</td>
    <td class="estilo7">HORAS DE IMPRESOS</td>
    <td class="estilo7">TIEMPOS PERDIDOS</td>  
    <td class="estilo7">KILOS REFILADO</td> 
    <td class="estilo7">KILOS DESPERDICIO REFILADO</td>   
    <td class="estilo7">KILOS DESPERDICIO MONTAJE</td>
    <td class="estilo7">HORAS DE REFILADO</td>
    <td class="estilo7">TIEMPOS PERDIDOS</td>
    <td class="estilo7">KILOS SELLADO</td> 
    <td class="estilo7">KILOS DESPERDICIO SELLADO</td>   
    <td class="estilo7">KILOS DESPERDICIO MONTAJE</td>
    <td class="estilo7">HORAS DE SELLADO</td>
    <td class="estilo7">TIEMPOS PERDIDOS</td>
    <td class="estilo7">BOLSAS</td> 
    </tr>
      <?php do { ?>
      <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF" bordercolor="#ACCFE8">   
      <td class="listado1"><?php echo $row_consumo['id_op'];?></td>
      <td class="listado1"><?php echo quitarHora($row_consumo['fecha_ini_rp']);?></td>
      <td nowrap class="listado1"><?php echo $row_consumo['int_cod_ref_op'];?></td>
      <td class="izquierda4"> 
        <?php 
		$clien=$row_consumo['int_cliente_op']; 	
		$sqlchm="SELECT nombre_c FROM cliente WHERE cliente.id_c='$clien'";
		$resultchm=mysql_query($sqlchm); $numchm=mysql_num_rows($resultchm);
		if ($numchm>='1') { 
		$cliente=mysql_result($resultchm,0,'nombre_c'); 	
		echo $cliente; }
		?></td>
      <td class="izquierda4"><?php 
		$rp=$row_consumo['id_ref_op']; 	
		$sqlref="SELECT tipo_bolsa_ref FROM Tbl_referencia WHERE id_ref=$rp";
		$resultref=mysql_query($sqlref); $numref=mysql_num_rows($resultref);
		if ($numref>='1') { 
		$tipoBolsa=mysql_result($resultref,0,'tipo_bolsa_ref');
        echo $tipoBolsa;
		}else{
		echo "N.A."; 
		}
		?></td>
      <td class="listado1"><?php echo $row_consumo['int_kilos_op'];?></td>
      <td class="listado1"><?php echo redondear_entero_puntos($row_consumo['int_cantidad_op']);?></td>
      <td class="listado1">
        <!--MODULO DE EXTRUSION-->
        <?php 
		//kilos utilizados
        $id_op=$row_consumo['id_op'];
        $sqlexk="SELECT SUM(valor_prod_rp) AS kilosT FROM Tbl_reg_kilo_producido WHERE op_rp='$id_op' AND id_proceso_rkp='1'"; 
        $resultexk=mysql_query($sqlexk); 
        $numexk=mysql_num_rows($resultexk); 
        if($numexk >= '1') 
        { $tkilos_ex=mysql_result($resultexk,0,'kilosT');
        echo $tkilos_ex;
        }else{echo "0";}	
        ?>
        </td>
      <td class="listado1">
        <?php 
	   //desperdicio general diferende de id 29, Desperdicio
	    $id_op=$row_consumo['id_op'];
	    $sqlexd="SELECT SUM(valor_desp_rd) AS kgDespe FROM Tbl_reg_desperdicio WHERE op_rd='$id_op' AND id_rpd_rd <> '29'  AND id_proceso_rd='1'"; 
	    $resultexd=mysql_query($sqlexd); 
	    $numexd=mysql_num_rows($resultexd); 
	    if($numexd >= '1') 
	    { $kilos_exd=mysql_result($resultexd,0,'kgDespe'); echo numeros_format($kilos_exd); }else {echo "0,00";}
	  ?>
      </td>
      <td class="listado1">
        <?php 
	    //solamente de desperdicio de montaje id 29, Montaje
	    $id_op=$row_consumo['id_op'];
	    $sqlexdm="SELECT SUM(valor_desp_rd) AS kgDespmont FROM Tbl_reg_desperdicio WHERE op_rd=$id_op AND id_proceso_rd='1' AND id_rpd_rd='29'"; 
	    $resultexdm=mysql_query($sqlexdm); 
	    $numexdm=mysql_num_rows($resultexdm); 
	    if($numexdm >= '1') 
	    { $kilos_exdm=mysql_result($resultexdm,0,'kgDespmont'); echo numeros_format($kilos_exdm); }else {echo "0,00";}
	  ?>
      </td>   
      <td class="listado1">
        <?php 
        $id_op=$row_consumo['id_op'];
        $sqlext="SELECT SEC_TO_TIME(SUM( TIME_TO_SEC(`total_horas_rp`))) AS horasT FROM Tbl_reg_produccion WHERE `id_op_rp`='$id_op' AND id_proceso_rp='1'"; 
        $resultext=mysql_query($sqlext); 
        $numext=mysql_num_rows($resultext); 
        if($numext >= '1') 
        { $tHoras_ext=mysql_result($resultext,0,'horasT');
        echo $tHoras_ext;
        }else{echo "00:00:00";}		
        ?>
      </td>
        <td class="listado1">
          <?php 
        $id_op=$row_consumo['id_op'];
        $sqlexm="SELECT SUM(valor_tiem_rt) AS horasM FROM Tbl_reg_tiempo WHERE op_rt='$id_op' AND id_proceso_rt='1"; 
        $resultexm=mysql_query($sqlexm); 
        $numexm=mysql_num_rows($resultexm); 
        if($numexm >= '1') 
        { $horasMex=mysql_result($resultexm,0,'horasM');}
          
        $sqlexp="SELECT SUM(valor_prep_rtp) AS horasP FROM Tbl_reg_tiempo_preparacion WHERE op_rtp='$id_op' AND id_proceso_rtp='1'"; 
        $resultexp=mysql_query($sqlexp); 
        $numexp=mysql_num_rows($resultexp); 
        if($numexp >= '1') 
        { $horasPexp=mysql_result($resultexp,0,'horasP');
		$tiempoPerdexp = conversorMinutosHoras($horasMex,$horasPexp);echo $tiempoPerdexp;
        }else{echo "00:00:00";}	  
        ?>
        </td>
    <td class="listado1">
        <?php 
		//kilos IMPRESOS		
        $id_op=$row_consumo['id_op'];
		$sqlimpk="SELECT COUNT(rollo_r) AS rollos, SUM(metro_r) AS metros_imp, SUM(kilos_r) AS kilosT FROM TblImpresionRollo WHERE id_op_r='$id_op'";
        $resultimpk=mysql_query($sqlimpk); 
        $numimpk=mysql_num_rows($resultimpk); 
        if($numimpk >= '1') 
        { $tkilos_imp=mysql_result($resultimpk,0,'kilosT');
        echo redondear_decimal($tkilos_imp);
        }else{echo "0";}	
        ?>
        </td>
      <td class="listado1">
        <?php 
	   //desperdicio general diferende de id 55
	    $id_op=$row_consumo['id_op'];
	    $sqlimpd="SELECT SUM(valor_desp_rd) AS kgDespe FROM Tbl_reg_desperdicio WHERE op_rd='$id_op' AND id_rpd_rd <> '55'  AND id_proceso_rd='2'"; 
	    $resultimpd=mysql_query($sqlimpd); 
	    $numimpd=mysql_num_rows($resultimpd); 
	    if($numimpd >= '1') 
	    { $kilos_impd=mysql_result($resultimpd,0,'kgDespe'); echo numeros_format($kilos_impd); }else {echo "0,00";}
	  ?>
      </td>
      <td class="listado1">
        <?php 
	    //solamente de desperdicio de montaje id 55
	    $id_op=$row_consumo['id_op'];
	    $sqlimpdm="SELECT SUM(valor_desp_rd) AS kgmont FROM Tbl_reg_desperdicio WHERE op_rd=$id_op  AND id_rpd_rd='55' AND id_proceso_rd='2'"; 
	    $resultimpdm=mysql_query($sqlimpdm); 
	    $numimpdm=mysql_num_rows($resultimpdm); 
	    if($numimpdm >= '1') 
	    { $kilos_impdm=mysql_result($resultimpdm,0,'kgDespmont'); echo numeros_format($kilos_impdm); }else {echo "0,00";}
	  ?>
      </td>   
      <td class="listado1">
        <?php 
        $id_op=$row_consumo['id_op'];
        $sqlimpt="SELECT SEC_TO_TIME(SUM( TIME_TO_SEC(`total_horas_rp`)))  AS horasT FROM Tbl_reg_produccion WHERE id_op_rp='$id_op' AND id_proceso_rp='2'"; 
        $resultimpt=mysql_query($sqlimpt); 
        $numimpt=mysql_num_rows($resultimpt); 
        if($numimpt >= '1') 
        { $tHoras_impt=mysql_result($resultimpt,0,'horasT');
        echo $tHoras_impt;
        }else{echo "00:00:00";}		
        ?>
      </td>
     <td class="listado1">
       <?php 
        $id_op=$row_consumo['id_op'];
        $sqlimpm="SELECT SUM(valor_tiem_rt) AS horasM FROM Tbl_reg_tiempo WHERE op_rt='$id_op' AND id_proceso_rt='2'"; 
        $resultimpm=mysql_query($sqlimpm); 
        $numimpm=mysql_num_rows($resultimpm); 
        if($numimpm >= '1') 
        { $horasMimp=mysql_result($resultimpm,0,'horasM');}
          
        $sqlimpp="SELECT SUM(valor_prep_rtp) AS horasP FROM Tbl_reg_tiempo_preparacion WHERE op_rtp='$id_op' AND id_proceso_rtp='2'"; 
        $resultimpp=mysql_query($sqlimpp); 
        $numimpp=mysql_num_rows($resultimpp); 
        if($numimpp >= '1') 
        { $horasPimpp=mysql_result($resultimpp,0,'horasP');
		$tiempoPerdimpp = conversorMinutosHoras($horasMimp,$horasPimpp);echo $tiempoPerdimpp;
        }else{echo "00:00:00";}	  
        ?>
     </td>
    <td class="listado1">
        <!--MODULO DE REFILADO-->
        <?php 
		//kilos IMPRESOS		
        $id_op=$row_consumo['id_op'];
		$sqlrefk="SELECT COUNT(rollo_r) AS rollos, SUM(metro_r) AS metros_ref, SUM(kilos_r) AS kilosT FROM TblRefiladoRollo WHERE id_op_r='$id_op'";
        $resultrefk=mysql_query($sqlrefk); 
        $numrefk=mysql_num_rows($resultrefk); 
        if($numrefk >= '1') 
        { $tkilos_ref=mysql_result($resultrefk,0,'kilosT');
        echo redondear_decimal($tkilos_ref);
        }else{echo "0";}	
        ?>
        </td>
      <td class="listado1">
        <?php 
	   //desperdicio general diferende de id 55
	    $id_op=$row_consumo['id_op'];
	    $sqlrefd="SELECT SUM(valor_desp_rd) AS kgDespe FROM Tbl_reg_desperdicio WHERE op_rd='$id_op' AND id_rpd_rd <> '55'  AND id_proceso_rd='3'"; 
	    $resultrefd=mysql_query($sqlrefd); 
	    $numrefd=mysql_num_rows($resultrefd); 
	    if($numrefd >= '1') 
	    { $kilos_refd=mysql_result($resultrefd,0,'kgDespe'); echo numeros_format($kilos_refd); }else {echo "0,00";}
	  ?>
      </td>
      <td class="listado1">
        <?php 
	    //solamente de desperdicio de montaje id 55
	    $id_op=$row_consumo['id_op'];
	    $sqlimpdm="SELECT SUM(valor_desp_rd) AS kgmont FROM Tbl_reg_desperdicio WHERE op_rd=$id_op  AND id_rpd_rd='55' AND id_proceso_rd='3'"; 
	    $resultimpdm=mysql_query($sqlimpdm); 
	    $numimpdm=mysql_num_rows($resultimpdm); 
	    if($numimpdm >= '1') 
	    { $kilos_impdm=mysql_result($resultimpdm,0,'kgDespmont'); echo numeros_format($kilos_impdm); }else {echo "0,00";}
	  ?>
      </td>   
      <td class="listado1">
        <?php 
        $id_op=$row_consumo['id_op'];
        $sqlimpt="SELECT SEC_TO_TIME(SUM( TIME_TO_SEC(`total_horas_rp`)))  AS horasT FROM Tbl_reg_produccion WHERE id_op_rp='$id_op' AND id_proceso_rp='3'"; 
        $resultimpt=mysql_query($sqlimpt); 
        $numimpt=mysql_num_rows($resultimpt); 
        if($numimpt >= '1') 
        { $tHoras_impt=mysql_result($resultimpt,0,'horasT');
        echo $tHoras_impt;
        }else{echo "00:00:00";}		
        ?>
      </td>
     <td class="listado1">
       <?php 
        $id_op=$row_consumo['id_op'];
        $sqlrefm="SELECT SUM(valor_tiem_rt) AS horasM FROM Tbl_reg_tiempo WHERE op_rt='$id_op' AND id_proceso_rt='3'"; 
        $resultrefm=mysql_query($sqlrefm); 
        $numrefm=mysql_num_rows($resultrefm); 
        if($numrefm >= '1') 
        { $horasMref=mysql_result($resultrefm,0,'horasM');}
          
        $sqlrefp="SELECT SUM(valor_prep_rtp) AS horasP FROM Tbl_reg_tiempo_preparacion WHERE op_rtp='$id_op' AND id_proceso_rtp='3'"; 
        $resultrefp=mysql_query($sqlrefp); 
        $numrefp=mysql_num_rows($resultrefp); 
        if($numrefp >= '1') 
        { $horasPrefp=mysql_result($resultrefp,0,'horasP');
		$tiempoPerdrefp = conversorMinutosHoras($horasMref,$horasPrefp);echo $tiempoPerdrefp;
        }else{echo "00:00:00";}	  
        ?>
     </td>    
    <td class="listado1"> 
        <?php 
		//kilos IMPRESOS		
        $id_op=$row_consumo['id_op'];
		$sqlsellk="SELECT SUM(bolsa_rp) AS BOLSAS, SUM(int_kilos_prod_rp) AS kilosT FROM Tbl_reg_produccion WHERE id_op_rp='$id_op' AND id_proceso_rp='4'";
        $resultsellk=mysql_query($sqlsellk); 
        $numsellk=mysql_num_rows($resultsellk); 
        if($numsellk >= '1') 
        { $tkilos_sell=mysql_result($resultsellk,0,'kilosT');
		$bolsas=mysql_result($resultsellk,0,'BOLSAS');
        echo redondear_decimal($tkilos_sell);
        }else{echo "0";}	
        ?> </td>
      <td class="listado1">
        <?php 
	  //desperdicio general diferende de id 105, Desperdicio
	  $id_op=$row_consumo['id_op'];
	  $sqldesp="SELECT SUM(valor_desp_rd) AS kgDespe FROM Tbl_reg_desperdicio WHERE op_rd='$id_op' AND id_rpd_rd <> '105'  AND id_proceso_rd='4'"; 
	  $resultdesp=mysql_query($sqldesp); 
	  $numdesp=mysql_num_rows($resultdesp); 
	  if($numdesp >= '1') 
	  { $kilos_desp_sell=mysql_result($resultdesp,0,'kgDespe'); echo numeros_format($kilos_desp_sell); }else {echo "0,00";}
	  ?>
      </td>
      <td class="listado1">
        <?php 
	  //solamente de desperdicio de montaje id 105, Montaje
	  $id_op=$row_consumo['id_op'];
	  $sqlmont="SELECT SUM(valor_desp_rd) AS kgmont FROM Tbl_reg_desperdicio WHERE op_rd=$id_op  AND id_rpd_rd='105' AND id_proceso_rd='4'"; 
	  $resultmont=mysql_query($sqlmont); 
	  $nummont=mysql_num_rows($resultmont); 
	  if($nummont >= '1') 
	  { $kgmontSell=mysql_result($resultmont,0,'kgmont'); echo numeros_format($kgmontSell); }else {echo "0,00";}
	  ?>
      </td>   
      <td class="listado1">
        <?php 	 
	  $id_op=$row_consumo['id_op'];
	  $sqlsell="SELECT SEC_TO_TIME(SUM( TIME_TO_SEC(`total_horas_rp`)))  AS horasT FROM Tbl_reg_produccion WHERE id_op_rp='$id_op' AND id_proceso_rp='4'"; 
	  $resultsell=mysql_query($sqlsell); 
	  $numsell=mysql_num_rows($resultsell); 
	  if($numsell >= '1') 
	  { $tHoras_sell=mysql_result($resultsell,0,'horasT');
	   echo $tHoras_sell;
	        $horasM_sellDec=horadecimal($tHoras_sell);//hora adecimal para operar
	  }else{echo "0";}
?>
      </td>
      <td class="listado1">
        <?php 
	  $id_op=$row_consumo['id_op'];
	  $sqlsellpm="SELECT SUM(valor_tiem_rt) AS horasM FROM Tbl_reg_tiempo WHERE op_rt='$id_op' AND id_proceso_rt='4'"; 
	  $resultsellpm=mysql_query($sqlsellpm); 
	  $numsellpm=mysql_num_rows($resultsellpm); 
	  if($numsellpm >= '1') 
	  { $horasMsell=mysql_result($resultsellpm,0,'horasM');
	  }else{echo "0";}
	 
 
	  $id_op=$row_consumo['id_op'];
	  $sqlsellp="SELECT SUM(valor_prep_rtp) AS horasP FROM Tbl_reg_tiempo_preparacion WHERE op_rtp='$id_op' AND id_proceso_rtp='4'"; 
	  $resultsellp=mysql_query($sqlsellp); 
	  $numsellp=mysql_num_rows($resultsellp); 
	  if($numsellp >= '1') 
	  { $horasP_sell=mysql_result($resultsellp,0,'horasP'); $totalTiempo_sell=$horasP_sell+$horasM_sell;echo $totalTiempo_sell;}
	  ?>
      </td>
      <td class="listado1"><?php echo redondear_entero_puntos($bolsas);?></td>    
    </tr>
    <?php } while ($row_consumo = mysql_fetch_assoc($consumo)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($costos);

?>