<?php

//Llamada al modelo
include("./Models/Msolicitud_compras.php");

class Csolicitud_comprasController
{


    public function guardarDatos()
    {

        $objMsolicitud = new Msolicitud_compras($_POST['id'], $_POST['id_solicitud'], strtoupper($_POST['area']), strtoupper($_POST['nombre']), $_POST['fecha'], strtoupper($_POST['maquina']), strtoupper($_POST['observaciones']), strtoupper($_POST['responsable']), strtoupper($_POST['autorizado']), strtolower($_POST['correo']));

        $idGenerado = $objMsolicitud->guardarSolicitud();

        if ($idGenerado != false) {
            $res2 = $objMsolicitud->guardarItems($idGenerado, $_REQUEST['insumo'], $_REQUEST['cantidad'], $_REQUEST['oc'], $_REQUEST['estado']);
            
            if ($res2 == 1) {
                $alerta = 2;
                
                
                header('Location:' . 'view_index.php?c=Csolicitud_compras&a=inicioListado&alerta='.$alerta);
                //$objMsolicitud->enviarEmail($_REQUEST['insumo'], $_REQUEST['cantidad']);
            } else {
                echo '<script language="javascript">alert("!Error¡ No fue posible Guardar los Items");</script>';
            }
        } else {
            echo '<script language="javascript">alert("!Error¡ No fue posible Guardar los datos");</script>';
        }
    }

    public function actualizarDatos()
    {

        $objMsolicitud = new Msolicitud_compras($_POST['id_solicitud'], $_POST['codigo'], strtoupper($_POST['area']), strtoupper($_POST['nombre']), $_POST['fecha'], strtoupper($_POST['maquina']), strtoupper($_POST['observaciones']), strtoupper($_POST['responsable']), strtoupper($_POST['autorizado']), strtolower($_POST['correo']));
        $res = $objMsolicitud->actualizarDatos();

        if ($res == 1) {
            $res2 = $objMsolicitud->actualizarItems($_REQUEST['id'], $_REQUEST['insumo'], $_REQUEST['cantidad'], $_REQUEST['oc'], $_REQUEST['estado']);
            if ($res2 == 1) {
                $alerta = 1;
                
                header('Location:' . 'view_index.php?c=Csolicitud_compras&a=inicioListado&alerta='.$alerta);
            } else {
                echo '<script language="javascript">alert("!Error¡ No fue posible Guardar los Items");</script>';
            }
        } else {
            echo '<script language="javascript">alert("!Error¡ No fue posible Actualizar los datos");</script>';
        }
    }

    public function CtraerConsecutivo()
    {
        $objConsecutivo = new Msolicitud_compras($_POST['id'], $_POST['id_solicitud'], $_POST['area'], $_POST['nombre'], $_POST['fecha'], $_POST['maquina'], $_POST['observaciones'], strtoupper($_POST['responsable']), strtoupper($_POST['autorizado']), strtolower($_POST['correo']));
        $actualYear = date('Y');
        $number = $objConsecutivo->traerConsecutivo();
        if (!isset($number)) {
            $newNumber = 1;
            $arrayCodigo[0] = $actualYear;
        } else {
            $arrayCodigo = explode("-", $number['codigo']);
            if ($arrayCodigo[0] == $actualYear) {
                $newNumber = $arrayCodigo[1] + 1; /* añadir +1 al ultimo consecutivo de la base de datos */
            } else {
                $arrayCodigo[0] = $actualYear;
                $newNumber = 1;
            }
        }
        return [
            "id_solicitud" => $number['id_solicitud'],
            "codigo" => ($arrayCodigo[0] . "-" . $newNumber)
        ];
    }

    public function Inicio()
    {
        $vista = 'view_solicitud_compras.php';
        self::Cvista($vista);
    }

    public function inicioListado()
    {
        $vista = 'view_solicitud_compras_listado.php';
        self::Cvista($vista);
    }

    public function inicioEdit()
    {
        $vista = 'view_solicitud_compras_edit.php';
        self::Cvista($vista);
    }

    public function Cvista($vista = '')
    {
        if ($vista) {
            require_once("views/" . $vista);  //header('Location:'.$vista);  
        } else {
            require_once("views/view_solicitud_compras.php?");
        }
    }

}


