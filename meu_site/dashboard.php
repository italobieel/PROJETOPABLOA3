<?php
session_start();
if (!isset($_SESSION['usuario_id']) || !$_SESSION['is_admin']) {
    header('Location: perfil.php');
    exit;
}

require 'includes/db.php';

// Variáveis de mensagens
$mensagem_sucesso = '';
$mensagem_erro = '';

// Excluir usuário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir_usuario_id'])) {
    $usuario_id = $_POST['excluir_usuario_id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->execute([$usuario_id]);
        $mensagem_sucesso = "Usuário excluído com sucesso!";
    } catch (PDOException $e) {
        $mensagem_erro = "Erro ao excluir usuário: " . $e->getMessage();
    }
}

// Editar informações do usuário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_usuario_id'])) {
    $usuario_id = $_POST['editar_usuario_id'];
    $novo_nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $novo_email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $nova_senha = $_POST['senha'] ?? '';

    if ($novo_nome && $novo_email) {
        try {
            if (!empty($nova_senha)) {
                $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE usuarios SET nome = ?, email = ?, senha = ? WHERE id = ?");
                $stmt->execute([$novo_nome, $novo_email, $senha_hash, $usuario_id]);
            } else {
                $stmt = $pdo->prepare("UPDATE usuarios SET nome = ?, email = ? WHERE id = ?");
                $stmt->execute([$novo_nome, $novo_email, $usuario_id]);
            }
            $mensagem_sucesso = "Usuário atualizado com sucesso!";
        } catch (PDOException $e) {
            $mensagem_erro = "Erro ao atualizar usuário: " . $e->getMessage();
        }
    } else {
        $mensagem_erro = "Preencha todos os campos corretamente.";
    }
}

// Obtém a lista de usuários atualizada
$stmt = $pdo->prepare("SELECT * FROM usuarios");
$stmt->execute();
$usuarios = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administrativo</title>
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
            width: 400px;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        h2, h3 {
            margin-bottom: 20px;
        }

        .user {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            text-align: left;
        }

        .user form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .user input {
            padding: 8px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
        }

        .user button {
            background-color: dodgerblue;
            border: none;
            padding: 10px;
            border-radius: 5px;
            color: white;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .user button:hover {
            background-color: deepskyblue;
        }

        .actions {
            margin-top: 20px;
        }

        .actions a {
            display: inline-block;
            background-color: dodgerblue;
            color: white;
            padding: 10px 20px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 15px;
            margin: 5px 10px;
            transition: background-color 0.3s ease;
        }

        .actions a:hover {
            background-color: deepskyblue;
        }

        .message {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
            font-size: 14px;
        }

        .success {
            background-color: #4caf50;
            color: white;
        }

        .error {
            background-color: #f44336;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Dashboard Administrativo</h2>
        <?php if ($mensagem_sucesso): ?>
            <div class="message success"><?= htmlspecialchars($mensagem_sucesso); ?></div>
        <?php endif; ?>
        <?php if ($mensagem_erro): ?>
            <div class="message error"><?= htmlspecialchars($mensagem_erro); ?></div>
        <?php endif; ?>
        <h3>Usuários Cadastrados:</h3>
        <?php if ($usuarios): ?>
            <?php foreach ($usuarios as $usuario): ?>
                <div class="user">
                    <form method="POST">
                        <input type="hidden" name="editar_usuario_id" value="<?= $usuario['id']; ?>">
                        <p><strong>Nome:</strong></p>
                        <input type="text" name="nome" value="<?= htmlspecialchars($usuario['nome']); ?>" required>
                        <p><strong>Email:</strong></p>
                        <input type="email" name="email" value="<?= htmlspecialchars($usuario['email']); ?>" required>
                        <p><strong>Senha:</strong></p>
                        <input type="password" name="senha" placeholder="Nova senha (opcional)">
                        <button type="submit">Salvar Alterações</button>
                    </form>
                    <form method="POST">
                        <input type="hidden" name="excluir_usuario_id" value="<?= $usuario['id']; ?>">
                        <button type="submit" style="background-color: #f44336;">Excluir Usuário</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nenhum usuário cadastrado.</p>
        <?php endif; ?>
        <div class="actions">
            <a href="alterar_senha.php">Alterar Senha</a>
            <a href="logout.php">Sair</a>
        </div>
    </div>
</body>
</html>
