<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
?> 
<?php require_once('Connections/conexion1.php'); ?>
<?php
require_once 'Models/Msquimicaslist.php';
include('funciones/funciones_php.php');//SISTEMA RUW PARA LA BASE DE DATOS 
?>
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


$conexion = new ApptivaDB();


$row_codigo = $conexion->llenaListas('insumo',"WHERE estado_insumo ='0' AND quimicos='SUSTANCIAS QUIMICAS' ","ORDER BY CONVERT(codigo_insumo, SIGNED INTEGER) DESC","codigo_insumo");

$row_descripcion = $conexion->llenaListas('insumo',"WHERE estado_insumo ='0' AND quimicos='SUSTANCIAS QUIMICAS' ","ORDER BY descripcion_insumo ASC","id_insumo,descripcion_insumo"); 
 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>SISADGE AC &amp; CIA</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link rel="stylesheet" type="text/css" href="css/general.css"/>
  <link rel="stylesheet" type="text/css" href="css/formato.css"/>
  <link rel="stylesheet" type="text/css" href="css/desplegable.css" />
  <script type="text/javascript" src="js/usuario.js"></script>
  <script type="text/javascript" src="js/formato.js"></script>
  <script type="text/javascript" src="js/addCamposCompras.js"></script>
  <script type="text/javascript" src="AjaxControllers/js/consultas.js"></script>
  <script type="text/javascript" src="AjaxControllers/js/elimina.js"></script>
  <script type="text/javascript" src="AjaxControllers/js/actualiza.js"></script>
  <!-- sweetalert -->
  <script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
  <link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">
  <!-- jquery -->
  <script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
  <script src="//code.jquery.com/jquery-1.11.2.min.js"></script> 
  <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>

  <!-- select2 -->
  <link href="select2/css/select2.min.css" rel="stylesheet"/>
  <script src="select2/js/select2.min.js"></script>
  <link rel="stylesheet" type="text/css" href="css/general.css"/>

  <!-- css Bootstrap hace mas grande el formato-->
  <link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
  <script>
      $(document).ready(function() { $(".busqueda").select2(); });
  </script>
