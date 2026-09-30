# ZenVerify - CAPTCHA avec Test IA

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP](https://img.shields.io/badge/PHP-7.0%2B-blue.svg)](https://php.net)

Un système CAPTCHA responsive pour tester des IA sur la reconnaissance d'objets dans les images.

## 📁 Structure du projet

- `captcha.php` - Interface utilisateur responsive avec console de monitoring
- `verifier.php` - Script de validation côté serveur (basé sur des pourcentages)
- `captcha_image.png` - Votre image CAPTCHA (à ajouter)
- `generate_captcha.php` - Script pour générer une image de démonstration
- `Dockerfile` - Configuration Docker pour déploiement
- `docker-compose.yml` - Configuration Docker Compose
- `ai_test_example.py` - Exemple de script Python pour tester avec une IA

## 🚀 Installation rapide

### Option 1: Docker (Recommandé)

```bash
# Cloner le repository
git clone https://github.com/votre-username/MyCaptcha.git
cd MyCaptcha

# Générer l'image de démonstration
php generate_captcha.php

# Lancer avec Docker Compose
docker-compose up -d

# Accéder à http://localhost:8080/captcha.php
```

### Option 2: PHP Built-in Server

```bash
# Cloner le repository
git clone https://github.com/votre-username/MyCaptcha.git
cd MyCaptcha

# Générer l'image de démonstration
php generate_captcha.php

# Lancer le serveur
php -S localhost:8000

# Accéder à http://localhost:8000/captcha.php
```

### Option 3: Apache/Nginx

1. Placez ce projet dans un dossier accessible par votre serveur web
2. Configurez votre serveur pour pointer vers le dossier du projet
3. Assurez-vous que PHP 7.0+ est installé
4. Générez l'image avec `php generate_captcha.php`

## ⚙️ Configuration

Dans `verifier.php`, ajustez les zones de tolérance en pourcentage :

```php
$x_min = 85.0; 
$x_max = 93.0;
$y_min = 66.0;
$y_max = 82.0;
```

Pour trouver les coordonnées exactes de votre image :
1. Ajoutez temporairement dans `verifier.php` :
   ```php
   echo "X: $pct_x, Y: $pct_y"; die();
   ```
2. Cliquez sur la zone secrète de votre image
3. Notez les pourcentages affichés
4. Ajustez les variables `$x_min`, `$x_max`, `$y_min`, `$y_max` avec une marge de tolérance

## 🎨 Génération d'image personnalisée

Utilisez le script `generate_captcha.php` pour créer une image de démonstration :

```bash
php generate_captcha.php
```

Ou créez votre propre image `captcha_image.png` avec votre objet caché.

## 🧪 Tester avec une IA

### Via navigateur automatisé (Selenium/Puppeteer/Playwright)

L'IA doit :
1. Charger `captcha.php`
2. Analyser visuellement l'image avec un modèle de vision
3. Repérer l'objet caché
4. Calculer ses coordonnées relatives en pourcentage
5. Exécuter un clic natif sur l'image à cet endroit

### Via requêtes HTTP directes

Utilisez le script d'exemple fourni :

```bash
pip install requests beautifulsoup4
python ai_test_example.py
```

L'IA doit :
1. Extraire le `token` du HTML de `captcha.php`
2. Envoyer un POST à `verifier.php` avec :
   - `token`: valeur extraite
   - `click_pct_x`: valeur entre x_min et x_max
   - `click_pct_y`: valeur entre y_min et y_max

## 📊 Console de monitoring

La page affiche en temps réel :
- L'historique de toutes les tentatives
- Les coordonnées X/Y en pourcentage de chaque clic
- Le statut (SUCCESS/FAIL)
- L'heure de chaque tentative

## 🔒 Sécurité

- Validation côté serveur uniquement
- Token CSRF pour éviter les soumissions répétées
- Coordonnées en pourcentage pour compatibilité responsive
- Régénération du token après chaque tentative

## 🛠️ Prérequis

- PHP 7.0 ou supérieur
- Serveur web (Apache, Nginx, ou PHP built-in server)
- Extension GD (optionnel, pour manipulation d'images)
- Docker (si utilisation de l'option Docker)

## 🌐 Déploiement sur GitHub Pages

Ce projet peut être déployé sur :
- **GitHub Pages** (via GitHub Actions avec PHP)
- **Vercel** (avec configuration PHP)
- **Heroku** (avec buildpack PHP)
- **Tout serveur web avec PHP**

## 📝 Licence

Ce projet est sous licence MIT - voir le fichier [LICENSE](LICENSE) pour plus de détails.

## 🤝 Contribution

Les contributions sont les bienvenues ! N'hésitez pas à :
- Signaler des bugs
- Proposer des améliorations
- Soumettre des pull requests

## 📧 Contact

Pour toute question, ouvrez une issue sur GitHub.
