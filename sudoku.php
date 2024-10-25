<!DOCTYPE html>
<html>

<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no"/>

<!-- D:\Paul\Documents\Programmation\Projets -->

<style> 
/* Styles généraux */
html{	font-family: 'Oswald';background-color: rgb(37,44,74);color:white;	}

/* Styles de la grille de Sudoku */
.sudoku-grid {
    margin-left: auto;
    margin-right: auto;
    display: grid;
    grid-template-columns: repeat(9, 1fr);
    grid-template-rows: repeat(9, 1fr);
    width: 90vmin;
    height: 90vmin;
    max-width: 500px;
    max-height: 500px;
    border: 3px solid black;
    /* box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); */
}

.cell-input:hover {
    background-color: #edeeff;
}

.cell-input {
    border: 1px solid #999;
    display: flex;
    align-items: center;
    font-size: 24px;
    text-align: center;
    background-color: #fff;
    width: 100%;
    height: 100%;
    box-sizing: border-box;
    outline: none;
}

/* Bordures plus épaisses pour les blocs de 3x3 */
.sudoku-grid input:nth-child(9n+3), 
.sudoku-grid input:nth-child(9n+6) {
    border-right: 2px solid black;
}

.sudoku-grid input:nth-child(n+19):nth-child(-n+27), 
.sudoku-grid input:nth-child(n+46):nth-child(-n+54) {
    border-bottom: 2px solid black;
}

.sudoku-grid input:nth-child(9n+1) {
    border-left: 2px solid black;
}

.sudoku-grid input:nth-child(-n+9) {
    border-top: 2px solid black;
}


.header {
    background-color: None;
    border-radius: 20px;
    padding: 10px;
    margin: 20px;
    margin-bottom: 40px;
    text-align: center;
    color: white;
    box-shadow: 0 4px 8px white; /* rgba(67, 170, 224, 1) */
}

/* Conteneur du header */
.container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1200px;
    margin: 0 auto;
}

/* Styles pour la navigation */
nav ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    gap: 15px;
}

nav ul li {
    display: inline;
}

nav ul li a {
    color: white;
    text-decoration: none;
    padding: 5px 5px;
    border-radius: 5px;
    transition: background-color 0.1s;
}

nav ul li a:hover {
    background-color: rgb(32, 74, 108);
}


button{
    font-family: 'Oswald';
    height: 35px;
    width: 80px;	
    border-radius: 5px;
    background: rgb(57,64,94);
    color: white;
    border-color: white;

}

button:hover{
		background-color: #204a6c;
}

.play_button{
    margin-left:20px;
    margin-right:20px;

}

</style>



<head>
    <title>Sudoku</title>
</head>


<form method="POST" action="">

<header class="header">
    <div class="container">
        <h1>MySudoku</h1>
        <nav>
            <ul>
                <li><button type='submit' name="submit_generate">Generate</button></li>
                <li><button type='button'>Reset</button></li>
            </ul>
        </nav>
    </div>
</header>



