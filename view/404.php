<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página não encontrada - Chama Serviço</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error-container {
            text-align: center;
            max-width: 600px;
            padding: 30px;
        }
        .error-code {
            font-size: 120px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 0;
            line-height: 1;
        }
        .error-divider {
            width: 80px;
            height: 5px;
            background: linear-gradient(135deg, #2c3e50 0%, #4f6fa5 100%);
            margin: 20px auto;
            border-radius: 3px;
        }
        .error-message {
            font-size: 24px;
            font-weight: 500;
            color: #333;
            margin-bottom: 20px;
        }
        .error-details {
            font-size: 16px;
            color: #6c757d;
            margin-bottom: 30px;
        }
        .home-button {
            background: linear-gradient(135deg, #2c3e50 0%, #4f6fa5 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .home-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            background: linear-gradient(135deg, #4f6fa5 0%, #2c3e50 100%);
            color: white;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1 class="error-code">404</h1>
        <div class="error-divider"></div>
        <h2 class="error-message">Página não encontrada</h2>
        <p class="error-details">
            Desculpe, a página que você está procurando não existe ou foi movida.
        </p>
        <a href="index.php" class="btn home-button">
            <i class="bi bi-house-door me-2"></i>Voltar para o início
        </a>
    </div>
</body>
</html>
