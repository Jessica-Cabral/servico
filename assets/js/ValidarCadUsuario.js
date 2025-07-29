document.addEventListener("DOMContentLoaded", function () {
    const cpfCnpjInput = document.getElementById("cpfCnpj");
    const erroCpfCnpj = document.getElementById("errocpfCnpj");

    // Aplica a máscara conforme o valor digitado
    function aplicarMascaraCpfCnpj(value) {
        value = value.replace(/\D/g, ""); // Remove tudo que não é número

        if (value.length <= 11) {
            // Máscara CPF: 999.999.999-99
            value = value.replace(/(\d{3})(\d)/, "$1.$2");
            value = value.replace(/(\d{3})(\d)/, "$1.$2");
            value = value.replace(/(\d{3})(\d{1,2})/, "$1-$2");
        } else {
            // Máscara CNPJ: 99.999.999/9999-99
            value = value.replace(/(\d{2})(\d)/, "$1.$2");
            value = value.replace(/(\d{3})(\d)/, "$1.$2");
            value = value.replace(/(\d{3})(\d)/, "$1/$2");
            value = value.replace(/(\d{4})(\d{1,2})/, "$1-$2");
        }

        return value;
    }

    // Validação básica de CPF
    function validarCpf(cpf) {
        cpf = cpf.replace(/\D/g, ""); // Remove não numéricos
        if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) return false;

        let soma = 0, resto;

        for (let i = 1; i <= 9; i++) soma += parseInt(cpf.substring(i - 1, i)) * (11 - i);
        resto = (soma * 10) % 11;
        if ((resto === 10) || (resto === 11)) resto = 0;
        if (resto !== parseInt(cpf.substring(9, 10))) return false;

        soma = 0;
        for (let i = 1; i <= 10; i++) soma += parseInt(cpf.substring(i - 1, i)) * (12 - i);
        resto = (soma * 10) % 11;
        if ((resto === 10) || (resto === 11)) resto = 0;
        return resto === parseInt(cpf.substring(10, 11));
    }

    // Validação básica de CNPJ
    function validarCnpj(cnpj) {
        cnpj = cnpj.replace(/\D/g, "");
        if (cnpj.length !== 14) return false;

        let tamanho = cnpj.length - 2,
            numeros = cnpj.substring(0, tamanho),
            digitos = cnpj.substring(tamanho),
            soma = 0,
            pos = tamanho - 7;

        for (let i = tamanho; i >= 1; i--) {
            soma += numeros.charAt(tamanho - i) * pos--;
            if (pos < 2) pos = 9;
        }

        let resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(0)) return false;

        tamanho = tamanho + 1;
        numeros = cnpj.substring(0, tamanho);
        soma = 0;
        pos = tamanho - 7;

        for (let i = tamanho; i >= 1; i--) {
            soma += numeros.charAt(tamanho - i) * pos--;
            if (pos < 2) pos = 9;
        }

        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        return resultado == digitos.charAt(1);
    }

    // Exibir erro no span
    function mostrarErro(mensagem) {
        erroCpfCnpj.style.display = "block";
        erroCpfCnpj.textContent = mensagem;
    }

    function limparErro() {
        erroCpfCnpj.style.display = "none";
        erroCpfCnpj.textContent = "";
    }

    // Validação ao sair do campo
    cpfCnpjInput.addEventListener("blur", function () {
        const valor = cpfCnpjInput.value.replace(/\D/g, "");
        limparErro();

        if (valor.length === 0) {
            mostrarErro("O campo CPF/CNPJ é obrigatório.");
            return;
        }

        if (valor.length === 11) {
            if (!validarCpf(valor)) {
                mostrarErro("CPF inválido! Verifique os números digitados.");
                cpfCnpjInput.value = "";
            }
        } else if (valor.length === 14) {
            if (!validarCnpj(valor)) {
                mostrarErro("CNPJ inválido! Verifique os números digitados.");
                cpfCnpjInput.value = "";
            }
        } else {
            mostrarErro("CPF ou CNPJ incompleto. Verifique a quantidade de dígitos.");
            cpfCnpjInput.value = "";
        }
    });

    // Evento para aplicar máscara em tempo real
    cpfCnpjInput.addEventListener("input", function () {
        cpfCnpjInput.value = aplicarMascaraCpfCnpj(cpfCnpjInput.value);
        limparErro();
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const telefoneInput = document.getElementById("telefone");

    telefoneInput.addEventListener("input", function () {
        let valor = telefoneInput.value.replace(/\D/g, ""); // Remove tudo que não for número

        // Limita a 11 dígitos (ex: 88992992708)
        valor = valor.substring(0, 11);

        if (valor.length <= 10) {
            // Fixo: (88) 3222-2708
            valor = valor.replace(/^(\d{2})(\d{4})(\d{0,4})/, "($1) $2-$3");
        } else {
            // Celular: (88) 9 9299-2708
            valor = valor.replace(/^(\d{2})(\d{1})(\d{4})(\d{0,4})/, "($1) $2 $3-$4");
        }

        telefoneInput.value = valor;
    });
});

$('#termo').click(() => {
    const div = $('<div>').attr('style',`
        position: absolute;
        width: 65%;
        height: 420px;
        border-radius: 10px;
        background: #fff; 

        color: #3d3d3d;

        -webkit-box-shadow: 0px 0px 6px -1px #000000; 
        box-shadow: 0px 0px 6px -1px #000000;

        display: flex;
        flex-direction: column;
        justify-content: space-evenly;
        align-items: center;
    `)

    $(div).addClass('termo')

    $(div).append(`Eu entendo e aceitos os termos impostos pela plataforma...`)

    $(div).append(
        $('<button>Ok</button>').click(() => {
            div.hide()
        })
    )
    
    $('body').append(div)
})