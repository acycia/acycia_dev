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

<script type="text/javascript">
    $(document).ready(function() { $(".busqueda").select2(); });
</script>

</head>
<body>

    <div class="spiffy_content"> <!-- este define el fondo gris de lado a lado si se coloca dentro de tabla inicial solamente coloca borde gris -->
      <div align="center">
        <table style="width: 100%">
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
              <form action="view_index.php?c=cmezclasIm&a=GuardarTintas&id=<?php echo $_GET['cod_ref'];?>" method="post" enctype="multipart/form-data" name="form1">
                <table class="table table-striped">
                <tr id="tr1">
                  <td colspan="28" id="titulo2">CARACTERISTICAS DE IMPRESION </td>
                </tr>
                <tr>
                  <td colspan="3" rowspan="5" id="dato2"><img src="images/logoacyc.jpg"/></td>
                  <td colspan="28" id="dato3"><a href="manteni.php"><img src="images/opciones.gif" style="cursor:hand;" alt="DISE&Ntilde;O Y DESARROLLO" title="LISTADO MEZCLAS Y CARACTERISTICAS" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" title="MENU PRINCIPAL" border="0"/></a>
                    <a href="view_index.php?c=cmezclasIm&a=Tintas&cod_ref=<?php echo $_GET['cod_ref'];?>"><img src="images/hoja.gif" alt="VISTA" title="VISTA" border="0"></a></td>
                </tr>
                <tr id="tr1">
                  <td width="182" colspan="10" nowrap="nowrap" id="fuente1">Fecha Ingreso
                   <b style="color:red;" >
                    <input name="fecha_rkp" type="datetime" min="2000-01-02" value="<?php echo $_GET['fecha']; ?>" size="19" readonly="readonly"> </b>
                  <td colspan="9" id="fuente1"> Ingresado por
                   <b style="color:red;" ><?php echo $row_caract['usuario']; ?></b>
                    <?php //$numero=$row_ultimo['id_cv']+1;  $numero; ?>
                    <!--<input type="hidden" name="id_cv" id="id_cv" value="<?php echo $numero; ?>"/>--></td>
                </tr>
                <tr>
                  <td colspan="8" nowrap="nowrap" id="fuente2">&nbsp;</td>
                  <td colspan="7" nowrap="nowrap" id="fuente2">&nbsp;</td>
                  <td colspan="4" id="fuente2">&nbsp;</td>
                </tr>
                <tr id="tr1">
                  <td colspan="8" nowrap="nowrap" id="fuente2">Referencia</td>
                  <td colspan="7" id="fuente2">Version</td>
                  <td colspan="4" id="dato1">&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="8" nowrap="nowrap" id="numero2"> 
                      <?php echo $row_referencia['cod_ref']; ?>
                    </td>
                  <td colspan="7" nowrap="nowrap" id="numero2"> 
                      <?php echo $row_referencia['version_ref']; ?>
                    </td>
                  <td colspan="4" id="fuente1">
                     
                </td> 
                </tr>  
                   <!--  INICIA MEZCLAS DE EXTRUDER -->
                      <tr id="tr1">
                        <td colspan="28" id="titulo4">IMPRESION</td>
                        </tr>
                        <tr>
                         <td  colspan="28" id="titulo4"> 
                          Impresora : 
                          <?php echo $row_mezcla['extrusora_mp']; ?> 
                        </td>
                       </tr>
                      <tr id="tr1">
                        <td rowspan="2" id="fuente1"> </td>
                        <td colspan="5" id="fuente1">UNIDAD 1</td>
                        <td colspan="3" id="fuente1">UNIDAD 2</td>
                        <td colspan="3" id="fuente1">UNIDAD 3</td>
                        <td colspan="3" id="fuente1">UNIDAD 4</td>
                        <td colspan="3" id="fuente1">UNIDAD 5</td>
                        <td colspan="3" id="fuente1">UNIDAD 6</td>
                        <td colspan="3" id="fuente1">UNIDAD 7</td>
                        <td colspan="4" id="fuente1">UNIDAD 8</td> 
                        </tr> 
                        <tr>
                          <td></td>
                        </tr> 
                      <tr id="tr1">
                        <td id="fuente1">COLORES</td>
                        <td colspan="2" id="fuente1">
                          <select name="id_i[]" id="id_i[]" style="width:80px">
                          <option value=""<?php if (!(strcmp("", $row_mezcla['int_ref1_tol1_pm']))) {echo "selected=\"selected\"";} ?>>COLOR</option>
                          <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                              <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_mezcla['int_ref1_tol1_pm']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                            </option>
                          <?php } ?> 
                        </select>
                      </td>
                        <td colspan="2" id="fuente1">
                          <b style="color:red;" > <?php echo $row_mezcla['int_ref1_tol1_porc1_pm']; ?></b>
                        </td>
                        <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_mezcla['int_ref3_tol3_pm']))) {echo "selected=\"selected\"";} ?>>COLOR</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_mezcla['int_ref3_tol3_pm']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_mezcla['int_ref3_tol3_porc3_pm'] ?></b>
                          </td>
                          <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                            <td id="fuente1">
                                <select name="id_i[]" id="id_i[]" style="width:80px">
                                <option value=""<?php if (!(strcmp("", $row_caract['campo_1']))) {echo "selected=\"selected\"";} ?>>COLOR</option>
                                <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                    <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_1']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                  </option>
                                <?php } ?> 
                              </select>
                          </td>
                            <td id="fuente1">
                             <b style="color:red;" > <?php echo $row_caract['campo_2'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                            <td id="fuente1">
                                <select name="id_i[]" id="id_i[]" style="width:80px">
                                <option value=""<?php if (!(strcmp("", $row_caract['campo_3']))) {echo "selected=\"selected\"";} ?>>COLOR</option>
                                <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                    <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_3']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                  </option>
                                <?php } ?> 
                              </select>
                          </td>
                            <td id="fuente1">
                             <b style="color:red;" > <?php echo $row_caract['campo_4'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0" step="0.01" value=""/></td> 
                            <td id="fuente1">
                                <select name="id_i[]" id="id_i[]" style="width:80px">
                                <option value=""<?php if (!(strcmp("", $row_caract['campo_5']))) {echo "selected=\"selected\"";} ?>>COLOR</option>
                                <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                    <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_5']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                  </option>
                                <?php } ?> 
                              </select>
                          </td>
                            <td id="fuente1">
                             <b style="color:red;" > <?php echo $row_caract['campo_6'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                            <td id="fuente1">
                                <select name="id_i[]" id="id_i[]" style="width:80px">
                                <option value=""<?php if (!(strcmp("", $row_caract['campo_7']))) {echo "selected=\"selected\"";} ?>>COLOR</option>
                                <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                    <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_7']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                  </option>
                                <?php } ?> 
                              </select>
                          </td>
                            <td id="fuente1">
                             <b style="color:red;" > <?php echo $row_caract['campo_8'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                            <td id="fuente1">
                                <select name="id_i[]" id="id_i[]" style="width:80px">
                                <option value=""<?php if (!(strcmp("", $row_caract['campo_9']))) {echo "selected=\"selected\"";} ?>>COLOR</option>
                                <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                    <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_9']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                  </option>
                                <?php } ?> 
                              </select>
                          </td>
                            <td id="fuente1">
                             <b style="color:red;" > <?php echo $row_caract['campo_10'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                            <td id="fuente1">
                                <select name="id_i[]" id="id_i[]" style="width:80px">
                                <option value=""<?php if (!(strcmp("", $row_caract['campo_11']))) {echo "selected=\"selected\"";} ?>>COLOR</option>
                                <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                    <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_11']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                  </option>
                                <?php } ?> 
                              </select>
                          </td>
                            <td id="fuente1">
                             <b style="color:red;" > <?php echo $row_caract['campo_12'] ?></b>
                            </td> 
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                      </tr>
                      <tr>
                        <td id="fuente1">MEZCLAS</td>
                        <td colspan="2"id="fuente1">
                            <select name="id_i[]" id="id_i[]" style="width:80px">
                            <option value=""<?php if (!(strcmp("", $row_mezcla['int_ref1_tol2_pm']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                            <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_mezcla['int_ref1_tol2_pm']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                              </option>
                            <?php } ?> 
                          </select>
                      </td>
                        <td colspan="2"id="fuente1">
                         <b style="color:red;" > <?php echo $row_mezcla['int_ref1_tol2_porc1_pm'] ?></b>
                        </td> 
                        <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_mezcla['int_ref3_tol4_pm']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_mezcla['int_ref3_tol4_pm']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_mezcla['int_ref3_tol4_porc3_pm'] ?></b>
                          </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                                <select name="id_i[]" id="id_i[]" style="width:80px">
                                <option value=""<?php if (!(strcmp("", $row_caract['campo_13']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                                <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                    <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_13']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                  </option>
                                <?php } ?> 
                              </select>
                          </td>
                            <td id="fuente1">
                             <b style="color:red;" > <?php echo $row_caract['campo_14'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                                <select name="id_i[]" id="id_i[]" style="width:80px">
                                <option value=""<?php if (!(strcmp("", $row_caract['campo_15']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                                <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                    <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_15']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                  </option>
                                <?php } ?> 
                              </select>
                          </td>
                            <td id="fuente1">
                             <b style="color:red;" > <?php echo $row_caract['campo_16'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                                <select name="id_i[]" id="id_i[]" style="width:80px">
                                <option value=""<?php if (!(strcmp("", $row_caract['campo_17']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                                <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                    <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_17']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                  </option>
                                <?php } ?> 
                              </select>
                          </td>
                            <td id="fuente1">
                             <b style="color:red;" > <?php echo $row_caract['campo_18'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                             <td id="fuente1">
                                <select name="id_i[]" id="id_i[]" style="width:80px">
                                <option value=""<?php if (!(strcmp("", $row_caract['campo_19']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                                <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                    <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_19']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                  </option>
                                <?php } ?> 
                              </select>
                          </td>
                            <td id="fuente1">
                             <b style="color:red;" > <?php echo $row_caract['campo_20'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                             <td id="fuente1">
                                <select name="id_i[]" id="id_i[]" style="width:80px">
                                <option value=""<?php if (!(strcmp("", $row_caract['campo_21']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                                <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                    <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_21']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                  </option>
                                <?php } ?> 
                              </select>
                          </td>
                            <td id="fuente1">
                             <b style="color:red;" > <?php echo $row_caract['campo_22'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                             <td id="fuente1">
                                <select name="id_i[]" id="id_i[]" style="width:80px">
                                <option value=""<?php if (!(strcmp("", $row_caract['campo_23']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                                <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                    <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_23']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                  </option>
                                <?php } ?> 
                              </select>
                          </td>
                            <td id="fuente1">
                             <b style="color:red;" > <?php echo $row_caract['campo_24'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                        </tr>
                        
                      </tr>
                      <tr id="tr1">
                        <td id="fuente1"></td>
                        <td colspan="2" id="fuente1">
                            <select name="id_i[]" id="id_i[]" style="width:80px">
                            <option value=""<?php if (!(strcmp("", $row_mezcla['int_ref1_tol3_pm']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                            <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_mezcla['int_ref1_tol3_pm']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                              </option>
                            <?php } ?> 
                          </select>
                      </td>
                        <td colspan="2" id="fuente1">
                         <b style="color:red;" > <?php echo $row_mezcla['int_ref1_tol3_porc1_pm'] ?></b>
                        </td>
                        <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_25']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_25']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_26'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_27']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_27']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_28'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_29']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_29']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_30'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_31']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_31']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_32'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_33']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_33']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_34'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_35']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_35']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_36'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_37']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_37']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_38'] ?></b>
                            </td> 
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                        </tr> 
                      </tr>
                      <tr>
                        <td id="fuente1"></td>
                        <td colspan="2" id="fuente1">
                            <select name="id_i[]" id="id_i[]" style="width:80px">
                            <option value=""<?php if (!(strcmp("", $row_mezcla['int_ref1_tol4_pm']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                            <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_mezcla['int_ref1_tol4_pm']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                              </option>
                            <?php } ?> 
                          </select>
                      </td>
                        <td colspan="2" id="fuente1">
                         <b style="color:red;" > <?php echo $row_mezcla['int_ref1_tol4_porc1_pm'] ?></b>
                        </td>
                        <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                      <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_39']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_39']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_40'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_41']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_41']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_42'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_43']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_43']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_44'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_45']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_45']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_46'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_47']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_47']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_48'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_49']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_49']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_50'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_51']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_51']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_52'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                      <tr>
                        <td id="fuente1"></td>
                          <td colspan="2" id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_mezcla['int_ref2_tol1_pm']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_mezcla['int_ref2_tol1_pm']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select> 
                        </td>
                          <td colspan="2" id="fuente1">
                           <b style="color:red;" > <?php echo $row_mezcla['int_ref2_tol1_porc2_pm'] ?></b>
                          </td>
                          <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                        <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_53']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_53']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_54'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_55']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_55']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_56'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_57']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_57']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_58'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_59']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_59']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_60'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_61']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_61']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_62'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_63']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_63']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_64'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_65']))) {echo "selected=\"selected\"";} ?>>MEZCLAS</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_65']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_66'] ?></b>
                         </td>
                         <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td>   
                      </tr>
                      <tr>
                        <td id="fuente1">ALCOHOL</td>
                          <td colspan="2" id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_mezcla['int_ref2_tol2_pm']))) {echo "selected=\"selected\"";} ?>>ALCOHOL</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_mezcla['int_ref2_tol2_pm']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td colspan="2" id="fuente1">
                           <b style="color:red;" > <?php echo $row_mezcla['int_ref2_tol2_porc2_pm'] ?></b>
                          </td>
                          <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                        <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_67']))) {echo "selected=\"selected\"";} ?>>ALCOHOL</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_67']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_68'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_69']))) {echo "selected=\"selected\"";} ?>>ALCOHOL</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_69']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_70'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_71']))) {echo "selected=\"selected\"";} ?>>ALCOHOL</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_71']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_72'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_73']))) {echo "selected=\"selected\"";} ?>>ALCOHOL</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_73']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_74'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_75']))) {echo "selected=\"selected\"";} ?>>ALCOHOL</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_75']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_76'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_77']))) {echo "selected=\"selected\"";} ?>>ALCOHOL</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_77']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_78'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_79']))) {echo "selected=\"selected\"";} ?>>ALCOHOL</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_79']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_80'] ?></b>
                            </td> 
                          <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td>    
                      </tr>
                      <tr>
                        <td id="fuente1">ACETATO NPA</td>
                          <td colspan="2" id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_mezcla['int_ref2_tol3_pm']))) {echo "selected=\"selected\"";} ?>>ACETATO</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_mezcla['int_ref2_tol3_pm']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td colspan="2" id="fuente1">
                            <b style="color:red;" ><?php echo $row_mezcla['int_ref2_tol3_porc2_pm'] ?></b>
                          </td>
                          <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                        <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_81']))) {echo "selected=\"selected\"";} ?>>ACETATO</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_81']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_82'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td>  
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_83']))) {echo "selected=\"selected\"";} ?>>ACETATO</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_83']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_84'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td>  
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_85']))) {echo "selected=\"selected\"";} ?>>ACETATO</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_85']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_86'] ?></b>
                            </td> 
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_87']))) {echo "selected=\"selected\"";} ?>>ACETATO</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_87']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_88'] ?></b>
                            </td> 
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_89']))) {echo "selected=\"selected\"";} ?>>ACETATO</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_89']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_90'] ?></b>
                            </td> 
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_91']))) {echo "selected=\"selected\"";} ?>>ACETATO</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_91']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_92'] ?></b>
                            </td> 
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_93']))) {echo "selected=\"selected\"";} ?>>ACETATO</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_93']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_94'] ?></b>
                            </td> 
                           <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td>   
                      </tr>  
                      <tr>
                        <td id="fuente1">METOXIPROPANOL</td>
                          <td colspan="2" id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_mezcla['int_ref2_tol4_pm']))) {echo "selected=\"selected\"";} ?>></option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_mezcla['int_ref2_tol4_pm']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td colspan="2" id="fuente1">
                           <b style="color:red;" > <?php echo $row_mezcla['int_ref2_tol4_porc2_pm'] ?></b>
                          </td>
                          <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                        <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_95']))) {echo "selected=\"selected\"";} ?>> </option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_95']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_96'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_97']))) {echo "selected=\"selected\"";} ?>> </option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_97']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_98'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_99']))) {echo "selected=\"selected\"";} ?>> </option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_99']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_100'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_101']))) {echo "selected=\"selected\"";} ?>> </option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_101']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_102'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_103']))) {echo "selected=\"selected\"";} ?>> </option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_103']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_104'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_105']))) {echo "selected=\"selected\"";} ?>> </option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_105']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_106'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_107']))) {echo "selected=\"selected\"";} ?>> </option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_107']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_108'] ?></b>
                            </td> 
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                      </tr>


                      <tr>
                        <td id="fuente1"></td> 
                      </tr>
                      <tr>
                        <td id="fuente1">ANILOX</td>
                          <td colspan="2" id="fuente1"> 
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_mezcla['int_ref3_tol1_pm']))) {echo "selected=\"selected\"";} ?>>ANILOX</option>
                              <?php  foreach($this->anilox as $row_anilox ) { ?>
                                  <option value="<?php echo $row_anilox['id_insumo']; ?>"<?php if (!(strcmp($row_anilox['id_insumo'], $row_mezcla['int_ref3_tol1_pm']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_anilox['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select> 
                        </td>
                          <td colspan="2" id="fuente1">
                           <b style="color:red;" > <?php echo $row_mezcla['int_ref3_tol1_porc3_pm'] ?></b>
                          </td>
                          <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                        <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_109']))) {echo "selected=\"selected\"";} ?>>ANILOX</option>
                              <?php  foreach($this->anilox as $row_anilox ) { ?>
                                  <option value="<?php echo $row_anilox['id_insumo']; ?>"<?php if (!(strcmp($row_anilox['id_insumo'], $row_caract['campo_109']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_anilox['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_110'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                        <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_111']))) {echo "selected=\"selected\"";} ?>>ANILOX</option>
                              <?php  foreach($this->anilox as $row_anilox ) { ?>
                                  <option value="<?php echo $row_anilox['id_insumo']; ?>"<?php if (!(strcmp($row_anilox['id_insumo'], $row_caract['campo_111']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_anilox['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_112'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_113']))) {echo "selected=\"selected\"";} ?>>ANILOX</option>
                              <?php  foreach($this->anilox as $row_anilox ) { ?>
                                  <option value="<?php echo $row_anilox['id_insumo']; ?>"<?php if (!(strcmp($row_anilox['id_insumo'], $row_caract['campo_113']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_anilox['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_114'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_115']))) {echo "selected=\"selected\"";} ?>>ANILOX</option>
                              <?php  foreach($this->anilox as $row_anilox ) { ?>
                                  <option value="<?php echo $row_anilox['id_insumo']; ?>"<?php if (!(strcmp($row_anilox['id_insumo'], $row_caract['campo_115']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_anilox['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_116'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_117']))) {echo "selected=\"selected\"";} ?>>ANILOX</option>
                              <?php  foreach($this->anilox as $row_anilox ) { ?>
                                  <option value="<?php echo $row_anilox['id_insumo']; ?>"<?php if (!(strcmp($row_anilox['id_insumo'], $row_caract['campo_117']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_anilox['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_118'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_119']))) {echo "selected=\"selected\"";} ?>>ANILOX</option>
                              <?php  foreach($this->anilox as $row_anilox ) { ?>
                                  <option value="<?php echo $row_anilox['id_insumo']; ?>"<?php if (!(strcmp($row_anilox['id_insumo'], $row_caract['campo_119']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_anilox['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_120'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_121']))) {echo "selected=\"selected\"";} ?>>ANILOX</option>
                              <?php  foreach($this->anilox as $row_anilox ) { ?>
                                  <option value="<?php echo $row_anilox['id_insumo']; ?>"<?php if (!(strcmp($row_anilox['id_insumo'], $row_caract['campo_121']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_anilox['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_122'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                      </tr>
                      <tr>
                        <td id="fuente1">BCM</td>
                          <td colspan="2" id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_mezcla['int_ref3_tol2_pm']))) {echo "selected=\"selected\"";} ?>>BCM</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_mezcla['int_ref3_tol2_pm']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td colspan="2" colspan="2" id="fuente1">
                           <b style="color:red;" > <?php echo $row_mezcla['int_ref3_tol2_porc3_pm'] ?></b>
                          </td>
                          <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                        <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_123']))) {echo "selected=\"selected\"";} ?>>BCM</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_123']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_124'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_125']))) {echo "selected=\"selected\"";} ?>>BCM</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_125']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_126'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_127']))) {echo "selected=\"selected\"";} ?>>BCM</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_127']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_128'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_129']))) {echo "selected=\"selected\"";} ?>>BCM</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_129']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_130'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_131']))) {echo "selected=\"selected\"";} ?>>BCM</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_131']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_132'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_133']))) {echo "selected=\"selected\"";} ?>>BCM</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_133']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_134'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                          <td id="fuente1">
                              <select name="id_i[]" id="id_i[]" style="width:80px">
                              <option value=""<?php if (!(strcmp("", $row_caract['campo_135']))) {echo "selected=\"selected\"";} ?>>BCM</option>
                              <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                  <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_caract['campo_135']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                                </option>
                              <?php } ?> 
                            </select>
                        </td>
                          <td id="fuente1">
                           <b style="color:red;" > <?php echo $row_caract['campo_136'] ?></b>
                            </td>
                            <td id="fuente1" ><input class="nameee" name="cant[]" type="number" style="width:50px" placeholder="kilos" min="0"step="0.01" value=""/></td> 
                      </tr>  

                <!-- INICIA CARACTERISTICAS -->
                 <tr>
                  <td  colspan="10" id="titulo4"> 
                    
                 </td>
                </tr>
                      
                <tr id="tr1">
                  <td colspan="28" id="fuente2">
                    <div class="panel-footer" > 
                        
                      <input type="hidden" name="op_rp" id="op_rp" value="<?php echo $_GET['id_op']; ?>"/>
                      <input type="hidden" name="int_rollo_rkp" id="int_rollo_rkp" value="<?php echo $_GET['rollo']; ?>"/>
                      <input type="hidden" name="id_proceso_rkp" id="id_proceso_rkp" value="2" /> 
                      <input type="hidden" name="cod_ref" id="cod_ref" value="<?php echo $_GET['cod_ref']; ?>"/> 

                      <input class="botonGeneral" type="submit" name="GuardarTintas" id="GuardarTintas" value="GuardarTintas" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
