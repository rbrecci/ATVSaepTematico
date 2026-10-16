<?php
include 'inc/base.php';

// Armazena o nome do usuário logado em uma variável para exibir na página
$nome = $_SESSION['usuario']['nome'];

// Totais de cada tabela, mostrados nos cartões
$total = $conn->query(
    "SELECT (SELECT COUNT(*) FROM jogador) AS jogadores,
            (SELECT COUNT(*) FROM personagem) AS personagens,
            (SELECT COUNT(*) FROM registro_sessao) AS registros"
)->fetch_assoc();

$titulo = "Início";
$ativa = "inicio";
include 'inc/topo.php';
?>

<h2 class="secao">Bem-vindo, <?= e($nome) ?></h2>
<p class="texto-centro capitular">O grimório está aberto. Cada jogador guarda seus personagens, e cada personagem escreve o que viveu nas sessões. Escolha uma página para começar.</p>

<div class="cartoes">
    <a href="jogadores.php" class="cartao">
        <svg viewBox="0 0 100 100" aria-hidden="true"><use href="#g-olho"/></svg>
        <strong><?= (int) $total['jogadores'] ?></strong>
        <span>Jogadores</span>
    </a>
    <a href="personagens.php" class="cartao">
        <svg viewBox="0 0 100 100" aria-hidden="true"><use href="#g-estrela"/></svg>
        <strong><?= (int) $total['personagens'] ?></strong>
        <span>Personagens</span>
    </a>
    <a href="diario.php" class="cartao">
        <svg viewBox="0 0 100 100" aria-hidden="true"><use href="#g-lua"/></svg>
        <strong><?= (int) $total['registros'] ?></strong>
        <span>Registros de sessão</span>
    </a>
</div>

<p class="texto-centro"><a href="logout.php" class="lacre">Sair do grimório</a></p>

<?php include 'inc/rodape.php'; ?>
