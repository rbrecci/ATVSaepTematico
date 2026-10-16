<?php
include 'inc/base.php';

$erro = "";

// Verifica se o formulário de cadastro foi enviado via POST e o botão 'add' foi clicado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($nome === '') {
        $erro = "O nome do jogador é obrigatório.";
    } elseif ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "O e-mail informado não é válido.";
    } else {
        // Consulta preparada para inserir o novo jogador, evitando SQL Injection
        $stmt = $conn->prepare("INSERT INTO jogador (nome, telefone, email) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nome, $_POST['telefone'], $email);
        $stmt->execute();
        // Após adicionar, redireciona para a mesma página para atualizar a lista
        header("Location: jogadores.php");
        exit();
    }
}

// Termo de busca enviado via GET; com a busca vazia o LIKE '%%' traz todos os jogadores
$busca = trim($_GET['busca'] ?? '');
$termo = "%$busca%";

// Lista os jogadores filtrados pelo nome, com a quantidade de personagens de cada um
$stmt = $conn->prepare(
    "SELECT j.id, j.nome, j.telefone, j.email,
            (SELECT COUNT(*) FROM personagem p WHERE p.jogador_id = j.id) AS personagens
     FROM jogador j
     WHERE j.nome LIKE ?
     ORDER BY j.nome"
);
$stmt->bind_param("s", $termo);
$stmt->execute();
$jogadores = $stmt->get_result();

$titulo = "Jogadores";
$ativa = "jogadores";
include 'inc/topo.php';
?>

<h2 class="secao">Jogadores</h2>

<form method="get" class="busca">
    <input name="busca" placeholder="Buscar jogador..." value="<?= e($busca) ?>" aria-label="Buscar jogador">
    <button type="submit" class="lacre">Buscar</button>
    <?php if ($busca !== ''): ?><a href="jogadores.php" class="link-tinta">Limpar</a><?php endif; ?>
</form>

<div class="tabela-rolagem">
<table>
    <tr><th>Nome</th><th>Telefone</th><th>E-mail</th><th>Personagens</th><th>Ações</th></tr>
    <?php while ($row = $jogadores->fetch_assoc()): ?>
      <tr>
        <td><?= e($row['nome']) ?></td>
        <td class="num"><?= e($row['telefone']) ?></td>
        <td><?= e($row['email']) ?></td>
        <td class="num"><?= (int) $row['personagens'] ?></td>
        <td class="acoes">
            <a href="editar_jogador.php?id=<?= (int) $row['id'] ?>" class="link-tinta">Editar</a>
            <form method="post" action="deletar_jogador.php" onsubmit="return confirm('Excluir o jogador apaga também os personagens e os registros dele. Confirma?')">
                <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
                <button class="lacre pequeno">Excluir</button>
            </form>
        </td>
      </tr>
    <?php endwhile; ?>
    <?php if ($jogadores->num_rows === 0): ?>
      <tr><td colspan="5" class="vazio">Nenhum jogador encontrado.</td></tr>
    <?php endif; ?>
</table>
</div>

<div class="divisor" aria-hidden="true"><svg viewBox="0 0 100 100"><use href="#g-estrela"/></svg></div>

<h3 class="subsecao">Novo jogador</h3>

<?php if ($erro): ?><div class="aviso erro" role="alert"><?= e($erro) ?></div><?php endif; ?>

<form method="post" class="formulario">
    <label>Nome
        <input name="nome" required value="<?= e($_POST['nome'] ?? '') ?>">
    </label>
    <label>Telefone
        <input type="tel" name="telefone" value="<?= e($_POST['telefone'] ?? '') ?>">
    </label>
    <label>E-mail
        <input type="email" name="email" value="<?= e($_POST['email'] ?? '') ?>">
    </label>
    <button name="add" class="lacre">Cadastrar</button>
</form>

<?php include 'inc/rodape.php'; ?>
