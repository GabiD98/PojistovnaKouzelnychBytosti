<?php

/**
 * Kontroler pro registraci nového uživatele
 */
class RegistraceKontroler extends Kontroler
{

    /**
     * Zpracuje registraci
     * @param array $parametry Parametry
     * @return void
     */
    public function zpracuj(array $parametry): void
    {
        // Hlavička stránky
        $this->hlavicka['titulek'] = 'Registrace';
        $this->hlavicka['popis'] = 'Registrace uživatele';
        $this->hlavicka['klicovaSlova'] = 'registrace, uživatel';

        // Je odeslán formulář
        if ($_POST) {
            try {
                $spravceUzivatelu = new SpravceUzivatelu();
                $spravceUzivatelu->registruj($_POST['uzivatelske_jmeno'], $_POST['email'], $_POST['telefon'], $_POST['heslo'], $_POST['heslo_znovu'], $_POST['rok']);
                $spravceUzivatelu->prihlas($_POST['uzivatelske_jmeno'], $_POST['heslo']);
                $this->pridejZpravu('Byli jste úspěšně zaregistrováni', 'uspech');
                $this->presmeruj('profil');
            } catch (ChybaUzivatele $chyba) {
                $this->pridejZpravu($chyba->getMessage(), 'chyba');
            }
        }

        //Zajištění, že se formulář nevymaže např. při chybové hlášce
        $uzivatelskeJmeno = (isset($_POST['uzivatelske_jmeno'])) ? $_POST['uzivatelske_jmeno'] : '';
        $email = (isset($_POST['email'])) ? $_POST['email'] : '';
        $telefon = (isset($_POST['telefon'])) ? $_POST['telefon'] : '';
        $rok = (isset($_POST['rok'])) ? $_POST['rok'] : '';

        //Proměnné pro výpis do šablony
        $this->data['uzivatelskeJmeno'] = $uzivatelskeJmeno;
        $this->data['email'] = $email;
        $this->data['telefon'] = $telefon;
        $this->data['rok'] = $rok;

        // Nastavení šablony
        $this->pohled = 'registrace';
    }
}
