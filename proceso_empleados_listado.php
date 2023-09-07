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
//LLAMADO A FUNCIONES
include('funciones/funciones_php.php');//SISTEMA RUW PARA LA BASE DE DATOS 
//FIN
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
$currentPage = $_SERVER["PHP_SELF"];

$conexion = new ApptivaDB();

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario); 

$maxRows_proceso_empleado = 60;
$pageNum_proceso_empleado = 0;
if (isset($_GET['pageNum_proceso_empleado'])) {
  $pageNum_proceso_empleado = $_GET['pageNum_proceso_empleado'];
}
$startRow_proceso_empleado = $pageNum_proceso_empleado * $maxRows_proceso_empleado;
 
$rows_empleado=$conexion->buscarListar(" empleado a INNER JOIN TblProcesoEmpleado b ON a.codigo_empleado=b.codigo_empleado","*","ORDER BY a.codigo_empleado DESC","",$maxRows_proceso_empleado,$pageNum_proceso_empleado," " );
 
if (isset($_GET['totalRows_proceso_empleado'])) {
  $totalRows_proceso_empleado = $_GET['totalRows_proceso_empleado'];
} else {
  $totalRows_proceso_empleado = $conexion->conteo('empleado'); 
} 
$totalPages_proceso_empleado = ceil($totalRows_proceso_empleado/$maxRows_proceso_empleado)-1;
 
$queryString_proceso_empleado = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_proceso_empleado") == false && 
        stristr($param, "totalRows_proceso_empleado") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_proceso_empleado = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_proceso_empleado = sprintf("&totalRows_proceso_empleado=%d%s", $totalRows_proceso_empleado, $queryString_proceso_empleado);

 
mysql_select_db($database_conexion1, $conexion1);
$query_mensual = "SELECT * FROM mensual ORDER BY id_mensual DESC";
$mensual = mysql_query($query_mensual, $conexion1) or die(mysql_error());
$row_mensual = mysql_fetch_assoc($mensual);
$totalRows_mensual = mysql_num_rows($mensual);

mysql_select_db($database_conexion1, $conexion1);
$query_ano = "SELECT * FROM anual ORDER BY anual DESC";
$ano = mysql_query($query_ano, $conexion1) or die(mysql_error());
$row_ano = mysql_fetch_assoc($ano);
$totalRows_ano = mysql_num_rows($ano);

mysql_select_db($database_conexion1, $conexion1);
$query_factor = "SELECT * FROM TblFactorP ORDER BY fecha_fp DESC";
$factor = mysql_query($query_factor, $conexion1) or die(mysql_error());
$row_factor = mysql_fetch_assoc($factor);
$totalRows_factor = mysql_num_rows($factor);
?>
<html>
<head>
  <title>SISADGE AC &amp; CIA</title>
  <link href="css/formato.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="js/listado.js"></script>
  <script type="text/javascript" src="js/formato.js"></script>

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

