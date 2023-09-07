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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO tbl_produccion_mezclas (id_pm,id_proceso,fecha_registro_pm,str_registro_pm,id_ref_pm,int_cod_ref_pm,version_ref_pm,int_ref1_tol1_pm,int_ref1_tol1_porc1_pm,
int_ref2_tol1_pm,int_ref2_tol1_porc2_pm,int_ref3_tol1_pm,int_ref3_tol1_porc3_pm,int_ref1_tol2_pm,int_ref1_tol2_porc1_pm,int_ref2_tol2_pm,int_ref2_tol2_porc2_pm,int_ref3_tol2_pm,int_ref3_tol2_porc3_pm,
int_ref1_tol3_pm,int_ref1_tol3_porc1_pm,int_ref2_tol3_pm,int_ref2_tol3_porc2_pm,int_ref3_tol3_pm,int_ref3_tol3_porc3_pm,int_ref1_tol4_pm,int_ref1_tol4_porc1_pm,int_ref2_tol4_pm,int_ref2_tol4_porc2_pm,
int_ref3_tol4_pm,int_ref3_tol4_porc3_pm, int_ref1_rpm_pm, int_ref1_tol5_porc1_pm, int_ref2_rpm_pm, int_ref2_tol5_porc2_pm, int_ref3_rpm_pm, int_ref3_tol5_porc3_pm, extrusora_mp, observ_pm,b_borrado_pm
) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s,%s, %s, %s, %s, %s, %s,%s, %s, %s, %s, %s,%s, %s, %s, %s,%s, %s, %s, %s, %s, %s,%s, %s, %s, %s, %s, %s )",
             GetSQLValueString($_POST['id_pm'], "int"),
             GetSQLValueString($_POST['id_proceso'], "int"),
             GetSQLValueString($_POST['fecha_registro_pm'], "date"),
					   GetSQLValueString($_POST['str_registro_pm'], "text"),
					   GetSQLValueString($_POST['id_ref_pm'], "int"),
					   GetSQLValueString($_POST['int_cod_ref_pm'], "int"),
					   GetSQLValueString($_POST['version_ref_pm'], "text"),
					   GetSQLValueString($_POST['int_ref1_tol1_pm'], "text"),
					   GetSQLValueString($_POST['int_ref1_tol1_porc1_pm'], "double"),
					   GetSQLValueString($_POST['int_ref2_tol1_pm'], "text"),
					   GetSQLValueString($_POST['int_ref2_tol1_porc2_pm'], "double"),
					   GetSQLValueString($_POST['int_ref3_tol1_pm'], "text"),
					   GetSQLValueString($_POST['int_ref3_tol1_porc3_pm'], "double"),
					   GetSQLValueString($_POST['int_ref1_tol2_pm'], "text"),
					   GetSQLValueString($_POST['int_ref1_tol2_porc1_pm'], "double"),
					   GetSQLValueString($_POST['int_ref2_tol2_pm'], "text"),
					   GetSQLValueString($_POST['int_ref2_tol2_porc2_pm'], "double"),
					   GetSQLValueString($_POST['int_ref3_tol2_pm'], "text"),
					   GetSQLValueString($_POST['int_ref3_tol2_porc3_pm'], "double"),
					   GetSQLValueString($_POST['int_ref1_tol3_pm'], "text"),
					   GetSQLValueString($_POST['int_ref1_tol3_porc1_pm'], "double"),
					   GetSQLValueString($_POST['int_ref2_tol3_pm'], "text"),
					   GetSQLValueString($_POST['int_ref2_tol3_porc2_pm'], "double"),
					   GetSQLValueString($_POST['int_ref3_tol3_pm'], "text"),
					   GetSQLValueString($_POST['int_ref3_tol3_porc3_pm'], "double"),
					   GetSQLValueString($_POST['int_ref1_tol4_pm'], "text"),
					   GetSQLValueString($_POST['int_ref1_tol4_porc1_pm'], "double"),
					   GetSQLValueString($_POST['int_ref2_tol4_pm'], "text"),
					   GetSQLValueString($_POST['int_ref2_tol4_porc2_pm'], "double"),
					   GetSQLValueString($_POST['int_ref3_tol4_pm'], "text"),
					   GetSQLValueString($_POST['int_ref3_tol4_porc3_pm'], "double"),
					   GetSQLValueString($_POST['int_ref1_rpm_pm'], "text"),
					   GetSQLValueString($_POST['int_ref1_tol5_porc1_pm'], "double"),
					   GetSQLValueString($_POST['int_ref2_rpm_pm'], "text"),
					   GetSQLValueString($_POST['int_ref2_tol5_porc2_pm'], "double"),
					   GetSQLValueString($_POST['int_ref3_rpm_pm'], "text"),
					   GetSQLValueString($_POST['int_ref3_tol5_porc3_pm'], "double"),
             GetSQLValueString($_POST['extrusora_mp'], "text"),
					   GetSQLValueString($_POST['observ_pm'], "text"),
					   GetSQLValueString($_POST['b_borrado_pm'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  //$insertGoTo = "produccion_caract_extrusion_add.php?id_pm=" . $_POST['id_pm'] . "";

  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}


$conexion = new ApptivaDB();

$maquinas = $conexion->llenaSelect('maquina','WHERE proceso_maquina=1',' ORDER BY nombre_maquina ASC');
 
$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = $_SESSION['MM_Username'];
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = %s", GetSQLValueString($colname_usuario, "text"));
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

mysql_select_db($database_conexion1, $conexion1);
$query_ultimo = "SELECT * FROM Tbl_produccion_mezclas ORDER BY id_pm DESC";
$ultimo = mysql_query($query_ultimo, $conexion1) or die(mysql_error());
$row_ultimo = mysql_fetch_assoc($ultimo);
$totalRows_ultimo = mysql_num_rows($ultimo);

$colname_ref = "-1";
if (isset($_GET['id_ref'])) 
{
  $colname_ref= (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ref = sprintf("SELECT * FROM Tbl_referencia WHERE id_ref=%s",$colname_ref);
$ref = mysql_query($query_ref, $conexion1) or die(mysql_error());
$row_ref = mysql_fetch_assoc($ref);
$totalRows_ref = mysql_num_rows($ref);

//SELECT REFERENCIA
mysql_select_db($database_conexion1, $conexion1);
$query_referencia = "SELECT * FROM Tbl_produccion_mezclas  ORDER BY Tbl_produccion_mezclas.int_cod_ref_pm DESC";
$referencia = mysql_query($query_referencia, $conexion1) or die(mysql_error());
$row_referencia = mysql_fetch_assoc($referencia);
$totalRows_referencia = mysql_num_rows($referencia);

//CARGA REF
$colname_ref_copia = "-1";
if (isset($_GET['ref'])) {
  $colname_ref_copia  = (get_magic_quotes_gpc()) ? $_GET['ref'] : addslashes($_GET['ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ref_copia = sprintf("SELECT * FROM Tbl_produccion_mezclas WHERE Tbl_produccion_mezclas.id_ref_pm=%s",$colname_ref_copia);
$ref_copia = mysql_query($query_ref_copia, $conexion1) or die(mysql_error());
$row_ref_copia = mysql_fetch_assoc($ref_copia);
$totalRows_ref_copia = mysql_num_rows($ref_copia);
//LLENA COMBOS DE MATERIAS PRIMAS
mysql_select_db($database_conexion1, $conexion1);
$query_insumo = "SELECT * FROM insumo WHERE clase_insumo='4' ORDER BY descripcion_insumo ASC";
$insumo = mysql_query($query_insumo, $conexion1) or die(mysql_error());
$row_insumo = mysql_fetch_assoc($insumo);
$totalRows_insumot = mysql_num_rows($insumo);
//LLENA COMBOS DE MATERIAS PRIMAS
mysql_select_db($database_conexion1, $conexion1);
$query_materia_prima = "SELECT * FROM insumo WHERE clase_insumo='4' ORDER BY descripcion_insumo ASC";
$materia_prima = mysql_query($query_materia_prima, $conexion1) or die(mysql_error());
$row_materia_prima = mysql_fetch_assoc($materia_prima);
$totalRows_materia_prima = mysql_num_rows($materia_prima);
//LLAMA EL TIPO DE MEZCLA DE LA REFERENCIA
$colname_formula_ref = "-1";
if (isset($_GET['cod_ref'])) {
  $colname_formula_ref  = (get_magic_quotes_gpc()) ? $_GET['cod_ref'] : addslashes($_GET['cod_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_formula_ref = sprintf("SELECT DISTINCT int_cod_ref_io,int_nombre_io FROM Tbl_items_ordenc WHERE int_cod_ref_io=%s AND int_nombre_io<>''",$colname_formula_ref);
$formula_ref = mysql_query($query_formula_ref, $conexion1) or die(mysql_error());
$row_formula_ref = mysql_fetch_assoc($formula_ref);
$totalRows_formula_ref = mysql_num_rows($formula_ref);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
<link rel="stylesheet" type="text/css" href="css/general.css"/>


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
  
<script type="text/javascript">
function show_hide() {
if(document.getElementById('check_sh1').checked) {
document.getElementById('select_sh2').style.display = "none";
document.getElementById('select_sh2').disabled = true;
} else {

document.getElementById('select_sh2').style.visibility = "visible";
document.getElementById('select_sh2').style.display = "block";
document.getElementById('select_sh2').disabled = false; 
}
}
</script>

<!-- jquery -->
<script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>
<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script> 
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>

<!-- sweetalert -->
<script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
<link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">

</head>
<body>
<?php echo $conexion->header('vistas'); ?>
  <form action="view_index.php?c=cmezclas&a=GuardarMezcla&cod_ref=<?php echo $_GET['cod_ref'];?>" method="post" enctype="multipart/form-data" name="form1">
    <table id="table table-bordered table-sm">
      <tr id="tr1">
        <td colspan="7" id="titulo2">PROCESO EXTRUSION MEZCLAS</td>
        </tr>
      <tr>
        <td width="137" rowspan="6" id="dato2"><img src="images/logoacyc.jpg"/></td>
        <td colspan="6" id="dato3"><a href="produccion_mezclas.php"><img src="images/m.gif" style="cursor:hand;" alt="LISTADO MEZCLAS" title="LISTADO MEZCLAS" border="0" /></a><a href="produccion_caracteristicas.php"><img src="images/c.gif" style="cursor:hand;" alt="LISTADO CARACTERISTICAS" title="LISTADO CARACTERISTICAS" border="0" /></a><a href="produccion_referencias.php"><img src="images/rp.gif" style="cursor:hand;" alt="LISTADO REF. POR PROCESO" title="LISTADO REF. POR PROCESO" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" title="MENU PRINCIPAL" border="0"/></a></td>
        </tr>
      <tr id="tr1">
        <td width="182" colspan="2" nowrap="nowrap" id="fuente1">Fecha Ingreso 
          <input name="fecha_registro_pm" type="date" min="2000-01-02" value="<?php echo date("Y-m-d"); ?>" size="10" autofocus />
          </td>
        <td colspan="4" id="fuente1">
      Ingresado por
     <input name="str_registro_pm" type="text" id="str_registro_pm" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="27" readonly="readonly"/>
          <?php $numero=$row_ultimo['id_pm']+1;  $numero; ?>
      <input type="hidden" name="id_pm" id="id_pm" value="<?php echo $numero; ?>"/></td>
        </tr>
      <tr id="tr3">
        <td colspan="2" nowrap="nowrap" id="fuente2">&nbsp;</td>
        <td width="126" colspan="2" nowrap="nowrap" id="fuente2">&nbsp;</td>
        <td width="235" colspan="2" id="fuente2">&nbsp;</td>
      </tr>
      <tr id="tr3">
        <td colspan="2" nowrap="nowrap" id="fuente2">Referencia:</td>
        <td colspan="2" id="fuente2">Version:</td>
        <td colspan="2" id="dato1"><input type="button" name="GENERAR COPIA" id="check_sh1" value="GENERAR COPIA" onclick="show_hide(this)"/></td>
      </tr>
      <tr>
        <td colspan="2" nowrap="nowrap" id="numero2"><input type="hidden" name="id_ref_pm" id="id_ref_pm" value="<?php echo $row_ref['id_ref'] ?>"/>
          <input type="hidden" name="int_cod_ref_pm" id="int_cod_ref_pm" value="<?php echo $row_ref['cod_ref']; ?>" />
          <?php echo $row_ref['cod_ref']; ?></td>
        <td colspan="2" nowrap="nowrap" id="numero2"><input type="hidden" name="version_ref_pm" id="version_ref_pm" value="<?php echo $row_ref['version_ref']; ?>" />
          <?php echo $row_ref['version_ref']; ?></td>
        <td colspan="2" id="fuente2">
        <select name="ref" id="select_sh2" onchange="if(form1.ref.value){ consulta_ref_mezcla(); } else{ alert('Debe Seleccionar una REFERENCIA'); }"  style="visibility:hidden">
          <option value=""<?php if (!(strcmp("", $_GET['ref']))) {echo "selected=\"selected\"";} ?>>Referencia</option>
          <?php
            do {  
            ?>
              <option value="<?php echo $row_referencia['id_ref_pm']?>"<?php if (!(strcmp($row_referencia['int_cod_ref_pm'], $_GET['ref']))) {echo "selected=\"selected\"";} ?>><?php echo $row_referencia['int_cod_ref_pm']?></option>
            <?php
            } while ($row_referencia = mysql_fetch_assoc($referencia));
              $rows = mysql_num_rows($referencia);
              if($rows > 0) {
                  mysql_data_seek($referencia, 0);
            	  $row_referencia = mysql_fetch_assoc($referencia);
              }
            ?>
        </select></td>
      </tr>
      <tr>
        <td colspan="2" id="dato2">&nbsp;</td>
        <td colspan="2" id="dato2">&nbsp;</td>
        <td colspan="2" id="dato2"><datalist id="dias"></datalist></td>
      </tr>
      <tr id="tr1">
        <td colspan="10" id="titulo4">EXTRUSION</td>
       </tr>
       <tr>
         <td  colspan="10" id="titulo4">
           Estrusora : 
           <select name="extrusora_mp" id="extrusora_mp" class="busqueda selectsMedio" required="required" >
               <option value="">Extrusoras</option>
                  <?php  foreach($maquinas as $maquinas ) { ?>
               <option value="<?php echo $maquinas['nombre_maquina']; ?>"><?php echo htmlentities($maquinas['nombre_maquina']); ?> 
             </option>
           <?php } ?>
           </select>
         </td>
       </tr>
      <tr id="tr1">
        <td rowspan="3" id="fuente1">EXT-1          
        </td>
        <td colspan="2" id="fuente1">TORNILLO A</td>
        <td colspan="2" id="fuente1">TORNILLO B</td>
        <td colspan="2" id="fuente1">TORNILLO C</td>
        </tr>
      <tr>
        <td colspan="2" id="dato1"></td>
        <td colspan="2" id="dato1"></td>
        <td colspan="2" id="dato1"></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Referencia</td>
        <td id="fuente1">%</td>
        <td id="fuente1">Referencia</td>
        <td id="fuente1">%</td>
        <td id="fuente1">Referencia</td>
        <td id="fuente1">%</td>
      </tr>
      <tr>
        <td id="dato1"></td>
        <td colspan="2" id="dato1"></td>
        <td colspan="2" id="dato1"></td>
        <td colspan="2" id="dato1"></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Tolva A</td>
        <td id="fuente1"><select name="int_ref1_tol1_pm" id="int_ref1_tol1_pm" style="width:120px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['int_ref1_tol1_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
            <?php
              do {  
              ?>
               <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_ref_copia['int_ref1_tol1_pm']))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
                          <?php
              } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                $rows = mysql_num_rows($materia_prima);
                if($rows > 0) {
                    mysql_data_seek($materia_prima, 0);
              	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
                }
              ?>
        </select></td>
        <td id="fuente1"><input name="int_ref1_tol1_porc1_pm" style="width:60px" min="0"step="0.01" type="number"  id="int_ref1_tol1_porc1_pm" placeholder="%" required="required"value="<?php echo $row_ref_copia['int_ref1_tol1_porc1_pm'] ?>"/></td>
        <td id="fuente1"><select name="int_ref2_tol1_pm" id="int_ref2_tol1_pm" style="width:120px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['int_ref2_tol1_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
            <?php
              do {  
              ?>
               <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_ref_copia['int_ref2_tol1_pm']))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
                          <?php
              } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                $rows = mysql_num_rows($materia_prima);
                if($rows > 0) {
                    mysql_data_seek($materia_prima, 0);
              	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
                }
              ?>
        </select></td>
        <td id="fuente1"><input name="int_ref2_tol1_porc2_pm" style="width:60px" min="0"step="0.01" type="number" id="int_ref2_tol1_porc2_pm" placeholder="%" required="required"value="<?php echo $row_ref_copia['int_ref2_tol1_porc2_pm'] ?>"/></td>
        <td id="fuente1"><select name="int_ref3_tol1_pm" id="int_ref3_tol1_pm" style="width:120px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['int_ref3_tol1_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
            <?php
                do {  
                ?>
                <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_ref_copia['int_ref3_tol1_pm']))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
                            <?php
                } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                  $rows = mysql_num_rows($materia_prima);
                  if($rows > 0) {
                      mysql_data_seek($materia_prima, 0);
                	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
                  }
                ?>
        </select></td>
        <td id="fuente1"><input name="int_ref3_tol1_porc3_pm" style="width:60px" min="0"step="0.01" type="number" id="int_ref3_tol1_porc3_pm" placeholder="%" required="required"value="<?php echo $row_ref_copia['int_ref3_tol1_porc3_pm'] ?>"/></td>
      </tr>
      <tr>
        <td id="fuente1">Tolva B</td>
        <td id="fuente1"><select name="int_ref1_tol2_pm" id="int_ref1_tol2_pm" style="width:120px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['int_ref1_tol2_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
            <?php
              do {  
              ?>
                <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_ref_copia['int_ref1_tol2_pm']))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
                          <?php
              } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                $rows = mysql_num_rows($materia_prima);
                if($rows > 0) {
                    mysql_data_seek($materia_prima, 0);
              	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
                }
              ?>
        </select></td>
        <td id="fuente1"><input name="int_ref1_tol2_porc1_pm" style="width:60px" min="0"step="0.01" type="number" id="int_ref1_tol2_porc1_pm" placeholder="%" required="required"value="<?php echo $row_ref_copia['int_ref1_tol2_porc1_pm'] ?>"/></td>
        <td id="fuente1"><select name="int_ref2_tol2_pm" id="int_ref2_tol2_pm" style="width:120px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['int_ref2_tol2_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
            <?php
              do {  
              ?>
                <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_ref_copia['int_ref2_tol2_pm']))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
                          <?php
              } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                $rows = mysql_num_rows($materia_prima);
                if($rows > 0) {
                    mysql_data_seek($materia_prima, 0);
              	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
                }
              ?>
        </select></td>
        <td id="fuente1"><input name="int_ref2_tol2_porc2_pm" style="width:60px" min="0"step="0.01" type="number" id="int_ref2_tol2_porc2_pm" placeholder="%" required="required"value="<?php echo $row_ref_copia['int_ref2_tol2_porc2_pm'] ?>"/></td>
        <td id="fuente1"><select name="int_ref3_tol2_pm" id="int_ref3_tol2_pm" style="width:120px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['int_ref3_tol2_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
            <?php
               do {  
               ?>
                <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_ref_copia['int_ref3_tol2_pm']))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
                           <?php
               } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                 $rows = mysql_num_rows($materia_prima);
                 if($rows > 0) {
                     mysql_data_seek($materia_prima, 0);
               	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
                 }
               ?>
        </select></td>
        <td id="fuente1"><input name="int_ref3_tol2_porc3_pm" style="width:60px" min="0"step="0.01" type="number" id="int_ref3_tol2_porc3_pm" placeholder="%" required="required"value="<?php echo $row_ref_copia['int_ref3_tol2_porc3_pm'] ?>"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Tolva C</td>
        <td id="fuente1"><select name="int_ref1_tol3_pm" id="int_ref1_tol3_pm" style="width:120px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['int_ref1_tol3_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
            <?php
               do {  
               ?>
                <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_ref_copia['int_ref1_tol3_pm']))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
                           <?php
               } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                 $rows = mysql_num_rows($materia_prima);
                 if($rows > 0) {
                     mysql_data_seek($materia_prima, 0);
               	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
                 }
               ?>
        </select></td>
        <td id="fuente1"><input name="int_ref1_tol3_porc1_pm" style="width:60px" min="0"step="0.01" type="number" id="int_ref1_tol3_porc1_pm" placeholder="%" required="required"value="<?php echo $row_ref_copia['int_ref1_tol3_porc1_pm'] ?>"/></td>
        <td id="fuente1"><select name="int_ref2_tol3_pm" id="int_ref2_tol3_pm" style="width:120px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['int_ref2_tol3_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
            <?php
              do {  
              ?>
                <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_ref_copia['int_ref2_tol3_pm']))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
                          <?php
              } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                $rows = mysql_num_rows($materia_prima);
                if($rows > 0) {
                    mysql_data_seek($materia_prima, 0);
              	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
                }
              ?>
        </select></td>
        <td id="fuente1"><input name="int_ref2_tol3_porc2_pm" style="width:60px" min="0"step="0.01" type="number" id="int_ref2_tol3_porc2_pm" placeholder="%" size="3"required="required"value="<?php echo $row_ref_copia['int_ref2_tol3_porc2_pm'] ?>"/></td>
        <td id="fuente1"><select name="int_ref3_tol3_pm" id="int_ref3_tol3_pm" style="width:120px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['int_ref3_tol3_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
            <?php
               do {  
               ?>
                <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_ref_copia['int_ref3_tol3_pm']))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
                           <?php
               } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                 $rows = mysql_num_rows($materia_prima);
                 if($rows > 0) {
                     mysql_data_seek($materia_prima, 0);
               	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
                 }
               ?>
        </select></td>
        <td id="fuente1"><input name="int_ref3_tol3_porc3_pm" style="width:60px" min="0"step="0.01" type="number"  id="int_ref3_tol3_porc3_pm" placeholder="%" required="required"value="<?php echo $row_ref_copia['int_ref3_tol3_porc3_pm'] ?>"/></td>
      </tr>
      <tr>
        <td id="fuente1">Tolva D</td>
        <td id="fuente1"><select name="int_ref1_tol4_pm" id="int_ref1_tol4_pm" style="width:120px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['int_ref1_tol4_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
            <?php
              do {  
              ?>
                <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_ref_copia['int_ref1_tol4_pm']))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
                          <?php
              } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                $rows = mysql_num_rows($materia_prima);
                if($rows > 0) {
                    mysql_data_seek($materia_prima, 0);
              	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
                }
              ?>
        </select></td>
        <td id="fuente1"><input name="int_ref1_tol4_porc1_pm" style="width:60px" min="0"step="0.01" type="number" id="int_ref1_tol4_porc1_pm" placeholder="%" required="required"value="<?php echo $row_ref_copia['int_ref1_tol4_porc1_pm'] ?>"/></td>
        <td id="fuente1"><select name="int_ref2_tol4_pm" id="int_ref2_tol4_pm" style="width:120px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['int_ref2_tol4_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
            <?php
              do {  
              ?>
               <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_ref_copia['int_ref2_tol4_pm']))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
                          <?php
              } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                $rows = mysql_num_rows($materia_prima);
                if($rows > 0) {
                    mysql_data_seek($materia_prima, 0);
              	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
                }
              ?>
        </select></td>
        <td id="fuente1"><input name="int_ref2_tol4_porc2_pm" style="width:60px" min="0"step="0.01" type="number" id="int_ref2_tol4_porc2_pm" placeholder="%" required="required"value="<?php echo $row_ref_copia['int_ref2_tol4_porc2_pm'] ?>"/></td>
        <td id="fuente1"><select name="int_ref3_tol4_pm" id="int_ref3_tol4_pm" style="width:120px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['int_ref3_tol4_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
            <?php
               do {  
               ?>
                 <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_ref_copia['int_ref3_tol4_pm']))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
                           <?php
               } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                 $rows = mysql_num_rows($materia_prima);
                 if($rows > 0) {
                     mysql_data_seek($materia_prima, 0);
               	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
                 }
               ?>
        </select></td>
        <td id="fuente1"><input name="int_ref3_tol4_porc3_pm" style="width:60px" min="0"step="0.01" type="number"  id="int_ref3_tol4_porc3_pm" placeholder="%" required="required"value="<?php echo $row_ref_copia['int_ref3_tol4_porc3_pm'] ?>"/></td>
      </tr>
