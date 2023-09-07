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

mysql_select_db($database_conexion1, $conexion1);
$query_n_cliente = "SELECT * FROM cliente ORDER BY id_c DESC";
$n_cliente = mysql_query($query_n_cliente, $conexion1) or die(mysql_error());
$row_n_cliente = mysql_fetch_assoc($n_cliente);
$totalRows_n_cliente = mysql_num_rows($n_cliente);

/*mysql_select_db($database_conexion1, $conexion1);
$query_vendedores = "SELECT * FROM vendedor ORDER BY nombre_vendedor ASC";
$vendedores = mysql_query($query_vendedores, $conexion1) or die(mysql_error());
$row_vendedores = mysql_fetch_assoc($vendedores);
$totalRows_vendedores = mysql_num_rows($vendedores);*/

mysql_select_db($database_conexion1, $conexion1);
$query_n_pais = "select * FROM Tbl_paises ORDER BY id_pais ASC";
$n_pais = mysql_query($query_n_pais, $conexion1) or die(mysql_error());
$row_n_pais = mysql_fetch_assoc($n_pais);
$totalRows_n_pais = mysql_num_rows($n_pais);
$row2 = mysql_fetch_array($n_pais);


/*$colname_egps = "-1";
if (isset($_GET['n_egp'])) {
  $colname_egps = (get_magic_quotes_gpc()) ? $_GET['n_egp'] : addslashes($_GET['n_egp']);
}*/
mysql_select_db($database_conexion1, $conexion1);
$query_n_ciudad = "select * FROM Tbl_ciudades_col ORDER BY nombre_ciudad ASC";
$n_ciudad = mysql_query($query_n_ciudad, $conexion1) or die(mysql_error());
$row_n_ciudad = mysql_fetch_assoc($n_ciudad);
$totalRows_n_ciudad = mysql_num_rows($n_ciudad);
$row2 = mysql_fetch_array($n_ciudad);
				  
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

/*$resulnit = mysql_query("SELECT * FROM cliente WHERE nit_c = '".$_POST['nit_c']."'");*///para cobntrolar el nit repetido
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")/*&&(mysql_num_rows($resulnit) < 1)*/) {
$tipo=$_POST['tipo_usuario'];
$id_usuario=$_POST['id_usuario'];
if($tipo == '10')
{
$id=$_POST['id_c'];
$sql3="UPDATE usuario SET codigo_usuario='$id' WHERE id_usuario='$id_usuario'";
$result3=mysql_query($sql3);
}
//ESTE CODIGO ES PARA DIVIDIR LA CADENA INDICATIVOS 	
$ind1=$_POST['indicativo1'];
$ind2=$_POST['indicativo2'];
$ind3=$_POST['indicativo3'];
$ind4=$_POST['indicativo4'];
$ind5=$_POST['indicativo5'];
$ind6=$_POST['indicativo6'];
$ind7=$_POST['indicativo7'];
$ind8=$_POST['indicativo8'];
$ind9=$_POST['indicativo9'];
$ind10=$_POST['indicativo10'];
$ind11=$_POST['indicativo11'];
$ind12=$_POST['indicativo12'];
$ind13=$_POST['indicativo13'];
function limpia_cad($ind1,$ind2,$ind3,$ind4,$ind5,$ind6,$ind7,$ind8,$ind9,$ind10,$ind11,$ind12,$ind13){
	//eliminamos los acentos
	$tofind = "ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ";
	$replac = "AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn";
	$cadena1 = strtr($teleconcatenado,$tofind,$replac);
 
	//eliminamos todo lo que no sean letras numeros o el punto de la extension
	//$cadena2 = ereg_replace("[^._A-Za-z0-9]", "", $cadena1);
 
	//substituimos espacios blancos por un guion
	$cadena3 = explode( '/',$cadena1);
	return($cadena3); 

}
//VARIABLES CONCATENADAS
$teleconcatenado=$ind1[0].'/'.$_POST['telefono_c'].'/'.$_POST['extension1'];
$telebodconca=$ind2[0].'/'.$_POST['telefono_bodega_c'].'/'.$_POST['extension2'];
$teleenvconca=$ind3[0].'/'.$_POST['telefono_envio_factura_c'].'/'.$_POST['extension3'];
$teledptconca=$ind4[0].'/'.$_POST['telefono_dpto_pagos_c'].'/'.$_POST['extension4'];
$tele1refconca=$ind5[0].'/'.$_POST['tel_1ref_comercial_c'].'/'.$_POST['extension5'];
$tele2refconca=$ind6[0].'/'.$_POST['tel_2ref_comercial_c'].'/'.$_POST['extension6'];
$tele3refconca=$ind7[0].'/'.$_POST['tel_3ref_comercial_c'].'/'.$_POST['extension7'];
$tele1bancconca=$ind8[0].'/'.$_POST['telefono_1ref_bancaria_c'].'/'.$_POST['extension8'];
$tele2bancconca=$ind9[0].'/'.$_POST['telefono_2ref_bancaria_c'].'/'.$_POST['extension9'];
$tele3bancconca=$ind10[0].'/'.$_POST['telefono_3ref_bancaria_c'].'/'.$_POST['extension10'];
$fax1=$ind11[0].'/'.$_POST['fax_c'].'/'.$_POST['extension11'];
$fax2=$ind12[0].'/'.$_POST['fax_envio_factura_c'].'/'.$_POST['extension12'];
$fax3=$ind13[0].'/'.$_POST['fax_dpto_pagos_c'].'/'.$_POST['extension13'];
//FINALIZA ESTE CODIGO ES PARA DIVIDIR LA CADENA INDICATIVOS
//VARIABLE PARA GUARDAR SI ES UNA CIUDAD EXTRANGERA
/*if(isset($_POST['ciudadexterno'])&&$_POST['ciudadexterno']!=''){$ciudad_c=$_POST['ciudad_c'];echo $ciudad_c;}else{$ciudad_c=$_POST['ciudad_c'];echo $ciudad_c;}*/

