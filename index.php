<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
    <title>Gerenciador de funcionários</title>
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

            <!-- Área da direita -->
            <div class="nav-right">
                <!--filtro-->
                <div class="search-wrap">
                    <form action="#" method="post">
                        <input type="search" class="search-input" name="filtro" placeholder="Procurar funcionário...">
                        <input type="submit" class="search-btn" value="Pesquisar">
                    </form>
                </div>
                <a href="incluir.php" class="btn add-btn">
                    Cadastrar
                </a>

            </div>

        </div>
    </div>
</nav>
<main>
    <?php
        try {
            require 'conexao.php';

            $sql = "";
            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                $filtro = $_POST['filtro'];
                $sql = "SELECT * FROM Funcionario WHERE Nome LIKE '%$filtro%' ORDER BY Id";
            }
            else {
                $sql = "SELECT * FROM Funcionario ORDER BY Id";
            }

            $query = $conexao->query($sql);

            while($dados = mysqli_fetch_array($query)) {
                
                $dt = new DateTime($dados['DataNasc'], new DateTimeZone("America/Sao_Paulo"));
                $data = $dt->format("d/m/Y");

                echo <<< CARD
                    <div class="container mt-4">

                        <div class="card shadow-sm funcionario-card">
                            <div class="row g-0">

                                <!-- Foto -->
                                <div class="col-md-3 text-center p-3">
                                    <img src="uploads/{$dados['Foto']}" alt="Foto do funcionário: {$dados['Nome']}" class="img-fluid rounded foto-funcionario">
                                </div>

                                <div class="col-md-9">
                                    <div class="card-body">

                                        <h5 class="card-title mb-3"> {$dados['Nome']} </h5>

                                        <div class="row">

                                            <div class="col-sm-6 mb-2">
                                                <strong>ID:</strong> {$dados['Id']}
                                            </div>

                                            <div class="col-sm-6 mb-2">
                                                <strong>Idade:</strong> {$dados['Idade']}
                                            </div>

                                            <div class="col-sm-6 mb-2">
                                                <strong>Endereço:</strong> {$dados['Endereco']}
                                            </div>

                                            <div class="col-sm-6 mb-2">
                                                <strong>Data de nascimento:</strong> {$data}
                                            </div>

                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                CARD;

            }

        } catch(Exception $e) {
            echo "fazer a mensagem de erro correta!!!";
        }

    ?>
</main>

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>