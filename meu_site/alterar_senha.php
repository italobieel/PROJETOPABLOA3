<?php
session_start();
require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novaSenha = password_hash($_POST['nova_senha'], PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
    $stmt->execute([$novaSenha, $_SESSION['usuario_id']]);

    echo "Senha alterada com sucesso!";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Alterar Senha</title>
</head>
<body>
    <form method="POST" action="alterar_senha.php">
        <input type="password" name="nova_senha" placeholder="Nova senha" required>
        <button type="submit">Alterar</button>
    </form>
</body>
</html>