</head>
<body>
 

    <div align="center">
      <table style="width: 80%"><!-- id="tabla1" -->
        <tr>
         <td align="center">
           <div class="row-fluid">
             <div class="span8 offset2"> <!--span8 offset2   esto da el tamaño pequeño -->
               <div class="panel panel-primary">
                <div class="panel-heading" align="left" ></div><!--color azul-->
                <div class="row" >
                    <div class="span12">&nbsp;&nbsp;&nbsp; <img src="images/cabecera.jpg"></div> 
                </div>
                <div class="panel-heading" align="left" ></div><!--color azul-->
                 <div id="cabezamenu">
                  <ul id="menuhorizontal">
                    <li id="nombreusuario" ><?php echo $_SESSION['Usuario']; ?></li>
                    <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                    <li><a href="menu.php">MENU PRINCIPAL</a></li>
                    <li><a href="costos_generales.php">COSTOS GENERALES</a></li>
                  </ul>
               </div> 
               <div class="panel-body">
                 <br> 
                 <div class="container">
                  <div class="row">
                    <div class="span12"> 
               </div>
             </div>

                  <form action="proceso_empleados_listado2.php" method="get" name="consulta">
                  <table >
                    <tr>
                      <td colspan="8" id="subtitulo">LISTADO DE EMPLEADOS DE PLANTA</td>
                    </tr>
                    <tr>
                      <td colspan="8" id="fuente2">Fecha Inicial
                        <select name="anual" id="anual">
                          <option value="0">ANUAL</option>
                          <?php
                          do {  
                            ?>
                            <option value="<?php echo $row_ano['anual']?>"<?php if (!(strcmp($row_ano['anual'], date('Y')))) {echo "selected=\"selected\"";} ?>><?php echo $row_ano['anual']?></option>
                            <?php
                          } while ($row_ano = mysql_fetch_assoc($ano));
                          $rows = mysql_num_rows($ano);
                          if($rows > 0) {
                            mysql_data_seek($ano, 0);
                            $row_ano = mysql_fetch_assoc($ano);
                          }
                          ?>
                        </select>
                        <select name="mensual" id="mensual">
                          <option value="0">MENSUAL</option>
                          <?php
                          do {  
                            ?>
                            <option value="<?php echo $row_mensual['id_mensual']?>"<?php if (!(strcmp($row_mensual['id_mensual'], date('m')))) {echo "selected=\"selected\"";} ?>><?php echo $row_mensual['mensual']?></option>
                            <?php
                          } while ($row_mensual = mysql_fetch_assoc($mensual));
                          $rows = mysql_num_rows($mensual);
                          if($rows > 0) {
                            mysql_data_seek($mensual, 0);
                            $row_mensual = mysql_fetch_assoc($mensual);
                          }
                          ?>
                        </select>
                        <select name="estado_empleado" id="estado_empleado">
                          <option value="2" selected>Todos</option>
                          <option value="1">Activo</option>
                          <option value="0">Inactivo</option>
                        </select>
                        <input class="botonUpdate" type="submit" name="Submit" value="FILTRO"/>
                        <input type="button" value="Excel Completo" onClick="window.location = 'proceso_empleados_listado_excel.php?id_todo=1'" /></td>
                      </tr>
                    </table>
                  </form>
              <?php if($_SESSION['acceso']): ?>
                  <table id="tabla3">
                    <tr>
                      <td colspan="11" id="fuente1"><!--<input name="" type="submit" value="Delete"/>--><strong>Nota:</strong> consulte por año y mes para poder visualizar los cambios de aporter y recargos correspondientes a la fecha, recuerde tener actualizados los factores</td>
                      <td colspan="4" id="fuente3"><a href="empleado_add.php"><img src="images/mas.gif" alt="ADD EMPLEADO" title="ADD EMPLEADO" border="0" style="cursor:hand;"></a><a href="factor_prestacional_add.php"><img src="images/f.gif" alt="FACTORES" title="FACTORES" border="0" style="cursor:hand;"></a><a href="empleado_tipo.php"><img src="images/p.gif" title="CARGO" alt="CARGO" border="0" style="cursor:hand;"></a><a href="turnos.php"><img src="images/t.gif" style="cursor:hand;" alt="TURNOS" title="TURNOS" border="0"/></a><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="CARGAR LISTADO" title="CARGAR LISTADO" border="0" style="cursor:hand;"/></a></td>
                    </tr>
                    <tr id="tr1">
                      <td id="titulo4">CODIGO</td>
                      <td id="titulo4">NOMBRE APELLIDO</td>
                      <td id="titulo4">CARGO</td>
                      <td id="titulo4">SUELDO</td>
                      <td id="titulo4">RECARGOS</td>
                      <td id="titulo4">APORTES</td>
                      <td id="titulo4">COSTO MES</td>
                      <td id="titulo4">VALOR HORA</td>
                      <td id="titulo4">DIAS NOVEDAD</td>
                      <td id="titulo4">DIAS LABORADOS</td>
                      <td id="titulo4">FECHA INICIAL</td>
                      <td id="titulo4">FECHA RETIRO</td>
                      <td id="titulo4">EMPRESA</td>
                      <td id="titulo4">NOVEDADES</td>
                      <td id="titulo4">ESTADO</td>
                    </tr>
                    <?php foreach($rows_empleado as $rows_empleado) {  ?>
                      <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
                        <td id="dato1"><a href="empleado_edit.php?id_empleado=<?php echo $rows_empleado['id_empleado']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $rows_empleado['codigo_empleado'];?></a></td>
                        <td nowrap id="dato1"><a href="empleado_edit.php?id_empleado=<?php echo $rows_empleado['id_empleado']; ?>" target="_top" style="text-decoration:none; color:#000000">
                          <?php $codigo_empleado=$rows_empleado['codigo_empleado']; 	
                          $sqlemp="SELECT nombre_empleado, apellido_empleado FROM empleado WHERE codigo_empleado='$codigo_empleado'";
                          $resultemp=mysql_query($sqlemp); $numemp=mysql_num_rows($resultemp);
                          if ($numemp>='1') { 
                           $nombre_empleado=mysql_result($resultemp,0,'nombre_empleado');$apellido_empleado=mysql_result($resultemp,0,'apellido_empleado');  
                           echo $nombre_empleado." ".$apellido_empleado; }?>
                         </a></td>
                         <td nowrap id="dato1">
                          <a href="empleado_edit.php?id_empleado=<?php echo $rows_empleado['id_empleado']; ?>" target="_top" style="text-decoration:none; color:#000000">
                          <?php 
                          $cargo_empleado=$rows_empleado['codigo_empleado'];
                          $sqlempt="SELECT empleado.codigo_empleado,empleado.empresa_empleado,empleado.tipo_empleado,empleado_tipo.nombre_tipo_empleado FROM empleado JOIN empleado_tipo ON empleado_tipo.id_empleado_tipo=empleado.tipo_empleado WHERE empleado.codigo_empleado='$cargo_empleado'  ";
                          $resultempt=mysql_query($sqlempt); 
                          $numempt=mysql_num_rows($resultempt);
                          if ($numempt>='1') { 
                           $empresa_empleado=mysql_result($resultempt,0,'empleado.empresa_empleado');
                           $cargo_empl=mysql_result($resultempt,0,'nombre_tipo_empleado'); 
                           echo $cargo_empl;  
                         }
                         ?>
                       </a></td>
                       <td id="dato3"><a href="proceso_empleado_edit.php?id_pem=<?php echo $rows_empleado['id_pem']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo numeros_format($rows_empleado['sueldo_empleado']);$totalsueldo+=$rows_empleado['sueldo_empleado'];?></a></td>
                       <td id="dato3"><a href="proceso_empleado_edit.php?id_pem=<?php echo $rows_empleado['id_pem']; ?>" target="_top" style="text-decoration:none; color:#000000">
                        <?php $novedades=$rows_empleado['codigo_empleado']; 	
                        $sqlrecargos="SELECT SUM(pago_acycia) AS valoracycia, SUM(pago_eps) AS valoreps, SUM(dias_incapacidad) as dias, SUM(dias_faltantes) as diasf, SUM(horas_extras) as horas,SUM(recargos) as recargos,SUM(festivos) as festivos
                        FROM TblNovedades
                        WHERE fecha >= date_sub(curdate(), interval 1 month) AND codigo_empleado=$novedades";
                        $resultrecargos=mysql_query($sqlrecargos); 
                        $numrecargos=mysql_num_rows($resultrecargos);
                        if ($numrecargos >='1') { 
                          $valoracycia=mysql_result($resultrecargos,0,'valoracycia'); 
                          $valoreps=mysql_result($resultrecargos,0,'valoreps');
                          $dias_incapacidad=mysql_result($resultrecargos,0,'dias'); 
                          $dias_falto=mysql_result($resultrecargos,0,'diasf');
                          $horas=mysql_result($resultrecargos,0,'horas');
                          $recargos=mysql_result($resultrecargos,0,'recargos'); 
                          $festivos=mysql_result($resultrecargos,0,'festivos');
                          $pagoIncapacidad=$valoracycia;
                          $total_recargos = $horas+$recargos+$festivos; 
                          echo $total_recargos;
                          $totalrecargo+=$total_recargos;
                        }?>
                      </a></td>
                      <td id="dato3"><a href="proceso_empleado_edit.php?id_pem=<?php echo $rows_empleado['id_pem']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php 
                      $sueld=$rows_empleado['sueldo_empleado'];	
                      $aux_trans=$rows_empleado['aux_empleado'];	
                      ?>
                      <?php 	
                      $codigo_aporte=$rows_empleado['codigo_empleado'];
                      $sqlaport="SELECT total FROM TblAportes WHERE codigo_empl=$codigo_aporte";
                      $resultaport=mysql_query($sqlaport); 
                      $numaport=mysql_num_rows($resultaport);
                      if ($numaport>='1') { 
                       $aport=mysql_result($resultaport,0,'total');  
                       echo $aport; $totalaporte+=$aport;}
                       ?></a></td>
                       <td id="dato3"><a href="proceso_empleado_edit.php?id_pem=<?php echo $rows_empleado['id_pem']; ?>" target="_top" style="text-decoration:none; color:#000000">
                        <?php 
	//sueldo mes
	//variables de control
                        $sueld=$rows_empleado['sueldo_empleado'];	
                        $aux_trans=$rows_empleado['aux_empleado'];
                        $diasmes = $rows_empleado['dias_empleado']-($dias_falto+$dias_incapacidad);	
                        $subsueldo = ($sueld+$aux_trans)/$rows_empleado['dias_empleado'];	
                        $costomes=$subsueldo * $diasmes;  
                        $costoMesNeto = sumar($total_recargos,$aport,$costomes,0);
                        echo numeros_format($costoMesNeto); $totalmes+=$costoMesNeto;
                        ?>
                      </a><a href="proceso_empleado_edit.php?id_pem=<?php echo $rows_empleado['id_pem']; ?>" target="_top" style="text-decoration:none; color:#000000"></a></td>
                      <td id="dato2"><a href="proceso_empleado_edit.php?id_pem=<?php echo $rows_empleado['id_pem']; ?>" target="_top" style="text-decoration:none; color:#000000">
                        <?php 
	$costo_hora = $costoMesNeto/$row_factor['hora_lab_fp']; //para saber costo por hora
	echo redondear_entero_puntos($costo_hora);
  ?>
</a></td>
<td id="dato2"><?php if($dias_falto!=''){echo ($dias_falto+$dias_incapacidad);} else {echo 0;}?></td>
<td id="dato2"><a href="proceso_empleado_edit.php?id_pem=<?php echo $rows_empleado['id_pem']; ?>" target="_top" style="text-decoration:none; color:#000000">
  <?php  echo $diasmes;?>
</a><a href="proceso_empleado_edit.php?id_pem=<?php echo $rows_empleado['id_pem']; ?>" target="_top" style="text-decoration:none; color:#000000"></a></td>
<td nowrap id="dato2"><a href="proceso_empleado_edit.php?id_pem=<?php echo $rows_empleado['id_pem']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $rows_empleado['fechainicial_empleado']; ?></a></td>
<td nowrap id="dato2"><a href="proceso_empleado_edit.php?id_pem=<?php echo $rows_empleado['id_pem']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $rows_empleado['fechafinal_empleado']; ?></a></td>
<td id="dato2"><a href="proceso_empleado_edit.php?id_pem=<?php echo $rows_empleado['id_pem']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $empresa_empleado; ?></a></td>
<td id="dato2"><?php 
$novedades=$rows_empleado['codigo_empleado']; 
$fechaI=first_month_day();
$fechaF=last_month_day();	
$sqlnov="SELECT codigo_empleado FROM TblNovedades WHERE codigo_empleado=$novedades AND fecha BETWEEN '$fechaI' AND '$fechaF'";
$resultnov=mysql_query($sqlnov); $numnov=mysql_num_rows($resultnov);
if ($numnov >='1') {?>
  <a href="javascript:popUp('novedades.php?id_pem=<?php echo $rows_empleado['id_pem']; ?>&cod=<?php echo $rows_empleado['codigo_empleado']; ?>&fecha=<?php echo $fechaF; ?>','800','500')"><span class="rojo_normal"><em>VerNovedad</em></span></a>
  <?php
} else{?>
  <a href="javascript:popUp('novedades.php?id_pem=<?php echo $rows_empleado['id_pem']; ?>&cod=<?php echo $rows_empleado['codigo_empleado']; ?>','800','500')"><em>AddNovedad</em></a>
  <?php } ?></td>
  <td id="dato2"><a href="proceso_empleado_edit.php?id_pem=<?php echo $rows_empleado['id_pem']; ?>" target="_top" style="text-decoration:none; color:#000000">
    <?php if($rows_empleado['estado_empleado']==0){echo "Inactivo";}else{echo "Activo";}?>
  </a></td>
</tr>
<?php } ?>
<tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
  <td id="dato4">&nbsp;</td>
  <td nowrap id="dato4">&nbsp;</td>
  <td nowrap id="dato4">&nbsp;</td>
  <td id="dato3"><?php echo number_format($totalsueldo, 2, ",", ".");?></td>
  <td id="dato3"><?php echo number_format($totalrecargo, 2, ",", ".");?></td>
  <td id="dato3"><?php echo number_format($totalaporte, 2, ",", ".");?></td>
  <td id="dato3"><?php echo number_format($totalmes, 2, ",", ".");?></td>
  <td id="dato6">&nbsp;</td>
  <td id="dato6">&nbsp;</td>
  <td id="dato6">&nbsp;</td>
  <td nowrap id="dato6">&nbsp;</td>
  <td nowrap id="dato6">&nbsp;</td>
  <td id="dato6">&nbsp;</td>
  <td id="dato6">&nbsp;</td>
  <td id="dato6">&nbsp;</td>
