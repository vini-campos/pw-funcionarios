<?php
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

                                   //credenciais para usar no host do site
    $host = "localhost";           //$host = "sql302.infinityfree.com";  
    $banco = "funcionario";        //$banco = "if0_42176059_funcionario";
    $user = "root";                //$user = "if0_42176059";
    $pass = "";                    //$pass = "LuisFlavio";

    try {
        $conexao = new mysqli($host, $user, $pass, $banco);
        $conexao->set_charset("utf8");
    } catch(Exception $e) {
        throw new Exception("Problemas com a conexão com o banco de dados : <br>" . $e->getMessage());
    }
?>