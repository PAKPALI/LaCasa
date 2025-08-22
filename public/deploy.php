<?php
// Vérifie que la requête est POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(403);
    exit('Forbidden');
}

// Exécuter le script de déploiement
exec('/home/u151875458/domains/lacasaweb.com/public_html/test/deploy.sh > /dev/null 2>&1 &');

echo "Déploiement lancé.";
//v

// deploy.php de test

// file_put_contents(__DIR__.'/test.log', "Webhook reçu à ".date('Y-m-d H:i:s')."\n", FILE_APPEND);
// echo "Webhook reçu.";

