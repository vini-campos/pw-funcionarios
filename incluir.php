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
                <div class="navbar-brand-custom">
                    <img src="img/favicon.png" alt="Logo">
                    <span>Gerenciador de Funcionários</span>
                </div>
            </div>
        </div>
    </nav>
    <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST'):
            try {
                require 'conexao.php';

                $nome = $_POST['Nome'];
                $endereco = $_POST['Endereco'];
                $idade = $_POST['Idade'];
                $dataNasc = $_POST['DataNasc'];
                $diretorioDestino = "uploads/";
                $arquivoDestino = $diretorioDestino . basename($_FILES['Image']['name']);
                $arquivo = basename($_FILES['Image']['name']);

                $erros = [];

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
                if ($_FILES['Image']['error'] != UPLOAD_ERR_OK) {
                    $erros[] = "O campo foto não pode estar vazio";
                }

            } catch(Exception $e) {
                $erros[] = "Aconteceu um erro: " . $e->getMessage();
            }
        endif;
    ?>

    <?php if (!empty($erros)): ?>
        <div class="modal" id="Modal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">Erro ao cadastrar!</h5>
                    </div>
                    <div class="modal-body">
                        <ul>
                            <?php foreach ($erros as $erro): ?>
                                <li><?= htmlspecialchars($erro) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Ok</button>
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

    <?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
        <?php
            $sql = "INSERT INTO Funcionario (Nome, Endereco, Idade, DataNasc, Foto) VALUES ('$nome', '$endereco', '$idade', '$dataNasc', '$arquivo')";
            $conexao->query($sql);

            if (move_uploaded_file($_FILES['Image']['tmp_name'], $arquivoDestino)) {
                $mensagem = "O arquivo " . htmlspecialchars(basename($_FILES["Image"]["name"])) . " foi enviado.";
            } else {
                $mensagem = "Aconteceu um erro durante o envio da sua imagem.";
            }
        ?>
        <div class="modal" id="Modal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">Cadastrado com sucesso!</h5>
                    </div>
                    <div class="modal-body">
                        <p><?= $mensagem ?></p>
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
    <?php endif; ?>

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
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>