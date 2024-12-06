<?php
session_start();
require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $senha = $_POST['senha'];

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE nome = ?");
    $stmt->execute([$nome]);
    $usuario = $stmt->fetch();

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['is_admin'] = $usuario['is_admin']; // Definir se é admin

        // Redirecionar com base no tipo de usuário
        if ($usuario['is_admin']) {
            header('Location: dashboard.php');
        } else {
            header('Location: perfil.php');
        }
        exit;
    } else {
        $erro = "Nome ou senha inválidos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form method="POST" action="login.php">
        <input type="text" name="nome" placeholder="Nome" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit">Entrar</button>
    </form>
    <?php if (isset($erro)) echo "<p>$erro</p>"; ?>
</body>
</html>
