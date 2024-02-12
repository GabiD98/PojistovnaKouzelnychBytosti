<?php

/**
 * Kontroler pro přihlášení
 */
class PrihlaseniKontroler extends Kontroler
{

    /**
     * Zpracuje přihlášení
     * @param array $parametry Parametry
     * @return void
     */
    public function zpracuj(array $parametry): void
    {
        // Hlavička stránky
        $this->hlavicka['titulek'] = 'Přihlášení';
        $this->hlavicka['popis'] = 'Přihlášení uživatele';
        $this->hlavicka['klicovaSlova'] = 'přihlášení, uživatel';

        // Vytvoření instance modelu, který nám umožní pracovat s uživatelem
        $spravceUzivatelu = new SpravceUzivatelu();
        if ($spravceUzivatelu->vratUzivatele()) {
            $this->presmeruj('profil');
        }

        // Je odeslán formulář
        if ($_POST) {
            try {
                $spravceUzivatelu->prihlas($_POST['uzivatelske_jmeno'], $_POST['heslo']);
                $this->pridejZpravu('Vítejte ve svém profilu!', 'uspech');
                $this->presmeruj('profil');
            } catch (ChybaUzivatele $chyba) {
                $this->pridejZpravu($chyba->getMessage(), 'chyba');
            }
        }

        //Zajištění, že se formulář nevymaže např. při chybové hlášce
        $uzivatelskeJmeno = (isset($_POST['uzivatelske_jmeno'])) ? $_POST['uzivatelske_jmeno'] : '';

        // Naplnění proměnných pro šablonu
        $this->data['uzivatelskeJmeno'] = $uzivatelskeJmeno;

        // Nastavení šablony
        $this->pohled = 'prihlaseni';
    }
}
