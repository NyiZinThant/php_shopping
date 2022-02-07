<?php
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' and empty($_POST['search'])) {
    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
        echo "Invalid CSRF Token";
        die();
    } else {
        unset($_SESSION['csrf']);
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
}

function escape($html)
{
    return htmlspecialchars($html);
}
