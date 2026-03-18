<?php
session_start();
require 'db.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Captura o tipo enviado pelo select do index.php
    $tipo   = $_POST['tipo'] ?? 'aluno';
    $nome   = $_POST['nome'] ?? '';
    $codigo = $_POST['codigo_validacao'] ?? '';
    $turma  = $_POST['turma'] ?? '';

    try {
        if ($tipo === 'professor') {
            // Lógica para Professor: Valida NOME e SENHA (ignora turma)
            $sql = "SELECT * FROM usuarios WHERE nome = :nome AND codigo_validacao = :codigo AND tipo = 'professor'";
            $params = [
                ':nome'   => $nome,
                ':codigo' => $codigo
            ];
        } else {
            // Lógica para Aluno: Valida NOME e TURMA (ignora senha)
            $sql = "SELECT * FROM usuarios WHERE nome = :nome AND turma = :turma AND tipo = 'aluno'";
            $params = [
                ':nome'  => $nome,
                ':turma' => $turma
            ];
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            // Sucesso! Guarda os dados na sessão
            $_SESSION['usuario_id']    = $usuario['id'];
            $_SESSION['usuario_nome']  = $usuario['nome'];
            $_SESSION['usuario_tipo']  = $usuario['tipo'];
            $_SESSION['usuario_turma'] = $usuario['turma'];

            header("Location: dashboard.php");
            exit;
        } else {
            // Se não encontrar nada no banco
            echo "<script>alert('Dados incorretos! Verifique as informações digitadas.'); window.location='index.php';</script>";
        }

    } catch (PDOException $e) {
        die("Erro ao consultar o banco: " . $e->getMessage());
    }
}