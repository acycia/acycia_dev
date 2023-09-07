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

$currentPage = $_SERVER["PHP_SELF"];

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

mysql_select_db($database_conexion1, $conexion1);
$id_ref = $_GET['id_ref'];
$id_c = $_GET['id_c'];
//$fecha = $_GET['fecha'];
//Filtra todos vacios
if($id_ref == '0' && $id_c == '0')
{
$query_cotizacion = "SELECT * FROM Tbl_referencia GROUP BY Tbl_referencia.cod_ref ORDER by id_ref desc";
}
//Filtra cotizacion lleno
if($id_ref != '0')
{
$query_cotizacion = "SELECT * FROM Tbl_referencia WHERE id_ref='$id_ref' GROUP BY Tbl_referencia.cod_ref ORDER by id_ref desc";
}
//Filtra cliente lleno
if($id_c != '0' && $id_ref == '0')
{
$query_cotizacion = "SELECT * FROM Tbl_cliente_referencia,Tbl_referencia WHERE Tbl_cliente_referencia.Str_nit='$id_c' AND Tbl_cliente_referencia.N_referencia=Tbl_referencia.cod_ref GROUP BY Tbl_referencia.cod_ref ORDER BY Tbl_referencia.cod_ref DESC";
}
//Filtra todos llenos
if($id_ref != '0' && $id_c != '0')
{
$query_cotizacion = "SELECT * FROM Tbl_cliente_referencia,Tbl_referencia WHERE Tbl_cliente_referencia.Str_nit='$id_c' AND  Tbl_cliente_referencia.N_referencia='$id_ref' AND Tbl_cliente_referencia.N_referencia=Tbl_referencia.cod_ref GROUP BY Tbl_referencia.cod_ref ORDER BY Tbl_referencia.cod_ref DESC";
}
$query_limit_cotizacion = sprintf("%s LIMIT %d, %d", $query_cotizacion, $startRow_cotizacion, $maxRows_cotizacion);
$cotizacion = mysql_query($query_limit_cotizacion, $conexion1) or die(mysql_error());
$row_cotizacion = mysql_fetch_assoc($cotizacion);

if (isset($_GET['totalRows_cotizacion'])) {
  $totalRows_cotizacion = $_GET['totalRows_cotizacion'];
} else {
  $all_cotizacion = mysql_query($query_cotizacion);
  $totalRows_cotizacion = mysql_num_rows($all_cotizacion);
}
$totalPages_cotizacion = ceil($totalRows_cotizacion/$maxRows_cotizacion)-1;

$row_cliente = $conexion->llenaSelect('cliente',"","ORDER BY nombre_c ASC");  

$row_numero = $conexion->llenaSelect('tbl_referencia',"WHERE estado_ref='1'","order by CONVERT(cod_ref, SIGNED INTEGER)  desc"); 

$queryString_cotizacion = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_cotizacion") == false && 
        stristr($param, "totalRows_cotizacion") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_cotizacion = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_cotizacion = sprintf("&totalRows_cotizacion=%d%s", $totalRows_cotizacion, $queryString_cotizacion);

