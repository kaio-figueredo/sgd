<?php
session_start();
require 'db.php';

// Busca termos aprovados em ordem alfabética
$stmt = $pdo->query("SELECT * FROM termos WHERE status = 'aprovado' ORDER BY termo ASC");
$termos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$portugues = array_filter($termos, fn($t) => $t['categoria'] == 'portugues');
$matematica = array_filter($termos, fn($t) => $t['categoria'] == 'matematica');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dicionário Técnico SGD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .nav-tabs .nav-link { color: #495057; font-weight: bold; }
        .term-title { color: #0d6efd; font-weight: 600; cursor: pointer; }
        .img-mat { max-width: 300px; border-radius: 8px; margin-top: 10px; display: block; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand" href="#"><i class="bi bi-book"></i> Dicionário SENAI</a>
        <div class="d-flex">
            <span class="navbar-text me-3 text-white">Olá, <?= $_SESSION['usuario_nome'] ?>!</span>
            <?php if($_SESSION['usuario_tipo'] == 'professor'): ?>
                <a href="admin.php" class="btn btn-warning btn-sm me-2">Moderar</a>
            <?php endif; ?>
            <a href="add_termo.php" class="btn btn-light btn-sm me-2">Sugerir Termo</a>
            <a href="logout.php" class="btn btn-outline-light btn-sm">Sair</a>
        </div>
    </div>
</nav>

<div class="container">
    <h2 class="text-center mb-4">Termos Técnicos</h2>

    <ul class="nav nav-pills justify-content-center mb-4" id="pills-tab" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#pills-port">Português</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#pills-mat">Matemática</button>
        </li>
    </ul>

    <div class="tab-content card p-4 shadow-sm">
        <div class="tab-pane fade show active" id="pills-port">
            <div class="accordion accordion-flush" id="accPort">
                <?php foreach($portugues as $i => $t): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed term-title" type="button" data-bs-toggle="collapse" data-bs-target="#p<?= $i ?>">
                                <?= $t['termo'] ?>
                            </button>
                        </h2>
                        <div id="p<?= $i ?>" class="accordion-collapse collapse" data-bs-parent="#accPort">
                            <div class="accordion-body text-secondary"><?= nl2br($t['definicao']) ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="tab-pane fade" id="pills-mat">
            <div class="accordion accordion-flush" id="accMat">
                <?php foreach($matematica as $i => $t): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed term-title" type="button" data-bs-toggle="collapse" data-bs-target="#m<?= $i ?>">
                                <?= $t['termo'] ?>
                            </button>
                        </h2>
                        <div id="m<?= $i ?>" class="accordion-collapse collapse" data-bs-parent="#accMat">
                            <div class="accordion-body">
                                <p class="text-secondary"><?= nl2br($t['definicao']) ?></p>
                                <?php if($t['imagem_url']): ?>
                                    <img src="<?= $t['imagem_url'] ?>" class="img-mat shadow-sm" alt="Ilustração">
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>