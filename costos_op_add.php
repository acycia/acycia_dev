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
$query_ref_op = sprintf("SELECT * FROM Tbl_orden_produccion,Tbl_referencia,Tbl_egp WHERE  Tbl_orden_produccion.id_op='%s' AND Tbl_orden_produccion.id_ref_op=Tbl_referencia.id_ref AND Tbl_referencia.n_egp_ref=Tbl_egp.n_egp ",$colname_ref);
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
    <tr align="center">
      <td><div> <b class="spiffy"> <b class="spiffy1"><b></b></b> <b class="spiffy2"><b></b></b> <b class="spiffy3"></b> <b class="spiffy4"></b> <b class="spiffy5"></b></b>
          <div class="spiffy_content">
            <table id="tabla1">
              <tr>
                <td colspan="2" align="center"><img src="images/cabecera.jpg"></td>
              </tr>
              <tr>
                <td id="nombreusuario"><?php echo $row_usuario['nombre_usuario']; ?></td>
                <td id="cabezamenu"><ul id="menuhorizontal">
                    <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                    <li><a href="menu.php">MENU PRINCIPAL</a></li>
                    <li><a href="costos_generales.php">GESTION COSTOS</a></li>
                  </ul></td>
              </tr>
              <tr>
                <td align="center" colspan="2" id="linea1"><form action="<?php echo $editFormAction; ?>" method="post" name="form1" >
                    <table id="tabla2">
                      <tr>
                        <td id="fuente1"><table id="tabla3">
                            <tr>
                              <td nowrap colspan="6" id="titulo2">COSTOS ORDEN DE PRODUCCION </td>
                            </tr>
                            <tr>
                              <td colspan="4" id="fuente1"><h1 style="color:#F00">N: <?php echo $row_ref_op['id_op']; ?></h1></td>
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
                              <td colspan="6" id="fuente3"><img src="images/impresor.gif" onClick="window.print();" style="cursor:hand;" alt="IMPRIMIR" /><a href="costos_producto_terminado.php"> <img src="images/opciones.gif" alt="LISTADO P.O COSTO" border="0" style="cursor:hand;"/></a> <a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a>
                                <input name="id_op" type="hidden" id="id_op" value="<?php echo $row_ref_op['id_op']; ?>" /></td>
                            </tr>
                            <tr  id="tr1">
                              <td colspan="6" id="titulo2">DESCRIPCION O.P</td>
                            </tr>
                            <tr>
                              <td id="fuente2"> FECHA O.P</td>
                              <td id="fuente2">REF-VERSION</td>
                              <td id="fuente2">TIPO BOLSA</td>
                              <td id="fuente2">UND X PAQ</td>
                              <td id="fuente2">KILOS P.</td>
                              <td id="fuente2">UND P.</td>
                            </tr>
                            <tr>
                              <td id="detalle2"><?php echo $row_ref_op['fecha_registro_op']; ?></td>
                              <td id="detalle2"><?php echo $row_ref_op['int_cod_ref_op']; ?>- <?php echo $row_ref_op['version_ref_op']; ?></td>
                              <td id="detalle2"><?php echo $row_ref_op['str_tipo_bolsa_op']; ?></td>
                              <td nowrap id="detalle2"><?php echo $row_ref_op['int_undxpaq_op'];?></td>
                              <td nowrap id="detalle2"><?php echo $row_ref_op['int_kilos_op']; ?></td>
                              <td nowrap id="detalle2"><?php echo redondear_entero_puntos($row_ref_op['int_cantidad_op']); ?></td>
                            </tr>
                            <tr>
                              <td id="detalle1">TIPO CLIENTE:</td>
                              <td nowrap id="detalle1"><?php 
							  $id_c=$row_ref_op['int_cliente_op'];
							  $sqlnclie="SELECT nombre_c,tipo_c FROM cliente WHERE id_c='$id_c'"; 
							  $resultclie=mysql_query($sqlnclie); 
							  $numclie=mysql_num_rows($resultclie); 
							  if($numclie >= '1') 
							  { $cliente=mysql_result($resultclie,0,'nombre_c');
							  $tipo=mysql_result($resultclie,0,'tipo_c'); echo $tipo;  }
							  ?></td>
                              <td  nowrap id="detalle1">CLIENTE:</td>
                              <td colspan="3"  nowrap id="detalle1"><?php echo $cliente;?></td>
                            </tr>
                            <tr>
                              <td colspan="6" id="fuente2">IDENTIFICACION DE LA REFERENCIA</td>
                            </tr>
                            <tr>
                              <td id="fuente2">MATERIAL</td>
                              <td colspan="2" id="fuente2">PRESENTACION</td> 
                              <td id="fuente2">TRATAMIENTO</td>
                              <td id="fuente2">ANCHO</td>
                              <td id="fuente2">LARGO</td>
                            </tr>
                            <tr>
                              <td id="detalle2"><?php echo $row_ref_op['str_matrial_op']; ?></td>
                              <td colspan="2" id="detalle2"><?php echo $row_ref_op['str_presentacion_op']; ?></td> 
                              <td id="detalle2"><?php echo $row_ref_op['str_tratamiento_op']; ?></td>
                              <td id="detalle2"><?php echo $anchoporc=$row_ref_op['ancho_ref']; ?></td>
                              <td id="detalle2"><?php echo $row_ref_op['largo_ref']; ?></td>
                            </tr>
                            <tr>
                              <td nowrap id="fuente2">SOLAPA</td>
                              <td nowrap id="fuente2">FUELLE</td>
                              <td nowrap id="fuente2">CALIBRE BOLSA</td>
                               <td nowrap id="fuente2">CALIBRE BOLS.</td>
                              <td nowrap id="fuente2">MILLAR BOLSA</td>
                              <td nowrap id="fuente2">MILLAR BOLSI.</td>
                            </tr>
                            <tr>
                              <td id="detalle2"><?php echo $row_ref_op['solapa_ref']; ?></td>
                              <td id="detalle2"><?php echo $row_ref_op['N_fuelle']; ?></td>
                              <td id="detalle2"><?php echo ($row_ref_op['calibre_ref']); ?></td>
                               <td id="detalle2"><?php if($row_ref_op['calibreBols_ref']==''){ echo $calibreBols="0";}else{echo $calibreBols=$row_ref_op['calibreBols_ref'];} ?></td>
                              <td id="detalle2"><?php echo ($row_ref_op['peso_millar_ref']); ?></td>
                              <td id="detalle2"><?php 
							 $pesoMbols = millarBolsillo($row_ref_op['ancho_ref'],$row_ref_op['largo_ref'],$calibreBols) ; //peso de un kilo de bolsillo
 							   ?>
                              <?php echo redondear_decimal_operar($pesoMbols); ?></td>
                            </tr>
                            <tr>
                              <td id="fuente2">TRASLAPE</td>
                              <td id="fuente2">ADHESIVO</td>
                              <td colspan="2" id="fuente2">TIPO ADHESIVO</td>
                              <td id="fuente2">N&deg; TINTAS </td>
                              <td id="fuente2">&nbsp;</td>
                            </tr>
                            <tr>
                              <td id="detalle2"><?php  if($row_ref_op['B_cantforma']==''){echo "0";}else{echo $row_ref_op['B_cantforma'];} ?></td>
                              <td id="detalle2"><?php echo $row_ref_op['adhesivo_ref']; ?></td>
                              <td colspan="2" id="detalle2"><?php 
							    $tipo_insumo=$row_ref_op['tipoCinta_ref'];
								$sqltipo="SELECT descripcion_insumo FROM insumo WHERE id_insumo = $tipo_insumo";
								$resultipo= mysql_query($sqltipo);
								$numtipo= mysql_num_rows($resultipo);
								if($numtipo >='1') { $tipo_insumo=mysql_result($resultipo,0,'descripcion_insumo'); }
								echo $tipo_insumo;  ?></td>
                              
                              <td id="detalle2"><?php echo $row_ref_op['impresion_ref']; ?></td>
                              <td id="detalle2">&nbsp;</td>
                            </tr>
                            <tr>
                              <td colspan="3" id="fuente7" valign="top">&nbsp;</td>
                              <td id="fuente7" colspan="3" valign="top">&nbsp;</td>
                            </tr>
                            <tr>
                              <td colspan="3" id="fuente1" valign="top"><fieldset>
                                  <legend id="dato1">PROCESO EXTRUSION</legend>
                                  <table >
                                    <tr id="tr1">
                                      <td colspan="3" id="titulo1"><strong>
                                        <?php  
 	  //CANTIDAD KILOS EN EXTRUDER
		$id_op=$row_ref_op['id_op'];
 		$query_extrusion ="SELECT SUM(int_kilos_prod_rp) AS kilosext, SUM(int_metro_lineal_rp) AS metrosext FROM Tbl_reg_produccion WHERE id_op_rp='$id_op' AND id_proceso_rp='1' ORDER BY id_rp DESC";
	    $resultextruder=mysql_query($query_extrusion); 
	    $numextruder=mysql_num_rows($resultextruder); 
	    if($numextruder >= '1') 
	    {
 		$kilos_ex=mysql_result($resultextruder,0,'kilosext');//kilos liquidados netos
		$metros_ext=mysql_result($resultextruder,0,'metrosext'); }else{ $kilos_ex = "0";//metros liquidados netos sin desperdicio
		}
 	  //COSTO MATERIAS PRIMAS
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
	      $kiloMPEXT = ($contValor/$contCant); //COSTO KILO DE MP 
	   //FIN  
 	  //FECHA GENERAL DE CIERRE EXTRUDER
  	  $id_op=$row_ref_op['id_op'];
	  $sqlrollo="SELECT COUNT(DISTINCT rollo_r) AS rollos, DATE_FORMAT(fechaI_r,'%Y-%m-%d') AS FECHA, SUM(metro_r) AS metros,SUM(kilos_r) AS kilos FROM TblExtruderRollo WHERE id_op_r='$id_op' ORDER BY fechaI_r ASC"; 
	  $resultrollo=mysql_query($sqlrollo); 
	  $numrollo=mysql_num_rows($resultrollo); 
	  if($numrollo >= '1') 
	  { $rollo=mysql_result($resultrollo,0,'rollos');
	  
 		$FECHA_NOVEDAD_EXT=mysql_result($resultrollo,0,'FECHA');
 		echo quitarDia($FECHA_NOVEDAD_EXT);	
		} 
 	 //FACTOR ANUAL
 		$query_factor = "SELECT * FROM TblFactorP ORDER BY fecha_fp DESC LIMIT 1";
		$factor = mysql_query($query_factor, $conexion1);
		$totalRows_factor = mysql_num_rows($factor);
		  if($totalRows_factor >= '1') 
		  { 
		  $horasmes_ext=mysql_result($factor,0,'hora_lab_fp');//186,666666666667
		  }	
		 
 	?>
                                        </strong></td>
                                    </tr>
                                    <tr>
                                      <td nowrap id="fuente2">ENTRADA-KG</td>
                                      <td nowrap id="fuente2">DESPERDICIO-KG</td>
                                      <td nowrap id="fuente2">% DESP.</td>
                                    </tr>
                                    <tr>
                                      <td id="detallegrande3"><?php echo redondear_decimal_operar($kilos_ex);?></td>
                                      <td id="detallegrande3"><?php 
	  //desperdicio general diferende
	  $id_op=$row_ref_op['id_op'];
	  $sqldesp="SELECT SUM(valor_desp_rd) AS kgDespe FROM Tbl_reg_desperdicio WHERE op_rd='$id_op' AND id_proceso_rd='1'"; 
	  $resultdesp=mysql_query($sqldesp); 
	  $numdesp=mysql_num_rows($resultdesp); 
	  if($numdesp >= '1') 
	  { $kilos_desp=mysql_result($resultdesp,0,'kgDespe'); echo  redondear_decimal_operar($kilos_desp); } else {echo $kilos_desp="0";}
       $KILOSREALESEXT = ($kilos_ex-($kilos_desp)); ?></td>
                                      <td id="detallegrande3"><?php 
				 $despPorcExt=($kgmont+$kilos_desp);
				 echo $porExt = regladetres($despPorcExt,100,$kilos_ex);
				  ?>
                                        %</td>
                                    </tr>
                                    <tr>
                                      <td nowrap id="fuente2">TIEMPO</td>
                                      <td nowrap id="fuente2">KILO / HORA</td>
                                      <td nowrap id="fuente2">METRO -LINEAL </td>
                                    </tr>
                                    <tr>
                                      <td id="detallegrande3"><a href="costos_listado_ref_xproceso_tiempos2.php?id_op=<?php echo $row_ref_op['id_op']; ?>" target="_blank" style="text-decoration:underline; color:#000000" ><strong> 
      <?php 	
	  $id_op=$row_ref_op['id_op'];
	  $sqlexm="SELECT SUM(valor_tiem_rt) AS horasM FROM Tbl_reg_tiempo WHERE op_rt='$id_op' AND id_proceso_rt='1'"; 
	  $resultexm=mysql_query($sqlexm); 
	  $numexm=mysql_num_rows($resultexm); 
	  if($numexm >= '1') 
	  { $horasM_ex=mysql_result($resultexm,0,'horasM');
	    $minutoM_exD = minutoaDecimal($horasM_ex);//no va en la o.p
	  }else{echo "0";}
	  					 
	  $id_op=$row_ref_op['id_op'];
	  $sqlex="SELECT `cod_empleado_r`, TIMEDIFF(MAX(`fechaF_r`), MIN(`fechaI_r`)) AS TIEMPODIFE FROM `TblExtruderRollo` WHERE `id_op_r`= '$id_op' GROUP BY `fechaI_r`,`cod_empleado_r` ASC"; 
	  $resultex=mysql_query($sqlex); 
	  $numex=mysql_num_rows($resultex); 
	  if($numex >= '1') 
	  { 
	  while ($row=mysql_fetch_array($resultex)) {
	  $horasenextr=$row['TIEMPODIFE'];//TIEMPO SEGUN FECHA Y CODIGO 
	  $totaltiempoEXT = horadecimalUna($horasenextr);
	  $tHoras_ex += $totaltiempoEXT; 
	  } 
 	    $horasMSinMuertos=horadecimalUna($tHoras_ex);//hora a decimal para operar
	    echo $horasM_exDec = number_format($horasMSinMuertos-$minutoM_exD, 2, '.', '');//AL TIEMPO TOTAL LE RESTO EL TIEMPO MUERTO YA QUE EL DE PREPARACION ES EL QUE SE DISTRIBUYE PARA TODAS LAS OP DEL MES
	  }else{echo $horasM_exDec="0";}

	  $fecha_general = quitarDia($FECHA_NOVEDAD_EXT);
 	  $sqlgeneral="SELECT * FROM `TblDistribucionHoras`  ORDER BY `fecha` DESC LIMIT 1";//ORDER BY fecha DESC
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
      <td id="detallegrande3"><a href="produccion_registro_extrusion_vista.php?id_op=<?php echo $id_op; ?>" target="_blank" style="text-decoration:underline; color:#000000" ><strong>
                                        <?php 	  
      $id_op=$row_ref_op['id_op'];
	  $sqlexKH="SELECT COUNT(id_op_rp) AS ITEMS,SUM(int_kilosxhora_rp) AS KILOHORA FROM Tbl_reg_produccion WHERE id_op_rp='$id_op' AND id_proceso_rp='1'"; 
	  $resultexKH=mysql_query($sqlexKH); 
	  $numexKH=mysql_num_rows($resultexKH); 
	  if($numexKH >= '1') 
	  {$ITEMSEXT=mysql_result($resultexKH,0,'ITEMS'); 
	  $KilosxHoraEX=mysql_result($resultexKH,0,'KILOHORA');
	  $KilosxHoraEXT=($KilosxHoraEX/$ITEMSEXT);
	  //echo redondear_decimal_operar($KilosxHoraEXT);
	  }
	   echo $KilosxHoraEXT =redondear_decimal_operar($kilos_ex/$horasM_exDec);
	   //echo redondear_decimal_operar($KilosxHoraEXT);
	  ?>
                                        </strong></a></td>
                                      <td id="detallegrande3" nowrap><?php 
			  //regla de tres
			   echo $metros_ext; 
  			   $metro_des_ext = metrolineal($kilos_desp,$kgmont,0,$metros_ext,$kilos_ex);
			  ?></td>
                                    </tr>
                                    <tr>
                                      <td nowrap id="fuente2">VALOR INSUMOS</td>
                                      <td nowrap id="fuente2">COSTO TOTAL</td>
                                      <td id="fuente2" nowrap>COSTO KILO</td>
                                    </tr>
                                    <tr>
                                      <td id="detallegrande3">$
                                        <?php 
	  echo redondear_entero_puntos($kiloMPEXT);//COSTO KILO DE MP	
	   ?></td>
                                      <td id="detallegrande3">$
                                        <?php 
	  $anchoporc=$row_ref_op['ancho_ref']; 
	  $bolsas_ext = bolsasAprox($metros_ext,$anchoporc); 
	  redondear_entero_puntos($row_ref_op['kls_sellado_bol_op']); 			  
    //PARA TODOS LOS PROCESOS
	//SUELDOS DE TODOS LOS EMPLEADOS FUERA DE PROCESO 
	$sqlbasico="SELECT COUNT(a.codigo_empleado) AS operarios,(a.horas_empleado) AS HORADIA, SUM(b.sueldo_empleado) AS SUELDO, SUM(b.aux_empleado) AS AUXILIO, SUM(c.total) AS APORTES
