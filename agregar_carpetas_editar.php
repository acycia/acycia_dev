<?php require_once('Connections/conexion1.php'); ?>
 
<?php
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
     
  }
  return $theValue;
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
//////////////////////////////////////////////////////////////////////////////
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
    //CREA CARPETA O DIRECTORIO
  /* is_dir() - Indica si el nombre de archivo es un directorio
     mkdir() - Crea un directorio
     unlink() - Borra un fichero*/
    $carpeta = 'C:\xampp\htdocs\acycia\dropboxacycia/'.$_POST['descripcion'];
    //echo($carpeta);exit();
    if(!file_exists($carpeta)){
      if(!mkdir($carpeta, 0777, true)) {
          die('Fallo al crear las carpetas...');
      }else{
      $insertSQL = sprintf("UPDATE  usuario_carpetas SET descripcion = %s WHERE id=%s",
                 GetSQLValueString($_POST['descripcion'], "text"),
                 GetSQLValueString($_GET['id'], "int"));

      mysql_select_db($database_conexion1, $conexion1);
      $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());
        
      }


    }
    
  $insertGoTo = "agregar_carpetas_editar.php" . $_POST['id'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$id=$_GET['id'];
 
mysql_select_db($database_conexion1, $conexion1);
$query_usuario =  "SELECT * FROM usuario_carpetas  WHERE id=$id";
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

mysql_select_db($database_conexion1, $conexion1);
$query_ver_nuevo = "SELECT * FROM usuario_carpetas ORDER BY id DESC";
$ver_nuevo = mysql_query($query_ver_nuevo, $conexion1) or die(mysql_error());
$row_ver_nuevo = mysql_fetch_assoc($ver_nuevo);
$totalRows_ver_nuevo = mysql_num_rows($ver_nuevo);

mysql_select_db($database_conexion1, $conexion1);
$query_carpeta = "SELECT * FROM usuario_carpetas ORDER BY id DESC";
$carpeta = mysql_query($query_carpeta, $conexion1) or die(mysql_error());
$row_carpeta = mysql_fetch_assoc($carpeta);
$totalRows_carpeta = mysql_num_rows($carpeta);
 
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<link href="css/desplegable.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/usuario.js"></script>
<script type="text/javascript" src="js/formato.js"></script>
</head>
<body>
<div align="center">
<table id="tabla1">
  <tr>
   <td align="center">
     <div class="row-fluid">
         <div class="span8 offset2"> <!--span8 offset2   esto da el tamaño pequeño -->
             <div class="panel panel-primary">
                 <div class="panel-heading">AGREGAR CARPETAS</div>
                 <div class="panel-body">
                  <br>
                  <div id="cabezamenu">
                  <ul id="menuhorizontal">
                  <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                  <li><a href="menu.php">MENU PRINCIPAL</a></li> 
                  <li><a href="/acycia/dropboxacycia/">VER ARCHIVOS</a></li>
                  <li><a href="/acycia/agregar_carpetas.php">AGREGAR CARPETAS</a></li>
                  <li><a href="/acycia/adjuntardropbox.php">ADJUNTAR ARCHIVOS</a></li>
                  </ul>
                  </div><br><br><br>
                     <form action="<?php echo $logoutAction ?>" method="post" name="form1" onSubmit="MM_validateForm('descripcion','','R' );return document.MM_returnValue">
                      <STRONG>AGREGUE LA DESCRIPCION DE LA CARPETA</STRONG>
                      <input name="id_usuario" type="hidden" value="<?php echo $row_usuario['id']; ?>">
                     <input type="text" name="descripcion" id="descripcion" value="<?php echo $row_usuario['descripcion']; ?>" size="50" required="required" >   

                     <div class="panel-footer">
                          <input type="submit" value="Update Carpeta">
                     </div>
                     <input type="hidden" name="MM_insert" value="form1">
                     </form>
                 </div>
                 <br><div align="center">
                   <table id="tabla2">
                     <h3>Carpetas Ingresadas</h3>
                     <?php do { ?>
                       <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF" bordercolor="#ACCFE8"> 
                       <td class="Estilo4">
                       <a href="agregar_carpetas_editar.php?id=<?php echo $row_carpeta['id']; ?>" target="_top" style="text-decoration:none; color:#000000"> <?php echo $row_carpeta['descripcion']; ?> </a> </td>
                       </tr>
                       <?php } while ($row_carpeta = mysql_fetch_assoc($carpeta)); ?>
                   </table>
                   </div>
             </div>
         </div>
     </div>
   </td>
</tr>
</table>
</div> 


</body>
</html>


 
<?php
mysql_free_result($usuario_nuevo);
 
?>
