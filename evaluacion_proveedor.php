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

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario); 

$row_proveedores = $conexion->llenaSelect('proveedor',"","ORDER BY proveedor_p ASC");  

$colname_proveedor = "-1";
if (isset($_GET['id_p'])) {
  $colname_proveedor = (get_magic_quotes_gpc()) ? $_GET['id_p'] : addslashes($_GET['id_p']);
} 

$row_proveedor = $conexion->llenarCampos("proveedor", "WHERE id_p ='".$colname_proveedor."' ", " ", "proveedor_p");

$colname_evaluaciones = "-1";
if (isset($_GET['id_p'])) {
  $colname_evaluaciones = (get_magic_quotes_gpc()) ? $_GET['id_p'] : addslashes($_GET['id_p']);
}
/*mysql_select_db($database_conexion1, $conexion1);
$query_evaluaciones = sprintf("SELECT * FROM evaluacion_proveedor WHERE id_p_ev = %s ORDER BY n_ev DESC", $colname_evaluaciones);
$evaluaciones = mysql_query($query_evaluaciones, $conexion1) or die(mysql_error());
$row_evaluaciones = mysql_fetch_assoc($evaluaciones);
$totalRows_evaluaciones = mysql_num_rows($evaluaciones);*/

$row_evaluaciones = $conexion->llenaSelect('proveedor pr left join evaluacion_proveedor ep '," ON pr.id_p=ep.id_p_ev WHERE pr.id_p = '$colname_evaluaciones' ","ORDER BY n_ev DESC");  

?><html>
<head>
  <title>SISADGE AC &amp; CIA</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="css/formato.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="js/formato.js"></script>
  <script type="text/javascript" src="js/listado.js"></script>
  <script type="text/javascript" src="AjaxControllers/js/consultas.js"></script>

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

  <!-- css Bootstrap-->
  <link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"> 

