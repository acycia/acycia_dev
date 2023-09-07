<?php
   require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
   require (ROOT_BBDD); 
?> 
<?php require_once('Connections/conexion1.php'); ?>
<?php
//initialize the session
/*if (!isset($_SESSION)) {
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
}*/
?>
<?php
/*if (!isset($_SESSION)) {
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

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario); 

$row_proveedores = $conexion->llenaListas('cliente','','ORDER BY nombre_c ASC','id_c,nombre_c'); 

$row_lista = $conexion->llenaListas('tbl_numeracion','','GROUP BY cod_ref_n  ORDER BY CAST(cod_ref_n AS int)  DESC','cod_ref_n'); 
 
$row_lista2 = $conexion->llenaListas('tbl_numeracion tn left JOIN tbl_orden_produccion top ON top.id_op=tn.int_op_n','','ORDER BY tn.int_op_n DESC','top.id_op');  

$maxRows_numeracion = 20;
$pageNum_numeracion = 0;
if (isset($_GET['pageNum_numeracion'])) {
  $pageNum_numeracion = $_GET['pageNum_numeracion'];
}
$startRow_numeracion = $pageNum_numeracion * $maxRows_numeracion;
mysql_select_db($database_conexion1, $conexion1);
$id_op = $_GET['id_op'];
$cod_ref_n = $_GET['cod_ref_n'];
$cliente = $_GET['cliente'];
//Filtra todos vacios
if($id_op == '0' && $cod_ref_n =='0' && $cliente=='')
{

$row_numeracion = $conexion->buscarListar("tbl_orden_produccion opro  LEFT JOIN tbl_numeracion num ON opro.id_op=num.int_op_n 
left join cliente cli ON opro.int_cliente_op=cli.id_c","*","ORDER BY num.int_op_n DESC","",$maxRows_numeracion,$pageNum_numeracion," WHERE num.existeTiq_n='1' AND num.b_borrado_n='0'" );
}
//Filtra op llenos
if($id_op != '0'  && $cod_ref_n =='0' && $cliente=='')
{
 
$row_numeracion = $conexion->buscarListar("tbl_orden_produccion opro LEFT JOIN tbl_numeracion num ON opro.id_op=num.int_op_n 
left join cliente cli ON opro.int_cliente_op=cli.id_c","*","ORDER BY  num.int_op_n DESC","",$maxRows_numeracion,$pageNum_numeracion," WHERE num.int_op_n=$id_op " );

}
//Filtra ref llenos
if($cod_ref_n != '0'  && $id_op == '0' && $cliente=='')
{

$row_numeracion = $conexion->buscarListar("tbl_orden_produccion opro LEFT JOIN tbl_numeracion num ON opro.id_op=num.int_op_n 
left join cliente cli ON opro.int_cliente_op=cli.id_c","*","ORDER BY num.int_op_n DESC","",$maxRows_numeracion,$pageNum_numeracion," WHERE num.cod_ref_n=$cod_ref_n " );
}
//Filtra cliente llenos
if($cod_ref_n == '0'  && $id_op == '0' && $cliente!='')
{

$row_numeracion = $conexion->buscarListar("tbl_orden_produccion opro LEFT JOIN tbl_numeracion num ON opro.id_op=num.int_op_n 
left join cliente cli ON opro.int_cliente_op=cli.id_c","*","ORDER BY num.int_op_n DESC","",$maxRows_numeracion,$pageNum_numeracion," WHERE cli.id_c=$cliente " );

}
//Filtra op y ref llenos
if($id_op != '0' && $cod_ref_n != '0' && $cliente=='')
{

$row_numeracion = $conexion->buscarListar("tbl_orden_produccion opro LEFT JOIN tbl_numeracion num ON opro.id_op=num.int_op_n 
left join cliente cli ON opro.int_cliente_op=cli.id_c","*","ORDER BY num.int_op_n DESC","",$maxRows_numeracion,$pageNum_numeracion," WHERE num.int_op_n=$id_op and num.cod_ref_n=$cod_ref_n " );

}
//Filtra ref y cliente llenos
if($id_op == '0' && $cod_ref_n != '0' && $cliente!='')
{

$row_numeracion = $conexion->buscarListar("tbl_orden_produccion opro LEFT JOIN tbl_numeracion num ON opro.id_op=num.int_op_n 
left join cliente cli ON opro.int_cliente_op=cli.id_c","*","ORDER BY num.int_op_n DESC","",$maxRows_numeracion,$pageNum_numeracion," WHERE  num.cod_ref_n=$cod_ref_n and cli.id_c=$cliente " );

}
//Filtra op y cliente llenos
if($id_op != '0' && $cod_ref_n == '0' && $cliente!='')
{
 
$row_numeracion = $conexion->buscarListar("tbl_orden_produccion opro LEFT JOIN tbl_numeracion num ON opro.id_op=num.int_op_n 
left join cliente cli ON opro.int_cliente_op=cli.id_c","*","ORDER BY num.int_op_n DESC","",$maxRows_numeracion,$pageNum_numeracion," WHERE  num.int_op_n=$id_op and cli.id_c=$cliente " );

}
//Filtra Todos llenos
if($id_op != '0' && $cod_ref_n != '0' && $cliente!='')
{

$row_numeracion = $conexion->buscarListar("tbl_orden_produccion opro LEFT JOIN tbl_numeracion num ON opro.id_op=num.int_op_n 
left join cliente cli ON opro.int_cliente_op=cli.id_c","*","ORDER BY num.int_op_n DESC","",$maxRows_numeracion,$pageNum_numeracion," WHERE num.int_op_n=$id_op and num.cod_ref_n=$cod_ref_n and cli.id_c=$cliente " );

}
 

if (isset($_GET['totalRows_numeracion'])) {
  $totalRows_numeracion = $_GET['totalRows_numeracion'];
} else {
  $totalRows_numeracion = $conexion->conteo('tbl_numeracion'); 
} 
$totalPages_numeracion = ceil($totalRows_numeracion/$maxRows_numeracion)-1;
 
$queryString_numeracion = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_numeracion") == false && 
        stristr($param, "totalRows_numeracion") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_numeracion = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_numeracion = sprintf("&totalRows_numeracion=%d%s", $totalRows_numeracion, $queryString_numeracion);
 
 



?><html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
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

  <!-- select2 -->
<!--   <link href="select2/css/select2.min.css" rel="stylesheet"/>
  <script src="select2/js/select2.min.js"></script> -->

  <!-- css Bootstrap hace mas grande el formato-->
  <link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body onload = "JavaScript: AutoRefresh (90000);">
 <script>
    //$(document).ready(function() { $(".busqueda").select2(); });
</script>
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
                <h4>LISTADO DE O.P SELLADO</h4>
            </div>
            <div class="panel-heading" align="left" ></div><!--color azul-->
             <div id="cabezamenu">
              <ul id="menuhorizontal">
                <li id="nombreusuario" ><?php echo $_SESSION['Usuario']; ?></li>
                <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                <li><a href="menu.php">MENU PRINCIPAL</a></li>
                <li><a href="sellado_control_numeracion_add.php">GENERAR TIQUETES</a></li>
                <li><a href="produccion_registro_sellado_listado.php">LIQUIDAR</a></li>
              </ul>
           </div> 
           <div class="panel-body">
             <br> 
             <div >
              <div class="row">
                <div class="span12"> 
           </div>
         </div>
         <form action="sellado_numeracion_listado2.php" method="get" name="consulta">
          <table class="table table-bordered table-sm"> 
                <tr>
                  <td id="fuente2">
                
                   <select id='id_op' name='id_op' class="selectsMini">
                       <option value="0"<?php if (!(strcmp("0", $_GET['id_op']))) {echo "selected=\"selected\"";} ?>>- O.P -</option>
                       <?php  foreach($row_lista2 as $row_lista2 ) { ?>
                         <option value="<?php echo $row_lista2['id_op']; ?>"><?php echo htmlentities($row_lista2['id_op']); ?> 
                       </option>
                     <?php } ?>
                   </select>
                   
                   <select id='cod_ref_n' name='cod_ref_n' class="selectsMini">
                       <option value="0"<?php if (!(strcmp("0", $_GET['cod_ref_n']))) {echo "selected=\"selected\"";} ?>>- REF -</option>
                       <?php  foreach($row_lista as $row_lista ) { ?>
                         <option value="<?php echo $row_lista['cod_ref_n']; ?>"><?php echo htmlentities($row_lista['cod_ref_n']); ?> 
                       </option>
                     <?php } ?>
                   </select>
                  
                   <select id='cliente' name='cliente' class="selectsGrande">
                      <option value=""<?php if (!(strcmp("", $_GET['cliente']))) {echo "selected=\"selected\"";} ?>>- CLIENTE -</option>
                      <?php  foreach($row_proveedores as $row_proveedores ) { ?>
                        <option value="<?php echo $row_proveedores['id_c']; ?>"<?php if (!(strcmp($row_proveedores['id_c'], $_GET['cliente']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_proveedores['nombre_c']); ?> 
                      </option>
                    <?php } ?>
                   </select>
                  </td>
                   </tr> 
                   </table>

        

              <!--   <select name="id_op" id="id_op"  class="busqueda selectsMini">
                  <option value="0"<?php if (!(strcmp("0", $_GET['id_op']))) {echo "selected=\"selected\"";} ?>>O.P</option>
                  <?php  foreach($row_lista2 as $row_lista2 ) { ?>
                    <option value="<?php echo $row_lista2['id_op']; ?>"<?php if (!(strcmp($row_lista2['cod_ref'], $_GET['id_op']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_lista2['id_op']); ?> 
                  </option>
                <?php } ?>
              </select>

              <select name="cod_ref_n" id="cod_ref_n"  class="busqueda selectsMini">
                <option value="0"<?php if (!(strcmp("0", $_GET['cod_ref_n']))) {echo "selected=\"selected\"";} ?>>REF</option>
                <?php  foreach($row_lista as $row_lista ) { ?>
                  <option value="<?php echo $row_lista['cod_ref_n']; ?>"<?php if (!(strcmp($row_lista['cod_ref_n'], $_GET['cod_ref_n']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_lista['cod_ref_n']); ?> 
                </option>
              <?php } ?>
            </select>

            <select name="cliente" id="cliente"  class="busqueda selectsGrande">
              <option value=""<?php if (!(strcmp("", $_GET['cliente']))) {echo "selected=\"selected\"";} ?>>CLIENTE</option>
              <?php  foreach($row_proveedores as $row_proveedores ) { ?>
                <option value="<?php echo $row_proveedores['id_c']; ?>"<?php if (!(strcmp($row_proveedores['id_c'], $_GET['cliente']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_proveedores['nombre_c']); ?> 
              </option>
            <?php } ?>
          </select>   -->

          <input type="submit" name="Submit" value="FILTRO" class="botonGMini">  
    </form>
    <form action="delete_listado.php" method="get" name="seleccion">
      <table class="table table-bordered table-sm">
        <tr>
          <td colspan="2" id="dato1"><input name="usuario" type="hidden" id="usuario" value="<?php echo $row_usuario['nombre_usuario']; ?>" />
            <input name="borrado" type="hidden" id="borrado" value="34" />
            <?php if($_SESSION['acceso']): ?> 
              <input name="Input" type="submit" value="Cambio Estado" class="botonMini" />
            <?php endif; ?>
          </td>
          <td colspan="4">

            <?php 

            $compactada=serialize($row_url); 

            $compactada=urlencode($compactada);     
            ?>
            <?php $id=$_GET['id']; 
            if($id >= '1') { ?> <div id="acceso1"> <?php echo "SE ELIMINO CORRECTAMENTE"; ?> </div> <?php }
            if($id == '0') { ?><div id="numero1"> <?php echo "NO HA SELECCIONADO"; ?> </div> <?php }?></td>
            <td colspan="5" id="dato3"><!-- <a href="sellado_control_numeracion_add.php"><img src="images/mas.gif" alt="CREAR TIQUETES A O.P" title="CREAR TIQUETES A O.P" border="0" style="cursor:hand;"/></a> -->
             <a href="view_index.php?c=csellado&a=Inicio"><img src="images/mas.gif" alt="CREAR TIQUETES A O.P" title="CREAR TIQUETES A O.P" border="0" style="cursor:hand;"/></a> 
             <?php if($_SESSION['acceso']): ?>
              <a href="despacho_direccion.php"><img src="images/c.gif" alt="VERIFICAR PAQUETES X CAJA" title="VERIFICAR PAQUETES X CAJA" border="0" style="cursor:hand;"/></a>
              <a href="numeracion_listado_caja_error.php" target="_blank" ><img src="images/20.PNG" alt="CAJA CON ERROR" title="CAJA CON ERROR" border="0" style="cursor:hand;"/></a>
            <?php endif; ?>
            <a href="numeracion_listado.php"><img src="images/f.gif" alt="O.P SIN TIQUETES" title="O.P SIN TIQUETES" border="0" style="cursor:hand;"/></a>
            <a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
          </tr> 
          <tr id="tr1">
            <td id="titulo4"><input name="chulo1" type="checkbox" onClick="if(seleccion.chulo1.checked) { seleccionar_todo() } else{ deseleccionar_todo() } "/></td>
            <td nowrap="nowrap"id="titulo4">N&deg; O.P </td> 
            <td nowrap="nowrap"id="titulo4">Ref</td>      
            <td nowrap="nowrap"id="titulo4">Caja</td>
            <td nowrap="nowrap"id="titulo4">Ultima Numeracion</td>                                 
            <td nowrap="nowrap"id="titulo4">Und x Paq. </td>   
            <td nowrap="nowrap"id="titulo4">Und x Caja </td> 
            <td nowrap="nowrap"id="titulo4">FECHA</td>                      
            <td nowrap="nowrap"id="titulo4">CLIENTE</td>
            <!-- <td nowrap="nowrap"id="titulo4">PRUEBA</td>  -->               
          </tr>
          <?php foreach($row_numeracion as $row_numeracion) {  ?>
            <?php 

            $row_url = $conexion->llenarCampos('tbl_numeracion', "WHERE int_op_n='".$row_numeracion['id_op']."' ", ""," * " );
    //$row_url = $conexion->llenarCampos('tbl_tiquete_numeracion', "WHERE int_op_tn='".$row_numeracion['id_op']."'  ", "ORDER BY int_caja_tn DESC, int_paquete_tn DESC  LIMIT 1"," * " );
            ?> 
            <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
              <td id="dato2"><input name="borrar[]" type="checkbox" value="<?php echo $row_numeracion['int_op_n']; ?>" /></td>
              <td id="dato2"><a href="view_index.php?c=csellado&a=Numeracion&mi_var_array=<?php echo $compactada; ?>&int_op_tn=<?php echo $row_url['int_op_n']=='' ? $row_numeracion['int_op_n'] : $row_url['int_op_n'];?>&int_caja_tn=<?php echo $row_url['int_caja_n']=='' ? $row_numeracion['int_caja_n'] : $row_url['int_caja_n'];?>" target="new" style="text-decoration:none; color:#000000"><strong><?php echo $row_numeracion['int_op_n']; ?></strong></a></td>
              <td nowrap="nowrap" id="dato2"><a href="view_index.php?c=csellado&a=Numeracion&mi_var_array=<?php echo $compactada; ?>&int_op_tn=<?php echo $row_url['int_op_n']=='' ? $row_numeracion['int_op_n'] : $row_url['int_op_n'];?>&int_caja_tn=<?php echo $row_url['int_caja_n']=='' ? $row_numeracion['int_caja_n'] : $row_url['int_caja_n'];?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_numeracion['cod_ref_n'];?></a></td>
              <td id="dato2"><a href="view_index.php?c=csellado&a=Numeracion&mi_var_array=<?php echo $compactada; ?>&int_op_tn=<?php echo $row_url['int_op_n']=='' ? $row_numeracion['int_op_n'] : $row_url['int_op_n'];?>&int_caja_tn=<?php echo $row_url['int_caja_n']=='' ? $row_numeracion['int_caja_n'] : $row_url['int_caja_n'];?>" target="new" style="text-decoration:none; color:#000000"><?php echo  $row_numeracion['int_caja_n']; ?></a></td>
              <td id="dato2"><a href="view_index.php?c=csellado&a=Numeracion&mi_var_array=<?php echo $compactada; ?>&int_op_tn=<?php echo $row_url['int_op_n']=='' ? $row_numeracion['int_op_n'] : $row_url['int_op_n'];?>&int_caja_tn=<?php echo $row_url['int_caja_n']=='' ? $row_numeracion['int_caja_n'] : $row_url['int_caja_n'];?>" target="new" style="text-decoration:none; color:#000000"><?php echo  $row_numeracion['int_hasta_n']; ?></a></td>            
              <td id="dato2"><a href="view_index.php?c=csellado&a=Numeracion&mi_var_array=<?php echo $compactada; ?>&int_op_tn=<?php echo $row_url['int_op_n']=='' ? $row_numeracion['int_op_n'] : $row_url['int_op_n'];?>&int_caja_tn=<?php echo $row_url['int_caja_n']=='' ? $row_numeracion['int_caja_n'] : $row_url['int_caja_n'];?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_numeracion['int_undxpaq_n']; ?></a></td>
              <td id="dato2"><a href="view_index.php?c=csellado&a=Numeracion&mi_var_array=<?php echo $compactada; ?>&int_op_tn=<?php echo $row_url['int_op_n']=='' ? $row_numeracion['int_op_n'] : $row_url['int_op_n'];?>&int_caja_tn=<?php echo $row_url['int_caja_n']=='' ? $row_numeracion['int_caja_n'] : $row_url['int_caja_n'];?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_numeracion['int_undxcaja_n']; ?></a></td>
              <td id="dato2" nowrap><a href="view_index.php?c=csellado&a=Numeracion&mi_var_array=<?php echo $compactada; ?>&int_op_tn=<?php echo $row_url['int_op_n']=='' ? $row_numeracion['int_op_n'] : $row_url['int_op_n'];?>&int_caja_tn=<?php echo $row_url['int_caja_n']=='' ? $row_numeracion['int_caja_n'] : $row_url['int_caja_n'];?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_numeracion['fecha_ingreso_n']; ?></a></td> 
              <td nowrap="nowrap" id="dato2"><a href="view_index.php?c=csellado&a=Numeracion&mi_var_array=<?php echo $compactada; ?>&int_op_tn=<?php echo $row_url['int_op_n']=='' ? $row_numeracion['int_op_n'] : $row_url['int_op_n'];?>&int_caja_tn=<?php echo $row_url['int_caja_n']=='' ? $row_numeracion['int_caja_n'] : $row_url['int_caja_n'];?>" target="new" style="text-decoration:none; color:#000000">
                <?php 
                $id_op=$row_numeracion['int_op_n'];
                $sqln="SELECT cliente.nombre_c FROM Tbl_orden_produccion,cliente WHERE Tbl_orden_produccion.id_op='$id_op' AND Tbl_orden_produccion.str_nit_op=cliente.nit_c"; 
                $resultn=mysql_query($sqln); 
                $numn=mysql_num_rows($resultn); 
                if($numn >= '1') 
                  { $nombre_cliente_c=mysql_result($resultn,0,'nombre_c'); $ca = htmlentities ($nombre_cliente_c); echo $ca; }
                else { echo ""; } ?>
              </a><a href="sellado_control_numeracion_edit.php?id_op=<?php echo $row_numeracion['int_op_n'];?>&int_caja_tn=<?php echo $row_numeracion['int_caja_n'];?>" target="new" style="text-decoration:none; color:#000000"></a>
            </td> 
            <?php //if($_SESSION['id_usuario']=='23') : ?>  
    <!--  <td> <a href="view_index.php?c=csellado&a=Numeracion&mi_var_array=<?php echo $compactada; ?>&int_op_tn=<?php echo $row_url['int_op_n']=='' ? $row_numeracion['int_op_n'] : $row_url['int_op_n'];?>&int_caja_tn=<?php echo $row_url['int_caja_n']=='' ? $row_numeracion['int_caja_n'] : $row_url['int_caja_n'];?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_numeracion['int_op_n']; ?></a> 
    </td> -->
    <?php //endif; ?>     
  </tr>
<?php } ?>
</table>
</form>
<table border="0" width="50%" align="center">
  <tr>
    <td width="23%" align="center" id="dato2"><?php if ($pageNum_numeracion > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_numeracion=%d%s", $currentPage, 0, $queryString_numeracion); ?>">Primero</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="31%" align="center" id="dato2"><?php if ($pageNum_numeracion > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_numeracion=%d%s", $currentPage, max(0, $pageNum_numeracion - 1), $queryString_numeracion); ?>">Anterior</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="23%" align="center" id="dato2"><?php if ($pageNum_numeracion < $totalPages_numeracion) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_numeracion=%d%s", $currentPage, min($totalPages_numeracion, $pageNum_numeracion + 1), $queryString_numeracion); ?>">Siguiente</a>
          <?php } // Show if not last page ?>
    </td>
    <td width="23%" align="center" id="dato2"><?php if ($pageNum_numeracion < $totalPages_numeracion) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_numeracion=%d%s", $currentPage, $totalPages_numeracion, $queryString_numeracion); ?>">&Uacute;ltimo</a>
          <?php } // Show if not last page ?>
    </td>
  </tr>
</table></td>
  </tr> 
 </div> <!-- contenedor -->

  </div>
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

 $(document).ready(function(){  

 
  $('#id_op').select2({ 
        ajax: {
            url: "select3/proceso.php",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    palabraClave: params.term, // search term
                    var1:"id_op",//campo normal para usar
                    var2:"tbl_numeracion tn left JOIN tbl_orden_produccion top ON top.id_op=tn.int_op_n",//tabla
                    var3:"",//where
                    var4:"ORDER BY tn.int_op_n DESC",
                    var5:"id_op",//clave
                    var6:"id_op"//columna a buscar
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
        
 
        $('#cod_ref_n').select2({ 
            ajax: {
                url: "select3/proceso.php",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        palabraClave: params.term, // search term
                        var1:"cod_ref_n",
                        var2:"tbl_numeracion",
                        var3:"",//where
                        var4:"GROUP BY cod_ref_n  ORDER BY CAST(cod_ref_n AS int)  DESC",
                        var5:"cod_ref_n",
                        var6:"cod_ref_n"//columna a buscar
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


        $('#cliente').select2({ 
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
                        var4:"ORDER BY nombre_c ASC",
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
 
 });  


</script>

<?php
mysql_free_result($usuario);
mysql_free_result($lista);
mysql_free_result($lista2);
mysql_free_result($numeracion);
mysql_free_result($all_numeracion);

/*mysql_close($conexion1);

unset($usuario,$conexion1); 
unset($lista,$conexion1);
unset($lista2,$conexion1);
unset($numeracion,$conexion1);

*/

?>
