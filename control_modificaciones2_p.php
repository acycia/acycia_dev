<?php
   require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
   require (ROOT_BBDD); 
?><?php require_once('Connections/conexion1.php'); ?>
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
$conexion = new ApptivaDB();
$currentPage = $_SERVER["PHP_SELF"];

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
$query_control_modificaciones = "SELECT * FROM Tbl_control_modificaciones_p ORDER BY id_cm DESC";
$control_modificaciones = mysql_query($query_control_modificaciones, $conexion1) or die(mysql_error());
$row_control_modificaciones = mysql_fetch_assoc($control_modificaciones);
$totalRows_control_modificaciones = mysql_num_rows($control_modificaciones);

mysql_select_db($database_conexion1, $conexion1);
$query_verificacion = "SELECT * FROM Tbl_verificacion_packing ORDER BY id_verif_p  DESC";
$verificacion = mysql_query($query_verificacion, $conexion1) or die(mysql_error());
$row_verificacion = mysql_fetch_assoc($verificacion);
$totalRows_verificacion = mysql_num_rows($verificacion);

mysql_select_db($database_conexion1, $conexion1);
$query_referencia = "SELECT * FROM Tbl_referencia WHERE tipo_bolsa_ref='PACKING_LIST' ORDER BY cod_ref ASC";
$referencia = mysql_query($query_referencia, $conexion1) or die(mysql_error());
$row_referencia = mysql_fetch_assoc($referencia);
$totalRows_referencia = mysql_num_rows($referencia);

mysql_select_db($database_conexion1, $conexion1);
$query_anual = "SELECT * FROM anual ORDER BY anual DESC";
$anual = mysql_query($query_anual, $conexion1) or die(mysql_error());
$row_anual = mysql_fetch_assoc($anual);
$totalRows_anual = mysql_num_rows($anual);

$maxRows_modificaciones = 20;
$pageNum_modificaciones = 0;
if (isset($_GET['pageNum_modificaciones'])) {
  $pageNum_modificaciones = $_GET['pageNum_modificaciones'];
}
$startRow_modificaciones = $pageNum_modificaciones * $maxRows_modificaciones;

mysql_select_db($database_conexion1, $conexion1);
$id_cm = $_GET['id_cm'];
$id_verif = $_GET['id_verif'];
$id_ref = $_GET['id_ref'];
$fecha = $_GET['fecha'];
//Filtra todos vacios
if($id_cm == '0' && $id_verif == '0' && $id_ref == '0' && $fecha == '0')
{
$query_modificaciones = "SELECT * FROM Tbl_control_modificaciones_p ORDER BY id_cm DESC";
}
//Filtra cm lleno
if($id_cm != '0' && $id_verif == '0' && $id_ref == '0' && $fecha == '0')
{
$query_modificaciones = "SELECT * FROM Tbl_control_modificaciones_p WHERE id_cm='$id_cm' ORDER BY id_cm DESC";
}
//Filtra verificacion lleno
if($id_cm == '0' && $id_verif != '0' && $id_ref == '0' && $fecha == '0')
{
$query_modificaciones = "SELECT * FROM Tbl_control_modificaciones_p WHERE id_verif_cm='$id_verif' ORDER BY id_cm DESC";
}
//Filtra referencia lleno
if($id_cm == '0' && $id_verif == '0' && $id_ref != '0' && $fecha == '0')
{
$query_modificaciones = "SELECT * FROM Tbl_control_modificaciones_p WHERE id_ref_cm='$id_ref' ORDER BY id_cm DESC";
}
//Filtra fecha lleno
if($id_cm == '0' && $id_verif == '0' && $id_ref == '0' && $fecha != '0')
{
$fecha1 = "$fecha-01-01";
$fecha2 = $fecha + 1;
$fecha2 = "$fecha2-01-01";
$query_modificaciones = "SELECT * FROM Tbl_control_modificaciones_p WHERE fecha_cm >= '$fecha1' and fecha_cm < '$fecha2' ORDER BY id_cm DESC";
}

