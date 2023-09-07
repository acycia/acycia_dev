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

  /*Cuando un visitante se registra en este sitio, la variable de sesión MM_Username igual a su nombre de usuario.
   / / Por lo tanto, sabemos que un usuario no se registra en el caso de que la variable de sesión está en blanco.*/
  if (!empty($UserName)) { 
   //Además de estar conectado, se puede restringir el acceso sólo a ciertos usuarios basados ??en una identificación establecida al iniciar la sesión.
     // Analizar las cadenas en las matrices.
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    //O bien, puede restringir el acceso sólo a determinados usuarios en base a su nombre de usuario. 
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

//AQUI EMPIEZA EL CODIGO PARA EVALUAR LOS TRES FILTROS ENVIADOS POR GET
$maxRows_cotizacion = 20;
$pageNum_cotizacion = 0;
if (isset($_GET['pageNum_cotizacion'])) {
  $pageNum_cotizacion = $_GET['pageNum_cotizacion'];
}
$startRow_cotizacion = $pageNum_cotizacion * $maxRows_cotizacion;

mysql_select_db($database_conexion1, $conexion1);
$estado_c = $_GET['estado_c'];
$tipo_c = $_GET['tipo_c'];
$revisado_c = $_GET['revisado_c'];
/*$n_cotiz = $_GET['estado_c'];
$id_c = $_GET['tipo_c'];
$fecha = $_GET['revisado_c'];*/
//Filtra todos vacios
if($estado_c == '0' && $tipo_c == '0' && $revisado_c == '0')
{
$query_cotizacion = "SELECT * FROM cliente ORDER BY id_c DESC";
}
//Filtra estado lleno
if($estado_c != '0' && $tipo_c == '0' && $revisado_c == '0')
{
$query_cotizacion = "SELECT * FROM cliente WHERE estado_c='$estado_c' ORDER BY estado_c DESC";
}
//Filtra tipo_c lleno
if($tipo_c != '0' && $estado_c == '0' && $revisado_c == '0')
{
$query_cotizacion = "SELECT * FROM cliente WHERE id_c='$id_c' ORDER BY tipo_c DESC";
}
//Filtra fecha lleno
if($revisado != '0' && $tipo_c == '0' && $estado_c == '0'  )
{
$query_cotizacion = "SELECT * FROM cliente WHERE revisado_c = '$revisado_c' ORDER BY revisado_c DESC";
}
/*//Filtra fecha y cliente lleno
if($fecha != '0' && $id_c != '0' && $n_cotiz == '0'  )
{
$fecha1 = "$fecha-01-01";
$fecha2 = $fecha + 1;
$fecha2 = "$fecha2-01-01";
$query_cotizacion = "SELECT * FROM cotizacion WHERE id_c_cotiz='$id_c' and fecha_cotiz >= '$fecha1' and fecha_cotiz < '$fecha2' ORDER BY n_cotiz DESC";
}
//Filtra cotizacion y fecha lleno
if($n_cotiz != '0' && $fecha != '0' && $id_c == '0')
{
$fecha1 = "$fecha-01-01";
$fecha2 = $fecha + 1;
$fecha2 = "$fecha2-01-01";
$query_cotizacion = "SELECT * FROM cotizacion WHERE n_cotiz='$n_cotiz' and fecha_cotiz >= '$fecha1' and fecha_cotiz < '$fecha2' ORDER BY n_cotiz DESC";
}
//Filtra cotizacion y cliente lleno
if($n_cotiz != '0' && $id_c != '0' && $fecha == '0')
{
$query_cotizacion = "SELECT * FROM cotizacion WHERE n_cotiz='$n_cotiz' and id_c_cotiz='$id_c' ORDER BY n_cotiz DESC";
}
//Filtra Todos llenos
if($n_cotiz != '0' && $id_c != '0' && $fecha != '0')
{
$fecha1 = "$fecha-01-01";
$fecha2 = $fecha + 1;
$fecha2 = "$fecha2-01-01";
$query_cotizacion = "SELECT * FROM cotizacion WHERE n_cotiz='$n_cotiz' and id_c_cotiz='$id_c' and fecha_cotiz >= '$fecha1' and fecha_cotiz < '$fecha2' ORDER BY n_cotiz DESC";
}*/
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

//AQUITERMINA LA EVALUACION DE LOS TRES FILTROS
//CONSULTA AGREGADA PARA EL FILTRO POR RAZON SOCIAL
mysql_select_db($database_conexion1, $conexion1);
$query_cliente = "SELECT * FROM cliente ORDER BY nombre_c ASC";
$cliente = mysql_query($query_cliente, $conexion1) or die(mysql_error());
$row_cliente = mysql_fetch_assoc($cliente);
$totalRows_cliente = mysql_num_rows($cliente);
//CONSULTA AGREGADA PARA EL FILTRO POR ESTADO
mysql_select_db($database_conexion1, $conexion1);
$query_numero = "select distinct estado_c from cliente where estado_c is not null";
$numero = mysql_query($query_numero, $conexion1) or die(mysql_error());
$row_numero = mysql_fetch_assoc($numero);
$totalRows_numero = mysql_num_rows($numero);
//CONSULTA AGREGADA PARA EL FILTRO POR TIPO CLIENTE
mysql_select_db($database_conexion1, $conexion1);
$query_t_cliente = "select distinct tipo_c from cliente where tipo_c is not null";
$t_cliente = mysql_query($query_t_cliente, $conexion1) or die(mysql_error());
$row_t_cliente = mysql_fetch_assoc($t_cliente);
$totalRows_t_cliente = mysql_num_rows($t_cliente);
//CONSULTA AGREGADA PARA EL FILTRO POR ASESOR
mysql_select_db($database_conexion1, $conexion1);
$query_ano = "select distinct revisado_c from cliente where revisado_c is not null";
$ano = mysql_query($query_ano, $conexion1) or die(mysql_error());
$row_ano = mysql_fetch_assoc($ano);
$totalRows_ano = mysql_num_rows($ano);

?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>SISADGE AC &amp; CIA</title>
<link rel="StyleSheet" href="css/general.css" type="text/css">
</head>
<body oncontextmenu="return false">
  <tr><td>  
<table id="tabla_formato">
    <tr>
      <td width="30%" id="codigo_formato">CODIGO: R1 - F02</td>
      <td width="45%" id="titulo_formato">LISTADO MAESTRO DE CLIENTES </td>
      <td width="25%" id="codigo_formato">VERSION: 1 </td>
    </tr>
    <tr>
      <td height="28"width="45%" id="subtitulo3" >
    <FORM > 
    <SELECT NAME="lista" id_c='id_c'>
    <option value="0">Seleccione una Razon Social</option>
     <?php do { ?>
    <OPTION VALUE="perfil_cliente_vista.php?id_c= <?php echo $row_cliente['id_c']; ?>&tipo_usuario=<?php echo $row_usuario['tipo_usuario']; ?>" target="_blank" style=" text-decoration:none; color:#000000"><?php echo $row_cliente['nombre_c']; ?><?php } while ($row_cliente = mysql_fetch_assoc($cliente)); ?> 
    </option>
    </SELECT> 
    <INPUT TYPE=button VALUE="OK" 
    onClick="window.top.location.href=this.form.lista.options[this.form.lista.selectedIndex].value"> 
    <input name="campo" type="hidden" value=" " size="5" readonly><?php echo $totalRows_cliente;echo ' '?>Clientes
    </FORM>          
      </td> 
      
    
      <td id="subtitulo3">
	<form action="listado_clientes_filtro.php" method="get" name="consulta" >
	<table id="tabla1">
<tr>
  <td colspan="3" id="dato2"><select name="estado_c" id="estado_c">
    <option value="0" <?php if (!(strcmp(0, $_GET['estado_c']))) {echo "selected=\"selected\"";} ?>>Estado</option>
    <?php
do {  
?><option value="<?php echo $row_numero['estado_c']?>"<?php if (!(strcmp($row_numero['estado_c'], $_GET['estado_c']))) {echo "selected=\"selected\"";} ?>><?php echo $row_numero['estado_c']?></option>
    <?php
} while ($row_numero = mysql_fetch_assoc($estado_c));
  $rows = mysql_num_rows($estado_c);
  if($rows > 0) {
      mysql_data_seek($estado_c, 0);
	  $row_numero = mysql_fetch_assoc($estado_c);
  }
?>
    </select><select name="tipo_c" id="tipo_c">
      <option value="0" <?php if (!(strcmp(0, $_GET['tipo_c']))) {echo "selected=\"selected\"";} ?>>Tipo Cliente</option>
      <?php
do {  
?><option value="<?php echo $row_t_cliente['tipo_c']?>"<?php if (!(strcmp($row_t_cliente['tipo_c'], $_GET['tipo_c']))) {echo "selected=\"selected\"";} ?>><?php echo $row_t_cliente['tipo_c']?></option>
      <?php
} while ($row_t_cliente = mysql_fetch_assoc($tipo_c));
  $rows = mysql_num_rows($tipo_c);
  if($rows > 0) {
      mysql_data_seek($tipo_c, 0);
	  $row_t_cliente = mysql_fetch_assoc($tipo_c);
  }
?>
    </select><select name="revisado_c" id="revisado_c">
      <option value="0" <?php if (!(strcmp(0, $_GET['revisado_c']))) {echo "selected=\"selected\"";} ?>>Asesor</option>
      <?php
do {  
?><option value="<?php echo $row_ano['revisado_c']?>"<?php if (!(strcmp($row_ano['revisado_c'], $_GET['revisado_c']))) {echo "selected=\"selected\"";} ?>><?php echo $row_ano['revisado_c']?></option>
      <?php
} while ($row_ano = mysql_fetch_assoc($revisado_c));
  $rows = mysql_num_rows($revisado_c);
  if($rows > 0) {
      mysql_data_seek($revisado_c, 0);
	  $row_ano = mysql_fetch_assoc($revisado_c);
  }
?>
    </select><input type="submit" name="Submit" value="FILTRO" onClick="if(consulta.estado_c.value=='0' && consulta.tipo_c.value=='0' && consulta.revisado_c.value=='0') { alert('DEBE SELECCIONAR UNA OPCION'); }"/>    
    <a href="listado_clientes2.php"></a></td>
  </tr>
</table>
</form>      
      </td>        
    </tr>    
</table>
<table id="tabla_borde_top">
  <tr>
    <td height="16" class="Estilo1">RAZON SOCIAL</td>
    <td class="Estilo1">CONTACTO</td>
    <td class="Estilo1">DIRECCION</td>
    <td class="Estilo2">PAIS/CIUDAD</td>
    <td class="Estilo2">TELEFONO</td>
    <td class="Estilo2">FAX</td>
    <td class="Estilo5">REF</td>
  </tr>
</table>
<?php
mysql_select_db($database_conexion1, $conexion1);
$cadena = "SELECT * FROM cliente";
$toda_cliente = mysql_query($cadena, $conexion1) or die(mysql_error());
//$registros_toda_cliente = mysql_fetch_assoc($toda_cliente);
$totalRegistros_toda_cliente = mysql_num_rows($toda_cliente);

echo "Encontrados: $totalRegistros_toda_cliente";
while ($registro = mysql_fetch_array($toda_cliente)){
echo $registro['revisado_c']; 
}

?>
<body>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($numero);

mysql_free_result($cliente);

mysql_free_result($t_cliente);

mysql_free_result($ano);
?>
