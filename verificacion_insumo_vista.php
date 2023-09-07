<?php require_once('Connections/conexion1.php'); ?><?php
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

$colname_verificacion = "-1";
if (isset($_GET['n_vi'])) {
  $colname_verificacion = (get_magic_quotes_gpc()) ? $_GET['n_vi'] : addslashes($_GET['n_vi']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_verificacion = sprintf("SELECT * FROM verificacion_insumos WHERE n_vi = %s", $colname_verificacion);
$verificacion = mysql_query($query_verificacion, $conexion1) or die(mysql_error());
$row_verificacion = mysql_fetch_assoc($verificacion);
$totalRows_verificacion = mysql_num_rows($verificacion);

$colname_verificacion_no = "-1";
if (isset($_GET['n_vi'])) {
  $colname_verificacion_no = (get_magic_quotes_gpc()) ? $_GET['n_vi'] : addslashes($_GET['n_vi']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_verificacion_no = sprintf("SELECT * FROM verificacion_noconformei WHERE n_vi = %s", $colname_verificacion_no);
$verificacion_no = mysql_query($query_verificacion_no, $conexion1) or die(mysql_error());
$row_verificacion_no = mysql_fetch_assoc($verificacion_no);
$totalRows_verificacion_no = mysql_num_rows($verificacion_no);

?>
<html>
<head>
  <title>SISADGE AC &amp; CIA</title>
  <link href="css/vista.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="js/vista.js"></script>
</head>
<body>
  <div align="center">
    <table id="tabla2">
      <tr>
        <td id="noprint" align="right"><img src="images/ciclo1.gif" alt="RESTAURAR" border="0" style="cursor:hand;" onClick="window.history.go()"/><img src="images/impresor.gif" onClick="window.print();" style="cursor:hand;" alt="IMPRIMIR" border="0" /><a href="verificacion_insumo_edit.php?n_vi=<?php echo $row_verificacion['n_vi']; ?>&n_oc=<?php echo $row_verificacion['n_oc_vi']; ?>&id_insumo=<?php echo $row_verificacion['id_insumo_vi']; ?>"><img src="images/menos.gif" alt="EDITAR" border="0" /></a><a href="verificacion_insumo_oc.php?n_oc=<?php echo $row_verificacion['n_oc_vi']; ?>"><img src="images/v.gif" alt="VERIF X INSUMO" border="0" style="cursor:hand;"/></a><a href="verificaciones_criticos.php"><img src="images/cat.gif" style="cursor:hand;" alt="VERIFICACIONES (CRITICOS)" border="0"/></a><a href="orden_compra.php"><img src="images/o.gif" alt="ORDENES DE COMPRA" border="0" style="cursor:hand;"/></a><a href="insumos.php"><img src="images/i.gif" style="cursor:hand;" alt="INSUMOS" border="0"/></a><a href="compras.php"><img src="images/opciones.gif" style="cursor:hand;" alt="GESTION COMPRAS" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" border="0"/></a><img src="images/salir.gif" style="cursor:hand;" alt="SALIR" onClick="window.close() "/></td>
      </tr>
    </table>
    <table id="tabla1"><tr><td align="center">
      <table id="tabla2">
        <tr><td rowspan="4" id="fondo2"><img src="images/logoacyc.jpg"></td>
          <td id="titulo">VERIFICACION</td>
        </tr>

        <tr>
          <td id="titular2">INSUMOS</td>
        </tr>
        <tr>
          <td id="numero2">N <strong><?php echo $row_verificacion['n_vi']; ?></strong></td>
        </tr>
        <tr>
          <td id="fondo2">ALBERTO CADAVID R & CIA S.A.  Nit: 890915756-6<br>Carrera 45 No. 14 - 15  Tel: 311-21-44  Fax: 266-41-23  Medellin-Colombia</td>
        </tr>
      </table>
      <table id="tabla2">
        <tr>
          <td colspan="4" id="subtitulo2"> O.C. N&deg; <strong><?php echo $row_verificacion['n_oc_vi']; ?>
            </strong>DE <strong><?php $id_p=$row_verificacion['id_p_vi'];
            $sqlp="SELECT * FROM proveedor WHERE id_p='$id_p'";
            $resultp=mysql_query($sqlp);
            $nump=mysql_num_rows($resultp);
            if($nump >= '1') { $proveedor_p=mysql_result($resultp,0,'proveedor_p'); echo $proveedor_p; }
            else { echo "";	} ?>
          </strong></td>
        </tr>
        <tr>
          <td colspan="4" id="dato2">INSUMO : <strong><?php $id_insumo=$row_verificacion['id_insumo_vi'];
          $sqlinsumo="SELECT * FROM insumo WHERE id_insumo='$id_insumo'";
          $resultinsumo=mysql_query($sqlinsumo);
          $numinsumo=mysql_num_rows($resultinsumo);
          if($numinsumo >= '1') { $descripcion_insumo=mysql_result($resultinsumo,0,'descripcion_insumo'); echo $descripcion_insumo; }
          else { echo "";	} ?></strong></td>
        </tr>

        <tr>
          <td id="subtitulo1">FECHA RECIBIDO</td>
          <td id="subtitulo1">RECIBIDO POR</td>
          <td id="subtitulo1">FACTURA</td>
          <td id="subtitulo1">REMISION</td>
        </tr>
        <tr>
          <td id="dato1"><?php echo $row_verificacion['fecha_vi']; ?></td>
          <td id="dato1"><?php echo $row_verificacion['recibido_vi']; ?></td>
          <td id="dato1"><?php echo $row_verificacion['factura_vi']; ?></td>
          <td id="dato1"><?php echo $row_verificacion['remision_vi']; ?></td>
        </tr>
        <tr>
          <td id="subtitulo1">ENTREGA</td>
          <td id="subtitulo1">CANT. SOLICITADA </td>
          <td id="subtitulo1">CANT. RECIBIDA </td>
          <td id="subtitulo1">FALTANTES</td>
        </tr>
        <tr>
          <td id="dato1"><?php echo $row_verificacion['entrega_vi']; ?></td>
          <td id="dato1"><?php echo $row_verificacion['cantidad_solicitada_vi']; ?></td>
          <td id="dato1"><?php echo $row_verificacion['cantidad_recibida_vi']; ?></td>
          <td id="dato1"><?php echo $row_verificacion['faltantes_vi']; ?></td>
        </tr>
        <tr>
          <td colspan="4" id="dato1"><strong>OBSERVACIONES : </strong><?php echo $row_verificacion['observaciones_vi']; ?></td>
        </tr>
        <tr>
          <td colspan="2" id="dato1">APARIENCIA DEL INSUMO : 
            <?php $apariencia=$row_verificacion['apariencia_vi'];
            if($apariencia=='0') { echo "Mala"; }
            if($apariencia=='0.5') { echo "Regular"; }
            if($apariencia=='1') { echo "Buena"; } ?></td>
            <td colspan="2" id="dato1"><input <?php if (!(strcmp($row_verificacion['accion_vi'],1))) {echo "checked=\"checked\"";} ?> name="accion_vi" type="checkbox" id="accion_vi" value="1">
            REQUIERE PLAN DE ACCION</td>
          </tr>
          <tr>
            <td colspan="2" id="dato1">REGISTRO : <?php echo $row_verificacion['registro_vi']; ?> - <?php echo $row_verificacion['fecha_registro_vi']; ?></td>
            <td colspan="2" id="dato1">( <strong><?php echo $row_verificacion['servicio_vi']; ?></strong> ) CALIFICACION DEL SERVICIO </td>
          </tr>
          <tr> 
            <td colspan="2" id="fuente1">USUARIO</td>
            <td colspan="2" id="fuente1">CALIDAD CUMPLE?</td>  
          </tr>
          <tr>
            <td colspan="2" id="dato1"><?php echo $row_verificacion['usuario']; ?></td>
            <td colspan="2" id="dato1"><?php echo $row_verificacion['autorizado']; ?>
            </td> 
          </tr>
          

          <tr>
           
                  <?php 
                 
                  $porciones = array();
                  $porciones = explode(",", $row_verificacion['userfilenuevo']);
                  $count = 0;
                  ?>
                  <?php if( $row_verificacion['userfilenuevo'] != ''): ?>
                   <?php foreach ($porciones as $key => $value) { ?>
                    <?php $count++;?>
                    <?php if($value!=''):?> 
                    <td id="dato1" >
                      <a href="javascript:verFoto('ArchivosVerifInsumo/<?php echo $value;?>','610','490')">Archivo<?php echo $count;?></a> 
                      <input name="userfile<?php echo $count;?>" type="hidden" id="userfile<?php echo $count;?>" value="<?php echo $value; ?>"/> 
                     </td>
                    <?php endif; ?>
                  <?php } ?> 
                <?php endif; ?>
              
            </tr>
            






          <!-- INICIA NO CONFORME -->
          <?php if($row_verificacion_no['no_conforme']=='NO'): ?> 
            <hr>
            <tr>
             <td colspan="9" >
               <div id="noconforme" >
                 <table class="table">
                   <thead >
                     <tr id="tr2">
                       <th id="subtitulo2" colspan="14" scope="col" style="text-align: center;" >IDENTIFICACION</th> 
                     </tr>
                   </thead>
                   <tbody>
                     <tr>
                       <th><b> Ensayo N: </b></th>
                       <td><?php echo $row_verificacion_no['ensayo']; ?>  </td>
                       <td ><b>Fecha: </b> </td>
                       <td colspan="6" ><?php echo $row_verificacion_no['fecha']; ?> </td> 
                       
                     </tr>
                     <tr>
                       <th ><b> Proveedor: </b></th>
                       <td><?php echo $row_verificacion_no['proveedor']; ?></td>
                       <td > <b> Lote de Produccion: </b></td>
                       <td colspan="6" ><?php echo $row_verificacion_no['loteprod']; ?></td>
                     </tr>
                     <tr>
                       <th><b> Referencia: </b></th>
                       <td><?php echo $row_verificacion_no['referencia']; ?></td>
                       <td><b> Ancho (mm): </b></td>
                       <td><b><?php echo $row_verificacion_no['ancho']; ?></b></td>
                       <td><b>  Factura :</b></td>
                       <td  ><?php echo $row_verificacion_no['factura']; ?></td>
                     </tr>
                     <tr>
                       <th><b> O de Compra: </b></th>
                       <td><?php echo $row_verificacion_no['ocompra']; ?></td>
                       <td><b> Destinada a: </b></td>
                       <td><?php echo $row_verificacion_no['destinada']; ?></td>
                       <td><b>  Produccion:</b></td>
                       <td><?php echo $row_verificacion_no['produccion']; ?></td>
                       <td><b>  Otros</b></td>
                       <td><?php echo $row_verificacion_no['otros']; ?></td>
                     </tr>
                   </tbody>
                 </table>

                 <table class="table">
                   <thead >
                     <tr id="tr2">
                       <th id="subtitulo2" colspan="14" scope="col" style="text-align: center;" >CONDICIONES DE ENSAYO</th> 
                     </tr>
                   </thead>
                   <tbody>
                     <tr>
                       <td><b> Color del Film: </b></td> 
                       <td ><?php echo $row_verificacion_no['colorfilm']; ?></td> 
                       <td><b> Numero de Pruebas: </b></td> 
                       <td ><?php echo $row_verificacion_no['numprueba']; ?></td> 
                     </tr>
                   </tbody>
                 </table>


                 <table class="table">
                   <thead >
                     <tr id="subtitulo2" >
                       <th id="subtitulo2" nowrap colspan="4" scope="col" >Ensayo con Calor 48  c</th>
                       <th id="subtitulo2" nowrap colspan="4" scope="col" >Ensayo con frio -96 c </th> 
                       <th id="subtitulo2" nowrap colspan="4" scope="col" >Ensayo temperatura ambiente 25 c </th> 
                     </tr>
                   </thead>
                   <tbody>
                     <tr>
                       <td colspan="3"><b> Prueba</b></td>
                       <td><b> Cumple</b></td>
                       <td colspan="3"><b> Prueba</b></td>
                       <td><b> Cumple</b></td>
                       <td colspan="3"><b> Prueba</b></td>
                       <td><b> Cumple</b></td>
                     </tr>
                     <tr>
                       <td colspan="3" >1</td>
                       <td nowrap> <?php echo $row_verificacion_no['preg_1']=='' ? 'NO' : $row_verificacion_no['preg_1']; ?></td>
                       <td colspan="3" >1</td>
                       <td nowrap> <?php echo $row_verificacion_no['preg_6']=='' ? 'NO' : $row_verificacion_no['preg_6']; ?></td>
                       <td colspan="3" >1</td>
                       <td nowrap> <?php echo $row_verificacion_no['preg_11']=='' ? 'NO' : $row_verificacion_no['preg_11']; ?></td>
                     </tr> 
                     <tr>
                       <td colspan="3" >2</td>
                       <td nowrap> <?php echo $row_verificacion_no['preg_2']=='' ? 'NO' : $row_verificacion_no['preg_2']; ?></td>
                       <td colspan="3" >2</td>
                       <td nowrap> <?php echo $row_verificacion_no['preg_7']=='' ? 'NO' : $row_verificacion_no['preg_7']; ?></td>
                       <td colspan="3" >2</td>
                       <td nowrap> <?php echo $row_verificacion_no['preg_12']=='' ? 'NO' : $row_verificacion_no['preg_12']; ?></td>
                     </tr>
                     <tr>
                       <td colspan="3" >3</td>
                       <td nowrap>  <?php echo $row_verificacion_no['preg_3']=='' ? 'NO' : $row_verificacion_no['preg_3']; ?></td>
                       <td colspan="3" >3</td>
                       <td nowrap>  <?php echo $row_verificacion_no['preg_8']=='' ? 'NO' : $row_verificacion_no['preg_8']; ?></td>
                       <td colspan="3" >3</td>
                       <td nowrap>  <?php echo $row_verificacion_no['preg_13']=='' ? 'NO' : $row_verificacion_no['preg_13']; ?></td>
                     </tr>
                     <tr>
                       <td colspan="3" >4</td>
                       <td nowrap>  <?php echo $row_verificacion_no['preg_4']=='' ? 'NO' : $row_verificacion_no['preg_4']; ?></td>
                       <td colspan="3" >4</td>
                       <td nowrap>  <?php echo $row_verificacion_no['preg_9']=='' ? 'NO' : $row_verificacion_no['preg_9']; ?></td>
                       <td colspan="3" >4</td>
                       <td nowrap>  <?php echo $row_verificacion_no['preg_14']=='' ? 'NO' : $row_verificacion_no['preg_14']; ?></td>
                     </tr>
                     <tr>
                       <td colspan="3" >5</td>
                       <td nowrap>  <?php echo $row_verificacion_no['preg_5']=='' ? 'NO' : $row_verificacion_no['preg_5']; ?></td>
                       <td colspan="3" >5</td>
                       <td nowrap>  <?php echo $row_verificacion_no['preg_10']=='' ? 'NO' : $row_verificacion_no['preg_10']; ?></td>
                       <td colspan="3" >5</td>
                       <td nowrap>  <?php echo $row_verificacion_no['preg_15']=='' ? 'NO' : $row_verificacion_no['preg_15']; ?></td>
                     </tr>
                   </tbody>
                 </table>
               </div>
             </td>
           </tr>

         <?php endif;?> 

         
         
       </table>
       <table id="tabla2">
        <tr>
          <td id="fondo1">CODIGO : A3 - F08</td>
          <td id="fondo3">VERSION :0</td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($verificacion);
?>