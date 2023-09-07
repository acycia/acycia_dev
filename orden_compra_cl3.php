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

$maxRows_ordenes_compra = 20;
$pageNum_ordenes_compra = 0;
if (isset($_GET['pageNum_ordenes_compra'])) {
  $pageNum_ordenes_compra = $_GET['pageNum_ordenes_compra'];
}
$startRow_ordenes_compra = $pageNum_ordenes_compra * $maxRows_ordenes_compra;

$row_ordenes_compra = $conexion->buscarListar("tbl_orden_compra","*","GROUP BY str_numero_oc ORDER BY b_estado_oc,  fecha_ingreso_oc DESC","WHERE b_borrado_oc='1'",$maxRows_ordenes_compra,$pageNum_ordenes_compra,"" );

/*mysql_select_db($database_conexion1, $conexion1);
$query_ordenes_compra ="SELECT * FROM Tbl_orden_compra WHERE b_borrado_oc='1' GROUP BY str_numero_oc ORDER BY b_estado_oc,  fecha_ingreso_oc DESC";
$query_limit_ordenes_compra = sprintf("%s LIMIT %d, %d", $query_ordenes_compra, $startRow_ordenes_compra, $maxRows_ordenes_compra);
$ordenes_compra = mysql_query($query_limit_ordenes_compra, $conexion1) or die(mysql_error());
$row_ordenes_compra = mysql_fetch_assoc($ordenes_compra);*/
 

$row_lista = $conexion->llenaSelect('tbl_orden_compra',"WHERE b_borrado_oc='1'",'ORDER BY str_numero_oc DESC');  

$row_proveedores = $conexion->llenaSelect('cliente','','ORDER BY nombre_c ASC'); 
 
$row_ano = $conexion->llenaSelect('cliente','','ORDER BY nit_c DESC'); 

 
if (isset($_GET['totalRows_ordenes_compra'])) {
  $totalRows_ordenes_compra = $_GET['totalRows_ordenes_compra'];
} else {
  $totalRows_ordenes_compra = $conexion->conteo('tbl_orden_compra'); 
} 
$totalPages_ordenes_compra = floor($totalRows_ordenes_compra/$maxRows_ordenes_compra)-1; 

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

session_start();
 ?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/listado.js"></script>

<!-- desde aqui para listados nuevos -->
  <link rel="stylesheet" type="text/css" href="css/desplegable.css" />
  <link rel="stylesheet" type="text/css" href="css/general.css"/>

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
</head>
<body onload = "JavaScript: AutoRefresh (60000);">
    <script>
      $(document).ready(function() { $(".busqueda").select2(); });
  </script>
  <div class="spiffy_content"> <!-- este define el fondo gris de lado a lado si se coloca dentro de tabla inicial solamente coloca borde gris -->
    <div align="center">
      <table style="width: 80%"><!-- class="table table-bordered table-sm" -->
        <tr>
         <td align="center">
           <div class="row-fluid">
             <div class="span8 offset2"> <!--span8 offset2   esto da el tamaño pequeño -->
               <div class="panel panel-primary">
                <div class="panel-heading" align="left" ></div><!--color azul-->
                <div class="row" >
                    <div class="span12">&nbsp;&nbsp;&nbsp; <img src="images/cabecera.jpg"></div>
                    <div class="panel-heading"><h3>ORDENES DE COMPRA</h3></div>
                </div>
                 <div class="panel-heading" align="left" ></div><!--color azul-->
                  <div id="cabezamenu">
                   <ul id="menuhorizontal">
                     <li><?php echo $row_usuario['nombre_usuario']; ?></li>
                     <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                     <li><a href="menu.php">MENU PRINCIPAL</a></li>
                     <li><a href="orden_compra_cl_reasig_oc.php">REASIGNAR OC</a></li>
                     </ul>
                   </ul>
                </div> 
               <div class="panel-body">
                 <br> 
                 <div ><!-- contenedor -->
                  <div class="row">
                    <div class="span12"> 
                    </div>
                  </div>   


