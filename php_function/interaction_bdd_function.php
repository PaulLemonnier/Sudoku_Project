<?php

//---------- Récupération et affichage de la grille depuis la BDD -------------------

// Récupère depuis la BDD la grille initial et la dernière grille utilisateur
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

// Affichage de la grille avec gestion des cases initiales
function show_grid_mix($pdo){
    $resultat = recover_bdd_grids($pdo);
    
    $init_grid = array_values($resultat['init_grid']);
    $last_grid = array_values($resultat['last_grid']);

    for ($row = 0; $row < 9; $row++){
        $nb_row = $row+1;
        for ($col = 0; $col < 9; $col++){
            $nb_col = $col+1;
            if ($init_grid[$row][$col]==$last_grid[$row][$col]){
                $value = $last_grid[$row][$col];
                if ($value==0 | $value=='0'){ 
                    $value="''"; 
                    echo "<input id=$nb_row$nb_col name=$nb_row$nb_col  type='number' class='cell-input' maxlength='1' min='0' max='9' value=$value>";
                }else{
                    echo "<input id=$nb_row$nb_col name=$nb_row$nb_col  type='number' class='cell-input init-input' maxlength='1' min='0' max='9' value=$value readonly>";
                }
            }else{
                $value = $last_grid[$row][$col];
                if ($value==0 | $value=='0'){ $value="''"; }
                echo "<input id=$nb_row$nb_col  name=$nb_row$nb_col  type='number' class='cell-input' maxlength='1' min='0' max='9' value=$value>";
            }
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



?>