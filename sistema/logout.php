<?php
// Inicia a sessão para poder destruí-la
session_start();
// Destrói todas as informações da sessão atual, efetivando o logout do usuário
session_destroy();
// Redireciona o usuário para a página de login após encerrar a sessão
header('Location: login.php');
// Interrompe a execução do script para garantir que o redirecionamento ocorra imediatamente
exit();
