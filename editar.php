<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
    <title>Editar Funcionário</title>

    <style>
        *{
            box-sizing: border-box;
        }

        body{
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: white;
            padding: 20px;
        }

        .container{
            width: 100%;
            max-width: 500px;
            padding: 30px;
            border-radius: 12px;
            background-color: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.15);
        }

        form{
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        p{
            margin: 0;
            font-weight: 600;
            font-size: 15px;
        }

        .campo{
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            outline: none;
            transition: 0.2s;
        }

        .campo:focus{
            border-color: #0d6efd;
            box-shadow: 0 0 5px rgba(13,110,253,0.3);
        }

        .divImg{
            display: flex;
            justify-content: center;
            align-items: center;
        
        }

        #preview-img{
            width: 200px;
            max-width: 100%;
            border-radius: 8px;
            object-fit: cover;
            display: block;
        }

        .btn{
            width: 100%;
            padding: 10px;
        }

        @media (max-width: 576px){

            .container{
                padding: 20px;
            }

            p{
                font-size: 14px;
            }

            .campo{
                font-size: 14px;
            }
        }
    </style>
</head>

<body>

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
            $dataNasc = $_POST['Data'];
            $diretorioDestino = "uploads/";
            $arquivoDestino = $diretorioDestino . basename($_FILES['Foto']['name']);
            $foto = $_FILES['Foto']['name'];

            move_uploaded_file(
                $_FILES['Foto']['tmp_name'],
                $arquivoDestino
            );

            $sql = "update funcionario set nome='" . $nome . "', endereco='". $endereco ."', idade=". $idade .", DataNasc='". $dataNasc ."', Foto ='". $foto ."'
            where Id = $id";

            $resultado = $conexao->query($sql);

            echo <<<MODAL
                    <div class="modal" tabindex="-1" id="modalSucesso">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Cadastrado com sucesso!</h5>
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
                        window.onload = function(){
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
        echo <<<ALERT
            <div class="alert alert-danger">
                {$e->getMessage()}
            </div>
        ALERT;
    }
?>

<main>
    <div class="container">

        <form action="#" name="FuncionariosUPDT" method="post" enctype="multipart/form-data">

            <p>Nome:</p>
            <input type="text" required="required" class="campo" name="Nome" value="<?php echo $nome;?>">

            <p>Endereço:</p>
            <input type="text" required="required" class="campo" name="Endereco" value="<?php echo $endereco;?>">

            <p>Idade:</p>
            <input type="number" required="required" class="campo" name="Idade" value="<?php echo $idade;?>">

            <p>Data de Nascimento:</p>
            <input type="date" required="required" class="campo" name="Data" value="<?php echo $dataNasc;?>">

            <p>Foto:</p>

            <div class="divImg" id="preview-container">
                <img src="uploads/<?php echo $foto?>" alt="Foto do Funcionário" id="preview-img">
            </div>

            <input type="file" required="required" class="campo" name="Foto" id="Foto" accept="image/*">

            <input type="submit" class="btn btn-outline-primary" value="Atualizar">

            <input type="reset" class="btn btn-outline-dark" value="Limpar">

        </form>

    </div>
</main>
  <script>
        //preview da foto
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