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
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
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


</style>



<head>
    <title>Sudoku</title>
</head>

<header>

<header class="header">
    <div class="container">
        <h1>MySudoku</h1>
        <nav>
            <ul>
                <li><button>Generate</button></li>
                <li><button>Reset</button></li>
            </ul>
        </nav>
    </div>
</header>



<body>
    
    <div style='text-align: center;margin-bottom:10px;'>
        <button>Submit</button>
    </div>

    <div class="sudoku-grid">
        

        <!-- Générer les 81 cases de la grille -->
        <input type="number" class="cell-input" maxlength="1" min="0" max="9">
        <input type="text" class="cell-input" maxlength="1">
        <input type="text" class="cell-input" maxlength="1">
        <input type="text" class="cell-input" maxlength="1">
        <input type="text" class="cell-input" maxlength="1">
        <input type="text" class="cell-input" maxlength="1">
        <input type="text" class="cell-input" maxlength="1">
        <input type="text" class="cell-input" maxlength="1">
        <input type="text" class="cell-input" maxlength="1">
        <input type="text" class="cell-input" maxlength="1">
        <input type="text" class="cell-input" maxlength="1">
        <input type="text" class="cell-input" maxlength="1">
        <input type="text" class="cell-input" maxlength="1">
        <input type="text" class="cell-input" maxlength="1">
        <input type="text" class="cell-input" maxlength="1">
        <input type="text" class="cell-input" maxlength="1">
        <input type="text" class="cell-input" maxlength="1">
        <input type="text" class="cell-input" maxlength="1">
        <input type="text" class="cell-input" maxlength="1">
        <input type="text" class="cell-input" maxlength="1">
        <input type="text" class="cell-input" maxlength="1">
        <input type="text" class="cell-input" maxlength="1">
        <input type="text" class="cell-input" maxlength="1">
        <input type="text" class="cell-input" maxlength="1">
        <input type="text" class="cell-input" maxlength="1">
        <input type="text" class="cell-input" maxlength="1">
        <input type="text" class="cell-input" maxlength="1">
        <input type="text" class="cell-input" maxlength="1">
        <input type="text" class="cell-input" maxlength="1">
        <input type="text" class="cell-input" maxlength="1">
        

    </div>
</body>
</html>