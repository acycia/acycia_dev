<?php
     require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
     require (ROOT_BBDD); 
?>
<?php require_once('Connections/conexion1.php'); ?>
<?php

require_once("db/db.php"); 
require_once("Controller/CmezclasIm.php");

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


$conexion = new ApptivaDB();

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
 
<?php foreach($this->row_referencia_copia as $row_referencia_copia) { $row_referencia_copia; } ?>
<?php foreach($this->row_referencia as $row_referencia) { $row_referencia; } ?>
<?php foreach($this->row_caract as $row_caract) { $row_caract; } ?>
<?php foreach($this->row_caract_m as $row_caract_m) { $row_caract_m; } ?>
<?php foreach($this->row_mezcla as $row_mezcla) { $row_mezcla; } ?>
<?php foreach($this->row_materia_prima as $row_materia_prima) { $row_materia_prima; } ?>
<?php foreach($this->maquinas as $maquinas) { $maquinas; } ?>
 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link rel="stylesheet" type="text/css" href="css/general.css"/>
<link rel="stylesheet" type="text/css" href="css/formato.css"/>
<link rel="stylesheet" type="text/css" href="css/desplegable.css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
<script type="text/javascript" src="js/consulta.js"></script> 
<script type="text/javascript" src="AjaxControllers/js/consultas.js"></script>

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

 
<script type="text/javascript">
function ce_micras()
{
calibre=parseFloat(document.form1.campo_1.value);
var z=(calibre).toFixed(2);
micra=(z)*25.4;
//var w = Math.round(z * Math.pow(10,2))/Math.pow(10,2);
document.form1.campo_6.value=Math.round(micra*100)/100;
}
</script>
<!--CONFIRMACION AL DARLE CLICK EN SALIR BOTON-->

<script>
    $(document).ready(function() { $(".busqueda").select2(); });
</script>

