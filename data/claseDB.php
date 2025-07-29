<?php
// DATA/claseDB.php
// Este archivo contiene la clase ClaseDB, que se encarga de todas las interacciones con la tabla 'clases' en la base de datos.

/**
 * Clase ClaseDB: Gestiona las operaciones de base de datos para la tabla 'clases'.
 * Proporciona métodos para obtener, insertar, actualizar y eliminar clases, así como para
 * obtener detalles adicionales como el número de inscritos y si un usuario está inscrito.
 */
class ClaseDB {

    private $db; // Propiedad para almacenar la conexión a la base de datos.
    private $table = 'clases'; // Nombre de la tabla de clases en la base de datos.

    // Constructor: recibe un objeto de conexión a la base de datos y lo asigna a la propiedad $db.
    public function __construct($database){
        $this->db = $database->getConexion();
    }

    // Obtiene todos los registros de la tabla de clases.
    public  function getAll(){
        $sql = "SELECT * FROM {$this->table}"; // Prepara la consulta SQL para seleccionar todas las clases.
        $resultado = $this->db->query($sql); // Ejecuta la consulta.

        // Comprueba si la consulta fue exitosa y si devolvió filas.
        if($resultado && $resultado->num_rows > 0){
            $clases = []; // Inicializa un array para guardar las clases.
            // Itera sobre cada fila del resultado y la añade al array de clases.
            while($row = $resultado->fetch_assoc()){
                $clases[] = $row;
            }
            return $clases; // Devuelve el array de clases.
        }else{
            return []; // Si no hay clases, devuelve un array vacío.
        }
    }

    // Obtiene una clase específica por su ID.
    public function getById($id){
        $sql = "SELECT * FROM {$this->table} WHERE id = ?"; // Consulta SQL con un marcador de posición para el ID.
        $stmt = $this->db->prepare($sql); // Prepara la consulta para evitar inyección SQL.
        if($stmt){
            $stmt->bind_param("i", $id); // Asocia el parámetro ID (como entero) a la consulta.
            $stmt->execute(); // Ejecuta la consulta preparada.
            $result = $stmt->get_result(); // Obtiene el resultado.

            // Comprueba si se encontró una clase.
            if($result->num_rows > 0){
                return $result->fetch_assoc(); // Devuelve los datos de la clase como un array asociativo.
            }
            $stmt->close(); // Cierra la sentencia preparada.
        }
        return null; // Si no se encuentra la clase o hay un error, devuelve null.
    }

