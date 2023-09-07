

<!--Ejemplo de codigo de barras-->
<!--<img src="barcode.php?text=0123456789&size=40&codetype=Code39&Orientation=vertical" />-->
<!--"barcode.php?text=0123456789&size=40&codetype=Code39&print=true"-->
<html>
<body>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>SISADGE AC & CIA</title>
		<link href="css/vista.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="js/vista.js"></script>
		<script type="text/javascript" src="js/formato.js"></script>
    <!-- <script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>    
    <script type="text/javascript" src="js/jquery-barcode-last.min.js"></script> -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css"> 
    <!--Librerias de codigo barras QR  -->
    <script src="jQuery_QR/js/jquery_qr.js"></script>
    <script type="text/javascript" src="jQuery_QR/js/jquery.classyqr.js"></script>
    <script type="text/javascript" src="jQuery_QR/js/jquery-barcode.js"></script>
    <!--MejoraAdo Barras-->
    <!-- <script src="JsBarcode-masters/src/barcodes/CODE128/CODE128.js"></script> -->
    <!-- Si requieres todos y con medida--> 
     <script src="JsBarcode-master/dist/JsBarcode.all.min.js"></script>
    <!--IMPRIME CODIGO DE BARRAS-->
    <script type="text/javascript">
       $(document).ready(function () {
        var codigo = "<?php $var = $row_vista_paquete['int_op_tn'] . "-" . $row_vista_paquete['int_caja_tn'];
        echo $var; ?>";
        var codigo2 = "770-771-1-<?php echo $row_refac['int_cod_ref_op']; ?>-1";
        $("#bcTarget").barcode(codigo2, "code128", {barWidth: 1, barHeight: 20});
                //$("#bcTarget").barcode("1234567", "int25"); 
            });
        </script>
        <!--IMPRIME AL CARGAR POPUP-->
        <script type="text/javascript" >
          function cerrar(num) {
            window.close()
        }
 /*           function imprimir()
            {
                if ((navigator.appName == "Netscape")) {
                    window.print();
                }
                else
                {
                    var WebBrowser = '<OBJECT ID="WebBrowser1" WIDTH=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></OBJECT>';
                    document.body.insertAdjacentHTML('beforeEnd', WebBrowser);
                    WebBrowser1.ExecWB(6, -1);
                    WebBrowser1.outerHTML = "";
                }
            }*/

            
    /*function cerrar() {
    setTimeout(function() {
    window.close();
    }, 100);
    }
    window.onload = cerrar();*/
</script>
<style type="text/css">

#oculto {
    display:none;

}
.container {
  /*border: solid 1px blue;*/
  width: 50%;
}

td {
  font-size: 1vw; 
  height: auto; 
}
</style> 
</head>
<body onLoad="self.print();"><!--self.close();-->
    <div align="center" id="seleccion"  onClick="cerrar('seleccion');"><!--onClick="javascript:imprSelec('seleccion')"-->
        <div class="container-fluid" > 
            <!--PRUEBA -->
            <table style="width:100%" border="4">
                <tr>
                    <td colspan="2"><h2><b  style="font-size:70px;">SHIP TO:</b>
                        <?php echo 'Direccion Clienteeeeeeeee';?></h2> 
                    </td>
                </tr>


                <tr>
                    <td colspan="2"><h1><b style="font-size:70px;">OC #</b>
                        <?php 
                          $sk = 1;
                        if ($sk == '') {
                            echo $sku = "N.A";
                        } else {
                           $sku = 'Referencia del cli'; 
                       }?>
                       <canvas id="sku"></canvas><br><br> <br> </h1>
                    </td> 
                </tr>


                <tr>    
                    <td colspan="2"><h3><b  style="font-size:70px;">REF:</b>
                        <?php  $ref =  '565-02'; ?>
                     <canvas id="ref"></canvas><br><br></h3>
                    </td>  
                </tr>

                    
                <tr>
                    <td colspan="2"><h3><b  style="font-size:70px;">CANT </b>
                        <?php $peso =  '2500'; ?> 
                        <canvas id="peso"></canvas><br><br></h3>
                    </td>
                </tr> 
                <tr>
                    <td><h3>
                      <b  style="font-size:70px;">PESO NETO:</b> <br>
                      <b  style="font-size:70px;">PESO BRUTO:</b> <br>
                      <b  style="font-size:70px;">NUMBERING:</b> <br>
                    </h3></td>

                    <td><h2>
                        <b  style="font-size:70px;">300 KG</b><br>
                        <b  style="font-size:70px;">50 KG</b><br>
                        <b  style="font-size:70px;"><?php echo '1231243434 - 5434234233'; ?></b>
                </h2></td> 
                </tr>
               <!-- <tr>
                    <td colspan="2"><h4><b>FAULTS: </b></h4> 
                        <?php  
                        $faltantes = array('23432432','b3333333','c2343434333');
                        if ($faltantes!=''){
                        ?>&nbsp;&nbsp;
                        <?php  
                    }else{echo "Sin Faltantes"; }?>
                    <div class="qrcode" id="qr"></div><br>
                    <samp style="font-size:70px; text-align:center; ">&nbsp;<?php //echo '30988';?></samp>
                    <div style="font-size:60px">2999</div>
                    </td> 
                </tr>-->
        
            <tr>
                 <td colspan="2"><h1 style="text-align:center; "><b>ALBERTO CADAVID R & CIA</b><br> 
                            Cra. 45 # 14-15, Medellin, Antioquia <br>
                        Telefono: (574)3112144</h1>
                </td>
            </tr>
            <tr>    
                <td><b>Cod Emp:&nbsp;&nbsp;&nbsp;</b><?php echo $row_vista_paquete['int_cod_empleado_tn'];?></td>
                <td><b>Cod Aux:&nbsp;&nbsp;&nbsp;</b><?php echo $row_vista_paquete['int_cod_rev_tn'];?></td>
            </tr>

        </table>  
