<?php
// Inicia a sessão para manter estados de login
session_start();
// Se o usuário já estiver logado, redireciona para a página principal
if (isset($_SESSION['usuario'])) {
    header('Location: principal.php');
    exit();
}
// Variável para armazenar mensagem de erro
$erro = "";
// Se a sessão expirou, as outras páginas mandam para cá com ?expirou=1
if (isset($_GET['expirou'])) {
    $erro = "Sua sessão expirou. Faça login novamente.";
}
// Verifica se o formulário foi submetido via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Inclui o arquivo config.php para usar $conn e conectar ao banco
    include 'config.php';
    $nome = $_POST['nome'] ?? '';
    $senha = $_POST['senha'] ?? '';
    // Busca só pelo nome: a senha está guardada como hash e é conferida depois
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE nome=?");
    $stmt->bind_param('s', $nome);
    $stmt->execute();
    $usuario = $stmt->get_result()->fetch_assoc();

    // password_verify compara a senha digitada com o hash criado por password_hash
    if ($usuario && password_verify($senha, $usuario['senha'])) {
        // Gera um novo id de sessão no login (evita fixação de sessão)
        session_regenerate_id(true);
        $_SESSION['usuario'] = $usuario;
        // Guarda o horário do login para controlar o tempo de expiração da sessão
        $_SESSION['ultimo_acesso'] = time();
        header("Location: principal.php");
        exit();
    } else {
        $erro = "Usuário ou senha inválidos.";
    }
}

$titulo = "Entrar";
$publica = true;
include 'inc/topo.php';
?>

<h2 class="secao">Abra o grimório</h2>
<p class="texto-centro">Diga seu nome e a palavra de passe para entrar na mesa.</p>

<?php if ($erro): ?>
    <div class="aviso erro" role="alert"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>

<form method="post" class="form-login">
    <label>Usuário
        <input type="text" name="nome" required autofocus autocomplete="username">
    </label>
    <label>Senha
        <input type="password" name="senha" required autocomplete="current-password">
    </label>
    <button type="submit" class="lacre">Entrar</button>
</form>

<?php include 'inc/rodape.php'; ?>
