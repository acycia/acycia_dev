<?php require_once('Connections/conexion1.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "usuario.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
mysql_select_db($database_conexion1, $conexion1);
$query_n_ciudad = "select * FROM Tbl_ciudades_col ";
$n_ciudad = mysql_query($query_n_ciudad, $conexion1) or die(mysql_error());
$row_n_ciudad = mysql_fetch_assoc($n_ciudad);
$totalRows_n_ciudad = mysql_num_rows($n_ciudad);
$row2 = mysql_fetch_array($n_ciudad);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Terabyte</title>



<script language="javascript" type="text/javascript">


function precio_prod(pro) {


document.formu_ord.precio.value=pro.value;



}

</script>

</head>


<body bgcolor="#ebf2fe" style='width:100%; height:1000px;'>



<div align='center' style='margin-top:50px; margin-left:50px;'>
 <form name="form1" METHOD="POST" action="">

<table  align='center' style='width:400px;  border-style:outset; border-color:#0066FF; font-size:14px;' cellpadding='14' cellspacing='3'>

    <tr> 
        <td colspan="4" style='text-align:center;' ><input type='text' readonly="readonly" size='60' value="Configura el ordenador a tu gusto" style='border:none;text-align:center; color: #B90D07; font-weight:bold; background-color: #ebf2fe;'> 
        </td>

    </tr>



    <tr>
        <td>Placa base</td>
        <td>
<?php
	 //CONSULTA CIUDADES     	
     // $query_n_ciudad="select * from ciudades ";
     if(!$result3=mysql_query($query_n_ciudad)) error($query_n_ciudad);
     //if(mysql_num_rows($result3 > 0)) {
     $row3 = mysql_fetch_array($result3);
     $apuntador3=$row3['id_ciudad'];	 
     //}
     echo "<select name='ciudad_c'onBlur='DatosCiudad('1',''ciudad_c',form1.ciudad_c.value);' onchange='Javascript:document.form1.txtdato.value=this.value;document.form1.txtdato2.value=this.value;document.form1.txtdato3.value=this.value'>";
	  if ($row3[0]==$row3[1]){
     echo "<option  value='$row3[nombre_ciudad]'>$row3[1]"; 
	  }else{ 
       echo "<option value='$row3[nombre_ciudad]'>$row3[1]"; 
     	 
     }
     while ($row3=mysql_fetch_array($result3)) {
     echo '<option value='.$row3["ind_ciudad"]; //aqui imprimo lo que quiero en los demas campos
     echo ' >';
	// $indica=$row3["ind_ciudad"];
	 echo $row3["nombre_ciudad"]; 
     
     }
	 echo '</select>';
	 
	 echo "<input name='txtdato2' value=''>";
     echo "<input name='txtdato3' value=''>";
			
   ?>    
    
    
            </td>

            <td colspan='2' style='text-align:center;' ><input type='text' readonly="readonly"  name='precio' style='border:none;text-align:center; color: #B90D07; background-color: #ebf2fe;'>  </td>
    
    </tr>

   <?php echo "<input name='txtdato' value=''>"; ?>
</table>
</form>

</div>


</body>
</html>
