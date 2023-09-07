<!-- INSERTAR UN REGISTRO -->
<html>
<head>
<title>Insertar Registro</title>
</head>

<body >
<?php
//Establecemos los datos para la conexion a la base
$servidor = "localhost";
$usuario = "root";
$password = "";
$BaseDatos ="base";

//Nos conectamos a la base
$conexion = new mysqli($servidor, $usuario, $password, $BaseDatos);

//Ejecutamos un select y contamos las filas que tiene la tabla
$result = $conexion->query("select * from clientes");
echo "Num de filas al iniciar: ".$result->num_rows. "</br>";

// deshabilitamos el autocommit para llevar a cabo la transacción
//con varias sentencias
$conexion->autocommit(false);

// Insertamos dos registros en la base de datos
$conexion->query("INSERT INTO clientes (id,nombre) VALUES(1,’Juan’);");
$conexion->query("INSERT INTO clientes (id,nombre) VALUES(2,’Jose’);");

// Ejecutamos el commit, esto hace que los cambios sean permanentes y se
// vuelven visibles para los otros usuarios.
$conexion->commit();

// Ejecutamos un select y contamos las filas que tiene la tabla
$result = $conexion->query("select * from clientes");
echo "Num de filas despues de ejecutar el commit: ".$result->num_rows."</br>";

// Insertamos dos registros en la base de datos
$conexion->query("INSERT INTO clientes (id,nombre) VALUES(3,’Ana’);");
$conexion->query("INSERT INTO clientes (id,nombre) VALUES(4,’Sofia’);");

// Ejecutamos un select y contamos las filas que tiene la tabla
$result = $conexion->query("select * from clientes");
echo "Numero de filas despues de insertar dos registros mas:". $result->num_rows."</br>";

// Ejecutamos el rollback para deshacer los cambios.
$conexion->rollback();

// Ejecutamos un select y contamos las filas que tiene la tabla
$result = $conexion->query("select * from clientes");
?>
</body>
</html>