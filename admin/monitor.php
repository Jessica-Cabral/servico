<?php
// Monitorar usuários ativos
$usuarios_ativos = [
    'total_sessoes' => count(glob(session_save_path() . '/sess_*')),
    'conexoes_db' => shell_exec('mysql -e "SHOW STATUS LIKE \'Threads_connected\'"'),
    'memoria_uso' => memory_get_usage(true),
    'tempo_resposta' => microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']
];

echo json_encode($usuarios_ativos);
?>
