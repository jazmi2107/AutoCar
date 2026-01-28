<?php
// Debugging Vercel Environment
if (isset($_GET['debug_vars'])) {
    header('Content-Type: text/plain');
    echo "--- SERVER KEYS ---\n";
    echo implode(', ', array_keys($_SERVER)) . "\n\n";
    echo "--- ENV KEYS ---\n";
    echo implode(', ', array_keys($_ENV)) . "\n\n";
    echo "--- GETENV CHECK ---\n";
    echo "FIREBASE_API_KEY: " . (getenv('FIREBASE_API_KEY') ? 'EXISTS' : 'NOT FOUND') . "\n";
    echo "APP_KEY: " . (getenv('APP_KEY') ? 'EXISTS' : 'NOT FOUND') . "\n";
    exit;
}

require __DIR__ . '/../public/index.php';
