       // Adicionar funcionalidade para o botão de remover
       document.querySelectorAll('.remover').forEach(button => {
        button.addEventListener('click', function() {
            this.parentElement.remove();
        });
    });

    // Capturar o formulário de adicionar cartão
    document.getElementById('formCartao').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const nome = document.getElementById('nomeCartao').value;
        const numero = document.getElementById('numeroCartao').value;
        const finalCartao = numero.slice(-4);

        const lista = document.getElementById('listaCartoes');
        const novoCartao = document.createElement('li');
        novoCartao.innerHTML = `**** **** **** ${finalCartao} <button class="remover">Remover</button>`;
        lista.appendChild(novoCartao);

        // Resetar formulário
        this.reset();

        // Adicionar evento ao novo botão
        novoCartao.querySelector('.remover').addEventListener('click', function() {
            novoCartao.remove();
        });
    });