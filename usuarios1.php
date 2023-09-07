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
$colname_usuario_usuarios = "1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario_usuarios = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario_usuarios = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario_usuarios);
$usuario_usuarios = mysql_query($query_usuario_usuarios, $conexion1) or die(mysql_error());
$row_usuario_usuarios = mysql_fetch_assoc($usuario_usuarios);
$totalRows_usuario_usuarios = mysql_num_rows($usuario_usuarios);

mysql_select_db($database_conexion1, $conexion1);
$query_ver = "SELECT * FROM usuario ORDER BY id_usuario ASC";
$ver = mysql_query($query_ver, $conexion1) or die(mysql_error());
$row_ver = mysql_fetch_assoc($ver);
$totalRows_ver = mysql_num_rows($ver);

mysql_select_db($database_conexion1, $conexion1);
$query_ver_tipo_user = "SELECT * FROM tipo_user";
$ver_tipo_user = mysql_query($query_ver_tipo_user, $conexion1) or die(mysql_error());
$row_ver_tipo_user = mysql_fetch_assoc($ver_tipo_user);
$totalRows_ver_tipo_user = mysql_num_rows($ver_tipo_user);

mysql_select_db($database_conexion1, $conexion1);
$campo=$_POST['buscar'];
$cadena=$_POST['cadena'];
$query_ver = "SELECT * FROM usuario where $campo like '%$cadena%' ORDER BY id_usuario ASC";
$ver = mysql_query($query_ver, $conexion1) or die(mysql_error());
$row_ver = mysql_fetch_assoc($ver);
$totalRows_ver = mysql_num_rows($ver);

