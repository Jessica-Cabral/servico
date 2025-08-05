<?php
require_once __DIR__ . '/Prestador.class.php';
require_once __DIR__ . '/Servico.class.php';
require_once __DIR__ . '/Proposta.class.php';

class PrestadorController
{
    public function dashboard()
    {
        session_start();
        if (empty($_SESSION['prestador_id'])) {
            header('Location: ../../Login.php');
            exit();
        }

        $prestador = new Prestador();
        $servico = new Servico();
        $proposta = new Proposta();

        $prestador_id = $_SESSION['prestador_id'];
        $prestador_nome = $_SESSION['prestador_nome'] ?? 'Prestador';
        $prestador_dados = $prestador->getById($prestador_id);
        $prestador_email = $prestador_dados['email'] ?? '';
        $prestador_telefone = $prestador_dados['telefone'] ?? '';
        $stats = $prestador->getStats($prestador_id);
        $servicos_disponiveis = $servico->getDisponiveis(5);
        $minhas_propostas = $proposta->getByPrestador($prestador_id, 4);
        $grafico_dados = $prestador->getGraficoDados($prestador_id);

        include __DIR__ . '/../view/prestador/prestadorDashboard.php';
    }
}
// ...adicione outros métodos conforme necessidade...
