<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
?>
<?php require_once('Connections/conexion1.php'); ?>
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

$currentPage = $_SERVER["PHP_SELF"];

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario); 

 
$maxRows_bolsa_verificacion = 20;
$pageNum_bolsa_verificacion = 0;
if (isset($_GET['pageNum_bolsa_verificacion'])) {
  $pageNum_bolsa_verificacion = $_GET['pageNum_bolsa_verificacion'];
}
 $startRow_bolsa_verificacion = $pageNum_bolsa_verificacion * $maxRows_bolsa_verificacion;

$row_bolsa_verificacion = $conexion->buscarListar("verificacion_bolsas","*","ORDER BY n_vb DESC","",$maxRows_bolsa_verificacion,$pageNum_bolsa_verificacion,"" );


if (isset($_GET['totalRows_bolsa_verificacion'])) {
  $totalRows_bolsa_verificacion = $_GET['totalRows_bolsa_verificacion'];
} else {
  $totalRows_bolsa_verificacion = $conexion->conteo('verificacion_bolsas'); //count($row_bolsa_verificacion); 
}

$totalPages_bolsa_verificacion = ceil($totalRows_bolsa_verificacion/$maxRows_bolsa_verificacion)-1; 
 
 

$row_ordenes_compra = $conexion->llenaSelect('orden_compra_bolsas','','ORDER BY n_ocb DESC');
 
$row_proveedores = $conexion->llenaSelect('proveedor','','ORDER BY proveedor_p ASC');

$row_bolsas = $conexion->llenaSelect('material_terminado_bolsas','','ORDER BY nombre_bolsa ASC');

$row_referencias = $conexion->llenaSelect('Tbl_referencia','','ORDER BY id_ref ASC');

$row_anual = $conexion->llenaSelect('anual','','ORDER BY anual DESC');

 
$queryString_bolsa_verificacion = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_bolsa_verificacion") == false && 
        stristr($param, "totalRows_bolsa_verificacion") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_bolsa_verificacion = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_bolsa_verificacion = sprintf("&totalRows_bolsa_verificacion=%d%s", $totalRows_bolsa_verificacion, $queryString_bolsa_verificacion);
?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/listado.js"></script>

