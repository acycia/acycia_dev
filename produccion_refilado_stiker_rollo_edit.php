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
//LLAMADO A FUNCIONES
include('funciones/funciones_php.php');//SISTEMA RUW PARA LA BASE DE DATOS 
//FIN

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE TblRefiladoRollo SET rollo_r=%s, id_op_r=%s, ref_r=%s, id_c_r=%s, cod_empleado_r=%s, cod_auxiliar_r=%s, turno_r=%s, fechaI_r=%s, fechaF_r=%s, metro_r=%s, kilos_r=%s, extru_r=%s, impre_r=%s,  refil_r=%s, observ_r=%s WHERE id_r=%s",
                       GetSQLValueString($_POST['rollo_r'], "int"),
                       GetSQLValueString($_POST['id_op_r'], "int"),
                       GetSQLValueString($_POST['ref_r'], "text"),
                       GetSQLValueString($_POST['id_c_r'], "int"),
                       GetSQLValueString($_POST['cod_empleado_r'], "int"),
					   GetSQLValueString($_POST['cod_auxiliar_r'], "int"),
                       GetSQLValueString($_POST['turno_r'], "int"),
					   GetSQLValueString($_POST['fechaI_r'], "date"),
					   GetSQLValueString($_POST['fechaF_r'], "date"),
                       GetSQLValueString($_POST['metro_r'], "int"),
                       GetSQLValueString($_POST['kilos_r'], "double"),
                       GetSQLValueString($_POST['extru_r'], "double"),
					   GetSQLValueString($_POST['impre_r'], "double"),
                       GetSQLValueString($_POST['refil_r'], "double"),
                       GetSQLValueString($_POST['observ_r'], "text"),
					   GetSQLValueString($_POST['id_r'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());
  $updateSQL2 = sprintf("UPDATE Tbl_reg_produccion SET id_op_rp=%s, int_cod_ref_rp=%s, rollo_rp=%s, int_kilos_prod_rp=%s, int_metro_lineal_rp=%s, str_maquina_rp=%s, fecha_ini_rp=%s, fecha_fin_rp=%s, int_cod_empleado_rp=%s, int_cod_liquida_rp=%s WHERE id_rp=%s",                     
                       GetSQLValueString($_POST['id_op_r'], "int"),
                       GetSQLValueString($_POST['ref_r'], "text"),
					   GetSQLValueString($_POST['rollo_r'], "int"),
                       GetSQLValueString($_POST['kilos_r'], "double"),
					   GetSQLValueString($_POST['metro_r'], "int"),
					   GetSQLValueString($_POST['maquina'], "text"),
					   GetSQLValueString($_POST['fechaI_r'], "date"),
                       GetSQLValueString($_POST['fechaF_r'], "date"),
					   GetSQLValueString($_POST['cod_empleado_r'], "int"),
					   GetSQLValueString($_POST['cod_auxiliar_r'], "int"),
					   GetSQLValueString($_POST['id_rp'], "int"));
  mysql_select_db($database_conexion1, $conexion1);
  $Result2 = mysql_query($updateSQL2, $conexion1) or die(mysql_error());
  $updateGoTo = "produccion_refilado_stiker_rollo_vista.php?id_r=" . $_POST['id_r'] . "";
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

//CODIGO EMPLEADO
mysql_select_db($database_conexion1, $conexion1);
$query_codigo_empleado = "SELECT codigo_empleado,nombre_empleado,tipo_empleado FROM empleado WHERE tipo_empleado='5' OR tipo_empleado='10' ORDER BY nombre_empleado ASC";
$codigo_empleado = mysql_query($query_codigo_empleado, $conexion1) or die(mysql_error());
$row_codigo_empleado = mysql_fetch_assoc($codigo_empleado);
$totalRows_codigo_empleado = mysql_num_rows($codigo_empleado);

$colname_rollo_refilado_edit = "-1";
if (isset($_GET['id_r'])) {
  $colname_rollo_refilado_edit = (get_magic_quotes_gpc()) ? $_GET['id_r'] : addslashes($_GET['id_r']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_rollo_refilado_edit = sprintf("SELECT * FROM TblRefiladoRollo WHERE TblRefiladoRollo.id_r=%s",$colname_rollo_refilado_edit);
$rollo_refilado_edit = mysql_query($query_rollo_refilado_edit, $conexion1) or die(mysql_error());
$row_rollo_refilado_edit = mysql_fetch_assoc($rollo_refilado_edit);
$totalRows_rollo_refilado_edit = mysql_num_rows($rollo_refilado_edit);
//EXISTE OP
$colname_liquidado = "-1";
if (isset($_GET['id_r'])) {
  $colname_liquidado = (get_magic_quotes_gpc()) ? $_GET['id_r'] : addslashes($_GET['id_r']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_liquidado = sprintf("SELECT Tbl_reg_produccion.rollo_rp, Tbl_reg_produccion.id_proceso_rp, Tbl_reg_produccion.id_rp FROM TblRefiladoRollo,Tbl_reg_produccion WHERE TblRefiladoRollo.id_r=%s AND TblRefiladoRollo.id_op_r= Tbl_reg_produccion.id_op_rp AND TblRefiladoRollo.rollo_r=Tbl_reg_produccion.rollo_rp AND Tbl_reg_produccion.id_proceso_rp='3'",$colname_liquidado);
$liquidado_edit= mysql_query($query_liquidado, $conexion1) or die(mysql_error());
$row_liquidado_edit = mysql_fetch_assoc($liquidado_edit);
$totalRows_liquidado_edit = mysql_num_rows($liquidado_edit);
//INFORMACION OP
$colname_op_carga = "-1";
if (isset($_GET['id_r'])) {
  $colname_op_carga = (get_magic_quotes_gpc()) ? $_GET['id_r'] : addslashes($_GET['id_r']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_op_carga = sprintf("SELECT * FROM TblRefiladoRollo, Tbl_orden_produccion WHERE TblRefiladoRollo.id_r='%s' AND TblRefiladoRollo.id_op_r = Tbl_orden_produccion.id_op AND Tbl_orden_produccion.b_borrado_op='0'",$colname_op_carga);
$op_carga = mysql_query($query_op_carga, $conexion1) or die(mysql_error());
$row_op_carga = mysql_fetch_assoc($op_carga);
$totalRows_op_carga = mysql_num_rows($op_carga);
//MAQUINAS
mysql_select_db($database_conexion1, $conexion1);
$query_maquinas = "SELECT * FROM maquina WHERE proceso_maquina='4' ORDER BY id_maquina DESC";
$maquinas= mysql_query($query_maquinas, $conexion1) or die(mysql_error());
$row_maquinas = mysql_fetch_assoc($maquinas);
$totalRows_maquinas = mysql_num_rows($maquinas);
?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
<script type="text/javascript">
function alerta(){
	  DatosGestiones3('10','id_rp',document.form1.id_rp.value,'&fechaI',document.form1.fechaI_r.value);

//alert('si cambia esta fecha, elimine primero los registros de tiempos, preparacion y desperdicios')
}
</script>
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
       <form action="<?php echo $editFormAction; ?>" method="POST" name="form1" onSubmit="return (validacion_unodelosdos_imp()&& validacion_select_fecha())">
         <table align="center" id="tabla2">
           <tr>
            <td rowspan="4" id="fondo"><img src="images/logoacyc.jpg" width="97" height="71"/></td>
            <td colspan="3" id="titulo2">IDENTIFICACION MATERIALES REFILADO
            <?php $id_op=$row_rollo_refilado_edit['id_op_r'];
            $sqlr="SELECT id_op_r, rollo_r AS maxrollo FROM TblExtruderRollo WHERE id_op_r='$id_op' ORDER BY rollo_r DESC LIMIT 1"; 
            $resultr=mysql_query($sqlr); 
            $numr=mysql_num_rows($resultr); 
            if($numr >= '1') 
            {$max_rollo=mysql_result($resultr,0,'maxrollo');
			}else{$max_rollo='0';} ?>
            </td>
          </tr>
          <tr>
            <td colspan="3" id="numero2">ROLLO N&deg; <?php echo $row_rollo_refilado_edit['rollo_r'];if ($max_rollo!='') {echo " de ".$max_rollo;} ?>
              <input type="hidden" name="rollo_r" id="rollo_r" style="width:40px" value="<?php echo $row_rollo_refilado_edit['rollo_r']; ?>">
              <input type="hidden" name="id_r" id="id_r" value="<?php echo $row_rollo_refilado_edit['id_r']; ?>">
              <input type="hidden" name="id_rp" id="id_rp" value="<?php echo $row_liquidado_edit['id_rp']; ?>"></td>
          </tr>
          <tr>
            <td id="talla3">&nbsp;Rollos impresos hasta el momento:</td>
            <td colspan="2" id="fuente1">
            <?php
			$id_op=$row_rollo_refilado_edit['id_op_r'];  
			$result = mysql_query("SELECT rollo_r FROM TblRefiladoRollo WHERE id_op_r='$id_op' ORDER BY rollo_r ASC"); 
			if ($row = mysql_fetch_array($result)){  
			   do { 
				  echo $row["rollo_r"].", "."\n"; 
			   } while ($row = mysql_fetch_array($result)); 
			} else { 
			echo "! Aun no hay Rollos!"; 
			} 
			?></td>
            </tr>
          <tr>
            <td colspan="4" id="fuente3"><a href="produccion_refilado_stiker_rollo_vista.php?id_r=<?php echo $row_rollo_refilado_edit['id_r']; ?>"><img src="images/hoja.gif" alt="VISTA" title="VISTA" border="0" /></a><a href="produccion_refilado_listado_rollos.php?id_op_r=<?php echo $row_rollo_refilado_edit['id_op_r']; ?>&rollo_r=<?php echo $row_rollo_refilado_edit['rollo_r']; ?>"><img src="images/opciones.gif" alt="LISTADO ROLLOS"title="LISTADO ROLLO" border="0" style="cursor:hand;"/></a>
              <?php if($row_usuario['tipo_usuario']==1){?><a href="produccion_refilado_stiker_rollo_colas_vista.php?id_op_r=<?php echo $row_rollo_refilado_edit['id_op_r']; ?>"><img src="images/t.gif" alt="REFILADO TODOS LOS ROLLOS" title="REFILADO TODOS LOS ROLLOS" border="0" /></a><a href="javascript:eliminar1('id_rr',<?php echo $row_rollo_refilado_edit['id_r']; ?>,'produccion_refilado_listado_rollos.php')"><img src="images/por.gif" alt="ELIMINAR" title="ELIMINAR" border="0" style="cursor:hand;"/></a><?php }?><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
             
            </tr>
          <tr>
            <td colspan="4" id="titulo1">INFORMACION GENERAL DE LA O.P.</td>
            </tr>
          <tr>
            <td id="fuente1">ORDEN P</td>
            <td id="fuente1"><input name="id_op_r" type="text" id="id_op_r" value="<?php echo $row_rollo_refilado_edit['id_op_r']; ?>" size="11" readonly/></td>
            <td id="fuente1">REF.</td>
            <td id="fuente1"><input type="number" name="ref_r" id="ref_r" min="0" max="20" style=" width:100px"value="<?php echo $row_rollo_refilado_edit['ref_r']; ?>" readonly>
             </td>
            </tr>
          <tr>
            <td id="fuente1">CLIENTE</td>
            <td colspan="3" id="fuente1"><?php $id_c=$row_rollo_refilado_edit['id_c_r'];
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
           <td nowrap id="fuente1">TRATADO INTERNO:</td>
           <td id="fuente1"><?php echo $row_op_carga['str_tratamiento_op'] ?></td>
           <td nowrap id="fuente1">TRATADO EXTERNO:</td>
           <td id="fuente1"><?php echo $row_op_carga['str_tratamiento_op'] ?></td>
         </tr>
         <tr>
           <td nowrap id="fuente1">PIGMENTO INTERIOR:</td>
           <td id="fuente1"><?php echo $row_op_carga['str_interno_op']; ?></td>
           <td nowrap id="fuente1">PIGMENTO EXTERIOR:</td>
           <td id="fuente1"><?php echo $row_op_carga['str_externo_op']; ?></td>
         </tr>
         <tr>
           <td nowrap id="fuente1">CALIBRE MILS:</td>
           <td id="fuente1"><?php echo $row_op_carga['int_calibre_op']; ?></td>
           <td nowrap id="fuente1">PRESENTACION:</td>
           <td id="fuente1"><?php echo $row_op_carga['str_presentacion_op']; ?></td>
         </tr>
         <tr>
           <td colspan="4"></td>
         </tr>
         <tr>
           <td colspan="4" id="titulo1">INFORMACION DEL ROLLO</td>
         </tr>
         <tr>
            <td id="fuente1">OPERARIO</td>
            <td id="fuente1"><select name="cod_empleado_r" id="operario" onBlur=" validacion_unodelosdos_imp()" style="width:120px" >
              <option value=""<?php if (!(strcmp("", $row_rollo_refilado_edit['int_cod_empleado_r']))) {echo "selected=\"selected\"";} ?>>Montaje</option>
              <option value="0">Seleccione</option>
              <?php
do {  
?>
              <option value="<?php echo $row_codigo_empleado['codigo_empleado']?>"<?php if (!(strcmp($row_codigo_empleado['codigo_empleado'], $row_rollo_refilado_edit['cod_empleado_r']))) {echo "selected=\"selected\"";} ?>><?php echo $row_codigo_empleado['codigo_empleado']." - ".$row_codigo_empleado['nombre_empleado']." ".$row_codigo_empleado['apellido_empleado']?></option>
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
            <td id="fuente1"><select name="cod_auxiliar_r" id="auxiliar" onBlur="validacion_unodelosdos_imp();"  style="width:120px">
              <option value=""<?php if (!(strcmp("", $row_rollo_refilado_edit['cod_auxiliar_r']))) {echo "selected=\"selected\"";} ?>>Auxiliar</option>
              <?php
do {  
?>
              <option value="<?php echo $row_codigo_empleado['codigo_empleado']?>"<?php if (!(strcmp($row_codigo_empleado['codigo_empleado'], $row_rollo_refilado_edit['cod_auxiliar_r']))) {echo "selected=\"selected\"";} ?>><?php echo $row_codigo_empleado['codigo_empleado']." - ".$row_codigo_empleado['nombre_empleado']." ".$row_codigo_empleado['apellido_empleado']?></option>
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
             <td id="fuente1"><input type="number" name="turno_r" id="turno_r" min="1" max="6" style="width:40px" value="<?php echo $row_rollo_refilado_edit['turno_r']; ?>" required></td>
             <td id="fuente1">MAQUINA:</td>
             <td id="fuente1"><select name="maquina" id="maquina" onBlur="if(form1.str_maquina_rp.value=='') { alert('Debe Seleccionar una maquina')}">
               <?php
do {  
?>
               <option value="<?php echo $row_maquinas['id_maquina']?>"><?php echo $row_maquinas['nombre_maquina']?></option>
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
            <td id="fuente1">METROS</td>
            <td id="fuente1"><input name="metro_r" type="number" id="metro_r" min="1" style="width:100px" value="<?php echo $row_rollo_refilado_edit['metro_r']; ?>" required /></td>
            <td id="fuente1">FECHA IMPRESION ROLLO</td>
            <td id="fuente1"><input name="fechaV_r" type="datetime-local" min="2000-01-02" style="width:130px" value="<?php echo muestradatelocal($row_rollo_refilado_edit['fechaV_r']); ?>" readonly required/></td>
            </tr> 
          <tr>
            <td id="fuente1">FECHA INICIO ROLLO</td>
            <td id="fuente1"><input name="fechaI_r" id="fecha_ini_rp"type="datetime-local" min="2000-01-02" size="15" value="<?php echo muestradatelocal($row_rollo_refilado_edit['fechaI_r']); ?>" onChange="alerta();"/></td>
            <td id="fuente1">FECHA FIN ROLLO</td>
            <td id="fuente1"><input name="fechaF_r" id="fecha_fin_rp" type="datetime-local" min="2000-01-02" style="width:130px" value="<?php echo muestradatelocal($row_rollo_refilado_edit['fechaF_r']); ?>" required onBlur="validacion_select_fecha();" /></td>
          </tr>
          <tr>
            <td colspan="2" id="fuente1"><div id="resultado_generador"></div></td>
            <td id="fuente1">PESO</td>
            <td id="fuente1"><input name="kilos_r" type="number" id="kilos_r" min="1.00" step="0.01" style="width:100px" value="<?php echo $row_rollo_impresion_edit['kilos_r']; ?>" required/></td>
            </tr>
         <tr>
           <td colspan="4" id="titulo1">DEFECTOS</td>
         </tr>
         <tr>
           <td id="fuente1">Extruder:</td>
           <td id="fuente1"><input name="extru_r" type="number" id="extru_r" style="width:80" min="0.00" step="0.01" value="<?php echo $row_rollo_refilado_edit['extru_r']; ?>"></td>
           <td id="fuente1">Impresion:</td>
           <td id="fuente1"><input name="impre_r" type="number" id="impre_r" style="width:80" min="0.00" step="0.01" value="<?php echo $row_rollo_refilado_edit['impre_r']; ?>"></td>
         </tr>
         <tr>
           <td id="fuente1">Refilado:</td>
           <td id="fuente1"><input name="refil_r" type="number" id="refil_r" style="width:80" min="0.00" step="0.01" value="<?php echo $row_rollo_refilado_edit['refil_r']; ?>"></td>
           <td id="fuente1">&nbsp;</td>
           <td id="fuente1">&nbsp;</td>
         </tr>
         <tr>
           <td colspan="4" id="titulo1">OBSERVACIONES</td>
         </tr>
         <tr>
           <td colspan="4" id="fuente2"><textarea name="observ_r" cols="75" rows="2" id="observ_r" onKeyUp="conMayusculas(this)"><?php echo $row_rollo_refilado_edit['observ_r']; ?></textarea></td>
         </tr>
            <tr>
              <td colspan="4" id="fuente5">&nbsp;</td>
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

mysql_free_result($lista_op);


?>