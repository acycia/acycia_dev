<?php require_once('Connections/conexion1.php'); ?>
<?php

/* require_once("db/db.php"); */
require_once 'Models/Mgeneral.php';
 require_once("db/db.php"); 
 require_once("Controller/Cgeneral.php");

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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $tipo2=$_POST['tipo_usuario'];
  if($tipo2=='10')
  {
    $nit2=$_POST['nit_c'];
    $usuario2=$_POST['id_usuario'];
    $sql2="UPDATE usuario SET nit='$nit2' WHERE id_usuario='$usuario2'";
    $result2=mysql_query($sql2);
  } 
//ESTE CODIGO ES PORQUE EN CIUDAD_C VIENE  EL IND_CIUDAD Y EL ID_CIUDAD hay que dividirlos 
  $guardarCiudad =$_POST['ciudad_c'];
//$guardarCiudad[0];
//$guardarCiudad[1];

  mysql_select_db($database_conexion1, $conexion1);
  $query_destinatario = "SELECT * FROM Tbl_ciudades_col WHERE ind_ciudad='$guardarCiudad[0]'AND id_ciudad='$guardarCiudad[1]'";
  $destinatario = mysql_query($query_destinatario, $conexion1) or die(mysql_error());
  $row_destinatario = mysql_fetch_assoc($destinatario);
  $totalRows_destinatario = mysql_num_rows($destinatario);
  $newciudad=$row_destinatario['nombre_ciudad'];
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
 $teleconcatenado = str_replace(' ', '', $teleconcatenado);
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
 if (($_POST['tipo_c']=='NACIONAL')&&($_POST['pais_c']=='COLOMBIA')){

  $ciudad_c=$_POST['ciudad_c'];}else{$ciudad_c=$_POST['ciudadexterno'];} 

  /*if(!(strcmp("NACIONAL", $_POST['tipo_c'])))&& (!(strcmp("COLOMBIA", $_POST['ciudad_c']))) */

/*if(isset($_POST['ciudad_c'])&&$_POST['ciudad_c']=='COLOMBIA'){
  $ciudad_c=$_POST['ciudadexterno'];}else{$ciudad_c=$_POST['ciudad_c'];}*/
//GUARDAR DATOS VALIDADOS SIN PUNTOS NO COMAS ETC
  $num2=trim($_POST['nit_c']);
  $nit_c = ereg_replace("[^A-Za-z0-9-]", "", $num2);
  $nit_c=str_replace(' ', '', $nit_c);

  $nit_c = explode('-', $nit_c);
  $nit_c = $nit_c[0].$nit_c[1];

//GUARDAR NOMBRE SIN ESPACIOS INICIO Y FIN
  $nomb=trim($_POST['nombre_c']);
//PARA SUBIR ARCHIVOS PDF
/*$nombre3=$_POST['arc3'];
$nombre4=$_POST['arc4'];
$nombre5=$_POST['arc5'];
$nombre6=$_POST['arc6'];
$nombre7=$_POST['arc7'];
$nombre8=$_POST['arc8'];*/
$nombre1 = $_POST['arc1'];
$nombre2 = $_POST['arc2'];
$nombre5 = $_POST['arc5'];
$nombre9 = $_POST['arc9'];

if (isset($_FILES['camara_comercio_c']) && $_FILES['camara_comercio_c']['name'] != "") {
  if($nombre1 == '') {
    if (file_exists("archivosc/".$nombre1))
      { unlink("archivosc/".$nombre1);  } 
  } 
  $directorio1 = "archivosc/";
  $nombre1 = str_replace(' ', '',  $_FILES['camara_comercio_c']['name']);
  $archivo_temporal1 = $_FILES['camara_comercio_c']['tmp_name'];
  if (!copy($archivo_temporal1,$directorio1.$nombre1)) {
    $error = "Error al enviar el Archivo";
  } else { $imagen1 = "archivosc/".$nombre1; }

}

if (isset($_FILES['balance_general_c']) && $_FILES['balance_general_c']['name'] != "") {
  if($nombre2 == '') {
    if (file_exists("archivosc/".$nombre2))
      { unlink("archivosc/".$nombre2);  } 
  } 
  $directorio2 = "archivosc/";
  $nombre2 = str_replace(' ', '',  $_FILES['balance_general_c']['name']);
  $archivo_temporal2 = $_FILES['balance_general_c']['tmp_name'];
  if (!copy($archivo_temporal2,$directorio2.$nombre2)) {
    $error = "Error al enviar el Archivo";
  } else { $imagen2 = "archivosc/".$nombre2; }
  
}
if (isset($_FILES['referencias_bancarias_c']) && $_FILES['referencias_bancarias_c']['name'] != "") {
  if($nombre5 == '') {
    if (file_exists("archivosc/".$nombre5))
      { unlink("archivosc/".$nombre5);  } 
  } 
  $directorio5 = "archivosc/";
  $nombre5 = str_replace(' ', '',  $_FILES['referencias_bancarias_c']['name']);
  $archivo_temporal5 = $_FILES['referencias_bancarias_c']['tmp_name'];
  if (!copy($archivo_temporal5,$directorio5.$nombre5)) {
    $error = "Error al enviar el Archivo";
  } else { $imagen5 = "archivosc/".$nombre5; }

}

if (isset($_FILES['pdf_impuesto']) && $_FILES['pdf_impuesto']['name'] != "") {
  if($nombre9 == '') {
    if (file_exists("archivosc/impuesto/".$nombre9))
      { unlink("archivosc/impuesto/".$nombre9);  } 
  } 
  $directorio9 = "archivosc/impuesto/";
  $nombre9 = str_replace(' ', '',  $_FILES['pdf_impuesto']['name']);
  $archivo_temporal9 = $_FILES['pdf_impuesto']['tmp_name'];
  if (!copy($archivo_temporal9,$directorio9.$nombre9)) {
    $error = "Error al enviar el Archivo";
  } else { $imagen9 = "archivosc/impuesto/".$nombre9; }

}