if(isset($_POST['ciudad_c'])&&$_POST['ciudad_c']!=''){
	$parte=explode(".","$_POST[ciudad_c]");
$ciudad_c=$parte[1].$_POST['ciudadexterno'];}
//GUARDAR DATOS VALIDADOS SIN PUNTOS NO COMAS ETC
$num2=trim($_POST['nit_c']);
$nit_c = ereg_replace("[^A-Za-z0-9-]", "", $num2);
//GUARDAR NOMBRE SIN ESPACIOS INICIO Y FIN
$nomb=trim($_POST['nombre_c']);
//PARA SUBIR ARCHIVOS PDF
if (isset($_FILES['camara_comercio_c']) && $_FILES['camara_comercio_c']['name'] != "") {
$directorio = "archivosc/";
$nombre1 = str_replace(' ', '',  $_FILES['camara_comercio_c']['name']);
$archivo_temporal = $_FILES['camara_comercio_c']['tmp_name'];
if (!copy($archivo_temporal,$directorio.$nombre1)) {
$error = "Error al enviar el Archivo";
} else { $imagen = "archivosc/".$nombre1; }
}
if (isset($_FILES['balance_general_c']) && $_FILES['balance_general_c']['name'] != "") {
$directorio = "archivosc/";
$nombre2 = str_replace(' ', '',  $_FILES['balance_general_c']['name']);
$archivo_temporal = $_FILES['balance_general_c']['tmp_name'];
if (!copy($archivo_temporal,$directorio.$nombre2)) {
$error = "Error al enviar el Archivo";
} else { $imagen2 = "archivosc/".$nombre2; }
}
if (isset($_FILES['estado_pyg_c']) && $_FILES['estado_pyg_c']['name'] != "") {
$directorio = "archivosc/";
$nombre3 = str_replace(' ', '',  $_FILES['estado_pyg_c']['name']);
$archivo_temporal = $_FILES['estado_pyg_c']['tmp_name'];
if (!copy($archivo_temporal,$directorio.$nombre3)) {
$error = "Error al enviar el Archivo";
} else { $imagen3 = "archivosc/".$nombre3; }
}
if (isset($_FILES['fotocopia_declar_iva_c']) && $_FILES['fotocopia_declar_iva_c']['name'] != "") {
$directorio = "archivosc/";
$nombre4 = str_replace(' ', '',  $_FILES['fotocopia_declar_iva_c']['name']);
$archivo_temporal = $_FILES['fotocopia_declar_iva_c']['tmp_name'];
if (!copy($archivo_temporal,$directorio.$nombre4)) {
$error = "Error al enviar el Archivo";
} else { $imagen4 = "archivosc/".$nombre4; }
}
if (isset($_FILES['referencias_bancarias_c']) && $_FILES['referencias_bancarias_c']['name'] != "") {
$directorio = "archivosc/";
$nombre5 = str_replace(' ', '',  $_FILES['referencias_bancarias_c']['name']);
$archivo_temporal = $_FILES['referencias_bancarias_c']['tmp_name'];
if (!copy($archivo_temporal,$directorio.$nombre5)) {
$error = "Error al enviar el Archivo";
} else { $imagen5 = "archivosc/".$nombre5; }
}
if (isset($_FILES['referencias_comerciales_c']) && $_FILES['referencias_comerciales_c']['name'] != "") {
$directorio = "archivosc/";
$nombre6 = str_replace(' ', '',  $_FILES['referencias_comerciales_c']['name']);
$archivo_temporal = $_FILES['referencias_comerciales_c']['tmp_name'];
if (!copy($archivo_temporal,$directorio.$nombre6)) {
$error = "Error al enviar el Archivo";
} else { $imagen6 = "archivosc/".$nombre6; }
}
if (isset($_FILES['flujo_caja_proy_c']) && $_FILES['flujo_caja_proy_c']['name'] != "") {
$directorio = "archivosc/";
$nombre7 = str_replace(' ', '',  $_FILES['flujo_caja_proy_c']['name']);
$archivo_temporal = $_FILES['flujo_caja_proy_c']['tmp_name'];
if (!copy($archivo_temporal,$directorio.$nombre7)) {
$error = "Error al enviar el Archivo";
} else { $imagen7 = "archivosc/".$nombre7; }
}
if (isset($_FILES['otros_doc_c']) && $_FILES['otros_doc_c']['name'] != "") {
$directorio = "archivosc/";
$nombre8 = str_replace(' ', '',  $_FILES['otros_doc_c']['name']);
$archivo_temporal = $_FILES['otros_doc_c']['tmp_name'];
if (!copy($archivo_temporal,$directorio.$nombre8)) {
$error = "Error al enviar el Archivo";
} else { $imagen8 = "archivosc/".$nombre8; }
}
//FIN DE LA VALIDACION Y SUBIDA DE ARCHIVOS PDF
$insertSQL = sprintf("INSERT INTO cliente (id_c, nit_c, nombre_c, tipo_c, fecha_ingreso_c, fecha_solicitud_c,rep_legal_c, telefono_c, direccion_c, fax_c, contacto_c, cargo_contacto_c, telefono_contacto_c, celular_contacto_c, pais_c, provincia_c, ciudad_c, email_comercial_c, contacto_bodega_c, cargo_contacto_bodega_c, direccion_entrega_c, email_contacto_bodega_c, pais_bodega_c, provincia_bodega_c, ciudad_bodega_c, telefono_bodega_c, fax_bodega_c, direccion_envio_factura_c, telefono_envio_factura_c, fax_envio_factura_c, observ_inf_c, contacto_dpto_pagos_c, telefono_dpto_pagos_c, fax_dpto_pagos_c, direccion_dpto_pagos_c, email_dpto_pagos_c, cupo_solicitado_c, forma_pago_c, otro_pago_c, `1ref_comercial_c`, tel_1ref_comercial_c, nombre_1ref_comercial_c, cupo_1ref_comercial_c, plazo_1ref_comercial_c, `2ref_comercial_c`, tel_2ref_comercial_c, nombre_2ref_comercial_c, cupo_2ref_comercial_c, plazo_2ref_comercial_c, `3ref_comercial_c`, tel_3ref_comercial_c, nombre_3ref_comercial_c, cupo_3ref_comercial_c, plazo_3ref_comercial_c, `1ref_bancaria_c`, telefono_1ref_bancaria_c, nombre_1ref_bancaria_c, `2ref_bancaria_c`, telefono_2ref_bancaria_c, nombre_2ref_bancaria_c, `3ref_bancaria_c`, telefono_3ref_bancaria_c, nombre_3ref_bancaria_c, observ_inf_finan_c, cupo_aprobado_c, plazo_aprobado_c, observ_aprob_finan_c, estado_comercial_c, observ_asesor_com_c, camara_comercio_c, referencias_bancarias_c, referencias_comerciales_c, estado_pyg_c, balance_general_c, flujo_caja_proy_c,  fotocopia_declar_iva_c,  otros_doc_c, observ_doc_c, estado_c, registrado_c) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['id_c'], "int"),
                       GetSQLValueString($nit_c, "text"),
                       GetSQLValueString($nomb, "text"),
                       GetSQLValueString($_POST['tipo_c'], "text"),
                       GetSQLValueString($_POST['fecha_ingreso_c'], "date"),
                       GetSQLValueString($_POST['fecha_solicitud_c'], "date"),
/*					   GetSQLValueString(isset($_POST['bolsa_plastica_c']) ? "true" : "", "defined","1","0"),
					   GetSQLValueString(isset($_POST['lamina_c']) ? "true" : "", "defined","1","0"),
					   GetSQLValueString(isset($_POST['cinta_c']) ? "true" : "", "defined","1","0"),
					   GetSQLValueString(isset($_POST['packing_list_c']) ? "true" : "", "defined","1","0"), */  					   
                       GetSQLValueString($_POST['rep_legal_c'], "text"),
                       GetSQLValueString($teleconcatenado, "text"),
                       GetSQLValueString($_POST['direccion_c'], "text"),
                       GetSQLValueString($fax1, "text"),
                       GetSQLValueString($_POST['contacto_c'], "text"),
                       GetSQLValueString($_POST['cargo_contacto_c'], "text"),
                       GetSQLValueString($_POST['telefono_contacto_c'], "text"),
                       GetSQLValueString($_POST['celular_contacto_c'], "text"),
                       GetSQLValueString($_POST['pais_c'], "text"),
                       GetSQLValueString($_POST['provincia_c'], "text"),
                       GetSQLValueString($ciudad_c, "text"),
                       GetSQLValueString($_POST['email_comercial_c'], "text"),
                       GetSQLValueString($_POST['contacto_bodega_c'], "text"),
                       GetSQLValueString($_POST['cargo_contacto_bodega_c'], "text"),
                       GetSQLValueString($_POST['direccion_entrega_c'], "text"),
                       GetSQLValueString($_POST['email_contacto_bodega_c'], "text"),
                       GetSQLValueString($_POST['pais_bodega_c'], "text"),
                       GetSQLValueString($_POST['provincia_bodega_c'], "text"),
                       GetSQLValueString($_POST['ciudad_bodega_c'], "text"),
                       GetSQLValueString($telebodconca, "text"),
                       GetSQLValueString($_POST['fax_bodega_c'], "text"),
                       GetSQLValueString($_POST['direccion_envio_factura_c'], "text"),
                       GetSQLValueString($teleenvconca, "text"),
                       GetSQLValueString($fax2, "text"),
                       GetSQLValueString($_POST['observ_inf_c'], "text"),
                       GetSQLValueString($_POST['contacto_dpto_pagos_c'], "text"),
                       GetSQLValueString($teledptconca, "text"),
                       GetSQLValueString($fax3, "text"),
                       GetSQLValueString($_POST['direccion_dpto_pagos_c'], "text"),
                       GetSQLValueString($_POST['email_dpto_pagos_c'], "text"),
                       GetSQLValueString($_POST['cupo_solicitado_c'], "double"),
                       GetSQLValueString($_POST['forma_pago_c'], "text"),
					   GetSQLValueString($_POST['otro_pago_c'], "text"),
                       GetSQLValueString($_POST['ref_comercial_c'], "text"),
                       GetSQLValueString($tele1refconca, "text"),
                       GetSQLValueString($_POST['nombre_1ref_comercial_c'], "text"),
                       GetSQLValueString($_POST['cupo_1ref_comercial_c'], "text"),
                       GetSQLValueString($_POST['plazo_1ref_comercial_c'], "text"),
                       GetSQLValueString($_POST['ref_comercial_c2'], "text"),
                       GetSQLValueString($tele2refconca, "text"),
                       GetSQLValueString($_POST['nombre_2ref_comercial_c'], "text"),
                       GetSQLValueString($_POST['cupo_2ref_comercial_c'], "text"),
                       GetSQLValueString($_POST['plazo_2ref_comercial_c'], "text"),
                       GetSQLValueString($_POST['ref_comercial_c3'], "text"),
                       GetSQLValueString($tele3refconca, "text"),
                       GetSQLValueString($_POST['nombre_3ref_comercial_c'], "text"),
                       GetSQLValueString($_POST['cupo_3ref_comercial_c'], "text"),
                       GetSQLValueString($_POST['plazo_3ref_comercial_c'], "text"),
                       GetSQLValueString($_POST['ref_bancaria_c'], "text"),
                       GetSQLValueString($tele1bancconca, "text"),
                       GetSQLValueString($_POST['nombre_1ref_bancaria_c'], "text"),
                       GetSQLValueString($_POST['ref_bancaria_c2'], "text"),
                       GetSQLValueString($tele2bancconca, "text"),
                       GetSQLValueString($_POST['nombre_2ref_bancaria_c'], "text"),
                       GetSQLValueString($_POST['ref_bancaria_c3'], "text"),
                       GetSQLValueString($tele3bancconca, "text"),
                       GetSQLValueString($_POST['nombre_3ref_bancaria_c'], "text"),
                       GetSQLValueString($_POST['observ_inf_finan_c'], "text"),
                       GetSQLValueString($_POST['cupo_aprobado_c'], "double"),
                       GetSQLValueString($_POST['plazo_aprobado_c'], "text"),
                       GetSQLValueString($_POST['observ_aprob_finan_c'], "text"),
                       GetSQLValueString($_POST['estado_comercial_c'], "text"),
                       //GetSQLValueString($_POST['asesor_comercial_c'], "text"),                      
                       GetSQLValueString($_POST['observ_asesor_com_c'], "text"),
                       GetSQLValueString($nombre1, "text"),
					   GetSQLValueString($nombre5, "text"),
					   GetSQLValueString($nombre6, "text"),
					   GetSQLValueString($nombre3, "text"),	
                       GetSQLValueString($nombre2, "text"),
                       GetSQLValueString($nombre7, "text"),			   
                       GetSQLValueString($nombre4, "text"),
                       GetSQLValueString($nombre8, "text"),
                       GetSQLValueString($_POST['observ_doc_c'], "text"),
                       GetSQLValueString($_POST['estado_c'], "text"),
                       GetSQLValueString($_POST['registrado_c'], "text"));
					   
  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

