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


$conexion = new ApptivaDB();

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("UPDATE TblFactorP SET fecha_fp=%s, dias_fp=%s, domin_fest_fp=%s, dif_dom_fp=%s,vacacion_fp=%s,dia_real_fp=%s,dif_vac_fp=%s,hora_lab_fp=%s,usuario_fp=%s WHERE id_fp = %s",
 					   GetSQLValueString($_POST['fecha_fp'], "date"),
                       GetSQLValueString($_POST['dias_fp'], "int"),
					   GetSQLValueString($_POST['domin_fest_fp'], "int"),
					   GetSQLValueString($_POST['dif_dom_fp'], "int"),
					   GetSQLValueString($_POST['vacacion_fp'], "int"),
					   GetSQLValueString($_POST['dia_real_fp'], "int"),
					   GetSQLValueString($_POST['dif_vac_fp'], "double"),
					   GetSQLValueString($_POST['hora_lab_fp'], "double"),
					   GetSQLValueString($_POST['usuario_fp'], "text"),
					   GetSQLValueString($_POST['id_fp'], "int"));
  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

   $insertGoTo = "factor_prestacional_edit.php";
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
//EDITAR
$colname_factor = "-1";
if (isset($_GET['id_fp'])) {
  $colname_factor = (get_magic_quotes_gpc()) ? $_GET['id_fp'] : addslashes($_GET['id_fp']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_factor = sprintf("SELECT * FROM TblFactorP WHERE id_fp='%s'", $colname_factor);
$factor = mysql_query($query_factor, $conexion1) or die(mysql_error());
$row_factor = mysql_fetch_assoc($factor);
$totalRows_factor = mysql_num_rows($factor);

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
            <td colspan="3" id="titulo2">FACTOR PRESTACIONAL</td>
            </tr>
          <tr>
            <td id="fuente1">&nbsp;</td>
            <td id="fuente1"><a href="factor_prestacional_add.php"><img src="images/mas.gif" alt="ADD FACTOR" title="ADD FACTOR" border="0" style="cursor:hand;"></a><a href="proceso_empleados_listado.php"><img src="images/e.gif" alt="EMPLEADOS" title="EMPLEADOS" border="0" style="cursor:hand;"></a><a href="costos_generales.php"><img src="images/opciones.gif" alt="COSTOS GENERALES" title="COSTOS GENERALES" border="0" style="cursor:hand;"></a><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/>
                <input name="id_fp" type="hidden" id="id_fp" value="<?php echo $row_factor['id_fp']?>">
            </a></td>
            <td id="fuente2">&nbsp;</td>
            </tr>
              <tr>
                <td id="fuente1" nowrap>Fecha</td>
                <td id="fuente1"><input name="fecha_fp" type="date" id="fecha_fp" style="width:150px" min="2000-01-02" required value="<?php echo $row_factor['fecha_fp']?>" onChange="factor_prestacional()"></td>
                <td id="fuente3"></td>
              </tr>
              <tr>
                <td id="fuente1">Dias</td>
                <td id="fuente1"><input name="dias_fp" type="number" id="dias_fp" placeholder="dias 365" style="width:150px" min="0" step="1" value="<?php echo $row_factor['dias_fp']?>" onChange="factor_prestacional()"></td>
                <td id="fuente1">&nbsp;</td>
              </tr>
              <tr>
                <td id="fuente1" nowrap>Dominicales y Festivos </td>
                <td id="fuente1"><input name="domin_fest_fp" type="number" id="domin_fest_fp" placeholder="dom y festivos 70" style="width:150px" min="0" step="1" value="<?php echo $row_factor['domin_fest_fp']?>" onChange="factor_prestacional()"></td>
                <td id="fuente1">&nbsp;</td>
              </tr>
              <tr>
                <td id="fuente1">Diferencia</td>
                <td id="fuente1"><input name="dif_dom_fp" type="number" id="dif_dom_fp" placeholder="diferencia" style="width:150px" min="0" step="1" value="<?php echo $row_factor['dif_dom_fp']?>" onChange="factor_prestacional()" readonly></td>
                <td id="fuente1">&nbsp;</td>
              </tr>
              <tr>
                <td id="fuente1">Vacaci&oacute;nes</td>
                <td id="fuente1"><input name="vacacion_fp" type="number" id="vacacion_fp" placeholder="vacaciones 15" style="width:150px" min="0" step="1" value="<?php echo $row_factor['vacacion_fp']?>" onChange="factor_prestacional()"></td>
                <td id="fuente1">&nbsp;</td>
              </tr>
              <tr>
                <td id="fuente1">Dias reales</td>
                <td id="fuente1"><input name="dia_real_fp" type="number" id="dia_real_fp" placeholder="dias reales 280" style="width:150px" min="0" step="1" value="<?php echo $row_factor['dia_real_fp']?>" onChange="factor_prestacional()" readonly></td>
                <td id="fuente1">&nbsp;</td>
              </tr>
              <tr>
                <td id="fuente1">Diferencia</td>
                <td id="fuente1"><input name="dif_vac_fp" type="number" id="dif_vac_fp" placeholder="diferencia" style="width:150px" min="0" value="<?php echo $row_factor['dif_vac_fp']?>" onChange="factor_prestacional()" readonly></td>
                <td id="fuente1">&nbsp;</td>
              </tr>
              <tr>
                <td id="fuente1" nowrap>Horas Laborales</td>
                <td id="fuente1"><input name="hora_lab_fp" type="number" id="hora_lab_fp" placeholder="horas laborales" style="width:150px" min="0" value="<?php echo $row_factor['hora_lab_fp']?>" onChange="factor_prestacional()" readonly></td>
                <td id="fuente1">&nbsp;</td>
              </tr>
              <tr>
                <td id="fuente1">&nbsp; </td>
                <td id="fuente1"><input type="hidden" name="usuario_fp" value="<?php echo $row_usuario['nombre_usuario']; ?>"></td>
                <td id="fuente1">&nbsp;</td>
              </tr>
               <tr>
                <td id="fuente1">&nbsp;</td>
                <td id="fuente1">&nbsp;</td>
                <td id="fuente1">&nbsp;</td>
              </tr>
          <tr>
            <td colspan="3" id="fuente1"><strong>Nota: </strong> <em>la fecha de estos factores siempre estara vigente hasta que ingrese los nuevos factores.</em></td>
          </tr>  
          <tr>
            <td colspan="3" id="fuente1">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3" id="dato2"><input type="submit" value="EDITAR"></td>
            </tr> 
        </table>
        <input type="hidden" name="MM_insert" value="form1">
      </form>
      <?php echo $conexion->header('footer'); ?>
</body>
</html>
<?php
mysql_free_result($usuario);
mysql_free_result($factor);

?>