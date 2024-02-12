<?php

session_start();

//Nastavení kódování Unicode pro práci s řetězci
mb_internal_encoding("UTF-8");

/**
 * Funkce pro automatické načítání tříd kontrolerů a modelů
 * @param string $trida Název třídy
 * @return void
 */
function autoloadFunkce(string $trida): void
{
//Končí název třídy řetězcem "Kontroler"?
    if (preg_match('/Kontroler$/', $trida)) {
        require("kontrolery/" . $trida . ".php");
    } else {
        require("modely/" . $trida . ".php");
    }
}

//Implementace pro autoloader
spl_autoload_register("autoloadFunkce");

//Připojení k databázi
Db::pripoj("127.0.0.1", "root", "", "pojisteni");

//Vytvoří směrovač (router) a zpracuje parametry z URL adresy
$smerovac = new SmerovacKontroler();
$smerovac->zpracuj(array($_SERVER['REQUEST_URI']));

//Renderuje pohled
$smerovac->vypisPohled();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

set_exception_handler(function ($exception) {
    echo '<pre>';
    echo 'Uncaught exception: ' . $exception->getMessage() . '<br>';
    echo 'Stack trace:<br>' . $exception->getTraceAsString() . '<br>';
    echo '</pre>';
    die();
});

set_error_handler(function ($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        return;
    }

    echo '<pre>';
    echo 'Error: ' . $message . '<br>';
    echo 'File: ' . $file . '<br>';
    echo 'Line: ' . $line . '<br>';
    echo '</pre>';
    die();
});

register_shutdown_function(function () {
    $error = error_get_last();
    if ($error !== null) {
        echo '<pre>';
        echo 'Shutdown error: ' . $error['message'] . '<br>';
        echo 'File: ' . $error['file'] . '<br>';
        echo 'Line: ' . $error['line'] . '<br>';
        echo '</pre>';
        die();
    }
});

