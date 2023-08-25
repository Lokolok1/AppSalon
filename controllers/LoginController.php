<?php

namespace Controllers;

use Clases\Correo;
use MVC\Router;
use Model\Usuario;

class LoginController {
    public static function login(Router $router) {
        $auth = new Usuario;
        $alertas = [];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $auth = new Usuario($_POST);

            $alertas = $auth->validarLogin();

            if (empty($alertas)) {
                // Comprobar que el usuario exista
                $usuario = Usuario::where("correo", $auth->correo);

                if ($usuario) {
                    // Verificar la contraseña
                    if ($usuario->comprobarPasswordAndVerificado($auth->contraseña)) {
                        // Autenticar al usuario
                        if(!isset($_SESSION)) {
                            session_start();
                        };

                        $_SESSION["id"] = $usuario->id;
                        $_SESSION["nombre"] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION["correo"] = $usuario->correo;
                        $_SESSION["login"] = true;

                        // Redireccionamiento
                        if ($usuario->admin === "1") {
                            $_SESSION["admin"] = $usuario->admin ?? null;
                            header("Location: /admin");
                        } else {
                            header("Location: /cita");
                        }
                    }
                } else {
                    Usuario::setAlerta("error", "Usuario no encontrado");
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render("auth/login", [
            "auth" => $auth,
            "alertas" => $alertas
        ]);
    }

    public static function logout() {
        if(!isset($_SESSION)) {
            session_start();
        }
        $_SESSION = [];
        header("Location: /");
    }

    public static function olvide(Router $router) {
        $alertas = [];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarCorreo();

            if (empty($alertas)) {
                $usuario = Usuario::where("correo", $auth->correo);

                if ($usuario && $usuario->confirmado === "1") {
                    // Generar un nuevo token
                    $usuario->crearToken();
                    $usuario->guardar();
                    Usuario::setAlerta("exito", "Revise su correo");

                    // Enviar el correo al usuario
                    $correo = new Correo($usuario->correo, $usuario->nombre, $usuario->token);
                    $correo->enviarInstrucciones();
                } else {
                    Usuario::setAlerta("error", "El usuario no existe o no esta confirmado");
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render("auth/olvide", [
            "alertas" => $alertas
        ]);
    }

    public static function recuperar(Router $router) {
        $error = false;
        $alertas = [];
        $token = s($_GET["token"]);

        // Buscar al usuario por su token
        $usuario = Usuario::where("token", $token);

        if (empty($usuario)) {
            Usuario::setAlerta("error", "Token no válido");
            $error = true;
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $contraseña = new Usuario($_POST);
            $alertas = $contraseña->validarContraseña();

            if (empty($alertas)) {
                $usuario->contraseña = null;
                $usuario->contraseña = $contraseña->contraseña;
                $usuario->hashPassword();
                $usuario->token = "";
                $resultado = $usuario->guardar();

                if ($resultado) {
                    Usuario::setAlerta("exito", "Contraseña actualizada correctamente");

                    header("Refresh: 3; url=/");
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render("auth/recuperar-contraseña", [
            "alertas" => $alertas,
            "error" => $error
        ]);
    }
    
    public static function crear(Router $router) {
        $usuario = new Usuario;
        $alertas = [];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarCuenta();

            // Revisar que alertas este vacio
            if (empty($alertas)) {
                // Verificar que el usuario no este registrado previamente
                $resultado = $usuario->existeUsuario();

                if ($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                } else {
                    // Hashear la contraseña
                    $usuario->hashPassword();

                    // Generar token unico
                    $usuario->crearToken();

                    // Enviar el correo
                    $correo = new Correo($usuario->correo, $usuario->nombre, $usuario->token);
                    $correo->enviarConfirmacion();

                    // Crear el usuario
                    $resultado = $usuario->guardar();

                    Usuario::setAlerta("exito", "revise su correo para confirmar su cuenta");

                    if ($resultado) {
                        header("Location: /mensaje");
                    }
                }
            }
        }

        $alertas = Usuario::getAlertas();
        
        $router->render("auth/crear-cuenta", [
            "usuario" => $usuario,
            "alertas" => $alertas
        ]);
    }

    public static function confirmar(Router $router) {
        $alertas = [];
        $token = s($_GET["token"]);

        $usuario = Usuario::where("token", $token);

        if (empty($usuario)) {
            Usuario::setAlerta("error", "Token no válido");
        } else {
            $usuario->confirmado = "1";
            $usuario->token = "";
            $usuario->guardar();

            Usuario::setAlerta("exito", "Cuenta comprobada correctamente");
        }

        $alertas = Usuario::getAlertas();
        $router->render("auth/confirmar-cuenta", [
            "alertas" => $alertas
        ]);
    }

    public static function mensaje(Router $router) {
        $router->render("auth/mensaje");
    }
}