session_start();
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0014)about:internet -->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/listado.js"></script>

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
<?php echo $conexion->header('listas'); ?>
              <table class="table table-bordered table-sm">
                <tr align="center">
                  <td> 
                    <td colspan="2" align="center" id="linea1">
                     <form action="referencia_copia2.php" method="get" name="consulta" >
                      <table id="tabla1">
                        <tr>
                          <td nowrap="nowrap" id="codigo" width="25%">CODIGO : R1 - F03</td>
                          <td nowrap="nowrap" id="titulo2" width="50%">LISTADO DE REFERENCIAS</td>
                          <td width="25%" colspan="2" nowrap="nowrap" id="codigo">VERSION : 2</td>
                        </tr>
                        <td colspan="2" id="dato2">
                            <select class="busqueda selectsMini" name="id_ref" id="id_ref">
                             <option value="0"<?php if (!(strcmp(0, $_GET['id_ref']))) {echo "selected=\"selected\"";} ?>>Referencia</option>
                             <?php  foreach($row_numero as $row_numero ) { ?>
                             <option value="<?php echo $row_numero['id_ref']?>"<?php if (!(strcmp($row_numero['id_ref'], $_GET['id_ref']))) {echo "selected=\"selected\"";} ?>><?php echo $row_numero['cod_ref']?></option>
                            <?php } ?>
                          </select>

                          <select class="busqueda selectsMini" name="id_c" id="id_c" style="width:250px">
                           <option value="0" <?php if (!(strcmp(0, $_GET['id_c']))) {echo "selected=\"selected\"";} ?>>Seleccione el Cliente</option>
                           <?php  foreach($row_cliente as $row_cliente ) { ?>
                            <option value="<?php echo $row_cliente['nit_c']?>"<?php if (!(strcmp($row_cliente['nit_c'], $_GET['id_c']))) {echo "selected=\"selected\"";} ?>><?php $cad =($row_cliente['nombre_c']);echo $cad;?></option>
                          <?php } ?>
                        </select> 
                     
                    <input type="submit" class="botonGMini" name="Submit" value="FILTRO" onclick="if(consulta.id_ref.value=='0' && consulta.id_c.value=='0') { alert('DEBE SELECCIONAR UNA OPCION'); }"/>    
                    <a href="cotizacion_bolsa.php"></a></td>
                    <td id="dato1"><input class="botonDel" type="button" value="Excel_Ref" onclick="window.location = 'referencia_excel.php?id_ref=<?php echo $_GET['id_ref']; ?>&id_c=<?php echo $_GET['id_c']; ?>'" /></td>
                    <td id="dato1"><input class="botonDel" type="button" value="Excel_RefCliente" onclick="window.location = 'referencia_excel_cliente.php?id_ref=<?php echo '0'; ?>&amp;id_c=<?php echo '0'; ?>&amp;ref_clientes=<?php echo '1'; ?>'" /></td>
                  </tr>
                </table>
              </form>
              <form action="delete_listado.php" method="get" name="seleccion">
                <table class="table table-bordered table-sm">
                  <tr>
                    <td colspan="2" id="dato1"><input name="Input" type="submit" value="Delete"/>
                      <input name="borrado" type="hidden" id="borrado" value="50" /></td>
                      <td colspan="3" nowrap="nowrap"><?php $id=$_GET['id']; 
                      if($id >= '1') { ?>
                        <div id="acceso1"> <?php echo "ELIMINACION COMPLETA"; ?> </div>
                      <?php }
                      if($id == '0') { ?>
                        <div id="numero1"> <?php echo "No se ha seleccionado ningún registro para eliminar"; ?> </div>
                        <?php }?></td>
                        <td colspan="8" id="dato3"><?php if($row_usuario['tipo_usuario'] != '11') { ?>
                          <a href="cotizacion_general_menu.php"><img src="images/mas.gif" alt="ADD COTIZACION" title="ADD COTIZACION" border="0" style="cursor:hand;"/></a><a href="cotizaciones_clientes.php"></a><?php } ?>
                          <a href="referencias.php" target="_top"><img src="images/a.gif" alt="REF'S ACTIVAS" title="REF'S ACTIVAS"border="0" style="cursor:hand;"/></a><a href="referencias_inactivas.php" target="_top"><img src="images/i.gif" alt="REF'S INACTIVAS"title="REF'S INACTIVAS" border="0" style="cursor:hand;"/></a><a href="cotizacion_general_menu.php"></a><a href="referencia_copia.php"><img src="images/ciclo1.gif" alt="RESTAURAR" title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
                        </tr>  
                        <tr id="tr1">
                          <td id="titulo4"><input name="chulo1" type="checkbox" onClick="if(seleccion.chulo1.checked) { seleccionar_todo() } else{ deseleccionar_todo() } "/></td>
                          <td nowrap="nowrap" id="titulo4">N° REF</td>
                          <td nowrap="nowrap" id="titulo4">COTIZ</td>
