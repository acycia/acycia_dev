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
  
  $resulnit = mysql_query("SELECT nit_c, nombre_c FROM cliente WHERE nit_c = '".$_POST['nit_c']."' || nombre_c = '".$_POST['nombre_c']."'");//para cobntrolar el nit repetido
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")&&(mysql_num_rows($resulnit) < 1)) {
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
  /*if(isset($_POST['ciudadexterno'])&&$_POST['ciudadexterno']!=''){$ciudad_c=$_POST['ciudad_c'];echo $ciudad_c;}else{$ciudad_c=$_POST['ciudad_c'];echo $ciudad_c;}*/
  
  if(isset($_POST['ciudad_c'])&&$_POST['ciudad_c']!=''){
    $parte=explode(".","$_POST[ciudad_c]");
  $ciudad_c=$parte[1].$_POST['ciudadexterno'];}
  //GUARDAR DATOS VALIDADOS SIN PUNTOS NO COMAS ETC
  $num2=trim($_POST['nit_c']);
  $nit_c = ereg_replace("[^A-Za-z0-9-]", "", $num2);
  $nit_c=str_replace(' ', '', $nit_c);
 
  $nit_c = explode('-', $nit_c);
  $nit_c = $nit_c[0].$nit_c[1];


  //GUARDAR NOMBRE SIN ESPACIOS INICIO Y FIN
  $nomb=trim($_POST['nombre_c']);
  //PARA SUBIR ARCHIVOS PDF
  if (isset($_FILES['camara_comercio_c']) && $_FILES['camara_comercio_c']['name'] != "") {
  $directorio1 = "archivosc/";
  $nombre1 = str_replace(' ', '',  $_FILES['camara_comercio_c']['name']);
  $archivo_temporal1 = $_FILES['camara_comercio_c']['tmp_name'];
  if (!copy($archivo_temporal1,$directorio1.$nombre1)) {
  $error = "Error al enviar el Archivo";
  } else { $imagen1 = "archivosc/".$nombre1; }
  }
  if (isset($_FILES['referencias_bancarias_c']) && $_FILES['referencias_bancarias_c']['name'] != "") {
  $directorio3 = "archivosc/";
  $nombre5 = str_replace(' ', '',  $_FILES['referencias_bancarias_c']['name']);
  $archivo_temporal5 = $_FILES['referencias_bancarias_c']['tmp_name'];
  if (!copy($archivo_temporal5,$directorio5.$nombre5)) {
  $error = "Error al enviar el Archivo";
  } else { $imagen5 = "archivosc/".$nombre5; }
  }
  if (isset($_FILES['balance_general_c']) && $_FILES['balance_general_c']['name'] != "") {
  $directorio2 = "archivosc/";
  $nombre2 = str_replace(' ', '',  $_FILES['balance_general_c']['name']);
  $archivo_temporal2 = $_FILES['balance_general_c']['tmp_name'];
  if (!copy($archivo_temporal2,$directorio2.$nombre2)) {
  $error = "Error al enviar el Archivo";
  } else { $imagen2 = "archivosc/".$nombre2; }
  }
  if (isset($_FILES['pdf_impuesto']) && $_FILES['pdf_impuesto']['name'] != "") {
  $directorio = "archivosc/impuesto/";
  $nombre9 = str_replace(' ', '',  $_FILES['pdf_impuesto']['name']);
  $archivo_temporal9 = $_FILES['pdf_impuesto']['tmp_name'];
  if (!copy($archivo_temporal9,$directorio.$nombre9)) {
  $error = "Error al enviar el Archivo";
  } else { $imagen9 = "archivosc/impuesto/".$nombre9; }
  }


