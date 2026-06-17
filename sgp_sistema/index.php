<?php
// 1. Inicia a sessão para o sistema lembrar quem logou
session_start();

// 2. Importa o ficheiro de ligação à base de dados
require_once 'conexao.php';

$erro = "";

// 3. Verifica se o formulário foi enviado pelo botão "Entrar"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    try {
        // Procura pelo funcionário ativo com o email digitado
        $sql = "SELECT * FROM utilizadores WHERE email = :email AND status_conta = 'ativo'";
        $stmt = $conexao->prepare($sql);
        $stmt->execute([':email' => $email]);
        $utilizador = $stmt->fetch(PDO::FETCH_ASSOC);

        // Valida se o utilizador existe e se a senha bate com a do banco
        if ($utilizador && $utilizador['senha'] === $senha) {
            
            // Guarda as variáveis de sessão para usar no header.php
            $_SESSION['user_id'] = $utilizador['id'];
            $_SESSION['user_nome'] = $utilizador['nome'];
            $_SESSION['user_nivel'] = $utilizador['nivel_acesso_id'];

            // Redireciona para o painel principal!
            header("Location: dashboard.php");
            exit();
        } else {
            $erro = "Email ou senha incorretos!";
        }
    } catch (PDOException $e) {
        $erro = "Erro no sistema: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>SGP - Login de Fábrica</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>

<div class="login-card">
    <h2>SGP - Acesso Industrial</h2>
    
    <?php if(!empty($erro)) { echo "<div class='erro-msg'>$erro</div>"; } ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="email">Email do Colaborador:</label>
            <input type="email" id="email" name="email" required placeholder="ex: joao@factory.com">
        </div>

        <div class="form-group">
            <label for="senha">Senha de Acesso:</label>
            <input type="password" id="senha" name="senha" required placeholder="••••••••">
        </div>

        <button type="submit" class="btn-entrar">Entrar no Sistema</button>
    </form>
</div>

</body>
</html>