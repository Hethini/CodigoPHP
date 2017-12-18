<!DOCTYPE html>
<html>
<head>
<style>
input[type=text], select, textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    margin-top: 6px;
    margin-bottom: 16px;
    resize: vertical;
}

input[type=submit] {
    background-color: #4CAF50;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

input[type=submit]:hover {
    background-color: #45a049;
}

.container {
    border-radius: 5px;
    background-color: #f2f2f2;
    padding: 20px;
}

</style>
</head>
<body>
  <div class="container" style="background-color:#f1f1f1">
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

//echo "User = ".$_POST["uname"]." e senha = ".$_POST["psw"];

$sql = "INSERT INTO `usuarios`(`nome`, `sobrenome`, `email`, `senha`) 
		VALUES ('".$_POST["uname"]."', '".$_POST["sbrname"]."', '".$_POST["email"]."', '".$_POST["psw"]."')";

if ($conn->query($sql) === TRUE) {
    echo "Cadastro efetuado com sucesso!";
} else {
    echo "Erro: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
<span class="cad"><br>Voltar para a tela de <a href="index.html">login</a></span>
</div>
</body>
</html>