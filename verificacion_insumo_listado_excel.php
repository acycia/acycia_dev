<?php
   require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
   require (ROOT_BBDD); 
?> 
<?php
header('Pragma: public'); 
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Aumentar el tamaño en php.ini   
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); 
header('Cache-Control: no-store, no-cache, must-revalidate'); // Aumentar el tamaño en php.ini 
header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // Aumentar el tamaño en php.ini 
header('Pragma: no-cache'); 
header('Expires: 0'); 
header('Content-Transfer-Encoding: none'); 
header('Content-Type: application/vnd.ms-excel'); // This should work for IE & Opera 
header('Content-type: application/x-msexcel'); // This should work for the rest 
header('Content-Disposition: attachment; filename="despachofaltantes.xls"');
?>

<?php
 
$conexion = new ApptivaDB();
 
$fecha_inicio = $_GET["fecha_inicio"];
$fecha_fin = $_GET["fecha_fin"];
  
if ( isset($_GET["fecha_fin"]) && isset($_GET["fecha_fin"]) ) {
 
  $registros=$conexion->llenaListas("verificacion_insumos","WHERE fecha_vi BETWEEN '$fecha_inicio' AND '$fecha_fin'","","*");
} 
 
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>SISADGE AC &amp; CIA</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body> 
      <table id="tabla1">   
          <!-- grid -->  
          <tr>   
                 <td><strong>O.C</strong></td>
                 <td><strong>INSUMO</strong></td>
                 <td><strong>RECIBIO</strong></td>
                 <td ><strong>FECHA RECIBIDO </strong></td>
                 <td><strong>FACTURA</strong></td>
                 <td><strong>ENTREGA</strong></td>
                 <td><strong>CANT. PEDIDA</strong></td>
                 <td><strong>SALDO ANTES</strong></td>
                 <td><strong>CANT. RECIBIDA</strong></td>
                 <td><strong>FALTAN</strong></td>           
              </tr> 
             <?php foreach($registros as $row_verificacion_insumo) {  ?>
            <tr>
              <td id="fondo_2">
                 <?php echo $row_verificacion_insumo['n_oc_vi']; ?>
              </td>
              <td id="fondo_2"> 
                  <?php   
                    $row_ver_nuevo = $conexion->buscar('insumo','id_insumo',$row_verificacion_insumo['id_insumo_vi']); 
                     echo $row_ver_nuevo['descripcion_insumo']=='' ? $row_verificacion_insumo['id_insumo_vi'] : $row_ver_nuevo['descripcion_insumo']; 
                  ?> 
              </td> 
              <td id="fondo_2">
                 <?php echo $row_verificacion_insumo['recibido_vi']; ?>
              </td> 
              <td id="fondo_2">
                 <?php echo $row_verificacion_insumo['fecha_vi']; ?>
              </td>
              <td id="fondo_2">
                 <?php echo $row_verificacion_insumo['factura_vi']; ?>
              </td>
              <td id="fondo_2">
                 <?php echo $row_verificacion_insumo['entrega_vi']; ?>
              </td>
              <td id="fondo_2">
                 <?php echo $row_verificacion_insumo['cantidad_solicitada_vi']; ?>
              </td>
              <td id="fondo_2">
                 <?php echo $row_verificacion_insumo['verificacion_det']; ?>
              </td>
              <td id="fondo_2">
                 <?php echo $row_verificacion_insumo['cantidad_recibida_vi']; ?>
              </td>
              <td id="fondo_2">
                 <?php echo $row_verificacion_insumo['faltantes_vi']; ?>
              </td>
               
            </tr>
            <?php  } ?>  
   </table>

</body>
</html>
 
<?php
mysql_free_result($usuario);

?>
