<!DOCTYPE html>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<style>
input[type=text], textarea {
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
    background-color: #A30723;
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

table {
	background-color: #dddddd;
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}

tr:nth-child(even) {
    background-color: #f2f2f2;
}

#post-principal-div {
    width: 70%;
    float: center;
    margin: 0 auto;
}

#post-principal {
    height: 75px;
    background: #dddddd;
    border-radius: 10px;
    border: 1px solid #dddddd;
    margin: 5px;
}

#comentarios-div {
    width: 90%;
    float: center;
    margin: 0 auto;
}

#comentarios {
    
    background: #dddddd;
    border-radius: 10px;
    border: 1px solid #dddddd;
    margin: 5px;
}

#caixa-div {
    width: 90%;
    float: center;
    margin: 0 auto;
}

#caixa {
    
    background: #dddddd;
    border-radius: 10px;
    border: 1px solid #dddddd;
    margin: 5px;
}

span.cad {
    float: right;
    padding-top: 0px;
}

/* Change styles for span and cancel button on extra small screens */
@media screen and (max-width: 300px) {
    span.cad {
       display: block;
       float: none;
    }
    .cancelbtn {
       width: 100%;
    }
}
.center {
    text-align: center;
}

.pagination {
    display: inline-block;
}

.pagination a {
    color: black;
    float: left;
    padding: 8px 16px;
    text-decoration: none;
    transition: background-color .3s;
    border: 1px solid #C0C0C0;
}

.pagination a.active {
    background-color: #A30723;
    color: white;
    border: 1px solid #A30723;
}

.pagination a:hover:not(.active) {background-color: #C0C0C0;}

</style>
<link rel="stylesheet" type="text/css" href="estilo.css">
</head>
<body>
<div class="container" style="background-color:#f1f1f1">
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



//*************** INICIO DA AREA DE POSTAGEM DO USUARIO LOGADO ***********
//***** sql para buscar qual user ta logado
if (!isset($_POST["postID"]))
{
	$sql = "SELECT  usuarios.nome,post.id_P,post.id_U,post.publicacao,DATE_FORMAT(post.reg_date, \"%d/%m/%Y às %H:%i\") as reg_date from post,usuarios  
		WHERE post.id_P = '".$_SESSION['postID']."' AND post.id_U = usuarios.id";
}else
{	
	$_SESSION['postID'] = $_POST['postID'];
	$sql = "SELECT  usuarios.nome,post.id_P,post.id_U,post.publicacao,DATE_FORMAT(post.reg_date, \"%d/%m/%Y às %H:%i\") as reg_date from post,usuarios  
		WHERE id_P = '".$_POST['postID']."' AND post.id_U = usuarios.id";
}

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // Resultado dos posts feito 
	echo " <div id=\"post-principal-div\">
			<div id=\"post-principal\">
				<table> ";
					while($row = $result->fetch_assoc()) {
						$idDonoPost = $row["id_U"];
						echo "<tr>
								<th>Publicado por <b>".$row["nome"]."</b> em ".$row["reg_date"]."</th> 
							  </tr>
							  <tr>
								<td>".$row["publicacao"]."</td>
							  </tr>";		
					}
	echo		"</table></div>";
} else {
	echo "<h3>Nenhuma publi&ccedil&atildeo feita. ";
}
//**************************************************************************


//*************** INICIO DA AREA DE COMENTARIOS PARA O POST SELECIONADO ***********
					$sql = "SELECT * FROM `comentarios`
							WHERE id_P = '".$_SESSION['postID']."'";
					$result = $conn->query($sql);
					//conta o total de posts feitos 
					$total = $result->num_rows;
					//calcula o número de páginas arredondando o resultado para cima ---- no maximo 10 posts por pagina 
					$numPaginas = ceil($total/10);
					//variavel para calcular o início da visualização com base na página atual 
					$inicio = (10*$pagina)-10;
					$sql = "SELECT comentarios.id_C, comentarios.comentario, DATE_FORMAT(comentarios.reg_date, \"%d/%m/%Y às %H:%i\") as reg_date , comentarios.status, usuarios.id, usuarios.nome FROM comentarios,usuarios 
						WHERE comentarios.id_P = '".$_SESSION['postID']."' AND usuarios.id = comentarios.id_U order by reg_date DESC limit ".$inicio.",10";
					
					$result = $conn->query($sql); 
					if ($result->num_rows > 0) 
					{
																						
						// Resultado dos comentarios feitos para o post escolhido
						echo "<div id=\"comentarios-div\">
								<div id=\"comentarios\">
									<table> ";
										while($row = $result->fetch_assoc()) 
										{
											
											if($idDonoPost == $_SESSION['userID'])
											{
													//**** UPDATE para atualizar quais comentarios foram lidos
													$sql2 = "UPDATE comentarios
															SET status = 0
															WHERE comentarios.id_C = ".$row["id_C"];
													//atualizando os comentarios lidos
													if ($conn->query($sql2) === TRUE) {
														//echo "Atualização feita";
													} else {
														echo "Erro ao atualizar os coments lidos: " . $conn->error;
													}
											}
											
											echo "<tr>
													<td>".$row["id"]."</td>
													<td>Comentado por <b>". $row["nome"]."</b> em ".$row["reg_date"]."</td>
												</tr>
												<tr>
													<td>".$row["id_C"]."</td>
													<td> " . $row["comentario"]. "</td>
												</tr>";		
										}
						echo 		"</table>
										<div class=\"center\">
										<div class=\"pagination\">
											<a href='comentarios.php?pagina=1'>&laquo;</a>";
											//exibe a paginação 
											
											for($i = 1; $i < ($numPaginas+1); $i++) 
											{
												if ($i == $pagina)
													echo "<a class=\"active\" href='comentarios.php?pagina=".$i."'>".$i."</a>";
												else 
													echo "<a href='comentarios.php?pagina=".$i."'>".$i."</a>";
											}			   
						echo "			<a href='comentarios.php?pagina=".($i-1)."'>&raquo;</a>
									</div>
								</div>
							</div>
							";


					} else {
						
						echo "<br>Nenhum coment&aacuterio feito ainda. </div>";
					}


//**************************************************************************



//*************** Formumlario para uma nova publicação **********
				echo "	<div>
						  <form action=\"Comentar.php\" method=\"post\">
						<br><br><br>
							<label for=\"lblpublicacao\">Faça um coment&aacuterio</label>
							<textarea id=\"comentario\" name=\"comentario\" placeholder=\"Digite algo..\" style=\"height:200px\"></textarea>
							<input type=\"hidden\" name=\"postID\" value=".$_SESSION['postID']." />
							<input type=\"submit\" value=\"Comentar\">
						  </form>
						</div>";
//***************************************************************

		echo "</div>";

$conn->close();
?>

</div>
</body>
</html>