//INSERT PARA LA TABLA TBL_DESTINATARIOS
//CODIGO PARA CAPTURAR REGISTROS DEL ARRAY DE LOS CAMPOS DINAMICOS
$id_d=$_POST['id_c'];
$pnt=$_POST['nit_c'];
$nombre=$_POST['responsable_dest'];
$dir=$_POST['direccion_dest'];
$ind=$_POST['indicativo_dest'];
$tel=$_POST['telefono_dest'];
$ext=$_POST['extension_dest'];
$ciu=$_POST['ciudad_dest'];
/*$mascotas=array("a"=>"Gato","b"=>"Perro","c"=>"Pajaro");
print_r(array_change_key_case($mascotas,CASE_UPPER));*/
for($n=0,$d=0,$i=0,$t=0,$e=0,$c=0;$n<count($nombre);$n++,$d++,$i++,$t++,$e++,$c++){
	$dir_des=strtoupper($dir[$d]);//pasa a mayusculas
	
  $insertSQL2 = sprintf("INSERT INTO Tbl_Destinatarios (id_d, nit,nombre_responsable,direccion,indicativo,telefono,extension,ciudad) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($id_d, "int"),
                       GetSQLValueString($pnt, "text"),
                       GetSQLValueString($nombre[$n], "text"),
                       GetSQLValueString($dir_des, "text"),
					   GetSQLValueString($ind[$i], "text"),
                       GetSQLValueString($tel[$t], "text"),
					   GetSQLValueString($ext[$e], "text"),
                       GetSQLValueString($ciu[$c], "text"));			
  mysql_select_db($database_conexion1, $conexion1);
  $Result2 = mysql_query($insertSQL2, $conexion1) or die(mysql_error());					   
//INSERT PARA LA TABLA TBL_DESTINATARIOS
}

  $insertGoTo = "perfil_cliente_vista.php?id_c=" . $_POST["id_c"] . "&tipo_usuario=" . $_POST["tipo_usuario"] ."&nit_c=" . $_POST["nit_c"]."&ciudad=" . $_POST["ciudad_c"];
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7"/>
<title>SISADGE AC &amp; CIA</title>
<link href="css/general.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
<script type="text/javascript" src="js/consulta_ciudad.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
<script type="text/javascript" src="js/agregueCampos.js"></script>
<script type="text/javascript">
function validacion_nit_nombre_Existe() {

			//valida codigo empleado vacio	
			indice = document.getElementById("nit_c").selectedIndex;
			indice2 = document.getElementById("nombre_c").selectedIndex;
			if( indice != null || indice != '' || indice2 != null || indice2 != '' ) {
				  alert('[ERROR] El Nit ya existe, o hay un cliente con el mismo nombre!');
				  //form1.nit_c.focus(); 
			return false;
			}
  
  // Si el script ha llegado a este punto, todas las condiciones
  // se han cumplido, por lo que se devuelve el valor true
  return true;
}
function nit() { 
/*Por ejemplo, en el siguiente código se comprueba si el usuario ha escrito correctamente 
una matrícula de automóvil que debe seguir el patrón código del país (1 o 2 letras), un 
espacio en blanco, numeración (4 dígitos), un espacio en blanco y letras (3 letras, empezando 
en BBB y acabando en ZZZ, sin las vocales): 
var expreg = /^[A-Z]{1,2}\s\d{4}\s([B-D]|[F-H]|[J-N]|[P-T]|[V-Z]){3}$/;*/ 
  var m = document.getElementById("nit_c").value;
  var expreg = new RegExp("^[0-9a-zA-z]{6,9}\[-]{1}\[0-9]{1}$");
  if(expreg.test(m)){
    alert("Es correcto continue!");
	return true;
  }else{
    alert("El Nit NO es correcto");
 return false;
  }
}
/*function nombre() { 
  var mm = document.getElementById("nombre_c").value;
  var expreg = new RegExp("^[0-9a-zA-z \.-]{3,7}$");
  if(expreg.test(mm)){
    alert("Es correcto continue!");
	return true;
  }else{
    alert("El Nombre NO es correcto");
 return false;
  } 
}*/
</script>
<!--CODIGO PARA LOS CAMPOS AGREGADOS DE BODEGAS-->
</head>
<body oncontextmenu="return false">
  <table id="tabla_formato">
    <tr><td>
    <div id="cabecera_formato"><div class="menu_formato"><ul>  
      <li><?php echo $row_usuario['nombre_usuario']; ?></li>       
       <li><a href="menu.php">COMERCIAL</a></li>
       <li><a href="menu.php">MENU</a></li>
       <li><a href="<?php echo $logoutAction ?>">SALIR</a></li>  
      </ul></div></div>
