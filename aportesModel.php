<?php

$conexion1 = mysql_pconnect($hostname_conexion1, $username_conexion1, $password_conexion1) or trigger_error(mysql_error(),E_USER_ERROR); 
class aportesModel {

    public static function insert($data) {
			mysql_query("INSERT INTO TblAportes(id_aporte, salarioBasico, auxilioTrans, cesantias, interesCesantias, prima, salud, pension, vacaciones, cajaCompensacion, sena, arl) VALUES ('{$data['id_aporte']}','{$data['salarioBasico']}', '{$data['auxilioTrans']}','{$data['cesantias']}','{$data['interesCesantias']}','{$data['prima']}','{$data['salud']}','{$data['pension']}','{$data['vacaciones']}','{$data['cajaCompensacion']}','{$data['sena']}','{$data['arl']}')");
    }
    public static function getAll($aportes) {
        return mysql_query("SELECT * FROM TblAportes ORDER BY id_aporte DESC)");
    }
    public static function delete($id_aporte) {
        mysql_query("DELETE FROM TblAportes WHERE id_aporte=$id_aporte");
    }
    public static function find($id_aporte) {
        $result = mysql_query("SELECT * FROM TblAportes WHERE id_aporte=$id_aporte");
        return mysql_fetch_assoc($result);
    }
    public static function update($data) {
        $query = "UPDATE TblAportes SET salarioBasico='{$data['salarioBasico']}', auxilioTrans='{$data['auxilioTrans']}', cesantias='{$data['cesantias']}', interesCesantias='{$data['interesCesantias']}', prima='{$data['prima']}', salud='{$data['salud']}', pension='{$data['pension']}', vacaciones='{$data['vacaciones']}', cajaCompensacion='{$data['cajaCompensacion']}', sena='{$data['sena']}', arl='{$data['arl']}'";
        mysql_query($query);
    }

}

