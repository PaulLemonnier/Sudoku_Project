
#########################

from random import shuffle, choice
from copy import deepcopy
from itertools import product


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
    insert_line = choice([i for i in range(9)])
    grid[insert_line] = random_line
    return grid


def show_grid(grid):
    print("######## START GRID ########")
    for line in grid:
        print(line,",")
    print("######### END GRID #########")


def is_empty_cell(grid,x,y):
    return grid[x][y]==0


def list_possibility(grid,x,y):
    possibility = [i+1 for i in range(9)]
    
    for rc in range(9):
        if grid[x][rc] in possibility: # remove number in line
            possibility.remove(grid[x][rc])

        if grid[rc][y] in possibility: # remove number in column
            possibility.remove(grid[rc][y])
    
        corner_x, corner_y = (x//3)*3, (y//3)*3
        for row in range(corner_x,corner_x+3):
            for col in range(corner_y,corner_y+3):
                if grid[row][col] in possibility:
                    possibility.remove(grid[row][col])
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


def humain_logical_resolution(grid):
    grid_play = deepcopy(grid)
    count=0
    number_complete = 0

    while (not is_all_cell_complete(grid_play)) and (count<10):
        count+=1

        # si le nombre n'a pas d'autre possibilité de placement alors je le met
        for test_number in range(1,10):

            # line finding number (vérifie si le nombre test_number à une seule possibilité de placement dans la ligne)
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

            # col finding number (vérifie si le nombre test_number à une seule possibilité de placement dans la colonne)
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

            # col finding number (vérifie si le nombre test_number à une seule possibilité de placement dans la colonne)
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

        
        # finding unique possibility (si la cellule n'a qu'une seule possibilité alors je la met)
        for row in range(9):
            for col in range(9):
                if is_empty_cell(grid_play,row,col):
                    possibility = list_possibility(grid_play,row,col)
                    if len(possibility)==1:
                        number_complete += 1
                        grid_play[row][col] = possibility[0]
                    
    # print(number_complete, is_all_cell_complete(grid_play), count)
    return grid_play


def is_logical_resolvable(grid):
    grid_play = humain_logical_resolution(grid)
    return is_all_cell_complete(grid_play)


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


def create_sudoku(choice_number_cell = 0, logical_resolution=True):
    # Génération de la grille pleine
    grid_main = init_grid()
    solve_grid(grid_main)
    
    coordinate_grid = [(x, y) for x in range(9) for y in range(9)] # Liste de coordonnés correspondant à la grille
    keep_number = 81

    # Crée une variable pour le nombre de coordonnées a laissé en possibilité dans la grille
    number_coordinate_grid_left = 0
    if logical_resolution:
        number_coordinate_grid_left = 20

    # Supprime dans la grille un nombre aléatoire tant que c'est viable et qu'il n'y a pas x nombres supprimés
    while (len(coordinate_grid) > number_coordinate_grid_left):
        grid_play = deepcopy(grid_main)
        random_coord = choice(coordinate_grid) # Sélection aléatoire des coordonnées
        value_grid = grid_main[random_coord[0]][random_coord[1]] # Sauvegarde de la valeur à supprimer
        grid_main[random_coord[0]][random_coord[1]] = 0 # Suppression de la valeur
        

        # Si l'utilisateur souhaite que la grille soit résoluble logiquement sinon la solution peut être supposée
        if logical_resolution:
            if not is_logical_resolvable(grid_main):
                grid_main[random_coord[0]][random_coord[1]] = value_grid
            else :
                keep_number -= 1
        else:
            # S'il existe plus d'une solution dans la nouvelle grille (solution non viable) remet la valeur initiale sinon supprime de la liste les coordonnées
            if count_solve_grid(grid_play) != 1:
                grid_main[random_coord[0]][random_coord[1]] = value_grid
            else :
                keep_number -= 1

        coordinate_grid.remove(random_coord) # supprime les coordonnées une fois la suppression de la valeur ou si la suppression n'est pas viable

        # Si le nombre de case restante est plus grand que le choix du nombre de cellule de l'utilisateur, extrait la grille
        if keep_number <= choice_number_cell : 
            return keep_number, grid_main 

    return keep_number, grid_main


def choose_sudoku_difficulty(difficulty = "Normal"):
    if difficulty == "Extreme" :
        count = 0
        keep_number_min, grid_min = create_sudoku(logical_resolution=False)
        while count < 5 : 
            keep_number, grid = create_sudoku(logical_resolution=False)
            count += 1
            if keep_number < keep_number_min : 
                keep_number_min, grid_min = keep_number, grid
        print(keep_number_min)
        return grid_min
    
    # Else difficulty =  Normal
    keep_number, grid = create_sudoku(logical_resolution=True)
    print(keep_number)
    return grid



#########  EXECUTION  #########


# grille provenant du livre sudoku
grid_1 = [[2,0,0,8,0,0,0,4,0],
          [6,0,7,3,0,0,0,0,0],
          [0,0,4,0,7,0,6,5,0],
          [0,0,2,0,0,0,1,0,0],
          [3,0,0,0,5,2,8,0,0],
          [0,0,8,7,0,1,0,0,2],
          [0,0,5,0,0,0,0,0,9],
          [0,0,0,5,0,0,0,0,0],
          [0,0,0,0,2,0,0,6,8]]

# grille à une solution mais demandant supposition
grid_2 = [[0,0,0,0,0,4,3,5,0],
          [1,3,7,2,0,0,0,0,0],
          [0,0,0,0,0,9,2,0,0],
          [0,0,3,0,0,0,1,6,0],
          [0,0,0,0,5,0,7,0,0],
          [2,6,0,7,0,0,0,0,5],
          [8,0,1,3,0,0,0,0,0],
          [0,2,4,0,9,8,0,1,0],
          [0,5,6,0,0,0,9,0,0]]

# show_grid(humain_logical_resolution(grid_3))


# solve_grid(grid_2)
# show_grid(grid_2)

grid_test = choose_sudoku_difficulty("Normal")
show_grid(grid_test)
print(is_logical_resolvable(grid_test))

# number, test_grid = create_sudoku(logical_resolution=True)
# show_grid(test_grid)

grid_test =[
[0, 2, 0, 0, 8, 0, 6, 0, 0] ,
[4, 0, 0, 3, 1, 0, 2, 5, 0] ,
[1, 6, 0, 7, 9, 0, 0, 0, 0] ,
[0, 1, 0, 5, 3, 0, 0, 0, 0] ,
[0, 0, 0, 0, 0, 0, 0, 0, 0] ,
[6, 0, 3, 0, 0, 0, 0, 0, 0] ,
[5, 0, 0, 0, 0, 0, 0, 0, 1] ,
[7, 0, 0, 0, 0, 8, 0, 0, 6] ,
[0, 0, 0, 0, 7, 0, 4, 3, 0] ,
]

# print(is_logical_resolvable(grid_test))

# print(count_solve_grid(grid_test))