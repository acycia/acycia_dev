<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
?> 
<?php require_once('Connections/conexion1.php'); ?>
<?php 

session_start();


// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) {
    // For security, start by assuming the visitor is NOT authorized. 
    $isValid = False;

    // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
    // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
    if (!empty($UserName)) {
        // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
        // Parse the strings into arrays. 
        $arrUsers = Explode(",", $strUsers);
        $arrGroups = Explode(",", $strGroups);
        if (in_array($UserName, $arrUsers)) {
            $isValid = true;
        }
        // Or, you may restrict access to only certain users based on their username. 
        if (in_array($UserGroup, $arrGroups)) {
            $isValid = true;
        }
        if (($strUsers == "") && true) {
            $isValid = true;
        }
    }
    return $isValid;
}

$MM_restrictGoTo = "usuario.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("", $MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {
    $MM_qsChar = "?";
    $MM_referrer = $_SERVER['PHP_SELF'];
    if (strpos($MM_restrictGoTo, "?"))
        $MM_qsChar = "&";
    if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0)
        $MM_referrer .= "?" . $QUERY_STRING;
    $MM_restrictGoTo = $MM_restrictGoTo . $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
    header("Location: " . $MM_restrictGoTo);
    exit;
}
?>
<?php
//LLAMADO A FUNCIONES
include('funciones/funciones_php.php'); //SISTEMA RUW PARA LA BASE DE DATOS 
//FIN
if (!function_exists("GetSQLValueString")) {

    function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") {
        if (PHP_VERSION < 6) {
            $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
        }

        $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

        switch ($theType) {
            case "text":
            $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
            break;
            case "long":
            case "int":
            $theValue = ($theValue != "") ? intval($theValue) : "NULL";
            break;
            case "double":
            $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
            break;
            case "date":
            $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
            break;
            case "defined":
            $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
            break;
        }
        return $theValue;
    }

}

$conexion = new ApptivaDB();
 
//cliente
$row_cliente = $conexion->llenarCampos('tbl_orden_produccion oc,cliente c','WHERE oc.int_cliente_op=c.id_c AND oc.id_op='.$_GET['id_op'],'','c.id_c,c.nombre_c,c.direccion_c,c.direccion_entrega_c' ); 

 
$row_refac = $conexion->llenarCampos('tbl_orden_produccion p','WHERE p.id_op='.$_GET['id_op'],'','p.id_op,p.int_cod_ref_op,p.version_ref_op,p.int_kilos_op,p.lote,p.str_numero_oc_op,p.int_cliente_op,p.charfin');



//$row_refac_refcl= $conexion->llenarCampos("tbl_refcliente r JOIN tbl_orden_produccion p ON r.int_ref_ac_rc=p.int_cod_ref_op  "," WHERE p.id_op= '".$_GET['id_op']."' AND p.int_cliente_op=r.id_c_rc ","","p.id_op,p.int_cod_ref_op,p.str_numero_oc_op,p.int_cliente_op,p.version_ref_op,r.int_ref_ac_rc,r.str_descripcion_rc,r.str_ref_cl_rc");


$row_vista_paquete = $conexion->llenarCampos('tbl_tiquete_numeracion', "WHERE int_op_tn='".$_GET['id_op']."' "." AND int_caja_tn='".$_GET['int_caja_tn']."' ", 'ORDER BY int_paquete_tn ASC LIMIT 1','int_op_tn, int_caja_tn, int_caja_tn, fecha_ingreso_tn, int_desde_tn, int_undxcaja_tn, int_undxpaq_tn int_cod_empleado_tn, int_cod_rev_tn');

$row_vista_MAX = $conexion->llenarCampos('tbl_tiquete_numeracion',"WHERE int_op_tn='".$_GET['id_op']."' "." AND int_caja_tn='".$_GET['int_caja_tn']."' ",'ORDER BY int_paquete_tn DESC LIMIT 1','int_hasta_tn'); 

$registros = $conexion->llenaListas('tbl_faltantes', " WHERE id_op_f='".$_GET['id_op']."' "." AND int_caja_f='".$_GET['int_caja_tn']."' ", 'ORDER BY int_inicial_f ASC','distinct int_inicial_f,int_final_f','DISTINCT int_inicial_f, int_final_f');


$row_cantidad = $conexion->llenarCampos('tbl_tiquete_numeracion',"WHERE int_op_tn='".$_GET['id_op']."' "." AND int_caja_tn='".$_GET['int_caja_tn']."' ",'ORDER BY int_paquete_tn ',' COUNT(int_op_tn) AS registros, SUM(int_undxpaq_tn) as cantidades, int_paquete_tn,int_undxcaja_tn,int_cod_empleado_tn,int_cod_rev_tn '); 


// $row_refac['int_cod_ref_op']='1364';
if($row_refac['int_cod_ref_op']=='1363' || $row_refac['int_cod_ref_op']=='1364'){
    $varControl = 1;
}else{
    $varControl = 0;
}

?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>SISADGE AC & CIA</title>
    <link href="css/vista.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="js/vista.js"></script>
    <script type="text/javascript" src="js/formato.js"></script>
    <link href="css/general.css" rel="stylesheet" type="text/css" />
    <!-- <script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>    
        <script type="text/javascript" src="js/jquery-barcode-last.min.js"></script> -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css"> 
        <!--Librerias de codigo barras QR  -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
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
      font-size: 2vw; 
      height: auto; 
  }
  .text { 
       /*"Impact", Garamond, 'Comic Sans';*/
      font-family: fantasy;
      line-height: 95%;/*espacio entre palabras arriba y abajo*/ 
    }
