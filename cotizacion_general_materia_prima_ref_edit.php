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
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
    $updateGoTo = "cotizacion_general_materia_prima_ref.php?id_mp_vta=" . $_POST["Str_referencia_m"];
  header(sprintf("Location: %s", $updateGoTo));
}
?>
<?php
$conexion = new ApptivaDB();

include('rud_cotizaciones/rud_cotizacion_materia_p.php');//SISTEMA RUW PARA LA BASE DE DATOS 
$colname_usuario= "1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

$colname_ver_ref = "-1";
if (isset($_GET['id_mp_vta'])) {
  $colname_ver_ref = (get_magic_quotes_gpc()) ? $_GET['id_mp_vta'] : addslashes($_GET['id_mp_vta']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_ref = sprintf("SELECT * FROM Tbl_mp_vta WHERE id_mp_vta=%s",$colname_ver_ref);
$ver_ref = mysql_query($query_ver_ref, $conexion1) or die(mysql_error());
$row_ver_ref = mysql_fetch_assoc($ver_ref);
$totalRows_ver_ref = mysql_num_rows($ver_ref);
//ID DE INSUMOS
mysql_select_db($database_conexion1, $conexion1);
$query_ver_ref2 = "SELECT id_insumo,codigo_insumo,descripcion_insumo FROM insumo  ORDER BY descripcion_insumo ASC";
$ver_ref2 = mysql_query($query_ver_ref2, $conexion1) or die(mysql_error());
$row_ver_ref2 = mysql_fetch_assoc($ver_ref2);
$totalRows_ver_ref2 = mysql_num_rows($ver_ref2);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/listado.js"></script>
<script type="text/javascript" src="js/adjuntos.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>

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
  <div class="spiffy_content">  
    <div align="center">
      <table id="tabla1"><!-- id="tabla1" -->
        <tr>
         <td align="center">
           <div class="row-fluid">
             <div class="span8 offset2"> <!--span8 offset2   esto da el tamaño pequeño -->
               <div class="panel panel-primary">
                <div class="panel-heading" align="left" ></div><!--color azul-->
                <div class="row" >
                  <div class="span12">&nbsp;&nbsp;&nbsp; <img src="images/cabecera.jpg"></div> 
                </div>
                <div class="panel-heading" align="left" ></div><!--color azul-->
                <div id="cabezamenu">
                  <ul id="menuhorizontal">
                    <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                    <li><a href="menu.php">MENU PRINCIPAL</a></li> 
                    <li><?php echo $_SESSION['Usuario']; ?></li>
                  </ul>
                </div> 
                <div class="panel-body">
                 <br> 
                 <div class="container">
                  <div class="row">
                    <div class="span12"> 
                    </div>
                  </div>
	<form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" onsubmit="MM_validateForm('Str_nombre_r','','R');return document.MM_returnValue">
	<table id="tabla1">
    <tr id="tr1">
        <td colspan="3" nowrap="nowrap" id="codigo">CODIGO : R1-F08 </td>
        <td nowrap="nowrap" id="titulo2">Editar Referencia  MP</td>
        <td nowrap="nowrap" id="codigo"> VERSION: 2</td>
        </tr>
	<tr>
	  <td colspan="3" rowspan="7" id="dato2"><img src="images/logoacyc.jpg" /></td>
  <td id="numero2">&nbsp;</td>
        <td id="fuente2"><a href="referencias.php"><img src="images/a.gif" style="cursor:hand;" alt="REFERENCIAS ACTIVAS" title="REFERENCIAS ACTIVAS" border="0" /></a><a href="referencias_inactivas.php"><img src="images/i.gif" style="cursor:hand;" alt="REFERENCIAS INACTIVAS" title="REFERENCIAS INACTIVAS" border="0" /></a><a href="cotizacion_copia.php"><img src="images/opciones.gif" style="cursor:hand;" alt="LISTADO COTIZACIONES"title="LISTADO COTIZACIONES" border="0" /></a><img src="images/ciclo1.gif" style="cursor:hand;" alt="RESTAURAR" border="0" onclick="window.history.go()" /></td>
      </tr>
      <tr>
        <td id="titulo2">&nbsp;</td>
        <td id="numero1">&nbsp;</td>
      </tr>
      <tr>
        <td id="fuente1">Fecha  Ingreso</td>
        <td id="fuente1">Hora Ingreso</td>
      </tr>
      <tr>
        <td id="fuente1"><input name="fecha_m" type="date" min="2000-01-02" value="<?php echo date("Y-m-d"); ?>" size="10" required="required"/></td>
        <td id="fuente1"><input name="hora_m" type="text" id="hora_m" value="<?php echo date("g:i a") ?>" size="10" readonly="true" /></td>
      </tr>
      <tr>
        <td id="fuente1">Ingresado por</td>
        <td id="fuente1">&nbsp;</td>
      </tr>
      <tr>
        <td id="fuente1"><input name="Str_usuario" type="text" id="Str_usuario" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="20" readonly="true" /></td>
        <td id="dato4">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" id="fuente2">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="5" id="fuente1">&nbsp;</td>
</tr>
<tr id="tr1">
  <td width="181" id="titulo4">ID N°</td>
  <td colspan="2" id="titulo4">Nombre Referencia</td>
<td id="titulo4">Archivo Adjunto</td>
<td id="titulo4">Editar Referencia</td>
</tr>
<tr>
  <td id="dato2"><input type="hidden" name="id_mp" id="id_mp" value="<?php echo $row_ver_ref['id_mp_vta'] ?>" />
    <select name="id_mp_vta" id="id_mp_vta" style="width:100px" >
    <option value=""<?php if (!(strcmp("", $_GET['id_mp_vta']))) {echo "selected=\"selected\"";} ?>>Select</option>
      <?php
do {  
?>
      <option value="<?php echo $row_ver_ref2['id_insumo']?>"<?php if (!(strcmp($row_ver_ref2['id_insumo'], $_GET['id_mp_vta']))) {echo "selected=\"selected\"";} ?>><?php echo $row_ver_ref2['codigo_insumo']."-".$row_ver_ref2['descripcion_insumo']?></option>
      <?php
} while ($row_ver_ref2 = mysql_fetch_assoc($ver_ref2));
  $rows = mysql_num_rows($ver_ref2);
  if($rows > 0) {
      mysql_data_seek($ver_ref2, 0);
	  $row_ver_ref2 = mysql_fetch_assoc($ver_ref2);
  }
?>
  </select></td>
  <td colspan="2" id="dato2"><input name="Str_nombre_r" type="text" id="Str_nombre_r" size="28" maxlength="70"onkeyup="conMayusculas(this)" value="<?php echo $row_ver_ref['Str_nombre'] ?>"/></td>
  <td id="dato1"><input type="hidden" name="arte1" value="<?php echo $row_ver_ref['Str_linc_archivo'] ?>"/><a href="javascript:verFoto('archivosc/archivos_pdf_mp/<?php echo $row_ver_ref['Str_linc_archivo'] ?>','610','490')" target="_blank"><?php echo $row_ver_ref['Str_linc_archivo'] ?></a>    <input type="file" name="Fil_archivo"/></td>
  <td id="dato2"><input name="valor" type="hidden" value="4" />
    <input name="submit" type="submit" onclick="MM_validateForm('Str_nombre_r','','R');return document.MM_returnValue"value="Editar" /></td>
</tr>
<tr>
  <td colspan="5" id="dato">&nbsp;</td>
</tr>
<tr id="tr1">
<td colspan="5" id="dato2"><a href="cotizacion_general_materia_prima_ref_nueva.php">FINALIZAR EDICION Y REGRESAR</a></td>
</tr>
</table>
<input type="hidden" name="MM_insert" value="form1">
</form>
<?php echo $conexion->header('footer'); ?>
</body>
</html>
<?php
mysql_free_result($usuario);
mysql_free_result($ver_ref);
?>