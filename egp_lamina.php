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
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
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
}

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

mysql_select_db($database_conexion1, $conexion1);
$query_egl = "SELECT * FROM egl ORDER BY n_egl DESC";
$egl = mysql_query($query_egl, $conexion1) or die(mysql_error());
$row_egl = mysql_fetch_assoc($egl);
$totalRows_egl = mysql_num_rows($egl);
?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/listado.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/listado.js"></script>
</head>
<body oncontextmenu="return false">
<table id="tabla_1"><tr><td>
  <div id="cabecera2"><div class="menu2"><ul>
       <li><?php echo $row_usuario['nombre_usuario']; ?></li>
       <li><a href="egp_lamina_add.php">ADD EGL</a></li>
       <li><a href="egp_menu.php">MENU EGP</a></li>
       <li><a href="comercial.php">COMERCIAL</a></li> 
       <li><a href="<?php echo $logoutAction ?>">SALIR</a></li>
  </ul></div></div>
</td></tr>
<tr><td>
     <form action="delete_listado.php" method="get" name="seleccion">
       <table id="tabla_1">
         <tr>
           <td><input name="borrado" type="hidden" id="borrado" value="21" />             <input name="" type="submit" value="Eliminar"/></td>
           <td id="titulo_1">LISTADO DE ESPECIFICACION GENERAL DE LAMINAS (EGL)</td>
         </tr>
		 <?php $id=$_GET['id']; if($id!='') { ?>
         <tr>
           <td colspan="2"><?php if($id>='1') { ?><div id="acceso_1"><?php echo "ELIMINACION COMPLETA"; ?> </div><?php } if($id == '0') { ?><div id="numero_1"><?php echo "SELECCIONE PARA ELIMINAR"; ?></div><?php } ?></td>
          </tr>
         <?php } ?> 
         <tr>
           <td colspan="2" id="tr_1">
             <table id="tabla_2">
               <tr id="tr_2">
                 <td id="subtitulo_2"><input name="chulo1" type="checkbox" onClick="if(seleccion.chulo1.checked) { seleccionar_todo() } else{ deseleccionar_todo() } "/></td>
                 <td id="subtitulo_2">EGL N&deg;</td>
                 <td id="subtitulo_2">ESTRUCTURA</td>
                 <td id="subtitulo_2">ANCHO</td>
                 <td id="subtitulo_2">CALIBRE</td>
                 <td id="subtitulo_2">PESO</td>
                 <td id="subtitulo_2">DIAMETRO</td>
                 <td id="subtitulo_2">REGISTRO</td>
                 <td id="subtitulo_2">ESTADO</td>
                 <td id="subtitulo_2">REF</td>
               </tr>
               <?php do { ?>               
                 <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
                   <td id="dato_2"><input name="borrar[]" type="checkbox" value="<?php echo $row_egl['n_egl']; ?>" /></td>
                   <td id="dato_3"><a href="egp_lamina_vista.php?n_egl=<?php echo $row_egl['n_egl']; ?>" target="_top" style="text-decoration:none; color:#000000 "><?php echo $row_egl['n_egl']; ?></a></td>
                   <td id="dato_1"><a href="egp_lamina_vista.php?n_egl=<?php echo $row_egl['n_egl']; ?>" target="_top" style="text-decoration:none; color:#000000 "><?php echo $row_egl['estructura_egl']; ?></a></td>
                   <td id="dato_3"><a href="egp_lamina_vista.php?n_egl=<?php echo $row_egl['n_egl']; ?>" target="_top" style="text-decoration:none; color:#000000 "><?php echo $row_egl['ancho_egl']; ?></a></td>
                   <td id="dato_3"><a href="egp_lamina_vista.php?n_egl=<?php echo $row_egl['n_egl']; ?>" target="_top" style="text-decoration:none; color:#000000 "><?php echo $row_egl['calibre_egl']; ?></a></td>
                   <td id="dato_3"><a href="egp_lamina_vista.php?n_egl=<?php echo $row_egl['n_egl']; ?>" target="_top" style="text-decoration:none; color:#000000 "><?php echo $row_egl['peso_egl']; ?></a></td>
                   <td id="dato_3"><a href="egp_lamina_vista.php?n_egl=<?php echo $row_egl['n_egl']; ?>" target="_top" style="text-decoration:none; color:#000000 "><?php echo $row_egl['diametro_rollo_egl']; ?></a></td>
                   <td id="dato_2"><a href="egp_lamina_vista.php?n_egl=<?php echo $row_egl['n_egl']; ?>" target="_top" style="text-decoration:none; color:#000000 "><?php echo $row_egl['fecha_egl']; ?> - <?php echo $row_egl['responsable_egl']; ?></a></td>
                   <td id="dato_1"><?php $estado=$row_egl['estado_egl']; if($estado == '0') { echo "Pendiente"; } if($estado == '1') { echo "Aceptada"; } ?></td>
                   <td id="dato_2">&nbsp;</td>
                 </tr>
                 <?php } while ($row_egl = mysql_fetch_assoc($egl)); ?>
             </table>
           </td>
         </tr>
       </table>
     </form>
</td></tr>
</table>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($egl);
?>
