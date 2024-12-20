import sqlite3
import json

conn = sqlite3.connect("Database/grid_sudoku.db")

cursor = conn.cursor()

# Suppression de la table
# cursor.execute("""DROP TABLE sudoku_grid""")
# cursor.execute("""DROP TABLE score""")

# Création de la table
# cursor.execute("""CREATE TABLE IF NOT EXISTS sudoku_grid(
#                id INTEGER PRIMARY KEY AUTOINCREMENT,
#                line1 TEXT,
#                line2 TEXT,
#                line3 TEXT,
#                line4 TEXT,
#                line5 TEXT,
#                line6 TEXT,
#                line7 TEXT,
#                line8 TEXT,
#                line9 TEXT
#             )
# """)

# cursor.execute("""CREATE TABLE IF NOT EXISTS score(point INTEGER, session TEXT, error TEXT)""")

# line_data = json.dumps([0,0,0,0,0,0,0,0,0]) 
# print(line_data)

# Insérer des données dans la table
# cursor.execute("""INSERT INTO sudoku_grid (line1,line2,line3,line4,line5,line6,line7,line8,line9) VALUES(?,?,?,?,?,?,?,?,?)""",
#                (line_data,line_data,line_data,line_data,line_data,line_data,line_data,line_data,line_data))

# line_error= json.dumps([0,0]) 
# cursor.execute("""INSERT INTO score (point, session, error) VALUES(?,?,?)""",
#                (8,'START',line_error))


# Extraction de la donnée
cursor.execute("""SELECT * FROM score LIMIT 10;""")
rows = cursor.fetchall()
# for row in rows:
#     for i in range(9):
#         print(row[i])

# print(len(rows))
print(rows)
# print(rows[0][1])

conn.commit()

conn.close()



# Désérialiser chaque colonne en une liste
# deserialized_row = [json.loads(column) for column in rows[0]]
# print(deserialized_row)




# conn.rollback()