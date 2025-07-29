    // Função que permite alterar os dados
    function alterarDados() {
        const novoNome = prompt("Digite o Nome Completo:", document.getElementById('nome').innerText);
        const novoCPF = prompt("Digite o CPF/CNPJ:", document.getElementById('cpf').innerText);
        const novaData = prompt("Digite a Data de Nascimento:", document.getElementById('nascimento').innerText);
        const novoTelefone = prompt("Digite o Telefone:", document.getElementById('telefone').innerText);
        const novoEmail = prompt("Digite o E-mail:", document.getElementById('email').innerText);
        const novoEndereco = prompt("Digite o Endereço:", document.getElementById('endereco').innerText);

        // Atualiza os campos
        if (novoNome) document.getElementById('nome').innerText = novoNome;
        if (novoCPF) document.getElementById('cpf').innerText = novoCPF;
        if (novaData) document.getElementById('nascimento').innerText = novaData;
        if (novoTelefone) document.getElementById('telefone').innerText = novoTelefone;
        if (novoEmail) document.getElementById('email').innerText = novoEmail;
        if (novoEndereco) document.getElementById('endereco').innerText = novoEndereco;

        alert("Os dados foram atualizados com sucesso!");
    }