<?php
// Copie este arquivo para config.php e ajuste a senha do seu MySQL.
// O config.php fica fora do git (veja o .gitignore).

$servername = "localhost";
$username = "root";
$password = "SUA_SENHA_AQUI";
$dbname = "diario_rpg";

$conn = new mysqli($servername, $username, $password, $dbname);

if($conn->connect_error){
    die("Falha na conexão: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

?>
