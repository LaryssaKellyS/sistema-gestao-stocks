<?php 
// 1. Puxa o cabeçalho global (que já faz a validação de segurança e carrega o Font Awesome)
require_once 'header.php'; 

// 2. Garante a ligação à base de dados MySQL
require_once 'conexao.php'; 

try {
    // Consulta A: Conta quantos produtos estão com stock crítico (menor ou igual ao mínimo)
    $sql_qtd_criticos = "SELECT COUNT(*) as total FROM produtos WHERE quantidade_atual <= stock_minimo";
    $stmt_criticos = $conexao->query($sql_qtd_criticos);
    $total_criticos = $stmt_criticos->fetch(PDO::FETCH_ASSOC)['total'];

    // Consulta B: Conta quantos produtos vencem nos próximos 30 dias (baseado na data atual)
    $sql_qtd_vencimento = "SELECT COUNT(*) as total FROM produtos WHERE data_vencimento <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) AND data_vencimento >= CURDATE()";
    $stmt_vencimento = $conexao->query($sql_qtd_vencimento);
    $total_vencimento = $stmt_vencimento->fetch(PDO::FETCH_ASSOC)['total'];

    // Consulta C: Procura os detalhes dos produtos críticos juntando a tabela de categorias
    $sql_detalhes = "SELECT p.*, c.nome_categoria 
                     FROM produtos p
                     INNER JOIN categorias c ON p.categoria_id = c.id
                     WHERE p.quantidade_atual <= p.stock_minimo";
    $produtos_criticos = $conexao->query($sql_detalhes)->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erro ao carregar dados do painel: " . $e->getMessage());
}
?>

<h2>Painel de Controlo Principal</h2>
<p>Monitorização em tempo real de inconformidades e roturas de stock na linha de produção.</p>

<div class="container-cartoes">
    
    <div class="card-status">
        <div class="card-icone icone-perigo">
            <i class="fa-solid fa-triangle-exclamation" style="color: #ef4444;"></i>
        </div>
        <div class="card-conteudo">
            <span class="card-titulo">Alertas de Stock Baixo</span>
            <span class="card-numero"><?php echo $total_criticos; ?></span>
        </div>
    </div>

    <div class="card-status">
        <div class="card-icone icone-aviso">
            <i class="fa-solid fa-box" style="color: #f59e0b;"></i>
        </div>
        <div class="card-conteudo">
            <span class="card-titulo">Produtos a Vencer (30 dias)</span>
            <span class="card-numero"><?php echo $total_vencimento; ?></span>
        </div>
    </div>

    <div class="card-status">
        <div class="card-icone icone-sucesso">
            <i class="fa-solid fa-industry" style="color: #10b981;"></i>
        </div>
        <div class="card-conteudo">
            <span class="card-titulo">Produção Ativa</span>
            <span class="card-numero">5</span>
        </div>
    </div>

</div>

<div class="painel-alertas">
    <?php if (empty($produtos_criticos)): ?>
        <div class="card-alerta safe">
            <h3>✓ Estado Operacional: Excelente</h3>
            <p>Todos os materiais industriais registados encontram-se com níveis de stock acima do mínimo estipulado.</p>
        </div>
    <?php else: ?>
        <div class="titulo-secao-alerta">Stock Overview - Ações Urgentes</div>
        
        <table class="tabela-industrial">
            <thead>
                <tr>
                    <th>Material</th>
                    <th>Categoria</th>
                    <th>Lote</th>
                    <th>Validade</th>
                    <th>Quantidade</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produtos_criticos as $prod): ?>
                    <tr class="linha-critica">
                        <td><strong><?php echo $prod['nome_produto']; ?></strong></td>
                        <td><?php echo $prod['nome_categoria']; ?></td>
                        <td><?php echo $prod['lote']; ?></td>
                        <td><?php echo $prod['data_vencimento'] ? date('d/m/Y', strtotime($prod['data_vencimento'])) : 'N/A'; ?></td>
                        <td class="txt-perigo"><?php echo $prod['quantidade_atual']; ?> un</td>
                        <td><span class="badge-perigo">Stock Mínimo</span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php 
// 3. Puxa o rodapé global para fechar o layout de forma limpa
require_once 'footer.php'; 
?>