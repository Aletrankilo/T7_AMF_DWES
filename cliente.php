<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API DWES</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div class="container">
        <h2>Listado de Autores</h2>
        <ul>
            <?php
                $autores = file_get_contents('http://localhost/T7_AMF_DWES/api.php?accion=get_listado_autores');
                $autores = json_decode($autores);
                
                foreach ($autores as $autor) {
                    echo "<li><a href='?accion=get_datos_autor&id={$autor->id}'>{$autor->nombre} {$autor->apellidos}</a></li>";
                }
            ?>
        </ul>

        <h2>Listado de Libros</h2>
        <ul>
            <?php
                $libros = file_get_contents('http://localhost/T7_AMF_DWES/api.php?accion=get_listado_libros');
                $libros = json_decode($libros);
                
                foreach ($libros as $libro) {
                    echo "<li><a href='?accion=get_datos_libro&id={$libro->id}'>{$libro->titulo}</a></li>";
                }
            ?>
        </ul>

        <?php
        if (isset($_GET["accion"]) && isset($_GET["id"]) && $_GET["accion"] == "get_datos_autor") {
            $datos_autor = file_get_contents('http://localhost/T7_AMF_DWES/api.php?accion=get_datos_autor&id=' . $_GET["id"]);
            $datos_autor = json_decode($datos_autor);
            echo "<h2>Datos del Autor</h2>";
            echo "<p><strong>Nombre:</strong> {$datos_autor->autor->nombre} {$datos_autor->autor->apellidos}</p>";
            echo "<p><strong>Nacionalidad:</strong> {$datos_autor->autor->nacionalidad}</p>";
            echo "<h3>Libros:</h3><ul>";
            foreach ($datos_autor->libros as $libro) {
                echo "<li>{$libro->titulo} ({$libro->f_publicacion})</li>";
            }
            echo "</ul>";
        }

        if (isset($_GET["accion"]) && isset($_GET["id"]) && $_GET["accion"] == "get_datos_libro") {
            $datos_libro = file_get_contents('http://localhost/T7_AMF_DWES/api.php?accion=get_datos_libro&id=' . $_GET["id"]);
            $datos_libro = json_decode($datos_libro);
            echo "<h2>Datos del Libro</h2>";
            echo "<p><strong>Título:</strong> {$datos_libro->titulo}</p>";
            echo "<p><strong>Fecha de publicación:</strong> {$datos_libro->f_publicacion}</p>";
            echo "<p><strong>Autor:</strong> {$datos_libro->nombre} {$datos_libro->apellidos}</p>";
        }
        ?>
        
        <a class="back-link" href="cliente.php">Volver al inicio</a>
    </div>
</body>
</html>
