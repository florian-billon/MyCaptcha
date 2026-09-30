<?php
/**
 * Script pour générer une image CAPTCHA de démonstration
 * Exécutez ce script une fois pour créer captcha_image.png
 */

// Créer une image de 800x600
$width = 800;
$height = 600;
$image = imagecreatetruecolor($width, $height);

// Couleurs
$bg_color = imagecolorallocate($image, 30, 30, 36);       // Fond sombre
$text_color = imagecolorallocate($image, 255, 255, 255); // Texte blanc
$robot_color = imagecolorallocate($image, 156, 39, 176);  // Robot violet
$deco_color = imagecolorallocate($image, 76, 175, 80);   // Décoration verte

// Remplir le fond
imagefill($image, 0, 0, $bg_color);

// Ajouter du texte de distraction
$font_size = 5;
for ($i = 0; $i < 10; $i++) {
    $x = rand(50, $width - 50);
    $y = rand(50, $height - 50);
    $angle = rand(-30, 30);
    $text = str_shuffle("ABCDEFGHJKLMNPQRSTUVWXYZ23456789");
    imagestring($image, $font_size, $x, $y, substr($text, 0, 5), $deco_color);
}

// Dessiner des formes de distraction
for ($i = 0; $i < 5; $i++) {
    $x1 = rand(0, $width);
    $y1 = rand(0, $height);
    $x2 = rand(0, $width);
    $y2 = rand(0, $height);
    imageline($image, $x1, $y1, $x2, $y2, $deco_color);
}

// Dessiner le robot caché (cible) en bas à droite
// Position: environ 85-93% en X, 66-82% en Y
$robot_x = $width * 0.89;
$robot_y = $height * 0.74;
$robot_size = 40;

// Corps du robot (cercle)
imagefilledellipse($image, $robot_x, $robot_y, $robot_size, $robot_size, $robot_color);

// Yeux du robot
$eye_size = 8;
imagefilledellipse($image, $robot_x - 10, $robot_y - 5, $eye_size, $eye_size, 255, 255, 255);
imagefilledellipse($image, $robot_x + 10, $robot_y - 5, $eye_size, $eye_size, 255, 255, 255);

// Pupilles
imagefilledellipse($image, $robot_x - 10, $robot_y - 5, 3, 3, 0, 0, 0);
imagefilledellipse($image, $robot_x + 10, $robot_y - 5, 3, 3, 0, 0, 0);

# Bouche (arc)
imagearc($image, $robot_x, $robot_y + 5, 20, 10, 0, 180, 255, 255, 255);

# Antennes
imageline($image, $robot_x - 5, $robot_y - 20, $robot_x - 10, $robot_y - 30, $robot_color);
imageline($image, $robot_x + 5, $robot_y - 20, $robot_x + 10, $robot_y - 30, $robot_color);
imagefilledellipse($image, $robot_x - 10, $robot_y - 32, 6, 6, $robot_color);
imagefilledellipse($image, $robot_x + 10, $robot_y - 32, 6, 6, $robot_color);

// Ajouter un titre
$title = "ZenVerify CAPTCHA - Cliquez sur le robot caché";
$title_x = ($width - imagefontwidth(5) * strlen($title)) / 2;
imagestring($image, 5, $title_x, 20, $title, $text_color);

// Sauvegarder l'image
imagepng($image, 'captcha_image.png');
imagedestroy($image);

echo "✅ Image CAPTCHA générée: captcha_image.png\n";
echo "🤖 Le robot violet se trouve en bas à droite de l'image\n";
echo "📍 Coordonnées approximatives: X: 85-93%, Y: 66-82%\n";
