<?php
include 'inc/base.php';

$erro = "";

// Verifica se o formulário de cadastro foi enviado via POST e o botão 'add' foi clicado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $personagem_id = (int) ($_POST['personagem_id'] ?? 0);
    $data_sessao = $_POST['data_sessao'] ?? '';
    $titulo_registro = trim($_POST['titulo'] ?? '');
    $xp = (int) ($_POST['xp_ganho'] ?? 0);

    if (!$personagem_id || $titulo_registro === '') {
        $erro = "Personagem e título são obrigatórios.";
    } elseif (!data_valida($data_sessao)) {
        $erro = "Informe uma data válida para a sessão.";
    } elseif ($xp < 0) {
        $erro = "O XP ganho não pode ser negativo.";
    } else {
        // O campo personagem_id liga o registro ao seu personagem (um personagem tem muitos registros)
        $stmt = $conn->prepare(
            "INSERT INTO registro_sessao (personagem_id, data_sessao, titulo, resumo, xp_ganho)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("isssi", $personagem_id, $data_sessao, $titulo_registro, $_POST['resumo'], $xp);
        $stmt->execute();
        header("Location: diario.php");
        exit();
    }
}

// Une registro, personagem e jogador (JOIN) e ordena pela data da sessão, da mais recente para a mais antiga
$registros = $conn->query(
    "SELECT r.*, p.nome AS personagem_nome, j.nome AS jogador_nome
     FROM registro_sessao r
     JOIN personagem p ON r.personagem_id = p.id
     JOIN jogador j ON p.jogador_id = j.id
     ORDER BY r.data_sessao DESC, r.id DESC"
);

// Personagens (com o jogador) para preencher o select do formulário
$personagens = $conn->query(
    "SELECT p.id, p.nome, j.nome AS jogador_nome
     FROM personagem p
     JOIN jogador j ON p.jogador_id = j.id
     ORDER BY p.nome"
);

$titulo = "Diário de Sessões";
$ativa = "diario";
include 'inc/topo.php';
?>

<h2 class="secao">Diário de Sessões</h2>

<div class="tabela-rolagem">
<table>
    <tr><th>Data</th><th>Título</th><th>Resumo</th><th>XP</th><th>Personagem</th><th>Ações</th></tr>
    <?php while ($row = $registros->fetch_assoc()): ?>
      <tr>
        <td class="num"><?= data_br($row['data_sessao']) ?></td>
        <td><?= e($row['titulo']) ?></td>
        <td class="resumo"><?= e($row['resumo']) ?></td>
        <td class="num"><?= (int) $row['xp_ganho'] ?></td>
        <td><?= e($row['personagem_nome']) ?><small><?= e($row['jogador_nome']) ?></small></td>
        <td class="acoes">
            <a href="editar_registro.php?id=<?= (int) $row['id'] ?>" class="link-tinta">Editar</a>
            <form method="post" action="deletar_registro.php" onsubmit="return confirm('Excluir este registro de sessão?')">
                <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
                <button class="lacre pequeno">Excluir</button>
            </form>
        </td>
      </tr>
    <?php endwhile; ?>
    <?php if ($registros->num_rows === 0): ?>
      <tr><td colspan="6" class="vazio">Nenhum registro de sessão ainda.</td></tr>
    <?php endif; ?>
</table>
</div>

<div class="divisor" aria-hidden="true"><svg viewBox="0 0 100 100"><use href="#g-lua"/></svg></div>

<h3 class="subsecao">Novo registro</h3>

<?php if ($erro): ?><div class="aviso erro" role="alert"><?= e($erro) ?></div><?php endif; ?>

<form method="post" class="formulario">
    <label>Personagem
        <select name="personagem_id" required>
            <option value="">Selecione o personagem</option>
            <?php while ($p = $personagens->fetch_assoc()): ?>
                <option value="<?= (int) $p['id'] ?>" <?= ($_POST['personagem_id'] ?? '') == $p['id'] ? 'selected' : '' ?>><?= e($p['nome']) ?> (<?= e($p['jogador_nome']) ?>)</option>
            <?php endwhile; ?>
        </select>
    </label>
    <label>Data da sessão
        <input type="date" name="data_sessao" required value="<?= e($_POST['data_sessao'] ?? date('Y-m-d')) ?>">
    </label>
    <label>Título
        <input name="titulo" required maxlength="120" value="<?= e($_POST['titulo'] ?? '') ?>">
    </label>
    <label>XP ganho
        <input type="number" name="xp_ganho" min="0" required value="<?= e($_POST['xp_ganho'] ?? '0') ?>">
    </label>
    <label class="larga">Resumo
        <textarea name="resumo" rows="4"><?= e($_POST['resumo'] ?? '') ?></textarea>
    </label>
    <button name="add" class="lacre">Registrar</button>
</form>

<?php include 'inc/rodape.php'; ?>
