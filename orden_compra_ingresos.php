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

$maxRows_ingresos = 20;
$pageNum_ingresos = 0;
if (isset($_GET['pageNum_ingresos'])) {
  $pageNum_ingresos = $_GET['pageNum_ingresos'];
}
$startRow_ingresos = $pageNum_ingresos * $maxRows_ingresos;

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}

$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario); 

$row_ingresos = $conexion->buscarListar("tblingresos","*","ORDER BY oc_ing DESC","",$maxRows_ingresos,$pageNum_ingresos,"" );
 
$row_lista = $conexion->llenaSelect('TblIngresos','','ORDER BY oc_ing DESC'); 

$row_codigo = $conexion->llenaSelect('insumo',' ','ORDER BY codigo_insumo DESC');  
 
$row_ano = $conexion->llenaSelect('anual','','ORDER BY anual DESC');

$row_mes = $conexion->llenaSelect('mensual','','ORDER BY id_mensual DESC');

$row_dia = $conexion->llenaSelect('dias','','ORDER BY dia DESC');
 

if (isset($_GET['totalRows_ingresos'])) {
  $totalRows_ingresos = $_GET['totalRows_ingresos'];
} else {
  $totalRows_ingresos = $conexion->conteo('tblingresos'); 
} 
$totalPages_ingresos = ceil($totalRows_ingresos/$maxRows_ingresos)-1; 

//session_start();
 ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
          <table style="width: 80%"><!-- class="table table-bordered table-sm" -->
            <tr>
             <td align="center">
               <div class="row-fluid">
                 <div class="span8 offset2"> <!--span8 offset2   esto da el tamaño pequeño -->
                   <div class="panel panel-primary">
                    <div class="panel-heading" align="left" ></div><!--color azul-->
                    <div class="row" >
                        <div class="span12">&nbsp;&nbsp;&nbsp; <img src="images/cabecera.jpg"></div>
                        <div class="panel-heading"><h3>COMPRAS</h3></div>
                    </div>
                     <div class="panel-heading" align="left" ></div><!--color azul-->
                      <div id="cabezamenu">
                       <ul id="menuhorizontal">
                         <li><?php echo $row_usuario['nombre_usuario']; ?></li>
                         <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                         <li><a href="menu.php">MENU PRINCIPAL</a></li>
                         <li><a href="compras.php">GESTION COMPRAS</a></li>
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
<form action="orden_compra_ingresos2.php" method="get" name="consulta">
<table class="table table-bordered table-sm"> 
<tr>
  <td id="fuente2">
    <select class="selectsMedio busqueda" name="n_oc" id="n_oc">
     <option value="0">O.C.</option>
           <?php  foreach($row_lista as $row_lista ) { ?>
        <option value="<?php echo $row_lista['oc_ing']?>"><?php echo $row_lista['oc_ing']?></option>
    
    <?php } ?>
    </select>

    <select class="selectsMedio busqueda" name="codigo" id="codigo">
     <option value="0">CODIGO</option>
           <?php  foreach($row_codigo as $row_codigo ) { ?>
        <option value="<?php echo $row_codigo['id_insumo']?>"><?php echo $row_codigo['codigo_insumo']?></option>
    
    <?php } ?>
    </select>


    <select class="selectsMedio busqueda" name="anual" id="anual">
     <option value="0">A&Ntilde;O</option>
           <?php  foreach($row_ano as $row_ano ) { ?>
       <option value="<?php echo $row_ano['anual']?>"><?php echo $row_ano['anual']?></option>
    
    <?php } ?>
    </select>

  <select class="selectsMedio busqueda" name="mes" id="mes">
   <option value="0">MES</option>
         <?php  foreach($row_mes as $row_mes ) { ?>
     <option value="<?php echo $row_mes['id_mensual']?>"><?php echo $row_mes['mensual']?></option>
  
  <?php } ?>
  </select>

  
<!--dias -->
  <select class="selectsMedio busqueda" name="dia" id="dia">
   <option value="0">DIA</option>
         <?php  foreach($row_dia as $row_dia ) { ?>
     <option value="<?php echo $row_dia['dia']?>"><?php echo $row_dia['dia']?></option>
  
  <?php } ?>
  </select>
 
    <input type="submit" name="Submit" value="FILTRO" class="botonGMini" onClick="if(consulta.n_oc.value=='0' && consulta.id_p.value=='0' && consulta.anual.value=='0') { alert('DEBE SELECCIONAR UNA OPCION'); }"/>
  <input type="button" id="excel" name="excel" class="botonDel" value="Descarga Excel" onclick="myFunction()">
</td>
  </tr>
 
