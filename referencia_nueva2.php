<?php require_once('Connections/conexion1.php'); ?>
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
<?php
mysql_select_db($database_conexion1, $conexion1);
//ID GENERAL SWITH
$id=$_POST['id'];
//BOLSAS
$medida=$_POST['Str_unidad_vta']; 
$n_cn=$_POST['n_cn'];
$cod_ref=$_POST['cod_ref'];
$Str_nit=$_POST['Str_nit'];
$tipo_bolsa=$_POST['tipo_bolsa'];
$Tiposolapa=$_POST['Tiposolapa'];
$presen=$_POST['presen'];
$trata=$_POST['trata'];
$registro=$row_usuario['nombre_usuario'];
$codigo=$row_usuario['codigo_usuario'];
$fecha= date("Y-m-d");
$hora= date("g:i a");
//PACKING
$n_cn2=$_POST['n_cn2'];
$cod_ref2=$_POST['cod_ref2'];
$Str_nit2=$_POST['Str_nit2'];
$presen2=$_POST['presen2'];
$trata2=$_POST['trata2'];
//LAMINAS
$n_cn3=$_POST['n_cn3'];
$cod_ref3=$_POST['cod_ref3'];
$Str_nit3=$_POST['Str_nit3'];
$presen3=$_POST['presen3'];
$trata3=$_POST['trata3'];

//INVENTARIO
$cods=$cod_ref.$cod_ref2.$cod_ref3; 
$costoUnd=$_POST['valor_ref'].$_POST['valor_ref2'].$_POST['valor_ref3'];
$codvers=$cods.'-00';
$sqlinv="INSERT INTO TblInventarioListado (Fecha, Cod_ref, Codigo, SaldoInicial, Entrada, CostoUnd, Acep, Tipo, Responsable) VALUES ('$fecha', '$cods', '$codvers', '0', '0', '$costoUnd', '0', '1', '$registro')";
$resultinv=mysql_query($sqlinv); 
//TBLCOSTOREF
/*$sqlinv2="INSERT INTO TblCostoRef (id_ref_cref, cod_cref ,codigo_cref, descripcion_cref, unidad_cref, cliente_cref, costo_und_cref,  responsable_cref, fecha_cref) VALUES (%s, '$cods', '$codvers', '$tipo_bolsa', '$medida', '$Str_nit', '$costoUnd', '$registro', '$fecha')";
$resultinv2=mysql_query($sqlinv2);*/ 
 

