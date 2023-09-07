<?php require_once('Connections/conexion1.php'); ?>
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
$colname_usuario_admon = "1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario_admon = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario_admon = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario_admon);
$usuario_admon = mysql_query($query_usuario_admon, $conexion1) or die(mysql_error());
$row_usuario_admon = mysql_fetch_assoc($usuario_admon);
$totalRows_usuario_admon = mysql_num_rows($usuario_admon);

mysql_select_db($database_conexion1, $conexion1);
$query_ver_permisos = "SELECT * FROM permisos";
$ver_permisos = mysql_query($query_ver_permisos, $conexion1) or die(mysql_error());
$row_ver_permisos = mysql_fetch_assoc($ver_permisos);
$totalRows_ver_permisos = mysql_num_rows($ver_permisos);

mysql_select_db($database_conexion1, $conexion1);
$query_ver_tipo_user = "SELECT * FROM tipo_user";
$ver_tipo_user = mysql_query($query_ver_tipo_user, $conexion1) or die(mysql_error());
$row_ver_tipo_user = mysql_fetch_assoc($ver_tipo_user);
$totalRows_ver_tipo_user = mysql_num_rows($ver_tipo_user);

mysql_select_db($database_conexion1, $conexion1);
$query_ver_menu = "SELECT * FROM menu";
$ver_menu = mysql_query($query_ver_menu, $conexion1) or die(mysql_error());
$row_ver_menu = mysql_fetch_assoc($ver_menu);
$totalRows_ver_menu = mysql_num_rows($ver_menu);

