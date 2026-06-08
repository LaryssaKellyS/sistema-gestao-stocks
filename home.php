<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Stock - Gestão de Validades</title>
    <link rel="stylesheet" href="css/estilo.css">
    <!-- pra importar os comando dos icones que vou querer usar -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> 
</head>
<body>

<div class="dashboard-container">
    
    <header class="dashboard-header"> <!--- header: é o topo do teu sistema.-->
        <div class="logo-area">
            <i class="fa-solid fa-boxes-stacked icon-logo"></i>
            <h1>Painel de Stock</h1>
        </div>
        <div class="user-area">
            <div class="user-info">
                <i class="fa-solid fa-circle-user icon-user"></i>
                <span>Admin <strong>Laryssa</strong></span>
            </div>
            <a href="login.php" class="btn-logout" title="Sair do Sistema">
                <i class="fa-solid fa-right-from-bracket"></i>
            </a>
        </div>
    </header>

    <section class="cards-grid">
        <div class="card card-total">
            <div class="card-icon"><i class="fa-solid fa-box"></i></div>
            <div class="card-data">
                <h3>Total Itens</h3>
                <p class="number">1,250</p>
                <span class="subtext">Materiais e Componentes</span>
            </div>
        </div>

        <div class="card card-alerta">
            <div class="card-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
            <div class="card-data">
                <h3>Próximos a Vencer</h3>
                <p class="number">8</p>
                <span class="subtext">Nos próximos 30 dias</span>
            </div>
        </div>

        <div class="card card-perigo">
            <div class="card-icon"><i class="fa-solid fa-skull-crossbones"></i></div>
            <div class="card-data">
                <h3>Itens Expirados</h3>
                <p class="number">3</p>
                <span class="subtext">Necessitam de descarte</span>
            </div>
        </div>
    </section>

    <main class="content-box">
        <div class="content-header">
            <h2><i class="fa-solid fa-list-check"></i> Inventário Detalhado</h2>
            <div class="action-bar">
                <input type="text" placeholder="Pesquisar materiais..." class="search-input">
                <select class="filter-select">
                    <option value="">Filtrar por Categoria</option>
                    <option value="Limpeza">Limpeza</option>
                    <option value="Alimentação">Alimentação</option>
                </section>
                <a href="index.php" class="btn-primary"><i class="fa-solid fa-plus"></i> Registar Novo Material</a>
            </div>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID Material</th>
                        <th>Material / Modelo</th>
                        <th>Categoria</th>
                        <th>Quantidade</th>
                        <th>Data de Validade</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>001</td>
                        <td>Detergente Multiusos 1L</td>
                        <td>Limpeza</td>
                        <td>50</td>
                        <td>15/12/2026</td>
                        <td><span class="badge badge-ok">OK</span></td>
                    </tr>
                    <tr>
                        <td>002</td>
                        <td>Iogurte Morango Pack 4</td>
                        <td>Alimentação</td>
                        <td>12</td>
                        <td>12/06/2026</td>
                        <td><span class="badge badge-alerta">ALERTA</span></td>
                    </tr>
                    <tr>
                        <td>003</td>
                        <td>Farinha de Trigo 1kg</td>
                        <td>Alimentação</td>
                        <td>5</td>
                        <td>01/03/2026</td>
                        <td><span class="badge badge-perigo">EXPIRADO</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
</div>

</body>
</html>