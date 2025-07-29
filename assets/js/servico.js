 // Função para ver detalhes
 function verDetalhes(servico, valor) {
    alert(`Detalhes do serviço:\n\nServiço: ${servico}\nValor: ${valor}\nStatus: Concluído`);
}

// Função para filtrar por data
function filtrarFaturamento() {
    const dataInicio = document.getElementById('data-inicio').value;
    const dataFim = document.getElementById('data-fim').value;

    const linhas = document.querySelectorAll('#tabela-faturamento tbody tr');

    linhas.forEach(linha => {
        const data = linha.cells[0].innerText;

        if (dataInicio && data < dataInicio || dataFim && data > dataFim) {
            linha.style.display = 'none';
        } else {
            linha.style.display = '';
        }
    });
}

// Função para exportar a tabela como CSV
function exportarRelatorio() {
    const tabela = document.getElementById('tabela-faturamento');
    const linhas = tabela.rows;

    let csvContent = "data:text/csv;charset=utf-8,";
    csvContent += "Data,Serviço,Valor\n"; // Cabeçalho

    for (let i = 1; i < linhas.length; i++) {
        const data = linhas[i].cells[0].innerText;
        const servico = linhas[i].cells[1].innerText;
        const valor = linhas[i].cells[2].innerText;

        // Só adiciona linhas visíveis (filtro ativo)
        if (linhas[i].style.display !== 'none') {
            csvContent += `${data},${servico},${valor}\n`;
        }
    }

    // Cria um link para download
    const link = document.createElement('a');
    link.href = encodeURI(csvContent);
    link.download = "relatorio_faturamento.csv";
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}