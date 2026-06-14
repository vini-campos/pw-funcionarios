<?php

    require 'conexao.php';

    $id = base64_decode($_GET['Id']);

    $sql = "DELETE FROM Funcionario WHERE Id = $id";

    $conexao->query($sql);

    header("Location: index.php");
?>