<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
require_once('AjaxControllers/Actions/funcioness.php');
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
$Cadenas = new Cadenas();

//$row_ordencompra = $conexion->llenarCampos('tbl_orden_compra oc, tbl_remisiones rem',"WHERE oc.str_numero_oc = rem.str_numero_oc_r AND oc.str_numero_oc= '".$_GET['ocompra']."' ",'GROUP BY rem.str_numero_oc_r ','oc.str_numero_oc,rem.str_transportador_r,rem.str_guia_r ' ); 

$row_ordencompra = $conexion->llenarCampos('tbl_remisiones',"WHERE  str_numero_oc_r= '".$_GET['ocompra']."' ",'GROUP BY str_numero_oc_r ','str_numero_oc_r, str_transportador_r, str_guia_r ' ); 

$cajasAImprimir = $_GET['cajas'];
$hasta = $_GET['nuninicio'];
$undxcaja = $_GET['undxcajas'];
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
  <?php  
    //$hastaNum=$hastaNum-$undxpaq;
    for ($i=0; $i < $cajasAImprimir; $i++) {  
     
     if($hasta!='S/N'){
      $hasta = $Cadenas->str_split_unicode($hasta);
      $hastaLetr = $Cadenas->retornaLetras($hasta);
      $hastaNum =  $Cadenas->retornaNumer($hasta);

      $desde = $hastaLetr.( ($hastaNum ) +1);

     }else{
      $desde = 'S/N';
     }
    ?>
    <div align="center" id="seleccion"  onClick="cerrar('seleccion');"><!--onClick="javascript:imprSelec('seleccion')"-->
        <div class="container-fluid" > 
            <table style="width:100%" border="4">
                <tr nowrap="nowrap"><!-- rowspan="2"  -->
                   
                   <td>
                        <samp style="font-size:20px;" class="text">ORDEN C:</samp><b style="font-size:20px;" class="text"> <?php
                             echo $oc = $_GET['ocompra'] ;  
                           ?> </b>
                   </td> 
                   <td rowspan="2" id="fondo"><img style="width: 110px; height: 45px;" src="images/logoacyc.jpg" />&nbsp;<br><br>  <br></td>
                    </tr>
                     
                     <tr nowrap="nowrap"><td style="text-align: center;" colspan="2">&nbsp; &nbsp;&nbsp;  </td></tr>
                      

                    <tr nowrap="nowrap">    
                          <td  nowrap="nowrap" style="text-align: left;"  VALIGN="TOP">
                             <b style="font-size:20px;" class="text">CODIGO SKU:&nbsp; 
                              <?php  $ref = $_GET['ref'];

                                    switch ($ref) {
                                      case '445':
                                            $sku = "23415";
                                            $descrip = "SOBRE BOLSILLO ADHES DARDO*100";
                                        break;
                                      case '1073':
                                            $sku = "30165";
                                            $descrip = "BOLS COURRI 2T C2.5 13*20 * 100"; 
                                        break;
                                      case '844':
                                            $sku = "32287";
                                            $descrip = "BOLSA MEDIAN 4 CM SOL C2 TCC*25";
                                        break;
                                      case '843':
                                            $sku = "32289";
                                            $descrip = "BOLSA PEQUE 4CM SOL C2 TCC*25";
                                        break;
                                      default:
                                            $sku = "0000";
                                            $descrip = "NINGUN SKU SELECCIONADO";
                                        break;
                                    }
                                    echo $sku;
                               ?></b>
                         </td>
                         <td style="text-align: center;">
                             <b style="font-size:20px;" class="text">DESCRIPCION: &nbsp;</b> <br>
                              <b style="font-size:15px;" class="text"> <?php echo $ref = $descrip; ?></b>
                         </td>  
                      </tr>  
                     
                     <tr nowrap="nowrap"><td style="text-align: center;" colspan="2">&nbsp; &nbsp;&nbsp;  </td></tr>
                     <tr nowrap="nowrap"><td style="text-align: center;" colspan="2">&nbsp; &nbsp;&nbsp;  </td></tr>
                     <tr nowrap="nowrap">  
                        <td style="text-align: center;" colspan="2"> <b style="font-size:20px;" class="text"> RANGO DE NUMERACION </b></td>
                     </tr>

                      <tr nowrap="nowrap">
                       <td  nowrap="nowrap" class="fuentev1"><b style="font-size:20px;" class="text"> DESDE</b></td>
                       <td  id="fuentev1"><b style="font-size:20px;" class="text">
                         <?php 
                            echo $desde;
                            $desde = $Cadenas->str_split_unicode($desde);
                            $desdeLetr = $Cadenas->retornaLetras($desde);
                            $desdeNum =  $Cadenas->retornaNumer($desde);
                            $desde= $desdeLetr.$desdeNum;
                         ?></b> </td>
                     </tr>
                     <tr>
                       <td  nowrap="nowrap" class="fuentev1"><b style="font-size:20px;" class="text">HASTA</b></td>
                       <td  id="fuentev1"><b style="font-size:20px;" class="text"><?php 

                          $desde = ($desdeNum + $undxcaja)-1; 
                          if($hasta!='S/N'){
                           $hasta =($desdeLetr.$desde);
                          }else{
                             $hasta = 'S/N';
                          }
                          echo $hasta;
                           ?></b> 
                     </td>
                     </tr>
                   <tr nowrap="nowrap"><td style="text-align: center;" colspan="2">&nbsp; &nbsp;&nbsp;  </td></tr>
                   <tr nowrap="nowrap"><td style="text-align: center;" colspan="2">&nbsp; &nbsp;&nbsp;  </td></tr>
                   <tr nowrap="nowrap"><td style="text-align: center;" colspan="2">&nbsp; &nbsp;&nbsp;  </td></tr>
                  <tr>
                    <td ><b style="font-size:15px;" class="text"><b style="font-size:20px;" class="text">GUIA TRANSPORTADORA:&nbsp;</b> <br>
                       <b style="font-size:20px;" class="text"><?php echo $row_ordencompra['str_guia_r']; //$row_ordencompra['str_transportador_r'];?></b> 
                     </td> 
                     <td ><b style="font-size:15px;" class="text"><b style="font-size:20px;" class="text">CAJAS:&nbsp;</b> 
                       <b style="font-size:20px;" class="text"><?php $paq_gen=($i+1); echo $paq_gen . ' DE ' .$cajasAImprimir; ?></b> 
                     </td> 
                   </tr> 
                   <tr nowrap="nowrap"><td style="text-align: center;" colspan="2">&nbsp; &nbsp;&nbsp;  </td></tr>
                   <tr nowrap="nowrap"><td style="text-align: center;" colspan="2">&nbsp; &nbsp;&nbsp;  </td></tr>
       </table> 
    <br> 

</div>

<div id="oculto">
    <table width="200" border="0" align="center">
        <tr>
            <td><input name="cerrar" type="button" autofocus value="cerrar"onClick="cerrar('seleccion');
                return false" ></td>
            </tr>
        </table>
    </div>

<?php } ?>
</body>
</html>
</html>
<script>
   
</script>