</tr>                    
</table>

<?php endif; ?>

<table id="tabla3">
  <tr>
    <td width="23%" align="center" id="dato2"><?php if ($pageNum_proceso_empleado > 0) { // Show if not first page ?>
      <a href="<?php printf("%s?pageNum_proceso_empleado=%d%s", $currentPage, 0, $queryString_proceso_empleado); ?>">Primero</a>
      <?php } // Show if not first page ?></td>
      <td width="31%" align="center" id="dato2"><?php if ($pageNum_proceso_empleado > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_proceso_empleado=%d%s", $currentPage, max(0, $pageNum_proceso_empleado - 1), $queryString_proceso_empleado); ?>">Anterior</a>
        <?php } // Show if not first page ?></td>
        <td width="23%" align="center" id="dato2"><?php if ($pageNum_proceso_empleado < $totalPages_proceso_empleado) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_proceso_empleado=%d%s", $currentPage, min($totalPages_proceso_empleado, $pageNum_proceso_empleado + 1), $queryString_proceso_empleado); ?>">Siguiente</a>
          <?php } // Show if not last page ?></td>
          <td width="23%" align="center" id="dato2"><?php if ($pageNum_proceso_empleado < $totalPages_proceso_empleado) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_proceso_empleado=%d%s", $currentPage, $totalPages_proceso_empleado, $queryString_proceso_empleado); ?>">&Uacute;ltimo</a>
            <?php } // Show if not last page ?></td>
          </tr>
        </table></td>
      </tr>
    </table>
  </div>
  <b class="spiffy"> <b class="spiffy5"></b> <b class="spiffy4"></b> <b class="spiffy3"></b> <b class="spiffy2"><b></b></b> <b class="spiffy1"><b></b></b></b></div></td>
</tr>
</table></td>
  </tr> 
 </div> <!-- contenedor -->

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
mysql_free_result($usuario);

mysql_free_result($proceso_empleado);

mysql_free_result($mensual);

mysql_free_result($ano);
?>