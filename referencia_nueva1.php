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
$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);
//BOLSAS
mysql_select_db($database_conexion1, $conexion1);
$query_referencianueva = "SELECT * FROM Tbl_cotiza_bolsa WHERE Tbl_cotiza_bolsa.B_estado = '1' AND (Tbl_cotiza_bolsa.B_generica = '0' OR Tbl_cotiza_bolsa.B_generica = '2') AND Tbl_cotiza_bolsa.N_referencia_c 
NOT IN(SELECT Tbl_referencia.cod_ref FROM  Tbl_referencia) ORDER BY Tbl_cotiza_bolsa.N_referencia_c  DESC";
$referencianueva = mysql_query($query_referencianueva, $conexion1) or die(mysql_error());
$row_referencianueva = mysql_fetch_assoc($referencianueva);
$totalRows_referencianueva = mysql_num_rows($referencianueva);
//PACKING
mysql_select_db($database_conexion1, $conexion1);
$query_referencianueva2 = "SELECT * FROM Tbl_cotiza_packing WHERE Tbl_cotiza_packing.B_estado = '1' AND Tbl_cotiza_packing.B_generica = '0' AND  Tbl_cotiza_packing.N_referencia_c 
NOT IN(SELECT Tbl_referencia.cod_ref FROM  Tbl_referencia)";
$referencianueva2 = mysql_query($query_referencianueva2, $conexion1) or die(mysql_error());
$row_referencianueva2 = mysql_fetch_assoc($referencianueva2);
$totalRows_referencianueva2 = mysql_num_rows($referencianueva2);
//LAMINAS
mysql_select_db($database_conexion1, $conexion1);
$query_referencianueva3 = "SELECT * FROM Tbl_cotiza_laminas WHERE Tbl_cotiza_laminas.B_estado = '1' AND Tbl_cotiza_laminas.B_generica = '0' AND  Tbl_cotiza_laminas.N_referencia_c 
NOT IN(SELECT Tbl_referencia.cod_ref FROM  Tbl_referencia)";
$referencianueva3 = mysql_query($query_referencianueva3, $conexion1) or die(mysql_error());
$row_referencianueva3 = mysql_fetch_assoc($referencianueva3);
$totalRows_referencianueva3 = mysql_num_rows($referencianueva3);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title>SISADGE AC &amp; CIA</title>
  <link rel="StyleSheet" href="css/formato.css" type="text/css">
  <script type="text/javascript" src="js/listado.js"></script>
  <link rel="stylesheet" type="text/css" href="css/general.css"/>
  <link rel="stylesheet" type="text/css" href="css/desplegable.css" />
