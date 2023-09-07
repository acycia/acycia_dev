<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
require(ROOT_BBDD);

include_once("./Controller/Csolicitud_compras.php")

?>

<?php
if (!isset($_SESSION)) {
  session_start();
}

$currentPage = $_SERVER["PHP_SELF"];

$objController = new Csolicitud_comprasController();
$conexion = new ApptivaDB();

$colname_usuario = "1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}

$row_usuario = $conexion->buscar('usuario', 'usuario', $colname_usuario);
$responsable = $conexion->buscar('usuario', 'usuario', 'Compras');
$consecutivo = $objController->CtraerConsecutivo();
$num = $consecutivo['codigo'];
$id = $consecutivo['id_solicitud'];

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
  <script type="text/javascript" src="AjaxControllers/js/insert.js"></script>
  <script type="text/javascript" src="AjaxControllers/js/consultas.js"></script>
  <script type="text/javascript" src="AjaxControllers/js/delete.js"></script>
  <!-- sweetalert -->
  <script src="librerias/sweetalert/dist/sweetalert.min.js"></script>
  <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
  <link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">
  <!-- jquery -->
  <script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
  <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>

  <!-- css Bootstrap-->
  <link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>

<body onKeyDown="javascript:Verificar()">
  <div class="spiffy_content"> <!-- este define el fondo gris de lado a lado si se coloca dentro de tabla inicial solamente coloca borde gris -->
    <div align="center">
      <table id="tabla1">
        <tr>
          <td align="center">
            <div class="row-fluid">
              <div class="span8 offset2"> <!--span8 offset2   esto da el tamaño pequeño -->
                <div class="panel panel-primary">
                  <div class="panel-heading">
                    <h2>SOLICITUD DE COMPRAS</h2>
                  </div>
                  <div id="cabezamenu">
                    <ul id="menuhorizontal">
                      <li id="nombreusuario"><?php echo $_SESSION['Usuario']; ?></li>
                      <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                      <li><a href="menu.php">MENU PRINCIPAL</a></li>
                      <li><a href='view_index.php?c=Csolicitud_compras&a=inicioListado'>LISTADO ENTRADAS</a></li>

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
                                SOLICITUD DE COMPRAS
                              </td>
                            </tr>
                            <tr>
                              <td align="center">
                                <h5 id="numero2" class="id_solicitud">
                                  <h2>N° <?php echo $num; ?></h2>
                                </h5>
                                <hr>
                                ALBERTO CADAVID R & CIA S.A. - Nit: 890915756-6 <br>
                                Carrera 45 N°. 14 - 15 Tel: 604 311-21-44 - Medellin-Colombia
                                <p></p>
                              </td>
                            </tr>
                          </table>
                        </div>
                      </div>


                      <form action="view_index.php?c=Csolicitud_compras&a=guardarDatos" method="post" id="form1" name="form1">
                        <table id="tabla1">
                          <tr>
                            <td>
                              <input id="id_solicitud" name="id_solicitud" type="hidden" value="<?php echo $num; ?>">
                              <strong>NOMBRE SOLICITANTE:</strong>
                              <input class="form-control" type="text" required="required" id="nombre" name="nombre" value="" autofocus="">
                            </td>
                            <td>
                              <strong>AREA&nbsp;&nbsp;</strong>
                              <input class="form-control" type="text" required="required" id="area" name="area" value="">
                            </td>
                          </tr>

                          <tr>
                            <td>
                              <strong>MAQUINA</strong>&nbsp;&nbsp;
                            </td>
                            <td>
                              <strong>FECHA: </strong>
                            </td>
                          </tr>

                          <tr>
                            <td>
                              <input class="form-control" type="text" id="maquina" name="maquina" value="">
                            </td>
                            <td>
                              <input class="form-control" type="date" required="required" id="fecha" name="fecha" value="">
                            </td>
                          </tr>


                          <tr>
                            <td colspan="2">
                              <hr>
                              <table border="1" style="width: 100%;">
                                <tr align="center">
                                  <td style="width:310px"><strong>MATERIAL SOLICITADO</strong></td>
                                  <td style="width:60px"><strong>CANTIDAD</strong></td>
                                  <td style="width:90px"><strong>ORDEN DE COMPRA</strong></td>
                                  <td style="width:85px"><strong>ESTADO</strong></td>
                                </tr>


                                <?php for ($i = 1; $i <= 8; $i++) { ?>
                                  <tr>
                                    <td colspan="12" id="dato1">

                                      <input type="hidden" name="solicitud_id" id="solicitud_id" value="<?php echo $num; ?>" style="width:20px">&nbsp;
                                      <input type="text" placeholder="Material" id="insumo" name="insumo[]" value="" style="width:383px"> &nbsp;
                                      <input type="number" placeholder="Cantidad" id="cantidad" name="cantidad[]" value="" style="width:72px">&nbsp;
                                      <input type="text" placeholder="Orden de Compra" id="oc" style="width:108px" value="" disabled>&nbsp;
                                      <select style="width:101px" name="estado[]" id="estado[]">
                                        <option value="PENDIENTE">PENDIENTE</option>
                                      </select>
                                    </td>
                                  </tr>
                                <?php } ?>


                              </table>
                            </td>
                          </tr>

                          <tr>
                            <td colspan="2">&nbsp;</td>
                          </tr>

                          <tr>
                            <td colspan="2">
                              <strong>OBSERVACIONES:</strong>
                              <textarea class="form-control" id="observaciones" name="observaciones" cols="50" rows="3"></textarea>
                            </td>
                          </tr>



                          <tr>
                            <td>
                              <strong>RESPONSABLE DE LA COMPRA: </strong>
                              <input type="text" placeholder="Responsable" id="responsable" name="responsable" value="<?php echo $responsable['nombre_usuario']; ?>" class='form-control' readonly>
                            </td>
                            <td>
                              <strong>AUTORIZADO POR: </strong>
                              <input type="text" placeholder="Autorizado" id="autorizado" name="autorizado" value="" disabled class='form-control' readonly>
                            </td>
                          </tr>

                          <tr>
                            <td colspan="2">&nbsp;</td>
                          </tr>

                          <tr>
                            <td align="center" colspan="2">
                              <strong>CORREO DE APROBACION</strong>
                              <br>
                              <strong>Elija el correo de la persona que debe autorizar la compra</strong>
                            </td>

                            <table border="1" style="width: 100%;">

                              <input type="hidden" name="asunto" id="asunto" value="Solicitud de compras">
                              <input type="hidden" name="mensaje" id="mensaje" value="test de mensaje">

                              <tr>
                                <td align="center" id="fondo_3" rowspan="6">
                                  <a class="Estilo5" onclick="window.print();">Imprimir</a>
                                </td>

                                <td>
                                  <?php $email1 = "mariateresacdv@acycia.com" ?>
                                  <a href="http://" target="_blank" rel="noopener noreferrer"><?php echo $email1 ?></a>
                                </td>
                                <td align="center" id="fondo_3">
                                  <input type="radio" name="correo" id="correo" value="<?php echo $email1 ?>">
                                </td>
                              </tr>

                              <tr>
                                <td>
                                  <?php $email2 = "coordinacion@acycia.com" ?>
                                  <a href="http://" target="_blank" rel="noopener noreferrer"><?php echo $email2 ?></a>
                                </td>
                                <td align="center" id="fondo_3">
                                  <input type="radio" name="correo" id="correo2" value="<?php echo $email2 ?>">
                                </td>
                              </tr>

                              <tr>
                                <td>
                                  <?php $email3 = "pagos@acycia.com" ?>
                                  <a href="http://" target="_blank" rel="noopener noreferrer"><?php echo $email3 ?></a>
                                </td>
                                <td align="center" id="fondo_3">
                                  <input type="radio" name="correo" id="correo" value="<?php echo $email3 ?>">
                                </td>
                              </tr>

                              <tr>
                                <td>
                                  <?php $email4 = "alvarocadavid@acycia.com" ?>
                                  <a href="http://" target="_blank" rel="noopener noreferrer"><?php echo $email4 ?></a>
                                </td>
                                <td align="center" id="fondo_3">
                                  <input type="radio" name="correo" id="correo2" value="<?php echo $email4 ?>">
                                </td>
                              </tr>

                              <tr>
                                <td>
                                  <?php $email5 = "mauriciocadavid@acycia.com" ?>
                                  <a href="http://" target="_blank" rel="noopener noreferrer"><?php echo $email5 ?></a>
                                </td>
                                <td align="center" id="fondo_3">
                                  <input type="radio" name="correo" id="correo2" value="<?php echo $email5 ?>">
                                </td>
                              </tr>
                            </table>
                            <fieldset style="border: 1px solid #000; padding:10px; margin:10px 0 ">
                              <div>
                                <div class="row " style="padding:0 10px; justify-content:space-between">
                                  <strong>CODIGO: A3-F06</strong>
                                  <strong>VERSION 02</strong>
                                </div>
                              </div>
                            </fieldset>
                          </tr>


                          <tr>
                            <td colspan="2">
                              <div class="row">
                                <div class="span12" style="align-items: center;">
                                  <em style="display: none;  align-items: center; justify-content: center;color: red; " id="alertG"></em>
                                </div>
                              </div>
                              <div class="panel-footer" id="continuar" align="center">

                                <button id="btnEnviarG" name="btnEnviarG" type="submit" class="botonGeneral">GUARDAR Y CONTINUAR</button>
                              </div>
                            </td>
                          </tr>

                          <input type="hidden" name="MM_insert" value="form1">
                          <tr>
                            <td>

                            </td>
                          </tr>
                        </table>
                      </form>



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
</body>

