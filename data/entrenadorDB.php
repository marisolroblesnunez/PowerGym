<?php
/**
 * Clase EntrenadorDB: Se encarga de interactuar con la base de datos para la tabla 'entrenadores'.
 * Proporciona métodos para realizar operaciones CRUD (Crear, Leer, Actualizar, Borrar) y búsquedas
 * específicas relacionadas con los entrenadores.
 */
class EntrenadorDB {

    private $db; // Propiedad para almacenar la conexión a la base de datos.
    private $table = 'entrenadores'; // Nombre de la tabla de entrenadores en la base de datos.

    // Constructor: recibe un objeto de conexión a la base de datos y lo asigna a la propiedad $db.
    public function __construct($database){
        $this->db = $database->getConexion();
    }

    // Obtiene todos los registros de la tabla de entrenadores.
    public  function getAll(){
        $sql = "SELECT * FROM {$this->table}"; // Prepara la consulta SQL para seleccionar todos los entrenadores.
        $resultado = $this->db->query($sql); // Ejecuta la consulta.

        // Comprueba si la consulta fue exitosa y si devolvió filas.
        if($resultado && $resultado->num_rows > 0){
            $entrenadores = []; // Inicializa un array para guardar los entrenadores.
            // Itera sobre cada fila del resultado y la añade al array de entrenadores.
            while($row = $resultado->fetch_assoc()){
                $entrenadores[] = $row;
            }
            return $entrenadores; // Devuelve el array de entrenadores.
        }else{
            return []; // Si no hay entrenadores, devuelve un array vacío.
        }
    }

    // Obtiene un entrenador específico por su ID.
    public function getById($id){
        $sql = "SELECT * FROM {$this->table} WHERE id = ?"; // Consulta SQL con un marcador de posición para el ID.
        $stmt = $this->db->prepare($sql); // Prepara la consulta para evitar inyección SQL.
        if($stmt){
            $stmt->bind_param("i", $id); // Asocia el parámetro ID (como entero) a la consulta.
            $stmt->execute(); // Ejecuta la consulta preparada.
            $result = $stmt->get_result(); // Obtiene el resultado.

            // Comprueba si se encontró un entrenador.
            if($result->num_rows > 0){
                return $result->fetch_assoc(); // Devuelve los datos del entrenador como un array asociativo.
            }
            $stmt->close(); // Cierra la sentencia preparada.
        }
        return null; // Si no se encuentra el entrenador o hay un error, devuelve null.
    }

