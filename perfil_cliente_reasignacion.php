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
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$nit_viejo=$_POST["nit_viejo"];
$nit_nuevo=$_POST["nit_nuevo"];

  $num2=trim($nit_nuevo);
  $nit_nuevo = ereg_replace("[^A-Za-z0-9-]", "", $num2);
  $nit_nuevo=str_replace(' ', '', $nit_nuevo);

  $nit_nuevo = explode('-', $nit_nuevo);
  $nit_nuevo = $nit_nuevo[0].$nit_nuevo[1];

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
//REASIGNAR NIT
if($nit_viejo!=''&&$nit_nuevo!=''){
/*mysql_select_db($database_conexion1, $conexion1);
$sqlnit="SELECT * FROM cliente WHERE nit_c='$nit_nuevo'";
$resultnit=mysql_query($sqlnit);
$numnit= mysql_num_rows($resultnit);	
if($numnit >'0')
{
$guardado='0';	
}else{
*/
mysql_select_db($database_conexion1, $conexion1);
$sqlnv="UPDATE cliente SET nit_c='$nit_nuevo' WHERE nit_c='$nit_viejo'";
$resultnv=mysql_query($sqlnv);

$sqlncr="UPDATE Tbl_cliente_referencia SET str_nit='$nit_nuevo' WHERE str_nit='$nit_viejo'";//
$resultncr=mysql_query($sqlncr);

$sqlnc="UPDATE Tbl_cotizaciones SET Str_nit='$nit_nuevo' WHERE Str_nit='$nit_viejo'";//
$resultnc=mysql_query($sqlnc);

$sqlncb="UPDATE Tbl_cotiza_bolsa SET Str_nit='$nit_nuevo' WHERE Str_nit='$nit_viejo'";//
$resultncb=mysql_query($sqlncb);

$sqlncbo="UPDATE Tbl_cotiza_bolsa_obs SET Str_nit='$nit_nuevo' WHERE Str_nit='$nit_viejo'";//
$resultncbo=mysql_query($sqlncbo);

$sqlncl="UPDATE Tbl_cotiza_laminas SET Str_nit='$nit_nuevo' WHERE Str_nit='$nit_viejo'";//
$resultncl=mysql_query($sqlncl);

$sqlnclo="UPDATE Tbl_cotiza_laminas_obs SET Str_nit='$nit_nuevo' WHERE Str_nit='$nit_viejo'";//
$resultnclo=mysql_query($sqlnclo);

$sqlnmp="UPDATE Tbl_cotiza_materia_p SET Str_nit='$nit_nuevo' WHERE Str_nit='$nit_viejo'";//
$resultnmp=mysql_query($sqlnmp);

$sqlnmpo="UPDATE Tbl_cotiza_materia_p_obs SET Str_nit='$nit_nuevo' WHERE Str_nit='$nit_viejo'";//
$resultnmpo=mysql_query($sqlnmpo);

$sqlncp="UPDATE Tbl_cotiza_packing SET Str_nit='$nit_nuevo' WHERE Str_nit='$nit_viejo'";//
$resultncp=mysql_query($sqlncp);

$sqlncpo="UPDATE Tbl_cotiza_packing_obs SET Str_nit='$nit_nuevo' WHERE Str_nit='$nit_viejo'";//
$resultncpo=mysql_query($sqlncpo);

$sqlnd="UPDATE Tbl_Destinatarios SET nit='$nit_nuevo' WHERE nit='$nit_viejo'";//
$resultnd=mysql_query($sqlnd);

$sqlnoc="UPDATE Tbl_orden_compra SET str_nit_oc='$nit_nuevo' WHERE str_nit_oc='$nit_viejo'";//
$resultnoc=mysql_query($sqlnoc);

$sqlnop="UPDATE Tbl_orden_produccion SET str_nit_op='$nit_nuevo' WHERE str_nit_op='$nit_viejo'";//
$resultnop=mysql_query($sqlnop);

$sqlnr="UPDATE Tbl_refcliente SET str_nit_rc='$nit_nuevo' WHERE str_nit_rc='$nit_viejo'";//
$resultnr=mysql_query($sqlnr);

/*$sqlnp="UPDATE pedido SET nit_c_pedido='$nit_nuevo' WHERE nit_c_pedido='$nit_viejo'";//
$resultnp=mysql_query($sqlnp);*/

$sqlmm="UPDATE Tbl_maestra_mp SET Str_nit='$nit_nuevo' WHERE Str_nit='$nit_viejo'";//
$resultmm=mysql_query($sqlmm);

$sqldes="UPDATE Tbl_despacho SET cliente_d='$nit_nuevo' WHERE cliente_d='$nit_viejo'";//
$resultdes=mysql_query($sqldes);

/*echo "<script>alert('El NIT $nit_viejo fue modificado por El nuevo NIT $nit_nuevo');</script>";*/
$guardado='1';
$nit_nuevo;
$nit_viejo;
}
}
 
?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>

<link rel="stylesheet" type="text/css" href="css/general.css"/> 
<link rel="stylesheet" type="text/css" href="css/desplegable.css" />
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
  <link rel="stylesheet" type="text/css" href="css/general.css"/>

  <!-- css Bootstrap hace mas grande el formato-->
  <link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

