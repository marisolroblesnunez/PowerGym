<?php
// Este archivo contiene la clase UsuarioDB, que maneja todas las interacciones con la tabla 'usuarios' en la base de datos.

// Configuración de errores de PHP.
ini_set('display_errors', 1); // Muestra los errores en pantalla (útil para desarrollo).
ini_set('log_errors', 1); // Habilita el registro de errores en un archivo.
ini_set('error_log', 'errores.log'); // Especifica el archivo donde se guardarán los errores.
error_reporting(E_ALL); // Reporta todos los tipos de errores de PHP.

/**
 * Se encarga de interactuar con la tabla 'usuarios' de la base de datos.
 * Contiene métodos para realizar operaciones CRUD (Crear, Leer, Actualizar, Borrar) y otras lógicas de negocio relacionadas con los usuarios.
 */
require_once '../config/config.php'; // Incluye la configuración general (como constantes para la URL).
require_once 'enviarCorreos.php'; // Incluye la clase para enviar correos reales.

class UsuarioDB {

    private $db; // Propiedad para almacenar la conexión a la base de datos.
    private $table = 'usuarios'; // Nombre de la tabla de usuarios en la base de datos.

    // El constructor recibe una conexión a la base de datos y la asigna a la propiedad $db.
    public function __construct($database){
        $this->db = $database->getConexion();
    }

    // Obtiene todos los registros de la tabla de usuarios.
    public  function getAll(){
        $sql = "SELECT * FROM {$this->table}"; // Prepara la consulta SQL.
        $resultado = $this->db->query($sql); // Ejecuta la consulta.

        if($resultado && $resultado->num_rows > 0){ // Si la consulta fue exitosa y devolvió filas.
            $usuarios = []; // Inicializa un array para guardar los usuarios.
            while($row = $resultado->fetch_assoc()){ // Itera sobre cada fila del resultado.
                $usuarios[] = $row; // Añade la fila (usuario) al array.
            }
            return $usuarios; // Devuelve el array de usuarios.
        }else{
            return []; // Si no hay usuarios, devuelve un array vacío.
        }
    }

    // Obtiene un usuario específico por su ID.
    public function getById($id){
        $sql = "SELECT * FROM {$this->table} WHERE id = ?"; // Consulta SQL con un marcador de posición para el ID.
        $stmt = $this->db->prepare($sql); // Prepara la consulta para evitar inyección SQL.
        if($stmt){
            $stmt->bind_param("i", $id); // Asocia el parámetro ID (como entero) a la consulta.
            $stmt->execute(); // Ejecuta la consulta preparada.
            $result = $stmt->get_result(); // Obtiene el resultado.

            if($result->num_rows > 0){ // Si se encontró un usuario.
                return $result->fetch_assoc(); // Devuelve los datos del usuario como un array asociativo.
            }
            $stmt->close(); // Cierra la sentencia preparada.
        }
        return null; // Si no se encuentra el usuario o hay un error, devuelve null.
    }
   
    // Busca un usuario por su dirección de email.
    public function getByEmail($email){
        $sql = "SELECT * FROM {$this->table} where email = ?"; // Consulta SQL con marcador para el email.
        $stmt = $this->db->prepare($sql);
        if($stmt){
            $stmt->bind_param("s",$email); // Asocia el email (como string) al parámetro.
            $stmt->execute();
            $result = $stmt->get_result(); 
            
            if($result->num_rows > 0){ // Si se encuentra un usuario con ese email.
                $usuario = $result->fetch_assoc();
                $stmt->close();
                return $usuario; // Devuelve los datos del usuario.
            }
            $stmt->close();
        }
        return null; // Si no se encuentra, devuelve null.
    }

    /**
     * Registra un nuevo usuario en la base de datos.
     */
    public function registrarUsuario($email, $password, $verificado = 0){
        $password = password_hash($password, PASSWORD_DEFAULT); // Hashea la contraseña para almacenarla de forma segura.
        $token = $this->generarToken(); // Genera un token único para la verificación del correo.

        $existe = $this->correoExiste($email); // Comprueba si el email ya está registrado.

        $sql = "INSERT INTO usuarios (nombre, apellido, email, password, token, token_recuperacion, verificado, intentos_fallidos, bloqueado, tipo) VALUES('', '', ?, ?, ?, '', 0, 0, 0, 0)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sss", $email, $password, $token); // Asocia los parámetros a la consulta.

