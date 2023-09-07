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
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

//obtenemos el archivo .csv
$tipo = $_FILES['archivo']['type'];
 
$tamanio = $_FILES['archivo']['size'];
 
$archivotmp = $_FILES['archivo']['tmp_name'];
 
//cargamos el archivo
 
$lineas = file($archivotmp);
 
//inicializamos variable a 0, esto nos ayudará a indicarle que no lea la primera línea

//Recorremos el bucle para leer línea por línea
$fecha=$_POST['Fecha'];
$tipomp=$_POST['tipomp'];
$FechaModif = date("Y-m-d");
foreach ($lineas as $linea_num => $linea)
{ 
   //abrimos bucle
   /*si es diferente a 0 significa que no se encuentra en la primera línea 
   (con los títulos de las columnas) y por lo tanto puede leerla*/
   for($i=0;$i <=$linea;$i++) 
   { 
       //abrimos condición, solo entrará en la condición a partir de la segunda pasada del bucle.
       /* La funcion explode nos ayuda a delimitar los campos, por lo tanto irá 
       leyendo hasta que encuentre un ; */
       $datos = explode(";",$linea);
       //Almacenamos los datos que vamos leyendo en una variable
       /*$nombre = trim($datos[0]);
       $edad = trim($datos[1]);
       $profesion = trim($datos[2]);*/
	   
	   //CREAMOS UN HISTORIAL
	   /*mysql_query("INSERT INTO TblInventarioHistory (Fecha, Cod_ref, Codigo, SaldoInicial, Entrada, Salida,  CostoUnd, Acep, Tipo, Responsable, Modifico)
	SELECT Fecha, Cod_ref, Codigo, SaldoInicial, Entrada, Salida,  CostoUnd, Acep, Tipo, Responsable, Modifico FROM TblInventarioListado ORDER BY idInv ASC");*/
	   //CONSULTO SI EXISTE
	   $sqlvi="SELECT Codigo FROM TblInventarioListado WHERE Codigo = '$datos[0]'";
	   $resultvi= mysql_query($sqlvi);
	   $numvi= mysql_num_rows($resultvi);
	   
	   $codigo = explode("-",$datos[0]);
	   $cod_ref = $codigo[0];
		
	   if($numvi >='1')
	   { 
        //UPDATE en base de datos la línea que existe
	    mysql_query("UPDATE TblInventarioListado SET Fecha='$fecha', Cod_ref='$cod_ref', Codigo='$datos[0]', SaldoInicial='$datos[4]', Entrada = '$datos[5]', Salida= '$datos[6]', Final= '$datos[7]', Acep='0', Tipo='$tipomp', Modifico='Almacen', FechaMOdif='$FechaModif' WHERE Codigo = '$datos[0]'");  
 	   }else{
        //INSERT en base de datos la línea leida que no existe
        mysql_query("INSERT INTO TblInventarioListado(Fecha, Cod_ref, Codigo, SaldoInicial, Entrada, Salida, Final, Acep, Tipo, Responsable) VALUES ('$fecha','$cod_ref','$datos[0]','$datos[4]','$datos[5]','$datos[6]','$datos[7]','0','$tipomp','Almacen')");
 	   }//cerramos condición
   }//for cerramos bucle
} 


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
 
$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = $_SESSION['MM_Username'];
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = %s", GetSQLValueString($colname_usuario, "text"));
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
<link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">
<script type="text/javascript" src="js/formato.js"></script> 
</head>
<body>
<div align="center">
<table align="center" id="tabla"><tr><td align="center">
<div> 
<b class="spiffy"> 
<b class="spiffy1"><b></b></b>
<b class="spiffy2"><b></b></b>
<b class="spiffy3"></b>
<b class="spiffy4"></b>
<b class="spiffy5"></b></b>
<div class="spiffy_content">
<table id="tabla1"><tr>
<td colspan="2" align="center">
<img src="images/cabecera.jpg"></td>
</tr>
<tr>
<td id="nombreusuario"><?php echo $row_usuario['nombre_usuario']; ?></td>
<td id="cabezamenu"><ul id="menuhorizontal">
           <li><a href="<?php echo $logoutAction ?>" target="_top">CERRAR SESION</a></li>
		   <li><a href="menu.php">MENU PRINCIPAL</a></li>
           <li><a href="inventario.php">INVENTARIO</a></li>
</ul></td>
</tr>
  <tr>
    <td colspan="2" align="center" id="linea1">
      <table border="0" id="tabla1">      
        <tr>
          <td id="fuente2">
            <form action="inventario_importar.php"  name="form2" enctype="multipart/form-data" method="post"> 
                <table>
                  <tr>
                    <td id="fuente2">&nbsp;</td>
                    <td id="fuente2">&nbsp;</td>
                    </tr>
                    <tr>
                    <td colspan="2" id="dato1">Nota importante: al cargar el archivo de excel verifique que tenga solamente las columnas codigo, inicial, entradas, salidas, final y sin encabezado; de lo contrario no cargara el archivo.</td>
                    </tr>
                    <tr>
                    <td id="fuente2">&nbsp;</td>
                    <td id="fuente2">&nbsp;</td>
                    </tr>
                  <tr>
                    <td id="fuente1">Ingrese fecha del Inventario</td>
                    <td id="fuente1">Archivo Excel</td>
                  </tr> 
                  <tr>
                    <td id="detalle1"><input name="Fecha" type="date" value="" required="required"/></td>
                    <td id="detalle2"><input id="archivo" accept=".csv" name="archivo" type="file" />
                      <input name="MAX_FILE_SIZE" type="hidden" value="90000" /></td>
                  </tr>
                  <tr>
                    <td id="fuente3">&nbsp;</td>
                    <td id="fuente3">&nbsp;</td>
                  </tr>
                  <tr>
                    <td id="fuente3">&nbsp;</td>
                    <td id="fuente3">&nbsp;</td>
                  </tr>
                  <tr>
                    <td id="fuente3"><input name="button" id="button" type="button" value="Guardar Historial del Inventario" onclick="return enviaForm('inserts.php?insert=<?php echo '0'; ?>');"/></td>
                    <td id="fuente2"><input type="hidden" name="tipomp" id="tipomp"  value="<?php echo $_GET['tipo']; ?>"/>                      <input name="enviar" type="submit" value="Importar" /></td>
                  </tr>                         
                  </table>                
                </fieldset>
              </form></td>
          </tr>
        </table>
      </td>
  </tr>
</table
  ></div>
<b class="spiffy"> 
<b class="spiffy5"></b>
<b class="spiffy4"></b>
<b class="spiffy3"></b>
<b class="spiffy2"><b></b></b>
<b class="spiffy1"><b></b></b></b></div> 
</td></tr></table>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($bolsas_producidas);

?>