</style> 
</head>
<body onLoad="self.print();"><!--self.close();-->
    <div style="width:100%" align="center" id="seleccion"  onClick="cerrar('seleccion');"><!--onClick="javascript:imprSelec('seleccion')"-->
        <div  > 
            <table style="width:100%" border="4">
                <tr><!-- rowspan="2"  -->

                  <td colspan="3">
                     <samp style="font-size:15px;" class="text">&nbsp;CLIENT: </samp><br>
                     <b style="font-size:30px;" class="text">&nbsp;<?php echo $row_cliente['nombre_c'];?>  </b>
                  </td>  
                  <td colspan="3"   id="fondo" >
                      <img style="width:75%; height: 75%;" src="images/logoacyc2.jpg" /> 
                   </td>
                 </tr>  
                        <tr nowrap="nowrap">
                            <td colspan="6">&nbsp;<b style="font-size:20px;" class="text">SHIP TO:</b>
                              &nbsp;&nbsp;<samp style="font-size:20px;" class="text"> 
                                <?php  if($varControl==1): ?>
                                 <samp style="font-size:15px;" class="text">  
                                   Centro Industrial Ternera Bodega 23 Laboratorio de la medida, Cartagena, Colombia 
                                 </samp>
                                <?php else: ?>
                                <?php 
                                $dir = $row_cliente['direccion_c'];
                                $dir_e = $row_cliente['direccion_entrega_c'];
                                if ($dir== '') {
                                  echo $dir = $dir_e;
                                } else  if ($dir_e== '') {
                                  echo $dir = $dir;
                                }else{$dir="N/A";$dir_e="N/A";}
                                ?></samp>
                              <?php endif; ?>
                              </td>
                          </tr>
 
                    <!-- <tr>
                        <td class="container"><h2><b>SKU #:</b> <?php if ($row_refac_refcl['str_ref_cl_rc'] == '') {
                             $sku = "N.A";
                        } else {
                           $sku = $row_refac_refcl['str_ref_cl_rc']; 
                       } ?></h2> </td> 
                       <td class="container"><canvas id="sku"></canvas><br><br> </td>
                   </tr> -->


                    <tr nowrap="nowrap">    
                          <td colspan="3" nowrap="nowrap" style="text-align: left;"  VALIGN="TOP">&nbsp;
                             <b style="font-size:70px;" class="text">EGP:&nbsp;<!-- <br> --><?php echo $ref = $row_refac['int_cod_ref_op'] . "-" . $row_refac['version_ref_op']; ?></b>
                         </td>
                         <td colspan="3" style="text-align: center;">
                             <canvas id="ref"></canvas> 
                         </td>  
                     </tr> 

                      <tr>    
                         <td colspan="6">
                          <b style="font-size:20px;" class="text">&nbsp;REF: </b>
                          <?php if($row_refac['int_cod_ref_op']=='1363'): ?> 
                            <samp style="font-size:15px;" class="text"> 
                              &nbsp;<?php echo $client = 'BOLSA SEGURIDAD AIRE/25X38/3.5MILS'; ?> 
                            </samp>
                          <?php elseif($row_refac['int_cod_ref_op']=='1364'): ?> 
                            <samp style="font-size:15px;" class="text"> 
                             &nbsp;<?php echo $client = 'BOLSA SEGURIDAD AIRE/40X50/3.5MILS'; ?> 
                            </samp>
                          <?php endif;?> 
                          <samp style="font-size:20px;" class="text">
                             &nbsp;<?php 
                              //ORDEN DE PRODUCCION HAY QUE DEJAR ESTA CONSULTA LAS MODERNAS NO FUNCIONARON
                              $sqlp="SELECT * FROM tbl_refcliente WHERE int_ref_ac_rc='".$row_refac['int_cod_ref_op']."' AND id_c_rc='".$row_refac['int_cliente_op']."' ";
                              $resultp=mysql_query($sqlp);
                              $nump=mysql_num_rows($resultp);
                              if($nump >= '1') { 
                                   $int_ref_ac_rc=mysql_result($resultp,0,'int_ref_ac_rc');
                                   $str_ref_cl_rc=mysql_result($resultp,0,'str_ref_cl_rc');
                                   $str_descripcion_rc=mysql_result($resultp,0,'str_descripcion_rc');
                                }
                                
                               if($int_ref_ac_rc) {  
                                 echo $client = $str_ref_cl_rc . ' ' . $str_descripcion_rc; 
                             } else {
                               echo $client = "N.A";
                           } ?></samp>
                          
                       </td>
                   </tr> 

                   <!-- <tr>
                        <td><h3><b>DESCRIPCION:</b></h3> </td>
                        <td><h4><?php echo $row_refac_refcl['str_descripcion_rc'];?></h4></td> 
                    </tr> --> 
                    <tr nowrap="nowrap">    
                        <!-- <td ><h3><b> GROSS WEIGHT:</b><br>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $row_refac['int_kilos_op']; ?> kg </h3></td> -->
                        <td colspan="3" nowrap="nowrap">&nbsp;<b style="font-size:80px;" class="text">CANT: </b>
                        <?php $cantidaddepaq = $row_cantidad['int_undxcaja_tn'] / $row_cantidad['cantidades'] ?>  
                           <b style="font-size:80px;" class="text">
                            <?php echo $cantidad = $row_cantidad['registros'] == 1 && $row_cantidad['int_paquete_tn']==$cantidaddepaq ? $row_vista_paquete['int_undxcaja_tn'] : $row_cantidad['cantidades']; // $row_vista_paquete['int_undxcaja_tn']; ?>
                          </b>
                        </td>  
                           <td colspan="3"><canvas id="cantidad"></canvas>
                           </td>
                       </tr> 

                       <tr>
                        <td colspan="3">
                          <?php  if($varControl==1): ?>
                           &nbsp;<b style="font-size:80px;" class="text">N. PEDIDO: </b>
                           &nbsp;<samp style="font-size:70px;" class="text">
                            <?php echo $oc = "5621000091"; ?>
                          </samp>
                           <?php  else: ?>
                           &nbsp;<b style="font-size:80px;" class="text">O.C: </b>
                              <b style="font-size:80px;" class="text"><?php if($row_refac['str_numero_oc_op'] ) {
                              echo $oc = $row_refac['str_numero_oc_op']; 
                                } else {
                              echo $oc = "N.A";
                          } ?>
                        <?php  endif;?>
                        </b>
                      </td>    
                      <td colspan="3"><canvas id="sku"></canvas><br>
                      </td> 
                  </tr> 
                  <tr>  <td colspan="6"><b style="font-size:20px;" class="text">&nbsp;NUMERACION:&nbsp;</b></td> </tr>
                  <tr>
                      <td colspan="6">&nbsp;<b style="font-size:70px;" class="text"><?php echo $row_vista_paquete['int_desde_tn'].$row_refac['charfin']." - ".$row_vista_MAX['int_hasta_tn'].$row_refac['charfin'];?></b> 
                      </td> 
                   </tr>
                    <tr>
                      <td colspan="6"><?php if($row_refac['lote']!=''):?>  
                        &nbsp;<b style="font-size:20px;" class="text">LOTE: <?php echo $row_refac['lote']; ?> </b>
                      <?php endif;?>
                      </td>
                    </tr>
                      <?php if($row_cliente['id_c']=='37'):?>
                       <?php $fechaActual =  $row_vista_paquete['fecha_ingreso_tn'];  

                       $nuevafecha = strtotime ( '+2 year' , strtotime ( $fechaActual ) ) ;
                       $fechaVence = date ( 'Y-m-d' , $nuevafecha );    ?>
                       <tr>
                           <td colspan="6">
                             &nbsp;<span style="font-size:15px;" class="text">Fecha Fabricado:  <?php echo $fechaActual; ?> 
                               &nbsp;&nbsp;&nbsp;&nbsp; | &nbsp; &nbsp; &nbsp;</span>
                            <span style="font-size:15px;" class="text">Fecha Vencimiento:  <?php echo $fechaVence; ?></span> 
                        </td>
                    </tr>
                <?php endif;?> 
                <tr>
                    <td colspan="3"> 
                        <?php  $faltantes = array();
                        if($registros[0]['int_inicial_f']!='') 
                        { 
                       /* foreach($registros as $d) {
                            $faltantes[] = $d[0]." Al ".$d[1]. " \n" ; 
                        }*/
                        foreach ($registros as $row_vista_faltantes) { 
                            $faltantes[] = $row_vista_faltantes['int_inicial_f'].$row_refac['charfin']." Al ".$row_vista_faltantes['int_final_f'].$row_refac['charfin']. "\n" ; 
                        } 
                    }
                    if (empty($faltantes)){
                      $faltantes = "SIN FALTANTES"; 
                  }  
                  ?>
                 <!--  <?php //if($faltantes !="SIN FALTANTES"):?>
                    <div class="qrcode" id="qr"></div> 
                <?php //endif;?> -->

                &nbsp;<b style="font-size:25px;" class="text">COD EMP:&nbsp; <?php echo $row_cantidad['int_cod_empleado_tn'];?></b><br>
                &nbsp;<b style="font-size:25px;" class="text">COD AUX:&nbsp;<?php echo $row_cantidad['int_cod_rev_tn'];?></b>
           

            </td> 
            <td colspan="3">
                &nbsp;<b style="font-size:20px;" class="text">CARTON:</b> 
                &nbsp;<samp style="font-size:70px; text-align:center; " class="text" ><?php echo $row_vista_paquete['int_caja_tn'];?></samp>
                <!--<div style="font-size:60px">2999</div>-->
            </td>
        </tr>
        <!-- <tr>    
            <td><b style="font-size:8px;">Cod Emp:&nbsp;&nbsp;&nbsp;<?php echo $row_vista_paquete['int_cod_empleado_tn'];?></b></td>
            <td><b style="font-size:8px;">Cod Aux:&nbsp;&nbsp;&nbsp;<?php echo $row_vista_paquete['int_cod_rev_tn'];?></b></td>
        </tr> -->

    </table> 
    <br> 
    <?php if($faltantes !="SIN FALTANTES"):?>
        <table style="width:100%" border="4">
            <tr>
                <td style="text-align: center;" >
                  <span style="font-size:10px;" class="text">FALTANTES: </span>   
                   <div class="qrcode" id="qr"></div>  
              </td>
          </tr>
      </table>
  <?php endif;?>

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
 /*  
 De la misma forma que los códigos de dos dimensiones, podremos definir el aspecto del barcode: color de fondo, color de las barras verticales, ancho y algo de las barras, etc.

  $(selector).barcode('1234567890128', 'codabar',
     {
      'barWidth': 2,
      'barHeight': 90,
      'color': '#1D82AF',
      'bgColor': '#ffffff',
      'fontSize': 30
     }
     );*/

