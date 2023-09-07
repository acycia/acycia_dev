<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
?>
<?php require_once('Connections/conexion1.php'); ?>
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
$conexion = new ApptivaDB();

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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}


 

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
//EVALUANDO EL COSTO  
  $id_op=$_POST['id_op_r'];
  $KilosxHora_imp=$_POST['int_kilosxhora_rp'];
  $KILOSREALESIMP=$_POST['int_total_kilos_rp'];
    $FECHA_COMPLETA = $_POST['fechaI_r'];//SI LLEVA LAS TINTAS
      $FECHA_NOVEDAD_IMP = quitarHora($_POST['fechaI_r']);//quita hora 
     $horas_imp = $_POST['total_horas_rp'];

     $sqlcostoMP="SELECT Tbl_reg_kilo_producido.id_rpp_rp, insumo.valor_unitario_insumo AS VALORKILO, Tbl_reg_kilo_producido.valor_prod_rp AS CANTKILOS FROM  insumo,Tbl_reg_kilo_producido WHERE insumo.id_insumo = Tbl_reg_kilo_producido.id_rpp_rp AND Tbl_reg_kilo_producido.id_proceso_rkp='2' AND Tbl_reg_kilo_producido.op_rp = '$id_op' AND  Tbl_reg_kilo_producido.fecha_rkp='$FECHA_COMPLETA'"; 
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
  $COSTOTINTA = ($contValorI);    

  $sqlgeneral="SELECT * FROM `TblDistribucionHoras`  ORDER BY `fecha` DESC LIMIT 1";
  $resultgeneral= mysql_query($sqlgeneral);
  $numgeneral= mysql_num_rows($resultgeneral);
  if($numgeneral >='1')
  { 
    $TiempomeImp =  mysql_result($resultgeneral, 0, 'impresion');
    //IMPRESION
    $costoUnHGga_imp = mysql_result($resultgeneral, 0, 'gga_imp');
    $costoUnHCif_imp = mysql_result($resultgeneral, 0, 'cif_imp');
    $costoUnHGgv_imp = mysql_result($resultgeneral, 0, 'ggv_imp');
    $costoUnHGgf_imp = mysql_result($resultgeneral, 0, 'ggf_imp');
    $cifyggaImp=($costoUnHGga_imp+$costoUnHCif_imp+$costoUnHGgv_imp+$costoUnHGgf_imp);
  }else{$TiempomeImp='0';} 

  //SUELDOS DE TODOS LOS EMPLEADOS FUERA DE PROCESO 
  $sqlbasico="SELECT COUNT(a.codigo_empleado) AS operarios,(a.horas_empleado) AS HORADIA, SUM(b.sueldo_empleado) AS SUELDO, SUM(b.aux_empleado) AS AUXILIO, SUM(c.total) AS APORTES
  FROM empleado a 
  LEFT JOIN TblProcesoEmpleado b ON a.codigo_empleado=b.codigo_empleado  
  LEFT JOIN TblAportes c ON a.codigo_empleado=c.codigo_empl 
WHERE b.estado_empleado='1' AND a.tipo_empleado NOT IN(4,5,6,7,8,9,10)";//NOT IN(4,5,6,7,8,9,10) son los que estan fuera de los procesos SE AGREGO b.estado_empleado='1' AND
$resultbasico=mysql_query($sqlbasico);
$operario_imp_demas=mysql_result($resultbasico,0,'operarios');
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
WHERE a.tipo_empleado NOT IN(4,5,6,7,8,9,10) AND b.fecha BETWEEN DATE_FORMAT('$FECHA_NOVEDAD_IMP', '%Y-%m-01') AND DATE_FORMAT('$FECHA_NOVEDAD_IMP', '%Y-%m-31')";//NOT IN(4,5,6,7,8,9,10) son los que estan fuera de los procesos
$resultnovbasico=mysql_query($sqlnovbasico);  
$pago_novbasico=mysql_result($resultnovbasico,0,'pago'); 
$extras_novbasico=mysql_result($resultnovbasico,0,'extras');  
$recargo_novbasico=mysql_result($resultnovbasico,0,'recargo');
$festivo_novbasico=mysql_result($resultnovbasico,0,'festivos');
  $horasmes_imp='240';//240 mientras se define horas al mes
  //OPERO TODOS LOS SUELDOS ETC, PARA SACAR EL COSTO HORA DE LOS FUERA DE PROCESOS
 $valorhoraxoperImpDemas = sueldoMes($sueldo_bas,$auxilio_bas,$aportes_bas,$horasmes_imp,$horasdia_bas,$recargo_novbasico,$festivo_novbasico); 
  $valorHoraImpDemas = ($valorhoraxoperImpDemas/$operario_imp_demas)/3;//total Horas se divide por # de operarios de fuera de los procesos dividido en 3 q son los procesos

    //SUELDOS DE TODOS LOS EMPLEADOS DENTRO DE IMPRESION 
  $sqlbasico="SELECT COUNT(a.codigo_empleado) AS operarios,(a.horas_empleado) AS HORADIA, SUM(b.sueldo_empleado) AS SUELDO, SUM(b.aux_empleado) AS AUXILIO, SUM(c.total) AS APORTES
  FROM empleado a 
  LEFT JOIN TblProcesoEmpleado b ON a.codigo_empleado=b.codigo_empleado  
  LEFT JOIN TblAportes c ON a.codigo_empleado=c.codigo_empl 
WHERE b.estado_empleado='1' AND a.tipo_empleado IN(5,10)";//IN(5,10) son impresion
$resultbasico=mysql_query($sqlbasico);
$operario_imp=mysql_result($resultbasico,0,'operarios');
  $sueldo_bas=mysql_result($resultbasico,0,'SUELDO'); //sueldos del mes 
  $auxilio_bas=mysql_result($resultbasico,0,'AUXILIO'); //auxilios trans del mes    
  $aportes_bas=mysql_result($resultbasico,0,'APORTES'); //aportes del mes 
  $horasdia_bas=mysql_result($resultbasico,0,'HORADIA');//esto es 8 
  $horasmes_imp='240';//240 mientras se define horas al mes
   //FIN   
   //NOVEDAD DE ESE MES DE TODOS LOS EMPLEADOS DENTRO DE IMPRESION 
 $sqlnovbasico="SELECT SUM(b.pago_acycia) as pago,SUM(b.horas_extras) as extras,SUM(b.recargos) as recargo,SUM(b.festivos) AS festivos 
 FROM empleado a 
 LEFT JOIN TblNovedades b ON a.codigo_empleado=b.codigo_empleado 
WHERE a.tipo_empleado IN(5,10) AND b.fecha BETWEEN DATE_FORMAT('$FECHA_NOVEDAD_IMP', '%Y-%m-01') AND DATE_FORMAT('$FECHA_NOVEDAD_IMP', '%Y-%m-31')";//IN(5,10)novedad impresion 
$resultnovbasico=mysql_query($sqlnovbasico);  
$pago_novbasico=mysql_result($resultnovbasico,0,'pago'); 
$extras_novbasico=mysql_result($resultnovbasico,0,'extras');  
$recargo_novbasico=mysql_result($resultnovbasico,0,'recargo');
$festivo_novbasico=mysql_result($resultnovbasico,0,'festivos');
  //FIN
  //OPERO TODOS LOS SUELDOS ETC, PARA SACAR EL COSTO HORA DE LOS FUERA DE PROCESOS
