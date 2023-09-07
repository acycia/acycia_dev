<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
?>
<?php require_once('Connections/conexion1.php'); ?>
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

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario); 

$colname_evaluacion_proveedor = "-1";
if (isset($_GET['id_ev'])) {
  $colname_evaluacion_proveedor = (get_magic_quotes_gpc()) ? $_GET['id_ev'] : addslashes($_GET['id_ev']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_evaluacion_proveedor = sprintf("SELECT * FROM evaluacion_proveedor,proveedor  WHERE evaluacion_proveedor.id_ev = %s AND evaluacion_proveedor.id_p_ev = proveedor.id_p", $colname_evaluacion_proveedor);
$evaluacion_proveedor = mysql_query($query_evaluacion_proveedor, $conexion1) or die(mysql_error());
$row_evaluacion_proveedor = mysql_fetch_assoc($evaluacion_proveedor);
$totalRows_evaluacion_proveedor = mysql_num_rows($evaluacion_proveedor);
?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<!-- <link href="css/vista.css" rel="stylesheet" type="text/css" /> -->
<script type="text/javascript" src="js/vista.js"></script>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/listado.js"></script>

<!-- desde aqui para listados nuevos -->
 
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

	<br> 
	<div align="center" >

		<table align="center" id="tabla" class="tabla_bordes" style="width:90%">
			<tr align="center">
				<td> 

					<table align="center" id="tabla">
						<tr align="center">
							<td rowspan="8" id="dato2" ><img src="images/logoacyc.jpg"></td>
							<td style="text-align: center;" ><h3>  EVALUACIÓN DE DESEMPEÑO DEL PROVEEDOR </h3>	
								<div id="fondo">
									NIT: 890915756-6<br>
									PBX: (60-4) 311 21 44 ∙ www.acycia.com<br>
									Carrera 45 # 14-15   Sector Barrio Colombia<br>
									Medellín – Colombia 
								</div>
							</td>
							<td nowrap="nowrap" style="text-align:left; " ><h4> N. <?php echo $row_evaluacion_proveedor['n_ev']; ?></h4></td>
						</tr>
					</table>
 
      <br>
		  <table style="width:80%">
      	<tr>
      		<td id="justificar">
				  Medellin, 
          <?php $fecha1= date("Y-m-d"); //$row_evaluacion_proveedor['fecha_registro_ev'];
          $dia1=substr($fecha1,8,2);
          $mes1=substr($fecha1,5,2);
          $ano1=substr($fecha1,0,4);
          if($mes1=='01')
          {
          	echo "Enero"."  ".$dia1."  "."de"."  ".$ano1;
          }
          if($mes1=='02')
          {
          	echo "Febrero"."  ".$dia1."  "."de"."  ".$ano1;
          }
          if($mes1=='03')
          {
          	echo "Marzo"."  ".$dia1."  "."de"."  ".$ano1;
          }
          if($mes1=='04')
          {
          	echo "Abril"."  ".$dia1."  "."de"."  ".$ano1;
          }
          if($mes1=='05')
          {
          	echo "Mayo"."  ".$dia1."  "."de"."  ".$ano1;
          }
          if($mes1=='06')
          {
          	echo "Junio"."  ".$dia1."  "."de"."  ".$ano1;
          }
          if($mes1=='07')
          {
          	echo "Julio"."  ".$dia1."  "."de"."  ".$ano1;
          }
          if($mes1=='08')
          {
          	echo "Agosto"."  ".$dia1."  "."de"."  ".$ano1;
          }
          if($mes1=='09')
          {
          	echo "Septiembre"."  ".$dia1."  "."de"."  ".$ano1;
          }
          if($mes1=='10')
          {
          	echo "Octubre"."  ".$dia1."  "."de"."  ".$ano1;
          }
          if($mes1=='11')
          {
          	echo "Noviembre"."  ".$dia1."  "."de"."  ".$ano1;
          }
          if($mes1=='12')
          {
          	echo "Diciembre"."  ".$dia1."  "."de"."  ".$ano1;
          }
        ?><br><br> 
        SEÑOR (A):<br>
        <strong><?php echo $row_evaluacion_proveedor['proveedor_p']; ?></strong><br>
        <?php echo $row_evaluacion_proveedor['contacto_p']; ?><br>
        <?php echo utf8_encode($row_evaluacion_proveedor['ciudad_p']); ?> - 
        <?php echo $row_evaluacion_proveedor['pais_p']; ?><br><br> 
        Cordial Saludo.<br> 
        <p>
          Como requisito fundamental de nuestro Sistema de Gestión de Calidad y en búsqueda del mejoramiento continuo de nuestras relaciones comerciales con clientes y proveedores, nos permitimos participarle el resultado obtenido de la evaluación de proveedores efectuada a la empresa por los <?php echo $row_evaluacion_proveedor['tipo_servicio_p']; ?> suministrados durante el periodo <strong> <?php echo $row_evaluacion_proveedor['periodo_desde_ev']; ?></strong> hasta <strong> <?php echo $row_evaluacion_proveedor['periodo_hasta_ev']; ?></strong>.<br>
        	<br>
        	La evaluación se lleva a cabo de acuerdo a los siguientes criterios:<br><br>	
        </p>

      </td>	
      <tr>
      </table>

    
      <table  class="table table-bordered" style="width:80%">
      	<thead>
      		<tr class="table-active">
      			<th id="subtitulo2" scope="col">REQUISITO A EVALUAR</th>
      			<th id="subtitulo2" scope="col">% ESTABLECIDO / REQUISITO</th>
      			<th id="subtitulo2" scope="col">CALIFICACION OBTENIDA</th> 
      		</tr>
      	</thead>
      	<tbody>
      		<tr>

      			<td><strong>OPORTUNIDAD</strong> <br>
      				(Tiempo de servicio en la fecha acordada)
      			</td>
      			<td id="dato2">40%</td>
      			<td id="dato2"><?php echo $row_evaluacion_proveedor['porcentaje_oportunos_ev']; ?> % </td>
      		</tr>
      		<tr>
      			<td><strong>CALIDAD</strong> <br>
      				(Cumplimiento a satisfacción en la prestación del servicio)
      			</td>
      			<td id="dato2">35%</td>
      			<td id="dato2"><?php echo $row_evaluacion_proveedor['porcentaje_conforme_ev']; ?> % </td>
      		</tr>

      		<tr>
      			<td><strong>SERVICIO</strong> <br> 
      				(Calificación de 1 a 10)<br> 
              Atención a quejas, reclamos, solicitudes,<br> sugerencias y amabilidad en el servicio.
      			</td>
      			<td id="dato2">25%</td>
      			<td id="dato2"><?php echo $row_evaluacion_proveedor['porcentaje_atencion_ev']; ?> % </td>
      		</tr>

      		<tr class="table-active">
      			<td id="dato1"><strong>TOTAL</strong></td>
      			<td id="dato2"><strong>100%</strong></td>
      			<td id="dato2"><strong><?php echo $row_evaluacion_proveedor['porcentaje_final_ev']; ?> % </strong></td>
      		</tr>
 
      	</tbody>
      </table>
      <?php 
         switch ($row_evaluacion_proveedor['porcentaje_final_ev']) {
         	case $row_evaluacion_proveedor['porcentaje_final_ev'] > 95 && $row_evaluacion_proveedor['porcentaje_final_ev'] < 100:
         		   $resultado_eval = "Excelente,";
         		break;
         	case $row_evaluacion_proveedor['porcentaje_final_ev'] > 80 && $row_evaluacion_proveedor['porcentaje_final_ev'] < 94:
         		   $resultado_eval = "Bueno,";
         		break;
         	case $row_evaluacion_proveedor['porcentaje_final_ev'] > 70 && $row_evaluacion_proveedor['porcentaje_final_ev'] < 79:
         		   $resultado_eval = "Regular y por ello se debe establecer un plan de acción.";
         		break;
         	case $row_evaluacion_proveedor['porcentaje_final_ev'] < 70:
         		   $resultado_eval = "Malo y por ello se debe establecer un plan de acción.";
         		break;
         	
         	default:
         		// code...
         		break;
         }
    

         ?>
      <table style="width:80%">
      	<tr>
      		<td id="justificar">
      			Total pedidos efectuados en el periodo: <?php echo $row_evaluacion_proveedor['total_oc_ev']; ?> <br>
      			Total de entregas efectuadas en el periodo: <?php echo $row_evaluacion_proveedor['total_verificacion_ev']; ?><br>
      			 
      			Según la siguiente tabla, los resultados obtenidos lo catalogan como un proveedor <strong><?php echo $resultado_eval; ?></strong> 
      			De antemano les agradecemos los servicios que nos han prestado y esperamos que esta evaluación sea de gran utilidad para retroalimentar su proceso y afianzar nuestras relaciones cliente/proveedor.<br>
      			<br> 
           </td>
         </tr>
         <tr align="center">
         	<td> 
           <table  class="table table-bordered" style="width:50%">
           	<thead>
           		<tr class="table-active">
           			<th id="subtitulo2" scope="col">CLASIFIACIÓN</th>
           			<th id="subtitulo2" scope="col">CALIFICACIÓN</th> 
           		</tr>
           	</thead>
           	<tbody>
           		<tr>  
                <td id="dato2">Excelente 
                   </td>
                <td>95% y 100%</td>
              </tr>
              <tr>
                <td id="dato2">Bueno
                   </td>
                <td>80% y 94%</td> 
              </tr>
              <tr>
                <td id="dato2">Regular
                   </td>
                <td>70% y 79%</td> 
              </tr>
              <tr>
                <td id="dato2">Malo
                   </td>
                <td>Menor a 70%</td>  
            </tr>
          	</tbody>
          </table>
          	</td>
          </tr>
        
        <tr>
          <td> 

      		<table>
      		  <tr>
      			 <td> 	 
      			<!-- De la anterior evaluaci&oacute;n se obtuvo una calificaci&oacute;n que lo ubico dentro de un rango de 
      			<?php if($row_evaluacion_proveedor['calificacion_ev']=='1') { echo "mal desempeno"; } ?>
      			<?php if($row_evaluacion_proveedor['calificacion_ev']=='2') { echo "regular desempeno y se debe establecer un plan de accion"; } ?>
      			<?php if($row_evaluacion_proveedor['calificacion_ev']=='3') { echo "buen desempeno y se considera que cumple para la organizacion"; } ?>
      			.<br><br>
      			De antemano les agradecemos los servicios que nos han  prestado y esperamos que esta evaluaci&oacute;n sea de gran utilidad para  retroalimentar su proceso y afianzar nuestras relaciones cliente/proveedor.<br><br><br>
      			Atentamente,<br><br><br> -->


      			Atentamente,<br><br> 

      			<?php echo $row_evaluacion_proveedor['responsable_registro_ev']; ?><br>
      			Coordinador Proceso de compras. 
            <br><br>
      		 </td>
      		<tr>
      		</table>

      		</td>
      	</tr>


      	</td>
      </tr>
    </table>
  </div>



</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($evaluacion_proveedor);
?>
