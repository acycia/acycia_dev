<?php require_once('Connections/conexion1.php'); ?>
<?php
mysql_select_db($database_conexion1, $conexion1);                                                            
/*----------------------------------------*/
/*--------------ACCIONES------------------*/
/*----------------------------------------*/
/*------------COTIZACIONES---------------*/
/*------------VARIABLE DE CONDICION SWITCH RUD---------------*/
$rud=$_POST['valor'];
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
// area de switch()
switch($rud) {
case '1':
$refMP=$_POST['Str_referencia_m']; 
$nitc=$_POST['Str_nit'];
$sqlestado="SELECT N_cotizacion,N_referencia_c,Str_nit,Str_referencia,fecha_creacion,B_estado FROM Tbl_cotiza_materia_p WHERE Str_referencia='$refMP' and Str_nit='$nitc' ORDER BY fecha_creacion DESC LIMIT 1";
$resultestado= mysql_query($sqlestado);
$numestado= mysql_num_rows($resultestado);
if($numestado >='1')
{
$cotiz=mysql_result($resultestado,0,'N_cotizacion');	
$sqlobsoleta="UPDATE Tbl_cotiza_materia_p SET B_estado='3' WHERE N_cotizacion='$cotiz' and Str_referencia='$refMP' and Str_nit='$nitc'";
$resultobsoleta=mysql_query($sqlobsoleta);  
} 
$insertSQL = sprintf("INSERT INTO Tbl_cotiza_materia_p (N_cotizacion,N_referencia_c, Str_nit, N_cantidad, Str_incoterms, Str_moneda, N_precio_vnta, Str_referencia,Str_unidad_vta,Str_plazo, fecha_creacion, Str_usuario, N_comision, Str_linc, B_estado, B_generica) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",           
                       GetSQLValueString($_POST['N_cotizacion'], "int"),
					   GetSQLValueString($_POST['N_referencia'], "int"),  
                       GetSQLValueString($_POST['Str_nit'], "text"),
                       GetSQLValueString($_POST['N_cantidad_m'], "text"),                      
					   GetSQLValueString($_POST['Str_incoterms_m'], "text"),
					   GetSQLValueString($_POST['Str_moneda_m'], "text"),                                                                    
                       GetSQLValueString($_POST['N_precio_vnta_m'], "text"),
					   GetSQLValueString($_POST['Str_referencia_m'], "text"),
					   GetSQLValueString($_POST['Str_unidad_vta'], "text"),
					   GetSQLValueString($_POST['Str_plazo'], "text"),
					   GetSQLValueString($_POST['fecha_m'], "date"),
					   GetSQLValueString($_POST['vendedor'], "text"),
					   GetSQLValueString($_POST['N_comision'], "double"),
					   GetSQLValueString($_POST['Str_linc'], "text"),
					   GetSQLValueString($_POST['B_estado'], "text"),
					   GetSQLValueString($_POST['B_generica'], "text"));
mysql_select_db($database_conexion1, $conexion1);
$Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());
/*SEGUNDO INSERT A LA SEGUNDA TABLA QUE CONTIENE LOS TEXTOS*/
$insertSQL2 = sprintf("INSERT INTO Tbl_cotiza_materia_p_obs ( N_cotizacion,N_referencia_c,Str_nit, texto) VALUES (%s, %s, %s, %s)",
GetSQLValueString($_POST['N_cotizacion'], "int"),
GetSQLValueString($_POST['N_referencia'], "int"),
GetSQLValueString($_POST['Str_nit'], "text"),
GetSQLValueString($_POST['nota_m'], "text"));
mysql_select_db($database_conexion1, $conexion1);
$Result2 = mysql_query($insertSQL2, $conexion1) or die(mysql_error());
//CONSULTA PARA VER SI EXISTE LA COTIZACION EN MAESTRO
   $n_cot=$_POST['N_cotizacion'];
   $nit=$_POST['Str_nit'];  
  if($n_cot!=''&& $nit!='')
  {	  
  $sqlver="SELECT * FROM Tbl_cotizaciones WHERE N_cotizacion ='$n_cot' and Str_nit='$nit'";
  $resultver= mysql_query($sqlver);
  $numver= mysql_num_rows($resultver);
  if($numver =='0')
  {
/*INSERT EN LA BD Tbl_cotizaciones*/ 
$insertSQL3 = sprintf("INSERT INTO Tbl_cotizaciones(N_cotizacion,Str_nit,Str_tipo, fecha) VALUES (%s, %s, %s, %s)",
GetSQLValueString($_POST['N_cotizacion'], "int"),
GetSQLValueString($_POST['Str_nit'], "text"),
GetSQLValueString($_POST['Str_tipo'], "text"),
GetSQLValueString($_POST['fecha_m'], "date"));
mysql_select_db($database_conexion1, $conexion1);
$Result3 = mysql_query($insertSQL3, $conexion1) or die(mysql_error()); 
  }
  }