</head>
<body>

    <div class="spiffy_content"> <!-- este define el fondo gris de lado a lado si se coloca dentro de tabla inicial solamente coloca borde gris -->
      <div align="center">
        <table ><!-- style="width: 100%" -->
          <tr>
           <td align="center">
             <div class="row-fluid">
               <div class="span8 offset2"> <!--span8 offset2   esto da el tamaño pequeño -->
                 <div class="panel panel-primary">
                  <div class="panel-heading" align="left" ></div><!--color azul-->
                   <div class="row" >
                     <div class="span12">&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/cabecera.jpg"></div>
                     <div class="span12"><h3> PROCESO DE MEZCLAS  &nbsp;&nbsp;&nbsp; </h3></div>
                   </div>
                   <div class="panel-heading" align="left" ></div><!--color azul-->
                      <div id="cabezamenu">
                       <ul id="menuhorizontal">
                        <li id="nombreusuario" ><?php echo $_SESSION['Usuario']; ?></li>
                        <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                        <li><a href="menu.php">MENU PRINCIPAL</a></li> 
                        <li><a href="produccion_registro_extrusion_listado.php">MEZCLAS</a></li> 
                      </ul>
                  </div> 
                 <div class="panel-body"> 
                   <div ><!--  SI QUITO  class="container" SE ALINEA A LA IZQUIERDA TODO EL CONTENIDO DE ESTE Y SE REDUCE -->
                    <div class="row">
                      <div class="span12"> 
                 </div>
               </div> 
            <!-- grid --> 

            <div class="container-fluid">  
              <form action="view_index.php?c=cmezclasIm&a=Guardar&id=<?php echo $_GET['cod_ref'];?>" method="post" enctype="multipart/form-data" name="form1">
                <table class="table table-bordered table-sm">
                <tr id="tr1">
                  <td colspan="19" id="titulo2">CARACTERISTICAS DE IMPRESION ADD</td>
                </tr>
                <tr>
                  <td colspan="3" rowspan="5" id="dato2"><img src="images/logoacyc.jpg"/></td>
                  <td colspan="19" id="dato3"><a href="manteni.php"><img src="images/opciones.gif" style="cursor:hand;" alt="DISE&Ntilde;O Y DESARROLLO" title="LISTADO MEZCLAS Y CARACTERISTICAS" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" title="MENU PRINCIPAL" border="0"/></a>
                    <a href="view_index.php?c=cmezclasIm&a=Mezcla&cod_ref=<?php echo $_GET['cod_ref'];?>"><img src="images/hoja.gif" alt="VISTA" title="VISTA" border="0"></a></td>
                </tr>
                <tr id="tr1">
                  <td width="182" colspan="10" nowrap="nowrap" id="fuente1">Fecha Ingreso
                    <input name="fecha_registro" type="date" min="2000-01-02" value="<?php echo date("Y-m-d"); ?>" size="10" autofocus /></td>
                  <td colspan="9" id="fuente1"> Ingresado por
                    <input name="usuario" type="text" value="<?php echo $_SESSION['Usuario']; ?>" size="27" readonly="readonly"/>
                    <?php //$numero=$row_ultimo['id_cv']+1;  $numero; ?>
                    <!--<input type="hidden" name="id_cv" id="id_cv" value="<?php echo $numero; ?>"/>--></td>
                </tr>
                <tr>
                  <td colspan="8" nowrap="nowrap" id="fuente2">&nbsp;</td>
                  <td width="126" colspan="7" nowrap="nowrap" id="fuente2">&nbsp;</td>
                  <td width="235" colspan="4" id="fuente2">&nbsp;</td>
                </tr>
                <tr id="tr1">
                  <td colspan="8" nowrap="nowrap" id="fuente2">Referencia</td>
                  <td colspan="7" id="fuente2">Version</td>
                  <td nowrap="nowrap" colspan="4" id="dato1">
                    <a class="botonGMini" onclick="vercopiaMezcla()">GENERAR COPIA</a>
                     </td>
                </tr>
                <tr>
                  <td colspan="3" nowrap="nowrap" id="numero2"> 
                      <?php echo $row_referencia['cod_ref']; ?>
                    </td>
                  <td nowrap="nowrap" id="numero2"> 
                      <?php echo $row_referencia['version_ref']; ?>
                    </td>
                  <td colspan="2" id="fuente1">
                    <select name="ref" id="refcopia" class="refcopia   selectsMini" style="display: none;" onchange="copiaMezcla();"  >
                    <option value=""<?php if (!(strcmp("", $_GET['cod_refcopia']))) {echo "selected=\"selected\"";} ?>>Referencia</option> 
                    <?php foreach($this->row_referencia_copia as $row_referencia_copia) {  ?>
                      <option value="<?php echo $row_referencia_copia['int_cod_ref_pm']; ?>"<?php if (!(strcmp($row_referencia_copia['int_cod_ref_pm'], $_GET['cod_refcopia']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_referencia_copia['int_cod_ref_pm']); ?> </option>
                    <?php } ?> 
                  </select>
                </td>
                  <td colspan="2" id="fuente1">
                   <?php if($_GET['cod_refcopia'])
                   echo 'Copiando Caracteristicas '.$_GET['cod_refcopia'];
                   ?>
                </td>
                </tr>  
                   <!--  INICIA MEZCLAS DE EXTRUDER -->
                      <tr id="tr1">
                        <td colspan="19" id="titulo4">IMPRESION</td>
                        </tr>
                        <tr>
                         <td  colspan="19" id="titulo4"> 
                          Impresora : 
                          <select name="extrusora_mp" id="extrusora_mp" class="busqueda selectsMedio" required="required" onchange="Impresora();">
                              <option value="">Impresora</option>
                                 <?php  foreach($this->maquinas as $maquinas ) { ?>
                              <option value="<?php echo $maquinas['nombre_maquina']; ?>"<?php if (!(strcmp($row_mezcla['extrusora_mp'] , $maquinas['nombre_maquina']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($maquinas['nombre_maquina']); ?> 
                            </option>
                          <?php } ?>
                          </select>  
                        </td>
                       </tr>
                      <tr id="tr1">
                        <td rowspan="2" id="fuente1"> </td>
                        <td colspan="4" id="fuente1">UNIDAD 1</td>
                        <td colspan="2" id="fuente1">UNIDAD 2</td>
                        <td colspan="2" id="fuente1">UNIDAD 3</td>
                        <td colspan="2" id="fuente1">UNIDAD 4</td>
                        <td colspan="2" id="fuente1">UNIDAD 5</td>
                        <td colspan="2" id="fuente1">UNIDAD 6</td>
                        <td colspan="2" id="fuente1">UNIDAD 7</td>
                        <td colspan="3" id="fuente1">UNIDAD 8</td> 
                        </tr> 
                        <tr>
                          <td></td>
                        </tr> 
                      <tr id="tr1">
                        <td id="fuente1">COLORES </td>
                        <td colspan="2" id="fuente1">
                          <select name="int_ref1_tol1_pm" id="int_ref1_tol1_pm" style="width:80px">
                          <option value=""<?php if (!(strcmp("", $row_referencia['pantone1_egp']))) {echo "selected=\"selected\"";} ?>>COLOR</option>
                          <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                              <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_referencia['pantone1_egp']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                            </option>
                          <?php } ?> 
                        </select>
                      </td>
                        <td colspan="2" id="fuente1"><input name="int_ref1_tol1_porc1_pm"  type="text"  id="int_ref1_tol1_porc1_pm" placeholder="%" size="3"value="<?php echo $row_mezcla['int_ref1_tol1_porc1_pm']; ?>"/>
                        </td>
                          <td id="fuente1">
                              <select name="int_ref3_tol3_pm" id="int_ref3_tol3_pm" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_referencia['pantone2_egp']))) {echo "selected=\"selected\"";} ?>>COLOR</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_referencia['pantone2_egp']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="int_ref3_tol3_porc3_pm"  type="text"  id="int_ref3_tol3_porc3_pm" placeholder="%" size="3"value="<?php echo $row_mezcla['int_ref3_tol3_porc3_pm'] ?>"/>
                          </td>

                            <td id="fuente1">
                                <select name="campo_1" id="campo_1" style="width:80px">
                                <option value=""<?php if (!(strcmp("", $row_referencia['pantone3_egp']))) {echo "selected=\"selected\"";} ?>>COLOR</option>
                                <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                    <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_referencia['pantone3_egp']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                  </option>
                                <?php } ?> 
                              </select>
                          </td>
                            <td id="fuente1">
                              <input name="campo_2"  type="text"  id="campo_2" placeholder="%" size="3"value="<?php echo $row_caract['campo_2'] ?>"/>
                            </td>
                            <td id="fuente1">
                                <select name="campo_3" id="campo_3" style="width:80px">
                                <option value=""<?php if (!(strcmp("", $row_referencia['pantone4_egp']))) {echo "selected=\"selected\"";} ?>>COLOR</option>
                                <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                    <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_referencia['pantone4_egp']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                  </option>
                                <?php } ?> 
                              </select>
                          </td>
                            <td id="fuente1">
                              <input name="campo_4"  type="text"  id="campo_4" placeholder="%" size="3"value="<?php echo $row_caract['campo_4'] ?>"/>
                            </td>
                            <td id="fuente1">
                                <select name="campo_5" id="campo_5" style="width:80px">
                                <option value=""<?php if (!(strcmp("", $row_referencia['pantone5_egp']))) {echo "selected=\"selected\"";} ?>>COLOR</option>
                                <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                    <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_referencia['pantone5_egp']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                  </option>
                                <?php } ?> 
                              </select>
                          </td>
                            <td id="fuente1">
                              <input name="campo_6"  type="text"  id="campo_6" placeholder="%" size="3"value="<?php echo $row_caract['campo_6'] ?>"/>
                            </td>
                            <td id="fuente1">
                                <select name="campo_7" id="campo_7" style="width:80px">
                                <option value=""<?php if (!(strcmp("", $row_referencia['pantone6_egp']))) {echo "selected=\"selected\"";} ?>>COLOR</option>
                                <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                    <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_referencia['pantone6_egp']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                  </option>
                                <?php } ?> 
                              </select>
                          </td>
                            <td id="fuente1">
                              <input name="campo_8"  type="text"  id="campo_8" placeholder="%" size="3"value="<?php echo $row_caract['campo_8'] ?>"/>
                            </td>
                            <td id="fuente1">
                                <select name="campo_9" id="campo_9" style="width:80px">
                                <option value=""<?php if (!(strcmp("", $row_referencia['pantone7_egp']))) {echo "selected=\"selected\"";} ?>>COLOR</option>
                                <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                    <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_referencia['pantone7_egp']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                  </option>
                                <?php } ?> 
                              </select>
                          </td>
                            <td id="fuente1">
                              <input name="campo_10"  type="text"  id="campo_10" placeholder="%" size="3"value="<?php echo $row_caract['campo_10'] ?>"/>
                            </td>
                            <td id="fuente1">
                                <select name="campo_11" id="campo_11" style="width:80px">
                                <option value=""<?php if (!(strcmp("", $row_referencia['pantone8_egp']))) {echo "selected=\"selected\"";} ?>>COLOR</option>
                                <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                    <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_referencia['pantone8_egp']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                  </option>
                                <?php } ?> 
                              </select>
                          </td>
                            <td id="fuente1">
                              <input name="campo_12"  type="text"  id="campo_12" placeholder="%" size="3"value="<?php echo $row_caract['campo_12'] ?>"/>
                            </td> 
                      </tr>
                      <tr>
                        <td id="fuente1">MEZCLAS</td>
                        <td colspan="2"id="fuente1">
                            <select name="int_ref1_tol2_pm" id="int_ref1_tol2_pm" style="width:80px">
                            <option value=""<?php if (!(strcmp("", $row_mezcla['int_ref1_tol2_pm']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                            <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_mezcla['int_ref1_tol2_pm']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                              </option>
                            <?php } ?> 
                          </select>
                      </td>
                        <td colspan="2"id="fuente1">
                          <input name="int_ref1_tol2_porc1_pm"  type="text"  id="int_ref1_tol2_porc1_pm" placeholder="%" size="3"value="<?php echo $row_mezcla['int_ref1_tol2_porc1_pm'] ?>"/>
                        </td> 
                          <td id="fuente1">
                              <select name="int_ref3_tol4_pm" id="int_ref3_tol4_pm" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_mezcla['int_ref3_tol4_pm']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_mezcla['int_ref3_tol4_pm']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="int_ref3_tol4_porc3_pm"  type="text"  id="int_ref3_tol4_porc3_pm" placeholder="%" size="3"value="<?php echo $row_mezcla['int_ref3_tol4_porc3_pm'] ?>"/>
                          </td>

                          <td id="fuente1">
                                <select name="campo_13" id="campo_13" style="width:80px">
                                <option value=""<?php if (!(strcmp("", $row_caract['campo_13']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                                <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                    <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_13']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                  </option>
                                <?php } ?> 
                              </select>
                          </td>
                            <td id="fuente1">
                              <input name="campo_14"  type="text"  id="campo_14" placeholder="%" size="3"value="<?php echo $row_caract['campo_14'] ?>"/>
                            </td>
                          <td id="fuente1">
                                <select name="campo_15" id="campo_15" style="width:80px">
                                <option value=""<?php if (!(strcmp("", $row_caract['campo_15']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                                <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                    <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_15']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                  </option>
                                <?php } ?> 
                              </select>
                          </td>
                            <td id="fuente1">
                              <input name="campo_16"  type="text"  id="campo_16" placeholder="%" size="3"value="<?php echo $row_caract['campo_16'] ?>"/>
                            </td>
                          <td id="fuente1">
                                <select name="campo_17" id="campo_17" style="width:80px">
                                <option value=""<?php if (!(strcmp("", $row_caract['campo_17']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                                <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                    <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_17']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                  </option>
                                <?php } ?> 
                              </select>
                          </td>
                            <td id="fuente1">
                              <input name="campo_18"  type="text"  id="campo_18" placeholder="%" size="3"value="<?php echo $row_caract['campo_18'] ?>"/>
                            </td>
                             <td id="fuente1">
                                <select name="campo_19" id="campo_19" style="width:80px">
                                <option value=""<?php if (!(strcmp("", $row_caract['campo_19']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                                <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                    <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_19']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                  </option>
                                <?php } ?> 
                              </select>
                          </td>
                            <td id="fuente1">
                              <input name="campo_20"  type="text"  id="campo_20" placeholder="%" size="3"value="<?php echo $row_caract['campo_20'] ?>"/>
                            </td>
                             <td id="fuente1">
                                <select name="campo_21" id="campo_21" style="width:80px">
                                <option value=""<?php if (!(strcmp("", $row_caract['campo_21']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                                <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                    <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_21']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                  </option>
                                <?php } ?> 
                              </select>
                          </td>
                            <td id="fuente1">
                              <input name="campo_22"  type="text"  id="campo_22" placeholder="%" size="3"value="<?php echo $row_caract['campo_22'] ?>"/>
                            </td>
                             <td id="fuente1">
                                <select name="campo_23" id="campo_23" style="width:80px">
                                <option value=""<?php if (!(strcmp("", $row_caract['campo_23']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                                <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                    <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_23']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                  </option>
                                <?php } ?> 
                              </select>
                          </td>
                            <td id="fuente1">
                              <input name="campo_24"  type="text"  id="campo_24" placeholder="%" size="3"value="<?php echo $row_caract['campo_24'] ?>"/>
                            </td>
                        </tr>
                        
                      </tr>
                      <tr id="tr1">
                        <td id="fuente1"></td>
                        <td colspan="2" id="fuente1">
                            <select name="int_ref1_tol3_pm" id="int_ref1_tol3_pm" style="width:80px">
                            <option value=""<?php if (!(strcmp("", $row_mezcla['int_ref1_tol3_pm']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                            <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_mezcla['int_ref1_tol3_pm']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                              </option>
                            <?php } ?> 
                          </select>
                      </td>
                        <td colspan="2" id="fuente1">
                          <input name="int_ref1_tol3_porc1_pm"  type="text"  id="int_ref1_tol3_porc1_pm" placeholder="%" size="3"value="<?php echo $row_mezcla['int_ref1_tol3_porc1_pm'] ?>"/>
                        </td>
                          <td id="fuente1">
                              <select name="campo_25" id="campo_25" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_25']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_25']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_26"  type="text"  id="campo_26" placeholder="%" size="3"value="<?php echo $row_caract['campo_26'] ?>"/>
                            </td>
                          <td id="fuente1">
                              <select name="campo_27" id="campo_27" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_27']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_27']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_28"  type="text"  id="campo_28" placeholder="%" size="3"value="<?php echo $row_caract['campo_28'] ?>"/>
                            </td>
                          <td id="fuente1">
                              <select name="campo_29" id="campo_29" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_29']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_29']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_30"  type="text"  id="campo_30" placeholder="%" size="3"value="<?php echo $row_caract['campo_30'] ?>"/>
                            </td>
                          <td id="fuente1">
                              <select name="campo_31" id="campo_31" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_31']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_31']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_32"  type="text"  id="campo_32" placeholder="%" size="3"value="<?php echo $row_caract['campo_32'] ?>"/>
                            </td>
                          <td id="fuente1">
                              <select name="campo_33" id="campo_33" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_33']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_33']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_34"  type="text"  id="campo_34" placeholder="%" size="3"value="<?php echo $row_caract['campo_34'] ?>"/>
                            </td>
                          <td id="fuente1">
                              <select name="campo_35" id="campo_35" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_35']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_35']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_36"  type="text"  id="campo_36" placeholder="%" size="3"value="<?php echo $row_caract['campo_36'] ?>"/>
                            </td>
                          <td id="fuente1">
                              <select name="campo_37" id="campo_37" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_37']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_37']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_38"  type="text"  id="campo_38" placeholder="%" size="3"value="<?php echo $row_caract['campo_38'] ?>"/>
                            </td> 
                        </tr>
                       
                        
                      </tr>
                      <tr>
                        <td id="fuente1"></td>
                        <td colspan="2" id="fuente1">
                            <select name="int_ref1_tol4_pm" id="int_ref1_tol4_pm" style="width:80px">
                            <option value=""<?php if (!(strcmp("", $row_mezcla['int_ref1_tol4_pm']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                            <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_mezcla['int_ref1_tol4_pm']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                              </option>
                            <?php } ?> 
                          </select>
                      </td>
                        <td colspan="2" id="fuente1">
                          <input name="int_ref1_tol4_porc1_pm"  type="text"  id="int_ref1_tol4_porc1_pm" placeholder="%" size="3"value="<?php echo $row_mezcla['int_ref1_tol4_porc1_pm'] ?>"/>
                        </td>
                      <td id="fuente1">
                              <select name="campo_39" id="campo_39" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_39']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_39']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_40"  type="text"  id="campo_40" placeholder="%" size="3"value="<?php echo $row_caract['campo_40'] ?>"/>
                            </td>
                          <td id="fuente1">
                              <select name="campo_41" id="campo_41" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_41']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_41']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_42"  type="text"  id="campo_42" placeholder="%" size="3"value="<?php echo $row_caract['campo_42'] ?>"/>
                            </td>
                          <td id="fuente1">
                              <select name="campo_43" id="campo_43" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_43']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_43']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_44"  type="text"  id="campo_44" placeholder="%" size="3"value="<?php echo $row_caract['campo_44'] ?>"/>
                            </td>
                          <td id="fuente1">
                              <select name="campo_45" id="campo_45" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_45']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_45']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_46"  type="text"  id="campo_46" placeholder="%" size="3"value="<?php echo $row_caract['campo_46'] ?>"/>
                            </td>
                          <td id="fuente1">
                              <select name="campo_47" id="campo_47" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_47']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_47']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_48"  type="text"  id="campo_48" placeholder="%" size="3"value="<?php echo $row_caract['campo_48'] ?>"/>
                            </td>
                          <td id="fuente1">
                              <select name="campo_49" id="campo_49" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_49']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_49']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_50"  type="text"  id="campo_50" placeholder="%" size="3"value="<?php echo $row_caract['campo_50'] ?>"/>
                            </td>
                          <td id="fuente1">
                              <select name="campo_51" id="campo_51" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_51']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_51']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_52"  type="text"  id="campo_52" placeholder="%" size="3"value="<?php echo $row_caract['campo_52'] ?>"/>
                            </td>
                           
                      <tr>
                        <td id="fuente1"></td>
                          <td colspan="2" id="fuente1">
                              <select name="int_ref2_tol1_pm" id="int_ref2_tol1_pm" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_mezcla['int_ref2_tol1_pm']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_mezcla['int_ref2_tol1_pm']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select> 
                        </td>
                          <td colspan="2" id="fuente1">
                            <input name="int_ref2_tol1_porc2_pm"  type="text"  id="int_ref2_tol1_porc2_pm" placeholder="%" size="3"value="<?php echo $row_mezcla['int_ref2_tol1_porc2_pm'] ?>"/>
                          </td>
                        <td id="fuente1">
                              <select name="campo_53" id="campo_53" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_53']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_53']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_54"  type="text"  id="campo_54" placeholder="%" size="3"value="<?php echo $row_caract['campo_54'] ?>"/>
                            </td>
                          <td id="fuente1">
                              <select name="campo_55" id="campo_55" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_55']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_55']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_56"  type="text"  id="campo_56" placeholder="%" size="3"value="<?php echo $row_caract['campo_56'] ?>"/>
                            </td>
                          <td id="fuente1">
                              <select name="campo_57" id="campo_57" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_57']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_57']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_58"  type="text"  id="campo_58" placeholder="%" size="3"value="<?php echo $row_caract['campo_58'] ?>"/>
                            </td>
                          <td id="fuente1">
                              <select name="campo_59" id="campo_59" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_59']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_59']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_60"  type="text"  id="campo_60" placeholder="%" size="3"value="<?php echo $row_caract['campo_60'] ?>"/>
                            </td>
                          <td id="fuente1">
                              <select name="campo_61" id="campo_61" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_61']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_61']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_62"  type="text"  id="campo_62" placeholder="%" size="3"value="<?php echo $row_caract['campo_62'] ?>"/>
                            </td>
                          <td id="fuente1">
                              <select name="campo_63" id="campo_63" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_63']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_63']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_64"  type="text"  id="campo_64" placeholder="%" size="3"value="<?php echo $row_caract['campo_64'] ?>"/>
                            </td>
                          <td id="fuente1">
                              <select name="campo_65" id="campo_65" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_65']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_65']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_66"  type="text"  id="campo_66" placeholder="%" size="3"value="<?php echo $row_caract['campo_66'] ?>"/>
                            </td>
                             
                      </tr>
                      <tr>
                        <td id="fuente1">ALCOHOL</td>
                          <td colspan="2" id="fuente1">
                              <select name="int_ref2_tol2_pm" id="int_ref2_tol2_pm" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_mezcla['int_ref2_tol2_pm']))) {echo "selected=\"selected\"";} ?>>ALCOHOL</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_mezcla['int_ref2_tol2_pm']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td colspan="2" id="fuente1">
                            <input name="int_ref2_tol2_porc2_pm"  type="text"  id="int_ref2_tol2_porc2_pm" placeholder="%" size="3"value="<?php echo $row_mezcla['int_ref2_tol2_porc2_pm'] ?>"/>
                          </td>
                        <td id="fuente1">
                              <select name="campo_67" id="campo_67" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_67']))) {echo "selected=\"selected\"";} ?>>ALCOHOL</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_67']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_68"  type="text"  id="campo_68" placeholder="%" size="3"value="<?php echo $row_caract['campo_68'] ?>"/>
                            </td>
                          <td id="fuente1">
                              <select name="campo_69" id="campo_69" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_69']))) {echo "selected=\"selected\"";} ?>>ALCOHOL</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_69']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_70"  type="text"  id="campo_70" placeholder="%" size="3"value="<?php echo $row_caract['campo_70'] ?>"/>
                            </td>
                          <td id="fuente1">
                              <select name="campo_71" id="campo_71" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_71']))) {echo "selected=\"selected\"";} ?>>ALCOHOL</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_71']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_72"  type="text"  id="campo_72" placeholder="%" size="3"value="<?php echo $row_caract['campo_72'] ?>"/>
                            </td>
                          <td id="fuente1">
                              <select name="campo_73" id="campo_73" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_73']))) {echo "selected=\"selected\"";} ?>>ALCOHOL</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_73']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_74"  type="text"  id="campo_74" placeholder="%" size="3"value="<?php echo $row_caract['campo_74'] ?>"/>
                            </td>
                          <td id="fuente1">
                              <select name="campo_75" id="campo_75" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_75']))) {echo "selected=\"selected\"";} ?>>ALCOHOL</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_75']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_76"  type="text"  id="campo_76" placeholder="%" size="3"value="<?php echo $row_caract['campo_76'] ?>"/>
                            </td>
                          <td id="fuente1">
                              <select name="campo_77" id="campo_77" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_77']))) {echo "selected=\"selected\"";} ?>>ALCOHOL</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_77']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_78"  type="text"  id="campo_78" placeholder="%" size="3"value="<?php echo $row_caract['campo_78'] ?>"/>
                            </td>
                          <td id="fuente1">
                              <select name="campo_79" id="campo_79" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_79']))) {echo "selected=\"selected\"";} ?>>ALCOHOL</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_79']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_80"  type="text"  id="campo_80" placeholder="%" size="3"value="<?php echo $row_caract['campo_80'] ?>"/>
                            </td> 
                             
                      </tr>
                      <tr>
                        <td id="fuente1">ACETATO NPA</td>
                          <td colspan="2" id="fuente1">
                              <select name="int_ref2_tol3_pm" id="int_ref2_tol3_pm" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_mezcla['int_ref2_tol3_pm']))) {echo "selected=\"selected\"";} ?>>ACETATO</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_mezcla['int_ref2_tol3_pm']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td colspan="2" id="fuente1"><input name="int_ref2_tol3_porc2_pm"  type="text"  id="int_ref2_tol3_porc2_pm" placeholder="%" size="3"value="<?php echo $row_mezcla['int_ref2_tol3_porc2_pm'] ?>"/>
                          </td>
                        <td id="fuente1">
                              <select name="campo_81" id="campo_81" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_81']))) {echo "selected=\"selected\"";} ?>>ACETATO</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_81']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_82"  type="text"  id="campo_82" placeholder="%" size="3"value="<?php echo $row_caract['campo_82'] ?>"/>
                            </td> 
                          <td id="fuente1">
                              <select name="campo_83" id="campo_83" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_83']))) {echo "selected=\"selected\"";} ?>>ACETATO</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_83']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_84"  type="text"  id="campo_84" placeholder="%" size="3"value="<?php echo $row_caract['campo_84'] ?>"/>
                            </td> 
                          <td id="fuente1">
                              <select name="campo_85" id="campo_85" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_85']))) {echo "selected=\"selected\"";} ?>>ACETATO</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_85']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_86"  type="text"  id="campo_86" placeholder="%" size="3"value="<?php echo $row_caract['campo_86'] ?>"/>
                            </td> 
                          <td id="fuente1">
                              <select name="campo_87" id="campo_87" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_87']))) {echo "selected=\"selected\"";} ?>>ACETATO</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_87']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_88"  type="text"  id="campo_88" placeholder="%" size="3"value="<?php echo $row_caract['campo_88'] ?>"/>
                            </td> 
                          <td id="fuente1">
                              <select name="campo_89" id="campo_89" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_89']))) {echo "selected=\"selected\"";} ?>>ACETATO</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_89']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_90"  type="text"  id="campo_90" placeholder="%" size="3"value="<?php echo $row_caract['campo_90'] ?>"/>
                            </td> 
                          <td id="fuente1">
                              <select name="campo_91" id="campo_91" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_91']))) {echo "selected=\"selected\"";} ?>>ACETATO</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_91']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_92"  type="text"  id="campo_92" placeholder="%" size="3"value="<?php echo $row_caract['campo_92'] ?>"/>
                            </td> 
                          <td id="fuente1">
                              <select name="campo_93" id="campo_93" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_93']))) {echo "selected=\"selected\"";} ?>>ACETATO</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_93']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_94"  type="text"  id="campo_94" placeholder="%" size="3"value="<?php echo $row_caract['campo_94'] ?>"/>
                            </td> 
                             
                      </tr>  
                      <tr>
                        <td id="fuente1">METOXIPROPANOL</td>
                          <td colspan="2" id="fuente1">
                              <select name="int_ref2_tol4_pm" id="int_ref2_tol4_pm" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_mezcla['int_ref2_tol4_pm']))) {echo "selected=\"selected\"";} ?>></option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_mezcla['int_ref2_tol4_pm']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td colspan="2" id="fuente1">
                            <input name="int_ref2_tol4_porc2_pm"  type="text"  id="int_ref2_tol4_porc2_pm" placeholder="%" size="3"value="<?php echo $row_mezcla['int_ref2_tol4_porc2_pm'] ?>"/>
                          </td>
                        <td id="fuente1">
                              <select name="campo_95" id="campo_95" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_95']))) {echo "selected=\"selected\"";} ?>></option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_95']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_96"  type="text"  id="campo_96" placeholder="%" size="3"value="<?php echo $row_caract['campo_96'] ?>"/>
                            </td>
                          <td id="fuente1">
                              <select name="campo_97" id="campo_97" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_97']))) {echo "selected=\"selected\"";} ?>></option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_97']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_98"  type="text"  id="campo_98" placeholder="%" size="3"value="<?php echo $row_caract['campo_98'] ?>"/>
                            </td>
                          <td id="fuente1">
                              <select name="campo_99" id="campo_99" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_99']))) {echo "selected=\"selected\"";} ?>></option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_99']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_100"  type="text"  id="campo_100" placeholder="%" size="3"value="<?php echo $row_caract['campo_100'] ?>"/>
                            </td>
                          <td id="fuente1">
                              <select name="campo_101" id="campo_101" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_101']))) {echo "selected=\"selected\"";} ?>></option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_101']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_102"  type="text"  id="campo_102" placeholder="%" size="3"value="<?php echo $row_caract['campo_102'] ?>"/>
                            </td>
                          <td id="fuente1">
                              <select name="campo_103" id="campo_103" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_103']))) {echo "selected=\"selected\"";} ?>></option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_103']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_104"  type="text"  id="campo_104" placeholder="%" size="3"value="<?php echo $row_caract['campo_104'] ?>"/>
                            </td>
                          <td id="fuente1">
                              <select name="campo_105" id="campo_105" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_105']))) {echo "selected=\"selected\"";} ?>></option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_105']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_106"  type="text"  id="campo_106" placeholder="%" size="3"value="<?php echo $row_caract['campo_106'] ?>"/>
                            </td>
                          <td id="fuente1">
                              <select name="campo_107" id="campo_107" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_107']))) {echo "selected=\"selected\"";} ?>></option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_107']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_108"  type="text"  id="campo_108" placeholder="%" size="3"value="<?php echo $row_caract['campo_108'] ?>"/>
                            </td> 
                      </tr>


                      <tr>
                        <td id="fuente1">VISCOSIDAD</td>
                        <td colspan="2" id="fuente1"> 
                          <input name="int_ref1_rpm_pm"  id="int_ref1_rpm_pm" style="width:50px" min="0"step="0.01" type="number" size="3" value="<?php echo $row_caract['int_ref1_rpm_pm'] ?>" placeholder="segundos"/> 
                        </td>
                         <td colspan="2" id="fuente1"> </td>
                        <td colspan="2" id="fuente1">
                          <input name="int_ref1_tol5_porc1_pm"  id="int_ref1_tol5_porc1_pm" style="width:50px" min="0"step="0.01" type="number" size="3" value="<?php echo $row_caract['int_ref1_tol5_porc1_pm'] ?>" placeholder="segundos"/>
                        </td>
                        <td colspan="2" id="fuente1">
                          <input name="int_ref2_rpm_pm"  id="int_ref2_rpm_pm" style="width:50px" min="0"step="0.01" type="number" size="3" value="<?php echo $row_caract['int_ref2_rpm_pm'] ?>" placeholder="segundos"/>
                        </td>
                        <td colspan="2" id="fuente1">
                          <input name="int_ref2_tol5_porc2_pm"  id="int_ref2_tol5_porc2_pm" style="width:50px" min="0"step="0.01" type="number" size="3" value="<?php echo $row_caract['int_ref2_tol5_porc2_pm'] ?>" placeholder="segundos"/>
                        </td>
                        <td colspan="2" id="fuente1">
                          <input name="int_ref3_rpm_pm"  id="int_ref3_rpm_pm" style="width:50px" min="0"step="0.01" type="number" size="3" value="<?php echo $row_caract['int_ref3_rpm_pm'] ?>" placeholder="segundos"/>
                        </td>
                        <td  colspan="2" id="fuente1">
                          <input name="int_ref3_tol5_porc3_pm"  id="int_ref3_tol5_porc3_pm" style="width:50px" min="0"step="0.01" type="number" size="3" value="<?php echo $row_caract['int_ref3_tol5_porc3_pm'] ?>" placeholder="segundos"/>
                        </td>
                        <td  colspan="2" id="fuente1">
                          <input name="campo_137"  id="campo_137" style="width:50px" min="0"step="0.01" type="number" size="3" value="<?php echo $row_caract['campo_137'] ?>" placeholder="segundos"/>
                        </td>
                        <td  colspan="2" id="fuente1">
                          <input name="campo_138"  id="campo_138" style="width:50px" min="0"step="0.01" type="number" size="3" value="<?php echo $row_caract['campo_138'] ?>" placeholder="segundos"/>
                        </td>
                      </tr>
                      <tr>
                        <td id="fuente1">ANILOX</td>
                          <td colspan="2" id="fuente1"> 
                              <select name="int_ref3_tol1_pm" id="int_ref3_tol1_pm" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_mezcla['int_ref3_tol1_pm']))) {echo "selected=\"selected\"";} ?>>ANILOX</option>
                              <?php  foreach($this->anilox as $row_anilox ) { ?>
                                  <option value="<?php echo $row_anilox['id_insumo']; ?>"<?php if (!(strcmp($row_anilox['id_insumo'], $row_mezcla['int_ref3_tol1_pm']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_anilox['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select> 
                        </td>
                          <td colspan="2" id="fuente1">
                            <input name="int_ref3_tol1_porc3_pm"  type="text"  id="int_ref3_tol1_porc3_pm" placeholder="%" size="3"value="<?php echo $row_mezcla['int_ref3_tol1_porc3_pm'] ?>"/>
                          </td>
                        <td id="fuente1">
                              <select name="campo_109" id="campo_109" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_109']))) {echo "selected=\"selected\"";} ?>>ANILOX</option>
                              <?php  foreach($this->anilox as $row_anilox ) { ?>
                                  <option value="<?php echo $row_anilox['id_insumo']; ?>"<?php if (!(strcmp($row_anilox['id_insumo'], $row_caract['campo_109']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_anilox['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_110"  type="text"  id="campo_110" placeholder="%" size="3"value="<?php echo $row_caract['campo_110'] ?>"/>
                            </td>
                        <td id="fuente1">
                              <select name="campo_111" id="campo_111" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_111']))) {echo "selected=\"selected\"";} ?>>ANILOX</option>
                              <?php  foreach($this->anilox as $row_anilox ) { ?>
                                  <option value="<?php echo $row_anilox['id_insumo']; ?>"<?php if (!(strcmp($row_anilox['id_insumo'], $row_caract['campo_111']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_anilox['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_112"  type="text"  id="campo_112" placeholder="%" size="3"value="<?php echo $row_caract['campo_112'] ?>"/>
                            </td>
                          <td id="fuente1">
                              <select name="campo_113" id="campo_113" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_113']))) {echo "selected=\"selected\"";} ?>>ANILOX</option>
                              <?php  foreach($this->anilox as $row_anilox ) { ?>
                                  <option value="<?php echo $row_anilox['id_insumo']; ?>"<?php if (!(strcmp($row_anilox['id_insumo'], $row_caract['campo_113']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_anilox['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_114"  type="text"  id="campo_114" placeholder="%" size="3"value="<?php echo $row_caract['campo_114'] ?>"/>
                            </td>
                          <td id="fuente1">
                              <select name="campo_115" id="campo_115" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_115']))) {echo "selected=\"selected\"";} ?>>ANILOX</option>
                              <?php  foreach($this->anilox as $row_anilox ) { ?>
                                  <option value="<?php echo $row_anilox['id_insumo']; ?>"<?php if (!(strcmp($row_anilox['id_insumo'], $row_caract['campo_115']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_anilox['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_116"  type="text"  id="campo_116" placeholder="%" size="3"value="<?php echo $row_caract['campo_116'] ?>"/>
                            </td>
                          <td id="fuente1">
                              <select name="campo_117" id="campo_117" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_117']))) {echo "selected=\"selected\"";} ?>>ANILOX</option>
                              <?php  foreach($this->anilox as $row_anilox ) { ?>
                                  <option value="<?php echo $row_anilox['id_insumo']; ?>"<?php if (!(strcmp($row_anilox['id_insumo'], $row_caract['campo_117']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_anilox['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_118"  type="text"  id="campo_118" placeholder="%" size="3"value="<?php echo $row_caract['campo_118'] ?>"/>
                            </td>
                          <td id="fuente1">
                              <select name="campo_119" id="campo_119" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_119']))) {echo "selected=\"selected\"";} ?>>ANILOX</option>
                              <?php  foreach($this->anilox as $row_anilox ) { ?>
                                  <option value="<?php echo $row_anilox['id_insumo']; ?>"<?php if (!(strcmp($row_anilox['id_insumo'], $row_caract['campo_119']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_anilox['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_120"  type="text"  id="campo_120" placeholder="%" size="3"value="<?php echo $row_caract['campo_120'] ?>"/>
                            </td>
                          <td id="fuente1">
                              <select name="campo_121" id="campo_121" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_121']))) {echo "selected=\"selected\"";} ?>>ANILOX</option>
                              <?php  foreach($this->anilox as $row_anilox ) { ?>
                                  <option value="<?php echo $row_anilox['id_insumo']; ?>"<?php if (!(strcmp($row_anilox['id_insumo'], $row_caract['campo_121']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_anilox['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_122"  type="text"  id="campo_122" placeholder="%" size="3"value="<?php echo $row_caract['campo_122'] ?>"/>
                            </td>
                      </tr>
                      <tr>
                        <td id="fuente1">BCM</td>
                          <td colspan="2" id="fuente1">
                              <select name="int_ref3_tol2_pm" id="int_ref3_tol2_pm" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_mezcla['int_ref3_tol2_pm']))) {echo "selected=\"selected\"";} ?>>BCM</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_mezcla['int_ref3_tol2_pm']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td colspan="2" colspan="2" id="fuente1">
                            <input name="int_ref3_tol2_porc3_pm"  type="text"  id="int_ref3_tol2_porc3_pm" placeholder="%" size="3"value="<?php echo $row_mezcla['int_ref3_tol2_porc3_pm'] ?>"/>
                          </td>
                        <td id="fuente1">
                              <select name="campo_123" id="campo_123" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_123']))) {echo "selected=\"selected\"";} ?>>BCM</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_123']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_124"  type="text"  id="campo_124" placeholder="%" size="3"value="<?php echo $row_caract['campo_124'] ?>"/>
                            </td>
                          <td id="fuente1">
                              <select name="campo_125" id="campo_125" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_125']))) {echo "selected=\"selected\"";} ?>>BCM</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_125']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_126"  type="text"  id="campo_126" placeholder="%" size="3"value="<?php echo $row_caract['campo_126'] ?>"/>
                            </td>
                          <td id="fuente1">
                              <select name="campo_127" id="campo_127" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_127']))) {echo "selected=\"selected\"";} ?>>BCM</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_127']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_128"  type="text"  id="campo_128" placeholder="%" size="3"value="<?php echo $row_caract['campo_128'] ?>"/>
                            </td>
                          <td id="fuente1">
                              <select name="campo_129" id="campo_129" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_129']))) {echo "selected=\"selected\"";} ?>>BCM</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_129']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_130"  type="text"  id="campo_130" placeholder="%" size="3"value="<?php echo $row_caract['campo_130'] ?>"/>
                            </td>
                          <td id="fuente1">
                              <select name="campo_131" id="campo_131" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_131']))) {echo "selected=\"selected\"";} ?>>BCM</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_131']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_132"  type="text"  id="campo_132" placeholder="%" size="3"value="<?php echo $row_caract['campo_132'] ?>"/>
                            </td>
                          <td id="fuente1">
                              <select name="campo_133" id="campo_133" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_133']))) {echo "selected=\"selected\"";} ?>>BCM</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_133']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_134"  type="text"  id="campo_134" placeholder="%" size="3"value="<?php echo $row_caract['campo_134'] ?>"/>
                            </td>
                          <td id="fuente1">
                              <select name="campo_135" id="campo_135" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_135']))) {echo "selected=\"selected\"";} ?>>BCM</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_135']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                            <input name="campo_136"  type="text"  id="campo_136" placeholder="%" size="3"value="<?php echo $row_caract['campo_136'] ?>"/>
                            </td>
                      </tr> 
                      <tr>
                        <td colspan="19" id="fuente1">
                          <textarea name="observ_pm" id="observ_pm" cols="80" rows="2" placeholder="OBSERVACIONES"><?php echo $row_mezcla['observ_pm'] ?></textarea>
                        </td>
                      </tr> 

                <!-- INICIA CARACTERISTICAS -->
                 <tr>
                  <td  colspan="10" id="titulo4"> 
                   <input name="extrusora"  type="hidden" id="extrusora" placeholder="Extrusora" size="20" value="<?php echo $row_mezcla['extrusora_mp']; ?>" readonly="readonly"/>  
                 </td>
                </tr>
                      <tr id="tr1">
                        <td colspan="19" id="titulo2">CARACTERISTICAS DE IMPRESION </td>
                      </tr>
                      <tr>
                        <td colspan="2" id="fuente1">Cantidad de Unidades</td>
                        <td colspan="2" nowrap="nowrap" id="fuente1">Temp Secado Grados C</td>
                        <td colspan="2" id="fuente1">Repeticion de Ancho</td>
                        <td colspan="2" id="fuente1">Rep. Perimetro</td>
                        <td colspan="2" id="fuente1">Arte Aprobado (0 SI, 1 NO)</td>
                        <td colspan="2" id="fuente1">Z</td>
                        <td colspan="2" id="fuente1">Guia Fotocelda (0 SI, 1 NO)</td>
                        <td colspan="2" id="fuente1">Velocidad Maquina</td>
                      </tr>
                      <tr>
                        <td  colspan="2" id="fuente1">
                          <input name="campo_139"  id="campo_139" style="width:50px" min="0" step="1" type="number" size="3" value="<?php echo $row_caract['campo_139'] ?>" placeholder="caracteristicas"/>
                        </td>
                        <td  colspan="2" id="fuente1">
                          <input name="campo_140"  id="campo_140" style="width:50px" min="0" step="1" type="number" size="3" value="<?php echo $row_caract['campo_140'] ?>" placeholder="caracteristicas"/>
                        </td>
                        <td  colspan="2" id="fuente1">
                          <input name="campo_141"  id="campo_141" style="width:50px" min="0" step="1" type="number" size="3" value="<?php echo $row_caract['campo_141'] ?>" placeholder="caracteristicas"/>
                        </td>
                        <td  colspan="2" id="fuente1">
                          <input name="campo_142"  id="campo_142" style="width:50px" min="0" step="1" type="number" size="3" value="<?php echo $row_caract['campo_142'] ?>" placeholder="caracteristicas"/>
                        </td>
                        <td  colspan="2" id="fuente1">
                          <input name="campo_143"  id="campo_143" style="width:50px" min="0" step="1" type="number" size="3" value="<?php echo $row_caract['campo_143'] ?>" placeholder="caracteristicas"/>
                        </td>
                        <td  colspan="2" id="fuente1">
                          <input name="campo_144"  id="campo_144" style="width:50px" min="0" step="1" type="number" size="3" value="<?php echo $row_caract['campo_144'] ?>" placeholder="caracteristicas"/>
                        </td>
                        <td  colspan="2" id="fuente1">
                          <input name="campo_145"  id="campo_145" style="width:50px" min="0" step="1" type="number" size="3" value="<?php echo $row_caract['campo_145'] ?>" placeholder="caracteristicas"/>
                        </td>
                        <td  colspan="2" id="fuente1">
                          <input name="campo_146"  id="campo_146" style="width:50px" min="0" step="1" type="number" size="3" value="<?php echo $row_caract['campo_146'] ?>" placeholder="caracteristicas"/>
                        </td>
                      </tr>   
                <tr id="tr1">
                  <td colspan="19" id="fuente2">
                    <div class="panel-footer" >
                      <input type="hidden" name="fecha_registro_pm" id="fecha_registro_pm" value="<?php echo date('Y-m-d') ?>"/> 
                      <input type="hidden" name="id_ref_pm" id="id_ref_pm" value="<?php echo $row_referencia['id_ref']; ?>"/>
                      <input type="hidden" name="int_cod_ref_pm" id="int_cod_ref_pm" value="<?php echo $row_referencia['cod_ref']; ?>"/>
                      <input type="hidden" name="version_ref_pm" id="version_ref_pm" value="<?php echo $row_referencia['version_ref']; ?>"/>
                      <input type="hidden" name="str_registro_pm" id="str_registro_pm" value="<?php echo $_SESSION['Usuario'] ?>" />
                      <input type="hidden" name="id_proceso" id="id_proceso" value="2"/>
                      <input type="hidden" name="b_borrado_pm" id="b_borrado_pm" value="0"/>

                      <input type="hidden" name="cod_ref" id="cod_ref" value="<?php echo $row_referencia['cod_ref']; ?>"/> 
                      <input type="hidden" name="proceso" id="proceso" value="2"/>
                      <input type="hidden" name="modifico" id="modifico" value="<?php echo $_SESSION['Usuario'] ?>"/>
                      <input type="hidden" name="fecha_modif" id="fecha_modif" value="<?php echo date('Y-m-d H:i:s') ?>"/> 
                      <input class="botonGeneral" type="submit" name="GUARDAR" id="GUARDAR" value="GUARDAR" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <a class="botonFinalizar" style="text-decoration:none; "href="javascript:Salir('view_index.php?c=cmezclasIm&a=Salir')" >SALIR</a>  
                    </div>
                  </td>
                </tr> 
                </table>
                <input type="hidden" name="MM_insert" value="form1">
                </form>            
              <!-- FIN CARACTERISTICAS -->
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

  $(document).ready(function() { 
    refcopia = $( ".refcopia" ).val(); 
    if(refcopia !='')
      vercopiaMezcla();

     extrusoraNumero();

    $( "#extrusora_mp" ).on( "change", function() {
         extrusoraNumero();
    }); 

  });

function vercopiaMezcla(){ 
        
        $('.refcopia').show();  
 
   }

  function copiaMezcla(){
    refcopia = $( "#refcopia" ).val();
    cod_ref = $( "#cod_ref" ).val();  
    if(refcopia)
    window.location="view_index.php?c=cmezclasIm&a=Carat&cod_ref="+cod_ref+"&cod_refcopia="+refcopia;

  }
  
  function Impresora(){
  $( "#extrusora" ).val($( "#extrusora_mp" ).val());
  }



  /*function extrusoraNumero(){
    if($( "#extrusora_mp" ).val() == "Maquina Extrusora 1") { 
       $('.zonaextruder1').show();
       $('.zonaextruder2').hide();
       $('.zonaimpr2').hide(); 

    }else if($( "#extrusora_mp" ).val() == "Maquina Extrusora 2"){  
       $('.zonaextruder1').hide();
       $('.zonaextruder2').show();
       $('.bloquef').text('Bloque Fijo');
       $('.cabezal1').text('Cabezal');
       $('.labios').text('Labios');
       $('.zona1').text('Zona 1');
       $('.zona2').text('Zona 2');
       $('.zona3').text('Zona 3');
       $('.zona4').text('Zona 4'); 
       $('.zonaimpr2').show();


    }
  }*/

</script>

<?php
mysql_free_result($usuario);
mysql_free_result($referencia);
mysql_free_result($referencia_copia); 
mysql_free_result($mezcla);
mysql_free_result($caract); 
mysql_free_result($ultimo);
?>
