<?php
require ("./envio_correo/envio_correos.php");

class Msolicitud_compras{
	private $consecutivo;
    private $area;
    private $nombre;
    private $fecha;
    private $maquina;
    private $observaciones;
    private $encargado;
    private $autorizado;
    private $correo;
    private $id;
    private $material;
    private $cantidad;
    private $oc;
    private $estado;

    public $db;

    public function __construct($id, $consecutivo, $area, $nombre, $fecha, $maquina, $observaciones, $encargado, $autorizado, $correo ){   
        $this->id = $id;
        $this->consecutivo = $consecutivo;
        $this->area = $area;
        $this->nombre = $nombre;
        $this->fecha = $fecha;
        $this->maquina = $maquina;
        $this->observaciones = $observaciones;
        $this->encargado = $encargado;
        $this->autorizado = $autorizado;
        $this->correo = $correo;
    }
 
    public function setConsecutivo($consecutivo){
        $this->consecutivo = $consecutivo;
    }

    public function getConsecutivo(){
        return $this->consecutivo;
    }

    public function setMaquina($maquina){
        $this->maquina = $maquina;
    }

    public function getMaquina(){
        return $this->maquina;
    }
    
    public function traerConsecutivo(){
        $this->db = Conectar::conexion();

        try {
            $id = $this->db->query("SELECT id_solicitud, codigo FROM tbl_info_solicitud_compras ORDER BY id_solicitud DESC LIMIT 1");
            $result = $id->fetch_assoc();
            return $result;
            
        } catch (\Throwable $th) {
            die($th->getMessage());
        }
        
    }

    public function guardarSolicitud(){
        $this->db = Conectar::conexion();
        
        try {
            $query = "INSERT INTO `tbl_info_solicitud_compras`(`codigo`, `area`, `nombre`, `fecha`, `maquina`, `observaciones`, `responsable`, `autorizado`, `correoaut`) VALUES ('$this->consecutivo','$this->area','$this->nombre','$this->fecha','$this->maquina','$this->observaciones','$this->encargado','$this->autorizado', '$this->correo')";
            $result = $this->db->query($query);
            $id = $this->db->insert_id; //obtengo el ultimo id generado en el insert
        
            if($result)
                return $id;
            return false;
        } catch (\Throwable $th) {
            die($th->getMessage());
        }
    }

    public function guardarItems($idGenerado, $insumo, $cantidad, $oc, $estado = 'PENDIENTE'){
        $this->db = Conectar::conexion();
        $num = self::elementosArray($insumo);

        try {
           for ($i=0; $i < $num; $i++) { 
               $query = "INSERT INTO `tbl_listado_materiales_compras`(`id_solicitud`,`codigo`, `material`, `cantidad`, `oc`, `estado`) VALUES ('$idGenerado', '$this->consecutivo','$insumo[$i]','$cantidad[$i]','$oc[$i]','$estado[$i]')";
               $guardar = $this->db->query($query);
           }
           if($guardar)
                return true;
            return false;

        } catch (\Throwable $th) {
            die($th->getMessage());
        }
    }

    public function actualizarDatos(){
        $this->db = Conectar::conexion();

        try {
            $query = "UPDATE tbl_info_solicitud_compras SET area='$this->area',nombre='$this->nombre',fecha='$this->fecha',maquina='$this->maquina',observaciones='$this->observaciones',responsable='$this->encargado',autorizado='$this->autorizado' WHERE id_solicitud = '$this->id' ";
            $guardar = $this->db->query($query);
            
            if($guardar)
                return true;
            return false;
        } catch (\Throwable $th) {
            die($th->getMessage());
        }
    }

    public function actualizarItems($id, $insumo, $cantidad, $oc, $estado){
        $this->db = Conectar::conexion();
        $num = self::elementosArray($insumo);
        
        try {
            for ($i=0; $i < $num; $i++) { 
                $query = "UPDATE `tbl_listado_materiales_compras` SET `material`='$insumo[$i]',`cantidad`='$cantidad[$i]',`oc`='$oc[$i]',`estado`='$estado[$i]' WHERE id_material = $id[$i]";
                $guardar = $this->db->query($query);
            }

            if($guardar)
                return true;
            return false;
 
         } catch (\Throwable $th) {
             die($th->getMessage());
         }
    }



    public function elementosArray($array){
        $count = 0;
        for ($i=0; $i < sizeof($array); $i++) { 
            if($array[$i] != ""){
                $count = $count +1;
            }
        }
        return $count;
    }

    public function enviarEmail($insumos, $cantidad){
        $num = self::elementosArray($insumos);
        $envioCorreo = new EnvioEmails();
        //$to = $this->correo;
        $to = "lidersistemas@acycia.com";
        $to2 = 'andres85684@outlook.com';
        //$from = ;
        $asunto = 'Solicitud para aprobacion de compras';
        $body = "Hola se requiere autorizar a ".$this->nombre. ", los siguientes materiales: "."<br>";
        for ($i=0; $i < $num; $i++) { 
            $body = $body. $insumos[$i]."   cantidad: ".$cantidad[$i]."<br>"; 
        }
        

        $envioCorreo->enviar($to, $to2, '', '', $asunto, $body, '');
        
    }


}  
 

/* 
    public function Delete($tabla,$id,$columna,$proceso,$master)
    {
        try 
        {  
                
            if($master==1){
              //Elimina Maestro 
               $stm = $this->db->query("DELETE FROM tbl_sicoq WHERE $columna = '$id' "); 
              //Elimina Items
               $stmi = $this->db->query("DELETE FROM $tabla WHERE $columna = '$id' ");                
            }else{
               //Elimina Items
               $stmi = $this->db->query("DELETE FROM $tabla WHERE id_i = $id ");      

            }
           

           
        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }



} */



