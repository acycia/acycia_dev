

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
            <table style="width:100%" border="4">
                <tr>
                    <th><h3><b>FROM:</b><h3> 
                        <td><h3><b>ALBERTO CADAVID R & CIA</b><br> 
                            Cra. 45 # 14-15, Medellin, Antioquia <br>
                        Telefono: (574)3112144</h3></th>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><h2><b>SHIP TO:</b>
                        &nbsp;&nbsp;<?php 
                        echo 'direccion';

            //if ($row_refac_refcl['str_ref_cl_rc'] == '') {
    //echo "N.A";
//} else {
   // echo $row_refac_refcl['str_ref_cl_rc'];
//}
                        ?>
                </h2> </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="container"><h2><b>SKU #:</b> <?php 
                          $sk = 1;
                        if ($sk == '') {
                            echo $sku = "N.A";
                        } else {
                           $sku = 'Referencia del cli'; 
                       }?></h2><canvas id="sku"></canvas><br><br>
                       </td>
                   </tr>
                    <tr style="text-align: center;">    
                       <td class="container"><h1 ><b>REF:&nbsp;&nbsp;<?php echo $ref = '400-02';?></b></h1></td> 
                       <td><canvas id="ref"></canvas><br><br></td>
                   </tr>

                    <tr>
                        <td><h2><b>Cliente:</b></h2></td>
                        <td><h3 style="text-align: center;"><b><?php echo 'nombrssss';?></b></h3></td> 
                  </tr>
                   <tr>
                    <td><h4><b>DESCRIPCION:</b></h4> </td>
                    <td><?php echo 'descripcion';?></td> 
                </tr>
                <tr>    
                    <td ><h3><b> GROSS WEIGHT:</b><br>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo 3000; ?> kg </h3></td>
                    <td class="container"><h3><b>&nbsp;QUANTITY:</b>&nbsp;&nbsp;
                        <?php echo $peso =  '2500'; ?>&nbsp;Units</h3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <canvas id="peso"></canvas><br><br>
                    </td>
                </tr> 
                <tr>
                    <td colspan="2"><h3><b>NUMBERING: </b> </h3></td> 
                </tr> 
                <tr><td colspan="2"><h2 style="text-align: center;"><b><?php echo '1231243434 - 5434234233'; ?></b></h2></td> 
                </tr>
                <tr>
                    <td><h4><b>FAULTS: </b></h4> 

                        <?php  
                        $faltantes = array('23432432','b3333333','c2343434333');
                        if ($faltantes!=''){
                        ?>&nbsp;&nbsp;<div class="qrcode" id="qr"></div><?php  
                    }else{echo "Sin Faltantes";
              
                }
                ?><br><br>
            </td> 
            <td ><b><b>CARTON:</b>&nbsp; 
                <samp style="font-size:70px; text-align:center; ">&nbsp;<?php echo '30988';?></samp>
                <!--<div style="font-size:60px">2999</div>--></td>
            </tr>
            <tr>    
                <td><b>Cod Emp:&nbsp;&nbsp;&nbsp;</b><?php echo $row_vista_paquete['int_cod_empleado_tn'];?></td>
                <td><b>Cod Aux:&nbsp;&nbsp;&nbsp;</b><?php echo $row_vista_paquete['int_cod_rev_tn'];?></td>
            </tr>

        </table>  

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

    var codigo =  <?php echo json_encode($faltantes); ?>;
    var myArray = [ codigo];
    $(document).ready(function() {
        $('#qr').ClassyQR({
   create: true, // signals the library to create the image tag inside the container div.
   type: 'text', // text/url/sms/email/call/locatithe text to encode in the QR. on/wifi/contact, default is TEXT
   text: myArray // the text to encode in the QR.
});
    });

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
    {width:2,height:80});

var peso = <?php echo json_encode($peso); ?>;
$("#peso").JsBarcode(peso,
    {width:6,height:80});

var ref = <?php echo json_encode($ref); ?>;
$("#ref").JsBarcode(ref,
    {width:5,height:80});
/*{
width: 2,
height: 100,
quite: 10,
format: "CODE128",
displayValue: false,
fontOptions: "",
font:"monospace",
textAlign:"center",
fontSize: 12,
backgroundColor:"",
lineColor:"#000"
}*/

</script>
<!--Tipos de código de barras compatibles:

EAN8
EAN13
UPC
estándar 2 de 5 (industrial)
intercalado 2 de 5
code11
code39
code93
code128
codabar
MSI
Matriz de datos-->