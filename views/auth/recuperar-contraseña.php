<h1 class="nombre-pagina">Recuperar mi Contraseña</h1>
<p class="descripcion-pagina">Coloca tu nueva contraseña a continuación</p>

<?php include_once __DIR__ . "/../templates/alertas.php"; ?>

<?php if ($error) return; ?>

<form class="formulario" method="POST">
    <div class="campo">
        <label for="contraseña">Contraseña:</label>
        <input type="password" id="contraseña" placeholder="Tu Nueva Contraseña" name="contraseña">
    </div>

    <input type="submit" value="Guardar Nueva Contraseña" class="boton">
</form>

<div class="acciones">
    <a href="/">Iniciar sesión</a>
    <a href="/crear-cuenta">Crear una cuenta</a>
</div>