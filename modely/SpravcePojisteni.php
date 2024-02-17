<?php

/**
 * Třída poskytuje metody pro správu pojištění
 */
class SpravcePojisteni
{

    /**
     * Uloží pojištění do databáze
     * @param array $pojisteni Asociativní pole s informacemi o pojištění
     * @return bool
     */
    public function pridejPojisteni(array $pojisteni): void
    {
        try {
            Db::vloz('pojisteni', $pojisteni);
            //Získá ID pojištění
            $id = Db::posledniId();
            //Aktualizuje záznam v databázi s novou hodnotou id
            Db::zmen('pojisteni', ['pojisteni_id' => $id], 'WHERE pojisteni_id = ?', [$id]);
        } catch (PDOException $chyba) {
            $this->pridejZpravu($chyba->getMessage(), 'chyba');
        }
    }

    /**
     * Upraví údaje o uloženém pojištění
     * @param int $id ID pojištění
     * @param array $pojisteniPojistence Asociativní pole s informacemi o pojištění
     * @return void
     */
    public function upravPojisteni(int $id, array $pojisteniPojistence): void
    {
        Db::zmen('pojisteni', $pojisteniPojistence, 'WHERE pojisteni_id = ?', array($id));
    }

    /**
     * Vypíše pojištění podle ID
     * @param string $url URL adresa pojištěnce
     * @return array|bool Základní informace o všech pojištěncích daného pojištěnce jako numerické pole asociativních polí, false pokud pojištěnec žádné pojištění nemá
     */
    public function vratVsechnaPojisteniPojistence(string $url): array|bool
    {
        return Db::dotazVsechny('
			SELECT `pojisteni_id`, `typ_pojisteni`, `predmet_pojisteni`, `od`, `do`, `castka`, `frekvence_platby`, `pojistenec_id`
			FROM `pojisteni` 
                        JOIN `pojistenci` ON `pojisteni`.`pojistenec_id` = `pojistenci`.`url`
                        WHERE `pojistenci`.`url` = ?
		', array($url));
    }

    /**
     * Vrátí seznam pojištění v databázi
     * @return array Základní informace o všech pojištěních jako numerické pole asociativních polí
     */
    public function vratVsechnaPojisteni(): array
    {
        return Db::dotazVsechny('
			SELECT `pojisteni_id`, `typ_pojisteni`, `predmet_pojisteni`, `od`, `do`, `castka`, `frekvence_platby`, `pojistenec_id`
			FROM `pojisteni` 
			ORDER BY `pojisteni_id` DESC 
		');
    }

    /**
     * Odstraní pojištění
     * @param string $pojisteni_id ID pojištění k odstranění
     * @return void
     */
    public function odstranPojisteni(int $pojisteniId): void
    {
        Db::dotaz('
			DELETE FROM pojisteni
                        WHERE pojisteni_id = :pojisteni_id;
		', array('pojisteni_id' => $pojisteniId));
    }

    /**
     * Vrátí počet uzavřených pojištění v databázi
     * @return int Počet pojištění
     */
    public function vratPocetPojisteni(): int
    {
        return Db::dotazSamotny('SELECT COUNT(`pojisteni_id`) FROM `pojisteni` WHERE `pojisteni_id`');
    }

    /**
     * Funkce pro získání nejpozdějšího možného data pro platnost pojištění
     * @return type
     */
    public function maximalniDatum()
    {
        $dnes = new DateTime();
        $dnes->modify('+150 years');
        return $dnes->format('Y-m-d');
    }

}