</td></tr></table>
     <div align="center">        
     <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" onSubmit="return nit()">
       <table id="tabla_formato2" >
         <tr>
           <td width="31%" id="codigo_formato_2">CODIGO: R1-F07<input name="chequeo" type="hidden" size="1" maxlength="1"></td>
           <td colspan="1" id="titulo_formato_2">PERFIL DE CLIENTES</td>
           <td colspan="2" id="codigo_formato_2">VERSION: 1</td>
         </tr>
         <tr>
           <td rowspan="6" id="logo_2"><img src="images/logoacyc.jpg"></td>
           <td id="dato_1">Fecha de Ingreso</td>
           <td id="dato_1"><a href="listado_clientes.php"><img src="images/cat.gif" alt="LISTADO CLIENTES"
title="LISTADO CLIENTES" border="0" style="cursor:hand;"></a><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
           <td id="dato_1">Fecha de Solicitud.</td>
         </tr>
         <tr>
           <td id="dato_1"><input name="fecha_ingreso_c" type="date" value="<?php echo date("Y-m-d"); ?>" size="10" ></td>
           <td id="dato_1">&nbsp;</td>
           <td id="dato_1"><input type="date" name="fecha_solicitud_c" value="<?php echo date("Y-m-d"); ?>" size="10"></td>
           
         </tr>
         <tr>
           <td id="dato_1">NIT  000000000-0</td>
           <td id="dato_1">Cliente N&deg;
             <input name="id_c" type="hidden" value="<?php $num=$row_n_cliente['id_c']+1;  echo $num; ?>">
           <?php $num=$row_n_cliente['id_c']+1; echo $num; ?></td>
           <td id="dato_1">&nbsp;</td>
           
         </tr>
         <tr>
           <td id="dato_1">
<input type="text" name="nit_c" id="nit_c" value="" size="30" onChange="if (form1.nit_c.value) { DatosGestiones('1','nit_c',form1.nit_c.value); } else { alert('Debe digitar el NIT para validar su existencia en la BD'); };desapareceClick(this);" onBlur="return nit()">
</td>
           <td colspan="3" id="dato1"><div id="resultado"></div></td>
           
         </tr>
         <tr>
           <td id="dato_1"> Raz&oacute;n Social</td>
           <td id="dato_1">Tipo de Cliente </td>
           <td id="dato_1">&nbsp;</td>
           
         </tr>
         <tr>
           <td id="dato_1"><input name="nombre_c" id="nombre_c" type="text" onBlur="conMayusculas(this),desapareceClick(this)" onChange="if (form1.nombre_c.value) { DatosGestiones('17','nombre_c',form1.nombre_c.value); } else { alert('Debe digitar el Nombre para validar su existencia en la BD'); };desapareceClick(this);" value="" size="30" maxlength="100">		   </td>
           <td id="dato_1"><select name="tipo_c" onChange="ocultarCampo(this)">
             <option value="">*</option>
             <option value="NACIONAL">Nacional
             <option value="EXTRANJERO">Extranjero
           </select></td>
           <td id="dato_1">&nbsp;</td>
           
         <tr>
           <td><input name="bolsa_plastica_c" type="hidden" id="bolsa_plastica_c" value="0">
               <input name="lamina_c" type="hidden" id="lamina_c" value="0"></td>
           <td colspan="3"><input name="cinta_c" type="hidden" id="cinta_c" value="0">
           <input name="packing_list_c" type="hidden" id="packing_list_c" value="0"></td>
         </tr>
         <tr>
           <td colspan="4" id="subtitulo2">INFORMACION GENERAL DEL CLIENTE</td>
         </tr>
         <tr>
           <td  id="dato_1">Representante Legal </td>
           <td colspan="1" id="dato_1">Indicat. Telefono(s). Extension</td>
           <td colspan="2" id="dato_1">Pais</td>
         </tr>
         <tr>
           <td colspan="1" id="dato_1"><input name="rep_legal_c" type="text" onBlur="conMayusculas(this)" value="" size="50" maxlength="100"></td>
           <td colspan="1" id="detalle_1"><input type="text" name="indicativo1"value= ""id="indicativo1" size="1"onKeyUp="return ValNumero(this)"onClick="this.value = ''">
             <input type="text" name="telefono_c" value="" size="10"onKeyUp="return ValNumero(this)">
           <input type="text" name="extension1" id="extension1" size="1"onKeyUp="return ValNumero(this)" value=""></td>
           <td colspan="2" ><select  class='Estilo7'name="pais_c" id="id_pais" style="width:250px">
             <?php
				do {  
				?>
			 <option value="<?php echo $row_n_pais['nombre_pais']?>"><?php echo $row_n_pais['nombre_pais']?></option>
								 <?php
				} while ($row_n_pais = mysql_fetch_assoc($n_pais));
				  $rows = mysql_num_rows($n_pais);
				  if($rows > 0) {
					  mysql_data_seek($n_pais, 0);
					  $row_n_pais = mysql_fetch_assoc($n_pais);
				  }
				?>
               </select>
             <?php
