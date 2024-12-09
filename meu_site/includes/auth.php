<?php
session_start(); // Sempre no início do script

require_once 'db.php'; // Inclui a conexão ao banco de dados

/**
 * Cadastra um novo usuário no sistema.
 *
 * @param string $nome Nome do usuário
 * @param string $email Email do usuário
 * @param string $senha Senha do usuário
 * @return string Mensagem de sucesso ou erro
 */
function cadastrarUsuario($nome, $email, $senha) {
    global $pdo;

    try {
        // Verifica se o email já está cadastrado
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->fetchColumn() > 0) {
            return "Este email já está cadastrado!";
        }

        // Criptografa a senha
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        // Insere o novo usuário no banco de dados
        $stmt = $pdo->prepare(
            "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)"
        );
        $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':senha', $senhaHash, PDO::PARAM_STR);

        if ($stmt->execute()) {
            return "Cadastro realizado com sucesso!";
        } else {
            return "Erro ao cadastrar usuário.";
        }
    } catch (PDOException $e) {
        error_log("Erro ao cadastrar usuário: " . $e->getMessage());
        return "Erro no banco de dados. Tente novamente mais tarde.";
    }
}

/**
 * Realiza o login do usuário.
 *
 * @param string $nome Nome do usuário
 * @param string $password Senha do usuário
 * @return bool True se o login for bem-sucedido, False caso contrário
 */
function login($nome, $password) {
    global $pdo;

    try {
        // Busca o usuário pelo nome
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE nome = :nome");
        $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica se o usuário existe e se a senha é válida
        if ($user && password_verify($password, $user['senha'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'nome' => $user['nome'],
                'email' => $user['email'],
                'role' => $user['role'] ?? 'user' // Define o papel padrão como 'user'
            ];
            return true;
        }
        return false;
    } catch (PDOException $e) {
        error_log("Erro ao realizar login: " . $e->getMessage());
        return false;
    }
}

/**
 * Verifica se o usuário está logado.
 *
 * @return bool True se estiver logado, False caso contrário
 */
function is_logged_in() {
    return !empty($_SESSION['user']);
}

/**
 * Verifica se o usuário é administrador.
 *
 * @return bool True se for admin, False caso contrário
 */
function is_admin() {
    return is_logged_in() && ($_SESSION['user']['role'] === 'admin');
}

/**
 * Faz logout do usuário.
 */
function logout() {
    session_unset(); // Remove todas as variáveis de sessão
    session_destroy(); // Destrói a sessão
    session_start(); // Reinicia a sessão para evitar erros futuros
}
