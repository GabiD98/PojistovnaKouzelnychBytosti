<?php

/**
 * Kontroler pro zpracování stránky s kontaktním formulářem
 */
class KontaktKontroler extends Kontroler
{

    /**
     * Zpracuje článek kontaktu
     * @param array $parametry
     * @return void
     */
    public function zpracuj(array $parametry): void
    {
        // Hlavička stránky
        $this->hlavicka['titulek'] = 'Kontaktujte nás';
        $this->hlavicka['popis'] = 'Kontaktní formulář';
        $this->hlavicka['klicovaSlova'] = 'kontakt, formulář';

        // Je odeslán formulář
        if ($_POST) {
            try {
                $odesilacEmailu = new OdesilacEmailu();
                $odesilacEmailu->odesliSAntispamem($_POST['rok'], "dvornikova.gabi@gmail.com", "Email z webu", $_POST['zprava'], $_POST['email']);
                $this->pridejZpravu('Email byl úspěšně odeslán', 'uspech');
                $this->presmeruj('kontakt');
            } catch (ChybaUzivatele $chyba) {
                $this->pridejZpravu($chyba->getMessage(), 'chyba');
            }
        }
        
        // Nastavení šablony
        $this->pohled = 'kontakt';
    }
}
