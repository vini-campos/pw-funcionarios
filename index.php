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

                <div class="search-wrap">
                    <input type="text"
                           class="search-input"
                           placeholder="Buscar usuário...">

                    <button class="search-btn">
                        Pesquisar
                    </button>
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

            $sql = "SELECT * FROM Funcionario ORDER BY Id";
            $query = $conexao->query($sql);


            while($dados = mysqli_fetch_array($query)) {
                
                $dt = new DateTime($dados['DataNasc'], new DateTimeZone("America/Sao_Paulo"));
                $data = $dt->format("d/m/Y");

                // para aparecer o usuario ainda é preciso fazer o insert manual no banco
                $id = base64_encode($dados["Id"]);
                echo <<< CARD
                    <div class="container mt-4">

                        <div class="card shadow-sm funcionario-card">
                            <div class="row g-0">

                                <!-- Foto -->
                                <div class="col-md-3 text-center p-3">
                                    <a href="editar.php?Id=$id">
                                        <img src="uploads/{$dados['Foto']}" alt="Foto do funcionário: {$dados['Nome']}" class="img-fluid rounded foto-funcionario">
                                    </a>
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
                                    <div class="m-3" style="display: inline-block;> 
                                        <a href="editar.php?Id=$id">
                                            <button class="btn btn-primary">Editar</button>
                                        </a>
                                    </div>
                                    
                                    <div class="m-3" style="display: inline-block;">
                                        <a href="#">
                                            <button class="btn btn-danger" onclick="modalExcluir('$id')">Deletar</button>
                                        </a>
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
    <?php include 'modalDel.php'//inclui o modal?>
    <script>
        function modalExcluir(id){

            document.getElementById("btnExcluir").href = "excluir.php?Id=" + id;

            const modal = new bootstrap.Modal(
                document.getElementById("modalExcluir")
            );

            modal.show();
        }
    </script>
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>