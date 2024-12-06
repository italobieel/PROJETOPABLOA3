<?php
session_start();
require 'includes/db.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Exibe as informações do usuário
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$usuario_id]);
$usuario = $stmt->fetch();

$mensagem_sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['editar'])) {
        // Lógica de edição do perfil
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        // Atualiza o perfil
        $stmt = $pdo->prepare("UPDATE usuarios SET nome = ?, email = ?, senha = ? WHERE id = ?");
        $stmt->execute([ $nome, $email, password_hash($senha, PASSWORD_DEFAULT), $usuario_id ]);
        
        // Definindo a mensagem de sucesso
        $mensagem_sucesso = "Informações atualizadas com sucesso!";
    } elseif (isset($_POST['excluir'])) {
        // Lógica de exclusão da conta
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->execute([$usuario_id]);
        session_destroy(); // Destrói a sessão após exclusão
        header('Location: login.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Perfil do Usuário</title>
</head>
<body>
    <h2>Perfil de <?php echo htmlspecialchars($usuario['nome']); ?></h2>
    
    <?php if ($mensagem_sucesso): ?>
        <p style="color: green;"><?php echo $mensagem_sucesso; ?></p>
    <?php endif; ?>

    <form method="POST" action="perfil.php">
        <div>
            <label for="nome">Nome:</label>
            <input type="text" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
        </div>
        <div>
            <label for="senha">Senha:</label>
            <input type="password" name="senha" placeholder="Nova senha" required>
        </div>
        <button type="submit" name="editar">Salvar Alterações</button>
    </form>

    <form method="POST" action="perfil.php">
        <button type="submit" name="excluir">Excluir Conta</button>
    </form>
</body>
</html>
