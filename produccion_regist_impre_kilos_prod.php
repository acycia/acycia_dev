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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
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
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
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
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	
	$id=($_POST['id_i']);
    foreach($id as $key=>$v)
    $a[]= $v;
	
	$cant=($_POST['cant']);
    foreach($cant as $key=>$v)
    $b[]= $v;
	
	$desp=($_POST['desp']);
    foreach($desp as $key=>$v)
    $c[]= $v;	
		
	for($x=0; $x<count($a); $x++){
		if($a[$x]!=''&&$b[$x]!=''){	
		
 	  $sqlcostoMP="SELECT valor_unitario_insumo AS valorkilo FROM insumo WHERE id_insumo = $a[$x]"; 
	  $resultcostoMP=mysql_query($sqlcostoMP); 
	  $numcostoMP=mysql_num_rows($resultcostoMP); 
	  $row_valoresMP = mysql_fetch_assoc($resultcostoMP);
	  $contValor=0;
      $valorMP = $row_valoresMP['valorkilo']; 
	  				 
 $insertSQLkp = sprintf("INSERT INTO Tbl_reg_kilo_producido (id_rpp_rp,valor_prod_rp,valDespImp_rp,op_rp,int_rollo_rkp,id_proceso_rkp,fecha_rkp,costo_mp) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",                      
                       GetSQLValueString($a[$x], "int"),
                       GetSQLValueString($b[$x], "double"),
					   GetSQLValueString($c[$x], "double"),
					   GetSQLValueString($_POST['id_op'], "int"),
					   GetSQLValueString($_POST['rollo_rp'], "int"),
					   GetSQLValueString($_POST['id_proceso_rkp'], "int"),
					   GetSQLValueString($_POST['fecha_ini_rp'], "date"),
					   GetSQLValueString($valorMP, "double"));
  mysql_select_db($database_conexion1, $conexion1);
  $Resultkp = mysql_query($insertSQLkp, $conexion1) or die(mysql_error());

   //UPDATE LA TABLA DE INVENTARIOS DESCONTANDO LO QUE SE GASTO
   $updateSQL = sprintf("UPDATE TblInventarioListado SET Salida=Salida + %s WHERE Codigo = %s",
					   GetSQLValueString($b[$x], "text"), 
                       GetSQLValueString($a[$x], "int"));
  mysql_select_db($database_conexion1, $conexion1);
  $Result = mysql_query($updateSQL, $conexion1) or die(mysql_error());		
  		  }
	}
 /*echo "<script type=\"text/javascript\">window.opener.location.reload();</script>";*/ 
