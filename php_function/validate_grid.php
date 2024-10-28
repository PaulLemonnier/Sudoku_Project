
<?php include 'sudoku_basic_function.php'; ?>

<?php


function database_connection($db_path_name){
    #'db_sudoky.db';
    try {
        $pdo = new PDO("sqlite:$db_path_name"); // Connexion à la base de données SQLite        
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Configuration pour générer des exceptions en cas d'erreur
    } catch (PDOException $e) { // Gestion des erreurs
        echo "Erreur de connexion ou d'exécution : " . $e->getMessage();
    }
    return $pdo;
}


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


function update_bdd_point($pdo){
    $sql_recover_point = "SELECT point FROM score LIMIT 1"; // Requête SQL à exécuter
    $stmt = $pdo->query($sql_recover_point); // Exécution de la requête
    $point = $stmt->fetch(PDO::FETCH_ASSOC)['point']; // Récupère les points

    $sql_update = "UPDATE score SET point=$point+1 WHERE point = (SELECT point FROM score LIMIT 1)";
    $stmt = $pdo->prepare($sql_update);
    $stmt->execute(); // Exécuter la requête en passant les paramètres
}



if (isset($_POST['ajax_grid_data'])) {
    $pdo = database_connection('../Database/db_sudoky.db'); // connexion à la BDD
    $grid_to_bdd = json_decode($_POST['ajax_grid_data'], true); // Récupération de la grille actuelle (from JS)
    $resultat = recover_bdd_grids($pdo); //récupère les grilles
    $init_grid = array_values($resultat['init_grid']); //récupère la grille initiale
    solve_grid($init_grid); //transforme la grille initial en grille solution

    if (compare_grid($init_grid, $grid_to_bdd)) {
        echo "<span style='font-weight:bold;color:#34c434'>Bravo ! +1</span>";
        update_bdd_point($pdo);
    } else {
        echo "<span style='font-weight:bold;color:#f42e35'>Essaye encore !</span>";
    }

}







?>
