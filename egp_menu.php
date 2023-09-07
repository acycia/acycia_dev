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
$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);
?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/imageMenu.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/mootools.js"></script>
<script type="text/javascript" src="js/imageMenu.js"></script>
</head>
<body oncontextmenu="return false" onkeydown="return false">
<div class="container-fluid" id="divconten"> 
        <div class="row">
         <div class="col-md-8" ><img src="images/cabecera.jpg" style="width: 100%;margin:10px 0px 10px;">
          </div>
           <div class="col-md-4"> 
            <div class="menu1">
               <ul>
               <li><?php echo $row_usuario['nombre_usuario']; ?></li>
               <li><a href="comercial.php">COMERCIAL </a></li>
               <li><a href="menu.php">MENU</a></li>
               <li><a href="<?php echo $logoutAction ?>">SALIR</a></li>
               </ul>
            </div>
          </div>
        </div>
        <div class="row">
         <div class="cos-xl-12 col-sm-6 col-md-12" align="center">ESPECIFICACIONES GENERAL DEL PRODUCTO
          </div>   
           </div>      
             <div class="row">                    
              <div class="col-xl-6 col-sm-12">
               <div id="kwick">
                  <ul class="kwicks">
                  <li><a class="kwick2 opt9" href="egp_bolsa.php"></a></li>
                  <li><a class="kwick2 opt10" href="egp_lamina.php"></a></li>
                  <li><a class="kwick2 opt11" href="acceso.php"></a></li>
                  <li><a class="kwick2 opt12" href="acceso.php"></a></li>
                  </ul>
               </div>
              </div>            
            </div>           
  </div> 
        <script type="text/javascript">
var myMenu = new ImageMenu($$('#kwick .kwick2'),{openWidth:200});</script>
</body>
</html>
<?php
mysql_free_result($usuario);
?>