</table>
</form>
<form action="delete_listado.php" method="get" name="seleccion">
<table class="table table-bordered table-sm">
     <tr>
    <td colspan="4" id="dato1"><input name="borrado" type="hidden" id="borrado" value="49" />
      <input name="Input" type="submit" value="Delete"/>
      </td>
      <td colspan="4"><?php if (isset($_GET['id'])) {$id= $_GET['id'];}else{$id= '';} 
      if($id == '1') { ?><div id="acceso1"><?php echo "ELIMINACION COMPLETA"; ?></div> <?php }
      if($id == '0') { ?><div id="numero1"><?php echo "NO HA SELECCIONADO"; ?></div> <?php }?></td>
      <td id="titulo4">
        <a href="orden_compra_ingresos.php"><img src="images/c.gif" style="cursor:hand;" alt="COMPRAS" title="COMPRAS" border="0" /></a>
        <a href="despacho_listado1_oc.php"><img src="images/r.gif" style="cursor:hand;" alt="REMISIONES" title="REMISIONES" border="0" /></a>
        <a href="orden_compra.php"><img src="images/ciclo1.gif" alt="RESTAURAR" border="0" style="cursor:hand;"/><img src="images/o.gif" alt="ORDENES DE COMPRAS" border="0" title="ORDENES DE COMPRAS" style="cursor:hand;"/></a></td>
      </tr>   
             <tr id="tr1">
              <td id="titulo4"><input name="chulo1" type="checkbox" onClick="if(seleccion.chulo1.checked) { seleccionar_todo() } else{ deseleccionar_todo() } "/></td>
              <td id="titulo4">O.C</td>
              <td id="titulo4">CODIGO</td>
              <td id="titulo4">DESCRIPCION</td>
              <td id="titulo4">MEDIDA</td>
              <td id="titulo4">CANTIDAD</td>
              <td id="titulo4">VALOR $</td>
              <td id="titulo4">FECHA</td>
              <td id="titulo4">ESTADO</td>
            </tr>
            <?php foreach($row_ingresos as $row_ingresos) {  ?>
            <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
            <td id="dato2"><input name="borrar[]" type="checkbox" value="<?php echo $row_ingresos['id_ing']; ?>" /></td>
            <td id="dato2"><?php echo $row_ingresos['oc_ing'];?></td>
            <td id="dato2"><?php 
            $insumo=$row_ingresos['id_insumo_ing'];
            $numins = $conexion->llenarCampos("insumo", "WHERE id_insumo=$insumo ", " ", "codigo_insumo,descripcion_insumo,medida_insumo"); 

            if($numins >='1')
            { 
              $insumo_nombre = $numins['descripcion_insumo'];
              $insumo_medida = $numins['medida_insumo'];
              $codigo_insumo = $numins['codigo_insumo'];
              echo $codigo_insumo;
            } ?></td>
            <td id="dato2"><?php echo $insumo_nombre;?></td>
            <td id="dato2">
              <?php $medida_insumo=$insumo_medida;
              $numedida = $conexion->llenarCampos("medida", "WHERE id_medida=$medida_insumo ", " ", "nombre_medida"); 
              
              if($numedida >='1') { 
                $medida_ins= $numedida['nombre_medida']; 
              }
              echo $medida_ins; ?>
              
            </td>
            <td id="dato2"><?php echo $row_ingresos['ingreso_ing'];?></td>
            <td id="dato2"><?php echo $row_ingresos['valor_und_ing'];?></td>
            <td nowrap id="dato2"><?php echo $row_ingresos['fecha_ing'];?></td>
            <td id="dato2"><img src="images/ok.gif" alt="INGRESOS OK" width="20" height="16" style="cursor:hand;" title="INGRESOS OK" border="0"></td>
          </tr>
        <?php } ?>
</table>
</form>
<table border="0" width="50%" align="center">
  <tr>
    <td width="23%" id="dato2"><?php if ($pageNum_ingresos > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_ingresos=%d%s", $currentPage, 0, $queryString_ingresos); ?>">Primero</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="31%" id="dato2"><?php if ($pageNum_ingresos > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_ingresos=%d%s", $currentPage, max(0, $pageNum_ingresos - 1), $queryString_ingresos); ?>">Anterior</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="23%" id="dato2"><?php if ($pageNum_ingresos < $totalPages_ingresos) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_ingresos=%d%s", $currentPage, min($totalPages_ingresos, $pageNum_ingresos + 1), $queryString_ingresos); ?>">Siguiente</a>
          <?php } // Show if not last page ?>
    </td>
    <td width="23%" id="dato2"><?php if ($pageNum_ingresos < $totalPages_ingresos) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_ingresos=%d%s", $currentPage, $totalPages_ingresos, $queryString_ingresos); ?>">&Uacute;ltimo</a>
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
function myFunction() { 
    var ano = document.getElementById("anual").value;
    var mes = document.getElementById("mes").value;
    var dia = document.getElementById("dia").value;
 window.location.href = "orden_compra_excel.php?anual="+ano+'&mes='+mes+'&dia='+dia;
}
</script>
<?php
mysql_free_result($usuario);

mysql_free_result($ingresos);

mysql_free_result($lista);

mysql_free_result($codigo);

mysql_free_result($ano);

mysql_free_result($mes);
?>