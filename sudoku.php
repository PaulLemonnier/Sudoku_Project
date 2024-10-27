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
        <h1 onclick="location.reload();">MySudoku</h1>
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

        header('Location: sudoku.php');
        exit;
    }



    // Si le bouton Generate est pressé génère une grille et l'insère dans la BDD
    if (isset($_POST['submit_generate'])) {
        $new_grid = choose_sudoku_difficulty("Normal");
        generate_bdd_grid($pdo, $new_grid);
        header('Location: sudoku.php');
        exit;
    }



    ?>


    </div>


    <div class="sudoku-grid">

        <!-- Générer les 81 cases de la grille -->
        <?php 

        show_grid_mix($pdo); // Affichage de la grille depuis la BDD
        // $pdo = null; // Fermeture de la connexion
        
        // $resultat = recover_bdd_grids($pdo);
    
        // $last_grid = array_values($resultat['last_grid']);

        // print_r(next_move_resolution($last_grid));
        ?>

    </div>

    <script src="js/sudoku.js"></script>



</body>

</form>



</html>

