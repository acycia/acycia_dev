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
$colname_usuario_comercial = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario_comercial = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario_comercial = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario_comercial);
$usuario_comercial = mysql_query($query_usuario_comercial, $conexion1) or die(mysql_error());
$row_usuario_comercial = mysql_fetch_assoc($usuario_comercial);
$totalRows_usuario_comercial = mysql_num_rows($usuario_comercial);

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
              <div class="panel-heading">RESULTADOS DE ARCHIVOS ADJUNTOS </div>
              <div class="panel-body">

                <table id="tabla">
                  <tr>
                    <td align="center">
                     <div id="cabecera"><img src="images/cabecera.jpg"></div>
                     <div id="cabezamenu">
                       <ul id="menuhorizontal">
                         <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                         <li><a href="menu.php">MENU PRINCIPAL</a></li> 
                         <li><a href="/acycia/dropboxacycia/">VER ARCHIVOS</a></li>
                         <li><a href="/acycia/agregar_carpetas.php">AGREGAR CARPETAS</a></li>
                         <li><a href="/acycia/agregar_carpetas.php">AGREGAR CARPETAS</a></li>
                       </ul>
                     </div>
                     <div id="nombreusuario"><?php echo $row_usuario_comercial['nombre_usuario']; ?></div>
                   </td>
                 </tr>
                 <tr>
                  <td id="subtitulo">&nbsp;</td>
                </tr>
                <tr>
                  <td id="subtitulo">RESULTADOS DE ARCHIVOS ADJUNTOS </td>
                </tr>
                <tr>
                  <td id="subtitulo"></td>
                </tr>

                <tr>
                  <td>
                   <?php  


                   $carpeta =  ($_POST["carpeta"]); 
                   $nombre_archivo = $_FILES['userfile']['name'];
                   $tipo_archivo = $_FILES['userfile']['type'];
                   $tamano_archivo = $_FILES['userfile']['size'];//1048576 es una mega 
                   $arte= $_POST["arte"]; 

          if((strpos($tipo_archivo, "xlsx")))
                 $output = "xlsx"; 
              else
          if((strpos($tipo_archivo, "doc")))
                 $output = "doc"; 
              else
          if((strpos($tipo_archivo, "pdf")))
                 $output = "pdf";
              else 
         if((strpos($tipo_archivo, "gif")))
                  $output = "gif"; 
              else
         if((strpos($tipo_archivo, "jpg")))
                  $output = "jpg"; 
              else
          if((strpos($tipo_archivo, "png")))
              $output = "png"; 


            if (!((strpos($tipo_archivo, $output)) )) 
             { ?>
               <div id="numero2"> <img src="images/por.gif" /> <?php echo "La extension o el tamano de los archivos no es correcta. <br><br>Se permiten archivos pdf,xlsx,doc,gif,jpg,png ."; ?> 
             </div> 
           <?php  } else {
             if($arte != '')
             {
              if (file_exists("dropboxacycia/".$carpeta."/".$arte))
              { 
                unlink("dropboxacycia/".$carpeta."/".$arte);
              }	   
            } 
            if (move_uploaded_file($_FILES['userfile']['tmp_name'], "dropboxacycia/".$carpeta."/".$nombre_archivo))    
             {  ?> <div id="acceso2"> <img src="images/cliente.gif" /> <?php
           echo "El archivo ha sido cargado correctamente."; ?> </div>	
         </td>
       </tr>
       <tr>
        <td id="fuente2">
          <?php 
        }
        else
          {  echo "Ocurrió algún error al subir el fichero. <br><br> No pudo guardarse."; 
        $sw=0;
      } 
    } 
    if($sw=='1')
    {
      echo $nombre_archivo;
    }
    if($sw=='0')
    {
      echo $arte;
    } 
    ?>

  </td>
</tr>
<br>
<tr>
  <td id="fuente2">
    <br><br>
    <a class="boton_personalizado" type="submit"  href="adjuntardropbox.php">Volver</a>
    <br><br>
  </td>
</tr>

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