//INSERT EN LA BD Tbl_maestra_mp
$insertSQL5 = sprintf("INSERT INTO Tbl_maestra_mp(id_mp,N_referencia,N_cotizacion,Str_nit) VALUES (%s, %s, %s, %s)",
GetSQLValueString($_POST['id_mp'], "int"),
GetSQLValueString($_POST['N_referencia'], "int"),
GetSQLValueString($_POST['N_cotizacion'], "int"),
GetSQLValueString($_POST['Str_nit'], "text"));
mysql_select_db($database_conexion1, $conexion1);
$Result5 = mysql_query($insertSQL5, $conexion1) or die(mysql_error()); 
/*ESTE CODIGO ES PARA ENVIAR POR GET LAS VARIABLES NI Y N_COTIZACION Y PODER HACER EL SELECT EN cotizacion_bolsa_vista.php*/
  $insertGoTo = "cotizacion_g_materiap_vista.php?Str_nit=" . $_POST['Str_nit'] . "&N_cotizacion=" . $_POST['N_cotizacion'] .  "&tipo=" . $_POST['tipo_usuario'] ;
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo)); 
break;
return '0';
case'2':
$updateSQL = sprintf("UPDATE Tbl_cotiza_materia_p SET N_cotizacion=%s,  N_referencia_c=%s,Str_nit=%s, N_cantidad=%s, Str_incoterms=%s, Str_moneda=%s, N_precio_vnta=%s, Str_referencia=%s,Str_unidad_vta=%s, Str_plazo=%s, fecha_creacion=%s, Str_usuario=%s, N_comision=%s, Str_linc=%s, B_estado=%s, B_generica=%s WHERE N_cotizacion='%s' and N_referencia_c='%s'",    
                       GetSQLValueString($_POST['N_cotizacion'], "int"),
					   GetSQLValueString($_POST['N_referencia'], "int"), 
                       GetSQLValueString($_POST['Str_nit'], "text"),
                       GetSQLValueString($_POST['N_cantidad_m'], "text"),                      
					   GetSQLValueString($_POST['Str_incoterms_m'], "text"),
					   GetSQLValueString($_POST['Str_moneda_m'], "text"),                                                                    
                       GetSQLValueString($_POST['N_precio_vnta_m'], "text"),
					   GetSQLValueString($_POST['Str_referencia_m'], "text"),
					   GetSQLValueString($_POST['Str_unidad_vta'], "text"),
					   GetSQLValueString($_POST['Str_plazo'], "text"),					   
					   GetSQLValueString($_POST['fecha_m'], "date"),
					   GetSQLValueString($_POST['vendedor'], "text"),
					   GetSQLValueString($_POST['N_comision'], "double"),
					   GetSQLValueString($_POST['Str_linc'], "text"),
					   GetSQLValueString($_POST['B_estado'], "text"),
					   GetSQLValueString($_POST['B_generica'], "text"),
					   GetSQLValueString($_POST['N_cotizacion'], "int"),
					   GetSQLValueString($_POST['N_referencia'], "int"));
