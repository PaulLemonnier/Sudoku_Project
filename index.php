<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no"/>

    <link rel="icon" href="images/grid.ico" type="image/x-icon">

    <link href="https://fonts.googleapis.com/css2?family=Oswald&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
                <li><button type='submit' name="submit_generate" style='height:55px;'>Generate new grid</button></li>
                <!-- <li><button type='button'>Reset</button></li> -->
            </ul>
        </nav>
    </div>
</header>

<?php

    //---------- Connexion à la BDD -------------------

    $databaseFile = 'Database/grid_sudoku.db';

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
        echo "Completed grids : $point";
    }
    show_score($pdo);
    ?>

    </div>
    
    <div style='text-align: center;margin-bottom:10px;'>
        
            <button class='play_button' type='submit' name="submit_save">Save</button>
            <button id='submit_validate' class='play_button' type='submit' name="submit_validate">Validate</button>
            <button id='submit_hint' class='play_button hint_button' type='button' name='submit_hint' style='width:40px;margin-left:0px;'><i class='fas fa-lightbulb'></i></button>
            <span id="result_validation">
            <?php
            //permet de savoir si la grille a été validé
            $session = recover_bdd_session($pdo);
            if ($session==0){echo "<span style='font-weight:bold;color:#f42e35'>Essaye encore !</span>";}
            elseif ($session==1){echo "<span style='color:#34c434'>Bravo ! +1</span>";}
            elseif ($session==6){echo "<span style='color:#ffa51d'>Tricheur !</span>";}
            
            ?>
            </span>


    <?php

    // Si le bouton Save est pressé ajoute la grille actuelle dans la BDD
    if (isset($_POST['submit_save'])) {

        // Récupération de la grille actuelle
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

        header('Location: index.php');
        exit;
    }



    // Si le bouton Generate est pressé génère une grille et l'insère dans la BDD
    if (isset($_POST['submit_generate'])) {
        
        $new_grid = choose_sudoku_difficulty("Normal");

        generate_bdd_grid($pdo, $new_grid);

        update_session_validation_bdd($pdo, 2);

        header('Location: index.php');
        exit;
    }


    // Si le bouton Validate est pressé génère vérifie la solution et ajoute les points
    if (isset($_POST['submit_validate'])) {

        // Récupération de la grille actuelle
        $actual_grid = array();
        for ($row = 1; $row <= 9; $row++) {
            $line_to_bdd = array();
            for ($col = 1; $col <= 9; $col++) {
                $val_input = $_POST[strval($row).strval($col)]; //récupère toutes les cases et les insère dans une array
                if ($val_input==""){$val_input=0;}
                array_push($line_to_bdd,intval($val_input));
            }
            array_push($actual_grid,$line_to_bdd);
        }
        
        
        $resultat = recover_bdd_grids($pdo); //récupère les grilles
        $init_grid = array_values($resultat['init_grid']); //récupère la grille initiale
        solve_grid($init_grid); //transforme la grille initial en grille solution
        $session = recover_bdd_session($pdo);

        //si la grille résultat est égale à notre grille et qu'elle n'a pas déjà été validé
        if (compare_grid($init_grid, $actual_grid) && $session!=1) { 
            update_session_validation_bdd($pdo, 1); //met à jour la session dans la BDD
            update_bdd_point($pdo); //met à jour les points dans la BDD
        } elseif($session==1) {
            update_session_validation_bdd($pdo, 6); // dans le cas où l'utilisateur revalide après avoir déjà valider sa grille
        }else {
            update_session_validation_bdd($pdo, 0);
        }
        
        header('Location: index.php');
        exit;
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


    <script src="js/sudoku.js"></script>




</br>
</body>

</form>



</html>

