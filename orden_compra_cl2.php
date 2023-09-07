<?php
   require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
   require (ROOT_BBDD); 
?> 
<?php require_once('Connections/conexion1.php'); ?>
<?php require_once('funciones/funciones_php.php'); ?>
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
  $currentPage = $_SERVER["PHP_SELF"];

  $conexion = new ApptivaDB();

  $maxRows_ordenes_compra = 15;
  $pageNum_ordenes_compra = 0;
  if (isset($_GET['pageNum_ordenes_compra'])) {
    $pageNum_ordenes_compra = $_GET['pageNum_ordenes_compra'];
  }
  $startRow_ordenes_compra = $pageNum_ordenes_compra * $maxRows_ordenes_compra;

 

  if($_GET['listar']==''){
    $listar="fecha_ingreso_oc DESC";
  }else{
    $listar=$_GET['listar'];
  }
  
  if(!$_SESSION['acceso'] && !$_SESSION['restriUsuarios']){
     
     $soloinventario = "(Tbl_orden_compra.tipo_despacho is null or Tbl_orden_compra.tipo_despacho ='despacho') and ";

  }

  mysql_select_db($database_conexion1, $conexion1);

  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra WHERE $soloinventario  b_borrado_oc='0' AND  pago_pendiente <> 'SI' ORDER BY  $listar,   str_numero_oc DESC";
  $query_limit_ordenes_compra = sprintf("%s LIMIT %d, %d", $query_ordenes_compra, $startRow_ordenes_compra, $maxRows_ordenes_compra);
  $ordenes_compra = mysql_query($query_limit_ordenes_compra, $conexion1)  or die(mysql_error());
  $row_ordenes_compra = mysql_fetch_assoc($ordenes_compra); 

  $query_ordenes_deudoras = "SELECT * FROM Tbl_orden_compra WHERE $soloinventario  b_borrado_oc='0' AND  pago_pendiente = 'SI' ORDER BY  $listar,   str_numero_oc DESC"; 
  $ordenes_deudoras = mysql_query($query_ordenes_deudoras, $conexion1)  or die(mysql_error());
  $row_ordenes_deudoras = mysql_fetch_assoc($ordenes_deudoras); 


  if (isset($_GET['totalRows_ordenes_compra'])) {
    $totalRows_ordenes_compra = $_GET['totalRows_ordenes_compra'];
  } else {
    $all_ordenes_compra = mysql_query($query_ordenes_compra) or die(mysql_error());
    $totalRows_ordenes_compra = mysql_num_rows($all_ordenes_compra);
  }
  $totalPages_ordenes_compra = ceil($totalRows_ordenes_compra/$maxRows_ordenes_compra)-1;


/*  $row_lista = $conexion->llenaListas('tbl_orden_compra',"WHERE b_borrado_oc='0' AND fecha_ingreso_oc > '2020-01-01' ","ORDER BY fecha_ingreso_oc DESC",'str_numero_oc');  
 
  $row_numero = $conexion->llenaListas('tbl_referencia'," WHERE estado_ref='1'",'ORDER BY CONVERT(cod_ref, SIGNED INTEGER) DESC','cod_ref'); 

  $row_proveedores = $conexion->llenaListas('cliente',"",' ORDER BY CONVERT(nombre_c, SIGNED INTEGER) ASC ','id_c,nombre_c,nit_c');

  $row_nit = $conexion->llenaListas('cliente',"",' ORDER BY CONVERT(nit_c, SIGNED INTEGER) DESC ','nit_c');

  $row_factura = $conexion->llenaListas('tbl_orden_compra',"WHERE factura_oc <>''",'ORDER BY fecha_ingreso_oc DESC','factura_oc');
*/
  $row_elaborado = $conexion->llenaListas('vendedor',"",'ORDER BY nombre_vendedor ASC','nombre_vendedor');

  $row_vendedores = $conexion->llenaListas('vendedor',"",'ORDER BY nombre_vendedor ASC','id_vendedor,nombre_vendedor');

 

/*  mysql_select_db($database_conexion1, $conexion1);
  $query_proveedores = "SELECT * FROM cliente ORDER BY nombre_c ASC";
  $proveedores = mysql_query($query_proveedores, $conexion1)  or die(mysql_error());
  $row_proveedores = mysql_fetch_assoc($proveedores);
  $totalRows_proveedores = mysql_num_rows($proveedores);*/

/*  mysql_select_db($database_conexion1, $conexion1);
  $query_ano = "SELECT * FROM cliente ORDER BY nit_c DESC";
  $ano = mysql_query($query_ano, $conexion1)  or die(mysql_error());
  $row_ano = mysql_fetch_assoc($ano);
  $totalRows_ano = mysql_num_rows($ano);*/

/*  mysql_select_db($database_conexion1, $conexion1);
  $query_factura = "SELECT factura_oc FROM tbl_orden_compra WHERE factura_oc <>'' ORDER BY fecha_ingreso_oc DESC";
  $factura = mysql_query($query_factura, $conexion1)  or die(mysql_error());
  $row_factura = mysql_fetch_assoc($factura);
  $totalRows_factura = mysql_num_rows($factura);*/

/* mysql_select_db($database_conexion1, $conexion1);
 $query_numero = "SELECT * FROM Tbl_referencia  WHERE estado_ref='1' ORDER BY CONVERT(cod_ref, SIGNED INTEGER)  DESC";
 $numero = mysql_query($query_numero, $conexion1)  or die(mysql_error());
 $row_numero = mysql_fetch_assoc($numero);
 $totalRows_numero = mysql_num_rows($numero);*/

 //IMRPIME EL NOMBRE DEL VENDEDOR
