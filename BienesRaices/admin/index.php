<?php

    require '../includes/funciones.php';    

    $auth = estaAutenticado();

    if(!$auth) {
        header('Location: /bienesraices/index.php');
    }

    //Importamos la DB
    require '../includes/config/database.php';
    $db = conectarDB();

    //Escribir el query
    $query = "SELECT * FROM propiedades;";

    //Consultarla
    $propiedades = mysqli_query($db, $query);

    //Muestra el resultado de un insert
    $resultado = $_GET['resultado'] ?? null;


    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if($id) {

            //Eliminar la imagen
            $query = "SELECT imagen FROM propiedades WHERE id = $id";

            $resultado = mysqli_query($db, $query);

            $propiedad = mysqli_fetch_assoc($resultado); 

            unlink('../imagenes/' . $propiedad['imagen']);

            $query = "DELETE FROM propiedades WHERE id = $id;";

            $resultado = mysqli_query($db, $query);

            if($resultado) {
                header('location: /bienesraices/admin/index.php?resultado=3');
            }
        }
    }

    //Incluye un template
    
    incluirTemplate('header');

?>
    <main class="contenedor seccion">
        <h1>Administrador de Bienes Raices</h1>
        <?php if($resultado === '1'): ?>
            <p class="alerta exito">Anuncio Creado Correctamente</p>
        <?php elseif($resultado === '2'): ?>
            <p class="alerta actualizado">Anuncio Actualizado Correctamente</p>
        <?php elseif($resultado === '3'): ?>
        <p class="alerta actualizado">Anuncio Eliminado Correctamente</p>
        <?php endif;?>
        
        <a href="/bienesraices/admin/propiedades/crear.php" class="boton boton-verde">Nueva propiedad</a>
    </main>
    
    <table class="propiedades contenedor">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titulo</th>
                <th>Imagen</th>
                <th>Precio</th>  
                <th>Acciones</th>
            </tr>
        </thead>
        <!-- Mostramos los resultados de la DB -->
        <tbody>
            <?php while($propiedad = mysqli_fetch_assoc($propiedades)) : ?>
            <tr>
                <td><?php echo $propiedad['id'] ?></td>
                <td><?php echo $propiedad['titulo']; ?></td>
                <td><img src="/bienesraices/imagenes/<?php echo $propiedad['imagen']; ?>" class="imagen-tabla" ></td>
                <td>$ <?php echo $propiedad['precio']; ?></td>
                <td>
                    <form method="POST" class="w-100">
                        <input type="hidden" name="id" value="<?php echo $propiedad['id'];?>">
                        <input type = "submit" class="boton-rojo-block" value="Eliminar">
                    </form>
                    <a href="./propiedades/actualizar.php?id=<?php echo $propiedad['id'] ?>" class="boton-amarillo-block">Actualizar</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

<!-- Cerrar la conexion -->
<?php 
    mysqli_close($db);
    incluirTemplate('footer'); 
?>
