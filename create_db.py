import sqlite3
import json

conn = sqlite3.connect("db_sudoky.db")

cursor = conn.cursor()

# Suppression de la table
cursor.execute("""DROP TABLE sudoku_grid""")

# Création de la table
cursor.execute("""CREATE TABLE IF NOT EXISTS sudoku_grid(
               line1 TEXT,
               line2 TEXT,
               line3 TEXT,
               line4 TEXT,
               line5 TEXT,
               line6 TEXT,
               line7 TEXT,
               line8 TEXT,
               line9 TEXT
            )
""")

line_data = json.dumps([0,0,0,0,0,0,0,0,0]) 
# print(line_data)

# Insérer des données dans la table
cursor.execute("""INSERT INTO sudoku_grid (line1,line2,line3,line4,line5,line6,line7,line8,line9) VALUES(?,?,?,?,?,?,?,?,?)""",
               (line_data,line_data,line_data,line_data,line_data,line_data,line_data,line_data,line_data))

# Extraction de la donnée
cursor.execute("""SELECT line1,line2,line3,line4,line5,line6,line7,line8,line9 FROM sudoku_grid""")
rows = cursor.fetchall()
# for row in rows:
#     for i in range(9):
#         print(row[i])


conn.commit()

conn.close()



data_as_lists = []
for row in rows:
    # Désérialiser chaque colonne en une liste
    deserialized_row = [json.loads(column) for column in row]

print(deserialized_row)




# conn.rollback()