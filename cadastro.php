<?php
$erro = $_GET['erro'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Usuário</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8fafc; font-family: Arial, sans-serif; }
        .cadastro-box { max-width: 420px; margin: 40px auto; background: #fff; border-radius: 12px; box-shadow: 0 4px 24px rgba(30,40,60,0.07); padding: 32px; }
        .form-label { font-weight: 600; }
    </style>
</head>
<body>
<div class="cadastro-box">
    <h2 class="mb-4 text-center">Cadastro</h2>
    <?php if ($erro): ?>
        <div class="alert alert-danger text-center"><?php echo htmlspecialchars($erro); ?></div>
    <?php endif; ?>
    <form method="post" id="cadastroForm" action="controllers/ClienteController.class.php?acao=cadastrar" autocomplete="off">
        <!-- Adicione este campo oculto para garantir que todos os campos sejam enviados -->
        <input type="hidden" name="enviar_todos_campos" value="1">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome completo</label>
            <input type="text" class="form-control" id="nome" name="nome" required maxlength="80" placeholder="Digite seu nome completo">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control" id="email" name="email" required maxlength="80" placeholder="Digite seu e-mail">
        </div>
        <div class="mb-3">
            <label for="senha" class="form-label">Senha</label>
            <input type="password" class="form-control" id="senha" name="senha" required minlength="6" maxlength="32" placeholder="Crie uma senha">
        </div>
        <div class="mb-3">
            <label for="cpf" class="form-label">CPF</label>
            <input type="text" class="form-control" id="cpf" name="cpf" required maxlength="14" placeholder="Digite seu CPF">
        </div>
        <div class="mb-3">
            <label for="telefone" class="form-label">Telefone</label>
            <input type="text" class="form-control" id="telefone" name="telefone" maxlength="15" placeholder="(99) 99999-9999" required>
        </div>
        <div class="mb-3">
            <label for="data_nascimento" class="form-label">Data de nascimento</label>
            <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" required>
        </div>
        <div class="mb-3">
            <label for="tipo" class="form-label">Tipo de perfil</label>
            <select class="form-select" id="tipo" name="tipo" required>
                <option value="">Selecione...</option>
                <option value="cliente">Cliente</option>
                <option value="prestador">Prestador</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
    </form>
    <div class="text-center mt-3">
        <a href="Login.php">Já tem conta? Entrar</a>
    </div>
</div>
<script>
function validarCPF(cpf) {
    cpf = cpf.replace(/[^\d]+/g,'');
    if (cpf.length !== 11 || /^(\d)\1+$/.test(cpf)) return false;
    let soma = 0, resto;
    for (let i=1; i<=9; i++) soma += parseInt(cpf.substring(i-1,i))*(11-i);
    resto = (soma*10)%11;
    if ((resto==10)||(resto==11)) resto=0;
    if (resto != parseInt(cpf.substring(9,10))) return false;
    soma = 0;
    for (let i=1; i<=10; i++) soma += parseInt(cpf.substring(i-1,i))*(12-i);
    resto = (soma*10)%11;
    if ((resto==10)||(resto==11)) resto=0;
    if (resto != parseInt(cpf.substring(10,11))) return false;
    return true;
}
function idadeMinima(data) {
    const nasc = new Date(data);
    const hoje = new Date();
    let idade = hoje.getFullYear() - nasc.getFullYear();
    const m = hoje.getMonth() - nasc.getMonth();
    if (m < 0 || (m === 0 && hoje.getDate() < nasc.getDate())) idade--;
    return idade >= 18;
}
document.getElementById('cadastroForm').addEventListener('submit', function(e) {
    const cpf = document.getElementById('cpf').value;
    const dataNasc = document.getElementById('data_nascimento').value;
    let msg = '';
    if (!validarCPF(cpf)) msg += 'CPF inválido.\n';
    if (!idadeMinima(dataNasc)) msg += 'Você deve ter pelo menos 18 anos.\n';
    if (msg) {
        alert(msg);
        e.preventDefault();
    }
});
</script>
</body>
</html>