echo "<script type=\"text/javascript\">window.close();</script>";
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
//CARGA MEZCLAS
mysql_select_db($database_conexion1, $conexion1);
$query_mezclas = "SELECT * FROM  Tbl_mezclas WHERE id_m < 10 ORDER BY id_m ASC";
$mezclas = mysql_query($query_mezclas, $conexion1) or die(mysql_error());
$row_mezclas = mysql_fetch_assoc($mezclas);
$totalRows_mezclas = mysql_num_rows($mezclas);
//LLENA COMBOS DE MATERIAS PRIMAS
mysql_select_db($database_conexion1, $conexion1);
$query_materia_prima = "SELECT * FROM insumo WHERE clase_insumo='8' AND estado_insumo='0' ORDER BY descripcion_insumo ASC";
$materia_prima = mysql_query($query_materia_prima, $conexion1) or die(mysql_error());
$row_materia_prima = mysql_fetch_assoc($materia_prima);
$totalRows_materia_prima = mysql_num_rows($materia_prima);
//CARGA UNIDAD 1
$colname_referencia = "-1";
if (isset($_GET['id_ref'])) {
  $colname_referencia = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_uno = sprintf("select * from Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi=%s AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='1' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m", $colname_referencia);
$unidad_uno = mysql_query($query_unidad_uno, $conexion1) or die(mysql_error());
$row_unidad_uno = mysql_fetch_assoc($unidad_uno);
$totalRows_unidad_uno = mysql_num_rows($unidad_uno);
//CARGA UNIDAD 2
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_dos = sprintf("select * from Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi=%s AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='2' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m ", $colname_referencia);
$unidad_dos = mysql_query($query_unidad_dos, $conexion1) or die(mysql_error());
$row_unidad_dos = mysql_fetch_assoc($unidad_dos);
$totalRows_unidad_dos = mysql_num_rows($unidad_dos);
//CARGA UNIDAD 3
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_tres = sprintf("select * from Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi=%s AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='3' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m", $colname_referencia);
$unidad_tres = mysql_query($query_unidad_tres, $conexion1) or die(mysql_error());
$row_unidad_tres = mysql_fetch_assoc($unidad_tres);
$totalRows_unidad_tres = mysql_num_rows($unidad_tres);
//CARGA UNIDAD 4
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_cuatro = sprintf("select * from Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi=%s AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='4' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m", $colname_referencia);
$unidad_cuatro = mysql_query($query_unidad_cuatro, $conexion1) or die(mysql_error());
$row_unidad_cuatro = mysql_fetch_assoc($unidad_cuatro);
$totalRows_unidad_cuatro = mysql_num_rows($unidad_cuatro);
//CARGA UNIDAD 5
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_cinco = sprintf("select * from Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi=%s AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='5' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m", $colname_referencia);
$unidad_cinco = mysql_query($query_unidad_cinco, $conexion1) or die(mysql_error());
$row_unidad_cinco = mysql_fetch_assoc($unidad_cinco);
$totalRows_unidad_cinco = mysql_num_rows($unidad_cinco);
//CARGA UNIDAD 6
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_seis = sprintf("select * from Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi=%s AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='6' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m", $colname_referencia);
$unidad_seis = mysql_query($query_unidad_seis, $conexion1) or die(mysql_error());
$row_unidad_seis = mysql_fetch_assoc($unidad_seis);
$totalRows_unidad_seis = mysql_num_rows($unidad_seis);
//CARGA UNIDAD 7
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_siete = sprintf("select * from Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi=%s AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='7' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m", $colname_referencia);
$unidad_siete = mysql_query($query_unidad_siete, $conexion1) or die(mysql_error());
$row_unidad_siete = mysql_fetch_assoc($unidad_siete);
$totalRows_unidad_siete = mysql_num_rows($unidad_siete);
//CARGA UNIDAD 8
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_ocho = sprintf("select * from Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi=%s AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='8' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m", $colname_referencia);
$unidad_ocho = mysql_query($query_unidad_ocho, $conexion1) or die(mysql_error());
$row_unidad_ocho = mysql_fetch_assoc($unidad_ocho);
$totalRows_unidad_ocho = mysql_num_rows($unidad_ocho);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
  <link rel="stylesheet" type="text/css" href="css/desplegable.css" />
  <link rel="stylesheet" type="text/css" href="css/general.css"/>
</head>
<body>
<div align="center">
<table align="center" id="tabla"><tr align="center"><td>
<div> 
<b class="spiffy"> 
<b class="spiffy1"><b></b></b>
<b class="spiffy2"><b></b></b>
<b class="spiffy3"></b>
<b class="spiffy4"></b>
<b class="spiffy5"></b></b>
<div class="spiffy_content">
<table id="tabla1"><tr>
<td colspan="2" align="center"><img src="images/cabecera.jpg"></td></tr>
<tr><td id="nombreusuario"><?php echo $row_usuario['nombre_usuario']; ?></td>
  <td id="cabezamenu"><!--<ul id="menuhorizontal">
  <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
<li><a href="menu.php">MENU PRINCIPAL</a></li>
<li><a href="compras.php">GESTION COMPRAS</a></li>
</ul>--></td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1">
      <form action="<?php echo $editFormAction; ?>" method="post" name="form1">
        <table id="tabla2">
          <tr>
            <td  nowrap="nowrap" colspan="5" id="subtitulo">AGREGAR TINTAS
              <input name="id_op" type="hidden" id="id_op" value="<?php echo $_GET['id_op']; ?>" />
              <input name="id_ref" type="hidden" id="id_ref" value="<?php echo $_GET['id_ref']; ?>"/>
              <input name="id_proceso_rkp" type="hidden" id="id_proceso_rkp" value="2" /></td>
            </tr>
          <tr>
            <td colspan="4" id="dato1">Se guardara la misma fecha inicial y hora inicial con la que va a guarda todo el registro</td>
            <td  nowrap="nowrap" id="dato2">Fecha y Hora:
              <input name="fecha_ini_rp" type="datetime" min="2000-01-02" value="<?php echo $_GET['fecha']; ?>" size="19" required="required"/>
             </td>
            </tr>
          <tr>
            <td colspan="5" id="dato2">
            Rollo n&deg;
            <input name="rollo_rp" type="number" style="width:60px" required="required" id="rollo_rp" value="<?php echo $_GET['rollo']; ?>" />
            <?php $id_r=$_GET['id_op'];
            $sqlr="SELECT COUNT(rollo_r) AS rolloI FROM TblImpresionRollo WHERE id_op_r=$id_r"; 
            $resultr=mysql_query($sqlr); 
            $numr=mysql_num_rows($resultr); 
            if($numr >= '1') 
            {$max_rolloI=mysql_result($resultr,0,'rolloI');		 
			}?> 
           Consumo para un total de rollos:  <?php echo $max_rolloI; ?>
           <?php
		   if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) { 
		   	$cantTin=($_POST['cant']);
            $a = array ($cantTin); 
            $acum=array_sum($a) ; 
			$TintasAcum=($acum/$max_rolloI);
			 
			$sqlsel="UPDATE Tbl_reg_produccion SET int_totalKilos_tinta_rp='$TintasAcum' WHERE id_op_rp='$id_r' AND id_proceso_rp='2'";
			$resultsel=mysql_query($sqlsel);
		   }
		   ?></td>
            </tr>

