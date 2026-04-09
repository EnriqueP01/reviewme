import json
from collections import OrderedDict

file_path = r'c:\Users\kikep\Desktop\CodingCours\L2\cours\IA\Projets\IA-Project\ReviewMe\lang\fr.json'

with open(file_path, 'r', encoding='utf-8') as f:
    # On lit le fichier brut pour garder l'ordre des premières apparitions
    lines = f.readlines()
    
# On va reconstruire le dict manuellement pour gérer les doublons sans perdre l'ordre
# Ou simplement utiliser json.load qui prend la dernière valeur en cas de doublon
with open(file_path, 'r', encoding='utf-8') as f:
    data = json.load(f)

# On trie les clés par ordre alphabétique pour la propreté
sorted_data = OrderedDict(sorted(data.items()))

with open(file_path, 'w', encoding='utf-8') as f:
    json.dump(sorted_data, f, ensure_ascii=False, indent=4)

print("Deduplication and sorting complete.")
