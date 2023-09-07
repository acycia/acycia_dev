<?php 
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
require_once('AjaxControllers/Actions/funcioness.php');
 
 require_once('Connections/conexion1.php'); ?>
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
 


session_start(); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title>SISADGE AC &amp; CIA</title>
 
  <link href="css/formato.css" rel="stylesheet" type="text/css" />
  <link href="css/desplegable.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="AjaxControllers/updateConAlert.js"></script>
  <!-- <script type="text/javascript" src="js/formato.js"></script>  -->
  <!-- jquery -->
  <script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
  <script src="//code.jquery.com/jquery-1.11.2.min.js"></script> 

  <!-- sweetalert -->
  <script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
  <link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">

  <link rel="stylesheet" type="text/css" href="css/general.css"/>
</head>
<body>
  <div align="center">
    <table id="tabla"><tr align="center"><td align="center">
      <div class="spiffy_content">
        <div class="row-fluid">
          <div class="span8 offset2"> <!--span8 offset2   esto da el tamaño pequeño -->
            <div class="panel panel-primary">
              <div class="panel-heading">TICKET PARA CAJAS</div>
              <div class="panel-body">

                <table id="tabla1"><tr><td align="center"><img src="images/cabecera.jpg"></td></tr>
                  <tr><td id="cabezamenu">
                    <ul id="menuhorizontal">
                      <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                      <li><a href="menu.php">MENU PRINCIPAL</a></li>
                      <li><a href="despacho_numeracionxcajas.php">NUMERACION X CAJA</a></li>
                      <li><a href="despacho_listado1_oc.php">LISTADO REMISIONES</a></li>
                      <li><a href="orden_compra_cl2.php">LISTADO O.C</a></li>
                      <!-- <li><a href="/acycia/dropboxacycia/">VER ARCHIVOS</a></li>
                       <li><a href="/acycia/agregar_tickets.php">AGREGAR ticketS</a></li> -->
                   </ul>
                     <div id="nombreusuario"><?php echo $_SESSION['Usuario']; ?></div>
                          </td>
                                  </tr>
                                  <tr>
                                    <td id="subtitulo">&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td id="subtitulo">GENERAR TICKET<br><br></td>
                                  </tr>
                                  <tr>
                                    <td id="subtitulo">
                                     <!--  <select name="tipo" id="tipo" onchange="defineConsecutivo()" >
                                         <option value="TCC">TCC</option>
                                         <option value="OTROS">OTROS</option> 
                                       </select>   --><br><br>
                                    </td>
                                   </tr>
                                  <tr>        
                                    <td id="numero2"> 
                                    NUMERACION INICIAL: <input name="nuninicio" id="nuninicio" type="text" value="S/N" required="required" > <br><br>
                                    ORDEN COMPRA: <input  name="ocompra" id="ocompra" type="text" value="" required="required" >
                                    REFERENCIA: <input  name="ref" id="ref" type="text" value="" required="required" ><br><br>
                                    CUANTAS CAJAS: <input name="cajas" id="cajas" type="text" value="" required="required" >  
                                    UNIDADES X CAJA: <input name="undxcajas" id="undxcajas" type="text" value="" required="required" ><br><br> 
                                    </td>
                                  </tr> 
                                  <tr>
                                    <td align="center"> 
                                        <div align="center">
                                          <table width="50%">
                                            <tr id="tr1">
                                              <td width="25%" id="titulo4">  </td>
                                              <td width="25%" id="titulo4">  </td>
                                            </tr>
                                            <tr id="tr3"> 
                                              <button id="btnEnviarItems" onclick="javascript:envioAlert();" name="btnEnviarItems" type="button" class="botonGeneral" autofocus="" >CLIC PARA GENERAR NUMERACION</button>  
                                            </tr>
                                            <tr>
                                              <td> &nbsp;</td>
                                            </tr> 
                                            <tr id="tr3">
                                              <td  id="dato2" style="text-align: left;" >  </td>

                                              <td colspan="2" id="dato2"> </td>

                                            </tr>
                                          </table>
                                        </div>

                                      </div>
                                    </div>
                                  </div>


                              </div>
                           
                      </td>
                  </tr>
                  <br><br>
              </table>
          </div>
      </div> 
  </td>
</tr>

</table>
</div>
</body>
</html>
 <script type="text/javascript" >
 
    function envioAlert(){ 
 
     
       var nuninicio = document.getElementById("nuninicio").value; 
       var ocompra = document.getElementById("ocompra").value;  
       var ref = document.getElementById("ref").value; 
       var cajas = document.getElementById("cajas").value; 
       var undxcajas = document.getElementById("undxcajas").value; 
        

      if( nuninicio!='' && ocompra !='' && cajas !='' && undxcajas!=''){
       swal({   
        title: "NUMERACION?",   
        text: "Esta seguro que Quiere Generar" ,   
        type: "warning",   
        showCancelButton: true,   
        confirmButtonColor: "#DD6B55",   
        confirmButtonText: "Si, Generado!",   
        cancelButtonText: "No, Generado!",   
        closeOnConfirm: false,   
        closeOnCancel: false }, 
        function(isConfirm){   
          if (isConfirm) {  
            swal("Continuar!", "El registro se ha Generado.", "success"); 
            window.location.href = "despacho_numeracionxcajas2.php?nuninicio="+nuninicio+'&ref='+ref+'&ocompra='+ocompra+'&cajas='+cajas+'&undxcajas='+undxcajas; 
          } else {     
            swal("Cancelado", "has cancelado :)", "error"); 
          } 
        });  

     }else{
         swal("Error", "Campos vacios :)", "error");
     }

    }
 

 </script>