<?php
// Configurações do XAMPP local
$host = "localhost";
$db_nome = "meu_projeto_sgp";
$utilizador = "root";
$senha = "";

try {
    // Criação da conexão usando PDO (Padrão mais seguro)
    $conexao = new PDO("mysql:host=$host;dbname=$db_nome;charset=utf8", $utilizador, $senha);
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $erro) {
    die("Erro ao ligar à base de dados do SGP: " . $erro->getMessage());
}
?>