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
                $email = $dados['Email'];
                $endereco = $dados['Endereco'];
                $dataNasc = $dados['DataNasc'];
                $foto = $dados['Foto'];

            } else {

                $nome = $_POST['Nome'];
                $email = $_POST['Email'];
                $endereco = $_POST['Endereco'];
                $dataNasc = $_POST['DataNasc'];

                $erros = [];

                // Calculo da idade a partir da data de nascimento
                $data = new DateTime($dataNasc);
                $hoje = new DateTime();
                $idadeCalc = $hoje->diff($data)->y;

                if (empty($nome) || strlen($nome) < 5) {
                    $erros[] = "O campo nome está vazio ou incompleto";
                }
                if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $erros[] = "O campo e-mail é inválido";
                }
                if (empty($endereco) || strlen($endereco) < 5) {
                    $erros[] = "O campo endereço está vazio ou incompleto";
                }
                if (empty($dataNasc)) {
                    $erros[] = "O campo data de nascimento está vazio";
                } elseif ($idadeCalc < 18 || $idadeCalc > 70) {
                    $erros[] = "O funcionário deve ter entre 18 e 70 anos";
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
                                            <li><?php echo $erro; ?></li>
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
                        $arquivo = basename($_FILES['Foto']['name']);
                        $arquivoDestino = "uploads/" . $arquivo;
                        $foto = $arquivo;
                        move_uploaded_file($_FILES['Foto']['tmp_name'], $arquivoDestino);
                    }

                    $sql = "UPDATE Funcionario SET Nome='$nome', Email='$email', Endereco='$endereco', Idade=$idadeCalc, DataNasc='$dataNasc', Foto='$foto' WHERE Id=$id";
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
                            <p><?php echo $e->getMessage(); ?></p>
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
            <form action="editar.php?Id=<?php echo $_GET['Id']; ?>" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Nome completo</label>
                    <input type="text" class="form-control" name="Nome" value="<?php echo $nome; ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="Email" value="<?php echo $email; ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Endereço</label>
                    <input type="text" class="form-control" name="Endereco" value="<?php echo $endereco; ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Data de nascimento</label>
                    <input type="date" class="form-control" name="DataNasc" value="<?php echo $dataNasc; ?>" min="1900-01-01" max="2010-12-31" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Foto</label>
                    <input type="file" class="form-control" name="Foto" id="Foto" accept="image/*">
                    <div style="margin-top: 10px;">
                        <img class="img-preview img-fluid rounded" id="preview-img" src="uploads/<?php echo $foto; ?>" alt="Preview da foto"
                            style="max-width: 200px; max-height: 200px; object-fit: cover;">
                    </div>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" required>
                    <label class="form-check-label">Desejo enviar os dados inseridos</label>
                </div>
                <input type="hidden" name="FotoAtual" value="<?php echo $foto; ?>">
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