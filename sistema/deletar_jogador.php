<?php
include 'inc/base.php';

// Só apaga quando o pedido vem do botão Excluir (POST); acessar a URL direto não apaga nada
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);

    // Como a chave estrangeira usa ON DELETE CASCADE, o banco apaga junto
    // os personagens do jogador e os registros de sessão deles
    $stmt = $conn->prepare("DELETE FROM jogador WHERE id=?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
}

header("Location: jogadores.php");
exit();
