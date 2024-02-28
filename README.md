<h2>Pojišťovací aplikace</h2>
<p>Tato aplikace slouží pro evidenci pojištěnců a jejich pojištění. Přihlášení uživatelé ji mohou používat k zobrazení dat, administrátoři mohou mimo to data přidávat, odstraňovat nebo upravovat.</p>
<h3>Instalace</h3>
<ol>
  <li><strong>Stažení zdrojových kódů</strong></br>git clone https://github.com/GabiD98/ZaverecnyProjekt.git</li></br>
  <li><strong>Nastavení databáze</strong>
    <ul>
      <li>Vytvořte novou MySQL databázi.</li>
      <li>Naimportujte SQL soubor pojisteni.sql.</li>
    </ul>   
  </li></br>   
  <li><strong>Konfigurace připojení k databázi</strong>
    <ul>
      <li>Otevřete soubor config.php.</li>
      <li>Upravte údaje pro připojení k databázi (DB_HOST, DB_USER, DB_PASS, DB_NAME) podle vašeho nastavení.</li>
    </ul>
  </li></br>
  <li><strong>Spusťte aplikaci.</strong></br>Otevřete aplikaci ve webovém prohlížeči.</li>
</ol>
<h3>Použití</h3>
<ol>
  <li><strong>Registrace</strong>
    <ul>
      <li>Zaregistrujte se. Uživatelské jméno a heslo vám poté bude sloužit pro přihlášení.</li>
      <li>Pro roli administrátora vstupte do databáze do tabulky 'uzivatele' a změňte hodnotu ve sloupci 'admin' na 1.</li></br>
    </ul>
  </li>
  <li><strong>Funkce</strong>
    <ul>
      <li><strong>Přihlášení uživatelé bez administrátorských oprávnění</strong> mohou upravit své osobní údaje na svém profilu. Nemohou provádět jiné změny, aplikace jim slouží pouze k zobrazení a čtení údajů o pojištěncích a jejich pojištěních.</li>
      <li><strong>Administrátoři</strong> mohou kromě úpravy svého profilu přidávat, editovat a odstraňovat pojištěnce. Ke každému pojištěnci mohou přidat pojištění, které následně mohou editovat nebo odstranit.</li>
    </ul>
  </li>
</ol>
<h3>Dodatek</h3>
<strong>Plánované implementace:</strong>
<ul>
  <li>připojení pojistné události k pojištěnci za využití jeho pojištění,</li>
  <li>možnost pro pojištěnce spravovat svůj profil a svá pojištění coby uživatelé.</li>
</ul>