/* mysql_select_db($database_conexion1, $conexion1);
 $query_vendedores = "SELECT * FROM vendedor ORDER BY nombre_vendedor ASC";
 $vendedores = mysql_query($query_vendedores, $conexion1)  or die(mysql_error());
 $row_vendedores = mysql_fetch_assoc($vendedores);
 $totalRows_vendedores = mysql_num_rows($vendedores);*/

  $queryString_ordenes_compra = "";
  if (!empty($_SERVER['QUERY_STRING'])) {
    $params = explode("&", $_SERVER['QUERY_STRING']);
    $newParams = array();
    foreach ($params as $param) {
      if (stristr($param, "pageNum_ordenes_compra") == false && 
        stristr($param, "totalRows_ordenes_compra") == false) {
        array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_ordenes_compra = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_ordenes_compra = sprintf("&totalRows_ordenes_compra=%d%s", $totalRows_ordenes_compra, $queryString_ordenes_compra);


?>
<html>
<head>
  <title>SISADGE AC &amp; CIA</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <link href="css/formato.css" rel="stylesheet" type="text/css" />
  <link href="css/general.css" rel="stylesheet" type="text/css" />
  
  <script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
  <link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">
  <script src="//code.jquery.com/jquery-1.11.2.min.js"></script> 
  <script type="text/javascript" src="AjaxControllers/js/updates.js"></script>
  <script type="text/javascript" src="AjaxControllers/envioListados.js"></script>
  <script type="text/javascript" src="AjaxControllers/updateAutorizar.js"></script>
  <script type="text/javascript" src="AjaxControllers/js/consultas.js"></script>
  <script type="text/javascript" src="js/general.js"></script> 
  <script type="text/javascript" src="AjaxControllers/js/actualiza.js"></script>

  <link href="css/formato.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="js/listado.js"></script>
  <script type="text/javascript" src="js/formato.js"></script>

  <!-- desde aqui para listados nuevos -->

    <link rel="stylesheet" type="text/css" href="css/desplegable.css" /> 

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
    <!-- css Bootstrap-->
    <link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <!-- Select3 Nuevo -->
    <meta charset="UTF-8">
    <!-- jQuery -->
    <script src='select3/assets/js/jquery-3.4.1.min.js' type='text/javascript'></script>

    <!-- select2 css -->
    <link href='select3/assets/plugin/select2/dist/css/select2.min.css' rel='stylesheet' type='text/css'>

    <!-- select2 script -->
    <script src='select3/assets/plugin/select2/dist/js/select2.min.js'></script>
    <!-- Styles -->
    <link rel="stylesheet" href="select3/assets/css/style.css">
    <!-- Fin Select3 Nuevo -->

</head>
<body onload = "JavaScript: AutoRefresh (150000);">
    <script>
        $(document).ready(function() { $(".combos").select2(); });
    </script>

    <div align="center">
      <table style="width: 80%"><!-- id="tabla1" -->
        <tr>
         <td align="center">
           <div class="row-fluid">
             <div class="span8 offset2"> <!--span8 offset2   esto da el tamaño pequeño -->
               <div class="panel panel-primary ">
                <div class="panel-heading" align="left" ></div><!--color azul-->
                <div class="row" >
                    <div class="span12">&nbsp;&nbsp;&nbsp; <img src="images/cabecera.jpg"></div> 
                     <h3> ORDENES DE COMPRA</h3> 
                </div>
                <div class="panel-heading" align="left" ></div><!--color azul-->
                 <div id="cabezamenu">
                  <ul id="menuhorizontal">
                    <li id="nombreusuario" ><?php echo $_SESSION['Usuario']; ?></li>
                    <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                    <li><a href="menu.php">MENU PRINCIPAL</a></li>
                    <li><a href="orden_compra_cl_reasig_oc.php">REASIGNAR OC</a></li>
                    <li><a href="despacho_listado1_oc.php">LISTADO REMISIONES</a></li>
                    <li><a href="ticket.php">ETIQUETAS</a></li> 
                  </ul>
               </div> 
  <div class="panel-body"> 
     <div >  <!--  class="container" si lo quito se amplia todo el listado-->
          <div class="row">
                <div class="span12"> </div>
                     </div> 
               <br>
          <!-- grid --> 
                

                <form action="orden_compra1_cl2.php" method="get" name="consulta">
                
                  <table class="table table-bordered table-sm">
                   <thead> 
                    <tr>
                      <td>
                        <div class="main"> 
                           <select id='str_numero_oc' name='str_numero_oc' style="width:150px">
                             <option value='0'<?php if (!(strcmp(0, $_GET['str_numero_oc']))) {echo "selected=\"selected\"";} ?>>- O.C. -</option>
                           </select>
                        </div> 
                      </td>
                      <td> 
                        <select class="combos" name="elaborador" id="elaborador" style="width:120px">
                          <option value="0">Elaborador</option>
                          <?php foreach ($row_elaborado as $row_elaborado) { ?>
                             <option value="<?php echo $row_elaborado['nombre_vendedor']?>"><?php echo $row_elaborado['nombre_vendedor']?></option>
                            <?php } ?>
                        </select> 

                      </td>
                      <td>  
                         <select class="combos" name="vendedor" id="vendedor" style="width:120px">
                          <option value="0">Vendedor</option>
                          <?php foreach ($row_vendedores as $row_vendedores) { ?>
                            <option value="<?php echo $row_vendedores['id_vendedor']?>"><?php echo $row_vendedores['nombre_vendedor']?></option>
                            <?php } ?>
                        </select> 
                      </td>
                      <td>
                        <div class="main"> 
                           <select id='id_c' name='id_c' style="width:300px">
                             <option value='0'<?php if (!(strcmp(0, $_GET['id_c']))) {echo "selected=\"selected\"";} ?>>- CLIENTE -</option>
                           </select>
                        </div>  
                      </td>
                      <td>
                        <div class="main"> 
                           <select id='nit_c' name='nit_c' style="width:200px">
                             <option value='0'<?php if (!(strcmp(0, $_GET['nit_c']))) {echo "selected=\"selected\"";} ?>>- NIT -</option>
                           </select>
                        </div> 
                      </td>
                      <td> 
                        <select class="combos" name="estado_oc" id="estado_oc" style="width:150px">
                          <option value="0">Estado O.C</option>
                          <option value="1">INGRESADA</option>
                          <option value="2">PROGRAMADA</option>
                          <option value="3">REMISIONADA</option>
                          <option value="4">FAC.PARCIAL</option>
                          <option value="5">FAC.TOTAL</option>
                        </select> 
                      </td>
                      <td>
                        <select class="combos" name="pendiente" id="pendiente" style="width:150px">
                          <option value="0">Seleccione</option>
                          <option value="=">COMPLETOS</option>
                          <option value=">">PENDIENTES</option> 
                        </select>
                      </td> 
                      </tr>



                      <tr>
                      <td>
                        <div class="main"> 
                           <select id='cod_ref' name='cod_ref' style="width:150px">
                             <option value='0'<?php if (!(strcmp(0, $_GET['cod_ref']))) {echo "selected=\"selected\"";} ?>>- REF -</option>
                           </select>
                        </div> 
                      </td>
                      <td>
                        <select class="combos" name="tbpw" id="tbpw" style="width:150px">
                          <option value="0">Seleccione TB/PW</option>
                          <option value="TB">TB</option>
                          <option value="PW">PW</option> 
                        </select>
                      </td>
                      <td>
                        <select class="combos" name="factura" id="factura" style="width:150px">
                          <option value="0">Factura oc</option>
                          <option value="1">con factura</option>
                          <option value="2">sin factura</option> 
                        </select>
                      </td>
                      <td>
                        <div class="main"> 
                           <select id='nfactura' name='nfactura' style="width:150px">
                             <option value='0'<?php if (!(strcmp(0, $_GET['nfactura']))) {echo "selected=\"selected\"";} ?>>- # Factura -</option>
                           </select>
                        </div> 
                      </td>
                      <td>
                        <select class="combos" name="autorizado" id="autorizado" style="width:150px">
                          <option value="0">Autorizado</option>
                          <option value="SI">SI</option>
                          <option value="NO">NO</option> 
                        </select> 
                      </td>
                      <td>
                        FECHA INICIO:
                            <input name="fecha_ini" type="date" id="fecha_ini" min="2000-01-02" size="10" value=""/>
                      </td>
                      <td>
                        FECHA FIN:
                            <input name="fecha_fin" type="date" id="fecha_fin" min="2000-01-02" size="10" value=""/> 
                      </td> 
                     </tr> 
                     <tr>
                       <td colspan="12" style="text-align: center;" >
                           <input type="submit" class="botonGMini" name="Submit" value="FILTRAR" onClick="if(consulta.str_numero_oc.value=='0' && consulta.id_c.value=='0' && consulta.nit_c.value=='0' && consulta.estado_oc.value=='0' && consulta.pendiente.value=='0' &&  cod_ref=='0') { alert('DEBE SELECCIONAR UNA OPCION'); }"/> 
                          <button type="button" class="botonDel"  onclick="envioListados();" >Descarga Excel</button>                         
                       </td> 
                     </tr>
                      <!-- <tr>
                        <td id="dato1">
                          <img src="images/falta.gif" alt="INGRESADA x O.C." title="INGRESADA O.C." border="0" style="cursor:hand;"/>ingresada
                          <img src="images/f.gif" alt="FACTURADA O.C." title="FACTURADA O.C." border="0" style="cursor:hand;"/> facturado total
                          <img src="images/fr.gif" alt="FACTURADA PARCIAL" title="FACTURADA PARCIAL" border="0" style="cursor:hand;"/> factura parcial
                          <img src="images/r.gif" alt="REMISION O.C." title="REMISION O.C." border="0" style="cursor:hand;"/> remisionada    
                        </td>
                          <td id="dato1">
                            <img src="images/p.gif" alt="PROGRAMADA O.C." title="PROGRAMADA O.C." border="0" style="cursor:hand;"/> programada
                            <img src="images/pa.gif" alt="PROGRAMADA O.C." title="PROGRAMADA O.C." border="0" style="cursor:hand;"/> En produccion
                            <img src="images/falta3.gif" alt="PENDIENTES" width="20" height="18" style="cursor:hand;" title="PENDIENTES" border="0"/> cantidades pendientes por despachar
                            <img src="images/cumple.gif" alt="OK" width="20" height="18" style="cursor:hand;" title="OK" border="0"/> cantidades despachadas en su totalidad
                          </td>
                          <td id="dato1">
                              <img src="images/accept.png" alt="AUTORIZADA" title="AUTORIZADA" border="0" style="cursor:hand;" width="20" height="18" />Orden Autorizada 
                              <img src="images/salir.gif" alt="SIN AUTORIZAR" title="SIN AUTORIZAR" border="0" style="cursor:hand;" width="20" height="18" />Orden Sin autorizar 
                              <img src="images/facturado.png" alt="YA TIENE FACTURA" title="YA TIENE FACTURA" border="0" style="cursor:hand;" width="20" height="18" />Ya Tiene Factura
                              <img src="images/falta8.gif" alt="SIN AUTORIZAR" title="SIN AUTORIZAR" border="0" style="cursor:hand;" width="20" height="18" />Sin Factura
                            </td>
                          </tr> -->
                          
                          <tr>
                            <td colspan="25" id="dato1">Nota: es importante que el usuario este registrado como vendedor para poder hacer el filtro de vendedor correctamente <br>
                              <?php if( $_SESSION['restriUsuarios'] ): ?>

                                 <button type="button" class="botonGMini" target="_top" onclick="envioListadosPrecio();" >Excel ref Precio</button> 
                              <?php endif; ?>
                            </td>
                          </tr>
                          </thead> 
                        </table>
                      </form>
                      <br> 

          
                    <?php if($row_ordenes_deudoras['id_pedido'] ): ?>
                    <fieldset> <legend id="dato1">ORDENES DE COMPRA QUE DEBE PROFORMA</legend>
                      <table class="table table-bordered table-sm">
                         <thead>
                           <tr class="table-danger"> 
                             <td nowrap="nowrap" id="titulo4"># O.C</td>
                             <td nowrap="nowrap" id="titulo4">INGRESO</td>
                             <td nowrap="nowrap" id="titulo4">CLIENTE</td>
                             <td nowrap="nowrap" id="titulo4">ELABORADOR</td>
                             <td nowrap="nowrap" id="titulo4">VENDEDOR</td>
                             <td nowrap="nowrap" id="titulo4">CANTIDAD</td> 
                             <td nowrap="nowrap" id="titulo4">$ CARTERA</td>
                             <td nowrap="nowrap" id="titulo4">PROFORMA</td>
                             <td nowrap="nowrap" id="titulo4">ADDPROF</td>
                             <td nowrap="nowrap" id="titulo4">PAGO?</td> 
                         </tr>
                         </thead>
                         <tbody>
                         <?php do { ?>
                           <tr onMouseOver="uno(this,'8C8C9F');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF"> 
                           <td id="dato1" nowrap><a href="orden_compra_cl_edit.php?str_numero_oc=<?php echo $row_ordenes_deudoras['str_numero_oc'];?>&id_oc=<?php echo $row_ordenes_deudoras['id_c_oc'];?>" target="_top" style="text-decoration:none; color:#000000"><strong><?php echo $row_ordenes_deudoras['str_numero_oc']; ?></strong></a></td>
                           <td id="dato2" nowrap><a href="orden_compra_cl_edit.php?str_numero_oc=<?php echo $row_ordenes_deudoras['str_numero_oc'];?>&id_oc=<?php echo $row_ordenes_deudoras['id_c_oc'];?>" target="_top" style="text-decoration:none; color:#000000"><strong><?php echo $row_ordenes_deudoras['fecha_ingreso_oc']; ?> </strong></a>
                          </td>
                          <td id="dato1" nowrap>
                            <a href="orden_compra_cl_edit.php?str_numero_oc=<?php echo $row_ordenes_deudoras['str_numero_oc']; ?>&id_oc=<?php echo $row_ordenes_deudoras['id_c_oc'];?>" target="_top" style="text-decoration:none; color:#000000">
                            <?php 
                            $id_c_oc=$row_ordenes_deudoras['id_c_oc'];
                            $sqln="SELECT nombre_c FROM cliente WHERE id_c='$id_c_oc'"; 
                            $resultn=mysql_query($sqln); 
                            $numn=mysql_num_rows($resultn); 
                            if($numn >= '1') 
                            { 
                             $nit_cliente_c=mysql_result($resultn,0,'nombre_c'); echo  ($nit_cliente_c); 
                           }
                           ?>
                         </a></td>
                         <td id="dato1" nowrap><a href="orden_compra_cl_edit.php?str_numero_oc=<?php echo $row_ordenes_deudoras['str_numero_oc'];?>&id_oc=<?php echo $row_ordenes_deudoras['id_c_oc'];?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_ordenes_deudoras['str_elaboro_oc']; ?></a>
                         </td>
                         <td id="dato1" nowrap><a href="orden_compra_cl_edit.php?str_numero_oc=<?php echo $row_ordenes_deudoras['str_numero_oc'];?>&id_oc=<?php echo $row_ordenes_deudoras['id_c_oc'];?>" target="_top" style="text-decoration:none; color:#000000">
                           <?php 
                           $idoc = $row_ordenes_deudoras['str_numero_oc'];
                           $select_direccion = $conexion->llenaListas('vendedor ver',"left join tbl_items_ordenc itm on  ver.id_vendedor=itm.int_vendedor_io WHERE itm.str_numero_io= '$idoc'","","distinct ver.nombre_vendedor");
                           foreach($select_direccion as $row_direccion) { 
                             echo $row_direccion['nombre_vendedor']." ";
                           } 
                           ?> 
                         </a>
                       </td>
                       <td id="dato1" nowrap>
                        <?php 
                        $id_pedido=$row_ordenes_deudoras['id_pedido'];
                        $sqlCAN="SELECT SUM(int_cantidad_io) AS int_cantidad_io FROM Tbl_items_ordenc WHERE id_pedido_io='$id_pedido'"; 
                        $resultCAN=mysql_query($sqlCAN); 
                        $numCAN=mysql_num_rows($resultCAN); 
                        if($numCAN >= '1') 
                        { 
                          $int_cantidad_io=mysql_result($resultCAN,0,'int_cantidad_io'); echo round($int_cantidad_io); 
                        }
                        ?>

                      </td> 
                      <td id="dato2">
                          <?php echo redondear_decimal_operar($row_ordenes_deudoras['valor_cartera']) ;?> 
                      </td>
 
                    <td id="dato1" nowrap>
                      <?php  
                      if($row_ordenes_deudoras['proforma_oc']!='0' && $row_ordenes_deudoras['proforma_oc']!=''){
                          $datosPR = substr($row_ordenes_deudoras['proforma_oc'], 0, 2);  
                         
                         $variasFacArray = array();
                        if($datosPR=="PRF-"){ 
                          //$variasFacArray[] = $row_ordenes_deudoras['proforma_oc'];
                           $variasFacArray=( explode(',', $row_ordenes_deudoras['proforma_oc']) );
                           foreach ($variasFacArray as $key => $value) {
                             $conceros = $value ;
                            ?>  
                            <a href="javascript:verFoto('PDF_PRF/<?php echo $conceros ;?>.pdf','610','490')">  <?php echo $conceros.'<br>'; ?> </a> 
                            <?php
                           }  

                          }
                          if($datosPR!="PRF-"){
                          
                             $digito = "PRF-";
                             $proformaCompleto = $row_ordenes_deudoras['proforma_oc'];
                             if($proformaCompleto!='' || $proformaCompleto!= null){ 
                             //$conceros = $digito.(str_pad($proformaCompleto, 7, "0", STR_PAD_LEFT)); 
                             $conceros = $digito.$proformaCompleto;
                             ?>  
                             <a href="javascript:verFoto('PDF_PRF/<?php echo $conceros ;?>.pdf','610','490')">  <?php echo $conceros.'<br>'; ?> </a> 
                             <?php  
                             }else{
                               $conceros = '';   
                             } 
                           }  

                         }

                     ?> 
                    </td>
                    <?php if($_SESSION['restriUsuarios']): ?>
                    <td id="fuente2"> 
                       <a href="javascript:updateListProf('id_proforma',<?php echo $row_ordenes_deudoras['id_pedido']; ?>,'orden_compra_cl2.php')" >
                         <?php
                         /*$id_oc=$row_ordenes_deudoras['str_numero_oc'];  
                         $sqldato="SELECT factura_r FROM Tbl_remisiones WHERE str_numero_oc_r='$id_oc'";
                         $resultdato=mysql_query($sqldato);
                         $factura_r=mysql_result($resultdato,0,'factura_r');*/

                         if(/*($factura_r=='' || $factura_r=='0') && */($row_ordenes_deudoras['proforma_oc']=='' || $row_ordenes_deudoras['proforma_oc']=='0')): ?>
                             <img src="images/falta8.gif" alt="SIN PROFORMA" title="SIN PROFORMA" border="0" style="cursor:hand;" width="20" height="18" /> 
                               <?php else: ?>
                             <img src="images/facturado.png" alt="YA TIENE PROFORMA" title="YA TIENE PROFORMA" border="0" style="cursor:hand;" width="20" height="18" />
                           <?php endif; ?>
                           </a> 
                    </td> 
                       <div style="display: none;  align-items: center; justify-content: center; " id="resp"> <b style="color: red;" >Actualizando... Numero de Proforma!</b>
                       </div>
                   <?php endif; ?>
                    <td id="dato2">
                     <?php if( $_SESSION['restriUsuarios'] ): ?>
                      <a class="botonUpdateMini" id="btnDelItems" onclick='uPDATE("id_pedido","1","1", "<?php echo $row_ordenes_deudoras['id_pedido']; ?>", "view_index.php?c=comercialList&a=Actualizar")' type="button" >PAGO?</a>
                      <?php endif; ?>
                    </td>
                    </tr>
                       <?php } while ($row_ordenes_deudoras = mysql_fetch_assoc($ordenes_deudoras)); ?>
                    </tbody>
                 </table>
              </fieldset>
            <?php endif; ?>
              
                    <fieldset>
                      <form action="delete_listado.php" method="get" name="seleccion">
                        <table class="table table-bordered table-sm">
                         <thead>
                          <tr>
                            <td colspan="2" id="dato1"><input name="borrado" type="hidden" id="borrado" value="22" />
                              <?php if($_SESSION['superacceso']): ?><input name="Input" type="submit" value="Delete"/><?php endif; ?>  </td>
                              <td colspan="3"><?php if (isset($_GET['id'])){$id=$_GET['id'];} else {
                                $id = "";} 
                                if($id == '2') { ?> 
                                <div id="numero1"> <?php echo "NO SE PUEDE ELIMINAR PORQUE TIENE REMISIONES CREADAS O ESTA EN PRODUCCION"; ?> </div>
                                <?php } 
                                if($id == '1') { ?> 
                                <div id="acceso1"> <?php echo "ELIMINACION COMPLETA"; ?> </div>
                                <?php }
                                if($id == '0') { ?>
                                <div id="numero1"> <?php echo "SELECCIONE PARA ELIMINAR"; ?> </div>  
                                <?php }      
                                ?></td>
                                <td colspan="3" id="dato2">
                                  <a href="orden_compra_cl_add.php"><img src="images/mas.gif" alt="ADD ORDEN DE COMPRA" title="ADD ORDEN DE COMPRA" border="0" style="cursor:hand;"/></a><a href="orden_compra_cl2.php"><img src="images/ciclo1.gif" alt="RESTAURAR" border="0" style="cursor:hand;"/></a><a href="insumos.php"><!--<img src="images/i.gif" style="cursor:hand;" alt="INSUMOS" border="0"/>--></a>
                                  <!-- <a href="javascript:updateGeneral('facturar','array1','array2','orden_compra_cl2.php')" ><img src="images/updatedb.png" alt="ACTUALIZAR # FACTURA" border="0" style="cursor:hand;"/></a> -->

                                  <a href="javascript:verFoto('facturas_update.php?facturar=1','870','810')"><img src="images/updatedb.png" alt="ACTUALIZAR # FACTURA"title="ACTUALIZAR # FACTURA" border="0" style="cursor:hand;" /></a> 


                                </td>
                              </tr> 

                              <tr class="table-primary">
                                <td id="fuente2"><!-- <input name="chulo1" type="checkbox" onClick="if(seleccion.chulo1.checked) { seleccionar_todo() } else{ deseleccionar_todo() } "/> --></td>
                                <td id="titulo4">N&deg;</td>
                                <td id="titulo4">INGRESO</td>
                                <td id="titulo4">CLIENTE</td>
                                <td id="titulo4">ELABORADOR</td>
                                <td id="titulo4">VENDEDOR</td>
                                <td id="titulo4">CANTIDAD</td>
                                <td id="titulo4">FACTURA</td>
                                <td nowrap="nowrap" id="titulo4">$ CARTERA</td>
                                <td id="titulo4">VENCIDA</td>
                                <td id="titulo4">PENDIENTE</td>
                                <td id="titulo4"><a href="verificaciones_criticos.php"><!--<img src="images/v.gif" alt="VERIFICACIONES (CRITICOS)" border="0" style="cursor:hand;"/>--></a><a href="orden_compra_cl2.php?listar=<?php echo "b_estado_oc ASC";?>">ESTADO</a></td>
                                <?php if($_SESSION['acceso']): ?><td id="titulo4" nowrap>ADDFACT</td> 
                                <?php endif; ?>
                                <td id="titulo4" nowrap="nowrap" >AUTORIZAR SALIDA</td>
                              </tr>
                              </thead>
                              <tbody>
                              <?php do { ?>
                              <tr onMouseOver="uno(this,'8C8C9F');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
                                <td id="dato2"><?php if($_SESSION['superacceso']): ?><input name="borrar[]" type="checkbox" id="borrar[]" value="<?php echo $row_ordenes_compra['id_pedido']; ?>" /><?php endif; ?>
                                </td>
                                <td id="dato1" nowrap><a href="orden_compra_cl_edit.php?str_numero_oc=<?php echo $row_ordenes_compra['str_numero_oc'];?>&id_oc=<?php echo $row_ordenes_compra['id_c_oc'];?>" target="_top" style="text-decoration:none; color:#000000"><strong><?php echo $row_ordenes_compra['str_numero_oc']; ?></strong></a></td>
                                <td id="dato2" nowrap> 
                                 <a href="javascript:verConsulta('historico','<?php echo $row_ordenes_compra['id_pedido']; ?>','orden_compra_cl2.php')" ><?php echo $row_ordenes_compra['fecha_ingreso_oc']; ?></a>
                                </td>
                                <td id="dato1" nowrap><a href="orden_compra_cl_edit.php?str_numero_oc=<?php echo $row_ordenes_compra['str_numero_oc']; ?>&id_oc=<?php echo $row_ordenes_compra['id_c_oc'];?>" target="_top" style="text-decoration:none; color:#000000">
                                   <?php 
                                   $id_c_oc=$row_ordenes_compra['id_c_oc'];
                                   $sqln="SELECT nombre_c FROM cliente WHERE id_c='$id_c_oc'"; 
                                   $resultn=mysql_query($sqln); 
                                   $numn=mysql_num_rows($resultn); 
                                   if($numn >= '1') 
                                   { 
                                    $nit_cliente_c=mysql_result($resultn,0,'nombre_c'); echo  ($nit_cliente_c); 
                                  }
                                  ?>
                                </a>
                              </td>
                                <td id="dato1" nowrap><a href="orden_compra_cl_edit.php?str_numero_oc=<?php echo $row_ordenes_compra['str_numero_oc'];?>&id_oc=<?php echo $row_ordenes_compra['id_c_oc'];?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_ordenes_compra['str_elaboro_oc']; ?></a>
                                </td>
                                <td id="dato1" nowrap><a href="orden_compra_cl_edit.php?str_numero_oc=<?php echo $row_ordenes_compra['str_numero_oc'];?>&id_oc=<?php echo $row_ordenes_compra['id_c_oc'];?>" target="_top" style="text-decoration:none; color:#000000">
                                    <?php 
                                     $idoc = $row_ordenes_compra['str_numero_oc'];
                                     $select_direccion = $conexion->llenaListas('vendedor ver',"left join tbl_items_ordenc itm on  ver.id_vendedor=itm.int_vendedor_io WHERE itm.str_numero_io= '$idoc'","","distinct ver.nombre_vendedor");
                                      foreach($select_direccion as $row_direccion) { 
                                        echo $row_direccion['nombre_vendedor']." ";
                                      } 
                                     ?> 
                                   </a>
                                </td>
                                <td id="dato1" nowrap>
                                   <?php 
                                   $id_pedido=$row_ordenes_compra['id_pedido'];
                                   $sqlCAN="SELECT SUM(int_cantidad_io) AS int_cantidad_io FROM Tbl_items_ordenc WHERE id_pedido_io='$id_pedido'"; 
                                   $resultCAN=mysql_query($sqlCAN); 
                                   $numCAN=mysql_num_rows($resultCAN); 
                                   if($numCAN >= '1') 
                                   { 
                                     $int_cantidad_io=mysql_result($resultCAN,0,'int_cantidad_io'); echo round($int_cantidad_io); 
                                   }
                                  ?>
                                  
                                </td>
                                <td id="dato1" nowrap>
                                  <?php  
                                  if($row_ordenes_compra['factura_oc']!='0' && $row_ordenes_compra['factura_oc']!=''){
                                      $datosFE = substr($row_ordenes_compra['factura_oc'], 0, 2);  
                                     
                                     $variasFacArray = array();
                                    if($datosFE=="FE"){ 
                                      //$variasFacArray[] = $row_ordenes_compra['factura_oc'];
                                       $variasFacArray=( explode(',', $row_ordenes_compra['factura_oc']) );
                                       foreach ($variasFacArray as $key => $value) {
                                         $conceros = $value ;
                                        ?>  
                                        <a href="javascript:verFoto('PDF_FE/<?php echo $conceros ;?>.pdf','610','490')">  <?php echo $conceros.'<br>'; ?> </a> 
                                        <?php
                                       }  

                                      }if($datosFE!="FE"){
                                      
                                         $digito = "FE";
                                         $facturaCompleto = $row_ordenes_compra['factura_oc'];
                                         if($facturaCompleto!='' || $facturaCompleto!= null){ 
                                         $conceros = $digito.(str_pad($facturaCompleto, 7, "0", STR_PAD_LEFT)); 
                                         ?>  
                                         <a href="javascript:verFoto('PDF_FE/<?php echo $conceros ;?>.pdf','610','490')">  <?php echo $conceros.'<br>'; ?> </a> 
                                         <?php  
                                         }else{
                                           $conceros = '';   
                                         } 
                                       }  

                                     }

                                 ?> 
                                </td>
                                <td id="dato2"><?php echo redondear_decimal_operar($row_ordenes_compra['valor_cartera']) ;?></td>
                                <td id="dato2"><?php echo utf8_encode($row_ordenes_compra['tipo_pago_cartera']);?></td>
                                <td id="dato2"><a href="orden_compra_cl_edit.php?str_numero_oc=<?php echo $row_ordenes_compra['str_numero_oc']; ?>&id_oc=<?php echo $row_ordenes_compra['id_c_oc'];?>">
                                  <?php 
                                  $id_pedido=$row_ordenes_compra['id_pedido'];
                                  $sqlpend="SELECT SUM(int_cantidad_rest_io) AS restante FROM Tbl_items_ordenc WHERE id_pedido_io='$id_pedido'"; 
                                  $resultpend=mysql_query($sqlpend);
                                  $numpend=mysql_num_rows($resultpend); 
                                  if($numpend >= '1'){
                                    $restante = mysql_result($resultpend, 0, 'restante'); 
                                  } 
                                  if( $restante > 0.00 ){?>
                                  <img src="images/falta3.gif" alt="CANTIDAD PENDIENTES" width="20" height="18" style="cursor:hand;" title="CANTIDAD PENDIENTES" border="0"/>  
                                  <?php }else if($restante == ''){?><em>sin items</em><?php } else {?>
                                  <img src="images/cumple.gif" alt="OK" width="20" height="18" style="cursor:hand;" title="OK" border="0"/><?php } ?>
                                </a>
                               </td>
                                <td id="dato2"><a href="orden_compra_cl_edit.php?str_numero_oc=<?php echo $row_ordenes_compra['str_numero_oc']; ?>&id_oc=<?php echo $row_ordenes_compra['id_c_oc'];?>"><?php  
                                      $estado=$row_ordenes_compra['b_estado_oc'];
                                      if($estado=='5'){  ?>
                                        
                                       <img src="images/f.gif" alt="FACTURADA O.C." title="FACTURADA O.C." border="0" style="cursor:hand;"/>
                                       <?php }
                                       if($estado=='4'){ ?><img src="images/fr.gif" alt="FACTURADA PARCIAL" title="FACTURADA PARCIAL" border="0" style="cursor:hand;"/><?php }
                                        if($estado=='3'){ ?><img src="images/r.gif" alt="REMISION O.C." title="REMISION O.C." border="0" style="cursor:hand;"/><?php }
                                          $id_oc=$row_ordenes_compra['str_numero_oc'];
                                          $sqlmp="SELECT Tbl_orden_produccion.int_cod_ref_op AS existe_op 
                                          FROM Tbl_items_ordenc,Tbl_orden_produccion WHERE Tbl_items_ordenc.str_numero_io='$id_oc' AND Tbl_items_ordenc.str_numero_io=Tbl_orden_produccion.str_numero_oc_op 
                                          AND Tbl_items_ordenc.int_cod_ref_io=Tbl_orden_produccion.int_cod_ref_op AND Tbl_orden_produccion.b_borrado_op='0'";
                                          $resultmp= mysql_query($sqlmp);
                                          $nump = mysql_num_rows($resultmp);
                                          if($nump >='1')
                                          { 
                                            $existe_op = mysql_result($resultmp,0,'existe_op');
                                          }else {$existe_op="0";} 
                                          if($estado=='2' && $existe_op =='0'){ ?><img src="images/p.gif" alt="PROGRAMADA O.C." title="PROGRAMADA O.C." border="0" style="cursor:hand;"/><?php }else 
                                            if($estado=='2' && $existe_op > '0'){ ?><img src="images/pa.gif" alt="EN PRODUCCION" title="EN PRODUCCION" border="0" style="cursor:hand;"/><?php }
                                              if($estado=='1'){ ?><img src="images/falta.gif" alt="INGRESADA x O.C." title="INGRESADA O.C." border="0" style="cursor:hand;"/><?php }
                                                ?>
                                            </a>
                                        </td>
                                       <?php if($_SESSION['acceso']): ?>
                                       <td id="fuente2">
                                          <a href="javascript:updateList('id_pedido',<?php echo $row_ordenes_compra['id_pedido']; ?>,'orden_compra_cl2.php')" >
                                            <?php  
                                            $sqldato="SELECT factura_r FROM Tbl_remisiones WHERE str_numero_oc_r='$id_oc'";
                                            $resultdato=mysql_query($sqldato);
                                            $factura_r=mysql_result($resultdato,0,'factura_r');

                                            if(($factura_r=='' || $factura_r=='0') && ($row_ordenes_compra['factura_oc']=='' || $row_ordenes_compra['factura_oc']=='0')): ?>
                                                <img src="images/falta8.gif" alt="SIN FACTURA" title="SIN FACTURA" border="0" style="cursor:hand;" width="20" height="18" /> 
                                                  <?php else: ?>
                                                <img src="images/facturado.png" alt="YA TIENE FACTURA" title="YA TIENE FACTURA" border="0" style="cursor:hand;" width="20" height="18" />
                                              <?php endif; ?>
                                              </a> 
                                       </td> 
                                          <div style="display: none;  align-items: center; justify-content: center; " id="resp"> <b style="color: red;" >Actualizando... Numero de Factura!</b>
                                          </div>
                                          <?php endif; ?>
                                       <td id="fuente2">
                                         <?php if($row_ordenes_compra['autorizado']=='SI'): ?>
                                            <a href="javascript:updateAutorizar('Desautorizar',<?php echo $row_ordenes_compra['id_pedido']; ?>,'orden_compra_cl2.php','<?php echo $row_ordenes_compra['str_numero_oc']; ?>')" ><img src="images/accept.png" alt="AUTORIZADA" title="AUTORIZADA" border="0" style="cursor:hand;" width="20" height="18" /></a>
                                               <?php else: ?>
                                             <a href="javascript:updateAutorizar('Autorizar',<?php echo $row_ordenes_compra['id_pedido']; ?>,'orden_compra_cl2.php','<?php echo $row_ordenes_compra['str_numero_oc']; ?>')" ><img src="images/salir.gif" alt="AUTORIZAR" title="AUTORIZAR" border="0" style="cursor:hand;" width="20" height="18" /></a>
                                           <?php endif; ?>
                                           <div style="display: none;  align-items: center; justify-content: center; " id="autorizado"> <b style="color: red;" >Actualizando... despacho!</b></div>
                                       </td>  
                                   </tr>
                                 </tbody>
                                <?php } while ($row_ordenes_compra = mysql_fetch_assoc($ordenes_compra)); ?>
                             </table> 
                          </form>
                       </fieldset>
                         <table border="0" width="50%" align="center">
                           <tr>
                             <td width="23%" id="dato2"><?php if ($pageNum_ordenes_compra > 0) { // Show if not first page ?>
                               <a href="<?php printf("%s?pageNum_ordenes_compra=%d%s", $currentPage, 0, $queryString_ordenes_compra); ?>">Primero</a>
                               <?php } // Show if not first page ?>
                             </td>
                             <td width="31%" id="dato2"><?php if ($pageNum_ordenes_compra > 0) { // Show if not first page ?>
                               <a href="<?php printf("%s?pageNum_ordenes_compra=%d%s", $currentPage, max(0, $pageNum_ordenes_compra - 1), $queryString_ordenes_compra); ?>">Anterior</a>
                               <?php } // Show if not first page ?>
                             </td>
                             <td width="23%" id="dato2"><?php if ($pageNum_ordenes_compra < $totalPages_ordenes_compra) { // Show if not last page ?>
                               <a href="<?php printf("%s?pageNum_ordenes_compra=%d%s", $currentPage, min($totalPages_ordenes_compra, $pageNum_ordenes_compra + 1), $queryString_ordenes_compra); ?>">Siguiente</a>
                               <?php } // Show if not last page ?>
                             </td>
                             <td width="23%" id="dato2"><?php if ($pageNum_ordenes_compra < $totalPages_ordenes_compra) { // Show if not last page ?>
                               <a href="<?php printf("%s?pageNum_ordenes_compra=%d%s", $currentPage, $totalPages_ordenes_compra, $queryString_ordenes_compra); ?>">&Uacute;ltimo</a>
                               <?php } // Show if not last page ?>
                             </td>
                           </tr>
                         </table>
     
                     </div>  <!-- contenedor -->
                   </div>    <!-- panel - body -->
                  </div>
                </div>
          </div>
      </td>
    </tr>
</table>
</div>
</body>
  
</html>
<script> 
/*  $(document).ready(function(){

    var editar =  "<?php echo $_SESSION['acceso'];?>";
    
    if(editar==0){
      $('button').attr('disabled', 'disabled');
     $('a').each(function() { 
      $(this).attr('href', '#');
    });
     //swal("No Autorizado", "Sin permisos para editar :)", "error"); 
   }
 });
*/

  function uPDATE(id,valor,colum,proceso,url){
 
     swal({   
      title: "Actualizar?",   
      text: "Esta seguro que Quiere Actualizar a Pagado!",   
      type: "warning",   
      showCancelButton: true,   
      confirmButtonColor: "#DD6B55",   
      confirmButtonText: "Si, Actualizar!",   
      cancelButtonText: "No, Actualizar!",   
      closeOnConfirm: false,   
      closeOnCancel: false }, 
      function(isConfirm){   
        if (isConfirm) {  
          swal("Actualizado!", "El registro se ha Actualizado.", "success"); 
          actualizacion(id,valor,colum,proceso,url);
          location.reload(); 
        } else {     
          swal("Cancelado", "has cancelado :)", "error"); 
        } 
      });  

  }

 
</script>

<script>

 $(document).ready(function(){  
 
        $('#str_numero_oc').select2({ 
            ajax: {
                url: "select3/proceso.php",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        palabraClave: params.term, // search term
                        var1:"str_numero_oc",//campo normal para usar
                        var2:"tbl_orden_compra",//tabla
                        var3:" b_borrado_oc='0' ",//where
                        var4:" ORDER BY fecha_ingreso_oc DESC",
                        var5:"str_numero_oc",//clave
                        var6:"str_numero_oc"//columna a buscar
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });


        $('#id_c').select2({ 
            ajax: {
                url: "select3/proceso.php",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        palabraClave: params.term, // search term
                        var1:"id_c,nombre_c,nit_c",//campo normal para usar
                        var2:"cliente",//tabla
                        var3:"",//where
                        var4:"ORDER BY CONVERT(nombre_c, SIGNED INTEGER) ASC",
                        var5:"id_c",//clave
                        var6:"nombre_c"//columna a buscar
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });




    $('#nit_c').select2({ 
        ajax: {
            url: "select3/proceso.php",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    palabraClave: params.term, // search term
                    var1:"nit_c",
                    var2:"cliente",
                    var3:"",//where
                    var4:"ORDER BY CONVERT(nit_c, SIGNED INTEGER) DESC",
                    var5:"nit_c",
                    var6:"nit_c"//columna a buscar
                };
            },
            processResults: function (response) {
                return {
                    results: response
                };
            },
            cache: true
        }
    });


    $('#cod_ref').select2({ 
        ajax: {
            url: "select3/proceso.php",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    palabraClave: params.term, // search term
                    var1:"cod_ref",
                    var2:"tbl_referencia",
                    var3:" estado_ref='1'",//where
                    var4:"ORDER BY CONVERT(cod_ref, SIGNED INTEGER) DESC",
                    var5:"cod_ref",
                    var6:"cod_ref"//columna a buscar
                };
            },
            processResults: function (response) {
                return {
                    results: response
                };
            },
            cache: true
        }
    });


    $('#nfactura').select2({ 
        ajax: {
            url: "select3/proceso.php",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    palabraClave: params.term, // search term
                    var1:"factura_oc",
                    var2:"tbl_orden_compra",
                    var3:" factura_oc <>'' ",//where
                    var4:"ORDER BY fecha_ingreso_oc DESC",
                    var5:"factura_oc",
                    var6:"factura_oc"//columna a buscar
                };
            },
            processResults: function (response) {
                return {
                    results: response
                };
            },
            cache: true
        }
    });
  
  
});



function envioListadosPrecio() { 
 
 var autorizado = 1;
 window.location.href ="orden_compralist_excel_precio.php?autorizado="+autorizado;
}
</script>

<?php
mysql_free_result($usuario); 
mysql_free_result($ordenes_compra); 
mysql_free_result($all_ordenes_compra); 
mysql_free_result($lista); 
mysql_free_result($proveedores); 
mysql_free_result($ano); 
mysql_free_result($numero); 
mysql_free_result($vendedores); 
mysql_free_result($resultmp);


/*mysql_close($conexion1);


unset($ordenes_compra,$conexion1);
unset($all_ordenes_compra,$conexion1);
unset($lista,$conexion1);
unset($proveedores,$conexion1);
unset($ano,$conexion1);
unset($numero,$conexion1);
unset($vendedores,$conexion1);*/

?>