<!--FIN PRUEBA-->



<!-- ACYCIA ORIGINAL 1-->
<!--             <table style="width:100%" border="4">
                <tr>
                    <th><h3><b>FROM:</b><h3> 
                        <td><h4><b>ALBERTO CADAVID R & CIA</b><br> 
                            Cra. 45 # 14-15, Medellin, Antioquia <br>
                        Telefono: (574)3112144</h4></th>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><h4><b>SHIP TO:</b>
                        &nbsp;&nbsp;<?php 
               $row_direccion['str_direccion_desp_io'];
            if ($row_direccion['str_direccion_desp_io'] == '') {
              echo $dir = "N.A";
              } else {
              echo $dir = $row_direccion['str_direccion_desp_io'];
              }
                        ?></h4></td>
                    </tr>
                    <tr>
                        <td class="container"><h2><b>SKU #:</b> <?php if ($row_refac_refcl['str_ref_cl_rc'] == '') {
                             $sku = "N.A";
                        } else {
                           $sku = $row_refac_refcl['str_ref_cl_rc']; 
                       } ?></h2> </td> 
                       <td class="container"><canvas id="sku"></canvas><br><br> </td>
                       </tr>
                    <tr>    
                       <td class="container"><h2><b>REF:&nbsp;&nbsp;<?php echo $ref = $row_refac['int_cod_ref_op'] . "-" . $row_refac['version_ref_op']; ?>
                        </b></h2></td> 
                       <td><br><br><canvas id="ref"></canvas><br><br></td>
                   </tr> 
                    <tr>
                        <td><h2><b>Cliente:</b></h2></td>
                        <td><h2 style="text-align: center;"><b><?php echo $row_cliente['nombre_c'];?></b></h2></td> 
                  </tr>
                   <tr>
                    <td><h3><b>DESCRIPCION:</b></h3> </td>
                    <td><h4><?php echo $row_refac_refcl['str_descripcion_rc'];?></h4></td> 
                </tr>
                <tr>    
                    <td ><h3><b> GROSS WEIGHT:</b><br>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $row_refac['int_kilos_op']; ?> kg </h3></td>
                    <td ><h3 style="text-align: center;"><b>&nbsp;QUANTITY:</b>&nbsp;&nbsp;
                        <?php echo $cantidad =  $row_vista_paquete['int_undxcaja_tn']; ?>&nbsp;Units</h3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <canvas id="cantidad"></canvas><br><br>
                    </td>
                </tr> 
                <tr>
                    <td colspan="2"><h3><b>NUMBERING: </b> </h3></td> 
                </tr> 
                <tr><td colspan="2"><h2 style="text-align: center;"><b><?php echo $row_vista_paquete['int_desde_tn']." - ".$row_vista_MAX['int_hasta_tn']; ?></b></h2></td> 
                </tr>
                <tr>
                    <td><h4><b>FAULTS: </b></h4> 

                        <?php  
                        if($row_vista_faltantes['int_inicial_f']!='')
                        {
                           do { 
                              $faltantes = $row_vista_faltantes['int_inicial_f']." - ".$row_vista_faltantes['int_final_f']. ", \n"; 
                          } while ($row_vista_faltantes = mysql_fetch_assoc($vista_faltantes)); 
                      }if ($faltantes!=''){
                        ?>&nbsp;&nbsp;<div class="qrcode" id="qr"><br><br></div><?php  
                    }else{echo $faltantes = "Sin Faltantes";
         //$faltantes = array('23432432','b3333333','c2343434333');
                }
                ?>
            </td> 
            <td ><b><b>CARTON:</b>&nbsp; 
                <samp style="font-size:70px; text-align:center; ">&nbsp;<?php echo $row_vista_paquete['int_caja_tn'];?></samp>
                 </td>
            </tr>
            <tr>    
                <td><b>Cod Emp:&nbsp;&nbsp;&nbsp;</b><?php echo $row_vista_paquete['int_cod_empleado_tn'];?></td>
                <td><b>Cod Aux:&nbsp;&nbsp;&nbsp;</b><?php echo $row_vista_paquete['int_cod_rev_tn'];?></td>
            </tr>

        </table>  --> 