/*if (isset($_FILES['estado_pyg_c']) && $_FILES['estado_pyg_c']['name'] != "") {
if($nombre3 != '') {
if (file_exists("archivosc/".$nombre3))
{ unlink("archivosc/".$nombre3);  } 
}
$directorio = "archivosc/";
$nombre3 = str_replace(' ', '',  $_FILES['estado_pyg_c']['name']);
$archivo_temporal = $_FILES['estado_pyg_c']['tmp_name'];
if (!copy($archivo_temporal,$directorio.$nombre3)) {
$error = "Error al enviar el Archivo";
} else { $imagen3 = "archivosc/".$nombre3; }
}
if (isset($_FILES['fotocopia_declar_iva_c']) && $_FILES['fotocopia_declar_iva_c']['name'] != "") {
if($nombre4 != '') {
if (file_exists("archivosc/".$nombre4))
{ unlink("archivosc/".$nombre4);  } 
}
$directorio = "archivosc/";
$nombre4 = str_replace(' ', '',  $_FILES['fotocopia_declar_iva_c']['name']);
$archivo_temporal = $_FILES['fotocopia_declar_iva_c']['tmp_name'];
if (!copy($archivo_temporal,$directorio.$nombre4)) {
$error = "Error al enviar el Archivo";
} else { $imagen4 = "archivosc/".$nombre4; }
}


if (isset($_FILES['referencias_comerciales_c']) && $_FILES['referencias_comerciales_c']['name'] != "") {
if($nombre6 != '') {
if (file_exists("archivosc/".$nombre6))
{ unlink("archivosc/".$nombre6);  } 
}
$directorio = "archivosc/";
$nombre6 = str_replace(' ', '',  $_FILES['referencias_comerciales_c']['name']);
$archivo_temporal = $_FILES['referencias_comerciales_c']['tmp_name'];
if (!copy($archivo_temporal,$directorio.$nombre6)) {
$error = "Error al enviar el Archivo";
} else { $imagen6 = "archivosc/".$nombre6; }
}
if (isset($_FILES['flujo_caja_proy_c']) && $_FILES['flujo_caja_proy_c']['name'] != "") {
if($nombre7 != '') {
if (file_exists("archivosc/".$nombre7))
{ unlink("archivosc/".$nombre7);  } 
}
$directorio = "archivosc/";
$nombre7 = str_replace(' ', '',  $_FILES['flujo_caja_proy_c']['name']);
$archivo_temporal = $_FILES['flujo_caja_proy_c']['tmp_name'];
if (!copy($archivo_temporal,$directorio.$nombre7)) {
$error = "Error al enviar el Archivo";
} else { $imagen7 = "archivosc/".$nombre7; }
}
if (isset($_FILES['otros_doc_c']) && $_FILES['otros_doc_c']['name'] != "") {
if($nombre8 != '') {
if (file_exists("archivosc/".$nombre8))
{ unlink("archivosc/".$nombre8);  } 
}
$directorio = "archivosc/";
$nombre8 = str_replace(' ', '',  $_FILES['otros_doc_c']['name']);
$archivo_temporal = $_FILES['otros_doc_c']['tmp_name'];
if (!copy($archivo_temporal,$directorio.$nombre8)) {
$error = "Error al enviar el Archivo";
} else { $imagen8 = "archivosc/".$nombre8; }
}*/
//FIN DE LA VALIDACION Y SUBIDA DE ARCHIVOS PDF
$insertSQL = sprintf("UPDATE cliente SET nit_c=%s, nombre_c=%s, tipo_c=%s, fecha_ingreso_c=%s, fecha_solicitud_c=%s, rep_legal_c=%s, telefono_c=%s, direccion_c=%s, fax_c=%s, contacto_c=%s, cargo_contacto_c=%s, telefono_contacto_c=%s, celular_contacto_c=%s, pais_c=%s, provincia_c=%s, ciudad_c=%s, email_comercial_c=%s, contacto_bodega_c=%s, cargo_contacto_bodega_c=%s, direccion_entrega_c=%s, email_contacto_bodega_c=%s, pais_bodega_c=%s, provincia_bodega_c=%s, ciudad_bodega_c=%s, telefono_bodega_c=%s, fax_bodega_c=%s, direccion_envio_factura_c=%s, telefono_envio_factura_c=%s, fax_envio_factura_c=%s, observ_inf_c=%s, contacto_dpto_pagos_c=%s, telefono_dpto_pagos_c=%s, fax_dpto_pagos_c=%s, direccion_dpto_pagos_c=%s, email_dpto_pagos_c=%s, cupo_solicitado_c=%s, forma_pago_c=%s, otro_pago_c=%s, 1ref_comercial_c=%s, tel_1ref_comercial_c=%s, nombre_1ref_comercial_c=%s, cupo_1ref_comercial_c=%s, plazo_1ref_comercial_c=%s, 2ref_comercial_c=%s, tel_2ref_comercial_c=%s, nombre_2ref_comercial_c=%s, cupo_2ref_comercial_c=%s, plazo_2ref_comercial_c=%s, 3ref_comercial_c=%s, tel_3ref_comercial_c=%s, nombre_3ref_comercial_c=%s, cupo_3ref_comercial_c=%s, plazo_3ref_comercial_c=%s, 1ref_bancaria_c=%s, telefono_1ref_bancaria_c=%s, nombre_1ref_bancaria_c=%s, 2ref_bancaria_c=%s, telefono_2ref_bancaria_c=%s, nombre_2ref_bancaria_c=%s, 3ref_bancaria_c=%s, telefono_3ref_bancaria_c=%s, nombre_3ref_bancaria_c=%s, observ_inf_finan_c=%s, cupo_aprobado_c=%s, plazo_aprobado_c=%s, observ_aprob_finan_c=%s, estado_comercial_c=%s, observ_asesor_com_c=%s, camara_comercio_c=%s, referencias_bancarias_c=%s, referencias_comerciales_c=%s, estado_pyg_c=%s, balance_general_c=%s,  flujo_caja_proy_c=%s,  fotocopia_declar_iva_c=%s,  otros_doc_c=%s, observ_doc_c=%s, estado_c=%s, revisado_c=%s, fecha_revision_c=%s, email_factura_c=%s,impuesto=%s,pdf_impuesto=%s WHERE id_c=%s",
 GetSQLValueString($nit_c, "text"),
 GetSQLValueString($nomb, "text"),
 GetSQLValueString($_POST['tipo_c'], "text"),
 GetSQLValueString($_POST['fecha_ingreso_c'], "date"),
 GetSQLValueString($_POST['fecha_solicitud_c'], "date"),
/*           GetSQLValueString(isset($_POST['bolsa_plastica_c']) ? "true" : "", "defined","1","0"),
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
            GetSQLValueString($_POST['revisado_c'], "text"),
            GetSQLValueString($_POST['fecha_revision_c'], "date"),
            GetSQLValueString($_POST['email_factura_c'], "text"),
            GetSQLValueString(isset($_POST['impuesto']) ? "true" : "", "defined","1","0"),
            GetSQLValueString($nombre9, "text"),
            GetSQLValueString($_POST['id_c'], "int"));

mysql_select_db($database_conexion1, $conexion1);
$Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

//INSERT DE LOGS


$conexion = new CgeneralController();
//GUARDADO DE HISTORICOS
if(isset($_POST['id_c']) && $_REQUEST){
 
  $conexion->insertLogs("tbl_logs","codigo_id, descrip, fecha, modificacion, usuario", $_REQUEST, "CLIENTE","se modifico en cliente edit");
}//FIN LOGS

//UPDATE PARA LA TABLA TBL_DESTINATARIOS
//CODIGO PARA CAPTURAR REGISTROS DEL ARRAY DE LOS CAMPOS DINAMICOS
/*$eliminar=$_POST['eliminar'];
$ide_b=$_POST['id_b'];
$id_d_b=$_POST['id_c'];
$pnt_b=$_POST['nit_c'];
$nombre_b=$_POST['responsable_dest_b'];
$dir_b=$_POST['direccion_dest_b'];
$ind_b=$_POST['indicativo_dest_b'];
$tel_b=$_POST['telefono_dest_b'];
$ext_b=$_POST['extension_dest_b'];
$ciu_b=$_POST['ciudad_dest_b'];
//CODIGO PARA ENVIAR ARRAY POR GET, DEBE ESTAR EN ESTA POSICION PARA QUE PUEDA SER LEIDO
if($eliminar!=''){
 function array_envia($array) { 
   $tmp = serialize($array); 
   $tmp = urlencode($tmp); 
   return $tmp; 
 }  
 $array=($eliminar); 
 $array=array_envia($array);
}
//FIN CODIGO     
for($id=0,$idd=0,$pn=0,$n=0,$d=0,$i=0,$t=0,$e=0,$c=0;$n<count($dir_b);$id++,$idd++,$pn++,$n++,$d++,$i++,$t++,$e++,$c++){
  $dir_des=strtoupper($dir_b[$d]);//pasa a mayusculas
  
  $insertSQL2 = sprintf("UPDATE Tbl_Destinatarios SET id_d=%s,nit=%s,nombre_responsable=%s,direccion=%s,indicativo=%s,telefono=%s,extension=%s,ciudad=%s WHERE id= %s",                                            
   GetSQLValueString($id_d_b, "text"),
   GetSQLValueString($pnt_b, "text"),
   GetSQLValueString($nombre_b[$n], "text"),
   GetSQLValueString($dir_des, "text"),
   GetSQLValueString($ind_b[$i], "text"),
   GetSQLValueString($tel_b[$t], "text"),
   GetSQLValueString($ext_b[$e], "text"),
   GetSQLValueString($ciu_b[$c], "text"),
   GetSQLValueString($ide_b[$id], "int"));      
  mysql_select_db($database_conexion1, $conexion1);
  $Result2 = mysql_query($insertSQL2, $conexion1) or die(mysql_error());                 
//FIN UPDATE PARA LA TABLA TBL_DESTINATARIOS
}*/
//INSERTAR BODEGAE DESDE LA PAGINA UPDATE
//if($_POST['responsable_dest']!=''&&$_POST['direccion_dest']!=''&&$_POST['indicativo_dest']!=''&&$_POST['telefono_dest']!=''&&$_POST['extension_dest']!=''&&$_POST['ciudad_dest']!=''){
/*$ide=$_POST['id'];
$id_d=$_POST['id_c'];
$pnt=$_POST['nit_c'];
$nombre=$_POST['responsable_dest'];
$dir=$_POST['direccion_dest'];

$ind=$_POST['indicativo_dest'];
$tel=$_POST['telefono_dest'];
$ext=$_POST['extension_dest'];
$ciu=$_POST['ciudad_dest'];
for($n=0,$d=0,$i=0,$t=0,$e=0,$c=0;$n<count($dir);$n++,$d++,$i++,$t++,$e++,$c++){
  if(isset($dir[$d]) && $dir[$d]!='')//controlo bodegas vacias
 {
  $insertSQL2 = sprintf("INSERT INTO Tbl_Destinatarios (id_d, nit,nombre_responsable,direccion,indicativo,telefono,extension,ciudad) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
   GetSQLValueString($id_d, "int"),
   GetSQLValueString($pnt, "text"),
   GetSQLValueString($nombre[$n], "text"),
   GetSQLValueString($dir[$d], "text"),
   GetSQLValueString($ind[$i], "text"),
   GetSQLValueString($tel[$t], "text"),
   GetSQLValueString($ext[$e], "text"),
   GetSQLValueString($ciu[$c], "text"));      
  mysql_select_db($database_conexion1, $conexion1);
  $Result2 = mysql_query($insertSQL2, $conexion1) or die(mysql_error());             
   }//if bodegas vacias
 }*/
//FIN INSERT PARA LA TABLA TBL_DESTINATARIOS 
 $updateGoTo = "perfil_cliente_vista.php?tipo_usuario=" . $_POST["tipo_usuario"] ."&nit_c=" . $_POST["nit_c"]."&array=" . $array;
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
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

$colname_cliente = "-1";
if (isset($_GET['id_c'])) {
  $colname_cliente = (get_magic_quotes_gpc()) ? $_GET['id_c'] : addslashes($_GET['id_c']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_cliente = sprintf("SELECT * FROM cliente WHERE id_c = %s", $colname_cliente);
$cliente = mysql_query($query_cliente, $conexion1) or die(mysql_error());
$row_cliente = mysql_fetch_assoc($cliente);
$totalRows_cliente = mysql_num_rows($cliente);

mysql_select_db($database_conexion1, $conexion1);
$query_n_pais = sprintf("select * FROM Tbl_paises  ORDER BY id_pais ASC",$colname_pais);
$n_pais = mysql_query($query_n_pais, $conexion1) or die(mysql_error());
$row_n_pais = mysql_fetch_assoc($n_pais);
$totalRows_n_pais = mysql_num_rows($n_pais);

mysql_select_db($database_conexion1, $conexion1);
$query_n_ciudad = "select * FROM Tbl_ciudades_col ORDER BY nombre_ciudad ASC";
$n_ciudad = mysql_query($query_n_ciudad, $conexion1) or die(mysql_error());
$row_n_ciudad = mysql_fetch_assoc($n_ciudad);
$totalRows_n_ciudad = mysql_num_rows($n_ciudad);

//CODIGO DE VERIFICACION BODEGAS
$colname_ver_bodegas= "-1";
if (isset($_GET['id_c'])) 
{
  $colname_ver_bodegas = (get_magic_quotes_gpc()) ? $_GET['id_c'] : addslashes($_GET['id_c']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_bodegas = sprintf("SELECT * FROM Tbl_Destinatarios  WHERE  id_d= '%s'",$colname_ver_bodegas);
$ver_bodegas = mysql_query($query_ver_bodegas, $conexion1) or die(mysql_error());
$row_bodegas= mysql_fetch_assoc($ver_bodegas);//pendiente si quiero quitar lo puedo hacer
$num1=mysql_num_rows($ver_bodegas);

?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <title>SISADGE AC &amp; CIA</title>
  <script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
  <link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">

  <link href="css/general.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="js/formato.js"></script>
  <script type="text/javascript" src="js/listado.js"></script>
  <script type="text/javascript" src="js/consulta.js"></script>
  <script type="text/javascript" src="js/consulta_ciudad.js"></script>
  <script type="text/javascript" src="js/validacion_numerico.js"></script>
  <script type="text/javascript" src="js/agregueCampos.js"></script>
  <script type="text/javascript" src="AjaxControllers/js/actualiza.js"></script>

  <script type="text/javascript" src="AjaxControllers/js/insert.js"></script>
  <script type="text/javascript" src="AjaxControllers/js/consultas.js"></script>
  <script type="text/javascript" src="AjaxControllers/js/delete.js"></script> 
  <script type="text/javascript" src="AjaxControllers/Actions/guardar.php"></script> 
  <!-- <script type="text/javascript" src="js/delete.js"></script> -->
  <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
  <script type="text/javascript" src="js/mayusculasTodo.js"></script>

  <!-- sweetalert -->
  <script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
  <link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">
  <!-- jquery -->
  <script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
  <script src="//code.jquery.com/jquery-1.11.2.min.js"></script> 
  <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
  <!-- css Bootstrap-->
  <link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <!--CODIGO PARA LOS CAMPOS AGREGADOS DE BODEGAS-->
</head>
<body onLoad="insert();">
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
     <form method="post" name="form1" action="<?php echo $editFormAction; ?>"  enctype="multipart/form-data" onSubmit="MM_validateForm('fecha_ingreso_c','','R', 'nombre_c','','R','nit_c','','R','tipo_c','','R','rep_legal_c','','R','telefono_c','','R','direccion_c','','R','pais_c','','R' );return document.MM_returnValue">
       <table width="114%" id="tabla_formato2">
         <tr>
           <td width="17%" id="codigo_formato_2">CODIGO: R1-F07</td>
           <td colspan="1" id="titulo_formato_2">PERFIL DE CLIENTES</td>
           <td colspan="2" id="codigo_formato_2">VERSION: 1</td>
         </tr>
         <tr>
           <td rowspan="6" id="logo_2"><img src="images/logoacyc.jpg"></td>
           <td id="dato_1">Fecha de Ingreso
             <input name="tipo_usuario" type="hidden" id="tipo_usuario" value="<?php echo $row_usuario['tipo_usuario']; ?>">
             <input name="id_usuario" type="hidden" id="id_usuario" value="<?php echo $row_usuario['id_usuario']; ?>"></td>
             <?php $tipo=$row_usuario['tipo_usuario']; ?>
             <td id="dato_2"><a href="perfil_cliente_vista.php?id_c=<?php echo $row_cliente['id_c']; ?>&tipo_usuario=<?php echo $row_usuario['tipo_usuario']; ?>"><img src="images/hoja.gif" style="cursor:hand;" alt="VISTA IMPRESION"title="VISTA IMPRESION" border="0"></a><?php $tipo=$row_usuario['tipo_usuario']; if($tipo=='1' || $tipo=='2') { ?><a href="javascript:eliminar_cliente('id_c',<?php echo $row_cliente['id_c']; ?>,'listado_clientes.php')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR CLIENTE" title="ELIMINAR CLIENTE" border="0"></a><a href="listado_clientes.php"><img src="images/cat.gif" border="0" style="cursor:hand;" alt="LISTADO CLIENTES" title="LISTADO CLIENTES"></a><?php } ?><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR" title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
             <td id="dato_1">Fecha de Solicitud.</td>
           </tr>
           <tr>
             <td id="dato_1"><input name="fecha_ingreso_c" type="date" value="<?php echo $row_cliente['fecha_ingreso_c']; ?>" size="10"></td>
             <td id="dato_1">&nbsp;</td>
             <td id="dato_1"><input type="date" name="fecha_solicitud_c" value="<?php echo $row_cliente['fecha_solicitud_c']; ?>" size="10"></td>

           </tr>
           <tr>
             <td id="dato_1">NIT 000000000-0</td>
             <td id="dato_1">Cliente N&deg;<?php echo $row_cliente['id_c']; ?></td>
             <td id="dato_1">&nbsp;</td>

           </tr>
           <tr>
             <td colspan="3" id="dato_1">
              <input name="nit_c" type="hidden" onChange="desapareceClick(this)"  value="<?php echo $row_cliente['nit_c']; ?>" size="30" readonly onClick="reasignarnit()"><a href="perfil_cliente_reasignacion.php?nit=<?php echo $row_cliente['nit_c'];?>" title="Reasignacion del nit" target="new"><em><?php echo $row_cliente['nit_c'];?> Reasignar nit</em></a>
              <input name="nit" type="hidden" id="nit" value="<?php echo $row_cliente['nit_c']; ?>"></td> 
            </tr>
            <tr>
             <td id="dato_1"> Raz&oacute;n Social</td>
             <td id="dato_1">Tipo de Cliente </td>
             <td id="dato_1">&nbsp;</td>

           </tr> 
           <tr>
            <td id="dato_1"><input name="nombre_c" type="text" onBlur="MayusculaSinEspacios(this)"  onChange="desapareceClick(this);" value="<?php echo  ($row_cliente['nombre_c']); ?>" size="30" maxlength="100">      </td>
            <td id="dato_1"><select name="tipo_c" tabindex="1\" >
             <option value="">*</option>
             <option value="NACIONAL" <?php if (!(strcmp("NACIONAL", $row_cliente['tipo_c']))) {echo "selected=\"selected\"";} ?>>Nacional</option>
             <option value="EXTRANJERO"<?php if (!(strcmp("EXTRANJERO", $row_cliente['tipo_c']))) {echo "selected=\"selected\"";} ?>>Extranjero</option>
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
                 <td id="dato_1">Representante Legal </td>
                 <td colspan="1" id="dato_1">Indicat. Telefono(s). Extension</td>
                 <td colspan="2" id="dato_1">Pais</td>
               </tr>
               <tr>
                 <td colspan="1" id="dato_1"><input name="rep_legal_c" type="text"  value="<?php echo ($row_cliente['rep_legal_c']); ?>" size="50" maxlength="100" ></td>
                 <td colspan="1" id="detalle_1"><input type="text" name="indicativo1" value= "<?php $tel= $row_cliente['telefono_c'];list($ind,$tele,$ext)=explode("/",$tel);echo $ind?>" id="indicativo1" style="width:40px" onKeyUp="return ValNumero(this)">
                   <input type="number" style="width:130px" name="telefono_c" value="<?php echo $tele; ?>" >
                   <input  type="number" style="width:60px" name="extension1" id="extension1"  value="<?php echo $ext;?>"></td>
                   <td colspan="2" ><select  class='Estilo7' name="pais_c" id="id_pais" style="width:250px">
                     <option value="0" <?php if (!(strcmp(0, $row_cliente['pais_c']))) {echo "selected=\"selected\"";} ?>>Seleccione pais</option>
                     <?php
                     do {  
                      ?>
                      <option value="<?php echo  ($row_n_pais['nombre_pais'])?>"<?php if (!(strcmp($row_n_pais['nombre_pais'], $row_cliente['pais_c']))) {echo "selected=\"selected\"";} ?>><?php echo ($row_n_pais['nombre_pais']);?></option>
                      <?php
                    } while ($row_n_pais = mysql_fetch_assoc($n_pais));
                    $rows = mysql_num_rows($n_pais);
                    if($rows > 0) {
                      mysql_data_seek($n_pais, 0);
                      $row_n_pais = mysql_fetch_assoc($n_pais);
                    }
                    ?>
                  </select>
                </td>
              </tr>
              <tr>
               <td id="dato_1"> Direcci&oacute;n Comercial</td>
               <td id="dato_1">&nbsp;</td>
               <td id="dato_1">Ciudad</td>
               <td id="dato_5">&nbsp;</td>

             </tr>
             <tr>
               <td id="dato_1"><input name="direccion_c"  id="dirC" type="text" onBlur="copiadirC()" value="<?php echo ($row_cliente['direccion_c']); ?>" size="50" maxlength="500" required></td>
               <td id="dato_4"></td>
               <td colspan="2" id="dato_4">
                 <?php 
/*      $datos= $_GET['ciudad'];
      $d = explode( '.',$datos);
      $ind=$d[0];
      $id_ci=$d[1]; */
      ?>                
      <select class='Estilo7' name="ciudad_c" id='miCampoDeTexto' style="width:250px">
       <option value="" <?php if (!(strcmp('', $row_cliente['ciudad_c']))) {echo "selected=\"selected\"";} ?>>Seleccione Ciudad</option>
       <?php
       do {  
        ?>
        <option value="<?php echo ($row_n_ciudad['nombre_ciudad'])?>"<?php if (!(strcmp($row_n_ciudad['nombre_ciudad'], $row_cliente['ciudad_c']))) {echo "selected=\"selected\"";}?>><?php echo ($row_n_ciudad['nombre_ciudad']);?></option>
        <?php
      } while ($row_n_ciudad = mysql_fetch_assoc($n_ciudad));
      $rows = mysql_num_rows($n_ciudad);
      if($rows > 0) {
        mysql_data_seek($n_ciudad, 0);
        $row_n_ciudad = mysql_fetch_assoc($n_ciudad);
      }
      ?>
    </select>
  </td>           
</tr>
<tr>
 <td id="dato_1">Email Comercial. </td>
 <td width="6%" id="dato_1">Indicat. Fax inf. general. Extension</td>
 <td width="13%" id="dato_1">Ciudad Extranjera</td>
 <td width="5%" id="dato_1">&nbsp;</td>

</tr>
<tr>
 <td id="dato_1"><input id="email" type="text" name="email_comercial_c" value="<?php echo $row_cliente['email_comercial_c']; ?>" size="50"onblur="MM_validateForm('email_comercial_c','','NisEmail'); return document.MM_returnValue,conMayusculas(this)"></td>
 <td id="detalle_1"><input name="indicativo11" type="text" id="indicativo11" onClick="this.value = ''" onKeyUp="return ValNumero(this)" value= "<?php $tel= $row_cliente['fax_c'];list($ind,$tele,$ext)=explode("/",$tel);echo $ind?>" style="width:40px">
   <input type="number" style="width: 130px" name="fax_c" value="<?php echo $tele;?>" >
   <input type="number" style="width:60px" name="extension11" id="extension11" value="<?php echo $ext?>"></td>
   <td colspan="2" id="dato_1">
     <input name="ciudadexterno" type="text" style="width: 250px" value="<?php echo $row_cliente['ciudadexterno'] ?>" id="ciudadexterno" /> </td>
   </tr>
   <tr>
     <td id="dato_1">&nbsp;</td>
     <td colspan="3" id="dato_6"><input type="hidden" name="provincia_c" value="" size="20"></td>
   </tr>
   <tr>
     <td colspan="4" id="subtitulo2">INFORMACION DEL CONTACTO GENERAL</td>
   </tr>
   <tr>
     <td colspan="1" id="dato_1">Nombre del Contacto Comercial </td>
     <td id="dato_1">Cargo  Cont. Com.</td>
     <td id="dato_1">Email Contact.Comercial.</td>
     <td id="dato_1">&nbsp;</td>

   </tr>
   <tr>
     <td colspan="1" id="dato_1"><input name="contacto_c" type="text"  value="<?php echo $row_cliente['contacto_c']; ?>" size="50" maxlength="100" ></td>
     <td id="dato_1"><input type="text" name="cargo_contacto_c" value="<?php echo $row_cliente['cargo_contacto_c']; ?>" style="width: 250px"  ></td>
     <td colspan="2" id="dato_1"><input type="text" name="email_contacto_bodega_c" value="<?php echo $row_cliente['email_contacto_bodega_c']; ?>" style="width: 250px" onBlur="MM_validateForm('email_comercial_c','','NisEmail');return document.MM_returnValue" ></td>
   </tr>
   <tr>
     <td id="dato_1">Direcci&oacute;n Envio de Factura </td>
     <td width="6%" id="dato_1">Indic.  Telefono En. Factura Extension</td>
     <td width="13%" id="dato_1">Indicat. Fax Envio de Factura. Extension</td>
     <td width="5%" id="dato_1">&nbsp;</td>

   </tr>
   <tr>
     <td id="dato_1"><input name="direccion_envio_factura_c" id="dirF" type="text"  value="<?php echo $row_cliente['direccion_envio_factura_c']; ?>" size="50" maxlength="500" required></td>
     <td id="detalle_1"><input type="text" name="indicativo3" value= "<?php $tel=$row_cliente['telefono_envio_factura_c'];list($ind,$tele,$ext)=explode("/",$tel);echo $ind; ?>" id="indicativo3" style="width:40px" onKeyUp="return ValNumero(this)" onClick="this.value = ''">
       <input type="number" style="width: 130px"  name="telefono_envio_factura_c" value="<?php echo $tele;?>" >
       <input type="number" style="width:60px" name="extension3" id="extension3" size="1" onKeyUp="return ValNumero(this)" value="<?php echo $ext;?>" ></td>
       <td  nowrap id="detalle_1"><input type="text" name="indicativo12" value= "<?php $tel= $row_cliente['fax_envio_factura_c'];list($ind,$tele,$ext)=explode("/",$tel);echo $ind?>" id="indicativo12" style="width:40px" onKeyUp="return ValNumero(this)" onClick="this.value = ''">
         <input type="number" style="width: 130px" name="fax_envio_factura_c" value="<?php echo $tele;?>" >
         <input type="number" style="width:60px" name="extension12" id="extension12"  value="<?php echo $ext?>"></td>
         <td id="dato_1">&nbsp;</td>
       </tr>
       <tr>
         <td id="dato_1">Celular Contacto Comercial</td>
         <td id="detalle_3">Email Factura</td>
         <td id="detalle_3">&nbsp;</td>
         <td id="detalle_3">&nbsp;</td>
       </tr>
       <tr>
         <td id="dato_1"><input type="text" name="celular_contacto_c" value="<?php echo $row_cliente['celular_contacto_c']; ?>" size="50" onKeyUp="return ValNumero(this)" ></td>
         <td id="dato_1"><input id="email" type="text" name="email_factura_c" value="<?php echo $row_cliente['email_factura_c']; ?>" size="50"onblur="MM_validateForm('email_factura_c','','NisEmail'); return conMayusculas(this)" required="required" ></td>
         <td id="dato_1"><input type="hidden" name="fax_bodega_c" value="" size="30" onKeyUp="return ValNumero(this)">
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
           <td colspan="4" id="detalle_1"><textarea name="observ_inf_c" cols="100" rows="2" ><?php echo $row_cliente['observ_inf_c']; ?></textarea></td>
         </tr>























          <tr>
           <td colspan="4"> 
           </td>
         </tr>
         <tr>
           <td colspan="4" id="subtitulo2">INFORMACION FINANCIERA </td>
         </tr>
         <tr>
           <td id="dato_1">Contacto Dpto Pagos</td>
           <td width="6%"id="dato_1">Indicativo. Telefono. Extension</td>
           <td width="13%"id="dato_1">E-mail</td>
           <td width="5%"id="dato_1">&nbsp;</td>
         </tr>
         <tr>
           <td id="dato_1"><input type="text" name="contacto_dpto_pagos_c" value="<?php echo $row_cliente['contacto_dpto_pagos_c']; ?>" size="50" ></td>
           <td id="detalle_1"><input type="text" name="indicativo4" value= "<?php $tel= $row_cliente['telefono_dpto_pagos_c'];list($ind,$tele,$ext)=explode("/",$tel);echo $ind?>" id="indicativo4" style="width:40px" onKeyUp="return ValNumero(this)"onclick="this.value = ''">
             <input type="number" style="width: 130px" name="telefono_dpto_pagos_c" value="<?php echo $tele;?>" >
             <input type="number" style="width:60px" name="extension4" id="extension4" value="<?php echo $ext?>"></td>
             <td colspan="2" id="dato_1"><input type="text" name="email_dpto_pagos_c" value="<?php echo $row_cliente['email_dpto_pagos_c']; ?>" style="width: 250px" onChange="conMayusculas(this)"onblur="MM_validateForm('email_dpto_pagos_c','','NisEmail');return document.MM_returnValue"autocomplete='off'></td>
           </tr>
           <tr>
             <td id="dato_1">Direcci&oacute;n</td>
             <td id="dato_1"> Indicat. Fax financiera. Exension</td>
             <td id="dato_1">Cupo Solicitado ($)</td>
             <td id="dato_1">&nbsp;</td>          
           </tr>
           <tr>
             <td id="dato_1"><input type="text" name="direccion_dpto_pagos_c" value="<?php echo $row_cliente['direccion_dpto_pagos_c']; ?>" size="50" autocomplete='off'></td>
             <td id="detalle_1"><input type="text" name="indicativo13" value= "<?php $tel= $row_cliente['fax_dpto_pagos_c'];list($ind,$tele,$ext)=explode("/",$tel);echo $ind?>" id="indicativo13" style="width:40px" onKeyUp="return ValNumero(this)" onClick="this.value = ''">
               <input type="number" style="width: 130px" name="fax_dpto_pagos_c" value="<?php echo $tele;?>" >
               <input type="number" style="width:60px" name="extension13" id="extension13"  value="<?php echo $ext;?>"></td>
               <td colspan="2" id="dato_1"><input type="text" name="cupo_solicitado_c" value="<?php echo $row_cliente['cupo_solicitado_c']; ?>" style="width: 250px" onKeyUp="return ValNumero(this)" autocomplete='off'></td>
             </tr>
             <tr>
               <td id="dato_1"><select name="forma_pago_c">
                 <option value=""<?php if (!(strcmp("", $row_cliente['forma_pago_c']))) {echo "selected=\"selected\"";} ?>></option>
                 <option value="CHEQUE"<?php if (!(strcmp("CHEQUE", $row_cliente['forma_pago_c']))) {echo "selected=\"selected\"";} ?>>Cheque</option>
                 <option value="CONSIGNACION"<?php if (!(strcmp("CONSIGNACION", $row_cliente['forma_pago_c']))) {echo "selected=\"selected\"";} ?>>Consignacion</option>
                 <option value="TRANSFERENCIA"<?php if (!(strcmp("TRANSFERENCIA", $row_cliente['forma_pago_c']))) {echo "selected=\"selected\"";} ?>>Transferencia</option>
                 <option value="Transferencia bancaria directa"<?php if (!(strcmp("Transferencia bancaria directa", $row_cliente['forma_pago_c']))) {echo "selected=\"selected\"";} ?>>Transferencia bancaria directa</option>
                 <option value="PlacetoPay"<?php if (!(strcmp("PlacetoPay", $row_cliente['forma_pago_c']))) {echo "selected=\"selected\"";} ?>>PlacetoPay</option>
                 <option value="OTRA"<?php if (!(strcmp("OTRA", $row_cliente['forma_pago_c']))) {echo "selected=\"selected\"";} ?>>Otra</option>
               </select>
             Forma de Pago </td>
             <td id="dato_1">Otra Forma de Pago
               <input type="text" name="otro_pago_c" value="<?php echo $row_cliente['otro_pago_c']; ?>" size="30" autocomplete='off'></td>
               <td id="dato_1">&nbsp;</td>
               <td id="dato_1">&nbsp;</td>          
            </tr> 
  <tr>
    <td colspan="4" id="subtitulo2">APROBACION FINANCIERA</td>
  </tr>
  <tr>
    <td colspan="4" id="dato_1">Cupo Aprobado Plazo Aprobado</td>
  </tr>
  <tr>
    <td colspan="4" id="dato_1"onkeyUp="return ValNumero(this)"><input type="text" name="cupo_aprobado_c" value="<?php echo $row_cliente['cupo_aprobado_c']; ?>" size="30"onkeyUp="return ValNumero(this)" autocomplete='off'>
      <select name="plazo_aprobado_c" required>
        <option value="ANTICITADO"<?php if (!(strcmp("ANTICITADO", $row_cliente['plazo_aprobado_c']))) {echo "selected=\"selected\"";} ?>>Anticipado</option>
        <option value="PAGO DE CONTADO"<?php if (!(strcmp("PAGO DE CONTADO", $row_cliente['plazo_aprobado_c']))) {echo "selected=\"selected\"";} ?>>Pago de Contado</option>
        <option value="PAGO A 15 DIAS"<?php if (!(strcmp("PAGO A 15 DIAS", $row_cliente['plazo_aprobado_c']))) {echo "selected=\"selected\"";} ?>>Pago a 15 Dias

        </option>
        <option value="PAGO A 30 DIAS"<?php if (!(strcmp("PAGO A 30 DIAS", $row_cliente['plazo_aprobado_c']))) {echo "selected=\"selected\"";} ?>>Pago a 30 Dias

        </option>
        <option value="PAGO A 45 DIAS"<?php if (!(strcmp("PAGO A 45 DIAS", $row_cliente['plazo_aprobado_c']))) {echo "selected=\"selected\"";} ?>>Pago a 45 Dias

        </option>
        <option value="PAGO A 60 DIAS"<?php if (!(strcmp("PAGO A 60 DIAS", $row_cliente['plazo_aprobado_c']))) {echo "selected=\"selected\"";} ?>>Pago a 60 Dias

        </option>
        <option value="PAGO A 90 DIAS"<?php if (!(strcmp("PAGO A 90 DIAS", $row_cliente['plazo_aprobado_c']))) {echo "selected=\"selected\"";} ?>>Pago a 90 Dias

        </option>
        <option value="PAGO A 120 DIAS"<?php if (!(strcmp("PAGO A 120 DIAS", $row_cliente['plazo_aprobado_c']))) {echo "selected=\"selected\"";} ?>>Pago a 120 Dias

        </option>
      </select>
     </td>
    </tr>
    <tr>
      <td colspan="4" id="dato_1">Observaciones de la Aprobacion Financiera del Cliente </td>
    </tr>
    <tr>
      <td colspan="4" id="detalle_1"><textarea name="observ_aprob_finan_c" cols="100" rows="3" ><?php echo $row_cliente['observ_aprob_finan_c']; ?></textarea></td>
    </tr>
    <tr>
      <td colspan="4" id="subtitulo2">APROBACION COMERCIAL</td>
    </tr>
    <tr>
      <td id="dato_1"> Estado Comercial </td>
      <td colspan="3" id="dato_1"><select name="estado_comercial_c">
        <option value="PENDIENTE"<?php if (!(strcmp("PENDIENTE", $row_cliente['estado_comercial_c']))) {echo "selected=\"selected\"";} ?>>Pendiente</option>
        <option value="ACEPTADO"<?php if (!(strcmp("ACEPTADO", $row_cliente['estado_comercial_c']))) {echo "selected=\"selected\"";} ?>>Aceptado</option>
        <option value="RECHAZADO"<?php if (!(strcmp("RECHAZADO", $row_cliente['estado_comercial_c']))) {echo "selected=\"selected\"";} ?>>Rechazado</option>
      </select>
     </td>
    </tr>
    <tr>
      <td colspan="4" id="dato_1">Observaciones de Aprobaci&oacute;n Comercial </td>
    </tr>
    <tr>
      <td colspan="4" id="detalle_1"><textarea name="observ_asesor_com_c" cols="100" rows="2" ><?php echo $row_cliente['observ_asesor_com_c']; ?></textarea></td>
    </tr>
    <tr>
      <td colspan="4" id="subtitulo2">DOCUMENTOS ADJUNTOS </td>
    </tr>
    <tr>
      <td colspan="2"nowrap id="detalle_1">
        Camara de Comercio (Vigente)<br>
        <input name="camara_comercio_c" type="file" size="20" maxlength="60" class="botones_file">
        <input type="hidden" name="arc1" value="<?php echo $row_cliente['camara_comercio_c'] ?>"/>
        <a href="javascript:verFoto('archivosc/<?php echo $row_cliente['camara_comercio_c'] ?>','610','490')"><?php if($row_cliente['camara_comercio_c']!='') echo "Camara Comercio"; ?></a>
      </td>
      <td colspan="2" nowrap id="detalle_1">RUT<br>
        <input name="balance_general_c" type="file" size="20" maxlength="60" class="botones_file">
        <input type="hidden" name="arc2" value="<?php echo $row_cliente['balance_general_c'] ?>"/>
        <a href="javascript:verFoto('archivosc/<?php echo $row_cliente['balance_general_c'] ?>','610','490')"> 
          <?php if($row_cliente['balance_general_c']!='') echo "Rut"; ?>
        </a>
      </td>
    </tr>
    <tr>
      <td colspan="2" nowrap id="detalle_1">Proteccion de Datos<br>
        <input name="referencias_bancarias_c" type="file" size="20" maxlength="60" class="botones_file">
        <input type="hidden" name="arc5" value="<?php echo $row_cliente['referencias_bancarias_c'] ?>"/>
        <a href="javascript:verFoto('archivosc/<?php echo $row_cliente['referencias_bancarias_c'] ?>','610','490')">
          <?php if($row_cliente['referencias_bancarias_c']!='') echo "Proteccion de Datos"?>
        </a>
      </td>
    <!--<td colspan="2" nowrap id="detalle_1">Referencias Comerciales<br>
    <input name="referencias_comerciales_c" type="file" size="20" maxlength="60"class="botones_file">
    <input type="hidden" name="arc6"value="<?php echo $row_cliente['referencias_comerciales_c'] ?>"/>
    <a href="javascript:verFoto('archivosc/<?php echo $row_cliente['referencias_comerciales_c'] ?>','610','490')">
    <?php if($row_cliente['referencias_comerciales_c']!='') echo "Ref. Comerciales"?>
  </a></td>-->
</tr>
<tr>
  <td colspan="2"id="detalle_1">Estado P&amp;G <br>
    <input type="file" name="estado_pyg_c" size="20"class="botones_file">
    <input type="hidden" name="arc3"value="<?php echo $row_cliente['estado_pyg_c'] ?>"/>
    <a href="javascript:verFoto('archivosc/<?php echo $row_cliente['estado_pyg_c'] ?>','610','490')">
      <?php if($row_cliente['estado_pyg_c']!='') echo "Estado PYG"?>
    </a></td>
    <td colspan="2" id="detalle_1">Flujo Caja Proyectado<br>
      <input name="flujo_caja_proy_c" type="file" size="20" maxlength="60"class="botones_file">
      <input type="hidden" name="arc7"value="<?php echo $row_cliente['flujo_caja_proy_c'] ?>"/>
      <a href="javascript:verFoto('archivosc/<?php echo $row_cliente['flujo_caja_proy_c'] ?>','610','490')">
        <?php if($row_cliente['flujo_caja_proy_c']!='') echo "Flujo Caja"?>
      </a></td>
 </tr>
  <tr>
      <td colspan="2"id="detalle_1">Fotocopia Declaraci&oacute;n IVA<br>
        <input name="fotocopia_declar_iva_c" type="file" size="20" maxlength="60"class="botones_file">
        <input type="hidden" name="arc4"value="<?php echo $row_cliente['fotocopia_declar_iva_c'] ?>"/>
        <a href="javascript:verFoto('archivosc/<?php echo $row_cliente['fotocopia_declar_iva_c'] ?>','610','490')">
          <?php if($row_cliente['fotocopia_declar_iva_c']!='') echo "Declaracion"?>
        </a>
      </td>
      <td colspan="2" id="detalle_1">Otros<br>
          <input name="otros_doc_c" type="file" size="20" maxlength="60"class="botones_file">
          <input type="hidden" name="arc8"value="<?php echo $row_cliente['otros_doc_c'] ?>"/>
          <a href="javascript:verFoto('archivosc/<?php echo $row_cliente['otros_doc_c'] ?>','610','490')">
            <?php if($row_cliente['otros_doc_c']!='') echo "Otros"?>
          </a>
       </td>
  </tr> 
   <tr>
      <td colspan="4" id="dato_1">Observaciones de Documentos Adjuntos </td>
   </tr>
  <tr>
    <td colspan="4" id="detalle_1"><textarea name="observ_doc_c" cols="100" rows="2" <?php if($row_cliente['observ_doc_c']!=''){?> readonly  <?php } ?>><?php echo $row_cliente['observ_doc_c']; ?></textarea></td>
  </tr>
  <tr>
    <td colspan="4" id="subtitulo2">INFORMACION FINAL DEL FORMATO PERFIL DE CLIENTES </td>
  </tr>
  <tr>
     <td id="dato_1">ESTADO DEL CLIENTE
            <select name="estado_c" id="estado_c">
              <option value="ACTIVO"<?php if (!(strcmp("ACTIVO", $row_cliente['estado_c']))) {echo "selected=\"selected\"";} ?>>ACTIVO</option>
              <option value="PENDIENTE"<?php if (!(strcmp("PENDIENTE", $row_cliente['estado_c']))) {echo "selected=\"selected\"";} ?>>PENDIENTE</option>
              <option value="INACTIVO"<?php if (!(strcmp("INACTIVO", $row_cliente['estado_c']))) {echo "selected=\"selected\"";} ?>>INACTIVO</option>
            </select>
     </td>
     <td colspan="3" id="dato_1">Registrado Por: <?php echo $row_cliente['registrado_c']; ?> 
        <input name="registrado_c" type="hidden" value="<?php echo $row_cliente['registrado_c']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      Impuesto Adicional? <input type="checkbox" name="impuesto" value="1" <?php if (!(strcmp($row_cliente['impuesto'],1))) {echo "checked=\"checked\"";} ?>> &nbsp;&nbsp;Adjunto PDF:  <input name="pdf_impuesto" type="file" size="20" maxlength="60" class="botones_file">
              <input type="hidden" name="arc9" value="<?php echo $row_cliente['pdf_impuesto'] ?>"/>
              <a href="javascript:verFoto('archivosc/impuesto/<?php echo $row_cliente['pdf_impuesto'] ?>','610','490')"> 
                <?php if($row_cliente['pdf_impuesto']!='') echo "Impuesto"; ?>
              </a>
    </td> 
  </tr>
  <tr>
    <td id="dato_1">Revisada por <?php echo $row_cliente['revisado_c']; ?>
        <input name="revisado_c" type="hidden" value="<?php  $usuar=strtoupper( $row_usuario['nombre_usuario']);echo $usuar; ?>"></td>
            <td id="dato_1">Fecha Revision/Modifico </td>
                <td id="dato_1"><?php echo $row_cliente['fecha_revision_c']; ?>
             <input name="fecha_revision_c" type="hidden" value="<?php echo date("Y/m/d"); ?>"></td>
        <td id="dato_1">&nbsp;</td>
   </tr>
  <tr>
      <td colspan="4" id="dato_2">
        <input type="submit" class="botonGeneral" value="Actualizar">
      </td>
  </tr>

 <input type="hidden" name="MM_update" value="form1">
     <input type="hidden" name="id_c" id="id_c" value="<?php echo $row_cliente['id_c']; ?>">    
  </form>
 


         <!--aqui empieza la opcion multiple de agregar-->                          
 
           <td colspan="4">
             <hr>
             <b> INFORMACION DE DESPACHO BODEGAS</b>
            </td>  
           <tr> 
              <form id="formItems" name="formItems" action="guardar.php" method="post">
                <tr id="items" ><!--  style="display: none;" --> 
                  <td colspan="4" id="dato1">
                    <input type="hidden" name="id_d" id="id_d" value="<?php echo $row_cliente['id_c']; ?>" class='campostextMini' >
                    <input type="hidden" name="nit" id="nit" value="<?php echo $row_cliente['nit_c']; ?>" class='campostextMini' > 
                    <input type="text" required="required" placeholder="nombre responsable" id="nombre_responsable" name="nombre_responsable" value="<?php echo $row_insumos['nombre_responsable']; ?>" class='campostext'>
                    <input type="text" required="required" placeholder="direccion" id="direccion" name="direccion" value="<?php echo $row_insumos['direccion']; ?>" class='selectsMMedio'  >
                    <input type="text" required="required" placeholder="indicativo" id="indicativo" name="indicativo" value="<?php echo $row_insumos['indicativo']; ?>" class='campostextMini'  >
                    <input type="text" required="required" placeholder="telefono" id="telefono" name="telefono" value="<?php echo $row_insumos['telefono']; ?>" class='campostextMini'  >
                    <input type="text" required="required" placeholder="extension" id="extension" name="extension" value="<?php echo $row_insumos['extension']; ?>" class='campostextMini'  >
                    <input type="text" required="required" placeholder="ciudad" id="ciudad" name="ciudad" value="<?php echo $row_insumos['ciudad']; ?>" class='campostextMini'>
                    <button id="btnEnviarItems" name="btnEnviarItems" type="button" class="botonMini" autofocus="" >ADD BODEGA</button><br> 
                    <em style="display: none;  align-items: center; justify-content: center;color: red; " id="AlertItem" ></em> 
                  </td> 
                </tr>  
                <input type="hidden" name="formItems" value="formItems">
              </form>  
          </tr>  
      <tr>
       <td colspan="4">
         <br>
         <!-- grid -->   
         <table id="example" class="table table-striped" style="width:100%" >
           <thead>
             <tr> 
               <th nowrap="nowrap">NOMBRE RESPONSABLE</th>
               <th nowrap="nowrap">DIRECCION</th>
               <th nowrap="nowrap">INDICATIVO</th>
               <th nowrap="nowrap" style="text-align: center;" >TELEFONO</th>
               <th nowrap="nowrap">EXTENSION</th>
               <th nowrap="nowrap">CIUDAD</th> 
               <th nowrap="nowrap">DELETE</th>
             </tr>
           </thead>
           <td colspan="7"><em id="AlertUpdate" ></em>&nbsp;</td>
           <tbody id="DataResult"> 

           </tbody>
         </table>  
       </td>
     </tr>

   </td>
 </tr>  

 </div>
 </table>

 </body>
   </html>
        <script>
            $(document).on('ready',function(){ 
              $('#btn-ingresar').on('click',function(){  
                var idborrar = '';
                var url='';  
                $("input[name=borrar]").each(function (index) {
                 if($(this).is(':checked')){
                  idborrar = $(this).val();
                  var url = "delete2.php?iddir_bodega="+idborrar;
                  elim=confirm("¿Quieres Eliminar los ID ? "+idborrar);
                  recorrerListacompras(url);
                }
              });


              });
            });
 
            function recorrerListacompras(url){

              if(url)
              {
                $.ajax({                        
                 type: "POST",                 
                 url: url,                     
                 data: $("#form1").serialize(), 
                 success: function(data)             
                 {
                   location.reload();               
                   $('#resp').text("Eliminado correctamente");
                 }
               }); 
              } 
            }          
        </script> 
      <!--aqui termina la opcion multiple -->  
        <script type="text/javascript">
           $(document).ready(function(){
            consultasBodegas($("#id_c").val());//despliega los items
          });

            $( "#btnEnviarItems" ).on( "click", function() {
         
            if($("#responsable").val()==''){
              swal("Error", "Debe agregar un valor al campo responsable! :)", "error"); 
              return false;
            } 
            else if($("#direccion").val()==''){
              swal("Error", "Debe agregar un valor al campo direccion! :)", "error"); 
              return false;
            }
            else if($("#telefono").val()==''){
              swal("Error", "Debe agregar un valor al campo telefono! :)", "error"); 
              return false;
            }
            else if($("#ciudad").val()==''){
              swal("Error", "Debe agregar un valor al campo ciudad! :)", "error"); 
              return false;
            }else{ 

              guardarConAlertBodegas();
            }
     
          });
          
 
           function Updates(vid,valores){

             ids='id';//coloque la columna del id a actualizar
             valorid = ''+vid; 
             tabla='tbl_destinatarios';
             url='view_index.php?c=cgeneral&a=Actualizar'; //la envio en campo proceso
         
             actualizapaso(ids,valorid,valores,tabla,url);   

           }

        </script>
          <?php
          mysql_free_result($usuario);

          mysql_free_result($cliente);

          mysql_free_result($n_pais);

          mysql_free_result($n_ciudad);

          mysql_free_result($ver_bodegas);

         //mysql_free_result($destinatario);

          mysql_close($conexion1);
          ?>
