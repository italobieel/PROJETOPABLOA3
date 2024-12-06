<?php
require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $novaSenha = $_POST['nova_senha'];

    // Gera o hash da nova senha
    $novaSenhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);

    // Atualiza a senha no banco
    $stmt = $pdo->prepare("UPDATE usuarios SET senha = :nova_senha WHERE nome = :nome");
    $stmt->bindParam(':nova_senha', $novaSenhaHash);
    $stmt->bindParam(':nome', $nome);

    if ($stmt->execute() && $stmt->rowCount() > 0) {
        echo "Senha atualizada com sucesso!";
    } else {
        echo "Erro: Usuário não encontrado ou não foi possível atualizar a senha.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Resetar Senha</title>
</head>
<body>
    <h2>Resetar Senha</h2>
    <form method="POST" action="">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" required>
        <label for="nova_senha">Nova Senha:</label>
        <input type="password" name="nova_senha" required>
        <button type="submit">Resetar</button>
    </form>
</body>
</html>
