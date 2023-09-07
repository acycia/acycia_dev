<?php require_once('Connections/conexion1.php'); ?>
<?php
mysql_select_db($database_conexion1, $conexion1);
/*----------VARIABLES------------*/
/*----------COTIZACION BOLSAS--------*/
$nit=$_GET['nit'];
$N_ancho=$_GET['N_ancho'];
$N_alto=$_GET['N_alto'];
$B_fuelle=$_GET['B_fuelle'];
$N_calibre=$_GET['N_calibre'];
$B_troquel=$_GET['B_troquel'];
$B_bolsillo=$_GET['B_bolsillo'];
$N_tamano_bolsillo=$_GET['N_tamano_bolsillo'];
$N_solapa=$_GET['N_solapa'];
$Str_moneda=$_GET['Str_moneda'];
$N_precio=$_GET['N_precio'];			                                                                    
$Str_unidad_vta=$_GET['Str_unidad_vta'];
$Str_incoterms=$_GET['Str_incoterms']; 
$Str_tipo_coextrusion=$_GET['Str_tipo_coextrusion'];
$Str_capa_ext_coext=$_GET['Str_capa_ext_coext'];
$Str_capa_inter_coext=$_GET['Str_capa_inter_coext'];
$N_cant_impresion=$_GET['N_cant_impresion'];
$B_impresion=$_GET['B_impresion'];
$N_colores_impresion=$_GET['N_colores_impresion'];
$B_cyreles=$_GET['B_cyreles'];
$B_sellado_seguridad=$_GET['B_sellado_seguridad'];
$B_sellado_permanente=$_GET['B_sellado_permanente'];
$B_sellado_resellable=$_GET['B_sellado_resellable'];
$B_sellado_hotm=$_GET['B_sellado_hotm'];
$B_sellado_lateral=$_GET['B_sellado_lateral'];
$B_sellado_hilo=$_GET['B_sellado_hilo'];
$B_sellado_plano=$_GET['B_sellado_plano'];
$B_sellado_hilop=$_GET['B_sellado_hilop'];
$nota_b=$_GET['nota_b'];
$n_cotizacion=$_GET['n_cotizacion']; 
/*----------COTIZACION LAMINAS--------*/
$nit_l=$_GET['nit_l'];
$N_ancho_l=$_GET['N_ancho_l'];
$N_repeticion_l=$_GET['N_repeticion_l'];
$N_calibre_l=$_GET['N_calibre_l'];
$N_cantidad_metros_r_l=$_GET['N_cantidad_metros_r_l'];
$Str_incoterms_l=$_GET['Str_incoterms_l'];
$B_impresion_l=$_GET['B_impresion_l'];
$N_colores_impresion_l=$_GET['N_colores_impresion_l'];
$B_cyreles_l=$_GET['B_cyreles_l'];
$N_cantidad_l=$_GET['N_cantidad_l'];		                                                                    
$N_peso_max=$_GET['N_peso_max'];
$N_diametro_max_l=$_GET['N_diametro_max_l']; 
$Str_moneda_l=$_GET['Str_moneda_l'];
$N_precio_k=$_GET['N_precio_k'];
$nota_l=$_GET['nota_l'];
$n_cotizacion_l=$_GET['n_cotizacion_l'];                                                               
/*----------------------------------------*/
/*--------------ACCIONES------------------*/
/*----------------------------------------*/
/*------------COTIZACIONES---------------*/
/*FUNCION PARA LIMPIAR VARIABLES PARA ESCAPAR DE ALGUNOS DATOS PARA PASARLO A MYSQL*/
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
/*------------------------------------------------------------------*/
/*--------------------------GESTION COMERCIAL-----------------------*/
/*INSERT BOLSAS*/
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
$insertSQLB1 = sprintf("INSERT INTO Tbl_cotiza_bolsa (n_cotizacion, nit, ancho, alto, fuelle, calibre, troquel, bolsillo, tamano_bolsillo, solapa, moneda, precio,  unidad_vta, incoterms, tipo_coextrusion, capa_ext_coext, capa_inter_coext, cant_impresion, impresion, colores_impresion, cyreles, sellado_seguridad, sellado_permanente, sellado_resellable, sellado_hotm, sellado_lateral, sellado_plano, sellado_hilo, sellado_hilop) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",                      
GetSQLValueString($_POST['n_cotizacion'], "int"),
GetSQLValueString($_POST['nit'], "text"),
GetSQLValueString($_POST['N_ancho'], "int"),
GetSQLValueString($_POST['N_alto'], "int"),
GetSQLValueString($_POST['B_fuelle'], "int"),
GetSQLValueString($_POST['N_calibre'], "int"),
GetSQLValueString($_POST['B_troquel'], "int"),
GetSQLValueString($_POST['B_bolsillo'], "int"),
GetSQLValueString($_POST['N_tamano_bolsillo'], "int"),
GetSQLValueString($_POST['N_solapa'], "int"),
GetSQLValueString($_POST['Str_moneda'], "text"),
GetSQLValueString($_POST['N_precio'], "int"),			                                                                    
GetSQLValueString($_POST['Str_unidad_vta'], "text"),
GetSQLValueString($_POST['Str_incoterms'], "text"), 
GetSQLValueString($_POST['Str_tipo_coextrusion'], "text"),
GetSQLValueString($_POST['Str_capa_ext_coext'], "text"),
GetSQLValueString($_POST['Str_capa_inter_coext'], "text"),
GetSQLValueString($_POST['N_cant_impresion'], "int"),
GetSQLValueString($_POST['B_impresion'], "int"),
GetSQLValueString($_POST['N_colores_impresion'], "int"),
GetSQLValueString($_POST['B_cyreles'], "int"),
GetSQLValueString($_POST['B_sellado_seguridad'], "int"),
GetSQLValueString($_POST['B_sellado_permanente'], "int"),
GetSQLValueString($_POST['B_sellado_resellable'], "int"),
GetSQLValueString($_POST['B_sellado_hotm'], "int"),
GetSQLValueString($_POST['B_sellado_lateral'], "int"),
GetSQLValueString($_POST['B_sellado_hilo'], "int"),
GetSQLValueString($_POST['B_sellado_plano'], "int"),
GetSQLValueString($_POST['B_sellado_hilop'], "int"));
$ResultB1 = mysql_query($insertSQLB1, $conexion1) or die(mysql_error());
/*SEGUNDO INSERT A LA SENDA TABLA QUE CONTIENE LOS TEXTOS*/
$insertSQLB2 = sprintf("INSERT INTO Tbl_cotiza_bolsa_obs (nit, n_cotizacion, texto) VALUES (%s, %s, %s)",
GetSQLValueString($_POST['nit'], "text"),
GetSQLValueString($_POST['n_cotizacion'], "int"),
GetSQLValueString($_POST['nota_b'], "text"));
$ResultB2 = mysql_query($insertSQLB2, $conexion1) or die(mysql_error());  /*ESTE CODIGO ES PARA ENVIAR POR GET LAS VARIABLES NI Y N_COTIZACION Y PODER HACER EL SELECT EN cotizacion_bolsa_vista.php*/
  $insertGoTo = "cotizacion_bolsa_vista.php?nit=" . $_POST['nit'] . "&n_cotizacion=" . $_POST['n_cotizacion'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo)); 

}
/*INSERT LAMINAS*/
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
$insertSQLL1 = sprintf("INSERT INTO Tbl_cotiza_laminas (n_cotizacion, nit, ancho, repeticion, calibre, cantidad_metros_r, incoterms, impresion, colores_impresion, cyreles, cantidad, peso_max, diametro_max, moneda, precio_k) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
 					   GetSQLValueString($_POST['n_cotizacion'], "int"),                     
					   GetSQLValueString($_POST['nit'], "text"),
                       GetSQLValueString($_POST['N_ancho_l'], "int"),
                       GetSQLValueString($_POST['N_repeticion_l'], "int"),
                       GetSQLValueString($_POST['N_calibre_l'], "int"),
                       GetSQLValueString($_POST['N_cantidad_metros_r_l'], "int"),
                       GetSQLValueString($_POST['Str_incoterms'], "text"),
                       GetSQLValueString($_POST['B_impresion'], "int"),
					   GetSQLValueString($_POST['N_colores_impresion'], "int"),
					   GetSQLValueString($_POST['B_cyreles_l'], "int"),
                       GetSQLValueString($_POST['N_cantidad_l'], "int"),		                                                                    
                       GetSQLValueString($_POST['N_peso_max'], "int"),
					   GetSQLValueString($_POST['N_diametro_max_l'], "int"), 
                       GetSQLValueString($_POST['Str_moneda_l'], "text"),
                       GetSQLValueString($_POST['N_precio_k'], "int"));
mysql_select_db($database_conexion1, $conexion1);
$ResultL1 = mysql_query($insertSQL1, $conexion1) or die(mysql_error());
/*SEGUNDO INSERT A LA SENDA TABLA QUE CONTIENE LOS TEXTOS*/
$insertSQLL2 = sprintf("INSERT INTO Tbl_cotiza_lamina_obs (nit, n_cotizacion, texto) VALUES (%s, %s, %s)",
GetSQLValueString($_POST['nit'], "text"),
GetSQLValueString($_POST['n_cotizacion'], "int"),
GetSQLValueString($_POST['nota_l'], "text"));
mysql_select_db($database_conexion1, $conexion1);
$ResultL2 = mysql_query($insertSQLL2, $conexion1) or die(mysql_error());  /*ESTE CODIGO ES PARA ENVIAR POR GET LAS VARIABLES NI Y N_COTIZACION Y PODER HACER EL SELECT EN cotizacion_bolsa_vista.php*/
  $insertGoTo = "cotizacion_bolsa_vista.php?nit=" . $_POST['nit'] . "&n_cotizacion=" . $_POST['n_cotizacion'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo)); 
}

?>