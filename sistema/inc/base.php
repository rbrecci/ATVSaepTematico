<?php
// Cabeçalho comum das páginas internas: sessão, expiração, banco e funções de apoio.

// SameSite=Lax: o navegador não manda a sessão em POST vindo de outro site (CSRF).
// HttpOnly: o JavaScript da página não consegue ler o cookie da sessão.
session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);

// Inicia a sessão para verificar se o usuário está autenticado
session_start();

// Se não houver usuário logado, redireciona para a página de login
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

// Se ficou mais de 30 minutos sem usar o sistema, encerra a sessão e avisa no login
if (time() - $_SESSION['ultimo_acesso'] > 30 * 60) {
    session_destroy();
    header('Location: login.php?expirou=1');
    exit();
}

// Ainda está no prazo: renova o horário do último acesso
$_SESSION['ultimo_acesso'] = time();

// Conexão com o banco ($conn)
include __DIR__ . '/../config.php';

// Escapa texto para exibir na tela (evita XSS)
function e($texto) {
    return htmlspecialchars((string) $texto, ENT_QUOTES, 'UTF-8');
}

// Confere se a data veio no formato AAAA-MM-DD e se existe no calendário
function data_valida($data) {
    $d = DateTime::createFromFormat('Y-m-d', $data);
    return $d && $d->format('Y-m-d') === $data;
}

// Mostra a data do banco (AAAA-MM-DD) como DD/MM/AAAA
function data_br($data) {
    return date('d/m/Y', strtotime($data));
}