$colname_ver_submenu = "1";
if (isset($_GET['id_menu'])) {
  $colname_ver_submenu = (get_magic_quotes_gpc()) ? $_GET['id_menu'] : addslashes($_GET['id_menu']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_submenu = sprintf("SELECT * FROM submenu WHERE id_menu_submenu = %s", $colname_ver_submenu);
$ver_submenu = mysql_query($query_ver_submenu, $conexion1) or die(mysql_error());
$row_ver_submenu = mysql_fetch_assoc($ver_submenu);
$totalRows_ver_submenu = mysql_num_rows($ver_submenu);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.Estilo26 {color: #000066}
.Estilo7 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
	font-style: italic;
	color: #000066;
	font-size: 11px;
}
.Estilo33 {font-family: Arial, Helvetica, sans-serif}
.Estilo34 {
	font-size: 18px;
	font-weight: bold;
	color: #000066;
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
.Estilo35 {font-size: 14px; font-family: Arial, Helvetica, sans-serif; font-weight: bold; color: #000066; }
.Estilo36 {font-family: Georgia, "Times New Roman", Times, serif}
.Estilo41 {
	font-family: "Agency FB", Algerian, Arial, "Arial Black", "Arial Narrow", "Arial Rounded MT Bold", "Arial Unicode MS", "Baskerville Old Face", "Bauhaus 93", "Bell MT", "Berlin Sans FB", "Berlin Sans FB Demi", "Bernard MT Condensed", "Blackadder ITC", "Bodoni MT", "Bodoni MT Black", "Bodoni MT Condensed", "Bodoni MT Poster Compressed", "Book Antiqua", "Bookman Old Style", "Century Gothic", "Comic Sans MS";
	color: #000066;
	font-weight: bold;
}
.Estilo42 {
	font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
	font-size: 12px;
}
.Estilo43 {
	font-size: 12px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #000066;
}
.Estilo44 {
	font-size: 11px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #000066;
}
.Estilo47 {font-size: 11px; font-family: Arial, Helvetica, sans-serif; }
.Estilo52 {font-size: 16px}
.Estilo55 {font-family: Arial, Helvetica, sans-serif; color: #000066; font-weight: bold; font-size: 12px; }
.Estilo56 {font-size: 12px; font-family: Arial, Helvetica, sans-serif; }
.Estilo57 {color: #990000}
-->
</style>
</head>

<body>
<table width="737" height="100" border="2" align="center" cellspacing="3">
  
  <tr bgcolor="#CCCCCC">
    <td height="26" colspan="2" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="726" border="0" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
        <tr>
          <td width="452"><span class="Estilo43"><?php echo $row_usuario_admon['nombre_usuario']; ?></span></td
          <td width="434"><div align="right"><a href="<?php echo $logoutAction ?>" class="Estilo44">Cerrar Sesi&oacute;n</a> </div></td>
        </tr>
    </table></td>
  </tr>
  <tr bgcolor="#999999">
    <td height="21" colspan="2" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="726" border="0" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
        <tr bgcolor="#CCCCCC">
          <td width="355" height="21" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo47">
            <div align="left"><span class="Estilo35 Estilo36 Estilo27 Estilo33 Estilo34"><span class="Estilo35 Estilo27 Estilo33 Estilo52">LISTADO DE PERMISOS </span></span></div>
          </div></td>
          <td width="113" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo47"><a href="menu.php" class="Estilo26">Menu</a></div></td>
          <td width="163" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo47">
              <div align="center"><a href="permiso_nuevo.php" class="Estilo57">*Adicionar Permiso </a></div>
          </div></td>
          <td width="72" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo47"><a href="Administrador.php" class="Estilo26">Administrador</a></div></td>
        </tr>
      </table></td>
  </tr>
  <tr bordercolor="#999999" bgcolor="#999999">
    <td height="54" colspan="2" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="726" border="0" cellspacing="3" bordercolor="#CCCCCC" bgcolor="#CCCCCC">
      <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
        <td width="30" bordercolor="#CCCCCC" bgcolor="#EEEEEE"><div align="center" class="Estilo55">No</div></td>
        <td width="185" bordercolor="#CCCCCC" bgcolor="#EEEEEE" class="Estilo41"><div align="center" class="Estilo56">TIPO DE USUARIO </div></td>
        <td width="253" bordercolor="#CCCCCC" bgcolor="#EEEEEE"><div align="center" class="Estilo55">MENU</div></td>
        <td width="355" bordercolor="#CCCCCC" bgcolor="#EEEEEE"><div align="center" class="Estilo55">SUBMENU</div></td>
        <td width="42" bordercolor="#CCCCCC" bgcolor="#EEEEEE"><div align="center" class="Estilo55">EDITAR</div></td>
        <td width="48" bordercolor="#CCCCCC" bgcolor="#EEEEEE"><div align="center" class="Estilo26 Estilo42">ELIMINAR</div></td>
      </tr>
      <?php do { ?>
      <tr bordercolor="#999999" bgcolor="#CCCCCC">
          <td width="30" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="center" class="Estilo56">
            <input name="id_registro" type="text" value="<?php echo $row_ver_permisos['id_registro']; ?>" size="3" readonly="true">
          </div></td>
          <td bordercolor="#CCCCCC" bgcolor="#FFFFFF">
            <div align="left" class="Estilo56">
              <?php $tipo_user=$row_ver_permisos['usuario']; 
          $sql1="select * from tipo_user where id_tipo='$tipo_user'";
          $result1=mysql_query($sql1);
          $nom1=mysql_result($result1,0,'nombre_tipo');
          echo $nom1;?>
            </div></td>
          <td bordercolor="#CCCCCC" bgcolor="#FFFFFF">
            <div align="left" class="Estilo56">
              <?php $menu_user=$row_ver_permisos['menu']; 
          $sql2="select * from menu where id_menu='$menu_user'";
          $result2=mysql_query($sql2);
          $nom2=mysql_result($result2,0,'nombre_menu');
          echo $nom2;?>
            </div></td>
          
          <td bordercolor="#CCCCCC" bgcolor="#FFFFFF">
            <div align="left" class="Estilo56">
              <?php $submenu_user=$row_ver_permisos['submenu']; 
          $sql3="select * from submenu where id_submenu='$submenu_user'";
          $result3=mysql_query($sql3);
          $nom3=mysql_result($result3,0,'nombre_submenu');
          echo $nom3;?>
            </div></td><td bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="center" class="Estilo56"><a href="permisos_editar.php?id_registro=<?php echo $row_ver_permisos['id_registro']; ?>"><img src="hoja.gif" width="18" height="18" border="0"></a></div></td>
          <td bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="center" class="Estilo56"><a href="borrado_permiso.php?id_registro=<?php echo $row_ver_permisos['id_registro']; ?>"><img src="eliminar.gif" width="18" height="18" border="0"></a></div></td>
      </tr>
      <?php } while ($row_ver_permisos = mysql_fetch_assoc($ver_permisos)); ?>
    </table></td>
  </tr>
  
  <tr bordercolor="#FFFFFF" bgcolor="#999999">
    <td width="102" height="31" bordercolor="#FFFFFF" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="618" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="621" border="0" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <tr bgcolor="#CCCCCC">
        <td width="372" height="21" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo47"><a href="menu.php" class="Estilo26">Menu</a></div></td>
        <td width="158" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo47">
            <div align="center"><a href="permiso_nuevo.php" class="Estilo57">*Adicionar Permiso </a></div>
        </div></td>
        <td width="73" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo47"><a href="Administrador.php" class="Estilo26">Administrador</a></div></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($usuario_admon);

mysql_free_result($ver_permisos);

mysql_free_result($ver_tipo_user);

mysql_free_result($ver_menu);

mysql_free_result($ver_submenu);
?>
