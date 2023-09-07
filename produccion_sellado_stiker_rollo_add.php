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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO TblSelladoRollo ( id_op_r, ref_r, bolsas_r,kilos_r, reproceso_r,rollo_r, maquina_r, numIni_r, numFin_r, cod_empleado_r, cod_auxiliar_r, turno_r, fechaI_r, fechaF_r) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['id_op_r'], "int"),
                       GetSQLValueString($_POST['ref_r'], "text"),
                       GetSQLValueString($_POST['bolsas_r'], "int"),
					   GetSQLValueString($_POST['kilos_r'], "double"),
					   GetSQLValueString($_POST['reproceso_r'], "int"),
					   GetSQLValueString($_POST['rollo_r'], "int"),
                       GetSQLValueString($_POST['maquina_r'], "int"),
                       GetSQLValueString($_POST['numIni_r'], "text"),
                       GetSQLValueString($_POST['numFin_r'], "text"),
                       GetSQLValueString($_POST['cod_empleado_r'], "int"),
                       GetSQLValueString($_POST['cod_auxiliar_r'], "int"),
                       GetSQLValueString($_POST['turno_r'], "int"),
                       GetSQLValueString($_POST['fechaI_r'], "text"),
                       GetSQLValueString($_POST['fechaF_r'], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());
 
  $updateSQL2 = sprintf("UPDATE TblInventarioListado SET Entrada=Entrada + %s WHERE Cod_ref = %s",
					   GetSQLValueString($_POST['bolsas_r'], "int"),
                       GetSQLValueString($_POST['ref_r'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result2 = mysql_query($updateSQL2, $conexion1) or die(mysql_error());
  
  $insertGoTo = "produccion_sellado_stiker_rollo_add.php";
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

//ORDENES DE PRODUCCION
mysql_select_db($database_conexion1, $conexion1);
$query_lista_op = "SELECT id_op FROM Tbl_orden_produccion WHERE b_estado_op!='0' ORDER BY Tbl_orden_produccion.id_op DESC";
$lista_op = mysql_query($query_lista_op, $conexion1) or die(mysql_error());
$row_lista_op = mysql_fetch_assoc($lista_op);
$totalRows_lista_op = mysql_num_rows($lista_op);
//CODIGO EMPLEADO
mysql_select_db($database_conexion1, $conexion1);
$query_codigo_empleado = "SELECT codigo_empleado,nombre_empleado,tipo_empleado FROM empleado WHERE tipo_empleado IN(7,9) ORDER BY nombre_empleado ASC";
$codigo_empleado = mysql_query($query_codigo_empleado, $conexion1) or die(mysql_error());
$row_codigo_empleado = mysql_fetch_assoc($codigo_empleado);
$totalRows_codigo_empleado = mysql_num_rows($codigo_empleado);

mysql_select_db($database_conexion1, $conexion1);
$query_maquinas = "SELECT * FROM maquina WHERE proceso_maquina='3' ORDER BY maquina.nombre_maquina ASC";
$maquinas = mysql_query($query_maquinas, $conexion1) or die(mysql_error());
$row_maquinas = mysql_fetch_assoc($maquinas);
$totalRows_maquinas = mysql_num_rows($maquinas);

$colname_opref = "-1";
if (isset($_GET['id_op_r'])) {
  $colname_opref = (get_magic_quotes_gpc()) ? $_GET['id_op_r'] : addslashes($_GET['id_op_r']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_referencia = sprintf("SELECT SUM(bolsas_r) AS finalizar FROM TblSelladoRollo WHERE id_op_r='%s'",$colname_opref);
$referencia = mysql_query($query_referencia, $conexion1) or die(mysql_error());
$row_referencia = mysql_fetch_assoc($referencia);
$totalRows_referencia = mysql_num_rows($referencia);

//PARA IMPRIMIR MENU LISTADO Y FECHAS
$colname_rollo = "-1";
if (isset($_GET['id_op_r'])) {
  $colname_rollo = (get_magic_quotes_gpc()) ? $_GET['id_op_r'] : addslashes($_GET['id_op_r']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_rollo = sprintf("SELECT kilos_r,maquina_r,numIni_r,numFin_r,cod_empleado_r,cod_auxiliar_r,fechaI_r,fechaF_r,id_op_r,turno_r FROM TblSelladoRollo WHERE id_op_r='%s' ORDER BY fechaF_r DESC LIMIT 1",$colname_rollo);//order por fecha porq los pueden ingresar en orden aleatorio
$rollo = mysql_query($query_rollo, $conexion1) or die(mysql_error());
$row_rollo = mysql_fetch_assoc($rollo);
$totalRows_rollo = mysql_num_rows($rollo);

//INFORMACION OP, CANTIDAD BOLSAS, CLIENTE, REF
$colname_op_carga = "-1";
if (isset($_GET['id_op_r'])) {
  $colname_op_carga = (get_magic_quotes_gpc()) ? $_GET['id_op_r'] : addslashes($_GET['id_op_r']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_op_carga = sprintf("SELECT id_op, int_cod_ref_op, int_cliente_op, int_cantidad_op, int_calibre_op, numInicio_op, b_estado_op FROM Tbl_orden_produccion WHERE id_op='%s' AND b_borrado_op='0'",$colname_op_carga);
$op_carga = mysql_query($query_op_carga, $conexion1) or die(mysql_error());
$row_op_carga = mysql_fetch_assoc($op_carga);
$totalRows_op_carga = mysql_num_rows($op_carga);

$colname_rollo_edit = "-1";
if (isset($_GET['id_op_r'])) {
  $colname_rollo_edit = (get_magic_quotes_gpc()) ? $_GET['id_op_r'] : addslashes($_GET['id_op_r']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_rollo_edit = sprintf("SELECT * FROM TblSelladoRollo WHERE TblSelladoRollo.id_op_r='%s'",$colname_rollo_edit);
$rollo_edit = mysql_query($query_rollo_edit, $conexion1) or die(mysql_error());
$row_rollo_edit = mysql_fetch_assoc($rollo_edit);
$totalRows_rollo_edit = mysql_num_rows($rollo_edit);

//O ESTA CONSULTA TRAYENDO DESDE TIQUETES LA NUMERACION
$colname_faltantes = "-1";
if (isset($_GET['id_op_r'])) {
  $colname_faltantes  = (get_magic_quotes_gpc()) ? $_GET['id_op_r'] : addslashes($_GET['id_op_r']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_faltantes = sprintf("SELECT SUM(`int_total_f`) as Falt FROM `Tbl_faltantes` WHERE `id_op_f`='%s'",$colname_faltantes);
$faltantes = mysql_query($query_faltantes, $conexion1) or die(mysql_error());
$row_faltantes = mysql_fetch_assoc($faltantes);
$totalRows_faltantes = mysql_num_rows($faltantes);

$colname_numeracion = "-1";
if (isset($_GET['id_op_r'])) {
  $colname_numeracion  = (get_magic_quotes_gpc()) ? $_GET['id_op_r'] : addslashes($_GET['id_op_r']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_numeracion = sprintf("SELECT MAX(Tbl_tiquete_numeracion.int_hasta_tn) as Inicio FROM Tbl_tiquete_numeracion WHERE int_op_tn = '%s'",$colname_numeracion);
$numeracion = mysql_query($query_numeracion, $conexion1) or die(mysql_error());
$row_numeracion = mysql_fetch_assoc($numeracion);
$totalRows_numeracion = mysql_num_rows($numeracion);

$colname_ref = "-1";
if (isset($_GET['id_op_r'])) {
  $colname_ref = (get_magic_quotes_gpc()) ? $_GET['id_op_r'] : addslashes($_GET['id_op_r']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ref = sprintf("SELECT ancho_ref,int_calibre_op,int_pesom_op FROM Tbl_orden_produccion,Tbl_referencia WHERE Tbl_orden_produccion.id_op='%s' AND Tbl_orden_produccion.id_ref_op=Tbl_referencia.id_ref",$colname_ref);
$ref = mysql_query($query_ref, $conexion1) or die(mysql_error());
$row_ref = mysql_fetch_assoc($ref);
$totalRows_ref = mysql_num_rows($ref);
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
<script type="text/javascript" src="js/listado.js"></script>
<script language="JavaScript" type="text/javascript" src="produccion_sellado_ajax.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
<script type="text/javascript">
function alerta1(){
	msn=confirm("Desea dejar la O.P en estado de continuidad?")
		 if(msn==true){
			 if(document.getElementById('continua')!=''){
		   DatosGestiones('16','id_op_continua',document.form1.id_op_r.value);
		 }else 
		 if (msn == false){window.history.go(); } 
	    }
}
function alerta2(){
	msn=confirm("Desea Finalizar al O.P?")
		 if(msn==true){
			 if(document.getElementById('cierre')!=''){
		   DatosGestiones('16','id_op_cierre',document.form1.id_op_r.value);
		 }else 
		 if (msn == false){window.history.go(); } 
	    }
}
</script>
</head>
<body  onLoad="sumaNumeracionTurnos();">
<div align="center">
<table align="center" id="tabla">
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
<tr><td id="nombreusuario"><?php echo $row_usuario['nombre_usuario'];?></td>
  <td id="cabezamenu"><ul id="menuhorizontal">
  <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
  </ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1">
       <form action="<?php echo $editFormAction; ?>" method="POST" name="form1" onSubmit="return (validacion_unodelosdos_imp()&& validacion_select_fecha())"><!--onsubmit="return validarForm(this);"onSubmit="return (validacion_select()&& validacion_select_fecha())"-->
        <table align="center" id="tabla2">
          <tr>
            <td colspan="9" id="titulo2">REPORTE DIARIO DE PRODUCCION EN SELLADO</td>
          </tr>
          <tr>
            <td colspan="9" id="fuente3"><?php if ($row_rollo['turno_r']!=''){?><a href="produccion_sellado_listado_rollos.php?id_op_r=<?php echo $row_rollo['id_op_r']; ?>"><img src="images/opciones.gif" alt="LISTADO TURNOS"title="LISTADO TURNOS" border="0" style="cursor:hand;"/></a><?php }?><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
          </tr>
          <tr>
            <td colspan="4" id="titulo1">INFORMACION GENERAL DE LA O.P.</td>
            <td colspan="5" id="titulo1"><?php if ($row_op_carga['int_cod_ref_op']==''){?>
              <strong class="rojo_inteso">la o.p esta en estado inactiva</strong>
              <?php }?>
              <?php 
			  $id_op_cierre=$_GET['id_op_r']; 
			  $mitadOp = porcentaje($row_op_carga['int_cantidad_op'],50);
			  if ($row_referencia['finalizar'] >= $mitadOp){
				  mysql_select_db($database_conexion1, $conexion1);
				  $resultado = mysql_query("UPDATE Tbl_orden_produccion SET b_estado_op='5' WHERE id_op='$id_op_cierre'");
			  }else{echo "EN PROCESO";}?></td> 
            </tr>
          <tr>
            <td colspan="4" id="fuente1">Estado de la O.P: <strong>
              <?php if($row_op_carga['b_estado_op']=='4'){echo "continua en sellado";}else if($row_op_carga['b_estado_op']=='5'){echo "O.P cerrada";} ?>
            </strong></td>
            <td colspan="2" id="fuente1"><input type="button" name="continua" id="continua" onClick="alerta1(this.id)" value="O.P EN FIN SEMANA"></td>
            <td colspan="2" id="fuente1"><input type="button" name="cierre" id="fin" onClick="alerta2(this.id)" value="FINALIZAR O.P"></td>
            <td id="fuente1"><div id="resultado"></div></td> 
            </tr>
          <tr>
            <td id="fuente1"><input type="number" name="id_op_r" id="id_op_r" min="1" style="width:80px" value="<?php echo $_GET['id_op_r']; ?>" required readonly></td>
            <td nowrap id="fuente1">REF.
              <input type="number" name="ref_r" id="ref_r" min="0" max="20" required style=" width:80px" value="<?php echo $row_op_carga['int_cod_ref_op']; ?>" readonly></td> 
            <td colspan="2" id="fuente1">CLIENTE:</td>
            <td colspan="3" id="fuente1"><?php $id_c=$row_op_carga['int_cliente_op'];
            $sqln="SELECT id_c,nombre_c FROM cliente WHERE id_c='$id_c'"; 
            $resultn=mysql_query($sqln); 
            $numn=mysql_num_rows($resultn); 
            if($numn >= '1') 
            {$id_co=mysql_result($resultn,0,'id_c');  
			$nombre_c=mysql_result($resultn,0,'nombre_c'); 
			$cadenaN = htmlentities($nombre_c); echo $cadenaN; 
			} ?></td>
            <td id="fuente1"><a href="javascript:popUp('produccion_registro_sellado_total_vista.php?id_op=<?php echo $_GET['id_op_r']; ?>','800','600')"><i>ver consumo de la o.p</i></a></td>
            <td id="fuente1"><?php
			$id_op=$_GET['id_op_r'];  
            $sqlrS="SELECT COUNT(DISTINCT rollo_r) AS rollosS FROM TblSelladoRollo WHERE id_op_r='$id_op'"; 
            $resultrS=mysql_query($sqlrS); 
            $numrS=mysql_num_rows($resultrS); 
            if($numrS >= '1') 
            {  
			 $rollos_sell = mysql_result($resultrS,0,'rollosS');
			}			
            $sqlrI="SELECT COUNT(DISTINCT rollo_r) AS max_rolloI FROM TblImpresionRollo WHERE id_op_r='$id_op'"; 
            $resultrI=mysql_query($sqlrI); 
            $numrI=mysql_num_rows($resultrI); 
            if($numrI >= '1')  
            { 
			  $max_rolloI =mysql_result($resultrI,0,'max_rolloI');  
			} 			
			//if($rollos_sell >= $max_rolloI ){?><a href="javascript:popUp('produccion_registro_sellado_add.php?id_op=<?php echo $_GET['id_op_r']; ?>','800','600')" target="_self"><i>liquidar rollo aquí</i></a><?php //}?></td>
             </tr>
 
            <tr>
              <td colspan="9" id="titulo1">INFORMACION DEL TURNO</td>
            </tr>
           <tr>
             <td nowrap id="numero2">TURNO N&deg;</td>
             <td id="fuente1">Operario</td>
             <td colspan="2" id="fuente1">Auxiliar</td>
             <td colspan="2" id="fuente1">Hora Inicio(hora militar)</td>
             <td colspan="3" id="fuente1">Hora Final(hora militar)</td>
             
           </tr>
           <tr>
             <td id="fuente1"><input type="number" name="turno_r" id="turno_r" min="1" max="6" style="width:40px" value="<?php $turn=$row_rollo['turno_r']+1;echo $turn; ?>" required></td>
             <td id="fuente1"><select name="cod_empleado_r" id="operario" style="width:100px">
                 <option value=""<?php if (!(strcmp("", $row_rollo['cod_empleado_r']))) {echo "selected=\"selected\"";} ?>>Operario</option>
                 <?php
			do {  
			?>
                 <option value="<?php echo $row_codigo_empleado['codigo_empleado']?>"<?php if (!(strcmp($row_codigo_empleado['codigo_empleado'], $row_rollo['cod_empleado_r']))) {echo "selected=\"selected\"";} ?>><?php echo $row_codigo_empleado['codigo_empleado']." - ".$row_codigo_empleado['nombre_empleado']." ".$row_codigo_empleado['apellido_empleado']?></option>
                 <?php
			} while ($row_codigo_empleado = mysql_fetch_assoc($codigo_empleado));
			  $rows = mysql_num_rows($codigo_empleado);
			  if($rows > 0) {
				  mysql_data_seek($codigo_empleado, 0);
				  $row_codigo_empleado = mysql_fetch_assoc($codigo_empleado);
			  }
			?>
               </select></td>
             <td colspan="2" id="fuente1"><select name="cod_auxiliar_r" style="width:100px" id="auxiliar">
                 <option value=""<?php if (!(strcmp("", $row_rollo['cod_auxiliar_r']))) {echo "selected=\"selected\"";} ?>>Auxiliar</option>
                 <?php
do {  
?>
                 <option value="<?php echo $row_codigo_empleado['codigo_empleado']?>"<?php if (!(strcmp($row_codigo_empleado['codigo_empleado'], $row_rollo['cod_auxiliar_r']))) {echo "selected=\"selected\"";} ?>><?php echo $row_codigo_empleado['codigo_empleado']." - ".$row_codigo_empleado['nombre_empleado']." ".$row_codigo_empleado['apellido_empleado']?></option>
                 <?php
} while ($row_codigo_empleado = mysql_fetch_assoc($codigo_empleado));
  $rows = mysql_num_rows($codigo_empleado);
  if($rows > 0) {
      mysql_data_seek($codigo_empleado, 0);
	  $row_codigo_empleado = mysql_fetch_assoc($codigo_empleado);
  }
?>
               </select></td>
             <td colspan="2" id="fuente1"><input name="fechaI_r" id="fecha_ini_rp" value="<?php if($row_rollo['fechaF_r']==''){echo restaHoras();}else{echo muestradatelocal($row_rollo['fechaF_r']);}?>" type="datetime-local" required/></td>
             <td colspan="3" id="fuente1"><input name="fechaF_r" id="fecha_fin_rp" value="<?php  echo fechaHoraDatelocal();?>" type="datetime-local" required onBlur="validacion_select_fecha();" /></td> 
             
            </tr>
           <tr>
             <td id="fuente1"># Rollo</td>
             <td id="fuente1">Nro. Inicio</td>
             <td id="fuente1">Nro. Final</td>
             <td id="fuente1">Cant. Bolsas</td>
             <td id="fuente1"><strong>KILOS
                 <!--<input type="hidden" size="2" name="ancho_ref" id="ancho_ref" value="<?php echo $row_ref['ancho_ref']?>">
               <input type="hidden" size="2" name="calibre_ref" id="calibre_ref" value="<?php echo $row_ref['int_calibre_op']?>">-->
                 <input type="hidden" size="2" name="peso_millar_op" id="peso_millar_op" value="<?php echo $row_ref['int_pesom_op']?>">
             </strong></td>
             <td id="fuente1"><strong>Reproceso kg</strong></td>
             <td id="fuente1"><strong>Desperdicios</strong></td>
             <td id="fuente1">Maquina</td>
             <td id="fuente1">&nbsp;</td>
             </tr>
           <tr>
             <td id="fuente1"><input name="rollo_r" id="rollo_r" min="1" style="width:40px" type="number" value="<?php $roll=$row_rollo['rollo_r'];echo $roll; ?>" required></td>
             <td id="fuente1"><input name="numIni_r" id="numIni_r" size="10" type="text" required pattern="[0-9a-zA-Z]{0,20}" title="Este no parece un Dato válida verifique solo cadena entre letras y numeros sin espacios" onChange="conMayusculas(this);" onClick="sumaPaqSelladoAdd(this);"  value="<?php if($row_rollo['numFin_r']==''){echo $row_op_carga['numInicio_op'];}else{ echo $row_rollo['numFin_r'];}?>"/></td>
             <td id="fuente1"><input name="numFin_r" id="numFin_r" size="10" type="text" required pattern="[0-9a-zA-Z]{0,20}" title="Este no parece un Dato válida verifique solo cadena entre letras y numeros sin espacios" onBlur="conMayusculas(this);" value=""/></td>
             <td id="fuente1"><input name="bolsas_r" id="bolsas_r" type="number" style="width:60px" required value="" onChange="metrosakilos();"/></td>
             <td id="fuente1"><input name="kilos_r" id="kilos_r" type="number" style="width:60px" min="0" step="0.01" required value="" readonly/></td>
             <td id="fuente1"><input name="reproceso_r" id="reproceso_r" type="number" style="width:60px" min="0" step="1" required value="0" placeholder="kilos"/></td>
             <td id="fuente1"><input type="button" name="Tiempos Desperdicio" id="check_sh1" value="Tiem. y Desp."  onClick="if(form1.fechaI_r.value=='' || form1.rollo_r.value=='' || form1.bolsas_r.value=='') { alert('DEBE SELECCIONAR FECHA INICIO, ROLLO Y CANTIDAD DE BOLSAS'); }else{verFoto('produccion_registro_sellado_detalle_add.php?idop='+document.getElementById('id_op_r').value+'&fecha='+document.getElementById('fecha_ini_rp').value+'&rollo='+document.getElementById('rollo_r').value+'&bolsas='+document.getElementById('bolsas_r').value+'','850','300')}"/></td>
             <td id="fuente1"><select name="maquina_r" id="revisor" style="width:60px">
               <option value=""<?php if (!(strcmp("", $row_rollo['maquina_r']))) {echo "selected=\"selected\"";} ?>>Maquinas</option>
               <?php
do {  
?>
               <option value="<?php echo $row_maquinas['id_maquina']?>"<?php if (!(strcmp($row_maquinas['id_maquina'], $row_rollo['maquina_r']))) {echo "selected=\"selected\"";} ?>><?php echo $row_maquinas['nombre_maquina']?></option>
               <?php
} while ($row_maquinas = mysql_fetch_assoc($maquinas));
  $rows = mysql_num_rows($maquinas);
  if($rows > 0) {
      mysql_data_seek($maquinas, 0);
	  $row_maquinas = mysql_fetch_assoc($maquinas);
  }
?>
             </select></td>
             <td id="fuente1"><input type="submit" name="button" id="button" value="GUARDAR" onClick="envio_form(this);"></td>
             
            </tr>
           <tr>
              
    <td id="fuente1"><?php //echo $row_faltantes['Falt']." - ". $row_numeracion['Inicio']?><!--<i><div id="resultado_generador"></div></i>--></td>
    <td id="fuente1">&nbsp;</td>
    <td colspan="2" id="fuente1"><?php echo redondear_entero_puntos($row_referencia['finalizar']); ?> DE <?php echo redondear_entero_puntos($row_op_carga['int_cantidad_op']); ?></td>
    <td colspan="2" id="fuente1">&nbsp;</td>
    <td colspan="2" id="fuente1">&nbsp;</td>
    <td id="fuente1">&nbsp;</td>
     
              
             </tr>
   <!--<tr>
     <td colspan="4" id="titulo1">INFORMACION GENERAL DE LA 
       <?php if($row_referencia['tipo_bolsa_ref']=='LAMINA'){echo "LAMINAS";}else if($row_referencia['tipo_bolsa_ref']=='PACKING LIST'){echo "PACKING LIST";}else{echo "BOLSAS";}?></td>
   </tr>
  <tr>
    <td id="talla1"><strong>ANCHO</strong></td>
    <td id="talla1"><strong>LARGO</strong></td>
    <td  id="talla1"><strong>SOLAPA</strong></td>
    <td  id="talla1"><strong>BOLSILLO PORTAGUIA</strong></td>
  </tr>
  <tr>
    <td id="talla1"><?php echo $row_referencia['ancho_ref']; ?></td>
    <td id="talla1"><?php echo $row_referencia['largo_ref']; ?></td>
    <td id="talla1"><?php echo $row_referencia['solapa_ref']; ?></td>
    <td id="talla1"><?php if ($row_referencia['b_solapa_caract_ref']==2) {echo "Sencilla";}else if ($row_referencia['b_solapa_caract_ref']==1){echo "Doble";}else {echo "";} ?> : 
      <?php echo $row_referencia['bolsillo_guia_ref']; ?></td>
  </tr>
  <tr>
    <td id="talla1"><strong>CALIBRE</strong></td>
    <td id="talla1"><strong>PESO MILLAR</strong></td>
    <td id="talla1"><strong>TIPO DE BOLSA </strong></td>
    <td id="talla1">FUELLE</td>
  </tr>
  <tr>
    <td id="talla1"><?php echo $row_referencia['calibre_ref']; ?></td>
    <td id="talla1"><?php echo $row_referencia['peso_millar_ref']; ?></td>
    <td id="talla1"><?php echo $row_referencia['tipo_bolsa_ref']; ?></td>
    <td id="talla1"><?php echo $row_referencia['N_fuelle']; ?></td>
  </tr>
  <tr>
    <td id="talla1"><strong>ADHESIVO</strong></td>
    <td id="talla1"><strong>PRESENTACION</strong></td>
    <td id="talla1"><strong>TRATAMIENTO CORONA</strong></td>
    <td id="talla1">&nbsp;</td>
  </tr>
  <tr>
    <td id="talla1"><?php echo $row_referencia['adhesivo_ref']; ?></td>
    <td id="talla1"><?php echo $row_referencia['Str_presentacion']; ?></td>
    <td id="talla1"><?php echo $row_referencia['Str_tratamiento']; ?></td>
    <td id="talla1">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" id="talla2"><strong>BOLSILLO PORTAGUIA</strong></td>
    </tr>
  <tr>
    <td id="talla1"> <strong>(Ubicacion)</strong></td>
    <td id="talla1"><strong>(Forma)</strong></td>
    <td id="talla1"><strong>(Lamina 1)</strong></td>
    <td id="talla1"><strong>(Lamina 2)</strong></td>
  </tr>
  <tr>
    <td id="talla1"><?php echo $row_referencia['str_bols_ub_ref']; ?></td>
    <td id="talla1"><?php echo $row_referencia['str_bols_fo_ref']; ?></td>
    <td id="talla1"><?php echo $row_referencia['bol_lamina_1_ref']; ?></td>
    <td id="talla1"><?php echo $row_referencia['bol_lamina_2_ref']; ?></td>
  </tr>
  <tr>
    <td id="talla1"><strong>TIPO DE SELLO:</strong></td>
    <td id="talla1"><strong>UNIDADES X CAJA:</strong></td>
    <td id="talla1"><strong>UNIDADES X PAQUETE:</strong></td>
    <td id="talla1"><strong>PRECORTE (Bolsillo Portaguia):</strong></td>
    </tr>
  <tr>
    <td id="talla1"><?php echo $row_referencia['tipo_sello_egp']; ?></td>
    <td id="talla1"><?php echo $row_referencia['unids_caja_egp']; ?></td>
    <td id="talla1"><?php echo $row_referencia['unids_paq_egp']; ?></td>
    <td id="talla1"><?php if($row_referencia['B_troque']=='1') {echo "SI";}else{echo "NO";}; ?></td>
  </tr>
  <tr>
    <td  id="talla1"><strong>POSICION</strong></td>
    <td  id="talla1"><strong>TIPO DE NUMERACION </strong></td>
    <td  id="talla1"><strong>BARRAS &amp; FORMATO</strong></td>
  </tr>
  <tr>
    <td  id="talla1"><strong>Solapa Talonario Recibo</strong></td>
    <td  id="talla1"><?php echo $row_referencia['tipo_solapatr_egp']; ?></td>
    <td  id="talla1"><?php echo $row_referencia['cb_solapatr_egp']; ?></td>
  </tr>
  <tr>
    <td  id="talla1"><strong>Cinta</strong></td>
    <td  id="talla1"><?php echo $row_referencia['tipo_cinta_egp']; ?></td>
    <td  id="talla1"><?php echo $row_referencia['cb_cinta_egp']; ?></td>
  </tr>
  <tr>
    <td  id="talla1"><strong>Superior</strong></td>
    <td  id="talla1"><?php echo $row_referencia['tipo_superior_egp']; ?></td>
    <td  id="talla1"><?php echo $row_referencia['cb_superior_egp']; ?></td>
  </tr>
  <tr>
    <td  id="talla1"><strong>Principal</strong></td>
    <td  id="talla1"><?php echo $row_referencia['tipo_principal_egp']; ?></td>
    <td  id="talla1"><?php echo $row_referencia['cb_principal_egp']; ?></td>
  </tr>
  <tr>
    <td  id="talla1"><strong>Inferior</strong></td>
    <td  id="talla1"><?php echo $row_referencia['tipo_inferior_egp']; ?></td>
    <td  id="talla1"><?php echo $row_referencia['cb_inferior_egp']; ?></td>
  </tr>
  <tr>
    <td  id="talla1"><strong>Liner</strong></td>
    <td  id="talla1"><?php echo $row_referencia['tipo_liner_egp']; ?></td>
    <td  id="talla1"><?php echo $row_referencia['cb_liner_egp']; ?></td>
  </tr>
  <tr>
    <td  id="talla1"><strong>Bolsillo</strong></td>
    <td  id="talla1"><?php echo $row_referencia['tipo_bols_egp']; ?></td>
    <td  id="talla1"><?php echo $row_referencia['cb_bols_egp']; ?></td>
  </tr>
  <tr>
    <td  id="talla1"><?php echo $row_referencia['tipo_nom_egp']; ?></td>
    <td  id="talla1"><?php echo $row_referencia['tipo_otro_egp']; ?></td>
    <td  id="talla1"><?php echo $row_referencia['cb_otro_egp']; ?></td>
  </tr>-->
          <tr>
            <td colspan="18" id="dato2"></td>
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

<?php $id_r=$row_rollo_edit['id_r']; if($id_r!='') {?>
<div align="center">
<table align="center" id="tabla">
<tr align="center"><td>
<div> 
<b class="spiffy"> 
<b class="spiffy1"><b></b></b>
<b class="spiffy2"><b></b></b>
<b class="spiffy3"></b>
<b class="spiffy4"></b>
<b class="spiffy5"></b></b>
<div class="spiffy_content">	
	<table align="center" id="tabla2">
  <tr>
    <td colspan="13" id="titulo1">TURNOS REGISTRADOS</td>
  </tr>
<!--   <tr>
	 <td id="fuente4">
    <div id="resultado"><?php include('produccion_sellado_consulta_stiker.php?id_op_r='.$_GET['id_op_r']);?><?php //$PAG="produccion_sellado_consulta_stiker.php?id_op_r"; echo  include('$PAG'.$_GET['id_op_r']); ?></div></td>
  </tr>-->
  <div id="resultado">
  <tr>
        <td nowrap id="fuente1">TURNO N&deg;</td>
        <td nowrap id="fuente1">Operario</td>
        <td nowrap id="fuente1">Auxiliar</td>
        <td nowrap id="fuente1">Hora Inicio(hora militar)</td>
        <td nowrap id="fuente1">Hora Final(hora militar)</td>
        <td nowrap id="fuente1">Total/Tiempo</td>
        <td nowrap id="fuente1"># Rollo</td>
        <td nowrap id="fuente1">Nro. Inicio</td>
        <td nowrap id="fuente1">Nro. Final</td>
        <td nowrap id="fuente1">Cant. Bolsas</td>
        <td nowrap id="fuente1">Kilos</td>
        <td nowrap id="fuente1">Reproceso</td>
        <td nowrap id="fuente1">Maquina</td>
        <td nowrap id="fuente1">EDITAR</td>
      </tr>

<?php
//while ($row_rollo_sellado = mysql_fetch_assoc($rollo_sellado)); 
do{
	echo "	<tr>";
	//mediante el evento onclick llamaremos a la funcion PedirDatos(), la cual tiene como parametro
	//de entrada el ID del empleado
	echo " 		<td id='detalle2'>".$row_rollo_edit['turno_r']."</td>";
	echo " 		<td id='detalle1'>".$row_rollo_edit['cod_empleado_r']."</td>";
	echo " 		<td id='detalle1'>".$row_rollo_edit['cod_auxiliar_r']."</td>";
	echo " 		<td id='detalle1'>".$row_rollo_edit['fechaI_r']."</td>";
	echo " 		<td id='detalle1'>".$row_rollo_edit['fechaF_r']."</td>";
	echo " 		<td id='detalle2'>".RestarFechas($row_rollo_edit['fechaI_r'],$row_rollo_edit['fechaF_r'])."</td>";
	echo " 		<td id='detalle2'>".$row_rollo_edit['rollo_r']."</td>";
	echo " 		<td id='detalle1'>".$row_rollo_edit['numIni_r']."</td>";
	echo " 		<td id='detalle1'>".$row_rollo_edit['numFin_r']."</td>";
	echo " 		<td id='detalle1'>".$row_rollo_edit['bolsas_r']."</td>";
	echo " 		<td id='detalle1'>".$row_rollo_edit['kilos_r']."</td>";
	echo " 		<td id='detalle1'>".$row_rollo_edit['reproceso_r']."</td>";	
	echo " 		<td id='detalle1'>".
	  $id_maq=$row_rollo_edit['maquina_r'];
	  $sqlmaq='SELECT codigo_maquina FROM maquina WHERE id_maquina=$id_maq';
	  $resultmaq= mysql_query($sqlmaq);
	  $nummaq= mysql_num_rows($resultmaq);
	  if($nummaq >='1')
	  { 
	  $nombremaq = mysql_result($resultmaq, 0, 'codigo_maquina');echo $nombremaq; 
	  }"</td>";
	  	echo " 		<td id='detalle1'><a style=\"text-decoration:underline;cursor:pointer;\" onclick=\"pedirDatos('".$row_rollo_edit['id_r']."')\"><i>Editar</i></a></td>";
	echo "	</tr>";
} while ($row_rollo_edit = mysql_fetch_assoc($rollo_edit)); 
?></div>
  <tr>
    <td colspan="13" id="titulo1"> <div id="formulario" style="display:none;"></div></td>
   </tr>  
    </table>
</div>
<b class="spiffy"> 
<b class="spiffy5"></b>
<b class="spiffy4"></b>
<b class="spiffy3"></b>
<b class="spiffy2"><b></b></b>
<b class="spiffy1"><b></b></b></b></div>
</td></tr></table>
</div>
<?php } ?>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($codigo_empleado);

mysql_free_result($maquinas);

mysql_free_result($referencia);

mysql_free_result($lista_op);

mysql_free_result($op_carga);

?>