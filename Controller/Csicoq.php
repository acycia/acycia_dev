<?php
//Llamada al modelo
require_once("Models/Msicoq.php");
include('funciones/adjuntar.php');

class CsicoqController
{

  private $ordenc;
  private $proveedores;
  private $insumo;
  private $proformas;

  public function __CONSTRUCT()
  {
    $ordenc = new oSicoq();
  }

  public function Index()
  {
    $ordenc = new oSicoq(); //instanciamos la clase oSicoq del Modelo Msicoq
    self::Msicoq();
  }

  public function Inicio()
  {
    $proveedores = new oSicoq(); //instanciamos la clase oSicoq del Modelo Msicoq
    $insumo = new oSicoq();
    $proceso = new oSicoq();
    $general = new oSicoq();
    $items = new oSicoq();
    $kilosold = new oSicoq();
    $kilosmes = new oSicoq();
    $this->proveedores = $proveedores->get_Provee(); //aqui llamo las funciones del modelo
    $this->insumo = $insumo->get_Insumo($_REQUEST['controladas']);
    $this->proceso = $proceso->get_Proceso();

    
    $this->general = $general->buscarControl('tbl_sicoq ts left join tbl_sicoq_items tsi ', "ts.autorizacion,ts.kilospermitidosmes,ts.kilosdisponiblescompra,ts.totalingresados,ts.totalsalida,ts.totalinventario,ts.fecha_inicio,ts.fecha_vence,ts.userfile", "ts.autorizacion", " ON ts.autorizacion=tsi.autorizacion", " WHERE tsi.controladas='".$_GET['controladas']."'"); //se agrega un consecutivo al numero de autorizacion
    

    $this->kilosmes = $kilosmes->buscarId(' tbl_sicoq ', "kilospermitidosmes ");

    $anyoActual = date('Y');

    $anyoold = date("Y") - 1;
    $anyonew = date("Y");

    $query = "SELECT COUNT(old.factura),old.factura,SUM(old.ingresokilos) AS tingreso,SUM(old.salidakilos) as tsalida, SUM(old.ingresokilos) -SUM(old.salidakilos) as kilosold FROM  tbl_sicoq_items old WHERE (YEAR(old.fecha_recepcion)='$anyoold' OR YEAR(old.fecha_salida)='$anyoold') and old.controladas ='" . $_REQUEST['controladas']."'";
    
    $this->kilosold = $kilosold->multiConsulta($query, "");
    /* $this->kilosold=$kilosold->multiConsulta("SELECT COUNT(old.factura),old.factura,SUM(old.ingresokilos) AS tingreso,SUM(old.salidakilos) as tsalida, SUM(old.ingresokilos) -SUM(old.salidakilos) as kilosold FROM  tbl_sicoq_items old WHERE (YEAR(old.fecha_recepcion)='$anyoold' xor YEAR(old.fecha_salida)='$anyoold') AND  old.factura in (SELECT new.factura FROM tbl_sicoq_items new WHERE YEAR(new.fecha_salida)='$anyonew' and new.controladas = 'SI' GROUP BY new.factura)","");  */

    $this->items = $items->ListaritemsDesc(' tbl_sicoq_items pcd ', "pcd.id_i", " WHERE pcd.controladas='" . $_REQUEST['controladas'] . "' AND (pcd.fecha_recepcion LIKE '%$anyoActual%' OR pcd.fecha_salida LIKE '%$anyoActual%') ");

    $vista = 'view_sicoq.php';

    self::Msicoq($vista);
  }


  public function Menu()
  {
    $ordenc = new oSicoq();
    //$this->ordenc=$ordenc->get_Menu();//aqui llamo las funciones del modelo
    header('Location:menu.php');
  }