FROM empleado a 
LEFT JOIN TblProcesoEmpleado b ON a.codigo_empleado=b.codigo_empleado  
LEFT JOIN TblAportes c ON a.codigo_empleado=c.codigo_empl 
WHERE b.estado_empleado='1' AND a.tipo_empleado NOT IN(4,5,6,7,8,9,10)";//NOT IN(4,5,6,7,8,9,10) son los que estan fuera de los procesos SE AGREGO b.estado_empleado='1' AND
	$resultbasico=mysql_query($sqlbasico);
	$sueldo_bas=mysql_result($resultbasico,0,'SUELDO'); //sueldos del mes 
	$auxilio_bas=mysql_result($resultbasico,0,'AUXILIO'); //auxilios trans del mes 	  
	$aportes_bas=mysql_result($resultbasico,0,'APORTES'); //aportes del mes 
 	//$horasmes_bas=mysql_result($resultbasico,0,'HORASMES');//LO EQUIVALENTE A LAS HORAS QUE SE TRABAJAN EN UN MES REAL 186,6666667 SE ENCUENTRA EN FACTOR
	$operarios_bas=mysql_result($resultbasico,0,'operarios');//CANTIDAD DE OPERARIOS 
	$horasdia_bas=mysql_result($resultbasico,0,'HORADIA');//esto es 8 
	 	 
	 //NOVEDAD DE TODOS LOS EMPLEADOS FUERA DE PROCESO 
 	$sqlnovbasico="SELECT SUM(b.pago_acycia) as pago,SUM(b.horas_extras) as extras,SUM(b.recargos) as recargo,SUM(b.festivos) AS festivos 
