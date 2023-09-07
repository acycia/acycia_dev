<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>

<body>
<form action="envioemail" method="post">
  <?php 
  
    $oc='AC-43';
    $id_c=0;
    $nit_c=0;
				   $headers = "MIME-Version: 1.0\r\n"; 
				   $headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 
				   //dirección del remitente 
				   $headers .= "From: ACYCIA\r\n"; 
				   //dirección de respuesta, si queremos que sea distinta que la del remitente 
				   $headers .= "ACYCIA\r\n"; 			   
				   $to = 'robinrt144@hotmail.com';  //enviar al correo su carnet
				   $mensaje = "<p>Orden de Compra para Facturar: $oc</p></b>";				   
                   $mensaje .= "<p>Revisa en el siguiente Link:</p></b>";
				   $mensaje .= "<a href='http://intranet.acycia.com/orden_compra1_cl.php?str_numero_oc=$oc&id_c=0&nit_c=0' target='_blank'>Ver Orden de Compra</a></p></b>";
				   $mensaje .= "<p><span style=\"color: #FF0000\"><strong>Nota Importante: </strong></span>Recuerde que es necesario haber iniciado sesion en la pagina de SISAGGE, para poder abrir este link, Gracias. </p></b>";
						  //$impri = "carnet.php?id=" . $_SESSION["k_username"]; //si quiero enviar una variable por url
				  (mail("$to","Orden de Compra para Facturar: $oc",$mensaje,$headers));  
?>

<input name="envio" type="button" value="envio" />
</form>
</body>
</html>