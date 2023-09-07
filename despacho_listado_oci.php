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
$conexion = new ApptivaDB();

$currentPage = $_SERVER["PHP_SELF"];

$row_ano = $conexion->llenaSelect('anual','','ORDER BY anual DESC'); 

$row_mensual = $conexion->llenaSelect('mensual','','ORDER BY id_mensual DESC');

$row_dia = $conexion->llenaSelect('dias','','ORDER BY dia DESC');

$row_vendedores = $conexion->llenaSelect('vendedor','','ORDER BY nombre_vendedor ASC');
 
$row_numero = $conexion->llenaSelect('tbl_remisiones',"WHERE b_borrado_r='1'",'ORDER BY int_remision DESC'); 

$row_orden = $conexion->llenaListas('tbl_remisiones',"",'ORDER BY str_numero_oc_r DESC','DISTINCT str_numero_oc_r');  

$row_ref = $conexion->llenaListas('tbl_referencia',"",'ORDER BY CONVERT(cod_ref, SIGNED INTEGER) DESC','cod_ref');  

$row_cliente = $conexion->llenaSelect('cliente',"",'ORDER BY nombre_c ASC'); 
 
$maxRows_registros = 20;
$pageNum_registros = 0;
if (isset($_GET['pageNum_registros'])) {
  $pageNum_registros = $_GET['pageNum_registros'];
}
$startRow_registros = $pageNum_registros * $maxRows_registros;

 
$int_remision = $_GET['int_remision'];
$str_numero= $_GET['str_numero'];
$id_c = $_GET['id_c'];
$cod_ref = $_GET['cod_ref'];
$estado_oc = $_GET['estado_oc'];
$estado_rd = $_GET['estado_rd'];
$anual=$_GET['fecha'];
$mes=$_GET['mensual'];
$dia = $_GET['dia'];
$fecha = $anual.'-'.$mes.'-'. $dia;
$vende = $_GET['vende'];

//Filtra todos vacios
$registros= $conexion->buscarListar("tbl_remisiones","*","ORDER BY int_remision DESC","",$maxRows_registros,$pageNum_registros,"where b_borrado_r='1'" );

if($str_numero == '0' && $id_c == '0'  && $cod_ref == '0' && $int_remision== '0' && $estado_oc== '0' && $estado_rd == '0' && $anual == '0' && $mes == '0' && $dia == '0' && $vende =='0')
{
  $registros= $conexion->buscarListar("tbl_remisiones","*","ORDER BY int_remision DESC","",$maxRows_registros,$pageNum_registros,"where b_borrado_r='1'" );
}