<!--    <?php if($id_ref!='0'&&$id_c=='0' ) { ?> 
    <td nowrap="nowrap" id="titulo4">CLIENTE</td>
    <?php }?>-->
                            <td nowrap="nowrap" id="titulo4">TIPO</td>
                            <td colspan="2" nowrap="nowrap" id="titulo4">Material</td>
                            <td nowrap="nowrap" id="titulo4">Adhesivo</td>
                            <td nowrap="nowrap" id="titulo4">Ancho</td>
                            <td nowrap="nowrap" id="titulo4">Largo</td>
                            <td nowrap="nowrap" id="titulo4">Solapa</td>
                            <td nowrap="nowrap" id="titulo4">Bolsillo</td>
                            <td nowrap="nowrap" id="titulo4">Calibre</td>
                            <td nowrap="nowrap" id="titulo4">Impuesto $</td>
                            <td nowrap="nowrap" id="titulo4">Peso M.</td>
                          </tr>
                          <?php do { ?>
                            <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
                              <td id="dato2"><?php if($row_cotizacion['id_ref']!='') { ?><input name="ref[]" type="checkbox" value="<?php echo $row_cotizacion['id_ref']; ?>" /><?php } ?></td>
                              <td id="dato2"><a href="control_tablas.php?cod_ref= <?php echo $row_cotizacion['cod_ref']; ?>&n_cotiz= <?php echo $row_cotizacion['n_cotiz_ref']; ?>&id_ref= <?php echo $row_cotizacion['id_ref']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "5"; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_cotizacion['cod_ref']; ?></a></td>
                              <td id="dato2"><a href="control_tablas.php?cod_ref= <?php echo $row_cotizacion['cod_ref']; ?>&n_cotiz= <?php echo $row_cotizacion['n_cotiz_ref']; ?>&id_ref= <?php echo $row_cotizacion['id_ref']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "5"; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_cotizacion['n_cotiz_ref']; ?></a></td>                        

                        <!--<?php if($n_cotiz!='0'&&$id_c=='0') { ?>      
                        <td id="dato2"><a href="control_tablas.php?cod_ref= <?php echo $row_cotizacion['cod_ref']; ?>&n_cotiz= <?php echo $row_cotizacion['n_cotiz_ref']; ?>&id_ref= <?php echo $row_cotizacion['id_ref']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "5"; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_cliente2['nombre_c']; ?></a></td>      
                        <?php }?>-->                        

                        <td id="dato2"><a href="control_tablas.php?cod_ref= <?php echo $row_cotizacion['cod_ref']; ?>&n_cotiz= <?php echo $row_cotizacion['n_cotiz_ref']; ?>&id_ref= <?php echo $row_cotizacion['id_ref']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "5"; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_cotizacion['tipo_bolsa_ref']; ?></a></td>
                        <td colspan="2" id="dato2"><a href="control_tablas.php?cod_ref= <?php echo $row_cotizacion['cod_ref']; ?>&amp;n_cotiz= <?php echo $row_cotizacion['n_cotiz_ref']; ?>&amp;id_ref= <?php echo $row_cotizacion['id_ref']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>&amp;case=<?php echo "5"; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_cotizacion['material_ref']; ?></a></td>
                        <td id="dato2"><a href="control_tablas.php?cod_ref= <?php echo $row_cotizacion['cod_ref']; ?>&amp;n_cotiz= <?php echo $row_cotizacion['n_cotiz_ref']; ?>&amp;id_ref= <?php echo $row_cotizacion['id_ref']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>&amp;case=<?php echo "5"; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_cotizacion['adhesivo_ref']; ?></a></td>
                        <td id="dato2"><a href="control_tablas.php?cod_ref= <?php echo $row_cotizacion['cod_ref']; ?>&amp;n_cotiz= <?php echo $row_cotizacion['n_cotiz_ref']; ?>&amp;id_ref= <?php echo $row_cotizacion['id_ref']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>&amp;case=<?php echo "5"; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_cotizacion['ancho_ref']; ?></a></td>
                        <td id="dato2"><a href="control_tablas.php?cod_ref= <?php echo $row_cotizacion['cod_ref']; ?>&amp;n_cotiz= <?php echo $row_cotizacion['n_cotiz_ref']; ?>&amp;id_ref= <?php echo $row_cotizacion['id_ref']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>&amp;case=<?php echo "5"; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_cotizacion['largo_ref']; ?></a></td>
                        <td id="dato2"><a href="control_tablas.php?cod_ref= <?php echo $row_cotizacion['cod_ref']; ?>&amp;n_cotiz= <?php echo $row_cotizacion['n_cotiz_ref']; ?>&amp;id_ref= <?php echo $row_cotizacion['id_ref']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>&amp;case=<?php echo "5"; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_cotizacion['solapa_ref']; ?></a></td>
                        <td id="dato2"><a href="control_tablas.php?cod_ref= <?php echo $row_cotizacion['cod_ref']; ?>&amp;n_cotiz= <?php echo $row_cotizacion['n_cotiz_ref']; ?>&amp;id_ref= <?php echo $row_cotizacion['id_ref']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>&amp;case=<?php echo "5"; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_cotizacion['bolsillo_guia_ref']; ?></a></td>
                        <td id="dato2"><a href="control_tablas.php?cod_ref= <?php echo $row_cotizacion['cod_ref']; ?>&amp;n_cotiz= <?php echo $row_cotizacion['n_cotiz_ref']; ?>&amp;id_ref= <?php echo $row_cotizacion['id_ref']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>&amp;case=<?php echo "5"; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_cotizacion['calibre_ref']; ?></a></td>
                        <td id="dato2"><a href="control_tablas.php?cod_ref= <?php echo $row_cotizacion['cod_ref']; ?>&amp;n_cotiz= <?php echo $row_cotizacion['n_cotiz_ref']; ?>&amp;id_ref= <?php echo $row_cotizacion['id_ref']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>&amp;case=<?php echo "5"; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_cotizacion['valor_impuesto']; ?></a></td>
                        <td id="dato1"><a href="control_tablas.php?cod_ref= <?php echo $row_cotizacion['cod_ref']; ?>&amp;n_cotiz= <?php echo $row_cotizacion['n_cotiz_ref']; ?>&amp;id_ref= <?php echo $row_cotizacion['id_ref']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>&amp;case=<?php echo "5"; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_cotizacion['peso_millar_ref']; ?></a></td>
                        </tr>
                        <?php } while ($row_cotizacion = mysql_fetch_assoc($cotizacion)); ?>
                        </table>
                        <table id="tabla1">
                          <tr>
                            <td id="dato2" width="25%"><?php if ($pageNum_cotizacion > 0) { // Show if not first page ?>
                              <a href="<?php printf("%s?pageNum_cotizacion=%d%s", $currentPage, 0, $queryString_cotizacion); ?>">Primero</a>
                            <?php } // Show if not first page ?>
                          </td>
                          <td id="dato2" width="25%"><?php if ($pageNum_cotizacion > 0) { // Show if not first page ?>
                            <a href="<?php printf("%s?pageNum_cotizacion=%d%s", $currentPage, max(0, $pageNum_cotizacion - 1), $queryString_cotizacion); ?>">Anterior</a>
                          <?php } // Show if not first page ?>
                        </td>
                        <td id="dato2" width="25%"><?php if ($pageNum_cotizacion < $totalPages_cotizacion) { // Show if not last page ?>
                          <a href="<?php printf("%s?pageNum_cotizacion=%d%s", $currentPage, min($totalPages_cotizacion, $pageNum_cotizacion + 1), $queryString_cotizacion); ?>">Siguiente</a>
                        <?php } // Show if not last page ?>
                        </td>
                        <td id="dato2" width="25%"><?php if ($pageNum_cotizacion < $totalPages_cotizacion) { // Show if not last page ?>
                          <a href="<?php printf("%s?pageNum_cotizacion=%d%s", $currentPage, $totalPages_cotizacion, $queryString_cotizacion); ?>">&Uacute;ltimo</a>
                        <?php } // Show if not last page ?>
                        </td>
                        </tr>
                      </table>
                    </form>
                  </td>
                </div>
              </td>
            </tr>
          </table>
        <?php echo $conexion->header('footer'); ?>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($cotizacion);

mysql_free_result($numero);

?>