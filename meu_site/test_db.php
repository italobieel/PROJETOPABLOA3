<?php
require 'includes/db.php';

try {
    echo "Conexão com o banco bem-sucedida!";
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
