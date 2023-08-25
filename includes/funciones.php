<?php

function debug($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

function esUltimo(string $act, string $prox) : bool {
    if ($act !== $prox) {
        return true;
    }
    return false;
}

// Funcion que revisa que el usuario este autenticado
function isAuth() : void {
    if (!isset($_SESSION["login"])) {
        header("Location: /");
    }
}

// Funcion que revisa que el usuario sea admin
function isAdmin() : void {
    if (!isset($_SESSION["admin"])) {
        debug($_SESSION);
        header("Location: /");
    }
}