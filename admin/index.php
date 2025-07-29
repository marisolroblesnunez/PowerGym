<?php
// Este archivo es el panel de control principal para la administración del gimnasio futura.
//Actualmente muestra información de libros de una biblioteca, pero lo he dejado como ejemplo, para poder adaptaarlo a la funcionalidad del adiministrador del gimnasio.
// Permite a los usuarios autenticados gestionar diversas funcionalidades, como la creación de nuevos elementos (actualmente "libros", que podría ser un placeholder para clases, entrenadores, etc.),
// y muestra información relevante en tablas dinámicas.
// También se encarga de la verificación de sesión para asegurar que solo los administradores puedan acceder.

//todo comprobar si el usuario esta logueado y si no esta logueado lo mandamos a login

if(session_status() == PHP_SESSION_NONE){ //este if se escribe para poder leer las variables superglobales que hemos creado en la carpeta usuarioController.php
    session_start();
}

///si no metes datos, redirigeme a login que es donde tengo el formulario//o si no esta logueado, redirigelo tambien a login
if(!isset($_SESSION['logueado']) || !$_SESSION['logueado'] || !isset($_SESSION['usuario']['tipo']) || $_SESSION['usuario']['tipo'] != 1){
  header("Location: ../login.php"); // Redirige al login general
  exit();
}

if(isset($_SESSION['mensaje'])){    /////////si está logueado, muestrame el mensaje y luego borramelo 
  echo '<div>' . $_SESSION['mensaje']. '</div>';
  unset($_SESSION['mensaje']);/////unset significa borrame el mensaje 
}

echo '<button id="cerrarSesion">Cerrar Sesión</button>'; //// le pongo el id porque la funcion del boton la tengo que hacer desde javascript

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de control</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <div class="container">
<h1>Panel de control</h1>
<div class="panelCrear">
<button id="crear"class="btn-crear">Crear un nuevo libro</button>
</div>
<!-- enctype="multipart/form-data" se utiliza cuando queremos subir archivos-->
<form method= "POST" enctype="multipart/form-data"> 
    <h2> 📚Nuevo libro</h2>
    
    
<div class="form-group">
<label for="titulo">Titulo</label>
<input type="text" id="titulo" name="titulo" required>
<small class="error" id="error-titulo"></small>
</div>

<div class="form-group">
<label for="autor">Autor</label>
<input type="text" id="autor" name="autor" required>
<small class="error" id="error-autor"></small>
</div>
<div class="form-group">
<label for="genero">Género</label>
<input type="text" id="genero" name="genero">
</div>
<div class= form-group>
<label for="fecha_publicacion">Fecha de publicación</label>
<input type="number" id="fecha_publicacion" name="fecha_publicacion" min="1000">
<small class="error" id="error-publicacion"></small>
</div>
<div class= form-group>
<label for="imagen">Imagen</label>
<input type="file" id="imagen" name="img" accept="image/*">
<small class="error" id="error-imagen"></small>
</div>
<div class="checkbox-group">
<input type="checkbox" id="disponible" name="disponible">
<label for ="disponible">Disponible</label>
</div>

<div class="checkbox-group">
<input type="checkbox" id="favorito" name="favorito">
<label for="favorito">Favorito</label>
</div>

<div class="form-group">
<label for="resumen">Resumen</label>
<textarea name="resumen" id="resumen" rows="6" placeholder="Escribe un brebe resumen del libro..."></textarea> 
<small class="error" id="error-resumen" ></small>
</div>


<button type="submit" id="btnGuardar">Guardar libro</button>
</form>
<table class="tablaLibros" id="tablaLibros"></table>
 </div>
<!-- 
 <div id="modal" class="modal hidden">
      <div class="modal-contenido">
        <span class="cerrar">&times;</span>
        <img id="modal-imagen"  alt="Portada libro">
        <p id="modal-info"></p>
      </div>
    </div> -->
    
   <script src="js/funciones.js"></script>
   <script src="js/sesiones.js"></script>
</body>
</html>