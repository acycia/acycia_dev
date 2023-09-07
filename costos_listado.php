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
//LLAMADO A FUNCIONES
include('funciones/funciones_php.php');//distintas funciones
//FIN	
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

$maxRows_costos = 20;
$pageNum_costos = 0;
if (isset($_GET['pageNum_costos'])) {
  $pageNum_costos = $_GET['pageNum_costos'];
}
$startRow_costos = $pageNum_costos * $maxRows_costos;

mysql_select_db($database_conexion1, $conexion1);
$query_costos = "SELECT * FROM Tbl_reg_produccion,Tbl_orden_produccion WHERE Tbl_reg_produccion.id_op_rp=Tbl_orden_produccion.id_op AND Tbl_orden_produccion.b_estado_op > '0' AND Tbl_orden_produccion.b_borrado_op='0' 
GROUP BY Tbl_reg_produccion.id_op_rp DESC";
$query_limit_costos = sprintf("%s LIMIT %d, %d", $query_costos, $startRow_costos, $maxRows_costos);
$costos = mysql_query($query_limit_costos, $conexion1) or die(mysql_error());
$row_costos = mysql_fetch_assoc($costos);

if (isset($_GET['totalRows_costos'])) {
  $totalRows_costos = $_GET['totalRows_costos'];
} else {
  $all_costos = mysql_query($query_costos);
  $totalRows_costos = mysql_num_rows($all_costos);
}
$totalPages_costos = ceil($totalRows_costos/$maxRows_costos)-1;

mysql_select_db($database_conexion1, $conexion1);
$query_lista_op = "SELECT id_op FROM Tbl_orden_produccion WHERE b_estado_op > 0 ORDER BY Tbl_orden_produccion.id_op DESC";
$lista_op = mysql_query($query_lista_op, $conexion1) or die(mysql_error());
$row_lista_op = mysql_fetch_assoc($lista_op);
$totalRows_lista_op = mysql_num_rows($lista_op);

mysql_select_db($database_conexion1, $conexion1);
$query_ref_op = "SELECT id_ref, cod_ref FROM Tbl_referencia order by id_ref desc";
$ref_op = mysql_query($query_ref_op, $conexion1) or die(mysql_error());
$row_ref_op = mysql_fetch_assoc($ref_op);
$totalRows_ref_op = mysql_num_rows($ref_op);

$queryString_costos = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_costos") == false && 
        stristr($param, "totalRows_costos") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_costos = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_costos = sprintf("&totalRows_costos=%d%s", $totalRows_costos, $queryString_costos);
 
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0014)about:internet -->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/listado.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
</head>
<body><div align="center">

<table align="center" id="tabla"><tr align="center"><td>
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
<li><a href="menu.php">MENU PRINCIPAL</a></li>
<li><a href="costos_producto_terminado.php" target="_top">COSTOS</a></li>
</ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1">
<form action="costos_listado2.php" method="get" name="form1">
<table id="tabla1">
<tr>
<td nowrap="nowrap" id="codigo" width="25%">CODIGO : R1 - F03</td>
<td colspan="3" nowrap="nowrap" id="titulo2">LISTADO DE COSTOS</td>
<td nowrap="nowrap" id="codigo" width="25%">VERSION : 2</td>
</tr>
<tr>
  <td colspan="5" id="fuente2"><select name="op" id="op" onchange="ListadoProduccion('costos_listado2.php',this.name,this.value)">
    <option value="0">O.P.</option>
    <?php
do {  
?>
    <option value="<?php echo $row_lista_op['id_op']?>"><?php echo $row_lista_op['id_op']?></option>
    <?php
} while ($row_lista_op = mysql_fetch_assoc($lista_op));
  $rows = mysql_num_rows($lista_op);
  if($rows > 0) {
      mysql_data_seek($lista_op, 0);
	  $row_lista_op = mysql_fetch_assoc($lista_op);
  }
?>
  </select>
    <select name="id_ref" id="id_ref" onchange="ListadoProduccion('costos_listado2.php',this.name,this.value)">
      <option value="0">REF</option>
      <?php
