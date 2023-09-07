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
$usuario = mysql_query($query_usuario, $conexion1) ;
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);
//ORDEN DE PRODUCCION
mysql_select_db($database_conexion1, $conexion1);
$query_ticket = "SELECT consecutivo,consecutivo2 FROM ticket ORDER BY consecutivo DESC";
$ticket = mysql_query($query_ticket, $conexion1) or die(mysql_error());
$row_ticket = mysql_fetch_assoc($ticket);
$totalRows_ticket = mysql_num_rows($ticket);
?>
<?php
  $CODIGO =$_GET['codigoe'].'/';//EN EL CODIGO BARRAS
  $ID = $_GET['idenvio'];//EL ID DEL TEXTO
/* switch ($_GET['tipo']) {
   case 'TCC':
   $CODIGO ='1222788/';//EN EL CODIGO BARRAS
   $ID = '47689176';//EL ID DEL TEXTO
     break;
    
   default:
        $CODIGO ='1453847/';
        $ID = '586429948';
     break;
 }*/
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title>SISADGE AC & CIA</title>
  <link href="css/vista.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="AjaxControllers/updateConAlert.js"></script>
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

 
      <script type="text/javascript" >
              function cerrar(num) {

                //var valores =document.getElementById("consecutivo").value; 
                //updateConAlert('UpdateSiTick',valores,'ticket.php');
                window.opener.location.reload();
                window.close();//cierra popUp
              }
 
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
   $consecInicial = ($_GET['consecInicial']);
   $codigobarras =  array();
   $consecutivo =  array();
   $desce = $_GET['consecFinal']+1;// numero de cantidad de etiquetas 68
   $tipobarras = $_GET['tipobarras'];
    for ($i=$consecInicial; $i < $desce; $i++) { 
    ?>  
  <div style="text-align: center;"  id="seleccion"  onClick="cerrar('seleccion');"><!--onClick="javascript:imprSelec('seleccion')"-->
    <div class="container-fluid"   > 

      <table style="width:100%;text-align: center;" border="2"   >
        <tr> 
          <!-- <td rowspan="2" id="fondo"><img style="width: 110px; height: 80px;" src="images/logoacyc.jpg" /><br></td> -->
            <td colspan="4">
             <samp style="font-size:50px;" class="text"> ETIQUETA DE BULTO</samp></td>
             <p> </p>
           </tr>
           <tr>
            <td colspan="2" style="font-size:20px;"> 
               <?php  
                  $codigobarras[] = 'ENVIO '.$CODIGO;
                  $consecutivo[] = $i;
                ?>
                
              <samp id="codigoqrs_cod<?php echo $i; ?>" style="display: none; font-size:40px;" > &nbsp;&nbsp; <span id="qr<?php echo $i; ?>" class="qrcode"></span>&nbsp;&nbsp; </samp>
              <samp id="codigobarras_cod<?php echo $i; ?>" style="display: none; font-size:40px;" > &nbsp;&nbsp; <canvas id="codigo<?php echo $i; ?>"></canvas>&nbsp;&nbsp; </samp> 
             
            </td>
            
            <td id="codigoqrs<?php echo $i; ?>" colspan="2" style="font-size:25px; text-align: left;">
              <strong>ENVIO <?php echo $CODIGO.$i; ?></strong><br>
              ALBERTOCADAVIDRCIASA <br> 
              ID: <?php echo $ID;?>
            </td>
          </tr>
          <tr id="codigobarras<?php echo $i; ?>" style="display: none; " >
            <td colspan="2" style="font-size:25px;">ALBERTOCADAVIDRCIASA - ID: <?php echo $ID;?></td>
          </tr>
        </table>  
      </div>
      <div id="oculto">
        <table width="200" border="0" align="center">
          <tr>
            <td>
              <input name="cerrar" type="button" autofocus value="cerrar" onClick="cerrar('seleccion');return false" ></td>
            </tr>
          </table>
        </div>
        <?php $consecfinal = $i; } ?>
          <input name="consecutivo" id="consecutivo" type="hidden" value="<?php echo $consecfinal; ?>">
        <p> </p>
        <p> </p>
        <p> </p>
        <p> </p>
 
      </body>
      </html>
      </html>
      <script>
//codigo barras 
$(document).ready(function() { 
    arrayJS = <?php echo json_encode($codigobarras); ?>;
    consecJS = <?php echo json_encode($consecutivo); ?>; 
    tipobarras = <?php echo json_encode($tipobarras); ?>; 
   
       arrayJS = verCodigo(arrayJS,consecJS,tipobarras);
 

 });

//codigo QR
function verCodigo(arrayJS,consecJS,tipobarras){

     if(tipobarras=='qr'){
    for(var j=0;j<consecJS.length;j++)
        {    
          for(var i=0;i<arrayJS.length;i++)
          {
            var id = consecJS[j]; 

            var secu = arrayJS[i];  
          }
 
           $('#qr'+id).ClassyQR({
               create: true,   
               type: 'text',  
               text: secu+id  
           });
          $("#codigoqrs"+id).show();
          $("#codigoqrs_cod"+id).show();
     }
   $("#codigobarras"+id).hide();
   $("#codigobarras_cod"+id).hide();

 }else{
    for(var i=0;i<arrayJS.length;i++)
    {
      for(var j=0;j<consecJS.length;j++)
           {
              var id = consecJS[j];
               
              var secu = arrayJS[i];
             $("#codigo"+id).JsBarcode(secu+id,{width:3,height:350});
             $("#codigobarras"+id).show();
             $("#codigobarras_cod"+id).show();
             $("#codigoqrs"+id).hide();
             $("#codigoqrs_cod"+id).hide();
           } 
   }
 }
}
  

 
</script>
<?php

mysql_free_result($usuario);
mysql_free_result($row_ticket);



?>
