<?php

/**
 * Kontroler pro zpracování úvodní stránky
 */
class UvodKontroler extends Kontroler
{

    public function zpracuj(array $parametry): void
    {
        //Hlavička strany
        $this->hlavicka['titulek'] = 'Homepage';
        $this->hlavicka['popis'] = 'Úvodní strana';
        $this->hlavicka['klicovaSlova'] = 'úvod, homepage';

        // Proměnné pro šablonu
        $spravceUzivatelu = new SpravceUzivatelu();
        $uzivatel = $spravceUzivatelu->vratUzivatele();
        $this->data['uzivatel'] = $spravceUzivatelu->jePrihlaseny();
        $this->data['admin'] = $uzivatel && $uzivatel['admin'];
        if ($uzivatel) {
            $this->data['uzivatelskeJmeno'] = $uzivatel['uzivatelske_jmeno'];
        }
        $this->data['pocetUzivatelu'] = $spravceUzivatelu->vratPocetUzivatelu();
        $this->data['pocetAdminu'] = $spravceUzivatelu->vratPocetAdminu();
        $spravcePojistencu = new SpravcePojistencu();
        $this->data['pocetPojistencu'] = $spravcePojistencu->vratPocetPojistencu();
        $this->data['pocetPojistenychPojistencu'] = $spravcePojistencu->vratPocetPojistenychPojistencu();
        $dnes = date("d.m.Y");
        $this->data['dnes'] = $dnes;
        $spravcePojisteni = new SpravcePojisteni();
        $this->data['pocetPojisteni'] = $spravcePojisteni->vratPocetPojisteni();

        // Nastavení šablony
        $this->pohled = 'uvod';
    }
}
