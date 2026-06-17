<?php
require_once 'conexao.php';

// Configura o navegador para descarregar o ficheiro em formato Excel
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=Relatorio_Stock_SGP.xls");
header("Pragma: no-cache");
header("Expires: 0");

try {
    $sql = "SELECT m.*, p.nome_produto, u.nome as nome_funcionario 
            FROM movimentacoes m
            INNER JOIN produtos p ON m.produto_id = p.id
            INNER JOIN utilizadores u ON m.utilizador_id = u.id
            ORDER BY m.data_movimento DESC";
    $stmt = $conexao->query($sql);
    $historico = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}

// Criamos uma variável para juntar toda a tabela HTML
$html = "<table border='1'>";
$html .= "<tr>
            <th style='background-color:#1e3a8a; color:white;'>Data / Hora</th>
            <th style='background-color:#1e3a8a; color:white;'>Material</th>
            <th style='background-color:#1e3a8a; color:white;'>Operador</th>
            <th style='background-color:#1e3a8a; color:white;'>Tipo</th>
            <th style='background-color:#1e3a8a; color:white;'>Quantidade</th>
          </tr>";

foreach ($historico as $mov) {
    $tipo = ($mov['tipo_movimentacao'] == 'entrada') ? 'Entrada' : 'Saída';
    $qtd = ($mov['tipo_movimentacao'] == 'entrada') ? "+".$mov['quantidade'] : "-".$mov['quantidade'];
    
    $html .= "<tr>";
    $html .= "<td>" . date('d/m/Y H:i', strtotime($mov['data_movimento'])) . "</td>";
    $html .= "<td>" . $mov['nome_produto'] . "</td>";
    $html .= "<td>" . $mov['nome_funcionario'] . "</td>";
    $html .= "<td>" . $tipo . "</td>";
    $html .= "<td>" . $qtd . " un</td>";
    $html .= "</tr>";
}
$html .= "</table>";

// O TRUQUE DE VALOR DO JAVASCRIPT / PHP: 
// Converte a string de UTF-8 para UTF-16LE com BOM (Byte Order Mark) para o Excel ler os acentos perfeitamente!
echo chr(255).chr(254).mb_convert_encoding($html, 'UTF-16LE', 'UTF-8');
exit;
?>