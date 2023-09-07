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

$maxRows_ordenes_compra = 20;
$pageNum_ordenes_compra = 0;
if (isset($_GET['pageNum_ordenes_compra'])) {
  $pageNum_ordenes_compra = $_GET['pageNum_ordenes_compra'];
}
$startRow_ordenes_compra = $pageNum_ordenes_compra * $maxRows_ordenes_compra;

mysql_select_db($database_conexion1, $conexion1);
$query_ordenes_compra = "SELECT * FROM Tbl_orden_compra  WHERE Tbl_orden_compra.b_borrado_oc='1' GROUP BY str_numero_oc ORDER BY b_estado_oc,  fecha_ingreso_oc DESC";
$query_limit_ordenes_compra = sprintf("%s LIMIT %d, %d", $query_ordenes_compra, $startRow_ordenes_compra, $maxRows_ordenes_compra);
$ordenes_compra = mysql_query($query_limit_ordenes_compra, $conexion1) or die(mysql_error());
$row_ordenes_compra = mysql_fetch_assoc($ordenes_compra);

if (isset($_GET['totalRows_ordenes_compra'])) {
  $totalRows_ordenes_compra = $_GET['totalRows_ordenes_compra'];
} else {
  $all_ordenes_compra = mysql_query($query_ordenes_compra);
  $totalRows_ordenes_compra = mysql_num_rows($all_ordenes_compra);
}
$totalPages_ordenes_compra = ceil($totalRows_ordenes_compra/$maxRows_ordenes_compra)-1;

mysql_select_db($database_conexion1, $conexion1);
$query_lista = "SELECT * FROM Tbl_orden_compra WHERE b_borrado_oc='0' ORDER BY str_numero_oc DESC";
$lista = mysql_query($query_lista, $conexion1) or die(mysql_error());
$row_lista = mysql_fetch_assoc($lista);
$totalRows_lista = mysql_num_rows($lista);

mysql_select_db($database_conexion1, $conexion1);
$query_proveedores = "SELECT * FROM cliente ORDER BY nombre_c";
$proveedores = mysql_query($query_proveedores, $conexion1) or die(mysql_error());
$row_proveedores = mysql_fetch_assoc($proveedores);
$totalRows_proveedores = mysql_num_rows($proveedores);

mysql_select_db($database_conexion1, $conexion1);
$query_ano = "SELECT * FROM cliente ORDER BY nit_c DESC";
$ano = mysql_query($query_ano, $conexion1) or die(mysql_error());
$row_ano = mysql_fetch_assoc($ano);
$totalRows_ano = mysql_num_rows($ano);

$queryString_ordenes_compra = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_ordenes_compra") == false && 
        stristr($param, "totalRows_ordenes_compra") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_ordenes_compra = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_ordenes_compra = sprintf("&totalRows_ordenes_compra=%d%s", $totalRows_ordenes_compra, $queryString_ordenes_compra);

 ?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/listado.js"></script>
</head>
<body onload = "JavaScript: AutoRefresh (60000);"><div align="center">
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
<li><a href="orden_compra_cl_reasig_oc.php">REASIGNAR OC</a></li>
</ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1">
<form action="orden_compra1_cl.php" method="get" name="consulta">
<table id="tabla3">
<tr>
<td colspan="2" id="titulo2">ORDENES DE COMPRA INACTIVAS</td>
</tr>
<tr>
  <td colspan="2" id="fuente2"><select name="str_numero_oc" id="str_numero_oc" style="width:100px">
    <option value="0">O.C.</option>
    <?php
do {  
?>
    <option value="<?php echo $row_lista['str_numero_oc']?>"><?php echo $row_lista['str_numero_oc']?></option>
    <?php
} while ($row_lista = mysql_fetch_assoc($lista));
  $rows = mysql_num_rows($lista);
  if($rows > 0) {
      mysql_data_seek($lista, 0);
	  $row_lista = mysql_fetch_assoc($lista);
  }
?>
  </select>
    <select name="id_c" id="id_c" style="width:100px">
      <option value="0">CLIENTE</option>
      <?php
