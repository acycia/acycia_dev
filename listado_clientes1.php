<?php require_once('Connections/conexion1.php'); ?>
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
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE cliente SET estado_c=%s WHERE nit_c=%s",
                       GetSQLValueString($_POST['estado_c'], "text"),
                       GetSQLValueString($_POST['nit_c'], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());
}

$colname_usuario_listado_clientes = "1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario_listado_clientes = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario_listado_clientes = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario_listado_clientes);
$usuario_listado_clientes = mysql_query($query_usuario_listado_clientes, $conexion1) or die(mysql_error());
$row_usuario_listado_clientes = mysql_fetch_assoc($usuario_listado_clientes);
$totalRows_usuario_listado_clientes = mysql_num_rows($usuario_listado_clientes);

mysql_select_db($database_conexion1, $conexion1);
$query_ver_clientes = "SELECT * FROM cliente ORDER BY nombre_c ASC";
$ver_clientes = mysql_query($query_ver_clientes, $conexion1) or die(mysql_error());
$row_ver_clientes = mysql_fetch_assoc($ver_clientes);
$totalRows_ver_clientes = mysql_num_rows($ver_clientes);

mysql_select_db($database_conexion1, $conexion1);
$campo=$_POST['buscar'];
$cadena=$_POST['cadena'];
$query_ver_clientes ="SELECT * FROM cliente WHERE $campo LIKE '%$cadena%'  ";
//"SELECT * FROM cliente where $campo like '%$cadena%' ORDER BY nombre_c ASC";
$ver_clientes = mysql_query($query_ver_clientes, $conexion1) or die(mysql_error());
$row_ver_clientes = mysql_fetch_assoc($ver_clientes);
$totalRows_ver_clientes = mysql_num_rows($ver_clientes);
 
session_start();
 ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0014)about:internet -->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>SISADGE AC &amp; CIA</title>
<link rel="StyleSheet" href="css/general.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--Fireworks 8 Dreamweaver 8 target.  Created Tue Jun 06 20:42:04 GMT-0500 2006-->

