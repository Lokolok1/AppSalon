<h1 class="nombre-pagina">Panel de Administración</h1>

<?php include_once __DIR__ . "/../templates/barra.php"; ?>

<h2>Buscar Citas</h2>
<div class="busqueda">
    <form class="formulario">
        <div class="campo">
            <label for="fecha">Fecha:</label>
            <input type="date" id="fecha" name="fecha" value="<?php echo $fecha; ?>">
        </div>
    </form>
</div>

<?php 
    if (count($citas) === 0) {
        echo "<h2>No Hay Citas para esta Fecha :(</h2>";
    }
?>

<div id="citas-admin">
    <ul class="citas">
        <?php
            $idCita = -1;
            foreach ($citas as $key => $cita):
                if ($idCita !== $cita->id):
                    $total = 0;
        ?>

        <li>
            <h3>Información de la Cita</h3>

            <p>ID: <span><?php echo $cita->id;?></span></p>
            <p>Hora: <span><?php echo $cita->hora;?></span></p>
            <p>Cliente: <span><?php echo $cita->cliente;?></span></p>
            <p>Correo:: <span><?php echo $cita->correo;?></span></p>
            <p>Telefono:: <span><?php echo $cita->telefono;?></span></p>

            <h3>Servicios</h3>

            <?php 
                $idCita = $cita->id; 
                endif;
            ?>

            <p class="servicio"><?php echo $cita->servicio . " - $" . $cita->precio; ?></p>
            <?php $total += $cita->precio; ?>

        <?php
            $actual = $cita->id;
            $proximo = $citas[$key + 1]->id ?? 0;

            if (esUltimo($actual, $proximo)) :
        ?>
            <p class="total">Total: <span>$<?php echo $total; ?>.00</span></p>

            <form action="/api/eliminar" method="POST" id="eliminar">
                <input type="hidden" name="id" value="<?php echo $cita->id; ?>">
                <input type="submit" class="boton-eliminar" value="Eliminar Cita">
            </form>
        <?php
            endif;
            endforeach;
        ?>
    </ul>
</div>

<?php
    $script = "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script src='build/js/buscador.js'></script>
    ";
?>