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
            if ($value==0 | $value=='0'){ $value="''"; $var_disabled='';}
            echo "<input id=$nb_row$nb_col name=$nb_row$nb_col  type='number' class='cell-input' value=$value maxlength='1' min='0' max='9'  $var_disabled>";
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


// Complète une grille avec une résolution logique
function next_move_resolution($grid) {
    $grid_play = unserialize(serialize($grid));
    $count = 0;

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
                        return [$test_number, $pos_number[0][0], $pos_number[0][1]];
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
                        return [$test_number, $pos_number[0][0], $pos_number[0][1]];
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
                        return [$test_number, $pos_number[0][0], $pos_number[0][1]];
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
                        return [$possibility[0], $row, $col];
                    }
                }
            }
        }
    }

    return [0,0,0];
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



//return True si les deux grilles ont les mêmes valeurs
function compare_grid($grid1, $grid2){
    if (count($grid1) != count($grid2)) {
        return false;
    }
    for ($row = 0; $row < 9; $row++){
        for ($col = 0; $col < 9; $col++){
            if($grid1[$row][$col]!=$grid2[$row][$col]){
                return false;
            }   
        }
    }
    return true;
}


?>