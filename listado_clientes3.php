<?php
   require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
   require (ROOT_BBDD); 
?> 
<?php require_once('Connections/conexion1.php'); ?>
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

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario); 
 
//AQUI EMPIEZA EL CODIGO PARA EVALUAR LOS TRES FILTROS ENVIADOS POR GET

$maxRows_registros = 40;
$pageNum_registros = 0;
if (isset($_GET['pageNum_registros'])) {
  $pageNum_registros = $_GET['pageNum_registros'];
}
$startRow_registros = $pageNum_registros * $maxRows_registros;

mysql_select_db($database_conexion1, $conexion1);
$estado_c= $_GET['estado_c'];
$tipo_c= $_GET['tipo_c'];
$revisado_c= $_GET['revisado_c'];
 
    //PARA CARGAR LA VARIABLE EN SU POSICION INICIAL
/*     $estado_c= $_GET['estado_c'];
     $tipo_c= $_GET['tipo_c'];
     $nit_c= $_GET['nit_c'];
     if ($estado_c == NULL){ 
     $estado_c = '0';
     }
     if ($tipo_c == NULL){ 
     $tipo_c = '0';
     }
     if ($nit_c == NULL){ 
     $nit_c = '0';
     }   */  
//if(isset($estado_c)||  isset($tipo_c)|| isset($revisado_c))
?>
<?php 
//toda la tabla
    $row_registros=$conexion->buscarListar("cliente","*","ORDER BY nombre_c ASC","",$maxRows_registros,$pageNum_registros,"" ); 

 
   if (isset($_GET['estado_c']) || isset($_GET['tipo_c']) || isset($_GET['nit_c'])){

     if ($_GET['estado_c'] != '' && $_GET['tipo_c'] == '' && $_GET['nit_c'] == '' ){
     //imresiones por estado 
      $row_registros = $conexion->buscarListar("cliente","*","ORDER BY estado_c,nombre_c ASC","",$maxRows_registros,$pageNum_registros," WHERE estado_c='".$_GET['estado_c']."'  " ); 
 
     }else if ($_GET['tipo_c'] != '' && $_GET['estado_c'] == ''&& $_GET['nit_c'] == ''){
     //imresion por tipo
       $row_registros = $conexion->buscarListar("cliente","*","ORDER BY estado_c,nombre_c ASC","",$maxRows_registros,$pageNum_registros," WHERE tipo_c='".$_GET['tipo_c']."' " );
      
     }else if($_GET['nit_c'] != '' && $_GET['tipo_c'] == ''&& $_GET['estado_c'] == ''){
     //imresion por NIT
       $row_registros = $conexion->buscarListar("cliente","*","ORDER BY nit_c ASC","",$maxRows_registros,$pageNum_registros," WHERE nit_c='".$_GET['nit_c']."'  " );     

     }
}
  
 

//CONSULTA AGREGADA PARA EL FILTRO POR ESTADO PARA EL DO WHILE

$row_numero = $conexion->llenaListas('cliente',"",'ORDER BY estado_c ASC',"DISTINCT estado_c");//tabla, condicion, orden, distinct

$row_t_cliente = $conexion->llenaListas('cliente',"",'ORDER BY tipo_c ASC',"DISTINCT tipo_c");//tabla, condicion, orden, distinct

$row_nit = $conexion->llenaListas('cliente',"",'ORDER BY nit_c DESC',"DISTINCT nit_c");//tabla, condicion, orden, distinct

$row_cliente =$conexion->llenaListas("cliente","","ORDER BY nombre_c ASC","*" );


if (isset($_GET['totalRows_registros'])) {
  $totalRows_registros = $_GET['totalRows_registros'];
} else {
  $totalRows_registros = $conexion->conteo('cliente'); 
} 
$totalPages_registros = ceil($totalRows_registros/$maxRows_registros)-1;


$queryString_registros = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_registros") == false && 
        stristr($param, "totalRows_registros") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_registros = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_registros = sprintf("&totalRows_registros=%d%s", $totalRows_registros, $queryString_registros);

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>SISADGE AC &amp; CIA</title>
<link rel="StyleSheet" href="css/general.css" type="text/css">
<script type="text/javascript" src="js/formato.js"></script>     
<script type="text/javascript" src="js/listado.js"></script>
<link rel="StyleSheet" href="css/formato.css" type="text/css">

  <link rel="stylesheet" type="text/css" href="css/desplegable.css" /> <!-- importante dejarlo imprime todo lo nuevo-->
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

