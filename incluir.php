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
    <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                require 'conexao.php';

                $nome = $_POST['Nome'];
                $endereco = $_POST['Endereco'];
                $idade = $_POST['Idade'];
                $dataNasc = $_POST['DataNasc'];
                $diretorioDestino = "uploads/";
                $arquivoDestino = $diretorioDestino . basename($_FILES['Image']['name']);
                $arquivo = basename($_FILES['Image']['name']);

                $sql = "INSERT INTO Funcionario (Nome, Endereco, Idade, DataNasc, Foto) VALUES ('$nome', '$endereco', '$idade', '$dataNasc', '$arquivo')";
                $resultado = $conexao->query($sql);

                if (move_uploaded_file($_FILES['Image']['tmp_name'], $arquivoDestino)) {
                    $mensagem = "O arquivo " . htmlspecialchars(basename($_FILES["Image"]["name"])) . " foi enviado.";
                } else {
                    $mensagem = "Aconteceu um erro durante o envio da sua imagem";
                }

                echo <<<ALERT
                    <div class="modal" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Cadastrado com sucesso!</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><?php $mensagem?></p>
                                </div>
                                <div class="modal-footer">
                                    <a href="index.php" class="btn btn-primary">Início</a>
                                </div>
                            </div>
                        </div>
                    </div>
                ALERT;

            } catch(Exception $e) {
                echo <<<ALERT
                    <div class="alert alert-danger container alert-dismissible fade show" role="alert">
                        <h2>Aconteceu um erro:<br>
                            {$e->getMessage()}
                        </h2>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <a href="index.php" class="btn btn-primary">Voltar</a>
                    </div>\n
                ALERT;
            }
        }
    ?>
    <main>
        <div class="container mt-5">
            <form action="incluir.php" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="Nome" class="form-label">Nome completo</label>
                    <input type="text" class="form-control" name="Nome" placeholder="Nome do Funcionário" required>
                </div>
                <div class="mb-3">
                    <label for="Endereco" class="form-label">Endereço</label>
                    <input type="text" class="form-control" name="Endereco" placeholder="Endereço do Funcionário" required>
                </div>
                <div class="mb-3">
                    <label for="Idade" class="form-label">Idade</label>
                    <input type="number" class="form-control" name="Idade" placeholder="Idade do Funcionário" min="18" max="70" required>
                </div>
                <div class="mb-3">
                    <label for="DataNasc" class="form-label">Data de nascimento</label>
                    <input type="date" class="form-control" name="DataNasc" placeholder="Data de nascimento do Funcionário" min="1900-01-01" max="2010-12-31" required>
                </div>
                <div class="mb-3">
                    <label for="Image" class="form-label">Foto</label>
                    <input type="file" class="form-control" name="Image" id="Image" accept="image/*" required>
                    <!-- Preview logo abaixo -->
                    <div id="preview-container" style="display:none; margin-top: 10px;">
                        <img class="img-preview img-fluid rounded" id="preview-img" src="" alt="Preview da foto">
                    </div>
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
    <script>
        //preview da foto
        document.getElementById('Image').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (event) {
                document.getElementById('preview-img').src = event.target.result;
                document.getElementById('preview-container').style.display = 'block';
            };
            reader.readAsDataURL(file);
        });
    </script>
    <script src="js/bootstrap.bunble.min.js"></script>
</body>
</html>