  public function Crud()
  {
    $ordenc = new oSicoq();
    if (isset($_REQUEST['id'])) {

      $proveedores = new oSicoq();
      $insumo = new oSicoq();
      $proceso = new oSicoq();
      $general = new oSicoq();
      $items = new oSicoq();
      $kilosold = new oSicoq();
      $kilosmes = new oSicoq();

      $this->proveedores = $proveedores->get_Provee(); //aqui llamo las funciones del modelo
      $this->insumo = $insumo->get_Insumo($_REQUEST['controladas']);
      $this->proceso = $proceso->get_Proceso();

      /*if($_REQUEST['columna']=="nombre"){
                
              $this->items=$items->Listaritems("tbl_sicoq_items"," ". $_REQUEST['columna'] ."='".$_REQUEST['id']."' AND controladas='". $_REQUEST['controladas'] ."' ", " ORDER BY fecha_recepcion DESC " );

            }else */


      if ($_REQUEST['id'] != "" && $_REQUEST['id2'] == "" && $_REQUEST['mes'] == "") {

        $this->items = $items->ObtenerFecha(' tbl_sicoq_items ', " " . '(' . $_REQUEST['columna'] . " ", $_REQUEST['id'], " OR fecha_salida LIKE '%" . $_REQUEST['id'] . "%' ) AND controladas='" . $_REQUEST['controladas'] . "' ");
      } else if ($_REQUEST['id2'] == "" && $_REQUEST['mes'] != "") {

        if ($_REQUEST['id'] == "") {
          $_REQUEST['id'] = date('Y');
        };

        $this->items = $items->ObtenerPorMes(' tbl_sicoq_items ', " " . '(' . $_REQUEST['columna'] . " ", $_REQUEST['id'] . "-" . $_REQUEST['mes'], " OR fecha_salida LIKE '%" . $_REQUEST['id'] . "-" . $_REQUEST['mes'] . "%' ) AND controladas='" . $_REQUEST['controladas'] . "' ");
      } else if ($_REQUEST['id'] == "" && $_REQUEST['id2'] != "") {

        $this->items = $items->ObtenerFecha(' tbl_sicoq_items ', " " . '(' . $_REQUEST['columna2'] . " ", $_REQUEST['id2'], "  AND controladas='" . $_REQUEST['controladas'] . "' ");
      } else {

        $this->items = $items->Obtener(' tbl_sicoq_items ', "  " . $_REQUEST['columna'] . " ", $_REQUEST['id'], " AND controladas='" . $_REQUEST['controladas'] . "' ");
      }

      $this->kilosmes = $kilosmes->buscarId(' tbl_sicoq ', "kilospermitidosmes ");

      $anyoold = date("Y") - 1;
      $anyonew = $_REQUEST['id'];
      $anobuscador =  $anyonew-1;
      /*SELECT COUNT(old.factura),old.factura,SUM(old.ingresokilos) AS tingreso,SUM(old.salidakilos) as tsalida, SUM(old.ingresokilos) -SUM(old.salidakilos) as kilosold FROM  tbl_sicoq_items old WHERE (YEAR(old.fecha_recepcion)='$anyoold' xor YEAR(old.fecha_salida)='$anyoold') AND  old.factura in (SELECT new.factura FROM tbl_sicoq_items new WHERE YEAR(new.fecha_salida)='$anyonew' and new.controladas = 'SI' GROUP BY new.factura)*/

      $query = "SELECT COUNT(old.factura),old.factura,SUM(old.ingresokilos) AS tingreso,SUM(old.salidakilos) as tsalida, SUM(old.ingresokilos) -SUM(old.salidakilos) as kilosold FROM  tbl_sicoq_items old WHERE (YEAR(old.fecha_recepcion)='$anobuscador' OR YEAR(old.fecha_salida)='$anobuscador') and old.controladas ='" . $_REQUEST['controladas']."'";
           
      $this->kilosold = $kilosold->multiConsulta($query, "");
     

      $this->general = $general->Obtener(' tbl_sicoq pc LEFT JOIN tbl_sicoq_items pcd ON pc.autorizacion=pcd.autorizacion ', " pc.anyo", $_REQUEST['id']); //" pc.". $_REQUEST['columna'] ."
    }


    $vista = 'view_sicoq.php';
    self::Msicoq($vista); //le digo que muestre en vista edit
  }