<body>
    
    <div style='text-align: center;margin-bottom:10px;'>
        
            <button class='play_button' type='submit' name="submit_save">Save</button>
            <button class='play_button' type='submit' name="submit_validate" disabled>Validate</button>



    <?php 
    
    $possibility = [1,2,3];

    $var1 = 1; 
    $var2 = 2;

    ?>

    <?php

    $databaseFile = 'db_sudoky.db';

    try {
        $pdo = new PDO("sqlite:$databaseFile"); // Connexion à la base de données SQLite        
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Configuration pour générer des exceptions en cas d'erreur
    } catch (PDOException $e) { // Gestion des erreurs
        echo "Erreur de connexion ou d'exécution : " . $e->getMessage();
    }

    //---------- Récupération de la grille depuis la BDD -------------------

    function recover_bdd_grids($pdo){
        $sql_select_init = "SELECT line1,line2,line3,line4,line5,line6,line7,line8,line9 FROM sudoku_grid ORDER BY id ASC LIMIT 1"; // Requête SQL à exécuter
        $stmt = $pdo->query($sql_select_init); // Exécution de la requête
        $result = $stmt->fetch(PDO::FETCH_ASSOC); // Récupère les résultats
        $grid_bdd_init = array_map('json_decode', $result); //redimensionne les array

        $sql_select_last = "SELECT line1,line2,line3,line4,line5,line6,line7,line8,line9 FROM sudoku_grid ORDER BY id DESC LIMIT 1"; // Requête SQL à exécuter
        $stmt = $pdo->query($sql_select_last); // Exécution de la requête
        $result = $stmt->fetch(PDO::FETCH_ASSOC); // Récupère les résultats
        $grid_bdd_last = array_map('json_decode', $result); //redimensionne les array

        return ['init_grid' => $grid_bdd_init, 'last_grid' => $grid_bdd_last];
    }

    function show_grid_mix($pdo){
        $resultat = recover_bdd_grids($pdo);
        
        $init_grid = $resultat['init_grid'];
        $last_grid = $resultat['last_grid'];

        print_r($last_grid[0]);
        // TODO  VOIR COMMENT PARCOURIR LES DEUX GRIDS
        for ($row = 0; $row < 9; $row++){
            for ($col = 0; $col < 9; $col++){
                $nb_row = $row+1;
                $nb_col = $col+1;
                // if ($init_grid[$row][$col]==$last_grid[$row][$col]){
                    // $value = $last_grid[$row][$col];
                    // if ($value==0){ 
                    //     $value=""; 
                    //     echo "<input id=$nb_row$nb_col name=$nb_row$nb_col  type='number' class='cell-input' maxlength='1' min='0' max='9' value=$value>";
                    // }else{
                    //     echo "<input id=$nb_row$nb_col name=$nb_row$nb_col  type='number' class='cell-input' maxlength='1' min='0' max='9' value=$value readonly>";
                    // }
                // }else{
                //     $value = $last_grid[$row][$col];
                //     echo "<input id=$nb_row$nb_col name=$nb_row$nb_col  type='number' class='cell-input' maxlength='1' min='0' max='9' value=$value>";
                // }
                // echo "<input id=$nb_row$nb_col name=$nb_row$nb_col  type='number' class='cell-input' maxlength='1' min='0' max='9' value=''>";
            }
        }
    }

    
    //---------- Insertion de la grille courante dans la BDD (à la dernière position) -------------------

    function insert_bdd_grid($pdo, $grid_to_bdd){
        $lineData1 = json_encode($grid_to_bdd[0]); // Convertir la liste en JSON
        $lineData2 = json_encode($grid_to_bdd[1]);
        $lineData3 = json_encode($grid_to_bdd[2]);
        $lineData4 = json_encode($grid_to_bdd[3]);
        $lineData5 = json_encode($grid_to_bdd[4]);
        $lineData6 = json_encode($grid_to_bdd[5]);
        $lineData7 = json_encode($grid_to_bdd[6]);
        $lineData8 = json_encode($grid_to_bdd[7]);
        $lineData9 = json_encode($grid_to_bdd[8]);

        $sql_insert = "INSERT INTO sudoku_grid (line1,line2,line3,line4,line5,line6,line7,line8,line9) 
        VALUES (:lineData1,:lineData2,:lineData3,:lineData4,:lineData5,:lineData6,:lineData7,:lineData8,:lineData9)"; // Requête d'insertion dans la BDD
        // $sql_update = "UPDATE sudoku_grid SET line1=:lineData1, line2=:lineData2, line3=:lineData3, line4=:lineData4, line5=:lineData5, line6=:lineData6, line7=:lineData7, line8=:lineData8, line9=:lineData9
        // WHERE line1 = (SELECT line1 FROM sudoku_grid LIMIT 1)";
        $stmt = $pdo->prepare($sql_insert);
        $stmt->execute([':lineData1' => $lineData1,':lineData2' => $lineData2,':lineData3' => $lineData3,':lineData4' => $lineData4,':lineData5' => $lineData5,':lineData6' => $lineData6,':lineData7' => $lineData7,':lineData8' => $lineData8,':lineData9' => $lineData9]); // Exécuter la requête en passant les paramètres
    }

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

    //---------- Génération d'une nouvelle grille et génération de la table dans la BDD -------------------

    function generate_bdd_grid($pdo, $grid){
        $lineData1 = json_encode($grid[0]); // Convertir la liste en JSON
        $lineData2 = json_encode($grid[1]);
        $lineData3 = json_encode($grid[2]);
        $lineData4 = json_encode($grid[3]);
        $lineData5 = json_encode($grid[4]);
        $lineData6 = json_encode($grid[5]);
        $lineData7 = json_encode($grid[6]);
        $lineData8 = json_encode($grid[7]);
        $lineData9 = json_encode($grid[8]);

        $sql_drop = "DROP TABLE IF EXISTS sudoku_grid";
        $pdo->query($sql_drop);
        $sql_create = "CREATE TABLE IF NOT EXISTS sudoku_grid(
               id INTEGER PRIMARY KEY AUTOINCREMENT, 
               line1 TEXT, line2 TEXT, line3 TEXT, line4 TEXT, line5 TEXT, line6 TEXT, line7 TEXT, line8 TEXT, line9 TEXT)";
        $pdo->query($sql_create);
        $sql_insert = "INSERT INTO sudoku_grid (line1,line2,line3,line4,line5,line6,line7,line8,line9) 
        VALUES (:lineData1,:lineData2,:lineData3,:lineData4,:lineData5,:lineData6,:lineData7,:lineData8,:lineData9)"; // Requête d'insertion dans la BDD
        $stmt = $pdo->prepare($sql_insert); // Exécution de la requête
        $stmt->execute([':lineData1' => $lineData1,':lineData2' => $lineData2,':lineData3' => $lineData3,':lineData4' => $lineData4,':lineData5' => $lineData5,':lineData6' => $lineData6,':lineData7' => $lineData7,':lineData8' => $lineData8,':lineData9' => $lineData9]); // Exécuter la requête en passant les paramètres
    }


    // Si le bouton Generate est pressé
    if (isset($_POST['submit_generate'])) {

        $new_grid = choose_sudoku_difficulty("Normal");
        generate_bdd_grid($pdo, $new_grid);
    }

    ?>


    </div>


    <div class="sudoku-grid">

        <!-- Générer les 81 cases de la grille -->
        <?php 

        show_grid_mix($pdo);
        // $pdo = null; // Fermeture de la connexion

        ?>

    </div>

