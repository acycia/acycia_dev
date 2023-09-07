<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
?>
<?php require_once('Connections/conexion1.php'); ?>
<?php
header('Pragma: public'); 
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past    
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); 
header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1 
header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1 
header('Pragma: no-cache'); 
header('Expires: 0'); 
header('Content-Transfer-Encoding: none'); 
header('Content-Type: application/vnd.ms-excel'); // This should work for IE & Opera 
header('Content-type: application/x-msexcel'); // This should work for the rest 
header('Content-Disposition: attachment; filename="Despachos.xls"');
?>

<?php
$conexion = new ApptivaDB();

$currentPage = $_SERVER["PHP_SELF"];
 
 
 $int_remision = $_GET['int_remision'];
 $str_numero= $_GET['str_numero'];
 $id_c = $_GET['id_c'];
 $cod_ref=$_GET['cod_ref'];
 $estado_oc = $_GET['estado_oc'];
 $estado_rd = $_GET['estado_rd'];
 $anual=$_GET['fecha'];
 $mes=$_GET['mensual']; 
 $dia = $_GET['dia'];
 $fecha = $anual.'-'.$mes.'-'. $dia;
 $vende = $_GET['vende'];

 ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
 </head>
<body>
<?php
 
//Filtra remision, FECHA
mysql_select_db($database_conexion1, $conexion1);
//Filtra todos vacios
if($id_c =='0' && $anual == '0' && $mes == '0' && $dia == '0' && $cod_ref == '0' && $vende =='0')
{ 
 
  $registros = $conexion->llenaListas('Tbl_orden_compra,Tbl_remisiones,Tbl_remision_detalle,Tbl_items_ordenc',"WHERE Tbl_remisiones.str_numero_oc_r=Tbl_orden_compra.str_numero_oc and Tbl_remisiones.b_borrado_r='0' AND Tbl_remisiones.int_remision=Tbl_remision_detalle.int_remision_r_rd AND  Tbl_remision_detalle.str_numero_oc_rd=Tbl_items_ordenc.str_numero_io AND  Tbl_remision_detalle.int_mp_io_rd=Tbl_items_ordenc.id_mp_vta_io and Tbl_remision_detalle.int_item_io_rd=Tbl_items_ordenc.id_items",'GROUP BY Tbl_remisiones.str_numero_oc_r,tbl_remision_detalle.int_ref_io_rd ORDER BY Tbl_remision_detalle.id_rd ASC',"*"); 
 
}
//Filtra fecha lleno
if($fecha != '0' && $mes != '0' && $dia != '0' && $cod_ref == '0' && $vende =='0')
{
  $registros = $conexion->llenaListas('Tbl_orden_compra,Tbl_remisiones,Tbl_remision_detalle,Tbl_items_ordenc',"WHERE Tbl_remisiones.str_numero_oc_r=Tbl_orden_compra.str_numero_oc and Tbl_remision_detalle.fecha_rd =  '$fecha' AND Tbl_remisiones.b_borrado_r='0' AND Tbl_remisiones.int_remision=Tbl_remision_detalle.int_remision_r_rd AND  Tbl_remision_detalle.str_numero_oc_rd=Tbl_items_ordenc.str_numero_io AND  Tbl_remision_detalle.int_ref_io_rd=Tbl_items_ordenc.int_cod_ref_io  and Tbl_remision_detalle.int_item_io_rd=Tbl_items_ordenc.id_items",'GROUP BY Tbl_remisiones.str_numero_oc_r,tbl_remision_detalle.int_ref_io_rd ORDER BY Tbl_remisiones.fecha_r DESC',"*");
 
}
//Filtra ref lleno
 if($id_c =='0' && $anual == '0' && $mes == '0' && $dia == '0' && $cod_ref != '0' && $vende =='0')
{
  $registros = $conexion->llenaListas('Tbl_orden_compra,Tbl_remisiones,Tbl_items_ordenc,Tbl_remision_detalle',"WHERE Tbl_remisiones.str_numero_oc_r=Tbl_orden_compra.str_numero_oc and Tbl_remisiones.int_remision = Tbl_remision_detalle.int_remision_r_rd and Tbl_remisiones.str_numero_oc_r = Tbl_orden_compra.str_numero_oc and Tbl_orden_compra.str_numero_oc = Tbl_items_ordenc.str_numero_io and Tbl_items_ordenc.int_cod_ref_io = '$cod_ref'",'GROUP BY Tbl_remisiones.str_numero_oc_r,tbl_remision_detalle.int_ref_io_rd ORDER BY Tbl_remisiones.fecha_r DESC',"*"); 

}
//Filtra año lleno
 if($id_c =='0' && $anual != '0' && $mes == '0' && $dia == '0' && $cod_ref == '0' && $vende =='0')
{ 
  $registros = $conexion->llenaListas('Tbl_orden_compra,Tbl_remisiones,Tbl_remision_detalle,Tbl_items_ordenc',"WHERE Tbl_remisiones.str_numero_oc_r=Tbl_orden_compra.str_numero_oc and  YEAR(Tbl_remision_detalle.fecha_rd) = '$anual' AND Tbl_remisiones.b_borrado_r='0' AND Tbl_remisiones.int_remision=Tbl_remision_detalle.int_remision_r_rd AND  Tbl_remision_detalle.str_numero_oc_rd=Tbl_items_ordenc.str_numero_io AND  Tbl_remision_detalle.int_ref_io_rd=Tbl_items_ordenc.int_cod_ref_io  and Tbl_remision_detalle.int_item_io_rd=Tbl_items_ordenc.id_items",'GROUP BY Tbl_remisiones.str_numero_oc_r,tbl_remision_detalle.int_ref_io_rd ORDER BY Tbl_remisiones.fecha_r DESC',"*"); 

}
  //Filtra ref  y cliente llenos
  if($id_c !='0' && $anual == '0' && $mes == '0' && $dia == '0' && $cod_ref != '0' && $vende =='0')
  {
    $registros = $conexion->llenaListas('Tbl_orden_compra,Tbl_remisiones,Tbl_items_ordenc,Tbl_remision_detallelle',"WHERE Tbl_remisiones.str_numero_oc_r=Tbl_orden_compra.str_numero_oc and Tbl_remisiones.int_remision = Tbl_remision_detalle.int_remision_r_rd and Tbl_remisiones.str_numero_oc_r=Tbl_orden_compra.str_numero_oc and Tbl_orden_compra.id_pedido = Tbl_items_ordenc.id_pedido_io and Tbl_items_ordenc.int_cod_ref_io = '$cod_ref' and Tbl_orden_compra.id_c_oc='$id_c' and Tbl_remisiones.b_borrado_r='0'",'GROUP BY Tbl_remisiones.str_numero_oc_r,tbl_remision_detalle.int_ref_io_rd ORDER BY Tbl_remisiones.int_remision DESC',"*");  ;
  }
