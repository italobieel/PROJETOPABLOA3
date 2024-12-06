<?php
require_once 'includes/auth.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (login($_POST['nome'], $_POST['senha'])) {
        header("Location: dashboard.php");
        exit;
    } else {
        $erro = "Usuário ou senha inválidos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <link rel="stylesheet" href="css/style.css">
    <title>Login</title>
</head>
<body>
    <h2>Index</h2>
    <form method="POST">
        <input type="text" name="nome" placeholder="Usuário" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit">Entrar</button>
    </form>
    <?php if (isset($erro)) echo "<p>$erro</p>"; ?>
</body>
</html>
