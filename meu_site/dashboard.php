<?php
session_start();
if (!isset($_SESSION['usuario_id']) || !$_SESSION['is_admin']) {
    header('Location: perfil.php');
    exit;
}

require 'includes/db.php';

echo "Bem-vindo ao dashboard!";

// Exibir lista de usuários para o administrador
$stmt = $pdo->prepare("SELECT * FROM usuarios");
$stmt->execute();
$usuarios = $stmt->fetchAll();

echo "<h3>Usuários Cadastrados:</h3>";
foreach ($usuarios as $usuario) {
    echo "<p>{$usuario['nome']} - {$usuario['email']}</p>";
    echo "<a href='editar_usuario.php?id={$usuario['id']}'>Editar</a> | ";
    echo "<a href='excluir_usuario.php?id={$usuario['id']}'>Excluir</a><br>";
}

?>
<a href="alterar_senha.php">Alterar Senha</a>
<a href="logout.php">Sair</a>
