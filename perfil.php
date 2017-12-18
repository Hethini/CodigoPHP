<!DOCTYPE html>
<html>
<head>

<link rel="stylesheet" type="text/css" href="estilo.css">
</head>
<body>
<div class="container" style="background-color:#f1f1f1">
<div>
<a href="login.php"><img src="img\home.png" alt="Home" class="home"></a>
<span class="titulo"><b>Perfil</b></a></span>
<span class="cad"><a href="perfil.php"><img src="img\perfil.png" alt="Perfil" class="perfil"></a></span>
</div>
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
			
			
//*************** Formumlario para uma nova publicação **********
echo "	<div id=\"caixa-div\">
			<div id=\"caixa\">
				  <form action=\"publicar.php\" method=\"post\">
				
					<label for=\"lblpublicacao\">Faça uma nova publi&ccedil&atildeo</label>
					<textarea id=\"publicacao\" name=\"publicacao\" placeholder=\"Digite algo..\" style=\"height:100px\"></textarea>

					<input type=\"submit\" value=\"Publicar\">
				  </form>
			</div>
			</div>";
//***************************************************************


//*************** INICIO DA AREA DE POSTAGEM DO USUARIO LOGADO ***********
$sql = "SELECT `id_P`,`id_U`,`publicacao`,DATE_FORMAT(reg_date, \"%d/%m/%Y às %H:%i\") as reg_date from post  
		WHERE id_U = '".$_SESSION['userID']."' order by reg_date DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Resultado dos posts feito pelo usuário logado
	echo "<div class=\"container\">
			
			<table> ";
    while($row = $result->fetch_assoc()) {
        echo "<tr>
				<td><form action=\"comentarios.php\" method=\"post\"><input type=\"hidden\" name=\"postID\" value=".$row["id_P"]." />Publicado em " . $row["reg_date"]." </td> 
			</tr>
			<tr>	
				<td> " . $row["publicacao"]. "<br><br><input type=\"submit\" value=\"Ver comentários\"></form></td>
			  </tr>";		
    }
	echo "
		</table></div>";
} else {
   
	echo "Nenhuma publi&ccedil&atildeo feita.";
}
//**************************************************************************

$conn->close();
?>
</div>
</body>
</html>