/*	 //CONSULTA PAIS      	
     // $query_n_pais="select * from paises ";
     if(!$result2=mysql_query($query_n_pais)) error($query_n_pais);
     //if(mysql_num_rows($result2 > 0)) {
     $row2 = mysql_fetch_array($result2);
     $apuntador2=$row2['id_pais'];	 
     //}
     echo "<select  class='Estilo7'name='pais_c' id='id_pais'>";
	  if ($row2[0]==$row2[1]){ 
     echo "<option selected value='$row2[nombre_pais]'>$row2[1]"; 
	 }
	  else{ 
       echo "<option  class='Estilo7'value='$row2[nombre_pais]'>$row2[1]"; 
     }
     while ($row2=mysql_fetch_array($result2)) {
     echo "<option  class='Estilo7'value=$row2[nombre_pais]"; //id_pais
     echo ' >';
     echo $row2["nombre_pais"];      
     }
      echo '</select>';*/
    ?>
           </td>
         </tr>
         <tr>
           <td id="dato_1"> Direcci&oacute;n Comercial</td>
           <td id="dato_1">&nbsp;</td>
           <td id="dato_1">Ciudad</td>
           <td id="dato_5">&nbsp;</td>
           
         </tr>
         <tr>
           <td id="dato_1"><input name="direccion_c" type="text"onBlur="conMayusculas(this)" value="" size="50" maxlength="150"></td>
           <td id="dato_4"><?php //prueba para indicativo ciudad
/*		   mysql_select_db($database_conexion1, $conexion1);		  
		   $consulta= mysql_query("SELECT*FROM ciudades_col WHERE nombre_ciudad='$_GET[ciudad_c]'");
		   $total_row_consulta = mysql_num_rows($consulta);
           $row = mysql_fetch_array($consulta);
           while ($row = mysql_fetch_array($consulta))
			{ 
			echo "El Nombre es: <b>".$row['ind_ciudad']."</b><br>n"; 
			 
		   $campo= stripslashes($campos['ind_ciudad']);
		   echo"la variable es: ";
		   echo $row_consulta;
			}*/
		   ?></td>
           <td colspan="2" id="dato_4"><select class='Estilo7' style="width:250px" name="ciudad_c" id='miCampoDeTexto'onchange='Javascript:document.form1.indicativo1.value=this.value;
	 document.form1.indicativo11.value=this.value;
	 document.form1.indicativo3.value=this.value;
	 document.form1.indicativo12.value=this.value;'>
             <?php
	 do {  
	 ?>
             <option value="<?php echo $row_n_ciudad['ind_ciudad']?>______.<?php echo $row_n_ciudad['nombre_ciudad']?>"><?php echo $row_n_ciudad['nombre_ciudad']?></option>
     <?php
	  } while ($row_n_ciudad = mysql_fetch_assoc($n_ciudad));
	  $rows = mysql_num_rows($n_ciudad);
	  if($rows > 0) {
      mysql_data_seek($n_ciudad, 0);
	  $row_n_ciudad = mysql_fetch_assoc($n_ciudad);
      }
      ?>
        </select>
             <?php
/*	 //CONSULTA CIUDADES     	
     // $query_n_ciudad="select * from ciudades ";
     if(!$result3=mysql_query($query_n_ciudad)) error($query_n_ciudad);
     //if(mysql_num_rows($result3 > 0)) {
     $row3 = mysql_fetch_array($result3);
     $apuntador3=$row3['id_ciudad'];	 
     //}
	 //IMPRESION DEL INDICATIVO EN LOS CAMPOS CORRESPONDIENTES APARTIR DEL OnChange
     echo "<select class='Estilo7' name='ciudad_c'   id='miCampoDeTexto'onchange='Javascript:document.form1.indicativo1.value=this.value;
	 document.form1.indicativo11.value=this.value;
	 document.form1.indicativo3.value=this.value;
	 document.form1.indicativo12.value=this.value;
	 document.form1.indicativo4.value=this.value;
	 document.form1.indicativo13.value=this.value;
	 document.form1.indicativo5.value=this.value;
	 document.form1.indicativo6.value=this.value;
	 document.form1.indicativo7.value=this.value;
	 document.form1.indicativo8.value=this.value;
	 document.form1.indicativo9.value=this.value;
	 document.form1.indicativo10.value=this.value;
	 '>";//FIN IMPRESION DEL INDICATIVO EN LOS CAMPOS CORRESPONDIENTES
	  if ($row3[0]==$row3[1]){
     echo "<option selected value=$row3[nombre_ciudad]>$row3[1]"; 
	 }
	  else{ 
       echo "<option value='$row3[nombre_ciudad]'>$row3[1]"; 
     }
     while ($row3=mysql_fetch_array($result3)) {
     echo "<option  class='Estilo7'value=$row3[ind_ciudad]______.$row3[id_ciudad]";  
     echo ' />'.$row3['nombre_ciudad']; 	     
     }
     echo '</select>';	*/
    ?>
           </td>           
         </tr>
         <tr>
           <td id="dato_1">Email Comercial. </td>
           <td width="31%" id="dato_1">Indicat. Fax inf. general. Extension</td>
           <td width="13%"id="dato_1">Ciudad Extranjera</td>
           <td width="25%"id="dato_1">&nbsp;</td>
           
         </tr>
         <tr>
           <td id="dato_1"><input id="email"type="text" name="email_comercial_c" value="" size="50"onBlur="MM_validateForm('email_comercial_c','','NisEmail');return document.MM_returnValue;conMayusculas(this)"></td>
           <td id="detalle_1"><input name="indicativo11" type="text"id="indicativo11"onClick="this.value = ''"onKeyUp="return ValNumero(this)"value= "" size="1">
           <input type="text" name="fax_c" value="" size="10"onKeyUp="return ValNumero(this)">
           <input type="text" name="extension11" id="extension11" size="1"onKeyUp="return ValNumero(this)" value=""></td>
           <td colspan="2" id="dato_1"><input name="ciudadexterno"type="text"size="48"value="" id="ciudadexterno" onBlur="conMayusculas(this)"/></td>
         </tr>
         <tr>
           <td id="dato_1">&nbsp;</td>
           <td colspan="3" id="dato_6"><input type="hidden" name="provincia_c" value="" size="20"></td>
         </tr>
           <tr>
           <td colspan="4" id="subtitulo2">INFORMACION DEL CONTACTO GENERAL</td>
         </tr>
         <tr>
           <td colspan="1" id="dato_1">Nombre del Contacto  Comercial </td>
           <td id="dato_1">Cargo  Cont. Com.</td>
           <td id="dato_1">Email Contact.Comercial.</td>
           <td id="dato_1">&nbsp;</td>
           
         </tr>
         <tr>
           <td colspan="1" id="dato_1"><input name="contacto_c" type="text"onBlur="conMayusculas(this)" value="" size="50" maxlength="100"></td>
           <td id="dato_1"><input type="text" name="cargo_contacto_c" value="" size="30"onBlur="conMayusculas(this)"></td>
           <td colspan="2" id="dato_1"><input type="text" name="email_contacto_bodega_c" value="" size="48"onBlur="MM_validateForm('email_contacto_bodega_c','','NisEmail');return document.MM_returnValue;conMayusculas(this)"></td>
         </tr>
         <tr>
           <td id="dato_1">Direcci&oacute;n Envio de Factura </td>
           <td width="31%"id="dato_1">Indic.  Telefono En. Factura Extension</td>
           <td width="13%"id="dato_1">Indicat. Fax Envio de Factura. Extension</td>
           <td width="25%"id="dato_1">&nbsp;</td>
           
         </tr>
         <tr>
           <td id="dato_1"><input name="direccion_envio_factura_c" type="text"onBlur="conMayusculas(this)" value="" size="50" maxlength="100"></td>
           <td id="detalle_1"><input type="text" name="indicativo3"value= ""id="indicativo3" size="1"onKeyUp="return ValNumero(this)"onClick="this.value = ''">
             <input type="text" name="telefono_envio_factura_c" value="" size="10"onKeyUp="return ValNumero(this)">
           <input type="text" name="extension3" id="extension3" size="1"onKeyUp="return ValNumero(this)" value="" ></td>
           <td colspan="2" id="dato_1"><input type="text" name="indicativo12"value= ""id="indicativo12" size="1"onKeyUp="return ValNumero(this)"onClick="this.value = ''">
             <input type="text" name="fax_envio_factura_c" value="" size="20"onKeyUp="return ValNumero(this)">
           <input type="text" name="extension12" id="extension12" size="1"onKeyUp="return ValNumero(this)" value=""></td>
         </tr>
         <tr>
           <td id="dato_1">Celular Contacto Comercial</td>
           <td id="detalle_3">&nbsp;</td>
           <td id="detalle_3">&nbsp;</td>
           <td id="detalle_3">&nbsp;</td>
         </tr>
         <tr>
           <td id="dato_1"><input type="text" name="celular_contacto_c" value="" size="50"onKeyUp="return ValNumero(this)"></td>
           <td id="dato_1">&nbsp;</td>
           <td id="dato_1"><input type="hidden" name="fax_bodega_c" value="" size="30"onKeyUp="return ValNumero(this)">
             <input type="hidden" name="provincia_bodega_c" value="" size="20">
             <input type="hidden" name="telefono_contacto_c" value="" size="20">
             <input type="hidden" name="contacto_bodega_c" value="" size="50">
             <input type="hidden" name="cargo_contacto_bodega_c" value="" size="20"></td>
           <td id="dato_1">&nbsp;</td>        
         </tr>
         <tr>
           <td colspan="4" id="dato_1">Observaciones de Informaci&oacute;n General del Cliente </td>
         </tr>
         <tr>
           <td colspan="4" id="detalle_1">&nbsp;</td>
         </tr>
			
         <tr>
         <!--aqui empieza la opcion multiple de agregar-->
           <td height="74" colspan="4" id="detalle_1">          
  <table width="100%">