<style type="text/css">
<!--
a {
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
a:link {
	text-decoration: underline;
	color: #FFFFFF;
}
a:visited {
	text-decoration: underline;
	color: #FFFFFF;
}
a:hover {
	text-decoration: none;
	color: #FFFFFF;
}
a:active {
	text-decoration: underline;
	color: #FFFFFF;
}
body,td,th {
	color: #FFFFFF;
}
-->
</style>

<style type="text/css">
<!--
.Estilo1 {color: #000000}
.Estilo32 {color: #000066}
.Estilo38 {
	font-family: "Agency FB", Algerian, Arial, "Arial Black", "Arial Narrow", "Arial Rounded MT Bold", "Arial Unicode MS", "Baskerville Old Face", "Bauhaus 93", "Bell MT", "Berlin Sans FB", "Berlin Sans FB Demi", "Bernard MT Condensed", "Blackadder ITC", "Bodoni MT", "Bodoni MT Black", "Bodoni MT Condensed", "Bodoni MT Poster Compressed", "Book Antiqua", "Bookman Old Style", "Century Gothic", "Comic Sans MS";
	font-weight: bold;
}
.Estilo40 {color: #000000; font-size: 12px; }
.Estilo65 {font-size: 12px; font-family: Verdana, Arial, Helvetica, sans-serif; color: #000066;}
.Estilo66 {
	font-size: 11px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
.Estilo68 {
	font-size: 12px;
	font-weight: bold;
	font-family: Arial, Helvetica, sans-serif;
}
.Estilo69 {
	font-size: 12px;
	color: #000066;
	font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
}
.Estilo70 {font-size: 12px; color: #000066; font-family: Arial, Helvetica, sans-serif; }
.Estilo72 {font-size: 11px; font-family: Arial, Helvetica, sans-serif; }
.Estilo79 {font-size: 18px; font-family: Verdana, Arial, Helvetica, sans-serif; color: #000066; }
.Estilo80 {font-size: 11px}
.Estilo82 {font-size: 12px; font-family: Arial, Helvetica, sans-serif; }
.Estilo83 {font-size: 12px; font-weight: bold; font-family: Arial, Helvetica, sans-serif; color: #2F2679; }
-->
</style>
</head>
<body bgcolor="#ffffff">
 
<table width="100" border="2" align="center" cellpadding="0" cellspacing="0">
<!-- fwtable fwsrc="Sin título-1.png" fwbase="index.gif" fwstyle="Dreamweaver" fwdocid = "1537494765" fwnested="0" -->
  

  <tr bgcolor="#1B3781">
    <td height="22" bordercolor="#FFFFFF"><img src="/logo_acyc.gif" width="101" height="80" /><img src="index_r1_c2.gif" width="626" height="80" /></td>
  </tr>
  <tr>
    <td height="22" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="858" border="0" cellspacing="3">
      <tr>
        <td width="760"><span class="Estilo65"><?php echo $row_usuario_listado_clientes['nombre_usuario']; ?></span></td>
        <td width="85" bgcolor="#999999"><div align="right" class="Estilo66">
          <div align="center"><a href="<?php echo $logoutAction ?>" class="Estilo32">Cerrar Sesion</a></div>
        </div></td>
      </tr>
    </table></td>
  </tr>
  <tr>
   <td height="144" valign="top"><table width="100%" border="0" align="center" class="forumline">
       <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC" border="3">
         <td colspan="11" bgcolor="#FFFFFF" class="Estilo79">LISTADO MAESTRO DE CLIENTES </td>
         <td colspan="2" bgcolor="#FFFFFF" class="currentpage Estilo32 Estilo70"><div align="right"><img src="firma3.bmp" /></div></td>
        </tr>
       <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC" border="3">
         <td colspan="11" bordercolor="#FFFFFF" bgcolor="#EAEAEA" class="currentpage"><form action="listado_clientes1.php" method="post" name="form2" class="Estilo32" id="form2">
             <span class="estilos"><span class="Estilo82">Buscar por Nombre
             <input  <?php if (!(strcmp($_POST['buscar'],"nombre_c"))) {echo "CHECKED";} ?> name="buscar" type="radio" value="nombre_c" />
        Contacto
        <input  <?php if (!(strcmp($_POST['buscar'],"contacto_c"))) {echo "CHECKED";} ?> name="buscar" type="radio" value="contacto_c" />
        Asesor</span>
        <input name="buscar" type="radio" value="asesor_comercial_c" />
             </span>
             <input name="cadena" type="text" class="caja" id="cadena" value="" />
             <input name="Submit3" type="submit" class="caja" value="Buscar" /> 
             <a href="listado_clientes.php" class="Estilo83">Ver Todos</a>
         </form></td>
         <td bgcolor="#999999" class="currentpage Estilo32 Estilo70"><div align="center" class="Estilo71 Estilo80"><a href="comercial.php">Gestión Comercial</a></div></td>
         <td bgcolor="#999999" class="currentpage Estilo32 Estilo70"><div align="center" class="Estilo71 Estilo80"><a href="menu.php">Menu</a></div></td>
       </tr>
       <tr bordercolor="#FFFFFF" borde="3">
         <td width="7%" bordercolor="#FFFFFF" bgcolor="#ffffff" class="currentpage"><div align="center" class="Estilo69">Estado</div></td>
         <td width="150" bordercolor="#FFFFFF" bgcolor="#ffffff" class="currentpage"><div align="center" class="Estilo70"><strong>Razón Social</strong></div></td>
         <td width="100" bordercolor="#FFFFFF" bgcolor="#ffffff" class="currentpage Estilo32"><div align="center" class="Estilo68">Nit</div></td>
         <td width="150" bordercolor="#FFFFFF" bgcolor="#ffffff" class="currentpage"><div align="center" class="Estilo70"><strong>Contacto Comercial</strong></div></td>
         <td width="100" bordercolor="#FFFFFF" bgcolor="#ffffff" class="currentpage"><div align="center" class="Estilo70"><strong>Dirección</strong></div></td>
         <td width="80" bordercolor="#FFFFFF" bgcolor="#ffffff" class="currentpage"><div align="center" class="Estilo70"><strong>Pais</strong></div></td>
         <td width="100" bordercolor="#FFFFFF" bgcolor="#ffffff" class="currentpage"><div align="center" class="Estilo70"><strong>Provincia</strong></div></td>
         <td width="100" bordercolor="#FFFFFF" bgcolor="#ffffff" class="currentpage"><div align="center" class="Estilo70"><strong>Ciudad</strong></div></td>
         <td width="80" bordercolor="#FFFFFF" bgcolor="#ffffff" class="currentpage"><div align="center" class="Estilo70"><strong>Telefono</strong></div></td>
         <td width="80" bordercolor="#FFFFFF" bgcolor="#ffffff" class="currentpage"><div align="center" class="Estilo70"><strong>Fax</strong></div></td>
         <td width="100" bordercolor="#FFFFFF" bgcolor="#ffffff" class="currentpage"><div align="center" class="Estilo70"><strong>Email</strong></div></td>
         <td width="100" bordercolor="#FFFFFF" bgcolor="#ffffff" class="currentpage"><div align="center" class="Estilo70"><strong>Asesor</strong></div></td>
         <td width="4%" bordercolor="#FFFFFF" bgcolor="#FFFFFF" class="currentpage"><div align="center" class="Estilo69">Acción</div></td>
       </tr>
       <?php 
	  $i=0;
	  
?>
       <?php do { ?>
       <tr <?php if ($i%2==0) {?> style="background:#efefef" <?php }else {?>style="background:#dee3e7" <?php } 
	  $i++;		  ?>>
          <td bordercolor="#FFFFFF" class="currentpage Estilo1">            
        <form name="form1" id="form1" method="POST" action="<?php echo $editFormAction; ?>">
            <p align="center"><span class="Estilo38">
              <select name="estado_c">
                  <option value="Activo"  <?php if (!(strcmp("Activo", $row_ver_clientes['estado_c']))) {echo "SELECTED";} ?>>Activo</option>
                  <option value="Retirado"  <?php if (!(strcmp("Retirado", $row_ver_clientes['estado_c']))) {echo "SELECTED";} ?>>Retirado</option>
                  <option value="Pendiente"  <?php if (!(strcmp("Pendiente", $row_ver_clientes['estado_c']))) {echo "SELECTED";} ?>>Pendiente</option>
              </select>
            </span><span class="Estilo38">
                <input type="submit" value="ok">
                <input name="nit_c" type="hidden" id="nit_c" value="<?php echo $row_ver_clientes['nit_c']; ?>" />
                            </span>
                <input type="hidden" name="MM_update" value="form1">          
        </p>
                  </form></td>
    <td width="150" bordercolor="#FFFFFF" class="currentpage Estilo1"><div align="center" class="Estilo72"><?php echo $row_ver_clientes['nombre_c']; ?></div></td>
          <td width="100" bordercolor="#FFFFFF" class="currentpage Estilo1"><div align="center" class="Estilo72"><?php echo $row_ver_clientes['nit_c']; ?></div></td>
          <td width="150" bordercolor="#FFFFFF" class="currentpage Estilo1"><div align="center" class="Estilo72"><?php echo $row_ver_clientes['contacto_c']; ?></div></td>
          <td width="100" bordercolor="#FFFFFF" class="currentpage Estilo1"><div align="center" class="Estilo72"><?php echo $row_ver_clientes['direccion_c']; ?></div></td>
          <td width="80" bordercolor="#FFFFFF" class="currentpage Estilo1"><div align="center" class="Estilo72"><?php echo $row_ver_clientes['cod_pais_c']; ?></div></td>
          <td width="100" bordercolor="#FFFFFF" class="currentpage Estilo1"><div align="center" class="Estilo72"><?php echo $row_ver_clientes['cod_dpto_c']; ?></div></td>
          <td width="100" bordercolor="#FFFFFF" class="currentpage Estilo1"><div align="center" class="Estilo72"><?php echo $row_ver_clientes['cod_ciudad_c']; ?></div></td>
          <td width="80" bordercolor="#FFFFFF" class="currentpage Estilo1"><div align="center" class="Estilo72"><?php echo $row_ver_clientes['telefono_c']; ?></div></td>
          <td width="80" bordercolor="#FFFFFF" class="currentpage Estilo1"><div align="center" class="Estilo72"><?php echo $row_ver_clientes['fax_c']; ?></div></td>
          <td width="100" bordercolor="#FFFFFF" class="currentpage Estilo1"><div align="center" class="Estilo72"><?php echo $row_ver_clientes['email_comercial_c']; ?></div></td>
          <td width="100" bordercolor="#FFFFFF" class="currentpage Estilo1"><div align="center" class="Estilo72"><?php echo $row_ver_clientes['asesor_comercial_c']; ?></div></td>
          <td bordercolor="#FFFFFF" bgcolor="#999999" class="currentpage"><div align="left"><span class="Estilo40"><a href="perfil_cliente_editar.php?nit_c=<?php echo $row_ver_clientes['nit_c']; ?>">Editar</a><br />
              <a href="borrado_cliente.php?nit_c=<?php echo $row_ver_clientes['nit_c']; ?>">Eliminar</a><br />
            <a href="imprimir_clientes.php?nit_c=<?php echo $row_ver_clientes['nit_c']; ?>" target="_blank">Imprimir</a><br />
                <a href="encuesta_detalle.php?nit_c=<?php echo $row_ver_clientes['nit_c']; ?>">Encuesta</a><br>
                <a href="quejas_detalle.php?nit_c=<?php echo $row_ver_clientes['nit_c']; ?>">Quejas</a></span></a>
                </p>
          </div></td>
       </tr>
       <?php } while ($row_ver_clientes = mysql_fetch_assoc($ver_clientes)); ?>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($usuario_listado_clientes);

mysql_free_result($ver_clientes);

mysql_free_result($menu);

mysql_close($conexion1);
?>