$valorhoraTodosImp = sueldoMes($sueldo_bas,$auxilio_bas,$aportes_bas,$horasmes_imp,$horasdia_bas,$recargo_novbasico,$festivo_novbasico);
  $kiloXHora=($KILOSREALESIMP/$horas_imp);//kilo x hora para los cif y gga
  $valorHoraImp = ($valorhoraTodosImp/$operario_imp);//total Horas se divide por # de operarios de Impresion    
    $costokiloInsumo=($COSTOTINTA/$KILOSREALESIMP);//$ costo de 1 kilos mp
  $manoObra=($horas_imp*($valorHoraImp+$valorHoraImpDemas))/$KILOSREALESIMP;//$ costo de 1 kilo mano obra en 1 hora
    $valorkilocifygga=($cifyggaImp/$kiloXHora);// $kiloXHora valor por hora de cif y gga  
    $COSTOHORAKILOIMP = ($costokiloInsumo+$manoObra+$valorkilocifygga);
  //FIN DE EVALUACION DEL COSTO
 
    $updateSQL2 = sprintf("UPDATE Tbl_reg_produccion SET id_op_rp=%s, int_cod_ref_rp=%s, version_ref_rp=%s, rollo_rp=%s, int_kilos_prod_rp=%s, int_kilos_desp_rp=%s, int_total_kilos_rp=%s, int_totalKilos_tinta_rp=%s, porcentaje_op_rp=%s, int_metro_lineal_rp=%s, total_horas_rp=%s, rodamiento_rp=%s, horas_muertas_rp=%s, horas_prep_rp=%s, str_maquina_rp=%s, str_responsable_rp=%s, fecha_ini_rp=%s, fecha_fin_rp=%s, int_kilosxhora_rp=%s,int_metroxmin_rp=%s, int_cod_empleado_rp=%s, int_cod_liquida_rp=%s, costo=%s WHERE id_rp=%s",
     GetSQLValueString($_POST['id_op_r'], "int"),
     GetSQLValueString($_POST['ref_r'], "text"),
     GetSQLValueString($_POST['version'], "int"),
     GetSQLValueString($_POST['rollo_r'], "int"),
     GetSQLValueString($_POST['int_kilos_prod_rp'], "double"),
     GetSQLValueString($_POST['int_kilos_desp_rp'], "double"),
     GetSQLValueString($_POST['int_total_kilos_rp'], "double"),
     GetSQLValueString($_POST['int_totalKilos_tinta_rp'], "double"),
     GetSQLValueString($_POST['porcentaje'], "int"),
     GetSQLValueString($_POST['metro_r2'], "int"),
     GetSQLValueString($_POST['total_horas_rp'], "text"),
     GetSQLValueString($_POST['tiempoOptimo_rp'], "text"),
     GetSQLValueString($_POST['horas_muertas_rp'], "int"),
     GetSQLValueString($_POST['horas_prep_rp'], "int"),
     GetSQLValueString($_POST['str_maquina_rp'], "text"),
     GetSQLValueString($_POST['str_responsable_rp'], "text"),
     GetSQLValueString($_POST['fechaI_r'], "date"),
     GetSQLValueString($_POST['fechaF_r'], "date"),
     GetSQLValueString($_POST['int_kilosxhora_rp'], "double"),
     GetSQLValueString($_POST['int_metroxmin_rp'], "text"), 
     GetSQLValueString($_POST['cod_empleado_r'], "int"),
     GetSQLValueString($_POST['cod_auxiliar_r'], "int"),
     GetSQLValueString($COSTOHORAKILOIMP, "int"),
     GetSQLValueString($_POST['id_rp'], "int"));
    mysql_select_db($database_conexion1, $conexion1);
    $Result2 = mysql_query($updateSQL2, $conexion1) or die(mysql_error());  
    $updateSQL = sprintf("UPDATE TblImpresionRollo SET id_op_r=%s, ref_r=%s, id_c_r=%s, tratInter_r=%s, tratExt_r=%s, pigmInt_r=%s, pigmExt_r=%s, calibre_r=%s, presentacion_r=%s, cod_empleado_r=%s, cod_auxiliar_r=%s, turno_r=%s, fechaI_r=%s, fechaF_r=%s, metro_r=%s, kilos_r=%s, desf_r=%s, tante_r=%s, manch_r=%s, color_r=%s, empat_r=%s, medid_r=%s, rasqueta_r=%s, bandera_r=%s, montaje_r=%s, apagon_r=%s, observ_r=%s, costo_r=%s,desf2_r=%s,tante2_r=%s,manch2_r=%s,color2_r=%s,empat2_r=%s,medid2_r=%s,rasqueta2_r=%s,apagon2_r=%s,montaje2_r=%s WHERE id_r=%s",
      /* GetSQLValueString($_POST['rollo_r'], "int"),*/
      GetSQLValueString($_POST['id_op_r'], "int"),
      GetSQLValueString($_POST['ref_r'], "text"),
      GetSQLValueString($_POST['id_c_r'], "int"),
      GetSQLValueString($_POST['tratInter_r'], "text"),
      GetSQLValueString($_POST['tratExt_r'], "text"),
      GetSQLValueString($_POST['pigmInt_r'], "text"),
      GetSQLValueString($_POST['pigmExt_r'], "text"),
      GetSQLValueString($_POST['calibre_r'], "double"),
      GetSQLValueString($_POST['presentacion_r'], "text"),
      GetSQLValueString($_POST['cod_empleado_r'], "int"),
      GetSQLValueString($_POST['cod_auxiliar_r'], "int"),
      GetSQLValueString($_POST['turno_r'], "int"),
      GetSQLValueString($_POST['fechaI_r'], "date"),
      GetSQLValueString($_POST['fechaF_r'], "date"),
      GetSQLValueString($_POST['metro_r2'], "int"),
      GetSQLValueString($_POST['int_total_kilos_rp'], "double"),
      GetSQLValueString($_POST['desf_r'], "int"),
      GetSQLValueString($_POST['tante_r'], "int"),
      GetSQLValueString($_POST['manch_r'], "int"),
      GetSQLValueString($_POST['color_r'], "int"),
      GetSQLValueString($_POST['empat_r'], "int"),
      GetSQLValueString($_POST['medid_r'], "int"),
      GetSQLValueString($_POST['rasqueta_r'], "int"),
      GetSQLValueString($_POST['bandera_r'], "int"),
      GetSQLValueString($_POST['montaje_r'], "int"),
      GetSQLValueString($_POST['apagon_r'], "int"),
      GetSQLValueString($_POST['observ_r'], "text"),
      GetSQLValueString($COSTOHORAKILOIMP, "int"),
      GetSQLValueString($_POST['desf2_r'], "text"),
      GetSQLValueString($_POST['tante2_r'], "text"),
      GetSQLValueString($_POST['manch2_r'], "text"),
      GetSQLValueString($_POST['color2_r'], "text"),
      GetSQLValueString($_POST['empat2_r'], "text"),
      GetSQLValueString($_POST['medid2_r'], "text"),
      GetSQLValueString($_POST['rasqueta2_r'], "text"),
      GetSQLValueString($_POST['apagon2_r'], "text"),
      GetSQLValueString($_POST['montaje2_r'], "text"),
      GetSQLValueString($_POST['id_r'], "int"));

    mysql_select_db($database_conexion1, $conexion1);
    $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());
   //apartir de aqui se registran los tiempos muertos y desperdicios
    if (!empty ($_POST['id_rpt'])&&!empty ($_POST['valor_tiem_rt'])){
      foreach($_POST['id_rpt'] as $key=>$v)
        $a[]= $v;
      foreach($_POST['valor_tiem_rt'] as $key=>$v)
        $b[]= $v;
      $c= $_POST['id_op_r'];  

      for($i=0; $i<count($a); $i++) {
      if(!empty($a[$i])&&!empty($b[$i])){ //no salga error con campos vacios
       $insertSQLt = sprintf("INSERT INTO Tbl_reg_tiempo (id_rpt_rt,valor_tiem_rt,op_rt,int_rollo_rt,id_proceso_rt,fecha_rt) VALUES (%s, %s, %s, %s, %s, %s)",                      
         GetSQLValueString($a[$i], "int"),
         GetSQLValueString($b[$i], "int"),
         GetSQLValueString($c, "int"),
         GetSQLValueString($_POST['rollo_r'], "int"),
         GetSQLValueString($_POST['id_proceso_rp'], "int"),
         GetSQLValueString($_POST['fechaI_r'], "date"));
       mysql_select_db($database_conexion1, $conexion1);
       $Resultt = mysql_query($insertSQLt, $conexion1) or die(mysql_error());
     }
   }
 }
 if (!empty ($_POST['id_rtp'])&&!empty ($_POST['valor_prep_rtp'])){
  foreach($_POST['id_rtp'] as $key=>$n)
    $h[]= $n;
  foreach($_POST['valor_prep_rtp'] as $key=>$n)
    $l[]= $n;
  $c= $_POST['id_op_r'];  

  for($x=0; $x<count($h); $x++) {
      if(!empty($h[$x])&&!empty($l[$x])){ //no salga error con campos vacios
       $insertSQLp = sprintf("INSERT INTO Tbl_reg_tiempo_preparacion (id_rpt_rtp,valor_prep_rtp,op_rtp,int_rollo_rtp,id_proceso_rtp,fecha_rtp) VALUES (%s, %s, %s, %s, %s, %s)",                      
         GetSQLValueString($h[$x], "int"),
         GetSQLValueString($l[$x], "int"),
         GetSQLValueString($c, "int"),
         GetSQLValueString($_POST['rollo_r'], "int"),
         GetSQLValueString($_POST['id_proceso_rp'], "int"),
         GetSQLValueString($_POST['fechaI_r'], "date"));
       mysql_select_db($database_conexion1, $conexion1);
       $Resultp = mysql_query($insertSQLp, $conexion1) or die(mysql_error());
     }
   }
 }
 if (!empty ($_POST['id_rpd'])&&!empty ($_POST['valor_desp_rd'])){
  foreach($_POST['id_rpd'] as $key=>$k)
    $f[]= $k;
  foreach($_POST['valor_desp_rd'] as $key=>$k)
    $g[]= $k;
  $c= $_POST['id_op_r'];  

  for($s=0; $s<count($f); $s++) {
      if(!empty($f[$s])&&!empty($g[$s])){ //no salga error con campos vacios

        /*$id_proceso_rp = $conexion->seleccionProceso($f[$s],$_POST['rollo_r'],$_POST['id_op_r'],$g[$s]);
        $id_proceso_rp = $id_proceso_rp=='' ? $_POST['id_proceso_rp'] : $id_proceso_rp; */
        $id_proceso_rp = $_POST['id_proceso_rp'];

       $insertSQLd = sprintf("INSERT INTO Tbl_reg_desperdicio (id_rpd_rd,valor_desp_rd,op_rd,int_rollo_rd,id_proceso_rd,fecha_rd,cod_ref_rd) VALUES (%s, %s, %s, %s, %s, %s, %s)",                      
         GetSQLValueString($f[$s], "int"),
         GetSQLValueString($g[$s], "double"),
         GetSQLValueString($c, "int"),
         GetSQLValueString($_POST['rollo_r'], "int"),
         GetSQLValueString($id_proceso_rp, "int"),
         GetSQLValueString($_POST['fechaI_r'], "date"),
         GetSQLValueString($_POST['int_cod_ref_rp'], "text"));
       mysql_select_db($database_conexion1, $conexion1);
       $Resultd = mysql_query($insertSQLd, $conexion1) or die(mysql_error());
     }
   }
 }   
 $updateGoTo = "produccion_impresion_stiker_rollo_vista.php?id_r=" . $_POST['id_r'] . "";
 if (isset($_SERVER['QUERY_STRING'])) {
  $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
  $updateGoTo .= $_SERVER['QUERY_STRING'];
}
header(sprintf("Location: %s", $updateGoTo));
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

