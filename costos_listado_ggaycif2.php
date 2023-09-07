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

$maxRows_costos = 20;
$pageNum_costos = 0;
if (isset($_GET['pageNum_costos'])) {
  $pageNum_costos = $_GET['pageNum_costos'];
}
$startRow_costos = $pageNum_costos * $maxRows_costos;

mysql_select_db($database_conexion1, $conexion1);
$ano = $_GET['fecha'];
$mensual = $_GET['mensual'];
//Filtra fecha lleno
if($fecha != '0'&& $mensual != '0')
{
$query_costos = sprintf("SELECT DISTINCT FechaInicio, FechaFin, ResponsableGGA, TotalGGA, UnidadesProducidas, CostoGGAxUn, TotalGGA_parcial, porc_parcial, UnidadesProducidas_parcial, CostoGGAxUn_parcial FROM TblDetalleGGAProd WHERE YEAR(FechaInicio) = $ano AND MONTH(FechaInicio) = $mensual AND YEAR(FechaFin) = $ano AND MONTH(FechaFin) = $mensual GROUP BY FechaInicio DESC");
}
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
$query_mensual = "SELECT * FROM mensual ORDER BY id_mensual DESC";
$mensual = mysql_query($query_mensual, $conexion1) or die(mysql_error());
$row_mensual = mysql_fetch_assoc($mensual);
$totalRows_mensual = mysql_num_rows($mensual);

mysql_select_db($database_conexion1, $conexion1);
$query_ano = "SELECT * FROM anual ORDER BY anual DESC";
$ano = mysql_query($query_ano, $conexion1) or die(mysql_error());
$row_ano = mysql_fetch_assoc($ano);
$totalRows_ano = mysql_num_rows($ano);

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

session_start();
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
<li><a href="costos_generales.php">COSTOS GENERALES</a></li>
</ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1">
<form action="costos_listado_gga2.php" method="get" name="form1">
<table id="tabla1">
<tr>
<td colspan="3" nowrap="nowrap" id="codigo" width="25%">CODIGO : R1 - F03</td>
<td colspan="3"nowrap="nowrap" id="titulo2" width="50%">LISTADO DE VALORES GGA Y CIF</td>
<td colspan="4"nowrap="nowrap" id="codigo" width="25%">VERSION : 2</td>
</tr>
<tr>
  <td colspan="10" id="fuente2"><select name="fecha" id="fecha">
    <option value="0"<?php if (!(strcmp("", $_GET['fecha']))) {echo "selected=\"selected\"";} ?>>ANUAL</option>
    <?php
do {  
?>
    <option value="<?php echo $row_ano['anual']?>"<?php if (!(strcmp($row_ano['anual'], $_GET['fecha']))) {echo "selected=\"selected\"";} ?>><?php echo $row_ano['anual']?></option>
    <?php
} while ($row_ano = mysql_fetch_assoc($ano));
  $rows = mysql_num_rows($ano);
  if($rows > 0) {
      mysql_data_seek($ano, 0);
	  $row_ano = mysql_fetch_assoc($ano);
  }
?>
  </select>
    <select name="mensual" id="mensual" onChange="if(form1.mensual.value) { consulta_costo_mensual(); }else { alert('Debe Seleccionar una Mes')}">
    <option value="0"<?php if (!(strcmp("", $_GET['mensual']))) {echo "selected=\"selected\"";} ?>>MENSUAL</option>
		<?php
    do {  
    ?>
        <option value="<?php echo $row_mensual['id_mensual']?>"<?php if (!(strcmp($row_mensual['id_mensual'], $_GET['mensual']))) {echo "selected=\"selected\"";} ?>><?php echo $row_mensual['mensual']?></option>
        <?php
    } while ($row_mensual = mysql_fetch_assoc($mensual));
      $rows = mysql_num_rows($mensual);
      if($rows > 0) {
          mysql_data_seek($mensual, 0);
          $row_mensual = mysql_fetch_assoc($mensual);
      }
    ?>
  </select></td>
  </tr>
  </table>