    // Inserta un nuevo entrenador en la base de datos.
    public function insert($nombre, $especialidad, $telefono, $email) {
        $sql = "INSERT INTO {$this->table} (nombre, especialidad, telefono, email) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ssss", $nombre, $especialidad, $telefono, $email); // Asocia los parámetros a la consulta.
            if ($stmt->execute()) {
                return true; // Inserción exitosa.
            }
            $stmt->close();
        }
        return false; // Fallo en la inserción.
    }

    // Actualiza los datos de un entrenador existente.
    public function update($id, $nombre, $especialidad, $telefono, $email) {
        $sql = "UPDATE {$this->table} SET nombre = ?, especialidad = ?, telefono = ?, email = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ssssi", $nombre, $especialidad, $telefono, $email, $id); // Asocia los parámetros a la consulta.
            if ($stmt->execute()) {
                return true; // Actualización exitosa.
            }
            $stmt->close();
        }
        return false; // Fallo en la actualización.
    }

    // Elimina un entrenador de la base de datos.
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?"; // Consulta SQL para eliminar por ID.
        $stmt = $this->db->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $id); // Asocia el ID al parámetro.
            if ($stmt->execute()) {
                return true; // Eliminación exitosa.
            }
            $stmt->close();
        }
        return false; // Fallo en la eliminación.
    }

    // Obtiene un entrenador por su dirección de email.
    public function getByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return $result->fetch_assoc(); // Devuelve el primer entrenador encontrado.
            }
            $stmt->close();
        }
        return null; // No se encontró ningún entrenador con ese email.
    }

    // Obtiene todos los entrenadores con una especialidad específica.
    public function getByEspecialidad($especialidad) {
        $sql = "SELECT * FROM {$this->table} WHERE especialidad = ?";
        $stmt = $this->db->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("s", $especialidad);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return $result->fetch_all(MYSQLI_ASSOC); // Devuelve todos los entrenadores con esa especialidad.
            }
            $stmt->close();
        }
        return []; // No se encontraron entrenadores con esa especialidad.
    }

    // Obtiene entrenadores con paginación.
    public function getAllWithPagination($offset, $limit) {
        $sql = "SELECT * FROM {$this->table} LIMIT ?, ?";
        $stmt = $this->db->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ii", $offset, $limit); // Asocia el offset y el límite.
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return $result->fetch_all(MYSQLI_ASSOC); // Devuelve los entrenadores paginados.
            }
            $stmt->close();
        }
        return []; // No se encontraron entrenadores.
    }

    // Cuenta el número total de entrenadores.
    public function countAll() {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $result = $this->db->query($sql);
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['total']; // Devuelve el número total de entrenadores.
        }
        return 0; // En caso de error, devuelve 0.
    }

    // Busca entrenadores por nombre o especialidad.
    public function search($keyword) {
        $sql = "SELECT * FROM {$this->table} WHERE nombre LIKE ? OR especialidad LIKE ?";
        $stmt = $this->db->prepare($sql);
        if ($stmt) {
            $likeKeyword = "%{$keyword}%"; // Prepara el término de búsqueda para LIKE.
            $stmt->bind_param("ss", $likeKeyword, $likeKeyword); // Asocia el término a ambos campos.
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return $result->fetch_all(MYSQLI_ASSOC); // Devuelve los entrenadores que coinciden con la búsqueda.
            }
            $stmt->close();
        }
        return []; // No se encontraron entrenadores que coincidan con la búsqueda.
    }

    // Obtiene todas las especialidades únicas de los entrenadores.
    public function getSpecialties() {
        $sql = "SELECT DISTINCT especialidad FROM {$this->table}";
        $result = $this->db->query($sql);
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC); // Devuelve todas las especialidades únicas.
        }
        return []; // En caso de error, devuelve un array vacío.
    }

    // Obtiene un entrenador por su número de teléfono.
    public function getByPhone($telefono) {
        $sql = "SELECT * FROM {$this->table} WHERE telefono = ?";
        $stmt = $this->db->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("s", $telefono);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return $result->fetch_assoc(); // Devuelve el primer entrenador encontrado.
            }
            $stmt->close();
        }
        return null; // No se encontró ningún entrenador con ese teléfono.
    }

    // Obtiene un entrenador por su nombre.
    public function getByName($nombre) {
        $sql = "SELECT * FROM {$this->table} WHERE nombre = ?";
        $stmt = $this->db->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("s", $nombre);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return $result->fetch_assoc(); // Devuelve el primer entrenador encontrado.
            }
            $stmt->close();
        }
        return null; // No se encontró ningún entrenador con ese nombre.
    }

    // Obtiene un entrenador por su especialidad y nombre.
    public function getBySpecialtyAndName($especialidad, $nombre) {
        $sql = "SELECT * FROM {$this->table} WHERE especialidad = ? AND nombre = ?";
        $stmt = $this->db->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ss", $especialidad, $nombre);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return $result->fetch_assoc(); // Devuelve el primer entrenador encontrado.
            }
            $stmt->close();
        }
        return null; // No se encontró ningún entrenador con esa especialidad y nombre.
    }

    // Obtiene un entrenador por su especialidad y teléfono.
    public function getBySpecialtyAndPhone($especialidad, $telefono) {
        $sql = "SELECT * FROM {$this->table} WHERE especialidad = ? AND telefono = ?";
        $stmt = $this->db->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ss", $especialidad, $telefono);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return $result->fetch_assoc(); // Devuelve el primer entrenador encontrado.
            }
            $stmt->close();
        }
        return null; // No se encontró ningún entrenador con esa especialidad y teléfono.
    }

    // Obtiene un entrenador por su especialidad y email.
    public function getBySpecialtyAndEmail($especialidad, $email) {
        $sql = "SELECT * FROM {$this->table} WHERE especialidad = ? AND email = ?";
        $stmt = $this->db->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ss", $especialidad, $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return $result->fetch_assoc(); // Devuelve el primer entrenador encontrado.
            }
            $stmt->close();
        }
        return null; // No se encontró ningún entrenador con esa especialidad y email.
    }

    // Obtiene un entrenador por su especialidad, nombre y teléfono.
    public function getBySpecialtyAndNameAndPhone($especialidad, $nombre, $telefono) {
        $sql = "SELECT * FROM {$this->table} WHERE especialidad = ? AND nombre = ? AND telefono = ?";
        $stmt = $this->db->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sss", $especialidad, $nombre, $telefono);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return $result->fetch_assoc(); // Devuelve el primer entrenador encontrado.
            }
            $stmt->close();
        }
        return null; // No se encontró ningún entrenador con esa especialidad, nombre y teléfono.
    }

    // Obtiene un entrenador por su especialidad, nombre y email.
    public function getBySpecialtyAndNameAndEmail($especialidad, $nombre, $email) {
        $sql = "SELECT * FROM {$this->table} WHERE especialidad = ? AND nombre = ? AND email = ?";
        $stmt = $this->db->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sss", $especialidad, $nombre, $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return $result->fetch_assoc(); // Devuelve el primer entrenador encontrado.
            }
            $stmt->close();
        }
        return null; // No se encontró ningún entrenador con esa especialidad, nombre y email.
    }

    // Obtiene un entrenador por su especialidad, teléfono y email.
    public function getBySpecialtyAndPhoneAndEmail($especialidad, $telefono, $email) {
        $sql = "SELECT * FROM {$this->table} WHERE especialidad = ? AND telefono = ? AND email = ?";
        $stmt = $this->db->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sss", $especialidad, $telefono, $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return $result->fetch_assoc(); // Devuelve el primer entrenador encontrado.
            }
            $stmt->close();
        }
        return null; // No se encontró ningún entrenador con esa especialidad, teléfono y email.
    }

    // Obtiene un entrenador por su especialidad, nombre, teléfono y email.
    public function getBySpecialtyAndNameAndPhoneAndEmail($especialidad, $nombre, $telefono, $email) {
        $sql = "SELECT * FROM {$this->table} WHERE especialidad = ? AND nombre = ? AND telefono = ? AND email = ?";
        $stmt = $this->db->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ssss", $especialidad, $nombre, $telefono, $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return $result->fetch_assoc(); // Devuelve el primer entrenador encontrado.
            }
            $stmt->close();
        }
        return null; // No se encontró ningún entrenador con esa especialidad, nombre, teléfono y email.
    }
}