$colname_rollo_linc = "-1";
if (isset($_GET['id_r'])) {
  $colname_rollo_linc = (get_magic_quotes_gpc()) ? $_GET['id_r'] : addslashes($_GET['id_r']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_rp = sprintf("SELECT * FROM TblImpresionRollo,Tbl_reg_produccion WHERE Tbl_reg_produccion.id_proceso_rp='2' AND TblImpresionRollo.id_op_r = Tbl_reg_produccion.id_op_rp AND TblImpresionRollo.id_r=%s",$colname_rollo_linc);
$rp_edit= mysql_query($query_rp, $conexion1) or die(mysql_error());
$row_rp_edit = mysql_fetch_assoc($rp_edit);
$totalRows_rp_edit = mysql_num_rows($rp_edit);
//CODIGO EMPLEADO
/*mysql_select_db($database_conexion1, $conexion1);
$query_codigo_empleado = "SELECT codigo_empleado,nombre_empleado,tipo_empleado FROM empleado WHERE tipo_empleado IN(5,10) ORDER BY nombre_empleado ASC";
$codigo_empleado = mysql_query($query_codigo_empleado, $conexion1) or die(mysql_error());
$row_codigo_empleado = mysql_fetch_assoc($codigo_empleado);
$totalRows_codigo_empleado = mysql_num_rows($codigo_empleado);*/

$row_codigo_empleado = $conexion->llenaSelect('empleado a INNER JOIN TblProcesoEmpleado b ','ON a.codigo_empleado=b.codigo_empleado WHERE a.tipo_empleado IN(5,10) AND b.estado_empleado=1 ','ORDER BY a.nombre_empleado ASC');
  
$row_revisor = $conexion->llenaSelect('empleado a INNER JOIN TblProcesoEmpleado b ','ON a.codigo_empleado=b.codigo_empleado WHERE a.tipo_empleado IN(5,10) AND b.estado_empleado=1 ','ORDER BY a.nombre_empleado ASC');

$colname_rollo_impresion_edit = "-1";
if (isset($_GET['id_r'])) {
  $colname_rollo_impresion_edit = (get_magic_quotes_gpc()) ? $_GET['id_r'] : addslashes($_GET['id_r']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_rollo_impresion_edit = sprintf("SELECT * FROM TblImpresionRollo WHERE TblImpresionRollo.id_r=%s",$colname_rollo_impresion_edit);
$rollo_impresion_edit = mysql_query($query_rollo_impresion_edit, $conexion1) or die(mysql_error());
$row_rollo_impresion_edit = mysql_fetch_assoc($rollo_impresion_edit);
$totalRows_rollo_impresion_edit = mysql_num_rows($rollo_impresion_edit);
//EXISTE LIQUIDACION
$colname_liquidado = "-1";
if (isset($_GET['id_r'])) {
  $colname_liquidado = (get_magic_quotes_gpc()) ? $_GET['id_r'] : addslashes($_GET['id_r']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_liquidado = sprintf("SELECT * FROM TblImpresionRollo,Tbl_reg_produccion WHERE TblImpresionRollo.id_r=%s AND TblImpresionRollo.id_op_r = Tbl_reg_produccion.id_op_rp AND TblImpresionRollo.rollo_r=Tbl_reg_produccion.rollo_rp AND Tbl_reg_produccion.id_proceso_rp='2'",$colname_liquidado);
$liquidado_edit= mysql_query($query_liquidado, $conexion1) or die(mysql_error());
$row_liquidado_edit = mysql_fetch_assoc($liquidado_edit);
$totalRows_liquidado_edit = mysql_num_rows($liquidado_edit);

//CARGA LOS TIEMPOS MUERTOS
$id_op=$row_liquidado_edit['id_op_rp']; 
$rolloNum=$row_liquidado_edit['rollo_rp'];
$colname_tiempoMuerto= "-1";
if (isset($id_op)) {
  $colname_tiempoMuerto = (get_magic_quotes_gpc()) ? $id_op : addslashes($id_op);
}

$row_orden_produccion = $conexion->llenarCampos("tbl_orden_produccion ", "WHERE id_op='".$id_op."' AND b_borrado_op='0' ", "ORDER BY id_op DESC", " int_cod_ref_op ");

mysql_select_db($database_conexion1, $conexion1);
$query_tiempoMuerto = sprintf("SELECT *, valor_tiem_rt AS muertos FROM Tbl_reg_tiempo WHERE op_rt=%s AND int_rollo_rt = '$rolloNum' AND    id_proceso_rt='2' ORDER BY id_rpt_rt ASC",$colname_tiempoMuerto);
$tiempoMuerto = mysql_query($query_tiempoMuerto, $conexion1) or die(mysql_error());
$row_tiempoMuerto = mysql_fetch_assoc($tiempoMuerto);
$totalRows_tiempoMuerto = mysql_num_rows($tiempoMuerto);
//CARGA LOS TIEMPOS PREPARACION 
mysql_select_db($database_conexion1, $conexion1);
$query_tiempoPreparacion = sprintf("SELECT *, valor_prep_rtp AS preparacion FROM Tbl_reg_tiempo_preparacion WHERE op_rtp=%s AND int_rollo_rtp = $rolloNum AND id_proceso_rtp='2' ORDER BY id_rpt_rtp ASC",$colname_tiempoMuerto);
$tiempoPreparacion  = mysql_query($query_tiempoPreparacion , $conexion1) or die(mysql_error());
$row_tiempoPreparacion  = mysql_fetch_assoc($tiempoPreparacion );
$totalRows_tiempoPreparacion  = mysql_num_rows($tiempoPreparacion );
//CARGA LOS KILOS DESPERDICIOS
mysql_select_db($database_conexion1, $conexion1);
$query_desperdicio = sprintf("SELECT *, valor_desp_rd AS desperdicio FROM Tbl_reg_desperdicio WHERE op_rd=%s AND int_rollo_rd = $rolloNum AND id_proceso_rd='2' ORDER BY `id_rpd_rd` ASC",$colname_tiempoMuerto);
$desperdicio = mysql_query($query_desperdicio, $conexion1) or die(mysql_error());
$row_desperdicio = mysql_fetch_assoc($desperdicio);
$totalRows_desperdicio = mysql_num_rows($desperdicio);

//ORDENES DE PRODUCCION
$colname_liquidado_op = "-1";
if (isset($_GET['id_r'])) {
  $colname_liquidado_op = (get_magic_quotes_gpc()) ? $_GET['id_r'] : addslashes($_GET['id_r']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_lista_op = sprintf("SELECT Tbl_orden_produccion.id_op, Tbl_orden_produccion.id_ref_op FROM Tbl_orden_produccion,TblImpresionRollo WHERE TblImpresionRollo.id_op_r = Tbl_orden_produccion.id_op AND  TblImpresionRollo.id_r=%s",$colname_liquidado_op);
$lista_op = mysql_query($query_lista_op, $conexion1) or die(mysql_error());
$row_lista_op = mysql_fetch_assoc($lista_op);
$totalRows_lista_op = mysql_num_rows($lista_op);

//CARGA LAS TINTAS UTILIZADAS
$colname_liquidado_tintas = "-1";
if (isset($_GET['id_r'])) {
  $colname_liquidado_tintas = (get_magic_quotes_gpc()) ? $_GET['id_r'] : addslashes($_GET['id_r']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_totalTintas = sprintf("SELECT * FROM TblImpresionRollo, Tbl_reg_kilo_producido WHERE TblImpresionRollo.id_r=%s AND TblImpresionRollo.id_op_r=Tbl_reg_kilo_producido.op_rp AND Tbl_reg_kilo_producido.id_proceso_rkp='2'",$colname_liquidado_tintas);
$totalTintas = mysql_query($query_totalTintas, $conexion1) or die(mysql_error());
$row_totalTintas = mysql_fetch_assoc($totalTintas);
$totalRows_totalTintas = mysql_num_rows($totalTintas);
//MAQUINAS
mysql_select_db($database_conexion1, $conexion1);
$query_maquinas = "SELECT * FROM maquina WHERE proceso_maquina='2' ORDER BY id_maquina DESC";
$maquinas= mysql_query($query_maquinas, $conexion1) or die(mysql_error());
$row_maquinas = mysql_fetch_assoc($maquinas);
$totalRows_maquinas = mysql_num_rows($maquinas);
//TIEMPOS LLENA COMBOS DINAMICOS
mysql_select_db($database_conexion1, $conexion1);
$query_tiempo_muertos = "SELECT * FROM Tbl_reg_tipo_desperdicio WHERE Tbl_reg_tipo_desperdicio.id_proceso_rtd='2' AND Tbl_reg_tipo_desperdicio.codigo_rtp='1' AND estado_rtp='0' ORDER BY Tbl_reg_tipo_desperdicio.nombre_rtp ASC";
$tiempo_muertos = mysql_query($query_tiempo_muertos, $conexion1) or die(mysql_error());
$row_tiempo_muertos = mysql_fetch_assoc($tiempo_muertos);
$totalRows_tiempo_muertos = mysql_num_rows($tiempo_muertos);

mysql_select_db($database_conexion1, $conexion1);
$query_tiempo_preparacion = "SELECT * FROM Tbl_reg_tipo_desperdicio WHERE Tbl_reg_tipo_desperdicio.id_proceso_rtd='2' AND Tbl_reg_tipo_desperdicio.codigo_rtp='2' AND estado_rtp='0' ORDER BY Tbl_reg_tipo_desperdicio.nombre_rtp ASC";
$tiempo_preparacion = mysql_query($query_tiempo_preparacion, $conexion1) or die(mysql_error());
$row_tiempo_preparacion = mysql_fetch_assoc($tiempo_preparacion);
$totalRows_tiempo_preparacion = mysql_num_rows($tiempo_preparacion);
//DESPERDICIOS
mysql_select_db($database_conexion1, $conexion1);
$query_desperdicios = "SELECT * FROM Tbl_reg_tipo_desperdicio WHERE Tbl_reg_tipo_desperdicio.id_proceso_rtd='2' AND Tbl_reg_tipo_desperdicio.codigo_rtp='3' AND estado_rtp='0' ORDER BY Tbl_reg_tipo_desperdicio.nombre_rtp ASC";
$desperdicios = mysql_query($query_desperdicios, $conexion1) or die(mysql_error());
$row_desperdicios = mysql_fetch_assoc($desperdicios);
$totalRows_desperdicios = mysql_num_rows($desperdicios);

 

$registros = $conexion->llenaListas('Tbl_reg_kilo_producido', "WHERE Tbl_reg_kilo_producido.op_rp='$id_op' AND Tbl_reg_kilo_producido.id_proceso_rkp='2' ",'ORDER BY Tbl_reg_kilo_producido.id_rkp ASC','id_rkp,id_rpp_rp,valor_prod_rp' );

?>
<html>
<head>
  <title>SISADGE AC &amp; CIA</title>
  <script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
  <link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">

  <link href="css/formato.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="js/formato.js"></script>
  <script type="text/javascript" src="js/consulta.js"></script>
  <script type="text/javascript" src="js/validacion_numerico.js"></script>
  <link rel="stylesheet" type="text/css" href="css/desplegable.css" />
  <link rel="stylesheet" type="text/css" href="css/general.css"/>

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

  <script type="text/javascript">
    function alerta2(){
     DatosGestiones3('6','id_rp',document.form1.id_rp.value,'&fechaI',document.form1.fechaI_r.value);

//alert('si cambia esta fecha, elimine primero los registros de tiempos, preparacion y desperdicios')
}
</script>
<script>
//Metodo II: Deshabilitar el botón Enviar
function checkSubmit() {
  document.getElementById("btsubmit").value = "Enviando...";
  document.getElementById("btsubmit").disabled = true;
  return true;
}
</script>
<script language="javascript" type="text/javascript">
  var upload_number=1;
  function tiemposM() {
   var i=0;
   var d = document.createElement("div");
   var file0 = document.createElement("select");
   file0.setAttribute("name", "id_rpt[]");
   file0.setAttribute("onChange", "restakilosT()" );
   file0.setAttribute("required", "required");
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
 file.setAttribute("placeholder", "minutos" );
 file.setAttribute("style", "width:65px" );
 file.setAttribute("onChange", "restakilosT()" ); 
 file.setAttribute("required", "required");
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
   file0.setAttribute("required", "required");
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
 file.setAttribute("placeholder", "minutos" );
 file.setAttribute("style", "width:65px" );
 file.setAttribute("onChange", "restakilosT()" ); 
 file.setAttribute("required", "required");
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
   /*file0.setAttribute("onChange", "restakilosD()");*/
   file0.setAttribute("required", "required"); 
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
 file.setAttribute("placeholder", "kilos" );
 file.setAttribute("style", "width:65px" );
 file.setAttribute("onBlur", "restakilosD()" );
 file.setAttribute("required", "required");
 f.appendChild(file); 

 document.getElementById("moreUploads3").appendChild(f);
 upload_number++;
}
</script>
</head>
<body onLoad="restakilosT();restakilosD();">
 <?php echo $conexion->header('vistas'); ?>
                 <form action="<?php echo $editFormAction; ?>" method="POST" name="form1" onSubmit="return validacion_unodelosdos_imp()">
                  <table align="center" class="table table-bordered table-sm">
                    <tr>
                      <td rowspan="4" id="fondo"><img src="images/logoacyc.jpg" width="97" height="71"/></td>
                      <td colspan="4" id="titulo2">IDENTIFICACION MATERIALES IMPRESION
                        <?php 
                        $id_op=$row_rollo_impresion_edit['id_op_r'];
                        $sqlre="SELECT COUNT(DISTINCT rollo_r) AS max_rolloE,SUM(metro_r) as metro_r, SUM(kilos_r) as kilos_r FROM TblExtruderRollo WHERE id_op_r=$id_op"; 
                        $resultre=mysql_query($sqlre); 
                        $numre=mysql_num_rows($resultre); 
                        if($numre >= '1') 
                        { 
                         $max_rolloE =mysql_result($resultre,0,'max_rolloE'); 
                         $metrosE=mysql_result($resultre,0,'metro_r'); 
                       } 
      //$nuevosMetros = regladetres($kilosR,$metrosE,$kilosT);//metros nuevos para impresion    
                       ?>
                     </td>
                   </tr>
                   <tr>
                    <td colspan="4" id="numero2">ROLLO N&deg; <?php echo $row_rollo_impresion_edit['rollo_r'];if ($max_rolloE!='') {echo " de ".$max_rolloE;} ?>

                    <input type="hidden" name="id_ref_r" id="id_ref_r" value="<?php echo $row_lista_op['id_ref_op']; ?>" readonly>
                    <input type="hidden" name="rollo_r" id="rollo_r" style="width:40px" value="<?php echo $row_rollo_impresion_edit['rollo_r']; ?>">
                    <input type="hidden" name="id_r" id="idrollo" value="<?php echo $row_rollo_impresion_edit['id_r']; ?>">
                    <input type="hidden" name="id_rp" id="id_rp" value="<?php echo $row_liquidado_edit['id_rp']; ?>"></td>
                  </tr>
                  <tr>
                    <td id="talla3">&nbsp;Rollos impresos hasta el momento:</td>
                    <td colspan="3" id="fuente1">
                      <?php
                      $id_op=$row_rollo_impresion_edit['id_op_r'];  
                      $sqlrI="SELECT COUNT(DISTINCT rollo_r) AS rollosI FROM TblImpresionRollo WHERE id_op_r='$id_op'"; 
                      $resultrI=mysql_query($sqlrI); 
                      $numrI=mysql_num_rows($resultrI); 
                      if($numrI >= '1') 
                      {  
                       $rollos_imp = mysql_result($resultrI,0,'rollosI');
                     }      
                     $id_op=$row_rollo_impresion_edit['id_op_r'];  
                     $result = mysql_query("SELECT rollo_r FROM TblImpresionRollo WHERE id_op_r='$id_op' ORDER BY rollo_r ASC"); 
                     if ($row = mysql_fetch_array($result)){  
                      do { 
                        echo $row["rollo_r"].", "."\n"; 
                      } while ($row = mysql_fetch_array($result)); 
                    } else { 
                     echo "! Aun no hay Rollos!"; 
                   } 

                   $rollo_r=$row_rollo_impresion_edit['rollo_r'];
                   $sqlFE="SELECT metro_r,fechaI_r FROM TblExtruderRollo WHERE id_op_r=$id_op AND rollo_r='$rollo_r'"; 
                   $resultFE=mysql_query($sqlFE); 
                   $numFE=mysql_num_rows($resultFE); 
                   if($numFE >= '1') 
                   { 
                     $fechaExt =mysql_result($resultFE,0,'fechaI_r'); 
                     $metroExt =mysql_result($resultFE,0,'metro_r');
                   }      
                   ?></td>
                 </tr>
                 <tr>
                  <td colspan="5" id="fuente3"><a href="produccion_impresion_stiker_rollo_vista.php?id_r=<?php echo $row_rollo_impresion_edit['id_r']; ?>"><img src="images/hoja.gif" alt="VISTA" title="VISTA" border="0" /></a><a href="produccion_impresion_listado_rollos.php?id_op_r=<?php echo $row_rollo_impresion_edit['id_op_r']; ?>&rollo_r=<?php echo $row_rollo_impresion_edit['rollo_r']; ?>"><img src="images/opciones.gif" alt="LISTADO ROLLOS"title="LISTADO ROLLO" border="0" style="cursor:hand;"/></a>
                    <?php if($row_usuario['tipo_usuario']==1){?><a href="produccion_impresion_stiker_rollo_colas_vista.php?id_op_r=<?php echo $row_rollo_impresion_edit['id_op_r']; ?>"><img src="images/t.gif" alt="IMPRESION TODOS LOS ROLLOS" title="IMPRESION TODOS LOS ROLLOS" border="0" /></a>
                    <a href="javascript:eliminar1('id_ri',<?php echo $row_rollo_impresion_edit['id_r']; ?>,'produccion_impresion_listado_rollos.php')"><img src="images/por.gif" alt="ELIMINAR" title="ELIMINAR" border="0" style="cursor:hand;"/></a>
                  <?php }?>
                  <?php  if($totalRows_liquidado_edit > '0') {?>
                    <a href="produccion_registro_impresion_vista.php?id_op=<?php echo $row_rollo_impresion_edit['id_op_r']; ?>&id_r=<?php echo $row_rollo_impresion_edit['id_r']; ?>"><img src="images/palomita.gif" style="cursor:hand;" alt="LIQUIDADO" title="LIQUIDADO" border="0"/></a><a href="javascript:location.reload()">
                    <?php }?>
                    <img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>

                  </tr>
                  <tr>
                    <td colspan="5" id="titulo1">INFORMACION GENERAL DE LA O.P.</td>
                  </tr>
                  <tr>
                    <td id="fuente1">ORDEN P</td>
                    <td id="fuente1"><input name="id_op_r" type="text" id="id_op_r" value="<?php echo $row_rollo_impresion_edit['id_op_r']; ?>" size="11" readonly/></td>
                    <td id="fuente1">REF.</td>
                    <td colspan="2" id="fuente1"><input type="number" name="ref_r" id="ref_r" min="0" max="20" style=" width:100px"value="<?php echo $row_rollo_impresion_edit['ref_r']; ?>" readonly>
                      <input id="version" name="version" type="hidden" value="<?php echo $row_liquidado_edit['version_ref_rp']; ?>" />
                      <input name="str_responsable_rp" type="hidden" id="str_responsable_rp" value="<?php echo $row_usuario['nombre_usuario']; ?>"/></td>
                    </tr>
                    <tr>
                      <td id="fuente1">CLIENTE</td>
                      <td colspan="4" id="fuente1"><?php $id_c=$row_rollo_impresion_edit['id_c_r'];
                      $sqln="SELECT id_c,nombre_c FROM cliente WHERE id_c='$id_c'"; 
                      $resultn=mysql_query($sqln); 
                      $numn=mysql_num_rows($resultn); 
                      if($numn >= '1') 
                        {$id_co=mysql_result($resultn,0,'id_c');  
                      $nombre_c=mysql_result($resultn,0,'nombre_c'); 
                      $cadenaN = htmlentities($nombre_c); echo $cadenaN; 
                    } ?><input type="hidden" name="id_c_r" id="id_c_r" value="<?php echo $id_co; ?>" size="11"></td>
                  </tr>          
                  <tr>
                    <td id="fuente1">TRATADO INTERNO</td>
                    <td id="fuente1"><select name="tratInter_r" id="tratInter_r" style="width:100px">
                      <option value="N.A"<?php if (!(strcmp('N.A', $row_rollo_impresion_edit['tratInter_r']))) {echo "selected=\"selected\"";} ?>>N.A</option>
                      <option value="UNA CARA" <?php if (!(strcmp('UNA CARA', $row_rollo_impresion_edit['tratInter_r']))) {echo "selected=\"selected\"";} ?>>UNA CARA</option>
                      <option value="DOBLE CARA" <?php if (!(strcmp('DOBLE CARA', $row_rollo_impresion_edit['tratInter_r']))) {echo "selected=\"selected\"";} ?>>DOBLE CARA</option>
                    </select></td>
                    <td id="fuente1">TRATADO EXTERNO</td>
                    <td colspan="2" id="fuente1"><select name="tratExt_r" id="tratExt_r" style="width:100px">
                      <option value="N.A"<?php if (!(strcmp('N.A', $row_rollo_impresion_edit['tratExt_r']))) {echo "selected=\"selected\"";} ?>>N.A</option>
                      <option value="UNA CARA" <?php if (!(strcmp('UNA CARA', $row_rollo_impresion_edit['tratExt_r']))) {echo "selected=\"selected\"";} ?>>UNA CARA</option>
                      <option value="DOBLE CARA" <?php if (!(strcmp('DOBLE CARA', $row_rollo_impresion_edit['tratExt_r']))) {echo "selected=\"selected\"";} ?>>DOBLE CARA</option>
                    </select></td>
                  </tr>
                  <tr>
                    <td id="fuente1">PIGMENTO INTERIOR</td>
                    <td id="fuente1"><input name="pigmInt_r" type="text" onKeyUp="conMayusculas(this)" value="<?php echo $row_rollo_impresion_edit['pigmInt_r']; ?>" size="11" readonly/></td>
                    <td id="fuente1">PIGMENTO EXTERIOR</td>
                    <td colspan="2" id="fuente1"><input name="pigmExt_r" type="text" onKeyUp="conMayusculas(this)" value="<?php echo $row_rollo_impresion_edit['pigmExt_r']; ?>" size="11" readonly/></td>
                  </tr>
                  <tr>
                    <td id="fuente1">CALIBRE MILS.</td>
                    <td id="fuente1"><input name="calibre_r" type="text" id="calibre_r" value="<?php echo $row_rollo_impresion_edit['calibre_r']; ?>" size="11" readonly/></td>
                    <td id="fuente1">PRESENTACION</td>
                    <td colspan="2" id="fuente1"><input name="presentacion_r" type="text" value="<?php echo $row_rollo_impresion_edit['presentacion_r']; ?>" size="11" readonly/></td>
                  </tr>
                  <tr>
                    <td colspan="5">            
                    </td>
                  </tr>
                  <tr>
                    <td colspan="5" id="titulo1">INFORMACION DEL ROLLO </td>
                  </tr>
                  <tr>
                    <td id="fuente1">OPERARIO</td>
                    <td id="fuente1">

                      <select name="cod_empleado_r"  style="width:120px" id="operario" onFocus="">
                          <option value=""<?php if (!(strcmp("", $row_rollo_impresion_edit['int_cod_empleado_r']))) {echo "selected=\"selected\"";} ?>>Seleccione</option> 
                            <?php  foreach($row_codigo_empleado as $row_codigo_empleado ) { ?>
                              <option value="<?php echo $row_codigo_empleado['codigo_empleado']?>"<?php if (!(strcmp($row_codigo_empleado['codigo_empleado'], $row_rollo_impresion_edit['cod_empleado_r']))) {echo "selected=\"selected\"";} ?>><?php echo $row_codigo_empleado['codigo_empleado']." - ".$row_codigo_empleado['nombre_empleado']." ".$row_codigo_empleado['apellido_empleado']?></option>
                            <?php } ?>
                      </select>  
                     
                      </td>
                     <td id="fuente1">AUXILIAR</td>
                    <td colspan="2" id="fuente1">

                      <select name="cod_auxiliar_r" style="width:120px" id="auxiliar">
                        <option value=""<?php if (!(strcmp("", $row_rollo_impresion_edit['cod_auxiliar_r']))) {echo "selected=\"selected\"";} ?>>Seleccione</option>
                          <?php  foreach($row_revisor as $row_revisor ) { ?>
                            <option value="<?php echo $row_revisor['codigo_empleado']?>"<?php if (!(strcmp($row_revisor['codigo_empleado'], $row_rollo_impresion_edit['cod_auxiliar_r']))) {echo "selected=\"selected\"";} ?>><?php echo $row_revisor['codigo_empleado']." - ".$row_revisor['nombre_empleado']." ".$row_revisor['apellido_empleado']?></option>
                          <?php } ?>
                        </select>
                     </td>
                  </tr>
                  <tr>
                   <td id="fuente1">TURNO</td>
                   <td id="fuente1"><input type="number" name="turno_r" id="turno_r" min="1" max="7" style="width:40px" value="<?php echo $row_rollo_impresion_edit['turno_r']; ?>" required></td>
                   <td id="fuente1">MAQUINA</td>
                   <td colspan="2" id="fuente1"><select name="str_maquina_rp" id="maquina"  style="width:120px" onblur="validacion_unodelosdos_imp();">
                     <option value=""<?php if (!(strcmp("", $row_liquidado_edit['str_maquina_rp']))) {echo "selected=\"selected\"";} ?>>Seleccione</option>
                     <?php
                     do {  
                      ?>
                      <option value="<?php echo $row_maquinas['id_maquina']?>"<?php if (!(strcmp($row_maquinas['id_maquina'], $row_liquidado_edit['str_maquina_rp']))) {echo "selected=\"selected\"";} ?>><?php echo $row_maquinas['nombre_maquina']?></option>
                      <?php
                    } while ($row_maquinas = mysql_fetch_assoc($maquinas));
                    $rows = mysql_num_rows($maquinas);
                    if($rows > 0) {
                      mysql_data_seek($maquinas, 0);
                      $row_maquinas = mysql_fetch_assoc($maquinas);
                    }
                    ?>
                  </select></td>
                </tr>
                <tr>
                  <td id="fuente1">FECHA EXTRUIDO</td>
                  <td id="fuente1"><?php echo $fechaExt;?></td>
                  <td colspan="3" id="fuente1"><p>&nbsp;</p>
                    <p>&nbsp;</p><input name="fechaV_r" type="datetime-local" min="2000-01-02" value="<?php echo muestradatelocal($row_rollo_impresion_edit['fechaV_r']); ?>" readonly required/>
                  F.IMPRESO</td>
                </tr> 
                <tr>
                  <td id="fuente1">FECHA INICIO</td>
                  <td id="fuente1"><input name="fechaI_r" id="fecha_ini_rp" type="datetime-local" min="2000-01-02" value="<?php echo muestradatelocal($row_rollo_impresion_edit['fechaI_r']); ?>" onChange="alerta2();restakilosT();" required/>
                    <input name="fechaExt" id="fechaExt" type="hidden" value="<?php echo $fechaExt;?>"></td>
                    <td colspan="3" id="fuente1"><input name="fechaF_r" id="fecha_fin_rp" type="datetime-local" min="2000-01-02" value="<?php echo muestradatelocal($row_rollo_impresion_edit['fechaF_r']); ?>" required  onChange="restakilosT();"onBlur="validacion_unodelosdos_imp()"/>
                    FECHA FIN</td>
                  </tr>               
                  <tr>
                    <td colspan="2" id="fuente1"><div id="resultado_generador"></div></td>
                    <td id="fuente1">&nbsp; </td>
                    <td id="fuente1">TIEMPO TOTAL </td>
                    <td id="fuente1">&nbsp;</td>
                  </tr>
                  <tr>
                    <td id="fuente1"><strong>METROS INICIAL:</strong></td>
                    <td id="fuente1"><input name="metro_r" type="number" id="metro_r" min="1" style="width:100px" value="<?php echo $metroExt; ?>" required onChange="restakilosT();" readonly/>
              <input name="metro_r3" type="hidden" id="metro_r3" min="1" style="width:100px" value="<?php //echo $row_rollo_impresion_edit['metro_r'];//
              if($row_liquidado_edit['int_metro_lineal_rp']==''){echo $row_rollo_impresion_edit['metro_r'];}else{echo $row_liquidado_edit['int_metro_lineal_rp'];} ?>" required onChange="restakilosT()" readonly/></td>
            <td id="fuente1"><input name="metro_r2" type="number" id="metro_r2" min="1" style="width:100px" value="<?php //echo $row_rollo_impresion_edit['metro_r'];//
            if($row_liquidado_edit['int_metro_lineal_rp']==''){echo $row_rollo_impresion_edit['metro_r'];}else{echo $row_liquidado_edit['int_metro_lineal_rp'];} ?>" required onChange="restakilosT();" readonly/></td>
            <td id="fuente1"><input name="total_horas_rp" type="text" id="total_horas_rp" style="width:100px" value="<?php echo $row_liquidado_edit['total_horas_rp']; ?>" readonly/></td>
            <td id="fuente3"><!-- <input type="submit" name="button" id="button_imp_rollo" value="EDITAR" > -->
              <input class="botonGeneral" type="submit" name="ENVIAR" id="ENVIAR" value="EDITAR" />
              <!--onClick="envio_form(this);"--></td>
            </tr>
            <tr>
              <td id="fuente1"><strong>PESO INICIAL:</strong></td>
              <td id="fuente7"><input type="number" name="int_kilos_prod_rp" id="int_kilos_prod_rp"  size="12" style="width:100px" value="<?php echo $row_liquidado_edit['int_kilos_prod_rp']; ?>" readonly >
                <input name="kilos_r" type="hidden" id="int_kilos_prod_rp2" min="1.00" step="0.01" style="width:100px" value="<?php echo $row_liquidado_edit['int_total_kilos_rp']; ?>" required   readonly/></td>
                <td id="fuente7"><input name="int_total_kilos_rp" type="number" id="int_total_kilos_rp" min="1.00" step="0.01" style="width:100px" value="<?php echo $row_liquidado_edit['int_total_kilos_rp']; ?>" required readonly/></td>
                <td colspan="2" id="fuente7">&nbsp;</td>
              </tr>
              <tr> 
                <td id="fuente1">&nbsp;</td>
                <td id="fuente1">&nbsp;</td>
                <td colspan="2" id="fuente1">&nbsp;</td>
              </tr>
     <tr>
       <td colspan="7">
          <table style="width:100%; " >   
            <tr id="tr1">
                <td id="fuente1"><input type="button" class="botonFinalizar" name="button3" id="button3" value="Desperdicios" onClick="if(form1.fechaI_r.value=='' || form1.fechaF_r.value=='') { alert('LA FECHA DE INICIO O FECHA FINAL ESTAN VACIOS')}else{tiemposD()}" style="width:125px"/></td>
                <td id="fuente1"><input type="button" class="botonFinalizar" name="button2" id="button2" value="Tiempos Muertos" onClick="if(form1.fechaI_r.value=='' || form1.fechaF_r.value=='') { alert('LA FECHA DE INICIO O FECHA FINAL ESTAN VACIOS')}else{tiemposM()}" style="width:125px"/></td>
                <td colspan="2" id="fuente1"><input type="button" class="botonFinalizar" name="button1" id="button1" value="Tiempos Preparacion" onClick="if(form1.fechaI_r.value=='' || form1.fechaF_r.value=='') { alert('LA FECHA DE INICIO O FECHA FINAL ESTAN VACIOS')}else{tiemposP()}" style="width:125px"/></td>
              </tr>
              <tr> 
                <td id="fuente1"><div id="moreUploads3"></div></td>
                <td id="fuente1"><div id="moreUploads" ></div></td>
                <td colspan="2" id="fuente1"><div id="moreUploads2"></div></td>
              </tr>
 
              <?php if($row_tiempoMuerto['id_rpt_rt']!='') {?>
                <tr> 
                  <td nowrap id="detalle2"><strong>Tiempos Muertos - Tipo</strong></td>
                  <td nowrap id="detalle2"><strong>Tiempos Muertos - Minutos</strong></td>
                  <td colspan="2" nowrap id="detalle2"><strong>ELIMINA</strong></td>
                </tr>
                <?php  for ($k=0;$k<=$totalRows_tiempoMuerto-1;$k++) { ?>
                  <tr> 
                    <td id="fuente1"><?php $id1=mysql_result($tiempoMuerto,$k,id_rpt_rt); 
                    $id_tm=$id1;
                    $sqltm="SELECT * FROM Tbl_reg_tipo_desperdicio WHERE id_rtp='$id_tm'";
                    $resulttm= mysql_query($sqltm);
                    $numtm= mysql_num_rows($resulttm);
                    if($numtm >='1')
                    { 
                      $nombre = mysql_result($resulttm, 0, 'nombre_rtp');echo $nombre; }?>
                      <strong>
                        <!--<input name="id_rpt[]" type="hidden" id="id_rpt[]" value="<?php echo $id1; ?>" size="6"/>-->
                      </strong></td>
                      <td id="fuente1"><?php $var1=mysql_result($tiempoMuerto,$k,muertos); echo $var1; $totalTM=$totalTM+$var1;?></td>
                      <td  colspan="2" id="fuente1"><a href="javascript:eliminar_varias('id_rtei',<?php $delrt=mysql_result($tiempoMuerto,$k,id_rt); echo $delrt; ?>,'id_r',<?php echo $row_rp_edit['id_r']; ?>,'produccion_impresion_stiker_rollo_edit.php')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR " title="ELIMINAR" border="0" /></a></td>
                  </tr><?php } ?>
                      <tr> 
                        <td id="fuente1">TOTAL</td>
                        <td id="fuente1"><strong>
                          <?php echo $totalTM;  ?>
                          <input name="valor_tiem_rt[]" type="hidden" id="valor_tiem_rt[]" value="<?php echo $totalTM; ?>" size="6" />
                        </strong></td>
                        <td  colspan="2" id="fuente1">&nbsp;</td>
                      </tr>
                    <?php } ?>
                    <?php if($row_tiempoPreparacion['id_rpt_rtp']!='') {?>
                      <tr> 
                        <td nowrap id="detalle2"><strong>Tiempos Preparacion - Tipo</strong></td>

                        <td nowrap id="detalle2"><strong>Tiempos Preparacion - Minutos</strong></td>
                        <td  colspan="2" nowrap id="detalle2"><strong>ELIMINA</strong></td>
                      </tr>
                      <?php  for ($x=0;$x<=$totalRows_tiempoPreparacion-1;$x++) { ?>
                        <tr> 
                          <td id="fuente1"><?php $id2=mysql_result($tiempoPreparacion,$x,id_rpt_rtp); 
                          $id_rtp=$id2;
                          $sqlrtp="SELECT * FROM Tbl_reg_tipo_desperdicio WHERE id_rtp='$id_rtp'";
                          $resultrtp= mysql_query($sqlrtp);
                          $numrtp= mysql_num_rows($resultrtp);
                          if($numrtp >='1')
                          { 
                           $nombre = mysql_result($resultrtp, 0, 'nombre_rtp');echo $nombre; }?>
                           <!--<input name="id_rtp[]" type="hidden" id="id_rtp[]" value="<?php echo $id2; ?>" size="6"/>--></td>
                           <td id="fuente1"><?php $var2=mysql_result($tiempoPreparacion,$x,preparacion); echo $var2; $totalTP=$totalTP+$var2; ?></td>
                           <td  colspan="2" id="fuente1"><a href="javascript:eliminar_varias('id_rpei',<?php $delrp=mysql_result($tiempoPreparacion,$x,id_rt); echo $delrp; ?>,'id_r',<?php echo $row_rp_edit['id_r']; ?>,'produccion_impresion_stiker_rollo_edit.php')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR " title="ELIMINAR" border="0" /></a></td>
                         </tr>
                        <?php } ?>
                           <tr> 
                            <td id="fuente1">TOTAL</td>
                            <td id="fuente1"><strong>
                              <?php echo $totalTP; ?>
                              <input name="valor_prep_rtp[]" type="hidden" id="valor_prep_rtp[]" value="<?php echo $totalTP; ?>" size="6" />
                            </strong></td>
                            <td colspan="2" id="fuente1">&nbsp;</td>
                          </tr>
                        <?php } ?>
                        <?php if($row_desperdicio['id_rpd_rd']!='') {?>
                          <tr> 
                            <td nowrap id="detalle2"><strong>Desperdicios - Tipo</strong></td>
                            <td nowrap id="detalle2"><strong>Desperdicios - Kilos</strong></td>
                            <td  colspan="2" nowrap id="detalle2"><strong>ELIMINA</strong></td>
                          </tr>
                          <?php  for ($i=0;$i<=$totalRows_desperdicio-1;$i++) { ?>
                            <tr> 
                              <td id="fuente1"><?php $id3=mysql_result($desperdicio,$i,id_rpd_rd); 
                              $id_rpd=$id3;
                              $sqlrtd="SELECT * FROM Tbl_reg_tipo_desperdicio WHERE id_rtp='$id_rpd'";
                              $resultrtd= mysql_query($sqlrtd);
                              $numrtd= mysql_num_rows($resultrtd);
                              if($numrtd >='1')
                              { 
                                $nombre2 = mysql_result($resultrtd, 0, 'nombre_rtp'); echo $nombre2; }?>
                                <!--<input name="id_rpd[]" type="hidden" id="id_rpd[]" value="<?php echo $id3; ?>" size="6"/>--></td>
                                <td id="fuente1"><?php $var3=mysql_result($desperdicio,$i,desperdicio); echo $var3; $totalMDD=$totalMDD+$var3;?>
                              </td>
                              <td  colspan="2" id="fuente1"><a href="javascript:eliminar_varias('id_rdei',<?php $delrd=mysql_result($desperdicio,$i,id_rd); echo $delrd; ?>,'id_r',<?php echo $row_rp_edit['id_r']; ?>,'produccion_impresion_stiker_rollo_edit.php')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR " title="ELIMINAR" border="0" /></a></td>
                              </tr>
                               <?php } ?>
                              <tr> 
                                <td id="fuente1">TOTAL</td>
                                <td id="fuente1" above><strong>
                                  <?php if($totalMDD!=''){echo $totalMDD;}else{echo "0";} ?>
                                </strong>
                                <input name="valor_desp_rd[]" type="hidden" id="valor_desp_rd[]" value="<?php echo $totalMDD; ?>" size="6"/>            <!--onClick="getSumD();"--><!--importante porque me acumula lo ingresado--></td>
                                <td  colspan="2" id="fuente1">&nbsp;</td>
                              </tr>
                            <?php } ?>           


                            <?php if($row_totalTintas['op_rp']!='') {?>
                             <tr> 
                               <td colspan="4" nowrap id="detalle2"><strong>Gasto Tintas - Solventes</strong></td>
                             </tr>
                             <tr id="tr1">
                             
                               <td colspan="2" nowrap="nowrap"id="detalle2">Tintas - Solventes</td>
                               <td colspan="2" nowrap="nowrap"id="detalle2">Kilos Ingresados</td>
                             </tr>           
                             <?php  do{ ?> 
                               <tr>   
                                 <td colspan="2" id="fuente1">
                                   <?php 
                                   $nombreMP=$row_totalTintas['id_rpp_rp']; 
                                   $sqlins="SELECT id_insumo,descripcion_insumo FROM insumo WHERE id_insumo='$nombreMP'";
                                   $resultins= mysql_query($sqlins);
                                   $numins= mysql_num_rows($resultins);
                                   if($numins >='1')
                                   { 
                                     $d_insu = mysql_result($resultins, 0, 'descripcion_insumo');echo $d_insu; }?></td>
                                     <td colspan="3" id="fuente1"><?php $Cant=$row_totalTintas['valor_prod_rp']; echo $Cant; $TotalTintas+=$Cant;?></td>      
                                   </tr>

                                 <?php } while ($row_totalTintas = mysql_fetch_assoc($totalTintas)); ?>
                        
                                 <td colspan="4" id="detalle2">
                                  <!-- <a href="javascript:verFoto('produccion_regist_impre_kilos_prod_edit.php?id_op=<?php echo $row_rp_edit['id_op_rp'] ?>&rollo=<?php echo $row_rp_edit['rollo_rp']?>&amp;fecha=<?php echo $row_rp_edit['fecha_ini_rp'] ?>&amp;id_ref=<?php echo $row_lista_op['id_ref_op'] ?>','840','640')" > Editar los valores de las unidades ingresadas</a> -->

                                  <input class="botonGeneral"  name="Editar los valores de las unidades ingresadas" id="Editar los valores de las unidades ingresadas" value="Ver-Tinta" onClick="editarTintas();" > 

                                </td>            
                              <?php } ?> 
                             <tr>
                              <td colspan="2" id="dato1"></td>
                              <td colspan="2" id="dato1"></td> 
                              <td colspan="2" id="dato1"></td> 
                           </tr>
                         </table>
                         </td> 
                   </tr>        

                              <tr>
                                <td id="fuente15">&nbsp;</td>
                                <td colspan="4" id="fuente15">&nbsp;</td>
                              </tr>

                              <tr> 
                                <td colspan="4" id="fuente4">
                                       
                                  <?php // if($rollos_imp >= $max_rolloE  ) { ?>
                                   
                                    <?php foreach($registros as $row_totalTintas) {$row_totalTintas; } ?>   

                                    <?php //if($row_totalTintas['id_rpp_rp']!=''){ ?>
                                          <!-- <input class="botonGeneral"  name="R. TINTAS" id="R. TINTAS" value="YA TIENE" onClick="editarTintas();" > --> 
                                          <input class="botonGeneral"  name="R. TINTAS" id="R. TINTAS" value="R. TINTAS" onClick="agregarTintas();" > 
                                             <?php //}else{  ?>
                                      <?php //} ?> 
                                  <?php //} ?>
                                 <input name="int_totalKilos_tinta_rp" type="hidden" id="int_totalKilos_tinta_rp" value="<?php echo $TotalTintas; ?>" size="6"/>

                               </td>
                               </tr>           
                               <tr>
                                <td colspan="5" id="titulo1">DEFECTOS</td>
                              </tr>
                              <tr>
                                <td id="fuente1">Desregistro</td>
                                <td id="fuente1"><input name="desf_r" type="number" id="desf_r" style="width:40px" min="0" max="9" value="<?php echo $row_rollo_impresion_edit['desf_r']; ?>" onChange="sumaBanderasI();">
                                  <input name="desf2_r" type="number" id="desf2_r" style="width:60px" min="0" value="<?php echo $row_rollo_impresion_edit['desf2_r']; ?>" >Metros</td>
                                <td id="fuente1">Tanteo</td>
                                <td id="fuente1"><input type="number" name="tante_r" min="0" max="9" id="tante_r" style="width:40px" value="<?php echo $row_rollo_impresion_edit['tante_r']; ?>" onChange="sumaBanderasI();">
                                  <input name="tante2_r" type="number" id="tante2_r" style="width:60px" min="0" value="<?php echo $row_rollo_impresion_edit['tante2_r']; ?>" >Metros</td>
                                <td id="fuente1">&nbsp;</td>
                              </tr>
                              <tr>
                                <td id="fuente1">Manchas</td>
                                <td id="fuente1"><input type="number" name="manch_r" min="0" max="9" id="manch_r" style="width:40px" value="<?php echo $row_rollo_impresion_edit['manch_r']; ?>" onChange="sumaBanderasI();">
                                  <input name="manch2_r" type="number" id="manch2_r" style="width:60px" min="0" value="<?php echo $row_rollo_impresion_edit['manch2_r']; ?>" >Metros</td>
                                <td id="fuente1">Color</td>
                                <td colspan="2" id="fuente1"><input type="number" name="color_r" min="0" max="9" id="color_r" style="width:40px" value="<?php echo $row_rollo_impresion_edit['color_r']; ?>" onChange="sumaBanderasI();">
                                  <input name="color2_r" type="number" id="color2_r" style="width:60px" min="0" value="<?php echo $row_rollo_impresion_edit['color2_r']; ?>" >Metros</td>
                              </tr>
                              <tr>
                                <td id="fuente1">Empates</td>
                                <td id="fuente1"><input type="number" name="empat_r" min="0" max="9" id="empat_r" style="width:40px" value="<?php echo $row_rollo_impresion_edit['empat_r']; ?>" onChange="sumaBanderasI();">
                                  <input name="empat2_r" type="number" id="empat2_r" style="width:60px" min="0" value="<?php echo $row_rollo_impresion_edit['empat2_r']; ?>" >Metros</td>
                                <td id="fuente1">Medida</td>
                                <td colspan="2" id="fuente1"><input type="number" name="medid_r" min="0" max="9" id="medid_r" style="width:40px" value="<?php echo $row_rollo_impresion_edit['medid_r']; ?>" onChange="sumaBanderasI();">
                                  <input name="medid2_r" type="number" id="medid2_r" style="width:60px" min="0" value="<?php echo $row_rollo_impresion_edit['medid2_r']; ?>" >Metros</td>
                              </tr>
                              <tr>
                                <td id="fuente1">Rasquetas</td>
                                <td id="fuente1"><input type="number" name="rasqueta_r" min="0" max="9" id="rasqueta_r" style="width:40px" value="<?php echo $row_rollo_impresion_edit['rasqueta_r']; ?>" onChange="sumaBanderasI();">
                                  <input name="rasqueta2_r" type="number" id="rasqueta2_r" style="width:60px" min="0" value="<?php echo $row_rollo_impresion_edit['rasqueta2_r']; ?>" >Metros</td>
                                <td id="fuente1">Apagón</td>
                                <td id="fuente1"><input type="number" name="apagon_r" min="0" max="9" id="apagon_r" style="width:40px" value="<?php echo $row_rollo_impresion_edit['apagon_r']; ?>" onChange="sumaBanderasI();">
                                  <input name="apagon2_r" type="number" id="apagon2_r" style="width:60px" min="0" value="<?php echo $row_rollo_impresion_edit['apagon2_r']; ?>" >Metros</td>
                              </tr>
                              <tr>
                                <td id="fuente1">Montaje</td>
                                <td id="fuente1"><input type="number" name="montaje_r" min="0" max="9" id="montaje_r" style="width:40px" value="<?php echo $row_rollo_impresion_edit['montaje_r']; ?>" onChange="sumaBanderasI();">
                                  <input name="montaje2_r" type="number" id="montaje2_r" style="width:60px" min="0" value="<?php echo $row_rollo_impresion_edit['montaje2_r']; ?>" >Metros</td>
                                <td id="fuente1"><strong>TOTAL BANDERAS</strong></td>
                                <td colspan="2" id="fuente1"><input name="bandera_r" type="number" id="bandera_r" value="<?php echo $row_rollo_impresion_edit['bandera_r']; ?>" style="width:40px" readonly  onClick="sumaBanderasI();"/></td>
                              </tr>
                              <tr>
                                <td colspan="5" id="dato1">Nota: Al guardar el rollo tambien se guarda los datos principales en la tabla maestra (<em>reg_produccion</em>)</td>
                              </tr>
                              <tr>
                                <td colspan="5" id="titulo1">OBSERVACIONES</td>
                              </tr>
                              <tr>
                                <td colspan="5" id="fuente2"><textarea name="observ_r" cols="75" rows="2" id="observ_r" onKeyUp="conMayusculas(this)"><?php echo $row_rollo_impresion_edit['observ_r']; ?></textarea>
                                  <input name="int_cod_ref_rp" type="hidden" id="int_cod_ref_rp" value="<?php echo $row_orden_produccion['int_cod_ref_op']; ?>" />
                                  <input name="int_kilosxhora_rp" type="hidden" id="int_kilosxhora_rp"  size="12" value="<?php echo $row_liquidado_edit['int_kilosxhora_rp']; ?>" onClick="restakilosT()"/>
                                  <input name="int_metroxmin_rp" type="hidden" id="metroxmin2" size="12" value="<?php echo $row_liquidado_edit['int_metroxmin_rp']; ?>" />
                                  <input type="hidden" name="tiempoOptimo_rp" id="tiempoOptimo_rp"   value="<?php echo $row_liquidado_edit['rodamiento_rp']; ?>">
                                  <input name="int_kilos_desp_rp" type="hidden" id="int_kilos_desp_rp"  size="12" value="<?php /*if($row_liquidado_edit['int_kilos_desp_rp'] >0){echo '0';}else{echo $row_liquidado_edit['int_kilos_desp_rp'];}*/
                                  echo $row_liquidado_edit['int_kilos_desp_rp'];?>" />
                                  <input type="hidden" name="horas_muertas_rp" id="horasmuertas"  size="12"  value="<?php /*if($row_liquidado_edit['horas_muertas_rp'] >0){echo '0';}else{echo $row_liquidado_edit['horas_muertas_rp'];}*/
                                  echo $row_liquidado_edit['horas_muertas_rp']; ?>">
                                  <input type="hidden" name="horas_prep_rp" id="horasprep"  size="12" value="<?php /*if($row_liquidado_edit['horas_prep_rp'] >0){echo '0';}else{echo $row_liquidado_edit['horas_prep_rp'];}*/
                                  echo $row_liquidado_edit['horas_prep_rp']; ?>" onClick="restakilosT()">
                                  <input type="hidden" name="id_proceso_rp" id="id_proceso_rp"  size="12" value="2" >
                                </td>
                              </tr>
                              <tr>
                                <td colspan="5" id="fuente5">&nbsp;</td>
                              </tr>
                              <tr>
                                <td colspan="5" id="fuente2"><input type="hidden" name="MM_update" value="form1"></td>
                              </tr>
                              <tr>
                                <td colspan="5" id="dato2"></td>
                              </tr>
                            </table>
                          </form>
                          <?php echo $conexion->header('footer'); ?>
                  </body>
                  </html>
                  <script type="text/javascript">
                    function editarTintas(){

                      verFoto('view_index.php?c=cmezclasIm&a=editaTintas&id_op=<?php echo $row_rollo_impresion_edit['id_op_r']; ?>&rollo=<?php echo $row_rp_edit['rollo_rp']?>&fecha=<?php echo $row_rp_edit['fecha_ini_rp'] ?>&id_ref=<?php echo $row_lista_op['id_ref_op'] ?>&cod_ref=<?php echo $row_rollo_impresion_edit['ref_r']; ?>','840','640')
                      //verFoto('produccion_regist_impre_kilos_prod_edit.php?id_op=<?php echo $row_rp_edit['id_op_rp'] ?>&rollo=<?php echo $row_rp_edit['rollo_rp']?>&amp;fecha=<?php echo $row_rp_edit['fecha_ini_rp'] ?>&amp;id_ref=<?php echo $row_lista_op['id_ref_op'] ?>','840','640')
                    }

                    function agregarTintas(){
                        if(document.getElementById('fecha_ini_rp').value==''){
                           swal("Debe llenar la fecha antes de agregar Tintas");
                           return false;
                        }else if(document.getElementById('ref_r').value==''){
                           swal("Debe llenar la ref_r antes de agregar Tintas");
                           return false;
                        }else if(document.getElementById('id_op_r').value==''){
                           swal("Debe llenar el numero de o.p antes de agregar Tintas");
                           return false;
                        }else if(document.getElementById('rollo_r').value==''){
                           swal("Debe llenar la rollo  antes de agregar Tintas");
                           return false;
                        }

                        verFoto('view_index.php?c=cmezclasIm&a=Tintas&cod_ref='+document.getElementById('ref_r').value+'&rollo='+document.getElementById('rollo_r').value+'&id_op='+document.getElementById('id_op_r').value+'&fecha='+document.getElementById('fecha_ini_rp').value+' ','1250','1050')
                    }

  </script>
  <?php
  mysql_free_result($usuario);

  mysql_free_result($codigo_empleado);

  mysql_free_result($lista_op);


  ?>