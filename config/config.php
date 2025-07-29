<?php
// Credenciales para el envío de correos (usadas solo en producción)
define('MAIL_HOST', 'smtp.ionos.es');
define('MAIL_USER', 'info@aznaitin.es');
define('MAIL_PASS', 'SanFermin$7_Marisol');
define('DEBUG_MAIL', true); // En producción, siempre en false. Poner en true solo para depurar.

// Detección automática del entorno (Local vs. Producción)
$host = $_SERVER['HTTP_HOST'];
$serverName = $_SERVER['SERVER_NAME'];

if ($host === 'localhost' ||
    $host === '127.0.0.1' ||
    $serverName === 'localhost' ||
    $serverName === '127.0.0.1' ||
    strpos($host, 'localhost:') === 0 ||
    strpos($host, '127.0.0.1:') === 0) {

    // --- CONFIGURACIÓN PARA DESARROLLO LOCAL ---
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'gimnasio');
    // Usando el nombre correcto de la carpeta del proyecto
    define('URL_ADMIN','http://localhost/PowerGym/admin');
    define('USAR_EMAIL_REAL', false); // En local, usamos correos simulados

} else {
    // --- CONFIGURACIÓN PARA PRODUCCIÓN (NUBE) ---
    define('DB_HOST', 'db5018304787.hosting-data.io');
    define('DB_USER', 'dbu5425553');
    define('DB_PASS', '76065850Cc.');
    define('DB_NAME', 'dbs14505994');
    // Usando un nombre de carpeta consistente.
    // ¡IMPORTANTE! Asegúrate de que la carpeta en tu servidor real se llame así.
    define('URL_ADMIN','http://www.alumnamarisol.com/PowerGym/admin');
    define('USAR_EMAIL_REAL', true); // En producción, enviamos correos de verdad
}


?>