</head>
<body>
  <div class="spiffy_content">  
    <div align="center">
      <table id="tabla1"><!-- id="tabla1" -->
        <tr>
         <td align="center">
           <div class="row-fluid">
             <div class="span8 offset2"> <!--span8 offset2   esto da el tamaño pequeño -->
               <div class="panel panel-primary">
                <div class="panel-heading" align="left" ></div><!--color azul-->
                <div class="row" >
                  <div class="span12">&nbsp;&nbsp;&nbsp; <img src="images/cabecera.jpg"></div> 
                </div>
                <div class="panel-heading" align="left" ></div><!--color azul-->
                <div id="cabezamenu">
                  <ul id="menuhorizontal">
                    <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                    <li><a href="disenoydesarrollo.php" target="_top">DISENO-DESARROLLO</a></li>
                    <li><a href="referencia_busqueda.php" target="_top">FILTRO</a></li>
                    <li><?php echo $_SESSION['Usuario']; ?></li>
                  </ul>
                </div> 
                <div class="panel-body">
                 <br> 
                 <div class="container">
                  <div class="row">
                    <div class="span12"> 
                    </div>
                  </div>


                  <table id="tabla1" align="center">
                    <tr>    
                      <td colspan="9" nowrap id="titulo3">REFERENCIAS NUEVAS ACEPTADAS</td>    
                      <td nowrap id="titulo3"><a href="referencias.php"><img src="images/a.gif" alt="REF'S ACTIVAS" border="0" style="cursor:hand;"/></a><a href="referencias_inactivas.php"><img src="images/i.gif" alt="REF'S INACTIVAS" border="0" style="cursor:hand;"/></a></td>
                    </tr>
                    <tr>
                      <td colspan="10" id="numero2">Por favor!!! verifique las condiciones  de cada Referencia antes de Adicionarlas.</td>
                    </tr>
                    <!--PARA BOLSAS-->
                    <?php if($row_referencianueva['N_referencia_c']!=''){ ?>
                      <tr id="tr2">
                        <td id="detalle2">REFERENCIA</td>
                        <!--<td id="detalle2">EGP</td>-->
                        <td id="detalle2">COTIZACION</td>
                        <td nowrap="nowrap" id="detalle2">TIPO DE BOLSA </td>
                        <td id="detalle2"><?php if ($row_referencianueva['N_solapa'] > 0){?>SOLAPA<?php }?></td>
                        <td id="detalle2">MATERIAL</td>
                        <td nowrap="nowrap" id="detalle2">PRESENTACION</td>
                        <td nowrap="nowrap" id="detalle2">TRATAMIENTO</td>
                        <td id="detalle2">ADICIONAR</td>
                        <td id="detalle2">RECHAZAR</td>
                        <td id="detalle2">ESTADO COTIZ</td>
                      </tr> 
                      <?php do if($row_referencianueva['N_referencia_c']!=''){ ?>

                        <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#ffffffff');" bgcolor#ffffffffFF">
                          <td id="dato2"><?php echo $row_referencianueva['N_referencia_c']; ?></td>
                          <!--<td id="dato3"><?php echo $row_referencianueva['N_referencia_c']; ?></td>-->
                          <td id="dato2"><?php echo $row_referencianueva['N_cotizacion']; ?></td>
                          <form name="form1" method="post" action="referencia_nueva2.php" enctype="multipart/form-data" onsubmit="MM_validateForm('presen','','R','trata','','R');return document.MM_returnValue">
                           <td id="dato2"><?php /*if($row_referencianueva['B_sellado_seguridad']==1){echo "SEGURIDAD";}else if($row_referencianueva['B_sellado_permanente']==1){echo "CURRIER";}else if($row_referencianueva['B_sellado_resellable']==1){echo "CURRIER";}else if($row_referencianueva['B_sellado_hotm']==1){echo "CURRIER";}else if($row_referencianueva['tipo_bolsa']=="COMPOSTABLE"){echo "COMPOSTABLE";}else{echo $tippobolsa;} */

                             $tippobolsa=$row_referencianueva['tipo_bolsa'];

                           ?>

                           <select name="tipo_bolsa" id="tipo_bolsa" style="width:100px" required >
                             <option value=""></option> 
                             <option value="SEGURIDAD"<?php if ($row_referencianueva['B_sellado_seguridad']==1) {echo "selected=\"selected\"";} ?>>SEGURIDAD</option>
                             <option value="CURRIER"<?php if ($row_referencianueva['B_sellado_permanente']==1 || $row_referencianueva['B_sellado_resellable']==1||$row_referencianueva['B_sellado_hotm']==1) {echo "selected=\"selected\"";} ?>>CURRIER</option>
                             <option value="BOLSA PLASTICA" <?php if(!(strcmp("BOLSA PLASTICA", $tippobolsa))) {echo "selected=\"selected\"";} ?>>BOLSA PLASTICA</option>
                             <option value="BOLSA MONEDA" <?php if (!(strcmp("BOLSA MONEDA", $tippobolsa))) {echo "selected=\"selected\"";} ?>>BOLSA MONEDA</option>
                             <option value="COMPOSTABLE" <?php if (!(strcmp("COMPOSTABLE", $tippobolsa))) {echo "selected=\"selected\"";} ?>>COMPOSTABLE</option>
                             <option value="BOLSA TROQUELADA" <?php if (!(strcmp("BOLSA TROQUELADA", $tippobolsa))) {echo "selected=\"selected\"";} ?>>BOLSA TROQUELADA</option>
                           </select>
 
               </td> 
        <td nowrap="nowrap" id="dato1"> 
      <?php if ($row_referencianueva['N_solapa'] > 0){?>
          <input type="radio" name="Tiposolapa" id="ocultar" value="0" required/>N/A<br/> 
          <input type="radio" name="Tiposolapa" id="mostrar" value="2"/>Sencilla<br/>
          <input type="radio" name="Tiposolapa" id="mostrar" value="1"/>Doble

        <?php }?>
          </td>
          <td id="dato2"><?php echo $row_referencianueva['Str_tipo_coextrusion']; ?></td>
          <td colspan="3" id="dato1">

            <select name="presen" id="presen" style="width:95px">
              <option value=""></option>
              <option value="N.A">N.A</option>
              <option value="LAMINA">LAMINA</option>
              <option value="TUBULAR">TUBULAR</option>
              <option value="SEMITUBULAR">SEMITUBULAR</option>
            </select>
            <select name="trata" id="trata" style="width:85px">
              <option value=""></option>
              <option value="N.A"<?php if (!(strcmp('N.A', $row_referencia_editar['Str_tratamiento']))) {echo "selected=\"selected\"";} ?>>N.A</option>
              <option value="UNA CARA"<?php if (!(strcmp('UNA CARA', $row_referencia_editar['Str_tratamiento']))) {echo "selected=\"selected\"";} ?>>UNA CARA</option>
              <option value="DOBLE CARA"<?php if (!(strcmp('DOBLE CARA', $row_referencia_editar['Str_tratamiento']))) {echo "selected=\"selected\"";} ?>>DOBLE CARA</option>
            </select>
            <input name="Submit" type="submit" id="Submit" class="botonGMini" value="ADD" />
            <input name="n_cn" type="hidden" id="n_cn" value="<?php echo $row_referencianueva['N_cotizacion']; ?>" />
            <input name="cod_ref" type="hidden" id="cod_ref" value="<?php echo $row_referencianueva['N_referencia_c']; ?>" />
            <input name="Str_nit" type="hidden" id="Str_nit" value="<?php echo $row_referencianueva['Str_nit']; ?>" />
            <input name="valor_ref" type="hidden" id="valor_ref" value="<?php echo $row_referencianueva['N_precio']; ?>" />
            <input name="id" type="hidden" id="id" value="1" />
            <input name="Str_unidad_vta" type="hidden" id="Str_unidad_vta" value="<?php echo $row_referencianueva['Str_unidad_vta']; ?>" />
            <input name="id" type="hidden" id="id" value="1" />
          </td>
        </form> 
        <td id="dato2"><form name="form2" method="post" action="referencia_nueva2.php" enctype="multipart/form-data">	  
          <input type="submit" class="botonDel" name="Submit" value="DEL">
          <input name="n_cn" type="hidden" id="n_cn" value="<?php echo $row_referencianueva['N_cotizacion']; ?>">
          <input name="cod_ref" type="hidden" id="cod_ref" value="<?php echo $row_referencianueva['N_referencia_c']; ?>">
          <input name="Str_nit" type="hidden" id="Str_nit" value="<?php echo $row_referencianueva['Str_nit']; ?>">
          <input name="valor_impuesto" type="hidden" id="valor_impuesto" value="<?php echo $row_referencianueva['valor_impuesto']; ?>">

          <input name="id" type="hidden" id="id" value="2" />
        </form>
      </td>
      <td id="dato2"> 
              <?php
                switch ($row_referencianueva['B_estado']) {
                  case '0':
                     echo 'Pendiente';
                    break;
                  case '1':
                     echo 'Aceptada';
                    break;
                  case '2':
                     echo 'Rechazada';
                    break;
                  case '3':
                     echo 'Obsoleta';
                    break;
                  
                  default:
                     echo '';
                    break;
                }

               ?>
      </td>

      </tr>
    <?php } while ($row_referencianueva = mysql_fetch_assoc($referencianueva)); ?>
  <?php } ?>
  <!--PARA PAKING LIST-->
  <?php if($row_referencianueva2['N_referencia_c']!=''){ ?>
   <tr id="tr2">
    <td id="detalle2">REFERENCIA</td>
    <!--<td id="detalle2">EGP</td>-->
    <td id="detalle2">COTIZACION</td>
    <td nowrap="nowrap" id="detalle2">PACKING LIST </td>
    <td id="detalle2">&nbsp;</td>
    <td id="detalle2">BOCA-ENTRADA / UBI.ENTRADA</td>
    <td nowrap="nowrap" id="detalle2">PRESENTACION</td>
    <td nowrap="nowrap" id="detalle2">TRATAMIENTO</td>   
    <td id="detalle2">ADICIONAR</td>
    <td id="detalle2">RECHAZAR</td>
    <td id="detalle2">ESTADO COTIZ</td>
  </tr> 
  <?php  do if($row_referencianueva2['N_referencia_c']!=''){ ?>

    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#ffffffff');" bgcolor#ffffffFFFF">
      <td id="dato2"><?php echo $row_referencianueva2['N_referencia_c']; ?></td>
      <!--<td id="dato3"><?php echo $row_referencianueva2['N_referencia_c']; ?></td>-->
      <td id="dato2"><?php echo $row_referencianueva2['N_cotizacion']; ?></td>
      <td id="dato2"><?php if($row_referencianueva2['N_referencia_c']!='') echo "PACKING LIST"; ?>

      <input name="tipo_bolsa" type="hidden" id="tipo_bolsa" value="<?php echo "PACKING LIST"; ?>" />

    </td>
    <td id="dato2">&nbsp;</td>
    <td id="dato2"><?php echo $row_referencianueva2['Str_boca_entrada']." / ". $row_referencianueva2['Str_ubica_entrada']; ?></td>
    <td colspan="3" id="dato1">
      <form name="form3" method="post" action="referencia_nueva2.php" enctype="multipart/form-data">
      <select name="presen2" id="presen2" style="width:95px">
        <option value=""></option>
        <option value="N.A">N.A</option>
        <option value="LAMINA">LAMINA</option>
        <option value="TUBULAR">TUBULAR</option>
        <option value="SEMITUBULAR">SEMITUBULAR</option>
      </select>        <select name="trata2" id="trata2" style="width:85px">
        <option value=""></option>
        <option value="N.A"<?php if (!(strcmp('N.A', $row_referencia_editar['Str_tratamiento']))) {echo "selected=\"selected\"";} ?>>N.A</option> 
              <option value="UNA CARA"<?php if (!(strcmp('UNA CARA', $row_referencia_editar['Str_tratamiento']))) {echo "selected=\"selected\"";} ?>>UNA CARA</option>
              <option value="DOBLE CARA"<?php if (!(strcmp('DOBLE CARA', $row_referencia_editar['Str_tratamiento']))) {echo "selected=\"selected\"";} ?>>DOBLE CARA</option>
      </select>        	  
      <input type="submit" name="Submit" class="botonGMini" value="ADD">
      <input name="n_cn2" type="hidden" id="n_cn2" value="<?php echo $row_referencianueva2['N_cotizacion']; ?>"><input name="cod_ref2" type="hidden" id="cod_ref2" value="<?php echo $row_referencianueva2['N_referencia_c']; ?>">
      <input name="Str_nit2" type="hidden" id="Str_nit2" value="<?php echo $row_referencianueva2['Str_nit']; ?>">
      <input name="valor_ref2" type="hidden" id="valor_ref2" value="<?php echo $row_referencianueva2['N_precio_vnta']; ?>" />
      <input name="id" type="hidden" id="id" value="3" />
    </form>
  </td>
    <td id="dato2">
      <form name="form4" method="post" action="referencia_nueva2.php" enctype="multipart/form-data">	  
      <input type="submit" class="botonDel" name="Submit" value="DEL">
      <input name="n_cn2" type="hidden" id="n_cn2" value="<?php echo $row_referencianueva2['N_cotizacion']; ?>">
      <input name="cod_ref2" type="hidden" id="cod_ref2" value="<?php echo $row_referencianueva2['N_referencia_c']; ?>">
      <input name="Str_nit2" type="hidden" id="Str_nit2" value="<?php echo $row_referencianueva2['Str_nit']; ?>">
      <input name="id" type="hidden" id="id" value="4" />
    </form>
  </td>
  <td id="dato2"> 
          <?php
            switch ($row_referencianueva['B_estado']) {
              case '0':
                 echo 'Pendiente';
                break;
              case '1':
                 echo 'Aceptada';
                break;
              case '2':
                 echo 'Rechazada';
                break;
              case '3':
                 echo 'Obsoleta';
                break;
              
              default:
                 echo '';
                break;
            }

           ?>
  </td>
  </tr>
<?php } while ($row_referencianueva2 = mysql_fetch_assoc($referencianueva2)); ?>
<?php } ?>    
<!--PARA LAMINAS-->
<?php if($row_referencianueva3['N_referencia_c']!=''){ ?>
 <tr id="tr2">
  <td id="detalle2">REFERENCIA</td>
  <!--<td id="detalle2">EGP</td>-->
  <td id="detalle2">COTIZACION</td>
  <td nowrap="nowrap" id="detalle2">LAMINAS</td>
  <td id="detalle2">&nbsp;</td>
  <td id="detalle2">MATERIAL</td>
  <td id="detalle2">PRESENTACION</td>
  <td id="detalle2">TRATAMIENTO</td>
  <td id="detalle2">ADICIONAR</td>
  <td id="detalle2">RECHAZAR</td>
  <td id="detalle2">ESTADO COTIZ</td>
</tr>     
<?php do if($row_referencianueva3['N_referencia_c']!=''){ ?>

  <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#ffffffff');" bgcolor="#FFFFFF">
    <td id="dato2"><?php echo $row_referencianueva3['N_referencia_c']; ?></td>
    <!--<td id="dato3"><?php echo $row_referencianueva3['N_referencia_c']; ?></td>-->
    <td id="dato2"><?php echo $row_referencianueva3['N_cotizacion']; ?></td>
    <td id="dato2"><?php if($row_referencianueva3['N_referencia_c']!='') echo "LAMINAS"?>
    <input name="tipo_bolsa" type="hidden" id="tipo_bolsa" value="<?php echo "LAMINAS"; ?>" />  
  </td>
  <td id="dato2">&nbsp;</td>
  <td id="dato2"><?php echo $row_referencianueva3['Str_tipo_coextrusion'];?></td>
  <td colspan="3" id="dato1"><form name="form5" method="post" action="referencia_nueva2.php" enctype="multipart/form-data">
    <select name="presen3" id="presen3" style="width:95px">
      <option value=""></option>
      <option value="N.A">N.A</option>
      <option value="LAMINA">LAMINA</option>
      <option value="TUBULAR">TUBULAR</option>
      <option value="SEMITUBULAR">SEMITUBULAR</option>
    </select>
    <select name="trata3" id="trata3" style="width:85px">
      <option value=""></option>
      <option value="N.A"<?php if (!(strcmp('N.A', $row_referencia_editar['Str_tratamiento']))) {echo "selected=\"selected\"";} ?>>N.A</option>
      <option value="UNA CARA"<?php if (!(strcmp('UNA CARA', $row_referencia_editar['Str_tratamiento']))) {echo "selected=\"selected\"";} ?>>UNA CARA</option>
      <option value="DOBLE CARA"<?php if (!(strcmp('DOBLE CARA', $row_referencia_editar['Str_tratamiento']))) {echo "selected=\"selected\"";} ?>>DOBLE CARA</option>
    </select>        	  
    <input type="submit" name="Submit" class="botonGMini" value="ADD">
    <input name="n_cn3" type="hidden" id="n_cn3" value="<?php echo $row_referencianueva3['N_cotizacion']; ?>">
    <input name="cod_ref3" type="hidden" id="cod_ref3" value="<?php echo $row_referencianueva3['N_referencia_c']; ?>">
    <input name="Str_nit3" type="hidden" id="Str_nit3" value="<?php echo $row_referencianueva3['Str_nit']; ?>">
    <input name="valor_ref3" type="hidden" id="valor_ref3" value="<?php echo $row_referencianueva3['N_precio_k']; ?>" />
    <input name="id" type="hidden" id="id" value="5" />
  </form></td>
  <td id="dato2"><form name="form6" method="post" action="referencia_nueva2.php" enctype="multipart/form-data">	  
    <input type="submit" class="botonDel" name="Submit" value="DEL">
    <input name="n_cn3" type="hidden" id="n_cn3" value="<?php echo $row_referencianueva3['N_cotizacion']; ?>">
    <input name="cod_ref3" type="hidden" id="cod_ref3" value="<?php echo $row_referencianueva3['N_referencia_c']; ?>">
    <input name="Str_nit3" type="hidden" id="Str_nit3" value="<?php echo $row_referencianueva3['Str_nit']; ?>">
    <input name="id" type="hidden" id="id" value="6" />
  </form>
</td>
<td id="dato2"> 
        <?php
          switch ($row_referencianueva['B_estado']) {
            case '0':
               echo 'Pendiente';
              break;
            case '1':
               echo 'Aceptada';
              break;
            case '2':
               echo 'Rechazada';
              break;
            case '3':
               echo 'Obsoleta';
              break;
            
            default:
               echo '';
              break;
          }

         ?>
</td>
</tr>
<?php } while ($row_referencianueva3 = mysql_fetch_assoc($referencianueva3)); ?>
<?php } ?>      
</table> 

</div>
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
<?php
mysql_free_result($usuario);
mysql_free_result($referencianueva);
mysql_free_result($referencianueva2);
mysql_free_result($referencianueva3);
?>
