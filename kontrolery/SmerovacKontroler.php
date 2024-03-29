<?php

/**
 * Kontroler, který podle URL zjistí příslušný kontroler, zavolá ho a uloží do atributu $kontroler
 * Uloží do své šablony (rozložení stránky) pohled volaného kontroleru (obsah stránky) a zobrazí výsledek uživateli
 */
class SmerovacKontroler extends Kontroler
{
    protected ?Kontroler $kontroler = null;

    public function zpracuj(array $parametry): void
    {
        $naparsovanaURL = $this->parsujURL($parametry[0]);

        if (empty($naparsovanaURL[0])) {
            $this->presmeruj('uvod');
        }

        $tridaKontroleru = $this->pomlckyDoVelbloudiNotace(array_shift($naparsovanaURL)) . 'Kontroler';

        if (file_exists('kontrolery/' . $tridaKontroleru . '.php')) {
            $this->kontroler = new $tridaKontroleru;
        } else {
            $this->pridejZpravu('Požadovaná stránka nebyla nalezena, zkontrolujte prosím URL adresu', 'chyba');
            $this->presmeruj('uvod');
        }

        if ($this->kontroler) {
            // Zjistí aktuální stránku z URL parametru
            $stranka = isset($_GET['page']) ? $_GET['page'] : 1;

            // Předá aktuální stránku do načítaného kontroleru
            $this->kontroler->zpracuj($naparsovanaURL, $stranka);

            // Nastavení proměnných pro šablonu
            $this->data['titulek'] = $this->kontroler->hlavicka['titulek'];
            $this->data['popis'] = $this->kontroler->hlavicka['popis'];
            $this->data['klicovaSlova'] = $this->kontroler->hlavicka['klicovaSlova'];
            $this->data['zpravy'] = $this->vratZpravy();
            $spravceUzivatelu = new SpravceUzivatelu();
            $this->data['uzivatel'] = $spravceUzivatelu->jePrihlaseny();
            $uzivatel = $spravceUzivatelu->vratUzivatele();
            $this->data['admin'] = $uzivatel && $uzivatel['admin'];

            $rok = date("Y");
            $this->data['rok'] = $rok;

            $this->pohled = 'rozlozeni';
        } else {
            // Handle case when controller is not initialized
        }
    }


    /**
     * Naparsuje URL adresu a vrátí ji ve formě pole parametrů
     * @param string $url URL adresa, kterou potřebujeme naparsovat
     * @return array Pole parametrů z naparsované URL adresy
     */
    private function parsujURL(string $url): array
    {
        //Oddělí část s protokolem a doménou od části s parametry URL adresy (tento řetězec uloží do asociativního pole pod indexem "path")
        $naparsovanaURL = parse_url($url);
        //Odstraní lomítko před parametrem pro název kontroleru
        $naparsovanaURL["path"] = ltrim($naparsovanaURL["path"], "/");
        //Odstraní bílé znaky okolo URL adresy
        $naparsovanaURL["path"] = trim($naparsovanaURL["path"]);
        //Rozbije výsledný řetězec podle lomítek na pole jednotlivých parametrů
        $rozdelenaCesta = explode("/", $naparsovanaURL["path"]);
        return $rozdelenaCesta;
    }

    /**
     * Převede název kontroleru s pomlčkami (kebab-case) na název třídy (camelCase)
     * @param string $text Řetězec v kebab-case
     * @return string
     */
    private function pomlckyDoVelbloudiNotace(string $text): string
    {
        //Nahradí pomlčky ve $text mezerami a uloží do $veta
        $veta = str_replace('-', ' ', $text);
        //Zvětší první písmena slov
        $veta = ucwords($veta);
        //Odstraní mezery
        $veta = str_replace(' ', '', $veta);
        return $veta;
    }
}
