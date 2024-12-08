<?php
session_start();  // Chama session_start() logo no início
require_once 'db.php';  // Inclui db.php uma única vez

function cadastrarUsuario($nome, $email, $senha) {
    global $pdo;

    // Verificar se o email já está cadastrado
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        return "Este email já está cadastrado!";
    }

    // Criptografar a senha
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    // Inserir o novo usuário no banco
    $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)");
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senhaHash);

    if ($stmt->execute()) {
        return "Cadastro realizado com sucesso!";
    } else {
        return "Erro ao cadastrar usuário.";
    }
}

// Login
function login($nome, $password) {
    global $pdo;

    // Busca o usuário pelo nome
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE nome = :nome");
    $stmt->bindParam(':nome', $nome);
    $stmt->execute();
    $user = $stmt->fetch();

    // Verifica a senha e retorna sucesso se válido
    if ($user && password_verify($password, $user['senha'])) {
        $_SESSION['user'] = $user;
        return true;
    }
    return false;
}

// Verifica se usuário está logado
function is_logged_in() {
    return isset($_SESSION['user']);
}

// Verifica se é admin
function is_admin() {
    return is_logged_in() && $_SESSION['user']['role'] === 'admin';
}
