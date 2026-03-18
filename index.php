<?php
require 'db.php'; 

// Busca as turmas para o SELECT (apenas as que existem no banco)
$query_turmas = $pdo->query("SELECT DISTINCT turma FROM usuarios WHERE turma IS NOT NULL AND turma != '' AND turma != 'Staff/Professor'");
$lista_turmas = $query_turmas->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>SGD - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f0f2f5; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { width: 100%; max-width: 400px; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); background: white; }
        .hidden { display: none; } /* Classe auxiliar para esconder */
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
            <select name="tipo" id="tipoUsuario" class="form-select" onchange="alternarCampos()">
                <option value="aluno" selected>Aluno</option>
                <option value="professor">Professor</option>
            </select>
        </div>

        <div class="mb-3" id="divTurma">
            <label class="form-label">Turma</label>
            <select name="turma" class="form-select" id="selectTurma">
                <option value="" selected disabled>Selecione sua turma</option>
                <?php foreach($lista_turmas as $t): ?>
                    <option value="<?= $t['turma'] ?>"><?= $t['turma'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3 hidden" id="divSenha">
            <label class="form-label">Código de Validação (Senha)</label>
            <input type="password" name="codigo_validacao" id="inputSenha" class="form-control" placeholder="Digite sua senha">
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 mt-3">Entrar no Sistema</button>
    </form>
</div>

<script>
function alternarCampos() {
    var tipo = document.getElementById("tipoUsuario").value;
    var divTurma = document.getElementById("divTurma");
    var divSenha = document.getElementById("divSenha");
    var inputSenha = document.getElementById("inputSenha");
    var selectTurma = document.getElementById("selectTurma");

    if (tipo === "professor") {
        // Professor: Mostra Senha e Esconde Turma
        divSenha.classList.remove("hidden");
        divTurma.classList.add("hidden");
        
        // Ajusta obrigatoriedade
        inputSenha.required = true;
        selectTurma.required = false;
    } else {
        // Aluno: Mostra Turma e Esconde Senha
        divTurma.classList.remove("hidden");
        divSenha.classList.add("hidden");
        
        // Ajusta obrigatoriedade
        selectTurma.required = true;
        inputSenha.required = false;
    }
}
</script>
</body>
</html>