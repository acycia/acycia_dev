<?php
   require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
   require (ROOT_BBDD); 
?>
<?php require_once('Connections/conexion1.php'); ?>
<?php
//initialize the session
/*if (!isset($_SESSION)) {
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
}*/
?>
<?php
/*if (!isset($_SESSION)) {
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
}*/
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
$query_lista = "SELECT int_op_n FROM Tbl_numeracion WHERE fecha_ingreso_n <= '2016-12-31' AND existeTiq_n='1' AND b_borrado_n='0' ORDER BY int_op_n DESC";
$lista = mysql_query($query_lista, $conexion1) or die(mysql_error());
$row_lista = mysql_fetch_assoc($lista);
$totalRows_lista = mysql_num_rows($lista);

$maxRows_numeracion = 20;
$pageNum_numeracion = 0;
if (isset($_GET['pageNum_numeracion'])) {
  $pageNum_numeracion = $_GET['pageNum_numeracion'];
}
$startRow_numeracion = $pageNum_numeracion * $maxRows_numeracion;
mysql_select_db($database_conexion1, $conexion1);
$id_op = $_GET['id_op'];
//Filtra todos vacios
if($id_op == '0' )
{
$query_numeracion = "SELECT * FROM Tbl_numeracion WHERE fecha_ingreso_n <= '2016-12-31' AND existeTiq_n='1' AND b_borrado_n='0' ORDER BY int_op_n DESC";
}
//Filtra todos llenos
if($id_op != '0' )
{
$query_numeracion = "SELECT * FROM Tbl_numeracion WHERE fecha_ingreso_n <= '2016-12-31' AND int_op_n=$id_op  ORDER BY  int_op_n DESC";
}
$query_limit_numeracion = sprintf("%s LIMIT %d, %d", $query_numeracion, $startRow_numeracion, $maxRows_numeracion);
$numeracion = mysql_query($query_limit_numeracion, $conexion1) or die(mysql_error());
$row_numeracion = mysql_fetch_assoc($numeracion);

if (isset($_GET['totalRows_numeracion'])) {
  $totalRows_numeracion = $_GET['totalRows_numeracion'];
} else {
  $all_numeracion = mysql_query($query_numeracion);
  $totalRows_numeracion = mysql_num_rows($all_numeracion);
}
$totalPages_numeracion = ceil($totalRows_numeracion/$maxRows_numeracion)-1;

$queryString_numeracion = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_numeracion") == false && 
        stristr($param, "totalRows_numeracion") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_numeracion = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_numeracion = sprintf("&totalRows_numeracion=%d%s", $totalRows_numeracion, $queryString_numeracion);



?><html>
<head>
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
<link rel="stylesheet" type="text/css" href="css/general.css"/>

<!-- css Bootstrap-->
<link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

</head>
<body onload = "JavaScript: AutoRefresh (10000);">
<?php echo $conexion->header('listas'); ?>
<table align="center" class="table table-bordered table-sm">
  <tr align="center">
    <td>
	<form action="sellado_numeracion_listado2_backup.php" method="get" name="consulta">
	<table class="table table-bordered table-sm">
	  <tr>
	  <td id="subtitulo">LISTADO DE O.P SELLADO</td>
	  </tr>
	  <tr>
	  <td id="fuente2">
      <select name="id_op" id="id_op">
        <option value="0">O.P</option>
        <?php
do {  
?>
        <option value="<?php echo $row_lista['int_op_n']?>"><?php echo $row_lista['int_op_n']?></option>
        <?php
} while ($row_lista = mysql_fetch_assoc($lista));
  $rows = mysql_num_rows($lista);
  if($rows > 0) {
      mysql_data_seek($lista, 0);
	  $row_lista = mysql_fetch_assoc($lista);
  }
?>
      </select>
      <input type="submit" name="Submit" value="FILTRO" onClick="if(consulta.id_op.value=='0') { alert('DEBE SELECCIONAR UNA OPCION'); }"/>      </td>
  </tr>