/*  if (isset($_FILES['estado_pyg_c']) && $_FILES['estado_pyg_c']['name'] != "") {
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
  }*/
  //FIN DE LA VALIDACION Y SUBIDA DE ARCHIVOS PDF



  $insertSQL = sprintf("INSERT INTO cliente (id_c, nit_c, nombre_c, tipo_c, fecha_ingreso_c, fecha_solicitud_c,rep_legal_c, telefono_c, direccion_c, fax_c, contacto_c, cargo_contacto_c, telefono_contacto_c, celular_contacto_c, pais_c, provincia_c, ciudad_c, email_comercial_c, contacto_bodega_c, cargo_contacto_bodega_c, direccion_entrega_c, email_contacto_bodega_c, pais_bodega_c, provincia_bodega_c, ciudad_bodega_c, telefono_bodega_c, fax_bodega_c, direccion_envio_factura_c, telefono_envio_factura_c, fax_envio_factura_c, observ_inf_c, contacto_dpto_pagos_c, telefono_dpto_pagos_c, fax_dpto_pagos_c, direccion_dpto_pagos_c, email_dpto_pagos_c, cupo_solicitado_c, forma_pago_c, otro_pago_c, `1ref_comercial_c`, tel_1ref_comercial_c, nombre_1ref_comercial_c, cupo_1ref_comercial_c, plazo_1ref_comercial_c, `2ref_comercial_c`, tel_2ref_comercial_c, nombre_2ref_comercial_c, cupo_2ref_comercial_c, plazo_2ref_comercial_c, `3ref_comercial_c`, tel_3ref_comercial_c, nombre_3ref_comercial_c, cupo_3ref_comercial_c, plazo_3ref_comercial_c, `1ref_bancaria_c`, telefono_1ref_bancaria_c, nombre_1ref_bancaria_c, `2ref_bancaria_c`, telefono_2ref_bancaria_c, nombre_2ref_bancaria_c, `3ref_bancaria_c`, telefono_3ref_bancaria_c, nombre_3ref_bancaria_c, observ_inf_finan_c, cupo_aprobado_c, plazo_aprobado_c, observ_aprob_finan_c, estado_comercial_c, observ_asesor_com_c, camara_comercio_c, referencias_bancarias_c, referencias_comerciales_c, estado_pyg_c, balance_general_c, flujo_caja_proy_c,  fotocopia_declar_iva_c,  otros_doc_c, observ_doc_c, estado_c, registrado_c,email_factura_c,impuesto,pdf_impuesto) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
             GetSQLValueString($_POST['id_c'], "int"),
             GetSQLValueString($nit_c, "text"),
             GetSQLValueString($nomb, "text"),
             GetSQLValueString($_POST['tipo_c'], "text"),
             GetSQLValueString($_POST['fecha_ingreso_c'], "date"),
             GetSQLValueString($_POST['fecha_solicitud_c'], "date"),
  /*             GetSQLValueString(isset($_POST['bolsa_plastica_c']) ? "true" : "", "defined","1","0"),
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
             GetSQLValueString($_POST['registrado_c'], "text"),
             GetSQLValueString($_POST['email_factura_c'], "text"),
             GetSQLValueString(isset($_POST['impuesto']) ? "true" : "", "defined","1","0"),
             GetSQLValueString($nombre9, "text"));
             
  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());
  
  //INSERT PARA LA TABLA TBL_DESTINATARIOS
  //CODIGO PARA CAPTURAR REGISTROS DEL ARRAY DE LOS CAMPOS DINAMICOS
/*  $id_d=$_POST['id_c'];
  $pnt=$_POST['nit_c'];
  $nombre=$_POST['responsable_dest'];
  $dir=$_POST['direccion_dest'];
  $ind=$_POST['indicativo_dest'];
  $tel=$_POST['telefono_dest'];
  $ext=$_POST['extension_dest'];
  $ciu=$_POST['ciudad_dest'];
 
  for($n=0,$d=0,$i=0,$t=0,$e=0,$c=0;$n<count($nombre);$n++,$d++,$i++,$t++,$e++,$c++){
    $dir_des=strtoupper($dir[$d]);//pasa a mayusculas
    if((isset($nombre[$n])) && (!(empty($nombre[$n]))))//controlo bodegas vacias
    {
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
   }//FIN IF NO VACIOS
  }*/
  $insertGoTo = "perfil_cliente_vista.php?id_c=" . $_POST["id_c"] . "&tipo_usuario=" . $_POST["tipo_usuario"] ."&nit_c=" . $_POST["nit_c"]."&ciudad=" . $_POST["ciudad_c"];
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
  }else  if (isset($_POST['nit_c'])){ echo "<script type=\"text/javascript\">alert(\"EL NIT O EL NOMBRE ESTAN REPETIDOS, VERIFIQUE\");history.go(-1)</script>"; } 




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
  <script type="text/javascript" src="js/consulta.js"></script>
  <script type="text/javascript" src="js/consulta_ciudad.js"></script>
  <script type="text/javascript" src="js/validacion_numerico.js"></script>
  <script type="text/javascript" src="js/agregueCampos.js"></script>
  <script type="text/javascript" src="js/mayusculasTodo.js"></script> 
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

 <script> 
  function copiadirC(){
                    
            var nombre = document.getElementById('contacto_c').value;
            var nombre2=nombre.toUpperCase();
          document.getElementById('respons').value =  nombre2;
          dircaf = document.getElementById('dirC').value;
          var DirM =dircaf.toUpperCase();
          document.getElementById('dirF').value =DirM;
          document.getElementById('dir').value=DirM;
          indica1  = document.getElementById('indicativo1').value;
            var res = indica1.split("______.");//separa string
          var IND=res[0];
          var CIU=res[1];
          document.getElementById('indica').value=IND;
          telefono_c = document.getElementById('telefono_c').value;
          document.getElementById('tel').value=telefono_c;
          exten = document.getElementById('extension1').value;
          document.getElementById('exten').value=exten; 
          
          ciudadexter = document.getElementById('ciudadexterno').value;
          if(ciudadexter!=""){
          CIUEX=ciudadexter;
          document.getElementById('ciudad_dest').value=CIUEX;
          }else{
          document.getElementById('ciudad_dest').value=CIU;
          }
                }
  </script>
  </head>
  <body oncontextmenu="return true"><!-- bloquea clic derecho oncontextmenu -->
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
     <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" onSubmit="MM_validateForm('fecha_ingreso_c','','R', 'nombre_c','','R','nit_c','','R','tipo_c','','R','rep_legal_c','','R','telefono_c','','R','direccion_c','','R','pais_c','','R');return document.MM_returnValue">
     <table id="tabla_formato2" >
       <tr>
       <td width="31%" id="codigo_formato_2">CODIGO: R1-F07</td>
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
       <td id="dato_1">NIT 000000000-0</td>
       <td id="dato_1">Cliente N&deg;
         <input name="id_c" id="id_c" type="hidden" value="<?php $num=$row_n_cliente['id_c']+1;  echo $num; ?>">
       <?php $num=$row_n_cliente['id_c']+1; echo $num; ?></td>
       <td id="dato_1">&nbsp;</td>
       
       </tr>
       <tr>
       <td colspan="4" id="dato_1">
           <input type="text" id="nit_c" name="nit_c" value="" size="30" onChange="if (form1.nit_c.value) {MayusEspacio(this); DatosGestiones('1','nit_c',form1.nit_c.value); } else { alert('Debe digitar el NIT para validar su existencia en la BD'); };desapareceClick(this);" >
            <em style="color: red;" >Ingrese el nit con el digito de verificación Sin guiones </em><br>

      </td> 
       </tr>
       <tr>
       <td id="dato_1"> Raz&oacute;n Social</td>
       <td id="dato_1">Tipo de Cliente </td>
       <td id="dato_1">&nbsp;</td>
       
       </tr>
       <tr>
       <td id="dato_1">
        <input name="nombre_c" type="text" onBlur="MayusculaSinEspacios(this),desapareceClick(this)" onChange="if (form1.nombre_c.value) { DatosGestiones('2','nombre_c',form1.nombre_c.value); } else { alert('Debe digitar el Nombre para validar su existencia en la BD'); };desapareceClick(this);" value="" size="30" maxlength="100" required>
        <div id="resultado"></div>
       </td>
       <td id="dato_1">
        <select name="tipo_c" onChange="ocultarCampo(this);">
         <option value="">*</option>
         <option value="NACIONAL">Nacional
         <option value="EXTRANJERO">Extranjero
       </select>
       </td>
       <td id="dato_1">&nbsp;</td>
       <tr>
       <td><input name="bolsa_plastica_c" type="hidden" id="bolsa_plastica_c" value="0">
         <input name="lamina_c" type="hidden" id="lamina_c" value="0">
       </td>
       <td colspan="3">
        <input name="cinta_c" type="hidden" id="cinta_c" value="0">
        <input name="packing_list_c" type="hidden" id="packing_list_c" value="0">
       </td>
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
       <td colspan="1" id="dato_1"><input name="rep_legal_c" id="rep_legal_c" type="text"  value="" size="50" maxlength="100"></td>
       <td nowrap colspan="1" id="detalle_1"><input type="text" name="indicativo1" value=""id="indicativo1" style="width:40px" onClick="this.value = ''">
         <input type="number" style="width: 130px" name="telefono_c" id="telefono_c" value="" onBlur="copiadirC();">
       <input type="number" style="width:60px" name="extension1" id="extension1"  value=""onBlur="copiadirC();"></td>
       <td colspan="2" ><select  class='Estilo7'name="pais_c" id="id_pais" style="width:250px" >
         <?php
          do {  
          ?>
         <option value="<?php echo utf8_encode($row_n_pais['nombre_pais'])?>"><?php echo utf8_encode($row_n_pais['nombre_pais']);?></option>
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
  /*   //CONSULTA PAIS        
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
       <td id="dato_1"><input name="direccion_c" id="dirC" type="text" onBlur="copiadirC();conMayusculas(this)" value="" size="50" maxlength="500" required></td>
       <td id="dato_4"><?php //prueba para indicativo ciudad
  /*       mysql_select_db($database_conexion1, $conexion1);      
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
       <td colspan="2" id="dato_4"><select class='Estilo7' style="width:250px" name="ciudad_c" id='miCampoDeTexto' onchange='Javascript:document.form1.indicativo1.value=this.value;
     document.form1.indicativo11.value=this.value;
     document.form1.indicativo3.value=this.value;
     document.form1.indicativo12.value=this.value;copiadirC();'>
         <?php
     do {  
     ?>
         <option value="<?php echo  utf8_encode($row_n_ciudad['ind_ciudad'])?>______.<?php echo  utf8_encode($row_n_ciudad['nombre_ciudad'])?>"><?php echo utf8_encode($row_n_ciudad['nombre_ciudad']);?></option>
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
  /*   //CONSULTA CIUDADES      
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
     echo '</select>';  */
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
       <td id="detalle_1"><input name="indicativo11" type="text"id="indicativo11"onClick="this.value = ''"onKeyUp="return ValNumero(this)"value= "" style="width:40px">
       <input type="number" name="fax_c" value="" style="width: 130px">
       <input type="number" style="width:60px" name="extension11" id="extension11" onKeyUp="return ValNumero(this)" value=""></td>
       <td colspan="2" id="dato_1"><input name="ciudadexterno" id="ciudadexterno" type="text" style="width: 250px;" value=""  onBlur="conMayusculas(this);copiadirC();" /></td>
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
       <td colspan="1" id="dato_1"><input name="contacto_c" id="contacto_c" type="text"onBlur="conMayusculas(this);copiadirC();" value="" size="50" maxlength="100"></td>
       <td id="dato_1"><input type="text" name="cargo_contacto_c" value="" style="width: 250px" ></td>
       <td colspan="2" id="dato_1"><input type="text" name="email_contacto_bodega_c" value="" style="width: 250px" onBlur="MM_validateForm('email_contacto_bodega_c','','NisEmail');return document.MM_returnValue;conMayusculas(this)"></td>
       </tr>
       <tr>
       <td id="dato_1">Direcci&oacute;n Envio de Factura </td>
       <td width="31%" id="dato_1">Indic.  Telefono En. Factura Extension</td>
       <td width="13%" id="dato_1">Indicat. Fax Envio de Factura. Extension</td>
       <td width="25%" id="dato_1">&nbsp;</td>
       
       </tr>
       <tr>
       <td id="dato_1"><input name="direccion_envio_factura_c" id="dirF" type="text"  value="" size="50" maxlength="500" required></td>
       <td id="detalle_1"><input type="text" name="indicativo3"value= ""id="indicativo3" style="width: 40px" onKeyUp="return ValNumero(this)"onClick="this.value = ''">
         <input type="number" name="telefono_envio_factura_c" value="" style="width: 130px" >
       <input type="number" style="width:60px" name="extension3" id="extension3" onKeyUp="return ValNumero(this)" value="" ></td>
       <td colspan="2" nowrap id="dato_1"><input type="text" name="indicativo12"value= ""id="indicativo12" style="width:40px" onKeyUp="return ValNumero(this)"onClick="this.value = ''">
         <input type="number" name="fax_envio_factura_c" value="" style="width: 130px">
       <input type="number" style="width:60px" name="extension12" id="extension12" onKeyUp="return ValNumero(this)" value=""></td>
       </tr>
       <tr>
       <td id="dato_1">Celular Contacto Comercial</td>
       <td id="dato_1">Email Factura</td>
       <td id="detalle_3">&nbsp;</td>
       <td id="detalle_3">&nbsp;</td>
       </tr>
       <tr>
       <td id="dato_1"><input type="text" name="celular_contacto_c" value="" size="50"onKeyUp="return ValNumero(this)"></td>
       <td id="dato_1"><input id="email" type="text" name="email_factura_c" value="<?php echo $row_cliente['email_factura_c']; ?>" size="50"onblur="MM_validateForm('email_factura_c','','NisEmail'); return conMayusculas(this)" required="required" ></td>
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
       <td colspan="4" id="detalle_1"><textarea name="observ_inf_c" cols="100" rows="2"></textarea>
  





                
       
           
       
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
       <td id="dato_1"><input type="text" name="contacto_dpto_pagos_c" value="" size="50"></td>
       <td id="detalle_1"><input type="text" name="indicativo4"value= ""id="indicativo4"  style="width:40px" onKeyUp="return ValNumero(this)"onclick="this.value = ''">
       <input type="number" name="telefono_dpto_pagos_c" value=""  style="width: 130px">
       <input type="number" style="width:60px" name="extension4" id="extension4" onKeyUp="return ValNumero(this)" value=""></td>
       <td colspan="2" id="dato_1"><input type="text" name="email_dpto_pagos_c" value="" style="width: 250px"  onChange="MM_validateForm('email_dpto_pagos_c','','NisEmail');return document.MM_returnValue"></td>
       </tr>
       <tr>
       <td id="dato_1">Direcci&oacute;n</td>
       <td id="dato_1"> Indicat. Fax financiera. Exension</td>
       <td id="dato_1">Cupo Solicitado ($)</td>
       <td id="dato_1">&nbsp;</td>          
       </tr>
       <tr>
       <td id="dato_1"><input type="text" name="direccion_dpto_pagos_c" value="" size="50"></td>
       <td id="detalle_1"><input type="text" name="indicativo13"value= ""id="indicativo13" style="width:40px" onKeyUp="return ValNumero(this)"onClick="this.value = ''">
         <input type="number" name="fax_dpto_pagos_c" value=""  style="width: 130px">
       <input type="number" style="width:60px" name="extension13" id="extension13" onKeyUp="return ValNumero(this)" value=""></td>
       <td colspan="2" id="dato_1"><input type="text" name="cupo_solicitado_c" value="" style="width: 250px" onKeyUp="return ValNumero(this)"></td>
       </tr>
       <tr>
       <td id="dato_1"><select name="forma_pago_c">
       <option value=""> </option>
         <option value="CHEQUE">Cheque</option>
         <option value="CONSIGNACION">Consignacion</option>
         <option value="TRANSFERENCIA">Transferencia</option>
         <option value="Transferencia bancaria directa">Transferencia bancaria directa</option>
         <option value="OTRA">Otra</option>
       </select>
  Forma de Pago </td>
       <td id="dato_1">Otra Forma de Pago
       <input type="text" name="otro_pago_c" value="" size="30"></td>
       <td id="dato_1">&nbsp;</td>
       <td id="dato_1">&nbsp;</td>          
       </tr> 
  <tr>
    <td colspan="4" id="subtitulo2">DOCUMENTOS ADJUNTOS </td>
  </tr>
  <tr>
    <td colspan="2" nowrap id="dato_1">
      Camara de Comercio (Vigente)<br>
      <input name="camara_comercio_c" type="file" size="20" maxlength="60"class="botones_file">
    </td>
    <td colspan="2" nowrap id="dato_1">Rut<br>
    <input name="balance_general_c" type="file" size="20" maxlength="60"class="botones_file">
    </td>
  </tr>
  <tr>
    <td colspan="2" nowrap id="dato_1">Proteccion de Datos<br>
    <input name="referencias_bancarias_c" type="file" size="20" maxlength="60" class="botones_file">
  </td>
<!--    <td colspan="2" nowrap id="dato_1">Referencias Comerciales<br>
    <input name="referencias_comerciales_c" type="file" size="20" maxlength="60"class="botones_file">
    </td> -->
  <!--</tr>
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
    <input name="otros_doc_c" type="file" size="20" maxlength="60"class="botones_file"></td>-->
  </tr>
  <tr>
    <td colspan="4" id="dato_1">Observaciones de Documentos Adjuntos </td>
  </tr>
  <tr>
    <td colspan="4" id="detalle_1"><textarea name="observ_doc_c" cols="100" rows="2"></textarea></td>
  </tr>
  <tr>
    <td colspan="4" id="subtitulo2">INFORMACION FINAL DEL FORMATO PERFIL DE CLIENTES </td>
  </tr>
  <tr>
    <td id="dato_1">ESTADO DEL CLIENTE
    <select name="estado_c" id="estado_c">
      <option value="ACTIVO" selected>ACTIVO</option>
      <option value="PENDIENTE">PENDIENTE</option>
      <option value="INACTIVO">INACTIVO</option>
    </select>
    <input name="tipo_usuario" type="hidden" id="tipo_usuario" value="<?php echo $row_usuario['tipo_usuario']; ?>">
      <input name="id_usuario" type="hidden" id="id_usuario" value="<?php echo $row_usuario['id_usuario']; ?>"></td>
    <td colspan="3" id="dato_1">Registrado Por
    <input name="registrado_c" type="text" value="<?php  $usuar=strtoupper( $row_usuario['nombre_usuario']);echo $usuar; ?>" size="20"readonly>
    &nbsp;&nbsp;IMPUESTO ADICIONAL? <input type="checkbox" name="impuesto" id="impuesto" checked value="1"> <label for="impuesto"> &nbsp;&nbsp;Adjunto PDF: <input name="pdf_impuesto" type="file" size="20" maxlength="60"class="botones_file">
  </td> 
  </tr>
  <tr>
    <td colspan="4" id="dato_2">
      <br>
      <input name="save" type="submit" class="botonGeneral" onClick="MM_validateForm('email','','NisEmail');return document.MM_returnValue" value="GUARDAR" onBlur="copiadirC();">
    </td>
  </tr>
     <input type="hidden" name="MM_insert" value="form1">
  </form>

         <!--aqui empieza la opcion multiple de agregar-->                          
  <tr>
   <td colspan="4">   
      <table id="Bodegas" style="display: none;">
           <td colspan="4">
             <hr>
             <b> INFORMACION DE DESPACHO BODEGAS</b>
            </td>  
             <tr> 
                  <form id="formItems" name="formItems" action="guardar.php" method="post">
                    <tr id="items" ><!--  style="display: none;" -->
                      <td colspan="4" id="dato1">
                        <input type="hidden" name="id_d" id="id_d" value="<?php echo $num; ?>" class='campostextMini' >
                        <input type="hidden" name="nit" id="nit" value="<?php echo $row_cliente['nit_c']; ?>" class='campostextMini' > 
                        <input type="text" required="required" placeholder="nombre responsable" id="nombre_responsable" name="nombre_responsable" value="" class='campostext'  >
                        <input type="text" required="required" placeholder="direccion" id="direccion" name="direccion" value="" class='selectsMMedio'  >
                        <input type="text" required="required" placeholder="indicativo" id="indicativo" name="indicativo" value="" class='campostextMini'  >
                        <input type="text" required="required" placeholder="telefono" id="telefono" name="telefono" value="" class='campostextMini'  >
                        <input type="text" required="required" placeholder="extension" id="extension" name="extension" value="" class='campostextMini'  >
                        <input type="text" required="required" placeholder="ciudad" id="ciudad" name="ciudad" value="" class='campostextMini'>
                        <button id="btnEnviarItems" name="btnEnviarItems" type="button" class="botonMini" autofocus="">ADD BODEGA</button> <br>
                        <em style="display: none;  align-items: center; justify-content: center;color: red; " id="AlertItem"></em> 
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
   </table>
   </td>
 </tr>  


</div>
 </table>
  </body>
  </html>
  <script type="text/javascript">
  
   $(document).ready(function(){
    if($("#id_c").val())
    consultasBodegas($("#id_c").val());//despliega los items al recargar
  });

  $("#nit_c").on( "change", function() {
      $("#nit").val($("#nit_c").val() );
      $("#Bodegas").show(); 
       consultasBodegas($("#id_c").val());
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
  mysql_free_result($usuario);mysql_close($conexion1);
  
  mysql_free_result($n_cliente);
  
  mysql_free_result($n_pais);
  
  mysql_free_result($n_ciudad);
  
  //mysql_free_result($destinatario);

  mysql_close($conexion1);
  ?>