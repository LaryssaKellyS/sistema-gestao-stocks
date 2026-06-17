<?php
// 1. Inicia a sessão e valida a segurança (copiado do teu header padrão)
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// 2. Conecta à base de dados MySQL
require_once 'conexao.php';

try {
    // Procura o histórico completo idêntico ao da tua tela de movimentos
    $sql = "SELECT m.*, p.nome_produto, u.nome as nome_funcionario 
            FROM movimentacoes m
            INNER JOIN produtos p ON m.produto_id = p.id
            INNER JOIN utilizadores u ON m.utilizador_id = u.id
            ORDER BY m.data_movimento DESC"; 
            
    $stmt = $conexao->query($sql);
    $historico = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erro ao gerar relatório: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Movimentações de Stock - SGP</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <style>
        /* Estilos exclusivos para a folha de impressão ficar perfeita em A4 */
        body { 
            background-color: #ffffff !important; 
            color: #1e293b !important;
            padding: 30px !important;
        }
        .topo-relatorio {
            border-bottom: 3px solid #1e3a8a;
            padding-bottom: 12px;
            margin-bottom: 25px;
        }
        .topo-relatorio h1 {
            color: #1e3a8a;
            font-size: 26px;
            margin: 0;
        }
        .meta-dados {
            font-size: 13px;
            color: #475569;
            margin-top: 5px;
        }
        .tabela-industrial {
            width: 100% !important;
            border-collapse: collapse !important;
            margin-top: 20px !important;
        }
        .tabela-industrial th {
            background-color: #1e3a8a !important;
            color: #ffffff !important;
        }
        /* Remove efeitos de hover que não fazem sentido no papel/PDF */
        .tabela-industrial tr:hover { background-color: transparent !important; }
    </style>
</head>
<body>

    <div class="topo-relatorio">
        <h1>SGP - Sistema de Gestão de Produção</h1>
        <div class="meta-dados">
            <strong>Relatório:</strong> Histórico de Movimentações de Stock (Módulo UC00605)<br>
            <strong>Emitido por:</strong> <?php echo $_SESSION['user_nome']; ?> | 
            <strong>Data de Emissão:</strong> <?php echo date('d/m/Y H:i'); ?>
        </div>
    </div>

    <table class="tabela-industrial">
        <thead>
            <tr>
                <th>Data / Hora</th>
                <th>Material / Componente</th>
                <th>Operador / Colaborador</th>
                <th>Tipo</th>
                <th>Quantidade</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($historico as $mov): ?>
                <tr>
                    <td><strong><?php echo date('d/m/Y H:i', strtotime($mov['data_movimento'])); ?></strong></td>
                    <td><?php echo $mov['nome_produto']; ?></td>
                    <td><?php echo $mov['nome_funcionario']; ?></td>
                    <td>
                        <?php echo ($mov['tipo_movimentacao'] == 'entrada') ? '📥 Entrada' : '📤 Saída (Produção)'; ?>
                    </td>
                    <td>
                        <strong>
                            <?php echo ($mov['tipo_movimentacao'] == 'entrada') ? '+' : '-'; ?> <?php echo $mov['quantidade']; ?> un
                        </strong>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        // Quando a página carregar no navegador, abre a janela de impressão/PDF automaticamente
        window.onload = function() {
            window.print();
            // Pequeno delay para fechar a aba extra se o utilizador cancelar/concluir
            setTimeout(function() {
                window.close();
            }, 500);
        }
    </script>

</body>
</html>