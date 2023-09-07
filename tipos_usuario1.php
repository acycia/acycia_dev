<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
?>
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

$conexion = new ApptivaDB();

$colname_usuario_usuarios = "1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario_usuarios = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
$row_usuario_usuarios = $conexion->buscar('usuario','usuario',$colname_usuario); 

mysql_select_db($database_conexion1, $conexion1);
$query_ver_tipo_user = "SELECT * FROM tipo_user ORDER BY id_tipo ASC";
$ver_tipo_user = mysql_query($query_ver_tipo_user, $conexion1) or die(mysql_error());
$row_ver_tipo_user = mysql_fetch_assoc($ver_tipo_user);
$totalRows_ver_tipo_user = mysql_num_rows($ver_tipo_user);

mysql_select_db($database_conexion1, $conexion1);
$campo=$_POST['buscar'];
$cadena=$_POST['cadena'];
$query_ver_tipo_user = "SELECT * FROM tipo_user where $campo like '%$cadena%' ORDER BY id_tipo ASC";
$ver_tipo_user = mysql_query($query_ver_tipo_user, $conexion1) or die(mysql_error());
$row_ver_tipo_user = mysql_fetch_assoc($ver_tipo_user);
$totalRows_ver_tipo_user = mysql_num_rows($ver_tipo_user)

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

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

<!-- css Bootstrap-->
<link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

<style type="text/css">
<!--
.Estilo28 {	font-size: 14px;
	font-family: Georgia, "Times New Roman", Times, serif;
	font-weight: bold;
	color: #000066;
}
.Estilo7 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
	font-style: italic;
	color: #000066;
	font-size: 11px;
}
.Estilo30 {font-size: 18px}
.Estilo31 {font-size: 14px}
.Estilo32 {
	font-family: "Times New Roman", Times, serif;
	font-weight: bold;
	color: #000066;
	font-size: 12px;
}
.Estilo36 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
.Estilo41 {color: #000066}
.Estilo47 {font-family: Verdana, Arial, Helvetica, sans-serif}
.Estilo48 {
	font-size: 12px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #000066;
}
.Estilo49 {font-size: 11px; font-family: Verdana, Arial, Helvetica, sans-serif; color: #000066; }
.Estilo50 {font-size: 12px}
.Estilo51 {color: #000066; font-family: Arial, Helvetica, sans-serif; }
.Estilo52 {color: #000066; font-weight: bold; }
.Estilo55 {font-family: Arial, Helvetica, sans-serif; font-weight: bold; color: #000066; font-size: 12px; }
.Estilo59 {font-size: 11px; font-family: Arial, Helvetica, sans-serif; }
.Estilo61 {font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #000099;}
.Estilo65 {
	color: #000066;
	font-size: 11px;
	font-family: Arial, Helvetica, sans-serif;
}
.Estilo63 {color: #990000}
.Estilo64 {font-size: 11px; font-family: Verdana, Arial, Helvetica, sans-serif; }
.Estilo66 {font-size: 11px}
-->
</style>
</head>

<body>
<table width="737" height="268" border="2" align="center" cellspacing="3">
  <tr bgcolor="#1B3781">
    <td height="23" bordercolor="#FFFFFF"><img src="logo_acyc.gif" width="101" height="80"><img src="index_r1_c2.gif" width="626" height="80"></td>
  </tr>
  <tr bgcolor="#CCCCCC">
    <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="728" border="0" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
        <tr>
          <td width="358"><span class="Estilo48"><?php echo $row_usuario_usuarios['nombre_usuario']; ?></span></td>
          <td width="357"><div align="right"><a href="<?php echo $logoutAction ?>" class="Estilo49">Cerrar Sesi&oacute;n</a></div></td>
        </tr>
    </table></td>
  </tr>
  <tr bgcolor="#999999">
    <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center"><span class="Estilo28 Estilo30 Estilo47"><span class="Estilo28 Estilo47  Estilo30">LISTA DE TIPOS DE USUARIO</span></span></div></td>
  </tr>
  <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
    <td height="23" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><form action="tipos_usuario.php" method="post" name="form2" class="Estilo51" id="form2">
      <span class="Estilo50"><span class="estilos">Buscar por: Login
      <input  <?php if (!(strcmp($_POST['buscar'],"id_tipo"))) {echo "CHECKED";} ?> name="buscar" type="radio" value="id_tipo" />
        Nombre
        <input  <?php if (!(strcmp($_POST['buscar'],"nombre_tipo"))) {echo "CHECKED";} ?> name="buscar" type="radio" value="nombre_tipo" />
             </span>
             <span class="Estilo50">
             <input name="cadena" type="text" class="caja" id="cadena" value="" />
             <input name="Submit3" type="submit" class="caja" value="Buscar" /> 
             <a href="tipos_usuario.php" class="Estilo52">Ver Todos</a></span>
                        </form></td>
  </tr>
  <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
    <td height="50" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left">
      <table width="710" border="0" align="center" cellspacing="3" bordercolor="#CCCCCC" bgcolor="#CCCCCC">
        <tr bgcolor="#CCCCCC">
          <td width="100" bgcolor="#EAEAEA"><div align="center" class="Estilo31 Estilo32 Estilo36">N&ordm;</div></td>
          <td width="455" bgcolor="#EAEAEA"><div align="center" class="Estilo55">NOMBRE</div></td>
          <td width="54" bgcolor="#EAEAEA"><div align="center" class="Estilo55">EDITAR</div></td>
          <td width="64" bgcolor="#EAEAEA"><div align="center" class="Estilo36"><span class="Estilo41 "><strong>ELIMINAR</strong></span></div></td>
        </tr>
        <?php do { ?>
        <tr bgcolor="#CCCCCC">
            <td bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="center" class="Estilo36"><?php echo $row_ver_tipo_user['id_tipo']; ?></div></td>
            <td bordercolor="#CCCCCC" bgcolor="#FFFFFF"><span class="Estilo36"><?php echo $row_ver_tipo_user['nombre_tipo']; ?></span></td>
            <td bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="center"><a href="tipo_usuario_editar.php?id_tipo=<?php echo $row_ver_tipo_user['id_tipo']; ?>"><img src="hoja.gif" width="18" height="18" border="0"></a></div></td>
            <td bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="center"><a href="borrado_tipo_usuario.php?id_tipo=<?php echo $row_ver_tipo_user['id_tipo']; ?>"><img src="eliminar.gif" width="18" height="18" border="0"></a></div></td>
        </tr>
        <?php } while ($row_usuario_usuarios = mysql_fetch_assoc($usuario_usuarios)); ?>
      </table>
    </div></td>
  </tr>
  
  <tr bordercolor="#FFFFFF" bgcolor="#999999">
    <td height="29" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="726" border="0" cellspacing="3">
      <tr>
        <td width="131"><div align="left" class="Estilo64">
            <div align="center"><a href="menu.php" class="Estilo41">Menu</a></div>
        </div></td>
        <td width="166"><div align="right" class="Estilo47">
            <div align="right" class="Estilo41">
              <div align="right" class="Estilo66">
                <div align="center"><a href="Administrador.php" class="Estilo41">Administrador</a></div>
              </div>
            </div>
        </div></td>
        <td width="285"><div align="center" class="Estilo64"><a href="tipo_usuario_nuevo.php" class="Estilo63">*Adicionar Tipo de Usuario </a></div></td>
        <td width="121"><div align="right"><img src="firma3.bmp"></div></td>
      </tr>
    </table></td>
  </tr>
</table>


</body>
</html>
<?php
mysql_free_result($usuario_usuarios);

mysql_free_result($ver_tipo_user);
?>
