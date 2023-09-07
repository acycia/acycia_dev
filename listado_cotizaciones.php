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
session_start();
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
$colname_usuario_listado_clientes = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario_listado_clientes = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario_listado_clientes = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario_listado_clientes);
$usuario_listado_clientes = mysql_query($query_usuario_listado_clientes, $conexion1) or die(mysql_error());
$row_usuario_listado_clientes = mysql_fetch_assoc($usuario_listado_clientes);
$totalRows_usuario_listado_clientes = mysql_num_rows($usuario_listado_clientes);

if($_GET['orden']==''){
	$orden="N_cotizacion DESC";
	}else{
$orden=$_GET['orden'];
	}
mysql_select_db($database_conexion1, $conexion1);
$query_cotizacion = "SELECT * FROM Tbl_cotizaciones, cliente WHERE Tbl_cotizaciones.Str_nit = cliente.nit_c ORDER BY $orden";
$cotizacion = mysql_query($query_cotizacion, $conexion1) or die(mysql_error());
$row_cotizacion = mysql_fetch_assoc($cotizacion);
$totalRows_cotizacion = mysql_num_rows($cotizacion);

session_start();
 ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0014)about:internet -->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>SISADGE AC &amp; CIA</title>
<script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
<link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">

<script type="text/javascript" src="js/formato.js"></script> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--Fireworks 8 Dreamweaver 8 target.  Created Tue Jun 06 20:42:04 GMT-0500 2006-->
<style type="text/css">
<!--
.Estilo1 {
	font-size: 14px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #000066;
}
.Estilo2 {
	font-size: 12px;
	font-family: Arial, Helvetica, sans-serif;
	color: #000066;
}
.Estilo3 {font-size: 12px; font-family: Arial, Helvetica, sans-serif; color: #FF0000; }
.Estilo4 {
	color: #FF0000;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 11px;
}
.Estilo5 {
	font-size: 12px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #000066;
}
.Estilo76 {font-size: 18px; color: #000066; font-family: Arial, Helvetica, sans-serif; font-weight: bold; }
.Estilo6 {
	font-size: 12px;
	font-weight: bold;
	color: #000066;
	font-family: Arial, Helvetica, sans-serif;
}
.Estilo7 {font-size: 11px; font-family: Verdana, Arial, Helvetica, sans-serif; color: #000066; }
.Estilo8 {
	font-size: 12px;
	font-family: Arial, Helvetica, sans-serif;
}
.Estilo9 {font-size: 12px}
-->
</style>
</head>
<body bgcolor="#ffffff">
<table width="737" height="10" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
  <tr bgcolor="#1B3781">
    <td width="729" bordercolor="#FFFFFF"><img src="images/logoacyc.jpg" width="101" height="80" /><img src="images/cabecera.jpg" width="626" height="80" /></td>
  </tr>
  <tr>
    <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="730" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <tr>
        <td colspan="2" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left" class="Estilo1 Estilo9"><?php echo $row_usuario_listado_clientes['nombre_usuario']; ?></div></td>
        <td width="128" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right"><a href="<?php echo $logoutAction ?>" class="Estilo2">Cerrar Sesión</a></div></td>
      </tr>
      
      <tr>
        <td width="170" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center"><span class="Estilo6"><strong>Codigo: R1-F03</strong></span></div></td>
        <td width="404" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center"><span class="Estilo76">SEGUIMIENTO A COTIZACIONES</span></div></td>
        <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo6">Versión: 0</div></td>
      </tr>

      <tr>
        <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center"><a href="menu.php" class="Estilo7">Menu Principal </a></div></td>
        <td bordercolor="#FFFFFF" bgcolor="#FFFFFF">&nbsp;</td>
        <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center"><a href="comercial.php" class="Estilo7">Gestión Comercial </a></div></td>
      </tr>
      <tr>
        <td colspan="2" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><form name="form1" id="form1" method="get" action="listado_cotizaciones1.php" >
          <span class="Estilo5"> Cotización
          <input type="radio" name="campo" value="N_cotizacion" />
Nit
          <input type="radio" name="campo" value="Str_nit" />
          </span>
          <input name="criterio" type="text" class="Estilo68" id="criterio" size="20"  required="required"/>
          <input name="Submit" type="submit" class="Estilo67" value="Buscar" />
        </form></td>
        <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo3">
          <div align="center"><a href="cotizacion_general_menu.php" class="Estilo4">*Add Cotización </a></div>
        </div></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td bordercolor="#FFFFFF" bgcolor="#FFFFFF">
    <div style="height:500px; overflow:scroll;">
    <table width="730" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <tr>
        <td width="104" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo6"><a href="listado_cotizaciones.php?orden=<?php echo "N_cotizacion DESC";?>">Cotización Nº</a></div></td>
        <td width="128" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo6">Producto</div></td>
        <td width="250" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo6"><a href="listado_cotizaciones.php?orden=<?php echo "nombre_c ASC";?>">Cliente</a></div></td>
        <td width="100" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo6"><a href="listado_cotizaciones.php?orden=<?php echo "nit_c ASC";?>">Nit</a></div></td>
        <td width="51" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo6">View</div></td> 
        <td width="54" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo6">Delete</div></td>
      </tr>
	  <?php 
	  $i=0;  ?>
      <?php do { 
	  
	   	 switch ($row_cotizacion['Str_tipo']) {
    case "BOLSA":
        $LINCK ="cotizacion_g_bolsa_vista.php?";
        break;
    case "PACKING LIST":
        $LINCK ="cotizacion_g_packing_vista.php?";
        break;
    case "MATERIA PRIMA":
        $LINCK ="cotizacion_g_materiap_vista.php?";
        break;
	case "LAMINA":
        $LINCK ="cotizacion_g_lamina_vista.php?";
        break;	
 }?>
       <tr <?php if ($i%2==0) {?> style="background:#FFFFFF" <?php }else {?>style="background:#ECF5FF" <?php } 
	  $i++;		  ?>>
          <td width="104"><div align="center" class="Estilo8"><?php echo $row_cotizacion['N_cotizacion']; ?></div></td>
          <td width="128"><div align="center"><span class="Estilo8"><?php echo $row_cotizacion['Str_tipo']; ?></span></div></td>
          <td width="250"><div align="center"><span class="Estilo8"><?php echo $row_cotizacion['nombre_c']; ?></span></div></td>
          <td width="100"><div align="center"><span class="Estilo8"><?php echo $row_cotizacion['nit_c']; ?></span></div></td>
          <td width="51"><div align="center"><a href="<?php echo $LINCK;?>N_cotizacion=<?php echo $row_cotizacion['N_cotizacion']; ?>&cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "6"; ?>" target="new" style="text-decoration:none; color:#000000"><img src="images/hoja.gif" alt="Ver" width="18" height="18" border="0" /></a></div></td>
           
          <td width="54"><div align="center">
                    <a href="javascript:eliminar1('id_cotiz',<?php echo $row_cotizacion['id_cotiz'];?>,'listado_cotizaciones.php')"><img src="images/por.gif" alt="ELIMINAR COTIZ." title="ELIMINAR COTIZ." border="0" style="cursor:hand;"/></a></div></td>
        </tr>
        <?php } while ($row_cotizacion = mysql_fetch_assoc($cotizacion)); ?>
    </table>
    </div>
    </td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($usuario_listado_clientes);

mysql_free_result($cotizacion);
?>