$query_limit_modificaciones = sprintf("%s LIMIT %d, %d", $query_modificaciones, $startRow_modificaciones, $maxRows_modificaciones);
$modificaciones = mysql_query($query_limit_modificaciones, $conexion1) or die(mysql_error());
$row_modificaciones = mysql_fetch_assoc($modificaciones);

if (isset($_GET['totalRows_modificaciones'])) {
  $totalRows_modificaciones = $_GET['totalRows_modificaciones'];
} else {
  $all_modificaciones = mysql_query($query_modificaciones);
  $totalRows_modificaciones = mysql_num_rows($all_modificaciones);
}
$totalPages_modificaciones = ceil($totalRows_modificaciones/$maxRows_modificaciones)-1;

$queryString_modificaciones = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_modificaciones") == false && 
        stristr($param, "totalRows_modificaciones") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_modificaciones = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_modificaciones = sprintf("&totalRows_modificaciones=%d%s", $totalRows_modificaciones, $queryString_modificaciones);
?><html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
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
  
 <!-- css Bootstrap-->
 <link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

</head>
<body>
<?php echo $conexion->header('listas'); ?>
	<form action="control_modificaciones2_p.php" method="get" name="consulta">
	<table id="tabla1">
      <tr id="tr1">
	  <td nowrap="nowrap" id="codigo">CODIGO: R2-F02</td>
	  <td nowrap="nowrap" id="titulo2">PLAN DE DISEÑO Y DESARROLLO</td>
	  <td nowrap="nowrap" id="codigo">VERSION: 3</td>
	  </tr>
	  <tr>
	  <td colspan="3" id="subtitulo">CONTROL DE MODIFICACIONES </td>
	  </tr>
	  <tr>
	  <td colspan="3" id="fuente2">
      <select name="id_cm" id="id_cm">
        <option value="0" <?php if (!(strcmp(0, $_GET['id_cm']))) {echo "selected=\"selected\"";} ?>>C.M.</option>
        <?php
do {  
?><option value="<?php echo $row_control_modificaciones['id_cm']?>"<?php if (!(strcmp($row_control_modificaciones['id_cm'], $_GET['id_cm']))) {echo "selected=\"selected\"";} ?>><?php echo $row_control_modificaciones['id_cm']?></option>
        <?php
} while ($row_control_modificaciones = mysql_fetch_assoc($control_modificaciones));
  $rows = mysql_num_rows($control_modificaciones);
  if($rows > 0) {
      mysql_data_seek($control_modificaciones, 0);
	  $row_control_modificaciones = mysql_fetch_assoc($control_modificaciones);
  }
?>
      </select>
	  <select name="id_verif" id="id_verif">
	    <option value="0" <?php if (!(strcmp(0, $_GET['id_verif']))) {echo "selected=\"selected\"";} ?>>VERIF</option>
	    <?php
do {  
?><option value="<?php echo $row_verificacion['id_verif']?>"<?php if (!(strcmp($row_verificacion['id_verif'], $_GET['id_verif']))) {echo "selected=\"selected\"";} ?>><?php echo $row_verificacion['id_verif']?></option>
	    <?php
} while ($row_verificacion = mysql_fetch_assoc($verificacion));
  $rows = mysql_num_rows($verificacion);
  if($rows > 0) {
      mysql_data_seek($verificacion, 0);
	  $row_verificacion = mysql_fetch_assoc($verificacion);
  }
?>
      </select>
      <select name="id_ref" id="id_ref">
        <option value="0" <?php if (!(strcmp(0, $_GET['id_ref']))) {echo "selected=\"selected\"";} ?>>REF</option>
        <?php
