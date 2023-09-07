<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
require_once('AjaxControllers/Actions/funcioness.php');
?> 
<?php require_once('Connections/conexion1.php'); ?>
<?php
header('Pragma: public'); 
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Aumentar el tamaño en php.ini   
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); 
header('Cache-Control: no-store, no-cache, must-revalidate'); // Aumentar el tamaño en php.ini 
header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // Aumentar el tamaño en php.ini 
header('Pragma: no-cache'); 
header('Expires: 0'); 
header('Content-Transfer-Encoding: none'); 
header('Content-Type: application/vnd.ms-excel'); // This should work for IE & Opera 
header('Content-type: application/x-msexcel'); // This should work for the rest 
header('Content-Disposition: attachment; filename="despachofaltantes.xls"');
?>
<!-- 
Aumentar el tamaño en php.ini 


max_execution_time = 360      ; Maximum execution time of each script, in seconds (I CHANGED THIS VALUE)
max_input_time = 120          ; Maximum amount of time each script may spend parsing request data
;max_input_nesting_level = 64 ; Maximum input variable nesting level
memory_limit = 128M           ; Maximum amount of memory a script may consume (128MB by default)

memory_limit 

-->
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);

  $logoutGoTo = "usuario.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

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
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
    $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
$conexion = new ApptivaDB();

$Cadenas = new Cadenas();

$currentPage = $_SERVER["PHP_SELF"];
 
 $maxRows_numeracion = 20;
$pageNum_numeracion = 0;
if (isset($_GET['pageNum_numeracion'])) {
  $pageNum_numeracion = $_GET['pageNum_numeracion'];
}
$startRow_numeracion = $pageNum_numeracion * $maxRows_numeracion;

//Filtra codigo, mes, dia llenos
mysql_select_db($database_conexion1, $conexion1);
$id_op = $_GET['id_op'];
$cajaini = trim($_GET['cajaini']);
$cajafin = trim($_GET['cajafin']);
 
if($id_op!= '' && $cajaini=='' && $cajafin=='')
{
/*$query_numeracion = "SELECT int_op_tn,int_cod_rev_tn,id_tn,fecha_ingreso_tn,int_caja_tn,int_paquete_tn,int_undxpaq_tn,int_undxcaja_tn,int_desde_tn,int_hasta_tn FROM Tbl_tiquete_numeracion WHERE int_op_tn='$id_op' AND id_despacho IS NULL GROUP BY int_op_tn, int_caja_tn, int_paquete_tn  ASC";*/

/*$registros = $conexion->llenaListas("tbl_tiquete_numeracion", " WHERE int_op_tn='".$id_op."'  AND id_despacho IS NULL","GROUP BY int_op_tn, int_caja_tn, int_paquete_tn  ASC","int_op_tn,int_cod_rev_tn,id_tn,fecha_ingreso_tn,int_caja_tn,int_paquete_tn,int_undxpaq_tn,int_undxcaja_tn,int_desde_tn,int_hasta_tn"  );*/

$registros = $conexion->llenaListas("tbl_tiquete_numeracion"," WHERE int_op_tn='".$id_op."' AND id_despacho IS NULL","GROUP BY int_op_tn, int_caja_tn, int_paquete_tn  ASC","id_tn,int_op_tn,ref_tn,int_caja_tn,int_undxcaja_tn,int_desde_tn,int_hasta_tn,pesot,int_paquete_tn,int_caja_tn,int_undxpaq_tn" );


}
if($id_op!= '' && $cajaini!='' && $cajafin!='')
{
/*$query_numeracion = "SELECT int_op_tn,int_cod_rev_tn,id_tn,fecha_ingreso_tn,int_caja_tn,int_paquete_tn,int_undxpaq_tn,int_undxcaja_tn,int_desde_tn,int_hasta_tn FROM Tbl_tiquete_numeracion WHERE int_op_tn='$id_op' and  int_caja_tn  between  $cajaini  AND $cajafin  AND id_despacho IS NULL GROUP BY int_op_tn, int_caja_tn, int_paquete_tn  ASC";*/
 
//$registros = $conexion->llenaListas("tbl_tiquete_numeracion"," WHERE int_op_tn='".$id_op."' and  int_caja_tn  between  '".$cajaini."'  AND '".$cajafin."'  AND id_despacho IS NULL","GROUP BY int_op_tn, int_caja_tn, int_paquete_tn  ASC","int_op_tn,int_cod_rev_tn,id_tn,fecha_ingreso_tn,int_caja_tn,int_paquete_tn,int_undxpaq_tn,int_undxcaja_tn,int_desde_tn,int_hasta_tn" );
//
$registros = $conexion->llenaListas("tbl_tiquete_numeracion"," WHERE int_op_tn='".$id_op."' and  int_caja_tn  BETWEEN  '".$cajaini."'  AND '".$cajafin."'  AND id_despacho IS NULL","GROUP BY int_op_tn, int_caja_tn, int_paquete_tn  ASC","id_tn,int_op_tn,ref_tn,int_caja_tn,int_undxcaja_tn,int_desde_tn,int_hasta_tn,pesot,int_paquete_tn,int_caja_tn,int_undxpaq_tn" );

}

