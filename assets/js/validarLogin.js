// assets/js/validarLogin.js

document.addEventListener('DOMContentLoaded', function () {
    // Seleciona o formulário
    const form = document.querySelector('form');
    // Seleciona os campos de email e senha
    const emailInput = document.getElementById('email');
    const senhaInput = document.getElementById('senha');
    // Seleciona o botão de login
    const loginButton = document.querySelector('button[name="Login"]');
    // Seleciona o elemento de mensagem de erro
    const errorMessage = document.getElementById('errorMessage');

    // Função para validar o formulário
    function validarLogin(event) {
        // Previne o envio padrão do formulário para validação
        event.preventDefault();

        const email = emailInput.value.trim();
        const senha = senhaInput.value.trim();

        // Verifica se os campos estão vazios
        if (email === '' || senha === '') {
            errorMessage.textContent = 'Por favor, digite seu email e senha.';
            errorMessage.style.display = 'block';
            return false; // Impede o envio do formulário
        } else {
            errorMessage.textContent = ''; // Limpa a mensagem de erro
            errorMessage.style.display = 'none';
            form.submit(); // Envia o formulário se os campos estiverem preenchidos
        }
    }

    // Adiciona o evento de clique ao botão "Acessar"
    loginButton.addEventListener('click', validarLogin);

    // Limpa a mensagem de erro ao digitar nos campos
    emailInput.addEventListener('input', function () {
        if (errorMessage.textContent !== '') {
            errorMessage.textContent = '';
            errorMessage.style.display = 'none';
        }
    });
    senhaInput.addEventListener('input', function () {
        if (errorMessage.textContent !== '') {
            errorMessage.textContent = '';
            errorMessage.style.display = 'none';
        }
    });
});