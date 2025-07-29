<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Senac TDS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1976d2 0%, #2196f3 100%);
            min-height: 100vh;
        }
        .login-container {
            max-width: 400px;
            margin: 5% auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(25, 118, 210, 0.2);
            padding: 2.5rem 2rem;
        }
        .form-control:focus {
            border-color: #1976d2;
            box-shadow: 0 0 0 0.2rem rgba(25, 118, 210, 0.15);
        }
        .show-password {
            cursor: pointer;
        }
        .logo {
            font-size: 2.5rem;
            color: #1976d2;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="text-center mb-4">
            <i class="bi bi-person-circle logo"></i>
            <h2 class="mt-2" style="color:#1976d2;">Login</h2>
        </div>
        <div class="text-center mb-3">
                            <i class="bi bi-tools fa-2x text-primary mb-2"></i>
                            <h3 class="fw-bold mb-1" style="font-size:1.5rem;">Chama Serviço</h3>
                            <p class="text-muted mb-0" style="font-size:0.98rem;">Acesse sua conta para usar o sistema</p>
                        </div>
        <form>
            <div class="mb-3">
                <label for="username" class="form-label">Usuário</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-person"></i></span>
                    <input type="text" class="form-control" id="username" placeholder="Digite seu usuário" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-lock"></i></span>
                    <input type="password" class="form-control" id="password" placeholder="Digite sua senha" required>
                    <span class="input-group-text bg-white show-password" onclick="togglePassword()">
                        <i class="bi bi-eye" id="toggleIcon"></i>
                    </span>
                </div>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">Entrar</button>
            </div>
            <div class="text-center mt-3">
                <a href="#" style="color:#1976d2;">Esqueceu a senha?</a>
            </div>
        </form>
    </div>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            }
        }
    </script>
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>