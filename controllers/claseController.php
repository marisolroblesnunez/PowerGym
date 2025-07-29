<?php
// Controlador para gestionar la lógica de las clases.
require_once __DIR__ . '/../config/database.php'; // Incluye la configuración de la base de datos.
require_once __DIR__ . '/../data/claseDB.php'; // Incluye el acceso a datos de las clases.
require_once __DIR__ . '/../data/entrenadorDB.php'; // Incluye el acceso a datos de los entrenadores.

class ClaseController {
    private $claseDB; // Propiedad para la instancia de ClaseDB.
    private $entrenadorDB; // Propiedad para la instancia de EntrenadorDB.

    public function __construct() {
        $database = new Database(); // Crea una nueva instancia de la base de datos.
        $this->claseDB = new ClaseDB($database); // Inicializa la propiedad claseDB.
        $this->entrenadorDB = new EntrenadorDB($database); // Inicializa la propiedad entrenadorDB.
    }

    public function mostrarClases() {
        // Inicia la sesión si no está iniciada para poder acceder a $_SESSION.
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $id_usuario = $_SESSION['user_id'] ?? null; // Obtiene el ID del usuario de la sesión.
        $clases = []; // Inicializa el array de clases.
        $dia_seleccionado = isset($_GET['dia']) ? $_GET['dia'] : null; // Obtiene el día seleccionado de la URL.

        if ($dia_seleccionado) {
            // Pasamos el id_usuario al método.
            $clases = $this->claseDB->getClasesByDia($dia_seleccionado, $id_usuario); // Obtiene las clases para el día seleccionado.
        } else {
            // Pasamos el id_usuario al método.
            $clases = $this->claseDB->getAllClasesWithDetails($id_usuario); // Obtiene todas las clases con detalles.
        }

        $entrenadores = $this->entrenadorDB->getAll(); // Obtiene todos los entrenadores.

        return ['clases' => $clases, 'entrenadores' => $entrenadores, 'dia_seleccionado' => $dia_seleccionado]; // Devuelve los datos a la vista.
    }

    // Otros métodos para manejar inscripciones, etc., se añadirán aquí más tarde.

    public function handleApiRequest($method) {
        if ($method == 'GET') {
            // Esta línea hace lo mismo que tu api/clases.php.
            $clases = $this->claseDB->getAllClasesWithDetails(); // Obtiene todas las clases con detalles para la API.
            echo json_encode($clases); // Devuelve las clases en formato JSON.
        } else {
            // Devolvemos un error si se intenta usar un método no permitido (POST, PUT, etc.).
            header('HTTP/1.1 405 Method Not Allowed'); // Establece la cabecera de error.
            echo json_encode(['error' => 'Método no permitido para este recurso']); // Devuelve un mensaje de error en JSON.
        }
    }
}
?>