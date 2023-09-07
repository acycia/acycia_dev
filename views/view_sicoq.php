<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
require(ROOT_BBDD);
?>
<?php
include('funciones/funciones_php.php'); //SISTEMA RUW PARA LA BASE DE DATOS 
?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF'] . "?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")) {
  $logoutAction .= "&" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) && ($_GET['doLogout'] == "true")) {
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
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup)
{
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
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("", $MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0)
    $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo . $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: " . $MM_restrictGoTo);
  exit;
}


$conexion = new ApptivaDB();
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
  <link href="select2/css/select2.min.css" rel="stylesheet" />
  <script src="select2/js/select2.min.js"></script>
  <link rel="stylesheet" type="text/css" href="css/general.css" />

  <!-- css Bootstrap hace mas grande el formato-->
  <link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>

<body>
  <script>
    $(document).ready(function() {
      $(".busqueda").select2();
    });
  </script>
  <form action="view_index.php?c=csicoq&a=Guardar" method="post" enctype="multipart/form-data" name="form1" id="form1">
    <div class="spiffy_content"> <!-- este define el fondo gris de lado a lado si se coloca dentro de tabla inicial solamente coloca borde gris -->
      <div align="center">
        <table style="width: 100%">
          <tr>
            <td align="center">
              <div class="row-fluid">
                <div class="span8 offset2"> <!--span8 offset2   esto da el tamaño pequeño -->
                  <div class="panel panel-primary">
                    <div class="panel-heading" align="left"></div><!--color azul-->
                    <div class="row">
                      <div class="span12">&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/cabecera.jpg"></div>
                      <div class="span12">
                        <h3> ENTRADA SALIDA SUSTANCIAS <?php echo $_GET['controladas'] == 'NO' ? $_GET['controladas'] : ''; ?> CONTROLADAS &nbsp;&nbsp;&nbsp; </h3>
                      </div>
                    </div>
                    <div class="panel-heading" align="left"></div><!--color azul-->
                    <div id="cabezamenu">
                      <ul id="menuhorizontal">
                        <li id="nombreusuario"><?php echo $_SESSION['Usuario']; ?></li>
                        <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                        <li><a href="menu.php">MENU PRINCIPAL</a></li>
                        <li><a target="_self" href="view_index.php?c=csicoq&a=Inicio&controladas=<?php echo $_GET['controladas'] == 'SI' ? 'NO' : 'SI'; ?> "><?php echo $_GET['controladas'] == 'SI' ? 'NO CONTROLADAS' : 'SI CONTROLADAS'; ?> </a></li>
                        <?php if ($_SESSION['superacceso']) : ?>
                          <li>
                            <!-- <a class="botonDel" href="?c=csicoqFA&a=Eliminar&columna=<?php echo $_GET['columna']; ?>&master=<?php echo $_GET['id']; ?>">DELETE</a> -->
                            <a class="botonDel" id="btnDelMaster" onclick='eliminar("<?php echo $_GET['id']; ?>","<?php echo $_GET['columna']; ?>","autorizacion", "?c=csicoq&a=Eliminar","1"  )' type="button">DELETE</a>
                          </li>
                        <?php endif; ?>
                      </ul>
                    </div>
                    <div class="panel-body">
                      <br>
                      <div><!--  SI QUITO  class="container" SE ALINEA A LA IZQUIERDA TODO EL CONTENIDO DE ESTE -->
                        <div class="row">
                          <div class="span12">
                            <table id="tabla2">
                              <tr>
                                <td id="subtitulo">
                                  CREAR - SICOQ
                                </td>
                                <td id="subtitulo">VERSIÓN: 01 </td>
                                <td id="subtitulo">Fecha Actual: <?php echo $fechaActual = date('Y-m-d'); ?></td>
                                <td><img src="images/impresor.gif" onclick="window.print();" style="cursor:hand;" alt="INPRIMIR" title="INPRIMIR"></td>
                              </tr>
                            </table>
                          </div>
                        </div>
                        <br>
                        <!-- grids -->
                        <div class="row">
                          <div class="span12">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <?php if ($_SESSION['superacceso']) : ?>
                              <button id='botondeenvio' type="submit" onclick="validarTodos(); return false;"><img type="image" style="width: 30px; height: 40px;" name="botondeenvio" id="botondeenvio" src="images/save3.PNG" alt="GUARDAR" title="GUARDAR"></button>&nbsp;&nbsp;&nbsp;&nbsp;
                              <button id='botondeeditar' type="button" onclick="editarform(); return false;"><img type="image" style="width: 30px; height: 40px;" name="botondeeditar" id="botondeeditar" src="images/editar.PNG" alt="EDITAR" title="EDITAR"></button>
                            <?php endif; ?>
                            <input name="proceso" id="proceso" value="sicoq" type="hidden">
                            <strong>AÑO: <?php echo date('Y') ?>&nbsp;&nbsp;&nbsp;<em><a target="_self" href="view_index.php?c=csicoq&a=Inicio&controladas=<?php echo $_GET['controladas'] == 'SI' ? 'NO' : 'SI'; ?> "><?php echo $_GET['controladas'] == 'SI' ? 'NO CONTROLADAS' : 'SI CONTROLADAS'; ?> >>></a></em>&nbsp;<em id="AlertUpdates"></em></strong>&nbsp;
                            <input name="anyo" id="anyo" value="<?php echo $_GET['columna'] == 'fecha_recepcion' ? $_GET['id'] : date('Y'); ?>" type="hidden">
                          </div>
                        </div><br>

                        <table class="table table-striped" id="items">

                          <?php foreach ($this->general as $general) {
                            $general;
                          } ?>
                          <?php foreach ($this->kilosmes as $kilosmes) {
                            $kilosmes;
                          } ?>
                          <tr>
                            <td colspan="6">
                              <?php if ($_GET['controladas'] != 'NO') : ?>
                                AUTORIZACION #
                                <input name="autorizacion" id="autorizacion" value="<?php echo $_GET['columna'] == 'autorizacion' ? $_GET['id'] : $general['autorizacion']; ?>" type="text" placeholder="autorizacion" size="20" required="required">
                              <?php else : ?>
                                CONSECUTIVO
                                <input name="autorizacion" id="autorizacion" value="<?php echo $_GET['columna'] == 'autorizacion' ? $_GET['id'] :  ceros($general['autorizacion']); ?>" type="text" placeholder="autorizacion" size="20" required="required">
                              <?php endif; ?>
                              FACTURA:<input name="facturabusqueda" id="facturabusqueda" value="" type="text" placeholder="factura busqueda" style="width: 100px;">&nbsp;&nbsp;
                              FECHA O AÑO:<input name="fechabusqueda" id="fechabusqueda" onblur="resetear()" value="<?php echo $_GET['columna'] == 'fecha_recepcion' ? $_GET['id'] : $general['fechabusqueda']; ?>" type="text" placeholder="AÑO" style="width: 100px;">&nbsp;&nbsp;
                              MES: <select name="mesBusqueda" id="mesBusqueda" >
                                <option value="">Seleccione</option>
                                <option value="01" <?php if (!(strcmp("01", $_GET['mes']))) {
                                                        echo "selected=\"selected\"";
                                                      } ?>>Enero</option>
                                <option value="02" <?php if (!(strcmp("02", $_GET['mes']))) {
                                                        echo "selected=\"selected\"";
                                                      } ?>>Febrero</option>
                                <option value="03" <?php if (!(strcmp("03", $_GET['mes']))) {
                                                        echo "selected=\"selected\"";
                                                      } ?>>Marzo</option>
                                <option value="04" <?php if (!(strcmp("04", $_GET['mes']))) {
                                                        echo "selected=\"selected\"";
                                                      } ?>>Abril</option>
                                <option value="05" <?php if (!(strcmp("05", $_GET['mes']))) {
                                                        echo "selected=\"selected\"";
                                                      } ?>>Mayo</option>
                                <option value="06" <?php if (!(strcmp("06", $_GET['mes']))) {
                                                        echo "selected=\"selected\"";
                                                      } ?>>Junio</option>
                                <option value="07" <?php if (!(strcmp("07", $_GET['mes']))) {
                                                        echo "selected=\"selected\"";
                                                      } ?>>Julio</option>
                                <option value="08" <?php if (!(strcmp("08", $_GET['mes']))) {
                                                        echo "selected=\"selected\"";
                                                      } ?>>Agosto</option>
                                <option value="09" <?php if (!(strcmp("09", $_GET['mes']))) {
                                                        echo "selected=\"selected\"";
                                                      } ?>>Septiembre</option>
                                <option value="10" <?php if (!(strcmp("10", $_GET['mes']))) {
                                                        echo "selected=\"selected\"";
                                                      } ?>>Octubre</option>
                                <option value="11" <?php if (!(strcmp("11", $_GET['mes']))) {
                                                        echo "selected=\"selected\"";
                                                      } ?>>Noviembre</option>
                                <option value="12" <?php if (!(strcmp("12", $_GET['mes']))) {
                                                        echo "selected=\"selected\"";
                                                      } ?>>Diciembre</option>
                              </select>
                            

                              <!-- SUSTANCIA:
                      <select name="sustanciabusqueda" id="sustanciabusqueda" class="selectsMMedio busqueda" >
                        <option value="">Nombre sustancia controlada</option>
                        <?php foreach ($this->insumo as $insumo) {  ?>
                          <option value="<?php echo $insumo['descripcion_insumo']; ?>"><?php echo htmlentities($insumo['descripcion_insumo']); ?> </option>
                        <?php } ?>
                      </select>&nbsp;&nbsp; -->

                            </td>
                          </tr>
                          <tr>
                            <td colspan="6">
                              CANTIDAD DE KILOS PERMITIDOS
                              <input name="kilospermitidosmes" id="kilospermitidosmes" class="kilospermitidosmes" step="0.1" value="<?php echo $general['kilospermitidosmes'] == '' ? $kilosmes['id'] : $general['kilospermitidosmes']; ?>" type="number" placeholder="kilos por mes" size="20" required="required" <?php if (!$_SESSION['superacceso']) : ?> readonly <?php endif; ?>> KILOS POR MES
                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                              TOTAL KILOS DISPONIBLE PARA COMPRA
                              <input name="kilosdisponiblescompra" id="kilosdisponiblescompra" class="kilosdisponiblescompra" step="0.1" value="<?php echo $general['kilosdisponiblescompra']; ?>" type="number" placeholder="kilos disponibles" size="20" required='required' readonly>
                              FECHA INICIO
                              <input name="fecha_inicio" id="fecha_inicio" <?php if (!$_SESSION['superacceso']) : ?> readonly type="text" <?php else : ?>type="date" <?php endif; ?> step="1" min="2020-01-01" placeholder="fecha_inicio" value="<?php echo $general['fecha_inicio'] == '' ? date('Y-m-d') : $general['fecha_inicio']; ?>" required="required" class="campostextMedio" style="width: 100px;">

                              FECHA VENCIMIENTO DIAS

                              <input name="fecha_vence" id="fecha_vence" <?php if (!$_SESSION['superacceso']) : ?> readonly type="text" <?php else : ?>type="date" <?php endif; ?> step="1" min="2020-01-01" placeholder="fecha_vence" value="<?php echo $general['fecha_vence'] == '' ? date('Y-m-d') : $general['fecha_vence']; ?>" required="required" class="campostextMedio" style="width: 100px;">
                              <?php
                              $fechaVencimiento = $general['fecha_vence'] == '' ? date('Y-m-d') : $general['fecha_vence'];
                              $diasaVencer =  RestarFechasNew($fechaActual, $fechaVencimiento); //en horas 
                              echo "Faltan " . $diasaVencer . " Dias Para el vencimiento";
                              ?>
                            </td>
                          </tr>
                          <tr>
                            <td colspan="6">
                              <table id="tabla3">
                                <tr>
                                  <td id="subtitulo">
                                    TOTAL INGRESOS
                                    <input name="totalingresados" id="totalingresados" class="totalingresados" step="0.001" value="<?php echo $kilosold['kilosold']; ?>" type="number" placeholder="total ingresados" size="20" readonly="readonly"> 
                                    <abbr title="Kilos sobrantes del Año Anterior">AÑO ANTERIOR:</abbr>
                                    <?php foreach ($this->kilosold as $kilosold) {
                                      $kilosold;  ?>
                                    <?php } ?>
                                    <input name="kilosold" id="kilosold" class="kilosold" step="0.001" value="<?php echo $kilosold['kilosold'] == '' ? 0 : $kilosold['kilosold']; ?>" type="number" placeholder="k. old" style="width: 60px;" readonly="readonly"> Registros: <?php echo $kilosold['cuantos']; ?>
                                  </td>
                                  <td id="subtitulo">
                                    TOTAL SALIDA
                                    <input name="totalsalida" id="totalsalida" class="totalsalida" step="0.001" value="<?php echo $general['totalsalida']; ?>" type="number" placeholder="total salida" size="20" readonly="readonly"> KILOS
                                  </td>
                                  <td id="subtitulo">
                                    TOTAL INVENTARIO AC
                                    <input name="totalinventario" id="totalinventario" class="totalinventario" step="0.001" value="<?php echo $general['totalinventario']; ?>" type="number" placeholder="total inventario" size="20" readonly="readonly"> KILOS
                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                          <tr>
                            <td><em style="display: none;  align-items: center; justify-content: center;color: red; " id="mensaje"></em><em>Nota: Para consultar años anteriores hagalo por el fintro de Año y pude ingresar el numero de AUTORIZACION en la casilla de ccit autorizacion </em> </td>
                          </tr>

                          <tbody>
                            <tr>
                              <td nowrap='nowrap'>

                                <div class="divSinMedio" id="itemspedido" role="alert" style="text-align: left;">
                                  <div class="row celdaborde1">
                                    <div style="width: 40px;"><strong></strong></div>
                                    <div style="width: 220px;"><strong>NOMBRE SUSTANCIA CONTROLADA</strong></div>
                                    <div style="width: 100px;"><strong>INGRESO KILOS</strong></div>
                                    <div style="width: 110px;"><strong>FECHA RECEP.</strong></div>
                                    <div style="width: 210px;"><strong>PROVEEDOR</strong></div>
                                    <div style="width: 90px;"><strong>COSTO UND.</strong></div>
                                    <div style="width: 110px;"><strong>FACTURA</strong></div>
                                    <div style="width: 90px;"><strong>ÁREA </strong></div>
                                    <div style="width: 120px;"><strong>FECHA SALIDA </strong></div>
                                    <div style="width: 70px;"><strong>KILOS SAL. </strong></div>
                                    <div style="width: 110px;"><strong>CODIFICADORA </strong></div>
                                    <div style="width: 110px;"><strong>AUTORIZACION </strong></div>
                                    <div style="width: 60px;"><strong>O.P </strong></div>
                                    <div style="width: 90px;"><strong>RESPONSABLE </strong></div>
                                    <div style="width: 90px;"><strong>REVISÓ </strong></div>
                                    <div style="width: 90px;"><strong>APROBADO </strong></div>
                                  </div>
                                  <div id="dinamicos" class="row celdaborde1">
                                    <div class="col-lg-12" id="fondo_2">
                                      <input type="checkbox" id="items" value="first_checkbox" disabled="disabled"></label>
                                      <select name="nombre" id="nombre" class="busqueda" style="width: 220px;">
                                        <option value="">Nombre sustancia controlada</option>
                                        <?php foreach ($this->insumo as $insumo) {  ?>
                                          <option value="<?php echo $insumo['descripcion_insumo']; ?>"><?php echo htmlentities($insumo['descripcion_insumo']); ?> </option>
                                        <?php } ?>
                                      </select>&nbsp;&nbsp;
                                      <input name="ingresokilos" id="ingresokilos" class="ingresokilos" step="0.001" value="0" type="number" placeholder="ingreso kilos" style="width: 80px;" onChange="totalIngreso();">&nbsp;&nbsp;
                                      <input name="fecha_recepcion" id="fecha_recepcion" type="date" step="1" min="2020-01-01" placeholder="fecha_recepcion" value="" class="campostextMedio">&nbsp;&nbsp;
                                      <select name="proveedor" id="proveedor" class=" busqueda" style="width: 200px;">
                                        <option value="">Seleccione Proveedor</option>
                                        <?php foreach ($this->proveedores as $proveedores) {  ?>
                                          <option value="<?php echo $proveedores['proveedor_p']; ?>"><?php echo htmlentities($proveedores['proveedor_p']); ?> </option>
                                        <?php } ?>
                                      </select>&nbsp;&nbsp;
                                      <input name="costound" id="costound" step="0.1" value="" type="number" placeholder="costo und" style="width: 80px;">&nbsp;&nbsp;
                                      <input name="factura" id="factura" value="" type="text" placeholder="factura" style="width: 100px;">&nbsp;&nbsp;
                                      <select name="area" style="width: 80px;">
                                        <?php foreach ($this->proceso as $proceso) {  ?>
                                          <option value="<?php echo $proceso['id_tipo_proceso']; ?>"><?php echo htmlentities($proceso['nombre_proceso']); ?> </option>
                                        <?php } ?>
                                      </select>&nbsp;&nbsp;
                                      <input name="fecha_salida" id="fecha_salida" type="date" step="1" min="2020-01-01" placeholder="fecha_salida" value="" size="10">&nbsp;&nbsp;
                                      <input name="salidakilos" id="salidakilos" class="salidakilos" step="0.001" value="0" type="number" placeholder="Cantidad" style="width: 70px;" onChange="totalIngreso();">&nbsp;&nbsp;
                                      <select name="numeradora" class="selectsMini" style="width: 90px;">
                                        <option value="0">0</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                      </select> &nbsp;&nbsp;
                                      <input name="ccit_autorizacion" id="ccit_autorizacion" class="ccit_autorizacion" value="" type="text" placeholder="ccit autorizacion" style="width: 100px;">&nbsp;&nbsp;
                                      <input name="op" id="op" class="op" value="" type="text" placeholder="o.p" required="required" style="width: 50px;">&nbsp;&nbsp;
                                      <input name="responsable" id="responsable" type="text" placeholder="responsable" value="<?php echo $general['responsable'] == '' ? $_SESSION['Usuario'] : $general['responsable']; ?>" class="campostextMedio" style="width: 80px;">&nbsp;&nbsp;
                                      <input name="revisado" id="revisado" type="text" placeholder="revisado" value="<?php echo $general['revisado'] == '' ? $_SESSION['Usuario'] : $general['revisado']; ?>" class="campostextMedio" style="width: 80px;">&nbsp;&nbsp;
                                      <input name="aprobado" id="aprobado" type="text" placeholder="aprobado" value="<?php echo $general['aprobado'] == '' ? $_SESSION['Usuario'] : $general['aprobado']; ?>" class="campostextMedio" style="width: 80px;">&nbsp;&nbsp;
                                      <input name="modificado" id="modificado" type="hidden" placeholder="modificado" value="<?php echo $_SESSION['Usuario']; ?>" class="campostextMedio">
                                      <input name="controladas" id="controladas" class="controladas" type="hidden" value="<?php echo $_GET['controladas']; ?>">
                                    </div>
                                    <!-- <fieldset id="field"></fieldset> --> <!-- este muestra dinamicos -->
                                  </div>
                                </div>

                      </div>
                      Adjuntar pdf: <!-- <input name="adjunto" type="adjunto" size="100" maxlength="100" class="botonGMini"> -->
                      <input class="botonGMini" type="file" name="adjunto" id="adjunto" />
                      <?php if ($general['userfile'] != '') : ?>
                        <input name="userfile" type="hidden" id="userfile" value="<?php echo $general['userfile']; ?>" />
                        <a href="javascript:verFoto('pdfsicoq/<?php echo $general['userfile']; ?>','800','600')"> Ver Archivo</a>
                      <?php endif; ?>
            </td>
          </tr>
          </tbody>

  </form>

  </table>

  <hr>
  <div style="text-align: left"><strong> <em scope="col">ITEMS DE SICOQ &nbsp;&nbsp;<em id="AlertUpdate"></em>&nbsp;</em></strong></div><br>
  <div class="row align-items-start">

    <div style="width: 370px;"><strong>NOMBRE SUSTANCIA CONTROLADA</strong></div>
    <div style="width: 100px;"><strong>ING.KILOS</strong></div>
    <div style="width: 80px;"><strong>FECHA RECEP.</strong></div>
    <div style="width: 150px;"><strong>PROVEEDOR</strong></div>
    <div style="width: 120px;"><strong>COSTO UND.</strong></div>
    <div style="width: 80px;"><strong>AUTORIZACION</strong></div>
    <div style="width: 100px;"><strong>FACTURA</strong></div>
    <div style="width: 80px;"><strong>ÁREA </strong></div>
    <div style="width: 100px;"><strong>FECHA SAL.</strong></div>
    <div style="width: 120px;"><strong>KILOS SALIDA</strong></div>
    <div style="width: 110px;"><strong>CODIFICADORA </strong></div>
    <div style="width: 40px;"><strong>O.P </strong></div>
    <div style="width: 140px;"><strong>MODIFICO</strong></div>
    <?php if ($_SESSION['superacceso']) : ?>
      <div style="width: 60px;"><strong>ELIMINAR</strong></div>
    <?php endif; ?>
    <!--  <div class="col-lg-1" ><strong>VER</strong></div>     -->
  </div>
  <div class="divScrollGigante" id="itemspedido" role="alert" style="text-align: left;">

    <?php $ids = 0;
    foreach ($this->items as $items) {
      $ids++; ?>
      <div id="dinamicos" class="row celdaborde1">
        <div style="width: 20px;" id="fondo_2"> </div>
        <div style="width: 370px;" id="fondo_2">
          <?php echo $items['nombre']; ?>
        </div>
        <div style="width: 80px;" id="fondo_1">

          <input onChange="UpdatesItems(<?php echo $items['id_i']; ?>,this)" type="text" required="required" id="ingresokilos_<?php echo $ids; ?>" name="ingresokilos_<?php echo $ids; ?>" value="<?php echo $items['ingresokilos']; ?>" class="campostext" style="display: none;width: 80px;"><?php $sumaingresostotal += $items['ingresokilos']; ?>
          <strong class="ingresokilos_<?php echo $ids; ?>" onClick="UpdatesItems(<?php echo $items['id_i']; ?>,this)"><?php echo $items['ingresokilos']; ?></strong>

        </div>
        <div style="width: 120px;" id="fondo_2">

          <?php echo $items['fecha_recepcion']; ?>

        </div>
        <div style="width: 130px;" id="fondo_2">

          <?php echo $items["proveedor"]; ?>

        </div>
        <div style="width: 100px;" id="fondo_1">

          <input onChange="UpdatesItems(<?php echo $items['id_i']; ?>,this)" type="text" id="costound_<?php echo $ids; ?>" name="costound_<?php echo $ids; ?>" value="<?php echo $items['costound']; ?>" class="campostext" style="display: none;width: 80px;">
          <strong class="costound_<?php echo $ids; ?>" onClick="UpdatesItems(<?php echo $items['id_i']; ?>,this)"><?php echo $items['costound']; ?></strong>

        </div>
        <div style="width: 100px;" id="fondo_2">

          <?php echo $items["autorizacion"]; ?>
        </div>
        <div style="width: 100px;" id="fondo_2">

          <input onChange="UpdatesItems(<?php echo $items['id_i']; ?>,this)" type="text" id="factura_<?php echo $ids; ?>" name="factura_<?php echo $ids; ?>" value="<?php echo $items['factura']; ?>" class="campostext" style="display: none;width: 80px;">
          <strong class="factura_<?php echo $ids; ?>" onClick="UpdatesItems(<?php echo $items['id_i']; ?>,this)"><?php echo $items['factura']; ?></strong>

        </div>
        <div style="width: 80px;" id="fondo_2">

          <select onChange="UpdatesItems(<?php echo $items['id_i']; ?>,this)" name="area_<?php echo $ids; ?>" id="area_<?php echo $ids; ?>" style="display: none;width: 80px;" class="campostext">
            <?php foreach ($this->proceso as $proceso) {  ?>
              <option value="<?php echo $proceso['id_tipo_proceso']; ?>" <?php if (!(strcmp($proceso['id_tipo_proceso'], $items['area']))) {
                                                                            echo "selected=\"selected\"";
                                                                          } ?>><?php echo htmlentities($proceso['nombre_proceso']); ?> </option>
            <?php } ?>
          </select>
          <strong class="area_<?php echo $ids; ?>" onClick="UpdatesItems(<?php echo $items['id_i']; ?>,this)">
            <?php $areas = $conexion->llenarCampos("tipo_procesos", "WHERE id_tipo_proceso='" . $items['area'] . "' ", " ", "nombre_proceso ");
            echo $areas['nombre_proceso']; ?></strong>
        </div>
        <div style="width: 100px;" id="fondo_2">
          <?php echo $items["fecha_salida"]; ?>
        </div>
        <div style="width: 110px;" id="fondo_2">

          <input onChange="UpdatesItems(<?php echo $items['id_i']; ?>,this)" type="text" id="salidakilos_<?php echo $ids; ?>" name="salidakilos_<?php echo $ids; ?>" value="<?php echo $items['salidakilos']; ?>" class="campostext" style="display: none;width: 80px;">
          <strong class="salidakilos_<?php echo $ids; ?>" onClick="UpdatesItems(<?php echo $items['id_i']; ?>,this)"><?php echo $items['salidakilos']; ?></strong>
          <?php $salidakilostotal += $items["salidakilos"]; ?>
        </div>
        <div style="width: 110px;" id="fondo_2">

          <input onChange="UpdatesItems(<?php echo $items['id_i']; ?>,this)" type="text" id="numeradora_<?php echo $ids; ?>" name="numeradora_<?php echo $ids; ?>" value="<?php echo $items['numeradora']; ?>" class="campostext" style="display: none;width: 80px;">
          <strong class="numeradora_<?php echo $ids; ?>" onClick="UpdatesItems(<?php echo $items['id_i']; ?>,this)"><?php echo $items['numeradora']; ?></strong>
        </div>
        <div style="width: 70px;" id="fondo_2">

          <input onChange="UpdatesItems(<?php echo $items['id_i']; ?>,this)" type="text" id="op_<?php echo $ids; ?>" name="op_<?php echo $ids; ?>" value="<?php echo $items['op']; ?>" class="campostext" style="display: none;width: 80px;">
          <strong class="op_<?php echo $ids; ?>" onClick="UpdatesItems(<?php echo $items['id_i']; ?>,this)"><?php echo $items['op']; ?></strong>

        </div>
        <div style="width: 90px;" id="fondo_2">

          <input onChange="UpdatesItems(<?php echo $items['id_i']; ?>,this)" type="text" id="modificado_<?php echo $ids; ?>" name="modificado_<?php echo $ids; ?>" value="<?php echo $items['modificado']; ?>" class="campostext" style="display: none;width: 80px;">
          <strong class="modificado_<?php echo $ids; ?>" onClick="UpdatesItems(<?php echo $items['id_i']; ?>,this)"><?php echo $items['modificado']; ?></strong>

        </div>

        <?php if ($items['autorizacion']  && $_SESSION['superacceso']) {  ?>
          <div class="col-lg-1" id="fondo_2">
            <a class="botonDel" id="btnDelItems" onclick='eliminar("<?php echo $items['id_i']; ?>","<?php echo $_GET['columna']; ?>","autorizacion","?c=csicoq&a=Eliminar","0" )' type="button">DELETE</a>
          </div>
        <?php  } ?>
      </div>
    <?php  } ?>

  </div>
  <div id="dinamicos" class="row celdaborde1">
    <div style="width: 200px;" id="fondo_1"><strong>TOTAL INGRESO KILOS:</strong> </div>
    <div style="width: 120px;" id="fondo_1">
      <p><?php echo $sumaingresostotal; ?></p>
    </div>
    <div style="width: 200px;" id="fondo_1"><strong>CANTIDAD SALIDA TOTAL:</strong> </div>
    <div style="width: 120px;" id="fondo_1">
      <p><?php //echo $salidakilostotal = number_format($salidakilostotal, 3) - number_format($kilosold['kilosold'], 3);
      echo number_format($salidakilostotal, 3);?></p>
      
    </div>
  </div>
  <input name="sumaingresos" id="sumaingresos" class="sumaingresos" type="hidden" value="<?php echo $sumaingresostotal; ?>">
  <input name="sumasalidas" id="sumasalidas" class="sumasalidas" type="hidden" value="<?php echo $salidakilostotal; ?>">


  <br><br><br>
  <div class="panel-footer">
    VERSIÓN: 01
    <!-- <a class="botonGeneral" href="?c=ocomercial&id=<?php echo $items['id_pedido']; ?>">SALIR</a>  -->
    <a class="botonFinalizar" style="text-decoration:none; " href="javascript:Salir('view_index.php?c=csicoq&a=Menu')">SALIR</a>

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
  //bloquea envio del formulario con enter 
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('input[type=text]').forEach(node => node.addEventListener('keypress', e => {
      if (e.keyCode == 13) {
        e.preventDefault();
      }
    }))
  });


  $("#autorizacion").on("change", function() {
    idautorizacion = $("#autorizacion").val();
    controladas = $("#controladas").val();
    window.location = "view_index.php?c=csicoq&a=Crud&columna=autorizacion&id=" + idautorizacion + "&controladas=" + controladas;
    $('#mensaje').hide();
    if (idautorizacion) {
      $('#mensaje').show();
      $("#mensaje").text('Buscando autorizacion... !');
    }
  });

  $("#facturabusqueda").on("change", function() {
    idfacturabusqueda = $("#facturabusqueda").val();
    idfechabusqueda = $("#fechabusqueda").val();
    controladas = $("#controladas").val();
    window.location = "view_index.php?c=csicoq&a=Crud&columna=factura&id=" + idfacturabusqueda + "&columna2=fecha&id2=" + idfechabusqueda + "&controladas=" + controladas;
    $('#mensaje').hide();
    if (idfacturabusqueda) {
      $('#mensaje').show();
      $("#mensaje").text('Buscando facturabusqueda... !');
    }
  });


  $("#fechabusqueda").on("change", function() {
    idfacturabusqueda = $("#facturabusqueda").val();
    idfechabusqueda = $("#fechabusqueda").val();
    controladas = $("#controladas").val();
    window.location = "view_index.php?c=csicoq&a=Crud&columna=fecha_recepcion&id=" + idfechabusqueda + "&columna2=factura&id2=" + idfacturabusqueda + "&controladas=" + controladas;
    $('#mensaje').hide();
    if (idfechabusqueda) {
      $('#mensaje').show();
      $("#mensaje").text('Buscando fechabusqueda... !');
    }
  });

  function mensaje(){
    alert("si");
  }

  function resetear(){
    window.location ="view_index.php?c=csicoq&a=Inicio&controladas=<?php echo $_GET['controladas'] ?>"
  }

  $('#mesBusqueda').on("change", function() {
    idfechabusqueda = $('#fechabusqueda').val();
    controladas = $("#controladas").val();
    idmesbusqueda = $('#mesBusqueda').val();
    if(document.getElementById('mesBusqueda').value == ""){
      window.location ="view_index.php?c=csicoq&a=Inicio&controladas=<?php echo $_GET['controladas'] ?>"
    } else {
    window.location = "view_index.php?c=csicoq&a=Crud&id="+idfechabusqueda+"&mes="+ idmesbusqueda+"&columna=fecha_recepcion&columna2=fecha_salida&controladas=" + controladas;
    }
  });

  

  /*  $( "#sustanciabusqueda" ).on( "change", function() { 
        idsustanciabusqueda = $( "#sustanciabusqueda" ).val();  
        controladas = $( "#controladas" ).val();   
        window.location="view_index.php?c=csicoq&a=Crud&columna=nombre&id="+idsustanciabusqueda+"&controladas="+controladas;
        $('#mensaje').hide(); 
        if(idsustanciabusqueda){ 
          $('#mensaje').show(); 
          $("#mensaje").text('Buscando sustanciabusqueda... !');  
        }
   }); */



  $(document).ready(function() {

    totalIngreso();

    diasVencer();


  });

  /*$( ".totalingresados" ).on( "change", function() {
  });*/

  function totalIngreso() {

    var kilospermitidosmes = $(".kilospermitidosmes").val();
    var ingresokilos = !$(".ingresokilos").val() ? 0 : $(".ingresokilos").val();
    var sumaingresos = !$(".sumaingresos").val() ? 0 : $(".sumaingresos").val();
    var totalkIngreso = parseFloat(ingresokilos) + parseFloat(sumaingresos);

    var sumasalidas = !$(".sumasalidas").val() ? 0 : $(".sumasalidas").val();
    var salidakilos = !$(".salidakilos").val() ? 0 : $(".salidakilos").val();
    totalsalida = parseFloat(sumasalidas) + parseFloat(salidakilos);

    controladas = $("#controladas").val();

    //solamente e valida para Controladas
    if (controladas == 'SI') {

      if (totalkIngreso <= kilospermitidosmes) {
        $(".totalingresados").val(totalkIngreso.toFixed(3));

        if ((totalsalida > totalkIngreso)) {

          //$(".salidakilos").val('0');//para dejarlo q pueda ingresar los kilos


          swal("Menos Cantidad", "Los kilos ingresados en cantidad no debe superar los kilos ingresados!", "error");
        }

        $(".totalsalida").val(totalsalida.toFixed(3));
      } else {
        $(".ingresokilos").val('0');
        $(".salidakilos").val('0');

        //totalIngreso();
        swal("Ingrese Menos Kilos", "Los kilos ingresados no pueden superar los kilos permitidos por mes !", "error");
        return false;
      }

    }

    var totalingresados = $(".totalingresados").val();
    var kilosold = !$("#kilosold").val() ? 0 : $("#kilosold").val();

    totalinventario = (parseFloat(totalingresados) + parseFloat(totalsalida));

    /*totalinventario = parseFloat($(".totalingresados").val() ) - parseFloat(totalsalida) ; */
    $(".totalinventario").val(totalinventario.toFixed(3));

    kilosdisponiblescompra = parseFloat(kilospermitidosmes) - parseFloat(totalkIngreso);
    $(".kilosdisponiblescompra").val(kilosdisponiblescompra.toFixed(3));


  }

  function diasVencer() {
    var dias = "<?php echo $diasaVencer; ?>";
    //120 dias
    if (dias != 0 && dias < 120) {
      swal("Alerta", dias + " dias Para el vencimiento", "warning")
    }

  }

  function editarform() {
    ids = '' + "<?php echo $_GET['id']; ?>"; //coloque la columna del id a actualizar
    colum = "id";
    tabla = 'tbl_sicoq';
    url = 'view_index.php?c=csicoq&a=Guardar&alert=1&';
    //nuevoArchivo=document.getElementById("adjunto");

    updates(ids, colum, tabla, url);

  }

  function UpdatesItems(vid, valores) {
    separador = "_";
    var ingreso = $(valores).attr("class");
    ingreso2 = ingreso.split(separador);


    if (ingreso2[1] != '') {
      $("#" + ingreso2[0] + "_" + ingreso2[1]).show();
      $("." + ingreso2[0] + "_" + ingreso2[1]).hide();
    }


    var name = $(valores).attr("name");
    var textoseparado = name.split(separador);
    var valores = $(valores).attr("name", textoseparado[0]);

    ids = 'id_i'; //coloque la columna del id a actualizar
    valorid = '' + vid;
    tabla = 'tbl_sicoq_items';
    url = 'view_index.php?c=cgeneral&a=Actualizar'; //la envio en campo proceso


    actualizapaso(ids, valorid, valores, tabla, url);
    location.reload();


  }


  function validarTodos() {
    validaFechas();

  }

  function validaFechas() {
    nombre = $("#nombre").val();
    ingresokilos = $("#ingresokilos").val();
    fecha_recepcion = $("#fecha_recepcion").val();
    proveedor = $("#proveedor").val();
    costound = $("#costound").val();
    factura = $("#factura").val();
    area = $("#area").val();
    fecha_salida = $("#fecha_salida").val();
    salidakilos = $("#salidakilos").val();
    numeradora = $("#numeradora").val();
    ccit_autorizacion = $("#ccit_autorizacion").val();
    op = $("#op").val();
    responsable = $("#responsable").val();
    revisado = $("#revisado").val();
    aprobado = $("#aprobado").val();

    if (nombre == '') {
      swal("Alerta", " Ingrese Nombre Sustancia", "warning")
      return false;
    }
    if (ingresokilos > '0' && fecha_recepcion == '') {
      swal("Alerta", " Ingrese Fecha Ingreso", "warning")
      return false;
    }
    if (proveedor == '') {
      swal("Alerta", " Ingrese Proveedor", "warning")
      return false;
    }
    if (costound == '') {
      swal("Alerta", " Ingrese Costo unidad", "warning")
      return false;
    }
    if (factura == '') {
      swal("Alerta", " Ingrese Factura", "warning")
      return false;
    }
    if (fecha_recepcion != '' && ingresokilos == '' || fecha_recepcion != '' && ingresokilos == '0') {
      swal("Alerta", " Ingrese kilos de Ingreso", "warning")
      return false;
    }
    if (fecha_salida != '' && salidakilos == '' || fecha_salida != '' && salidakilos == '0') {
      swal("Alerta", " Ingrese kilos Salida", "warning")
      return false;
    }
    if (salidakilos > '0' && fecha_salida == '') {
      swal("Alerta", " Ingrese Fecha Salida", "warning")
      return false;
    }

    if (fecha_recepcion == '' && fecha_salida == '') {
      swal("Alerta", " Ingrese una de las Fechas sea de ingreso o salida", "warning")
      return false;
    }
    if (ingresokilos == '' && salidakilos == '' || ingresokilos == '0' && salidakilos == '0') {
      swal("Alerta", " Ingrese uno de los Kilos", "warning")
      return false;
    }

    if (nombre != '' && ingresokilos != '' && costound == '' || nombre != '' && salidakilos != '' && costound == '') {
      swal("Alerta", " Ingrese Costo Unidad", "warning")
      return false;
    }


    if (ccit_autorizacion == '' && $("#autorizacion").val() == '') {
      swal("Alerta", " Ingrese Autorizacion o ccit_autorizacion", "warning")
      return false;
    }
    /*if(ccit_autorizacion==''){
      swal("Alerta"," Ingrese Autorizacion","warning")
         return false;
    }*/
    if (op == '') {
      swal("Alerta", " Ingrese OP", "warning")
      return false;
    }
    if (responsable == '') {
      swal("Alerta", " Ingrese Responsable", "warning")
      return false;
    }
    if (revisado == '') {
      swal("Alerta", " Ingrese Revisado", "warning")
      return false;
    }
    if (aprobado == '') {
      swal("Alerta", " Ingrese Aprobado", "warning")
      return false;
    }


    document.form1.submit();

  }



   
</script>