<!--CODIGO DE CONFIRMACION CIERRE DE PAGINA WEB-->
<script type="text/javascript">
/*window.onbeforeunload = function exitAlert()
{
var textillo = "Esta Seguro de Salir y no ingresar mas ITEMS a la O.C ?";
return textillo;
}*/
</script>
<script type="text/javascript">
function reasignar()
{
var nitv = document.form1.nit_viejo.value;
var nitn = document.form1.nit_nuevo.value;
if (nitv =='' || nitn=='')
{alert("Uno de los dos campos esta vacio");}else
{
var statusConfirm = confirm("Esta seguro de Cambiar el numero de NIT: "+nitv+" por el NIT: "+nitn);
if (statusConfirm == true)
{
	document.form1.submit()
}else if (statusConfirm == false)
{
   window.close();
}
}
}
<!--FIN-->
</script>
</head>
<body>
<script language="JavaScript" type="text/javascript">
var nv= "<?php echo $nit_viejo; ?>" ; 
var nn= "<?php echo $nit_nuevo; ?>" ; 
var guardado = "<?php echo $guardado; ?>" ;
function alerta() {
if(guardado=='1'){
alert("El NIT "+nv+" fue modificado por El nuevo NIT "+nn+"");
}
}
window.onload = alerta;
</script>
<div class="spiffy_content"> <!-- este define el fondo gris de lado a lado si se coloca dentro de tabla inicial solamente coloca borde gris -->
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
                <div class="panel-heading"><h3>REASIGNAR NIT &nbsp;&nbsp; </h3></div>
              </div>
              <div class="panel-heading" align="left" ></div><!--color azul-->
              <div id="cabezamenu">
                <ul id="menuhorizontal">
                  <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                  <li><a href="menu.php">MENU PRINCIPAL</a></li>
                  <li><a href="listado_clientes.php">GESTION NIT</a></li>
                  <li><?php echo $_SESSION['Usuario']; ?></li>
                </ul>
              </div> 
              <div class="panel-body">
               <br> 
               <div class="container">
                <div class="row">
                  <div class="span12"> 
                  </div>
                </div> 


                <form action="<?php echo $editFormAction; ?>" method="post" name="form1" onSubmit="MM_validateForm('nit_viejo','','R','nit_nuevo','','R');return document.MM_returnValue" >
                  <table class="table table-bordered table-sm">
                    <tr>
                      <td colspan="3" id="subtitulo">REASIGNAR NIT</td>
                    </tr>
                    <tr>
                      <td id="fuente1">NIT DEL CLIENTE</td>
                      <td colspan="2" id="fuente2">&nbsp;</td>
                    </tr>

                    <tr>
                      <td colspan="3" id="fuente1">Al reasignar un numero de Nit, tambien se modificara el Nit en todas las tablas.</td>
                    </tr>

                    <tr id="tr2">
                      <td colspan="4" id="dato2"><table id="tabla1">
                        <tr>
                          <td id="nivel2">NIT A CAMBIAR</td>
                          <td id="nivel2"><span id="sprytextfield1">
                            <input type="text" name="nit_viejo" id="nit_viejo"onBlur="if (form1.nit_viejo.value) { DatosGestiones('9','nit_viejo',form1.nit_viejo.value); } else{ alert('DEBE INGRESAR UN NIT'); }" value="<?php echo $_GET['nit']; ?>">
                            <span class="textfieldRequiredMsg"></span></span></td>
                          </tr>

                          <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
                            <td id="nivel2">NIT NUEVO</td>
                            <td id="talla2"><span id="sprytextfield2">
                              <input type="text" name="nit_nuevo" id="nit_nuevo"onBlur="if (form1.nit_nuevo.value) { DatosGestiones('8','nit_nuevo',form1.nit_nuevo.value); } else{ alert('DEBE INGRESAR UN NIT NUEVO'); }"onChange="conMayusculas(this)">
                              <span class="textfieldRequiredMsg"></span></span></td>                    
                            </tr>
                            <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
                              <td colspan="2" id="talla2">
                                <div id="resultado"></div>
                              </td>
                            </tr>


                          </table></td>
                        </tr>

                        <tr>
                          <td colspan="3" id="dato1">

                          </td>
                        </tr>
                        <tr>
                          <td colspan="3" id="dato1"></td>
                        </tr>
                        <tr>
                          <td colspan="3" id="dato2"><!--<input type="submit" value="REASIGNAR NIT" onClick="reasignar();">-->
                           <!--  <img src="images/reas.gif" style="cursor:hand;" alt="REASIGNAR NIT" title="REASIGNAR NIT" onClick="reasignar();"/> -->
                           <br>
                           <button type="button" class="botonGeneral" onClick="reasignar();" >REASIGNAR NIT </button>
                         </td>
                       </tr>
                     </table>
                     <input type="hidden" name="MM_update" value="form1">
                   </form></td>
                 </tr>
                 <tr>
                  <td colspan="2" align="center">&nbsp;</td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </td>
</tr>
</table>
</div>
</div>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "none", {validateOn:["change"]});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "none", {validateOn:["change"]});
</script>
</body>
</html>
<?php
mysql_free_result($usuario);mysql_close($conexion1);
/*mysql_free_result($orden_compra);*/
?>