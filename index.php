<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Stock & Validades</title>
    <link rel="stylesheet" href="./css/estilo.css">
</head>
<body>

<div class="container">
    <h2>📦 Registar Produto no Stock</h2>
    <p>Insira as informações do produto e controle a data de expiração.</p>
    
    <form action="salvar_produto.php" method="POST">
        <div class="form-group">
            <label for="nome">Nome do Produto / Modelo:</label>
            <input type="text" id="nome" name="nome" required placeholder="Ex: Caixa de Leite UHT">
        </div>

        <div class="form-group">
            <label for="categoria">Categoria:</label>
            <select id="categoria" name="categoria" required>
                <option value="">-- Selecione uma Categoria --</option>
                <option value="Alimentação">Alimentação</option>
                <option value="Limpeza">Limpeza</option>
                <option value="Logística">Logística</option>
            </select>
        </div>

        <div class="form-group">
            <label for="quantidade">Quantidade:</label>
            <input type="number" id="quantidade" name="quantidade" min="1" required placeholder="Ex: 50">
        </div>

        <div class="form-group">
            <label for="validade">Data de Validade:</label>
            <input type="date" id="validade" name="validade" required>
        </div>

        <button type="submit">Gravar no Stock</button>
    </form>
</div>

</body>
</html>