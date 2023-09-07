<?php require_once('Connections/conexion1.php'); ?>
<?php
$consulta = $_GET["consulta"]; 
//TIPO DESPERDICIO
mysql_select_db($database_conexion1, $conexion1);
switch ($consulta) { 
    case "tipo_maquina": 
        //Select per les Seccions 
        $busqueda="SELECT * FROM Tbl_reg_tipo_desperdicio WHERE codigo_rtp='1' ORDER BY id_rtp ASC"; 
        $sql = mysql_query($busqueda, $kon) or die("Error de busqueda"); 
        // Comienzo a imprimir el select 
        echo "<select class='combo' id='tipo_maquina' name='tipo_maquina'>"; 
        while($reg=mysql_fetch_row($sql)) 
        { 
            // Paso a HTML acentors y ñ para su correcta visualizacion 
            $reg[1]=htmlentities($reg[1]); 
            // Imprimo las opciones del select                     
            echo "<option value='".$reg[0]."'>".$reg[2]."</option>"; 
        }             
        echo "</select>"; 
    break;     
    case "id_seccio": 
        //Select per les Seccions 
        $busqueda="SELECT * FROM Tbl_reg_tipo_desperdicio WHERE codigo_rtp='2' ORDER BY id_rtp ASC"; 
        $sql = mysql_query($busqueda, $kon) or die("Error de busqueda"); 
        // Comienzo a imprimir el select 
        echo "<select class='combo' id='id_seccio' name='id_seccio'>"; 
        while($reg=mysql_fetch_row($sql)) 
        { 
            // Paso a HTML acentors y ñ para su correcta visualizacion 
            $reg[1]=htmlentities($reg[1]); 
            // Imprimo las opciones del select 
            echo "<option value='".$reg[0]."'>".$reg[2]."</option>"; 
        }             
        echo "</select>"; 
    break; 
}
?>