<?php
//Llamada al modelo
require_once("Models/CcomprasLQ.php");
include('funciones/adjuntar.php'); 
/* 

$ordenc=new oComprasLQ();
$datos=$ordenc->get_ordenc();
require_once("views/view_compras.php");*/

class ComprasLQController{

	private $ordenc;
    private $proveedores;
    private $insumo;
    private $proformas;
    private $items;

	public function __CONSTRUCT(){
		$ordenc = new oComprasLQ();
    }

    public function Index(){ 
    	$ordenc = new oComprasLQ();//instanciamos la clase oComprasLQ del Modelo CcomprasLQ
    	self::CcomprasLQ();
    }
 
    public function Liquidacion(){ 
        $proveedores = new oComprasLQ();//instanciamos la clase oComprasLQ del Modelo CcomprasLQ
        $insumo = new oComprasLQ();
        $ordenc = new oComprasLQ();
        $proformas = new oComprasLQ();
        $general = new oComprasLQ();
        $items = new oComprasLQ();
        $this->proveedores=$proveedores->get_Provee();//aqui llamo las funciones del modelo
        $this->insumo=$insumo->get_Insumo(); 

        $fechainicial = date("Y-m-01");
        $fechafinal = date("Y-m-31");

        /* para imprimir todas las facturas del mes actual
        $this->general=$general->get_SelectDepend(" tbl_proceso_compras pc LEFT JOIN tbl_proceso_compras_liquidacion pcl ON pc.factura=pcl.factura "," * ", "  WHERE pc.fecha BETWEEN '$fechainicial' AND '$fechafinal'", " ORDER BY pc.proveedor ASC ", " GROUP BY pc.factura" ); 
        $this->items=$items->get_SelectDepend(" tbl_proceso_compras pc LEFT JOIN tbl_proceso_compras_liquidacion pcl ON pc.factura=pcl.factura "," * ", "  WHERE pc.fecha BETWEEN '$fechainicial' AND '$fechafinal'", " ORDER BY pc.proveedor ASC ", " GROUP BY pc.factura" ); 
*/
        self::CcomprasLQ();
    }


    public function Menu(){ 
        $ordenc = new oComprasLQ();
        //$this->ordenc=$ordenc->get_Menu();//aqui llamo las funciones del modelo
        $vista = 'view_submenu_compras.php';
        self::CcomprasLQ($vista);
    }


    public function Selectes(){
     
        if(isset($_REQUEST['id_category']) && $_REQUEST['id_category'] !='' ){
            $facturas = new oComprasLQ();  
            $this->facturas = new oComprasLQ();
           
           if($_REQUEST['columna'] =='factura'){ 

            if($_REQUEST['fecha'] != ''){
               $fechainicial = $_REQUEST['fecha']."-01";
               $fechafinal = date($_REQUEST['fecha']."-31");
               $columFecha = "AND pc.fecha BETWEEN '$fechainicial' AND '$fechafinal' ";
            }

          
            $this->facturas=$facturas->get_SelectDepend("  tbl_proceso_compras pc LEFT JOIN tbl_proceso_compras_liquidacion pcl ON pc.". $_REQUEST['columna'] ."=pcl.". $_REQUEST['columna'] ." "," pc.factura as factura, pc.fecha as fecha", " WHERE pc.". $_REQUEST['nombrecolum'] ."='". $_REQUEST['id_category'] ."'  $columFecha ", " ORDER BY pc.". $_REQUEST['columna'] ." DESC,pc.fecha DESC ", " GROUP BY pc.". $_REQUEST['columna'] ." " ); 
            
           }else if($_REQUEST['columna'] =='pedido'){
           
            $this->facturas=$facturas->get_SelectSubDepend("  tbl_proceso_compras pc LEFT JOIN tbl_proceso_compras_liquidacion pcl ON pc.". $_REQUEST['columna'] ."=pcl.". $_REQUEST['columna'] ." "," pc.pedido ", " WHERE pc.". $_REQUEST['nombrecolum'] ."='". $_REQUEST['id_category'] ."' $columFecha", "ORDER BY pc.". $_REQUEST['columna'] ." DESC,pc.fecha DESC", " GROUP BY pc.". $_REQUEST['columna'] ." " );
           }

        }
    }

