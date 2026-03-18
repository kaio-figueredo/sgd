<?php
session_start();
require 'db.php'; // Carrega a conexão $pdo

// Proteção: Se não houver sessão, volta para o login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

// Busca termos aprovados em ordem alfabética
try {
    $stmt = $pdo->query("SELECT * FROM termos WHERE status = 'aprovado' ORDER BY termo ASC");
    $termos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao carregar termos: " . $e->getMessage());
}

// Filtra os termos por categoria
$portugues = array_filter($termos, fn($t) => $t['categoria'] == 'portugues');
$matematica = array_filter($termos, fn($t) => $t['categoria'] == 'matematica');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dicionário Técnico - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <style>
        :root { --primary-color: #0d6efd; --bg-gray: #f0f2f5; }
        body { background-color: var(--bg-gray); font-family: 'Segoe UI', sans-serif; }
        
        /* Navbar estilizada */
        .navbar { border-bottom: 4px solid #ffc107; }
        .navbar-brand { font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }

        /* Estilo dos Cards e Accordion */
        .main-card { border-radius: 15px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .accordion-item { border: none; margin-bottom: 10px; border-radius: 10px !important; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.03); }
        .accordion-button { font-weight: 600; color: #444; background-color: #fff; }
        .accordion-button:not(.collapsed) { background-color: #e7f1ff; color: var(--primary-color); }
        
        /* Abas (Pills) */
        .nav-pills .nav-link { background: #fff; color: #555; margin: 0 5px; border-radius: 25px; border: 1px solid #dee2e6; transition: 0.3s; }
        .nav-pills .nav-link.active { background-color: var(--primary-color); box-shadow: 0 4px 10px rgba(13,110,253,0.3); }

        /* Imagem do Termo */
        .img-container { text-align: center; background: #fdfdfd; padding: 15px; border-radius: 10px; border: 1px dashed #ddd; margin-top: 15px; }
        .term-img { max-width: 100%; height: auto; border-radius: 8px; max-height: 350px; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand" href="#"><i class="bi bi-book-half"></i> Dicionário SENAI</a>
        <div class="d-flex align-items-center">
            <span class="navbar-text me-3 text-white d-none d-md-block">
                <i class="bi bi-person-circle"></i> Olá, <strong><?= htmlspecialchars($_SESSION['usuario_nome']) ?></strong>!
            </span>
            <?php if($_SESSION['usuario_tipo'] == 'professor'): ?>
                <a href="admin.php" class="btn btn-warning btn-sm me-2 shadow-sm fw-bold text-dark">MODERAR</a>
            <?php endif; ?>
            <a href="add_termo.php" class="btn btn-light btn-sm me-2 shadow-sm">Sugerir Termo</a>
            <a href="logout.php" class="btn btn-outline-light btn-sm border-2">Sair</a>
        </div>
    </div>
</nav>

<div class="container pb-5">
    <header class="text-center mb-5">
        <h1 class="display-6 fw-bold text-dark">Termos Técnicos</h1>
        <p class="text-muted">Explore as definições de Português e Matemática</p>
    </header>

    <ul class="nav nav-pills justify-content-center mb-4" id="pills-tab" role="tablist">
        <li class="nav-item">
            <button class="nav-link active px-4" data-bs-toggle="pill" data-bs-target="#pills-port">
                <i class="bi bi-translate me-2"></i>Português
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link px-4" data-bs-toggle="pill" data-bs-target="#pills-mat">
                <i class="bi bi-calculator me-2"></i>Matemática
            </button>
        </li>
    </ul>

    <div class="tab-content card p-4 main-card">
        
        <div class="tab-pane fade show active" id="pills-port">
            <div class="accordion accordion-flush" id="accPort">
                <?php if(empty($portugues)) echo "<p class='text-center text-muted'>Nenhum termo aprovado.</p>"; ?>
                
                <?php foreach($portugues as $t): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#term-<?= $t['id'] ?>">
                                <?= htmlspecialchars($t['termo']) ?>
                            </button>
                        </h2>
                        <div id="term-<?= $t['id'] ?>" class="accordion-collapse collapse" data-bs-parent="#accPort">
                            <div class="accordion-body">
                                <p class="text-secondary" style="font-size: 1.1rem;"><?= nl2br(htmlspecialchars($t['definicao'])) ?></p>
                                <?php if(!empty($t['imagem_url'])): ?>
                                    <div class="img-container">
                                        <img src="<?= $t['imagem_url'] ?>" class="term-img shadow-sm" alt="Ilustração">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="tab-pane fade" id="pills-mat">
            <div class="accordion accordion-flush" id="accMat">
                <?php if(empty($matematica)) echo "<p class='text-center text-muted'>Nenhum termo aprovado.</p>"; ?>

                <?php foreach($matematica as $t): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#term-<?= $t['id'] ?>">
                                <?= htmlspecialchars($t['termo']) ?>
                            </button>
                        </h2>
                        <div id="term-<?= $t['id'] ?>" class="accordion-collapse collapse" data-bs-parent="#accMat">
                            <div class="accordion-body">
                                <p class="text-secondary" style="font-size: 1.1rem;"><?= nl2br(htmlspecialchars($t['definicao'])) ?></p>
                                <?php if(!empty($t['imagem_url'])): ?>
                                    <div class="img-container">
                                        <img src="<?= $t['imagem_url'] ?>" class="term-img shadow-sm" alt="Ilustração">
                                    </div>
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