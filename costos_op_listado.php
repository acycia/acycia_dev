<?php require_once('Connections/conexion1.php'); ?>
<?php
//LLAMADO A FUNCIONES
include('funciones/funciones_php.php');//SISTEMA RUW PARA LA BASE DE DATOS 
//FIN
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

$nuevafecha = date('Y-m-01');	
$fecha1=$nuevafecha;
$fecha2= date("Y-m-d");

$maxRows_costos = 20;
$pageNum_costos = 0;
if (isset($_GET['pageNum_costos'])) {
  $pageNum_costos = $_GET['pageNum_costos'];
}
$startRow_costos = $pageNum_costos * $maxRows_costos;

mysql_select_db($database_conexion1, $conexion1);
$query_costos = "SELECT * FROM Tbl_orden_produccion WHERE b_estado_op > 0 AND fecha_registro_op BETWEEN '$fecha1'
AND  '$fecha2' ORDER BY id_op DESC";
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

mysql_select_db($database_conexion1, $conexion1);
$query_orden_produccion = "SELECT id_op FROM Tbl_orden_produccion WHERE b_estado_op > 0 ORDER BY id_op DESC";
$orden_produccion = mysql_query($query_orden_produccion, $conexion1) or die(mysql_error());
$row_orden_produccion = mysql_fetch_assoc($orden_produccion);
$totalRows_orden_produccion = mysql_num_rows($orden_produccion);

mysql_select_db($database_conexion1, $conexion1);
$query_referencia = "SELECT id_ref, cod_ref FROM Tbl_referencia order by id_ref desc";
$referencia = mysql_query($query_referencia, $conexion1) or die(mysql_error());
$row_referencia = mysql_fetch_assoc($referencia);
$totalRows_referencia = mysql_num_rows($referencia);


mysql_select_db($database_conexion1, $conexion1);
$query_cliente = "SELECT id_c,nit_c,nombre_c FROM cliente ORDER BY nombre_c ASC";
$cliente = mysql_query($query_cliente, $conexion1) or die(mysql_error());
$row_cliente = mysql_fetch_assoc($cliente);
$totalRows_cliente = mysql_num_rows($cliente);

//LLENA COMBOS DE MATERIAS PRIMAS
mysql_select_db($database_conexion1, $conexion1);
$query_proceso = "SELECT * FROM tipo_procesos ORDER BY id_tipo_proceso ASC";
$procesos = mysql_query($query_proceso, $conexion1) or die(mysql_error());
$row_proceso = mysql_fetch_assoc($procesos);
$totalRows_proceso = mysql_num_rows($procesos);
 ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/listado.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
</head>
<body>
<div align="center">
  <table align="center" id="tabla">
    <tr align="center">
      <td><div> <b class="spiffy"> <b class="spiffy1"><b></b></b> <b class="spiffy2"><b></b></b> <b class="spiffy3"></b> <b class="spiffy4"></b> <b class="spiffy5"></b></b>
          <div class="spiffy_content">
            <table id="tabla1">
              <tr>
                <td colspan="10" align="center"><img src="images/cabecera.jpg"></td>
              </tr>
              <tr>
                <td id="nombreusuario"><?php echo $row_usuario['nombre_usuario']; ?></td>
                <td id="cabezamenu"><ul id="menuhorizontal">
                    <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                    <li><a href="menu.php">MENU PRINCIPAL</a></li>
                  </ul></td>
              </tr>
              <tr>
                <td colspan="2" align="center" id="linea1"><form action="costos_op_listado2.php" method="get" name="form1">
                    <table id="tabla1">
                      <tr>
                        <td colspan="2" owrap="nowrap" id="codigo">CODIGO : R1 - F03</td>
                        <td colspan="6" nowrap="nowrap" id="subtitulo">COSTOS</td>
                        <td colspan="2" nowrap="nowrap" id="codigo">VERSION : 2</td>
                      </tr>
                      <tr>
                        <td colspan="10" nowrap id="fuente2">FECHA INICIO:
                          <input name="fecha_ini" type="date"  id="fecha_ini" min="2000-01-02" size="10" value="<?php echo date('Y-m-01');?>"/>
                          FECHA FIN:
                          <input name="fecha_fin" type="date" id="fecha_fin" min="2000-01-02" size="10" value="<?php echo fecha();?>"/>
                          O.P
                          <select name="op" id="op">
                            <option value="0">OP</option>
                            <?php
do {  
?>
                            <option value="<?php echo $row_orden_produccion['id_op']?>"><?php echo $row_orden_produccion['id_op']?></option>
                            <?php
} while ($row_orden_produccion = mysql_fetch_assoc($orden_produccion));
  $rows = mysql_num_rows($orden_produccion);
  if($rows > 0) {
      mysql_data_seek($orden_produccions, 0);
	  $row_orden_produccion = mysql_fetch_assoc($orden_produccion);
  }
