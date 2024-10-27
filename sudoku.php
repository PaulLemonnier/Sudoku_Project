<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no"/>

    <link rel="icon" href="images/grid.ico" type="image/x-icon">

    <link rel="stylesheet" href="css/sudoku.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <?php include 'php_function/sudoku_basic_function.php'; ?>
    <?php include 'php_function/interaction_bdd_function.php'; ?>

    <title>Sudoku</title>

</head>

<style>
</style>



<form id="myForm" method="POST" action="">

<header class="header">
    <div class="container">
        <h1>MySudoku</h1>
        <nav>
            <ul>
                <li><button type='submit' name="submit_generate">Generate</button></li>
                <!-- <li><button type='button'>Reset</button></li> -->
            </ul>
        </nav>
    </div>
</header>

<?php

    //---------- Connexion à la BDD -------------------

    $databaseFile = 'Database/db_sudoky.db';

    try {
        $pdo = new PDO("sqlite:$databaseFile"); // Connexion à la base de données SQLite        
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Configuration pour générer des exceptions en cas d'erreur
    } catch (PDOException $e) { // Gestion des erreurs
        echo "Erreur de connexion ou d'exécution : " . $e->getMessage();
    }

?>

<body>

    <div style='text-align: center;margin-bottom:20px;'>

    <?php
    // Affichage du score
    function show_score($pdo){
        $sql_recover_point = "SELECT point FROM score LIMIT 1"; // Requête SQL à exécuter
        $stmt = $pdo->query($sql_recover_point); // Exécution de la requête
        $point = $stmt->fetch(PDO::FETCH_ASSOC)['point']; // Récupère les points
        echo "Nombre de grilles complétées : $point";
    }
    show_score($pdo);
    ?>

    </div>
    
    <div style='text-align: center;margin-bottom:10px;'>
        
            <button class='play_button' type='submit' name="submit_save">Save</button>
            <button id='submit_validate' class='play_button' type='button' name="submit_validate">Validate</button>
            <span id="result_validation"></span>


    <?php

    // Si le bouton Save est pressé
    if (isset($_POST['submit_save'])) {

        $grid_to_bdd = array();
        for ($row = 1; $row <= 9; $row++) {
            $line_to_bdd = array();
            for ($col = 1; $col <= 9; $col++) {
                $val_input = $_POST[strval($row).strval($col)]; //récupère toutes les cases et les insère dans une array
                if ($val_input==""){$val_input=0;}
                array_push($line_to_bdd,intval($val_input));
            }
            array_push($grid_to_bdd,$line_to_bdd);
        }
        insert_bdd_grid($pdo, $grid_to_bdd);
    }



    // Si le bouton Generate est pressé génère une grille et l'insère dans la BDD
    if (isset($_POST['submit_generate'])) {
        $new_grid = choose_sudoku_difficulty("Normal");
        generate_bdd_grid($pdo, $new_grid);
    }



    ?>


    </div>


    <div class="sudoku-grid">

        <!-- Générer les 81 cases de la grille -->
        <?php 

        show_grid_mix($pdo); // Affichage de la grille depuis la BDD
        // $pdo = null; // Fermeture de la connexion

        ?>

    </div>

<script>

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

</script>


<script>

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



</script>



</body>

</form>



</html>