do {  
?>
      <option value="<?php echo $row_proveedores['id_c']?>">
        <?php $cad =($row_proveedores['nombre_c']); echo $cad;?>
        </option>
      <?php
} while ($row_proveedores = mysql_fetch_assoc($proveedores));
  $rows = mysql_num_rows($proveedores);
  if($rows > 0) {
      mysql_data_seek($proveedores, 0);
	  $row_proveedores = mysql_fetch_assoc($proveedores);
  }
?>
    </select>
    <select name="nit_c" id="nit_c" style="width:100px">
      <option value="0">NIT</option>
      <?php
do {  
?>
      <option value="<?php echo $row_ano['nit_c']?>"><?php echo $row_ano['nit_c']?></option>
      <?php
} while ($row_ano = mysql_fetch_assoc($ano));
  $rows = mysql_num_rows($ano);
  if($rows > 0) {
      mysql_data_seek($ano, 0);
	  $row_ano = mysql_fetch_assoc($ano);
  }
?>
    </select>
    <select name="estado_oc" id="estado_oc" style="width:100px">
      <option value="0">Estado O.C</option>
      <option value="1">INGRESADA</option>
      <option value="2">PROGRAMADA</option>
      <option value="3">REMISIONADA</option>
      <option value="4">FAC.PARCIAL</option>
      <option value="5">FAC.TOTAL</option>
    </select>
    <select name="pendiente" id="pendiente" style="width:100px">
      <option value="0">Seleccione</option>
      <option value="=">COMPLETOS</option>
      <option value=">">PENDIENTES</option>
    </select>
    <input type="submit" name="Submit" value="FILTRO" onClick="if(consulta.str_numero_oc.value=='0' && consulta.id_c.value=='0' && consulta.nit_c.value=='0' && consulta.estado_oc.value=='0' && consulta.pendiente.value=='0') { alert('DEBE SELECCIONAR UNA OPCION'); }"/></td>
  </tr>
<tr>
  <td id="dato1"><p><img src="images/falta.gif" alt="INGRESADA x O.C."title="INGRESADA O.C." border="0" style="cursor:hand;"/> ingresada</p>
    <p><img src="images/f.gif" alt="FACTURADA O.C." title="FACTURADA O.C." border="0" style="cursor:hand;"/> facturado total</p>
    <p><img src="images/fr.gif" alt="FACTURADA PARCIAL"title="FACTURADA PARCIAL" border="0" style="cursor:hand;"/> factura parcial</p>
    <p><img src="images/r.gif" alt="REMISION O.C."title="REMISION O.C." border="0" style="cursor:hand;"/> remisionada    </p></td>
  <td id="dato1"><p><img src="images/p.gif" alt="PROGRAMADA O.C."title="PROGRAMADA O.C." border="0" style="cursor:hand;"/> programada</p>
    <p><img src="images/pa.gif" alt="PROGRAMADA O.C."title="PROGRAMADA O.C." border="0" style="cursor:hand;"/> En produccion</p>
    <p><img src="images/falta3.gif" alt="PENDIENTES" width="20" height="18" style="cursor:hand;"title="PENDIENTES" border="0"/> cantidades pendientes por despachar</p>
    <p><img src="images/cumple.gif" alt="OK" width="20" height="18" style="cursor:hand;"title="OK" border="0"/> cantidades despachadas en su totalidad</p></td>
 
</tr>

