<?php

/**
 * CRUD wrapper pro komunikaci s databází
 */
class Db
{

    /**
     * @var PDO Databázové spojení
     */
    private static PDO $spojeni;

    /**
     * @var array Výchozí nastavení ovladače
     */
    private static array $nastaveni = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_EMULATE_PREPARES => false,
    );

    /**
     * Připojí se k databázi
     * @param string $host Hostitel databáze
     * @param string $uzivatel Přihlašovací jméno
     * @param string $heslo Příhlašovací heslo
     * @param string $databaze Název databáze
     */
    public static function pripoj(string $host, string $uzivatel, string $heslo, string $databaze): void
    {
        if (!isset(self::$spojeni)) {
            self::$spojeni = @new PDO(
                            "mysql:host=$host;dbname=$databaze",
                            $uzivatel,
                            $heslo,
                            self::$nastaveni
            );
        }
    }

    /**
     * Spustí dotaz a vrátí z něj první řádek
     * @param string $dotaz SQL dotaz s parametry nahrazenými otazníky
     * @param array $parametry Parametry pro doplnění do připraveného SQL dotazu
     * @return array|bool Asociativní pole s informacemi z prvního řádku výsledku nebo FALSE v případě prázdného výsledku
     */
    public static function dotazJeden(string $dotaz, array $parametry = array()): array|bool
    {
        $navrat = self::$spojeni->prepare($dotaz);
        $navrat->execute($parametry);
        return $navrat->fetch();
    }

    /**
     * Spustí dotaz a vrátí všechny jeho řádky
     * @param string $dotaz SQL dotaz s parametry nahrazenými otazníky
     * @param array $parametry Parametry pro doplnění do připraveného SQL dotazu
     * @return array|bool Pole asociativních pole s informacemi o všech řádcích výsledku nebo FALSE v případě prázdného výsledku
     */
    public static function dotazVsechny(string $dotaz, array $parametry = array()): array|bool
    {
        $navrat = self::$spojeni->prepare($dotaz);
        $navrat->execute($parametry);
        return $navrat->fetchAll();
    }

    /**
     * Spustí dotaz a vrátí z něj první sloupec prvního řádku
     * @param string $dotaz SQL dotaz s parametry nahrazenými otazníky
     * @param array $parametry Parametry pro doplnění do připraveného SQL dotazu
     * @return string|null Hodnota v prvním sloupci prvního řádku výsledku
     */
    public static function dotazSamotny(string $dotaz, array $parametry = array()): string
    {
        $vysledek = self::dotazJeden($dotaz, $parametry);
        return $vysledek[0];
    }

    /**
     * Spustí dotaz a vrátí počet ovlivněných řádků
     * @param string $dotaz SQL dotaz s parametry nahrazenými otazníky
     * @param array $parametry Parametry pro doplnění do připraveného SQL dotazu
     * @return int Počet ovlivněných řádků
     */
    public static function dotaz(string $dotaz, array $parametry = array()): int
    {
        $navrat = self::$spojeni->prepare($dotaz);
        $navrat->execute($parametry);
        return $navrat->rowCount();
    }

    /**
     * Vloží do tabulky nový řádek jako data z asociativního pole
     * @param string $tabulka Název databázové tabulky
     * @param array $parametry Asociativní pole parametrů pro vložení
     * @return bool TRUE v případě úspěšného provedení dotazu
     */
    public static function vloz(string $tabulka, array $parametry = array()): bool
    {
        $parametry = array_map('trim', $parametry); // Přidání trim() ke všem hodnotám v poli

        return self::dotaz("INSERT INTO `$tabulka` (`" .
                        implode('`, `', array_keys($parametry)) .
                        "`) VALUES (" . str_repeat('?,', sizeOf($parametry) - 1) . "?)",
                        array_values($parametry));
    }

    /**
     * Změní řádek v tabulce tak, aby obsahoval data z asociativního pole
     * @param string $tabulka Název databázové tabulky
     * @param array $hodnoty Asociativní pole hodnot ke změně
     * @param string $podminka Podmínka pro ovlivňované záznamy ("WHERE ...")
     * @param array $parametry Asociativní pole dalších parametrů
     * @return bool TRUE v případě úspěšného provedení dotazu
     */
    public static function zmen(string $tabulka, array $hodnoty, string $podminka, array $parametry = array()): bool
    {
        $hodnoty = array_map('trim', $hodnoty); // Přidání trim() ke všem hodnotám v poli

        return self::dotaz("UPDATE `$tabulka` SET `" .
                        implode('` = ?, `', array_keys($hodnoty)) .
                        "` = ? " . $podminka,
                        array_merge(array_values($hodnoty), $parametry));
    }

    /**
     * Vrací ID posledně vloženého záznamu
     * @return int ID posledního vloženého záznamu
     */
    public static function posledniId(): int
    {
        return self::$spojeni->lastInsertId();
    }

    /**
     * Ověří, zda uživatel s daným ID existuje v databázi
     * @param int $id ID uživatele
     * @return bool TRUE, pokud uživatel existuje, jinak FALSE
     */
    public static function existujeUzivatel(int $id): bool
    {
        $dotaz = "SELECT COUNT(*) FROM uzivatele WHERE uzivatele_id = ?";
        $pocetUzivatelu = self::dotazSamotny($dotaz, array($id));

        return $pocetUzivatelu > 0;
    }
}
