document.getElementById('toggleSenha').addEventListener('click', function () {
    const senhaInput = document.getElementById('senha');
    const iconSenha = document.getElementById('iconSenha');
    if (senhaInput.type === 'password') {
        senhaInput.type = 'text';
        iconSenha.classList.remove('bi-eye');
        iconSenha.classList.add('bi-eye-slash');
    } else {
        senhaInput.type = 'password';
        iconSenha.classList.remove('bi-eye-slash');
        iconSenha.classList.add('bi-eye');
    }
});