</html>


<script type="text/javascript">
  $("#btnEnviarG").on("click", function() {

    if ($("#nombre").val() == '') {
      swal("Error", "Debe agregar un valor al campo Nombre! :)", "error");
      return false;
    } else
    if ($("#area").val() == '') {
      swal("Error", "Debe agregar un valor al campo Area! :)", "error");
      return false;
    } else
    if ($("#fecha").val() == '') {
      swal("Error", "Debe agregar un valor al campo Fecha! :)", "error");
      return false;
    } else
    if ($('input:radio[name=correo]:checked').val() == undefined) {
      swal("Error", "Debe agregar un valor al campo Email! :)", "error");
      return false;
    } else
    if ($("#insumo").val() == '') {
      swal("Error", "Debe agregar un valor al campo insumo! :)", "error");
      return false;
    } else {
      //guardarSolicitudConAlert($("#id_solicitud").val());
    }

  });




  $("#btnEnviarItems").on("click", function() {

    if ($("#insumo").val() == '') {
      swal("Error", "Debe agregar un valor al campo insumo! :)", "error");
      return false;
    } else if ($("#peso").val() == '' && $("#precio").val() == '') {
      swal("Error", "Debe agregar un valor al campo peso o precio! :)", "error");
      return false;
    } else if ($("#cantidad").val() == '') {
      swal("Error", "Debe agregar un valor al campo cantidad! :)", "error");
      return false;
    } else {
      guardarConAlertItems();
    }

  });
</script>
<?php

mysqli_free_result($usuario);
mysqli_free_result($ver_nuevo);
mysqli_free_result($proveedores);
mysqli_free_result($insumos);


?>