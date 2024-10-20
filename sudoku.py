
#########################

from random import shuffle, choice
from copy import deepcopy
from itertools import product

def retry_on_timeout(max_duration=4, max_retries=100):
    def decorator(func):
        def wrapper(*args, **kwargs):
            result = [None]
            attempts = 0

            def target():
                try:
                    result[0] = func(*args, **kwargs)
                except Exception as e:
                    result[0] = e

            while attempts < max_retries:
                attempts += 1
                thread = threading.Thread(target=target)
                thread.start()
                thread.join(max_duration)

                if thread.is_alive():
                    print(f"Tentative {attempts}: La fonction {func.__name__} a pris plus de {max_duration} secondes. Relance...")
                    thread = None  # Assurer l'arrêt propre du thread
                else:
                    # Retourner le résultat si l'exécution a réussi dans le délai imparti
                    if isinstance(result[0], Exception):
                        raise result[0]
                    return result[0]

            # Si on atteint le nombre maximum de tentatives, lever une exception
            raise TimeoutError(f"La fonction {func.__name__} n'a pas pu etre completee dans les {max_retries} tentatives.")
        return wrapper
    return decorator


def init_grid():
    grid = [[0,0,0,0,0,0,0,0,0],
            [0,0,0,0,0,0,0,0,0],
            [0,0,0,0,0,0,0,0,0],
            [0,0,0,0,0,0,0,0,0],
            [0,0,0,0,0,0,0,0,0],
            [0,0,0,0,0,0,0,0,0],
            [0,0,0,0,0,0,0,0,0],
            [0,0,0,0,0,0,0,0,0],
            [0,0,0,0,0,0,0,0,0]]
    random_line = [i+1 for i in range(9)]
    shuffle(random_line)
    grid[0] = random_line
    return grid

def show_grid(grid):
    print("###### START GRID ######")
    for line in grid:
        print(line,",")
    print("####### END GRID #######")

def is_empty_cell(grid,x,y):
    return grid[x][y]==0

