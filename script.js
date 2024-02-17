// Kurzor myši - hůlka
document.addEventListener('mousemove', function (event) {
    const cursor = document.querySelector('.custom-cursor');
    cursor.style.top = event.pageY + 'px';
    cursor.style.left = event.pageX + 'px';
});

// Odstranit všechny znaky kromě čísel na vstupu
function validovatCislo(input) {
    input.value = input.value.replace(/[^0-9]/g, '');
}

// Funkce pro validaci částky za pojištění
function validovatCastku(input) {
    // Odstranit všechny nečíselné znaky
    input.value = input.value.replace(/\D/g, '');
    // Omezit délku na 7 znaků
    if (input.value.length > 7) {
        input.value = input.value.slice(0, 7);
    }
}
// Různé podmínky pro různá pojištění
function podminkyPojisteni() {
    // Nastavení různých hodnot výběrů pojištění 
        var typPojisteniSelect = document.querySelector('select[name="typ_pojisteni"]');
        var predmetPojisteniSelect = document.querySelector('select[name="predmet_pojisteni"]');
        var frekvencePlatbySelect = document.querySelector('select[name="frekvence_platby"]');

        typPojisteniSelect.addEventListener('change', function () {
            var selectedValue = typPojisteniSelect.value;

            // Nastavení možností frekvence platby v závislosti na vybraném typu pojištění
            var frekvenceOptions = {
                'Cestovní': ['Jednorázově', 'Měsíčně', 'Čtvrtletně', 'Ročně'],
                'Úrazové': ['Měsíčně', 'Čtvrtletně', 'Ročně'],
                'Nemocenské': ['Měsíčně', 'Čtvrtletně', 'Ročně'],
                'Důchodové': ['Měsíčně', 'Čtvrtletně', 'Ročně'],
                'Havarijní': ['Měsíčně', 'Čtvrtletně', 'Ročně']
            };

            // Nastavení možností předmětu pojištění v závislosti na vybraném typu pojištění
            var predmetOptions = {
                'Cestovní': ['Pojištěnec', 'Příbuzný klienta'],
                'Úrazové': ['Pojištěnec', 'Příbuzný klienta'],
                'Nemocenské': ['Pojištěnec', 'Příbuzný klienta'],
                'Důchodové': ['Pojištěnec'],
                'Havarijní': ['Vozidlo', 'Nemovitost']
            };

            // Nastavení možností frekvence platby
            frekvencePlatbySelect.innerHTML = '';
            frekvenceOptions[selectedValue].forEach(function (optionValue) {
                var option = document.createElement('option');
                option.value = optionValue;
                option.text = optionValue;
                frekvencePlatbySelect.add(option);
            });

            // Nastavení možností předmětu pojištění
            predmetPojisteniSelect.innerHTML = '';
            predmetOptions[selectedValue].forEach(function (optionValue) {
                var option = document.createElement('option');
                option.value = optionValue;
                option.text = optionValue;
                predmetPojisteniSelect.add(option);
            });
        });

        // Spuštění události change po načtení DOM
        typPojisteniSelect.dispatchEvent(new Event('change'));
}

// Funkce pro validaci formulářů včetně validace data narození
function validaceFormulare() {
    var tlacitkaKontroly = document.querySelectorAll('.tlacitkoKontrola');
    var tlacitkaKontroly2 = document.querySelectorAll('.tlacitkoKontrola2');
    var modalyUloz = document.querySelectorAll('.uloz');
    var modalyUloz2 = document.querySelectorAll('.uloz2');

    tlacitkaKontroly.forEach(function (tlacitkoKontrola) {
        tlacitkoKontrola.addEventListener('click', function (event) {
            var formular = tlacitkoKontrola.closest('form');
            if (!formular.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                formular.classList.add('was-validated');
            } else {
                modalyUloz.forEach(function (modalUloz) {
                    new bootstrap.Modal(modalUloz).show();
                });
            }
        });
    });

    tlacitkaKontroly2.forEach(function (tlacitkoKontrola2) {
        tlacitkoKontrola2.addEventListener('click', function (event) {
            var formular = tlacitkoKontrola2.closest('form');
            if (!formular.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                formular.classList.add('was-validated');
            } else {
                modalyUloz2.forEach(function (modalUloz2) {
                    new bootstrap.Modal(modalUloz2).show();
                });
            }
        });
    });
}
// Zakáže odeslání nezvalidovaného formuláře enterem
function neodesilatEnterem() {
    var formulare = document.querySelectorAll('form');

    formulare.forEach(function(formular) {
        formular.addEventListener('keypress', function(event) {
            if (event.key === 'Enter') {
                if (!formular.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                    formular.classList.add('was-validated');
                }
            }
        });
    });
}
