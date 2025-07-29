<?php
// Controlador para gestionar la lógica de las inscripciones a clases.
require_once __DIR__ . '/../config/database.php'; // Incluye la configuración de la base de datos.
require_once __DIR__ . '/../data/inscripcionclaseDB.php'; // Incluye el acceso a datos de las inscripciones.
require_once __DIR__ . '/../data/claseDB.php'; // Incluye el acceso a datos de las clases.

class InscripcionController {
    private $inscripcionClaseDB; // Propiedad para la instancia de InscripcionClaseDB.
    private $claseDB; // Propiedad para la instancia de ClaseDB.

    public function __construct() { // Constructor de la clase.
        $database = new Database(); // Crea una nueva instancia de la base de datos.
        $this->inscripcionClaseDB = new InscripcionClaseDB($database); // Inicializa la propiedad inscripcionClaseDB.
        $this->claseDB = new ClaseDB($database); // Inicializa la propiedad claseDB.
    }

    public function procesarInscripcion() { // Procesa una solicitud de inscripción desde la web (no API).
        // Asegúrate de que el usuario esté logueado y tengas su ID
        // Por ahora, asumiremos un ID de usuario de ejemplo o que viene de la sesión.
        // Obtener el id_usuario de la sesión
        session_start(); // Inicia o reanuda una sesión.
        $id_usuario = $_SESSION['usuario_id'] ?? null; // Obtiene el ID del usuario de la sesión.

        if (!$id_usuario) { // Si no hay un usuario logueado.
            return ['success' => false, 'message' => 'Debes iniciar sesión para inscribirte a una clase.']; // Devuelve un mensaje de error.
        }

        if (isset($_GET['clase_id'])) { // Comprueba si se ha proporcionado un ID de clase en la URL.
            $id_clase = (int)$_GET['clase_id']; // Obtiene y convierte el ID de la clase a entero.

            // 1. Verificar si la clase existe y obtener su cupo máximo
            $clase = $this->claseDB->getById($id_clase); // Obtiene los detalles de la clase.
            if (!$clase) { // Si la clase no existe.
                return ['success' => false, 'message' => 'Clase no encontrada.']; // Devuelve un mensaje de error.
            }

            // 2. Verificar si el usuario ya está inscrito en esta clase
            if ($this->inscripcionClaseDB->estaInscrito($id_usuario, $id_clase)) { // Comprueba si el usuario ya está inscrito.
                return ['success' => false, 'message' => 'Ya estás inscrito en esta clase.']; // Devuelve un mensaje de error.
            }

            // 3. Verificar el cupo máximo
            $inscritos_actuales = $this->claseDB->getInscritosCount($id_clase); // Obtiene el número de inscritos actuales.
            if ($inscritos_actuales >= $clase['cupo_maximo']) { // Comprueba si el cupo está lleno.
                return ['success' => false, 'message' => 'El cupo para esta clase está lleno.']; // Devuelve un mensaje de error.
            }

            // 4. Realizar la inscripción
            if ($this->inscripcionClaseDB->insertarInscripcion($id_usuario, $id_clase)) { // Intenta insertar la inscripción en la base de datos.
                return ['success' => true, 'message' => 'Inscripción exitosa!']; // Devuelve un mensaje de éxito.
            } else { // Si la inserción falla.
                return ['success' => false, 'message' => 'Error al procesar la inscripción.']; // Devuelve un mensaje de error.
            }
        }
        return ['success' => false, 'message' => 'ID de clase no proporcionado.']; // Devuelve un error si no se proporcionó el ID de la clase.
    }

    public function mostrarInscripcionesUsuario($id_usuario) { // Muestra las inscripciones de un usuario específico.
        return $this->inscripcionClaseDB->getInscripcionesByUsuario($id_usuario); // Devuelve las inscripciones del usuario.
    }

    public function eliminarInscripcionUsuario($id_inscripcion) { // Elimina una inscripción de un usuario.
        return $this->inscripcionClaseDB->eliminarInscripcion($id_inscripcion); // Llama al método para eliminar la inscripción.
    }

    // Nuevo método para la API, más limpio y reutilizable
    public function procesarInscripcionApi($id_usuario, $id_clase) { // Procesa una solicitud de inscripción desde la API.
        // 1. Verificar si la clase existe y obtener su cupo máximo
        $clase = $this->claseDB->getById($id_clase); // Obtiene los detalles de la clase.
        if (!$clase) { // Si la clase no existe.
            return ['success' => false, 'message' => 'Clase no encontrada.']; // Devuelve un mensaje de error.
        }

        // 2. Verificar si el usuario ya está inscrito en esta clase
        if ($this->inscripcionClaseDB->estaInscrito($id_usuario, $id_clase)) { // Comprueba si el usuario ya está inscrito.
            return ['success' => false, 'message' => 'Ya estás inscrito en esta clase.']; // Devuelve un mensaje de error.
        }

        // 3. Verificar el cupo máximo
        $inscritos_actuales = $this->claseDB->getInscritosCount($id_clase); // Obtiene el número de inscritos actuales.
        if ($inscritos_actuales >= $clase['cupo_maximo']) { // Comprueba si el cupo está lleno.
            return ['success' => false, 'message' => 'El cupo para esta clase está lleno.']; // Devuelve un mensaje de error.
        }

        // 4. Realizar la inscripción
        if ($this->inscripcionClaseDB->insertarInscripcion($id_usuario, $id_clase)) { // Intenta insertar la inscripción.
            return ['success' => true, 'message' => '¡Inscripción realizada con éxito!']; // Devuelve un mensaje de éxito.
        } else { // Si la inserción falla.
            return ['success' => false, 'message' => 'Error al procesar la inscripción.']; // Devuelve un mensaje de error.
        }
    }
}