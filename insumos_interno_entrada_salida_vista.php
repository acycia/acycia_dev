<?php
   require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
   require (ROOT_BBDD); 
?> 
<?php require_once('Connections/conexion1.php'); ?><?php
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

$colname_usuario = "1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
 
$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario); 

$colname_remision_id= "-1";
if (isset($_GET["id_remision"])) {
  $colname_remision_id = (get_magic_quotes_gpc()) ? $_GET["id_remision"] : addslashes($_GET["id_remision"]);
}

$row_existe = $conexion->buscar('tbl_remision_interna','id_remision',$colname_remision_id);
 
?>
<html>
<head>
  <title>SISADGE AC &amp; CIA</title>
<!--   <link href="css/vista.css" rel="stylesheet" type="text/css" /> -->
  <script type="text/javascript" src="js/formato.js"></script> 

 <!-- desde aqui para listados nuevos -->
   <link href="css/formato.css" rel="stylesheet" type="text/css" />
   <link rel="stylesheet" type="text/css" href="css/desplegable.css" />
   <link rel="stylesheet" type="text/css" href="css/general.css"/>
   <script type="text/javascript" src="AjaxControllers/js/consultas.js"></script>

    <script type="text/javascript" src="AjaxControllers/js/insert.js"></script>
    <script type="text/javascript" src="AjaxControllers/js/consultas.js"></script>
    <script type="text/javascript" src="AjaxControllers/js/delete.js"></script> 
   <!-- sweetalert -->
   <script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
   <link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">
   <!-- jquery -->
   <script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>
   <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
   <script src="//code.jquery.com/jquery-1.11.2.min.js"></script> 
   <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>


  <!-- css Bootstrap hace mas grande el formato-->
  <link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

</head>
<body>
  <div class="table-responsive"><!-- este define el fondo gris de lado a lado si se coloca dentro de tabla inicial solamente coloca borde gris -->
    <div class="table-responsive" align="center"> <!-- <div align="center"> -->
      <table style="width: 95%; height:100%;" > 
        <tr>
         <td> 
           <div  class="panel panel-primary">
                <div class="panel-heading" align="left" ></div><!--color azul-->
                <div class="row" >
                  <div class="span3">&nbsp;&nbsp;&nbsp; <img src="images/cabecera.jpg"></div> 
                  <div class="span3">
                      <img src="images/ciclo1.gif" alt="RESTAURAR" border="0" style="cursor:hand;" onClick="window.history.go()"/><img src="images/impresor.gif" onClick="window.print();" style="cursor:hand;" alt="IMPRIMIR" border="0" /><?php if($row_existe['id_remision']=='') { ?><a href="insumos_interno_entrada_salida_edit.php?id_remision=<?php echo $row_existe['id_remision']; ?>"><img src="images/menos.gif" alt="EDITAR" border="0" /></a><?php } else { ?><a href="insumos_interno_entrada_salida_edit.php?id_remision=<?php echo $_GET['id_remision']; ?>"><img src="images/menos.gif" alt="EDITAR" border="0" /></a><?php } ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </div>
                </div>
               
                <div class="panel-body">

                 <div align="center"> 
                  <table > 
                    <tr>
                      <td ><h2 style="text-align: right;" >REMISION <?php echo strtoupper($row_existe['entrada']); ?> </h2></td>
                    </tr> 
                  </table>
                </div> 

                <table class="table table-bordered table-sm">
                  <tr>
                    <td rowspan="4" align="center" ><img src="images/logoacyc.jpg"></td> 
                  </tr>  
                  <tr>
                    <td align="center" ><h3> N&deg; <?php echo $row_existe['id_remision']; ?> </h3></td>
                  </tr>
                  <tr>
                    <td align="center" >ALBERTO CADAVID R & CIA S.A.  Nit: 890915756-6<br>Carrera 45 No. 14 - 15  Tel: 604 311-21-44 Medellin-Colombia</td>
                  </tr>
                </table>
                <table class="table table-bordered table-sm">
                  <tr>
                    <td colspan="10" ><strong>CLIENTE: </strong><?php echo $row_existe['cliente']; ?></td> 
                  </tr>
                  <tr>
                    <td colspan="5" ><strong>NIT : </strong><?php echo $row_existe['documento']; ?></td>
                    <td colspan="5" ><strong>PAIS / CIUDAD : </strong><?php echo $row_existe['pais']; ?> / <?php echo $row_existe['ciudad_p']; ?></td>
                  </tr>
                  <tr>
                    <td colspan="10" ><strong>CONTACTO: </strong><?php echo $row_existe['contacto']; ?></td>
                  </tr> 
                  <hr>
                  <tr>
                    <td colspan="5" ><strong>TELEFONO : </strong><?php echo $row_existe['telefono']; ?></td>
                    <td colspan="5" ><strong>N° CELULAR: </strong><?php echo $row_existe['celular']; ?></td>
                  </tr> 
                  <tr> 
                    <td colspan="5" ><strong>FECHA ENTRADA:</strong><?php echo $row_existe['fecha']; ?> </td>
                    <td colspan="5" ><strong>FECHA SALIDA:</strong><?php echo $row_existe['fecha_salida']; ?>  </td> 
                  </tr>
                   <tr>
                      <td colspan="10">
                         <!-- grid --> 
                         <hr>
                         <table id="example" class="display" style="width:100%" border="1">
                           <thead>
                             <tr> 
                               <th style="text-align: center;" >DESCRIPCION</th>
                               <th>MEDIDA/AC</th>
                               <th>CANTIDAD</th>
                               <th>PESO</th>
                               <th>SUBTOTAL</th>   
                             </tr>
                           </thead>
                           <tbody id="DataResult"> 
                             
                           </tbody> 
                         </table>
                       

                    </td>
                  </tr>
            </table>
 
       <table class="table table-bordered table-sm"> 
        <tr>
           <td colspan="10"> 
            <strong >OBSERVACIONES:</strong>
            <textarea class="form-control" id="observacion" name="observacion" cols="50" rows="3"><?php echo $row_existe['observacion']; ?></textarea>
           </td>
         </tr>
        <tr style="text-align: center;" > 
          <td colspan="10" id="fondo2" ><b style="font-size:15px;" >Para mayor información al correo <span>despachos.bolsas@acycia.com</span> </b></td>
        </tr>
        <tr>
          <td ><strong >ELABORADO POR</strong> </td>
          <td ><strong >RECIBIDO POR</strong> </td>
          <td colspan="5"><strong >Firma y Sello Acycia</strong> </td>  
          </td>
        </tr>
        <tr>
          <td><?php echo $row_existe['elabora']; ?></td>
          <td><?php echo $row_existe['recibe']; ?></td>
          <td colspan="5"></td> 
        </tr>
      </table>
      <table class="table table-bordered table-sm">
        <tr>
          <td id="fondo1">CODIGO : A3 - F02</td>
          <td id="fondo3">VERSION : 2</td>
        </tr>
      </table>
 
  
          </div>
        </div> 
      </td>
    </tr>
  </table>
</div>
</div>
</body>
</html>

<script type="text/javascript">
 $(document).ready(function(){
    var id_remision = "<?php echo $_GET['id_remision']; ?>";
  consultasItemsVista(id_remision);//despliega los items
 
});
</script>
<?php
mysql_free_result($usuario);mysql_close($conexion1);

mysql_free_result($orden_compra);

mysql_free_result($proveedor_oc);

mysql_free_result($detalle);
?>