FROM empleado a 
LEFT JOIN TblNovedades b ON a.codigo_empleado=b.codigo_empleado 
WHERE a.tipo_empleado NOT IN(4,5,6,7,8,9,10) AND b.fecha BETWEEN DATE_FORMAT('$FECHA_NOVEDAD_EXT', '%Y-%m-01') AND DATE_FORMAT('$FECHA_NOVEDAD_EXT', '%Y-%m-31')";//NOT IN(4,5,6,7,8,9,10) son los que estan fuera de los procesos
	$resultnovbasico=mysql_query($sqlnovbasico);	
	$pago_novbasico=mysql_result($resultnovbasico,0,'pago'); 
	$extras_novbasico=mysql_result($resultnovbasico,0,'extras');  
	$recargo_novbasico=mysql_result($resultnovbasico,0,'recargo');
	$festivo_novbasico=mysql_result($resultnovbasico,0,'festivos');
	//OPERO TODOS LOS SUELDOS ETC, PARA SACAR EL COSTO HORA DE LOS FUERA DE PROCESOS
	
 	$valorhoraxoperExtDemas = sueldoMes($sueldo_bas,$auxilio_bas,$aportes_bas,$horasmes_ext,$horasdia_bas,$recargo_novbasico,$festivo_novbasico);
	$distribuir = ($valorhoraxoperExtDemas)/3;//3 son los procesos principales
   	$horaOper =  $distribuir;// promedio hora de todos los operario fuera de los procesos fijos      
	$mod_demas = ($horaOper)/$KilosxHoraEXT; //costo kilo/hora de los demas
   //FIN COSTO EMPLEADOS FUERA DE PROCESO  
   
   
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
  	//SUELDOS, AUXI TRANS, APORTES	
	$sqlemp="SELECT (a.horas_empleado) AS HORADIA,(b.sueldo_empleado) AS SUELDO,(b.aux_empleado) AS AUXILIO,(c.total) AS APORTES FROM 
empleado a 
LEFT JOIN TblProcesoEmpleado b ON a.codigo_empleado=b.codigo_empleado  
LEFT JOIN TblAportes c ON a.codigo_empleado=c.codigo_empl WHERE a.codigo_empleado=$oper_proc_ext";
	$resultemp_EXT=mysql_query($sqlemp);	
	$sueldo_EXT=mysql_result($resultemp_EXT,0,'SUELDO'); //sueldo del mes por cada uno
	$aux_trans_EXT=mysql_result($resultemp_EXT,0,'AUXILIO');//auxilio de transp por cada uno
	$aportestotal_EXT=mysql_result($resultemp_EXT,0,'APORTES');//total de los aportes del empleado 
	//$horasmes=mysql_result($resultemp,0,'HORASMES');//LO EQUIVALENTE A LAS HORAS QUE SE TRABAJAN EN UN MES REAL 186,6666667 
	$horasmes_EXT=$horasmes_ext;//186,6666667  DESDE FACTOR
	$horasdia=mysql_result($resultemp_EXT,0,'HORADIA');//esto es 8 
	
	//NOVEDADES DEL MES DE LOS OPERARIOS DE EXTRUDER AQUI DESCARTO SI TRABAJO DE NOCHE O EXTRAS ETC
  	$sqlnovedad="SELECT SUM(recargos) as RECARGOS,SUM(festivos) as FESTIVOS FROM TblNovedades WHERE codigo_empleado='$oper_proc_ext' AND fecha BETWEEN DATE_FORMAT('$FECHA_NOVEDAD_EXT', '%Y-%m-01') AND DATE_FORMAT('$FECHA_NOVEDAD_EXT', '%Y-%m-31')";  
    $resultnovedad_EXT=mysql_query($sqlnovedad);
	$recargos_EXT=mysql_result($resultnovedad_EXT,0,'RECARGOS');//define si el operario trabajo en nocturna o no
	$festivos_EXT=mysql_result($resultnovedad_EXT,0,'FESTIVOS');//otros recargos
	//OPERO TODOS LOS SUELDOS ETC, PARA SACAR EL COSTO HORA DE LOS DE LOS PROCESOS PRINCIPALES
 	$valorhoraxoperExt = sueldoMes($sueldo_EXT,$aux_trans_EXT,$aportestotal_EXT,$horasmes_EXT,$horasdia,$recargos_EXT,$festivos_EXT);
	$KilosxHora = $KilosxHoraEXT;//$KilosxHoraEXT;//kilos por hora
    $const_mode += $valorhoraxoperExt;//valor total hora de todos operarios en extr
	$horaOper =  $const_mode / $numoper;//$numoper promedio hora un operario
     if($numoper > 1)//$mod_demas la suma de los demas operarios en general
     {$mod_e =  (($horaOper)/$KilosxHora)+$mod_demas;}
	 else{
	 $mod_e =  (($horaOper * $oper_horas_ext)/$KilosxHora)+$mod_demas;}  
     }while ($row_operario = mysql_fetch_assoc($operario));//PARA CADA EMPLEADO
  	 redondear_decimal($mod_e); 
  	 $cif_e = ($costoUnHCif_ext/$KilosxHoraEXT); 
	 redondear_entero_puntos($cif_e);//CIF HORA
 	 $gga_e = ($costoUnHGga_ext/$KilosxHoraEXT); 
	 redondear_entero_puntos($gga_e);
 	 $ggv_e = ($costoUnHGgv_ext/$KilosxHoraEXT);
	 redondear_entero_puntos($ggv_e); //GGV HORA	
 	 $ggf_e = ($costoUnHGgf_ext/$KilosxHoraEXT);
	 redondear_entero_puntos($ggf_e); //GGF HORA
	 		
     $COSTOHORAKILO = ($mod_e+$cif_e+$gga_e+$ggv_e+$ggf_e+$kiloMPEXT);//dentro de $kiloMPEXT ya esta el desperdicio
	 $totalextruder=($kilos_ex * $COSTOHORAKILO);
	 echo redondear_entero_puntos($totalextruder);
 	 ?></td>
                                      <td id="detallegrande3" nowrap>$
                                        <?php 
	  //COSTO MATERIA PRIMA
  	  echo redondear_entero_puntos($COSTOHORAKILO);
	  //CONTROLA LA RENTABILIDAD CUANDO UNO DE LOS PROCESOS NO EXISTE
 	  $costoBolsaExt=$totalextruder/$bolsas_ext;
	  redondear_decimal_operar($costoBolsaExt);  
      $id_op=$row_ref_op['id_op'];
      $sqlcotiz="SELECT Tbl_items_ordenc.trm AS trm,Tbl_items_ordenc.str_unidad_io,Tbl_items_ordenc.int_precio_io FROM Tbl_orden_produccion,Tbl_items_ordenc WHERE Tbl_orden_produccion.id_op = $id_op  AND Tbl_orden_produccion.int_cod_ref_op = Tbl_items_ordenc.int_cod_ref_io ORDER BY Tbl_items_ordenc.fecha_despacho_io DESC LIMIT 1";				
				  $resultcotiz=mysql_query($sqlcotiz); 
				  $numcotiz=mysql_num_rows($resultcotiz); 
				  $medida=mysql_result($resultcotiz,0,'str_unidad_io');
				  $precioCotiz=mysql_result($resultcotiz,0,'int_precio_io');
				  $trmCotiz=mysql_result($resultcotiz,0,'trm');
                  $undPaquetes=$row_ref_op['int_undxpaq_op'];//unidad x paquetes
				  
				$precioCotiz_ext = unidadMedida($medida,$precioCotiz,$undPaquetes,1);
				$precioCotiz_ext; 
			 $utilidadExt=($precioCotiz_ext-$costoBolsaExt);
			  redondear_decimal_operar($utilidadExt); 
			 porcentaje2($precioCotiz_ext,$utilidadExt,0);
			  ?></td>
                                    </tr>
                                    <tr>
                                      <td colspan="3" nowrap  id="fuente2"><?php 
									 // SI NO SE HA LIQUIDADO
									  $liquidoExtrusion = redondear_decimal_operar($KilosxHoraEXT);
									 if($liquidoExtrusion=='0.00') { ?>
                                        <a href="produccion_registro_extrusion_listado2.php?op=<?php echo $row_ref_op['id_op'];?>" title="Sin Liquidar" style="text-decoration:none; color:#F00"  target="_blank">Sin Liquidar En Extrusion</a>
                                      <?php }?></td>
                                    </tr>
                                  </table>
                                  
                              </fieldset></td>
                              <?php