<form action="view_index.php?c=csquimicaslist&a=Guardar" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <div class="spiffy_content"> <!-- este define el fondo gris de lado a lado si se coloca dentro de tabla inicial solamente coloca borde gris -->
    <div align="center">
      <table style="width: 100%">
        <tr>
         <td align="center">
           <div class="row-fluid">
             <div class="span8 offset2"> <!--span8 offset2   esto da el tamaño pequeño -->
               <div class="panel panel-primary">
                <div class="panel-heading" align="left" ></div><!--color azul-->
                 <div class="row" >
                   <div class="span12">&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/cabecera.jpg"></div>
                   <div class="span12"><h3> INVENTARIO GENERAL DE SUSTANCIAS QUÍMICAS&nbsp;&nbsp;&nbsp; </h3></div>
                 </div>
                 <div class="panel-heading" align="left" ></div><!--color azul-->
                    <div id="cabezamenu">
                     <ul id="menuhorizontal">
                      <li id="nombreusuario" ><?php echo $_SESSION['Usuario']; ?></li>
                      <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                      <li><a href="menu.php">MENU PRINCIPAL</a></li>
                       
                    </ul>
                </div> 
               <div class="panel-body">
                 <br> 
                 <div ><!--  SI QUITO  class="container" SE ALINEA A LA IZQUIERDA TODO EL CONTENIDO DE ESTE -->
                  <div class="row">
                    <div class="span12">
                     <table id="tabla2">
                       <tr>
                        <td id="subtitulo">
                         LISTAR - SUSTANCIAS
                       </td>
                       <td id="subtitulo">VERSIÓN: 01 </td>
                       <td id="subtitulo">Fecha Actual: <?php echo $fechaActual = date('Y-m-d'); ?></td>
                       <td><img src="images/impresor.gif" onclick="window.print();" style="cursor:hand;" alt="INPRIMIR" title="INPRIMIR"></td>
                     </tr> 
                 </table> 
               </div>
             </div> 
             <br> 
                 <table >
                   <tr>
                     <td><form action="view_squimicaslist.php" method="get" name="consulta">
                       <?php $mes= $_GET['mes']!='' ? $_GET['mes'] : date('m'); ?>
                       <select name="mes" id="mes">
                         <option value="00"<?php if(!(strcmp("00", $mes))) {echo "selected=\"selected\"";} ?>>Mes</option>
                         <option value="01"<?php if(!(strcmp("01", $mes))) {echo "selected=\"selected\"";} ?>>ENERO</option>
                         <option value="02"<?php if(!(strcmp("02", $mes))) {echo "selected=\"selected\"";} ?>>FEBRERO</option>
                         <option value="03"<?php if(!(strcmp("03", $mes))) {echo "selected=\"selected\"";} ?>>MARZO</option>
                         <option value="04"<?php if(!(strcmp("04", $mes))) {echo "selected=\"selected\"";} ?>>ABRIL</option>
                         <option value="05"<?php if(!(strcmp("05", $mes))) {echo "selected=\"selected\"";} ?>>MAYO</option>
                         <option value="06"<?php if(!(strcmp("06", $mes))) {echo "selected=\"selected\"";} ?>>JUNIO</option>
                         <option value="07"<?php if(!(strcmp("07", $mes))) {echo "selected=\"selected\"";} ?>>JULIO</option>
                         <option value="08"<?php if(!(strcmp("08", $mes))) {echo "selected=\"selected\"";} ?>>AGOSTO</option>
                         <option value="09"<?php if(!(strcmp("09", $mes))) {echo "selected=\"selected\"";} ?>>SEPTIEMBRE</option>
                         <option value="10"<?php if(!(strcmp("10", $mes))) {echo "selected=\"selected\"";} ?>>OCTUBRE</option>
                         <option value="11"<?php if(!(strcmp("11", $mes))) {echo "selected=\"selected\"";} ?>>NOVIEMBRE</option>
                         <option value="12"<?php if(!(strcmp("12", $mes))) {echo "selected=\"selected\"";} ?>>DICIEMBRE</option>        
                       </select> 
                       <select name="codigo" id="codigo"  class="busqueda selectsMedio">
                         <option value="0"<?php if (!(strcmp(0, $_GET['codigo']))) {echo "selected=\"selected\"";} ?>>CODIGO</option>
                         <?php foreach ($row_codigo as $row_codigo) { ?>
                           <option value="<?php echo $row_codigo['codigo_insumo']?>"<?php if (!(strcmp($row_codigo['codigo_insumo'], $_GET['codigo']))) {echo "selected=\"selected\"";} ?>><?php echo $row_codigo['codigo_insumo'];?>
                         </option>
                       <?php } ?>
                     </select>
                     <select name="descrip" id="descrip"  class="busqueda selectsGrande">
                       <option value="0"<?php if (!(strcmp(0, $_GET['descrip']))) {echo "selected=\"selected\"";} ?>>DESCRIPCION</option>
                       <?php foreach ($row_descripcion as $row_descripcion) { ?>
                         <option value="<?php echo $row_descripcion['id_insumo']?>"<?php if (!(strcmp($row_descripcion['id_insumo'], $_GET['descrip']))) {echo "selected=\"selected\"";} ?>><?php echo $row_descripcion['descripcion_insumo'];?>
                       </option>
                     <?php } ?>
                   </select> 
                 <select name="quimicos" id="quimicos">
                  <option value="SUSTANCIAS QUIMICAS">SELECCIONE...</option>
                  <option value="SUSTANCIAS QUIMICAS">SUSTANCIAS QUIMICAS</option>
                  <option value="NA">NA</option>    
                </select>
                &nbsp;&nbsp; 
               <!--  <input type="submit" class="botonGMini" style='width:90px; height:25px' name="Submit" value="FILTRO" /> -->
              </form>
             </td>
             </tr>
             </table>
             <table class="table table-striped" id="items" >
     
             <hr> 
             <div style="text-align: left"> 
                <div class="row align-items-start">
                  <div style="width: 40px;"><strong></strong></div>
                  <div style="width: 170px;"><strong>AREA</strong></div> 
                  <div style="width: 70px;"><strong>CODIGO</strong></div> 
                  <div style="width: 350px;"><strong>NOMBRE DEL PRODUCTO</strong></div>
                  <div style="width: 350px;"><strong>PROVEEDOR</strong></div>
                  <!-- <div style="width: 120px;"><strong>CONSUMO % MES</strong></div>   -->
                  <div style="width: 120px;"><strong>CONSUMO MES</strong></div>
                  <div style="width: 120px;"><strong>FECHA</strong></div>
                  <div style="width: 120px;"><strong>QUIMICOS</strong></div>
                  <div style="width: 120px;"><strong>MEDIDA</strong></div> 
                  <div style="width: 100px;"><strong>STOCK</strong></div>  
                  
                </div> 
              <div class="divScrollGigante" id="itemspedido" role="alert" style="text-align: left;"> 
                     
                 <?php foreach($this->items as $items) { ?>
                <div id="dinamicos" class="row celdaborde1">
                  <div style="width: 40px;margin" id="fondo_2"> </div>
                  <div style="width: 170px;" id="fondo_2">
                   <?php 
                   $clase_insumo=$items['clase_insumo']=='' ? 0 : $items['clase_insumo'];
                   $numclase = $conexion->llenarCampos("clase", "WHERE id_clase=$clase_insumo ", " ", "*");
                   
                   if($numclase >='1')
                   { 
                     echo $clase = $numclase['nombre_clase'];    
                     
                   } 
                   ?>
                 </div>
                 <div style="width: 70px;" id="fondo_1">
                   
                   <?php echo $items['codigo_insumo']; ?> 
                   
                 </div> 
                 
                 <div style="width: 350px;" id="fondo_2">
                    
                    <?php echo $items["descripcion_insumo"]; ?> 

                 </div> 
                 <div style="width: 350px;" id="fondo_1">
                    
                   <?php  
                    $id_insumo=$items['id_insumo']=='' ? 0 : $items['id_insumo'];
                    $sqldato="SELECT proveedor.proveedor_p FROM TblProveedorInsumo, proveedor WHERE TblProveedorInsumo.id_in=$id_insumo AND TblProveedorInsumo.id_p=proveedor.id_p ORDER BY proveedor.id_p DESC ";
                    $resultdato=mysql_query($sqldato);
                    $row_proveedores = mysql_fetch_assoc($resultdato);
                    echo $proveedor_p=mysql_result($resultdato,0,'proveedor_p'); 
                    ?>
                     
                 </div>
                 <!-- <div style="width: 120px;" id="fondo_2">
                    
                    <?php echo $items["medida_insumo"]; ?> 

                 </div>  -->
                  <div style="width: 120px;" id="fondo_1">
                    

                    <?php  
                    $consultar = new oSquimicaslist();
                    $elmes = $mes;
                    $anyoActual = date("Y");
                    $id_rpp_rp=$items['id_insumo']=='' ? 0 : $items['id_insumo']; 
                    $consumomaterial = $consultar->multiConsulta("SELECT SUM(old.valor_prod_rp) AS consumo,old.fecha_rkp as fecha FROM  tbl_reg_kilo_producido old WHERE YEAR(old.fecha_rkp)='$anyoActual' AND MONTH(old.fecha_rkp)='$elmes' AND old.id_rpp_rp='$id_rpp_rp' ORDER BY old.fecha_rkp DESC ","");
                    
                    /*$desperdicio = $consultar->multiConsultaRow("SELECT old.op_rp as oprd FROM tbl_reg_kilo_producido old WHERE YEAR(old.fecha_rkp)='$anyoActual' AND old.id_rpp_rp='$id_rpp_rp' ","");
 
                      foreach ($desperdicio  as $value) { 
                         if($value['oprd']!=''){
                          
                         $consumodesperdicio = $consultar->multiConsulta("SELECT SUM(valor_desp_rd) as desperd FROM tbl_reg_desperdicio WHERE op_rd ='".$value['oprd']."' ",""); 
                         
                         $elconsumodes += $consumodesperdicio['desperd'] ; 
                         }
                      }
                       echo  $elconsumodes;*/
                       $elconsumo = $consumomaterial['consumo']=='' ? 0 : $consumomaterial['consumo']; 

                       echo number_format($elconsumo, 2, ',', '.'); 
                    ?>
                    
                 </div>
                 <div style="width: 120px;" id="fondo_2">
                     <?php echo $consumomaterial['fecha']; ?> 
                 </div>
                 <div style="width: 120px;" id="fondo_2">
                    
                    <?php echo $items["quimicos"]; ?> 
                    
                 </div>
                 <div style="width: 120px;" id="fondo_1">
                    
                    <?php 
                    $medida_insumo=$items['medida_insumo']=='' ? 0 : $items['medida_insumo'];
                    $numedida = $conexion->llenarCampos("medida", "WHERE id_medida=$medida_insumo ", " ", "*");
                    
                    if($numedida >='1') { 
                      echo $medida_insumo=$numedida['nombre_medida']; 
                    }
                    ?> 
                    
                 </div> 
                 <div style="width: 100px;" id="fondo_2">
                    
                    <?php echo $items['stok_insumo']; ?>
                    
                 </div>     
               </div>
                <?php  } ?>
           
                
          </div> 
             <br> 
             <div class="panel-footer" > 
              VERSIÓN: 01
                <!-- <a class="botonGeneral" href="?c=ocomercial&id=<?php echo $items['id_pedido']; ?>">SALIR</a>  -->
                <a class="botonFinalizar" style="text-decoration:none; "href="javascript:Salir('view_index.php?c=csquimicaslist&a=Menu')" >SALIR</a>  
               
             </div>
         

   </div> <!-- contenedor -->

 </div>
