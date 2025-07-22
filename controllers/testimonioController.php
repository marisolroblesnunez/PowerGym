<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../data/testimonioDB.php';

class TestimonioController {
    private $testimonioDB;

    public function __construct() {
        $database = new Database();
        $this->testimonioDB = new testimonioDB($database);
    }

    public function procesarEnvioTestimonio() {
        session_start(); // Asegurarse de que la sesión esté iniciada
        $errores = [];
        $mensaje_exito = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtener el id_usuario de la sesión
        session_start();
        $id_usuario = $_SESSION['usuario_id'] ?? null;

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

    public function obtenerTestimoniosParaWeb() {
        return $this->testimonioDB->obtenerTestimoniosVisibles();
    }

    public function obtenerTodosLosTestimoniosAdmin() {
        return $this->testimonioDB->obtenerTodosLosTestimonios();
    }

    public function actualizarVisibilidad($id, $visible) {
        return $this->testimonioDB->actualizarVisibilidadTestimonio($id, $visible);
    }

    public function eliminarTestimonio($id) {
        return $this->testimonioDB->eliminarTestimonio($id);
    }
}