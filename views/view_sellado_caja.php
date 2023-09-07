<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
require_once('../AjaxControllers/Actions/funcioness.php');
 
  session_start();
 
$conexion = new ApptivaDB();

$Cadenas = new Cadenas();

$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario);

//IMPRIME COLAS DE TIQUETES
 
//$row_colas_tikets = $conexion->llenarCampos('tbl_tiquete_numeracion', "WHERE id_tn='".$_GET['id_tn']."' ","","*" );

//$row_tiquete_num =$conexion->llenarCampos("tbl_tiquete_numeracion", "WHERE id_tn='".$_GET['id_tn']."' ", "", "int_undxcaja_tn,int_undxpaq_tn");


$row_tiquete_num = $conexion->llenarCampos("tbl_tiquete_numeracion", "WHERE int_op_tn='".$_GET['id_op']."' AND int_caja_tn='".$_GET['int_caja_tn']."'", "ORDER BY int_paquete_tn DESC LIMIT 1", "int_undxcaja_tn,int_undxpaq_tn");


//$row_colas_tikets = $conexion->llenarCampos('tbl_tiquete_numeracion', "WHERE int_op_tn='".$_GET['id_op']."' "." AND int_caja_tn='".$cajamenos1."' ","ORDER BY int_caja_tn DESC LIMIT 1",'*' );
$cajamenos1 = $_GET['int_caja_tn']-1;


//$row_colas_tikets = $conexion->llenarCampos('tbl_tiquete_numeracion', "WHERE int_op_tn='".$_GET['id_op']."' "." AND int_caja_tn='".$_GET['int_caja_tn']."' ", "ORDER BY int_paquete_tn DESC LIMIT 1","int_hasta_tn,int_caja_tn,fecha_ingreso_tn,int_op_tn,int_undxpaq_tn,int_cod_empleado_tn,int_undxcaja_tn,int_cod_rev_tn" );

$row_numeracion = $conexion->llenarCampos('tbl_tiquete_numeracion', "WHERE int_op_tn='".$_GET['id_op']."' "." AND int_caja_tn='".$cajamenos1."' ", "ORDER BY int_paquete_tn DESC LIMIT 1","int_hasta_tn " );





$row_master = $conexion->llenarCampos('tbl_numeracion', "WHERE int_op_n='".$_GET['id_op']."' ", "","int_paquete_n " );

$row_colas = $conexion->llenaListas("tbl_tiquete_numeracion","WHERE int_op_tn='".$_GET['id_op']."' AND int_caja_tn='".$_GET['int_caja_tn']."' ","ORDER BY int_paquete_tn ASC"," * ");
//$row_colas = $conexion->llenarCampos('tbl_tiquete_numeracion', "WHERE int_op_tn='".$_GET['id_op']."' "." AND int_caja_tn='".$_GET['int_caja_tn']."' ", "ORDER BY int_paquete_tn ASC ","int_hasta_tn,int_caja_tn,fecha_ingreso_tn,int_op_tn,int_undxpaq_tn,int_cod_empleado_tn,int_undxcaja_tn,int_cod_rev_tn" );

$row_vista_paquete = $conexion->llenarCampos('tbl_tiquete_numeracion', "WHERE int_op_tn='".$_GET['id_op']."' "." AND int_paquete_tn='".$_GET['int_paquete_tn']."' "." AND int_caja_tn='".$_GET['int_caja_tn']."' ", '','*' );

//$paquete = $row_master['int_paquete_n'];

$row_info_op = $conexion->llenarCampos('tbl_orden_produccion', "WHERE id_op='".$_GET['id_op']."' ", "","charfin" );

$undxcaja = $row_tiquete_num['int_undxcaja_tn'];
$undxpaq = $row_tiquete_num['int_undxpaq_tn'];
$paquAImprimir = ($undxcaja/$undxpaq);
$hasta = $row_numeracion['int_hasta_tn'];//inicial de la etiqueta
//$hasta = 'AB2018021429'; 
 