<table id="tabla_borde_top">
  <tr>


    <td id="subtitulo3">
       <form action="listado_clientes3.php" method="GET" name="consulta">
         <?php echo "Estado del Cliente "; echo"</br>"; ?>
           <select name="estado_c" id="estado_c" class="combos2 busqueda selectsMedio">
               <option value="0"<?php if (!(strcmp("0", $_GET['estado_c']))) {echo "selected=\"selected\"";} ?>>Estado</option> 
                 
                    <?php foreach($row_numero as $row_numero ) { ?>
                      <option value="<?php echo $row_numero['estado_c']?>"<?php if (!(strcmp($row_numero['estado_c'], $_GET['estado_c']))) {echo "selected=\"selected\"";} ?>><?php echo $row_numero['estado_c']?></option>
                </option>
             <?php } ?>
           </select>
           <input type="submit" name="Submit" value="BUSQUEDA" />
       </form>
    </td>


    <td id="subtitulo3">
       <form action="listado_clientes3.php" method="GET" name="consulta2">
         <?php echo "Tipo de Cliente "; echo"</br>"; ?>
           <select class="combos2 busqueda selectsMedio" name="tipo_c" id="tipo_c">
             
               <option value="0"<?php if (!(strcmp("0", $_GET['tipo_c']))) {echo "selected=\"selected\"";} ?>>Tipo cliente</option>
                    <?php foreach($row_t_cliente as $row_t_cliente ) { ?>
                      <option value="<?php echo $row_t_cliente['tipo_c']?>"<?php if (!(strcmp($row_t_cliente['tipo_c'], $_GET['tipo_c']))) {echo "selected=\"selected\"";} ?>><?php echo $row_t_cliente['tipo_c']?></option>
                </option>
             <?php } ?>
           </select>
           <input type="submit" name="Submit" value="BUSQUEDA" />
       </form>
    </td>


    <td id="subtitulo3">
       <form action="listado_clientes3.php" method="GET" name="consulta3">
         <?php echo "Nit"; echo"</br>"; ?>
           <select class="combos2 busqueda selectsMedio" name="nit_c" id="nit_c">
             
               <option value="0"<?php if (!(strcmp("0", $_GET['nit_c']))) {echo "selected=\"selected\"";} ?>>Nit cliente</option>
                    <?php foreach($row_nit as $row_nit ) { ?>
                      <option value="<?php echo $row_nit['nit_c']?>"<?php if (!(strcmp($row_nit['nit_c'], $_GET['nit_c']))) {echo "selected=\"selected\"";} ?>><?php echo $row_nit['nit_c']?></option>
                </option>
             <?php } ?>
           </select>
           <input type="submit" name="Submit" value="BUSQUEDA" />
       </form>
    </td>


    <td id="subtitulo3">
       <form action="perfil_cliente_vista.php "target='_blank' method="GET" name="consulta4"> 
         <?php echo "Perfil del Cliente "; echo"</br>"; ?>
           <select class="combos2 busqueda selectsGrande" name="id_c" id="id_c">
             <option value="0"<?php if (!(strcmp("0", $_GET['id_c']))) {echo "selected=\"selected\"";} ?>>Cliente</option>
                    <?php foreach($row_cliente as $row_cliente ) { ?>
                      <option value="<?php echo $row_cliente['id_c']?>"<?php if (!(strcmp($row_cliente['id_c'], $_GET['id_c']))) {echo "selected=\"selected\"";} ?>><?php echo $row_cliente['nombre_c']?></option>
                </option>
             <?php } ?>
           </select>
           <input type="submit" name="Submit" value="BUSQUEDA" /> 
          <input name="campo" type="hidden" value=" " size="5" readonly><?php $tclientes=$totalRows_cliente;echo $tclientes." "."Clientes";?>
       </form>
    </td>
      
  </tr>
</table>
 

</head>
<body>
<div align="center">
<table id="tabla3">

