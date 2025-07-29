      // Função para mostrar detalhes (exemplo básico)
      function verDetalhes(servico) {
        alert("Detalhes do serviço: " + servico + "\n\nEste serviço foi concluído com sucesso.");
    }

    // Função para exportar tabela como relatório CSV
    function exportarRelatorio() {
        let tabela = document.getElementById('tabela-servicos');
        let linhas = tabela.rows;

        let csvContent = "data:text/csv;charset=utf-8,";
        csvContent += "Data,Serviço,Status\n"; // Cabeçalho

        for (let i = 1; i < linhas.length; i++) {
            let data = linhas[i].cells[0].innerText;
            let servico = linhas[i].cells[1].innerText;
            let status = linhas[i].cells[2].innerText;

            csvContent += `${data},${servico},${status}\n`;
        }

        // Criação do link para download
        let link = document.createElement("a");
        link.href = encodeURI(csvContent);
        link.download = "relatorio_servicos.csv";
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }