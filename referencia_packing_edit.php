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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE Tbl_egp SET responsable_egp=%s, codigo_usuario=%s, fecha_egp=%s, hora_egp=%s, estado_egp=%s, ancho_egp=%s, largo_egp=%s, solapa_egp=%s, calibre_egp=%s, adhesivo_egp=%s, tipo_bolsa_egp=%s, color1_egp=%s, pantone1_egp=%s, ubicacion1_egp=%s, color2_egp=%s, pantone2_egp=%s, ubicacion2_egp=%s, color3_egp=%s, pantone3_egp=%s, ubicacion3_egp=%s, color4_egp=%s, pantone4_egp=%s, ubicacion4_egp=%s, color5_egp=%s, pantone5_egp=%s, ubicacion5_egp=%s, color6_egp=%s, pantone6_egp=%s, ubicacion6_egp=%s, color7_egp=%s, pantone7_egp=%s, ubicacion7_egp=%s, color8_egp=%s, pantone8_egp=%s, ubicacion8_egp=%s,tipo_principal_egp=%s, tipo_inferior_egp=%s, cb_principal_egp=%s, cb_inferior_egp=%s, comienza_egp=%s,fecha_cad_egp=%s, arte_sum_egp=%s, ent_logo_egp=%s, orient_arte_egp=%s, unids_paq_egp=%s, unids_caja_egp=%s, marca_cajas_egp=%s, observacion5_egp=%s, responsable_modificacion=%s, fecha_modificacion=%s, hora_modificacion=%s, vendedor=%s WHERE n_egp=%s",
                       /*GetSQLValueString($_POST['cod_ref'], "int"),*/
                       GetSQLValueString($_POST['registro1_ref'], "text"),
                       GetSQLValueString($_POST['codigo_usuario'], "text"),
					   GetSQLValueString($_POST['fecha_registro1_ref'], "date"),
					   GetSQLValueString($_POST['hora_modificacion'], "text"),
                       GetSQLValueString($_POST['estado_ref'], "int"),
					   GetSQLValueString($_POST['ancho_ref'], "int"),
					   GetSQLValueString($_POST['largo_ref'], "int"),
					   GetSQLValueString($_POST['solapa_ref'], "double"),					  
					   GetSQLValueString($_POST['calibre_ref'], "int"),	
					   GetSQLValueString($_POST['adhesivo_ref'], "text"),				   					   					   
                       GetSQLValueString($_POST['tipo_bolsa_ref'], "text"),                     
                       GetSQLValueString($_POST['color1_egp'], "text"),
                       GetSQLValueString($_POST['pantone1_egp'], "text"),
                       GetSQLValueString($_POST['ubicacion1_egp'], "text"),
                       GetSQLValueString($_POST['color2_egp'], "text"),
                       GetSQLValueString($_POST['pantone2_egp'], "text"),
                       GetSQLValueString($_POST['ubicacion2_egp'], "text"),
                       GetSQLValueString($_POST['color3_egp'], "text"),
                       GetSQLValueString($_POST['pantone3_egp'], "text"),
                       GetSQLValueString($_POST['ubicacion3_egp'], "text"),
                       GetSQLValueString($_POST['color4_egp'], "text"),
                       GetSQLValueString($_POST['pantone4_egp'], "text"),
                       GetSQLValueString($_POST['ubicacion4_egp'], "text"),
                       GetSQLValueString($_POST['color5_egp'], "text"),
                       GetSQLValueString($_POST['pantone5_egp'], "text"),
                       GetSQLValueString($_POST['ubicacion5_egp'], "text"),
                       GetSQLValueString($_POST['color6_egp'], "text"),
                       GetSQLValueString($_POST['pantone6_egp'], "text"),
                       GetSQLValueString($_POST['ubicacion6_egp'], "text"),
                       GetSQLValueString($_POST['color7_egp'], "text"),
                       GetSQLValueString($_POST['pantone7_egp'], "text"),
                       GetSQLValueString($_POST['ubicacion7_egp'], "text"),
                       GetSQLValueString($_POST['color8_egp'], "text"),
                       GetSQLValueString($_POST['pantone8_egp'], "text"),
                       GetSQLValueString($_POST['ubicacion8_egp'], "text"),	
                       GetSQLValueString($_POST['tipo_principal_egp'], "text"),
                       GetSQLValueString($_POST['tipo_inferior_egp'], "text"),
					   GetSQLValueString($_POST['cb_principal_egp'], "text"),
                       GetSQLValueString($_POST['cb_inferior_egp'], "text"),
					   GetSQLValueString($_POST['comienza_egp'], "text"),					   				   					   
                       GetSQLValueString(isset($_POST['fecha_cad_egp']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['arte_sum_egp']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['ent_logo_egp']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['orient_arte_egp']) ? "true" : "", "defined","1","0"), 
                       GetSQLValueString($_POST['unids_paq_egp'], "int"),
                       GetSQLValueString($_POST['unids_caja_egp'], "int"),
                       GetSQLValueString($_POST['marca_cajas_egp'], "text"),					                        
                       GetSQLValueString($_POST['observacion5_egp'], "text"),
                       GetSQLValueString($_POST['responsable_modificacion'], "text"),
                       GetSQLValueString($_POST['fecha_modificacion'], "date"),
                       GetSQLValueString($_POST['hora_modificacion'], "text"),
					   GetSQLValueString($_POST['vendedor'], "int"),
					   GetSQLValueString($_POST['cod_ref'], "int")); 
  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

/*  $updateGoTo = "referencia_vista.php?id_ref=" . $_POST['id_ref'] . "&tipo=" . $_POST['tipo_usuario'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {*/
  $updateSQL2 = sprintf("UPDATE Tbl_referencia SET cod_ref=%s, version_ref=%s, n_egp_ref=%s, n_cotiz_ref=%s, tipo_bolsa_ref=%s,Str_presentacion=%s,Str_tratamiento=%s, ancho_ref=%s, largo_ref=%s, solapa_ref=%s, b_solapa_caract_ref=%s, calibre_ref=%s, peso_millar_ref=%s, Str_boca_entr_p=%s, Str_entrada_p=%s, Str_lamina1_p=%s, Str_lamina2_p=%s, impresion_ref=%s,num_pos_ref=%s, cod_form_ref=%s, adhesivo_ref=%s, estado_ref=%s, registro1_ref=%s, fecha_registro1_ref=%s, registro2_ref=%s, fecha_registro2_ref=%s, B_generica=%s, tipoCinta_ref=%s,valor_impuesto=%s WHERE id_ref=%s",
                       /*GetSQLValueString($_POST['id_ref'], "int"),*/
                       GetSQLValueString($_POST['cod_ref'], "text"),
                       GetSQLValueString($_POST['version_ref'], "text"),
                       GetSQLValueString($_POST['cod_ref'], "int"),
                       GetSQLValueString($_POST['n_cotiz_ref'], "int"),
					   GetSQLValueString($_POST['tipo_bolsa_ref'], "text"),
					   GetSQLValueString($_POST['Str_presentacion'], "text"),
					   GetSQLValueString($_POST['Str_tratamiento'], "text"),
                       GetSQLValueString($_POST['ancho_ref'], "double"),
                       GetSQLValueString($_POST['largo_ref'], "double"),
					   GetSQLValueString($_POST['solapa_ref'], "double"),
					   GetSQLValueString($_POST['valora'], "int"),
                       GetSQLValueString($_POST['calibre_ref'], "double"),
					   GetSQLValueString($_POST['peso_millar_ref'], "double"),
                       GetSQLValueString($_POST['Str_boca_entr_p'], "text"),
                       GetSQLValueString($_POST['Str_entrada_p'], "text"),
                       GetSQLValueString($_POST['Str_lamina1_p'], "text"),
                       GetSQLValueString($_POST['Str_lamina2_p'], "text"),  		   
                       GetSQLValueString($_POST['impresion_ref'], "text"),
					   GetSQLValueString(isset($_POST['num_pos_ref']) ? "true" : "", "defined","1","0"),
					   GetSQLValueString(isset($_POST['cod_form_ref']) ? "true" : "", "defined","1","0"),	
					   GetSQLValueString($_POST['adhesivo_ref'], "text"),				   
                       GetSQLValueString($_POST['estado_ref'], "int"),
                       GetSQLValueString($_POST['registro1_ref'], "text"),
                       GetSQLValueString($_POST['fecha_registro1_ref'], "date"),
                       GetSQLValueString($_POST['registro2_ref'], "text"),
                       GetSQLValueString($_POST['fecha_registro2_ref'], "date"),
					   GetSQLValueString($_POST['B_generica'], "text"),
					   GetSQLValueString($_POST['tipoCinta_ref'], "int"),
             GetSQLValueString($_POST['valor_impuesto'], "text"),
					   GetSQLValueString($_POST['id_ref'], "int"));
  mysql_select_db($database_conexion1, $conexion1);
  $Result2 = mysql_query($updateSQL2, $conexion1) or die(mysql_error());

  $updateGoTo = "referencia_packing_vista.php?cod_ref=" . $_POST['cod_ref'] . "&tipo=" . $_POST['tipo_usuario'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario); 

