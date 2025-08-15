// Habilita edição dos campos ao clicar em "Alterar Dados"
document.addEventListener('DOMContentLoaded', function() {
    const btnEditar = document.getElementById('btnEditarPerfil');
    const btnSalvar = document.getElementById('btnSalvarPerfil');
    const form = document.getElementById('formPerfil');
    const camposEditaveis = [
        'perfil-nome', 'perfil-email', 'perfil-telefone', 'foto_perfil', 'perfil-senha'
    ];
    btnEditar.addEventListener('click', function() {
        camposEditaveis.forEach(function(id) {
            const campo = document.getElementById(id);
            if (campo) campo.removeAttribute('readonly');
            if (campo && campo.type === 'file') campo.removeAttribute('disabled');
            if (campo && campo.type === 'password') campo.removeAttribute('disabled');
        });
        btnSalvar.removeAttribute('disabled');
        btnEditar.setAttribute('disabled', 'disabled');
    });

    // Preview da imagem de perfil ao selecionar novo arquivo
    const fotoInput = document.getElementById('foto_perfil');
    if (fotoInput) {
        fotoInput.addEventListener('change', function(e) {
            const [file] = fotoInput.files;
            if (file) {
                document.getElementById('imgPerfil').src = URL.createObjectURL(file);
            }
        });
    }

    // Sempre que abrir a modal, recarrega a imagem do banco (caso tenha sido alterada em outro local)
    const perfilModal = document.getElementById('perfilModal');
    perfilModal.addEventListener('show.bs.modal', function() {
        // Recarrega a imagem do banco (força reload removendo cache)
        const img = document.getElementById('imgPerfil');
        if (img) {
            const src = img.getAttribute('src').split('?')[0];
            img.setAttribute('src', src + '?t=' + new Date().getTime());
        }
    });
});

// Exibe modal de sucesso se perfil foi atualizado
document.addEventListener('DOMContentLoaded', function() {
    if (window.location.search.includes('sucesso')) {
        var sucessoModal = new bootstrap.Modal(document.getElementById('sucessoModal'));
        sucessoModal.show();
        // Remove o parâmetro 'sucesso' da URL após exibir o modal
        if (window.history.replaceState) {
            const url = new URL(window.location);
            url.searchParams.delete('sucesso');
            window.history.replaceState({}, document.title, url.pathname + url.search);
        }
    }
});

// Chart.js Script
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('performanceChart').getContext('2d');
    if (window.grafico_dados) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: window.grafico_dados.labels,
                datasets: [{
                    label: 'Trabalhos Realizados',
                    data: window.grafico_dados.dados,
                    borderColor: '#27ae60',
                    backgroundColor: 'rgba(39, 174, 96, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
});

// Adiciona/atualiza função global para abrir detalhes do serviço via modal ou fallback para página
function verDetalhes(id) {
    // usa a rota do Router: /servico/prestador/detalhes-servico
    const url = '/servico/prestador/detalhes-servico?id=' + encodeURIComponent(id);
    const modalEl = document.getElementById('acaoModal');
    if (modalEl) {
        const modalBody = modalEl.querySelector('#acaoModalConteudo');
        const modalTitle = modalEl.querySelector('#acaoModalLabel') || { innerText: 'Detalhes' };
        const bsModal = new bootstrap.Modal(modalEl, { keyboard: true });

        modalBody.innerHTML = '<div class="d-flex justify-content-center align-items-center" style="height:120px;"><div class="spinner-border text-info" role="status" aria-hidden="true"></div><span class="visually-hidden">Carregando...</span></div>';
        modalTitle.innerText = 'Detalhes do Serviço';
        bsModal.show();

        fetch(url, {
            method: 'GET',
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => {
            if (response.url && response.url.includes('/servico/home')) {
                bsModal.hide();
                window.location.href = response.url;
                throw new Error('Sessão expirada. Redirecionando.');
            }
            if (!response.ok) return response.text().then(text => { throw new Error(text || response.statusText); });
            return response.text();
        })
        .then(html => {
            try {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const selectors = ['#detalhesServico', '#detalhes-servico', '.detalhes-oportunidade', '.detalhes-servico', 'main', '#conteudo'];
                let extracted = null;
                for (const sel of selectors) {
                    const el = doc.querySelector(sel);
                    if (el) { extracted = el.innerHTML.trim(); break; }
                }
                if (!extracted) {
                    const body = doc.querySelector('body');
                    extracted = body ? body.innerHTML.trim() : html;
                }
                modalBody.innerHTML = extracted || '<div class="alert alert-warning">Conteúdo vazio.</div>';
            } catch (e) {
                modalBody.innerHTML = html || '<div class="alert alert-warning">Conteúdo vazio.</div>';
            }
        })
        .catch(err => {
            console.error('Erro ao carregar detalhes:', err);
            if (modalBody) modalBody.innerHTML = '<div class="alert alert-danger">Não foi possível carregar os detalhes. Tente novamente.</div>';
        });
    } else {
        window.location.href = url;
    }
}
window.verDetalhes = verDetalhes;


// ...existing code...