//codigo barras 
var sku = <?php echo json_encode($oc); ?>;
$("#sku").JsBarcode(sku,
    {width:2,height:30});

var cantidad = <?php echo json_encode($cantidad); ?>;
$("#cantidad").JsBarcode(cantidad,
    {width:2,height:30});

var ref = <?php echo json_encode($ref); ?>;
$("#ref").JsBarcode(ref,
    {width:1,height:25});

/*var dir = <?php //echo json_encode($dir); ?>;
$("#dir").JsBarcode(dir,
{width:1,height:40});*/

  //codigo QR
  //el tamaño del codigo lo define en jQuery_QR - > jquery.classyqr.js linea 15
  //El tamaño máximo y la corrección de error más baja dan los siguientes valores:
  //Sólo numérico Máx. 7.089 caracteres
  //Alfanumérico Máx. 4296 caracteres
  //Binario / byte Máx. 2953 caracteres (bytes de 8 bits)
/*size -  tamaño del código QR. Puede omitirse, el valor predeterminado es 230 .

encoding -  codificación del QR. Se puede omitir, podría ser uno de: UTF-8 / Shift_JIS / ISO-8859-1, el valor predeterminado es UTF-8

type -  tipo QR. Puede ser: texto / url / sms / correo electrónico / llamada / ubicación / wifi / contacto, el valor predeterminado es TEXTO

create -  si el complemento creará una etiqueta de imagen dentro del contenedor o simplemente reemplazará el src de una etiqueta de imagen ya existente. Puede ser verdadero o falso, el valor predeterminado es FALSO .

text -  el texto a codificar en el QR. Solo es necesario para códigos QR de texto, correo electrónico o sms.

number -  el número de teléfono si desea crear un sms o un código QR de llamada.

email -  la dirección de correo electrónico en caso de que desee crear un código QR de correo electrónico.

subject -  el asunto en caso de que desee crear un código QR de correo electrónico.

latitude -  la latitud geográfica para el código QR de geolocalización.

longitude -  la longitud geográfica del código QR de geolocalización.

address -  la dirección de casa en caso de que desee crear un código QR de contacto.

name -  el nombre de la persona en caso de que desee crear un código QR de contacto.

url -  la dirección URL en caso de que desee crear una URL o un código QR de contacto.

alt -  el código alt para la imagen creada / reemplazada. Puede ser omitido.

note -  una nota en caso de que desee crear un código QR de contacto.*/


var codigo =  <?php echo json_encode($faltantes); ?>;
  //var myArray = [codigo]; //codigo
   //alert(codigo);
   /*for(var i=0;i<codigo.length;i++){
     alert(codigo[i]);
 } */ 
 $(document).ready(function() {
   $('#qr').ClassyQR({
     create: true,  
       //type: 'subject',
       //subject: codigo,  
       type: 'text',  
       text: codigo  
   });
});   
</script>