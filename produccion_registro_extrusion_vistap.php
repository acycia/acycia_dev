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
//IMPRIME CAMPOS MAESTRA
$colname_extrusion_vista = "-1";
if (isset($_GET['id_op_rp'])) {
  $colname_extrusion_vista = (get_magic_quotes_gpc()) ? $_GET['id_op_rp'] : addslashes($_GET['id_op_rp']);
}
$parcial=$_GET['parcial'];
mysql_select_db($database_conexion1, $conexion1);
$query_extrusion_vista = sprintf("SELECT * FROM Tbl_reg_produccion WHERE id_op_rp='%s' AND id_proceso_rp='1' ORDER BY rollo_rp DESC",$colname_extrusion_vista);
$extrusion_vista = mysql_query($query_extrusion_vista, $conexion1) or die(mysql_error());
$row_extrusion_vista = mysql_fetch_assoc($extrusion_vista);
$totalRows_extrusion_vista = mysql_num_rows($extrusion_vista);

$colname_tiempoMuerto = "-1";
if (isset($_GET['id_op_rp'])) {
  $colname_tiempoMuerto = (get_magic_quotes_gpc()) ? $_GET['id_op_rp'] : addslashes($_GET['id_op_rp']);
}

//CARGA LOS TIEMPOS MUERTOS 
$fecha_ini_rp=$row_extrusion_vista['fecha_ini_rp'];
mysql_select_db($database_conexion1, $conexion1);
$query_tiempoMuerto = sprintf("SELECT *, (`valor_tiem_rt`) AS muertos FROM Tbl_reg_tiempo WHERE op_rt=%s AND id_proceso_rt='1' ",$colname_tiempoMuerto);
$tiempoMuerto = mysql_query($query_tiempoMuerto, $conexion1) or die(mysql_error());
$row_tiempoMuerto = mysql_fetch_assoc($tiempoMuerto);
$totalRows_tiempoMuerto = mysql_num_rows($tiempoMuerto);
//CARGA LOS TIEMPOS PREPARACION 
mysql_select_db($database_conexion1, $conexion1);
$query_tiempoPreparacion = sprintf("SELECT *, (`valor_prep_rtp`) AS preparacion FROM Tbl_reg_tiempo_preparacion WHERE op_rtp=%s AND id_proceso_rtp='1' ORDER BY id_rpt_rtp ASC",$colname_tiempoMuerto);
$tiempoPreparacion  = mysql_query($query_tiempoPreparacion , $conexion1) or die(mysql_error());
$row_tiempoPreparacion  = mysql_fetch_assoc($tiempoPreparacion );
$totalRows_tiempoPreparacion  = mysql_num_rows($tiempoPreparacion );
//CARGA LOS TIEMPOS  DESPERDICIOS
$fecha_ini_rp=$row_extrusion_vista['fecha_ini_rp'];
mysql_select_db($database_conexion1, $conexion1);
$query_desperdicio = sprintf("SELECT *, (`valor_desp_rd`) AS desperdicio FROM Tbl_reg_desperdicio WHERE op_rd=%s AND id_proceso_rd='1' ORDER BY `id_rpd_rd` ASC",$colname_tiempoMuerto);
$desperdicio = mysql_query($query_desperdicio, $conexion1) or die(mysql_error());
$row_desperdicio = mysql_fetch_assoc($desperdicio);
$totalRows_desperdicio = mysql_num_rows($desperdicio);
//CARGA LOS TIEMPOS KILOS PRODUCIDOS
 
