<?php
session_start();
require 'db.php';

$acao = $_GET['acao'] ?? $_POST['acao'] ?? '';

// 1. APROVAR
if ($acao == 'aprovar' && $_SESSION['usuario_tipo'] == 'professor') {
    $id = $_GET['id'];
    $pdo->prepare("UPDATE termos SET status = 'aprovado' WHERE id = ?")->execute([$id]);
    header("Location: admin.php?msg=aprovado");
}

// 2. EXCLUIR
if ($acao == 'excluir' && $_SESSION['usuario_tipo'] == 'professor') {
    $id = $_GET['id'];
    $pdo->prepare("DELETE FROM termos WHERE id = ?")->execute([$id]);
    header("Location: admin.php?msg=excluido");
}

// 3. ADICIONAR NOVO (ALUNO OU PROFESSOR)
if ($acao == 'adicionar') {
    $termo = $_POST['termo'];
    $def = $_POST['definicao'];
    $cat = $_POST['categoria'];
    $autor = $_SESSION['usuario_id'];
    
    // Se for professor, já entra aprovado. Se for aluno, entra pendente.
    $status = ($_SESSION['usuario_tipo'] == 'professor') ? 'aprovado' : 'pendente';

    $img_nome = "";
    if (!empty($_FILES['foto']['name'])) {
        $img_nome = "uploads/" . time() . "_" . $_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], $img_nome);
    }

    $sql = "INSERT INTO termos (categoria, termo, definicao, imagem_url, status, autor_id) VALUES (?,?,?,?,?,?)";
    $pdo->prepare($sql)->execute([$cat, $termo, $def, $img_nome, $status, $autor]);
    
    header("Location: dashboard.php?msg=enviado");
}
?>