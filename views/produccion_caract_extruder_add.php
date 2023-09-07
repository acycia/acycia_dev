<?php
     require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
     require (ROOT_BBDD); 
?>
<?php require_once('Connections/conexion1.php'); ?>
<?php

require_once("db/db.php"); 
require_once("Controller/Cmezclas.php");

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
        <table> <!-- style="width: 100%" -->
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
              <form action="view_index.php?c=cmezclas&a=Guardar&id=<?php echo $_GET['cod_ref'];?>" method="post" enctype="multipart/form-data" name="form1">
                <table class="table table-bordered table-sm">
                <tr id="tr1">
                  <td colspan="10" id="titulo2">CARACTERISTICAS DE EXTRUSION </td>
                </tr>
                <tr>
                  <td colspan="3" rowspan="5" id="dato2"><img src="images/logoacyc.jpg"/></td>
                  <td colspan="7" id="dato3"><a href="manteni.php"><img src="images/opciones.gif" style="cursor:hand;" alt="DISE&Ntilde;O Y DESARROLLO" title="LISTADO MEZCLAS Y CARACTERISTICAS" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" title="MENU PRINCIPAL" border="0"/></a>
                    <a href="view_index.php?c=cmezclas&a=Mezcla&cod_ref=<?php echo $_GET['cod_ref'];?>"><img src="images/hoja.gif" alt="VISTA" title="VISTA" border="0"></a></td>
                </tr>
                <tr id="tr1">
                  <td width="182" colspan="3" nowrap="nowrap" id="fuente1">Fecha Ingreso
                    <input name="fecha_registro" type="date" min="2000-01-02" value="<?php echo date("Y-m-d"); ?>" size="10" autofocus /></td>
                  <td colspan="4" id="fuente1"> Ingresado por
                    <input name="usuario" type="text" value="<?php echo $_SESSION['Usuario']; ?>" size="27" readonly="readonly"/>
                    <?php //$numero=$row_ultimo['id_cv']+1;  $numero; ?>
                    <!--<input type="hidden" name="id_cv" id="id_cv" value="<?php echo $numero; ?>"/>--></td>
                </tr>
                <tr>
                  <td colspan="3" nowrap="nowrap" id="fuente2">&nbsp;</td>
                  <td width="126" colspan="2" nowrap="nowrap" id="fuente2">&nbsp;</td>
                  <td width="235" colspan="2" id="fuente2">&nbsp;</td>
                </tr>
                <tr id="tr1">
                  <td colspan="3" nowrap="nowrap" id="fuente2">Referencia</td>
                  <td colspan="2" id="fuente2">Version</td>
                  <td nowrap="nowrap" colspan="2" id="dato1">
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
                        <td colspan="10" id="titulo4">EXTRUSION</td>
                        </tr>
                        <tr>
                         <td  colspan="10" id="titulo4"> 
                          Estrusora : 
                          <select name="extrusora_mp" id="extrusora_mp" class="busqueda selectsMedio" required="required" onchange="Extrusora();">
                              <option value="">Extrusoras</option>
                                 <?php  foreach($this->maquinas as $maquinas ) { ?>
                              <option value="<?php echo $maquinas['nombre_maquina']; ?>"<?php if (!(strcmp($row_mezcla['extrusora_mp'] , $maquinas['nombre_maquina']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($maquinas['nombre_maquina']); ?> 
                            </option>
                          <?php } ?>
                          </select>  
                        </td>
                       </tr>
                      <tr id="tr1">
                        <td rowspan="2" id="fuente1">EXT-1          
                        </td>
                        <td colspan="4" id="fuente1">TORNILLO A</td>
                        <td colspan="3" id="fuente1">TORNILLO B</td>
                        <td colspan="2" id="fuente1">TORNILLO C</td>
                        </tr> 
                      <tr id="tr1">
                        <td colspan="2" id="fuente1">Referencia</td>
                        <td colspan="2" id="fuente1">%</td>
                        <td  id="fuente1">Referencia</td>
                        <td id="fuente1">%</td>
                        <td id="fuente1">Referencia</td>
                        <td colspan="2" id="fuente1">%</td>
                      </tr>
                      
                      <tr id="tr1">
                        <td id="fuente1">Tolva A</td>
                        <td colspan="2" id="fuente1">
                          <select name="int_ref1_tol1_pm" id="int_ref1_tol1_pm" style="width:120px">
                          <option value=""<?php if (!(strcmp("", $row_mezcla['int_ref1_tol1_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
                          <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                              <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_mezcla['int_ref1_tol1_pm']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                            </option>
                          <?php } ?> 
                        </select>
                      </td>
                        <td colspan="2" id="fuente1"><input name="int_ref1_tol1_porc1_pm"  type="text" required="required" id="int_ref1_tol1_porc1_pm" placeholder="%" size="3"value="<?php echo $row_mezcla['int_ref1_tol1_porc1_pm']; ?>"/>
                        </td>
                        <td id="fuente1">
                            <select name="int_ref2_tol1_pm" id="int_ref2_tol1_pm" style="width:120px">
                            <option value=""<?php if (!(strcmp("", $row_mezcla['int_ref2_tol1_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
                            <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_mezcla['int_ref2_tol1_pm']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                              </option>
                            <?php } ?> 
                          </select> 
                      </td>
                        <td id="fuente1">
                          <input name="int_ref2_tol1_porc2_pm"  type="text" required="required" id="int_ref2_tol1_porc2_pm" placeholder="%" size="3"value="<?php echo $row_mezcla['int_ref2_tol1_porc2_pm'] ?>"/>
                        </td>
                        <td id="fuente1"> 
                            <select name="int_ref3_tol1_pm" id="int_ref3_tol1_pm" style="width:120px">
                            <option value=""<?php if (!(strcmp("", $row_mezcla['int_ref3_tol1_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
                            <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_mezcla['int_ref3_tol1_pm']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                              </option>
                            <?php } ?> 
                          </select> 
                      </td>
                        <td  colspan="2" id="fuente1">
                          <input name="int_ref3_tol1_porc3_pm"  type="text" required="required" id="int_ref3_tol1_porc3_pm" placeholder="%" size="3"value="<?php echo $row_mezcla['int_ref3_tol1_porc3_pm'] ?>"/>
                        </td>
                      </tr>
                      <tr>
                        <td id="fuente1">Tolva B</td>
                        <td colspan="2"id="fuente1">
                            <select name="int_ref1_tol2_pm" id="int_ref1_tol2_pm" style="width:120px">
                            <option value=""<?php if (!(strcmp("", $row_mezcla['int_ref1_tol2_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
                            <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_mezcla['int_ref1_tol2_pm']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                              </option>
                            <?php } ?> 
                          </select>
                      </td>
                        <td colspan="2"id="fuente1">
                          <input name="int_ref1_tol2_porc1_pm"  type="text" required="required" id="int_ref1_tol2_porc1_pm" placeholder="%" size="3"value="<?php echo $row_mezcla['int_ref1_tol2_porc1_pm'] ?>"/>
                        </td>
                        <td id="fuente1">
                            <select name="int_ref2_tol2_pm" id="int_ref2_tol2_pm" style="width:120px">
                            <option value=""<?php if (!(strcmp("", $row_mezcla['int_ref2_tol2_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
                            <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_mezcla['int_ref2_tol2_pm']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                              </option>
                            <?php } ?> 
                          </select>
                      </td>
                        <td id="fuente1">
                          <input name="int_ref2_tol2_porc2_pm"  type="text" required="required" id="int_ref2_tol2_porc2_pm" placeholder="%" size="3"value="<?php echo $row_mezcla['int_ref2_tol2_porc2_pm'] ?>"/>
                        </td>
                        <td id="fuente1">
                            <select name="int_ref3_tol2_pm" id="int_ref3_tol2_pm" style="width:120px">
                            <option value=""<?php if (!(strcmp("", $row_mezcla['int_ref3_tol2_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
                            <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_mezcla['int_ref3_tol2_pm']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                              </option>
                            <?php } ?> 
                          </select>
                      </td>
                        <td  colspan="2" id="fuente1">
                          <input name="int_ref3_tol2_porc3_pm"  type="text" required="required" id="int_ref3_tol2_porc3_pm" placeholder="%" size="3"value="<?php echo $row_mezcla['int_ref3_tol2_porc3_pm'] ?>"/>
                        </td>
                      </tr>
                      <tr id="tr1">
                        <td id="fuente1">Tolva C</td>
                        <td colspan="2" id="fuente1">
                            <select name="int_ref1_tol3_pm" id="int_ref1_tol3_pm" style="width:120px">
                            <option value=""<?php if (!(strcmp("", $row_mezcla['int_ref1_tol3_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
                            <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_mezcla['int_ref1_tol3_pm']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                              </option>
                            <?php } ?> 
                          </select>
                      </td>
                        <td colspan="2" id="fuente1">
                          <input name="int_ref1_tol3_porc1_pm"  type="text" required="required" id="int_ref1_tol3_porc1_pm" placeholder="%" size="3"value="<?php echo $row_mezcla['int_ref1_tol3_porc1_pm'] ?>"/>
                        </td>
                        <td id="fuente1">
                            <select name="int_ref2_tol3_pm" id="int_ref2_tol3_pm" style="width:120px">
                            <option value=""<?php if (!(strcmp("", $row_mezcla['int_ref2_tol3_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
                            <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_mezcla['int_ref2_tol3_pm']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                              </option>
                            <?php } ?> 
                          </select>
                      </td>
                        <td id="fuente1"><input name="int_ref2_tol3_porc2_pm"  type="text" required="required" id="int_ref2_tol3_porc2_pm" placeholder="%" size="3"value="<?php echo $row_mezcla['int_ref2_tol3_porc2_pm'] ?>"/>
                        </td>
                        <td id="fuente1">
                            <select name="int_ref3_tol3_pm" id="int_ref3_tol3_pm" style="width:120px">
                            <option value=""<?php if (!(strcmp("", $row_mezcla['int_ref3_tol3_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
                            <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_mezcla['int_ref3_tol3_pm']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                              </option>
                            <?php } ?> 
                          </select>
                      </td>
                        <td  colspan="2" id="fuente1">
                          <input name="int_ref3_tol3_porc3_pm"  type="text" required="required" id="int_ref3_tol3_porc3_pm" placeholder="%" size="3"value="<?php echo $row_mezcla['int_ref3_tol3_porc3_pm'] ?>"/>
                        </td>
                      </tr>
                      <tr>
                        <td id="fuente1">Tolva D</td>
                        <td colspan="2" id="fuente1">
                            <select name="int_ref1_tol4_pm" id="int_ref1_tol4_pm" style="width:120px">
                            <option value=""<?php if (!(strcmp("", $row_mezcla['int_ref1_tol4_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
                            <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_mezcla['int_ref1_tol4_pm']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                              </option>
                            <?php } ?> 
                          </select>
                      </td>
                        <td colspan="2" id="fuente1">
                          <input name="int_ref1_tol4_porc1_pm"  type="text" required="required" id="int_ref1_tol4_porc1_pm" placeholder="%" size="3"value="<?php echo $row_mezcla['int_ref1_tol4_porc1_pm'] ?>"/>
                        </td>
                        <td id="fuente1">
                            <select name="int_ref2_tol4_pm" id="int_ref2_tol4_pm" style="width:120px">
                            <option value=""<?php if (!(strcmp("", $row_mezcla['int_ref2_tol4_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
                            <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_mezcla['int_ref2_tol4_pm']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                              </option>
                            <?php } ?> 
                          </select>
                      </td>
                        <td id="fuente1">
                          <input name="int_ref2_tol4_porc2_pm"  type="text" required="required" id="int_ref2_tol4_porc2_pm" placeholder="%" size="3"value="<?php echo $row_mezcla['int_ref2_tol4_porc2_pm'] ?>"/>
                        </td>
                        <td id="fuente1">
                            <select name="int_ref3_tol4_pm" id="int_ref3_tol4_pm" style="width:120px">
                            <option value=""<?php if (!(strcmp("", $row_mezcla['int_ref3_tol4_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
                            <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                                <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_mezcla['int_ref3_tol4_pm']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                              </option>
                            <?php } ?> 
                          </select>
                      </td>
                        <td  colspan="2" id="fuente1">
                          <input name="int_ref3_tol4_porc3_pm"  type="text" required="required" id="int_ref3_tol4_porc3_pm" placeholder="%" size="3"value="<?php echo $row_mezcla['int_ref3_tol4_porc3_pm'] ?>"/></td>
                      </tr>
                      <tr id="tr1">
                        <td id="fuente1">RPM - %</td>
                        <td  colspan="2" id="fuente1"><input name="int_ref1_rpm_pm"  type="text" placeholder="Rpm Torn-A" required="required" size="10"value="<?php echo $row_mezcla['int_ref1_rpm_pm'] ?>"/></td>
                        <td  colspan="2" id="fuente1"><input name="int_ref1_tol5_porc1_pm"  type="text" required="required" id="int_ref1_tol5_porc1_pm" placeholder="%" size="3"value="<?php echo $row_mezcla['int_ref1_tol5_porc1_pm'] ?>"/></td>
                        <td id="fuente1"><input name="int_ref2_rpm_pm"  type="text" placeholder="Rpm Torn-B" required="required" size="10"value="<?php echo $row_mezcla['int_ref2_rpm_pm'] ?>"/></td>
                        <td id="fuente1"><input name="int_ref2_tol5_porc2_pm"  type="text" required="required" id="int_ref2_tol5_porc2_pm" placeholder="%" size="3"value="<?php echo $row_mezcla['int_ref2_tol5_porc2_pm'] ?>"/></td>
                        <td id="fuente1"><input name="int_ref3_rpm_pm"  type="text" placeholder="Rpm Torn-C" required="required" size="10"value="<?php echo $row_mezcla['int_ref3_rpm_pm'] ?>"/></td>
                        <td  colspan="2" id="fuente1"><input name="int_ref3_tol5_porc3_pm"  type="text" required="required" id="int_ref3_tol5_porc3_pm" placeholder="%" size="3"value="<?php echo $row_mezcla['int_ref3_tol5_porc3_pm'] ?>"/></td>
                      </tr>
                      <tr>
                        <td colspan="10" id="fuente1">
                          <textarea name="observ_pm" id="observ_pm" cols="80" rows="2" placeholder="OBSERVACIONES"><?php echo $row_mezcla['observ_pm'] ?></textarea>
                        </td>
                      </tr>
                <!-- FIN MEZCLAS -->

    
                <!-- INICIA CARACTERISTICAS -->
                 <tr>
                  <td  colspan="10" id="titulo4"> 
                   <input name="extrusora"  type="hidden" id="extrusora" placeholder="Extrusora" size="20" value="<?php echo $row_mezcla['extrusora_mp']; ?>" readonly="readonly"/>  
                 </td>
                </tr>
                <tr id="tr1">
                  <td colspan="4" id="fuente1">Opcion No 1</td>
                  <td colspan="2" id="fuente2">Calibre</td>
                  <td colspan="4" id="fuente2">Ancho material</td>
                  </tr>
                <tr>
                  <td colspan="3" id="fuente1">
                  Boquilla de Extrusion</td>
                  <td colspan="2" id="fuente1"> 
                    <input name="campo_1" id="campo_1" type="text" placeholder="Boquilla" size="10" required="required" onblur="ce_micras(this)" value="<?php echo $row_caract['campo_1']; ?>"/></td>
                  <td id="fuente1">Calibre</td>
                  <td id="fuente1">Micras</td>
                  <td colspan="3" id="fuente1">&nbsp;Ancho</td>
                  </tr>
                <tr id="tr1">
                  <td colspan="3" id="fuente1">Relacion Soplado (RS)</td>
                  <td colspan="2" id="fuente1">  
                    <input name="campo_2"  type="text"  id="campo_2" placeholder="Relacion Soplado" size="10"required="required" value="<?php echo $row_caract['campo_2']; ?>"/></td>
                  <td id="fuente1">
                    <input name="campo_3"  type="text"  id="campo_3" placeholder="Milesimas" size="10"  value="<?php echo $row_referencia['calibre_ref']=='' ? $row_caract['campo_2']: $row_referencia['calibre_ref']; ?>" onblur="ce_micras(this)" readonly="readonly"/>
                    <input type="hidden" name="campo_4" id="campo_4" value="<?php echo $row_referencia['calibre_ref']=='' ? $row_caract['campo_4']: $row_referencia['calibre_ref']; ?>" /></td>
                  <td id="fuente1">
                    <input name="campo_5"  type="hidden"  id="campo_5"  size="10" value="<?php echo $row_caract['campo_5'];?>"/>
                    <label for="micrass"></label>
                    <input type="text" name="campo_6" id="campo_6" placeholder="Micras" size="10" readonly="readonly" value="<?php echo $row_caract['campo_6'];?>"/></td>
                  <td colspan="3" id="fuente1">  
                    <input name="campo_7"  type="text"  id="campo_7" placeholder="Micras" size="10" value="<?php echo $row_caract['campo_7']=='' ? $row_referencia['ancho_ref']: $row_caract['campo_7']; ?>"/>
                  </td>
                  </tr>
                <tr>
                  <td colspan="3" rowspan="2" id="fuente1">Altura Linea Enfriamiento</td>
                  <td colspan="2" rowspan="2" id="fuente1"> 
                    <input name="campo_8"  type="text"  id="campo_8" placeholder="Altura Linea" size="10" required="required" value="<?php echo $row_caract['campo_8'];?>"/></td>
                  <td id="fuente1">Presentacion</td>
                  <td id="fuente1">&nbsp;</td>
                  <td colspan="3" id="fuente1">Peso Millar</td>
                  </tr>
                <tr>
                  <td id="fuente1">
                    <input name="campo_9" type="text" id="campo_9" value="<?php echo $row_caract['campo_9']=='' ? $row_referencia['Str_presentacion'] : $row_caract['campo_9']; ?>" size="10" readonly="readonly" /></td>
                  <td id="fuente1">&nbsp;</td>
                  <td colspan="3" id="fuente1">
                    <input name="campo_10" type="text" id="campo_10" value="<?php echo $row_caract['campo_10']=='' ? $row_referencia['peso_millar_ref'] : $row_caract['campo_10']; ?>" size="14" readonly="readonly" /></td>
                  </tr>
                <tr id="tr1">
                  <td rowspan="2" id="fuente1">Velocidad de Halado</td>
                  <td colspan="2" id="fuente1">Tratamiento Corona</td>
                  <td colspan="5" id="fuente2">Ubicaci&oacute;n Tratamiento</td>
                  <td colspan="2" id="fuente1">Pigmentaci&oacute;n</td>
                </tr>
                <tr>
                  <td id="fuente1">Potencia</td>
                  <td id="fuente1">
                    <input name="campo_11"  type="text"  id="campo_11" placeholder="Potencia" size="5" value="<?php echo $row_caract['campo_11'];?>"/>
                   </td>
                  <td id="fuente1">Cara Interior</td>
                  <td colspan="4" id="fuente1"><input name="campo_12"  type="text"  id="campo_12" size="16" value="<?php if ($row_referencia['Str_tratamiento']=="CARA INTERNA"){echo "CARA INTERNA";}else{echo $row_caract['campo_12'];}?>" onblur="conMayusculas(this)"  />
                    </td>
                  <td id="fuente1">Interior
                   </td>
                  <td id="fuente1"><input name="campo_13"  type="text"  id="campo_13" placeholder="Pig Interior"onblur="conMayusculas(this)"value="<?php echo $row_referencia['pigm_int_epg']=='' ? $row_caract['campo_13'] : $row_referencia['pigm_int_epg']; ?>" size="10"/></td>
                </tr>
                <tr>
                  <td id="fuente1"> <input name="campo_14"  type="text"  id="campo_14" placeholder="Velocidad Helado" size="10" value="<?php echo $row_caract['campo_14'];?>"/></td>
                  <td id="fuente1">Dinas</td>
                  <td id="fuente1">
                    <input name="campo_15"  type="text"  id="campo_15" placeholder="Dinas" size="5" value="<?php echo $row_caract['campo_15'];?>"/>
                  </td>
                  <td id="fuente1">Cara Exterior</td>
                  <td colspan="4" id="fuente1">
                    <input name="campo_16"  type="text"  id="campo_16" size="16" value="<?php if ($row_referencia['Str_tratamiento']=="CARA EXTERNA"){echo "CARA EXTERNA";}else{echo $row_caract['campo_16'];}?>"onblur="conMayusculas(this)"/> 
                   </td>
                  <td id="fuente1">Exterior
                    </td>
                  <td id="fuente1">
                    <input name="campo_17"  type="text"  id="campo_17" placeholder="Pig Exterior"onblur="conMayusculas(this)"value="<?php echo $row_referencia['pigm_ext_egp']=='' ? $row_caract['campo_15'] : $row_referencia['pigm_ext_egp']; ?>" size="10"/>
                  </td>
                </tr>
                <tr id="tr1">
                  <td rowspan="2" id="fuente1">% Aire Anillo Enfriamiento</td>
                  <td colspan="9" id="fuente2">Tension</td>
                </tr>

                
                   <tr id="tr1" class="zonaextruder1" style="display: none;">
                     <td id="fuente1">Sec Take Off</td>
                     <td id="fuente1">Winder A</td>
                     <td id="fuente1">Winder B</td>
                     <td colspan="6" id="fuente1" nowrap="nowrap">&nbsp;</td>
                   </tr>
                   <tr id="tr1" class="zonaextruder2" style="display: none;">
                     <td id="fuente1">Calandia</td>
                     <td id="fuente1">Colapsador</td>
                     <td id="fuente1">Embobinador Ext.</td>
                     <td colspan="6" id="fuente1" nowrap="nowrap">Embobinador Int.</td>
                   </tr>
                   <tr>
                     <td id="fuente1">
                      <input name="campo_18"  type="text"  id="campo_18" placeholder="Aire Anillo" size="10" value="<?php echo $row_caract['campo_18'];?>"/>
                     </td>
                     <td id="fuente1"  class="zonaextruder1" style="display: none;">
                       <input name="campo_19"  type="text"  id="campo_19" placeholder="Sec Take" size="15" value="<?php echo $row_caract['campo_19'];?>"/>
                     </td>
                     <td id="fuente1"  class="zonaextruder1" style="display: none;">
                       <input name="campo_20"  type="text"  id="campo_20" placeholder="Winder A" size="15" value="<?php echo $row_caract['campo_20'];?>"/>
                     </td>
                     <td id="fuente1"  class="zonaextruder1" style="display: none;">
                       <input name="campo_21"  type="text"  id="campo_21" placeholder="Winder B" size="15" value="<?php echo $row_caract['campo_21'];?>"/>
                      </td>
                      <td id="fuente1" class="zonaextruder1" style="display: none;" nowrap="nowrap" >&nbsp;</td>
                 
                     <td id="fuente1" class="zonaextruder2" style="display: none;">
                        <input name="campo_54"  type="text"  id="campo_54" placeholder="Calandia" size="15" value="<?php echo $row_caract['campo_54'];?>"/>
                     </td>
                     <td id="fuente1" class="zonaextruder2" style="display: none;">
                       <input name="campo_55"  type="text"  id="campo_55" placeholder="Colapsador" size="15" value="<?php echo $row_caract['campo_55'];?>"/>
                     </td>
                     <td id="fuente1" class="zonaextruder2" style="display: none;">
                       <input name="campo_56"  type="text"  id="campo_56" placeholder="Embobinador Ext." size="15" value="<?php echo $row_caract['campo_56'];?>"/>
                     </td>
                     <td id="fuente1" class="zonaextruder2" style="display: none;" nowrap="nowrap">
                       <input name="campo_57"  type="text"  id="campo_57" placeholder="Embobinador Int." size="15" value="<?php echo $row_caract['campo_57'];?>"/>
                     </td> 

                     <td colspan="6" id="fuente1">Nota: Favor entregar al proceso siguiente el material debidamente identificado seg&uacute;n el documento correspondiente para cada rollo de material.</td>
                   </tr>
     
                <tr>
                  <td colspan="10" id="fuente1">&nbsp;</td>
                </tr>
                <tr id="tr1">
                  <td colspan="10" id="titulo4">TEMPERATURAS DE FUNDIDO EN TORNILLOS Y CABEZAL</td>
                </tr>
                <tr id="tr1">
                  <td colspan="2"id="fuente1">&nbsp;</td>
                  <td colspan="2"id="fuente1">TORNILLO A</td>
                  <td colspan="2"id="fuente1">TORNILLO B</td>
                  <td id="fuente1">TORNILLO C</td>
                  <td id="fuente1" class="zonaimpr2" style="display: none;" >ZONA</td>
                  <td colspan="1" id="fuente1">Cabezal (Die Head)</td>
                  <td colspan="2" id="fuente1">&deg;C</td>
                </tr>
                <tr>
                  <td colspan="2"id="fuente1"><span class="zona1">Barrel Zone 1</span></td>
                  <td colspan="2"id="fuente1"><input name="campo_22"  type="text"  id="campo_22" placeholder="Tor A" size="10"value="<?php echo $row_caract['campo_22'];?>"/> </td>
                  <td colspan="2"id="fuente1"><input name="campo_23"  type="text"  id="campo_23" placeholder="Tor B" size="10"value="<?php echo $row_caract['campo_23'];?>"/>
                   </td>
                  <td id="fuente1"><input name="campo_24"  type="text"  id="campo_24" placeholder="Tor C" size="10"value="<?php echo $row_caract['campo_24'];?>"/> 
                   </td>
                  <td id="fuente1" class="zonaimpr2" style="display: none;" >D</td>
                  <td colspan="1" id="fuente1"><span class="bloquef">Share Lower</span> </td>
                  <td colspan="2" id="fuente1"><input name="campo_25"  type="text"  id="campo_25" placeholder="Share Lower" size="5"value="<?php echo $row_caract['campo_25'];?>"/>
                  </td>
                </tr>
                <tr id="tr1">
                  <td colspan="2"id="fuente1"><span class="zona2">Barrel Zone 2</span></td>
                  <td colspan="2"id="fuente1"><input name="campo_26"  type="text"  id="campo_26" placeholder="Tor A" size="10"value="<?php echo $row_caract['campo_26'];?>"/>
                  </td>
                  <td colspan="2"id="fuente1"><input name="campo_27"  type="text"  id="campo_27" placeholder="Tor B" size="10"value="<?php echo $row_caract['campo_27'];?>"/>
                  </td>
                  <td id="fuente1"><input name="campo_28"  type="text"  id="campo_28" placeholder="Tor C" size="10"value="<?php echo $row_caract['campo_28'];?>"/>
                    </td>
                    <td id="fuente1" class="zonaimpr2" style="display: none;" >H1</td>
                  <td colspan="1" id="fuente1"><span class="cabezal1">Share Upper</span></td>
                  <td colspan="2" id="fuente1"><input name="campo_29"  type="text"  id="campo_29" placeholder="Share Upper" size="5"value="<?php echo $row_caract['campo_29'];?>"/>
                   </td>
                </tr>
                <tr>
                  <td colspan="2" id="fuente1"><span class="zona3">Barrel Zone 3</span></td>
                  <td colspan="2"id="fuente1"><input name="campo_30"  type="text"  id="campo_30" placeholder="Tor A" size="10"value="<?php echo $row_caract['campo_30'];?>"/>
                    </td>
                  <td colspan="2"id="fuente1"><input name="campo_31"  type="text"  id="campo_31" placeholder="Tor B" size="10"value="<?php echo $row_caract['campo_31'];?>"/>
                   </td>
                  <td id="fuente1"><input name="campo_32"  type="text"  id="campo_32" placeholder="Tor C" size="10"value="<?php echo $row_caract['campo_32'];?>"/>
                  </td>
                  <td id="fuente1" class="zonaimpr2" style="display: none;" >H2</td>
                  <td colspan="1" id="fuente1"><span class="cabezal1">L-Die</span></td>
                  <td colspan="2" id="fuente1"><input name="campo_33"  type="text"  id="campo_33" placeholder="L-Die" size="5"value="<?php echo $row_caract['campo_33'];?>"/>
                   </td>
                </tr>
                <tr id="tr1">
                  <td colspan="2"id="fuente1"><span class="zona4">Barrel Zone 4</span></td>
                  <td colspan="2"id="fuente1"><input name="campo_34"  type="text"  id="campo_34" placeholder="Tor A" size="10"value="<?php echo $row_caract['campo_34'];?>"/>
                   </td>
                  <td colspan="2"id="fuente1"><input name="campo_35"  type="text"  id="campo_35" placeholder="Tor B" size="10"value="<?php echo $row_caract['campo_35'];?>"/>
                    </td>
                  <td id="fuente1"><input name="campo_36"  type="text"  id="campo_36" placeholder="Tor C" size="10"value="<?php echo $row_caract['campo_36'];?>"/>
                    </td>
                    <td id="fuente1" class="zonaimpr2" style="display: none;" >H3</td>
                  <td colspan="1" id="fuente1"><span class="labios">V- Die</span></td>
                  <td colspan="2" id="fuente1"><input name="campo_37"  type="text"  id="campo_37" placeholder="V- Die" size="5"value="<?php echo $row_caract['campo_37'];?>"/>
                   </td>
                </tr>
           
                <tr class="zonaextruder1" style="display: none;">
                  <td colspan="2"id="fuente1">Filter Front</td>
                  <td colspan="2"id="fuente1"><input name="campo_38"  type="text"  id="campo_38" placeholder="Tor A" size="10"value="<?php echo $row_caract['campo_38'];?>"/>
                   </td>
                  <td colspan="2"id="fuente1"><input name="campo_39"  type="text"  id="campo_39" placeholder="Tor B" size="10"value="<?php echo $row_caract['campo_39'];?>"/>
                    </td>
                  <td id="fuente1"><input name="campo_40"  type="text"  id="campo_40" placeholder="Tor C" size="10"value="<?php echo $row_caract['campo_40'];?>"/>
                    </td>
                  <td colspan="1" id="fuente1">Die Head</td>
                  <td colspan="2" id="fuente1"><input name="campo_41"  type="text"  id="campo_41" placeholder="Die Head" size="5"value="<?php echo $row_caract['campo_41'];?>"/>
                    </td>
                </tr>
                <tr id="tr1" class="zonaextruder1" style="display: none;">
                  <td colspan="2"id="fuente1">Filter Back</td>
                  <td colspan="2"id="fuente1"><input name="campo_42"  type="text"  id="campo_42" placeholder="Tor A" size="10"value="<?php echo $row_caract['campo_42'];?>"/>
                    </td>
                  <td colspan="2"id="fuente1"><input name="campo_43"  type="text"  id="campo_43" placeholder="Tor B" size="10"value="<?php echo $row_caract['campo_43'];?>"/>
                    </td>
                  <td id="fuente1"><input name="campo_44"  type="text"  id="campo_44" placeholder="Tor C" size="10"value="<?php echo $row_caract['campo_44'];?>"/>
                    </td>
                  <td colspan="1" id="fuente1">Die Lid</td>
                  <td colspan="2" id="fuente1"><input name="campo_45"  type="text"  id="campo_45" placeholder="Die Lid" size="5"value="<?php echo $row_caract['campo_45'];?>"/>
                    </td>
                </tr>
                <tr class="zonaextruder1" style="display: none;">
                  <td colspan="2"id="fuente1">Sec- Barrel</td>
                  <td colspan="2"id="fuente1"><input name="campo_46"  type="text"  id="campo_46" placeholder="Tor A" size="10"value="<?php echo $row_caract['campo_46'];?>"/>
                     </td>
                  <td colspan="2"id="fuente1"><input name="campo_47"  type="text"  id="campo_47" placeholder="Tor B" size="10"value="<?php echo $row_caract['campo_47'];?>"/>
                    </td>
                  <td id="fuente1"><input name="campo_48"  type="text"  id="campo_48" placeholder="Tor C" size="10"value="<?php echo $row_caract['campo_48'];?>"/>
                    </td>
                  <td colspan="1" id="fuente1">Die Center Lower</td>
                  <td colspan="2" id="fuente1">
                    <input name="campo_49"  type="text"  id="campo_49" placeholder="Die Center Lower" size="5"value="<?php echo $row_caract['campo_49'];?>"/>
                    </td>
                </tr>
                <tr id="tr1" class="zonaextruder1" style="display: none;">
                  <td colspan="2"id="fuente1">Melt Temp &deg;C</td>
                  <td colspan="2"id="fuente1"><input name="campo_50"  type="text"  id="campo_50" placeholder="Tor A" size="10"value="<?php echo $row_caract['campo_50'];?>"/>
                    </td>
                  <td colspan="2"id="fuente1"><input name="campo_51"  type="text"  id="campo_51" placeholder="Tor B" size="10"value="<?php echo $row_caract['campo_51'];?>"/>
                    </td>
                  <td id="fuente1"><input name="campo_52"  type="text"  id="campo_52" placeholder="Tor C" size="10"value="<?php echo $row_caract['campo_52'];?>"/>
                    </td>
                  <td colspan="1" id="fuente1">Die Center Upper</td>
                  <td colspan="2" id="fuente1">
                    <input name="campo_53"  type="text"  id="campo_53" placeholder="Die Center Upper" size="5"value="<?php echo $row_caract['campo_53'];?>"/>
                 </td>
                </tr>
             

                <!-- EXTRUSORA 2 -->
              
                    <tr class="zonaextruder2" style="display: none;" >
                      <td colspan="2"id="fuente1">Zona 5</td>
                      <td colspan="2"id="fuente1"><input name="campo_58"  type="text"  id="campo_58" placeholder="Tor A" size="10"value="<?php echo $row_caract['campo_58'];?>"/>
                        </td>
                      <td colspan="2"id="fuente1"><input name="campo_59"  type="text"  id="campo_59" placeholder="Tor B" size="10"value="<?php echo $row_caract['campo_59'];?>"/>
                        </td>
                      <td id="fuente1"><input name="campo_60"  type="text"  id="campo_60" placeholder="Tor C" size="10"value="<?php echo $row_caract['campo_60'];?>"/>
                        </td>
                        <td colspan="4">&nbsp;</td>
                    </tr>
                    <tr id="tr1" class="zonaextruder2" style="display: none;" >
                      <td colspan="2"id="fuente1">Zona 6</td>
                      <td colspan="2"id="fuente1"><input name="campo_61"  type="text"  id="campo_61" placeholder="Tor A" size="10"value="<?php echo $row_caract['campo_61'];?>"/>
                        </td>
                      <td colspan="2"id="fuente1"><input name="campo_62"  type="text"  id="campo_62" placeholder="Tor B" size="10"value="<?php echo $row_caract['campo_62'];?>"/>
                        </td>
                      <td id="fuente1"><input name="campo_63"  type="text"  id="campo_63" placeholder="Tor C" size="10"value="<?php echo $row_caract['campo_63'];?>"/>
                        </td>
                        <td colspan="4">&nbsp;</td>
                    </tr>
                  
                 
                <tr>
                  <td colspan="10"id="fuente1">Estos son valores de referencia que pueden cambiar de acuerdo    a velocidad, temperatura ambiente, calibre, etc.</td>
                </tr> 
                 
                <tr id="tr1">
                  <td colspan="10" id="fuente2">
                    <div class="panel-footer" >
                      <input type="hidden" name="fecha_registro_pm" id="fecha_registro_pm" value="<?php echo date('Y-m-d') ?>"/> 
                      <input type="hidden" name="id_ref_pm" id="id_ref_pm" value="<?php echo $row_referencia['id_ref']; ?>"/>
                      <input type="hidden" name="int_cod_ref_pm" id="int_cod_ref_pm" value="<?php echo $row_referencia['cod_ref']; ?>"/>
                      <input type="hidden" name="version_ref_pm" id="version_ref_pm" value="<?php echo $row_referencia['version_ref']; ?>"/>
                      <input type="hidden" name="str_registro_pm" id="str_registro_pm" value="<?php echo $_SESSION['Usuario'] ?>" />
                      <input type="hidden" name="id_proceso" id="id_proceso" value="1"/>
                      <input type="hidden" name="b_borrado_pm" id="b_borrado_pm" value="0"/>

                      <input type="hidden" name="cod_ref" id="cod_ref" value="<?php echo $row_referencia['cod_ref']; ?>"/> 
                      <input type="hidden" name="proceso" id="proceso" value="1"/>
                      <input type="hidden" name="modifico" id="modifico" value="<?php echo $_SESSION['Usuario'] ?>"/>
                      <input type="hidden" name="fecha_modif" id="fecha_modif" value="<?php echo date('Y-m-d H:i:s') ?>"/> 
                      <input class="botonGeneral" type="submit" name="GUARDAR" id="GUARDAR" value="GUARDAR" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <a class="botonFinalizar" style="text-decoration:none; "href="javascript:Salir('view_index.php?c=cmezclas&a=Salir')" >SALIR</a>  
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
    window.location="view_index.php?c=cmezclas&a=Carat&cod_ref="+cod_ref+"&cod_refcopia="+refcopia;

  }
  
  function Extrusora(){
  $( "#extrusora" ).val($( "#extrusora_mp" ).val());
  }



  function extrusoraNumero(){

    if($( "#extrusora_mp" ).val() == "1 Maquina Extrusora") { 
       $('.zonaextruder1').show();
       $('.zonaextruder2').hide();
       $('.zonaimpr2').hide(); 

    }else if($( "#extrusora_mp" ).val() == "2 Maquina Extrusora"){  
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
  }

</script>

<?php
mysql_free_result($usuario);
mysql_free_result($referencia);
mysql_free_result($referencia_copia); 
mysql_free_result($mezcla);
mysql_free_result($caract); 
mysql_free_result($ultimo);
?>