mysql_select_db($database_conexion1, $conexion1);
$Result4 = mysql_query($updateSQL, $conexion1) or die(mysql_error());
/*SEGUNDO INSERT A LA SENDA TABLA QUE CONTIENE LOS TEXTOS*/
$updateSQL2 = sprintf("UPDATE Tbl_cotiza_materia_p_obs SET N_cotizacion=%s,N_referencia_c=%s,Str_nit=%s, texto=%s WHERE N_cotizacion='%s' and N_referencia_c='%s'",
GetSQLValueString($_POST['N_cotizacion'], "int"),
GetSQLValueString($_POST['N_referencia'], "int"),
GetSQLValueString($_POST['Str_nit'], "text"),
GetSQLValueString($_POST['nota_m'], "text"),
GetSQLValueString($_POST['N_cotizacion'], "int"),
GetSQLValueString($_POST['N_referencia'], "int"));
mysql_select_db($database_conexion1, $conexion1);
$Result2 = mysql_query($updateSQL2, $conexion1) or die(mysql_error()); 
/*ESTE CODIGO ES PARA ENVIAR POR GET LAS VARIABLES NIT Y N_COTIZACION Y PODER HACER EL SELECT EN cotizacion_bolsa_vista.php*/ 
  $updateGoTo = "cotizacion_g_materiap_vista.php?Str_nit=" . $_POST['Str_nit'] . "&N_cotizacion=" . $_POST['N_cotizacion'] .  "&tipo=" . $_POST['tipo_usuario'] ;
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo)); 
break;
return '0';
case'3':
/*INSERT EN LA BD Tbl_mp_vta*/
if (isset($_FILES['Fil_archivo']) && $_FILES['Fil_archivo']['name'] != "") {
$dir ="archivosc/archivos_pdf_mp/";
$nom1 = str_replace(' ', '',  $_FILES['Fil_archivo']['name']);//quitar espacios en la cadena
$archivo_temporal = $_FILES['Fil_archivo']['tmp_name'];
if (!copy($archivo_temporal,$dir.$nom1)) {
$error = "Error al enviar el Archivo";
} else { $archivof = "archivosc/archivos_pdf_mp/".$nom1; }
}
$insertSQLadj = sprintf("INSERT INTO Tbl_mp_vta(id_mp_vta, Str_nombre, Str_linc_archivo) VALUES (%s, %s, '$nom1')",
GetSQLValueString($_POST['id_mp_vta'], "int"),
GetSQLValueString($_POST['Str_nombre_r'], "text"));
mysql_select_db($database_conexion1, $conexion1);
$Resultadj = mysql_query($insertSQLadj, $conexion1) or die(mysql_error());
header('location:cotizacion_general_materia_prima_ref_nueva.php');
break;
return '0'; 
case'4':
//UPDATE EN LA BD Tbl_mp_vta 
$ID=$_POST['id_mp'];
$nombre1=$_POST['arte1'];//UPDATE ESPECIAL PARA ARCHIVOS DEL SERVER Y EL LINC DE LA TABLA
if (isset($_FILES['Fil_archivo']) && $_FILES['Fil_archivo']['name'] != "") {
if($nombre1 != '') {
if (file_exists("archivosc/archivos_pdf_mp/".$nombre1))
{ unlink("archivosc/archivos_pdf_mp/".$nombre1);} 
}
$directorio = "archivosc/archivos_pdf_mp/";
$nombre1  = str_replace(' ', '',  $_FILES['Fil_archivo']['name']);//quitar espacios en la cadena
$archivo_temporal = $_FILES['Fil_archivo']['tmp_name'];
if (!copy($archivo_temporal,$directorio.$nombre1)) {
$error = "Error al enviar el Archivo";
} else { $archivo = "archivosc/archivos_pdf_mp/".$nombre1; }	
}	
$insertSQLup = sprintf("UPDATE Tbl_mp_vta SET id_mp_vta=%s, Str_nombre=%s, Str_linc_archivo='$nombre1' WHERE id_mp_vta=%s",
GetSQLValueString($_POST['id_mp_vta'], "int"),
GetSQLValueString($_POST['Str_nombre_r'], "text"),
GetSQLValueString($_POST['id_mp'], "int"));
mysql_select_db($database_conexion1, $conexion1);
$Resultup = mysql_query($insertSQLup, $conexion1) or die(mysql_error());
  $updateGoTo = "cotizacion_general_materia_prima_ref_nueva.php?Str_nit=" . $_POST['Str_nit'] . "&N_cotizacion=" . $_POST['N_cotizacion'] .  "&tipo=" . $_POST['tipo_usuario'] ;
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo)); 
//header('location:cotizacion_general_materia_prima_ref_nueva.php');
break;
return '0';

 
};
}
?>