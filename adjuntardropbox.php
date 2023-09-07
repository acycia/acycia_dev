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
?><?php
$colname_usuario_comercial = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario_comercial = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario_comercial = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario_comercial);
$usuario_comercial = mysql_query($query_usuario_comercial, $conexion1) or die(mysql_error());
$row_usuario_comercial = mysql_fetch_assoc($usuario_comercial);
$totalRows_usuario_comercial = mysql_num_rows($usuario_comercial);

mysql_select_db($database_conexion1, $conexion1);
$query_carpeta = "SELECT * FROM usuario_carpetas ORDER BY id DESC";
$carpeta = mysql_query($query_carpeta, $conexion1) or die(mysql_error());
$row_carpeta = mysql_fetch_assoc($carpeta);
$totalRows_carpeta = mysql_num_rows($carpeta);

session_start(); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title>SISADGE AC &amp; CIA</title>
  <link href="css/formato.css" rel="stylesheet" type="text/css" />
  <link href="css/desplegable.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="js/formato.js"></script>
  <style type="text/css">
    .boton_personalizado{
      text-decoration: none;
      padding: 10px;
      font-weight: 600;
      font-size: 20px;
      color: #ffffff;
      background-color: #1883ba;
      border-radius: 6px;
      border: 2px solid #0016b0;
    }
  </style>
</head>
<body>
  <div class="spiffy_content"> <!-- este define el fondo gris de lado a lado si se coloca dentro de tabla inicial solamente coloca borde gris -->
    <div align="center">
      <table id="tabla">
        <tr align="center">
          <td align="center">

            <div class="row-fluid">
              <div class="span8 offset2"> <!--span8 offset2   esto da el tamaño pequeño -->
                <div class="panel panel-primary">
                  <div class="panel-heading">ADJUNTAR ARCHIVO</div>
                  <div class="panel-body">

                    <table id="tabla1">
                      <tr>
                        <td align="center">
                          <img src="images/cabecera.jpg">
                        </td>
                      </tr>
                      <tr>
                        <td id="cabezamenu">
                          <ul id="menuhorizontal">
                            <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                            <li><a href="menu.php">MENU PRINCIPAL</a></li> 
                            <li><a href="/acycia/dropboxacycia/">VER ARCHIVOS</a></li>
                            <li><a href="/acycia/agregar_carpetas.php">AGREGAR CARPETAS</a></li>
                      </ul>
                      <div id="nombreusuario"><?php echo $row_usuario_comercial['nombre_usuario']; ?></div>
                    </td>
                  </tr>
                  <tr>
                    <td id="subtitulo">&nbsp;</td>
                  </tr>
                  <tr>
                    <td id="subtitulo">ADJUNTAR ARCHIVO</td>
                  </tr>
                  <tr>
                    <td id="numero2">Recuerde que el archivo debe ser de extensi&oacute;n .xlsx, doc, pdf, gif, jpg, png
                    </td>
                  </tr>
                  <tr>
                    <td id="fuente2">&nbsp;</td>
                  </tr>  
                  <tr>
                    <td align="center">
                      <form action="adjuntardropbox2.php" method="post" enctype="multipart/form-data">
                        <div align="center">
                          <table width="50%">
                            <tr id="tr1">
                              <td width="25%" id="titulo4">ARCHIVO </td>
                              <td width="25%" id="titulo4"> CLICK</td>
                            </tr>
                            <tr id="tr3">
                              <td colspan="2" id="dato2"  style="text-align: left;">
                                <select name="carpeta" id="carpeta" required="required" > 
                                 <option value="">SELECCIONE...</option> 
                                 <?php do {  ?>
                                  <option value="<?php echo $row_carpeta['descripcion'];?>"><?php echo $row_carpeta['descripcion']?></option>
                                  <?php
                                } while ($row_carpeta = mysql_fetch_assoc($carpeta));
                                $rows = mysql_num_rows($carpeta);
                                if($rows > 0) {
                                  mysql_data_seek($carpeta, 0);
                                  $row_carpeta = mysql_fetch_assoc($carpeta);
                                }
                                ?>
                              </select>

<!--                       <select name="carpeta" id="carpeta" required="required" > 
                          <option value=""></option> 
                          <option value="1-C1. Gestión Plane1">1-C1. Gestión Plane</option>  
                          <option value="2-R1. Gestión Comer">2-R1. Gestión Comer</option> 
                          <option value="3-R2. Gestión Dise">3-R2. Gestión Dise</option> 
                          <option value="5-R4. Gestión Produ">5-R4. Gestión Produ</option> 
                          <option value="6-A1. Gestión Docum">6-A1. Gestión Docum</option> 
                          <option value="7-A2. Gestión Humana">7-A2. Gestión Humana</option> 
                          <option value="8-A3. Gestión Compras">8-A3. Gestión Compras</option> 
                          <option value="9-A4. Gestión Mante">9-A4. Gestión Mante</option> 
                          <option value="Anotaciones auditori">Anotaciones auditori</option> 
                          <option value="Documentos Origen Ex">Documentos Origen Ex</option> 
                          <option value="Gestión Almacenamie">Gestión Almacenamie</option> 
                          <option value="Gestión de Recursos">Gestión de Recursos</option> 
                          <option value="Laboratorio ACYCIA">Laboratorio ACYCIA</option> 
                          <option value="Manual logo icontec">Manual logo icontec</option> 
                          <option value="Plan de mejoramiento">Plan de mejoramiento</option> 
                          <option value="Salud Ocupacional">Salud Ocupacional</option> 
                          <option value="obsoletos">obsoletos</option>    
                        </select> -->
                      </td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                    </tr> 
                    <tr id="tr3">
                      <td  id="dato2" style="text-align: left;" ><input type="hidden" name="MAX_FILE_SIZE" value="10485770"> 
                       <input name="userfile" type="file"> </td>

                       <td colspan="2" id="dato2"><!-- <a class="boton_personalizado" type="submit"  href="#">Adjuntar</a> --><input type="submit" value="Adjuntar"/></td>
                     </tr>
                   </table>
                 </div>
               </form>
             </td>
           </tr>
           <br><br>
         </table>

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
mysql_free_result($usuario_comercial); 
?>