<tr id="tr1">
        <td id="fuente1">RPM - %</td>
        <td id="fuente1"><input id="int_ref1_rpm_pm" name="int_ref1_rpm_pm" style="width:117px" type="number" min="0" step="1" value="<?php echo $row_ref_copia['int_ref1_rpm_pm'] ?>"/></td>
        <td id="fuente1"><input name="int_ref1_tol5_porc1_pm" style="width:60px" min="0"step="0.01" type="number"  id="int_ref1_tol5_porc1_pm" placeholder="%" required="required"value="<?php echo $row_ref_copia['int_ref1_tol5_porc1_pm'] ?>"/></td>
        <td id="fuente1"><input id="int_ref2_rpm_pm" name="int_ref2_rpm_pm" style="width:117px" type="number" min="0" step="1" value="<?php echo $row_ref_copia['int_ref2_rpm_pm'] ?>"/></td>
        <td id="fuente1"><input name="int_ref2_tol5_porc2_pm" style="width:60px" min="0"step="0.01" type="number" id="int_ref2_tol5_porc2_pm" placeholder="%" required="required"value="<?php echo $row_ref_copia['int_ref2_tol5_porc2_pm'] ?>"/></td>
        <td id="fuente1"><input id="int_ref3_rpm_pm" name="int_ref3_rpm_pm" style="width:117px" type="number" min="0" step="1" value="<?php echo $row_ref_copia['int_ref3_rpm_pm'] ?>"/></td>
        <td id="fuente1"><input name="int_ref3_tol5_porc3_pm" style="width:60px" min="0"step="0.01" type="number" id="int_ref3_tol5_porc3_pm" placeholder="%" required="required"value="<?php echo $row_ref_copia['int_ref3_tol5_porc3_pm'] ?>"/></td>
      </tr>      
      <tr id="tr1">
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">NOMBRE FORMULA</td>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
      </tr>
      <tr>
        <td id="fuente1">&nbsp;</td>
        <td colspan="6" id="fuente1"><textarea name="f" cols="45" rows="1" readonly="readonly" id="f"><?php 
			  $id_nombre=$row_formula_ref['int_nombre_io'];
			  $sqlnom="SELECT * FROM Tbl_formula_nombres WHERE id='$id_nombre'";
			  $resultnom= mysql_query($sqlnom);
			  $numnom= mysql_num_rows($resultnom);
			  if($numnom >='1')
			  { 
			  $nombre = mysql_result($resultnom, 0, 'nombre_fn');  echo $nombre;
			  }else{echo " ";}
		      ?></textarea> 
          Informacion del Item de la O.C</td>
        </tr>   
      <tr>
        <td id="fuente1"></td>
        <td colspan="2" id="fuente1"></td>
        <td colspan="2" id="fuente1"></td>
        <td colspan="2" id="fuente1"></td>
      </tr>
      <tr id="tr1">
        <td colspan="10" id="fuente2">OBSERVACIONES</td>
        </tr>
      <tr>
        <td colspan="10" id="fuente2"><textarea name="observ_pm" id="observ_pm" cols="45" rows="5"placeholder="OBSERVACIONES"onblur="conMayusculas(this)"></textarea></td>
      </tr>
      <tr id="tr1">
        <td colspan="10" id="fuente2">
        <input type="hidden" name="id_ref" id="id_ref" value="<?php echo $row_ref['id_ref']; ?>"/>
        <input type="hidden" name="cod_ref" id="cod_ref" value="<?php echo $row_ref['cod_ref']; ?>"/>
        <input type="hidden" name="id_proceso" id="id_proceso" value="1"/>
          <input type="hidden" name="b_borrado_pm" id="b_borrado_pm" value="0"/>
          <input class="botonGeneral" type="submit" name="SIGUIENTE" id="SIGUIENTE" value="SIGUIENTE" /></td>
      </tr>
    </table>
    <input type="hidden" name="MM_insert" value="form1">
    </form>
 <?php echo $conexion->header('footer'); ?>
</body>
</html>
 
<?php
mysql_free_result($usuario);
mysql_free_result($ultimo);
mysql_free_result($referencia);
mysql_free_result($materia_prima);
mysql_free_result($formula_ref);
mysql_free_result($ref_copia);
mysql_free_result($ref);

?>
