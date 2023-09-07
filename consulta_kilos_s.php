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
$query_insumo = "SELECT * FROM insumo WHERE clase_insumo='5' ORDER BY descripcion_insumo ASC";
$insumo = mysql_query($query_insumo, $conexion1) or die(mysql_error());
$row_insumo = mysql_fetch_assoc($insumo);
$totalRows_insumot = mysql_num_rows($insumo);
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
mysql_select_db($database_conexion1, $conexion1);
// DATOS
$str_numero_r=$_GET["id_op_rp"];

//ORDEN COMPRA
if ($str_numero_r!='')  
{		
?> <div id="acceso2"><table id="tabla1">
  <tr>
    <td id="dato">&nbsp;</td>
    <td id="dato">&nbsp;</td>
    <td id="dato">MATERIA PRIMA</td>
    <td id="dato">&nbsp;</td>
    <td>MTS / KILOS</td>
  </tr>
  <tr><td colspan="4" id="dato2">
                 <select name="id_rpp[]" id="id_rpp[]" style="width:200px">
                   <option value="">Materias Primas</option>
                     <?php
                    do {  
                    ?>
                      <option value="<?php echo $row_insumo['id_insumo']?>"><?php echo $row_insumo['descripcion_insumo']?></option>
                     <?php
                    } while ($row_insumo = mysql_fetch_assoc($insumo));
                      $rows = mysql_num_rows($insumo);
                      if($rows > 0) {
                          mysql_data_seek($insumo, 0);
                          $row_insumo = mysql_fetch_assoc($insumo);
                      }
                    ?>                
                    </select></td><td><input type="number" name="valor_prod_rp[]" id="valor_prod_rp[]" min="0"step="0.01"size="12"   autofocus/></td></tr>
                 <tr><td colspan="4" id="dato2">
                 <select name="id_rpp[]" id="id_rpp[]" style="width:200px">
                   <option value="">Materias Primas</option>
                     <?php
                    do {  
                    ?>
                      <option value="<?php echo $row_insumo['id_insumo']?>"><?php echo $row_insumo['descripcion_insumo']?></option>
                     <?php
                    } while ($row_insumo = mysql_fetch_assoc($insumo));
                      $rows = mysql_num_rows($insumo);
                      if($rows > 0) {
                          mysql_data_seek($insumo, 0);
                          $row_insumo = mysql_fetch_assoc($insumo);
                      }
                    ?>                
                    </select></td><td><input type="number" name="valor_prod_rp[]" id="valor_prod_rp[]" min="0"step="0.01"size="12"   autofocus/></td></tr>
                 <tr><td colspan="4" id="dato2">
                 <select name="id_rpp[]" id="id_rpp[]" style="width:200px">
                   <option value="">Materias Primas</option>
                     <?php
                    do {  
                    ?>
                      <option value="<?php echo $row_insumo['id_insumo']?>"><?php echo $row_insumo['descripcion_insumo']?></option>
                     <?php
                    } while ($row_insumo = mysql_fetch_assoc($insumo));
                      $rows = mysql_num_rows($insumo);
                      if($rows > 0) {
                          mysql_data_seek($insumo, 0);
                          $row_insumo = mysql_fetch_assoc($insumo);
                      }
                    ?>                
                    </select></td><td><input type="number" name="valor_prod_rp[]" id="valor_prod_rp[]" min="0"step="0.01"size="12"   autofocus/></td></tr>
                 <tr><td colspan="4" id="dato2">
                 <select name="id_rpp[]" id="id_rpp[]" style="width:200px">
                   <option value="">Materias Primas</option>
                     <?php
                    do {  
                    ?>
                      <option value="<?php echo $row_insumo['id_insumo']?>"><?php echo $row_insumo['descripcion_insumo']?></option>
                     <?php
                    } while ($row_insumo = mysql_fetch_assoc($insumo));
                      $rows = mysql_num_rows($insumo);
                      if($rows > 0) {
                          mysql_data_seek($insumo, 0);
                          $row_insumo = mysql_fetch_assoc($insumo);
                      }
                    ?>                
                    </select></td><td><input type="number" name="valor_prod_rp[]" id="valor_prod_rp[]" min="0"step="0.01"size="12"   autofocus/></td></tr>
                 <tr><td colspan="4" id="dato2">
                 <select name="id_rpp[]" id="id_rpp[]" style="width:200px">
                   <option value="">Materias Primas</option>
                     <?php
                    do {  
                    ?>
                      <option value="<?php echo $row_insumo['id_insumo']?>"><?php echo $row_insumo['descripcion_insumo']?></option>
                     <?php
                    } while ($row_insumo = mysql_fetch_assoc($insumo));
                      $rows = mysql_num_rows($insumo);
                      if($rows > 0) {
                          mysql_data_seek($insumo, 0);
                          $row_insumo = mysql_fetch_assoc($insumo);
                      }
                    ?>                
                    </select></td><td><input type="number" name="valor_prod_rp[]" id="valor_prod_rp[]" min="0"step="0.01"size="12"   autofocus/></td></tr>
                 <tr><td colspan="4" id="dato2">
                 <select name="id_rpp[]" id="id_rpp[]" style="width:200px">
                   <option value="">Materias Primas</option>
                     <?php
                    do {  
                    ?>
                      <option value="<?php echo $row_insumo['id_insumo']?>"><?php echo $row_insumo['descripcion_insumo']?></option>
                     <?php
                    } while ($row_insumo = mysql_fetch_assoc($insumo));
                      $rows = mysql_num_rows($insumo);
                      if($rows > 0) {
                          mysql_data_seek($insumo, 0);
                          $row_insumo = mysql_fetch_assoc($insumo);
                      }
                    ?>                
                    </select></td><td><input type="number" name="valor_prod_rp[]" id="valor_prod_rp[]" min="0"step="0.01"size="12"   autofocus/></td></tr>
                 <tr><td colspan="4" id="dato2">
                 <select name="id_rpp[]" id="id_rpp[]" style="width:200px">
                   <option value="">Materias Primas</option>
                     <?php
                    do {  
                    ?>
                      <option value="<?php echo $row_insumo['id_insumo']?>"><?php echo $row_insumo['descripcion_insumo']?></option>
                     <?php
                    } while ($row_insumo = mysql_fetch_assoc($insumo));
                      $rows = mysql_num_rows($insumo);
                      if($rows > 0) {
                          mysql_data_seek($insumo, 0);
                          $row_insumo = mysql_fetch_assoc($insumo);
                      }
                    ?>                
                    </select></td><td><input type="number" name="valor_prod_rp[]" id="valor_prod_rp[]" min="0"step="0.01"size="12"   autofocus/></td></tr>
                    <tr><td colspan="4" id="dato2">
                 <select name="id_rpp[]" id="id_rpp[]" style="width:200px">
                   <option value="">Materias Primas</option>
                     <?php
                    do {  
                    ?>
                      <option value="<?php echo $row_insumo['id_insumo']?>"><?php echo $row_insumo['descripcion_insumo']?></option>
                     <?php
                    } while ($row_insumo = mysql_fetch_assoc($insumo));
                      $rows = mysql_num_rows($insumo);
                      if($rows > 0) {
                          mysql_data_seek($insumo, 0);
                          $row_insumo = mysql_fetch_assoc($insumo);
                      }
                    ?>                
                    </select></td><td><input type="number" name="valor_prod_rp[]" id="valor_prod_rp[]" min="0"step="0.01"size="12"   autofocus/></td></tr>
                    <tr><td colspan="4" id="dato2">
                 <select name="id_rpp[]" id="id_rpp[]" style="width:200px">
                   <option value="">Materias Primas</option>
                     <?php
                    do {  
                    ?>
                      <option value="<?php echo $row_insumo['id_insumo']?>"><?php echo $row_insumo['descripcion_insumo']?></option>
                     <?php
                    } while ($row_insumo = mysql_fetch_assoc($insumo));
                      $rows = mysql_num_rows($insumo);
                      if($rows > 0) {
                          mysql_data_seek($insumo, 0);
                          $row_insumo = mysql_fetch_assoc($insumo);
                      }
                    ?>                
                    </select></td><td><input type="number" name="valor_prod_rp[]" id="valor_prod_rp[]" min="0"step="0.01"size="12"   autofocus/></td></tr> </table>                                                                                                                       
                    </div>

<?php }?>
</body>
</html>
<?php
mysql_free_result($usuario);
?>