  public function Guardar($vista = '')
  {

    $directorio = ROOT . "pdfsicoq/";
    $nombre = str_replace(' ', '', $_FILES['adjunto']['name']);
    $porciones = explode(".", $nombre);
    $adjunto = "SICOQ" . $_REQUEST['autorizacion'] . "." . $porciones[1];
    $tieneadjunto1 = adjuntarArchivo('', $directorio, $adjunto, $_FILES['adjunto']['tmp_name'], 'NUEVOS');


    $this->proformas =  new oSicoq();

    if ($nombre != '') {
      $tieneadjunto1 = adjuntarArchivo('', $directorio, $adjunto, $_FILES['adjunto']['tmp_name'], 'NUEVOS');
      $_REQUEST['userfile'] = $tieneadjunto1;
    } else {
      $tieneadjunto2 = adjuntarArchivo($_REQUEST['userfile'], $directorio, $_FILES['adjunto']['tmp_name'], $_FILES['adjunto']['tmp_name'], 'UPDATES');
      $_REQUEST['userfile'] = $tieneadjunto2;
    }

    $autorizacion = $_REQUEST['autorizacion']  == '' ? $_REQUEST['ccit_autorizacion'] : $_REQUEST['autorizacion']; //cuando se consulta año anterior ya que no se puede agregar el numero de autorizacion en el filtro

    $this->general = array('autorizacion' => $autorizacion, 'anyo' => $_REQUEST['anyo'], 'kilospermitidosmes' => $_REQUEST['kilospermitidosmes'], 'kilosdisponiblescompra' => $_REQUEST['kilosdisponiblescompra'], 'totalingresados' => $_REQUEST['totalingresados'], 'totalsalida' => $_REQUEST['totalsalida'], 'totalinventario' => $_REQUEST['totalinventario'], 'fecha_inicio' => $_REQUEST['fecha_inicio'], 'fecha_vence' => $_REQUEST['fecha_vence'], 'userfile' => $_REQUEST['userfile']);

    $this->items = array('nombre' => $_REQUEST['nombre'], 'ingresokilos' => $_REQUEST['ingresokilos'], 'fecha_recepcion' => $_REQUEST['fecha_recepcion'], 'proveedor' => $_REQUEST['proveedor'], 'costound' => $_REQUEST['costound'], 'factura' => $_REQUEST['factura'], 'area' => $_REQUEST['area'], 'fecha_salida' => $_REQUEST['fecha_salida'], 'salidakilos' => $_REQUEST['salidakilos'], 'numeradora' => $_REQUEST['numeradora'], 'autorizacion' => $autorizacion, 'ccit_autorizacion' => $autorizacion, 'op' => $_REQUEST['op'], 'controladas' => $_REQUEST['controladas'], 'responsable' => $_REQUEST['responsable'], 'revisado' => $_REQUEST['revisado'], 'aprobado' => $_REQUEST['aprobado'], 'modificado' => $_REQUEST['modificado']);


    $respuesta = $this->proformas->Registrar("tbl_sicoq", "autorizacion,anyo,kilospermitidosmes,kilosdisponiblescompra,totalingresados,totalsalida,totalinventario,fecha_inicio,fecha_vence,userfile", $this->general);

    $this->proformas->RegistrarItems("tbl_sicoq_items", "nombre,ingresokilos,fecha_recepcion,proveedor,costound,factura,area,fecha_salida,salidakilos,numeradora,autorizacion,ccit_autorizacion,op,controladas,responsable,revisado,aprobado,modificado", $this->items);

    if ($_REQUEST['alert']) {

      echo $respuesta;
      die; //para q norecargue y muestre el alert 
    }

    header('Location:view_index.php?c=csicoq&a=Crud&columna=autorizacion&id=' . $autorizacion . '&controladas=' . $_REQUEST['controladas']);
  }



  public function Editar()
  {


    $this->sicoq =  new oSicoq();
    $this->sicoqs = $_REQUEST;
    //ids,valorid,valores,tabla,url  
    $respuesta =  $this->logs->Update($_REQUEST['tabla'], $this->sicoqs);
    echo $respuesta;
    //$respuesta =  $this->logs->Update($_REQUEST['ids'],$_REQUEST['valorid'],$_REQUEST['name'],$_REQUEST['valorc'],$_REQUEST['tabla']);  
    //header("Location:view_index.php?c=csicoq&a=Crud&columna=". $_REQUEST['columna'] ."&id=". $_REQUEST['id'] ."&controladas=" . $_REQUEST['controladas']. " ");
  }


  public function Eliminar()
  {


    $this->sicoq =  new oSicoq();
    $this->sicoqs = $_REQUEST;
    $this->sicoq->Delete("tbl_sicoq_items", $_REQUEST['id'], $_REQUEST['columna'], $_REQUEST['proceso'], $_REQUEST['master']);

    header("Location:view_index.php?c=csicoq&a=Crud&columna=" . $_REQUEST['columna'] . "&id=" . $_REQUEST['id'] . "&controladas=" . $_REQUEST['controladas'] . " ");
  }

  public function Msicoq($vista = '')
  {
    if ($vista) {
      require_once("views/" . $vista);  //header('Location:'.$vista);  
    } else {
      require_once("views/view_sicoq.php");
    }
  }
}
