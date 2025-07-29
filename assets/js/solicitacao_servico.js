document.getElementById('form-servicos').addEventListener('submit', function(event) {
    event.preventDefault();
    
    // Captura dos valores dos campos
    const categoria = document.getElementById('categoria').value;
    const descricao = document.getElementById('descricao').value;
    const endereco = document.getElementById('endereco').value;
    const data = document.getElementById('data').value;
    const hora = document.getElementById('hora').value;
    const orcamento = document.getElementById('orcamento').value;
    const imagens = document.getElementById('imagens').files;

    // Validação obrigatória
    if (!categoria || !descricao || !endereco || !data || !hora) {
        alert("Por favor, preencha todos os campos obrigatórios.");
        return;
    }

    // Validação de upload de imagens
    if (imagens.length > 3) {
        alert("Você pode enviar no máximo 3 imagens.");
        return;
    }

    // Simulação de envio e notificações
    alert("Solicitação cadastrada com sucesso! Prestadores serão notificados.");
});

document.getElementById('btn-localizacao').addEventListener('click', function() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(position => {
            const { latitude, longitude } = position.coords;
            alert(`Localização Capturada: Latitude ${latitude}, Longitude ${longitude}`);
        }, () => {
            alert("Erro ao capturar localização. Verifique as permissões do navegador.");
        });
    } else {
        alert("Geolocalização não suportada pelo navegador.");
    }
});
