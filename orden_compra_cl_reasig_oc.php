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

$conexion = new ApptivaDB();

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$oc_vieja=$_POST["oc_vieja"];
$oc_nueva=$_POST["oc_nueva"];
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
//REASIGNAR
  if($oc_vieja!=''&&$oc_nueva!=''){
    mysql_select_db($database_conexion1, $conexion1);
    $sqlnit="SELECT * FROM Tbl_orden_compra WHERE str_numero_oc='$oc_nueva'";
    $resultnit=mysql_query($sqlnit);
    $numnit= mysql_num_rows($resultnit);	
    if($numnit >='1')
    {
      {echo "<script type=\"text/javascript\">alert(\"El numero nuevo de O.C que quiere Reasignar YA existe!\");</script>";
    }
  }else{$sqlreas="SELECT * FROM Tbl_orden_compra WHERE str_numero_oc='$oc_vieja'";
  $resultreas=mysql_query($sqlreas);
  $str_numero_oc=mysql_result($resultreas,0,'str_numero_oc');
/*$interno=explode ('-',$str_numero_oc);
$inter=$interno[0];
if($inter=="AC-"){$int='1';}else{$int='0';}*/
$sqloc="UPDATE Tbl_orden_compra SET str_numero_oc='$oc_nueva',b_oc_interno='0' WHERE str_numero_oc='$str_numero_oc'";
$resultoc=mysql_query($sqloc);

/*$sqlioc="UPDATE Tbl_items_ordenc SET str_numero_io='$oc_nueva' WHERE str_numero_io='$str_numero_oc'";
$resultioc=mysql_query($sqlioc);*/

$sqlr="UPDATE Tbl_remisiones SET str_numero_oc_r='$oc_nueva' WHERE str_numero_oc_r='$str_numero_oc'";
$resultr=mysql_query($sqlr);

$sqlrd="UPDATE Tbl_remision_detalle SET str_numero_oc_rd='$oc_nueva' WHERE str_numero_oc_rd='$str_numero_oc'";
$resultrd=mysql_query($sqlrd);

$sqlop="UPDATE Tbl_orden_produccion SET str_numero_oc_op='$oc_nueva' WHERE str_numero_oc_op='$str_numero_oc'";
$resultop=mysql_query($sqlop); 

/*echo "<script type=\"text/javascript\">alert(\"LA O.C $oc_vieja fue modificada por la O.C $oc_nueva\");</script>";*/
$guardado='1';
$oc_nueva;
$oc_vieja;
}
}
}
$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);
?>
<html>
<head>
  <title>SISADGE AC &amp; CIA</title>
  <link href="css/formato.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="js/formato.js"></script>
  <script type="text/javascript" src="js/consulta.js"></script>
  <script type="text/javascript" src="js/validacion_numerico.js"></script>

  <link rel="stylesheet" type="text/css" href="css/desplegable.css" />
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
    var ocv = document.form1.oc_vieja.value;
    var ocn = document.form1.oc_nueva.value;
    if (ocv =='' || ocn=='')
      {alert("Uno de los dos campos esta vacio");}else
    {	
      var statusConfirm = confirm("Esta seguro de Cambiar el numero de O.C: "+ocv+" por el numero O.C: "+ocn+" ?");
      if (statusConfirm == true)
      {
       document.form1.submit()
     }
   }
 }
 <!--FIN-->
</script>
<script language="JavaScript" type="text/javascript">
  var ocv= "<?php echo $oc_vieja; ?>" ; 
  var ocn= "<?php echo $oc_nueva; ?>" ; 
  var guardado = "<?php echo $guardado; ?>" ;
  function alerta() {
    if(guardado=='1'){
      alert("LA O.C "+ocv+" fue modificado por la O.C "+ocn+"");
    }
  }
  window.onload = alerta;
</script>
</head>
<body>
<?php echo $conexion->header('listas'); ?>
                  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" onSubmit="MM_validateForm('oc_vieja','','R','oc_nueva','','R');return document.MM_returnValue" >
                    <table id="tabla1">
                      <tr>
                        <td colspan="3" id="subtitulo">REASIGNAR O.C. </td>
                      </tr>
                      <tr>
                        <td id="fuente1">ORDEN DE COMPRA </td>
                        <td colspan="2" id="fuente2"><a href="orden_compra_cl.php"><img src="images/o.gif" alt="ORDENES DE COMPRA" title="ORDENES DE COMPRA" border="0" style="cursor:hand;"/></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" title="MENU PRINCIPAL" border="0"/></a></td>
                      </tr>

                      <tr>
                        <td colspan="3" id="fuente1">Al reasignar un numero de Orden de Compra, tambien se modificara las Remisiones creadas para esta O.C.</td>
                      </tr>

                      <tr id="tr2">
                        <td colspan="4" id="dato2"><table id="tabla1">
                          <tr>
                            <td id="nivel2">O.C A CAMBIAR</td>
                            <td id="nivel2"><span id="sprytextfield1">
                              <input type="text" name="oc_vieja" id="oc_vieja" onBlur="if (form1.oc_vieja.value) { DatosGestiones('7','oc_vieja',form1.oc_vieja.value); } else{ alert('DEBE INGRESAR UN NUMERO O.C'); }">
                              <span class="textfieldRequiredMsg"></span></span></td>
                            </tr>

                            <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
                              <td id="nivel2">NUEVA O.C.</td>
                              <td id="talla2"><span id="sprytextfield2">
                                <input type="text" name="oc_nueva" id="oc_nueva"onChange="conMayusculas(this)">
                                <span class="textfieldRequiredMsg"></span></span></td>                    
                              </tr>
                              <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
                                <td colspan="2" id="talla2">
                                  <div id="resultado"></div> </td>
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
                              <td colspan="3" id="dato2"><img src="images/reas.gif" class="botonGeneral"  style="cursor:hand;" alt="REASIGNAR O.C." title="REASIGNAR O.C." onClick="reasignar();"/></td>
                            </tr>
                          </table>
                          <input type="hidden" name="MM_update" value="form1">
                        </form></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="center">&nbsp;</td>
                      </tr>
                    </table>
                 <?php echo $conexion->header('footer'); ?>
<script type="text/javascript">
  var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "none", {validateOn:["change"]});
  var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "none", {validateOn:["change"]});
</script>
</body>
</html>
<?php
mysql_free_result($usuario);
/*mysql_free_result($orden_compra);*/
?>