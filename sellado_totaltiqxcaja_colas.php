<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
require_once('AjaxControllers/Actions/funcioness.php');
 
  session_start();
 
$conexion = new ApptivaDB();

$Cadenas = new Cadenas();

$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario);

//IMPRIME COLAS DE TIQUETES
 
//$row_colas_tikets = $conexion->llenarCampos('tbl_tiquete_numeracion', "WHERE id_tn='".$_GET['id_tn']."' ","","*" );

//$row_tiquete_num =$conexion->llenarCampos("tbl_tiquete_numeracion", "WHERE id_tn='".$_GET['id_tn']."' ", "", "int_undxcaja_tn,int_undxpaq_tn");

 
$row_tiquete_num = $conexion->llenarCampos("tbl_tiquete_numeracion", "WHERE int_op_tn='".$_GET['id_op']."' AND int_caja_tn='".$_GET['int_caja_tn']."'", "ORDER BY int_paquete_tn DESC LIMIT 1", "int_undxcaja_tn,int_undxpaq_tn");


//$row_colas_tikets = $conexion->llenarCampos('tbl_tiquete_numeracion', "WHERE int_op_tn='".$_GET['id_op']."' "." AND int_caja_tn='".$cajamenos1."' ","ORDER BY int_caja_tn DESC LIMIT 1",'*' );
$cajamenos1 = $_GET['int_caja_tn'];//-1;


$row_colas_tikets = $conexion->llenarCampos('tbl_tiquete_numeracion', "WHERE int_op_tn='".$_GET['id_op']."' "." AND int_caja_tn='".$_GET['int_caja_tn']."' ", "ORDER BY int_paquete_tn DESC LIMIT 1","int_hasta_tn,int_caja_tn,fecha_ingreso_tn,hora_tn,int_op_tn,int_undxpaq_tn,int_cod_empleado_tn,int_undxcaja_tn,int_cod_rev_tn" );

$row_numeracion =$conexion->llenarCampos('tbl_tiquete_numeracion', "WHERE int_op_tn='".$_GET['id_op']."' "." AND int_caja_tn='".$cajamenos1."' ", "ORDER BY int_paquete_tn DESC LIMIT 1","int_hasta_tn " );

$row_info_op = $conexion->llenarCampos('tbl_orden_produccion', "WHERE id_op='".$_GET['id_op']."' ", "","charfin" );

$undxcaja = $row_tiquete_num['int_undxcaja_tn'];
$undxpaq = $row_tiquete_num['int_undxpaq_tn'];
$paquAImprimir = ($undxcaja/$undxpaq);
$hasta = $row_numeracion['int_hasta_tn'];//inicial de la etiqueta

$hasta = $Cadenas->str_split_unicode($hasta);
$hastaLetr = $Cadenas->retornaLetras($hasta);
$hastaNum =  $Cadenas->retornaNumer($hasta);
$hasta = $hastaLetr.($hastaNum-$undxcaja);//para q arranque desde primer caja
 
?>
<html>
<head>
  <title>SISADGE AC & CIA</title>
  <!-- <link href="css/vista.css" rel="stylesheet" type="text/css" /> -->
  <link href="css/general.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="js/vista.js"></script>
  <script type="text/javascript" src="js/formato.js"></script>

  <!--Librerias de codigo barras QR  -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>  
  <script src="JsBarcode-master/dist/JsBarcode.all.min.js"></script>

  <!--IMPRIME AL CARGAR POPUP-->
<style type="text/css"> 
 #oculto {
  display:none; 
}
</style>
<script>
  function cerrar(num) { 
    window.close()
  }