<tr>
 <td colspan="7" id="subtitulo2">INFORMACION DE DESPACHO BODEGAS</td>
  <tr>
    <td colspan="2" id="dato_1">&nbsp;</td>
    <td width="146" id="dato_1">&nbsp;</td>
    <td width="109" id="dato_1">&nbsp;</td>
    <td width="103" id="dato_1">&nbsp;</td>
    <td width="123" id="dato_1">&nbsp;</td>
    <td width="244"><input type="button" onClick="crear(this)"value="Agregar Bodegas" ></td>
  </tr>
  <tr>
 
<!--<td width="175" id="subtitulo3">NIT</td>-->
<td width="28" id="dato_1">&nbsp;</td>
<td width="241" id="dato_1">NOMBRE</td>
<td  width="146"id="dato_1">DIRECCION</td>
<td  width="109"id="dato_1">INDICATIVO</td>
<td  width="103"id="dato_1">TELEFONO</td>
<td width="123"id="dato_1">EXTENSION</td>
<td width="244"id="dato_1">CIUDAD</td>
  </tr>
  <tr>
    <td  colspan="7" id="dato_1"><fieldset id="field"></fieldset> </td>
    </tr>     
</table>
  <textarea name="observ_inf_c" cols="100" rows="2"onBlur="conMayusculas(this)"></textarea></td> 
           <!--aqui termina la opcion multiple -->                   
         </tr>             
         <tr>
           <td colspan="4">&nbsp;</td>
         </tr>
         <tr>
           <td colspan="4" id="subtitulo2">INFORMACION FINANCIERA </td>
         </tr>
         <tr>
           <td id="dato_1">Contacto Dpto Pagos</td>
           <td width="31%"id="dato_1">Indicativo. Telefono. Extension</td>
           <td width="13%"id="dato_1">E-mail</td>
           <td width="25%"id="dato_1">&nbsp;</td>
         </tr>
         <tr>
           <td id="dato_1"><input type="text" name="contacto_dpto_pagos_c" value="" size="50"onBlur="conMayusculas(this)"></td>
           <td id="detalle_1"><input type="text" name="indicativo4"value= ""id="indicativo4" size="1"onKeyUp="return ValNumero(this)"onclick="this.value = ''">
           <input type="text" name="telefono_dpto_pagos_c" value="" size="10"onKeyUp="return ValNumero(this)">
           <input type="text" name="extension4" id="extension4" size="1"onKeyUp="return ValNumero(this)" value=""></td>
           <td colspan="2" id="dato_1"><input type="text" name="email_dpto_pagos_c" value="" size="48"onBlur="conMayusculas(this)" onChange="MM_validateForm('email_dpto_pagos_c','','NisEmail');return document.MM_returnValue"></td>
         </tr>
         <tr>
           <td id="dato_1">Direcci&oacute;n</td>
           <td id="dato_1"> Indicat. Fax financiera. Exension</td>
           <td id="dato_1">Cupo Solicitado ($)</td>
           <td id="dato_1">&nbsp;</td>          
         </tr>
         <tr>
           <td id="dato_1"><input type="text" name="direccion_dpto_pagos_c" value="" size="50"onBlur="conMayusculas(this)"></td>
           <td id="detalle_1"><input type="text" name="indicativo13"value= ""id="indicativo13" size="1"onKeyUp="return ValNumero(this)"onClick="this.value = ''">
             <input type="text" name="fax_dpto_pagos_c" value="" size="10"onKeyUp="return ValNumero(this)">
           <input type="text" name="extension13" id="extension13" size="1"onKeyUp="return ValNumero(this)" value=""></td>
           <td colspan="2" id="dato_1"><input type="text" name="cupo_solicitado_c" value="" size="48"onKeyUp="return ValNumero(this)"></td>
         </tr>
         <tr>
           <td id="dato_1"><select name="forma_pago_c">
           <option value=""> </option>
             <option value="CHEQUE">Cheque</option>
             <option value="CONSIGNACION">Consignacion</option>
             <option value="TRANSFERENCIA">Transferencia</option>
             <option value="OTRA">Otra</option>
           </select>
