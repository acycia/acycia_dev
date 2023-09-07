<?php require_once('Connections/conexion1.php'); ?><?php
//LLAMADO A FUNCIONES
include('funciones/funciones_php.php');//SISTEMA RUW PARA LA BASE DE DATOS 
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
//IMPRIME CAMPOS MAESTRA
$colname_impresion_vista= "-1";
if (isset($_GET['id_op'])) {
  $colname_impresion_vista = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_impresion_vista = sprintf("SELECT * FROM Tbl_reg_produccion  WHERE Tbl_reg_produccion.id_op_rp=%s AND id_proceso_rp='2' ORDER BY rollo_rp ASC",$colname_impresion_vista);
$impresion_vista = mysql_query($query_impresion_vista, $conexion1) or die(mysql_error());
$row_impresion_vista = mysql_fetch_assoc($impresion_vista);
$totalRows_impresion_vista = mysql_num_rows($impresion_vista);
//CARGA LOS TIEMPOS MUERTOS 
$colname_tiempoMuerto= "-1";
if (isset($_GET['id_op'])) {
  $colname_tiempoMuerto = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}

mysql_select_db($database_conexion1, $conexion1);
$query_tiempoMuerto = sprintf("SELECT *, SUM(valor_tiem_rt) AS tiempom FROM Tbl_reg_tiempo WHERE op_rt='%s' AND id_proceso_rt='2' GROUP BY id_rpt_rt ASC",$colname_tiempoMuerto);
$tiempoMuerto = mysql_query($query_tiempoMuerto, $conexion1) or die(mysql_error());
$row_tiempoMuerto = mysql_fetch_assoc($tiempoMuerto);
$totalRows_tiempoMuerto = mysql_num_rows($tiempoMuerto);
//CARGA LOS TIEMPOS PREPARACION 
mysql_select_db($database_conexion1, $conexion1);
$query_tiempoPreparacion = sprintf("SELECT *, SUM(`valor_prep_rtp`) AS preparacion FROM Tbl_reg_tiempo_preparacion WHERE op_rtp='%s' AND id_proceso_rtp='2' GROUP BY id_rpt_rtp ASC",$colname_tiempoMuerto);
$tiempoPreparacion  = mysql_query($query_tiempoPreparacion , $conexion1) or die(mysql_error());
$row_tiempoPreparacion  = mysql_fetch_assoc($tiempoPreparacion );
$totalRows_tiempoPreparacion  = mysql_num_rows($tiempoPreparacion );
//CARGA LOS TIEMPOS  DESPERDICIOS
mysql_select_db($database_conexion1, $conexion1);
$query_desperdicio = sprintf("SELECT *, SUM(`valor_desp_rd`) AS desperdicio FROM Tbl_reg_desperdicio WHERE op_rd='%s' AND id_proceso_rd='2' GROUP BY `id_rpd_rd` ASC",$colname_tiempoMuerto);
$desperdicio = mysql_query($query_desperdicio, $conexion1) or die(mysql_error());
$row_desperdicio = mysql_fetch_assoc($desperdicio);
$totalRows_desperdicio = mysql_num_rows($desperdicio);
//LLAMA LAS MEZCLAS DE IMPRESION UNIDAD 
mysql_select_db($database_conexion1, $conexion1);
$query_kilo_editar = sprintf("SELECT id_rpp_rp, valor_prod_rp,costo_mp FROM Tbl_reg_kilo_producido WHERE Tbl_reg_kilo_producido.op_rp='%s' AND id_proceso_rkp='2' ORDER BY id_rkp ASC",$colname_tiempoMuerto);
$kilo_editar = mysql_query($query_kilo_editar, $conexion1) or die(mysql_error());
$row_kilo_editar = mysql_fetch_assoc($kilo_editar);
$totalRows_kilo_editar = mysql_num_rows($kilo_editar);

$colname_ref= "-1";
if (isset($_GET['id_op'])) {
  $colname_ref = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ref_op = sprintf("SELECT Tbl_referencia.impresion_ref FROM Tbl_orden_produccion,Tbl_referencia,Tbl_egp WHERE  Tbl_orden_produccion.id_op='%s' AND Tbl_orden_produccion.id_ref_op=Tbl_referencia.id_ref AND Tbl_referencia.n_egp_ref=Tbl_egp.n_egp",$colname_ref);
$ref_op = mysql_query($query_ref_op, $conexion1) or die(mysql_error());
$row_ref_op = mysql_fetch_assoc($ref_op);
$totalRows_ref_op = mysql_num_rows($ref_op);
//LLAMA LAS UNIDADES DE IMPRESION
/*$colname_caract = "-1";
if (isset($_GET['id_op'])) {
  $colname_caract  = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_caract_valor = sprintf("SELECT * FROM Tbl_orden_produccion ,Tbl_caracteristicas, Tbl_caracteristicas_valor WHERE Tbl_orden_produccion.id_op='%s' AND Tbl_orden_produccion.id_ref_op= Tbl_caracteristicas_valor.id_ref_cv AND Tbl_caracteristicas.id_c=Tbl_caracteristicas_valor.id_c_cv AND Tbl_caracteristicas.proceso_c='2' 
ORDER BY Tbl_caracteristicas_valor.id_cv ASC",$colname_caract);
$caract_valor = mysql_query($query_caract_valor, $conexion1) or die(mysql_error());
$row_caract_valor = mysql_fetch_assoc($caract_valor);
$totalRows_caract_valor = mysql_num_rows($caract_valor);*/
$horasOpmes=$row_impresion_vista['id_op_rp'];
$resultOpmes = mysql_query("SELECT rollo_r AS rollo_r, `cod_empleado_r`,`cod_auxiliar_r`, `turno_r`, DATE_FORMAT(MIN(`fechaI_r`), '%k.%i.%s') AS TIEMPOINI, DATE_FORMAT(MAX(`fechaF_r`),'%k.%i.%s') AS TIEMPOFIN, TIMEDIFF(MAX(`fechaF_r`), MIN(`fechaI_r`)) AS TIEMPODIFE, SUM(`kilos_r`) AS KILOS FROM `TblImpresionRollo` WHERE `id_op_r`= '$horasOpmes' GROUP BY `fechaI_r`,`cod_empleado_r`,`cod_auxiliar_r` ASC");
$numOpmes=mysql_num_rows($resultOpmes); //enviar código MySQL
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
  <link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">
  <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
  <script type="text/javascript" src="AjaxControllers/eliminaFantasma.js"></script> 

  <link href="css/vista.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="js/vista.js"></script>
  <script type="text/javascript" src="js/formato.js"></script>
  <title>SISADGE AC & CIA</title>
</head>
<body>
  <div align="center">
    <table id="tablainterna">
      <tr>    
       <td colspan="13" id="principal">REGISTRO DE PRODUCCION IMPRESION</td>
     </tr>
     <tr>
      <td rowspan="6" id="fondo">&nbsp;</td>
      <td rowspan="6" id="fondo"><img src="images/logoacyc.jpg"/></td>
      <td colspan="9" id="fuente3"><a href="produccion_impresion_listado_rollos.php?id_op_r=<?php echo  $_GET['id_op'] ?>"><img src="images/completo.gif" style="cursor:hand;" alt="ESTIQUER" title="ESTIQUER" border="0"/></a><a href="menu.php"><img src="images/impresor.gif" onClick="window.print();" style="cursor:hand;" alt="IMPRIMIR" title="IMPRIMIR" /></a></td>
    </tr>
    <tr>
      <td colspan="4" id="subppal2">FECHA DE INGRESO </td>
      <td id="subppal2">RESPONSABLE</td>
      <td colspan="4" id="subppal2">OPERARIO</td>
    </tr>
    <tr>
      <td colspan="4" id="fuente2"><?php echo $row_impresion_vista['fecha_ini_rp']; ?></td>
      <td nowrap id="fuente2"><?php echo $row_impresion_vista['str_responsable_rp']; ?></td>
      <td colspan="4" nowrap id="fuente2"><?php  
      $id_emp=$row_impresion_vista['int_cod_empleado_rp'];
      $sqlemp="SELECT nombre_empleado FROM empleado WHERE codigo_empleado='$id_emp' ";
      $resultemp= mysql_query($sqlemp);
      $numemp= mysql_num_rows($resultemp);
      if($numemp >='1')
      { 
       $nombre = mysql_result($resultemp, 0, 'nombre_empleado');echo $nombre; }else{echo "N.A";}?></td>
     </tr>
     <tr>
      <td colspan="2" id="subppal2">Orden de Produccion</td>
      <td colspan="2" id="subppal2">Referencia - version</td>
      <td id="subppal2">Tintas</td>
      <td colspan="3" id="subppal2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" nowrap id="fuente2"><strong><?php echo $row_impresion_vista['id_op_rp'] ?></strong></td>
      <td colspan="2" id="fuente2"><strong><?php echo $row_impresion_vista['int_cod_ref_rp'] ?>-<?php echo $row_impresion_vista['version_ref_rp'] ?></strong></td>
      <td id="fuente2"><?php if($row_ref_op['impresion_ref'] > 0){echo "SI";}else{echo "NO";}; ?></td>
      <td colspan="3" id="fuente2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="8" id="fondo">Alguna Inquietud o Comentario : sistemas@acycia.com </td>
    </tr>
    <tr>
      <td colspan="10" id="subppal2"><strong>IMPRESION / TIEMPOS</strong></td>
    </tr>
    <tr>
      <td colspan="10" id="fuente1"><strong>Nota:</strong> Informacion suministrada segun el ingreso rollo a rollo del operario.</td>
    </tr>
    <tr>
      <td nowrap id="fuente1">Rollo</td>
      <td nowrap id="fuente1">OPERARIOS</td>
      <td nowrap id="fuente1">CODIGO</td>
      <td nowrap id="fuente1">AUXILIAR</td>
      <td nowrap id="fuente1">CODIGO</td>
      <td nowrap id="fuente1">TURNO</td>
      <td nowrap id="fuente1">RANGO INICIAL</td>
      <td nowrap id="fuente1">RANGO FINAL</td>
      <td colspan="2" nowrap id="fuente1">TIEMPO TOTAL</td>
<!--      <td id="fuente1">KILOS</td>
-->    </tr>
<?php 	 
 		while ($row=mysql_fetch_array($resultOpmes)) { //Bucle para ver todos los registros
      ?>
      <tr>
       <td id="fuente1"><?php 
	  $Nrollo=$row['rollo_r']; //numero de rollo
	  echo $Nrollo; ?></td>
    <td id="fuente1"><?php
	  $empleado=$row['cod_empleado_r']; //datos del campo teléfono
	  $id_emp=$empleado; 
	  $sqlemp="SELECT * FROM empleado WHERE codigo_empleado='$id_emp'";
	  $resultemp= mysql_query($sqlemp);
	  $numemp= mysql_num_rows($resultemp);
	  if($numemp >='1')
	  { 
     $operarios = mysql_result($resultemp, 0, 'nombre_empleado');
     echo $operarios; 
	  }  //visualizar datos
    ?></td>

    <td nowrap id="fuente1"><?php
			  echo "$empleado"; //visualizar datos
        ?></td>
        <td nowrap id="fuente1"><?php
	  $aux=$row['cod_auxiliar_r']; //datos del campo teléfono
	  $id_aux=$aux; 
	  $sqlaux="SELECT nombre_empleado FROM empleado WHERE codigo_empleado='$id_aux'";
	  $resultaux= mysql_query($sqlaux);
	  $numaux= mysql_num_rows($resultaux);
	  if($numaux >='1')
	  { 
     $auxiliar = mysql_result($resultaux, 0, 'nombre_empleado');
     echo $auxiliar; 
	  } //visualizar datos
    ?></td>
    <td nowrap id="fuente1"><?php
			  echo "$aux"; //visualizar datos
        ?></td>      
        <td id="fuente1"><?php
	  $turno=$row['turno_r']; //datos del campo email
			  echo $turno; //visualizar datos
        ?></td>
        <td id="fuente1"><strong>
          <?php
		$tiempoini=$row['TIEMPOINI']; //datos del campo email
			  echo $tiempoini; //visualizar datos
        ?>
      </strong></td>
      <td id="fuente1"><strong>
        <?php
		$tiempofin=$row['TIEMPOFIN']; //datos del campo email
			  echo $tiempofin; //visualizar datos
        ?>
      </strong></td>
      <td colspan="2" id="fuente1"><strong>
        <?php
		$tiempototal=$row['TIEMPODIFE']; //datos del campo email
		     $totaltiempo = horadecimalUna($tiempototal);echo $totaltiempo;             $totaltiem += $totaltiempo;//visualizar datos
         ?>
       </strong></td>
<!--      <td id="fuente1"><?php
$kilos=$row['KILOS']; //datos del campo email
			  echo numeros_format($kilos);
			  $totalkilos += $kilos;//visualizar datos
        ?></td>-->
      </tr>
      <?php  } ?>    
      <tr>
       <td colspan="8" id="detalle3" >Totales:</td>
       <td colspan="2" id="fuente3" ><?php echo $totaltiem;?></td>
       <!-- <td id="fuente3" ><?php echo redondear_decimal($totalkilos);?></td>-->
     </tr>
     <tr>
      <td colspan="10" id="fuente2">
        <table width="100%" border="0" id="tablainterna">
          <?php if($row_tiempoMuerto['id_rpt_rt']!='') {?>
          <tr>
            <td colspan="10" id="subppal2"><strong>TIEMPOS DESPERDICIOS DE TODOS LOS ROLLOS</strong></td>
          </tr> 
          <tr>
            <!--<td nowrap id="subppal2"><strong>Rollo N&deg;</strong></td>-->
            <td nowrap id="subppal2"><strong>Tiempos Muertos- Tipo</strong></td>
            <td nowrap id="subppal2"><strong>Tiempos Muertos- Minutos</strong></td>

          </tr>
          <?php  do{ ?>
          <tr>
            <!--<td id="detalle2"><?php echo $row_tiempoMuerto['int_rollo_rt']; ?></td>-->
            <td id="detalle1">
              <?php 
              $varM=$row_tiempoMuerto['id_rpt_rt']; 
              $id_tm=$varM;
              $sqltm="SELECT nombre_rtp FROM Tbl_reg_tipo_desperdicio WHERE id_rtp='$id_tm'";
              $resulttm= mysql_query($sqltm);
              $numtm= mysql_num_rows($resulttm);
              if($numtm >='1')
              { 
               $nombreM = mysql_result($resulttm, 0, 'nombre_rtp');echo $nombreM; }?>
             </td>
             <td id="detalle2"><?php $var1=$row_tiempoMuerto['tiempom']; echo $var1;$totalM=$totalM+$var1; ?></td>
           </tr> <?php } while ($row_tiempoMuerto = mysql_fetch_assoc($tiempoMuerto)); ?>
           <tr>
             <!-- <td id="detalle3">&nbsp;</td>-->
             <td id="detalle3"><strong>TOTAL</strong></td>
             <td id="fuente2"><strong><?php echo $totalM; ?></strong></td>
           </tr>
           <?php } ?>
           <?php if($row_tiempoPreparacion['id_rpt_rtp']!='') {?>
           <tr>
             <!-- <td nowrap id="subppal2"><strong>Rollo N&deg;</strong></td>-->
             <td nowrap id="subppal2"><strong>Tiempos Preparacion- Tipo</strong></td>
             <td nowrap id="subppal2"><strong>Tiempos Preparacion - Minutos</strong>       </td>
           </tr>
           <?php  do { ?>
           <tr>
            <!--<td id="detalle2"><?php echo $row_tiempoPreparacion['int_rollo_rtp']; ?></td>-->
            <td id="detalle1">
              <?php 
              $varP=$row_tiempoPreparacion['id_rpt_rtp']; 
              $id_rtp=$varP;
              $sqlrtp="SELECT nombre_rtp FROM Tbl_reg_tipo_desperdicio WHERE id_rtp='$id_rtp'";
              $resultrtp= mysql_query($sqlrtp);
              $numrtp= mysql_num_rows($resultrtp);
              if($numrtp >='1')
              { 
               $nombreP = mysql_result($resultrtp, 0, 'nombre_rtp');echo $nombreP; }?>
             </td>
             <td id="detalle2"><?php $var2=$row_tiempoPreparacion['preparacion']; echo $var2; $totalP=$totalP+$var2;  ?></td>
           </tr><?php } while ($row_tiempoPreparacion = mysql_fetch_assoc($tiempoPreparacion)); ?>
           <tr>
            <!--<td id="detalle3">&nbsp;</td>-->
            <td id="detalle3"><strong>TOTAL</strong></td>
            <td id="fuente2"><strong><?php echo $totalP; ?></strong></td>
          </tr>
          <?php } ?> 
          <?php if($row_desperdicio['id_rpd_rd']!='') {?>  
          <tr>
            <!--<td nowrap id="subppal2"><strong>Rollo N&deg;</strong></td> -->
            <td nowrap id="subppal2"><strong>Desperdicios - Tipo</strong></td>
            <td nowrap id="subppal2"><strong>Desperdicios - Kilos</strong></td>      
          </tr>
          <?php do{ ?>
          <tr>
            <!--<td id="detalle2"><?php echo $row_desperdicio['int_rollo_rd']; ?></td>-->
            <td id="detalle1">
              <?php 
              $varD=$row_desperdicio['id_rpd_rd']; 
              $id_rpd=$varD;
              $sqlrtd="SELECT nombre_rtp FROM Tbl_reg_tipo_desperdicio WHERE id_rtp='$id_rpd'";
              $resultrtd= mysql_query($sqlrtd);
              $numrtd= mysql_num_rows($resultrtd);
              if($numrtd >='1')
              { 
               $nombreD = mysql_result($resultrtd, 0, 'nombre_rtp'); echo $nombreD; }?>
             </td>
             <td id="detalle2"><?php $var3=$row_desperdicio['desperdicio']; echo $var3;$totalD=$totalD+$var3; ?></td>
           </tr><?php } while ($row_desperdicio = mysql_fetch_assoc($desperdicio)); ?>
           <tr>
            <td id="detalle3"><strong>TOTAL</strong></td>
            <td id="fuente2" above><strong><?php echo numeros_format($totalD); ?></strong></td>
          </tr>
          <?php } ?> 
        </table>
      </td>
    </tr> 
    <tr>
      <td colspan="10" id="fuente2">&nbsp;
       <?php if($row_kilo_editar['id_rpp_rp']!='') {?> 
       <table width="100%" border="0" id="tablainterna">
         <tr id="tr1">
           <td colspan="13" id="subppal2"><strong>CONSUMO DE TINTAS</strong></td>
         </tr>
         <tr id="tr1">
          <td nowrap="nowrap" id="subppal2"><strong>Nombre Insumo</strong></td>
          <td nowrap="nowrap" id="subppal2"><strong>Kilos Ingresados</strong></td>
          <td nowrap="nowrap" id="subppal2"><strong>Costo MP/kg</strong></td>
          <td nowrap="nowrap" id="subppal2"><strong>Sub Total</strong></td>
        </tr>
        <?php  for ($x=0;$x<=$totalRows_kilo_editar-1 ;$x++) { ?> 
        <tr>         
         <td id="detalle1"><?php $id_rkp=mysql_result($kilo_editar,$x,id_rpp_rp);
         $sqlinsum="SELECT * FROM insumo WHERE id_insumo='$id_rkp'";
         $resultinsum= mysql_query($sqlinsum);
         $numinsum= mysql_num_rows($resultinsum);
         if($numinsum >='1')
         { 
           $nombre_insumo = mysql_result($resultinsum, 0, 'descripcion_insumo');
           echo $nombre_insumo; 
         }?>
         <td id="detalle2"><?php $valort=mysql_result($kilo_editar,$x,valor_prod_rp); echo $valort;
         $totalTintas+=$valort; ?>              
         <td id="detalle2"><?php echo $costomp=mysql_result($kilo_editar,$x,costo_mp); 
         $costo_mp+=$costomp; ?></td>
         <td id="detalle2"><?php 
         echo $valorcosto=$valort*$costomp; 
         $costoTotal+=$valorcosto;?></td>  
       </tr>
       <?php  } ?> 
       <tr>
        <td id="detalle3"><strong>TOTAL</strong></td>
        <td id="detalle2"><strong><?php echo numeros_format($totalTintas);?></strong></td>
        <td id="fuente2">&nbsp;</td>
        <td id="fuente2"><strong><?php echo numeros_format($costoTotal);?></strong></td>
      </tr> 
    </table>
    <?php  } ?>   
  </td> 
</tr>
<tr>
  <td colspan="13" id="fuente2">
    <table border="0"  > 
      <tr>
        <td colspan="13" id="subppal2"><strong>LIQUIDACION POR ROLLO</strong></td>
      </tr>
      <tr>
        <td colspan="13" id="fuente1"><strong>Nota:</strong> Informacion suministrada segun la liquidacion de la o.p hecha por el operario..</td>    
      </tr>
      <tr>
        <td id="subppal2">Rollo N</td>
        <td id="subppal2">Kilos Producidos </td>
        <td id="subppal2">Kilos Desperdiciados</td>
        <td nowrap id="subppal2">Total Kilos</td>
        <td id="subppal2">Horas Trabajadas</td>
        <td id="subppal2">Rodamiento Horas</td>
        <td id="subppal2">Tiempo Muertos - Minutos</td>
        <td id="subppal2">Tiempos Preparacion - Minutos</td>
        <td id="subppal2">Promedio Kilos Producidos x Hora</td>
        <td id="subppal2">Promedio Metros Producidos x Minuto</td>
        <td nowrap id="subppal2">Metro Lineal</td>
        <td nowrap id="subppal2">Editar</td>
        <?php if($_SESSION['acceso']): ?><td nowrap id="subppal2">Elimina</td><?php endif; ?>
      </tr>
      <?php $rollos=1; do{?>
      <tr>
        <td id="fuente2"><?php echo $rollos=$row_impresion_vista['rollo_rp'];
        $trollos=$rollos;
        $rollos++; ?></td>
        <td id="fuente2"><?php echo $row_impresion_vista['int_kilos_prod_rp']; $totalkp=$totalkp+$row_impresion_vista['int_kilos_prod_rp'];?></td>
        <td id="fuente2"><?php echo $row_impresion_vista['int_kilos_desp_rp']; $totalkd=$totalkd+$row_impresion_vista['int_kilos_desp_rp']; ?></td>
        <td id="fuente2"><?php echo $row_impresion_vista['int_total_kilos_rp']; $totalTk=$totalTk+$row_impresion_vista['int_total_kilos_rp']; ?></td>
        <td id="fuente2"><?php 
        $opes=$row_impresion_vista['id_op_rp'];
        $sqlimp="SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(total_horas_rp))) AS horasT, SEC_TO_TIME(SUM(TIME_TO_SEC(rodamiento_rp))) AS horashr FROM Tbl_reg_produccion WHERE id_op_rp='$opes' AND id_proceso_rp='2'"; 
        $resultimp=mysql_query($sqlimp); 
        $numimp=mysql_num_rows($resultimp); 
        if($numimp >= '1') 
         { $tHoras_imp=mysql_result($resultimp,0,'horasT'); 
       $tHoras_hr=mysql_result($resultimp,0,'horashr');
     }else {echo "00:00:00";}	
     echo $row_impresion_vista['total_horas_rp']; 
     $totalh=$tHoras_imp;$totalhr=$tHoras_hr; ?></td>
     <td id="fuente2"><?php echo $row_impresion_vista['rodamiento_rp'];?></td>
     <td id="fuente2"><?php echo $row_impresion_vista['horas_muertas_rp']; $totalhm=$totalhm+$row_impresion_vista['horas_muertas_rp'];?></td>
     <td id="fuente2"><?php echo $row_impresion_vista['horas_prep_rp']; $totalhp=$totalhp+$row_impresion_vista['horas_prep_rp'];?></td>
     <td id="fuente2"><?php echo $row_impresion_vista['int_kilosxhora_rp'];$totalkh=$totalkh+$row_impresion_vista['int_kilosxhora_rp']; ?></td>
     <td id="fuente2"><?php echo $row_impresion_vista['int_metroxmin_rp'];$totalmm=$totalmm+$row_impresion_vista['int_metroxmin_rp']; 
     ?></td>
     <td id="fuente2"><?php echo $row_impresion_vista['int_metro_lineal_rp'];
     $totalml = $totalml + $row_impresion_vista['int_metro_lineal_rp'];//metro lineal ?></td>
     <td id="fuente2">
       <?php 
       $id_rp=$row_impresion_vista['id_rp'];
       $sqlrp="SELECT TblImpresionRollo.id_r AS id_r,Tbl_reg_produccion.id_op_rp,Tbl_reg_produccion.rollo_rp FROM Tbl_reg_produccion,TblImpresionRollo WHERE Tbl_reg_produccion.id_rp='$id_rp' AND Tbl_reg_produccion.id_op_rp=TblImpresionRollo.id_op_r AND Tbl_reg_produccion.rollo_rp=TblImpresionRollo.rollo_r"; 
       $resultrp=mysql_query($sqlrp); 
       $numrp=mysql_num_rows($resultrp); 
       if($numrp >= '1') 
       { 
        $id_r=mysql_result($resultrp,0,'id_r'); 
      }
      ?>
      <a href="produccion_impresion_stiker_rollo_edit.php?id_r=<?php echo $id_r; ?>">Editar</a>
    </td>
    <?php if($_SESSION['acceso']): ?>
      <td id="fuente2">
        <a href="javascript:eliminar_fantasma('id_fantasma',<?php echo $row_impresion_vista['id_rp']; ?>,'produccion_registro_impresion_vista.php')"><img src="images/por.gif" alt="ELIMINAR" title="ELIMINAR" border="0" style="cursor:hand;"/></a> 
        <div style="display: none;" id="resp"> <b style="color: red;" >Eliminado!</b></div>
      </td> 
    <?php endif; ?>
  </tr>
  <?php } while ($row_impresion_vista = mysql_fetch_assoc($impresion_vista)); ?>
  <tr>
    <td id="detalle1"><strong>TOTALES</strong></td>
    <td id="fuente2"><strong><?php echo $totalkp; ?></strong></td>
    <td id="fuente2"><strong><?php echo $totalkd; ?></strong></td>
    <td id="fuente2"><strong><?php echo $totalTk; ?></strong> </td>
    <td id="fuente2"><strong><?php echo $totalh; ?></strong></td>
    <td id="fuente2"><strong><?php echo $totalhr; ?></strong></td>
    <td id="fuente2"><strong><?php echo $totalhm; ?></strong></td>
    <td id="fuente2"><strong><?php echo $totalhp; ?></strong></td>
    <td id="fuente2"><strong><?php echo numeros_format($totalkh/$trollos); 
    ?></strong></td>
    <td id="fuente2"><strong><?php echo numeros_format($totalmm/$trollos); 
    ?></strong></td>
    <!-- <td id="fuente2">&nbsp;</td>-->
    <td id="fuente2"><strong><?php echo $totalml; 
    ?></strong></td>
    <td id="fuente2">&nbsp;</td>
  </tr>
</table></td>
</tr>
<tr>
  <td colspan="13" >
  <!--      <table>
        <tr id="tr1">
          <td colspan="100%" id="subppal2">CARACTERISTICAS</td>
          </tr> 
        <tr>
          <?php  do{ ?>          
            <td  id="fuente3"><?php $id_cv=$row_caract_valor['id_cv']; $var=$row_caract_valor['str_nombre_caract_c']; echo $var; ?>                                             
              <?php $valor=$row_caract_valor['str_valor_cv']; echo $valor;?>
              </td>
            <?php } while ($row_caract_valor = mysql_fetch_assoc($caract_valor)); ?>
          </tr> 
        </table>-->  
      </td>
    </tr>                                                   
  </table>   
</table>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);
mysql_free_result($tiempoMuerto);
mysql_free_result($impresion_vista);
mysql_free_result($tiempoPreparacion);
mysql_free_result($desperdicio);
mysql_free_result($producido);
mysql_free_result($caract_valor);
?>