</body>
</form>

<?php

// Création d'une grille contenant une première ligne de valeur aléatoire
function init_grid() {
    $grid = array_fill(0, 9, array_fill(0, 9, 0)); 
    $random_line = range(1, 9); 
    shuffle($random_line);
    $grid[0] = $random_line;
    return $grid;
}

// Insertion dans la page d'une grille
function show_grid($grid){
    $nb_row = 0;
    foreach ($grid as $line) { 
        $nb_row++;
        $nb_col = 0;
        foreach ($line as $value) {
            $nb_col++;
            $var_disabled='readonly';
            if ($value==0){ $value=""; $var_disabled='';}
            echo "<input id=$nb_row$nb_col name=$nb_row$nb_col  type='number' class='cell-input' maxlength='1' min='0' max='9' value=$value $var_disabled>";
        }
    }
}

// Vérifie si une cellule est remplie
function is_empty_cell($grid,$row,$col){
    return $grid[$row][$col]==0;
}

// Liste les possibilités de remplissage pour une position
function list_possibility($grid, $x, $y) {
    $possibility = range(1, 9);

    for ($rc = 0; $rc < 9; $rc++) {
        if (($key = array_search($grid[$x][$rc], $possibility)) !== false) {
            unset($possibility[$key]);
        }
        if (($key = array_search($grid[$rc][$y], $possibility)) !== false) {
            unset($possibility[$key]);
        }
    }

    $corner_x = intval($x / 3) * 3;
    $corner_y = intval($y / 3) * 3;
    for ($row = $corner_x; $row < $corner_x + 3; $row++) {
        for ($col = $corner_y; $col < $corner_y + 3; $col++) {
            if (($key = array_search($grid[$row][$col], $possibility)) !== false) {
                unset($possibility[$key]);
            }
        }
    }
    return array_values($possibility);
}

