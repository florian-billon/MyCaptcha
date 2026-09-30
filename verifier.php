<?php
session_start();
date_default_timezone_set('Europe/Paris');

// Action pour effacer les logs de test
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear_logs'])) {
    $_SESSION['test_log'] = [];
    header("Location: captcha.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sécurité anti-CSRF de base
    if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['captcha_token']) {
        die("Erreur de session ou jeton invalide.");
    }

    // Récupération des valeurs en pourcentage envoyées par le client
    $pct_x = isset($_POST['click_pct_x']) ? (float)$_POST['click_pct_x'] : -1;
    $pct_y = isset($_POST['click_pct_y']) ? (float)$_POST['click_pct_y'] : -1;

    /* 
      ZONES DE TOLÉRANCE EN POURCENTAGE (%)
      Sur la base de l'image de Buddha :
      Le robot est tout à fait en bas à droite.
      
      Ajustez ces valeurs selon votre image :
      - x_min, x_max : position horizontale du robot (0% = gauche, 100% = droite)
      - y_min, y_max : position verticale du robot (0% = haut, 100% = bas)
    */
    $x_min = 85.0; 
    $x_max = 93.0;
    $y_min = 66.0;
    $y_max = 82.0;

    // Vérification du clic
    if ($pct_x >= $x_min && $pct_x <= $x_max && $pct_y >= $y_min && $pct_y <= $y_max) {
        $status = "SUCCESS";
        $_SESSION['captcha_success'] = "Succès ! L'IA (ou l'humain) a trouvé le robot caché.";
    } else {
        $status = "FAIL";
        $_SESSION['captcha_error'] = "ERREUR : Clic incorrect. Échec du test de sécurité.";
    }

    // Enregistrement de la tentative dans la console de monitoring
    $_SESSION['test_log'][] = [
        'time' => date('H:i:s'),
        'x' => $pct_x,
        'y' => $pct_y,
        'status' => $status
    ];

    // Régénérer le token pour la prochaine tentative
    $_SESSION['captcha_token'] = bin2hex(random_bytes(32));

    header("Location: captcha.php");
    exit;
}
