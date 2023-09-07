<?php 


$cedulanit   = '1';        
$razon_social='razon'; 
$fecha = '2020-10-23'; 
$nombresEnvio = 'nombre envio';
$dir = 'DIRECCION_1  DIRECCION_2';
$ciud = 'ciudadf';
$emaill = 'emaill';
$telefono ='22233344';
$guia = 'guiaa';
$idcliente = '0';
mysql_select_db($database_conexion1, $conexion1);
					 $sqlUpcliente ="UPDATE cliente SET nit_c = '$cedulanit', nombre_c = '$razon_social',fecha_ingreso_c = '$fecha',fecha_solicitud_c = '$fecha', rep_legal_c = '$nombresEnvio',telefono_c = '/$telefono/',ciudad_c = '$ciud', direccion_c = '$dir', contacto_c = '$nombresF', contacto_c = '$nombresF',telefono_contacto_c = '/$telefono/',celular_contacto_c = '/$telefono/',email_comercial_c = '$emaill', direccion_envio_factura_c ='$dir',telefono_envio_factura_c = '/$telefono/',observ_inf_c = '$guia' WHERE id_c='".$idcliente."' ";
					 echo $sqlUpcliente;die;
                    $resultUpcliente=mysql_query($sqlUpcliente); 


?>
</body>
</html>