<form action="orden_compra1_cl3.php" method="get" name="consulta">
<table class="table table-bordered table-sm"> 
<tr>
  <td colspan="2" id="fuente2">
    <select class="selectsMedio busqueda" name="str_numero_oc" id="str_numero_oc">
       <option value="0">O.C.</option>
           <?php  foreach($row_lista as $row_lista ) { ?>
        <option value="<?php echo $row_lista['str_numero_oc']?>"><?php echo $row_lista['str_numero_oc']?></option>
    <?php } ?>
    </select>

    <select class="selectsMedio busqueda" name="id_c" id="id_c">
       <option value="0">CLIENTE</option>
           <?php  foreach($row_proveedores as $row_proveedores ) { ?>
        <option value="<?php echo $row_proveedores['id_c']?>"><?php $cad = ($row_proveedores['nombre_c']); echo $cad;?></option>
    <?php } ?>
    </select>


    <select class="selectsMedio busqueda" name="nit_c" id="nit_c">
       <option value="0">NIT</option>
           <?php  foreach($row_ano as $row_ano ) { ?>
        <option value="<?php echo $row_ano['nit_c']?>"><?php echo $row_ano['nit_c']?></option>
    <?php } ?>
    </select>


    <select name="estado_oc" id="estado_oc" style="width:100px">
      <option value="0">Estado O.C</option>
      <option value="1">INGRESADA</option>
      <option value="2">PROGRAMADA</option>
      <option value="3">REMISIONADA</option>
      <option value="4">FAC.PARCIAL</option>
      <option value="5">FAC.TOTAL</option>
    </select>    <select name="pendiente" id="pendiente" style="width:100px">
      <option value="0">Seleccione</option>
      <option value="=">COMPLETOS</option>
      <option value=">">PENDIENTES</option>
    </select>    <input type="submit" name="Submit" value="FILTRO" onClick="if(consulta.str_numero_oc.value=='0' && consulta.id_c.value=='0' && consulta.nit_c.value=='0'&& consulta.estado_oc.value=='0' && consulta.pendiente.value=='0') { alert('DEBE SELECCIONAR UNA OPCION'); }"/></td>

    <td id="titulo4" nowrap >ENVIO LOCAL</td>
    <td id="titulo4" nowrap>TB. PAGO</td>
  </tr>
  <tr>
    <td colspan="2" id="dato1">Nota: Si la ' F' esta en color rojo significa que es factura parcial</td>
  </tr>
 </table>
