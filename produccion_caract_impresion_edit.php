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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
	$pmi=($_POST['id_pmi']);
  foreach($pmi as $key=>$v)
    $a[]= $v;

  $id=($_POST['id']);
  foreach($id as $key=>$v)
    $b[]= $v;

  $valor=($_POST['valor']);
  foreach($valor as $key=>$v)
    $c[]= $v;

  for($x=0; $x<count($b); $x++) 
  {
		//if($a[$x]!=''&&$b[$x]!=''&&$c[$x]!=''){			 
    $updateSQL = sprintf("UPDATE Tbl_produccion_mezclas_impresion SET fecha_registro_pmi=%s, str_registro_pmi=%s, id_i_pmi=%s,  str_valor_pmi=%s,  observ_pmi=%s WHERE id_pmi=%s",                      
     GetSQLValueString($_POST['fecha_registro_pmi'], "date"),
     GetSQLValueString($_POST['str_registro_pmi'], "text"),
     GetSQLValueString($b[$x], "text"),
     GetSQLValueString($c[$x], "text"),
     GetSQLValueString($_POST['observ_pmi'], "text"),
     GetSQLValueString($a[$x], "int"));

    mysql_select_db($database_conexion1, $conexion1);
    $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());
	}//llave de for    
	//}	  

	//UPDATE CARACTERISTICAS VALOR
	$id_cv=($_POST['id_cv']);
  foreach($id_cv as $key=>$v)
    $d[]= $v;

  $valor_cv=($_POST['valor_cv']);
  foreach($valor_cv as $key=>$v)
    $e[]= $v;

  for($y=0; $y<count($d); $y++){	
	//if($d[$y]!=''&&$e[$y]!=''){		
    $updateSQL2 = sprintf("UPDATE Tbl_caracteristicas_valor SET str_valor_cv=%s, fecha_registro_cv=%s, str_registro_cv=%s WHERE id_cv=%s",
      GetSQLValueString($e[$y], "text"),
      GetSQLValueString($_POST['fecha_registro_pmi'], "date"),
      GetSQLValueString($_POST['str_registro_pmi'], "text"),
      GetSQLValueString($d[$y], "int"));

    mysql_select_db($database_conexion1, $conexion1);
    $Result2 = mysql_query($updateSQL2, $conexion1) or die(mysql_error());
 	}//llave de for    
	//}

  $updateGoTo = "produccion_caract_impresion_vista.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo)); 	
}
$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = $_SESSION['MM_Username'];
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = %s", GetSQLValueString($colname_usuario, "text"));
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);
//CONSULTA COLOR EGP
$colname_ref = "-1";
if (isset($_GET['id_ref'])) 
{
  $colname_ref= (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ref = sprintf("SELECT * FROM Tbl_referencia,Tbl_egp WHERE Tbl_referencia.id_ref=%s AND Tbl_referencia.n_egp_ref=Tbl_egp.n_egp ",$colname_ref);
$ref = mysql_query($query_ref, $conexion1) or die(mysql_error());
$row_ref = mysql_fetch_assoc($ref);
$totalRows_ref = mysql_num_rows($ref);
//LLENA COMBOS DE MATERIAS PRIMAS
mysql_select_db($database_conexion1, $conexion1);
$query_materia_prima = " SELECT * FROM insumo WHERE insumo.clase_insumo='8' AND insumo.estado_insumo='0' ORDER BY descripcion_insumo ASC";
$materia_prima = mysql_query($query_materia_prima, $conexion1) or die(mysql_error());
$row_materia_prima = mysql_fetch_assoc($materia_prima);
$totalRows_materia_prima = mysql_num_rows($materia_prima);
//LLAMA LAS UNIDADES DE IMPRESION
mysql_select_db($database_conexion1, $conexion1);
$query_insumo_impresion = "SELECT * FROM Tbl_insumo_impresion ORDER BY id_i ASC";
$insumo_impresion= mysql_query($query_insumo_impresion, $conexion1) or die(mysql_error());
$row_insumo_impresion = mysql_fetch_assoc($insumo_impresion);
$totalRows_insumo_impresion = mysql_num_rows($insumo_impresion);
//CARGA UNIDAD 1
$colname_unidad_uno = "-1";
if (isset($_GET['id_ref'])) {
  $colname_unidad_uno  = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_uno = sprintf("select * from Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi=%s AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='1' AND Tbl_mezclas.id_m=Tbl_produccion_mezclas_impresion.id_m ORDER  BY Tbl_produccion_mezclas_impresion.id_m ASC",$colname_unidad_uno);
$unidad_uno = mysql_query($query_unidad_uno, $conexion1) or die(mysql_error());
$row_unidad_uno = mysql_fetch_assoc($unidad_uno);
$totalRows_unidad_uno = mysql_num_rows($unidad_uno);
//CARGA UNIDAD 2
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_dos = sprintf("select * from Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi=%s AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='2' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m ORDER  BY Tbl_produccion_mezclas_impresion.id_m ASC",$colname_unidad_uno);
$unidad_dos = mysql_query($query_unidad_dos, $conexion1) or die(mysql_error());
$row_unidad_dos = mysql_fetch_assoc($unidad_dos);
$totalRows_unidad_dos = mysql_num_rows($unidad_dos);
//CARGA UNIDAD 3
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_tres = sprintf("select * from Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi=%s AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='3' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m ORDER  BY Tbl_produccion_mezclas_impresion.id_m ASC",$colname_unidad_uno);
$unidad_tres = mysql_query($query_unidad_tres, $conexion1) or die(mysql_error());
$row_unidad_tres = mysql_fetch_assoc($unidad_tres);
$totalRows_unidad_tres = mysql_num_rows($unidad_tres);
//CARGA UNIDAD 4
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_cuatro = sprintf("select * from Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi=%s AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='4' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m ORDER  BY Tbl_produccion_mezclas_impresion.id_m ASC",$colname_unidad_uno);
$unidad_cuatro = mysql_query($query_unidad_cuatro, $conexion1) or die(mysql_error());
$row_unidad_cuatro = mysql_fetch_assoc($unidad_cuatro);
$totalRows_unidad_cuatro = mysql_num_rows($unidad_cuatro);
//CARGA UNIDAD 5
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_cinco = sprintf("select * from Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi=%s AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='5' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m ORDER  BY Tbl_produccion_mezclas_impresion.id_m ASC",$colname_unidad_uno);
$unidad_cinco = mysql_query($query_unidad_cinco, $conexion1) or die(mysql_error());
$row_unidad_cinco = mysql_fetch_assoc($unidad_cinco);
$totalRows_unidad_cinco = mysql_num_rows($unidad_cinco);
//CARGA UNIDAD 6
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_seis = sprintf("select * from Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi=%s AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='6' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m ORDER  BY Tbl_produccion_mezclas_impresion.id_m ASC",$colname_unidad_uno);
$unidad_seis = mysql_query($query_unidad_seis, $conexion1) or die(mysql_error());
$row_unidad_seis = mysql_fetch_assoc($unidad_seis);
$totalRows_unidad_seis = mysql_num_rows($unidad_seis);
//CARGA UNIDAD 7
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_siete = sprintf("select * from Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi=%s AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='7' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m ORDER  BY Tbl_produccion_mezclas_impresion.id_m ASC",$colname_unidad_uno);
$unidad_siete = mysql_query($query_unidad_siete, $conexion1) or die(mysql_error());
$row_unidad_siete = mysql_fetch_assoc($unidad_siete);
$totalRows_unidad_siete = mysql_num_rows($unidad_siete);
//CARGA UNIDAD 8
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_ocho = sprintf("select * from Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi=%s AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='8' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m ORDER  BY Tbl_produccion_mezclas_impresion.id_m ASC",$colname_unidad_uno);
$unidad_ocho = mysql_query($query_unidad_ocho, $conexion1) or die(mysql_error());
$row_unidad_ocho = mysql_fetch_assoc($unidad_ocho);
$totalRows_unidad_ocho = mysql_num_rows($unidad_ocho);
//LLAMA LAS UNIDADES DE IMPRESION
$colname_caract = "-1";
if (isset($_GET['id_ref'])) {
  $colname_caract  = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_caract_valor = sprintf("SELECT * FROM Tbl_caracteristicas, Tbl_caracteristicas_valor WHERE Tbl_caracteristicas_valor.id_ref_cv='%s' AND Tbl_caracteristicas.id_c=Tbl_caracteristicas_valor.id_c_cv AND Tbl_caracteristicas.proceso_c='2' ORDER BY Tbl_caracteristicas_valor.id_cv ASC",$colname_caract);
$caract_valor = mysql_query($query_caract_valor, $conexion1) or die(mysql_error());
$row_caract_valor = mysql_fetch_assoc($caract_valor);
$totalRows_caract_valor = mysql_num_rows($caract_valor);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title>SISADGE AC &amp; CIA</title>
  <script src="js/jquery-1.3.2.min.js" type="text/javascript"></script>   
  <script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
  <link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">
  
  <link href="css/formato.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="js/formato.js"></script>
  <script type="text/javascript" src="js/validacion_numerico.js"></script>
  <script type="text/javascript" src="js/consulta.js"></script>
  <script type="text/javascript">
    function show_hide() {
      if(document.getElementById('check_sh1').checked) {
        document.getElementById('select_sh2').style.display = "none";
        document.getElementById('select_sh2').disabled = true;
      } else {

        document.getElementById('select_sh2').style.visibility = "visible";
        document.getElementById('select_sh2').style.display = "block";
        document.getElementById('select_sh2').disabled = false;
      }
    }
  </script>
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
                <td id="cabezamenu"><ul id="menuhorizontal">
                 <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                 <li><a href="referencias.php" target="new">REFERENCIAS</a></li>
               </ul>
             </td>
           </tr>  
           <tr>
            <td colspan="2" align="center">   
              <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1">
                <table id="tabla2">
                  <tr id="tr1">
                    <td colspan="9" id="titulo2">MEZCLAS DE  IMPRESION</td>
                  </tr>
                  <tr>
                    <td width="137" colspan="2" rowspan="6" id="dato2"><img src="images/logoacyc.jpg"/></td>
                    <td colspan="7" id="dato3"><a href="produccion_caract_impresion_vista.php?id_ref=<?php echo $row_ref['id_ref']; ?>"><img src="images/hoja.gif" alt="VISTA CARACTERISTICA" title="VISTA CARACTERISTICA" border="0" /></a><a href="produccion_mezclas_impresion.php"><img src="images/m.gif" style="cursor:hand;" alt="LISTADO MEZCLAS" title="LISTADO MEZCLAS" border="0" /></a><a href="produccion_caracteristicas.php"><img src="images/c.gif" style="cursor:hand;" alt="LISTADO CARACTERISTICAS" title="LISTADO CARACTERISTICAS" border="0" /></a><a href="produccion_referencias.php"><img src="images/rp.gif" style="cursor:hand;" alt="LISTADO REF. POR PROCESO" title="LISTADO REF. POR PROCESO" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" title="MENU PRINCIPAL" border="0"/></a></td>
                  </tr>
                  <tr id="tr1">
                    <td width="182" nowrap="nowrap" id="fuente1">Fecha Ingreso 
                      <input name="fecha_registro_pmi" type="date" autofocus="autofocus" id="fecha_registro_pmi" min="2000-01-02" value="<?php echo date("Y-m-d"); ?>" size="10" /></td>
                      <td colspan="6" id="fuente1">
                        Ingresado por
                        <input name="str_registro_pmi" type="text" id="str_registro_pmi" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="27" readonly="readonly"/></td>
                      </tr>
                      <tr id="tr3">
                        <td nowrap="nowrap" id="fuente2">&nbsp;</td>
                        <td width="126" colspan="2" nowrap="nowrap" id="fuente2">&nbsp;</td>
                        <td width="235" colspan="4" id="fuente2">&nbsp;</td>
                      </tr>
                      <tr id="tr3">
                        <td nowrap="nowrap" id="fuente2">Referencia</td>
                        <td colspan="2" id="fuente2">Version</td>
                        <td colspan="4" id="dato1"><!--<input type="button" name="GENERAR COPIA" id="check_sh1" value="GENERAR COPIA" onclick="show_hide(this)"/>--></td>
                      </tr>
                      <tr>
                        <td nowrap="nowrap" id="numero2"><input type="hidden" name="id_ref_pmi" id="id_ref_pmi" value="<?php echo $row_ref['id_ref']; ?>"/>
                          <input type="hidden" name="int_cod_ref_pmi" id="int_cod_ref_pmi" value="<?php echo $row_ref['cod_ref'] ?>"/>
                          <?php echo $row_ref['cod_ref']; ?></td>
                          <td colspan="2" nowrap="nowrap" id="numero2"><input type="hidden" name="version_ref_pmi" id="version_ref_pmi" value="<?php echo $row_ref['version_ref'] ?>"/>
                            <?php echo $row_ref['version_ref']; ?></td>
                            <td colspan="4" id="fuente2"></td>
                          </tr>
                          <tr>
                            <td id="dato2">&nbsp;</td>
                            <td colspan="2" id="dato2"><a href="referencia_bolsa_edit.php?id_ref=<?php echo $row_ref['id_ref']; ?>&amp;n_egp=<?php echo $row_ref['cod_ref']; ?>" title="REF-EGP" target="new"><em>REF-EGP</em></a></td>
                            <td colspan="4" id="dato2"><datalist id="dias"></datalist></td>
                          </tr>
                          <tr id="tr1">
                            <td colspan="8" id="titulo4">UNIDADES DE IMPRESION<?php $id_ref=$_GET['ref'];?></td>
                          </tr>

                          <tr>      
                           <td valign="top" colspan="2" nowrap="nowrap" >
                             <table>
                               <?php  if ($row_unidad_uno!='') { ?>
                               <tr>
                                 <td valign="top" nowrap="nowrap"id="fuente1"><strong>COLOR</strong></td>
                                 <td colspan="2" valign="top" nowrap="nowrap"id="fuente1"><?php echo $row_ref['color1_egp'] ?></td>
                               </tr>
                               <tr>
                                 <td valign="top" nowrap="nowrap"id="fuente3"><strong>UNIDAD 1</strong></td>
                                 <td colspan="2" valign="top" nowrap="nowrap"id="fuente3">%</td>
                               </tr>
                               <?php }?>
                               <?php  for ($x=0;$x<=$totalRows_unidad_uno-1 ;$x++) { ?>
                               <tr>
                                 <td id="detalle3"><strong>
                                   <?php $id=mysql_result($unidad_uno,$x,id_i_pmi);$id_pmi=mysql_result($unidad_uno,$x,id_pmi);$id_m=mysql_result($unidad_uno,$x,str_nombre_m);echo $id_m;?>
                                 </strong></td>
                                 <td nowrap="nowrap" id="fuente3"><input name="id_pmi[]" type="hidden" id="id_pmi[]" value="<?php echo $id_pmi; ?>" />
                                   <select name="id[]" id="id[]" style="width:60px">
                                     <option value=""<?php if (!(strcmp("", $id))) {echo "selected=\"selected\"";} ?>>Ref</option>
                                     <?php
                                     do {  
                                       ?>
                                       <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $id))) {echo "selected=\"selected\"";} ?>><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
                                       <?php
                                     } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                                     $rows = mysql_num_rows($materia_prima);
                                     if($rows > 0) {
                                      mysql_data_seek($materia_prima, 0);
                                      $row_materia_prima = mysql_fetch_assoc($materia_prima);
                                    }
                                    ?>
                                  </select>
                                  <input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]" value="<?php $valor=mysql_result($unidad_uno,$x,str_valor_pmi); echo $valor;?>"/>            </td>
                                </tr>
                                <?php  } ?>
                              </table></td> 

                              <td valign="top" colspan="2" nowrap="nowrap">
                                <table>
                                  <?php  if ($row_unidad_dos!='') { ?>
                                  <tr>
                                   <td valign="top" nowrap="nowrap"id="fuente1"><strong>COLOR</strong></td>
                                   <td colspan="2" valign="top" nowrap="nowrap"id="fuente1"><?php echo $row_ref['color2_egp'] ?></td>
                                 </tr>
                                 <tr>
                                  <td valign="top" nowrap="nowrap" id="fuente3"><strong>UNIDAD 2</strong></td>
                                  <td colspan="2" valign="top" nowrap="nowrap"id="fuente3">%</td>
                                </tr>
                                <?php }?>
                                <?php  for ($x=0;$x<=$totalRows_unidad_dos-1 ;$x++) { ?>
                                <tr>
                                  <td id="detalle3"><strong>
                                    <?php $id=mysql_result($unidad_dos,$x,id_i_pmi);$id_pmi=mysql_result($unidad_dos,$x,id_pmi);$id_m=mysql_result($unidad_dos,$x,str_nombre_m);echo $id_m; ?>
                                  </strong></td>
                                  <td  nowrap="nowrap" id="fuente3"><input name="id_pmi[]" type="hidden" id="id_pmi[]" value="<?php echo $id_pmi; ?>" />
                                    <select name="id[]" id="id[]" style="width:60px">
                                      <option value="0"<?php if (!(strcmp("", $id))) {echo "selected=\"selected\"";} ?>>Ref</option>
                                      <?php
                                      do {  
                                       ?>
                                       <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $id))) {echo "selected=\"selected\"";} ?>><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
                                       <?php
                                     } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                                     $rows = mysql_num_rows($materia_prima);
                                     if($rows > 0) {
                                      mysql_data_seek($materia_prima, 0);
                                      $row_materia_prima = mysql_fetch_assoc($materia_prima);
                                    }
                                    ?>
                                  </select>
                                  <input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]" value="<?php $valor=mysql_result($unidad_dos,$x,str_valor_pmi); echo $valor;?>"/>            </td>
                                </tr>
                                <?php  } ?>
                              </table>           
                            </td> 

                            <td valign="top"colspan="3" nowrap="nowrap">
                             <table>
                               <?php  if ($row_unidad_tres!='') { ?>
                               <tr>
                                 <td valign="top" nowrap="nowrap"id="fuente1"><strong>COLOR</strong></td>
                                 <td colspan="2" valign="top" nowrap="nowrap"id="fuente1"><?php echo $row_ref['color3_egp'] ?></td>
                               </tr>
                               <tr>
                                 <td valign="top" nowrap="nowrap"id="fuente3"><strong>UNIDAD 3</strong></td>
                                 <td colspan="2" valign="top" nowrap="nowrap"id="fuente3">%</td>
                               </tr>
                               <?php }?>
                               <?php  for ($x=0;$x<=$totalRows_unidad_tres-1 ;$x++) { ?>
                               <tr>
                                 <td id="detalle3"><strong>
                                   <?php $id=mysql_result($unidad_tres,$x,id_i_pmi);$id_pmi=mysql_result($unidad_tres,$x,id_pmi);$id_m=mysql_result($unidad_tres,$x,str_nombre_m);echo $id_m;?>
                                 </strong></td>
                                 <td nowrap="nowrap" id="fuente3"><input name="id_pmi[]" type="hidden" id="id_pmi[]" value="<?php echo $id_pmi; ?>" />
                                   <select name="id[]" id="id[]" style="width:60px">
                                     <option value="0"<?php if (!(strcmp("", $id))) {echo "selected=\"selected\"";} ?>>Ref</option>
                                     <?php
                                     do {  
                                       ?>
                                       <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $id))) {echo "selected=\"selected\"";} ?>><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
                                       <?php
                                     } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                                     $rows = mysql_num_rows($materia_prima);
                                     if($rows > 0) {
                                      mysql_data_seek($materia_prima, 0);
                                      $row_materia_prima = mysql_fetch_assoc($materia_prima);
                                    }
                                    ?>
                                  </select>
                                  <input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]" value="<?php $valor=mysql_result($unidad_tres,$x,str_valor_pmi); echo $valor;?>"/>            </td>
                                </tr>
                                <?php  } ?>
                              </table>         </td>    
                            </tr>         
                            <tr>
                             <td valign="top" colspan="2" id="fuente4">
                              <table>
                                <?php  if ($row_unidad_cuatro!='') { ?>
                                <tr>
                                 <td valign="top" nowrap="nowrap"id="fuente1"><strong>COLOR</strong></td>
                                 <td colspan="2" valign="top" nowrap="nowrap"id="fuente1"><?php echo $row_ref['color4_egp'] ?></td>
                               </tr>
                               <tr>
                                <td valign="top" nowrap="nowrap"id="fuente3"><strong>UNIDAD 4</strong></td>
                                <td colspan="2" valign="top" nowrap="nowrap"id="fuente3">%</td>
                              </tr>
                              <?php }?>
                              <?php  for ($x=0;$x<=$totalRows_unidad_cuatro-1 ;$x++) { ?>
                              <tr>
                                <td id="detalle3"><strong>
                                  <?php $id=mysql_result($unidad_cuatro,$x,id_i_pmi);$id_pmi=mysql_result($unidad_cuatro,$x,id_pmi); $id_m=mysql_result($unidad_cuatro,$x,str_nombre_m);echo $id_m;?>
                                </strong></td>
                                <td nowrap="nowrap" id="fuente3"><input name="id_pmi[]" type="hidden" id="id_pmi[]" value="<?php echo $id_pmi; ?>" />
                                  <select name="id[]" id="id[]" style="width:60px">
                                    <option value=""<?php if (!(strcmp("", $id))) {echo "selected=\"selected\"";} ?>>Ref</option>
                                    <?php
                                    do {  
                                     ?>
                                     <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $id))) {echo "selected=\"selected\"";} ?>><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
                                     <?php
                                   } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                                   $rows = mysql_num_rows($materia_prima);
                                   if($rows > 0) {
                                    mysql_data_seek($materia_prima, 0);
                                    $row_materia_prima = mysql_fetch_assoc($materia_prima);
                                  }
                                  ?>
                                </select>
                                <input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]" value="<?php $valor=mysql_result($unidad_cuatro,$x,str_valor_pmi); echo $valor;?>"/>            </td>
                              </tr>
                              <?php  } ?>
                            </table>         

                          </td>
                          <td valign="top" colspan="2" id="fuente4">
                            <table>
                              <?php  if ($row_unidad_cinco!='') { ?>
                              <tr>
                               <td valign="top" nowrap="nowrap"id="fuente1"><strong>COLOR</strong></td>
                               <td colspan="2" valign="top" nowrap="nowrap"id="fuente1"><?php echo $row_ref['color5_egp'] ?></td>
                             </tr>
                             <tr>
                              <td valign="top" nowrap="nowrap"id="fuente3"><strong>UNIDAD 5</strong></td>
                              <td colspan="2" valign="top" nowrap="nowrap"id="fuente3">%</td>
                            </tr>
                            <?php }?>
                            <?php  for ($x=0;$x<=$totalRows_unidad_cinco-1 ;$x++) { ?>
                            <tr>
                              <td id="detalle3"><strong>
                                <?php $id=mysql_result($unidad_cinco,$x,id_i_pmi);$id_pmi=mysql_result($unidad_cinco,$x,id_pmi); $id_m=mysql_result($unidad_cinco,$x,str_nombre_m);echo $id_m;?>
                              </strong></td>
                              <td  nowrap="nowrap" id="fuente3"><input name="id_pmi[]" type="hidden" id="id_pmi[]" value="<?php echo $id_pmi; ?>" />
                                <select name="id[]" id="id[]" style="width:60px">
                                  <option value="0"<?php if (!(strcmp("", $id))) {echo "selected=\"selected\"";} ?>>Ref</option>
                                  <?php
                                  do {  
                                   ?>
                                   <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $id))) {echo "selected=\"selected\"";} ?>><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
                                   <?php
                                 } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                                 $rows = mysql_num_rows($materia_prima);
                                 if($rows > 0) {
                                  mysql_data_seek($materia_prima, 0);
                                  $row_materia_prima = mysql_fetch_assoc($materia_prima);
                                }
                                ?>
                              </select>
                              <input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]" value="<?php $valor=mysql_result($unidad_cinco,$x,str_valor_pmi); echo $valor;?>"/>            </td>
                            </tr>
                            <?php  } ?>
                          </table>         
                        </td>
                        <td valign="top"colspan="3" nowrap="nowrap">
                         <table>
                           <?php  if ($row_unidad_seis!='') { ?>
                           <tr>
                             <td valign="top" nowrap="nowrap"id="fuente1"><strong>COLOR</strong></td>
                             <td colspan="2" valign="top" nowrap="nowrap"id="fuente1"><?php echo $row_ref['color6_egp'] ?></td>
                           </tr>
                           <tr>
                             <td valign="top" nowrap="nowrap"id="fuente3"><strong>UNIDAD 6</strong></td>
                             <td colspan="2" valign="top" nowrap="nowrap"id="fuente3">%</td>
                           </tr>
                           <?php }?>
                           <?php  for ($x=0;$x<=$totalRows_unidad_seis-1 ;$x++) { ?>
                           <tr>
                             <td id="detalle3"><strong>
                               <?php $id=mysql_result($unidad_seis,$x,id_i_pmi);$id_pmi=mysql_result($unidad_seis,$x,id_pmi); $id_m=mysql_result($unidad_seis,$x,str_nombre_m);echo $id_m;?>
                             </strong></td>
                             <td  nowrap="nowrap" id="fuente3"><input name="id_pmi[]" type="hidden" id="id_pmi[]" value="<?php echo $id_pmi; ?>" />
                              <select name="id[]" id="id[]" style="width:60px">
                                <option value="0"<?php if (!(strcmp("", $id))) {echo "selected=\"selected\"";} ?>>Ref</option>
                                <?php
                                do {  
                                 ?>
                                 <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $id))) {echo "selected=\"selected\"";} ?>><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
                                 <?php
                               } while ($row_materia_prima = mysql_fetch_assoc($materia_prima)); 
                               $rows = mysql_num_rows($materia_prima);
                               if($rows > 0) {
                                mysql_data_seek($materia_prima, 0);
                                $row_materia_prima = mysql_fetch_assoc($materia_prima);
                              }
                              ?>
                            </select>
                            <input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]" value="<?php $valor=mysql_result($unidad_seis,$x,str_valor_pmi); echo $valor;?>"/></td>
                          </tr>
                          <?php  } ?>
                        </table>         </td>
                      </tr>
                      <tr>
                        <td valign="top" colspan="2" id="fuente1">
                          <table>
                            <?php  if ($row_unidad_siete!='') { ?>
                            <tr>
                             <td valign="top" nowrap="nowrap"id="fuente1"><strong>COLOR</strong></td>
                             <td colspan="2" valign="top" nowrap="nowrap"id="fuente1"><?php echo $row_ref['color7_egp'] ?></td>
                           </tr>
                           <tr>
                            <td valign="top" nowrap="nowrap"id="fuente3"><strong>UNIDAD 7</strong></td>
                            <td colspan="2" valign="top" nowrap="nowrap"id="fuente3">%</td>
                          </tr>
                          <?php }?>
                          <?php  for ($x=0;$x<=$totalRows_unidad_siete-1 ;$x++) { ?>

                          <tr>
                            <td id="detalle3"><strong>
                              <?php $id=mysql_result($unidad_siete,$x,id_i_pmi);$id_pmi=mysql_result($unidad_siete,$x,id_pmi);$id_m=mysql_result($unidad_siete,$x,str_nombre_m);echo $id_m;?>
                            </strong></td>
                            <td  nowrap="nowrap" id="fuente3"><input name="id_pmi[]" type="hidden" id="id_pmi[]" value="<?php echo $id_pmi; ?>" />
                              <select name="id[]" id="id[]" style="width:60px">
                                <option value=""<?php if (!(strcmp("", $id))) {echo "selected=\"selected\"";} ?>>Ref</option>
                                <?php
                                do {  
                                 ?>
                                 <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $id))) {echo "selected=\"selected\"";} ?>><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
                                 <?php
                               } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                               $rows = mysql_num_rows($materia_prima);
                               if($rows > 0) {
                                mysql_data_seek($materia_prima, 0);
                                $row_materia_prima = mysql_fetch_assoc($materia_prima);
                              }
                              ?>
                            </select>
                            <input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]" value="<?php $valor=mysql_result($unidad_siete,$x,str_valor_pmi); echo $valor;?>"/>            </td>
                          </tr>
                          <?php  } ?>
                        </table>        
                      </td>
                      <td valign="top" colspan="2" id="fuente1">
                        <table>
                          <?php  if ($row_unidad_ocho!='') { ?>
                          <tr>
                           <td valign="top" nowrap="nowrap"id="fuente1"><strong>COLOR</strong></td>
                           <td colspan="2" valign="top" nowrap="nowrap"id="fuente1"><?php echo $row_ref['color8_egp'] ?></td>
                         </tr>
                         <tr>
                          <td valign="top" nowrap="nowrap"id="fuente3"><strong>UNIDAD 8</strong></td>
                          <td colspan="2" valign="top" nowrap="nowrap"id="fuente3">%</td>
                        </tr>
                        <?php }?>
                        <?php  for ($x=0;$x<=$totalRows_unidad_ocho-1 ;$x++) { ?>
                        <tr>
                          <td id="detalle3"><strong>
                            <?php $id=mysql_result($unidad_ocho,$x,id_i_pmi);$id_pmi=mysql_result($unidad_ocho,$x,id_pmi);$id_m=mysql_result($unidad_ocho,$x,str_nombre_m);echo $id_m;?>
                          </strong></td>
                          <td nowrap="nowrap"id="fuente3"><input name="id_pmi[]" type="hidden" id="id_pmi[]" value="<?php echo $id_pmi; ?>" />
                            <select name="id[]" id="id[]" style="width:60px">
                              <option value="0"<?php if (!(strcmp("", $id))) {echo "selected=\"selected\"";} ?>>Ref</option>
                              <?php
                              do {  
                               ?>
                               <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $id))) {echo "selected=\"selected\"";} ?>><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
                               <?php
                             } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                             $rows = mysql_num_rows($materia_prima);
                             if($rows > 0) {
                              mysql_data_seek($materia_prima, 0);
                              $row_materia_prima = mysql_fetch_assoc($materia_prima);
                            }
                            ?>
                          </select>
                          <input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]" value="<?php $valor=mysql_result($unidad_ocho,$x,str_valor_pmi); echo $valor;?>"/>            </td>
                        </tr>
                        <?php  } ?>
                      </table>        
                    </td>
                    <td colspan="3" id="fuente1">&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="12" id="fuente2"><textarea name="observ_pmi" id="observ_pmi" cols="80" rows="2"placeholder="OBSERVACIONES"onblur="conMayusculas(this)"><?php $con="select DISTINCT id_ref_pmi,int_cod_ref_pmi,b_borrado_pmi,observ_pmi  from Tbl_produccion_mezclas_impresion WHERE id_ref_pmi='$id_ref' AND b_borrado_pmi='0'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['observ_pmi'];?></textarea></td>
                  </tr>
                  <tr id="tr1">
                    <td colspan="12" id="fuente2"><input type="hidden" name="id_ref" id="id_ref" value="<?php echo $row_ref['id_ref']; ?>"/>
                      <input type="hidden" name="cod_ref" id="cod_ref" value="<?php echo $row_ref['cod_ref']; ?>"/>
                      <input type="hidden" name="id_proceso" id="id_proceso" value="2"/>
                      <input type="hidden" name="b_borrado_pmi" id="b_borrado_pmi" value="0"/></td>
                    </tr>

                    <tr>
                     <td colspan="7" id="fuente1">
                      <table border="0">
                       <tr id="tr1">
                         <td colspan="100%" id="titulo4">CARACTERISTICAS DE IMPRESION</td>
                       </tr> 
                       <tr>
                        <?php  for ($x=0;$x<=$totalRows_caract_valor-1;$x++) { ?>          
                        <td id="fuente1"><?php $id_cv=mysql_result($caract_valor,$x,id_cv); $var=mysql_result($caract_valor,$x,str_nombre_caract_c); echo $var; ?>                                             
                          <input name="id_cv[]" type="hidden" value="<?php echo $id_cv; ?>" /><input name="valor_cv[]" type="number" style="width:37px" min="0"step="1"  placeholder="Cant/Und" value="<?php $valor=mysql_result($caract_valor,$x,str_valor_cv); echo $valor;?>"/>
                        </td>
                        <?php  } ?>
                      </tr> 
                    </table></td>
                  </tr>
                  <tr>
                    <td colspan="7" id="fuente2"><input type="submit" name="Guardar" id="Guardar" value="Editar" /></td>
                  </tr>
                </table>
                <input type="hidden" name="id_pm_cv" id="id_pm_cv" value="0"/>
                <input type="hidden" name="version_ref_cv" id="version_ref_cv" value="<?php echo $row_ref['version_ref']; ?>" />
                <input type="hidden" name="id_proceso_cv" id="id_proceso_cv" value="2"/>
                <input type="hidden" name="id_ref_cv" id="id_ref_cv" value="<?php echo $row_ref['id_ref']; ?>"/>
                <input type="hidden" name="cod_ref_cv" id="cod_ref_cv" value="<?php echo $row_ref['cod_ref']; ?>"/>
                <input type="hidden" name="fecha_registro_cv" id="fecha_registro_cv"  value="<?php echo date("Y-m-d"); ?>"/>
                <input type="hidden" name="str_registro_cv" id="str_registro_cv" value="<?php echo $row_usuario['nombre_usuario']; ?>" />
                <input type="hidden" name="b_borrado_cv" id="b_borrado_cv" value="0"/>
                <input type="hidden" name="MM_update" value="form1">
              </form>  
            </td></tr>
          </table>


        </div>
        <b class="spiffy"> 
          <b class="spiffy5"></b>
          <b class="spiffy4"></b>
          <b class="spiffy3"></b>
          <b class="spiffy2"><b></b></b>
          <b class="spiffy1"><b></b></b></b></div> 
        </td>
      </tr>
    </table>
  </div>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($ref);

mysql_free_result($materia_prima);

mysql_close($conexion1);

?>
