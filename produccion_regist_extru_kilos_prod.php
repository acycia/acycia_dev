<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
?> 
<?php require_once('Connections/conexion1.php'); ?>
<?php
//LLAMADO A FUNCIONES
include('funciones/funciones_php.php');//SISTEMA RUW PARA LA BASE DE DATOS 
//FIN
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
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


$conexion = new ApptivaDB();

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
if (!empty ($_POST['id_rpp'])&&!empty ($_POST['valor_prod_rp'])){
    foreach($_POST['id_rpp'] as $key=>$k)
    $f[]= $k;
    foreach($_POST['valor_prod_rp'] as $key=>$k)
    $g[]= $k;
    $c= $_POST['id_op_rp'];	
	
	for($s=0; $s<count($f); $s++) {
		  if(!empty($f[$s])&&!empty($g[$s])){ //no salga error con campos vacios
		  
 	  $sqlcostoMP="SELECT valor_unitario_insumo AS valorkilo FROM insumo WHERE id_insumo = $f[$s]"; 
	  $resultcostoMP=mysql_query($sqlcostoMP); 
	  $numcostoMP=mysql_num_rows($resultcostoMP); 
	  $row_valoresMP = mysql_fetch_assoc($resultcostoMP);
	  $contValor=0;
      $valorMP = $row_valoresMP['valorkilo']; 
	  
  $insertSQLd = sprintf("INSERT INTO Tbl_reg_kilo_producido (id_rpp_rp,valor_prod_rp,op_rp,id_proceso_rkp,fecha_rkp,costo_mp) VALUES (%s, %s, %s, %s, %s, %s)",                      
                       GetSQLValueString($f[$s], "int"),
                       GetSQLValueString($g[$s], "double"),
					   GetSQLValueString($c, "int"),
					   GetSQLValueString($_POST['id_proceso_rkp'], "int"),
					   GetSQLValueString($_POST['fecha_ini_rp'], "date"),
					   GetSQLValueString($valorMP, "double"));
  mysql_select_db($database_conexion1, $conexion1);
  $Resultd = mysql_query($insertSQLd, $conexion1) or die(mysql_error());
  
   //UPDATE LA TABLA DE INVENTARIOS DESCONTANDO LO QUE SE GASTO
   $updateSQL = sprintf("UPDATE TblInventarioListado SET Salida=Salida + %s WHERE Codigo = %s",
					   GetSQLValueString($g[$s], "text"), 
                       GetSQLValueString($f[$s], "int"));
  mysql_select_db($database_conexion1, $conexion1);
  $Result = mysql_query($updateSQL, $conexion1) or die(mysql_error());
 		 
	$sqlupdate= sprintf("UPDATE Tbl_reg_produccion SET int_kilos_prod_rp =  int_kilos_prod_rp + %s, int_total_kilos_rp = int_total_kilos_rp + %s WHERE id_op_rp=%s AND id_proceso_rp='1'",
	
					   GetSQLValueString($g[$s], "text"),
					   GetSQLValueString($g[$s], "text"),
                       GetSQLValueString($c, "int"));
	  mysql_select_db($database_conexion1, $conexion1);
  $Result2 = mysql_query($sqlupdate, $conexion1) or die(mysql_error());	 
		 
		  }
	}
}
echo "<script type=\"text/javascript\">window.opener.location.reload();</script>"; 
 
echo "<script type=\"text/javascript\">window.close();</script>";
}
?>
<?php 
//LLENA COMBOS DE MATERIAS PRIMAS
/*mysql_select_db($database_conexion1, $conexion1);
$query_insumo = "SELECT * FROM insumo WHERE clase_insumo='4' AND estado_insumo='0' ORDER BY descripcion_insumo ASC";
$insumo = mysql_query($query_insumo, $conexion1) or die(mysql_error());
$row_insumo = mysql_fetch_assoc($insumo);
$totalRows_insumo = mysql_num_rows($insumo);*/

