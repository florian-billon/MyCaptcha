<?php
session_start();

// Initialisation du jeton de sécurité
if (empty($_SESSION['captcha_token'])) {
    $_SESSION['captcha_token'] = bin2hex(random_bytes(32));
}

// Initialisation d'un historique des tests pour analyser le comportement de l'IA
if (!isset($_SESSION['test_log'])) {
    $_SESSION['test_log'] = [];
}

$error_message = isset($_SESSION['captcha_error']) ? $_SESSION['captcha_error'] : '';
$success_message = isset($_SESSION['captcha_success']) ? $_SESSION['captcha_success'] : '';
unset($_SESSION['captcha_error'], $_SESSION['captcha_success']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZenVerify - IA Test Bench</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #1e1e24;
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }
        .container {
            max-width: 600px;
            width: 100%;
            background: #2a2a35;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.3);
            text-align: center;
        }
        h2 { margin-top: 0; color: #4caf50; }
        
        /* Conteneur de l'image responsive */
        .captcha-wrapper {
            position: relative;
            width: 100%;
            margin: 15px 0;
            cursor: crosshair;
        }
        .captcha-wrapper img {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 6px;
            border: 2px solid #3f3f52;
        }

        .status {
            font-weight: bold;
            padding: 10px;
            border-radius: 6px;
            margin: 15px 0;
        }
        .error { background-color: #8b0000; color: #fff; }
        .success { background-color: #006400; color: #fff; }

        /* Panneau de monitoring pour l'IA */
        .log-panel {
            margin-top: 30px;
            max-width: 600px;
            width: 100%;
            background: #111;
            padding: 15px;
            border-radius: 8px;
            font-family: monospace;
            text-align: left;
            box-sizing: border-box;
        }
        .log-panel h3 { margin: 0 0 10px 0; font-size: 14px; color: #aaa; text-transform: uppercase; }
        .log-entry { padding: 5px 0; border-bottom: 1px solid #222; font-size: 12px; }
        .log-success { color: #4caf50; }
        .log-fail { color: #f44336; }
        .clear-btn {
            background: #444; color: #fff; border: none; padding: 5px 10px; 
            cursor: pointer; border-radius: 4px; font-size: 11px; float: right;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>ZenVerify v42.1 (Anti-IA)</h2>
    <p style="color: #ccc; font-size: 14px;">Cliquez sur la zone secrète pour valider.</p>

    <?php if ($error_message): ?>
        <div class="status error"><?php echo htmlspecialchars($error_message); ?></div>
    <?php endif; ?>
    <?php if ($success_message): ?>
        <div class="status success"><?php echo htmlspecialchars($success_message); ?></div>
    <?php endif; ?>

    <!-- Zone cliquable du CAPTCHA -->
    <div class="captcha-wrapper" id="captchaZone">
        <img src="captcha_image.png" alt="Captcha ZenVerify">
    </div>

    <!-- Formulaire invisible envoyé par le script après le clic -->
    <form id="captchaForm" action="verifier.php" method="POST" style="display:none;">
        <input type="hidden" name="token" value="<?php echo $_SESSION['captcha_token']; ?>">
        <input type="hidden" name="click_pct_x" id="pctX">
        <input type="hidden" name="click_pct_y" id="pctY">
    </form>
</div>

<!-- Console de monitoring pour voir les essais de l'IA -->
<div class="log-panel">
    <form action="verifier.php" method="POST" style="display:inline;">
        <input type="hidden" name="clear_logs" value="1">
        <button type="submit" class="clear-btn">Effacer l'historique</button>
    </form>
    <h3>Console de test IA</h3>
    <div style="max-height: 200px; overflow-y: auto;">
        <?php if (empty($_SESSION['test_log'])): ?>
            <span style="color: #666;">Aucune tentative enregistrée. En attente d'un clic (ou d'un script d'IA)...</span>
        <?php else: ?>
            <?php foreach (array_reverse($_SESSION['test_log']) as $log): ?>
                <div class="log-entry <?php echo $log['status'] === 'SUCCESS' ? 'log-success' : 'log-fail'; ?>">
                    [<?php echo $log['time']; ?>] 
                    Clic détecté à <strong>X: <?php echo $log['x']; ?>%</strong>, <strong>Y: <?php echo $log['y']; ?>%</strong> 
                    -> <strong><?php echo $log['status']; ?></strong>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
document.getElementById('captchaZone').addEventListener('click', function(e) {
    // Récupère les dimensions réelles affichées de l'image au moment du clic
    const rect = this.getBoundingClientRect();
    
    // Calcule la position exacte du clic en pixels par rapport à l'image
    const xPixels = e.clientX - rect.left;
    const yPixels = e.clientY - rect.top;
    
    // Convertit ces pixels en pourcentages (0% à 100%)
    const pctX = (xPixels / rect.width) * 100;
    const pctY = (yPixels / rect.height) * 100;
    
    // Injecte les pourcentages dans le formulaire caché et l'envoie
    document.getElementById('pctX').value = pctX.toFixed(2);
    document.getElementById('pctY').value = pctY.toFixed(2);
    document.getElementById('captchaForm').submit();
});
</script>

</body>
</html>