</form>
<form action="delete_listado.php" method="get" name="seleccion">
<table class="table table-bordered table-sm">
  <tr>
    <td colspan="2" id="dato1"><input name="borrado" type="hidden" id="borrado" value="222" />
    <input name="Input" type="submit" value="Activar"/>  </td>
    <td colspan="3"><?php $id=$_GET['id']; 
  if($id == '2') { ?> 
      <div id="numero1"> <?php echo "NO SE PUEDE ELIMINAR PORQUE TIENE REMISIONES CREADAS"; ?> </div>
      <?php } 
  if($id == '1') { ?> 
      <div id="acceso1"> <?php echo "ELIMINACION COMPLETA"; ?> </div>
      <?php }
  if($id == '0') { ?>
      <div id="numero1"> <?php echo "SELECCIONE PARA ELIMINAR"; ?> </div>      <?php }?></td>
    <td colspan="3" id="dato2"><a href="orden_compra_cl_add.php"><img src="images/mas.gif" alt="ADD ORDEN DE COMPRA" title="ADD ORDEN DE COMPRA" border="0" style="cursor:hand;"/></a><a href="orden_compra_cl.php"><img src="images/ciclo1.gif" alt="RESTAURAR" border="0" style="cursor:hand;"/></a><a href="insumos.php"><!--<img src="images/i.gif" style="cursor:hand;" alt="INSUMOS" border="0"/>--></a></td>
    </tr>  
  <tr id="tr1">
    <td id="fuente2"><input name="chulo1" type="checkbox" onClick="if(seleccion.chulo1.checked) { seleccionar_todo() } else{ deseleccionar_todo() } "/></td>
    <td id="titulo4">N&deg;</td>
    <td id="titulo4">INGRESO</td>
    <td id="titulo4">CLIENTE</td>
    <td id="titulo4">RESPONSABLE</td>
    <td id="titulo4">PENDIENTE</td>
    <td id="titulo4"><a href="verificaciones_criticos.php"><!--<img src="images/v.gif" alt="VERIFICACIONES (CRITICOS)" border="0" style="cursor:hand;"/>--></a>ESTADO</td>
  </tr>
  <?php foreach($row_ordenes_compra as $row_ordenes_compra) {  ?>
    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
      <td id="dato2"><input name="borrar[]" type="checkbox" id="borrar[]" value="<?php echo $row_ordenes_compra['id_pedido']; ?>" /></td>
      <td id="dato1"><a href="orden_compra_cl_edit.php?str_numero_oc=<?php echo $row_ordenes_compra['str_numero_oc'];?>&id_oc=<?php echo $row_ordenes_compra['id_c_oc'];?>" target="_top" style="text-decoration:none; color:#000000"><strong><?php echo $row_ordenes_compra['str_numero_oc']; ?></strong></a></td>
      <td id="dato2"><a href="orden_compra_cl_edit.php?str_numero_oc=<?php echo $row_ordenes_compra['str_numero_oc']; ?>&id_oc=<?php echo $row_ordenes_compra['id_c_oc'];?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_ordenes_compra['fecha_ingreso_oc']; ?></a></td>
      <td id="dato2"><a href="orden_compra_cl_edit.php?str_numero_oc=<?php echo $row_ordenes_compra['str_numero_oc']; ?>&id_oc=<?php echo $row_ordenes_compra['id_c_oc'];?>" target="_top" style="text-decoration:none; color:#000000">
        <?php 
	$nit_c=$row_ordenes_compra['str_nit_oc'];
	$sqln="SELECT * FROM cliente WHERE nit_c='$nit_c'"; 
	$resultn=mysql_query($sqln); 
	$numn=mysql_num_rows($resultn); 
	if($numn >= '1') 
	{ $nit_cliente_c=mysql_result($resultn,0,'nombre_c'); $ca =($nit_cliente_c); echo $ca; }
	else { echo "";	} ?>
      </a></td>
      <td id="dato1"><a href="orden_compra_cl_edit.php?str_numero_oc=<?php echo $row_ordenes_compra['str_numero_oc']; ?>&id_oc=<?php echo $row_ordenes_compra['id_c_oc'];?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_ordenes_compra['str_responsable_oc']; ?></a></td>
      <td id="dato2"><a href="orden_compra_cl_edit.php?str_numero_oc=<?php echo $row_ordenes_compra['str_numero_oc']; ?>&id_oc=<?php echo $row_ordenes_compra['id_c_oc'];?>">
        <?php 
	$id_pedido=$row_ordenes_compra['id_pedido'];
	$sqlpend="SELECT SUM(int_cantidad_rest_io) AS restante FROM Tbl_items_ordenc WHERE id_pedido_io='$id_pedido'"; 
	$resultpend=mysql_query($sqlpend);
	$numpend=mysql_num_rows($resultpend); 
	if($numpend >= '1'){
	$restante = mysql_result($resultpend, 0, 'restante'); 
	} 
	if( $restante > 0.00){?>
        <img src="images/falta3.gif" alt="CANTIDAD PENDIENTES" width="20" height="18" style="cursor:hand;"title="CANTIDAD PENDIENTES" border="0"/>
        <?php }else if($restante == ''){?><em>sin items</em><?php }else {?>
        <img src="images/cumple.gif" alt="OK" width="20" height="18" style="cursor:hand;"title="OK" border="0"/>
        <?php } ?>
        </a></td>
      <td id="dato2"><a href="orden_compra_cl_edit.php?str_numero_oc=<?php echo $row_ordenes_compra['str_numero_oc']; ?>&id_oc=<?php echo $row_ordenes_compra['id_c_oc'];?>"><?php 
	$id_pedido=$row_ordenes_compra['id_pedido'];
	$id_c_oc=$row_ordenes_compra['id_c_oc'];
	$sqldet="SELECT * FROM Tbl_orden_compra WHERE id_pedido='$id_pedido' AND id_c_oc='$id_c_oc'"; 
	$resultdet=mysql_query($sqldet); 
	$numdet=mysql_num_rows($resultdet);
	if($numdet >= '1'){
	$borrado = mysql_result($resultdet, 0, 'b_borrado_oc');
	}
	if($numdet >= '1'&&$borrado=='1') 
	{
	
	 ?><img src="images/e.gif" alt="INACTIVA" title="INACTIVA" border="0" style="cursor:hand;"/><?php }else{echo "";}?></a></td> 

   <!-- <td id="dato3" style="text-align: center;"><input name="update3[]" type="checkbox" id="update3[]" value="<?php echo $row_ordenes_compra['id_pedido']; ?>" /></td>
   <td id="dato4" style="text-align: center;"><input name="update4[]" type="checkbox" id="update4[]" value="<?php echo $row_ordenes_compra['id_pedido']; ?>" /></td> -->

    </tr>
    <?php } ?>
</table>
</form>
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
    </td> 
  </div>
  </div><!-- contenedor -->
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
<script>
$(document).ready(function(){

$("#change1").keyup(function(){
    var parametros="change="+$(this).val()
    $.ajax({
        data: parametros,
        url: 'update_ocpw.php',
        type:  'GET',
        beforeSend: function () {},
            success:  function (response) {    
            $(".salida").html(response);
        },
        error:function(){
            alert("error")
        }
    });
})

})
</script>

<?php
mysql_free_result($usuario);

mysql_free_result($ordenes_compra);

mysql_free_result($lista);

mysql_free_result($proveedores);

mysql_free_result($ano);
?>