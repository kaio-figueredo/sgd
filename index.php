<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>SGD - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f0f2f5; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { width: 100%; max-width: 400px; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); background: white; }
    </style>
</head>
<body>
<div class="login-card">
    <h3 class="text-center mb-4 text-primary">Dicionário Técnico</h3>
    <form action="auth.php" method="POST">
        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" name="nome" class="form-control" required placeholder="Seu nome completo">
        </div>
        <div class="mb-3">
            <label class="form-label">Tipo de Usuário</label>
            <select name="tipo" id="tipoSelect" class="form-select" onchange="toggleFields()">
                <option value="aluno">Aluno</option>
                <option value="professor">Professor</option>
            </select>
        </div>
        <div id="divTurma" class="mb-3">
            <label class="form-label">Turma</label>
            <input type="text" name="turma" class="form-control" placeholder="Ex: 3º Dev">
        </div>
        <div id="divCodigo" class="mb-3 d-none">
            <label class="form-label text-danger">Código de Validação Professor</label>
            <input type="password" name="codigo_validacao" class="form-control" placeholder="Digite o código secreto">
        </div>
        <button type="submit" class="btn btn-primary w-100 py-2">Entrar no Sistema</button>
    </form>
</div>

<script>
function toggleFields() {
    const tipo = document.getElementById('tipoSelect').value;
    document.getElementById('divTurma').classList.toggle('d-none', tipo === 'professor');
    document.getElementById('divCodigo').classList.toggle('d-none', tipo === 'aluno');
}
</script>
</body>
</html>