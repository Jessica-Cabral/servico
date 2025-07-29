<!-- Modal de Editar Perfil -->
<div class="modal fade" id="profileModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user-edit me-2"></i>Editar Perfil
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="profileForm" action="{{ route('cliente.perfil.atualizar') }}" method="POST" enctype="multipart/form-data">
                  

                    <!-- Foto do Perfil -->
                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block">
                            <img id="previewImage" src="{{ asset('images/default-profile.png') }}"
                                 class="rounded-circle border" width="120" height="120" style="object-fit: cover;">
                            <button type="button" class="btn btn-primary btn-sm position-absolute bottom-0 end-0 rounded-circle"
                                    onclick="document.getElementById('foto_perfil').click()">
                                <i class="fas fa-camera"></i>
                            </button>
                        </div>
                        <input type="file" name="foto_perfil" id="foto_perfil" accept="image/*" class="d-none">
                        <p class="text-muted small mt-2">Clique no ícone para alterar a foto</p>
                    </div>

                    <!-- Informações Pessoais -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="fas fa-user text-primary me-2"></i>Informações Pessoais
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="nome" class="form-label fw-semibold">Nome Completo</label>
                                    <input type="text" class="form-control" id="nome" name="nome"
                                           value="{{ $cliente->nome ?? '' }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="telefone" class="form-label fw-semibold">Telefone</label>
                                    <input type="text" class="form-control" id="telefone" name="telefone"
                                           value="{{ $cliente->telefone ?? '' }}" required placeholder="(11) 99999-9999">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="cpf" class="form-label fw-semibold">CPF</label>
                                    <input type="text" class="form-control" id="cpf" name="cpf"
                                           value="{{ $cliente->cpf ?? '' }}" maxlength="14" placeholder="000.000.000-00">
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-semibold">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                           value="{{ $cliente->email ?? '' }}" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Alterar Senha -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="fas fa-lock text-primary me-2"></i>Alterar Senha
                            </h6>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small mb-3">Deixe em branco se não quiser alterar a senha</p>

                            <div class="mb-3">
                                <label for="senha_atual" class="form-label fw-semibold">Senha Atual</label>
                                <input type="password" class="form-control" id="senha_atual" name="senha_atual"
                                       placeholder="Digite sua senha atual">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nova_senha" class="form-label fw-semibold">Nova Senha</label>
                                    <input type="password" class="form-control" id="nova_senha" name="nova_senha"
                                           placeholder="Mínimo 6 caracteres">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="confirma_senha" class="form-label fw-semibold">Confirmar Nova Senha</label>
                                    <input type="password" class="form-control" id="confirma_senha" name="confirma_senha"
                                           placeholder="Repita a nova senha">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancelar
                </button>
                <button type="submit" form="profileForm" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>Salvar Alterações
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Perfil Profissional -->
<div class="modal fade" id="perfilModal" tabindex="-1" aria-labelledby="perfilModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="perfilModalLabel">
                    <i class="fas fa-user-edit me-2"></i> Meu Perfil
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="perfilForm" action="../../controllers/ClienteController.php?acao=atualizarPerfil" method="post" enctype="multipart/form-data" autocomplete="off">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-12 text-center">
                            <div class="position-relative d-inline-block">
                                <img id="previewFotoPerfil" src="<?php echo htmlspecialchars($dados['foto_perfil'] ?? '../../assets/img/default-user.png'); ?>"
                                     class="rounded-circle border border-3 border-primary shadow" width="120" height="120" style="object-fit: cover;">
                                <label for="imagem" class="btn btn-primary btn-sm rounded-circle position-absolute bottom-0 end-0" style="width:36px;height:36px;">
                                    <i class="fas fa-camera"></i>
                                    <input type="file" id="imagem" name="foto_perfil" accept="image/*" class="d-none">
                                </label>
                            </div>
                            <p class="text-muted small mt-2">Clique no ícone para alterar a foto</p>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nome Completo</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($dados['nome']); ?>" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">E-mail</label>
                            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($dados['email']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">CPF</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($dados['cpf']); ?>" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Data de Nascimento</label>
                            <input type="date" class="form-control" value="<?php echo htmlspecialchars($dados['dt_nascimento'] ?? ''); ?>" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Telefone <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="telefone" name="telefone" value="<?php echo htmlspecialchars($dados['telefone']); ?>" required placeholder="(11) 99999-9999">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Cidade</label>
                            <input type="text" class="form-control" name="cidade" value="<?php echo htmlspecialchars($dados['cidade'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Estado</label>
                            <input type="text" class="form-control" name="estado" value="<?php echo htmlspecialchars($dados['estado'] ?? ''); ?>">
                        </div>
                    </div>
                    <hr class="my-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nova Senha</label>
                            <input type="password" class="form-control" name="nova_senha" placeholder="Mínimo 6 caracteres">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Confirmar Nova Senha</label>
                            <input type="password" class="form-control" name="confirma_senha" placeholder="Repita a nova senha">
                        </div>
                    </div>
                    <div class="alert alert-info mt-4 small">
                        <i class="fas fa-info-circle me-2"></i>
                        CPF e Data de Nascimento não podem ser alterados. Para mudar outros dados, entre em contato com o suporte.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
document.getElementById('imagem').addEventListener('change', function(e) {
    const [file] = e.target.files;
    if (file) {
        document.getElementById('previewFotoPerfil').src = URL.createObjectURL(file);
    }
});
</script>
</script>
