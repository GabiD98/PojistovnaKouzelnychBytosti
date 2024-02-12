<?php

/**
 * Kontroler pro chybovou stránku
 */
class ChybaKontroler extends Kontroler
{

    public function zpracuj(array $parametry): void
    {
        // Pokud je přihlášený uživatel smazán z databáze, odhlásí ho
        $this->overUzivatele();

        // Hlavička požadavku
        header("HTTP/1.0 404 Not Found");

        // Hlavička stránky
        $this->hlavicka['titulek'] = 'Chyba 404';
        $this->hlavicka['popis'] = 'Nenalezená URL adresa';
        $this->hlavicka['klicovaSlova'] = 'Chyba 404, chyba';

        // Nastavení šablony
        $this->pohled = 'chyba';
    }
}
