<?php
 

class oComprasLQ{
    private $db;
    private $ordenc;

    public function __construct(){
        $this->db=Conectar::conexion();
        $this->ordenc=array();
        /*$this->proveedores=array();
        $this->insumo=array();*/

    }
 
    public function get_SelectDepend($tablas,$columnas,$consulta,$order,$group){

       try 
        {
            //echo "SELECT $columnas FROM $tablas $consulta $group $order "; 
            $consulta=$this->db->query("SELECT $columnas FROM $tablas $consulta $group $order ");
            $html = '<option value="">Seleccione...</option>'; 
            while($filas=$consulta->fetch_assoc()){
                $html .='<option value="'.$filas['factura'].'">'.$filas['factura'].'</option>';;
            }
 
            echo $html;
    
        } catch (Exception $e) 
        {
            die($e->getMessage());
        } 
    }


    public function get_SelectSubDepend($tablas,$columnas,$consulta,$order,$group){
      
        try 
        {
  
            $consulta = $this->db->query("SELECT $columnas FROM $tablas $consulta $group $order ");
            $html = '<option value="">Seleccione...</option>'; 
            while($filas=$consulta->fetch_assoc()){
                $html .='<option value="'.$filas['pedido'].'">'.$filas['pedido'].'</option>';;
            }

            echo $html;
    
        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }



    public function get_Provee(){

        try 
        {
            $consulta=$this->db->query("SELECT id_p, proveedor_p FROM proveedor ORDER BY proveedor_p ASC");
            while($filas=$consulta->fetch_assoc()){
                $this->proveedores[]=$filas;
            }
 
            return $this->proveedores;
        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }





    public function get_Insumo(){

        try 
        {
            $consulta=$this->db->query("SELECT id_insumo,descripcion_insumo, valor_unitario_insumo FROM insumo ORDER BY descripcion_insumo ASC");
            while($filas=$consulta->fetch_assoc()){
                $this->insumo[]=$filas;
            }
    
            return $this->insumo;
        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }

    public function get_Maquina(){

        try 
        {
            $consulta=$this->db->query("SELECT * FROM maquina WHERE proceso_maquina <>'0' ORDER BY nombre_maquina ASC");
            while($filas=$consulta->fetch_assoc()){
                $this->maquina[]=$filas;
            }
    
            return $this->maquina;
        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }


    public function Obtener($tabla,$columna,$id)
    {

        try 
        {
            if($tabla!='' && $columna!='' && $id!=''){ 
                $stm = $this->db->query("SELECT * FROM $tabla WHERE $columna = '$id' ");
                while($filas=$stm->fetch_assoc()){
                    $this->ordenc[]=$filas;
                }
            return $this->ordenc;
            }

        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }

    public function Registrar($tabla,$columna,$filtro,$id, $data)
    { 

        try 
        {
                $array_codificado = UtilHelper::arrayEncode($data);
                $array_deco = UtilHelper::arrayDecode($array_codificado); 
                $arrayPHP =  ($array_deco) ; 
 

                $consulta=$this->db->query("SELECT * FROM $tabla WHERE ".$filtro." ='$id'");
                if($consulta){
                 while($filas=$consulta->fetch_assoc()){
                    $this->existe[]=$filas;
                 }

                }
                   
                if(is_null($this->existe)){

                    $stmt = $this->db->query("INSERT INTO $tabla ($columna) VALUES ( '".$arrayPHP['factura']."','".$arrayPHP['pedido']."','".$arrayPHP['trm_cl']."','".$arrayPHP['precio_proforma']."','".$arrayPHP['precio_unit_fob1']."','".$arrayPHP['precio_unit_fob2']."','".$arrayPHP['precio_unit_fob3']."','".$arrayPHP['precio_unit_fob4']."','".$arrayPHP['precio_unit_fob5']."','".$arrayPHP['total_fob1']."','".$arrayPHP['total_fob2']."','".$arrayPHP['total_fob3']."','".$arrayPHP['total_fob4']."','".$arrayPHP['total_fob5']."','".$arrayPHP['flete_inter_seguro1']."','".$arrayPHP['flete_inter_seguro2']."','".$arrayPHP['flete_inter_seguro3']."','".$arrayPHP['flete_inter_seguro4']."','".$arrayPHP['flete_inter_seguro5']."','".$arrayPHP['total_cif1']."','".$arrayPHP['total_cif2']."','".$arrayPHP['total_cif3']."','".$arrayPHP['total_cif4']."','".$arrayPHP['total_cif5']."','".$arrayPHP['total_cop1']."','".$arrayPHP['total_cop2']."','".$arrayPHP['total_cop3']."','".$arrayPHP['total_cop4']."','".$arrayPHP['total_cop5']."','".$arrayPHP['oc_acycia_sa1']."','".$arrayPHP['oc_acycia_sa2']."','".$arrayPHP['oc_acycia_sa3']."','".$arrayPHP['oc_acycia_sa4']."','".$arrayPHP['oc_acycia_sa5']."','".$arrayPHP['proveedor1']."','".$arrayPHP['proveedor2']."','".$arrayPHP['proveedor3']."','".$arrayPHP['proveedor4']."','".$arrayPHP['proveedor5']."','".$arrayPHP['proforma1']."','".$arrayPHP['proforma2']."','".$arrayPHP['proforma3']."','".$arrayPHP['proforma4']."','".$arrayPHP['proforma5']."','".$arrayPHP['material1']."','".$arrayPHP['material2']."','".$arrayPHP['material3']."','".$arrayPHP['material4']."','".$arrayPHP['material5']."','".$arrayPHP['factura1']."','".$arrayPHP['factura2']."','".$arrayPHP['factura3']."','".$arrayPHP['factura4']."','".$arrayPHP['factura5']."','".$arrayPHP['fecha_factura1']."','".$arrayPHP['fecha_factura2']."','".$arrayPHP['fecha_factura3']."','".$arrayPHP['fecha_factura4']."','".$arrayPHP['fecha_factura5']."','".$arrayPHP['cantidad1']."','".$arrayPHP['cantidad2']."','".$arrayPHP['cantidad3']."','".$arrayPHP['cantidad4']."','".$arrayPHP['cantidad5']."','".$arrayPHP['precio_compra_unidad_usd1']."','".$arrayPHP['precio_compra_unidad_usd2']."','".$arrayPHP['precio_compra_unidad_usd3']."','".$arrayPHP['precio_compra_unidad_usd4']."','".$arrayPHP['precio_compra_unidad_usd5']."','".$arrayPHP['recogida_prov1']."','".$arrayPHP['recogida_prov2']."','".$arrayPHP['recogida_prov3']."','".$arrayPHP['recogida_prov4']."','".$arrayPHP['recogida_prov5']."','".$arrayPHP['aduana_origen1']."','".$arrayPHP['aduana_origen2']."','".$arrayPHP['aduana_origen3']."','".$arrayPHP['aduana_origen4']."','".$arrayPHP['aduana_origen5']."','".$arrayPHP['otrosgo1']."','".$arrayPHP['otrosgo2']."','".$arrayPHP['otrosgo3']."','".$arrayPHP['otrosgo4']."','".$arrayPHP['otrosgo5']."','".$arrayPHP['flete_internal_seguro1']."','".$arrayPHP['flete_internal_seguro2']."','".$arrayPHP['flete_internal_seguro3']."','".$arrayPHP['flete_internal_seguro4']."','".$arrayPHP['flete_internal_seguro5']."','".$arrayPHP['otrosti1']."','".$arrayPHP['otrosti2']."','".$arrayPHP['otrosti3']."','".$arrayPHP['otrosti4']."','".$arrayPHP['otrosti5']."','".$arrayPHP['emision_b_l1']."','".$arrayPHP['emision_b_l2']."','".$arrayPHP['emision_b_l3']."','".$arrayPHP['emision_b_l4']."','".$arrayPHP['emision_b_l5']."','".$arrayPHP['liberacion_doc_transp1']."','".$arrayPHP['liberacion_doc_transp2']."','".$arrayPHP['liberacion_doc_transp3']."','".$arrayPHP['liberacion_doc_transp4']."','".$arrayPHP['liberacion_doc_transp5']."','".$arrayPHP['opera_portuaria1']."','".$arrayPHP['opera_portuaria2']."','".$arrayPHP['opera_portuaria3']."','".$arrayPHP['opera_portuaria4']."','".$arrayPHP['opera_portuaria5']."','".$arrayPHP['doc_uso_instalaciones1']."','".$arrayPHP['doc_uso_instalaciones2']."','".$arrayPHP['doc_uso_instalaciones3']."','".$arrayPHP['doc_uso_instalaciones4']."','".$arrayPHP['doc_uso_instalaciones5']."','".$arrayPHP['manejo_carga1']."','".$arrayPHP['manejo_carga2']."','".$arrayPHP['manejo_carga3']."','".$arrayPHP['manejo_carga4']."','".$arrayPHP['manejo_carga5']."','".$arrayPHP['bodegaje_o_almacen1']."','".$arrayPHP['bodegaje_o_almacen2']."','".$arrayPHP['bodegaje_o_almacen3']."','".$arrayPHP['bodegaje_o_almacen4']."','".$arrayPHP['bodegaje_o_almacen5']."','".$arrayPHP['montac_o_elevador1']."','".$arrayPHP['montac_o_elevador2']."','".$arrayPHP['montac_o_elevador3']."','".$arrayPHP['montac_o_elevador4']."','".$arrayPHP['montac_o_elevador5']."','".$arrayPHP['inspeccion_preinspec1']."','".$arrayPHP['inspeccion_preinspec2']."','".$arrayPHP['inspeccion_preinspec3']."','".$arrayPHP['inspeccion_preinspec4']."','".$arrayPHP['inspeccion_preinspec5']."','".$arrayPHP['costos_financieros1']."','".$arrayPHP['costos_financieros2']."','".$arrayPHP['costos_financieros3']."','".$arrayPHP['costos_financieros4']."','".$arrayPHP['costos_financieros5']."','".$arrayPHP['otrosgpd1']."','".$arrayPHP['otrosgpd2']."','".$arrayPHP['otrosgpd3']."','".$arrayPHP['otrosgpd4']."','".$arrayPHP['otrosgpd5']."','".$arrayPHP['comision_intermedia_aduanera1']."','".$arrayPHP['comision_intermedia_aduanera2']."','".$arrayPHP['comision_intermedia_aduanera3']."','".$arrayPHP['comision_intermedia_aduanera4']."','".$arrayPHP['comision_intermedia_aduanera5']."','".$arrayPHP['elabora_declara_importa1']."','".$arrayPHP['elabora_declara_importa2']."','".$arrayPHP['elabora_declara_importa3']."','".$arrayPHP['elabora_declara_importa4']."','".$arrayPHP['elabora_declara_importa5']."','".$arrayPHP['declara_andina_valor1']."','".$arrayPHP['declara_andina_valor2']."','".$arrayPHP['declara_andina_valor3']."','".$arrayPHP['declara_andina_valor4']."','".$arrayPHP['declara_andina_valor5']."','".$arrayPHP['gastos_operativos1']."','".$arrayPHP['gastos_operativos2']."','".$arrayPHP['gastos_operativos3']."','".$arrayPHP['gastos_operativos4']."','".$arrayPHP['gastos_operativos5']."','".$arrayPHP['trasmi_siglo_xxi1']."','".$arrayPHP['trasmi_siglo_xxi2']."','".$arrayPHP['trasmi_siglo_xxi3']."','".$arrayPHP['trasmi_siglo_xxi4']."','".$arrayPHP['trasmi_siglo_xxi5']."','".$arrayPHP['aranceles1']."','".$arrayPHP['aranceles2']."','".$arrayPHP['aranceles3']."','".$arrayPHP['aranceles4']."','".$arrayPHP['aranceles5']."','".$arrayPHP['descargue_directo1']."','".$arrayPHP['descargue_directo2']."','".$arrayPHP['descargue_directo3']."','".$arrayPHP['descargue_directo4']."','".$arrayPHP['descargue_directo5']."','".$arrayPHP['otrosgn1']."','".$arrayPHP['otrosgn2']."','".$arrayPHP['otrosgn3']."','".$arrayPHP['otrosgn4']."','".$arrayPHP['otrosgn5']."','".$arrayPHP['fletes_devol_contenedor1']."','".$arrayPHP['fletes_devol_contenedor2']."','".$arrayPHP['fletes_devol_contenedor3']."','".$arrayPHP['fletes_devol_contenedor4']."','".$arrayPHP['fletes_devol_contenedor5']."','".$arrayPHP['descargue_servientrega1']."','".$arrayPHP['descargue_servientrega2']."','".$arrayPHP['descargue_servientrega3']."','".$arrayPHP['descargue_servientrega4']."','".$arrayPHP['descargue_servientrega5']."','".$arrayPHP['flete_terrestre1']."','".$arrayPHP['flete_terrestre2']."','".$arrayPHP['flete_terrestre3']."','".$arrayPHP['flete_terrestre4']."','".$arrayPHP['flete_terrestre5']."','".$arrayPHP['itr_puerto1']."','".$arrayPHP['itr_puerto2']."','".$arrayPHP['itr_puerto3']."','".$arrayPHP['itr_puerto4']."','".$arrayPHP['itr_puerto5']."','".$arrayPHP['otrosted1']."','".$arrayPHP['otrosted2']."','".$arrayPHP['otrosted3']."','".$arrayPHP['otrosted4']."','".$arrayPHP['otrosted5']."','".$arrayPHP['valor_factura1']."','".$arrayPHP['valor_factura2']."','".$arrayPHP['valor_factura3']."','".$arrayPHP['valor_factura4']."','".$arrayPHP['valor_factura5']."','".$arrayPHP['gastos_origen1']."','".$arrayPHP['gastos_origen2']."','".$arrayPHP['gastos_origen3']."','".$arrayPHP['gastos_origen4']."','".$arrayPHP['gastos_origen5']."','".$arrayPHP['fletes_total1']."','".$arrayPHP['fletes_total2']."','".$arrayPHP['fletes_total3']."','".$arrayPHP['fletes_total4']."','".$arrayPHP['fletes_total5']."','".$arrayPHP['gastos_portuarios1']."','".$arrayPHP['gastos_portuarios2']."','".$arrayPHP['gastos_portuarios3']."','".$arrayPHP['gastos_portuarios4']."','".$arrayPHP['gastos_portuarios5']."','".$arrayPHP['valor_gastos_nacional1']."','".$arrayPHP['valor_gastos_nacional2']."','".$arrayPHP['valor_gastos_nacional3']."','".$arrayPHP['valor_gastos_nacional4']."','".$arrayPHP['valor_gastos_nacional5']."','".$arrayPHP['valor_transp_interno1']."','".$arrayPHP['valor_transp_interno2']."','".$arrayPHP['valor_transp_interno3']."','".$arrayPHP['valor_transp_interno4']."','".$arrayPHP['valor_transp_interno5']."','".$arrayPHP['total1']."','".$arrayPHP['total2']."','".$arrayPHP['total3']."','".$arrayPHP['total4']."','".$arrayPHP['total5']."','".$arrayPHP['numero_bl1']."','".$arrayPHP['numero_bl2']."','".$arrayPHP['numero_bl']."','".$arrayPHP['numero_bl4']."','".$arrayPHP['numero_bl5']."','".$arrayPHP['fecha_bl1']."','".$arrayPHP['fecha_bl2']."','".$arrayPHP['fecha_bl3']."','".$arrayPHP['fecha_bl4']."','".$arrayPHP['fecha_bl5']."','".$arrayPHP['declara1']."','".$arrayPHP['declara2']."','".$arrayPHP['declara3']."','".$arrayPHP['declara4']."','".$arrayPHP['declara5']."','".$arrayPHP['fecha_dec1']."','".$arrayPHP['fecha_dec2']."','".$arrayPHP['fecha_dec3']."','".$arrayPHP['fecha_dec4']."','".$arrayPHP['fecha_dec5']."','".$arrayPHP['costo_importacion1']."','".$arrayPHP['costo_importacion2']."','".$arrayPHP['costo_importacion3']."','".$arrayPHP['costo_importacion4']."','".$arrayPHP['costo_importacion5']."','".$arrayPHP['valor_deposito1']."','".$arrayPHP['valor_deposito2']."','".$arrayPHP['valor_deposito3']."','".$arrayPHP['valor_deposito4']."','".$arrayPHP['valor_deposito5']."','".$arrayPHP['num_contenedor1']."','".$arrayPHP['num_contenedor2']."','".$arrayPHP['num_contenedor3']."','".$arrayPHP['num_contenedor4']."','".$arrayPHP['num_contenedor5']."','".$arrayPHP['tam_contenedor1']."','".$arrayPHP['tam_contenedor2']."','".$arrayPHP['tam_contenedor3']."','".$arrayPHP['tam_contenedor4']."','".$arrayPHP['tam_contenedor5']."','".$arrayPHP['nota1']."','".$arrayPHP['nota2']."','".$arrayPHP['nota3']."','".$arrayPHP['nota4']."','".$arrayPHP['nota5']."','".$arrayPHP['total_planta1']."','".$arrayPHP['total_planta2']."','".$arrayPHP['total_planta3']."','".$arrayPHP['total_planta4']."','".$arrayPHP['total_planta5']."' );");  

                }else{
                   
                     


                    $updatepro = $this->db->query("UPDATE $tabla SET factura = '".$arrayPHP['factura']."',pedido = '".$arrayPHP['pedido']."',trm_cl = '".$arrayPHP['trm_cl']."',precio_proforma = '".$arrayPHP['precio_proforma']."',precio_unit_fob1 = '".$arrayPHP['precio_unit_fob1']."',precio_unit_fob2 = '".$arrayPHP['precio_unit_fob2']."',precio_unit_fob3 = '".$arrayPHP['precio_unit_fob3']."',precio_unit_fob4 = '".$arrayPHP['precio_unit_fob4']."',precio_unit_fob5 = '".$arrayPHP['precio_unit_fob5']."',total_fob1 = '".$arrayPHP['total_fob1']."',total_fob2 = '".$arrayPHP['total_fob2']."',total_fob3 = '".$arrayPHP['total_fob3']."',total_fob4 = '".$arrayPHP['total_fob4']."',total_fob5 = '".$arrayPHP['total_fob5']."',flete_inter_seguro1 = '".$arrayPHP['flete_inter_seguro1']."',flete_inter_seguro2 = '".$arrayPHP['flete_inter_seguro2']."',flete_inter_seguro3 = '".$arrayPHP['flete_inter_seguro3']."',flete_inter_seguro4 = '".$arrayPHP['flete_inter_seguro4']."',flete_inter_seguro5 = '".$arrayPHP['flete_inter_seguro5']."',total_cif1 = '".$arrayPHP['total_cif1']."',total_cif2 = '".$arrayPHP['total_cif2']."',total_cif3 = '".$arrayPHP['total_cif3']."',total_cif4 = '".$arrayPHP['total_cif4']."',total_cif5 = '".$arrayPHP['total_cif5']."',total_cop1 = '".$arrayPHP['total_cop1']."',total_cop2 = '".$arrayPHP['total_cop2']."',total_cop3 = '".$arrayPHP['total_cop3']."',total_cop4 = '".$arrayPHP['total_cop4']."',total_cop5 = '".$arrayPHP['total_cop5 ']."',oc_acycia_sa1 = '".$arrayPHP['oc_acycia_sa1']."',oc_acycia_sa2 = '".$arrayPHP['oc_acycia_sa2']."',oc_acycia_sa3 = '".$arrayPHP['oc_acycia_sa3']."',oc_acycia_sa4 = '".$arrayPHP['oc_acycia_sa4']."',oc_acycia_sa5 = '".$arrayPHP['oc_acycia_sa5']."',proveedor1 = '".$arrayPHP['proveedor1']."',proveedor2 = '".$arrayPHP['proveedor2']."',proveedor3 = '".$arrayPHP['proveedor3']."',proveedor4 = '".$arrayPHP['proveedor4']."',proveedor5 = '".$arrayPHP['proveedor5']."',proforma1 = '".$arrayPHP['proforma1']."',proforma2 = '".$arrayPHP['proforma2']."',proforma3 = '".$arrayPHP['proforma3']."',proforma4 = '".$arrayPHP['proforma4']."',proforma5 = '".$arrayPHP['proforma5']."',material1 = '".$arrayPHP['material1']."',material2 = '".$arrayPHP['material2']."',material3 = '".$arrayPHP['material3']."',material4 = '".$arrayPHP['material4']."',material5 = '".$arrayPHP['material5']."',factura1 = '".$arrayPHP['factura1']."',factura2 = '".$arrayPHP['factura2']."',factura3 = '".$arrayPHP['factura3']."',factura4 = '".$arrayPHP['factura4']."',factura5 = '".$arrayPHP['factura5']."',fecha_factura1 = '".$arrayPHP['fecha_factura1']."',fecha_factura2 = '".$arrayPHP['fecha_factura2']."',fecha_factura3 = '".$arrayPHP['fecha_factura3']."',fecha_factura4 = '".$arrayPHP['fecha_factura4']."',fecha_factura5 = '".$arrayPHP['fecha_factura5']."',cantidad1 = '".$arrayPHP['cantidad1']."',cantidad2 = '".$arrayPHP['cantidad2']."',cantidad3 = '".$arrayPHP['cantidad3']."',cantidad4 = '".$arrayPHP['cantidad4']."',cantidad5 = '".$arrayPHP['cantidad5']."',precio_compra_unidad_usd1 = '".$arrayPHP['precio_compra_unidad_usd1']."',precio_compra_unidad_usd2 = '".$arrayPHP['precio_compra_unidad_usd2']."',precio_compra_unidad_usd3 = '".$arrayPHP['precio_compra_unidad_usd3']."',precio_compra_unidad_usd4 = '".$arrayPHP['precio_compra_unidad_usd4']."',precio_compra_unidad_usd5 = '".$arrayPHP['precio_compra_unidad_usd5']."',recogida_prov1 = '".$arrayPHP['recogida_prov1']."',recogida_prov2 = '".$arrayPHP['recogida_prov2']."',recogida_prov3 = '".$arrayPHP['recogida_prov3']."',recogida_prov4 = '".$arrayPHP['recogida_prov4']."',recogida_prov5 = '".$arrayPHP['recogida_prov5']."',aduana_origen1 = '".$arrayPHP['aduana_origen1']."',aduana_origen2 = '".$arrayPHP['aduana_origen2']."',aduana_origen3 = '".$arrayPHP['aduana_origen3']."',aduana_origen4 = '".$arrayPHP['aduana_origen4']."',aduana_origen5 = '".$arrayPHP['aduana_origen5']."',otrosgo1 = '".$arrayPHP['otrosgo1']."',otrosgo2 = '".$arrayPHP['otrosgo2']."',otrosgo3 = '".$arrayPHP['otrosgo3']."',otrosgo4 = '".$arrayPHP['otrosgo4']."',otrosgo5 = '".$arrayPHP['otrosgo5']."',flete_internal_seguro1 = '".$arrayPHP['flete_internal_seguro1']."',flete_internal_seguro2 = '".$arrayPHP['flete_internal_seguro2']."',flete_internal_seguro3 = '".$arrayPHP['flete_internal_seguro3']."',flete_internal_seguro4 = '".$arrayPHP['flete_internal_seguro4']."',flete_internal_seguro5 = '".$arrayPHP['flete_internal_seguro5']."',otrosti1 = '".$arrayPHP['otrosti1']."',otrosti2 = '".$arrayPHP['otrosti2']."',otrosti3 = '".$arrayPHP['otrosti3']."',otrosti4 = '".$arrayPHP['otrosti4']."',otrosti5 = '".$arrayPHP['otrosti5']."',emision_b_l1 = '".$arrayPHP['emision_b_l1']."',emision_b_l2 = '".$arrayPHP['emision_b_l2']."',emision_b_l3 = '".$arrayPHP['emision_b_l3']."',emision_b_l4 = '".$arrayPHP['emision_b_l4']."',emision_b_l5 = '".$arrayPHP['emision_b_l5']."',liberacion_doc_transp1 = '".$arrayPHP['liberacion_doc_transp1']."',liberacion_doc_transp2 = '".$arrayPHP['liberacion_doc_transp2']."',liberacion_doc_transp3 = '".$arrayPHP['liberacion_doc_transp3']."',liberacion_doc_transp4 = '".$arrayPHP['liberacion_doc_transp4']."',liberacion_doc_transp5 = '".$arrayPHP['liberacion_doc_transp5']."',opera_portuaria1 = '".$arrayPHP['opera_portuaria1']."',opera_portuaria2 = '".$arrayPHP['opera_portuaria2']."',opera_portuaria3 = '".$arrayPHP['opera_portuaria3']."',opera_portuaria4 = '".$arrayPHP['opera_portuaria4']."',opera_portuaria5 = '".$arrayPHP['opera_portuaria5']."',doc_uso_instalaciones1 = '".$arrayPHP['doc_uso_instalaciones1']."',doc_uso_instalaciones2 = '".$arrayPHP['doc_uso_instalaciones2']."',doc_uso_instalaciones3 = '".$arrayPHP['doc_uso_instalaciones3']."',doc_uso_instalaciones4 = '".$arrayPHP['doc_uso_instalaciones4']."',doc_uso_instalaciones5 = '".$arrayPHP['doc_uso_instalaciones5']."',manejo_carga1 = '".$arrayPHP['manejo_carga1']."',manejo_carga2 = '".$arrayPHP['manejo_carga2']."',manejo_carga3 = '".$arrayPHP['manejo_carga3']."',manejo_carga4 = '".$arrayPHP['manejo_carga4']."',manejo_carga5 = '".$arrayPHP['manejo_carga5']."',bodegaje_o_almacen1 = '".$arrayPHP['bodegaje_o_almacen1']."',bodegaje_o_almacen2 = '".$arrayPHP['bodegaje_o_almacen2']."',bodegaje_o_almacen3 = '".$arrayPHP['bodegaje_o_almacen3']."',bodegaje_o_almacen4 = '".$arrayPHP['bodegaje_o_almacen4']."',bodegaje_o_almacen5 = '".$arrayPHP['bodegaje_o_almacen5']."',montac_o_elevador1 = '".$arrayPHP['montac_o_elevador1']."',montac_o_elevador2 = '".$arrayPHP['montac_o_elevador2']."',montac_o_elevador3 = '".$arrayPHP['montac_o_elevador3']."',montac_o_elevador4 = '".$arrayPHP['montac_o_elevador4']."',montac_o_elevador5 = '".$arrayPHP['montac_o_elevador5']."',inspeccion_preinspec1 = '".$arrayPHP['inspeccion_preinspec1']."',inspeccion_preinspec2 = '".$arrayPHP['inspeccion_preinspec2']."',inspeccion_preinspec3 = '".$arrayPHP['inspeccion_preinspec3']."',inspeccion_preinspec4 = '".$arrayPHP['inspeccion_preinspec4']."',inspeccion_preinspec5 = '".$arrayPHP['inspeccion_preinspec5']."',costos_financieros1 = '".$arrayPHP['costos_financieros1']."',costos_financieros2 = '".$arrayPHP['costos_financieros2']."',costos_financieros3 = '".$arrayPHP['costos_financieros3']."',costos_financieros4 = '".$arrayPHP['costos_financieros4']."',costos_financieros5 = '".$arrayPHP['costos_financieros5']."',otrosgpd1 = '".$arrayPHP['otrosgpd1']."',otrosgpd2 = '".$arrayPHP['otrosgpd2']."',otrosgpd3 = '".$arrayPHP['otrosgpd3']."',otrosgpd4 = '".$arrayPHP['otrosgpd4']."',otrosgpd5 = '".$arrayPHP['otrosgpd5']."',comision_intermedia_aduanera1 = '".$arrayPHP['comision_intermedia_aduanera1']."',comision_intermedia_aduanera2 = '".$arrayPHP['comision_intermedia_aduanera2']."',comision_intermedia_aduanera3 = '".$arrayPHP['comision_intermedia_aduanera3']."',comision_intermedia_aduanera4 = '".$arrayPHP['comision_intermedia_aduanera4']."',comision_intermedia_aduanera5 = '".$arrayPHP['comision_intermedia_aduanera5']."',elabora_declara_importa1 = '".$arrayPHP['elabora_declara_importa1']."',elabora_declara_importa2 = '".$arrayPHP['elabora_declara_importa2']."',elabora_declara_importa3 = '".$arrayPHP['elabora_declara_importa3']."',elabora_declara_importa4 = '".$arrayPHP['elabora_declara_importa4']."',elabora_declara_importa5 = '".$arrayPHP['elabora_declara_importa5']."',declara_andina_valor1 = '".$arrayPHP['declara_andina_valor1']."',declara_andina_valor2 = '".$arrayPHP['declara_andina_valor2']."',declara_andina_valor3 = '".$arrayPHP['declara_andina_valor3']."',declara_andina_valor4 = '".$arrayPHP['declara_andina_valor4']."',declara_andina_valor5 = '".$arrayPHP['declara_andina_valor5']."',gastos_operativos1 = '".$arrayPHP['gastos_operativos1']."',gastos_operativos2 = '".$arrayPHP['gastos_operativos2']."',gastos_operativos3 = '".$arrayPHP['gastos_operativos3']."',gastos_operativos4 = '".$arrayPHP['gastos_operativos4']."',gastos_operativos5 = '".$arrayPHP['gastos_operativos5']."',trasmi_siglo_xxi1 = '".$arrayPHP['trasmi_siglo_xxi1']."',trasmi_siglo_xxi2 = '".$arrayPHP['trasmi_siglo_xxi2']."',trasmi_siglo_xxi3 = '".$arrayPHP['trasmi_siglo_xxi3']."',trasmi_siglo_xxi4 = '".$arrayPHP['trasmi_siglo_xxi4']."',trasmi_siglo_xxi5 = '".$arrayPHP['trasmi_siglo_xxi5']."',aranceles1 = '".$arrayPHP['aranceles1']."',aranceles2 = '".$arrayPHP['aranceles2']."',aranceles3 = '".$arrayPHP['aranceles3']."',aranceles4 = '".$arrayPHP['aranceles4']."',aranceles5 = '".$arrayPHP['aranceles5']."',descargue_directo1 = '".$arrayPHP['descargue_directo1']."',descargue_directo2 = '".$arrayPHP['descargue_directo2']."',descargue_directo3 = '".$arrayPHP['descargue_directo3']."',descargue_directo4 = '".$arrayPHP['descargue_directo4']."',descargue_directo5 = '".$arrayPHP['descargue_directo5']."',otrosgn1 = '".$arrayPHP['otrosgn1']."',otrosgn2 = '".$arrayPHP['otrosgn2']."',otrosgn3 = '".$arrayPHP['otrosgn3']."',otrosgn4 = '".$arrayPHP['otrosgn4']."',otrosgn5 = '".$arrayPHP['otrosgn5']."',fletes_devol_contenedor1 = '".$arrayPHP['fletes_devol_contenedor1']."',fletes_devol_contenedor2 = '".$arrayPHP['fletes_devol_contenedor2']."',fletes_devol_contenedor3 = '".$arrayPHP['fletes_devol_contenedor3']."',fletes_devol_contenedor4 = '".$arrayPHP['fletes_devol_contenedor4']."',fletes_devol_contenedor5 = '".$arrayPHP['fletes_devol_contenedor5']."',descargue_servientrega1 = '".$arrayPHP['descargue_servientrega1']."',descargue_servientrega2 = '".$arrayPHP['descargue_servientrega2']."',descargue_servientrega3 = '".$arrayPHP['descargue_servientrega3']."',descargue_servientrega4 = '".$arrayPHP['descargue_servientrega4']."',descargue_servientrega5 = '".$arrayPHP['descargue_servientrega5']."',flete_terrestre1 = '".$arrayPHP['flete_terrestre1']."',flete_terrestre2 = '".$arrayPHP['flete_terrestre2']."',flete_terrestre3 = '".$arrayPHP['flete_terrestre3']."',flete_terrestre4 = '".$arrayPHP['flete_terrestre4']."',flete_terrestre5 = '".$arrayPHP['flete_terrestre5']."',itr_puerto1 = '".$arrayPHP['itr_puerto1']."',itr_puerto2 = '".$arrayPHP['itr_puerto2']."',itr_puerto3 = '".$arrayPHP['itr_puerto3']."',itr_puerto4 = '".$arrayPHP['itr_puerto4']."',itr_puerto5 = '".$arrayPHP['itr_puerto5']."',otrosted1 = '".$arrayPHP['otrosted1']."',otrosted2 = '".$arrayPHP['otrosted2']."',otrosted3 = '".$arrayPHP['otrosted3']."',otrosted4 = '".$arrayPHP['otrosted4']."',otrosted5 = '".$arrayPHP['otrosted5']."',valor_factura1 = '".$arrayPHP['valor_factura1']."',valor_factura2 = '".$arrayPHP['valor_factura2']."',valor_factura3 = '".$arrayPHP['valor_factura3']."',valor_factura4 = '".$arrayPHP['valor_factura4']."',valor_factura5 = '".$arrayPHP['valor_factura5']."',gastos_origen1='".$arrayPHP['gastos_origen1']."',gastos_origen2='".$arrayPHP['gastos_origen2']."',gastos_origen3='".$arrayPHP['gastos_origen3']."',gastos_origen4='".$arrayPHP['gastos_origen4']."',gastos_origen5='".$arrayPHP['gastos_origen5']."',fletes_total1='".$arrayPHP['fletes_total1']."',fletes_total2='".$arrayPHP['fletes_total2']."',fletes_total3='".$arrayPHP['fletes_total3']."',fletes_total4='".$arrayPHP['fletes_total4']."',fletes_total5='".$arrayPHP['fletes_total5']."',gastos_portuarios1='".$arrayPHP['gastos_portuarios1']."',gastos_portuarios2='".$arrayPHP['gastos_portuarios2']."',gastos_portuarios3='".$arrayPHP['gastos_portuarios3']."',gastos_portuarios4='".$arrayPHP['gastos_portuarios4']."',gastos_portuarios5='".$arrayPHP['gastos_portuarios5']."',valor_gastos_nacional1='".$arrayPHP['valor_gastos_nacional1']."',valor_gastos_nacional2='".$arrayPHP['valor_gastos_nacional2']."',valor_gastos_nacional3='".$arrayPHP['valor_gastos_nacional3']."',valor_gastos_nacional4='".$arrayPHP['valor_gastos_nacional4']."',valor_gastos_nacional5='".$arrayPHP['valor_gastos_nacional5']."',valor_transp_interno1='".$arrayPHP['valor_transp_interno1']."',valor_transp_interno2='".$arrayPHP['valor_transp_interno2']."',valor_transp_interno3='".$arrayPHP['valor_transp_interno3']."',valor_transp_interno4='".$arrayPHP['valor_transp_interno4']."',valor_transp_interno5='".$arrayPHP['valor_transp_interno5']."',total1='".$arrayPHP['total1']."',total2='".$arrayPHP['total2']."',total3='".$arrayPHP['total3']."',total4='".$arrayPHP['total4']."',total5='".$arrayPHP['total5']."', numero_bl1 = '".$arrayPHP['numero_bl1']."',numero_bl2 = '".$arrayPHP['numero_bl2']."',numero_bl = '".$arrayPHP['numero_bl']."',numero_bl4 = '".$arrayPHP['numero_bl4']."',numero_bl5 = '".$arrayPHP['numero_bl5']."',fecha_bl1 = '".$arrayPHP['fecha_bl1']."',fecha_bl2 = '".$arrayPHP['fecha_bl2']."',fecha_bl3 = '".$arrayPHP['fecha_bl3']."',fecha_bl4 = '".$arrayPHP['fecha_bl4']."',fecha_bl5 = '".$arrayPHP['fecha_bl5']."',declara1 = '".$arrayPHP['declara1']."',declara2 = '".$arrayPHP['declara2']."',declara3 = '".$arrayPHP['declara3']."',declara4 = '".$arrayPHP['declara4']."',declara5 = '".$arrayPHP['declara5']."',fecha_dec1 = '".$arrayPHP['fecha_dec1']."',fecha_dec2 = '".$arrayPHP['fecha_dec2']."',fecha_dec3 = '".$arrayPHP['fecha_dec3']."',fecha_dec4 = '".$arrayPHP['fecha_dec4']."',fecha_dec5 = '".$arrayPHP['fecha_dec5']."', valor_deposito1 ='".$arrayPHP['valor_deposito1']."',valor_deposito2 ='".$arrayPHP['valor_deposito2']."',valor_deposito3 ='".$arrayPHP['valor_deposito3']."',valor_deposito4 ='".$arrayPHP['valor_deposito4']."',valor_deposito5 ='".$arrayPHP['valor_deposito5']."',num_contenedor1 ='".$arrayPHP['num_contenedor1']."',num_contenedor2 ='".$arrayPHP['num_contenedor2']."',num_contenedor3 ='".$arrayPHP['num_contenedor3']."',num_contenedor4 ='".$arrayPHP['num_contenedor4']."',num_contenedor5 ='".$arrayPHP['num_contenedor5']."',tam_contenedor1 ='".$arrayPHP['tam_contenedor1']."',tam_contenedor2 ='".$arrayPHP['tam_contenedor2']."',tam_contenedor3 ='".$arrayPHP['tam_contenedor3']."',tam_contenedor4 ='".$arrayPHP['tam_contenedor4']."',tam_contenedor5 ='".$arrayPHP['tam_contenedor5']."',  nota1 = '".$arrayPHP['nota1']."',nota2 = '".$arrayPHP['nota2']."',nota3 = '".$arrayPHP['nota3']."',nota4 = '".$arrayPHP['nota4']."',nota5 = '".$arrayPHP['nota5']."',total_planta1 = '".$arrayPHP['total_planta1']."',total_planta2 = '".$arrayPHP['total_planta2']."',total_planta3 = '".$arrayPHP['total_planta3']."',total_planta4 = '".$arrayPHP['total_planta4']."',total_planta5 = '".$arrayPHP['total_planta5']."'  WHERE ".$filtro." = '". $id ."' ;" );

                }
    
       
        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }


    public function RegistrarItems($tabla,$columna, $arrayPHP)
    { 

        try 
        {
            

             $cantidad=$arrayPHP['cantidad'];
             $code=$arrayPHP['code'];
             $descripcion=$arrayPHP['descripcion'];
             $moneda=$arrayPHP['moneda'];
             $precio=$arrayPHP['precio'];
             $precio_total=$arrayPHP['precio_total'];
             $incoterm=$arrayPHP['incoterm'];
             $valoricot=$arrayPHP['valoricot'];
             $estado=$arrayPHP['estado'];
             $medida=$arrayPHP['medida'];

             for ($d=0,$e=0,$f=0,$g=0,$h=0,$i=0,$j=0,$k=0,$l=0;$d<count($cantidad);$d++,$e++,$f++,$g++,$h++,$i++,$j++,$k++,$l++){
            
                if( !(empty($cantidad[$d])) && !(empty($code[$e]))&& !(empty($descripcion[$f]))&& !(empty($moneda[$g]))&& !(empty($precio[$h]))&& !(empty($precio_total[$i]))  )  { 
                 $stmt = $this->db->query("INSERT INTO $tabla ($columna) VALUES ( '". $arrayPHP['proforma'] ."', '" . $arrayPHP['pedido'] . "', '" . $arrayPHP['factura'] . "', '" . $arrayPHP['proceso'] . "', '" . $cantidad[$d] . "', '" . $medida[$l] . "','" . $code[$e] . "', '" . $descripcion[$f] . "', '" . $moneda[$g] . "', '" . $precio[$h] . "', '" . $precio_total[$i] . "', '" . $incoterm[$j] . "', '" . $valoricot[$k] . "', '" . $arrayPHP['estado'] . "' );");
            

                   } 
            }
     
        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }

    public function Eliminar($id)
    {
        try 
        {
            $stm = $this->db->query("DELETE FROM tbl_proceso_compras_detalle WHERE id_pedido = $id");                      

            $stm->execute(array($id));
        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }

    public function Actualizar(oCompras $data)
    {
        try 
        {
            $sql = "UPDATE tbl_proceso_compras_detalle SET 
            str_numero_oc         = ?, 
            fecha_ingreso_oc      = ?,
            str_condicion_pago_oc = ? 
            WHERE id_pedido = ?";
 
            $this->db->prepare($sql)
            ->execute(
                array( 
                    $data->__GET('str_numero_oc'),
                    $data->__GET('fecha_ingreso_oc'),
                    $data->__GET('str_condicion_pago_oc'),
                    $data->__GET('id_pedido')
                )
            );
        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }

      public function getResultados($arreglo)
      {
        $rows = array();
      while($row = $arreglo->fetch_array(MYSQLI_BOTH))//MYSQLI_ASSOC array asociativo, MYSQLI_NUM array numérico
      {
        $rows[] = $row;
      }

      return $rows;
    }


}

class UtilHelper {
   /* Crea un string codificado a partir de un array
   * @param Array array: array asociativo clave => valor
   * @return cadena de texto con el array listo para insertarse en BD
   */
   static function arrayEncode($array){
      return base64_encode(json_encode($array));
  }

   /* Crea un array a partir de un string codificado
   * @param String array_texto : string codificado de un array asociativo clave => valor
   * @return Array php
   */
   static function arrayDecode($array){
      return json_decode((base64_decode($array)),true);
  }
}
?>