// Solutionne une grille en argument
function solve_grid(&$grid) {
    for ($x = 0; $x < 9; $x++) {
        for ($y = 0; $y < 9; $y++) {
            if (is_empty_cell($grid, $x, $y)) {
                $possibility = list_possibility($grid, $x, $y);
                shuffle($possibility);
                foreach ($possibility as $p) {
                    // echo "$p | $x, $y\n";
                    $grid[$x][$y] = $p;
                    if (solve_grid($grid)) {
                        return true;
                    }
                    $grid[$x][$y] = 0;
                }
                return false;
            }
        }
    }
    return true;
}


// Vérifie sur une grille est complété entièrement
function is_all_cell_complete($grid){
    for ($x = 0; $x < 9; $x++) {
        for ($y = 0; $y < 9; $y++) {
            if (is_empty_cell($grid,$x,$y)){
                return false;
            }
        }
    }
    return true;
}


// Complète une grille avec une résolution logique
function human_logical_resolution($grid) {
    $grid_play = unserialize(serialize($grid));
    $count = 0;
    $number_complete = 0;

    while (!is_all_cell_complete($grid_play) && $count < 10) {
        $count++;

        // Parcours tous les nombres
        for ($test_number = 1; $test_number <= 9; $test_number++) {

            // Recherche du nombre dans les lignes (vérifie si le nombre $test_number a une seule possibilité de placement dans la ligne)
            for ($row = 0; $row < 9; $row++) {
                if (!in_array($test_number, $grid_play[$row])) {
                    $pos_number = [];
                    for ($col = 0; $col < 9; $col++) {
                        if (is_empty_cell($grid_play, $row, $col)) {
                            if (in_array($test_number, list_possibility($grid_play, $row, $col))) {
                                $pos_number[] = [$row, $col];
                            }
                        }
                    }
                    if (count($pos_number) === 1) {
                        $number_complete++;
                        $grid_play[$pos_number[0][0]][$pos_number[0][1]] = $test_number;
                    }
                }
            }

            // Recherche du nombre dans les colonnes (vérifie si le nombre $test_number a une seule possibilité de placement dans la colonne)
            for ($col = 0; $col < 9; $col++) {
                $column_numbers = array_column($grid_play, $col);
                if (!in_array($test_number, $column_numbers)) {
                    $pos_number = [];
                    for ($row = 0; $row < 9; $row++) {
                        if (is_empty_cell($grid_play, $row, $col)) {
                            if (in_array($test_number, list_possibility($grid_play, $row, $col))) {
                                $pos_number[] = [$row, $col];
                            }
                        }
                    }
                    if (count($pos_number) === 1) {
                        $number_complete++;
                        $grid_play[$pos_number[0][0]][$pos_number[0][1]] = $test_number;
                    }
                }
            }

            // Recherche du nombre dans les blocs 3x3
            foreach ([[0,0], [0,3], [0,6], [3,0], [3,3], [3,6], [6,0], [6,3], [6,6]] as $pos_corner) {
                $corner_x = $pos_corner[0];
                $corner_y = $pos_corner[1];
                $list_number_case = [];

                for ($row = $corner_x; $row < $corner_x + 3; $row++) {
                    for ($col = $corner_y; $col < $corner_y + 3; $col++) {
                        $list_number_case[] = $grid_play[$row][$col];
                    }
                }

                if (!in_array($test_number, $list_number_case)) {
                    $pos_number = [];
                    for ($row = $corner_x; $row < $corner_x + 3; $row++) {
                        for ($col = $corner_y; $col < $corner_y + 3; $col++) {
                            if (is_empty_cell($grid_play, $row, $col)) {
                                if (in_array($test_number, list_possibility($grid_play, $row, $col))) {
                                    $pos_number[] = [$row, $col];
                                }
                            }
                        }
                    }
                    if (count($pos_number) === 1) {
                        $number_complete++;
                        $grid_play[$pos_number[0][0]][$pos_number[0][1]] = $test_number;
                    }
                }
            }
        }

        // Recherche d'une possibilité unique (si la cellule n'a qu'une seule possibilité alors on la place)
        for ($row = 0; $row < 9; $row++) {
            for ($col = 0; $col < 9; $col++) {
                if (is_empty_cell($grid_play, $row, $col)) {
                    $possibility = list_possibility($grid_play, $row, $col);
                    if (count($possibility) === 1) {
                        $number_complete++;
                        $grid_play[$row][$col] = $possibility[0];
                    }
                }
            }
        }
    }

    return $grid_play;
}