/*					  $id_op=$row_ref_op['id_op'];
					  $sqlrollo="SELECT rollo_r FROM TblImpresionRollo WHERE id_op_r='$id_op'"; 
					  $resultrollo=mysql_query($sqlrollo); 
					  $numrollo=mysql_num_rows($resultrollo); */
					  if($row_ref_op['impresion_ref'] >= '1')  //define si tiene o no impresion
					  {
					  ?>
                              <td id="fuente1" colspan="3" valign="top"><fieldset>
                                  <legend id="dato1">PROCESO IMPRESION</legend>
                                  <table>
                                    <tr id="tr1">
                                      <td colspan="3" id="titulo1"><strong>
                                        <?php
	  $id_op=$row_ref_op['id_op'];
	  $sqlrollo="SELECT COUNT(DISTINCT rollo_r) AS rollos, DATE_FORMAT(fechaI_r,'%Y-%m-%d') AS FECHA, SUM(metro_r) AS metros_imp, SUM(kilos_r) AS kilos FROM TblImpresionRollo WHERE id_op_r='$id_op' ORDER BY fechaI_r ASC"; 
	  $resultrollo=mysql_query($sqlrollo); 
	  $numrollo=mysql_num_rows($resultrollo); 
	  if($numrollo >= '1') 
	  {$rollo_imp=mysql_result($resultrollo,0,'rollos');
	   //$metros_imp=mysql_result($resultrollo,0,'metros_imp');
	   
	   $kilos_imp=mysql_result($resultrollo,0,'kilos');
	   $FECHA_NOVEDAD_IMP=mysql_result($resultrollo,0,'FECHA');
	   echo quitarDia($FECHA_NOVEDAD_IMP);
	   $KILOSREALESEXT; 
	   }else {$KILOSREALESEXT = "0";}
	   $id_op=$row_ref_op['id_op'];
	   $sqlimp="SELECT SUM(valor_prod_rp) AS kge FROM Tbl_reg_kilo_producido WHERE op_rp='$id_op' AND id_proceso_rkp='2' "; 
	   $resultimp=mysql_query($sqlimp); 
	   $numimp=mysql_num_rows($resultimp); 
	   if($numimp >= '1') 
	   {$kilos_impTinta=mysql_result($resultimp,0,'kge');}else {$kilos_impTinta = "0";}
	   //FACTOR ANUAL
 		$query_factor = "SELECT * FROM TblFactorP ORDER BY fecha_fp DESC LIMIT 1";
		$factor = mysql_query($query_factor, $conexion1);
		$totalRows_factor = mysql_num_rows($factor);
		if($totalRows_factor >= '1') 
	    {$horasmes_imp=mysql_result($factor,0,'hora_lab_fp');}//186,666666666667		
	    ?>
                                        </strong></td>
                                    </tr>
                                    <tr>
                                      <td nowrap id="fuente2">ENTRADA-KG</td>
                                      <td nowrap id="fuente2">DESPERDICIO-KG</td>
                                      <td nowrap id="fuente2">% DESP.</td>
                                    </tr>
                                    <tr>
                                      <td id="detallegrande3"><?php echo redondear_decimal_operar($KILOSREALESEXT); ?></td>
                                      <td id="detallegrande3"><?php 
	  //desperdicio general
 	  $id_op=$row_ref_op['id_op'];
	  $sqldesp="SELECT SUM(valor_desp_rd) AS kgDespe FROM Tbl_reg_desperdicio WHERE op_rd='$id_op' AND id_proceso_rd='2'"; 
	  $resultdesp=mysql_query($sqldesp); 
	  $numdesp=mysql_num_rows($resultdesp); 
	  if($numdesp >= '1') 
	  {$kilos_desp_imp=mysql_result($resultdesp,0,'kgDespe');; echo  redondear_decimal_operar($kilos_desp_imp); } else {echo $kilos_desp_imp="0";}  
	  $KILOSREALESIMP =($KILOSREALESEXT-($kilos_desp_imp));//$KILOSREALESEXT con descuento desperdicio extruder 
 	  $metro_des_imp = metrolineal($kilos_desp_imp,0,0,$metros_ext,$kilos_ex);//igual q extruder menos el de impresion
 	  ?>
       </td>
      <td id="detallegrande3"><?php 
	  $id_op=$row_ref_op['id_op'];
	  $sqlimpm="SELECT SUM(valor_tiem_rt) AS horasM FROM Tbl_reg_tiempo WHERE op_rt='$id_op' AND id_proceso_rt='2'"; 
	  $resultimpm=mysql_query($sqlimpm); 
	  $numimpm=mysql_num_rows($resultimpm); 
	  if($numimpm >= '1') 
	  { $horasM_imp=mysql_result($resultimpm,0,'horasM');
		$minutoM_impD = minutoaDecimal($horasM_imp);//no va en la o.p
	  } 					  
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
 	   $horasMSinMuertos_imp=horadecimalUna($tHoras_imp);//hora a decimal para operar
 	   $horasM_impDec = number_format($horasMSinMuertos_imp-$minutoM_impD, 2, '.', '');
	  }else{echo $horasM_exDec="0";}
      /*$id_op=$row_ref_op['id_op'];
	  $sqlimpp="SELECT SUM(valor_prep_rtp) AS horasP FROM Tbl_reg_tiempo_preparacion WHERE op_rtp='$id_op' AND id_proceso_rtp='2'"; 
	  $resultimpp=mysql_query($sqlimpp); 
	  $numimpp=mysql_num_rows($resultimpp); 
	  if($numimpp >= '1') 
	  { $horasP_imp=mysql_result($resultimpp,0,'horasP'); $totalTiempo_imp=$horasP_imp;      }*/
 	  $fecha_general_imp = quitarDia($FECHA_NOVEDAD_IMP);
 	  $sqlgeneral="SELECT * FROM `TblDistribucionHoras`  ORDER BY `fecha` DESC LIMIT 1";
	  $resultgeneral= mysql_query($sqlgeneral);
	  $numgeneral= mysql_num_rows($resultgeneral);
	  if($numgeneral >='1')
	  { 
 	  $TiempomeImp =  mysql_result($resultgeneral, 0, 'impresion');
	  }else{$TiempomeImp='0';}
      $despPorcImp=($kgmontImp+$kilos_desp_imp);
	  echo $porImp = regladetres($despPorcImp,100,$kilos_ex);
	  
				 ?>
                                     % </td>
                                    </tr>
                                    <tr>
                                      <td nowrap id="fuente2">KILO / HORA</td>
                                      <td nowrap id="fuente2">TINTAS-KG</td>
                                      <td nowrap id="fuente2">METRO -LINEAL </td>
                                    </tr>
                                    <tr>
                                      <td id="detallegrande3"><a href="produccion_registro_impresion_vista.php?id_op=<?php echo $id_op; ?>" target="_blank" style="text-decoration:underline; color:#000000" ><strong>
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
                                        </strong></a></td>
                                      <td id="detallegrande3"><?php 
			  $anchoporc_imp=$row_ref_op['ancho_ref']; 
			  $bolsas_imp = bolsasAprox($metros_imp,$anchoporc_imp);
			  echo redondear_entero_puntos($kilos_impTinta); ?></td>
              <td id="detallegrande3"><?php
			   //$metros_imp = $metros_imp;
			  //regla de tres
			  
			  /*$metros_imp=($metros_ext-($metro_des_imp));
  			  $metro_des_imp = metrolineal($kilos_desp_imp,$kgmontImp,0,$metros_imp,$KILOSREALESEXT);
			  echo $metros_imp;*/
			  $despMetro = regladetres($kilos_desp_imp,$metros_ext,$kilos_ex);
			  echo $metros_imp = $metros_ext - $despMetro;
			  
			  ?></td>
                                    </tr>
                                    <tr>
                                      <td nowrap id="fuente2">VALOR INSUMOS</td>
                                      <td nowrap id="fuente2">COSTO TOTAL</td>
                                      <td nowrap id="fuente2">COSTO KILO</td>
                                    </tr>
                                    <tr>
                                      <td id="detallegrande3">$
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
	  $KilosMP = $row_valoresMP['CANTKILOS'];
      $valorItem=$valorMP*$KilosMP;//cada item cuanto vale un kilo
	  $contValorI+=$valorItem;//ACUMULA VALOR POR ITEM
 
    } while ($row_valoresMP = mysql_fetch_assoc($resultcostoMP));
	  $COSTOTINTA = ($contValorI/$KILOSREALESIMP); 
	  redondear_entero_puntos($contValorI*$KILOSREALESIMP);//COSTO KILO DE TINTA
	  echo redondear_entero_puntos($contValorI);		
	  //COSTO MATERIA PRIMA EN IMPRESION TINTAS
	  $kiloMPIMP = $COSTOHORAKILO;//$COSTOHORAKILO PORQUE VA SUMANDO EL COSTO DE LOS ANTERIORES PROCESOS
 	// echo redondear_entero_puntos($kiloMPIMP);
 	  
	  ?></td>
      <td id="detallegrande3">$ <?php
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
  	//APORTES DE LEY	
	$sqlemp="SELECT (a.horas_empleado) AS HORADIA,(b.sueldo_empleado) AS SUELDO,(b.aux_empleado) AS AUXILIO,(c.total) AS APORTES FROM 
