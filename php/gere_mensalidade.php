<?php
include_once 'conexao.php';
session_start();

$moto_nome = "SELECT nome FROM motorista WHERE moto_cpf = '" . $_SESSION['moto_cpf'] . "'";
$link_moto = mysqli_query($sql, $moto_nome);
$linked_moto = mysqli_fetch_assoc($link_moto);

$checa_id = "SELECT trans_id FROM transporte WHERE moto_cpf ='" . $_SESSION['moto_cpf'] . "'";
$result_id = mysqli_query($sql, $checa_id);
$trans_id = mysqli_fetch_assoc($result_id)["trans_id"];

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<title>Pagamentos Mensais</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../css/estilo.css">
	<link rel="stylesheet" href="../css/gerenciamento.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
	<link rel="shortcut icon" href="../img/favicon.png" type="image/x-icon">
	<script type="text/JavaScript"></script>
</head>

<body>
	<div id="gere_menu">
	<div class="btn_expandir">
		<i class="bi bi-caret-right-square"></i>
		</div>
		<div>
			<?php
			include "conexao.php";
			if ($sql === false) {
				die("Erro na conexão com o banco de dados: " . mysqli_connect_error());
			}
			$moto_cpf = $_SESSION['moto_cpf'];
			$result = mysqli_query($sql, "SELECT foto FROM motorista WHERE moto_cpf = '$moto_cpf'");
			if ($result) {
				$rowft = mysqli_fetch_assoc($result);
				$imagem = $rowft['foto'];
				if ($imagem) {
					echo '<img src="data:image/jpeg;base64,' . base64_encode($imagem) . '" style="border-radius: 50%; width: 11vw; height: 11vw; object-fit: cover;">';
				} else {
					echo '<img src="../img/fundo_foto_padrao.png" alt="Foto do Perfil" style="width:11vw;height:11vw;">';
				}
			} else {
				echo "Erro ao obter a foto do banco de dados: " . mysqli_error($sql);
			}
			?>
			<p>Bem Vindo<br>
				<?php echo $linked_moto['nome'] . PHP_EOL ?>
			</p>
		</div>
		<div>
			<br />
			<div class="gere_links"><a href="gere_inicio.php">Home</a></div><br />
			<div class="gere_links"><a href="gere_revisao.php">Revisão</a></div><br />
			<div class="gere_links"><a href="gere_criancas_escolas.php">Crianças</a></div><br />
			<div id="gere_local"><a href="gere_lucro.php">Lucro</a></div><br />
			<div class="gere_links"><a href="gere_perfil.php">Perfil</a></div><br />
			<div class="gere_links"><a href="gere_chamada_escolas.php">Chamada</a></div><br />
			<div class="gere_links"><a href="">Chat</a></div><br />
		</div>
		<footer id="gere_sair"><a href="sair.php">Sair</a></footer>
	</div>

	<div id="gere_conteudo">

		<div id="pagamentos" style="text-align: center;">

			<div id="filtro"><a></a><input type="text" id="barra_busca" style="font-size:20px;" placeholder="Pesquise aqui">
			<button id="btn_filtro" onclick="searchData()"></button>
			</div>
			<?php

			if(!empty($_GET['search'])){
				$data = $_GET['search'];
				$select_search = $sql->query("SELECT crianca.cria_id as id_crianca, crianca.nome as nome_crianca, responsavel.nome as nome_responsavel, crianca.escola, crianca.valor FROM crianca INNER JOIN responsavel ON crianca.res_cpf = responsavel.res_cpf WHERE crianca.trans_id = $trans_id AND crianca.nome LIKE '%$data%'");
				if ($select_search !== false && $select_search->num_rows > 0) {
					while ($row = $select_search->fetch_assoc()) {
						echo '<form method="post" id="forms_mensalidade" action="salvar_valor_crianca.php?cria_id='. urlencode($row['id_crianca']).'">
							 <div>
								 <label>Criança: </label><input type="text" class="pagamento_crianca" style="font-size:20px;" value="' . $row['nome_crianca'] . '" readonly><br>
								 <label>Pagante: </label><input type="text" class="pagemento_pagante" style="font-size:20px;" value="' . $row['nome_responsavel'] . '" readonly><br>
								 <label>Escola: </label><input type="text" class="pagamento_escola" style="font-size:20px;" value="' . $row['escola'] . '" readonly><br>
								 <label>Valor: </label><input type="text" class="pagamento_valor" name="valor" style="font-size:20px;" value="' . $row['valor'] . '"><br>
							 </div>
							 <div>
								 <a href="../php/gere_pagamento_historico.php?cria_id='. urlencode($row['id_crianca']).'">
								 <img src="../img/icone_arquivos_v2.png" id="cad_pagamento" style="background-repeat: no-repeat; background-size:4.5vw 4.5vw;align-items:center;text-align:center;width:4vw; height: 4vw;" value=" "></a>
								 <a href="../php/salvar_valor_crianca.php?cria_id='. urlencode($row['id_crianca']).'">
								 <input id="cad_salvar" type="submit" style="background-image: url(../img/icone_salvar.png);background-repeat: no-repeat; background-size:3.3vw 3.3vw;align-items:center;text-align:center;width:3.3vw; height:3.3vw; box-shadow: 0 0 0 0; border: 0 none; outline: 0;" value=" ">
								 </input>
							   </a>
							   </div>
						 </form>';;
							
					}
				}
			}else if(empty($_GET['search'])){
				$result3 = $sql->query("SELECT crianca.cria_id as id_crianca, crianca.nome as nome_crianca, responsavel.nome as nome_responsavel, crianca.escola, crianca.valor FROM crianca INNER JOIN responsavel ON crianca.res_cpf = responsavel.res_cpf WHERE crianca.trans_id = $trans_id");
			if ($result3 !== false && $result3->num_rows > 0) {
				while ($row = $result3->fetch_assoc()) {
					echo '<form method="post" id="forms_mensalidade" action="salvar_valor_crianca.php?cria_id='. urlencode($row['id_crianca']).'">
							 <div>
								 <label>Criança: </label><input type="text" class="pagamento_crianca" style="font-size:20px;" value="' . $row['nome_crianca'] . '" readonly><br>
								 <label>Pagante: </label><input type="text" class="pagemento_pagante" style="font-size:20px;" value="' . $row['nome_responsavel'] . '" readonly><br>
								 <label>Escola: </label><input type="text" class="pagamento_escola" style="font-size:20px;" value="' . $row['escola'] . '" readonly><br>
								 <label>Valor: </label><input type="text" class="pagamento_valor" name="valor" style="font-size:20px;" value="' . $row['valor'] . '"><br>
							 </div>
							 <div>
								 <a href="../php/gere_pagamento_historico.php?cria_id='. urlencode($row['id_crianca']).'">
								 <img src="../img/icone_arquivos_v2.png" id="cad_pagamento" style="background-repeat: no-repeat; background-size:4.5vw 4.5vw;align-items:center;text-align:center;width:4vw; height: 4vw;" value=" "></a>
								 <a href="../php/salvar_valor_crianca.php?cria_id='. urlencode($row['id_crianca']).'">
								 <input id="cad_salvar" type="submit" style="background-image: url(../img/icone_salvar.png);background-repeat: no-repeat; background-size:3.3vw 3.3vw;align-items:center;text-align:center;width:3.3vw; height:3.3vw; box-shadow: 0 0 0 0; border: 0 none; outline: 0;" value=" ">
								 </input>
							   </a>
							   </div>
						 </form>';
				}
			}else{
				
				echo '<div id="forms_mensalidade">
								 <div>
									 <label>Criança: </label><input type="text" class="pagamento_crianca" style="font-size:20px;" value="Sem Dados" readonly><br>
									 <label>Pagante: </label><input type="text" class="pagemento_pagante" style="font-size:20px;" value="Sem Dados" readonly><br>
									 <label>Escola: </label><input type="text" class="pagamento_escola" style="font-size:20px;" value="Sem Dados"readonly><br>
									 <label>Valor: </label><input type="text" class="pagamento_valor" style="font-size:20px;" value="Sem Dados"readonly><br>
								 </div>
								 <div>
								 <a href="../php/gere_pagamento_historico.php?cria_id=1">
								 <img src="../img/icone_arquivos_v2.png" id="cad_pagamento" style="background-repeat: no-repeat; background-size:4.5vw 4.5vw;align-items:center;text-align:center;width:4vw; height: 4vw;" value=" "></a>
							   </div>
							 </div>';
			}
		}
			?>

		</div>

	</div>
	
</body>
<script type="text/JavaScript">
var search = document.getElementById('barra_busca');

search.addEventListener("keydown",function(event) {
if (event.key === "Enter"){
	searchData();
}
});

function searchData(){
	window.location = "gere_mensalidade.php?search="+ search.value;
}
</script>
</html>