//EL ID_REF ES ENVIADO DESDE VISTA DE REFERENCIA
$colname_referencia_editar = "-1";
if (isset($_GET['id_ref'])) {
  $colname_referencia_editar = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_referencia_editar = sprintf("SELECT * FROM Tbl_referencia WHERE id_ref = '%s'", $colname_referencia_editar);
$referencia_editar = mysql_query($query_referencia_editar, $conexion1) or die(mysql_error());
$row_referencia_editar = mysql_fetch_assoc($referencia_editar);
$totalRows_referencia_editar = mysql_num_rows($referencia_editar);

mysql_select_db($database_conexion1, $conexion1);
$query_ver_nref = ("SELECT * FROM Tbl_referencia ORDER BY id_ref DESC");
$ver_nref = mysql_query($query_ver_nref, $conexion1) or die(mysql_error());
$row_ver_nref = mysql_fetch_assoc($ver_nref);
$totalRows_ver_nref = mysql_num_rows($ver_nref);

$colname_ver_ref = "-1";
if (isset($_GET['id_ref'])) {
  $colname_ver_ref= (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_ref = sprintf("SELECT * FROM Tbl_referencia,Tbl_cotiza_packing WHERE Tbl_referencia.id_ref='%s' and Tbl_referencia.cod_ref=Tbl_cotiza_packing.N_referencia_c", $colname_ver_ref);
$ver_ref = mysql_query($query_ver_ref, $conexion1) or die(mysql_error());
$row_ver_ref = mysql_fetch_assoc($ver_ref);
$totalRows_ver_ref = mysql_num_rows($ver_ref);

$colname_ver_egp = "-1";
if (isset($_GET['id_ref'])) {
  $colname_ver_egp = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_egp = sprintf("SELECT * FROM Tbl_referencia,Tbl_egp WHERE Tbl_referencia.id_ref = '%s' and Tbl_referencia.cod_ref=Tbl_egp.n_egp", $colname_ver_egp);
$ver_egp = mysql_query($query_ver_egp, $conexion1) or die(mysql_error());
$row_ver_egp = mysql_fetch_assoc($ver_egp);
$totalRows_ver_egp = mysql_num_rows($ver_egp);

$colname_ref_verif = "-1";
if (isset($_GET['id_ref'])) {
  $colname_ref_verif = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ref_verif = sprintf("SELECT * FROM Tbl_verificacion_packing WHERE id_ref_verif_p = %s AND estado_arte_verif_p = '2'", $colname_ref_verif);
$ref_verif = mysql_query($query_ref_verif, $conexion1) or die(mysql_error());
$row_ref_verif = mysql_fetch_assoc($ref_verif);
$totalRows_ref_verif = mysql_num_rows($ref_verif);

mysql_select_db($database_conexion1, $conexion1);
$query_vendedores = "SELECT * FROM vendedor ORDER BY nombre_vendedor ASC";
$vendedores = mysql_query($query_vendedores, $conexion1) or die(mysql_error());
$row_vendedores = mysql_fetch_assoc($vendedores);
$totalRows_vendedores = mysql_num_rows($vendedores);
//REF CLIENTE
$colname_refcliente = "-1";
if (isset($_GET['id_ref'])) {
  $colname_refcliente = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_refcliente = sprintf("SELECT Tbl_referencia.cod_ref,Tbl_refcliente.id_refcliente,Tbl_refcliente.int_ref_ac_rc,Tbl_refcliente.str_ref_cl_rc,Tbl_refcliente.str_descripcion_rc FROM Tbl_referencia,Tbl_refcliente WHERE Tbl_referencia.id_ref = '%s' and Tbl_referencia.cod_ref=Tbl_refcliente.int_ref_ac_rc", $colname_refcliente);
$refcliente = mysql_query($query_refcliente, $conexion1) or die(mysql_error());
$row_refcliente = mysql_fetch_assoc($refcliente);
$totalRows_refcliente = mysql_num_rows($refcliente);
//INSUMOS
mysql_select_db($database_conexion1, $conexion1);
$query_insumo = "SELECT id_insumo,codigo_insumo,descripcion_insumo FROM insumo WHERE clase_insumo='5' ORDER BY  descripcion_insumo ASC";
$insumo = mysql_query($query_insumo, $conexion1) or die(mysql_error());
$row_insumo = mysql_fetch_assoc($insumo);
$totalRows_insumo = mysql_num_rows($insumo);
//INSUMOS TIPO ADHESIVO
mysql_select_db($database_conexion1, $conexion1);
$query_insumo3 = "SELECT id_insumo,descripcion_insumo FROM insumo WHERE clase_insumo='2' AND estado_insumo='0' ORDER BY descripcion_insumo ASC";
$insumo3 = mysql_query($query_insumo3, $conexion1) or die(mysql_error());
$row_insumo3 = mysql_fetch_assoc($insumo3);
$totalRows_insumo3 = mysql_num_rows($insumo3);

//SELECTS COMBOS
 $materiasss=$conexion->llenaSelect('insumo',"WHERE clase_insumo='8' AND estado_insumo='0' ", "ORDER BY descripcion_insumo ASC","id_insumo, descripcion_insumo " );

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" /> 
<script src="js/jquery-1.11.2.min.js"></script>
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

<!-- select2 -->
<link href="select2/css/select2.min.css" rel="stylesheet"/>
<script src="select2/js/select2.min.js"></script>

<!-- css Bootstrap-->
<link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"> 

</head>
<body>
<?php echo $conexion->header('listas'); ?><ul id="menuhorizontal">
  <?php echo $row_usuario['nombre_usuario'] ?>

	<li><a href="produccion_registro_extrusion_listado.php">EXTRUSION</a></li>
    <li><a href="produccion_sellado_add.php">SELLADO</a></li>	    
    <li><a href="produccion_registro_impresion_listado.php">IMPRESION</a></li>	
    
	</ul>
 
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" onsubmit="return validacion_tipocinta()">
    <table class="table table-bordered table-sm">
      <tr id="tr1">
        <td colspan="5" id="titulo2">REFERENCIA ( PACKING LIST) </td>
        </tr>
      <tr>
        <td rowspan="7" id="dato2"><img src="images/logoacyc.jpg"/></td>
        <td colspan="4" id="dato3"><a href="referencia_packing_vista.php?id_ref=<?php echo $row_referencia_editar['id_ref']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>"><img src="images/hoja.gif" alt="VISTA IMPRESION" title="VISTA IMPRESION" border="0" /></a><a href="referencia_cliente.php?id_ref=<?php echo $row_referencia_editar['id_ref']; ?>"><img src="images/cliente.gif" alt="CLIENTES" title="CLIENTES" border="0"></a><a href="referencia_copia.php"><img src="images/opciones.gif" style="cursor:hand;" alt="LISTADO COTIZACIONES"title="LISTADO COTIZACIONES" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" title="MENU PRINCIPAL" border="0"/></a><a href="referencias_p.php"><img src="images/a.gif" alt="REF'S ACTIVAS" title="REF'S ACTIVAS" border="0" style="cursor:hand;"/></a><a href="referencias_inactivas.php"><img src="images/i.gif" alt="REF'S INACTIVAS" title="REF'S INACTIVAS" border="0" style="cursor:hand;"/></a><?php $ref=$row_referencia_editar['id_ref'];
	  $sqlrevision="SELECT * FROM Tbl_revision_packing WHERE id_ref_rev_p='$ref'";
	  $resultrevision= mysql_query($sqlrevision);
	  $row_revision = mysql_fetch_assoc($resultrevision);
	  $numrev= mysql_num_rows($resultrevision);
	  if($numrev >='1')
	  { ?><a href="revision_packing_vista.php?id_rev_p=<?php echo $row_revision['id_rev_p']; ?>" target="_top" ><img src="images/r.gif" alt="REVISION" title="REVISION" border="0" style="cursor:hand;"></a><?php } else { ?><a href="revision_packing_add.php?id_ref=<?php echo $ref; ?>" target="_top"><img src="images/r.gif" alt="ADD REVISION" title="ADD REVISION" border="0" style="cursor:hand;" /></a><?php } ?><a href="verificacion_referencia_p.php?id_ref=<?php echo $row_referencia_editar['id_ref']; ?>"><img src="images/v.gif" alt="VERIFICACION" title="VERIFICACION" border="0" style="cursor:hand;" /></a><?php $ref=$row_referencia_editar['id_ref'];
	  $sqlval="SELECT * FROM Tbl_validacion_packing WHERE id_ref_val_p='$ref'";
	  $resultval= mysql_query($sqlval);
	  $row_val = mysql_fetch_assoc($resultval);
	  $numval= mysql_num_rows($resultval);
	  if($numval >='1')
	  { ?><a href="validacion_packing_vista.php?id_val_p=<?php echo $row_val['id_val_p']; ?>"><img src="images/v.gif" alt="VALIDACION" title="VALIDACION" border="0" style="cursor:hand;" /></a><?php } else { ?><a href="validacion_packing_add.php?id_ref=<?php echo $ref; ?>" target="_top"><img src="images/v.gif" alt="ADD VALIDACION" title="ADD VALIDACION" border="0" style="cursor:hand;" /></a><?php } $ref=$row_referencia_editar['id_ref'];
	  $sqlft="SELECT * FROM TblFichaTecnica WHERE id_ref_ft='$ref'";
	  $resultft= mysql_query($sqlft);
	  $row_ft = mysql_fetch_assoc($resultft);
	  $numft= mysql_num_rows($resultft);
	  if($numft >='1')
	  { ?><a href="ficha_tecnica_vista.php?n_ft=<?php echo $row_ft['n_ft']; ?>"><img src="images/f.gif" alt="FICHA TECNICA" title="FICHA TECNICA" border="0" style="cursor:hand;" /></a><?php } else { ?><a href="ficha_tecnica_add.php?id_ref=<?php echo $ref; ?>" target="_top"><img src="images/f.gif" alt="ADD FICHA TECNICA" title="ADD FICHA TECNICA" border="0" style="cursor:hand;" /></a><?php } ?></td>
        </tr>
      <tr id="tr1">
        <td nowrap="nowrap" id="dato2">Fecha Ingreso 
          <input name="fecha_registro1_ref" type="text" id="fecha_b" value="<?php echo date("Y-m-d"); ?>" size="10" /></td>
        <td colspan="3" id="dato3">
          Ingresado por          
            <input name="registro1_ref" type="text" value="<?php echo $row_referencia_editar['registro1_ref']; ?>" size="27" /></td>
        </tr>
      <tr id="tr3">
        <td nowrap="nowrap" id="fuente2"><strong>REFERENCIA - VERSION</strong></td>
        <td colspan="2" nowrap="nowrap" id="fuente2">Estado</td>
        <td id="fuente2">Arte</td>
      </tr>
      <tr id="tr3">
        <td nowrap="nowrap" id="fuente2"><input name="cod_ref" type="text" value="<?php echo $row_referencia_editar['cod_ref']; ?>" size="5" readonly="readonly"/>
-
  <input name="version_ref" type="text" value="00" size="2" /></td>
        <td colspan="2" id="fuente2"><select name="estado_ref" id="estado_ref">
          <option value="1" <?php if (!(strcmp(1, $row_referencia_editar['estado_ref']))) {echo "selected=\"selected\"";} ?>>Activa</option><option value="0" <?php if (!(strcmp(0, $row_referencia_editar['estado_ref']))) {echo "selected=\"selected\"";} ?>>Inactiva</option>
        </select></td>
        <td id="dato2"><a href="javascript:verFoto('archivo/<?php echo $row_ref_verif['userfile_p'];?>','610','490')"> <?php echo $row_ref_verif['userfile_p']; ?> </a> </td>
      </tr>
      <tr>
        <td colspan="4" nowrap="nowrap" id="fuente1"><?php  if ($row_refcliente['id_refcliente']!="") {?>
        <a href="javascript:verFoto('ref_ac_ref_cliente_edit.php?id_refcliente=<?php echo $row_refcliente['id_refcliente'];?>','840','370')"><?php echo "Ver nombre de la Ref Aquí"; ?></a><?php }else{?>
            <a href="javascript:verFoto('ref_ac_ref_cliente_add.php','840','390')"><?php echo "Agregue Nombre a la Ref Aquí"; ?></a><?php }?></td>
        </tr>
      <tr id="tr1">
        <td nowrap="nowrap" id="fuente2">Cotizaci&oacute;n N&ordm;</td>
        <td colspan="2" nowrap="nowrap" id="fuente2">Referencia Generica</td>
        <td id="fuente2">Fecha Arte</td>
      </tr>
      <tr>
        <td id="dato2"><input name="n_cotiz_ref" type="text" value="<?php echo $row_referencia_editar['n_cotiz_ref']; ?>" size="5" readonly="readonly"/></td>
        <td colspan="2" id="dato2"><select name="B_generica" id="B_generica">
          <option value=""<?php if (!(strcmp('', $row_referencia_editar['B_generica']))) {echo "selected=\"selected\"";} ?>></option>
          </option>
          <option value="1" <?php if (!(strcmp(1, $row_referencia_editar['B_generica']))) {echo "selected=\"selected\"";} ?>>SI</option>
          <option value="0" <?php if (!(strcmp(0, $row_referencia_editar['B_generica']))) {echo "selected=\"selected\"";} ?>>NO</option>
        </select></td>
        <td id="dato2"><?php echo $row_ref_verif['fecha_aprob_arte_verif_p']; ?></td>
      </tr>
      <tr id="tr1">
        <td colspan="5" id="titulo4">DATOS GENERALES DE LA REFERENCIA </td>
        </tr>
      <tr id="tr1">
        <td id="fuente1">ANCHO</td>
        <td id="fuente1">LARGO</td>
        <td colspan="2" id="fuente1">SOLAPA</td>
        <td id="fuente1">CALIBRE</td>
      </tr>
      <tr>
        <td id="dato1"><input name="ancho_ref" id="ancho_ref" type="number" style="width:90px" min="0.00" step="0.01" required="required" value="<?php echo $row_referencia_editar['ancho_ref']; ?>"/></td>
        <td id="dato1"><input name="largo_ref" id="largo_ref" type="number" style="width:90px" min="0.00" step="0.01" required="required" value="<?php echo $row_referencia_editar['largo_ref']; ?>"/></td>
        <td id="dato1"><input type="radio" name="valora" <?php if (!(strcmp($row_referencia_editar['b_solapa_caract_ref'],2))) {echo "checked=\"checked\"";} ?> value="2" onclick="calcular_pesom();"/> Sencilla<br />
          <input type="radio" name="valora" <?php if (!(strcmp($row_referencia_editar['b_solapa_caract_ref'],1))) {echo "checked=\"checked\"";} ?> value="1" onclick="calcular_pesom();"/>
          Doble</td>
        <td id="dato1"><input name="solapa_ref" id="solapa_ref" type="number" style="width:50px" min="0.00" step="0.01" required="required" value="<?php echo $row_referencia_editar['solapa_ref']; ?>" onblur="validarRadio(),calcular_pesom()"/></td>
        <td id="dato1"><input name="calibre_ref" id="calibre_ref" type="number" style="width:50px" min="0.00" step="0.01" required="required" value="<?php echo $row_referencia_editar['calibre_ref']; ?>" onBlur="calcular_pesom()"/>
          <input name="bolsillo_guia_ref" type="hidden" id="bolsillo_guia_ref" value="<?php echo $row_referencia_editar['bolsillo_guia_ref']; ?>" size="10"/>
          <input name="B_fuelle" type="hidden" id="B_fuelle" value="<?php echo $row_referencia_editar['N_fuelle']?>" size="10" /></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Boca de Entrada:</td>
        <td id="fuente1">PESO MILLAR</td>
        <td colspan="2" id="fuente1">Lamina 1 (Adhesivo)</td>
        <td id="fuente1">Lamina 2</td>
      </tr>
      <tr>
        <td id="dato1"><select name="Str_boca_entr_p" id="Str_boca_entr_p" style="width:100px">
          <option></option>
          <option value="HORIZONTAL"<?php if (!(strcmp("HORIZONTAL", $row_referencia_editar['Str_boca_entr_p']))) {echo "selected=\"selected\"";} ?>>HORIZONTAL</option>
          <option value="VERTICAL"<?php if (!(strcmp("VERTICAL", $row_referencia_editar['Str_boca_entr_p']))) {echo "selected=\"selected\"";} ?>>VERTICAL</option>
        </select></td>
        <td id="dato1"><input name="peso_millar_ref" type="text" id="peso_millar_ref" onBlur="calcular_pesom();" value="<?php echo $row_referencia_editar['peso_millar_ref']; ?>" size="10" readonly="readonly"/></td>
        <td colspan="2" id="dato1"><select name="Str_lamina1_p" id="Str_lamina1_p" style="width:100px">
          <option></option>
          <option value="PIGMENTADO"<?php if (!(strcmp("PIGMENTADO", $row_referencia_editar['Str_lamina1_p']))) {echo "selected=\"selected\"";} ?>>PIGMENTADO</option>
          <option value="TRANSPARENTE"<?php if (!(strcmp("TRANSPARENTE", $row_referencia_editar['Str_lamina1_p']))) {echo "selected=\"selected\"";} ?>>TRANSPARENTE</option>
        </select></td>
        <td id="dato1"><select name="Str_lamina2_p" id="Str_lamina2_p" style="width:100px">
          <option></option>
          <option value="PIGMENTADO"<?php if (!(strcmp("PIGMENTADO", $row_referencia_editar['Str_lamina2_p']))) {echo "selected=\"selected\"";} ?>>PIGMENTADO</option>
          <option value="TRANSPARENTE"<?php if (!(strcmp("TRANSPARENTE", $row_referencia_editar['Str_lamina2_p']))) {echo "selected=\"selected\"";} ?>>TRANSPARENTE</option>
        </select></td>
      </tr>
      <tr>
        <td id="fuente1">Ubicacion de la Entrada:</td>
        <td id="fuente1">PRESENTACION</td>
        <td id="fuente1">TRATAMIENTO</td>
        <td id="fuente1">ADHESIVO</td>
        <td id="fuente1">Tipo</td>
      </tr>
      <tr>
        <td id="dato4"><select name="Str_entrada_p" id="Str_entrada_p" style="width:100px">
          <option></option>
          <option value="ANVERSO"<?php if (!(strcmp("ANVERSO", $row_referencia_editar['Str_entrada_p']))) {echo "selected=\"selected\"";} ?>>ANVERSO</option>
          <option value="REVERSO"<?php if (!(strcmp("REVERSO", $row_referencia_editar['Str_entrada_p']))) {echo "selected=\"selected\"";} ?>>REVERSO</option>
        </select></td>
        <td id="dato4"><select name="Str_presentacion" id="Str_presentacion" style="width:100px">
          <option value=""></option>
          <option value="N.A"<?php if (!(strcmp('N.A', $row_referencia_editar['Str_presentacion']))) {echo "selected=\"selected\"";} ?>>N.A</option>
          <option value="LAMINA" <?php if (!(strcmp('LAMINA', $row_referencia_editar['Str_presentacion']))) {echo "selected=\"selected\"";} ?>>LAMINA</option>
          <option value="TUBULAR" <?php if (!(strcmp('TUBULAR', $row_referencia_editar['Str_presentacion']))) {echo "selected=\"selected\"";} ?>>TUBULAR</option>
          <option value="SEMITUBULAR" <?php if (!(strcmp('SEMITUBULAR', $row_referencia_editar['Str_presentacion']))) {echo "selected=\"selected\"";} ?>>SEMITUBULAR</option>
        </select></td>
        <td id="dato4"><select name="Str_tratamiento" id="Str_tratamiento" style="width:100px">
          <option value="N.A"<?php if (!(strcmp('N.A', $row_referencia_editar['Str_tratamiento']))) {echo "selected=\"selected\"";} ?>>N.A</option>
          <option value="UNA CARA" <?php if (!(strcmp('UNA CARA', $row_referencia_editar['Str_tratamiento']))) {echo "selected=\"selected\"";} ?>>UNA CARA</option>
          <option value="DOBLE CARA" <?php if (!(strcmp('DOBLE CARA', $row_referencia_editar['Str_tratamiento']))) {echo "selected=\"selected\"";} ?>>DOBLE CARA</option>
        </select></td>
        <td id="dato4"><select name="adhesivo_ref" id="adhesivo" style="width:100px">
          <option value="N.A" <?php if (!(strcmp("N.A", $row_referencia_editar['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="HOT MELT" <?php if (!(strcmp("HOT MELT", $row_referencia_editar['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>HOT MELT</option>
          <option value="CINTA PERMANENTE" <?php if (!(strcmp("CINTA PERMANENTE", $row_referencia_editar['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>CINTA PERMANENTE</option>
          <option value="CINTA RESELLABLE" <?php if (!(strcmp("CINTA RESELLABLE", $row_referencia_editar['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>CINTA RESELLABLE</option>
          <option value="CINTA DE SEGURIDAD" <?php if (!(strcmp("CINTA DE SEGURIDAD", $row_referencia_editar['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>CINTA DE SEGURIDAD</option>
        </select></td>
        <td id="dato4"><select name="tipoCinta_ref" id="tipocinta" style="width:100px">
         <option value="" <?php if (!(strcmp("", $row_referencia_editar['tipoCinta_ref']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <?php
do {  
?>
          <option value="<?php echo $row_insumo3['id_insumo']?>"<?php if (!(strcmp($row_insumo3['id_insumo'], $row_referencia_editar['tipoCinta_ref']))) {echo "selected=\"selected\"";} ?>><?php echo $row_insumo3['descripcion_insumo']?></option>
          <?php
} while ($row_insumo3 = mysql_fetch_assoc($insumo3));
  $rows = mysql_num_rows($insumo3);
  if($rows > 0) {
      mysql_data_seek($insumo3, 0);
	  $row_insumo3 = mysql_fetch_assoc($insumo3);
  }
?>
        </select>
      </td>
      </tr>
      <tr>
        <td colspan="9" id="dato4">
            IMPUESTO $  <strong><input name="valor_impuesto" id="valor_impuesto" style="width:50px" type="text" value="<?php echo $row_referencia_editar['valor_impuesto']?>" <?php if(!$_SESSION['superacceso']){ echo "readonly"; } ?> /></strong>
        </td>
      </tr>
      <tr id="tr1">
        <td colspan="5" id="dato1">Ultima Actualizaci&oacute;n : 
          <input name="registro2_ref" type="hidden" value="<?php echo $row_usuario['nombre_usuario']; ?>" />
          <?php echo $row_referencia_editar['registro2_ref']; ?>
          <input name="fecha_registro2_ref" type="hidden" value="<?php echo date("Y-m-d"); ?>" />
          <?php echo $row_referencia_editar['fecha_registro2_ref']; ?>
          <input name="tipo_usuario" type="hidden" id="tipo_usuario" value="<?php echo $row_usuario['tipo_usuario']; ?>" /></td>
        </tr>
    </table>
      
        
        <table class="table table-bordered table-sm">
      <tr id="tr1">
        <td colspan="3" id="titulo4">DATOS ESPECIFICOS DE LA REFERENCIA</td>
        </tr>
      <tr id="tr1">
        <td id="fuente1">Numero de Colores</td>
        <td id="fuente1"><input <?php if (!(strcmp($row_referencia_editar['num_pos_ref'],1))) {echo "checked=\"checked\"";} ?> name="num_pos_ref" type="checkbox" value="1" />
Numeracion </td>
        <td id="fuente1"></td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="impresion_ref" size="5" id="impresion_ref" value="<?php echo $row_referencia_editar['impresion_ref'] ?>" />
          <?php //echo  "Lleva ".$row_ver_ref['impresion_ref']." Colores"?></td>
        <td id="dato1"><input <?php if (!(strcmp($row_referencia_editar['cod_form_ref'],1))) {echo "checked=\"checked\"";} ?> name="cod_form_ref" type="checkbox" value="1" />
Codigo de Barras </td>
        <td id="dato1">&nbsp;</td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Color 1 </td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color1_egp" value="<?php echo $row_ver_egp['color1_egp']; ?>" size="20"onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1">
        <select name="pantone1_egp" id="pantone1_egp" class="busqueda selectsGrande" >
           <option value=""<?php if (!(strcmp("", $row_ver_egp['pantone1_egp']))) {echo "selected=\"selected\"";} ?>>COLOR</option> 
           <?php foreach($materiasss as $row_materia_prima ) { ?>
               <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'],$row_ver_egp['pantone1_egp']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
             </option>
           <?php } ?> 
         </select>
       </td>
        <td id="dato1"><input type="text" name="ubicacion1_egp" value="<?php echo $row_ver_egp['ubicacion1_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Color 2 </td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color2_egp" value="<?php echo $row_ver_egp['color2_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1">
          <select name="pantone2_egp" id="pantone2_egp" class="busqueda selectsGrande" >
           <option value=""<?php if (!(strcmp("", $row_ver_egp['pantone2_egp']))) {echo "selected=\"selected\"";} ?>>COLOR</option> 
           <?php foreach($materiasss as $row_materia_prima ) { ?>
               <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'],$row_ver_egp['pantone2_egp']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
             </option>
           <?php } ?> 
         </select></td>
        <td id="dato1"><input type="text" name="ubicacion2_egp" value="<?php echo $row_ver_egp['ubicacion2_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Color 3 </td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color3_egp" value="<?php echo $row_ver_egp['color3_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1">
        <select name="pantone3_egp" id="pantone3_egp" class="busqueda selectsGrande" >
           <option value=""<?php if (!(strcmp("", $row_ver_egp['pantone3_egp']))) {echo "selected=\"selected\"";} ?>>COLOR</option> 
           <?php foreach($materiasss as $row_materia_prima ) { ?>
               <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'],$row_ver_egp['pantone3_egp']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
             </option>
           <?php } ?> 
         </select>
       </td>
        <td id="dato1"><input type="text" name="ubicacion3_egp" value="<?php echo $row_ver_egp['ubicacion3_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Color 4</td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color4_egp" value="<?php echo $row_ver_egp['color4_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1">
        <select name="pantone4_egp" id="pantone4_egp" class="busqueda selectsGrande" >
           <option value=""<?php if (!(strcmp("", $row_ver_egp['pantone4_egp']))) {echo "selected=\"selected\"";} ?>>COLOR</option> 
           <?php foreach($materiasss as $row_materia_prima ) { ?>
               <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'],$row_ver_egp['pantone4_egp']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
             </option>
           <?php } ?> 
         </select>
       </td>
        <td id="dato1"><input type="text" name="ubicacion4_egp" value="<?php echo $row_ver_egp['ubicacion4_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Color 5 </td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color5_egp" value="<?php echo $row_ver_egp['color5_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1">
        <select name="pantone5_egp" id="pantone5_egp" class="busqueda selectsGrande" >
           <option value=""<?php if (!(strcmp("", $row_ver_egp['pantone5_egp']))) {echo "selected=\"selected\"";} ?>>COLOR</option> 
           <?php foreach($materiasss as $row_materia_prima ) { ?>
               <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'],$row_ver_egp['pantone5_egp']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
             </option>
           <?php } ?> 
         </select>
       </td>
        <td id="dato1"><input type="text" name="ubicacion5_egp" value="<?php echo $row_ver_egp['ubicacion5_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Color 6 </td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color6_egp" value="<?php echo $row_ver_egp['color6_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1">
        <select name="pantone6_egp" id="pantone6_egp" class="busqueda selectsGrande" >
           <option value=""<?php if (!(strcmp("", $row_ver_egp['pantone6_egp']))) {echo "selected=\"selected\"";} ?>>COLOR</option> 
           <?php foreach($materiasss as $row_materia_prima ) { ?>
               <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'],$row_ver_egp['pantone6_egp']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
             </option>
           <?php } ?> 
         </select>
       </td>
        <td id="dato1"><input type="text" name="ubicacion6_egp" value="<?php echo $row_ver_egp['ubicacion6_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Color 7</td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color7_egp" value="<?php echo $row_ver_egp['color7_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1">
        <select name="pantone7_egp" id="pantone7_egp" class="busqueda selectsGrande" >
           <option value=""<?php if (!(strcmp("", $row_ver_egp['pantone7_egp']))) {echo "selected=\"selected\"";} ?>>COLOR</option> 
           <?php foreach($materiasss as $row_materia_prima ) { ?>
               <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'],$row_ver_egp['pantone7_egp']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
             </option>
           <?php } ?> 
         </select>
       </td>
        <td id="dato1"><input type="text" name="ubicacion7_egp" value="<?php echo $row_ver_egp['ubicacion7_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>  
      <tr id="tr1">
        <td id="fuente1">Color 8</td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color8_egp" value="<?php echo $row_ver_egp['color8_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1">
        <select name="pantone8_egp" id="pantone8_egp" class="busqueda selectsGrande" >
           <option value=""<?php if (!(strcmp("", $row_ver_egp['pantone8_egp']))) {echo "selected=\"selected\"";} ?>>COLOR</option> 
           <?php foreach($materiasss as $row_materia_prima ) { ?>
               <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'],$row_ver_egp['pantone8_egp']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
             </option>
           <?php } ?> 
         </select>
       </td>
        <td id="dato1"><input type="text" name="ubicacion8_egp" value="<?php echo $row_ver_egp['ubicacion8_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>
      <tr id="tr1">
        <td id="detalle2">POSICION</td>
        <td id="detalle2">TIPO DE NUMERACION </td>
        <td id="detalle2">FORMATO &amp; CODIGO DE BARAS </td>
      </tr>
      <tr>
        <td id="detalle1">Principal</td>
        <td id="detalle2"><select name="tipo_principal_egp" id="tipo_principal_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_ver_egp['tipo_principal_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="Normal" <?php if (!(strcmp("Normal", $row_ver_egp['tipo_principal_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
          </select></td>
        <td id="detalle2"><select name="cb_principal_egp" id="cb_principal_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_ver_egp['cb_principal_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="EAN128" <?php if (!(strcmp("EAN128", $row_ver_egp['cb_principal_egp']))) {echo "selected=\"selected\"";} ?>>EAN128</option>
          </select></td>
      </tr>
      <tr>
        <td id="detalle1">Inferior</td>
        <td id="detalle2"><select name="tipo_inferior_egp" id="tipo_inferior_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_ver_egp['tipo_inferior_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="Normal" <?php if (!(strcmp("Normal", $row_ver_egp['tipo_inferior_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
        </select></td>
        <td id="detalle2"><select name="cb_inferior_egp" id="cb_inferior_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_ver_egp['cb_inferior_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="EAN128" <?php if (!(strcmp("EAN128", $row_ver_egp['cb_inferior_egp']))) {echo "selected=\"selected\"";} ?>>EAN128</option>
        </select></td>
      </tr>
      <tr>
        <td id="detalle3">Comienza en </td>
        <td id="detalle2"><input type="text" name="comienza_egp" value="<?php echo $row_ver_egp['comienza_egp']; ?>" size="20" onKeyUp="return ValNumero(this)"/></td>
        <td id="detalle1"><input <?php if (!(strcmp($row_ver_egp['fecha_cad_egp'],1))) {echo "checked=\"checked\"";} ?> name="fecha_cad_egp" type="checkbox" value="1" />
Incluir Fecha de Caducidad </td>
      </tr>      
      <tr>
        <td colspan="2" nowrap="nowrap" id="dato1">Cyreles ?:
          <?php if ($row_ver_ref['B_cyreles']==1){ echo "SI ";}else {echo "NO";}?>
          Se Facturan Artes y Planchas</td>
        <td nowrap="nowrap" id="dato1">&nbsp;</td>
      </tr>
      <tr>
        <td nowrap="nowrap" id="dato1"><input <?php if (!(strcmp($row_ver_egp['arte_sum_egp'],1))) {echo "checked=\"checked\"";} ?> name="arte_sum_egp" type="checkbox" value="1">
          Arte suministrado por el cliente </td>
        <td nowrap="nowrap" id="dato1"><input <?php if (!(strcmp($row_ver_egp['ent_logo_egp'],1))) {echo "checked=\"checked\"";} ?> name="ent_logo_egp" type="checkbox" value="1" />
          Entrego Logos de la entidad</td>
        <td nowrap="nowrap" id="dato1"><input <?php if (!(strcmp($row_ver_egp['orient_arte_egp'],1))) {echo "checked=\"checked\"";} ?> name="orient_arte_egp" type="checkbox" value="1" />
          Solicito orientaci&oacute;n en el arte </td>
      </tr>
      <tr>
        <td colspan="3" nowrap="nowrap" id="fuente4">&nbsp;</td>
      </tr>
<tr id="tr1">
        <td id="dato1">Unidades por Paquete </td>
        <td id="dato1">Unidades por Caja </td>
        <td id="dato1">Medida de la Caja</td>
      </tr>
      <tr>
        <td id="dato1"><input type="number" name="unids_paq_egp" value="<?php echo $row_ver_egp['unids_paq_egp']; ?>" required="required" size="20" /></td>
        <td id="dato1"><input type="number" name="unids_caja_egp" value="<?php echo $row_ver_egp['unids_caja_egp']; ?>" required="required" size="20" /></td>
        <td id="dato1">
      <select name="marca_cajas_egp" id="opciones" style="width:150px">
      <option value="0"<?php if (!(strcmp(0, $row_ver_egp['marca_cajas_egp']))) {echo "selected=\"selected\"";} ?>>SELECCIONE</option>
        <?php
do {  
?>
        <option value="<?php echo $row_insumo['id_insumo']?>"<?php if (!(strcmp($row_insumo['id_insumo'], $row_ver_egp['marca_cajas_egp']))) {echo "selected=\"selected\"";} ?>><?php echo $row_insumo['descripcion_insumo']?></option>
        <?php
} while ($row_insumo = mysql_fetch_assoc($insumo));
  $rows = mysql_num_rows($insumo);
  if($rows > 0) {
      mysql_data_seek($insumo, 0);
	  $row_insumo = mysql_fetch_assoc($insumo);
  }
?>
      </select>
            </td>
      </tr> 
      <tr>
        <td colspan="3" nowrap="nowrap" id="fuente4">&nbsp;</td>
      </tr>           
      <tr>
        <td colspan="3" nowrap="nowrap" id="fuente1">Observaciones</td>
        </tr>
      <tr>
        <td colspan="3" nowrap="nowrap" id="dato1"><textarea name="observacion5_egp" cols="75" rows="2" id="observacion5_egp"onkeyup="conMayusculas(this)"><?php echo $row_ver_egp['observacion5_egp']; ?></textarea></td>
        </tr>
      <tr id="tr1">
        <td colspan="2" id="fuente1">Ultima Modificaci&oacute;n : 
          <input name="responsable_modificacion" type="hidden" value="<?php echo $row_usuario['nombre_usuario']; ?>" />
          <?php echo $row_ver_egp['responsable_modificacion']; ?>
          <input name="fecha_modificacion" type="hidden" value="<?php echo date("Y-m-d"); ?>" />
          <?php echo $row_ver_egp['fecha_modificacion']; ?>
          <input name="hora_modificacion" type="hidden" value="<?php echo date("g:i a"); ?>" />
          <?php echo $row_ver_egp['hora_modificacion']; ?>
          <input name="tipo_usuario" type="hidden" id="tipo_usuario" value="<?php echo $row_usuario['tipo_usuario']; ?>" />
          <input name="codigo_usuario" type="hidden" id="codigo_usuario" value="<?php echo $row_usuario['codigo_usuario']; ?>" /></td>
        <td id="dato2"><input name="submit" type="submit" value="EDITAR REFERENCIA" /></td>
      </tr>
    </table>
        <input type="hidden" name="tipo_bolsa_ref" id="tipo_bolsa_ref" value="<?php echo $row_referencia_editar['tipo_bolsa_ref']; ?>" />
        <input type="hidden" name="vendedor" id="vendedor" value="<?php echo $row_ver_egp['vendedor']?>"/>
        <input type="hidden" name="MM_update" value="form1">
    <!--<input type="hidden" name="Str_nit" value="<?php //echo $row_ver_ref['Str_nit']; ?>">-->
    <input type="hidden" name="id_ref" value="<?php echo $row_referencia_editar['id_ref']; ?>">    
  </form>
  <?php echo $conexion->header('footer'); ?>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($ver_nref);

mysql_free_result($refcliente);

mysql_free_result($ref_verif);

mysql_free_result($ver_egp);

?>
