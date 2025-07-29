<?php
// DATA/inscripcionclaseDB.php
// Este archivo contiene la clase InscripcionClaseDB, que gestiona las interacciones con la tabla 'inscripciones_clases' en la base de datos.

/**
 * Clase InscripcionClaseDB: Se encarga de interactuar con la base de datos para la tabla 'inscripciones_clases'.
 * Proporciona métodos para realizar operaciones como insertar inscripciones, verificar si un usuario ya está inscrito,
 * obtener inscripciones por usuario y eliminar inscripciones.
 */
class InscripcionClaseDB {

    private $db; // Propiedad para almacenar la conexión a la base de datos.
    private $table = 'inscripciones_clases'; // Nombre de la tabla de inscripciones de clases en la base de datos.

    // Constructor: recibe un objeto de conexión a la base de datos y lo asigna a la propiedad $db.
    public function __construct($database){
        $this->db = $database->getConexion();
    }

    // Obtiene todos los registros de la tabla de inscripciones de clases.
    public  function getAll(){
        $sql = "SELECT * FROM {$this->table}"; // Prepara la consulta SQL para seleccionar todas las inscripciones.
        $resultado = $this->db->query($sql); // Ejecuta la consulta.

        // Comprueba si la consulta fue exitosa y si devolvió filas.
        if($resultado && $resultado->num_rows > 0){
            $inscripciones = []; // Inicializa un array para guardar las inscripciones.
            // Itera sobre cada fila del resultado y la añade al array de inscripciones.
            while($row = $resultado->fetch_assoc()){
                $inscripciones[] = $row;
            }
            return $inscripciones; // Devuelve el array de inscripciones.
        }else{
            return []; // Si no hay inscripciones, devuelve un array vacío.
        }
    }

    // Obtiene una inscripción específica por su ID.
    public function getById($id){
        $sql = "SELECT * FROM {$this->table} WHERE id = ?"; // Consulta SQL con un marcador de posición para el ID.
        $stmt = $this->db->prepare($sql); // Prepara la consulta para evitar inyección SQL.
        if($stmt){
            $stmt->bind_param("i", $id); // Asocia el parámetro ID (como entero) a la consulta.
            $stmt->execute(); // Ejecuta la consulta preparada.
            $result = $stmt->get_result(); // Obtiene el resultado.

            // Comprueba si se encontró una inscripción.
            if($result->num_rows > 0){
                return $result->fetch_assoc(); // Devuelve los datos de la inscripción como un array asociativo.
            }
            $stmt->close(); // Cierra la sentencia preparada.
        }
        return null; // Si no se encuentra la inscripción o hay un error, devuelve null.
    }

    // Inserta una nueva inscripción en la base de datos.
    public function insertarInscripcion($id_usuario, $id_clase) {
        $sql = "INSERT INTO inscripciones_clases (id_usuario, id_clase, fecha_inscripcion) VALUES (?, ?, NOW())"; // Consulta SQL para insertar una inscripción.
        $stmt = $this->db->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ii", $id_usuario, $id_clase); // Asocia los IDs de usuario y clase (como enteros) a la consulta.
            $result = $stmt->execute(); // Ejecuta la inserción.
            $stmt->close();
            return $result; // Devuelve true si la inserción fue exitosa, false en caso contrario.
        }
        return false;
    }

    // Verifica si un usuario ya está inscrito en una clase específica.
    public function estaInscrito($id_usuario, $id_clase) {
        $sql = "SELECT COUNT(*) AS count FROM inscripciones_clases WHERE id_usuario = ? AND id_clase = ?"; // Consulta para contar inscripciones.
        $stmt = $this->db->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ii", $id_usuario, $id_clase); // Asocia los IDs de usuario y clase.
            $stmt->execute();
            $result = $stmt->get_result(); // Obtiene el resultado de la consulta.
            $row = $result->fetch_assoc(); // Obtiene la fila como un array asociativo.
            $stmt->close();
            return $row['count'] > 0; // Devuelve true si el conteo es mayor que 0 (ya está inscrito), false en caso contrario.
        }
        return false;
    }

    // Obtiene todas las inscripciones de un usuario específico, incluyendo detalles de la clase.
    public function getInscripcionesByUsuario($id_usuario) {
        $sql = "SELECT ic.*, c.nombre AS nombre_clase, c.dia_semana, c.hora
                FROM inscripciones_clases ic
                JOIN clases c ON ic.id_clase = c.id
                WHERE ic.id_usuario = ?
                ORDER BY c.dia_semana, c.hora"; // Consulta para obtener inscripciones con detalles de clase.
        $stmt = $this->db->prepare($sql);
        $inscripciones = [];
        if ($stmt) {
            $stmt->bind_param("i", $id_usuario); // Asocia el ID de usuario.
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $inscripciones[] = $row;
            }
            $stmt->close();
        }
        return $inscripciones; // Devuelve el array de inscripciones.
    }

    // Elimina una inscripción específica por su ID.
    public function eliminarInscripcion($id_inscripcion) {
        $sql = "DELETE FROM inscripciones_clases WHERE id = ?"; // Consulta SQL para eliminar una inscripción.
        $stmt = $this->db->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $id_inscripcion); // Asocia el ID de la inscripción.
            $result = $stmt->execute(); // Ejecuta la eliminación.
            $stmt->close();
            return $result; // Devuelve true si la eliminación fue exitosa, false en caso contrario.
        }
        return false;
    }
}