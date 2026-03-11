<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Sugerir Termo</title>
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="card mx-auto shadow" style="max-width: 600px;">
        <div class="card-body">
            <h3>Novo Termo Técnico</h3>
            <form action="api.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="acao" value="adicionar">
                <div class="mb-3">
                    <label>Categoria</label>
                    <select name="categoria" class="form-select">
                        <option value="portugues">Português</option>
                        <option value="matematica">Matemática</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Termo</label>
                    <input type="text" name="termo" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Definição</label>
                    <textarea name="definicao" class="form-control" rows="4" required></textarea>
                </div>
                <div class="mb-3">
                    <label>Imagem (Opcional para Matemática)</label>
                    <input type="file" name="imagem" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary w-100">Enviar para Aprovação</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>