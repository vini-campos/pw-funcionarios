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
        try {
            require 'conexao.php';

            if (isset($_GET['Id']) && is_numeric(base64_decode($_GET['Id']))) {
                $id = base64_decode($_GET['Id']);
            } else {
                throw new Exception('Funcionário não existe!');
            }

            if ($_SERVER['REQUEST_METHOD'] == 'GET') {

                $sql = "SELECT * FROM funcionario WHERE Id = $id";
                $resultado = $conexao->query($sql);
                $dados = $resultado->fetch_assoc();

                $nome = $dados['Nome'];
                $endereco = $dados['Endereco'];
                $idade = $dados['Idade'];
                $dataNasc = $dados['DataNasc'];
                $foto = $dados['Foto'];

            } else {

                $nome = $_POST['Nome'];
                $endereco = $_POST['Endereco'];
                $idade = $_POST['Idade'];
                $dataNasc = $_POST['DataNasc'];

                $erros = [];

                // calculo da idade
                $data = new DateTime($dataNasc);
                $hoje = new DateTime();
                $idadeCalc = $hoje->diff($data)->y;

                if (empty($nome) || strlen($nome) < 10) {
                    $erros[] = "O campo nome está vazio ou incompleto";
                }
                if (empty($endereco) || strlen($endereco) < 5) {
                    $erros[] = "O campo endereço está vazio ou incompleto";
                }
                if (!is_numeric($idade) || $idade < 18 || $idade > 70) {
                    $erros[] = "A idade deve estar entre 18 e 70 anos";
                }
                if (empty($dataNasc) || $idadeCalc != $idade) {
                    $erros[] = "A idade não corresponde à data de nascimento inserida";
                }

                if (!empty($erros)):
                    $foto = $_POST['FotoAtual'];
                ?>
                    <div class="modal" id="Modal" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title">Erro ao atualizar!</h5>
                                </div>
                                <div class="modal-body">
                                    <ul>
                                        <?php foreach ($erros as $erro): ?>
                                            <li><?php echo $erro;?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Corrigir</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const modal = new bootstrap.Modal(document.getElementById('Modal'));
                            modal.show();
                        });
                    </script>
                <?php
                else:
                    if ($_FILES['Foto']['error'] == UPLOAD_ERR_NO_FILE) {
                        $foto = $_POST['FotoAtual'];
                    } else {
                        $diretorioDestino = "uploads/";
                        $arquivoDestino = $diretorioDestino . basename($_FILES['Foto']['name']);
                        $foto = basename($_FILES['Foto']['name']);
                        move_uploaded_file($_FILES['Foto']['tmp_name'], $arquivoDestino);
                    }

                    $sql = "UPDATE Funcionario SET Nome='$nome', Endereco='$endereco', Idade=$idade, DataNasc='$dataNasc', Foto='$foto' WHERE Id=$id";
                    $conexao->query($sql);
                ?>
                    <div class="modal" tabindex="-1" id="Modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-success text-white">
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
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const modal = new bootstrap.Modal(document.getElementById('Modal'));
                            modal.show();
                        });
                    </script>
                <?php
                endif;
            }

        } catch (Exception $e) {
        ?>
            <div class="modal" tabindex="-1" id="Modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">Ocorreu um erro!</h5>
                        </div>
                        <div class="modal-body">
                            <p><?php echo $e->getMessage();?></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const modal = new bootstrap.Modal(document.getElementById('Modal'));
                    modal.show();
                });
            </script>
        <?php } ?>

    <main>
        <div class="container mt-5">
            <form action="editar.php?Id=<?php echo $_GET['Id'];?>" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Nome completo</label>
                    <input type="text" class="form-control" name="Nome" value="<?php echo $nome;?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Endereço</label>
                    <input type="text" class="form-control" name="Endereco" value="<?php echo $endereco;?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Idade</label>
                    <input type="number" class="form-control" name="Idade" value="<?php echo $idade;?>" min="18" max="70" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Data de nascimento</label>
                    <input type="date" class="form-control" name="DataNasc" value="<?php echo $dataNasc;?>" min="1900-01-01" max="2010-12-31" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Foto</label>
                    <input type="file" class="form-control" name="Foto" id="Foto" accept="image/*">
                    <div style="margin-top: 10px;">
                        <img class="img-preview img-fluid rounded" id="preview-img"
                             src="uploads/<?php echo $foto;?>"
                             alt="Preview da foto"
                             style="max-width: 200px; max-height: 200px; object-fit: cover;">
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