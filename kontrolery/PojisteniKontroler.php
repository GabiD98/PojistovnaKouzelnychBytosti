<?php

/**
 * Kontroler pro zpracování přehledu pojištění
 */
class PojisteniKontroler extends Kontroler
{

    public function zpracuj(array $parametry): void
    {
        //Hlavička strany
        $this->hlavicka['titulek'] = 'Pojištění';
        $this->hlavicka['popis'] = 'Typy pojištění';
        $this->hlavicka['klicovaSlova'] = 'pojištění, typy';

        //Nastavení proměnných pro šablonu
        $spravceUzivatelu = new SpravceUzivatelu();
        $uzivatel = $spravceUzivatelu->vratUzivatele();
        $this->data['uzivatel'] = $uzivatel;
        $this->data['admin'] = $uzivatel && $uzivatel['admin'];
        
        //Vypíše pohled
        $this->pohled = 'pojisteni';
    }
}