def list_possibility(grid,x,y):
    possibility = [i+1 for i in range(9)]
    
    for rc in range(9):
        try: # remove number in line
            possibility.remove(grid[x][rc])
        except:
            pass
        try: # remove number in column
            possibility.remove(grid[rc][y])
        except:
            pass  
    
        corner_x, corner_y = (x//3)*3, (y//3)*3
        for row in range(corner_x,corner_x+3):
            for col in range(corner_y,corner_y+3):
                try:
                    possibility.remove(grid[row][col])
                except:
                    pass
    return possibility



def solve_grid(grid):
    for x in range(9):
        for y in range(9):
            if is_empty_cell(grid,x,y):
                possibility = list_possibility(grid,x,y)
                shuffle(possibility)
                for p in possibility:
                    # print(p, " | ", x, y)
                    grid[x][y]=p
                    if solve_grid(grid):
                        return True
                    grid[x][y]=0
                return False
    return True


def is_all_cell_complete(grid):
    for x in range(9):
        for y in range(9):
            if is_empty_cell(grid,x,y):
                return False
    return True


def count_solve_grid(grid):
    solution_count = 0
    for x in range(9):
        for y in range(9):
            if is_empty_cell(grid,x,y):
                possibility = list_possibility(grid,x,y)
                shuffle(possibility)
                for p in possibility:
                    # print(p, " | ", x, y)
                    grid[x][y] = p
                    # show_grid(grid)
                    solution_count += count_solve_grid(grid)
                    if solution_count > 1:
                        break
                    grid[x][y] = 0
                return solution_count
    return 1

# @retry_on_timeout()
def main(choice_number_cell = 81):
    # Génération de la grille pleine
    grid_main = init_grid()
    solve_grid(grid_main)
    
    coordinate_grid = [(x, y) for x in range(9) for y in range(9)] # Liste de coordonnés correspondant à la grille
    keep_number = 81
    # Supprime dans la grille un nombre aléatoire tant que c'est viable et qu'il n'y a pas 50 nombres supprimés
    while len(coordinate_grid) != 0:
        grid_play = deepcopy(grid_main)
        random_coord = choice(coordinate_grid) # Sélection aléatoire des coordonnées
        value_grid = grid_main[random_coord[0]][random_coord[1]] # Sauvegarde de la valeur à supprimer
        grid_main[random_coord[0]][random_coord[1]]=0 # Suppression de la valeur
        # print(random_coord)
        # S'il existe plus d'une solution dans la nouvelle grille (solution non viable) remet la valeur initiale sinon supprime de la liste les coordonnées
        if count_solve_grid(grid_play)!=1:
            grid_main[random_coord[0]][random_coord[1]] = value_grid
        else :
            keep_number -=1

        coordinate_grid.remove(random_coord)

        if keep_number <= choice_number_cell : 
            return keep_number, grid_main 

    return keep_number, grid_main


def choose_sudoku_difficulty(difficulty = "hard"):
    if difficulty == "extreme" :
        count = 0
        keep_number_min, grid_min = main(0)
        while count < 50 : 
            keep_number, grid = main(0)
            count += 1
            if keep_number < keep_number_min : 
                keep_number_min, grid_min = keep_number, grid
        print(keep_number_min)
        return grid_min
    elif difficulty == "hard" :
        value_difficulty = 35
    elif difficulty == "medium" : 
        value_difficulty = 45
    elif difficulty == "easy" : 
        value_difficulty = 60
    else : 
        value_difficulty = 81
        
    count = 0
    keep_number, grid = main(value_difficulty)
    while  keep_number > value_difficulty and count < 100 : 
        keep_number, grid = main(value_difficulty)
        count += 1
    print(keep_number)
    return grid

# grid = choose_sudoku_difficulty("hard")
# show_grid(grid)


grid_2 = [[0,5,9,0,2,0,0,6,0],
          [0,1,0,5,0,7,4,0,0],
          [0,0,0,4,0,6,5,0,1],
          [0,6,0,0,0,0,0,0,0],
          [5,0,4,0,6,0,3,0,8],
          [9,0,0,7,0,0,0,0,0],
          [0,8,0,0,7,4,0,9,2],
          [0,9,2,0,3,0,0,0,4],
          [1,0,6,0,0,9,7,0,0]]

# solve_grid(grid_2)
# show_grid(grid_2)


grid_1 = [[2,0,0,8,0,0,0,4,0],
          [6,0,7,3,0,0,0,0,0],
          [0,0,4,0,7,0,6,5,0],
          [0,0,2,0,0,0,1,0,0],
          [3,0,0,0,5,2,8,0,0],
          [0,0,8,7,0,1,0,0,2],
          [0,0,5,0,0,0,0,0,9],
          [0,0,0,5,0,0,0,0,0],
          [1,0,0,0,2,0,0,6,8]]



def humain_resolution(grid):
    grid_play = deepcopy(grid)
    count=0
    number_complete = 0

    while (not is_all_cell_complete(grid_play)) and (count<1000):
        count+=1

        for test_number in range(1,10):

            # line finding (vérifie si le nombre test_number à une seule possibilité de placement dans la ligne)
            for row in range(9):
                if not (test_number in grid_play[row]):
                    pos_number = []
                    for col in range(9):
                        if is_empty_cell(grid_play,row,col):
                            if test_number in list_possibility(grid_play,row,col):
                                pos_number.append([row,col])
                    if len(pos_number)==1:
                        # print('oui',pos_number[0][0],pos_number[0][1],test_number)
                        number_complete += 1
                        grid_play[pos_number[0][0]][pos_number[0][1]] = test_number

            # col finding (vérifie si le nombre test_number à une seule possibilité de placement dans la colonne)
            for col in range(9):
                if not (test_number in [line[col] for line in grid_play]):
                    pos_number = []
                    for row in range(9):
                        if is_empty_cell(grid_play,row,col):
                            if test_number in list_possibility(grid_play,row,col):
                                pos_number.append([row,col])
                    if len(pos_number) == 1:
                        # print('oui',pos_number[0][0],pos_number[0][1],test_number)
                        number_complete += 1
                        grid_play[pos_number[0][0]][pos_number[0][1]] = test_number

            # col finding (vérifie si le nombre test_number à une seule possibilité de placement dans la colonne)
            for pos_corner in list(product([0,3,6], repeat=2)):
                corner_x, corner_y = pos_corner[0], pos_corner[1]
                list_number_case = []
                for row in range(corner_x,corner_x+3):
                    for col in range(corner_y,corner_y+3):
                        list_number_case.append(grid_play[row][col])
                if not (test_number in list_number_case):
                    pos_number = []
                    for row in range(corner_x,corner_x+3):
                        for col in range(corner_y,corner_y+3):
                            if is_empty_cell(grid_play,row,col):
                                if test_number in list_possibility(grid_play,row,col):
                                    pos_number.append([row,col])
                    if len(pos_number) == 1:
                        # print('oui',pos_number[0][0],pos_number[0][1],test_number)
                        number_complete += 1
                        grid_play[pos_number[0][0]][pos_number[0][1]] = test_number

    print(number_complete, is_all_cell_complete(grid_play))
    return grid_play


show_grid(humain_resolution(grid_2))

