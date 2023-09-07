<?php
   require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
   require (ROOT_BBDD); 
?>
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

  /*Cuando un visitante se registra en este sitio, la variable de sesión MM_Username igual a su nombre de usuario.
   / / Por lo tanto, sabemos que un usuario no se registra en el caso de que la variable de sesión está en blanco.*/
  if (!empty($UserName)) { 
   //Además de estar conectado, se puede restringir el acceso sólo a ciertos usuarios basados ??en una identificación establecida al iniciar la sesión.
     // Analizar las cadenas en las matrices.
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    //O bien, puede restringir el acceso sólo a determinados usuarios en base a su nombre de usuario. 
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

$conexion = new ApptivaDB();

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario); 

 
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>SISADGE AC &amp; CIA</title>
<link rel="StyleSheet" href="css/general.css" type="text/css">
</head>
<body oncontextmenu="return false">
  <table id="tabla_formato"><tr><td>
    <div id="cabecera_formato"><div class="menu_formato"><ul>  
       <li><?php echo $row_usuario['nombre_usuario']; ?></li>
       <li><a href="perfil_cliente_add.php" target="_top">ADD CLIENTE</a></li>       
       <li><a href="menu.php" target="_top">COMERCIAL</a></li>
       <li><a href="menu.php" target="_top">MENU</a></li>
       <li><a href="<?php echo $logoutAction ?>" target="_top">SALIR</a></li>  
      </ul></div></div>
   </td></tr></table>
 <tr><td>  
<table id="tabla_formato">
    <tr>
      <td width="30%" id="codigo_formato">CODIGO: R1 - F02</td>
      <td width="45%" id="titulo_formato">LISTADO MAESTRO DE CLIENTES </td>
      <td width="25%" id="codigo_formato">VERSION: 1 </td>
    </tr>
    <tr>        
    </tr>    
</table>
<table id="tabla_borde_top">
  <tr>
    <td class="Estilo2">NIT</td>
    <td height="16" class="Estilo1">RAZON SOCIAL</td>
    <td class="Estilo1">CONTACTO</td>
    <td class="Estilo1">DIRECCION</td>
    <td class="Estilo2">PAIS/CIUDAD</td>
    <td class="Estilo2">TELEFONO</td>
    <td class="Estilo2">FAX</td>
    <td class="Estilo5">REF</td>
    <td class="Estilo5">ESTADO</td>
    <td class="Estilo5">DATOS ADJ.</td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_close($conexion1);
?>