$row_tiquete_num = $conexion->llenarCampos("tbl_tiquete_numeracion", "WHERE int_op_tn='".$id_op."' ", "ORDER BY int_paquete_tn DESC LIMIT 1", "int_undxcaja_tn,int_undxpaq_tn");
 
$undxcaja = $row_tiquete_num['int_undxcaja_tn'];
$undxpaq = $row_tiquete_num['int_undxpaq_tn'];
$paquAImprimir = ($undxcaja/$undxpaq);


$row_info_op = $conexion->llenarCampos('tbl_orden_produccion', "WHERE id_op='".$id_op."' ", "","charfin" );

?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>SISADGE AC &amp; CIA</title>
  <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
  <link href="css/formato.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="js/listado.js"></script>
  <script type="text/javascript" src="js/consulta.js"></script>
  <script type="text/javascript" src="js/validacion_numerico.js"></script>
  <script type="text/javascript" src="js/formato.js"></script>
  <!-- jquery -->
  <script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
  <script src="//code.jquery.com/jquery-1.11.2.min.js"></script> 
  <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
  <link rel="stylesheet" type="text/css" href="css/general.css"/>
 
</head>
<body>

  <table id="tabla1">
    <tr>  
      <td colspan="2" id="dato3"></td>
    </tr>  

    <?php foreach($registros as $row_numeracion) {  
      //for ($i=0; $i < $paquAImprimir; $i++) {  

        
      $cajamenos1 = $row_numeracion['int_caja_tn']-1;
      $consultodesde = $conexion->llenarCampos('tbl_tiquete_numeracion', "WHERE int_op_tn='".$id_op."' "." AND int_caja_tn='".$cajamenos1."' ", "ORDER BY int_paquete_tn DESC LIMIT 1","int_hasta_tn " );

      $hasta = ($consultodesde['int_hasta_tn']);//inicial de la etiqueta
       

     ?>
      <?php  
          if($caja != $row_numeracion['int_caja_tn'] ) { 

            $conteo = $conexion->conteoRegistro("tbl_tiquete_numeracion", "*", "WHERE int_op_tn='".$id_op."' and  int_caja_tn  =  '".$row_numeracion['int_caja_tn']."' AND id_despacho IS NULL AND imprime=1 "," ");
 
 
           ?> 
 
        <tr id="tr1">
          <td id="titulo4">N. O.P</td> 
          <td id="titulo4">REF</td> 
          <td id="titulo4">N. CAJA</td> 
          <td id="titulo4">UND X CAJA</td> 
          <td id="titulo4">DESDE</td> 
          <td id="titulo4">HASTA</td> 
          <td id="titulo4">PESO</td> 
          <td id="titulo4">N. PAQUETE</td> 
          <td id="titulo4">UND X PAQ</td> 
          <td id="titulo4">DESDE</td> 
          <td id="titulo4">HASTA</td> 
          <td id="titulo4">FALTANTES X PAQ</td> 
          <td id="titulo4">ERROR FALTAN</td> 
        </tr>
      <?php } ?> 
 
    <?php 
    $opF=$row_numeracion['int_op_tn'];
    $paqF=$row_numeracion['int_paquete_tn'];
    $cajaF=$row_numeracion['int_caja_tn'];
    $query_vista_faltantes="SELECT int_inicial_f,int_final_f,int_total_f FROM  Tbl_faltantes WHERE  Tbl_faltantes.id_op_f= '$opF'  AND  Tbl_faltantes.int_paquete_f='$paqF' AND  Tbl_faltantes.int_caja_f='$cajaF' ORDER BY Tbl_faltantes.int_inicial_f ASC"; 
    $vista_faltantes = mysql_query($query_vista_faltantes, $conexion1) or die(mysql_error());
    $row_vista_faltantes = mysql_fetch_assoc($vista_faltantes);
    $totalRows_vista_faltantes = mysql_num_rows($vista_faltantes);
    

    ?>
    <?php
        //controlo para que muestre los paquetes de los que se guardo un registro en sellado
       if($conteo['total'] > 0) { 
         
     ?>
         <?php for ($i=0; $i < $paquAImprimir; $i++) {    ?>
          <?php 
             $hasta = $Cadenas->str_split_unicode($hasta);
             $hastaLetr = $Cadenas->retornaLetras($hasta);
             $hastaNum =  $Cadenas->retornaNumer($hasta);

             $desde = $hastaLetr.( ($hastaNum ) +1); 

            ?>
          <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
           <td id="dato2"><?php echo $row_numeracion['int_op_tn'] ;?></td>
           <td id="dato2"><?php echo $row_numeracion['ref_tn']; ?></td> 
           <td id="dato2"><?php echo $row_numeracion['int_caja_tn']; ?></td> 
           <td id="dato2"><?php echo $row_numeracion['int_undxcaja_tn']; ?></td> 
           <td id="dato2"><?php 
           echo $desde;
           //proceso si tiene letras para poder sumar unidades x caja
           $desde = $Cadenas->str_split_unicode($desde);
           $desdeLetr = $Cadenas->retornaLetras($desde);
           $desdeNum =  $Cadenas->retornaNumer($desde);
           $desde= $desdeLetr.$desdeNum;
           ?> 
        </td> 
           <td id="dato2"><?php $desde = ($desdeNum + $undxpaq)-1; echo $hasta=$desdeLetr.$desde; ?></td> 
           <td id="dato2" nowrap="nowrap"><?php echo $row_numeracion['pesot']; ?></td> 
           <td id="dato2"><?php echo $i+1; ?></td>
           <td id="dato2"> </td> 
           <td id="dato2"><?php echo $row_numeracion['int_undxpaq_tn']; ?></td>     
           <td id="dato2"> </td>
          </tr>
         <?php }  ?>

     <?php 
         //controlo para que muestre los paquetes de los que se guardo un registro en sellado
       } else { 
  
      ?>

   <?php   do {  ?>
    <tr >
      <td id="dato2"><?php echo $row_numeracion['int_op_tn'] ;?></td>
      <td id="dato2"><?php echo $row_numeracion['ref_tn']; ?></td> 
      <td id="dato2"><?php echo $row_numeracion['int_caja_tn']; ?></td> 
      <td id="dato2"><?php echo $row_numeracion['int_undxcaja_tn']; ?></td> 
      <td id="dato2"><?php echo $row_numeracion['int_desde_tn']; ?><?php echo $row_info_op['charfin'];?></td> 
      <td id="dato2"><?php echo $row_numeracion['int_hasta_tn']; ?><?php echo $row_info_op['charfin'];?></td> 
      <td id="dato2"  nowrap="nowrap"><?php echo $row_numeracion['pesot']; ?></td> 
      <td id="dato2"><?php echo $row_numeracion['int_paquete_tn']; ?></td> 
      <!-- <td id="dato2"><?php echo $cajasinFaltantes; ?></td>   -->   
      <td id="dato2"><?php echo $row_numeracion['int_undxpaq_tn']; ?></td>
      
     <!-- faltantes -->
      <td id="dato2"><?php echo $row_vista_faltantes['int_inicial_f']; ?><?php echo $row_info_op['charfin'];?></td> 
      <td id="dato2"><?php echo  $row_vista_faltantes['int_final_f']; ?><?php echo $row_info_op['charfin'];?></td> 
      <td id="dato2"><?php echo  $row_vista_faltantes['int_total_f'];?></td>
      <!-- fin faltantes --> 
 
      <td id="dato2">
       <span id="Error<?php echo $row_numeracion['int_desde_tn'];?><?php echo $row_info_op['charfin'];?>"></span>     

       <?php 
         
          $query_error="SELECT sum(int_total_f) as sumat FROM  Tbl_faltantes WHERE id_op_f=$opF AND int_paquete_f=$paqF AND int_caja_f=$cajaF group by int_paquete_f "; 
           $vista_error = mysql_query($query_error, $conexion1) or die(mysql_error());
           $row_error = mysql_fetch_assoc($vista_error);
       ?>
        <script type="text/javascript">
          numDesde = "<?php echo $row_numeracion['int_desde_tn'];?>"; 
          numHasta = "<?php echo $row_numeracion['int_hasta_tn'];?>";   
          undP = "<?php echo $row_numeracion['int_undxpaq_tn'];?>"; 
          paqF = "<?php echo $row_numeracion['int_paquete_tn'];?>";      

          sumat = "<?php echo $row_error['sumat'];?>";      

         
                sumat = ( sumat == "" || sumat == "NaN" ) ? 0 : sumat; 
      
           if(numDesde!=''){
                   numeracionDesdeF(numDesde,numHasta,undP,paqF,sumat);
            }     

           function numeracionDesdeF(numDesde,numHasta,undP,paqF,sumat) {
      
            var dividida1 = numeracionCharF(numDesde); 
            var numerosD = dividida1[0];
            var cadena = dividida1[1];     

            var dividida2 = numeracionCharF(numHasta); 
            var numerosH = dividida2[0];
            var cadena2 = dividida2[1];     

            var result=parseInt(numerosH) - parseInt(numerosD);
            var RangoT =parseInt(result) + parseInt(1); 
           
             var TotalundP = parseInt(undP) + parseInt(sumat) ;     

             var conteo = numDesde;
             
         
           if(RangoT > TotalundP){
               $("#Error"+conteo).text('Error: '+paqF);
                  alert('Paque: '+ paqF+' RangoT: '+RangoT + ' sumat: ' +TotalundP);     

               } 
           }     
     

        </script>
         
       </td> 
       <script type="text/javascript">
              

         //FUNCION GENERAL AUXILIARES FALTANTES DE TIQUETES SELLADO  
         function numeracionCharF(carac){
           var num="",caden="",l="",b="",c="",d="",e="",g="",h="",desde="", sal="",sal2="",cadena="";
             var caract =carac.toUpperCase().replace(/\s/g,'');//a mayusculas,reemplaza espacios   
             var z=(caract.search(/AA1Y|AA1F|AA1G|AA1H|AA1I|AA1J|AA1K|AA1L|AA1M|AA1N|AA1O|AA1P|AA1Q|AA1R|AA1S|AA1T|AA1U|AA1V|AA1W|AA1X|AA1Z|AA1A|AA1B|AA1C|AA1D|AA1E|AA2Y|AA2F|AA2G|AA2H|AA2I|AA2J|AA2K|AA2L|AA2M|AA2N|AA2O|AA2P|AA2Q|AA2R|AA2S|AA2T|AA2U|AA2V|AA2W|AA2X|AA2Z|AA2A|AA2B|AA2C|AA2D|AA2E|AA3Y|AA3F|AA3G|AA3H|AA3I|AA3J|AA3K|AA3L|AA3M|AA3N|AA3O|AA3P|AA3Q|AA3R|AA3S|AA3T|AA3U|AA3V|AA3W|AA3X|AA3Z|AA3A|AA3B|AA3C|AA3D|AA3E|AA4Y|AA4F|AA4G|AA4H|AA4I|AA4J|AA4K|AA4L|AA4M|AA4N|AA4O|AA4P|AA4Q|AA4R|AA4S|AA4T|AA4U|AA4V|AA4W|AA4X|AA4Z|AA4A|AA4B|AA4C|AA4D|AA4E|AA5Y|AA5F|AA5G|AA5H|AA5I|AA5J|AA5K|AA5L|AA5M|AA5N|AA5O|AA5P|AA5Q|AA5R|AA5S|AA5T|AA5U|AA5V|AA5W|AA5X|AA5Z|AA5A|AA5B|AA5C|AA5D|AA5E|AA6Y|AA6F|AA6G|AA6H|AA6I|AA6J|AA6K|AA6L|AA6M|AA6N|AA6O|AA6P|AA6Q|AA6R|AA6S|AA6T|AA6U|AA6V|AA6W|AA6X|AA6Z|AA6A|AA6B|AA6C|AA6D|AA6E|AA7Y|AA7F|AA7G|AA7H|AA7I|AA7J|AA7K|AA7L|AA7M|AA7N|AA7O|AA7P|AA7Q|AA7R|AA7S|AA7T|AA7U|AA7V|AA7W|AA7X|AA7Z|AA7A|AA7B|AA7C|AA7D|AA7E|AA8Y|AA8F|AA8G|AA8H|AA8I|AA8J|AA8K|AA8L|AA8M|AA8N|AA8O|AA8P|AA8Q|AA8R|AA8S|AA8T|AA8U|AA8V|AA8W|AA8X|AA8Z|AA8A|AA8B|AA8C|AA8D|AA8E|AA9Y|AA9F|AA9G|AA9H|AA9I|AA9J|AA9K|AA9L|AA9M|AA9N|AA9O|AA9P|AA9Q|AA9R|AA9S|AA9T|AA9U|AA9V|AA9W|AA9X|AA9Z|AA9A|AA9B|AA9C|AA9D|AA9E/i));
             
                codigo1 = buscaDigitos(caract);//busca 5 fecha 
                var n=(caract.search(/\d+/g));//d solo numeros
                var l=(caract.search(/\w+/g));//w alfanumericos

            if(codigo1!= undefined ){
                 var codigo = caract.split("-");//hasta el guion  
                 var data = codigo[0];
                 var num = codigo[1];
                 cadena=data+"-";
                 solonumeros=num;  
                 return [  solonumeros, cadena ];    
             }else
             if(z=='0'){
               var v = caract; 
               var data = v.substring(0,4);
               var num = v.substring(4); 
               cadena=data;
               solonumeros=num;  
               return [  solonumeros, cadena ];    
             }else
             //solo numeros
             if( n=='0'){      
               var v = caract; 
               var num = v.substring(0);
               var vacia="";  
               solonumeros=num; 
               return [ solonumeros, vacia ];    
             }else
             //letras al inicio 
             if(l=='0' && z!='0'&& n!='0'){
             //caract.match(/\d+/g).join('');
             l=caract.match(/\D+/g); //D acepta diferente de numeros
             cadena=l;
             num=caract.match(/\d+/g); //d acepta solo numeros
             solonumeros=num;
             return [ solonumeros, cadena ];       
             }//fin if
         }    
 


    function buscaDigitos(caract){
     
        var codigo = caract.split("-");//hasta el guion
        if(codigo[1]){
 
            return codigo;  
 
        }
    
    }
       </script>
        </tr>    

        <?php } while ($row_vista_faltantes = mysql_fetch_assoc($vista_faltantes)); ?>    

       <?php  }//fin total ?>    

        <?php 
             $caja = $row_numeracion['int_caja_tn'];
           }  ?>
      </table>
</body>
</html>
<script type="text/javascript">
 

</script>
<?php
mysql_free_result($usuario);
mysql_free_result($numeracion);


?>