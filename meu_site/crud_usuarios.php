<?php
// Exemplo de código para listar usuários
$stmt = $pdo->query("SELECT * FROM usuarios");
$usuarios = $stmt->fetchAll();
foreach ($usuarios as $usuario) {
    echo $usuario['nome'] . " - " . $usuario['email'];
}
?>