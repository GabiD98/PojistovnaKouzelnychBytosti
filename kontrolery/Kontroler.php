<?php

/**
 * Obecný kontroler, ze kterého dědí ostatní kontrolery
 */
abstract class Kontroler
{

    /**
     * @var array Pole pro data získaná od modelu k předání pohledu
     */
    protected array $data = array();

    /**
     * @var string Název pohledu bez přípony
     */
    protected string $pohled = "";

    /**
     * @var array|string[] Hlavička HTML stránky s povinnými atributy
     */
    protected array $hlavicka = array('titulek' => '', 'popis' => '', 'klicovaSlova' => '');

    /**
     * Ošetří proměnnou pro výpis do HTML stránky
     * @param mixed|null $x Proměnná k ošetření
     * @return mixed Proměnná ošetřená proti XSS útoku
     */
    private function osetri(mixed $x = null): mixed
    {
        if (!isset($x)) {
            return null;
        } elseif (is_string($x)) {
            return htmlspecialchars(trim($x), ENT_QUOTES);
        } elseif (is_array($x)) {
            foreach ($x as $k => $v) {
                $x[$k] = $this->osetri($v);
            }
            return $x;
        } else {
            return $x;
        }
    }

    /**
     * Vyrenderuje pohled
     * @return void
     */
    public function vypisPohled(): void
    {
        if ($this->pohled) {
            extract($this->osetri($this->data));
            extract($this->data, EXTR_PREFIX_ALL, "");
            require("pohledy/" . $this->pohled . ".phtml");
        }
    }

    /**
     * Přidá zprávu pro uživatele
     * @param string $zprava Hláška k zobrazení
     * @param $typ Nastaví typ vzhledu zprávy
     * @return void
     */
    public function pridejZpravu(string $zprava, $typ = 'info'): void
    {
        if (isset($_SESSION['zpravy'])) {
            $_SESSION['zpravy'][] = [$typ => $zprava];
        } else {
            $_SESSION['zpravy'][] = [$typ => $zprava];
        }
    }

    /**
     * Vrátí zprávy pro uživatele
     * @return array Všechny uložené hlášky k zobrazení
     */
    public function vratZpravy(): array
    {
        if (isset($_SESSION['zpravy'])) {
            $zpravy = $_SESSION['zpravy'];
            unset($_SESSION['zpravy']);
            return $zpravy;
        } else {
            return array();
        }
    }

    /**
     * Přesměruje na jinou stránku a zastaví zpracování současného skriptu
     * @param string $url URL adresa stránky, na kterou chceme přesměrovat
     * @return never
     */
    public function presmeruj(string $url): never
    {
        header("Location: /$url");
        header("Connection: close");
        exit;
    }

    /**
     * Ověří, zda je přihlášený uživatel, případně přesměruje na úvod
     * @param bool $uzivatel TRUE, pokud je přihlášený uživatel
     * @return void
     */
    public function overUzivatele(bool $uzivatel = false): void
    {
        // Vytvoření instance modelu, který nám umožní pracovat s uživatelem
        $spravceUzivatelu = new SpravceUzivatelu();
        $prihlasenyUzivatel = $spravceUzivatelu->vratUzivatele();
        if (!$prihlasenyUzivatel) {
            $this->pridejZpravu('Nedostatečná oprávnění', 'upozorneni');
            $this->presmeruj('uvod');
        } elseif (!Db::existujeUzivatel($prihlasenyUzivatel['uzivatele_id'])) {
            $spravceUzivatelu->odhlas();
            $this->pridejZpravu('Nedostatečná oprávnění', 'upozorneni');
            $this->presmeruj('uvod');
        }
    }

    /**
     * Ověří, zda je přihlášený uživatel administrátor, případně přesměruje na úvod
     * @param bool $admin TRUE, pokud je přihlášený uživatel administrátor
     * @return void
     */
    public function overAdmina(bool $admin = false): void
    {
        // Vytvoření instance modelu, který nám umožní pracovat s uživatelem
        $spravceUzivatelu = new SpravceUzivatelu();
        $prihlasenyUzivatel = $spravceUzivatelu->vratUzivatele();
        if (!$prihlasenyUzivatel || ($admin && !$prihlasenyUzivatel['admin'])) {
            $this->pridejZpravu('Nedostatečná oprávnění', 'upozorneni');
            $this->presmeruj('uvod');
        }
    }

    /**
     * Funkce pro zjištění věku pojištěnce nebo uživatele z data narození
     * @param DateTime $datumNarozeni
     * @return int|null Vrátí věk, pokud je zadané datum narození, nebo NULL, pokud datum narození zadáno není
     */
    public function vypocitejVek($datumNarozeni): int|null
    {

        if (isset($datumNarozeni) && $datumNarozeni !== '0000-00-00' && $datumNarozeni !== '0') {
            $datumNarozeni = new DateTime($datumNarozeni);
            $dnesniDatum = new DateTime();
            $vek = $datumNarozeni->diff($dnesniDatum)->y;
            return $vek;
        } else {
            return null;
        }
    }

    /**
     * Hlavní metoda pro zpracování parametrů
     * @param array $parametry Pole parametrů
     * @return void
     */
    abstract function zpracuj(array $parametry): void;
}
