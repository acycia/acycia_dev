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
session_start();
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


$maxRows_cotizacion = 20;
$pageNum_cotizacion = 0;
if (isset($_GET['pageNum_cotizacion'])) {
  $pageNum_cotizacion = $_GET['pageNum_cotizacion'];
}
$startRow_cotizacion = $pageNum_cotizacion * $maxRows_cotizacion;


if($_GET['orden']==''){
  $orden="N_cotizacion DESC";
}else{
 $orden=$_GET['orden'];
}
/*mysql_select_db($database_conexion1, $conexion1);
$query_cotizacion = "SELECT * FROM tbl_cotizaciones, cliente WHERE tbl_cotizaciones.Str_nit = cliente.nit_c ORDER BY $orden";
$cotizacion = mysql_query($query_cotizacion, $conexion1) or die(mysql_error());
$row_cotizacion = mysql_fetch_assoc($cotizacion);
$totalRows_cotizacion = mysql_num_rows($cotizacion);*/

$row_cotizacion = $conexion->buscarListar("tbl_cotizaciones ","*","ORDER BY $orden","WHERE fecha > '2017-01-01'",$maxRows_cotizacion,$pageNum_cotizacion,"" );


if (isset($_GET['totalRows_cotizacion'])) {
  $totalRows_cotizacion = $_GET['totalRows_cotizacion'];
} else {
  $totalRows_cotizacion = $conexion->conteo('tbl_cotizaciones'); 
} 
$totalPages_cotizacion = ceil($totalRows_cotizacion/$maxRows_cotizacion)-1; 

$row_cliente = $conexion->llenaSelect('cliente',' ','ORDER BY nombre_c ASC'); 

session_start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0014)about:internet -->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>SISADGE AC &amp; CIA</title>
  <link href="css/formato.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="js/listado.js"></script>
  <script type="text/javascript" src="js/formato.js"></script>

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

  <!-- css Bootstrap hace mas grande el formato-->
  <link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

