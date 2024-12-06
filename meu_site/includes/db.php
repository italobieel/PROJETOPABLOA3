<?php
// includes/db.php
$host = 'localhost';
$dbname = 'meusite';  // Substitua pelo nome do seu banco de dados
$username = 'root';              // Substitua pelo seu usuário do MySQL (geralmente "root" no XAMPP)
$password = '';                  // Substitua pela sua senha (geralmente em branco no XAMPP)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro ao conectar ao banco de dados: " . $e->getMessage();
    die();
}
