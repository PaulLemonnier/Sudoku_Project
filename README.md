# Sudoku_Project

## Structuration du projet :

Sudoku.php : Fichiers initiale de la page internet.

**Database** :
Contient la base de donnée SQLite (.db) ainsi que le fichier python permettant la création de la base ainsi qu'un requêtage simplifié à la base de données.

**php_function** :
Contient les fichiers PHP regroupant les différentes fonctions utilisées dans la page.

* sudoku_basic_function.php : Fonctions basiques du sudoku indépendantes de la page internet (création d'une grille, résolution d'une grille, création mode de jeu, comptage de solution).
* interaction_bdd_function.php : Fonctions permettant l'intéraction avec la base de données (récupération de la grille dans la bdd, affichage de la grille dans la page, sauvegarde la grille dans la bdd, génération et insertion d'une nouvelle grille dans la bdd).
* validate_grid.php : Fonction appelée lors de la validation de la grille (via AJAX).

**python_sudoku** :
Contient le code Python initiatique du projet permettant de générer des grilles de sudoku dans un fichier PDF. 