empleado a 
LEFT JOIN TblProcesoEmpleado b ON a.codigo_empleado=b.codigo_empleado  
LEFT JOIN TblAportes c ON a.codigo_empleado=c.codigo_empl WHERE a.codigo_empleado=$oper_proc_imp";
	$resultemp_IMP=mysql_query($sqlemp);	
	$sueldo_IMP=mysql_result($resultemp_IMP,0,'SUELDO'); //sueldo del mes  
	$aux_trans_IMP=mysql_result($resultemp_IMP,0,'AUXILIO');//auxilio de transp
	$aportestotal_IMP=mysql_result($resultemp_IMP,0,'APORTES');//total de los aportes del empleado 
	//$horasmes=mysql_result($resultemp,0,'HORASMES');//LO EQUIVALENTE A LAS HORAS QUE SE TRABAJAN EN UN MES REAL 186,6666667 
	$horasmes_IMP=$horasmes_imp;
	$horasdia=mysql_result($resultemp_IMP,0,'HORADIA');//esto es 8 
	//NOVEDADES DEL MES DE LOS OPRARIOS DE IMPRESION AQUI DESCARTO LA NECESIDAD DE VERIFICAR SI TRABAJO NOCTURNO ETC
 	$sqlnovedad="SELECT SUM(recargos) as RECARGOS,SUM(festivos) as FESTIVOS FROM TblNovedades WHERE codigo_empleado=$oper_proc_imp AND fecha BETWEEN DATE_FORMAT('$FECHA_NOVEDAD_IMP', '%Y-%m-01') AND DATE_FORMAT('$FECHA_NOVEDAD_IMP', '%Y-%m-31')";  
    $resultnovedad_IMP=mysql_query($sqlnovedad);
	$recargos_IMP=mysql_result($resultnovedad_IMP,0,'RECARGOS');//define si el operario trabajo en nocturna o no
	$festivos_IMP=mysql_result($resultnovedad_IMP,0,'FESTIVOS');//otros recargos
	//OPERACION
 	$valorhoraxoperImp = sueldoMes($sueldo_IMP,$aux_trans_IMP,$aportestotal_IMP,$horasmes_IMP,$horasdia,$recargos_IMP,$festivos_IMP);
	
	$KilosxHora_imp=$KilosxHoraIMP;
    $const_mode_imp += $valorhoraxoperImp;//valor total hora de todos operarios en ex
	$horaOper_imp =  $const_mode_imp / $numoper_imp;//$numoper promedio hora un operario
		if($numoper_imp > 1)
		{$mod_i=($horaOper_imp / $KilosxHora_imp)+$mod_demas;}
		else{
		$mod_i=(($horaOper_imp * $oper_horas_imp)/$KilosxHora_imp)+$mod_demas;}
     } while ($row_operario = mysql_fetch_assoc($operario));	
 	redondear_decimal($mod_i);	   
 	$cif_i = ($costoUnHCif_imp/$KilosxHoraIMP);
	redondear_entero_puntos($cif_i); //COSTO DE HORA IMPRESA CON CIF
 	$gga_i  = ($costoUnHGga_imp/$KilosxHoraIMP);
	redondear_entero_puntos($gga_i);//COSTO DE HORA EXTRUIDA CON CIF	
 	$ggv_i = ($costoUnHGgv_imp/$KilosxHoraIMP); 
	redondear_entero_puntos($ggv_i); //COSTO DE HORA EXTRUIDA CON CIF	
 	$ggf_i = ($costoUnHGgf_imp/$KilosxHoraIMP); 
	redondear_entero_puntos($ggf_i); //COSTO DE HORA EXTRUIDA CON CIF	
	 
	$COSTOHORAKILOIMP = ($mod_i+$cif_i+$gga_i+$ggv_i+$ggf_i+$kiloMPIMP+$COSTOTINTA);
	$totalimpresion=($COSTOHORAKILOIMP*$KILOSREALESEXT); //$KILOSREALESEXT porq ya esta con el desperdicio, ademas el desperdicio ya tendria el valor con tinta
	echo redondear_entero_puntos($totalimpresion);
 		 ?></td>
           <td id="detallegrande3">$ <?php 
	   //COSTO MATERIA PRIMA
	   $costoMP_IMP = $contValorI;
redondear_entero_puntos($mod_i+$cif_i+$gga_i+$ggv_i+$ggf_i +$COSTOTINTA+$contValorI); //COSTO TOTAL DE MP EN IMPRESION		  		
 	   echo redondear_entero_puntos($COSTOHORAKILOIMP); 
				//CONTROLA LA RENTABILIDAD CUANDO UNO DE LOS PROCESOS NO EXISTE
 				$costoBolsaImp=$totalimpresion/$bolsas_imp;
				redondear_decimal_operar($costoBolsaImp);
                  $id_op=$row_ref_op['id_op'];
              	  $sqlcotiz="SELECT  Tbl_items_ordenc.trm AS trm,Tbl_items_ordenc.str_unidad_io,Tbl_items_ordenc.int_precio_io  FROM Tbl_orden_produccion,Tbl_items_ordenc WHERE Tbl_orden_produccion.id_op = $id_op  AND Tbl_orden_produccion.int_cod_ref_op = Tbl_items_ordenc.int_cod_ref_io ORDER BY Tbl_items_ordenc.fecha_despacho_io DESC LIMIT 1";				
				  $resultcotiz=mysql_query($sqlcotiz); 
				  $numcotiz=mysql_num_rows($resultcotiz); 
				  $medida=mysql_result($resultcotiz,0,'str_unidad_io');
				  $precioCotiz=mysql_result($resultcotiz,0,'int_precio_io');
				  $trmCotiz=mysql_result($resultcotiz,0,'trm');
                  $undPaquetes=$row_ref_op['int_undxpaq_op'];//unidad x paquetes
				  
				  $precioCotiz_imp = unidadMedida($medida,$precioCotiz,$undPaquetes,$trmCotiz);
				  $precioCotiz_imp;  
				  $utilidadImp=($precioCotiz_imp-$costoBolsaImp);  redondear_decimal_operar($utilidadImp); 
			 porcentaje2($precioCotiz_imp,$utilidadImp,0);
			  ?></td>
                                    </tr>
                                    <tr>
                                      <td colspan="3" nowrap id="fuente2"> 
                                        <?php 
									 // SI NO SE HA LIQUIDADO
									  $liquidoImpresion = redondear_decimal_operar($KilosxHoraIMP);
									 if($liquidoImpresion=='0.00') { ?>
                                      <a href="produccion_registro_impresion_listado2.php?op=<?php echo $row_ref_op['id_op'];?>" title="Sin Liquidar" style="text-decoration:none; color:#F00"  target="_blank">Sin Liquidar En Impresion</a> <?php }?>
                                      </td>
                                    </tr>
                                  </table>
                                </fieldset></td>
                              <?php  //SI NO TIENE IMPRESION
							  }else{
					//PARA PASAR LOS DATOS A SELLADO CUANDO NO HAY IMPRESION 
				 $kiloMPIMP = $COSTOHORAKILO;
				$COSTOHORAKILOIMP = ($mod_i+$cif_i+$gga_i+$ggv_i+$ggf_i+$kiloMPIMP+$COSTOTINTA);
				?>
                              <td colspan="3" id="titulo1"><h4 style="color:#F00">SIN IMPRESION</h4></td>
                              <?php } ?>
                            </tr>
                            <tr>
                              <td colspan="6" id="fuente6" valign="top"><?php
			  //DATOS PARA REFILADO 
			  if($KILOSREALESIMP =='0.00' || $KILOSREALESIMP ==''){$KILOSREALESIMP = $KILOSREALESEXT;
 			  }
			   $KILOSREALESREF=($KILOSREALESIMP); //echo redondear_decimal_operar($KILOSREALESREF); 
			   
	  $fecha_general_ref = quitarDia($FECHA_NOVEDAD_REF);
 	  $sqlgeneral="SELECT * FROM `TblDistribucionHoras`  ORDER BY `fecha` DESC LIMIT 1";
	  $resultgeneral= mysql_query($sqlgeneral);
	  $numgeneral= mysql_num_rows($resultgeneral);
	  if($numgeneral >='1')
	  { 
	  $TiempomeRef =  mysql_result($resultgeneral, 0, 'refilado');
 	  }
 	  
      $id_op=$row_ref_op['id_op'];
	  $sqlselKH="SELECT COUNT(DISTINCT rollo_rp) AS ITEMS,SUM(int_kilosxhora_rp) AS KILOHORA FROM Tbl_reg_produccion WHERE id_op_rp='$id_op' AND id_proceso_rp='3' "; 
	  $resultselKH=mysql_query($sqlselKH); 
	  $numselKH=mysql_num_rows($resultexKH); 
	  if($numselKH >= '1') {
	  $ITEMSREF=mysql_result($resultselKH,0,'ITEMS');
	  $KilosxHREF=mysql_result($resultselKH,0,'KILOHORA'); 
	  $KilosxHoraREF=($KilosxHREF/$ITEMSREF);
	   redondear_decimal_operar($KilosxHoraREF);//este es el real para el proximo mes julio 2015
	 //$KilosxHoraSELL=($KILOSREALESSELL/$horasM_sellDec);
	  }
	    
				//CONTROLA LA RENTABILIDAD CUANDO UNO DE LOS PROCESOS NO EXISTE
 				$costoBolsaSel=$totalsellado/$bolsas_sel;
				 redondear_decimal_operar($costoBolsaSel); 
				  
                  $id_op=$row_ref_op['id_op'];
              	  $sqlcotiz="SELECT Tbl_items_ordenc.trm AS trm,Tbl_items_ordenc.str_unidad_io,Tbl_items_ordenc.int_precio_io FROM Tbl_orden_produccion,Tbl_items_ordenc WHERE Tbl_orden_produccion.id_op = $id_op  AND Tbl_orden_produccion.int_cod_ref_op = Tbl_items_ordenc.int_cod_ref_io ORDER BY Tbl_items_ordenc.fecha_despacho_io DESC LIMIT 1";				
				  $resultcotiz=mysql_query($sqlcotiz); 
				  $numcotiz=mysql_num_rows($resultcotiz); 
				  $medida=mysql_result($resultcotiz,0,'str_unidad_io');
				  $precioCotiz=mysql_result($resultcotiz,0,'int_precio_io');
				  $trmCotiz=mysql_result($resultcotiz,0,'trm');
                  $undPaquetes=$row_ref_op['int_undxpaq_op'];//unidad x paquetes
				  
				$precioCotiz_ref = unidadMedida($medida,$precioCotiz,$undPaquetes,$trmCotiz);
				 $precioCotiz_ref; 
				 $utilidadRef=($precioCotiz_ref - $costoBolsaRef);  
				 redondear_decimal_operar(); 
			  porcentaje2($precioCotiz_ref,$utilidadRef,0);
			  ?>
                                &nbsp;</td>
                            </tr>
                            <tr>
                              <td colspan="3" id="fuente1" valign="top"><fieldset>
                                  <legend id="dato1">PROCESO SELLADO</legend>
                                  <table>
                                    <tr id="tr1">
                                      <td colspan="3" id="titulo1"><strong>
                                        <?php
	  $id_op=$row_ref_op['id_op'];
	  $sqlrollo="SELECT COUNT(DISTINCT rollo_r) AS rollos, SUM(kilos_r) AS kilos, SUM(bolsas_r) AS bolsas, DATE_FORMAT(fechaI_r,'%Y-%m-%d') AS FECHA, SUM(reproceso_r) AS reproceso FROM TblSelladoRollo WHERE id_op_r='$id_op' ORDER BY fechaI_r ASC"; 
	  $resultrollo=mysql_query($sqlrollo); 
	  $numrollo=mysql_num_rows($resultrollo); 
	  if($numrollo >= '1') 
	  { $rollo_sell=mysql_result($resultrollo,0,'rollos');
	    $bolsas_sell=mysql_result($resultrollo,0,'bolsas');
		$kilos_sell=mysql_result($resultrollo,0,'kilos');
		$reproceso_sell=mysql_result($resultrollo,0,'reproceso');
		$FECHA_NOVEDAD_SELL=mysql_result($resultrollo,0,'FECHA'); 
		echo quitarDia($FECHA_NOVEDAD_SELL);
		
	   //FACTOR ANUAL
 		$query_factor = "SELECT * FROM TblFactorP ORDER BY fecha_fp DESC LIMIT 1";
		$factor = mysql_query($query_factor, $conexion1);
		$totalRows_factor = mysql_num_rows($factor);
		  if($totalRows_factor >= '1') 
		  { 
		  $horasmes_sell=mysql_result($factor,0,'hora_lab_fp');//186,666666666667
		  }			
		?>
                                        </strong></td>
                                    </tr>
                                    <tr>
                                      <td nowrap id="fuente2">ENTRADA-KG</td>
                                      <td nowrap id="fuente2">DESPERDICIO-KG</td>
                                      <td nowrap id="fuente2">% DESP.</td>
                                    </tr>
                                    <tr>
                                      <td id="detallegrande3"><?php 
			  if($KILOSREALESREF=='0.00' ||$KILOSREALESREF==''){$KILOSREALESIMP = $KILOSREALESIMP;}
		 echo redondear_decimal_operar($KILOSREALESIMP); 
	   }else {echo "0";} ?></td>
      <td id="detallegrande3"><?php 
	  //desperdicio general 
