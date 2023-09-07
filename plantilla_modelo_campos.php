<?php
   require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
   require (ROOT_BBDD); 
?> 
  <?php
/*//initialize the session
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
  }*/
  ?>
<?php
$currentPage = $_SERVER["PHP_SELF"];

$conexion = new ApptivaDB();

require_once('Connections/conexion1.php');;
mysql_select_db($database_conexion1, $conexion1);

//$row_op = $conexion->llenarCampos("tbl_tiquete_numeracion","WHERE int_op_tn='4009' " , 'ORDER BY id_tn DESC LIMIT 1','ref_tn, int_cod_rev_tn'); 


$factura ="80124402"; 
//$row_fact = $conexion->llenarCampos('tbl_orden_compra','WHERE str_numero_oc = "8551PW"', 'ORDER BY id_pedido DESC','str_numero_oc, factura_oc,fecha_cierre_fac '); 

$row_fact = $conexion->llenarCampos("tbl_orden_compra", "WHERE factura_oc='".$factura."' ", " ", "str_numero_oc, factura_oc,fecha_cierre_fac ");

  
  
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
  <!-- sweetalert -->
  <script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
  <link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">
  <!-- jquery -->
  <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
  <script src="//code.jquery.com/jquery-1.11.2.min.js"></script> 
  <!-- select2 -->
  <link href="select2/css/select2.min.css" rel="stylesheet"/>
  <script src="select2/js/select2.min.js"></script>
  <!-- css Bootstrap-->
  <link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">  
</head>
<body>
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
                   <div class="span12"><h3> PROCESO DE COMPRAS  &nbsp;&nbsp;&nbsp; </h3></div>
                 </div>
                 <div class="panel-heading" align="left" ></div><!--color azul-->
                    <div id="cabezamenu">
                     <ul id="menuhorizontal">
                      <li id="nombreusuario" ><?php echo $_SESSION['Usuario']; ?></li>
                      <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                      <li><a href="menu.php">MENU PRINCIPAL</a></li> 
                      <li><a href="insumos.php">VER INSUMOS</a></li>
                      <li><a href="orden_compra.php">ORDENE COMPRA</a></li> 
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
                         MENU - ORDEN DE COMPRA
                       </td>
                     </tr> 
                 </table> 
               </div>
             </div>
             <br> 
          <br>
          <!-- grid --> 

          <div class="container-fluid">  
          
            <table class="table table-striped">
              <thead>
                <tr>
                  <th scope="col">ORDEN DE COMPRA</th>
                  <th scope="col">FACTURA</th> 
                </tr>
              </thead>
              <tbody >
                <tr>
                  <th scope="row"><input type="text" required="required" placeholder="Orden Compra" id="oc1" name="oc1" value=""  class='campostext' ></th>
                  <td><input type="text" required="required" placeholder="Factura" id="fac1" name="fac1" value=""  class='campostext' ></td> 
                </tr>
                <tr>
                  <th scope="row"><input type="text" required="required" placeholder="Orden Compra" id="oc2" name="oc2" value=""  class='campostext' ></th>
                  <td><input type="text" required="required" placeholder="Factura" id="fac2" name="fac2" value=""  class='campostext' ></td> 
                </tr>
                <tr>
                  <td><em> CREAR PROFORMA</em>&nbsp;&nbsp;&nbsp;&nbsp;<a href="?action=editar&id=<?php echo $r->id; ?>" style="text-decoration:none;" >CREAR PROFORMA >>></a></td>
                  <td> </td> 
                </tr>
                <tr>
                  <td><em> CREAR PROFORMA</em>&nbsp;&nbsp;&nbsp;&nbsp;<a href="?action=editar&id=<?php echo $r->id; ?>" style="text-decoration:none;" >CREAR PROFORMA >>></a></td>
                  <td> </td> 
                </tr>
                <tr>
                  <td> <a class="botonGeneral" style="text-decoration:none; "href="?id=<?php echo $dato['id_pedido']; ?>">SALIR</a> 
                    <button id="btnEnviarG" name="btnEnviarG" type="button" class="botonGeneral" autofocus="" >GUARDAR Y CONTINUAR</button> </td>
                  <td><button id="btnEnviarG" name="btnEnviarG" type="button" class="botonFinalizar" autofocus="" >FINALIZAR</button> </td>
                <td><a class="botonGeneral" href="?c=ocomercial&a=Crud&id=<?php echo $dato['id_pedido']; ?>">Editar</a> </td>
                <td><a class="botonFinalizar" href="?c=ocomercial&a=Eliminar&id=<?php echo $dato['id_pedido']; ?>">Eliminar</a>
                <a href="?action=editar&id=<?php echo $r->id; ?>" style="text-decoration:none;" >Editar</a>
              <a class="botonGMini" target="_blank"  href="view_index.php?c=ocomercial&a=Crud&id=<?php echo $row_orden_compra['id_pedido']; ?>&columna=id_pedido&tabla=tbl_orden_compra_historico">VER HISTORICO DE MODIFICACIONES</a> </td>
                </tr>
              </tbody>
            </table> 
            <div class="panel-footer" > 
               <!-- <a class="botonGeneral" href="?c=ocomercial&id=<?php echo $dato['id_pedido']; ?>">SALIR</a>  -->
               <a class="botonFinalizar" style="text-decoration:none; "href="?id=<?php echo $dato['id_pedido']; ?>">SALIR</a> 
              
            </div>
            </div> 
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
</html>
<script type="text/javascript">
  $(document).ready(function(){
 
   $(".buscar").change(function(){
       // form = $("#envio").serialize();
       //var name = document.getElementsByName("buscar")[0];
       var name = $(this).attr('name'); 
       var value = $(this).val();  
        url = '<?php echo BASE_URL; ?>'; 
        window.location.assign(url+'verificacion_insumo_listado.php?busqueda='+name+'&valor='+value)
    });
  });
  /*$("#ejemplo2").change(function(){   });*/
</script>