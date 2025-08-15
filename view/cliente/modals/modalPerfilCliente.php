<?php
// Verificar se os dados do cliente estão definidos
if (!isset($dados_cliente) && isset($_SESSION['cliente_id'])) {
    $cliente = new Cliente();
    $dados_cliente = $cliente->getById($_SESSION['cliente_id']);
}

// Definir variáveis padrão se não existirem
$cliente_nome = $cliente_nome ?? $dados_cliente['nome'] ?? 'Cliente';
$cliente_email = $cliente_email ?? $dados_cliente['email'] ?? '';
$cliente_telefone = $cliente_telefone ?? $dados_cliente['telefone'] ?? '';
$cliente_foto = $cliente_foto ?? $dados_cliente['foto_perfil'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($cliente_nome);
?>

<div class="modal fade" id="modalPerfilCliente" tabindex="-1" aria-labelledby="modalPerfilClienteLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPerfilClienteLabel">
                    <i class="fas fa-user-circle me-2"></i>Meu Perfil
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarPerfil">
                    <div class="mb-3 text-center">
                        <img src="<?php echo htmlspecialchars($cliente_foto ?? 'https://ui-avatars.com/api/?name=' . urlencode($cliente_nome ?? 'Cliente')); ?>" 
                             alt="Foto de Perfil" id="previewFotoPerfil" class="rounded-circle mb-2" width="100" height="100" style="object-fit:cover;">
                        <input type="file" class="form-control mt-2" id="imagem" name="imagem" accept="image/*" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($cliente_nome ?? ''); ?>" required readonly>
                    </div>
                    <div class="mb-3">
                        <label for="perfil-email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="perfil-email" name="email" value="<?php echo htmlspecialchars($cliente_email ?? ''); ?>" required readonly>
                    </div>
                    <div class="mb-3">
                        <label for="telefone" class="form-label">Telefone</label>
                        <input type="text" class="form-control" id="telefone" name="telefone" value="<?php echo htmlspecialchars($cliente_telefone ?? ''); ?>" required readonly>
                    </div>
                    <div class="mb-3">
                        <label for="cep" class="form-label">CEP</label>
                        <input type="text" class="form-control" id="cep" name="cep" value="<?php echo htmlspecialchars($dados_cliente['cep'] ?? ''); ?>" maxlength="9" required readonly>
                    </div>
                    <div class="mb-3">
                        <label for="endereco" class="form-label">Endereço</label>
                        <input type="text" class="form-control" id="endereco" name="endereco" value="<?php echo htmlspecialchars($dados_cliente['logradouro'] ?? $dados_cliente['endereco'] ?? ''); ?>" required readonly>
                    </div>
                    <div class="mb-3">
                        <label for="numero" class="form-label">Número</label>
                        <input type="text" class="form-control" id="numero" name="numero" value="<?php echo htmlspecialchars($dados_cliente['numero'] ?? ''); ?>" required readonly>
                    </div>
                    <div class="mb-3">
                        <label for="bairro" class="form-label">Bairro</label>
                        <input type="text" class="form-control" id="bairro" name="bairro" value="<?php echo htmlspecialchars($dados_cliente['bairro'] ?? ''); ?>" required readonly>
                    </div>
                    <div class="mb-3">
                        <label for="cidade" class="form-label">Cidade</label>
                        <input type="text" class="form-control" id="cidade" name="cidade" value="<?php echo htmlspecialchars($dados_cliente['cidade'] ?? ''); ?>" required readonly>
                    </div>
                    <div class="mb-3">
                        <label for="uf" class="form-label">UF</label>
                        <input type="text" class="form-control" id="uf" name="uf" value="<?php echo htmlspecialchars($dados_cliente['estado'] ?? $dados_cliente['uf'] ?? ''); ?>" maxlength="2" required readonly>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label for="senha" class="form-label">Nova Senha</label>
                        <input type="password" class="form-control" id="senha" name="senha" placeholder="Deixe em branco para não alterar" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="senha_confirmar" class="form-label">Confirmar Nova Senha</label>
                        <input type="password" class="form-control" id="senha_confirmar" name="senha_confirmar" placeholder="Repita a nova senha" disabled>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-warning" id="btnEditarDados">Editar Dados</button>
                <button type="submit" class="btn btn-primary" id="btnSalvarAlteracoes" form="formEditarPerfil" disabled>Salvar Alterações</button>
            </div>
        </div>
    </div>
</div>