</script>
</head>
<body onLoad="self.print();"><!--self.close(); onLoad="imprimir();"--> 
  <?php  
    //$hastaNum=$hastaNum-$undxpaq;
    for ($i=0; $i < $paquAImprimir; $i++) {  
   
     $hasta = $Cadenas->str_split_unicode($hasta);
     $hastaLetr = $Cadenas->retornaLetras($hasta);
     $hastaNum =  $Cadenas->retornaNumer($hasta);

     $desde = $hastaLetr.(($hastaNum )+1);
    ?>
    <div class="container" id="seleccion" onClick="cerrar('seleccion');return false" >
   
      <table align="center" id="tabla_borde"  style="padding-top: 2%;" > <!-- border="1"  -->
         
       <!--  <tr>
          <td colspan="4" nowrap="nowrap" align="center" class="fuentev2"><strong>CONTROL DE NUMERACION</strong> </td>
        </tr> -->
        <tr>
          <td nowrap="nowrap" ><b> PAQUETE</b></td>
          <td nowrap="nowrap" id="stikers_fuentN2"><?php echo $paq_gen=$i+1; ?></td>
          <td nowrap="nowrap" ><b> CAJA</b></td>
          <td nowrap="nowrap" id="stikers_fuentN2"><?php echo $caja_gen=$row_colas_tikets['int_caja_tn']; ?></td>
        </tr>    
        <tr>
          <td nowrap="nowrap" colspan="2" ><b> FECHA</b></td>
          <td nowrap="nowrap" colspan="2" ><?php echo $row_colas_tikets['fecha_ingreso_tn']; ?><b>/</b><?php echo ($row_colas_tikets['hora_tn']);?></td>
        </tr>
        <tr>
          <td nowrap="nowrap" colspan="2" ><b> ORDEN P.</b></td>
          <td nowrap="nowrap" colspan="2" ><?php echo $op_gen=$row_colas_tikets['int_op_tn']; ?></td>
        </tr>
        <tr>
          <td nowrap="nowrap" ><b>UNIDADES X PAQ.</b></td>
          <td nowrap="nowrap" ><?php echo $row_colas_tikets['int_undxpaq_tn']; ?></td>
          <td nowrap="nowrap" ><b>CODIGO EMP.</b></td>
          <td nowrap="nowrap" ><?php echo $row_colas_tikets['int_cod_empleado_tn']; ?></td>
        </tr>
        <tr>
         <td nowrap="nowrap" ><b>UNIDADES X CAJA</b></td>
         <td nowrap="nowrap" ><?php echo $row_colas_tikets['int_undxcaja_tn'];?></td>
         <td nowrap="nowrap" ><b>CODIGO REV.</b></td>
         <td nowrap="nowrap" ><?php echo $row_colas_tikets['int_cod_rev_tn']; ?></td>
       </tr>
       <tr> 
        <td nowrap="nowrap" colspan="2" ><b>DESDE </b> <?php  echo $desde;
             //proceso si tiene letras para poder sumar unidades x caja
             $desde = $Cadenas->str_split_unicode($desde);
             $desdeLetr = $Cadenas->retornaLetras($desde);
             $desdeNum =  $Cadenas->retornaNumer($desde);
             $desde = $desdeLetr.$desdeNum;
             $desdeConLetras = $desdeLetr.$desdeNum;
          ?><?php echo $row_info_op['charfin']; ?></td>
        <td nowrap="nowrap" colspan="2" ><canvas id="desde<?php echo $paq_gen; ?>"></canvas> </td>
      </tr>
      <tr>
        <td nowrap="nowrap" colspan="2"  ><b>HASTA </b><?php $desde = ($desdeNum + $undxpaq)-1; echo $hasta=$desdeLetr.$desde; ?><?php echo $row_info_op['charfin']; ?></td>
        <td nowrap="nowrap" colspan="2" ><canvas id="hasta<?php echo $paq_gen; ?>"></canvas> </td>
      </tr> 
      
    </table> 
  
   
  </div>
  <div id="oculto">
    <table width="100%" height="100%" border="0" align="center">
      <tr>
        <td><input name="cerrar" type="button" autofocus value="cerrar"onClick="cerrar('seleccion');return false" ></td>
      </tr>
    </table>

  </div>
<script type="text/javascript">

   var conteo = <?php echo $paq_gen; ?>;
   
  var desde = <?php echo json_encode($desdeConLetras); ?>;
  $("#desde"+conteo).JsBarcode(desde,{format:"CODE128",displayValue:false,fontSize:10, width:1,height:7});


  var hasta = <?php echo json_encode($hasta); ?>;
  $("#hasta"+conteo).JsBarcode(hasta,{format:"CODE128",displayValue:false,fontSize:10, width:1,height:7});
</script>
<?php } ?>

</body>
</html>