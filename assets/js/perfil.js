     // Script para adicionar novos campos de serviço
     document.getElementById('adicionar-servico').addEventListener('click', () => {
        const container = document.getElementById('servicos-container');
        const index = container.children.length + 1;
        const div = document.createElement('div');
        div.classList.add('form-group');
        div.innerHTML = `
            <label for="servico${index}">Serviço:</label>
            <input type="text" id="servico${index}" name="servicos[]" required>
            <label for="preco${index}">Preço:</label>
            <input type="number" id="preco${index}" name="precos[]" min="0" required>
        `;
        container.appendChild(div);
    });