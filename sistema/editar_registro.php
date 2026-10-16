<?php
include 'inc/base.php';

// Recebe o ID do registro a ser editado via GET; se não informado, assume 0
$id = (int) ($_GET['id'] ?? 0);

$stmt = $conn->prepare("SELECT * FROM registro_sessao WHERE id=?");
$stmt->bind_param('i', $id);
$stmt->execute();
$registro = $stmt->get_result()->fetch_assoc();

// Se não encontrou o registro, volta para a lista
if (!$registro) {
    header("Location: diario.php");
    exit();
}

$erro = "";

// Processa o formulário quando enviado via POST para atualizar o registro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        $stmt = $conn->prepare(
            "UPDATE registro_sessao
             SET personagem_id=?, data_sessao=?, titulo=?, resumo=?, xp_ganho=?
             WHERE id=?"
        );
        $stmt->bind_param("isssii", $personagem_id, $data_sessao, $titulo_registro, $_POST['resumo'], $xp, $id);
        $stmt->execute();
        header("Location: diario.php");
        exit();
    }
    $registro = array_merge($registro, $_POST);
}

// Personagens (com o jogador) para o select (permite trocar o personagem do registro)
$personagens = $conn->query(
    "SELECT p.id, p.nome, j.nome AS jogador_nome
     FROM personagem p
     JOIN jogador j ON p.jogador_id = j.id
     ORDER BY p.nome"
);

$titulo = "Editar registro";
$ativa = "diario";
include 'inc/topo.php';
?>

<h2 class="secao">Editar registro de sessão</h2>

<?php if ($erro): ?><div class="aviso erro" role="alert"><?= e($erro) ?></div><?php endif; ?>

<form method="post" class="formulario">
    <label>Personagem
        <select name="personagem_id" required>
            <?php while ($p = $personagens->fetch_assoc()): ?>
                <option value="<?= (int) $p['id'] ?>" <?= $p['id'] == $registro['personagem_id'] ? 'selected' : '' ?>><?= e($p['nome']) ?> (<?= e($p['jogador_nome']) ?>)</option>
            <?php endwhile; ?>
        </select>
    </label>
    <label>Data da sessão
        <input type="date" name="data_sessao" required value="<?= e($registro['data_sessao']) ?>">
    </label>
    <label>Título
        <input name="titulo" required maxlength="120" value="<?= e($registro['titulo']) ?>">
    </label>
    <label>XP ganho
        <input type="number" name="xp_ganho" min="0" required value="<?= e($registro['xp_ganho']) ?>">
    </label>
    <label class="larga">Resumo
        <textarea name="resumo" rows="4"><?= e($registro['resumo']) ?></textarea>
    </label>
    <button class="lacre">Salvar</button>
    <a href="diario.php" class="link-tinta">Voltar</a>
</form>

<?php include 'inc/rodape.php'; ?>
