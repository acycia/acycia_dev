<?php require_once('Connections/conexion1.php'); ?><?php
if (!isset($_SESSION)) {
    session_start();
}

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

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
    $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);
//ORDEN DE PRODUCCION
$colname_op = "-1";
if (isset($_GET['id_op'])) {
    $colname_op = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_refac_refcl = sprintf("SELECT id_op,int_cod_ref_op,int_cliente_op,version_ref_op,int_ref_ac_rc,str_descripcion_rc,str_ref_cl_rc,str_numero_oc_op FROM Tbl_orden_produccion, Tbl_refcliente WHERE Tbl_orden_produccion.id_op=%s AND Tbl_orden_produccion.int_cod_ref_op=Tbl_refcliente.int_ref_ac_rc AND Tbl_orden_produccion.int_cliente_op=Tbl_refcliente.id_c_rc", $colname_op);
$refac_refcl = mysql_query($query_refac_refcl, $conexion1) or die(mysql_error());
$row_refac_refcl = mysql_fetch_assoc($refac_refcl);
$totalRows_refac_refcl = mysql_num_rows($refac_refcl);
////cliente
mysql_select_db($database_conexion1, $conexion1);
$query_cliente = sprintf("SELECT cliente.nombre_c,cliente.direccion_c,cliente.direccion_entrega_c FROM Tbl_orden_produccion, Tbl_refcliente,cliente WHERE Tbl_orden_produccion.id_op=%s AND Tbl_orden_produccion.int_cod_ref_op=Tbl_refcliente.int_ref_ac_rc AND Tbl_orden_produccion.int_cliente_op=Tbl_refcliente.id_c_rc", $colname_op);
$cliente = mysql_query($query_cliente, $conexion1) or die(mysql_error());
$row_cliente = mysql_fetch_assoc($cliente);
$totalRows_cliente = mysql_num_rows($cliente);

$colname_op = "-1";
if (isset($_GET['id_op'])) {
    $colname_op = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_refac = sprintf("SELECT id_op,int_cod_ref_op,version_ref_op,int_kilos_op FROM Tbl_orden_produccion WHERE id_op=%s", $colname_op);
$refac = mysql_query($query_refac, $conexion1) or die(mysql_error());
$row_refac = mysql_fetch_assoc($refac);
$totalRows_refac = mysql_num_rows($refac);

$colname_op = "-1";
if (isset($_GET['id_op'])) {
    $colname_op = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
$colname_p = "-1";
if (isset($_GET['int_caja_tn'])) {
    $colname_p = (get_magic_quotes_gpc()) ? $_GET['int_caja_tn'] : addslashes($_GET['int_caja_tn']);
}

//SELECT * FROM Tbl_tiquete_numeracion WHERE int_op_tn=%s AND int_caja_tn=%s ORDER BY id_tn ASC
mysql_select_db($database_conexion1, $conexion1);
$query_vista_paquete = sprintf("SELECT int_op_tn, int_caja_tn, int_caja_tn, fecha_ingreso_tn, int_desde_tn, int_undxcaja_tn, int_cod_empleado_tn,int_cod_rev_tn FROM Tbl_tiquete_numeracion WHERE int_op_tn=%s AND int_caja_tn=%s ORDER BY int_paquete_tn ASC LIMIT 1", $colname_op, $colname_p);
$vista_paquete = mysql_query($query_vista_paquete, $conexion1) or die(mysql_error());
$row_vista_paquete = mysql_fetch_assoc($vista_paquete);
$totalRows_vista_paquete = mysql_num_rows($vista_paquete);

$colname_MAX = "-1";
if (isset($_GET['id_op'])) {
    $colname_MAX = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
$colname_MAX2 = "-1";
if (isset($_GET['int_caja_tn'])) {
    $colname_MAX2 = (get_magic_quotes_gpc()) ? $_GET['int_caja_tn'] : addslashes($_GET['int_caja_tn']);
}
//SELECT * FROM Tbl_tiquete_numeracion WHERE int_op_tn=%s AND int_caja_tn=%s ORDER BY int_paquete_tn DESC
mysql_select_db($database_conexion1, $conexion1);
$query_vista_MAX = sprintf("SELECT int_hasta_tn FROM Tbl_tiquete_numeracion WHERE int_op_tn=%s AND int_caja_tn=%s ORDER BY int_paquete_tn DESC LIMIT 1", $colname_MAX, $colname_MAX2);
$vista_MAX = mysql_query($query_vista_MAX, $conexion1) or die(mysql_error());
$row_vista_MAX = mysql_fetch_assoc($vista_MAX);
$totalRows_vista_MAX = mysql_num_rows($vista_MAX);
//faltantes
//VARIABLES GET
$colname_faltantes_op = $_GET['id_op'];
$colname_faltantes_k =  $_GET['int_caja_tn'];
/*mysql_select_db($database_conexion1, $conexion1);
$query_vista_faltantes = sprintf("SELECT distinct Tbl_faltantes.int_inicial_f, Tbl_faltantes.int_final_f FROM Tbl_tiquete_numeracion, Tbl_faltantes WHERE Tbl_tiquete_numeracion.int_op_tn=%s AND Tbl_tiquete_numeracion.int_caja_tn=%s  AND Tbl_tiquete_numeracion.int_op_tn=Tbl_faltantes.id_op_f AND Tbl_tiquete_numeracion.int_caja_tn=Tbl_faltantes.int_caja_f ORDER BY Tbl_faltantes.int_inicial_f ASC", $colname_faltantes_op, $colname_faltantes_k);
$vista_faltantes = mysql_query($query_vista_faltantes, $conexion1) or die(mysql_error());
$row_vista_faltantes = mysql_fetch_assoc($vista_faltantes);
$totalRows_vista_faltantes = mysql_num_rows($vista_faltantes);*/

mysql_select_db($database_conexion1, $conexion1);
$query_vista_faltantes = sprintf("SELECT distinct Tbl_faltantes.int_inicial_f, Tbl_faltantes.int_final_f FROM Tbl_tiquete_numeracion, Tbl_faltantes WHERE Tbl_tiquete_numeracion.int_op_tn=%s AND Tbl_tiquete_numeracion.int_caja_tn=%s  AND Tbl_tiquete_numeracion.int_op_tn=Tbl_faltantes.id_op_f AND Tbl_tiquete_numeracion.int_caja_tn=Tbl_faltantes.int_caja_f ORDER BY Tbl_faltantes.int_inicial_f ASC", $colname_faltantes_op, $colname_faltantes_k);
$vista_faltantes = mysql_query($query_vista_faltantes, $conexion1) or die(mysql_error());
$row_vista_faltantes = mysql_fetch_assoc($vista_faltantes);
$totalRows_vista_faltantes = mysql_num_rows($vista_faltantes);


$colname_op = "-1";
if (isset($_GET['id_op'])) {
    $colname_op = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_direccion = sprintf("SELECT  tbl_items_ordenc.id_pedido_io,tbl_items_ordenc.str_direccion_desp_io,tbl_items_ordenc.str_numero_io FROM Tbl_orden_produccion, tbl_items_ordenc  WHERE tbl_orden_produccion.id_op=%s and tbl_orden_produccion.int_cod_ref_op = tbl_items_ordenc.int_cod_ref_io order by tbl_items_ordenc.id_pedido_io DESC", $colname_op);
$direccion = mysql_query($query_direccion, $conexion1) or die(mysql_error());
$row_direccion = mysql_fetch_assoc($direccion);
$totalRows_direccion = mysql_num_rows($direccion);
?>
<html>
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
  font-size: 2vw; 
  height: auto; 
}
.text { font-family: "Impact", Garamond, 'Comic Sans'; }
</style> 
</head>
<body onLoad="self.print();"><!--self.close();-->
    <div align="center" id="seleccion"  onClick="cerrar('seleccion');"><!--onClick="javascript:imprSelec('seleccion')"-->
        <div class="container-fluid" > 
            <table style="width:100%" border="4">
                <tr><!-- rowspan="2"  -->
                    
                <td rowspan="2" id="fondo"><img style="width: 110px; height: 45px;" src="images/logoacyc.jpg" />
                <br></td>
                <td>
                   <samp style="font-size:12px;" class="text"> CLIENT:</samp><br><b style="font-size:17px;" class="text"><?php echo $row_cliente['nombre_c'];?></b></td>
                    <!-- <td id="fondo"><img src="images/logoacyc.jpg"/><br></td>  -->
                    
                </tr>
               <tr>
                        <td colspan="2"><b style="font-size:20px;"> </td>
             </tr>

                <tr>
                        <td colspan="2"><b style="font-size:20px;" class="text">SHIP TO:</b>
                            &nbsp;&nbsp;<samp style="font-size:15px;" class="text"> <?php 
                   $dir= $row_cliente['direccion_c'];
                   $dir_e = $row_cliente['direccion_entrega_c'];
                if ($dir== '') {
                  echo $dir = $dir_e;
                  } else  if ($dir_e== '') {
                  echo $dir = $dir;
                  }else{$dir="N/A";$dir_e="N/A";}
                            ?></samp></td>
             </tr>



                    <!-- <tr>
                        <td class="container"><h2><b>SKU #:</b> <?php if ($row_refac_refcl['str_ref_cl_rc'] == '') {
                             $sku = "N.A";
                        } else {
                           $sku = $row_refac_refcl['str_ref_cl_rc']; 
                       } ?></h2> </td> 
                       <td class="container"><canvas id="sku"></canvas><br><br> </td>
                    </tr> -->


                    <tr>    
                       <td colspan="2" style="text-align: center;">
                        <b style="font-size:30px;" class="text">REF:&nbsp;&nbsp; <?php echo $ref = $row_refac['int_cod_ref_op'] . "-" . $row_refac['version_ref_op']; ?></b>
                        </td>  
                   </tr> 

                    <tr>    
                       <td colspan="2"><canvas id="ref"></canvas>  </td>
                   </tr> 

                   <!-- <tr>
                        <td><h3><b>DESCRIPCION:</b></h3> </td>
                        <td><h4><?php echo $row_refac_refcl['str_descripcion_rc'];?></h4></td> 
                   </tr> -->


                   <tr>    
                        <!-- <td ><h3><b> GROSS WEIGHT:</b><br>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $row_refac['int_kilos_op']; ?> kg </h3></td> -->
                        <td ><b style="font-size:20px;" class="text"> CANT:&nbsp;</b>  
                         <b style="font-size:35px;" class="text"><?php echo $cantidad =  $row_vista_paquete['int_undxcaja_tn']; ?>&nbsp; </b></td >
                         <td ><canvas id="cantidad"></canvas>
                        </td>
                    </tr> 

                <tr>
                    <td ><b style="font-size:20px;" class="text">ORDEN C:</b>
                        <?php if (empty($row_refac_refcl['str_numero_oc_op'])) {
                             $oc = "N.A";
                        } else {
                           $oc = $row_refac_refcl['str_numero_oc_op']; 
                       } ?>
                    </td>    
                     <td ><canvas id="sku"></canvas><br>
                    </td> 
                </tr>


                <tr>
                    <td colspan="2"><b style="font-size:15px;" class="text"><b>NUMERACION:&nbsp;</b> 
                     <b style="font-size:20px;" class="text"><?php echo $row_vista_paquete['int_desde_tn']." - ".$row_vista_MAX['int_hasta_tn']; ?></b></h2></td> 
                </tr>
                <tr>
                    <td> 
                        <?php  
                          $faltantes = array();
                       if(!empty($row_vista_faltantes['int_inicial_f']))
                        {
                           do { 
                              $faltantes[] = $row_vista_faltantes['int_inicial_f']." - ".$row_vista_faltantes['int_final_f']. ", \n"; 
                          } while ($row_vista_faltantes = mysql_fetch_assoc($vista_faltantes)); 
                      }if (empty($faltantes)){
                        $faltantes = "SIN FALTANTES";
                         
                    }  
                ?><div class="qrcode" id="qr"></div> 
                <b style="font-size:10px;" class="text">FALTANTES: </b>
            </td> 
            <td ><b style="font-size:20px;" class="text">CARTON:</b>&nbsp; 
                <samp style="font-size:45px; text-align:center; " class="text" >&nbsp;<?php echo $row_vista_paquete['int_caja_tn'];?></samp>
                <!--<div style="font-size:60px">2999</div>--></td>
            </tr>
            <tr>    
                <td><b style="font-size:8px;">Cod Emp:&nbsp;&nbsp;&nbsp;<?php echo $row_vista_paquete['int_cod_empleado_tn'];?></b></td>
                <td><b style="font-size:8px;">Cod Aux:&nbsp;&nbsp;&nbsp;<?php echo $row_vista_paquete['int_cod_rev_tn'];?></b></td>
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
  
 
//codigo barras 
var sku = <?php echo json_encode($oc); ?>;
$("#sku").JsBarcode(sku,
    {width:2,height:30});

var cantidad = <?php echo json_encode($cantidad); ?>;
$("#cantidad").JsBarcode(cantidad,
    {width:2,height:30});

var ref = <?php echo json_encode($ref); ?>;
$("#ref").JsBarcode(ref,
    {width:2,height:30});

/*var dir = <?php //echo json_encode($dir); ?>;
$("#dir").JsBarcode(dir,
    {width:1,height:40});*/

  //codigo QR

    var codigo =  <?php echo json_encode($faltantes); ?>;
    var myArray = [codigo];
    $(document).ready(function() {
        $('#qr').ClassyQR({
   create: true,  
   type: 'text',  
   text: myArray  
         });
    });   
 </script>
<?php
mysql_free_result($usuario);

mysql_free_result($vista_paquete);

mysql_free_result($vista_MAX);

mysql_free_result($refac_refcl);

mysql_free_result($refac);
?>
