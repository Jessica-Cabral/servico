<?php
require_once __DIR__ . '/../../models/PropostaClass.php';

if (!isset($_GET['id'])) {
    echo '<div class="alert alert-danger">ID da proposta não informado.</div>';
    exit;
}
$id = (int)$_GET['id'];
$proposta = new Proposta();
$dados = $proposta->getById($id);

if (!$dados) {
    echo '<div class="alert alert-danger">Proposta não encontrada.</div>';
    exit;
}
?>
<div class="p-3">
    <h5>Editar Proposta #<?php echo $id; ?></h5>
    <form id="formEditarProposta">
        <div class="mb-3">
            <label for="valor" class="form-label">Valor</label>
            <input type="number" class="form-control" id="valor" name="valor" min="0" step="0.01" value="<?php echo htmlspecialchars($dados['valor']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="prazo" class="form-label">Prazo (dias)</label>
            <input type="number" class="form-control" id="prazo" name="prazo" min="1" value="<?php echo htmlspecialchars($dados['prazo_execucao']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <textarea class="form-control" id="descricao" name="descricao" rows="3" required><?php echo htmlspecialchars($dados['descricao']); ?></textarea>
        </div>
        <div id="editarPropostaStatus"></div>
        <button type="submit" class="btn btn-primary">Salvar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
    </form>
</div>
<script>
    document.getElementById('formEditarProposta').addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = this.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Salvando...';
        document.getElementById('editarPropostaStatus').innerHTML = '';

        fetch('editar-proposta-acao.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    id: <?php echo $id; ?>,
                    valor: document.getElementById('valor').value,
                    prazo: document.getElementById('prazo').value,
                    descricao: document.getElementById('descricao').value
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.sucesso) {
                    document.getElementById('editarPropostaStatus').innerHTML = '<div class="alert alert-success">Proposta atualizada com sucesso!</div>';
                    setTimeout(() => {
                        window.location.reload();
                    }, 1200);
                } else {
                    document.getElementById('editarPropostaStatus').innerHTML = '<div class="alert alert-danger">' + (data.erro || 'Erro ao atualizar proposta.') + '</div>';
                    btn.disabled = false;
                    btn.innerHTML = 'Salvar';
                }
            })
            .catch(() => {
                document.getElementById('editarPropostaStatus').innerHTML = '<div class="alert alert-danger">Erro de comunicação.</div>';
                btn.disabled = false;
                btn.innerHTML = 'Salvar';
            });
    });
</script>