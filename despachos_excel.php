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

mysql_select_db($database_conexion1, $conexion1);
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>SISADGE AC &amp; CIA</title>
  <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
</head>
<body>
  <?php

  
  //Filtra todos vacios
   if($id_c =='0' && $anual == '0' && $mes == '0' && $dia == '0' && $cod_ref != '0' && $vende =='0')
    {
      $registros = $conexion->llenaListas("tbl_orden_compra,tbl_remisiones,tbl_items_ordenc,tbl_remision_detalle","where Tbl_remisiones.str_numero_oc_r=Tbl_orden_compra.str_numero_oc and Tbl_remisiones.int_remision = Tbl_remision_detalle.int_remision_r_rd and Tbl_remisiones.str_numero_oc_r=Tbl_orden_compra.str_numero_oc and Tbl_orden_compra.id_pedido = Tbl_items_ordenc.id_pedido_io and Tbl_items_ordenc.int_cod_ref_io = '$cod_ref' and Tbl_remisiones.b_borrado_r='0'","GROUP BY Tbl_remisiones.str_numero_oc_r,tbl_remision_detalle.int_ref_io_rd ORDER BY Tbl_remisiones.int_remision DESC","*" );

    }
    //anual
    //anual
    if($id_c =='0' && $anual != '0' && $mes == '0' && $dia == '0' && $cod_ref == '0' && $vende =='0')
    {
      $registros = $conexion->llenaListas('tbl_orden_compra,tbl_items_ordenc,tbl_remisiones,tbl_remision_detalle',"WHERE Tbl_remisiones.str_numero_oc_r=Tbl_orden_compra.str_numero_oc and Tbl_orden_compra.id_pedido = Tbl_items_ordenc.id_pedido_io and Tbl_remisiones.int_remision = Tbl_remision_detalle.int_remision_r_rd and Tbl_remisiones.b_borrado_r='0' and YEAR(Tbl_remisiones.fecha_r) = '$anual'",'GROUP BY Tbl_remisiones.str_numero_oc_r,tbl_remision_detalle.int_ref_io_rd ORDER BY Tbl_remisiones.int_remision DESC',"*"); 
    }
    //anual y mes
    if($id_c =='0' && $anual != '0' && $mes != '0' && $dia == '0' && $cod_ref == '0' && $vende =='0')
    {

      $registros = $conexion->llenaListas('tbl_orden_compra,tbl_items_ordenc,tbl_remisiones,tbl_remision_detalle',"WHERE Tbl_remisiones.str_numero_oc_r=Tbl_orden_compra.str_numero_oc and Tbl_orden_compra.id_pedido = Tbl_items_ordenc.id_pedido_io and Tbl_remisiones.int_remision = Tbl_remision_detalle.int_remision_r_rd and Tbl_remisiones.b_borrado_r='0' and YEAR(Tbl_remisiones.fecha_r) = '$anual' AND MONTH(Tbl_remisiones.fecha_r) = '$mes'",'GROUP BY Tbl_remisiones.str_numero_oc_r,tbl_remision_detalle.int_ref_io_rd ORDER BY Tbl_remisiones.int_remision DESC',"*");  
    }
    //anual, mes, dia
    if($id_c =='0' && $anual != '0' && $mes != '0' && $dia != '0' && $cod_ref == '0' && $vende =='0')
    {
      $registros = $conexion->llenaListas('tbl_orden_compra,tbl_items_ordenc,tbl_remisiones,tbl_remision_detalle',"WHERE Tbl_remisiones.str_numero_oc_r=Tbl_orden_compra.str_numero_oc and Tbl_orden_compra.id_pedido = Tbl_items_ordenc.id_pedido_io and Tbl_remisiones.int_remision = Tbl_remision_detalle.int_remision_r_rd and Tbl_remisiones.b_borrado_r ='0' and Tbl_remisiones.fecha_r =  '$fecha'",'GROUP BY Tbl_remisiones.str_numero_oc_r,tbl_remision_detalle.int_ref_io_rd ORDER BY Tbl_remisiones.int_remision DESC',"*"); 
    }
    //Filtra ref  y cliente llenos
    if($id_c !='0' && $anual == '0' && $mes == '0' && $dia == '0' && $cod_ref != '0' && $vende =='0')
    {
      $registros = $conexion->llenaListas('tbl_orden_compra,tbl_remisiones,tbl_items_ordenc,tbl_remision_detalle',"WHERE Tbl_remisiones.str_numero_oc_r=Tbl_orden_compra.str_numero_oc and Tbl_remisiones.int_remision = Tbl_remision_detalle.int_remision_r_rd and Tbl_remisiones.str_numero_oc_r=Tbl_orden_compra.str_numero_oc and Tbl_orden_compra.id_pedido = Tbl_items_ordenc.id_pedido_io and Tbl_items_ordenc.int_cod_ref_io = '$cod_ref' and Tbl_orden_compra.id_c_oc='$id_c' and Tbl_remisiones.b_borrado_r='0'",'GROUP BY Tbl_remisiones.str_numero_oc_r,tbl_remision_detalle.int_ref_io_rd ORDER BY Tbl_remisiones.int_remision DESC',"*"); 

    }

    //Filtra fecha y ref, vende lleno
      if($vende!='0'){
        $elvendedor = $conexion->llenarCampos("vendedor","WHERE id_vendedor=$vende","","nombre_vendedor");
        $vendedor = $elvendedor['nombre_vendedor'];
      }
    if($id_c =='0' && $anual != '0' && $mes != '0' && $dia != '0' && $cod_ref != '0' && $vende !='0')
    {
      $registros = $conexion->llenaListas('tbl_orden_compra,tbl_remisiones,tbl_items_ordenc',"WHERE Tbl_remisiones.str_numero_oc_r=Tbl_orden_compra.str_numero_oc and Tbl_orden_compra.id_pedido = Tbl_items_ordenc.id_pedido_io and Tbl_remisiones.fecha_r = '$fecha' AND Tbl_items_ordenc.int_cod_ref_io = '$cod_ref' AND Tbl_orden_compra.str_responsable_oc='$vendedor' AND Tbl_remisiones.b_borrado_r='0'",'GROUP BY Tbl_remisiones.str_numero_oc_r,tbl_remision_detalle.int_ref_io_rd ORDER BY Tbl_remisiones.int_remision DESC',"*"); 

    }
    //Filtra fecha y vende lleno
    if($id_c =='0' && $anual != '0' && $mes != '0' && $dia != '0' && $cod_ref == '0' && $vende !='0')
    {
      /*$registros = $conexion->llenaListas('tbl_orden_compra,tbl_remisiones,tbl_remision_detalle ',"WHERE Tbl_remisiones.b_borrado_r='0' AND Tbl_remisiones.fecha_r = '$fecha' AND Tbl_orden_compra.str_responsable_oc='$vendedor' AND Tbl_remisiones.str_numero_oc_r=Tbl_orden_compra.str_numero_oc",'GROUP BY Tbl_remisiones.str_numero_oc_r,tbl_remision_detalle.int_ref_io_rd ORDER BY Tbl_remisiones.int_remision DESC',"*"); */

      $registros = $conexion->llenaListas('tbl_orden_compra,tbl_remisiones,tbl_remision_detalle',"WHERE Tbl_remisiones.str_numero_oc_r=Tbl_orden_compra.str_numero_oc and Tbl_remisiones.int_remision = Tbl_remision_detalle.int_remision_r_rd and Tbl_remisiones.b_borrado_r ='0' and Tbl_remisiones.fecha_r =  '$fecha' AND Tbl_orden_compra.str_responsable_oc='$vendedor'",'GROUP BY Tbl_remisiones.str_numero_oc_r,tbl_remision_detalle.int_ref_io_rd ORDER BY Tbl_remisiones.int_remision DESC',"*"); 
      
    }
    //Filtra  AÑO Y MES, REF, vende
    if($id_c =='0' && $anual != '0' && $mes != '0' && $dia == '0' && $cod_ref != '0' && $vende !='0')
    {
      $registros = $conexion->llenaListas('tbl_orden_compra,tbl_remisiones,tbl_items_ordenc,tbl_remision_detalle',"WHERE Tbl_remisiones.int_remision = Tbl_remision_detalle.int_remision_r_rd and Tbl_remisiones.str_numero_oc_r=Tbl_orden_compra.str_numero_oc and Tbl_orden_compra.id_pedido = Tbl_items_ordenc.id_pedido_io and YEAR(Tbl_remisiones.fecha_r) = '$anual' AND MONTH(Tbl_remisiones.fecha_r) = '$mes' AND Tbl_items_ordenc.int_cod_ref_io = '$cod_ref' and Tbl_orden_compra.str_responsable_oc='$vendedor' and Tbl_remisiones.b_borrado_r='0'",'GROUP BY Tbl_remisiones.str_numero_oc_r,tbl_remision_detalle.int_ref_io_rd ORDER BY Tbl_remisiones.int_remision DESC',"*"); 

    }
    //Filtra ref lleno, AÑO, vende
    if($id_c =='0' && $anual != '0' && $mes == '0' && $dia == '0' && $cod_ref != '0' && $vende !='0')
    {
      $registros = $conexion->llenaListas('tbl_orden_compra,tbl_remisiones,tbl_items_ordenc,tbl_remision_detalle',"WHERE Tbl_remisiones.int_remision = Tbl_remision_detalle.int_remision_r_rd and Tbl_remisiones.str_numero_oc_r=Tbl_orden_compra.str_numero_oc and Tbl_orden_compra.id_pedido = Tbl_items_ordenc.id_pedido_io AND YEAR(Tbl_remisiones.fecha_r) = '$anual' AND Tbl_items_ordenc.int_cod_ref_io = '$cod_ref' and Tbl_orden_compra.str_responsable_oc='$vendedor' and Tbl_remisiones.b_borrado_r='0'",'GROUP BY Tbl_remisiones.str_numero_oc_r,tbl_remision_detalle.int_ref_io_rd ORDER BY Tbl_remisiones.int_remision DESC',"*");  
    }

    //Filtra ref, vende
    if($id_c =='0' && $anual == '0' && $mes == '0' && $dia == '0' && $cod_ref != '0' && $vende !='0')
    {
      $registros = $conexion->llenaListas('tbl_orden_compra,tbl_items_ordenc,tbl_remisiones,tbl_remision_detalle',"WHERE Tbl_remisiones.int_remision = Tbl_remision_detalle.int_remision_r_rd and Tbl_remisiones.str_numero_oc_r=Tbl_orden_compra.str_numero_oc and Tbl_orden_compra.id_pedido = Tbl_items_ordenc.id_pedido_io and Tbl_orden_compra.str_responsable_oc='$vendedor' AND Tbl_items_ordenc.int_cod_ref_io = '$cod_ref' and Tbl_remisiones.b_borrado_r='0'",'GROUP BY Tbl_remisiones.str_numero_oc_r,tbl_remision_detalle.int_ref_io_rd ORDER BY Tbl_remisiones.int_remision DESC',"*");   
    }
    //Filtra ref, MES, vende
    if($id_c =='0' && $anual == '0' && $mes != '0' && $dia == '0' && $cod_ref != '0' && $vende !='0')
    {
      $registros = $conexion->llenaListas('tbl_orden_compra,tbl_remisiones,tbl_items_ordenc,tbl_remision_detalle',"WHERE Tbl_remisiones.int_remision = Tbl_remision_detalle.int_remision_r_rd and Tbl_remisiones.str_numero_oc_r=Tbl_orden_compra.str_numero_oc and Tbl_orden_compra.id_pedido = Tbl_items_ordenc.id_pedido_io AND MONTH(Tbl_remisiones.fecha_r) = '$mes' and Tbl_orden_compra.str_responsable_oc='$vendedor' AND Tbl_items_ordenc.int_cod_ref_io = '$cod_ref' and Tbl_remisiones.b_borrado_r='0'",'GROUP BY Tbl_remisiones.str_numero_oc_r,tbl_remision_detalle.int_ref_io_rd ORDER BY Tbl_remisiones.int_remision DESC',"*"); 
    }
    //Filtra vende lleno
    if($id_c =='0' && $anual == '0' && $mes == '0' && $dia == '0' && $cod_ref == '0' && $vende !='0')
    {
      $registros = $conexion->llenaListas('tbl_orden_compra,tbl_remisiones ',"WHERE Tbl_remisiones.b_borrado_r='0' AND Tbl_orden_compra.str_responsable_oc='$vendedor' AND Tbl_remisiones.str_numero_oc_r=Tbl_orden_compra.str_numero_oc",'GROUP BY Tbl_remisiones.str_numero_oc_r,tbl_remision_detalle.int_ref_io_rd ORDER BY Tbl_remisiones.int_remision DESC',"*"); 
    }

 
  ?>

  <table id="tabla1" border=1>
    <tr> 
      <td nowrap="nowrap" id="nivel2">REMISION</td>
      <td nowrap="nowrap" id="nivel2">FECHA INGRESO O.C.</td>
      <td nowrap="nowrap" id="nivel2">FECHA ENTREGA PACTADA</td>
      <td nowrap="nowrap" id="nivel2">FECHA ENTREGA REM.</td>
      <td nowrap="nowrap" id="nivel2">CLIENTE</td>
      <td nowrap="nowrap" id="nivel2">O.C</td>
      <td nowrap="nowrap" id="nivel2">REF. AC</td>  
      <td nowrap="nowrap" id="nivel2">CANT.DESPACHADA</td>
      <td nowrap="nowrap" id="nivel2">CANT.PENDIENTE</td>
      <td nowrap="nowrap" id="nivel2">TRANSPORTADOR</td>
      <td nowrap="nowrap" id="nivel2">GUIA</td>
      <td nowrap="nowrap" id="nivel2">FACTURA</td>                
      <td nowrap="nowrap" id="nivel2">PAIS / CIUDAD</td>
      <td nowrap="nowrap" id="nivel2">DIRECCION ENTREGA</td>
      <td nowrap="nowrap" id="nivel2">VENDEDOR</td>
    </tr>                
   <?php foreach($registros as $row_remision) {  ?>
    <tr>
      <td id="talla2"><?php echo $row_remision['int_remision']; ?></td>  
      <td id="talla2"><?php echo $row_remision['fecha_ingreso_oc']; ?></td>
      <td id="talla2"><?php echo $row_remision['fecha_entrega_io']; ?></td>
      <td id="talla2"><?php echo $row_remision['fecha_r'];//$row_remision['fecha_entrega_io']; ?></td>
      <td id="talla2"><?php $clientes=$row_remision['str_numero_oc_r'];
      $refer=$row_remision['int_ref_io_rd'];
      $id_pedido=$row_remision['id_pedido']; 
      
      if(!empty($clientes))
      {
       $sqln = $conexion->llenarCampos('tbl_orden_compra oc, cliente c', "WHERE oc.id_c_oc= c.id_c and oc.id_pedido = '$id_pedido' ", '','distinct c.nombre_c, c.direccion_c, c.ciudad_c, c.pais_c' );

        $nombre_c=$sqln['nombre_c'];  
        $ciudad_c=$sqln['ciudad_c']; 
        $direccion_c = $sqln['direccion_c'];  
        $pais_c = $sqln['pais_c']; 
        echo htmlentities($nombre_c); 
      }
      ?> 
      </td>
     <td id="talla2"><?php echo $row_remision['str_numero_oc_r']; ?></td>                  
     <td id="talla1">
      <?php $mp=$row_remision['str_numero_oc_r'];
      if($mp!='')
      { 
        $resultio = $conexion->llenarCampos('tbl_items_ordenc', "WHERE id_pedido_io = '$id_pedido' AND int_cod_ref_io ='$refer'", 'GROUP BY int_cod_ref_io ','id_items,int_cod_ref_io, int_cantidad_io, int_cantidad_rest_io, str_direccion_desp_io,(int_cantidad_io - int_cantidad_rest_io) as despachada' ); 
        $despachada = $resultio['despachada']; 
        $int_cantidad_io = $resultio['int_cantidad_io']; 
        $int_cantidad_rest_io = $resultio['int_cantidad_rest_io']; 
        $str_direccion_desp_io = $resultio['str_direccion_desp_io']; 
      } 
      echo $row_remision['int_ref_io_rd'];
      ?>  
  </td> 
 
  <td id="talla2">
    <?php  echo $despachada =='' ? '0.00' :$despachada;//O PUEDE SER ESTE $row_remision['int_cant_rd'] ?>  
  </td>
  <td  id="talla2"><?php echo $int_cantidad_rest_io =='' ? '0.00' : $int_cantidad_rest_io; ?></td>
  <td  id="talla2"><?php echo $row_remision['str_transportador_r']; ?></td>
  <td  id="talla2"><?php echo $row_remision['str_guia_r']; ?></td>
  <td  id="talla2"><?php echo $row_remision['factura_r']; ?></td>
  <td  id="talla2"><?php echo htmlentities($row_remision['ciudad_pais'])?></td>
  <td id="talla2"><?php echo htmlentities($str_direccion_desp_io); ?></td>
  <td id="talla2">
  <?php 
  $idoc = $row_remision['str_numero_oc_r'];
  $select_direccion = $conexion->llenaListas('vendedor ver',"LEFT JOIN tbl_items_ordenc itm on  ver.id_vendedor=itm.int_vendedor_io WHERE itm.str_numero_io= '$idoc'","","distinct ver.nombre_vendedor");
   foreach($select_direccion as $row_direccion) { 
     $vende = $row_direccion['nombre_vendedor']." ";
   } 
   echo $vende; 
   ?> 
 </td> 
</tr>
<?php }  ?>

</table>

</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($ano);

mysql_free_result($mezclas);

mysql_free_result($id_pm);
?>