/*	  $id_op=$row_ref_op['id_op'];
	  $sqldesp="SELECT SUM(valor_desp_rd) AS kgDespe FROM Tbl_reg_desperdicio WHERE op_rd='$id_op' AND id_proceso_rd='4'"; 
	  $resultdesp=mysql_query($sqldesp); 
	  $numdesp=mysql_num_rows($resultdesp); 
	  if($numdesp >= '1') 
	  { $kilos_desp_sell=mysql_result($resultdesp,0,'kgDespe');   redondear_decimal_operar($kilos_desp_sell); } else{  $kilos_desp_sell = 0;}		*/  
 	   $Metrosbolsa=bolsasAprox2($anchoporc,$bolsas_sell);
 	   $bolsasReproceso=($reproceso_sell*1000)/$row_ref_op['peso_millar_ref'];
	   $metroReproceso = bolsasAprox2($anchoporc,$bolsasReproceso);
	   $metroFinalSell=($Metrosbolsa + $metroReproceso);
	   $metros_desp_sell = ($metros_imp - $metroFinalSell);
	   echo $kilos_desp_sell=regladetres2($metros_desp_sell,$metros_imp,$KILOSREALESIMP); //metros a kilos
	   
	   //$mtroDes_sell=($metros_imp-$Metrosbolsa);
	    
	    //$kilos_desp_sell  =  metroaKilos3($row_ref_op['ancho_ref'],$row_ref_op['calibre_ref'],$mtroDes_sell);
		
	    //redondear_decimal_operar($kilos_desp_sell);
		$KILOSREALESSELL = ($KILOSREALESREF-($kilos_desp_sell));
	    ?>
       </td>
                 <td id="detallegrande3"><?php 
 				 $despPorcSell=($kgmontSell+$kilos_desp_sell);
				 echo $porSell = regladetres($despPorcSell,100,$kilos_ex);
				  ?>
                                     %</td>
                                    </tr>
                                    <tr>
                                      <td nowrap id="fuente2">BOLSAS</td>
                                      <td nowrap id="fuente2">REPRO-KG</td>
                                      <td nowrap id="fuente2">METRO-LINEAL</td>
                                    </tr>
                                    <tr>
                                      <td id="detallegrande3"><?php 
			 echo $bolsas_sell;
			  ?></td>
                                      <td id="detallegrande3"><?php  
									  echo $reproceso_sell;
									  ?></td>
                                      <td id="detallegrande3" nowrap><?php  
			  //regla de tres
               
			  echo $metros_imp;
  			  ?></td>
                                    </tr>
                                    <tr >
                                      <td nowrap id="fuente2">TIEMPO </td>
                                      <td nowrap id="fuente2">KILO / HORA</td>
                                      <td nowrap id="fuente2">CINTA/HOTMEL</td>
                                    </tr>
                                    <tr>
                                      <td id="detallegrande3"><a href="costos_listado_ref_xproceso_tiempos2.php?id_op=<?php echo $row_ref_op['id_op']; ?>" target="_blank" style="text-decoration:underline; color:#000000" ><strong> 
                                        <?php 	
	  $id_op=$row_ref_op['id_op'];
	  $sqlsellpm="SELECT SUM(valor_tiem_rt) AS horasM FROM Tbl_reg_tiempo WHERE op_rt='$id_op' AND id_proceso_rt='4'"; 
	  $resultsellpm=mysql_query($sqlsellpm); 
	  $numsellpm=mysql_num_rows($resultsellpm); 
	  if($numsellpm >= '1') 
	  { $horasM_sell=mysql_result($resultsellpm,0,'horasM');
	    $minutoM_sellD = minutoaDecimal($horasM_sell);//no va en la o.p
	  }else{echo "0";}				
				 
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
 	    redondear_decimal($tHoras_sell);//tiempo de la o.p en extruder
	    $horasMSinMuertosSell=horadecimalUna($tHoras_sell);//hora a decimal para operar
	    echo $horasM_sellDec = number_format($horasMSinMuertosSell-$minutoM_sellD, 2, '.', '');
	  }else{echo $horasM_sellDec="0";}  	  
  	 
/* 	  $id_op=$row_ref_op['id_op'];
	  $sqlsellp="SELECT SUM(valor_prep_rtp) AS horasP FROM Tbl_reg_tiempo_preparacion WHERE op_rtp='$id_op' AND id_proceso_rtp='4'"; 
	  $resultsellp=mysql_query($sqlsellp); 
	  $numsellp=mysql_num_rows($resultsellp); 
	  if($numsellp >= '1') 
	  { $horasP_sell=mysql_result($resultsellp,0,'horasP'); $totalTiempo_sell=$horasP_sell;}*/
 	  $fecha_general_sell = quitarDia($FECHA_NOVEDAD_SELL);
 	  $sqlgeneral="SELECT * FROM `TblDistribucionHoras`  ORDER BY `fecha` DESC LIMIT 1";
	  $resultgeneral= mysql_query($sqlgeneral);
	  $numgeneral= mysql_num_rows($resultgeneral);
	  if($numgeneral >='1') 
	  { 
	  $TiempomeSell	=  mysql_result($resultgeneral, 0, 'sellado');  
 	  }else{$TiempomeSell ='0';}
  	  ?>
                                        </strong></a></td>
                                      <td id="detallegrande3"><a href="produccion_registro_sellado_total_vista.php?id_op=<?php echo $id_op; ?>" target="_blank" style="text-decoration:underline; color:#000000" ><strong>
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
      /*$tiempo_menostmuert =($tHoras_sell-($totalTiempo_sell /60));
	    $KilosxHoraSELL = ($KILOSREALESREF / $tiempo_menostmuert); 
		echo redondear_decimal_operar($KilosxHoraSELL);*/
  	  ?>
                                        </strong></a></td>
                                      <td id="detallegrande3">$
                                        <?php 
 	      //COSTO SELLADO
		  $id_op=$row_ref_op['id_op'];
		  $tipo_cinta = $row_ref_op['tipoCinta_ref'];//TIPO DE CINTA O LINER
		  $id_ter=$row_ref_op['id_termica_op'];
		  //SI LLEVA CINTA TERMICA
 	      $sqlterm="SELECT valor_unitario_insumo FROM insumo WHERE id_insumo='$id_ter'"; 
		  $resultterm=mysql_query($sqlterm); 
		  $numterm=mysql_num_rows($resultterm);  
 		  if($numterm >= '1')  
		  { 
		  $valor_term=mysql_result($resultterm,0,'valor_unitario_insumo');
		  $RealmetrosTermica = ($row_ref_op['cinta_termica_op']*$bolsas_sell);//por el ancho real de la cinta termica
		  $costoTermica=($RealmetrosTermica * $valor_term);
		  }else{$costoTermica='0';}
   		 // $insumoS=0;
		 // do{
  		  $tipo = $row_ref_op['adhesivo_ref'];//HOTMELT O CINTA
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
  		  $costoHotmel = ($costoliner+$costopega);// El precio total de hotmelt
		  }else{
		  //CINTA SEGURIDAD
 		  $sqlcostoMP="SELECT valor_unitario_insumo AS VALORMETRO FROM  insumo WHERE insumo.id_insumo = '$tipo_cinta'"; 
		  $resultcostoMP=mysql_query($sqlcostoMP); 
		  $numcostoMP=mysql_num_rows($resultcostoMP); 
		  $row_valoresMP = mysql_fetch_assoc($resultcostoMP);
		  if($numcostoMP >= '1')  
		  { 
		  $valorMP = $row_valoresMP['VALORMETRO'];
		  $valorcinta = $metros_imp * $valorMP;//esto pasa a dinero
		  } 			  
 		 }	
		  //SUMA CINTA SEGURIDAD Y TERMICA
		  $insumoS = ($valorcinta+$costoTermica+$costoHotmel);//si es cinta con todo	  
   // } while ($row_valoresMP = mysql_fetch_assoc($resultcostoMP));
 	      echo  redondear_entero_puntos($insumoS); 
 	      $kiloMPSELL = $COSTOHORAKILOIMP;//$COSTOHORAKILOIMP PORQUE VA ACUMULANDO EL ANTERIOR PROCESO
	       $insumoSkilo = ($insumoS/$KILOSREALESIMP); //costo insumo x kilo
	 ?></td>
                                    </tr>
                                    <tr>
                                      <td id="fuente2">BOLSILLO</td>
                                      <td nowrap id="fuente2">COSTO TOTAL</td>
                                      <td nowrap id="fuente2">COSTO  KILO<?php $valorHotmelt=$valorMP+$valorLiner;?></td>
                                    </tr>
                                    <tr>
                                      <td id="detallegrande3">$ <?php 
  									 //INFORMACION PROVIENE DE LA O.P 
