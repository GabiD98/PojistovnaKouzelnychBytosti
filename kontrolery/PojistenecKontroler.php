<?php

/**
 * Kontroler pro výpis profilu pojištěnce
 */
class PojistenecKontroler extends Kontroler
{

    /**
     * Zpracuje pojištěnce
     * @param array $parametry Parametry
     * @return void
     */
    public function zpracuj(array $parametry): void
    {

        // Vytvoření instance modelu, který nám umožní pracovat s pojištěnci
        $spravcePojistencu = new SpravcePojistencu();
        $pojistenec = $spravcePojistencu->vratJednohoPojistence($parametry[0]);
        // Vytvoření instance modelu, který nám umožní pracovat s uživatelem
        $spravceUzivatelu = new SpravceUzivatelu();
        $uzivatel = $spravceUzivatelu->vratUzivatele();
        // Vytvoření instance modelu, který nám umožní pracovat s pojištěním
        $spravcePojisteni = new SpravcePojisteni();
        // Ověření, zda je uživatel přihlášený
        $this->overUzivatele();

        // Hlavička stránky
        $this->hlavicka = ['titulek' => $pojistenec['jmeno'] . " " . $pojistenec['prijmeni']];
        $this->hlavicka['popis'] = 'Profil pojištěnce';
        $this->hlavicka['klicovaSlova'] = 'pojištěnec, profil';

        // Je zadáno URL pojištěnce ke smazání
        if (!empty($parametry[1]) && $parametry[1] == 'odstranit') {
            $this->overAdmina(true);
            $spravcePojistencu->odstranPojistence($parametry[0]);
            $this->pridejZpravu('Pojištěnec byl úspěšně odstraněn', 'uspech');
            $this->presmeruj('pojistenci');
            //Je zadáno URL pojištěnce k zobrazení - získání pojištěnce podle URL
        } else if (!empty($parametry[0])) {
            $pojistenec = $spravcePojistencu->vratJednohoPojistence($parametry[0]);
            // Pokud nebyl pojištěnec s danou URL nalezen, přesměrujeme na ChybaKontroler
            if (!$pojistenec) {
                $this->pridejZpravu('Pojištěnec nebyl nalezen', 'chyba');
                $this->presmeruj('pojistenci');
            }
        }

        // Příprava prázdného formuláře pojištění
        $pojisteni = [
            'pojisteni_id' => '',
            'typ_pojisteni' => '',
            'predmet_pojisteni' => '',
            'od' => '',
            'do' => '',
            'castka' => '',
            'frekvence_platby' => '',
            'pojistenec_id' => '',
        ];
        // Přidá nové pojištění
        if ($_POST && empty($_POST['pojisteni_id']) && isset($_POST['pojistenec_id'])) {
            $klice = ['typ_pojisteni', 'predmet_pojisteni', 'od', 'do', 'castka', 'frekvence_platby', 'pojistenec_id'];
            $pojisteni = array_intersect_key($_POST, array_flip($klice));
            $spravcePojisteni->pridejPojisteni($pojisteni);
            $this->pridejZpravu('Bylo přidáno nové pojištění', 'uspech');
            $this->presmeruj('pojistenec/' . $pojistenec['url']);
            // Odstraní pojištění
        } elseif ($_POST && !empty($_POST['pojisteni_id']) && !empty($_POST['odstranit'])) {
            try {
                $klice = ['pojisteni_id'];
                $pojisteniPojistence = array_intersect_key($_POST, array_flip($klice));
                $spravcePojisteni->odstranPojisteni($_POST['pojisteni_id']);
                $this->pridejZpravu('Pojištění bylo odstraněno', 'uspech');
                $this->presmeruj('pojistenec/' . $pojistenec['url']);
            } catch (ChybaUzivatele $chyba) {
                $this->pridejZpravu($chyba->getMessage(), 'chyba');
            }
            // Edituje pojištění
        } elseif ($_POST && !empty($_POST['pojisteni_id'])) {
            try {
                $klice = ['pojisteni_id', 'typ_pojisteni', 'predmet_pojisteni', 'od', 'do', 'castka', 'frekvence_platby', 'pojistenec_id'];
                $pojisteniPojistence = array_intersect_key($_POST, array_flip($klice));
                $spravcePojisteni->upravPojisteni($_POST['pojisteni_id'], $pojisteniPojistence);
                $this->pridejZpravu('Údaje o pojištění byly změněny', 'uspech');
                $this->presmeruj('pojistenec/' . $pojistenec['url']);
            } catch (ChybaUzivatele $chyba) {
                $this->pridejZpravu($chyba->getMessage(), 'chyba');
            }
        }

        // Vypíše pojištění na stránce daného pojištěnce
        if (!empty($parametry[0])) {
            $pojisteniPojistence = $spravcePojisteni->vratVsechnaPojisteniPojistence($parametry[0]);
            // Pokud pojištěnec nemá žádné pojištění, vypíše se prázdné pole
            if ($pojisteniPojistence === false) {
                $this->data['pojisteniPojistence'] = [];
            } else {
                $this->data['pojisteniPojistence'] = $pojisteniPojistence;
            }
        }

        // Naplnění proměnných pro šablonu
        $this->data['pojistenec'] = $pojistenec;
        $this->data['uzivatel'] = $uzivatel;
        $this->data['admin'] = $uzivatel && $uzivatel['admin'];
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

        $this->data['pojisteni'] = $pojisteniPojistence;
        $this->data['typPojisteni'] = $pojisteni['typ_pojisteni'];
        $this->data['predmetPojisteni'] = $pojisteni['predmet_pojisteni'];
        $this->data['od'] = $pojisteni['od'];
        $this->data['do'] = $pojisteni['do'];
        $this->data['castka'] = $pojisteni['castka'];
        $this->data['frekvencePlatby'] = $pojisteni['frekvence_platby'];
        
        $vek = $this->vypocitejVek($pojistenec['datum_narozeni']);
        $this->data['vek'] = $vek;
        $maximalniDatum = $spravcePojisteni->maximalniDatum();
        $this->data['maximalniDatum'] = $maximalniDatum;

        // Nastavení šablony
        $this->pohled = 'pojistenec';
    }
}
