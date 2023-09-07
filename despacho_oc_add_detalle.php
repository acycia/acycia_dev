<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
?> 
<?php require_once('Connections/conexion1.php'); ?>
<?php

require_once("db/db.php");
require_once 'Models/Mremision.php';

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

$conexion = new ApptivaDB();


//BASES DE DATOS
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);
//ITEMS
$colname_ver_items = "-1";   
if (isset($_GET['id_items'])){
  $colname_ver_items= (get_magic_quotes_gpc()) ? $_GET['id_items'] : addslashes($_GET['id_items']);}
  mysql_select_db($database_conexion1, $conexion1);
  $query_items =sprintf("SELECT * FROM Tbl_items_ordenc  WHERE id_items = '%s' ORDER BY int_consecutivo_io DESC ",$colname_ver_items);
  $items = mysql_query($query_items, $conexion1) or die(mysql_error());
  $row_items = mysql_fetch_assoc($items);
  $totalRows_items = mysql_num_rows($items);

  $colname_orden_compra = "-1";
  if (isset($_GET['id_items'])) {
    $colname_orden_compra = (get_magic_quotes_gpc()) ? $_GET['id_items'] : addslashes($_GET['id_items']);
  }
  mysql_select_db($database_conexion1, $conexion1);
  $query_orden_compra = sprintf("SELECT * FROM Tbl_items_ordenc,Tbl_orden_compra WHERE Tbl_items_ordenc.id_items ='%s' AND Tbl_items_ordenc.str_numero_io=Tbl_orden_compra.str_numero_oc", $colname_orden_compra);
  $orden_compra = mysql_query($query_orden_compra, $conexion1) or die(mysql_error());
  $row_orden_compra = mysql_fetch_assoc($orden_compra);
  $totalRows_orden_compra = mysql_num_rows($orden_compra);
//EL ID_REF ES ENVIADO DESDE VISTA DE REFERENCIA
  $colname_referencia = "-1";
  if (isset($_GET['id_items'])) {
    $colname_referencia = (get_magic_quotes_gpc()) ? $_GET['id_items'] : addslashes($_GET['id_items']);
  }
  mysql_select_db($database_conexion1, $conexion1);
  $query_referencia = sprintf("SELECT Tbl_referencia.cod_ref,Tbl_referencia.version_ref FROM Tbl_items_ordenc,Tbl_referencia WHERE Tbl_items_ordenc.id_items ='%s' AND Tbl_items_ordenc.int_cod_ref_io=Tbl_referencia.cod_ref ORDER BY Tbl_referencia.version_ref DESC", $colname_referencia);
  $referencia = mysql_query($query_referencia, $conexion1) or die(mysql_error());
  $row_referencia = mysql_fetch_assoc($referencia);
  $totalRows_referencia = mysql_num_rows($referencia);
//FIN
//CONTROL CANTIDAD
  $can1=$_POST['int_cant_rd'];
  $can2=$row_items['int_cantidad_io'];
//if($can2>$can1){echo "LA CANTIDAD NO PUEDE SER MAYOR";}
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
//ACTUALIZA TODOS LOS ITEMS QUE TENGAN ESTADO PENDIENTE DE DESPACHO Y TENGAN LA FECHA DE INGRESO MAYOR A UN MES, SEGUN FECHA ACTUAL
    $updateFecha = sprintf("UPDATE `Tbl_remision_detalle` SET `fecha_rd` = CURDATE( ) WHERE TIMESTAMPDIFF( MONTH , `fecha_rd` , CURDATE( )) <= '1' AND  `estado_rd` = '1'",
      GetSQLValueString($_POST['fecha_rd'], "text")
    );
    mysql_select_db($database_conexion1, $conexion1);
    $ResultFecha = mysql_query($updateFecha, $conexion1) or die(mysql_error()); 
    /* if ((isset($_POST['int_caja_rd'])&&($_POST['int_caja_rd']!=''))&& (isset($_POST['int_numd_rd'])&&($_POST['int_numd_rd']!=''))&& (isset($_POST['int_numd_rh'])&&($_POST['int_numh_rh']!=''))&& (isset($_POST['int_cant_rd'])&&($_POST['int_cant_rd']!=''))&& (isset($_POST['int_peso_rd'])&&($_POST['int_peso_rd']!=''))&& (isset($_POST['int_pesoneto_rd'])&&($_POST['int_pesoneto_rd']!=''))){*/