<?php foreach($row_registros as $row_cliente_lista) {  ?> 
   <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF" bordercolor="#ACCFE8"> 
	  <td class="Estilo3"> <a href="perfil_cliente_vista.php?id_c= <?php echo $row_cliente_lista['id_c']; ?>&tipo_usuario=<?php echo $row_usuario['tipo_usuario']; ?>" target="_blank" style="text-decoration:none; color:#000000"> <?php echo $row_cliente_lista['nit_c']; ?> </a> </td>      
	  <td class="Estilo3"> <a href="perfil_cliente_vista.php?id_c= <?php echo $row_cliente_lista['id_c']; ?>&tipo_usuario=<?php echo $row_usuario['tipo_usuario']; ?>" target="_blank" style="text-decoration:none; color:#000000"> <?php echo $row_cliente_lista['nombre_c']; ?> </a> </td>
      <td class="Estilo3"> <a href="perfil_cliente_vista.php?id_c= <?php echo $row_cliente_lista['id_c']; ?>&tipo_usuario=<?php echo $row_usuario['tipo_usuario']; ?>" target="_blank" style="text-decoration:none; color:#000000"> <?php echo $row_cliente_lista['contacto_c']; ?> </a> </td>
      <td class="Estilo3"> <a href="perfil_cliente_vista.php?id_c= <?php echo $row_cliente_lista['id_c']; ?>&tipo_usuario=<?php echo $row_usuario['tipo_usuario']; ?>" target="_blank" style="text-decoration:none; color:#000000"> <?php echo $row_cliente_lista['direccion_c']; ?></a></td>
      <td class="Estilo4"> <a href="perfil_cliente_vista.php?id_c= <?php echo $row_cliente_lista['id_c']; ?>&tipo_usuario=<?php echo $row_usuario['tipo_usuario']; ?>" target="_blank" style="text-decoration:none; color:#000000"> <?php echo $row_cliente_lista['pais_c']; ?> / <?php echo $row_cliente_lista['ciudad_c']; ?> </a> </td>
      <td class="Estilo4"><a href="perfil_cliente_vista.php?id_c= <?php echo $row_cliente_lista['id_c']; ?>&tipo_usuario=<?php echo $row_usuario['tipo_usuario']; ?>" target="_blank" style="text-decoration:none; color:#000000"><?php echo $row_cliente_lista['telefono_c']; ?></a></td>
      <td class="Estilo4"><a href="perfil_cliente_vista.php?id_c= <?php echo $row_cliente_lista['id_c']; ?>&tipo_usuario=<?php echo $row_usuario['tipo_usuario']; ?>" target="_blank" style="text-decoration:none; color:#000000"><?php echo $row_cliente_lista['fax_c']; ?></a></td>    
      <td class="Estilo6">
	  <?php if($row_cliente_lista['bolsa_plastica_c']=='1') { echo "B"; } ?>
	  <?php if($row_cliente_lista['lamina_c']=='1') { echo "  L"; } ?>
	  <?php if($row_cliente_lista['cinta_c']=='1') { echo "  C"; } ?>
	  <?php if($row_cliente_lista['packing_list_c']=='1') { echo " P"; } ?>
      </td>
      <td class="Estilo6"><a href="perfil_cliente_vista.php?id_c= <?php echo $row_cliente_lista['id_c']; ?>&tipo_usuario=<?php echo $row_usuario['tipo_usuario']; ?>" target="_blank" style="text-decoration:none; color:#000000"><?php echo $row_cliente_lista['estado_c']; ?></a>
      </td> 
      <td  class="Estilo6" width="150"> 
              <a href="javascript:verFoto('archivosc/<?php echo $row_cliente_lista['referencias_bancarias_c'] ?>','610','490')">
                    <?php if($row_cliente_lista['referencias_bancarias_c']!=''){ ?>
                  <img src="images/arte.gif" alt="<?php echo $muestra;?>" title="DATOS PROYECCION" border="0" style="cursor:hand;"  /></a>
                    <?php }else{  } ?>

                    <a href="javascript:verFoto('archivosc/<?php echo $row_cliente_lista['camara_comercio_c'] ?>','610','490')">
                    <?php if($row_cliente_lista['camara_comercio_c']!=''){ ?>
                  <img src="images/arte.gif" alt="<?php echo $muestra2;?>" title="CAMARA" border="0" style="cursor:hand;"  /></a>
                    <?php }else{  } ?>

                    <a href="javascript:verFoto('archivosc/<?php echo $row_cliente_lista['balance_general_c'] ?>','610','490')">
                    <?php if($row_cliente_lista['balance_general_c']!=''){ ?>
                  <img src="images/arte.gif" alt="<?php echo $muestra3;?>" title="RUT" border="0" style="cursor:hand;"  /></a>
                    <?php }else{  } ?>
         </td>
      </tr>
    <?php } ?>

	 
</table>
</div>
<br>
        <!-- tabla para paginacion opcional -->
        <table border="0" width="50%" align="center">
          <tr>
            <td width="23%" id="dato2"><?php if ($pageNum_registros > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, 0, $queryString_registros); ?>">Primero</a>
            <?php } // Show if not first page ?>
          </td>
          <td width="31%" id="dato2"><?php if ($pageNum_registros > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, max(0, $pageNum_registros - 1), $queryString_registros); ?>">Anterior</a>
          <?php } // Show if not first page ?>
        </td>
        <td width="23%" id="dato2"><?php if ($pageNum_registros < $totalPages_registros) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, min($totalPages_registros, $pageNum_registros + 1), $queryString_registros); ?>">Siguiente</a>
        <?php } // Show if not last page ?>
      </td>
      <td width="23%" id="dato2"><?php if ($pageNum_registros < $totalPages_registros) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, $totalPages_registros, $queryString_registros); ?>">&Uacute;ltimo</a>
      <?php } // Show if not last page ?>
    </td>
  </tr>
</table>

</body>
</html>
<script>
    $(document).ready(function() { $(".combos2").select2(); });
</script>
<?php
mysql_free_result($usuario);mysql_close($conexion1);

mysql_free_result($numero);

mysql_free_result($cliente);

mysql_free_result($t_cliente);

mysql_free_result($nit);

mysql_close($conexion1);
?>
