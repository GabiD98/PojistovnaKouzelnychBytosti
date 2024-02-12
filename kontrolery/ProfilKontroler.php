<?php

/**
 * Kontroler pro zpracování profilu uživatele
 */
class ProfilKontroler extends Kontroler
{

    /**
     * Zpracuje uživatele
     * @param array $parametry Parametry
     * @return void
     */
    public function zpracuj(array $parametry): void
    {

        // Hlavička stránky
        $this->hlavicka['titulek'] = 'Profil';
        $this->hlavicka['popis'] = 'Profil uživatele';
        $this->hlavicka['klicovaSlova'] = 'profil, uživatel';

        // Do profilu mají přístup jen přihlášení uživatelé
        $this->overUzivatele();

        // Vytvoření instance modelu, který nám umožní pracovat s uživatelem
        $spravceUzivatelu = new SpravceUzivatelu();

        //Odhlášení
        if (!empty($parametry[0]) && $parametry[0] == 'odhlasit') {
            $spravceUzivatelu->odhlas();
            $this->pridejZpravu('Byli jste úspěšně odhlášeni', 'uspech');
            $this->presmeruj('prihlaseni');
        }

        // Je odeslán formulář
        if ($_POST) {
            // Změna osobních údajů
            if (!empty($_POST['uzivatele_id']) && empty($_POST['puvodni_heslo'])) {
                $klice = array('uzivatele_id', 'jmeno', 'prijmeni', 'datum_narozeni', 'email', 'telefon', 'adresa', 'mesto', 'psc');
                $uzivatel = array_intersect_key($_POST, array_flip($klice));
                try {
                    $spravceUzivatelu->ulozUzivatele($_POST['uzivatele_id'], $uzivatel);
                    $this->pridejZpravu('Vaše údaje byly úspěšně změněny', 'uspech');
                    $this->presmeruj('profil');
                } catch (ChybaUzivatele $chyba) {
                    $this->pridejZpravu($chyba->getMessage(), 'chyba');
                }
                // Změna hesla
            } elseif (!empty($_POST['puvodni_heslo']) && !empty($_POST['nove_heslo']) && !empty($_POST['heslo_znovu'])) {
                try {
                    $spravceUzivatelu->zmenHeslo($_POST['puvodni_heslo'], $_POST['nove_heslo'], $_POST['heslo_znovu']);
                    $this->pridejZpravu('Heslo bylo úspěšně změněno.', 'uspech');
                    $this->presmeruj('profil');
                } catch (ChybaUzivatele $chyba) {
                    $this->pridejZpravu($chyba->getMessage(), 'chyba');
                }
            }
        }


        // Proměnné pro šablonu
        $uzivatel = $spravceUzivatelu->vratUzivatele();
        $this->data['uzivatel'] = $uzivatel;
        $this->data['uzivateleId'] = $uzivatel['uzivatele_id'];
        $this->data['uzivatelskeJmeno'] = $uzivatel['uzivatelske_jmeno'];
        $this->data['admin'] = $uzivatel['admin'];
        $this->data['jmeno'] = $uzivatel['jmeno'];
        $this->data['prijmeni'] = $uzivatel['prijmeni'];
        $this->data['datumNarozeni'] = $uzivatel['datum_narozeni'];
        $this->data['email'] = $uzivatel['email'];
        $this->data['telefon'] = $uzivatel['telefon'];
        $this->data['adresa'] = $uzivatel['adresa'];
        $this->data['mesto'] = $uzivatel['mesto'];
        $this->data['psc'] = $uzivatel['psc'];
        $this->data['heslo'] = $uzivatel['heslo'];
        $vek = $this->vypocitejVek($uzivatel['datum_narozeni']);
        $this->data['vek'] = $vek;

        // Nastavení šablony
        $this->pohled = 'profil';
    }
}
