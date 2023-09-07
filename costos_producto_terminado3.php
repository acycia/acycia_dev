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
include('funciones/funciones_costos.php');//distintas funciones
//FIN		
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
 
$fecha1 = "2015-11-01";//first_year_month();
$fecha2 = fecha(); 
 
$query_consumo = "SELECT * FROM Tbl_reg_produccion,Tbl_orden_produccion WHERE Tbl_reg_produccion.id_op_rp=Tbl_orden_produccion.id_op AND Tbl_orden_produccion.b_estado_op > '0' AND Tbl_orden_produccion.b_borrado_op = '0' 
AND DATE(Tbl_reg_produccion.fecha_ini_rp) BETWEEN '$fecha1' AND '$fecha2' AND DATE(Tbl_reg_produccion.fecha_fin_rp) BETWEEN '$fecha1' AND '$fecha2' GROUP BY Tbl_reg_produccion.id_op_rp DESC";
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
<div align="center"> 
<!--<div style="overflow: hidden; width:auto; height: 90px">-->
<!--<div style="height:650px;width: auto;overflow:scroll;"> --><!--style="height:650px;width: auto;overflow:scroll;"--><!--align:left;-->
<table id="tabla3">      
      <?php do { ?>
      <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF" bordercolor="#ACCFE8">   
      <td class="centrado6"><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_consumo['id_op'];?></a></td>
     <td class="centrado6"><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="new" style="text-decoration:none; color:#000000"><?php 
	    $idop=$row_consumo['id_op'];
		$fechaop = "SELECT COUNT(DISTINCT rollo_r) AS rollos, LAST_DAY(fechaF_r) AS FECHA, SUM(metro_r) AS metros,SUM(kilos_r) AS kilos FROM TblExtruderRollo";
		 echo   $fecha_op = fechaGeneral($idop,$fechaop);	 
	 
	// echo quitarHora($row_consumo['fecha_ini_rp']);?></a></td><!-- 
      <td nowrap class="centrado6"><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_consumo['int_cod_ref_op'];?></a></td>
      <td class="izquierda4"> <a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="new" style="text-decoration:none; color:#000000">
        <?php 
		$clien=$row_consumo['int_cliente_op']; 	
		$sqlchm="SELECT nombre_c FROM cliente WHERE cliente.id_c='$clien'";
		$resultchm=mysql_query($sqlchm); $numchm=mysql_num_rows($resultchm);
		if ($numchm>='1') { 
		$cliente=mysql_result($resultchm,0,'nombre_c'); 	
		echo $cliente; }
		?></a></td>
      <td class="izquierda4"><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="new" style="text-decoration:none; color:#000000"><?php 
		$rp=$row_consumo['id_ref_op']; 	
		$sqlref="SELECT tipo_bolsa_ref FROM Tbl_referencia WHERE id_ref=$rp";
		$resultref=mysql_query($sqlref); $numref=mysql_num_rows($resultref);
		if ($numref>='1') { 
		$tipoBolsa=mysql_result($resultref,0,'tipo_bolsa_ref');
        echo $tipoBolsa;
		}else{
		echo "N.A."; 
		}
		?></a></td>
      <td class="centrado6"><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_consumo['int_kilos_op'];?></a></td>
      <td class="centrado6"><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo redondear_entero_puntos($row_consumo['int_cantidad_op']);?></a></td>-->
      <td class="centrado6"><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="new" style="text-decoration:none; color:#000000">
        <!--MODULO DE EXTRUSION-->
        <?php 
	   //desperdicio general diferende de id 29, Desperdicio
	    $id_op=$row_consumo['id_op'];
	    $sqlexd="SELECT SUM(valor_desp_rd) AS kgDespe FROM Tbl_reg_desperdicio WHERE op_rd='$id_op' AND id_proceso_rd='1'"; 
	    $resultexd=mysql_query($sqlexd); 
	    $numexd=mysql_num_rows($resultexd); 
	    if($numexd >= '1') 
	    { $kilos_exd=mysql_result($resultexd,0,'kgDespe');} 
 
		//kilos utilizados
        $id_op=$row_consumo['id_op'];
        $sqlexk="SELECT SUM(valor_prod_rp) AS kilosT FROM Tbl_reg_kilo_producido WHERE op_rp='$id_op' AND id_proceso_rkp='1'"; 
        $resultexk=mysql_query($sqlexk); 
        $numexk=mysql_num_rows($resultexk); 
        if($numexk >= '1') 
        { $tkilos_ex=mysql_result($resultexk,0,'kilosT');
        echo $tkilos_ex+$kilos_exd;
        }else{echo "0";}	
        ?></a></td>
      <td class="centrado6"><?php echo numeros_format($kilos_exd);?></td>  
      <td class="centrado6"><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="new" style="text-decoration:none; color:#000000">
		<?php 
        $id_op=$row_consumo['id_op']; 
	  $sqlex="SELECT SEC_TO_TIME(SUM( TIME_TO_SEC(`total_horas_rp`))) AS horasT FROM Tbl_reg_produccion WHERE `id_op_rp`='$id_op' AND id_proceso_rp = '1'"; 
	  $resultex=mysql_query($sqlex); 
	  $numex=mysql_num_rows($resultex); 
	  if($numex >= '1') 
	  { $tHoras_ex=mysql_result($resultex,0,'horasT');
	    echo $tHoras_ex;
	    $horasM_exDec=horadecimalUna($tHoras_ex);//hora adecimal para operar
	  }else{echo "0";}	
        ?>
       </a> </td>
        <td class="centrado6"><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="new" style="text-decoration:none; color:#000000">
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
        }else{echo "0";}	  
        ?> </a>   
    </td>
    <td class="centrado6"><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="new" style="text-decoration:none; color:#000000">
        <!--MODULO DE IMPRESION-->
        <?php 
	    $sqlimpd="SELECT SUM(valor_desp_rd) AS kgDespe FROM Tbl_reg_desperdicio WHERE op_rd='$id_op' AND id_proceso_rd='2'"; 
	    $resultimpd=mysql_query($sqlimpd); 
	    $numimpd=mysql_num_rows($resultimpd); 
	    if($numimpd >= '1') 
	    { $kilos_impd=mysql_result($resultimpd,0,'kgDespe');} 		
		
		//kilos IMPRESOS		
        $id_op=$row_consumo['id_op'];
		$sqlimpk="SELECT COUNT(rollo_r) AS rollos, SUM(metro_r) AS metros_imp, SUM(kilos_r) AS kilosT FROM TblImpresionRollo WHERE id_op_r='$id_op'";
        $resultimpk=mysql_query($sqlimpk); 
        $numimpk=mysql_num_rows($resultimpk); 
        if($numimpk >= '1') 
        { $tkilos_imp=mysql_result($resultimpk,0,'kilosT');
        echo redondear_decimal($tkilos_imp+$kilos_impd);
        }else{echo "0";}	
        ?></a>
      </td>
      <td class="centrado6"><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="new" style="text-decoration:none; color:#000000"><?php 
	   echo numeros_format($kilos_impd);
 	  ?></a>
      </td>   
      <td class="centrado6"><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="new" style="text-decoration:none; color:#000000">
		<?php 
        $id_op=$row_consumo['id_op'];
        $sqlimpt="SELECT SEC_TO_TIME(SUM( TIME_TO_SEC(`total_horas_rp`)))  AS horasT FROM Tbl_reg_produccion WHERE id_op_rp='$id_op' AND id_proceso_rp='2'"; 
        $resultimpt=mysql_query($sqlimpt); 
        $numimpt=mysql_num_rows($resultimpt); 
        if($numimpt >= '1') 
        { $tHoras_impt=mysql_result($resultimpt,0,'horasT');
        echo $tHoras_impt;
		$horasM_impDec=horadecimalUna($tHoras_impt);//hora adecimal para operar
        }else{echo "0";}	
         ?></a>
     </td>
     <td class="centrado6">
     <a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="new" style="text-decoration:none; color:#000000">
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
        }else{echo "0";}	  
        ?></a>      
    </td>
    <td class="centrado6"><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="new" style="text-decoration:none; color:#000000">
        <!--MODULO DE REFILADO--></a>
      <a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="new" style="text-decoration:none; color:#000000">
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
      </a></td>
      <td class="centrado6"><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="new" style="text-decoration:none; color:#000000">
        <?php 
	   //desperdicio general diferende de id 55
	    $id_op=$row_consumo['id_op'];
	    $sqlrefd="SELECT SUM(valor_desp_rd) AS kgDespe FROM Tbl_reg_desperdicio WHERE op_rd='$id_op' AND id_rpd_rd <> '55'  AND id_proceso_rd='3'"; 
	    $resultrefd=mysql_query($sqlrefd); 
	    $numrefd=mysql_num_rows($resultrefd); 
	    if($numrefd >= '1') 
	    { $kilos_refd=mysql_result($resultrefd,0,'kgDespe'); echo numeros_format($kilos_refd); }else {echo "0,00";}
	  ?>
      </a></td>    
      <td class="centrado6"><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="new" style="text-decoration:none; color:#000000">
        <?php 
        $id_op=$row_consumo['id_op'];
        $sqlimpt="SELECT SEC_TO_TIME(SUM( TIME_TO_SEC(`total_horas_rp`)))  AS horasT FROM Tbl_reg_produccion WHERE id_op_rp='$id_op' AND id_proceso_rp='3'"; 
        $resultimpt=mysql_query($sqlimpt); 
        $numimpt=mysql_num_rows($resultimpt); 
        if($numimpt >= '1') 
        { $tHoras_impt=mysql_result($resultimpt,0,'horasT');
        echo $tHoras_impt;
        }else{echo "0";}		
        ?>
      </a></td>
     <td class="centrado6"><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="new" style="text-decoration:none; color:#000000">
       <?php 
        $id_op=$row_consumo['id_op'];
        $sqlrefm="SELECT SUM(valor_tiem_rt) AS horasM FROM Tbl_reg_tiempo WHERE op_rt='$id_op' AND id_proceso_rt='3'"; 
        $resultrefm=mysql_query($sqlrefm); 
        $numrefm=mysql_num_rows($resultrefm); 
        if($numrefm >= '1') 
        { $horasM_ref=mysql_result($resultrefm,0,'horasM');}
          
        $sqlrefp="SELECT SUM(valor_prep_rtp) AS horasP FROM Tbl_reg_tiempo_preparacion WHERE op_rtp='$id_op' AND id_proceso_rtp='3'"; 
        $resultrefp=mysql_query($sqlrefp); 
        $numrefp=mysql_num_rows($resultrefp); 
        if($numrefp >= '1') 
        { $horasP_ref=mysql_result($resultrefp,0,'horasP');
		$tiempoPerdrefp = conversorMinutosHoras($horasM_ref,$horasP_ref);echo $tiempoPerdrefp;
        }else{echo "0";}	  
        ?>
     </a></td>    
    <td class="centrado6"><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="new" style="text-decoration:none; color:#000000">
        <!--MODULO DE SELLADO--></a><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="new" style="text-decoration:none; color:#000000">
        <?php
	  $sqldesp="SELECT SUM(valor_desp_rd) AS kgDespe FROM Tbl_reg_desperdicio WHERE op_rd='$id_op' AND id_proceso_rd='4'"; 
	  $resultdesp=mysql_query($sqldesp); 
	  $numdesp=mysql_num_rows($resultdesp); 
	  if($numdesp >= '1') 
	  { $kilos_desp_sell=mysql_result($resultdesp,0,'kgDespe');	}
 		//kilos SELLADOS		
        $id_op=$row_consumo['id_op'];
		$sqlsellk="SELECT SUM(bolsa_rp) AS BOLSAS, SUM(int_kilos_prod_rp) AS kilosT FROM Tbl_reg_produccion WHERE id_op_rp='$id_op' AND id_proceso_rp='4'";
        $resultsellk=mysql_query($sqlsellk); 
        $numsellk=mysql_num_rows($resultsellk); 
        if($numsellk >= '1') 
        { $tkilos_sell=mysql_result($resultsellk,0,'kilosT');
		$bolsas=mysql_result($resultsellk,0,'BOLSAS');
        echo redondear_decimal($tkilos_sell+$kilos_desp_sell);
        }else{echo "0";}	
        ?>
        </a>
      </td>
    <td class="centrado6"><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="new" style="text-decoration:none; color:#000000">
      <?php 
	    echo numeros_format($kilos_desp_sell);
 	  ?>
    </a><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="new" style="text-decoration:none; color:#000000"></a></td>
      <td class="centrado6"><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="new" style="text-decoration:none; color:#000000">
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
      </a><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="new" style="text-decoration:none; color:#000000"></a></td>   
      <td class="centrado6"><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="new" style="text-decoration:none; color:#000000">
        <?php 
	  $id_op=$row_consumo['id_op'];
	  $sqlsellpm="SELECT SUM(valor_tiem_rt) AS horasM FROM Tbl_reg_tiempo WHERE op_rt='$id_op' AND id_proceso_rt='4'"; 
	  $resultsellpm=mysql_query($sqlsellpm); 
	  $numsellpm=mysql_num_rows($resultsellpm); 
	  if($numsellpm >= '1') 
	  { $horasM_sell=mysql_result($resultsellpm,0,'horasM');
	  }
	 
 	  $id_op=$row_consumo['id_op'];
	  $sqlsellp="SELECT SUM(valor_prep_rtp) AS horasP FROM Tbl_reg_tiempo_preparacion WHERE op_rtp='$id_op' AND id_proceso_rtp='4'"; 
	  $resultsellp=mysql_query($sqlsellp); 
	  $numsellp=mysql_num_rows($resultsellp); 
	  if($numsellp >= '1') 
	  { $horasP_sell=mysql_result($resultsellp,0,'horasP');
	  $totalTiempo_sell = conversorMinutosHoras($horasM_sell,$horasP_sell);
	   echo $totalTiempo_sell;
	   
	   }
	  ?>
      </a></td>
      <td class="centrado6"><?php echo redondear_entero_puntos($bolsas);
	 // echo quitarHora($row_consumo['fecha_ini_rp']);?></td>
      <td class="centrado6">                
	  <?php  
  	  $id_op=$row_consumo['id_op']; 
