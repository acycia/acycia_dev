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
//VARIABLES DESDE LISTADO REFERENCIAS
$codigo_ref=$_GET['cod_ref'];
$id_ref=$_GET['id_ref'];
$n_cotiz=$_GET['n_cotiz'];

$Str_nit=$_GET['Str_nit'];
$tipo=$_GET['tipo'];
$case=$_GET['case'];
$id_rev=$_GET['id_rev'];


switch($case) {
//ACTIVAS
case '1':
//ENVIAR EL COD_REF A LA TABLA BOLSAS
$query_bolsa ="SELECT * FROM Tbl_referencia,Tbl_cliente_referencia WHERE Tbl_referencia.id_ref = '$id_ref' and Tbl_referencia.cod_ref=Tbl_cliente_referencia.N_referencia";
$bolsa = mysql_query($query_bolsa, $conexion1) or die(mysql_error());
$row_bolsa = mysql_fetch_assoc($bolsa);
$totalRows_bolsa= mysql_num_rows($bolsa);
//ENVIAR EL COD_REF A LA TABLA LAMINAS
//IMPRESIONES SEGUN EL TIPO DE PRODUCTO CORRESPONDIENTE A LA REFERENCIA
if($row_bolsa['tipo_bolsa_ref']!='LAMINA'||$row_bolsa['tipo_bolsa_ref']!='LAMINAS'||$row_bolsa['tipo_bolsa_ref']!='PACKING LIST'){
header("location:referencia_bolsa_vista.php?cod_ref=" . $row_bolsa['cod_ref'] . "&id_ref=" . $row_bolsa['id_ref']. "&Str_nit=" .  $row_bolsa['Str_nit'] . "&n_cotiz=" . $row_bolsa['n_cotiz_ref']  . "&tipo=" . $_GET['tipo']);}
if ($row_bolsa['tipo_bolsa_ref']=='LAMINA'||$row_bolsa['tipo_bolsa_ref']=='LAMINAS'){
header("location:referencia_lamina_vista.php?cod_ref=" . $row_bolsa['cod_ref'] . "&id_ref=" . $row_bolsa['id_ref']. "&Str_nit=" . $row_bolsa['Str_nit'] . "&n_cotiz=" . $row_bolsa['n_cotiz_ref'] . "&tipo=" . $_GET['tipo']);}
if($row_bolsa['tipo_bolsa_ref']=='PACKING LIST'){
header("location:referencia_packing_vista.php?cod_ref=" . $row_bolsa['cod_ref'] . "&id_ref=" . $row_bolsa['id_ref']. "&Str_nit=" . $row_bolsa['Str_nit'] . "&n_cotiz=" .$row_bolsa['n_cotiz_ref'] . "&tipo=" . $_GET['tipo']);}
break;
return '0';
//case 2
case '2':
//ENVIAR EL N_COTIZ A LA TABLA BOLSAS 
mysql_select_db($database_conexion1, $conexion1);
$query_bolsa ="SELECT * FROM Tbl_referencia,Tbl_cliente_referencia WHERE Tbl_referencia.id_ref = '$id_ref' and Tbl_referencia.n_cotiz_ref=Tbl_cliente_referencia.N_cotizacion";
$bolsa = mysql_query($query_bolsa, $conexion1) or die(mysql_error());
$row_bolsa = mysql_fetch_assoc($bolsa);
$totalRows_bolsa= mysql_num_rows($bolsa);
//ENVIAR EL N_COTIZ A LA TABLA LAMINAS
if($row_bolsa['tipo_bolsa_ref']!='LAMINA'||$row_bolsa['tipo_bolsa_ref']!='LAMINAS'||$row_bolsa['tipo_bolsa_ref']!='PACKING LIST'){
header("location:cotizacion_g_bolsa_vista.php?N_cotizacion=" . $row_bolsa['n_cotiz_ref'] . "&Str_nit=" .  $row_bolsa['Str_nit'] . "&cod_ref=" . $row_bolsa['cod_ref'] ."&tipo=" . $_GET['tipo']);}
if($row_bolsa['tipo_bolsa_ref']=='LAMINA'||$row_bolsa['tipo_bolsa_ref']=='LAMINAS'){
header("location:cotizacion_g_lamina_vista.php?N_cotizacion=" . $row_bolsa['n_cotiz_ref'] . "&Str_nit=" .  $row_bolsa['Str_nit'] . "&cod_ref=" . $row_bolsa['cod_ref'] . "&tipo=" . $_GET['tipo']);}
if($row_bolsa['tipo_bolsa_ref']=='PACKING LIST'){
header("location:cotizacion_g_packing_vista.php?N_cotizacion=" . $row_bolsa['n_cotiz_ref'] .  "&Str_nit=" . $row_bolsa['Str_nit'] . "&cod_ref=" . $row_bolsa['cod_ref'] . "&tipo=" . $_GET['tipo']);}
break;
return '0';

//INACTIVAS
case '3':
//ENVIAR EL COD_REF A LA TABLA BOLSAS
$query_bolsa ="SELECT * FROM Tbl_referencia,Tbl_cliente_referencia WHERE Tbl_referencia.id_ref = '$id_ref' and Tbl_referencia.cod_ref=Tbl_cliente_referencia.N_referencia and Tbl_referencia.estado_ref='0'";
$bolsa = mysql_query($query_bolsa, $conexion1) or die(mysql_error());
$row_bolsa = mysql_fetch_assoc($bolsa);
$totalRows_bolsa= mysql_num_rows($bolsa);
//ENVIAR EL COD_REF A LA TABLA LAMINAS
//IMPRESIONES SEGUN EL TIPO DE PRODUCTO CORRESPONDIENTE A LA REFERENCIA
if($row_bolsa['tipo_bolsa_ref']!='LAMINA'||$row_bolsa['tipo_bolsa_ref']!='LAMINAS'||$row_bolsa['tipo_bolsa_ref']!='PACKING LIST'){
header("location:referencia_bolsa_vista.php?cod_ref=" . $row_bolsa['cod_ref'] . "&id_ref=" . $row_bolsa['id_ref']."&Str_nit=" .  $row_bolsa['Str_nit'] . "&n_cotiz=" . $row_bolsa['n_cotiz_ref']  . "&tipo=" . $_GET['tipo']);}
if ($row_bolsa['tipo_bolsa_ref']=='LAMINA'||$row_bolsa['tipo_bolsa_ref']=='LAMINAS'){
header("location:referencia_lamina_vista.php?cod_ref=" . $row_bolsa['cod_ref'] . "&id_ref=" . $row_bolsa['id_ref']. "&Str_nit=" . $row_bolsa['Str_nit'] . "&n_cotiz=" . $row_bolsa['n_cotiz_ref'] . "&tipo=" . $_GET['tipo']);}
if($row_bolsa['tipo_bolsa_ref']=='PACKING LIST'){
header("location:referencia_packing_vista.php?cod_ref=" . $row_bolsa['cod_ref'] ."&id_ref=" . $row_bolsa['id_ref']. "&Str_nit=" . $row_bolsa['Str_nit'] . "&n_cotiz=" .$row_bolsa['n_cotiz_ref'] . "&tipo=" . $_GET['tipo']);}
break;
return '0';
//case 4
case '4':
//ENVIAR EL N_COTIZ A LA TABLA BOLSAS 
mysql_select_db($database_conexion1, $conexion1);
$query_bolsa ="SELECT * FROM Tbl_referencia,Tbl_cliente_referencia WHERE Tbl_referencia.id_ref = '$id_ref' and Tbl_referencia.n_cotiz_ref=Tbl_cliente_referencia.N_cotizacion and Tbl_referencia.estado_ref='0'";
$bolsa = mysql_query($query_bolsa, $conexion1) or die(mysql_error());
$row_bolsa = mysql_fetch_assoc($bolsa);
$totalRows_bolsa= mysql_num_rows($bolsa);
//ENVIAR EL N_COTIZ A LA TABLA LAMINAS
if($row_bolsa['tipo_bolsa_ref']!='LAMINA'||$row_bolsa['tipo_bolsa_ref']!='LAMINAS'||$row_bolsa['tipo_bolsa_ref']!='PACKING LIST'){
header("location:cotizacion_g_bolsa_vista.php?N_cotizacion=" . $row_bolsa['n_cotiz_ref'] . "&Str_nit=" .  $row_bolsa['Str_nit'] . "&cod_ref=" . $row_bolsa['cod_ref'] ."&tipo=" . $_GET['tipo']);}
if($row_bolsa['tipo_bolsa_ref']=='LAMINA'||$row_bolsa['tipo_bolsa_ref']=='LAMINAS'){
header("location:cotizacion_g_lamina_vista.php?N_cotizacion=" . $row_bolsa['n_cotiz_ref'] . "&Str_nit=" .  $row_bolsa['Str_nit'] . "&cod_ref=" . $row_bolsa['cod_ref'] . "&tipo=" . $_GET['tipo']);}
if($row_bolsa['tipo_bolsa_ref']=='PACKING LIST'){
header("location:cotizacion_g_packing_vista.php?N_cotizacion=" . $row_bolsa['n_cotiz_ref'] .  "&Str_nit=" . $row_bolsa['Str_nit'] . "&cod_ref=" . $row_bolsa['cod_ref'] . "&tipo=" . $_GET['tipo']);}
break;
return '0';

//COPIAR REFERENCIA 
case '5':
//ENVIAR EL COD_REF PARA TRAER EL NIT
//referencia_bolsa_add.php?N_cotizacion=667&Str_nit=860512330-3&cod_refe=502
mysql_select_db($database_conexion1, $conexion1);
$query_bolsa ="SELECT * FROM Tbl_referencia WHERE Tbl_referencia.id_ref = '$id_ref' ";
//SELECT * FROM Tbl_referencia,Tbl_cliente_referencia WHERE Tbl_referencia.id_ref = '$id_ref' and Tbl_referencia.cod_ref=Tbl_cliente_referencia.N_referencia
$bolsa = mysql_query($query_bolsa, $conexion1) or die(mysql_error());
$row_bolsa = mysql_fetch_assoc($bolsa);
$totalRows_bolsa= mysql_num_rows($bolsa);
//IMPRESIONES SEGUN EL TIPO DE PRODUCTO CORRESPONDIENTE A LA REFERENCIA
if($row_bolsa!='')
{
if($row_bolsa['tipo_bolsa_ref']!='LAMINA'||$row_bolsa['tipo_bolsa_ref']!='LAMINAS'||$row_bolsa['tipo_bolsa_ref']!='PACKING LIST'){
header("location:referencia_bolsa_edit.php?id_ref=" . $row_bolsa['id_ref'] . "&N_cotizacion=" .  $row_bolsa['n_cotiz_ref']  ."&tipo=" . $_GET['tipo']);}
if ($row_bolsa['tipo_bolsa_ref']=='LAMINA'||$row_bolsa['tipo_bolsa_ref']=='LAMINAS'){
header("location:referencia_lamina_edit.php?id_ref=" . $row_bolsa['id_ref'] . "&N_cotizacion=" .  $row_bolsa['n_cotiz_ref']  ."&tipo=" . $_GET['tipo']);}
if($row_bolsa['tipo_bolsa_ref']=='PACKING LIST'){
header("location:referencia_packing_edit.php?id_ref=" . $row_bolsa['id_ref'] . "&N_cotizacion=" .  $row_bolsa['n_cotiz_ref']  ."&tipo=" . $_GET['tipo']);}
}else 
    { 
	header("location:referencia_copia.php?mensaje=" . "3");
	
	}
break;
return '0';

//COPIAR COTIZACIONES 
case '6':
//ENVIAR EL COD_REF PARA TRAER EL NIT
mysql_select_db($database_conexion1, $conexion1);
$query_bolsa ="SELECT * FROM Tbl_cotizaciones WHERE Tbl_cotizaciones.N_cotizacion = '$n_cotiz' ORDER BY fecha DESC LIMIT 1";
 
 
$bolsa = mysql_query($query_bolsa, $conexion1) or die(mysql_error());
$row_bolsa = mysql_fetch_assoc($bolsa);
$totalRows_bolsa= mysql_num_rows($bolsa);
//IMPRESIONES SEGUN EL TIPO DE PRODUCTO CORRESPONDIENTE A LA COTIZCIONES
if($row_bolsa!='')
{
if($row_bolsa['Str_tipo']!='LAMINA'||$row_bolsa['tipo_bolsa_ref']!='LAMINAS'||$row_bolsa['Str_tipo']!='PACKING LIST'){
  $tipo = $_GET['tipo'] =='' ? $row_bolsa['Str_tipo'] :$_GET['tipo'];
header("location:cotizacion_g_bolsa_vista.php?N_cotizacion=" .  $row_bolsa['N_cotizacion']  ."&Str_nit=" .  $row_bolsa['Str_nit'] ."&cod_refe=" .  $_GET['cod_ref'] ."&tipo=" . $tipo);}
if ($row_bolsa['Str_tipo']=='LAMINA'||$row_bolsa['tipo_bolsa_ref']=='LAMINAS'){
header("location:cotizacion_g_lamina_vista.php?N_cotizacion=" .  $row_bolsa['N_cotizacion']  ."&Str_nit=" .  $row_bolsa['Str_nit'] ."&cod_refe=" .  $_GET['cod_ref'] ."&tipo=" . $tipo);}
if ($row_bolsa['Str_tipo']=='MATERIA PRIMA'){
header("location:cotizacion_g_materiap_vista.php?N_cotizacion=" .  $row_bolsa['N_cotizacion']  ."&Str_nit=" .  $row_bolsa['Str_nit'] ."&cod_refe=" .  $_GET['cod_ref'] ."&tipo=" . $tipo);}
if($row_bolsa['Str_tipo']=='PACKING LIST'){
header("location:cotizacion_g_packing_vista.php?N_cotizacion=" .  $row_bolsa['N_cotizacion']  ."&Str_nit=" .  $row_bolsa['Str_nit'] ."&cod_refe=" .  $_GET['cod_ref'] ."&tipo=" . $tipo);}
}
else  

    { 
mysql_select_db($database_conexion1, $conexion1);
$query_bolsa2 ="SELECT * FROM Tbl_cotizaciones,Tbl_cotiza_bolsa, Tbl_cotiza_laminas,Tbl_cotiza_materia_p, Tbl_cotiza_packing WHERE Tbl_cotizaciones.N_cotizacion = '$n_cotiz' and Tbl_cotizaciones.N_cotizacion=Tbl_cotiza_bolsa.N_cotizacion and Tbl_cotizaciones.N_cotizacion=Tbl_cotiza_laminas.N_cotizacion and Tbl_cotizaciones.N_cotizacion=Tbl_cotiza_materia_p.N_cotizacion and Tbl_cotizaciones.N_cotizacion=Tbl_cotiza_packing.N_cotizacion";
$bolsa2 = mysql_query($query_bolsa2, $conexion1) or die(mysql_error());
$row_bolsa2 = mysql_fetch_assoc($bolsa2);
$totalRows_bolsa2= mysql_num_rows($bolsa2);
//IMPRESIONES SEGUN EL TIPO DE PRODUCTO CORRESPONDIENTE A LA COTIZCIONES
if($row_bolsa2!='')
{
if($row_bolsa2['Str_tipo']!='LAMINA'||$row_bolsa['tipo_bolsa_ref']!='LAMINAS'||$row_bolsa2['Str_tipo']!='PACKING LIST'){
header("location:cotizacion_g_bolsa_vista.php?N_cotizacion=" .  $row_bolsa2['N_cotizacion']  ."&Str_nit=" .  $row_bolsa2['Str_nit'] ."&cod_refe=" .  $row_bolsa2['N_referencia_c'] ."&tipo=" . $_GET['tipo']);}
if ($row_bolsa2['Str_tipo']=='LAMINA'||$row_bolsa['tipo_bolsa_ref']=='LAMINAS'){
header("location:cotizacion_g_lamina_vista.php?N_cotizacion=" .  $row_bolsa2['N_cotizacion']  ."&Str_nit=" .  $row_bolsa2['Str_nit'] ."&cod_refe=" .  $row_bolsa2['N_referencia_c'] ."&tipo=" . $_GET['tipo']);}
if ($row_bolsa2['Str_tipo']=='MATERIA PRIMA'){
header("location:cotizacion_g_materiap_vista.php?N_cotizacion=" .  $row_bolsa2['N_cotizacion']  ."&Str_nit=" .  $row_bolsa2['Str_nit'] ."&cod_refe=" .  $row_bolsa2['N_referencia_c'] ."&tipo=" . $_GET['tipo']);}
if($row_bolsa2['Str_tipo']=='PACKING LIST'){
header("location:cotizacion_g_packing_vista.php?N_cotizacion=" .  $row_bolsa2['N_cotizacion']  ."&Str_nit=" .  $row_bolsa2['Str_nit'] ."&cod_refe=" .  $row_bolsa2['N_referencia_c'] ."&tipo=" . $_GET['tipo']);}
}
else{	
header("location:cotizacion_copia.php?mensaje=" . "4");
}
}
break;
return '0';
//CONSULTA PRECIO POR REFERENCIA 
case '7':
//ENVIAR EL COD_REF PARA TRAER EL NIT
mysql_select_db($database_conexion1, $conexion1);
$query_bolsa ="SELECT N_cotizacion,Str_nit,Str_tipo FROM Tbl_cotizaciones WHERE Tbl_cotizaciones.N_cotizacion = '$n_cotiz' AND Str_nit='$Str_nit'";
$bolsa = mysql_query($query_bolsa, $conexion1) or die(mysql_error());
$row_bolsa = mysql_fetch_assoc($bolsa);
$totalRows_bolsa= mysql_num_rows($bolsa);
//IMPRESIONES SEGUN EL TIPO DE PRODUCTO CORRESPONDIENTE A LA COTIZCIONES
if($row_bolsa!='')
{
if($row_bolsa['Str_tipo']!='LAMINA'||$row_bolsa['tipo_bolsa_ref']!='LAMINAS'||$row_bolsa['Str_tipo']!='PACKING LIST'){
header("location:cotizacion_g_bolsa_vista.php?N_cotizacion=" .  $row_bolsa['N_cotizacion']  ."&Str_nit=" .  $row_bolsa['Str_nit'] ."&cod_refe=" .  $codigo_ref ."&tipo=" . $_GET['tipo']);}
if ($row_bolsa['Str_tipo']=='LAMINA'||$row_bolsa['tipo_bolsa_ref']=='LAMINAS'){
header("location:cotizacion_g_lamina_vista.php?N_cotizacion=" .  $row_bolsa['N_cotizacion']  ."&Str_nit=" .  $row_bolsa['Str_nit'] ."&cod_refe=" .  $codigo_ref ."&tipo=" . $_GET['tipo']);}
if ($row_bolsa['Str_tipo']=='MATERIA PRIMA'){
header("location:cotizacion_g_materiap_vista.php?N_cotizacion=" .  $row_bolsa['N_cotizacion']  ."&Str_nit=" .  $row_bolsa['Str_nit'] ."&cod_refe=" .  $codigo_ref ."&tipo=" . $_GET['tipo']);}
if($row_bolsa['Str_tipo']=='PACKING LIST'){
header("location:cotizacion_g_packing_vista.php?N_cotizacion=" .  $row_bolsa['N_cotizacion']  ."&Str_nit=" .  $row_bolsa['Str_nit'] ."&cod_refe=" .  $codigo_ref ."&tipo=" . $_GET['tipo']);}
}
break;
return '0'; 
//CONSULTA PRECIO POR REFERENCIA INDIVIDUAL
case '8':
//ENVIAR EL COD_REF PARA TRAER EL NIT
mysql_select_db($database_conexion1, $conexion1);
$query_bolsa ="SELECT N_cotizacion,Str_nit,Str_tipo FROM Tbl_cotizaciones WHERE Tbl_cotizaciones.N_cotizacion = '$n_cotiz' AND Str_nit='$Str_nit'";
$bolsa = mysql_query($query_bolsa, $conexion1) or die(mysql_error());
$row_bolsa = mysql_fetch_assoc($bolsa); 
$totalRows_bolsa= mysql_num_rows($bolsa);
//IMPRESIONES SEGUN EL TIPO DE PRODUCTO CORRESPONDIENTE A LA COTIZCIONES
if($row_bolsa!='')
{
if($row_bolsa['Str_tipo']!='LAMINA'||$row_bolsa['tipo_bolsa_ref']!='LAMINAS'||$row_bolsa['Str_tipo']!='PACKING LIST'){
header("location:referencia_precio_vista_bolsa.php?N_cotizacion=" .  $row_bolsa['N_cotizacion']  ."&Str_nit=" .  $row_bolsa['Str_nit'] ."&cod_refe=" .  $codigo_ref ."&tipo=" . $_GET['tipo']);}
/*if ($row_bolsa['Str_tipo']=='LAMINA'){
header("location:referencia_precio_vista_lamina.php?N_cotizacion=" .  $row_bolsa['N_cotizacion']  ."&Str_nit=" .  $row_bolsa['Str_nit'] ."&cod_refe=" .  $codigo_ref ."&tipo=" . $_GET['tipo']);}
if ($row_bolsa['Str_tipo']=='MATERIA PRIMA'){
header("location:referencia_precio_vista_mp.php?N_cotizacion=" .  $row_bolsa['N_cotizacion']  ."&Str_nit=" .  $row_bolsa['Str_nit'] ."&cod_refe=" .  $codigo_ref ."&tipo=" . $_GET['tipo']);}
if($row_bolsa['Str_tipo']=='PACKING LIST'){
header("location:referencia_precio_vista_packing.php?N_cotizacion=" .  $row_bolsa['N_cotizacion']  ."&Str_nit=" .  $row_bolsa['Str_nit'] ."&cod_refe=" .  $codigo_ref ."&tipo=" . $_GET['tipo']);}*/
}
break;
return '0'; 
//CONSULTA PRECIO POR REFERENCIA INDIVIDUAL
case '9':
    //TIPO DE REFERENCIA BOLSA LAMINA, PACKING 
$query_cotizacion = "SELECT N_cotizacion FROM Tbl_cotizaciones ORDER BY N_cotizacion DESC";
$cotizacion = mysql_query($query_cotizacion, $conexion1) or die(mysql_error());
$row_cotizacion = mysql_fetch_assoc($cotizacion);
$totalRows_cotizacion = mysql_num_rows($cotizacion);	

$newCotiz = $row_cotizacion['N_cotizacion']+1;
	
$query_tipo ="SELECT id_ref,tipo_bolsa_ref AS tipo FROM Tbl_referencia WHERE CONVERT(cod_ref, SIGNED INTEGER) ='$codigo_ref'";
$tipo = mysql_query($query_tipo, $conexion1) or die(mysql_error());
$row_tipo = mysql_fetch_assoc($tipo);
$totalRows_tipo= mysql_num_rows($tipo);
 
	if($totalRows_tipo >= '1') 
	{
	 $idRef=$row_tipo['id_ref']; 
	 $tipoRef=$row_tipo['tipo'];
	}
	if($tipoRef!='')
	{
	if($tipoRef!='LAMINA'||$tipoRef!='LAMINAS'||$tipoRef!='PACKING LIST'){
 	header("location:cotizacion_general_bolsa_generica.php?id_ref=" . $idRef . "&N_cotizacion=" . $newCotiz . "&Str_nit=" . $Str_nit);
	 }
	if($tipoRef=='LAMINA'){
	header("location:cotizacion_general_laminas_generica.php?id_ref=" . $idRef . "&N_cotizacion=" . $newCotiz . "&Str_nit=" . $Str_nit);
	 }
	if($tipoRef=='PACKING LIST'){
	header("location:cotizacion_general_packingList_generica.php?id_ref=" . $idRef . "&N_cotizacion=" . $newCotiz . "&Str_nit=" . $Str_nit);
	 } 
	 
	}
	
	
	//cotizacion_general_bolsas_edit.php?N_cotizacion=1381&Str_nit=860014040-6&cod_ref=249
 break;
case '10':
    //TIPO DE REFERENCIA BOLSA LAMINA, PACKING 
$query_cotizacion = "SELECT N_cotizacion FROM Tbl_cotizaciones ORDER BY N_cotizacion DESC";
$cotizacion = mysql_query($query_cotizacion, $conexion1) or die(mysql_error());
$row_cotizacion = mysql_fetch_assoc($cotizacion);
$totalRows_cotizacion = mysql_num_rows($cotizacion);  

$newCotiz = $row_cotizacion['N_cotizacion']+1;
  
$query_tipo ="SELECT id_ref,tipo_bolsa_ref AS tipo FROM Tbl_referencia WHERE CONVERT(cod_ref, SIGNED INTEGER)='$codigo_ref'";
$tipo = mysql_query($query_tipo, $conexion1) or die(mysql_error());
$row_tipo = mysql_fetch_assoc($tipo);
$totalRows_tipo= mysql_num_rows($tipo);
 
  if($totalRows_tipo >= '1') 
  {
   $idRef=$row_tipo['id_ref']; 
   $tipoRef=$row_tipo['tipo'];
   $Str_referencia_m = $_GET['Str_referencia_m'];
  }
  if($tipoRef!='')
  {
   header("location:cotizacion_general_materia_prima.php?id_ref=" . $idRef . "&N_cotizacion=" . $newCotiz . "&Str_nit=" . $Str_nit. "&Str_referencia_m=" . $Str_referencia_m );
  }
 break;
return '0'; 
}//fin switch
?>