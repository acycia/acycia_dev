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
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
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

$colname_op_carga = "-1";
if (isset($_GET['id_op'])) {
  $colname_op_carga = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_op_carga = sprintf("SELECT * FROM Tbl_orden_produccion WHERE Tbl_orden_produccion.id_op=%s",$colname_op_carga);
$op_carga = mysql_query($query_op_carga, $conexion1) or die(mysql_error());
$row_op_carga = mysql_fetch_assoc($op_carga);
$totalRows_op_carga = mysql_num_rows($op_carga);

?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link rel="stylesheet" type="text/css" media="all" href="css/style_login.css" />
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
<!--VALIDAR EL ENVIO DE FORMULARIO-->
<script type="text/javascript">
function funcion(){
var paq=parseInt(document.form1.int_undxpaq_n.value);
var caj=parseInt(document.form1.int_undxcaja_n.value);
var bol=parseInt(document.form1.int_bolsas_n.value);
if(form1.int_op_n.value==''){ 
alert ('Debe seleccionar una O.P !')
return false;
}else	
if(form1.int_caja_n.value==''){ 
alert ('Debe seleccionar total cajas !')
return false;
}else if(form1.int_cod_empleado_n.value==''){
alert ('Debe seleccionar un Empleado !')	
return false;
}else if(form1.int_cod_rev_n.value==''){
alert ('Debe seleccionar un Revisor !')	
return false;
}else 
if(paq > caj){
alert ('Unidad x paquete no puede ser mayor a la unidad x caja !')	
return false;
}else if(caj > bol){
alert ('Unidad x caja no puede ser mayor a las Bolsas !')	
return false;
}else {return true;} 
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
<tr><td id="nombreusuario"><?php echo $row_usuario['nombre_usuario']; ?></td>
  <td id="cabezamenu"><ul id="menuhorizontal">
  <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
  </ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1">
      <form action="<?php echo $editFormAction; ?>" method="POST" name="form1" onSubmit="return funcion();">
        <table align="center" id="tabla35">
          <tr>
            <td colspan="2" id="titulo2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2" id="titulo2"><?php $num1=$row_ultimo['id_numeracion']; $num2=$num1+1; ?>
              <input name="id_numeracion" type="hidden" value="<?php echo $num2; ?>">
              <input type="hidden" name="b_borrado_n" id="b_borrado_n" value="0"></td>
            </tr>
          <tr>
            <td colspan="2"><!--<input type="text" maxsize="15" lenght="15" onBlur="vmatricula(this)" name="matricula">--> </td>
            </tr>
          <tr>
            <td id="fuente1">FECHA</td>
            <td id="fuente1"><input name="fecha_ingreso_n" type="date" min="2000-01-02" value="<?php echo date("Y-m-d"); ?>" size="10" required  autofocus/></td>
          </tr>
          <tr>
            <td id="fuente1">ORDEN P.</td>
            <td id="fuente1"><select name="int_op_n" id="int_op_n" onChange="if(form1.int_op_n.value) { consulta_m_op(); }else { alert('Debe Seleccionar una O.P')}">
              <option value=""<?php if (!(strcmp("", $_GET['id_op']))) {echo "selected=\"selected\"";} ?>>Seleccione</option>
                <?php
do {  
?>
                <option value="<?php echo $row_op['id_op']?>"<?php if (!(strcmp($row_op['id_op'], $_GET['id_op']))) {echo "selected=\"selected\"";} ?>><?php echo $row_op['id_op']?></option>
                <?php
} while ($row_op = mysql_fetch_assoc($op));
  $rows = mysql_num_rows($op);
  if($rows > 0) {
      mysql_data_seek($op, 0);
	  $row_op = mysql_fetch_assoc($op);
  }
?>
              </select></td>
            </tr>
          <tr>
            <td id="fuente1">BOLSAS</td>
            <td ><input type="number" id="pswd" name="int_bolsas_n" min="0" value="<?php echo $row_op_carga['int_cantidad_op'];?>" readonly></td>
            </tr>          
          <tr>
            <td id="fuente1">UNIDADES X CAJA</td>
            <td ><input type="number" name="int_undxcaja_n" id="pswd" min="0" value="<?php echo $row_op_carga['int_undxcaja_op'];?>" onBlur="cajas();" readonly></td>
          </tr>
          <tr>
            <td id="fuente1">UNIDADES  X PAQ.</td>
            <td >
              <input type="number" name="int_undxpaq_n" id="pswd"  onBlur="paquetes();"value="<?php echo $row_op_carga['int_undxpaq_op'];?>" required/></td>
            </tr>
          <tr>
            <td id="fuente1"><strong>DESDE</strong></td>
            <td ><input type="text" name="int_desde_n" id="pswd" min="0" onBlur="sumaNumeracion();"onChange="conMayusculas(this)" required></td>
          </tr>
          <tr>
            <td id="fuente1"><strong>HASTA</strong></td>
            <td ><input type="text" name="int_hasta_n" id="pswd" required  min="0"onBlur="cajas();"></td>
          </tr>
          <tr>
            <td id="fuente1">CODIGO DE EMPLEADO</td>
            <td id="fuente1"><select name="int_cod_empleado_n" onBlur="if(form1.int_cod_empleado_n.value=='') { alert('Debe Seleccionar un empleado')}">
            <option value="">Seleccione</option>
              <?php
do {  
?>
              <option value="<?php echo $row_codigo_empleado['codigo_empleado']?>"><?php echo $row_codigo_empleado['nombre_empleado']?></option>
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
            <td id="fuente1">CODIGO DE REVISOR</td>
            <td id="fuente1"><select name="int_cod_rev_n"onBlur="if(form1.int_cod_rev_n.value=='') { alert('Debe Seleccionar un revisor')}">
              <option value="">Seleccione</option>
              <?php
do {  
?>
              <option value="<?php echo $row_codigo_empleado['codigo_empleado']?>"><?php echo $row_codigo_empleado['nombre_empleado']?></option>
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
            <td colspan="2">            
             </td>
            </tr>
            <tr>
            <td id="fuente1">PAQUETES X CAJA</td>
            <td ><input type="number" name="int_paquete_n" id="pswd" min="0" onBlur="paquetes();" required >
              <input type="hidden" name="int_caja_n" id="int_caja_n" onBlur="cajas();"></td>
          </tr>
            <tr>
            <td colspan="2">&nbsp;</td>
            </tr>
          <tr>
            <td colspan="2" id="dato2">
            <button type="submit"onClick="cajas();">SIGUIENTE</button></td>
          </tr>
            <tr>
            <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
            <td colspan="2" id="dato2">&nbsp;</td>
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

mysql_free_result($op);

mysql_free_result($op_carga);

mysql_free_result($ultimo);
?>