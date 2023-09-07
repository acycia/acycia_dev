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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO orden_compra (n_oc, id_p_oc, fecha_pedido_oc, fecha_entrega_oc, responsable_oc) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['n_oc'], "int"),
                       GetSQLValueString($_POST['id_p_oc'], "int"),
                       GetSQLValueString($_POST['fecha_pedido_oc'], "date"),
                       GetSQLValueString($_POST['fecha_entrega_oc'], "date"),
					   GetSQLValueString($_POST['responsable_oc'], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertGoTo = "orden_compra_edit.php?n_oc=" . $_POST['n_oc'] . "&id_p_oc=" . $_POST['id_p_oc'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$conexion = new ApptivaDB();

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

mysql_select_db($database_conexion1, $conexion1);
$query_ultimo = "SELECT * FROM orden_compra ORDER BY n_oc DESC";
$ultimo = mysql_query($query_ultimo, $conexion1) or die(mysql_error());
$row_ultimo = mysql_fetch_assoc($ultimo);
$totalRows_ultimo = mysql_num_rows($ultimo);

mysql_select_db($database_conexion1, $conexion1);
$query_proveedores = "SELECT * FROM proveedor ORDER BY proveedor_p ASC";
$proveedores = mysql_query($query_proveedores, $conexion1) or die(mysql_error());
$row_proveedores = mysql_fetch_assoc($proveedores);
$totalRows_proveedores = mysql_num_rows($proveedores);
?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>

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

<!-- Select3 Nuevo -->
<meta charset="UTF-8">
<!-- jQuery -->
<script src='select3/assets/js/jquery-3.4.1.min.js' type='text/javascript'></script>

<!-- select2 css -->
<link href='select3/assets/plugin/select2/dist/css/select2.min.css' rel='stylesheet' type='text/css'>

<!-- select2 script -->
<script src='select3/assets/plugin/select2/dist/js/select2.min.js'></script>
<!-- Styles -->
<link rel="stylesheet" href="select3/assets/css/style.css">
<!-- Fin Select3 Nuevo -->

</head>
<body>
<?php echo $conexion->header('vistas'); ?>
 <form action="<?php echo $editFormAction; ?>" method="post" name="form1" onSubmit="MM_validateForm('fecha_pedido_oc','','R','fecha_entrega_oc','','R','id_p_oc','','R');return document.MM_returnValue">
        <table class="table table-bordered table-sm">
          <tr id="tr1">
      <td nowrap id="codigo" width="25%">CODIGO : A3 - F02 </td>
      <td nowrap id="titulo2" width="50%">ORDEN DE COMPRA </td>
      <td nowrap id="codigo" width="25%">VERSION : 1 </td>
    </tr>
          <tr>
            <td rowspan="6" id="dato2"><img src="images/logoacyc.jpg"></td>
            <td id="subtitulo">INSUMOS</td>			
            <td id="dato2"><img src="images/ciclo1.gif" alt="RESTAURAR" border="0" style="cursor:hand;" onClick="window.history.go()"/><a href="orden_compra.php"><img src="images/o.gif" alt="ORDENES DE COMPRA" border="0" style="cursor:hand;"/></a><a href="compras.php"><img src="images/opciones.gif" alt="GESTION DE COMPRAS" border="0" style="cursor:hand;"/></a></td>
          </tr>
          <tr>
            <td id="numero2">N&deg; <strong>
              <?php $numero=$row_ultimo['n_oc']+1; echo $numero; ?>
              <input name="n_oc" type="hidden" value="<?php echo $numero; ?>">
            </strong></td>
            <td id="fuente1">&nbsp;</td>
          </tr>
          <tr>
            <td id="fuente1">FECHA DE PEDIDO </td>
            <td id="fuente1">FECHA RECIBIDO</td>
            </tr>
          <tr>
            <td id="dato1"><input type="date" name="fecha_pedido_oc" value="<?php echo date("Y-m-d"); ?>" size="10"></td>
            <td id="dato1"><input type="date" name="fecha_entrega_oc" value="" size="10"></td>
            </tr>
          <tr>
            <td colspan="2" id="fuente1">PROVEEDOR</td>
            </tr>
          <tr>
            <td colspan="2" id="dato1"><select name="id_p_oc" id="id_p_oc" onChange="DatosGestiones('3','id_p',form1.id_p_oc.value);">
              <option value="">SELECCIONE</option>
              <?php
                 do {  
                 ?>
                  <option value="<?php echo $row_proveedores['id_p']?>"><?php echo $row_proveedores['proveedor_p']?></option><?php
                 } while ($row_proveedores = mysql_fetch_assoc($proveedores));
                   $rows = mysql_num_rows($proveedores);
                   if($rows > 0) {
                       mysql_data_seek($proveedores, 0);
                 	  $row_proveedores = mysql_fetch_assoc($proveedores);
                   }
                 ?>
            </select></td>
            </tr>
          <tr>
            <td colspan="3" id="fuente2"><div id="resultado"></div></td>
            </tr>
          <tr>
            <td colspan="3" id="dato2"><input class="botonGeneral" type="submit" value="SIGUIENTE"></td>
            </tr>
        </table>
        <input type="hidden" name="responsable_oc" value="<?php echo $row_usuario['nombre_usuario']; ?>">
        <input type="hidden" name="MM_insert" value="form1">
      </form> 
<?php echo $conexion->header('footer'); ?>
</body>
</html>

<script>
  
 $('#id_p_oc').select2({ 
        ajax: {
            url: "select3/proceso.php",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    palabraClave: params.term, // search term
                    var1:"id_p",//campo normal para usar
                    var2:"proveedor",//tabla
                    var3:"",//where
                    var4:"ORDER BY proveedor_p ASC",
                    var5:"id_p",//clave
                    var6:"proveedor_p"//columna a buscar
                };
            },
            processResults: function (response) {
                return {
                    results: response
                };
            },
            cache: true
        }
    }); 

 
</script>

<?php
mysql_free_result($usuario);

mysql_free_result($ultimo);

mysql_free_result($proveedores);
?>