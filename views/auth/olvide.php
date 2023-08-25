<h1 class="nombre-pagina">Olvide mi Contraseña</h1>
<p class="descripcion-pagina">Reestablece tu contraseña escribiendo tu correo a continuación</p>

<?php include_once __DIR__ . "/../templates/alertas.php"; ?>

<form class="formulario" method="POST" action="/olvide">
    <div class="campo">
        <label for="correo">Correo:</label>
        <input type="email" id="correo" placeholder="Tu Correo" name="correo">
    </div>

    <input type="submit" value="Recuperar Contraseña" class="boton">
</form>

<div class="acciones">
    <a href="/">Iniciar sesión</a>
    <a href="/crear-cuenta">Crear una cuenta</a>
</div>