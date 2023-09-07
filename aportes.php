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


$conexion = new ApptivaDB();

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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO TblAportes (id_aporte,codigo_empl, cesantias_porc, interesCesantias_porc, prima_porc,salud_porc,pension_porc,vacaciones_porc,cajaCompensacion_porc,sena_porc,arl_porc,total,fecha) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
             GetSQLValueString($_POST['id_aporte'], "int"),
					   GetSQLValueString($_POST['id_aporte'], "int"),
             GetSQLValueString($_POST['cesantias_porc'], "double"),
					   GetSQLValueString($_POST['interesCesantias_porc'], "double"),
					   GetSQLValueString($_POST['prima_porc'], "double"),
					   GetSQLValueString($_POST['salud_porc'], "double"),
					   GetSQLValueString($_POST['pension_porc'], "double"),
					   GetSQLValueString($_POST['vacaciones_porc'], "double"),
					   GetSQLValueString($_POST['cajaCompensacion_porc'], "double"),
					   GetSQLValueString($_POST['sena_porc'], "double"),
					   GetSQLValueString($_POST['arl_porc'], "double"),
					   GetSQLValueString($_POST['total'], "double"),
					   GetSQLValueString($_POST['fecha'], "date"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

   $insertGoTo = "aportes_edit.php?id_aporte=" . $_POST['id_aporte'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario); 
//ULTIMO
mysql_select_db($database_conexion1, $conexion1);
$query_aportes = "SELECT id_aporte FROM TblAportes ORDER BY id_aporte DESC";
$aportes = mysql_query($query_aportes, $conexion1) or die(mysql_error());
$row_aportes = mysql_fetch_assoc($aportes);
$totalRows_aportes = mysql_num_rows($aportes);
 
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script> 

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

</head>
<body>
<?php echo $conexion->header('listas'); ?>
      <form action="<?php echo $editFormAction; ?>" method="POST" name="form1">
        <table align="center" class="table table-bordered table-sm">
          <tr>
            <td colspan="3" id="titulo2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3" id="titulo2">APORTES</td>
            </tr>
          <tr>
            <td id="fuente1">&nbsp;</td>
            <td id="fuente1"><a href="proceso_empleados_listado.php"><img src="images/opciones.gif" alt="EMPLEADOS POR PROCESO" title="EMPLEADOS POR PROCESO" border="0" style="cursor:hand;"></a><a href="factor_prestacional_add.php"><img src="images/f.gif" alt="FACTORES" title="FACTORES" border="0" style="cursor:hand;"></a><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/>
                <input name="id_aporte" type="hidden" id="id_aporte" value="<?php echo $row_aportes['id_aporte']+1?>">
            </a></td>
            <td id="fuente2">&nbsp;</td>
            </tr>
              <tr>
                <td id="fuente1" nowrap>FECHA DE LOS APORTES</td>
                <td id="fuente1"><input name="fecha" type="date" id="fecha" style="width:145px" min="2000-01-02" required value="<?php echo date("y-m-d") ?>"></td>
                <td id="fuente3"></td>
              </tr>
              <tr>
                <td id="fuente1">CESANTIAS</td>
                <td id="fuente1"><input name="cesantias_porc" type="number" id="cesantias_porc" placeholder="%" style="width:70px" min="0" step="0.001" value="8.33"></td>
                <td id="fuente1">&nbsp;</td>
              </tr>
              <tr>
                <td id="fuente1" nowrap>INTERESES DE CESANTIAS</td>
                <td id="fuente1"><input name="interesCesantias_porc" type="number" id="interesCesantias_porc" placeholder="%" style="width:70px" min="0" step="0.001" value="12"></td>
                <td id="fuente1">&nbsp;</td>
              </tr>
              <tr>
                <td id="fuente1">PRIMA</td>
                <td id="fuente1"><input name="prima_porc" type="number" id="prima_porc" placeholder="%" style="width:70px" min="0" step="0.001" value="8.33"></td>
                <td id="fuente1">&nbsp;</td>
              </tr>
              <tr>
                <td id="fuente1">SALUD</td>
                <td id="fuente1"><input name="salud_porc" type="number" id="salud_porc" placeholder="%" style="width:70px" min="0" step="0.001" value="0"></td>
                <td id="fuente1">&nbsp;</td>
              </tr>
              <tr>
                <td id="fuente1">PENSION</td>
                <td id="fuente1"><input name="pension_porc" type="number" id="pension_porc" placeholder="%" style="width:70px" min="0" step="0.001" value="12"></td>
                <td id="fuente1">&nbsp;</td>
              </tr>
              <tr>
                <td id="fuente1">VACACIONES</td>
                <td id="fuente1"><input name="vacaciones_porc" type="number" id="vacaciones_porc" placeholder="%" style="width:70px" min="0" step="0.001" value="4.17"></td>
                <td id="fuente1">&nbsp;</td>
              </tr>
              <tr>
                <td id="fuente1" nowrap>CAJA DE COMPENSACION</td>
                <td id="fuente1"><input name="cajaCompensacion_porc" type="number" id="cajaCompensacion_porc" placeholder="%" style="width:70px" min="0" step="0.001" value="4"></td>
                <td id="fuente1">&nbsp;</td>
              </tr>
              <tr>
                <td id="fuente1">SENA</td>
                <td id="fuente1"><input name="sena_porc" type="number" id="sena_porc" placeholder="%" style="width:70px" min="0" step="0.001" value="0"></td>
                <td id="fuente1">&nbsp;</td>
              </tr>
              <tr>
                <td id="fuente1">ARL</td>
                <td id="fuente1"><input name="arl_porc" type="number" id="arl_porc" placeholder="%" style="width:70px" min="0" step="0.001" value="2.436"></td>
                <td id="fuente1">&nbsp;</td>
              </tr>
              <tr>
                <td id="fuente1">&nbsp;</td>
                <td id="fuente1">&nbsp;</td>
                <td id="fuente1">&nbsp;</td>
              </tr>
          <tr>
            <td colspan="3" id="fuente1"><strong>Nota: </strong> <em>la fecha de estos aportes siempre estara vigente hasta que ingrese los nuevos aportes.</em></td>
          </tr>  
          <tr>
            <td colspan="3" id="fuente1">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3" id="dato2"><input type="submit" value="SIGUIENTE"></td>
            </tr>
        </table>
        <input type="hidden" name="MM_insert" value="form1">
      </form>
      <?php echo $conexion->header('footer'); ?>
</body>
</html>
<?php
mysql_free_result($usuario);
mysql_free_result($aportes);

?>