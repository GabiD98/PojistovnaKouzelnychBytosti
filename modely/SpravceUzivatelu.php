<?php

/**
 * Správce uživatelů
 */
class SpravceUzivatelu
{

    /**
     * Vrátí otisk hesla
     * @param string $heslo Heslo pro vypočítání otisku
     * @return string Otisk hesla
     */
    public function vratOtisk(string $heslo): string
    {
        return password_hash($heslo, PASSWORD_DEFAULT);
    }

    /**
     * Registruje nového uživatele do systému
     * @param string $uzivatelskeJmeno Přihlašovací jméno
     * @param string $email Emailová adresa
     * @param string $telefon Telefonní číslo
     * @param string $heslo Přihlašovací heslo
     * @param string $hesloZnovu Zopakované heslo
     * @param string $rok Zadaný rok do antispamového pole
     * @return void
     * @throws ChybaUzivatele
     */
    public function registruj(string $uzivatelskeJmeno, string $email, string $telefon, string $heslo, string $hesloZnovu, string $rok): void
    {
        // Kontrola duplicitních údajů nového a již registrovaného uživatele
        $registrovanyUzivatel = Db::dotazJeden('
			SELECT *
			FROM uzivatele
			WHERE uzivatelske_jmeno = ? OR email = ? OR telefon = ?;
		', array($uzivatelskeJmeno, $email, $telefon));

        // Validace formuláře
        if ($rok != date('Y')) {
            throw new ChybaUzivatele('Chybně vyplněný antispam.');
        } elseif ($heslo != $hesloZnovu) {
            throw new ChybaUzivatele('Zadané heslo nesouhlasí s kontrolním heslem.');
        } elseif ($uzivatelskeJmeno === $registrovanyUzivatel['uzivatelske_jmeno']) {
            throw new ChybaUzivatele('Uživatel s tímto uživatelským jménem je již zaregistrován.');
        } elseif ($email === $registrovanyUzivatel['email']) {
            throw new ChybaUzivatele('Uživatel s touto emailovou adresou je již zaregistrován.');
        } elseif ($telefon === $registrovanyUzivatel['telefon']) {
            throw new ChybaUzivatele('Uživatel s tímto telefonním číslem je již zaregistrován.');
        }

        // Asociativní pole s údaji o uživateli
        $uzivatel = array(
            'uzivatelske_jmeno' => $uzivatelskeJmeno,
            'email' => $email,
            'telefon' => $telefon,
            'heslo' => $this->vratOtisk($heslo),
        );

        // Vložení nového uživatele do databáze
        try {
            Db::vloz('uzivatele', $uzivatel);
        } catch (PDOException $chyba) {
            $this->pridejZpravu($chyba->getMessage(), 'chyba');
        }
    }

    /**
     * Přihlásí uživatele do systému
     * @param string $uzivatelskeJmeno Přihlašovací jméno
     * @param string $heslo Přihlašovací heslo
     * @return void
     * @throws ChybaUzivatele
     */
    public function prihlas(string $uzivatelskeJmeno, string $heslo): void
    {
        $uzivatel = Db::dotazJeden('
			SELECT uzivatele_id, uzivatelske_jmeno, jmeno, prijmeni, datum_narozeni, email, telefon, adresa, mesto, psc, heslo, admin
			FROM uzivatele
			WHERE uzivatelske_jmeno = ?
		', array($uzivatelskeJmeno));
        if (!$uzivatel || !password_verify($heslo, $uzivatel['heslo'])) {
            throw new ChybaUzivatele('Neplatné jméno nebo heslo.');
        }
        $_SESSION['uzivatel'] = $uzivatel;
    }

    /**
     * Odhlásí uživatele
     * @return void
     */
    public function odhlas(): void
    {
        unset($_SESSION['uzivatel']);
    }

    /**
     * Vrátí aktuálně přihlášeného uživatele
     * @return array|null Pole s informacemi o přihlášeném uživateli nebo NULL, pokud není žádný uživatel přihlášen
     */
    public function vratUzivatele(): array|null
    {
        if (isset($_SESSION['uzivatel'])) {
            return $_SESSION['uzivatel'];
        }
        return null;
    }

    /**
     * Zjistí, zda je uživatel přihlášený
     * @param bool $prihlaseny
     * @return bool
     */
    public function jePrihlaseny(bool $prihlaseny = true): bool
    {
        $spravceUzivatelu = new SpravceUzivatelu();
        $uzivatel = $spravceUzivatelu->vratUzivatele();
        if ($prihlaseny) {
            return $uzivatel !== null;
        } else {
            return $uzivatel == null;
        }
    }

    /**
     * Uloží změny v údajích uživatele do systému
     * @param int $id ID uživatele k editaci
     * @param array $uzivatel Asociativní pole s informacemi o uživateli
     * @return void
     */
    public function ulozUzivatele(int $id, array $uzivatel): void
    {

        // Kontrola duplicitních údajů nového a již registrovaného uživatele
        $registrovanyUzivatel = Db::dotazJeden('
        SELECT *
        FROM uzivatele
        WHERE email = ? OR telefon = ?;
    ', array($uzivatel['email'], $uzivatel['telefon']));

        // Pokud byl nalezen duplicitní záznam a nejedná se o současně editovaného uživatele, vyhodí chybu
        if ($registrovanyUzivatel && $registrovanyUzivatel['uzivatele_id'] !== $id) {
            if ($uzivatel['email'] === $registrovanyUzivatel['email']) {
                throw new ChybaUzivatele('Uživatel s touto emailovou adresou je již zaregistrován.');
            }
            if ($uzivatel['telefon'] === $registrovanyUzivatel['telefon']) {
                throw new ChybaUzivatele('Uživatel s tímto telefonním číslem je již zaregistrován.');
            }
        }

        try {
            // Pokud nebyl nalezen duplicitní záznam nebo jde o současně editovaného uživatele, provede se úprava
            if (!$registrovanyUzivatel || $registrovanyUzivatel['uzivatele_id'] === $id) {
                Db::zmen('uzivatele', $uzivatel, 'WHERE uzivatele_id = ?', array($id));
            }
        } catch (PDOException $chyba) {
            $this->pridejZpravu($chyba->getMessage(), 'chyba');
        }

        // Aktualizace session po provedení změn
        $_SESSION['uzivatel'] = array_merge($_SESSION['uzivatel'], $uzivatel);
    }

    /**
     * Vrátí počet uživatelů v databázi
     * @return int Počet uživatelů
     */
    public function vratPocetUzivatelu(): int
    {
        return Db::dotazSamotny('SELECT COUNT(`uzivatele_id`) FROM `uzivatele` WHERE `uzivatele_id`');
    }

    /**
     * Vrátí počet adminů v databázi
     * @return int Počet adminů
     */
    public function vratPocetAdminu(): int
    {
        return Db::dotazSamotny('SELECT COUNT(`uzivatele_id`) FROM `uzivatele` WHERE `admin`');
    }

    /**
     * Změní heslo uživatele
     * @param string $puvodniHeslo Původní heslo pro ověření
     * @param string $noveHeslo Nové heslo
     * @param string $hesloZnovu Nové heslo pro ověření
     * @throws ChybaUzivatele Pokud původní heslo není platné nebo nové hesla nesouhlasí s ověřením
     */
    public function zmenHeslo(string $puvodniHeslo, string $noveHeslo, string $hesloZnovu): void
    {
        $uzivatel = $this->vratUzivatele();

        // Ověření původního hesla
        if (!password_verify($puvodniHeslo, $uzivatel['heslo'])) {
            throw new ChybaUzivatele('Zadané původní heslo není správné.');
        }

        // Ověření shody nových hesel
        if ($noveHeslo !== $hesloZnovu) {
            throw new ChybaUzivatele('Ověření hesla nesouhlasí.');
        }

        // Aktualizace hesla v databázi
        $novyOtisk = $this->vratOtisk($noveHeslo);
        $this->ulozUzivatele($uzivatel['uzivatele_id'], ['heslo' => $novyOtisk]);
    }
}