<!--FIN ORIGINAL 1-->




<!-- ACYCIA ORIGINAL 2-->
<!--            
         <table style="width:100%" border="4">
                    <tr>
                         
                    <td colspan="2"><h2><b  style="font-size:70px;">SHIP TO:</b> <?php  $row_direccion['str_direccion_desp_io']; if ($row_direccion['str_direccion_desp_io'] == '') { echo $dir = "N.A"; } else {  echo $dir = $row_direccion['str_direccion_desp_io'];
                         }
                        ?></h2> 
                    </td>
                </tr>


                <tr>
                    <td colspan="2"><h1><b style="font-size:70px;">OC #</b><?php if ($row_refac_refcl['str_ref_cl_rc'] == '') {
                             $sku = "N.A";
                        } else {
                           $sku = $row_refac_refcl['str_ref_cl_rc']; 
                       } ?>
                       <canvas id="sku"></canvas><br><br> <br> </h1>
                    </td> 
                </tr>    


                 <tr>    
                    <td colspan="2"><h3><b  style="font-size:70px;">REF:</b><?php  $ref = $row_refac['int_cod_ref_op'] . "-" . $row_refac['version_ref_op']; ?>
                     <canvas id="ref"></canvas><br><br></h3>
                    </td>  
                </tr> 

                <tr>
                    <td colspan="2"><h3><b  style="font-size:70px;">CANT </b><?php echo $cantidad =  $row_vista_paquete['int_undxcaja_tn']; ?>
                        <?php //echo $row_cliente['nombre_c'];?>
                        <?php //echo $row_refac_refcl['str_descripcion_rc'];?>
                        <canvas id="cantidad"></canvas><br><br></h3>
                    </td>
                </tr>  

                <tr>
                    <td><h3>
                      <b  style="font-size:50px;">PESO NETO:</b> <br>
                      <b  style="font-size:50px;">PESO BRUTO:</b> <br>
                      <b  style="font-size:50px;">NUMBERING:</b> <br>
                    </h3></td>

                    <td><h2>
                        <b  style="font-size:50px;"><?php echo $row_refac['int_kilos_op']; ?> KG</b><br>
                        <b  style="font-size:50px;">50 KG</b><br>
                        <b  style="font-size:50px;"><?php echo $row_vista_paquete['int_desde_tn']." - ".$row_vista_MAX['int_hasta_tn']; ?></b>
                </h2></td> 
                </tr>

            <tr>
                 <td colspan="2"><h1 style="text-align:center; "><b>ALBERTO CADAVID R & CIA</b><br> 
                            Cra. 45 # 14-15, Medellin, Antioquia <br>
                        Telefono: (574)3112144</h1>
                </td>
            </tr>
        </table>   -->
<!--FIN ORIGINAL2-->



    </div>

    <div id="oculto">
        <table width="200" border="0" align="center">
            <tr>
                <td><input name="cerrar" type="button" autofocus value="cerrar"onClick="cerrar('seleccion');
                    return false" ></td>
                </tr>
            </table>











        </div>
    </body>
    </html>
    </html>
    <script>
    //codigo QR

    

//Antiguo codigo barras sin medidas
/*var peso = <?php //echo json_encode($peso); ?>;
$("#peso").barcode(peso,"code128" // type (string)
    );
var sku = <?php// echo json_encode($sku); ?>;
$("#sku").barcode(
sku,"code128" // type (string)
);*/

//codigo barras
var sku = <?php echo json_encode($sku); ?>;
$("#sku").JsBarcode(sku,
    {width:3,height:200});


var refe = <?php echo json_encode($ref); ?>;
$("#ref").JsBarcode(refe,
    {width:3,height:200});

var peso = <?php echo json_encode($peso); ?>;
$("#peso").JsBarcode(peso,
    {width:6,height:200});

/*var codigo =  <?php echo json_encode($faltantes); ?>;
    var myArray = [ codigo];
    $(document).ready(function() {
        $('#qr').ClassyQR({
   create: true, 
   type: 'text',  
   text: myArray  
     });
});*/
</script>
