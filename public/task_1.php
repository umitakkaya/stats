<?php

declare(strict_types = 1);

use SmAssignment\User;

require __DIR__ . '/../vendor/autoload.php';

$user = new User(null, 'Tony Montana');

try {
    $pdo = new PDO('mysql:host=db;port=3306;dbname=assignment', 'root', 'root');
}
catch (Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}

/**
 * INSTRUCTIONS
 * ------------
 *
 * Make the sprintf() below return the actual ID of the added user.
 *
 * The ID must be the one given by the database, so we'd expect the output to
 * change every time the script is executed.
 *
 * Note: It's ok to add the same name to the database again and again.
 */

echo sprintf(
    'Say hello to my little friend!%s  - %s (ID: %d)',
    PHP_EOL . PHP_EOL,
    $user->getName(),
    $user->getId() ?? 0
);

echo PHP_EOL;
