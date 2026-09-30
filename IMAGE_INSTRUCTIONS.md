# Image CAPTCHA

L'image `captcha_image.png` sera générée automatiquement lors du déploiement GitHub Actions ou manuellement avec :

```bash
php generate_captcha.php
```

Si vous utilisez Docker, l'image sera générée au premier lancement.

## Image de démonstration

Le script `generate_captcha.php` crée une image de 800x600 pixels avec :
- Un robot violet caché en bas à droite (cible)
- Des éléments de distraction (texte, lignes, formes)
- Coordonnées cibles : X: 85-93%, Y: 66-82%

Pour utiliser votre propre image :
1. Remplacez `captcha_image.png` par votre image
2. Ajustez les coordonnées dans `verifier.php`
