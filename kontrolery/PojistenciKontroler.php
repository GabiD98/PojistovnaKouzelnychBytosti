<?php

/**
 * Kontroler pro výpis seznamu pojištěnců
 */
class PojistenciKontroler extends Kontroler
{

    /**
     * Zpracuje pojištěnce
     * @param array $parametry Parametry
     * @return void
     */
    public function zpracuj(array $parametry): void
    {

        // Pojištěnce vidí jen přihlášení uživatelé
        $this->overUzivatele();
        
        // Hlavička stránky
        $this->hlavicka['titulek'] = 'Seznam pojištěnců';
        $this->hlavicka['popis'] = 'Seznam všech pojištěnců';
        $this->hlavicka['klicovaSlova'] = 'pojištěnci';

        // Vytvoření instance modelu, který nám umožní pracovat s uživatelem
        $spravceUzivatelu = new SpravceUzivatelu();
        // Ověření, zda je uživatel přihlášený
        $this->overUzivatele();
        // Ověří, zda je uživatel zároveň administrátor
        $uzivatel = $spravceUzivatelu->vratUzivatele();
        // Vytvoření instance modelu, který nám umožní pracovat s pojištěnci
        $spravcePojistencu = new SpravcePojistencu();

        // Naplnění proměnných pro šablonu
        $pojistenci = $spravcePojistencu->vratVsechnyPojistence();
        if (!empty($parametry[0])) {
            $pojistenec = $spravcePojistencu->vratJednohoPojistence($parametry[0]);
        }
        $this->data['admin'] = $uzivatel && $uzivatel['admin'];
        $this->data['pojistenci'] = $pojistenci;

        // Nastavení šablony
        $this->pohled = 'pojistenci';
    }
}
