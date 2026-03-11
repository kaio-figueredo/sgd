<?php
session_start();
require 'db.php';
if ($_SESSION['usuario_tipo'] != 'professor') { header("Location: index.php"); exit; }

$pendentes = $pdo->query("SELECT t.*, u.nome as autor FROM termos t JOIN usuarios u ON t.autor_id = u.id WHERE t.status = 'pendente'")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Admin - Moderação</title>
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Pendentes de Aprovação</h2>
        <a href="dashboard.php" class="btn btn-secondary">Voltar</a>
    </div>

    <div class="row">
        <?php foreach($pendentes as $p): ?>
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><?= $p['termo'] ?> <span class="badge bg-info"><?= $p['categoria'] ?></span></h5>
                    <p class="text-muted small">Enviado por: <?= $p['autor'] ?></p>
                    <p><?= $p['definicao'] ?></p>
                    <hr>
                    <a href="api.php?acao=aprovar&id=<?= $p['id'] ?>" class="btn btn-success btn-sm">Aprovar</a>
                    <a href="api.php?acao=excluir&id=<?= $p['id'] ?>" class="btn btn-danger btn-sm">Excluir</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>