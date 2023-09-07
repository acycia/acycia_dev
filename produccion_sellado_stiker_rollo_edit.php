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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE TblSelladoRollo SET id_op_r=%s, ref_r=%s, bolsas_r=%s, maquina_r=%s, numIni_r=%s, numFin_r=%s, cod_empleado_r=%s, cod_auxiliar_r=%s, turno_r=%s, fechaI_r=%s, fechaF_r=%s, observ_r=%s WHERE id_r=%s",
                       GetSQLValueString($_POST['id_op_r'], "int"),
                       GetSQLValueString($_POST['ref_r'], "text"),
                       GetSQLValueString($_POST['bolsas_r'], "int"),
                       GetSQLValueString($_POST['maquina_r'], "int"),
                       GetSQLValueString($_POST['numIni_r'], "text"),
                       GetSQLValueString($_POST['numFin_r'], "text"),
                       GetSQLValueString($_POST['cod_empleado_r'], "int"),
                       GetSQLValueString($_POST['cod_auxiliar_r'], "int"),
                       GetSQLValueString($_POST['turno_r'], "int"),
                       GetSQLValueString($_POST['fechaI_r'], "date"),
                       GetSQLValueString($_POST['fechaF_r'], "date"),
                       GetSQLValueString($_POST['observ_r'], "text"),
                       GetSQLValueString($_POST['id_r'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "produccion_sellado_listado_rollos.php?id_op_r=" .$_POST['id_op_r']."";
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

//ORDENES DE PRODUCCION
mysql_select_db($database_conexion1, $conexion1);
$query_lista_op = "SELECT id_op FROM Tbl_orden_produccion WHERE b_estado_op!='0' ORDER BY Tbl_orden_produccion.id_op DESC";
$lista_op = mysql_query($query_lista_op, $conexion1) or die(mysql_error());
$row_lista_op = mysql_fetch_assoc($lista_op);
$totalRows_lista_op = mysql_num_rows($lista_op);
//CODIGO EMPLEADO
mysql_select_db($database_conexion1, $conexion1);
$query_codigo_empleado = "SELECT codigo_empleado,nombre_empleado,tipo_empleado FROM empleado WHERE tipo_empleado='7' OR tipo_empleado='9' ORDER BY nombre_empleado ASC";
$codigo_empleado = mysql_query($query_codigo_empleado, $conexion1) or die(mysql_error());
$row_codigo_empleado = mysql_fetch_assoc($codigo_empleado);
$totalRows_codigo_empleado = mysql_num_rows($codigo_empleado);

mysql_select_db($database_conexion1, $conexion1);
$query_maquinas = "SELECT * FROM maquina WHERE proceso_maquina='3' ORDER BY maquina.nombre_maquina ASC";
$maquinas = mysql_query($query_maquinas, $conexion1) or die(mysql_error());
$row_maquinas = mysql_fetch_assoc($maquinas);
$totalRows_maquinas = mysql_num_rows($maquinas);

$colname_rollo_edit = "-1";
if (isset($_GET['id_r'])) {
  $colname_rollo_edit = (get_magic_quotes_gpc()) ? $_GET['id_r'] : addslashes($_GET['id_r']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_rollo_edit = sprintf("SELECT * FROM TblSelladoRollo WHERE TblSelladoRollo.id_r='%s'",$colname_rollo_edit);
$rollo_edit = mysql_query($query_rollo_edit, $conexion1) or die(mysql_error());
$row_rollo_edit = mysql_fetch_assoc($rollo_edit);
$totalRows_rollo_edit = mysql_num_rows($rollo_edit);

$colname_opref = "-1";
if (isset($_GET['id_r'])) {
  $colname_opref = (get_magic_quotes_gpc()) ? $_GET['id_r'] : addslashes($_GET['id_r']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_referencia = sprintf("SELECT * FROM TblSelladoRollo,Tbl_referencia WHERE TblSelladoRollo.id_r='%s' AND TblSelladoRollo.ref_r=Tbl_referencia.cod_ref",$colname_opref);
$referencia = mysql_query($query_referencia, $conexion1) or die(mysql_error());
$row_referencia = mysql_fetch_assoc($referencia);
$totalRows_referencia = mysql_num_rows($referencia);

//INFORMACION OP
$colname_op_carga = "-1";
if (isset($_GET['id_r'])) {
  $colname_op_carga = (get_magic_quotes_gpc()) ? $_GET['id__r'] : addslashes($_GET['id_r']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_op_carga = sprintf("SELECT Tbl_orden_produccion.id_op, Tbl_orden_produccion.int_cod_ref_op, Tbl_orden_produccion.int_cliente_op, Tbl_orden_produccion.int_cantidad_op FROM TblSelladoRollo,Tbl_orden_produccion WHERE TblSelladoRollo.id_r='%s' AND TblSelladoRollo.id_op_r=Tbl_orden_produccion.id_op",$colname_op_carga);
$op_carga = mysql_query($query_op_carga, $conexion1) or die(mysql_error());
$row_op_carga = mysql_fetch_assoc($op_carga);
$totalRows_op_carga = mysql_num_rows($op_carga);
?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
</head>
<body>
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
       <form action="<?php echo $editFormAction; ?>" method="POST" name="form1" onSubmit="return validacion_select_fecha()">
        <table align="center" id="tabla2">
          <tr>
            <td rowspan="4" id="fondo"><img src="images/logoacyc.jpg" width="97" height="71"/></td>
            <td colspan="3" id="titulo2">TURNOS EN SELLADO</td>
          </tr>
          <tr>
            <td colspan="3" id="numero2">TURNO N&deg;
              <input type="number" name="turno_r" id="turno_r" min="1" max="6" style="width:40px" value="<?php echo $row_rollo_edit['turno_r']?>" required>
              <input type="hidden" name="id_r" id="id_r" value="<?php echo $row_rollo_edit['id_r']; ?>"></td>
          </tr>
          <tr>
            <td id="talla3">&nbsp;</td>
            <td colspan="2" id="fuente1">&nbsp;</td>
            </tr>
          <tr>
            <td colspan="3" id="fuente3"><?php if ($row_rollo_edit['turno_r']!=''){?><a href="produccion_sellado_listado_rollos.php?id_op_r=<?php echo $row_rollo_edit['id_op_r']; ?>"><img src="images/opciones.gif" alt="LISTADO TURNOS"title="LISTADO TURNOS" border="0" style="cursor:hand;"/></a><?php }?><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
            </tr>
          <tr>
            <td colspan="4" id="titulo1">INFORMACION GENERAL DE LA O.P.</td>
            </tr>
          <tr>
            <td id="fuente1">ORDEN P</td>
            <td id="fuente1"><select name="id_op_r" id="id_op_r" style="width:170px" onChange="if(form1.id_op_r.value) { consulta_rollo_SEdit(); }else { alert('Debe Seleccionar una O.P')}" autofocus>
              <option value=""<?php if (!(strcmp("", $row_rollo_edit['id_op_r']))) {echo "selected=\"selected\"";} ?>>Seleccione</option>
              <?php
			do {  
			?>
			 <option value="<?php echo $row_lista_op['id_op']?>"<?php if (!(strcmp($row_lista_op['id_op'], $row_rollo_edit['id_op_r']))) {echo "selected=\"selected\"";} ?>><?php echo $row_lista_op['id_op']?></option>
						  <?php
			} while ($row_lista_op = mysql_fetch_assoc($lista_op));
			  $rows = mysql_num_rows($lista_op);
			  if($rows > 0) {
				  mysql_data_seek($lista_op, 0);
				  $row_lista_op = mysql_fetch_assoc($lista_op);
			  }
			?>
            </select></td>
            <td id="fuente1">REF.</td>
            <td id="fuente1"><input type="number" name="ref_r" id="ref_r" min="0" max="20" style=" width:100px" value="<?php echo $row_rollo_edit['ref_r']; ?>" readonly></td>
            </tr>
          <tr>
            <td id="fuente1">CLIENTE</td>
            <td colspan="3" id="fuente1"><?php $id_c=$row_op_carga['int_cliente_op'];
            $sqln="SELECT id_c,nombre_c FROM cliente WHERE id_c='$id_c'"; 
            $resultn=mysql_query($sqln); 
            $numn=mysql_num_rows($resultn); 
            if($numn >= '1') 
            {$id_co=mysql_result($resultn,0,'id_c');  
			$nombre_c=mysql_result($resultn,0,'nombre_c'); 
			$cadenaN = htmlentities($nombre_c); echo $cadenaN; 
			} ?></td>
          </tr>
          <tr>
            <td id="fuente1">&nbsp;</td>
            <td id="fuente1">&nbsp;</td>
            <td id="fuente1">&nbsp;</td>
            <td id="fuente1">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4">            
              </td>
          </tr>
            <tr>
              <td colspan="4" id="titulo1">INFORMACION DEL TURNO</td>
            </tr>
           <tr>
            <td id="fuente1">&nbsp;</td>
            <td id="fuente1">&nbsp;</td>
            <td id="fuente1">Bolsas x Turno</td>
            <td id="fuente1"><input type="number" name="bolsas_r" id="bolsas_r" style="width:100px" value="<?php echo $row_rollo_edit['bolsas_r'] ?>" />
              DE <?php echo $row_op_carga['int_cantidad_op']; ?></td>
            </tr>
           <tr>
             <td id="fuente1">Maquina</td>
             <td id="fuente1"><select name="maquina_r" id="maquina_r" style="width:170px">
             <option value=""<?php if (!(strcmp("", $row_rollo_edit['maquina_r']))) {echo "selected=\"selected\"";} ?>>Seleccione</option>
               <?php
do {  
?>
               <option value="<?php echo $row_maquinas['id_maquina']?>"<?php if (!(strcmp($row_maquinas['id_maquina'], $row_rollo_edit['maquina_r']))) {echo "selected=\"selected\"";} ?>><?php echo $row_maquinas['nombre_maquina']?></option>
               <?php
} while ($row_maquinas = mysql_fetch_assoc($maquinas));
  $rows = mysql_num_rows($maquinas);
  if($rows > 0) {
      mysql_data_seek($maquinas, 0);
	  $row_maquinas = mysql_fetch_assoc($maquinas);
  }
?>
             </select></td>
             <td id="fuente1">&nbsp;</td>
             <td id="fuente1">&nbsp;</td>
           </tr>
           <tr>
             <td id="fuente1">Numeracion Inicial</td>
             <td id="fuente1"><input type="text" name="numIni_r" id="numIni_r" style="width:170px" required onBlur="conMayusculas(this);" value="<?php echo $row_rollo_edit['numIni_r']?>"/></td>
             <td id="fuente1">Numeracion Final</td>
             <td id="fuente1"><input type="text" name="numFin_r" id="numFin_r" size="14" required onBlur="conMayusculas(this)" value="<?php echo $row_rollo_edit['numFin_r']?>"/></td>
           </tr>
           <tr>
             <td id="fuente1">OPERARIO</td>
             <td id="fuente1"><select name="cod_empleado_r" id="operario" style="width:170px" onBlur="validacion_select()">
               <option value=""<?php if (!(strcmp("", $row_rollo_edit['cod_empleado_r']))) {echo "selected=\"selected\"";} ?>>Seleccione</option>
               <?php
			do {  
			?>
               <option value="<?php echo $row_codigo_empleado['codigo_empleado']?>"<?php if (!(strcmp($row_codigo_empleado['codigo_empleado'], $row_rollo_edit['cod_empleado_r']))) {echo "selected=\"selected\"";} ?>><?php echo $row_codigo_empleado['codigo_empleado']." - ".$row_codigo_empleado['nombre_empleado']." ".$row_codigo_empleado['apellido_empleado']?></option>
               <?php
			} while ($row_codigo_empleado = mysql_fetch_assoc($codigo_empleado));
			  $rows = mysql_num_rows($codigo_empleado);
			  if($rows > 0) {
				  mysql_data_seek($codigo_empleado, 0);
				  $row_codigo_empleado = mysql_fetch_assoc($codigo_empleado);
			  }
			?>
             </select></td>
             <td id="fuente1">AUXILIAR</td>
             <td id="fuente1"><select name="cod_auxiliar_r" id="sin" style="width:100px" >
               <option value=""<?php if (!(strcmp("", $row_rollo_edit['cod_auxiliar_r']))) {echo "selected=\"selected\"";} ?>>Auxiliar</option>
               <?php
do {  
?>
               <option value="<?php echo $row_codigo_empleado['codigo_empleado']?>"<?php if (!(strcmp($row_codigo_empleado['codigo_empleado'], $row_rollo_edit['cod_auxiliar_r']))) {echo "selected=\"selected\"";} ?>><?php echo $row_codigo_empleado['codigo_empleado']." - ".$row_codigo_empleado['nombre_empleado']." ".$row_codigo_empleado['apellido_empleado']?></option>
               <?php
} while ($row_codigo_empleado = mysql_fetch_assoc($codigo_empleado));
  $rows = mysql_num_rows($codigo_empleado);
  if($rows > 0) {
      mysql_data_seek($codigo_empleado, 0);
	  $row_codigo_empleado = mysql_fetch_assoc($codigo_empleado);
  }
?>
             </select></td>
           </tr>
          <tr>
            <td id="fuente1">TURNO</td>
            <td id="fuente1">&nbsp;</td>
            <td id="fuente1">&nbsp;</td>
            <td id="fuente1">&nbsp;</td>
            </tr>
          <tr>
            <td id="fuente1">FECHA INICIO TURNO</td>
            <td id="fuente1"><input name="fechaI_r" id="fechaI_r" min="2000-01-02" size="20"  type='datetime' value="<?php echo $row_rollo_edit['fechaI_r']?>" required/></td>
            <td id="fuente1">FECHA FIN TURNO</td>
            <td id="fuente1"><input name="fechaF_r" id="fechaF_r" type="datetime" min="2000-01-02" size="20" required value="<?php echo $row_rollo_edit['fechaF_r']?>"  onBlur="validacion_select_fecha();"/>
            </td>
          </tr>
   <tr>
     <td id="fuente1">&nbsp;</td>
     <td id="fuente1">&nbsp;</td>
     <td id="fuente1">&nbsp;</td>
     <td id="fuente1">&nbsp;</td>
   </tr>
   <tr>
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
  </tr>      
            <tr>
              <td colspan="4" id="titulo1">OBSERVACIONES</td>
            </tr>
            <tr>
              <td colspan="4" id="fuente2"><textarea name="observ_r" cols="75" rows="2" id="observ_r" onKeyUp="conMayusculas(this)"><?php echo $row_rollo_edit['observ_r'] ?></textarea></td>
              </tr>
            <tr>
              <td colspan="4" id="fuente5">
             </td>
            </tr>
            <tr>
              <td colspan="4" id="fuente2"><input type="submit" name="button" id="button" value="EDITAR"></td>
            </tr>
          <tr>
            <td colspan="4" id="dato2"></td>
            </tr>
        </table>
        <input type="hidden" name="MM_update" value="form1">
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

mysql_free_result($codigo_empleado);

mysql_free_result($maquinas);

mysql_free_result($rollo_edit);

mysql_free_result($referencia);

mysql_free_result($lista_op);

mysql_free_result($op_carga);

?>