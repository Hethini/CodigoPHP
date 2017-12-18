<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="estilo.css">
</head>
<body>
	<div class="container">
		<a href="login.php"><img src="img\home.png" alt="Home" class="home"></a>
		<span class="cad"><a href="perfil.php"><img src="img\perfil.png" alt="Perfil" class="perfil"></a></span>
	
<?php
session_start(); // Inicia a sessão

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

//echo "User = ".$_POST["userID"]." e senha = ".$_POST["publicacao"];

$sql = "INSERT INTO `post`(`id_U`, `publicacao`) 
		VALUES ('".$_SESSION['userID']."', '".$_POST["publicacao"]."')";

if ($conn->query($sql) === TRUE) {
    echo "<br><br><br>Publicação realizada com sucesso!";
} else {
    echo "Erro: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>

</div>
</body>
<html>