</table>
</form>
<form action="delete_listado.php" method="get" name="seleccion">
<table id="tabla1">
  <tr>
    <td colspan="2" id="dato1"><input name="borrado" type="hidden" id="borrado" value="22" />
    <input name="Input" type="submit" value="Delete"/>  </td>
    <td colspan="3"><?php $id=$_GET['id']; 
   if($id == '2') { ?> 
      <div id="numero1"> <?php echo "NO SE PUEDE ELIMINAR PORQUE TIENE REMISIONES CREADAS O ESTA EN PRODUCCION"; ?> </div>
      <?php }
  if($id == '1') { ?> 
      <div id="acceso1"> <?php echo "ELIMINACION COMPLETA"; ?> </div>
      <?php }
  if($id == '0') { ?>
      <div id="numero1"> <?php echo "SELECCIONE PARA ELIMINAR"; ?> </div>      <?php }?></td>
    <td colspan="3" id="dato2"><a href="orden_compra_cl_add.php"><img src="images/mas.gif" alt="ADD ORDEN DE COMPRA" title="ADD ORDEN DE COMPRA" border="0" style="cursor:hand;"/></a><a href="orden_compra_cl.php"><img src="images/ciclo1.gif" alt="RESTAURAR" border="0" style="cursor:hand;"/></a><a href="insumos.php"><!--<img src="images/i.gif" style="cursor:hand;" alt="INSUMOS" border="0"/>--></a></td>
    </tr> 
     
  <tr id="tr1">
    <td id="fuente2"><input name="chulo1" type="checkbox" onClick="if(seleccion.chulo1.checked) { seleccionar_todo() } else{ deseleccionar_todo() } "/></td>
    <td id="titulo4">N&deg;</td>
    <td id="titulo4">INGRESO </td>
    <td id="titulo4">CLIENTE</td>
    <td id="titulo4">RESPONSABLE</td>
    <td id="titulo4">PENDIENTE</td>
    <td id="titulo4"><a href="verificaciones_criticos.php"><!--<img src="images/v.gif" alt="VERIFICACIONES (CRITICOS)" border="0" style="cursor:hand;"/>--></a><a href="orden_compra_cl_vista.php?str_numero_oc=<?php echo $row_ordenes_compra['str_numero_oc']; ?>&id_oc=<?php echo $row_ordenes_compra['id_c_oc'];?>"></a>
ESTADO</td>
  </tr>
  <?php do { ?>
    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
      <td id="dato2"><input name="borrar[]" type="checkbox" id="borrar[]" value="<?php echo $row_ordenes_compra['id_pedido']; ?>" /></td>
      <td id="dato1" nowrap><a href="orden_compra_cl_vista.php?str_numero_oc=<?php echo $row_ordenes_compra['str_numero_oc'];?>&id_oc=<?php echo $row_ordenes_compra['id_c_oc'];?>" target="_top" style="text-decoration:none; color:#000000"><strong><?php echo $row_ordenes_compra['str_numero_oc']; ?></strong></a></td>
      <td id="dato1" nowrap><a href="orden_compra_cl_vista.php?str_numero_oc=<?php echo $row_ordenes_compra['str_numero_oc']; ?>&id_oc=<?php echo $row_ordenes_compra['id_c_oc'];?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_ordenes_compra['fecha_ingreso_oc']; ?></a></td>
      <td id="dato2"><a href="orden_compra_cl_vista.php?str_numero_oc=<?php echo $row_ordenes_compra['str_numero_oc']; ?>&id_oc=<?php echo $row_ordenes_compra['id_c_oc'];?>" target="_top" style="text-decoration:none; color:#000000">
        <?php 
	$nit_c=$row_ordenes_compra['str_nit_oc'];
	$sqln="SELECT * FROM cliente WHERE nit_c='$nit_c'"; 
	$resultn=mysql_query($sqln); 
	$numn=mysql_num_rows($resultn); 
	if($numn >= '1') 
	{ $nit_cliente_c=mysql_result($resultn,0,'nombre_c'); $ca =($nit_cliente_c); echo $ca; }
	else { echo "";	} ?>
      </a></td>
      <td id="dato1"><a href="orden_compra_cl_vista.php?str_numero_oc=<?php echo $row_ordenes_compra['str_numero_oc']; ?>&id_oc=<?php echo $row_ordenes_compra['id_c_oc'];?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_ordenes_compra['str_responsable_oc']; ?></a></td>
      <td id="dato2"><a href="orden_compra_cl_edit.php?str_numero_oc=<?php echo $row_ordenes_compra['str_numero_oc']; ?>&id_oc=<?php echo $row_ordenes_compra['id_c_oc'];?>">
        <?php 
	$id_pedido=$row_ordenes_compra['id_pedido'];
	$sqlpend="SELECT SUM(int_cantidad_rest_io) AS restante FROM Tbl_items_ordenc WHERE id_pedido_io='$id_pedido'"; 
	$resultpend=mysql_query($sqlpend);
	$numpend=mysql_num_rows($resultpend); 
	if($numpend >= '1'){
	$restante = mysql_result($resultpend, 0, 'restante'); 
	} 
	if( $restante > 0.00){?>
        <img src="images/falta3.gif" alt="CANTIDAD PENDIENTES" width="20" height="18" style="cursor:hand;"title="CANTIDAD PENDIENTES" border="0"/>
        <?php }else if($restante == ''){?><em>sin items</em><?php } else {?>
        <img src="images/cumple.gif" alt="OK" width="20" height="18" style="cursor:hand;"title="OK" border="0"/>
        <?php } ?>
      </a></td>
      <td id="dato2"><a href="orden_compra_cl_edit.php?str_numero_oc=<?php echo $row_ordenes_compra['str_numero_oc']; ?>&id_oc=<?php echo $row_ordenes_compra['id_c_oc'];?>"><?php 	
	$estado=$row_ordenes_compra['b_estado_oc'];
	if($estado=='5'){	
	 ?><img src="images/f.gif" alt="FACTURADA O.C." title="FACTURADA O.C." border="0" style="cursor:hand;"/><?php }
    if($estado=='4'){ ?><img src="images/fr.gif" alt="FACTURADA PARCIAL"title="FACTURADA PARCIAL" border="0" style="cursor:hand;"/><?php }
	if($estado=='3'){ ?><img src="images/r.gif" alt="REMISION O.C."title="REMISION O.C." border="0" style="cursor:hand;"/><?php }
$id_oc=$row_ordenes_compra['str_numero_oc'];
$sqlmp="SELECT Tbl_orden_produccion.int_cod_ref_op AS existe_op 
FROM Tbl_items_ordenc,Tbl_orden_produccion WHERE Tbl_items_ordenc.str_numero_io='$id_oc' AND Tbl_items_ordenc.str_numero_io=Tbl_orden_produccion.str_numero_oc_op 
AND Tbl_items_ordenc.int_cod_ref_io=Tbl_orden_produccion.int_cod_ref_op AND Tbl_orden_produccion.b_borrado_op='0'";
					$resultmp= mysql_query($sqlmp);
					$nump = mysql_num_rows($resultmp);
					if($nump >='1')
					{ 
					$existe_op = mysql_result($resultmp,0,'existe_op');
 					}else {$existe_op="0";} 
 	if($estado=='2' && $existe_op =='0'){ ?><img src="images/p.gif" alt="PROGRAMADA O.C."title="PROGRAMADA O.C." border="0" style="cursor:hand;"/><?php }else 
	if($estado=='2' && $existe_op > '0'){ ?><img src="images/pa.gif" alt="EN PRODUCCION"title="EN PRODUCCION" border="0" style="cursor:hand;"/><?php }
	if($estado=='1'){ ?><img src="images/falta.gif" alt="INGRESADA x O.C."title="INGRESADA O.C." border="0" style="cursor:hand;"/><?php }
	 ?></a></td>
    </tr>
    <?php } while ($row_ordenes_compra = mysql_fetch_assoc($ordenes_compra)); ?>
</table>
</form>
<table border="0" width="50%" align="center">
  <tr>
    <td width="23%" id="dato2"><?php if ($pageNum_ordenes_compra > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_ordenes_compra=%d%s", $currentPage, 0, $queryString_ordenes_compra); ?>">Primero</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="31%" id="dato2"><?php if ($pageNum_ordenes_compra > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_ordenes_compra=%d%s", $currentPage, max(0, $pageNum_ordenes_compra - 1), $queryString_ordenes_compra); ?>">Anterior</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="23%" id="dato2"><?php if ($pageNum_ordenes_compra < $totalPages_ordenes_compra) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_ordenes_compra=%d%s", $currentPage, min($totalPages_ordenes_compra, $pageNum_ordenes_compra + 1), $queryString_ordenes_compra); ?>">Siguiente</a>
          <?php } // Show if not last page ?>
    </td>
    <td width="23%" id="dato2"><?php if ($pageNum_ordenes_compra < $totalPages_ordenes_compra) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_ordenes_compra=%d%s", $currentPage, $totalPages_ordenes_compra, $queryString_ordenes_compra); ?>">&Uacute;ltimo</a>
          <?php } // Show if not last page ?>
    </td>
  </tr>
</table></td>
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
mysql_free_result($usuario);mysql_close($conexion1);

mysql_free_result($ordenes_compra);

mysql_free_result($lista);

mysql_free_result($proveedores);

mysql_free_result($ano);
?>