mysql_select_db($database_conexion1, $conexion1);
$query_producido = sprintf("SELECT *, SUM(rkp.valor_prod_rp) AS producido  FROM Tbl_reg_produccion rp  
 left join Tbl_reg_kilo_producido rkp on rkp.op_rp =  rp.id_op_rp
 WHERE rkp.op_rp=%s AND rkp.id_proceso_rkp='1' and rkp.fecha_rkp=rp.fecha_ini_rp GROUP BY rkp.id_rpp_rp ASC",$colname_tiempoMuerto);
$producido = mysql_query($query_producido, $conexion1) or die(mysql_error());
$row_producido = mysql_fetch_assoc($producido);
$totalRows_producido = mysql_num_rows($producido);
//LLENA CAMPOS DE MEZCLAS
$colname_mezcla= "-1";
if (isset($_GET['id_op_rp'])) {
  $colname_mezcla = (get_magic_quotes_gpc()) ? $_GET['id_op_rp'] : addslashes($_GET['id_op_rp']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_mezcla = sprintf("SELECT * FROM Tbl_orden_produccion,Tbl_produccion_mezclas WHERE Tbl_orden_produccion.id_op='%s' AND Tbl_orden_produccion.int_cod_ref_op=Tbl_produccion_mezclas.int_cod_ref_pm ",$colname_mezcla);
$mezcla = mysql_query($query_mezcla, $conexion1) or die(mysql_error());
$row_mezcla = mysql_fetch_assoc($mezcla);
$totalRows_mezcla = mysql_num_rows($mezcla);
 
$horasOpmes=$row_extrusion_vista['id_op_rp'];//$row_extrusion_vista['rollo_rp'];
$resultOpmes = mysql_query(" SELECT * FROM TblExtruderRollo WHERE id_op_r= '$horasOpmes' ORDER BY rollo_r ASC");//  
$numOpmes=mysql_num_rows($resultOpmes); 


 $colname_rollo_cola = "-1";
if (isset($_GET['id_op_rp'])) {
  $colname_rollo_cola = (get_magic_quotes_gpc()) ? $_GET['id_op_rp'] : addslashes($_GET['id_op_rp']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_rollo_estrusion = sprintf("SELECT count(rollo_r) as total_rollos , sum(kilos_r) as kilos FROM TblExtruderRollo WHERE id_op_r='%s' " ,$colname_rollo_cola, $startRow_proceso_rollos, $maxRows_proceso_rollos);
$rollo_estrusion = mysql_query($query_rollo_estrusion, $conexion1) or die(mysql_error());
$row_rollo_estrusion = mysql_fetch_assoc($rollo_estrusion);
$totalRows_rollo_estrusion = mysql_num_rows($rollo_estrusion); 

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
        <td colspan="7" id="principal">REGISTRO DE PRODUCCION EN EXTRUSION</td>
      </tr>
      <tr>
        <td colspan="7" id="fuente3"><a href="produccion_registro_extrusionp_edit.php?id_op_rp=<?php echo $row_extrusion_vista['id_op_rp']; ?>&amp;id_rp=<?php echo $row_extrusion_vista['id_rp'];?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>&parcial=<?php echo $row_extrusion_vista['parcial']; ?>"><img src="images/menosp.gif" alt="EDIT EXTRUSION" title="EDIT EXTRUSION" border="0" /></a><a href="produccion_extrusion_listado_rollos.php?id_op_r=<?php echo  $_GET['id_op_rp'] ?>"><img src="images/parcial.gif" style="cursor:hand;" alt="ESTIQUER" title="ESTIQUER" border="0"/></a><a href="menu.php"><img src="images/impresor.gif" onClick="window.print();" style="cursor:hand;" alt="IMPRIMIR" title="IMPRIMIR" /></a></td>
      </tr>
      <tr>
        <td colspan="4" id="subppal2">FECHA DE INGRESO </td>
        <td colspan="3" id="subppal2">RESPONSABLE</td>
      </tr>
      <tr>
        <td colspan="4" id="fuente2"><?php echo $row_extrusion_vista['fecha_ini_rp']; ?></td>
        <td colspan="3" nowrap id="fuente2"><?php echo $row_extrusion_vista['str_responsable_rp']; ?></td>
      </tr>
      <tr>
        <td colspan="4" id="subppal2">Orden de Produccion</td>
        <td colspan="3" id="subppal2">Referencia - version</td>
      </tr>
      <tr>
        <td colspan="4" nowrap id="fuente2"><strong><?php echo $row_extrusion_vista['id_op_rp'] ?></strong></td>
        <td colspan="3" nowrap id="fuente2"><strong><?php echo $row_extrusion_vista['int_cod_ref_rp'] ?>-<?php echo $row_extrusion_vista['version_ref_rp'] ?></strong></td>
      </tr>
      <tr>
        <td colspan="7" id="fondo">Alguna Inquietud o Comentario : sistemas@acycia.com </td>
      </tr>
      <tr>
        <td colspan="7" id="subppal2"><strong> TIEMPOS DE EXTRUSION </strong></td>
      </tr>
      <tr>
        <td colspan="7" id="fuente1"><strong>Nota:</strong> Informacion suministrada segun el ingreso rollo a rollo del operario.</td>
      </tr>
      <tr>
        <td nowrap id="fuente1">ROLLO</td>
        <td nowrap id="fuente1">OPERARIOS</td>
        <td nowrap id="fuente1">TURNO</td>
        <td nowrap id="fuente1">RANGO INICIAL</td>
        <td nowrap id="fuente1">RANGO FINAL</td>
        <td nowrap id="fuente1">TIEMPO TOTAL</td>
        <td id="fuente1">KILOS</td>
      </tr>
      <?php 	 
    while ($row=mysql_fetch_array($resultOpmes)) { //Bucle para ver todos los registros
     ?>
     <tr>
      <td  nowrap id="fuente1"><strong>
        <?php
		    $Nrollos=$row['rollo_r']; //numero de rollo
			  echo $Nrollos; //visualizar datos
       ?>
     </strong>
   </td>
   <td  nowrap id="fuente1">
    <?php
   	  $trollos=$row_rollo_estrusion['total_rollos']; //datos del campo total rollo
   	  
   	  $empleado=$row['cod_empleado_r']; //datos del campo teléfono
   	  $id_emp=$empleado; 
   	  $sqlemp="SELECT * FROM empleado WHERE codigo_empleado='$id_emp'";
   	  $resultemp= mysql_query($sqlemp);
   	  $numemp= mysql_num_rows($resultemp);
       if($numemp >='1')
       { 
         echo  mysql_result($resultemp, 0, 'nombre_empleado'); 
	     } 
     ?> 
   </td>
    <td id="fuente1">
        <?php echo $row['turno_r'];?>
      </td>
      <td id="fuente1"><strong>
          <?php
          echo quitarFecha($row['fechaI_r']);  
        ?>
      </strong></td>
      <td id="fuente1"><strong>
        <?php
          echo quitarFecha($row['fechaF_r']); 
        ?>
      </strong>
     </td>
      <td id="fuente1"><strong>
        <?php
        $tiempototal=RestarFechas($row['fechaI_r'], $row['fechaF_r']); 
        $totaltiempo = horadecimalUna($tiempototal);echo $totaltiempo;
			$totaltiem += $totaltiempo;//visualizar datos
      ?>
    </strong></td>
    <td id="fuente1"><strong>
         <?php echo $kilos=numeros_format($row['kilos_r']); 
			      $totalkilos += $kilos;//visualizar datos
         ?>
        </strong></td>
      </tr>
      <?php  }  ?>
      <tr>
       <td colspan="5" id="detalle3" ><strong> Totales:</strong></td>
       <td id="fuente3" ><?php echo $totaltiem;?></td>
       <td id="fuente3" ><?php echo redondear_decimal($totalkilos);?></td>
     </tr>
     <tr>
      <td colspan="7" id="subppal2"><strong>Tiempos y Desperdicios</strong></td>
    </tr>
    <tr>
      <td colspan="7" id="fuente2">
        <table width="100%" border="0" id="tablainterna">
          <?php if($row_tiempoMuerto['id_rpt_rt']!='') {?>
          <tr>
            <td nowrap id="subppal2"><strong>Tiempos Muertos- Tipo</strong></td>
            <td colspan="3" nowrap id="subppal2"><strong>Tiempos Muertos - Minutos</strong></td>
          </tr>
          <?php  for ($k=0;$k<=$totalRows_tiempoMuerto-1;$k++) { ?>
          <tr>
            <td id="detalle1"><?php $var=mysql_result($tiempoMuerto,$k,id_rpt_rt); 
            $id_tm=$var;
            $sqltm="SELECT * FROM Tbl_reg_tipo_desperdicio WHERE id_rtp='$id_tm'";
            $resulttm= mysql_query($sqltm);
            $numtm= mysql_num_rows($resulttm);
            if($numtm >='1')
            { 
             $nombre = mysql_result($resulttm, 0, 'nombre_rtp');echo $nombre; }?></td>
             <td colspan="3" id="detalle2"><?php echo $var1=mysql_result($tiempoMuerto,$k,muertos);  $tiempoM+=$var1; ?></td>
           </tr>
           <?php } ?>
           <tr> 
            <td id="detalle3"><strong>TOTAL</strong></td>
            <td colspan="3" id="fuente2"><strong><?php echo $tiempoM; ?></strong></td>
          </tr>         
          <?php } ?>
          <?php if($row_tiempoPreparacion['id_rpt_rtp']!='') {?>
          <tr>
            <td nowrap id="subppal2"><strong>Tiempos Preparacion- Tipo</strong></td>
            <td colspan="3" nowrap id="subppal2"><strong>Tiempos Preparacion - Minutos</strong></td>
          </tr>
          <?php  for ($x=0;$x<=$totalRows_tiempoPreparacion-1;$x++) { ?>
          <tr>
            <td id="detalle1"><?php $var=mysql_result($tiempoPreparacion,$x,id_rpt_rtp); 
            $id_rtp=$var;
            $sqlrtp="SELECT * FROM Tbl_reg_tipo_desperdicio WHERE id_rtp='$id_rtp'";
            $resultrtp= mysql_query($sqlrtp);
            $numrtp= mysql_num_rows($resultrtp);
            if($numrtp >='1')
            { 
             $nombre = mysql_result($resultrtp, 0, 'nombre_rtp');echo $nombre; }?></td>
             <td colspan="3" id="detalle2"><?php echo $var2=mysql_result($tiempoPreparacion,$x,preparacion);  $tiempoP+=$var2; ?></td>
           </tr>
           <?php } ?>
           <tr> 
            <td id="detalle3"><strong>TOTAL</strong></td>
            <td colspan="3" id="fuente2"><strong><?php echo $tiempoP; ?></strong></td>
          </tr>         
          <?php } ?>
          <?php if($row_desperdicio['id_rpd_rd']!='') {?>
          <tr>
            <td nowrap id="subppal2"><strong>Desperdicios - Tipo</strong></td>
            <td colspan="3" nowrap id="subppal2"><strong>Desperdicios - Kilos</strong></td>
          </tr>
          <?php  for ($i=0;$i<=$totalRows_desperdicio-1;$i++) { ?>
          <tr>
            <td id="detalle1"><?php $var1=mysql_result($desperdicio,$i,id_rpd_rd); 
            $id_rpd=$var1;
            $sqlrtd="SELECT * FROM Tbl_reg_tipo_desperdicio WHERE id_rtp='$id_rpd'";
            $resultrtd= mysql_query($sqlrtd);
            $numrtd= mysql_num_rows($resultrtd);
            if($numrtd >='1')
            { 
             $nombre2 = mysql_result($resultrtd, 0, 'nombre_rtp'); echo $nombre2; }?></td>
             <td colspan="3" id="detalle2"><?php echo $var3=mysql_result($desperdicio,$i,desperdicio);  $desperdicios+=$var3; ?></td>
           </tr>
           <?php } ?>
           <tr> 
            <td id="detalle3"><strong>TOTAL</strong></td>
            <td colspan="3" id="fuente2"><strong><?php echo $desperdicios; ?></strong></td>
          </tr>          
          <?php } ?>
          <?php if($row_producido['id_rpp_rp']!='') {?>
          <tr>
            <td nowrap id="subppal2"><strong>Producidos - Tipo</strong></td>
            <td nowrap id="subppal2"><strong>Producidos - Kilos</strong></td>
            <td nowrap id="subppal2"><strong>Costo MP/kg</strong></td>
            <td nowrap id="subppal2"><strong>Sub Total</strong></td>
          </tr>
          <?php  for ($j=0;$j<=$totalRows_producido-1;$j++) { ?>
          <tr>
            <td id="detalle1"><?php $var1=mysql_result($producido,$j,id_rpp_rp); 
            $id_rkp=$var1;
            $sqlrkp="SELECT * FROM insumo WHERE id_insumo='$id_rkp'";
            $resultrkp= mysql_query($sqlrkp);
            $numrkp= mysql_num_rows($resultrkp);
            if($numrkp >='1')
            { 
             $nombre4 = mysql_result($resultrkp, 0, 'descripcion_insumo'); echo $nombre4; }?></td>
             <td id="detalle2"><?php  echo $var4=mysql_result($producido,$j,producido); $polietileno+=$var4; ?></td>
             <td id="detalle2"><?php 
             echo $var5=mysql_result($producido,$j,costo_mp); $costo_mp+=$var5; ?></td>
             <td id="detalle2"><?php 
             echo $valorcosto=$var4*$var5; 
             $costoTotal+=$valorcosto;?></td>
           </tr>
           <?php } ?>
           <tr> 
            <td id="detalle3"><strong>TOTAL</strong></td>
            <td id="fuente2"><strong><?php echo $polietileno; ?></strong></td>
            <td id="fuente2"><?php echo redondear_decimal($costoTotal/$polietileno);?></td>
            <td id="fuente2"><strong><?php echo number_format($costoTotal);?></strong></td>
          </tr>          
          <?php } ?>
        </table></td>
      </tr>
      <td colspan="10" id="fuente2">
        <table border="0" > 
          <tr>
            <td colspan="9" id="subppal2"><strong>LIQUIDACION TODOS LOS ROLLOS</strong></td>
          </tr>
          <tr>
            <td colspan="9" id="fuente1"><strong>Nota:</strong> Informacion suministrada segun la liquidacion de la o.p hecha por el operario..<?php
            $id_op=$row_extrusion_vista['id_op_rp'];
            $sqlrrollos="SELECT count(`id_rp`) AS ROLLOS FROM Tbl_reg_produccion WHERE id_op_rp='$id_op' AND id_proceso_rp='1' and parcial=$parcial";
            $resultrrollos = mysql_query($sqlrrollos);
            $numrrollos= mysql_num_rows($resultrrollos);
            if($numrrollos >='1')
            { 
             $rollosLiquidado = mysql_result($resultrrollos, 0, 'ROLLOS'); }?></td> 
           </tr> 
           <tr> 
            <td colspan="2" nowrap="nowrap" id="subppal2">Kilos Producidos </td>
            <td nowrap="nowrap" id="subppal2">Fecha Inicial</td>
            <td nowrap="nowrap" id="subppal2">Fecha Final</td>
            <td nowrap="nowrap" id="subppal2">Horas Trabajadas</td>
            <td nowrap="nowrap" id="subppal2">Rodamiento Horas</td>
            <td nowrap="nowrap" id="subppal2">Kilos Desperdiciados</td>
            <td nowrap="nowrap" id="subppal2">Kilos Por Hora</td>
            <td nowrap="nowrap" id="subppal2">Editar</td>
          </tr>
          <?php do{?>
          <tr>

            <td  colspan="2" id="fuente2">
              <?php 
                   $totalkp+=$row_extrusion_vista['int_total_kilos_rp'];
 
                 echo $row_extrusion_vista['int_total_kilos_rp'];//$row_extrusion_vista['int_kilos_prod_rp'];//con desperdicio

            ?></td>
            <td id="fuente2"><?php echo $row_extrusion_vista['fecha_ini_rp']; ?></td>
            <td nowrap id="fuente2"><?php echo $row_extrusion_vista['fecha_fin_rp']; ?></td>
            <td id="fuente2">
            <?php 
               $f1 = new DateTime($row_extrusion_vista['fecha_ini_rp']);
               $f2 = new DateTime($row_extrusion_vista['fecha_fin_rp']);
               $d = $f1->diff($f2);
               echo $totalh = $d->format('%H:%I:%S'); 
               $totalh =horadecimalUna($totalh);
               $totalhorasTodosRollos +=horadecimalUna($totalh);
            ?> 
            </td>
            <td id="fuente2"><?php $rodami_horas=($totalh*60)-$tiempoM;echo horadecimalUna($rodami_horas/60)?></td>
            <td id="fuente2"><?php   
            echo $totalkdes=$row_extrusion_vista['int_kilos_desp_rp']; 
            $totalkd+=$totalkdes; ?></td>
            <td id="fuente2">
            <?php 
               echo $KILOXHORA=$row_extrusion_vista['int_kilosxhora_rp']; 
               $totalkh+=$KILOXHORA;
               $metrosl=$row_extrusion_vista['int_metro_lineal_rp'];
               $totalL+=$metrosl;
            ?></td>
            <td id="fuente2"><a href="produccion_registro_extrusionp_edit.php?id_op_rp=<?php echo $row_extrusion_vista['id_op_rp']; ?>&amp;id_rp=<?php echo $row_extrusion_vista['id_rp'];?>&amp;parcial=<?php echo $row_extrusion_vista['parcial'];?>">Editar</a>     
            </td>
          </tr>
          <?php } while ($row_extrusion_vista = mysql_fetch_assoc($extrusion_vista)); ?> <tr>
            <td colspan="2" id="detalle2"><strong><?php echo $totalkp; ?></strong></td>
            <td id="fuente2">&nbsp; </td>
            <td id="fuente2">&nbsp; </td>
            <td id="fuente2"><strong><?php echo redondear_decimal($totalhorasTodosRollos); ?></strong></td>
            <td id="fuente2">&nbsp;</td>
            <td id="fuente2"><strong><?php echo $totalkd; ?></strong></td>
            <td id="fuente2"><strong><?php echo redondear_decimal($totalkh/$rollosLiquidado);?></strong></td>

            <td id="fuente2">&nbsp;</td>
          </tr>     

          <tr>
            <td colspan="2" id="subppal2">Tiempo Muertos - Minutos</td>
            <td id="subppal2">Tiempos Preparacion - Minutos</td>
            <td id="subppal2">Kilos Producidos </td>
            <td id="subppal2">Desperdicios</td>
            <td id="subppal2">&nbsp;</td>
            <td id="subppal2">Kilos Reales</td>
            <td id="subppal2">Metros Lineal</td>
            <td id="subppal2">Total Rollos </td>
          </tr>

          <tr>
            <td colspan="2" id="fuente2"><strong><?php echo $tiempoM; ?></strong></td>
            <td id="fuente2"><strong><?php echo $tiempoP; ?></strong></td>
            <td id="fuente2"><strong><?php echo $totalkp; ?></strong></td>
            <td id="fuente2"><strong><?php echo $desperdicios; ?></strong></td>
            <td id="fuente2">&nbsp;</td>
            <td id="fuente2"><strong><?php echo redondear_decimal($totalkp-$desperdicios); ?></strong></td>
            <td id="fuente2"><strong><?php echo $totalL; ?></strong></td>
            <td id="fuente2"><strong><?php echo $trollos; ?></strong></td>
          </tr>
          <tr>
            <td colspan="9" id="subppal"><strong>RPM - %</strong></td>
          </tr>
          <tr>
            <td colspan="3" id="subppal2">TORNILLO A</td>
            <td colspan="2" id="subppal2">TORNILLO B</td>
            <td colspan="4" id="subppal2">TORNILLO C</td>
          </tr>
          <tr>
            <td colspan="2" id="fuente2">RPM</td>
            <td id="fuente2">%</td>
            <td id="fuente2">RPM</td>
            <td id="fuente2">%</td>
            <td colspan="3" id="fuente2">RPM</td>
            <td id="fuente2">%</td>
          </tr>
          <tr>
            <td colspan="2" id="fuente2"><?php echo $row_mezcla['int_ref1_rpm_pm']; ?></td>
            <td id="fuente2"><?php echo $row_mezcla['int_ref1_tol5_porc1_pm'] ?></td>
            <td id="fuente2"><?php echo $row_mezcla['int_ref2_rpm_pm']; ?></td>
            <td id="fuente2"><?php echo $row_mezcla['int_ref2_tol5_porc2_pm'] ?></td>
            <td colspan="3" id="fuente2"><?php echo $row_mezcla['int_ref3_rpm_pm']; ?></td>
            <td id="fuente2"><?php echo $row_mezcla['int_ref3_tol5_porc3_pm'] ?></td>
          </tr>
          <tr>
            <td colspan="9" id="subppal2"></td>
          </tr>
        </table></td>
      </tr>
    </table>
  </div>
</body>
</html>
<?php
mysql_free_result($usuario);
mysql_free_result($tiempoMuerto);
mysql_free_result($extrusion_vista);
mysql_free_result($mezcla);
mysql_free_result($tiempoPreparacion);
mysql_free_result($desperdicio);
mysql_free_result($producido);
?>