        if(!$existe){ // Si el correo no existe.
            if($stmt->execute()){ // Si la inserción es exitosa.
                $mensaje_email = "Por favor, verifica tu cuenta haciendo clic en este enlace: " . URL_ADMIN . "/verificar.php?token=$token";
                
                if (USAR_EMAIL_REAL) { // Comprueba si se debe enviar un correo real (configurado en config.php).
                    $mensaje = Correo::enviarCorreo($email, "Cliente", "Verificación de cuenta", $mensaje_email);
                } else { // Si no, simula el envío.
                    $mensaje = $this->enviarCorreoSimulado($email, "Verificación de cuenta", $mensaje_email);
                }
            }else{
                $mensaje = ["success" => false, "mensaje" => "Error en el registro: " . $stmt->error];
            }
        }else{ // Si el correo ya existe.
            $mensaje = ["success" => false, "mensaje" => "Ya existe una cuenta con ese email"];
        }

        return $mensaje; // Devuelve el resultado de la operación.
    }

    // Genera un token criptográficamente seguro.
    public function generarToken(){
        return bin2hex(random_bytes(32)); // Crea un token hexadecimal de 64 caracteres.
    }

    // Comprueba si un correo electrónico ya existe en la base de datos.
    public function correoExiste($correo, $excludeId = null){
        $sql = "SELECT id FROM {$this->table} WHERE email = ?"; // Consulta base.
        $params = [$correo]; // Parámetros para la consulta.
        $types = "s"; // Tipos de parámetros (string).

        if($excludeId){ // Si se proporciona un ID para excluir (útil al actualizar un usuario).
            $sql .= " AND id != ?";
            $params[] = $excludeId;
            $types .= "i"; // Añade el tipo entero para el ID.
        }

        $stmt = $this->db->prepare($sql);
        if($stmt){
            $stmt->bind_param($types, ...$params); // Usa el operador splat para pasar los parámetros.
            $stmt->execute();
            $result = $stmt->get_result();
            $exists = $result->num_rows > 0; // Devuelve true si hay al menos una fila, false si no.
            $stmt->close();
            return $exists;
        }
        return false;
    }

    // Simula el envío de un correo electrónico guardándolo en un archivo de log.
    public function enviarCorreoSimulado($destinatario, $asunto, $mensaje){        
        $archivo_log = __DIR__ . '/correos_simulados.log'; // Ruta del archivo de log.
        $contenido = "Fecha: " . date('Y-m-d H:i:s'. "\n");
        $contenido .= "Para: $destinatario\n";
        $contenido .= "Asunto: $asunto\n";
        $contenido .= "Mensaje:\n$mensaje\n";
        $contenido .= "__________________________________________\n\n";

        file_put_contents($archivo_log, $contenido, FILE_APPEND); // Añade el contenido al final del archivo.

        return ["success" => true, "mensaje" => "Registro exitoso. Por favor, verifica tu correo"];
    }

    // Verifica las credenciales de un usuario (email y contraseña) para el inicio de sesión.
    public function verificarCredenciales($email, $password){
        $usuario = $this->getByEmail($email); // Obtiene el usuario por su email.

        if(!$usuario){ // Si el usuario no existe.
            return ['success' => false, 'mensaje' => 'Usuario no encontrado'];
        }

        if($usuario['bloqueado'] == 1){ // Si la cuenta está bloqueada.
            return['success'=> false, 'mensaje' => 'Usuario bloqueado'];
        }

        if($usuario['verificado'] === 0){ // Si la cuenta no ha sido verificada por correo.
            return ['success' => false, 'mensaje' => 'Verifica tu correo'];
        }

        if(!password_verify($password, $usuario['password'])){ // Si la contraseña no coincide con el hash almacenado.
            return ['success' =>false, 'mensaje' =>'Contraseña incorrecta'];
        }

        // Si las credenciales son correctas, actualiza la fecha del último acceso.
        $this->actualizarUltimoAcceso($usuario['id']);
       
        // Elimina datos sensibles antes de devolver la información del usuario.
        unset($usuario['password']);
        unset($usuario['token']);
        unset($usuario['token_recuperacion']);

        return ['success' => true, 'usuario'=> $usuario, 'mensaje' =>'Login correcto'];
    }

    // Actualiza la columna 'ultima_conexion' con la fecha y hora actuales.
    public function actualizarUltimoAcceso($id){
        $sql = "UPDATE {$this->table} SET ultima_conexion = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        if($stmt){
            $stmt->bind_param("i", $id);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        }
        return false;
    }

    // Verifica un token de activación de cuenta.
    public function verificarToken($token){
        $sql = "SELECT id FROM usuarios WHERE token = ? AND verificado = 0"; // Busca un usuario con el token y que no esté verificado.
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows === 1){ // Si se encuentra exactamente un usuario.
            $row = $result->fetch_assoc();
            $user_id = $row['id'];
            
            // Actualiza el estado a verificado y limpia el token.
            $update_sql = "UPDATE usuarios SET verificado = 1, token = '' WHERE id= ?";
            $update_stmt = $this->db->prepare($update_sql);
            $update_stmt->bind_param("i", $user_id);

            if($update_stmt->execute()){
                $resultado = ["success" => true, "mensaje" => "Tu cuenta ha sido verificada. Ahora puedes iniciar sesión"];
            } else {
                $resultado = ["success" => false, "mensaje" => "Hubo un error al verificar tu cuenta. Por favor, intenta de nuevo más tarde"];
            }
        }else{ // Si el token no es válido o ya fue usado.
            $resultado = ["success" => false, "mensaje" => "Token no válido"];
        }
        return $resultado;
    }    

    // Inicia el proceso de recuperación de contraseña.
    public function recuperarPassword($email){
        $existe = $this->correoExiste($email); // Comprueba si el correo existe.
        $resultado = ["success" => false, "mensaje" => "El correo electrónico proporcionado no corresponde a ningún usuario registrado."];

        if($existe){ // Si el correo existe.
            $token = $this->generarToken(); // Genera un nuevo token de recuperación.

            $sql = "UPDATE usuarios SET token_recuperacion = ? WHERE email = ?"; // Almacena el token en la base de datos.
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("ss", $token, $email);

            if($stmt->execute()){
                $mensaje_email = "Para restablecer tu contraseña, haz click en este enlace: " . URL_ADMIN . "/restablecer.php?token=$token";
                
                if (USAR_EMAIL_REAL) {
                    $mensaje = Correo::enviarCorreo($email, "Cliente", "Restablecer Contraseña", $mensaje_email);
                } else {
                    $this->enviarCorreoSimulado($email, "Recuperación de contraseña", $mensaje_email);
                    $resultado = ["success" => true, "mensaje" => "Se ha enviado un enlace de recuperación a tu correo (simulado)"];
                }
            }
        }else{
            $resultado = ["success" => false, "mensaje" => "Error al procesar la solicitud"];
        }
        return $resultado;
    }

    // Restablece la contraseña de un usuario usando un token de recuperación.
    public function restablecerPassword($token, $nueva_password){
        $password = password_hash($nueva_password, PASSWORD_DEFAULT); // Hashea la nueva contraseña.
        $sql = "SELECT id FROM usuarios WHERE token_recuperacion = ?"; // Busca al usuario con el token.
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        $resultado = ["success" => false, "mensaje" => "El token de recuperación no es válido o ya ha sido utilizado"];

        if($result->num_rows === 1){ // Si el token es válido.
            $row = $result->fetch_assoc();
            $user_id = $row['id'];

            // Actualiza la contraseña y elimina el token para que no se pueda reutilizar.
            $update_sql = "UPDATE usuarios SET password = ?, token_recuperacion = NULL WHERE id = ?";
            $update_stmt = $this->db->prepare($update_sql);
            $update_stmt->bind_param("si", $password, $user_id);

            if($update_stmt->execute()){
                $resultado = ["success" => true, "mensaje" => "Tu contraseña ha sido actualizada correctamente"];
            }else{
                $resultado = ["success" => false, "mensaje" => "Hubo un error al actualizar tu contraseña. Por favor, intenta de nuevo más tarde"];
            }
        }
        return $resultado;
    }
}