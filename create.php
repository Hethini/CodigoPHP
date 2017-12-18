<?php
$servername = "localhost"; //*** dados para acesso local 
$username = "root";
$password = "";
$dbname = "sexlog";

// Estabelecendo conexão
$conn = new mysqli($servername, $username, $password, $dbname);
// Confere sucesso da conexão
if ($conn->connect_error) {
    die("Problemas ao conectar: " . $conn->connect_error);
} 

// Create database
$sql = "CREATE TABLE post (
id_P INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
id_U INT(6) NOT NULL,
comentario VARCHAR( 255 ) NOT NULL,
reg_date TIMESTAMP
)";
if ($conn->query($sql) === TRUE) {
    echo "Tabela criada com sucesso!";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>