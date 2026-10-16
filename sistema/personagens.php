<?php
include 'inc/base.php';

$erro = "";

// Verifica se o formulário de cadastro foi enviado via POST e o botão 'add' foi clicado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $jogador_id = (int) ($_POST['jogador_id'] ?? 0);
    $nome = trim($_POST['nome'] ?? '');
    $raca = trim($_POST['raca'] ?? '');
    $classe = trim($_POST['classe'] ?? '');
    $nivel = (int) ($_POST['nivel'] ?? 0);

    if (!$jogador_id || $nome === '' || $raca === '' || $classe === '') {
        $erro = "Jogador, nome, raça e classe são obrigatórios.";
    } elseif ($nivel < 1 || $nivel > 20) {
        $erro = "O nível deve ficar entre 1 e 20.";
    } else {
        // O campo jogador_id liga o personagem ao seu jogador (um jogador tem muitos personagens)
        $stmt = $conn->prepare(
            "INSERT INTO personagem (jogador_id, nome, raca, classe, nivel) VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("isssi", $jogador_id, $nome, $raca, $classe, $nivel);
        $stmt->execute();
        header("Location: personagens.php");
        exit();
    }
}

// Une personagem e jogador (JOIN) para mostrar o nome do jogador de cada personagem
$personagens = $conn->query(
    "SELECT p.*, j.nome AS jogador_nome
     FROM personagem p
     JOIN jogador j ON p.jogador_id = j.id
     ORDER BY p.nome"
);

// Jogadores para preencher o select do formulário
$jogadores = $conn->query("SELECT id, nome FROM jogador ORDER BY nome");

$titulo = "Personagens";
$ativa = "personagens";
include 'inc/topo.php';
?>

<h2 class="secao">Personagens</h2>

<div class="tabela-rolagem">
<table>
    <tr><th>Nome</th><th>Raça</th><th>Classe</th><th>Nível</th><th>Jogador</th><th>Ações</th></tr>
    <?php while ($row = $personagens->fetch_assoc()): ?>
      <tr>
        <td><?= e($row['nome']) ?></td>
        <td><?= e($row['raca']) ?></td>
        <td><?= e($row['classe']) ?></td>
        <td class="num"><?= (int) $row['nivel'] ?></td>
        <td><?= e($row['jogador_nome']) ?></td>
        <td class="acoes">
            <a href="editar_personagem.php?id=<?= (int) $row['id'] ?>" class="link-tinta">Editar</a>
            <form method="post" action="deletar_personagem.php" onsubmit="return confirm('Excluir o personagem apaga também os registros de sessão dele. Confirma?')">
                <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
                <button class="lacre pequeno">Excluir</button>
            </form>
        </td>
      </tr>
    <?php endwhile; ?>
    <?php if ($personagens->num_rows === 0): ?>
      <tr><td colspan="6" class="vazio">Nenhum personagem cadastrado.</td></tr>
    <?php endif; ?>
</table>
</div>

<div class="divisor" aria-hidden="true"><svg viewBox="0 0 100 100"><use href="#g-olho"/></svg></div>

<h3 class="subsecao">Novo personagem</h3>

<?php if ($erro): ?><div class="aviso erro" role="alert"><?= e($erro) ?></div><?php endif; ?>

<form method="post" class="formulario">
    <label>Jogador
        <select name="jogador_id" required>
            <option value="">Selecione o jogador</option>
            <?php while ($j = $jogadores->fetch_assoc()): ?>
                <option value="<?= (int) $j['id'] ?>" <?= ($_POST['jogador_id'] ?? '') == $j['id'] ? 'selected' : '' ?>><?= e($j['nome']) ?></option>
            <?php endwhile; ?>
        </select>
    </label>
    <label>Nome
        <input name="nome" required value="<?= e($_POST['nome'] ?? '') ?>">
    </label>
    <label>Raça
        <input name="raca" required value="<?= e($_POST['raca'] ?? '') ?>">
    </label>
    <label>Classe
        <input name="classe" required value="<?= e($_POST['classe'] ?? '') ?>">
    </label>
    <label>Nível
        <input type="number" name="nivel" min="1" max="20" required value="<?= e($_POST['nivel'] ?? '1') ?>">
    </label>
    <button name="add" class="lacre">Cadastrar</button>
</form>

<?php include 'inc/rodape.php'; ?>