//BOLSAS INSERTAR EL TABLA DE REFERENCIAS
if($id == '1') {//EL NUMERO UNO ES ADD DE BOLSA A LA TABLA DE REFERENCIAS
$sql="SELECT * FROM Tbl_cotiza_bolsa WHERE Tbl_cotiza_bolsa.N_cotizacion='$n_cn' and Tbl_cotiza_bolsa.N_referencia_c='$cod_ref' and Tbl_cotiza_bolsa.Str_nit='$Str_nit'";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result)) {
//FUNCION PARA GUARDAR EL PESO MILLAR EN LA REFERENCIA
/*0-N_cotizacion	1-N_referencia_c	2-Str_nit	3-N_ancho	4-N_alto	5-B_fuelle	6-N_calibre	7-B_troquel	
8-B_precorte 9-B_bolsillo	10-N_tamano_bolsillo	11-N_solapa	12-Str_moneda	13-N_precio	14-Str_unidad_vta	
15-Str_plazo 16-Str_incoterms	17Str_tipo_coextrusion	18-Str_capa_ext_coext	19-Str_capa_inter_coext	
20-N_cant_impresion	21-B_impresion	22-N_colores_impresion	23-B_cyreles	24-B_sellado_seguridad	25-B_sellado_permanente	
26-B_sellado_resellable	27-B_sellado_hotm	28-Str_sellado_lateral	29-B_fondo	30-B_codigo_b	31-B_numeracion	
32-fecha_creacion	33-Str_usuario	34-N_comision	35-B_estado	 36-B_generica*/
$ancho=$row[3];	
$alto=$row[4];
$fuelle=$row[5];
$calibre=$row[6];
$solapaT=$row[11];
$solapa=($solapaT/$Tiposolapa);
$constan= $tipo_bolsa=="COMPOSTABLE" ? 0.00665 : 0.00467;
 
$subm=($ancho * ($alto+$fuelle+$solapa)*$calibre*$constan);//en js es var subm=ancho*(larg+fuelle+dsolapa)*calibre*cons;
$var5=($subm*100)/100;
$ps=$var5;
$psm = number_format($ps,2);	
//DEFINO EL CAMPO ADHESIVO
if($row[24]=='1'){$seg="CINTA DE SEGURIDAD";}

if($row[25]=='1'){$per="CINTA PERMANENTE";}

if($row[26]=='1'){$res="CINTA RESELLABLE";}

if($row[27]=='1'){$hot="HOT MELT";}
$adhesivo=$seg.$per.$res.$hot;

//INSERTA REFERENCIA
//ESTAS SON LAS COLUMNAS DE LA TABLA BOLSAS
/*	REFERENCIA  */
 
$sql1="INSERT INTO Tbl_referencia (cod_ref, version_ref, n_egp_ref, n_cotiz_ref, tipo_bolsa_ref, material_ref, Str_presentacion, Str_tratamiento, ancho_ref, N_repeticion_l, N_diametro_max_l, N_peso_max_l, 
N_cantidad_metros_r_l, N_embobinado_l, Str_referencia_m, Str_linc_m, largo_ref, solapa_ref, b_solapa_caract_ref, bolsillo_guia_ref, calibre_ref, peso_millar_ref, Str_boca_entr_p,Str_entrada_p,Str_lamina1_p,Str_lamina2_p, B_troquel, 
B_precorte, N_fuelle, B_fondo, impresion_ref, num_pos_ref, cod_form_ref, adhesivo_ref, estado_ref, registro1_ref, fecha_registro1_ref, registro2_ref, fecha_registro2_ref, B_generica, valor_impuesto
) VALUES ('$row[1]','00','$row[1]','$row[0]','$tipo_bolsa','$row[17]','$presen','$trata','$row[3]','','','','','','','','$row[4]','$row[11]','$Tiposolapa','$row[10]','$row[6]','$psm','','','','','$row[7]','$row[8]','$row[5]','$row[29]','$row[22]','$row[31]','$row[30]','$adhesivo','1','$registro','$fecha','','','','$row[41]')";
 
$result1=mysql_query($sql1); 
//INSERTA EGP
//ESTAS SON LAS COLUMNAS DE LA TABLA EGP
$sql2="INSERT INTO Tbl_egp (n_egp, responsable_egp, codigo_usuario, fecha_egp, hora_egp, estado_egp, ancho_egp, largo_egp, solapa_egp, largo_cang_egp, calibre_egp, tipo_ext_egp, pigm_ext_egp, pigm_int_epg, adhesivo_egp, tipo_bolsa_egp, cantidad_egp, tipo_sello_egp, 
color1_egp, pantone1_egp, ubicacion1_egp, color2_egp, pantone2_egp, ubicacion2_egp, color3_egp, pantone3_egp, ubicacion3_egp, color4_egp, pantone4_egp, ubicacion4_egp, color5_egp, pantone5_egp, ubicacion5_egp, color6_egp, pantone6_egp, ubicacion6_egp, color7_egp, pantone7_egp, ubicacion7_egp, 
color8_egp, pantone8_egp, ubicacion8_egp, tipo_solapatr_egp, tipo_cinta_egp, tipo_principal_egp, tipo_inferior_egp, cb_solapatr_egp, cb_cinta_egp, cb_principal_egp, cb_inferior_egp, comienza_egp, fecha_cad_egp, arte_sum_egp, ent_logo_egp, orient_arte_egp, archivo1, archivo2, archivo3, 
disenador_egp, telef_disenador_egp, unids_paq_egp, unids_caja_egp, marca_cajas_egp, lugar_entrega_egp, observacion5_egp, responsable_modificacion, fecha_modificacion, hora_modificacion, vendedor
) VALUES ('$row[1]','$registro','$codigo','$fecha','$hora','1','$row[3]','$row[4]','$row[11]','$row[10]','$row[6]','$row[17]','$row[18]','$row[19]','$adhesivo','$tipo_bolsa','$row[22]','$row[28]','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','$registro','$fecha','$hora','$row[33]')";
$result2=mysql_query($sql2);
}
}
//RECHAZA LA REFERENCIA
if($id == '2'){//EL NUMERO 2 ES PARA PASAR LA REFERENCIA EN COTIZA A ESTADO RECHAZADO
$sql="UPDATE Tbl_cotiza_bolsa SET B_estado = '2' WHERE Tbl_cotiza_bolsa.N_cotizacion='$n_cn'  and Tbl_cotiza_bolsa.N_referencia_c='$cod_ref' and Tbl_cotiza_bolsa.Str_nit='$Str_nit'";
$result=mysql_query($sql);
}
header("Location:referencia_nueva1.php");

