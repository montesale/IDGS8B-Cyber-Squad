<?php
include("./bd.php");

// Variables para almacenar mensajes de éxito o error
$mensaje_exito = "";
$mensaje_error = "";

// Procesar el formulario de agregar
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $usuario = $_POST["usuario"];
    $password = $_POST["password"];
    $correo = $_POST["correo"];
    // Establecer el tipo como "alumno"
    $tipo = "alumno";

    // Validación de la contraseña
    if (strlen($password) < 8 || !preg_match("/[A-Z]/", $password)) {
        $mensaje_error = "La contraseña debe tener al menos 8 caracteres y al menos una letra mayúscula.";
    } else {
        // Validación del correo electrónico utilizando expresión regular
        if (!preg_match('/^[A-z0-9\._-]+@[A-z0-9][A-z0-9-]* (\.[A-z0-9_-]+)*\.([A-z]{2,6})$/', $correo)) {
            $mensaje_error = "Formato de correo electrónico inválido.";
        } else {
            // Realizar la conexión a la base de datos (ya incluido en bd.php)
            // Escapar los valores para evitar inyección de SQL
            $usuario = mysqli_real_escape_string($conexion, $usuario);
            $password = mysqli_real_escape_string($conexion, $password);
            $correo = mysqli_real_escape_string($conexion, $correo);

            // Crear la sentencia SQL de inserción
            $sql = "INSERT INTO usuario (usuario, password, correo, tipo) VALUES ('$usuario', '$password', '$correo', '$tipo')";

            // Ejecutar la sentencia SQL
            if (mysqli_query($conexion, $sql)) {
                // Registro agregado correctamente
                $mensaje_exito = "El registro se agregó correctamente.";
                // Obtener el ID del usuario registrado
                $id_usuario = mysqli_insert_id($conexion);
                // Guardar el ID en una variable de sesión
                $_SESSION["id_usuario"] = $id_usuario;
            } else {
                // Error al ejecutar la sentencia SQL
                $mensaje_error = "Error al agregar el registro: " . mysqli_error($conexion);
            }
        }
    }

    // Cerrar la conexión a la base de datos (ya incluido en bd.php)
    mysqli_close($conexion);
}
?>

<!doctype html>
<html lang="en">
<head>
  <title>Title</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <script
  src="https://code.jquery.com/jquery-3.6.4.min.js"
  integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8="
  crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
  <script src="http://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="imagenes/">
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<style>
  body {
    background-color: #e4e4f3;
    background-color: #e4e4f3;
  }
  .btn-primary {
      background-color: blue;
      color: white;
    }
    .btn-success {
      background-color: #228B22;
      color: white;
    }
    .card{
      background-color: #e4e4f3;
    }
</style>
<header>
        <div class="logo">
          <img src="imagenes/logo-universidad-tecnologica-santa-catarina-PhotoRoom.png" alt="Logotipo de la página">
        </div>
        <nav>
        <ul>
                <li><a href="inicio.php">Inicio</a></li>
                <li><a href="registrarse.php">Registrarse</a></li>
                <li><a href="login.php">Inicio de session</a></li>
                <li><a href="Faq.php">FAQ</a></li>
          </ul>
        </nav>
      </header>
  <main class="container">
<br>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <div class="form-group">
        <label for="usuario">Nombre de usuario:</label>
        <input type="text" name="usuario" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="password">Contraseña:</label>
        <div style="position: relative;">
         <input type="password" name="password" class="form-control" required>
         <?php if (!empty($mensaje_error) && strlen($password) < 8): ?>
            <p class="text-danger">La contraseña debe tener al menos 8 caracteres.</p>
         <?php elseif (!empty($mensaje_error) && !preg_match("/[A-Z]/", $password)): ?>
            <p class="text-danger">La contraseña debe contener al menos una letra mayúscula.</p>
         <?php endif; ?>
           <!-- Icono para mostrar/ocultar la contraseña -->
           <button type="button" onclick="mostrarContrasena()" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%);">
            <i class="fas fa-eye"></i>
        </button>
        </div>
    </div>
    <script>
     function mostrarContrasena() {
     const inputPassword = document.querySelector('input[name="password"]');
     if (inputPassword.type === "password") {
        inputPassword.type = "text";
      } else {
        inputPassword.type = "password";
       }
      }
    </script>
    
     <div class="form-group">
         <label for="correo">Correo electrónico:</label>
         <input type="email" name="correo" class="form-control" required>
         <?php if (!empty($mensaje_error)): ?>
             <p class="text-danger"><?php echo $mensaje_error; ?></p>
         <?php endif; ?>
     </div>
     <button type="submit" name="register" class="btn btn-primary">Registrarse</button>
     
     
</form>

<!-- Mostrar mensaje de éxito -->
<?php
if (!empty($mensaje_exito)) {
    echo "<p class='text-success'>$mensaje_exito</p>";
}
?>
</main>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
    integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
  </script>
</body>
</html>