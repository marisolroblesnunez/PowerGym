<?php
// Este archivo define la clase Database para gestionar la conexión con la base de datos.

//importamos el archivo config.php
require_once 'config.php'; // Importa las constantes de configuración de la base de datos.

//clase  para establecer la conexión con la base de datos
class Database { // Define la clase para la conexión a la base de datos.
    //Definimos los atributos
    //Les ponemos el valor de las constantes de config.php
    private $host = DB_HOST; // Host de la base de datos (ej: 'localhost').
    private $username = DB_USER; // Nombre de usuario de la base de datos.
    private $password = DB_PASS; // Contraseña de la base de datos.
    private $database = DB_NAME; // Nombre de la base de datos.
    //guarda la conexion con la base de datos
    //la conexión con la base de datos es un objeto de tipo mysqli
    private $conexion; // Propiedad para almacenar el objeto de conexión mysqli.

    public function __construct() // Constructor de la clase.
    {
        $this->connect(); // Llama al método de conexión al instanciar la clase.
    }

    //Abre la conexión con la base de datos
    private function connect(){ // Método privado para establecer la conexión.
        $this->conexion = new mysqli($this->host, $this->username, $this->password, $this->database); // Crea un nuevo objeto mysqli para la conexión.

        if($this->conexion->connect_error){ // Comprueba si hubo un error en la conexión.
            die("Error de conexión: " . $this->conexion->connect_error); // Termina la ejecución y muestra el error si la conexión falla.
        }

        $this->conexion->set_charset("utf8"); // Establece el juego de caracteres a UTF-8.
    }

    public function getConexion(){ // Método público para obtener el objeto de conexión.
        return $this->conexion; // Devuelve la conexión activa.
    }

    public function close(){ // Método público para cerrar la conexión.
        if($this->conexion){ // Comprueba si existe una conexión activa.
            $this->conexion->close(); // Cierra la conexión a la base de datos.
        }
    }
}