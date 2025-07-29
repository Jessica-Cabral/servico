// assets/js/validarRecuperar.js

document.addEventListener('DOMContentLoaded', function () {
    // Seleciona o formulário
    const form = document.getElementById('recuperarForm');
    // Seleciona os campos de email e confirmação de email
    const emailInput = document.getElementById('email');
    const cmailInput = document.getElementById('cmail');
    // Seleciona o botão de envio
    const enviarBtn = document.getElementById('enviarBtn');
    // Seleciona o elemento de mensagem de erro
    const errorMessage = document.getElementById('errorMessage');

    // Função para validar o formulário
    function validarRecuperar(event) {
        // Previne o envio padrão do formulário para validação
        event.preventDefault();

        const email = emailInput.value.trim();
        const cmail = cmailInput.value.trim();

        // Limpa a mensagem de erro anterior
        errorMessage.textContent = '';
        errorMessage.style.display = 'none';

        // Valida se os campos estão vazios
        if (email === '' || cmail === '') {
            errorMessage.textContent = 'Por favor, preencha ambos os campos de email.';
            errorMessage.style.display = 'block';
            return false;
        }

        // Valida se os emails são iguais
        if (email !== cmail) {
            errorMessage.textContent = 'Os emails não coincidem. Por favor, verifique.';
            errorMessage.style.display = 'block';
            return false;
        }

        // Valida o formato do email (básico)
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            errorMessage.textContent = 'Por favor, insira um email válido.';
            errorMessage.style.display = 'block';
            return false;
        }

        // Se todas as validações passarem, envia o formulário
        form.submit();
    }

    // Adiciona o evento de clique ao botão "Enviar"
    enviarBtn.addEventListener('click', validarRecuperar);

    // Limpa a mensagem de erro ao digitar nos campos
    emailInput.addEventListener('input', function () {
        if (errorMessage.textContent !== '') {
            errorMessage.textContent = '';
            errorMessage.style.display = 'none';
        }
    });

    cmailInput.addEventListener('input', function () {
        if (errorMessage.textContent !== '') {
            errorMessage.textContent = '';
            errorMessage.style.display = 'none';
        }
    });
});