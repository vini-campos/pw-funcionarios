<?php
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $host = "localhost";
    $banco = "site";
    $user = "root";
    $pass = "";

    try {
        $conexao = new mysqli($host, $user, $pass, $banco);
        $conexao->set_charset("utf8");
    } catch(Exception $e) {
        throw new Exception("Problemas com a conexão com o banco de dados : <br>" . $e->getMessage());
    }
?>