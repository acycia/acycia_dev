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
//LLENA COMBOS DE MATERIAS PRIMAS
mysql_select_db($database_conexion1, $conexion1);
$query_materia_prima = "SELECT * FROM insumo ORDER BY descripcion_insumo ASC";
$materia_prima = mysql_query($query_materia_prima, $conexion1) or die(mysql_error());
$row_materia_prima = mysql_fetch_assoc($materia_prima);
$totalRows_materia_prima = mysql_num_rows($materia_prima);
//LLAMA LAS MEZCLAS DE IMPRESION UNIDAD 
$id_op=$_GET['id_op'];
$fecha=$_GET['fecha'];
mysql_select_db($database_conexion1, $conexion1);
$query_kilo_editar = "SELECT * FROM Tbl_reg_kilo_producido WHERE Tbl_reg_kilo_producido.op_rp='$id_op' AND Tbl_reg_kilo_producido.fecha_rkp='$fecha' AND Tbl_reg_kilo_producido.id_proceso_rkp='2' ORDER BY Tbl_reg_kilo_producido.id_rkp ASC";
$kilo_editar = mysql_query($query_kilo_editar, $conexion1) or die(mysql_error());
$row_kilo_editar = mysql_fetch_assoc($kilo_editar);
$totalRows_kilo_editar = mysql_num_rows($kilo_editar);
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
$id_op=$_GET['id_op'];
//ORDEN COMPRA
if ($id_op!='')  
{		
?> <div id="acceso2">
<table id="tabla1">
      <tr id="tr1">
          <td colspan="9" id="titulo">MEZCLAS DE  IMPRESION</td>
        </tr>
        
        <tr>
         <td colspan="2"  nowrap="nowrap"id="fuente2">PRODUCTOS</td>
        <td nowrap="nowrap"id="fuente1">KILOS INGRESADOS</td>
        <td nowrap="nowrap"id="fuente1">KILOS A CORREGIR</td>
      </tr>
	   <?php  for ($x=0;$x<=$totalRows_kilo_editar-1 ;$x++) { ?> 
       <tr>         
       <td id="fuente1"></td>   
       <td id="fuente1"><?php $id_rkp=mysql_result($kilo_editar,$x,id_rkp);?><input name="id_i[]" type="hidden" value="<?php echo $id_rkp; ?>" />
        <select name="id_m[]" id="id_m[]" style="width:150px">
                 <option value="">Ref</option>
                 <?php
        do {  
        ?>
                 <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'],$valor=mysql_result($kilo_editar,$x,id_rpp_rp)))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['codigo_insumo']." (DESCRIP) ".$row_materia_prima['descripcion_insumo']?></option>
                 <?php
        } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
          $rows = mysql_num_rows($materia_prima);
          if($rows > 0) {
              mysql_data_seek($materia_prima, 0);
              $row_materia_prima = mysql_fetch_assoc($materia_prima);
          }
        ?>
       </select>         
      <td id="fuente1"><input name="valor[]" readonly type="text" size="6" value="<?php $valor=mysql_result($kilo_editar,$x,valor_prod_rp); echo $valor; ?>" style="width:80px"/></td>
       <td id="fuente1"><input name="cant[]" type="number" style="width:80px" min="0"step="0.01" value="<?php $cant=mysql_result($kilo_editar,$x,valor_prod_rp); echo $cant; ?>"/></td>       
       </tr>
	   <?php  } ?>        
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