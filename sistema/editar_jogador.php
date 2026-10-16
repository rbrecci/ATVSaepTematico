<?php
include 'inc/base.php';

// Recebe o ID do jogador a ser editado via GET; se não informado, assume 0
$id = (int) ($_GET['id'] ?? 0);

$stmt = $conn->prepare("SELECT * FROM jogador WHERE id=?");
$stmt->bind_param('i', $id);
$stmt->execute();
$jogador = $stmt->get_result()->fetch_assoc();

// Se não encontrou o jogador, volta para a lista
if (!$jogador) {
    header("Location: jogadores.php");
    exit();
}

$erro = "";

// Processa o formulário quando enviado via POST para atualizar o jogador
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($nome === '') {
        $erro = "O nome do jogador é obrigatório.";
    } elseif ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "O e-mail informado não é válido.";
    } else {
        $stmt = $conn->prepare("UPDATE jogador SET nome=?, telefone=?, email=? WHERE id=?");
        $stmt->bind_param("sssi", $nome, $_POST['telefone'], $email, $id);
        $stmt->execute();
        header("Location: jogadores.php");
        exit();
    }
    // Em caso de erro, o formulário volta com o que foi digitado
    $jogador = array_merge($jogador, $_POST);
}

$titulo = "Editar jogador";
$ativa = "jogadores";
include 'inc/topo.php';
?>

<h2 class="secao">Editar jogador</h2>

<?php if ($erro): ?><div class="aviso erro" role="alert"><?= e($erro) ?></div><?php endif; ?>

<form method="post" class="formulario">
    <label>Nome
        <input name="nome" required value="<?= e($jogador['nome']) ?>">
    </label>
    <label>Telefone
        <input type="tel" name="telefone" value="<?= e($jogador['telefone']) ?>">
    </label>
    <label>E-mail
        <input type="email" name="email" value="<?= e($jogador['email']) ?>">
    </label>
    <button class="lacre">Salvar</button>
    <a href="jogadores.php" class="link-tinta">Voltar</a>
</form>

<?php include 'inc/rodape.php'; ?>
