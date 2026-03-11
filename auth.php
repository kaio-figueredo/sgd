<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $tipo = $_POST['tipo'];
    $turma = $_POST['turma'] ?? '';
    $codigo_enviado = $_POST['codigo_validacao'] ?? '';

    // DEFINA O CÓDIGO DO PROFESSOR AQUI
    $codigo_secreto_professor = "SENAI123"; 

    if ($tipo == 'professor') {
        if ($codigo_enviado !== $codigo_secreto_professor) {
            echo "<script>alert('Código de Professor Inválido!'); window.location='index.php';</script>";
            exit;
        }
        $turma = "Staff/Professor";
    }

    // Salva no banco para saber quem postou o que
    $stmt = $pdo->prepare("INSERT INTO usuarios (nome, tipo, turma, codigo_validacao) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nome, $tipo, $turma, $codigo_enviado]);
    
    // Salva na SESSÃO (Isso faz o login durar)
    $_SESSION['usuario_id'] = $pdo->lastInsertId();
    $_SESSION['usuario_nome'] = $nome;
    $_SESSION['usuario_tipo'] = $tipo;

    header("Location: dashboard.php");
}
?>