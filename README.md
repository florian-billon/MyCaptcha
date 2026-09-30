# ZenVerify - CAPTCHA IA

Page web simple avec un CAPTCHA conçu pour tester des IA. Version HTML/JS pure pour GitHub Pages.

## 📁 Fichiers

- `index.html` - Page principale avec instruction IA et message de succès
- `captcha_image.png` - Image du CAPTCHA

## 🚀 Déploiement sur GitHub Pages

1. Créez un repository sur GitHub
2. Uploadez les fichiers
3. Activez GitHub Pages dans Settings → Pages
4. Choisissez la branche main
5. Votre site sera accessible à `https://votre-username.github.io/MyCaptcha/`

## 🤖 Pour l'IA

L'IA doit :
1. Charger la page `index.html`
2. Analyser l'image pour trouver le robot violet
3. Calculer les coordonnées en pourcentage (X: 85-93%, Y: 66-82%)
4. Simuler un clic sur l'image à ces coordonnées

La validation se fait côté client en JavaScript - les résultats sont visibles dans la console du navigateur et sur la page.
