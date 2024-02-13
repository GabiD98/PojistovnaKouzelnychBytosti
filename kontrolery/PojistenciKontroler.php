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

        // Vytvoření instance modelu, který nám umožní pracovat s pojištěnci
        $spravcePojistencu = new SpravcePojistencu();

        // Definice počtu položek na stránku
        $polozekNaStranku = 10;

        // Zjistíme aktuální stránku
        $stranka = isset($_GET['page']) ? (int) $_GET['page'] : 1;

        // Získáme celkový počet pojištěnců
        $pocetPojistencu = $spravcePojistencu->vratPocetPojistencu();

        // Vypočítáme celkový počet stránek
        $pocetStranek = ceil($pocetPojistencu / $polozekNaStranku);

        // Omezení pro offset
        $offset = ($stranka - 1) * $polozekNaStranku;

        // Získání pojištěnců pro aktuální stránku
        $pojistenci = $spravcePojistencu->vratVsechnyPojistence($offset, $polozekNaStranku);

        // Vytvoření instance modelu, který nám umožní pracovat s uživatelem
        $spravceUzivatelu = new SpravceUzivatelu();
        // Ověření, zda je uživatel přihlášený
        $this->overUzivatele();
        // Ověří, zda je uživatel zároveň administrátor
        $uzivatel = $spravceUzivatelu->vratUzivatele();

        // Naplnění proměnných pro šablonu
        $pojistenci = $spravcePojistencu->vratVsechnyPojistence();
        if (!empty($parametry[0])) {
            $pojistenec = $spravcePojistencu->vratJednohoPojistence($parametry[0]);
        }
        $this->data['admin'] = $uzivatel && $uzivatel['admin'];
        $this->data['pojistenci'] = $pojistenci;
        $this->data['pocetStranek'] = $pocetStranek;
        $this->data['aktualniStranka'] = $stranka;

        // Nastavení šablony
        $this->pohled = 'pojistenci';
    }
}