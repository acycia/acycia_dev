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
$colname_usuario_comercial = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario_comercial = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario_comercial = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario_comercial);
$usuario_comercial = mysql_query($query_usuario_comercial, $conexion1) or die(mysql_error());
$row_usuario_comercial = mysql_fetch_assoc($usuario_comercial);
$totalRows_usuario_comercial = mysql_num_rows($usuario_comercial);

$colname_ver_sub_menu = "-1";
if (isset($_GET['id_menu'])) {
  $colname_ver_sub_menu = (get_magic_quotes_gpc()) ? $_GET['id_menu'] : addslashes($_GET['id_menu']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_sub_menu = sprintf("SELECT * FROM submenu WHERE id_menu_submenu = '1'", $colname_ver_sub_menu);
$ver_sub_menu = mysql_query($query_ver_sub_menu, $conexion1) or die(mysql_error());
$row_ver_sub_menu = mysql_fetch_assoc($ver_sub_menu);
$totalRows_ver_sub_menu = mysql_num_rows($ver_sub_menu);
?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link rel="StyleSheet" href="css/imageMenu.css" type="text/css">
<link href="SpryAssets/SpryMenuBarVertical.css" rel="stylesheet" type="text/css">
<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
</head>
<body oncontextmenu="return false">
<table id="tablacentro"><tr><td id="tdcentro">
<div align="center"><table id="tabla1"><tr><td>
<div id="cabecera1"><div class="menu1"><ul>
  <li><?php echo $row_usuario_comercial['nombre_usuario']; ?></li>
  <li><a href="menu.php">MENU</a></li>  
  <li><a href="<?php echo $logoutAction ?>">SALIR</a></li>
  </ul></div></div>
  <div id="justificacion">
  <div id="columna1">
    <div class="navbar">
      <ul id="MenuBar2" class="MenuBarVertical">
        <li><a class="MenuBarItemSubmenu" href="#">Perfil del Cliente</a>
          <ul>
            <li><a href="#">Creacion</a></li>
            <li><a href="#">Consulta</a></li>
          </ul>
        </li>
        <li><a class="MenuBarItemSubmenu" href="#">Cotizaciones</a>
          <ul>
                <li><a href="#">Creacion</a></li>
                <li><a href="#">Consulta Individual</a></li>
                <li><a href="#">Listado Cotizaciones Cliente</a></li>
                <li><a href="#">Seguimiento Cotizaciones</a></li>
              </ul>
            </li>
            <li><a href="#">Analisis Quejas Y Reclamos</a></li>
          </ul>
        </li>
      </ul>
    </div></div>
 

<div id="columna2"><br><strong>GESTION COMERCIAL</strong><br><br>
      <strong>Objetivo:</strong> Proyectar y posicionar la Organización en nuevos mercados con productos de excelente calidad que cumplan los requerimientos y especificaciones de los clientes logrando su satisfacción, lealtad y confianza generando rentabilidad para la empresa.<br><br><strong>Alcance:</strong> Cubre desde la planificación de mercado y ventas, la comercialización, definición y verificación del cumplimiento de los requisitos, la determinación de los recursos para la fabricación del producto, la evaluación del nivel de satisfacción del cliente, su lealtad y confianza, hasta el cumplimiento de las metas de venta para lograr la rentabilidad esperada.<br><br><strong>Responsable:</strong> Gerente de Unidad - Director Comercial.<br><br><strong>Participantes:</strong> Gerente de Unidad, Asesores comerciales, Coordinador de Calidad, Supervisor de Producción.<br>
    </div></td></tr></table></div></td></tr></table>
<script type="text/javascript">
var MenuBar1 = new Spry.Widget.MenuBar("MenuBar1", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
var MenuBar2 = new Spry.Widget.MenuBar("MenuBar2", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
</script>
</body>
</html>
<?php
mysql_free_result($usuario_comercial);

mysql_free_result($ver_sub_menu);
?>