// Vérifie si la résolutin est logique
function is_logical_resolvable($grid) {
    $grid_play = human_logical_resolution($grid);
    return is_all_cell_complete($grid_play);
}

// Compte le nombre de solution d'une grille
function count_solve_grid(&$grid) {
    $solution_count = 0;
    for ($x = 0; $x < 9; $x++) {
        for ($y = 0; $y < 9; $y++) {
            if (is_empty_cell($grid, $x, $y)) {
                $possibility = list_possibility($grid, $x, $y);
                shuffle($possibility);
                foreach ($possibility as $p) {
                    $grid[$x][$y] = $p;
                    $solution_count += count_solve_grid($grid);
                    if ($solution_count > 1) {
                        break;
                    }
                    $grid[$x][$y] = 0;
                }
                return $solution_count;
            }
        }
    }
    return 1;
}


// Fonction main de création du Sudoku
function create_sudoku($choiceNumberCell = 0, $logicalResolution = true) {
    // Génération de la grille pleine
    $gridMain = init_grid();
    solve_grid($gridMain);
    
    $coordinateGrid = [];
    for ($x = 0; $x < 9; $x++) {
        for ($y = 0; $y < 9; $y++) {
            $coordinateGrid[] = [$x, $y]; // Liste de coordonnées correspondant à la grille
        }
    }
    $keepNumber = 81;

    // Crée une variable pour le nombre de coordonnées à laisser en possibilité dans la grille
    $numberCoordinateGridLeft = 0;
    if ($logicalResolution) {
        $numberCoordinateGridLeft = 20;
    }
    
    // Supprime dans la grille un nombre aléatoire tant que c'est viable et qu'il n'y a pas x nombres supprimés
    while (count($coordinateGrid) > $numberCoordinateGridLeft) {
        $gridPlay = unserialize(serialize($gridMain)); // Clone de la grille
        $randomCoord = $coordinateGrid[array_rand($coordinateGrid)]; // Sélection aléatoire des coordonnées
        $valueGrid = $gridMain[$randomCoord[0]][$randomCoord[1]]; // Sauvegarde de la valeur à supprimer
        $gridMain[$randomCoord[0]][$randomCoord[1]] = 0; // Suppression de la valeur

        // Si l'utilisateur souhaite que la grille soit résoluble logiquement sinon la solution peut être supposée
        if ($logicalResolution) {
            if (!is_logical_resolvable($gridMain)) {
                $gridMain[$randomCoord[0]][$randomCoord[1]] = $valueGrid;
            } else {
                $keepNumber--;
            }
        } else {
            // S'il existe plus d'une solution dans la nouvelle grille (solution non viable) remet la valeur initiale sinon supprime de la liste les coordonnées
            if (count_solve_grid($gridPlay) != 1) {
                $gridMain[$randomCoord[0]][$randomCoord[1]] = $valueGrid;
            } else {
                $keepNumber--;
            }
        }
        unset($coordinateGrid[array_search($randomCoord, $coordinateGrid)]); // supprime les coordonnées une fois la suppression de la valeur ou si la suppression n'est pas viable

        // Si le nombre de cases restantes est plus grand que le choix du nombre de cellules de l'utilisateur, extrait la grille
        if ($keepNumber <= ($choiceNumberCell+0)) { 
            return [$keepNumber, $gridMain];
        }
    }
    return [$keepNumber, $gridMain];
}


// création du sudoku avec choix de difficulté
function choose_sudoku_difficulty($difficulty = "Normal") {
    if ($difficulty === "Extreme") {
        $count = 0;
        list($keep_number_min, $grid_min) = create_sudoku(false);
        
        while ($count < 5) { 
            list($keep_number, $grid) = create_sudoku(false);
            $count++;
            if ($keep_number < $keep_number_min) { 
                $keep_number_min = $keep_number;
                $grid_min = $grid;
            }
        }
        // echo $keep_number_min . "\n"; // Affiche le nombre minimum
        return $grid_min;
    }

    // Sinon, difficulté = Normal
    list($keep_number, $grid) = create_sudoku(true);
    // echo $keep_number . "\n"; // Affiche le nombre
    return $grid;
}


?>

</html>

