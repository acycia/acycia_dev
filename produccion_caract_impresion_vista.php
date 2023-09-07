<?php require_once('Connections/conexion1.php'); ?><?php
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
//CARGA UNIDAD 1
$colname_unidad_uno = "-1";
if (isset($_GET['id_ref'])) {
  $colname_unidad_uno  = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_uno = sprintf("select * from insumo, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi=%s AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='1' AND Tbl_produccion_mezclas_impresion.id_i_pmi=insumo.id_insumo",$colname_unidad_uno);
$unidad_uno = mysql_query($query_unidad_uno, $conexion1) or die(mysql_error());
$row_unidad_uno = mysql_fetch_assoc($unidad_uno);
$totalRows_unidad_uno = mysql_num_rows($unidad_uno);
//CARGA UNIDAD 2
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_dos = sprintf("select * from insumo, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi=%s AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='2' AND Tbl_produccion_mezclas_impresion.id_i_pmi=insumo.id_insumo",$colname_unidad_uno);
$unidad_dos = mysql_query($query_unidad_dos, $conexion1) or die(mysql_error());
$row_unidad_dos = mysql_fetch_assoc($unidad_dos);
$totalRows_unidad_dos = mysql_num_rows($unidad_dos);
//CARGA UNIDAD 3
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_tres = sprintf("select * from insumo, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi=%s AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='3' AND Tbl_produccion_mezclas_impresion.id_i_pmi=insumo.id_insumo",$colname_unidad_uno);
$unidad_tres = mysql_query($query_unidad_tres, $conexion1) or die(mysql_error());
$row_unidad_tres = mysql_fetch_assoc($unidad_tres);
$totalRows_unidad_tres = mysql_num_rows($unidad_tres);
//CARGA UNIDAD 4
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_cuatro = sprintf("select * from insumo, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi=%s AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='4' AND Tbl_produccion_mezclas_impresion.id_i_pmi=insumo.id_insumo",$colname_unidad_uno);
$unidad_cuatro = mysql_query($query_unidad_cuatro, $conexion1) or die(mysql_error());
$row_unidad_cuatro = mysql_fetch_assoc($unidad_cuatro);
$totalRows_unidad_cuatro = mysql_num_rows($unidad_cuatro);
//CARGA UNIDAD 5
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_cinco = sprintf("select * from insumo, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi=%s AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='5' AND Tbl_produccion_mezclas_impresion.id_i_pmi=insumo.id_insumo",$colname_unidad_uno);
$unidad_cinco = mysql_query($query_unidad_cinco, $conexion1) or die(mysql_error());
$row_unidad_cinco = mysql_fetch_assoc($unidad_cinco);
$totalRows_unidad_cinco = mysql_num_rows($unidad_cinco);
//CARGA UNIDAD 6
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_seis = sprintf("select * from insumo, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi=%s AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='6' AND Tbl_produccion_mezclas_impresion.id_i_pmi=insumo.id_insumo",$colname_unidad_uno);
$unidad_seis = mysql_query($query_unidad_seis, $conexion1) or die(mysql_error());
$row_unidad_seis = mysql_fetch_assoc($unidad_seis);
$totalRows_unidad_seis = mysql_num_rows($unidad_seis);
//CARGA UNIDAD 7
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_siete = sprintf("select * from insumo, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi=%s AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='7' AND Tbl_produccion_mezclas_impresion.id_i_pmi=insumo.id_insumo",$colname_unidad_uno);
$unidad_siete = mysql_query($query_unidad_siete, $conexion1) or die(mysql_error());
$row_unidad_siete = mysql_fetch_assoc($unidad_siete);
$totalRows_unidad_siete = mysql_num_rows($unidad_siete);
//CARGA UNIDAD 8
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_ocho = sprintf("select * from insumo, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi=%s AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='8' AND Tbl_produccion_mezclas_impresion.id_i_pmi=insumo.id_insumo",$colname_unidad_uno);
$unidad_ocho = mysql_query($query_unidad_ocho, $conexion1) or die(mysql_error());
$row_unidad_ocho = mysql_fetch_assoc($unidad_ocho);
$totalRows_unidad_ocho = mysql_num_rows($unidad_ocho);
//MENU
$colname_referencia_egp = "-1";
if (isset($_GET['id_ref'])) {
  $colname_referencia_egp = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_referencia_egp = sprintf("SELECT * FROM Tbl_referencia, Tbl_egp WHERE Tbl_referencia.id_ref = '%s' AND Tbl_referencia.n_egp_ref = Tbl_egp.n_egp", $colname_referencia_egp);
$referencia_egp = mysql_query($query_referencia_egp, $conexion1) or die(mysql_error());
$row_referencia_egp = mysql_fetch_assoc($referencia_egp);
$totalRows_referencia_egp = mysql_num_rows($referencia_egp);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="css/vista.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/vista.js"></script>
<script type="text/javascript" src="js/formato.js"></script>
<title>SISADGE AC & CIA</title>
</head>
<body>
<div align="center">
<table id="tablainterna">
  <tr>    
     <td colspan="8" id="principal">PROCESO IMPRESION MEZCLAS</td>
  </tr>
  <tr>
    <td rowspan="6" id="fondo"><img src="images/logoacyc.jpg"/></td>
    <td colspan="7" id="dato3"><a href="produccion_caract_impresion_edit.php?id_ref=<?php echo $_GET['id_ref']; ?>&ref=<?php echo $row_referencia_egp['cod_ref']; ?>"><img src="images/menos.gif" alt="EDITAR"title="EDITAR" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" border="0"/><img src="images/impresor.gif" onClick="window.print();" style="cursor:hand;" alt="INPRIMIR"title="INPRIMIR" /></a></td>
    </tr>
  <tr>
    <td id="dato3">Menu de Produccion</td>
    <td colspan="4" id="dato3"><?php $ref=$row_referencia_egp['id_ref'];
	  $sqlpm="SELECT * FROM Tbl_produccion_mezclas WHERE id_ref_pm='$ref' and id_proceso='1'";
	  $resultpm= mysql_query($sqlpm);
	  $row_pm = mysql_fetch_assoc($resultpm);
	  $numpm= mysql_num_rows($resultpm);
	  if($numpm >='1')
	  { ?>
      <a href="produccion_caract_extrusion_mezcla_vista.php?id_ref=<?php echo $row_referencia_egp['id_ref']; ?>&id_pm=<?php echo $row_pm['id_pm']; ?>"><img src="images/e.gif" style="cursor:hand;" alt="EXTRUSION" title="EXTRUSION" border="0" /></a>
      <?php } else{ ?>
      <a href="produccion_mezclas_add.php?id_ref=<?php echo $row_referencia_egp['id_ref']; ?>&cod_ref=<?php echo $row_referencia_egp['cod_ref']; ?>"><img src="images/e.gif" style="cursor:hand;" alt="ADD FORMULA EXTRUSION" title="ADD FORMULA EXTRUSION" border="0" /></a>
      <?php } ?>
      <?php $ref=$row_referencia_egp['id_ref'];
	  $sqlci="SELECT DISTINCT id_ref_pmi,id_proceso FROM Tbl_produccion_mezclas_impresion WHERE id_ref_pmi='$ref' and id_proceso='2'";
	  $resultci= mysql_query($sqlci);
	  $row_ci = mysql_fetch_assoc($resultci);
	  $numci= mysql_num_rows($resultci);
	  if($numci >='1')
	  { ?>
      <a href="produccion_caract_impresion_vista.php?id_ref=<?php echo $row_referencia_egp['id_ref']; ?>"><img src="images/i.gif" style="cursor:hand;" alt="IMPRESION" title="IMPRESION" border="0" /></a>
      <?php } else{ ?>
      <a href="produccion_caract_impresion_add.php?id_ref=<?php echo $row_referencia_egp['id_ref']; ?>&cod_ref=<?php echo $row_referencia_egp['cod_ref']; ?>"><img src="images/i.gif" style="cursor:hand;" alt="ADD FORMULA IMPRESION" title="ADD FORMULA IMPRESION" border="0" /></a>
      <?php } ?>
	 </td>
    </tr>    
  <tr>
    <td colspan="3" id="subppal2">FECHA DE INGRESO </td>
    <td colspan="4" id="subppal2"><?php  $id_ref=$_GET['id_ref']?>
      RESPONSABLE</td>
    </tr>
  <tr>
    <td colspan="3" id="fuente2"><?php $con="select DISTINCT id_ref_pmi,fecha_registro_pmi,b_borrado_pmi  from Tbl_produccion_mezclas_impresion WHERE id_ref_pmi=$id_ref AND b_borrado_pmi='0' ";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['fecha_registro_pmi'];?></td>
    <td colspan="4" nowrap id="fuente2"><?php $con="select DISTINCT id_ref_pmi,str_registro_pmi,b_borrado_pmi  from Tbl_produccion_mezclas_impresion WHERE id_ref_pmi=$id_ref AND b_borrado_pmi='0'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_registro_pmi'];?></td>
    </tr>
  <tr>
    <td colspan="3" id="subppal2">Referencia</td>
    <td id="subppal2">Version</td>
    <td colspan="3" id="subppal2">REF-EGP</td>
    </tr>
  <tr>
    <td colspan="3" nowrap id="fuente2"><?php $con="select DISTINCT id_ref_pmi,int_cod_ref_pmi,b_borrado_pmi  from Tbl_produccion_mezclas_impresion WHERE id_ref_pmi=$id_ref AND b_borrado_pmi='0'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['int_cod_ref_pmi'];?></td>
    <td id="fuente2"><?php $con="select DISTINCT id_ref_pmi,version_ref_pmi,b_borrado_pmi  from Tbl_produccion_mezclas_impresion WHERE id_ref_pmi=$id_ref AND b_borrado_pmi='0'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['version_ref_pmi'];?></td>
    <td colspan="3" id="fuente2"><a href="referencia_bolsa_edit.php?id_ref=<?php echo $row_referencia_egp['id_ref']; ?>&amp;n_egp=<?php echo $row_referencia_egp['cod_ref']; ?>" title="REF-EGP" target="new"><em>REF-EGP</em></a></td>
    </tr>
  <tr>
    <td colspan="7" id="fondo">Alguna Inquietud o Comentario : sistemas@acycia.com </td>
    </tr>
       <tr>
          <td colspan="8" id="subppal2">MEZCLA DE IMPRESION</td>
       </tr> 
    <tr>
          <td colspan="8" id="fuente3">

   <table id="tablainterna">
     <tr> 
       <td valign="top">
         <div id="tintas"><?php if($totalRows_unidad_uno!='0') { ?>              
         <table>
              <td nowrap colspan="<?php echo $totalRows_unidad_uno+1; ?>" id="subppal2"><strong>UNIDAD 1</strong></td>
            </tr>
            <tr><?php  for ($y=0;$y<=$totalRows_unidad_uno-1;$y++) { ?> 
            <td width="300" id="fuente3"><?php $var=mysql_result($unidad_uno,$y,descripcion_insumo); if($var!=''){echo $var;}else{echo "MP eliminada";} ?></td>           
            <td id="fuente3"><?php $var1=mysql_result($unidad_uno,$y,str_valor_pmi); echo $var1; ?></td>         
        </tr>  <?php  } ?>                                        
       </table> 
      <?php  } ?></div>       

      <div id="tintas"><?php if($totalRows_unidad_dos!='0') { ?>               
      <table>
        <tr>
            <td nowrap colspan="<?php echo $totalRows_unidad_dos+1; ?>" id="subppal2"><strong>UNIDAD 2</strong></td>
            </tr>
            <tr><?php  for ($x=0;$x<=$totalRows_unidad_dos-1;$x++) { ?>
            <td width="300" id="fuente3"><?php $var=mysql_result($unidad_dos,$x,descripcion_insumo);if($var!=''){echo $var;}else{echo "MP eliminada";} ?></td>           
            <td id="fuente3"><?php $var1=mysql_result($unidad_dos,$x,str_valor_pmi); echo $var1; ?></td>         
         </tr>  <?php  } ?>                                        
       </table>
       <?php  } ?></div>       
<div id="tintas"><?php if($totalRows_unidad_tres!='0') { ?>
         <table>
           <tr>
             <td nowrap colspan="<?php echo $totalRows_unidad_tres+1; ?>" id="subppal"><strong>UNIDAD 3</strong></td>
           </tr>
           <tr>
             <?php  for ($y=0;$y<=$totalRows_unidad_tres-1;$y++) { ?>
             <td width="300"id="fuente3"><?php $var=mysql_result($unidad_tres,$y,descripcion_insumo);if($var!=''){echo $var;}else{echo "MP eliminada";} ?></td>
             <td id="fuente3"><?php $var1=mysql_result($unidad_tres,$y,str_valor_pmi); echo $var1; ?></td>
           </tr>
           <?php  } ?>
         </table>
         <?php  } ?></div>
		 <div id="tintas"><?php if($totalRows_unidad_cuatro!='0') { ?>
         <table>
           <tr>
             <td nowrap colspan="<?php echo $totalRows_unidad_cuatro+1; ?>" id="subppal2"><strong>UNIDAD 4</strong></td>
           </tr>
           <tr>
             <?php  for ($y=0;$y<=$totalRows_unidad_cuatro-1;$y++) { ?>
             <td width="300"  id="fuente3"><?php $var=mysql_result($unidad_cuatro,$y,descripcion_insumo);if($var!=''){echo $var;}else{echo "MP eliminada";} ?></td>
             <td id="fuente3"><?php $var=mysql_result($unidad_cuatro,$y,str_valor_pmi); echo $var; ?></td>
           </tr>
           <?php  } ?>
         </table>
         <?php  } ?></div>
		 <div id="tintas"><?php if($totalRows_unidad_cinco!='0') { ?>
         <table>
           <tr>
             <td nowrap colspan="<?php echo $totalRows_unidad_cinco+1; ?>" id="subppal2"><strong>UNIDAD 5</strong></td>
           </tr>
           <tr>
             <?php  for ($y=0;$y<=$totalRows_unidad_cinco-1;$y++) { ?>
             <td  width="300" id="fuente3"><?php $var=mysql_result($unidad_cinco,$y,descripcion_insumo);if($var!=''){echo $var;}else{echo "MP eliminada";} ?></td>
             <td id="fuente3"><?php $var=mysql_result($unidad_cinco,$y,str_valor_pmi); echo $var; ?></td>
           </tr>
           <?php  } ?>
         </table>
         <?php  } ?></div>
		 <div id="tintas"><?php if($totalRows_unidad_seis!='0') { ?>
        <table>
           <tr>
             <td nowrap colspan="<?php echo $totalRows_unidad_seis+1; ?>" id="subppal2"><strong>UNIDAD 6</strong></td>
           </tr>
           <tr>
             <?php  for ($y=0;$y<=$totalRows_unidad_seis-1;$y++) { ?>
             <td width="300" id="fuente3"><?php $var=mysql_result($unidad_seis,$y,descripcion_insumo);if($var!=''){echo $var;}else{echo "MP eliminada";} ?></td>
             <td id="fuente3"><?php $var=mysql_result($unidad_seis,$y,str_valor_pmi); echo $var; ?></td>
           </tr>
           <?php  } ?>
         </table>
         <?php  } ?></div>
		 <div id="tintas"><?php if($totalRows_unidad_siete!='0') { ?>
         <table>
           <tr>
             <td nowrap colspan="<?php echo $totalRows_unidad_siete+1; ?>" id="subppal2"><strong>UNIDAD 7</strong></td>
           </tr>
           <tr>
             <?php  for ($y=0;$y<=$totalRows_unidad_siete-1;$y++) { ?>
             <td width="300" id="fuente3"><?php $var=mysql_result($unidad_siete,$y,descripcion_insumo);if($var!=''){echo $var;}else{echo "MP eliminada";} ?></td>
             <td id="fuente3"><?php $var=mysql_result($unidad_siete,$y,str_valor_pmi); echo $var; ?></td>
           </tr>
           <?php  } ?>
         </table>
         <?php  } ?></div>
		 <div id="tintas"><?php if($totalRows_unidad_ocho!='0') { ?>
         <table>
           <tr>
             <td nowrap colspan="<?php echo $totalRows_unidad_ocho+1; ?>" id="subppal2"><strong>UNIDAD 8</strong></td>
           </tr>
           <tr>
             <?php  for ($y=0;$y<=$totalRows_unidad_ocho-1;$y++) { ?>
             <td width="300" id="fuente3"><?php $var=mysql_result($unidad_ocho,$y,descripcion_insumo);if($var!=''){echo $var;}else{echo "MP eliminada";} ?></td>
             <td id="fuente3"><?php $var=mysql_result($unidad_ocho,$y,str_valor_pmi); echo $var; ?></td>
           </tr>
           <?php  } ?>
         </table>
         <?php  } ?></div>
         </td>
     </tr>
     <tr>
       <td colspan="2" id="fondo1" valign="top"><strong>Nota:</strong> si no aparece las mezclas puede ser que haya eliminado el insumo</td>
       </tr>
     </table>
     <table id="tablainterna">
     
      <tr id="tr1">
        <td colspan="12" id="subppal2">CARACTERISTICAS DE IMPRESION</td>
        </tr> 
         <tr>
          <?php  for ($x=0;$x<=$totalRows_caract_valor-1;$x++) { ?>          
          <td width="137" id="fuente3"><?php $id_cv=mysql_result($caract_valor,$x,id_cv); $var=mysql_result($caract_valor,$x,str_nombre_caract_c);if($var!=''){echo $var;}else{echo "MP eliminada";} ?>                                             
         <?php $valor=mysql_result($caract_valor,$x,str_valor_cv); echo $valor;?>
          </td>
         <?php  } ?>
         </tr>                                                     
      </table>   
</table>
</div>
</body>
</html>
<?php

mysql_free_result($usuario); 
mysql_free_result($unidad_uno);
mysql_free_result($unidad_dos);
mysql_free_result($unidad_tres);
mysql_free_result($unidad_cuatro);
mysql_free_result($unidad_cinco);
mysql_free_result($unidad_seis);
mysql_free_result($unidad_siete);
mysql_free_result($unidad_ocho);
mysql_free_result($caract_valor);

mysql_close($conexion1);
?>