do {  
?>
      <option value="<?php echo $row_ref_op['cod_ref']?>">
        <?php  echo $row_ref_op['cod_ref']?>
        </option>
      <?php
} while ($row_ref_op = mysql_fetch_assoc($ref_op));
  $rows = mysql_num_rows($ref_op);
  if($rows > 0) {
      mysql_data_seek($ref_op, 0);
	  $row_ref_op = mysql_fetch_assoc($ref_op);
  }
?>
    </select></td>
  </tr>
  <tr>
    <td colspan="5" id="dato3"><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR" title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
  </tr>  
  <tr id="tr1">
    <td nowrap="nowrap" id="titulo4">ORDEN P.</td>
    <td width="50%" nowrap="nowrap"id="titulo4">CLIENTE</td>
    <td width="50%" nowrap="nowrap"id="titulo4">REF</td>
    <td width="25%" nowrap="nowrap"id="titulo4">FECHA FINAL</td>
    <td nowrap="nowrap"id="titulo4">ESTADO</td>
  </tr>
  <?php do { ?>
  <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
      <td id="dato2"><a href="costos_op_add.php?id_op=<?php echo $row_costos['id_op']; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_costos['id_op'];?></a></td>
      <td id="dato1"><a href="costos_op_add.php?id_op=<?php echo $row_costos['id_op']; ?>" target="new" style="text-decoration:none; color:#000000">
        <?php 
		$clien=$row_costos['int_cliente_op']; 	
		$sqlchm="SELECT nombre_c FROM cliente WHERE cliente.id_c='$clien'";
		$resultchm=mysql_query($sqlchm); $numchm=mysql_num_rows($resultchm);
		if ($numchm>='1') { 
		$cliente=mysql_result($resultchm,0,'nombre_c'); 	
		echo $cliente; }
		?>
      </a></td>
      <td id="dato2"><a href="costos_op_add.php?id_op=<?php echo $row_costos['id_op']; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_costos['int_cod_ref_op'];?></a></td>
      <td id="dato2"><a href="costos_op_add.php?id_op=<?php echo $row_costos['id_op']; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo quitarHora($row_costos['fecha_fin_rp']);?></a></td>
      <td id="dato1"><a href="costos_op_add.php?id_op=<?php echo $row_costos['id_op']; ?>" target="new" style="text-decoration:none; color:#000000">
        <?php $estados=$row_costos['b_estado_op']; 
						switch ($estados){
							case 0: echo "INGRESADA";
							break;
							case 1: echo "EXTRUIDA";
							break;
							case 2: echo "IMPRESA";
							break;
							case 3: echo "REFILADA";
							break;
							case 4: echo "SELLADA";
							break;
							case 5: echo "FINALIZADA";
							break;							
							}
 						?>
      </a></td>
    </tr>
    <?php } while ($row_costos = mysql_fetch_assoc($costos)); ?>
</table>
<table id="tabla1">
  <tr>
    <td id="dato1" width="25%"><?php if ($pageNum_costos > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_costos=%d%s", $currentPage, 0, $queryString_costos); ?>">Primero</a>
          <?php } // Show if not first page ?>
    </td>
    <td id="dato1" width="25%"><?php if ($pageNum_costos > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_costos=%d%s", $currentPage, max(0, $pageNum_costos - 1), $queryString_costos); ?>">Anterior</a>
          <?php } // Show if not first page ?>
    </td>
    <td id="dato1" width="25%"><?php if ($pageNum_costos < $totalPages_costos) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_costos=%d%s", $currentPage, min($totalPages_costos, $pageNum_costos + 1), $queryString_costos); ?>">Siguiente</a>
          <?php } // Show if not last page ?>
    </td>
    <td id="dato1" width="25%"><?php if ($pageNum_costos < $totalPages_costos) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_costos=%d%s", $currentPage, $totalPages_costos, $queryString_costos); ?>">&Uacute;ltimo</a>
          <?php } // Show if not last page ?>
    </td>
  </tr>
</table>
</form>
</td>
  </tr>
</table>
</div>
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

mysql_free_result($costos);

?>