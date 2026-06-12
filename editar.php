<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/editar.css">
    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
    <title>Editar Funcionário</title>

    <style>

    </style>
</head>
<body>
    <nav class="site-navbar">
        <div class="container-fluid px-4">
            <div class="navbar-content">
                <div class="navbar-brand-custom">
                    <img src="img/favicon.png" alt="Logo">
                    <span>Gerenciador de Funcionários</span>
                </div>
            </div>
        </div>
    </nav>
    <?php
        try{
            require 'conexao.php';

            if (isset($_GET['Id']) && is_numeric(base64_decode($_GET['Id']))) {
                $id = base64_decode($_GET['Id']);
            } else {
                throw new Exception('Funcionário não existe!');
            }

            if($_SERVER['REQUEST_METHOD'] == 'GET')
            {
                $sql = "select * from funcionario where Id = $id";
                $resultado = $conexao->query($sql);
                $dados = $resultado->fetch_assoc();

                $nome = $dados['Nome'];
                $endereco = $dados['Endereco'];
                $idade = $dados['Idade'];
                $dataNasc = $dados['DataNasc'];
                $foto = $dados['Foto'];
            }
            else{
                $nome = $_POST['Nome'];
                $endereco = $_POST['Endereco'];
                $idade = $_POST['Idade'];
                $dataNasc = $_POST['DataNasc'];
                
                // se nenhuma outra foto for enviada, ele mantem a atual
                if ($_FILES['Foto']['error'] == UPLOAD_ERR_NO_FILE) {
                    $foto = $_POST['FotoAtual'];
                }
                else {
                    $diretorioDestino = "uploads/";
                    $arquivoDestino = $diretorioDestino . basename($_FILES['Foto']['name']);
                    $foto = basename($_FILES['Foto']['name']);
                    move_uploaded_file($_FILES['Foto']['tmp_name'], $arquivoDestino);
                }

                $sql = "update funcionario set nome='" . $nome . "', endereco='". $endereco ."', idade=". $idade .", DataNasc='". $dataNasc ."', Foto ='". $foto ."'
                where Id = $id";

                $resultado = $conexao->query($sql);

                echo <<<MODAL
                        <div class="modal" tabindex="-1" id="modalSucesso">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Atualizado com sucesso!</h5>
                                    </div>
                                    <div class="modal-body">
                                        <p>Dados alterados com sucesso!</p>
                                    </div>
                                    <div class="modal-footer">
                                        <a href="index.php" class="btn btn-primary">Início</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script> //js p mostar o
                            window.onload = function() {
                                const modal = new bootstrap.Modal(
                                    document.getElementById('modalSucesso')
                                );
                                modal.show();
                            }
                        </script>
                    MODAL;
            }
        }
        catch(Exception $e){
            echo <<<MODAL
                    <div class="modal" tabindex="-1" id="modalSucesso">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Ocorreu um erro!</h5>
                                </div>
                                <div class="modal-body">
                                    <p>{$e->getMessage()}</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Fechar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script> //js p mostar o
                        window.onload = function() {
                            const modal = new bootstrap.Modal(
                                document.getElementById('modalSucesso')
                            );
                            modal.show();
                        }
                    </script>
                MODAL;
        }
    ?>
    <main>
    <div class="container mt-5">
        <form action="editar.php?Id=<?= $_GET['Id'] ?>" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Nome completo</label>
                <input type="text" class="form-control" name="Nome" value="<?php echo $nome;?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Endereço</label>
                <input type="text" class="form-control" name="Endereco" value="<?php echo $endereco?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Idade</label>
                <input type="number" class="form-control" name="Idade" value="<?php echo $idade?>" min="18" max="70" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Data de nascimento</label>
                <input type="date" class="form-control" name="DataNasc" value="<?php echo $dataNasc?>" min="1900-01-01" max="2010-12-31" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Foto</label>
                <input type="file" class="form-control" name="Foto" id="Foto" accept="image/*">
                <!-- preview: mostra foto atual, troca ao selecionar nova -->
                <div style="margin-top: 10px;">
                    <img class="img-preview img-fluid rounded" id="preview-img" src="uploads/<?php echo $foto;?>" alt="Preview da foto" style="max-width: 200px; max-height: 200px; object-fit: cover;">
                </div>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" required>
                <label class="form-check-label">Desejo enviar os dados inseridos</label>
            </div>
            <input type="hidden" name="FotoAtual" value="<?php echo $foto;?>">
            <button type="submit" class="btn btn-outline-primary btn-sm" style="margin-right: 15px;">
                Salvar alterações
            </button>
            <a href="index.php" class="btn btn-outline-secondary btn-sm">
                Voltar para a página inicial
            </a>
        </form>
    </div>
</main>

<script>
    document.getElementById('Foto').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (event) {
            document.getElementById('preview-img').src = event.target.result;
        };
        reader.readAsDataURL(file);
    });
</script>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>