//PACKING INSERTAR EL TABLA DE REFERENCIAS
if($id == '3') {//EL NUMERO 3 ES ADD DE PACKING A LA TABLA DE REFERENCIAS
$sql="SELECT * FROM Tbl_cotiza_packing WHERE Tbl_cotiza_packing.N_cotizacion='$n_cn2' and Tbl_cotiza_packing.N_referencia_c='$cod_ref2' and Tbl_cotiza_packing.Str_nit='$Str_nit2'";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result)) {
//FUNCION PARA GUARDAR EL PESO MILLAR EN LA REFERENCIA	
$alto=$row[4];
$ancho=$row[3];
/*$solapaT=$row[10];
$solapa=($solapaT/2);
$fuelle=$row[5];*/
$calibre=$row[6];
$constan=0.00467;

$subm=(($ancho * $alto)*$calibre*$constan);
$var5=($subm*100)/100;
$ps=$var5;
$psm= number_format($ps,2);
// INSERTA REFERENCIA
//PACKING
/*0N_cotizacion	1N_referencia_c	2Str_nit	3N_ancho	4N_alto	5N_cantidad	6N_calibre	7Str_incoterms	8Str_moneda	
9N_precio_vnta	10Str_boca_entrada	11B_impresion	12N_colores_impresion	13B_cyreles	14Str_ubica_entrada	
15Str_lam1	16Str_lam2	17Str_unidad_vta	18Str_plazo	19fecha_creacion	20Str_usuario	21N_comision	22B_estado	23B_generica*/
$sql1="INSERT INTO Tbl_referencia (cod_ref, version_ref, n_egp_ref, n_cotiz_ref, tipo_bolsa_ref, material_ref, Str_presentacion, Str_tratamiento, ancho_ref, N_repeticion_l, N_diametro_max_l, N_peso_max_l, N_cantidad_metros_r_l, N_embobinado_l, Str_referencia_m, Str_linc_m, 
largo_ref, solapa_ref, bolsillo_guia_ref, calibre_ref, peso_millar_ref, Str_boca_entr_p,Str_entrada_p,Str_lamina1_p,Str_lamina2_p,B_troquel,N_fuelle,B_fondo, impresion_ref, num_pos_ref, cod_form_ref, adhesivo_ref, estado_ref, registro1_ref, fecha_registro1_ref,registro2_ref,fecha_registro2_ref,B_generica, valor_impuesto
) VALUES ('$row[1]','00','$row[1]','$row[0]','PACKING LIST','PACKING LIST','$presen2','$trata2','$row[3]','','','','','','','','$row[4]','','','$row[6]','$psm','$row[10]','$row[14]','$row[15]','$row[16]','0','0','0','$row[12]','0','0','N.A','1','$registro','$fecha','','','0','$row[27]')";
$result1=mysql_query($sql1);
//INSERTA EGP
//ESTAS SON LAS COLUMNAS DE LA TABLA EGP
$sql2="INSERT INTO Tbl_egp (n_egp, responsable_egp, codigo_usuario, fecha_egp, hora_egp, estado_egp, ancho_egp, largo_egp, solapa_egp, largo_cang_egp, calibre_egp, tipo_ext_egp, pigm_ext_egp, pigm_int_epg, adhesivo_egp, 
tipo_bolsa_egp, cantidad_egp, tipo_sello_egp, color1_egp, pantone1_egp, ubicacion1_egp, color2_egp, pantone2_egp, ubicacion2_egp, color3_egp, pantone3_egp, ubicacion3_egp, color4_egp, pantone4_egp, ubicacion4_egp, color5_egp,
 pantone5_egp, ubicacion5_egp, color6_egp, pantone6_egp, ubicacion6_egp, color7_egp, pantone7_egp, ubicacion7_egp, color8_egp, pantone8_egp, ubicacion8_egp, tipo_solapatr_egp, tipo_cinta_egp, tipo_principal_egp, tipo_inferior_egp,
  cb_solapatr_egp, cb_cinta_egp, cb_principal_egp, cb_inferior_egp, comienza_egp, fecha_cad_egp, arte_sum_egp, ent_logo_egp, orient_arte_egp, archivo1, archivo2, archivo3, disenador_egp, telef_disenador_egp, unids_paq_egp, unids_caja_egp, marca_cajas_egp, lugar_entrega_egp, observacion5_egp, responsable_modificacion, fecha_modificacion, hora_modificacion, vendedor
) VALUES ('$row[1]','$registro','$codigo','$fecha','$hora','1','$row[3]','$row[4]','','','$row[6]','','','','N.A','PACKING LIST','$row[5]','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','$registro','$fecha','$hora','$row[20]')";
$result2=mysql_query($sql2);
}
}
//RECHAZA LA REFERENCIA
if($id == '4'){//EL NUMERO 4 ES DEL PARA PASAR LA REFERENCIA EN COTIZA A ESTADO RECHAZADO
$sql="UPDATE Tbl_cotiza_packing SET B_estado = '2' WHERE Tbl_cotiza_packing.N_cotizacion='$n_cn2' and Tbl_cotiza_packing.N_referencia_c='$cod_ref2' and Tbl_cotiza_packing.Str_nit='$Str_nit2'";
$result=mysql_query($sql);
}
header("Location:referencia_nueva1.php");

