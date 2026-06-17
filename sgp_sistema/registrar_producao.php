<?php 
// 1. Puxa a estrutura global (Segurança + Menu Lateral)
require_once 'header.php'; 

// 2. Garante a ligação à base de dados MySQL
require_once 'conexao.php'; 

$mensagem = "";

// 3. Processa a baixa de stock quando o formulário é enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $produto_id = intval($_POST['produto_id']);
    $quantidade_usada = intval($_POST['quantidade_usada']);
    $utilizador_id = $_SESSION['user_id']; // Identifica o utilizador logado

    try {
        // Inicia uma transação segura no MySQL
        $conexao->beginTransaction();

        // Verifica se há stock suficiente no armazém antes de subtrair
        $sql_check = "SELECT quantidade_atual, nome_produto FROM produtos WHERE id = :id";
        $stmt_check = $conexao->prepare($sql_check);
        $stmt_check->execute([':id' => $produto_id]);
        $prod = $stmt_check->fetch(PDO::FETCH_ASSOC);

        if ($prod && $prod['quantidade_atual'] >= $quantidade_usada) {
            
            // EXECUTA A MÁGICA: Deduz a quantidade atual no banco de dados
            $sql_update = "UPDATE produtos SET quantidade_atual = quantidade_atual - :qtd WHERE id = :id";
            $stmt_update = $conexao->prepare($sql_update);
            $stmt_update->execute([':qtd' => $quantidade_usada, ':id' => $produto_id]);

            // Grava o evento na tabela de movimentações para auditoria da UC
            $sql_mov = "INSERT INTO movimentacoes (produto_id, utilizador_id, tipo_movimentacao, quantidade, descricao) 
                        VALUES (:prod_id, :user_id, 'saida', :qtd, 'Material consumido na linha de produção')";
            $stmt_mov = $conexao->prepare($sql_mov);
            $stmt_mov->execute([
                ':prod_id' => $produto_id,
                ':user_id' => $utilizador_id,
                ':qtd' => $quantidade_usada
            ]);

            // Confirma todas as alterações no banco
            $conexao->commit();
            $mensagem = "<div class='alerta-sucesso'>✓ Sucesso: O consumo de " . $quantidade_usada . " un de '" . $prod['nome_produto'] . "' foi registado na produção!</div>";
        } else {
            // Cancela se não houver material suficiente
            $conexao->rollBack();
            $mensagem = "<div class='alerta-erro'>✕ Erro: Stock insuficiente! Quantidade disponível: " . ($prod ? $prod['quantidade_atual'] : 0) . " un.</div>";
        }

    } catch (PDOException $e) {
        $conexao->rollBack();
        $mensagem = "<div class='alerta-erro'>✕ Erro Crítico no MySQL: " . $e->getMessage() . "</div>";
    }
}

// 4. Procura os produtos no banco para preencher o campo de seleção (Dropdown)
$produtos = $conexao->query("SELECT id, nome_produto, quantidade_atual FROM produtos")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="modulo-cadastro">
    <h2>Controlo de Produção Ativa</h2>
    <p>Selecione a matéria-prima para dar baixa automática do stock do armazém.</p>

    <?php echo $mensagem; ?>

    <form method="POST" action="" class="form-industrial">
        <div class="linha-form">
            <div class="coluna-6">
                <label for="produto_id">Material a Utilizar *</label>
                <select id="produto_id" name="produto_id" required>
                    <option value="">-- Selecione o Produto --</option>
                    <?php foreach ($produtos as $p): ?>
                        <option value="<?php echo $p['id']; ?>">
                            <?php echo $p['nome_produto']; ?> (Atual: <?php echo $p['quantidade_atual']; ?> un)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="coluna-6">
                <label for="quantidade_usada">Quantidade para a Produção *</label>
                <input type="number" id="quantidade_usada" name="quantidade_usada" min="1" required placeholder="Ex: 5">
            </div>
        </div>

        <button type="submit" class="btn-salvar-material" style="background-color: #3b82f6;">
            Lançar Consumo de Material
        </button>
    </form>
</div>

<?php 
// 5. Puxa o rodapé global
require_once 'footer.php'; 
?>