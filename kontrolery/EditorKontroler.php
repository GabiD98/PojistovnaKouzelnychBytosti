<?php

/**
 * Kontroler pro editaci pojištěnců
 */
class EditorKontroler extends Kontroler
{

    /**
     * Zpracuje editor
     * @param array $parametry Parametry
     * @return void
     */
    public function zpracuj(array $parametry): void
    {
        // Pokud je přihlášený uživatel smazán z databáze, odhlásí ho
        $this->overUzivatele();
        
        // Editor smí používat jen administrátoři
        $this->overAdmina(true);

        // Hlavička stránky
        $this->hlavicka['titulek'] = 'Editor pojištěnců';
        $this->hlavicka['popis'] = 'editor';
        $this->hlavicka['klicovaSlova'] = 'editor, pojištěnec';

        // Vytvoření instance modelu
        $spravcePojistencu = new SpravcePojistencu();

        // Příprava prázdného profilu pojištěnce
        $pojistenec = array(
            'pojistenci_id' => '',
            'jmeno' => '',
            'prijmeni' => '',
            'datum_narozeni' => '',
            'email' => '',
            'telefon' => '',
            'adresa' => '',
            'mesto' => '',
            'psc' => '',
            'url' => '',
        );

        // Je odeslán formulář
        if ($_POST) {
            try {
                // Získání pojištěnce z $_POST
                $klice = array('pojistenci_id','jmeno', 'prijmeni', 'datum_narozeni', 'email', 'telefon', 'adresa', 'mesto', 'psc', 'url');
                $pojistenec = array_intersect_key($_POST, array_flip($klice));
                // Uložení pojištěnce do DB
                $spravcePojistencu->ulozPojistence($_POST['pojistenci_id'], $pojistenec);
                $this->pridejZpravu('Údaje byly úspěšně uloženy', 'uspech');
                $this->presmeruj('pojistenci');
            } catch (ChybaUzivatele $chyba) {
                $this->pridejZpravu($chyba->getMessage(), 'chyba');
            }
        } else if (!empty($parametry[0])) {
            // Je zadané URL pojištěnce k editaci
            $nactenyPojistenec = $spravcePojistencu->vratJednohoPojistence($parametry[0]);
            if ($nactenyPojistenec) {
                $pojistenec = $nactenyPojistenec;
            } else {
                $this->pridejZpravu('Pojištěnec nebyl nalezen', 'chyba');
            }
        }

        // Nastavení proměnných pro šablonu
        $this->data['pojistenec'] = $pojistenec;
        $this->data['pojistenecId'] = $pojistenec['pojistenci_id'];
        $this->data['jmeno'] = $pojistenec['jmeno'];
        $this->data['prijmeni'] = $pojistenec['prijmeni'];
        $this->data['datumNarozeni'] = $pojistenec['datum_narozeni'];
        $this->data['email'] = $pojistenec['email'];
        $this->data['telefon'] = $pojistenec['telefon'];
        $this->data['adresa'] = $pojistenec['adresa'];
        $this->data['mesto'] = $pojistenec['mesto'];
        $this->data['psc'] = $pojistenec['psc'];
        $this->data['url'] = $pojistenec['url'];

        // Nastavení šablony
        $this->pohled = 'editor';
    }
}