/*									 $ref_bolsillo = $row_ref_op['ref_bols'];
									 $sqlref_bols="SELECT COUNT(id_rp) as id,SUM(costo) AS costo FROM Tbl_reg_produccion WHERE int_cod_ref_rp='$ref_bolsillo' AND id_proceso_rp='2' AND costo <> '0' ORDER BY `id_op_rp` DESC"; 
									 $resultref_bols=mysql_query($sqlref_bols); 
									 $numref_bols=mysql_num_rows($resultref_bols);  
									 if($numref_bols > '0') 
									 { $items=mysql_result($resultref_bols,0,'id');
									 $costo = mysql_result($resultref_bols,0,'costo');
									 $costo_bols = ($costo/$items);//SACO PONDERADO DEL COSTO BOLSILLO EN IMPRESION SI EXISTE
									 }else{									  
 									//INFORMACION PROVIENE DE LA O.P 
									$ref_bolsillo = $row_ref_op['ref_bols'];//miral el costo de la laminas
								    $sqlref_bols="SELECT COUNT(id_rp) as id,SUM(costo) AS costo FROM Tbl_reg_produccion WHERE int_cod_ref_rp='$ref_bolsillo' AND id_proceso_rp='1' AND costo <> '0' ORDER BY `id_op_rp` DESC"; 
									$resultref_bols=mysql_query($sqlref_bols); 
									$numref_bols=mysql_num_rows($resultref_bols);  
									if($numref_bols >= '1') 
									{ $items=mysql_result($resultref_bols,0,'id');
									$costo = mysql_result($resultref_bols,0,'costo');
									$costo_bols = ($costo/$items);//SACO PONDERADO DEL COSTO BOLSILLO EN EXTRUDER
								      }else{echo"Sin registro";}									  
 									 } */
									 
									 //COSTO BOLSILLO SEGUN LAMINA
 									$tipoLm = $row_ref_op['tipoLamina_ref'];
 									$sqlrbols="SELECT valor_unitario_insumo FROM insumo WHERE id_insumo = '$tipoLm'";
									$resultrbols= mysql_query($sqlrbols);
									$numrbols = mysql_num_rows($resultrbols);
									if($numrbols >='1')
									{ 
									$costo_bols = mysql_result($resultrbols, 0, 'valor_unitario_insumo'); 
									echo redondear_entero_puntos($costo_bols*$pesoMbols);     
									} else if($row_ref_op['calibreBols_ref'] >'0.00' && $numrbols <='0')
									{echo "falta tipo de lamina en la ref";}else{echo "0";}							
 									$costobolsil=redondear_decimal_operar($costo_bols/$pesoMbols);//para operar kilo de bolsillo
 									    	  
 									   ?></td>
                                      <td id="detallegrande3">$
                                      <?php 
  	  //HORAS TRABAJADAS DE LA O.P
      $id_op=$row_ref_op['id_op'];
	  $sqloperario_sell="SELECT cod_empleado_r FROM `TblSelladoRollo` WHERE `id_op_r`= $id_op  ORDER BY `cod_empleado_r` ASC"; 
	  $resultoperario_sell=mysql_query($sqloperario_sell); 
	  $numoper_sell=mysql_num_rows($resultoperario_sell); 

      $id_op=$row_ref_op['id_op'];
	  $sqloper="SELECT `cod_empleado_r`, `turno_r`, MIN(`fechaI_r`) AS TIEMPOINI, MAX(`fechaF_r`) AS TIEMPOFIN, TIMEDIFF (`fechaF_r`, `fechaI_r`) AS TIEMPODIFE FROM `TblSelladoRollo` WHERE `id_op_r` = $id_op  GROUP BY `fechaI_r`, `cod_empleado_r` ASC"; 
      $operario = mysql_query($sqloper, $conexion1) or die(mysql_error());
      $row_operario = mysql_fetch_assoc($operario);    
      $const_mode_sell=0;
	  do{
      $oper_proc_sell = $row_operario['cod_empleado_r']; 
	  $oper_horas_sell = horadecimalUna($row_operario['TIEMPODIFE']);
 
	$sqlemp="SELECT (a.horas_empleado) AS HORADIA,(b.sueldo_empleado) AS SUELDO,(b.aux_empleado) AS AUXILIO,(c.total) AS APORTES FROM 
empleado a 
LEFT JOIN TblProcesoEmpleado b ON a.codigo_empleado=b.codigo_empleado  
LEFT JOIN TblAportes c ON a.codigo_empleado=c.codigo_empl WHERE a.codigo_empleado=$oper_proc_sell";
	$resultemp_SELL=mysql_query($sqlemp);	
	$sueldo_SELL=mysql_result($resultemp_SELL,0,'SUELDO'); //sueldo del mes  
	$aux_trans_SELL=mysql_result($resultemp_SELL,0,'AUXILIO');//auxilio de transp
	$aportestotal_SELL=mysql_result($resultemp_SELL,0,'APORTES');//total de los aportes del empleado 
	//$horasmes=mysql_result($resultemp,0,'HORASMES');//LO EQUIVALENTE A LAS HORAS QUE SE TRABAJAN EN UN MES REAL 186,6666667 
	$horasmes_SELL=$horasmes_sell;
	$horasdia=mysql_result($resultemp_SELL,0,'HORADIA');//esto es 8 
	//NOVEDADES DEL MES DE LOS OPRARIOS DE EXTRUDER 
 	$sqlnovedad="SELECT SUM(recargos) as RECARGOS,SUM(festivos) as FESTIVOS FROM TblNovedades WHERE codigo_empleado=$oper_proc_sell AND fecha BETWEEN DATE_FORMAT('$FECHA_NOVEDAD_SELL', '%Y-%m-01') AND DATE_FORMAT('$FECHA_NOVEDAD_SELL', '%Y-%m-31')";  
    $resultnovedad_SELL=mysql_query($sqlnovedad);
	$recargos_SELL=mysql_result($resultnovedad_SELL,0,'RECARGOS');//define si el operario trabajo en nocturna o no
	$festivos_SELL=mysql_result($resultnovedad_SELL,0,'FESTIVOS');//otros recargos
	
 	$valorhoraxoperSell  =  sueldoMes($sueldo_SELL,$aux_trans_SELL,$aportestotal_SELL,$horasmes_SELL,$horasdia,$recargos_SELL,$festivos_SELL); 
	$KilosxHora_sell=$KilosxHoraSELL;
    $const_mode_sell += $valorhoraxoperSell;//valor total hora de todos operarios en ex
	$horaOper_sell =  $const_mode_sell / $numoper_sell;//$numoper_sell promedio hora un operario
 		if($numoper_sell > 1)
		{$mod_s = (($horaOper_sell)/$KilosxHora_sell)+$mod_demas;}
		else{
		$mod_s = (($horaOper_sell * $oper_horas_sell)/$KilosxHora_sell)+$mod_demas;}
     } while ($row_operario = mysql_fetch_assoc($operario));
    redondear_entero_puntos($mod_s);	   
 	$cif_s = ($costoUnHCif_sell/$KilosxHoraSELL);
	redondear_entero_puntos($cif_s);//COSTO DE HORA SELLADA CON CIF	 
 	$gga_s = ($costoUnHGga_sell/$KilosxHoraSELL);
	redondear_entero_puntos($gga_s);//COSTO DE HORA SELLADA CON CIF	
 	$ggv_s = ($costoUnHGgv_sell/$KilosxHoraSELL); 
	redondear_entero_puntos($ggv_s); //COSTO DE HORA SELLADO CON CIF	
 	$ggf_s = ($costoUnHGgf_sell/$KilosxHoraSELL);
    redondear_entero_puntos($ggf_s); //COSTO DE HORA EXTRUIDA CON CIF	
 
 	$COSTOHORAKILOSELL = ($insumoSkilo+$costobolsil+$mod_s+$cif_s+$gga_s+$ggv_s+$ggf_s+$kiloMPSELL); 
	$costo_reproc = $COSTOHORAKILOSELL * $reproceso_sell;//costo reproceso
    $totalsellado=($KILOSREALESREF*$COSTOHORAKILOSELL)+$costo_reproc;//+$costo_reproc;
	echo redondear_entero_puntos($totalsellado);
	
		 ?></td>
                                      <td id="detallegrande3">$
                                      <?php 
	      //COSTO MATERIA PRIMA COSTO KILO EN SELLADO	 	  		
  		  echo redondear_entero_puntos($totalsellado/$KILOSREALESIMP);?></td>
                                    </tr>
                                    <tr>
                                      <td colspan="3" id="fuente2"><?php 
									 // SI NO SE HA LIQUIDADO
									  $liquidoSellado = redondear_decimal_operar($KilosxHoraSELL);
									 if($liquidoSellado=='0.00') { ?>
                                        <a href="produccion_registro_sellado_listado2.php?op=<?php echo $row_ref_op['id_op'];?>" title="Sin Liquidar" style="text-decoration:none; color:#F00"  target="_blank">Sin Liquidar En Sellado</a>
                                      <?php }?></td>
                                    </tr>
                                  </table>
                                  <?php 
								/*$horadia=($distribuir/8); 
								 echo  (($sueldo_bas+$auxilio_bas+$aportes_bas+$recargo_novbasico+$festivo_novbasico)/$horadia);*/
								 ?>
                                </fieldset></td>
                              <td colspan="3" id="fuente1" valign="top"><fieldset>
                                  <legend id="dato1">INFORMACION GENERAL </legend>
                                  <table>
                                    <tr id="tr1">
                                      <td colspan="2" id="titulo1">RESUMEN</td>
                                    </tr>
                                    <tr>
                                      <td nowrap id="fuente2">COSTO  BOLSA</td>
                                      <td nowrap id="fuente2">Precio Vta. O.C</td>
                                    </tr>
                                    <tr>
                                      <td id="detallegrande3"><?php  
			  //regla de tres
  			  $metros_sell_finalRes = bolsasAprox2($anchoporc,$bolsas_sell);
  			  $despMetro=($metros_ext - $metros_sell_finalRes);
			  $desperdicioTotal = metroaKilos2($anchoporc,$row_ref_op['calibre_ref'],$despMetro);
			  //echo $despMetro; 
			   ?> $ <?php  
	//CONTROLA LA RENTABILIDAD CUANDO UNO DE LOS PROCESOS NO EXISTE
  	$costoBolsa_sell=$totalsellado/$bolsas_sell;
	$costoBolsa_imp=$totalimpresion/$bolsas_imp;
	$costoBolsa_ext=$totalextruder/$bolsas_ext;
	if($costoBolsa_sell=='0'){$costoBolsa=$costoBolsa_imp;}else 
	if($costoBolsa_imp=='0'){$costoBolsa=$costoBolsa_ext;}
    else {$costoBolsa=$costoBolsa_sell;}
			
			 echo redondear_decimal_operar($costoBolsa+$COSTOCINTA);?></td>
                                      <td id="detallegrande3"><?php  
                  $id_op=$row_ref_op['id_op'];
              	  $sqlcotiz="SELECT Tbl_items_ordenc.trm AS trm,Tbl_items_ordenc.str_unidad_io AS medida,Tbl_items_ordenc.int_precio_io AS precio,Tbl_items_ordenc.str_moneda_io AS moneda FROM Tbl_orden_produccion,Tbl_items_ordenc WHERE Tbl_orden_produccion.id_op = $id_op  AND Tbl_orden_produccion.int_cod_ref_op = Tbl_items_ordenc.int_cod_ref_io ORDER BY Tbl_items_ordenc.fecha_entrega_io DESC LIMIT 1";				
				  $resultcotiz=mysql_query($sqlcotiz); 
				  $numcotiz=mysql_num_rows($resultcotiz); 
				  $moneda=mysql_result($resultcotiz,0,'moneda');
				  $medida=mysql_result($resultcotiz,0,'medida');
				  $precioCotiz=mysql_result($resultcotiz,0,'precio');
				  $trmCotiz=mysql_result($resultcotiz,0,'trm');
                  $undPaquetes=$row_ref_op['int_undxpaq_op'];//unidad x paquetes
				  echo $moneda.' '.$precioCotiz;
				  $precioCotiz_sell = unidadMedida($medida,$precioCotiz,$undPaquetes,$trmCotiz);
				 ?></td>
                                    </tr>
                                    <tr>
                                      <td nowrap id="fuente2">PROGRAMADAS</td>
                                      <td nowrap id="fuente2">SELLADAS</td>
                                    </tr>
                                    <tr>
                                      <td id="detallegrande3"><?php echo $row_ref_op['int_cantidad_op'];?></td>
                                      <td id="detallegrande3"><?php 
			//OJO MIRAR SI ES MEJOS SACAR LAS BOLSAS DESDE TBL SELLADO O POR LOS METROSL
			 /*$anchoporc_sell=$row_ref_op['ancho_ref']; 
			 echo $bolsas_sell = bolsasAprox($metros_imp,$anchoporc_sell);*/
			 echo $bolsas_sell;
			  ?></td>
                                    </tr>
                                    <tr>
                                      <td nowrap id="fuente2">HORAS EXTRUIDAS</td>
                                      <td nowrap id="fuente2">H. TOTALES</td>
                                    </tr>
                                    <tr>
                                      <td id="detallegrande3"><?php echo $horasM_exDec;?></td>
                                      <td id="detallegrande3"><?php echo $TiempomeExt;?></td>
                                    </tr>
                                    <tr>
                                      <td nowrap id="fuente2">HORAS IMPRESION</td>
                                      <td nowrap id="fuente2">H. TOTALES</td>
                                    </tr>
                                    <tr>
                                      <td id="detallegrande3"><?php echo redondear_decimal($tHoras_imp);?></td>
                                      <td id="detallegrande3"><?php echo $TiempomeImp;?></td>
                                    </tr>
                                    <tr>
                                      <td nowrap id="fuente2">HORAS SELLADAS</td>
                                      <td nowrap id="fuente2">H. TOTALES</td>
                                    </tr>
                                    <tr>
                                      <td id="detallegrande3"><?php echo $horasM_sellDec;?></td>
                                      <td id="detallegrande3"><?php echo $TiempomeSell;?></td>
                                    </tr>
                                    <tr>
                                      <td colspan="2" id="fuente1">&nbsp;</td>
                                    </tr>
                                    <tr>
                                      <td id="fuente2">DESPERDICIO %</td>
                                      <td id="detallegrande3"><?php 