?>
                          </select>
                          REF
                          <select name="ref" id="ref">
                            <option value="0">REF</option>
                            <?php
do {  
?>
                            <option value="<?php echo $row_referencia['cod_ref']?>"><?php echo $row_referencia['cod_ref']?></option>
                            <?php
} while ($row_referencia = mysql_fetch_assoc($referencia));
  $rows = mysql_num_rows($referencia);
  if($rows > 0) {
      mysql_data_seek($referencia, 0);
	  $row_referencia = mysql_fetch_assoc($referencia);
  }
?>
                          </select>
                          CLIENTE
                          <select name="cliente" id="cliente" style=" width:130px">
                            <option value="0">CLIENTE</option>
                            <?php
do {  
?>
                            <option value="<?php echo $row_cliente['nit_c']?>"><?php echo $row_cliente['nombre_c']?></option>
                            <?php
} while ($row_cliente = mysql_fetch_assoc($cliente));
  $rows = mysql_num_rows($cliente);
  if($rows > 0) {
      mysql_data_seek($cliente, 0);
	  $row_cliente = mysql_fetch_assoc($cliente);
  }
?>
                          </select>
                          <input type="submit" name="submit" id="submit" value="Consultar" /></td>
                      </tr>
                      <tr>
                        <td colspan="10" id="dato3"><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR" title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
                      </tr>
                    </table>
                  </form>
                  <table id="tabla1">
                    <tr id="tr1">
                      <td  nowrap="nowrap" id="titulo4">O.P</td>
                      <td  nowrap="nowrap" id="titulo4">CLIENTE</td>
                      <td nowrap="nowrap" id="titulo4">REF</td>
                      <td nowrap="nowrap" id="titulo4">ROLLOS</td>
                      <td  nowrap="nowrap" id="titulo4">METROS/L</td>
                      <td  nowrap="nowrap" id="titulo4">KILO/M.P</td>
                      <td  nowrap="nowrap" id="titulo4">KILOS/DESPERDICIO</td>
                      <td  nowrap="nowrap" id="titulo4">TIEMPO DESP</td>
                      <td  nowrap="nowrap" id="titulo4">FECHA</td>
                      <td  nowrap="nowrap" id="titulo4">PROCESO</td>
                    </tr>
                    <?php do { ?>
                      <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
                        <td id="dato2"><a href="costos_op_add.php?id_op=<?php echo $row_costos['id_op']; ?>&amp;fechafin=<?php echo $fecha2; ?>" target="new" style="text-decoration:none; color:#000000"><strong><?php echo $row_costos['id_op'];?></strong></a></td>
                        <td id="dato1" nowrap><a href="costos_op_add.php?id_op=<?php echo $row_costos['id_op']; ?>&amp;fechafin=<?php echo $fecha2; ?>" target="new" style="text-decoration:none; color:#000000"><?php 
	  $nit=$row_costos['str_nit_op'];
	  $sqlnclie="SELECT nombre_c FROM cliente WHERE nit_c='$nit'"; 
	  $resultclie=mysql_query($sqlnclie); 
	  $numclie=mysql_num_rows($resultclie); 
	  if($numclie >= '1') 
	  { $cliente=mysql_result($resultclie,0,'nombre_c'); echo $cliente; }
	  ?>
                          </a></td>
                        <td id="dato2"><a href="costos_op_add.php?id_op=<?php echo $row_costos['id_op']; ?>&amp;fechafin=<?php echo $fecha2; ?>" target="new" style="text-decoration:none; color:#000000"><strong>
                          <?php 
	  echo $row_costos['int_cod_ref_op'];?>
                        </strong></a></td>
                        <td id="dato2"><a href="costos_op_add.php?id_op=<?php echo $row_costos['id_op']; ?>&amp;fechafin=<?php echo $fecha2; ?>" target="new" style="text-decoration:none; color:#000000"><?php 
	  $id_op=$row_costos['id_op'];
	  $sqlrollo="SELECT COUNT(rollo_r) AS rollos,SUM(metro_r) AS metros,SUM(kilos_r) AS kilos FROM TblExtruderRollo WHERE id_op_r='$id_op'"; 
	  $resultrollo=mysql_query($sqlrollo); 
	  $numrollo=mysql_num_rows($resultrollo); 
	  if($numrollo >= '1') 
	  { $rollo=mysql_result($resultrollo,0,'rollos');
	    $metros=mysql_result($resultrollo,0,'metros');
	    $kilos=mysql_result($resultrollo,0,'kilos'); 
		echo $rollo; }?></a></td>
                        <td id="dato1"><a href="costos_op_add.php?id_op=<?php echo $row_costos['id_op']; ?>&amp;fechafin=<?php echo $fecha2; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo redondear_entero_puntos($metros);?></a></td>
                        <td id="dato1"><a href="costos_op_add.php?id_op=<?php echo $row_costos['id_op']; ?>&amp;fechafin=<?php echo $fecha2; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo numeros_format($kilos);?></a></td>
                        <td id="dato1"><a href="costos_op_add.php?id_op=<?php echo $row_costos['id_op']; ?>&amp;fechafin=<?php echo $fecha2; ?>" target="new" style="text-decoration:none; color:#000000"><?php 
	  $id_op=$row_costos['id_op'];
	  $sqldesp="SELECT SUM(valor_desp_rd) AS kgDespe FROM Tbl_reg_desperdicio WHERE op_rd='$id_op' "; 
	  $resultdesp=mysql_query($sqldesp); 
	  $numdesp=mysql_num_rows($resultdesp); 
	  if($numdesp >= '1') 
	  { $kilos_desp=mysql_result($resultdesp,0,'kgDespe'); echo numeros_format($kilos_desp); }else {echo "0,00";}
	  ?></a></td>
                        <td id="dato1"><a href="costos_op_add.php?id_op=<?php echo $row_costos['id_op']; ?>&amp;fechafin=<?php echo $fecha2; ?>" target="new" style="text-decoration:none; color:#000000"><?php 
	  $id_op=$row_costos['id_op'];
	  $sqlexm="SELECT SUM(valor_tiem_rt) AS horasM FROM Tbl_reg_tiempo WHERE op_rt='$id_op'"; 
	  $resultexm=mysql_query($sqlexm); 
	  $numexm=mysql_num_rows($resultexm); 
	  if($numexm >= '1') 
	  { $horasM_ex=mysql_result($resultexm,0,'horasM'); echo enteroHoras($horasM_ex); }if ($horasM_ex==NULL){ echo "00";}
	  ?></a></td>
                        <td nowrap id="dato1"><?php echo $row_costos['fecha_registro_op']; ?></td>
                        <td id="dato1"><a href="costos_op_add.php?id_op=<?php echo $row_costos['id_op']; ?>&amp;fechafin=<?php echo $fecha2; ?>" target="new" style="text-decoration:none; color:#000000"><?php $estados=$row_costos['b_estado_op']; 
						switch ($estados){
							case 0: echo "INGRESADA";
							break;
							case 1: echo "EXTRUSION";
							break;
							case 2: echo "IMPRESION";
							break;
							case 3: echo "REFILADO";
							break;
							case 4: echo "SELLADO";
							break;
							case 5: echo "FINALIZADA";
							break;							
							}
						
						?></a></td>
                      </tr>
                      <?php } while ($row_costos = mysql_fetch_assoc($costos)); ?>
                  </table>
                  <table id="tabla1">
                    <tr>
                      <td id="dato1" ><?php if ($pageNum_costos > 0) { // Show if not first page ?>
                          <a href="<?php printf("%s?pageNum_costos=%d%s", $currentPage, 0, $queryString_costos); ?>">Primero</a>
                          <?php } // Show if not first page ?></td>
                      <td id="dato1" ><?php if ($pageNum_costos > 0) { // Show if not first page ?>
                          <a href="<?php printf("%s?pageNum_costos=%d%s", $currentPage, max(0, $pageNum_costos - 1), $queryString_costos); ?>">Anterior</a>
                          <?php } // Show if not first page ?></td>
                      <td id="dato1" ><?php if ($pageNum_costos < $totalPages_costos) { // Show if not last page ?>
                          <a href="<?php printf("%s?pageNum_costos=%d%s", $currentPage, min($totalPages_costos, $pageNum_costos + 1), $queryString_costos); ?>">Siguiente</a>
                          <?php } // Show if not last page ?></td>
                      <td id="dato1" ><?php if ($pageNum_costos < $totalPages_costos) { // Show if not last page ?>
                          <a href="<?php printf("%s?pageNum_costos=%d%s", $currentPage, $totalPages_costos, $queryString_costos); ?>">&Uacute;ltimo</a>
                          <?php } // Show if not last page ?></td>
                    </tr>
                  </table></td>
              </tr>
            </table>
          </div>
          <b class="spiffy"> <b class="spiffy5"></b> <b class="spiffy4"></b> <b class="spiffy3"></b> <b class="spiffy2"><b></b></b> <b class="spiffy1"><b></b></b></b></div></td>
    </tr>
  </table>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($costos);

?>