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
        $senhaHash = password_hash('admin123', PASSWORD_DEFAULT);

        $this->mockStmt->method('execute')->willReturn(true);
        $this->mockStmt->method('fetch')->willReturn([
            'id' => 1,
            'nome' => 'admin',
            'email' => 'admin@example.com', // Adicionado email ao mock
            'senha' => $senhaHash,
            'role' => 'admin',
        ]);
        $this->mockPdo->method('prepare')->willReturn($this->mockStmt);

        // Testar login válido
        $this->assertTrue(login('admin', 'admin123'));
    }

    public function testLoginInvalido() {
        // Configurar o comportamento esperado para login com senha incorreta
        $senhaHash = password_hash('admin123', PASSWORD_DEFAULT);

        $this->mockStmt->method('execute')->willReturn(true);
        $this->mockStmt->method('fetch')->willReturn([
            'id' => 1,
            'nome' => 'admin',
            'senha' => $senhaHash,
            'role' => 'admin',
        ]);
        $this->mockPdo->method('prepare')->willReturn($this->mockStmt);

        // Testar login com senha errada
        $this->assertFalse(login('admin', 'senha_errada'));
    }

    public function testLoginUsuarioNaoExistente() {
        // Configurar o comportamento esperado para login com usuário não existente
        $this->mockStmt->method('execute')->willReturn(true);
        $this->mockStmt->method('fetch')->willReturn(false); // Usuário não encontrado
        $this->mockPdo->method('prepare')->willReturn($this->mockStmt);

        // Testar login com usuário não existente
        $this->assertFalse(login('inexistente', 'senha123'));
    }

    public function testCadastroUsuario() {
        $nome = "Teste";
        $email = "teste@teste.com";
        $senha = "123456";

        // Mock para verificar se o email já existe
        $this->mockStmt->method('execute')->willReturn(true);
        $this->mockStmt->method('fetchColumn')->willReturn(0); // Email não cadastrado
        $this->mockPdo->method('prepare')->willReturn($this->mockStmt);

        // Mock para inserir novo usuário
        $this->mockStmt->method('execute')->willReturn(true);

        // Testar cadastro
        $resultado = cadastrarUsuario($nome, $email, $senha);
        $this->assertEquals("Cadastro realizado com sucesso!", $resultado);
    }

    public function testCadastroUsuarioEmailExistente() {
        $nome = "Teste";
        $email = "teste@teste.com";
        $senha = "123456";

        // Mock para verificar que o email já existe
        $this->mockStmt->method('execute')->willReturn(true);
        $this->mockStmt->method('fetchColumn')->willReturn(1); // Email já cadastrado
        $this->mockPdo->method('prepare')->willReturn($this->mockStmt);

        // Testar cadastro com email existente
        $resultado = cadastrarUsuario($nome, $email, $senha);
        $this->assertEquals("Este email já está cadastrado!", $resultado);
    }
}