</head>
<body>
  <?php echo $conexion->header('vistas'); ?>

  <table class="table table-bordered table-sm">
    <tr>
      <td id="titulo">EVALUACION DE DESEMPENO DEL PROVEEDOR</td>
    </tr>
    <tr>
      <td id="dato2">
        <?php $id_p= $_GET['id_p']; $evaluacion= $_GET['evaluacion'];
        if($id_p == '' && $evaluacion == '') { ?>
          <table class="table table-bordered table-sm">
            <tr>
              <td id="codigo" width="25%">CODIGO : A3 - F05 </td>
              <td id="titulo2" width="50%"><a href="evaluacion_proveedor.php"><img src="images/ciclo1.gif" alt="RESTAURAR" border="0" style="cursor:hand;" /></a><a href="proveedores.php"><img src="images/p.gif" alt="PROVEEDORES" border="0" /></a><a href="insumos.php"><img src="images/i.gif" style="cursor:hand;" alt="INSUMOS" border="0" /></a><a href="orden_compra.php"><img src="images/o.gif" style="cursor:hand;" alt="ORDENES DE COMPRA" border="0" /></a><a href="verificaciones_criticos.php"><img src="images/v.gif" style="cursor:hand;" alt="VERIFICACIONES (INSUMOS CRITICOS)" border="0" /></a></td>
              <td id="codigo" width="25%">VERSION : 2 </td>
            </tr>
            <tr>
              <td id="codigo"><img src="images/logoacyc.jpg" /></td>
              <td colspan="2" id="dato1">
               <form name="form1" method="get" action="evaluacion_proveedor.php">
                 Seleccione el Proveedor a Evaluar<br>

                 <select name="id_p" id="id_p"  class="busqueda selectsGrande">
                  <option value="0">O.P</option>
                  <?php  foreach($row_proveedores as $row_proveedores ) { ?>
                    <option value="<?php echo $row_proveedores['id_p']?>"><?php echo $row_proveedores['proveedor_p']?></option>
                  </option>
                <?php } ?>
              </select> 

              <br><br> 
              <select name="evaluacion" id="evaluacion">
                  <option value="">N.A.</option>
                  <option value="PRODUCTOS">PRODUCTO</option>
                  <option value="SERVICIOS" selected="selected">SERVICIOS</option>
                  <option value="PRODUCTO-SERVICIOS">PRODUCTO-SERVICIOS</option>
              </select>

              <!-- <select name="evaluacion">
               <option value="1">PRODUCTO</option>
               <option value="2">SERVICIO</option>
             </select> -->
             <br><br>        
             <input name="Submit" class="botonGeneral" type="submit" value="EVALUACION">        
           </form></td>
         </tr>


         <tr>
          <td id="dato2">&nbsp;</td>
          <td id="dato2">&nbsp;</td>
          <td id="dato2">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" id="justificacion">Segun <strong>ANEXO 4. METODOLOGIA DE EVALUACION DE DESEMPENO DE PROVEEDORES</strong><br>
            <br>
            <strong>A. EVALUACION DE DESEMPENO DEL PROVEEDOR PRODUCTOS CRITICOS</strong>.<br><br>
            En el momento que se recibe el material, se realiza la inspeccion de acuerdo a la guia A3-G02 Plan de Inspeccion de Materia Prima. Cuando el material recibido es polietileno con o sin impresion o bolsas terminadas, se registra la informacion en el formato A3-F08 Verificacion de Materia Prima y Productos recibidos de acuerdo a los parametros definidos para cada caso.<br><br>
            El proveedor de insumos criticos como tintas, aditivos, teflen, entre otros se verifica en el momento de recepcien la cantidad (contenido) y apariencia del material dejando evidencia (visto bueno) en el documento del proveedor y/o en la orden de compra interna. Los resultados de las verificaciones realizadas se registran en el formato A3-F05 Evaluacion de DesempeNo del Proveedor teniendo en cuenta las variables de control establecidas para ponderar anualmente su desempeNo. Para los insumos se han definido cuatro variables de control y su aceptacion como son:<br><br><strong>1. Oportunidad (25%)</strong>: Donde se valida el cumplimiento en la entrega, donde debe ser antes o igual a la fecha de compromiso.<br><strong>2. Cumplimiento en Cantidad (25%)</strong>: Donde se valida que la cantidad recibida sea por lo menos el 90% de los solicitado.<br><strong>3. Calidad (25%)</strong>: Se valida el cumplimiento de la verificacion realizada en el formato A3-F08 Verificacion (INSUMOS CRITICOS) recibidos de acuerdo a los parametros cualitativos y cuantitativos establecidos.<br><strong>4. Servicio (25%)</strong>: Se califica de a 1 a 10 validando la atencion durante la prestacion del servicio desde el momento en que se pacta la compra hasta la recepcion, como son la atencion a inquietudes, quejas, reclamos, sugerencias y amabilidad.<br><br><strong>B. EVALUACION DE DESEMPENO DEL PROVEEDOR DE SERVICIOS CRITICOS</strong>. <br><br>Cuando se solicita la prestacion del servicio, despues de su ejecucion se validan estas variables:<br><br><strong>1. Oportunidad (40%)</strong>: Verificando el cumplimiento en tiempo de la prestacion del servicio, el cual debe ser igual a la fecha de compromiso.<br><strong>2. Calidad (35%)</strong>: Se valida el cumplimiento de la prestacion del servicio en cuanto al correcto desempeNo para lo cual fue contratado, Ej: Eficacia del trabajo realizado en proveedores de mantenimiento, recepcion y transporte de productos en proveedores de transporte y Cias, etc.<br><strong>3. Servicio (25%)</strong>: Se califica de 1 a 10, validando la atencion durante la prestacion del servicio hasta la verificacion del cumplimiento del trabajo realizado. Tambien se tiene en cuenta la atencion a inquietudes, quejas, reclamos, sugerencias y amabilidad en la prestacion del servicio.<br><br>Para la evaluacion de la calidad en la prestacion del servicio de transporte se tiene en cuenta la disponibilidad y amabilidad del personal que recoge la mercancia (estado en que llegue la mercancia al destino final en caso que sea reportado).</td>
          </tr>
        </table>
      <?php } if($id_p != ''){ // && $evaluacion == 'PRODUCTOS' ?>
        <table id="tabla3">
          <tr>
            <td colspan="8" id="fuente1"><strong>PROVEEDOR : </strong>
              <?php echo $row_proveedor['proveedor_p']; ?></td>
              <td colspan="3" id="dato3"><a href="evaluacion_proveedor_add.php?id_p=<?php echo $_GET['id_p']; ?>&evaluacion=<?php echo $_GET['evaluacion']; ?>"><img src="images/mas.gif" alt="ADD EVALUACION" border="0" style="cursor:hand;" /></a><a href="evaluacion_proveedor.php"><img src="images/e.gif" alt="CAMBIAR DE PROVEEDOR" border="0" style="cursor:hand;" /></a><a href="proveedores.php"><img src="images/p.gif" alt="PROVEEDORES" border="0" /></a><a href="insumos.php"><img src="images/i.gif" style="cursor:hand;" alt="INSUMOS" border="0" /></a><a href="orden_compra.php"><img src="images/o.gif" style="cursor:hand;" alt="ORDENES DE COMPRA" border="0" /></a><a href="verificaciones_criticos.php"><img src="images/v.gif" style="cursor:hand;" alt="VERIFICACIONES (INSUMOS CRITICOS)" border="0" /></a></td>
            </tr>
            <tr>
              <td id="titulo4">N&deg;</td>
              <td id="titulo4">DESDE</td>
              <td id="titulo4">HASTA</td>
              <td id="titulo4"># O.C. </td>
              <td id="titulo4"># VERIF.</td>
              <td id="titulo4">% OPORTUNOS </td>
              <td id="titulo4">% CUMPLE</td>
              <td id="titulo4">% CONFORME </td>
              <td id="titulo4">% ATENCION </td>
              <td id="titulo4">% TOTAL</td>
              <td id="titulo4">CARTA</td>
            </tr>
            <?php foreach($row_evaluaciones as $row_evaluaciones) {  ?>
              <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF" bordercolor="#ACCFE8"> 
                <td id="detalle3"><a href="evaluacion_proveedor_vista.php?id_p= <?php echo $row_evaluaciones['id_p_ev']; ?>&evaluacion=<?php echo $_GET['evaluacion']; ?>&id_ev= <?php echo $row_evaluaciones['id_ev']; ?>&desde=<?php echo $row_evaluaciones['periodo_desde_ev']; ?>&hasta=<?php echo $row_evaluaciones['periodo_hasta_ev']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_evaluaciones['n_ev']; ?></a></td>
                <td id="detalle2"><a href="evaluacion_proveedor_vista.php?id_p= <?php echo $row_evaluaciones['id_p_ev']; ?>&evaluacion=<?php echo $_GET['evaluacion']; ?>&id_ev= <?php echo $row_evaluaciones['id_ev']; ?>&desde=<?php echo $row_evaluaciones['periodo_desde_ev']; ?>&hasta=<?php echo $row_evaluaciones['periodo_hasta_ev']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_evaluaciones['periodo_desde_ev']; ?></a></td>
                <td id="detalle2"><a href="evaluacion_proveedor_vista.php?id_p= <?php echo $row_evaluaciones['id_p_ev']; ?>&evaluacion=<?php echo $_GET['evaluacion']; ?>&id_ev= <?php echo $row_evaluaciones['id_ev']; ?>&desde=<?php echo $row_evaluaciones['periodo_desde_ev']; ?>&hasta=<?php echo $row_evaluaciones['periodo_hasta_ev']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_evaluaciones['periodo_hasta_ev']; ?></a></td>
                <td id="detalle2"><a href="evaluacion_proveedor_vista.php?id_p= <?php echo $row_evaluaciones['id_p_ev']; ?>&evaluacion=<?php echo $_GET['evaluacion']; ?>&id_ev= <?php echo $row_evaluaciones['id_ev']; ?>&desde=<?php echo $row_evaluaciones['periodo_desde_ev']; ?>&hasta=<?php echo $row_evaluaciones['periodo_hasta_ev']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_evaluaciones['total_oc_ev']; ?></a></td>
                <td id="detalle2"><a href="evaluacion_proveedor_vista.php?id_p= <?php echo $row_evaluaciones['id_p_ev']; ?>&evaluacion=<?php echo $_GET['evaluacion']; ?>&id_ev= <?php echo $row_evaluaciones['id_ev']; ?>&desde=<?php echo $row_evaluaciones['periodo_desde_ev']; ?>&hasta=<?php echo $row_evaluaciones['periodo_hasta_ev']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_evaluaciones['total_verificacion_ev']; ?></a></td>
                <td id="detalle2"><a href="evaluacion_proveedor_vista.php?id_p= <?php echo $row_evaluaciones['id_p_ev']; ?>&evaluacion=<?php echo $_GET['evaluacion']; ?>&id_ev= <?php echo $row_evaluaciones['id_ev']; ?>&desde=<?php echo $row_evaluaciones['periodo_desde_ev']; ?>&hasta=<?php echo $row_evaluaciones['periodo_hasta_ev']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_evaluaciones['porcentaje_oportunos_ev']; ?> %</a></td>
                <td id="detalle2"><a href="evaluacion_proveedor_vista.php?id_p= <?php echo $row_evaluaciones['id_p_ev']; ?>&evaluacion=<?php echo $_GET['evaluacion']; ?>&id_ev= <?php echo $row_evaluaciones['id_ev']; ?>&desde=<?php echo $row_evaluaciones['periodo_desde_ev']; ?>&hasta=<?php echo $row_evaluaciones['periodo_hasta_ev']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_evaluaciones['porcentaje_cumple_ev']; ?> %</a></td>
                <td id="detalle2"><a href="evaluacion_proveedor_vista.php?id_p= <?php echo $row_evaluaciones['id_p_ev']; ?>&evaluacion=<?php echo $_GET['evaluacion']; ?>&id_ev= <?php echo $row_evaluaciones['id_ev']; ?>&desde=<?php echo $row_evaluaciones['periodo_desde_ev']; ?>&hasta=<?php echo $row_evaluaciones['periodo_hasta_ev']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_evaluaciones['porcentaje_conforme_ev']; ?> %</a></td>
                <td id="detalle2"><a href="evaluacion_proveedor_vista.php?id_p= <?php echo $row_evaluaciones['id_p_ev']; ?>&evaluacion=<?php echo $_GET['evaluacion']; ?>&id_ev= <?php echo $row_evaluaciones['id_ev']; ?>&desde=<?php echo $row_evaluaciones['periodo_desde_ev']; ?>&hasta=<?php echo $row_evaluaciones['periodo_hasta_ev']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_evaluaciones['porcentaje_atencion_ev']; ?> %</a></td>
                <td id="detalle2"><a href="evaluacion_proveedor_vista.php?id_p= <?php echo $row_evaluaciones['id_p_ev']; ?>&evaluacion=<?php echo $_GET['evaluacion']; ?>&id_ev= <?php echo $row_evaluaciones['id_ev']; ?>&desde=<?php echo $row_evaluaciones['periodo_desde_ev']; ?>&hasta=<?php echo $row_evaluaciones['periodo_hasta_ev']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_evaluaciones['porcentaje_final_ev']; ?> %</a></td>
                <td id="detalle2">
                  <?php if($row_evaluaciones['tipo_servicio_p']=='PRODUCTOS'): ?>
                  <a href="evaluacion_proveedor_carta.php?id_ev=<?php echo $row_evaluaciones['id_ev']; ?>&tipo_evaluacion=<?php echo $row_evaluaciones['tipo_servicio_p']; ?>"><img src="images/carta.gif" alt="CARTA" title="PRODUCTOS" border="0" style="cursor:hand;" ></a>
                 <?php elseif($row_evaluaciones['tipo_servicio_p']=='SERVICIOS'):?>
                  <a href="evaluacion_proveedor_carta_servicio.php?id_ev=<?php echo $row_evaluaciones['id_ev']; ?>&tipo_evaluacion=<?php echo $row_evaluaciones['tipo_servicio_p']; ?>"><img src="images/carta.gif" alt="CARTA" title="SERVICIOS" border="0" style="cursor:hand;" ></a>
                  <?php elseif($row_evaluaciones['tipo_servicio_p']=='PRODUCTO-SERVICIOS'):?>
                    <a href="evaluacion_proveedor_carta_producto_servicio.php?id_ev=<?php echo $row_evaluaciones['id_ev']; ?>&tipo_evaluacion=<?php echo $row_evaluaciones['tipo_servicio_p']; ?>"><img src="images/carta.gif" alt="CARTA" title="PRODUCTO-SERVICIO" border="0" style="cursor:hand;" ></a>
                <?php endif;?>
                </td>
              </tr>
            <?php } ?>
          </table>
        <?php } ?>
        <?php echo $conexion->header('footer'); ?>
      </body>
      </html>
      <script type="text/javascript">
         $("#id_p").on("change", function(){
 
               consultaGeneralTodosAjax("proveedor","tipo_servicio_p","id_p","","",$("#id_p").val(),"","");
         });


      </script>
      <?php
      mysql_free_result($usuario);mysql_close($conexion1);

      mysql_free_result($proveedores);

      mysql_free_result($proveedor);

      mysql_free_result($evaluaciones);
      ?>
