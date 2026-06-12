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

            <div class="navbar-brand-custom">
                <img src="img/icon_php_96x96.png" alt="Logo">
                <span>Gerenciador de Funcionários</span>
            </div>

            <div class="nav-right">
                <div class="search-wrap">
                    <form action="#" method="post">
                        <input type="search" class="search-input" name="filtro" placeholder="Procurar funcionário...">
                        <input type="submit" class="search-btn" value="Pesquisar">
                    </form>
                </div>
                <a href="incluir.php" class="add-btn">
                    Cadastrar
                </a>
            </div>

        </div>
    </div>
</nav>
<main>
    <div class="page-wrap">
        <?php
            try {
                require 'conexao.php';

                if ($_SERVER['REQUEST_METHOD'] == "POST") {
                    $filtro = $_POST['filtro'];
                    $sql = "SELECT * FROM Funcionario WHERE Nome LIKE '%$filtro%' ORDER BY Id";
                } else {
                    $sql = "SELECT * FROM Funcionario ORDER BY Id";
                }

                $query = $conexao->query($sql);
                $total = $query->num_rows;
        ?>

        <div class="page-header">
            <h1 class="page-title">Funcionários</h1>
            <span class="page-count"><?php echo $total;?> Registros</span>
        </div>

        <?php while ($dados = mysqli_fetch_array($query)):
            $dt = new DateTime($dados['DataNasc'], new DateTimeZone("America/Sao_Paulo"));
            $data = $dt->format("d/m/Y");
            $id = base64_encode($dados["Id"]);
        ?>

        <div class="container mt-3">
            <div class="card shadow-sm funcionario-card">
                <div class="row g-0">

                    <!-- Foto -->
                    <div class="col-md-3 text-center p-3 d-flex align-items-center justify-content-center">
                        <a href="editar.php?Id=<?= $id; ?>">
                            <img src="uploads/<?php echo $dados['Foto'];?>" alt="Foto de <?php echo $dados['Nome'];?>" class="img-fluid rounded foto-funcionario">
                        </a>
                    </div>

                    <!-- Dados -->
                    <div class="col-md-7">
                        <div class="card-body">
                            <h5 class="card-title mb-3"><?php echo $dados['Nome'];?></h5>
                            <div class="row">
                                <div class="col-sm-6 mb-2">
                                    <strong>ID:</strong> <?php echo $dados['Id'];?>
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <strong>Idade:</strong> <?php echo $dados['Idade'];?>
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <strong>Endereço:</strong> <?php echo $dados['Endereco'];?>
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <strong>Nascimento:</strong> <?php echo $data;?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ações -->
                    <div class="col-md-2 d-flex align-items-center justify-content-center">
                        <div class="card-actions">
                            <a href="editar.php?Id=<?= $id ?>" class="btn-editar">Editar</a>
                            <button class="btn-deletar" onclick="modalExcluir('<?= $id ?>')">Deletar</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <?php endwhile; ?>

        <?php
            } catch (Exception $e) {
                echo <<<MODAL
                    <div class="modal" tabindex="-1" id="modalErro">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Ocorreu um erro!</h5>
                                </div>
                                <div class="modal-body">
                                    <p>{$e->getMessage()}</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        window.onload = function() {
                            const modal = new bootstrap.Modal(document.getElementById('modalErro'));
                            modal.show();
                        }
                    </script>
                MODAL;
            }
        ?>

    </div>
</main>

<?php include 'modalDel.php'; ?>

<script>
    function modalExcluir(id) {
        document.getElementById("btnExcluir").href = "excluir.php?Id=" + id;
        const modal = new bootstrap.Modal(document.getElementById("modalExcluir"));
        modal.show();
    }
</script>
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>