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
        // Configurar o comportamento esperado
        $this->mockStmt->method('execute')->willReturn(true);
        $this->mockStmt->method('fetch')->willReturn([
            'id' => 1,
            'username' => 'admin',
            'senha' => md5('admin123'),
            'role' => 'admin',
        ]);

        $this->mockPdo->method('prepare')->willReturn($this->mockStmt);

        // Testar login válido
        $this->assertTrue(login('admin', 'admin123'));
    }

    public function testLoginInvalido() {
        // Configurar o comportamento esperado
        $this->mockStmt->method('execute')->willReturn(true);
        $this->mockStmt->method('fetch')->willReturn(false);

        $this->mockPdo->method('prepare')->willReturn($this->mockStmt);

        // Testar login inválido
        $this->assertFalse(login('admin', 'senha_errada'));
    }

    public function testLoginUsuarioNaoExistente() {
        // Configurar o comportamento esperado
        $this->mockStmt->method('execute')->willReturn(true);
        $this->mockStmt->method('fetch')->willReturn(false);

        $this->mockPdo->method('prepare')->willReturn($this->mockStmt);

        // Testar login com usuário não existente
        $this->assertFalse(login('inexistente', 'senha123'));
    }
    public function testLoginSuccess() {
        $auth = new Auth();
        $this->assertTrue($auth->login('email@teste.com', 'senha123'));
    }
    public function testCadastroUsuario()
    {
        $nome = "Teste";
        $email = "teste@teste.com";
        $senha = "123456";

        $resultado = cadastrarUsuario($nome, $email, $senha);

        $this->assertEquals("Cadastro realizado com sucesso!", $resultado);
    }
}