/*	  $bds = array(
	  1 => "SELECT COUNT(DISTINCT rollo_r) AS rollos, LAST_DAY(fechaF_r) AS FECHA, SUM(metro_r) AS metros,SUM(kilos_r) AS kilos FROM TblExtruderRollo",
      2 => "SELECT COUNT(DISTINCT rollo_r) AS rollos, LAST_DAY(fechaF_r) AS FECHA, SUM(metro_r) AS metros_imp, SUM(kilos_r) AS kilos FROM TblImpresionRollo",
      3 => "SELECT COUNT(DISTINCT rollo_r) AS rollos, SUM(bolsas_r) AS bolsas, LAST_DAY(fechaF_r) AS FECHA, SUM(reproceso_r) AS reproceso FROM TblSelladoRollo",
); */

	 $bd_e = "SELECT COUNT(DISTINCT rollo_r) AS rollos, LAST_DAY(fechaF_r) AS FECHA, SUM(metro_r) AS metros,SUM(kilos_r) AS kilos FROM TblExtruderRollo";
     $bd_i = "SELECT COUNT(DISTINCT rollo_r) AS rollos, LAST_DAY(fechaF_r) AS FECHA, SUM(metro_r) AS metros_imp, SUM(kilos_r) AS kilos FROM TblImpresionRollo";
     $bd_s = "SELECT COUNT(DISTINCT rollo_r) AS rollos, SUM(bolsas_r) AS bolsas, LAST_DAY(fechaF_r) AS FECHA, SUM(reproceso_r) AS reproceso FROM TblSelladoRollo";
 
