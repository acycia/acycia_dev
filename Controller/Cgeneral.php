<?php
//Llamada al modelo
require_once("Models/Mgeneral.php");

/* 

$logs=new omGeneral();
$datos=$logs->get_ordenc();
require_once("views/view_compras.php");*/

class CgeneralController{

      private $logs;
    private $proveedores;
    private $insumo; 

    public function __CONSTRUCT(){
        $logs = new omGeneral();
    }

    public function Index(){ 
        $logs = new omGeneral();//instanciamos la clase omGeneral del Modelo Cgeneral
        self::Cgeneral();
    }
 
    public function Control(){ 
        $proveedores = new omGeneral();//instanciamos la clase omGeneral del Modelo Cgeneral 
        $this->proveedores=$proveedores->get_Provee();//aqui llamo las funciones del modelo 
        
        self::Cgeneral();
    }


    public function Menu(){ 
        $logs = new omGeneral();
        //$this->ordenc=$logs->get_Menu();//aqui llamo las funciones del modelo
        $vista = 'orden_compra_cl2.php';
        self::Cgeneral($vista);
    }
 

    public function Actualizar(){
 
             //$data = json_decode($_POST['datos']); 

             $this->logs =  new omGeneral();  
             //$this->logs->Update($_REQUEST['url'], $_REQUEST['id'],$_REQUEST['proceso']); 
          
              $respuesta =  $this->logs->Update($_REQUEST['ids'],$_REQUEST['valorid'],$_REQUEST['name'],$_REQUEST['valorc'],$_REQUEST['tabla']);  
              echo $respuesta; 
             //header("Location:".$_REQUEST['url'] );  
    }


    public function insertLogs($tabla,$columnas,$data,$vista='',$modificacion=''){
       
        $this->logs =  new omGeneral();
        $this->logs->RegistrarLogs($tabla, $columnas, $data,$vista,$modificacion);
    
        self::consultarlog($tabla,'codigo_id',$data);//envio el correo al modificar perfil
    
    }


    public function consultarlog($tabla='',$columna='',$valorid='',$sms='' ){
        $this->logs =  new omGeneral();

        $_REQUEST['valorid'] = $_REQUEST['valorid'] ==''? $valorid['id_c'] : $_REQUEST['valorid'];
        $_REQUEST['tabla']=$_REQUEST['tabla']==''?$tabla:$_REQUEST['tabla'];
        $_REQUEST['columna']=$columna==''? $_REQUEST['columna'] :$columna;
        $_REQUEST['sms'] = $_REQUEST['sms']=='' ? $valorid['nit_c'] : $_REQUEST['sms'];
        $respuesta =  $this->logs->ConsultarLogsExiste($_REQUEST['tabla'],$_REQUEST['columna'],$_REQUEST['valorid'] );
       
        
        $hoy = date("Y-m-d"); 
        if($respuesta!="" && ($respuesta == $hoy)){
    
       
            $this->logs->envioCorreos($_REQUEST['sms']); 
            $respuesta=1; 
        }else{
            $respuesta=11;
        }
           echo $respuesta; 


    }


    public function consultarIdes($tabla, $columna){
     
        $this->ides =  new omGeneral();
 
         $ides = $this->ides->buscarIds($tabla, $columna);

         return $ides;
    }


    public function consultarlogGeneral(){
     
        $this->logs =  new omGeneral();

        $_REQUEST['valorid'] = $_REQUEST['valorid'] ==''? $valorid['id_c'] : $_REQUEST['valorid'];
        $_REQUEST['tabla']=$_REQUEST['tabla']==''?$tabla:$_REQUEST['tabla'];
        $_REQUEST['columna']=$columna==''? $_REQUEST['columna'] :$columna;
        $_REQUEST['columna2']=$columna2==''? $_REQUEST['columna2'] :$columna2;
        //$respuesta =  $this->logs->ConsultarNormal($_REQUEST['tabla'],$_REQUEST['columna'],$_REQUEST['valorid'] );
  
        $respuesta =  $this->logs->ConsultarNormal($_REQUEST['tabla']," WHERE YEAR(".$_REQUEST['columna2'].") ='".$_REQUEST['fecha']."'  AND ", $_REQUEST['columna'],$_REQUEST['columna2'], $_REQUEST['valorid'],$_REQUEST['sms'], $_REQUEST['fecha'], "GROUP BY nombre ORDER BY  fecharecepcion DESC", " if( SUM(inventariofinal)  <  20,  nombre,'' ) AS insumoveinte "); 
       
    
        if($_REQUEST['sms']!="" ){
   
            $this->logs->envioCorreos($_REQUEST['sms']); 
            
        } 
         echo json_encode($respuesta); 


    }

