<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../data/claseDB.php';
require_once __DIR__ . '/../data/entrenadorDB.php';

class ClaseController {
    private $claseDB;
    private $entrenadorDB;

    public function __construct() {
        $database = new Database();
        $this->claseDB = new ClaseDB($database);
        $this->entrenadorDB = new EntrenadorDB($database);
    }

    public function mostrarClases() {
        // Inicia la sesión si no está iniciada para poder acceder a $_SESSION
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $id_usuario = $_SESSION['user_id'] ?? null;
        $clases = [];
        $dia_seleccionado = isset($_GET['dia']) ? $_GET['dia'] : null;

        if ($dia_seleccionado) {
            // Pasamos el id_usuario al método
            $clases = $this->claseDB->getClasesByDia($dia_seleccionado, $id_usuario);
        } else {
            // Pasamos el id_usuario al método
            $clases = $this->claseDB->getAllClasesWithDetails($id_usuario);
        }

        $entrenadores = $this->entrenadorDB->getAll();

        return ['clases' => $clases, 'entrenadores' => $entrenadores, 'dia_seleccionado' => $dia_seleccionado];
    }

    // Otros métodos para manejar inscripciones, etc., se añadirán aquí más tarde.

    public function handleApiRequest($method) {
        if ($method == 'GET') {
            // Esta línea hace lo mismo que tu api/clases.php
            $clases = $this->claseDB->getAllClasesWithDetails();
            echo json_encode($clases);
        } else {
            // Devolvemos un error si se intenta usar un método no permitido (POST, PUT, etc.)
            header('HTTP/1.1 405 Method Not Allowed');
            echo json_encode(['error' => 'Método no permitido para este recurso']);
        }
    }
}