//VARIABLE DE CAMPOS DINAMICOS
//IGUALES PARA TODO REGISTRO	
     $int_remision_r_rd=$_POST['int_remision_r_rd'];
     $str_numero_oc_rd=$_POST['str_numero_oc_rd'];
     $fecha_rd=$_POST['fecha_rd'];
     $int_item_io_rd=$_POST['int_item_io_rd'];
     $int_ref_io_rd=$_POST['int_ref_io_rd'];
     $int_mp_io_rd=$_POST['int_mp_io_rd'];
     $str_ref_cl_io_rd=$_POST['str_ref_cl_io_rd'];
     $int_total_cajas_rd=$_POST['int_total_cajas_rd'];
     $int_tolerancia_rd=$_POST['int_tolerancia_rd'];

	//DINAMICO
     $int_caja_rd=$_POST['int_caja_rd'];
     $int_numd_rd=$_POST['int_numd_rd'];
     $int_numh_rd=$_POST['int_numh_rd'];
     $int_cant_rd=$_POST['int_cant_rd'];
     $int_peso_rd=$_POST['int_peso_rd'];
     $int_pesoneto_rd=$_POST['int_pesoneto_rd'];

     if ($_POST['int_caja_rd']!=''&&$_POST['int_numd_rd']!=''&&$_POST['int_numh_rd']!=''&& $_POST['int_cant_rd']!=''&&$_POST['int_peso_rd']!=''&&$_POST['int_pesoneto_rd']){
      foreach($_POST['int_caja_rd'] as $key=>$value)
        $a[]= $value;
      foreach($_POST['int_numd_rd'] as $key=>$value)
        $b[]= $value;
      foreach($_POST['int_numh_rd'] as $key=>$value)
        $c[]= $value;
      foreach($_POST['int_cant_rd'] as $key=>$value)
        $d[]= $value;
      foreach($_POST['int_peso_rd'] as $key=>$value)
        $e[]= $value;
      foreach($_POST['int_pesoneto_rd'] as $key=>$value)
        $f[]= $value;
//}
      for($i=0; $i<count($a); $i++) 
      {
//INSERT DE ITEMS
$v=5;//Porcentaje
$e[$i];//peso
$f[$i];//peso/n
$pn=$e[$i]-(($e[$i]*$v)/100);// Restamos porcentaje de un numero entero
$tn=(($e[$i]*$v)/100);// Obtenemos porcentaje de un numero entero
//CALCULAR CANTIDAD
$acumulador = 0;
foreach($_POST['int_cant_rd'] as $precio){
   // echo $_POST['int_cant_rd'] . " = " . $precio . "<br />";
  $acumulador += $precio;
}
//FIN


//SUMAR EL 10% A CANTIDAD
//suma 10% a cantidad items
$cantr=$_POST["int_cantidad_io"];//cantidad del item para sumar 10%
$porcr=$_POST["int_tolerancia_rd"];//Porcentaje
$tcpr=$cantr+(($cantr*$porcr)/100);//total sumado
$cnr=(($cantr*$porcr)/100);//el 10% q se suma
//suma 10% a cantidad_rest items
$cant=(float)$_POST["int_cantidad_rest_io"];//cantidad del item para sumar 10%
$porc=$_POST["int_tolerancia_rd"];//Porcentaje
$tcp=$cant+(($cant*$porc)/100);//total sumado
$cn=(($cant*$porc)/100);//el 10% q se suma
//FIN SUMA
//de cantidad_item y $cant de cantidad_rest controlo total % y suma de restante
$total_rest=$cnr+$cant;
if ($total_rest >= $acumulador) {
	if($a[$i]!=''&&$b[$i]!=''&&$c[$i]!=''&&$d[$i]!=''&&$e[$i]!=''){
    $insertSQL = sprintf("INSERT INTO Tbl_remision_detalle (int_remision_r_rd,str_numero_oc_rd, fecha_rd, int_item_io_rd,int_caja_rd,int_mp_io_rd,int_ref_io_rd,str_ref_cl_io_rd,int_numd_rd,int_numh_rd,int_cant_rd,int_peso_rd,int_pesoneto_rd,int_total_cajas_rd,int_tolerancia_rd,str_direccion_desp_rd,estado_rd) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",                     
     GetSQLValueString($int_remision_r_rd, "int"),
     GetSQLValueString($str_numero_oc_rd, "text"),
     GetSQLValueString($fecha_rd, "text"),
     GetSQLValueString($int_item_io_rd, "int"),
     GetSQLValueString($a[$i], "int"),
     GetSQLValueString($int_mp_io_rd, "text"),
     GetSQLValueString($int_ref_io_rd, "text"),
     GetSQLValueString($str_ref_cl_io_rd, "text"),
     GetSQLValueString($b[$i], "text"),
     GetSQLValueString($c[$i], "text"),
     GetSQLValueString($d[$i], "double"),
     GetSQLValueString($e[$i], "double"),
     GetSQLValueString($pn, "double"),
     GetSQLValueString($int_total_cajas_rd, "int"),
     GetSQLValueString($int_tolerancia_rd, "int"),
     GetSQLValueString($_POST["dir"], "text"),
     GetSQLValueString($_POST["estado_rd"], "int")
   );
    mysql_select_db($database_conexion1, $conexion1);
    $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());  


    //GUARDADO DE HISTORICOS
    $myObject = new oRemision();
    $historico =  new oRemision();

    if(isset($_POST['int_remision_r_rd'])){ 
      $historico=$myObject->ObtenerId('tbl_remision_detalle','int_remision_r_rd','id_rd', " '".$_POST['int_remision_r_rd']."'  " );
    }  

    if(isset($_POST['int_remision_r_rd']) && $historico){
      $myObject->RegistrarItems("tbl_remision_detalle_historico", "int_remision_r_rd,str_numero_oc_rd, fecha_rd, int_item_io_rd,int_caja_rd,int_mp_io_rd,int_ref_io_rd,str_ref_cl_io_rd,int_numd_rd,int_numh_rd,int_cant_rd,int_peso_rd,int_pesoneto_rd,int_total_cajas_rd,int_tolerancia_rd,str_direccion_desp_rd,estado_rd,modifico", $historico);
    }//FIN HISTORICO
    
    //UPDATE A LA CANTIDAD RESTANTE DEL ITEMS  
    $cant_r=(float)$_POST["int_cantidad_rest_io"];//resto al cantidad_rest
    $cant_rest=(float)$acumulador;
    $estado_io=$_POST["b_estado_io"];	
    $id_items=$_GET['id_items'];

    $updateSQL2 = sprintf("UPDATE Tbl_items_ordenc SET  int_cantidad_rest_io=int_cantidad_rest_io - %s, int_cod_cliente_io=%s, fecha_despacho_io=%s, b_estado_io=%s WHERE id_items=%s",
     GetSQLValueString($d[$i], "double"),
     GetSQLValueString($_POST['int_cod_cliente_io'], "text"),
     GetSQLValueString($fecha_rd, "text"),
     GetSQLValueString($estado_io, "int"),
     GetSQLValueString($id_items, "int")					   
                       //GetSQLValueString($_POST['str_numero_oc_rd'], "text"),
					   //GetSQLValueString($_POST['int_ref_io_rd'], "text")
   );
    mysql_select_db($database_conexion1, $conexion1);
    $Result3 = mysql_query($updateSQL2, $conexion1) or die(mysql_error()); 

  //SUMA AL INVENTARIO LAS REFERENCIA DE BOLSA

//SI ES PARCIAL TOTAL O DESPACHADO  b_estado_io  Y estado_rd
    $estado_rd = $_POST["estado_rd"];
    if($estado_io > '3' || $estado_rd=='0')
    {  
     $updateINV6 = sprintf("UPDATE TblInventarioListado SET Salida=Salida + %s WHERE Cod_ref = %s",
      GetSQLValueString($d[$i], "text"),
      GetSQLValueString($_POST['ref_inven'], "text"));				   mysql_select_db($database_conexion1, $conexion1);
     $Result6 = mysql_query($updateINV6, $conexion1) or die(mysql_error()); 
 }//FIN IF DE ESTADOS PARA INVENTARIO 
 
//UPDATE AL ESTADO DE O.C COMO REMISIONADA
 $estado_oc=$row_orden_compra['b_estado_oc'];
/*		if($estado_oc=='5'||$estado_oc=='4'){$estado=$estado_oc;}
	else
	if($_POST["int_cantidad_rest_io"] < 1){$estado='3';}*/
	if($cant_rest < 1){$estado=3;}
	else {$estado=$estado_oc;}
	$updateSQL = sprintf("UPDATE Tbl_orden_compra SET b_estado_oc=%s WHERE str_numero_oc=%s",
   GetSQLValueString($estado, "int"),
   GetSQLValueString($_POST['str_numero_oc_rd'], "text"));
  mysql_select_db($database_conexion1, $conexion1);
  $Result2 = mysql_query($updateSQL, $conexion1) or die(mysql_error());  

  echo "<script type=\"text/javascript\">window.opener.location.reload();self.close();</script>";
  
    //UPDATE A LA CANTIDAD RESTANTE DEL ITEMS POR MATERIA PRIMA 
  if($int_mp_io_rd!=''){ 
    //$cant_r=$_POST["int_cantidad_rest_io"];//resto al cantidad_rest
    $cant_rest=$acumulador;
    $estado_io=$_POST["b_estado_io"];
    $id_items=$_GET['id_items'];
    $updateSQL2 = sprintf("UPDATE Tbl_items_ordenc SET int_cantidad_rest_io = int_cantidad_rest_io - %s, int_cod_cliente_io=%s, fecha_despacho_io=%s, b_estado_io=%s WHERE id_items=%s",
     GetSQLValueString($cant_rest, "int"),
     GetSQLValueString($_POST['int_cod_cliente_io'], "text"),
     GetSQLValueString($fecha_rd, "text"),
     GetSQLValueString($estado_io, "int"),
     GetSQLValueString($id_items, "int")
                      // GetSQLValueString($_POST['str_numero_oc_rd'], "text"),
					  // GetSQLValueString($int_mp_io_rd, "text")
   );
    mysql_select_db($database_conexion1, $conexion1);
    $Result3 = mysql_query($updateSQL2, $conexion1) or die(mysql_error());
    //UPDATE LA TABLA DE INVENTARIOS DESCONTANDO LO QUE SE DESPACHO EN MP
/*  $updateSQL4 = sprintf("UPDATE TblInventarioListado SET Salida=Salida + %s WHERE Codigo = %s",
					   GetSQLValueString($cant_rest, "text"),
                       GetSQLValueString($_POST['id_mp_vta_io'], "text"));
  mysql_select_db($database_conexion1, $conexion1);
  $Result4 = mysql_query($updateSQL4, $conexion1) or die(mysql_error());*/

  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")&&($_POST['b_estado_io']=='5')){  
   $updateSQL5 = sprintf("UPDATE Tbl_orden_produccion SET f_despacho=%s WHERE str_numero_oc_op=%s AND int_cod_ref_io==%s",
     GetSQLValueString($_POST['fecha_rd'], "date"),
     GetSQLValueString($_POST['str_numero_oc_rd'], "text"),
     GetSQLValueString($_POST['int_cod_ref_io'], "text"));

   mysql_select_db($database_conexion1, $conexion1);
   $Result5 = mysql_query($updateSQL5, $conexion1) or die(mysql_error()); 
 }

 echo "<script type=\"text/javascript\">window.opener.location.reload();self.close();</script>"; 
}
}else{echo"<script type=\"text/javascript\">alert(\"No se puede guardar campos vacios.\")</script>";
}
}else{echo"<script type=\"text/javascript\">alert(\"La Cantidad ingresada no puede ser mayor a la cantidad de la referencia mas el $porcr %, ingrese menor cantidad.\")</script>";  
}
}
}
}
$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
?>
<html>
<head>
  <title>SISADGE AC &amp; CIA</title>
  <link href="css/formato.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="js/formato.js"></script>
  <script type="text/javascript" src="js/consulta.js"></script>
  <script type="text/javascript" src="js/validacion_numerico.js"></script>
  <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
  <script type="text/javascript" src="AjaxControllers/js/funcionesDespachos.js"></script> 


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
  <!--CODIGO DE CONFIRMACION CIERRE DE PAGINA WEB-->
  <script type="text/javascript">
