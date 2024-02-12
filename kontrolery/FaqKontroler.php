<?php

/**
 * Kontroler pro zpracování FAQ stránky
 */
class FaqKontroler extends Kontroler
{

    public function zpracuj(array $parametry): void
    {
        //Hlavička stránky
        $this->hlavicka['titulek'] = 'FAQ';
        $this->hlavicka['popis'] = 'FAQ';
        $this->hlavicka['klicovaSlova'] = 'faq, otázky, odpovědi';

        // Nastavení šablony
        $this->pohled = 'faq';
    }
}