//Filtra año y mes llenos
if($id_c =='0' && $anual != '0' && $mes != '0' && $dia == '0' && $cod_ref == '0' && $vende =='0')
{
  $registros = $conexion->llenaListas('tbl_orden_compra,tbl_remisiones,tbl_remision_detalle,tbl_items_ordenc',"WHERE Tbl_remisiones.str_numero_oc_r=Tbl_orden_compra.str_numero_oc AND Tbl_remisiones.b_borrado_r='0' AND Tbl_remisiones.int_remision=Tbl_remision_detalle.int_remision_r_rd AND  Tbl_remision_detalle.str_numero_oc_rd=Tbl_items_ordenc.str_numero_io AND  Tbl_remision_detalle.int_ref_io_rd=Tbl_items_ordenc.int_cod_ref_io  and Tbl_remision_detalle.int_item_io_rd=Tbl_items_ordenc.id_items and YEAR(Tbl_remision_detalle.fecha_rd) = '$anual' AND MONTH(Tbl_remisiones.fecha_r) = '$mes'",'GROUP BY Tbl_remisiones.str_numero_oc_r,tbl_remision_detalle.int_ref_io_rd ORDER BY Tbl_remisiones.fecha_r DESC',"*");  
 

}
//Filtra año y mes, REF Y VENDE llenos
if($id_c =='0' && $anual != '0' && $mes != '0' && $dia == '0' && $cod_ref != '0' && $vende !='0')
{
  $registros = $conexion->llenaListas('Tbl_orden_compra,Tbl_remisiones,Tbl_remision_detalle,Tbl_items_ordenc',"WHERE Tbl_remisiones.str_numero_oc_r=Tbl_orden_compra.str_numero_oc and YEAR(Tbl_remision_detalle.fecha_rd) = '$anual' AND MONTH(Tbl_remisiones.fecha_r) = '$mes' and Tbl_items_ordenc.int_cod_ref_io = '$cod_ref' AND Tbl_orden_compra.str_responsable_oc='$vende' AND Tbl_remisiones.b_borrado_r='0' AND Tbl_remisiones.int_remision=Tbl_remision_detalle.int_remision_r_rd AND  Tbl_remision_detalle.str_numero_oc_rd=Tbl_items_ordenc.str_numero_io AND  Tbl_remision_detalle.int_ref_io_rd=Tbl_items_ordenc.int_cod_ref_io  and Tbl_remision_detalle.int_item_io_rd=Tbl_items_ordenc.id_items",'GROUP BY Tbl_remisiones.str_numero_oc_r,tbl_remision_detalle.int_ref_io_rd ORDER BY Tbl_remisiones.fecha_r DESC',"*");  

}
//Filtra fecha y VENDE llenos
if($id_c =='0' && $anual != '0' && $mes != '0' && $dia != '0' && $cod_ref == '0' && $vende !='0')
{
  $registros = $conexion->llenaListas('Tbl_orden_compra,Tbl_remisiones,Tbl_remision_detalle,Tbl_items_ordenc',"WHERE Tbl_remisiones.str_numero_oc_r=Tbl_orden_compra.str_numero_oc and Tbl_remision_detalle.fecha_rd =  '$fecha' AND Tbl_orden_compra.str_responsable_oc='$vende' AND Tbl_remisiones.b_borrado_r='0' AND Tbl_remisiones.int_remision=Tbl_remision_detalle.int_remision_r_rd AND  Tbl_remision_detalle.str_numero_oc_rd=Tbl_items_ordenc.str_numero_io AND  Tbl_remision_detalle.int_ref_io_rd=Tbl_items_ordenc.int_cod_ref_io  and Tbl_remision_detalle.int_item_io_rd=Tbl_items_ordenc.id_items",'GROUP BY Tbl_remisiones.str_numero_oc_r,tbl_remision_detalle.int_ref_io_rd ORDER BY Tbl_remisiones.fecha_r DESC',"*"); 

} 
//Filtra MES, REF Y VENDE llenos
if($id_c =='0' && $anual == '0' && $mes != '0' && $dia == '0' && $cod_ref != '0' && $vende !='0')
{
  $registros = $conexion->llenaListas('Tbl_orden_compra,Tbl_remisiones,Tbl_remision_detalle,Tbl_items_ordenc',"WHERE Tbl_remisiones.str_numero_oc_r=Tbl_orden_compra.str_numero_oc and MONTH(Tbl_remisiones.fecha_r) = '$mes' and Tbl_items_ordenc.int_cod_ref_io = '$cod_ref' AND Tbl_orden_compra.str_responsable_oc='$vende' AND Tbl_remisiones.b_borrado_r='0' AND Tbl_remisiones.int_remision=Tbl_remision_detalle.int_remision_r_rd AND  Tbl_remision_detalle.str_numero_oc_rd=Tbl_items_ordenc.str_numero_io AND  Tbl_remision_detalle.int_ref_io_rd=Tbl_items_ordenc.int_cod_ref_io  and Tbl_remision_detalle.int_item_io_rd=Tbl_items_ordenc.id_items",'GROUP BY Tbl_remisiones.str_numero_oc_r,tbl_remision_detalle.int_ref_io_rd ORDER BY Tbl_remisiones.fecha_r DESC',"*");  

} 
//Filtra REF Y VENDE llenos
if($id_c =='0' && $anual == '0' && $mes == '0' && $dia == '0' && $cod_ref != '0' && $vende !='0')
{
  $registros = $conexion->llenaListas('Tbl_orden_compra,Tbl_remisiones,Tbl_remision_detalle,Tbl_items_ordenc',"WHERE Tbl_remisiones.str_numero_oc_r=Tbl_orden_compra.str_numero_oc and Tbl_items_ordenc.int_cod_ref_io = '$cod_ref' AND Tbl_orden_compra.str_responsable_oc='$vende' AND Tbl_remisiones.b_borrado_r='0' AND Tbl_remisiones.int_remision=Tbl_remision_detalle.int_remision_r_rd AND  Tbl_remision_detalle.str_numero_oc_rd=Tbl_items_ordenc.str_numero_io AND  Tbl_remision_detalle.int_ref_io_rd=Tbl_items_ordenc.int_cod_ref_io  and Tbl_remision_detalle.int_item_io_rd=Tbl_items_ordenc.id_items",'GROUP BY Tbl_remisiones.str_numero_oc_r,tbl_remision_detalle.int_ref_io_rd ORDER BY Tbl_remisiones.fecha_r DESC',"*");  

} 
//Filtra vende lleno
if($id_c =='0' && $anual == '0' && $mes == '0' && $dia == '0' && $cod_ref == '0' && $vende !='0')
{
  $registros = $conexion->llenaListas('Tbl_orden_compra,Tbl_remisiones,Tbl_remision_detalle,Tbl_items_ordenc',"WHERE Tbl_remisiones.str_numero_oc_r=Tbl_orden_compra.str_numero_oc and Tbl_orden_compra.str_responsable_oc='$vende' AND Tbl_remisiones.b_borrado_r='0' AND Tbl_remisiones.int_remision=Tbl_remision_detalle.int_remision_r_rd AND  Tbl_remision_detalle.str_numero_oc_rd=Tbl_items_ordenc.str_numero_io AND  Tbl_remision_detalle.int_ref_io_rd=Tbl_items_ordenc.int_cod_ref_io  and Tbl_remision_detalle.int_item_io_rd=Tbl_items_ordenc.id_items",'GROUP BY Tbl_remisiones.str_numero_oc_r,tbl_remision_detalle.int_ref_io_rd ORDER BY Tbl_remisiones.fecha_r DESC',"*");
} 
 
