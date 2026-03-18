<?php
session_start();
require 'db.php';

// Proteção: Só entra professor
if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] != 'professor') { 
    header("Location: index.php"); 
    exit; 
}

// ALTERAÇÃO NA SQL: Agora pegamos 'pendente' OU 'aprovado'
// Usamos o ORDER BY para os pendentes aparecerem primeiro
$sql = "SELECT t.*, u.nome as autor 
        FROM termos t 
        JOIN usuarios u ON t.autor_id = u.id 
        WHERE t.status IN ('pendente', 'aprovado') 
        ORDER BY t.status DESC, t.id DESC";

$termos = $pdo->query($sql)->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Admin - Gerenciar Dicionário</title>
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gerenciar Termos</h2>
        <a href="dashboard.php" class="btn btn-secondary">Voltar</a>
    </div>

    <div class="row">
        <?php foreach($termos as $t): ?>
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm border-<?= $t['status'] == 'pendente' ? 'warning' : 'success' ?>">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h5 class="card-title"><?= htmlspecialchars($t['termo']) ?></h5>
                        <span class="badge <?= $t['status'] == 'pendente' ? 'bg-warning' : 'bg-success' ?>">
                            <?= ucfirst($t['status']) ?>
                        </span>
                    </div>
                    
                    <p class="text-muted small mb-1">Categoria: <?= htmlspecialchars($t['categoria']) ?></p>
                    <p class="text-muted small">Enviado por: <?= htmlspecialchars($t['autor']) ?></p>
                    <p><?= nl2br(htmlspecialchars($t['definicao'])) ?></p>
                    <hr>

                    <div class="d-flex gap-2">
                        <?php if($t['status'] == 'pendente'): ?>
                            <a href="api.php?acao=aprovar&id=<?= $t['id'] ?>" class="btn btn-success btn-sm">Aprovar</a>
                        <?php endif; ?>
                        
                        <a href="api.php?acao=excluir&id=<?= $t['id'] ?>" 
                           class="btn btn-danger btn-sm" 
                           onclick="return confirm('Tem certeza que deseja excluir este termo?')">
                           Excluir
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        
        <?php if(count($termos) == 0): ?>
            <div class="col-12 text-center">
                <p class="text-muted">Nenhum termo encontrado.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>