<?php require_once('Connections/conexion1.php'); ?><?php
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
$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);
//IMPRIME MEZCLAS
/*$colname_ver_pm = "1";
if (isset($_GET['id_pm'])) 
{
  $colname_ver_pm = (get_magic_quotes_gpc()) ? $_GET['id_pm'] : addslashes($_GET['id_pm']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_pm = sprintf("SELECT * FROM Tbl_produccion_mezclas WHERE id_pm = %s", $colname_ver_pm);
$ver_pm = mysql_query($query_ver_pm, $conexion1) or die(mysql_error());
$row_ver_pm  = mysql_fetch_assoc($ver_pm);
$totalRows_ver_pm=mysql_num_rows($ver_pm);*/

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="css/vista.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/vista.js"></script>
<script type="text/javascript" src="js/formato.js"></script>
<title>SISADGE AC & CIA</title>
</head>
<body>
<div align="center">
<table id="tablainterna">
  <tr>    
     <td colspan="7" id="principal">PROCESO EXTRUSION MEZCLAS</td>
  </tr>
  <tr>
    <td rowspan="6" id="fondo"><img src="images/logoacyc.jpg"/></td>
    <td colspan="6" id="noprint"><a href="vista.php?id_ref=<?php echo $row['id_ref']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>"><img src="images/hoja.gif" alt="VISTA IMPRESION" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" border="0"/><img src="images/impresor.gif" onclick="window.print();" style="cursor:hand;" alt="INPRIMIR"title="INPRIMIR" /></a></td>
    </tr>
  <tr>
    <td colspan="3" id="subppal2">FECHA DE INGRESO </td>
    <td colspan="3" id="subppal2">RESPONSABLE</td>
    </tr>
  <tr>
    <td colspan="3" id="fuente2"><?php echo $row_ver_pm['fecha_registro1_ref']; ?></td>
    <td colspan="3" nowrap id="fuente2"><?php echo $row_ver_pm['registro1_ref']; ?></td>
    </tr>
  <tr>
    <td colspan="3" id="subppal2">&nbsp;</td>
    <td colspan="3" id="subppal2">&nbsp;</td>
    </tr>
  <tr>
    <td colspan="3" nowrap id="fuente2">&nbsp;</td>
    <td colspan="3" id="fuente2">&nbsp;</td>
    </tr>
  <tr>
    <td colspan="6" id="fondo">Alguna Inquietud o Comentario : info@acycia.com </td>
    </tr>
  <tr>
    <td colspan="7" id="subppal2">&nbsp;</td>
    </tr>
  <tr>
    <td rowspan="2" id="subppal2">EXT-1 </td>
    <td colspan="2" id="subppal2">TORNILLO A</td>
    <td colspan="2" id="subppal2">TORNILLO B</td>
    <td colspan="2" id="subppal2">TORNILLO B</td>
    </tr>
  <tr>
    <td id="subppal3">Referencia</td>
    <td id="subppal2">%</td>
    <td id="subppal3">Referencia</td>
    <td id="subppal2">%</td>
    <td id="subppal3">Referencia</td>
    <td id="subppal2">%</td>
  </tr>
  <tr>
    <td id="fuente3"><?php echo $row_ver_pm['id_pm']; ?></td>
    <td id="fuente3"><?php echo $row_ver_pm['id_pm']; ?></td>
    <td id="fuente3">&nbsp;</td>
    <td id="fuente3"><?php echo $row_ver_pm['id_pm']; ?></td>
    <td id="fuente3">&nbsp;</td>
    <td id="fuente3"><?php echo $row_ver_pm['id_pm']; ?></td>
    <td id="fuente3">&nbsp;</td>
  </tr> 
<tr>
    <td id="fuente3"><?php echo $row_ver_pm['id_pm']; ?></td>
    <td id="fuente3"><?php echo $row_ver_pm['id_pm']; ?></td>
    <td id="fuente3">&nbsp;</td>
    <td id="fuente3"><?php echo $row_ver_pm['id_pm']; ?></td>
    <td id="fuente3">&nbsp;</td>
    <td id="fuente3"><?php echo $row_ver_pm['id_pm']; ?></td>
    <td id="fuente3">&nbsp;</td>
  </tr>
  <tr>
    <td id="fuente3"><?php echo $row_ver_pm['id_pm']; ?></td>
    <td id="fuente3"><?php echo $row_ver_pm['id_pm']; ?></td>
    <td id="fuente3">&nbsp;</td>
    <td id="fuente3"><?php echo $row_ver_pm['id_pm']; ?></td>
    <td id="fuente3">&nbsp;</td>
    <td id="fuente3"><?php echo $row_ver_pm['id_pm']; ?></td>
    <td id="fuente3">&nbsp;</td>
  </tr>
  <tr>
    <td id="fuente3"><?php echo $row_ver_pm['id_pm']; ?></td>
    <td id="fuente3"><?php echo $row_ver_pm['id_pm']; ?></td>
    <td id="fuente3">&nbsp;</td>
    <td id="fuente3"><?php echo $row_ver_pm['id_pm']; ?></td>
    <td id="fuente3">&nbsp;</td>
    <td id="fuente3"><?php echo $row_ver_pm['id_pm']; ?></td>
    <td id="fuente3">&nbsp;</td>
  </tr>
  <tr>
    <td id="fuente3"><?php echo $row_ver_pm['id_pm']; ?></td>
    <td id="fuente3"><?php echo $row_ver_pm['id_pm']; ?></td>
    <td id="fuente3">&nbsp;</td>
    <td id="fuente3"><?php echo $row_ver_pm['id_pm']; ?></td>
    <td id="fuente3">&nbsp;</td>
    <td id="fuente3"><?php echo $row_ver_pm['id_pm']; ?></td>
    <td id="fuente3">&nbsp;</td>
  </tr>     
  <tr>
    <td colspan="7" id="fondo">&nbsp;</td>
  </tr>
<tr>
    <td colspan="7" id="fuente3">OBSERVACIONES</td>
  </tr>  
  <tr>
    <td colspan="4" id="subppal2">FECHA ULTIMA MODIFICACION </td>
    <td colspan="3" id="subppal2">RESPONSABLE ULTIMA MODIFICACION </td>
    </tr>
  <tr>
    <td colspan="4" id="fuente2"><?php echo $row_ver_pm['fecha_registro2_ref']; ?></td>
    <td colspan="3" id="fuente2"><?php echo $row_ver_pm['registro2_ref']; ?></td>
    </tr>
</table>
</div>
</body>
</html>
<?php

mysql_free_result($usuario);

?>
