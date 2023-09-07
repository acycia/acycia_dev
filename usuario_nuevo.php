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
session_start();
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
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
    $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
    break;    
    case "long":
    case "int":
    $theValue = ($theValue != "") ? intval($theValue) : "NULL";
    break;
    case "double":
    $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
//////////////////////////////////////////////////////////////////////////////
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO usuario (id_usuario, usuario, clave_usuario, nombre_usuario, tipo_usuario, codigo_usuario, responsable, fecha_registro, responsable_edit, fecha_edit, email_usuario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
   GetSQLValueString($_POST['id_usuario'], "int"),
   GetSQLValueString($_POST['usuario'], "text"),
   GetSQLValueString($_POST['clave_usuario'], "text"),
   GetSQLValueString($_POST['nombre_usuario'], "text"),
   GetSQLValueString($_POST['tipo_usuario'], "text"),
   GetSQLValueString($_POST['codigo_usuario'], "text"),
   GetSQLValueString($_POST['responsable'], "text"),
   GetSQLValueString($_POST['fecha_registro'], "date"),
   GetSQLValueString($_POST['responsable_edit'], "text"),
   GetSQLValueString($_POST['fecha_edit'], "date"),
   GetSQLValueString($_POST['email_usuario'], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertSQLL2 = sprintf("INSERT INTO accesos (usuario_id, acceso, superacceso) VALUES (%s, %s, %s)",
    GetSQLValueString($_POST['id_usuario'], "text"),
    GetSQLValueString($_POST['acceso'], "text"),
    GetSQLValueString($_POST['superacceso'], "text"));
  mysql_select_db($database_conexion1, $conexion1);
   $Result2 = mysql_query($insertSQLL2, $conexion1) or die(mysql_error());


  $insertGoTo = "usuarios.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$conexion = new ApptivaDB();

$colname_usuario_nuevo = "1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario_nuevo = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
$row_usuario_nuevo = $conexion->buscar('usuario','usuario',$colname_usuario); 
mysql_select_db($database_conexion1, $conexion1);
$query_ver_nuevo = "SELECT * FROM usuario ORDER BY id_usuario DESC";
$ver_nuevo = mysql_query($query_ver_nuevo, $conexion1) or die(mysql_error());
$row_ver_nuevo = mysql_fetch_assoc($ver_nuevo);
$totalRows_ver_nuevo = mysql_num_rows($ver_nuevo);

mysql_select_db($database_conexion1, $conexion1);
$query_ver_tipo_user = "SELECT * FROM tipo_user ORDER BY id_tipo ASC";
$ver_tipo_user = mysql_query($query_ver_tipo_user, $conexion1) or die(mysql_error());
$row_ver_tipo_user = mysql_fetch_assoc($ver_tipo_user);
$totalRows_ver_tipo_user = mysql_num_rows($ver_tipo_user);

mysql_select_db($database_conexion1, $conexion1);
$query_cliente = "SELECT * FROM cliente ORDER BY nombre_c ASC";
$cliente = mysql_query($query_cliente, $conexion1) or die(mysql_error());
$row_cliente = mysql_fetch_assoc($cliente);
$totalRows_cliente = mysql_num_rows($cliente);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>SISADGE AC &amp; CIA</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="css/formato.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="js/usuario.js"></script>
  <script type="text/javascript" src="js/formato.js"></script>
 
<!-- desde aqui para listados nuevos -->
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

<!-- select2 -->
<link href="select2/css/select2.min.css" rel="stylesheet"/>
<script src="select2/js/select2.min.js"></script>

<!-- css Bootstrap-->
<link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"> 

</head>
<body>
      <?php echo $conexion->header('listas'); ?>
                  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" onSubmit="MM_validateForm('fecha_registro','','R','usuario','','R','clave_usuario','','R','nombre_usuario','','R','email_usuario','','R');return document.MM_returnValue">
                    <table class="table table-bordered table-sm">    
                      <tr>
                        <td width="162" rowspan="8" id="dato2"><img src="images/logoacyc.jpg"></td>
                        <td colspan="2" id="fuente2"><strong>ADICIONAR USUARIO</strong></td>
                        <td id="dato2"><img src="images/ciclo1.gif" style="cursor:hand;" alt="RESTAURAR" onClick="window.history.go()" ><a href="usuarios.php"><img src="images/cat.gif" alt="LISTADO DE USUARIOS" border="0" style="cursor:hand;"></a></td>
                      </tr>
                      <tr>
                        <td id="fuente2">Fecha
                          <input name="id_usuario" type="hidden" value="<?php $num=$row_ver_nuevo['id_usuario']+1; echo $num; ?>">
                        Registro</td>
                        <td colspan="2" id="fuente1">Registrado por </td>
                      </tr>
                      <tr>
                        <td id="dato2"><input name="fecha_registro" type="text" id="fecha_registro" size="10" value="<?php echo date("Y/m/d"); ?>"></td>
                        <td colspan="2" id="detalle1"><?php echo $row_usuario_nuevo['nombre_usuario']; ?>
                          <input name="responsable" type="hidden" id="responsable" value="<?php echo $row_usuario_nuevo['nombre_usuario']; ?>"></td>
                        </tr>
                        <tr>
                          <td id="fuente2">Usuario</td>
                          <td id="fuente2">Clave de Acceso</td>
                          <td id="fuente2">Nombre de Usuario</td>
                        </tr>
                        <tr>
                          <td id="dato2"><input name="usuario" type="text" id="usuario" onBlur="if (form1.usuario.value) { DatosUsuario(form1.usuario.value); } else { alert('Debe digitar el usuario de acceso para validar su existencia en la BD'); }" value="" size="20"></td>
                          <td id="dato2"><input name="clave_usuario" type="text" id="clave_usuario" size="20"></td>
                          <td id="dato2"><input name="nombre_usuario" type="text" id="nombre_usuario" value="" size="30"></td>
                        </tr>
                        <tr>
                          <td id="fuente2">&nbsp;</td>
                          <td colspan="2" id="fuente2">E-mail de la compa&ntilde;ia</td>
                        </tr>
                        <tr>
                          <td id="dato2">&nbsp;</td>
                          <td colspan="2" id="dato2"><label for="email_usuario"></label>
                            <input type="text" name="email_usuario" id="email_usuario" size="50"></td>
                          </tr>
                          <tr>
                            <td id="dato2"><div id="existe"></div></td>
                            <td colspan="2" id="dato2">Nota: No se ingresara el usuario si existe en la BD!! </td>
                          </tr>
                          <tr id="tr2">
                            <td colspan="2" id="titulo4">TIPO DE USUARIO </td>
                            <td colspan="2" id="titulo4">CODIGO DE USUARIO </td>
                            <td colspan="2" id="titulo4">ACCESO </td>
                            <td colspan="2" id="titulo4">SUPER ACCESO </td>
                          </tr>
                          <tr>      
                            <td colspan="2" id="dato2">
                              <select name="tipo_usuario" onChange="DefinicionUsuario(form1.tipo_usuario.value,<?php echo $_SESSION['MM_IdUsername'];?>);">
                               <option value="">Seleccione</option>
                            <?php
                               do {  
                               ?>
                                 <option value="<?php echo $row_ver_tipo_user['id_tipo']?>"><?php echo $row_ver_tipo_user['nombre_tipo']?></option><?php
                               } while ($row_ver_tipo_user = mysql_fetch_assoc($ver_tipo_user));
                                 $rows = mysql_num_rows($ver_tipo_user);
                              if($rows > 0) {
                                 mysql_data_seek($ver_tipo_user, 0);
                                 $row_ver_tipo_user = mysql_fetch_assoc($ver_tipo_user);
                               }
                            ?>
                          </select></td>
                          <td colspan="2" id="dato2"><div id="definicion"></div></td>
                          <td colspan="2" id="dato2">
                            <select name="acceso">
                              <option value="0" selected>SIN ACCESO</option> 
                              <option value="1">CON ACCESO</option>
                            </select>
                          </td>
                          <td colspan="2" id="dato2">
                            <select name="superacceso">
                              <option value="0" selected>SIN SUPERACCESO</option> 
                              <option value="1">CON SUPERACCESO</option>
                            </select>
                          </td>
                        </tr>
                        <tr>
                          <td colspan="4" id="fuente2">Alguna inquietud o recomendaci&oacute;n favor comunicarse con sistemas@acycia.com
                            <input name="responsable_edit" type="hidden" id="responsable_edit" value="">
                            <input name="fecha_edit" type="hidden" id="fecha_edit" value=""></td>
                          </tr>    
                          <tr>
                            <td colspan="4" id="dato2"><input type="submit" value="Add Usuario"></td>
                          </tr>
                        </table>
                        <input type="hidden" name="MM_insert" value="form1">
                      </form>
            
                  <?php echo $conexion->header('footer'); ?>
              </body>
              </html>
              <?php
              mysql_free_result($usuario_nuevo);
              mysql_free_result($ver_nuevo);
              mysql_free_result($ver_tipo_user);

              mysql_free_result($cliente);
              ?>
