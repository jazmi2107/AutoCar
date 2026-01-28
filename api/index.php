<?php
// Debugging Vercel Environment
if (isset($_GET['debug_vars'])) {
    echo "Available keys in \$_SERVER: " . implode(', ', array_keys($_SERVER));
    exit;
}

require __DIR__ . '/../public/index.php';