<!-- sweetalert -->
  <link rel="stylesheet" type="text/css" href="css/general.css"/>
  <link rel="stylesheet" type="text/css" href="css/desplegable.css" />
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
        <div class="spiffy_content"> <!-- este define el fondo gris de lado a lado si se coloca dentro de tabla inicial solamente coloca borde gris -->
          <div align="center">
            <table style="width: 80%"><!-- id="tabla1" -->
              <tr>
               <td align="center">
                 <div class="row-fluid">
                   <div class="span8 offset2"> <!--span8 offset2   esto da el tamaño pequeño -->
                     <div class="panel panel-primary">
                      <div class="panel-heading" align="left" ></div><!--color azul-->
                      <div class="row" >
                          <div class="span12">&nbsp;&nbsp;&nbsp; <img src="images/cabecera.jpg"></div>
                          <div class="panel-heading"><h3>VERIFICACIONES   ( BOLSAS ) </h3></div>
                      </div>
                      <div class="panel-heading" align="left" ></div><!--color azul-->
                       <div id="cabezamenu">
                        <ul id="menuhorizontal">
                          <li><?php echo $row_usuario['nombre_usuario']; ?></li>
                          <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                          <li><a href="menu.php">MENU PRINCIPAL</a></li>
                          <li><a href="compras.php">GESTION COMPRAS</a></li>
                        </ul>
                     </div> 
                     <div class="panel-body">
                       <br> 
                       <div ><!-- contenedor -->
                        <div class="row">
                          <div class="span12"> 
                     </div>
                   </div>  

                   <form action="bolsas_verificacion1.php" method="get" name="consulta">
                    <div align="center">
                      <table class="table table-bordered table-sm">
                        <tr>
                          <td id="titulo2">VERIFICACIONES   ( BOLSAS ) </td>
                        </tr>
                        <tr>
                          <td id="fuente2">
                            <select class="selectsMedio busqueda" name="n_ocb" id="n_ocb">
                              <option value="0">O.C.</option>
                                   <?php  foreach($row_ordenes_compra as $row_ordenes_compra ) { ?>
                                <option value="<?php echo $row_ordenes_compra['n_ocb']?>"><?php echo $row_ordenes_compra['n_ocb']?></option>
                            
                            <?php } ?>
                            </select>

                            <select class="selectsGrande busqueda" name="id_p" id="id_p">
                            <option value="0">PROVEEDOR</option>
                                   <?php  foreach($row_proveedores as $row_proveedores ) { ?>
                                <option value="<?php echo $row_proveedores['id_p']?>"><?php echo $row_proveedores['proveedor_p']?></option>
                            
                            <?php } ?>
                            </select>

                            <select class="selectsMedio busqueda" name="id_bolsa" id="id_bolsa">
                            <option value="0">BOLSA</option>
                                   <?php  foreach($row_bolsas as $row_bolsas ) { ?>
                                <option value="<?php echo $row_bolsas['id_bolsa']?>"><?php echo $row_bolsas['nombre_bolsa']?></option>
                            
                            <?php } ?>
                            </select> 

                            <select class="selectsMedio busqueda" name="id_ref" id="id_ref">
                             <option value="0">REF</option>
                                   <?php  foreach($row_referencias as $row_referencias ) { ?>
                                <option value="<?php echo $row_referencias['id_ref']?>"><?php echo $row_referencias['cod_ref']?></option>
                            
                            <?php } ?>
                            </select> 

                         <select class="selectsMedio busqueda" name="fecha" id="fecha">
                          <option value="0">A&Ntilde;O</option>
                                    <?php  foreach($row_anual as $row_anual ) { ?>
                                <option value="<?php echo $row_anual['anual']?>"><?php echo $row_anual['anual']?></option>
                             
                             <?php } ?>
                         </select> 

                           
                        </td>
                      </tr>
                      <tr><td id="fuente2"><input type="submit" name="Submit" class="botonGeneral" value="FILTRO" onClick="if(consulta.n_ocb.value=='0' && consulta.id_p.value=='0' && consulta.id_bolsa.value=='0' && consulta.id_ref.value=='0' && consulta.fecha.value=='0') { alert('DEBE SELECCIONAR UNA OPCION'); }"/></td></tr>
                    </table>
                  </div>
                </form>
                <form action="delete_listado.php" method="get" name="seleccion">
                  <table class="table table-bordered table-sm">
                    <tr>
                      <td id="dato2"><input name="borrado" type="hidden" id="borrado" value="18" />
                        <input name="Input" type="submit" value="X"/></td>
                        <td colspan="6" id="dato1"><?php $id=$_GET['id']; 
                        if($id >= '1') { ?> 
                          <div id="acceso1"> <?php echo "ELIMINACION COMPLETA"; ?> </div>
                        <?php }
                        if($id == '0') { ?>
                          <div id="numero1"> <?php echo "No se ha seleccionado ningún registro para eliminar"; ?> </div>
                        <?php }
                        if($id == '') { ?>
                          <div id="dato1"> <?php echo "Si elimina una verificacion, sera definitivamente."; ?> </div>      <?php }
                          ?></td>
                          <td colspan="2" id="dato2"><a href="bolsas_verificacion.php" target="_top"><img src="images/ciclo1.gif" alt="RESTAURAR" border="0" style="cursor:hand;"/></a><a href="bolsas_oc.php" target="_top"><img src="images/o.gif" alt="O.C. (BOLSA)" border="0" style="cursor:hand;"/></a><a href="bolsas.php" target="_top"><img src="images/b.gif" alt="BOLSAS" border="0" style="cursor:hand;"/></a></td>
                        </tr>  
                        <tr id="tr1">
                          <td id="titulo4"><input name="chulo1" type="checkbox" onClick="if(seleccion.chulo1.checked) { seleccionar_todo() } else{ deseleccionar_todo() } "/></td>
                          <td id="titulo4">VERIF</td>
                          <td id="titulo4">O.C.</td>
                          <td id="titulo4">PROVEEDOR</td>
                          <td id="titulo4">NOMBRE DE LA BOLSA</td>
                          <td id="titulo4">REF.</td>
                          <td id="titulo4">FECHA RECIBO</td>    
                          <td id="titulo4">ENTREGA</td>
                          <td id="titulo4">RECIBIDO</td>    
                        </tr>
                        <?php foreach($row_bolsa_verificacion as $row_bolsa_verificacion) {  ?>
                          <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
                            <td id="dato2"><input name="borrar[]" type="checkbox" value="<?php echo $row_bolsa_verificacion['n_vb']; ?>" /></td>
                            <td id="dato2"><strong><a href="bolsas_verificacion_vista.php?n_vb=<?php echo $row_bolsa_verificacion['n_vb']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_bolsa_verificacion['n_vb']; ?></a></strong></td>
                            <td id="dato2"><a href="bolsas_oc_vista.php?n_ocb=<?php echo $row_bolsa_verificacion['n_ocb_vb']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_bolsa_verificacion['n_ocb_vb']; ?></a></td>
                            <td id="dato1"><a href="proveedor_vista.php?id_p=<?php echo $row_bolsa_verificacion['id_p_vb']; ?>" target="_top" style="text-decoration:none; color:#000000">
                              <?php $id_p=$row_bolsa_verificacion['id_p_vb'];
                              if($id_p!=''){

                                $nump = $conexion->llenarCampos("proveedor", "WHERE id_p=$id_p ", " ", "proveedor_p"); 

                               if($nump >='1') { 
                                   echo $proveedor_p= $nump['proveedor_p']; 
                                }

                               } 
                               ?>
                             </a>
                           </td>
                             <td id="dato1"><a href="bolsas_vista.php?id_bolsa=<?php echo $row_bolsa_verificacion['id_bolsa_vb']; ?>" target="_top" style="text-decoration:none; color:#000000">
                              <?php $id_bolsa=$row_bolsa_verificacion['id_bolsa_vb'];
                              if($id_bolsa!=''){

                                $numbolsa = $conexion->llenarCampos("material_terminado_bolsas", "WHERE id_bolsa = $id_bolsa ", " ", "nombre_bolsa"); 
 
                                  if($numbolsa >='1') { 
                                    echo $nombre_bolsa = $numbolsa['nombre_bolsa']; 
                                   }
                                 
                                } 
                               ?>
                             </a></td>
                             <td id="dato2"><a href="referencia_vista.php?id_ref=<?php echo $row_bolsa_verificacion['id_ref_vb']; ?>" target="_top" style="text-decoration:none; color:#000000">
                              <?php 
                               $ref=$row_bolsa_verificacion['id_ref_vb'];
                              if($ref!=''){ 

                                    $numref = $conexion->llenarCampos("Tbl_referencia", "WHERE id_ref = $ref", " ", "cod_ref"); 
     
                                  if($numref >='1') { 
                                    $cod_ref= $numref['cod_ref']; 
                                  }
                                   echo $cod_ref; 
                              } 
                             ?> 
                             </a>
                           </td>
                             <td id="dato2"><a href="bolsas_verificacion_vista.php?n_vb=<?php echo $row_bolsa_verificacion['n_vb']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_bolsa_verificacion['fecha_recibido_vb']; ?></a></td>
                             <td id="dato2"><a href="bolsas_verificacion_vista.php?n_vb=<?php echo $row_bolsa_verificacion['n_vb']; ?>" target="_top" style="text-decoration:none; color:#000000">
                              <?php if($row_bolsa_verificacion['entrega_vb']=='0') { echo "Parcial"; } if($row_bolsa_verificacion['entrega_vb']=='1') { echo "Total"; } ?>
                            </a></td>
                            <td id="dato2"><a href="bolsas_verificacion_vista.php?n_vb=<?php echo $row_bolsa_verificacion['n_vb']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_bolsa_verificacion['responsable_recibido_vb']; ?></a></td>
                          </tr>
                        <?php } ?>
                      </table>
                      </form> 
                      <table id="tabla1">
                        <tr>
                          <td id="dato1" width="25%"><?php if ($pageNum_bolsa_verificacion > 0) { // Show if not first page ?>
                                <a href="<?php printf("%s?pageNum_bolsa_verificacion=%d%s", $currentPage, 0, $queryString_bolsa_verificacion); ?>">Primero</a>
                                <?php } // Show if not first page ?>
                          </td>
                          <td id="dato1" width="25%"><?php if ($pageNum_bolsa_verificacion > 0) { // Show if not first page ?>
                                <a href="<?php printf("%s?pageNum_bolsa_verificacion=%d%s", $currentPage, max(0, $pageNum_bolsa_verificacion - 1), $queryString_bolsa_verificacion); ?>">Anterior</a>
                                <?php } // Show if not first page ?>
                          </td>
                          <td id="dato1" width="25%"><?php if ($pageNum_bolsa_verificacion < $totalPages_bolsa_verificacion) { // Show if not last page ?>
                                <a href="<?php printf("%s?pageNum_bolsa_verificacion=%d%s", $currentPage, min($totalPages_bolsa_verificacion, $pageNum_bolsa_verificacion + 1), $queryString_bolsa_verificacion); ?>">Siguiente</a>
                                <?php } // Show if not last page ?>
                          </td>
                          <td id="dato1" width="25%"><?php if ($pageNum_bolsa_verificacion < $totalPages_bolsa_verificacion) { // Show if not last page ?>
                                <a href="<?php printf("%s?pageNum_bolsa_verificacion=%d%s", $currentPage, $totalPages_bolsa_verificacion, $queryString_bolsa_verificacion); ?>">&Uacute;ltimo</a>
                                <?php } // Show if not last page ?>
                          </td>
                        </tr>
                      </table>
            </div>
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
<?php
mysql_free_result($usuario);

mysql_free_result($ordenes_compra);

mysql_free_result($proveedores);

mysql_free_result($bolsas);

mysql_free_result($referencias);

mysql_free_result($anual);

mysql_free_result($bolsa_verificacion);
?>