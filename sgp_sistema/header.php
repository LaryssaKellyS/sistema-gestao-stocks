<?php
// Garante que a sessão está ativa em todas as páginas que usam o header
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Segurança: Se o utilizador não fez login, é expulso para a tela de login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGP - Sistema de Gestão Industrial</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>

    <header class="navbar">
        <div class="logo">SGP - Fábrica</div>
        <div class="usuario-info">
            Olá, <strong><?php echo $_SESSION['user_nome']; ?></strong> 
            <span class="badge-nivel">
                <?php 
                    if($_SESSION['user_nivel'] == 1) echo "Chefia";
                    elseif($_SESSION['user_nivel'] == 2) echo "Operador Sola";
                    else echo "Operador Costura";
                ?>
            </span>
            <a href="logout.php" class="btn-sair">Sair</a>
        </div>
    </header>

   <nav class="sidebar">
    <a href="dashboard.php"><i class="fa-solid fa-chart-line"></i> Painel Principal</a>
    
    <?php if ($_SESSION['user_nivel'] == 1): ?>
        <a href="cadastrar_produto.php"><i class="fa-solid fa-box-open"></i> Cadastrar Produto</a>
        <a href="gerenciar_usuarios.php"><i class="fa-solid fa-users-gear"></i> Gerir Funcionários</a>
    <?php endif; ?>
    
    <?php if ($_SESSION['user_nivel'] == 1 || $_SESSION['user_nivel'] == 2): ?>
        <a href="registrar_producao.php"><i class="fa-solid fa-gears"></i> Registar Produção</a>
    <?php endif; ?>

    <a href="ver_stock.php"><i class="fa-solid fa-boxes-stacked"></i> Stock Atual</a>
    
    <a href="movimentacoes.php"><i class="fa-solid fa-history"></i> Histórico de Stock</a>
</nav>

    <main class="conteudo-principal">