<!DOCTYPE html>
<html>
<head>

<link rel="stylesheet" type="text/css" href="estilo.css">
</head>
<body>
<div class="container" style="background-color:#f1f1f1">
<div>
<a href="login.php"><img src="img\home.png" alt="Home" class="home"></a>
<span class="titulo"><b>Home</b></a></span>
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

//verifica a página atual caso seja informada na URL, senão atribui como 1ª página 
    $pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1; 
	
//***** sql para buscar qual user ta logado
if (!isset($_POST["uname"]))
{
	$sql = "SELECT `id`, `nome` FROM `usuarios`  
		WHERE id = ".$_SESSION['userID'];
}else
{	
	$sql = "SELECT `id`, `nome`, `senha` FROM `usuarios`  
			WHERE nome = '".$_POST["uname"]."' AND senha = '".$_POST["psw"]."'";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Resultado
    while($row = $result->fetch_assoc()) {
		$_SESSION['userID'] = $row["id"];

		echo " Seja bem vindo(a) ".$row["nome"]."!";		
    }
	
	
	//******** sql pra contar quantos posts nao lidos para o user logado
	$sql = "SELECT COUNT(status) as count FROM comentarios,post WHERE status = 1 AND comentarios.id_p = post.id_P AND post.id_U = ".$_SESSION['userID']." AND comentarios.id_U !=".$_SESSION['userID'];
	
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		// Resultado
		while($row = $result->fetch_assoc())
		{	
			echo "<br>Voc&ecirc possui <button id=\"myBtn\" class=\"button\">".$row["count"]."</button> notifica&ccedil&otildees.<br>";
		}
		?>
					
					<!-- janela Modal -->
					<div id="myModal" class="modal">

					  <!-- Modal conteudo -->
					  <div class="modal-content">
						<div class="modal-header">
						  <span class="close">&times;</span>
						  <h2>Notifica&ccedil&otildees</h2>
						</div>
						<div class="modal-body">
						<?php
							$sql = "SELECT usuarios.nome, post.publicacao
									FROM comentarios,post, usuarios
									WHERE status = 1 AND comentarios.id_p = post.id_P AND post.id_U = ".$_SESSION['userID']." AND usuarios.id = comentarios.id_U AND comentarios.id_U !=".$_SESSION['userID'];
							
							$result = $conn->query($sql);

							if ($result->num_rows > 0) {
								// Resultado
								while($row = $result->fetch_assoc())
								{	
									echo "<p><b>".$row["nome"]."</b> comentou na sua publi&ccedil&atildeo:<br>'".$row["publicacao"]."'<p>";
								
								}
							}
							else
							{
								echo "<p>Nenhum novo coment&aacuterio<p>";
							}
						?>
						</div>
					  </div>

					</div>

					<script>
					
					var modal = document.getElementById('myModal');
					var btn = document.getElementById("myBtn");
					var span = document.getElementsByClassName("close")[0];

					btn.onclick = function() {
						modal.style.display = "block";
					}
					span.onclick = function() {
						modal.style.display = "none";
					}
					window.onclick = function(event) {
						if (event.target == modal) {
							modal.style.display = "none";
						}
					}
					</script>
			<?php
		
	}
	
	
	
	echo "	<div id=\"caixa-div\">
			<div id=\"caixa\">
				  <form action=\"publicar.php\" method=\"post\">
				
					<label for=\"lblpublicacao\">Faça uma nova publi&ccedil&atildeo</label>
					<textarea id=\"publicacao\" name=\"publicacao\" placeholder=\"Digite algo..\" style=\"height:100px\" required></textarea>

					<input type=\"submit\" value=\"Publicar\">
				  </form>
			</div>
			</div>";
			
	//*************** INICIO DA listagem de todos os posts ***********
	$sql = "SELECT post.id_P,post.publicacao,post.reg_date,usuarios.nome FROM post,usuarios  
		WHERE post.id_U = usuarios.id order by reg_date DESC";
	
	$result = $conn->query($sql);
	//conta o total de posts feitos 
    $total = $result->num_rows;
	//calcula o número de páginas arredondando o resultado para cima ---- no maximo 10 posts por pagina 
    $numPaginas = ceil($total/10);
	//variavel para calcular o início da visualização com base na página atual 
    $inicio = (10*$pagina)-10; 
 
    //seleciona os itens por página 
	
    $sql = "SELECT post.id_P,post.publicacao,DATE_FORMAT(post.reg_date, \"%d/%m/%Y às %H:%i\") as reg_date,usuarios.nome FROM post,usuarios 
			WHERE post.id_U = usuarios.id  order by reg_date DESC limit ".$inicio.",10"; 
			
	
    $result = $conn->query($sql); 
   
		if ($result->num_rows > 0) {
			// Resultado dos posts feito por todos usuários com cadastro
			echo "<div class=\"container\">
					<table> ";
			while($row = $result->fetch_assoc()) {
				echo "
					  <tr>
						<td>Publicado por ". $row["nome"]." em ".$row["reg_date"]."</td> 
					  </tr>
					  <tr>
						<td><form action=\"comentarios.php\" method=\"post\">
							<input type=\"hidden\" name=\"postID\" value=".$row["id_P"]." /> 
							". $row["publicacao"]." 
							<br><br><input type=\"submit\" value=\"Ver comentários\">
							</form>
						</td>
					  </tr>";		
			}
			echo "</table>
					<div class=\"center\">
						<div class=\"pagination\">
							<a href='login.php?pagina=1'>&laquo;</a>";
							//exibe a paginação 
							for($i = 1; $i < ($numPaginas+1); $i++) 
							{
								if ($i == $pagina)
									echo "<a class=\"active\" href='login.php?pagina=".$i."'>".$i."</a>";
								else 
									echo "<a href='login.php?pagina=".$i."'>".$i."</a>";
							}			   
			echo "			<a href='login.php?pagina=".($i-1)."'>&raquo;</a>
						</div>
					</div>
				</div>";
		} else {
			echo "Nenhuma publi&ccedil&atildeo feita. ";
		}
//**************************************************************************
} else {
	echo "Desculpe mas o usuário ou a senha não foram encontrados.<br>Deseja se <a href=\"cadastro.html\">cadastrar</a> ou voltar para a tela de <a href=\"index.html\">login</a>?";
}
$conn->close();
?>

  </div>
</body>
</html>