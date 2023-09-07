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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
$maquina=$_POST['maquina'];
  $insertSQL = sprintf("INSERT INTO maquina (id_maquina, codigo_maquina, nombre_maquina, proceso_maquina) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['id_maquina'], "int"),
                       GetSQLValueString($_POST['codigo_maquina'], "text"),
                       GetSQLValueString($_POST['nombre_maquina'], "text"),
					   GetSQLValueString($_POST['proceso_maquina'], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertGoTo = "maquinas.php";
  /*if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }*/
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE maquina SET codigo_maquina=%s, nombre_maquina=%s, proceso_maquina=%s WHERE id_maquina=%s",
                       GetSQLValueString($_POST['codigo_maquina'], "text"),
                       GetSQLValueString($_POST['nombre_maquina'], "text"),
					   GetSQLValueString($_POST['proceso_maquina'], "text"),
                       GetSQLValueString($_POST['id_maquina'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "maquinas.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    //$updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    //$updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$conexion = new ApptivaDB();

$colname_usuario_admon = "1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario_admon = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario); 
$row_exste = $conexion->llenarCampos("maquina", "WHERE codigo_maquina <>'' ", "ORDER BY codigo_maquina ASC", "*");
$row_ver_maquinas = $conexion->llenaListas('maquina','','ORDER BY codigo_maquina ASC','*'); 

$colname_editar_maquina = "-1";
if (isset($_GET['id_maquina'])) {
  $colname_editar_maquina = (get_magic_quotes_gpc()) ? $_GET['id_maquina'] : addslashes($_GET['id_maquina']);
}

$row_editar_maquina = $conexion->llenarCampos("maquina", "WHERE id_maquina = '".$colname_editar_maquina."' ", " ", "*");

$row_procesos = $conexion->llenaSelect('tipo_procesos',"","ORDER BY nombre_proceso ASC");  

$row_procesos2 = $conexion->llenaSelect('tipo_procesos',"","ORDER BY nombre_proceso ASC");  
  

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/listado.js"></script>
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

  <!-- select2 -->
  <link href="select2/css/select2.min.css" rel="stylesheet"/>
  <script src="select2/js/select2.min.js"></script>
  
  <!-- css Bootstrap-->
  <link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"> 

</head>
<body>
  <?php echo $conexion->header('listas'); ?>
  <table id="tabla3">	
	<tr>
	  <td id="titulo6">LISTADO DE MAQUINAS</td>
	</tr>
	<?php /*Si no hay datos*/ 
	$id_maquina=$row_exste['id_maquina']; 
  if($id_maquina=='') { ?>
	<tr>
	  <td id="numero1">- NO HAY DATOS -</td>
	</tr>
	<tr>
	  <td id="subtitulo1">&nbsp;</td>
	  </tr>
	<?php } /*Si hay datos*/ if($id_maquina!='') { ?>
	<tr>
	  <td id="dato1">	  
	  <table id="tabla3">
	  <tr id="tr1">
	    <td id="titulo4">CODIGO</td>
		<td id="titulo4">NOMBRE</td>
		<td id="titulo4">PROCESO</td>
		<td id="titulo4">ACCION</td>
	  </tr>
	<?php foreach($row_ver_maquinas as $row_ver_maquinas) {  ?>
	<tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF" bordercolor="#ACCFE8">
	   <td id="detalle1"><?php echo $row_ver_maquinas['codigo_maquina']; ?></td>
       <td id="detalle1"><?php echo $row_ver_maquinas['nombre_maquina']; ?></td>
       <td id="detalle1"><?php echo $row_ver_maquinas['proceso_maquina']; ?></td>
       <td id="detalle2"><a href="maquinas.php?id_maquina=<?php echo $row_ver_maquinas['id_maquina']; ?>&amp;maquina=1"><img src="images/menos.gif" alt="EDITAR" border="0" style="cursor:hand;"/></a>
        <a href="javascript:eliminar1('id_maquina',<?php echo $row_ver_maquinas['id_maquina']; ?>,'maquinas.php')"><img src="images/por.gif" alt="ELIMINAR" border="0" style="cursor:hand;"/></a></td>
    </tr>
	<?php } ?>
  </table> </td>
</tr>
<?php } /*fin de los datos*/
/*EDITAR MAQUINA*/
$maquina=$_GET['maquina']; if($maquina=='1') { ?>
<tr>
 <td align="left">
    <form method="post" name="form1" action="<?php echo $editFormAction; ?>" onsubmit="MM_validateForm('codigo_maquina','','R','nombre_maquina','','R','proceso_maquina','','R');return document.MM_returnValue">
        <table id="tabla35">
          
          <tr>
            <td colspan="2" id="titulo6">EDITAR DATOS DE MAQUINA</td>
            <td id="titulo6">&nbsp;</td>
          </tr>
          <tr>
            <td id="fuente1">CODIGO</td>
            <td id="fuente1"><input type="text" name="codigo_maquina" value="<?php echo $row_editar_maquina['codigo_maquina']; ?>" size="10" onblur="if (form1.codigo_maquina.value) { DatosDos('codigo_maquina',form1.codigo_maquina.value,'id_maquina',form1.id_maquina.value); } else { alert('Debe digitar el codigo de maquina'); }"/></td>
            <td id="fuente1"><div id="resultado2"></div></td>
          </tr>
          <tr>
            <td id="fuente1">NOMBRE</td>
            <td colspan="2" id="fuente1"><input type="text" name="nombre_maquina" value="<?php echo $row_editar_maquina['nombre_maquina']; ?>" size="30" /></td>
            </tr>
          <tr>
            <td id="fuente1">PROCESO</td>
            <td colspan="2" id="dato1">
              <select name="proceso_maquina" id="proceso_maquina"  class="selectsMedio">
                  <option value=""<?php if (!(strcmp("", $row_editar_maquina['proceso_maquina']))) {echo "selected=\"selected\"";} ?>>Seleccione</option>
                    <?php  foreach($row_procesos as $row_procesos ) { ?>
                  <option value="<?php echo $row_procesos['id_tipo_proceso']?>"<?php if (!(strcmp($row_procesos['id_tipo_proceso'], $row_editar_maquina['proceso_maquina']))) {echo "selected=\"selected\"";} ?>><?php echo $row_procesos['nombre_proceso']?></option>
              <?php } ?>
              </select>
              </td>
            </tr>
          <tr>
            <td colspan="3" id="dato1"><input class="botonGeneral" name="submit" type="submit" value="Actualizar Maquina" />  <a href="maquinas.php">Cancelar</a></td>
            </tr>
        </table>
        <input type="hidden" name="MM_update" value="form1">
        <input name="id_maquina" type="hidden" value="<?php echo $row_editar_maquina['id_maquina']; ?>">
      </form> </td>
</tr>
<?php } /*FIN DE EDITAR LAS MAQUINAS*/ 
if($maquina!='1') { ?>
<tr>
  <td align="left">
    <form action="<?php echo $editFormAction; ?>" method="post" name="form2" onsubmit="MM_validateForm('codigo_maquina','','R','nombre_maquina','','R','proceso_maquina','','R');return document.MM_returnValue">
	<table id="tabla35">
	<tr>
	  <td id="titulo6">NUEVA MAQUINA</td>
	  <td id="titulo6">&nbsp;</td>
	  <td id="titulo6">&nbsp;</td>
	</tr>
	<tr>
	  <td id="fuente1">CODIGO</td>
	  <td id="fuente1"><input name="id_maquina" type="hidden" id="id_maquina" />
	    <input name="codigo_maquina" type="text" id="codigo_maquina" size="10" value="" onBlur="if (form2.codigo_maquina.value) { DatosGestiones('4','codigo_maquina',form2.codigo_maquina.value); } else { alert('Debe digitar el codigo de la maquina'); }" /></td>
      <td id="fuente1"><div id="resultado"></div></td>
	</tr>
   <tr>
    <td id="fuente1">NOMBRE DE MAQUINA</td>
    <td colspan="2" id="fuente1"><input type="text" name="nombre_maquina" value="" size="30" /></td>
  </tr>
  <tr>
    <td id="fuente1">PROCESO</td>
    <td colspan="2" id="fuente1">
      <select name="proceso_maquina" id="proceso_maquina"  class="selectsMedio"> 
        <option value=""<?php if (!(strcmp("", $row_editar_maquina['proceso_maquina']))) {echo "selected=\"selected\"";} ?>>Seleccione</option>
        <?php  foreach($row_procesos2 as $row_procesos2 ) { ?>
          <option value="<?php echo $row_procesos2['id_tipo_proceso']?>"<?php if (!(strcmp($row_procesos2['id_tipo_proceso'], $row_editar_maquina['proceso_maquina']))) {echo "selected=\"selected\"";} ?>><?php echo $row_procesos2['nombre_proceso']?></option>
      <?php } ?>
      </select>

 </td>
  </tr>
  <tr>
    <td colspan="3" id="dato1"><input class="botonGeneral" name="submit" type="submit" value="Add Maquina" /></td>
  </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form2">
  </form> </td>
</tr>
  <?php } ?>
  </table>
 
<?php echo $conexion->header('footer'); ?>
</div>
</body>
</html>
<?php
mysql_free_result($usuario_admon);

mysql_free_result($ver_maquinas);

mysql_free_result($editar_maquina);

mysql_free_result($procesos);
?>