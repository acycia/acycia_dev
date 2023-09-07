<?php
//Llamada al modelo
require_once("Models/Referencias.php");
/* 

$referencia=new Referencias();
$datos=$referencia->get_ordenc();
require_once("views/view_Referencias.php");*/

class CreferenciasController{

	private $referencia;

	public function __CONSTRUCT(){
		$referencia = new Referencias();  
        /*$this->referencia=$referencia->get_Listar();//aqui llamo las funciones del modelo
        self::Referencias();*/
    }

    public function Index(){
    	$referencia = new Referencias();  
    	$this->referencia=$referencia->get_Listar();//aqui llamo las funciones del modelo
    	self::Referencias();
    }


    public function Crud(){ 
    	$referencia = new Referencias();
    	if(isset($_REQUEST['id'])){
    		$this->referencia = $referencia->Obtener($_GET['tabla'],$_GET['columna'],$_REQUEST['id']);//aqui llamo las funciones del modelo
    	}

        self::Referencias();//le digo que muestre en vista edit
    }


    public function Historico($vista=''){
    	$myObject = new Referencias();
    	$this->historico =  new Referencias();

    	if(isset($_REQUEST['id'])){ 
    		$this->historico=$myObject->Obtener('tbl_referencia_historico','id_ref',$_REQUEST['id']);
    	} 
        
        $myObject->Registrar("tbl_referencia_historico", "id_ref,cod_ref,version_ref,n_egp_ref,n_cotiz_ref,tipo_bolsa_ref,material_ref,Str_presentacion,Str_tratamiento,ancho_ref,N_repeticion_l,N_diametro_max_l,N_peso_max_l,N_cantidad_metros_r_l,N_embobinado_l,Str_referencia_m,Str_linc_m,largo_ref,solapa_ref,b_solapa_caract_ref,bolsillo_guia_ref,str_bols_ub_ref,str_bols_fo_ref,B_cantforma,bol_lamina_1_ref,bol_lamina_2_ref,calibre_ref,peso_millar_ref,Str_boca_entr_p,Str_entrada_p,Str_lamina1_p,Str_lamina2_p,B_troquel,B_precorte,N_fuelle,B_fondo,impresion_ref,num_pos_ref,cod_form_ref,adhesivo_ref,estado_ref,registro1_ref,fecha_registro1_ref,registro2_ref,fecha_registro2_ref,B_generica,calibreBols_ref,peso_millar_bols,precorte_cuerpo,precorte_solapa,tipoLamina_ref,tipoCinta_ref,modifico", $this->historico);
  
      	 $vista =!'' ? header('Location:'.$vista)  : header('Location: index.php');
    }


    public function Guardar(){
    	$referencia = new Referencias();
    	$referencia->id_pedido = $_REQUEST['id_pedido'];
    	$referencia->str_numero_oc = 'pruebas';
    	$referencia->fecha_ingreso_oc = $_REQUEST['fecha_ingreso_oc'];
    	$referencia->str_condicion_pago_oc = $_REQUEST['str_condicion_pago_oc']; 
    	$referencia->id_pedido > 0 ? $this->referencia->Actualizar($referencia) : $this->referencia->Registrar($referencia);

    	header('Location: index.php');
    }


    public function Eliminar(){
    	$this->referencia->Eliminar($_REQUEST['id']);//aqui llamo las funciones del modelo
    	header('Location: index.php');
    }

    public function Referencias(){ 

    	require_once("views/view_referencias.php");
    }

    public function listadonormal(){ 
    	require_once("views/view_referencias.php?id=".$_REQUEST['id'].'&columna=id_pedido&tabla=tbl_referencia_historico' );
    }


}



?>
