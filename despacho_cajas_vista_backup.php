<?php require_once('Connections/conexion1.php'); ?><?php
if (!isset($_SESSION)) {
    session_start();
}

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
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("", $MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {
    $MM_qsChar = "?";
    $MM_referrer = $_SERVER['PHP_SELF'];
    if (strpos($MM_restrictGoTo, "?"))
        $MM_qsChar = "&";
    if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0)
        $MM_referrer .= "?" . $QUERY_STRING;
    $MM_restrictGoTo = $MM_restrictGoTo . $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
    header("Location: " . $MM_restrictGoTo);
    exit;
}
?>
<?php
//LLAMADO A FUNCIONES
include('funciones/funciones_php.php'); //SISTEMA RUW PARA LA BASE DE DATOS 
//FIN
if (!function_exists("GetSQLValueString")) {

    function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") {
        if (PHP_VERSION < 6) {
            $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
        }

        $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

        switch ($theType) {
            case "text":
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;
            case "long":
            case "int":
                $theValue = ($theValue != "") ? intval($theValue) : "NULL";
                break;
            case "double":
                $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
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
//ORDEN DE PRODUCCION
$colname_op = "-1";
if (isset($_GET['id_op'])) {
    $colname_op = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_refac_refcl = sprintf("SELECT id_op,int_cod_ref_op,int_cliente_op,version_ref_op,int_ref_ac_rc,str_descripcion_rc,str_ref_cl_rc FROM Tbl_orden_produccion, Tbl_refcliente WHERE Tbl_orden_produccion.id_op=%s AND Tbl_orden_produccion.int_cod_ref_op=Tbl_refcliente.int_ref_ac_rc AND Tbl_orden_produccion.int_cliente_op=Tbl_refcliente.id_c_rc", $colname_op);
$refac_refcl = mysql_query($query_refac_refcl, $conexion1) or die(mysql_error());
$row_refac_refcl = mysql_fetch_assoc($refac_refcl);
$totalRows_refac_refcl = mysql_num_rows($refac_refcl);

$colname_op = "-1";
if (isset($_GET['id_op'])) {
    $colname_op = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_refac = sprintf("SELECT id_op,int_cod_ref_op,version_ref_op FROM Tbl_orden_produccion WHERE id_op=%s", $colname_op);
$refac = mysql_query($query_refac, $conexion1) or die(mysql_error());
$row_refac = mysql_fetch_assoc($refac);
$totalRows_refac = mysql_num_rows($refac);

$colname_op = "-1";
if (isset($_GET['id_op'])) {
    $colname_op = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
$colname_p = "-1";
if (isset($_GET['int_caja_tn'])) {
    $colname_p = (get_magic_quotes_gpc()) ? $_GET['int_caja_tn'] : addslashes($_GET['int_caja_tn']);
}

//SELECT * FROM Tbl_tiquete_numeracion_backup WHERE int_op_tn=%s AND int_caja_tn=%s ORDER BY id_tn ASC
mysql_select_db($database_conexion1, $conexion1);
$query_vista_paquete = sprintf("SELECT int_op_tn, int_caja_tn, int_caja_tn, fecha_ingreso_tn, int_desde_tn, int_undxcaja_tn, int_cod_empleado_tn FROM Tbl_tiquete_numeracion_backup WHERE int_op_tn=%s AND int_caja_tn=%s ORDER BY int_paquete_tn ASC LIMIT 1", $colname_op, $colname_p);
$vista_paquete = mysql_query($query_vista_paquete, $conexion1) or die(mysql_error());
$row_vista_paquete = mysql_fetch_assoc($vista_paquete);
$totalRows_vista_paquete = mysql_num_rows($vista_paquete);

$colname_MAX = "-1";
if (isset($_GET['id_op'])) {
    $colname_MAX = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
$colname_MAX2 = "-1";
if (isset($_GET['int_caja_tn'])) {
    $colname_MAX2 = (get_magic_quotes_gpc()) ? $_GET['int_caja_tn'] : addslashes($_GET['int_caja_tn']);
}
//SELECT * FROM Tbl_tiquete_numeracion_backup WHERE int_op_tn=%s AND int_caja_tn=%s ORDER BY int_paquete_tn DESC
mysql_select_db($database_conexion1, $conexion1);
$query_vista_MAX = sprintf("SELECT int_hasta_tn FROM Tbl_tiquete_numeracion_backup WHERE int_op_tn=%s AND int_caja_tn=%s ORDER BY int_paquete_tn DESC LIMIT 1", $colname_MAX, $colname_MAX2);
$vista_MAX = mysql_query($query_vista_MAX, $conexion1) or die(mysql_error());
$row_vista_MAX = mysql_fetch_assoc($vista_MAX);
$totalRows_vista_MAX = mysql_num_rows($vista_MAX);
 
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>SISADGE AC & CIA</title>
        <link href="css/vista.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="js/vista.js"></script>
        <script type="text/javascript" src="js/formato.js"></script>
        <script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>    
        <script type="text/javascript" src="js/jquery-barcode-last.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css"> 
        <!--IMPRIME CODIGO DE BARRAS-->
        <script type="text/javascript">
            /*type (string) TIPOS DE CODIGO DE BARRAS PARA USAR
             codabar
             code11 (code 11)
             code39 (code 39)
             code93 (code 93)
             code128 (code 128)
             ean8 (ean 8)
             ean13 (ean 13)
             std25 (standard 2 of 5 - industrial 2 of 5)
             int25 (interleaved 2 of 5)
             msi
             datamatrix (ASCII + extended)
             
             //OBJETOS
             barWidth
             barHeight
             */
            $(document).ready(function () {
                var codigo = "<?php $var = $row_vista_paquete['int_op_tn'] . "-" . $row_vista_paquete['int_caja_tn'];
echo $var; ?>";
                var codigo2 = "770-771-1-<?php echo $row_refac['int_cod_ref_op']; ?>-1";
                $("#bcTarget").barcode(codigo2, "code128", {barWidth: 1, barHeight: 20});
                //$("#bcTarget").barcode("1234567", "int25"); 
            });
        </script>
        <!--IMPRIME AL CARGAR POPUP-->
        <SCRIPT language="javascript">
            function imprimir()
            {
                if ((navigator.appName == "Netscape")) {
                    window.print();
                }
                else
                {
                    var WebBrowser = '<OBJECT ID="WebBrowser1" WIDTH=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></OBJECT>';
                    document.body.insertAdjacentHTML('beforeEnd', WebBrowser);
                    WebBrowser1.ExecWB(6, -1);
                    WebBrowser1.outerHTML = "";
                }
            }
        </SCRIPT>
        <style type="text/css">

            #oculto {
                display:none;

            }
        </style> 
        <script>
            function cerrar(num) {
                window.close()
            }
        </script>
    </head>
    <body onLoad="imprimir();">
        <div align="center" id="seleccion"  onClick="cerrar('seleccion');"><!--onClick="javascript:imprSelec('seleccion')"-->
            <div class="container-fluid" id="bordeRedondo"> 
                <div class="row" id="div-1">
                    <div class="col-md-9">
                        <h5 align="center">AC & CIA <!--<div id="div-1a"><img src="images/qrcodigo.png" /><img src="images/qrcodigo.png" /></div>-->
                            <br><b>CONTROL DE NUMERACION</b></h5>
                        
<!--                        <hr> -->
                        <div class="col-md-6">
                            <div id="div-1a">
                                <p align="right"><FONT SIZE="3">CAJA:</FONT> </p>
                                <p><?php $caja = $row_vista_paquete['int_caja_tn'];
echo $caja; ?></p>
                            </div>
                            <b>FECHA: </b><?php echo $row_vista_paquete['fecha_ingreso_tn']; ?> /<?php echo Hora(); ?></div>
                        <div class="col-md-6">
                            <b>DESDE: </b><?php echo $row_vista_paquete['int_desde_tn']; ?></div>
                        <div class="col-md-6">
                            <b>HASTA: </b><?php echo $row_vista_MAX['int_hasta_tn']; ?></div>
                        <div class="col-md-6"><b>REF. CLIENTE</b>: 
<?php if ($row_refac_refcl['str_ref_cl_rc'] == '') {
    echo "N.A";
} else {
    echo $row_refac_refcl['str_ref_cl_rc']; ?>
                            </div>
                            <div class="col-md-6" id="fuentND"><?php echo $row_refac_refcl['str_descripcion_rc'];
}
?></div>
                        <div class="col-md-6">
                            <b>REF: </b><?php echo $row_refac['int_cod_ref_op'] . "-" . $row_refac['version_ref_op']; ?></div>         
                        <div class="col-md-6">
                            <b>CANT: </b><?php echo $row_vista_paquete['int_undxcaja_tn']; ?></div>
                        <div class="col-md-6">
                            <b>COD. EMPL:<?php echo $row_vista_paquete['int_cod_empleado_tn']; ?></b> 
                        </div>
                        <br>
                    </div>
                </div>
            </div> 

        </div>
        <div id="oculto">
            <table width="200" border="0" align="center">
                <tr>
                    <td><input name="cerrar" type="button" autofocus value="cerrar"onClick="cerrar('seleccion');
                            return false" ></td>
                </tr>
            </table>
        </div>
    </body>

</html>
<?php

mysql_free_result($usuario);

mysql_free_result($vista_paquete);

mysql_free_result($vista_MAX);

mysql_free_result($refac_refcl);

mysql_free_result($refac );


?>
