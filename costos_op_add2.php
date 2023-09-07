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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
/*  $insertSQL = sprintf("INSERT INTO material_terminado_bolsas (id_bolsa, codigo_bolsa, nombre_bolsa, descripcion_bolsa, id_medida_bolsa, observacion_bolsa, id_ref_bolsa) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['id_bolsa'], "int"),
                       GetSQLValueString($_POST['codigo_bolsa'], "text"),
                       GetSQLValueString($_POST['nombre_bolsa'], "text"),
                       GetSQLValueString($_POST['descripcion_bolsa'], "text"),
                       GetSQLValueString($_POST['id_medida_bolsa'], "int"),
                       GetSQLValueString($_POST['observacion_bolsa'], "text"),
                       GetSQLValueString($_POST['id_ref_bolsa'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());
*/
  $insertGoTo = "costos_op_vista.php?id_op=" . $_POST['id_op'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
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

?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/vista.js"></script>
<script type="text/javascript" src="js/listado.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
</head>
<body>
<div align="center">
<table align="center" id="tabla4">
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
  <li><a href="costos_generales.php">GESTION COSTOS</a></li>
  </ul>
</td>
</tr>  
<tr>
	<td align="center" colspan="2" id="linea1">
      <form action="<?php echo $editFormAction; ?>" method="post" name="form1" >
        <table id="tabla2">
        <tr>
          <td id="fuente1"><table id="tabla3">
            <tr>
              <td nowrap colspan="7" id="titulo2">COSTOS ORDEN DE PRODUCCION  </td>
              </tr>
            <tr>
              <td colspan="5" id="fuente2"><h1 style="color:#F00">N: <?php echo $row_ref_op['id_op']; ?></h1></td>
              <td colspan="2" id="fuente3"><h2>
                <?php if($row_ref_op['b_estado_op']=='5'){echo "Finalizada";}else{ ?>
              </h2>
                <h2 style="color:#F00"><?php echo "En proceso";}?></h2></td>
              </tr>
            <!--<tr>
              <td colspan="9" id="fuente2">Novedades de los Operarios, Tiempos,
                <input type="date" name="fechafin" id="fechafin" value="<?php if($_GET['fechafin']==''){ echo fecha();}else{echo $_GET['fechafin'];}?>" onChange="cargarop()"></td>
              <td id="fuente3"></td>
            </tr>-->
            <tr>
              <td colspan="7" id="fuente3"><img src="images/impresor.gif" onClick="window.print();" style="cursor:hand;" alt="IMPRIMIR" /><a href="costos_producto_terminado.php"> <img src="images/opciones.gif" alt="LISTADO P.O COSTO" border="0" style="cursor:hand;"/></a>
<a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a>
<input name="id_op" type="hidden" id="id_op" value="<?php echo $row_ref_op['id_op']; ?>" /></td>
            </tr>
            <tr>
              <td colspan="7" id="fuente1"><p><strong>Nota:</strong> Importante revisar y tener al dia el precio kilo  de los insumos, Novedades de los operarios por mes, valores de CIF, GGA, GGF, GGV.</p></td>
            </tr>
             <tr>
              <td colspan="7" id="fuente2">&nbsp;</td>
            </tr>
            <tr  id="tr1">
              <td colspan="7" id="titulo2">DESCRIPCION O.P</td>
            </tr>
            <tr>
              <td id="fuente2"> FECHA O.P</td>
              <td id="fuente2">REF-VERSION</td>
              <td id="fuente2">TIPO BOLSA</td>
              <td id="fuente2">UND X PAQ</td>
              <td id="fuente2">KILOS P.</td>
              <td id="fuente2">UND P.</td>
              <td id="fuente2">% DESPERDICIO</td>
              
            </tr>
            <tr>
              <td id="detalle2"> <?php echo $row_ref_op['fecha_registro_op']; ?></td>
              <td id="detalle2"><?php echo $row_ref_op['int_cod_ref_op']; ?>- <?php echo $row_ref_op['version_ref_op']; ?></td>
              <td id="detalle2"><?php echo $row_ref_op['str_tipo_bolsa_op']; ?></td>
              <td nowrap id="detalle2"><?php echo $row_ref_op['int_undxpaq_op'];?></td>
              <td nowrap id="detalle2"><?php echo $row_ref_op['int_kilos_op']; ?></td>
              <td nowrap id="detalle2"><?php echo redondear_entero_puntos($row_ref_op['int_cantidad_op']); ?></td>
              <td nowrap id="detalle2"><?php echo $row_ref_op['int_desperdicio_op']; ?></td>
              
            </tr>
            <tr>
              <td id="fuente2">TIPO Cliente</td>
              <td nowrap id="detalle1"><?php 
	  $id_c=$row_ref_op['int_cliente_op'];
	  $sqlnclie="SELECT nombre_c,tipo_c FROM cliente WHERE id_c='$id_c'"; 
	  $resultclie=mysql_query($sqlnclie); 
	  $numclie=mysql_num_rows($resultclie); 
	  if($numclie >= '1') 
	  { $cliente=mysql_result($resultclie,0,'nombre_c');
	  $tipo=mysql_result($resultclie,0,'tipo_c'); echo $tipo;  }
	  ?></td>
              <td  nowrap id="fuente2">CLIENTE:</td>
              <td colspan="4"  nowrap id="detalle1"><?php echo $cliente;?></td>
              
            </tr>
            <tr>
              <td colspan="7" id="fuente2">IDENTIFICACION DE LA REFERENCIA</td>
            </tr>
            <tr>
              <td id="fuente2">MATERIAL</td>
              <td id="fuente2">PRESENTACION</td> 
              <td id="fuente2">TRATAMIENTO</td>
              <td id="fuente2">ANCHO</td>
              <td id="fuente2">LARGO</td>
              <td id="fuente2">SOLAPA</td>
              <td id="fuente2">FUELLE</td>
              
            </tr>
            <tr>
              <td id="detalle2"><?php echo $row_ref_op['str_matrial_op']; ?></td>
              <td id="detalle2"><?php echo $row_ref_op['str_presentacion_op']; ?></td>
              
              <td id="detalle2"><?php echo $row_ref_op['str_tratamiento_op']; ?></td>
              <td id="detalle2"><?php echo $row_ref_op['ancho_ref']; ?></td>
              <td id="detalle2"><?php echo $row_ref_op['largo_ref']; ?></td>
              <td id="detalle2"><?php echo $row_ref_op['solapa_ref']; ?></td>
              <td id="detalle2"><?php echo $row_ref_op['N_fuelle']; ?></td>
              
            </tr>
            <tr> 
              
              <td nowrap id="fuente2">ADHESIVO</td>
              <td nowrap id="fuente2">MILLAR</td>
              <td nowrap colspan="2" id="fuente2">CALIBRE BOLSA</td>
              <td nowrap id="fuente2">CALIBRE B.</td>
              <td nowrap id="fuente2">  MILLAR /B</td>
              <td nowrap id="fuente2">N&deg; TINTAS</td>
            </tr>
            <tr> 
              
              <td id="detalle2"><?php echo $row_ref_op['adhesivo_ref']; ?></td>
              <td id="detalle2"><?php echo $row_ref_op['peso_millar_ref']; ?></td>
              <td colspan="2" id="detalle2"><?php echo $row_ref_op['calibre_ref']; ?></td>
              <td id="detalle2"><?php if($row_ref_op['calibreBols_ref']==''){echo "0";}else{echo $row_ref_op['calibreBols_ref'];} ?></td>
              <td id="detalle2"><?php if($row_ref_op['peso_millar_bols']==''){echo $pesoMbols="0";}else{echo $pesoMbols=$row_ref_op['peso_millar_bols'];} ?></td>
              <td id="detalle2"><?php echo $row_ref_op['impresion_ref']; ?></td>
            </tr>
            <tr>
              <td colspan="7" id="fuente2">&nbsp;</td>
            </tr>
            <tr id="tr1">
              <td colspan="7" id="titulo1">EXTRUSION </td>
              
            </tr>
            <tr>
              <td nowrap id="fuente2">ENTRADA-KG</td>
              <td nowrap id="fuente2">DESPERDICIO-KG</td>
              <td nowrap id="fuente2">MONTAJE-KG</td>
              <td nowrap  id="fuente2">TIEMPO</td>
              <td nowrap  id="fuente2">KILO / HORA</td> 
              <td nowrap  id="fuente2">&nbsp;</td>
              <td nowrap  id="fuente2">&nbsp;</td> 
            </tr>
            <tr>
            <td id="detallegrande3">
              <strong>
              <?php  			  
	  //CANTIDAD MATERIA PRIMA
	  $id_op=$row_ref_op['id_op'];
	  $sqlcostoMP="SELECT Tbl_reg_kilo_producido.id_rpp_rp, insumo.valor_unitario_insumo AS VALORKILO, Tbl_reg_kilo_producido.valor_prod_rp AS CANTKILOS FROM insumo,Tbl_reg_kilo_producido WHERE insumo.id_insumo = Tbl_reg_kilo_producido.id_rpp_rp AND Tbl_reg_kilo_producido.id_proceso_rkp='1' AND Tbl_reg_kilo_producido.op_rp = '$id_op'"; 
	  $resultcostoMP=mysql_query($sqlcostoMP); 
	  $numcostoMP=mysql_num_rows($resultcostoMP); 
	  $row_valoresMP = mysql_fetch_assoc($resultcostoMP);
	  $contValor=0;
	  $contCant=0;
	  do{
		  $valorMP = $row_valoresMP['VALORKILO'];
		  $KilosMP = $row_valoresMP['CANTKILOS'];//TODOS LOS KILOS REGISTRADOS CON DESPERDICIOS
          $valorItem=$valorMP*$KilosMP;//cada item cuanto vale un kilo
	      $contValor+=$valorItem;//ACUMULA VALOR POR ITEM
		  $contCant+=$KilosMP;//ACUMULA CANTIDAD POR ITEM
    } while ($row_valoresMP = mysql_fetch_assoc($resultcostoMP));
	      $contValor=$contValor;//DATO PARA EL CAMPO COSTO MP
	      $kiloMP = ($contValor/$contCant); //COSTO KILO DE MP	 
	   
 	  //FECHA GENERAL DE CIERRE CON LA DE SELLADO 
  	  $id_op=$row_ref_op['id_op'];
	  $sqlrollo="SELECT COUNT(DISTINCT rollo_r) AS rollos, LAST_DAY(fechaF_r) AS FECHA, SUM(metro_r) AS metros,SUM(kilos_r) AS kilos FROM TblExtruderRollo WHERE id_op_r='$id_op'"; 
	  $resultrollo=mysql_query($sqlrollo); 
	  $numrollo=mysql_num_rows($resultrollo); 
	  if($numrollo >= '1') 
	  { $rollo=mysql_result($resultrollo,0,'rollos');
	    $metros_ext=mysql_result($resultrollo,0,'metros');
		//$kilos_r=mysql_result($resultrollo,0,'kilos');
		$kilos_ex=$contCant;
		$FECHA_NOVEDAD_EXT=mysql_result($resultrollo,0,'FECHA');
		 echo  redondear_decimal_operar($kilos_ex);}else{echo "0,00";
	   } 			  
	  //VALOR KILO MATERIA PRIMA 
/*	  $sqlmateriaP="SELECT `id_ing`,`fecha_ing`, (TblIngresos.valor_und_ing) AS VALORKILO, Tbl_reg_kilo_producido.valor_prod_rp AS CANTKILOS  FROM TblIngresos,Tbl_reg_kilo_producido WHERE TblIngresos.id_insumo_ing = Tbl_reg_kilo_producido.id_rpp_rp AND Tbl_reg_kilo_producido.id_proceso_rkp='1' AND Tbl_reg_kilo_producido.op_rp = '$id_op' AND  DATE_FORMAT(TblIngresos.fecha_ing,'%Y-%m') = DATE_FORMAT('$FECHA_NOVEDAD_EXT', '%Y-%m') ORDER BY Tbl_reg_kilo_producido.id_rpp_rp, TblIngresos.fecha_ing DESC"; 
	  $resultmateriaP=mysql_query($sqlmateriaP); 
	  $nummateriaP=mysql_num_rows($resultmateriaP); 
	  if($nummateriaP >= '1') 
	  { $contValor_materiaP=mysql_result($resultmateriaP,0,'kgDespe');}else{$contValorR_materiaP='0';}*/
	?>
              </strong></td>
              <td id="detallegrande3"><a href="costos_listado_ref_xproceso.php" target="_blank" style="text-decoration:underline; color:#000000" ><?php 
	  //desperdicio general diferende de id 29, Desperdicio
	  $id_op=$row_ref_op['id_op'];
	  $sqldesp="SELECT SUM(valor_desp_rd) AS kgDespe FROM Tbl_reg_desperdicio WHERE op_rd='$id_op' AND id_rpd_rd <> '29' AND id_proceso_rd='1'"; 
	  $resultdesp=mysql_query($sqldesp); 
	  $numdesp=mysql_num_rows($resultdesp); 
	  if($numdesp >= '1') 
	  { $kilos_desp=mysql_result($resultdesp,0,'kgDespe'); echo redondear_decimal_operar($kilos_desp); }else {echo "0,00";}
	  ?></a></td>
              <td id="detallegrande3"><?php 
	  //solamente de desperdicio de montaje id 29, Montaje
	  $id_op=$row_ref_op['id_op'];
	  $sqlmont="SELECT SUM(valor_desp_rd) AS kgmont FROM Tbl_reg_desperdicio WHERE op_rd=$id_op AND id_proceso_rd='1' AND id_rpd_rd='29'"; 
	  $resultmont=mysql_query($sqlmont); 
	  $nummont=mysql_num_rows($resultmont); 
	  if($nummont >= '1') 
	  { $kgmont=mysql_result($resultmont,0,'kgmont'); echo redondear_decimal_operar($kgmont); }else {echo "0,00";}
	  ?></td>
              <td id="detallegrande3"><a href="costos_listado_ref_xproceso_tiempos.php" target="_blank" style="text-decoration:underline; color:#000000" ><strong>
                <?php  $KILOSREALESEXT = ($kilos_ex-($kilos_desp+$kgmont)); ?>
                <?php 	 
	   $id_op=$row_ref_op['id_op'];
	  $sqlex="SELECT `cod_empleado_r`, TIMEDIFF(MAX(`fechaF_r`), MIN(`fechaI_r`)) AS TIEMPODIFE FROM `TblExtruderRollo` WHERE `id_op_r`= '$id_op' GROUP BY `fechaI_r`,`cod_empleado_r` ASC"; 
	  $resultex=mysql_query($sqlex); 
	  $numex=mysql_num_rows($resultex); 
	  if($numex >= '1') 
	  { 
	  while ($row=mysql_fetch_array($resultex)) {
	  $horasenextr=$row['TIEMPODIFE'];  
	  $totaltiempoEXT = horadecimalUna($horasenextr);
	  $tHoras_ex += $totaltiempoEXT; 
	  } 
	    echo redondear_decimal($tHoras_ex);
	    $horasM_exDec=horadecimalUna($tHoras_ex);//hora adecimal para operar
	  }else{echo "0";}
?>
                <?php 
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
	  { $horasP_ex=mysql_result($resultexp,0,'horasP'); $totalTiempo=$horasP_ex+$horasM_ex;}
	  ?>
                <?php
      //GASTOS GENERALES
	  $fecha_general = quitarDia($FECHA_NOVEDAD_EXT);
 	  $sqlgeneral="SELECT * FROM `TblDistribucionHoras` WHERE fecha BETWEEN '$fecha_general' AND '$fecha_general'";
	  $resultgeneral= mysql_query($sqlgeneral);
	  $numgeneral= mysql_num_rows($resultgeneral);
 	  //PARA TODOS LOS PROCESOS
	  if($numgeneral >='1')
	  { 
	  $TiempomeExt =  mysql_result($resultgeneral, 0, 'extrusion');
      //EXTRUDER
	  $costoUnHGga_ext = mysql_result($resultgeneral, 0, 'gga_ext');
	  $costoUnHGga_imp = mysql_result($resultgeneral, 0, 'gga_imp');
	  $costoUnHGga_ref = mysql_result($resultgeneral, 0, 'gga_ref');
	  $costoUnHGga_sell = mysql_result($resultgeneral, 0, 'gga_sell');	  
	  //IMPRESION
	  $costoUnHCif_ext = mysql_result($resultgeneral, 0, 'cif_ext');
	  $costoUnHCif_imp = mysql_result($resultgeneral, 0, 'cif_imp');
	  $costoUnHCif_ref = mysql_result($resultgeneral, 0, 'cif_ref');
	  $costoUnHCif_sell = mysql_result($resultgeneral, 0, 'cif_sell');
	  //REFILADO
	  $costoUnHGgv_ext = mysql_result($resultgeneral, 0, 'ggv_ext');
	  $costoUnHGgv_imp = mysql_result($resultgeneral, 0, 'ggv_imp');
	  $costoUnHGgv_ref = mysql_result($resultgeneral, 0, 'ggv_ref');
	  $costoUnHGgv_sell = mysql_result($resultgeneral, 0, 'ggv_sell');
	  //SELLADO
	  $costoUnHGgf_ext = mysql_result($resultgeneral, 0, 'ggf_ext');
	  $costoUnHGgf_imp = mysql_result($resultgeneral, 0, 'ggf_imp');
	  $costoUnHGgf_ref = mysql_result($resultgeneral, 0, 'ggf_ref');
	  $costoUnHGgf_sell = mysql_result($resultgeneral, 0, 'ggf_sell');
 	  }				
	   $TiempomeExt;		
	  ?>
              </strong></a></td>
              <td id="detallegrande3"><strong>
                <?php 	  
/*      $id_op=$row_ref_op['id_op'];
	  $sqlexKH="SELECT COUNT(id_op_rp) AS ITEMS,SUM(int_kilosxhora_rp) AS KILOHORA FROM Tbl_reg_produccion WHERE id_op_rp='$id_op' AND id_proceso_rp='1'"; 
	  $resultexKH=mysql_query($sqlexKH); 
	  $numexKH=mysql_num_rows($resultexKH); 
	  if($numexKH >= '1') 
	  {$ITEMSEXT=mysql_result($resultexKH,0,'ITEMS'); 
	  $KilosxHoraEX=mysql_result($resultexKH,0,'KILOHORA');
	  $KilosxHoraEXT=($KilosxHoraEX/$ITEMSEXT);
	  echo redondear_decimal_operar($KilosxHoraEXT);
	  }*/
	   $KilosxHoraEXT =($kilos_ex/$horasM_exDec);
	   echo redondear_decimal_operar($KilosxHoraEXT);
	   
	  ?>
              </strong></td> 
              <td id="detallegrande3">&nbsp;</td>
      <td id="detallegrande3">&nbsp;</td> 
            </tr>
            <tr>
              <td nowrap id="fuente2">METRO -LINEAL </td>
              <td nowrap id="fuente2">COSTO INSUMO</td>
              <td nowrap id="fuente2">COSTO KILO</td> 
              <td nowrap id="fuente2"><strong>COSTO TOTAL</strong></td>
              <td nowrap id="fuente2">&nbsp;</td>
              <td nowrap id="fuente2">&nbsp;</td>
              <td nowrap id="fuente2">&nbsp;</td>
               
            </tr>
            <tr>
              <td id="detallegrande3"><?php 
			  $anchoporc=$row_ref_op['ancho_ref']; 
			  $bolsas_ext = bolsasAprox($metros_ext,$anchoporc); 
			  redondear_entero_puntos($row_ref_op['kls_sellado_bol_op']); 			  
	          ?>
                <a href="proceso_empleados_listado.php" target="_blank" style="text-decoration:underline; color:#000000" >
                <?php 
	//COSTO EMPLEADOS FUERA DE PROCESO 
	$sqlbasico="SELECT COUNT(a.codigo_empleado) AS operarios,(a.horasmes_reales) AS HORASMES, (a.horas_empleado) AS HORADIA, SUM(b.sueldo_empleado) AS SUELDO, SUM(b.aux_empleado) AS AUXILIO, SUM(c.total) AS APORTES
FROM empleado a 
LEFT JOIN TblProcesoEmpleado b ON a.codigo_empleado=b.codigo_empleado  
LEFT JOIN TblAportes c ON a.codigo_empleado=c.codigo_empl 
WHERE a.tipo_empleado IN(1,2,3,8,11,12,13,14)";
	$resultbasico=mysql_query($sqlbasico);	
	$sueldo_bas=mysql_result($resultbasico,0,'SUELDO'); //sueldo del mes 
	$auxilio_bas=mysql_result($resultbasico,0,'AUXILIO'); //sueldo del mes 	  
	$aportes_bas=mysql_result($resultbasico,0,'APORTES'); //aportes del mes 
 	$horasmes_bas=mysql_result($resultbasico,0,'HORASMES');//LO EQUIVALENTE A LAS HORAS QUE SE TRABAJAN EN UN MES REAL 186,6666667
	$operarios_bas=mysql_result($resultbasico,0,'operarios');//OPERARIOS 
	$horasdia_bas=mysql_result($resultbasico,0,'HORADIA');//esto es 8 
	 	 
	 //NOVEDAD EMPLEADOS FUERA DE PROCESO 
 	$sqlnovbasico="SELECT SUM(b.pago_acycia) as pago,SUM(b.horas_extras) as extras,SUM(b.recargos) as recargo,SUM(b.festivos) AS festivos 
FROM empleado a 
LEFT JOIN TblNovedades b ON a.codigo_empleado=b.codigo_empleado 
WHERE a.tipo_empleado IN(1,2,3,8,11,12,13,14) AND b.fecha BETWEEN DATE_FORMAT('$FECHA_NOVEDAD_EXT', '%Y-%m-01') AND '$FECHA_NOVEDAD_EXT'";
	$resultnovbasico=mysql_query($sqlnovbasico);	
	$pago_novbasico=mysql_result($resultnovbasico,0,'pago'); 
	$extras_novbasico=mysql_result($resultnovbasico,0,'extras');  
	$recargo_novbasico=mysql_result($resultnovbasico,0,'recargo');
	$festivo_novbasico=mysql_result($resultnovbasico,0,'festivos');
 	$valorhoraxoperExtDemas = sueldoMes($sueldo_bas,$auxilio_bas,$aportes_bas,   $horasmes_bas,$horasdia_bas,$recargo_novbasico,$festivo_novbasico);
	$distribuir = ($valorhoraxoperExtDemas)/4;
   	$horaOper =  $distribuir;//$numoper promedio hora un operario       
	$mod_demas = ($horaOper)/$KilosxHoraEXT; 
   //FIN COSTO EMPLEADOS FUERA DE PROCESO 
?>
                <?php 
 	  //REGISTROS
      $id_op=$row_ref_op['id_op'];
	  $sqloper="SELECT `cod_empleado_r`, `turno_r`, MIN(`fechaI_r`) AS TIEMPOINI, MAX(`fechaF_r`) AS TIEMPOFIN, TIMEDIFF (`fechaF_r`, `fechaI_r`) AS TIEMPODIFE FROM `TblExtruderRollo` WHERE `id_op_r`= $id_op GROUP BY `fechaI_r`, `cod_empleado_r` ASC"; 
      $operario = mysql_query($sqloper, $conexion1) or die(mysql_error());
      $row_operario = mysql_fetch_assoc($operario);
	  $numoper=mysql_num_rows($operario); 
      $const_mode=0;
	  do{
      $oper_proc_ext = $row_operario['cod_empleado_r']; 
	  $oper_horas_ext = horadecimalUna($row_operario['TIEMPODIFE']);
  		
	$sqlemp="SELECT (a.horasmes_reales) AS HORASMES,(a.diasmes_reales) AS MES,(a.horas_empleado) AS HORADIA,(b.sueldo_empleado) AS SUELDO,(b.aux_empleado) AS AUXILIO,(c.total) AS APORTES FROM 
empleado a 
LEFT JOIN TblProcesoEmpleado b ON a.codigo_empleado=b.codigo_empleado  
LEFT JOIN TblAportes c ON a.codigo_empleado=c.codigo_empl WHERE a.codigo_empleado=$oper_proc_ext";
	$resultemp=mysql_query($sqlemp);	
	$sueldo=mysql_result($resultemp,0,'SUELDO'); //sueldo del mes 
	$aux_trans=mysql_result($resultemp,0,'AUXILIO');//auxilio de transp
	$aportestotal=mysql_result($resultemp,0,'APORTES');//total de los aportes del empleado 
	$horasmes=mysql_result($resultemp,0,'HORASMES');//LO EQUIVALENTE A LAS HORAS QUE SE TRABAJAN EN UN MES REAL 186,6666667 
	$horasdia=mysql_result($resultemp,0,'HORADIA');//esto es 8 
	//NOVEDADES DEL MES DE LOS OPRARIOS DE EXTRUDER 
	
	$fechafin=($FECHA_NOVEDAD_EXT);//define las novedades de la consulta
 	$sqlnovedad="SELECT SUM(recargos) as RECARGOS,SUM(festivos) as FESTIVOS FROM TblNovedades WHERE codigo_empleado=$oper_proc_ext AND fecha BETWEEN DATE_FORMAT('$fechafin', '%Y-%m-01') AND '$fechafin'";  
    $resultnovedad=mysql_query($sqlnovedad);
	$recargos=mysql_result($resultnovedad,0,'RECARGOS');//define si el operario trabajo en nocturna o no
	$festivos=mysql_result($resultnovedad,0,'FESTIVOS');//otros recargos
	
 	$valorhoraxoperExt  =  sueldoMes($sueldo,$aux_trans,$aportestotal,$horasmes,$horasdia,$recargos,$festivos);
	$KilosxHora = $KilosxHoraEXT;//$KilosxHoraEXT;//kilos por hora
    $const_mode += $valorhoraxoperExt;//valor total hora de todos operarios en ex
	$horaOper =  $const_mode / $numoper;//$numoper promedio hora un operario
        if($numoper > 1)
		{$mod_e =  ($horaOper)/$KilosxHora;}
		else{
		$mod_e =  ($horaOper * $oper_horas_ext)/$KilosxHora;} 
        }while ($row_operario = mysql_fetch_assoc($operario));
  		 redondear_decimal($mod_e+$mod_demas);
 	   ?>
                </a><a href="costos_listado_gga.php" target="_blank" style="text-decoration:underline; color:#000000" >
                <?php 
 	  $cif_e = ($costoUnHCif_ext/$KilosxHoraEXT); 
	  redondear_entero_puntos($cif_e);//CIF HORA
	  ?>
                <?php 
	 $gga_e = ($costoUnHGga_ext/$KilosxHoraEXT); 
	 redondear_entero_puntos($gga_e);
	  ?>
                <?php 
	 $ggv_e = ($costoUnHGgv_ext/$KilosxHoraEXT);
	  redondear_entero_puntos($ggv_e); //GGV HORA	
	  ?>
                <?php 
	 $ggf_e = ($costoUnHGgf_ext/$KilosxHoraEXT);
	  redondear_entero_puntos($ggf_e); //GGF HORA		
	  ?>
                <?php 
			  //regla de tres
			  echo $metros_ext;
  			  $metro_des_ext = metrolineal($kilos_desp,$kgmont,0,$metros_ext,$kilos_ex);
			  ?>
                </a>              </td>
              <td id="detallegrande3">$
                <?php 
	  echo redondear_entero_puntos($kiloMP);//COSTO KILO DE MP	
	   ?></td>
              <td id="detallegrande3" nowrap>$
                <?php  $COSTOHORAKILO = ($mod_e+$cif_e+$gga_e+$ggv_e+$ggf_e+$kiloMP);echo redondear_entero_puntos($COSTOHORAKILO);  ?></td> 
              <td id="detallegrande3"><?php 
	   //COSTO MATERIA PRIMA
	  $costoMP = $contValor;
	   
	   redondear_entero_puntos($costoMP); //COSTO TOTAL DE MP	  		
	  ?>
$
  <?php 
			  $totalextruder=($kilos_ex * $COSTOHORAKILO);
			  echo redondear_entero_puntos($totalextruder);?>
  <?php  
				//CONTROLA LA RENTABILIDAD CUANDO UNO DE LOS PROCESOS NO EXISTE
 				$costoBolsaExt=$totalextruder/$bolsas_ext;
				 redondear_decimal_operar($costoBolsaExt);?>
  <?php  
                  $id_op=$row_ref_op['id_op'];
              	  $sqlcotiz="SELECT Tbl_items_ordenc.str_unidad_io,Tbl_items_ordenc.int_precio_io FROM Tbl_orden_produccion,Tbl_items_ordenc WHERE Tbl_orden_produccion.id_op = $id_op  AND Tbl_orden_produccion.int_cod_ref_op = Tbl_items_ordenc.int_cod_ref_io ORDER BY Tbl_items_ordenc.fecha_despacho_io DESC LIMIT 1";				
				  $resultcotiz=mysql_query($sqlcotiz); 
				  $numcotiz=mysql_num_rows($resultcotiz); 
				  $medida=mysql_result($resultcotiz,0,'str_unidad_io');
				  $precioCotiz=mysql_result($resultcotiz,0,'int_precio_io');
                  $undPaquetes=$row_ref_op['int_undxpaq_op'];//unidad x paquetes
				  
				$precioCotiz_ext = unidadMedida($medida,$precioCotiz,$undPaquetes);
				$precioCotiz_ext; ?>
  <?php $utilidadExt=($precioCotiz_ext-$costoBolsaExt); redondear_decimal_operar($utilidadExt);?>
  <?php 
			 porcentaje2($precioCotiz_ext,$utilidadExt,0);
			  ?></td>
              <td nowrap id="detallegrande3">&nbsp;</td>
              <td id="detallegrande3" nowrap>&nbsp;</td>
              <td id="detallegrande3" nowrap>&nbsp;</td>
              
             
            </tr>
            <tr>
              <td id="detallegrande5">&nbsp;</td>
              <td colspan="5" id="fuente1"><span style="color:#F00"><em>
                <?php if ($tHoras_ex==''){echo "* Falta Liquidar en Extrusion</br>";}
				if ($TiempomeExt==''){echo "* Falta horas por Mes, este proceso puede estar en el siguiente mes o falta agregar la distribucion de horas del mes actual.</br>";}
				if ($KilosxHoraEXT==''){echo "* Falta liquidar kilos x hora 0.00 </br>";} ?>
              </em></span></td>
              <td id="fuente1">&nbsp;</td>
               
            </tr>
            
            	            <?php
	  $id_op=$row_ref_op['id_op'];
	  $sqlrollo="SELECT rollo_r FROM TblImpresionRollo WHERE id_op_r='$id_op'"; 
	  $resultrollo=mysql_query($sqlrollo); 
	  $numrollo=mysql_num_rows($resultrollo); 
	  if($numrollo >= '1') 
	  {
	  ?>
            <tr id="tr1">
              <td colspan="7" id="titulo1">IMPRESION</td>
              
            </tr>
            <tr>
              <td nowrap id="fuente2">ENTRADA-KG</td>
              <td nowrap id="fuente2">DESPERDICIO-KG</td>
              <td nowrap id="fuente2">MONTAJE-KG</td>
              <td nowrap  id="fuente2">TIEMPO</td>
              <td nowrap  id="fuente2">KILO / HORA</td> 
              <td nowrap  id="fuente2">&nbsp;</td>
              <td nowrap  id="fuente2">&nbsp;</td>
              
            </tr>
            <tr>
              <td id="detallegrande3">
	            <strong>
	            <?php
	  $id_op=$row_ref_op['id_op'];
	  $sqlrollo="SELECT COUNT(DISTINCT rollo_r) AS rollos, LAST_DAY(fechaF_r) AS FECHA, SUM(metro_r) AS metros_imp, SUM(kilos_r) AS kilos FROM TblImpresionRollo WHERE id_op_r='$id_op'"; 
	  $resultrollo=mysql_query($sqlrollo); 
	  $numrollo=mysql_num_rows($resultrollo); 
	  if($numrollo >= '1') 
	  { $rollo_imp=mysql_result($resultrollo,0,'rollos');
	    $metros_imp=mysql_result($resultrollo,0,'metros_imp');
		$kilos_imp=mysql_result($resultrollo,0,'kilos');
		$FECHA_NOVEDAD_IMP=mysql_result($resultrollo,0,'FECHA');
		
		 echo redondear_decimal_operar($KILOSREALESEXT); 
	   }else {echo "0,00";}
	   
	  $id_op=$row_ref_op['id_op'];
	  $sqlimp="SELECT SUM(valor_prod_rp) AS kge FROM Tbl_reg_kilo_producido WHERE op_rp='$id_op' AND id_proceso_rkp='2' "; 
	  $resultimp=mysql_query($sqlimp); 
	  $numimp=mysql_num_rows($resultimp); 
	  if($numimp >= '1') 
	  { $kilos_impTinta=mysql_result($resultimp,0,'kge');}else {echo "0,00";}
	  ?>
	            </strong></td>
              <td id="detallegrande3"><a href="costos_listado_ref_xproceso.php" target="_blank" style="text-decoration:underline; color:#000000" >
                <?php 
	  //desperdicio general diferende de id 55, Desperdicio
	  $id_op=$row_ref_op['id_op'];
	  $sqldesp="SELECT SUM(valor_desp_rd) AS kgDespe FROM Tbl_reg_desperdicio WHERE op_rd='$id_op' AND id_rpd_rd <> '55' AND id_proceso_rd='2'"; 
	  $resultdesp=mysql_query($sqldesp); 
	  $numdesp=mysql_num_rows($resultdesp); 
	  if($numdesp >= '1') 
	  { $kilos_desp_imp=mysql_result($resultdesp,0,'kgDespe'); echo redondear_decimal_operar($kilos_desp_imp); }else {echo "0,00";}
	  ?>
              </a></td>
              <td id="detallegrande3"><?php 
	  //solamente de desperdicio de montaje id 55, Montaje
	  $id_op=$row_ref_op['id_op'];
	  $sqlmont="SELECT SUM(valor_desp_rd) AS kgmont FROM Tbl_reg_desperdicio WHERE op_rd=$id_op  AND id_rpd_rd='55' AND id_proceso_rd='2'"; 
	  $resultmont=mysql_query($sqlmont); 
	  $nummont=mysql_num_rows($resultmont); 
	  if($nummont >= '1') 
	  { $kgmontImp=mysql_result($resultmont,0,'kgmont'); echo redondear_decimal_operar($kgmontImp); }else {echo "0,00";}
	  ?></td>
              <td id="detallegrande3"><a href="costos_listado_ref_xproceso_tiempos.php" target="_blank" style="text-decoration:underline; color:#000000" ><strong>
                <?php $KILOSREALESIMP =($KILOSREALESEXT-($kilos_desp_imp+$kgmontImp)); ?>
                <?php 	 
	  $id_op=$row_ref_op['id_op'];
	  $sqlimp="SELECT `cod_empleado_r`,`cod_auxiliar_r`,  TIMEDIFF(MAX(`fechaF_r`), MIN(`fechaI_r`)) AS TIEMPODIFE FROM `TblImpresionRollo` WHERE `id_op_r`= '$id_op' GROUP BY `fechaI_r`,`cod_empleado_r`,`cod_auxiliar_r` ASC"; 
	  $resultimp=mysql_query($sqlimp); 
	  $numimp=mysql_num_rows($resultimp); 
	  if($numimp >= '1') 
	  { 
	  while ($row=mysql_fetch_array($resultimp)) {
	  $horasenimp=$row['TIEMPODIFE'];  
	  $totaltiempoIMP = horadecimalUna($horasenimp);
	  $tHoras_imp += $totaltiempoIMP; 
	  }
	   echo redondear_decimal($tHoras_imp);
	   $horasM_impDec=$tHoras_imp;//hora adecimal para operar
	  }else{echo "0";}  
?>
                <?php 
	  $id_op=$row_ref_op['id_op'];
	  $sqlimpm="SELECT SUM(valor_tiem_rt) AS horasM FROM Tbl_reg_tiempo WHERE op_rt='$id_op' AND id_proceso_rt='2' "; 
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
	  { $horasP_imp=mysql_result($resultimpp,0,'horasP'); $totalTiempo_imp=$horasP_imp+$horasM_imp;}
	  ?>
                <?php
	  $fecha_general_imp = quitarDia($FECHA_NOVEDAD_IMP);
 	  $sqlgeneral="SELECT * FROM `TblDistribucionHoras` WHERE fecha BETWEEN '$fecha_general_imp' AND '$fecha_general_imp'";
	  $resultgeneral= mysql_query($sqlgeneral);
	  $numgeneral= mysql_num_rows($resultgeneral);
	  if($numgeneral >='1')
	  { 
 	  $TiempomeImp =  mysql_result($resultgeneral, 0, 'impresion');
 	   $TiempomeImp;	
	  }
	  ?>
              </strong></a></td>
              <td id="detallegrande3"><strong>
                <?php 	  
      $id_op=$row_ref_op['id_op'];
	  $sqlimKH="SELECT COUNT(DISTINCT rollo_rp) AS ITEMS,SUM(int_kilosxhora_rp) AS KILOHORA FROM Tbl_reg_produccion WHERE id_op_rp='$id_op' AND id_proceso_rp='2' "; 
	  $resultimKH=mysql_query($sqlimKH); 
	  $numimKH=mysql_num_rows($resultimKH); 
	  if($numimKH >= '1') 
	  {
	  $ITEMSIMP=mysql_result($resultimKH,0,'ITEMS');
	  $KilosxHIMP=mysql_result($resultimKH,0,'KILOHORA');
	  $KilosxHoraIMP=($KilosxHIMP/$ITEMSIMP);
	  echo redondear_decimal_operar($KilosxHoraIMP);
	  }
 
	  /*$KilosxHoraIMP =($KILOSREALESEXT/$horasM_impDec);
	  echo redondear_decimal_operar($KilosxHoraIMP);*/
	  ?>
              </strong></td> 
              <td id="detallegrande3">&nbsp;</td>
              <td id="detallegrande3">&nbsp;</td>
              
            </tr>
            <tr>
              <td id="fuente2"> TINTAS-KG</td>
              <td id="fuente2">METRO -LINEAL </td>
              <td id="fuente2">  <strong> </strong><strong> </strong><strong>COSTO INSUMO </strong></td>
              <td id="fuente2">COSTO KILO</td>
              <td id="fuente2"><strong>COSTO TOTAL</strong></td>
              <td id="fuente2">&nbsp;</td>
              <td id="fuente2">&nbsp;</td>
               
            </tr>
            <tr>
              <td id="detallegrande3"><?php 
			  $anchoporc_imp=$row_ref_op['ancho_ref']; 
			  $bolsas_imp = bolsasAprox($metros_imp,$anchoporc_imp);
			     echo numeros_format($kilos_impTinta); ?>
                <a href="proceso_empleados_listado.php" target="_blank" style="text-decoration:underline; color:#000000" >
                <?php
	  //HORAS TRABAJADAS DE LA O.P
$id_op=$row_ref_op['id_op'];
	  $sqloperario_imp="SELECT cod_empleado_r FROM `TblImpresionRollo` WHERE `id_op_r`= $id_op  ORDER BY `cod_empleado_r` ASC"; 
	  $resultoperario_imp=mysql_query($sqloperario_imp); 
	  $numoper_imp=mysql_num_rows($resultoperario_imp);   
	  	  
      $id_op=$row_ref_op['id_op'];
	  $sqloper="SELECT `cod_empleado_r`, `turno_r`, MIN(`fechaI_r`) AS TIEMPOINI, MAX(`fechaF_r`) AS TIEMPOFIN, TIMEDIFF (`fechaF_r`, `fechaI_r`) AS TIEMPODIFE FROM `TblImpresionRollo` WHERE `id_op_r`= $id_op  GROUP BY `fechaI_r`, `cod_empleado_r` ASC"; 
      $operario = mysql_query($sqloper, $conexion1) or die(mysql_error());
      $row_operario = mysql_fetch_assoc($operario);

      $const_mode_imp=0;
	  do{
      $oper_proc_imp = $row_operario['cod_empleado_r']; 
	  $oper_horas_imp = horadecimalUna($row_operario['TIEMPODIFE']);
  		
	$sqlemp="SELECT (a.horasmes_reales) AS HORASMES,(a.diasmes_reales) AS MES,(a.horas_empleado) AS HORADIA,(b.sueldo_empleado) AS SUELDO,(b.aux_empleado) AS AUXILIO,(c.total) AS APORTES FROM 
empleado a 
LEFT JOIN TblProcesoEmpleado b ON a.codigo_empleado=b.codigo_empleado  
LEFT JOIN TblAportes c ON a.codigo_empleado=c.codigo_empl WHERE a.codigo_empleado=$oper_proc_imp";
	$resultemp=mysql_query($sqlemp);	
	$sueldo=mysql_result($resultemp,0,'SUELDO'); //sueldo del mes  
	$aux_trans=mysql_result($resultemp,0,'AUXILIO');//auxilio de transp
	$aportestotal=mysql_result($resultemp,0,'APORTES');//total de los aportes del empleado 
	$horasmes=mysql_result($resultemp,0,'HORASMES');//LO EQUIVALENTE A LAS HORAS QUE SE TRABAJAN EN UN MES REAL 186,6666667 
	$horasdia=mysql_result($resultemp,0,'HORADIA');//esto es 8 
	//NOVEDADES DEL MES DE LOS OPRARIOS DE EXTRUDER 
	$fechafin=($FECHA_NOVEDAD_IMP);//define las novedades de la consulta
	$sqlnovedad="SELECT SUM(recargos) as RECARGOS,SUM(festivos) as FESTIVOS FROM TblNovedades WHERE codigo_empleado=$oper_proc_imp AND fecha BETWEEN DATE_FORMAT('$fechafin', '%Y-%m-01') AND '$fechafin'";  
    $resultnovedad=mysql_query($sqlnovedad);
	$recargos=mysql_result($resultnovedad,0,'RECARGOS');//define si el operario trabajo en nocturna o no
	$festivos=mysql_result($resultnovedad,0,'FESTIVOS');//otros recargos
	
 	$valorhoraxoperImp  =  sueldoMes($sueldo,$aux_trans,$aportestotal,$horasmes,$horasdia,$recargos,$festivos);
	
	$KilosxHora_imp=$KilosxHoraIMP;
    $const_mode_imp += $valorhoraxoperImp;//valor total hora de todos operarios en ex
	$horaOper_imp =  $const_mode_imp / $numoper_imp;//$numoper promedio hora un operario
		if($numoper_imp > 1)
		{$mod_i=($horaOper_imp / $KilosxHora_imp);}
		else{
		$mod_i=($horaOper_imp * $oper_horas_imp)/$KilosxHora_imp;}
     } while ($row_operario = mysql_fetch_assoc($operario));	
 		 redondear_decimal($mod_i+$mod_demas);	   
	   ?>
                <?php 
	 $cif_i = ($costoUnHCif_imp/$KilosxHoraIMP);
	  redondear_entero_puntos($cif_i); //COSTO DE HORA IMPRESA CON CIF
	  ?>
                </a><a href="costos_listado_gga.php" target="_blank" style="text-decoration:underline; color:#000000" >
                <?php 
	$gga_i  = ($costoUnHGga_imp/$KilosxHoraIMP);
	 redondear_entero_puntos($gga_i);//COSTO DE HORA EXTRUIDA CON CIF	
	  ?>
                <?php 
	 $ggv_i = ($costoUnHGgv_imp/$KilosxHoraIMP); 
	  redondear_entero_puntos($ggv_i); //COSTO DE HORA EXTRUIDA CON CIF	
	  ?>
                <?php 
	 $ggf_i = ($costoUnHGgf_imp/$KilosxHoraIMP); 
	 redondear_entero_puntos($ggf_i); //COSTO DE HORA EXTRUIDA CON CIF	
	  ?>
                </a></td>
              <td id="detallegrande3"><?php
			  echo $metros_imp = $metros_imp;
			  //echo $metros_imp = ($metros_ext - $metro_des_ext);
			  //regla de tres
  			  $metro_des_imp = metrolineal($kilos_desp_imp,$kgmontImp,0,$metros_imp,$KILOSREALESEXT);
			  ?></td>
              <td id="detallegrande3" nowrap>$
                <?php 
	   //COSTO TINTAS EN IMPRESION
	  $id_op=$row_ref_op['id_op'];
	  $sqlcostoMP="SELECT Tbl_reg_kilo_producido.id_rpp_rp, insumo.valor_unitario_insumo AS VALORKILO, Tbl_reg_kilo_producido.valor_prod_rp AS CANTKILOS FROM  insumo,Tbl_reg_kilo_producido WHERE insumo.id_insumo = Tbl_reg_kilo_producido.id_rpp_rp AND Tbl_reg_kilo_producido.id_proceso_rkp='2' AND Tbl_reg_kilo_producido.op_rp = '$id_op'"; 
	  $resultcostoMP=mysql_query($sqlcostoMP); 
	  $numcostoMP=mysql_num_rows($resultcostoMP); 
	  $row_valoresMP = mysql_fetch_assoc($resultcostoMP);
	  $contValorI=0;
	  $contCantI=0;
	  do{
		  $valorMP = $row_valoresMP['VALORKILO'];
		  $KilosMP = $row_valoresMP['CANTKILOS'] ;
          $valorItem=$valorMP*$KilosMP;//cada item cuanto vale un kilo
	      $contValorI+=$valorItem;//ACUMULA VALOR POR ITEM
    } while ($row_valoresMP = mysql_fetch_assoc($resultcostoMP));
	 $COSTOTINTA = ($contValorI/$KILOSREALESIMP); 
	 echo redondear_entero_puntos($COSTOTINTA);//COSTO KILO DE TINTA		
	//COSTO MATERIA PRIMA EN IMPRESION TINTAS
	  $kiloMPIMP = $COSTOHORAKILO;//$COSTOHORAKILO PORQUE VA SUMANDO EL COSTO DE LOS ANTERIORES PROCESOS
 	// echo redondear_entero_puntos($kiloMPIMP);
	  ?></td>
              <td id="detallegrande3">$
                <?php $COSTOHORAKILOIMP = ($mod_i+$cif_i+$gga_i+$ggv_i+$ggf_i+$kiloMPIMP+$COSTOTINTA);  
			  echo redondear_entero_puntos($COSTOHORAKILOIMP); ?></td>
              <td id="detallegrande3"><?php 
	   //COSTO MATERIA PRIMA
	   $costoMP_IMP = $contValorI;
redondear_entero_puntos($mod_i+$cif_i+$gga_i+$ggv_i+$ggf_i +$COSTOTINTA+$contValorI); //COSTO TOTAL DE MP EN IMPRESION		  		
	  ?>
$
  <?php
			  $totalimpresion=($COSTOHORAKILOIMP*$KILOSREALESEXT); 
			  echo redondear_entero_puntos($totalimpresion); 
			  ?>
  <?php  
				//CONTROLA LA RENTABILIDAD CUANDO UNO DE LOS PROCESOS NO EXISTE
 				$costoBolsaImp=$totalimpresion/$bolsas_imp;
				redondear_decimal_operar($costoBolsaImp);?>
  <?php  
                  $id_op=$row_ref_op['id_op'];
              	  $sqlcotiz="SELECT Tbl_items_ordenc.str_unidad_io,Tbl_items_ordenc.int_precio_io  FROM Tbl_orden_produccion,Tbl_items_ordenc WHERE Tbl_orden_produccion.id_op = $id_op  AND Tbl_orden_produccion.int_cod_ref_op = Tbl_items_ordenc.int_cod_ref_io ORDER BY Tbl_items_ordenc.fecha_despacho_io DESC LIMIT 1";				
				  $resultcotiz=mysql_query($sqlcotiz); 
				  $numcotiz=mysql_num_rows($resultcotiz); 
				  $medida=mysql_result($resultcotiz,0,'str_unidad_io');
				  $precioCotiz=mysql_result($resultcotiz,0,'int_precio_io');
                  $undPaquetes=$row_ref_op['int_undxpaq_op'];//unidad x paquetes
				  
				$precioCotiz_imp = unidadMedida($medida,$precioCotiz,$undPaquetes);
				$precioCotiz_imp; ?>
  <?php   $utilidadImp=($precioCotiz_imp-$costoBolsaImp);  redondear_decimal_operar($utilidadImp);?>
  <?php 
			 porcentaje2($precioCotiz_imp,$utilidadImp,0);
			  ?></td>
              <td nowrap id="detallegrande3">&nbsp;</td>
              <td id="detallegrande3">&nbsp;</td>
               
            </tr>
            <tr>
              <td id="detallegrande5">&nbsp;</td>
              <td colspan="5" id="fuente1"><span style="color:#F00"><em>
                <?php if ($row_ref_op['impresion_ref']==0 && $tHoras_imp==''){echo "* O.p no lleva impresion </br>";}else if ($row_ref_op['impresion_ref']>0 && $tHoras_imp==''){echo "* Falta Liquidar en Impresion </br>";};
				if ($TiempomeImp==''){echo "* Falta horas por Mes, este proceso puede estar en el siguiente mes o falta agregar la distribucion de horas del mes actual.</br>";}
				if ($KilosxHoraIMP==""){echo "* Falta liquidar kilos x hora 0.00";} ?>
              </em></span></td>
              <td id="fuente1">&nbsp;</td>
               
            </tr> 
            <?php }else{ ?>
             <tr>  <td colspan="7" id="titulo1"><h4 style="color:#F00">SIN IMPRESION</h4></td>
               
             </tr> 
            <?php } ?>        
            <tr>
              <td colspan="7" id="titulo2"> 
                <?php 
			  if($KILOSREALESIMP =='0.00' || $KILOSREALESIMP ==''){$KILOSREALESIMP = $KILOSREALESEXT;}
			   $KILOSREALESREF=($KILOSREALESIMP); //echo redondear_decimal_operar($KILOSREALESREF);?>
                <?php
	  $fecha_general_ref = quitarDia($FECHA_NOVEDAD_REF);
 	  $sqlgeneral="SELECT * FROM `TblDistribucionHoras` WHERE fecha BETWEEN '$fecha_general_ref' AND '$fecha_general_ref'";
	  $resultgeneral= mysql_query($sqlgeneral);
	  $numgeneral= mysql_num_rows($resultgeneral);
	  if($numgeneral >='1')
	  { 
	  $TiempomeRef =  mysql_result($resultgeneral, 0, 'refilado');
	  	
	  }
 	  ?>
                <strong>
                  <?php 	  
      $id_op=$row_ref_op['id_op'];
	  $sqlselKH="SELECT COUNT(DISTINCT rollo_rp) AS ITEMS,SUM(int_kilosxhora_rp) AS KILOHORA FROM Tbl_reg_produccion WHERE id_op_rp='$id_op' AND id_proceso_rp='3' "; 
	  $resultselKH=mysql_query($sqlselKH); 
	  $numselKH=mysql_num_rows($resultexKH); 
	  if($numselKH >= '1') 
	  {$ITEMSREF=mysql_result($resultselKH,0,'ITEMS');
	  $KilosxHREF=mysql_result($resultselKH,0,'KILOHORA'); 
	  $KilosxHoraREF=($KilosxHREF/$ITEMSREF);
	   redondear_decimal_operar($KilosxHoraREF);//este es el real para el proximo mes julio 2015
	 //$KilosxHoraSELL=($KILOSREALESSELL/$horasM_sellDec);
	  }
	  ?>
                  <?php  
				//CONTROLA LA RENTABILIDAD CUANDO UNO DE LOS PROCESOS NO EXISTE
 				$costoBolsaSel=$totalsellado/$bolsas_sel;
				 redondear_decimal_operar($costoBolsaSel);?>
                  <?php  
                  $id_op=$row_ref_op['id_op'];
              	  $sqlcotiz="SELECT Tbl_items_ordenc.str_unidad_io,Tbl_items_ordenc.int_precio_io FROM Tbl_orden_produccion,Tbl_items_ordenc WHERE Tbl_orden_produccion.id_op = $id_op  AND Tbl_orden_produccion.int_cod_ref_op = Tbl_items_ordenc.int_cod_ref_io ORDER BY Tbl_items_ordenc.fecha_despacho_io DESC LIMIT 1";				
				  $resultcotiz=mysql_query($sqlcotiz); 
				  $numcotiz=mysql_num_rows($resultcotiz); 
				  $medida=mysql_result($resultcotiz,0,'str_unidad_io');
				  $precioCotiz=mysql_result($resultcotiz,0,'int_precio_io');
                  $undPaquetes=$row_ref_op['int_undxpaq_op'];//unidad x paquetes
				  
				$precioCotiz_ref = unidadMedida($medida,$precioCotiz,$undPaquetes);
				 $precioCotiz_ref;?>
                  <?php $utilidadRef=($precioCotiz_ref - $costoBolsaRef);  redondear_decimal_operar();?>
                  <?php 
			  porcentaje2($precioCotiz_ref,$utilidadRef,0);
			  ?>
                </strong></td>
              
            </tr>
            <!--<tr id="tr1">
              <td id="fuente2">Refilados  / kg</td>
              <td colspan="2" id="fuente2">Desp. Refilado</td>
              <td colspan="2" id="fuente2">Desp. Montaje</td>
              <td id="fuente2">Refilado Real /kg</td>
              <td id="fuente2">Horas Refilado</td>
              <td id="fuente2">Tiempo Perdido (minutos)</td>
              <td id="fuente2">Horas Mes</td>
              <td id="fuente2">KILO / HORA</td>
              <td id="fuente2">Rollos </td>
              <td id="fuente2">Refilados (mts)</td>
            </tr>
            <tr>
              <td id="detallegrande2">&nbsp;</td>
              <td colspan="2" id="detallegrande2">&nbsp;</td>
              <td colspan="2" id="detallegrande2">&nbsp;</td>
              <td id="detallegrande2">&nbsp;</td>
              <td id="detallegrande2">&nbsp;</td>
              <td id="detallegrande2">&nbsp;</td>
              <td id="detallegrande2">&nbsp;</td>
              <td id="detallegrande2">&nbsp;</td>
              <td id="detallegrande2">&nbsp;</td>
              <td id="detallegrande2">&nbsp;</td>
            </tr>
            <tr id="tr1">
              <td id="fuente2"><strong>Bolsas Aprox.</strong></td>
              <td colspan="2" id="fuente2"><strong>Mano de Obra</strong></td>
              <td colspan="6" id="fuente2"><strong> </strong><strong> </strong><strong> </strong><strong> </strong></td>
              <td id="fuente2" nowrap>COSTO KILO</td>
              <td id="fuente2" nowrap><strong>COSTO T. MP</strong></td>
              <td id="fuente2" nowrap>COSTO TOTAL</td>
            </tr>
            <tr>
              <td id="detallegrande1">&nbsp;</td>
              <td colspan="2" id="detallegrande1">&nbsp;</td>
              <td colspan="3" id="detallegrande2">&nbsp;</td>
              <td id="detallegrande2">&nbsp;</td>
              <td id="detallegrande2">&nbsp;</td>
              <td id="detallegrande2">&nbsp;</td>
              <td id="detallegrande2">$ </td>
              <td id="detallegrande2">&nbsp;</td>
              <td nowrap id="detallegrande2">$ </td>
            </tr>
<tr>
              <td id="detallegrande5">&nbsp;</td>
              <td id="detallegrande6">&nbsp;</td>
              <td id="detallegrande6" nowrap>&nbsp;</td>
              <td colspan="2" id="fuente3">&nbsp;</td>
              <td colspan="2" id="detallegrande6">&nbsp;</td>
              <td colspan="2" id="fuente2">COSTO BOLSA</td>
              <td id="fuente2">PRECIO B. O.C</td>
              <td id="fuente2">UTILIDAD </td>
              <td nowrap id="fuente2">RENTABILIDAD </td>
            </tr>
            <tr>
              <td id="detallegrande4">&nbsp;</td>
              <td id="detallegrande4">&nbsp;</td>
              <td id="detallegrande4" nowrap>&nbsp;</td>
              <td colspan="2" id="detallegrande4">&nbsp;</td>
              <td colspan="2" id="detallegrande4">&nbsp;</td>
              <td colspan="2" id="detallegrande2">$                </td>
              <td id="detallegrande2">$                </td>
              <td id="detallegrande2">$                </td>
              <td nowrap id="detallegrande2"> % </td>
            </tr>-->           
            <tr id="tr1">
              <td colspan="7" id="titulo1">SELLADO</td>
              
            </tr>
            <tr >
              <td nowrap id="fuente2">ENTRADA-KG</td>
              <td nowrap id="fuente2">DESPERDICIO-KG</td>
              <td nowrap id="fuente2">MONTAJE-KG</td>
              <td nowrap id="fuente2">TIEMPO </td>
              <td nowrap id="fuente2">KILO / HORA</td>
              <td nowrap id="fuente2">&nbsp;</td>
              <td nowrap id="fuente2">&nbsp;</td>
               
            </tr>
            <tr>
              <td id="detallegrande3">
	            <strong>
	            <?php
	  $id_op=$row_ref_op['id_op'];
	  $sqlrollo="SELECT COUNT(DISTINCT rollo_r) AS rollos, SUM(kilos_r) AS kilos, SUM(bolsas_r) AS bolsas, LAST_DAY(fechaF_r) AS FECHA, SUM(reproceso_r) AS reproceso FROM TblSelladoRollo WHERE id_op_r='$id_op'"; 
	  $resultrollo=mysql_query($sqlrollo); 
	  $numrollo=mysql_num_rows($resultrollo); 
	  if($numrollo >= '1') 
	  { $rollo_sell=mysql_result($resultrollo,0,'rollos');
	    $bolsas_sell=mysql_result($resultrollo,0,'bolsas');
		$kilos_sell=mysql_result($resultrollo,0,'kilos');
		$reproceso_sell=mysql_result($resultrollo,0,'reproceso');
		$FECHA_NOVEDAD_SELL=mysql_result($resultrollo,0,'FECHA'); 
		if($KILOSREALESREF=='0.00' ||$KILOSREALESREF==''){$KILOSREALESIMP = $KILOSREALESIMP;}
		 echo redondear_decimal_operar($KILOSREALESIMP); 
	   }else {echo "0,00";}?>
	            </strong></td>
              <td id="detallegrande3"><a href="costos_listado_ref_xproceso.php" target="_blank" style="text-decoration:underline; color:#000000" >
                <?php 
	  //desperdicio general diferende de id 105, Desperdicio
	  $id_op=$row_ref_op['id_op'];
	  $sqldesp="SELECT SUM(valor_desp_rd) AS kgDespe FROM Tbl_reg_desperdicio WHERE op_rd='$id_op' AND id_rpd_rd <> '105'  AND id_proceso_rd='4'"; 
	  $resultdesp=mysql_query($sqldesp); 
	  $numdesp=mysql_num_rows($resultdesp); 
	  if($numdesp >= '1') 
	  { $kilos_desp_sell=mysql_result($resultdesp,0,'kgDespe'); echo redondear_decimal_operar($kilos_desp_sell); }else {echo "0,00";}
	  ?>
              </a><a href="produccion_registro_impresion_listado_add.php" target="_blank" style="text-decoration:underline; color:#000000" ></a></td>
              <td id="detallegrande3"><?php 
	  //solamente de desperdicio de montaje id 105, Montaje
	  $id_op=$row_ref_op['id_op'];
	  $sqlmont="SELECT SUM(valor_desp_rd) AS kgmont FROM Tbl_reg_desperdicio WHERE op_rd=$id_op  AND id_rpd_rd='105' AND id_proceso_rd='4'"; 
	  $resultmont=mysql_query($sqlmont); 
	  $nummont=mysql_num_rows($resultmont); 
	  if($nummont >= '1') 
	  { $kgmontSell=mysql_result($resultmont,0,'kgmont'); echo redondear_decimal_operar($kgmontSell); }else {echo "0,00";}
	  ?></td>
              <td id="detallegrande3"><a href="costos_listado_ref_xproceso_tiempos.php" target="_blank" style="text-decoration:underline; color:#000000" ><strong>
                <?php  $KILOSREALESSELL=($KILOSREALESREF-($kilos_desp_sell+$kgmontSell)); ?>
                <?php 	 
	  $id_op=$row_ref_op['id_op'];
	  $sqlsell="SELECT `cod_empleado_r`,`cod_auxiliar_r`,  TIMEDIFF(MAX(`fechaF_r`), MIN(`fechaI_r`)) AS TIEMPODIFE FROM `TblSelladoRollo` WHERE `id_op_r`= '$id_op' GROUP BY `fechaI_r`,`cod_empleado_r`,`cod_auxiliar_r` ASC"; 
	  $resultsell=mysql_query($sqlsell); 
	  $numsell=mysql_num_rows($resultsell); 
	  if($numsell >= '1') 
	  { 
	  while ($row=mysql_fetch_array($resultsell)) {
	  $horasensell=$row['TIEMPODIFE'];  
	  $totaltiempo = horadecimalUna($horasensell);
	  $tHoras_sell += $totaltiempo; 
	  }
	   echo redondear_decimal($tHoras_sell);
	   $horasM_sellDec=$tHoras_sell;//hora adecimal para operar
	  }else{echo "0";}  	 
 	  ?>
                <?php 
	  $id_op=$row_ref_op['id_op'];
	  $sqlsellpm="SELECT SUM(valor_tiem_rt) AS horasM FROM Tbl_reg_tiempo WHERE op_rt='$id_op' AND id_proceso_rt='4'"; 
	  $resultsellpm=mysql_query($sqlsellpm); 
	  $numsellpm=mysql_num_rows($resultsellpm); 
	  if($numsellpm >= '1') 
	  { $horasM_sell=mysql_result($resultsellpm,0,'horasM');
	  }else{echo "0";}
	 
 	  $id_op=$row_ref_op['id_op'];
	  $sqlsellp="SELECT SUM(valor_prep_rtp) AS horasP FROM Tbl_reg_tiempo_preparacion WHERE op_rtp='$id_op' AND id_proceso_rtp='4'"; 
	  $resultsellp=mysql_query($sqlsellp); 
	  $numsellp=mysql_num_rows($resultsellp); 
	  if($numsellp >= '1') 
	  { $horasP_sell=mysql_result($resultsellp,0,'horasP'); $totalTiempo_sell=$horasP_sell+$horasM_sell;}
	  ?>
                <?php
	  $fecha_general_sell = quitarDia($FECHA_NOVEDAD_SELL);
 	  $sqlgeneral="SELECT * FROM `TblDistribucionHoras` WHERE fecha BETWEEN '$fecha_general_sell' AND '$fecha_general_sell'";
	  $resultgeneral= mysql_query($sqlgeneral);
	  $numgeneral= mysql_num_rows($resultgeneral);
	  if($numgeneral >='1')
	  { 
	  $TiempomeSell	=  mysql_result($resultgeneral, 0, 'sellado');  
	   $TiempomeSell; 
	  }
  	  ?>
              </strong></a></td>
              <td id="detallegrande3"><strong>
                <?php 
      $id_op=$row_ref_op['id_op'];
	  $sqlselKH="SELECT COUNT(rollo_rp) AS ITEMS, SUM(int_kilosxhora_rp) AS KILOHORA FROM Tbl_reg_produccion WHERE id_op_rp='$id_op' AND id_proceso_rp='4'"; 
	  $resultselKH=mysql_query($sqlselKH); 
	  $numselKH=mysql_num_rows($resultselKH); 
	  if($numselKH >= '1') 
	  {
      $ITEMSSELL=mysql_result($resultselKH,0,'ITEMS');
	  $KilosxHSELL=mysql_result($resultselKH,0,'KILOHORA'); 
	  $KilosxHoraSELL=($KilosxHSELL/$ITEMSSELL);
	  echo redondear_decimal_operar($KilosxHoraSELL); 
 	  }		 
			  
/*	    $tiempo_menostmuert =($tHoras_sell-($totalTiempo_sell /60));
	    $KilosxHoraSELL = ($KILOSREALESREF / $tiempo_menostmuert); 
		echo redondear_decimal_operar($KilosxHoraSELL);*/
	    
 	  ?>
              </strong></td>
              <td id="detallegrande3">&nbsp;</td>
              <td id="detallegrande3">&nbsp;</td>
       
            </tr>
            <tr>
              <td nowrap id="fuente2">BOLSAS APROX</td>
              <td nowrap id="fuente1"><strong>REPRO-KG </strong></td>
              <td nowrap id="fuente1"><strong>METRO-LINEAL </strong></td>
              <td nowrap id="fuente1"><strong>VALOR DE INSUMOS</strong></td>
              <td nowrap id="fuente1"><strong>COSTO KILO</strong></td>
              <td nowrap id="fuente1"><strong>COSTO TOTAL</strong></td>
 
              <td nowrap id="fuente2">&nbsp;</td>
               
            </tr>
            <tr>
              <td id="detallegrande3">
                  <?php 
			//OJO MIRAR SI ES MEJOS SACAR LAS BOLSAS DESDE TBL SELLADO O POR LOS METROSL
			 /*$anchoporc_sell=$row_ref_op['ancho_ref']; 
			 echo $bolsas_sell = bolsasAprox($metros_sell_final,$anchoporc_sell);*/
			 echo $bolsas_sell;
			  ?> </td>
              <td id="detallegrande3"><?php 
 			  $costobolsil=($COSTOHORAKILO/$pesoMbols); 
 			   redondear_decimal_operar($costobolsil); ?>                <a href="proceso_empleados_listado.php" target="_blank" style="text-decoration:underline; color:#000000" ><?php
	  //HORAS TRABAJADAS DE LA O.P
      $id_op=$row_ref_op['id_op'];
	  $sqloperario_sell="SELECT cod_empleado_r FROM `TblSelladoRollo` WHERE `id_op_r`= $id_op  ORDER BY `cod_empleado_r` ASC"; 
	  $resultoperario_sell=mysql_query($sqloperario_sell); 
	  $numoper_sell=mysql_num_rows($resultoperario_sell); 

      $id_op=$row_ref_op['id_op'];
	  $sqloper="SELECT `cod_empleado_r`, `turno_r`, MIN(`fechaI_r`) AS TIEMPOINI, MAX(`fechaF_r`) AS TIEMPOFIN, TIMEDIFF (`fechaF_r`, `fechaI_r`) AS TIEMPODIFE FROM `TblSelladoRollo` WHERE `id_op_r`= $id_op  GROUP BY `fechaI_r`, `cod_empleado_r` ASC"; 
      $operario = mysql_query($sqloper, $conexion1) or die(mysql_error());
      $row_operario = mysql_fetch_assoc($operario);    
      $const_mode_sell=0;
	  do{
      $oper_proc_sell = $row_operario['cod_empleado_r']; 
	  $oper_horas_sell = horadecimalUna($row_operario['TIEMPODIFE']);
 
	$sqlemp="SELECT (a.horasmes_reales) AS HORASMES,(a.diasmes_reales) AS MES,(a.horas_empleado) AS HORADIA,(b.sueldo_empleado) AS SUELDO,(b.aux_empleado) AS AUXILIO,(c.total) AS APORTES FROM 
empleado a 
LEFT JOIN TblProcesoEmpleado b ON a.codigo_empleado=b.codigo_empleado  
LEFT JOIN TblAportes c ON a.codigo_empleado=c.codigo_empl WHERE a.codigo_empleado=$oper_proc_sell";
	$resultemp=mysql_query($sqlemp);	
	$sueldo=mysql_result($resultemp,0,'SUELDO'); //sueldo del mes  
	$aux_trans=mysql_result($resultemp,0,'AUXILIO');//auxilio de transp
	$aportestotal=mysql_result($resultemp,0,'APORTES');//total de los aportes del empleado 
	$horasmes=mysql_result($resultemp,0,'HORASMES');//LO EQUIVALENTE A LAS HORAS QUE SE TRABAJAN EN UN MES REAL 186,6666667 
	$horasdia=mysql_result($resultemp,0,'HORADIA');//esto es 8 
	//NOVEDADES DEL MES DE LOS OPRARIOS DE EXTRUDER 
	$fechafin=($FECHA_NOVEDAD_SELL);//define las novedades de la consulta
	$sqlnovedad="SELECT SUM(recargos) as RECARGOS,SUM(festivos) as FESTIVOS FROM TblNovedades WHERE codigo_empleado=$oper_proc_sell AND fecha BETWEEN DATE_FORMAT('$fechafin', '%Y-%m-01') AND '$fechafin'";  
    $resultnovedad=mysql_query($sqlnovedad);
	$recargos=mysql_result($resultnovedad,0,'RECARGOS');//define si el operario trabajo en nocturna o no
	$festivos=mysql_result($resultnovedad,0,'FESTIVOS');//otros recargos
	
 	$valorhoraxoperSell  =  sueldoMes($sueldo,$aux_trans,$aportestotal,$horasmes,$horasdia,$recargos,$festivos); 
	$KilosxHora_sell=$KilosxHoraSELL;
    $const_mode_sell += $valorhoraxoperSell;//valor total hora de todos operarios en ex
	$horaOper_sell =  $const_mode_sell / $numoper_sell;//$numoper_sell promedio hora un operario
 		if($numoper_sell > 1)
		{$mod_s =  ($horaOper_sell)/$KilosxHora_sell;}
		else{
		$mod_s =  ($horaOper_sell * $oper_horas_sell)/$KilosxHora_sell;}
     } while ($row_operario = mysql_fetch_assoc($operario));
 		 redondear_entero_puntos($mod_s+$mod_demas);	   
	   ?>
                  </a><a href="costos_listado_gga.php" target="_blank" style="text-decoration:underline; color:#000000" >
                    <?php 
	 $cif_s =  ($costoUnHCif_sell/$KilosxHoraSELL);
	  redondear_entero_puntos($cif_s);//COSTO DE HORA SELLADA CON CIF	 
	  ?>
                    <?php 
	$gga_s  =  ($costoUnHGga_sell/$KilosxHoraSELL);
	 redondear_entero_puntos($gga_s);//COSTO DE HORA SELLADA CON CIF	
	  ?>
                    <?php 
	 $ggv_s =  ($costoUnHGgv_sell/$KilosxHoraSELL); 
	  redondear_entero_puntos($ggv_s); //COSTO DE HORA SELLADO CON CIF	
	  ?>
                    <?php 
	 $ggf_s =  ($costoUnHGgf_sell/$KilosxHoraSELL);
	 redondear_entero_puntos($ggf_s); //COSTO DE HORA EXTRUIDA CON CIF	
	  ?>
                    <?php echo $reproceso_sell; ?>                  </a></td>
              <td id="detallegrande3"><?php  
			  //regla de tres
  			 echo $metros_sell_final = bolsasAprox2($anchoporc,$bolsas_sell);
 			  /*$metros_sell = ($metros_imp - $metro_des_imp);
  			  $metro_des_sell = metrolineal($kilos_desp_sell,$kgmontSell,$reproceso_sell,$metros_sell,$KILOSREALESIMP);
			 echo $metros_sell_final = ($metros_sell - $metro_des_sell);*/
 			  ?></td>
              <td id="detallegrande3" nowrap><?php 
		  $tipolam=$row_ref_op['tipoLamina_ref'];
		  $sqlinsumos="SELECT descripcion_insumo FROM insumo WHERE id_insumo='$tipolam'"; 
		  $resultinsumos=mysql_query($sqlinsumos); 
		  $numinsumos=mysql_num_rows($resultinsumos); 
		  if($numinsumos >= '1'){
			 $tipoBols=mysql_result($resultinsumos,0,'descripcion_insumo');
			  }?>
$
  <?php 
	   //COSTO SELLADO
	  $id_op=$row_ref_op['id_op'];
	  $sqlcostoMP="SELECT Tbl_reg_kilo_producido.id_rpp_rp, insumo.valor_unitario_insumo AS VALORMETRO, SUM(Tbl_reg_kilo_producido.valor_prod_rp) AS METROoKILO FROM  insumo,Tbl_reg_kilo_producido WHERE insumo.id_insumo = Tbl_reg_kilo_producido.id_rpp_rp AND Tbl_reg_kilo_producido.id_proceso_rkp='4' AND Tbl_reg_kilo_producido.op_rp = $id_op AND Tbl_reg_kilo_producido.id_rpp_rp NOT IN (1406,1407,1655,1656,1657)"; //1407 es el bolsillo
	  $resultcostoMP=mysql_query($sqlcostoMP); 
	  $numcostoMP=mysql_num_rows($resultcostoMP); 
	  $row_valoresMP = mysql_fetch_assoc($resultcostoMP);
	  $contValorS=0;
 	  do{
		  $valorMP = $row_valoresMP['VALORMETRO'];
		  $cantMP = $row_valoresMP['METROoKILO'];
 		  $tipo = $row_ref_op['adhesivo_ref'];
 		  if($tipo=='HOT MELT')//EVALUO QUE SEA HOT PORQ ES KILO
          {
		  $sqlliner="SELECT `id_insumo`,`valor_unitario_insumo` FROM `insumo` WHERE `id_insumo` = 1559";//1559 es el liner
		  $resultliner=mysql_query($sqlliner);
          $valorLiner = mysql_result($resultliner,0,'valor_unitario_insumo');
		  $precioliner = $metros_sell_final * $valorLiner;//valor liner por metro lineal
 		  $preciohot=$cantMP * $valorMP;//multiplico kilos adhesivo hot por el precio  
		  $contValorS = ($precioliner+$preciohot);// El precio total de hotmelt
		  }else{ 
 		  $valorcinta = $metros_sell_final * $valorMP;//esto pasa a dinero
		  //$contValorS = $valorcinta / $KILOSREALESREF;//se distribuye el kilo hot a los kilos de bolsa y esto es lo que le corresponde a un kilo en sellado
		  $contValorS = $valorcinta;//si es cinta
		  //DEBO PASAR A KILOS 
 		}		  
    } while ($row_valoresMP = mysql_fetch_assoc($resultcostoMP));
	 echo redondear_decimal_operar($contValorS+$costobolsil); 
	 //COSTO MATERIA PRIMA EN IMPRESION POLIETILENO 
	 $kiloMPSELL = $COSTOHORAKILOIMP;//$COSTOHORAKILOIMP PORQUE VA ACUMULANDO EL ANTERIOR PROCESO
	 // echo redondear_entero_puntos($kiloMPIMP);
	 ?></td>
              <td nowrap id="detallegrande3">$
                <?php
				
				 $COSTOHORAKILOSELL = ($costobolsil+$mod_s+$cif_s+$gga_s+$ggv_s+$ggf_s+$kiloMPSELL); 
				 echo redondear_entero_puntos($COSTOHORAKILOSELL); ?></td>
              <td id="detallegrande3"><?php 
	   //COSTO MATERIA PRIMA 
 redondear_entero_puntos($costobolsil+$mod_s+$cif_s+$gga_s+$ggv_s+$ggf_s+$contValorS); //COSTO TOTAL DE MP EN SELLADO		  		
	  ?>
$
  <?php
			  $costo_reproc = $reproceso_sell * $COSTOHORAKILOSELL;
			  $totalsellado=($KILOSREALESREF*$COSTOHORAKILOSELL)+$costo_reproc;
			  echo redondear_entero_puntos($totalsellado)?></td>
              <td id="detallegrande3" nowrap>&nbsp;</td>
               
            </tr> 
            <tr>
              <td id="abajo1">&nbsp;</td>
              <td colspan="2" id="abajo1"><?php echo  $tipoBols; ?></td>
              <td id="abajo1">&nbsp;</td>
              <td id="abajo1">valor Und: <?php echo $valorMP+$valorLiner;?></td>
              <td id="detallegrande6">&nbsp;</td>
              <td id="fuente1">&nbsp;</td>
               
            </tr>
            <tr>
              <td id="detallegrande6">&nbsp;</td>
              <td colspan="5" id="fuente1"><span style="color:#F00"><em>
                <?php if ($tHoras_sell==''){echo "* Falta Liquidar en Sellado  ";}
				if ($TiempomeSell==''){echo "* Falta horas por Mes, este proceso puede estar en el siguiente mes o falta agregar la distribucion de horas del mes actual ";} 
				if ($KilosxHoraSELL==''){echo "* Falta liquidar kilos x hora 0.00";} ?>
              </em></span></td>
              <td id="fuente1">&nbsp;</td>
               
            </tr>
            <tr>
              <td id="detallegrande7">&nbsp;</td>
              <td id="fuente10">&nbsp;</td>
              <td colspan="4" id="titulo3">RESUMEN</td>
              <td id="titulo3">&nbsp;</td>
              
            </tr>
            <tr>
              <td id="fuente1">&nbsp;</td>
              <td id="fuente1">&nbsp;</td>
              <td nowrap id="fuente1">COSTO BOLSA</td>
              <td nowrap id="fuente1">PRECIO B. O.C</td>
              <td nowrap id="fuente1">UTILIDAD </td>
              <td nowrap id="fuente1">RENTABILIDAD </td>
              <td id="fuente1">&nbsp;</td>
              
            </tr>
            <tr>
              <td id="fuente6">&nbsp;</td>
              <td id="fuente6">&nbsp;</td>
              <td nowrap id="detallegrande1">$
                <?php  
	//CONTROLA LA RENTABILIDAD CUANDO UNO DE LOS PROCESOS NO EXISTE
  	$costoBolsa_sell=$totalsellado/$bolsas_sell;
	$costoBolsa_imp=$totalimpresion/$bolsas_imp;
	$costoBolsa_ext=$totalextruder/$bolsas_ext;
	if($costoBolsa_sell=='0'){$costoBolsa=$totalimpresion/$bolsas_imp;}else 
	if($costoBolsa_imp=='0'){$costoBolsa=$totalextruder/$bolsas_ext;}
    else {$costoBolsa=$totalsellado/$bolsas_sell;}
			
			 echo redondear_decimal_operar($costoBolsa+$COSTOCINTA);?></td>
              <td nowrap id="detallegrande1">$
                <?php  
                  $id_op=$row_ref_op['id_op'];
              	  $sqlcotiz="SELECT Tbl_items_ordenc.str_unidad_io AS medida,Tbl_items_ordenc.int_precio_io AS precio FROM Tbl_orden_produccion,Tbl_items_ordenc WHERE Tbl_orden_produccion.id_op = $id_op  AND Tbl_orden_produccion.int_cod_ref_op = Tbl_items_ordenc.int_cod_ref_io ORDER BY Tbl_items_ordenc.fecha_entrega_io DESC LIMIT 1";				
				  $resultcotiz=mysql_query($sqlcotiz); 
				  $numcotiz=mysql_num_rows($resultcotiz); 
				  $medida=mysql_result($resultcotiz,0,'medida');
				  $precioCotiz=mysql_result($resultcotiz,0,'precio');
                  $undPaquetes=$row_ref_op['int_undxpaq_op'];//unidad x paquetes
				  
				$precioCotiz_sell = unidadMedida($medida,$precioCotiz,$undPaquetes);
				echo $precioCotiz_sell;?></td>
              <td nowrap id="detallegrande1">$
                <?php  $utilidadSell= ($precioCotiz_sell-$costoBolsa);echo redondear_decimal_operar($utilidadSell);?></td>
              <td nowrap id="detallegrande1"><?php 
			 $rentabil = porcentaje2($precioCotiz_sell,$utilidadSell,0);
			 if($rentabil < 0) {?>
                <h4 style="color:#F00"> <?php echo $rentabil;?> % </h4>
                <?php }else{?>
                <?php echo $rentabil;?> %
                <?php } ?></td>
              <td id="fuente6">&nbsp;</td>
              
            </tr>
            <tr>
              <td colspan="12" id="titulo"> </td>
            </tr>
            <tr>
              <td colspan="12" id="titulo2"><!--<input name="submit" type="submit"value="VISTA" />--></td>
            </tr>
          </table></td>
        </tr>
        </table>
        <input type="hidden" name="MM_insert" value="form1">
      </form></td>
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

mysql_free_result($ultimo);

mysql_free_result($referencias);

mysql_free_result($medidas);
?>