// J /* Abrimos etiqueta de código Javascript */
var num=0;
num++;
var posicionCampo=1;

function addremi(){

  nuevaFila = document.getElementById("tablaremision").insertRow(-1);

  nuevaFila.id=posicionCampo;

  nuevaCelda=nuevaFila.insertCell(-1);

  nuevaCelda.innerHTML="<td> <input type='text' value="+num+++" name='int_caja_rd["+posicionCampo+"]' style='width:40px;' ></td>";
  nuevaCelda=nuevaFila.insertCell(-1);

  nuevaCelda.innerHTML="<td id='tddesde'> <input type='text' required='required' minlength='5' autofocus='autofocus' id='desde-"+posicionCampo+"' name='int_numd_rd["+posicionCampo+"]' style='width:150px;'></td>";
  nuevaCelda=nuevaFila.insertCell(-1);

  nuevaCelda.innerHTML="<td id='tdhasta'> <input type='text' required='required' minlength='5' onChange='totalizar(this)' id='hasta-"+posicionCampo+"' name='int_numh_rd["+posicionCampo+"]'style='width:150px;' ></td>";
  nuevaCelda=nuevaFila.insertCell(-1);

  nuevaCelda.innerHTML="<td> <input type='number' step='0.001' required='required' name='int_cant_rd["+posicionCampo+"]' style='width:80px;'></td>";
  nuevaCelda=nuevaFila.insertCell(-1);

  nuevaCelda.innerHTML="<td> <input type='number' required='required' name='int_peso_rd["+posicionCampo+"]' style='width:80px;'></td>";
  nuevaCelda=nuevaFila.insertCell(-1);

  nuevaCelda.innerHTML="<td> <input type='number' value='' id='total-"+posicionCampo+"'  required='required' name='total["+posicionCampo+"]' style='width:80px;'></td>";
  nuevaCelda=nuevaFila.insertCell(-1);



  nuevaCelda.innerHTML="<td> <input type='hidden' required='required' name='int_pesoneto_rd["+posicionCampo+"]' style='width:80px;'></td>";
  nuevaCelda=nuevaFila.insertCell(-1);

  nuevaCelda.innerHTML="<td> <input type='hidden' required='required' name='agregar["+posicionCampo+"]' style='width:80px;'></td>";
  nuevaCelda=nuevaFila.insertCell(-1); 

  nuevaCelda.innerHTML="<td><input type='button' value='Eliminar' onclick='eliminarremision(this)'></td>";

  posicionCampo++;

}
function eliminarremision(obj){
  var oTr = obj;

  while(oTr.nodeName.toLowerCase()!='tr'){

    oTr=oTr.parentNode;

  }

  var root = oTr.parentNode;

  root.removeChild(oTr);

}

