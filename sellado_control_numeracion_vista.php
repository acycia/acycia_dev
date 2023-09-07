<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
?>
<?php require_once('Connections/conexion1.php'); ?><?php

session_start();



$conexion = new ApptivaDB();

$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario); 

$row_vista_paquete = $conexion->llenarCampos('tbl_tiquete_numeracion', "WHERE int_op_tn='".$_GET['id_op']."' "." AND int_paquete_tn='".$_GET['int_paquete_tn']."' "." AND int_caja_tn='".$_GET['int_caja_tn']."' ", '','*' );

$registros = $conexion->llenaListas('tbl_faltantes', " WHERE id_op_f='".$_GET['id_op']."' "." AND int_paquete_f='".$_GET['int_paquete_tn']."' "." AND int_caja_f='".$_GET['int_caja_tn']."' ", 'ORDER BY int_inicial_f ASC','*' );

$row_info_op = $conexion->llenarCampos('tbl_orden_produccion', "WHERE id_op='".$_GET['id_op']."' ", "","charfin" );
 
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>SISADGE AC & CIA</title>
  <link rel="stylesheet" type="text/css" href="css/general.css"/>
  <script type="text/javascript" src="js/vista.js"></script>
  <script type="text/javascript" src="js/formato.js"></script>

  <!--Librerias de codigo barras QR  -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>  
  <script src="JsBarcode-master/dist/JsBarcode.all.min.js"></script>

</head>
<body onload="self.print();"><!--self.close(); onLoad="imprimir();"   id="seleccion" onClick="cerrar('seleccion');return false"-->
  <div align="center" id="seleccion" onClick="cerrar('seleccion');return false">
    <table align="center" style="border:1px solid #CCC;" > <!-- border:1px hay q dejarlo en 1 porq es un solo ticket-->
      <tr>
        <td nowrap="nowrap"><b>PAQUETE</b></td>
        <td nowrap="nowrap" id="stikers_fuentN2"><?php echo $paq_gen=$row_vista_paquete['int_paquete_tn']; ?></td>
        <td nowrap="nowrap"><b>CAJA</b></td>
        <td nowrap="nowrap" id="stikers_fuentN2"><?php echo $caja_gen=$_GET['int_caja_tn']; ?></td>
      </tr>    
      <tr>
        <td nowrap="nowrap" colspan="2"><b>FECHA</b></td>
        <td nowrap="nowrap" colspan="2"><?php echo $row_vista_paquete['fecha_ingreso_tn']; ?> HORA: <?php echo $row_vista_paquete['hora_tn'];?></td>
      </tr>
      <tr>
        <td nowrap="nowrap" colspan="2"><b>ORDEN P.</b></td>
        <td nowrap="nowrap" colspan="2"><?php echo $op_gen=$_GET['id_op']; ?></td>
      </tr> 
      <tr>
        <td nowrap="nowrap"><b>UNIDADES X PAQ.</b></td>
        <td nowrap="nowrap"><?php echo $row_vista_paquete['int_undxpaq_tn']; ?></td>
        <td nowrap="nowrap"><b>CODIGO EMP.</b><?php echo $row_vista_paquete['int_cod_empleado_tn']; ?></td>
      </tr>
      <tr>
       <td nowrap="nowrap"><b>UNIDADES X CAJA</b></td>
       <td nowrap="nowrap"><?php echo $row_vista_paquete['int_undxcaja_tn'];?></td>
       <td nowrap="nowrap"><b>CODIGO REV.</b><?php echo $row_vista_paquete['int_cod_rev_tn']; ?></td>
     </tr>
     <tr>
      <td nowrap="nowrap" colspan="2"><b>DESDE: <?php echo $row_vista_paquete['int_desde_tn']; ?><?php echo $row_info_op['charfin']; ?> </b> </td>
      <td nowrap="nowrap" colspan="2" ><canvas id="desde"></canvas> </td>
    </tr>
    <tr>
      <td nowrap="nowrap" colspan="2" ><b>HASTA: <?php echo $row_vista_paquete['int_hasta_tn']; ?><?php echo $row_info_op['charfin']; ?></b></td>
      <td nowrap="nowrap" colspan="2" ><canvas id="hasta"></canvas> </td>
    </tr> 
    <?php if($registros[0]['int_inicial_f']!=''){ ?>
    <br>
      <tr>
        <td colspan="4" nowrap="nowrap" id="stikers_subt2"><b>FALTANTES Paq: <?php echo $paq_gen; ?> Caja:<?php echo $caja_gen ; ?> OP:<?php echo $op_gen; ?> </b></td>
      </tr>    
      <?php foreach ($registros as $row_vista_faltantes) { ?>
       <tr>
        <td width="52" colspan="4" id="stikers_fuentN2"><?php echo 'Del: <b>'. $row_vista_faltantes['int_inicial_f']; ?><?php echo $row_info_op['charfin']; ?> - <?php echo '</b>Al: <b>'. $row_vista_faltantes['int_final_f'].$row_info_op['charfin']; "</b> "; ?></td>
      </tr> 
    <?php }  ?>
  <?php }?>    

</table>
</div><script type="text/javascript" async="async">
  function cerrar() {

    window.close();

  }
</script>

<script type="text/javascript">
  var desde = <?php echo json_encode($row_vista_paquete['int_desde_tn'].$row_info_op['charfin']); ?>;
  $("#desde").JsBarcode(desde,{format:"CODE128",displayValue:false,fontSize:10, width:1,height:7.5});


  var hasta = <?php echo json_encode($row_vista_paquete['int_hasta_tn'].$row_info_op['charfin']); ?>;
  $("#hasta").JsBarcode(hasta,{format:"CODE128",displayValue:false,fontSize:10, width:1,height:7.5});
</script>
</body>
</html>
 
