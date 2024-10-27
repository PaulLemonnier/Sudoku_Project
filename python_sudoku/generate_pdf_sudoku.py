from reportlab.lib.pagesizes import A4
from reportlab.pdfgen import canvas


def dessiner_grille_sudoku(pdf, taille_case=50, marge=40):
    # Définir les dimensions du PDF
    largeur_page, hauteur_page = A4

    # Calculer la position de départ pour centrer la grille
    position_x = (largeur_page - taille_case * 9) / 2
    position_y = (hauteur_page - taille_case * 9) / 2

    # Dessiner les lignes de la grille
    for i in range(10):
        epaisseur = 2 if i % 3 == 0 else 1
        # Lignes horizontales
        pdf.setLineWidth(epaisseur)
        pdf.line(position_x, position_y + i * taille_case,
                 position_x + 9 * taille_case, position_y + i * taille_case)
        # Lignes verticales
        pdf.line(position_x + i * taille_case, position_y,
                 position_x + i * taille_case, position_y + 9 * taille_case)

def ajouter_valeurs_sudoku(pdf, grille, taille_case=50):
    # Définir les dimensions du PDF
    largeur_page, hauteur_page = A4

    # Calculer la position de départ pour centrer la grille
    position_x = (largeur_page - taille_case * 9) / 2
    position_y = (hauteur_page - taille_case * 9) / 2

    # Ajouter les valeurs dans les cases
    pdf.setFont("Helvetica", 24)
    for i in range(9):
        for j in range(9):
            valeur = grille[i][j]
            if valeur != 0:  # Si la case n'est pas vide
                x = position_x + j * taille_case + taille_case / 3
                y = position_y + (8 - i) * taille_case + taille_case / 4
                pdf.drawString(x, y, str(valeur))

def ajouter_titre(pdf, titre):
    # Définir les dimensions du PDF
    largeur_page, hauteur_page = A4

    # Ajouter le titre au centre en haut de la page
    pdf.setFont("Helvetica-Bold", 28)
    pdf.drawCentredString(largeur_page / 2, hauteur_page - 50, titre)

def creer_pdf_sudoku(nom_fichier, grille, titre="Sudoku"):
    # Créer un objet canvas pour générer le PDF
    pdf = canvas.Canvas(nom_fichier, pagesize=A4)

    # Ajouter le titre, dessiner la grille de Sudoku et ajouter les valeurs
    ajouter_titre(pdf, titre)
    dessiner_grille_sudoku(pdf)
    ajouter_valeurs_sudoku(pdf, grille)

    # Sauvegarder et fermer le PDF
    pdf.save()

# Exemple de grille de Sudoku (0 représente une case vide)
grille_sudoku = [
[0, 2, 4, 0, 0, 7, 1, 0, 9] ,
[0, 0, 0, 8, 0, 1, 0, 0, 0] ,
[0, 0, 0, 0, 0, 0, 7, 6, 5] ,
[0, 8, 0, 0, 0, 9, 6, 0, 1] ,
[3, 0, 0, 0, 6, 0, 0, 0, 0] ,
[0, 0, 0, 0, 0, 2, 4, 0, 0] ,
[0, 0, 0, 0, 2, 0, 0, 0, 0] ,
[0, 1, 0, 0, 0, 6, 9, 0, 8] ,
[0, 9, 0, 0, 1, 0, 3, 4, 6] ,]

# Générer le fichier PDF
id_sudoku = ''.join(map(str,grille_sudoku[0]))

creer_pdf_sudoku(f"sudoku_test_{id_sudoku}.pdf", grille_sudoku, f"Sudoku {id_sudoku}")
