<?php
require_once 'includes/auth.php';
if (!is_admin()) {
    header("Location: dashboard.php");
    exit;
}
require_once 'includes/db.php';

// Lógica de editar ou excluir usuários aqui.
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <link rel="stylesheet" href="css/style.css">
    <title>Administração</title>
</head>
<body>
    <h2>Administração de Usuários</h2>
    <!-- Exibição da lista de usuários com opções de edição e exclusão -->
</body>
</html>