 /*   public function Selectes(){
     
        if(isset($_REQUEST['id_category']) && $_REQUEST['id_category'] !='' ){
            $facturas = new oComprasLQ();  
            $this->facturas = new oComprasLQ();
           
           if($_REQUEST['columna'] =='factura'){ 
            $fechainicial = $_REQUEST['fecha']."-01";
            $fechafinal = date($_REQUEST['fecha']."-31");
             
            $this->facturas=$facturas->get_SelectDepend("  tbl_proceso_compras pc LEFT JOIN tbl_proceso_compras_liquidacion pcl ON pc.". $_REQUEST['columna'] ."=pcl.". $_REQUEST['columna'] ." "," pc.fecha as fecha,pc.factura as factura ", " WHERE pc.". $_REQUEST['nombrecolum'] ."='". $_REQUEST['id_category'] ."'  AND pc.fecha BETWEEN '$fechainicial' AND '$fechafinal' ", " ORDER BY pc.". $_REQUEST['columna'] ." ASC ", " GROUP BY pc.". $_REQUEST['columna'] ." " ); 
            
           }else if($_REQUEST['columna'] =='pedido'){
           
            $this->facturas=$facturas->get_SelectSubDepend("  tbl_proceso_compras pc LEFT JOIN tbl_proceso_compras_liquidacion pcl ON pc.". $_REQUEST['columna'] ."=pcl.". $_REQUEST['columna'] ." "," pc.pedido ", " WHERE pc.". $_REQUEST['nombrecolum'] ."='". $_REQUEST['id_category'] ."' ", "ORDER BY pc.". $_REQUEST['columna'] ." ASC", " GROUP BY pc.". $_REQUEST['columna'] ." " );
           }

        }
    }*/

    public function Crud(){
    	$ordenc = new oComprasLQ(); 
    	if(isset($_REQUEST['id'])){
            $proveedores = new oComprasLQ();//instanciamos la clase oComprasLQ del Modelo CcomprasLQ
            $insumo = new oComprasLQ();
            $maquina = new oComprasLQ();
            $general = new oComprasLQ();
            $proformas = new oComprasLQ();
            $items = new oComprasLQ();
            $this->proveedores=$proveedores->get_Provee();//aqui llamo las funciones del modelo
            $this->insumo=$insumo->get_Insumo();
            $this->maquina=$maquina->get_Maquina();
 

            $this->general=$general->Obtener("  tbl_proceso_compras pc LEFT JOIN tbl_proceso_compras_liquidacion pcl ON pc.". $_REQUEST['columna']."=pcl.". $_REQUEST['columna']." ", "pc.proceso='ENTRADA MERCANCIA' AND pc.". $_REQUEST['columna'] ." ",$_REQUEST['id']);
            $this->items=$items->Obtener(' tbl_proceso_compras_detalle pcd',"pcd.proceso='ENTRADA MERCANCIA' AND pcd.". $_REQUEST['columna'] ." ",$_REQUEST['id']);

 
            if(!$this->general)
                $this->general=$general->Obtener(' tbl_proceso_compras pc INNER JOIN tbl_proceso_compras_detalle pcd ON pc.proforma=pcd.proforma'," pc.". $_REQUEST['columna'] ." <>'' AND pc.proceso='ENTRADA MERCANCIA' AND pc.proceso=pcd.proceso  AND pc.". $_REQUEST['columna'] ." ",$_REQUEST['id']);
            if(!$this->items)
                $this->items=$items->Obtener(' tbl_proceso_compras_detalle pcd',"pcd.proceso='ENTRADA MERCANCIA' AND pcd.". $_REQUEST['columna'] ." ",$_REQUEST['id']);

            if(!$this->general)
                $this->general=$general->Obtener(' tbl_proceso_compras pc INNER JOIN tbl_proceso_compras_detalle pcd ON pc.proforma=pcd.proforma'," pc.". $_REQUEST['columna'] ." <>'' AND pc.proceso='DETALLE EMBARQUE' AND pc.proceso=pcd.proceso  AND pc.". $_REQUEST['columna'] ." ",$_REQUEST['id']);
            if(!$this->items)
                $this->items=$items->Obtener(' tbl_proceso_compras_detalle pcd',"pcd.proceso='DETALLE EMBARQUE' AND pcd.". $_REQUEST['columna'] ." ", $_REQUEST['id']);

            if(!$this->general)
                $this->general=$general->Obtener(' tbl_proceso_compras pc INNER JOIN tbl_proceso_compras_detalle pcd ON pc.proforma=pcd.proforma'," pc.". $_REQUEST['columna'] ." <>'' AND pc.proceso='ENTRADA FACTURA' AND pc.proceso=pcd.proceso  AND pc.". $_REQUEST['columna'] ." ",$_REQUEST['id']);
            if(!$this->items)
                $this->items=$items->Obtener(' tbl_proceso_compras_detalle pcd',"pcd.proceso='ENTRADA FACTURA' AND pcd.".$_REQUEST['columna'] ." ",$_REQUEST['id']);

            if(!$this->general)
                $this->general=$general->Obtener(' tbl_proceso_compras pc INNER JOIN tbl_proceso_compras_detalle pcd ON pc.proforma=pcd.proforma'," pc.". $_REQUEST['columna'] ." <>'' AND pc.proceso='PROFORMA' AND pc.proceso=pcd.proceso  AND pc.". $_REQUEST['columna'] ." ",$_REQUEST['id']);
            if(!$this->items)
                $this->items=$items->Obtener(' tbl_proceso_compras_detalle pcd',"pcd.proceso='PROFORMA' AND pcd.". $_REQUEST['columna'] ." ",$_REQUEST['id']);
            
                $this->fechafact=$general->Obtener(' tbl_proceso_compras pc'," pc.". $_REQUEST['columna'] ." <>'' AND pc.proceso='ENTRADA FACTURA' AND pc.". $_REQUEST['columna'] ." ",$_REQUEST['id']);

              
         /*        echo '<pre>';
                var_dump($this->general);die;
                echo '<pre>';  */

    	}

        self::CcomprasLQ();//le digo que muestre en vista edit
    }
 
