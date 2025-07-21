<?php
// Iniciar sesión para acceder a las variables de sesión en toda la API
session_start();

// 1. CABECERAS Y CONFIGURACIÓN INICIAL
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// 2. INCLUSIÓN DE ARCHIVOS ESENCIALES
// Incluimos los controladores que contienen la lógica de negocio.
require_once __DIR__ . '/../controllers/claseController.php';
require_once __DIR__ . '/../controllers/inscripcionController.php';
// Incluimos la capa de datos para los entrenadores, ya que no tienen un controlador complejo.
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../data/entrenadorDB.php';

// 3. ENRUTADOR PRINCIPAL (ROUTER)
// Obtenemos el recurso solicitado (ej: 'clases', 'reservar') de la URL.
$resource = $_GET['resource'] ?? null;
// Obtenemos el método de la petición (GET, POST, etc.).
$requestMethod = $_SERVER['REQUEST_METHOD'];

// 4. DISTRIBUCIÓN DE LA PETICIÓN AL CÓDIGO ADECUADO
switch ($resource) {
    case 'clases':
        if ($requestMethod == 'GET') {
            $controller = new ClaseController();
            // La lógica para obtener las clases (incluyendo el user_id)
            // ya está en el método mostrarClases(), así que simplemente lo llamamos.
            $datosClases = $controller->mostrarClases();
            echo json_encode($datosClases['clases']);
        } else {
            header("HTTP/1.1 405 Method Not Allowed");
            echo json_encode(['success' => false, 'message' => 'Método no permitido para el recurso clases.']);
        }
        break;

    case 'entrenadores':
        if ($requestMethod == 'GET') {
            $database = new Database();
            $entrenadorDB = new EntrenadorDB($database);
            $entrenadores = $entrenadorDB->getAll();
            echo json_encode($entrenadores);
            $database->close();
        } else {
            header("HTTP/1.1 405 Method Not Allowed");
            echo json_encode(['success' => false, 'message' => 'Método no permitido para el recurso entrenadores.']);
        }
        break;

    case 'reservar':
        if ($requestMethod == 'POST') {
            // Endpoint protegido: requiere que el usuario haya iniciado sesión.
            if (!isset($_SESSION['user_id'])) {
                header('HTTP/1.1 401 Unauthorized');
                echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión para realizar una reserva.']);
                exit();
            }
            
            // Obtenemos el ID de la clase del cuerpo de la petición POST.
            $data = json_decode(file_get_contents('php://input'), true);
            $id_clase = $data['id_clase'] ?? null;

            if (!$id_clase) {
                header('HTTP/1.1 400 Bad Request');
                echo json_encode(['success' => false, 'message' => 'El ID de la clase es obligatorio.']);
                exit();
            }

            // Usamos el controlador de inscripciones para procesar la reserva.
            $controller = new InscripcionController();
            // Pasamos el ID del usuario de la sesión y el ID de la clase.
            $resultado = $controller->procesarInscripcionApi($_SESSION['user_id'], $id_clase);
            
            if ($resultado['success']) {
                header('HTTP/1.1 200 OK');
            } else {
                // Si la inscripción falla (ej: cupo lleno), devolvemos un error de conflicto.
                header('HTTP/1.1 409 Conflict');
            }
            echo json_encode($resultado);

        } else {
            header("HTTP/1.1 405 Method Not Allowed");
            echo json_encode(['success' => false, 'message' => 'Método no permitido para el recurso reservar.']);
        }
        break;

    case 'check_session':
        if ($requestMethod == 'GET') {
            if (isset($_SESSION['logueado']) && $_SESSION['logueado'] === true) {
                echo json_encode([
                    'logueado' => true,
                    'username' => $_SESSION['usuario']['nombre'] ?? 'Usuario', // O el campo que corresponda
                    'user_id' => $_SESSION['user_id']
                ]);
            } else {
                echo json_encode(['logueado' => false]);
            }
        } else {
            header("HTTP/1.1 405 Method Not Allowed");
            echo json_encode(['success' => false, 'message' => 'Método no permitido para el recurso check_session.']);
        }
        break;

    default:
        // Si el recurso no se reconoce, devolvemos un error 404.
        header("HTTP/1.0 404 Not Found");
        echo json_encode(['success' => false, 'message' => 'Endpoint no encontrado.']);
        break;
}
?>