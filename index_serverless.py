import requests
from bs4 import BeautifulSoup
import sys

# Récupérer la ville en tant qu'argument de ligne de commande
ville = sys.argv[1]

url = f"https://www.bing.com/search?q=météo+{ville}&form=QBLH&sp=-1&ghc=1&lq=0&pq=météo+{ville}&sc=10-14&qs=n&sk=&cvid=A94421AB031B43C3A6CFA2FAE6ECAAFC&ghsh=0&ghacc=0&ghpl="

payload={}

headers = {
  'User-Agent': 'PostmanRuntime/7.29.2',
  'Cookie': 'MUID=142425C4C29067812CEF3688C3B96669; SRCHD=AF=QBLH; SRCHHPGUSR=SRCHLANG=fr; SRCHUID=V=2&GUID=2DC671511D0946E0B7BB6D46322D9A33&dmnchg=1; SRCHUSR=DOB=20230712; SUID=M; _EDGE_S=F=1&SID=01F58A4C10456FB00F529900116C6ECF; _EDGE_V=1; _SS=SID=01F58A4C10456FB00F529900116C6ECF; MUIDB=142425C4C29067812CEF3688C3B96669'
}

response = requests.request("GET", url, headers=headers, data=payload)
"""
#print(response.text)
fichier = open("newdata.txt", "a")
fichier.write(response.text)
fichier.close()
"""

# Analyser la chaîne HTML avec BeautifulSoup
soup = BeautifulSoup(response.text, 'html.parser')

# Sélectionner la balise div par sa classe
div_element = soup.select_one('.wtr_currTemp')

# Récupérer le contenu texte de la balise div
temperature = div_element.get_text()

# Afficher la valeur de la température
print(temperature)