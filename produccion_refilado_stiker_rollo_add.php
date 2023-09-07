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
  $insertSQL = sprintf("INSERT INTO TblRefiladoRollo (rollo_r, id_op_r, ref_r, id_c_r, cod_empleado_r, cod_auxiliar_r, turno_r, fechaI_r, fechaF_r, metro_r, kilos_r, extru_r, impre_r,  refil_r, observ_r) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
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
                       GetSQLValueString($_POST['observ_r'], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());
  $proce="3";
  $kilodes=$_POST['extru_r']+$_POST['impre_r']+$_POST['refil_r'];
  $insertSQL2 = sprintf("INSERT INTO Tbl_reg_produccion ( id_proceso_rp, id_op_rp, id_ref_rp, int_cod_ref_rp, version_ref_rp, rollo_rp, int_kilos_prod_rp, int_kilos_desp_rp, porcentaje_op_rp, int_metro_lineal_rp, int_total_rollos_rp, str_maquina_rp, str_responsable_rp, fecha_ini_rp, fecha_fin_rp, int_cod_empleado_rp, int_cod_liquida_rp) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",                      
                       GetSQLValueString($proce, "int"),
                       GetSQLValueString($_POST['id_op_r'], "int"),
					   GetSQLValueString($_POST['id_ref_r'], "text"),
                       GetSQLValueString($_POST['ref_r'], "text"),
					   GetSQLValueString($_POST['version'], "text"),
					   GetSQLValueString($_POST['rollo_r'], "int"),
                       GetSQLValueString($_POST['kilos_r'], "double"),
                       GetSQLValueString($kilodes, "double"),
					   GetSQLValueString($_POST['porcentaje'], "text"),
					   GetSQLValueString($_POST['metro_r'], "int"),
					   GetSQLValueString($_POST['totalRollos'], "int"),
					   GetSQLValueString($_POST['maquina'], "text"),
				       GetSQLValueString($_POST['responsable'], "text"),			   
					   GetSQLValueString($_POST['fechaI_r'], "date"),
                       GetSQLValueString($_POST['fechaF_r'], "date"),
					   GetSQLValueString($_POST['cod_empleado_r'], "int"),
					   GetSQLValueString($_POST['cod_auxiliar_r'], "int"));
  mysql_select_db($database_conexion1, $conexion1);
  $Result2 = mysql_query($insertSQL2, $conexion1) or die(mysql_error());	
	
  $updateSQL3 = sprintf("UPDATE Tbl_orden_produccion SET b_estado_op='3' WHERE id_op='%s'",
					   GetSQLValueString($_POST['id_op_r'], "int"));
  mysql_select_db($database_conexion1, $conexion1);
  $Result3 = mysql_query($updateSQL3, $conexion1) or die(mysql_error());
  
    $insertGoTo = "produccion_refilado_listado_rollos.php?id_op_rp=" . $_POST['id_op_r'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }/**/
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

//CODIGO EMPLEADO
mysql_select_db($database_conexion1, $conexion1);
$query_codigo_empleado = "SELECT codigo_empleado,nombre_empleado,tipo_empleado FROM empleado WHERE tipo_empleado='5' OR tipo_empleado='10' ORDER BY nombre_empleado ASC";
$codigo_empleado = mysql_query($query_codigo_empleado, $conexion1) or die(mysql_error());
$row_codigo_empleado = mysql_fetch_assoc($codigo_empleado);
$totalRows_codigo_empleado = mysql_num_rows($codigo_empleado);

$colname_rollo = "-1";
if (isset($_GET['id_op_r'])) {
  $colname_rollo = (get_magic_quotes_gpc()) ? $_GET['id_op_r'] : addslashes($_GET['id_op_r']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_rollo = sprintf("SELECT cod_empleado_r,turno_r,fechaF_r,id_op_r,rollo_r FROM TblRefiladoRollo WHERE id_op_r='%s' ORDER BY fechaF_r DESC LIMIT 1",$colname_rollo);
$rollo = mysql_query($query_rollo, $conexion1) or die(mysql_error());
$row_rollo = mysql_fetch_assoc($rollo);
$totalRows_rollo = mysql_num_rows($rollo);
//INFORMACION OP
$colname_op_carga = "-1";
if (isset($_GET['id_op_r'])) {
  $colname_op_carga = (get_magic_quotes_gpc()) ? $_GET['id_op_r'] : addslashes($_GET['id_op_r']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_op_carga = sprintf("SELECT id_op, int_cod_ref_op, id_ref_op, version_ref_op, int_cliente_op,int_desperdicio_op,str_presentacion_op,int_calibre_op,str_interno_op,str_externo_op,str_tratamiento_op FROM Tbl_orden_produccion WHERE id_op='%s' AND b_borrado_op='0'",$colname_op_carga);
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
function validar2() {
    DatosGestiones3('9','op',form1.id_op_r.value,'&rollo',form1.rollo_r.value);
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
<td colspan="2" align="center"><img src="images/cabecera.jpg">
  <form name="form2" method="post" action="">
    <label for="gh"></label>
  </form></td></tr>
<tr><td id="nombreusuario"><?php echo $row_usuario['nombre_usuario'];?></td>
  <td id="cabezamenu"><ul id="menuhorizontal">
  <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
  </ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1">
       <form action="<?php echo $editFormAction; ?>" method="POST" name="form1"  onSubmit="return (validacion_unodelosdos_imp()&& validacion_select_fecha())">
         <table align="center" id="tabla2">
         <tr>
           <td rowspan="4" id="fondo"><img src="images/logoacyc.jpg" width="97" height="71"/></td>
           <td colspan="3" id="titulo2">IDENTIFICACION MATERIALES REFILADO
             <?php $id_r=$_GET['id_op_r'];
			$rollo_r=$_GET['rollo_r'];
            $sqlre="SELECT metro_r, kilos_r FROM TblExtruderRollo WHERE id_op_r='$id_r' AND rollo_r='$rollo_r'"; 
            $resultre=mysql_query($sqlre); 
            $numre=mysql_num_rows($resultre); 
            if($numre >= '1') 
            {  
			$kilosR=mysql_result($resultre,0,'kilos_r');  
			$metrosE=mysql_result($resultre,0,'metro_r');
			}
            $sqlrI="SELECT MAX(rollo_r) AS max_rolloE FROM TblExtruderRollo WHERE id_op_r='$id_r'"; 
            $resultrI=mysql_query($sqlrI); 
            $numrI=mysql_num_rows($resultrI); 
            if($numrI >= '1') 
            {  
			$max_rolloE =mysql_result($resultrI,0,'max_rolloE'); 
			}		
			?></td>
         </tr>
         <tr>
           <td colspan="3" id="numero2">ROLLO N&deg;
             <input type="number" name="rollo_r" id="rollo_r" min="1" style="width:50px" required value="" onChange="validar2();">
             <?php if ($_GET['rollo_r']!='') {echo " de ".$max_rolloE;} ?>
             <!--<input type="hidden" name="id_r" id="id_r" value="<?php echo $row_ultimo['id_r']+1; ?>">--></td>
         </tr>
         <tr>
           <td id="talla3">&nbsp;Rollos impresos hasta el momento:
             <div id="resultado_generador"></div></td>
           <td colspan="2" id="fuente1"><?php
			$id_op=$_GET['id_op_r'];  
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
           <td colspan="4" id="fuente3"><?php if ($row_rollo['rollo_r']!=''){?>
             <a href="produccion_refilado_listado_rollos.php?id_op_r=<?php echo $row_rollo['id_op_r']; ?>"><img src="images/opciones.gif" alt="LISTADO ROLLOS"title="LISTADO ROLLO" border="0" style="cursor:hand;"/></a>
             <?php }?>
             <a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
         </tr>
         <tr>
           <td colspan="4" id="titulo1">INFORMACION GENERAL DE LA O.P.</td>
         </tr>
         <tr>
           <td id="fuente1">ORDEN P</td>
           <td id="fuente1"><input name="id_op_r" readonly type="text" id="id_op_r" value="<?php echo $_GET['id_op_r'] ?>" size="11"/></td>
           <td id="fuente1">REF.</td>
           <td id="fuente1"><input type="number" name="ref_r" id="ref_r" min="0" max="20" style=" width:100px" value="<?php echo $row_op_carga['int_cod_ref_op']; ?>" readonly>
             <input type="hidden" name="id_ref_r" id="id_ref_r" value="<?php echo $row_op_carga['id_ref_op']; ?>" readonly>
             <input type="hidden" name="version" id="version" value="<?php echo $row_op_carga['version_ref_op']; ?>" readonly>
             <input type="hidden" name="totalRollos" id="totalRollos" value="<?php echo $max_rolloE; ?>" readonly>
             <input id="porcentaje" name="porcentaje" type="hidden" value="<?php echo $row_op_carga['int_desperdicio_op']; ?>" min="0" max="100" step="1" style="width:40px" required readonly/>
             <input id="responsable" name="responsable" type="hidden" value="<?php echo $row_usuario['nombre_usuario']; ?>" min="0" max="100" step="1" style="width:40px" required readonly/></td>
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
			} ?>
             <input type="hidden" name="id_c_r" id="id_c_r" value="<?php echo $id_co; ?>" size="11"></td>
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
           <td id="fuente1"><select name="cod_empleado_r" id="operario" onBlur=" validacion_unodelosdos_imp()">
             <option value=""<?php if (!(strcmp("", $row_rollo['cod_empleado_r']))) {echo "selected=\"selected\"";} ?>>Seleccione</option>
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
           <td id="fuente1">AUXILIAR</td>
           <td id="fuente1"><select name="cod_auxiliar_r" id="auxiliar" onBlur="validacion_unodelosdos_imp();" style="width:120px" >
             <option value="">Auxiliar</option>
             <?php
do {  
?>
             <option value="<?php echo $row_codigo_empleado['codigo_empleado']?>"><?php echo $row_codigo_empleado['codigo_empleado']." - ".$row_codigo_empleado['nombre_empleado']." ".$row_codigo_empleado['apellido_empleado']?></option>
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
           <td id="fuente1"><input type="number" name="turno_r" id="turno_r" min="1" max="6" style="width:40px" value="<?php echo $row_rollo['turno_r'];?>" required></td>
           <td id="fuente1">MAQUINA:</td>
           <td id="fuente1"><select name="maquina" id="maquina">
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
           <td id="fuente1">FECHA INICIO ROLLO</td>
           <td id="fuente1"><input name="fechaI_r" id="fecha_ini_rp" min="2000-01-02" size="15" value="<?php if($row_rollo['fechaF_r']==''){echo fechaHoraDatelocal();  }else{echo muestradatelocal($row_rollo['fechaF_r']);}?>" type="datetime-local" required/></td>
           <td colspan="2" id="fuente1"><p>FECHA FIN ROLLO
             </p>
             <p>
               <input name="fechaF_r" id="fecha_fin_rp" type="datetime-local" min="2000-01-02" size="15"  value="<?php  echo fechaHoraDatelocal();?>" onBlur="validacion_select_fecha();" required/>
             </p></td>
           </tr>
         <tr>
           <td id="fuente1">METROS</td>
           <td id="fuente1"><input name="metro_r" type="number" id="metro_r" min="1" style="width:100px" value="<?php echo redondear_decimal($metrosE); ?>" required/></td>
           <td id="fuente1">PESO</td>
           <td id="fuente1"><input name="kilos_r" type="number" id="kilos_r" min="1.00" step="0.01" style="width:100px" value="<?php echo redondear_decimal($kilosR); ?>" required/></td>
         </tr>
         <tr>
           <td colspan="4" id="titulo1">DEFECTOS</td>
         </tr>
         <tr>
           <td id="fuente1">Extruder:</td>
           <td id="fuente1"><input name="extru_r" type="number" id="extru_r" style="width:80" min="0.00" step="0.01" value=""></td>
           <td id="fuente1">Impresion:</td>
           <td id="fuente1"><input name="impre_r" type="number" id="impre_r" style="width:80" min="0.00" step="0.01" value=""></td>
         </tr>
         <tr>
           <td id="fuente1">Refilado:</td>
           <td id="fuente1"><input name="refil_r" type="number" id="refil_r" style="width:80" min="0.00" step="0.01" value=""></td>
           <td id="fuente1">&nbsp;</td>
           <td id="fuente1">&nbsp;</td>
         </tr>
         <tr>
           <td colspan="4" id="dato1">Nota: Al guardar el rollo me guarda los datos principales en la tabla maestra (<em>reg_produccion</em>)</td>
           </tr>
         <tr>
           <td colspan="4" id="titulo1">OBSERVACIONES</td>
         </tr>
         <tr>
           <td colspan="4" id="fuente2"><textarea name="observ_r" cols="75" rows="2" id="observ_r" onKeyUp="conMayusculas(this)"></textarea></td>
         </tr>
         <tr>
           <td colspan="4" id="fuente5"></td>
         </tr>
         <tr>
           <td colspan="4" id="fuente2"><input type="submit" name="button" id="button" value="GUARDAR"></td>
         </tr>
         <tr>
           <td colspan="4" id="dato2"></td>
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

mysql_free_result($codigo_empleado);

mysql_free_result($ultimo);

mysql_free_result($lista_op);

mysql_free_result($op_carga);

?>