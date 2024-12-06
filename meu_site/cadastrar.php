<?php
require_once 'includes/db.php'; // Certifique-se de que está conectando ao banco com PDO

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitização e validação dos campos
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $senha = $_POST['senha'];

    if ($nome && $email && $senha) {
        try {
            // Criptografando a senha
            $senha = password_hash($senha, PASSWORD_DEFAULT);

            // Preparando e executando a query para inserir o usuário no banco
            $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)");
            $stmt->execute([
                ':nome' => $nome,
                ':email' => $email,
                ':senha' => $senha,
            ]);

            echo "Usuário cadastrado com sucesso!";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                echo "E-mail já cadastrado.";
            } else {
                echo "Erro ao cadastrar: " . $e->getMessage();
            }
        }
    } else {
        echo "Preencha todos os campos corretamente.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Cadastro de Novo Usuário</h2>
    <form method="POST" action="cadastrar.php">
        <div>
            <label for="nome">Nome:</label>
            <input type="text" name="nome" required>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" name="email" required>
        </div>
        <div>
            <label for="senha">Senha:</label>
            <input type="password" name="senha" required>
        </div>
        <button type="submit">Cadastrar</button>
    </form>
</body>
</html>