</head>
<body >
  <?php echo $conexion->header('listas'); ?>
  <table >
    <tr bgcolor="#1B3781">
      <td > </td>
    </tr>
    <tr>
      <td bordercolor="#FFFFFF" bgcolor="#FFFFFF">
        <table width="730" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
          <tr>
            <td colspan="2" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left" class="Estilo1 Estilo9"><a href="comercial.php" class="Estilo7">Gestión Comercial </a></div>
            </td>
            <td colspan="2" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left" class="Estilo1 Estilo9"><?php echo $row_usuario['nombre_usuario']; ?></div></td>
            <td width="128" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right"><a href="<?php echo $logoutAction ?>" class="Estilo2">Cerrar Sesión</a></div></td>
          </tr>

          <tr>
            <td width="170" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center"><span class="Estilo6"><strong>Codigo: R1-F03</strong></span></div></td>
            <td width="404" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center"><span class="Estilo76">SEGUIMIENTO A COTIZACIONES</span></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo6">Versión:0</div></td>
          </tr>
          <tr id="tr1">
             <td colspan="7" id="fuente1"><em style="color: red;" >La cotizaciones se eliminan si son del dia actual, sino solamente pasan a estado Rechazada! </em> </td>
          </tr>

          <tr> 
            <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><?php $id=$_GET['id'];  
            if($id >= '1') { ?> <div id="acceso1"> <?php echo "Se cambio de estado a rechazada"; ?> </div><?php }
            if($id == '0') { ?><div id="numero1"> <?php echo "No se pudo eliminar o actualizar"; ?> </div> <?php }?></td> 
          </tr>
          <tr>
            <td colspan="2" bordercolor="#FFFFFF" bgcolor="#FFFFFF" nowrap="nowrap"><form name="form1" id="form1" method="get" action="cotizacion_copia2.php" >
              <span class="Estilo5"> 
                Cotización
                <input type="radio" name="campo" value="N_cotizacion" />
                Cliente
                <input type="radio" name="campo" value="Str_nit" />
                Nit
                <input type="radio" name="campo" value="Str_nit" />
              </span>
              <input name="criterio" type="text" class="Estilo68" id="criterio" size="8"/>

              <select class="selectsGrande busqueda" name="id_c" id="id_c">
               <option value="0">Seleccione el Cliente</option>
               <?php  foreach($row_cliente as $row_cliente ) { ?>
                <option value="<?php echo $row_cliente['nit_c']?>"><?php echo $row_cliente['nombre_c']?></option>

              <?php } ?>
            </select>

            <input name="Submit" type="submit"class="botonGMini" value="Buscar" />
          </form></td>
          <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo3">
            <div align="center"><a href="cotizacion_general_menu.php" class="Estilo4">*Add Cotización </a></div>
          </div></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td bordercolor="#FFFFFF" bgcolor="#FFFFFF">
        <div >
          <table width="776" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
            <tr>
              <td nowrap="nowrap" width="104" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo6"><a href="cotizacion_copia.php?orden=<?php echo "N_cotizacion DESC";?>">Cotiz Nº</a></div></td>
              <td width="128" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo6">Producto</div></td>
              <td width="250" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo6"><a href="cotizacion_copia.php?orden=<?php echo "Str_nit ASC";?>">Cliente</a></div></td>
              <td width="100" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo6"><a href="cotizacion_copia.php?orden=<?php echo "Str_nit ASC";?>">Nit</a></div></td>
              <td width="51" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo6">Fecha</div></td>
              <td width="51" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo6">Estado</div></td> 
              <td width="51" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo6">View</div></td> 
              <td width="54" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo6">Delete</div></td>
            </tr>
            <?php 
            $i=0; 
            foreach($row_cotizacion as $row_cotizacion)  { 
         
             switch ($row_cotizacion['Str_tipo']) {
              case "BOLSA":
              $LINCK ="cotizacion_g_bolsa_vista.php?";
              $basdatos = "Tbl_cotiza_bolsa";
              break;
              case "PACKING LIST":
              $LINCK ="cotizacion_g_packing_vista.php?";
              $basdatos = "Tbl_cotiza_packing";
              break;
              case "MATERIA PRIMA":
              $LINCK ="cotizacion_g_materiap_vista.php?";
              $basdatos = "Tbl_cotiza_materia_p";
              break;
              case "LAMINA":
              $LINCK ="cotizacion_g_lamina_vista.php?";
              $basdatos = "Tbl_cotiza_laminas";
              break;	
            }

            ?>
            <tr <?php if ($i%2==0) {?> style="background:#FFFFFF" <?php }else {?>style="background:#ECF5FF" <?php } $i++; ?>>
              <td width="104"><a href="<?php echo $LINCK;?>N_cotizacion=<?php echo $row_cotizacion['N_cotizacion']; ?>&cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "6"; ?>" target="new" style="text-decoration:none; color:#000000"><div align="center" class="Estilo8"><?php echo $row_cotizacion['N_cotizacion']; ?> 
                 </a> 
            </td>
              <td nowrap="nowrap" width="128"><a href="<?php echo $LINCK;?>N_cotizacion=<?php echo $row_cotizacion['N_cotizacion']; ?>&cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "6"; ?>" target="new" style="text-decoration:none; color:#000000"><div align="left"><span class="Estilo8"><?php echo $row_cotizacion['Str_tipo']; ?>
                
              </a>
              </span>
              </div>
            </td>
              <td nowrap="nowrap"><div align="left"><span class="Estilo8">
                <a href="<?php echo $LINCK;?>N_cotizacion=<?php echo $row_cotizacion['N_cotizacion']; ?>&cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "6"; ?>" target="new" style="text-decoration:none; color:#000000">
                <?php 
                $nit=$row_cotizacion['Str_nit'];
                $numins = $conexion->llenarCampos("cliente", "WHERE nit_c='$nit'", " ", "nombre_c"); 

                if($numins >='1')
                { 
                  echo $numins['nombre_c'];
                }
                ?>  
                 </a>
              </span>
             </div>
          </td>
              <td width="100"><div align="left"><a href="<?php echo $LINCK;?>N_cotizacion=<?php echo $row_cotizacion['N_cotizacion']; ?>&cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "6"; ?>" target="new" style="text-decoration:none; color:#000000"><span class="Estilo8"><?php echo $row_cotizacion['Str_nit']; ?> </span></div>
              </td>
              <td nowrap="nowrap"><span class="Estilo8">
                <a href="<?php echo $LINCK;?>N_cotizacion=<?php echo $row_cotizacion['N_cotizacion']; ?>&cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "6"; ?>" target="new" style="text-decoration:none; color:#000000">
                  <?php echo $row_cotizacion['fecha']; ?>
                    </a>
                 </span>
              </td>
              <td width="54">
                <div align="center" class="Estilo8">
                  <a href="<?php echo $LINCK;?>N_cotizacion=<?php echo $row_cotizacion['N_cotizacion']; ?>&cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "6"; ?>" target="new" style="text-decoration:none; color:#000000">
                <?php 
                $cotiz=$row_cotizacion['N_cotizacion'];
                                
                $estado = $conexion->llenarCampos($basdatos, "WHERE N_cotizacion='$cotiz'", " ", "B_estado"); 

                if($estado >='1')
                { 
                   switch ($estado['B_estado']) {
                    case "0":
                    $estado ="Pendiente"; 
                    break;
                    case "1":
                    $estado ="Aceptada"; 
                    break;
                    case "2":
                    $estado ="Rechazada"; 
                    break;
                    case "3":
                    $estado ="Obsoleta"; 
                    break;  
                  }

                   echo $estado ;
                }
                ?>
                 </a>
               </div>
              </td>
               <td width="51"><div align="center"><a href="<?php echo $LINCK;?>N_cotizacion=<?php echo $row_cotizacion['N_cotizacion']; ?>&cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "6"; ?>" target="new" style="text-decoration:none; color:#000000"><img src="images/hoja.gif" alt="Ver" width="18" height="18" border="0" /></a></div>
               </td>
              <td width="54"><div align="center">
                <?php if($row_cotizacion['fecha'] == date("Y-m-d"))  { 

                ?> <a href="javascript:eliminar1('id_cotiz',<?php echo $row_cotizacion['id_cotiz'];?>,'cotizacion_copia.php')"><img src="images/por.gif" alt="ELIMINAR COTIZ." title="ELIMINAR COTIZ." border="0" style="cursor:hand;"/></a>
              <?php }else{ ?>
                <a href="javascript:update1('id_cotiz_up',<?php echo $row_cotizacion['id_cotiz'];?>,'cotizacion_copia.php')"><img src="images/por.gif" alt="UPDATE ESTADO COTIZ." title="UPDATE ESTADO COTIZ." border="0" style="cursor:hand;"/></a><?php }?>  
             </div></td>
           </tr>
         <?php }  ?>
       </table>
       <table border="0" width="50%" align="center">
        <tr>
          <td width="23%" id="dato2"><?php if ($pageNum_cotizacion > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_cotizacion=%d%s", $currentPage, 0, $queryString_cotizacion); ?>">Primero</a>
          <?php } // Show if not first page ?>
        </td>
        <td width="31%" id="dato2"><?php if ($pageNum_cotizacion > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_cotizacion=%d%s", $currentPage, max(0, $pageNum_cotizacion - 1), $queryString_cotizacion); ?>">Anterior</a>
        <?php } // Show if not first page ?>
      </td>
      <td width="23%" id="dato2"><?php if ($pageNum_cotizacion < $totalPages_cotizacion) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_cotizacion=%d%s", $currentPage, min($totalPages_cotizacion, $pageNum_cotizacion + 1), $queryString_cotizacion); ?>">Siguiente</a>
      <?php } // Show if not last page ?>
    </td>
    <td width="23%" id="dato2"><?php if ($pageNum_cotizacion < $totalPages_cotizacion) { // Show if not last page ?>
      <a href="<?php printf("%s?pageNum_cotizacion=%d%s", $currentPage, $totalPages_cotizacion, $queryString_cotizacion); ?>">&Uacute;ltimo</a>
    <?php } // Show if not last page ?>
  </td>
</tr>
</table>
<?php echo $conexion->header('footer'); ?>
</body>
</html>
<?php
mysql_free_result($row_usuario);

mysql_free_result($cotizacion);
?>
