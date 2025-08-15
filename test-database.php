<?php
require_once 'config/database.php';

echo "<h2>🔧 Teste de Conexão com Banco de Dados</h2>";

try {
    echo "<p>📡 Testando conexão com o servidor MySQL...</p>";
    
    $database = Database::getInstance();
    
    echo "<p>✅ Instância do Database criada com sucesso!</p>";
    
    if ($database->databaseExists()) {
        echo "<p>✅ Banco de dados 'bd_servicos' encontrado!</p>";
    } else {
        echo "<p>❌ Banco de dados 'bd_servicos' NÃO encontrado!</p>";
        echo "<p>📝 <strong>Solução:</strong> Importe o arquivo SQL no phpMyAdmin.</p>";
        exit();
    }
    
    $conn = $database->getConnection();
    echo "<p>✅ Conexão estabelecida com sucesso!</p>";
    
    if ($database->testConnection()) {
        echo "<p>✅ Teste de conexão passou!</p>";
    } else {
        echo "<p>❌ Teste de conexão falhou!</p>";
        exit();
    }
    
    // Testar algumas tabelas importantes
    $tabelas = ['tb_pessoa', 'tb_solicita_servico', 'tb_status_solicitacao', 'tb_tipo_servico'];
    
    echo "<h3>📋 Verificando tabelas:</h3>";
    foreach ($tabelas as $tabela) {
        $stmt = $conn->query("SHOW TABLES LIKE '$tabela'");
        if ($stmt->rowCount() > 0) {
            echo "<p>✅ Tabela '$tabela' encontrada</p>";
        } else {
            echo "<p>❌ Tabela '$tabela' NÃO encontrada</p>";
        }
    }
    
    // Testar dados básicos
    echo "<h3>📊 Verificando dados:</h3>";
    
    $stmt = $conn->query("SELECT COUNT(*) as total FROM tb_pessoa");
    $result = $stmt->fetch();
    echo "<p>👥 Total de pessoas cadastradas: " . $result['total'] . "</p>";
    
    $stmt = $conn->query("SELECT COUNT(*) as total FROM tb_solicita_servico");
    $result = $stmt->fetch();
    echo "<p>🛠️ Total de serviços solicitados: " . $result['total'] . "</p>";
    
    $stmt = $conn->query("SELECT COUNT(*) as total FROM tb_tipo_servico");
    $result = $stmt->fetch();
    echo "<p>🏷️ Total de tipos de serviço: " . $result['total'] . "</p>";
    
    echo "<h3>🎉 Conexão funcionando perfeitamente!</h3>";
    echo "<p><a href='index.php'>← Voltar para o sistema</a></p>";
    
} catch (Exception $e) {
    echo "<h3>❌ Erro na conexão:</h3>";
    echo "<p style='color: red;'>" . htmlspecialchars($e->getMessage()) . "</p>";
    
    echo "<h3>🔧 Possíveis soluções:</h3>";
    echo "<ul>";
    echo "<li>✅ Verifique se o XAMPP está rodando</li>";
    echo "<li>✅ Verifique se o MySQL está ativo no painel do XAMPP</li>";
    echo "<li>✅ Confirme se o banco 'bd_servicos' foi criado no phpMyAdmin</li>";
    echo "<li>✅ Importe o arquivo SQL fornecido no banco</li>";
    echo "<li>✅ Verifique as credenciais em config/database.php</li>";
    echo "</ul>";
}
