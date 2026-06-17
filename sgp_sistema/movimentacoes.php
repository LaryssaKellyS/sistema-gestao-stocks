<?php 
// 1. Puxa a estrutura global (Segurança + Menu Lateral)
require_once 'header.php'; 

// 2. Conecta à base de dados MySQL
require_once 'conexao.php'; 

try {
    /* 3. SQL Ajustado: Trocámos m.data por m.data_movimento para bater 100% com o MySQL */
    $sql = "SELECT m.*, p.nome_produto, u.nome as nome_funcionario 
            FROM movimentacoes m
            INNER JOIN produtos p ON m.produto_id = p.id
            INNER JOIN utilizadores u ON m.utilizador_id = u.id
            ORDER BY m.data_movimento DESC"; 
            
    $stmt = $conexao->query($sql);
    $historico = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erro ao carregar o histórico de stock: " . $e->getMessage());
}
?>

<div class="painel-alertas" style="margin-top: 20px;">
    <h2><i class="fa-solid fa-history"></i> Histórico de Movimentações de Stock</h2>
    <p style="margin-bottom: 25px; color: #64748b;">Auditoria em tempo real de todas as entradas e saídas de materiais efetuadas no sistema.</p>
<div style="margin-bottom: 20px; display: flex; gap: 10px;">
    <a href="exportar_pdf.php" target="_blank" class="badge-perigo" style="background-color: #fee2e2; color: #991b1b; padding: 10px 15px; border-radius: 6px; text-decoration: none; font-weight: bold; border: 1px solid #fecaca;">
        <i class="fa-solid fa-file-pdf"></i> Exportar para PDF
    </a>
    <a href="exportar_excel.php" class="badge-perigo" style="background-color: #dcfce7; color: #166534; padding: 10px 15px; border-radius: 6px; text-decoration: none; font-weight: bold; border: 1px solid #bbf7d0;">
        <i class="fa-solid fa-file-excel"></i> Exportar para Excel
    </a>
</div>
    <?php if (empty($historico)): ?>
        <div class="card-alerta safe">
            <p>ℹ️ Nenhuma movimentação de stock foi registada até ao momento.</p>
        </div>
    <?php else: ?>
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
                            <?php if ($mov['tipo_movimentacao'] == 'entrada'): ?>
                                <span class="badge-perigo" style="background-color: #dcfce7; color: #166534; border: 1px solid #bbf7d0;">📥 Entrada</span>
                            <?php else: ?>
                                <span class="badge-perigo" style="background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca;">📤 Saída (Produção)</span>
                            <?php endif; ?>
                        </td>
                        
                        <td>
                            <strong class="<?php echo $mov['tipo_movimentacao'] == 'entrada' ? 'txt-sucesso' : 'txt-perigo'; ?>">
                                <?php echo $mov['tipo_movimentacao'] == 'entrada' ? '+' : '-'; ?> <?php echo $mov['quantidade']; ?> un
                            </strong>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php 
// 4. Puxa o rodapé global
require_once 'footer.php'; 
?>