?>

    <table id="tabla1" border=1>
        <tr> 
          <td id="nivel2">FECHA ENTREGA REM.</td>
          <td id="nivel2">CLIENTE</td>
          <td id="nivel2">O.C</td>
          <td id="nivel2">REF. AC</td> 
          <td id="nivel2">CANT.DESPACHADA</td>
          <td id="nivel2">CANT.PENDIENTE</td>
          <td id="nivel2">TRANSPORTADOR</td>
          <td id="nivel2">GUIA</td>
          <td id="nivel2">FACTURA</td>                
          <td id="nivel2">REMISION</td>
          <td id="nivel2">PAIS / CIUDAD</td>
          <td id="nivel2">DIRECCION ENTREGA</td>
          </tr>                
        <?php foreach($registros as $row_remision) {  ?>
          <tr> 
            <td id="talla2"><?php echo $row_remision['fecha_rd'];?></td>
            <td id="talla2"><?php $clientes=$row_remision['str_numero_oc_rd']; 
            $ocremi=$row_remision['str_numero_oc_r'];
            $refer=$row_remision['int_ref_io_rd'];
            $id_pedido=$row_remision['id_pedido']; 

          if(!empty($clientes))
            {
             $resultmp = $conexion->llenarCampos('tbl_orden_compra oc, cliente c', "WHERE oc.id_c_oc= c.id_c and oc.id_pedido = '$id_pedido' ", '','distinct c.nombre_c, c.direccion_c, c.ciudad_c, c.pais_c' );

            $nombre_c=$resultmp['nombre_c'];  
              $ciudad_c=$resultmp['ciudad_c']; 
              $direccion_c = $resultmp['direccion_c'];  
              $pais_c = $resultmp['pais_c']; 
              echo htmlentities($nombre_c); 
            }
           ?></td>
            <td id="talla2"><?php echo $row_remision['str_numero_oc_rd']; ?></td>                  
            <td id="talla1"><?php echo $row_remision['int_ref_io_rd']; ?> </td> 
            <td id="talla2"><?php 
            $mp=$row_remision['str_numero_oc_r'];
            if($mp!='')
            { 
              $resultio = $conexion->llenarCampos('tbl_items_ordenc', "WHERE id_pedido_io = '$id_pedido' AND int_cod_ref_io='$refer'", 'GROUP BY int_cod_ref_io ORDER BY id_items DESC','id_items,int_cod_ref_io, int_cantidad_io, int_cantidad_rest_io, str_direccion_desp_io,(int_cantidad_io - int_cantidad_rest_io) as despachada ' ); 
              $despachada = $resultio['despachada']; 
              $int_cantidad_io = $resultio['int_cantidad_io']; 
              $int_cantidad_rest_io = $resultio['int_cantidad_rest_io']; 
              $str_direccion_desp_io = $resultio['str_direccion_desp_io']; 
            }  
             ?><?php  echo $despachada =='' ? '0.00' :$despachada;//O PUEDE SER ESTE $row_remision['int_cant_rd'] ?>  
             </td>
            <td  id="talla2"><?php echo $int_cantidad_rest_io; // $row_remision['int_cant_rd']; ?></td>  
            <td  id="talla2"><?php echo $row_remision['str_transportador_r']; ?></td>
            <td  id="talla2"><?php echo $row_remision['str_guia_r']; ?></td>
            <td  id="talla2"><?php echo $row_remision['factura_r']; ?></td>
            <td  id="talla2"><?php echo $row_remision['int_remision_r_rd']; ?></td> 
            <td  id="talla2"><?php echo htmlentities($row_remision['ciudad_pais'])?></td>
            <td id="talla2"><?php  echo htmlentities($str_direccion_desp_io); ?> </td>
          </tr>
          <?php }  ?>

    </table>

</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($remision);
?>