    // Obtiene una clase por su nombre.
    public function getByName($nombre) {
        $sql = "SELECT * FROM {$this->table} WHERE nombre = ?"; // Consulta SQL con marcador para el nombre.
        $stmt = $this->db->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("s", $nombre); // Asocia el nombre (como string) al parámetro.
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return $result->fetch_assoc();
            }
            $stmt->close();
        }
        return null;
    }

    // Inserta una nueva clase en la base de datos.
    public function insertarClase($nombre, $descripcion, $dia_semana, $hora, $duracion_minutos, $cupo_maximo, $id_entrenador) {
        $sql = "INSERT INTO clases (nombre, descripcion, dia_semana, hora, duracion_minutos, cupo_maximo, id_entrenador) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        if ($stmt) {
            // Asocia los parámetros a la consulta (s: string, i: integer).
            $stmt->bind_param("sssssii", $nombre, $descripcion, $dia_semana, $hora, $duracion_minutos, $cupo_maximo, $id_entrenador);
            $result = $stmt->execute(); // Ejecuta la inserción.
            $stmt->close();
            return $result; // Devuelve true si la inserción fue exitosa, false en caso contrario.
        }
        return false;
    }

    // Actualiza los datos de una clase existente.
    public function actualizarClase($id, $nombre, $descripcion, $dia_semana, $hora, $duracion_minutos, $cupo_maximo, $id_entrenador) {
        $sql = "UPDATE clases SET nombre = ?, descripcion = ?, dia_semana = ?, hora = ?, duracion_minutos = ?, cupo_maximo = ?, id_entrenador = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        if ($stmt) {
            // Asocia los parámetros a la consulta de actualización.
            $stmt->bind_param("sssssiii", $nombre, $descripcion, $dia_semana, $hora, $duracion_minutos, $cupo_maximo, $id_entrenador, $id);
            $result = $stmt->execute(); // Ejecuta la actualización.
            $stmt->close();
            return $result; // Devuelve true si la actualización fue exitosa, false en caso contrario.
        }
        return false;
    }

    // Elimina una clase de la base de datos.
    public function eliminarClase($id) {
        $sql = "DELETE FROM clases WHERE id = ?"; // Consulta SQL para eliminar por ID.
        $stmt = $this->db->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $id); // Asocia el ID al parámetro.
            $result = $stmt->execute(); // Ejecuta la eliminación.
            $stmt->close();
            return $result; // Devuelve true si la eliminación fue exitosa, false en caso contrario.
        }
        return false;
    }

    // Obtiene todas las clases con detalles adicionales como el nombre del entrenador, el número de inscritos
    // y si un usuario específico está inscrito en cada clase.
    public function getAllClasesWithDetails($id_usuario = null) {
        $sql = "SELECT 
                    c.*, 
                    e.nombre AS nombre_entrenador, 
                    COUNT(ic.id_clase) AS inscritos_actuales,
                    CASE 
                        WHEN ? IS NOT NULL THEN EXISTS(SELECT 1 FROM inscripciones_clases WHERE id_clase = c.id AND id_usuario = ?)
                        ELSE 0 
                    END AS usuario_inscrito
                FROM clases c
                LEFT JOIN entrenadores e ON c.id_entrenador = e.id
                LEFT JOIN inscripciones_clases ic ON c.id = ic.id_clase
                GROUP BY c.id
                ORDER BY FIELD(c.dia_semana, 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'), c.hora";
        
        $stmt = $this->db->prepare($sql);
        $clases = [];

        if ($stmt) {
            // Bindea el id_usuario dos veces: una para el CASE (comprobación de NULL) y otra para la subconsulta EXISTS.
            $stmt->bind_param("ii", $id_usuario, $id_usuario);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $clases[] = $row;
            }
            $stmt->close();
        }
        
        return $clases;
    }

    // Obtiene las clases para un día de la semana específico, incluyendo detalles del entrenador y estado de inscripción del usuario.
    public function getClasesByDia($dia_semana, $id_usuario = null) {
        $sql = "SELECT 
                    c.*, 
                    e.nombre AS nombre_entrenador, 
                    COUNT(ic.id_clase) AS inscritos_actuales,
                    CASE 
                        WHEN ? IS NOT NULL THEN EXISTS(SELECT 1 FROM inscripciones_clases WHERE id_clase = c.id AND id_usuario = ?)
                        ELSE 0 
                    END AS usuario_inscrito
                FROM clases c
                LEFT JOIN entrenadores e ON c.id_entrenador = e.id
                LEFT JOIN inscripciones_clases ic ON c.id = ic.id_clase
                WHERE c.dia_semana = ?
                GROUP BY c.id
                ORDER BY c.hora";
                
        $stmt = $this->db->prepare($sql);
        $clases = [];
        if ($stmt) {
            // Bindea los tres parámetros: id_usuario (dos veces) y dia_semana.
            $stmt->bind_param("iis", $id_usuario, $id_usuario, $dia_semana);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $clases[] = $row;
            }
            $stmt->close();
        }
        return $clases;
    }

    // Obtiene el número actual de inscritos para una clase específica.
    public function getInscritosCount($id_clase) {
        $sql = "SELECT COUNT(*) AS count FROM inscripciones_clases WHERE id_clase = ?";
        $stmt = $this->db->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $id_clase);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();
            return $row['count']; // Devuelve el conteo de inscritos.
        }
        return 0; // Si hay un error o no hay inscritos, devuelve 0.
    }

}