/*									$despPorcExt=($kgmont+$kilos_desp);
				 $porExt= regladetres($despPorcExt,100,$kilos_ex);
					
					$despPorcImp=($kgmontImp+$kilos_desp_imp);
				 $porImp= regladetres($despPorcImp,100,$kilos_ex);
									
 				 $despPorcSell=($kgmontSell+$kilos_desp_sell);
				 $porSell= regladetres($despPorcSell,100,$kilos_ex);*/
				echo ($porExt+$porImp+$porSell);
				  ?>
                                        %</td>
                                    </tr>
                                    <tr>
                                      <td nowrap id="fuente2">UTILIDAD</td>
                                      <td nowrap id="fuente2">RENTABILIDAD</td>
                                    </tr>
                                    <tr>
                                      <td id="detallegrande3">$
                                        <?php  $utilidadSell= ($precioCotiz_sell-$costoBolsa);echo redondear_decimal_operar($utilidadSell);?></td>
                                      <td id="detallegrande3"><?php 
			 $rentabil = porcentaje2($precioCotiz_sell,$utilidadSell,0);
			 if($rentabil < 0) {?>
                                        <h4 style="color:#F00"> <?php echo $rentabil;?> % </h4>
                                        <?php }else{?>
                                        <?php echo $rentabil;?> %
                                        <?php } ?></td>
                                    </tr>
                                  </table>
                                </fieldset></td>
                            </tr>
                            <tr>
                              <td colspan="6" id="fuente1">&nbsp;</td>
                            </tr>
                            <tr>
                              <td colspan="6" id="fuente1"><span style="color:#F00"><em>
                                <?php if ($tHoras_ex==''){echo "* Falta Liquidar en Extrusion</br>";}
				if ($TiempomeExt==''){echo "* Falta horas por Mes en Extruder, este proceso puede estar en el siguiente mes o falta agregar la distribucion de horas del mes actual.</br>";}
				if ($KilosxHoraEXT==''){echo "* Falta liquidar kilos x hora 0.00 </br>";} ?>
                                </em></span></td>
                            </tr>
                            <tr>
                              <td colspan="6" id="fuente1"><span style="color:#F00"><em>
                                <?php if ($row_ref_op['impresion_ref']!=0 && $tHoras_imp!=''){
				 if ($row_ref_op['impresion_ref']>0 && $tHoras_imp==''){echo "* Falta Liquidar en Impresion </br>";};
				if ($TiempomeImp=='' && $row_ref_op['impresion_ref']!=0){echo "* Falta horas por Mes en Impresion, este proceso puede estar en el siguiente mes o falta agregar la distribucion de horas del mes actual.</br>";}
				if ($KilosxHoraIMP=="" && $row_ref_op['impresion_ref']!=0){echo "* Falta liquidar kilos x hora 0.00";} 
				}?>
                                </em></span></td>
                            </tr>
                            <tr>
                              <td colspan="6" id="fuente1"><span style="color:#F00"><em>
                                <?php if ($tHoras_sell==''){echo "* Falta Liquidar en Sellado  ";}
				if ($TiempomeSell==''){echo "* Falta horas por Mes en Sellado, este proceso puede estar en el siguiente mes o falta agregar la distribucion de horas del mes actual.";} 
				if ($KilosxHoraSELL==''){echo "* Falta liquidar kilos x hora 0.00";} ?>
                                </em></span></td>
                            </tr>
                            <tr>
                              <td colspan="6" id="fuente1"><p><strong>Nota:</strong> Importante revisar y tener al dia el precio kilo  de los insumos, Novedades de los operarios por mes, valores de CIF, GGA, GGF, GGV y la Distribucion de el total de horas por proceso.</p></td>
                            </tr>
                            
                              <td colspan="6" id="titulo1"><!--<input name="submit" type="submit"value="VISTA" />--></td>
                            </tr>
                          </table></td>
                      </tr>
                    </table>
                    <input type="hidden" name="MM_insert" value="form1">
                  </form></td>
              </tr>
            </table>
          </div>
          <b class="spiffy"> <b class="spiffy5"></b> <b class="spiffy4"></b> <b class="spiffy3"></b> <b class="spiffy2"><b></b></b> <b class="spiffy1"><b></b></b></b></div></td>
    </tr>
  </table>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($ultimo);

mysql_free_result($referencias);

mysql_free_result($medidas);
?>