<tr id="tr1">
          <td colspan="9" id="titulo">CONSUMO MATERIA PRIMA EN IMPRESION</td>
        </tr>  
        <tr id="tr1">
        <td  nowrap="nowrap"id="subtitulo1">UNIDAD 1</td>
        <td  nowrap="nowrap"id="subtitulo1">REFERENCIAS</td>
        <td  nowrap="nowrap"id="subtitulo1">%</td>
        <td  nowrap="nowrap"id="subtitulo1">CONSUMO</td>
        <td  nowrap="nowrap"id="subtitulo1">DESPERDICIO</td>
        </tr>
	   <?php  for ($x=0;$x<=$totalRows_unidad_uno-1 ;$x++) { ?>
       <tr>         
       <td id="fuente1"><?php $var=mysql_result($unidad_uno,$x,str_nombre_m); echo $var;$id=mysql_result($unidad_uno,$x,id_i_pmi); ?></td>
       <td id="fuente1"><select name="id_i[]" id="id_i[]" style="width:150px">
         <option value=""<?php if (!(strcmp("", $id))) {echo "selected=\"selected\"";} ?>>Ref</option>
         <?php
do {  
?>
         <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $id))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
         <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
       </select></td>   
       <td id="fuente1"><input name="valor[]" readonly type="text" size="5"value="<?php $valor=mysql_result($unidad_uno,$x,str_valor_pmi); echo $valor;?>" style="width:50px"/></td>
       <td id="fuente1"><input name="cant[]" type="number" style="width:80px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
       <td id="fuente1"><input name="desp[]" type="number" style="width:80px" placeholder="kilos" min="0"step="0.01" value=""/></td>       
       </tr>
	   <?php  } ?>     
       <tr>
         <td colspan="5" id="dato9"></td>
         <td colspan="5" nowrap="nowrap" id="fuente10"></td>
       </tr>
       <tr>
         <td colspan="5" id="dato8"></td>
         <td colspan="5" nowrap="nowrap" id="fuente9"></td>
       </tr>
         
        <tr id="tr1">        
        <td  nowrap="nowrap"id="subtitulo1">UNIDAD 2</td>
        <td  nowrap="nowrap"id="subtitulo1">REFERENCIAS</td>
        <td  nowrap="nowrap"id="subtitulo1">%</td>
        <td  nowrap="nowrap"id="subtitulo1">CONSUMO</td>
        <td  nowrap="nowrap"id="subtitulo1">DESPERDICIO</td>
        </tr> 
	   <?php  for ($x=0;$x<=$totalRows_unidad_dos-1 ;$x++) { ?> 
       <tr> 
       <td id="fuente1"><?php  $var=mysql_result($unidad_dos,$x,str_nombre_m); echo $var;$id=mysql_result($unidad_dos,$x,id_i_pmi); ?></td>        
       <td id="fuente1"><select name="id_i[]" id="id_i[]" style="width:150px">
         <option value=""<?php if (!(strcmp("", $id))) {echo "selected=\"selected\"";} ?>>Ref</option>
         <?php
do {  
?>
         <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $id))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
         <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
       </select></td>   
       <td id="fuente1"><input name="valor[]" readonly type="text" size="5"value="<?php $valor=mysql_result($unidad_dos,$x,str_valor_pmi); echo $valor;?>" style="width:50px"/></td>
       <td id="fuente1"><input name="cant[]" type="number" style="width:80px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
       <td id="fuente1"><input name="desp[]" type="number" style="width:80px" placeholder="kilos" min="0"step="0.01" value=""/></td>         
       </tr>
	   <?php  } ?>
        <tr id="tr1">
        <td  nowrap="nowrap"id="subtitulo1">UNIDAD 3</td>
        <td  nowrap="nowrap"id="subtitulo1">REFERENCIAS</td>
        <td  nowrap="nowrap"id="subtitulo1">%</td>
        <td  nowrap="nowrap"id="subtitulo1">CONSUMO</td>
        <td  nowrap="nowrap"id="subtitulo1">DESPERDICIO</td>
        </tr>
	   <?php  for ($x=0;$x<=$totalRows_unidad_tres-1 ;$x++) { ?> 
       <tr>  
       <td id="fuente1"><?php  $var=mysql_result($unidad_tres,$x,str_nombre_m); echo $var;$id=mysql_result($unidad_tres,$x,id_i_pmi); ?></td>       
       <td id="fuente1"><select name="id_i[]" id="id_i[]" style="width:150px">
         <option value=""<?php if (!(strcmp("", $id))) {echo "selected=\"selected\"";} ?>>Ref</option>
         <?php
do {  
?>
         <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $id))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
         <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
       </select></td>   
       <td id="fuente1"><input name="valor[]" readonly type="text" size="5"value="<?php $valor=mysql_result($unidad_tres,$x,str_valor_pmi); echo $valor;?>" style="width:50px"/></td>
       <td id="fuente1"><input name="cant[]" type="number" style="width:80px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
       <td id="fuente1"><input name="desp[]" type="number" style="width:80px" placeholder="kilos" min="0"step="0.01" value=""/></td>       
       </tr>
	   <?php  } ?>
        <tr id="tr1">
        <td  nowrap="nowrap"id="subtitulo1">UNIDAD 4</td>
        <td  nowrap="nowrap"id="subtitulo1">REFERENCIAS</td>
        <td  nowrap="nowrap"id="subtitulo1">%</td>
        <td  nowrap="nowrap"id="subtitulo1">CONSUMO</td>
        <td  nowrap="nowrap"id="subtitulo1">DESPERDICIO</td>
        </tr>
	   <?php  for ($x=0;$x<=$totalRows_unidad_cuatro-1 ;$x++) { ?> 
       <tr>
       <td id="fuente1"><?php  $var=mysql_result($unidad_cuatro,$x,str_nombre_m); echo $var;$id=mysql_result($unidad_cuatro,$x,id_i_pmi); ?></td>         
       <td id="fuente1"><select name="id_i[]" id="id_i[]" style="width:150px">
         <option value=""<?php if (!(strcmp("", $id))) {echo "selected=\"selected\"";} ?>>Ref</option>
         <?php
do {  
?>
         <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $id))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
         <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
       </select></td>   
       <td id="fuente1"><input name="valor[]" readonly type="text" size="5"value="<?php $valor=mysql_result($unidad_cuatro,$x,str_valor_pmi); echo $valor;?>" style="width:50px"/></td>
       <td id="fuente1"><input name="cant[]" type="number" style="width:80px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
       <td id="fuente1"><input name="desp[]" type="number" style="width:80px" placeholder="kilos" min="0"step="0.01" value=""/></td>       
       </tr>
	   <?php  } ?>
       <tr id="tr1">
        <td  nowrap="nowrap"id="subtitulo1">UNIDAD 5</td>
        <td  nowrap="nowrap"id="subtitulo1">REFERENCIAS</td>
        <td  nowrap="nowrap"id="subtitulo1">%</td>
        <td  nowrap="nowrap"id="subtitulo1">CONSUMO</td>
        <td  nowrap="nowrap"id="subtitulo1">DESPERDICIO</td>
        </tr> 
	   <?php  for ($x=0;$x<=$totalRows_unidad_cinco-1 ;$x++) { ?> 
       <tr>  
       <td id="fuente1"><?php  $var=mysql_result($unidad_cinco,$x,str_nombre_m); echo $var;$id=mysql_result($unidad_cinco,$x,id_i_pmi); ?></td>       
       <td id="fuente1"><select name="id_i[]" id="id_i[]" style="width:150px">
         <option value=""<?php if (!(strcmp("", $id))) {echo "selected=\"selected\"";} ?>>Ref</option>
         <?php
do {  
?>
         <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $id))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
         <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
       </select></td>   
       <td id="fuente1"><input name="valor[]" readonly type="text" size="5"value="<?php $valor=mysql_result($unidad_cinco,$x,str_valor_pmi); echo $valor;?>" style="width:50px"/></td>
       <td id="fuente1"><input name="cant[]" type="number" placeholder="kilos" style="width:80px" min="0"step="0.01" value=""/></td> 
       <td id="fuente1"><input name="desp[]" type="number" placeholder="kilos" style="width:80px" min="0"step="0.01" value=""/></td>      
       </tr>
	   <?php  } ?> 
       <tr id="tr1">
        <td  nowrap="nowrap"id="subtitulo1">UNIDAD 6</td>
        <td  nowrap="nowrap"id="subtitulo1">REFERENCIAS</td>
        <td  nowrap="nowrap"id="subtitulo1">%</td>
        <td  nowrap="nowrap"id="subtitulo1">CONSUMO</td>
        <td  nowrap="nowrap"id="subtitulo1">DESPERDICIO</td>
        </tr> 
	   <?php  for ($x=0;$x<=$totalRows_unidad_seis-1 ;$x++) { ?>         
       <tr>  
       <td id="fuente1"><?php $var=mysql_result($unidad_seis,$x,str_nombre_m); echo $var;$id=mysql_result($unidad_seis,$x,id_i_pmi); ?></td>       
       <td id="fuente1"><select name="id_i[]" id="id_i[]" style="width:150px">
         <option value=""<?php if (!(strcmp("", $id))) {echo "selected=\"selected\"";} ?>>Ref</option>
         <?php
do {  
?>
         <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $id))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
         <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
       </select></td>   
       <td id="fuente1"><input name="valor[]" readonly type="text" size="5"value="<?php $valor=mysql_result($unidad_seis,$x,str_valor_pmi); echo $valor;?>" style="width:50px"/></td>
       <td id="fuente1"><input name="cant[]" type="number" style="width:80px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
       <td id="fuente1"><input name="desp[]" type="number" style="width:80px" placeholder="kilos" min="0"step="0.01" value=""/></td>       
       </tr>
	   <?php  } ?> 
       <tr id="tr1">
        <td  nowrap="nowrap"id="subtitulo1">UNIDAD 7</td>
        <td  nowrap="nowrap"id="subtitulo1">REFERENCIAS</td>
        <td  nowrap="nowrap"id="subtitulo1">%</td>
        <td  nowrap="nowrap"id="subtitulo1">CONSUMO</td>
        <td  nowrap="nowrap"id="subtitulo1">DESPERDICIO</td>
        </tr>
	   <?php  for ($x=0;$x<=$totalRows_unidad_siete-1 ;$x++) { ?>         
       <tr>  
       <td id="fuente1"><?php  $var=mysql_result($unidad_siete,$x,str_nombre_m); echo $var;$id=mysql_result($unidad_siete,$x,id_i_pmi); ?></td>       
       <td id="fuente1"><select name="id_i[]" id="id_i[]" style="width:150px">
         <option value=""<?php if (!(strcmp("", $id))) {echo "selected=\"selected\"";} ?>>Ref</option>
         <?php
do {  
?>
         <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $id))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
         <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
       </select></td>   
       <td id="fuente1"><input name="valor[]" readonly type="text" size="5"value="<?php $valor=mysql_result($unidad_siete,$x,str_valor_pmi); echo $valor;?>" style="width:50px"/></td>
       <td id="fuente1"><input name="cant[]" type="number" style="width:80px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
       <td id="fuente1"><input name="desp[]" type="number" style="width:80px" placeholder="kilos" min="0"step="0.01" value=""/></td>       
       </tr>
	   <?php  } ?> 
        <tr id="tr1">
        <td  nowrap="nowrap"id="subtitulo1">UNIDAD 8</td>
        <td  nowrap="nowrap"id="subtitulo1">REFERENCIAS</td>
        <td  nowrap="nowrap"id="subtitulo1">%</td>
        <td  nowrap="nowrap"id="subtitulo1">CONSUMO</td>
        <td  nowrap="nowrap"id="subtitulo1">DESPERDICIO</td>
        </tr> 
	   <?php  for ($x=0;$x<=$totalRows_unidad_ocho-1 ;$x++) { ?> 
       <tr>
       <td id="fuente1"><?php $var=mysql_result($unidad_ocho,$x,str_nombre_m); echo $var;$id=mysql_result($unidad_ocho,$x,id_i_pmi); ?></td>         
       <td id="fuente1"><select name="id_i[]" id="id_i[]" style="width:150px">
         <option value=""<?php if (!(strcmp("", $id))) {echo "selected=\"selected\"";} ?>>Ref</option>
         <?php
do {  
?>
         <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $id))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
         <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
       </select></td>   
       <td id="fuente1"><input name="valor[]" readonly type="text" size="5"value="<?php $valor=mysql_result($unidad_ocho,$x,str_valor_pmi); echo $valor;?>" style="width:50px"/></td>
       <td id="fuente1"><input name="cant[]" type="number" style="width:80px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
       <td id="fuente1"><input name="desp[]" type="number" style="width:80px" placeholder="kilos" min="0"step="0.01" value=""/></td>       
       </tr>
	   <?php  } ?> 
          <tr>
            <td colspan="5" id="dato2">
            </td>
            </tr>
          <tr>
            <td colspan="5" id="dato1">&nbsp;</td>
          </tr>
          <tr>
            <td ></td>
            </tr>
          <tr>
            <td colspan="5" id="dato2"><input type="submit" value="ADD A IMPRESION"/ onClick="envio_form(this);"><!--onClick="envio_form(this);"--></td>
            </tr>
        </table>
        <input type="hidden" name="MM_insert" value="form1" />
      </form></td>
  </tr>
  <tr>
    <td colspan="2" align="center">&nbsp;</td>
  </tr>
</table>
</div>
<b class="spiffy"> 
<b class="spiffy5"></b>
<b class="spiffy4"></b>
<b class="spiffy3"></b>
<b class="spiffy2"><b></b></b>
<b class="spiffy1"><b></b></b></b></div> 
</td></tr></table>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($mezclas);

mysql_free_result($materia_prima);

mysql_free_result($unidad_uno);

mysql_free_result($unidad_dos);

mysql_free_result($unidad_tres);

mysql_free_result($unidad_cuatro);

mysql_free_result($unidad_cinco);

mysql_free_result($unidad_seis);

mysql_free_result($unidad_siete);

mysql_free_result($unidad_ocho);

?>