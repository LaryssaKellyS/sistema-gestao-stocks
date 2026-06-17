<?php 
// 1. Puxa o cabeçalho (que já garante a segurança e traz a barra lateral)
require_once 'header.php'; 

// 2. Liga à base de dados
require_once 'conexao.php'; 

$mensagem = "";

// 3. Processa o formulário quando o utilizador submete
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome_produto'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $lote = $_POST['lote'];
    $data_vencimento = !empty($_POST['data_vencimento']) ? $_POST['data_vencimento'] : null;
    $quantidade = intval($_POST['quantidade_atual']);
    $stock_minimo = intval($_POST['stock_minimo']);
    $categoria_id = intval($_POST['categoria_id']);

    try {
        $sql = "INSERT INTO produtos (nome_produto, marca, modelo, lote, data_vencimento, quantidade_atual, stock_minimo, categoria_id) 
                VALUES (:nome, :marca, :modelo, :lote, :vencimento, :qtd, :minimo, :categoria)";
        
        $stmt = $conexao->prepare($sql);
        $stmt->execute([
            ':nome' => $nome,
            ':marca' => $marca,
            ':modelo' => $modelo,
            ':lote' => $lote,
            ':vencimento' => $data_vencimento,
            ':qtd' => $quantidade,
            ':minimo' => $stock_minimo,
            ':categoria' => $categoria_id
        ]);

        $mensagem = "<div class='alerta-sucesso'>✓ Material cadastrado com sucesso e registado no MySQL!</div>";
    } catch (PDOException $erro) {
        $mensagem = "<div class='alerta-erro'>✕ Erro ao gravar: " . $erro->getMessage() . "</div>";
    }
}

// 4. Procura as categorias vivas no MySQL para preencher o Dropdown dinamicamente
$sql_categorias = "SELECT id, nome_categoria FROM categorias";
$categorias = $conexao->query($sql_categorias)->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="modulo-cadastro">
    <h2>Registo de Novos Materiais / Stock</h2>
    <p>Insira as especificações técnicas do material para dar entrada no inventário da fábrica.</p>

    <?php echo $mensagem; ?>

    <form method="POST" action="" class="form-industrial">
        <div class="linha-form">
            <div class="coluna-6">
                <label for="nome_produto">Nome do Produto/Material *</label>
                <input type="text" id="nome_produto" name="nome_produto" required placeholder="Ex: Borracha Sola T39">
            </div>
            <div class="coluna-6">
                <label for="categoria_id">Categoria de Controlo *</label>
                <select id="categoria_id" name="categoria_id" required>
                    <option value="">-- Selecione --</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>"><?php echo $cat['nome_categoria']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="linha-form">
            <div class="coluna-4">
                <label for="marca">Marca</label>
                <input type="text" id="marca" name="marca" placeholder="Ex: FlexMax">
            </div>
            <div class="coluna-4">
                <label for="modelo">Especificação / Modelo</label>
                <input type="text" id="modelo" name="modelo" placeholder="Ex: Antiderrapante">
            </div>
            <div class="coluna-4">
                <label for="lote">Lote de Fábrica</label>
                <input type="text" id="lote" name="lote" placeholder="Ex: L-2026/01">
            </div>
        </div>

        <div class="linha-form">
            <div class="coluna-4">
                <label for="data_vencimento">Data de Vencimento</label>
                <input type="date" id="data_vencimento" name="data_vencimento">
            </div>
            <div class="coluna-4">
                <label for="quantidade_atual">Quantidade Inicial em Stock *</label>
                <input type="number" id="quantidade_atual" name="quantidade_atual" min="0" required value="0">
            </div>
            <div class="coluna-4">
                <label for="stock_minimo">Nível de Alerta (Mínimo) *</label>
                <input type="number" id="stock_minimo" name="stock_minimo" min="1" required value="10">
            </div>
        </div>

        <button type="submit" class="btn-salvar-material">Gravar Material no Sistema</button>
    </form>
</div>

<?php 
// 5. Puxa o rodapé global
require_once 'footer.php'; 
?>