    public function consultarTodos(){
     
        $this->todos =  new omGeneral();
 
        $_REQUEST['valorid2'] = $_REQUEST['valorid2'] !='' ? " AND ".$_REQUEST['columna2']." = '". $_REQUEST['valorid2']."'  " : "";
        $_REQUEST['valorid3'] = $_REQUEST['valorid3'] !='' ? " AND ".$_REQUEST['columna3']." = '". $_REQUEST['valorid3']."'  " : "";

        $respuesta =  $this->todos->ConsultarTodos($_REQUEST['tabla'], $_REQUEST['distinct'], " WHERE ".$_REQUEST['columna1']." ='".$_REQUEST['valorid1']."'  ".$_REQUEST['valorid2']."  ".$_REQUEST['valorid3']."  ", ""); 
         if($respuesta){
            echo json_encode($respuesta[0]); 
         }else{
            $respuesta[0]='null';
            echo json_encode($respuesta[0]);
         }
 
    }
    

    //BORRA
    public function Eliminar(){
        $this->logs =  new omGeneral(); 
        $this->logs->Eliminar($_REQUEST['id'],$_REQUEST['columna'],$_REQUEST['proceso'],$_REQUEST['master'] );
        header('Location: index.php');
    }

     //INSERTAR
    public function insertarGen($tabla,$columna, $datos,$vista=''){
     
        $this->todos =  new omGeneral(); 
  
         $respuesta = $this->todos->RegistrarGen($tabla,$columna,$datos);
 
        if($vista){
         header($vista);  
        }
    }
 
    //ACTUALIZAR
      public function actualizarGen($ids,$valorid,$name,$valorc,$tabla){ 
        $this->sicoq =  new omGeneral(); 
        $this->sicoqs = $_REQUEST;
        //ids,valorid,valores,tabla,url  
        $respuesta =  $this->logs->Update($ids,$valorid,$name,$valorc,$tabla);  
        echo $respuesta;        
      } 




      public function multiConsultass($consulta1,$consulta2,$order='')
      {

          try 
          {
              if($consulta1!='' && $consulta2!=''  ){ 
                 //echo " $consulta1 ($consulta2) ";die;
                  $stm = $this->todos->query(" $consulta1 ($consulta2) ");
                 if($stm){
                  while($filas=$stm->fetch_assoc()){
                      $this->todos[]=$filas;
                  }

              return $this->todos;
                 }
              }

          } catch (Exception $e) 
          {
              die($e->getMessage());
          }
      }




       //LLENAR CAMPOS
      public function llenarCampos($tabla, $condicion, $orden='', $distinct='' ){  
         
          $this->ides =  new omGeneral();
         
          $ides = $this->ides->buscarCampos($tabla, $condicion, $orden, $distinct);

          return $ides;

      }

       //LLENAR SELECTS
      public function llenarSelects($tabla, $condicion, $orden='', $distinct='' ){  
         
          $this->ides =  new omGeneral();
         
          $ides = $this->ides->buscarListar($tabla, $condicion, $orden, $distinct);

          return $ides;

      }

    public function Cgeneral($vista=''){ 
        if($vista){ 
          require_once("views/".$vista);  //header('Location:'.$vista);  
        }
        else{
          require_once("orden_compra_cl2.php");
        }
    }




    public function get_materiaPrima($tabla, $condicion='', $order){   

        try 
        {   
            //echo "SELECT * FROM $tabla $condicion $order";die;
            $consulta=$this->conexion->query("SELECT * FROM $tabla $condicion $order");
            while($filas=$consulta->fetch_assoc()){
                $this->maquina[]=$filas;
            }
    
            return $this->maquina;
        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }


 

}



?>