    public function Guardar($vista ){
 
        //$directorio = ROOT."pdfprocesocomprasLQ/";
        //$tieneadjunto1 = adjuntarArchivo('', $directorio, $_FILES['adjunto']['name'],$_FILES['adjunto']['tmp_name'],'NUEVOS');
 
        //$proformas = new oComprasLQ();
    	$this->proformas =  new oComprasLQ(); 
        $this->proforma = $_REQUEST;
        //$this->proforma['adjunto']= $tieneadjunto1; 

        $this->proformas->Registrar("tbl_proceso_compras_liquidacion", " factura,pedido,trm_cl,precio_proforma,precio_unit_fob1,precio_unit_fob2,precio_unit_fob3,precio_unit_fob4,precio_unit_fob5,total_fob1,total_fob2,total_fob3,total_fob4,total_fob5,flete_inter_seguro1,flete_inter_seguro2,flete_inter_seguro3,flete_inter_seguro4,flete_inter_seguro5,total_cif1,total_cif2,total_cif3,total_cif4,total_cif5,total_cop1,total_cop2,total_cop3,total_cop4,total_cop5,oc_acycia_sa1,oc_acycia_sa2,oc_acycia_sa3,oc_acycia_sa4,oc_acycia_sa5,proveedor1,proveedor2,proveedor3,proveedor4,proveedor5,proforma1,proforma2,proforma3,proforma4,proforma5,material1,material2,material3,material4,material5,factura1,factura2,factura3,factura4,factura5,fecha_factura1,fecha_factura2,fecha_factura3,fecha_factura4,fecha_factura5,cantidad1,cantidad2,cantidad3,cantidad4,cantidad5,precio_compra_unidad_usd1,precio_compra_unidad_usd2,precio_compra_unidad_usd3,precio_compra_unidad_usd4,precio_compra_unidad_usd5,recogida_prov1,recogida_prov2,recogida_prov3,recogida_prov4,recogida_prov5,aduana_origen1,aduana_origen2,aduana_origen3,aduana_origen4,aduana_origen5,otrosgo1,otrosgo2,otrosgo3,otrosgo4,otrosgo5,flete_internal_seguro1,flete_internal_seguro2,flete_internal_seguro3,flete_internal_seguro4,flete_internal_seguro5,otrosti1,otrosti2,otrosti3,otrosti4,otrosti5,emision_b_l1,emision_b_l2,emision_b_l3,emision_b_l4,emision_b_l5,liberacion_doc_transp1,liberacion_doc_transp2,liberacion_doc_transp3,liberacion_doc_transp4,liberacion_doc_transp5,opera_portuaria1,opera_portuaria2,opera_portuaria3,opera_portuaria4,opera_portuaria5,doc_uso_instalaciones1,doc_uso_instalaciones2,doc_uso_instalaciones3,doc_uso_instalaciones4,doc_uso_instalaciones5,manejo_carga1,manejo_carga2,manejo_carga3,manejo_carga4,manejo_carga5,bodegaje_o_almacen1,bodegaje_o_almacen2,bodegaje_o_almacen3,bodegaje_o_almacen4,bodegaje_o_almacen5,montac_o_elevador1,montac_o_elevador2,montac_o_elevador3,montac_o_elevador4,montac_o_elevador5,inspeccion_preinspec1,inspeccion_preinspec2,inspeccion_preinspec3,inspeccion_preinspec4,inspeccion_preinspec5,costos_financieros1,costos_financieros2,costos_financieros3,costos_financieros4,costos_financieros5,otrosgpd1,otrosgpd2,otrosgpd3,otrosgpd4,otrosgpd5,comision_intermedia_aduanera1,comision_intermedia_aduanera2,comision_intermedia_aduanera3,comision_intermedia_aduanera4,comision_intermedia_aduanera5,elabora_declara_importa1,elabora_declara_importa2,elabora_declara_importa3,elabora_declara_importa4,elabora_declara_importa5,declara_andina_valor1,declara_andina_valor2,declara_andina_valor3,declara_andina_valor4,declara_andina_valor5,gastos_operativos1,gastos_operativos2,gastos_operativos3,gastos_operativos4,gastos_operativos5,trasmi_siglo_xxi1,trasmi_siglo_xxi2,trasmi_siglo_xxi3,trasmi_siglo_xxi4,trasmi_siglo_xxi5,aranceles1,aranceles2,aranceles3,aranceles4,aranceles5,descargue_directo1,descargue_directo2,descargue_directo3,descargue_directo4,descargue_directo5,otrosgn1,otrosgn2,otrosgn3,otrosgn4,otrosgn5,fletes_devol_contenedor1,fletes_devol_contenedor2,fletes_devol_contenedor3,fletes_devol_contenedor4,fletes_devol_contenedor5,descargue_servientrega1,descargue_servientrega2,descargue_servientrega3,descargue_servientrega4,descargue_servientrega5,flete_terrestre1,flete_terrestre2,flete_terrestre3,flete_terrestre4,flete_terrestre5,itr_puerto1,itr_puerto2,itr_puerto3,itr_puerto4,itr_puerto5,otrosted1,otrosted2,otrosted3,otrosted4,otrosted5,valor_factura1,valor_factura2,valor_factura3,valor_factura4,valor_factura5,gastos_origen1,gastos_origen2,gastos_origen3,gastos_origen4,gastos_origen5,fletes_total1,fletes_total2,fletes_total3,fletes_total4,fletes_total5,gastos_portuarios1,gastos_portuarios2,gastos_portuarios3,gastos_portuarios4,gastos_portuarios5,valor_gastos_nacional1,valor_gastos_nacional2,valor_gastos_nacional3,valor_gastos_nacional4,valor_gastos_nacional5,valor_transp_interno1,valor_transp_interno2,valor_transp_interno3,valor_transp_interno4,valor_transp_interno5,total1,total2,total3,total4,total5,numero_bl1,numero_bl2,numero_bl,numero_bl4,numero_bl5,fecha_bl1,fecha_bl2,fecha_bl3,fecha_bl4,fecha_bl5,declara1,declara2,declara3,declara4,declara5,fecha_dec1,fecha_dec2,fecha_dec3,fecha_dec4,fecha_dec5,costo_importacion1,costo_importacion2,costo_importacion3,costo_importacion4,costo_importacion5,valor_deposito1,valor_deposito2,valor_deposito3,valor_deposito4,valor_deposito5,num_contenedor1,num_contenedor2,num_contenedor3,num_contenedor4,num_contenedor5,tam_contenedor1,tam_contenedor2,tam_contenedor3,tam_contenedor4,tam_contenedor5,nota1,nota2,nota3,nota4,nota5,total_planta1,total_planta2,total_planta3,total_planta4,total_planta5",$_REQUEST['columna'],$_REQUEST['id'], $this->proforma);  
        //header("Location:view_index.php?c=comprasLQ&a=Crud&columna=". $_REQUEST['columna'] ." &id=". $_REQUEST['id'] ); 
        header("Location:view_index.php?c=comprasLQ&a=Liquidacion" ); 

    }
    public function Eliminar(){
    	$this->ordenc->Eliminar($_REQUEST['id']);//aqui llamo las funciones del modelo
    	header('Location: index.php');
    }

    public function CcomprasLQ($vista=''){ 
        if($vista){ 
          require_once("views/".$vista);  //header('Location:'.$vista);  
        }
        else{
    	  require_once("views/view_compras_lq.php");
        }
    }
/*
    public function listadonormal(){ 
    	require_once("views/view_compras.php?id=".$_REQUEST['id'].'&columna=id_pedido&tabla=tbl_orden_compra_historico' );
    }*/


}



?>
