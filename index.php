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