session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.Estilo7 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
	font-style: italic;
	color: #000066;
	font-size: 11px;
}
.Estilo31 {font-size: 12px}
.Estilo32 {
	font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
	color: #000066;
	font-size: 12px;
}
.Estilo33 {font-size: 14px; font-family: "Agency FB", Algerian, Arial, "Arial Black", "Arial Narrow", "Arial Rounded MT Bold", "Arial Unicode MS", "Baskerville Old Face", "Bauhaus 93", "Bell MT", "Berlin Sans FB", "Berlin Sans FB Demi", "Bernard MT Condensed", "Blackadder ITC", "Bodoni MT", "Bodoni MT Black", "Bodoni MT Condensed", "Bodoni MT Poster Compressed", "Book Antiqua", "Bookman Old Style", "Century Gothic", "Comic Sans MS"; }
.Estilo34 {
	color: #000066;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
.Estilo35 {
	font-size: 14px;
	font-family: Arial, Helvetica, sans-serif;
	color: #000066;
	font-weight: bold;
}
.Estilo41 {color: #000066}
.Estilo42 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;
}
.Estilo48 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 18px;
	color: #000066;
}
.Estilo50 {
	font-size: 12px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #000066;
}
.Estilo51 {
	font-size: 11px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #000066;
}
.Estilo52 {font-family: Arial, Helvetica, sans-serif}
.Estilo53 {
	font-family: Georgia, "Times New Roman", Times, serif;
	font-weight: bold;
	font-size: 12px;
	color: #000066;
}
.Estilo60 {font-size: 11px; font-family: Arial, Helvetica, sans-serif; }
.Estilo61 {font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #000099;}
.Estilo62 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; }
.Estilo47 {font-family: Verdana, Arial, Helvetica, sans-serif}
.Estilo67 {	color: #990000;
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
.Estilo68 {color: #000066; font-family: Verdana, Arial, Helvetica, sans-serif; }
-->
</style>
</head>

<body>
<table width="737" border="2" align="center" cellspacing="3">
  <tr bgcolor="#1B3781">
    <td height="23" bordercolor="#FFFFFF"><img src="logo_acyc.gif" width="101" height="80"><img src="index_r1_c2.gif" width="626" height="80"></td>
  </tr>
  <tr bgcolor="#CCCCCC">
    <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="727" border="0" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
        <tr>
          <td width="452"><span class="Estilo50"><?php echo $row_usuario_usuarios['nombre_usuario']; ?></span></td>
          <td width="434"><div align="right"><a href="<?php echo $logoutAction ?>" class="Estilo51">Cerrar Sesi&oacute;n</a></div></td>
        </tr>
    </table></td>
  </tr>
  <tr bgcolor="#999999">
    <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo48">LISTADO DE USUARIOS </div></td>
  </tr>
  <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
    <td height="23" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><form action="usuarios.php" method="post" name="form2" class="Estilo41" id="form2">
      <span class="estilos"><span class="Estilo42">Buscar por: Login
      <input  <?php if (!(strcmp($_POST['buscar'],"usuario"))) {echo "CHECKED";} ?> name="buscar" type="radio" value="usuario" />
        Nombre
        <input  <?php if (!(strcmp($_POST['buscar'],"nombre_usuario"))) {echo "CHECKED";} ?> name="buscar" type="radio" value="nombre_usuario" />
             </span>
             <span class="Estilo52">
             <input name="cadena" type="text" class="caja" id="cadena" value="" />
             <input name="Submit3" type="submit" class="caja" value="Buscar" /> 
             <a href="usuarios.php" class="Estilo53">Ver Todos</a>        </span>
    </form></td>
  </tr>
  <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
    <td height="55" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left">
      <table width="719" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
        <tr bgcolor="#CCCCCC">
          <td width="86" bordercolor="#FFFFFF" bgcolor="#E9E9E9"><div align="center" class="Estilo31 Estilo32">N&ordm;</div></td>
          <td width="97" bordercolor="#FFFFFF" bgcolor="#E9E9E9"><div align="center" class="Estilo33 Estilo34"><strong>LOGIN</strong></div></td>
          <td width="121" bordercolor="#FFFFFF" bgcolor="#E9E9E9"><div align="center" class="Estilo35 Estilo62">CONTRASE&Ntilde;A</div></td>
          <td width="191" bordercolor="#FFFFFF" bgcolor="#E9E9E9"><div align="center" class="Estilo32">NOMBRE</div></td>
          <td width="99" bordercolor="#FFFFFF" bgcolor="#E9E9E9"><div align="center" class="Estilo32">Tipo</div></td>
          <td width="33" bordercolor="#FFFFFF" bgcolor="#E9E9E9"><div align="center" class="Estilo32">Editar</div></td>
          <td width="54" bordercolor="#FFFFFF" bgcolor="#E9E9E9"><div align="center" class="Estilo62"><span class="Estilo41 "><strong>Eliminar</strong></span></div></td>
        </tr>
        <?php do { ?>
        <tr bgcolor="#CCCCCC">
            <td bordercolor="#FFFFFF" bgcolor="#E9E9E9"><div align="center" class="Estilo62"><?php echo $row_ver['id_usuario']; ?></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#E9E9E9"><span class="Estilo62"><?php echo $row_ver['usuario']; ?></span></td>
            <td bordercolor="#FFFFFF" bgcolor="#E9E9E9"><span class="Estilo62"><?php echo $row_ver['clave_usuario']; ?></span></td>
            <td bordercolor="#FFFFFF" bgcolor="#E9E9E9"><span class="Estilo62"><?php echo $row_ver['nombre_usuario']; ?></span></td>
            <td bordercolor="#FFFFFF" bgcolor="#E9E9E9"><div align="center" class="Estilo62">
              <select name="tipo_usuario" disabled="disabled" id="tipo_usuario">
                <?php
do {  
?>
                <option value="<?php echo $row_ver_tipo_user['id_tipo']?>"<?php if (!(strcmp($row_ver_tipo_user['id_tipo'], $row_ver['tipo_usuario']))) {echo "SELECTED";} ?>><?php echo $row_ver_tipo_user['nombre_tipo']?></option>
                <?php
} while ($row_ver_tipo_user = mysql_fetch_assoc($ver_tipo_user));
  $rows = mysql_num_rows($ver_tipo_user);
  if($rows > 0) {
      mysql_data_seek($ver_tipo_user, 0);
	  $row_ver_tipo_user = mysql_fetch_assoc($ver_tipo_user);
  }
?>
              </select>
            </div></td>
            <td bordercolor="#FFFFFF" bgcolor="#E9E9E9"><div align="center" class="Estilo62"><a href="usuario_editar.php?id_usuario=<?php echo $row_ver['id_usuario']; ?>"><img src="hoja.gif" width="18" height="18" border="0"></a></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#E9E9E9"><div align="center" class="Estilo62"><a href="borrado_usuario.php?id_usuario=<?php echo $row_ver['id_usuario']; ?>"><img src="eliminar.gif" width="18" height="18" border="0"></a></div></td>
        </tr>
        <?php } while ($row_ver = mysql_fetch_assoc($ver)); ?>
      </table>
    </div></td>
  </tr>
  
  <tr bordercolor="#FFFFFF" bgcolor="#999999">
    <td height="26" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="727" border="0" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <tr>
        <td width="119"><div align="left" class="Estilo61 Estilo47">
            <div align="center"><a href="menu.php" class="Estilo68">Menu</a></div>
        </div></td>
        <td width="234"><div align="center" class="Estilo61 Estilo47"><a href="usuario_nuevo.php" class="Estilo67">*Adicionar Usuario </a></div>
            <div align="right" class="Estilo61 Estilo47">
              <div align="right"></div>
            </div></td>
        <td width="196"><div align="right" class="Estilo61 Estilo47">
            <div align="center"><a href="Administrador.php" class="Estilo68">Administrador</a></div>
        </div></td>
        <td width="155"><div align="right"><img src="firma3.bmp"></div></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($usuario_usuarios);

mysql_free_result($ver);

mysql_free_result($ver_tipo_user);
?>