/* foreach ($bds as $bd) {	 
	 $fecha_g = fechaGeneral($id_op,$bd); //trae la fecha en q se proceso
	 //$fecha_general = quitarDia($fecha_g);
  	   $fecha_mes =  $fecha_g;  
        }*/
		
		  $fecha_e = fechaGeneral($id_op,$bd_e)."</br>";//fecha de o.p
		  $fecha_i = fechaGeneral($id_op,$bd_i)."</br>";
		  $fecha_s = fechaGeneral($id_op,$bd_s);
		   
		 echo   $extr=distribucion($fecha_e,"extrusion");
		    $imp= distribucion($fecha_i,"impresion");
		    $sell= distribucion($fecha_s,"sellado");  
		   
/*		$bds = array($fecha_e,$fecha_i,$fecha_s);
		 for ($i=0; $i<=(3); $i++) {
			  $fechas=($bds[$i]);
		  
			 distribucion($fechas);
		  }*/
/*		    $sqlgeneral="SELECT * FROM `TblDistribucionHoras` WHERE fecha = '$fecha_e'";
		 
	  $general= mysql_query($sqlgeneral)or die(mysql_error());
	  $row_general = mysql_fetch_assoc($general);
	  $numgeneral = mysql_num_rows($general); 
	  echo $row_general['extrusion'];*/
	  ?></td>
      <td class="centrado6"><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="new" style="text-decoration:none; color:#000000"><?php $estados=$row_consumo['b_estado_op']; 
						switch ($estados){
							case 0: echo "INGRESADA";
							break;
							case 1: echo "EXTRUSION";
							break;
							case 2: echo "IMPRESION";
							break;
							case 3: echo "REFILADO";
							break;
							case 4: echo "SELLADO";
							break;
							case 5: echo "FINALIZADA";
							break;							
							}
	    ?></a></td>    
    </tr>
    <?php } while ($row_consumo = mysql_fetch_assoc($consumo)); ?>
</table>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($consumo);
?>
