<?php
include 'inc/base.php';

// Só apaga quando o pedido vem do botão Excluir (POST); acessar a URL direto não apaga nada
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);

    $stmt = $conn->prepare("DELETE FROM registro_sessao WHERE id=?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
}

header("Location: diario.php");
exit();
