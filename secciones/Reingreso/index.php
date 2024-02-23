<?php include("../../templates/header.php"); ?>
<?php
$conexion = mysqli_connect("localhost", "root", "", "sabaticos");
if (!$conexion) {
    die("Error de conexión a la base de datos: " . mysqli_connect_error());
}
function obtenerIdUsuarioActual() {
    if (isset($_SESSION["usuario"]) && $_SESSION["tipo"] === "alumno") {
        return $_SESSION['id']; // Asegúrate de que esta sesión exista
    } else {
        header("Location: login.php");
        exit();
    }
}
// Obtener el ID del usuario actual
$usuarioId = obtenerIdUsuarioActual();

?>


<?php include("../../templates/footer.php"); ?>