function totalizar(elemento){
 var element =elemento;
 
 var idh = (element.id);
 var numeroh = idh.split('-');
 var valhasta = element.value;
 var idhasta =numeroh[1];

 
 var hasta = valhasta; 
 var desde = $("#desde-"+idhasta).val();
 var total = (hasta-desde)+1;
 

 $("#total-"+idhasta).val(total);
} 
/* function totalizar(elemento){ 
var idh= $(elemento).attr('id');
var numeroh = idh.split('-');
var valhasta = $(elemento).val();
var idhasta =numeroh[1];

       var hasta = valhasta; 
       var desde = $("#desde-"+idhasta).val();
       var total = (hasta-desde)+1;


        $("#total-"+idhasta).val(total);
      }*/
    </script>
  </head>
  <body>
<?php echo $conexion->header('vistas'); ?>

    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      <table id="tabla2">
        <tr>
          <td colspan="4" id="subtitulo">AGREGAR REFERENCIA X CAJAS</td>
        </tr>
        <tr>
          <td colspan="2" id="fuente1">REMISION  N&deg; <strong><?php echo $_GET['int_remision']; ?></strong>
            <input name="int_remision_r_rd" type="hidden" value="<?php echo $_GET['int_remision']; ?>">
            <input name="str_numero_oc_rd" type="hidden" value="<?php echo $row_items['str_numero_io']; ?>">
            <input name="int_item_io_rd" type="hidden" value="<?php echo $row_items['id_items']; ?>">
            <input name="int_mp_io_rd" type="hidden" value="<?php echo $row_items['id_mp_vta_io']; ?>">
            <input name="int_ref_io_rd" type="hidden" value="<?php echo $row_items['int_cod_ref_io']; ?>">
            <input name="str_ref_cl_io_rd" type="hidden" value="<?php echo $row_items['int_cod_cliente_io']; ?>">
            <input type="hidden" name="ref_inven" id="ref_inven" value="<?php echo $row_referencia['cod_ref'];?>"/></td>
            <td colspan="2" id="fuente1"><strong>Fecha:</strong><input name="fecha_rd" type="text" id="fecha_rd" value="<?php echo date("Y-m-d"); ?>" size="10" readonly /></td>
          </tr>
          
          <tr>
            <td colspan="4" id="fuente3">&nbsp;</td>
          </tr>

          <tr id="tr2">
            <td colspan="5" id="dato2"><table id="tabla1">
              <tr>
                <td nowrap="nowrap" id="nivel2">ITEM </td>
                <td nowrap="nowrap" id="nivel2">REF. AC</td>
                <td nowrap="nowrap" id="nivel2">REF. MP</td>
                <td nowrap="nowrap" id="nivel2">REF. CLIENTE</td>
                <td nowrap="nowrap" id="nivel2">CANT.</td>
                <td nowrap="nowrap" id="nivel2">CANT. RESTANTE</td>
                <td nowrap="nowrap" id="nivel2">UNIDADES</td>
                <td nowrap="nowrap" id="nivel2">FECHA ENTREGA</td>
                <td nowrap="nowrap" id="nivel2">IPUU</td>
                <td nowrap="nowrap" id="nivel2">PRECIO/VENTA</td>
                <td nowrap="nowrap" id="nivel2">TOTAL ITEM</td>
                <td nowrap="nowrap" id="nivel2">MONEDA</td>
                <td id="nivel2">DIRECCION DESPACHO</td>
                <td nowrap="nowrap" id="nivel2">FACTURADO</td>
              </tr>
              
              <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
                <td nowrap="nowrap" id="talla2"><?php echo $row_items['int_consecutivo_io']; ?></td>
                <td nowrap="nowrap" id="talla2"><?php echo $row_items['int_cod_ref_io']; ?>
              </td>
              <td nowrap="nowrap" id="talla2"><?php $mp=$row_items['id_mp_vta_io'];
              if($mp!='')
              {
                $sqlmp="SELECT * FROM Tbl_mp_vta WHERE id_mp_vta='$mp'";
                $resultmp= mysql_query($sqlmp);
                $nump= mysql_num_rows($resultmp);
                if($nump >='1')
                { 
                  $nombre_mp = mysql_result($resultmp,0,'str_nombre');
                  echo $nombre_mp;
                } }else {echo "N.A";} ?></td>
                <td nowrap="nowrap" id="talla2"><input name="int_cod_cliente_io" type="text" size="15" maxlength="20"  value="<?php echo $row_items['int_cod_cliente_io']; ?>"></td>
                <td nowrap="nowrap" id="talla2"><input name="int_cantidad_io" type="hidden" value="<?php echo $row_items['int_cantidad_io']; ?>"><?php echo $row_items['int_cantidad_io']; ?></td>
                <td nowrap="nowrap" id="talla2"> 
                  <input name="int_cantidad_rest_io" type="hidden" value="<?php echo $row_items['int_cantidad_rest_io']; ?>">
                  <?php echo $row_items['int_cantidad_rest_io']; ?></td>
                  <td nowrap="nowrap" id="talla2"><?php echo $row_items['str_unidad_io']; ?></td>
                  <td nowrap="nowrap" id="talla2"><?php echo $row_items['fecha_entrega_io']; ?></td>
                  <td nowrap="nowrap" id="talla2"><?php echo $row_items['impuesto']==1 ? 'SI': 'NO'; ?></td>
                  <td nowrap="nowrap" id="talla2"><?php echo $row_items['int_precio_io']; ?></td>
                  <td nowrap="nowrap" id="talla2"><?php echo $subtotal=($row_items['int_cantidad_io']*$row_items['N_precio_old']);?>
                  <?php //echo $row_items['int_total_item_io'];$subtotal=$subtotal+$row_items['int_total_item_io'];?></td>
                  <td nowrap="nowrap" id="talla2"><?php echo $row_items['str_moneda_io']; ?></td>
                  <td id="talla2"><input type="hidden" name="dir" id="dir" value="<?php echo $row_items['str_direccion_desp_io']; ?>">
                    <?php echo $row_items['str_direccion_desp_io']; ?></td>
                    <td nowrap="nowrap"id="talla2">
                      <?php if($row_items['b_estado_io']=='5'){echo "Facturado Total";}else if($row_items['b_estado_io']=='4'){echo "Facturado Parcial";}else if($row_items['b_estado_io']=='1'){echo "Ingresado";}else if($row_items['b_estado_io']=='2'){echo "Programado";}else if($row_items['b_estado_io']=='3'){echo "Remisionado";}else if($row_items['b_estado_io']=='6'){echo "Muestras reposicion";}   ?>
                      <a href="javascript:verFoto('despacho_oc_add_detalle.php?id_items=<?php echo $row_items['id_items']; ?>&int_remision=<?php echo $row_remision['int_remision']; ?>','900','400')" target="_top" style="text-decoration:none; color:#000000">

                      </a>
                    </td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td colspan="4" id="dato1">            
                  </td>
                </tr>
                <tr>
                  <td colspan="4" id="dato1"></td>
                </tr>
                <tr>
                  <td colspan="4" id="dato2">&nbsp;</td>
                </tr>
                <tr>
                  <td id="dato1"><strong>Total Cajas</strong>:
                    <input name="int_total_cajas_rd" type="number" id="int_total_cajas_rd" required title="Ingrese cajas"  style="width:60px"></td>
                    <td id="dato1"><strong>Tolerancia % </strong>
                      <input name="int_tolerancia_rd" type="number" id="int_tolerancia_rd" required title="Ingrese tolerancia" max="50"  value="50" style="width:40px"></td>
                      <td id="dato1"><strong>Facturar:</strong>
                        <select name="b_estado_io" id="opciones">
                          <option value="3">REMISIONADA</option>
                          <option value="4">FACTURADA PARCIAL </option>
                          <option value="5">FACTURADA TOTAL</option>
                          <option value="6">MUESTRAS REPOSICION</option>
                        </select>
                        <strong>ESTADO:</strong><select name="estado_rd" id="estado_rd">
                          <option value="0" selected>Despachado</option>
                          <option value="1">Pendiente</option>
                        </select></td>
                        <td id="dato1">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="4" id="dato2"></td>
                      </tr> 
 
                      <!-- <tr>
                        <td colspan="4" id="dato2">
                          <table width="616" id="tablaremision">            
                            <tr> <?php $item=$row_items['int_consecutivo_io']; ?>           
                            <td id="nivel2" width="26">RANGO</td>  
                            <td id="nivel2" width="76">NUM. DESDE</td> 
                            <td id="nivel2" width="76">NUM. HASTA</td>
                            <td id="nivel2" width="64">CANTIDAD</td>  
                            <td id="nivel2" width="64">PESO</td>  
                            <td id="nivel2" width="64">TOTAL</td>             
                            <td id="nivel2" width="30"><input type="button" onClick="addremi(this)" value=" + " ></td>       
                          </tr>            
                        </table>           
                      </td>
                    </tr>  -->

                           <tr>
                             <td colspan="6" id="dato2">
                              <div id="faltantess" >
                               <!-- TABLA DE FALTANTES-->  
                               <div id="contenedor">
                                 <table id="tablaf">
                                  <thead>
                                   <tr> <?php $item=$row_items['int_consecutivo_io']; ?>           
                                   <td id="nivel2" width="26">RANGO</td> 
                                   <td id="nivel2" width="76"> </td>  
                                   <td id="nivel2" width="76">NUM. DESDE</td> 
                                   <td id="nivel2" width="76">NUM. HASTA</td>
                                   <td id="nivel2" width="64">CANTIDAD</td>  
                                   <td id="nivel2" width="64">PESO</td>  
                                   <td id="nivel2" width="64">TOTAL</td>   
                                   <th width="120" id="nivel2"><button type="button" class="botonGMini" onClick="AddItemd();" > + </button></th>
                                 </tr>
                               </thead>
                               <tbody>
                                <tfoot>
                                 <tr>
                                  <td id="nivel2">TOTAL </td>
                                  <td colspan="7" id="nivel2"><span id="total2">0</span></td>
                                  <td></td>
                                </tr>
                              </tfoot>
                            </tbody>
                          </table>          

                        </div>
                      </div>
                    </td>
                  </tr>



                    <tr>
                      <td colspan="4" id="dato1"><strong>Nota: </strong>Recuerde se guarda por caja no por rango.</td>
                    </tr>
                    <tr>
                      <td colspan="4" id="dato2">&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="4" id="dato2">&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="4" id="dato2">&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="4" id="dato2"><input class="botonGMini" type="submit" value="FINALIZAR REMISION" onClick="if(form1.b_despacho_io.value=='0' && form1.b_estado_io.value=='0') { alert('DEBE SELECCIONAR UNA OPCION'); }">
                        <!--<img src="images/rf.gif" width="31" height="18" onClick="javascript:submit();window.opener.location.reload();window.close();">--></td>
                      </tr>
                    </table>
                    <input type="hidden" name="MM_insert" value="form1">
                  </form></td>
                </tr>
                <tr>
                  <td colspan="2" align="center">&nbsp;</td>
                </tr>
              </table>
            </div>
           <?php echo $conexion->header('footer'); ?>
          
      </body>
      </html>
      <?php
      mysql_free_result($usuario);mysql_close($conexion1);
      mysql_free_result($items);
      ?>
