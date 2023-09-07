<?php require_once('Connections/conexion1.php'); ?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

if (isset($_POST['email_usuario'])) {
  $emailUsername=$_POST['email_usuario'];
  $MM_redirectLoginSuccess = "usuario_cambio_password2.php";
  $MM_redirectLoginFailed = "usuario_cambio_password.php?id_m=1";
  mysql_select_db($database_conexion1, $conexion1);
  
  $LoginRS__query=sprintf("SELECT email_usuario FROM usuario WHERE email_usuario='%s'",
    get_magic_quotes_gpc() ? $emailUsername : addslashes($emailUsername)); 
   
  $LoginRS = mysql_query($LoginRS__query, $conexion1) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser !='') {
	      
    header("Location: " . $MM_redirectLoginSuccess ."?". "email=".$_POST['email_usuario']);
  }
  else {
	  
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="StyleSheet" href="css/imageMenu.css" type="text/css">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
<script type="text/javascript" src="js/formato.js"></script>
</head>
<body oncontextmenu="return false">
<div class="container-fluid" id="divconten"> 
        <div class="row">
         <div class="col-md-8" ><img src="images/cabecera.jpg" style="width: 100%;margin:10px 0px 10px;">
          </div>
          <div class="col-md-4"> 
            <div class="menu2"><ul>
              <li><?php echo $row_usuario['nombre_usuario']; ?></li>
              <li><a href="<?php echo $logoutAction ?>">SALIR</a></li>
              </ul>
            </div> 
          </div>
        </div>
         <div class="row">
           <div class="col-md-4">
              <!--LOGUIN-->
	            <div class="navbar">
                   <div class="navbar"><ul>
                    <li><a href="index.php">Pagina Principal</a></li>
                     <li><a href="menu.php">Pagina Comercial</a></li>  
                      </ul>
                      </div>
                     <form ACTION="<?php echo $loginFormAction; ?>" method="POST" name="form1" onSubmit="MM_validateForm('email_usuario,'','R');return document.MM_returnValue">
                      <div id="fuente2">E-mail</div>
                       <div id="fuente2"><input name="email_usuario" type="email" id="email_usuario"size="20" required></div>
                        <div id="fuente2"><input name="Enviar" type="submit" id="Enviar" value="Enviar"></div>
						 <?php if ($_GET['id_m']=='1'){?>
                         <div id="rojo3">Este correo no existe en la Base de Datos !</b></div>
                         <?php }?>
                         <div id="fuente2"><br> <a href="usuario_cambio_password.php">Cambiar clave ?</a>
                         <br><a href="usuario_olvido_password.php">Olvido Clave ?</a> <br> </div>
                      </form>
                   </div>                   
              </div>          
               <div class="col-md-4">
                <strong>MENU PRINCIPAL</strong><br><br>
 				 El sistema administrador de gestiones (SISADGE) de ALBERTO CADAVID R & C&Iacute;A S.A. es un desarrollo gen&eacute;rico que especifica el Sistema de Gesti&oacute;n de Calidad en nuestra organizaci&oacute;n. 
				<br>El proposito fundamental de este desarrollo es seguir paso a paso la metodologia del sistema de Gesti&oacute;n de Calidad para la linea comercial, de dise&ntilde;o, producci&oacute;n y comercializaci&oacute;n de bolsas de seguridad para el empaque y transporte de valores.<br><br>           
               </div>
                <div class="col-md-4">
                <strong>CAMBIO DE CONTRASEÑA</strong><br><br>
 				 El sistema puedes cambiar tu contraseña, siempre y cuando tengas un correo de la compañia.<br><br>
              </div>                     
          </div>

  </div>
</body>
</html>
