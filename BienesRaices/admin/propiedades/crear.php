<?php
    require '../../includes/funciones.php';

    $auth = estaAutenticado();

    if(!$auth) {
        header('Location: /bienesraices/index.php');
    }
    //Base de Datos
    require '../../includes/config/database.php';

    $db = conectarDB();

    //Consultar los vendedores
    $consulta = "SELECT * FROM vendedores;"; 

    $resultado = mysqli_query($db, $consulta);


    //Arreglo con mensaje de errores
    $errores = [];

    $titulo = '';
    $precio = '';
    $descripcion = '';
    $habitaciones = '';
    $wc = '';
    $estacionamiento = '';
    $vendedorId = '';
    $creado = date('Y/m/d');

    //Establecer variables y Insertar en la BD
    if($_SERVER["REQUEST_METHOD"] === 'POST') {
        
        $titulo = mysqli_real_escape_string($db, $_POST['titulo']);
        $precio = mysqli_real_escape_string($db, $_POST['precio']);
        $descripcion = mysqli_real_escape_string($db, $_POST['descripcion']);
        $habitaciones = mysqli_real_escape_string($db, $_POST['habitaciones']);
        $wc = mysqli_real_escape_string($db, $_POST['wc']);
        $estacionamiento = mysqli_real_escape_string($db, $_POST['estacionamiento']);
        $vendedorId = mysqli_real_escape_string($db, $_POST['vendedorId']);
 
        $imagen = $_FILES['imagen'];

        if(!$titulo) {
            $errores[] = 'Debes añadir un titulo';
        }
        if(!$precio) {
            $errores[] = 'El precio es obligatorio';
        }
        if(strlen($descripcion) < 50) {
            $errores[] = 'La descripcion es obligatoria y debe tener al menos 50 caracteres';
        }
        if(!$habitaciones) {
            $errores[] = 'El numero de habitaciones es obligatorio';
        }
        if(!$wc) {
            $errores[] = 'El numero de baños es obligatorio';
        }
        if(!$estacionamiento) {
            $errores[] = 'El numero de estacionamiento es obligatorio';
        }
        if(!$vendedorId) {
            $errores[] = 'Elige un vendedor';
        }
        if(!$imagen['name'] || $imagen['error']) {
            $errores[] = 'La imagen es obligatoria';
        }
        
        //Establecer un tamaño maximo de las imagenes
        $medida = 1000 * 1000;
        
        if($imagen['size'] > $medida) {
            $errores[] = 'La imagen es muy pesada'; 
        }
        

       //Permite que no se ejecute la insersion a la BD

       if(empty($errores)) {

        // Subir archivos

        // Crear Carpeta
        $carpetaImagenes = '../../imagenes/';

        if(!is_dir($carpetaImagenes)) {
            mkdir($carpetaImagenes);
        };

        //Generar nombre unico

        $nombreImagen = md5( uniqid(rand(), true) ) . '.jpg';

        move_uploaded_file($imagen['tmp_name'],$carpetaImagenes . $nombreImagen);

        // Insertar en la base de datos
        $query = "INSERT INTO propiedades (titulo, precio, imagen, descripcion, habitaciones, wc, estacionamiento, creado, vendedores_id) VALUES ( '$titulo', '$precio', '$nombreImagen', '$descripcion', '$habitaciones', '$wc', '$estacionamiento', '$creado', '$vendedorId');";

        $resultado = mysqli_query($db, $query);

        if($resultado) {
            echo 'Insertado correctamente';

            header('Location: /bienesraices/admin/index.php?resultado=1');
        }
       };

        
    }

    incluirTemplate('header');

?>
    <main class="contenedor seccion">
        <h1>Crear</h1>

        <a href="/bienesraices/admin/index.php" class="boton boton-verde">Volver</a>

        <?php foreach($errores as $error): ?>
            <div class="alerta error">
                <?php echo $error ?>
            </div>
        <?php endforeach; ?>

        <form action="" class="formulario" method="POST" action="/admin/propiedades/crear.php" enctype="multipart/form-data">
            <fieldset>
                <legend>Información General</legend>

                <label for="titulo">Titulo:</label>
                <input type="text" name="titulo" placeholder="Titulo Propiedad" id="titulo" value="<?php echo $titulo; ?>">
                
                <label for="precio">Precio:</label>
                <input type="number" name="precio" placeholder="Precio Propiedad" id="precio" value="<?php echo $precio; ?>">
                
                <label for="imagen">Imagen:</label>
                <input type="file" id="imagen" accept="image/jpeg, image/png" name="imagen">

                <label for="descripcion">Descripcion:</label>
                <textarea id="descripcion" name="descripcion" ><?php echo $descripcion; ?></textarea>
               
            </fieldset>

            <fieldset>
                <legend>Información Propiedad</legend>

                <label for="habitaciones">Habitacion:</label>
                <input type="number" id="habitaciones" name="habitaciones" placeholder="Ej: 3" min="1" max="9" value="<?php echo $habitaciones; ?>">

                <label for="wc">Baños:</label>
                <input type="number" id="wc" name="wc" placeholder="Ej: 3" min="1" max="9" value="<?php echo $wc; ?>">

                <label for="estacionamiento">Estacionamiento:</label>
                <input type="number" id="estacionamiento" name="estacionamiento" placeholder="Ej: 3" min="1" max="9" value="<?php echo $estacionamiento; ?>">
            </fieldset>

            <fieldset>
                <legend>Vendedor</legend>
                <select name="vendedorId">
                    <option value="">--Seleccione--</option>
                    <?php while($vendedor = mysqli_fetch_assoc($resultado)) : ?>
                        <option 
                        <?php echo $vendedorId === $vendedor['id'] ? 'selected' : ''; ?>
                        value="<?php echo $vendedor['id']; ?>"> 
                        <?php echo $vendedor['nombre'] . " " .  $vendedor['apellido']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </fieldset>

            <input type="submit" value="Crear Propiedad" class="boton-verde">

        </form>
    </main>
    

<?php incluirTemplate('footer'); ?>