//LAMINAS INSERTAR EL TABLA DE REFERENCIAS
if($id == '5') {//EL NUMERO 5 ES ADD DE LAMINAS A LA TABLA DE REFERENCIAS
$sql="SELECT * FROM Tbl_cotiza_laminas WHERE Tbl_cotiza_laminas.N_cotizacion='$n_cn3' and Tbl_cotiza_laminas.N_referencia_c='$cod_ref3' and Tbl_cotiza_laminas.Str_nit='$Str_nit3'";
$result=mysql_query($sql);
//FUNCION PARA GUARDAR EL PESO/ML EN LA REFERENCIA	
while ($row=mysql_fetch_array($result)) {
$var1=$row[3];
$var2=$row[5];
$var3=($var1);
$var4=($var2);
$peso=(24*($var4));	
$ps=($peso*100)/100;
$psm= number_format($ps,2);	
// INSERTA REFERENCIA
//ESTAS SON LAS COLUMNAS DE LA TABLA LAMINAS
/*0N_cotizacion	1N_referencia_c	2Str_nit	3N_ancho	4N_repeticion	5N_calibre	6Str_tipo_coextrusion	7Str_capa_ext_coext	8Str_capa_inter_coext	9N_embobinado	10Str_plazo	11N_cantidad_metros_r	12Str_incoterms	13B_impresion	14N_colores_impresion	15B_cyreles	16N_cantidad	17N_peso_max	18N_diametro_max	19Str_moneda	20N_precio_k  21Str_unidad_vta	 22fecha_creacion	23Str_usuario	24N_comision	25B_estado	26B_generica*/

$sql1="INSERT INTO Tbl_referencia (cod_ref, version_ref, n_egp_ref, n_cotiz_ref, tipo_bolsa_ref, material_ref,Str_presentacion, Str_tratamiento, ancho_ref, N_repeticion_l,	N_diametro_max_l, N_peso_max_l, N_cantidad_metros_r_l, N_embobinado_l, Str_referencia_m, Str_linc_m, largo_ref, solapa_ref, bolsillo_guia_ref, calibre_ref, peso_millar_ref, Str_boca_entr_p,Str_entrada_p,Str_lamina1_p,Str_lamina2_p,B_troquel,N_fuelle,B_fondo, impresion_ref, num_pos_ref, cod_form_ref, adhesivo_ref, estado_ref, registro1_ref, fecha_registro1_ref,registro2_ref,fecha_registro2_ref,B_generica, valor_impuesto
) VALUES ('$row[1]','00','$row[1]','$row[0]','LAMINA','$row[6]','$presen3','$trata3','$row[3]','$row[4]','$row[18]','$row[17]','$row[11]','$row[9]','0','0','0','0','0','$row[5]','$psm','0','0','0','0','0','0','0','$row[14]','0','0','N.A','1','$registro','$fecha','','','0','$row[30]')";

$result1=mysql_query($sql1);
//INSERTA EGP
//ESTAS SON LAS COLUMNAS DE LA TABLA EGP
$sql2="INSERT INTO Tbl_egp (n_egp, responsable_egp, codigo_usuario, fecha_egp, hora_egp, estado_egp, ancho_egp, largo_egp, solapa_egp, largo_cang_egp, calibre_egp, tipo_ext_egp, pigm_ext_egp, pigm_int_epg, adhesivo_egp, tipo_bolsa_egp, cantidad_egp, tipo_sello_egp, color1_egp, pantone1_egp, ubicacion1_egp, color2_egp, pantone2_egp, ubicacion2_egp, color3_egp, pantone3_egp, ubicacion3_egp, color4_egp, pantone4_egp, ubicacion4_egp, color5_egp, pantone5_egp, ubicacion5_egp, color6_egp, pantone6_egp, ubicacion6_egp, color7_egp, pantone7_egp, ubicacion7_egp, color8_egp, pantone8_egp, 
ubicacion8_egp, tipo_solapatr_egp, tipo_cinta_egp, tipo_principal_egp, tipo_inferior_egp, cb_solapatr_egp, cb_cinta_egp, cb_principal_egp, cb_inferior_egp, comienza_egp, fecha_cad_egp, arte_sum_egp, ent_logo_egp, orient_arte_egp, archivo1, archivo2, archivo3, disenador_egp, telef_disenador_egp, unids_paq_egp, unids_caja_egp, marca_cajas_egp, lugar_entrega_egp, observacion5_egp, responsable_modificacion, fecha_modificacion, hora_modificacion, vendedor
) VALUES ('$row[1]','$registro','$codigo','$fecha','$hora','1','$row[3]','','','','$row[5]','$row[6]','$row[7]','$row[8]','N.A','LAMINA','$row[11]','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','$registro','$fecha','$hora','$row[23]')";
$result2=mysql_query($sql2);
}
}
//RECHAZA LA REFERENCIA
if($id == '6'){//EL NUMERO 6 ES DEL PARA PASAR LA REFERENCIA EN COTIZA A ESTADO RECHAZADO
$sql="UPDATE Tbl_cotiza_laminas SET B_estado = '2' WHERE Tbl_cotiza_laminas.N_cotizacion='$n_cn3' and Tbl_cotiza_laminas.N_referencia_c='$cod_ref3' and Tbl_cotiza_laminas.Str_nit='$Str_nit3'";
$result=mysql_query($sql);
}
//EN TODOS LOS CASOS
header("Location:referencias.php");



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
</head>
<body>
</body>
</html>
<?php
mysql_free_result($usuario);
?>