Forma de Pago </td>
           <td id="dato_1">Otra Forma de Pago
           <input type="text" name="otro_pago_c" value="" size="30"onBlur="conMayusculas(this)"></td>
           <td id="dato_1">&nbsp;</td>
           <td id="dato_1">&nbsp;</td>          
         </tr>
         <tr>
           <td colspan="4"><table width="100%" id="tabla_formato">
               <tr>
                 <td colspan="7" id="subtitulo2">REFERENCIAS COMERCIALES</td>
               </tr>
               <tr>
                 <td width="19%" id="dato_2">REFERENCIAS COMERCIALES</td>
                 <td width="7%"id="dato_2">INDICAT. </td>
                 <td width="11%"id="dato_2">TELEFONOS</td>
                 <td width="8%"id="dato_2">Extensiones</td>
                 <td width="18%"id="dato_1">CUPOS</td>
                 <td width="37%"id="dato_1">PLAZOS</td>
               </tr>
               <tr>
                 <td id="detalle_1"><input type="text" name="ref_comercial_c" value="" size="30"onBlur="conMayusculas(this)"></td>
                 <td id="detalle_1"><input type="text" name="indicativo5"value= ""id="indicativo5" size="3"onKeyUp="return ValNumero(this)"onclick="this.value = ''"></td>
                 <td id="detalle_1"><input type="text" name="tel_1ref_comercial_c" value="" size="15"onKeyUp="return ValNumero(this)"></td>
                 <td id="detalle_1"><input type="text" name="extension5" id="extension5" size="5"onKeyUp="return ValNumero(this)" value="" ></td>
                 <td id="detalle_1"><input type="text" name="cupo_1ref_comercial_c" value="" size="20"></td>
                 <td id="detalle_1"><select name="plazo_1ref_comercial_c">
                     <option value=""></option>
                     <option value="PAGO DE CONTADO">Pago de Contado</option>
                     <option value="PAGO A 15 DIAS">Pago a 15 Dias</option>
                     <option value="PAGO A 30 DIAS">Pago a 30 Dias</option>
                     <option value="PAGO A 45 DIAS">Pago a 45 Dias</option>
                     <option value="PAGO A 60 DIAS">Pago a 60 Dias</option>
                     <option value="PAGO A 90 DIAS">Pago a 90 Dias</option>
                     <option value="PAGO A 999 DIAS">Pago a 999 Dias</option>
                   </select>
                     <input type="hidden" name="nombre_1ref_comercial_c" value="" size="20"onChange="conMayusculas(this)"></td>
               </tr>
               <tr>
                 <td id="detalle_1"><input type="text" name="ref_comercial_c2" value="" size="30"onBlur="conMayusculas(this)"></td>
                 <td id="detalle_1"><input type="text" name="indicativo6"value= ""id="indicativo6" size="3"onKeyUp="return ValNumero(this)"onclick="this.value = ''"></td>
                 <td id="detalle_1"><input type="text" name="tel_2ref_comercial_c" value="" size="15"onKeyUp="return ValNumero(this)"></td>
                 <td id="detalle_1"><input type="text" name="extension6" id="extension6" size="5"onKeyUp="return ValNumero(this)" value="" ></td>
                 <td id="detalle_1"><input type="text" name="cupo_2ref_comercial_c" value="" size="20"></td>
                 <td id="detalle_1"><select name="plazo_2ref_comercial_c">
                     <option value=""></option>
                     <option value="PAGO DE CONTADO">Pago de Contado</option>
                     <option value="PAGO A 15 DIAS">Pago a 15 Dias</option>
                     <option value="PAGO A 30 DIAS">Pago a 30 Dias</option>
                     <option value="PAGO A 45 DIAS">Pago a 45 Dias</option>
                     <option value="PAGO A 60 DIAS">Pago a 60 Dias</option>
                     <option value="PAGO A 90 DIAS">Pago a 90 Dias</option>
                     <option value="PAGO A 999 DIAS">Pago a 999 Dias</option>
                 </select>
                 <input type="hidden" name="nombre_2ref_comercial_c" value="" size="20"onChange="conMayusculas(this)"></td>
               </tr>
               <tr>
                 <td id="detalle_1"><input type="text" name="ref_comercial_c3" value="" size="30"onBlur="conMayusculas(this)"></td>
                 <td id="detalle_1"><input type="text" name="indicativo7"value= ""id="indicativo7" size="3"onKeyUp="return ValNumero(this)"onclick="this.value = ''"></td>
                 <td id="detalle_1"><input type="text" name="tel_3ref_comercial_c" value="" size="15"onKeyUp="return ValNumero(this)"></td>
                 <td id="detalle_1"><input type="text" name="extension7" id="extension7" size="5"onKeyUp="return ValNumero(this)" value=""></td>
                 <td id="detalle_1"><input type="text" name="cupo_3ref_comercial_c" value="" size="20"></td>
                 <td id="detalle_1"><select name="plazo_3ref_comercial_c">
                   <option value=""></option>
                   <option value="PAGO DE CONTADO">Pago de Contado</option>
                     <option value="PAGO A 15 DIAS">Pago a 15 Dias</option>
                     <option value="PAGO A 30 DIAS">Pago a 30 Dias</option>
                     <option value="PAGO A 45 DIAS">Pago a 45 Dias</option>
                     <option value="PAGO A 60 DIAS">Pago a 60 Dias</option>
                     <option value="PAGO A 90 DIAS">Pago a 90 Dias</option>
                     <option value="PAGO A 999 DIAS">Pago a 999 Dias</option>
                   </select>
                     <input type="hidden" name="nombre_3ref_comercial_c" value="" size="20"onChange="conMayusculas(this)"></td>
               </tr>
           </table></td>
         </tr>
             <td colspan="4"><table width="100%" id="tabla_formato">
               <tr>         
  <td colspan="7" id="subtitulo2">REFERENCIAS BANCARIAS</td>
  <tr>
    <td width="19%" id="dato_2">REFERENCIAS BANCARIAS</td>
    <td width="7%"id="dato_2">INDICAT. </td>
    <td width="11%"id="dato_2">TELEFONOS</td>
    <td width="8%"id="dato_2">Extensiones</td>
    <td width="55%"id="dato_1">NOMBRES</td>
  </tr>
  <tr>
    <td id="detalle_1"><input type="text" name="ref_bancaria_c" value="" size="30"onBlur="conMayusculas(this)"></td>
    <td id="detalle_1"><input type="text" name="indicativo8"value= ""id="indicativo8" size="3"onKeyUp="return ValNumero(this)"onclick="this.value = ''"></td>
    <td id="detalle_1"><input type="text" name="telefono_1ref_bancaria_c" value="" size="15"onKeyUp="return ValNumero(this)"></td>
    <td id="detalle_1"><input type="text" name="extension8" id="extension8" size="5"onKeyUp="return ValNumero(this)" value="" ></td>
    <td id="detalle_1"><input type="text" name="nombre_1ref_bancaria_c" value="" size="45"onBlur="conMayusculas(this)"></td>
  </tr>
  <tr>
    <td id="detalle_1"><input type="text" name="ref_bancaria_c2" value="" size="30"onBlur="conMayusculas(this)"></td>
    <td id="detalle_1"><input type="text" name="indicativo9"value= ""id="indicativo9" size="3"onKeyUp="return ValNumero(this)"onclick="this.value = ''"></td>
    <td id="detalle_1"><input type="text" name="telefono_2ref_bancaria_c" value="" size="15"onKeyUp="return ValNumero(this)"></td>
    <td id="detalle_1"><input type="text" name="extension9" id="extension9" size="5"onKeyUp="return ValNumero(this)" value=""></td>
    <td id="detalle_1"><input type="text" name="nombre_2ref_bancaria_c" value="" size="45"onBlur="conMayusculas(this)"></td>
  </tr>
  <tr>
    <td id="detalle_1"><input type="text" name="ref_bancaria_c3" value="" size="30"onBlur="conMayusculas(this)"></td>
    <td id="detalle_1"><input type="text" name="indicativo10"value= ""id="indicativo10" size="3"onKeyUp="return ValNumero(this)"onclick="this.value = ''"></td>
    <td id="detalle_1"><input type="text" name="telefono_3ref_bancaria_c" value="" size="15"onKeyUp="return ValNumero(this)"></td>
    <td id="detalle_1"><input type="text" name="extension10" id="extension10" size="5"onKeyUp="return ValNumero(this)" value=""></td>
    <td id="detalle_1"><input type="text" name="nombre_3ref_bancaria_c" value="" size="45"onBlur="conMayusculas(this)"></td>
               </tr>
           </table></td>
         </tr>
    <td colspan="4" id="dato_1">Observaciones de la Informaci&oacute;n Financiera </td>
  </tr>
  <tr>
    <td colspan="4" id="detalle_1"><textarea name="observ_inf_finan_c" cols="100" rows="2"onBlur="conMayusculas(this)"></textarea></td>
  </tr>
  <tr>
    <td colspan="4" id="subtitulo2">APROBACION FINANCIERA</td>
  </tr>
  <tr>
    <td colspan="4" id="dato_1">Cupo Aprobado Plazo Aprobado</td>
  </tr>
  <tr>
    <td colspan="4"id="dato_1"onkeyUp="return ValNumero(this)"><input type="text" name="cupo_aprobado_c" value="" size="30"onkeyUp="return ValNumero(this)">
        <select name="plazo_aprobado_c">
                     <option value="ANTICIPADO">Anticipado</option>
                     <option value="PAGO DE CONTADO">Pago de Contado</option>
                     <option value="PAGO A 15 DIAS">Pago a 15 Dias</option>
                     <option value="PAGO A 30 DIAS">Pago a 30 Dias</option>
                     <option value="PAGO A 45 DIAS">Pago a 45 Dias</option>
                     <option value="PAGO A 60 DIAS">Pago a 60 Dias</option>
                     <option value="PAGO A 90 DIAS">Pago a 90 Dias</option>
                     <option value="PAGO A 120 DIAS">Pago a 120 Dias</option>
          </select></td>
  </tr>
  <tr>
    <td colspan="4"id="dato_1">Observaciones de la Aprobacion Financiera del Cliente </td>
  </tr>
  <tr>
    <td colspan="4"id="detalle_1"><textarea name="observ_aprob_finan_c" cols="100" rows="3"onBlur="conMayusculas(this)"></textarea></td>
  </tr>
  <tr>
    <td colspan="4" id="subtitulo2">APROBACION COMERCIAL</td>
  </tr>
  <tr>
    <td id="dato_1"> Estado Comercial </td>
    <td colspan="3" id="dato_1"><select name="estado_comercial_c">
      <option value="PENDIENTE">Pendiente</option>
      <option value="ACEPTADO">Aceptado</option>
      <option value="RECHAZADO">Rechazado</option>
    </select></td>
  </tr>
  <tr>
    <td colspan="4" id="dato_1">Observaciones de Aprobaci&oacute;n Comercial </td>
  </tr>
  <tr>
    <td colspan="4" id="detalle_1"><textarea name="observ_asesor_com_c" cols="100" rows="2"onBlur="conMayusculas(this)"></textarea></td>
  </tr>
  <tr>
    <td colspan="4" id="subtitulo2">DOCUMENTOS ADJUNTOS </td>
  </tr>
  <tr>
    <td colspan="2"nowrap id="dato_1">
        Camara de Comercio (Vigente)<br>
        <input name="camara_comercio_c" type="file" size="20" maxlength="60"class="botones_file">
      </td>
    <td colspan="2" nowrap id="dato_1">
    Referencias Bancarias<br>
    <input name="referencias_bancarias_c" type="file" size="20" maxlength="60"class="botones_file">
    </td>
  </tr>
  <tr>
    <td colspan="2" nowrap id="dato_1">
      Rut<br>
      <input name="balance_general_c" type="file" size="20" maxlength="60"class="botones_file"></td>
    <td colspan="2" nowrap id="dato_1">Referencias Comerciales<br>
    <input name="referencias_comerciales_c" type="file" size="20" maxlength="60"class="botones_file">
    </td>
  </tr>
  <tr>
    <td colspan="2"id="dato_1">Estado P&amp;G <br>
      <input type="file" name="estado_pyg_c" size="20"class="botones_file"></td>
    <td colspan="2" id="dato_1">Flujo Caja Proyectado<br>
      <input name="flujo_caja_proy_c" type="file" size="20" maxlength="60"class="botones_file"></td>
  </tr>
  <tr>
    <td colspan="2"id="dato_1">Fotocopia Declaraci&oacute;n IVA<br>
      <input name="fotocopia_declar_iva_c" type="file" size="20" maxlength="60"class="botones_file"></td>
    <td colspan="2" id="dato_1">Otros<br>
      <input name="otros_doc_c" type="file" size="20" maxlength="60"class="botones_file"></td>
