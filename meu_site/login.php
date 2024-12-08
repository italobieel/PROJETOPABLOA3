<?php
session_start();
require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'login') {
        $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
        $senha = $_POST['senha'] ?? '';

        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE nome = ?");
        $stmt->execute([$nome]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['is_admin'] = $usuario['is_admin'];

            // Redireciona baseado no tipo de usuário
            header('Location: ' . ($usuario['is_admin'] ? 'dashboard.php' : 'perfil.php'));
            exit;
        } else {
            $erro = "Nome ou senha inválidos.";
        }
    } elseif ($action === 'register') {
        $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $senha = $_POST['senha'] ?? '';

        if ($nome && $email && $senha) {
            try {
                $senha = password_hash($senha, PASSWORD_DEFAULT);

                $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)");
                $stmt->execute([
                    ':nome' => $nome,
                    ':email' => $email,
                    ':senha' => $senha,
                ]);

                $mensagem = "Usuário cadastrado com sucesso! Agora você pode fazer login.";
            } catch (PDOException $e) {
                $erro = ($e->getCode() === '23000') ? "E-mail já cadastrado." : "Erro ao cadastrar: " . $e->getMessage();
            }
        } else {
            $erro = "Preencha todos os campos corretamente.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login e Cadastro</title>
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

        h1 {
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
        <h1>Login</h1>
        <form method="POST" action="login.php">
            <input type="text" name="nome" placeholder="Nome" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit" name="action" value="login">Entrar</button>
        </form>

        <h1>Cadastro</h1>
        <form method="POST" action="login.php">
            <input type="text" name="nome" placeholder="Nome" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit" name="action" value="register">Cadastrar</button>
        </form>

        <?php if (isset($erro)): ?>
            <p class="error"><?= htmlspecialchars($erro); ?></p>
        <?php endif; ?>

        <?php if (isset($mensagem)): ?>
            <p class="success"><?= htmlspecialchars($mensagem); ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
