<?php
$serverName =   "172.27.4.35\SQLEXPRESS2019"; //serverName\instanceName
$connectionInfo = array( "Database"=>"neo_britanico", "UID"=>"sa", "PWD"=>"desarrollo", "Driver"=>"ODBC Driver 18 for SQL Server");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if( $conn ) {
     echo "Conexión establecida.<br />";
}else{
     echo "Conexión no se pudo establecer.<br />";
     die( print_r( sqlsrv_errors(), true));
}


/* Query que selecciona un nombre de columna inválida. */
$sql = "select count(*) from dbo.Cheque";

/* Ejecución de la query que fallará debido al nombre de columna incorrecto. */
$stmt = sqlsrv_query( $conn, $sql );
if( $stmt === false ) {
	echo "error??";
    if( ($errors = sqlsrv_errors() ) != null) {
        foreach( $errors as $error ) {
            echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
            echo "code: ".$error[ 'code']."<br />";
            echo "message: ".$error[ 'message']."<br />";
        }
    }
} else {
	echo "ok";
	print_r($stmt);
}

// Hacer que sea disponible para su lectura la primera (y en este caso única) fila del conjunto resultado.
if( sqlsrv_fetch( $stmt ) === false) {
     die( print_r( sqlsrv_errors(), true));
}

// Obtener los campos de la fila. Los índices de campo empiezan desde 0 y se deben obtener en orden.
// Recuperar los nombres de campo por su nombre no está soportado por sqlsrv_get_field.
$name = sqlsrv_get_field( $stmt, 0);
echo "Name: ";
echo $name;

echo "fin";

?>