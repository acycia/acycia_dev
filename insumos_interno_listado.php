<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
require(ROOT_BBDD);
?>
<?php

if (!isset($_SESSION)) {
  session_start();
}
/*//initialize the session

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

$maxRows_registros = 10;
$pageNum_registros = 0;
if (isset($_GET['pageNum_registros'])) {
  $pageNum_registros = $_GET['pageNum_registros'];
}

$startRow_registros = $pageNum_registros * $maxRows_registros;

$conexion = new ApptivaDB();

$colname_usuario = "1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}

$row_usuario = $conexion->buscar('usuario', 'usuario', $colname_usuario);

$proveedores = $conexion->llenaSelect('proveedor', '', 'ORDER BY proveedor_p ASC');

$colname_cliente = "-1";
$colname_entrada = "-1";
$colname_busqueda = "-1";
$busqueda = $_GET["busqueda"];

$colname_busqueda = "-1";
if (isset($_GET["valor"])) {
  $colname_busqueda = (get_magic_quotes_gpc()) ? $_GET["valor"] : addslashes($_GET["valor"]);
  $registros = $conexion->buscarListar("tbl_remision_interna", "*", "ORDER BY id_remision DESC", "", $maxRows_registros, $pageNum_registros, "where $busqueda ='$colname_busqueda'");
} else {
  $registros = $conexion->buscarListar("tbl_remision_interna", "*", "ORDER BY id_remision DESC", "", $maxRows_registros, $pageNum_registros, "");
}

if (isset($_GET['totalRows_registros'])) {
  $totalRows_registros = $_GET['totalRows_registros'];
} else {
  $totalRows_registros = $conexion->conteo('tbl_remision_interna');
}
$totalPages_registros = ceil($totalRows_registros / $maxRows_registros) - 1;

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
  <title>SISADGE AC &amp; CIA</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link rel="stylesheet" type="text/css" href="css/general.css" />
  <link rel="stylesheet" type="text/css" href="css/formato.css" />
  <link rel="stylesheet" type="text/css" href="css/desplegable.css" />
  <script type="text/javascript" src="js/usuario.js"></script>
  <script type="text/javascript" src="js/formato.js"></script>
  <!-- sweetalert -->
  <script src="librerias/sweetalert/dist/sweetalert.min.js"></script>
  <link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">
  <!-- jquery -->
  <script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
  <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>

  <!-- css Bootstrap-->
  <link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>

<body>
  <div class="spiffy_content"> <!-- este define el fondo gris de lado a lado si se coloca dentro de tabla inicial solamente coloca borde gris -->
    <div align="center">
      <table id="tabla1">
        <tr>
          <td align="center">
            <div class="row-fluid">
              <div class="span8 offset2"> <!--span8 offset2   esto da el tamaño pequeño -->
                <div class="panel panel-primary">
                  <div class="panel-heading">
                    <h2>INSUMOS INTERNO ENTRADA-SALIDA</h2>
                  </div>
                  <div id="cabezamenu">
                    <ul id="menuhorizontal">
                      <li id="nombreusuario"><?php echo $row_usuario['nombre_usuario']; ?></li>
                      <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                      <li><a href="menu.php">MENU PRINCIPAL</a></li>
                      <li><a href="insumos.php/">VER INSUMOS</a></li>
                      <li><a href="insumos_interno_listado.php">LISTADO ENTRADAS</a></li>
                    </ul>
                  </div>
                  <div class="panel-body">
                    <br>
                    <div class="container">
                      <div class="row">
                        <div class="span12">
                          <table id="tabla2">
                            <tr>
                              <td rowspan="4" id="fondo2"><img src="images/logoacyc.jpg"></td>
                            </tr>
                            <tr>
                              <td id="subtitulo">
                                REMISION SALIDA - ENTRADA
                              </td>
                            </tr>
                            <tr>
                              <td id="numero2">
                                <h5 id="numero2"> </h5>
                              </td>
                            </tr>
                          </table>
                        </div>
                      </div>
                      <br>
                      <br>
                      <!-- COLUMNAS -->
                      <form id="envio" name="envio" action="envio.php" method="get">
                        <fieldset style="border: 1px solid #000; padding:10px">
                          <legend style="border: 0.5px solid">BUSQUEDA</legend>

                          <div class="row" style="padding:10px; justify-content:space-around">
                            <div class="span3">
                              <div>
                                <strong>CLIENTE:</strong>
                              </div>
                              <select name="cliente" id="cliente" class="selectsMedio buscar">
                                <option value="" <?php if (!(strcmp("", $_GET['valor']))) {
                                                    echo "selected=\"selected\"";
                                                  } ?>>Seleccione Cliente</option>
                                <?php foreach ($proveedores as $row_proveedores) { ?>
                                  <option value="<?php echo $row_proveedores['id_p']; ?>" <?php if (!(strcmp($row_proveedores['id_p'], $_GET['valor']))) {
                                                                                            echo "selected=\"selected\"";
                                                                                          } ?>><?php echo htmlentities($row_proveedores['proveedor_p']); ?> </option>
                                <?php } ?>
                              </select>
                            </div>
                            <div class="span3">
                              <div>
                                <strong>Entrada - Salida</strong>
                              </div>
                              <select id="entrada" name="entrada" class="selectsMini buscar">
                                <option value="" <?php if (!(strcmp("", $_GET['valor']))) {
                                                    echo "selected=\"selected\"";
                                                  } ?>>Seleccione</option>
                                <option value="Entrada" <?php if (!(strcmp("Entrada", $_GET['valor']))) {
                                                          echo "selected=\"selected\"";
                                                        } ?>>Entrada</option>
                                <option value="Salida" <?php if (!(strcmp("Salida", $_GET['valor']))) {
                                                          echo "selected=\"selected\"";
                                                        } ?>>Salida</option>
                              </select>
                            </div>
                            <div class="span3">
                              <div>
                                <strong>FECHAS: </strong>
                              </div>
                              <input type="date" id="fecha" name="fecha" value="<?php echo $_GET['valor']; ?>" class='campostext buscar'>
                            </div>
                            <!-- <div class="span3" style="text-align: center;">
                               <button id="btnBuscar" name="btnBuscar"  class="botonGMini buscar" autofocus="" >BUSCAR</button> 
                            </div> -->
                          </div>
                        </fieldset>
                      </form>

                      <br>
                      <br>
                      <!-- grid -->

                      <div class="container-fluid">
                        <h3 id="dato2"><strong>REMISIONES - INGRESADAS</strong></h3>
                        <div style="text-align: right;">CREAR NUEVA REMISION <a href="insumos_interno_entrada_salida.php"><img src="images/masazul.PNG" alt="ADD REMISION INTERNA" title="ADD REMISION INTERNA" border="0" style="cursor:hand;" /></a></div>
                        <hr>
                        <div class="row align-items-start">
                          <div class="col-sm-1"><strong>ITEMS</strong></div>
                          <div class="col-sm-2"><strong>ID REMISION</strong></div>
                          <div class="col-sm-3"><strong>CLIENTE</strong></div>
                          <div class="col-sm-1"><strong>ENTRADA</strong></div>
                          <div class="col-sm-2"><strong>TELEFONO</strong></div>
                          <div class="col-sm-2"><strong>FECHA</strong></div>
                          <div class="col-sm-1"><strong>VER</strong></div>
                        </div>
                        <?php foreach ($registros as $row_registros) {  ?>
                          <div class="row celdaborde1">
                            <div class="col-sm-1" id="fondo_2">
                              <p> - </p>
                            </div>
                            <div class="col-sm-2" id="fondo_2">
                              <p><a href="insumos_interno_entrada_salida_edit.php?id_remision=<?php echo $row_registros['id_remision']; ?>" target="_top" style="text-decoration:none; color:#000000"> <?php echo $row_registros['id_remision']; ?></a></p>
                            </div>
                            <div class="col-sm-3" id="fondo_2">
                              <p><a href="insumos_interno_entrada_salida_edit.php?id_remision=<?php echo $row_registros['id_remision']; ?>" target="_top" style="text-decoration:none; color:#000000">
                                  <?php
                                  $row_ver_nuevo = $conexion->buscar('proveedor', 'id_p', $row_registros['cliente']);
                                  echo $row_ver_nuevo['proveedor_p'] == '' ? 'N/A' : $row_ver_nuevo['proveedor_p'];

                                  ?>
                                </a></p>
                            </div>
                            <div class="col-sm-1" id="fondo_2">
                              <p><a href="insumos_interno_entrada_salida_edit.php?id_remision=<?php echo $row_registros['id_remision']; ?>" target="_top" style="text-decoration:none; color:#000000"> <?php echo $row_registros['entrada']; ?></a></p>
                            </div>
                            <div class="col-sm-2" id="fondo_2">
                              <p><a href="insumos_interno_entrada_salida_edit.php?id_remision=<?php echo $row_registros['id_remision']; ?>" target="_top" style="text-decoration:none; color:#000000"> <?php echo $row_registros['telefono']; ?></a></p>
                            </div>
                            <div class="col-sm-2" id="fondo_2">
                              <p><a href="insumos_interno_entrada_salida_edit.php?id_remision=<?php echo $row_registros['id_remision']; ?>" target="_top" style="text-decoration:none; color:#000000"> <?php echo $row_registros['fecha']; ?></a></p>
                            </div>
                            <div class="col-sm-1" id="fondo_2">
                              <p><a href="insumos_interno_entrada_salida_edit.php?id_remision=<?php echo $row_registros['id_remision']; ?>" target="_top" style="text-decoration:none; color:#000000"><img src="images/pincel.PNG" alt="EDITAR" title="EDITAR" border="0" style="cursor:hand;" width="20" height="18" /> </a></p>
                            </div>
                          </div>
                        <?php  } ?>
                      </div>
                      <!-- tabla para paginacion opcional -->
                      <table border="0" width="50%" align="center">
                        <tr>
                          <td width="23%" id="dato2"><?php if ($pageNum_registros > 0) { // Show if not first page 
                                                      ?>
                              <a href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, 0, $queryString_registros); ?>">Primero</a>
                            <?php } // Show if not first page 
                            ?>
                          </td>
                          <td width="31%" id="dato2"><?php if ($pageNum_registros > 0) { // Show if not first page 
                                                      ?>
                              <a href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, max(0, $pageNum_registros - 1), $queryString_registros); ?>">Anterior</a>
                            <?php } // Show if not first page 
                            ?>
                          </td>
                          <td width="23%" id="dato2"><?php if ($pageNum_registros < $totalPages_registros) { // Show if not last page 
                                                      ?>
                              <a href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, min($totalPages_registros, $pageNum_registros + 1), $queryString_registros); ?>">Siguiente</a>
                            <?php } // Show if not last page 
                            ?>
                          </td>
                          <td width="23%" id="dato2"><?php if ($pageNum_registros < $totalPages_registros) { // Show if not last page 
                                                      ?>
                              <a href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, $totalPages_registros, $queryString_registros); ?>">&Uacute;ltimo</a>
                            <?php } // Show if not last page 
                            ?>
                          </td>
                        </tr>
                      </table>


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

  <!-- js Bootstrap-->
  <!-- <script src="bootstrap-4/js/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="bootstrap-4/js/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="bootstrap-4/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script> -->

</body>

</html>

<script type="text/javascript">
  $(document).ready(function() {

    $(".buscar").change(function() {
      // form = $("#envio").serialize();
      //var name = document.getElementsByName("buscar")[0];
      var name = $(this).attr('name');
      var value = $(this).val();
      url = '<?php echo BASE_URL; ?>';
      if (value != "") {
        window.location.assign(url + 'insumos_interno_listado.php?busqueda=' + name + '&valor=' + value)
      } else {
        window.location.assign(url + 'insumos_interno_listado.php')
        
      }
    });
  });
  /*$("#ejemplo2").change(function(){   });*/
</script>


<?php
mysql_free_result($usuario);

?>