CREATE DATABASE Funcionario;
USE Funcionario;

CREATE TABLE Funcionario(
    Id INT AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(50),
    Endereco VARCHAR(50),
    Idade INT,
    DataNasc DATE,
    Foto VARCHAR(50)
);