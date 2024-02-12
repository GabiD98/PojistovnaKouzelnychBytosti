<?php

/**
 * Pomocná třída poskytující metody pro odeslání emailu
 */
class OdesilacEmailu
{

    /**
     * Odešle email jako HTML
     * @param string $komu Adresa příjemce
     * @param string $predmet Předmět e-mailu
     * @param string $zprava Obsah e-mailu
     * @param string $od Adresa odesílatele
     * @return void
     * @throws ChybaUzivatele Pokud se nepodaří e-mail odeslat
     */
    public function odesli(string $komu, string $predmet, string $zprava, string $od): void
    {
        $hlavicka = "From: " . $od;
        $hlavicka .= "\nMIME-Version: 1.0\n";
        $hlavicka .= "Content-Type: text/html; charset=\"utf-8\"\n";
        if (!mb_send_mail($komu, $predmet, $zprava, $hlavicka)) {
            throw new ChybaUzivatele('Email se nepodařilo odeslat.');
        }
    }

    /**
     * Zkontroluje, zda byl zadán aktuální rok jako antispam a odešle email
     * @param string $rok Současný rok vyplněný uživatelem
     * @param string $komu Adresa příjemce
     * @param string $predmet Předmět e-mailu
     * @param string $zprava Obsah e-mailu
     * @param string $od Adresa odesílatele
     * @return void
     * @throws ChybaUzivatele Pokud se nepodaří e-mail odeslat
     */
    public function odesliSAntispamem(string $rok, string $komu, string $predmet, string $zprava, string $od): void
    {
        if ($rok != date("Y")) {
            throw new ChybaUzivatele('Chybně vyplněný antispam.');
        }
        $this->odesli($komu, $predmet, $zprava, $od);
    }
}