</div>
</div>
</div>
</td>
</tr>
</table>
</div> 
</div>

 
</body>
</form>
</html>

<script type="text/javascript">
  //bloquea envio del formulario con enter 
    document.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('input[type=text]').forEach( node => node.addEventListener('keypress', e => {
        if(e.keyCode == 13) {
          e.preventDefault();
        }
      }))
    });
 
  var mes = $( "#mes" ).val();
 
  $( "#codigo" ).on( "change", function() {  
       codigo = $( "#codigo" ).val();
       var vista = "view_squimicaslist.php"   
       window.location="view_index.php?c=csquimicaslist&a=Busqueda&columna=codigo_insumo&id="+codigo+"&vista="+vista+"&mes="+mes;
       $('#mensaje').hide(); 
       if(idautorizacion){ 
         $('#mensaje').show(); 
         $("#mensaje").text('Buscando autorizacion... !');  
       }
  });

  $( "#descrip" ).on( "change", function() {  
       descrip = $( "#descrip" ).val();
       var vista = "view_squimicaslist.php"   
       window.location="view_index.php?c=csquimicaslist&a=Busqueda&columna=id_insumo&id="+descrip+"&vista="+vista+"&mes="+mes;
       $('#mensaje').hide(); 
       if(idfacturabusqueda){ 
         $('#mensaje').show(); 
         $("#mensaje").text('Buscando facturabusqueda... !');  
       }
  });
 

 $( "#quimicos" ).on( "change", function() { 
      quimicos = $( "#quimicos" ).val();
      var vista = "view_squimicaslist.php"   
      window.location="view_index.php?c=csquimicaslist&a=Busqueda&columna=quimicos&id="+quimicos+"&vista="+vista+"&mes="+mes;
      $('#mensaje').hide(); 
      if(idfechabusqueda){ 
        $('#mensaje').show(); 
        $("#mensaje").text('Buscando fechabusqueda... !');  
      }
 });

 $( "#mes" ).on( "change", function() { 
      quimicos = $( "#quimicos" ).val();
      var mes = $( "#mes" ).val();
      var vista = "view_squimicaslist.php"   
      window.location="view_index.php?c=csquimicaslist&a=Busqueda&columna=quimicos&id="+quimicos+"&vista="+vista+"&mes="+mes;
      $('#mensaje').hide(); 
      if(idfechabusqueda){ 
        $('#mensaje').show(); 
        $("#mensaje").text('Buscando fechabusqueda... !');  
      }
 });
 
</script>
 