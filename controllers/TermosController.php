<?php
/**
 * Controller para a página de Termos de Serviço
 */
class TermosController {
    
    /**
     * Exibe a página de Termos de Serviço
     */
    public function index() {
        ob_start();
        require __DIR__ . '/../view/public/Termos.php'; // apenas conteúdo principal
        $mainContent = ob_get_clean();
        $pageTitle = 'Termos de Uso';
        $extraScripts = '';
        require __DIR__ . '/../view/public/main.php'; // layout principal com menu, footer e CSS
    }
    
    /**
     * Retorna os termos em formato JSON para requisições AJAX
     */
    public function getJson() {
        header('Content-Type: application/json');
        
        $termos = [
            'titulo' => 'Termos de Uso do Chama Serviço',
            'data_atualizacao' => '01/06/2024',
            'versao' => '1.0',
            'secoes' => [
                [
                    'titulo' => 'Aceitação dos Termos',
                    'conteudo' => 'Ao acessar e utilizar a plataforma Chama Serviço, você concorda com estes termos de uso.'
                ],
                [
                    'titulo' => 'Cadastro e Conta',
                    'conteudo' => 'Para utilizar nossos serviços, é necessário realizar um cadastro com informações verdadeiras.'
                ],
                [
                    'titulo' => 'Uso do Serviço',
                    'conteudo' => 'A plataforma serve como intermediária entre clientes e prestadores de serviços.'
                ],
                [
                    'titulo' => 'Responsabilidades',
                    'conteudo' => 'O Chama Serviço não se responsabiliza pela qualidade dos serviços prestados pelos profissionais cadastrados.'
                ]
            ]
        ];
        
        echo json_encode($termos);
        exit;
    }
}