$row_insumo = $conexion->llenaSelect('insumo',"WHERE clase_insumo='4' AND estado_insumo='0'","ORDER BY descripcion_insumo ASC");
$row_insumo2 = $conexion->llenaSelect('insumo',"WHERE clase_insumo='4' AND estado_insumo='0'","ORDER BY descripcion_insumo ASC");  
$row_insumo3 = $conexion->llenaSelect('insumo',"WHERE clase_insumo='4' AND estado_insumo='0'","ORDER BY descripcion_insumo ASC");  
$row_insumo4 = $conexion->llenaSelect('insumo',"WHERE clase_insumo='4' AND estado_insumo='0'","ORDER BY descripcion_insumo ASC");  
$row_insumo5 = $conexion->llenaSelect('insumo',"WHERE clase_insumo='4' AND estado_insumo='0'","ORDER BY descripcion_insumo ASC");  
$row_insumo6 = $conexion->llenaSelect('insumo',"WHERE clase_insumo='4' AND estado_insumo='0'","ORDER BY descripcion_insumo ASC");  
$row_insumo7 = $conexion->llenaSelect('insumo',"WHERE clase_insumo='4' AND estado_insumo='0'","ORDER BY descripcion_insumo ASC");  
$row_insumo8 = $conexion->llenaSelect('insumo',"WHERE clase_insumo='4' AND estado_insumo='0'","ORDER BY descripcion_insumo ASC");  
$row_insumo9 = $conexion->llenaSelect('insumo',"WHERE clase_insumo='4' AND estado_insumo='0'","ORDER BY descripcion_insumo ASC");  
$row_insumo10 = $conexion->llenaSelect('insumo',"WHERE clase_insumo='4' AND estado_insumo='0'","ORDER BY descripcion_insumo ASC");  
$row_insumo11 = $conexion->llenaSelect('insumo',"WHERE clase_insumo='4' AND estado_insumo='0'","ORDER BY descripcion_insumo ASC");  
$row_insumo12 = $conexion->llenaSelect('insumo',"WHERE clase_insumo='4' AND estado_insumo='0'","ORDER BY descripcion_insumo ASC");    

