<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../includes/auth.php';

class AuthTest extends TestCase {
    private $mockPdo;
    private $mockStmt;

    protected function setUp(): void {
        // Mock da conexão PDO
        $this->mockPdo = $this->createMock(PDO::class);
        $this->mockStmt = $this->createMock(PDOStatement::class);

        // Substituir a instância global de PDO
        global $pdo;
        $pdo = $this->mockPdo;
    }

    public function testLoginValido() {
        // Configurar o comportamento esperado para um login válido
        $this->mockStmt->method('execute')->willReturn(true);
        $this->mockStmt->method('fetch')->willReturn([
            'id' => 1,
            'username' => 'admin',
            'senha' => password_hash('admin123', PASSWORD_DEFAULT),  // Usando password_hash em vez de md5
            'role' => 'admin',
        ]);
        $this->mockPdo->method('prepare')->willReturn($this->mockStmt);

        // Testar login válido
        $this->assertTrue(login('admin', 'admin123'));
    }

    public function testLoginInvalido() {
        // Configurar o comportamento esperado para login com senha incorreta
        $this->mockStmt->method('execute')->willReturn(true);
        $this->mockStmt->method('fetch')->willReturn(false);  // Usuário não encontrado
        $this->mockPdo->method('prepare')->willReturn($this->mockStmt);

        // Testar login inválido
        $this->assertFalse(login('admin', 'senha_errada'));
    }

    public function testLoginUsuarioNaoExistente() {
        // Configurar o comportamento esperado para login com usuário não existente
        $this->mockStmt->method('execute')->willReturn(true);
        $this->mockStmt->method('fetch')->willReturn(false);  // Usuário não encontrado
        $this->mockPdo->method('prepare')->willReturn($this->mockStmt);

        // Testar login com usuário não existente
        $this->assertFalse(login('inexistente', 'senha123'));
    }

    public function testCadastroUsuario() {
        // Testando o cadastro de um novo usuário
        $nome = "Teste";
        $email = "teste@teste.com";
        $senha = "123456";
        
        // Supondo que a função cadastrarUsuario seja definida corretamente
        // e retorne uma mensagem de sucesso
        $resultado = cadastrarUsuario($nome, $email, $senha);

        // Verificando se o retorno da função é o esperado
        $this->assertEquals("Cadastro realizado com sucesso!", $resultado);
    }
}
