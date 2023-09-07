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
include('rud_cotizaciones/rud_cotizacion_materia_p.php');//SISTEMA RUW PARA LA BASE DE DATOS 

$conexion = new ApptivaDB();

$colname_usuario = "1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario); 

/*$colname_ver_nuevo = "-1";
if (isset($_GET['id_menu'])) {
  $colname_ver_nuevo = (get_magic_quotes_gpc()) ? $_GET['id_menu'] : addslashes($_GET['id_menu']);
}*/
/*mysql_select_db($database_conexion1, $conexion1);
$query_ver_ref = "SELECT * FROM Tbl_mp_vta ORDER BY id_mp_vta";
$ver_ref = mysql_query($query_ver_ref, $conexion1) or die(mysql_error());
$row_ver_ref = mysql_fetch_assoc($ver_ref);
$totalRows_ver_ref = mysql_num_rows($ver_ref);*/

$row_ver_refval = $conexion->llenarCampos("tbl_mp_vta","WHERE  id_mp_vta  ","","*" ); 

$row_ver_ref = $conexion->llenaListas("tbl_mp_vta","","ORDER BY id_mp_vta DESC","*" );

//ID DE INSUMOS
 

$row_ver_ref2 = $conexion->llenaSelect('insumo','','ORDER BY descripcion_insumo ASC');

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
<?php echo $conexion->header('listas'); ?>


	<form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" onsubmit="MM_validateForm('Str_nombre_r','','R');return document.MM_returnValue">
	<table  class="table table-bordered table-sm">
    <tr id="tr1">
        <td colspan="3" nowrap="nowrap" id="codigo">CODIGO : R1-F08 </td>
        <td nowrap="nowrap" id="titulo2">Referencia  Materia Prima</td>
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
        <td colspan="3" id="fuente1"><strong>Nota:</strong> al seleccionar una materia prima se guardara el ID del insumo de esta.</td>
        <td colspan="2"> <span class="text-success" > <?php echo $_GET['msn']; ?></span> </td>
</tr>

  <?php $ver=$row_ver_refval['id_mp_vta'];
  if($ver!='')
  {
  ?>
<tr id="tr1">
  <td width="181" id="titulo4">ID N°</td>
  <td colspan="2" id="titulo4">Nombre Referencia</td>
<td id="titulo4">Archivo Adjunto</td>
<td id="titulo4">ACCION</td>
</tr>

<?php foreach($row_ver_ref as $row_ver_ref) {  ?>

<tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF" bordercolor="#ACCFE8">
  <td id="detalle1"><?php echo $row_ver_ref['id_mp_vta']; ?></td>
  <td colspan="2" id="detalle1"><a href="cotizacion_general_materia_prima_ref_edit.php?id_mp_vta=<?php echo $row_ver_ref['id_mp_vta']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_ver_ref['Str_nombre']; ?></a></td>
<td id="detalle1"><a href="cotizacion_general_materia_prima_ref_edit.php?id_mp_vta=<?php echo $row_ver_ref['id_mp_vta']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_ver_ref['Str_linc_archivo']; ?></a></td>
<td id="detalle2"><a href="cotizacion_general_materia_prima_ref_edit.php?id_mp_vta=<?php echo $row_ver_ref['id_mp_vta']; ?>"><img src="images/menos.gif" alt="EDIT REF" border="0" style="cursor:hand;"/></a>
<a href="javascript:eliminar4('id_mp_vta',<?php echo $row_ver_ref['id_mp_vta']; ?>,'cotizacion_general_materia_prima_ref_nueva.php')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR REF" border="0"></a>
</td>
</tr><?php }  ?>
<?php
} ?>
<tr id="tr1">
  <td id="titulo4">N&deg;</td>
  <td colspan="2" id="titulo4">Digite Nuevo Nombre Referencia </td>
<td id="titulo4">Adjunte Archivo</td>
<td id="titulo4">Adicionar</td>
</tr>
<tr>
  <td id="dato2">  

    <select name="id_mp_vta" id="id_mp_vta" class="busqueda selectsMedio" >
      <option value="">Select</option>
      <?php  foreach($row_ver_ref2 as $row_ver_ref2 ) { ?>
        <option value="<?php echo $row_ver_ref2['id_insumo']?>"><?php echo $row_ver_ref2['codigo_insumo']."-".$row_ver_ref2['descripcion_insumo']?></option>
      <?php } ?>
    </select> 

    </td>
  <td colspan="2" id="dato2"><input name="Str_nombre_r" type="text" id="Str_nombre_r" size="28" maxlength="70"onkeyup="conMayusculas(this)"/></td>
<td id="dato2"><input name="Fil_archivo" type="file" /></td>
<td id="dato2"><input name="valor" type="hidden" value="3" />
    <input class="botonMini" name="submit" type="submit" onclick="MM_validateForm('Str_nombre_r','','R');return document.MM_returnValue"value="Add Referencia" /></td>
</tr>
<tr>
  <td colspan="5" id="dato">&nbsp;</td>
</tr>
<tr id="tr1">
<td colspan="5" id="dato2"><a href="cotizacion_general_materia_prima.php" class="botonGeneral">FINALIZAR REGISTRO Y REGRESAR</a></td>
</tr>
</table>
<input type="hidden" name="MM_insert" value="form1">
</form>
<?php echo $conexion->header('footer'); ?>
</body>
</html>
<?php
  

?>