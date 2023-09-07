<?php require_once('Connections/conexion1.php'); ?>
<?php
//initialize the session
session_start();

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  session_unregister('MM_Username');
  session_unregister('MM_UserGroup');
	
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
$colname_usuario_comercial = "1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario_comercial = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario_comercial = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario_comercial);
$usuario_comercial = mysql_query($query_usuario_comercial, $conexion1) or die(mysql_error());
$row_usuario_comercial = mysql_fetch_assoc($usuario_comercial);
$totalRows_usuario_comercial = mysql_num_rows($usuario_comercial);

$colname_ver_revision = "-1";
if (isset($_GET['cod_ref'])) {
  $colname_ver_revision = (get_magic_quotes_gpc()) ? $_GET['cod_ref'] : addslashes($_GET['cod_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_revision = sprintf("SELECT * FROM revision WHERE cod_ref_rev = '%s'", $colname_ver_revision);
$ver_revision = mysql_query($query_ver_revision, $conexion1) or die(mysql_error());
$row_ver_revision = mysql_fetch_assoc($ver_revision);
$totalRows_ver_revision = mysql_num_rows($ver_revision);

$colname_ver_referencia = "-1";
if (isset($_GET['cod_ref'])) {
  $colname_ver_referencia = (get_magic_quotes_gpc()) ? $_GET['cod_ref'] : addslashes($_GET['cod_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_referencia = sprintf("SELECT * FROM referencia WHERE cod_ref = '%s'", $colname_ver_referencia);
$ver_referencia = mysql_query($query_ver_referencia, $conexion1) or die(mysql_error());
$row_ver_referencia = mysql_fetch_assoc($ver_referencia);
$totalRows_ver_referencia = mysql_num_rows($ver_referencia);

mysql_select_db($database_conexion1, $conexion1);
$tipo_usuario=$row_usuario_comercial['tipo_usuario'];
$query_ver_sub_menu = "SELECT distinct(id_submenu),nombre_submenu,url,submenu FROM submenu,permisos,usuario WHERE permisos.menu='1' AND permisos.submenu=submenu.id_submenu and permisos.usuario='$tipo_usuario'";
$ver_sub_menu = mysql_query($query_ver_sub_menu, $conexion1) or die(mysql_error());
$row_ver_sub_menu = mysql_fetch_assoc($ver_sub_menu);
$totalRows_ver_sub_menu = mysql_num_rows($ver_sub_menu);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.Estilo14 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 18px;
	font-weight: bold;
	color: #000066;
}
.Estilo23 {font-family: "Courier New", Courier, mono; font-size: 14px; font-weight: bold; color: #000066; }
.Estilo43 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; }
.Estilo45 {
	font-size: 12px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #000066;
}
.Estilo46 {
	font-size: 11px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #000066;
}
.Estilo52 {
	color: #FF0000;
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
.Estilo53 {
	font-size: 12px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
.Estilo54 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold; color: #000066; }
.Estilo56 {font-family: Verdana, Arial, Helvetica, sans-serif; color: #000066;}
.Estilo58 {font-size: 11px}
.Estilo59 {color: #000066; font-size: 11px; font-family: Arial, Helvetica, sans-serif; }
.Estilo60 {font-family: Verdana, Arial, Helvetica, sans-serif}
.Estilo61 {font-size: 20px}
-->
</style>
</head>

<body>
<table width="735" height="299" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
  <tr bgcolor="#1B3781">
    <td height="23" bordercolor="#FFFFFF"><img src="logo_acyc.gif" width="101" height="80"><img src="index_r1_c2.gif" width="626" height="80"></td>
  </tr>
  <tr bgcolor="#CCCCCC">
    <td height="26" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="726" border="0" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <tr>
        <td width="450"><span class="Estilo45"><?php echo $row_usuario_comercial['nombre_usuario']; ?></span></td>
        <td width="433"><div align="right"><a href="<?php echo $logoutAction ?>" class="Estilo46">Cerrar Sesi&oacute;n</a></div></td>
      </tr>
    </table></td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td height="120" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="735" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
<tr bordercolor="#FFFFFF" bgcolor="#FFFFFF">
  <td width="741" colspan="2"><table width="735" border="0" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
    <tr>
      <td colspan="2" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo14 Estilo61">PLAN DE DISE&Ntilde;O Y DESARROLLO </div></td>
      </tr>
    <tr>
      <td width="356" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo23 Estilo53">Codigo: R2-F01 </div></td>
      <td width="366" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo54">Versi&oacute;n: 2 </div></td>
    </tr>
    
  </table></td>
</tr>
          <tr bordercolor="#FFFFFF" bgcolor="#FFFFFF">
            <td height="21" colspan="2" bgcolor="#FFFFFF"><table width="735" border="0" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
              <tr>
                <td width="694"><div align="center"><span class="Estilo14">1. REVISION </span></div></td>
                <td width="28"><div align="right"><a href="verificacion_detalle.php?cod_ref=<?php echo $row_ver_referencia['cod_ref']; ?>"><img src="arrows069.gif" alt="Verificación" width="26" height="21" border="0"></a></div></td>
              </tr>
            </table></td>
          </tr>
          <tr bordercolor="#FFFFFF" bgcolor="#FFFFFF">
            <td height="21" colspan="2"><table width="735" border="0" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#ECF5FF">
              <tr>
                <td width="112" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo54">Revisi&oacute;n N&ordm;</div></td>
                <td width="144" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo54">Referencia</div></td>
                <td width="172" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo54">Cliente</div></td>
                <td width="139" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo54">Fecha de Revisi&oacute;n </div></td>
                <td width="30" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo54">Ver</div></td>
                <td width="49" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo54">Imprimir</div></td>
                <td width="51" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo54">Eliminar</div></td>
              </tr>
              <?php do { ?>
                <tr bordercolor="#FFFFFF" bgcolor="#FFFFFF">
                  <td><div align="center" class="Estilo43"><?php echo $row_ver_revision['id_rev']; ?></div></td>
                  <td><div align="center" class="Estilo43"><?php echo $row_ver_revision['cod_ref_rev']; ?></div></td>
                  <td><div align="center" class="Estilo43"><?php 
				  $cliente=$row_ver_revision['nit_c_rev'];
				  if($cliente!='Varios')
				  {
				  mysql_select_db($database_conexion1, $conexion1);
				  $sql1="select * from cliente where nit_c='$cliente'";
				  $result1=mysql_query($sql1);
				  $num1=mysql_num_rows($result1);
				  if ($num1 >='1')
				  {
				  $cliente2=mysql_result($result1,0,'nombre_c');
				  echo $cliente2;
				  }
				  }
				  else
				  {
				  echo $cliente;
				  }?></div></td>
                  <td><div align="center" class="Estilo43"><?php echo $row_ver_revision['fecha_rev']; ?></div></td>
                  <td><div align="center" class="Estilo43"><a href="revision_ver.php?cod_ref=<?php echo $row_ver_referencia['cod_ref']; ?>"><img src="hoja.gif" width="18" height="18" border="0" alt="Editar Revisión"></a></div></td>
                  <td><div align="center" class="Estilo43"><a href="revision_imprimir.php?cod_ref=<?php echo $row_ver_referencia['cod_ref']; ?>" target="new"><img src="impresor.gif" width="18" height="18" border="0" alt="Imprimir Revisión"></a></div></td>
                  <td><div align="center" class="Estilo43"><a href="borrado_revision.php?id_rev=<?php echo $row_ver_revision['id_rev']; ?>"><img src="eliminar.gif" width="18" height="18" border="0" alt="Eliminar Revisión"></a></div></td>
                </tr>
                <?php } while ($row_ver_revision = mysql_fetch_assoc($ver_revision)); ?>
            </table></td>
          </tr>
        </table>        </td>
  </tr>
  
  <tr bordercolor="#FFFFFF" bgcolor="#999999">
    <td height="31" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="735" border="0" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <tr>
        <td width="97"><div align="center" class="Estilo58"><a href="menu.php"><span class="Estilo56"><img src="home.gif" alt="Menu Principal" width="22" height="23"></span></a></div></td>
        <td width="155"><div align="center" class="Estilo59"><a href="referencias.php" class="Estilo56">Listado Referencias </a></div></td>
        <td width="215"><div align="center" class="Estilo59">
            <?php if($totalRows_ver_revision=="0")
		{?>
            <a href="revision_nueva.php?cod_ref=<?php echo $row_ver_referencia['cod_ref']; ?>" class="Estilo52">*Adicionar          Revisi&oacute;n</a>
            <?php }
		else
		{?>
            <span class="Estilo60">Existe Revisi&oacute;n </span>
          <?php
		}
		 ?>
        </div></td>
        <td width="141"><div align="right" class="Estilo59">
          <div align="center"><a href="disenoydesarrollo.php" class="Estilo56">Dise&ntilde;o y Desarrollo </a></div>
        </div></td>
        <td width="99"><div align="right" class="Estilo58"><img src="firma3.bmp"></div></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($usuario_comercial);

mysql_free_result($ver_revision);

mysql_free_result($ver_referencia);

mysql_free_result($ver_sub_menu);
?>
