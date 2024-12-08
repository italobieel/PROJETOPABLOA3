<?php
session_start();
require 'includes/db.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Obtém as informações do usuário
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$usuario_id]);
$usuario = $stmt->fetch();

$mensagem_sucesso = '';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'editar') {
        $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $senha = $_POST['senha'] ?? '';

        if ($nome && $email && $senha) {
            $stmt = $pdo->prepare("UPDATE usuarios SET nome = ?, email = ?, senha = ? WHERE id = ?");
            $stmt->execute([
                $nome,
                $email,
                password_hash($senha, PASSWORD_DEFAULT),
                $usuario_id
            ]);
            $mensagem_sucesso = "Informações atualizadas com sucesso!";
        } else {
            $erro = "Preencha todos os campos corretamente.";
        }
    } elseif ($action === 'excluir') {
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->execute([$usuario_id]);
        session_destroy();
        header('Location: login.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Usuário</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-image: linear-gradient(45deg, cyan, yellow);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: rgba(0, 0, 0, 0.9);
            padding: 40px;
            border-radius: 15px;
            color: #fff;
            text-align: center;
            width: 300px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        h2 {
            margin-bottom: 20px;
        }

        input {
            padding: 10px;
            border: none;
            outline: none;
            font-size: 15px;
            width: 100%;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        button {
            background-color: dodgerblue;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 10px;
            color: white;
            font-size: 15px;
            cursor: pointer;
            margin-bottom: 15px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: deepskyblue;
        }

        .error {
            color: red;
            margin-top: 10px;
        }

        .success {
            color: green;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Bem-vindo, <?= htmlspecialchars($usuario['nome']); ?>!</h2>

        <?php if ($mensagem_sucesso): ?>
            <p class="success"><?= htmlspecialchars($mensagem_sucesso); ?></p>
        <?php endif; ?>

        <?php if ($erro): ?>
            <p class="error"><?= htmlspecialchars($erro); ?></p>
        <?php endif; ?>

        <form method="POST" action="perfil.php">
            <input type="text" name="nome" value="<?= htmlspecialchars($usuario['nome']); ?>" placeholder="Nome" required>
            <input type="email" name="email" value="<?= htmlspecialchars($usuario['email']); ?>" placeholder="Email" required>
            <input type="password" name="senha" placeholder="Nova senha" required>
            <button type="submit" name="action" value="editar">Salvar Alterações</button>
        </form>

        <form method="POST" action="perfil.php">
            <button type="submit" name="action" value="excluir">Excluir Conta</button>
        </form>
    </div>
</body>
</html>