//MEZCLAS
$colname_materiap = "-1";
if (isset($_GET['id_op'])) 
{
  $colname_materiap= (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_materiap = sprintf("SELECT Tbl_produccion_mezclas.int_ref1_tol1_pm,Tbl_produccion_mezclas.int_ref1_tol1_porc1_pm,Tbl_produccion_mezclas.int_ref2_tol1_pm,Tbl_produccion_mezclas.int_ref2_tol1_porc2_pm,Tbl_produccion_mezclas.int_ref3_tol1_pm,Tbl_produccion_mezclas.int_ref3_tol1_porc3_pm,Tbl_produccion_mezclas.int_ref1_tol2_pm,Tbl_produccion_mezclas.int_ref1_tol2_porc1_pm,Tbl_produccion_mezclas.int_ref2_tol2_pm,Tbl_produccion_mezclas.int_ref2_tol2_porc2_pm,Tbl_produccion_mezclas.int_ref3_tol2_pm,Tbl_produccion_mezclas.int_ref3_tol2_porc3_pm,
Tbl_produccion_mezclas.int_ref1_tol3_pm,Tbl_produccion_mezclas.int_ref1_tol3_porc1_pm,Tbl_produccion_mezclas.int_ref2_tol3_pm,Tbl_produccion_mezclas.int_ref2_tol3_porc2_pm,Tbl_produccion_mezclas.int_ref3_tol3_pm,Tbl_produccion_mezclas.int_ref3_tol3_porc3_pm,Tbl_produccion_mezclas.int_ref1_tol4_pm,Tbl_produccion_mezclas.int_ref1_tol4_porc1_pm,Tbl_produccion_mezclas.int_ref2_tol4_pm,Tbl_produccion_mezclas.int_ref2_tol4_porc2_pm,Tbl_produccion_mezclas.int_ref3_tol4_pm,Tbl_produccion_mezclas.int_ref3_tol4_porc3_pm,
Tbl_produccion_mezclas.id_ref_pm,Tbl_orden_produccion.id_ref_op 
FROM Tbl_orden_produccion,Tbl_produccion_mezclas WHERE Tbl_orden_produccion.id_op='%s' AND Tbl_orden_produccion.id_ref_op=Tbl_produccion_mezclas.id_ref_pm",$colname_materiap);
$materiap = mysql_query($query_materiap, $conexion1) or die(mysql_error());
$row_materiap = mysql_fetch_assoc($materiap);
$totalRows_materiap = mysql_num_rows($materiap);
//ORDEN DE PRODUCCION
$colname_op= "-1";
if (isset($_GET['id_op'])) {
  $colname_op = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_orden_produccion = sprintf("SELECT * FROM Tbl_orden_produccion WHERE id_op=%s AND b_borrado_op='0' ORDER BY id_op DESC",$colname_op);
$orden_produccion = mysql_query($query_orden_produccion, $conexion1) or die(mysql_error());
$row_orden_produccion = mysql_fetch_assoc($orden_produccion);
$totalRows_orden_produccion = mysql_num_rows($orden_produccion);
//SUMA TOTAL DE KILOS EXTRUIDOS POR O.P
$colname_totalKilos= "-1";
if (isset($_GET['id_op'])) {
  $colname_totalKilos = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_totalKilos = sprintf("SELECT * FROM Tbl_reg_produccion WHERE id_op_rp='%s' AND id_proceso_rp='1'",$colname_totalKilos);
$totalKilos = mysql_query($query_totalKilos, $conexion1) or die(mysql_error());
$row_totalKilos = mysql_fetch_assoc($totalKilos);
$totalRows_totalKilos = mysql_num_rows($totalKilos);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
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

  <!-- Select3 Nuevo -->
  <meta charset="UTF-8">
  <!-- jQuery -->
  <script src='select3/assets/js/jquery-3.4.1.min.js' type='text/javascript'></script>

  <!-- select2 css -->
  <link href='select3/assets/plugin/select2/dist/css/select2.min.css' rel='stylesheet' type='text/css'>

  <!-- select2 script -->
  <script src='select3/assets/plugin/select2/dist/js/select2.min.js'></script>
  <!-- Styles -->
  <link rel="stylesheet" href="select3/assets/css/style.css">
  <!-- Fin Select3 Nuevo -->

</head>
<body>
<?php echo $conexion->header('vistas'); ?>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1">
  <table class="table table-bordered table-sm">
    <tr>
      <td nowrap="nowrap" colspan="10" id="subtitulo">AGREGAR KILOS PRODUCIDOS</td>
    </tr>
    <tr>
      <td nowrap="nowrap" colspan="10" id="dato1"  style="color:red;" >Al guardar las materias primas asegurese de haber seleccionado el ultimo rollo en la liquidación, de lo contrario las amterias primas quedaran divididas con distintas fechas, a menos que sea un Rollo Parcial</td>
    </tr>
    <tr>
      <td colspan="7" id="dato1">&nbsp;</td>
      <td colspan="3"  nowrap="nowrap" id="dato2">            
        <strong>Fecha y Hora:</strong>
        <input name="fecha_ini_rp" type="datetime" min="2000-01-02" value="<?php echo $_GET['fecha']; ?>" size="19" required="required" readonly="readonly"/>            </tr>
        <tr>
          <td colspan="10" id="dato5">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="5" id="dato2">MATERIA PRIMA</td>
          <td colspan="5" id="dato2">KILOS</td>
        </tr>
        <tr><td colspan="5" id="dato2">

         <select name="id_rpp[]" id="id_rpp[]" class="busqueda selectsGrande">
           <option value="">Referencia MP</option>
           <?php  foreach($row_insumo as $row_insumo ) { ?>
            <option value="<?php echo $row_insumo['id_insumo']?>"><?php echo $row_insumo['codigo_insumo']." (CODIGO) ".$row_insumo['descripcion_insumo'];?></option>
          <?php } ?>                
        </select>
      </td>
      <td colspan="5" id="dato2">
        <input type="number" name="valor_prod_rp[]" id="valor_prod_rp[]" min="0"step="0.01"size="12"  placeholder="Kilos" autofocus onBlur="return validacion_kilos_extrusion();"/></td></tr>
        <tr>
          <td colspan="5" id="dato2">
           <select name="id_rpp[]" id="id_rpp[]" class="busqueda selectsGrande">
             <option value="">Referencia MP</option>
             <?php  foreach($row_insumo2 as $row_insumo2 ) { ?>
              <option value="<?php echo $row_insumo2['id_insumo']?>"><?php echo $row_insumo2['descripcion_insumo']." (CODIGO) ".$row_insumo2['codigo_insumo']?></option>
              <?php } ?>                 
          </select>
        </td>
        <td colspan="5" id="dato2"><input type="number" name="valor_prod_rp[]" id="valor_prod_rp[]" min="0"step="0.01"size="12"  placeholder="Kilos" onBlur="return validacion_kilos_extrusion();"/></td></tr>
        <tr><td colspan="5" id="dato2">
         <select name="id_rpp[]" id="id_rpp[]" class="busqueda selectsGrande">
           <option value="">Referencia MP</option>
           <?php  foreach($row_insumo3 as $row_insumo3 ) { ?>
            <option value="<?php echo $row_insumo3['id_insumo']?>"><?php echo $row_insumo3['descripcion_insumo']." (CODIGO) ".$row_insumo3['codigo_insumo']?></option>
            <?php } ?>               
        </select></td><td colspan="5" id="dato2"><input type="number" name="valor_prod_rp[]" id="valor_prod_rp[]" min="0"step="0.01"size="12"  placeholder="Kilos" onBlur="return validacion_kilos_extrusion();"/></td></tr>
        <tr><td colspan="5" id="dato2">
         <select name="id_rpp[]" id="id_rpp[]" class="busqueda selectsGrande">
           <option value="">Referencia MP</option>
           <?php  foreach($row_insumo4 as $row_insumo4 ) { ?>
            <option value="<?php echo $row_insumo4['id_insumo']?>"><?php echo $row_insumo4['descripcion_insumo']." (CODIGO) ".$row_insumo4['codigo_insumo']?></option>
            <?php } ?>                
        </select></td><td colspan="5" id="dato2"><input type="number" name="valor_prod_rp[]" id="valor_prod_rp[]" min="0"step="0.01"size="12"  placeholder="Kilos" onBlur="return validacion_kilos_extrusion();"/></td></tr>
        <tr><td colspan="5" id="dato2">
         <select name="id_rpp[]" id="id_rpp[]" class="busqueda selectsGrande">
           <option value="">Referencia MP</option>
           <?php  foreach($row_insumo5 as $row_insumo5 ) { ?>
            <option value="<?php echo $row_insumo5['id_insumo']?>"><?php echo $row_insumo5['descripcion_insumo']." (CODIGO) ".$row_insumo5['codigo_insumo']?></option>
            <?php } ?>                
        </select></td><td colspan="5" id="dato2"><input type="number" name="valor_prod_rp[]" id="valor_prod_rp[]" min="0"step="0.01"size="12"  placeholder="Kilos" onBlur="return validacion_kilos_extrusion();"/></td></tr>
        <tr><td colspan="5" id="dato2">
         <select name="id_rpp[]" id="id_rpp[]" class="busqueda selectsGrande">
           <option value="">Referencia MP</option>
           <?php  foreach($row_insumo6 as $row_insumo6 ) { ?>
            <option value="<?php echo $row_insumo6['id_insumo']?>"><?php echo $row_insumo6['descripcion_insumo']." (CODIGO) ".$row_insumo6['codigo_insumo']?></option>
            <?php } ?>                 
        </select></td><td colspan="5" id="dato2"><input type="number" name="valor_prod_rp[]" id="valor_prod_rp[]" min="0"step="0.01"size="12"  placeholder="Kilos" onBlur="return validacion_kilos_extrusion();"/></td></tr>
        <tr><td colspan="5" id="dato2">
         <select name="id_rpp[]" id="id_rpp[]" class="busqueda selectsGrande">
           <option value="">Referencia MP</option>
           <?php  foreach($row_insumo7 as $row_insumo7 ) { ?>
            <option value="<?php echo $row_insumo7['id_insumo']?>"><?php echo $row_insumo7['descripcion_insumo']." (CODIGO) ".$row_insumo7['codigo_insumo']?></option>
            <?php } ?>                
        </select></td><td colspan="5" id="dato2"><input type="number" name="valor_prod_rp[]" id="valor_prod_rp[]" min="0"step="0.01"size="12"  placeholder="Kilos" onBlur="return validacion_kilos_extrusion();"/></td></tr>
        <tr><td colspan="5" id="dato2">
         <select name="id_rpp[]" id="id_rpp[]" class="busqueda selectsGrande">
           <option value="">Referencia MP</option>
           <?php  foreach($row_insumo8 as $row_insumo8 ) { ?>
            <option value="<?php echo $row_insumo8['id_insumo']?>"><?php echo $row_insumo8['descripcion_insumo']." (CODIGO) ".$row_insumo8['codigo_insumo']?></option>
            <?php } ?>                
        </select></td><td colspan="5" id="dato2"><input type="number" name="valor_prod_rp[]" id="valor_prod_rp[]" min="0"step="0.01"size="12"  placeholder="Kilos" onBlur="return validacion_kilos_extrusion();"/></td></tr>
        <tr><td colspan="5" id="dato2">
         <select name="id_rpp[]" id="id_rpp[]" class="busqueda selectsGrande">
           <option value="">Referencia MP</option>
           <?php  foreach($row_insumo9 as $row_insumo9 ) { ?>
            <option value="<?php echo $row_insumo9['id_insumo']?>"><?php echo $row_insumo9['descripcion_insumo']." (CODIGO) ".$row_insumo9['codigo_insumo']?></option>
            <?php } ?>                
        </select></td><td colspan="5" id="dato2"><input type="number" name="valor_prod_rp[]" id="valor_prod_rp[]" min="0"step="0.01"size="12"  placeholder="Kilos" onBlur="return validacion_kilos_extrusion();"/></td>
        <tr> 
          <td colspan="5" id="dato2">
           <select name="id_rpp[]" id="id_rpp[]" class="busqueda selectsGrande">
             <option value="">Referencia MP</option>
             <?php  foreach($row_insumo10 as $row_insumo10 ) { ?>
              <option value="<?php echo $row_insumo10['id_insumo']?>"><?php echo $row_insumo10['descripcion_insumo']." (CODIGO) ".$row_insumo10['codigo_insumo']?></option>
              <?php } ?>                 
          </select></td><td colspan="5" id="dato2"><input type="number" name="valor_prod_rp[]" id="valor_prod_rp[]" min="0"step="0.01"size="12"  placeholder="Kilos" onBlur="return validacion_kilos_extrusion();"/></td></tr>
          <tr><td colspan="5" id="dato2">
           <select name="id_rpp[]" id="id_rpp[]" class="busqueda selectsGrande">
             <option value="">Referencia MP</option>
             <?php  foreach($row_insumo11 as $row_insumo11 ) { ?>
              <option value="<?php echo $row_insumo11['id_insumo']?>"><?php echo $row_insumo11['descripcion_insumo']." (CODIGO) ".$row_insumo11['codigo_insumo']?></option>
              <?php } ?>                 
          </select></td><td colspan="5" id="dato2"><input type="number" name="valor_prod_rp[]" id="valor_prod_rp[]" min="0"step="0.01"size="12"  placeholder="Kilos" onBlur="return validacion_kilos_extrusion();"/></td></tr>
          <tr><td colspan="5" id="dato2">
           <select name="id_rpp[]" id="id_rpp[]" class="busqueda selectsGrande">
             <option value="">Referencia MP</option>
             <?php  foreach($row_insumo12 as $row_insumo12 ) { ?>
              <option value="<?php echo $row_insumo12['id_insumo']?>"><?php echo $row_insumo12['descripcion_insumo']." (CODIGO) ".$row_insumo12['codigo_insumo']?></option>
              <?php } ?>                 
          </select></td><td colspan="5" id="dato2"><input type="number" name="valor_prod_rp[]" id="valor_prod_rp[]" min="0"step="0.01"size="12"  placeholder="Kilos" onBlur="return validacion_kilos_extrusion();"/></td>                    

        </tr>


        <tr>
          <td colspan="10" id="dato1">&nbsp;
          </td>
        </tr> 
        <tr>
          <td colspan="10" id="dato2"><input type="submit" class="botonGeneral" value="ADD A EXTRUSION" onClick="envio_form(this);"/></td>
        </tr>                   
        <tr>
          <td colspan="10" id="dato2"><strong>DISTRIBUCION DE LA MEZCLA SEGUN LA FORMULA</strong></td>
        </tr>
        <tr>
          <td colspan="10" id="dato1">O.P: <strong><?php echo $row_orden_produccion['id_op'] ?></strong> Kilos Requeridos:<strong><?php echo $row_orden_produccion['int_kilos_op'] ?> </strong>mas el <strong><?php echo $row_orden_produccion['int_desperdicio_op'] ?></strong>% de Tolerancia de la O.P</td>
        </tr>
        <td rowspan="3" id="fuente1"><strong>EXT-1          
        </strong></td>
        <td colspan="3" id="fuente1"><strong>TORNILLO A</strong></td>
        <td colspan="3" id="fuente1"><strong>TORNILLO B</strong></td>
        <td colspan="3" id="fuente1"><strong>TORNILLO C</strong></td>
      </tr>
      <tr>
        <td colspan="3" id="dato1"></td>
        <td id="dato1"></td>
        <td colspan="2" id="dato1"></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1"><strong>Referencia</strong></td>
        <td id="fuente1"><strong>% Mezcla</strong></td>
        <td id="fuente1"><strong>Kilos aprox.</strong></td>
        <td id="fuente1"><strong>Referencia</strong></td>
        <td id="fuente1"><strong>% Mezcla</strong></td>
        <td id="fuente1"><strong>Kilos aprox.</strong></td>
        <td id="fuente1"><strong>Referencia</strong></td>
        <td id="fuente1"><strong>% Mezcla</strong></td>
        <td id="fuente1"><strong>Kilos aprox.</strong></td> 
        <tr>
          <td id="fuente1"><strong>Tolva A</strong></td>
          <td id="fuente1"><?php 
          $idinsumo=$row_materiap['int_ref1_tol1_pm']; 
          $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
          $resultm=mysql_query($sqlm); 
          $numm=mysql_num_rows($resultm); 
          if($numm >= '1') 
            { $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
          else { echo "";	
        }	
      ?></td>
      <td id="fuente1"><?php echo $row_materiap['int_ref1_tol1_porc1_pm'] ?></td>
      <td id="fuente1"><?php $kilosaprox = sacar_porcentaje($row_orden_produccion['int_kilos_op'],$row_materiap['int_ref1_tol1_porc1_pm']); echo $kilosaprox;?></td>
      <td id="fuente1"><?php $idinsumo=$row_materiap['int_ref2_tol1_pm'];
      $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
      $resultm=mysql_query($sqlm); 
      $numm=mysql_num_rows($resultm); 
      if($numm >= '1') 
        { $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
      else { echo "";	
    }	?></td>
    <td id="fuente1"><?php echo $row_materiap['int_ref2_tol1_porc2_pm'] ?></td>
    <td id="fuente1"><?php $kilosaprox = sacar_porcentaje($row_orden_produccion['int_kilos_op'],$row_materiap['int_ref2_tol1_porc2_pm']); echo $kilosaprox;?></td>
    <td id="fuente1"><?php $idinsumo= $row_materiap['int_ref3_tol1_pm'];
    $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
    $resultm=mysql_query($sqlm); 
    $numm=mysql_num_rows($resultm); 
    if($numm >= '1') 
      { $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
    else { echo "";	
  }		
?></td>
<td id="fuente1"><?php echo $row_materiap['int_ref3_tol1_porc3_pm'] ?></td>
<td id="fuente1"><?php $kilosaprox = sacar_porcentaje($row_orden_produccion['int_kilos_op'],$row_materiap['int_ref3_tol1_porc3_pm']); echo $kilosaprox; ?></td>
</tr>
<tr id="tr1">
  <td id="fuente1"><strong>Tolva B</strong></td>
  <td id="fuente1"><?php $idinsumo = $row_materiap['int_ref1_tol2_pm'];
  $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
  $resultm=mysql_query($sqlm); 
  $numm=mysql_num_rows($resultm); 
  if($numm >= '1') 
    { $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
  else { echo "";	
}
?></td>
<td id="fuente1"><?php echo $row_materiap['int_ref1_tol2_porc1_pm'] ?></td>
<td id="fuente1"><?php $kilosaprox = sacar_porcentaje($row_orden_produccion['int_kilos_op'],$row_materiap['int_ref1_tol2_porc1_pm']); echo $kilosaprox; ?></td>
<td id="fuente1"><?php $idinsumo =  $row_materiap['int_ref2_tol2_pm'];
$sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
$resultm=mysql_query($sqlm); 
$numm=mysql_num_rows($resultm); 
if($numm >= '1') 
  { $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
else { echo "";	
}	
?></td>
<td id="fuente1"><?php echo $row_materiap['int_ref2_tol2_porc2_pm'] ?></td>
<td id="fuente1"><?php $kilosaprox = sacar_porcentaje($row_orden_produccion['int_kilos_op'],$row_materiap['int_ref2_tol2_porc2_pm']); echo $kilosaprox; ?></td>
<td id="fuente1"><?php $idinsumo =  $row_materiap['int_ref3_tol2_pm'];
$sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
$resultm=mysql_query($sqlm); 
$numm=mysql_num_rows($resultm); 
if($numm >= '1') 
  { $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
else { echo "";	
}	
?></td>
<td id="fuente1"><?php echo $row_materiap['int_ref3_tol2_porc3_pm'] ?></td>
<td id="fuente1"><?php $kilosaprox = sacar_porcentaje($row_orden_produccion['int_kilos_op'],$row_materiap['int_ref3_tol2_porc3_pm']); echo $kilosaprox; ?></td>
</tr>
<tr>
  <td id="fuente1"><strong>Tolva C</strong></td>
  <td id="fuente1"><?php $idinsumo =  $row_materiap['int_ref1_tol3_pm'];
  $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
  $resultm=mysql_query($sqlm); 
  $numm=mysql_num_rows($resultm); 
  if($numm >= '1') 
    { $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
  else { echo "";	
}	
?></td>
<td id="fuente1"><?php echo $row_materiap['int_ref1_tol3_porc1_pm'] ?></td>
<td id="fuente1"><?php $kilosaprox = sacar_porcentaje($row_orden_produccion['int_kilos_op'],$row_materiap['int_ref1_tol3_porc1_pm']); echo $kilosaprox; ?></td>
<td id="fuente1"><?php $idinsumo =  $row_materiap['int_ref2_tol3_pm'];
$sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
$resultm=mysql_query($sqlm); 
$numm=mysql_num_rows($resultm); 
if($numm >= '1') 
  { $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
else { echo "";	
}	
?></td>
<td id="fuente1"><?php echo $row_materiap['int_ref2_tol3_porc2_pm'] ?></td>
<td id="fuente1"><?php $kilosaprox = sacar_porcentaje($row_orden_produccion['int_kilos_op'],$row_materiap['int_ref2_tol3_porc2_pm']); echo $kilosaprox; ?></td>
<td id="fuente1"><?php $idinsumo =  $row_materiap['int_ref3_tol3_pm'];
$sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
$resultm=mysql_query($sqlm); 
$numm=mysql_num_rows($resultm); 
if($numm >= '1') 
  { $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
else { echo "";	
}	
?></td>
<td id="fuente1"><?php echo $row_materiap['int_ref3_tol3_porc3_pm'] ?></td>
<td id="fuente1"><?php $kilosaprox = sacar_porcentaje($row_orden_produccion['int_kilos_op'],$row_materiap['int_ref3_tol3_porc3_pm']); echo $kilosaprox; ?></td>
</tr>
<tr id="tr1">
  <td id="fuente1"><strong>Tolva D</strong></td>
  <td id="fuente1"><?php $idinsumo =  $row_materiap['int_ref1_tol4_pm'];
  $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
  $resultm=mysql_query($sqlm); 
  $numm=mysql_num_rows($resultm); 
  if($numm >= '1') 
    { $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
  else { echo "";	
}	
?></td>
<td id="fuente1"><?php echo $row_materiap['int_ref1_tol4_porc1_pm'] ?></td>
<td id="fuente1"><?php $kilosaprox = sacar_porcentaje($row_orden_produccion['int_kilos_op'],$row_materiap['int_ref1_tol4_porc1_pm']); echo $kilosaprox; ?></td>
<td id="fuente1"><?php $idinsumo =  $row_materiap['int_ref2_tol4_pm'];
$sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
$resultm=mysql_query($sqlm); 
$numm=mysql_num_rows($resultm); 
if($numm >= '1') 
  { $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
else { echo "";	
}	
?></td>
<td id="fuente1"><?php echo $row_materiap['int_ref2_tol4_porc2_pm'] ?></td>
<td id="fuente1"><?php $kilosaprox = sacar_porcentaje($row_orden_produccion['int_kilos_op'],$row_materiap['int_ref2_tol4_porc2_pm']); echo $kilosaprox; ?></td>
<td id="fuente1"><?php $idinsumo =  $row_materiap['int_ref3_tol4_pm'];
$sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
$resultm=mysql_query($sqlm); 
$numm=mysql_num_rows($resultm); 
if($numm >= '1') 
  { $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
else { echo "";	
}	
?></td>
<td id="fuente1"><?php echo $row_materiap['int_ref3_tol4_porc3_pm'] ?></td>
<td id="fuente1"><?php $kilosaprox = sacar_porcentaje($row_orden_produccion['int_kilos_op'],$row_materiap['int_ref3_tol4_porc3_pm']); echo $kilosaprox; ?></td>
</tr>
<tr>
  <td colspan="10" id="dato1">&nbsp;

  </td>
</tr>          
<tr>
  <td colspan="10" nowrap="nowrap"  id="dato1"><p><strong>Nota:</strong> Los kilo aprox. son los kilos aproximados que se deben utilizar segun  </p>
    <p>la cantidad requerida en la orden de produccion * el % de la mezcla .</p></td>
  </tr>
  <tr>
    <td colspan="10" id="dato2">&nbsp;</td>
  </tr>
  <tr>
    <td ></td>
  </tr>
  <tr>
    <td colspan="10" id="dato2">&nbsp;</td>
  </tr>
</table>
<input  name="porcentaje" id="porcentaje" type="hidden" value="<?php echo  $row_orden_produccion['int_desperdicio_op']; ?>"/>
<input name="id_op_rp" type="hidden" id="id_op_rp" value="<?php echo $_GET['id_op']; ?>" />
<input name="id_proceso_rkp" type="hidden" id="id_proceso_rkp" value="1" />
<input name="kilos_op" type="hidden" id="kilos_op" value="<?php echo $row_orden_produccion['int_kilos_op']; ?>" />
<?php  for ($x=0;$x<=$totalRows_totalKilos-1;$x++) { ?>
  <input name="kilos_extruido[]" type="hidden" id="kilos_extruido[]" value="<?php $tK=mysql_result($totalKilos,$x,int_total_kilos_rp); echo $tK; ?>" />
<?php } ?>
<input type="hidden" name="MM_insert" value="form1">
</form>
      <?php echo $conexion->header('footer'); ?>
</body>
</html>