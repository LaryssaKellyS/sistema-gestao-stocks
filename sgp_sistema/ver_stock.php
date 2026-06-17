<?php 
// 1. Segurança e Menu
require_once 'header.php'; 

// 2. Conexão à Base de Dados
require_once 'conexao.php'; 

try {
    // Busca todos os produtos do armazém e traz o nome da categoria juntinho
    $sql = "SELECT p.*, c.nome_categoria 
            FROM produtos p
            INNER JOIN categorias c ON p.categoria_id = c.id
            ORDER BY p.nome_produto ASC";
            
    $stmt = $conexao->query($sql);
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao carregar o stock atual: " . $e->getMessage());
}
?>

<div class="painel-alertas" style="margin-top: 20px;">
    <h2><i class="fa-solid fa-boxes-stacked"></i> Inventário e Stock Atual</h2>
    <p style="margin-bottom: 25px; color: #64748b;">Consulte em tempo real a quantidade disponível de cada material e os limites de segurança definidos.</p>

    <?php if (empty($produtos)): ?>
        <div class="card-alerta info">
            <p>ℹ️ Ainda não existem produtos registados no inventário.</p>
        </div>
    <?php else: ?>
        <table class="tabela-industrial">
            <thead>
                <tr>
                    <th>Material / Componente</th>
                    <th>Categoria</th>
                    <th>Lote</th>
                    <th>Stock Mínimo</th>
                    <th>Stock Atual</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produtos as $prod): ?>
                    <?php 
                        // Verifica se o stock está crítico (igual ou abaixo do mínimo)
                        $critico = ($prod['quantidade_atual'] <= $prod['stock_minimo']);
                    ?>
                    <tr>
                        <td><strong><?php echo $prod['nome_produto']; ?></strong></td>
                        <td><span style="background-color: #f1f5f9; padding: 4px 8px; border-radius: 4px; font-size: 12px; color: #475569; border: 1px solid #e2e8f0;"><?php echo $prod['nome_categoria']; ?></span></td>
                        <td><code><?php echo $prod['lote']; ?></code></td>
                        <td><?php echo $prod['stock_minimo']; ?> un</td>
                        
                        <td>
                            <strong class="<?php echo $critico ? 'txt-perigo' : 'txt-sucesso'; ?>">
                                <?php echo $prod['quantidade_atual']; ?> un
                            </strong>
                        </td>
                        
                        <td>
                            <?php if ($critico): ?>
                                <span class="badge-perigo" style="background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; font-size: 11px;">⚠️ Stock Crítico</span>
                            <?php else: ?>
                                <span class="badge-perigo" style="background-color: #dcfce7; color: #166534; border: 1px solid #bbf7d0; font-size: 11px;">🟢 Conforme</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php 
// 3. Rodapé global
require_once 'footer.php'; 
?>