<?php
session_start();
require 'db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $termo = $_POST['termo'] ?? '';
    $definicao = $_POST['definicao'] ?? '';
    $categoria = $_POST['categoria'] ?? '';
    $autor_id = $_SESSION['usuario_id'];
    $imagem_url = null; // Padrão se não houver foto

    // Lógica de Upload da Imagem
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
        $pasta_destino = 'uploads/';
        
        // Gera um nome único para o arquivo para não sobrescrever outros
        $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $nome_arquivo = uniqid() . "." . $extensao;
        $caminho_final = $pasta_destino . $nome_arquivo;

        // Move o arquivo da memória temporária para a pasta uploads
        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_final)) {
            $imagem_url = $caminho_final; // Caminho que será salvo no banco
        }
    }

    try {
        $sql = "INSERT INTO termos (termo, definicao, categoria, imagem_url, autor_id, status) 
                VALUES (:termo, :definicao, :categoria, :imagem_url, :autor_id, 'aprovado')";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':termo' => $termo,
            ':definicao' => $definicao,
            ':categoria' => $categoria,
            ':imagem_url' => $imagem_url,
            ':autor_id' => $autor_id
        ]);

        header("Location: dashboard.php?msg=enviado");
        exit;
    } catch (PDOException $e) {
        die("Erro ao salvar: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Sugerir Termo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f0f2f5; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .card { width: 100%; max-width: 500px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="card p-4">
        <h3 class="text-center mb-4">Novo Termo Técnico</h3>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Categoria</label>
                <select name="categoria" class="form-select" required>
                    <option value="portugues">Português</option>
                    <option value="matematica">Matemática</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Termo</label>
                <input type="text" name="termo" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Definição</label>
                <textarea name="definicao" class="form-control" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Imagem (Opcional)</label>
                <input type="file" name="imagem" class="form-control" accept="image/*">
            </div>
            <button type="submit" class="btn btn-primary w-100">Enviar para Aprovação</button>
            <a href="dashboard.php" class="btn btn-link w-100 mt-2 text-secondary">Voltar</a>
        </form>
    </div>
</body>
</html>