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
		//verifica a página atual caso seja informada na URL, senão atribui como 1ª página 
		$pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1; 

		//echo "User = ".$_POST["userID"]." e senha = ".$_POST["comentario"];
		$idPost = $_POST['postID'];
		$sql = "INSERT INTO `comentarios`(`id_U`,`id_P`, `comentario`) 
				VALUES ('".$_SESSION['userID']."', '".$_POST['postID']."', '".$_POST["comentario"]."')";

		if ($conn->query($sql) === TRUE) {
			echo "<br><br>Coment&aacuterio realizado com sucesso!";
		} else {
			echo "Erro: " . $sql . "<br>" . $conn->error;
		}
		
		echo "<br><br><br><form action=\"comentarios.php\" method=\"post\">
				<input type=\"hidden\" name=\"postID\" value=".$_POST['postID']." /> 
				<input type=\"submit\" value=\"Voltar\">
			</form>";
		$conn->close();
		?>
	</div>
</body>
<html>