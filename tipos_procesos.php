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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE tipo_procesos SET nombre_proceso=%s WHERE id_tipo_proceso=%s",
                       GetSQLValueString($_POST['nombre_proceso'], "text"),
                       GetSQLValueString($_POST['id_tipo_proceso'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "tipos_procesos.php";
  /*if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }*/
  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO tipo_procesos (id_tipo_proceso, nombre_proceso) VALUES (%s, %s)",
                       GetSQLValueString($_POST['id_tipo_proceso'], "int"),
                       GetSQLValueString($_POST['nombre_proceso'], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertGoTo = "tipos_procesos.php";
  /*if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }*/
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
$query_lista_procesos = "SELECT * FROM tipo_procesos ORDER BY nombre_proceso ASC";
$lista_procesos = mysql_query($query_lista_procesos, $conexion1) or die(mysql_error());
$row_lista_procesos = mysql_fetch_assoc($lista_procesos);
$totalRows_lista_procesos = mysql_num_rows($lista_procesos);

$colname_editar_procesos = "-1";
if (isset($_GET['id_tipo_proceso'])) {
  $colname_editar_procesos = (get_magic_quotes_gpc()) ? $_GET['id_tipo_proceso'] : addslashes($_GET['id_tipo_proceso']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_editar_procesos = sprintf("SELECT * FROM tipo_procesos WHERE id_tipo_proceso = %s", $colname_editar_procesos);
$editar_procesos = mysql_query($query_editar_procesos, $conexion1) or die(mysql_error());
$row_editar_procesos = mysql_fetch_assoc($editar_procesos);
$totalRows_editar_procesos = mysql_num_rows($editar_procesos);
?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
<link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">

<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
<script type="text/javascript" src="js/listado.js"></script>

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
<link rel="stylesheet" type="text/css" href="css/general.css"/>

<!-- css Bootstrap-->
<link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

</head>
<body>
 <?php echo $conexion->header('listas'); ?>
 
 <table id="tabla3">
      <tr><td id="subtitulo1">LISTADO DE PROCESOS</td>
        </tr>
	  <?php $proceso=$row_lista_procesos['id_tipo_proceso']; if($proceso=='') { ?>
	  <tr>
	    <td id="numero1">- No hay procesos adicionados - </td>
	    </tr>
		<?php }  else { ?>
	  <tr>
        <td id="dato1">
          <table id="tabla35">
		  <?php do { ?>
		    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF" bordercolor="#ACCFE8">            
		      <td id="detalle1"><?php echo $row_lista_procesos['nombre_proceso']; ?></td>
		      <td id="detalle2"><a href="tipos_procesos.php?id_tipo_proceso=<?php echo $row_lista_procesos['id_tipo_proceso']; ?>"><img src="images/menos.gif" alt="EDIT PROCESO" border="0" style="cursor:hand;"/></a><a href="javascript:eliminar1('id_tipo_proceso',<?php echo $row_lista_procesos['id_tipo_proceso']; ?>,'tipos_procesos.php')"><img src="images/por.gif" alt="ELIMINAR" border="0" style="cursor:hand;"/></a></td>
		    </tr>
		    <?php } while ($row_lista_procesos = mysql_fetch_assoc($lista_procesos)); ?>
		  </table></td>
        </tr>
	  <?php } ?>
	  <?php $id_tipo_proceso=$_GET['id_tipo_proceso']; if($id_tipo_proceso=='') { ?>
      <tr>
        <td><form action="<?php echo $editFormAction; ?>" method="post" name="form1" onSubmit="MM_validateForm('nombre_proceso','','R');return document.MM_returnValue">
            <table>
              <tr>
                <td id="dato1"><input type="text" name="nombre_proceso" value="" size="20"></td>
                <td id="dato2"><input name="submit" type="submit" value="Adicionar"></td>
              </tr>
            </table>
            <input type="hidden" name="id_tipo_proceso" value="">
            <input type="hidden" name="MM_insert" value="form1">
          </form></td>
        </tr><?php } ?>
	  <?php $id_tipo_proceso=$_GET['id_tipo_proceso']; if($id_tipo_proceso!='') { ?>
      <tr>
        <td id="dato1"><form method="post" name="form2" action="<?php echo $editFormAction; ?>" onSubmit="MM_validateForm('nombre_proceso','','R');return document.MM_returnValue">
            <table>
              <tr>
                <td><input type="text" name="nombre_proceso" value="<?php echo $row_editar_procesos['nombre_proceso']; ?>" size="20"></td>
                <td><input name="submit2" type="submit" value="Actualizar"></td>
              </tr>
            </table>
            <input type="hidden" name="MM_update" value="form2">
            <input type="hidden" name="id_tipo_proceso" value="<?php echo $row_editar_procesos['id_tipo_proceso']; ?>">
          </form><a href="tipos_procesos.php">Cancelar</a></td>
        </tr><?php } ?>
    </table></td>
  </tr>
</table>
<?php echo $conexion->header('footer'); ?>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($lista_procesos);

mysql_free_result($editar_procesos);
?>