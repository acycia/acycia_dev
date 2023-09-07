<?php require_once('Connections/conexion1.php'); ?>
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

$id_ref=$_GET['id_ref'];
//LLAMA LAS MEZCLAS DE IMPRESION UNIDAD 1
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_uno = "select * from insumo, Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi='$id_ref' AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='1' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m AND Tbl_produccion_mezclas_impresion.id_i_pmi=insumo.id_insumo";
$unidad_uno = mysql_query($query_unidad_uno, $conexion1) or die(mysql_error());
$row_unidad_uno = mysql_fetch_assoc($unidad_uno);
$totalRows_unidad_uno = mysql_num_rows($unidad_uno);
$row_uno = mysql_fetch_array($unidad_uno);
//CARGA UNIDAD 2
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_dos = "select * from insumo, Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi='$id_ref' AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='2' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m AND Tbl_produccion_mezclas_impresion.id_i_pmi=insumo.id_insumo";
$unidad_dos = mysql_query($query_unidad_dos, $conexion1) or die(mysql_error());
$row_unidad_dos = mysql_fetch_assoc($unidad_dos);
$totalRows_unidad_dos = mysql_num_rows($unidad_dos);
//CARGA UNIDAD 3
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_tres = "select * from insumo, Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi='$id_ref' AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='3' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m AND Tbl_produccion_mezclas_impresion.id_i_pmi=insumo.id_insumo";
$unidad_tres = mysql_query($query_unidad_tres, $conexion1) or die(mysql_error());
$row_unidad_tres = mysql_fetch_assoc($unidad_tres);
$totalRows_unidad_tres = mysql_num_rows($unidad_tres);
//CARGA UNIDAD 4
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_cuatro = "select * from insumo, Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi='$id_ref' AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='4' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m AND Tbl_produccion_mezclas_impresion.id_i_pmi=insumo.id_insumo";
$unidad_cuatro = mysql_query($query_unidad_cuatro, $conexion1) or die(mysql_error());
$row_unidad_cuatro = mysql_fetch_assoc($unidad_cuatro);
$totalRows_unidad_cuatro = mysql_num_rows($unidad_cuatro);
//CARGA UNIDAD 5
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_cinco = "select * from insumo, Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi='$id_ref' AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='5' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m AND Tbl_produccion_mezclas_impresion.id_i_pmi=insumo.id_insumo";
$unidad_cinco = mysql_query($query_unidad_cinco, $conexion1) or die(mysql_error());
$row_unidad_cinco = mysql_fetch_assoc($unidad_cinco);
$totalRows_unidad_cinco = mysql_num_rows($unidad_cinco);
//CARGA UNIDAD 6
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_seis = "select * from insumo, Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi='$id_ref' AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='6' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m AND Tbl_produccion_mezclas_impresion.id_i_pmi=insumo.id_insumo";
$unidad_seis = mysql_query($query_unidad_seis, $conexion1) or die(mysql_error());
$row_unidad_seis = mysql_fetch_assoc($unidad_seis);
$totalRows_unidad_seis = mysql_num_rows($unidad_seis);
//CARGA UNIDAD 7
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_siete = "select * from insumo, Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi='$id_ref' AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='7' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m AND Tbl_produccion_mezclas_impresion.id_i_pmi=insumo.id_insumo";
$unidad_siete = mysql_query($query_unidad_siete, $conexion1) or die(mysql_error());
$row_unidad_siete = mysql_fetch_assoc($unidad_siete);
$totalRows_unidad_siete = mysql_num_rows($unidad_siete);
//CARGA UNIDAD 8
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_ocho = "select * from insumo, Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi='$id_ref' AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='8' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m AND Tbl_produccion_mezclas_impresion.id_i_pmi=insumo.id_insumo";
$unidad_ocho = mysql_query($query_unidad_ocho, $conexion1) or die(mysql_error());
$row_unidad_ocho = mysql_fetch_assoc($unidad_ocho);
$totalRows_unidad_ocho = mysql_num_rows($unidad_ocho);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="css/formato.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php 
// DATOS
$id_ref=$_GET['id_ref'];
//ORDEN COMPRA
if ($id_ref!='')  
{		
?> <div id="acceso2">
<table id="tabla1">
      <tr id="tr1">
          <td colspan="9" id="titulo">MEZCLAS DE  IMPRESION</td>
        </tr>  
        <tr>
         <td colspan="5" id="dato5">
       <?php if($row_unidad_uno['id_pmi']!=''){?>       
        <tr>
        <td colspan="2"  nowrap="nowrap"id="fuente2">UNIDAD 1</td>
        <td  nowrap="nowrap"id="fuente1">%</td>
        <td  nowrap="nowrap"id="fuente1">KILOS</td>
        </tr>
                
	   <?php  for ($x=0;$x<=7 ;$x++) { ?> 
    
       <tr>         
       <td id="fuente1"><?php $id_i=mysql_result($unidad_uno,$x,id_insumo); $var=mysql_result($unidad_uno,$x,str_nombre_m); echo $var; ?></td>   
       <td id="fuente1"><input name="id_i[]" type="hidden" value="<?php echo $id_i; ?>" /><?php $nombre_insumo=mysql_result($unidad_uno,$x,descripcion_insumo); echo $nombre_insumo; ?></td>
       <td id="fuente1"><input name="valor[]" readonly type="text" size="5"value="<?php $valor=mysql_result($unidad_uno,$x,str_valor_pmi); echo $valor;?>"/></td>
       <td id="fuente1"><input name="cant[]" type="number" style="width:47px" min="0"step="0.01" value=""/></td>       
       </tr>
	   <?php  } ?>   <?php }?>      
       <tr>
         <td colspan="5" id="dato9"></td>
         <td colspan="5" nowrap="nowrap" id="fuente10"></td>
       </tr>
       <tr>
         <td colspan="5" id="dato8"></td>
         <td colspan="5" nowrap="nowrap" id="fuente9"></td>
       </tr>
         
       <tr>
         <td colspan="5" id="dato7">
		 <?php if($row_unidad_dos['id_pmi']!=''){?>
         <tr>        
        <td colspan="2"  nowrap="nowrap"id="fuente2">UNIDAD 2</td>
        </tr> 
		       
	   <?php  for ($x=0;$x<=7 ;$x++) { ?> 
        
       <tr>         
       <td id="fuente1"><?php $id_i=mysql_result($unidad_dos,$x,id_insumo); $var=mysql_result($unidad_dos,$x,str_nombre_m); echo $var; ?></td>   
       <td id="fuente1"><input name="id_i[]" type="hidden" value="<?php echo $id_i; ?>" /><?php $nombre_insumo=mysql_result($unidad_dos,$x,descripcion_insumo); echo $nombre_insumo; ?></td>
       <td id="fuente1"><input name="valor[]" readonly type="text" size="5"value="<?php $valor=mysql_result($unidad_dos,$x,str_valor_pmi); echo $valor;?>"/></td>
       <td id="fuente1"><input name="cant[]" type="number" style="width:47px" min="0"step="0.01" value=""/></td>       
       </tr>
	   <?php  } ?><?php }?>
	   </td>
       </tr>
       <tr>
         <td colspan="5" id="dato13">
         <?php if($row_unidad_tres['id_pmi']!=''){?>   
         <tr>
        <td colspan="2"  nowrap="nowrap"id="fuente2">UNIDAD 3</td>
        </tr>
              
	   <?php  for ($x=0;$x<=7 ;$x++) { ?> 
        
       <tr>         
       <td id="fuente1"><?php $id_i=mysql_result($unidad_tres,$x,id_insumo); $var=mysql_result($unidad_tres,$x,str_nombre_m); echo $var; ?></td>   
       <td id="fuente1"><input name="id_i[]" type="hidden" value="<?php echo $id_i; ?>" /><?php $nombre_insumo=mysql_result($unidad_tres,$x,descripcion_insumo); echo $nombre_insumo; ?></td>
       <td id="fuente1"><input name="valor[]" readonly type="text" size="5"value="<?php $valor=mysql_result($unidad_tres,$x,str_valor_pmi); echo $valor;?>"/></td>
       <td id="fuente1"><input name="cant[]" type="number" style="width:47px" min="0"step="0.01" value=""/></td>       
       </tr>
	   <?php  } ?> <?php }?>
	   </td>
       </tr>
       <tr>
         <td colspan="5" id="dato12">
         <?php if($row_unidad_cuatro['id_pmi']!=''){?>  
        <tr>
        <td colspan="2"  nowrap="nowrap"id="fuente2">UNIDAD 4</td>
        </tr>
               
	   <?php  for ($x=0;$x<=7 ;$x++) { ?> 
       
       <tr>         
       <td id="fuente1"><?php $id_i=mysql_result($unidad_cuatro,$x,id_insumo); $var=mysql_result($unidad_cuatro,$x,str_nombre_m); echo $var; ?></td>   
       <td id="fuente1"><input name="id_i[]" type="hidden" value="<?php echo $id_i; ?>" /><?php $nombre_insumo=mysql_result($unidad_cuatro,$x,descripcion_insumo); echo $nombre_insumo; ?></td>
       <td id="fuente1"><input name="valor[]" readonly type="text" size="5"value="<?php $valor=mysql_result($unidad_cuatro,$x,str_valor_pmi); echo $valor;?>"/></td>
       <td id="fuente1"><input name="cant[]" type="number" style="width:47px" min="0"step="0.01" value=""/></td>       
       </tr>
	   <?php  } ?><?php }?> 
	   </td>
       </tr>
       <tr>
         <td colspan="5" id="dato11">
         <?php if($row_unidad_cinco['id_pmi']!=''){?>    
         <tr>
        <td colspan="2"  nowrap="nowrap"id="fuente2">UNIDAD 5</td>
        </tr> 
              
	   <?php  for ($x=0;$x<=7 ;$x++) { ?> 
        
       <tr>         
       <td id="fuente1"><?php $id_i=mysql_result($unidad_cinco,$x,id_insumo); $var=mysql_result($unidad_cinco,$x,str_nombre_m); echo $var; ?></td>   
       <td id="fuente1"><input name="id_i[]" type="hidden" value="<?php echo $id_i; ?>" /><?php $nombre_insumo=mysql_result($unidad_cinco,$x,descripcion_insumo); echo $nombre_insumo; ?></td>
       <td id="fuente1"><input name="valor[]" readonly type="text" size="5"value="<?php $valor=mysql_result($unidad_cinco,$x,str_valor_pmi); echo $valor;?>"/></td>
       <td id="fuente1"><input name="cant[]" type="number" style="width:47px" min="0"step="0.01" value=""/></td>       
       </tr>
	   <?php  } ?><?php }?> 
	   </td>
       </tr>
       <tr>
         <td colspan="5" id="dato10">
         <?php if($row_unidad_seis['id_pmi']!=''){?>   
         <tr>
        <td colspan="2"  nowrap="nowrap"id="fuente2">UNIDAD 6</td>
        </tr> 
               
	   <?php  for ($x=0;$x<=7 ;$x++) { ?> 
        
       <tr>         
       <td id="fuente1"><?php $id_i=mysql_result($unidad_seis,$x,id_insumo); $var=mysql_result($unidad_seis,$x,str_nombre_m); echo $var; ?></td>   
       <td id="fuente1"><input name="id_i[]" type="hidden" value="<?php echo $id_i; ?>" /><?php $nombre_insumo=mysql_result($unidad_seis,$x,descripcion_insumo); echo $nombre_insumo; ?></td>
       <td id="fuente1"><input name="valor[]" readonly type="text" size="5"value="<?php $valor=mysql_result($unidad_seis,$x,str_valor_pmi); echo $valor;?>"/></td>
       <td id="fuente1"><input name="cant[]" type="number" style="width:47px" min="0"step="0.01" value=""/></td>       
       </tr>
	   <?php  } ?><?php }?>
	   </td>
       </tr>
       <tr>
         <td colspan="5" id="dato15">
         <?php if($row_unidad_siete['id_pmi']!=''){?>  
         <tr>
        <td colspan="2"  nowrap="nowrap"id="fuente2">UNIDAD 7</td>
        </tr>
             
	   <?php  for ($x=0;$x<=7 ;$x++) { ?>         
       <tr>         
       <td id="fuente1"><?php $id_i=mysql_result($unidad_siete,$x,id_insumo); $var=mysql_result($unidad_siete,$x,str_nombre_m); echo $var; ?></td>   
       <td id="fuente1"><input name="id_i[]" type="hidden" value="<?php echo $id_i; ?>" /><?php $nombre_insumo=mysql_result($unidad_siete,$x,descripcion_insumo); echo $nombre_insumo; ?></td>
       <td id="fuente1"><input name="valor[]" readonly type="text" size="5"value="<?php $valor=mysql_result($unidad_siete,$x,str_valor_pmi); echo $valor;?>"/></td>
       <td id="fuente1"><input name="cant[]" type="number" style="width:47px" min="0"step="0.01" value=""/></td>       
       </tr>
	   <?php  } ?><?php }?>  
	   
         </td>
       </tr>
       <tr>
         <td colspan="5" id="dato14">
         <?php if($row_unidad_ocho['id_pmi']!=''){?>  
         <tr>
        <td colspan="2"  nowrap="nowrap"id="fuente2">UNIDAD 8</td>
        </tr> 
             
	   <?php  for ($x=0;$x<=7 ;$x++) { ?> 
         
       <tr>         
       <td id="fuente1"><?php $id_i=mysql_result($unidad_ocho,$x,id_insumo); $var=mysql_result($unidad_ocho,$x,str_nombre_m); echo $var; ?></td>   
       <td id="fuente1"><input name="id_i[]" type="hidden" value="<?php echo $id_i; ?>" /><?php $nombre_insumo=mysql_result($unidad_ocho,$x,descripcion_insumo); echo $nombre_insumo; ?></td>
       <td id="fuente1"><input name="valor[]" readonly type="text" size="5"value="<?php $valor=mysql_result($unidad_ocho,$x,str_valor_pmi); echo $valor;?>"/></td>
       <td id="fuente1"><input name="cant[]" type="number" style="width:47px" min="0"step="0.01" value=""/></td>       
       </tr>
	   <?php  } ?><?php }?> 
	  </td>
       </tr>
       <tr>
         <td colspan="5" id="dato6"></td>
         <td colspan="5" nowrap="nowrap" id="fuente7"></td>
       </tr>
      <tr id="tr1">
        <td colspan="13" id="fuente2"></td>
      </tr>
    </table>
  </div>
<?php }?>
</body>
</html>
<?php
mysql_free_result($usuario);
mysql_free_result($mezcla);

?>