?>
<html>
<head>
  <title>SISADGE AC & CIA</title>
  <!-- <link href="css/vista.css" rel="stylesheet" type="text/css" /> -->
  <link rel="stylesheet" type="text/css" href="../css/general.css"/>
  <script type="text/javascript" src="../js/vista.js"></script>
  <script type="text/javascript" src="../js/formato.js"></script>

  <!--Librerias de codigo barras QR  -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>  
  <script src="../JsBarcode-master/dist/JsBarcode.all.min.js"></script>

  <!--IMPRIME AL CARGAR POPUP-->
<style type="text/css"> 
  .box {
    align-items: center;
    border: 2px solid #CCC;
    height: 175px;
    width: 335px;
  }

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
 
    <div class="container" id="seleccion" onClick="cerrar('seleccion');return false" >
   
       <?php foreach($row_colas as $row_colas_tikets) { $paq_gen=$row_colas_tikets['int_paquete_tn'];  ?>
  
      <table align="center" style="padding-top:0.5%;border:2px solid #CCC;" ><!-- border:2px hay q dejarlo en 1 porq son varios ticket-->
        <tr>
          <td nowrap="nowrap" ><b> PAQUETE</b></td>
          <td nowrap="nowrap" id="stikers_fuentN2"><?php echo  $row_colas_tikets['int_paquete_tn'];?></td>
          <td nowrap="nowrap" ><b> CAJA</b></td>
          <td nowrap="nowrap" id="stikers_fuentN2"><?php echo $caja_gen=$row_colas_tikets['int_caja_tn']; ?></td>
        </tr>    
        <tr>
          <td nowrap="nowrap" colspan="2" ><b> FECHA</b></td>
          <td nowrap="nowrap" colspan="2" ><?php echo $row_colas_tikets['fecha_ingreso_tn']; ?><b> - </b><?php echo date("H:i");?></td>
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
        <td nowrap="nowrap" colspan="2" ><b>DESDE </b> <?php  echo $desde = $row_colas_tikets['int_desde_tn'].$row_info_op['charfin'];?></td>
        <td nowrap="nowrap" colspan="2" ><canvas id="desde<?php echo $paq_gen; ?>"></canvas> </td>
      </tr>
      <tr>
        <td nowrap="nowrap" colspan="2"  ><b>HASTA </b><?php  echo $hasta = $row_colas_tikets['int_hasta_tn'].$row_info_op['charfin'];?></td>
        <td nowrap="nowrap" colspan="2" ><canvas id="hasta<?php echo $paq_gen; ?>"></canvas> </td>
      </tr> 
   
    </table> 
   <?php 

   $faltantes = $conexion->llenaListas('tbl_faltantes', " WHERE id_op_f='".$op_gen."' "." AND int_paquete_f='".$paq_gen."' "." AND int_caja_f='".$caja_gen."' ", 'ORDER BY int_inicial_f ASC','int_inicial_f,int_final_f ' );
   ?>
   <?php if($faltantes): ?>

        <div class="box" >
          <table > <!-- border="1"  -->
           <?php if($faltantes[0]['int_inicial_f']!=''){ ?>
                 <tr>
                   <td nowrap="nowrap" ><b>FALTANTES Paq: <?php echo $paq_gen; ?> Caja:<?php echo $caja_gen ; ?> OP:<?php echo $op_gen; ?> </b></td>
                 </tr>    
                 <?php foreach ($faltantes as $row_vista_faltantes) { ?>
                  <tr>
                   <td id="stikers_fuentN2"><?php echo 'Del: <b>'. $row_vista_faltantes['int_inicial_f'].$row_info_op['charfin']; ?> - <?php echo '</b>Al: <b>'. $row_vista_faltantes['int_final_f'].$row_info_op['charfin']."</b> "; ?></td>
                 </tr> 
               <?php }  ?>
             <?php }?> 
         </table> 
        </div>
  <?php endif; ?> 
    

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
   
  var desde = <?php echo json_encode($desde); ?>;
  $("#desde"+conteo).JsBarcode(desde,{format:"CODE128",displayValue:false,fontSize:10, width:1,height:7});

  var hasta = <?php echo json_encode($hasta); ?>;
  $("#hasta"+conteo).JsBarcode(hasta,{format:"CODE128",displayValue:false,fontSize:10, width:1,height:7});
</script>

<?php } ?>
</body>
</html>