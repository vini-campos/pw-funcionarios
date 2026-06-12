<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="css/incluir.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <title>Adicionar Funcionário</title>
</head>
<body>
    <nav class="site-navbar">
    <div class="container-fluid px-4">
        <div class="navbar-content">

            <!-- Logo -->
            <div class="navbar-brand-custom">
                <img src="img/favicon.png" alt="Logo">
                <span>Gerenciador de Funcionários</span>
            </div>
        </div>
    </div>
</nav>
    <main>
        <div class="container mt-5">
            <form action="incluir.php" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="Nome" class="form-label">Nome completo</label>
                    <input type="text" class="form-control" name="nome" placeholder="Nome do Funcionário" required>
                </div>
                <div class="mb-3">
                    <label for="Endereco" class="form-label">Endereço</label>
                    <input type="text" class="form-control" name="Endereco" placeholder="Endereço do Funcionário" required>
                </div>
                <div class="mb-3">
                    <label for="Idade" class="form-label">Idade</label>
                    <input type="number" class="form-control" name="Idade" placeholder="Idade do Funcionário" required>
                </div>
                <div class="mb-3">
                    <label for="DataNasc" class="form-label">Data de nascimento</label>
                    <input type="date" class="form-control" name="DataNasc" placeholder="Data de nascimento do Funcionário" required>
                </div>
                <div class="mb-3">
                    <label for="Image" class="form-label">Foto</label>
                    <input type="file" class="form-control" name="Image" placeholder="Foto do Funcionário" required>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" required>
                    <label class="form-check-label" for="exampleCheck1">Desejo enviar os dados inseridos</label>
                </div>
                <button type="submit" id="btn" class="btn btn-outline-primary btn-sm" style="margin-right: 15px;">
                    Enviar Dados
                </button>
                <a href="index.php" class="btn btn-outline-secondary btn-sm">
                    Voltar para a página inicial
                </a>
            </form>
        </div>
    </main>
    <footer>

    </footer>
</body>
</html>