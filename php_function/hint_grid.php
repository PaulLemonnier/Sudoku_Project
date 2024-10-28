
<?php include 'sudoku_basic_function.php'; ?>

<?php

// Si le bouton Astuce est pressé génère affiche le prochain move
if (isset($_POST['grid_for_hint'])) {
        
    $actual_grid = json_decode($_POST['grid_for_hint'], true);

    $array_next_move = next_move_resolution($actual_grid);
    echo strval($array_next_move[1]+1).strval($array_next_move[2]+1);

}







?>
