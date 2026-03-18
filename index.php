<?php
session_start();
require 'db.php';

// Busca turmas únicas para o select do aluno
try {
    $stmt = $pdo->query("SELECT DISTINCT turma FROM usuarios WHERE turma IS NOT NULL AND turma != '' ORDER BY turma ASC");
    $turmas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $turmas = [];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Dicionário Técnico SGD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #0d6efd;
            --accent-color: #ffc107;
        }

        body {
            background: linear-gradient(135deg, #f0f2f5 0%, #c9d6ff 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .login-header {
            background: var(--primary-color);
            padding: 30px;
            text-align: center;
            color: white;
        }

        .login-header i { font-size: 3rem; margin-bottom: 10px; }

        .form-control, .form-select {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #dee2e6;
            margin-bottom: 5px;
        }

        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
            border-color: var(--primary-color);
        }

        .btn-login {
            background: var(--primary-color);
            color: white;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            width: 100%;
            border: none;
            transition: 0.3s;
            margin-top: 15px;
        }

        .btn-login:hover {
            background: #0b5ed7;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
        }

        .input-group-text {
            background: transparent;
            border-radius: 10px 0 0 10px;
            border-right: none;
        }

        .has-icon .form-control { border-left: none; border-radius: 0 10px 10px 0; }

        label { font-size: 0.9rem; font-weight: 500; color: #555; margin-bottom: 5px; }

        /* Esconde campos específicos inicialmente */
        #field-senha, #field-turma { display: none; }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-header">
        <i class="bi bi-book-half"></i>
        <h4 class="mb-0">Dicionário SENAI</h4>
    </div>
    
    <div class="card-body p-4">
        <form action="auth.php" method="POST">
            
            <div class="mb-3">
                <label>Tipo de Acesso</label>
                <select name="tipo" id="tipoSelect" class="form-select" required onchange="toggleFields()">
                    <option value="" selected disabled>Quem é você?</option>
                    <option value="aluno">Sou Aluno</option>
                    <option value="professor">Sou Professor</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Nome Completo</label>
                <div class="input-group has-icon">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" name="nome" class="form-control" placeholder="Digite seu nome" required>
                </div>
            </div>

            <div id="field-turma" class="mb-3">
                <label>Sua Turma</label>
                <div class="input-group has-icon">
                    <span class="input-group-text"><i class="bi bi-people"></i></span>
                    <select name="turma" class="form-select">
                        <option value="" selected disabled>Selecione sua turma</option>
                        <?php foreach($turmas as $t): ?>
                            <option value="<?= $t['turma'] ?>"><?= $t['turma'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div id="field-senha" class="mb-3">
                <label>Código de Validação (Senha)</label>
                <div class="input-group has-icon">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="codigo_validacao" class="form-control" placeholder="••••••">
                </div>
            </div>

            <button type="submit" class="btn-login">
                Entrar no Sistema <i class="bi bi-arrow-right-short"></i>
            </button>
        </form>
    </div>
</div>

<script>
function toggleFields() {
    const tipo = document.getElementById('tipoSelect').value;
    const fieldSenha = document.getElementById('field-senha');
    const fieldTurma = document.getElementById('field-turma');
    const inputSenha = fieldSenha.querySelector('input');
    const inputTurma = fieldTurma.querySelector('select');

    if (tipo === 'professor') {
        fieldSenha.style.display = 'block';
        fieldTurma.style.display = 'none';
        inputSenha.required = true;
        inputTurma.required = false;
    } else if (tipo === 'aluno') {
        fieldSenha.style.display = 'none';
        fieldTurma.style.display = 'block';
        inputSenha.required = false;
        inputTurma.required = true;
    }
}
</script>

</body>
</html>