</tr>
  <tr>
    <td colspan="4" id="dato_1">Observaciones de Documentos Adjuntos </td>
  </tr>
  <tr>
    <td colspan="4" id="detalle_1"><textarea name="observ_doc_c" cols="100" rows="2"onBlur="conMayusculas(this)"></textarea></td>
  </tr>
  <tr>
    <td colspan="4" id="subtitulo2">INFORMACION FINAL DEL FORMATO PERFIL DE CLIENTES </td>
  </tr>
  <tr>
    <td id="dato_1">ESTADO DEL CLIENTE
      <select name="estado_c" id="estado_c">
        <option value="ACTIVO">ACTIVO</option>
        <option value="PENDIENTE">PENDIENTE</option>
        <option value="INACTIVO">INACTIVO</option>
      </select>
      <input name="tipo_usuario" type="hidden" id="tipo_usuario" value="<?php echo $row_usuario['tipo_usuario']; ?>">
        <input name="id_usuario" type="hidden" id="id_usuario" value="<?php echo $row_usuario['id_usuario']; ?>"></td>
    <td colspan="3" id="dato_1">Registrado Por
      <input name="registrado_c" type="text" value="<?php  $usuar=strtoupper( $row_usuario['nombre_usuario']);echo $usuar; ?>" size="20"readonly></td>
  </tr>
  <tr>
    <td colspan="4" id="dato_2"><input name="save" type="submit" onClick="MM_validateForm('email','','NisEmail');return document.MM_returnValue" value="Adicionar Perfil de Cliente"></td>
  </tr>
       </table>
       <input type="hidden" name="MM_insert" value="form1">
</form></div>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($n_cliente);

mysql_free_result($n_pais);

mysql_free_result($n_ciudad);

//mysql_free_result($destinatario);
?>