</table>
</form>
<form action="delete_listado.php" method="get" name="seleccion">
<table class="table table-bordered table-sm">
  <tr>
    <td id="dato1">&nbsp; </td>
    <td colspan="4">&nbsp; </td>
    <td colspan="5" id="dato3"><a href="sellado_control_numeracion_add.php"><img src="images/mas.gif" alt="CREAR TIQUETES A O.P" title="CREAR TIQUETES A O.P" border="0" style="cursor:hand;"/></a>
    <?php if($row_usuario['tipo_usuario']=='1') { ?><a href="despacho_direccion.php"><img src="images/c.gif" alt="VERIFICAR PAQUETES X CAJA" title="VERIFICAR PAQUETES X CAJA" border="0" style="cursor:hand;"/></a><?php }?>
    <a href="numeracion_listado.php"><img src="images/f.gif" alt="O.P SIN TIQUETES" title="O.P SIN TIQUETES" border="0" style="cursor:hand;"/></a>
    <a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
  </tr>
  <tr>
    <td id="dato4">&nbsp;</td>
    <td colspan="4">&nbsp;</td>
    <td colspan="5" id="dato5">&nbsp;</td>
  </tr>  
  <tr id="tr1">
    <td nowrap="nowrap"id="titulo4">N&deg; O.P </td> 
<!--    <td nowrap="nowrap"id="titulo4">Paquete</td>-->     
    <td nowrap="nowrap"id="titulo4">Caja</td>              
    <td nowrap="nowrap"id="titulo4">Und x Paq. </td>   
    <td nowrap="nowrap"id="titulo4">Und x Caja </td> 
    <td nowrap="nowrap"id="titulo4">FECHA</td>                      
    <td nowrap="nowrap"id="titulo4">CLIENTE</td>                
  </tr>
  <?php do { ?>
    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
      <?php 
/*	$id_op=$row_numeracion['int_op_n'];
	$sqlp="SELECT int_op_tn,int_paquete_tn,int_caja_tn FROM Tbl_tiquete_numeracion WHERE int_op_tn='$id_op' ORDER BY  int_caja_tn DESC"; 
	$resultp=mysql_query($sqlp); 
	$nump=mysql_num_rows($resultp); 
	if($nump >= '1') 
	{ $paquete_tn=mysql_result($resultp,0,'int_paquete_tn');  $paquete_tn;
	$caja_tn=mysql_result($resultp,0,'int_caja_tn'); $caja_tn; }
	else { echo "";	}*/ ?>
      <td id="dato2"><strong><?php echo $row_numeracion['int_op_n']; ?></strong></td>
<!--      <td nowrap="nowrap" id="dato2"><?php echo $paquete_tn; ?></td> -->     
      <td id="dato2"><?php echo  $row_numeracion['int_caja_n']; ?></td>           
      <td id="dato2"><?php echo $row_numeracion['int_undxpaq_n']; ?></td>
      <td id="dato2"><?php echo $row_numeracion['int_undxcaja_n']; ?></td>
      <td id="dato2" nowrap><?php echo $row_numeracion['fecha_ingreso_n']; ?></td> 
      <td nowrap="nowrap" id="dato2">
        <?php 
	$id_op=$row_numeracion['int_op_n'];
	$sqln="SELECT cliente.nombre_c FROM Tbl_orden_produccion,cliente WHERE Tbl_orden_produccion.id_op='$id_op' AND Tbl_orden_produccion.str_nit_op=cliente.nit_c"; 
	$resultn=mysql_query($sqln); 
	$numn=mysql_num_rows($resultn); 
	if($numn >= '1') 
	{ $nombre_cliente_c=mysql_result($resultn,0,'nombre_c'); $ca = htmlentities ($nombre_cliente_c); echo $ca; }
	else { echo "";	} ?>
    </td>    
    </tr>
    <?php } while ($row_numeracion = mysql_fetch_assoc($numeracion)); ?>
</table>
</form>
<table border="0" width="50%" align="center">
  <tr>
    <td width="23%" align="center" id="dato2"><?php if ($pageNum_numeracion > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_numeracion=%d%s", $currentPage, 0, $queryString_numeracion); ?>">Primero</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="31%" align="center" id="dato2"><?php if ($pageNum_numeracion > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_numeracion=%d%s", $currentPage, max(0, $pageNum_numeracion - 1), $queryString_numeracion); ?>">Anterior</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="23%" align="center" id="dato2"><?php if ($pageNum_numeracion < $totalPages_numeracion) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_numeracion=%d%s", $currentPage, min($totalPages_numeracion, $pageNum_numeracion + 1), $queryString_numeracion); ?>">Siguiente</a>
          <?php } // Show if not last page ?>
    </td>
    <td width="23%" align="center" id="dato2"><?php if ($pageNum_numeracion < $totalPages_numeracion) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_numeracion=%d%s", $currentPage, $totalPages_numeracion, $queryString_numeracion); ?>">&Uacute;ltimo</a>
          <?php } // Show if not last page ?>
    </td>
  </tr>
</table>
</td>
  </tr>
</table>
<?php echo $conexion->header('footer'); ?>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($lista);

mysql_free_result($numeracion);

?>