do {  
?><option value="<?php echo $row_referencia['id_ref']?>"<?php if (!(strcmp($row_referencia['id_ref'], $_GET['id_ref']))) {echo "selected=\"selected\"";} ?>><?php echo $row_referencia['cod_ref']?></option>
        <?php
} while ($row_referencia = mysql_fetch_assoc($referencia));
  $rows = mysql_num_rows($referencia);
  if($rows > 0) {
      mysql_data_seek($referencia, 0);
	  $row_referencia = mysql_fetch_assoc($referencia);
  }
?>
      </select>
      <select name="fecha" id="fecha">
        <option value="0" <?php if (!(strcmp(0, $_GET['fecha']))) {echo "selected=\"selected\"";} ?>>ANUAL</option>
        <?php
do {  
?><option value="<?php echo $row_anual['anual']?>"<?php if (!(strcmp($row_anual['anual'], $_GET['fecha']))) {echo "selected=\"selected\"";} ?>><?php echo $row_anual['anual']?></option>
        <?php
} while ($row_anual = mysql_fetch_assoc($anual));
  $rows = mysql_num_rows($anual);
  if($rows > 0) {
      mysql_data_seek($anual, 0);
	  $row_anual = mysql_fetch_assoc($anual);
  }
?>
      </select>
      <input type="submit" name="Submit" value="FILTRO" onClick="if(consulta.id_cm.value=='0' && consulta.id_verif.value=='0'&& consulta.id_ref.value=='0' && consulta.fecha.value=='0') { alert('DEBE SELECCIONAR UNA OPCION'); }"/>    
      </td>
  </tr>
