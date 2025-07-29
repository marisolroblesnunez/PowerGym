<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../data/testimonioDB.php';

/**
 * Controlador para gestionar testimonios de usuarios.
 * 
 * Métodos principales:
 * - procesarEnvioTestimonio(): Valida y procesa el envío de un nuevo testimonio por parte de un usuario.
 * - obtenerTestimoniosParaWeb(): Obtiene los testimonios visibles para mostrarlos en la web.
 * - obtenerTodosLosTestimoniosAdmin(): Obtiene todos los testimonios para administración.
 * - actualizarVisibilidad($id, $visible): Cambia la visibilidad de un testimonio.
 * - eliminarTestimonio($id): Elimina un testimonio.
 */
class TestimonioController {
    /**
     * Instancia de la clase testimonioDB para operaciones de base de datos.
     * @var testimonioDB
     */
    private $testimonioDB;

    /**
     * Constructor. Inicializa la conexión a la base de datos y la clase testimonioDB.
     */
    public function __construct() {
        $database = new Database();
        $this->testimonioDB = new testimonioDB($database);
    }

    /**
     * Procesa el envío de un testimonio por parte de un usuario.
     * Valida el mensaje y el estado de sesión, guarda el testimonio si es válido.
     * 
     * @return array ['errores' => array, 'mensaje_exito' => string]
     */
    public function procesarEnvioTestimonio() {
        $errores = [];
        $mensaje_exito = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_usuario = $_SESSION['user_id'] ?? null;

            if (!$id_usuario) {
                $errores[] = 'Debes iniciar sesión para dejar una reseña.';
            }
            
            $mensaje = trim($_POST['mensaje'] ?? '');

            if (empty($mensaje)) {
                $errores[] = 'El mensaje de la reseña es obligatorio.';
            } elseif (strlen($mensaje) < 10) {
                $errores[] = 'La reseña debe tener al menos 10 caracteres.';
            }

            if (empty($errores)) {
                if ($this->testimonioDB->guardarTestimonio($id_usuario, $mensaje)) {
                    $mensaje_exito = 'Gracias por tu opinión. ¡Pronto estará visible para otros usuarios!';
                    $_POST['mensaje'] = ''; // Limpiar campo
                } else {
                    $errores[] = 'Hubo un error al guardar tu reseña. Por favor, inténtalo de nuevo.';
                }
            }
        }
        return ['errores' => $errores, 'mensaje_exito' => $mensaje_exito];
    }

    /**
     * Obtiene los testimonios visibles para mostrarlos en la web.
     * 
     * @return array Lista de testimonios visibles.
     */
    public function obtenerTestimoniosParaWeb() {
        return $this->testimonioDB->obtenerTestimoniosVisibles();
    }

    /**
     * Obtiene todos los testimonios para administración.
     * 
     * @return array Lista completa de testimonios.
     */
    public function obtenerTodosLosTestimoniosAdmin() {
        return $this->testimonioDB->obtenerTodosLosTestimonios();
    }

    /**
     * Actualiza la visibilidad de un testimonio.
     * 
     * @param int $id ID del testimonio.
     * @param bool $visible Estado de visibilidad.
     * @return bool True si se actualizó correctamente.
     */
    public function actualizarVisibilidad($id, $visible) {
        return $this->testimonioDB->actualizarVisibilidadTestimonio($id, $visible);
    }

    /**
     * Elimina un testimonio.
     * 
     * @param int $id ID del testimonio.
     * @return bool True si se eliminó correctamente.
     */
    public function eliminarTestimonio($id) {
        return $this->testimonioDB->eliminarTestimonio($id);
    }
}