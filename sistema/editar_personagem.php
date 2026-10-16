<?php
include 'inc/base.php';

// Recebe o ID do personagem a ser editado via GET; se não informado, assume 0
$id = (int) ($_GET['id'] ?? 0);

$stmt = $conn->prepare("SELECT * FROM personagem WHERE id=?");
$stmt->bind_param('i', $id);
$stmt->execute();
$personagem = $stmt->get_result()->fetch_assoc();

// Se não encontrou o personagem, volta para a lista
if (!$personagem) {
    header("Location: personagens.php");
    exit();
}

$erro = "";

// Processa o formulário quando enviado via POST para atualizar o personagem
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        $stmt = $conn->prepare(
            "UPDATE personagem SET jogador_id=?, nome=?, raca=?, classe=?, nivel=? WHERE id=?"
        );
        $stmt->bind_param("isssii", $jogador_id, $nome, $raca, $classe, $nivel, $id);
        $stmt->execute();
        header("Location: personagens.php");
        exit();
    }
    $personagem = array_merge($personagem, $_POST);
}

// Jogadores para o select (permite trocar o jogador do personagem)
$jogadores = $conn->query("SELECT id, nome FROM jogador ORDER BY nome");

$titulo = "Editar personagem";
$ativa = "personagens";
include 'inc/topo.php';
?>

<h2 class="secao">Editar personagem</h2>

<?php if ($erro): ?><div class="aviso erro" role="alert"><?= e($erro) ?></div><?php endif; ?>

<form method="post" class="formulario">
    <label>Jogador
        <select name="jogador_id" required>
            <?php while ($j = $jogadores->fetch_assoc()): ?>
                <option value="<?= (int) $j['id'] ?>" <?= $j['id'] == $personagem['jogador_id'] ? 'selected' : '' ?>><?= e($j['nome']) ?></option>
            <?php endwhile; ?>
        </select>
    </label>
    <label>Nome
        <input name="nome" required value="<?= e($personagem['nome']) ?>">
    </label>
    <label>Raça
        <input name="raca" required value="<?= e($personagem['raca']) ?>">
    </label>
    <label>Classe
        <input name="classe" required value="<?= e($personagem['classe']) ?>">
    </label>
    <label>Nível
        <input type="number" name="nivel" min="1" max="20" required value="<?= e($personagem['nivel']) ?>">
    </label>
    <button class="lacre">Salvar</button>
    <a href="personagens.php" class="link-tinta">Voltar</a>
</form>

<?php include 'inc/rodape.php'; ?>
