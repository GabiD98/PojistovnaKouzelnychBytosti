
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
