
 //---------- Accessibilité du bouton Validate -------------------

const form = document.getElementById('myForm');
const submitValidate = document.getElementById('submit_validate');
const inputs = form.querySelectorAll('input');

// Fonction pour vérifier si tous les inputs requis sont remplis
function checkInputs() {
    let allFilled = true;
    inputs.forEach(input => {
        if (!input.value | input.value==0) {
            allFilled = false;  
        }
    });
    submitValidate.disabled = !allFilled; // Activer ou désactiver le bouton en fonction de la vérification
}

// Ajouter un écouteur d'événements sur chaque input pour vérifier les changements
inputs.forEach(input => {
    input.addEventListener('input', checkInputs);
 });

// Vérifier initialement si les champs sont remplis ou non
checkInputs();



 //---------- Envoie formulaire Validate (Ajax JQuery) -------------------

$(document).ready(function () {
    $('#submit_validate').click(function () {
        // Récupérer les données du formulaire
        var gridData = [];
        for (var row = 1; row <= 9; row++) {
            var lineData = [];
            for (var col = 1; col <= 9; col++) {
                var inputValue = $('input[name="' + row + col + '"]').val();
                lineData.push(inputValue === "" ? 0 : parseInt(inputValue));
            }
            gridData.push(lineData);
        }
        console.log(gridData);

        // Envoyer les données avec AJAX
        $.ajax({
            type: 'POST',
            url: 'php_function/validate_grid.php',
            data: { ajax_grid_data: JSON.stringify(gridData) },
            success: function (response) {
                // Afficher la réponse dans le div "resultat"
                $('#result_validation').html(response);
            },
            error: function () {
                $('#result_validation').text('Erreur lors de l\'envoi des données.');
            }
        });
    });
});




 //---------- Surbrillance des cases pour les mêmes nombres -------------------

const cells = document.querySelectorAll('.cell-input');

// Ajoutez les événements mouseover et mouseout
cells.forEach(cell => {
    cell.addEventListener('mouseover', () => {
        const currentValue = cell.value;
        if (currentValue) { // Vérifiez que la valeur n'est pas vide
            // Appliquez la classe highlight aux autres cases avec la même valeur
            cells.forEach(otherCell => {
                if (otherCell.value === currentValue) {
                    otherCell.classList.add('highlight');
                }
            });
        }
    });

    cell.addEventListener('mouseout', () => {
        // Enlevez la classe highlight lorsque le survol se termine
        cells.forEach(otherCell => {
            otherCell.classList.remove('highlight');
        });
    });
});