</table>
</form>
<form action="delete_listado.php" method="get" name="seleccion">
<table id="tabla1">
  <tr>
    <td colspan="2" id="dato1"><input name="borrado" type="hidden" id="borrado" value="27" />
      <input name="Input" type="submit" value="Delete"/></td>
    <td colspan="5"><?php $id=$_GET['id']; 
  if($id >= '1') { ?> <div id="acceso1"> <?php echo "ELIMINACION COMPLETA"; ?> </div> <?php }
  if($id == '0') { ?><div id="numero1"> <?php echo "SELECCIONE UN REGISTRO"; ?> </div> <?php }?></td>
    <td colspan="2" id="dato3"><a href="control_modificaciones_p.php"><img src="images/ciclo1.gif" alt="RESTAURAR" title="RESTAURAR" border="0" style="cursor:hand;"/></a><a href="referencias.php"><img src="images/a.gif" alt="REF'S ACTIVAS" title="REF'S ACTIVAS" border="0" style="cursor:hand;"/></a><a href="referencias_inactivas.php"><img src="images/i.gif" alt="REF'S INACTIVAS" title="REF'S INACTIVAS" border="0" style="cursor:hand;"/></a><a href="revision_p.php"><img src="images/r.gif" alt="REVISIONES" title="REVISIONES" border="0" style="cursor:hand;" /></a><a href="validacion_p.php"><img src="images/v.gif" alt="VALIDACIONES" title="VALIDACIONES" border="0" style="cursor:hand;" /></a><a href="ficha_tecnica_p.php"><img src="images/f.gif" alt="FICHAS TECNICAS" title="FICHAS TECNICAS" border="0" style="cursor:hand;" /></a></td>
    </tr>  
  <tr id="tr1">
    <td id="titulo4"><input name="chulo1" type="checkbox" onClick="if(seleccion.chulo1.checked) { seleccionar_todo() } else{ deseleccionar_todo() } "/></td>
    <td nowrap="nowrap" id="titulo4"> C.M.</td>
    <td nowrap="nowrap" id="titulo4">VERIF </td>
    <td nowrap="nowrap" id="titulo4">REFERENCIA</td>
    <td nowrap="nowrap" id="titulo4">VERSION</td>
    <td nowrap="nowrap" id="titulo4">FECHA C.M. </td>
    <td nowrap="nowrap" id="titulo4">RESPONSABLE C.M. </td>
    <td nowrap="nowrap" id="titulo4">FECHA EDICION</td>
    <td nowrap="nowrap" id="titulo4">EDITADO POR </td>
    </tr>
  <?php do { ?>
    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
        <td id="dato2"><input name="borrar[]" type="checkbox" value="<?php echo $row_modificaciones['id_cm']; ?>" /></td>
      <td id="dato3"><a href="control_modif_p_edit.php?id_cm=<?php echo $row_modificaciones['id_cm']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_modificaciones['id_cm']; ?></a></td>
      <td id="dato3"><a href="verificacion_packing_vista.php?id_verif_p=<?php echo $row_modificaciones['id_verif_cm']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_modificaciones['id_verif_cm']; ?></a></td>
      <td id="dato2"><?php $id_ref=$row_modificaciones['id_ref_cm'];
	  $sql2="SELECT * FROM Tbl_referencia WHERE id_ref='$id_ref'";
	  $result2=mysql_query($sql2);
	  $num2=mysql_num_rows($result2);
	  if ($num2 >= '1')
	  {	$cod_ref=mysql_result($result2,0,'cod_ref');
	    $version_ref=mysql_result($result2,0,'version_ref');
	  } ?><a href="referencia_packing_vista.php?id_ref=<?php echo $row_modificaciones['id_ref_cm']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $cod_ref; ?></a></td>
      <td id="dato2"><a href="referencia_packing_vista.php?id_ref=<?php echo $row_modificaciones['id_ref_cm']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $version_ref; ?></a></td>
      <td id="dato2"><a href="control_modif_p_edit.php?id_cm=<?php echo $row_modificaciones['id_cm']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_modificaciones['fecha_cm']; ?></a></td>
      <td id="dato1"><a href="control_modif_p_edit.php?id_cm=<?php echo $row_modificaciones['id_cm']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php $cad=htmlentities ($row_modificaciones['responsable_cm']);echo $cad; ?></a></td>
      <td id="dato2"><a href="control_modif_p_edit.php?id_cm=<?php echo $row_modificaciones['id_cm']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_modificaciones['fecha_edit_cm']; ?></a></td>
      <td id="dato2"><a href="control_modif_p_edit.php?id_cm=<?php echo $row_modificaciones['id_cm']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php $cad2=htmlentities ($row_modificaciones['responsable_edit_cm']);echo $cad2; ?></a></td>
      </tr>
    <?php } while ($row_modificaciones = mysql_fetch_assoc($modificaciones)); ?>
</table>
<table border="0" align="center" id="tabla1">
  <tr>
    <td width="23%" align="center" id="dato2"><?php if ($pageNum_modificaciones > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_modificaciones=%d%s", $currentPage, 0, $queryString_modificaciones); ?>">Primero</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="31%" align="center" id="dato2"><?php if ($pageNum_modificaciones > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_modificaciones=%d%s", $currentPage, max(0, $pageNum_modificaciones - 1), $queryString_modificaciones); ?>">Anterior</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="23%" align="center" id="dato2"><?php if ($pageNum_modificaciones < $totalPages_modificaciones) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_modificaciones=%d%s", $currentPage, min($totalPages_modificaciones, $pageNum_modificaciones + 1), $queryString_modificaciones); ?>">Siguiente</a>
          <?php } // Show if not last page ?>
    </td>
    <td width="23%" align="center" id="dato2"><?php if ($pageNum_modificaciones < $totalPages_modificaciones) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_modificaciones=%d%s", $currentPage, $totalPages_modificaciones, $queryString_modificaciones); ?>">&Uacute;ltimo</a>
          <?php } // Show if not last page ?>
    </td>
  </tr>
</table>
</form>
 <?php echo $conexion->header('footer'); ?>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($control_modificaciones);

mysql_free_result($verificacion);

mysql_free_result($referencia);

mysql_free_result($anual);

mysql_free_result($modificaciones);
?>
