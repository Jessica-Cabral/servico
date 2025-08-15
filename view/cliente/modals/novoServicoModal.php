<?php
// Verificar se as variáveis necessárias estão definidas
if (!isset($tipos_servico)) {
    $servico = new Servico();
    $tipos_servico = $servico->getTiposServico();
}

if (!isset($enderecos)) {
    $enderecos = $servico->getEnderecosPorCliente($_SESSION['cliente_id']);
}
?>

<div class="modal fade" id="novoServicoModal" tabindex="-1" aria-labelledby="novoServicoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title d-flex align-items-center" id="novoServicoModalLabel">
                    <i class="fas fa-plus-circle me-2"></i> Solicitar Novo Serviço
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <div id="alertContainer"></div>
                <form id="novoServicoForm">
                    <!-- Tipo de Serviço e Urgência -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tipo_servico_id" class="form-label">
                                <i class="fas fa-tools me-1 text-primary"></i> Tipo de Serviço *
                            </label>
                            <select class="form-select" id="tipo_servico_id" name="tipo_servico_id" required>
                                <option value="">Selecione o tipo de serviço</option>
                                <?php if (!empty($tipos_servico)): ?>
                                    <?php foreach ($tipos_servico as $tipo): ?>
                                        <option value="<?php echo $tipo['id']; ?>">
                                            <?php echo htmlspecialchars($tipo['nome']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="">Nenhum tipo disponível</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="urgencia" class="form-label">
                                <i class="fas fa-exclamation-circle me-1 text-warning"></i> Urgência *
                            </label>
                            <select class="form-select" id="urgencia" name="urgencia" required>
                                <option value="baixa">Baixa</option>
                                <option value="media" selected>Média</option>
                                <option value="alta">Alta</option>
                            </select>
                        </div>
                    </div>

                    <!-- Título e Descrição -->
                    <div class="mb-3">
                        <label for="titulo" class="form-label">
                            <i class="fas fa-heading me-1 text-primary"></i> Título do Serviço *
                        </label>
                        <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Ex: Instalação de tomadas na sala" required>
                    </div>
                    <div class="mb-3">
                        <label for="descricao" class="form-label">
                            <i class="fas fa-align-left me-1 text-primary"></i> Descrição Detalhada *
                        </label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="4" placeholder="Descreva detalhadamente o serviço que precisa..." required></textarea>
                    </div>

                    <!-- Data e Horário -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="data_desejada" class="form-label">
                                <i class="fas fa-calendar-alt me-1 text-primary"></i> Data Desejada
                            </label>
                            <input type="date" class="form-control" id="data_desejada" name="data_desejada">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="horario_desejado" class="form-label">
                                <i class="fas fa-clock me-1 text-primary"></i> Horário Desejado
                            </label>
                            <select class="form-select" id="horario_desejado" name="horario_desejado">
                                <option value="">Selecione o horário</option>
                                <option value="08:00">08:00 - Manhã</option>
                                <option value="09:00">09:00 - Manhã</option>
                                <option value="10:00">10:00 - Manhã</option>
                                <option value="11:00">11:00 - Manhã</option>
                                <option value="13:00">13:00 - Tarde</option>
                                <option value="14:00">14:00 - Tarde</option>
                                <option value="15:00">15:00 - Tarde</option>
                                <option value="16:00">16:00 - Tarde</option>
                                <option value="17:00">17:00 - Tarde</option>
                                <option value="18:00">18:00 - Tarde</option>
                                <option value="19:00">19:00 - Noite</option>
                                <option value="20:00">20:00 - Noite</option>
                            </select>
                        </div>
                    </div>

                    <!-- Endereço e Orçamento -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="endereco_opcao" class="form-label">
                                <i class="fas fa-map-marker-alt me-1 text-primary"></i> Opção de Endereço *
                            </label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="endereco_opcao" id="endereco_existente" value="existente" checked>
                                <label class="form-check-label" for="endereco_existente">Usar endereço cadastrado</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="endereco_opcao" id="endereco_novo" value="novo">
                                <label class="form-check-label" for="endereco_novo">Cadastrar novo endereço</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="orcamento_estimado" class="form-label">
                                <i class="fas fa-wallet me-1 text-primary"></i> Orçamento Estimado
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="number" class="form-control" id="orcamento_estimado" name="orcamento_estimado" step="0.01" placeholder="0.00">
                            </div>
                        </div>
                    </div>

                    <!-- Endereço Existente -->
                    <div id="endereco_existente_group" class="mb-3">
                        <label for="endereco_id" class="form-label">
                            <i class="fas fa-map-pin me-1 text-primary"></i> Selecionar Endereço *
                        </label>
                        <select class="form-select" id="endereco_id" name="endereco_id">
                            <option value="">Selecione o endereço</option>
                            <?php if (!empty($enderecos)): ?>
                                <?php foreach ($enderecos as $endereco): ?>
                                    <option value="<?php echo $endereco['id']; ?>">
                                        <?php echo htmlspecialchars($endereco['logradouro'] . ', ' . $endereco['numero'] . ' - ' . $endereco['bairro'] . ' - ' . $endereco['cidade'] . '/' . $endereco['estado']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="">Nenhum endereço cadastrado</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Novo Endereço -->
                    <div id="endereco_novo_group" class="endereco-fields d-none">
                        <h6 class="mb-3 text-primary">
                            <i class="fas fa-map-marker-alt me-2"></i> Novo Endereço
                        </h6>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="cep" class="form-label">CEP *</label>
                                <input type="text" class="form-control" id="cep" name="cep" placeholder="00000-000" maxlength="9">
                            </div>
                            <div class="col-md-8 mb-3">
                                <label for="logradouro" class="form-label">Logradouro *</label>
                                <input type="text" class="form-control" id="logradouro" name="logradouro" placeholder="Rua, Avenida, etc.">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="numero" class="form-label">Número *</label>
                                <input type="text" class="form-control" id="numero" name="numero" placeholder="123">
                            </div>
                            <div class="col-md-5 mb-3">
                                <label for="complemento" class="form-label">Complemento</label>
                                <input type="text" class="form-control" id="complemento" name="complemento" placeholder="Apto, Sala, etc.">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="bairro" class="form-label">Bairro *</label>
                                <input type="text" class="form-control" id="bairro" name="bairro" placeholder="Nome do bairro">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="cidade" class="form-label">Cidade *</label>
                                <input type="text" class="form-control" id="cidade" name="cidade" placeholder="Nome da cidade">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="estado" class="form-label">Estado *</label>
                                <select class="form-select" id="estado" name="estado">
                                    <option value="">Selecione</option>
                                    <option value="AC">Acre</option>
                                    <option value="AL">Alagoas</option>
                                    <!-- ...outros estados... -->
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Upload de Fotos -->
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="fas fa-camera me-1 text-primary"></i> Fotos do Serviço (opcional)
                        </label>
                        <div class="upload-area border border-primary rounded p-3 text-center" id="uploadArea">
                            <i class="fas fa-upload fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-2">Clique aqui ou arraste fotos para ajudar no orçamento</p>
                            <small class="text-muted">Máximo 5 fotos - JPG, PNG (até 2MB cada)</small>
                            <input type="file" id="fotosServico" name="fotos_servico[]" multiple accept="image/*" class="d-none">
                        </div>
                        <div id="previewContainer" class="mt-3"></div>
                    </div>

                    <!-- Botões -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i> Cancelar
                            </button>
                        </div>
                        <div class="col-md-6 mb-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-paper-plane me-1"></i> Solicitar Serviço
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
