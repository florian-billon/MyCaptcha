"""
Exemple de script Python pour tester le CAPTCHA avec une IA
Utilise requests pour simuler une attaque directe via HTTP
"""

import requests
from bs4 import BeautifulSoup
import random

# Configuration
BASE_URL = "http://localhost:8000"
CAPTCHA_URL = f"{BASE_URL}/captcha.php"
VERIFY_URL = f"{BASE_URL}/verifier.php"

def get_token_and_page():
    """Récupère la page CAPTCHA et extrait le token CSRF"""
    session = requests.Session()
    response = session.get(CAPTCHA_URL)
    
    # Parse le HTML pour extraire le token
    soup = BeautifulSoup(response.text, 'html.parser')
    token_input = soup.find('input', {'name': 'token'})
    
    if token_input:
        token = token_input['value']
        return session, token
    else:
        print("Erreur: Impossible de trouver le token")
        return None, None

def simulate_ai_attack(session, token):
    """Simule une attaque IA en estimant les coordonnées"""
    
    # L'IA "devine" les coordonnées du robot caché
    # Dans un cas réel, l'IA utiliserait un modèle de vision pour analyser l'image
    
    # Coordonnées cibles (basées sur verifier.php: x_min=85, x_max=93, y_min=66, y_max=82)
    target_x = random.uniform(85.0, 93.0)
    target_y = random.uniform(66.0, 82.0)
    
    print(f"🤖 IA estime les coordonnées: X={target_x:.2f}%, Y={target_y:.2f}%")
    
    # Prépare les données POST
    data = {
        'token': token,
        'click_pct_x': target_x,
        'click_pct_y': target_y
    }
    
    # Envoie la requête
    response = session.post(VERIFY_URL, data=data)
    
    # Vérifie le résultat
    if response.status_code == 302:  # Redirect indique une tentative
        print("✅ Tentative envoyée avec succès")
        print(f"📊 Consultez la console de test sur {CAPTCHA_URL}")
    else:
        print(f"❌ Erreur: Status code {response.status_code}")

def main():
    print("🎯 Test IA du CAPTCHA ZenVerify")
    print("=" * 50)
    
    session, token = get_token_and_page()
    
    if session and token:
        print(f"🔑 Token CSRF extrait: {token[:16]}...")
        
        # Simule plusieurs tentatives
        for i in range(1, 4):
            print(f"\n📍 Tentative #{i}")
            simulate_ai_attack(session, token)
            
            # Récupère un nouveau token pour la prochaine tentative
            session, token = get_token_and_page()
            if not session or not token:
                break

if __name__ == "__main__":
    # Prérequis: pip install requests beautifulsoup4
    try:
        import requests
        import bs4
        main()
    except ImportError:
        print("❌ Erreur: Installez les dépendances avec:")
        print("   pip install requests beautifulsoup4")
