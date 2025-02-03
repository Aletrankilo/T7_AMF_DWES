<?php

// Configuración de la base de datos
$servidor = "localhost";
$usuario = "root";
$contrasena = "";
$base_datos = "libros";

// Conexión a la base de datos
$conn = new mysqli($servidor, $usuario, $contrasena, $base_datos);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener listado de autores
function get_listado_autores() {
    global $conn;
    $sql = "SELECT * FROM autor";
    $resultado = $conn->query($sql);
    $autores = [];
    while ($fila = $resultado->fetch_assoc()) {
        $autores[] = $fila;
    }
    return json_encode($autores);
}

// Obtener datos de un autor y sus libros
function get_datos_autor($id) {
    global $conn;
    $sql = "SELECT * FROM autor WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $autor = $stmt->get_result()->fetch_assoc();
    
    if (!$autor) {
        return json_encode(["error" => "Autor no encontrado"]);
    }
    
    // Obtener libros del autor
    $sql = "SELECT * FROM libro WHERE id_autor = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $libros = [];
    while ($fila = $resultado->fetch_assoc()) {
        $libros[] = $fila;
    }
    
    return json_encode(["autor" => $autor, "libros" => $libros]);
}

// Obtener listado de libros
function get_listado_libros() {
    global $conn;
    $sql = "SELECT id, titulo FROM libro";
    $resultado = $conn->query($sql);
    $libros = [];
    while ($fila = $resultado->fetch_assoc()) {
        $libros[] = $fila;
    }
    return json_encode($libros);
}

// Obtener datos de un libro y su autor
function get_datos_libro($id) {
    global $conn;
    $sql = "SELECT l.id, l.titulo, l.f_publicacion, a.nombre, a.apellidos FROM libro l 
            JOIN autor a ON l.id_autor = a.id WHERE l.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $libro = $stmt->get_result()->fetch_assoc();
    
    if (!$libro) {
        return json_encode(["error" => "Libro no encontrado"]);
    }
    
    return json_encode($libro);
}

// Manejo de peticiones
if (isset($_GET['accion'])) {
    switch ($_GET['accion']) {
        case 'get_listado_autores':
            echo get_listado_autores();
            break;
        case 'get_datos_autor':
            echo get_datos_autor($_GET['id']);
            break;
        case 'get_listado_libros':
            echo get_listado_libros();
            break;
        case 'get_datos_libro':
            echo get_datos_libro($_GET['id']);
            break;
        default:
            echo json_encode(["error" => "Acción no válida"]);
    }
} else {
    echo json_encode(["error" => "No se especificó ninguna acción"]);
}

// Cerrar conexión
$conn->close();
?>
