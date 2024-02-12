<?php

/**
 * Třída poskytuje metody pro správu pojištěnců
 */
class SpravcePojistencu
{

    /**
     * Vrátí uživatele z databáze podle jeho URL
     * @param string $url URL uživatele k zobrazení
     * @return array Data uživatele z databáze jako asociativní pole
     */
    public function vratJednohoPojistence(string $url): array|bool
    {
        return Db::dotazJeden('
			SELECT `pojistenci_id`, `jmeno`, `prijmeni`, `datum_narozeni`, `email`, `telefon`, `adresa`, `mesto`, `psc`,`url`
			FROM `pojistenci` 
			WHERE `url` = ?
		', array($url));
    }

    /**
     * Uloží pojištěnce do systému. Pokud je ID false, vloží nového, jinak provede editaci.
     * @param int|bool $id ID pojištěnce k editaci, FALSE pro vložení nového pojištěnce
     * @param array $pojistenec Asociativní pole s informacemi o pojištěnci
     * @return void
     */
    public function ulozPojistence(int|bool $id, array $pojistenec): void
    {

        // Kontrola duplicitních údajů nového a již evidovaného pojištěnce
        $evidovanyPojistenec = Db::dotazJeden('
			SELECT *
			FROM pojistenci
			WHERE email = ? OR telefon = ?;
		', array($pojistenec['email'], $pojistenec['telefon']));

        // Pokud byl nalezen duplicitní záznam a nejedná se o současně editovaného pojištěnce, vyhodí chybu
        if ($evidovanyPojistenec && $evidovanyPojistenec['pojistenci_id'] !== $id) {
            if ($pojistenec['email'] === $evidovanyPojistenec['email']) {
                throw new ChybaUzivatele('Pojištěnec s touto emailovou adresou je již v databázi.');
            }
            if ($pojistenec['telefon'] === $evidovanyPojistenec['telefon']) {
                throw new ChybaUzivatele('Pojištěnec s tímto telefonním číslem je již v databázi.');
            }
        }

        if (!$id && !$evidovanyPojistenec) {
            //Při vkládání nového pojištěnce
            try {
                Db::vloz('pojistenci', $pojistenec);
                //Získá jeho ID
                $noveId = Db::posledniId();
                //Nastaví hodnotu 'url' na hodnoru 'pojistenci_id'
                $url = $noveId;
                $pojistenec['url'] = $url;
                //Aktualizuje záznam v databázi s novou hodnotou URL
                Db::zmen('pojistenci', ['url' => $url], 'WHERE pojistenci_id = ?', [$noveId]);
            } catch (PDOException $chyba) {
                $this->pridejZpravu($chyba->getMessage(), 'chyba');
            }
        } elseif ($id && !$evidovanyPojistenec || $evidovanyPojistenec['pojistenci_id'] === $id) {
            //Upraví již existujícího pojištěnce
            try {
                Db::zmen('pojistenci', $pojistenec, 'WHERE pojistenci_id = ?', array($id));
            } catch (PDOException $chyba) {
                $this->pridejZpravu($chyba->getMessage(), 'chyba');
            }
        }
    }

    /**
     * Vrátí seznam pojištěnců v databázi
     * @return array Základní informace o všech pojištěncích jako numerické pole asociativních polí
     */
    public function vratVsechnyPojistence(): array
    {
        return Db::dotazVsechny('
			SELECT `pojistenci_id`, `jmeno`, `prijmeni`, `datum_narozeni`, `email`, `telefon`, `adresa`,`mesto`, `psc`, `url`
			FROM `pojistenci` 
			ORDER BY `pojistenci_id` DESC 
		');
    }

    /**
     * Odstraní pojištěnce i všechna jeho pojištění
     * @param string $url URL pojištěnce k odstranění
     * @return void
     */
    public function odstranPojistence(string $url): void
    {
        Db::dotaz('
			DELETE pojistenci, pojisteni
                        FROM pojistenci
                        LEFT JOIN pojisteni ON pojistenci.url = pojisteni.pojistenec_id
			WHERE url = ?
		', array($url));
    }

    /**
     * Vrátí počet pojištěnců v databázi
     * @return int Počet pojištěnců
     */
    public function vratPocetPojistencu(): int
    {
        return Db::dotazSamotny('SELECT COUNT(*) FROM `pojistenci`');
    }

    /**
     * Vrátí počet pojištěných pojištěnců v databázi
     * @return int Počet pojištěných pojištěnců
     */
    public function vratPocetPojistenychPojistencu(): int
    {
        return Db::dotazSamotny('
			SELECT COUNT(DISTINCT `pojistenci`.`pojistenci_id`)
			FROM `pojistenci`
                        JOIN `pojisteni` ON `pojistenci`.`pojistenci_id` = `pojisteni`.`pojistenec_id`
	');
    }
}