//Filtra todos llenos
if($str_numero != '0' && $id_c != '0' && $cod_ref != '0' && $int_remision!= '0' && $estado_oc > '0' && $estado_rd == '0' && $anual == '0' && $mes == '0' && $dia == '0' && $vende =='0')
{
  $registros = $conexion->buscarListar('tbl_orden_compra,tbl_remisiones',' tbl_remisiones.comprobante_file, tbl_remisiones.ciudad_pais,tbl_remisiones.str_transportador_r,tbl_remisiones.factura_r, tbl_remisiones.int_remision,tbl_remisiones.b_borrado_r,tbl_remisiones.str_numero_oc_r,
  tbl_remisiones.fecha_r,tbl_remisiones.str_guia_r,tbl_orden_compra.str_numero_oc,tbl_orden_compra.id_c_oc,tbl_orden_compra.b_estado_oc','ORDER BY tbl_remisiones.int_remision DESC','',$maxRows_registros,$pageNum_registros,"WHERE Tbl_remisiones.int_remision='$int_remision' AND Tbl_remisiones.b_borrado_r='1' AND Tbl_remisiones.str_numero_oc_r='$str_numero' and Tbl_items_ordenc.int_cod_ref_io = '$cod_ref' AND Tbl_remisiones.str_numero_oc_r=Tbl_orden_compra.str_numero_oc AND Tbl_orden_compra.id_c_oc='$id_c'
  AND Tbl_orden_compra.b_estado_oc='$estado_oc'"); 
}
//Filtra remision lleno
if($str_numero == '0' && $id_c == '0'  && $cod_ref == '0' && $int_remision!= '0' && $estado_oc== '0' && $estado_rd == '0' && $anual == '0' && $mes == '0' && $dia == '0' && $vende =='0')
{  
  $registros = $conexion->buscarListar('tbl_remisiones','tbl_remisiones.comprobante_file, ciudad_pais,str_transportador_r,factura_r,int_remision,str_numero_oc_r,fecha_r,str_guia_r,b_borrado_r','ORDER BY int_remision ASC',"",$maxRows_registros,$pageNum_registros,"WHERE int_remision='$int_remision' AND b_borrado_r='1'");

}

//Filtra ref lleno
if($str_numero == '0' && $id_c == '0'   && $cod_ref != '0' && $int_remision== '0' && $estado_oc== '0' && $estado_rd == '0' && $anual == '0' && $mes == '0' && $dia == '0' && $vende =='0')
{
  $registros = $conexion->buscarListar('tbl_orden_compra,tbl_remisiones,tbl_items_ordenc','tbl_remisiones.comprobante_file, tbl_remisiones.ciudad_pais,tbl_remisiones.str_transportador_r,tbl_remisiones.factura_r,tbl_remisiones.int_remision,tbl_remisiones.b_borrado_r,tbl_remisiones.str_numero_oc_r,tbl_items_ordenc.int_cod_ref_io,
  tbl_remisiones.fecha_r,tbl_remisiones.str_guia_r,tbl_orden_compra.str_numero_oc,tbl_orden_compra.b_estado_oc','ORDER BY Tbl_remisiones.int_remision DESC',"",$maxRows_registros,$pageNum_registros,"WHERE tbl_remisiones.str_numero_oc_r=tbl_orden_compra.str_numero_oc AND tbl_orden_compra.str_numero_oc = tbl_items_ordenc.str_numero_io AND tbl_items_ordenc.int_cod_ref_io = '$cod_ref' AND tbl_remisiones.b_borrado_r='1' "); 

}

//Filtra str_numero lleno
if($str_numero != '0' && $id_c == '0'  && $cod_ref == '0' && $int_remision== '0' && $estado_oc== '0' && $estado_rd == '0' && $anual == '0' && $mes == '0' && $dia == '0' && $vende =='0')
{
   $registros = $conexion->buscarListar('tbl_remisiones','tbl_remisiones.comprobante_file, ciudad_pais,str_transportador_r,factura_r,int_remision,str_numero_oc_r,fecha_r,str_guia_r,b_borrado_r','ORDER BY int_remision DESC','',$maxRows_registros,$pageNum_registros,"WHERE str_numero_oc_r='$str_numero' AND b_borrado_r='1'"); 
}


//Filtra  cliente lleno
if($str_numero == '0' && $id_c != '0' && $cod_ref == '0' && $int_remision== '0' && $estado_oc== '0' && $estado_rd == '0' && $anual == '0' && $mes == '0' && $dia == '0' && $vende =='0')
{
  $registros = $conexion->buscarListar('tbl_orden_compra,tbl_remisiones','tbl_remisiones.comprobante_file, tbl_remisiones.ciudad_pais,tbl_remisiones.str_transportador_r,tbl_remisiones.factura_r,tbl_remisiones.int_remision,tbl_remisiones.b_borrado_r,tbl_remisiones.str_numero_oc_r,
  tbl_remisiones.fecha_r,tbl_remisiones.str_guia_r,tbl_orden_compra.str_numero_oc,tbl_orden_compra.id_c_oc,tbl_orden_compra.b_estado_oc','ORDER BY  tbl_remisiones.fecha_r DESC','',$maxRows_registros,$pageNum_registros,"WHERE tbl_orden_compra.id_c_oc='$id_c' AND tbl_orden_compra.str_numero_oc=tbl_remisiones.str_numero_oc_r AND tbl_remisiones.b_borrado_r='1' AND b_borrado_r='1' "); 
 
}
//Filtra estado oc lleno
if($str_numero == '0' && $id_c == '0'  && $cod_ref == '0' && $int_remision== '0' && $estado_oc > '0' && $estado_rd == '0' && $anual == '0' && $mes == '0' && $dia == '0' && $vende =='0')
{

  $registros = $conexion->buscarListar('tbl_orden_compra,tbl_remisiones','tbl_remisiones.comprobante_file, tbl_remisiones.ciudad_pais,tbl_remisiones.str_transportador_r,tbl_remisiones.factura_r,tbl_remisiones.int_remision,tbl_remisiones.b_borrado_r,tbl_remisiones.str_numero_oc_r,
  tbl_remisiones.fecha_r,tbl_remisiones.str_guia_r,tbl_orden_compra.str_numero_oc,tbl_orden_compra.b_estado_oc ','ORDER BY tbl_remisiones.int_remision DESC','',$maxRows_registros,$pageNum_registros,"WHERE tbl_orden_compra.b_estado_oc='$estado_oc' AND tbl_orden_compra.str_numero_oc=tbl_remisiones.str_numero_oc_r and tbl_remisiones.b_borrado_r='1' "); 
 
}

//Filtra ref  y cliente llenos
if($str_numero == '0' && $id_c != '0'   && $cod_ref != '0' && $int_remision== '0' && $estado_oc== '0' && $estado_rd == '0' && $anual == '0' && $mes == '0' && $dia == '0' && $vende =='0')
{
  $registros = $conexion->buscarListar('tbl_orden_compra,tbl_remisiones,tbl_items_ordenc','tbl_remisiones.comprobante_file, tbl_remisiones.ciudad_pais,tbl_remisiones.str_transportador_r,tbl_remisiones.factura_r,tbl_remisiones.int_remision,tbl_remisiones.b_borrado_r,tbl_remisiones.str_numero_oc_r,tbl_items_ordenc.int_cod_ref_io,
  tbl_remisiones.fecha_r,tbl_remisiones.str_guia_r,tbl_orden_compra.str_numero_oc,tbl_orden_compra.b_estado_oc','ORDER BY tbl_remisiones.int_remision DESC','',$maxRows_registros,$pageNum_registros,"WHERE Tbl_remisiones.str_numero_oc_r=Tbl_orden_compra.str_numero_oc and Tbl_orden_compra.str_numero_oc = Tbl_items_ordenc.str_numero_io and Tbl_items_ordenc.int_cod_ref_io = '$cod_ref' and Tbl_orden_compra.id_c_oc='$id_c' and Tbl_remisiones.b_borrado_r='1'"); 
 
}

//Filtra remision y estado lleno
if($str_numero == '0' && $id_c == '0'  && $cod_ref == '0' && $int_remision!= '0' && $estado_oc > '0' && $estado_rd == '0' && $anual == '0' && $mes == '0' && $dia == '0' && $vende =='0')
{
  $registros = $conexion->buscarListar('tbl_orden_compra,tbl_remisiones','tbl_remisiones.comprobante_file, tbl_remisiones.ciudad_pais,tbl_remisiones.str_transportador_r,tbl_remisiones.factura_r,tbl_remisiones.int_remision,tbl_remisiones.b_borrado_r,tbl_remisiones.str_numero_oc_r,
  tbl_remisiones.fecha_r,tbl_remisiones.str_guia_r,tbl_orden_compra.str_numero_oc,tbl_orden_compra.b_estado_oc','ORDER BY int_remision DESC',"",$maxRows_registros,$pageNum_registros,"WHERE Tbl_remisiones.int_remision='$int_remision' AND Tbl_remisiones.b_borrado_r='1' AND Tbl_remisiones.str_numero_oc_r=Tbl_orden_compra.str_numero_oc AND
  Tbl_orden_compra.b_estado_oc='$estado_oc'");
 
}
//Filtra O.C. y estado lleno
if($str_numero != '0' && $id_c == '0'  && $cod_ref == '0' && $int_remision== '0' && $estado_oc > '0' && $estado_rd == '0' && $anual == '0' && $mes == '0' && $dia == '0' && $vende =='0')
{
  $registros = $conexion->buscarListar('tbl_orden_compra,tbl_remisiones','tbl_remisiones.comprobante_file, tbl_remisiones.ciudad_pais,tbl_remisiones.str_transportador_r,tbl_remisiones.factura_r,tbl_remisiones.int_remision,tbl_remisiones.b_borrado_r,tbl_remisiones.str_numero_oc_r,
  tbl_remisiones.fecha_r,tbl_remisiones.str_guia_r,tbl_orden_compra.str_numero_oc,tbl_orden_compra.b_estado_oc','ORDER BY int_remision DESC',"",$maxRows_registros,$pageNum_registros,"WHERE
  Tbl_remisiones.str_numero_oc_r='$str_numero' AND Tbl_remisiones.b_borrado_r='1' AND Tbl_remisiones.str_numero_oc_r=Tbl_orden_compra.str_numero_oc AND 
  Tbl_orden_compra.b_estado_oc='$estado_oc'");
 
}
//Filtra remision y O.C. lleno
if($str_numero != '0' && $id_c == '0'  && $cod_ref == '0' && $int_remision!= '0' && $estado_oc== '0' && $estado_rd == '0' && $anual == '0' && $mes == '0' && $dia == '0' && $vende =='0')
{
  $registros = $conexion->buscarListar('tbl_remisiones','tbl_remisiones.comprobante_file, ciudad_pais,str_transportador_r,factura_r,int_remision,b_borrado_r,str_numero_oc_r,fecha_r,str_guia_r','ORDER BY int_remision DESC',"",$maxRows_registros,$pageNum_registros,"WHERE int_remision='$int_remision' AND b_borrado_r='1' AND str_numero_oc_r='$str_numero'");
 
}
//Filtra remision, O.C. y estado lleno
if($str_numero != '0' && $id_c == '0'  && $cod_ref == '0' && $int_remision!= '0' && $estado_oc > '0' && $estado_rd == '0' && $anual == '0' && $mes == '0' && $dia == '0' && $vende =='0')
{
  $registros = $conexion->buscarListar('tbl_orden_compra,tbl_remisiones','tbl_remisiones.comprobante_file, tbl_remisiones.ciudad_pais,tbl_remisiones.str_transportador_r,tbl_remisiones.factura_r,tbl_remisiones.int_remision,tbl_remisiones.b_borrado_r,tbl_remisiones.str_numero_oc_r,
  tbl_remisiones.fecha_r,tbl_remisiones.str_guia_r,tbl_orden_compra.str_numero_oc,tbl_orden_compra.b_estado_oc','ORDER BY tbl_remisiones.int_remision DESC',"",$maxRows_registros,$pageNum_registros,"WHERE
  Tbl_remisiones.int_remision='$int_remision' AND Tbl_remisiones.b_borrado_r='1' AND  Tbl_remisiones.str_numero_oc_r='$str_numero' AND Tbl_remisiones.str_numero_oc_r=Tbl_orden_compra.str_numero_oc AND Tbl_orden_compra.b_estado_oc='$estado_oc'");

}
//Filtra remision y cliente llenos
if($str_numero == '0' && $id_c != '0' && $cod_ref == '0' && $int_remision!= '0' && $estado_oc== '0' && $estado_rd == '0' && $anual == '0' && $mes == '0' && $dia == '0' && $vende =='0')
{
  $registros = $conexion->buscarListar('tbl_orden_compra,tbl_remisiones','tbl_remisiones.comprobante_file, tbl_remisiones.ciudad_pais,tbl_remisiones.str_transportador_r,tbl_remisiones.factura_r,tbl_remisiones.int_remision,tbl_remisiones.b_borrado_r,tbl_remisiones.str_numero_oc_r,
  tbl_remisiones.fecha_r,tbl_remisiones.str_guia_r,tbl_orden_compra.str_numero_oc,tbl_orden_compra.id_c_oc','ORDER BY tbl_remisiones.int_remision DESC',"",$maxRows_registros,$pageNum_registros,"WHERE 
  Tbl_remisiones.int_remision='$int_remision' AND Tbl_remisiones.b_borrado_r='1' AND Tbl_remisiones.str_numero_oc_r=Tbl_orden_compra.str_numero_oc AND Tbl_orden_compra.id_c_oc='$id_c'");
 
}
//Filtra remision, cliente y estado llenos
if($str_numero == '0' && $id_c != '0' && $cod_ref == '0' && $int_remision!= '0' && $estado_oc > '0' && $estado_rd == '0' && $anual == '0' && $mes == '0' && $dia == '0' && $vende =='0')
{
  $registros = $conexion->buscarListar('tbl_orden_compra,tbl_remisiones','tbl_remisiones.comprobante_file, tbl_remisiones.ciudad_pais,tbl_remisiones.str_transportador_r,tbl_remisiones.factura_r,tbl_remisiones.int_remision,tbl_remisiones.b_borrado_r,tbl_remisiones.str_numero_oc_r,tbl_remisiones.fecha_r,
  tbl_remisiones.str_guia_r,tbl_orden_compra.str_numero_oc,tbl_orden_compra.b_estado_oc,tbl_orden_compra.id_c_oc','ORDER BY tbl_remisiones.int_remision DESC',"",$maxRows_registros,$pageNum_registros,"WHERE Tbl_remisiones.int_remision='$int_remision' AND Tbl_remisiones.b_borrado_r='1' AND  
  Tbl_remisiones.str_numero_oc_r=Tbl_orden_compra.str_numero_oc AND Tbl_orden_compra.b_estado_oc='$estado_oc' AND Tbl_orden_compra.id_c_oc='$id_c' ");

}
//Filtra remision, cliente y O.C llenos
if($str_numero != '0' && $id_c != '0'  && $cod_ref == '0' && $int_remision!= '0' && $estado_oc== '0' && $estado_rd == '0' && $anual == '0' && $mes == '0' && $dia == '0' && $vende =='0')
{
  $registros = $conexion->buscarListar('tbl_orden_compra,tbl_remisiones','tbl_remisiones.comprobante_file, tbl_remisiones.ciudad_pais,tbl_remisiones.str_transportador_r,tbl_remisiones.factura_r,tbl_remisiones.int_remision,tbl_remisiones.b_borrado_r,tbl_remisiones.str_numero_oc_r,tbl_remisiones.fecha_r,
  tbl_remisiones.str_guia_r,tbl_orden_compra.str_numero_oc,tbl_orden_compra.id_c_oc','ORDER BY tbl_remisiones.int_remision DESC',"",$maxRows_registros,$pageNum_registros,"WHERE Tbl_remisiones.int_remision='$int_remision' AND Tbl_remisiones.b_borrado_r='1' AND Tbl_remisiones.str_numero_oc_r='$str_numero' AND 
  Tbl_remisiones.str_numero_oc_r=Tbl_orden_compra.str_numero_oc AND Tbl_orden_compra.id_c_oc='$id_c' ");
 
}
//Filtra  FECHA sin dia
if($str_numero == '0' && $id_c == '0'  && $cod_ref == '0' && $int_remision == '0' && $estado_oc== '0' && $estado_rd == '0'  && $anual != '0' && $mes != '0' && $dia == '0' && $vende =='0')
{
  $registros = $conexion->buscarListar('tbl_orden_compra,tbl_remisiones','tbl_remisiones.comprobante_file, tbl_remisiones.ciudad_pais,tbl_remisiones.str_transportador_r,tbl_remisiones.factura_r,tbl_remisiones.int_remision,tbl_remisiones.b_borrado_r,tbl_remisiones.str_numero_oc_r,tbl_remisiones.fecha_r,
  tbl_remisiones.str_guia_r,tbl_orden_compra.str_numero_oc,tbl_orden_compra.id_c_oc','ORDER BY tbl_remisiones.int_remision DESC',"",$maxRows_registros,$pageNum_registros,"WHERE tbl_remisiones.b_borrado_r='1' and year(tbl_remisiones.fecha_r) = '$anual' and month(tbl_remisiones.fecha_r) = '$mes' and  tbl_remisiones.str_numero_oc_r=tbl_orden_compra.str_numero_oc");
 
}
//Filtra  FECHA
if($str_numero == '0' && $id_c == '0'  && $cod_ref == '0' && $int_remision == '0' && $estado_oc== '0' && $estado_rd == '0'  && $anual != '0' && $mes != '0' && $dia != '0' && $vende =='0')
{
  $registros = $conexion->buscarListar('tbl_orden_compra,tbl_remisiones','tbl_remisiones.comprobante_file, tbl_remisiones.ciudad_pais,tbl_remisiones.str_transportador_r,tbl_remisiones.factura_r,tbl_remisiones.int_remision,tbl_remisiones.b_borrado_r,tbl_remisiones.str_numero_oc_r,tbl_remisiones.fecha_r,
  tbl_remisiones.str_guia_r,tbl_orden_compra.str_numero_oc,tbl_orden_compra.id_c_oc','ORDER BY tbl_remisiones.int_remision DESC',"",$maxRows_registros,$pageNum_registros,"WHERE tbl_remisiones.b_borrado_r='1' and tbl_remisiones.fecha_r =  '$fecha' and  tbl_remisiones.str_numero_oc_r=tbl_orden_compra.str_numero_oc"); 
}

//Filtra Estado o.c, FECHA
if($str_numero == '0' && $id_c == '0'  && $cod_ref == '0' && $int_remision == '0' && $estado_oc!= '0' && $estado_rd == '0'  && $anual != '0' && $mes != '0' && $dia != '0' && $vende =='0')
{
  $registros = $conexion->buscarListar('tbl_orden_compra,tbl_remisiones','DISTINCT tbl_remisiones.comprobante_file, tbl_remisiones.ciudad_pais,tbl_remisiones.str_transportador_r,tbl_remisiones.factura_r,tbl_remisiones.int_remision,tbl_remisiones.b_borrado_r,tbl_remisiones.str_numero_oc_r,tbl_remisiones.fecha_r,
  tbl_remisiones.str_guia_r,tbl_orden_compra.str_numero_oc,tbl_orden_compra.id_c_oc','ORDER BY tbl_remisiones.int_remision DESC',"",$maxRows_registros,$pageNum_registros,"WHERE tbl_remisiones.b_borrado_r='1' and tbl_orden_compra.b_estado_oc='$estado_oc' and tbl_remisiones.fecha_r =  '$fecha' and  tbl_remisiones.str_numero_oc_r=tbl_orden_compra.str_numero_oc"); 
}

//Filtra Estado y AÑO, MES 
if($str_numero == '0' && $id_c == '0'   && $cod_ref == '0' && $int_remision== '0' && $estado_oc!= '0' && $estado_rd == '0' && $anual != '0' && $mes != '0' && $dia == '0' && $vende =='0')
{
  
  $registros = $conexion->buscarListar('tbl_orden_compra,tbl_remisiones','DISTINCT tbl_remisiones.comprobante_file, tbl_remisiones.ciudad_pais,tbl_remisiones.str_transportador_r,tbl_remisiones.factura_r,tbl_remisiones.int_remision,tbl_remisiones.b_borrado_r,tbl_remisiones.str_numero_oc_r,tbl_remisiones.fecha_r,
  tbl_remisiones.str_guia_r,tbl_orden_compra.str_numero_oc,tbl_orden_compra.id_c_oc','ORDER BY tbl_remisiones.int_remision DESC',"",$maxRows_registros,$pageNum_registros,"WHERE tbl_remisiones.b_borrado_r='1' and year(tbl_remisiones.fecha_r) = '$anual' and month(tbl_remisiones.fecha_r) = '$mes' AND  tbl_orden_compra.b_estado_oc='$estado_oc' and  tbl_remisiones.str_numero_oc_r=tbl_orden_compra.str_numero_oc");
 
}

//Filtra Estado y AÑO, MES, VENDEDOR
if($str_numero == '0' && $id_c == '0'   && $cod_ref == '0' && $int_remision== '0' && $estado_oc!= '0' && $estado_rd == '0' && $anual != '0' && $mes != '0' && $dia == '0' && $vende !='0')
{
  if($vende!='0'){
    $elvendedor = $conexion->llenarCampos("vendedor","WHERE id_vendedor=$vende","","nombre_vendedor");
    $vendedor = $elvendedor['nombre_vendedor'];
  }
  $registros = $conexion->buscarListar('tbl_orden_compra,tbl_remisiones','DISTINCT tbl_remisiones.comprobante_file, tbl_remisiones.ciudad_pais,tbl_remisiones.str_transportador_r,tbl_remisiones.factura_r,tbl_remisiones.int_remision,tbl_remisiones.b_borrado_r,tbl_remisiones.str_numero_oc_r,tbl_remisiones.fecha_r,
  tbl_remisiones.str_guia_r,tbl_orden_compra.str_numero_oc,tbl_orden_compra.id_c_oc','ORDER BY tbl_remisiones.int_remision DESC',"",$maxRows_registros,$pageNum_registros,"WHERE tbl_remisiones.b_borrado_r='1' and year(tbl_remisiones.fecha_r) = '$anual' and month(tbl_remisiones.fecha_r) = '$mes' AND tbl_orden_compra.b_estado_oc='$estado_oc' AND tbl_orden_compra.str_elaboro_oc='$vendedor' AND  tbl_remisiones.str_numero_oc_r=tbl_orden_compra.str_numero_oc");
  
}

//Filtra VENDEDOR
 if($str_numero == '0' && $id_c == '0'  && $cod_ref == '0' && $int_remision == '0' && $estado_oc== '0' && $estado_rd == '0'  && $anual == '0' && $mes == '0' && $dia == '0' && $vende !='0')
{
  if($vende!='0'){
    $elvendedor = $conexion->llenarCampos("vendedor","WHERE id_vendedor=$vende","","nombre_vendedor");
    $vendedor = $elvendedor['nombre_vendedor'];
  }
  $registros = $conexion->buscarListar('tbl_orden_compra,tbl_remisiones','DISTINCT tbl_remisiones.comprobante_file, tbl_remisiones.ciudad_pais,tbl_remisiones.str_transportador_r,tbl_remisiones.factura_r,tbl_remisiones.int_remision,tbl_remisiones.b_borrado_r,tbl_remisiones.str_numero_oc_r,tbl_remisiones.fecha_r,tbl_remisiones.str_guia_r,tbl_orden_compra.str_numero_oc,tbl_orden_compra.id_c_oc','ORDER BY tbl_remisiones.int_remision DESC',"",$maxRows_registros,$pageNum_registros,"WHERE tbl_remisiones.b_borrado_r='1' and tbl_orden_compra.str_elaboro_oc='$vendedor' and tbl_remisiones.str_numero_oc_r=tbl_orden_compra.str_numero_oc");  
} 
//Filtra FECHA y vendedor
if($str_numero == '0' && $id_c == '0'  && $cod_ref == '0' && $int_remision == '0' && $estado_oc== '0' && $estado_rd == '0'  && $anual != '0' && $mes != '0' && $dia != '0' && $vende !='0')
{
  if($vende!='0'){
    $elvendedor = $conexion->llenarCampos("vendedor","WHERE id_vendedor=$vende","","nombre_vendedor");
    $vendedor = $elvendedor['nombre_vendedor'];
  }
  $registros = $conexion->buscarListar('tbl_orden_compra,tbl_remisiones ','DISTINCT tbl_remisiones.comprobante_file, tbl_remisiones.ciudad_pais,tbl_remisiones.str_transportador_r,tbl_remisiones.factura_r,tbl_remisiones.int_remision,tbl_remisiones.b_borrado_r,tbl_remisiones.str_numero_oc_r,tbl_remisiones.fecha_r,
  tbl_remisiones.str_guia_r,tbl_orden_compra.str_numero_oc,tbl_orden_compra.id_c_oc','ORDER BY tbl_remisiones.int_remision DESC',"",$maxRows_registros,$pageNum_registros,"WHERE tbl_remisiones.b_borrado_r='1' and tbl_remisiones.fecha_r =  '$fecha'  and tbl_orden_compra.str_elaboro_oc='$vendedor' and tbl_remisiones.str_numero_oc_r=tbl_orden_compra.str_numero_oc ");  

}

//Filtra FECHA, vende lleno
/*if($str_numero == '0' && $id_c == '0'   && $cod_ref == '0' && $int_remision== '0' && $estado_oc== '0' && $estado_rd == '0' && $anual != '0' && $mes != '0' && $dia != '0' && $vende !='0')
{
  $registros = $conexion->buscarListar('tbl_orden_compra,tbl_remisiones,tbl_items_ordenc','*','ORDER BY tbl_remisiones.int_remision DESC',"",$maxRows_registros,$pageNum_registros,"WHERE tbl_remisiones.b_borrado_r='1' and tbl_remisiones.fecha_r = '$fecha' and tbl_items_ordenc.int_vendedor_io = '$vende' and tbl_remisiones.str_numero_oc_r=tbl_orden_compra.str_numero_oc");  
 
}*/
//Filtra ref y vende lleno
if($str_numero == '0' && $id_c == '0'   && $cod_ref != '0' && $int_remision== '0' && $estado_oc== '0' && $estado_rd == '0' && $anual == '0' && $mes == '0' && $dia == '0' && $vende !='0')
{
  if($vende!='0'){
    $elvendedor = $conexion->llenarCampos("vendedor","WHERE id_vendedor=$vende","","nombre_vendedor");
    $vendedor = $elvendedor['nombre_vendedor'];
  }
  $registros = $conexion->buscarListar('tbl_orden_compra,tbl_remisiones,tbl_items_ordenc','DISTINCT tbl_remisiones.comprobante_file, tbl_remisiones.ciudad_pais,tbl_remisiones.str_transportador_r,tbl_remisiones.factura_r,tbl_remisiones.int_remision,tbl_remisiones.b_borrado_r,tbl_remisiones.str_numero_oc_r,tbl_items_ordenc.int_cod_ref_io,
  tbl_remisiones.fecha_r,tbl_remisiones.str_guia_r,tbl_orden_compra.str_numero_oc,tbl_orden_compra.b_estado_oc','ORDER BY tbl_remisiones.int_remision DESC',"",$maxRows_registros,$pageNum_registros,"WHERE tbl_remisiones.str_numero_oc_r=tbl_orden_compra.str_numero_oc and tbl_orden_compra.str_numero_oc = tbl_items_ordenc.str_numero_io and tbl_items_ordenc.int_cod_ref_io = '$cod_ref' and tbl_orden_compra.str_elaboro_oc='$vendedor' and tbl_remisiones.b_borrado_r='1'");   

}
//Filtra ref y FECHA, vende lleno
if($str_numero == '0' && $id_c == '0'   && $cod_ref != '0' && $int_remision== '0' && $estado_oc== '0' && $estado_rd == '0' && $anual != '0' && $mes != '0' && $dia != '0' && $vende !='0')
{
  if($vende!='0'){
    $elvendedor = $conexion->llenarCampos("vendedor","WHERE id_vendedor=$vende","","nombre_vendedor");
    $vendedor = $elvendedor['nombre_vendedor'];
  }
  $registros = $conexion->buscarListar('tbl_orden_compra,tbl_remisiones,tbl_items_ordenc','DISTINCT tbl_remisiones.comprobante_file, tbl_remisiones.ciudad_pais,tbl_remisiones.str_transportador_r,tbl_remisiones.factura_r,tbl_remisiones.int_remision,tbl_remisiones.b_borrado_r,tbl_remisiones.str_numero_oc_r,tbl_items_ordenc.int_cod_ref_io,
  tbl_remisiones.fecha_r,tbl_remisiones.str_guia_r,tbl_orden_compra.str_numero_oc,tbl_orden_compra.b_estado_oc','ORDER BY tbl_remisiones.int_remision DESC',"",$maxRows_registros,$pageNum_registros,"WHERE tbl_remisiones.str_numero_oc_r=tbl_orden_compra.str_numero_oc and tbl_orden_compra.str_numero_oc = tbl_items_ordenc.str_numero_io and tbl_remisiones.fecha_r = '$fecha' and tbl_items_ordenc.int_cod_ref_io = '$cod_ref' and tbl_orden_compra.str_elaboro_oc='$vendedor' and tbl_remisiones.b_borrado_r='1'");  
}

//Filtra ref y AÑO, vende lleno
if($str_numero == '0' && $id_c == '0'   && $cod_ref != '0' && $int_remision== '0' && $estado_oc== '0' && $estado_rd == '0' && $anual != '0' && $mes == '0' && $dia == '0' && $vende !='0')
{
  if($vende!='0'){
    $elvendedor = $conexion->llenarCampos("vendedor","WHERE id_vendedor=$vende","","nombre_vendedor");
    $vendedor = $elvendedor['nombre_vendedor'];
  }
  $registros = $conexion->buscarListar('tbl_orden_compra,tbl_remisiones,tbl_items_ordenc','DISTINCT tbl_remisiones.comprobante_file, tbl_remisiones.ciudad_pais,tbl_remisiones.str_transportador_r,tbl_remisiones.factura_r,tbl_remisiones.int_remision,tbl_remisiones.b_borrado_r,tbl_remisiones.str_numero_oc_r,tbl_items_ordenc.int_cod_ref_io,
  tbl_remisiones.fecha_r,tbl_remisiones.str_guia_r,tbl_orden_compra.str_numero_oc,tbl_orden_compra.b_estado_oc','ORDER BY tbl_remisiones.int_remision DESC',"",$maxRows_registros,$pageNum_registros,"WHERE Tbl_remisiones.str_numero_oc_r=Tbl_orden_compra.str_numero_oc and Tbl_orden_compra.str_numero_oc = Tbl_items_ordenc.str_numero_io  AND YEAR(Tbl_remisiones.fecha_r) = '$anual' AND  Tbl_items_ordenc.int_cod_ref_io = '$cod_ref' AND tbl_orden_compra.str_elaboro_oc='$vendedor' AND Tbl_remisiones.b_borrado_r='1'"); 
 
}
//Filtra ref y MES, vende lleno
if($str_numero == '0' && $id_c == '0'   && $cod_ref != '0' && $int_remision== '0' && $estado_oc== '0' && $estado_rd == '0' && $anual == '0' && $mes != '0' && $dia == '0' && $vende !='0')
{
  if($vende!='0'){
    $elvendedor = $conexion->llenarCampos("vendedor","WHERE id_vendedor=$vende","","nombre_vendedor");
    $vendedor = $elvendedor['nombre_vendedor'];
  }
  $registros = $conexion->buscarListar('tbl_orden_compra,tbl_remisiones,tbl_items_ordenc','DISTINCT tbl_remisiones.comprobante_file, tbl_remisiones.ciudad_pais,tbl_remisiones.str_transportador_r,tbl_remisiones.factura_r,tbl_remisiones.int_remision,tbl_remisiones.b_borrado_r,tbl_remisiones.str_numero_oc_r,tbl_items_ordenc.int_cod_ref_io,
  tbl_remisiones.fecha_r,tbl_remisiones.str_guia_r,tbl_orden_compra.str_numero_oc,tbl_orden_compra.b_estado_oc','ORDER BY tbl_remisiones.int_remision DESC',"",$maxRows_registros,$pageNum_registros,"WHERE Tbl_remisiones.str_numero_oc_r=Tbl_orden_compra.str_numero_oc and Tbl_orden_compra.str_numero_oc = Tbl_items_ordenc.str_numero_io  AND MONTH(Tbl_remisiones.fecha_r) = '$mes' AND  Tbl_items_ordenc.int_cod_ref_io = '$cod_ref' and tbl_orden_compra.str_elaboro_oc='$vendedor' AND Tbl_remisiones.b_borrado_r='1'"); 

}
//Filtra ref y AÑO, MES, vende lleno
if($str_numero == '0' && $id_c == '0'   && $cod_ref != '0' && $int_remision== '0' && $estado_oc== '0' && $estado_rd == '0' && $anual != '0' && $mes != '0' && $dia == '0' && $vende !='0')
{
  if($vende!='0'){
    $elvendedor = $conexion->llenarCampos("vendedor","WHERE id_vendedor=$vende","","nombre_vendedor");
    $vendedor = $elvendedor['nombre_vendedor'];
  }
  $registros = $conexion->buscarListar('tbl_orden_compra,tbl_remisiones,tbl_items_ordenc','DISTINCT tbl_remisiones.comprobante_file, tbl_remisiones.ciudad_pais,tbl_remisiones.str_transportador_r,tbl_remisiones.factura_r,tbl_remisiones.int_remision,tbl_remisiones.b_borrado_r,tbl_remisiones.str_numero_oc_r,tbl_items_ordenc.int_cod_ref_io,
  tbl_remisiones.fecha_r,tbl_remisiones.str_guia_r,tbl_orden_compra.str_numero_oc,tbl_orden_compra.b_estado_oc','ORDER BY tbl_remisiones.int_remision DESC',"",$maxRows_registros,$pageNum_registros,"WHERE Tbl_remisiones.str_numero_oc_r=Tbl_orden_compra.str_numero_oc and Tbl_orden_compra.str_numero_oc = Tbl_items_ordenc.str_numero_io  AND YEAR(Tbl_remisiones.fecha_r) = '$anual' AND  MONTH(Tbl_remisiones.fecha_r) = '$mes' AND  Tbl_items_ordenc.int_cod_ref_io = '$cod_ref' AND tbl_orden_compra.str_elaboro_oc='$vendedor' AND Tbl_remisiones.b_borrado_r='1'");  
}
//Filtra ref y AÑO, MES 
if($str_numero == '0' && $id_c == '0'   && $cod_ref != '0' && $int_remision== '0' && $estado_oc== '0' && $estado_rd == '0' && $anual != '0' && $mes != '0' && $dia == '0' && $vende =='0')
{
  if($vende!='0'){
    $elvendedor = $conexion->llenarCampos("vendedor","WHERE id_vendedor=$vende","","nombre_vendedor");
    $vendedor = $elvendedor['nombre_vendedor'];
  }
  $registros = $conexion->buscarListar('tbl_orden_compra,tbl_remisiones,tbl_items_ordenc','DISTINCT tbl_remisiones.comprobante_file, tbl_remisiones.ciudad_pais,tbl_remisiones.str_transportador_r,tbl_remisiones.factura_r,tbl_remisiones.int_remision,tbl_remisiones.b_borrado_r,tbl_remisiones.str_numero_oc_r,tbl_items_ordenc.int_cod_ref_io,
  tbl_remisiones.fecha_r,tbl_remisiones.str_guia_r,tbl_orden_compra.str_numero_oc,tbl_orden_compra.b_estado_oc','ORDER BY tbl_remisiones.int_remision DESC',"",$maxRows_registros,$pageNum_registros,"WHERE Tbl_remisiones.str_numero_oc_r=Tbl_orden_compra.str_numero_oc and Tbl_orden_compra.str_numero_oc = Tbl_items_ordenc.str_numero_io  AND YEAR(Tbl_remisiones.fecha_r) = '$anual' AND  MONTH(Tbl_remisiones.fecha_r) = '$mes' AND  Tbl_items_ordenc.int_cod_ref_io = '$cod_ref' AND tbl_items_ordenc.int_cod_ref_io = '$cod_ref' AND Tbl_remisiones.b_borrado_r='1'");  

}



//Filtra remision detalle pendientes
if($estado_rd == '1')
{

  $registros = $conexion->buscarListar('tbl_remisiones,tbl_remision_detalle','tbl_remisiones.comprobante_file, tbl_remisiones.ciudad_pais,tbl_remisiones.str_transportador_r,tbl_remisiones.factura_r,tbl_remisiones.int_remision,tbl_remisiones.str_numero_oc_r,tbl_remisiones.fecha_r,tbl_remisiones.str_guia_r,tbl_remisiones.b_borrado_r','ORDER BY tbl_remision_detalle.int_remision_r_rd DESC','',$maxRows_registros,$pageNum_registros,"WHERE tbl_remisiones.int_remision=tbl_remision_detalle.int_remision_r_rd and tbl_remision_detalle.estado_rd = '$estado_rd'"); 
}
/*mysql_select_db($database_conexion1, $conexion1);
$query_limit_remision = sprintf("%s LIMIT %d, %d", $registros, $startRow_remision, $maxRows_remision);
$remision = mysql_query($query_limit_remision, $conexion1) or die(mysql_error());
$row_remision = mysql_fetch_assoc($remision);*/

if (isset($_GET['totalRows_registros'])) {
  $totalRows_registros = $_GET['totalRows_registros'];
} else {
  $totalRows_registros = $conexion->conteo('tbl_remisiones'); 
} 
$totalPages_registros = ceil($totalRows_registros/$maxRows_registros)-1;
 
$queryString_registros = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_registros") == false && 
        stristr($param, "totalRows_registros") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_registros = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_registros = sprintf("&totalRows_registros=%d%s", $totalRows_registros, $queryString_registros);


 
$row_alertas_rojo = $conexion->llenaListas('tbl_orden_compra,tbl_remisiones',"WHERE tbl_orden_compra.str_numero_oc = tbl_remisiones.str_numero_oc_r and (tbl_remisiones.comprobante_file is  null or tbl_remisiones.comprobante_file ='')   and tbl_orden_compra.b_estado_oc in('3','4') and tbl_orden_compra.comprobante_ent='SI'" ,'ORDER BY CONVERT(tbl_orden_compra.str_numero_oc, SIGNED INTEGER) ASC',"DISTINCT tbl_remisiones.int_remision, tbl_remisiones.str_numero_oc_r, tbl_remisiones.comprobante_file ");//and tbl_orden_compra.entrega_fac='NO'

$row_alertas_verde = $conexion->llenaListas('tbl_orden_compra,tbl_remisiones',"WHERE tbl_orden_compra.str_numero_oc = tbl_remisiones.str_numero_oc_r and (tbl_remisiones.comprobante_file is NOT null or tbl_remisiones.comprobante_file ='')  and tbl_orden_compra.b_estado_oc in('3','4') " ,'ORDER BY CONVERT(tbl_orden_compra.str_numero_oc, SIGNED INTEGER) ASC',"DISTINCT tbl_remisiones.int_remision, tbl_remisiones.str_numero_oc_r, tbl_remisiones.comprobante_file ");//and tbl_orden_compra.entrega_fac='NO' 

/*if (isset($_GET['totalRows_ordenes_compra'])) {
  $totalRows_registros = $_GET['totalRows_registros'];
} else {
  $all_registros = mysql_query($query_registros);
  $totalRows_registros = mysql_num_rows($all_registros);
}
$totalPages_registros = ceil($totalRows_registros/$maxRows_registros)-1;*/

 

?>
<html>
<head>
  <title>SISADGE AC &amp; CIA</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link href="css/formato.css" rel="stylesheet" type="text/css" />
  <link href="css/general.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="js/listado.js"></script>


  <script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
  <link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">
  <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
  <script type="text/javascript" src="AjaxControllers/js/updates.js"></script>
  <script type="text/javascript" src="js/formato.js"></script>

  <!-- css Bootstrap-->
  <link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  
  <!-- desde aqui para listados nuevos -->
  <link rel="stylesheet" type="text/css" href="css/desplegable.css" />
  <link rel="stylesheet" type="text/css" href="css/general.css"/>

  <!-- jquery -->
  <script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
  <script src="//code.jquery.com/jquery-1.11.2.min.js"></script> 
  <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
  <!-- select2 -->
  <link href="select2/css/select2.min.css" rel="stylesheet"/>
  <script src="select2/js/select2.min.js"></script>

  <!-- jquery -->
  <script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
  <script src="//code.jquery.com/jquery-1.11.2.min.js"></script> 
  <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
  <!-- select2 -->
  <link href="select2/css/select2.min.css" rel="stylesheet"/>
  <script src="select2/js/select2.min.js"></script>

</head>
<body>
  <script>
      $(document).ready(function() { $(".combos").select2(); });
  </script>
  <div align="center">
    <table style="width: 80%"><!-- id="tabla1" -->
      <tr>
       <td align="center">
         <div class="row-fluid">
           <div class="span8 offset2"> <!--span8 offset2   esto da el tamaño pequeño -->
             <div class="panel panel-primary">
              <div class="panel-heading" align="left" ></div><!--color azul-->
              <div class="row" >
                  <div class="span12">&nbsp;&nbsp;&nbsp; <img src="images/cabecera.jpg"></div> 
              </div>
              <div class="panel-heading" align="left" ></div><!--color azul-->
               <div id="cabezamenu">
                <ul id="menuhorizontal">
                  <li id="nombreusuario" ><?php echo $_SESSION['Usuario']; ?></li>
                  <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                  <li><a href="menu.php">MENU PRINCIPAL</a></li>
                  <li><a href="costos_generales.php">COSTOS GENERALES</a></li>
                </ul>
             </div> 
             <div class="panel-body">
               <br> 
               <div class="container">
                <div class="row">
                  <div class="span12"> 
             </div>
           </div>
              <form action="despacho_listado_oci.php" method="get" name="consulta">
                <table >
                  <tr>
                    <td id="titulo2">REMISIONES INACTIVAS - ELIMINADAS</td>
                  </tr>
                  <tr>
                    <td id="fuente2"><select class="combos" name="int_remision" id="int_remision" style="width:120px">
                      <option value="0"<?php if (!(strcmp(0, $_GET['int_remision']))) {echo "selected=\"selected\"";} ?>>Remision</option>
                       <?php foreach ($row_numero as $row_numero) { ?>
                        <option value="<?php echo $row_numero['int_remision']?>"<?php if (!(strcmp($row_numero['int_remision'], $_GET['int_remision']))) {echo "selected=\"selected\"";} ?>><?php echo $row_numero['int_remision'];?>
                        </option>
                        <?php } ?>
                    </select>
                    <select class="combos" name="str_numero" id="str_numero" style="width:120px">
                      <option value="0"<?php if (!(strcmp(0, $_GET['str_numero']))) {echo "selected=\"selected\"";} ?>>O.C.</option>
                      <?php foreach ($row_orden as $row_orden) { ?>
                        <option value="<?php echo $row_orden['str_numero_oc_r']?>"<?php if (!(strcmp($row_orden['str_numero_oc_r'], $_GET['str_numero']))) {echo "selected=\"selected\"";} ?>><?php echo $row_orden['str_numero_oc_r'];?>
                        </option>
                        <?php } ?>
                    </select>

                    <select class="combos" name="cod_ref" id="cod_ref" style="width:120px">
                      <option value="0"<?php if (!(strcmp(0, $_GET['cod_ref']))) {echo "selected=\"selected\"";} ?>>REF.</option>
                      <?php foreach ($row_ref as $row_ref) { ?>
                        <option value="<?php echo $row_ref['cod_ref']?>"<?php if (!(strcmp($row_ref['cod_ref'], $_GET['cod_ref']))) {echo "selected=\"selected\"";} ?>>
                          <?php echo $row_ref['cod_ref'];?>
                        </option>
                        <?php } ?>
                    </select>

                    <select class="combos" name="id_c" id="id_c" style="width:200px">
                      <option value="0"<?php if (!(strcmp(0, $_GET['id_c']))) {echo "selected=\"selected\"";} ?>>Cliente</option>
                      <?php foreach ($row_cliente as $row_cliente) { ?>
                        <option value="<?php echo $row_cliente['id_c']?>"<?php if (!(strcmp($row_cliente['id_c'], $_GET['id_c']))) {echo "selected=\"selected\"";} ?>><?php $cad =  utf8_encode($row_cliente['nombre_c']); echo $cad;?>
                        </option>
                        <?php } ?>
                    </select>
                    <select class="combos" name="estado_oc" id="estado_oc" style="width:180px">
                      <option value="0"<?php if (!(strcmp(0, $_GET['estado_oc']))) {echo "selected=\"selected\"";} ?>>Estado O.C</option>
                      <option value="3"<?php if (!(strcmp(3, $_GET['estado_oc']))) {echo "selected=\"selected\"";} ?>>Remisionada</option>
                      <option value="4"<?php if (!(strcmp(4, $_GET['estado_oc']))) {echo "selected=\"selected\"";} ?>>Fact. Parcial</option>
                      <option value="5"<?php if (!(strcmp(5, $_GET['estado_oc']))) {echo "selected=\"selected\"";} ?>>Fact. Total</option>
                      <option value="6"<?php if (!(strcmp(6, $_GET['estado_oc']))) {echo "selected=\"selected\"";} ?>>Muestras reposicion</option>

                    </option>
                  </select>  
                  <select class="combos" name="estado_rd" id="estado_rd" style="width:120px">
                    <option value="0"<?php if (!(strcmp(0, $row_remision_detalle['estado_rd']))) {echo "selected=\"selected\"";} ?> selected>Despachado</option>
                    <option value="1"<?php if (!(strcmp(1, $row_remision_detalle['estado_rd']))) {echo "selected=\"selected\"";} ?>>Pendiente</option>
                  </select>
                  <select class="combos" name="fecha" id="fecha" style="width:120px"> 
                    <option value="0"<?php if (!(strcmp("", $_GET['fecha']))) {echo "selected=\"selected\"";} ?>>ANUAL</option>
                    <?php foreach ($row_ano as $row_ano) { ?>
                      <option value="<?php echo $row_ano['anual']?>"<?php if (!(strcmp($row_ano['anual'], $_GET['fecha']))) {echo "selected=\"selected\"";} ?>><?php echo $row_ano['anual']?></option>
                      <?php } ?>
                  </select>
                  <select class="combos" name="mensual" id="mensual" style="width:120px">
                    <option value="0"<?php if (!(strcmp("", $_GET['mensual']))) {echo "selected=\"selected\"";} ?>>MENSUAL</option>
                    <?php foreach ($row_mensual as $row_mensual) { ?>
                      <option value="<?php echo $row_mensual['id_mensual']?>"<?php if (!(strcmp($row_mensual['id_mensual'], $_GET['mensual']))) {echo "selected=\"selected\"";} ?>><?php echo $row_mensual['mensual']?></option>
                      <?php } ?>
                  </select>
                  <!--dias -->

                </select>
                <select class="combos" name="dia" id="dia" style="width:60px">
                  <option value="0"<?php if (!(strcmp("", $_GET['dia']))) {echo "selected=\"selected\"";} ?>>DIA</option>
                  <?php foreach ($row_dia as $row_dia) { ?>
                    <option value="<?php echo $row_dia['dia']?>"<?php if (!(strcmp($row_dia['dia'], $_GET['dia']))) {echo "selected=\"selected\"";} ?>><?php echo $row_dia['dia']?></option>
                    <?php } ?>
                </select>
                <select class="combos" name="vende" id="vende" style="width:90px">
                  <option value="0"<?php if (!(strcmp("0", $_GET['vende']))) {echo "selected=\"selected\"";} ?>>Vendedor</option>
                  <?php foreach ($row_vendedores as $row_vendedores) { ?>
                    <option value="<?php echo $row_vendedores['id_vendedor']?>"<?php if (!(strcmp($row_vendedores['id_vendedor'], $_GET['vende']))) {echo "selected=\"selected\"";} ?>><?php echo $row_vendedores['nombre_vendedor']?></option>
                    <?php } ?>
                </select>
                <br>
                <br>
                <input type="submit" class="botonGMini" style='width:90px; height:25px' name="Submit" value="FILTRO" />
                <input type="button" class="botonDel" style='width:140px; height:25px' id="excel" name="excel" value="Descarga Excel" onclick="myFunction()">
                <input type="button" class="botonDel" style='width:140px; height:25px' id="excel" name="excel" value="Descarga Excel Brinks" onclick="myFunction2()"> <br><br>
              </td> 
            </tr>
          </table>
        </form>
          </div>
        </div>
        <form action="delete_listado.php" method="get" name="seleccion">
          <table id="tabla1">
            <tr>
              <td colspan="2" id="dato1"><input name="update" type="hidden" id="update" value="activaRem" /> </td>
                <td colspan="2"><?php if (isset($_GET['id'])) {$id= $_GET['id'];}else{$id= '';} 
                if($id >= '1') { ?> 
                <div id="acceso1"> <?php echo "SE REACTIVO CORRECTAMENTE"; ?> </div>
                <?php }
                if($id == '0') { ?>
                <div id="numero1"> <?php echo "NO SE REACTIVO"; ?> </div>
                <?php }?>
                </td>
                <td colspan="2" id="dato3">
                  <a href="despacho_listado1_oc.php"><img src="images/A.gif" alt="REMISIONES ACTIVAS"title="REMISIONES ACTIVAS" border="0" style="cursor:hand;"/></a>
                  <a href="despacho_listado1_oc.php"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a>
                </td>
              </tr>
                <tr>
                  <td style="text-align: right;"  colspan="12" id="dato1"> 
                    <img src="images/facturado.png" alt="YA TIENE FACTURA" title="YA TIENE FACTURA" border="0" style="cursor:hand;" width="20" height="18" />Ya Tiene Factura/Comprobante <img src="images/falta8.gif" alt="SIN AUTORIZAR" title="SIN AUTORIZAR" border="0" style="cursor:hand;" width="20" height="18" />Sin Factura 
                  </td>
                </tr>
                <tr>
                      <td style="text-align: right;"  colspan="5" id="dato1"> 
                        <div class="alert alert-danger divScroll" role="alert" style="text-align: left;">
                          <span>SIN COMPROBANTE:</span><br>
                          <?php  foreach($row_alertas_rojo as $row_rojo ) { ?>
                            <?php echo "* Remision: " .$row_rojo['int_remision'] ."* O.C ".$row_rojo['str_numero_oc_r']. " sin comprobante de entrega" ."<br>" ; ?>
                          <?php } ?>
                        </div>
                      </td>
                      <td style="text-align: right;"  colspan="7" id="dato1"> 
                        <div class="alert alert-success divScroll" role="alert" style="text-align: left;" >
                          <span>CON COMPROBANTE:</span><br>
                          <?php  foreach($row_alertas_verde as $row_verde ) { ?>
                            <?php echo "* Remision: " .$row_verde['int_remision'] . "* ".$row_verde['str_numero_oc_r']. " con comprobante de entrega" ."<br>" ; ?>
                          <?php } ?>
                        </div> 
                      </td>
                    </tr>
                <tr id="tr1">
                  <tr>
                    <td><input name="Input" class="botonGMini" type="submit" value="Activar"/></td>
                  </tr>
                  <td id="fuente2"><input name="chulo1" type="checkbox" onClick="if(seleccion.chulo1.checked) { seleccionar_todo() } else{ deseleccionar_todo() } "/></td>
                  <td id="titulo4" nowrap="nowrap" >N° REMISION</td>
                  <td id="titulo4">N° O.C.</td>
                  <td id="titulo4">CLIENTE</td>
                  <td id="titulo4">CIUDAD</td>
                  <td id="titulo4">TRANSPOR.</td>
                  <td id="titulo4">FECHA</td>
                  <td id="titulo4">GUIA N°</td>
                  <td nowrap="nowrap" id="titulo4">FACTURA N°</td>
                  <td id="titulo4">VENDEDOR</td>
                  <td id="titulo4">COMPROBANTE?</td>
                  <td id="titulo4">ESTADO</td>
                  <?php if($_SESSION['acceso']): ?><td id="titulo4" nowrap>FACTURAR</td> <?php endif; ?>
                </tr>
                <?php foreach($registros as $row_remision) {  ?>
                <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
                  <td id="dato2"><input name="borrar[]" type="checkbox" id="borrar[]" value="<?php echo $row_remision['int_remision']; ?>" /></td>
                  <td nowrap id="dato2"><a href="despacho_items_oc_vista.php?int_remision=<?php echo $row_remision['int_remision']; ?>" target="_blank" style="text-decoration:none; color:#000000"><strong><?php echo $row_remision['int_remision']; ?></strong></a></td>
                  <td nowrap id="dato2"><a href="despacho_items_oc_vista.php?int_remision=<?php echo $row_remision['int_remision']; ?>" target="_blank" style="text-decoration:none; color:#000000"><strong><?php echo $row_remision['str_numero_oc_r']; ?></strong></a></td>
                  <td nowrap id="dato1"><a href="despacho_items_oc_vista.php?int_remision=<?php echo $row_remision['int_remision']; ?>" target="_blank" style="text-decoration:none; color:#000000">
                    <?php 
                         $str_numero_oc_r=$row_remision['str_numero_oc_r'];
                         $sqln = $conexion->llenarCampos('cliente,tbl_orden_compra', "WHERE tbl_orden_compra.id_c_oc = cliente.id_c and tbl_orden_compra.str_numero_oc='$str_numero_oc_r' ", '','cliente.nombre_c,cliente.ciudad_c' );
                         
                         $cliente_c=$sqln['nombre_c']; echo  htmlentities($cliente_c);  
                         $ciudad_c=$sqln['ciudad_c']; 
                    ?>
                    </a>
                  </td>
                  <td nowrap id="dato1"><a href="despacho_items_oc_vista.php?int_remision=<?php echo $row_remision['int_remision']; ?>" target="_blank" style="text-decoration:none; color:#000000"><?php echo str_replace ( "/", "", $row_remision['ciudad_pais']); ?>
                        </a>
                      </td>
                   <td nowrap id="dato1">
                   <?php
                      $transport = $row_remision['str_transportador_r'];
                      $transport = trim($transport); 
                     if($transport=="COORDINADORA"){
                           
                           ?>
                           <a href="https://www.coordinadora.com/portafolio-de-servicios/servicios-en-linea/rastrear-guias/" target="_blank" ><?php echo $transport; ?></a>
                           <?php

                     }else if($transport=="TCC"){
                           
                           ?>
                           <a href="https://www.tcc.com.co/courier/mensajeria-productos-y-servicios/rastrear-envio/" target="_blank" ><?php echo $transport; ?></a>
                           <?php

                     }else if($transport=="SERVIENTREGA"){
                          ?>
                          <a href="https://www.servientrega.com/wps/portal/Colombia/transacciones-personas/rastreo-envios/" target="_blank" ><?php echo $transport; ?></a>
                          <?php
                           
                     
                     }else if($transport==''){ 
                        ?>
                         <a href="despacho_items_oc_vista.php?int_remision=<?php echo $row_remision['int_remision']; ?>" target="_blank" style="text-decoration:none; color:#000000"><?php echo"Sin Transportadora"; ?></a>
                        <?php
                     }else{ 
                        ?>
                         <a href="despacho_items_oc_vista.php?int_remision=<?php echo $row_remision['int_remision']; ?>" target="_blank" style="text-decoration:none; color:#000000"><?php echo $transport; ?></a>
                        <?php
                     } 
                   ?></td>
                  <td nowrap id="dato1"><a href="despacho_items_oc_vista.php?int_remision=<?php echo $row_remision['int_remision']; ?>" target="_blank" style="text-decoration:none; color:#000000"><?php echo $row_remision['fecha_r']; ?></a></td>
                  <td id="dato2">
                  <?php
                     $str_guia = $row_remision['str_transportador_r'];
                     $str_guia = trim($str_guia); 
                    if($str_guia=="COORDINADORA"){
                          
                          ?>
                          <a href="https://www.coordinadora.com/portafolio-de-servicios/servicios-en-linea/rastrear-guias/" target="_blank" ><?php echo $row_remision['str_guia_r'] ?></a>
                          <?php

                    }else if($str_guia=="TCC"){
                          
                          ?>
                          <a href="https://www.tcc.com.co/courier/mensajeria-productos-y-servicios/rastrear-envio/" target="_blank" ><?php echo $row_remision['str_guia_r'] ?></a>
                          <?php

                    }else if($str_guia=="SERVIENTREGA"){
                         ?>
                         <a href="https://www.servientrega.com/wps/portal/Colombia/transacciones-personas/rastreo-envios/" target="_blank" ><?php echo $row_remision['str_guia_r'] ?></a>
                         <?php
                          
                    
                    }else if($str_guia==''){ 
                       ?>
                        <a href="despacho_items_oc_vista.php?int_remision=<?php echo $row_remision['int_remision']; ?>" target="_blank" style="text-decoration:none; color:#000000"><?php echo"Sin Guia"; ?></a>
                       <?php
                    }else{ 
                       ?>
                        <a href="despacho_items_oc_vista.php?int_remision=<?php echo $row_remision['int_remision']; ?>" target="_blank" style="text-decoration:none; color:#000000"><?php echo $row_remision['str_guia_r'] ?></a>
                       <?php
                    } 
                  ?>
                </td>
                  
                  <td id="dato1">
                     <?php  
                            if($row_remision['factura_r']!='0' && $row_remision['factura_r']!=''){
                                $datosFE = substr($row_remision['factura_r'], 0, 2);  
                               
                               $variasFacArray = array();
                              if($datosFE=="FE"){ 
                                //$variasFacArray[] = $row_remision['factura_r'];
                                 $variasFacArray=( explode(',', $row_remision['factura_r']) );
                                 foreach ($variasFacArray as $key => $value) {
                                   $conceros = $value ;
                                  ?>  
                                  <a href="javascript:verFoto('PDF_FE/<?php echo $conceros ;?>.pdf','610','490')">  <?php echo $conceros.'<br>'; ?> </a> 
                                  <?php
                                 }  

                                }if($datosFE!="FE"){
                                
                                   $digito = "FE";
                                   $facturaCompleto = $row_remision['factura_r'];
                                   if($facturaCompleto!='' || $facturaCompleto!= null){ 
                                   $conceros = $digito.(str_pad($facturaCompleto, 7, "0", STR_PAD_LEFT)); 
                                   ?>  
                                   <a href="javascript:verFoto('PDF_FE/<?php echo $conceros ;?>.pdf','610','490')">  <?php echo $conceros.'<br>'; ?> </a> 
                                   <?php  
                                   }else{
                                     $conceros = '';   
                                   } 
                                 }  

                               }

                        ?> 
                  </td>
                  <td nowrap="nowrap" id="dato1">
                    <?php $mp=$row_remision['str_numero_oc_r'];
                    if($mp!='')
                    { 

                      $resultmp = $conexion->llenarCampos('tbl_orden_compra', "WHERE str_numero_oc='$mp' ", '','factura_oc,b_estado_oc,str_elaboro_oc' ); 
                        $b_estado_oc= $resultmp['b_estado_oc'];
                        $factura_oc =  $resultmp['factura_oc'];
                        $vendedor =  $resultmp['str_elaboro_oc'];  
                    } 
                    /*$idoc = $row_remision['str_numero_oc_r'];
                    $select_direccion = $conexion->llenaListas('vendedor ver',"left join tbl_items_ordenc itm on  ver.id_vendedor=itm.int_vendedor_io WHERE itm.str_numero_io= '$idoc'","","distinct ver.nombre_vendedor");
                     foreach($select_direccion as $row_direccion) { 
                       $vende = $row_direccion['nombre_vendedor']." ";
                     } */
                         echo htmlentities($vendedor); 
                    ?>  
                  </td>
                  <td id="dato2">  
                    <?php if( ($row_remision['comprobante_file']!='' || !is_null($row_remision['comprobante_file'])) ):  ?>
                      <a href="javascript:verFoto('Archivosdesp/<?php echo $row_remision['comprobante_file'];?>','610','490')"> 
                         <img src="images/facturado.png" alt="YA TIENE COMPROBANTE" title="YA TIENE COMPROBANTE" border="0" style="cursor:hand;" width="20" height="18" /> 
                         </a> 
                          <?php else: ?> 
                         <img src="images/18.PNG" alt="SIN COMPROBANTE" title="SIN COMPROBANTE" border="0" style="cursor:hand;" width="20" height="18" />   
                      <?php endif; ?>
                  </td>
                  <td id="dato2">
                    <a href="despacho_items_oc_vista.php?int_remision=<?php echo $row_remision['int_remision']; ?>" target="_blank" style="text-decoration:none; color:#000000">
                      <?php if($b_estado_oc=='5'){echo "Facturado Total";}else if($b_estado_oc=='4'){echo "Facturado Parcial";}else if($b_estado_oc=='1'){echo "Ingresado";}else if($b_estado_oc=='2'){echo "Programado";}else if($b_estado_oc=='3'){echo "Remisionado";}else if($b_estado_oc=='6'){echo "Muestras reposicion";}  ?></a>
                    </td>
                    <?php if($_SESSION['acceso']): ?>
                      <td id="dato2"> 
                        <a href="javascript:updateList('int_remision',<?php echo $row_remision['int_remision']; ?>,'despacho_listado2_oc.php')" >
                          <?php   
                          if( ($row_remision['factura_r']=='' || $row_remision['factura_r']=='0')  && ($factura_oc=='' || $factura_oc=='0')  ):?>
                              <img src="images/falta8.gif" alt="ACTUALIZAR" title="ACTUALIZAR" border="0" style="cursor:hand;" width="20" height="18" /></a>
                                <?php else: ?>
                              <img src="images/facturado.png" alt="YA TIENE FACTURA" title="YA TIENE FACTURA" border="0" style="cursor:hand;" width="20" height="18" />
                            <?php endif; ?>
                            </a>
                      </td> 
                      <div style="display: none;  align-items: center; justify-content: center; " id="resp"> <b style="color: red;" >Actualizando Numero de Factura!</b></div>
                    <?php endif; ?>
                  </tr>
                  <?php } ?>
                </table>
              </form>
              <!-- tabla para paginacion opcional -->
             <table border="0" width="50%" align="center">
               <tr>
                 <td width="23%" id="dato2"><?php if ($pageNum_registros > 0) { // Show if not first page ?>
                   <a href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, 0, $queryString_registros); ?>">Primero</a>
                 <?php } // Show if not first page ?>
               </td>
               <td width="31%" id="dato2"><?php if ($pageNum_registros > 0) { // Show if not first page ?>
                 <a href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, max(0, $pageNum_registros - 1), $queryString_registros); ?>">Anterior</a>
               <?php } // Show if not first page ?>
             </td>
             <td width="23%" id="dato2"><?php if ($pageNum_registros < $totalPages_registros) { // Show if not last page ?>
               <a href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, min($totalPages_registros, $pageNum_registros + 1), $queryString_registros); ?>">Siguiente</a>
             <?php } // Show if not last page ?>
           </td>
           <td width="23%" id="dato2"><?php if ($pageNum_registros < $totalPages_registros) { // Show if not last page ?>
             <a href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, $totalPages_registros, $queryString_registros); ?>">&Uacute;ltimo</a>
           <?php } // Show if not last page ?>
         </td>
       </tr>
     </table>
            </td>
              </tr> 
             </div> <!-- contenedor --> 
              </div>
                 </div>
                </div>
              </div>
            </td>
          </tr>
        </table> 
     </div>
    </body>
    </html>
    <script>
      function myFunction() { 
        var cod_ref = document.getElementById("cod_ref").value;
        var ano = document.getElementById("fecha").value;
        var mes = document.getElementById("mensual").value;
        var dia = document.getElementById("dia").value;
        var vende = document.getElementById("vende").value;
        var id_c = document.getElementById("id_c").value;
        var int_remision= document.getElementById("int_remision").value;
        var str_numero= document.getElementById("str_numero").value;
        var estado_oc= document.getElementById("estado_oc").value;
        var estado_rd= document.getElementById("estado_rd").value;
        window.location.href = "despachos_excel.php?fecha="+ano+'&mensual='+mes+'&dia='+dia+'&cod_ref='+cod_ref+'&vende='+vende+'&id_c='+id_c+'&int_remision='+int_remision+'&str_numero='+str_numero+'&estado_oc='+estado_oc+'&estado_rd='+estado_rd;
      }

      function myFunction2() { 
        var cod_ref = document.getElementById("cod_ref").value;
        var int_remision = document.getElementById("int_remision").value;
        var str_numero = document.getElementById("str_numero").value;
        var ano = document.getElementById("fecha").value;
        var mes = document.getElementById("mensual").value;
        var dia = document.getElementById("dia").value;
        var vende = document.getElementById("vende").value;
        var id_c = document.getElementById("id_c").value;
        window.location.href = "despachos_excel_brinks.php?fecha="+ano+'&mensual='+mes+'&dia='+dia+'&cod_ref='+cod_ref+'&vende='+vende+'&id_c='+id_c;
      }
    </script>

    <?php
    mysql_free_result($usuario);mysql_close($conexion1);

    mysql_free_result($orden);

    mysql_free_result($numero);

    mysql_free_result($cliente);

    mysql_free_result($remision);

    ?>