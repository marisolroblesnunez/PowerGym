<?php
// Este archivo actúa como el enrutador principal para todas las solicitudes de la API.
// Iniciar sesión para acceder a las variables de sesión en toda la API
session_start(); // Inicia o reanuda una sesión.

// 1. CABECERAS Y CONFIGURACIÓN INICIAL
header("Access-Control-Allow-Origin: *"); // Permite el acceso desde cualquier origen (CORS).
header("Content-Type: application/json; charset=UTF-8"); // Establece el tipo de contenido de la respuesta a JSON.
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE"); // Define los métodos HTTP permitidos.
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With"); // Define las cabeceras permitidas en la solicitud.

// 2. INCLUSIÓN DE ARCHIVOS ESENCIALES
// Incluimos los controladores que contienen la lógica de negocio.
require_once __DIR__ . '/../controllers/claseController.php'; // Incluye el controlador de clases.
require_once __DIR__ . '/../controllers/inscripcionController.php'; // Incluye el controlador de inscripciones.
// Incluimos la capa de datos para los entrenadores, ya que no tienen un controlador complejo.
require_once __DIR__ . '/../config/database.php'; // Incluye la configuración de la base de datos.
require_once __DIR__ . '/../data/entrenadorDB.php'; // Incluye el acceso a datos de los entrenadores.

// 3. ENRUTADOR PRINCIPAL (ROUTER)
// Obtenemos el recurso solicitado (ej: 'clases', 'reservar') de la URL.
$resource = $_GET['resource'] ?? null; // Obtiene el recurso de la query string, ej: /api/index.php?resource=clases
// Obtenemos el método de la petición (GET, POST, etc.).
$requestMethod = $_SERVER['REQUEST_METHOD']; // Obtiene el método HTTP de la solicitud (GET, POST, etc.).

// 4. DISTRIBUCIÓN DE LA PETICIÓN AL CÓDIGO ADECUADO
switch ($resource) { // Evalúa el recurso solicitado.
    case 'clases': // Si el recurso es 'clases'.
        if ($requestMethod == 'GET') { // Si el método es GET.
            $controller = new ClaseController(); // Crea una instancia del controlador de clases.
            // La lógica para obtener las clases (incluyendo el user_id)
            // ya está en el método mostrarClases(), así que simplemente lo llamamos.
            $datosClases = $controller->mostrarClases(); // Llama al método para obtener los datos de las clases.
            echo json_encode($datosClases['clases']); // Devuelve las clases en formato JSON.
        } else { // Si el método no es GET.
            header("HTTP/1.1 405 Method Not Allowed"); // Devuelve un error 405.
            echo json_encode(['success' => false, 'message' => 'Método no permitido para el recurso clases.']); // Mensaje de error.
        }
        break; // Termina el caso.

    case 'entrenadores': // Si el recurso es 'entrenadores'.
        if ($requestMethod == 'GET') { // Si el método es GET.
            $database = new Database(); // Crea una nueva conexión a la base de datos.
            $entrenadorDB = new EntrenadorDB($database); // Crea una instancia del acceso a datos de entrenadores.
            $entrenadores = $entrenadorDB->getAll(); // Obtiene todos los entrenadores.
            echo json_encode($entrenadores); // Devuelve los entrenadores en formato JSON.
            $database->close(); // Cierra la conexión a la base de datos.
        } else { // Si el método no es GET.
            header("HTTP/1.1 405 Method Not Allowed"); // Devuelve un error 405.
            echo json_encode(['success' => false, 'message' => 'Método no permitido para el recurso entrenadores.']); // Mensaje de error.
        }
        break; // Termina el caso.

    case 'reservar': // Si el recurso es 'reservar'.
        if ($requestMethod == 'POST') { // Si el método es POST.
            // Endpoint protegido: requiere que el usuario haya iniciado sesión.
            if (!isset($_SESSION['user_id'])) { // Comprueba si el usuario ha iniciado sesión.
                header('HTTP/1.1 401 Unauthorized'); // Devuelve un error 401 si no está autorizado.
                echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión para realizar una reserva.']); // Mensaje de error.
                exit(); // Termina la ejecución.
            }
            
            // Obtenemos el ID de la clase del cuerpo de la petición POST.
            $data = json_decode(file_get_contents('php://input'), true); // Lee el cuerpo de la solicitud POST (JSON).
            $id_clase = $data['id_clase'] ?? null; // Obtiene el id_clase del JSON.

            if (!$id_clase) { // Si no se proporcionó el id_clase.
                header('HTTP/1.1 400 Bad Request'); // Devuelve un error 400.
                echo json_encode(['success' => false, 'message' => 'El ID de la clase es obligatorio.']); // Mensaje de error.
                exit(); // Termina la ejecución.
            }

            // Usamos el controlador de inscripciones para procesar la reserva.
            $controller = new InscripcionController(); // Crea una instancia del controlador de inscripciones.
            // Pasamos el ID del usuario de la sesión y el ID de la clase.
            $resultado = $controller->procesarInscripcionApi($_SESSION['user_id'], $id_clase); // Procesa la inscripción.
            
            if ($resultado['success']) { // Si la inscripción fue exitosa.
                header('HTTP/1.1 200 OK'); // Devuelve un estado 200 OK.
            } else { // Si la inscripción falló.
                // Si la inscripción falla (ej: cupo lleno), devolvemos un error de conflicto.
                header('HTTP/1.1 409 Conflict'); // Devuelve un error 409.
            }
            echo json_encode($resultado); // Devuelve el resultado de la operación en JSON.

        } else { // Si el método no es POST.
            header("HTTP/1.1 405 Method Not Allowed"); // Devuelve un error 405.
            echo json_encode(['success' => false, 'message' => 'Método no permitido para el recurso reservar.']); // Mensaje de error.
        }
        break; // Termina el caso.

    case 'check_session': // Si el recurso es 'check_session'.
        if ($requestMethod == 'GET') { // Si el método es GET.
            if (isset($_SESSION['logueado']) && $_SESSION['logueado'] === true) { // Comprueba si el usuario está logueado.
                echo json_encode([ // Devuelve los datos de la sesión.
                    'logueado' => true,
                    'username' => $_SESSION['usuario']['nombre'] ?? 'Usuario', // O el campo que corresponda
                    'user_id' => $_SESSION['user_id']
                ]);
            } else { // Si el usuario no está logueado.
                echo json_encode(['logueado' => false]); // Devuelve que no está logueado.
            }
        } else { // Si el método no es GET.
            header("HTTP/1.1 405 Method Not Allowed"); // Devuelve un error 405.
            echo json_encode(['success' => false, 'message' => 'Método no permitido para el recurso check_session.']); // Mensaje de error.
        }
        break; // Termina el caso.

    default: // Si el recurso no coincide con ninguno de los casos anteriores.
        // Si el recurso no se reconoce, devolvemos un error 404.
        header("HTTP/1.0 404 Not Found"); // Devuelve un error 404.
        echo json_encode(['success' => false, 'message' => 'Endpoint no encontrado.']); // Mensaje de error.
        break; // Termina el caso.
}
?>