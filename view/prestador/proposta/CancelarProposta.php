<?php
// Recebe o id da proposta via GET
$id = $_GET['id'] ?? null;
if (!$id) {
    echo '<div class="alert alert-danger">ID da proposta não informado.</div>';
    exit;
}
?>
<div class="text-center">
    <p class="mb-4 fs-5">Tem certeza que deseja cancelar esta proposta?</p>
    <div class="mb-3">
        <textarea class="form-control" id="motivoCancelamento" placeholder="Motivo do cancelamento (opcional)"></textarea>
    </div>
    <button class="btn btn-danger" id="btnConfirmarCancelamento" data-id="<?php echo $id; ?>">Confirmar Cancelamento</button>
    <button class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
    <div id="cancelarStatus" class="mt-3"></div>
</div>
<script>
    // Usa delegação de evento para funcionar com conteúdo dinâmico
    document.addEventListener('click', function(e) {
        if (e.target && e.target.id === 'btnConfirmarCancelamento') {
            const btn = e.target;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Cancelando...';
            const id = btn.getAttribute('data-id');
            const motivo = document.getElementById('motivoCancelamento').value;
            fetch('cancelar-proposta-acao.php?id=' + id, {
                    method: 'POST',
                    body: new URLSearchParams({
                        motivo: motivo
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.sucesso) {
                        document.getElementById('cancelarStatus').innerHTML = '<div class="alert alert-success">Proposta cancelada com sucesso!</div>';
                        setTimeout(() => {
                            location.reload();
                        }, 1200);
                    } else {
                        document.getElementById('cancelarStatus').innerHTML = '<div class="alert alert-danger">Erro ao cancelar proposta.</div>';
                        btn.disabled = false;
                        btn.innerHTML = 'Confirmar Cancelamento';
                    }
                })
                .catch(() => {
                    document.getElementById('cancelarStatus').innerHTML = '<div class="alert alert-danger">Erro de comunicação.</div>';
                    btn.disabled = false;
                    btn.innerHTML = 'Confirmar Cancelamento';
                });
        }
    });
</script>