<!--</form>
<form action="delete_listado.php" method="get" name="seleccion">-->
<table id="tabla1">
  <tr>
    <td colspan="3" id="dato1"><!--<input name="borrado" type="hidden" id="borrado" value="41" />
      <input name="Input" type="submit" value="Delete"/>--></td>
    <td colspan="6" id="dato3"><?php $id=$_GET['id']; 
     if($id == '1') { ?> <div id="acceso1"> <?php echo "ELIMINACION COMPLETA"; ?> </div> <?php }
     if($id == '0') { ?><div id="numero1"> <?php echo "NO HA SELECCIONADO"; ?> </div> <?php }?>
     </td>
    <td colspan="2" id="dato3"><?php if($row_usuario['tipo_usuario'] != '11') { ?>
      <a href="costos_gga_add.php"><img src="images/mas.gif" alt="ADD COSTOS" title="ADD COSTOS" border="0" style="cursor:hand;"/></a><?php } ?>
      <a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR" title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
  </tr>  
  <tr id="tr1">
<!--    <td id="titulo1"><input name="chulo1" type="checkbox" onClick="if(seleccion.chulo1.checked) { seleccionar_todo() } else{ deseleccionar_todo() } "/>
-->    <td nowrap="nowrap"id="titulo4">FECHA INICIAL</td>
    <td nowrap="nowrap"id="titulo4">FECHA FINAL</td>
    <td nowrap="nowrap"id="titulo4">TOTAL GGA Y CIF</td>
    <td nowrap="nowrap"id="titulo4">%</td>
    <td nowrap="nowrap"id="titulo4">UNIDADES</td>
    <td nowrap="nowrap"id="titulo4">COSTO X UNIDAD</td>
    <td nowrap="nowrap"id="titulo4">250 CM&sup2;</td>
    <td nowrap="nowrap"id="titulo4">250/500 CM&sup2;</td>
    <td nowrap="nowrap"id="titulo4">501/1000 CM&sup2;</td>
    <td nowrap="nowrap"id="titulo4">1001/4000 CM&sup2;</td>
    <td nowrap="nowrap"id="titulo4">TOTAL GGA Y CIF PARCIAL</td>
    <td nowrap="nowrap"id="titulo4">%</td>
    <td nowrap="nowrap"id="titulo4">UNIDADES PARCIAL</td>
    <td nowrap="nowrap"id="titulo4">COSTO X UNIDAD PARCIAL</td>
    <td nowrap="nowrap"id="titulo4">250 CM&sup2;</td>
    <td nowrap="nowrap"id="titulo4">250/500 CM&sup2;</td>
    <td nowrap="nowrap"id="titulo4">501/1000 CM&sup2;</td>
    <td nowrap="nowrap"id="titulo4">1001/4000 CM&sup2;</td>
    <td nowrap="nowrap"id="titulo4">RESPONSABLE</td>
  </tr>
  <?php do { ?>
  <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
<!--      <td id="dato2"><input name="borrar[]" type="checkbox" value="<?php echo $row_costos['FechaInicio']; ?>" /></td>
-->      <td id="dato2"><a href="costos_gga_vista.php?FechaInicio=<?php echo $row_costos['FechaInicio']; ?>&FechaFin=<?php echo $row_costos['FechaFin']; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_costos['FechaInicio']; ?></a></td>
      <td id="dato2"><a href="costos_gga_vista.php?FechaInicio=<?php echo $row_costos['FechaInicio']; ?>&FechaFin=<?php echo $row_costos['FechaFin']; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_costos['FechaFin']; ?></a></td>
      <td id="dato2"><a href="costos_gga_vista.php?FechaInicio=<?php echo $row_costos['FechaInicio']; ?>&FechaFin=<?php echo $row_costos['FechaFin']; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_costos['TotalGGA']; ?></a></td>
      <td id="dato2"><a href="costos_gga_vista.php?FechaInicio=<?php echo $row_costos['FechaInicio']; ?>&FechaFin=<?php echo $row_costos['FechaFin']; ?>" target="new" style="text-decoration:none; color:#000000">100</a></td>
      <td id="dato2"><a href="costos_gga_vista.php?FechaInicio=<?php echo $row_costos['FechaInicio']; ?>&FechaFin=<?php echo $row_costos['FechaFin']; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_costos['UnidadesProducidas']; ?></a></td>
      <td id="dato2"><a href="costos_gga_vista.php?FechaInicio=<?php echo $row_costos['FechaInicio']; ?>&FechaFin=<?php echo $row_costos['FechaFin']; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_costos['CostoGGAxUn']; ?></a></td>
      <td id="dato2"><a href="costos_gga_vista.php?FechaInicio=<?php echo $row_costos['FechaInicio']; ?>&FechaFin=<?php echo $row_costos['FechaFin']; ?>" target="new" style="text-decoration:none; color:#000000">
      <?php 
	  $fecha1=$row_costos['FechaInicio'];
	  $fecha2=$row_costos['FechaFin'];
	  $sqlTotal="SELECT ValorCaracGGA FROM TblDetalleGGAProd WHERE FechaInicio='$fecha1' AND FechaFin='$fecha2' AND IDCaracGGA='13'"; 
	  $resultTotal=mysql_query($sqlTotal); 
	  $numTotal=mysql_num_rows($resultTotal); 
	  if($numTotal >= '1') 
	  { $V250=mysql_result($resultTotal,0,'ValorCaracGGA'); echo $V250; }if ($V250==NULL) {echo "0,00";}	
	  ?>
      </a></td>
      <td id="dato2"><a href="costos_gga_vista.php?FechaInicio=<?php echo $row_costos['FechaInicio']; ?>&FechaFin=<?php echo $row_costos['FechaFin']; ?>" target="new" style="text-decoration:none; color:#000000">
      <?php 
	  $fecha1=$row_costos['FechaInicio'];
	  $fecha2=$row_costos['FechaFin'];
	  $sqlTotal="SELECT ValorCaracGGA FROM TblDetalleGGAProd WHERE FechaInicio='$fecha1' AND FechaFin='$fecha2' AND IDCaracGGA='14'"; 
	  $resultTotal=mysql_query($sqlTotal); 
	  $numTotal=mysql_num_rows($resultTotal); 
	  if($numTotal >= '1') 
	  { $V250=mysql_result($resultTotal,0,'ValorCaracGGA'); echo $V250; }if ($V250==NULL) {echo "0,00";}	
	  ?>
      </a></td>
      <td id="dato2"><a href="costos_gga_vista.php?FechaInicio=<?php echo $row_costos['FechaInicio']; ?>&FechaFin=<?php echo $row_costos['FechaFin']; ?>" target="new" style="text-decoration:none; color:#000000">
      <?php 
	  $fecha1=$row_costos['FechaInicio'];
	  $fecha2=$row_costos['FechaFin'];
	  $sqlTotal="SELECT ValorCaracGGA FROM TblDetalleGGAProd WHERE FechaInicio='$fecha1' AND FechaFin='$fecha2' AND IDCaracGGA='15'"; 
	  $resultTotal=mysql_query($sqlTotal); 
	  $numTotal=mysql_num_rows($resultTotal); 
	  if($numTotal >= '1') 
	  { $V250=mysql_result($resultTotal,0,'ValorCaracGGA'); echo $V250; }if ($V250==NULL) {echo "0,00";}	
	  ?>
      </a></td>
      <td id="dato2"><a href="costos_gga_vista.php?FechaInicio=<?php echo $row_costos['FechaInicio']; ?>&FechaFin=<?php echo $row_costos['FechaFin']; ?>" target="new" style="text-decoration:none; color:#000000">
      <?php 
	  $fecha1=$row_costos['FechaInicio'];
	  $fecha2=$row_costos['FechaFin'];
	  $sqlTotal="SELECT ValorCaracGGA FROM TblDetalleGGAProd WHERE FechaInicio='$fecha1' AND FechaFin='$fecha2' AND IDCaracGGA='16'"; 
	  $resultTotal=mysql_query($sqlTotal); 
	  $numTotal=mysql_num_rows($resultTotal); 
	  if($numTotal >= '1') 
	  { $V250=mysql_result($resultTotal,0,'ValorCaracGGA'); echo $V250; }if ($V250==NULL) {echo "0,00";}	
	  ?>
      </a></td>
      <td id="dato2"><a href="costos_gga_vista.php?FechaInicio=<?php echo $row_costos['FechaInicio']; ?>&FechaFin=<?php echo $row_costos['FechaFin']; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_costos['TotalGGA_parcial']; ?></a></td>
      <td id="dato2"><a href="costos_gga_vista.php?FechaInicio=<?php echo $row_costos['FechaInicio']; ?>&FechaFin=<?php echo $row_costos['FechaFin']; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_costos['porc_parcial']; ?></a></td>
      <td id="dato2"><a href="costos_gga_vista.php?FechaInicio=<?php echo $row_costos['FechaInicio']; ?>&FechaFin=<?php echo $row_costos['FechaFin']; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_costos['UnidadesProducidas_parcial']; ?></a></td>
      <td id="dato2"><a href="costos_gga_vista.php?FechaInicio=<?php echo $row_costos['FechaInicio']; ?>&FechaFin=<?php echo $row_costos['FechaFin']; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_costos['CostoGGAxUn_parcial']; ?></a></td>
      <td id="dato2"><a href="costos_gga_vista.php?FechaInicio=<?php echo $row_costos['FechaInicio']; ?>&FechaFin=<?php echo $row_costos['FechaFin']; ?>" target="new" style="text-decoration:none; color:#000000">
      <?php 
	  $fecha1=$row_costos['FechaInicio'];
	  $fecha2=$row_costos['FechaFin'];
	  $sqlTotal="SELECT ValorCaracGGA FROM TblDetalleGGAProd WHERE FechaInicio='$fecha1' AND FechaFin='$fecha2' AND IDCaracGGA='29'"; 
	  $resultTotal=mysql_query($sqlTotal); 
	  $numTotal=mysql_num_rows($resultTotal); 
	  if($numTotal >= '1') 
	  { $V250=mysql_result($resultTotal,0,'ValorCaracGGA'); echo $V250; }if ($V250==NULL) {echo "0,00";}	
	  ?>
      </a></td>
      <td id="dato2"><a href="costos_gga_vista.php?FechaInicio=<?php echo $row_costos['FechaInicio']; ?>&FechaFin=<?php echo $row_costos['FechaFin']; ?>" target="new" style="text-decoration:none; color:#000000">
      <?php 
	  $fecha1=$row_costos['FechaInicio'];
	  $fecha2=$row_costos['FechaFin'];
	  $sqlTotal="SELECT ValorCaracGGA FROM TblDetalleGGAProd WHERE FechaInicio='$fecha1' AND FechaFin='$fecha2' AND IDCaracGGA='30'"; 
	  $resultTotal=mysql_query($sqlTotal); 
	  $numTotal=mysql_num_rows($resultTotal); 
	  if($numTotal >= '1') 
	  { $V250=mysql_result($resultTotal,0,'ValorCaracGGA'); echo $V250; }if ($V250==NULL) {echo "0,00";}	
	  ?>
      </a></td>
      <td id="dato2"><a href="costos_gga_vista.php?FechaInicio=<?php echo $row_costos['FechaInicio']; ?>&FechaFin=<?php echo $row_costos['FechaFin']; ?>" target="new" style="text-decoration:none; color:#000000">
      <?php 
	  $fecha1=$row_costos['FechaInicio'];
	  $fecha2=$row_costos['FechaFin'];
	  $sqlTotal="SELECT ValorCaracGGA FROM TblDetalleGGAProd WHERE FechaInicio='$fecha1' AND FechaFin='$fecha2' AND IDCaracGGA='31'"; 
	  $resultTotal=mysql_query($sqlTotal); 
	  $numTotal=mysql_num_rows($resultTotal); 
	  if($numTotal >= '1') 
	  { $V250=mysql_result($resultTotal,0,'ValorCaracGGA'); echo $V250; }if ($V250==NULL) {echo "0,00";}	
	  ?>
      </a></td>
      <td id="dato2"><a href="costos_gga_vista.php?FechaInicio=<?php echo $row_costos['FechaInicio']; ?>&FechaFin=<?php echo $row_costos['FechaFin']; ?>" target="new" style="text-decoration:none; color:#000000">
      <?php 
	  $fecha1=$row_costos['FechaInicio'];
	  $fecha2=$row_costos['FechaFin'];
	  $sqlTotal="SELECT ValorCaracGGA FROM TblDetalleGGAProd WHERE FechaInicio='$fecha1' AND FechaFin='$fecha2' AND IDCaracGGA='32'"; 
	  $resultTotal=mysql_query($sqlTotal); 
	  $numTotal=mysql_num_rows($resultTotal); 
	  if($numTotal >= '1') 
	  { $V250=mysql_result($resultTotal,0,'ValorCaracGGA'); echo $V250; }if ($V250==NULL) {echo "0,00";}	
	  ?>
      </a></td> 
      <td id="dato2"><a href="costos_gga_vista.php?FechaInicio=<?php echo $row_costos['FechaInicio']; ?>&FechaFin=<?php echo $row_costos['FechaFin']; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_costos['ResponsableGGA']; ?></a></td>  
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
    <td  id="dato1" width="25%"><?php if ($pageNum_costos < $totalPages_costos) { // Show if not last page ?>
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

mysql_free_result($numero);

?>